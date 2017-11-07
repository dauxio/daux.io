<?php namespace Todaymade\Daux\Format\Confluence;

use GuzzleHttp\Exception\BadResponseException;
use Todaymade\Daux\Console\RunAction;

class Publisher
{
    use RunAction;

    /**
     * @var Api
     */
    protected $client;

    /**
     * @var array
     */
    protected $confluence;

    /**
     * @var int terminal width
     */
    public $width;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    public $output;

    /**
     * @param $confluence
     */
    public function __construct($confluence)
    {
        $this->confluence = $confluence;

        $this->client = new Api($confluence['base_url'], $confluence['user'], $confluence['pass']);
        $this->client->setSpace($confluence['space_id']);
    }

    public function run($title, $closure)
    {
        try {
            return $this->runAction($title, $this->output, $this->width, $closure);
        } catch (BadResponseException $e) {
            $this->output->writeLn('<fg=red>' . $e->getMessage() . '</>');
        }
    }

    public function publish(array $tree)
    {
        $this->output->writeLn('Finding Root Page...');
        $published = $this->getRootPage($tree);

        $this->run(
            'Getting already published pages...',
            function () use (&$published) {
                if ($published != null) {
                    $published['children'] = $this->client->getList($published['id'], true);
                }
            }
        );

        $published = $this->run(
            "Create placeholder pages...",
            function () use ($tree, $published) {
                return $this->createRecursive($this->confluence['ancestor_id'], $tree, $published);
            }
        );

        $this->output->writeLn('Publishing updates...');
        $published = $this->updateRecursive($this->confluence['ancestor_id'], $tree, $published);

        $shouldDelete = array_key_exists('delete', $this->confluence) && $this->confluence['delete'];
        $delete = new PublisherDelete($this->output, $shouldDelete, $this->client);
        $delete->handle($published);
    }

    protected function getRootPage($tree)
    {
        if (array_key_exists('ancestor_id', $this->confluence)) {
            $pages = $this->client->getList($this->confluence['ancestor_id']);
            foreach ($pages as $page) {
                if ($page['title'] == $tree['title']) {
                    return $page;
                }
            }
        }

        if (array_key_exists('root_id', $this->confluence)) {
            $published = $this->client->getPage($this->confluence['root_id']);
            $this->confluence['ancestor_id'] = $published['ancestor_id'];
            return $published;
        }

        throw new \RuntimeException('You must at least specify a `root_id` or `ancestor_id` in your confluence configuration.');
    }

    protected function createPage($parent_id, $entry, $published)
    {
        echo '- ' . PublisherUtilities::niceTitle($entry['file']->getUrl()) . "\n";
        $published['version'] = 1;
        $published['title'] = $entry['title'];
        $published['id'] = $this->client->createPage($parent_id, $entry['title'], 'The content will come very soon !');

        return $published;
    }

    protected function createPlaceholderPage($parent_id, $entry, $published)
    {
        echo '- ' . $entry['title'] . "\n";
        $published['version'] = 1;
        $published['title'] = $entry['title'];
        $published['id'] = $this->client->createPage($parent_id, $entry['title'], '');

        return $published;
    }

    protected function recursiveWithCallback($parent_id, $entry, $published, $callback)
    {
        $published = $callback($parent_id, $entry, $published);

        if (!array_key_exists('children', $entry)) {
            return $published;
        }

        foreach ($entry['children'] as $child) {
            $pub = [];
            if (isset($published['children']) && array_key_exists($child['title'], $published['children'])) {
                $pub = $published['children'][$child['title']];
            }

            $published['children'][$child['title']] = $this->recursiveWithCallback(
                $published['id'],
                $child,
                $pub,
                $callback
            );
        }

        return $published;
    }

    protected function createRecursive($parent_id, $entry, $published)
    {
        $callback = function ($parent_id, $entry, $published) {
            // nothing to do if the ID already exists
            if (array_key_exists('id', $published)) {
                return $published;
            }

            if (array_key_exists('page', $entry)) {
                return $this->createPage($parent_id, $entry, $published);
            }

            // If we have no $entry['page'] it means the page
            // doesn't exist, but to respect the hierarchy,
            // we need a blank page there
            return $this->createPlaceholderPage($parent_id, $entry, $published);
        };

        return $this->recursiveWithCallback($parent_id, $entry, $published, $callback);
    }

    protected function updateRecursive($parent_id, $entry, $published)
    {
        $callback = function ($parent_id, $entry, $published) {
            if (array_key_exists('id', $published) && array_key_exists('page', $entry)) {
                $this->updatePage($parent_id, $entry, $published);
            }
            $published['needed'] = true;

            return $published;
        };

        return $this->recursiveWithCallback($parent_id, $entry, $published, $callback);
    }

    protected function updatePage($parent_id, $entry, $published)
    {
        $updateThreshold = array_key_exists('update_threshold', $this->confluence) ? $this->confluence['update_threshold'] : 2;

        $this->run(
            '- ' . PublisherUtilities::niceTitle($entry['file']->getUrl()),
            function () use ($entry, $published, $parent_id, $updateThreshold) {
                $generated_content = $entry['page']->getContent();
                if (PublisherUtilities::shouldUpdate($entry['page'], $generated_content, $published, $updateThreshold)) {
                    $this->client->updatePage(
                        $parent_id,
                        $published['id'],
                        $published['version'] + 1,
                        $entry['title'],
                        $generated_content
                    );
                }
            }
        );

        if (count($entry['page']->attachments)) {
            foreach ($entry['page']->attachments as $attachment) {
                $this->run(
                    "  With attachment: $attachment[filename]",
                    function ($write) use ($published, $attachment) {
                        $this->client->uploadAttachment($published['id'], $attachment, $write);
                    }
                );
            }
        }
    }
}
