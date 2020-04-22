<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use League\CommonMark\Block\Parser\BlockParserInterface;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;

class TableOfContentsParser implements BlockParserInterface
{
    public function parse(ContextInterface $context, Cursor $cursor): bool
    {
        if ($cursor->isIndented()) {
            return false;
        }

        $previousState = $cursor->saveState();
        $cursor->advanceToNextNonSpaceOrNewline();
        $fence = $cursor->match('/^\[TOC\]/');
        if (is_null($fence)) {
            $cursor->restoreState($previousState);

            return false;
        }

        $context->addBlock(new TableOfContents());

        return true;
    }
}
