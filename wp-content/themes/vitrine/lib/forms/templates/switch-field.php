<?php
$name     = $vars['key'];
$settings = $vars['settings'];
$class    = epico_array_value('class', $settings);//Optional value
$state0   = $settings['state0'];
$state1   = $settings['state1'];
$default  = epico_array_value('default', $settings);//Optional value
$label        = epico_array_value('label', $settings);//Optional value
$val      = $this->epico_GetValue($name);
$val      = strlen($val) ? $val : $default;
?>
<div class="field clear-after <?php echo esc_attr($class); ?>">
    <?php if($label != ''){ ?>
        <span class="field-label"><?php echo esc_attr($label); ?></span>
    <?php } ?>
    <label><div class="label"></div></label>
    <input name="<?php echo esc_attr($name); ?>" type="range" class="switch" value="<?php echo esc_attr( $val ); ?>" min="0" max="1" step="1"  data-state0="<?php echo esc_attr($state0); ?>" data-state1="<?php echo esc_attr($state1); ?>" />
</div>