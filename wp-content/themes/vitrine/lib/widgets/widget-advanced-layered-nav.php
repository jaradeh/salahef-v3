<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Layered Navigation Widget
 *
 * Filter products by attributes
 */
// Ensure woocommerce is active
if(function_exists('is_woocommerce')) {
	class epico_Advanced_WC_Widget_Layered_Nav extends WC_Widget {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->widget_cssclass    = 'Epico woocommerce widget_layered_nav';
			$this->widget_description = esc_html__( 'Shows a custom attribute in a widget which lets you narrow down the list of products when viewing product categories.', 'vitrine' );
			$this->widget_id          = 'woocommerce_layered_nav';
			$this->widget_name        = esc_html__( 'Epico WC Layered Nav', 'vitrine' );

			// This is where we add the style and script
	        add_action( 'load-widgets.php', array(&$this, 'epico_Admin_Scripts') );

		    // Change list of attributes by ajax for image & color
		    add_action('wp_ajax_change_attribute_display_type', array(&$this,'change_attribute_display_type'));
		    add_action('wp_ajax_nopriv_change_attribute_display_type', array(&$this,'change_attribute_display_type'));

			parent::__construct();
		}


		public function epico_Admin_Scripts() {

	        //Include wpcolorpicker + its patch to support alpha chanel
	        wp_enqueue_style( 'wp-color-picker' );
	        wp_enqueue_script( 'wp-color-picker' );
	        wp_enqueue_script('colorpickerAlpha', EPICO_THEME_LIB_URI  .'/admin/scripts/wp-color-picker-alpha.js',array( 'wp-color-picker' ), '1.2.2');


	        wp_enqueue_script('media-upload');
	        wp_enqueue_script('thickbox');
	        wp_enqueue_style('thickbox');
		}

		/**
		 * update function.
		 *
		 * @see WP_Widget->update
		 * @access public
		 * @param array $new_instance
		 * @param array $old_instance
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = $old_instance;

			if ( empty( $new_instance['title'] ) ) {
				$new_instance['title'] = esc_html__( 'Filter by', 'vitrine' );
			}

			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['attribute'] = strip_tags( $new_instance['attribute'] );
			$instance['query_type'] = stripslashes( $new_instance['query_type'] );
			$instance['display_type'] = stripslashes( $new_instance['display_type'] );
			$instance['values'] = $new_instance['values'];//holds extra data (color/image)
			$instance['hide_text'] = $new_instance['hide_text'];

			return $instance;
		}

		/**
		 * form function.
		 *
		 * @see WP_Widget->form
		 * @access public
		 * @param array $instance
		 */
		public function form( $instance ) {
			$defaults = array(
				'title' 		=> '',
				'attribute' 	=> '',
				'display_type' 	=> 'list',
				'query_type'	=> 'and',
				'values' 		=> '', // hold values of images/colors
				'hide_text' 	=> false
			);

			$instance = wp_parse_args( (array) $instance, $defaults );

			 ?>
			<p>
				<label>
					<?php esc_html_e( 'Title', 'vitrine' ); ?><br />
					<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
				</label>
			</p>
			<p class="attribute_container">
	        	<label for="<?php echo esc_attr($this->get_field_id( 'attribute' )); ?>"><?php esc_html_e( 'Attribute', 'vitrine' ); ?></label>
	            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'attribute' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'attribute' ) ); ?>">
	                <?php
						$attribute_taxonomies = wc_get_attribute_taxonomies();
						$options = '';
						
						if ( $attribute_taxonomies ) {
							foreach ( $attribute_taxonomies as $tax ) {
								if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
									$options .= '<option name="' . esc_attr($tax->attribute_name) . '"' . selected( $tax->attribute_name, $instance['attribute'], false ) . '">' . esc_html($tax->attribute_name) . '</option>';
								}
							}
						}
					
						echo $options; //Sanitized in above lines!
					?>
	            </select>
	        </p>
			<p>
	        	<label for="<?php echo esc_attr( $this->get_field_id( 'query_type' ) ); ?>"><?php esc_html_e( 'Query Type', 'vitrine' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'query_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'query_type' ) ); ?>">
					<option value="and" <?php selected( $instance['query_type'], 'and' ); ?>><?php esc_html_e( 'AND', 'vitrine' ); ?></option>
					<option value="or" <?php selected( $instance['query_type'], 'or' ); ?>><?php esc_html_e( 'OR', 'vitrine' ); ?></option>
				</select>
			</p>
	        <p class="display_type_container">
	        	<label for="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>"><?php esc_html_e( 'Display Type', 'vitrine' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_type' ) ); ?>" data-id="<?php echo esc_attr($this->id); ?>">
					<option value="list" <?php selected( $instance['display_type'], 'list' ); ?>><?php esc_html_e( 'List', 'vitrine' ); ?></option>
					<option value="dropdown" <?php selected( $instance['display_type'], 'dropdown' ); ?>><?php esc_html_e( 'Dropdown', 'vitrine' ); ?></option>
	                <option value="color" <?php selected( $instance['display_type'], 'color' ); ?>><?php esc_html_e( 'Color', 'vitrine' ); ?></option>
	                <option value="image" <?php selected( $instance['display_type'], 'image' ); ?>><?php esc_html_e( 'Image', 'vitrine' ); ?></option>
				</select>
			</p>
			<p class="hide_text_container" style="<?php echo ($instance['display_type'] != 'image' ? 'display:none;':'') ?>">
				<label for="<?php echo esc_attr($this->get_field_id( 'hide_text' )); ?>"><?php esc_html_e('Show only images', 'vitrine') ?></label>
	            <input type="checkbox" id="<?php echo esc_attr($this->get_field_id( 'hide_text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'hide_text' )); ?>" <?php checked( $instance['hide_text'], 'on' ); ?> />
			</p>
			<div class="epico-widget-attributes-table">
				<span class="wc-loading hide"></span>
				<?php	
					$attribute = $instance['attribute'];
					$display_type = $instance['display_type'];
					$values = $instance['values'];
					echo $this->show_terms($attribute, $display_type, $values, $this->id, $this->id_base, $this->number);
				?>
			</div>

			<input type="hidden" name="w_id" value="<?php echo esc_attr($this->id); ?>"/>
			<input type="hidden" name="w_idbase" value="<?php echo esc_attr($this->id_base); ?>"/>
			<input type="hidden" name="w_number" value="<?php echo esc_attr($this->number); ?>"/>

			<input type="hidden" name="ajax_nonce" value="<?php echo wp_create_nonce('ajax_nonce'); ?>"/>
			<input type="hidden" name="widget_id" value="widget-<?php echo esc_attr( $this->id ); ?>-" />
			<input type="hidden" name="widget_name" value="widget-<?php echo esc_attr( $this->id_base ); ?>[<?php echo esc_attr( $this->number ); ?>]" />
	        <?php
		}

		public function show_terms($attribute, $display_type, $values, $id , $id_base, $number) {

			$terms = get_terms( 'pa_' . $attribute, array( 'hide_empty' => '0' ) );
			$output = '';		
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {


				if($display_type == 'color' || $display_type == 'image')
				{

					if(!isset($id) || !isset($id_base) || !isset($number))
					{
						return;
					}
					
					$output = sprintf( '<table><tr><th>%s</th><th></th></tr>', esc_html__( 'Terms', 'vitrine' ) );
					
					
					foreach ( $terms as $term ) {
						$name = 'widget-' . $id_base . '[' . $number . ']';
						$id = 'widget-' . $id . '-' . $term->term_id;
						
						$output .= '<tr>
							<td><label for="' . esc_attr( $id ) . '">' . esc_attr( $term->name ) . ' </label></td>';


						$output .= '<td>';

						$value = ( isset( $values[$term->slug] ) ? esc_attr( $values[$term->slug] ) : '' );

						if($display_type == 'color') {
							//reset value if display_type is changed from image to color
							if(substr( $value, 0, 1 ) !== "#")
							{
								$value = '';
							}

							$output .= '<div class="field color-field clear-after">
											<div class="color-field-wrap clear-after">
										        <input id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '[values][' . esc_attr( $term->slug ) . ']" type="text" value="' . esc_attr($value) . '" class="colorinput" placeholder="" />
											    <div class="color-view"></div>
											</div>
										</div>';
						
						}
						else
						{
							//reset value if display_type is changed from color to image
							if(substr( $value, 0, 1 ) === "#")
							{
								$value = '';
							}

							$output .= '<div class="field upload-field clear-after" data-title="' . esc_attr__('Upload Image', 'vitrine') . '" data-referer="ep-attr-image" >
							    			<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '[values][' . esc_attr( $term->slug ) . ']" value="' . esc_attr($value) . '" placeholder="" />
							   				<a href="#" class="upload-button">' . esc_html__('Browse', 'vitrine') . '</a>
										    <div class="upload-thumb ' . ( $value != '' ? 'show' : '').'">
										    	<div class="close"><span class="close-icon"></span></div>
										    	<img class="" src="' . esc_url($value) . '" alt="">
										    </div>
										</div>';

						}
						$output .= '</td></tr>';
					}

					$output .= '</table>';
					$output .= '<input type="hidden" name="' . esc_attr( $name ) . '[labels]" value="" />';

				}

			} else {
				if($display_type == 'color' || $display_type == 'image')
				{
					$output  = '<span class="no-term">' . esc_html__('No terms defined for this attribute','vitrine') . '</span>';
				}
			}
		
			return $output;
		}


		public function change_attribute_display_type() {

	        // check to see if the submitted nonce matches with the
	        // generated nonce we created earlier

			if(!isset($_POST['ajax_nonce']))
				die ( 'Failed!');

	        $nonce = $_POST['ajax_nonce'];

	        if ( ! wp_verify_nonce( $nonce, 'ajax_nonce' ) )
	           die ( 'Failed!');

			if(!isset($_POST['attribute']) || !isset($_POST['display_type']) || !isset($_POST['id']) || !isset($_POST['id_base']) || !isset($_POST['number']))
			{
				exit;
				return;
			}

	       	$attribute = $_POST['attribute'];
	        $display_type = $_POST['display_type'];
	        $id = $_POST['id'];
	        $id_base = $_POST['id_base'];
	        $number = $_POST['number'];

	        $output = '';

	        if($display_type == 'color' || $display_type == 'image' )
	        {
	        	$output = $this->show_terms($attribute, $display_type, '' , $id, $id_base, $number);
	        }
	        
	        echo $output;
	                
	        exit;

	    }

		/**
		 * widget function.
		 *
		 * @see WP_Widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {

			if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
				return;
			}

			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$taxonomy           = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : $this->settings['attribute']['std'];
			$query_type         = isset( $instance['query_type'] ) ? $instance['query_type'] : $this->settings['query_type']['std'];
			$display_type       = isset( $instance['display_type'] ) ? $instance['display_type'] : $this->settings['display_type']['std'];

			/* Epico codes */
			$hide_text = (isset( $instance['hide_text'] ) ? true : $this->settings['hide_text']['std']);
			$values = (isset( $instance['values'] ) ? $instance['values']  : array());
			/*End of Epico codes */

			if ( ! taxonomy_exists( $taxonomy ) ) {
				return;
			}

			$get_terms_args = array( 'hide_empty' => '1' );

			$orderby = wc_attribute_orderby( $taxonomy );

			switch ( $orderby ) {
				case 'name' :
					$get_terms_args['orderby']    = 'name';
					$get_terms_args['menu_order'] = false;
				break;
				case 'id' :
					$get_terms_args['orderby']    = 'id';
					$get_terms_args['order']      = 'ASC';
					$get_terms_args['menu_order'] = false;
				break;
				case 'menu_order' :
					$get_terms_args['menu_order'] = 'ASC';
				break;
			}

			$terms = get_terms( $taxonomy, $get_terms_args );

			if ( 0 === sizeof( $terms ) ) {
				return;
			}

			switch ( $orderby ) {
				case 'name_num' :
					usort( $terms, '_wc_get_product_terms_name_num_usort_callback' );
				break;
				case 'parent' :
					usort( $terms, '_wc_get_product_terms_parent_usort_callback' );
				break;
			}

			ob_start();

			$this->widget_start( $args, $instance );

			if ( 'dropdown' === $display_type ) {
				$found = $this->layered_nav_dropdown( $terms, $taxonomy, $query_type );
			}
			/* Epico codes*/
			elseif('color' == $display_type ) { 
				$found = $this->layered_nav_color( $terms, $taxonomy, $query_type, $values );
			} elseif('image' == $display_type) {
				$found = $this->layered_nav_image( $terms, $taxonomy, $query_type, $values, $hide_text  );
			}
			/* End of Epico codes*/
			else { 
				$found = $this->layered_nav_list( $terms, $taxonomy, $query_type );
			}

			$this->widget_end( $args );

			// Force found when option is selected - do not force found on taxonomy attributes
			if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
				$found = true;
			}

			if ( ! $found ) {
				ob_end_clean();
			} else {
				echo ob_get_clean();
			}

		}

		/**
		 * Return the currently viewed taxonomy name.
		 * @return string
		 */
		protected function get_current_taxonomy() {
			return is_tax() ? get_queried_object()->taxonomy : '';
		}

		/**
		 * Return the currently viewed term ID.
		 * @return int
		 */
		protected function get_current_term_id() {
			return absint( is_tax() ? get_queried_object()->term_id : 0 );
		}

		/**
		 * Return the currently viewed term slug.
		 * @return int
		 */
		protected function get_current_term_slug() {
			return absint( is_tax() ? get_queried_object()->slug : 0 );
		}

		/**
		 * Show dropdown layered nav.
		 * @param  array $terms
		 * @param  string $taxonomy
		 * @param  string $query_type
		 * @return bool Will nav display?
		 */
		protected function layered_nav_dropdown( $terms, $taxonomy, $query_type ) {
			$found = false;

			if ( $taxonomy !== $this->get_current_taxonomy() ) {
				$term_counts          = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
				$_chosen_attributes   = WC_Query::get_layered_nav_chosen_attributes();
				$taxonomy_filter_name = str_replace( 'pa_', '', $taxonomy );
				$taxonomy_label       = wc_attribute_label( $taxonomy );
				$any_label            = apply_filters( 'woocommerce_layered_nav_any_label', sprintf( esc_html__( 'Any %s', 'vitrine' ), $taxonomy_label ), $taxonomy_label, $taxonomy );


				echo '<select class="dropdown_layered_nav dropdown_layered_nav_' . esc_attr( $taxonomy_filter_name ) . '"  data-filtername="' . esc_attr( $taxonomy_filter_name ) . '">';
				echo '<option value="">' . esc_html( $any_label ) . '</option>';

				foreach ( $terms as $term ) {

					// If on a term page, skip that term in widget list
					if ( $term->term_id === $this->get_current_term_id() ) {
						continue;
					}

					// Get count based on current view
					$current_values    = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
					$option_is_set     = in_array( $term->slug, $current_values );
					$count             = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

					// Only show options with count > 0
					if ( 0 < $count ) {
						$found = true;
					} elseif ( 0 === $count && ! $option_is_set ) {
						continue;
					}

					echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( $option_is_set, true, false ) . '>' . esc_html( $term->name ) . '</option>';
				}

				echo '</select>';

				wc_enqueue_js( "
					jQuery( '.dropdown_layered_nav_" . esc_js( $taxonomy_filter_name ) . "' ).change( function() {
						var slug = jQuery( this ).val();
						location.href = '" . preg_replace( '%\/page\/[0-9]+%', '', str_replace( array( '&amp;', '%2C' ), array( '&', ',' ), esc_js( add_query_arg( 'filtering', '1', remove_query_arg( array( 'page', 'filter_' . $taxonomy_filter_name ) ) ) ) ) ) . "&filter_" . esc_js( $taxonomy_filter_name ) . "=' + slug;
					});
				" );
			}

			return $found;
		}

		/**
		 * Get current page URL for layered nav items.
		 * @return string
		 */
		protected function get_page_base_url( $taxonomy ) {

			if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
				$link = home_url();
			} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
				$link = get_post_type_archive_link( 'product' );
			} elseif ( is_product_category() ) {
				$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
			} elseif ( is_product_tag() ) {
				$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
			} else {
				$queried_object = get_queried_object();
				$link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			}

			// Min/Max
			if ( isset( $_GET['min_price'] ) ) {
				$link = add_query_arg( 'min_price', wc_clean( $_GET['min_price'] ), $link );
			}

			if ( isset( $_GET['max_price'] ) ) {
				$link = add_query_arg( 'max_price', wc_clean( $_GET['max_price'] ), $link );
			}

			// Orderby
			if ( isset( $_GET['orderby'] ) ) {
				$link = add_query_arg( 'orderby', wc_clean( $_GET['orderby'] ), $link );
			}

			/**
			 * Search Arg.
			 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
			 */
			if ( get_search_query() ) {
				$link = add_query_arg( 's', rawurlencode( htmlspecialchars_decode( get_search_query() ) ), $link );
			}

			// Post Type Arg
			if ( isset( $_GET['post_type'] ) ) {
				$link = add_query_arg( 'post_type', wc_clean( $_GET['post_type'] ), $link );
			}

			// Min Rating Arg
			if ( isset( $_GET['rating_filter'] ) ) {
				$link = add_query_arg( 'rating_filter', wc_clean( $_GET['rating_filter'] ), $link );
			}

			/***** Epicomedia codes *****/
			// On Sale Arg
			if ( isset( $_GET['status'] ) && $_GET['status'] == 'sale') {
				$link = add_query_arg( 'status', wc_clean($_GET['status']), $link );
			}
			//In stock Arg
			if ( isset( $_GET['availability'] ) && $_GET['availability'] == 'in_stock') {
				$link = add_query_arg( 'availability', wc_clean($_GET['availability']), $link );
			}
			/***** End of Epicomedia codes *****/

			// All current filters
			if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) {

				foreach ( $_chosen_attributes as $name => $data ) {
					if ( $name === $taxonomy ) {
						continue;
					}
					$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );
					if ( ! empty( $data['terms'] ) ) {
						$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
					}
					if ( 'or' == $data['query_type'] ) {
						$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
					}
				}
			}

			return $link;
		}

		/**
		 * Count products within certain terms, taking the main WP query into consideration.
		 *
		 * @param  array  $term_ids
		 * @param  string $taxonomy
		 * @param  string $query_type
		 * @return array
		 */
		protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
			global $wpdb;

			$tax_query  = WC_Query::get_main_tax_query();
			$meta_query = WC_Query::get_main_meta_query();

			if ( 'or' === $query_type ) {
				foreach ( $tax_query as $key => $query ) {
					if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
						unset( $tax_query[ $key ] );
					}
				}
			}

			$meta_query      = new WP_Meta_Query( $meta_query );
			$tax_query       = new WP_Tax_Query( $tax_query );
			$meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );

			// Generate query
			$query           = array();
			$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
			$query['from']   = "FROM {$wpdb->posts}";
			$query['join']   = "
				INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
				INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
				INNER JOIN {$wpdb->terms} AS terms USING( term_id )
				" . $tax_query_sql['join'] . $meta_query_sql['join'];

			$query['where']   = "
				WHERE {$wpdb->posts}.post_type IN ( 'product' )
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . "
				AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
			";

			if ( $search = WC_Query::get_main_search_query_sql() ) {
				$query['where'] .= ' AND ' . $search;
			}

			$query['group_by'] = "GROUP BY terms.term_id";
			$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
			$query             = implode( ' ', $query );
			$results           = $wpdb->get_results( $query );

			return wp_list_pluck( $results, 'term_count', 'term_count_id' );
		}

		/**
		 * Show list based layered nav.
		 * @param  array  $terms
		 * @param  string $taxonomy
		 * @param  string $query_type
		 * @return bool   Will nav display?
		 */
		protected function layered_nav_list( $terms, $taxonomy, $query_type ) {
			// List display
			echo '<ul>';

			$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$found              = false;

			foreach ( $terms as $term ) {
				$current_values    = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
				$option_is_set     = in_array( $term->slug, $current_values );
				$count             = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

				// skip the term for the current archive
				if ( $this->get_current_term_id() === $term->term_id ) {
					continue;
				}

				// Only show options with count > 0
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 0 === $count && ! $option_is_set ) {
					continue;
				}

				$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) );
				$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( $_GET[ $filter_name ] ) ) : array();
				$current_filter = array_map( 'sanitize_title', $current_filter );

				if ( ! in_array( $term->slug, $current_filter ) ) {
					$current_filter[] = $term->slug;
				}

				$link = $this->get_page_base_url( $taxonomy );

				// Add current filters to URL.
				foreach ( $current_filter as $key => $value ) {
					// Exclude query arg for current term archive term
					if ( $value === $this->get_current_term_slug() ) {
						unset( $current_filter[ $key ] );
					}

					// Exclude self so filter can be unset on click.
					if ( $option_is_set && $value === $term->slug ) {
						unset( $current_filter[ $key ] );
					}
				}

				if ( ! empty( $current_filter ) ) {
					$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

					// Add Query type Arg to URL
					if ( $query_type === 'or' && ! ( 1 === sizeof( $current_filter ) && $option_is_set ) ) {
						$link = add_query_arg( 'query_type_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) ), 'or', $link );
					}
				}



				if ( $count > 0 || $option_is_set ) {
					$link      = esc_url( apply_filters( 'woocommerce_layered_nav_link', $link, $term, $taxonomy ) );
					$term_html = '<a href="' . esc_url($link) . '">' . esc_html( $term->name ) . '</a>';
				} else {
					$link      = false;
					$term_html = '<span>' . esc_html( $term->name ) . '</span>';
				}

				$term_html .= ' ' . apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term );

				echo '<li class="wc-layered-nav-term ' . ( $option_is_set ? 'chosen' : '' ) . '">';
				echo wp_kses_post( apply_filters( 'woocommerce_layered_nav_term_html', $term_html, $term, $link, $count ) );
				echo '</li>';

			}

			echo '</ul>';

			return $found;
		}

		/******* Epico codes ********/

		/**
		 * Show color list layered nav.
		 * @param  array $terms
		 * @param  string $taxonomy
		 * @param  string $query_type
		 * @param  array $values
		 * @return bool Will nav display?
		 */
		protected function layered_nav_color($terms, $taxonomy, $query_type, $values ) {

			$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$found              = false;

			echo '<ul class="colorlist">';

			foreach ( $terms as $term ) {

				$current_values    = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
				$option_is_set     = in_array( $term->slug, $current_values );
				$count             = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

				// skip the term for the current archive
				if ( $this->get_current_term_id() === $term->term_id ) {
					continue;
				}

				// Only show options with count > 0
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 'and' === $query_type && 0 === $count && ! $option_is_set ) {
					continue;
				}

				$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) );
				$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( $_GET[ $filter_name ] ) ) : array();
				$current_filter = array_map( 'sanitize_title', $current_filter );

				if ( ! in_array( $term->slug, $current_filter ) ) {
					$current_filter[] = $term->slug;
				}

				$link = $this->get_page_base_url( $taxonomy );


				// Add current filters to URL.
				foreach ( $current_filter as $key => $value ) {
					// Exclude query arg for current term archive term
					if ( $value === $this->get_current_term_slug() ) {
						unset( $current_filter[ $key ] );
					}

					// Exclude self so filter can be unset on click.
					if ( $option_is_set && $value === $term->slug ) {
						unset( $current_filter[ $key ] );
					}
				}

				if ( ! empty( $current_filter ) ) {
					$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

					// Add Query type Arg to URL
					if ( $query_type === 'or' && ! ( 1 === sizeof( $current_filter ) && $option_is_set ) ) {
						$link = add_query_arg( 'query_type_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) ), 'or', $link );
					}
				}

				$color = isset( $values[$term->slug] ) ? $values[$term->slug] : '';

				if($color)
				{


					if ( $count > 0 || $option_is_set ) {
						$link      = esc_url( apply_filters( 'woocommerce_layered_nav_link', $link, $term, $taxonomy ) );
						$term_html = '<a href="' . esc_url($link) . '">' . '<span class="color" style="background-color:' . esc_attr( $color ) . ';" ></span>' . esc_html( $term->name ) . '</a>';
					} else {
						$link      = false;
						$term_html = '<span class="color" style="background-color:' . esc_attr( $color ) . ';" ></span>' . esc_html( $term->name );
					}

					$term_html .= ' ' . apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term );

					echo '<li class="' . ( $option_is_set ? 'chosen' : '' ) . '">';
					echo wp_kses_post( apply_filters( 'woocommerce_layered_nav_term_html', $term_html, $term, $link, $count ) );
					echo '</li>';


				}
			}

			echo '</ul>';

			return $found;

		}


		/**
		 * Show image list layered nav.
		 * @param  array $terms
		 * @param  string $taxonomy
		 * @param  string $query_type
		 * @param  array $values
		 * @param  bool $hide_text
		 * @return bool Will nav display?
		 */
		protected function layered_nav_image($terms, $taxonomy, $query_type, $values, $hide_text) {

			$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$found              = false;

			echo '<ul class="imagelist">';

			foreach ( $terms as $term ) {

				$current_values    = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
				$option_is_set     = in_array( $term->slug, $current_values );
				$count             = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

				// skip the term for the current archive
				if ( $this->get_current_term_id() === $term->term_id ) {
					continue;
				}

				// Only show options with count > 0
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 'and' === $query_type && 0 === $count && ! $option_is_set ) {
					continue;
				}

				$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) );
				$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( $_GET[ $filter_name ] ) ) : array();
				$current_filter = array_map( 'sanitize_title', $current_filter );

				if ( ! in_array( $term->slug, $current_filter ) ) {
					$current_filter[] = $term->slug;
				}

				$link = $this->get_page_base_url( $taxonomy );


				// Add current filters to URL.
				foreach ( $current_filter as $key => $value ) {
					// Exclude query arg for current term archive term
					if ( $value === $this->get_current_term_slug() ) {
						unset( $current_filter[ $key ] );
					}

					// Exclude self so filter can be unset on click.
					if ( $option_is_set && $value === $term->slug ) {
						unset( $current_filter[ $key ] );
					}
				}

				if ( ! empty( $current_filter ) ) {
					$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

					// Add Query type Arg to URL
					if ( $query_type === 'or' && ! ( 1 === sizeof( $current_filter ) && $option_is_set ) ) {
						$link = add_query_arg( 'query_type_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) ), 'or', $link );
					}
				}

				$image = isset( $values[$term->slug] ) ? $values[$term->slug] : '';

				if($image)
				{

					if ( $count > 0 || $option_is_set ) {
						$link      = esc_url( apply_filters( 'woocommerce_layered_nav_link', $link, $term, $taxonomy ) );
						$term_html = '<a href="' . esc_url($link) . '">' . '<img class="image" title="' . esc_attr($term->name) .'" alt="' . esc_attr($term->name) .'" src="' . esc_url( $image ) . '" >' . ($hide_text == false ? esc_html( $term->name ):'') . '</a>';
					} else {
						$link      = false;
						$term_html = '<img class="image" title="' . esc_attr($term->name) .'" alt="' . esc_attr($term->name) .'" src="' . esc_url( $image ) . '" >' . ($hide_text == false ? esc_html( $term->name ):'');
					}

					echo '<li class="' . ( $option_is_set ? 'chosen' : '' ) . '">';
					echo wp_kses_post( apply_filters( 'woocommerce_layered_nav_term_html', $term_html, $term, $link ) );
					echo '</li>';

				}

			}

			echo '</ul>';

			return $found;

		}

		/******* End of Epico codes ********/
	}

	add_action( 'widgets_init', 'epico_override_woocommerce_layered_Nav_widget', 25 );
	 
	function epico_override_woocommerce_layered_Nav_widget() {
	 	// Remove default widget
	  	if ( class_exists( 'WC_Widget_Layered_Nav' ) ) {
	    	unregister_widget( 'WC_Widget_Layered_Nav' );
	 	}
	    register_widget( 'epico_Advanced_WC_Widget_Layered_Nav' );
	}

}
