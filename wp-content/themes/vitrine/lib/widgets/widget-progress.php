<?php

// Widget class
class epico_Progress_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'Epico_Progress', // Base ID
            'Epico - Progress Widget', // Name
            array( 'description' => esc_html__( 'Displays 5 progress bars with a title', 'vitrine' ) ) // Args
        );
    }

    function widget( $args, $instance ) {
        extract( $args );

        // Our variables from the widget settings
        $title      = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
        
        // Before widget (defined by theme functions file)
        echo $before_widget;

        // Display the widget title if one was input
        if ( $title )
            echo $before_title . $title . $after_title;

        ?>
        <div class="progress-list">
        <?php
        for($i=1; $i<=5; $i++)
        {
            $id = "title$i"; $progId = "progress$i";

            if(!strlen($instance[$id]))
                continue;

            ?>  
            <div class="progress_bar">
                <div class="progressbar_holder">
                    <span class="progress_title"><?php echo esc_attr($instance[$id]); ?></span>
                    <span class="progress_percent_value complete"><?php echo esc_attr($instance[$progId]); ?>%</span>
                    <div class="progressbar_percent" data-percentage="<?php echo esc_attr($instance[$progId]); ?>"></div>
                </div>
            </div>
            
            <?php
        }
        ?>
        </div>
        <?php
        // After widget (defined by theme functions file)
        echo $after_widget;
    }


    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        // Strip tags to remove HTML (important for text inputs)
        $instance['title'] = strip_tags( $new_instance['title'] );

        for($i=1; $i<=5; $i++)
        {
            $id = "title$i"; $strId = "progress$i";

            $instance[$id] = trim(strip_tags( $new_instance[$id] ));
            $instance[$strId] = $new_instance[$strId];
        }

        return $instance;
    }

    function form( $instance ) {

        // Set up some default widget settings
        $defaults = array(
            'title' => 'Skills',
        );

        for($i=1; $i<=5; $i++)
        {
            $defaults["title$i"] = '';
            $defaults["progress$i"] = '';
        }

        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'vitrine') ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>"  value=" <?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <?php for($i=1; $i<=5; $i++){ ?>
        <!-- Title: Text Input -->
        <p>
            <?php $id="title$i"; ?>
            <label for="<?php echo esc_attr($this->get_field_id( $id )); ?>"><?php printf(esc_html__('Progress %d Title:', 'vitrine'), $i); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( $id )); ?>" name="<?php echo esc_attr($this->get_field_name( $id )); ?>" value="<?php echo esc_attr( $instance[$id]); ?>" />
        </p>

        <!-- Progress: Text Input -->
        <p>
            <?php $id="progress$i"; ?>
            <label for="<?php echo esc_attr($this->get_field_id( $id )); ?>"><?php printf(esc_html__('Progress %d:', 'vitrine'), $i); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id( $id )); ?>" name="<?php echo esc_attr($this->get_field_name( $id )); ?>" class="widefat">
                <?php for($j=0; $j<=100; $j+=10){ ?>
                    <option <?php selected( esc_attr($instance[$id]), $j);?> value="<?php echo esc_attr($j); ?>"><?php echo esc_html($j) ?>%</option>
                <?php } ?>
            </select>
        </p>

        <?php }//end for(...) ?>

        <?php
        }
}

// register widget
add_action( 'widgets_init', create_function( '', 'register_widget( "epico_Progress_Widget" );' ) );
