<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Epico codes
if(epico_get_meta('product_detail_style_inherit') == '1')
{
    $product_detail_style = epico_get_meta('product_detail_style'); // style of product detail in product page
    $product_detail_sidebar_position = epico_get_meta('product-detail-sidebar-position'); // style of sidebar in product page
    $product_detail_sidebar_responsive = epico_get_meta('product-detail-sidebar-responsive'); // style of sidebar in product page
}
else
{
    $product_detail_style = epico_opt("product-detail-style"); // style of product detail in theme settings
    $product_detail_sidebar_position = epico_opt('product-detail-sidebar-position'); // style of sidebar in product page
    $product_detail_sidebar_responsive = epico_opt('product-detail-sidebar-responsive'); // style of sidebar in product page
}

$catalog_mode =  epico_opt('catalog_mode');			
$product_navigation =  epico_opt('product_navigation');			
$product_breadcrumb =  epico_opt('product_breadcrumb');			
$compare_wishlist_style = epico_opt('product_wishlist_compare_style');
$compare_wishlist_style_class = '';
if( ($catalog_mode != 0) || ($compare_wishlist_style != 0))
{
	$compare_wishlist_style_class = "seperate_rows_wishlist_compare";
}


//End of Epico codes
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }

?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( array($product_detail_style, $compare_wishlist_style_class) ); ?>>
	<div class="product-detail-bg">
		<div class="container">
			<?php
			if( $product_detail_style == 'pd_classic_sidebar')
			{
				echo '<div class="product-detail-content-with-sidebar ' . $product_detail_sidebar_position .'">';//Put wrapper around image & summary
			}
			?>
			<span class="product-line hidden-phone hidden-v-tablet"></span>
		    <?php

		    if(!isset($is_product_shortcode) )
			{
				if(($product_detail_style == "pd_top")&& ($product_breadcrumb!=0)){
					/* Breadcrumb */
					woocommerce_breadcrumb( array(
						'delimiter'   	=> '<span class="delimiter">/</span>',
						'wrap_before'	=> '<nav id="breadcrumb" class="woocommerce-breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
						'wrap_after'	=> '</nav>',
					) );
				}

				/* Prev/next product buttons */
				$thumbnail = '';
				if ($product_navigation !=0){
				$prevPost = get_previous_post();
				if($prevPost) {
					$thumbnail = get_the_post_thumbnail($prevPost->ID , 'shop_thumbnail');
                }
                echo '<span id="next-product" class="hidden-phone">';
				previous_post_link( '%link', '<span></span>'. $thumbnail );
				echo '</span>';

				$nextPost = get_next_post();
				if($nextPost) {
					$thumbnail = get_the_post_thumbnail($nextPost->ID , 'shop_thumbnail'); 
				}
				echo '<span id="prev-product" class="hidden-phone">';
				next_post_link( '%link', '<span></span>' . $thumbnail );
				echo '</span>';
			}	
			}	

				/**
				 * woocommerce_before_single_product_summary hook
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );
			?>
	<?php
	if($product_detail_style == "pd_top")
	{ ?>
		</div>
	</div>
	<div class="container">
	<?php } ?>

			<div class="summary entry-summary">
				<?php
					if(($product_detail_style!="pd_top") && ($product_breadcrumb!=0) ){
						/* Breadcrumb */
						woocommerce_breadcrumb( array(
							'delimiter'   	=> '<span class="delimiter">/</span>',
							'wrap_before'	=> '<nav id="breadcrumb" class="woocommerce-breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
							'wrap_after'	=> '</nav>',
						) );
					}					
				
					/**
					 * woocommerce_single_product_summary hook
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 40
					 * @hooked woocommerce_template_single_sharing - 50
					 */
					do_action( 'woocommerce_single_product_summary' );
				?>

			</div><!-- .summary -->
		<?php

		if( $product_detail_style == 'pd_classic_sidebar' )
		{
			echo '</div>';//Put wrapper around image & summary
		}


		if($product_detail_style == "pd_top"){ ?>
		</div>
		<?php
		}
		elseif($product_detail_style == "pd_classic_sidebar"){
			$pd_sidebar_in_responsive_class = '';
			if($product_detail_sidebar_responsive == 0)
			{
				$pd_sidebar_in_responsive_class = ' visible-desktop';
			}
		 ?>
			<div id="woocommerce-product-sidebar" class="span3<?php echo $pd_sidebar_in_responsive_class; ?>"><?php echo epico_get_sidebar('woocommerce-product-sidebar'); ?></div>
		<?php
		}

		?>

	<?php
	if($product_detail_style != "pd_top")
	{ ?>
		</div>
	</div>
	<?php } ?>

		<?php
			/**
			 * woocommerce_after_single_product_summary hook
			 *
			 * @hooked woocommerce_output_product_data_tabs - 10
			 * @hooked woocommerce_upsell_display - 15
			 * @hooked woocommerce_output_related_products - 20
			 */
			do_action( 'woocommerce_after_single_product_summary' );
		?>

		<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>

<?php  
	// footer section
	$widgetized_footer = epico_opt("product_widget_area");
	if ($widgetized_footer != 0) {
		//Footer Widgetized Area
		get_template_part('templates/section', 'widgetized_footer');
	}
?>
<?php get_footer(); ?>