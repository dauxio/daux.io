<?php
namespace Todaymade\Daux\Format\HTML;

use League\Plates\Engine;
use League\Plates\Exception\TemplateNotFound;
use League\Plates\Template\Name;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class TemplateResolverTest extends TestCase
{
    public function testTakesTheFirstDirectoryContainingTheTemplate()
    {
        $root = $this->getRoot();
        $resolver = new TemplateResolver([$root->url() . '/theme', $root->url() . '/builtin']);

        $this->assertEquals(
            $root->url() . '/theme/home.php',
            $resolver($this->getName('theme::home'))
        );
    }

    public function testFallsBackToTheNextDirectory()
    {
        $root = $this->getRoot();
        $resolver = new TemplateResolver([$root->url() . '/theme', $root->url() . '/builtin']);

        $this->assertEquals(
            $root->url() . '/builtin/content.php',
            $resolver($this->getName('theme::content'))
        );
    }

    public function testResolvesNestedTemplates()
    {
        $root = $this->getRoot();
        $resolver = new TemplateResolver([$root->url() . '/theme', $root->url() . '/builtin']);

        $this->assertEquals(
            $root->url() . '/builtin/layout/00_layout.php',
            $resolver($this->getName('theme::layout/00_layout'))
        );
    }

    public function testListsAllSearchedPathsWhenNothingMatches()
    {
        $root = $this->getRoot();
        $resolver = new TemplateResolver([$root->url() . '/theme', $root->url() . '/builtin']);

        try {
            $resolver($this->getName('theme::missing'));
            $this->fail('Expected a TemplateNotFound exception');
        } catch (TemplateNotFound $e) {
            $this->assertEquals(
                [$root->url() . '/theme/missing.php', $root->url() . '/builtin/missing.php'],
                $e->paths()
            );
            $this->assertStringContainsString($root->url() . '/theme/missing.php', $e->getMessage());
            $this->assertStringContainsString($root->url() . '/builtin/missing.php', $e->getMessage());
        }
    }

    private function getRoot()
    {
        return vfsStream::setup('root', null, [
            'theme' => [
                'home.php' => 'theme home',
            ],
            'builtin' => [
                'home.php' => 'builtin home',
                'content.php' => 'builtin content',
                'layout' => [
                    '00_layout.php' => 'builtin layout',
                ],
            ],
        ]);
    }

    private function getName($name)
    {
        $engine = new Engine(vfsStream::url('root'));
        $engine->addFolder('theme', vfsStream::url('root/theme'));

        return new Name($engine, $name);
    }
}
