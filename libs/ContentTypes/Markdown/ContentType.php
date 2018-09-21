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
    protected $converter;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->converter = new CommonMarkConverter(['daux' => $config]);
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
        Daux::writeln("Running conversion", OutputInterface::VERBOSITY_VERBOSE);
        return $this->converter->convertToHtml($raw);
    }

    public function convert($raw, Content $node)
    {
        $this->config->setCurrentPage($node);

        if (!$this->config->canCache()) {
            return $this->doConversion($raw);
        }

        // TODO :: add daux version to cache key
        $cacheKey = $this->config->getCacheKey() . sha1($raw);

        $payload = Cache::get($cacheKey);

        if ($payload) {
            Daux::writeln("Using cached version", OutputInterface::VERBOSITY_VERBOSE);
        } else {
            Daux::writeln("Not found in the cache, generating...", OutputInterface::VERBOSITY_VERBOSE);
            $payload = $this->doConversion($raw);
            Cache::put($cacheKey, $payload);
        } 

        return $payload;
    }
}
