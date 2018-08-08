<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * In Stock Filter Widget
 *
 * Filter to show in stock products.
 *
 */
// Ensure woocommerce is active
if(function_exists('is_woocommerce')) {

	 class epico_WC_Widget_In_Stock_Filter extends WC_Widget {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->widget_cssclass    = 'woocommerce widget_in_stock_filter';
			$this->widget_description = esc_html__( 'Shows in stock filter in a widget which lets you narrow down the list of in stock products.', 'vitrine' );
			$this->widget_id          = 'epico_woocommerce_in_stock_filter';
			$this->widget_name        = esc_html__( 'Epico WC in stock Filter', 'vitrine' );
			$this->settings           = array(
				'title' => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter in Stock', 'vitrine' ),
					'label' => esc_html__( 'Title', 'vitrine' )
				),
			);
			
			add_action('pre_get_posts',array($this,'in_stock_items'));

			parent::__construct();
		}



		function in_stock_items($query) {

		    if (!is_admin() && ($query->is_post_type_archive( 'product' ) || $query->is_tax( get_object_taxonomies( 'product' )))) {

		    	if(isset($_GET['availability']) && $_GET['availability'] == 'in_stock')
		    	{
		    		$meta_query = WC()->query->get_meta_query();
		    		$meta_query[] = array(
		                    'key' => '_stock_status',
		                    'value' => 'outofstock', //instock,outofstock
		                    'compare' => 'NOT IN',
		            );

			        if(isset($_GET['status']) && $_GET['status'] == 'sale')
			        {
			        	$meta_query[] = array(
					    'relation' => 'OR',
				        array( // Simple products type
				            'key'           => '_sale_price',
				            'value'         => 0,
				            'compare'       => '>',
				            'type'          => 'numeric'
				        ),
				        array( // Variable products type
				            'key'           => '_min_variation_sale_price',
				            'value'         => 0,
				            'compare'       => '>',
				            'type'          => 'numeric'
				        )
		            	);
			        }

		        	$query->set('meta_query',$meta_query);
		    	}
		    }
		}


		/**
		 * widget function.
		 *
		 * @see WP_Widget
		 * @param array $args
		 * @param array $instance
		 */

		public function widget( $args, $instance ) {
			global $wp, $wp_the_query;

			extract( $args );

			if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
				return;
			}

			if ( ! $wp_the_query->post_count ) {
				return;
			}

			$min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
			$max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';

			$title  = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
			
			if ( get_option( 'permalink_structure' ) == '' ) {
				$link = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
			} else {
				$link = preg_replace( '%\/page/[0-9]+%', '', home_url( $wp->request ) );
			}
			
			if ( get_search_query() ) {
				$link = add_query_arg( 's', rawurlencode( htmlspecialchars_decode( get_search_query() ) ), $link );
			}

			// Min/Max
			if ( isset( $_GET['min_price'] ) ) {
				$link = add_query_arg( 'min_price', esc_attr($_GET['min_price']), $link );
			}

			if ( isset( $_GET['max_price'] ) ) {
				$link = add_query_arg( 'max_price', esc_attr($_GET['max_price']), $link );
			}

			if ( ! empty( $_GET['post_type'] ) ) {

				$link = add_query_arg( 'post_type', esc_attr( $_GET['post_type'] ), $link );
			}

			if ( ! empty ( $_GET['product_cat'] ) ) {
				$link = add_query_arg( 'product_cat', esc_attr( $_GET['product_cat'] ), $link );
			}

			if ( ! empty( $_GET['product_tag'] ) ) {
				$link = add_query_arg( 'product_tag', esc_attr( $_GET['product_tag'] ), $link );
			}

			if ( ! empty( $_GET['orderby'] ) ) {
				$link = add_query_arg( 'orderby', esc_attr( $_GET['orderby'] ), $link );
			}

			// Min Rating Arg
			if ( isset( $_GET['rating_filter'] ) ) {
				$link = add_query_arg( 'rating_filter', wc_clean( $_GET['rating_filter'] ), $link );
			}

			//Epico
			if ( ! empty( $_GET['status'] ) ) {
				$link = add_query_arg( 'status', esc_attr( $_GET['status'] ), $link );
			}

			if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) {
				foreach ( $_chosen_attributes as $attribute => $data ) {
					$taxonomy_filter = 'filter_' . str_replace( 'pa_', '', $attribute );
		
					$link = add_query_arg( esc_attr( $taxonomy_filter ), esc_attr( implode( ',', $data['terms'] ) ), $link );
					
					if ( 'or' == $data['query_type'] ) {
						$link = add_query_arg( esc_attr( str_replace( 'pa_', 'query_type_', $attribute ) ), 'or', $link );
					}
				}
			}

			//Echo wrapper of widget & title
			echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title;
			
			
			$output = '<ul class="in-stock-filter">';
			        
			if ( isset($_GET['availability']) && $_GET['availability'] == 'in_stock' ) {
				$output .= '<li class="chosen"><a href="' . esc_url( $link ) . '"><span></span>' . esc_html__( 'In stock only', 'vitrine' ) . '</a></li>';
			} else {
				$url = add_query_arg( array( 'availability' => 'in_stock' ), $link );
				$output .= '<li><a href="' . esc_url( $url ) . '"><span></span>' . esc_html__( 'In stock only', 'vitrine' ) . '</a></li>';

			}
		
			$output .= '</ul>';

			echo $output;
			echo $after_widget;
		}
	}

	add_action( 'widgets_init', 'epico_woocommerce_in_stock_widget', 25 );
	 
	function epico_woocommerce_in_stock_widget() {
	    	register_widget( 'epico_WC_Widget_In_Stock_Filter' );
	}
}