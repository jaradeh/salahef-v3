<?php

get_template_part('post-type');

class epico_Gallery extends epico_PostType
{

    function __construct()
    {
        parent::__construct('gallery');
    }

    function epico_EnqueueScripts()
    {
        wp_enqueue_script('hoverIntent');
        wp_enqueue_script('jquery-easing');
        
        wp_enqueue_script('nouislider');
        wp_enqueue_style('nouislider');

        //Include wpcolorpicker + its patch to support alpha chanel
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'colorpickerAlpha' );

        wp_enqueue_style('theme-admin');
        wp_enqueue_script('theme-admin');

        wp_enqueue_script('portfolio', EPICO_THEME_LIB_URI . '/post-types/js/portfolio.js', array('jquery'), THEME_VERSION);

    }
    
    protected function epico_GetOptions()
    {
        $fields = array(
            'title-bar-switch' => array(
                'type' => 'select',
                'label'=> esc_html__('Section Title', 'vitrine'),
                'options' => array('2'=>esc_html__('Show gallery item title', 'vitrine'),'1'=>esc_html__('Show custom title', 'vitrine')),
            ),
            'title-text' => array(
                'type' => 'text',
                'label'=> esc_html__('Title Text', 'vitrine'),
                'placeholder' => esc_html__('Override title text', 'vitrine'),

            ),
			'subtitle-text' => array(
                'type' => 'text',
                'label'=> esc_html__('Subtitle Text', 'vitrine'),
                'placeholder' => esc_html__('Subtitle text', 'vitrine'),
            ),

            'image' => array(
                'type'  => 'upload',
                'title' => esc_html__('Gallery Image', 'vitrine'),
                'referer' => 'ep-portfolio-image',
                'meta'  => array('array'=>true),
            ),
            'video-type' => array(
                'type' => 'select',
                'options' => array(
                    'vimeo' => esc_html__( "Vimeo",  'vitrine' ),
                    'youtube' => esc_html__( "YouTube",  'vitrine' ),
                ),
            ),
            'video-id' => array(
                'type' => 'text',
                'placeholder' => esc_html__('Video URL', 'vitrine'),
            ),//video id
            'audio-url' => array(
                'type' => 'text',
                'placeholder' => esc_html__('Audio URL', 'vitrine'),
            ),//Audio URL
            'link-url' => array(
                'type' => 'text',
                'placeholder' => esc_html__('URL', 'vitrine'),
            ),//link
            'portfolio-featured-size' => array(
                'type' => 'visual-select',
                'title' => 'Choose your gallery post size',
                'label'=> esc_html__('Gallery Thumbnail Size', 'vitrine'),
                'options' => array('square' =>'square', 'big'=> 'big', 'slim'=>'slim','hslim'=>'hslim','wide' =>'wide'),
            ),
            'gallery-external-link' => array(
                'type' => 'text',
                'label'=> esc_html__('Button URL', 'vitrine'),
                'placeholder' => esc_html__('ADD button URL Here', 'vitrine'),
            ),//gallery external link URL
            'gallery-external-link-text' => array(
                'type' => 'text',
                'label'=> esc_html__('Button Text', 'vitrine'),
                'placeholder' => esc_html__('ADD button text here', 'vitrine'),
            ),//gallery external link text
            'attribute-value' => array(
                'type'  => 'text',
                'class' => 'attribute-value',
                'placeholder' => esc_html__('Attribute Value', 'vitrine'),
                'meta'  => array('array'=>true, 'dontsave'=>true),//This will indirectly get saved
            ),//Attribute Value
        );
		
        //Option sections
        $options = array(
            'title-bar' => array(
                'title'   => esc_html__('Gallery Item Title', 'vitrine'),
                'tooltip' => esc_html__('Enter a gallery item title.', 'vitrine'),
                'fields'  => array(
                    'title-bar'    => $fields['title-bar-switch'],
                    'title-text'   => $fields['title-text'],
					'subtitle-text'   => $fields['subtitle-text'],
               )
            ),//Title bar sec  
            'gallery-external-link' => array(
                'title'   => esc_html__('Add URL and text for The button that appears on the image pop-up', 'vitrine'),
                'tooltip' => esc_html__('Add URL and text for The button that appears on the image pop-up, ', 'vitrine'),
                'fields'  => array(
                    'gallery-external-link'    => $fields['gallery-external-link'],
                    'gallery-external-link-text'   => $fields['gallery-external-link-text'],
               )
            ),//look book Button
            'featured-size' => array(
                'title'   => esc_html__('Gallery Thumbnail Size', 'vitrine'),
                'tooltip' => esc_html__('Choose a size for the contents of gallery.', 'vitrine'),
                'fields'  => array(
                    'portfolio-featured-size' => $fields['portfolio-featured-size'],
                )
            ),//Gallery Thumbnail Size
            'video' => array(
                'title'   => esc_html__('Video', 'vitrine'),
                'tooltip' => esc_html__('Copy and paste your browser URL in this section to automatically load a video into your gallery.', 'vitrine'),
                'fields'  => array(
                    'video-type' => $fields['video-type'],
                    'video-id' => $fields['video-id'],
                )
            ),//Video sec
            'audio' => array(
                'title'   => esc_html__('Post Audio', 'vitrine'),
                'tooltip' => esc_html__('You can enter audio URL hosted in SoundCloud', 'vitrine'),
                'fields'  => array(
                    'audio-url' => $fields['audio-url'],
                )
            ),//Audio sec
            'link' => array(
                'title'   => esc_html__('Post Link', 'vitrine'),
                'tooltip' => esc_html__('You can enter the URL of another website.', 'vitrine'),
                'fields'  => array(
                    'link-url' => $fields['link-url'],
                )
            ),//Audio sec
        );

        return array(
            array(
                'id' => 'portfolio_meta_box',
                'title' => esc_html__('Gallery Options', 'vitrine'),
                'context' => 'normal',
                'priority' => 'default',
                'options' => $options,
            )//Meta box
        );
    }
}

function epicomedia_gallery(){
    new epico_Gallery();
}
add_action('after_setup_theme', 'epicomedia_gallery');
