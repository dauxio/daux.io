<?php namespace Todaymade\Daux\Format\Confluence;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Config as GlobalConfig;
use Todaymade\Daux\Console\RunAction;
use Todaymade\Daux\Daux;
use Todaymade\Daux\Tree\Content;
use Todaymade\Daux\Tree\Directory;

class Generator implements \Todaymade\Daux\Format\Base\Generator
{
    use RunAction;

    /** @var string */
    protected $prefix;

    /** @var Daux */
    protected $daux;

    public function __construct(Daux $daux)
    {
        $this->daux = $daux;

        $this->checkConfiguration();
    }

    public function checkConfiguration()
    {
        $config = $this->daux->getConfig();
        $confluence = $config->getConfluenceConfiguration();

        if ($confluence == null) {
            throw new \RuntimeException('You must specify your Confluence configuration');
        }

        $mandatory = ['space_id', 'base_url', 'user', 'pass', 'prefix'];
        $errors = [];
        foreach ($mandatory as $key) {
            if (!$confluence->hasValue($key)) {
                $errors[] = $key;
            }
        }

        if (count($errors)) {
            throw new \RuntimeException("The following options are mandatory for confluence : '" . implode("', '", $errors) . "'");
        }

        if (!$confluence->hasAncestorId() && !$confluence->hasRootId()) {
            throw new \RuntimeException("You must specify an 'ancestor_id' or a 'root_id' for confluence.");
        }
    }

    /**
     * @return array
     */
    public function getContentTypes()
    {
        return [
            new ContentTypes\Markdown\ContentType($this->daux->getConfig()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function generateAll(InputInterface $input, OutputInterface $output, $width)
    {
        $config = $this->daux->getConfig();

        $confluence = $config->getConfluenceConfiguration();
        $this->prefix = trim($confluence->getPrefix()) . ' ';
        if ($this->prefix == ' ') {
            $this->prefix = '';
        }

        $tree = $this->runAction(
            'Generating Tree ...',
            $width,
            function () use ($config) {
                $tree = $this->generateRecursive($this->daux->tree, $config);
                $tree['title'] = $this->prefix . $config->getTitle();

                return $tree;
            }
        );

        $output->writeln('Start Publishing...');

        $publisher = new Publisher($confluence);
        $publisher->output = $output;
        $publisher->width = $width;
        $publisher->publish($tree);
    }

    private function generateRecursive(Directory $tree, GlobalConfig $config, $base_url = '')
    {
        $final = ['title' => $this->prefix . $tree->getTitle()];
        $config['base_url'] = $base_url;

        $config->setImage(str_replace('<base_url>', $base_url, $config->getImage()));
        if ($base_url !== '') {
            $config->setEntryPage($tree->getFirstPage());
        }
        foreach ($tree->getEntries() as $key => $node) {
            if ($node instanceof Directory) {
                $final['children'][$this->prefix . $node->getTitle()] = $this->generateRecursive(
                    $node,
                    $config,
                    '../' . $base_url
                );
            } elseif ($node instanceof Content) {
                $config->setRequest($node->getUrl());

                $contentType = $this->daux->getContentTypeHandler()->getType($node);

                $data = [
                    'title' => $this->prefix . $node->getTitle(),
                    'file' => $node,
                    'page' => ContentPage::fromFile($node, $config, $contentType),
                ];

                if ($key == 'index.html') {
                    $final['title'] = $this->prefix . $tree->getTitle();
                    $final['file'] = $node;
                    $final['page'] = $data['page'];
                } else {
                    $final['children'][$data['title']] = $data;
                }
            }
        }

        return $final;
    }
}
