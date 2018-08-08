<?php
$name        = $vars['key'];
$settings    = $vars['settings'];
$value       = $vars['val'];
$class       = epico_array_value('class', $settings);
$title       = epico_array_value('title', $settings, esc_html__('Upload Image', 'vitrine'));
$referer     = epico_array_value('referer', $settings);
$placeholder = epico_array_value('placeholder', $settings);
$label       = epico_array_value('label', $settings);//Optional value
?>
<div class="field upload-field clear-after <?php echo esc_attr($class); ?>" data-title="<?php echo esc_attr($title); ?>" data-referer="<?php echo esc_attr($referer); ?>" >
    <?php if($label != ''){ ?>
        <label for="field-<?php echo esc_attr($name); ?>"><?php echo esc_attr($label); ?></label>
    <?php } ?>
    <input type="text" id="field-<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" />
    <a href="#" class="upload-button"><?php esc_html_e('Browse', 'vitrine'); ?></a>
    <div class="upload-thumb <?php if($value) { echo 'show'; } ?>">
    	<div class="close"><span class="close-icon"></span></div>
    	<img class="" src="<?php echo esc_attr( $value ); ?>" alt="<?php echo esc_attr($label); ?>">
    </div>
</div>