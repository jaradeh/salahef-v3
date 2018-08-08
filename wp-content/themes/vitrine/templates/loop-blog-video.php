<div class="post-content">
    <?php
    //Parse the content for the first occurrence of video url
    $video = epico_extract_video_info(get_post_meta(get_the_ID(), 'video-id', true ));
    
    if($video != null)
    {
        $w = 500; $h = 280;
        epico_get_video_meta($video);

        if(array_key_exists('width', $video))
        {
            $w = $video['width'];
            $h = $video['height'];
        }

        //Extract video ID
        ?>
        <div class="post-media video-frame">
        <?php
            if($video['type'] == 'youtube')
                $src = "http://www.youtube.com/embed/" . $video['id'];
            else
                $src = "http://player.vimeo.com/video/" . $video['id'] . "?color=ff4c2f";
        ?>
        <iframe src="<?php echo esc_url($src); ?>" width="<?php echo esc_attr($w); ?>" height="<?php echo esc_attr($h); ?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
        </div>
    <?php
    } 
          get_template_part( 'templates/loop', "blog-meta" );?>    
</div>
<hr>
