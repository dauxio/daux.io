<?php namespace Todaymade\Daux\Server;

use Todaymade\Daux\Format\HTML\SimplePage;
use Todaymade\Daux\Format\HTML\Template;

class ErrorPage extends SimplePage
{
    const NORMAL_ERROR_TYPE = 'NORMAL_ERROR';
    const MISSING_PAGE_ERROR_TYPE = 'MISSING_PAGE_ERROR';
    const FATAL_ERROR_TYPE = 'FATAL_ERROR';

    /**
     * @var \Todaymade\Daux\Config
     */
    private $config;

    /**
     * @param string $title
     * @param string $content
     * @param \Todaymade\Daux\Config $config
     */
    public function __construct($title, $content, $config)
    {
        parent::__construct($title, $content);
        $this->config = $config;
    }

    /**
     * @return string
     */
    protected function generatePage()
    {
        $config = $this->config;
        $page = [
            'title' => $this->title,
            'content' => $this->getPureContent(),
            'language' => '',
        ];

        $template = new Template($config);

        return $template->render('error', ['page' => $page, 'config' => $config]);
    }
}
