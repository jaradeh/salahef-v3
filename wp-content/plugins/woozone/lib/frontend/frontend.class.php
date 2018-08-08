<?php
/*
* Define class WooZoneFrontend
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;
   
if (class_exists('WooZoneFrontend') != true) {
	class WooZoneFrontend
	{
		const VERSION = '1.0';
		
		public $the_plugin 		= null;
		public $is_admin		= null;

		public $amz_settings = array();
		public $countryflags_aslink = false;

		static protected $_instance;

		public $alias;
		public $localizationName;

		private $current_theme = null;

		private $woo_tab_data = false;

		public $p_type = null;
		public $product_buy_is_amazon_url = null;
		public $product_url_short = null;

		private $syncfront_args = array();
		private $sync_options = array();
		private $sync_settings = array();

		private static $sql_chunk_limit = 2000;

		// synchronization on frontend is activated?
		public $syncfront_activate = 'no';


		public function __construct( $parent )
		{
			$this->the_plugin = $parent;
			$this->is_admin = $this->the_plugin->is_admin;
			
			$this->amz_settings = $this->the_plugin->amz_settings;

			$this->alias = $this->the_plugin->alias;
			$this->localizationName = $this->the_plugin->localizationName;

			$this->p_type = $this->the_plugin->p_type;
			$this->product_buy_is_amazon_url = $this->the_plugin->product_buy_is_amazon_url;
			$this->product_url_short = $this->the_plugin->product_url_short;

			$this->countryflags_aslink = isset($this->amz_settings['product_countries_countryflags'])
				&& $this->amz_settings['product_countries_countryflags'] == "yes" ? true : false;
			
			$this->current_theme = wp_get_theme(); //get_current_theme() - deprecated notice!
			//var_dump('<pre>',$this->current_theme,'</pre>');

			// sync options & settings
			$this->init_sync_settings();
			$this->init_sync_options();

			$this->syncfront_activate = isset($this->sync_options['syncfront_activate'])
				? (string) $this->sync_options['syncfront_activate'] : 'no';
			//$this->syncfront_activate = 'no'; //DEBUG SYNC

			// wp actions - frontend
			if ( ! $this->is_admin ) {

				add_action( 'init' , array( $this, 'init' ) );

				// cross sell shortcode
				add_shortcode( 'amz_corss_sell', array($this, 'cross_sell_box') );
			}

			// executed only on frontend
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
			
			// woocommerce fix thumb for remote images with https - on frontend
			add_action( 'woocommerce_before_mini_cart', array( $this, 'woocommerce_before_mini_cart' ) );

			// wp ajax actions
			add_action('wp_ajax_WooZone_frontend', array( $this, 'ajax_requests') );
			add_action('wp_ajax_nopriv_WooZone_frontend', array( $this, 'ajax_requests') );

			// checkout email: wp ajax actions
			if ( 'simple' == $this->p_type ) {
				if ( isset($this->amz_settings['checkout_email']) && $this->amz_settings['checkout_email'] == 'yes' ) {
					add_action( 'wp_ajax_WooZone_before_user_checkout', array( $this, 'woocommerce_ajax_before_user_checkout') );
					add_action( 'wp_ajax_nopriv_WooZone_before_user_checkout', array( $this, 'woocommerce_ajax_before_user_checkout') );
				}
			}
			
			// cross sell checkout - !needs to be bellow Amazon helper
			$this->cross_sell_checkout();

			// 2018-jan : make bitly request to retrieve product short url
			add_action( 'wp', array( $this, 'action_do_bitly_request' ), 10, 1 );
			add_action( 'wp', array( $this, 'action_do_product_page' ), 11, 1 );


			//:: GDPR update
			add_action( 'shutdown', array( $this, 'session_check' ), 0 );


			//:: Badges / Flags
			// removed from 3.0, used in older versions of woocommerce as 2.X
			add_filter('woocommerce_single_product_image_html', array( $this, 'badges_show_onproduct' ), 999, 2);
			add_filter('woocommerce_single_product_image_thumbnail_html', array( $this, 'badges_show_onproduct_thumbnail' ), 999, 2);
			//add_filter('post_thumbnail_html', array( $this, 'badges_show_onproduct' ), 9999, 2);

			// woocommerce default onsale badge
            add_filter( 'woocommerce_sale_flash', array( $this, 'woocommerce_sale_flash' ), 10, 3 );
		}

		// Singleton pattern
		static public function getInstance( $parent )
		{
			if (!self::$_instance) {
				self::$_instance = new self($parent);
			}
			
			return self::$_instance;
		}

		public function session_check() {

			//if ( 0 ) { //DEBUG
			if ( 'yes' == $this->the_plugin->gdpr_rules_is_activated ) {
				$used_sessions = array(
					'WooZone_wizard',
					'WooZone_sync',
					'WooZone_country',
					'WooZone',
					'AmzStore_country',
				);

				if( count($used_sessions) ){
					foreach ( $used_sessions as $key) {
						unset( $_SESSION[$key] );
					}
				}
			}

			//$tmp = (json_encode($_SESSION));

			$html = array();
			$html[] = '<h2>' . 'SESSION:' . '</h2>';
			ob_start();
			echo '<pre>';
			print_r( $_SESSION );
			echo '</pre>';
			$html[] = ob_get_clean();

			$html[] = '<h2>' . 'COOKIES:' . '</h2>';
			ob_start();
			echo '<pre>';
			print_r( $_COOKIE );
			echo '</pre>';
			$html[] = ob_get_clean();

			$html = implode( PHP_EOL, $html );

			if ( ! is_admin() ) {
				WooZone_debugbar()->add2bar_row( 'woozone-debugbar-session-check', $html, array() );
			}
		}



		//====================================================
		//== MAIN METHODS

		/**
		 * Inits...
		 */
		// wp enqueue scripts & stypes
		public function wp_enqueue_scripts() {

			if( !wp_style_is($this->alias . '-frontend-style') ) {
				wp_enqueue_style( $this->alias . '-frontend-style', WooZone_asset_path( 'css', $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'lib/frontend/css/frontend.css', true ), array(), WooZone_asset_version( 'css' ) );
			}
			
			if( !wp_script_is($this->alias . '-frontend-script') ) {
				wp_enqueue_script( $this->alias . '-frontend-script' , WooZone_asset_path( 'js', $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'lib/frontend/js/frontend.js', true ), array( 'jquery' ), WooZone_asset_version( 'js' ) );

				$_checkout_url = wc_get_checkout_url();
				$_checkout_url = is_string($_checkout_url) ? esc_url( $_checkout_url ) : '';

				$vars = array(
					'ajax_url'				=> admin_url('admin-ajax.php'),
					'checkout_url' 		=> $_checkout_url,
					'lang' 					=> array(
						'loading'								=> WooZone()->_translate_string( 'Loading...' ),
						'closing'                   			=> WooZone()->_translate_string( 'Closing...' ),
						'saving'                   				=> WooZone()->_translate_string( 'Saving...' ),
						'amzcart_checkout'       				=> WooZone()->_translate_string( 'checkout done' ),
						'amzcart_cancel' 						=> WooZone()->_translate_string( 'canceled' ),
						'amzcart_checkout_msg'					=> WooZone()->_translate_string( 'all good' ),
						'amzcart_cancel_msg'					=> WooZone()->_translate_string( 'You must check or cancel all amazon shops!' ),
						'available_yes'							=> WooZone()->_translate_string( 'available' ),
						'available_no' 							=> WooZone()->_translate_string( 'not available' ),
						'load_cross_sell_box'					=> WooZone()->_translate_string( 'Frequently Bought Together' ) . ' ' . WooZone()->_translate_string( 'Loading...' ),
					),
				);
				wp_localize_script( 'WooZone-frontend-script', 'woozone_vars', $vars );
			}
		}

		// wp 'init' hook
		public function init() {

			WooZone_debugbar()->add2bar_menu( 'woozone-debugbar-session-check', __('Session Check', 'woozone'), array() );
			WooZone_debugbar()->add2bar_menua( 'woozone-debugbar-session-check', __('Session Check', 'woozone'), array() );

			add_action( 'wp_footer', array( $this, 'wp_footer' ), 1 );
			
			//::::::::::::::::::::::::::::::::::::
			// start box with product country check
			$is_country_check = ( ! isset($this->amz_settings['product_countries'])
				|| 'yes' == $this->amz_settings['product_countries'] ? true : false );
			if ( $is_country_check ) {

				// single product page
				$box_countries_pos = isset($this->amz_settings['product_countries_main_position'])
					? $this->amz_settings['product_countries_main_position'] : 'before_add_to_cart';
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
				switch ($box_countries_pos) {
					case 'before_add_to_cart':
						add_action( 'woocommerce_single_product_summary', array($this, 'woocommerce_single_product_summary'), 21 );
						if ( 'Kingdom - Woocommerce Amazon Affiliates Theme' == $this->current_theme || 'BravoStore' == $this->current_theme  ) {
							add_action( 'WooZone_frontend_footer', array( $this, 'before_add_to_cart' ), 1 );
						}
						break;
					
					case 'before_title_and_thumb':
						add_action( 'WooZone_frontend_footer', array( $this, 'before_title_and_thumb' ), 1 );
						break;

					case 'before_woocommerce_tabs':
						add_action( 'WooZone_frontend_footer', array( $this, 'before_woocommerce_tabs' ), 1 );
						break;
						
					case 'as_woocommerce_tab':
						add_action( 'woocommerce_product_tabs', array($this, 'woocommerce_product_tabs'), 0 );
						break;		
				}

				//$where_country_check = isset($this->amz_settings['product_countries_where'])
				//	? (array) $this->amz_settings['product_countries_where'] : array(); //'maincart', 'minicart'
				$product_countries_maincart = ( ! isset($this->amz_settings['product_countries_maincart'])
				|| 'yes' == $this->amz_settings['product_countries_maincart'] ? true : false );
				$where_country_check = $product_countries_maincart ? array('maincart') : array();

				// view main cart
				if ( in_array('maincart', $where_country_check) )
					add_filter( 'woocommerce_cart_item_quantity', array($this, 'woocommerce_cart_item_quantity'), 10, 3 );

				// view mini cart
				if ( in_array('minicart', $where_country_check) ) {
					add_filter( 'woocommerce_widget_cart_item_quantity', array($this, 'woocommerce_widget_cart_item_quantity'), 10, 3 );
					if ( 'Kingdom - Woocommerce Amazon Affiliates Theme' == $this->current_theme ) {
						add_action( 'WooZone_frontend_footer', array( $this, 'widget_cart_item_quantity' ), 1 );
					}
				}

				// cart page
				//add_action( 'woocommerce_after_cart_table', array($this, 'woocommerce_after_cart') ); // don't work - already have a form
				add_action( 'woocommerce_after_cart', array($this, 'woocommerce_after_cart') );
			}
			// end box with product country check
			//::::::::::::::::::::::::::::::::::::
			
			$redirect_cart = (isset($_REQUEST['redirectCart']) && $_REQUEST['redirectCart']) != '' ? $_REQUEST['redirectCart'] : '';
			if( isset($redirect_cart) && $redirect_cart == 'true' ) {
				if ( ! $this->the_plugin->disable_amazon_checkout )
					$this->redirect_cart();
			}

			// product details page - external product
			add_action( 'woocommerce_after_add_to_cart_button', array($this, 'woocommerce_external_add_to_cart'), 10 );

			// non-external products pages
			if ( 'simple' == $this->p_type ) {
				// cart checkout
				if ( ! $this->the_plugin->disable_amazon_checkout ) {
					add_action( 'woocommerce_checkout_init', array($this, 'woocommerce_external_checkout'), 10 );
				}

				// checkout email
				if( isset($this->amz_settings['checkout_email']) && $this->amz_settings['checkout_email'] == 'yes' ) {
					add_filter( 'woocommerce_before_cart_totals', array($this, 'woocommerce_before_checkout'), 10 );
				}
			}

			//:: Amazon Reviews
			if ( isset($this->amz_settings['show_review_tab']) && ($this->amz_settings['show_review_tab'] == 'yes') ) {
				add_action('woocommerce_product_tabs', array($this, 'amazon_reviews_custom_product_tabs'), 25);
			}
		}

		// 'wp_footer' hook
		public function wp_footer() {
			global $wp_query;
			
			echo PHP_EOL . "<!-- start/ " . ($this->the_plugin->alias) . " wp_footer hook -->" . PHP_EOL;
			
			if ( ! has_action('WooZone_frontend_footer') )
				return true;
			
			do_action( 'WooZone_frontend_footer' );
			
			echo "<!-- end/ " . ($this->the_plugin->alias) . " wp_footer hook -->" . PHP_EOL.PHP_EOL;
			
			return true;
		}



		//====================================================
		//== COUNTRY AVAILABILITY

		/**
		 * Hooks functions
		 */
		// wp 'hooks' functions
		// amazon shops checkout on cart page
		public function woocommerce_after_cart() {
			//$is_cart_page = is_cart();
			//if ( ! $is_cart_page ) return ;
   
			$box = $this->box_amazon_shops_checkout();
			if ( !empty($box) )
				echo $box;
		}

		// product country on product details page
		public function woocommerce_single_product_summary() {
			global $product;
   
			$box = $this->box_country_check_details( $product );
			if ( !empty($box) )
				echo $box;
		}
		
		// product country on main cart
		public function woocommerce_cart_item_quantity($product_quantity, $cart_item_key, $cart_item=null) {
			$str = $product_quantity;

			// theme: kingdom
			if ( empty($cart_item) ) {
				$cart_items_nb = (int) WC()->cart->get_cart_contents_count();
				if ( $cart_items_nb )
					$cart_item = WC()->cart->get_cart_item( $cart_item_key);
			}

			$box = $this->box_country_check_small( isset($cart_item['product_id']) ? $cart_item['product_id'] : 0 );
			if ( !empty($box) ) {
				//$str .= $box;
				$str = str_replace('</div>', $box . '</div>', $str);
			}
			echo $str;
		}
		
		// product country on mini cart
		public function woocommerce_widget_cart_item_quantity($product_quantity, $cart_item, $cart_item_key) {
			$str = $product_quantity;
			$box = $this->box_country_check_small( isset($cart_item['product_id']) ? $cart_item['product_id'] : 0 );
			if ( !empty($box) ) {
				//$str .= $box;
				$str = str_replace('</span></span>', '</span></span>' . $box, $str);
			}
			echo $str;
		}
		public function widget_cart_item_quantity() {
			$pms = array('box_position' => 'minicart');
			$box = $this->box_country_check_minicart( $pms );
			if ( !empty($box) )
				echo $box;
		}
		
		// main box as woocommerce tab
		public function woocommerce_product_tabs( $tabs ) {
			$tabs['woozone_tab_countries_availability'] = array(
				'title'				=> __( 'Countries availability', $this->localizationName ),
				'priority'		=> 15,
				'callback'		=> array($this, 'woo_tab_countries_availability')
			);

			return $tabs;
		}
		public function woo_tab_countries_availability( $tab ) {
			global $product;

			$box = $this->box_country_check_details( $product );
			if ( !empty($box) )
				echo $box;
		}

		// main box positioning
		public function single_product_summary( $pms=array() ) {
			$is_product_page = is_product();
			if ( !$is_product_page ) return;

			global $product;

			$box = $this->box_country_check_details( $product, $pms );
			if ( !empty($box) )
				echo $box;
		}
		public function before_add_to_cart() {
			$this->single_product_summary( array('box_position' => 'before_add_to_cart') );
		}
		public function before_title_and_thumb() {
			$this->single_product_summary( array('box_position' => 'before_title_and_thumb') );
		}
		public function before_woocommerce_tabs() {
			$this->single_product_summary( array('box_position' => 'before_woocommerce_tabs') );
		}
		
		
		/**
		 * box: product country check
		 */
		// build minicart box with product country check
		private function box_country_check_minicart( $pms=array() ) {
			// parameters
			$pms = array_merge(array(
				'with_wrapper'			=> true,
				'box_position'			=> false,
			), $pms);
			extract($pms);
			
			// theme: kingdom
			$cart_items_nb = (int) WC()->cart->get_cart_contents_count();
			if ( !$cart_items_nb )
				return false;

			$minicart_items = array();

			$cart_items = WC()->cart->get_cart();
			foreach ( $cart_items as $key => $value ) {

				//$prod_id = isset($value['variation_id']) && (int)$value['variation_id'] > 0 ? $value['variation_id'] : $value['product_id'];
				$product_id = $value['product_id'];

				$asin = get_post_meta( $product_id, '_amzASIN', true );
				if ( empty($asin) ) continue 1;

				$product_country = $this->get_product_country_current( $product_id );
				$product_country__ = $product_country;
				if ( !empty($product_country) && isset($product_country['website']) ) {
					$product_country = substr($product_country['website'], 1);
				}
				
				$country_name = $product_country__['name'];
				
				$country_status = $product_country__['available'];
				$country_status_css = 'available-todo'; $country_status_text = __('not verified yet', $this->localizationName);
				switch ($country_status) {
					case 1:
						$country_status_css = 'available-yes';
						$country_status_text = __('is available', $this->localizationName);
						break;
						
					case 0:
						$country_status_css = 'available-no';
						$country_status_text = __('not available', $this->localizationName);
						break;
				}
				
				$minicart_items[] = array(
					'cart_item_key'				=> $key,
					'product_id'					=> $product_id,
					'asin'								=> $asin,
					'product_country'			=> $product_country,
					'country_name'				=> $country_name,
					'country_status_css'		=> $country_status_css,
					'country_status_text'	=> $country_status_text,
				);
			}

			ob_start();
		?>

<div class="WooZone-cc-small-cached" style="display: none;"><?php echo json_encode( $minicart_items ); ?></div>
<script type="text/template" id="WooZone-cc-small-template">
	<span class="WooZone-country-check-small WooZone-cc-custom">
		
		<span>
			<span class="WooZone-cc_domain"></span>
			<span class="WooZone-cc_status"></span>
		</span>

	</span>
</script>

		<?php
			$contents = ob_get_clean();
			return $contents;
		}

		public function get_asin_first_variation( $product_id )
		{
			$asin = false;
			$_product = wc_get_product( $product_id );
			if ( $_product->is_type( 'variable' ) ){
				
				$variations = $_product->get_available_variations();
				if( isset($variations[0]['variation_id']) ){
					$variation_asin = get_post_meta( $variations[0]['variation_id'], '_amzASIN', true);
					if ( !empty($variation_asin) ) {
						$asin = $variation_asin;
					}
				}
			}

			return $asin;
		}

		// build small box with product country check
		private function box_country_check_small( $product, $pms=array() ) {
			// get product id
			$product_id = $product;
			if ( is_object($product) ) {
				$prod_id = 0;
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
				$product_id = $prod_id;
			}
			if ( empty($product_id) ) return false;

			// parameters
			$pms = array_merge(array(
				'with_wrapper'			=> true,
				'box_position'			=> false,
			), $pms);
			extract($pms);

			// get asin meta key
			$asin = get_post_meta($product_id, '_amzASIN', true);

			if ( empty($asin) ) return false; // verify to be amazon product!

			$first_variation_asin = $this->get_asin_first_variation( $product_id );
			if( $first_variation_asin !== false ){
				$asin = $first_variation_asin;
			}
			//$asin = 'B000P0ZSHK'; // DEBUG
			//var_dump('<pre>',$asin,'</pre>');

			$product_country = $this->get_product_country_current( $product_id );
			$product_country__ = $product_country;
			//var_dump('<pre>', $product_id, $product_country, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			if ( !empty($product_country) && isset($product_country['website']) ) {
				$product_country = substr($product_country['website'], 1);
			}
			
			//$all_countries_affid = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->get_countries('main_aff_id');
			//$country_affid = $product_country__['key'];
			//$country_name = isset($all_countries_affid["$country_affid"]) ? $all_countries_affid["$country_affid"] : 'missing country name';
			$country_name = $product_country__['name'];

			$country_status = $product_country__['available'];
			$country_status_css = 'available-todo'; $country_status_text = __('not verified yet', $this->localizationName);
			switch ($country_status) {
				case 1:
					$country_status_css = 'available-yes';
					$country_status_text = __('is available', $this->localizationName);
					break;
					
				case 0:
					$country_status_css = 'available-no';
					$country_status_text = __('not available', $this->localizationName);
					break;
			}

			ob_start();
		?>

