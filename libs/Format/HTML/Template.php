<?php namespace Todaymade\Daux\Format\HTML;

use League\Plates\Engine;
use Todaymade\Daux\Config;
use Todaymade\Daux\Daux;
use Todaymade\Daux\Tree\Content;
use Todaymade\Daux\Tree\Directory;

class Template
{
    protected $engine;

    protected $params;

    /**
     * @param string $base
     * @param string $theme
     */
    public function __construct(Config $params)
    {
        $this->params = $params;
    }

    public function getEngine(Config $params)
    {
        if ($this->engine) {
            return $this->engine;
        }

        $base = $params['templates'];
        $theme = $params['theme']['templates'];

        // Use internal templates if no templates
        // dir exists in the working directory
        if (!is_dir($base)) {
            $base = __DIR__ . '/../../../templates';
        }

        // Create new Plates instance
        $this->engine = new Engine($base);
        if (!is_dir($theme)) {
            $theme = $base;
        }
        $this->engine->addFolder('theme', $theme, true);

        $this->registerFunctions($this->engine);

        return $this->engine;
    }

    /**
     * @param string $name
     * @param array $data
     * @return string
     */
    public function render($name, array $data = [])
    {
        $engine = $this->getEngine($data['params']);

        $engine->addData([
            'base_url' => $data['params']['base_url'],
            'base_page' => $data['params']['base_page'],
            'page' => $data['page'],
            'params' => $data['params'],
            'tree' => $data['params']['tree'],
        ]);

        return $engine->render($name, $data);
    }

    protected function registerFunctions($engine)
    {
        $engine->registerFunction('get_navigation', function ($tree, $path, $current_url, $base_page, $mode) {
            $nav = $this->buildNavigation($tree, $path, $current_url, $base_page, $mode);

            return $this->renderNavigation($nav);
        });

        $engine->registerFunction('translate', function ($key) {
            $language = $this->params['language'];

            if (array_key_exists($key, $this->params['strings'][$language])) {
                return $this->params['strings'][$language][$key];
            }

            if (array_key_exists($key, $this->params['strings']['en'])) {
                return $this->params['strings']['en'][$key];
            }

            return "Unknown key $key";
        });

        $engine->registerFunction('get_breadcrumb_title', function ($page, $base_page) {
            $title = '';
            $breadcrumb_trail = $page['breadcrumb_trail'];
            $separator = $this->getSeparator($page['breadcrumb_separator']);
            foreach ($breadcrumb_trail as $key => $value) {
                $title .= '<a href="' . $base_page . $value . '">' . $key . '</a>' . $separator;
            }
            if ($page['filename'] === 'index' || $page['filename'] === '_index') {
                if ($page['title'] != '') {
                    $title = substr($title, 0, -1 * strlen($separator));
                }
            } else {
                $title .= '<a href="' . $base_page . $page['request'] . '">' . $page['title'] . '</a>';
            }

            return $title;
        });
    }

    private function renderNavigation($entries)
    {
        $nav = '';
        foreach ($entries as $entry) {
            if (array_key_exists('children', $entry)) {
                $icon = '<i class="Nav__arrow">&nbsp;</i>';

                if (array_key_exists('href', $entry)) {
                    $link = '<a href="' . $entry['href'] . '" class="folder">' . $icon . $entry['title'] . '</a>';
                } else {
                    $link = '<a href="#" class="aj-nav folder">' . $icon . $entry['title'] . '</a>';
                }

                $link .= $this->renderNavigation($entry['children']);
            } else {
                $link = '<a href="' . $entry['href'] . '">' . $entry['title'] . '</a>';
            }

            $nav .= "<li class='Nav__item $entry[class]'>$link</li>";
        }

        return "<ul class='Nav'>$nav</ul>";
    }

    private function buildNavigation(Directory $tree, $path, $current_url, $base_page, $mode)
    {
        $nav = [];
        foreach ($tree->getEntries() as $node) {
            $url = $node->getUri();
            if ($node instanceof Content) {
                if ($node->isIndex()) {
                    continue;
                }

                $link = ($path === '') ? $url : $path . '/' . $url;

                $nav[] = [
                    'title' => $node->getTitle(),
                    'href' => $base_page . $link,
                    'class' => $node->isHotPath() ? 'Nav__item--active' : '',
                ];
            } elseif ($node instanceof Directory) {
                if (!$node->hasContent()) {
                    continue;
                }

                $folder = [
                    'title' => $node->getTitle(),
                    'class' => $node->isHotPath() ? 'Nav__item--open' : '',
                ];

                if ($index = $node->getIndexPage()) {
                    $folder['href'] = $base_page . $index->getUrl();
                }

                //Child pages
                $new_path = ($path === '') ? $url : $path . '/' . $url;
                $folder['children'] = $this->buildNavigation($node, $new_path, $current_url, $base_page, $mode);

                if (!empty($folder['children'])) {
                    $folder['class'] .= ' has-children';
                }

                $nav[] = $folder;
            }
        }

        return $nav;
    }

    /**
     * @param string $separator
     * @return string
     */
    private function getSeparator($separator)
    {
        switch ($separator) {
            case 'Chevrons':
                return ' <svg class="Page__header--separator" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 477.175 477.175"><path d="M360.73 229.075l-225.1-225.1c-5.3-5.3-13.8-5.3-19.1 0s-5.3 13.8 0 19.1l215.5 215.5-215.5 215.5c-5.3 5.3-5.3 13.8 0 19.1 2.6 2.6 6.1 4 9.5 4 3.4 0 6.9-1.3 9.5-4l225.1-225.1c5.3-5.2 5.3-13.8.1-19z"/></svg> ';
            default:
                return $separator;
        }
    }
}
