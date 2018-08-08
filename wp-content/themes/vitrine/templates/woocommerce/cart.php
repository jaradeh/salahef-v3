<?php 

    $cart_style = epico_opt('shop-cart-style');
    $header_type = epico_opt('header-type');
    global $woocommerce;

?>
<div class="cartSidebarbtn widget widget_woocommerce-dropdown-cart responsive-cart <?php if ( $cart_style == 1 ) { ?>light<?php } ?>">
	<?php
		echo '<a href="'. wc_get_cart_url() .'"></a>';
	?>
    <div class="cart-contents"><div class="cartContentsCount"><?php echo  WC()->cart->cart_contents_count; ?></div></div>
    <span class="icon icon-cart"></span>
</div>
