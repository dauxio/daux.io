<?php namespace Todaymade\Daux\ContentTypes\Markdown;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\Tree\Builder;
use Todaymade\Daux\Tree\Root;

class AdmonitionRendererTest extends TestCase
{
    public function provideAdmonitionCases()
    {
        return [
            // Note with content before and after
            [
                <<<'EOD'
                    hey

                    !!! note "Title"
                        Content in

                    content outside
                    EOD,
                <<<'EOD'
                    <p>hey</p>
                    <div class="Admonition Admonition--note"><p class="Admonition__title">Title</p><p>Content in</p></div>
                    <p>content outside</p>
                    EOD
            ],
            [
                <<<'EOD'
                    !!! note
                        Note's content
                    EOD,
                <<<'EOD'
                    <div class="Admonition Admonition--note"><p>Note’s content</p></div>
                    EOD
            ],
            [
                <<<'EOD'
                    !!! warning "WARNING !!!"
                        * one
                        * two
                    EOD,
                <<<'EOD'
                    <div class="Admonition Admonition--warning"><p class="Admonition__title">WARNING !!!</p><ul>
                    <li>one</li>
                    <li>two</li>
                    </ul></div>
                    EOD
            ],
            [
                <<<'EOD'
                    !!! danger "This is dangerous"
                        > one
                        > two
                    EOD,
                <<<'EOD'
                    <div class="Admonition Admonition--danger"><p class="Admonition__title">This is dangerous</p><blockquote>
                    <p>one
                    two</p>
                    </blockquote></div>
                    EOD
            ],
        ];
    }

    /**
     * @dataProvider provideAdmonitionCases
     *
     * @param mixed $expected
     * @param mixed $input
     */
    public function testRenderLink($input, $expected)
    {
        $structure = [
            'Content' => ['Page.md' => 'some text content'],
        ];
        $root = vfsStream::setup('root', null, $structure);

        $config = ConfigBuilder::withMode()
            ->withDocumentationDirectory($root->url())
            ->withValidContentExtensions(['md'])
            ->with([
                'base_url' => '',
            ])
            ->build();

        $tree = new Root($config);
        Builder::build($tree, []);

        $config = ConfigBuilder::withMode()->build();
        $config->setTree($tree);

        $converter = new CommonMarkConverter(['daux' => $config]);

        $this->assertEquals($expected, trim($converter->convert($input)->getContent()));
    }
}
