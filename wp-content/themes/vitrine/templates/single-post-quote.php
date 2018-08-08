<div class="post-content">
    
    <?php
    $quote_content = get_post_meta( get_the_ID(), "quote_content", true );
    $quote_author = get_post_meta( get_the_ID(), "quote_author", true );
    $id = get_the_ID();
    ?>
    <style>
        .post-image.quote_<?php echo esc_attr($id); ?>{
            <?php   if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {?>
                        background-image:url(<?php echo wp_get_attachment_url( get_post_thumbnail_id(  ) ); ?>);
            <?php  }?>
            <?php   if (empty( $quote_content )&& empty( $quote_author ) ) {?>
                        display:none!important;
            <?php  }?>
        }
    </style>
    <?php
    $sidebar=epico_opt('blog-sidebar-position');//to make header fill in span8
    if($sidebar=='no-sidebar'){?>
        <div class="row"><div class="span8 offset2 single-post-header">
    <?php } ?>
    <div class="post-media quotePostType">
        <div class="post-image quote_<?php echo esc_attr($id); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
        <?php if ( !empty( $quote_content )) { ?>
            <div class="quote_content ep-icon icon-quotes-right"><h3><?php echo esc_attr($quote_content); ?></h3></div>
        <?php }
              if ( !empty( $quote_author )) { ?>
            <div class="quote_author"><h3><?php echo esc_attr($quote_author); ?></h3></div>
        <?php }?>
        </div>
    </div>  
<?php
    if($sidebar=='no-sidebar'){ ?>
        </div></div>
    <?php }
    
    get_template_part( 'templates/single', "post-meta" );
    the_content();
    wp_link_pages();?>
</div>
<?php   get_template_part( 'templates/single', "post-content" );?>