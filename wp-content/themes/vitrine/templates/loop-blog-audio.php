<div class="post-content">
    <?php
    //Parse the content for the first occurrence of video url
    $audio = epico_extract_audio_info(get_post_meta(get_the_ID(), 'audio-url', true ));
    
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
    }
     get_template_part( 'templates/loop', "blog-meta" );?>    
</div>
<hr>