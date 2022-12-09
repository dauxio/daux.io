<?php namespace Todaymade\Daux\Traits;

use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Cache;
use Todaymade\Daux\Config;
use Todaymade\Daux\Daux;

trait Cacheable
{
    function wrapCache(Config $config, $raw, $converter) {
        $canCache = $config->canCache();

        // TODO :: add daux version to cache key
        $cacheKey = $config->getCacheKey() . sha1($raw);

        $payload = Cache::get($cacheKey);

        if ($canCache && $payload) {
            Daux::writeln('Using cached version', OutputInterface::VERBOSITY_VERBOSE);
        }

        if (!$canCache || !$payload) {
            Daux::writeln(
                $canCache
                      ? 'Not found in the cache, generating...'
                      : 'Cache disabled, generating...',
                OutputInterface::VERBOSITY_VERBOSE
            );
            $payload = $converter();
        }

        if ($canCache) {
            Cache::put($cacheKey, $payload);
        }

        return $payload;
    }
}
