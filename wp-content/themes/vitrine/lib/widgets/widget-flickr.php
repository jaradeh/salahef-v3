<?php

// Widget class
class epico_Flickr_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'Epico_Flickr', // Base ID
            'Epico - Flickr Widget', // Name
            array( 'description' => esc_html__( 'Displays your Flickr photo stream', 'vitrine' ) ) // Args
        );
    }
    
    
    function widget( $args, $instance ) {
        extract( $args );

        // Our variables from the widget settings
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
        $flickrID = isset( $instance['flickrID'] ) ? esc_attr( $instance['flickrID'] ) : '';
        $postcount = isset( $instance['postcount'] ) ? esc_attr( $instance['postcount'] ) : '';
        $type = isset( $instance['type'] ) ? esc_attr( $instance['type'] ) : '';
        $display = isset( $instance['display'] ) ? esc_attr( $instance['display'] ) : '';

        // Before widget (defined by theme functions file)
        echo $before_widget;

        // Display the widget title if one was input
        if ( $title )
            echo $before_title . $title . $after_title;

        // Display Flickr Photos
         ?>

        <div class="flickr-container">

            <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo esc_attr($postcount) ?>&amp;display=<?php echo esc_attr($display) ?>&amp;size=s&amp;layout=x&amp;source=<?php echo esc_attr($type) ?>&amp;<?php echo esc_attr($type) ?>=<?php echo esc_attr($flickrID) ?>"></script>

        </div>

        <?php

        // After widget (defined by theme functions file)
        echo $after_widget;

    }


    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        // Strip tags to remove HTML (important for text inputs)
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['flickrID'] = strip_tags( $new_instance['flickrID'] );

        // No need to strip tags
        $instance['postcount'] = $new_instance['postcount'];
        $instance['type'] = $new_instance['type'];
        $instance['display'] = $new_instance['display'];

        return $instance;
    }

    function form( $instance ) {

        // Set up some default widget settings
        $defaults = array(
            'title' => 'My Photostream',
            'flickrID' => '8710861@N07',
            'postcount' => '8',
            'type' => 'user',
            'display' => 'latest',
        );

        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'vitrine') ?></label>           
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value=" <?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <!-- Flickr ID: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'flickrID' )); ?>"><?php esc_html_e('Flickr ID:', 'vitrine') ?> (<a href="http://idgettr.com/">idGettr</a>)</label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'flickrID' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'flickrID' )); ?>" value="<?php echo esc_attr( $instance['flickrID'] ); ?>" />
        </p>

        <!-- Postcount: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'postcount' )); ?>"><?php esc_html_e('Number of Photos:', 'vitrine') ?></label>
            <select id="<?php echo esc_attr($this->get_field_id( 'postcount' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'postcount' )); ?>" class="widefat">
                <?php for($i=1; $i<=14; $i++){ ?>
                <option <?php selected(esc_attr($instance['postcount']), $i);?>><?php echo esc_html($i) ?></option>
                <?php } ?>
            </select>
        </p>

        <!-- Type: Select Box -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'type' )); ?>"><?php esc_html_e('Type (user or group):', 'vitrine') ?></label>
            <select id="<?php echo esc_attr($this->get_field_id( 'type' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'type' )); ?>" class="widefat">
                <option <?php if ( 'user' == esc_attr($instance['type']) ) echo 'selected="selected"'; ?>>user</option>
                <option <?php if ( 'group' == esc_attr($instance['type']) ) echo 'selected="selected"'; ?>>group</option>
            </select>
        </p>

        <!-- Display: Select Box -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'display' )); ?>"><?php esc_html_e('Display (random or latest):', 'vitrine') ?></label>
            <select id="<?php echo esc_attr($this->get_field_id( 'display' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'display' )); ?>" class="widefat">
                <option <?php if ( 'random' == esc_attr($instance['display']) ) echo 'selected="selected"'; ?>>random</option>
                <option <?php if ( 'latest' == esc_attr($instance['display']) ) echo 'selected="selected"'; ?>>latest</option>
            </select>
        </p>

        <?php
        }
}

// register widget
add_action( 'widgets_init', create_function( '', 'register_widget( "epico_Flickr_Widget" );' ) );
