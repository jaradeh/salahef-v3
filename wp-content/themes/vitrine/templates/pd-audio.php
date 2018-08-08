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