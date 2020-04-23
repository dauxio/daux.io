<?php

$report = file_get_contents(dirname(__DIR__) . "/test-report.xml");

$doc = new DOMDocument($report);
$doc->loadXML($report);

function hasDataSetTestCase(DomNode $node) {
    foreach ($node->childNodes as $child) {
        if ($child->nodeName === "testcase" && strpos($child->attributes->getNamedItem("name")->textContent, "with data set #" ) !== false) {
            return $child;
        }
    }

    return false;
}

function drillDownTestSuite(DomDocument $document, DomNode $node) {
    if ($dataset = hasDataSetTestCase($node)) {
        $childAttributes =  $dataset->attributes;
        $nodeAttributes= $node->attributes;

        $case = $document->createElement('testcase');
        $case->setAttribute('name', $childAttributes->getNamedItem('name')->textContent);
        $case->setAttribute('class', $childAttributes->getNamedItem('class')->textContent);
        $case->setAttribute('classname', $childAttributes->getNamedItem('classname')->textContent);
        $case->setAttribute('file', $childAttributes->getNamedItem('file')->textContent);
        $case->setAttribute('line', $childAttributes->getNamedItem('line')->textContent);
        $case->setAttribute('assertions', $nodeAttributes->getNamedItem('assertions')->textContent);
        $case->setAttribute('time', $nodeAttributes->getNamedItem('time')->textContent);

        $node->parentNode->replaceChild($case, $node);
        return true;
    }

    /** @var DomNode $child */
    for ($i=0; $i< $node->childNodes->length; $i++) {
        $child = $node->childNodes->item($i);
        if ($child->localName === "testsuite") {
            if (drillDownTestSuite($document, $child)) {
                $i--;
            }
        }
    }

    return false;
}

drillDownTestSuite($doc, $doc->firstChild);

file_put_contents(dirname(__DIR__) . "/test-report.xml", $doc->saveXML());
echo "Done\n";
