<?php namespace Todaymade\Daux\Format\HTMLFile\ContentTypes\Markdown;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Link;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Exception\LinkNotFoundException;

class LinkRenderer extends \Todaymade\Daux\ContentTypes\Markdown\LinkRenderer
{
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

        $element = parent::render($inline, $htmlRenderer);

        $url = $inline->getUrl();

        // empty urls and anchors should
        // not go through the url resolver
        if (!DauxHelper::isValidUrl($url)) {
            return $element;
        }

        // Absolute urls, shouldn't either
        if (DauxHelper::isExternalUrl($url)) {
            $element->setAttribute('class', 'Link--external');

            return $element;
        }

        // if there's a hash component in the url, we can directly use it as all pages are in the same file
        $urlAndHash = explode('#', $url);
        if (isset($urlAndHash[1])) {
            $element->setAttribute('href', '#' . $urlAndHash[1]);

            return $element;
        }

        try {
            $file = DauxHelper::resolveInternalFile($this->daux, $url);
            $url = $file->getUrl();
        } catch (LinkNotFoundException $e) {
            if ($this->daux->isStatic()) {
                throw $e;
            }

            $element->setAttribute('class', 'Link--broken');
        }

        $url = str_replace('/', '_', $url);
        $element->setAttribute('href', "#file_$url");

        return $element;
    }
}
