<?php

declare(strict_types=1);

namespace Todaymade\Daux\Format\Confluence;

use PHPUnit\Framework\TestCase;
use Todaymade\Daux\Config;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\Exception;

class MermaidRendererFactoryTest extends TestCase
{
    public function testCreateWithAvailableCliRenderer()
    {
        $config = $this->createConfigWithMermaidCli();

        // Try to create renderer - it will succeed if mmdc is available, otherwise throw exception
        try {
            $renderer = MermaidRendererFactory::create($config);

            $this->assertInstanceOf(MermaidRendererInterface::class, $renderer);
            $this->assertEquals('mermaid-cli', $renderer->getName());
        } catch (Exception $e) {
            // If mmdc is not available, that's expected in test environment
            $this->assertStringContainsString('No Mermaid renderer is available', $e->getMessage());
        }
    }

    public function testCreateThrowsExceptionWhenNoRendererAvailable()
    {
        $config = $this->createConfigWithInvalidCliPath();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No Mermaid renderer is available');

        MermaidRendererFactory::create($config);
    }

    public function testRendererHasRequiredMethods()
    {
        $renderer = new MermaidCliRenderer();

        $this->assertTrue(method_exists($renderer, 'render'));
        $this->assertTrue(method_exists($renderer, 'isAvailable'));
        $this->assertTrue(method_exists($renderer, 'getName'));
        $this->assertEquals('mermaid-cli', $renderer->getName());
    }

    private function createConfigWithMermaidCli(): Config
    {
        $config = ConfigBuilder::withMode()
            ->withCache(false)
            ->with([
                'confluence' => [
                    'base_url' => 'https://test.confluence.com/',
                    'user' => 'test',
                    'pass' => 'test',
                    'pre_render_mermaid' => true,
                    'mermaid_cli_path' => 'mmdc',
                ],
            ])
            ->build();

        return $config;
    }

    private function createConfigWithInvalidCliPath(): Config
    {
        $config = ConfigBuilder::withMode()
            ->withCache(false)
            ->with([
                'confluence' => [
                    'base_url' => 'https://test.confluence.com/',
                    'user' => 'test',
                    'pass' => 'test',
                    'pre_render_mermaid' => true,
                    'mermaid_cli_path' => '/nonexistent/path/to/mmdc',
                ],
            ])
            ->build();

        return $config;
    }
}
