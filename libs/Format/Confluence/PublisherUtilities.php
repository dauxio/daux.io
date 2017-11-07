<?php namespace Todaymade\Daux\Format\Confluence;

class PublisherUtilities
{
    public static function niceTitle($title)
    {
        if ($title == 'index.html') {
            return 'Homepage';
        }

        return rtrim(strtr($title, ['index.html' => '', '.html' => '']), '/');
    }

    public static function shouldUpdate($local, $local_content, $published, $threshold)
    {
        if (!array_key_exists('content', $published)) {
            return true;
        }

        $trimmed_local = trim($local_content);
        $trimmed_distant = trim($published['content']);

        if ($trimmed_local == $trimmed_distant) {
            return false;
        }

        // I consider that if the files are 98% identical you
        // don't need to update. This will work for false positives.
        // But sadly will miss if it's just a typo update
        // This is configurable with `update_threshold`
        $threshold = 100 - $threshold;

        if ($threshold < 100) {
            similar_text($trimmed_local, $trimmed_distant, $percent);
            if ($percent > $threshold) {
                return false;
            }
        }

        //DEBUG
        if (getenv('DEBUG') && strtolower(getenv('DEBUG')) != 'false') {
            $prefix = 'static/export/';
            if (!is_dir($prefix)) {
                mkdir($prefix, 0777, true);
            }
            $url = $local->getFile()->getUrl();
            file_put_contents($prefix . strtr($url, ['/' => '_', '.html' => '_local.html']), $trimmed_local);
            file_put_contents($prefix . strtr($url, ['/' => '_', '.html' => '_distant.html']), $trimmed_distant);
        }

        return true;
    }
}
