<div class="ep-main">
    <?php
    $panels = $this->template['panels'];

    foreach($panels as $panelKey => $panel)
    {
    ?>
        <div id="<?php echo esc_attr($panelKey); ?>" class="panel">
            <div class="content-head">
                <div class="ep-content-wrap">
                    <a href="#" class="save-button" >
                        <?php echo $this->epico_GetImage('save_icon.png', 'Save', 'save-icon'); ?>
                        <?php echo $this->epico_GetImage('loading24.gif', 'Loading', 'loading-icon'); ?>
                        <div><?php esc_html_e('Save', 'vitrine'); ?></div>
                    </a>
                    <h3><?php echo esc_html($panel['title']); ?></h3>

                    <div class="support">
                        <a href="<?php echo esc_url($this->template['document-url']); ?>"><?php esc_html_e('Documentation', 'vitrine'); ?></a><span class="separator"></span><a href="<?php echo esc_attr($this->template['support-url']); ?>"><?php esc_html_e('Support', 'vitrine'); ?></a>
                    </div>
                </div>
            </div>
            <?php echo $this-> epico_GetTemplate('section', $panel['sections']); ?>
        </div>
    <?php
    }
    ?>
</div>