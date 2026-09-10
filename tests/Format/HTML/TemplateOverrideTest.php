<?php
namespace Todaymade\Daux\Format\HTML;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\Daux;
use Todaymade\Daux\DauxHelper;

class TemplateOverrideTest extends TestCase
{
    /**
     * An override directory only has to contain the templates it wants to
     * change, the other ones are taken from the templates shipped with Daux.
     *
     * @see https://github.com/dauxio/daux.io/issues/329
     */
    public function testIncompleteOverrideDirectoryFallsBackToTheShippedTemplates()
    {
        $daux = $this->getDaux(['templates' => 'vfs://root/templates']);
        $generator = new Generator($daux);

        $config = $daux->getConfig();
        DauxHelper::rebaseConfiguration($config, '');

        $daux->tree->setActiveNode($daux->tree['index.html']);
        $content = $generator->generateOne($daux->tree['index.html'], $config)->getContent();

        // 'home.php' is not in the override directory, the shipped one is used
        $this->assertStringContainsString('Homepage, welcome!', $content);
        $this->assertStringNotContainsString('OVERRIDDEN CONTENT', $content);
    }

    public function testOverrideDirectoryTakesPrecedenceOverTheShippedTemplates()
    {
        $daux = $this->getDaux(['templates' => 'vfs://root/templates']);
        $generator = new Generator($daux);

        $config = $daux->getConfig();
        DauxHelper::rebaseConfiguration($config, '');

        $daux->tree->setActiveNode($daux->tree['Content']['Page.html']);
        $content = $generator->generateOne($daux->tree['Content']['Page.html'], $config)->getContent();

        $this->assertStringContainsString('OVERRIDDEN CONTENT', $content);
    }

    protected function getDaux($moreConfig = [])
    {
        vfsStream::setup('root', null, [
            'docs' => [
                'index.md' => 'Homepage, welcome!',
                'Content' => [
                    'Page.md' => 'some text content',
                ],
            ],
            'templates' => [
                'content.php' => 'OVERRIDDEN CONTENT',
            ],
        ]);

        $config = ConfigBuilder::withMode()
            ->withDocumentationDirectory('vfs://root/docs')
            ->withValidContentExtensions(['md'])
            ->with($moreConfig)
            ->build();

        $daux = new Daux($config, new NullOutput());
        $daux->generateTree();

        return $daux;
    }
}
