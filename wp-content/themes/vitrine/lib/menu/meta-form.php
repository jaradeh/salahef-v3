<?php wp_nonce_field( 'theme-post-meta-form', THEME_NAME_SEO . '_post_nonce' ); ?>

<div class="epico-menu-field">
<?php
	global $post;

    $this->epico_SetWorkingDirectory(epico_path_combine(EPICO_THEME_LIB, 'forms/templates'));
    foreach($vars as $key => $settings)
    {
        $isArray = epico_array_value('array', epico_array_value('meta', $settings, array()), false);
        $generalKey = rtrim($key, "-". $post->ID);
        $val     = $this->epico_GetValue($generalKey);
        $fieldRepeat = 1;

        //Convert the key so it become array type
        if($isArray)
        {
            $key .= '[]';

            if(is_array($val))
                $fieldRepeat = max(count($val), $fieldRepeat);
        }

        for($m=0; $m<$fieldRepeat; $m++)
        {
            $value = is_array($val) ? epico_array_value($m, $val) : $val;

            echo $this->GetField($key, $settings, array('val' => $value));
        }
    }
?>
</div>