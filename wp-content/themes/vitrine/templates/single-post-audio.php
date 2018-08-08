<div <?php post_class(); ?>>
    <?php
    $sidebar=epico_opt('blog-sidebar-position');//to make header audio fill in span8
    if($sidebar=='no-sidebar'){?>
        <div class="row"><div class="span8 offset2 single-post-header">
    <?php } ?>
    <div class="post-media">
        <?php

        //Parse the content for the first occurrence of video url
        $audio = epico_extract_audio_info(epico_get_meta('audio-url'));

        if($audio != null)
        {
            //Extract video ID
            ?>
            <div class="post-media audio-frame">
                <?php
                echo epico_soundcloud_get_embed($audio['url']);
                ?>
            </div>
        <?php
        }?>
    </div>
    <?php
    if($sidebar=='no-sidebar'){?>
         </div></div>
    <?php } 
    get_template_part( 'templates/single', "post-meta" );
    the_content();
    wp_link_pages();?>
</div>
<?php   get_template_part( 'templates/single', "post-content" );?>