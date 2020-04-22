<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use League\CommonMark\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Inline\Element as InlineElement;
use Todaymade\Daux\Config;

class CommonMarkConverter extends \League\CommonMark\CommonMarkConverter
{
    /**
     * Create a new commonmark converter instance.
     */
    public function __construct(array $config = [])
    {
        $environment = Environment::createCommonMarkEnvironment();
        $environment->mergeConfig($config);
        $environment->addExtension(new AutolinkExtension());
        $environment->addExtension(new SmartPunctExtension());
        $environment->addExtension(new StrikethroughExtension());
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
