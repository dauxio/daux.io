<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use League\CommonMark\Block\Element as BlockElement;
use League\CommonMark\Environment;
use League\CommonMark\Inline\Element as InlineElement;
use Todaymade\Daux\Config;
use Todaymade\Daux\ContentTypes\Markdown\TableOfContents;

class CommonMarkConverter extends \Todaymade\Daux\ContentTypes\Markdown\CommonMarkConverter
{
    protected function getLinkRenderer(Environment $environment)
    {
        return new LinkRenderer($environment->getConfig('daux'));
    }

    protected function extendEnvironment(Environment $environment, Config $config)
    {
        parent::extendEnvironment($environment, $config);

        $environment->addBlockRenderer(TableOfContents::class, new TOCRenderer());

        //Add code renderer
        $environment->addBlockRenderer(BlockElement\FencedCode::class, new FencedCodeRenderer($config));
        $environment->addBlockRenderer(BlockElement\IndentedCode::class, new IndentedCodeRenderer());

        $environment->addInlineRenderer(InlineElement\Image::class, new ImageRenderer());
    }
}
