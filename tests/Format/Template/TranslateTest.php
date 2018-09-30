<?php
namespace Todaymade\Daux\Format\Template;

use org\bovigo\vfs\vfsStream;
use Todaymade\Daux\Config;
use Todaymade\Daux\Daux;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Format\HTML\Template;
use Todaymade\Daux\Tree\Builder;
use Todaymade\Daux\Tree\Entry;
use Todaymade\Daux\Tree\Root;
use PHPUnit\Framework\TestCase;

/**
 * Class TranslateTest
 *
 * @package Todaymade\Daux\Format\Template
 */
class TranslateTest extends TestCase
{
    protected function getTree(Config $config)
    {
        $structure = [
            'en' => [
                'Page.md' => 'some text content',
            ],
            'it' => [
                'Page.md' => 'another page',
            ],
        ];
        $root = vfsStream::setup('root', null, $structure);

        $config->setDocumentationDirectory($root->url());
        $config['valid_content_extensions'] = ['md'];
        $config['mode'] = Daux::STATIC_MODE;
        $config['index_key'] = 'index.html';

        $tree = new Root($config);
        Builder::build($tree, []);

        return $tree;
    }

    public function translateDataProvider()
    {
        return [
            ['Previous', 'en'],
            ['Pagina precedente', 'it'],
        ];
    }

    /**
     * @dataProvider translateDataProvider
     *
     * @param $expectedTranslation
     * @param $language
     */
    public function testTranslate($expectedTranslation, $language)
    {
        $current = $language . '/Page.html';
        $entry = $this->prophesize(Entry::class);

        $config = new Config();
        $config['tree']      = $this->getTree($config);
        $config['title']     = '';
        $config['index']     = $entry->reveal();
        $config['language']  = $language;
        $config['base_url']  = '';
        $config['base_page'] = '';
        $config['templates'] = '';
        $config['page']['language'] = $language;

        $config['html'] = [
            'search'           => '',
            'float'            => false,
            'toggle_code'      => false,
            'piwik_analytics'  => '',
            'google_analytics' => '',
        ];
        $config['theme'] = [
            'js'        => [''],
            'css'       => [''],
            'fonts'     => [''],
            'favicon'   => '',
            'templates' => 'name',
        ];
        $config['strings'] = [
            'en' => ['Link_previous' => 'Previous',],
            'it' => ['Link_previous' => 'Pagina precedente',],
        ];


        $config->setCurrentPage(DauxHelper::getFile($config['tree'], $current));

        $template = new Template($config);
        $value = $template->getEngine($config)->getFunction('translate')->call(null, ['Link_previous']);

        $this->assertEquals($expectedTranslation, $value);
    }
}
