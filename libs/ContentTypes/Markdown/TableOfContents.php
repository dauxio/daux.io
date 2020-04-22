<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Cursor;

class TableOfContents extends AbstractBlock
{
    /**
     * Returns true if this block can contain the given block as a child node.
     */
    public function canContain(AbstractBlock $block): bool
    {
        return false;
    }

    /**
     * Whether this is a code block.
     */
    public function isCode(): bool
    {
        return false;
    }

    public function matchesNextLine(Cursor $cursor): bool
    {
        return false;
    }
}
