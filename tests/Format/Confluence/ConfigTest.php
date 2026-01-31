<?php

declare(strict_types=1);

namespace Todaymade\Daux\Format\Confluence;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Todaymade\Daux\ConfigBuilder;

class ConfigTest extends TestCase
{
    #[DataProvider('provideMermaidConfigData')]
    public function testMermaidConfiguration(
        array $confluenceConfig,
        bool $expectedPreRender,
        string $expectedCliPath,
        string $expectedFormat,
        ?int $expectedWidth,
        ?int $expectedHeight
    ) {
        $config = ConfigBuilder::withMode()
            ->withCache(false)
            ->with([
                'confluence' => array_merge([
                    'base_url' => 'https://test.confluence.com/',
                    'user' => 'test',
                    'pass' => 'test',
                ], $confluenceConfig),
            ])
            ->build();

        $confluenceConfigObj = $config->getConfluenceConfiguration();

        $this->assertEquals($expectedPreRender, $confluenceConfigObj->getPreRenderMermaid());
        $this->assertEquals($expectedCliPath, $confluenceConfigObj->getMermaidCliPath());
        $this->assertEquals($expectedFormat, $confluenceConfigObj->getMermaidImageFormat());
        $this->assertEquals($expectedWidth, $confluenceConfigObj->getMermaidImageWidth());
        $this->assertEquals($expectedHeight, $confluenceConfigObj->getMermaidImageHeight());
    }

    public static function provideMermaidConfigData()
    {
        return [
            'Default values' => [
                [],
                false,
                'mmdc',
                'svg',
                null,
                null,
            ],
            'Pre-render enabled with defaults' => [
                [
                    'pre_render_mermaid' => true,
                ],
                true,
                'mmdc',
                'svg',
                null,
                null,
            ],
            'Custom CLI path' => [
                [
                    'pre_render_mermaid' => true,
                    'mermaid_cli_path' => 'npx @mermaid-js/mermaid-cli',
                ],
                true,
                'npx @mermaid-js/mermaid-cli',
                'svg',
                null,
                null,
            ],
            'PNG format' => [
                [
                    'pre_render_mermaid' => true,
                    'mermaid_image_format' => 'png',
                ],
                true,
                'mmdc',
                'png',
                null,
                null,
            ],
            'Invalid format defaults to SVG' => [
                [
                    'pre_render_mermaid' => true,
                    'mermaid_image_format' => 'jpg',
                ],
                true,
                'mmdc',
                'svg',
                null,
                null,
            ],
            'With width' => [
                [
                    'pre_render_mermaid' => true,
                    'mermaid_image_width' => 1024,
                ],
                true,
                'mmdc',
                'svg',
                1024,
                null,
            ],
            'With height' => [
                [
                    'pre_render_mermaid' => true,
                    'mermaid_image_height' => 600,
                ],
                true,
                'mmdc',
                'svg',
                null,
                600,
            ],
            'With both width and height' => [
                [
                    'pre_render_mermaid' => true,
                    'mermaid_image_width' => 1024,
                    'mermaid_image_height' => 600,
                ],
                true,
                'mmdc',
                'svg',
                1024,
                600,
            ],
        ];
    }

    public function testGetMermaidKrokiUrl()
    {
        $config = ConfigBuilder::withMode()
            ->withCache(false)
            ->with([
                'confluence' => [
                    'base_url' => 'https://test.confluence.com/',
                    'user' => 'test',
                    'pass' => 'test',
                    'mermaid_kroki_url' => 'https://kroki.io',
                ],
            ])
            ->build();

        $confluenceConfig = $config->getConfluenceConfiguration();

        $this->assertEquals('https://kroki.io', $confluenceConfig->getMermaidKrokiUrl());
    }

    public function testGetMermaidKrokiUrlReturnsNullWhenNotSet()
    {
        $config = ConfigBuilder::withMode()
            ->withCache(false)
            ->with([
                'confluence' => [
                    'base_url' => 'https://test.confluence.com/',
                    'user' => 'test',
                    'pass' => 'test',
                ],
            ])
            ->build();

        $confluenceConfig = $config->getConfluenceConfiguration();

        $this->assertNull($confluenceConfig->getMermaidKrokiUrl());
    }
}
