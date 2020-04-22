<?php namespace Todaymade\Daux\Format\HTML\ContentTypes\Markdown;

use League\CommonMark\Block\Element as BlockElement;
use League\CommonMark\Environment;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Inline\Element as InlineElement;
use Todaymade\Daux\Config;
use Todaymade\Daux\ContentTypes\Markdown\TableOfContents;

class CommonMarkConverter extends \Todaymade\Daux\ContentTypes\Markdown\CommonMarkConverter
{
    protected function extendEnvironment(Environment $environment, Config $config)
    {
        parent::extendEnvironment($environment, $config);

        $environment->addBlockRenderer(BlockElement\FencedCode::class, new FencedCodeRenderer());

        $processor = new TOC\Processor($config);
        $environment->addEventListener(DocumentParsedEvent::class, [$processor, 'onDocumentParsed']);
        $environment->addBlockRenderer(TableOfContents::class, new TOC\Renderer($config));

        $environment->addInlineRenderer(InlineElement\Image::class, new ImageRenderer($config));
    }
}
