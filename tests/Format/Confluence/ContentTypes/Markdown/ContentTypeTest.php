<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use PHPUnit\Framework\TestCase;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\Tree\Content;
use Todaymade\Daux\Tree\Root;

class ContentTypeTest extends TestCase
{
    public function providerContent()
    {
        return [
            'Render Mermaid and LaTex' => [
                <<<'EOD'
                    ```tex
                    c = \pm\sqrt{a^2 + b^2}\in\RR
                    ```

                    ```mermaid
                    graph TD
                    A[Hard] -->|Text| B(Round)
                    B --> C{Decision}
                    C -->|One| D[Result 1]
                    C -->|Two| E[Result 2]
                    ```
                    EOD,
                <<<'EOD'
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
                    EOD
            ],
            'Render a neutral code block' => [
                <<<'EOD'
                    ```
                    console.log("I'm in a code block");
                    ```
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="code"><ac:plain-text-body><![CDATA[...]]></ac:plain-text-body></ac:structured-macro>
                    EOD
            ],
            'Render a js code block' => [
                <<<'EOD'
                    ```js
                    console.log("I'm in a code block");
                    ```
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="code"><ac:parameter ac:name="language">javascript</ac:parameter><ac:plain-text-body><![CDATA[...]]></ac:plain-text-body></ac:structured-macro>
                    EOD
            ],
            'Render an unsupported language code block' => [
                <<<'EOD'
                    ```rust
                    mut stuff
                    ```
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="code"><ac:plain-text-body><![CDATA[...]]></ac:plain-text-body></ac:structured-macro>
                    EOD
            ],
            'Render an indented code block' => [
                <<<'EOD'
                    Some text before

                        console.log("This is a code block");
                    EOD,
                <<<'EOD'
                    <p>Some text before</p>
                    <ac:structured-macro ac:name="code"><ac:plain-text-body><![CDATA[...]]></ac:plain-text-body></ac:structured-macro>
                    EOD
            ],
        ];
    }

    /**
     * @dataProvider providerContent
     *
     * @param mixed $content
     * @param mixed $expected
     */
    public function testRendering($content, $expected)
    {
        $config = ConfigBuilder::withMode()
            ->withCache(false)
            ->build();
        $tree = new Root($config);
        $config->setTree($tree);

        $node = new Content($tree, null, null);
        $node->setContent($content);
        $node->setTitle('Some File');

        $converter = new ContentType($config);

        $result = trim($converter->convert($node->getContent(), $node));
        $result = preg_replace('/<!\[CDATA\[(.*?)\]\]>/s', '<![CDATA[...]]>', $result);

        $this->assertEquals($expected, $result);
    }
}
