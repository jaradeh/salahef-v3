<?php
$name     = $vars['key'];
$settings = $vars['settings'];
$class    = epico_array_value('class', $settings);//Optional value
$checked = epico_array_value('checked', $settings);//Optional value
$label    = epico_array_value('label', $settings);//Optional value
$description    = epico_array_value('description', $settings);//Optional description
$value       = ( ( esc_attr( $this->epico_GetValue($name) ) == "") && (epico_array_value('value', $settings) != "") ) ? epico_array_value('value', $settings) : esc_attr( $this->epico_GetValue($name) );

//remove -{id} from end of name of filed[just used in admin menu page] becouse in that page we make names unique by adding -{id}
$current_value = get_post_meta(get_the_ID(), rtrim($name, "-". get_the_ID()) , true);

?>

<div class="field checkbox-input <?php echo esc_attr($class); ?>">
    <input type="checkbox" id="field-<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" <?php echo ($current_value != '')?'checked="checked"':''; ?> />
	<?php if($description != ''){ ?>
        <span class="description"><?php echo esc_attr($description); ?></span>
    <?php } ?>
	<?php if($label != ''){ ?>
        <label for="field-<?php echo esc_attr($name); ?>"><?php echo esc_attr($label); ?></label>
    <?php } ?>
	
</div>
<?php if (strpos ($class,'related')!== false){ ?>
    <div class="clearfix"></div>
<?php }