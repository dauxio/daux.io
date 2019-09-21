<!DOCTYPE html>
<html class="no-js" lang="<?=$params['language'] ?>">
<head>
    <title><?= $page['title']; ?> <?= ($page['title'] != $params['title'])? '- ' . $params['title'] : "" ?></title>
<?php //SEO meta tags...
    if (array_key_exists('attributes', $page) && array_key_exists('description', $page['attributes'])) {
        echo "    <meta name=\"description\" content=\"{$page['attributes']['description']}\">\n";
    } elseif (array_key_exists('tagline', $params)) {
        echo "    <meta name=\"description\" content=\"{$params['tagline']}\">\n";
    }
    if (array_key_exists('attributes', $page) && array_key_exists('keywords', $page['attributes'])) {
        echo "    <meta name=\"keywords\" content=\"{$page['attributes']['keywords']}\">\n";
    }
    if (array_key_exists('attributes', $page) && array_key_exists('author', $page['attributes'])) {
        echo "    <meta name=\"author\" content=\"{$page['attributes']['author']}\">\n";
    } elseif (array_key_exists('author', $params)) {
        echo "    <meta name=\"author\" content=\"{$params['author']}\">\n";
    }
?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" href="<?= $params['theme']['favicon']; ?>" type="image/x-icon">

    <!-- Mobile -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- JS -->
    <script>
        window.base_url = "<?php echo $base_url?>";
        document.documentElement.classList.remove('no-js');
    </script>

    <!-- Font -->
    <?php foreach ($params['theme']['fonts'] as $font) {
        echo "<link href='$font' rel='stylesheet' type='text/css'>";
    } ?>

    <!-- CSS -->
    <?php foreach ($params['theme']['css'] as $css) {
        echo "<link href='$css' rel='stylesheet' type='text/css'>";
    } ?>

    <?php if ($params['html']['search']) { ?>
        <!-- Search -->
        <link href="<?= $base_url; ?>_libraries/search.css" rel="stylesheet">
    <?php } ?>
</head>
<body class="<?= $this->section('classes'); ?>">
    <?= $this->section('content'); ?>

    <?php
    if ($params['html']['google_analytics']) {
        $this->insert('theme::partials/google_analytics', ['analytics' => $params['html']['google_analytics'], 'host' => array_key_exists('host', $params) ? $params['host'] : '']);
    }
    if ($params['html']['piwik_analytics']) {
        $this->insert('theme::partials/piwik_analytics', ['url' => $params['html']['piwik_analytics'], 'id' => $params['html']['piwik_analytics_id']]);
    }
    ?>

    <!-- JS -->
    <?php foreach ($params['theme']['js'] as $js) {
        echo '<script src="' . $js . '"></script>';
    } ?>

    <?php $this->insert('theme::partials/search_script', ['page' => $page, 'base_url' => $base_url]); ?>

</body>
</html>
