<?php
$output = $el_class = '';
extract(shortcode_atts(array(
    'el_class' => '',
    'row_type' => 'row',
    'type' => '',
    'background_img_id' => '',
    'background_position' => '',
    'background_color' => '',
    'min_height' => '',
    'parallax_speed' => '100',
    'video_height' => '550px',
    'video_mp4' => '',
    'video_webm' => '',
    'video_image' => '',
    'overlay_texture' => 'texture1',  
    'overlay_color' => '',  
    'row_padding_top' => '',
    'row_padding_bottom' => '',
    'row_padding_right' => '',
    'row_padding_left' => '',
    'row_responsive_padding_top' => '',
    'row_responsive_padding_bottom' => '',
    'row_responsive_padding_right' => '',
    'row_responsive_padding_left' => '',
    'row_margin_top' => '',
    'row_margin_bottom' => '',
    'row_responsive_margin_top' => '',
    'row_responsive_margin_bottom' => '',
    'equal_height' => 'false',
    'disable_element' =>'',
    'sound' => '',
    'gap' => '30',
    'full_height' => '',
    'columns_placement' => '',
    'row_animation_delay' => '0',
    'row_animation' => '',
    'responsive_animation' => '',
), $atts));

wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
wp_enqueue_style('js_composer_custom_css');

// generate uniqe id for Row 
$id = epico_sc_id('vc_row');

if ( 'yes' === $disable_element ) {
    return '';
}
$mobrowspace = "";
$rowspace = "";

//row spacing
if ( $row_padding_top != "" || $row_padding_bottom != "" || $row_padding_left != "" || $row_padding_right != "" || $row_responsive_padding_top != "" || $row_responsive_padding_bottom != ""|| $row_responsive_padding_left != ""|| $row_responsive_padding_right != "" || $row_margin_top != "" || $row_margin_bottom != "" ||
 $row_responsive_margin_top != "" ||  $row_responsive_margin_bottom != "") {
    
    if ( $row_responsive_padding_top != "") {
        $mobrowspace .= 'padding-top:'.$row_responsive_padding_top.'px !important;';
    } 
        
    if ( $row_responsive_padding_bottom != "") {
        $mobrowspace .= 'padding-bottom:'.$row_responsive_padding_bottom.'px !important;';
    } 
           
    if ( $row_responsive_padding_right != "") {
       $mobrowspace .= 'padding-right:'.$row_responsive_padding_right.'px !important;';
    } 
        
    if ( $row_responsive_padding_left != "") {
        $mobrowspace .= 'padding-left:'.$row_responsive_padding_left.'px !important;';
    } 
    if ( $row_padding_top != "") {
        $rowspace .= 'padding-top:'.$row_padding_top.'px;';
    } 
        
    if ( $row_padding_bottom != "") {
        $rowspace .= 'padding-bottom:'.$row_padding_bottom.'px;';
    } 
           
    if ( $row_padding_right != "") {
        $rowspace .= 'padding-right:'.$row_padding_right.'px;';
    } 
        
    if ( $row_padding_left != "") {
        $rowspace .= 'padding-left:'.$row_padding_left.'px;';
    } 
    
    if ( $row_responsive_margin_top != "") {
        $mobrowspace .= 'margin-top:'.$row_responsive_margin_top.'px !important;';
    } 
        
    if ( $row_responsive_margin_bottom != "") {
        $mobrowspace .= 'margin-bottom:'.$row_responsive_margin_bottom.'px !important;';
    }    
    if ( $row_margin_top != "") {
        $rowspace .= 'margin-top:'.$row_margin_top.'px;';
    } 
        
    if ( $row_margin_bottom != "") {
        $rowspace .= 'margin-bottom:'.$row_margin_bottom.'px;';
    }
}

$el_class = $this->getExtraClass($el_class);
$anim_class = '';
if($row_animation != '')
{
    $anim_class .= 'shortcodeAnimation';

    if($responsive_animation != '')
    {
        $anim_class .= ' no-responsive-animation';
    }
}

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'section ' . esc_attr($anim_class) . vc_shortcode_custom_css_class($el_class).$el_class, $this->settings['base']);

$row_class= '';

if ( ! empty( $full_height ) ) {
     $row_class .= 'vc_row-o-full-height';

    if ( ! empty( $columns_placement ) ) {

        $flex_row = true;
        $row_class .= ' vc_row-o-columns-' . $columns_placement;

    } else {
        // default value = middle
        $flex_row = true;
        $row_class .= ' vc_row-o-columns-middle';

    }

}

