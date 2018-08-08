<?php

$search_post_type =  epico_opt('search_post_type');

if (!$search_post_type) { // set on product when update theme 
    $search_post_type = 'product';
}

?>

<div class="search-form">
    <form role="search" method="get" class="searchform"  action="<?php echo esc_url( home_url('/') );?>">
	    <div>
		    <label class="screen-reader-text" for="s"><?php esc_html_x( 'Search for:', 'label', 'vitrine' ); ?></label>
		    <input type="text" placeholder="<?php echo esc_attr_x( 'Search', 'submit button', 'vitrine' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
		    <input type="hidden" name="post_type" value="<?php echo  $search_post_type;?>">
		
	    </div>
	</form>
</div>