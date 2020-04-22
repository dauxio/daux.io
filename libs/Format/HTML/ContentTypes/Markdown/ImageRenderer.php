<?php namespace Todaymade\Daux\Format\HTML\ContentTypes\Markdown;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Image;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;
use League\CommonMark\Util\ConfigurationAwareInterface;
use League\CommonMark\Util\ConfigurationInterface;
use Todaymade\Daux\Config;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Exception\LinkNotFoundException;

class ImageRenderer implements InlineRendererInterface, ConfigurationAwareInterface
{
    /**
     * @var Config
     */
    protected $daux;

    /**
     * @var ConfigurationInterface
     */
    protected $config;

    /**
     * @var \League\CommonMark\Inline\Renderer\ImageRenderer
     */
    protected $parent;

    public function __construct($daux)
    {
        $this->daux = $daux;
        $this->parent = new \League\CommonMark\Inline\Renderer\ImageRenderer();
    }

    /**
     * Relative URLs can be done using either the folder with
     * number prefix or the final name (with prefix stripped).
     * This ensures that we always use the final name when generating.
     *
     * @param mixed $url
     *
     * @throws LinkNotFoundException
     */
    protected function getCleanUrl($url)
    {
        // empty urls and anchors should
        // not go through the url resolver
        if (!DauxHelper::isValidUrl($url)) {
            return $url;
        }

        // Absolute urls, shouldn't either
        if (DauxHelper::isExternalUrl($url)) {
            return $url;
        }

        try {
            $file = DauxHelper::resolveInternalFile($this->daux, $url);

            return DauxHelper::getRelativePath($this->daux->getCurrentPage()->getUrl(), $file->getUrl());
        } catch (LinkNotFoundException $e) {
            if ($this->daux->isStatic()) {
                throw $e;
            }
        }

        return $url;
    }

    /**
     * @param Image                    $inline
     *
     * @throws LinkNotFoundException
     *
     * @return HtmlElement
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (!($inline instanceof Image)) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . \get_class($inline));
        }

        $inline->setUrl($this->getCleanUrl($inline->getUrl()));

        return $this->parent->render($inline, $htmlRenderer);
    }

    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->config = $configuration;
        $this->parent->setConfiguration($configuration);
    }
}
