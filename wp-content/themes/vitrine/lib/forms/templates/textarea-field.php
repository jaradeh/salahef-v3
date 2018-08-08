<?php
$name     = $vars['key'];
$settings = $vars['settings'];
$class    = epico_array_value('class', $settings);//Optional value
$placeholder = epico_array_value('placeholder', $settings);//Optional value
$label    = epico_array_value('label', $settings);//Optional value
?>

<div class="field textarea-input <?php echo esc_attr($class); ?>">
	<?php if($label != ''){ ?>
        <label for="field-<?php echo esc_attr($name); ?>"><?php echo esc_attr($label); ?></label>
    <?php } ?>
    <textarea name="<?php echo esc_attr($name); ?>" cols="30" rows="10" placeholder="<?php echo esc_attr($placeholder); ?>" ><?php echo esc_textarea($this->epico_GetValue($name)); ?></textarea>
</div>