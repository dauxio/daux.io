<?php namespace Todaymade\Daux\Tree;

abstract class ContentAbstract extends Entry
{
    /** @var string */
    protected $content;

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
