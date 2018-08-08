<?php

    get_header();

?>

<!-- Page Content-->
<?php
$shopClass = '';
if (is_shop()) {
	$shopClass = ' ep_shop_page';
} else if (is_product()) {
	$shopClass = ' ep_product_page';
};

//Check shop fullwidth option
$fullwidth = epico_opt('shop-enable-fullwidth');
if(epico_opt('shop-enable-fullwidth') == '1')
{
	$shopClass .= ' fullwidth';
}
?>
<div class="wrap epicoSection customSection woocommercepage <?php echo esc_attr($shopClass); ?>" id="pageHeight">

	<?php
	//Get the sidebar option
	$sidebarPos = epico_opt('shop-sidebar-position');


    // shop and category page display options 
    $display_type_shop_wc_setting = get_option('woocommerce_shop_page_display');
    $display_type_wc_setting = get_option('woocommerce_category_archive_display');

	if( 0 == $sidebarPos ) {

		if(is_product()) {
	
			 woocommerce_content();

		}  else if(is_shop() || is_product_category() || is_product_tag()) { ?>
			<div class="row  <?php if( $display_type_shop_wc_setting == 'subcategories' || $display_type_wc_setting == 'subcategories') {?> shop_is_categories_style <?php } ?>"> <!-- "shop_categories_style" categories style for shop -->  
				<?php woocommerce_content(); ?>
			</div>

        <?php } else { ?>

            <div class="row">
             
	                <?php woocommerce_content(); ?>
                
            </div>

		<?php
		}
	} else { ?>
		<!-- has Sidebar -->   
		<?php if(is_product()) { ?>

			<div class="shop_coulmn3">
				<?php woocommerce_content(); ?>
			</div>
				  
		<?php }  else if(is_shop() || is_product_category() || is_product_tag()) { ?>

			<div class="row  <?php if( $display_type_shop_wc_setting == 'subcategories' || $display_type_wc_setting == 'subcategories') {?> shop_is_categories_style <?php } ?>">  <!-- "shop_categories_style" categories style for shop -->  
				<?php woocommerce_content(); ?>
			</div>

        <?php } else { ?>

            <div class="row">
                <?php woocommerce_content(); ?>
            </div>
        <?php } ?>

    <?php } ?> 

</div><!-- Page Content End -->

<?php  if(is_shop()) { ?>

	<?php 
	//disable processing of footer widget area and map in djax requests for better performance
	if(!epico_is_shop_ajax_request()) {
		if ( get_post() )
		{
			$footerMap = epico_get_meta("footer-map");
			
			if ($footerMap == "1") {
				get_template_part('templates/section', 'location');
			}

			$widgetized_footer = epico_get_meta("footer-widget-area");

			if ($widgetized_footer == "inherit") {

				$widgetized_footer_theme_setting = epico_opt('footer-widget-area');
				if($widgetized_footer_theme_setting)
				{
					//Footer Widgetized Area
					get_template_part('templates/section', 'widgetized_footer');
				}
				
			}
			elseif($widgetized_footer == "enable")
			{
				//Footer Widgetized Area
				get_template_part('templates/section', 'widgetized_footer');
			}

			
		}
	}
} 

$widgetized_footer = epico_opt("category_widget_area");
	if (($widgetized_footer != 0) &&  (is_product_category() || is_product_tag())) {
	
		get_template_part('templates/section', 'widgetized_footer');
	} 
?>
<?php get_footer(); ?>