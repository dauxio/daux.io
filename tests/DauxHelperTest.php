<?php
namespace Todaymade\Daux;

use PHPUnit\Framework\TestCase;

class DauxHelperTest extends TestCase
{
    public function providerGetFilenames()
    {
        return [
            [['Page.html', 'Page'], 'Page.html'],
            [['Page.html', 'Page'], 'Page.md'],
            [['Page.html', 'Page'], 'Page'],
            [['Code_Highlighting.html', 'Code_Highlighting'], '05_Code_Highlighting.md'],
        ];
    }

    /**
     * @dataProvider providerGetFilenames
     */
    public function testGetFilenames($expected, $node)
    {
        $config = new Config();
        $config['valid_content_extensions'] = ['md'];

        $this->assertEquals($expected, DauxHelper::getFilenames($config, $node));
    }
}
