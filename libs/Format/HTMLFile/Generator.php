<?php namespace Todaymade\Daux\Format\HTMLFile;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Console\RunAction;
use Todaymade\Daux\Daux;
use Todaymade\Daux\Format\HTML\Template;
use Todaymade\Daux\Format\HTML\HTMLUtils;
use Todaymade\Daux\Format\HTMLFile\ContentTypes\Markdown\ContentType;

class Generator implements \Todaymade\Daux\Format\Base\Generator
{
    use RunAction, HTMLUtils;

    /** @var Daux */
    protected $daux;

    /**
     * @param Daux $daux
     */
    public function __construct(Daux $daux)
    {
        $params = $daux->getParams();

        $this->daux = $daux;
        $this->templateRenderer = new Template($params);
        $params->templateRenderer = $this->templateRenderer;
    }

    /**
     * @return array
     */
    public function getContentTypes()
    {
        return [
            'markdown' => new ContentType($this->daux->getParams()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function generateAll(InputInterface $input, OutputInterface $output, $width)
    {
        $destination = $input->getOption('destination');

        $params = $this->daux->getParams();
        if (is_null($destination)) {
            $destination = $this->daux->local_base . DIRECTORY_SEPARATOR . 'static';
        }

        $this->runAction(
            'Cleaning destination folder ...',
            $width,
            function() use ($destination, $params) {
                $this->ensureEmptyDestination($destination);
            }
        );

        $data = [
            'author' => $params['author'],
            'title' => $params['title'],
            'subject' => $params['tagline']
        ];

        $book = new Book($this->daux->tree, $data);

        $current = $this->daux->tree->getIndexPage();
        while ($current) {
            $this->runAction(
                'Generating ' . $current->getTitle(),
                $width,
                function () use ($book, $current, $params) {
                    $contentType = $this->daux->getContentTypeHandler()->getType($current);
                    $content = ContentPage::fromFile($current, $params, $contentType);
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
