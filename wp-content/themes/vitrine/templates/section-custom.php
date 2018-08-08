<?php 
    // Visual Composer option , true if Visual composer is be Active in page
    $wpb_vc_js_status = get_post_meta( get_the_ID(), "_wpb_vc_js_status", true );
    
?>

<!-- Custom Section  -->
<section class="epicoSection customSection">
    <h1 style="display:none!important"><?php the_title(); ?> </h1>
    <div id="ep_<?php echo esc_attr($post->post_name);?>" class="wrap">
       <?php
            if ( $wpb_vc_js_status == 'false' || $wpb_vc_js_status == '' ) { ?>
            
                <!-- container div Add when Classic Editor is Enable - Visual Composer not Enable -->
                <div class="container customContent clearfix">
            
        <?php } else { ?>
        
                <div class="customContent clearfix">
                    
        <?php }  ?>
             
            <?php the_content(); ?>
            
        </div>
    </div>
</section>
<!-- End Custom Section  -->
