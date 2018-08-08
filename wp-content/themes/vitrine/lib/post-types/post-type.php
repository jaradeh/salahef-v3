<?php

require_once(EPICO_THEME_LIB . '/forms/fieldtemplate.php');
require_once(EPICO_THEME_LIB . '/forms/post-options-provider.php');

abstract class epico_PostType
{
    protected $postType;

    function __construct($postType)
    {

        $this->postType = $postType;

        add_action( 'after_setup_theme', array(&$this, 'epico_CreatePostType'),0);

        add_action('add_meta_boxes', array(&$this, 'epico_AddMetaBoxes'));
        add_action('admin_print_scripts-post-new.php', array( &$this, 'epico_InitScripts' ));
        add_action('admin_print_scripts-post.php', array( &$this, 'epico_InitScripts' ));

        /* Save post meta on the 'save_post' hook. */
        add_action( 'save_post', array( &$this, 'epico_SaveData' ), 10, 2 );

    }

    function epico_SaveData($post_id = false, $post = false)
    {


        /* Verify the nonce before proceeding. */
        $nonce = THEME_NAME_SEO . '_post_nonce';

        if ( !isset( $_POST[$nonce] ) || !wp_verify_nonce( $_POST[$nonce], 'theme-post-meta-form' ) )
            return $post_id;

        // check autosave
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
            return $post_id;


        if( $post->post_type != $this->postType || !current_user_can('edit_post', $post_id))
            return $post_id;


        //CRUD Operation
        foreach( $this->epico_GetOptionsForStore() as $key => $settings )
        {
            //Let the derived class intercept the process
            if($this->epico_OnProcessFieldForStore($post_id, $key, $settings))
                continue;

            $postedVal = isset( $_POST[$key] ) ? $_POST[$key] : '';
            $val       = get_post_meta( $post_id, $key, false );

            if(is_array($postedVal))
            {
                //Insert
                if ( !empty($postedVal) && empty($val) )
                {
                    add_post_meta( $post_id, $key, $postedVal );

                }
                //Delete
                elseif ( !empty($val) && empty($postedVal) )
                {
                    delete_post_meta( $post_id, $key );

                    //Delete the attachment as well
                    if($settings['type'] == 'upload')
                    {
                        epico_delete_attachment($val);
                    }
                }
                //Update
                elseif ( !empty($val) && !empty($postedVal) && $postedVal != $val )
                {
                    update_post_meta( $post_id, $key, $postedVal );
                }

            }
            else
            {
                //Insert
                if ( $postedVal != '' && empty($val) )
                {
                    add_post_meta( $post_id, $key, $postedVal );

                }
                //Delete
                elseif ( !empty($val) && $postedVal == '' )
                {
                    delete_post_meta( $post_id, $key );

                    //Delete the attachment as well
                    if($settings['type'] == 'upload')
                    {
                        epico_delete_attachment($val);
                    }
                }
                //Update
                elseif ( $postedVal != '' && !empty($val) &&  $postedVal != $val )
                {
                    update_post_meta( $post_id, $key, $postedVal );
                }


            }
        }

        return $post_id;
    }

    function epico_OnProcessFieldForStore($post_id, $key, $settings)
    {
        return false;
    }

    function epico_CreatePostType()
    {

    }

    protected function epico_GetOptionsForStore()
    {
        $options = $this->epico_GetOptions();
        $values  = array();

        foreach($options as $box)
        {
            foreach($box['options'] as $section)
            {
                foreach($section['fields'] as $key => $field)
                {
                    $ignore = epico_array_value('dontsave', epico_array_value('meta', $field, array()), false);

                    if($ignore)
                        continue;

                    $values[$key] = $field;
                }
            }
        }

        return $values;
    }

    protected function epico_GetOptions()
    {
        return array();
    }

    function epico_AddMetaBoxes()
    {
        $options = $this->epico_GetOptions();

        foreach($options as $box)
        {

            add_meta_box(
                $box['id'], // $id
                $box['title'], // $title
                array(&$this, 'epico_ShowMetaBox'), // $callback
                $this->postType, // $page
                $box['context'], // $context
                $box['priority'],// $priority
                $box['options']
            );

        }

    }

    function epico_ShowMetaBox($post, $metabox)
    {
        $args = $metabox['args'];

        $form = new epico_FieldTemplate(new epico_PostOptionsProvider(), dirname(__FILE__));

        echo $form-> epico_GetTemplate('meta-form', $args);
    }


    function epico_InitScripts()
    {
        global $post_type;

        if( $post_type != $this->postType )
            return;

        $this->epico_RegisterScripts();
        $this->epico_EnqueueScripts();
    }

    protected function epico_RegisterScripts()
    {
        wp_register_script('jquery-easing', EPICO_THEME_LIB_URI  .'/admin/scripts/jquery.easing.1.3.js', array('jquery'), '1.3.0');

        wp_register_style( 'nouislider', EPICO_THEME_LIB_URI . '/admin/css/jquery.nouislider.min.css', false, '7.0.10', 'screen' );
        wp_register_script('nouislider', EPICO_THEME_LIB_URI  .'/admin/scripts/jquery.nouislider.min.js', array('jquery'), '7.0.10');

        wp_register_script('colorpickerAlpha', EPICO_THEME_LIB_URI  .'/admin/scripts/wp-color-picker-alpha.js',array( 'wp-color-picker' ), '1.2.2');

        wp_register_style( 'chosen', EPICO_THEME_LIB_URI . '/admin/css/chosen.css', false, '1.0.0', 'screen' );
        wp_register_script('chosen', EPICO_THEME_LIB_URI  .'/admin/scripts/chosen.jquery.min.js', array('jquery'), '1.0.0');

        wp_register_style( 'theme-admin-css', EPICO_THEME_LIB_URI . '/admin/css/style.css', false, '1.0.0', 'screen' );
        wp_register_script('theme-admin-script', EPICO_THEME_LIB_URI  .'/admin/scripts/admin.js', array('jquery'), '1.0.0');
    }

    protected function epico_EnqueueScripts()
    {
        wp_enqueue_script('hoverIntent');
        wp_enqueue_script('jquery-easing');

        wp_enqueue_style('nouislider');
        wp_enqueue_script('nouislider');

        //Include wpcolorpicker + its patch to support alpha chanel
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'colorpickerAlpha' );

        wp_enqueue_style('chosen');
        wp_enqueue_script('chosen');

        wp_enqueue_style('theme-admin');
        wp_enqueue_script('theme-admin');
    }
}