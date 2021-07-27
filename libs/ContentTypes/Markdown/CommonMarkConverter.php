<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;
use Todaymade\Daux\Config;

class CommonMarkConverter extends MarkdownConverter
{
    /**
     * Create a new Markdown converter pre-configured for CommonMark
     *
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        // We have a custom normalizer that does some transliteration
        $config['slug_normalizer']['instance'] = new TextNormalization();

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new AutolinkExtension());
        $environment->addExtension(new SmartPunctExtension());
        $environment->addExtension(new StrikethroughExtension());
        $environment->addExtension(new TableExtension());

        $environment->addExtension(new DauxExtension());

        $this->extendEnvironment($environment, $config['daux']);

        if ($config['daux']->hasProcessorInstance()) {
            $config['daux']->getProcessorInstance()->extendCommonMarkEnvironment($environment);
        }

        parent::__construct($environment);
    }

    protected function getLinkRenderer(Config $config)
    {
        return new LinkRenderer($config);
    }

    protected function extendEnvironment(Environment $environment, Config $config)
    {
        $environment->addRenderer(Link::class, $this->getLinkRenderer($config));
    }
}
