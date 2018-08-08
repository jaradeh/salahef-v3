<?php 

if ( epico_get_meta("display-top-slider") != '1'  || (is_shop() && epico_is_shop_ajax_request()) || epico_get_meta("snap-to-scroll") == 1) {
    echo '<div id="home" class="hidehome"></div>';
    return;
}

    //overlay Options
    $homeOverlayTexture = epico_get_meta('slider-overlay-texture');
    $homeOverlayColor =  epico_get_meta('slider-overlay-color');
    $homeOverlayOpacity = (intval(epico_get_meta('slider-overlay-opacity')))/100;
    $sliderType = epico_get_meta('slider-type');
    $epicoSliderCategory = epico_get_meta('epico-slider-cat');
    $epicoSliderMode = epico_get_meta('slide-mode');
    $homeRevSlide = epico_get_meta('home-rev-slide');
    $sliderStartBtn = epico_get_meta('slider-start-btn');
    $sliderStartBtnType = epico_get_meta('slider-start-type');
    $sliderRevContainer = epico_get_meta('rev-slider-container');
    $sliderParallax = epico_get_meta('slider-parallax');

?>
<section id="home" class="<?php echo esc_attr(($sliderParallax == 1) ? 'sliderParallax ':'');  if ( $sliderType == 'slider-revolutionSlider' && $homeRevSlide != 'no-slider' ){ echo esc_attr($homeRevSlide); } ?>">
    <h1 style="display:none!important"> <?php esc_html_e('Home section','vitrine');?> </h1>

    <!-- inline Style For parallax background Color and color Opacity  -->
    <?php if ( isset($homeOverlayColor) || isset($homeOverlayOpacity)) { ?>

        <style type="text/css" media="all" scoped>
            #home .<?php echo esc_attr($homeOverlayTexture);?> {
                <?php if ( isset($homeOverlayColor) ) { ?> background-color:<?php echo esc_attr($homeOverlayColor);?>; <?php } ?>
                <?php if ( isset($homeOverlayOpacity) ) { ?> opacity:<?php echo esc_attr($homeOverlayOpacity);?>; <?php } ?>
            }
            <?php if ( isset($homeOverlayColor) ) { ?>
                #home .videoColorMask  {
                     background-color:<?php echo esc_attr($homeOverlayColor);?> !important;
                }
             <?php } ?>
        </style>

    <?php } ?>
    <div class="slider-wrap">
        <div class="homeWrap">
            <?php

                $slideNumber= "";
                if ( $sliderType == 'epico-slider' ) {
                    $slides = get_posts(array(
                      'post_type' => 'slider',
                      'numberposts' => -1,
                      'tax_query' => array(
                        array(
                          'taxonomy' => 'slider_cats',
                          'field' => 'slug',
                          'terms' =>  $epicoSliderCategory,
                        )
                      )
                    ));

                $slideNumber = count($slides);

                //duplicate slides if number of slides are 2
                if( $slideNumber == 2 && $epicoSliderMode !='fade')
                {
                    $slides[] = $slides[0];
                    $slides[] = $slides[1];
                    $slideNumber = 4;
                }

                if ( $slideNumber > 1 ) { ?>
                    <!-- FullScreen Slider -->
                    <div class="wrap <?php echo esc_attr($epicoSliderMode); ?>" id="fullScreenSlider">

                        <!-- Swiper -->
                        <div id="slides" class="swiper-container no-select">
                            <div class="swiper-wrapper">

                                <?php
                                $iterator = 0;
                                foreach ($slides as $slideID) {
                                    $meta_values = get_post_meta( $slideID->ID );
                                    $caption_style = 'style1';
                                    $caption_dark_light = 1;
                                    $caption_align = 'center';
                                    $caption_container = 'caption-fullwidth';

                                    if(isset($meta_values['caption-container']) && $meta_values['caption-container']== '0')
                                        $caption_container = '';

                                    if(isset($meta_values['caption-style']))
                                        $caption_style = $meta_values['caption-style'][0];

                                    if(isset($meta_values['caption-dark-light']))
                                        $caption_dark_light = $meta_values['caption-dark-light'][0];

                                    if(isset($meta_values['caption-align']))
                                        $caption_align = str_replace('-',' ',$meta_values['caption-align'][0]);

                                    if( $caption_dark_light == 1 )
                                        $caption_dark_light = 'light';
                                    else
                                        $caption_dark_light = 'dark';

                                    if($caption_style == 'style5')
                                        $caption_align = 'center';
                                    
                                    ?>
                                    
                                    <div class="swiper-slide no-select <?php if($meta_values['background-type'][0] == 'video' && ($iterator == 0 || $iterator == count($slides)-1 )) { echo "has-duplicate-video"; } ?>">
                                        <div class="swiper-slide-image" style="background:url(<?php if($meta_values['background-type'][0] == 'image'){ echo esc_url($meta_values['background-image'][0]); } ?>)">
                                            <div class="caption <?php echo esc_attr($caption_style) . ' ' . esc_attr($caption_dark_light) . ' ' . esc_attr($caption_align);  ?>">
                                                <?php
                                                $title = $subtitle = $subtitle2 = $caption_element_type = $output = $title_style = $subtitle_style = $subtitle2_style = '';
                                                $caption_image = $caption_icon = $button_url = $button_text = $button = '';
                                                $caption_element_animation = false;

                                                /* slider caption styles */
                                                //title
                                                if(isset($meta_values['title-color']))
                                                {
                                                    $title_style = 'color:' . esc_attr($meta_values['title-color'][0] ).' !important;';
                                                    $title_style .= 'border-color:' . esc_attr($meta_values['title-color'][0] ).' !important;';
                                                }

                                                if(isset($meta_values['title-size']))
                                                {
                                                    $font_size = str_replace("px","",esc_attr($meta_values['title-size'][0]));
                                                    $title_style .= 'font-size:' . esc_attr($font_size) .'px;';
                                                }


                                                if(isset($meta_values['title-font-weight']))
                                                {
                                                    $title_style .= 'font-weight:' . esc_attr($meta_values['title-font-weight'][0]) .';';
                                                }

                                                if($meta_values['title-font-type'][0] == 'google')
                                                {
                                                    $font = key((Array)json_decode($meta_values['title-font'][0]));
                                                    $title_style  .= 'font-family:' . $font .',sans-serif;';
                                                }
                                                elseif($meta_values['title-font-type'][0] == 'custom')
                                                {
                                                    if(isset($meta_values['title-custom-font-name']))
                                                    {
                                                        $title_style  .= 'font-family:' . $meta_values['title-custom-font-name'][0] .';';
                                                        if(isset($meta_values['title-custom-font-weight']))
                                                        {
                                                            $subtitle_style .= 'font-weight:' . esc_attr($meta_values['title-custom-font-weight'][0]) .';';
                                                        }                                                        
                                                    }

                                                }


                                                //Subtitle
                                                if(isset($meta_values['subtitle-color']))
                                                    $subtitle_style .= 'color:' . esc_attr($meta_values['subtitle-color'][0] ).' !important;';

                                                if(isset($meta_values['subtitle-size']))
                                                {
                                                    $font_size = str_replace("px","",esc_attr($meta_values['subtitle-size'][0]));
                                                    $subtitle_style .= 'font-size:' . esc_attr($font_size) .'px;';
                                                }

                                                if($meta_values['subtitle-font-type'][0] == 'google')
                                                {
                                                    $font = key((Array)json_decode($meta_values['subtitle-font'][0]));
                                                    $subtitle_style  .= 'font-family:' . $font .',sans-serif;';
                                                    if(isset($meta_values['subtitle-font-weight']))
                                                    {
                                                        $subtitle_style .= 'font-weight:' . esc_attr($meta_values['subtitle-font-weight'][0]) .';';
                                                    }
                                                }
                                                elseif($meta_values['subtitle-font-type'][0] == 'custom')
                                                {
                                                    if(isset($meta_values['subtitle-custom-font-name']))
                                                    {
                                                        $subtitle_style  .= 'font-family:' . $meta_values['subtitle-custom-font-name'][0] .';';
                                                        if(isset($meta_values['subtitle-custom-font-weight']))
                                                        {
                                                            $subtitle_style .= 'font-weight:' . esc_attr($meta_values['subtitle-custom-font-weight'][0]) .';';
                                                        }
                                                    }
                                                }


                                                //Subtitle2
                                                if(isset($meta_values['subtitle2-color']))
                                                    $subtitle2_style .= 'color:' . esc_attr($meta_values['subtitle2-color'][0] ).'  !important;';

                                                if(isset($meta_values['subtitle2-size']))
                                                {
                                                    $font_size = str_replace("px","",esc_attr($meta_values['subtitle2-size'][0]));
                                                    $subtitle2_style .= 'font-size:' . esc_attr($font_size) .'px;';
                                                }

                                                if(isset($meta_values['subtitle2-font-weight']))
                                                {
                                                    $subtitle2_style .= 'font-weight:' . esc_attr($meta_values['subtitle2-font-weight'][0]) .';';
                                                }

                                                if($meta_values['subtitle2-font-type'][0] == 'google')
                                                {
                                                    $font = key((Array)json_decode($meta_values['subtitle2-font'][0]));
                                                    $subtitle2_style  .= 'font-family:' . $font .',sans-serif;';
                                                }
                                                elseif($meta_values['subtitle2-font-type'][0] == 'custom')
                                                {
                                                    if(isset($meta_values['subtitle2-custom-font-name']))
                                                    {
                                                        $subtitle2_style  .= 'font-family:' . $meta_values['subtitle2-custom-font-name'][0] .';';
                                                        if(isset($meta_values['subtitle2-custom-font-weight']))
                                                        {
                                                            $subtitle_style .= 'font-weight:' . esc_attr($meta_values['subtitle2-custom-font-weight'][0]) .';';
                                                        }
                                                    }
                                                }

                                                if($title_style != '' || $subtitle_style != '' || $subtitle2_style != '') {
                                                ?>
                                                    <style type="text/css" media="all" scoped>

                                                        <?php
                                                        if($title_style != '')
                                                        {
                                                         echo ".caption div.slide_$slideID->ID .caption-title"; ?>
                                                            {
                                                                <?php echo $title_style; ?>
                                                            }
                                                        <?php                                                                  
                                                        }

                                                        
                                                        if($subtitle_style != '')
                                                        {
                                                         echo " .caption div.slide_$slideID->ID .caption-subtitle"; ?>
                                                            {
                                                                <?php echo $subtitle_style; ?>
                                                            }
                                                        <?php                                                                    
                                                        }

                                                        if($subtitle2_style != '')
                                                        {
                                                         echo ".caption div.slide_$slideID->ID .caption-subtitle2"; ?>
                                                            {
                                                                <?php echo $subtitle2_style; ?>
                                                            }
                                                        <?php                                                                    
                                                        }
                                                        ?>

                                                    </style>
                                                <?php
                                                }


                                                if(isset($meta_values['subtitle-text']))
                                                    $subtitle = '<div class="caption-subtitle">'. $meta_values['subtitle-text'][0] . '</div>';

                                                if(isset($meta_values['title-text']))
                                                    $title = '<div class="caption-title">'. $meta_values['title-text'][0] . '</div>';
                                        
                                                if(isset($meta_values['subtitle2-text']))
                                                    $subtitle2 = '<div class="caption-subtitle2">'. $meta_values['subtitle2-text'][0] . '</div>';

                                                if(isset($meta_values['caption-icon-image']))
                                                    $caption_element_type = $meta_values['caption-icon-image'][0];

                                                if(isset($meta_values['caption-icon-image-animation']) && $meta_values['caption-icon-image-animation'][0] == '1')
                                                    $caption_element_animation = true;

                                                if(isset($meta_values['caption-image']))
                                                    $caption_image = '<img alt="caption_image" class="caption-image ' .(($caption_element_animation == true) ? 'animated' : '' ) .'" src="'. esc_url($meta_values['caption-image'][0]) . '">';

                                                if(isset($meta_values['caption-icon']))
                                                    $caption_icon = '<span class="caption-icon icon-'. esc_attr($meta_values['caption-icon'][0]) . ' ' .(($caption_element_animation == true) ? 'animated' : '' ) . '" ' . ((isset($meta_values['caption-icon-color'])) ? 'style="color:'. esc_attr($meta_values['caption-icon-color'][0]) .'"' : '' ) .'></span>';

                                                if(isset($meta_values['button-url']))
                                                    $button_url = $meta_values['button-url'][0];

                                                if(isset($meta_values['button-text']))
                                                    $button_text = $meta_values['button-text'][0];


                                                if(!empty($button_url) && !empty($button_text))
                                                {
                                                    $button_text_color = $button_text_hover_color = $button_background_style = $button_background_color = '';

                                                    if(isset($meta_values['button-text-color']))
                                                        $button_text_color = $meta_values['button-text-color'][0];

                                                    if(isset($meta_values['button-text-hover-color']))
                                                        $button_text_hover_color = $meta_values['button-text-hover-color'][0];

                                                    if(isset($meta_values['button-background-style']))
                                                        $button_background_style = $meta_values['button-background-style'][0];

                                                    if(isset($meta_values['button-background-color']))
                                                        $button_background_color = $meta_values['button-background-color'][0];


                                                    //remove http and https from URL - do_shrtcode accept link without http and https  
                                                    $button_url = preg_replace("(^https?://)", "", $button_url );


                                                    $button = do_shortcode('[button button_hover_style="style2" text="' . esc_attr($button_text) . '" url="url:' . antispambot($button_url) .'" button_text_color="' . esc_attr($button_text_color) . '" button_text_hover_color="'. esc_attr($button_text_hover_color) . '" button_bg_style="' . esc_attr($button_background_style) .'" button_bg_border_color="' . esc_attr($button_background_color) .'" button_border_radius="20px" alignment="'. esc_attr(str_replace('bottom','',$caption_align)) .'"]');
                                                }
                                                ?>
                                                <div class="caption-container slide_<?php echo esc_attr($slideID->ID); ?> <?php echo esc_attr($caption_container); ?>">
                                                
                                                    <?php

                                                        $image_icon = '';
                                                        if($caption_element_type == 'image')
                                                        {
                                                            $image_icon = $caption_image;
                                                        }
                                                        else
                                                        {
                                                            $image_icon = $caption_icon;
                                                        }

                                                        if($caption_style =='style1')
                                                        {
                                                            $output  =  $subtitle;
                                                            $output  .=  $title;
                                                            $output  .=  $subtitle2;
                                                            echo $output;
                                                        }
                                                        elseif($caption_style == 'style2')
                                                        {

                                                            $output  = $image_icon;
                                                            $output  .=  $title;
                                                            $output  .=  $subtitle;
                                                            echo $output;
                                                        }
                                                        elseif($caption_style == 'style3')
                                                        {
                                                            $output  = $image_icon;
                                                            $output  .=  $title;
                                                            $output  .=  $subtitle;
                                                            echo $output;
                                                        }
                                                        elseif($caption_style == 'style4')
                                                        {
                                                            $output  = '<div class="caption-box">';
                                                            $output  .= $image_icon;
                                                            $output  .=  $title;
                                                            $output  .=  $subtitle;
                                                            $output  .= '</div>';
                                                            
                                                            echo $output; 
                                                        }
                                                        else
                                                        {
                                                            $output  = '<div class="caption-box-top">';
                                                            $output  .= $image_icon;
                                                            $output  .= '</div>';
                                                            $output  .=  $title;
                                                            $output  .=  $subtitle;
                                                            $output  .= '<div class="caption-box-bottom"></div>';
                                                            echo $output; 
                                                        }
                                                    ?>
                                                </div>

                                                <?php
                                                if( $epicoSliderMode == 'epico' || $epicoSliderMode == 'fade')
                                                {
                                                    ?>
                                                    <!-- Add Arrows -->
                                                    <div class="swiper-button-next no-selectn"></div>
                                                    <div class="swiper-button-prev no-select"></div>
                                                    <?php
                                                }

                                                if($button != '')
                                                {
                                                    echo '<div class="caption-container button-container ' . esc_attr($caption_container) .'">' .$button . '</div>';
                                                }

                                                ?>
                                                
                                            </div>
                                            <?php
                                                if( $epicoSliderMode == 'fade')      
                                                {   
                                                    ?>      
                                                    <div class="slide-image-fade" style="background:url(<?php if($meta_values['background-type'][0] == 'image'){ echo esc_url($meta_values['background-image'][0]); } ?>)"></div>       
                                                    <?php       
                                                }

                                                if($meta_values['background-type'][0] == 'video') {
                                                    $video_preview ='';
                                                    if(isset($meta_values['video-prev-image'][0]))
                                                        $video_preview = $meta_values['video-prev-image'][0];

                                                    $output = '';
                                                    $output .= '<div style="background-image:url('. esc_url($video_preview) .')" class="hidden-desktop videoHomePreload"></div>';
                                                    $output .= '<div class="videoHome videoWrap"><video class="video " width="1920" height="800" poster="' . esc_url(EPICO_THEME_IMAGES_URI .'/video-transparent-poster.png') . '" style="background:url('. esc_url($video_preview) .') 0 0;" preload="auto" loop="true" autoplay="true">';

                                                    if ( isset( $meta_values['video-url-webm'][0] ) ) {
                                                        // WebM/VP8 for Firefox4, Opera, and Chrome
                                                        $output .= '<source type="video/webm" src="'. esc_url($meta_values['video-url-webm'][0]) .'" />';
                                                    }

                                                    if (isset( $meta_values['video-url-mp4'][0] ) ) {
                                                        //MP4 for Safari, IE9, iPhone, iPad, Android, and Windows Phone 7
                                                        $output .= '<source type="video/mp4" src="'. esc_url($meta_values['video-url-mp4'][0]) .'" />';
                                                    }

                                                    if ( isset( $meta_values['video-url-mp4'][0] ) ) {
                                                        //Flash fallback for non-HTML5 browsers without JavaScript
                                                        $output .= '<object width="320" height="240" type="application/x-shockwave-flash" data="'.get_template_directory_uri().'/assets/js/flashmediaelement.swf">';
                                                        $output .= '<param name="movie" value="'.esc_url(get_template_directory_uri().'/assets/js/flashmediaelement.swf') . '" />';
                                                        $output .= '<param name="flashvars" value="controls=true&file='. esc_url($meta_values['video-url-mp4'][0]) .'" />';
                                                        $output .= '<img src="'.esc_url($video_preview).'" width="1920" height="800" title="No video playback capabilities" alt="#" />';
                                                        $output .= '</object>';
                                                    }
                                                    $output .= '</video></div>';

                                                    echo $output;

                                                }
                                                
                                            ?>
                                            <div class="homeTexture sectionOverlay <?php echo esc_attr($homeOverlayTexture); ?>"></div>
                                            
                                        </div>
                                    </div>

                                <?php
                                $iterator++;
                                }
                                ?>
                            </div>
                            <!-- Next Arrows -->
                            <div class="arrows-button-next no-select arrows-button-next<?php echo esc_attr($id); if( $epicoSliderMode != 'slide') { echo " hidden-desktop"; }?>"></div>
                            <!-- Prev Arrows -->
                            <div class="arrows-button-prev no-select arrows-button-prev<?php echo esc_attr($id); if( $epicoSliderMode != 'slide') { echo " hidden-desktop"; }?>"></div>
                        <?php

                        if( $sliderStartBtn == 1)
                        {
                            ?>
                            <div id="caption-start" class="<?php echo esc_attr($sliderStartBtnType); ?>">
                            <?php
                            if( $sliderStartBtnType == 'style-2')
                            {
                                ?>
                                <div class="dot"></div>
                                <?php

                            }
                            ?>
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                </div>
                <?php } 

                else if ($slideNumber == 1) { 

                    $meta_values = get_post_meta( $slides[0]->ID );

                    $caption_style = 'style1';
                    $caption_dark_light = 1;
                    $caption_align = 'center';

                    $caption_container = 'caption-fullwidth';

                    if(isset($meta_values['caption-container']) && $meta_values['caption-container']== '0')
                        $caption_container = '';

                    if(isset($meta_values['caption-style']))
                        $caption_style = $meta_values['caption-style'][0];

                    if(isset($meta_values['caption-dark-light']))
                        $caption_dark_light = $meta_values['caption-dark-light'][0];

                    if(isset($meta_values['caption-align']))
                        $caption_align = str_replace('-',' ',$meta_values['caption-align'][0]);

                    if( $caption_dark_light == 1 )
                        $caption_dark_light = 'light';
                    else
                        $caption_dark_light = 'dark';

                    if($caption_style == 'style5')
                        $caption_align = 'center';
                    ?>
                    <!-- FullScreen Image -->
                    <div id="fullScreenImage" class="fullScreenImage" style="background:url(<?php if($meta_values['background-type'][0] == 'image'){ echo esc_url($meta_values['background-image'][0]); } ?>);" >
                        <div class="caption <?php echo esc_attr($caption_style . ' ' . $caption_dark_light . ' ' . $caption_align);  ?>">
                            <div class="caption-container  <?php echo esc_attr($caption_container); ?>">

                                <?php


                                $title = $subtitle = $subtitle2 = $caption_element_type = $output = $title_style = $subtitle_style = $subtitle2_style = '';
                                $caption_image = $caption_icon = $button_url = $button_text = $button = '';
                                $caption_element_animation = false;

                                /* slider caption styles */
                                //title
                                if(isset($meta_values['title-color']))
                                {
                                    $title_style = 'color:' . esc_attr($meta_values['title-color'][0] ).';';
                                    $title_style .= 'border-color:' . esc_attr($meta_values['title-color'][0] ).';';
                                }

                                if(isset($meta_values['title-size']))
                                {
                                    $font_size = str_replace("px","",esc_attr($meta_values['title-size'][0]));
                                    $title_style .= 'font-size:' . esc_attr($font_size) .'px;';
                                }


                                if(isset($meta_values['title-font-weight']))
                                {
                                    $title_style .= 'font-weight:' . esc_attr($meta_values['title-font-weight'][0]) .';';
                                }

                                if($meta_values['title-font-type'][0] == 'google')
                                {
                                    $font = key((Array)json_decode($meta_values['title-font'][0]));
                                    $title_style  .= 'font-family:' . $font .',sans-serif;';
                                }
                                elseif($meta_values['title-font-type'][0] == 'custom')
                                {
                                    if(isset($meta_values['title-custom-font-name']))
                                    {
                                        $title_style  .= 'font-family:' . $meta_values['title-custom-font-name'][0] .';';
                                        if(isset($meta_values['title-custom-font-weight']))
                                        {
                                            $subtitle_style .= 'font-weight:' . esc_attr($meta_values['title-custom-font-weight'][0]) .';';
                                        }
                                    }
                                }


                                //Subtitle
                                if(isset($meta_values['subtitle-color']))
                                    $subtitle_style .= 'color:' . esc_attr($meta_values['subtitle-color'][0] ).';';

                                if(isset($meta_values['subtitle-size']))
                                {
                                    $font_size = str_replace("px","",esc_attr($meta_values['subtitle-size'][0]));
                                    $subtitle_style .= 'font-size:' . esc_attr($font_size) .'px;';
                                }

                                if($meta_values['subtitle-font-type'][0] == 'google')
                                {
                                    $font = key((Array)json_decode($meta_values['subtitle-font'][0]));
                                    $subtitle_style  .= 'font-family:' . $font .',sans-serif;';
                                    if(isset($meta_values['subtitle-font-weight']))
                                    {
                                        $subtitle_style .= 'font-weight:' . esc_attr($meta_values['subtitle-font-weight'][0]) .';';
                                    }
                                }
                                elseif($meta_values['subtitle-font-type'][0] == 'custom')
                                {
                                    if(isset($meta_values['subtitle-custom-font-name']))
                                    {
                                        $subtitle_style  .= 'font-family:' . $meta_values['subtitle-custom-font-name'][0] .';';
                                        if(isset($meta_values['subtitle-custom-font-weight']))
                                        {
                                            $subtitle_style .= 'font-weight:' . esc_attr($meta_values['subtitle-custom-font-weight'][0]) .';';
                                        }
                                    }
                                }


                                //Subtitle2
                                if(isset($meta_values['subtitle2-color']))
                                    $subtitle2_style .= 'color:' . esc_attr($meta_values['subtitle2-color'][0] ).';';

                                if(isset($meta_values['subtitle2-size']))
                                {
                                    $font_size = str_replace("px","",esc_attr($meta_values['subtitle2-size'][0]));
                                    $subtitle2_style .= 'font-size:' . esc_attr($font_size) .'px;';
                                }

                                if(isset($meta_values['subtitle2-font-weight']))
                                {
                                    $subtitle2_style .= 'font-weight:' . esc_attr($meta_values['subtitle2-font-weight'][0]) .';';
                                }

                                if($meta_values['subtitle2-font-type'][0] == 'google')
                                {
                                    $font = key((Array)json_decode($meta_values['subtitle2-font'][0]));
                                    $subtitle2_style  .= 'font-family:' . $font .',sans-serif;';
                                }
                                elseif($meta_values['subtitle2-font-type'][0] == 'custom')
                                {
                                    if(isset($meta_values['subtitle2-custom-font-name']))
                                    {
                                        $subtitle2_style  .= 'font-family:' . $meta_values['subtitle2-custom-font-name'][0] .';';
                                        if(isset($meta_values['subtitle2-custom-font-weight']))
                                        {
                                            $subtitle_style .= 'font-weight:' . esc_attr($meta_values['subtitle2-custom-font-weight'][0]) .';';
                                        }
                                    }
                                }

                                if($title_style != '' || $subtitle_style != '' || $subtitle2_style != '') {
                                ?>
                                    <style type="text/css" media="all" scoped>

                                        <?php
                                        if($title_style != '')
                                        {
                                         echo "div#fullScreenImage .caption-title"; ?>
                                            {
                                                <?php echo $title_style; ?>
                                            }
                                        <?php                                                                  
                                        }

                                        
                                        if($subtitle_style != '')
                                        {
                                         echo "div#fullScreenImage .caption-subtitle"; ?>
                                            {
                                                <?php echo $subtitle_style; ?>
                                            }
                                        <?php                                                                    
                                        }

                                        if($subtitle2_style != '')
                                        {
                                         echo "div#fullScreenImage .caption-subtitle2"; ?>
                                            {
                                                <?php echo $subtitle2_style; ?>
                                            }
                                        <?php                                                                    
                                        }
                                        ?>

                                    </style>
                                <?php
                                }


                                if(isset($meta_values['subtitle-text']))
                                    $subtitle = '<div class="caption-subtitle">'. $meta_values['subtitle-text'][0] . '</div>';

                                if(isset($meta_values['title-text']))
                                    $title = '<div class="caption-title">'. $meta_values['title-text'][0] . '</div>';
                        
                                if(isset($meta_values['subtitle2-text']))
                                    $subtitle2 = '<div class="caption-subtitle2">'. $meta_values['subtitle2-text'][0] . '</div>';

                                if(isset($meta_values['caption-icon-image']))
                                    $caption_element_type = $meta_values['caption-icon-image'][0];

                                if(isset($meta_values['caption-icon-image-animation']) && $meta_values['caption-icon-image-animation'][0] == '1')
                                    $caption_element_animation = true;

                                if(isset($meta_values['caption-image']))
                                    $caption_image = '<img alt="caption_image" class="caption-image ' .(($caption_element_animation == true) ? 'animated' : '' ) .'" src="'. esc_url($meta_values['caption-image'][0]) . '">';

                                if(isset($meta_values['caption-icon']))
                                    $caption_icon = '<span class="caption-icon icon-'. esc_attr($meta_values['caption-icon'][0]) . ' ' .(($caption_element_animation == true) ? 'animated' : '' ) .'" ' . ((isset($meta_values['caption-icon-color'])) ? 'style="color:'. esc_attr($meta_values['caption-icon-color'][0]) .'"' : '' ) .'></span>';

                                if(isset($meta_values['button-url']))
                                    $button_url = $meta_values['button-url'][0];

                                if(isset($meta_values['button-text']))
                                    $button_text = $meta_values['button-text'][0];


                                if(!empty($button_url) && !empty($button_text))
                                {

                                    $button_text_color = $button_text_hover_color = $button_background_style = $button_background_color = '';

                                    if(isset($meta_values['button-text-color']))
                                        $button_text_color = $meta_values['button-text-color'][0];

                                    if(isset($meta_values['button-text-hover-color']))
                                        $button_text_hover_color = $meta_values['button-text-hover-color'][0];

                                    if(isset($meta_values['button-background-style']))
                                        $button_background_style = $meta_values['button-background-style'][0];

                                    if(isset($meta_values['button-background-color']))
                                        $button_background_color = $meta_values['button-background-color'][0];

                                    //remove http and https from URL - do_shrtcode accept link without http and https  
                                    $button_url = preg_replace("(^https?://)", "", $button_url );

                                    $button = do_shortcode('[button button_hover_style="style2" text="' . esc_attr($button_text) . '" url="url:' . antispambot($button_url) .'" button_text_color="' . esc_attr($button_text_color) . '" button_text_hover_color="'. esc_url($button_text_hover_color) . '" button_bg_style="' . esc_attr($button_background_style) .'" button_bg_border_color="' . esc_attr($button_background_color).'" button_border_radius="20px" alignment="'. esc_attr(str_replace('bottom','',$caption_align)) .'"]');
                                }

                                $image_icon = '';
                                if($caption_element_type == 'image')
                                {
                                    $image_icon = $caption_image;
                                }
                                else
                                {
                                    $image_icon = $caption_icon;
                                }

                                if($caption_style =='style1')
                                {
                                    $output  =  $subtitle;
                                    $output  .=  $title;
                                    $output  .=  $subtitle2;
                                    echo $output;
                                }
                                elseif($caption_style == 'style2')
                                {

                                    $output  = $image_icon;
                                    $output  .=  $title;
                                    $output  .=  $subtitle;
                                    echo $output;
                                }
                                elseif($caption_style == 'style3')
                                {
                                    $output  = $image_icon;
                                    $output  .=  $title;
                                    $output  .=  $subtitle;
                                    echo $output;
                                }
                                elseif($caption_style == 'style4')
                                {
                                    $output  = '<div class="caption-box">';
                                    $output  .= $image_icon;
                                    $output  .=  $title;
                                    $output  .=  $subtitle;
                                    $output  .= '</div>';
                                    echo $output; 
                                }
                                else
                                {
                                    $output  = '<div class="caption-box-top">';
                                    $output  .= $image_icon;
                                    $output  .= '</div>';
                                    $output  .=  $title;
                                    $output  .=  $subtitle;
                                    $output  .= '<div class="caption-box-bottom"></div>';
                                    echo $output; 
                                }

                                ?>

                            </div>
                            <?php
                            if($button != '')
                            {
                                echo '<div class="caption-container button-container '. esc_attr($caption_container) . '">' .$button . '</div>';
                            }
                            
                            ?>
                      
                        </div>
                        <?php
                            if( $sliderStartBtn == 1)
                            {
                                ?>
                                <div id="caption-start" class="<?php echo esc_attr($sliderStartBtnType); ?>">
                                    <?php
                                    if( $sliderStartBtnType == 'style-2')
                                    {
                                        ?>
                                        <div class="dot"></div>
                                        <?php

                                    }
                                    ?>
                                </div>
                            <?php
                            }

                        if($meta_values['background-type'][0] == 'video') {
                                $video_preview ='';
                                if(isset($meta_values['video-prev-image'][0]))
                                    $video_preview = $meta_values['video-prev-image'][0];

                                $output = '';
                                $output .= '<div style="background-image:url('. esc_url($video_preview) .')" class="hidden-desktop videoHomePreload"></div>';
                                $output .= '<div class="videoHome videoWrap"><video class="video " width="1920" height="800" poster="' . esc_url(EPICO_THEME_IMAGES_URI .'/video-transparent-poster.png') . '" style="background:url('. esc_url($video_preview) .') 0 0;" preload="auto" loop="true" autoplay="true">';

                                if ( isset( $meta_values['video-url-webm'][0] ) ) {
                                    // WebM/VP8 for Firefox4, Opera, and Chrome
                                    $output .= '<source type="video/webm" src="'. esc_url($meta_values['video-url-webm'][0]) .'" />';
                                }

                                if (isset( $meta_values['video-url-mp4'][0] ) ) {
                                    //MP4 for Safari, IE9, iPhone, iPad, Android, and Windows Phone 7
                                    $output .= '<source type="video/mp4" src="'. esc_url($meta_values['video-url-mp4'][0]) .'" />';
                                }

                                if ( isset( $meta_values['video-url-mp4'][0] ) ) {
                                    //Flash fallback for non-HTML5 browsers without JavaScript
                                    $output .= '<object width="320" height="240" type="application/x-shockwave-flash" data="'.get_template_directory_uri().'/assets/js/flashmediaelement.swf">';
                                    $output .= '<param name="movie" value="'.esc_url(get_template_directory_uri().'/assets/js/flashmediaelement.swf') . '" />';
                                    $output .= '<param name="flashvars" value="controls=true&file='. esc_url($meta_values['video-url-mp4'][0]) .'" />';
                                    $output .= '<img src="'.esc_url($video_preview).'" width="1920" height="800" title="No video playback capabilities" />';
                                    $output .= '</object>';
                                }
                                $output .= '</video></div>';

                                echo $output;

                            }
                            
                            ?>
            
                        <div class="homeTexture sectionOverlay <?php echo esc_attr($homeOverlayTexture); ?>"></div>
                    </div>
                <?php } ?>
            <?php } else if ( $sliderType == 'slider-revolutionSlider' ){ ?>
                <!-- Revolution Slider -->
                <div id="homeHeight" class="revolutionSlider">
                    <?php
                    if($sliderRevContainer != 0) { ?>
                    <div class="container">
                    <?php
                    }
                        if(class_exists('RevSliderFront') && $homeRevSlide != 'no-slider')
                        {
                            $homeRevolutionslider = '[rev_slider '. $homeRevSlide .']';
                            echo do_shortcode($homeRevolutionslider);
                        }
                    ?>
                    <?php if($sliderRevContainer != 0) { ?>
                    </div>
                    <?php
                    }
                    ?>
                </div>

            <?php } 
            
        ?>

        </div>
    </div>
</section>
<div id="startHere"></div>