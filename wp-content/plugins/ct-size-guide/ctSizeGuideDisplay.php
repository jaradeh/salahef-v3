<?php

/**
 * Displays the size guide
 * @author jacek
 */
class ctSizeGuideDisplay {

	protected $tables;
	protected $sg_id;

	/**
	 * Initializes object
	 */

	public function __construct() {
		add_action( 'woocommerce_before_single_product', array( $this, 'displaySizeGuide' ), 70 );

		add_action( 'add_meta_boxes', array( $this, 'chooseSizeGuide' ) );

		add_action( 'save_post', array( $this, 'saveSizeGuideDropdown' ) );
		add_action( 'edit_post', array( $this, 'saveSizeGuideDropdown' ) );

		add_shortcode( 'ct_size_guide', array( $this, 'triggerSizeGuide' ) );

		add_action( 'wp_footer', array( $this, 'overlayColor' ) );
	}

	/**
	 * Size Guide MetaBox
	 */

	public function chooseSizeGuide() {
		add_meta_box( 'ct_sizeguideopt', __( 'Choose size guide', 'ct_sgp' ), array(
			$this,
			'renderSizeGuideDropdown'
		), 'product', 'side' );
	}

	/**
	 * Select size guide per product
	 *
	 * @param $post
	 */

	public function renderSizeGuideDropdown( $post ) {
		$args = array(
			'post_type'   => 'ct_size_guide',
			'numberposts' => - 1,
			'orderby'     => 'title',
			'order'       => "ASC"
		);

		$sg_list = get_posts( $args );

		$current = get_current_screen()->action;

		//we have to check if there is already size guide post attached to the product
		$post_id = $post->ID;

		$sg_post_id = get_post_meta( $post_id, '_ct_selectsizeguide' );

		$sg_post_id = isset( $sg_post_id[0] ) ? $sg_post_id[0] : '';

		echo '<select id="ct_selectsizeguide" name="ct_selectsizeguide"><br>';
		echo '<option value="#NONE#">— Select —</option>';
		foreach ( $sg_list as $sg_object ) {
			$sg_id    = $sg_object->ID;
			$sg_title = $sg_object->post_title;

			echo '<option value="' . $sg_id . '"' . selected( $sg_post_id, $sg_id ) . '>' . $sg_title . '</option>';
		}
		echo '</select>';

		echo '<br><br><input type="checkbox" name="ct_disablesizeguide" id="ct_disablesizeguide" ' . checked( 'checked', $sg_post_id, false ) . '>Hide size guide from this product</input>';
	}

	/**
	 * Save size guide per product
	 *
	 * @param $post_id
	 */

	public function saveSizeGuideDropdown( $post_id ) {
		$slug = 'product';
		// If this isn't a 'book' post, don't update it.
		if ( ! isset( $_POST['post_type'] ) || $slug != $_POST['post_type'] ) {
			return;
		}

		if ( isset( $_POST['ct_selectsizeguide'] ) ) {
			$disablesg = isset( $_POST['ct_disablesizeguide'] ) ? 'on' : '';
			if ( $disablesg == 'on' ) {
				$disablesg = 'checked';
				update_post_meta( $post_id, '_ct_selectsizeguide', $disablesg );
			} else {
				$selectedsg = $_POST['ct_selectsizeguide'];
				update_post_meta( $post_id, '_ct_selectsizeguide', $selectedsg );

			}
		}
	}

	protected function hasChild( $id, $terms ) {

		foreach ( $terms as $term ) {
			if ( $id === $term->parent ) {
				return $term->term_id;
			}

		}

		return false;
	}

	/**
	 * Display size guide
	 *
	 * @param null $post_id
	 */

