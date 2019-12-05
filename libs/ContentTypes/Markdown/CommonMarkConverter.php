<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use League\CommonMark\Ext\Table\TableExtension;
use League\CommonMark\Inline\Element as InlineElement;
use Todaymade\Daux\Config;

class CommonMarkConverter extends \League\CommonMark\CommonMarkConverter
{
    /**
     * Create a new commonmark converter instance.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $environment = Environment::createCommonMarkEnvironment();
        $environment->mergeConfig($config);
        $environment->addExtension(new TableExtension());

        // Table of Contents
        $environment->addBlockParser(new TableOfContentsParser());

        $this->extendEnvironment($environment, $config['daux']);

        if ($config['daux']->hasProcessorInstance()) {
            $config['daux']->getProcessorInstance()->extendCommonMarkEnvironment($environment);
        }

        parent::__construct($config, $environment);
    }

    protected function getLinkRenderer(Environment $environment)
    {
        return new LinkRenderer($environment->getConfig('daux'));
    }

    protected function extendEnvironment(Environment $environment, Config $config)
    {
        $environment->addInlineRenderer(InlineElement\Link::class, $this->getLinkRenderer($environment));
    }
}
