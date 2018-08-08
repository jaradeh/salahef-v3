<?php wp_nonce_field( 'theme-post-meta-form', THEME_NAME_SEO . '_post_nonce' ); ?>

<div class="ep-container post-meta">
    <div class="ep-main">
        <?php
            $this->epico_SetWorkingDirectory(epico_path_combine(EPICO_THEME_LIB, 'forms/templates'));
            echo $this-> epico_GetTemplate('section', $vars);
        ?>
    </div>
</div>
