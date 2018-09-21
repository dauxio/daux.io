<?php namespace Todaymade\Daux\Format\HTMLFile\ContentTypes\Markdown;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Link;
use Todaymade\Daux\Config;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Exception\LinkNotFoundException;
use Todaymade\Daux\Tree\Entry;

class LinkRenderer extends \Todaymade\Daux\ContentTypes\Markdown\LinkRenderer
{
    /**
     * @param AbstractInline|Link $inline
     * @param ElementRendererInterface $htmlRenderer
     * @return HtmlElement
     * @throws LinkNotFoundException
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        // This can't be in the method type as
        // the method is an abstract and should
        // have the same interface
        if (!$inline instanceof Link) {
            throw new \RuntimeException(
                'Wrong type passed to ' . __CLASS__ . '::' . __METHOD__ .
                " the expected type was 'League\\CommonMark\\Inline\\Element\\Link' but '" .
                get_class($inline) . "' was provided"
            );
        }

        $element = parent::render($inline, $htmlRenderer);

        $url = $inline->getUrl();

        // empty urls and anchors should
        // not go through the url resolver
        if (!$this->isValidUrl($url)) {
            return $element;
        }

        // Absolute urls, shouldn't either
        if ($this->isExternalUrl($url)) {
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
            $file = $this->resolveInternalFile($url);
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