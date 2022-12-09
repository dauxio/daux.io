<?php namespace Todaymade\Daux\ContentTypes\Plantuml;

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

    /**
     * @return string[]
     */
    public function getExtensions()
    {
        return ['puml'];
    }

    protected function doConversion($raw)
    {
        Daux::writeln('Running conversion', OutputInterface::VERBOSITY_VERBOSE);

        var_dump($raw);

        return "...";
    }

    public function convert($raw, Content $node)
    {
        $this->config->setCurrentPage($node);

        return $this->wrapCache($this->config, $raw, function () use ($raw) {
            return $this->doConversion($raw);
        });
    }

    public function toContent() {

    }
}
