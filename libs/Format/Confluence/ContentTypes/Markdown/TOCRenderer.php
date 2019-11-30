<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use Todaymade\Daux\ContentTypes\Markdown\TableOfContents;

class TOCRenderer implements BlockRendererInterface
{
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, $inTightList = false)
    {
        if (!($block instanceof TableOfContents)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . get_class($block));
        }

        return '<ac:structured-macro ac:name="toc"></ac:structured-macro>';
    }
}
