<?php

declare(strict_types=1);

namespace Todaymade\Daux\Format\Confluence;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MermaidExtractorTest extends TestCase
{
    #[DataProvider('provideExtractionData')]
    public function testExtractFromContent(string $html, array $expectedDiagrams)
    {
        $extractor = new MermaidExtractor();
        $result = $extractor->extractFromContent($html);

        $this->assertCount(count($expectedDiagrams), $result);

        foreach ($expectedDiagrams as $index => $expected) {
            $this->assertArrayHasKey('id', $result[$index]);
            $this->assertArrayHasKey('code', $result[$index]);
            $this->assertArrayHasKey('original', $result[$index]);
            $this->assertEquals($expected['code'], $result[$index]['code']);
            $this->assertStringContainsString('mermaid', $result[$index]['id']);
        }
    }

    public static function provideExtractionData()
    {
        return [
            'Single Mermaid diagram' => [
                '<pre class="mermaid">graph TD
    A[Start] --> B[End]</pre>',
                [
                    [
                        'code' => 'graph TD
    A[Start] --> B[End]',
                    ],
                ],
            ],
            'Multiple Mermaid diagrams' => [
                '<p>Some text</p>
<pre class="mermaid">graph TD
    A --> B</pre>
<p>More text</p>
<pre class="mermaid">sequenceDiagram
    A->>B: Message</pre>',
                [
                    [
                        'code' => 'graph TD
    A --> B',
                    ],
                    [
                        'code' => 'sequenceDiagram
    A->>B: Message',
                    ],
                ],
            ],
            'No Mermaid diagrams' => [
                '<p>Some text</p>
<pre class="code">console.log("test");</pre>',
                [],
            ],
            'Mermaid with HTML entities' => [
                '<pre class="mermaid">graph TD
    A[Start &amp; End] --> B</pre>',
                [
                    [
                        'code' => 'graph TD
    A[Start & End] --> B',
                    ],
                ],
            ],
        ];
    }

    public function testExtractFromContentWithEmptyString()
    {
        $extractor = new MermaidExtractor();
        $result = $extractor->extractFromContent('');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
