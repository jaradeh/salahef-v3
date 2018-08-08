<?php

get_template_part('post-type');

class epico_Blog extends epico_PostType
{
    function __construct()
    {
        parent::__construct('post');
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

        wp_enqueue_script('blog', EPICO_THEME_LIB_URI . '/post-types/js/blog.js', array('jquery'), THEME_VERSION);
        
    }

    function epico_OnProcessFieldForStore($post_id, $key, $settings)
    {
        //Process media field
        if($key != 'media')
            return false;

        $selectedOpt = $_POST[$key];
		
        switch($selectedOpt)
        {
            case "image":
            {
                //delete video meta
                delete_post_meta($post_id, "video-type");
                delete_post_meta($post_id, "video-id");

                $images = $_POST["gallery"];

                update_post_meta( $post_id, "gallery", $images );

                break;
            }
            case "video":
            {
                //Delete images
                delete_post_meta($post_id, "image");

                $videoType = $_POST["video-type"];
                $videoId   = $_POST["video-id"];

                update_post_meta( $post_id, "video-type", $videoType );
                update_post_meta( $post_id, "video-id", $videoId );

                break;
            }
            default:
            {
                //Delete all
                delete_post_meta($post_id, "video-type");
                delete_post_meta($post_id, "video-id");
                delete_post_meta($post_id, "image");

                break;
            }
        }

        return false;
    }

    protected function epico_GetOptions()
    {
        $fields = array(
            'social_share_inherit' => array(
                'type'   => 'switch',
                'state0' => esc_html__('Inherit from theme setting', 'vitrine'),
                'state1' => esc_html__('Custom', 'vitrine'),
                'default' => 0,
                'value'  => 0
            ),
			'post-social-share' => array(
                'type'   => 'switch',
                'state0' => esc_html__('Disable', 'vitrine'),
                'state1' => esc_html__('Enable', 'vitrine'),
                'default'   => 0
            ),
            'media' => array(
                'type' => 'visual-select',
                'title' => 'Specify kind of media',
                'options' => array(
                    'none'  => 'none',
                    'quote'  => 'quote',
                    'gallery' => 'gallery',
                    'video' => 'video',
                    'video_gallery' => 'video_gallery',
                    'audio' => 'audio',
                    'audio_gallery' =>'audio_gallery'
                ),
                'class' => 'post_type'
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
            'gallery' => array(
                'type'  => 'upload',
                'title' => esc_html__('Gallery Image', 'vitrine'),
                'referer' => 'ep-post-gallery-image',
                'meta'  => array('array'=>true),
            ),//gallery image
            'quote_content' => array(
				'type' => 'text',
				'label'=> esc_html__('Quote', 'vitrine'),
				'placeholder' => esc_html__('Quote', 'vitrine'),
			),//Quote content
            'quote_author' => array(
				'type' => 'text',
				'label'=> esc_html__('Quote author', 'vitrine'),
				'placeholder' => esc_html__('Author', 'vitrine'),
			),//Quote author
            'extra_class' => array(
				'type' => 'text',
				'label'=> esc_html__('Extra Class Name', 'vitrine'),
				'placeholder' => esc_html__('class name ex: class1 class2', 'vitrine'),
			),// Extra class name 
        );

        //Option sections
        $options = array(
           'post-social-share' => array(
                'title'   => esc_html__('Display social share icons', 'vitrine'),
                'tooltip' => esc_html__('Choose to Show or hide social share icons in blog detail', 'vitrine'),
                'fields'  => array(
                    'social_share_inherit' => $fields['social_share_inherit'],
                    'post-social-share' => $fields['post-social-share'],
                )
            ),//Enable And Disable social share icon in blog detail
            'media' => array(
                'title'   => esc_html__('Display Media Type', 'vitrine'),
                'tooltip' => esc_html__('Specify what kind of media (Image(s), Video , Audio , Video and Image(s) or Audio and Image(s)) you would like to be displayed in  blog detail page. You can always use shortcodes to add other media types in content', 'vitrine'),
                'fields'  => array(
                    'media' => $fields['media']
                )
            ),//media sec
             'video' => array(
                'title'   => esc_html__('Post Video', 'vitrine'),
                'tooltip' => esc_html__('Copy and paste your browser URL in this section to automatically load a video into your portfolio. Additional information can be uploaded in the content area.', 'vitrine'),
                'fields'  => array(
                    'video-type' => $fields['video-type'],
                    'video-id' => $fields['video-id'],
                )
            ),//Video sec
            'audio' => array(
                'title'   => esc_html__('Post Audio', 'vitrine'),
                'tooltip' => esc_html__('Copy the URL of an audio that is uploaded on the SoundCloud.', 'vitrine'),
                'fields'  => array(
                    'audio-url' => $fields['audio-url'],
                )
            ),//Audio sec
            'gallery' => array(
                'title'   => esc_html__('Post Gallery', 'vitrine'),
                'tooltip' => esc_html__('Upload images to be shown in blog detail page slider', 'vitrine'),
                'fields'  => array(
                    'gallery' => $fields['gallery'],
                )
            ),//Gallery sec
            'quote' => array(
				'title'   => esc_html__('Quote', 'vitrine'),
				'tooltip' => esc_html__('Type down quote information', 'vitrine'),
				'fields'  => array(
                    'quote_content' => $fields['quote_content'],
				    'quote_author' => $fields['quote_author'],
				)
			),//Quote sec
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
                'id' => 'blog_meta_box',
                'title' => esc_html__('Settings', 'vitrine'),
                'context' => 'normal',
                'priority' => 'default',
                'options' => $options,
            )//Meta box
        );
    }
}

new epico_Blog();
