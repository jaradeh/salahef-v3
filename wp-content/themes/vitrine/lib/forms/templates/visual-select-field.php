<?php
$name     = $vars['key'];
$settings = $vars['settings'];
$label    = epico_array_value('label', $settings);//Optional value
$selected = $this->epico_GetValue($name);
$options  = $settings['options'];
$class    = epico_array_value('class', $settings);//Optional value
$default  = epico_array_value('default', $settings);//Optional value
?>
<div class="field imageSelect <?php echo esc_attr($class); ?>">

	<?php if($label != ''){ ?>
        <label for="field-<?php echo esc_attr($name); ?>"><?php echo esc_attr($label); ?></label>
    <?php } ?>

	<div class="imageList">
		<?php
		foreach($options as $key => $value)
		{
			$selectedClass = ($value == $selected || ($selected == '' && $default == $value)) ? 'selected' : '';
			?>
			<a href="#" class="<?php echo esc_attr($key . ' ' . $selectedClass); ?>"><?php echo esc_html($value); ?></a>
		<?php
		}
		?>
	</div>
	
    <input name="<?php echo esc_attr($name); ?>" type="text" value="<?php echo esc_attr($selected); ?>" />
</div>