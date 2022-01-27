<?php namespace Todaymade\Daux\Format\HTMLFile\ContentTypes\Markdown;

use Todaymade\Daux\Config;

class CommonMarkConverter extends \Todaymade\Daux\Format\HTML\ContentTypes\Markdown\CommonMarkConverter
{
    protected function getLinkRenderer(Config $config)
    {
        return new LinkRenderer($config);
    }
}