if (!empty($atts['gap'])) {

    $row_class .= ' vc_row  vc_column-gap vc_column-gap-'.$atts['gap'];

} else if(!empty($atts['gap']) && ($atts['gap'] == "zero")) {

    $row_class .= ' vc_row ';

} else { // default Value For Columns Gap

    $row_class .= ' vc_row vc_column-gap vc_column-gap-30';

}

if ( ! empty( $equal_height ) && $equal_height == "true" ) {
    $flex_row = true;
    $row_class .= ' vc_row vc_row-o-equal-height';
}

if ( ! empty( $flex_row ) ) {
     $row_class .= ' vc_row-flex';
}

$_image ="";
if($background_img_id != '' || $background_img_id != ' ') { 
    $_image = wp_get_attachment_image_src( $background_img_id, 'full');
}


$overlay_image ="";
if($video_image != '' && $video_image != ' ') { 
    $overlay_image = wp_get_attachment_image_src( $video_image, 'full');
}

if($type == "full_width"){
    $css_class_type =  " fullWidth";
} else {
    $css_class_type =  "";
}

if ($row_type == 'row') {

    $background_color_style = '';
    $background_image_style = '';
    if($background_color != "" || $_image != ""){
        if($background_color != ""){
            $background_color_style .='style="background-color:'. esc_attr($background_color) .';"';
        }
        if($_image != ""){
            $background_image_style .='style="background-image:url('. esc_url($_image[0]) .');';

            if($background_position != '' && $background_position != 'center center') {
                $background_image_style .='background-position:'. esc_attr($background_position) .';';
            }

            $background_image_style .= '"';
        }
    }
  

    $output .= '<div id='. esc_attr($id) .' class="ep-section background_cover row_section ' . esc_attr($css_class_type .' ' . $css_class ).'" ' . (($row_animation != '')? 'data-delay="' . esc_attr($row_animation_delay) . '" data-animation="' . esc_attr($row_animation) . '"' : '') .'>';
    $output .= '<div class="section-container" ' . $background_color_style .'>';

    if($background_image_style != '')
    {
        $output .= '<div class="background-img" ' . $background_image_style . '></div>';
    }
    

   if($mobrowspace)
    
    {
       $output .= '<div class="clearfix"></div>';
       $output .= '<style media="screen and (max-width:1140px)" type="text/css">';
       $output .= '#'. $id  .' .section-content-container{'. esc_html($mobrowspace) .' }';
       $output .= '</style>';

    }
    
   $output .= '<div class="clearfix"></div>';
   $output .= '<div class="section-content-container" style="' . esc_attr($rowspace) . '">';
   $output .='<div class="container"><div class="wpb_row vc_row-fluid parallax_content ' . esc_attr($row_class).'">';   
        
} else if ($row_type == 'parallax') {

    if($equal_height == "enable")
    {
        $css_class = 'equal-column-height';
    }


    $parallax_speed = (int)$parallax_speed;

    if($parallax_speed >=0 && $parallax_speed<=100)
    {
        $parallax_speed = $parallax_speed/4;
    }
    else
    {
        $parallax_speed = 20;
    }
    
    $output .='<div id='. esc_attr($id) .' class="ep-section parallax sectionOverlay' . ' ' . esc_attr($css_class . ' ' . $el_class .' '. $overlay_texture) .'" ' . (($row_animation != '')? 'data-delay="' . esc_attr($row_animation_delay) . '" data-animation="' . esc_attr($row_animation) . '"' : '') .' data-speed="'. esc_attr($parallax_speed) .'" style = "' . esc_attr($rowspace) . '';

    if($min_height !=''){

        if( strpos($min_height, 'px')) { } else {
            $min_height .= 'px';
        }

        $output .= ' min-height:' . esc_attr($min_height) . ';';
    }

    if (  $overlay_color != "" ) {   
            $output .= 'background-color:'. esc_attr($overlay_color).';';  
    } 


    $output .= '"';
    $output .= '>';
    $output .= '<div class="section-container">';
    $output .= '<div class="parallax-img" style="';
    $output .= ($background_img_id !== '' || $background_img_id !== ' ') ? " background-image:url('" . esc_url($_image[0]) . "');" : "";
    $output .= (($background_img_id !== '' || $background_img_id !== ' ') && $background_position != '' && $background_position != 'center center') ? " background-position:" . esc_attr($background_position) . ";" : "";
    $output .= '"></div>';
    $output .= '<div class="section-content-container">';
    $output .='<div class="container"><div class="wpb_row vc_row-fluid parallax_content ' . esc_attr($row_class).'">';
    
  
}
else if ($row_type == 'interactive_background') {
    $output = '';

    //$css_class = "";

    if($equal_height == "enable")
    {
        $css_class = 'equal-column-height';
    }
    
    $output .='<div id='. esc_attr($id) .' class="ep-section interactive-background' . ' ' . esc_attr($css_class . ' ' . $el_class) .'" ' . (($row_animation != '')? 'data-delay="' . esc_attr($row_animation_delay) . '" data-animation="' . esc_attr($row_animation) . '"' : '') .' style = "';

    if($min_height !=''){

        if( strpos($min_height, 'px')) { } else {
            $min_height .= 'px';
        }

        $output .= ' min-height:' . esc_attr($min_height) . ';';
    }
    
    $output .= '">';
    $output .= '<div class="section-container">';
    $output .= '<div class="interactive-background-image sectionOverlay ' . esc_attr($overlay_texture) .'" style = "';

    if (  $overlay_color != "" ) {   
        $output .= 'background-color:'. esc_attr($overlay_color).';';  
    } 

    $output .= '">';
    $output .= ($background_img_id !== '' || $background_img_id !== ' ') ? "<img src='" . esc_url($_image[0]) . "' alt=''>" : "";
    $output .= '</div>';
    $output .= '<div class="section-content-container" style="' . esc_attr($rowspace) . '">';
    $output .='<div class="container"><div class="wpb_row vc_row-fluid parallax_content ' . esc_attr($row_class).'">';
    
  
} else if ($row_type == 'video')  { 
    $output = '';
    $css_class_type =  " fullWidth";
    if($equal_height == "enable")
    {
        $css_class .= ' equal-column-height';
    }

     $v_image = wp_get_attachment_url($video_image);
    $output .= '<div class="wrap ep-section" style="height:'. esc_attr($video_height) . '; '. esc_attr($rowspace) .'" ' . (($row_animation != '')? 'data-delay="' . esc_attr($row_animation_delay) . '" data-animation="' . esc_attr($row_animation) . '"' : '') .'>';
    $output .= '<div class="vc_videowrap section-container" style="height:'. esc_attr($video_height) .'">';
    $output .= '<div style="background-image:url('. esc_url($v_image) .')" class="hidden-desktop videoHomePreload"></div>';
    $output .= '<div id='. esc_attr($id) .' class="videoHome sectionOverlay ' . esc_attr($css_class . ' '. $css_class_type .' '. $overlay_texture) .'" style = "';

    if ( $overlay_color != "" ) {   
        $output .= 'background-color:'. esc_attr($overlay_color) .';';  
    }

    $output .= '">';
    $output .= '<div class="videoWrap">';
    $output .= '<video ' . (($sound == 'off')? 'muted':'') . ' class="video" width="1900" height="800" poster="'.esc_url($v_image).'" controls="controls" preload="auto" loop autoplay>';
    
            
            if(!empty($video_webm)) {   $output .= '<source type="video/webm" src="'.esc_attr($video_webm).'">'; }     
            if(!empty($video_mp4))  {   $output .= '<source type="video/mp4" src="'.esc_attr($video_mp4).'">'; }   
                                        $output .='<object width="320" height="240" type="application/x-shockwave-flash" data="'.esc_url(get_template_directory_uri().'/js/flashmediaelement.swf') . '">
                                        <param name="movie" value="'. esc_url(get_template_directory_uri().'/js/flashmediaelement.swf') . '" />
                                        <param name="flashvars" value="controls=true&file='.esc_url($video_mp4).'" />
                                        <img src="'.esc_url($v_image).'" width="1920" height="800" title="No video playback capabilities" alt="Video thumb" />
                </object>
        </video>        
</div>';   
    $output .= '<div class="section-content-container" style="' . esc_attr($rowspace) . '">';
    $output .='<div class="container"><div class="wpb_row vc_row-fluid videoContent vc_videocontent ' . esc_attr($row_class) .'">';

}

     $output .= wpb_js_remove_wpautop($content);

    
if($row_type == 'row') {
    $output .= '</div></div></div></div></div>'.$this->endBlockComment('row');
    
} elseif($row_type == 'parallax'){
    $output .= '</div></div></div></div></div>'.$this->endBlockComment('row');
    
} elseif($row_type == 'interactive_background'){
    $output .= '</div></div></div></div></div>'.$this->endBlockComment('row');
    
} elseif ($row_type == 'video') {
    $output .= '</div></div></div></div></div></div>'.$this->endBlockComment('row');
}
echo $output;