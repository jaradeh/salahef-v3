<?php
$name = $vars['key'];
$settings = $vars['settings'];
$options  = $settings['options'];
$selected     = epico_array_value('selected', $settings, array());//Optional value
$optionsFlags = epico_array_value('option-flags', $settings, array());
$flags        = epico_array_value('flags', $settings);//Optional value
$class        = epico_array_value('class', $settings);
$label        = epico_array_value('label', $settings);//Optional value
?>

<div class="field clear-after <?php echo esc_attr($class); ?>">
	<div class="ep-input">
		<div class="ep-input-multiselect">
			<?php if($label != ''){ ?>
				<span class="field-label"><?php echo esc_attr($label); ?></span>
			<?php } ?>
			<div class="<?php if($label != ''){echo ' has-label';} ?>">
				<div></div>
			
				<select name="<?php echo esc_attr($name); ?>" multiple="" data-flags="<?php echo esc_attr($flags); ?>" class="chosen">
					<?php
					foreach($options as $value => $text)
					{
						$selectedAttr = in_array($value, $selected) ? 'selected="selected"' : '';
						$flags        = epico_array_value($value, $optionsFlags);
						?>
						<option value="<?php echo esc_attr($value); ?>" data-flags="<?php echo esc_attr($flags); ?>" <?php echo ($selectedAttr); ?> ><?php echo esc_attr($text); ?></option>
					<?php
					}
					?>
				</select>
			</div>
			
		</div>
    </div>
</div>