<?php if ($with_wrapper) { ?>
<span class="WooZone-country-check-small" data-prodid="<?php echo $product_id; ?>" data-asin="<?php echo $asin; ?>" data-prodcountry="<?php echo $product_country; ?>">
<?php } ?>

		<span>
			<span class="WooZone-cc_domain <?php echo str_replace('.', '-', $product_country); ?>" title="<?php echo $country_name; ?>"></span>
			<span class="WooZone-cc_status <?php echo $country_status_css; ?>" title="<?php echo $country_status_text; ?>"></span>
		</span>

<?php if ($with_wrapper) { ?>
</span>
<?php } ?>

		<?php
			$contents = ob_get_clean();
			return $contents;
		}

		// build main box with product country check
		private function box_country_check_details( $product, $pms=array() ) {
			// get product id
			$product_id = $product;
			if ( is_object($product) ) {
				$prod_id = 0;
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
				$product_id = $prod_id;
			}
			if ( empty($product_id) ) return false;

			// parameters
			$pms = array_merge(array(
				'with_wrapper'			=> true,
				'box_position'			=> false,
			), $pms);
			extract($pms);
			
			// get asin meta key
			$asin = get_post_meta($product_id, '_amzASIN', true);
			if ( empty($asin) ) return false; // verify to be amazon product!


			$first_variation_asin = $this->get_asin_first_variation( $product_id );
			if( $first_variation_asin !== false ){
				$asin = $first_variation_asin;
			}

			//$asin = 'B000P0ZSHK'; // DEBUG
			//var_dump('<pre>',$asin,'</pre>');
			
			$available_countries = $this->get_product_countries_available( $product_id );
			//var_dump('<pre>', $product_id, $available_countries, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;   
			if ( empty($available_countries) ) return false;

			$product_country = $this->get_product_country_current( $product_id );
			//var_dump('<pre>', $product_id, $product_country, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			if ( !empty($product_country) && isset($product_country['website']) ) {
				$product_country = substr($product_country['website'], 1);
			}
			
			// aff ids
			$aff_ids = $this->the_plugin->get_aff_ids();

			ob_start();
		?>

<?php if ($with_wrapper) { ?>
<ul class="WooZone-country-check" data-prodid="<?php echo $product_id; ?>" data-asin="<?php echo $asin; ?>" data-prodcountry="<?php echo $product_country; ?>" data-boxpos="<?php echo $box_position; ?>" <?php echo !empty($box_position) ? 'style="display: none;"' : ''; ?>>
<?php } ?>

	<div class="WooZone-country-cached" style="display: none;"><?php echo json_encode( $available_countries ); ?></div>
	<div class="WooZone-country-affid" style="display: none;"><?php echo json_encode( $aff_ids ); ?></div>
	<div class="WooZone-country-loader">
		<div>
			<div id="floatingBarsG">
				<div class="blockG" id="rotateG_01"></div>
				<div class="blockG" id="rotateG_02"></div>
				<div class="blockG" id="rotateG_03"></div>
				<div class="blockG" id="rotateG_04"></div>
				<div class="blockG" id="rotateG_05"></div>
				<div class="blockG" id="rotateG_06"></div>
				<div class="blockG" id="rotateG_07"></div>
				<div class="blockG" id="rotateG_08"></div>
			</div>
			<div class="WooZone-country-loader-text"></div>
		</div>
	</div>
	<div class="WooZone-country-loader bottom">
		<div>
			<div id="floatingBarsG">
				<div class="blockG" id="rotateG_01"></div>
				<div class="blockG" id="rotateG_02"></div>
				<div class="blockG" id="rotateG_03"></div>
				<div class="blockG" id="rotateG_04"></div>
				<div class="blockG" id="rotateG_05"></div>
				<div class="blockG" id="rotateG_06"></div>
				<div class="blockG" id="rotateG_07"></div>
				<div class="blockG" id="rotateG_08"></div>
			</div>
			<div class="WooZone-country-loader-text"></div>
		</div>
	</div>
	<div style="display: none;" id="WooZone-cc-template">
		<li>
			<?php if ( 'external' != $this->p_type ) { ?>
			<span class="WooZone-cc_checkbox">
				<input type="radio" name="WooZone-cc-choose[<?php echo $asin; ?>]" />
			</span>
			<?php } ?>
			<span class="WooZone-cc_domain<?php echo $this->countryflags_aslink ? ' WooZone-countryflag-aslink' : ''; ?>">
				<?php if ( $this->countryflags_aslink ) { ?>
				<a href="#" target="_blank"></a>
				<?php } ?>
			</span>
			<span class="WooZone-cc_name"><a href="#" target="_blank"></a></span>
			-
			<span class="WooZone-cc-status">
				<span class="WooZone-cc-loader">
					<span class="WooZone-cc-bounce1"></span>
					<span class="WooZone-cc-bounce2"></span>
					<span class="WooZone-cc-bounce3"></span>
				</span>
			</span>
		</li>
	</div>

