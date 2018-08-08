<?php
$name     = $vars['key'];
$settings = $vars['settings'];
$class    = epico_array_value('class', $settings);//Optional value
$placeholder = epico_array_value('placeholder', $settings);//Optional value
$label    = epico_array_value('label', $settings);//Optional value
if($name == 'attribute-title[]' || $name == 'attribute-value[]')
    $value       = $vars['val'];
else
    $value       = ( ( esc_attr( $this->epico_GetValue($name) ) == "") && (epico_array_value('value', $settings) != "") ) ? epico_array_value('value', $settings) : esc_attr( $this->epico_GetValue(rtrim($name, "-". get_the_ID())) );

?>

<div class="field text-input <?php echo esc_attr($class); ?>">
    <?php if($label != ''){ ?>
        <label for="field-<?php echo esc_attr($name); ?>"><?php echo esc_attr($label); ?></label>
    <?php } ?>
    <input type="text" id="field-<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" />
</div>
