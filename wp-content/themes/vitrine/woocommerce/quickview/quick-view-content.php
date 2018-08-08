<?php

global $post, $product;


if(epico_get_meta(get_the_ID(),'product_detail_style_inherit') == '1')
{
	$product_detail_style = epico_get_meta(get_the_ID(),'product_detail_style'); // style of product detail in product page
}
else
{
	$product_detail_style = epico_opt("product-detail-style"); // style of product detail in theme settings
}

$qv_slider_style = "";
if($product_detail_style != "pd_ep_classic" && $product_detail_style != "pd_classic")
{
	$qv_slider_style = "fade-style";
}
 ?>
	<div id="product-<?php esc_attr(the_ID()); ?>" <?php post_class('product '. $qv_slider_style); ?>>

		<?php do_action( 'quick_view_product_image' ); ?>

		<div class="summary entry-summary">
			<div class="summary-content">
				<?php do_action( 'quick_view_product_summary' ); ?>
			</div>
		</div>

	</div>