<?php
$name     = $vars['key'];
$settings = $vars['settings'];
$class    = epico_array_value('class', $settings);//Optional value
$label    = epico_array_value('label', $settings);//Optional value
$text    = epico_array_value('text', $settings);//Optional value
$link    = epico_array_value('link', $settings);//Required value
$target    = epico_array_value('target', $settings);//_self/_blank/_parent/_top/

if($link != '')
{
	?>
<div class="field text-input ">
    <?php if($label != ''){ ?>
        <span for="field-<?php echo esc_attr($name); ?>"><?php echo esc_attr($label); ?></span>
    <?php } ?>
	<a class="field button <?php echo esc_attr($class); ?>" href="<?php echo esc_attr($link); ?>" target="<?php echo esc_attr($target); ?>">
		<?php echo esc_html($text); ?>
	</a>
</div>
	<?php
}
?>
