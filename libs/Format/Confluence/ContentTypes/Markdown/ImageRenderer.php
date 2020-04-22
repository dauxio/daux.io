<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Image;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;
use League\CommonMark\Util\ConfigurationAwareInterface;
use League\CommonMark\Util\ConfigurationInterface;

class ImageRenderer implements InlineRendererInterface, ConfigurationAwareInterface
{
    /**
     * @var ConfigurationInterface
     */
    protected $config;

    /**
     * @var \League\CommonMark\Inline\Renderer\ImageRenderer
     */
    protected $parent;

    public function __construct()
    {
        $this->parent = new \League\CommonMark\Inline\Renderer\ImageRenderer();
    }

    /**
     * @param Image                    $inline
     *
     * @return HtmlElement
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (!($inline instanceof Image)) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . get_class($inline));
        }

        // External Images need special handling
        if (strpos($inline->getUrl(), 'http') === 0) {
            return new HtmlElement(
                'ac:image',
                [],
                new HtmlElement('ri:url', ['ri:value' => $inline->getUrl()])
            );
        }

        return $this->parent->render($inline, $htmlRenderer);
    }

    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->parent->setConfiguration($configuration);
    }
}
