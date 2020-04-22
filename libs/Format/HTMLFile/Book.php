<?php namespace Todaymade\Daux\Format\HTMLFile;

use Todaymade\Daux\Tree\Content;
use Todaymade\Daux\Tree\Directory;

class Book
{
    protected $cover;
    protected $tree;
    protected $pages = [];

    public function __construct(Directory $tree, $cover)
    {
        $this->tree = $tree;
        $this->cover = $cover;
    }

    protected function getStyles()
    {
        // TODO :: un-hardcode that
        return '<style>' . file_get_contents('themes/daux_singlepage/css/main.min.css') . '</style>';
    }

    protected function getPageUrl($page)
    {
        return 'file_' . str_replace('/', '_', $page->getUrl());
    }

    protected function buildNavigation(Directory $tree)
    {
        $nav = [];
        foreach ($tree->getEntries() as $node) {
            if ($node instanceof Content) {
                if ($node->isIndex()) {
                    continue;
                }

                $nav[] = [
                    'title' => $node->getTitle(),
                    'href' => '#' . $this->getPageUrl($node),
                ];
            } elseif ($node instanceof Directory) {
                if (!$node->hasContent()) {
                    continue;
                }

                $page_index = ($index = $node->getIndexPage()) ? $index : $node->getFirstPage();

                $nav[] = [
                    'title' => $node->getTitle(),
                    'href' => '#' . $this->getPageUrl($page_index),
                    'children' => $this->buildNavigation($node),
                ];
            }
        }

        return $nav;
    }

    private function renderNavigation($entries)
    {
        $nav = '';
        foreach ($entries as $entry) {
            if (array_key_exists('children', $entry)) {
                if (array_key_exists('href', $entry)) {
                    $link = '<a href="' . $entry['href'] . '" class="Nav__item__link--nopage">' . $entry['title'] . '</a>';
                } else {
                    $link = '<a href="#" class="Nav__item__link Nav__item__link--nopage">' . $entry['title'] . '</a>';
                }

                $link .= $this->renderNavigation($entry['children']);
            } else {
                $link = '<a href="' . $entry['href'] . '">' . $entry['title'] . '</a>';
            }

            $nav .= "<li>$link</li>";
        }

        return "<ul>$nav</ul>";
    }

    protected function generateTOC()
    {
        return '<h1>Table of Contents</h1>' .
        $this->renderNavigation($this->buildNavigation($this->tree)) .
        '</div><div class="PageBreak">&nbsp;</div>';
    }

    protected function generateCover()
    {
        return '<div>' .
        "<h1 style='font-size:40pt; margin-bottom:0;'>{$this->cover['title']}</h1>" .
        "<p><strong>{$this->cover['subject']}</strong> by {$this->cover['author']}</p>" .
        '</div><div class="PageBreak">&nbsp;</div>';
    }

    protected function generatePages()
    {
        $content = '';
        foreach ($this->pages as $page) {
            $content .= '<a id="' . $this->getPageUrl($page['page']) . '"></a>';
            $content .= '<h1>' . $page['page']->getTitle() . '</h1>';
            $content .= '<section class="s-content">' . $page['content'] . '</section>';
            $content .= '<div class="PageBreak">&nbsp;</div>';
        }

        return $content;
    }

    public function addPage($page, $content)
    {
        $this->pages[] = ['page' => $page, 'content' => $content];
    }

    public function generateHead()
    {
        $head = [
            "<title>{$this->cover['title']}</title>",
            "<meta name='description' content='{$this->cover['subject']}' />",
            "<meta name='author' content='{$this->cover['author']}'>",
            "<meta charset='UTF-8'>",
            $this->getStyles(),
        ];

        return '<head>' . implode('', $head) . '</head>';
    }

    public function generateBody()
    {
        return '<body>' . $this->generateCover() . $this->generateTOC() . $this->generatePages() . '</body>';
    }

    public function generate()
    {
        return '<!DOCTYPE html><html>' . $this->generateHead() . $this->generateBody() . '</html>';
    }
}
