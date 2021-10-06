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

        $this->assertEquals("<h1><a id=\"page_test\" href=\"#page_test\" class=\"Permalink\" aria-hidden=\"true\" title=\"Permalink\">#</a>Test</h1>\n", $converter->convertToHtml('# Test')->getContent());
    }

    public function testShouldAddTOCWhenAutoTOCisOn()
    {
        $config = $this->getConfig();
        $config['daux']['html']['auto_toc'] = true;
        $converter = new CommonMarkConverter($config);

        $source = "# Title";
        $expected = <<<'EXPECTED'
<ul class="TableOfContents">
<li>
<a href="#page_title">Title</a>
</li>
</ul>
<h1><a id="page_title" href="#page_title" class="Permalink" aria-hidden="true" title="Permalink">#</a>Title</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source)->getContent());
    }

    public function testShouldNotAddTOCWhenAutoTOCisOnAndTOCisPresent()
    {
        $config = $this->getConfig();
        $config['daux']['html']['auto_toc'] = true;
        $converter = new CommonMarkConverter($config);

        $source = "Some Content\n[TOC]\n# Title";
        $expected = <<<'EXPECTED'
<p>Some Content</p>
<ul class="TableOfContents">
<li>
<a href="#page_title">Title</a>
</li>
</ul>
<h1><a id="page_title" href="#page_title" class="Permalink" aria-hidden="true" title="Permalink">#</a>Title</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source)->getContent());
    }

    public function testTOCToken()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# Title";
        $expected = <<<'EXPECTED'
<ul class="TableOfContents">
<li>
<a href="#page_title">Title</a>
</li>
</ul>
<h1><a id="page_title" href="#page_title" class="Permalink" aria-hidden="true" title="Permalink">#</a>Title</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source)->getContent());
    }

    public function testUnicodeTOC()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# 基础操作\n# 操作基础";
        $expected = <<<'EXPECTED'
<ul class="TableOfContents">
<li>
<a href="#page_ji_chu_cao_zuo">基础操作</a>
</li>
<li>
<a href="#page_cao_zuo_ji_chu">操作基础</a>
</li>
</ul>
<h1><a id="page_ji_chu_cao_zuo" href="#page_ji_chu_cao_zuo" class="Permalink" aria-hidden="true" title="Permalink">#</a>基础操作</h1>
<h1><a id="page_cao_zuo_ji_chu" href="#page_cao_zuo_ji_chu" class="Permalink" aria-hidden="true" title="Permalink">#</a>操作基础</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source)->getContent());
    }

    public function testDuplicatedTOC()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# Test\n# Test\n# Test";
        $expected = <<<'EXPECTED'
<ul class="TableOfContents">
<li>
<a href="#page_test">Test</a>
</li>
<li>
<a href="#page_test-1">Test</a>
</li>
<li>
<a href="#page_test-2">Test</a>
</li>
</ul>
<h1><a id="page_test" href="#page_test" class="Permalink" aria-hidden="true" title="Permalink">#</a>Test</h1>
<h1><a id="page_test-1" href="#page_test-1" class="Permalink" aria-hidden="true" title="Permalink">#</a>Test</h1>
<h1><a id="page_test-2" href="#page_test-2" class="Permalink" aria-hidden="true" title="Permalink">#</a>Test</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source)->getContent());
    }

    public function testEscapedTOC()
    {
        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# TEST : Test";
        $expected = <<<'EXPECTED'
<ul class="TableOfContents">
<li>
<a href="#page_test_test">TEST : Test</a>
</li>
</ul>
<h1><a id="page_test_test" href="#page_test_test" class="Permalink" aria-hidden="true" title="Permalink">#</a>TEST : Test</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source)->getContent());
    }

    public function testQuotesWorkCorrectly()
    {
        if (version_compare(PHP_VERSION, '8.0.0', '<')) {
            $this->markTestSkipped('This seems to not work with PHP 7.4, though the output is still valid');
        }

        $converter = new CommonMarkConverter($this->getConfig());

        $source = "[TOC]\n# Daux's bug";
        $expected = <<<'EXPECTED'
<ul class="TableOfContents">
<li>
<a href="#page_daux_s_bug">Daux’s bug</a>
</li>
</ul>
<h1><a id="page_daux_s_bug" href="#page_daux_s_bug" class="Permalink" aria-hidden="true" title="Permalink">#</a>Daux’s bug</h1>

EXPECTED;

        $this->assertEquals($expected, $converter->convertToHtml($source)->getContent());
    }
}
