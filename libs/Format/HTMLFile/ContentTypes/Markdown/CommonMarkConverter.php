<?php namespace Todaymade\Daux\Format\HTMLFile\ContentTypes\Markdown;

use League\CommonMark\Environment;
use Todaymade\Daux\Config;

class CommonMarkConverter extends \Todaymade\Daux\Format\HTML\ContentTypes\Markdown\CommonMarkConverter
{
    protected function getLinkRenderer(Environment $environment)
    {
        var_dump(LinkRenderer::class);
        return new LinkRenderer($environment->getConfig('daux'));
    }
}
