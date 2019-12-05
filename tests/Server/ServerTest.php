<?php namespace Todaymade\Daux\Server;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;
use Todaymade\Daux\Format\HTML\RawPage;
use Todaymade\Daux\Config;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\Daux;
use Todaymade\Daux\Server\Server;
use org\bovigo\vfs\vfsStream;

class ServerTest extends TestCase
{
    function testCreateResponse() {

        $structure = [
            'index.md' => 'first page',
            'Page.md' => 'another page',
            'somefile.css' => 'body {}',
            '22.png' => ''
        ];
        $root = vfsStream::setup('root', null, $structure);


        $config = ConfigBuilder::withMode(Daux::LIVE_MODE)
            ->withDocumentationDirectory($root->url())
            ->build();

        $daux = new Daux($config, new NullOutput());

        $daux->generateTree();

        $page = new RawPage($daux->tree['somefile.css']->getPath());

        $server = new Server($daux);
        $response = $server->createResponse($page)->prepare(Request::createFromGlobals());

        $this->assertEquals("text/css", $response->headers->get('Content-Type'));
    }
}

