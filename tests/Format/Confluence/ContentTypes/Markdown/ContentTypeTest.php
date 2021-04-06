<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use PHPUnit\Framework\TestCase;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Tree\Root;
use Todaymade\Daux\Tree\Content;

class ContentTypeTest extends TestCase
{
    public function testRenderMermaid()
    {
        $content = <<<EOD
        ```tex
        c = \\pm\\sqrt{a^2 + b^2}\\in\\RR
        ```

        ```mermaid
        graph TD
        A[Hard] -->|Text| B(Round)
        B --> C{Decision}
        C -->|One| D[Result 1]
        C -->|Two| E[Result 2]
        ```
        EOD;

        $expected = <<<EOD
        <pre><code class="katex">c = \pm\sqrt{a^2 + b^2}\in\RR
        </code></pre>
        <pre class="mermaid">graph TD
        A[Hard] --&gt;|Text| B(Round)
        B --&gt; C{Decision}
        C --&gt;|One| D[Result 1]
        C --&gt;|Two| E[Result 2]
        </pre>
        <ac:structured-macro ac:name="html">
           <ac:plain-text-body> <![CDATA[...]]></ac:plain-text-body>
        </ac:structured-macro>
        EOD;


        $config = ConfigBuilder::withMode()
            ->withCache(false)
            ->build();
        $tree = new Root($config);
        $config->setTree($tree);

        $node = new Content($tree, null, null);
        $node->setContent($content);
        $node->setTitle("Some File");

        $converter = new ContentType($config);

        $result = trim($converter->convert($node->getContent(), $node));
        $result = preg_replace('/<!\[CDATA\[(.*?)\]\]>/s', '<![CDATA[...]]>', $result);

        $this->assertEquals($expected, $result);
    }
}
