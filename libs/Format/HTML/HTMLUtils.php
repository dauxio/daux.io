<?php namespace Todaymade\Daux\Format\HTML;

use Todaymade\Daux\GeneratorHelper;

trait HTMLUtils
{
    public function ensureEmptyDestination($destination)
    {
        if (is_dir($destination)) {
            GeneratorHelper::rmdir($destination);
        } else {
            mkdir($destination);
        }
    }

    /**
     * Copy all files from $local to $destination.
     *
     * @param string $destination
     * @param string $local_base
     */
    public function copyThemes($destination, $local_base)
    {
        mkdir($destination . DIRECTORY_SEPARATOR . 'themes');
        GeneratorHelper::copyRecursive(
            $local_base,
            $destination . DIRECTORY_SEPARATOR . 'themes'
        );
    }
}
