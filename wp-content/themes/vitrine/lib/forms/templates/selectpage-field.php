<?php
$name = $vars['key'];
$settings = $vars['settings'];
$default  = epico_array_value('default', $settings);
$selected = $this->epico_GetValue($name);
$selected = $selected == '' ? $default : $selected;
$class        = epico_array_value('class', $settings);
$label        = epico_array_value('label', $settings);//Optional value
$maintenance        = epico_array_value('maintenance', $settings);//Optional value
?>
<div class="field clear-after <?php echo esc_attr($class); ?>">
    <div class="select<?php if($label != ''){echo ' has-label';} ?>">
        <div></div>

        <?php  if($maintenance == 1 ) {
			$args = array(
				'meta_key' => '_wp_page_template',
				'meta_value' => 'maintenance.php',
                'selected'              => $selected,
                'name'                  =>  $name,
			);

			$the_pages = new WP_Query($args);

			if( $the_pages->have_posts() ){
				while( $the_pages->have_posts() ){
					$the_pages->the_post();
					the_title();
				}
			}
			wp_reset_postdata();	
			wp_dropdown_pages( $args );

		}  else {
			$args = array(
				'selected'  => $selected,
				'name'  =>  $name,
			);
			wp_dropdown_pages( $args );

		} ?>

    </div>
</div>