<?php namespace Todaymade\Daux\Format\HTML\Test;

use PHPUnit\Framework\TestCase;
use Todaymade\Daux\Config as MainConfig;
use Todaymade\Daux\Format\HTML\ContentTypes\Markdown\CommonMarkConverter;

class Engine
{
    public function render($template, $data)
    {
        return $data['content'];
    }
}

class Template
{
    public function getEngine()
    {
        return new Engine();
    }
}

class TableOfContentsTest extends TestCase
{
    public function getConfig()
    {
        $config = new MainConfig();
        $config->templateRenderer = new Template();

        return ['daux' => $config];
    }

    public function testNoTOCByDefault()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $this->assertEquals("<h1 id=\"page_Test\">Test</h1>\n", $converter->convertToHtml('# Test'));
    }

    public function testTOCToken()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# Title";
        $expected = <<<'EXPECTED'
<ul class="TableOfContents">
<li>
<p><a href="#page_Title">Title</a></p>
</li>
</ul>
<h1 id="page_Title">Title</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source));
    }

    public function testUnicodeTOC()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# 基础操作\n# 操作基础";
        $expected = <<<'EXPECTED'
<ul class="TableOfContents">
<li>
<p><a href="#page_ji_chu_cao_zuo">基础操作</a></p>
</li>
<li>
<p><a href="#page_cao_zuo_ji_chu">操作基础</a></p>
</li>
</ul>
<h1 id="page_ji_chu_cao_zuo">基础操作</h1>
<h1 id="page_cao_zuo_ji_chu">操作基础</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source));
    }

    public function testDuplicatedTOC()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# Test\n# Test\n# Test";
        $expected = <<<'EXPECTED'
<ul class="TableOfContents">
<li>
<p><a href="#page_Test">Test</a></p>
</li>
<li>
<p><a href="#page_Test-2">Test</a></p>
</li>
<li>
<p><a href="#page_Test-3">Test</a></p>
</li>
</ul>
<h1 id="page_Test">Test</h1>
<h1 id="page_Test-2">Test</h1>
<h1 id="page_Test-3">Test</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source));
    }

    public function testEscapedTOC()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# TEST : Test";
        $expected = <<<'EXPECTED'
<ul class="TableOfContents">
<li>
<p><a href="#page_TEST_Test">TEST : Test</a></p>
</li>
</ul>
<h1 id="page_TEST_Test">TEST : Test</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source));
    }


    public function testQuotesWorkCorrectly()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# Daux's bug";
        $expected = <<<'EXPECTED'
<ul class="TableOfContents">
<li>
<p><a href="#page_Daux_s_bug">Daux’s bug</a></p>
</li>
</ul>
<h1 id="page_Daux_s_bug">Daux’s bug</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source));
    }
}
