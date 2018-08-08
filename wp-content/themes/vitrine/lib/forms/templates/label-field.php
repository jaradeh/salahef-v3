<?php
$settings = $vars['settings'];
$desc     = epico_array_value('desc', $settings);//Optional value
$title    = epico_array_value('title', $settings);//Optional value
$class    = epico_array_value('class', $settings);//Optional value
?>
<div class="field clear-after ep-input-label  <?php echo esc_attr($class); ?>">
    <strong><?php echo esc_attr($title); ?></strong>
    <?php if(strlen($desc)){ ?>
    <span><?php echo esc_attr($desc); ?></span>
    <?php } ?>
</div>