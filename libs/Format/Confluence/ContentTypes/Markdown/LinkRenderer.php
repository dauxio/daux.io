<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Link;
use Todaymade\Daux\DauxHelper;

class LinkRenderer extends \Todaymade\Daux\ContentTypes\Markdown\LinkRenderer
{
    /**
     * @param AbstractInline|Link $inline
     *
     * @return HtmlElement
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (!($inline instanceof Link)) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . \get_class($inline));
        }

        // Default handling
        $element = parent::render($inline, $htmlRenderer);

        $url = $inline->getUrl();

        // empty urls, anchors and absolute urls
        // should not go through the url resolver
        if (!DauxHelper::isValidUrl($url) || DauxHelper::isExternalUrl($url)) {
            return $element;
        }

        // if there's a hash component in the url, ensure we
        // don't put that part through the resolver.
        $urlAndHash = explode('#', $url);
        $url = $urlAndHash[0];

        //Internal links
        $file = DauxHelper::resolveInternalFile($this->daux, $url);

        $link_props = [];
        if (isset($urlAndHash[1])) {
            $link_props["ac:anchor"] = $urlAndHash[1];
        }

        $page_props = [
            'ri:content-title' => trim(trim($this->daux['confluence']['prefix']) . ' ' . $file->getTitle()),
            'ri:space-key' => $this->daux['confluence']['space_id'],
        ];

        $page = strval(new HtmlElement('ri:page', $page_props, '', true));
        $children = $htmlRenderer->renderInlines($inline->children());
        if (strpos($children, '<') !== false) {
            $children = '<ac:link-body>' . $children . '</ac:link-body>';
        } else {
            $children = '<ac:plain-text-link-body><![CDATA[' . $children . ']]></ac:plain-text-link-body>';
        }

        return new HtmlElement('ac:link', $link_props, $page . $children);
    }
}
