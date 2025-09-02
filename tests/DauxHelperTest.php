<?php namespace Todaymade\Daux;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DauxHelperTest extends TestCase
{
    /**
     * @param mixed $expected
     * @param mixed $node
     */
    #[DataProvider('providerGetFilenames')]
    public function testGetFilenames($expected, $node)
    {
        $config = ConfigBuilder::withMode()
            ->withValidContentExtensions(['md'])
            ->build();

        $this->assertEquals($expected, DauxHelper::getFilenames($config, $node));
    }

    public static function providerGetFilenames()
    {
        return [
            [['Page.html', 'Page'], 'Page.html'],
            [['Page.html', 'Page'], 'Page.md'],
            [['Page.html', 'Page'], 'Page'],
            [['Code_Highlighting.html', 'Code_Highlighting'], '05_Code_Highlighting.md'],
        ];
    }
}
