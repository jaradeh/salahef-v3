<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

    if ( ! defined( 'ABSPATH' ) ) {
	    exit;
    }
    if(!isset($hover_color)) // when used in shop/category pages not as shortcode
    {
	    $hover_color ='rgba(0, 0, 0, 0.7)';
	    $image_size = 'scale(1,1)';
	    $hover_text_color = '#FFF' ;
	    $border= 'none';
	    $count = 1;
	    $description = '';
	    $style = '#FFF';
    }

    $class = array();
    if($border == '')
    {
	    $class[] = 'with-border';
    }

    //EPICOMEDIA CUSTOM CODE 
    // Find the category + category parent, if applicable
    $term 			= get_queried_object();
    $parent_id 		= empty( $term->term_id ) ? 0 : $term->term_id;


    // NOTE: using child_of instead of parent - this is not ideal but due to a WP bug ( https://core.trac.wordpress.org/ticket/15626 ) pad_counts won't work
    $product_categories = get_categories( apply_filters( 'woocommerce_product_subcategories_args', array(
        'parent'       => $parent_id,
        'menu_order'   => 'ASC',
        'hide_empty'   => 0,
        'hierarchical' => 1,
        'taxonomy'     => 'product_cat',
        'pad_counts'   => 1,
    ) ) );
    
    $display_type_shop_wc_setting = get_option('woocommerce_shop_page_display');
    $display_type_wc_setting = get_option('woocommerce_category_archive_display');
    $display_type_cat_setting = get_woocommerce_term_meta( $parent_id, 'display_type' );

  
    if ( (is_shop() && $display_type_shop_wc_setting !== 'subcategories') || (is_product_category() && $display_type_wc_setting !== 'subcategories' && $product_categories) )  {
        return;
    }
    //EPICOMEDIA CUSTOM CODE 

?> 
<li  <?php wc_product_cat_class($class, $category); ?> >
	<?php do_action( 'woocommerce_before_subcategory', $category ); ?>
    
		<div class="interactive-background-image">
		<?php
			/**
			 * woocommerce_before_subcategory_title hook
			 *
			 * @hooked woocommerce_subcategory_thumbnail - 10
			 */
			//Epico code ( pass $iamge_size to acton handler)
			do_action( 'woocommerce_before_subcategory_title', $category, $image_size );
		?>
		</div>
                   <div class="category-hover" style="background-color: <?php echo esc_attr($hover_color); ?>"> </div>
            <style type="text/css" media="all" scoped>
            <?php if(strlen(esc_attr($hover_text_color))) {//Changes on hover text color
                    echo ".product-category:hover h3#categories_".$category ->term_id;?>
                    {
                        color:<?php echo esc_attr($hover_text_color); ?>!important;
                    }
           <?php } ?>
           </style>
         <h3 id="<?php echo esc_attr('categories_'.$category ->term_id); ?>" style="color: <?php echo esc_attr($style);?>">
			        <?php
				        echo esc_html($category->name);
				        if ( $count== 'enable' && $category->count > 0 )
					        echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category );
						 
						if($description == 'enable' && $category->description != '')
							echo '<span>' . esc_html($category->description) . '</span>';
                    ?>
		    </h3>

		<?php
			/**
			 * woocommerce_after_subcategory_title hook
			 */
			do_action( 'woocommerce_after_subcategory_title', $category );
		?>

	<?php do_action( 'woocommerce_after_subcategory', $category); ?>
</li>
