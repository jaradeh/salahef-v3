<?php

use MetzWeb\Instagram\Instagram;

// Widget class
class epico_Instagram_Widget extends WP_Widget {

    public function __construct() {

        parent::__construct(
            'Epico_Instagram', // Base ID
            'Epico - Instagram', // Name
            array( 'description' => esc_html__( 'A widget that displays Instagram media.', 'vitrine' ) ) // Args
        );

        // This is where we add the style and script
        add_action( 'load-widgets.php', array(&$this, 'epico_Admin_Scripts') );

    }


    public function epico_Admin_Scripts() {

        //Include wpcolorpicker + its patch to support alpha chanel
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script('colorpickerAlpha', EPICO_THEME_LIB_URI  .'/admin/scripts/wp-color-picker-alpha.js',array( 'wp-color-picker' ), '1.2.2');

        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
    }

    function widget( $args, $instance ) {
        extract( $args );

        // Our variables from the widget settings
        $user                = isset( $instance['user'] ) ? esc_attr( $instance['user'] ) : 'croixmurphy';
        $posts_count          = isset( $instance['posts_count'] ) ? esc_attr( $instance['posts_count'] ) : '10';
        $column               = isset( $instance['column'] ) ? esc_attr( $instance['column'] ) : '6';
        $image_resolution     = isset( $instance['image_resolution'] ) ? esc_attr( $instance['image_resolution'] ) : 'thumbnail';
        $gutter               = isset( $instance['gutter'] ) ? esc_attr( $instance['gutter'] ) : '';
        $carousel             = isset( $instance['carousel'] ) ? esc_attr( $instance['carousel'] ) : 'disable';
        $nav_style            = isset( $instance['nav_style'] ) ? esc_attr( $instance['nav_style'] ) : '';
        $hover_color          = isset( $instance['hover_color'] ) ? esc_attr( $instance['hover_color'] ) : '';
        $custom_hover_color   = isset( $instance['custom_hover_color'] ) ? esc_attr( $instance['custom_hover_color'] ) : '';
        $like                 = isset( $instance['like'] ) ? esc_attr( $instance['like'] ) : 'enable';
        $comment              = isset( $instance['comment'] ) ? esc_attr( $instance['comment'] ) : 'enable';


        $attributes = ' user="' . esc_attr($user) . '"';
        $attributes .= ' posts_count="' . esc_attr($posts_count) . '"';
        $attributes .= ' column="' . esc_attr($column) . '"';
        $attributes .= ' image_resolution="' . esc_attr($image_resolution) . '"';
        $attributes .= ' gutter="' . esc_attr($gutter) . '"';
        $attributes .= ' carousel="' . esc_attr($carousel) . '"';
        $attributes .= ' nav_style="' . esc_attr($nav_style) . '"';
        $attributes .= ' hover_color="' . esc_attr($hover_color) . '"';
        $attributes .= ' custom_hover_color="' . esc_attr($custom_hover_color) . '"';
        $attributes .= ' like="' . esc_attr($like) . '"';
        $attributes .= ' comment="' . esc_attr($comment) . '"';
        $attributes .= ' enterance_animation="default"';

        // Our variables from the widget settings
        $title      = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);

        // Before widget (defined by theme functions file)
        echo $before_widget;

        // Display the widget title if one was input
        if ( $title )
            echo $before_title . $title . $after_title;

        echo do_shortcode("[ep_instagram" . $attributes . "]");

