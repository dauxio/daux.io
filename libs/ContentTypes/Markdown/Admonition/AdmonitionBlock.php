<?php namespace Todaymade\Daux\ContentTypes\Markdown\Admonition;

use League\CommonMark\Node\Block\AbstractBlock;

class AdmonitionBlock extends AbstractBlock
{
    private string $type;
    private ?string $title;

    public function __construct(string $type, ?string $title)
    {
        $this->type = $type;
        $this->title = $title;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
