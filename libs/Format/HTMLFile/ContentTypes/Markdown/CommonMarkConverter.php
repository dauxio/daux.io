<?php namespace Todaymade\Daux\Format\HTMLFile\ContentTypes\Markdown;

use League\CommonMark\Environment;
use Todaymade\Daux\Config;

class CommonMarkConverter extends \Todaymade\Daux\Format\HTML\ContentTypes\Markdown\CommonMarkConverter
{
    protected function getLinkRenderer(Environment $environment)
    {
        return new LinkRenderer($environment->getConfig('daux'));
    }
}
