<?php if ($params['html']['search']) { ?>
    <script>
        <?php
        $search_strings = [
            "Search_one_result",
            "Search_results",
            "Search_no_results",
            "Search_common_words_ignored",
            "Search_too_short",
            "Search_one_character_or_more",
            "Search_should_be_x_or_more",
            "Link_previous",
            "Link_next",
        ];
        $search_translations = [];
        foreach($search_strings as $key) {
            $search_translations[$key] = $this->translate($key);
        }
        ?>

        window.searchLanguage = <?= json_encode($page['language']) ?>;
        window.searchTranslation = <?= json_encode($search_translations) ?>;
    </script>

    <!-- Tipue Search -->
    <script type="text/javascript" src="<?php echo $base_url; ?>tipuesearch/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>tipuesearch/tipuesearch.js"></script>

    <script>
        window.onunload = function(){}; // force $(document).ready to be called on back/forward navigation in firefox
        $(function() {
            tipuesearch({
                'base_url': '<?php echo $base_url?>'
            });
        });
    </script>
<?php } ?>