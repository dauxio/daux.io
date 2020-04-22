<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;
use League\CommonMark\Util\ConfigurationAwareInterface;
use League\CommonMark\Util\ConfigurationInterface;
use Todaymade\Daux\Config;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Exception\LinkNotFoundException;

class LinkRenderer implements InlineRendererInterface, ConfigurationAwareInterface
{
    /**
     * @var Config
     */
    protected $daux;

    /**
     * @var \League\CommonMark\Inline\Renderer\LinkRenderer
     */
    protected $parent;

    public function __construct($daux)
    {
        $this->daux = $daux;
        $this->parent = new \League\CommonMark\Inline\Renderer\LinkRenderer();
    }

    /**
     * @param AbstractInline|Link $inline
     *
     * @throws LinkNotFoundException
     *
     * @return HtmlElement
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (!($inline instanceof Link)) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . \get_class($inline));
        }

        $element = $this->parent->render($inline, $htmlRenderer);

        $url = $inline->getUrl();

        // empty urls and anchors should
        // not go through the url resolver
        if (!DauxHelper::isValidUrl($url)) {
            return $element;
        }

        // Absolute urls, shouldn't either
        if (DauxHelper::isExternalUrl($url)) {
            $element->setAttribute('class', 'Link--external');
            $element->setAttribute('rel', 'noopener noreferrer');

            return $element;
        }

        // if there's a hash component in the url, ensure we
        // don't put that part through the resolver.
        $urlAndHash = explode('#', $url);
        $url = $urlAndHash[0];

        $foundWithHash = false;

        try {
            $file = DauxHelper::resolveInternalFile($this->daux, $url);
            $url = DauxHelper::getRelativePath($this->daux->getCurrentPage()->getUrl(), $file->getUrl());
        } catch (LinkNotFoundException $e) {
            // For some reason, the filename could contain a # and thus the link needs to resolve to that.
            try {
                if (strlen($urlAndHash[1] ?? '') > 0) {
                    $file = DauxHelper::resolveInternalFile($this->daux, $url . '#' . $urlAndHash[1]);
                    $url = DauxHelper::getRelativePath($this->daux->getCurrentPage()->getUrl(), $file->getUrl());
                    $foundWithHash = true;
                }
            } catch (LinkNotFoundException $e2) {
                // If it's still not found here, we'll only
                // report on the first error as the second
                // one will tell the same.
            }

            if (!$foundWithHash) {
                if ($this->daux->isStatic()) {
                    throw $e;
                }

                $element->setAttribute('class', 'Link--broken');
            }
        }

        if (!$foundWithHash && isset($urlAndHash[1])) {
            $url .= '#' . $urlAndHash[1];
        }

        $element->setAttribute('href', $url);

        return $element;
    }

    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->parent->setConfiguration($configuration);
    }
}