<?php if ($with_wrapper) { ?>
</ul>
<?php } ?>

		<?php
			$contents = ob_get_clean();
			return $contents;
		}



		/**
		 * box: amazon shops checkout on cart page
		 */
		public function box_amazon_shops_checkout() {
			$shops = $this->woo_cart_get_amazon_prods_bycountry();
			if ( empty($shops) ) return false;
			
			$is_multiple = $this->woo_cart_is_amazon_multiple( $shops );
			if ( empty($is_multiple) || $is_multiple <= 1 ) return false;
			
			ob_start();
		?>

<div class="WooZone-cart-checkout">
	<ul class="WooZone-cart-shops">
	<?php
	foreach ($shops as $key => $value) {
		if ( empty($value) ) continue 1;

		//$country_name = array_shift(array_slice($array, 0, 1)); // get first element from array if a array "copy" is needed
		$domain = $value['domain'];
		$affID = $value['affID'];
		$country_name = $value['name'];

		$products = $value['products'];
		$nb_products = count($products);
		
		$prods_available = array();
		foreach ($products as $pkey => $pvalue)
			if ( $pvalue['available'] == 1 ) $prods_available[] = $pkey;
		$nb_available = count($prods_available);
	?>
		<li data-domain="<?php echo $domain; ?>">
			<span class="WooZone-cc_domain <?php echo str_replace('.', '-', $domain); ?>"></span>
			<span class="WooZone-cc_name"><?php echo $country_name; ?></span>
			<span class="WooZone-cc_count"><?php echo sprintf( _n('(%s available from %s product)', '(%s available from %s products)', $nb_available, $nb_products, $this->localizationName),  $nb_available, $nb_products ); ?></span>
			<span class="WooZone-cc_checkout">

				<form target="_blank" method="GET" action="//www.amazon.<?php echo $domain; ?>/gp/aws/cart/add.html">
					<input type="hidden" name="AssociateTag" value="<?php echo $affID; ?>"/>
					<?php /*<input type="hidden" name="SubscriptionId" value="<?php echo $this->amz_settings['AccessKeyID'];?>"/>*/ ?>
					<input type="hidden" name="AWSAccessKeyId" value="<?php echo $this->amz_settings['AccessKeyID'];?>"/>
					<?php 
					$cc = 1; 
					foreach ($products as $pkey => $pvalue){
					?>      
						<input type="hidden" name="ASIN.<?php echo $cc;?>" value="<?php echo $pvalue['asin'];?>"/>
						<input type="hidden" name="Quantity.<?php echo $cc;?>" value="<?php echo $pvalue['quantity'];?>"/>
					<?php
						$cc++;
					} // end foreach
					$redirect_in = isset($this->amz_settings['redirect_time']) && (int) $this->amz_settings['redirect_time'] > 0 ? ( (int) $this->amz_settings['redirect_time'] * 1000 ) : 1;
					?>
					<input type="submit" value="<?php _e('Proceed to Amazon Checkout', $this->localizationName); ?>" class="WooZone-button">
					<input type="button" value="<?php _e('Cancel', $this->localizationName); ?>" class="WooZone-button cancel">
				</form>

			</span>
			<span class="WooZone-cc_status"></span>
		</li>
	<?php
	} // end foreach
	?>
	</ul>
	<div class="WooZone-cart-msg"></div>
</div>

		<?php
			$contents = ob_get_clean();
			return $contents;
		}



		/**
		 * Cart related
		 */
		public function woocommerce_external_add_to_cart() {
			$prod_link_open_in = isset( $this->amz_settings['product_buy_button_open_in'] ) && !empty( $this->amz_settings['product_buy_button_open_in'] ) ? $this->amz_settings['product_buy_button_open_in'] : '_blank';
			if ( '_blank' == $prod_link_open_in ) {
				echo '<script>jQuery(".single_add_to_cart_button").attr("target", "_blank");</script>';
			}
		}

		public function woocommerce_external_checkout() {
			if( is_checkout() == true ){
				$this->redirect_cart();
			}
		}
		
		public function redirect_cart() {
			//global $woocommerce;

			$shops = $this->woo_cart_get_amazon_prods_bycountry();

			$is_multiple = $this->woo_cart_is_amazon_multiple( $shops );
			if ( empty($is_multiple) ) return true;

			// more than 1 amazon shops: product belonging to different amazon shops
			if ( $is_multiple > 1 ) {
				$this->woo_cart_update_meta_amazon_prods();
				$this->woo_cart_delete_amazon_prods();
				//echo '<script>setTimeout(function() { window.location.reload(true); }, 1);</script>'; 
				return true;
			}

			// single amazon shops: all products from cart will go to single amazon shop at checkout
			foreach ($shops as $key => $value) {
				if ( empty($value) ) continue 1;

				$domain = $value['domain'];
				$affID = $value['affID'];
				$country_name = $value['name'];
				$products = $value['products'];
				$nb_products = count($products);
			}
			//var_dump('<pre>', $domain, $affID, $country_name, $nb_products, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;    
			if ( ! $nb_products ) return true;

			$html = array();
			if ( isset($this->amz_settings["redirect_checkout_msg"]) && trim($this->amz_settings["redirect_checkout_msg"]) != "" ) {
				$html[] = '<img src="' . ( $this->the_plugin->cfg['paths']['freamwork_dir_url'] . 'images/checkout_loading.gif'  ) . '" style="margin: 10px auto;">';
				$html[] = "<h3>" . ( str_replace( '{amazon_website}', 'www.amazon.' . $domain, $this->amz_settings["redirect_checkout_msg"]) ) . "</h3>";
			}

			//$checkout_type =  isset($this->amz_settings['checkout_type']) && $this->amz_settings['checkout_type'] == '_blank' ? '_blank' : '_self';
			$checkout_type = '_self';

			ob_start();
			?>

			<form target="<?php echo $checkout_type;?>" id="amzRedirect" method="GET" action="//www.amazon.<?php echo $domain; ?>/gp/aws/cart/add.html">
				<input type="hidden" name="AssociateTag" value="<?php echo $affID;?>"/>
				<?php /*<input type="hidden" name="SubscriptionId" value="<?php echo $this->amz_settings['AccessKeyID'];?>"/>*/ ?>
				<input type="hidden" name="AWSAccessKeyId" value="<?php echo $this->amz_settings['AccessKeyID'];?>"/>
				<?php 
					$cc = 1; 
					foreach ($products as $key => $value){
				?>      
						<input type="hidden" name="ASIN.<?php echo $cc;?>" value="<?php echo $value['asin'];?>"/>
						<input type="hidden" name="Quantity.<?php echo $cc;?>" value="<?php echo $value['quantity'];?>"/>
				<?php
						$cc++;
					} // end foreach

					$redirect_in = isset($this->amz_settings['redirect_time']) && (int) $this->amz_settings['redirect_time'] > 0 ? ( (int) $this->amz_settings['redirect_time'] * 1000 ) : 1;
				?>
			</form>

			<script type="text/javascript">
				setTimeout(function() {
					document.getElementById("amzRedirect").submit();
					<?php 
						//if( (int)$woocommerce->cart->cart_contents_count > 0 && $checkout_type == '_blank' ){
						if ( $nb_products && $checkout_type == '_blank' ) {
					?>
					setTimeout(function() { window.location.reload(true); }, 1);
					<?php
						}
					?>
				}, <?php echo $redirect_in;?>);
			</script>

			<?php 
			$html[] = ob_get_contents(); //ob_clean();
			echo implode(PHP_EOL, $html);

			$this->woo_cart_update_meta_amazon_prods();
			$this->woo_cart_delete_amazon_prods();
			exit();
			return true;
		}



		/**
		 * checkout email
		 */
		public function woocommerce_before_checkout()
		{
			$return = '<div class="woozone_email_wrapper">';
			$return .= '<label for="woozone_checkout_user_email">E-mail:</label>';
			if( isset($this->amz_settings['checkout_email_mandatory']) && $this->amz_settings['checkout_email_mandatory'] == 'yes' ) {
				$return .= '<input type="hidden" id="woozone_checkout_email_required" name="woozone_checkout_email_required" value="1"/>';
			}
			$return .= '<input type="hidden" id="woozone_checkout_email_nonce" name="woozone_checkout_email_nonce" value="' . ( wp_create_nonce('woozone_checkout_email_nonce') ) . '"/>';
			$return .= '<input type="text" id="woozone_checkout_email" name="woozone_checkout_email" placeholder="email@example.com"/>';
			$return .= '</div>';

			echo $return;
		}
		
		public function woocommerce_ajax_before_user_checkout()
		{
			if( ! wp_verify_nonce( $_REQUEST['_nonce'], 'woozone_checkout_email_nonce')) die ('Busted!');
			unset($_REQUEST['_nonce']);
			
			$email = sanitize_email( $_REQUEST['email'] );
			$users_email = array();
			$users_email = get_option('WooZone_clients_email');
			
			if( is_email($email) ) {
				if( in_array($email, $users_email) ) {
					echo 'email_exists';
					die;
				}
				$users_email[] = $email;
				update_option('WooZone_clients_email', $users_email);
				echo 'success';
			}else{
				echo 'invalid_email';
			}
			
			die;
		}
	


		/**
		 * product country check
		 */
		// get product available amazon countries shops
		public function get_product_countries_available( $product ) {
			// get product id
			$product_id = $product;
			if ( is_object($product) ) {
				$prod_id = 0;
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
				$product_id = $prod_id;
			}
			if ( empty($product_id) ) return false;

			// amazon location & main affiliate ids
			$affIds = (array) ( isset($this->amz_settings['AffiliateID']) ? $this->amz_settings['AffiliateID'] : array() );
			if ( empty($affIds) ) return false;

			$main_aff_id = $this->the_plugin->main_aff_id();
			$main_aff_site = $this->the_plugin->main_aff_site();

			// countries
			$all_countries = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->get_countries('country');
			$all_countries_affid = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->get_countries('main_aff_id');

			// loop through setted affiliate ids from amazon config
			$available = array(); $cc = 0;
			foreach ($affIds as $key => $val) {
				if ( empty($val) ) continue 1;

				$convertCountry = $this->the_plugin->discount_convert_country2country();
				$domain = isset($convertCountry['amzwebsite']["$key"]) ? $convertCountry['amzwebsite']["$key"] : '';
				if ( empty($domain) ) continue 1;

				$available[$cc] = array(
					'domain'	=> $domain,
					'name'		=> isset($all_countries_affid["$key"]) ? $all_countries_affid["$key"] : 'missing country name',
				);
				$cc++;
			}
			if ( empty($available) ) return false;

			// verify affiliate ids based on product cached/saved available countries
			$meta_frontend = get_post_meta($product_id, '_amzaff_frontend', true);
			$cache_countries = isset($meta_frontend['countries']) && is_array($meta_frontend['countries']) ? $meta_frontend['countries'] : array();
			$cache_time = isset($meta_frontend['countries_cache_time']) ? $meta_frontend['countries_cache_time'] : 0;

			$cache_need_refresh = empty($cache_countries)
				|| !$cache_time
				|| ( ($cache_time + $this->the_plugin->ss['countries_cache_time']) < time() );

			// product amazon countries availability needs refresh (mandatory)
			if ( $cache_need_refresh ) return $available;

			// may need refresh if one country availability verification is missing!
			// verification for refresh is done in javascript/json based on 'available' key
			foreach ($available as $key => $val) {
				foreach ($cache_countries as $key2 => $val2) {
					// country founded
					if ( isset($val2['domain'], $val2['available']) && ($val['domain'] == $val2['domain']) ) {
						$available["$key"]['available'] = $val2['available'];
						break 1;
					}
				}
			}

			return $available;
		}

		// get product default country when added to cart (based on client country and main affiliate id)
		public function get_product_country_default( $product, $find_client_country=true ) {
			// get product id
			$product_id = $product;
			if ( is_object($product) ) {
				$prod_id = 0;
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
				$product_id = $prod_id;
			}
			if ( empty($product_id) ) return false;

			// client country
			$client_country = false;
			if ( $find_client_country ) {
				$client_country = $this->the_plugin->get_country_perip_external();
			}
			//var_dump('<pre>', $client_country, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			// return is of type:
			//array(3) {
			//	["key"]			=> string(3) "com"
			//	["website"]	=> string(4) ".com"
			//	["affID"]		=> string(8) "jimmy-us"
			//}

			// product available countries
			$available_countries = $this->get_product_countries_available( $product_id );
			$found = false; $first = false; $first_available = false;
			//var_dump('<pre>', $product_id, $available_countries, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			if ( !empty($available_countries) ) {
				foreach ($available_countries as $key => $val) {

					if ( empty($first) )
						$first = $val['domain'];

					if ( isset($val['available']) ) {
						if ( empty($first) )
							$first = $val['domain'];
						if ( empty($first_available) && $val['available'] )
							$first_available = $val['domain'];
					}
  
					if ( ! empty($client_country) && isset($client_country['website'])
						&& substr($client_country['website'], 1) == $val['domain'] ) {
						$found = $val['domain'];
					}
				}
			}
			//var_dump('<pre>',$found, $first, $first_available,'</pre>');  

			// default country based on: first from all valid countries, first available country or found client country
			$the_country = false;
			if ( !empty($first) ) 
				$the_country = $first;
			if ( !empty($first_available) ) 
				$the_country = $first_available;
			if ( !empty($found) ) 
				$the_country = $found;

			$country = $this->the_plugin->domain2amzForUser( $the_country );
			if ( !empty($available_countries) ) {
				foreach ($available_countries as $key => $val) {
					if ( substr($country['website'], 1) == $val['domain'] ) {
						$country = array_merge($country, array(
							'name'			=> $val['name'],
							'available'		=> isset($val['available']) ? $val['available'] : -1,
						));
					}
				}
			}
			return $country;
		}

		// get product current country when added to cart (based on default country and if client choose a country by himself)
		public function get_product_country_current( $product, $find_client_country=true ) {
			// get product id
			$product_id = $product;
			if ( is_object($product) ) {
				$prod_id = 0;
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
				$product_id = $prod_id;
			}
			if ( empty($product_id) ) return false;
   
			$the_country = $this->get_product_country_default( $product_id, $find_client_country );
			$country = $the_country;
			
			// get asin meta key
			$asin = get_post_meta($product_id, '_amzASIN', true);
			$first_variation_asin = $this->get_asin_first_variation( $product_id );
			if( $first_variation_asin !== false ){
				$asin = $first_variation_asin;
			}
			//var_dump('<pre>',$asin,'</pre>');

			//unset($_SESSION['WooZone']);
			//var_dump('<pre>', $the_country, $_SESSION, '</pre>');

			if ( !empty($asin)
				 && isset(
					$_SESSION['WooZone'],
					$_SESSION['WooZone']['product_country'],
					$_SESSION['WooZone']['product_country']["$asin"]
				 )
				 && !empty($_SESSION['WooZone']['product_country']["$asin"])
			) {
				$sess_country = $_SESSION['WooZone']['product_country']["$asin"];

				// product available countries
				$available_countries = $this->get_product_countries_available( $product_id );

				if ( !empty($available_countries) ) {
					foreach ($available_countries as $key => $val) {

						if ( $sess_country == $val['domain'] ) {
							$the_country = $sess_country;
							$country = $this->the_plugin->domain2amzForUser( $the_country );
							$country = array_merge($country, array(
								'name'			=> $val['name'],
								'available'		=> isset($val['available']) ? $val['available'] : -1,
							));
						}
					}
				}
			}

			return $country;
		}
		
		// get amazon products from cart
		public function woo_cart_get_amazon_prods() {
			//global $woocommerce;

			$amz_products = array();

			$cart_items_nb = (int) WC()->cart->get_cart_contents_count();
			if ( ! $cart_items_nb ) return false;

			$cart_items = WC()->cart->get_cart();
			//var_dump('<pre>', $cart_items, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;    
			foreach ($cart_items as $key => $value) {

				$prod_id = isset($value['variation_id']) && (int)$value['variation_id'] > 0 ? $value['variation_id'] : $value['product_id'];
				$amzASIN = $prod_id ? get_post_meta( $prod_id, '_amzASIN', true ) : '';
				
				$parent_id = isset($value['variation_id']) && (int)$value['variation_id'] > 0 ? $value['product_id'] : 0;
				$parent_amzASIN = $parent_id ? get_post_meta( $parent_id, '_amzASIN', true ) : '';

				//if ( empty($amzASIN) || strlen($amzASIN) != 10 )
				if ( empty($amzASIN) ) continue 1;

				//$meta_amzResp = get_post_meta($prod_id, '_amzaff_amzRespPrice', true);

				$amz_products["$key"] = array(
					'cart_item_key'				=> $key,
					'product_id'				=> $prod_id,
					'asin'						=> $amzASIN,
					'parent_id'					=> $parent_id,
					'parent_asin' 				=> $parent_amzASIN,
					'quantity'					=> $value['quantity'],
				);
			} // end foreach
	
			return $amz_products;
		}
		
		// get amazon products from cart by country availability
		public function woo_cart_get_amazon_prods_bycountry() {
			$prods = $this->woo_cart_get_amazon_prods();
			//var_dump('<pre>', $prods, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;    
			if ( empty($prods) ) return false;

			foreach ($prods as $key => $value) {
				$prod_id = $value['parent_id'] ? $value['parent_id'] : $value['product_id'];
				$product_country = $this->get_product_country_current( $prod_id );

				$prods["$key"] = array_merge($prods["$key"], $product_country);
			} // end foreach
			//var_dump('<pre>', $prods, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$bycountry = array();
			foreach ($prods as $key => $value) {
				$domain = substr($value['website'], 1);

				if ( ! isset($bycountry["$domain"]) ) {
					$bycountry["$domain"] = array(
						'domain'			=> $domain,
						'affID'				=> $value['affID'],
						'name'				=> $value['name'],
						'products'			=> array(),
					);
				}
				$bycountry["$domain"]["products"]["$key"] = $value;
			} // end foreach
			//var_dump('<pre>', $bycountry, '</pre>');    

			return $bycountry;
		}

		// woocommerce cart contains multiple amazon shops
		public function woo_cart_is_amazon_multiple( $shops=array() ) {
			if ( empty($shops) )
				$shops = $this->woo_cart_get_amazon_prods_bycountry();
			if ( empty($shops) ) return false;

			$domains = array();
			foreach ($shops as $key => $value) {
				if ( empty($value) ) continue 1;
				
				$domain = $value['domain'];
				if ( ! in_array($domain, $domains) )
					$domains[] = $domain;
			}
			return count($domains);
		}
		
		// update meta (redirect to amazon) for amazon products from cart
		public function woo_cart_update_meta_amazon_prods() {
			$prods = $this->woo_cart_get_amazon_prods();
			//var_dump('<pre>', $prods, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;    
			if ( empty($prods) ) return false;
   
			foreach ($prods as $key => $value) {
				if ( ! isset($value['asin']) || trim($value['asin']) == '' ) continue 1;

				$post_id = $this->the_plugin->get_post_id_by_meta_key_and_value('_amzASIN', $value['asin']);

				$redirect_to_amz = (int) get_post_meta($post_id, '_amzaff_redirect_to_amazon', true);
				update_post_meta($post_id, '_amzaff_redirect_to_amazon', (int)($redirect_to_amz+1));

				$redirect_to_amz2 = (int) get_post_meta($post_id, '_amzaff_redirect_to_amazon_prev', true);
				update_post_meta($post_id, '_amzaff_redirect_to_amazon_prev', (int)($redirect_to_amz2+1));
			} // end foreach
		}
		
		// delete amazon products from cart
		public function woo_cart_delete_amazon_prods() {
			$prods = $this->woo_cart_get_amazon_prods();
			//var_dump('<pre>', $prods, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;    
			if ( empty($prods) ) return false;

			foreach ($prods as $key => $value) {
				if ( ! isset($value['asin']) || trim($value['asin']) == '' ) continue 1;

				//var_dump('<pre>', $key, $value,'</pre>');

				// Remove it from the cart
				//WC()->cart->set_quantity( $value['key'], 0 );
				WC()->cart->remove_cart_item($key);

				//$cart_item = WC()->cart->get_cart_item( $value['key'] );
				//var_dump('<pre>','after delete:', $cart_item,'</pre>');
			} // end foreach

			$cart_items_nb = (int) WC()->cart->get_cart_contents_count();
			//var_dump('<pre>', $cart_items_nb, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
		}



		//====================================================
		//== CROSS SELL

		/**
		 * Cross Sell - Similarity Products
		 */
		public function cross_sell_checkout()
		{
			$amz_cross_sell = isset($_GET['amz_cross_sell']) ? (string) $_GET['amz_cross_sell'] : false;
			if ( false === $amz_cross_sell ) return '';
			
			$asins = isset($_GET['asins']) ? $_GET['asins'] : '';
			$asins = trim($asins);
			if ( '' == $asins ) return '';
			
			$asins = explode(',', $asins);
			if ( empty($asins) ) return '';

			// I: use amazon api to add products to cart
			if (0) {

				//$GLOBALS['WooZone'] = $this;
				
				if ( $this->the_plugin->is_aateam_demo_keys() ) {
					return '';
				}

				$selectedItems = array();
				foreach ($asins as $key => $value){
					$selectedItems[] = array(
						'offerId' => $value,
						'quantity' => 1
					);
				}
   
				$rsp = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->api_main_request(array(
					'what_func' 			=> 'api_make_request',
					'amz_settings'          => $this->amz_settings,
					'from_file'             => str_replace($this->the_plugin->cfg['paths']['plugin_dir_path'], '', __FILE__),
					'from_func'             => __FUNCTION__ != __METHOD__ ? __METHOD__ : __FUNCTION__,
					'requestData'           => array(
						'selectedItems'         => $selectedItems,
					),
					//'optionalParameters'  => array(),
					'responseGroup'         => 'Cart',
					'method'                => 'cartThem',
				));
				$cart = $rsp['response'];
	  
				// debug only
				//unset($_SESSION['amzCart']);

				$user_country = $this->the_plugin->get_country_perip_external();
				$config = $this->amz_settings;
				// AssociateTag => $user_country['affID']
				// SubscriptionId => $config['AccessKeyID']
	
				$cart_items = isset($cart['CartItems']['CartItem']) ? $cart['CartItems']['CartItem'] : array();
				//var_dump('<pre>', $cart['PurchaseURL'], $cart_items, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;    
				if( count($cart_items) ){
					header('Location: ' . $cart['PurchaseURL'] . "%26tag=" . $user_country['affID']); // & = %26 => link must be encoded
					exit();
				}

			} // end I

			// II: create a fake form and submit it with javascript
			if (1) {

			$user_country = $this->the_plugin->get_country_perip_external();
			$main_aff_id = $this->the_plugin->main_aff_id();
			$main_aff_site = $this->the_plugin->main_aff_site();

			$products = array();
			foreach ($asins as $key => $value){
				$products[] = array(
					'asin' => $value,
					'quantity' => 1
				);
			}
			
			if ( empty($products) ) return true;

			$domain = substr($user_country['website'], 1); //$this->amz_settings['country']; //substr($user_country['website'], 1);
			$affID = $user_country['affID'];

			$html = array();
			if ( isset($this->amz_settings["redirect_checkout_msg"]) && trim($this->amz_settings["redirect_checkout_msg"]) != "" ) {
				$html[] = '<img src="' . ( $this->the_plugin->cfg['paths']['freamwork_dir_url'] . 'images/checkout_loading.gif'  ) . '" style="margin: 10px auto;">';
				$html[] = "<h3>" . ( str_replace( '{amazon_website}', 'www.amazon.' . $domain, $this->amz_settings["redirect_checkout_msg"]) ) . "</h3>";
			}
		
			//$checkout_type =  isset($this->amz_settings['checkout_type']) && $this->amz_settings['checkout_type'] == '_blank' ? '_blank' : '_self';
			$checkout_type = '_self';
			
			ob_start();
			?>

			<form target="<?php echo $checkout_type;?>" id="amzRedirect" method="GET" action="//www.amazon.<?php echo $domain; ?>/gp/aws/cart/add.html">
				<input type="hidden" name="AssociateTag" value="<?php echo $affID;?>"/>
				<?php /*<input type="hidden" name="SubscriptionId" value="<?php echo $this->amz_settings['AccessKeyID'];?>"/>*/ ?>
				<input type="hidden" name="AWSAccessKeyId" value="<?php echo $this->amz_settings['AccessKeyID'];?>"/>
				<?php 
					$cc = 1; 
					foreach ($products as $key => $value){
				?>      
						<input type="hidden" name="ASIN.<?php echo $cc;?>" value="<?php echo $value['asin'];?>"/>
						<input type="hidden" name="Quantity.<?php echo $cc;?>" value="<?php echo $value['quantity'];?>"/>
				<?php
						$cc++;
					} // end foreach

					//$redirect_in = isset($this->amz_settings['redirect_time']) && (int) $this->amz_settings['redirect_time'] > 0 ? ( (int) $this->amz_settings['redirect_time'] * 1000 ) : 1;
					$redirect_in = 1;
				?>
			</form>

			<script type="text/javascript">
				setTimeout(function() {
					document.getElementById("amzRedirect").submit();
				}, <?php echo $redirect_in;?>);
			</script>

			<?php 
			$html[] = ob_get_contents(); //ob_clean();
			echo implode(PHP_EOL, $html);
			exit;
			
			} // end II
		}

		public function cross_sell_box( $atts ) {
			extract( shortcode_atts( array(
				'asin' => ''
			), $atts ) );

			$cross_selling = (isset($this->amz_settings["cross_selling"]) && $this->amz_settings["cross_selling"] == 'yes' ? true : false);
			
			if( $cross_selling == false ) return '';

			$backHtml = array();
			$backHtml[] = '<div class="main-cross-sell" data-asin="' . $asin . '">';

			ob_start();
		?>

	<div class="WooZone-cross-sell-loader">
		<div>
			<div id="floatingBarsG">
				<div class="blockG" id="rotateG_01"></div>
				<div class="blockG" id="rotateG_02"></div>
				<div class="blockG" id="rotateG_03"></div>
				<div class="blockG" id="rotateG_04"></div>
				<div class="blockG" id="rotateG_05"></div>
				<div class="blockG" id="rotateG_06"></div>
				<div class="blockG" id="rotateG_07"></div>
				<div class="blockG" id="rotateG_08"></div>
			</div>
			<div class="WooZone-cross-sell-loader-text"></div>
		</div>
	</div>

		<?php
			$backHtml[] = ob_get_clean();

			$backHtml[] = '</div>';

			$opGetDebug = '<div id="WooZone-cross-sell-debug" class="WooZone-cross-sell-debug" data-asin="' . $asin . '"></div>';
			//if ( $this->the_plugin->is_debug_mode_allowed() ) {
			//	$backHtml[] = $opGetDebug;
			//}
			WooZone_debugbar()->add2bar_row( 'woozone-debugbar-cross-sell', $opGetDebug, array() );
			WooZone_debugbar()->add2bar_menu( 'woozone-debugbar-cross-sell', __('Frequently Bought Together', 'woozone'), array() );
			WooZone_debugbar()->add2bar_menua( 'woozone-debugbar-cross-sell', __('Frequently Bought Together', 'woozone'), array() );

			$backHtml[] = '<div style="clear:both;"></div>';
			
			$html = implode(PHP_EOL, $backHtml);
			return $html;
		}

		public function _cross_sell_box( $atts=array() ) {
			extract($atts);

			global $product;
			
			$ret = array('status' => 'valid', 'html' => '', 'nbprods' => 0, 'debug' => '');

			// get product related items from Amazon
			$products = $this->_cross_sell_get_similarity_prods( $asin, 10 );
			
			$ret['debug'] = $this->_cross_sell_debug_msg( $products );
			$ret['nbprods'] = count($products['rows']);

			$backHtml = array();
			if ( isset($products['status'], $products['rows']) && 'valid' == $products['status'] && !empty($products['rows']) ) {
				
				$choose_variation = isset($this->amz_settings['cross_selling_choose_variation']) ? (string) $this->amz_settings['cross_selling_choose_variation'] : 'first';

				$how_many = isset($this->amz_settings['cross_selling_nbproducts']) ? (int) $this->amz_settings['cross_selling_nbproducts'] : 3;
				$how_many = $how_many + 1; // add 1 fake in products, current product

				// :: open box wrapper
				$backHtml[] = WooZone_asset_path( 'css', $this->the_plugin->cfg['paths']['frontend_dir_url'] . '/css/cross-sell.css', false, array( 'id' => 'amz-cross-sell' ) );

				$backHtml[] = '<div class="cross-sell">';
				$backHtml[] = '<span class="cross-sell-price-sep" data-price_dec_sep="' . wc_get_price_decimal_separator() . '" style="display: none;"></span>';
				$backHtml[] =   '<h2>' . ( __( WooZone()->_translate_string( 'Frequently Bought Together' ), $this->localizationName ) ) . '</h2>';
				$backHtml[] =   '<div style="margin-top: 0px;" class="separator"></div>';

				// :: box first row - with thumbs
				$backHtml[] =   '<ul id="feq-products">';
				$cc = 0;
				$_total_price = 0;
				foreach ($products['rows'] as $key => $value) {
					
					if ( $cc >= $how_many ) break;

					// is variable product? => get chosen variation based on option
					if ( isset($value['is_variable']) && 'Y' == $value['is_variable'] ) {

						$variation_found = array();

						// if verification
						if ( isset($value['variations'], $value['variations_filtered'])
							&& is_array($value['variations']) && ! empty($value['variations'])
							&& is_array($value['variations_filtered']) ) {

							// just in case: choose first valid variation
							$variation_found = array_values($value['variations']);
							$variation_found = isset($variation_found[0]) ? $variation_found[0] : array();

							// choose variation from option value (allowed: first, lowest price, highest price)
							foreach ( $value['variations_filtered'] as $varType => $varAsin) {
								if ( ! empty($varAsin) && isset($value['variations']["$varAsin"]) ) {
									$variation_found = $value['variations']["$varAsin"];
									if ( $choose_variation == $varType ) { // the chosen one!
										break;
									}
								}
							}
						} // end if verification

						// couldn't find a valid variation for this product
						if ( empty($variation_found) ) {
							unset($products['rows']["$key"]); // delete this invalid product!
							continue 1; // we intentionaly don't increment the counter, so we can go and verify next products!
						}

						// replace old main variable product details with its variation child details
						$value = $variation_found;
						$products['rows']["$key"] = $variation_found;
					}

					$value['price'] = str_replace(",", ".", $value['price']);
					
					$product_buy_url = $this->the_plugin->_product_buy_url( 0, $value['ASIN'] );
					$prod_link = home_url('/?redirectAmzASIN=' . $value['ASIN'] );
					$prod_link = $product_buy_url;
					
					if( trim($value['thumb']) != "" ){
						$backHtml[] =   '<li>';
						$backHtml[] =   '<a target="_blank" rel="nofollow" href="' . ( $prod_link ) . '">';
						$backHtml[] =       '<img class="cross-sell-thumb" id="cross-sell-thumb-' . ( $value['ASIN'] ) . '" src="' . ( $value['thumb'] ) . '" alt="' . ( htmlentities( str_replace('"', "'", $value['Title']) ) ) . '">';
						$backHtml[] =   '</a>';
						if( $cc < (count($products['rows']) - 1) ){
							$backHtml[] =       '<div class="plus-sign">+</div>';
						}

						$backHtml[] =   '</li>';
						
						$_total_price = $_total_price + $value['price'];
					}

					
					$cc++;
				}
				$backHtml[] =   '</ul>';

				// :: box second row - with titles & prices
				$backHtml[] =   '<div class="cross-sell-buy-btn">';
				$backHtml[] =   	'<span id="cross-sell-bpt">' . WooZone()->_translate_string( 'Price for all' ) . ':</span>';
				$backHtml[] =   	'<span id="cross-sell-buying-price" class="price">' . ( wc_price( $_total_price ) ) . '</span>';
				$backHtml[] =       '<div style="clear:both"></div><a href="' . home_url(). '" id="cross-sell-add-to-cart">' . WooZone()->_translate_string( 'Add to cart' ) . '</a>';
				$backHtml[] =   '</div>';

				$backHtml[] = '<div class="cross-sell-buy-selectable">';
				$backHtml[] =   '<ul class="cross-sell-items">';
				$cc = 0;
				foreach ($products['rows'] as $key => $value) {
					
					if ( $cc >= $how_many ) break;

					if ( $cc == 0 && ( $asin == $value['ASIN'] || $asin == $value['ParentASIN'] ) ) {
						$backHtml[] =       '<li>';
						$backHtml[] =           '<input type="checkbox" checked="checked" value="' . ( $value['ASIN'] ) . '">';
						$backHtml[] =           '<div class="cross-sell-product-title"><strong>' . __( WooZone()->_translate_string( 'This item' ), $this->localizationName) . ': </strong>' . $value['Title'] . '</div>';
						$backHtml[] =           '<div class="cross-sell-item-price" data-item_price="' . $value['price'] . '">' . ( wc_price( $value['price'] ) ) . '</div>';
						$backHtml[] =       '</li>';
					}
					else{
						$product_buy_url = $this->the_plugin->_product_buy_url( 0, $value['ASIN'] );
						$prod_link = home_url('/?redirectAmzASIN=' . $value['ASIN'] );
						$prod_link = $product_buy_url;

						$backHtml[] =       '<li>';
						$backHtml[] =           '<input type="checkbox" checked="checked" value="' . ( $value['ASIN'] ) . '">';
						$backHtml[] =           '<div class="cross-sell-product-title">' . ( '<a target="_blank" rel="nofollow" href="' . ( $prod_link ) . '">' . $value['Title'] .'</a>' ) . '</div>';
						$backHtml[] =           '<div class="cross-sell-item-price" data-item_price="' . $value['price'] . '">' . ( wc_price( $value['price'] ) ) . '</div>';
						$backHtml[] =       '</li>';
					}

					$cc++;
				}
				$backHtml[] =   '</ul>';
				$backHtml[] = '</div>';

				// :: close box wrapper
				$backHtml[] = '</div>';

				$backHtml[] = '<div style="clear:both;"></div>';

				if ( isset($_total_price) && ($_total_price > 0) ) {
					return array_merge($ret, array(
						'html'		=> implode(PHP_EOL, $backHtml), 
					));
				}
				return $ret;
			}
			return $ret;
		}

		public function _cross_sell_get_similarity_prods( $asin, $return_nr=3, $force_update=false ) {
			$max_tries = 5;
			$cache_valid_for = (60 * 60 * 24); // 24 hours in seconds

			$return_nr = $return_nr + 1; // add 1 fake in products, current product

			$ret = array('status' => 'invalid', 'rows' => array(), 'msg' => '', 'msg_extra' => array());
			$retProd = array();
			$msg_extra = array();
			$nb_tries = 'inc';

			// check for cache of this ASIN
			$db = $this->the_plugin->db;
			$cache_request = $db->get_row( $db->prepare( "SELECT * FROM " . ( $db->prefix ) . "amz_cross_sell WHERE ASIN = %s", $asin), ARRAY_A );

			// if cache found for this product & NOT force update
			if ( $cache_request != "" && count($cache_request) > 0 && $force_update === false ) {

				// get products from DB cache amz_cross_sell table
				$products = maybe_unserialize($cache_request['products']);

				$msg_extra = array(
					'asin'					=> $cache_request['ASIN'],
					'nr_products'		=> $cache_request['nr_products'],
					'is_variable'		=> $cache_request['is_variable'],
					'nb_tries'			=> $cache_request['nb_tries'],
				);

				// is valid cache?
				if ( isset($cache_request['add_date']) ) {
					$add_date = strtotime($cache_request['add_date']);
					//$add_date = gmdate("U", $add_date);
				}
				$cache_isvalid = 
					isset($cache_request['add_date'])
					&& ( ($add_date + $cache_valid_for) > time() )
					? true : false;

				// if cache timeout (not valid anymore) => reset nb tries
				if ( ! $cache_isvalid ) {
					$nb_tries = 0;
				}
				else {
					$msg_extra['cache_expires_in'] = $this->the_plugin->u->time_since(
						time(),
						($add_date + $cache_valid_for)
					);
					unset($msg_extra['cache_expires_in']);
				}

				// make cache invalid, because no products found saved in cache & still allowed to make tries
				if ( empty($products) && isset($cache_request['nb_tries']) && ( $cache_request['nb_tries'] < $max_tries ) ) {
					$cache_isvalid = false;
				}

				// if cache still valid, get from mysql cache & NOT force update
				if ( $cache_isvalid ) {
					$msg_extra['from_cache'] = true;
					return array('status' => 'valid', 'rows' => array_slice( $products, 0, $return_nr ), 'msg' => 'products returned from cache.', 'msg_extra' => $msg_extra);
				}
			}

			if ( $this->the_plugin->is_aateam_demo_keys() ) {
				return array_merge( $ret, array('status' => 'invalid', 'rows' => array(), 'msg' => 'you\'re not allowed to use aateam demo keys on cross sell.') );
			}

			// get current product
			$rsp = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->api_main_request(array(
				'what_func' 			=> 'api_make_request',
				'amz_settings'          => $this->amz_settings,
				'from_file'             => str_replace($this->the_plugin->cfg['paths']['plugin_dir_path'], '', __FILE__),
				'from_func'             => __FUNCTION__ != __METHOD__ ? __METHOD__ : __FUNCTION__,
				'requestData'           => array(
					'asin'                  => $asin,
				),
				'optionalParameters'    => array(),
				'responseGroup'         => 'Large,ItemAttributes,OfferFull,Offers,Variations,VariationSummary',
				'method'                => 'lookup',
			));
			//var_dump('<pre>', $rsp, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			$thisProd = $rsp['response'];
			$thisProd_respStatus = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->is_amazon_valid_response( $thisProd );
			
			// loop current product
			if ( $thisProd_respStatus['status'] == 'valid' ) { // success
				$thisProd = $thisProd['Items']['Item'];
				$prodasin = $thisProd['ASIN'];
				$foundProd = $this->_cross_sell_get_prod_fields( $thisProd );
				if ( ! empty($foundProd) ) {
					$retProd[$prodasin] = $foundProd;
				}
			}
			//var_dump('<pre>', $retProd, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL; 
			
			// get SIMILARITY products
			$rsp = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->api_main_request(array(
				'what_func' 			=> 'api_make_request',
				'amz_settings'          => $this->amz_settings,
				'from_file'             => str_replace($this->the_plugin->cfg['paths']['plugin_dir_path'], '', __FILE__),
				'from_func'             => __FUNCTION__ != __METHOD__ ? __METHOD__ : __FUNCTION__,
				'requestData'           => array(
					'asin'                  => $asin,
				),
				'optionalParameters'    => array(),
				'responseGroup'         => 'Large,ItemAttributes,OfferFull,Offers,Variations,VariationSummary',
				'method'                => 'similarityLookup',
			));
			//var_dump('<pre>', $rsp, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			$similarity = $rsp['response'];
			$similarity_respStatus = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->is_amazon_valid_response( $similarity );

			// loop SIMILARITY products
			if ( $similarity_respStatus['status'] == 'valid' ) { // success
				foreach ($similarity['Items']['Item'] as $key => $value){
					if (
						count($similarity['Items']['Item']) > 0
						&& count($value) > 0
						&& isset($value['ASIN'])
						&& strlen($value['ASIN']) >= 10
					) {
						$thisProd = $value;
						$prodasin = $thisProd['ASIN'];
						$foundProd = $this->_cross_sell_get_prod_fields( $thisProd );
						if ( ! empty($foundProd) ) {
							$retProd[$prodasin] = $foundProd;
						}
					}
				}
			}
			//var_dump('<pre>', $retProd, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL; 

			// invalid response
			if ( empty($retProd) ) {
				$msg = array();
				if ( isset($thisProd['status'], $thisProd['msg']) && 'invalid' == $thisProd['status'] ) {
					$msg[] = $thisProd['msg'];
				}
				if ( isset($similarity['status'], $similarity['msg']) && 'invalid' == $similarity['status'] ) {
					$msg[] = $similarity['msg'];
				}
				$msg = implode('<br />', $msg);
				return array_merge( $ret, array('status' => 'invalid', 'rows' => array(), 'msg' => $msg) );
			}

			// SIMILARITY products response is invalid
			if ( $similarity_respStatus['status'] != 'valid' ) {
				// if "There are no similar items for this product" we need to save in cache
				if ( isset($similarity_respStatus['amz_code']) && 'aws.ecommerceservice.nosimilarities' == strtolower($similarity_respStatus['amz_code']) ) {
					$retProd = array();
					$ret['msg'] = $similarity_respStatus['amz_code'];
					$noSimilarities = true;
				}
				else {
					$msg = array();
					$msg[] = $similarity_respStatus['msg'];
					return array_merge( $ret, array('status' => 'invalid', 'rows' => array(), 'msg' => implode('<br />', $msg)) );
				}
			}

			// if cache found for this product
			$savedb = array(
				'products'				=> serialize($retProd), //serialize(array_slice( $retProd, 0, $return_nr)),
				'nr_products'			=> count($retProd), //$return_nr <= count($retProd) ? $return_nr : count($retProd),
				'is_variable'			=> isset($retProd["$asin"], $retProd["$asin"]['is_variable']) ? (string) $retProd["$asin"]['is_variable'] : 'N',
			);

			if ( $cache_request != "" && count($cache_request) > 0 ) {

				$nb_tries = isset($noSimilarities) && $noSimilarities ? $max_tries : $nb_tries;
				$calcTries = $this->_cross_sell_calc_tries($nb_tries, $cache_request['nb_tries'], $force_update);

				$updateQuery = "update " . $db->prefix . "amz_cross_sell" . " set products = %s, nr_products = %s, is_variable = %s" . $calcTries['query'] . "where 1=1 and ASIN = %s;";
				$updateQuery = $db->prepare( $updateQuery, $savedb['products'], $savedb['nr_products'], $savedb['is_variable'], $asin );
				$db->query( $updateQuery );
				/*
				$db->update(
					$db->prefix . "amz_cross_sell",
					array(
						'products'			=> $savedb['products'],
						'nr_products'		=> $savedb['nr_products'],
						'is_variable'		=> $savedb['is_variable'],
						'nb_tries'			=> 'nb_tries + 1',
					),
					array( 'ASIN' => $asin ),
					array(
						'%s',
						'%d',
						'%s',
						'%d'
					),
					array(
						'%s'
					)
				);
				*/
			}
			// if cache not found for this product
			else {
				$nb_tries = isset($noSimilarities) && $noSimilarities ? $max_tries : 1;
				$calcTries = $this->_cross_sell_calc_tries($nb_tries, 0, $force_update);

				/*$db->insert(
					$db->prefix . "amz_cross_sell",
					array(
						'ASIN'				=> $asin,
						'products'			=> $savedb['products'],
						'nr_products'		=> $savedb['nr_products'],
						'is_variable'		=> $savedb['is_variable'],
						'nb_tries'			=> 1,
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d'
					)
				);*/
				$this->the_plugin->db_custom_insert(
					$db->prefix . "amz_cross_sell",
					array(
						'values' => array(
							'ASIN'				=> $asin,
							'products'			=> $savedb['products'],
							'nr_products'		=> $savedb['nr_products'],
							'is_variable'		=> $savedb['is_variable'],
							'nb_tries'			=> $nb_tries,
						),
						'format' => array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d'
						)
					),
					true
				);
			}
			
			$msg_extra = array(
				'asin'					=> $asin,
				'nr_products'		=> $savedb['nr_products'],
				'is_variable'		=> $savedb['is_variable'],
				'nb_tries'			=> $calcTries['nb'],
			);
			if ( $force_update ) {
				$msg_extra['force_update'] = 'yes';
			}

			if ( ! empty($ret['msg']) ) {
				$ret['msg'] .= ' - ';
			}
			if ( ! empty($retProd) ) {
				$ret['msg'] .= 'products successfully returned from amazon request.';
			}
			else {
				$ret['msg'] .= 'no products returned from amazon request.';
			}
			return array_merge( $ret, array('status' => 'valid', 'rows' => array_slice( $retProd, 0, $return_nr ), 'msg_extra' => $msg_extra) );
		}

		public function _cross_sell_get_prod_fields( $thisProd, $pms=array() ) {
			$pms = array_replace_recursive(array(
				'max_variations'			=> -1, // -1 = unlimited; maximum variations to retrieve
				'is_variation_child'			=> false, // current product data is for a variation child
			), $pms);
			extract( $pms );

			$retProd = array();

			// :: main properties
			$retProd['ASIN'] = isset($thisProd['ASIN']) ? $thisProd['ASIN'] : '';
			$retProd['ParentASIN'] = isset($thisProd['ParentASIN']) ? $thisProd['ParentASIN'] : '';
			
			// :: product title
			$retProd['Title'] = isset($thisProd['ItemAttributes']['Title']) ? stripslashes($thisProd['ItemAttributes']['Title']) : '';
			
			// :: variations
			if ( ! $is_variation_child ) {
				
				$retProd['DetailPageURL'] = isset($thisProd['DetailPageURL']) ? $thisProd['DetailPageURL'] : '';

				$retProd['is_variable'] = 'N';
				$variations = isset($thisProd['Variations'], $thisProd['Variations']['Item'])
					? $thisProd['Variations']['Item'] : array();
	
				if ( ! empty($variations) ) {

					if ( isset($variations['ASIN']) ) {
						$variations = array( $variations );
					}

					$retProd['is_variable'] = 'Y';
					$retProd['variations'] = array();
					$retProd['variations_total'] = count($variations);
					$retProd['variations_filtered'] = array(
						'first'					=> '',
						'lowest_price'	=> '',
						'highest_price'	=> '',
					);
	
					$currentPrice = array('lowest_price' => null, 'highest_price' => null);
					foreach ($variations as $idx => $variation_item) {
						$variation_asin = isset($variation_item['ASIN']) ? $variation_item['ASIN'] : '';
						$variation_details = $this->_cross_sell_get_prod_fields( $variation_item, array('is_variation_child' => true) );

						if ( ! empty($variation_details) ) {
							$retProd['variations']["$variation_asin"] = $variation_details;
							
							//first variation
							if ( empty($retProd['variations_filtered']['first']) ) {
								$retProd['variations_filtered']['first'] = $variation_asin;
							}
							
							// compare prices so we can choose lowest price & highest price variation
							if ( is_null($currentPrice['lowest_price']) || ( $currentPrice['lowest_price'] > (float) $variation_details['price'] ) ) {
								$currentPrice['lowest_price'] = (float) $variation_details['price'];
								$retProd['variations_filtered']['lowest_price'] = $variation_asin;
							}
							if ( is_null($currentPrice['highest_price']) || ( $currentPrice['highest_price'] < (float) $variation_details['price'] ) ) {
								$currentPrice['highest_price'] = (float) $variation_details['price'];
								$retProd['variations_filtered']['highest_price'] = $variation_asin;
							}
						}
					} // end foreach variations
					
					// keep only necessary variations (optimization)
					$varKeep = array();
					foreach ($retProd['variations_filtered'] as $varAsin) {
						if ( ! empty($varAsin) ) {
							$varKeep["$varAsin"] = $retProd['variations']["$varAsin"];
						}
					}
					$retProd['variations'] = $varKeep;
				}
			}

			// :: product large image
			$retProd['thumb'] = isset($thisProd['SmallImage'], $thisProd['SmallImage']['URL'])
				? $thisProd['SmallImage']['URL'] : '';
			if ( empty($retProd['thumb']) ) {
				// Images
				$images = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->build_images_data( $thisProd );
				if ( empty($images['small']) ) {
					// no images found - if has variations, try to find first image from variations
					$images = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->get_first_variation_image( $thisProd );
				}
				if ( isset($images['small']) && !empty($images['small']) ) {
					$retProd['thumb'] = $images['small'][0];
				}
			}

			// :: product price
			$prodprice = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->get_product_price(
				$thisProd,
				null,
				array()
			);
			$retProd['price'] = $prodprice['_price'];
			$isValid_price = false;
			if ( trim($retProd['price']) != '' && (float) $retProd['price'] > '0.00' ) {
				//$retProd['price'] = number_format($retProd['price'], 2);
				$isValid_price = true;
			}

			// :: validation
			$isValid = true;
			// remove if don't have valid price
			if ( ! $isValid_price ) {
				$isValid = false;
			}
			else if ( isset($retProd['is_variable']) && 'Y' == $retProd['is_variable'] && empty( $retProd['variations'] ) ) {
				$isValid = false;
			}
			//var_dump('<pre>', $retProd, '</pre>'); 

			return $isValid ? $retProd : array();
		}

		public function _cross_sell_calc_tries( $nb_tries, $nb_tries_orig, $force_update ) {
			$ret = array('query' => '', 'nb' => $nb_tries);

			$ret['query'] = '';
			if ( $force_update ) ; // don't count tries if you force update
			else {
				if ( 'inc' == $nb_tries ) {
					$ret['query'] = ', nb_tries = nb_tries + 1';
				}
				else {
					$ret['query'] = ', nb_tries = '.$nb_tries;
				}
				$ret['query'] = ' '.$ret['query'].' ';
			}

			// here because of force_update case above
			if ( 'inc' == $nb_tries ) {
				$ret['nb'] = $nb_tries_orig + 1;
			}

			return $ret;
		}

		public function _cross_sell_debug_msg( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'msg'			=> '',
				'msg_extra'	=> array(),
			), $pms);
			extract($pms);

			$html = array();
			if ( '' != $msg ) {
				$html[] = '<div>' . $msg . '</div>';
			}
			if ( ! empty($msg_extra) && is_array($msg_extra) ) {

				$from_cache = isset($msg_extra['from_cache']) && $msg_extra['from_cache'] ? true : false;
				unset($msg_extra['from_cache']);

				$html[] = '<div>';
				$html[] = 		'<table>';
				$html[] = 			'<thead>';
				$html[] =				'<tr>';
				foreach ($msg_extra as $key => $val) {
					$html[] = 				'<th>' . str_replace('_', ' ', $key) . '</th>';
				}
				$html[] =				'</tr>';
				$html[] = 			'</thead>';
				$html[] = 			'<tbody>';
				$html[] = 				'<tr>';
				foreach ($msg_extra as $key => $val) {
					$html[] = 				'<td>' . $val . '</td>';
				}
				$html[] = 				'</tr>';
				$html[] = 			'</tbody>';
				$html[] = 		'</table>';
				$html[] = '</div>';
				
				if ( $from_cache ) {
					$html[] = '<div><button>empty cache</button></div>';
				}
			}

			return implode(PHP_EOL, $html); 
		}

		public function _cross_sell_empty_cache( $pms=array() ) {
			extract($pms);

			$db = $this->the_plugin->db;

			$asin = (string) $asin;

			$query = "DELETE FROM " . ( $db->prefix ) . "amz_cross_sell WHERE ASIN = %s;";
			$query = $db->prepare( $query, $asin );
			return $db->query( $query );
		}



		//====================================================
		//== AMAZON REVIEWS

		/**
		 * Amazon Reviews
		 */
		// Write the custom tab on the product view page.  In WooCommerce these are handled by templates.
		public function amazon_reviews_custom_product_tabs( $tabs=array() ) {
			global $product;

			if ($this->amazon_reviews_product_has_custom_tabs($product)) {

				$priority = 15;

				foreach ($this->woo_tab_data as $tab) {

					$tabs[ $tab['id'] ] = array(
						'title'    => __( WooZone()->_translate_string( 'Amazon Customer Reviews' ), $this->localizationName),
						'priority' => $priority,
						'callback' => array($this, 'amazon_reviews_product_review_tab')
					);
				} // end foreach
			}

			return $tabs;
		}

		public function amazon_reviews_product_review_tab( $tab ) {
			global $product;

			if ( $this->amazon_reviews_product_has_custom_tabs($product) ) {
				$content = $this->woo_tab_data[0]['content'];

				$prod_id = 0;
				if ( is_object($product) ) {
					if ( method_exists( $product, 'get_id' ) ) {
						$prod_id = (int) $product->get_id();
					} else if ( isset($product->id) && (int) $product->id > 0 ) {
						$prod_id = (int) $product->id;
					}
				}

				//$content = ''; //DEBUG
				echo '<div id="amzaff-amazon-review-tab" data-prodid="' . $prod_id . '">' . $content . '</div>';
			}
		}

		public function amazon_reviews_product_review_tab_ajax( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'prodid' 		=> 0,
			), $pms);
			extract( $pms );

			$ret = array(
				'status' 	=> 'invalid',
				'html' 		=> '',
			);

			if ( $this->amazon_reviews_product_has_custom_tabs($prodid) ) {

				$content = $this->woo_tab_data[0]['content'];

				preg_match('/src="([^"]+)"/', $content, $match);
				$url = (string) $match[1];
				$url = trim( $url );

				if ( $url != "" ) {
					// now try to parse the string
					parse_str( $url, $params );

					// verify if link expire 
					if ( trim($params['exp']) != "" ) {
						$expire_on = strtotime($params['exp']);

						if ( time() > $expire_on ) {
							// need to update the amazon review iframe
							//global $post;

							//$post_id = (int) $post->ID > 0 ? $post->ID : 0;
							$post_id = $prodid;
							if( $post_id > 0 ){
								$new_url = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->updateProductReviews( $post_id );
								$new_url = trim( $new_url );

								// update the url into content iframe tag
								if ( '' != $new_url ) {
									$content = str_replace( $url, $new_url, $content);
								}

								$content = str_replace( "http://", "//", $content );
								$content = str_replace( "https://", "//", $content );

								$ret = array_replace_recursive( $ret, array(
									'status' 	=> 'valid',
									'html' 		=> $content,
								));
							}
						}

						// DEBUG!
						//var_dump('<pre>', date( "F j, Y, g:i a", strtotime($params['exp'])),'</pre>'); die;  
					}
				} // end if url

				//echo str_replace( "http://", "//", $content );
				//echo str_replace( "https://", "//", $content );
			}

			return $ret;
		}

		// Lazy-load the product_tabs meta data, and return true if it exists, false otherwise
		// @return true if there is custom tab data, false otherwise
		private function amazon_reviews_product_has_custom_tabs( $product ) {
			if ( $this->woo_tab_data === false ) {
				$prod_id = 0;
				if ( is_object($product) ) {
					if ( method_exists( $product, 'get_id' ) ) {
						$prod_id = (int) $product->get_id();
					} else if ( isset($product->id) && (int) $product->id > 0 ) {
						$prod_id = (int) $product->id;
					}
				}
				else if ( ! is_array($product) ) {
					$prod_id = (int) $product;
				}

				$reviews = get_post_meta( $prod_id, 'amzaff_woo_product_tabs', true );
				$reviews = maybe_unserialize( $reviews );

				if ( isset($reviews, $reviews[0]) ) {
					$this->woo_tab_data[] = $reviews[0];
				}
				else {
					//$this->woo_tab_data[] = array('content' => '');
					return false;
				}
			}

			// tab must have content to be considered valid
			$ret = !empty($this->woo_tab_data)
					&& isset($this->woo_tab_data[0]) && !empty($this->woo_tab_data[0])
					&& isset($this->woo_tab_data[0]['content']) && !empty($this->woo_tab_data[0]['content']);
			return $ret;
		}



		//====================================================
		//== OTHERS

		/**
		 * OTHERS
		 */
		// woocommerce fix thumb for remote images with https - on frontend
		public function woocommerce_before_mini_cart() {
			echo '<div style="display: none;" class="WooZone-fix-minicart"></div>';
		}

		public function action_do_bitly_request() {
			global $product;

			//:: bitly account must be connected to plugin
			$access_token = get_option( 'WooZone_bitly_access_token', '' );
			// bitly access token wasn\'t found!
			if ( '' == $access_token ) {
				return true;
			}

			//:: other conditions
			if (
				$this->product_buy_is_amazon_url
				&& $this->product_url_short
				&& ( 'external' == $this->p_type )
				&& is_product()
			) {
				if ( ! is_object( $product) ) {
					$product = wc_get_product( get_the_ID() );
				}

				if ( ! is_object( $product) ) {
					return true;
				}

				$this->the_plugin->product_url_from_bitlymeta(array(
					'ret_what' 	=> 'do_request',
					'product' 	=> $product,
				));
			}
		}

		public function action_do_product_page() {
			global $product;

			//:: product info
			if ( ! is_product() ) {
				return true;
			}

			if ( ! is_object( $product) ) {
				$product = wc_get_product( get_the_ID() );
			}

			if ( ! is_object( $product) ) {
				return true;
			}

			$prod_id = 0;
			if ( is_object($product) ) {
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
			}

			$product_type = '';
			if ( is_object($product) ) {
				if ( method_exists( $product, 'get_type' ) ) {
					$product_type = (string) $product->get_type();
				} else if ( isset($product->product_type) && (string) $product->product_type > 0 ) {
					$product_type = (string) $product->product_type;
				}
			}

			//:: frontend synchronization
			if ( 'yes' == $this->syncfront_activate ) {

				// is amazon product?
				$redirect_asin = get_post_meta($prod_id, '_amzASIN', true);
				if ( empty($redirect_asin) ) {
					return true;
				}

				// build sync wrapper
				$this->syncfront_args = array(
					'asin' 			=> $redirect_asin,
					'product_id' 	=> $prod_id,
					'product_type' 	=> $product_type,
					'product' 		=> $product,
				);
				add_action( 'WooZone_frontend_footer', array( $this, 'syncfront_wrapper' ), 1 );
			}

			//:: external product
			if ( 'external' == $product_type ) {

				$this->the_plugin->product_url_from_bitlymeta(array(
					'ret_what' 	=> 'do_request',
					'product' 	=> $product,
				));

				add_filter('woocommerce_product_single_add_to_cart_text', array($this->the_plugin, '_product_buy_text'));
				add_filter('woocommerce_product_add_to_cart_text', array($this->the_plugin, '_product_buy_text'));

				if( $this->product_buy_is_amazon_url ) {
					add_filter( 'get_post_metadata', array($this->the_plugin, 'get_post_metadata'), 999, 4 );
				}
			}
		}



		//====================================================
		//== BADGES

		// removed from 3.0, used in older versions of woocommerce as 2.X
		// image_string: sprintf('<li>%s</li>', $image)
		public function badges_show_onproduct( $image_string, $product_id ) {

			//return $image_string; //DEBUG;

			// only one copy allowed
			//var_dump('<pre>jimmy',$image_string, strpos( $image_string, 'wzfront-badges-wrapper' ) ,'</pre>');
			if ( strpos( $image_string, 'wzfront-badges-wrapper' ) > 0 ) {
				return $image_string;
			}

			$product = wc_get_product( $product_id );
			if ( !$product ) {
				return $image_string;
			}

			$badge_content = $this->badges_get_template( $product_id );

			if ( empty( $badge_content ) ) {
				return $image_string;
			}

			$badge_content = '<div class="wzfront-badges-wrapper">' . $image_string . $badge_content . '</div>';
			//var_dump('<pre>badge_content',$badge_content ,'</pre>');

			return $badge_content;
		}

		public function badges_show_onproduct_thumbnail( $image_string, $thumb_id ) {

			//return $image_string; //DEBUG;

			global $product;

			if ( did_action( 'woocommerce_product_thumbnails' ) || ! $product ) {
				return $image_string;
			}

			// image string
			// <div data-thumb="{image_url_thumb}" class="woocommerce-product-gallery__image"><a href="{image_url}"><img width="350" height="350" src="{image_url}" class="wp-post-image" alt="" title="" data-caption="" data-src="{image_url}" data-large_image="{image_url}" data-large_image_width="500" data-large_image_height="500" srcset="{image_url} 500w, {image_url_size1} 160w, {image_url_size2} 110w" sizes="100vw" /></a></div>

			//:: get product id
			$prod_id = 0;
			if ( is_object($product) ) {
				$key = $product->is_type( 'variation' ) ? 'parent_id' : 'id';
				$key_ = "get_$key";
				if ( method_exists( $product, $key_ ) ) {
					$prod_id = (int) $product->$key_();
				} else if ( isset($product->$key) ) {
					$prod_id = (int) $product->$key;
				}
			}
			$product_id = $prod_id;

			if ( ! $product_id ) {
				return $image_string;
			}

			//:: show badge
			$show_it = false;
			$div_close = '</div>';

			if ( version_compare( WC()->version, '3.0', '>=' )
				&& get_theme_support( 'wc-product-gallery-slider' )
				&& preg_match('~</div>$~imu', $image_string) !== false
			) {
				$show_it = true;
			}

			if ( $show_it ) {

				$image_string = substr( $image_string, 0, -strlen( $div_close ) );

				$badge_content = $this->badges_get_template( $product_id );
				$image_string .= $badge_content;

				$image_string .= $div_close;
			}
			else {
				$image_string = $this->show_badge_on_product( $image_string, $product_id );
			}
			//var_dump('<pre>', $image_string , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			return $image_string;
		}

		private function badges_get_template( $product_id, $pms=array() ) {

			if ( empty($this->the_plugin->badges_activated) ) {
				return '';
			}

			//:: box position & offsets
			$badges_box_position = 
				isset($this->amz_settings['badges_box_position'])
					? (string) $this->amz_settings['badges_box_position'] : 'top_left';

			$badges_box_offset_vertical = 
				isset($this->amz_settings['badges_box_offset_vertical'])
					? (int) $this->amz_settings['badges_box_offset_vertical'] : 0;

			$badges_box_offset_horizontal = 
				isset($this->amz_settings['badges_box_offset_horizontal'])
					? (int) $this->amz_settings['badges_box_offset_horizontal'] : 0;

			$box_style = array();
			switch ($badges_box_position) {

				case 'top_left':

					$box_style[] = 'top: ' . $badges_box_offset_vertical . 'px;';
					$box_style[] = 'left: ' . $badges_box_offset_horizontal . 'px;';
					break;

				case 'top_right':

					$box_style[] = 'top: ' . $badges_box_offset_vertical . 'px;';
					$box_style[] = 'right: ' . $badges_box_offset_horizontal . 'px;';
					break;

				case 'bottom_left':

					$box_style[] = 'bottom: ' . $badges_box_offset_vertical . 'px;';
					$box_style[] = 'left: ' . $badges_box_offset_horizontal . 'px;';
					break;

				case 'bottom_right':

					$box_style[] = 'bottom: ' . $badges_box_offset_vertical . 'px;';
					$box_style[] = 'right: ' . $badges_box_offset_horizontal . 'px;';
					break;
			}
			$box_style = implode( ' ', $box_style );
			$box_css_class = $badges_box_position;
			//var_dump('<pre>', $box_style , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;


			//:: badges activated
			//var_dump('<pre>',$this->the_plugin->badges_activated ,'</pre>');
			$newproduct = false;
			if ( in_array('new', $this->the_plugin->badges_activated) ) {
				$newproduct = $this->the_plugin->product_badge_is_new( $product_id );
			}

			$onsale = false;
			if ( in_array('onsale', $this->the_plugin->badges_activated) ) {
				$onsale = $this->the_plugin->product_badge_is_onsale( $product_id );
			}

			$freeshipping = false;
			if ( in_array('freeshipping', $this->the_plugin->badges_activated) ) {
				//$freeshipping = $this->the_plugin->product_badge_is_freeshipping( $product_id );
				$freeshipping = $this->the_plugin->is_product_freeshipping( $product_id );
			}

			$amazonprime = false;
			if ( in_array('amazonprime', $this->the_plugin->badges_activated) ) {
				//$amazonprime = $this->the_plugin->product_badge_is_amazonprime( $product_id );
				$amazonprime = $this->the_plugin->is_product_amazonprime( $product_id );
			}

			$__ = compact( 'newproduct', 'onsale', 'freeshipping', 'amazonprime' );
			//var_dump('<pre>', $product_id, $__ , '</pre>');

			$is_found = false;
			$is_found = $is_found || $newproduct;
			$is_found = $is_found || $onsale;
			$is_found = isset($freeshipping['status']) ? $is_found || $freeshipping['status'] : $is_found || $freeshipping;
			$is_found = isset($amazonprime['status']) ? $is_found || $amazonprime['status'] : $is_found || $amazonprime;

			if ( ! $is_found ) {
				return '';
			}


			//:: get template
			$badge_content = WooZone_get_template_html( 'badges/badges.php', array_replace_recursive( array(
				'product_id' 				=> $product_id,
				'box_style' 				=> $box_style,
				'box_css_class' 			=> $box_css_class,

				'product_is_new' 			=> $newproduct,
				'product_is_onsale'			=> $onsale,
				'product_is_freeshipping' 	=> isset($freeshipping['status']) ? $freeshipping['status'] : false,
				'freeshipping_link' 		=> isset($freeshipping['link']) ? $freeshipping['link'] : '',
				'product_is_amazonprime' 	=> isset($amazonprime['status']) ? $amazonprime['status'] : false,
				'amazonprime_link' 			=> isset($amazonprime['link']) ? $amazonprime['link'] : '',
			), $pms ));

			return $badge_content;
		}

		public function woocommerce_sale_flash( $html, $post, $product ) {
			if ( 'yes' == $this->the_plugin->frontend_hide_onsale_default_badge ) {
				return '';
			}
			return $html;
		}



		//====================================================
		//== SYNCHRONIZATION ON FRONTEND - by ajax
		public function init_sync_settings() {
			$ss = get_option($this->alias . '_sync', array());
			$ss = maybe_unserialize($ss);
			$ss = $ss !== false ? $ss : array();
			$ss = array_merge(array(
				'sync_products_per_request'				=> 50, // Products to sync per each cron request
				'sync_hour_start'						=> '',
				'sync_recurrence'						=> 24,
				'sync_fields'							=> array(),
			), $ss);

			$this->sync_settings = $ss;
			return $this->sync_settings;
		}

		public function init_sync_options() {
			$ss = get_option($this->alias . '_sync_options', array());
			$ss = maybe_unserialize($ss);
			$ss = $ss !== false ? $ss : array();
			$ss = array_merge(array(
				'interface_max_products' => 'all',
			), $ss);

			$this->sync_options = $ss;
			return $this->sync_options;
		}

		public function syncfront_wrapper() {

			$pms = array_replace_recursive(array(
				'asin' 			=> '',
				'product_id' 	=> 0,
				'product_type' 	=> '',
				'product' 		=> null,
			), $this->syncfront_args);
			extract( $pms );
			//var_dump('<pre>', $pms , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$refresh_in = $this->the_plugin->ss['sync_frontend_refresh_page_sec'];

			$prods2meta = array();
			//$prods2meta['_amzASIN'] = $asin;

			$__meta_toget = array('_amzaff_sync_last_date');
			$prods2meta = $prods2meta + $this->the_plugin->get_product_metas( $product_id, $__meta_toget, array() );

			//:: do we need to synced it?
			$is_sync_needed = $this->the_plugin->syncproduct_is_sync_needed( array(
				'recurrence' => (int) ( $this->sync_settings['sync_recurrence'] * 3600 ),
				'product_id' => $product_id,
				'sync_last_date' => $prods2meta['sync_last_date'],
			));

			//:: do we need to load ajax?
			$do_ajax = 'no';
			$do_msg = sprintf( '%s product : no need to synced it', $product_type );
			if ( $is_sync_needed ) {
				$do_ajax = 'yes';
				$do_msg = sprintf( '%s product : recurrence condition for last sync date is met', $product_type );
			}

			// simple product
			if ( 'simple' == $product_type ) {
			}
			// variable product
			else if ( 'variable' == $product_type ) {
				// because variable parent product could have sync_last_date updated as synced by the cronjob (or from sync admin interface) before it's variation childs were synced
				//$do_ajax = 'yes';
				//$do_msg .= ' - always make an ajax request for this product type';
			}
			// external product
			else if ( 'external' == $product_type ) {
				//TODO???
			}
			// grouped product
			else if ( 'grouped' == $product_type ) {
				//TODO???
			}
			//$do_ajax = 'yes'; //DEBUG SYNC

			$jsPms = array(
				'do_ajax' 		=> $do_ajax,
				'do_msg' 		=> $do_msg,
				'asin' 			=> $asin,
				'product_id' 	=> $product_id,
				'product_type' 	=> $product_type,
				'refresh_in' 	=> $refresh_in,
			);

			$html = array();

			//:: main wrapper
			$html[] = '<div id="WooZone-syncfront-wrapper" class="WooZone-syncfront-wrapper" style="display: none;">';
			$html[] = 	'<div class="WooZone-syncfront-params" style="display: none;">' . json_encode( $jsPms ) . '</div>';
			$html[] = 	'<div id="WooZone-syncfront-content">';
			$html[] = 		'<h1>';
			$html[] = 		sprintf( __('We\'ve just updated this product information. The page will auto refresh in about <span>%s</span> seconds.', 'WooZone'), $refresh_in );
			$html[] = 		'</h1>';
			$html[] = 		'<div class="WooZone-syncfront-btn">';
			$html[] = 			'<input type="button" value="Refresh page now" class="WooZone-form-button-small WooZone-form-button-success WooZone-syncfront-action-refresh-yes">';
			$html[] = 			'<input type="button" value="Cancel page refresh" class="WooZone-form-button-small WooZone-form-button-danger WooZone-syncfront-action-refresh-no">';
			$html[] = 		'</div>';
			$html[] = 	'</div>';
			$html[] = '</div>';
			// end #WooZone-syncfront-wrapper

			//:: debug wrapper
			$opGetDebug = $this->syncfront_wrapper_debug( array(
				'asin' 			=> $asin,
				'product_id' 	=> $product_id,
				'product_type' 	=> $product_type,
				'prods2meta' 	=> $prods2meta,
			));
			if ( ! empty($opGetDebug['html']) ) {
				//$html[] = $opGetDebug['html'];
			}
			WooZone_debugbar()->add2bar_row( 'woozone-debugbar-sync-frontend', $opGetDebug['html'], array() );
			WooZone_debugbar()->add2bar_menu( 'woozone-debugbar-sync-frontend', __('Product Synchronization', 'woozone'), array() );
			WooZone_debugbar()->add2bar_menua( 'woozone-debugbar-sync-frontend', __('Product Synchronization', 'woozone'), array() );

			$html = implode( PHP_EOL, $html );
			echo $html;
		}

		public function syncfront_wrapper_debug( $pms=array() ) {
			global $wpdb;

			$pms = array_replace_recursive(array(
				'asin' 			=> '',
				'product_id' 	=> 0,
				'product_type' 	=> '',
				'prods2meta' 	=> array(),
			), $pms);
			extract( $pms );
			//var_dump('<pre>', $pms , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$ret = array(
				'html' 	=> '',
			);

			//:: debug wrapper
			//if ( $this->the_plugin->is_debug_mode_allowed() ) {

				$opLastSyncStats = $this->syncfront_wrapper_debug_lastsync( array(
					'asin' 			=> $asin,
					'product_id' 	=> $product_id,
					'product_type' 	=> $product_type,
					'prods2meta' 	=> $prods2meta,
				));
				extract( $opLastSyncStats );

				$html = array();
				$html[] = '<div id="WooZone-syncfront-debug" class="WooZone-syncfront-debug" style="display: none;">';
				$html[] = '<table>';
				$html[] = 	'<thead>';
				$html[] = 		'<tr>';
				$html[] = 			'<th>';
				$html[] = 				'Time';
				$html[] = 			'</th>';
				$html[] = 			'<th>';
				$html[] = 				'Operation';
				$html[] = 			'</th>';
				$html[] = 		'</tr>';
				$html[] = 	'</thead>';
				$html[] = 	'<tbody>';
				$html[] = 		$text_last_sync_niceinfo_html;
				$html[] = 		$text_last_sync_status_html;
				$html[] = 		$text_last_sync_date_html;
				$html[] = 		$text_product_info_html;
				$html[] = 	'</tbody>';
				$html[] = 	'<tfoot>';
				$html[] = 	'</tfoot>';
				$html[] = '</table>';
				$html[] = '</div>';
				// end #WooZone-syncfront-debug

				$ret['html'] = implode( PHP_EOL, $html );
			//}
			return $ret;
		}

		public function syncfront_wrapper_debug_lastsync( $pms=array() ) {
			global $wpdb;

			$pms = array_replace_recursive(array(
				'asin' 			=> '',
				'product_id' 	=> 0,
				'product_type' 	=> '',
				'prods2meta' 	=> array(),
				'recurrence' 	=> $this->sync_settings['sync_recurrence'],
				'text_sync' 	=> 'last sync',
			), $pms);
			extract( $pms );
			//var_dump('<pre>', $pms , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$ret = array();

			$__meta_toget = array('_amzaff_sync_last_date', '_amzaff_sync_hits', '_amzaff_sync_last_status', '_amzaff_sync_last_status_msg', '_amzaff_sync_trash_tries', '_amzaff_country', '_amzaff_sync_current_cycle');
			$prods2meta = $prods2meta + $this->the_plugin->get_product_metas( $product_id, $__meta_toget, array() );

			$row = array_replace_recursive( array(
				'sync_hits' => 0,
				'sync_last_status' => '',
				'sync_last_status_msg' => '',
				'sync_trash_tries' => 0,
				'sync_import_country' => '',
				'sync_current_cycle' => '',
				'first_updated_date' => '',
			), $prods2meta);

			$row["sync_last_status"] = $this->the_plugin->syncproduct_sanitize_last_status(
				$row["sync_last_status"]
			);
			$row["sync_last_status_msg"] = maybe_unserialize( $row["sync_last_status_msg"] );

			$sync_import_country = $row["country"];
			if ( '' != $sync_import_country ) {
				$country_flag = $this->the_plugin->get_product_import_country_flag( array(
					'country' 	=> $sync_import_country,
					'asin' 		=> $asin,
				));
				$sync_import_country = $country_flag['image_link'];
			}
			$row['sync_import_country'] = $sync_import_country;

			$first_updated_date = (int) get_option('WooZone_sync_first_updated_date', 0);
			$row['first_updated_date'] = $first_updated_date;

			//var_dump('<pre>', $row , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$sync_last_stats_column = $this->the_plugin->syncproduct_build_last_stats_column( array(
				'asin' => $asin,
				'sync_nb' => $row['sync_hits'],
				'sync_last_status' => $row['sync_last_status'],
				'sync_last_status_msg' => $row['sync_last_status_msg'],
				'sync_trash_tries' => $row['sync_trash_tries'],
				'sync_import_country' => $row['sync_import_country'],
				'sync_current_cycle' => $row['sync_current_cycle'],
				'first_updated_date' => $row['first_updated_date'],
			));
			$text_last_sync_niceinfo = $sync_last_stats_column['text_last_sync_niceinfo'];

			$text_last_sync_date = sprintf(
				'<u>%s date</u>: %s <br />recurrence: %s hour(s)',
				$text_sync,
				! empty($row['sync_last_date']) ? $this->the_plugin->last_update_date('true', $row['sync_last_date']) : 'none',
				$recurrence
			);

			$text_last_sync_status = sprintf(
				'<u>%s status</u>: %s',
				$text_sync,
				strtoupper($row['sync_last_status']) . '<br />' . $sync_last_stats_column['text_last_sync_title']
			);


			$text_product_info = array();
			$text_product_info[] = '<u>product info</u>';
			$text_product_info[] = 'product #ID: ' . $product_id;
			$text_product_info[] = 'product Asin: ' . $asin;
			$text_product_info[] = 'product Type: ' . $product_type;
			$text_product_info = implode('<br />', $text_product_info);


			//:: HTML
			$html = array();
			$html[] = 		'<tr class="wzsync-update-time">';
			$html[] = 			'<td>';
			$html[] = 				'00:00:00';
			$html[] = 			'</td>';
			$html[] = 			'<td>';
			$html[] = 				$text_product_info;
			$html[] = 			'</td>';
			$html[] = 		'</tr>';

			$ret['text_product_info'] = $text_product_info;
			$ret['text_product_info_html'] = implode( PHP_EOL, $html );


			$html = array();
			$html[] = 		'<tr class="wzsync-update-time">';
			$html[] = 			'<td>';
			$html[] = 				'00:00:00';
			$html[] = 			'</td>';
			$html[] = 			'<td>';
			$html[] = 				$text_last_sync_date;
			$html[] = 			'</td>';
			$html[] = 		'</tr>';

			$ret['text_last_sync_date'] = $text_last_sync_date;
			$ret['text_last_sync_date_html'] = implode( PHP_EOL, $html );


			$html = array();
			$html[] = 		'<tr class="wzsync-update-time">';
			$html[] = 			'<td>';
			$html[] = 				'00:00:00';
			$html[] = 			'</td>';
			$html[] = 			'<td>';
			$html[] = 				$text_last_sync_status;
			$html[] = 			'</td>';
			$html[] = 		'</tr>';

			$ret['text_last_sync_status'] = $text_last_sync_status;
			$ret['text_last_sync_status_html'] = implode( PHP_EOL, $html );


			$html = array();
			$html[] = 		'<tr class="wzsync-update-time">';
			$html[] = 			'<td>';
			$html[] = 				'00:00:00';
			$html[] = 			'</td>';
			$html[] = 			'<td class="wzsync-last-sync-info-wrapper">';
			$html[] = 				$text_last_sync_niceinfo;
			$html[] = 			'</td>';
			$html[] = 		'</tr>';

			$ret['text_last_sync_niceinfo'] = $text_last_sync_niceinfo;
			$ret['text_last_sync_niceinfo_html'] = implode( PHP_EOL, $html );

			return $ret;
		}



		//====================================================
		//== AJAX

		/**
		 * Ajax request
		 */
		public function ajax_requests()
		{
			$action = isset($_REQUEST['sub_action']) ? $_REQUEST['sub_action'] : 'none';
	
			$allowed_action = array( 'save_countries', 'save_product_country', 'load_cross_sell', 'cross_sell_empty_cache', 'load_amazon_reviews', 'do_sync' );

			if( !in_array($action, $allowed_action) ){
				die(json_encode(array(
					'status'	=> 'invalid',
					'html'		=> 'Invalid action!'
				)));
			}

			if ( 'save_countries' == $action ) {
				$req = array(
					'product_id'			=> isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : 0,
					'product_country'		=> isset($_REQUEST['product_country']) ? trim( $_REQUEST['product_country'] ) : 0,
					'countries'				=> isset($_REQUEST['countries']) ? stripslashes(trim( $_REQUEST['countries'] )) : '',
				);
				extract($req);
				//var_dump('<pre>', $req, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				
				$countries = json_decode( $countries, true );
				if ( $countries ) {
					foreach ($countries as $key => $val) {
						unset($countries["$key"]['name']);
					}
				}
				//var_dump('<pre>', $countries, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				
				// save it
				if ( $product_id && $countries ) {
					$meta_value = array(
						'countries'							=> $countries,
						'countries_cache_time'		=> time(),
					);
					update_post_meta( $product_id, '_amzaff_frontend', $meta_value );
				}
				
				// get asin meta key
				$asin = get_post_meta($product_id, '_amzASIN', true);
				$first_variation_asin = $this->get_asin_first_variation( $product_id );
				if( $first_variation_asin !== false ){
					$asin = $first_variation_asin;
				}
				//var_dump('<pre>',$asin,'</pre>');
				
				// save product country
				$_SESSION['WooZone']['product_country']["$asin"] = $product_country;

				die(json_encode(array(
					'status'	=> 'valid',
					'html'		=> 'ok'
				)));
			}

			if ( 'save_product_country' == $action ) {
				$req = array(
					'product_id'			=> isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : 0,
					'product_country'	=> isset($_REQUEST['product_country']) ? trim( $_REQUEST['product_country'] ) : 0,
				);
				extract($req);
				//var_dump('<pre>', $req, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				
				// get asin meta key
				$asin = get_post_meta($product_id, '_amzASIN', true);
				$first_variation_asin = $this->get_asin_first_variation( $product_id );
				if( $first_variation_asin !== false ){
					$asin = $first_variation_asin;
				}
				//var_dump('<pre>',$asin,'</pre>');
				
				// save product country
				$_SESSION['WooZone']['product_country']["$asin"] = $product_country;

				die(json_encode(array(
					'status'	=> 'valid',
					'html'		=> 'ok'
				)));
			}
			
			if ( 'load_cross_sell' == $action ) {
				$req = array(
					'asin'			=> isset($_REQUEST['asin']) ? (string) $_REQUEST['asin'] : 0,
				);
				extract($req);
				//var_dump('<pre>', $req, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$boxRsp = $this->_cross_sell_box( array('asin' => $asin) );

				die(json_encode(array(
					'status'	=> 'valid',
					'html'		=> $boxRsp['html'],
					'debug'		=> $boxRsp['debug'],
				)));
			}
			
			if ( 'cross_sell_empty_cache' == $action ) {
				$req = array(
					'asin'			=> isset($_REQUEST['asin']) ? (string) $_REQUEST['asin'] : 0,
				);
				extract($req);
				//var_dump('<pre>', $req, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$this->_cross_sell_empty_cache( array('asin' => $asin) );

				die(json_encode(array(
					'status'		=> 'valid',
				)));
			}

			if ( 'load_amazon_reviews' == $action ) {
				$req = array(
					'prodid'			=> isset($_REQUEST['prodid']) ? (string) $_REQUEST['prodid'] : 0,
				);
				extract($req);
				//var_dump('<pre>', $req, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$boxRsp = $this->amazon_reviews_product_review_tab_ajax( array('prodid' => $prodid) );

				die(json_encode(array(
					'status'	=> $boxRsp['status'],
					'html'		=> $boxRsp['html'],
					//'debug'		=> $boxRsp['debug'],
				)));
			}

			// SYNCHRONIZATION
			if ( 'do_sync' == $action ) {
				$req = array(
					'product_id'		=> isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : 0,
					'asin'				=> isset($_REQUEST['asin']) ? trim( $_REQUEST['asin'] ) : 0,
					'product_type'		=> isset($_REQUEST['product_type']) ? trim( $_REQUEST['product_type'] ) : '',
				);
				extract($req);
				//var_dump('<pre>', $req, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$html = array();

				$sync_choose_country = isset($this->sync_options['sync_choose_country'])
					? $this->sync_options['sync_choose_country'] : 'default';

				if ( empty($asin) ) {
					$asin = get_post_meta($id, '_amzASIN', true);
				}
				//var_dump('<pre>',$asin,'</pre>');


				//:: sync product!
				// Initialize the wwcAmazonSyncronize class
				require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . '/modules/synchronization/init.php' );
				$syncObj = new wwcAmazonSyncronize($this->the_plugin);

				$syncProdPms = array();

				$country = '';
				if ( 'import_country' == $sync_choose_country ) {
					$country_db = get_post_meta($product_id, '_amzaff_country', true);
					if ( ! empty($country_db) && is_string($country_db) ) {
						$country = (string) $country_db;
					}
				}

				//$syncStat = $syncObj->syncprod_multiple_oldvers( array( $product_id => $asin ), $country, $syncProdPms );

				$syncProdPms = array_replace_recursive( $syncProdPms, array(
					//'use_cache' => true,
					'verify_sync_date' => true,
					'verify_sync_date_vars' => true,
					//'recurrence' => '',
				));

				//DEBUG SYNC - BYPASS LAST SYNC DATE
				/*
				$syncProdPms = array_replace_recursive( $syncProdPms, array(
					//'DEBUG' => true,
					'verify_sync_date' => false,
					'verify_sync_date_vars' => false,
				));
				*/

				$syncStat = $syncObj->syncprod_multiple( array( $product_id => $asin ), $country, $syncProdPms );
				$is_sync_needed = isset($syncStat['is_sync_needed'], $syncStat['is_sync_needed']["$product_id"])
					? $syncStat['is_sync_needed']["$product_id"] : true;
				//var_dump('<pre>', $syncStat , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				//$is_sync_needed = true; //DEBUG

				$html[] = $syncStat['msg'];

				$html = implode('<br />', $html);
				//var_dump('<pre>', $html , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;


				//:: current sync status
				$html_aftersync = '';
				if ( $is_sync_needed ) {
					$opLastSyncStats = $this->syncfront_wrapper_debug_lastsync( array(
						'asin' 			=> $asin,
						'product_id' 	=> $product_id,
						'product_type' 	=> $product_type,
						'prods2meta' 	=> array(),
						'text_sync' 	=> 'current sync',
					));
					extract( $opLastSyncStats );

					$html_aftersync = array();
					$html_aftersync[] = $text_last_sync_niceinfo_html;
					$html_aftersync[] = $text_last_sync_status_html;
					$html_aftersync[] = $text_last_sync_date_html;
					//$html_aftersync[] = $text_product_info_html;
					$html_aftersync = implode( PHP_EOL, $html_aftersync );
				}


				//:: needs refresh
				$do_refresh = 'no';
				if ( $is_sync_needed ) {
					$sync_last_status = get_post_meta($product_id, '_amzaff_sync_last_status', true);
					if ( 'updated' == $sync_last_status ) {
						$do_refresh = 'yes';
					}
				}
				//$do_refresh = 'yes'; //DEBUG SYNC

				die(json_encode(array(
					'status'	=> 'valid',
					'html'		=> $html,
					'html_aftersync' => $html_aftersync,
					'do_refresh' => $do_refresh,
				)));
			}

			die(json_encode(array(
				'status' 	=> 'invalid',
				'html'		=> 'Invalid action!'
			)));
		}
	}
}