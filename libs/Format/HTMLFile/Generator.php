<?php namespace Todaymade\Daux\Format\HTMLFile;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Console\RunAction;
use Todaymade\Daux\Daux;
use Todaymade\Daux\Format\HTML\HTMLUtils;
use Todaymade\Daux\Format\HTML\Template;
use Todaymade\Daux\Format\HTMLFile\ContentTypes\Markdown\ContentType;

class Generator implements \Todaymade\Daux\Format\Base\Generator
{
    use RunAction;
    use HTMLUtils;

    /** @var Daux */
    protected $daux;

    /** @var Template */
    protected $templateRenderer;

    public function __construct(Daux $daux)
    {
        $config = $daux->getConfig();

        $this->daux = $daux;
        $this->templateRenderer = new Template($config);
        $config->templateRenderer = $this->templateRenderer;
    }

    /**
     * @return array
     */
    public function getContentTypes()
    {
        return [
            'markdown' => new ContentType($this->daux->getConfig()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function generateAll(InputInterface $input, OutputInterface $output, $width)
    {
        $destination = $input->getOption('destination');

        $config = $this->daux->getConfig();
        if (is_null($destination)) {
            $destination = $config->getLocalBase() . DIRECTORY_SEPARATOR . 'static';
        }

        $this->runAction(
            'Cleaning destination folder ...',
            $width,
            function () use ($destination) {
                $this->ensureEmptyDestination($destination);
            }
        );

        $data = [
            'author' => $config->getAuthor(),
            'title' => $config->getTitle(),
            'subject' => $config->getTagline(),
        ];

        $book = new Book($this->daux->tree, $data);

        $current = $this->daux->tree->getIndexPage();
        while ($current) {
            $this->runAction(
                'Generating ' . $current->getTitle(),
                $width,
                function () use ($book, $current, $config) {
                    $contentType = $this->daux->getContentTypeHandler()->getType($current);
                    $content = ContentPage::fromFile($current, $config, $contentType);
                    $content->templateRenderer = $this->templateRenderer;
                    $content = $content->getContent();
                    $book->addPage($current, $content);
                }
            );

            $current = $current->getNext();
        }

        $content = $book->generate();
        file_put_contents($input->getOption('destination') . '/file.html', $content);
    }
}