	public function displaySizeGuide( $post_id = null ) {

		$post_id = $post_id ? $post_id : get_the_ID();

		$sg_post_id = get_post_meta( $post_id, '_ct_selectsizeguide' );
		if ( $sg_post_id && $sg_post_id[0] != '#NONE#' ) {
			$sg_post_id  = $sg_post_id[0];
			$this->sg_id = $sg_post_id;
			$size_table  = get_post_meta( $sg_post_id, '_ct_sizeguide' );
			if ( $size_table ) {
				$size_table   = $size_table[0];
				$this->tables = $size_table;
				$this->handleShortcodeRender( $size_table, $sg_post_id );
			}
		} else {

			is_object( $post_id ) ? $post_id = $post_id->ID : '';

			$terms = wp_get_post_terms( $post_id, 'product_cat' );


			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( $child_id = $this->hasChild( $term->term_id, $terms ) ) {
						$sg_post_id_candidate = get_woocommerce_term_meta( $child_id, '_ct_assignsizeguide' );
						if ( $sg_post_id_candidate != '' && get_post( $sg_post_id_candidate ) ) {
							$sg_post_id = $sg_post_id_candidate;
							break;
						}
					} else {
						$sg_post_id_candidate = get_woocommerce_term_meta( $term->term_id, '_ct_assignsizeguide' );
						if ( $sg_post_id_candidate != '' && get_post( $sg_post_id_candidate ) ) {
							$sg_post_id = $sg_post_id_candidate;
						}
					}
				}

			}

