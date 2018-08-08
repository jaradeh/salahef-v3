<?php

get_template_part('post-type');

class epico_Product extends epico_PostType
{
    function __construct()
    {
        parent::__construct('product');
    }

    function epico_EnqueueScripts()
    {

        wp_enqueue_script('jquery-easing');

        wp_enqueue_script('nouislider');
        wp_enqueue_style('nouislider');

        //Include wpcolorpicker + its patch to support alpha chanel
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script('product', EPICO_THEME_LIB_URI . '/post-types/js/product.js', array('jquery'), THEME_VERSION);
    }

    protected function epico_GetOptions()
    {

        $fields = array(
            'video_type' => array(
                'type' => 'select',
                'label'=> esc_html__('Video type', 'vitrine'),
                'options' => array('none'=>esc_html__('No video', 'vitrine'),'local_video_popup'=>esc_html__('local video popup', 'vitrine'),'embeded_video_vimeo_popup'=>esc_html__('Vimeo video popup', 'vitrine'), 'embeded_video_youtube_popup'=>esc_html__('Youtube video popup', 'vitrine')),
            ),
            'video_webm' => array(
                'type' => 'text',
                'label'=> esc_html__('.webm extension', 'vitrine'),
                'placeholder' => esc_html__('.webm video', 'vitrine'),
            ),
            'video_mp4' => array(
                'type' => 'text',
                'label'=> esc_html__('.mp4 extension', 'vitrine'),
                'placeholder' => esc_html__('.mp4 video', 'vitrine'),
            ),
            'video_ogv' => array(
                'type' => 'text',
                'label'=> esc_html__('.ogv extension', 'vitrine'),
                'placeholder' => esc_html__('.ogv video', 'vitrine'),
            ),
            'video_vimeo_id' => array(
                'type' => 'text',
                'label'=> esc_html__('Vimeo Video link', 'vitrine'),
            ),
            'video_youtube_id' => array(
                'type' => 'text',
                'label'=> esc_html__('Youtube Video link', 'vitrine'),
            ),
            'video_play_button_color' => array(
                'type'   => 'select',
                'label' => esc_html__('Select play button style.', 'vitrine'),
                'options'=> array(
                    'light' => esc_html__('Light', 'vitrine'),
                    'dark' =>  esc_html__('Dark', 'vitrine'),
                )
            ),
            'video_button_label' => array(
                'type' => 'text',
                'label'=> esc_html__('Play button label', 'vitrine'),
                'placeholder' => esc_html__('Whatch Video', 'vitrine'),
            ),
            'shop_enable_zoom' => array(
                'type'   => 'switch',
                'state0' => esc_html__('Disabled', 'vitrine'),
                'state1' => esc_html__('Enabled', 'vitrine'),
                'value'  => 1,
                'default' => 1,
            ),
            'product_detail_style_inherit' => array(
                'type'   => 'switch',
                'state0' => esc_html__('Inherit from theme setting', 'vitrine'),
                'state1' => esc_html__('Custom', 'vitrine'),
                'default' => 0,
                'value'  => 0
            ),
            'product_detail_style' => array(
                'type' => 'visual-select',
                'options' => array('pd_classic'=>'pd_classic','pd_ep_classic'=>'pd_ep_classic','pd_background'=>'pd_background', 'pd_top'=>'pd_top', 'pd_classic_sidebar'=>'pd_classic_sidebar'),
                'class' => 'product-detail',
                'value' => 'pd_classic',
            ),
            'product-detail-sidebar-position' => array(
                'type' => 'visual-select',
                'options' => array('left'=>'left','right'=>'right'),
                'class' => 'product-detail-sidebar',
                'value' => 'right',
            ),
            'product_detail_bg' => array(
                'type'   => 'color',
                'label'  => esc_html__('Background Color', 'vitrine'),
                'value'  => '#fff'
            ),
            'extra_class' => array(
				'type' => 'text',
				'label'=> esc_html__('Extra Class Name', 'vitrine'),
				'placeholder' => esc_html__('class name ex: class1 class2', 'vitrine'),
			),// Extra class name 
            'social_share_inherit' => array(
                'type'   => 'switch',
                'state0' => esc_html__('Inherit from theme setting', 'vitrine'),
                'state1' => esc_html__('Custom', 'vitrine'),
                'default' => 0,
                'value'  => 0
            ),
			'product-social-share' => array(
                'type'   => 'switch',
                'state0' => esc_html__('Disable', 'vitrine'),
                'state1' => esc_html__('Enable', 'vitrine'),
                'default'   => 1
            ),
			 'product_hashtag' => array(
            'label'    => esc_html__( 'Instagram product hashtag', 'vitrine' ), 
            'id'      => 'product_hashtag',
            'type'    => 'text',
			'placeholder' => esc_html__('For example: #nike_rush_run', 'vitrine'),

        ) 
        );

        //Option sections
        $options = array(
            'product_detail_style' => array(
                'title'   => esc_html__('Product detail style', 'vitrine'),
                'tooltip' => esc_html__('Choose product detail style for this product', 'vitrine'),
                'fields'  => array(
                    'product_detail_style_inherit' => $fields['product_detail_style_inherit'],
                    'product_detail_style' => $fields['product_detail_style'],
                    'product_detail_bg' => $fields['product_detail_bg'],
                )
            ),
            'product_detail_sidebar_position' => array(
                'title'   => esc_html__('Product detail sidebar position', 'vitrine'),
                'tooltip' => esc_html__('Choose product detail sidebar position', 'vitrine'),
                'fields'  => array(
                    'product-detail-sidebar-position' => $fields['product-detail-sidebar-position'],
                )
            ),//Product detail bg color
            'shop_enable_zoom' => array(
                'title'   => esc_html__('Zooming of Products Images', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable zooming of products images', 'vitrine'),
                'fields'  => array(
                    'shop_enable_zoom' => $fields['shop_enable_zoom'],
                )
            ),
            'extra_class' => array(
				'title'   => esc_html__('Extra Class name', 'vitrine'),
				'tooltip' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS. use space between diffrent class name', 'vitrine'),
				'fields'  => array(
				    'extra_class' => $fields['extra_class'],
			    )
			),
			 'product_hashtag' => array(
				'title'   => esc_html__('product hashtag', 'vitrine'),
				'tooltip'    => esc_html__( 'Insert tag that will be used to display images from instagram from your customers.', 'vitrine' ),
				'fields'  => array(
				    'product_hashtag' => $fields['product_hashtag'],
			    )
			), //Extra Class name
             'product-social-share' => array(
                'title'   => esc_html__('Social Share display', 'vitrine'),
                'tooltip' => esc_html__('Disable/enable social share', 'vitrine'),
                'fields'  => array(
                    'social_share_inherit' => $fields['social_share_inherit'],
                    'product-social-share' => $fields['product-social-share'],
                )
            ),// Product detail social share button 
            'video_type' => array(
                'title'   => esc_html__('Video type', 'vitrine'),
                'tooltip' => esc_html__('Select video display type.', 'vitrine'),
                'fields'  => array(
                    'video_type' => $fields['video_type'],
                )
            ),//Video Type
            'video_extensions' => array(
                'title'   => esc_html__('Video extension', 'vitrine'),
                'tooltip' => esc_html__('Enter self Hosted Videos', 'vitrine'),
                'fields'  => array(
                    'video_webm'    => $fields['video_webm'],
                    'video_mp4'   => $fields['video_mp4'],
                    'video_ogv'   => $fields['video_ogv'],
               )
            ),
            'video_vimeo_id' => array(
                'title'   => esc_html__('Vimeo Video Link', 'vitrine'),
                'tooltip' => esc_html__('Enter a Video link', 'vitrine'),
                'fields'  => array(
                    'video_vimeo_id' => $fields['video_vimeo_id'],
                )
            ),
            'video_youtube_id' => array(
                'title'   => esc_html__('Youtube Video link', 'vitrine'),
                'tooltip' => esc_html__('Enter a Video link', 'vitrine'),
                'fields'  => array(
                    'video_youtube_id' => $fields['video_youtube_id'],
                )
            ),// Youtube ID
            'video_play_button_color' => array(
                'title'   => esc_html__('Play button style', 'vitrine'),
                'tooltip' => esc_html__('Select play button style', 'vitrine'),
                'fields'  => array(
                    'video_play_button_color' => $fields['video_play_button_color'],
                     'video_button_label' => $fields['video_button_label'],
                )
            ),// Video Button Color 
        );
        return array(
            array(
                'id' => 'product_meta_box',
                'title' => esc_html__('Settings', 'vitrine'),
                'context' => 'normal',
                'priority' => 'default',
                'options' => $options,
            ),//Meta box 1
        );
    }
}

new epico_Product();
