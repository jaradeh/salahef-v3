<?php
/**
 * Shop breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/breadcrumb.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $post, $wp_query;
$page_breadcrumb = epico_opt('page_breadcrumb');
$prepend      = '';
$permalinks   = get_option( 'woocommerce_permalinks' );
$shop_page_id = wc_get_page_id( 'shop' );
$shop_page    = get_post( $shop_page_id );
$delimiter    = '<span class="delimiter">/</span>';
if($page_breadcrumb == '1'){
// If permalinks contain the shop page in the URI prepend the breadcrumb with shop
if ( $shop_page_id && $shop_page && strstr( $permalinks['product_base'], '/' . ( isset($shop_page->post_name) ? $shop_page->post_name : '' ) ) && get_option( 'page_on_front' ) !== $shop_page_id ) {
	$prepend = $before . '<a href="' . esc_url(get_permalink( $shop_page )) . '">' . ( isset($shop_page->post_title) ? $shop_page->post_title : '' ) . '</a> ' . $after . $delimiter;
}

if ( ( ! is_home() && ! is_front_page() && ! ( is_post_type_archive() && get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ) ) || is_paged() ) {

	
	if ( $breadcrumb  ) {

    // add shop home url to breadcrumbs
   		if( is_product_category() || is_product_tag() || is_product()) {
        	$shop_home_arr = array( get_the_title($shop_page_id), get_permalink($shop_page_id));

        	// insert to breadcrumbs array on second position
        	array_splice($breadcrumb, 1, 0, array($shop_home_arr));
    	}

    	echo $wrap_before;

    	foreach ( $breadcrumb as $key => $crumb ) {

        	echo $before;

        	if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) {
            	echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>';
        	} else {
            	echo esc_html( $crumb[0] );
        	}

        	echo $after;

        	if ( sizeof( $breadcrumb ) !== $key + 1 ) {
            	echo $delimiter;
        	}
    	}
    	echo $wrap_after;
		
	}
	elseif ( is_day() ) {

		echo $before . '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . get_the_time('Y') . '</a>' . $after . $delimiter;
		echo $before . '<a href="' . esc_url(get_month_link(get_the_time('Y'),get_the_time('m'))) . '">' . get_the_time('F') . '</a>' . $after . $delimiter;
		echo $before . get_the_time('d') . $after;

	} elseif ( is_month() ) {

		echo $before . '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . get_the_time('Y') . '</a>' . $after . $delimiter;
		echo $before . get_the_time('F') . $after;

	} elseif ( is_year() ) {

		echo $before . get_the_time('Y') . $after;

	} elseif ( is_post_type_archive('product') && get_option('page_on_front') !== $shop_page_id ) {

		$_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';

		if ( ! $_name ) {
			$product_post_type = get_post_type_object( 'product' );
			$_name = $product_post_type->labels->singular_name;
		}

		if ( is_search() ) {

			echo $before . '<a href="' . esc_url(get_post_type_archive_link('product')) . '">' . $_name . '</a>' . $delimiter . esc_html__( 'Search results for &ldquo;', 'vitrine' ) . get_search_query() . '&rdquo;' . $after;

		} elseif ( is_paged() ) {

			echo $before . '<a href="' . esc_url(get_post_type_archive_link('product')) . '">' . $_name . '</a>' . $after;

		} else {

			echo $before . $_name . $after;

		}

	} elseif ( is_single() && !is_attachment() ) {

		if ( get_post_type() == 'product' ) {

			echo $prepend;

			if ( $terms = wp_get_object_terms( $post->ID, 'product_cat' ) ) {
				$term = current( $terms );
				$parents = array();
				$parent = $term->parent;

				while ( $parent ) {
					$parents[] = $parent;
					$new_parent = get_term_by( 'id', $parent, 'product_cat' );
					$parent = $new_parent->parent;
				}

				if ( ! empty( $parents ) ) {
					$parents = array_reverse($parents);
					foreach ( $parents as $parent ) {
						$item = get_term_by( 'id', $parent, 'product_cat');
						echo $before . '<a href="' . esc_url(get_term_link( $item->slug, 'product_cat' )) . '">' . $item->name . '</a>' . $after . $delimiter;
					}
				}

				echo $before . '<a href="' . esc_url(get_term_link( $term->slug, 'product_cat' )) . '">' . $term->name . '</a>' . $after . $delimiter;

			}

			echo $before . esc_html(get_the_title()) . $after;

		} elseif ( get_post_type() != 'post' ) {

			$post_type = get_post_type_object( get_post_type() );
			$slug = $post_type->rewrite;
				echo $before . '<a href="' . esc_url(get_post_type_archive_link( get_post_type() )) . '">' . $post_type->labels->singular_name . '</a>' . $after . $delimiter;
			echo $before . esc_html(get_the_title()) . $after;

		} else {

			$cat = current( get_the_category() );
			echo get_category_parents( $cat, true, $delimiter );
			echo $before . esc_html(get_the_title()) . $after;

		}

	} elseif ( is_404() ) {

		echo $before . esc_html__( 'Error 404', 'vitrine' ) . $after;

	} elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' ) {

		$post_type = get_post_type_object( get_post_type() );

		if ( $post_type )
			echo $before . $post_type->labels->singular_name . $after;

	} elseif ( is_attachment() ) {

		$parent = get_post( $post->post_parent );
		$cat = get_the_category( $parent->ID );
		$cat = $cat[0];
		echo get_category_parents( $cat, true, '' . $delimiter );
		echo $before . '<a href="' . esc_url(get_permalink( $parent )) . '">' . $parent->post_title . '</a>' . $after . $delimiter;
		echo $before . esc_html(get_the_title()) . $after;

	} elseif ( is_page() && !$post->post_parent ) {

		echo $before . esc_html(get_the_title()) . $after;

	} elseif ( is_page() && $post->post_parent ) {

		$parent_id  = $post->post_parent;
		$breadcrumbs = array();

		while ( $parent_id ) {
			$page = get_page( $parent_id );
			$breadcrumbs[] = '<a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html(get_the_title( $page->ID )) . '</a>';
			$parent_id  = $page->post_parent;
		}

		$breadcrumbs = array_reverse( $breadcrumbs );

		foreach ( $breadcrumbs as $crumb )
			echo $crumb . '' . $delimiter;

		echo $before . esc_html(get_the_title()) . $after;

	} elseif ( is_search() ) {

		echo $before . esc_html__( 'Search results for &ldquo;', 'vitrine' ) . get_search_query() . '&rdquo;' . $after;

	} elseif ( is_tag() ) {

			echo $before . esc_html__( 'Posts tagged &ldquo;', 'vitrine' ) . single_tag_title('', false) . '&rdquo;' . $after;

	} elseif ( is_author() ) {

		$userdata = get_userdata($author);
		echo $before . esc_html__( 'Author:', 'vitrine' ) . ' ' . $userdata->display_name . $after;

	}

	if ( get_query_var( 'paged' ) )
		echo ' (' . esc_html__( 'Page', 'vitrine' ) . ' ' . get_query_var( 'paged' ) . ')';

	echo $wrap_after;

}

}