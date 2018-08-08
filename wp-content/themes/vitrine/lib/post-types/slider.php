<?php

get_template_part('post-type');
include_once EPICO_THEME_LIB . '/google-fonts.php';

class epico_Slider extends epico_PostType
{

    function __construct()
    {
        parent::__construct('slider');
    }

    function epico_EnqueueScripts()
    {
        wp_enqueue_script('hoverIntent');
        wp_enqueue_script('jquery-easing');
        
        wp_enqueue_script('nouislider');
        wp_enqueue_style('nouislider');

        wp_enqueue_style('theme-admin');
        wp_enqueue_script('theme-admin');

        //Include wpcolorpicker + its patch to support alpha chanel
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script('colorpickerAlpha');

        wp_enqueue_script('ep_slider', EPICO_THEME_LIB_URI . '/post-types/js/slider.js', array('jquery'), THEME_VERSION);

    }

    function epico_OnProcessFieldForStore($post_id, $key, $settings)
    {
        return false;
    }


    protected function epico_GetOptions()
    {

        global $post;
        $id = $post->ID;

        $gf = new epico_GoogleFonts(epico_path_combine(EPICO_THEME_LIB, 'googlefonts.php'));
        $fontNames = $gf->GetFontNames();

        $titleFontType = get_post_meta($id, 'title-font-type' ,true );
        $titleFontVariantValue = get_post_meta($id, 'title-font-weight' ,true );
        $titleFontVariantOptions = array();
        if($titleFontType == 'google')
        {
            $titleFont = current((Array)json_decode(get_post_meta($id, 'title-font' ,true )));
            foreach ($titleFont as $key => $value) {
                $titleFontVariantOptions[$value] = $value;
            }
        }

        $subtitleFontType = get_post_meta($id, 'subtitle-font-type' ,true );
        $subtitleFontVariantValue = get_post_meta($id, 'subtitle-font-weight' ,true );
        $subtitleFontVariantOptions = array();
        if($subtitleFontType == 'google')
        {
            $subtitleFont = current((Array)json_decode(get_post_meta($id, 'subtitle-font' ,true )));
            foreach ($subtitleFont as $key => $value) {
                $subtitleFontVariantOptions[$value] = $value;
            }
        }


        $subtitle2FontType = get_post_meta($id, 'subtitle2-font-type' ,true );
        $subtitle2FontVariantValue = get_post_meta($id, 'subtitle2-font-weight' ,true );
        $subtitle2FontVariantOptions = array();

        if($subtitle2FontType == 'google')
        {
            $subtitle2Font = current((Array)json_decode(get_post_meta($id, 'subtitle2-font' ,true )));

            foreach ($subtitle2Font as $key => $value) {
                $subtitle2FontVariantOptions[$value] = $value;
            }
        }

        /*$titleFontVariants = (Array)json_decode($titleFont);
        $subtitleFontVariants = (Array)json_decode(array_keys($fonts[]));
        $subtitle2FontVariants = (Array)json_decode(array_keys($fonts[]));*/

        $fields = array(
            'title-text' => array(
                'type' => 'text',
                'label'=> esc_html__('Title', 'vitrine'),
                'placeholder' => esc_html__('Override title text', 'vitrine'),

            ),
            'subtitle-text' => array(
                'type' => 'text',
                'label'=> esc_html__('Subtitle', 'vitrine'),
                'placeholder' => esc_html__('Subtitle text ', 'vitrine'),
            ),
            'subtitle2-text' => array(
                'type' => 'text',
                'label'=> esc_html__('Subtitle 2', 'vitrine'),
                'placeholder' => esc_html__('Subtitle 2 text', 'vitrine'),
            ),
            'caption-icon-image' => array(
                'type' => 'visual-select',
                'title' => 'choose caption image/icon',
                'label'=> esc_html__('Caption image/icon', 'vitrine'),
                'options' => array('image' => 'image', 'icon'=> 'icon'),
            ),
            'caption-icon-image-animation' => array(
                'type'   => 'switch',
                'label'  => esc_html__('Icon/image animation', 'vitrine'),
                'state0' => esc_html__('Off', 'vitrine'),
                'state1' => esc_html__('On', 'vitrine'),
                'default'   => 1
            ),
            'caption-image' => array(
                'type'  => 'upload',
                'class' => 'caption-image-field',
                'title' => 'Upload caption image',
                'referer' => 'ep-caption-image',
            ),
            'caption-icon' => array(
                'type'  => 'icon',
                'title' => esc_html__('Caption Icon', 'vitrine'),
                'class' => 'caption-icon-field',
                'desc'  => esc_html__('Select an icon for top of caption', 'vitrine'),
                'flags' => 'attribute',//CSV
            ),
           'caption-icon-color' => array(
                'type'   => 'color',
                'label'  => esc_html__('Icon color', 'vitrine'),
                'value'  => '#fff'
            ),
            'background-type' => array(
                'type' => 'visual-select',
                'title' => 'choose Background type',
                'label'=> esc_html__('Background Type', 'vitrine'),
                'options' => array('image' =>'image', 'video'=> 'video'),
            ),
            'background-image' => array(
                'type'  => 'upload',
                'title' => 'Upload Slider image',
                'class' => 'slider-image-upload',
                'referer' => 'ep-slide-image',
            ),
            'video-url-webm' => array(
                'type' => 'text',
                'class' => 'slider-video-url',
                'placeholder' => esc_html__('Video URL ( .webm format)', 'vitrine'),
            ),//video id
            'video-url-mp4' => array(
                'type' => 'text',
                'class' => 'slider-video-url',
                'placeholder' => esc_html__('Video URL ( .mp4 format)', 'vitrine'),
            ),//video id
            'video-prev-image' => array(
                'type'  => 'upload',
                'title' => 'Upload Video preview image',
                'class' => 'slider-video-prev',
                'referer' => 'ep-video-prev-image',
            ),
            'button-url' => array(
                'type' => 'text',
                'label' => esc_html__('URL', 'vitrine'),
                'placeholder' => esc_html__('URL', 'vitrine'),
            ),//Button
            'button-text' => array(
                'type' => 'text',
                'label' => esc_html__('Text', 'vitrine'),
                'placeholder' => esc_html__('Text', 'vitrine'),
            ),//Button text
           'button-text-color' => array(
                'type'   => 'color',
                'label'  => esc_html__('Text color', 'vitrine'),
                'value'  => '#ffffff'
            ),
           'button-text-hover-color' => array(
                'type'   => 'color',
                'label'  => esc_html__('Text hover-color', 'vitrine'),
                'value'  => '#222222'
            ),
            'button-background-style' => array(
                'label' => esc_html__('Style', 'vitrine'),
                'type' => 'select',
                'options' =>array("transparent" => "Transparent", "fill" => "Fill"),
                'value'=> "transparent",
            ),
           'button-background-color' => array(
                'type'   => 'color',
                'label'  => esc_html__('Background Color', 'vitrine'),
                'value'  => '#ffffff'
            ),
            'caption-style' => array(
                'type' => 'visual-select',
                'title' => 'choose your caption style',
                'class' => 'caption-style-field',
                'label'=> esc_html__('Caption Style', 'vitrine'),
                'options' => array('style1' =>'style1', 'style2'=> 'style2', 'style3'=>'style3','style4'=>'style4','style5' =>'style5'),
                'default' => 'style1'
            ),
            'caption-align' => array(
                'type' => 'visual-select',
                'title' => 'choose caption position',
                'class' => 'caption-align-field',
                'label'=> esc_html__('Caption Position', 'vitrine'),
                'options' => array('left' =>'left', 'center'=> 'center', 'right'=>'right', 'left-bottom' =>'left bottom', 'center-bottom'=> 'center bottom', 'right-bottom'=>'right bottom'),
                'default' => 'center'
            ),
            'caption-container' => array(
                'type'   => 'switch',
                'label'  => esc_html__('Container/fullwidth captions', 'vitrine'),
                'state0' => esc_html__('Container', 'vitrine'),
                'state1' => esc_html__('Fullwidth', 'vitrine'),
                'default'   => 1
            ),
            'caption-dark-light' => array(
                'type'   => 'switch',
                'class' => 'caption-dark-light-field',
                'label'  => esc_html__('Captions background-color style', 'vitrine'),
                'state0' => esc_html__('Dark', 'vitrine'),
                'state1' => esc_html__('Light', 'vitrine'),
                'default'   => 1
            ),
            'title-font-type' => array(
                'type'   => 'select',
                'label'=> esc_html__('Font type', 'vitrine'),
                'options'=> array('default' => 'Theme default font', 'google' => 'Google fonts','custom' => 'Custom font'),
                'value'  => 'default'
            ),
            'title-font' => array(
                'type'   => 'select',
                'label'=> esc_html__('Font', 'vitrine'),
                'options'=> $fontNames,
                'value'  => 'Poppins'
            ),
            'title-custom-font-url' => array(
                'type' => 'text',
                'label' => esc_html__('Font URL', 'vitrine'),
                'placeholder' => 'i.e. http://fonts.googleapis.com/css?family=Dosis'
            ),
            'title-custom-font-name' => array(
                'type' => 'text',
                'label' => esc_html__('Font Name', 'vitrine'),
                'placeholder' => "'Dosis', sans-serif"
            ),
           'title-color' => array(
                'type'   => 'color',
                'label'  => esc_html__('Color', 'vitrine'),
                'value'  => ''
            ),
            'title-font-weight' => array(
                'label' => esc_html__('Style( Font-weight )', 'vitrine'),
                'type' => 'select',
                'options' =>$titleFontVariantOptions,
                'value'=> $titleFontVariantValue,
            ),
            'title-custom-font-weight' => array(
                'label' => esc_html__('Style( Font-weight )', 'vitrine'),
                'type' => 'text',
                'placeholder' => "font weight"
            ),
            'title-size' => array(
                'type' => 'text',
                'label'=> esc_html__('Font size in px', 'vitrine'),
                'placeholder' => esc_html__('eg 60px', 'vitrine'),
            ),
            'subtitle-font-type' => array(
                'type'   => 'select',
                'label'=> esc_html__('Font type', 'vitrine'),
                'options'=> array('default' => 'Theme default font', 'google' => 'Google fonts','custom' => 'Custom font'),
                'value'  => 'default'
            ),
            'subtitle-font' => array(
                'type'   => 'select',
                'label'=> esc_html__('Font', 'vitrine'),
                'options'=> $fontNames,
                'value'  => 'Poppins'
            ),
            'subtitle-custom-font-url' => array(
                'type' => 'text',
                'label' => esc_html__('Font URL', 'vitrine'),
                'placeholder' => 'i.e. http://fonts.googleapis.com/css?family=Dosis'
            ),
            'subtitle-custom-font-name' => array(
                'type' => 'text',
                'label' => esc_html__('Font Name', 'vitrine'),
                'placeholder' => "'Dosis', sans-serif"
            ),
           'subtitle-color' => array(
                'type'   => 'color',
                'label'  => esc_html__('Color', 'vitrine'),
                'value'  => ''
            ),
            'subtitle-font-weight' => array(
                'label' => esc_html__('Style( Font-weight )', 'vitrine'),
                'type' => 'select',
                'options' =>$subtitleFontVariantOptions,
                'value'=> $subtitleFontVariantValue,
            ),
            'subtitle-custom-font-weight' => array(
                'label' => esc_html__('Style( Font-weight )', 'vitrine'),
                'type' => 'text',
                'placeholder' => "font weight"
            ),
            'subtitle-size' => array(
                'type' => 'text',
                'label'=> esc_html__('Font size in px', 'vitrine'),
                'placeholder' => esc_html__('eg 60px', 'vitrine'),
            ),
            'subtitle2-font-type' => array(
                'type'   => 'select',
                'label'=> esc_html__('Font type', 'vitrine'),
                'options'=> array('default' => 'Theme default font', 'google' => 'Google fonts','custom' => 'Custom font'),
                'value'  => 'default'
            ),
            'subtitle2-font' => array(
                'type'   => 'select',
                'label'=> esc_html__('Font', 'vitrine'),
                'options'=> $fontNames,
                'value'  => 'Poppins'
            ),
            'subtitle2-custom-font-url' => array(
                'type' => 'text',
                'label' => esc_html__('Font URL', 'vitrine'),
                'placeholder' => 'i.e. http://fonts.googleapis.com/css?family=Dosis'
            ),
            'subtitle2-custom-font-name' => array(
                'type' => 'text',
                'label' => esc_html__('Font Name', 'vitrine'),
                'placeholder' => "'Dosis', sans-serif"
            ),
           'subtitle2-color' => array(
                'type'   => 'color',
                'label'  => esc_html__('Color', 'vitrine'),
                'value'  => ''
            ),
            'subtitle2-font-weight' => array(
                'label' => esc_html__('Style( Font-weight )', 'vitrine'),
                'type' => 'select',
                'options' =>$subtitle2FontVariantOptions,
                'value'=> $subtitle2FontVariantValue,
            ),
            'subtitle2-custom-font-weight' => array(
                'label' => esc_html__('Style( Font-weight )', 'vitrine'),
                'type' => 'text',
                'placeholder' => "font weight"
            ),
            'subtitle2-size' => array(
                'type' => 'text',
                'label'=> esc_html__('Font size in px', 'vitrine'),
                'placeholder' => esc_html__('eg 60px', 'vitrine'),
            ),
        );


        //Option sections
        $options = array(
            'background' => array(
                'title'   => esc_html__('Choose a background format', 'vitrine'),
                'tooltip' => esc_html__('Choose a format for your slide.', 'vitrine'),
                'fields'  => array(
                    'background-type'   => $fields['background-type'],
               )
            ),//Background
            'background-image' => array(
                'title'   => esc_html__('Background Image', 'vitrine'),
                'tooltip' => esc_html__('Choose an image for slide', 'vitrine'),
                'fields'  => array(
                    'background-image'   => $fields['background-image'],
               )
            ),//Background
            'background-video' => array(
                'title'   => esc_html__('Background Video', 'vitrine'),
                'tooltip' => esc_html__('Enter Video URLs', 'vitrine'),
                'fields'  => array(
                    'video-url-webm'   => $fields['video-url-webm'],
                    'video-url-mp4'   => $fields['video-url-mp4'],
                    'video-prev-image'   => $fields['video-prev-image'],
               )
            ),//Background
            'caption' => array(
                'title'   => esc_html__('Caption Style', 'vitrine'),
                'tooltip' => esc_html__('Choose a caption style.', 'vitrine'),
                'fields'  => array(
                    'caption-container'   => $fields['caption-container'],
                    'caption-style' => $fields['caption-style'],
                    'caption-dark-light'   => $fields['caption-dark-light'],
                    'caption-align' => $fields['caption-align'],
                )
            ),//Caption style
            'title-font' => array(
                'title'   => esc_html__('Title', 'vitrine'),
                'tooltip' => esc_html__('Select your favorite font for the title.', 'vitrine'),
                'fields'  => array(
                    'title-text'   => $fields['title-text'],
                    'title-font-type' => $fields['title-font-type'],
                    'title-font' => $fields['title-font'],
                    'title-custom-font-url' => $fields['title-custom-font-url'],
                    'title-custom-font-name' => $fields['title-custom-font-name'],
                    'title-font-weight' => $fields['title-font-weight'],
                    'title-custom-font-weight' => $fields['title-custom-font-weight'],
                    'title-size' => $fields['title-size'],
                    'title-color' => $fields['title-color'],
                )
            ),//Slider title's Font
            'subtitle-font' => array(
                'title'   => esc_html__('Subtitle', 'vitrine'),
                'tooltip' => esc_html__('Select your favorite font for the subtitle.', 'vitrine'),
                'fields'  => array(
                    'subtitle-text'   => $fields['subtitle-text'],
                    'subtitle-font-type' => $fields['subtitle-font-type'],
                    'subtitle-font' => $fields['subtitle-font'],
                    'subtitle-custom-font-url' => $fields['subtitle-custom-font-url'],
                    'subtitle-custom-font-name' => $fields['subtitle-custom-font-name'],
                    'subtitle-font-weight' => $fields['subtitle-font-weight'],
                    'subtitle-custom-font-weight' => $fields['subtitle-custom-font-weight'],
                    'subtitle-size' => $fields['subtitle-size'],
                    'subtitle-color' => $fields['subtitle-color'],
                )
            ),//Slider subtitle's Font
            'subtitle2-font' => array(
                'title'   => esc_html__('Subtitle2', 'vitrine'),
                'tooltip' => esc_html__('Select your favorite font for the subtitle2.', 'vitrine'),
                'fields'  => array(
                    'subtitle2-text'   => $fields['subtitle2-text'],
                    'subtitle2-font-type' => $fields['subtitle2-font-type'],
                    'subtitle2-font' => $fields['subtitle2-font'],
                    'subtitle2-custom-font-url' => $fields['subtitle2-custom-font-url'],
                    'subtitle2-custom-font-name' => $fields['subtitle2-custom-font-name'],
                    'subtitle2-font-weight' => $fields['subtitle2-font-weight'],
                    'subtitle2-custom-font-weight' => $fields['subtitle2-custom-font-weight'],
                    'subtitle2-size' => $fields['subtitle2-size'],
                    'subtitle2-color' => $fields['subtitle2-color'],
                )
            ),//Slider subtitle2's Font
            'caption-image-icon' => array(
                'title'   => esc_html__('Caption Icon/Image', 'vitrine'),
                'tooltip' => esc_html__('Choose an image or an icon for your caption. This Field can be left empty.', 'vitrine'),
                'fields'  => array(
                    'caption-icon-image-animation' => $fields['caption-icon-image-animation'],
                    'caption-icon-image' => $fields['caption-icon-image'],
                )
            ),//Caption image/icon
            'caption-image' => array(
                'title'   => esc_html__('Caption Image', 'vitrine'),
                'tooltip' => esc_html__('Choose an image for caption', 'vitrine'),
                'fields'  => array(
                    'caption-image' => $fields['caption-image'],
                )
            ),//Caption image
            'caption-icon' => array(
                'title'   => esc_html__('Caption Icon', 'vitrine'),
                'tooltip' => esc_html__('Choose an icon for caption', 'vitrine'),
                'fields'  => array(
                    'caption-icon' => $fields['caption-icon'],
                    'caption-icon-color' => $fields['caption-icon-color'],
                )
            ),//Caption image
            'button' => array(
                'title'   => esc_html__('Button', 'vitrine'),
                'tooltip' => esc_html__('By specifying a URL and a label text, you can add a button to your slide, this button redirects users to another web page.', 'vitrine'),
                'fields'  => array(
                    'button-url' => $fields['button-url'],
                    'button-text' => $fields['button-text'],
                    'button-text-color' => $fields['button-text-color'],
                    'button-text-hover-color' => $fields['button-text-hover-color'],
                    'button-background-style' => $fields['button-background-style'],
                    'button-background-color' => $fields['button-background-color'],
                )
            ),//button
        );

        return array(
            array(
                'id' => 'slider_meta_box',
                'title' => esc_html__('Slider Options', 'vitrine'),
                'context' => 'normal',
                'priority' => 'default',
                'options' => $options,
            ),//Meta box
        );
    }
}

function epicomedia_slider(){
    new epico_Slider();
}
add_action('after_setup_theme', 'epicomedia_slider');
