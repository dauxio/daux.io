<?php namespace Todaymade\Daux\Format\HTML\ContentTypes\Markdown\TOC;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use Todaymade\Daux\Config;
use Todaymade\Daux\ContentTypes\Markdown\TableOfContents;

class Renderer implements BlockRendererInterface
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, $inTightList = false)
    {
        if (!($block instanceof TableOfContents)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . get_class($block));
        }

        $content = $htmlRenderer->renderBlocks($block->children());

        return $this->config->templateRenderer
            ->getEngine($this->config)
            ->render('theme::partials/table_of_contents', ['content' => $content]);
    }
}
