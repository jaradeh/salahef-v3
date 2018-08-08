<?php

//require_once('post-type.php');
get_template_part( 'lib/post-types/post-type' );

class epico_Portfolio extends epico_PostType
{

    function __construct()
    {
        parent::__construct('portfolio');
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
        wp_enqueue_script('colorpickerAlpha');

        wp_enqueue_style('theme-admin');
        wp_enqueue_script('theme-admin');

        wp_enqueue_script('portfolio', EPICO_THEME_LIB_URI . '/post-types/js/portfolio.js', array('jquery'), THEME_VERSION);
    }

    function epico_OnProcessFieldForStore($post_id, $key, $settings)
    {

        $selectedOpt = $_POST[$key];
        
        //Save Portfolio Attributes Titles
        $attributesTitles = $_POST["attribute-title"];
        $attributes = array_filter( array_map( 'trim', $attributesTitles ), 'strlen' );
        $attributes = array_values($attributesTitles);
        update_post_meta( $post_id, "attribute-title", $attributes );
        //Save Portfolio Attributes Values
        $attributesValue = $_POST["attribute-value"];
        $attributes = array_filter( array_map( 'trim', $attributesValue ), 'strlen' );
        $attributes = array_values($attributesValue);
        update_post_meta( $post_id, "attribute-value", $attributes );


        return false;
    }

    protected function epico_GetOptions()
    {
        $fields = array(
            'title-bar-switch' => array(
                'type' => 'select',
                'label'=> esc_html__('Section Title', 'vitrine'),
                'options' => array('2'=>esc_html__('Show post title', 'vitrine'),'1'=>esc_html__('Show custom title', 'vitrine'),'0'=>esc_html__('No title', 'vitrine')),
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
                'title' => esc_html__('Portfolio Image', 'vitrine'),
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
            ),//Audio url
            'link-url' => array(
                'type' => 'text',
                'placeholder' => esc_html__('URL', 'vitrine'),
            ),//link
            'portfolio-featured-size' => array(
                'type' => 'visual-select',
                'title' => 'choose your portfolio post size',
                'options' => array('square' =>'square', 'big'=> 'big', 'slim'=>'slim','hslim'=>'hslim','wide' =>'wide'),
            ),
            'portfolio-detail-style' => array(
                'type' => 'visual-select',
                'title' => 'choose portfolio detail style',
                'options' => array('portfolio_detail_full_width' =>'portfolio_detail_full_width', 'portfolio_detail_boxed'=> 'portfolio_detail_boxed', 'portfolio_detail_creative'=>'portfolio_detail_creative'),
            ),
            'social_share_inherit' => array(
                'type'   => 'switch',
                'state0' => esc_html__('Inherit from theme setting', 'vitrine'),
                'state1' => esc_html__('Custom', 'vitrine'),
                'default' => 0,
                'value'  => 0
            ),
            'portfolio-social-share' => array(
                'type'   => 'switch',
                'state0' => esc_html__('Disable', 'vitrine'),
                'state1' => esc_html__('Enable', 'vitrine'),
                'default'   => 0,
            ),
            'attribute-title' => array(
                'type'  => 'text',
                'class' => 'attribute-title',
                'placeholder' => esc_html__('Attribute Title', 'vitrine'),
                'meta'  => array('array'=>true, 'dontsave'=>true),//This will indirectly get saved
            ),//Attribute Title
            'attribute-value' => array(
                'type'  => 'text',
                'class' => 'attribute-value',
                'placeholder' => esc_html__('Attribute Value', 'vitrine'),
                'meta'  => array('array'=>true, 'dontsave'=>true),//This will indirectly get saved
            ),//Attribute Value 
            'extra_class' => array(
				'type' => 'text',
				'label'=> esc_html__('Extra Class Name', 'vitrine'),
				'placeholder' => esc_html__('class name ex: class1 class2', 'vitrine'),
			),// Extra class name 
        );
        //Option sections
        $options = array(
            'title-bar' => array(
                'title'   => esc_html__('Portfolio Detail Title', 'vitrine'),
                'tooltip' => esc_html__('Choose a portfolio detail title and a subtitle.', 'vitrine'),
                'fields'  => array(
                    'title-bar'    => $fields['title-bar-switch'],
                    'title-text'   => $fields['title-text'],
                    'subtitle-text'   => $fields['subtitle-text'],
               )
            ),//Title bar sec
            'featured-size' => array(
                'title'   => esc_html__('Portfolio Thumbnail Size', 'vitrine'),
                'tooltip' => esc_html__('Choose a size for the post in the grid.', 'vitrine'),
                'fields'  => array(
                    'portfolio-featured-size' => $fields['portfolio-featured-size'],
                )
            ),//Portfolio Thumbnail Size
            'portfolio-detail-style' => array(
                'title'   => esc_html__('Portfolio Detail Style', 'vitrine'),
                'tooltip' => esc_html__('Choose a Style for portfolio detail.', 'vitrine'),
                'fields'  => array(
                    'portfolio-detail-style' => $fields['portfolio-detail-style'],
                )
            ),//Portfolio Thumbnail Size
            'gallery' => array(
                'title'   => esc_html__('Portfolio Detail Header Images', 'vitrine'),
                'tooltip' => esc_html__('Choose one image to be shown at portfolio detail page. If you choose more than one image, it will be shown as a slider.', 'vitrine'),
                'fields'  => array(
                    'image' => $fields['image']
                )
            ),//images sec
            'video' => array(
                'title'   => esc_html__('Portfolio Video', 'vitrine'),
                'tooltip' => esc_html__('Copy and paste your browser URL in this section to automatically load a video into your portfolio. Additional information can be uploaded in the content area.', 'vitrine'),
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
            'portfolio-social-share' => array(
                'title'   => esc_html__('Portfolio Social Share', 'vitrine'),
                'tooltip' => esc_html__('Enable or Disable Social sharing for portfolio items.', 'vitrine'),
                'fields'  => array(
                    'social_share_inherit' => $fields['social_share_inherit'],
                    'portfolio-social-share' => $fields['portfolio-social-share'],
                )
            ),//portfolio social share icon sec
            'attribute' => array(
                'title'   => esc_html__('Portfolio Detail Attributes', 'vitrine'),
                'tooltip' => esc_html__('You can add many attributes to your portfolio item here, for example you can add the project client, date of that project and etc.', 'vitrine'),
                'fields'  => array(
                    'attribute-title' => $fields['attribute-title'],
                    'attribute-value' => $fields['attribute-value']
                )
            ),//attribute sec 
            'extra_class' => array(
				'title'   => esc_html__('Extra Class name', 'vitrine'),
				'tooltip' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS. use space between diffrent class name', 'vitrine'),
				'fields'  => array(
				    'extra_class' => $fields['extra_class'],
			    )
			),//Extra Class name
        );

        return array(
            array(
                'id' => 'portfolio_meta_box',
                'title' => esc_html__('Portfolio Options', 'vitrine'),
                'context' => 'normal',
                'priority' => 'default',
                'options' => $options,
            )//Meta box
        );
    }
}

function epicomedia_portfolio(){
    new epico_Portfolio();
}
add_action('after_setup_theme', 'epicomedia_portfolio');
