<?php
namespace Todaymade\Daux\Format\HTML;

use Todaymade\Daux\Config as MainConfig;
use \Todaymade\Daux\Format\HTML\ContentTypes\Markdown\CommonMarkConverter;
use PHPUnit\Framework\TestCase;

class Engine {
    function render($template, $data) {
        return $data['content'];
    }
}

class Template {
    function getEngine() {
        return new Engine;
    }
}

class TableOfContentsTest extends TestCase
{
    function getConfig() {
        $config = new MainConfig;
        $config->templateRenderer = new Template;
        
        return ['daux' => $config];
    }

    function testNoTOCByDefault() {
        $converter = new CommonMarkConverter($this->getConfig());

        $this->assertEquals("<h1 id=\"page_Test\">Test</h1>\n", $converter->convertToHtml('# Test'));
    }

    function testTOCToken() {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# Title";
        $expected = <<<EXPECTED
<ul class="TableOfContents">
<li>
<p><a href="#page_Title">Title</a></p>
</li>
</ul>
<h1 id="page_Title">Title</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source));
    }

    function testUnicodeTOC() {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# 基础操作\n# 操作基础";
        $expected = <<<EXPECTED
<ul class="TableOfContents">
<li>
<p><a href="#page_section_1">基础操作</a></p>
</li>
<li>
<p><a href="#page_section_2">操作基础</a></p>
</li>
</ul>
<h1 id="page_section_1">基础操作</h1>
<h1 id="page_section_2">操作基础</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source));
    }

    function testDuplicatedTOC() {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# Test\n# Test";
        $expected = <<<EXPECTED
<ul class="TableOfContents">
<li>
<p><a href="#page_Test">Test</a></p>
</li>
<li>
<p><a href="#page_Test-2">Test</a></p>
</li>
</ul>
<h1 id="page_Test">Test</h1>
<h1 id="page_Test-2">Test</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source));
    }

    function testEscapedTOC() {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# TEST : Test";
        $expected = <<<EXPECTED
<ul class="TableOfContents">
<li>
<p><a href="#page_TEST-Test">TEST : Test</a></p>
</li>
</ul>
<h1 id="page_TEST-Test">TEST : Test</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source));
    }
}
