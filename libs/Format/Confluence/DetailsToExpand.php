<?php namespace Todaymade\Daux\Format\Confluence;

class DetailsToExpand
{
    public function convert(string $content): string
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($content, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
        libxml_clear_errors();

        $detailElements = $dom->getElementsByTagName('details');

        $count = $detailElements->length;
        for ($i = $count - 1; $i >= 0; --$i) {
            $this->convertOne($detailElements->item($i));
        }

        return $dom->saveHTML();
    }

    protected function findSummary(\DOMElement $element): ?\DOMElement
    {
        if ($element->childElementCount == 0) {
            return null;
        }

        foreach ($element->childNodes as $child) {
            if ($child->nodeName == 'summary') {
                return $child;
            }
        }

        return null;
    }

    protected function convertOne(\DOMElement $element)
    {
        /*
        <ac:structured-macro ac:name="expand">
          <ac:parameter ac:name="">This is my message</ac:parameter>
          <ac:rich-text-body>
            <p>This text is <em>hidden</em> until you expand it.</p>
          </ac:rich-text-body>
        </ac:structured-macro>
        */

        $summary = $this->findSummary($element);
        if (!$summary) {
            // If we can't find a title we don't try to convert to expandable
            return;
        }

        // Create new title node
        $title = $element->ownerDocument->createElement('ac:parameter');
        $title->setAttribute('ac:name', '');
        $titleChildNodes = $summary->childNodes;
        while ($titleChildNodes->length > 0) {
            $title->appendChild($titleChildNodes->item(0));
        }
        $element->removeChild($summary);

        // Create body node
        $body = $element->ownerDocument->createElement('ac:rich-text-body');
        $childNodes = $element->childNodes;
        while ($childNodes->length > 0) {
            $body->appendChild($childNodes->item(0));
        }

        // Assemble the macro
        $macro = $element->ownerDocument->createElement('ac:structured-macro');
        $macro->setAttribute('ac:name', 'expand');
        $macro->appendChild($title);
        $macro->appendChild($body);

        $element->parentNode->replaceChild($macro, $element);
    }
}
