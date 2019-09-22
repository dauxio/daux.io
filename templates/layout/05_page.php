<?php $this->layout('theme::layout/00_layout') ?>
<div class="Columns content">
    <aside class="Columns__left Collapsible">
        <button type="button" class="Button Collapsible__trigger" aria-controls="sidebar_content" aria-expanded="false" aria-label="<?= $this->translate("Toggle_navigation") ?>">
            <span class="Collapsible__trigger__bar"></span>
            <span class="Collapsible__trigger__bar"></span>
            <span class="Collapsible__trigger__bar"></span>
        </button>

        <?php $this->insert('theme::partials/navbar_content', ['params' => $params]); ?>

        <div class="Collapsible__content" id="sidebar_content">
            <!-- Navigation -->
            <?php
            $rendertree = $tree;
            $path = '';

            if ($page['language'] !== '') {
                $rendertree = $tree[$page['language']];
                $path = $page['language'];
            }

            echo $this->get_navigation($rendertree, $path, isset($params['request']) ? $params['request'] : '', $base_page, $params['mode']);
            ?>


            <div class="Links">
                <?php if (!empty($params['html']['links'])) { ?>
                    <hr/>
                    <?php foreach ($params['html']['links'] as $name => $url) { ?>
                        <a href="<?= $url ?>" target="_blank"><?= $name ?></a>
                        <br />
                    <?php } ?>
                <?php } ?>
            </div>

            <?php if ($params['html']['toggle_code']) { ?>
                <div class="CodeToggler">
                    <hr/>
                    <label class="Checkbox"><?=$this->translate("CodeBlocks_show") ?>
                        <input type="checkbox" class="CodeToggler__button--main" checked="checked"/>
                        <div class="Checkbox__indicator"></div>
                    </label>
                </div>
            <?php } ?>

                <?php if (!empty($params['html']['twitter'])) { ?>
                    <div class="Twitter">
                        <hr/>
                        <?php $this->insert('theme::partials/twitter_buttons', ['params' => $params]); ?>
                    </div>
                <?php } ?>

                <?php if (!empty($params['html']['powered_by'])) { ?>
                    <div class="PoweredBy">
                        <hr/>
                        <?= $params['html']['powered_by'] ?>
                    </div>
                <?php } ?>
        </div>
    </aside>
    <div class="Columns__right">
        <div class="Columns__right__content">
            <div class="doc_content">
                <?= $this->section('content'); ?>
            </div>
        </div>
    </div>
</div>
