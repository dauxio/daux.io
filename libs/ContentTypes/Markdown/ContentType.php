<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Cache;
use Todaymade\Daux\Config;
use Todaymade\Daux\Daux;
use Todaymade\Daux\Tree\Content;

class ContentType implements \Todaymade\Daux\ContentTypes\ContentType
{
    /** @var Config */
    protected $config;

    /** @var CommonMarkConverter */
    private $converter;

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
        if (!$this->converter) {
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

        return $this->getConverter()->convertToHtml($raw);
    }

    public function convert($raw, Content $node)
    {
        $this->config->setCurrentPage($node);

        $can_cache = $this->config->canCache();

        // TODO :: add daux version to cache key
        $cacheKey = $this->config->getCacheKey() . sha1($raw);

        $payload = Cache::get($cacheKey);

        if ($can_cache && $payload) {
            Daux::writeln('Using cached version', OutputInterface::VERBOSITY_VERBOSE);
        }

        if (!$can_cache || !$payload) {
            Daux::writeln($can_cache ? 'Not found in the cache, generating...' : 'Cache disabled, generating...', OutputInterface::VERBOSITY_VERBOSE);
            $payload = $this->doConversion($raw);
        }

        if ($can_cache) {
            Cache::put($cacheKey, $payload);
        }

        return $payload;
    }
}
