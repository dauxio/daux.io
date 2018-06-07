<?php namespace Todaymade\Daux;

use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Daux;

class Cache
{

    static $printed = false;

    public static function getDirectory()
    {
        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "dauxio" . DIRECTORY_SEPARATOR;

        if (!Cache::$printed) {
            Cache::$printed = true;
            Daux::writeln("Using cache dir '$dir'", OutputInterface::VERBOSITY_VERBOSE);
        }

        return $dir;
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public static function put($key, $value)
    {
        Cache::ensureCacheDirectoryExists($path = Cache::path($key));
        file_put_contents($path, $value);
    }

    /**
     * Create the file cache directory if necessary.
     *
     * @param  string  $path
     * @return void
     */
    protected static function ensureCacheDirectoryExists($path)
    {
        $parent = dirname($path);

        if (!file_exists($parent)) {
            mkdir($parent, 0777, true);
        }
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public static function forget($key)
    {
        $path = Cache::path($key);

        if (file_exists($path)) {
            return unlink($path);
        }

        return false;
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string|array  $key
     * @return mixed
     */
    public static function get($key)
    {
        $path = Cache::path($key);

        if (file_exists($path)) {
            return file_get_contents($path);
        }

        return null;
    }

    /**
     * Get the full path for the given cache key.
     *
     * @param  string  $key
     * @return string
     */
    protected static function path($key)
    {
        $parts = array_slice(str_split($hash = sha1($key), 2), 0, 2);
        return Cache::getDirectory() . '/' . implode('/', $parts) . '/' . $hash;
    }

    public static function clear()
    {
        Cache::rrmdir(Cache::getDirectory());
    }

    protected static function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        Cache::rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }

                }
            }
            rmdir($dir);
        }
    }
}
