<?php namespace Todaymade\Daux\Format\HTMLFile\ContentTypes\Markdown;

use Todaymade\Daux\Config;

class ContentType extends \Todaymade\Daux\ContentTypes\Markdown\ContentType
{
    protected function createConverter() {
        return new CommonMarkConverter(['daux' => $this->config]);
    }
}
