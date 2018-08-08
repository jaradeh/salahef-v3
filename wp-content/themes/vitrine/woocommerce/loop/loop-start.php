<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */
?>
<?php
//th fallowing divs are container for title and woocomerce order dropdown menu
//started in woocommerce.php

$class = '';
$layoutmode = '';

$shop_column_responsive = epico_opt('shop-column-responsive');

if ($shop_column_responsive == '2'){
	$columns = ' shop-'. epico_opt('shop-column') .'column ' .'column_res  ';
}

else{
	$columns = ' shop-'. epico_opt('shop-column') .'column ';
}

$product_style = epico_opt('shop-product-style');//Get shop product style

    //Check shop fullwidth option
	$fullwidth = epico_opt('shop-enable-fullwidth');

	if($product_style == 'centered')
	{
		$product_style = 'buttonsOnHover centered';
	}

	//Shop entrance animation
	$shop_entrance_animation = epico_opt('shop-entrance-animation');

	$class .= 'main-shop-loop ';
	$class .= $columns;
	$class .= ($fullwidth == 0 ? ' ': 'fullwidthshop ');
	$class .= ($shop_entrance_animation != 'none' ? $shop_entrance_animation . ' ' : ' ');
	$class .= $product_style . ' ';
	$layoutmode = 'data-layoutmode=' . esc_attr(epico_opt('shop-layout'));




if(is_shop() || is_product_category() || is_product_tag())
{
	
	?>
    
	<ul class="products woocommerce wc-categories <?php echo esc_attr($class); ?>" <?php echo esc_attr($layoutmode); ?>>
	<?php
}
elseif(is_product())
{
	?>
	<ul class="products <?php echo esc_attr($product_style . $columns); ?>">
	<?php
}
else
{    	
?>
	<ul class="products <?php echo esc_attr($class); ?>" <?php echo esc_attr($layoutmode); ?> >
	<?php	
}
?>
