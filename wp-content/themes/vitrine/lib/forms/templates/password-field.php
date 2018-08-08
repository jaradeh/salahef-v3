<?php
$name     = $vars['key'];
$settings = $vars['settings'];
$class    = epico_array_value('class', $settings);//Optional value
$placeholder = epico_array_value('placeholder', $settings);//Optional value
$label    = epico_array_value('label', $settings);//Optional value
?>

<div class="field text-input <?php echo esc_attr($class); ?>">
    <?php if($label != ''){ ?>
        <label for="field-<?php echo esc_attr($name); ?>"><?php echo esc_attr($label); ?></label>
    <?php } ?>
    <input type="password" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr( $this->epico_GetValue($name) ); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" />
</div>