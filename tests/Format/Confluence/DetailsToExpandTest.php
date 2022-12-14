<?php namespace Todaymade\Daux\Format\Confluence;

use PHPUnit\Framework\TestCase;

class DetailsToExpandTest extends TestCase
{
    public function provideExpandData()
    {
        return [
            [
                // Convert simple case
                <<<'EOD'
                    <details>
                    <summary>Title !</summary>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>

                    </details>
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="expand"><ac:parameter ac:name="">Title !</ac:parameter><ac:rich-text-body>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>
                    </ac:rich-text-body></ac:structured-macro>
                    EOD
            ],
            [
                // Doesn't break existing macros
                <<<'EOD'
                    <ac:structured-macro ac:name="info"><ac:rich-text-body>
                        <p>Content</p>
                    </ac:rich-text-body></ac:structured-macro>

                    <details>
                    <summary>Title !</summary>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>

                    </details>
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="info"><ac:rich-text-body>
                        <p>Content</p>
                    </ac:rich-text-body></ac:structured-macro>
                    <ac:structured-macro ac:name="expand"><ac:parameter ac:name="">Title !</ac:parameter><ac:rich-text-body>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>
                    </ac:rich-text-body></ac:structured-macro>
                    EOD
            ],
            [
                // Don't convert without title
                <<<'EOD'
                    <details>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>

                    </details>
                    EOD,
                <<<'EOD'
                    <details>
                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>
                    </details>
                    EOD
            ],
            [
                // Convert nested
                <<<'EOD'
                    <details>
                    <summary>Title !</summary>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>

                    <details>
                    <summary>Inner title</summary>

                    <p>Some Text</p>

                    </details>
                    </details>
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="expand"><ac:parameter ac:name="">Title !</ac:parameter><ac:rich-text-body>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>
                    <ac:structured-macro ac:name="expand"><ac:parameter ac:name="">Inner title</ac:parameter><ac:rich-text-body>

                    <p>Some Text</p>
                    </ac:rich-text-body></ac:structured-macro>
                    </ac:rich-text-body></ac:structured-macro>
                    EOD
            ],
        ];
    }

    /**
     * @dataProvider provideExpandData
     *
     * @param mixed $input
     * @param mixed $expected
     */
    public function testDetailsToExpand($input, $expected)
    {
        $expander = new DetailsToExpand();

        $this->assertEquals($expected, preg_replace("/\n\n/", "\n", trim($expander->convert($input))));
    }
}
