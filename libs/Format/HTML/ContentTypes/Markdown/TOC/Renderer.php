<?php namespace Todaymade\Daux\Format\HTML\ContentTypes\Markdown\TOC;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use Todaymade\Daux\Config;

class Renderer implements BlockRendererInterface
{
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, $inTightList = false)
    {
        $content = $htmlRenderer->renderBlocks($block->children());
        return $this->config->templateRenderer
            ->getEngine($this->config)
            ->render('partials/table_of_contents', ['content' => $content]);
    }
}