        // After widget
        echo $after_widget;
    }


    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        // Strip tags to remove HTML (important for text inputs)
        $instance['title'] = strip_tags( $new_instance['title'] );

        if ( empty( $new_instance['user'] ) ) {
            $new_instance['user'] = 'self';
        }

        if ( empty( $new_instance['custom_hover_color'] ) ) {
            $new_instance['custom_hover_color'] = 'c0392b';
        }

        // Strip tags to remove HTML (important for text inputs)

        $instance['user']               = strip_tags( $new_instance['user'] );
        $instance['posts_count']       = strip_tags( $new_instance['posts_count'] );
        $instance['column']             = strip_tags( $new_instance['column'] );
        $instance['image_resolution']   = strip_tags( $new_instance['image_resolution'] );
        $instance['gutter']             = strip_tags( $new_instance['gutter'] );
        $instance['carousel']           = strip_tags( $new_instance['carousel'] );
        $instance['nav_style']          = strip_tags( $new_instance['nav_style'] );
        $instance['hover_color']        = strip_tags( $new_instance['hover_color'] );
        $instance['custom_hover_color'] = strip_tags( $new_instance['custom_hover_color'] );
        $instance['like']               = strip_tags( $new_instance['like'] );
        $instance['comment']            = strip_tags( $new_instance['comment'] );

        return $instance;
    }

    function form( $instance ) {

        // Set up some default widget settings
        $defaults = array(
            'title' => '',
            'user' => 'self',
            'posts_count' => '10',
            'column' => '6',
            'image_resolution' => 'thumbnail',
            'gutter' => '',
            'carousel' => 'disable',
            'nav_style' => '',
            'hover_color' => '',
            'custom_hover_color' => '',
            'like' => 'enable',
            'comment' => 'enable',
        );

        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'vitrine') ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>"  value=" <?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <!-- Widget Source -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'user' )); ?>"><?php esc_html_e('Display posts from a specific user', 'vitrine') ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'user' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'user' )); ?>" value="<?php echo esc_attr ($instance['user']); ?>" />

        </p>


        <!-- Widget post count -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'posts_count' )); ?>"><?php esc_html_e( 'Post Count', 'vitrine' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_count' ) ); ?>">
                <option value="1" <?php echo selected( "1", $instance['posts_count'], false ); ?>>1</option>;
                <option value="2" <?php echo selected( "2", $instance['posts_count'], false ); ?>>2</option>;
                <option value="3" <?php echo selected( "3", $instance['posts_count'], false ); ?>>3</option>;
                <option value="4" <?php echo selected( "4", $instance['posts_count'], false ); ?>>4</option>;
                <option value="5" <?php echo selected( "5", $instance['posts_count'], false ); ?>>5</option>;
                <option value="6" <?php echo selected( "6", $instance['posts_count'], false ); ?>>6</option>;
                <option value="7" <?php echo selected( "7", $instance['posts_count'], false ); ?>>7</option>;
                <option value="8" <?php echo selected( "8", $instance['posts_count'], false ); ?>>8</option>;
                <option value="9" <?php echo selected( "9", $instance['posts_count'], false ); ?>>9</option>;
                <option value="10" <?php echo selected( "10", $instance['posts_count'], false ); ?>>10</option>;
                <option value="11" <?php echo selected( "11", $instance['posts_count'], false ); ?>>11</option>;
                <option value="12" <?php echo selected( "12", $instance['posts_count'], false ); ?>>12</option>;
                <option value="13" <?php echo selected( "13", $instance['posts_count'], false ); ?>>13</option>;
                <option value="14" <?php echo selected( "14", $instance['posts_count'], false ); ?>>14</option>;
                <option value="15" <?php echo selected( "15", $instance['posts_count'], false ); ?>>15</option>;
                <option value="16" <?php echo selected( "16", $instance['posts_count'], false ); ?>>16</option>;
                <option value="17" <?php echo selected( "17", $instance['posts_count'], false ); ?>>17</option>;
                <option value="18" <?php echo selected( "18", $instance['posts_count'], false ); ?>>18</option>;
                <option value="19" <?php echo selected( "19", $instance['posts_count'], false ); ?>>19</option>;
                <option value="20" <?php echo selected( "20", $instance['posts_count'], false ); ?>>20</option>;
            </select>
        </p>


        <!-- Widget columns -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'column' )); ?>"><?php esc_html_e( 'Columns', 'vitrine' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'column' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'column' ) ); ?>">
                <option value="1" <?php echo selected( "1", $instance['column'], false ); ?>>1</option>;
                <option value="2" <?php echo selected( "2", $instance['column'], false ); ?>>2</option>;
                <option value="3" <?php echo selected( "3", $instance['column'], false ); ?>>3</option>;
                <option value="4" <?php echo selected( "4", $instance['column'], false ); ?>>4</option>;
                <option value="5" <?php echo selected( "5", $instance['column'], false ); ?>>5</option>;
                <option value="6" <?php echo selected( "6", $instance['column'], false ); ?>>6</option>;
            </select>
        </p>

        <!-- Widget image resolution -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'image_resolution' )); ?>"><?php esc_html_e( 'Images Resolution', 'vitrine' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_resolution' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_resolution' ) ); ?>">
                <option value="thumbnail" <?php echo selected( "thumbnail", $instance['image_resolution'], false ); ?>>Thumbnail (150x150)</option>;
                <option value="low_resolution" <?php echo selected( "low_resolution", $instance['image_resolution'], false ); ?>>Medium (306x306)</option>;
                <option value="standard_resolution" <?php echo selected( "standard_resolution", $instance['image_resolution'], false ); ?>>Full size (612x612)</option>;
            </select>
        </p>

        <!-- Widget gutter-->
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance[ 'gutter' ], 'no' ); ?>  value="no"  id="<?php echo esc_attr($this->get_field_id( 'gutter' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'gutter' )); ?>" /> 
            <label for="<?php echo esc_attr($this->get_field_id( 'gutter' )); ?>"><?php esc_html_e( 'Remove gutter between items', 'vitrine' ); ?></label>
        </p>

        <!-- Widget carousel-->
        <p>
            <input class="checkbox instagram-carousel" type="checkbox" <?php checked( $instance[ 'carousel' ], 'enable' ); ?> value="enable" id="<?php echo esc_attr($this->get_field_id( 'carousel' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'carousel' )); ?>" /> 
            <label for="<?php echo esc_attr($this->get_field_id( 'carousel' )); ?>"><?php esc_html_e( 'Enable Carousel', 'vitrine' ); ?></label>
        </p>

        <!-- Widget carousel navigation style-->
        <p class="instagram-nav-style <?php echo ( "enable" == $instance['carousel'] ? 'show': '' ); ?>">
            <label for="<?php echo esc_attr($this->get_field_id( 'nav_style' )); ?>"><?php esc_html_e( 'Navigation Style', 'vitrine' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'nav_style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'nav_style' ) ); ?>">
                <option value="light" <?php echo selected( "light", $instance['nav_style'], false ); ?>><?php echo esc_html__("Light","vitrine"); ?></option>;
                <option value="dark" <?php echo selected( "dark", $instance['nav_style'], false ); ?>><?php echo esc_html__("Dark","vitrine"); ?></option>;
            </select>
        </p>

        <!-- Widget hover color -->
        <div style="float: left; padding-bottom: 30px;" class="instagram-hover-color">
            <label for="<?php echo esc_attr($this->get_field_id( 'hover_color' )); ?>"><?php esc_html_e( 'Hover Color', 'vitrine' ); ?></label>
            <div class="ep-imageselect-container presets">
                <span class="ep-image image-c0392b <?php echo ( "c0392b" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="c0392b">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>c0392b.png">
                </span>
                <span class="ep-image image-d35400 <?php echo ( "d35400" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="d35400">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>d35400.png">
                </span>
                <span class="ep-image image-e74c3c <?php echo ( "e74c3c" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="e74c3c">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>e74c3c.png">
                </span>
                <span class="ep-image image-e67e22 <?php echo ( "e67e22" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="e67e22">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>e67e22.png">
                </span>
                <span class="ep-image image-f39c12 <?php echo ( "f39c12" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="f39c12">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>f39c12.png">
                </span>
                <span class="ep-image image-f1c40f <?php echo ( "f1c40f" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="f1c40f">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>f1c40f.png">
                </span>
                <span class="ep-image image-1abc9c <?php echo ( "1abc9c" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="1abc9c">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>1abc9c.png">
                </span>
                <span class="ep-image image-2ecc71 <?php echo ( "2ecc71" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="2ecc71">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>2ecc71.png">
                </span>
                <span class="ep-image image-3498db <?php echo ( "3498db" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="3498db">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>3498db.png">
                </span>
                <span class="ep-image image-01558f <?php echo ( "01558f" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="01558f">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>01558f.png">
                </span>
                <span class="ep-image image-9b59b6 <?php echo ( "9b59b6" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="9b59b6">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>9b59b6.png">
                </span>
                <span class="ep-image image-ecf0f1 <?php echo ( "ecf0f1" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="ecf0f1">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>ecf0f1.png">
                </span>
                <span class="ep-image image-bdc3c7 <?php echo ( "bdc3c7" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="bdc3c7">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>bdc3c7.png">
                </span>
                <span class="ep-image image-7f8c8d <?php echo ( "7f8c8d" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="7f8c8d">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>7f8c8d.png">
                </span>
                <span class="ep-image image-95a5a6 <?php echo ( "95a5a6" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="95a5a6">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>95a5a6.png">
                </span>
                <span class="ep-image image-34495e <?php echo ( "34495e" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="34495e">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>34495e.png">
                </span>
                <span class="ep-image image-2e2e2e <?php echo ( "2e2e2e" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="2e2e2e">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>2e2e2e.png">
                </span>
                <span class="ep-image image-custom <?php echo ( "custom" == $instance['hover_color'] ? 'selected': '' ); ?>" data-name="custom">
                    <img src="<?php echo EPICO_THEME_LIB_URI . '/admin/img/vcimages/';?>custom-color.png">
                </span>
                <input type="text" id="<?php echo esc_attr($this->get_field_id( 'hover_color' )); ?>" class="hidden-field-value" name="<?php echo esc_attr($this->get_field_name( 'hover_color' )); ?>" type="text" value="<?php echo esc_attr ($instance['hover_color']); ?>">
            </div>
        </div>

        <!-- Widget custom hover color -->
        <div class="field color-field clear-after instagram-custom-hover-color <?php echo ( "custom" == $instance['hover_color'] ? 'show': '' ); ?>">
            <div class="color-field-wrap clear-after">
                <input id="<?php echo esc_attr($this->get_field_id( 'custom_hover_color' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'custom_hover_color' )); ?>" type="text" value="<?php echo esc_attr ($instance['custom_hover_color']); ?>" class="widget-insta colorinput" placeholder="" />
                <div class="color-view"></div>
            </div>
        </div>

        <!-- Widget likes count-->
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance[ 'like' ], 'enable' ); ?> value="enable" id="<?php echo esc_attr($this->get_field_id( 'like' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'like' )); ?>" /> 
            <label for="<?php echo esc_attr($this->get_field_id( 'like' )); ?>"><?php esc_html_e( 'Show likes count', 'vitrine' ); ?></label>
        </p>

        <!-- Widget comments count-->
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance[ 'comment' ], 'enable' ); ?> value="enable" id="<?php echo esc_attr($this->get_field_id( 'comment' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'comment' )); ?>" /> 
            <label for="<?php echo esc_attr($this->get_field_id( 'comment' )); ?>"><?php esc_html_e( 'Show comments count', 'vitrine' ); ?></label>
        </p>
        <?php
        }
}

// register widget
add_action( 'widgets_init', create_function( '', 'register_widget( "epico_Instagram_Widget" );' ) );
