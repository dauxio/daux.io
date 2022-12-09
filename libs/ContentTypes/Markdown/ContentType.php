<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Cache;
use Todaymade\Daux\Config;
use Todaymade\Daux\Daux;
use Todaymade\Daux\Tree\Content;
use Todaymade\Daux\Traits\Cacheable;

class ContentType implements \Todaymade\Daux\ContentTypes\ContentType
{
    use Cacheable;

    protected Config $config;

    private ?CommonMarkConverter $converter;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    protected function createConverter()
    {
        return new CommonMarkConverter(['daux' => $this->config]);
    }

    protected function getConverter()
    {
        if (!isset($this->converter)) {
            $this->converter = $this->createConverter();
        }

        return $this->converter;
    }

    /**
     * @return string[]
     */
    public function getExtensions()
    {
        return ['md', 'markdown'];
    }

    protected function doConversion($raw)
    {
        Daux::writeln('Running conversion', OutputInterface::VERBOSITY_VERBOSE);

        return $this->getConverter()->convert($raw)->getContent();
    }

    public function convert($raw, Content $node)
    {
        $this->config->setCurrentPage($node);

        return $this->wrapCache($this->config, $raw, function () use ($raw) {
            return $this->doConversion($raw);
        });
    }
}