			if ( $sg_post_id ) {
				$this->sg_id = $sg_post_id;
				$size_table  = get_post_meta( $sg_post_id, '_ct_sizeguide' );
				if ( $size_table ) {
					$size_table   = $size_table[0];
					$this->tables = $size_table;
					$this->handleShortcodeRender( $size_table, $sg_post_id );
				}
			}
		}
	}

	/**
	 * Render shortcode
	 */

	protected function handleShortcodeRender( $size_table, $sg_post_id ) {
		switch ( $this->getSgOption( "wc_size_guide_button_position" ) ) {

			case 'ct-position-tab':
				add_filter( 'woocommerce_product_tabs', array( $this, 'addSizeGuideTab' ) );
				break;

			case 'ct-position-add-to-cart':
				add_action( 'woocommerce_after_add_to_cart_button', array(
					$this,
					'doSgShortcode'
				), $this->getSgOption( 'wc_size_guide_button_priority', 60 ) );
				break;

			case 'ct-position-price':
				add_filter( 'woocommerce_get_price_html', array(
					$this,
					'addToPrice'
				), $this->getSgOption( 'wc_size_guide_button_priority', 60 ) );
				break;

			case 'ct-position-info':
				add_action( 'woocommerce_before_add_to_cart_button', array(
					$this,
					'doBfrAddShortcode'
				), $this->getSgOption( 'wc_size_guide_button_priority', 60 ) );
				break;
		}
		$this->renderSizeGuideTableOutput( $size_table, $sg_post_id );
	}

	/**
	 * Add Size Guide under the price tag
	 *
	 * @param $quantity
	 *
	 * @return string
	 */
	public function addToquantity( $quantity ) {
		return $quantity . '<br><br>' . do_shortcode( '[ct_size_guide]' );
	}


	/**
	 * Add Size Guide under the price tag
	 *
	 * @param $price
	 *
	 * @return string
	 */
	public function addToPrice( $price ) {

		return $price . '<br><br>' . do_shortcode( '[ct_size_guide]' );
	}

	/**
	 * WooCommerce custom tab
	 *
	 * @param $tabs
	 *
	 * @return mixed
	 */

	public function addSizeGuideTab( $tabs ) {

		$tabs['size_guide'] = array(
			'title'    => $this->getSgOption( "wc_size_guide_button_label", "Size Guide" ),
			'priority' => $this->getSgOption( 'wc_size_guide_button_priority', 20 ),
			'callback' => array( $this, 'renderSizeGuideTab' )
		);

		return $tabs;

	}

	/**
	 * Render WooCommerce tab
	 */

	public function renderSizeGuideTab() {
		$this->renderSizeGuideTableOutput( $this->tables, $this->sg_id, true );
	}

	public function renderSizeGuideTableOutput( $tables, $post_id, $is_tab = false ) {
		$sg_object  = get_post( $post_id );
		$sg_title   = $sg_object->post_title;
		$sg_content = $sg_object->post_content;
		if ( $this->getSgOption( 'wc_size_guide_button_position', 'ct-position-summary' ) == 'ct-position-summary' ) {
			add_action( 'woocommerce_single_product_summary', array(
				$this,
				'doSgShortcode'
			), $this->getSgOption( 'wc_size_guide_button_priority', 60 ) );
		}
		if ( $is_tab ) {
			echo '<div id="ct_size_guide-'.$this->sg_id.'" class="ct-size-guide sg ct_sg_tabbed">';
		} else {
			$pleft   = $this->getSgOption( 'wc_size_guide_modal_padding_left', 0 );
			$ptop    = $this->getSgOption( 'wc_size_guide_modal_padding_top', 0 );
			$pright  = $this->getSgOption( 'wc_size_guide_modal_padding_right', 0 );
			$pbottom = $this->getSgOption( 'wc_size_guide_modal_padding_bottom', 0 );

			$paddings = '';

			if ( $pleft > 0 ) {
				$paddings .= 'padding-left: ' . (int) $pleft . 'px; ';
			}
			if ( $ptop > 0 ) {
				$paddings .= 'padding-top: ' . (int) $ptop . 'px; ';
			}
			if ( $pright > 0 ) {
				$paddings .= 'padding-right: ' . (int) $pright . 'px; ';
			}
			if ( $pbottom > 0 ) {
				$paddings .= 'padding-bottom: ' . (int) $pbottom . 'px; ';
			}
			echo '<div id="ct_size_guide-'. $this->sg_id .'" style="' . $paddings . '" class="ct-size-guide sg mfp-hide">';
		}

		echo '<h2 class="sg_title">' . $sg_title . '</h2><hr>';
		echo '<div class="sg_content"> ' . do_shortcode( $sg_content ) . '</div>';
		/*echo '<div class="sg_content">';
		print_r($sg_content);
		echo '</div>';*/
		echo '<div style="clear:both;"></div>';
		echo '<hr>';
		$c = count( $tables );

		foreach ( $tables as $key => $table ) {
			if ( ! empty( $table['title'] ) ) {
				echo '<h4 class="ct_table_title">' . do_shortcode( $table['title'] ) . '</h4>';
			}
			$responsive_class = $this->getSgOption( 'wc_size_guide_display_mobile_table', 'ct-size-guide--Responsive' );
			$hovers           = $this->getSgOption( 'wc_size_guide_hovers_on_tables', 'no' );

			echo '<div class="ct_table_container">';

			if ( $hovers === 'yes' ) {

				echo '<table class="' . $responsive_class . ' ct-table-hovers">';
			} else {
				echo '<table class="' . $responsive_class . '">';
			}


			$row_mark = 1;

			foreach ( $table['table'] as $row ) {
				$col_mark = 1;
				echo '<tr>';
				foreach ( $row as $cell ) {
					if ( $row_mark == 1 || $col_mark == 1 ) {
						echo '<th>' . $cell . '</th>';
					} else {
						echo '<td>';
						echo $cell;
						echo '</td>';
					}
					$col_mark ++;
				}
				$row_mark ++;
				echo '</tr>';
			}
			echo '</table>';
			echo '</div>';
			if ( $key < $c - 1 ) {
				echo '<hr>';
			}
		}

		if ( ! empty( $tables[0]['caption'] ) ) {
			echo '<br><p class="ct_table_caption">' . do_shortcode( $tables[0]['caption'] ) . '</p>';
		}
		echo '</div>';

	}

	public function triggerSizeGuide() {

		global $product;

		if ( ! $product ) {
			return '';
		}

		$productStock        = $product->get_stock_quantity();
		$productAvailability = $product->get_availability();
		$hide                = get_option( 'wc_size_guide_hide' );
		$output              = '';

		if ( $hide != 'yes' || $productAvailability['class'] != 'out-of-stock' ) {
			$trigger = $this->getSgOption( 'wc_size_guide_button_style', 'ct-trigger-button' );


			$align = $this->getSgOption( 'wc_size_guide_button_align', 'left' );
			if ( $this->getSgOption( 'wc_size_guide_button_position' ) == 'ct-position-add-to-cart' ) {
				$align = '';
			}
			$clear = $this->getSgOption( 'wc_size_guide_button_clear', 'no' );

			$mleft   = $this->getSgOption( 'wc_size_guide_button_margin_left', 0 );
			$mtop    = $this->getSgOption( 'wc_size_guide_button_margin_top', 0 );
			$mright  = $this->getSgOption( 'wc_size_guide_button_margin_right', 0 );
			$mbottom = $this->getSgOption( 'wc_size_guide_button_margin_bottom', 0 );

			$margins = '';

			if ( $mleft != 0 ) {
				$margins .= 'margin-left: ' . (int) $mleft . 'px; ';
			}
			if ( $mtop != 0 ) {
				$margins .= 'margin-top: ' . (int) $mtop . 'px; ';
			}
			if ( $mright != 0 ) {
				$margins .= 'margin-right: ' . (int) $mright . 'px; ';
			}
			if ( $mbottom != 0 ) {
				$margins .= 'margin-bottom: ' . (int) $mbottom . 'px; ';
			}

			if ( $trigger == 'ct-trigger-button' ) {


				$output = '<a class="open-popup-link ' . $this->getSgOption( 'wc_size_guide_button_class', 'button_sg' ) . '" href="#ct_size_guide-'.$this->sg_id.'" style="float: ' . $align . '; ' . $margins . '">' . $this->getSgOption( "wc_size_guide_button_label", esc_html__( "Size Guide", "ct_sgp" ) ) . '</a>';
			} else {
				$output = '<a class="open-popup-link" href="#ct_size_guide-'.$this->sg_id.'" style="float: ' . $align . '; ' . $margins . '">' . $this->getSgOption( "wc_size_guide_button_label", esc_html__( "Size Guide", "ct_sgp" ) ) . '</a>';
			}
			if ( $clear == 'no' ) {
				$output .= '<div class="clearfix"></div>';
			}
		}

		return $output;
	}

	protected function getSgOption( $opt, $default = "null" ) {
		$val = get_post_meta( $this->sg_id, '_ct_sizeguidesettings' );

		if ( $val ) {
			$val = $val[0];
		}

		// check if global value
		if (

			// margin dropdown
			( false !== strpos( $opt, 'wc_size_guide_button_margin_' ) &&
			  isset( $val['wc_size_guide_button_margins_dropdown'] ) &&
			  $val['wc_size_guide_button_margins_dropdown'] === 'global'
			) ||

			// padding dropdown
			(
				false !== strpos( $opt, 'wc_size_guide_modal_padding_' ) &&
				isset( $val['wc_size_guide_modal_padding_dropdown'] ) &&
				$val['wc_size_guide_modal_padding_dropdown'] === 'global'
			) ||

			// simple input global value
			(
				isset( $val[ $opt ] ) &&
				$val[ $opt ] == 'global' ||
				! $val
			)
			|| ( $opt === 'wc_size_guide_display_mobile_table' )
			|| ( $opt === 'wc_size_guide_hovers_on_tables' )
			|| ( $opt === 'wc_size_guide_active_hover_color' )
			|| ( $opt === 'wc_size_guide_lines_hover_color' )
		) {

			// get global value
			$val = get_option( $opt, $default );

			/** WPML support for global SG options */
			if ( function_exists( 'icl_register_string' ) ) {
				icl_register_string( 'ct_sgp', 'Size Guide option: ' . $opt, $val );
			}

			$val = apply_filters( 'wpml_translate_single_string', $val, 'ct_sgp', 'Size Guide option: ' . $opt );
			/** End WPML */

			// individual value
		} elseif ( isset( $val[ $opt ] ) && $val[ $opt ] != "" ) {

			$val = $val[ $opt ];

			// nothing found
		} else {

			$val = $default;

		}

		return $val;
	}


	public function overlayColor() {
		echo '<style>.mfp-bg{background:' . $this->getSgOption( 'wc_size_guide_overlay_color', '#000000' ) . ';}
					.mfp-content .ct_table_container .ct-table-hover{background: ' . $this->getSgOption( 'wc_size_guide_lines_hover_color', '#999999' ) . '; }
					.mfp-content .ct_table_container .ct-table-cursor{background: ' . $this->getSgOption( 'wc_size_guide_active_hover_color', '#2C72AD' ) . ';
					 color: #FFFFFF; }
					</style>';
	}

	public function doBfrAddShortcode() {
		echo do_shortcode( '[ct_size_guide]' . '<br>' );
	}

	public function doSgShortcode() {
		echo do_shortcode( '[ct_size_guide]' );
	}

}

new ctSizeGuideDisplay();