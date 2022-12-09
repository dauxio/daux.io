<?php namespace Todaymade\Daux\Format\Confluence;

use GuzzleHttp\Exception\BadResponseException;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Console\RunAction;

class Publisher
{
    use RunAction;

    public int $width;

    public OutputInterface $output;

    protected Api $client;

    protected Config $confluence;

    public function __construct(Config $confluence)
    {
        $this->confluence = $confluence;

        $this->client = new Api($confluence->getBaseUrl(), $confluence->getUser(), $confluence->getPassword());
    }

    public function run($title, $closure)
    {
        try {
            return $this->runAction($title, $this->width, $closure);
        } catch (BadResponseException $e) {
            $this->output->writeLn('<fg=red>' . $e->getMessage() . '</>');
        }
    }

    public function diff($local, $remote, $level)
    {
        if ($remote == null) {
            $this->output->writeLn("$level- " . $local['title'] . ' <fg=green>(create)</>');
        } elseif ($local == null) {
            $this->output->writeLn("$level- " . $remote['title'] . ' <fg=red>(delete)</>');
        } else {
            $this->output->writeLn("$level- " . $local['title'] . ' <fg=blue>(update)</>');
        }

        if ($local && array_key_exists('children', $local)) {
            $remoteChildren = $remote && array_key_exists('children', $remote) ? $remote['children'] : [];
            foreach ($local['children'] as $title => $content) {
                $this->diff($content, array_key_exists($title, $remoteChildren) ? $remoteChildren[$title] : null, "$level  ");
            }
        }

        if ($remote && array_key_exists('children', $remote)) {
            $localChildren = $local && array_key_exists('children', $local) ? $local['children'] : [];
            foreach ($remote['children'] as $title => $content) {
                if (!array_key_exists($title, $localChildren)) {
                    $this->diff(null, $content, "$level  ");
                }
            }
        }
    }

    public function publish(array $tree)
    {
        $this->output->writeLn('Finding Root Page...');
        $published = $this->getRootPage($tree);

        $ancestor_id = $published['ancestor_id'];

        // We infer the Space from the root page
        $this->client->setSpace($published['space_key']);
        $this->confluence->setSpaceId($published['space_key']);

        $this->run(
            'Getting already published pages...',
            function () use (&$published) {
                if ($published != null) {
                    $published['children'] = $this->client->getList($published['id'], true);
                }
            }
        );

        if ($this->confluence->shouldPrintDiff()) {
            $this->output->writeLn('The following changes will be applied');
            $this->diff($tree, $published, '');

            return;
        }

        $published = $this->run(
            'Create placeholder pages...',
            function () use ($tree, $published) {
                return $this->createRecursive($ancestor_id, $tree, $published);
            }
        );

        $this->output->writeLn('Publishing updates...');
        $published = $this->updateRecursive($ancestor_id, $tree, $published);

        $delete = new PublisherDelete($this->output, $this->confluence->shouldAutoDeleteOrphanedPages(), $this->client);
        $delete->handle($published);
    }

    protected function getRootPage($tree)
    {
        if ($this->confluence->hasAncestorId()) {
            $pages = $this->client->getList($this->confluence->getAncestorId());
            foreach ($pages as $page) {
                if ($page['title'] == $tree['title']) {
                    return $page;
                }
            }
        }

        if ($this->confluence->hasRootId()) {
            return $this->client->getPage($this->confluence->getRootId());
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
        $updateThreshold = $this->confluence->getUpdateThreshold();

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
