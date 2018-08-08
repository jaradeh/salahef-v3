<?php

require_once('ivalueprovider.php');

class epico_PostOptionsProvider implements IValueProvider {

    public function epico_GetValue($key)
    {
        global $post;
        return get_post_meta( $post->ID, $key, true );
	
    }
}