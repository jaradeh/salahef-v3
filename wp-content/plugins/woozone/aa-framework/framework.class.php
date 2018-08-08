<?php
/**
 * AA-Team freamwork class
 * http://www.aa-team.com
 * =======================
 *
 * @package     WooZone
 * @author      AA-Team
 * @version     1.0
 */
! defined( 'ABSPATH' ) and exit;

use Leafo\ScssPhp\Compiler;
if(class_exists('WooZone') != true) {
	class WooZone {

		public $version = null; // see version method for details

		const VERSION = 1.0;

		// The time interval for the remote XML cache in the database (21600 seconds = 6 hours)
		const NOTIFIER_CACHE_INTERVAL = 21600;

		public $alias = 'WooZone';
		public $details = array();
		public $localizationName = 'woozone';

		public $dev = '';
		public $debug = false;
		public $is_admin = false;

		/**
		 * configuration storage
		 *
		 * @var array
		 */
		public $cfg = array();

		/**
		 * plugin modules storage
		 *
		 * @var array
		 */
		public $modules = null;

		/**
		 * errors storage
		 *
		 * @var object
		 */
		private $errors = null;

		/**
		 * DB class storage
		 *
		 * @var object
		 */
		public $db = array();

		public $facebookInstance = null;
		public $fb_user_profile = null;
		public $fb_user_id = null;

		private $plugin_hash = null;
		private $v = null;

		// Products Providers Helpers!
		public $genericHelper = null;
		public $amzHelper = null;
		
		public $jsFiles = array();
		
		public $wp_filesystem = null;
		
		private $opStatusMsg = array();
		
		public $charset = '';
		
		public $pluginDepedencies = null;
		public $pluginName = 'WooZone';
		
		public $feedback_url = "http://aa-team.com/feedback/index.php?app=%s&refferer_url=%s";
				
		public $amz_settings = array();

		public $u; // utils function object!
		public $pu; // utils function object!
		public $timer; // timer object
				
		public $cur_provider = 'amazon';

		public $duplicate_images = array();

		// New Settings / february 2016
		public $plugin_details = array(); // see constructor
		public $ss = array(
			// (false = no caching) DEBUG: don't cache client country in session: $_SESSION['WooZone_country']
			'cache_client_country'						=> true,
			
			// max allowed remote requests to aa-team demo server
			'max_remote_request_number'					=> 100, // -1 = DEBUG
			
			// max allowed number of products imported using aa-team demo keys
			'max_products_demo_keys'					=> 10, //default: 10
			
			// admin css cache time ( 0 = no caching )
			'css_cache_time'							=> 86400, // in seconds  (86400 seconds = 24 hours)
			
			// amazon country shops where product is available - cache time ( 0 = no caching )
			//'countries_cache_time'						=> 7200, // in seconds  (86400 seconds = 24 hours)
			'countries_cache_time'						=> 86400, // in seconds  (86400 seconds = 24 hours)

			// timeout to verify if all plugin tables are installed right!
			'check_integrity'							=> array(
				// seconds  (86400 seconds = 24 hours)
				'check_tables'								=> 259200, // 3 days
				'check_alter_tables'						=> 259200, // 3 days
				'check_cronjobs_prefix'						=> 86400, // 1 day
				'check_alter_table_amz_queue' 				=> 86400, // 1 day
				'check_table_amz_locale_reference' 			=> 86400, // 1 day
				'check_table_amz_amzkeys' 					=> 86400, // 1 day
				'check_table_amz_amazon_cache' 				=> 86400, // 1 day
			),

			// maximum number of variations to import per product
			'max_per_product_variations' 				=> 1000,

			// cronjob sync retries on error/throttled items
			'max_cron_sync_retries_onerror' 			=> 2,

			// frontend synchronization - the time to refresh the page when successfull sync
			'sync_frontend_refresh_page_sec'			=> 15, //360000, // in seconds

			// mysql expression for cached amazon requests (used for product synchronization)
			'sync_amazon_requests_cache_exp' 			=> 'INTERVAL 1 HOUR',

			// maximum number of images per each variation child (for variable products)
			'max_images_per_variation' 					=> 10,
		);

		private static $plugin_row_meta = array(
			'buy_url'           => 'http://codecanyon.net/item/woocommerce-amazon-affiliates-wordpress-plugin/3057503',
			'portfolio'         => 'http://codecanyon.net/user/aa-team/portfolio',
			'docs_url'          => 'http://docs.aa-team.com/products/woocommerce-amazon-affiliates/',
			'support_url'       => 'http://support.aa-team.com/',
			'latest_ver_url'    => 'http://cc.aa-team.com/apps-versions/index.php?app=',
		);

		private static $aateam_keys_script = 'http://cc.aa-team.com/woozone-keys/keys-woozone.php';
		public $sync_tries_till_trash = 3;
		public static $amazon_images_path = 'images-amazon.';
		public $is_remote_images = false;

		public $cacheit = array(); // cached amazon images from CDN & maybe other stuff...
		public $frontend; // frontend object!
		
		public $disable_amazon_checkout = false;

		public $plugin_tables = array('amz_assets', 'amz_cross_sell', 'amz_products', 'amz_queue', 'amz_report_log', 'amz_search', 'amz_locale_reference', 'amz_amzkeys', 'amz_amazon_cache');

		public $page;

		public $updater_dev = null;
		
		public $cached_product_terms = array();

		public $country2mainaffid = array(
			'com' 	=> 'com',
			'ca' 	=> 'ca',
			'cn' 	=> 'cn',
			'de' 	=> 'de',
			'in' 	=> 'in',
			'it' 	=> 'it',
			'es' 	=> 'es',
			'fr' 	=> 'fr',
			'co.uk' => 'uk',
			'co.jp' => 'jp',
			'com.mx'=> 'mx',
			'com.br'=> 'br',
			'com.au'=> 'au',
		);

		// init_plugin_attributes
		public $p_type = null;
		public $product_buy_is_amazon_url = null;
		public $product_url_short = null;
		public $import_product_offerlistingid_missing = null;
		public $import_product_variation_offerlistingid_missing = null;
		public $product_offerlistingid_missing_external = null;
		public $product_offerlistingid_missing_delete = null;
		public $products_force_delete = null;
		public $gdpr_rules_is_activated = null;
		public $frontend_hide_onsale_default_badge = null;
		public $frontend_show_free_shipping = null;
		public $badges_activated = array();
		public $badges_where = array();

		public $bitly_oauth_api = 'https://api-ssl.bitly.com/';

		public $wsStatus = array( 'status' => 'valid', 'exception' => null, 'msg' => '' );

		public $demokeysObj = null;
		public $amzkeysObj = null;

		public $debug_bar_activate = true;
		public $debugbar = null; // debug bar object

		public $directimport = null; // direct import object
		

		/**
		 * The constructor
		 */
		public function __construct($here = __FILE__)
		{
			if( defined('UPDATER_DEV') ) {
				$this->updater_dev = (string) UPDATER_DEV;
			}

			$this->fix_dbalias_issue(); // amzstore dbalias fix

			$this->update_developer();
			$this->is_admin = is_admin() === true ? true : false;

			// admin css cache time ( 0 = no caching )
			//$this->ss['css_cache_time'] = 86400; // seconds  (86400 seconds = 24 hours)
			if( defined('WOOZONE_DEV_STYLE') && WOOZONE_DEV_STYLE ){
				$this->ss['css_cache_time'] = (int) WOOZONE_DEV_STYLE; // seconds
			}
			if ( defined('WOOZONE_DEV_STYLE_GULP') && WOOZONE_DEV_STYLE_GULP ) {
				$this->ss['css_cache_time'] = -1; //always use cache
			}
			
			add_action('wp_ajax_WooZone_framework_style', array( $this, 'framework_style') );
			add_action('wp_ajax_nopriv_WooZone_framework_style', array( $this, 'framework_style') );
			
			// get all amazon settings options
			$this->settings();

			$this->init_plugin_attributes();

			//$current_url = $_SERVER['HTTP_REFERER'];
			$current_url = $this->get_current_page_url();
			$this->feedback_url = sprintf($this->feedback_url, $this->alias, rawurlencode($current_url));
 
			// load WP_Filesystem 
			include_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
			global $wp_filesystem;
			$this->wp_filesystem = $wp_filesystem;

			$this->plugin_hash = get_option('WooZone_hash');

			// set the freamwork alias
			$this->buildConfigParams('default', array( 'alias' => $this->alias ));

			// get the globals utils
			global $wpdb;

			// store database instance
			$this->db = $wpdb;

			// instance new WP_ERROR - http://codex.wordpress.org/Function_Reference/WP_Error
			$this->errors = new WP_Error();
			
			// charset
			if ( isset($this->amz_settings['charset']) && !empty($this->amz_settings['charset']) ) {
				$this->charset = $this->amz_settings['charset'];
			}

			// plugin root paths
			$this->buildConfigParams('paths', array(
				// http://codex.wordpress.org/Function_Reference/plugin_dir_url
				'plugin_dir_url' => str_replace('aa-framework/', '', plugin_dir_url( (__FILE__)  )),

				// http://codex.wordpress.org/Function_Reference/plugin_dir_path
				'plugin_dir_path' => str_replace('aa-framework/', '', plugin_dir_path( (__FILE__) ))
			));

			// add plugin lib frontend paths and url
			$this->buildConfigParams('paths', array(
				'frontend_dir_url' => $this->cfg['paths']['plugin_dir_url'] . 'lib/frontend',
				'frontend_dir_path' => $this->cfg['paths']['plugin_dir_path'] . 'lib/frontend'
			));
	 
			// add plugin scripts paths and url
			$this->buildConfigParams('paths', array(
				'scripts_dir_url' => $this->cfg['paths']['plugin_dir_url'] . 'lib/scripts',
				'scripts_dir_path' => $this->cfg['paths']['plugin_dir_path'] . 'lib/scripts'
			));

			// add plugin admin paths and url
			$this->buildConfigParams('paths', array(
				'freamwork_dir_url' => $this->cfg['paths']['plugin_dir_url'] . 'aa-framework/',
				'freamwork_dir_path' => $this->cfg['paths']['plugin_dir_path'] . 'aa-framework/'
			));

			// add core-modules alias
			$this->buildConfigParams('core-modules', array(
				'amazon',
				'dashboard',
				'modules_manager',
				'setup_backup',
				'server_status',
				'insane_import',
				'support',
				'assets_download',
				'stats_prod',
				'price_select',
				'amazon_debug',
				'woocustom',
				'cronjobs',
				'direct_import'
			));

			// list of freamwork css files
			$this->buildConfigParams('freamwork-css-files', array(
				'core' => 'css/core.css',
				'panel' => 'css/panel.css',
				'form-structure' => 'css/form-structure.css',
				'form-elements' => 'css/form-elements.css',
				'form-message' => 'css/form-message.css',
				'button' => 'css/button.css',
				'table' => 'css/table.css',
				//'tipsy' => 'css/tooltip.css',
				'tipsy' => 'js/tippyjs/tippy.min.css',
				'admin' => 'css/admin-style.css',
				'jquery.simplemodal' => 'js/jquery.simplemodal/basic.css',
			));

			// list of freamwork js files
			$this->buildConfigParams('freamwork-js-files', array(
				'admin'             => 'js/adminv9.js',
				'hashchange'        => 'js/hashchange.min.js',
				'ajaxupload'        => 'js/ajaxupload.js',
				//'tipsy'             => 'js/tooltip.js',
				'tipsy'             => 'js/tippyjs/tippy.min.js',
				'download_asset'    => '../modules/assets_download/app.assets_download.js',
				'counter'           => 'js/counter.js',
				'jquery.simplemodal' => 'js/jquery.simplemodal/jquery.simplemodal.1.4.4.min.js',
			));
			
			$this->version(); // set plugin version
			
			// DEBUG - use hola chrome extension to test different client countries
			//$this->debug_get_country();
			
			// plugin folder in wp-content/plugins/
			$plugin_folder = explode('wp-content/plugins/', $this->cfg['paths']['plugin_dir_path']);
			$plugin_folder = end($plugin_folder);
			$this->plugin_details = array(
				'folder'        => $plugin_folder,
				'folder_index'  => $plugin_folder . 'plugin.php',
			);

			// utils functions
			require_once( $this->cfg['paths']['plugin_dir_path'] . 'aa-framework/utils/utils.php' );
			if( class_exists('WooZone_Utils') ){
				// $this->u = new WooZone_Utils( $this );
				$this->u = WooZone_Utils::getInstance( $this );
			}
			
			// plugin utils functions
			require_once( $this->cfg['paths']['plugin_dir_path'] . 'aa-framework/utils/plugin_utils.php' );
			if( class_exists('WooZone_PluginUtils') ){
				// $this->pu = new WooZone_PluginUtils( $this );
				$this->pu = WooZone_PluginUtils::getInstance( $this );
			}

			// DEBUG BAR
			require_once( $this->cfg['paths']['scripts_dir_path'] . '/debugbar/debugbar.php' );
			if( class_exists('WooZoneDebugBar') ){
				//$this->debugbar = new WooZoneDebugBar();
				$this->debugbar = WooZoneDebugBar::getInstance( $this );
			}

			// DIRECT IMPORT
			require_once( $this->cfg['paths']['scripts_dir_path'] . '/directimport/directimport.php' );
			if( class_exists('WooZoneDirectImport') ){
				//$this->directimport = new WooZoneDirectImport();
				$this->directimport = WooZoneDirectImport::getInstance( $this );
			}

			// product updater
			add_action( 'admin_init', array($this, 'product_updater') );

			// get plugin text details
			$this->get_plugin_data();
			
			// timer functions
			require_once( $this->cfg['paths']['scripts_dir_path'] . '/runtime/runtime.php' );
			if( class_exists('aaRenderTime') ){
				//$this->timer = new aaRenderTime( $this );
				$this->timer = aaRenderTime::getInstance();
			}
			
			// mandatory step, try to load the validation file
			require_once( $this->cfg['paths']['plugin_dir_path'] . 'validation.php' );
			$this->v = new WooZone_Validation();
			$this->v->isReg($this->plugin_hash);
			
			require_once( $this->cfg['paths']['plugin_dir_path'] . 'aa-framework/menu.php' );

			// Run the plugins section load method
			add_action('wp_ajax_WooZoneLoadSection', array( $this, 'load_section' ));
		
			add_action('wp_ajax_WooZoneDismissNotice', array( $this, 'dismiss_notice' ));
			
			// Plugin Depedencies Verification!
			if (get_option('WooZone_depedencies_is_valid', false)) {
				require_once( $this->cfg['paths']['scripts_dir_path'] . '/plugin-depedencies/plugin_depedencies.php' );
				$this->pluginDepedencies = new aaTeamPluginDepedencies( $this );

				// activation redirect to depedencies page
				if (get_option('WooZone_depedencies_do_activation_redirect', false)) {
					add_action('admin_init', array($this->pluginDepedencies, 'depedencies_plugin_redirect'));
					return false;
				}
	 
					// verify plugin library depedencies
				$depedenciesStatus = $this->pluginDepedencies->verifyDepedencies();
				if ( $depedenciesStatus['status'] == 'valid' ) {
					// go to plugin license code activation!
					add_action('admin_init', array($this->pluginDepedencies, 'depedencies_plugin_redirect_valid'));
				} else {
					// create depedencies page
					add_action('init', array( $this->pluginDepedencies, 'initDepedenciesPage' ), 5);
					return false;
				}
			}
			
			// Run the plugins initialization method
			add_action('init', array( $this, 'initThePlugin' ), 5);
			add_action('init', array( $this, 'session_start' ), 1);
			//add_action('wp_logout', array( $this, 'session_close' ), 1);
			//add_action('wp_login', array($this, 'session_close' ), 1);

			// Run the plugins section options save method
			add_action('wp_ajax_WooZoneSaveOptions', array( $this, 'save_options' ));

			// Run the plugins section options save method
			add_action('wp_ajax_WooZoneModuleChangeStatus', array( $this, 'module_change_status' ));
			
			// Run the plugins section options save method
			add_action('wp_ajax_WooZoneModuleChangeStatus_bulk_rows', array( $this, 'module_bulk_change_status' ));

			// Run the plugins section options save method
			add_action('wp_ajax_WooZoneInstallDefaultOptions', array( $this, 'install_default_options' ));

			add_action('wp_ajax_WooZoneUpload', array( $this, 'upload_file' ));
			
			add_action('admin_init', array($this, 'plugin_redirect'));
			
			if( $this->debug == true ){
				add_action('wp_footer', array($this, 'print_plugin_usages') );
				add_action('admin_footer', array($this, 'print_plugin_usages') );
			}
			
			add_action( 'admin_init', array($this, 'product_assets_verify') );

			if(!$this->is_admin){
				add_action( 'init' , array( $this, 'frontpage' ) );

				// cross sell shortcode
				//add_shortcode( 'amz_corss_sell', array($this, 'cross_sell_box') );
			}
						
			if ( $this->is_admin ) {
				//add_action( 'admin_bar_menu', array($this->pu, 'update_notifier_bar_menu'), 1000 );
				//add_action( 'admin_menu', array($this->pu, 'update_plugin_notifier_menu'), 1000 );

				// add additional links below plugin on the plugins page
				add_filter( 'plugin_row_meta', array($this->pu, 'plugin_row_meta_filter'), 10, 2 );
		
				// alternative API to check updating for the filter transient
				//add_filter( 'pre_set_site_transient_update_plugins', array( $this->pu, 'update_plugins_overwrite' ), 10, 1 );
	 
				// alternative response with plugin details for admin thickbox tab
				//add_filter( 'plugins_api', array( $this->pu, 'plugins_api_overwrite' ), 10, 3 );
				
				// message on wp plugins page with updating link
				//add_action( 'in_plugin_update_message-'.$this->plugin_details['folder_index'], array($this->pu, 'in_plugin_update_message'), 10, 2 );

				if( isset($_GET['post_type']) && $_GET['post_type'] == 'product' ) {
					add_action( 'manage_posts_custom_column' , array( $this, 'add_demo_products_marker' ), 10, 2 );
				}
				
				// FIX: woocommerce product list image srcset wrong url
				//add_filter( 'max_srcset_image_width', create_function( '', 'return 1;' ) );
				add_filter( 'max_srcset_image_width', function () {
					return 1;
				});
			}

			require_once( $this->cfg['paths']['plugin_dir_path'] . 'aa-framework/ajax-list-table.php' );
			new WooZoneAjaxListTable( $this );
			
			// GENERIC Helper
			//if( 1 ){
			//  require_once( $this->cfg['paths']['plugin_dir_path'] . 'aa-framework/helpers/generic.helper.class.php' );
			//  
			//  if( class_exists('aiowaffGenericHelper') ){
			//      // $this->genericHelper = new WooZoneGenericHelper( $this );
			//      $this->genericHelper = WooZoneGenericHelper::getInstance( $this );
			//  }
			//}

			// aateam amazon keys - when client use aateam demo keys
			require_once( $this->cfg['paths']['plugin_dir_path'] . '_keys/demokeys.php' );
			$this->demokeysObj = new aaWoozoneDemoKeysLib( $this, array() );

			// multiple amazon keys
			require_once( $this->cfg['paths']['plugin_dir_path'] . '_keys/amzkeys.php' );
			$this->amzkeysObj = new aaWoozoneAmzKeysLib( $this );

			// when we've implemented amazon multiple keys - version 9.3
			$this->fix_multikeys_from_single();

			// AMAZON Helper            
			//if (
			//	isset($this->amz_settings['AccessKeyID'])
			//	&& isset($this->amz_settings['SecretAccessKey'])
			//	&& trim($this->amz_settings['AccessKeyID']) != ""
			//	&& trim($this->amz_settings['SecretAccessKey']) != ""
			//) {
				$this->amzHelper = $this->get_ws_object_new( $this->cur_provider, 'new_helper', array(
					'the_plugin' => $this,
				));
				//:: disabled on 2018-feb
				//require_once( $this->cfg['paths']['plugin_dir_path'] . 'aa-framework/amz.helper.class.php' );
				//if( class_exists('WooZoneAmazonHelper') ){
				//	// $this->amzHelper = new WooZoneAmazonHelper( $this );
				//	$this->amzHelper = WooZoneAmazonHelper::getInstance( $this );
				//}
				//:: end disabled on 2018-feb
			//}
			
			// cross sell checkout - !needs to be bellow Amazon helper
			//$this->cross_sell_checkout();
			
			// ajax download lightbox
			add_action('wp_ajax_WooZoneDownoadAssetLightbox', array( $this, 'download_asset_lightbox' ));
			
			// admin ajax action
			require_once( $this->cfg['paths']['plugin_dir_path'] . 'aa-framework/utils/action_admin_ajax.php' );
			new WooZone_ActionAdminAjax( $this );

			// admin ajax action
			require_once( $this->cfg['paths']['plugin_dir_path'] . 'modules/cronjobs/cronjobs.core.php' );
			new WooZoneCronjobs( $this );
			//WooZoneCronjobs::getInstance();

			// frontend class
			//if ( ! $this->is_admin ) {
				require_once( $this->cfg['paths']['plugin_dir_path'] . 'lib/frontend/frontend.class.php' );
				$this->frontend = WooZoneFrontend::getInstance( $this );
			//}

			$is_installed = get_option( $this->alias . "_is_installed" );
			if( $this->is_admin && $is_installed === false ) {
				add_action( 'admin_print_styles', array( $this, 'admin_notice_install_styles' ) );
			}

			if( isset($this->amz_settings['remove_featured_image_from_gallery']) && $this->amz_settings['remove_featured_image_from_gallery'] == 'yes' ){
				add_filter('woocommerce_single_product_image_thumbnail_html', array( $this, 'remove_featured_image' ), 10, 3);
				add_filter('woocommerce_single_product_image_html', array( $this, 'remove_featured_image' ), 10, 3);
			}

			// Cacheit
			//$this->cacheitInit(); // deactivated on 2017-08-16

			// remote amazon images
			if ( $this->is_remote_images ) {
				add_filter( "wp_get_attachment_url", array($this, '_attachment_url'), 0, 2); // deactivated on 2017-08-16
				add_filter( "wp_calculate_image_srcset", array($this, '_calculate_image_srcset'), 0, 5); // deactivated on 2017-08-16

				//add_filter( "woocommerce_single_product_image_thumbnail_html", array($this, 'woocommerce_image_replace_src_revert'));
				//add_filter( "wp_get_attachment_image_src", array($this, 'woocommerce_image_replace_src_revert'));
				//add_filter( "wp_get_attachment_thumb_url", array($this, '_attachment_url'), 0, 2);
				//add_filter( "wp_get_attachment_metadata", array($this, '_attachment_metadata'), 0, 2);
				//add_filter( "image_get_intermediate_size", array($this, '_intermediate_size'), 0, 3);
				
				/*$meta_type = array('post', 'product');
				foreach ($meta_type as $meta_t) {
					get_{$meta_type}_metadata filter from wp-includes/meta.php
					add_filter( "get_{$meta_t}_metadata", array($this, '_hook_woc_metadata'), 0, 4);
				}*/
			}
			
			// delete attachments when you delete post (product)
			$delete_post_attachments = isset( $this->amz_settings['delete_attachments_at_delete_post'] )
				&& 'yes' == $this->amz_settings['delete_attachments_at_delete_post'] ? true : false;
			if ( $delete_post_attachments ) {
				add_action('before_delete_post', array( $this, 'delete_post_attachments' ));
			}
			
			// Export Emails Action
			$doExportEmails = isset($_REQUEST['do']) && $_REQUEST['do'] == 'export_emails' ? true : false;
			if( $this->is_admin && $doExportEmails ) {
				// output headers so that the file is downloaded rather than displayed
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=clients_email_'. ( date('d-m-Y_H-i') ) .'.csv');
				
				// create a file pointer connected to the output stream
				$output = fopen('php://output', 'w');
				
				// output the column headings
				fputcsv($output, array('Email'));
				
				$emails = get_option( 'WooZone_clients_email' );
				// loop over the rows, outputting them
				foreach( $emails as $email ) {
					fputcsv($output, array($email));
				}
				die;
			}

			// Fixed Images Issue (from amazon CDN) with https
			/*$cond1 = ! isset($_REQUEST['wp_customize']);
			$cond2 = ! isset($_REQUEST['action']) || ( ! in_array($_REQUEST['action'], array('upload-plugin', 'upload-theme')) );
			if ( $cond1 && $cond2 ) {
				if ( ! $this->is_plugin_active('w3totalcache') ) {

					//add_action( 'after_setup_theme', array( $this, 'buffer_start' ), 10 );
					//add_action( 'shutdown', array( $this, 'buffer_end' ), 10, 1 );
					add_action( 'plugins_loaded', array( $this, 'buffer_end_pre' ), 0 ); // hooks tried: plugins_loaded | wp_loaded
				}
				else {
					//add_filter( 'w3tc_can_cache', array($this, 'w3tc_can_cache'), 10, 3 );
					add_filter( 'w3tc_process_content', array($this, 'w3tc_process_content'), 999, 1 );
				}

				// 2017-08-17 fixes for amazon images
				add_filter( 'wp_prepare_attachment_for_js', array($this, 'wp_prepare_attachment_for_js'), 10, 3 );
				add_filter( 'woocommerce_available_variation', array($this, 'woocommerce_available_variation'), 10, 3 );
			}*/
			add_filter( 'woocommerce_cart_item_thumbnail', array($this, 'filter_woocommerce_cart_item_thumbnail'), 10, 3 );

			
			// [Speed Optimisation Module] Return cached product attributes to additional information tab
			add_action( 'woocommerce_before_single_product', array( $this, 'check_cached_product_terms') );

			add_filter( "woocommerce_product_class", array( $this, 'try_to_overwrite' ), 10, 2 );

			$this->expressions = array(
				'as of' => 'as of',
				'Frequently Bought Together' => 'Frequently Bought Together',
				'Price for all' => 'Price for all',
				'This item' => 'This item',
				'Amazon Customer Reviews' => 'Amazon Customer Reviews',
				'FREE Shipping' => 'FREE Shipping',
				'Details' => 'Details',
				'Loading...' => 'Loading...',
				'not available' => 'not available',
				'available' => 'available',
				'You must check or cancel all amazon shops!' => 'You must check or cancel all amazon shops!',
				'all good' => 'all good',
				'canceled' => 'canceled',
				'checkout done' => 'checkout done',
				'Saving...' => 'Saving...',
				'Closing...' => 'Closing...',
				'Add to cart' => 'Add to cart',
			);
			$this->translatable_strings();
		}

		public function try_to_overwrite( $product_type )
		{
			$allowed_product_type = array('WC_Product_Simple', 'WC_Product_Variable', 'WC_Product_External', 'WC_Product_Grouped' );
			
			if( in_array($product_type, $allowed_product_type) ){
				$file_name = '';

				if( $product_type == 'WC_Product_Simple' ){
					$file_name = 'overwrite-simple.php';
					$ret_class = 'WooZoneWcProductModify_Simple';
				}

				if( $product_type == 'WC_Product_External' ){
					$file_name = 'overwrite-external.php';
					$ret_class = 'WooZoneWcProductModify_External';
				}
				elseif( $product_type == 'WC_Product_Grouped' ){
					$file_name = 'overwrite-grouped.php';
					$ret_class = 'WooZoneWcProductModify_Grouped';
				}

				elseif( $product_type == 'WC_Product_Variable' ){
					$file_name = 'overwrite-variable.php';
					$ret_class = 'WooZoneWcProductModify_Variable';
				}

				if( $file_name != '' ){
					require_once(  $this->cfg['paths']['plugin_dir_path'] . "woocommerce-overwrite/" . $file_name );
					return $ret_class;
				}
			}
			elseif( $product_type == 'WC_Product_Variation' ){
				return $product_type;
			}
			
			return $product_type;
		}

		public function wp_prepare_attachment_for_js($response, $attachment, $meta) {
			$theid = isset($response['id']) ? (int) $response['id'] : 0;
			if ( ! $theid ) return $response;
			
			if ( isset($response['url']) ) {
				$response['url'] = $this->_attachment_url( $response['url'], $theid );
			}
			if ( isset($response['sizes']) ) {
				foreach ($response['sizes'] as $key => $val) {
					if ( isset($val['url']) ) {
						$response['sizes']["$key"]['url'] = $this->_attachment_url( $val['url'], $theid );
					}
				}
			}
			return $response;
		}

		public function woocommerce_available_variation( $data, $that, $variation ) {
			//var_dump('<pre>', $data, $that, $variation, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			
			$image = array();
			if ( isset($data['image']) && ! empty($data['image']) ) {
				$image = $data['image'];
			}
			else {
				return $data;
			}
			
			foreach ($image as $key => $prop) {
				if ( ! in_array($key, array('url', 'src', 'srcset', 'full_src', 'thumb_src')) ) {
					continue 1;
				}
				
				//$image["$key"] = $this->_parse_page_fix_amazon( $prop );

				$theid = isset($data['variation_id']) ? (int) $data['variation_id'] : 0;
				if ( ! $theid ) return $data;

				$image["$key"] = $this->_attachment_url( $prop, $theid );
			}

			$data['image'] = $image;
			return $data;
		}
		
		public function filter_woocommerce_cart_item_thumbnail( $product_get_image, $cart_item, $cart_item_key ) {
			$product_get_image = $this->_parse_page_fix_amazon( $product_get_image );
			return $product_get_image;
		}

		public function check_cached_product_terms()
		{
			if( is_product() ) {
				$this->cached_product_terms = get_post_meta( get_the_ID(), '_cached_product_terms', true );
				  
				if( is_array($this->cached_product_terms) && count($this->cached_product_terms) > 0 ) {
					add_filter( 'woocommerce_product_tabs', array( $this, 'cached_terms_additional_information_tab' ), 98 );
				}
			}
		}
		
		public function cached_terms_additional_information_tab( $tabs ) {
			$tabs['additional_information']['title'] = __('Additional Information', 'woozone');
			//$tabs['additional_information']['priority'] = 5;
			$tabs['additional_information']['callback'] = array( $this, 'return_cached_product_terms_to_tab' );
			return $tabs;	
		}
		public function return_cached_product_terms_to_tab() {
			$html = array();
			
			
			$html[] = '<table class="shop_attributes">';
			$html[] = 	'<tbody>';
			
			foreach( $this->cached_product_terms as $taxonomy => $terms ) {
				$display_terms = array();
				
				foreach( $terms as $term ) {  
					$display_terms[] = '<a href="' . ( home_url('/?s=' . $term['slug']) . '&post_type=product' ) . '" rel="tag">' . ( $term['name'] ) . '</a>';
				}
				
				$html[] = 		'<tr>';
				$html[] = 			'<th>' . ( $term['taxonomy_name'] ) . '</th>';
				$html[] = 			'<td>';
				$html[] = 				'<p>';
				if( isset($display_terms) && count( $display_terms) > 0 ) {
					$html[] = implode(', ', $display_terms);
				}
				$html[] = 				'</p>';
				$html[] = 			'</td>';
				$html[] = 		'</tr>';
			}
			
			$html[] = 	'</tbody>';
			$html[] = '</table>';
			
			echo implode("\n", $html);
		}
		
		/**
		 * Gets updater instance.
		 *
		 * @return AATeam_Product_Updater
		 */
		public function product_updater()
		{
			require_once( $this->cfg['paths']['plugin_dir_path'] . 'aa-framework/utils/class-updater.php' );
			
			if( class_exists('WooZone_AATeam_Product_Updater') ){
				$product_data = get_plugin_data( $this->cfg['paths']['plugin_dir_path'] . 'plugin.php', false ); 
				new WooZone_AATeam_Product_Updater( $this, $product_data['Version'], 'woozone', 'woozone/plugin.php' );
			}
		}

		public function buffer_end_pre()
		{
			ob_end_clean();
		}

		public function buffer_end( $pms=array() )
		{
			$pms = array_filter( (array) $pms );
			$pms = array_replace_recursive(array(
				'show'		=> true,
				'page'		=> '',
			), $pms);
			extract($pms);

			$page = isset($page) && ! empty($page) ? $page : $this->page;

			$page = $this->_parse_page_fix_amazon( $page );

			$cacheImagesDebug = $this->debug_cache_images();
			if ( ! empty($cacheImagesDebug) && isset($_REQUEST['aateam']) && (bool) $_REQUEST['aateam'] ) {
				$page .= $cacheImagesDebug;
			}

			$this->page = $page;
			if ( $show ) {
				echo $page;
			}
			return $page;
		}

		public function _parse_page_fix_amazon( $page ) {
			$upload = wp_upload_dir();
			$upload_base = $upload['baseurl'];
			$upload_base_ = str_replace( array("http://", "https://"), '', $upload_base );
			$upload_base_non_ssl = 'http://' . $upload_base_;
			$upload_base_is_ssl = 'https://' . $upload_base_;
			$upload_base__ = array( $upload_base_is_ssl . '/', $upload_base_non_ssl . '/', '//' . $upload_base_ . '/' );
			//var_dump('<pre>',$upload_base__,'</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			
			// fix for wordpress.com hosted sites and jetpack
			// https://jetpack.com/support/photon/
			if( class_exists( 'Jetpack' ) && method_exists( 'Jetpack', 'get_active_modules' ) && in_array( 'photon', Jetpack::get_active_modules() ) ) {
				$upload_base__[] = 'https://i0.wp.com/' . $upload_base_ . '/';
				$upload_base__[] = 'https://i1.wp.com/' . $upload_base_ . '/';
				$upload_base__[] = 'https://i2.wp.com/' . $upload_base_ . '/';
				$upload_base__[] = 'https://i3.wp.com/' . $upload_base_ . '/';
			}

			//:: PARSE PAGE
			{
				//:: all images
				$nb_images = preg_match_all( '/<img[^>]+>/i', $page, $images );
				$images = isset($images[0]) && ! empty($images[0]) ? (array) $images[0] : array();

				// debug!
				//var_dump('<pre>', $nb_images, $result, '</pre>'); //echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				// has images?
				if ( ! empty($images) ) {
					foreach ( $images as $page_img ) {
	
						// is amazon image? images-amazon.
						if ( strpos( $page_img, self::$amazon_images_path ) === false ) {
							continue 1;
						}
	
						$new_img_html = $page_img;
						$new_img_html = str_replace( $upload_base__, '', $new_img_html );
	
						// check if is ssl image hosted
						$amz_ = ( strpos( $page_img, 'ssl-images' ) !== false ? 'https://' : 'http://' );
	
						$new_img_html = str_replace( 'src="//', 'src="' . $amz_, $new_img_html );
						$new_img_html = str_replace( 'srcset="//', 'srcset="' . $amz_, $new_img_html );
						$new_img_html = str_replace( 'data-large_image="//', 'data-large_image="' . $amz_, $new_img_html );
						$new_img_html = str_replace( ', //', ', ' . $amz_, $new_img_html );
		
						$page = str_replace( $page_img, $new_img_html, $page );
					} // end foreach
				} // end has images?

				// debug!
				/*
				$nb_images = preg_match_all('/<img[^>]+>/i', $page, $result);
				var_dump('<pre>', $nb_images, $result, '</pre>'); //echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				*/
				
				//:: others
				$nb_others = preg_match_all( '/=(?:"|\')[^"\']*' . preg_quote(self::$amazon_images_path) . '[^"\']*(?:"|\')/i', $page, $others );
				$others = isset($others[0]) && ! empty($others[0]) ? (array) $others[0] : array();

				// debug!
				//var_dump('<pre>', $nb_images, $result, '</pre>'); //echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				// has others?
				if ( ! empty($others) ) {
					foreach ( $others as $page_img ) {
	
						$new_img_html = $page_img;
						$new_img_html = str_replace( $upload_base__, '', $new_img_html );
						$page = str_replace( $page_img, $new_img_html, $page );
					} // end foreach
				} // end has images?
			}
			//:: END PARSE PAGE

			//var_dump( "<pre>", $page , "</pre>" ) . PHP_EOL .  __FILE__ . ":" . __LINE__; die;  
			//die( var_dump( "<pre>", $page  , "<pre>" ) . PHP_EOL .  __FILE__ . ":" . __LINE__  );
			
			return $page;
		}

		public function buffer_start()
		{
			ob_start( array($this, 'buffer_callback') );
		}

		public function buffer_callback( $buffer ) 
		{
			$this->page = $buffer;
		}

		/**
		 * w3 total cache related
		 */
		public function w3tc_can_cache($original_can_cache, $that, $buffer) {
			return true;
		}
		
		public function w3tc_process_content( $buffer ) {
			$buffer = $this->buffer_end( array('page' => $buffer, 'show' => false) );
			return $buffer;
		}
		
		/**
		 * Cacheit Init
		 */
		public function cacheitInit() {
			$is_deactivated = true;

			if ( $is_deactivated ) {
				$cache_type = 'none';
			}
			else {
				$cache_type = 'file';
				if ( isset($this->amz_settings['cache_remote_images']) ) {
					if ( 'none' == $this->amz_settings['cache_remote_images'] ) {
						$cache_type = 'none';
					}
					else {
						$cache_type = $this->amz_settings['cache_remote_images'];
					}
				}
			}

			$levels_used = array();
			if ( 'none' != $cache_type ) {
				$levels_used = array('session', $cache_type);
			}
			//$levels_used = array('wpoption'); //array('session', 'wpoption', 'file') //DEBUG

			$cache_pms = array(
				'do_load'					=> true,
				'levels_used'				=> $levels_used,
				'cache_keymain'		=> array('WooZoneCached'),
				'cache_folder'			=> 'woozone-cached',
			);

			require_once( $this->cfg['paths']['plugin_dir_path'] . 'aa-framework/utils/cacheit.class.php' );

			$_SESSION['WooZoneCachedContor'] = array('hits' => 0, 'hitscache' => 0, 'nonamazon' => 0);

			$this->cacheit['imgurl'] = WooZoneCacheImagesUrl::getInstanceMultiple( $this, array_replace_recursive($cache_pms, array(
				'cache_keymain'		=> array('WooZoneCached_imgurl', 'imgurl'),
				//'cache_keymain'		=> array('WooZoneCached', 'imgurl'),
			)));

			$this->cacheit['imgsources'] = WooZoneCacheImagesSources::getInstanceMultiple( $this, array_replace_recursive($cache_pms, array(
				'cache_keymain'		=> array('WooZoneCached_imgsources', 'imgsources'),
				//'cache_keymain'		=> array('WooZoneCached', 'imgsources'),
			)));

			$this->cacheit['amzvalid'] = WooZoneCacheAmzValid::getInstanceMultiple( $this, array_replace_recursive($cache_pms, array(
				'cache_keymain'		=> array('WooZoneCached_amzvalid', 'amzvalid'),
				//'cache_keymain'		=> array('WooZoneCached', 'amzvalid'),
			)));

			//DEBUG
			foreach ( $this->cacheit as $key => $obj) {
				//$this->cacheit["$key"]->empty_cache();
				//$this->cacheit["$key"]->debug_cache();
			}
			//echo __FILE__ . ":" . __LINE__;die . PHP_EOL;    
		}

		public function framework_style( $onlygenerate=false )
		{
			$start = microtime(true);

			$main_file = $this->wp_filesystem->get_contents( $this->cfg['paths']['freamwork_dir_path'] . "/scss/styles.scss" );
			if( !$main_file ){
				$main_file = file_get_contents( $this->cfg['paths']['freamwork_dir_path'] . "/scss/styles.scss" );
			}

			$files = array();
			if(preg_match_all('/@import (url\(\"?)?(url\()?(\")?(.*?)(?(1)\")+(?(2)\))+(?(3)\")/i', $main_file, $matches)){
				foreach ($matches[4] as $url) {
					if( file_exists( $this->cfg['paths']['freamwork_dir_path'] . "/scss/_" . $url . '.scss') ){ 
						$files[] = '_' . $url . '.scss';
					}
					if( file_exists( $this->cfg['paths']['freamwork_dir_path'] . "/scss/" . $url . '.scss') ){
						$files[] = $url . '.scss';
					}
				}
			}
			
			$buffer = '';
			if( count($files) > 0 ){
				foreach ($files as $scss_file) {
					if( 0 ){ 
						$buffer .= "\n" .   "/****-------------------------------\n";
						$buffer .= "\n" .   " IN FILE: $scss_file \n";
						$buffer .= "\n" .   "------------------------------------\n";
						$buffer .= "\n***/\n";
					}

					$has_wrote = $this->wp_filesystem->get_contents( $this->cfg['paths']['freamwork_dir_path'] . "/scss/" . $scss_file );
					if ( !$has_wrote ) {
						$has_wrote = file_get_contents( $this->cfg['paths']['freamwork_dir_path'] . "/scss/" . $scss_file );
					}
					$buffer .= $has_wrote;
				}
			} 

			try {
				// 2018-may-31 update
				require $this->cfg['paths']['scripts_dir_path'] . "/scssphp/scss.inc.php";
				$scss = new scssc();
				//require $this->cfg['paths']['scripts_dir_path'] . "/scssphpnew/scss.inc.php";
				//$scss = new Compiler();
				//$scss->setLineNumberStyle(Compiler::LINE_COMMENTS);
				//$scss->setSourceMap(Compiler::SOURCE_MAP_INLINE);
				//$scss->setSourceMap(Compiler::SOURCE_MAP_FILE);
				//$scss->setSourceMapOptions( array(
				//	'sourceMapWriteTo' 	=> $this->cfg['paths']['freamwork_dir_path'] . 'main-style.css.map',
				//));

				$buffer = $scss->compile( $buffer );
			}
			catch (Exception $e) {
				die( 'scssphp: Unable to compile content' );
			}
			
			#$buffer = str_replace( "fonts/", $this->cfg['paths']['freamwork_dir_url'] . "fonts/", $buffer );
			$buffer = str_replace( '#framework_url/', $this->cfg['paths']['freamwork_dir_url'], $buffer );
			$buffer = str_replace( '#plugin_url', $this->cfg['paths']['plugin_dir_url'], $buffer );


			$time_elapsed_secs = microtime(true) - $start;
			$buffer .= "\n\n/*** Compile time: $time_elapsed_secs */";

			if ( ! isset($onlygenerate) || ! $onlygenerate ) {
				// Enable caching
				header('Cache-Control: public');

				// Expire in one day
				header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');

				// Set the correct MIME type, because Apache won't set it for us
				header("Content-type: text/css");

				// Write everything out
				echo $buffer;
			}
			
			$buffer = str_replace( $this->cfg['paths']['freamwork_dir_url'], '', $buffer );

			$has_wrote = $this->wp_filesystem->put_contents( $this->cfg['paths']['freamwork_dir_path'] . 'main-style.css', $buffer );
			if ( !$has_wrote ) {
				$has_wrote = file_put_contents( $this->cfg['paths']['freamwork_dir_path'] . 'main-style.css', $buffer );
			}

			if ( isset($onlygenerate) && $onlygenerate ) {
				return true;
			}

			die;
		}
		
		/**
		 * Base
		 */
		public function session_start() {
			$session_id = isset($_COOKIE['PHPSESSID']) ? session_id($_COOKIE['PHPSESSID']) : ( isset($_REQUEST['PHPSESSID']) ? $_REQUEST['PHPSESSID'] : session_id() );
			if(!$session_id) {
				// session isn't started
				session_start();
			}
			
			if( isset($_SESSION['AATeam_WooZone_ajax_debug']) && wp_doing_ajax() ) {
				ini_set( 'display_errors', 1 );
			}
			//!isset($_SESSION['aateam_sess_dbg']) ? $_SESSION['aateam_sess_dbg'] = 0 : $_SESSION['aateam_sess_dbg']++;
			//var_dump('<pre>',$_SESSION['aateam_sess_dbg'],'</pre>');

			//$_SESSION = array();
			//var_dump('<pre>', $_SESSION, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
		}
		public function session_close() {
			session_write_close(); // close the session
			//session_destroy();
		}

		public function dismiss_notice()
		{
			$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
			if ( !$id ) {
				header( 'Location: ' . sprintf( admin_url('admin.php?page=%s'), $this->alias ) );
				die;
			}

			$current = get_option( $this->alias . "_dismiss_notice", array() );
			$current = !empty($current) && is_array($current) ? $current : array();
			$current["$id"] = 1;
			update_option( $this->alias . "_dismiss_notice" , $current );
			header( 'Location: ' . sprintf( admin_url('admin.php?page=%s'), $this->alias ) );
			die;
		}

		public function notifier_cache_interval() {
			return self::NOTIFIER_CACHE_INTERVAL;
		}
		
		public function plugin_row_meta($what='') {
			if ( !empty($what) && isset(self::$plugin_row_meta["$what"]) ) {
				return self::$plugin_row_meta["$what"];
			}
			return self::$plugin_row_meta;
		}

		public function amazon_url_to_ssl( $url='' ) {
			if (empty($url)) return $url;
			if ( ! $this->is_ssl() ) return $url;

			// http://ecx.images-amazon TO https://images-na.ssl-images-amazon
			$newurl = preg_replace('/^http\:\/\/ec(.){0,1}\.images\-amazon/imu', 'https://images-na.ssl-images-amazon', $url);
			return !empty($newurl) ? $newurl : $url;
		}

		public function _attachment_metadata( $data='', $post_id=0 ) {
			return $data;

			$rules = array();
			$rules[0] = !empty($data) && is_array($data);
			$rules[1] = $rules[0] && isset($data['width'], $data['width'], $data['file'], $data['image_meta']);
			$rules[2] = $rules[0] && isset($data['sizes'])
				&& !empty($data['sizes']) && is_array($data['sizes']);
			$rules = $rules[0] && $rules[1] && $rules[2];

			if ( $rules ) {
			} 
			return $data;
		}
		
		public function _intermediate_size( $data=array(), $post_id=0, $size='' ) {
		}
		
		public function _hook_woc_metadata($metadata, $object_id, $meta_key, $single) {
			//var_dump('<pre>',$metadata, $object_id, $meta_key, $single,'</pre>');
			$metadata_orig = $metadata;
			
			$parsing = array(
				//'_product_image_gallery',
				//'_thumbnail_id',
				'_wp_attached_file',
				'_wp_attachment_metadata'
			);
			if ( !isset($meta_key) || !in_array($meta_key, $parsing) ) return $metadata;
			
			// must be amazon product
			// ... to do

			// loop through keys
			switch ( $meta_key ) {
				case '_wp_attached_file':
					$metadata = $this->_get_meta_key( $meta_key, $object_id );
					if ( empty($metadata) ) return $metadata;

					if ( strpos( $metadata->meta_value, self::$amazon_images_path ) ) {
						return $metadata->meta_value;
					}

					$metadata = $this->_get_amz_asset( (int) $metadata->post_id );
					if ( empty($metadata) ) return $metadata;
						
					$metadata = $metadata->asset;
					break;
					
				case '_wp_attachment_metadata':
					$metadata = $this->_get_meta_key( $meta_key, $object_id );
					if ( empty($metadata) ) return $metadata;

					$meta_value = maybe_unserialize( $metadata->meta_value );
					if ( empty($meta_value) || !is_array($meta_value) ) {
						return $metadata_orig;
					}
	
					$metadata_ = array_replace_recursive(array(
						'width'         => 0,
						'height'        => 0,
						'file'          => '',
						'sizes'         => array(),
						'image_meta'    => array(),
					), $meta_value);
					
					if ( !empty($metadata_['file'])
						&& strpos( $metadata_['file'], self::$amazon_images_path ) ) {
						return array($metadata_);
					}

					$metadata = $this->_get_amz_asset( (int) $metadata->post_id );
					if ( empty($metadata) ) return $metadata;
					
					$metadata_['file'] = $metadata->asset;
					
					$image_sizes = get_intermediate_image_sizes();
					foreach ( $image_sizes as $_size ) {

						$url = $metadata->asset;
						if ( in_array($_size, array('thumbnail', 'shop_thumbnail')) ) {
							$url = $metadata->thumb;
						}
						$url = basename($url);

						if ( isset($metadata_['sizes'], $metadata_['sizes']["$_size"]) ) {
							$metadata_['sizes']["$_size"]['file'] = $url;
						}
					}
					$metadata = array($metadata_);
					break;
			}

			//var_dump('<pre>',$object_id, $meta_key, $metadata,'</pre>');  
			return $metadata;
		}

		private function _get_meta_key( $meta_key, $post_id=0 ) {
			if ( empty($post_id) ) return false;
	 
			global $wpdb;
			
			$q = "select pm.post_id, pm.meta_value from $wpdb->postmeta as pm where 1=1 and pm.post_id = %s and pm.meta_key = %s order by pm.meta_id desc limit 1;";
			$q = $wpdb->prepare( $q, $post_id, $meta_key );
			$res = $wpdb->get_row( $q );
			if ( empty($res) ) return null;
			return $res;
		}
		
		private function _get_amz_asset( $media_id=0 ) {
			if ( empty($media_id) ) return false;
	 
			global $wpdb;
			$table = $wpdb->prefix . 'amz_assets';
	 
			$q = "select a.asset, a.thumb from $table as a where 1=1 and a.media_id = %s order by a.id asc limit 1;";
			$q = $wpdb->prepare( $q, $media_id );
			$res = $wpdb->get_row( $q );
			if ( empty($res) ) return null;
			return $res;
		}


		public function remove_featured_image($html, $attachment_id, $post_id = '') {    
			$featured_image = get_post_thumbnail_id($post_id);
			if ($attachment_id != $featured_image) {
					return $html;
			}
			return '';
		}


		/**
		 * Operation Messages
		 */
		public function opStatusMsgInit( $pms=array() ) {
			extract($pms);
			$this->opStatusMsg = array(
				'status'            => isset($status) ? $status : 'invalid',
				'operation'         => isset($operation) ? $operation : '',
				'operation_id'      => isset($operation_id) ? (string) $operation_id : '',
				'msg_header'        => isset($msg_header) ? $msg_header : '',
				'msg'               => array(),
				'duration'          => 0,
			);
			if ( isset($keep_msg) && $keep_msg ) {
				$opStatusMsg = $this->opStatusMsgGet('', 'file');
				$this->opStatusMsg['msg'][] = $opStatusMsg['msg'];
			}
			$this->opStatusMsgSetCache();
			return true;
		}
		public function opStatusMsgSet( $pms=array() ) {
			if ( empty($pms) ) return '';

			$msg = array();
			foreach ($pms as $key => $val) {
				if ( $key == 'msg' ) {
					if ( isset($pms['duration']) ) {
						$val .= ' - [ ' . (isset($pms['end']) ? 'total: ' : '') . $this->format_duration($pms['duration']) . ' ]'; 
					}
					$this->opStatusMsg["$key"][] = $val;
					$msg[] = $val;
				} else {
					$this->opStatusMsg["$key"] = $val;
				}
			}
			$this->opStatusMsgSetCache();

			$msg = implode('<br />', $msg);
			return $msg;
		}

		public function opStatusMsgSetCache( $from='file' ) {
			$this->session_close(); // close the session to allow asynchronous ajax calls

			if ( $from == 'session' ) {
				$this->opStatusMsgSetSession();
			} else if ( $from == 'cookie' ) {
				$this->opStatusMsgSetCookie();
			} else if ( $from == 'file' ) {
				$this->opStatusMsgSetFile();
			}
		}
		private function opStatusMsgSetSession() {
			$this->session_start(); // start the session
			$_SESSION['WooZone_opStatusMsg'] = serialize($this->opStatusMsg);
			$this->session_close(); // close the session
		}
		private function opStatusMsgSetCookie() {
			$cookie = $this->opStatusMsgGet();
			$cookie = $cookie['msg'];
			//$cookie = base64_encode($cookie);
			//$cookie = $this->encodeURIComponent( $cookie );

			$this->cookie_set(array(
				'name'          => 'WooZone_opStatusMsg',
				'value'         => $cookie,
				// time() + 604800, // 1 hour = 3600 || 1 day = 86400 || 1 week = 604800 || '+30 days'
				'expire_sec'    => strtotime( time() + 86400 )
			));
		}
		private function opStatusMsgSetFile() {
			$filename = $this->cfg['paths']['plugin_dir_path'] . 'cache/operation_status_msg.txt';

			$opStatusMsg = serialize($this->opStatusMsg);
			$this->u->writeCacheFile( $filename, $opStatusMsg );
		}

		public function opStatusMsgGet( $sep='<br />', $from='code' ) {
			$opStatusMsg = $this->opStatusMsg;
			if ( $from == 'session' ) {
				$opStatusMsg = unserialize($_SESSION['WooZone_opStatusMsg']);
			} else if ( $from == 'cookie' ) {
				$opStatusMsg = $_COOKIE['WooZone_opStatusMsg'];
				return $opStatusMsg;
			} else if ( $from == 'file' ) {
				$filename = $this->cfg['paths']['plugin_dir_path'] . 'cache/operation_status_msg.txt';

				if ( !$this->u->verifyFileExists($filename) ) {
					$this->u->createFile($filename);
				}
				$opStatusMsg = $this->u->getCacheFile( $filename );
				$opStatusMsg = unserialize($opStatusMsg);
			}

			$msg = (array) $opStatusMsg['msg'];
			$opStatusMsg['msg'] = implode( $sep, $msg );
			if ( isset($opStatusMsg['msg_header']) && !empty($opStatusMsg['msg_header']) ) {
				$opStatusMsg['msg'] = $opStatusMsg['msg_header'] . $sep . $opStatusMsg['msg'];
			}
			return $opStatusMsg;
		}

		/**
		 * Database tables
		 */
		public function admin_notice_install_styles()
		{
			if( !wp_style_is($this->alias . '-activation') ) {
				wp_enqueue_style( $this->alias . '-activation', $this->plugin_asset_get_path( 'css', $this->cfg['paths']['freamwork_dir_url'] . 'css/activation.css', true ), array(), $this->plugin_asset_get_version( 'css' ) );
			}
			
			// 2017-08-14 this code is deprecated - there is an wizard now to guide you through install settings
			//add_action( 'admin_notices', array( $this, 'admin_install_notice' ) );
		}

		public function admin_install_notice()
		{
		?>
		<div class="updated WooZone-message_activate wc-connect">
			<div class="squeezer">
				<h4><?php _e( sprintf( '<strong>%s</strong> &#8211; You are almost ready, if this is your first install, please install the default setup. To do that click on the "Install Default Setup" button below and after that click on the "Install Settings" button from the Setup/Backup page.', $this->pluginName ), $this->localizationName ); ?></h4>
				<p class="submit"><a href="<?php echo admin_url( 'admin.php?page=' . $this->alias ); ?>#!/setup_backup#makeinstall" class="button-primary"><?php _e( 'Install Default Setup', $this->localizationName ); ?></a> | 
				<a href="<?php echo admin_url("admin.php?page=WooZone&disable_activation");?>" class="aaFrm-dismiss"><?php _e('Dismiss This Message', $this->localizationName); ?></a>
				</p>
			</div>
		</div>
		<?php   
		}
		
		public function update_developer()
		{
			$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
			if ( in_array($ip, array('1.1.1.1')) ) {
				$this->dev = 'andrei';
			}
			else if ( in_array($ip, array()) ) {
				$this->dev = 'gimi';
			}
		}
		
		public function add_demo_products_marker( $column, $post_id )
		{
			switch ( $column ) {
				case 'name':
				
					$is_direct_import_products = (boolean)get_post_meta( $post_id, '_amzaff_direct_import', true );
					if( $is_direct_import_products === true ){
						$html = array();
						$html[] = '<span class="WooZone-marker-direct-import">';
						$html[] = 	'<img src="' . ( $this->cfg['paths']['plugin_dir_url'] . 'icon_directimport.png' ) . '" />';
						$html[] = 	'Direct Import';
						$html[] = '</span>';

						echo implode( "\n", $html );
					}else{
						$is_demo_products = (boolean)get_post_meta( $post_id, '_amzaff_aateam_keys', true );
						if( $is_demo_products === true ){
							$html = array();
							$html[] = '<span class="WooZone-marker-demo-product">';
							$html[] = 	'<img src="' . ( $this->cfg['paths']['plugin_dir_url'] . 'icon_24.png' ) . '" />';
							$html[] = 	'Demo Product';
							$html[] = '</span>';

							echo implode( "\n", $html );
						}
					}
					break;
			}
		}

		public function demo_products_import_end_html( $pms=array() )
		{
			extract($pms);

			$html = array();

			$products_id = $this->get_products_demo_keys('idlist');

			$html[] = '<div class="WooZone-demo_end_wrapper">';
			$html[] = 	'<div class="WooZone-demo_big_logo">';
			$html[] = 		'<img src="' . ( $this->cfg['paths']['freamwork_dir_url'] ) . 'images/woozone-big-logo.png" />';
			$html[] = 	'</div>';
			$html[] = 	'<div class="WooZone-demo_message">';
			$html[] = 		'<span class="WooZone-demo-arrow_box"></span>';
			$html[] = 		__( '<h3>Thank you for using WooZone, the best Amazon Affiliate WooCommerce plugin available on the market.</h3>', $this->localizationName );
			if ( isset($is_block_demo_keys) && $is_block_demo_keys ) {

			$html[] = sprintf( __( 'When you\'re using aateam demo keys, you\'re not allowed to use this module.<br/>Please follow the instructions to generate your own Amazon API keys and register for the affiliation program. You can find the instructions <a href="%s" target="_blank">here</a> or you can open a ticket at <a href="%s">support.aa-team.com</a>. <br />', $this->localizationName ), 'http://docs.aa-team.com/woocommerce-amazon-affiliates/documentation/how-to-create-an-amazon-account-and-sign-up-for-the-product-advertising-api/', 'http://support.aa-team.com' );

			}
			else {

			$html[] = 		sprintf( __( 'For an easier understanding about how awesome our plugin is, we give you the opportunity to import %s products using our demo keys.<br />', $this->localizationName), $this->ss['max_products_demo_keys'] );
			$html[] = 		__( 'These are the products you choose to import:', $this->localizationName );
			
			$html[] =		'<ul class="WooZone-demo-products-list">';
			if( !empty($products_id) && count($products_id) > 0 ){
				
				foreach ($products_id as $prod_id) {
					$product_thumb = '<img class="no-image-available" src="'. ( $this->cfg['paths']['plugin_dir_url'] ) . 'no-image.jpg" alt="no-image-available" />';
					if( get_the_post_thumbnail( $prod_id, array(50, 50) ) != '' ) {
						$product_thumb = get_the_post_thumbnail( $prod_id, array(50, 50) );
					}

					$html[] = '<li><a href="' . ( admin_url('post.php?post=' . ( $prod_id ) . '&action=edit') ) . '">' . ( $product_thumb ) . '</a></li>';
				}
			}

			$html[] =		'</ul>';
			
			$html[] = 		sprintf( __( 'In order to use the plugin at its full capacity, please follow the instructions to generate your own Amazon API keys and register for the affiliation program. You can find the instructions <a href="%s" target="_blank">here</a> or you can open a ticket at <a href="%s">support.aa-team.com</a>. <br />', $this->localizationName ), 'http://docs.aa-team.com/woocommerce-amazon-affiliates/documentation/how-to-create-an-amazon-account-and-sign-up-for-the-product-advertising-api/', 'http://support.aa-team.com' );

			}

			$html[] =		'<a class="WooZone-form-button-primary" href="' . ( admin_url( 'admin.php?page=WooZone#!/amazon' ) ) . '">' . __('Set your own keys now', $this->localizationName ) . '</a>';
			$html[] = 	'</div>';
			$html[] = '</div>';
			
			echo implode( "\n", $html );
		}

		public function frontpage()
		{
			global $product;

		
			if( !wp_script_is('thickbox') ) {
				wp_enqueue_script('thickbox', null,  array('jquery'));
			}
			if( !wp_style_is('thickbox.css') ) {
				wp_enqueue_style('thickbox.css',  $this->plugin_asset_get_path( 'css', '/' . WPINC . '/js/thickbox/thickbox.css', true ), null, $this->plugin_asset_get_version( 'css' ));
			}


			if( isset($this->amz_settings['remove_gallery']) && $this->amz_settings['remove_gallery'] == 'no' ){
				add_filter( 'the_content', array($this, 'remove_gallery'), 6);
			}
						
			// footer related!
			add_action( 'wp_footer', array( $this, 'make_footer' ), 1 );
			
			// product price disclaimer for amazon & other extra details!
			add_action( 'wp_head', array( $this, 'make_head' ), 1 );
			add_filter( 'woocommerce_get_price_html', array($this, 'amz_disclaimer_price_html'), 100, 2 );
			add_filter( 'woocommerce_get_availability', array($this, 'amz_availability'), 100, 2 );
   
			// remove featured image from gallery ids list - fixed duplicated first image from gallery bug
			//add_filter( 'woocommerce_product_gallery_attachment_ids', array($this, 'amz_product_gallery_attachment_ids'), 10, 2 ); //DEPRECATED
			add_filter( 'woocommerce_product_get_gallery_image_ids', array($this, 'amz_product_gallery_attachment_ids'), 10, 2 );

			if ( 'external' == $this->p_type ) {
				add_filter('woocommerce_product_single_add_to_cart_text', array($this, '_product_buy_text'));
				add_filter('woocommerce_product_add_to_cart_text', array($this, '_product_buy_text'));
				
				// Change the Add To Cart Link
				add_filter( 'woocommerce_loop_add_to_cart_link', array($this, 'amz_add_product_link') );
			}
			
			// product buy url is the original amazon url!
			if( $this->product_buy_is_amazon_url
				&& ( 'external' == $this->p_type )
			) {
				/*
				add_action( 'WooZone_footer', array($this, '_product_buy_url_make'), 30 );
				
				add_action( 'woocommerce_after_shop_loop_item', array($this, '_product_buy_url_html'), 1 );
				
				if( is_object(wp_get_theme()) && wp_get_theme()->template == 'flatsome' ) {
					add_action( 'woocommerce_after_add_to_cart_button', array($this, '_product_buy_url_html'), 1 );
				}else{
					add_action( 'woocommerce_after_single_product', array($this, '_product_buy_url_html'), 1 );
				}
				*/
				// 2017-oct-10 update
				add_filter( 'get_post_metadata', array($this, 'get_post_metadata'), 999, 4 );
			}

			$redirect_asin = (isset($_REQUEST['redirectAmzASIN']) && $_REQUEST['redirectAmzASIN']) != '' ? $_REQUEST['redirectAmzASIN'] : '';
			if( isset($redirect_asin) && strlen($redirect_asin) == 10 ) {
				if ( ! $this->disable_amazon_checkout )
					$this->redirect_amazon($redirect_asin);
			}  
		}
		
		public function amz_add_product_link( $link ) { 
			global $product;

			$prod_id = 0;
			if ( is_object($product) ) {
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
			}
			$product_id = $prod_id;

			$url = $product->add_to_cart_url();
			$product_sku = $product->get_sku();
			$quantity = isset( $quantity ) ? $quantity : 1;
			$class = isset( $class ) ? $class : 'button';
			$text = $product->add_to_cart_text();

			$prod_link_open_in = isset( $this->amz_settings['product_buy_button_open_in'] ) && !empty( $this->amz_settings['product_buy_button_open_in'] ) ? $this->amz_settings['product_buy_button_open_in'] : '_blank';
			
			$ajax_add_to_cart = '';
			if ( 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) {
				$ajax_add_to_cart = 'ajax_add_to_cart';
			}
			
			$link = '<a target="' . $prod_link_open_in . '" href="' . $url . '" rel="nofollow" data-product_id="' . $product_id . '" data-product_sku="' . $product_sku . '" data-quantity="' . $quantity . '" class="' . $ajax_add_to_cart . ' ' . $class . '">' . $text . '</a>';
			return $link;
		}
		
		public function make_head() {
			$details = array('plugin_name' => 'WooZone');

			$asof_font_size = isset($this->amz_settings['asof_font_size']) ? (string) $this->amz_settings['asof_font_size'] : '0.6';

			ob_start();
		?>
			<!-- start/ <?php echo $details['plugin_name']; ?> -->
			<style type="text/css">
				.WooZone-price-info {
					font-size: <?php echo $asof_font_size; ?>em;
					font-weight: normal;
				}

				.WooZone-availability-icon {
					background: transparent url("<?php bloginfo('url'); ?>/wp-content/plugins/woozone/aa-framework/images/shipping.png") no-repeat top left;
					padding-left: 30px;
				}

				.WooZone-free-shipping {
					color: #000;
					font-size: 14px;
				}
				.WooZone-free-shipping a.link {
					text-decoration: none;
				}

				.WooZone-coupon {
				}
					.WooZone-coupon .WooZone-coupon-title {
						color: #d71321;
						font-size: 18px;
					}
					.WooZone-coupon .WooZone-coupon-details {
						color: #8c8c8c;
						font-size: 14px;
					}
					.WooZone-coupon .WooZone-coupon-details a.link {
						color: #db2a37;
						text-decoration: none;
					}
				.WooZone-coupon-container {
					margin-top: 17px;
				}
					.WooZone-coupon-container .WooZone-coupon-clear {
						clear: left;
					}
					.WooZone-coupon-container .WooZone-coupon-header {
						float: left;
						width: 100%;
						color: #808080;
						font-size: 12px;
					}
					#TB_ajaxContent .WooZone-coupon-container .WooZone-coupon-header p {
						margin: 0px 0px 9px;
						padding: 0;
					}
					.WooZone-coupon-container .WooZone-coupon-header > p {
						float: left;
					}
					.WooZone-coupon-container .WooZone-coupon-header > a {
						float: right;
						color: #2b62a0;
						font-weight: bold;
					}
					.WooZone-coupon-container .WooZone-coupon-summary {
						background-color: #fff;
							border: 1px solid #eaeaea;
							border-radius: 4px;
						padding: 6px 8px;
							display: block;
						}
							.WooZone-coupon-container .WooZone-coupon-summary-inner {
								display: block;
								width: 100%;
							/*-webkit-transform-style: preserve-3d;
							-moz-transform-style: preserve-3d;
							transform-style: preserve-3d;*/
							}
								.WooZone-coupon-container .WooZone-coupon-summary-inner-left {
									display: inline-block;
									width: 53px;
								padding: 10px 5px;
								color: #7d9f22;
								line-height: 1.3em;
								border: 2px dashed #699000;
								border-radius: 10px;
								/*box-shadow: 0 0 0 4px #f5f8ee, 2px 1px 6px 4px rgba(10, 10, 0, 0.5);*/
								text-shadow: -1px -1px #c3d399;
								text-align: center;
								}
								.WooZone-coupon-container .WooZone-coupon-summary-inner-right {
								display: inline-block;
								margin-left: 15px;
								font-size: 12px;
								color: #363636;
								width: 80%;
									/*position: relative;
									top: 50%;
									-webkit-transform: translateY(-50%);
									-ms-transform: translateY(-50%);
									transform: translateY(-50%);*/
								}
								#TB_ajaxContent .WooZone-coupon-container .WooZone-coupon-summary-inner-right p {
									margin: 0px;
									padding: 0px;
								}
						.WooZone-coupon-container .WooZone-coupon-desc {
							font-size: 12px;
							color: #808080;
							margin-top: 24px;
						}
							.WooZone-coupon-container .WooZone-coupon-desc strong {
								color: #444444;
								margin-bottom: 12px;
							}
							.WooZone-coupon-container .WooZone-coupon-desc ol,
							.WooZone-coupon-container .WooZone-coupon-desc ul  {
								font-size: 11px;
								color: #5d5d5d;
							}
							.WooZone-coupon-container .WooZone-coupon-desc ul,
								.WooZone-coupon-container .WooZone-coupon-desc ol li,
								.WooZone-coupon-container .WooZone-coupon-desc ul li {
									margin-left: 9px;
								}
			</style>
			<!-- end/ <?php echo $details['plugin_name']; ?> -->
		<?php
			$contents = ob_get_clean();
			echo $contents;
		}
		
		public function make_footer() {
			global $wp_query;
			
			$details = array('plugin_name' => 'WooZone');

			echo "<!-- WooZone version: " . ( WOOZONE_VERSION ) . " -->" . PHP_EOL.PHP_EOL;

			// woocommerce-tabs amazon fix
			echo PHP_EOL . "<!-- start/ " . ($details['plugin_name']) . " woocommerce-tabs amazon fix -->" . PHP_EOL;
			echo '<script type="text/javascript">' . PHP_EOL;
			echo "jQuery('.woocommerce-tabs #tab-description .aplus p img[height=1]').css({ 'height': '1px' });". PHP_EOL;
			echo '</script>' . PHP_EOL;
			echo "<!-- end/ " . ($details['plugin_name']) . " woocommerce-tabs amazon fix -->" . PHP_EOL.PHP_EOL;
			
			$current_amazon_aff = $this->_get_current_amazon_aff();
			$current_amazon_aff = json_encode( $current_amazon_aff );
			$current_amazon_aff = htmlentities( $current_amazon_aff );
			echo '<span id="WooZone_current_aff" class="display: none;" data-current_aff="' . $current_amazon_aff . '"></span>';

			if ( !has_action('WooZone_footer') )
				return true;

			$details = array('plugin_name' => 'WooZone');

			$__wp_query = null;

			if ( !$wp_query->is_main_query() ) {
				$__wp_query = $wp_query;
				wp_reset_query();
			}

			echo PHP_EOL . "<!-- start/ " . ($details['plugin_name']) . " -->" . PHP_EOL;

			do_action( 'WooZone_footer' );
			$this->make_head();

			echo "<!-- end/ " . ($details['plugin_name']) . " -->" . PHP_EOL.PHP_EOL;

			if ( !empty($__wp_query) ) {
				$GLOBALS['wp_query'] = $__wp_query;
				unset( $__wp_query );
			}

			return true;
		}

		public function get_post_metadata( $null, $object_id, $meta_key, $single ) {
			if ( ! isset($meta_key) ) {
				return $null;
			}
			if ( ! $object_id ) {
				return $null;
			}

			if ( '_product_url' == $meta_key ) {
				//var_dump('<pre>',$object_id, $meta_key ,'</pre>');
				$product_buy_url = $this->_product_buy_url_asin( array(
					'product_id' 		=> $object_id,
					'redirect_asin' 	=> '',
				));

				$prod_link = $product_buy_url['link'];
				//$prod_asin = $product_buy_url['asin'];

				if ( $this->product_url_short ) {
					$prod_bitlymeta = $this->product_url_from_bitlymeta(array(
						'product' 	=> $object_id,
						'orig_url' 	=> $product_buy_url['link'],
						'country' 	=> $product_buy_url['country'],
					));
					if ( 'valid' === $prod_bitlymeta['status'] ) {
						$prod_link = $prod_bitlymeta['short_url'];
					}
				}

				// always return an array with your return value => no need to handle $single
				if( $prod_link != '' ) {
					return array( $prod_link );
				}
			}
			return $null;
		}

		public function _product_buy_url_make() {
			$details = array('plugin_name' => 'WooZone');
			$prod_link_open_in = isset( $this->amz_settings['product_buy_button_open_in'] ) && !empty( $this->amz_settings['product_buy_button_open_in'] ) ? $this->amz_settings['product_buy_button_open_in'] : '_blank';
			ob_start();
		?>
			<!-- start/ <?php echo $details['plugin_name']; ?> WooZone product buy url -->
			<script type="text/javascript">
				(function($) {
					jQuery(document).ready(function () {
						var prod_link_open_in = '<?php echo $prod_link_open_in; ?>';

						var links = $('body a[href*="redirectAmzASIN"]');
						//console.log( links );

						// loop through found links
						links.each(function(i) {
							var $this 	= $(this),
								href 	= $this.prop('href'),
								asin 	= href.split('redirectAmzASIN=')[1],
								rpl_el 	= $('.WooZone-product-buy-url-' + asin),
								rpl_link = rpl_el.length ? rpl_el.data('url') : '';
							//console.log( $this, asin );

							// replace link href
							if ( '' != rpl_link ) {
								//$this.attr('href', rpl_link);
								$this.prop('href', rpl_link);
								$this.prop('target', prod_link_open_in);
							}
						});
					});
				})(jQuery);
			</script>
			<!-- end/ <?php echo $details['plugin_name']; ?> wWooZone product buy url -->
		<?php
			$contents = ob_get_clean();
			echo $contents;
		}
		
		public function _product_buy_url_html() {
			global $product;

			$prod_id = 0;			
			if ( is_object($product) ) {
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
			}

			if ( $prod_id ) {
				$product_id = $prod_id;
				$product_buy_url = $this->_product_buy_url_asin( array(
					'product_id' 		=> $product_id,
					'redirect_asin' 	=> '',
				));

				$prod_link = $product_buy_url['link'];
				$prod_asin = $product_buy_url['asin'];

				if ( !empty($product_buy_url) ) {
					echo '<span data-url="' . $prod_link . '" data-product_id="' . $product_id . '" class="WooZone-product-buy-url WooZone-product-buy-url-' . $prod_asin . '" style="display: none;"></span>';
				}
			}
		}
		
		public function _product_buy_url_asin( $pms=array() ) {
			$pms = array_replace_recursive( array(
				'product_id' 		=> 0,
				'redirect_asin' 	=> '',

				// string value of the country to be forced on product amazon link | bool true = we determin here the country
				// if bool true then product_id is mandatory
				'force_country' 	=> false,
			), $pms);
			extract( $pms );

			$ret = array(
				'asin' 		=> '',
				'link' 		=> '',
				'country' 	=> '',
			);

			//:: get asin
			if ( empty($redirect_asin) ) {
				$redirect_asin = get_post_meta($product_id, '_amzASIN', true);
			}
			if ( empty($redirect_asin) ) {
				return $ret;
			}

			$ret = array_replace_recursive($ret, array(
				'asin' 	=> $redirect_asin,
			));

			//:: get country
			if ( is_bool($force_country) && $force_country ) {

				$getCountry = $this->get_product_import_country( array(
					'product_id'			=> $product_id,
					'country' 				=> '',
					'use_fallback_location' => true,
				));
				$the_country = $getCountry['country'];

				$the_country = $this->get_country2mainaffid( $the_country, array(
					'uk2gb' 	=> true,
				));

				$user_country = $this->amzForUser( $the_country );
			}
			else if ( is_string($force_country) && '' != $force_country ) {

				$the_country = $this->get_country2mainaffid( $force_country, array(
					'uk2gb' 	=> true,
				));

				$user_country = $this->amzForUser( $the_country );
			}
			else {
				/*$get_user_location = wp_remote_get( 'http://api.hostip.info/country.php?ip=' . $_SERVER["REMOTE_ADDR"] );
				if( isset($get_user_location->errors) ) {
					$main_aff_site = $this->main_aff_site();
					$user_country = $this->amzForUser( strtoupper(str_replace(".", '', $main_aff_site)) );
				}else{
					$user_country = $this->amzForUser($get_user_location['body']);
				}*/
				$user_country = $this->get_country_perip_external();
			}
			//var_dump('<pre>', $user_country, '</pre>'); die('debug...');

			//:: build link
			$link = '//www.amazon' . ( $user_country['website'] ) . '/gp/product/' . ( $redirect_asin ) . '/?tag=' . ( $user_country['affID'] ) . '';

			$ret = array_replace_recursive($ret, array(
				'link' 		=> $link,
				'country' 	=> substr( $user_country['website'], 1 ),
			));
			return $ret;
		}
		public function _product_buy_url( $product_id, $redirect_asin='', $force_country=false ) {
			$ret = $this->_product_buy_url_asin( array(
				'product_id' 		=> $product_id,
				'redirect_asin' 	=> $redirect_asin,
				'force_country' 	=> $force_country,
			));
			return $ret['link'];
		}

		public function amz_disclaimer_price_html( $price, $product ) {
			$prod_id = 0;
			if ( is_object($product) ) {
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
			}

			$post_id = $prod_id;
			if ( $post_id <=0 ) return $price;

			if ( !is_product() || !$product->get_price() || !$this->verify_product_is_amazon($post_id) ) return $price;

			// $price_update_date = get_post_meta($post_id, "_price_update_date", true);
			$price_update_date = get_post_meta($post_id, "_amzaff_sync_last_date", true);
			if ( empty($price_update_date) ) { // product not synced at least once yet! - bug solved 2015-11-03
				global $post;
				$price_update_date = strtotime($post->post_date); //$product->post->post_date
			}
			if ( !empty($price_update_date) ) {
				if( get_option( 'date_format' ) != '' && get_option( 'time_format' ) !='' ) {
					$price_update_date = date_i18n( get_option( 'date_format' ) .', '. get_option( 'time_format' ) , $price_update_date );	
				} else {
					$price_update_date = date('F j, Y, g:i a', $price_update_date);
				}

				//$gmt_offset = get_option( 'gmt_offset' );
				//$price_update_date = gmdate( get_option( 'date_format' ) .', '. get_option( 'time_format' ), ($price_update_date + ($gmt_offset * 3600)) );
			}

			//<ins><span class="amount">£26.99</span></ins>
			$text = !empty($price_update_date) ? '&nbsp;<em class="WooZone-price-info">' . sprintf( __('(' . ( $this->_translate_string( 'as of' ) ) . ' %s)', $this->localizationName), $price_update_date) . '</em>' : '';
			$text .= $this->amz_product_free_shipping($post_id);

			$reg_price = get_post_meta( get_the_ID(), '_regular_price');
			$s_price = get_post_meta( get_the_ID(), '_price');
			
			if( $reg_price != $s_price ) {
				if (strpos($price, '</del>') !== false) {
					return str_replace( '</del>', '</del>' . $text, $price );
				} else {
					return str_replace( '</ins>', '</ins>' . $text, $price );
				}
			} else {
				if (strpos($price, '</del>') !== false) {				
					return $this->u->str_replace_last( '</del>', '</del>' . $text, $price );
				} else {
					return $this->u->str_replace_last( '</span>', '</span>' . $text, $price );
				}
			}

			/*
			if ( substr_count($price, '</ins>') > 0 ) {
					$ret = str_replace( '</ins>', '</ins>' . $text, $price );
			} else {
				$ret = str_replace( '</span>', '</span>' . $text, $price );
			}
			return $ret;
			*/
		}
		
		public function amz_availability( $availability, $product ) {
			//change text "In Stock' to 'available'
			//if ( $_product->is_in_stock() )
			//  $availability['availability'] = __('available', 'woocommerce');

			//change text "Out of Stock' to 'sold out'
			//if ( !$_product->is_in_stock() )
			//  $availability['availability'] = __('sold out', 'woocommerce');

			$prod_id = 0;
			if ( is_object($product) ) {
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
			}

			$post_id = $prod_id;
			if ( $post_id > 0 ) {
				$meta = get_post_meta($post_id, '_amzaff_availability', true);
				if ( !empty($meta) ) {
					$availability['availability'] = /*'<img src="shipping.png" width="24" height="18" alt="Shipping availability" />'*/'' . $meta;
					$availability['class'] = 'WooZone-availability-icon';
				}
			}
			return $availability;
		}
		
		public function amz_product_free_shipping( $post_id ) {
			$contents = '';
			$current_amazon_aff = $this->_get_current_amazon_aff();

			$_tag = '';
			$_affid = $current_amazon_aff['user_country']['key'];
			if ( isset($this->amz_settings['AffiliateID']["$_affid"]) ) {
				$_tag = '&tag=' . $this->amz_settings['AffiliateID']["$_affid"];
			}

			if ( 'yes' == $this->frontend_show_free_shipping ) {

				$is_fs = $this->is_product_freeshipping( $post_id, array(
					'current_amazon_aff' 	=> $current_amazon_aff,
				));
				$contents .= $is_fs['html'];
			}

			// coupon
			if ( ! isset($this->amz_settings['frontend_show_coupon_text'])
				|| (
					isset($this->amz_settings['frontend_show_coupon_text'])
					&& $this->amz_settings['frontend_show_coupon_text'] == 'yes'
				)
			) {
 
				$meta_amzResp = get_post_meta($post_id, '_amzaff_amzRespPrice', true);
 
				if ( !empty($meta_amzResp) && isset($meta_amzResp['Offers'], $meta_amzResp['Offers']['Offer'], $meta_amzResp['Offers']['Offer']['Promotions'], $meta_amzResp['Offers']['Offer']['Promotions']['Promotion']['Summary'])
					&& !empty($meta_amzResp['Offers']['Offer']['Promotions']['Promotion']['Summary']) ) {
	 
					$post = get_post($post_id);
					$promotion = $meta_amzResp['Offers']['Offer']['Promotions']['Promotion']['Summary'];

					$coupon = array(
						'asin'              => get_post_meta($post_id, '_amzASIN', true),
						'prod_title'        => (string) $post->post_title,
						'title'             => isset($promotion['BenefitDescription']) ? $promotion['BenefitDescription'] : '',
						'details'           => sprintf( __('Your coupon will be applied at amazon checkout. %s', $this->localizationName), '<a name="' . __('COUPON DETAILS', $this->localizationName) . '" href="#TB_inline?width=500&height=700&inlineId=WooZone-coupon-popup" class="thickbox link">' . __('Details', $this->localizationName) . '</a>' ),
						'popup_content'     => isset($promotion['TermsAndConditions']) ? $promotion['TermsAndConditions'] : '',
						'link'              => '',
						'link_more'         => '',
					);
					if ( isset($promotion['PromotionId']) ) {
						$coupon = array_merge($coupon, array(
							'link'              => 'http://www.amazon' . $current_amazon_aff['user_country']['website'] . '/gp/coupon/c/' . $promotion['PromotionId'] . '?ie=UTF8&email=&redirectASIN=' . $coupon['asin'] . $_tag,
							'link_more'         => 'http://www.amazon' . $current_amazon_aff['user_country']['website'] . '/gp/coupons/most-popular?ref=vp_c_' . $promotion['PromotionId'] . '_tcs' . $_tag,
						)); 
					}

					// php query class
					require_once( $this->cfg['paths']['scripts_dir_path'] . '/php-query/phpQuery.php' );
					if( trim($coupon['popup_content']) != "" ){
						if ( !empty($this->charset) )
							$doc = WooZonephpQuery::newDocument( $coupon['popup_content'], $this->charset );
						else
							$doc = WooZonephpQuery::newDocument( $coupon['popup_content'] );
						
						$foundLinks = $doc->find("a");
						if ( (int)$foundLinks->size() > 0 ) {
							foreach ( $foundLinks as $foundLink ) {
								$foundLink = WooZonepq( $foundLink );
								$foundLink_href = trim($foundLink->attr('href'));
								$foundLink_href .= $_tag;
								$foundLink->attr( 'href', $foundLink_href );
							}
							$coupon['popup_content'] = $doc->html();
						}
					}

					ob_start();
			?>
					<div class="WooZone-coupon">
						<div class="WooZone-coupon-title"><?php echo $coupon['title']; ?></div>
						<div class="WooZone-coupon-details"><?php echo $coupon['details']; ?></div>
					</div>
					<div id="WooZone-coupon-popup" style="display: none;">
						<div class="WooZone-coupon-container">
							<div class="WooZone-coupon-header">
								<p><?php _e('Coupons available for this offer', $this->localizationName); ?></p>
								<a href="<?php echo $coupon['link_more']; ?>" target="_blank"><?php _e('View more coupons', $this->localizationName); ?></a>
							</div>
							<div class="WooZone-coupon-clear"></div>
							<div class="WooZone-coupon-summary">
								<div class="WooZone-coupon-summary-inner">
									<div class="WooZone-coupon-summary-inner-left">
										<a href="<?php echo $coupon['link']; ?>" target="_blank"><?php _e('Your coupon', $this->localizationName); ?></a>
									</div>
									<div class="WooZone-coupon-summary-inner-right">
										<div><?php echo $coupon['prod_title']; ?></div>
										<div><?php echo $coupon['title']; ?></div>
									</div>
								</div>
							</div>
							<div class="WooZone-coupon-desc">
								<?php echo $coupon['popup_content']; ?>
							</div>
						</div>
					</div>
			<?php
					$contents .= ob_get_clean();
				}
			}

			return $contents;
		}
		
		public function _get_current_amazon_aff() {
			$user_country = $this->get_country_perip_external();
	
			$ret = array(
				//'main_aff_site'           => $main_aff_site,
				'user_country'              => $user_country,
			);
			return $ret;
		}

		public function _product_buy_text($text) {
			$gtext = isset($this->amz_settings['product_buy_text']) && !empty($this->amz_settings['product_buy_text'])
				? $this->amz_settings['product_buy_text'] : '';
			if ( empty($gtext) ) return $text;
	
			global $product;

			$prod_id = 0;			
			if ( is_object($product) ) {
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
			}

			if ( $prod_id ) {
				$product_id = $prod_id;
	 
				// original text for non amazon/external products!
				if ( !$this->verify_product_is_amazon($product) ) return $text;

				$_button_text = get_post_meta($product_id, '_button_text', true);
				if ( !empty($_button_text) ) {
						return $_button_text;
				}
				return $gtext;
			}
			return $text;
		}

		public function amz_product_gallery_attachment_ids( $gallery_ids, $product ) {
			if ( empty($gallery_ids) ) {
				return $gallery_ids;
			}

			// verify we are in woocommerce product
			$prod_id = 0;
			if ( is_object($product) ) {
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
			}

			// product id
			$product_id = $prod_id;
			if ( empty($product_id) ) {
				return $gallery_ids;
			}

			// verify product is from amazon
			if ( !is_product() || !$this->verify_product_is_amazon($product_id) ) {
				return $gallery_ids;
			}

			// get featured image
			$thumbnail_id = (int) get_post_thumbnail_id( $product_id );
			if ( empty($thumbnail_id) ) {
				return $gallery_ids;
			}

			// remove featured image from gallery ids
			if ( in_array($thumbnail_id, $gallery_ids) ) {
				$__ = array_search($thumbnail_id, $gallery_ids);
				if ( $__ !== false ) {
					unset($gallery_ids["$__"]);
				}
			}
			return $gallery_ids;
		}

		public function get_amazon_country_site($country, $withPrefixPoint=false) {
			if ( isset($country) && !empty($country) ) {

				$config = array('main_aff_id' => $country);

				$ret = '';
				if( $config['main_aff_id'] == 'com' ){
					$ret = '.com';
				}
				elseif( $config['main_aff_id'] == 'ca' ){
					$ret = '.ca';
				}
				elseif( $config['main_aff_id'] == 'cn' ){
					$ret = '.cn';
				}
				elseif( $config['main_aff_id'] == 'de' ){
					$ret = '.de';
				}
				elseif( $config['main_aff_id'] == 'in' ){
					$ret = '.in';
				}
				elseif( $config['main_aff_id'] == 'it' ){
					$ret = '.it';
				}
				elseif( $config['main_aff_id'] == 'es' ){
					$ret = '.es';
				}
				elseif( $config['main_aff_id'] == 'fr' ){
					$ret = '.fr';
				}
				elseif( $config['main_aff_id'] == 'uk' ){
					$ret = '.co.uk';
				}
				elseif( $config['main_aff_id'] == 'jp' ){
					$ret = '.co.jp';
				}
				elseif( $config['main_aff_id'] == 'mx' ){
					$ret = '.com.mx';
				}
				elseif( $config['main_aff_id'] == 'br' ){
					$ret = '.com.br';
				}
				elseif( $config['main_aff_id'] == 'au' ){
					$ret = '.com.au';
				}
							
				if ( !empty($ret) && !$withPrefixPoint )
					$ret = substr($ret, 1); 
				return $ret;
			}
			return '';
		}

		public function amz_default_affid( $config ) {
			$config = (array) $config;

			// get all amazon settings options
			$main_aff_id = 'com'; $country = 'com';

			// already have a Valid main affiliate id!
			if( isset($config['main_aff_id'], $config['AffiliateID'], $config['AffiliateID'][$config['main_aff_id']])
				&& !empty($config['main_aff_id'])
				&& !empty($config['AffiliateID'][$config['main_aff_id']]) ) {

				return $config;
			}

			// get key for first found not empty affiliate id! 
			if ( isset($config['AffiliateID']) && !empty($config['AffiliateID'])
				&& is_array($config['AffiliateID']) ) {
				foreach ( $config['AffiliateID'] as $key => $val ) {
					if ( !empty($val) ) {
						$main_aff_id = $key;
						$country = $this->get_amazon_country_site($main_aff_id);
						break;
					}
				}
			}

			$config['main_aff_id'] = $main_aff_id;
			$config['country'] = $country;

			return $config;
		}

		public function main_aff_id()
		{
			$config = $this->amz_settings;
			$config = $this->amz_default_affid( $config );
			$config = (array) $config;

			if( isset($config['main_aff_id'], $config['AffiliateID'], $config['AffiliateID'][$config['main_aff_id']])
				&& !empty($config['main_aff_id'])
				&& !empty($config['AffiliateID'][$config['main_aff_id']]) ) {

				return $config['AffiliateID'][$config['main_aff_id']];
			}
			return 'com';
		}
		
		public function main_aff_site()
		{
			$config = $this->amz_settings;
			$config = $this->amz_default_affid( $config );
			$config = (array) $config;

			if( isset($config['main_aff_id'], $config['AffiliateID'], $config['AffiliateID'][$config['main_aff_id']])
				&& !empty($config['main_aff_id'])
				&& !empty($config['AffiliateID'][$config['main_aff_id']]) ) {

				if( $config['main_aff_id'] == 'com' ){
					return '.com';
				}
				elseif( $config['main_aff_id'] == 'ca' ){
					return '.ca';
				}
				elseif( $config['main_aff_id'] == 'cn' ){
					return '.cn';
				}
				elseif( $config['main_aff_id'] == 'de' ){
					return '.de';
				}
				elseif( $config['main_aff_id'] == 'in' ){
					return '.in';
				}
				elseif( $config['main_aff_id'] == 'it' ){
					return '.it';
				}
				elseif( $config['main_aff_id'] == 'es' ){
					return '.es';
				}
				elseif( $config['main_aff_id'] == 'fr' ){
					return '.fr';
				}
				elseif( $config['main_aff_id'] == 'uk' ){
					return '.co.uk';
				}
				elseif( $config['main_aff_id'] == 'jp' ){
					return '.co.jp';
				}
				elseif( $config['main_aff_id'] == 'mx' ){
					return '.com.mx';
				}
				elseif( $config['main_aff_id'] == 'br' ){
					return '.com.br';
				}
				elseif( $config['main_aff_id'] == 'au' ){
					return '.com.au';
				}
				else {
						return '.com';
				}
			}
			return '.com';
		}
		
		private function amzForUser( $userCountry='US' )
		{
			$config = $this->amz_settings;
			$config = $this->amz_default_affid( $config );
			$config = (array) $config;

			$affIds = (array) isset($config['AffiliateID']) ? $config['AffiliateID'] : array();
			$main_aff_id = $this->main_aff_id();
			$main_aff_site = $this->main_aff_site(); 
	   
			if( $userCountry == 'US' ){
				return array(
					'key'   => 'com',
					'website' => isset($affIds['com']) && (trim($affIds['com']) != "") ? '.com' : $main_aff_site,
					'affID' => isset($affIds['com']) && (trim($affIds['com']) != "") ? $affIds['com'] : $main_aff_id
				);
			}
			 
			elseif( $userCountry == 'CA' ){
				return array(
					'key'   => 'ca',
					'website' => isset($affIds['ca']) && (trim($affIds['ca']) != "") ? '.ca' : $main_aff_site,
					'affID' => isset($affIds['ca']) && (trim($affIds['ca']) != "") ? $affIds['ca'] : $main_aff_id
				);
			}
			
			elseif( $userCountry == 'FR' ){
				return array(
					'key'   => 'fr',
					'website' => isset($affIds['fr']) && (trim($affIds['fr']) != "") ? '.fr' : $main_aff_site,
					'affID' => isset($affIds['fr']) && (trim($affIds['fr']) != "") ? $affIds['fr'] : $main_aff_id
				);
			}
			
			elseif( $userCountry == 'CN' ){
				return array(
					'key'   => 'cn',
					'website' => isset($affIds['cn']) && (trim($affIds['cn']) != "") ? '.cn' : $main_aff_site,
					'affID' => isset($affIds['cn']) && (trim($affIds['cn']) != "") ? $affIds['cn'] : $main_aff_id
				);
			}
			
			elseif( $userCountry == 'DE' ){
				return array(
					'key'   => 'de',
					'website' => isset($affIds['de']) && (trim($affIds['de']) != "") ? '.de' : $main_aff_site,
					'affID' => isset($affIds['de']) && (trim($affIds['de']) != "") ? $affIds['de'] : $main_aff_id
				);
			}

			elseif( $userCountry == 'IN' ){
				return array(
					'key'   => 'in',
					'website' => isset($affIds['in']) && (trim($affIds['in']) != "") ? '.in' : $main_aff_site,
					'affID' => isset($affIds['in']) && (trim($affIds['in']) != "") ? $affIds['in'] : $main_aff_id
				);
			}
			
			elseif( $userCountry == 'IT' ){
				return array(
					'key'   => 'it',
					'website' => isset($affIds['it']) && (trim($affIds['it']) != "") ? '.it' : $main_aff_site,
					'affID' => isset($affIds['it']) && (trim($affIds['it']) != "") ? $affIds['it'] : $main_aff_id
				);
			}
			
			elseif( $userCountry == 'JP' ){
				return array(
					'key'   => 'jp',
					'website' => isset($affIds['jp']) && (trim($affIds['jp']) != "") ? '.co.jp' : $main_aff_site,
					'affID' => isset($affIds['jp']) && (trim($affIds['jp']) != "") ? $affIds['jp'] : $main_aff_id
				);
			}
			
			elseif( $userCountry == 'ES' ){
				return array(
					'key'   => 'es',
					'website' => isset($affIds['es']) && (trim($affIds['es']) != "") ? '.es' : $main_aff_site,
					'affID' => isset($affIds['es']) && (trim($affIds['es']) != "") ? $affIds['es'] : $main_aff_id
				);
			}
			
			elseif( $userCountry == 'GB' ){
				return array(
					'key'   => 'uk',
					'website' => isset($affIds['uk']) && (trim($affIds['uk']) != "") ? '.co.uk' : $main_aff_site,
					'affID' => isset($affIds['uk']) && (trim($affIds['uk']) != "") ? $affIds['uk'] : $main_aff_id
				);
			}
			
			elseif( $userCountry == 'MX' ){
				return array(
					'key'   => 'mx',
					'website' => isset($affIds['mx']) && (trim($affIds['mx']) != "") ? '.com.mx' : $main_aff_site,
					'affID' => isset($affIds['mx']) && (trim($affIds['mx']) != "") ? $affIds['mx'] : $main_aff_id
				);
			}
			
			elseif( $userCountry == 'BR' ){
				return array(
					'key'   => 'br',
					'website' => isset($affIds['br']) && (trim($affIds['br']) != "") ? '.com.br' : $main_aff_site,
					'affID' => isset($affIds['br']) && (trim($affIds['br']) != "") ? $affIds['br'] : $main_aff_id
				);
			}

			elseif( $userCountry == 'AU' ){
				return array(
					'key'   => 'au',
					'website' => isset($affIds['au']) && (trim($affIds['au']) != "") ? '.com.au' : $main_aff_site,
					'affID' => isset($affIds['au']) && (trim($affIds['au']) != "") ? $affIds['au'] : $main_aff_id
				);
			}

			else{
				
				$website = $config["main_aff_id"];
				if( $config["main_aff_id"] == 'uk' ) $website = 'co.uk';
				if( $config["main_aff_id"] == 'jp' ) $website = 'co.jp';
				if( $config["main_aff_id"] == 'mx' ) $website = 'com.mx';
				if( $config["main_aff_id"] == 'br' ) $website = 'com.br';
				if( $config["main_aff_id"] == 'au' ) $website = 'com.au';
	
				return array(
					'key'           => $config["main_aff_id"],
					'website'       => "." . $website,
					'affID'         => $main_aff_id
				); 
			}
		}

		private function redirect_amazon( $redirect_asin='' )
		{
			/*$get_user_location = wp_remote_get( 'http://api.hostip.info/country.php?ip=' . $_SERVER["REMOTE_ADDR"] );
			if( isset($get_user_location->errors) ) {
				$main_aff_site = $this->main_aff_site();
				$user_country = $this->amzForUser( strtoupper(str_replace(".", '', $main_aff_site)) );
			}else{
				$user_country = $this->amzForUser($get_user_location['body']);
			}*/
			$user_country = $this->get_country_perip_external();
			
			$config = $this->amz_settings;
			
			if( isset($redirect_asin) && trim($redirect_asin) != "" ){
				$post_id = $this->get_post_id_by_meta_key_and_value('_amzASIN', $redirect_asin);
								
				$redirect_to_amz = (int) get_post_meta($post_id, '_amzaff_redirect_to_amazon', true);
				update_post_meta($post_id, '_amzaff_redirect_to_amazon', (int)($redirect_to_amz+1));
								
				$redirect_to_amz2 = (int) get_post_meta($post_id, '_amzaff_redirect_to_amazon_prev', true);
				update_post_meta($post_id, '_amzaff_redirect_to_amazon_prev', (int)($redirect_to_amz2+1));
			}

			if( isset($config["90day_cookie"]) && $config["90day_cookie"] == 'yes' ){
		?>
			<form id="amzRedirect" method="GET" action="//www.amazon<?php echo $user_country['website'];?>/gp/aws/cart/add.html">
				<input type="hidden" name="AssociateTag" value="<?php echo $user_country['affID'];?>"/> 
				<input type="hidden" name="SubscriptionId" value="<?php echo $config['AccessKeyID'];?>"/> 
				<input type="hidden" name="ASIN.1" value="<?php echo $redirect_asin;?>"/>
				<input type="hidden" name="Quantity.1" value="1"/> 
			</form> 
		<?php 
			die('
				<script>
				setTimeout(function() {
						document.getElementById("amzRedirect").submit();
				}, 1);
				</script>
			');
			}else{ 
				$link = 'http://www.amazon' . ( $user_country['website'] ) . '/gp/product/' . ( $redirect_asin ) . '/?tag=' . ( $user_country['affID'] ) . '';
		
				die('<meta http-equiv="refresh" content="0; url=' . ( $link ) . '">');
			/* 
			<!--form id="amzRedirect" method="GET" action="<?php echo $link;?>">
			</form--> 
				*/
			}
		}

		public function get_post_id_by_meta_key_and_value($key, $value) 
			{
				global $wpdb;
				$meta = $wpdb->get_results($wpdb->prepare("SELECT * FROM `".$wpdb->postmeta."` WHERE meta_key=%s AND meta_value=%s", $key, $value));
				
				if (is_array($meta) && !empty($meta) && isset($meta[0])) {
					$meta = $meta[0];
				}   
				if (is_object($meta)) {
					return $meta->post_id;
				}
				else {
					return false;
				}
			}


		/**
		 * Some Plugin Status Info
		 */
		public function plugin_redirect() {

			$req = array(
				'disable_activation'        => isset($_REQUEST['disable_activation']) ? 1 : 0, 
				'page'                      => isset($_REQUEST['page']) ? (string) $_REQUEST['page'] : '',
			);
			extract($req);

			if ( $disable_activation && $this->alias == $page ) {
				update_option( $this->alias . "_is_installed", true );
				wp_redirect( get_admin_url() . 'admin.php?page=WooZone' );
			}
			
			if (get_option('WooZone_do_activation_redirect', false)) {
				
				$is_makeinstall = 1;
				
				$pullOutArray = @json_decode( file_get_contents( $this->cfg['paths']['plugin_dir_path'] . 'modules/setup_backup/default-setup.json' ), true );
				foreach ($pullOutArray as $key => $value){

					// prepare the data for DB update
					$saveIntoDb = $value;
					$saveIntoDb = is_bool($value) ? ( $value ? 'true' : 'false' ) : $value; //2016-june-21 fix

					//$saveIntoDb = $value != "true" ? serialize( $value ) : "true";
					//$saveIntoDb = !in_array( $value, array('true', 'false') ) && !is_bool($value) ? serialize( $value ) : $value; //2016-june-21 fix

					if ( 'WooZone_amazon' == $key ) {
						$saveIntoDb = $this->amazon_config_with_default( $value );
						//$saveIntoDb = serialize( $saveIntoDb ); //2016-june-21 fix
					}
					
					// option already exists in db => don't overwrite it!
					if  ( $is_makeinstall && get_option( $key, false ) ) {
						//var_dump('<pre>',$key, 'exists in db.', '</pre>');						
						continue 1;
					}
  
					// Use the function update_option() to update a named option/value pair to the options database table. The option_name value is escaped with $wpdb->escape before the INSERT statement.
					update_option( $key, $saveIntoDb );
				}
   
				/*
				$cross_sell_table_name = $this->db->prefix . "amz_cross_sell";
						if ($this->db->get_var("show tables like '$cross_sell_table_name'") != $cross_sell_table_name) {

								$sql = "CREATE TABLE " . $cross_sell_table_name . " (
						`ASIN` VARCHAR(10) NOT NULL,
						`products` TEXT NULL,
						`nr_products` INT(11) NULL DEFAULT NULL,
						`add_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
						PRIMARY KEY (`ASIN`),
						UNIQUE INDEX `ASIN` (`ASIN`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

								require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

								dbDelta($sql);
						}
				*/
				
				delete_option('WooZone_do_activation_redirect');
				wp_redirect( get_admin_url() . 'admin.php?page=WooZone' );
			}
		}

		public function amazon_config_with_default( $default ) {
			$dbs = $this->settings();
			$dbs = !empty($dbs) && is_array($dbs) ? $dbs : array();

			// keys to be maintained
			// AccessKeyID, SecretAccessKey, protocol, country, main_aff_id, amazon_requests_rate
			$maintain = array('AccessKeyID', 'SecretAccessKey', 'protocol', 'country', 'main_aff_id', 'amazon_requests_rate');
			foreach ($maintain as $key) {
				if ( isset($dbs["$key"]) && empty($dbs["$key"]) ) {
					unset($dbs["$key"]);
				}
			}

			// default mandatory keys & affiliate id
			//if ( isset($dbs['AccessKeyID']) && empty($dbs['AccessKeyID']) ) {
			//  unset($dbs['AccessKeyID']);
			//}
			//if ( isset($dbs['SecretAccessKey']) && empty($dbs['SecretAccessKey']) ) {
			//  unset($dbs['SecretAccessKey']);
			//}
			if ( isset($dbs['AffiliateID']) ) {
				if ( empty($dbs['AffiliateID']) || !is_array($dbs['AffiliateID']) ) {
					unset($dbs['AffiliateID']);
				} else {
					$found = false;
					foreach ($dbs['AffiliateID'] as $key => $val) {
						if ( !empty($val) ) {
							$found = true;
							break;
						}
					}
					if ( !$found ) {
						unset($dbs['AffiliateID']);
					}
				}
			}

			$new = array_replace_recursive( $default, $dbs);
			//var_dump('<pre>', $new, '</pre>'); die('debug...'); 
			return $new;
		}

		public function activate()
		{
			add_option('WooZone_do_activation_redirect', true);
			add_option('WooZone_depedencies_is_valid', true);
			add_option('WooZone_depedencies_do_activation_redirect', true);
			$this->plugin_integrity_check();
		}

		public function get_plugin_status ()
		{
			return $this->v->isReg( get_option($this->alias.'_hash') );
		}

		public function get_plugin_data()
		{
			$this->details = $this->pu->get_plugin_data();
			return $this->details;
		}

		public function get_latest_plugin_version($interval) {
			return $this->pu->get_latest_plugin_version($interval);
		}


		/**
		 * Create plugin init
		 *
		 *
		 * @no-return
		 */
		public function initThePlugin()
		{

			// If the user can manage options, let the fun begin!
			if($this->is_admin && current_user_can( 'manage_options' ) ){
				// Adds actions to hook in the required css and javascript
				add_action( "admin_print_styles", array( $this, 'admin_load_styles') );
				add_action( "admin_print_scripts", array( $this, 'admin_load_scripts') );

				// create dashboard page
				add_action( 'admin_menu', array( $this, 'createDashboardPage' ) );

				// get fatal errors
				add_action ( 'admin_notices', array( $this, 'fatal_errors'), 10 );

				// get fatal errors
				add_action ( 'admin_notices', array( $this, 'admin_warnings'), 10 );
				
				$section = isset( $_REQUEST['section'] ) ? $_REQUEST['section'] : '';
				$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
				if($page == $this->alias || strpos($page, $this->alias) == true && trim($section) != "" ) {
					add_action('init', array( $this, 'go_to_section' ));
				}
			}
			
			// keep the plugin modules into storage
			if(!isset($_REQUEST['page']) || strpos($_REQUEST['page'],'codestyling') === false){
				$this->load_modules();
			}
		}

		public function go_to_section()
		{
			$section = isset( $_REQUEST['section'] ) ? $_REQUEST['section'] : '';
			if( trim($section) != "" ) {    
				header('Location: ' . sprintf(admin_url('admin.php?page=%s#!/%s'), $this->alias, $section) );
				exit();
			}
		}

		// updated in 2018-jan-24
		private function update_products_type( $pms=array() ) {
			global $wpdb;

			$pms = array_replace_recursive(array(
				'products' 		=> 'all',
				'do_external' 	=> $this->product_offerlistingid_missing_external,
			), $pms);
			extract( $pms );

			//:: INIT
			$ret = array(
				'status' 	=> 'invalid',
				'msg' 		=> '',
			);

			$tposts = $wpdb->posts;
			$tpostmeta = $wpdb->postmeta;

			$p_type_gen = isset($this->amz_settings['onsite_cart']) && ( $this->amz_settings['onsite_cart'] == "no" )
				? 'external' : 'simple';

			$amzprods = array( 'prods' => array(), 'var' => array(), 'olprods' => array(), 'olvar' => array() );


			//:: what products do we want to retrieve - all or just some from input params
			$input_prods = array();
			if ( is_array($products) ) {
				$input_prods = (array) $products;
			}
			else if ( 'all' !== $products ) {
				$input_prods[] = (string) $products;	
			}

			$input_prods_ = $input_prods;
			$input_prods_ = implode(',', array_map(array($this, 'prepareForInList'), $input_prods_));
			//var_dump('<pre>', $input_prods_ , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$sql_clause = "";
			if ( ! empty($input_prods) ) {
				$sql_clause = " and p.ID IN ( $input_prods_ ) ";
			}


			//:: get all amazon simple & variable (parent) products
			// and p.post_status != ''
			// and (p.post_status = 'publish' OR p.post_status = 'future' OR p.post_status = 'draft' OR p.post_status = 'pending' OR p.post_status = 'private')
			$sql = "select p.ID, pm.meta_value from $tposts as p left join $tpostmeta as pm on p.ID = pm.post_id where 1=1 {clause} and p.post_type = 'product' and pm.meta_key='_amzASIN' and ! isnull(pm.post_id) and pm.meta_value != '' order by p.ID asc;";
			$sql = str_replace( '{clause}', $sql_clause, $sql );
			$res = $wpdb->get_results( $sql, OBJECT_K );
			$amzprods['prods'] = $res;
			//var_dump('<pre>', $sql, $amzprods['prods'] , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;


			//:: validation
			if ( empty($amzprods['prods']) || ! is_array($amzprods['prods']) ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 	=> 'no amazon products found based on input parameters',
				));
				return $ret;
			}


			//:: get all amazon variable (parent) products (each variable product must have at least one variation child associated)
			$sql = "select p.post_parent, count(p.ID) as _nb_found from $tposts as p left join $tpostmeta as pm on p.ID = pm.post_id where 1=1 {clause} and p.post_type = 'product_variation' and p.post_parent > 0 and pm.meta_key='_amzASIN' and ! isnull(pm.post_id) and pm.meta_value != '' group by p.post_parent having _nb_found > 0 order by p.post_parent asc;";
			$sql = str_replace( '{clause}', str_replace( 'p.ID', 'p.post_parent', $sql_clause ), $sql );
			$res = $wpdb->get_results( $sql, OBJECT_K );
			$amzprods['var'] = $res;
			//var_dump('<pre>', $sql, $amzprods['var'] , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;


			//:: we try to find all amazon products without an offerlistingid
			if ( $do_external ) {

				//:: get all amazon simple & variable (parent) products which have the _amzaff_amzRespPrice meta, but don't have an offerlistingid
				$sql = "select p.ID, pm.meta_value from $tposts as p left join $tpostmeta as pm on p.ID = pm.post_id where 1=1 {clause} and p.post_type = 'product' and pm.meta_key='_amzaff_amzRespPrice' and ! isnull(pm.post_id) and pm.meta_value != '' and pm.meta_value not regexp 's:14:\"OfferListingId\";' order by p.ID asc;";
				$sql = str_replace( '{clause}', $sql_clause, $sql );
				$res = $wpdb->get_results( $sql, OBJECT_K );
				$amzprods['olprods'] = $res;
				//var_dump('<pre>', $sql, $amzprods['olprods'] , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				//:: get all amazon variable (parent) products which have an offerlistingid (each variable product must have at least one variation child with an offerlistingid)
				if ( ! empty($amzprods['olprods']) && is_array($amzprods['olprods']) ) {
					$sql = "select p.post_parent, count(p.ID) as _nb_found from $tposts as p left join $tpostmeta as pm on p.ID = pm.post_id where 1=1 {clause} and p.post_type = 'product_variation' and p.post_parent > 0 and pm.meta_key='_amzaff_amzRespPrice' and ! isnull(pm.post_id) and pm.meta_value != '' and pm.meta_value regexp 's:14:\"OfferListingId\";' group by p.post_parent having _nb_found > 0 order by p.post_parent asc;";
					$sql = str_replace( '{clause}', str_replace( 'p.ID', 'p.post_parent', $sql_clause ), $sql );
					$res = $wpdb->get_results( $sql, OBJECT_K );
					$amzprods['olvar'] = $res;
					//var_dump('<pre>', $sql, $amzprods['olvar'] , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				}
			}
			//return true; //DEBUG


			//:: try to update the product type for the found products
			//var_dump('<pre>', $amzprods , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			foreach ($amzprods['prods'] as $key => $value) {

				$p_type = $p_type_gen;

				if ( $do_external ) {
					// product don't have an offerlistingid: simple | variable parent
					// & even if it's variable it doesn't have at least one valid variation
					if ( isset($amzprods['olprods']["$key"]) && ! isset($amzprods['olvar']["$key"]) ) {
						$p_type = 'external';
					}
				}

				if ( 'simple' == $p_type ) {
					if ( isset($amzprods['var']["$key"], $amzprods['var']["$key"]->_nb_found)
						&& $amzprods['var']["$key"]->_nb_found
					) {
						$p_type = 'variable';
					}
				}
				//var_dump('<pre>',$key, $p_type ,'</pre>');

				// doesn't seem to be used in woocommerce new version! /note on: 2015-07-14
				//delete_transient( "woocommerce_product_type_$key" );
				//set_transient( "woocommerce_product_type_$key", $p_type );

				delete_transient( "wc_product_type_$key" );
				set_transient( "wc_product_type_$key", $p_type );
				
				wp_set_object_terms( $key, $p_type, 'product_type');

			} // end foreach
		}
	 
		public function fixPlusParseStr ( $input=array(), $type='string' )
		{
			if($type == 'array'){
				if(count($input) > 0){
					$ret_arr = array();
					foreach ($input as $key => $value){
						$ret_arr[$key] = str_replace("###", '+', $value);
					}

					return $ret_arr;
				}

				return $input;
			}else{
				return str_replace('+', '###', $input);
			}
		}

		// saving the options
		public function save_options ()
		{
			// remove action from request
			unset($_REQUEST['action']);

			// unserialize the request options
			$serializedData = $this->fixPlusParseStr(urldecode($_REQUEST['options']));

			$savingOptionsArr = array();

			parse_str($serializedData, $savingOptionsArr);
 
			$savingOptionsArr = $this->fixPlusParseStr( $savingOptionsArr, 'array');

			// create save_id and remote the box_id from array
			$save_id = $savingOptionsArr['box_id'];
			unset($savingOptionsArr['box_id']); 

			// Verify that correct nonce was used with time limit.
			if( ! wp_verify_nonce( $savingOptionsArr['box_nonce'], $save_id . '-nonce')) die ('Busted!');
			unset($savingOptionsArr['box_nonce']);
 
			// remove the white space before asin
			if ( $save_id == 'WooZone_amazon' ) {

				if( isset($_SESSION['WooZone_country']) ){
					unset( $_SESSION['WooZone_country'] );
				}

				$_savingOptionsArr = $savingOptionsArr;
				$savingOptionsArr = array();
				foreach ($_savingOptionsArr as $key => $value) {
					if ( ! is_array($value) ) {
						// Check for and remove mistake in string after copy/paste keys 
						//if( $key == 'AccessKeyID' || $key == 'SecretAccessKey' ) {
						//	if( stristr($value, 'AWSAccessKeyId=') !== false ) {
						//		$value = str_ireplace('AWSAccessKeyId=', '', $value);
						//	}
						//	if( stristr($value, 'AWSSecretKey=') !== false ) {
						//		$value = str_ireplace('AWSSecretKey=', '', $value);
						//	}
						//}
						// update in 2018-mar-06
						if ( $key == 'AccessKeyID' || $key == 'SecretAccessKey' ) {
							unset( $savingOptionsArr[$key] );
						}
						else {
							$savingOptionsArr[$key] = trim($value);
						}
					} else {
						$savingOptionsArr[$key] = $value;
					}
				}

				$settings = get_option( $this->alias . '_amazon' ); // 'WooZone_amazon'
				$settings = maybe_unserialize( $settings );
				$settings = !empty($settings) && is_array($settings) ? $settings : array();

				foreach ( array('AccessKeyID', 'SecretAccessKey') as $awsKeyId ) {
					if ( isset($settings["$awsKeyId"]) ) {
						$savingOptionsArr["$awsKeyId"] = $settings["$awsKeyId"];
					}
				}
			}
						
			/*if ( $save_id == 'WooZone_report' ) {
				$__old_saving = get_option('WooZone_report', array());
				$__old_saving = maybe_unserialize(maybe_unserialize($__old_saving));
				$__old_saving = (array) $__old_saving;

				$savingOptionsArr["report"] = $__old_saving["report"];
			}*/
			
			// prepare the data for DB update
			$saveIntoDb = $savingOptionsArr;

			// Use the function update_option() to update a named option/value pair to the options database table. The option_name value is escaped with $wpdb->escape before the INSERT statement.
			update_option( $save_id, $saveIntoDb );
						
			$this->settings();
			
			// check for onsite cart option
			// 'WooZone_amazon'
			if( $save_id == $this->alias . '_amazon' ){
				$this->update_products_type( array(
					'products' => 'all'
				));
			}
			
			die(json_encode( array(
				'status' => 'ok',
				'html'   => 'Options updated successfully'
			)));
		}
		
		// saving the options
		public function install_default_options ()
		{
			// remove action from request
			unset($_REQUEST['action']);

			$is_makeinstall = isset($_REQUEST['is_makeinstall']) ? (int) $_REQUEST['is_makeinstall'] : 0;

			// unserialize the request options
			$serializedData = urldecode($_REQUEST['options']);
			
			$savingOptionsArr = array();
			parse_str($serializedData, $savingOptionsArr);
			
			// fix for setup
			if ( $savingOptionsArr['box_id'] == 'WooZone_setup_box' ) {
				$serializedData = preg_replace('/box_id=WooZone_setup_box&box_nonce=[\w]*&install_box=/', '', $serializedData);
				$savingOptionsArr['install_box'] = $serializedData;
				$savingOptionsArr['install_box'] = str_replace( "\\'", "\\\\'", $savingOptionsArr['install_box']);
			}
				
			// create save_id and remote the box_id from array
			$save_id = $savingOptionsArr['box_id'];
			unset($savingOptionsArr['box_id']);

			// Verify that correct nonce was used with time limit.
			if( ! wp_verify_nonce( $savingOptionsArr['box_nonce'], $save_id . '-nonce')) die ('Busted!');
			unset($savingOptionsArr['box_nonce']);
			
			// default sql - tables & tables data!
			require_once( $this->cfg['paths']['plugin_dir_path'] . 'modules/setup_backup/default-sql.php');

			//if ( $save_id != 'WooZone_setup_box' ) { //2016-june-21 fix
				$savingOptionsArr['install_box'] = str_replace( '\"', '"', $savingOptionsArr['install_box']);
			//}

			// convert to array
			$savingOptionsArr['install_box'] = str_replace('#!#', '&', $savingOptionsArr['install_box']);
			$savingOptionsArr['install_box'] = str_replace("'", "\'", $savingOptionsArr['install_box']); 
			$pullOutArray = json_decode( $savingOptionsArr['install_box'], true );
			if(count($pullOutArray) == 0){
				die(json_encode( array(
					'status' => 'error',
					'html'   => "Invalid install default json string, can't parse it!"
				)));
			}else{
 
				foreach ($pullOutArray as $key => $value){

					// prepare the data for DB update
					$saveIntoDb = $value;
					$saveIntoDb = is_bool($value) ? ( $value ? 'true' : 'false' ) : $value; //2016-june-21 fix
					
					//if( $saveIntoDb === true ){
					//  $saveIntoDb = 'true';
					//} else if( $saveIntoDb === false ){
					//  $saveIntoDb = 'false';
					//}

					// prepare the data for DB update
					//$saveIntoDb = $value != "true" ? serialize( $value ) : $value; //2016-june-21 fix
					
					//if ( 'WooZone_amazon' == $key ) {
					//    $saveIntoDb = $this->amazon_config_with_default( $value );
					//}
  
					// option already exists in db => don't overwrite it!
					if  ( $is_makeinstall && get_option( $key, false ) ) {
						//var_dump('<pre>',$key, 'exists in db.', '</pre>');						
						continue 1;
					}
					
					//var_dump('<pre>',$key, 'not found.', '</pre>');

					// Use the function update_option() to update a named option/value pair to the options database table. The option_name value is escaped with $wpdb->escape before the INSERT statement.
					update_option( $key, $saveIntoDb );
				}
 
				// update is_installed value to true 
				update_option( $this->alias . "_is_installed", 'true');

				die(json_encode( array(
					'status' => 'ok',
					'html'   => 'Install default successful'
				)));
			}
		}

		public function options_validate ( $input )
		{
			//var_dump('<pre>', $input  , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
		}

		public function module_change_status ( $resp='ajax' )
		{
			// remove action from request
			unset($_REQUEST['action']);

			// update into DB the new status
			$db_alias = $this->alias . '_module_' . $_REQUEST['module'];
			update_option( $db_alias, $_REQUEST['the_status'] );

			if ( !isset($resp) || empty($resp) || $resp == 'ajax' ) {
				die(json_encode(array(
					'status' => 'ok'
				)));
			}
		}
		
		public function module_bulk_change_status ()
		{
			global $wpdb; // this is how you get access to the database

			$request = array(
				'id'            => isset($_REQUEST['id']) && !empty($_REQUEST['id']) ? trim($_REQUEST['id']) : ''
			);
 
			if (trim($request['id'])!='') {
				$__rq2 = array();
				$__rq = explode(',', $request['id']);
				if (is_array($__rq) && count($__rq)>0) {
					foreach ($__rq as $k=>$v) {
						$__rq2[] = (string) $v;
					}
				} else {
					$__rq2[] = $__rq;
				}
				$request['id'] = implode(',', $__rq2);
			}

			if (is_array($__rq2) && count($__rq2)>0) {
				foreach ($__rq2 as $kk=>$vv) {
					$_REQUEST['module'] = $vv;
					$this->module_change_status( 'non-ajax' );
				}
				
				die( json_encode(array(
					'status' => 'valid',
					'msg'    => 'valid module change status Bulk'
				)) );
			}

			die( json_encode(array(
				'status' => 'invalid',
				'msg'    => 'invalid module change status Bulk'
			)) );
		}

		// loading the requested section
		public function load_section ()
		{
			$request = array(
				'section' => isset($_REQUEST['section']) ? strip_tags($_REQUEST['section']) : false
			);
			
			if( isset($request['section']) && $request['section'] == 'insane_mode' ){
				die( json_encode(array(
					'status' => 'redirect',
					'url'   => admin_url( 'admin.php?page=WooZone_insane_import' )
				)));
			}
			
			// get module if isset
			if(!in_array( $request['section'], $this->cfg['activate_modules'])) die(json_encode(array('status' => 'err', 'msg' => 'invalid section want to load!')));

			$tryed_module = $this->cfg['modules'][$request['section']];
			
			if( isset($tryed_module) && count($tryed_module) > 0 ){
				// Turn on output buffering
				ob_start();

				$opt_file_path = $tryed_module['folder_path'] . 'options.php';
				if( is_file($opt_file_path) ) {
					require_once( $opt_file_path  );
				}
				$options = ob_get_clean(); //copy current buffer contents into $message variable and delete current output buffer

				if(trim($options) != "") {
					$options = json_decode($options, true);

					// Derive the current path and load up aaInterfaceTemplates
					$plugin_path = dirname(__FILE__) . '/';
					if(class_exists('aaInterfaceTemplates') != true) {
						require_once($plugin_path . 'settings-template.class.php');

						// Initalize the your aaInterfaceTemplates
						$aaInterfaceTemplates = new aaInterfaceTemplates($this->cfg);

						// then build the html, and return it as string
						$html = $aaInterfaceTemplates->build_page($options, $this->alias, $tryed_module);

						// fix some URI
						$html = str_replace('{plugin_folder_uri}', $tryed_module['folder_uri'], $html);
						
						if(trim($html) != "") {
							$headline = '';
							if( isset($tryed_module[$request['section']]['in_dashboard']['icon']) ){
								$headline .= '<img src="' . ($tryed_module['folder_uri'] . $tryed_module[$request['section']]['in_dashboard']['icon'] ) . '" class="WooZone-headline-icon">';
							}
							$headline .= $tryed_module[$request['section']]['menu']['title'] . "<span class='WooZone-section-info'>" . ( $tryed_module[$request['section']]['description'] ) . "</span>";
							
							$has_help = isset($tryed_module[$request['section']]['help']) ? true : false;
							if( $has_help === true ){
								
								$help_type = isset($tryed_module[$request['section']]['help']['type']) && $tryed_module[$request['section']]['help']['type'] ? 'remote' : 'local';
								if( $help_type == 'remote' ){
									$headline .= '<a href="#load_docs" class="WooZone-show-docs" data-helptype="' . ( $help_type ) . '" data-url="' . ( $tryed_module[$request['section']]['help']['url'] ) . '" data-operation="help">HELP</a>';
								} 
							}
							
							$headline .= '<a href="#load_docs" class="WooZone-show-feedback" data-helptype="' . ( 'remote' ) . '" data-url="' . ( $this->feedback_url ) . '" data-operation="feedback">Feedback</a>';
 
							die( json_encode(array(
								'status'    => 'ok',
								'headline'  => $headline,
								'html'      =>  $html
							)) );
						}

						die(json_encode(array('status' => 'err', 'msg' => 'invalid html formatter!')));
					}
				}
			}
		}

		public function fatal_errors()
		{
			// print errors
			if(is_wp_error( $this->errors )) {
				$_errors = $this->errors->get_error_messages('fatal');

				if(count($_errors) > 0){
					foreach ($_errors as $key => $value){
						echo '<div class="error"> <p>' . ( $value ) . '</p> </div>';
					}
				}
			}
		}

		public function admin_warnings()
		{
			// print errors
			if(is_wp_error( $this->errors )) {
				$_errors = $this->errors->get_error_messages('warning');
				
				$current = get_option( $this->alias . "_dismiss_notice", array() );
				$current = !empty($current) && is_array($current) ? $current : array();
				//$is_dissmised = get_option( $this->alias . "_dismiss_notice" );
				// recommanded theme
				$theme_name = wp_get_theme(); //get_current_theme() - deprecated notice!
				if( $theme_name != "Kingdom - Woocommerce Amazon Affiliates Theme" ){
					
					//if( 1 ){
					if( !isset($current['theme']) || !$current['theme'] ){
						$_errors = array('
							<div class="woozone-themes">
								<div class="woozone-themesimgs">

									<a href="https://themeforest.net/item/bravostore-wzone-affiliates-theme-for-wordpress/20701838?ref=AA-Team" target="_blank">
										<img src="' . ( $this->cfg['paths']['plugin_dir_url'] . 'bravostore-theme.jpg' ) . '" />
										<h3>BravoStore</h3>
									</a>

									<a href="http://themeforest.net/item/kingdom-woocommerce-amazon-affiliates-theme/15163199?ref=AA-Team" target="_blank">
										<img src="' . ( $this->cfg['paths']['plugin_dir_url'] . 'kingdom-theme.jpg' ) . '" />
										<h3>Kingdom</h3>
									</a>
									
									<a href="http://codecanyon.net/item/the-market-woozone-affiliates-theme/13469852?ref=AA-Team" target="_blank">
										<img src="' . ( $this->cfg['paths']['plugin_dir_url'] . 'themarket-theme.jpg' ) . '" />
										<h3>The Market</h3>
									</a> 
								</div>	
								<p>For the <strong>Best Possible User Experience with the WooZone Plugin</strong> we highly Recommend using it in conjunction with any of the AA-Team custom Themes.</p>
							</div>
							<p>
								<strong>
									<a class="dismiss-notice" href="' . ( admin_url( 'admin-ajax.php?action=WooZoneDismissNotice&id=theme' ) ) . '" target="_parent">Dismiss this notice</a>
								</strong>
							</p>
						') ;
					}
				}

				// memory limit notice
				$memory = $this->let_to_num( WP_MEMORY_LIMIT );
				if ( $memory < 127108864 ) {
					if( !isset($current['memorylimit']) || !$current['memorylimit'] ){
						$_errors[] = '<p><strong style="color: red;">Current memory limit: ' . size_format( $memory ) . '</strong> | We recommend setting memory to at least 128MB. See: <a href="http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP">Increasing memory allocated to PHP</a> | <a class="dismiss-notice" href="' . ( admin_url( 'admin-ajax.php?action=WooZoneDismissNotice&id=memorylimit' ) ) . '" target="_parent">Dismiss this notice</a></p>';
					}
				}

				// soap module notice
				if ( extension_loaded('soap') || class_exists("SOAPClient") || class_exists("SOAP_Client") ) {
				} else {
					if( !isset($current['soap']) || !$current['soap'] ){
						$_errors[] = '<p>Your server does not have the <a href="http://php.net/manual/en/class.soapclient.php">SOAP Client</a> class enabled - some gateway plugins which use SOAP may not work as expected. | <a class="dismiss-notice" href="' . ( admin_url( 'admin-ajax.php?action=WooZoneDismissNotice&id=soap' ) ) . '" target="_parent">Dismiss this notice</a></p>';
					}
				}

				// woocommerce pages/shortcodes check
				$check_pages = array(
					_x( 'Cart Page', 'Page setting', 'woocommerce' ) => array(
							'option' => 'woocommerce_cart_page_id',
							'shortcode' => '[' . apply_filters( 'woocommerce_cart_shortcode_tag', 'woocommerce_cart' ) . ']'
						),
					_x( 'Checkout Page', 'Page setting', 'woocommerce' ) => array(
							'option' => 'woocommerce_checkout_page_id',
							'shortcode' => '[' . apply_filters( 'woocommerce_checkout_shortcode_tag', 'woocommerce_checkout' ) . ']'
						),
				);
				if ( class_exists( 'WooCommerce' ) ) {
					
					foreach ( $check_pages as $page_name => $values ) {
  
						$page_id = get_option( $values['option'], false );
	
						// Page ID check
						if ( ! $page_id ) {
							$_errors[] = '<p>' . sprintf( __( 'You need to install default WooCommerce page: %s', 'woocommerce' ), $page_name ) . '.</p>';
						} else {
							// Shortcode check
							if ( $values['shortcode'] ) {
								$page = get_post( $page_id );
								$_wpnonce_untrash = wp_create_nonce( 'untrash-post_' . $page_id );
				
								//var_dump('<pre>',$page ,'</pre>'); 
								if ( empty( $page ) ) {
									if( !isset($current['pageinstall']) || !$current['pageinstall'] ){
										$_errors[] = '<p><strong>Cart / Checkout</strong> page does not exist. Please install Woocommerce default pages from <a href="' . admin_url('admin.php?page=wc-status&tab=tools') . '" target="_blank">here</a>. | <a class="dismiss-notice" href="' . ( admin_url( 'admin-ajax.php?action=WooZoneDismissNotice&id=pageinstall' ) ) . '" target="_parent">Dismiss this notice</a></p>';
									}
								} elseif ( ! strstr( $page->post_content, $values['shortcode'] ) ) {
									if( !isset($current['pageshortcode']) || !$current['pageshortcode'] ){
										$_errors[] = '<p>The <strong>' . $page->post_title . '</strong> page does not contain the shortcode: <strong>' . $values['shortcode'] . '</strong> | <a class="dismiss-notice" href="' . ( admin_url( 'admin-ajax.php?action=WooZoneDismissNotice&id=pageshortcode' ) ) . '" target="_parent">Dismiss this notice</a></p>';
									}
								} elseif ( $page->post_status == 'trash' ) {
									if( !isset($current['pagetrash']) || !$current['pagetrash'] ){
										$_errors[] = '<p>The <strong>' . $page->post_title . '</strong> Woocommerce default page is in trash. Please <a href="' . admin_url('post.php?post=' . $page_id . '&action=untrash&_wpnonce=' . $_wpnonce_untrash) . '">restore it</a>. | <a class="dismiss-notice" href="' . ( admin_url( 'admin-ajax.php?action=WooZoneDismissNotice&id=pagetrash' ) ) . '" target="_parent">Dismiss this notice</a></p>';
									}
								}
							}
			
						}
			
						//$_errors[] = '<p>#' . absint( $page_id ) . ' - ' . str_replace( home_url(), '', get_permalink( $page_id ) ) . '</p>';
					}
				}

				if(count($_errors) > 0){
					foreach ($_errors as $key => $value){
						echo '<div class="updated"> <p>' . ( $value ) . '</p> </div>';
					}
				}
			}
		}

		public function let_to_num($size) {
			if ( function_exists('wc_let_to_num') ) {
				return wc_let_to_num( $size );
			}

			$l = substr($size, -1);
			$ret = substr($size, 0, -1);
			switch( strtoupper( $l ) ) {
				case 'P' :
					$ret *= 1024;
				case 'T' :
					$ret *= 1024;
				case 'G' :
					$ret *= 1024;
				case 'M' :
					$ret *= 1024;
				case 'K' :
					$ret *= 1024;
			}
			return $ret;
		}

		/**
		 * Builds the config parameters
		 *
		 * @param string $function
		 * @param array $params
		 *
		 * @return array
		 */
		protected function buildConfigParams($type, array $params)
		{
			// check if array exist
			if(isset($this->cfg[$type])){
				$params = array_merge( $this->cfg[$type], $params );
			}

			// now merge the arrays
			$this->cfg = array_merge(
				$this->cfg,
				array(  $type => array_merge( $params ) )
			);
		}

		/*
		 * admin_load_styles()
		 *
		 * Loads admin-facing CSS
		 */
		public function admin_get_frm_style() {
			$css = array();

			if( isset($this->cfg['freamwork-css-files'])
				&& is_array($this->cfg['freamwork-css-files'])
				&& !empty($this->cfg['freamwork-css-files'])
			) {

				foreach ($this->cfg['freamwork-css-files'] as $key => $value){
					if( is_file($this->cfg['paths']['freamwork_dir_path'] . $value) ) {
						
						$cssId = $this->alias . '-' . $key;
						$css["$cssId"] = $this->cfg['paths']['freamwork_dir_path'] . $value;
						// wp_enqueue_style( $this->alias . '-' . $key, $this->plugin_asset_get_path( 'css', $this->cfg['paths']['freamwork_dir_url'] . $value, true ), array(), $this->plugin_asset_get_version( 'css' ) );
					} else {
						$this->errors->add( 'warning', __('Invalid CSS path to file: <strong>' . $this->cfg['paths']['freamwork_dir_path'] . $value . '</strong>. Call in:' . __FILE__ . ":" . __LINE__ , $this->localizationName) );
					}
				}
			}
			return $css;
		}

		public function admin_load_styles()
		{
			global $wp_scripts;
			$protocol = is_ssl() ? 'https' : 'http';

			$javascript = $this->admin_get_scripts();

			wp_enqueue_style( $this->alias . '-google-Roboto',  $this->plugin_asset_get_path( 'css', $protocol . '://fonts.googleapis.com/css?family=Roboto:400,500,400italic,500italic,700,700italic', true ), array(), $this->plugin_asset_get_version( 'css' ) );

			//wp_enqueue_style( $this->alias . '-bootstrap', $this->plugin_asset_get_path( 'css', $protocol . '://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css', true ), array(), $this->plugin_asset_get_version( 'css' ) );
			$font_awesome = $protocol . '://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css';
			$font_awesome_cached = $this->cfg['paths']['freamwork_dir_path'] . 'css/font-awesome-v4.6.2.min.css';
			clearstatcache();
			if( is_file( $font_awesome_cached ) && is_readable( $font_awesome_cached ) ) {
				$font_awesome = $this->cfg['paths']['freamwork_dir_url'] . 'css/font-awesome-v4.6.2.min.css';
			}
			wp_enqueue_style( $this->alias . '-font-awesome', $this->plugin_asset_get_path( 'css', $font_awesome, true ), array(), $this->plugin_asset_get_version( 'css' ) );

			//tippyjs
			wp_enqueue_style( $this->alias . '-tippyjs', $this->plugin_asset_get_path( 'css', $this->cfg['paths']['freamwork_dir_url'] . 'js/tippyjs/tippy.min.css', true ), array(), $this->plugin_asset_get_version( 'css' ) );

			$main_style = admin_url('admin-ajax.php?action=WooZone_framework_style');
			$main_style_cached = $this->cfg['paths']['freamwork_dir_path'] . 'main-style.css';
			if( is_file( $main_style_cached ) ) {
				if( 
					-1 === $this->ss['css_cache_time'] //always use cache
					||
					(filemtime($main_style_cached) + $this->ss['css_cache_time']) > time()
				 ) {
					$main_style = $this->cfg['paths']['freamwork_dir_url'] . 'main-style.css';
				}
			}


			// !!! debug - please in the future, don't forget to comment it after you're finished with debugging
			//$main_style = admin_url('admin-ajax.php?action=WooZone_framework_style');

			wp_enqueue_style( $this->alias . '-main-style', $this->plugin_asset_get_path( 'css', $main_style, true ), array( $this->alias . '-font-awesome' ) );
			
			/*$style_url = $this->cfg['paths']['freamwork_dir_url'] . 'load-styles.php';
			if ( is_file( $this->cfg['paths']['freamwork_dir_path'] . 'load-styles.css' ) ) {
				$style_url = str_replace('.php', '.css', $style_url);
			}
			wp_enqueue_style( 'woozone-aa-framework-styles', $this->plugin_asset_get_path( 'css', $style_url, true ), array(), , $this->plugin_asset_get_version( 'css' ) );*/
			
			if( in_array( 'jquery-ui-core', $javascript ) ) {
				$ui = $wp_scripts->query('jquery-ui-core');
				if ($ui) {
					$uiBase = "http://code.jquery.com/ui/{$ui->ver}/themes/smoothness";
					wp_register_style('jquery-ui-core', "$uiBase/jquery-ui.css", FALSE, $ui->ver);
					wp_enqueue_style('jquery-ui-core');
				}
			}
			if( in_array( 'thickbox', $javascript ) ) wp_enqueue_style('thickbox');
		}

		/*
		 * admin_load_scripts()
		 *
		 * Loads admin-facing JavaScript
		 */
		public function admin_get_scripts() {
			$javascript = array();
			
			$current_url = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '';
			$current_url = explode("wp-admin/", $current_url);
			if( count($current_url) > 1 ){ 
				$current_url = "/wp-admin/" . $current_url[1];
			}else{
				$current_url = "/wp-admin/" . $current_url[0];
			}
	
			if ( isset($this->cfg['modules'])
				&& is_array($this->cfg['modules']) && !empty($this->cfg['modules'])
			) {
			foreach( $this->cfg['modules'] as $alias => $module ){

				if( isset($module[$alias]["load_in"]['backend']) && is_array($module[$alias]["load_in"]['backend']) && count($module[$alias]["load_in"]['backend']) > 0 ){
					// search into module for current module base on request uri
					foreach ( $module[$alias]["load_in"]['backend'] as $page ) {
	
						$expPregQuote = ( is_array($page) ? false : true );
							if ( is_array($page) ) $page = $page[0];

						$delimiterFound = strpos($page, '#');
						$page = substr($page, 0, ($delimiterFound!==false && $delimiterFound > 0 ? $delimiterFound : strlen($page)) );
						$urlfound = preg_match("%^/wp-admin/".($expPregQuote ? preg_quote($page) : $page)."%", $current_url);
							
						if(
							// $current_url == '/wp-admin/' . $page
							( ( $page == '@all' ) || ( $current_url == '/wp-admin/admin.php?page=WooZone' ) || ( !empty($page) && $urlfound > 0 ) )
							&& isset($module[$alias]['javascript']) ) {
	
							$javascript = array_merge($javascript, $module[$alias]['javascript']);
						}
					}
				}
			}
			} // end if
	
			$this->jsFiles = $javascript;
			return $javascript;
		}
		public function admin_load_scripts()
		{
			// very defaults scripts (in wordpress defaults)
			wp_enqueue_script( 'jquery' );
			
			$javascript = $this->admin_get_scripts();

			if ( count($javascript) > 0 ) {
				$javascript = @array_unique( $javascript );

				if( in_array( 'jquery-ui-core', $javascript ) ) wp_enqueue_script( 'jquery-ui-core' );
				if( in_array( 'jquery-ui-widget', $javascript ) ) wp_enqueue_script( 'jquery-ui-widget' );
				if( in_array( 'jquery-ui-mouse', $javascript ) ) wp_enqueue_script( 'jquery-ui-mouse' );
				if( in_array( 'jquery-ui-accordion', $javascript ) ) wp_enqueue_script( 'jquery-ui-accordion' );
				if( in_array( 'jquery-ui-autocomplete', $javascript ) ) wp_enqueue_script( 'jquery-ui-autocomplete' );
				if( in_array( 'jquery-ui-slider', $javascript ) ) wp_enqueue_script( 'jquery-ui-slider' );
				if( in_array( 'jquery-ui-tabs', $javascript ) ) wp_enqueue_script( 'jquery-ui-tabs' );
				if( in_array( 'jquery-ui-sortable', $javascript ) ) wp_enqueue_script( 'jquery-ui-sortable' );
				if( in_array( 'jquery-ui-draggable', $javascript ) ) wp_enqueue_script( 'jquery-ui-draggable' );
				if( in_array( 'jquery-ui-droppable', $javascript ) ) wp_enqueue_script( 'jquery-ui-droppable' );
				if( in_array( 'jquery-ui-datepicker', $javascript ) ) wp_enqueue_script( 'jquery-ui-datepicker' );
				if( in_array( 'jquery-ui-resize', $javascript ) ) wp_enqueue_script( 'jquery-ui-resize' );
				if( in_array( 'jquery-ui-dialog', $javascript ) ) wp_enqueue_script( 'jquery-ui-dialog' );
				if( in_array( 'jquery-ui-button', $javascript ) ) wp_enqueue_script( 'jquery-ui-button' );

				if( in_array( 'thickbox', $javascript ) ) wp_enqueue_script( 'thickbox' );
	
				// date & time picker
				if( !wp_script_is('jquery-timepicker') ) {
					if ( in_array( 'jquery-timepicker', $javascript ) ) {
						wp_enqueue_script( 'jquery-timepicker' , $this->plugin_asset_get_path( 'js', $this->cfg['paths']['freamwork_dir_url'] . 'js/jquery.timepicker.v1.1.1.min.js', true ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-slider' ), $this->plugin_asset_get_version( 'js' ) );
					}
				}
				
				wp_enqueue_script( 'sweetalert2-min' , $this->plugin_asset_get_path( 'js', $this->cfg['paths']['freamwork_dir_url'] . 'js/sweetalert2.min.js', true ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-slider' ), $this->plugin_asset_get_version( 'js' ) );
			}
	
			if ( count($this->cfg['freamwork-js-files']) > 0 ) {
				foreach ($this->cfg['freamwork-js-files'] as $key => $value) {

					if ( is_file($this->cfg['paths']['freamwork_dir_path'] . $value) ) {
						if( in_array( $key, $javascript ) ) {
							wp_enqueue_script( $this->alias . '-' . $key, $this->plugin_asset_get_path( 'js', $this->cfg['paths']['freamwork_dir_url'] . $value, true ), array(), $this->plugin_asset_get_version( 'js' ) );
						}
					} else {
						$this->errors->add( 'warning', __('Invalid JS path to file: <strong>' . $this->cfg['paths']['freamwork_dir_path'] . $value . '</strong> . Call in:' . __FILE__ . ":" . __LINE__ , $this->localizationName) );
					}
				}
			}
		}

		/*
		 * Builds out the options panel.
		 *
		 * If we were using the Settings API as it was likely intended we would use
		 * do_settings_sections here. But as we don't want the settings wrapped in a table,
		 * we'll call our own custom wplanner_fields. See options-interface.php
		 * for specifics on how each individual field is generated.
		 *
		 * Nonces are provided using the settings_fields()
		 *
		 * @param array $params
		 * @param array $options (fields)
		 *
		 */
		public function createDashboardPage ()
		{
			add_menu_page(
				__( 'WooZone - Amazon Affiliates', $this->localizationName ),
				__( 'WooZone', $this->localizationName ),
				'manage_options',
				$this->alias,
				array( $this, 'manage_options_template' ),
				$this->cfg['paths']['plugin_dir_url'] . 'icon_16.png'
			);
			
			add_submenu_page(
					$this->alias,
					$this->alias . " " . __('Amazon plugin configuration', $this->localizationName),
							__('Amazon config', $this->localizationName),
							'manage_options',
							$this->alias . "&section=amazon",
							array( $this, 'manage_options_template')
					);
			
			
			if( $this->verify_module_status('advanced_search') == true ) {
				add_submenu_page(
						$this->alias,
						$this->alias . " " . __('Amazon Advanced Search', $this->localizationName),
								__('Amazon Search', $this->localizationName),
								'manage_options',
								$this->alias . "&section=advanced_search",
								array( $this, 'manage_options_template')
						);
			}
			
			add_submenu_page(
					$this->alias,
					$this->alias . " " . __('Amazon Import Insane Mode', $this->localizationName),
							__('Insane Mode Import', $this->localizationName),
							'manage_options',
							$this->alias . "&section=insane_mode",
							array( $this, 'insane_import_redirect')
					);
			
			if( $this->verify_module_status('csv_products_import') == true ) {
				add_submenu_page(
						$this->alias,
						$this->alias . " " . __('CSV bulk products import', $this->localizationName),
								__('CSV import', $this->localizationName),
								'manage_options',
								$this->alias . "&section=csv_products_import",
								array( $this, 'manage_options_template')
						);
			}
		}

		public function manage_options_template()
		{
			// Derive the current path and load up aaInterfaceTemplates
			$plugin_path = dirname(__FILE__) . '/';
			if(class_exists('aaInterfaceTemplates') != true) {
				require_once($plugin_path . 'settings-template.class.php');

				// Initalize the your aaInterfaceTemplates
				$aaInterfaceTemplates = new aaInterfaceTemplates($this->cfg);

				// try to init the interface
				$aaInterfaceTemplates->printBaseInterface();
			}
		}

		public function insane_import_redirect()
		{
			echo __FILE__ . ":" . __LINE__;die . PHP_EOL;   
		}

		/**
		 * Getter function, plugin config
		 *
		 * @return array
		 */
		public function getCfg()
		{
			return $this->cfg;
		}

		/**
		 * Getter function, plugin all settings
		 *
		 * @params $returnType
		 * @return array
		 */
		public function getAllSettings( $returnType='array', $only_box='', $this_call=false )
		{
			if( $this_call == true ){
				//var_dump('<pre>',$returnType, $only_box,'</pre>');  
			}
			$allSettingsQuery = "SELECT * FROM " . $this->db->prefix . "options where 1=1 and option_name REGEXP '" . ( $this->alias) . "_([a-z])'";
			if (trim($only_box) != "") {
				$allSettingsQuery = "SELECT * FROM " . $this->db->prefix . "options where 1=1 and option_name = '" . ( $this->alias . '_' . $only_box) . "' LIMIT 1;";
			}
			$results = $this->db->get_results( $allSettingsQuery, ARRAY_A);
			
			// prepare the return
			$return = array();
			if( count($results) > 0 ){
				foreach ($results as $key => $value){
					if($value['option_value'] == 'true'){
						$return[$value['option_name']] = true;
					}else{
						//$return[$value['option_name']] = @unserialize(@unserialize($value['option_value']));
						$return[$value['option_name']] = maybe_unserialize($value['option_value']);
					}
				}
			}

			if(trim($only_box) != "" && isset($return[$this->alias . '_' . $only_box])){
				$return = $return[$this->alias . '_' . $only_box];
			}
 
			if($returnType == 'serialize'){
				return serialize($return);
			}else if( $returnType == 'array' ){
				return maybe_unserialize( $return );
			}else if( $returnType == 'json' ){
				return json_encode($return);
			}

			return false;
		}

		/**
		 * Getter function, all products
		 *
		 * @params $returnType
		 * @return array
		 */
		public function getAllProductsMeta( $returnType='array', $key='' )
		{
			// SELECT * FROM " . $this->db->prefix . "postmeta where 1=1 and meta_key='" . ( $key ) . "'
			$allSettingsQuery = "SELECT a.meta_value FROM " . $this->db->prefix . "postmeta AS a LEFT OUTER JOIN " . $this->db->prefix . "posts AS b ON a.post_id=b.ID WHERE 1=1 AND a.meta_key='" . ( $key ) . "' AND !ISNULL(b.ID) AND b.post_type IN ('product', 'product_variation')";
			$results = $this->db->get_results( $allSettingsQuery, ARRAY_A);
			
			//"SELECT * FROM wp_postmeta where 1=1 and meta_key='_amzASIN'";
			//$deleteAllAmzMeta = "DELETE FROM " . $this->db->prefix . "postmeta where 1=1 and meta_key='" . ( $key ) . "'";
			//$delAmzMetaNow = $this->db->query( 
			//                  $this->db->prepare( $deleteAllAmzMeta )
			//              );
			//echo $delAmzMetaNow;
			
			// prepare the return
			$return = array();
			if( count($results) > 0 ){
				foreach ($results as $key => $value){
					if(trim($value['meta_value']) != ""){
						$return[] = $value['meta_value'];
					}
				}
			}

			if($returnType == 'serialize'){
				return serialize($return);
			}
			else if( $returnType == 'text' ){
				return implode("\n", $return);
			}
			else if( $returnType == 'array' ){
				return $return;
			}
			else if( $returnType == 'json' ){
				return json_encode($return);
			}

			return false;
		}

		/*
		 * GET modules lists
		 */
		public function load_modules( $pluginPage='' )
		{
			$GLOBALS['WooZone'] = $this;
			
			$folder_path = $this->cfg['paths']['plugin_dir_path'] . 'modules/';
			$cfgFileName = 'config.php';

			// static usage, modules menu order
			$menu_order = array();

			$modules_list = glob($folder_path . '*/' . $cfgFileName);
			
			$nb_modules = count($modules_list);
			if ( $nb_modules > 0 ) {
				foreach ($modules_list as $key => $mod_path ) {

					$dashboard_isfound = preg_match("/modules\/dashboard\/config\.php$/", $mod_path);
					$depedencies_isfound = preg_match("/modules\/depedencies\/config\.php$/", $mod_path);
					
					if ( $pluginPage == 'depedencies' ) {
						if ( $depedencies_isfound!==false && $depedencies_isfound>0 ) ;
						else continue 1;
					} else {
						if ( $dashboard_isfound!==false && $dashboard_isfound>0 ) {
							unset($modules_list[$key]);
							$modules_list[$nb_modules] = $mod_path;
						}
					}
				}
			}
	
			foreach ($modules_list as $module_config ) {
				$module_folder = str_replace($cfgFileName, '', $module_config);

				// Turn on output buffering
				ob_start();

				if( is_file( $module_config ) ) {
					require_once( $module_config  );
				}
				$settings = ob_get_clean(); //copy current buffer contents into $message variable and delete current output buffer

				if(trim($settings) != "") {
					$settings = json_decode($settings, true);
					$settings_keys = array_keys($settings);
					$alias = (string)end($settings_keys);

					// create the module folder URI
					// fix for windows server
					$module_folder = str_replace( DIRECTORY_SEPARATOR, '/',  $module_folder );

					$__tmpUrlSplit = explode("/", $module_folder);
					$__tmpUrl = '';
					$nrChunk = count($__tmpUrlSplit);
					if($nrChunk > 0) {
						foreach ($__tmpUrlSplit as $key => $value){
							if( $key > ( $nrChunk - 4) && trim($value) != ""){
								$__tmpUrl .= $value . "/";
							}
						}
					}

					// get the module status. Check if it's activate or not
					$status = false;

					// default activate all core modules
					if ( $pluginPage == 'depedencies' ) {
						if ( $alias != 'depedencies' ) continue 1;
						else $status = true;
					} else {
						if ( $alias == 'depedencies' ) continue 1;
						
						if(in_array( $alias, $this->cfg['core-modules'] )) {
							$status = true;
						}else{
							// activate the modules from DB status
							$db_alias = $this->alias . '_module_' . $alias;
	
							if(get_option($db_alias) == 'true'){
								$status = true;
							}
						}
					}
	
					// push to modules array
					$this->cfg['modules'][$alias] = array_merge(array(
						'folder_path'   => $module_folder,
						'folder_uri'    => $this->cfg['paths']['plugin_dir_url'] . $__tmpUrl,
						'db_alias'      => $this->alias . '_' . $alias,
						'alias'         => $alias,
						'status'        => $status
					), $settings );

					// add to menu order array
					if(!isset($this->cfg['menu_order'][(int)$settings[$alias]['menu']['order']])){
						$this->cfg['menu_order'][(int)$settings[$alias]['menu']['order']] = $alias;
					}else{
						// add the menu to next free key
						$this->cfg['menu_order'][] = $alias;
					}

					// add module to activate modules array
					if($status == true){
						$this->cfg['activate_modules'][$alias] = true;
					}

					// load the init of current loop module
					if( $this->debug === true ) {
						$time_start = microtime(true);
						$start_memory_usage = (memory_get_usage());
					}
					
					// in backend
					if( $this->is_admin === true && isset($settings[$alias]["load_in"]['backend']) ){
						
						$need_to_load = false;
						if( is_array($settings[$alias]["load_in"]['backend']) && count($settings[$alias]["load_in"]['backend']) > 0 ){
						
							$current_url = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '';
							$current_url = explode("wp-admin/", $current_url);
							if( count($current_url) > 1 ){ 
								$current_url = "/wp-admin/" . $current_url[1];
							}else{
								$current_url = "/wp-admin/" . $current_url[0];
							}
							
							foreach ( $settings[$alias]["load_in"]['backend'] as $page ) {

								$expPregQuote = ( is_array($page) ? false : true );
									if ( is_array($page) ) $page = $page[0];

								$delimiterFound = strpos($page, '#');
								$page = substr($page, 0, ($delimiterFound!==false && $delimiterFound > 0 ? $delimiterFound : strlen($page)) );
								$urlfound = preg_match("%^/wp-admin/".($expPregQuote ? preg_quote($page) : $page)."%", $current_url);
								
								$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
								$section = isset($_REQUEST['section']) ? $_REQUEST['section'] : '';
								if(
									// $current_url == '/wp-admin/' . $page ||
									( ( $page == '@all' ) || ( $current_url == '/wp-admin/admin.php?page=WooZone' ) || ( !empty($page) && $urlfound > 0 ) )
									|| ( $action == 'WooZoneLoadSection' && $section == $alias )
									|| substr($action, 0, 3) == 'WooZone'
								){
									$need_to_load = true;  
								}
							}
						}
	
						if( $need_to_load == false ){
							continue;
						}  
					}
					
					if( $this->is_admin === false && isset($settings[$alias]["load_in"]['frontend']) ){
						
						$need_to_load = false;
						if( $settings[$alias]["load_in"]['frontend'] === true ){
							$need_to_load = true;
						}
						if( $need_to_load == false ){
							continue;
						}  
					}

					// load the init of current loop module
					//var_dump(array($alias => $this->cfg['modules'][$alias]));
					//die();
					if( $status == true && isset( $settings[$alias]['module_init'] ) ){
						if( is_file($module_folder . $settings[$alias]['module_init']) ){
							//if( $this->is_admin ) {
								$current_module = array($alias => $this->cfg['modules'][$alias]);
								$GLOBALS['WooZone_current_module'] = $current_module;
								
								require_once( $module_folder . $settings[$alias]['module_init'] );
								
								if( $this->debug === true ) {
									$time_end = microtime(true);
									$this->cfg['modules'][$alias]['loaded_in'] = $time_end - $time_start;
									
									$this->cfg['modules'][$alias]['memory_usage'] = (memory_get_usage() ) - $start_memory_usage;
									if( (float)$this->cfg['modules'][$alias]['memory_usage'] < 0 ){
										$this->cfg['modules'][$alias]['memory_usage'] = 0.0;
									}
								}
							//}
						}
					}
				}
			}
	
			// order menu_order ascendent
			ksort($this->cfg['menu_order']);
		}

		public function print_plugin_usages()
		{
			$html = array();
			
			$html[] = '<style type="text/css">
				.WooZone-bench-log {
					border: 1px solid #ccc; 
					width: 450px; 
					position: absolute; 
					top: 92px; 
					right: 2%;
					background: #95a5a6;
					color: #fff;
					font-size: 12px;
					z-index: 99999;
					
				}
					.WooZone-bench-log th {
						font-weight: bold;
						background: #34495e;
					}
					.WooZone-bench-log th,
					.WooZone-bench-log td {
						padding: 4px 12px;
					}
				.WooZone-bench-title {
					position: absolute; 
					top: 55px; 
					right: 2%;
					width: 425px; 
					margin: 0px 0px 0px 0px;
					font-size: 20px;
					background: #ec5e00;
					color: #fff;
					display: block;
					padding: 7px 12px;
					line-height: 24px;
					z-index: 99999;
				}
			</style>';
			
			$html[] = '<h1 class="WooZone-bench-title">WooZone: Benchmark performance</h1>';
			$html[] = '<table class="WooZone-bench-log">';
			$html[] =   '<thead>';
			$html[] =       '<tr>';
			$html[] =           '<th>Module</th>';
			$html[] =           '<th>Loading time</th>';
			$html[] =           '<th>Memory usage</th>';
			$html[] =       '</tr>';
			$html[] =   '</thead>';
			
			
			$html[] =   '<tbody>';
			
			$total_time = 0;
			$total_size = 0;
			foreach ($this->cfg['modules'] as $key => $module ) {

				$html[] =       '<tr>';
				$html[] =           '<td>' . ( $key ) . '</td>';
				$html[] =           '<td>' . ( number_format($module['loaded_in'], 4) ) . '(seconds)</td>';
				$html[] =           '<td>' . (  $this->formatBytes($module['memory_usage']) ) . '</td>';
				$html[] =       '</tr>';
			
				$total_time = $total_time + $module['loaded_in']; 
				$total_size = $total_size + $module['memory_usage']; 
			}

			$html[] =       '<tr>';
			$html[] =           '<td colspan="3">';
			$html[] =               'Total time: <strong>' . ( $total_time ) . '(seconds)</strong><br />';          
			$html[] =               'Total Memory: <strong>' . ( $this->formatBytes($total_size) ) . '</strong><br />';         
			$html[] =           '</td>';
			$html[] =       '</tr>';

			$html[] =   '</tbody>';
			$html[] = '</table>';
			
			//echo '<script>jQuery("body").append(\'' . ( implode("\n", $html ) ) . '\')</script>';
			echo implode("\n", $html );
		}

		public function check_secure_connection ()
		{

			$secure_connection = false;
			if(isset($_SERVER['HTTPS']))
			{
				if ($_SERVER["HTTPS"] == "on")
				{
					$secure_connection = true;
				}
			}
			return $secure_connection;
		}


		/*
			helper function, image_resize
			// use timthumb
		*/
		public function image_resize ($src='', $w=100, $h=100, $zc=2)
		{
			// in no image source send, return no image
			if( trim($src) == "" ){
				$src = $this->cfg['paths']['freamwork_dir_url'] . '/images/no-product-img.jpg';
			}

			if( is_file($this->cfg['paths']['plugin_dir_path'] . 'timthumb.php') ) {
				return $this->cfg['paths']['plugin_dir_url'] . 'timthumb.php?src=' . $src . '&w=' . $w . '&h=' . $h . '&zc=' . $zc;
			}
		}

		/*
			helper function, upload_file
		*/
		public function upload_file ()
		{
			$slider_options = '';
			 // Acts as the name
						$clickedID = $_POST['clickedID'];
						// Upload
						if ($_POST['type'] == 'upload') {
								$override['action'] = 'wp_handle_upload';
								$override['test_form'] = false;
				$filename = $_FILES [$clickedID];

								$uploaded_file = wp_handle_upload($filename, $override);
								if (!empty($uploaded_file['error'])) {
										echo json_encode(array("error" => "Upload Error: " . $uploaded_file['error']));
								} else {
										echo json_encode(array(
							"url" => $uploaded_file['url'],
							"thumb" => ($this->image_resize( $uploaded_file['url'], $_POST['thumb_w'], $_POST['thumb_h'], $_POST['thumb_zc'] ))
						)
					);
								} // Is the Response
						}else{
				echo json_encode(array("error" => "Invalid action send" ));
			}

						die();
		}

		public function download_image( $file_url='', $pid=0, $action='insert', $product_title='', $step=0 )
		{
			if(trim($file_url) != ""){
				
				if( $this->amz_settings["rename_image"] == 'product_title' ){
					$image_name = sanitize_file_name($product_title);
					$image_name = preg_replace("/[^a-zA-Z0-9-]/", "", $image_name);
					$image_name = substr($image_name, 0, 200);
				}else{
					$image_name = uniqid();
				}
				
				// Find Upload dir path
				$uploads = wp_upload_dir();
				$uploads_path = $uploads['path'] . '';
				$uploads_url = $uploads['url'];

				$fileExt = explode(".", $file_url);
								$fileExt = end($fileExt);
				$filename = $image_name . "-" . ( $step ) . "." . $fileExt;
				
				// Save image in uploads folder
				$response = wp_remote_get( $file_url );
	
				if( !is_wp_error( $response ) ){
					$image = $response['body'];
					
					$image_url = $uploads_url . '/' . $filename; // URL of the image on the disk
					$image_path = $uploads_path . '/' . $filename; // Path of the image on the disk
					$ii = 0;
					while ( $this->verifyFileExists($image_path) ) {
						$filename = $image_name . "-" . ( $step );
						$filename .= '-'.$ii;
						$filename .= "." . $fileExt;
						
						$image_url = $uploads_url . '/' . $filename; // URL of the image on the disk
						$image_path = $uploads_path . '/' . $filename; // Path of the image on the disk
						$ii++;
					}

					// verify image hash
					$hash = md5($image);
					$hashFound = $this->verifyProdImageHash( $hash );
					if ( !empty($hashFound) && isset($hashFound->media_id) ) { // image hash not found!
					
						$orig_attach_id = $hashFound->media_id;
						// $attach_data = wp_get_attachment_metadata( $orig_attach_id );
						// $image_path = $uploads_path . '/' . basename($attach_data['file']);
						$image_path = $hashFound->image_path;

						// Add image in the media library - Step 3
						/*$wp_filetype = wp_check_filetype( basename( $image_path ), null );
						$attachment = array(
							'post_mime_type' => $wp_filetype['type'],
							'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $image_path ) ),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);
	 
						// $attach_id = wp_insert_attachment( $attachment, $image_path, $pid  );
						require_once( ABSPATH . 'wp-admin/includes/image.php' );
						wp_update_attachment_metadata( $attach_id, $attach_data );*/
						
						return array(
							'attach_id'         => $orig_attach_id, // $attach_id,
							'image_path'        => $image_path,
							'hash'              => $hash
						);
					}
					//write image if the wp method fails
					$has_wrote = $this->wp_filesystem->put_contents(
						$uploads_path . '/' . $filename, $image, FS_CHMOD_FILE
					);
					
					if( !$has_wrote ){
						file_put_contents( $uploads_path . '/' . $filename, $image );
					}

					// Add image in the media library - Step 3
					$wp_filetype = wp_check_filetype( basename( $image_path ), null );
					$attachment = array(
						// 'guid'           => $image_url,
						'post_mime_type' => $wp_filetype['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $image_path ) ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					);
 
					$attach_id = wp_insert_attachment( $attachment, $image_path, $pid  ); 
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					$attach_data = wp_generate_attachment_metadata( $attach_id, $image_path );
					wp_update_attachment_metadata( $attach_id, $attach_data );
	
					return array(
						'attach_id'         => $attach_id,
						'image_path'        => $image_path,
						'hash'              => $hash
					);
				}
				else{
					return array(
						'status'    => 'invalid',
						'msg'       => htmlspecialchars( implode(';', $response->get_error_messages()) )
					);
				}
			}
		}
		
		public function verifyProdImageHash( $hash ) {
			require( $this->cfg['paths']['plugin_dir_path'] . '/modules/assets_download/init.php' );
			$WooZoneAssetDownloadCron = new WooZoneAssetDownload();
			
			return $WooZoneAssetDownloadCron->verifyProdImageHash( $hash );
		}

		public function remove_gallery($content)
		{
			return str_replace('[gallery]', '', $content);
		}

		public function addNewProduct( $retProd=array(), $pms=array() )
		{
			$default_pms = array(
				'ws'                    => 'amazon',

				'operation_id'          => '',
			
				'import_to_category'    => 'amz',

				'import_images'         => isset($this->amz_settings["number_of_images"])
					&& (int) $this->amz_settings["number_of_images"] > 0
					? (int) $this->amz_settings["number_of_images"] : 'all',

				'import_variations'     => isset($this->amz_settings['product_variation'])
					? $this->amz_settings['product_variation'] : 'yes_5',

				'spin_at_import'        => isset($this->amz_settings['spin_at_import'])
					&& ($this->amz_settings['spin_at_import'] == 'yes') ? true : false,
									
				'import_attributes'     => isset($this->amz_settings['item_attribute'])
					&& ($this->amz_settings['item_attribute'] == 'no') ? false : true,
			);
			$pms = array_merge( $default_pms, $pms );

			$durationQueue = array(); // Duration Queue
			$this->timer_start(); // Start Timer

			//---------------------
			//:: status messages
			$this->opStatusMsgInit(array(
				'operation_id'  => $pms['operation_id'],
				'operation'     => 'add_prod',
			));

			$ret = array(
				'status' 		=> 'invalid',
				'msg' 			=> '',
				'insert_id' 	=> 0,
			);
			$msg = array();

			//---------------------
			//:: empty amazon response?
			if ( count($retProd) == 0 ) {
				// status messages
				$msg[] = $this->opStatusMsgSet(array(
					'msg'       => 'empty product array from amazon!',
					'duration'  => $this->timer_end(), // End Timer
				));

				$ret = array_replace_recursive( $ret, array(
					'msg' 	=> implode('<br />', $msg),
				));
				return $ret;
			}
			
			$default_import = !isset($this->amz_settings["default_import"])
				|| ($this->amz_settings["default_import"] == 'publish')
				? 'publish' : 'draft';
			$default_import = strtolower($default_import);

			//---------------------
			//:: verify if : amazon zero price product!
			$price_zero_import = isset($this->amz_settings["import_price_zero_products"])
				&& $this->amz_settings["import_price_zero_products"] == 'yes'
				? true : false;

			if ( ! $price_zero_import && $this->get_ws_object( $this->cur_provider )->is_product_price_zero( $retProd ) ) {
				// status messages
				$msg[] = $this->opStatusMsgSet(array(
					'msg'       => 'price is zero, so it is skipped!',
					'duration'  => $this->timer_end(), // End Timer
				));

				$ret = array_replace_recursive( $ret, array(
					'msg' 	=> implode('<br />', $msg),
				));
				return $ret;
			}

			//---------------------
			//:: verify if : amazon missing offerlistingid product!
			if ( ! $this->import_product_offerlistingid_missing ) {

				$prod_has_offerlistingid = $this->get_ws_object( $this->cur_provider )->productHasOfferlistingid( array(
					'verify_variations' => true,
					'thisProd' 	=> $retProd,
					'post_id' 	=> 0,
				));
				//var_dump('<pre>', $prod_has_offerlistingid , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				if ( ! $prod_has_offerlistingid ) {
					// status messages
					$msg[] = $this->opStatusMsgSet(array(
						'msg'       => 'offerListingId is missing, so it is skipped!',
						'duration'  => $this->timer_end(), // End Timer
					));

					$ret = array_replace_recursive( $ret, array(
						'msg' 	=> implode('<br />', $msg),
					));
					return $ret;
				}
			}

			//---------------------
			//:: verify if : merchant is "only_amazon" and product has amazon among its sellers
			$merchant_is_amazon_only_import = isset($this->amz_settings["merchant_setup"])
				&& 'only_amazon' == $this->amz_settings["merchant_setup"] 
				? true : false;

			if ( $merchant_is_amazon_only_import
				&& ! $this->get_ws_object( $this->cur_provider )->product_has_amazon_seller( $retProd )
			) {
				// status messages
				$msg[] = $this->opStatusMsgSet(array(
					'msg'       => 'merchant setup is "only_amazon" and the product doesn\'t have amazon among its sellers!',
					'duration'  => $this->timer_end(), // End Timer
				));

				$ret = array_replace_recursive( $ret, array(
					'msg' 	=> implode('<br />', $msg),
				));
				return $ret;
			}

			//---------------------
			//:: build post data & import it if not exists in database
			$product_desc = $this->product_build_desc($retProd);
			$excerpt = isset($product_desc['short']) ? $product_desc['short'] : '';
			$desc = isset($product_desc['desc']) ? $product_desc['desc'] : '';

			$args = array(
				'post_title'    => $retProd['Title'],
				'post_status'   => $default_import,
				'post_content'  => $desc,
				'post_excerpt'  => $excerpt,
				'post_type'     => 'product',
				'menu_order'    => 0,
				'post_author'   => 1, //get_current_user_id()
			);

			$existProduct = amzStore_bulk_wp_exist_post_by_args($args);
			$metaPrefix = 'amzStore_product_';

			// check if post exists
			if ( $existProduct === false){
				$lastId = wp_insert_post($args);
								
				$duration = $this->timer_end(); // End Timer
				// status messages
				$msg[] = $this->opStatusMsgSet(array(
					'status'    => 'valid',
					'msg'       => 'product inserted with ID: ' . $lastId,
					'duration'  => $duration,
				));
			} else {
				$lastId = $existProduct['ID'];
								
				$duration = $this->timer_end(); // End Timer
				// status messages
				$msg[] = $this->opStatusMsgSet(array(
					'status'    => 'valid',
					'msg'       => 'product already exists with ID: ' . $lastId,
					'duration'  => $duration,
				));
			}

			apply_filters( 'WooZone_after_product_import', $lastId );
	
			$durationQueue[] = $this->timer_end(); // End Timer
			$this->timer_start(); // Start Timer

			//---------------------
			//:: spin post/product content!
			if ( $pms['spin_at_import'] ) {

				$replacements_nb = 10;
				if ( isset($this->amz_settings['spin_max_replacements']) )
					$replacements_nb = (int) $this->amz_settings['spin_max_replacements'];

				$this->spin_content(array(
					'prodID'        => $lastId,
					'replacements'  => $replacements_nb
				));
								
				$duration = $this->timer_end(); // End Timer
				$this->timer_start(); // Start Timer
				
				// status messages
				$msg[] = $this->opStatusMsgSet(array(
					'status'    => 'valid',
					'msg'       => 'spin content done',
					'duration'  => $duration,
				));
				
				// add last import report
				$this->add_last_imports('last_import_spin', array('duration' => $duration)); // End Timer & Add Report
			}

			//---------------------
			//:: import images - just put images paths to assets table
			if ( ( $pms['import_images'] === 'all' ) || ( (int) $pms['import_images'] > 0 ) ) {

				// get product images
				$setImagesStatus = $this->get_ws_object( $this->cur_provider )->set_product_images(
					$retProd,
					$lastId,
					0,
					$pms['import_images']
				);
				
				$duration = $this->timer_end(); // End Timer
				$durationQueue[] = $duration; // End Timer
				$this->timer_start(); // Start Timer

				// status messages
				$msg[] = $this->opStatusMsgSet(array(
					'status'    => 'valid',
					'msg'       => $setImagesStatus['msg'],
					'duration'  => $duration,
				));
			}
			
			$durationQueue[] = $this->timer_end(); // End Timer
			$this->timer_start(); // Start Timer

			//---------------------
			//:: import to category
			if ( $pms['import_to_category'] != 'amz' ) {
				
				$tocateg = $pms['import_to_category'];

				$final_categs = array();
				$final_categs[] = (int) $tocateg;
				
				$ancestors = get_ancestors( (int) $tocateg, 'product_cat' );  
				 
				if( count( $ancestors ) > 0 && is_array( $ancestors ) && $ancestors != '' ) {
					$final_categs = array_merge( $final_categs, $ancestors );    
				}
				 
				// set the post category
				wp_set_object_terms( $lastId, $final_categs, 'product_cat', true);

			}
			else {
				$tocateg = $retProd['BrowseNodes'];

				// setup product categories
				$createdCats = $this->get_ws_object( $this->cur_provider )->set_product_categories( $tocateg );
				
				// Assign the post on the categories created
				wp_set_post_terms( $lastId,  $createdCats, 'product_cat' );
			}

			//---------------------
			//:: product tags
			if ( isset($retProd['Tags']) && !empty($retProd['Tags']) ) {
				// setup product tags
				$createdTags = $this->get_ws_object( $this->cur_provider )->set_product_tags( $retProd['Tags'] );
				
				// Assign the post on the categories created
				if ( !empty($createdTags) ) {
					wp_set_post_terms( $lastId,  $createdTags, 'product_tag' );
				}
			}
						
			$duration = $this->timer_end(); // End Timer
			$durationQueue[] = $duration; // End Timer
			$this->timer_start(); // Start Timer
			
			// status messages
			$msg[] = $this->opStatusMsgSet(array(
				'status'    => 'valid',
				'msg'       => 'set product categories',
				'duration'  => $duration,
			));
			
			//---------------------
			//:: import attributes
			if ( $pms['import_attributes'] ) {
				if ( count($retProd['ItemAttributes']) > 0 ) {
					$this->timer_start(); // Start Timer
				}

				// add product attributes
				$this->get_ws_object( $this->cur_provider )->set_woocommerce_attributes( $retProd['ItemAttributes'], $lastId );
								
				if ( count($retProd['ItemAttributes']) > 0 ) {
					$duration = $this->timer_end(); // End Timer
					$this->timer_start(); // Start Timer
			
					// status messages
					$msg[] = $this->opStatusMsgSet(array(
						'status'    => 'valid',
						'msg'       => 'import attributes',
						'duration'  => $duration,
					));

					// add last import report
					$this->add_last_imports('last_import_attributes', array(
						'duration'      => $duration,
					)); // End Timer & Add Report
				}
			}

			//---------------------
			//:: than update the post metas
			$this->get_ws_object( $this->cur_provider )->set_product_meta_options( $retProd, $lastId, false );
						
			$duration = $this->timer_end(); // End Timer
			$durationQueue[] = $duration; // End Timer
			$this->timer_start(); // Start Timer
			
			// status messages
			$msg[] = $this->opStatusMsgSet(array(
				'status'    => 'valid',
				'msg'       => 'set product metas',
				'duration'  => $duration,
			));

			//---------------------
			//:: set the product price
			$this->get_ws_object( $this->cur_provider )->get_product_price(
				$retProd,
				$lastId,
				array( 'do_update' => true )
			);
						
			$duration = $this->timer_end(); // End Timer
			$durationQueue[] = $duration; // End Timer
			$this->timer_start(); // Start Timer
			
			// status messages
			$msg[] = $this->opStatusMsgSet(array(
				'status'    => 'valid',
				'msg'       => 'product price update',
				'duration'  => $duration,
			));

			//---------------------						
			//:: IMPORT PRODUCT VARIATIONS
			if ( $pms['import_variations'] != 'no' ) {
				$this->timer_start(); // Start Timer

				// current message
				$current_msg = $this->opStatusMsg['msg'];

				$setVariationsStatus = $this->get_ws_object( $this->cur_provider )->set_woocommerce_variations(
					$retProd,
					$lastId,
					array(
						'var_max_allowed' 	=> $this->convert_variation_number_to_number( $pms['import_variations'] ),
					)
				);

				// don't add all variation adding texts to the final message!
				$this->opStatusMsg['msg'] = $current_msg;

				$duration = $this->timer_end(); // End Timer
				$this->timer_start(); // Start Timer
				
				// status messages
				$msg[] = $this->opStatusMsgSet(array(
					'status'    => 'valid',
					'msg'       => $setVariationsStatus['msg'],
					'duration'  => $duration,
				));

				// add last import report
				// ...done in amazon helper file
			}

			//---------------------
			//:: set remote images
			if ( $this->is_remote_images ) {
				$setRemoteImgStatus = $this->get_ws_object( $this->cur_provider )->build_remote_images( $lastId );

				$duration = $this->timer_end(); // End Timer
				$this->timer_start(); // Start Timer
									
				// status messages
				$msg[] = $this->opStatusMsgSet(array(
					'status'    => 'valid',
					'msg'       => $setRemoteImgStatus['msg'],
					'duration'  => $duration,
				));
			}

			//---------------------						
			//:: Set the product type
			$this->update_products_type( array(
				'products' => array( $lastId )
			));
			
			$duration = $this->timer_end(); // End Timer
			$durationQueue[] = $duration; // End Timer
			$this->timer_start(); // Start Timer
			
			// status messages
			$msg[] = $this->opStatusMsgSet(array(
				'status'    => 'valid',
				'msg'       => 'update products type',
				'duration'  => $duration,
			));

			//---------------------
			//:: FINAL step

			$duration = $this->timer_end(); // End Timer
			$durationQueue[] = $duration; // End Timer
			$duration = round( array_sum($durationQueue), 4 ); // End Timer
			
			// status messages
			$msg[] = $this->opStatusMsgSet(array(
				'status'    => 'valid',
				'msg'       => 'product adding finished (duration is without time for spin, variations, attributes)',
				'duration'  => $duration,
				'end'       => true,
			));

			// add last import report
			$this->add_last_imports('last_product', array(
				'duration'      => $duration,
			)); // End Timer & Add Report

			$ret = array_replace_recursive( $ret, array(
				'status' 	=> 'valid',
				'msg' 		=> implode('<br />', $msg),
				'insert_id' => $lastId,
			));

			if ( $lastId ) {
				update_post_meta( $lastId, '_amzaff_import_status', $ret );
			}
			return $ret;
		}

		// $retProd must be formated through method 'build_product_data' from amz.helper.class.php
		public function updateWooProduct( $retProd=array(), $pms=array() )
		{
			$pms = array_replace_recursive(array(
				'rules' 		=> array(),

				'post_id' 		=> 0,
				'post_asin'		=> '',

				// array with post_title, post_content, post_excerpt or get_post( POSTID, ARRAY_A )
				'current_post' 	=> false,

				'parent_id' 	=> false, // integer or false,

				// array with post_title, post_content, post_excerpt or get_post( POSTID, ARRAY_A )
				'parent_post' 	=> false,

				// the return of method 'product_find_new_variations'
				'product_vars' 	=> array(),
			), $pms);
			extract( $pms );

			//---------------------
			//:: status messages
			$ret = array(
				'status' 	=> 'notfound',
				'msg' 		=> 'update product - init',
				'rules'		=> array(),
				'updated' 	=> array(),
			);
			$stats = array();

			//---------------------
			//:: empty amazon response?
			if ( empty($retProd) || ! is_array($retProd) ) {
				$ret = array_replace_recursive( $ret, array(
					'status' => 'notfound',
					'msg' 	=> 'update product - empty product array from amazon!',
				));
				return $ret;
			}

			//---------------------
			//:: some inits
			$is_variable = isset($retProd['Variations'], $retProd['Variations']['Item']);

			$is_variation_child = ('' != $retProd['ParentASIN']) && ($retProd['ASIN'] != $retProd['ParentASIN'])
				? true : false;

			$show_short_description = isset($this->amz_settings['show_short_description'])
				? $this->amz_settings['show_short_description'] : 'yes';
			$is_short_desc = isset($rules['short_desc']) && $rules['short_desc'] == true
				&& $show_short_description == 'yes';

			//---------------------
			//:: verify if : amazon missing offerlistingid product!
			if ( $this->product_offerlistingid_missing_delete ) {

				$verifyOfferPms = array(
					'verify_variations' => false,
					'thisProd' => $retProd,
					'post_id' => 0,
				);

				// variation child
				if ( $is_variation_child ) {
				}
				// variable product - parent
				else if ( $is_variable ) {
					$verifyOfferPms = array_replace_recursive( $verifyOfferPms, array(
						'verify_variations' => true,
					));
				}
				// simple product
				else {
				}

				$prod_has_offerlistingid = $this->get_ws_object( $this->cur_provider )->productHasOfferlistingid( $verifyOfferPms );
				//var_dump('<pre>', $prod_has_offerlistingid , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				if ( ! $prod_has_offerlistingid ) {
					$ret = array_replace_recursive( $ret, array(
						'status' => 'notfound',
						'msg' 	=> 'offerListingId is missing, so it is ( removed | moved to trash )!',
					));
					return $ret;
				}
			}

			//---------------------
			//:: configuration & get product meta
			$sync_rules = array_keys( $this->get_product_sync_rules() );
			foreach ( $sync_rules as $sync_rule ) {

				$_sync_rules["$sync_rule"] = false;
				if ( isset($rules["$sync_rule"]) && $rules["$sync_rule"] ) {
					$_sync_rules["$sync_rule"] = true;
				}

				if ( 'short_desc' == $sync_rule && isset($is_short_desc) ) {
					$_sync_rules["short_desc"] = $is_short_desc;
				}
			}

			// short OR full description - at least one of them
			$hasDesc = false;
			if ( $_sync_rules["short_desc"] || $_sync_rules["desc"] ) {
				$hasDesc = true;
			}

			//---------------------
			//:: get post meta
			$post_metas = array();
			$what_metas = array( '_amzASIN', '_amzaff_desc_used', '_sku', '_product_url', 'amzaff_woo_product_tabs', '_sales_rank', '_price' );
			$post_metas = $post_metas + $this->get_product_metas( $post_id, $what_metas, array('remove_prefix' => '') );

			//---------------------
			//:: other inits
			$need_post_maininfo = $_sync_rules['title'] || $hasDesc;

			$is_valid_current_post = is_array($current_post)
				&& isset($current_post['post_title'], $current_post['post_parent'], $current_post['post_content']);

			if ( $need_post_maininfo && ! $is_valid_current_post ) {
				$current_post = get_post( $post_id, ARRAY_A );
			}

			// full & short description need info
			if ( $hasDesc ) {

				if ( empty($post_asin) ) {
					$post_asin = isset($post_metas['_amzASIN'])
						? $post_metas['_amzASIN'] : get_post_meta( $post_id, '_amzASIN', true );
					$post_asin = !empty($post_asin) ? (string) $post_asin : '';
				}

				if ( $parent_id === false ) {
					if ( ! $is_valid_current_post ) {
						$current_post = get_post( $post_id, ARRAY_A );
					}
					$parent_id = isset($current_post['post_parent']) ? $current_post['post_parent'] : 0;
				}

				// is variation child?
				if ( $parent_id ) {

					$is_valid_parent_post = is_array($parent_post)
						&& isset($parent_post['post_title'], $parent_post['post_parent'], $parent_post['post_content']);

					if ( ! $is_valid_parent_post ) {
						$parent_post = get_post( $parent_id, ARRAY_A );
					}

					$retProd = array_merge_recursive($retProd, array(
						'__parent_asin'		=> isset($retProd['ParentASIN']) ? $retProd['ParentASIN'] : '',
						'__parent_content'	=> isset($parent_post['post_content']) ? $parent_post['post_content'] : '',
					));
				}

				$product_desc = $this->product_build_desc($retProd);
				$excerpt = isset($product_desc['short']) ? $product_desc['short'] : '';
				$desc = isset($product_desc['desc']) ? $product_desc['desc'] : '';
			}

			//---------------------
			//:: main update body
			$args_update = array();
			$args_update['ID'] = $post_id;

			//---------------------
			//:: TITLE
			if ( $_sync_rules["title"] ) {

				$args_update['post_title'] = $retProd['Title'];

				$opGetRule = $this->_updateWooProduct_get_rule_stats(
					'title',
					$args_update['post_title'],
					isset($current_post['post_title']) ? $current_post['post_title'] : null,
					array( 'rules' => $_sync_rules )
				);
				$stats = $stats + $opGetRule;
			}

			//---------------------
			//:: SHORT DESCRIPTION
			// short description
			if ( $_sync_rules["short_desc"] ) {

				$args_update['post_excerpt'] = $excerpt;

				$opGetRule = $this->_updateWooProduct_get_rule_stats(
					'short_desc',
					$args_update['post_excerpt'],
					isset($current_post['post_excerpt']) ? $current_post['post_excerpt'] : null,
					array( 'rules' => $_sync_rules )
				);
				$stats = $stats + $opGetRule;
			}

			//---------------------
			//:: FULL DESCRIPTION
			// full description
			if ( $_sync_rules["desc"] ) {

				$args_update['post_content'] = $desc;

				$opGetRule = $this->_updateWooProduct_get_rule_stats(
					'desc',
					$args_update['post_content'],
					isset($current_post['post_content']) ? $current_post['post_content'] : null,
					array( 'rules' => $_sync_rules )
				);
				$stats = $stats + $opGetRule;
			}

			if ( $hasDesc ) {
				$desc_used = array();
				if ( $parent_id ) { // is variation child?
					$desc_used = get_post_meta( $parent_id, '_amzaff_desc_used', true );
				}
				else if ( $is_variable ) { // variable product
					$desc_used = isset($post_metas['_amzaff_desc_used'])
						? $post_metas['_amzaff_desc_used'] : get_post_meta( $post_id, '_amzaff_desc_used', true );
				}
				$desc_used = !empty($desc_used) && is_array($desc_used) && isset($desc_used['child_asin']) ? $desc_used : array();

				//---------------------
				// is variation child?
				if ( $parent_id ) {
					$doit = false;
					if ( empty($desc_used) || empty($desc_used['child_asin']) ) {
						$doit = true;
					}
					else if ( $post_asin == $desc_used['child_asin'] ) {
						$doit = true;
					}

					if ( $doit ) {
						$desc_used = array(
							'child_asin'			=> $post_asin,
							'date_done'				=> date("Y-m-d H:i:s"), // only for debug purpose
						);
   
						if ( !empty($desc_used) && isset($desc_used['child_asin']) ) {
							update_post_meta( $parent_id, '_amzaff_desc_used', $desc_used );
						}

						//---------------------
						// update parent variation
						$parent_update = array();
						$parent_update['ID'] = $parent_id;

						if ( $_sync_rules["short_desc"] ) {
							$parent_update['post_excerpt'] = $excerpt;
						}
						if ( $_sync_rules["desc"] ) {
							$parent_update['post_content'] = $desc;
						}

						if ( isset($parent_update['post_content']) || isset($parent_update['post_excerpt']) ) {
							wp_update_post( $parent_update );
						}
					}
				}
				//---------------------
				// parent variation OR non-variable product 
				else if ( $is_variable ) {
					$variations = isset($retProd['Variations']['Item']) ? $retProd['Variations']['Item'] : array();
					$found = false;
					foreach ( $variations as $variation ) {
						$asin = isset($variation['ASIN']) ? $variation['ASIN'] : '';
						//var_dump('<pre>',$asin, $desc_used['child_asin'],'</pre>');  
						if ( isset($desc_used['child_asin']) && ( $asin == $desc_used['child_asin'] ) ) {
							$found = true;
						}
					}

					// variation child not found anymore => next sync will use another variation child to update desc
					if ( ! $found ) {
						$desc_used = array(
							'child_asin'			=> '',
							'date_done'				=> date("Y-m-d H:i:s"), // only for debug purpose
						);
					}

					$__post_content = isset($args_update['post_excerpt']) ? $args_update['post_excerpt'] : '';
					$__post_content = trim( $__post_content );

					if ( $__post_content == '' || $found ) { // is empty => don't try to update
						if ( isset($args_update['post_excerpt']) ) {
							unset( $args_update['post_excerpt'] );
						}
					}

					$__post_content = isset($args_update['post_content']) ? $args_update['post_content'] : '';
					$__post_content = $this->product_clean_desc( $__post_content );

					if ( $__post_content == '' || $found ) { // is empty => don't try to update
						if ( isset($args_update['post_content']) ) {
							unset( $args_update['post_content'] );
						}
						if ( !empty($desc_used) && isset($desc_used['child_asin']) ) {
							update_post_meta( $post_id, '_amzaff_desc_used', $desc_used );
						}
					}
				}
			}

			//---------------------
			//:: UPDATE POST - posts table
			//var_dump('<pre>', $args_update, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			// update the post if needed
			if ( count($args_update) > 1 ) { // because ID is allways the same!
				wp_update_post( $args_update );
			}

			//---------------------
			//:: SKU - postmeta table
			// than update the metapost
			if ( $_sync_rules["sku"] ) {

				$old_meta = isset($post_metas['_sku'])
					? $post_metas['_sku'] : get_post_meta( $post_id, '_sku', true );

				update_post_meta($post_id, '_sku', $retProd['SKU']);

				$opGetRule = $this->_updateWooProduct_get_rule_stats(
					'sku',
					$retProd['SKU'],
					$old_meta,
					array( 'rules' => $_sync_rules )
				);
				$stats = $stats + $opGetRule;
			}

			//---------------------
			//:: PRODUCT URL - postmeta table
			if ( $_sync_rules["url"] ) {

				$old_meta = isset($post_metas['_product_url'])
					? $post_metas['_product_url'] : get_post_meta( $post_id, '_product_url', true );

				$new_url = home_url('/?redirectAmzASIN=' . $retProd['ASIN'] );

				update_post_meta($post_id, '_product_url', $new_url);

				$opGetRule = $this->_updateWooProduct_get_rule_stats(
					'url',
					$new_url,
					$old_meta,
					array( 'rules' => $_sync_rules )
				);
				$stats = $stats + $opGetRule;
			}

			//---------------------
			//:: REVIEWS - postmeta table
			if ( $_sync_rules["reviews"] ) {
				if ( isset($retProd['CustomerReviewsURL']) && $retProd['CustomerReviewsURL'] != "" ) {

					$old_meta = isset($post_metas['amzaff_woo_product_tabs'])
						? $post_metas['amzaff_woo_product_tabs'] : get_post_meta( $post_id, 'amzaff_woo_product_tabs', true );

					$tab_data = array();
					$tab_data[] = array(
						'id' => 'amzAff-customer-review',
						'content' => '<iframe src="' . $retProd['CustomerReviewsURL'] . '" width="100%" height="450" frameborder="0"></iframe>'
					);
					//var_dump( $retProd, $tab_data );

					update_post_meta($post_id, 'amzaff_woo_product_tabs', $tab_data);

					$opGetRule = $this->_updateWooProduct_get_rule_stats(
						'reviews',
						maybe_serialize( $tab_data ),
						maybe_serialize( $old_meta ),
						array( 'rules' => $_sync_rules )
					);
					$stats = $stats + $opGetRule;
				}
			}

			//---------------------
			//:: SALES RANK - postmeta table
			if ( $_sync_rules["sales_rank"] ) {

				$old_meta = isset($post_metas['_sales_rank'])
					? $post_metas['_sales_rank'] : get_post_meta( $post_id, '_sales_rank', true );

				update_post_meta($post_id, '_sales_rank', $retProd['SalesRank']);

				$opGetRule = $this->_updateWooProduct_get_rule_stats(
					'sales_rank',
					$retProd['SalesRank'],
					$old_meta,
					array( 'rules' => $_sync_rules )
				);
				$stats = $stats + $opGetRule;
			}

			//---------------------
			//:: PRICE - postmeta table
			if ( $_sync_rules["price"] ) {

				$old_meta = isset($post_metas['_price'])
					? $post_metas['_price'] : get_post_meta( $post_id, '_price', true );

				// set the product price
				$product_price = $this->get_ws_object( $this->cur_provider )->get_product_price(
					$retProd,
					$post_id,
					array( 'do_update' => true )
				);

				$opGetRule = $this->_updateWooProduct_get_rule_stats(
					'price',
					$product_price['_price'],
					$old_meta,
					array( 'rules' => $_sync_rules )
				);
				$stats = $stats + $opGetRule;
			}

			//---------------------
			//:: NEW VARIATIONS (VARIABLE PARENT PRODUCT)
			// variable products only: only parent
			// also we update the product type here & remove woocommerce transients
			$is_ptupdated = false;
			if ( $_sync_rules["new_variations"] && $is_variable ) {

				if ( ! is_array($product_vars) || ! isset($product_vars['status']) ) {
					$product_vars = $this->product_find_new_variations( $retProd, array(
						'only_new' 		=> false,
						'product_id' 	=> $post_id,
					));
				}
				//var_dump('<pre>', $product_vars , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$product_addvars = $this->product_add_new_variations( $post_id, $product_vars );
				$is_ptupdated = true;

				$opGetRule = $this->_updateWooProduct_get_rule_stats(
					'new_variations',
					$product_addvars['new_added'],
					null,
					array( 'rules' => $_sync_rules, 'msg' => $product_addvars['msg'] )
				);
				$stats = $stats + $opGetRule;
			}

			//---------------------
			// variable products only: parent or child
			// product type not updated or woocommerce transients not removed above
			if ( ! $is_ptupdated ) {

				// parent variation
				$_idprod = 0;
				if ( $is_variable ) {
					$_idprod = $post_id;
				}

				// variation child
				if ( ! $_idprod && $is_variation_child ) {
					if ( ! isset($parent_id) || $parent_id === false ) {
						$current_post = get_post( $post_id, ARRAY_A );
						$parent_id = isset($current_post['post_parent']) ? $current_post['post_parent'] : 0;
					}
					$_idprod = $parent_id;
				}

				// parent variation | variation child
				if ( $_idprod ) {
					delete_transient( "wc_product_children_$_idprod" );
					delete_transient( "wc_var_prices_$_idprod" );

					// Set the product type
					$this->update_products_type( array(
						'products' => array( $_idprod )
					));
				}
			}

			// any stats changed?
			$status = 'notupdated';
			$updated = array();
			foreach ( $stats as $rule => $ruleinfo ) {
				if ( 'yes' == $ruleinfo['status'] ) {
					$status = 'updated';
					//break;
					$updated[] = $rule;
				}
			}

			$ret = array_replace_recursive( $ret, array(
				'status' 	=> $status,
				'msg' 		=> 'update product - parsing rules finished',
				'rules' 	=> $stats,
				'updated' 	=> $updated,
			));
			return $ret;
		}

		public function _updateWooProduct_get_rule_stats( $rule, $new, $old=null, $pms=array() ) {
			$pms = array_replace_recursive(array(
				'rules' 			=> array(),
				'rules_toverify' 	=> '',
			), $pms);
			extract( $pms );

			//$rules_toverify = explode(',', $rules_toverify);
			//$rules_toverify = array_map('trim', $rules_toverify);
			//$rules_toverify = array_unique( array_filter( $rules_toverify ) );

			$stats = array();
			$stats["$rule"] = array(
				'code' 		=> '',

				// def = no verification made | no = same info, so no update | yes = info was updated with new one
				// if ( rule is new_variations) => (int) number of new variations added
				'status' 	=> 'def',
			);

			if ( 'new_variations' == $rule ) {
				$stats["$rule"]['status'] = $new ? 'yes' : 'no';
				$stats["$rule"]['new_added'] = $new;
				if ( isset($msg) ) {
					$stats["$rule"]['msg'] = $msg;
				}
				return $stats;
			}

			$code_amz = md5( $new );
			$stats["$rule"]['code'] = $code_amz;

			//if ( in_array( $rule, $rules_toverify ) && ! is_null($old) ) {
				$code_old = md5( $old );
				$stats["$rule"]['status'] = ( $code_amz == $code_old ? 'no' : 'yes' );
			//}
			return $stats;
		}

		// $product_vars = the return of method 'product_find_new_variations'
		public function product_add_new_variations( $product_id, $product_vars=array() ) {
			$ret = array(
				'status' 	=> 'invalid',
				'msg' 		=> '',
				'new_added' => 0,
			);
			$msg = array();

			$retProd_new = $product_vars['retProd_new'];

			if ( ! $product_vars['total_new'] ) {
				$ret = array_replace_recursive( $ret, array(
					'msg' 	=> $product_vars['msg'],
				));
			}

			$msg[] = $product_vars['msg'];

			$setVariationsStatus = $this->get_ws_object( $this->cur_provider )->set_woocommerce_variations(
				$retProd_new,
				$product_id,
				array(
					'var_exist' 	=> count( $product_vars['variations_exist'] ),
					'var_new' 		=> count( $product_vars['variations_new'] ),
				)
			);
			$msg[] = $setVariationsStatus['msg'];
			//var_dump('<pre>', $setVariationsStatus , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			delete_transient( "wc_product_children_$product_id" );
			delete_transient( "wc_var_prices_$product_id" );

			// set remote images
			if ( $this->is_remote_images ) {
				$setRemoteImgStatus = $this->get_ws_object( $this->cur_provider )->build_remote_images( $product_id );
				$msg[] = $setRemoteImgStatus['msg'];
			}

			// Set the product type
			$this->update_products_type( array(
				'products' => array( $product_id )
			));

			$msg = implode( '<br />', $msg );
			$ret = array_replace_recursive( $ret, array(
				'status' 	=> $setVariationsStatus['status'],
				'new_added' => $setVariationsStatus['nb_parsed'],
				'msg' 		=> $msg,
			));
			return $ret;
		}

		// $retProd must be formated through method 'build_product_data' from amz.helper.class.php
		public function product_find_new_variations( $retProd=array(), $pms=array() ) {
			global $wpdb;

			$pms = array_replace_recursive(array(
				'DEBUG' 		=> false,

				// optimization: find only new variations
				'only_new' 		=> true,

				'product_id' 	=> 0,
			), $pms);
			extract( $pms );


			//:: init
			$product_asin = isset($retProd['ASIN']) ? $retProd['ASIN'] : '';

			$is_variable = isset($retProd['Variations'], $retProd['Variations']['Item']);

			$is_variation_child = ('' != $retProd['ParentASIN']) && ($retProd['ASIN'] != $retProd['ParentASIN'])
				? true : false;

			$product_type = $is_variable ? 'variable' : 'simple';
			if ( $is_variation_child ) {
				$product_type = 'variation';
			}

			$asins = array();
			$variations = array();

			$variations_new = array();
			$variations_new_asin = array();
			$variations_exist = array();
			$variations_exist_asin = array();
			$variations_notfound = array();
			$variations_notfound_asin = array();

			$retProd_new = $retProd;
			if ( isset($retProd_new['Variations'], $retProd_new['Variations']['Item']) ) {
				//$retProd_new['Variations']['Item'] = array();
				unset( $retProd_new['Variations']['Item'] );
			}
			if ( isset($retProd_new['Variations'], $retProd_new['Variations']['TotalVariations']) ) {
				//$retProd_new['Variations']['TotalVariations'] = 0;
				unset( $retProd_new['Variations']['TotalVariations'] );
			}


			//:: return init
			$ret = array(
				'status' 					=> 'invalid',
				'msg' 						=> '',
				'current_post' 				=> false,

				'product_type' 				=> $product_type,

				// new variations from amazon
				'variations_new_asin' 		=> $variations_new_asin,
				'variations_new' 			=> $variations_new,
				'retProd_new' 				=> $retProd_new,
				'total_new' 				=> 0,

				// variations from amazon which already exists in the table
				'variations_exist_asin'		=> $variations_exist_asin,
				'variations_exist' 			=> $variations_exist,

				// variations which exists in table but aren't received from amazon in response
				'variations_notfound_asin'	=> $variations_notfound_asin,
				'variations_notfound'		=> $variations_notfound,
			);


			//:: find all variations childs asins from amazon response
			if ( $is_variable ) {
				//$retProd['Variations']['TotalVariations']
				$total = $this->get_amazon_variations_nb( $retProd['Variations']['Item'] );
				
				if ($total <= 1 || isset($retProd['Variations']['Item']['ASIN'])) { // --fix 2015.03.19
					$variations[] = $retProd['Variations']['Item'];
				} else {
					$variations = (array) $retProd['Variations']['Item'];
				}
 
				// Loop through the variation
				foreach ($variations as $variation_item) {
					if ( isset($variation_item['ASIN']) && ! empty($variation_item['ASIN']) ) {
						$asins[] = $variation_item['ASIN'];
					}
				} // end foreach
			}

			$asins = array_unique( array_filter( $asins ) );


			//:: validation
			if ( empty($asins) ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> 'no variations found in amazon response!',
				));
				if ( $DEBUG ) {
					unset( $ret['variations_new'], $ret['retProd_new'], $ret['variations_exist'], $ret['variations_notfound'] );
				}
				return $ret;
			}


			//:: find all variation childs which already exists in database
			$tposts = $wpdb->posts;
			$tpostmeta = $wpdb->postmeta;

			$asins_ = implode(',', array_map(array($this, 'prepareForInList'), $asins));
			//var_dump('<pre>', $asins_ , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$get_fields = 'pm.meta_value, p.ID, p.post_parent, p.post_title, p.post_content, p.post_excerpt, p.post_type';
			// and p.post_status != ''
			if ( $only_new ) {
				$sql_x = "select $get_fields from $tposts as p left join $tpostmeta as pm on p.ID = pm.post_id and pm.meta_key='_amzASIN' where 1=1 and p.post_type IN ( 'product_variation', 'product' ) and ! isnull(pm.meta_value) and pm.meta_value IN ($asins_) order by p.ID asc;";
			}
			else {
				$sql_x = "select $get_fields from $tposts as p left join $tpostmeta as pm on p.ID = pm.post_id and pm.meta_key='_amzASIN' where 1=1 and p.post_type IN ( 'product_variation', 'product' ) and ! isnull(pm.meta_value) and ( p.ID = '$product_id' or p.post_parent = '$product_id' ) order by p.ID asc;";
			}
			$res_x = $wpdb->get_results( $sql_x, OBJECT_K );
			$asins_found = $res_x;
			//var_dump('<pre>', $asins_found , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			// parent variable product
			if ( isset($asins_found["$product_asin"]) && is_object($asins_found["$product_asin"]) ) {
				if ( 'product' == $asins_found["$product_asin"]->post_type ) {
					$ret['current_post'] = $this->_product_find_new_variations_getprodinfo( $asins_found["$product_asin"] );
				}
			}


			//:: new variations from amazon and variations from amazon which already exists in the table
			// Loop through the variation
			foreach ($variations as $variation_item) {
				$variation_asin = '';
				if ( isset($variation_item['ASIN']) && ! empty($variation_item['ASIN']) ) {
					$variation_asin = $variation_item['ASIN'];
				}
				if ( empty($variation_asin) ) {
					continue 1;
				}

				// variation already exists in database - UPDATE
				if ( isset($asins_found["$variation_asin"]) ) {
					$variations_exist["$variation_asin"] = array(
						'variation_item' 	=> $variation_item,
						'current_post' 		=> $this->_product_find_new_variations_getprodinfo( $asins_found["$variation_asin"] ),
					);
					$variations_exist_asin[] = $variation_asin;
				}
				// new variation - INSERT
				else {
					$variations_new[] = $variation_item;
					$variations_new_asin[] = $variation_asin;
				}
			}
			// end Loop through the variation
			//var_dump('<pre>', 'variations NEW', $variations_new_asin, 'variations EXIST', $variations_exist_asin , '</pre>');
			//var_dump('<pre>', 'variations NEW', $variations_new, 'variations EXIST', $variations_exist , '</pre>');
			//echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$total_new = count( $variations_new );
			if ( $total_new ) {
				$retProd_new['Variations']['Item'] = ( $total_new > 1 ? $variations_new : $variations_new[0] );
				$retProd_new['Variations']['TotalVariations'] = $total_new;
			}


			//:: variations from database, which don't exist on amazon anymore - DELETE
			if ( ! $only_new ) {
				foreach ($asins_found as $prodAsin => $prodInfo) {
					if ( 
						'product' == $prodInfo->post_type
						|| in_array($prodAsin, $variations_exist_asin)
						|| in_array($prodAsin, $variations_new_asin)
					) {
						continue 1;
					}

					$variations_notfound["$prodAsin"] = array(
						'variation_item' 	=> array(),
						'current_post' 		=> $this->_product_find_new_variations_getprodinfo( $prodInfo ),
					);
					$variations_notfound_asin[] = $prodAsin;
				}
			}


			//:: return
			$msg = array();
			if ( $only_new ) {
				$msg[] = sprintf( 'we\'ve found %s new variations, %s variations already imported', $total_new, count($variations_exist) );
			}
			else {
				$msg[] = sprintf( 'we\'ve found %s new variations, %s variations already imported, %s variations which don\'t exit on amazon anymore', $total_new, count($variations_exist), count($variations_notfound) );
			}
			$msg = implode( '<br />', $msg );

			$ret = array_replace_recursive($ret, array(
				'status' 					=> 'valid',
				'msg' 						=> $msg,

				'variations_new_asin' 		=> $variations_new_asin,
				'variations_new' 			=> $variations_new,
				'retProd_new' 				=> $retProd_new,
				'total_new' 				=> $total_new,

				'variations_exist_asin'		=> $variations_exist_asin,
				'variations_exist' 			=> $variations_exist,

				'variations_notfound_asin'	=> $variations_notfound_asin,
				'variations_notfound' 		=> $variations_notfound,
			));
			if ( $DEBUG ) {
				unset( $ret['variations_new'], $ret['retProd_new'], $ret['variations_exist'], $ret['variations_notfound'] );
			}
			return $ret;
		}

		public function _product_find_new_variations_getprodinfo( $prodInfo ) {
			if ( empty($prodInfo) ) {
				return false;
			}
			return array(
				'post_parent' 		=> $prodInfo->post_parent,
				'post_id'			=> $prodInfo->ID,
				'post_title'		=> $prodInfo->post_title,
				'post_excerpt'		=> $prodInfo->post_excerpt,
				'post_content'		=> $prodInfo->post_content,
			);
		}


		/**
			* HTML escape given string
			*
			* @param string $text
			* @return string
			*/
		public function escape($text)
			{
					$text = (string) $text;
					if ('' === $text) return '';

					$result = @htmlspecialchars($text, ENT_COMPAT, 'UTF-8');
					if (empty($result)) {
							$result = @htmlspecialchars(utf8_encode($text), ENT_COMPAT, 'UTF-8');
					}

					return $result;
			}
		
		public function getBrowseNodes( $nodeid=0, $provider='amazon' ) {
			if( !is_numeric($nodeid) ){
				return array(
					'status'    => 'invalid',
					'msg'       => 'The $nodeid is not numeric: ' . $nodeid
				);
			}

			// try to get the option with this browsenode
			$nodes = get_option( $this->alias . '_node_children_' . $nodeid, false );

			// unable to find the node into cache, get live data
			if( !isset($nodes) || $nodes == false || count($nodes) == 0 ){
				$nodes = $this->amzHelper->browseNodeLookup( $nodeid );
 
				if( isset($nodes['BrowseNodes']) && count($nodes['BrowseNodes']) > 0 ){
					if( isset($nodes['BrowseNodes']['BrowseNode']['Children']['BrowseNode']) && count($nodes['BrowseNodes']['BrowseNode']['Children']['BrowseNode']) > 0 ){
	
						if( !isset($nodes['BrowseNodes']['BrowseNode']['Children']['BrowseNode'][1]['BrowseNodeId']) ){
							$nodes['BrowseNodes']['BrowseNode']['Children']['BrowseNode'] = array(
								$nodes['BrowseNodes']['BrowseNode']['Children']['BrowseNode']
							);
						}
						
						if( count($nodes['BrowseNodes']['BrowseNode']['Children']['BrowseNode']) > 0 ){
							$nodes = $nodes['BrowseNodes']['BrowseNode']['Children']['BrowseNode'];
							
							// store the cache into DB
							update_option( $this->alias . '_node_children_' . $nodeid, $nodes );
						}
					}
				}
				else {
					$nodes = false;
				}
			}

			return $nodes;
		}

		public function multi_implode($array, $glue) 
		{
			$ret = '';

			foreach ($array as $item) {
					if (is_array($item)) {
							$ret .= $this->multi_implode($item, $glue) . $glue;
					} else {
							$ret .= $item . $glue;
					}
			}

			$ret = substr($ret, 0, 0-strlen($glue));

			return $ret;
		}

		public function download_asset_lightbox( $prod_id=0, $from='default', $return='die' )
		{
						$requestData = array(
								'prod_id'   => isset($_REQUEST['prod_id']) ? $_REQUEST['prod_id'] : $prod_id,
								'from'      => isset($_REQUEST['from']) ? $_REQUEST['from'] : $from,
						);
						extract($requestData);

			$assets = $this->get_ws_object( $this->cur_provider )->get_asset_by_postid( 'all', $prod_id, true );
			if ( count($assets) <= 0 ) {
				if( $return == 'die' ){
					die( json_encode(array(
						'status' => 'invalid',
						'html'  => __("this product has no assets to be dowloaded!", $this->localizationName )
					)));
				} else {
					return __("this product has no assets to be dowloaded!", $this->localizationName );
				}
			}
						
						$css = array();
						$css['container'] = ( $from == 'default' ? 'WooZone-asset-download-lightbox-properties' : 'WooZone-asset-download-IM' );
			
			$html = array();
			$html[] = '<div class="WooZone-asset-download-lightbox '.$css['container'].'">';
			$html[] =   '<div class="WooZone-donwload-in-progress-box">';
			$html[] =       '<h1>' . __('Images download in progress ... ', $this->localizationName ) . '<a href="#" class="WooZone-button red" id="WooZone-close-btn">' . __('CLOSE', $this->localizationName ) . '</a></h1>';
			$html[] =       '<p class="WooZone-message WooZone-info WooZone-donwload-notice">';
			$html[] =       __('Please be patient while the images are downloaded. 
			This can take a while if your server is slow (inexpensive hosting) or if you have many images. 
			Do not navigate away from this page until this script is done. 
			You will be notified via this box when the regenerating is completed.', $this->localizationName );
			$html[] =       '</p>';
			
			$html[] =       '<div class="WooZone-process-progress-bar">';
			$html[] =           '<div class="WooZone-process-progress-marker"><span>0%</span></div>';
			$html[] =       '</div>';
			
			$html[] =       '<div class="WooZone-images-tail">';
			$html[] =           '<ul>';
			
			if( count($assets) > 0 ){
				foreach ($assets as $asset) {
					 
					$html[] =       '<li data-id="' . ( $asset->id ) . '">';
					$html[] =           '<img src="' . ( $asset->thumb ) . '">';
					$html[] =       '</li>';    
				}
			} 
			
			$html[] =           '</ul>';
			$html[] =       '</div>';
			$html[] =       '
			<script>
				jQuery(".WooZone-images-tail ul").each(function(){
					
					var that = jQuery(this),
						lis = that.find("li"),
						size = lis.size();
					
					that.width( size *  86 );
				});
				jQuery(".WooZone-images-tail ul").scrollLeft(0);
			</script>
			';
			
			$html[] =       '<h2 class="WooZone-process-headline">' . __('Debugging Information:', $this->localizationName ) . '</h2>';
			$html[] =       '<table class="WooZone-table WooZone-debug-info">';
						if ( $from == 'default' ) {
			$html[] =           '<tr>';
			$html[] =               '<td width="150">' . __('Total Images:', $this->localizationName ) . '</td>';
			$html[] =               '<td>' . ( count($assets) ) . '</td>';
			$html[] =           '</tr>';
			$html[] =           '<tr>';
			$html[] =               '<td>' . __('Images Downloaded:', $this->localizationName ) . '</td>';
			$html[] =               '<td class="WooZone-value-downloaded">0</td>';
			$html[] =           '</tr>';
			$html[] =           '<tr>';
			$html[] =               '<td>' . __('Downloaded Failures:', $this->localizationName ) . '</td>';
			$html[] =               '<td class="WooZone-value-failures">0</td>';
			$html[] =           '</tr>';
						} else {
						$html[] =           '<tr>';
						$html[] =               '<td>' . __('Total Images:', $this->localizationName ) . '<span>' . ( count($assets) ) . '</span></td>';
						$html[] =               '<td>' . __('Images Downloaded:', $this->localizationName ) . '<span class="WooZone-value-downloaded">0</span></td>';
						$html[] =               '<td>' . __('Downloaded Failures:', $this->localizationName ) . '<span class="WooZone-value-failures">0</span></td>';
						$html[] =           '</tr>';
						}
			$html[] =       '</table>';
			
			$html[] =       '<div class="WooZone-downoad-log">';
			$html[] =           '<ol>';
			//$html[] =                 '<li>"One-size-fits-most-Tube-DressCoverup-Field-Of-Flowers-White-0" (ID 214) failed to resize. The error message was: The originally uploaded image file cannot be found at <code>/home/aateam30/public_html/cc/wp-plugins/woo-Amazon-payments/wp-content/uploads/2014/03/One-size-fits-most-Tube-DressCoverup-Field-Of-Flowers-White-0.jpg</code></li>';
			$html[] =           '</ol>';
			$html[] =       '</div>';
			$html[] =   '</div>';
			$html[] = '</div>';
			
			if( $return == 'die' ){
				die( json_encode(array(
					'status' => 'valid',
					'html'  => implode("\n", $html)
				)));
			}
			
			return implode("\n", $html);
		}
		
		
		/**
		 * Delete product assets
		 */
		public function product_assets_verify() {
			if ( current_user_can( 'delete_posts' ) )
				add_action( 'delete_post', array($this, 'product_assets_delete'), 10 );
		}
		
		public function product_assets_delete($prod_id) {
			// verify we are in woocommerce product
			if ( is_object($prod_id) ) {
				$product = $prod_id;
			} else if( function_exists('wc_get_product') ){
				$product = wc_get_product( $prod_id );
			} else if( function_exists('get_product') ){
				$product = get_product( $prod_id );
			}

			if ( is_object($product) ) {
				$prod_id = 0;
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
				if ( $prod_id ) {
					require( $this->cfg['paths']['plugin_dir_path'] . '/modules/assets_download/init.php' );
					$WooZoneAssetDownloadCron = new WooZoneAssetDownload();
					
					return $WooZoneAssetDownloadCron->product_assets_delete( $prod_id );
				}
			}
		}


		/**
		 * Usefull
		 */
		
		//format right (for db insertion) php range function!
		public function doRange( $arr ) {
			$newarr = array();
			if ( is_array($arr) && count($arr)>0 ) {
				foreach ($arr as $k => $v) {
					$newarr[ $v ] = $v;
				}
			}
			return $newarr;
		}
		
		//verify if file exists!
		public function verifyFileExists($file, $type='file') {
			clearstatcache();
			if ($type=='file') {
				if (!file_exists($file) || !is_file($file) || !is_readable($file)) {
					return false;
				}
				return true;
			} else if ($type=='folder') {
				if (!is_dir($file) || !is_readable($file)) {
					return false;
				}
				return true;
			}
			// invalid type
			return 0;
		}
		
		// Return current Unix timestamp with microseconds
		// Simple function to replicate PHP 5 behaviour
		public function microtime_float()
		{
			list($usec, $sec) = explode(" ", microtime());
			return ((float)$usec + (float)$sec);
		}

		public function formatBytes($bytes, $precision = 2) {
			$units = array('B', 'KB', 'MB', 'GB', 'TB');

			$bytes = max($bytes, 0);
			$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
			$pow = min($pow, count($units) - 1);

			// Uncomment one of the following alternatives
			// $bytes /= pow(1024, $pow);
			$bytes /= (1 << (10 * $pow));

			return round($bytes, $precision) . ' ' . $units[$pow];
		}
		
		public function prepareForInList($v) {
			return "'".$v."'";
		}
				
		public function prepareForPairView($v, $k) {
			return sprintf("(%s, %s)", $k, $v);
		}

		public function db_custom_insert($table, $fields, $ignore=false, $wp_way=false) {
			if ( $wp_way && !$ignore ) {
				$this->db->insert( 
					$table, 
					$fields['values'], 
					$fields['format']
				);
			} else {
			
				$formatVals = implode(', ', array_map(array($this, 'prepareForInList'), $fields['format']));
				$theVals = array();
				foreach ( $fields['values'] as $k => $v ) $theVals[] = $k;

				$q = "INSERT " . ($ignore ? "IGNORE" : "") . " INTO $table (" . implode(', ', $theVals) . ") VALUES (" . $formatVals . ");";
				foreach ($fields['values'] as $kk => $vv)
					$fields['values']["$kk"] = esc_sql($vv);
	
				$q = vsprintf($q, $fields['values']);
				$r = $this->db->query( $q );
			}
			return $this->db->insert_id;
		}
		
		public function verify_product_is_amazon_valid( $post_id ) {
			if ( empty($post_id) ) return false;

			$is_product_amazon = $this->verify_product_is_amazon( $post_id );
			return $is_product_amazon; 
		}

		public function verify_product_isvariation($prod_id) {
			// verify we are in woocommerce product
			if ( is_object($prod_id) ) {
				$product = $prod_id;
			} else if( function_exists('wc_get_product') ){
				//$product = wc_get_product( $prod_id );
				$product = new WC_Product_Variable( $prod_id ); // WC_Product
			} else if( function_exists('get_product') ){
				//$product = get_product( $prod_id );
				$product = new WC_Product_Variable( $prod_id ); // WC_Product
			}

			if ( is_object($product) ) {
				$prod_id = 0;
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
				if ( $prod_id ) {
					if ( $product->has_child() ) { // is product variation parent!
						return true;
					}
				}
			}
			return false;
		}
		
		public function get_product_variations($prod_id) {
			// verify we are in woocommerce product
			if ( is_object($prod_id) ) {
				$product = $prod_id;
			} else if( function_exists('wc_get_product') ){
				//$product = wc_get_product( $prod_id );
				$product = new WC_Product_Variable( $prod_id ); // WC_Product
			} else if( function_exists('get_product') ){
				//$product = get_product( $prod_id );
				$product = new WC_Product_Variable( $prod_id ); // WC_Product
			}

			if ( is_object($product) ) {
				$prod_id = 0;
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}
				if ( $prod_id ) {
					return $product->get_children();
				}
			}
			return array();
		}
		
		/**
		 * spin post/product content
		 */
		public function spin_content( $req=array() ) {

			$request = array(
				'prodID'            => isset($req['prodID']) ? $req['prodID'] : 0,
				'replacements'      => isset($req['replacements']) ? $req['replacements'] : 10
			);

			$ret = array(
				'status' => 'valid',
				'data' => array()
			);

			// spin content action
			require_once( $this->cfg['paths']["scripts_dir_path"] . '/php-query/phpQuery.php' );
			require_once( $this->cfg['paths']["scripts_dir_path"] . '/spin-content/spin.class.php' );
   
			if ( 1 ) {

				$lang = isset($this->amz_settings['main_aff_id']) ? $this->amz_settings['main_aff_id'] : 'en';
				$lang = strtolower( $lang );
			
				$spinner = WooZoneSpinner::getInstance();
				$spinner->set_syn_language( $lang );
				$spinner->set_replacements_number( $request['replacements'] );

				// first check if you have the original content saved into DB
				$post_content = get_post_meta( $request['prodID'], 'WooZone_old_content', true );

				// if not, retrive from DB
				if( $post_content == false ){
					$live_post = get_post( $request['prodID'], ARRAY_A );
					$post_content = $live_post['post_content'];
				}

				$spinner->load_content( $post_content );
				$spin_return = $spinner->spin_content();
				$reorder_content = $spinner->reorder_synonyms();
				$fresh_content = $spinner->get_fresh_content( $reorder_content );
	
				update_post_meta( $request['prodID'], 'WooZone_spinned_content', $spin_return['spinned_content'] );
				update_post_meta( $request['prodID'], 'WooZone_reorder_content', $reorder_content );
				update_post_meta( $request['prodID'], 'WooZone_old_content', $spin_return['old_content'] );
				update_post_meta( $request['prodID'], 'WooZone_finded_replacements', $spin_return['finded_replacements'] );

				// Update the post into the database
				wp_update_post( array(
							'ID'           => $request['prodID'],
							'post_content' => $fresh_content
				) );
	
				$ret = array(
					'status' => 'valid',
					'data' => array(
						'reorder_content' => $reorder_content
					)
				);
			}
			return $ret;
		}


		/**
		 * setup module messages
		 */
		public function print_module_error( $module=array(), $error_number, $title="" )
		{
			$html = array();
			if( count($module) == 0 ) return true;
	
			$html[] = '<div class="WooZone-grid_4 WooZone-error-using-module">';
			$html[] =   '<div class="WooZone-panel">';
			$html[] =       '<div class="WooZone-panel-header">';
			$html[] =           '<span class="WooZone-panel-title">';
			$html[] =               __( $title, $this->localizationName );
			$html[] =           '</span>';
			$html[] =       '</div>';
			$html[] =       '<div class="WooZone-panel-content">';
			
			$error_msg = isset($module[$module['alias']]['errors'][$error_number]) ? $module[$module['alias']]['errors'][$error_number] : '';
			
			$html[] =           '<div class="WooZone-error-details">' . ( $error_msg ) . '</div>';
			$html[] =       '</div>';
			$html[] =   '</div>';
			$html[] = '</div>';
			
			return implode("\n", $html);
		}
		
		public function convert_to_button( $button_params=array() )
		{
			$button = array();
			$button[] = '<a';
			if(isset($button_params['url'])) 
				$button[] = ' href="' . ( $button_params['url'] ) . '"';
			
			if(isset($button_params['target'])) 
				$button[] = ' target="' . ( $button_params['target'] ) . '"';
			
			$button[] = ' class="WooZone-button';
			
			if(isset($button_params['color'])) 
				$button[] = ' ' . ( $button_params['color'] ) . '';
				
			$button[] = '"';
			$button[] = '>';
			
			$button[] =  $button_params['title'];
		
			$button[] = '</a>';
			
			return implode("", $button);
		}

		public function load_terms($taxonomy){
			global $wpdb;
			
			$query = "SELECT DISTINCT t.name FROM {$wpdb->terms} AS t INNER JOIN {$wpdb->term_taxonomy} as tt ON tt.term_id = t.term_id WHERE 1=1 AND tt.taxonomy = '".esc_sql($taxonomy)."'";
				$result =  $wpdb->get_results($query , OBJECT);
				return $result;                 
		}
		
		public function get_current_page_url() {   
			$url = (!empty($_SERVER['HTTPS']))
				?
				"https://" . $this->get_host() . $_SERVER['REQUEST_URI']
				:
				"http://" . $this->get_host() . $_SERVER['REQUEST_URI']
			;
			return $url;
		}

		// verbose translation from Symfony
		public function get_host() {
			$possibleHostSources = array('HTTP_X_FORWARDED_HOST', 'HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR');
			$sourceTransformations = array(
				// since PHP 4 >= 4.0.1, PHP 5, PHP 7
				//"HTTP_X_FORWARDED_HOST" => create_function('$value', '$elements = explode(",", $value); return trim(end($elements));'),
				"HTTP_X_FORWARDED_HOST" => function ($value) {
					$elements = explode(",", $value); return trim(end($elements));
				},

				// since PHP 5.3.0 (anonymous function)
				//"HTTP_X_FORWARDED_HOST" => function($value) {
				//    $elements = explode(',', $value);
				//    return trim(end($elements));
				//},
			);
			$host = '';
			foreach ($possibleHostSources as $source)
			{
				if (!empty($host)) break;
				if (empty($_SERVER[$source])) continue;
				$host = $_SERVER[$source];
				if (array_key_exists($source, $sourceTransformations))
				{
					$host = $sourceTransformations[$source]($host);
				} 
			}
		
			// Remove port number from host
			$host = preg_replace('/:\d+$/', '', $host);
		
			return trim($host);
		}

		public function get_country_perip_external( $return_field='country' ) {
			//if ( isset($_COOKIE["WooZone_country"]) && !empty($_COOKIE["WooZone_country"]) ) {
			//  return unserialize($_COOKIE["WooZone_country"]);
			//}

			//if ( isset($_SESSION['WooZone_country']) ) unset($_SESSION["WooZone_country"]); // DEBUG
			if ( $this->ss['cache_client_country'] ) {
				if ( isset($_SESSION['WooZone_country']) && !empty($_SESSION['WooZone_country']) ) {
					if ( $return_field == 'country' ) {
						return unserialize($_SESSION['WooZone_country']);
					}
				}
			}

			$ip = $this->get_client_ip();
			//var_dump('<pre>', $ip , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
								
			$config = $this->amz_settings;
			
			$paths = array(
				//'api.hostip.info'           	=> 'http://api.hostip.info/country.php?ip={ipaddress}',
				'www.geoplugin.net'         	=> 'http://www.geoplugin.net/json.gp?ip={ipaddress}',
				//'www.telize.com'            	=> 'http://www.telize.com/geoip/{ipaddress}',
				'ipinfo.io'                 	=> 'http://ipinfo.io/{ipaddress}/geo',
			);

			$service_used = 'www.geoplugin.net';
			if ( isset($config['services_used_forip']) && !empty($config['services_used_forip']) ) {
				$service_used = $config['services_used_forip'];
			}
	
			$country = '';
			if ( $service_used == 'local_csv' ) { // local csv file with ip lists
					
				// read csv hash (string with ip from list)
				$csv_hash = file_get_contents( $this->cfg['paths']['plugin_dir_path'] . 'assets/GeoIPCountryWhois-hash.csv' );
				$csv_hash = explode(',', $csv_hash);
				
				// read csv full (ip from, ip to, country)
				$csv_full = file_get_contents( $this->cfg['paths']['plugin_dir_path'] . 'assets/GeoIPCountryWhois-full.csv' );
				$csv_full = explode(PHP_EOL, $csv_full);
				
				//var_dump('<pre>',count($csv_hash), count($csv_full),'</pre>');
				//var_dump('<pre>',$csv_hash, $csv_full,'</pre>');

				$ip2number = $this->ip2number( $ip );
				//var_dump('<pre>', $ip, $ip2number, '</pre>');
				
				$ipHashIndex = $this->binary_search($ip2number, $csv_hash, array($this, 'binary_search_cmp'));
				if ( $ipHashIndex < 0 ) { // verify if is between (ip_from, ip_to) of csv row
						$ipHashIndex = abs( $ipHashIndex );
						$ipFullRow = $csv_full["$ipHashIndex"];
						$csv_row = explode(',', $ipFullRow);
						if ( $ip2number >= $csv_row[0] && $ip2number <= $csv_row[1] ) {
								$country = $csv_row[2];
						}
				} else { // exact match in the list as ip_from of csv row
						$ipFullRow = $csv_full["$ipHashIndex"];
						$country = end( explode(',', $ipFullRow) );
				}

				if (empty($country)) {
						//$main_aff_site = $this->main_aff_site();
						//$country = strtoupper(str_replace(".", '', $main_aff_site));
						$country = 'NOT-FOUND';
				}
				$country = strtoupper( $country );

				//var_dump('<pre>', $ipHashIndex, $ipFullRow, $country, '</pre>');
				//echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
					
			} else { // external service
			
				$service_url = $paths["$service_used"];
				$service_url = str_replace('{ipaddress}', $ip, $service_url);
	
				$get_user_location = wp_remote_get( $service_url );
				if ( isset($get_user_location->errors) ) {
					//$main_aff_site = $this->main_aff_site();
					//$country = strtoupper(str_replace(".", '', $main_aff_site));
					$country = 'NOT-FOUND';
				} else {
					$country = $get_user_location['body'];

					$country = json_decode($country);

					if ( ! is_object($country) ) $country = 'NOT-FOUND';
					else {
						switch ($service_used) {
							//case 'api.hostip.info':
							//	break;
								
							case 'www.geoplugin.net':
								//$country = json_decode($country);
								$country = isset($country->geoplugin_countryCode) ? strtoupper( (string)$country->geoplugin_countryCode ) : 'NOT-FOUND';
								break;
								
							//case 'www.telize.com':
							//	//$country = json_decode($country);
							//	$country = strtoupper( $country->country_code );
							//	break;
								
							case 'ipinfo.io':
								//$country = json_decode($country);
								$country = isset($country->country) ? strtoupper( (string)$country->country ) : 'NOT-FOUND';
								break;
								
							default:
								break;
						}
					}
					//$country = 'CN'; //DEBUG
				}
			}
			
			if ( $return_field == 'country' ) {
				$user_country = $this->amzForUser($country);
				//var_dump('<pre>',$user_country,'</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				//$this->cookie_set(array(
				//  'name'          => 'WooZone_country',
				//  'value'         => serialize($user_country),
				//  'expire_sec'    => strtotime( '+30 days' ) // time() + 604800, // 1 hour = 3600 || 1 day = 86400 || 1 week = 604800
				//));
				if ( $this->ss['cache_client_country'] ) {
					$_SESSION['WooZone_country'] = serialize($user_country);
				}
				return $user_country;
			}
		}

		public function cookie_set( $cookie_arr = array() ) {
			extract($cookie_arr);
			if ( !isset($path) )
				$path = '/';
			if ( !isset($domain) )
				$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
					$stat = setcookie($name, $value, $expire_sec, $path, $domain);
			return $stat;
		}
		public function cookie_del( $cookie_arr = array() ) {
			extract($cookie_arr);
			if ( !isset($path) )
				$path = '/';
			if ( !isset($domain) )
				$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
			setcookie($name, null, strtotime('-1 day'), $path, $domain);
		}
		
		public function get_client_ip() {
			$ipaddress = '';

			if ($_SERVER['REMOTE_ADDR'])
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			else if ($_SERVER['HTTP_CLIENT_IP'])
				$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			else if ($_SERVER['HTTP_X_FORWARDED'])
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			else if ($_SERVER['HTTP_FORWARDED_FOR'])
				$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			else if( $_SERVER['HTTP_FORWARDED'])
				$ipaddress = $_SERVER['HTTP_FORWARDED'];
			else if ($_SERVER['HTTP_X_FORWARDED_FOR'])
				$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];

			return $ipaddress;
		}

		public function ip2number( $ip ) {
			$long = ip2long($ip);
			if ($long == -1 || $long === false) {
				return false;
			}
			return sprintf("%u", $long);
		}
				
		public function verify_module_status( $module='' ) {
			if ( empty($module) ) return false;

			$mod_active = get_option( 'WooZone_module_'.$module );
			if ( $mod_active != 'true' )
					return false; //module is inactive!
			return true;
		}

		public function last_update_date($format=false, $last_date=false, $year=false) {
			if ( $last_date === '' ) return $last_date;
			if ( $last_date === false ) $last_date = time();
			if ( !$format ) return $last_date;

			$date_format = 'D j M / H.i';
			if ( $year ) $date_format = 'D j M Y / H.i';
			return date($date_format, $last_date); // Mon 2 Feb / 13.21
		}
		
		public function set_content_type($content_type){
			return 'text/html';
		}

		public function category_nice_name($categ_name) {
			$ret = $categ_name;

			$special = array('DVD' => 'DVD', 'MP3Downloads' => 'MP3 Downloads', 'PCHardware' => 'PC Hardware', 'VHS' => 'VHS');
			if ( !in_array($categ_name, array_keys($special)) ) {
				$ret = preg_replace('/([A-Z])/', ' $1', $categ_name);
			} else {
				$ret = $special["$categ_name"];
			}
			return $ret;
		}

		// This function works exactly how encodeURIComponent is defined:
		// encodeURIComponent escapes all characters except the following: alphabetic, decimal digits, - _ . ! ~ * ' ( )
		public function encodeURIComponent($str) {
			$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
			return strtr(rawurlencode($str), $revert);
		}

		/**
		 * Parameters: 
		 *   $key - The key to be searched for.
		 *   $list - The sorted array. 
		 *   $compare_func - A user defined function for comparison. Same definition as the one in usort
		 *   $low - First index of the array to be searched (local parameters).
		 *   $high - Last index of the array to be searched (local parameters). 
		 *
		 * Return:
		 *   index of the search key if found, otherwise return -(insert_index + 1). 
		 *   insert_index is the index of greatest element that is smaller than $key or count($list) if $key
		 *   is larger than all elements in the array.
		 * 
		 * License: Feel free to use the code if you need it.
		 */
		public function binary_search($key, array $list, $compare_func) {
						$low = 0; 
						$high = count($list) - 1;
		 
						while ($low <= $high) {
								$mid = (int) (($high - $low) / 2) + $low; // could use php ceil function
								$cmp = call_user_func($compare_func, $list[$mid], $key);
		 
								if ($cmp < 0) {
										$low = $mid + 1;
								} else if ($cmp > 0) {
										$high = $mid - 1;
								} else {
										return $mid;
								}
						}
						return -($low - 1);
		}
		public function binary_search_cmp($a, $b) {
			return ($a < $b) ? -1 : (($a > $b) ? 1 : 0);
		}
		
		/**
		 * Insane Mode - Last Imports Stats / Duration
		 */
		public function get_last_imports( $what='all' ) {
			$ret = array();
			$cfg = get_option('WooZone_insane_last_reports', array());

			$def = array(
					// duration in miliseconds
					'request_amazon'                    	=> 200, // request product from amazon
					'request_cache'                     	=> 10, // request product from cache
					'last_product'                      		=> 150, // product without the bellow options
					//'last_import_images'					=> 120, // add images to assets table
					'last_import_images_download'	=> 1500, // download images
					'last_import_images_remote'	=> 20, // remote images
					'last_import_variations'            	=> 150, // import variations
					'last_import_spin'                  	=> 65, // spin post content
					'last_import_attributes'            	=> 230, // import attributes
			);
			foreach ($def as $key => $val) {
					$def["$key"] = array(
							'items' => array(
									array( 'duration' => $val ),
							),
					);
			}

			foreach ($def as $key => $val) {
					// default
					if ( !isset($cfg["$key"], $cfg["$key"]['items']) || !is_array($cfg["$key"]['items'])
							|| empty($cfg["$key"]['items']) ) {
							
							$cfg["$key"] = $def["$key"];
					}
			}
			foreach ($cfg as $key => $val) {

					$media = array();
					foreach ($val['items'] as $key2 => $val2) {
							
							$duration = $val2['duration'];
							if ( isset($val2['nb_items']) && (int) $val2['nb_items'] > 0 ) {
									$nb_items = (int) $val2['nb_items'];
									$media[] = round( $duration / $nb_items, 4 );
							} else {
									$media[] = round( $duration, 4 );
							}
					}
					$media = !empty($media) ? round( array_sum($media) / count($media), 4 ) : 0;
					
					$cfg["$key"]["media"] = array('duration' => $media);
			}

			$ret = $cfg;
			//var_dump('<pre>', $ret, '</pre>'); die('debug...'); 
			return $ret;
		}
				
		public function add_last_imports( $what='all', $new=array() ) {
			if ( $what === 'all' || empty($new) ) return false;

			$max_last_keep = in_array($what, array('last_import_images_download', 'last_import_variations')) ? 10 : 5;
			$ret = array();
			$cfg = get_option('WooZone_insane_last_reports', array());
			
			if ( !isset($cfg["$what"], $cfg["$what"]['items']) || !is_array($cfg["$what"]['items']) ) {

					$cfg["$what"] = array(
							'items'     => array()
					);
			}
			
			if ( count($cfg["$what"]['items']) >= $max_last_keep ) {
					array_shift($cfg["$what"]['items']); // remove oldes maintained log regarding import
			}
			// add new latest log regarding import
			$cfg["$what"]['items'][] = $new;
			
			update_option('WooZone_insane_last_reports', $cfg);
		}

		public function timer_start() {
			$this->timer->start();
		}
		public function timer_end( $debug=false ) {
			$this->timer->end( $debug );
			$duration = $this->timer->getRenderTime(1, 0, false);
			return $duration;
		}
				
		public function format_duration( $duration, $precision=1 ) {
			$prec = $this->timer->getUnit( $precision );
			$ret = $duration . ' ' . $prec;
			$ret = '<i>' . $ret . '</i>';
			return $ret;
		}
				
		public function save_amazon_request_time() {
			$time = microtime(true);
			update_option('WooZone_last_amazon_request_time', $time);

			$nb = get_option('WooZone_amazon_request_number', 0);
			update_option('WooZone_amazon_request_number', (int)($nb+1));
			return true;
		}
		public function verify_amazon_request_rate( $do_pause=true ) {
			$ret = array('status' => 'valid'); // valid = no need for pause! 

			$rate = isset($this->amz_settings['amazon_requests_rate']) ? $this->amz_settings['amazon_requests_rate'] : 1;
			$rate = (float) $rate;
			$rate_milisec = $rate > 0.00 && (int)$rate != 1 ? 1000 / $rate : 1000; // interval between requests in miliseconds
			$rate_milisec = floatval($rate_milisec);

			$current = microtime(true);
			$last = get_option('WooZone_last_amazon_request_time', 0);
			$elapsed = round(($current - $last) * pow(10, 3), 0); // time elapsed from the last amazon requests

			// we may need to pause
			if ( $elapsed < $rate_milisec ) {
				if ( $do_pause ) {
					$pause_microsec = ( $rate_milisec - $elapsed ) + 30; // here is in miliseconds - add 30 miliseconds to be sure
					$pause_microsec = $pause_microsec * 1000; // pause in microseconds
					usleep( $pause_microsec );
				}
			}
			return $ret;
		}
		public function get_amazon_request_number() {
			$nb = get_option('WooZone_amazon_request_number', 0);
			return $nb;
		}
		
		public function save_amazon_request_remote_time() {
			$time = microtime(true);
			update_option('WooZone_last_amazon_request_remote_time', $time);

			$nb = get_option('WooZone_amazon_request_remote_number', 0);
			update_option('WooZone_amazon_request_remote_number', (int)($nb+1));
			return true;
		}

		public function get_amazon_request_remote_number() {
			$nb = get_option('WooZone_amazon_request_remote_number', 0);
			return $nb;
		}

		/**
		 * cURL / Send http requests with curl
		 */
		public static function curl($url, $input_params=array(), $output_params=array(), $debug=false) {
			$ret = array('status' => 'invalid', 'http_code' => 0, 'data' => '');

			// build curl options
			$ipms = array_replace_recursive(array(
				'userpwd'                   => false,
				'htaccess'                  => false,
				'post'                      => false,
				'postfields'                => array(),
				'httpheader'                => false,
				'verbose'                   => false,
				'ssl_verifypeer'            => false,
				'ssl_verifyhost'            => false,
				'httpauth'                  => false,
				'failonerror'               => false,
				'returntransfer'            => true,
				'binarytransfer'            => false,
				'header'                    => false,
				'cainfo'                    => false,
				'useragent'                 => false,
				//'followlocation' 			=> false,
			), $input_params);
			extract($ipms);

			$opms = array_replace_recursive(array(
				'resp_is_json'              => false,
				'resp_add_http_code'        => false,
				'parse_headers'             => false,
			), $output_params);
			extract($opms);

			//var_dump('<pre>', $ipms, $opms, '</pre>'); die('debug...'); 

			// begin curl
			$url = trim($url);
			if (empty($url)) return (object) $ret;

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);

			if ( !empty($userpwd) ) {
				curl_setopt($curl, CURLOPT_USERPWD, $userpwd);
			}
			if ( !empty($htaccess) ) {
				$url = preg_replace( "/http(|s):\/\//i", "http://" . $htaccess . "@", $url );
			}
			if (!$post && !empty($postfields)) {
				$url = $url . "?" . http_build_query($postfields);
			}

			if ($post) {
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
			}

			if ( !empty($httpheader) ) {
				curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheader);
			}
						
			curl_setopt($curl, CURLOPT_VERBOSE, $verbose);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ssl_verifypeer);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $ssl_verifyhost);
			if ( $httpauth!== false ) curl_setopt($curl, CURLOPT_HTTPAUTH, $httpauth);
			curl_setopt($curl, CURLOPT_FAILONERROR, $failonerror);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, $returntransfer);
			if ( isset($followlocation) && $followlocation!== false ) curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $followlocation);
			curl_setopt($curl, CURLOPT_BINARYTRANSFER, $binarytransfer);
			curl_setopt($curl, CURLOPT_HEADER, $header);
			if ( $cainfo!== false ) curl_setopt($curl, CURLOPT_CAINFO, $cainfo);
			if ( $useragent!== false ) curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
			if ( isset($timeout) && $timeout!== false ) curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
			if ( isset($connecttimeout) && $connecttimeout!== false ) curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $connecttimeout);

			$data = curl_exec($curl);
			$http_code = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);

			$ret = array_merge($ret, array('http_code' => $http_code));
			if ($debug) {
				$ret = array_merge($ret, array('debug_details' => curl_getinfo($curl)));
			}
			if ( $data === false || curl_errno($curl) ) { // error occurred
				$ret = array_merge($ret, array(
					'data' => curl_errno($curl) . ' : ' . curl_error($curl)
				));
			} else { // success

				if ( $parse_headers ) {
					$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
					$headers = self::parse_headers( substr($data, 0, $header_size) ); // response begin with the headers
					$data = substr($data, $header_size);
					$ret = array_merge($ret, array('headers' => $headers));
				}

				// Add the status code to the json data, useful for error-checking
				if ( $resp_add_http_code && $resp_is_json ) {
					$data = preg_replace('/^{/', '{"http_code":'.$http_code.',', $data);
				}

				$ret = array_merge($ret, array(
					'status'    => 'valid',
					'data'       => $data
				));
			}

			curl_close($curl);
			return $ret;
		}
		private static function parse_headers($headers) {
			if (!is_array($headers)) {
				$headers = explode("\r\n", $headers);
			}
			$ret = array();
			foreach ($headers as $header) {
				$header = explode(":", $header, 2);
				if (count($header) == 2) {
					$ret[$header[0]] = trim($header[1]);
				}
			}
			return $ret;
		}
		
	
		/**
		 * 2015, October fixes including attributes after woocommerce version 2.4.0!
		 */
		public function cleanValue($value) {
			// Format Camel Case
			//$value = trim( preg_replace('/([A-Z])/', ' $1', $value) );

			// Clean / from value
			$value = trim( preg_replace('/(\/)/', '-', $value) );
			return $value;
		}
		
		public function cleanTaxonomyName($value, $withPrefix=true) {
			$ret = $value;
			
			// Sanitize taxonomy names. Slug format (no spaces, lowercase) - uses sanitize_title
			if ( $withPrefix ) {
				$ret = wc_attribute_taxonomy_name($value); // return 'pa_' . $value
			} else {
				// return $value
				$ret = function_exists('wc_sanitize_taxonomy_name')
					? wc_sanitize_taxonomy_name($value) : woocommerce_sanitize_taxonomy_name($value);
			}
			$limit_max = $withPrefix ? 32 : 29; // 29 = 32 - strlen('pa_')
			
			// limit to 32 characters (database/ table wp_term_taxonomy/ field taxonomy/ is limited to varchar(32) )
			return substr($ret, 0, $limit_max);

			return $ret;
		}
	
		public function get_woocommerce_version() {
			$ver = '';
			$is_found = false;

			// try to find version
			if ( !$is_found && defined('WC_VERSION') ) {
				$ver = WC_VERSION;
				$is_found = true;
			}

			if ( !$is_found ) {
				global $woocommerce;
				if ( is_object($woocommerce) && isset($woocommerce->version) && !empty($woocommerce->version) ) {
					$ver = $woocommerce->version;
					$is_found = true;
				}
			}
			
			if ( !$is_found ) {
				// If get_plugins() isn't available, require it
				if ( !function_exists( 'get_plugins' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}
				
				foreach (array('envato-wordpress-toolkit', 'woocommerce') as $folder) {
					// Create the plugins folder and file variables
					$plugin_folder = get_plugins( '/'.$folder );
					$plugin_file = 'woocommerce.php';
		
					// If the plugin version number is set, return it 
					if ( isset( $plugin_folder[$plugin_file]['Version'] )
						&& !empty($plugin_folder[$plugin_file]['Version']) ) {

						$ver = $plugin_folder[$plugin_file]['Version'];
						$is_found = true;
						break;
					}
				}
			}
			return $ver;
		}
		public function force_woocommerce_product_version($ver_prod, $ver_min='2.4.0', $ver_ret=false) {
			// min version compare
			$ret = $ver_prod;
			if( version_compare( $ver_prod, $ver_min, "<" ) ) {
				$ret = $ver_ret ? $ver_ret : $ver_min;
			}
			return $ret;
		}
	
		public function get_main_settings( $provider='all' ) {
			$amz_settings = $this->amz_settings;
			$providers = array(
				'amazon'    => array(
					'title'     => __( 'Amazon Settings', $this->localizationName ),
					'mandatory' => array('AccessKeyID', 'SecretAccessKey', 'country', 'main_aff_id'),
					'keys'      => array(
						'AccessKeyID'       => array(
							'title'             => __( 'Access Key ID',$this->localizationName ),
							'value'             => '',
						),
						'SecretAccessKey'       => array(
							'title'             => __( 'Secret Access Key',$this->localizationName ),
							'value'             => '',
						),
						'country'       => array(
							'title'             => __( 'Amazon location',$this->localizationName ),
							'value'             => '',
						),
						'main_aff_id'       => array(
							'title'             => __( 'Main Affiliate ID',$this->localizationName ),
							'value'             => '',
						),
						'AffiliateID'       => array(
							'title'             => __( 'Affiliate IDs',$this->localizationName ),
							'value'             => '',
						),
					),
				),
			);
			foreach ($providers as $pkey => $pval) {
				foreach ($pval['keys'] as $pkey2 => $pval2) {
					if ( isset($amz_settings["$pkey2"]) ) {
						$pval2 = $amz_settings["$pkey2"];
						$providers["$pkey"]['keys']["$pkey2"]['value'] = $pval2;
						
						if ( preg_match('/(country|main_aff_id)/iu', $pkey2) ) {
							$obj = is_object($this->get_ws_object( $this->cur_provider )) ? $this->get_ws_object( $this->cur_provider ) : null;
			
							if ( !is_null($obj) ) {
								$providers["$pkey"]['keys']["$pkey2"]['value'] = $obj->get_country_name(
									$pval2,
									str_replace('ebay_', '', $pkey2)
								);
							}
						}
					}
				}
			}
			//var_dump('<pre>', $providers, '</pre>'); die('debug...');
			
			if ( $provider != 'all' ) {
				return isset($providers["$provider"]) ? $providers["$provider"] : array();
			}
			return $providers;
		}

		public function verify_mandatory_settings( $provider='amazon' ) {
			$ret = array(
				'status'        => 'invalid',
				'fields'        => array(),
				'fields_title'  => array(),
			);

			$module_settings = $this->get_main_settings( $provider );
			if ( empty($module_settings) ) return array_merge($ret, array());

			$mandatory = isset($module_settings['mandatory']) ? $module_settings['mandatory'] : array();
			if ( empty($mandatory) ) return array_merge($ret, array('status' => 'valid'));

			$module_mandatoryFields = array(); $fields = array();
			foreach ( $mandatory as $field ) {

				if ( isset($module_settings['keys']["$field"]['title']) ) {
					$fields["$field"] = $module_settings['keys']["$field"]['title'];                    
				}

				$module_mandatoryFields["$field"] = false;
				if ( isset($module_settings['keys']["$field"]['value'])
					&& !empty($module_settings['keys']["$field"]['value']) ) {

					$module_mandatoryFields["$field"] = true;
				}
			}

			$mandatoryValid = true;
			foreach ($module_mandatoryFields as $k=>$v) {
				if ( !$v ) {
					$mandatoryValid = false;
					break;
				}
			}
			return array_merge($ret, array(
				'status'        => $mandatoryValid ? 'valid' : 'invalid',
				'fields'        => array_keys($fields),
				'fields_title'  => array_values($fields),
			));
		}

		public function settings() {
			//$settings = $this->getAllSettings('array', 'amazon');
			$settings = get_option( $this->alias . '_amazon' ); // 'WooZone_amazon'
			$settings = maybe_unserialize( $settings );
			$settings = !empty($settings) && is_array($settings) ? $settings : array();

			$def = array(
				'AccessKeyID' 		=> '', //zzz
				'SecretAccessKey' 	=> '', //zzz
				'country'			=> 'com',
				'main_aff_id' 		=> 'aateam',
				'AffiliateID' 		=> array(),
			);
			foreach ($def as $key => $val) {
				if ( ! isset($settings["$key"]) || ('' == $settings["$key"]) ) {
					$settings["$key"] = $val;
				}
			}

			$this->amz_settings = $settings;
			return $this->amz_settings;
		}

		public function build_amz_settings( $new=array() ) {
			if ( !empty($new) && is_array($new) ) {
				$this->amz_settings = array_replace_recursive($this->amz_settings, $new);
			}
			return $this->amz_settings;
		}


		/**
		 * Octomber 2015 - new plugin functions
		 */
		public function get_ws_prefixes($ws='all') {
			$wslist = array(
				'amazon'        => 'amz',
			);
			return $ws == 'all' ? $wslist : ( isset($wslist["$ws"]) ? $wslist["$ws"] : false );
		}
		
		public function get_ws_status($ws='all') {
			$wslist = array(
				'amazon'        => true,
			);
			return $ws == 'all' ? $wslist : ( isset($wslist["$ws"]) ? $wslist["$ws"] : false );
		}

		public function get_post_meta($post_id, $key='', $single=false, $withPrefix=true) {
			$_key = $key;
			//if ( $_key == '_amzASIN' ) $key = '_aiowaff_prodid';
			$ret = get_post_meta($post_id, $key, $single);
			if ( !$withPrefix && ($_key == '_amzASIN') ) {
				$wslist = $this->get_ws_prefixes();
				foreach ($wslist as $wsprefix) {
					$ret = str_replace($wsprefix.'-', '', $ret);
				}
			}
			return $ret;
		}

		public function get_product_by_wsid( $wsid ) {
			global $wpdb;
			
			//$key = '_aiowaff_prodid';
			$key = '_amzASIN';
			$query = "SELECT a.ID, a.post_title FROM {$wpdb->posts} AS a LEFT JOIN {$wpdb->postmeta} AS b ON a.ID = b.post_id WHERE 1=1 AND b.meta_key = '$key' AND b.meta_value = '".esc_sql($wsid)."' AND !ISNULL(b.meta_id);";
			$result =  $wpdb->get_results($query , ARRAY_A);
			return (isset($result[0]) ? $result[0] : $result);
		}

		/**
		 * Call Example
			$args = array(
				'post_title'    => $retProd['Title'],
				'post_status'   => $default_import,
				'post_content'  => $desc,
				'post_excerpt'  => $excerpt,
				'post_type'     => 'product',
				'menu_order'    => 0,
				'post_author'   => 1
			); 
		 */
		public function get_product_by_args($args) {
			global $wpdb;

			$args = array_merge(array(
				'post_title'    => '',
				'post_status'   => 'publish',
				'post_content'  => '',
				'post_excerpt'  => '',
				'post_type'     => 'product',
				'menu_order'    => 0,
				'post_author'   => 1
			), $args);

			//$result = $wpdb->get_row("SELECT * FROM " . ( $wpdb->prefix ) . "posts WHERE 1=1 and post_status = '" . ( $args['post_status'] ) . "' and post_title = '" .  ( $args['post_title'] )  . "'", 'ARRAY_A');
			$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . ( $wpdb->prefix ) . "posts WHERE 1=1 and post_type IN ('product', 'product_variation') and post_status = '" . ( $args['post_status'] ) . "' and post_title = %s", $args['post_title'] ), 'ARRAY_A' );
			if(count($result) > 0){
				return $result;
			}
			return false;
		}

		// get webservice object
		public function get_ws_object( $provider, $what='helper' ) {
			$arr = array(
				//'generic'     => array(
				//  'helper'        => $this->genericHelper,
				//  'ws'            => null,
				//),
				'amazon'        => array(
					'helper'        => $this->amzHelper,
					'ws'            => is_object($this->amzHelper) ? $this->amzHelper->aaAmazonWS : null,
				),
			);
			return $arr["$provider"]["$what"];
		}
		
		public function prodid_get_provider_alias( $id ) {
			$_id = explode('-', $id);
			return count($_id) > 1 ? $_id[0] : 'zzz';
		}
		
		public function prodid_get_asin( $id ) {
			$_id = explode('-', $id);
			return count($_id) > 1 ? $_id[1] : '9999999';
		}
		
		public function prodid_get_provider( $alias ) {
			$wslist = $this->get_ws_prefixes();
			foreach ($wslist as $key => $wsprefix) {
				if ( $alias == $wsprefix ) {
					return $key;
				}
			}
			return '';
		}

		public function prodid_set( $id, $provider, $what ) {
			$ret = array();
			$alias = $this->get_ws_prefixes($provider);

			if (empty($id)) return $id;
			$isa = is_array($id) ? true : false;

			if ( !$isa ) {
				$id = array($id);
			}
			foreach ($id as $key => $val) {
				if (empty($val)) {
					$ret["$key"] = $val;
					continue;
				}
				if ( 'add' == $what ) {
					$ret["$key"] = $val;
					if ( !preg_match('/^('.$alias.').*/imu', $val, $m) ) {
						$ret["$key"] = $alias.'-' . $val;
					}
				}
				else if ( 'sub' == $what ) {
					$ret["$key"] = str_replace($alias.'-', '', $val);
				}
			}
			if ( !$isa ) {
				return $ret[0];
			}
			return $ret;
		}
	
		public function set_product_meta_asset( $post_id, $metas=array() )
		{
			foreach ($metas as $key => $val) {
				update_post_meta( $post_id, $key, $val );
			}
		}
	
		public function multi_implode_keyval($array, $glue) 
		{
			$ret = '';
	
			foreach ($array as $key => $item) {
					if (is_array($item)) {
							$ret .= $this->multi_implode($item, $glue) . $glue;
					} else {
							$ret .= ($key . ': ' . $item) . $glue;
					}
			}
	
			$ret = substr($ret, 0, 0-strlen($glue));
	
			return $ret;
		}

		/**
		 * 2016, february
		 */
		public function is_module_active( $alias, $is_admin=true ) {
			$cfg = $this->cfg;

			$ret = false;

			// is module activated?
			if ( isset($cfg['modules'], $cfg['modules'][$alias], $cfg['modules'][$alias]['load_in']) ) {
				$ret = true;
			}
			// fix in 2018-jan
			else if ( isset($cfg['modules'], $cfg['modules'][$alias], $cfg['modules'][$alias][$alias]['load_in']) ) {
				$ret = true;
			}

			// is module in admin section?
			if ( $is_admin && !$this->is_admin ) {
				//$ret = false;
			}

			return $ret;
		}
	
		public function debug_get_country() {
			$ip = $this->get_client_ip();
			$country = $this->get_country_perip_external();

			var_dump('<pre>',$ip, $country,'</pre>');
			echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
		}
	
		public function bulk_wp_exist_post_by_args( $args ) {
			global $wpdb;
			//$result = $wpdb->get_row("SELECT * FROM " . ( $wpdb->prefix ) . "posts WHERE 1=1 and post_status = '" . ( $args['post_status'] ) . "' and post_title = '" .  ( $args['post_title'] )  . "'", 'ARRAY_A');
			$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . ( $wpdb->prefix ) . "posts WHERE 1=1 and post_type IN ('product', 'product_variation') and post_status = '" . ( $args['post_status'] ) . "' and post_title = %s", $args['post_title'] ), 'ARRAY_A' );
			if ( is_array($result) && ! empty($result) ) {
				return $result;
			}
			return false;
		}

		public function product_by_asin( $asins=array() ) {
			$asins = array_unique( array_filter($asins) );
			if (empty($asins)) return array();

			$ret = array_fill_keys ( $asins, false );

			global $wpdb;
		
			$asins_ = implode(',', array_map(array($this, 'prepareForInList'), $asins));

			$sql_asin2id = "select pm.meta_value as asin, p.* from " . $wpdb->prefix.'posts' . " as p left join " . $wpdb->prefix.'postmeta' . " as pm on p.ID = pm.post_id where 1=1 and !isnull(p.ID) and pm.meta_key = '_amzASIN' and pm.meta_value != '' and pm.meta_value in ($asins_);";
			$res_asin2id = $wpdb->get_results( $sql_asin2id, OBJECT_K );
			if ( !empty($res_asin2id) ) {
				foreach ($res_asin2id as $k => $v) {
					$asin = $v->asin;
					$ret["$asin"] = $v; 
				}
			}
			return $ret;
		}
	
	
		/**
		 * March 2016 - new methods
		 */
		public function get_aateam_demo_keys() {
			$demo_keys = array(
				// this is the text by which we know that he uses aa-team demo keys
				'alias' 		=> array(
					'AccessKeyID' 		=> 'aateam demo access key',
					'SecretAccessKey' 	=> 'aateam demo secret access key',
				),
				// these are pairs of keys which if are found setted, we interpret them as he really wants aa-team demo keys
				// !!! empty value keys & keys as above alias are by default iterpreted as demo
				// for each pair: index 0 = AccessKeyID, index 1 = SecretAccessKey
				'pairs' 		=> array(
					//:: start - DO NOT EDIT (this pair should always remain here)
					array( 'AKIAI2SZTIJCPKND45QA', 'Plt1xvnFAZJ2jlDGBozD4r8urvKciXpYm3yT7tAc' ),
					//:: end - DO NOT EDIT

					// add new pairs from here...
					/*array( 'AKIAJVUERCW2YISPWH2A', 'pF1OIAz2Ojk6qWUwdOXu3INO87/2tfMYLao6RqwP' ),
					array( 'AKIAIKFIR7QHU3RJIYWQ', 'yMj6J+vy694/ZjzB7YnHWDHRsZsi0wHFc/jD696/' ),
					array( 'AKIAJYSQRYKT7574QQRA', 'sNX69Zb0Y08+K1AiA+e4cmXfFc+e53DnkWNx2xqI' ),*/
				),
			);
			return $demo_keys;
		}

		public function verify_amazon_keys( $pms=array() ) {

			$pms = array_replace_recursive(array(
				'settings' 	=> array(),
			), $pms);
			extract( $pms );

			$is_custom = ! empty( $settings ) && is_array($settings) ? true : false;

			// aa-team demo keys - March 2016 - new /Update on 2017-10-03
			$demo_keys = $this->get_aateam_demo_keys();

			$ret = array(
				// valid | invalid | demo
				'status' 			=> '',

				// -3 = just a default value
				// -2 = alias text keys
				// -1 = empty value keys
				// >=0 = demo keys pair
				'pair_idx' 		=> -3,

				// amazon settings
				'settings' 		=> array(),
			);

			if ( ! $is_custom ) {
				$amz_settings = $this->amz_settings;
				$settings = array(
					'AccessKeyID' 		=> isset($amz_settings['AccessKeyID'])
						? trim($amz_settings['AccessKeyID']) : '',
					'SecretAccessKey' 	=> isset($amz_settings['SecretAccessKey'])
						? trim($amz_settings['SecretAccessKey']) : '',
				);
			}
			$ret['settings'] = $settings;

			// current keys from db
			$current_keys = array(
				'AccessKeyID' 		=> isset($settings['AccessKeyID'])
					? trim($settings['AccessKeyID']) : '',
				'SecretAccessKey' 	=> isset($settings['SecretAccessKey'])
					? trim($settings['SecretAccessKey']) : '',
			);
			//var_dump('<pre>',$current_keys,'</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			// default keys status
			$status = 'valid';

			// at least one key for a pair is setted as ( empty value | demo alias text ) => demo keys
			$_status = array();
			foreach ($current_keys as $key_id => $key_val) {
				if ( '' == $key_val ) {
					$status = 'demo';
					$ret['pair_idx'] = -1;
					break;
				}

				if ( $key_val == $demo_keys['alias']["$key_id"] ) {
					$status = 'demo';
					$_status[] = $key_id;
				}
			}
			// if full pair of both keys is found => we don't mark them again in db - see below step
			if ( ( 2 == count($_status) ) && ( 'demo' == $status ) ) {
				$status = 'demo999';
				$ret['pair_idx'] = -2;
			}

			// verify if curenty keys are demo keys: both keys value from each pair must match
			if ( 'valid' == $status ) {
				foreach ($demo_keys['pairs'] as $pair_idx => $pair_set) {
					//if ( preg_match('/^demo/i', $status) ) break;

					$_status = true;
					foreach ($current_keys as $key_id => $key_val) {
						$__kdidx = 'AccessKeyID' == $key_id ? 0 : 1;

						if ( $key_val != $pair_set[$__kdidx] ) {
							$_status = false;
							break;
						}
					}

					if ( $_status ) {
						$status = 'demo';
						$ret['pair_idx'] = $pair_idx;
						break;
					}
				}
			}

			// mark demo keys in database with "demo text"
			if ( 'demo' == $status ) {
				if ( ! $is_custom ) {
					$amz_settings = $this->settings();
					$amz_settings = !empty($amz_settings) && is_array($amz_settings) ? $amz_settings : array();
					
					foreach ($demo_keys['alias'] as $key_id => $key_val) {
						$amz_settings["$key_id"] = $key_val;
					}
					update_option( $this->alias . '_amazon', $amz_settings ); // 'WooZone_amazon'
				}
			}

			// make demo keys usable in amazon settings: use first found pair
			if ( preg_match('/^demo/i', $status) ) {
				$status = 'demo';

				// make demo keys usable
				foreach ($demo_keys['alias'] as $key_id => $key_val) {

					$__kdidx = 'AccessKeyID' == $key_id ? 0 : 1;
					$__kd = isset($demo_keys['pairs'][0], $demo_keys['pairs'][0][$__kdidx])
						? $demo_keys['pairs'][0][$__kdidx] : '';

					if ( ! $is_custom ) {
						$this->amz_settings["$key_id"] = $__kd;
					}
					$ret['settings']["$key_id"] = $__kd;
				}
			}

			$ret['status'] = $status;
			return $ret;
		}

		// number of products imported using aa-team demo keys
		// toret = nb (number of products) | idlist (list of product ids)
		public function get_products_demo_keys( $toret='nb' ) {
			$db = $this->db;
			$table = $db->postmeta;

			if ( 'nb' == $toret ) {
				$sql = "select count(pm.meta_id) as nb from $table as pm where 1=1 and pm.meta_key = '_amzaff_aateam_keys' and pm.meta_value = '1';";
				$res = $db->get_var( $sql );
				return (int) $res;
			}
			else {
				$sql = "select pm.post_id from $table as pm where 1=1 and pm.meta_key = '_amzaff_aateam_keys' and pm.meta_value = '1';";
				$res = $db->get_results( $sql, OBJECT_K );
				if ( empty($res) ) return array();
				return array_keys( $res );
			}
			return false;
		}

		// allowed: to import products using aa-team demo keys
		public function is_allowed_products_demo_keys() {
			$ret = $this->get_products_demo_keys() < $this->ss['max_products_demo_keys'] ? true : false;
			return $ret;
		}
		
		// allowed: to make remote requests to aa-team demo server
		public function is_allowed_remote_requests() {
			$ret = $this->get_amazon_request_remote_number() < $this->ss['max_remote_request_number'] ? true : false;
			return $ret;
		}
		
		// is: aa-team demo keys
		public function is_aateam_demo_keys() {
			$_status = $this->verify_amazon_keys();
			$_status = $_status['status'];

			$ret = 'demo' == $_status ? true : false;
			return $ret;
		}
		
		// aa-team demo server, not whole server, just the wp install for demo keys
		public function is_aateam_server() {
			//$ret = ('cc.aa-team.com' == $_SERVER['SERVER_NAME'])
			//	|| ('46.101.188.140' == $_SERVER['SERVER_ADDR']);
			//return $ret;

			if ( defined('WOOZONE_KEYS_SERVER') && WOOZONE_KEYS_SERVER ) {
				return true;
			}
			return false;
		}
		// aa-team development / dev server
		public function is_aateam_devserver() {
			if ( defined('WOOZONE_DEV_SERVER') && WOOZONE_DEV_SERVER ) {
				return true;
			}
			return false;
		}

		public function can_import_products() {
			// we are using aa-team demo keys
			// and
			// we are NOT on aa-team demo server
			if ( $this->is_aateam_demo_keys() ) {
				
				// we are allowed to import products using aa-team demo keys
				if ( $this->is_allowed_products_demo_keys() ) {
					return true;
				}
				else {
					return false;
				}
			}
			return true;
		}

		// 2018-05-07: return always FALSE, we've disabled this functionality
		// conditions are fulfilled for this to be a remote request to aa-team demo server
		public function do_remote_amazon_request( $what_rules=array() ) {
			return false;

			// we are using aa-team demo keys
			// and
			// we are NOT on aa-team demo server, not whole server, just the wp install for demo keys
			if ( $this->is_aateam_demo_keys() && ! $this->is_aateam_server() ) {
				
				// we are allowed to import products using aa-team demo keys
				if ( $this->is_allowed_products_demo_keys() ) {
					return true;
				}
				else {
					return false;
				}
			}
			return false;
		}
		
		// get remote request from aa-team demo server
		public function get_remote_amazon_request( $pms=array() ) {
			$ret = array(
				'status' 		=> 'invalid',
				'msg' 			=> '',
				'response' 		=> array(),
				'code' 			=> -1,
				'amz_code' 		=> '',
			);
			
			$remote_url = self::$aateam_keys_script . '?' . http_build_query(array(
				'action'            => 'amazon_request',
				'what_func'         => isset($pms['what_func']) ? $pms['what_func'] : '',
			));

			$params = array_merge(array(), $pms, array(
				'__request' => array(
					'client_ip'         => $this->get_client_ip(),
					'client_website'    => get_site_url(),
					'country'           => isset($this->amz_settings['country']) ? $this->amz_settings['country'] : 'com',
				),
			));
			if ( isset($params['amz_settings']) ) {
				$params['amz_settings'] = array(
					// NOT SENDing access keys for security concerns!
					'AccessKeyID'           => '', //$params['amz_settings']['AccessKeyID'],
					'SecretAccessKey'       => '', //$params['amz_settings']['SecretAccessKey'],

					'main_aff_id'           => isset($params['amz_settings']['main_aff_id']) ? $params['amz_settings']['main_aff_id'] : '',
					'country'               => isset($params['amz_settings']['country']) ? $params['amz_settings']['country'] : '',
				);
				//unset( $params['amz_settings'] );
			}
			//var_dump('<pre>', $remote_url, $params, '</pre>');
			//echo __FILE__ . ":" . __LINE__; die . PHP_EOL;

			$response = wp_remote_post( $remote_url, array(
				'method' => 'POST',
				'timeout' => 30,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => $params
			));
			//var_dump('<pre>', $response['body'], '</pre>');
			//echo __FILE__ . ":" . __LINE__; die . PHP_EOL;

			// If there's error
			if ( is_wp_error( $response ) ){
				return array_merge($ret, array('msg' => $response->get_error_message()));
			}
			$body = wp_remote_retrieve_body( $response );
			//var_dump('<pre>', $body, '</pre>'); die('debug...');

			if ( !function_exists('simplexml_load_string') ) {
				return array_merge($ret, array('msg' => 'Function simplexml_load_string don\'t exists!'));
			}

			if( strpos((string)$body, '<response>') === false ) {
				return array_merge($ret, array('msg' => 'Invalid xml response retrieved from aa-team server!'));
			}
			//var_dump('<pre>', $body, '</pre>'); die('debug...');

			$body = simplexml_load_string( $body );

			 $resp = array(
				'status' 		=> isset($body->status) ? (string) $body->status : 'invalid',
				'msg' 			=> isset($body->msg) ? (string) $body->msg : 'unknown error',
				'response' 		=> isset($body->body) ? (string) $body->body : '',
				'code' 			=> isset($body->code) ? (string) $body->code : -1,
				'amz_code' 		=> isset($body->amz_code) ? (string) $body->amz_code : '',
			 );
  
			// validate response
			 if ( empty($resp['response']) ) {
				$resp['response'] = array();
			 }
			 
			 $resp['response'] = maybe_unserialize( $resp['response'] );

			 if ( empty($resp['response']) || !is_array($resp['response']) ) {
				$resp['response'] = array();
			 }

			return $resp;
		}
		
		// save last requests to amazon: local or from aa-team demo server
		public function save_amazon_last_requests( $new=array() ) {
			$max_last_keep = 50;

			$last = get_option('WooZone_last_amazon_requests', array());

			if ( !isset($last['items']) || !is_array($last['items']) ) {
				$last = array(
						'items'     => array()
				);
			}

			if ( count($last['items']) >= $max_last_keep ) {
				array_shift($last['items']); // remove oldes maintained row
			}

			//'amz_settings'            => $this->amz_settings,
			//'from_file'               => str_replace($this->cfg['paths']['plugin_dir_path'], '', __FILE__),
			//'from_func'               => __FUNCTION__ != __METHOD__ ? __METHOD__ : __FUNCTION__, 
						// add new latest row
			$last['items'][] = array(
				'time'              => time(),
				'amazon'            => array(
					'AccessKeyID'           => $new['amz_settings']['AccessKeyID'],
					'SecretAccessKey'       => $new['amz_settings']['SecretAccessKey'],
					'main_aff_id'           => isset($new['amz_settings']['main_aff_id']) ? $new['amz_settings']['main_aff_id'] : '',
					'country'               => isset($new['amz_settings']['country']) ? $new['amz_settings']['country'] : '',
				),
				'from_file'         => $new['from_file'],
				'from_func'         => $new['from_func'],
				'status'            => $new['request_status']['status'],
				'msg'               => $new['request_status']['msg'],
				'is_remote'         => isset($new['is_remote']) && $new['is_remote'] ? 1 : 0,
			);
						
			update_option('WooZone_last_amazon_requests', $last);
		}
		
		// get last requests to amazon: local or from aa-team demo server
		public function get_amazon_last_requests() {
			$last = get_option('WooZone_last_amazon_requests', array());
			return $last;
		}

		// notice to show amazon requests: local or from aa-team demo sever
		public function print_demo_request()
		{
			return $this->_admin_notice_amazon_keys();
		}

		public function _admin_notice_amazon_keys( $print=true )
		{
			ob_start();
		?>
		<div class="WooZone-callout WooZone-callout-info WooZone-demo-keys">
			<h4><?php
				//_e( sprintf(
				//	// '<strong>%s</strong> &#8211; You are using AA-Team DEMO keys ( AccessKeyID = <span class="marked">%s</span>, SecretAccessKey = <span class="marked">%s</span> ) and you\'ve made <span class="marked">%s</span> amazon requests (<span class="marked">%s</span> remote).',
				//	'<strong>%s</strong> &#8211; You are using AA-Team DEMO keys and you\'ve made <strong>%s</strong> requests to amazon and <strong>%s</strong> remote requests.',
				//	$this->pluginName,
				//	// '',
				//	// '',
				//	$this->get_amazon_request_number(),
				//	$this->get_amazon_request_remote_number()
				//), $this->localizationName );
				if ( $this->is_aateam_demo_keys() ) {
					$msg = sprintf(
						'<strong>%s</strong> &#8211; You are using AA-Team DEMO keys and you\'ve made <strong>%s</strong> requests to amazon and <strong>%s</strong> remote requests.',
						$this->pluginName,
						$this->get_amazon_request_number(),
						$this->get_amazon_request_remote_number()
					);
				}
				else {
					$msg = sprintf(
						'<strong>%s</strong> &#8211; You\'ve made <strong>%s</strong> requests to amazon and <strong>%s</strong> remote requests.',
						$this->pluginName,
						$this->get_amazon_request_number(),
						$this->get_amazon_request_remote_number()
					);
				}
				_e( $msg, $this->localizationName );
			?></h4>
			<?php
				$html = array();                
				$last = $this->get_amazon_last_requests();
				$last = isset($last['items']) ? (array) $last['items'] : array();
				$last = array_reverse($last, true);
				if ( !empty($last) ) {
					$html[] = '<div class="last-requests" id="WooZone-list-rows">';
					$html[] = '<a href="#" class="WooZone-form-button-small WooZone-form-button-primary">' . __('view last requests', $this->localizationName) . '</a>';
					$html[] = '<table class="WooZone-table" style="width: 100%">';
					$html[] =   '<thead>';
					$html[] =       '<tr>';
					$html[] =           '<th>';
					$html[] =               __('Time', $this->localizationName);
					$html[] =           '</th>';
					$html[] =           '<th width="300">';
					$html[] =               __('From file', $this->localizationName);
					$html[] =           '</th>';
					$html[] =           '<th width="400">';
					$html[] =               __('From function', $this->localizationName);
					$html[] =           '</th>';
					$html[] =           '<th width="100">';
					$html[] =               __('Status', $this->localizationName);
					$html[] =           '</th>';
					$html[] =           '<th width="200">';
					$html[] =               __('Status message', $this->localizationName);
					$html[] =           '</th>';
					$html[] =       '</tr>';
					$html[] =   '</thead>';
					$html[] =   '<tfoot>';
					$html[] =   '</tfoot>';
					$html[] =   '<tbody>';
				}
				foreach ($last as $key => $val) {
					$html[] =       '<tr>';
					$html[] =           '<td>';
					$html[] =               $this->last_update_date(true, $val['time']);
					$html[] =           '</td>';
					$html[] =           '<td>';
					$html[] =               $val['from_file'];
					$html[] =           '</td>';
					$html[] =           '<td>';
					$html[] =               $val['from_func'];
					$html[] =           '</td>';
					$html[] =           '<td>';
					$html[] =               $val['status'];
					$html[] =           '</td>';
					$html[] =           '<td>';
					$html[] =               '<div class="status-msg">'
												. (isset($val['is_remote']) && $val['is_remote'] ? 'Remote | ' : '')
												. $val['msg']
											. '</div>';
					$html[] =           '</td>';
					$html[] =       '</tr>';
				}
				if ( !empty($last) ) {
					$html[] =   '</tbody>';
					$html[] = '</table>';
					$html[] = '</div>';
				}
				echo implode(PHP_EOL, $html);
			?>
		</div>
		<?php
			$contents = ob_get_clean();

			if ( $print ) echo $contents;
			else return $contents;
		}

		public function print_section_header( $title='', $desc='', $docs_url='')
		{
			$html = array();

			$html[] = '<div class="panel panel-default ' . ( $this->alias ) . '-panel ' . ( $this->alias ) . '-section-header">';
			$html[] =   '<div class="panel-heading ' . ( $this->alias ) . '-panel-heading">';
			if( trim($title) != "" )    $html[] =       '<h1 class="panel-title ' . ( $this->alias ) . '-panel-title">' . ( $title ) . '</h1>';
			if( trim($desc) != "" )     $html[] =       $desc;
			$html[] =   '</div>';
			$html[] =   '<div class="panel-body ' . ( $this->alias ) . '-panel-body ' . ( $this->alias ) . '-no-padding" >';
			
			
			if( trim($docs_url) != "" ) $html[] =       '<a href="' . ( $docs_url ) . '" target="_blank" class="' . ( $this->alias ) . '-tab"><i class="' . ( $this->alias ) . '-icon-support"></i>  Documentation</a>';
			$html[] =       '<a href="' . ( $this->plugin_row_meta( 'portfolio' ) ) . '?ref=AA-Team" target="_blank" class="' . ( $this->alias ) . '-tab"><i class="' . ( $this->alias ) . '-icon-other_products"></i> More AA-Team Products</a>';
			$html[] =   '</div>';
			$html[] = '</div>';

			return implode(PHP_EOL, $html);
		}

		public function get_image_sizes() {
			$wp_sizes = $this->u->get_image_sizes();

			$allowed = isset($this->amz_settings['images_sizes_allowed'])
				? $this->amz_settings['images_sizes_allowed'] : array();
			$allowed = $this->clean_multiselect( $allowed );
			$allowed = !empty($allowed) && is_array($allowed) ? $allowed : array();

			if ( empty($allowed) ) return $wp_sizes;
			foreach ( $wp_sizes as $size => $props ) {
				if ( !in_array($size, $allowed) ) {
					unset($wp_sizes["$size"]);
				}
			}
			return $wp_sizes;
		}


		/**
		 * 2016-july
		 */
		/**
		 * item			: A. result of amazon helper file / build_product_data | B. full api response array
		 * is_filtered	: true => you use A. ; false => you use B.
		 * retWhat	: what product description to retrieve: both | desc | short 
		 */
		public function product_build_desc( $item=array(), $is_filtered=true, $retWhat='both' ) {

			$retProd = array_replace_recursive(array(
				'EditorialReviews'		=> '',
				'Feature'				=> '',
				'ASIN'					=> '',
				'hasGallery'			=> 'false',
			), $item);

			// parse full amazon api response
			if ( !$is_filtered ) {

				$retProd = array();

				$EditorialReviews = isset($item['EditorialReviews']['EditorialReview']['Content'])
						? $item['EditorialReviews']['EditorialReview']['Content'] : '';

				// try to rebuid the description if it's empty
				if( trim($EditorialReviews) == "" ){
					if( isset($item['EditorialReviews']['EditorialReview']) && count($item['EditorialReviews']['EditorialReview']) > 0 ){
						
						$new_description = array();
						foreach ($item['EditorialReviews']['EditorialReview'] as $desc) {
							if( isset($desc['Content']) && isset($desc['Source']) ){
								//$new_description[] = '<h3>' . ( $desc['Source'] ) . ':</h3>';
								$new_description[] = $desc['Content'] . '<br />';
							}
						}
					}
					
					if( isset($new_description) && count($new_description) > 0 ){
						$EditorialReviews = implode( "\n", $new_description );
					}
				}

				$retProd['EditorialReviews'] = $EditorialReviews;

				$retProd['Feature'] = isset($item['ItemAttributes']['Feature']) ? $item['ItemAttributes']['Feature'] : '';

				$retProd['hasGallery'] = 'false';
			}

			if ( isset($item['__parent_asin']) ) {
				$retProd['ASIN'] = isset($item['__parent_asin']) ? $item['__parent_asin'] : '';
			}
			if ( isset($item['__parent_content']) ) {
				if ( preg_match('/\[gallery\]/imu', $item['__parent_content']) ) {
					$retProd['hasGallery'] = 'true';
				}
			}

			// short description
			$show_short_description = isset($this->amz_settings['show_short_description'])
				? $this->amz_settings['show_short_description'] : 'yes';

			$is_short_desc = $show_short_description;

			$excerpt = '';
			if ( $is_short_desc ) {
				// first 3 paragraph
				$excerpt = @explode("\n", @strip_tags( implode("\n", $retProd['Feature']) ) );
				$excerpt = @implode("\n", @array_slice($excerpt, 0, 3));
			}

			// full description
			$__desc = array();
			$__desc[] = ($retProd['hasGallery'] == 'true' ? "[gallery]" : "");
			$__desc[] = !empty($retProd['EditorialReviews']) ? $retProd['EditorialReviews'] : '';
			$__desc[] = (count($retProd['Feature']) > 0 &&  is_array($retProd['Feature']) == true ? implode("\n", $retProd['Feature']) : '');

			// [amz_corss_sell asin="B01G7TG6SW"]
			$cross_selling = (isset($this->amz_settings["cross_selling"]) && $this->amz_settings["cross_selling"] == 'yes' ? true : false);

			if( $cross_selling == true ) {
				$__desc[] = '[amz_corss_sell asin="' . ( $retProd['ASIN'] ) . '"]';
			}
			$desc = implode("\n", array_filter($__desc));

			if ( 'both' == $retWhat ) {
				return array(
					'short'			=> $excerpt,
					'desc'			=> $desc,
				);
			}
			if ( 'desc' == $retWhat ) {
				return $desc;
			}
			return $excerpt;
		}

		public function product_clean_desc( $post_content ) {
			$__post_content = $post_content;
			$__post_content = preg_replace('/\[gallery\]/imu', '', $__post_content);

			// [amz_corss_sell asin="B01G7TG6SW"]
			$__post_content = preg_replace('/\[amz_corss_sell asin\=".*"\]/imu', '', $__post_content);
			$__post_content = trim( $__post_content );
			return $__post_content;
		}

		// Determine if SSL is used.
		public function is_ssl() {
			if ( isset($_SERVER['HTTPS']) ) {
				if ( 'on' == strtolower($_SERVER['HTTPS']) )
					return true;
				if ( '1' == $_SERVER['HTTPS'] )
					return true;
			} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
				return true;
			}
			
			// HTTP_X_FORWARDED_PROTO: a de facto standard for identifying the originating protocol of an HTTP request, since a reverse proxy (load balancer) may communicate with a web server using HTTP even if the request to the reverse proxy is HTTPS
			if ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ) {
				if ( 'https' == strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) )
					return true;
			}
			if ( isset($_SERVER['HTTP_X_FORWARDED_SSL']) ) {
				if ( 'on' == strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) )
					return true;
				if ( '1' == $_SERVER['HTTP_X_FORWARDED_SSL'] )
					return true;
			}
			return false;
		}

	
		/**
		 * 2016-september - for amzstore plugin
		 */
		// in wp_options we have options like 'amzStore' but in version >= 9.0 I've changed plugin alias, so we have options like 'AmzStore" 
		public function fix_dbalias_issue() {
			$ret = array('status' => 'invalid', 'msg' => 'unknown msg.', 'count' => array());
			if ( 'AmzStore' != $this->alias ) return $ret;

			$found = get_option('AmzStore_fixed_dbalias', false);

			// already fixed
			if ( $found ) return $ret;

			global $wpdb;
			$db = $wpdb;
			$table = $db->prefix . 'options';
   
			// old version settings
			// MySQL queries are not case-sensitive by default.
			// If you need to make a case-sensitive query, it is very easy to do using the BINARY operator, which forces a byte by byte comparison
			$sql = "select option_id, option_name, option_value from $table where 1=1 and option_name regexp binary '^amzStore_' order by option_name asc;";
			//var_dump('<pre>',$sql,'</pre>');  
			$res = $db->get_results( $sql, OBJECT );
			if ( empty($res) ) {
				update_option('AmzStore_fixed_dbalias', true);
				return array_merge($ret, array('status' => 'valid', 'msg' => 'error or none found: trying to retrieve old version settings.'));
			}
   
			// new version 9.0 settings
			$sql90 = "select option_name, option_value from $table where 1=1 and option_name regexp binary '^AmzStore_' order by option_name asc;";
			$res90 = $db->get_results( $sql90, OBJECT_K );
			if ( empty($res90) ) {
				//return array_merge($ret, array('status' => 'valid', 'msg' => 'error or none found: trying to retrieve new version 9.0 settings.'));
			}
			foreach ($res90 as $key => $val) {
				$res90["$key"] = $val->option_value;
			}

			$ccupd = 0; $ccdel = 0; $ccupd_old = 0;
			foreach ($res as $val) {
				$option_id 		= $val->option_id;
				$option_name 	= $val->option_name;
				$option_value 	= $val->option_value;

				// amzStore_ option names become __amzStore_
				if ( 1 ) {
					$sqlupd_old = "update $table set option_name = concat('__', option_name) where 1=1 and option_name = binary %s;";
					$sqlupd_old = $db->prepare( $sqlupd_old, $option_name );
					$resupd_old = $db->query( $sqlupd_old );
					if ( $resupd_old ) ++$ccupd_old;
				}

				$option_name_new = str_replace('amzStore', 'AmzStore', $option_name);

				$option_value = maybe_unserialize( $option_value );
				$option_value = maybe_unserialize( $option_value ); // old version prior to 9.0 had a bug of double serialize for some options
				$option_value = maybe_serialize( $option_value );

				// add new option based on old setting value
				if ( isset($res90["$option_name_new"]) ) { // already exists
					$sqlupd = "update $table set option_value = %s where 1=1 and option_name = binary %s;";
					$sqlupd = $db->prepare( $sqlupd, $option_value, $option_name_new );
					$resupd = $db->query( $sqlupd );
					if ( $resupd ) ++$ccupd;
				}
				else {
					$sqlupd = "insert into $table (option_name, option_value) values (%s, %s);";
					$sqlupd = $db->prepare( $sqlupd, $option_name_new, $option_value );
					$resupd = $db->query( $sqlupd );
					if ( $resupd ) ++$ccupd;
				}
			} // end foreach

			update_option('AmzStore_fixed_dbalias', true);
			return array_merge($ret, array('status' => 'valid', 'msg' => 'successfull: old version settings fixed.', 'count' => array(
				'ccupd'			=> $ccupd,
				'ccdel'			=> $ccdel,
				'ccupd_old'	=> $ccupd_old,
			)));
			//return $ret;
		}
	

		/**
		 * 2016-october - for product country check
		 */
		// from ADF
		public function discount_convert_country2country() {
			$countries = array(
				'com' 	=> array('us', 'com', 'united-states', 'United States'),
				'uk' 	=> array('gb', 'co.uk', 'united-kingdom', 'United Kingdom'),
				'de' 	=> array('de', 'de', 'germany', 'Germany'),
				'fr' 	=> array('fr', 'fr', 'france', 'France'),
				'jp' 	=> array('jp', 'co.jp', 'japan', 'Japan'),
				'ca' 	=> array('ca', 'ca', 'canada', 'Canada'),
				'cn'	=> array('cn', 'cn', 'china', 'China'),
				'in' 	=> array('in', 'in', 'india', 'India'),
				'it' 	=> array('it', 'it', 'italy', 'Italy'),
				'es' 	=> array('es', 'es', 'spain', 'Spain'),
				'mx' 	=> array('mx', 'com.mx', 'mexico', 'Mexico'),
				'br' 	=> array('br', 'com.br', 'brazil', 'Brazil'),
				'au' 	=> array('au', 'com.au', 'australia', 'Australia')
			);
			$ret = array('tovalues' => array(), 'totitles' => array());
			foreach ($countries as $k => $v) {
				$ret['fromip']["$k"] = $v[0];
				$ret['amzwebsite']["$k"] = $v[1];
				$ret['tovalues']["$k"] = $v[2];
				$ret['totitles']["$k"] = $v[3];
			}
			return $ret;
		}

		// build a return array of type amzForUser from a domain key
		public function domain2amzForUser( $domain ) {
			$convertCountry = $this->discount_convert_country2country();
			$country_key = 'com';
			if ( in_array($domain, $convertCountry['amzwebsite']) ) {
				$country_key = array_search($domain, $convertCountry['amzwebsite']);
			}
			$ipcountry = isset($convertCountry['fromip']["$country_key"]) ? $convertCountry['fromip']["$country_key"] : 'us';
			$ipcountry = strtoupper($ipcountry);

			$country = $this->amzForUser( $ipcountry );
			return $country;
		}
	
		public function get_aff_ids() {
			$main_aff_id = $this->main_aff_id();

			$config = $this->amz_settings;
			$aff_ids = array();
			if ( isset($config['AffiliateID']) && !empty($config['AffiliateID'])
				&& is_array($config['AffiliateID']) ) {
				foreach ( $config['AffiliateID'] as $key => $val ) {
					if ( !empty($val) ) {
						$_key = $this->get_amazon_country_site( $key );						
						$aff_ids[] = array(
							'country' 		=> $_key,
							'aff_id'			=> $val,
						);
					}
				}
			}
			return array(
				'main_aff_id'			=> $main_aff_id,
				'aff_ids'					=> $aff_ids,
			);
		}
	
		public function get_country_from_url( $url ) {
			$country = '';
			if ( empty($url) ) return $country;

			$regex = "/https?:\/\/(?:.+\.)amazon\.([^\/]*)/imu";
			$found = preg_match($regex, $url, $m);
			if ( false !== $found ) {
				$country = $m[1];
			}
			return $country;
		}
	
		public function get_amazon_images_path() {
			return self::$amazon_images_path;
		}
	
		public function delete_post_attachments( $post_id ) {
			global $wpdb, $post_type;
			
			//check if is product
			$_post_type = get_post_type($post_id); //$post_type
			if ( ! in_array($_post_type, array('product', 'product_variation')) ) return;
			if ( ! is_int( $post_id ) || $post_id <= 0 ) return;

			//$ids = get_children(array(
			//	'post_parent' => $post_id,
			//	'post_type' => 'attachment'
			//));
			//$ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_parent = $post_id AND post_type = 'attachment'");
			//if (empty($ids)) return;
			//foreach ( $ids as $id ) {
			//	wp_delete_attachment( $id, true );
			//}

			if (1) {
				$args = array(
					'post_type'   => 'attachment',
					'post_parent' => $post_id,
					'post_status' => 'any',
					'nopaging'    => true,

					// Optimize query for performance.
					'no_found_rows'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				);
				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();

						wp_delete_attachment( $query->post->ID, true );
					}
				}
				wp_reset_postdata();
			}
		}


		/**
		 * Plugin Version
		 */
		// latest code version
		public function version() {
			if ( defined('WOOZONE_VERSION') ) {
				$this->version = (string) WOOZONE_VERSION;
				return $this->version;
			}

			$path = $this->cfg['paths']['plugin_dir_path'] . 'plugin.php';
			if ( function_exists('get_plugin_data') ) {
				$plugin_data = get_plugin_data( $path );
			}
			else {
				$plugin_data = WooZone_get_plugin_data();
			}

			$latest_version = '1.0';
			if( isset($plugin_data) && is_array($plugin_data) && !empty($plugin_data) ){
				if ( isset($plugin_data['Version']) ) {
					$latest_version = (string)$plugin_data['Version'];
				}
				else if ( isset($plugin_data['version']) ) {
					$latest_version = (string)$plugin_data['version'];
				}
			}

			$this->version = $latest_version;
			return $this->version;
		}

		private function check_if_table_exists( $force=false ) {
			$need_check_tables = $this->plugin_integrity_need_verification('check_tables');
			if ( ! $need_check_tables['status'] && ! $force ) {
				return true; // don't need verification yet!
			}

			// default sql - tables & tables data!
			require_once( $this->cfg['paths']['plugin_dir_path'] . 'modules/setup_backup/default-sql.php' );

			// retrieve all database tables & clean prefix
			$dbTables = $this->db->get_results( "show tables;", OBJECT_K );
			$dbTables = array_keys( $dbTables );
			if ( empty($dbTables) || ! is_array($dbTables) ) {

				$this->plugin_integrity_update_time('check_tables', array(
					'status'		=> 'invalid',
					'html'		=> __('Check plugin tables: error requesting tables list.', $this->localizationName),
				));
				return false; //something was wrong!
			}

			$dbTables_ = array();
			foreach ((array) $dbTables as $table) {
				$table_noprefix = str_replace($this->db->prefix, '', $table);
				$dbTables_[] = $table_noprefix;
			}

			// our plugin tables
			$dbTables_own = $this->plugin_tables;
			
			// did we find all our plugin tables?
			$dbTables_found = (array) array_intersect($dbTables_, $dbTables_own);
			$dbTables_missing = array_diff($dbTables_own, $dbTables_found);
			//var_dump('<pre>', $dbTables_own, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			if ( ! $dbTables_missing ) {

				$this->plugin_integrity_update_time('check_tables', array(
					'timeout'	=> time(),
					'status'		=> 'valid',
					'html'		=> __('Check plugin tables: all installed ( ' . implode(', ', $dbTables_found) . ' ).', $this->localizationName),
				));
				return true; // all is fine!
			}

			$this->plugin_integrity_update_time('check_tables', array(
				'status'		=> 'invalid',
				'html'		=> __('Check plugin tables: missing ( ' . implode(', ', $dbTables_missing) . ' ).', $this->localizationName),
			));
			return false; //something was wrong!
		}

		private function update_db_version( $version=null ) {
			delete_option( 'WooZone_db_version' );
			$version = empty($version) ? $this->version() : $version;
			add_option( 'WooZone_db_version', $version );
		}
		
		public function update_db( $force=false ) {
			// current installed db version
			//$current_db_version = get_option( 'WooZone_db_version' );
			//$current_db_version = !empty($current_db_version) ? (string)$current_db_version : '1.0';

			// added new amazon location 'australia'
			$amazon_location_check = get_option('WooZone_amazon_location_check', array());
			$amazon_location_check = ! is_array($amazon_location_check) ? array() : $amazon_location_check;
			if ( ! in_array('com.au', $amazon_location_check) ) {
				$force = true;

				$amazon_location_check[] = 'com.au';
				$amazon_location_check = array_unique( array_filter($amazon_location_check) );
				update_option('WooZone_amazon_location_check', $amazon_location_check);
			}

			// default db structure - integrity verification is done in function
			$this->check_if_table_exists( $force );

			$this->check_table_generic( 'amz_locale_reference', $force, array() ); // update 2018-feb
			$this->check_table_generic( 'amz_amzkeys', $force, array() ); // update 2018-feb
			$this->check_table_generic( 'amz_amazon_cache', $force, array( 'must_have_rows' => false ) ); // update 2018-apr

			$need_check_cronjobs_prefix = $this->plugin_integrity_need_verification('check_cronjobs_prefix');
			$need_check_alter_tables = $this->plugin_integrity_need_verification('check_alter_tables');
			$need_check_alter_table_amz_queue = $this->plugin_integrity_need_verification('check_alter_table_amz_queue'); // added 2018-04-11

			// installed version less than 9.0 / ex. 8.4.1.3
			//if ( version_compare( $current_db_version, '9.0', '<' ) ) {
			if ( $need_check_alter_tables['status'] || $force ) { // if need_check_alter_tables
				// installed version less than 9.0 / ex. 8.4.1.3
				$table_name = $this->db->prefix . "amz_assets";
				if ( $this->db->get_var("show tables like '$table_name'") == $table_name ) {
					$this->_update_db_tables(array(
						'opt_name' 		=> 'check_alter_tables',
						'operation'		=> $table_name,
						'table'			=> $table_name,
						'queries'		=> array(
							'image_sizes'				=> array(
								'main' 			=> "ALTER TABLE " . $table_name . " %s COLUMN `image_sizes` TEXT NULL;",
								'verify'		=> "SHOW COLUMNS FROM " . $table_name . " LIKE 'image_sizes';",
								'field_name'	=> 'image_sizes',
								'field_type'	=> 'text',
							),
							'download_status'		=> array(
								'main' 			=> "ALTER TABLE " . $table_name . " %s COLUMN `download_status` VARCHAR(20) NULL DEFAULT 'new' COMMENT 'new, success, inprogress, error, remote';",
								'verify' 		=> "SHOW COLUMNS FROM " . $table_name . " LIKE 'download_status';",
								'field_name'	=> 'download_status',
								'field_type'	=> 'varchar(20)',
							),
						),
					));
				}
				
				// installed version less than 9.0.3.3
				$table_name = $this->db->prefix . "amz_cross_sell";
				if ( $this->db->get_var("show tables like '$table_name'") == $table_name ) {
					$this->_update_db_tables(array(
						'opt_name' 		=> 'check_alter_tables',
						'operation'		=> $table_name,
						'table'			=> $table_name,
						'queries'		=> array(
							'is_variable'				=> array(
								'main' 			=> "ALTER TABLE " . $table_name . " %s COLUMN `is_variable` CHAR(1) NULL DEFAULT 'N';",
								'verify'		=> "SHOW COLUMNS FROM " . $table_name . " LIKE 'is_variable';",
								'field_name'	=> 'is_variable',
								'field_type'	=> 'char(1)',
							),
							'nb_tries'		=> array(
								'main' 			=> "ALTER TABLE " . $table_name . " %s COLUMN `nb_tries` TINYINT(1) UNSIGNED NULL DEFAULT '0';",
								'verify' 			=> "SHOW COLUMNS FROM " . $table_name . " LIKE 'nb_tries';",
								'field_name'	=> 'nb_tries',
								'field_type'	=> 'tinyint(1)',
							),
						),
					));
				}

				// occures with some clients servers
				$table_name = $this->db->prefix . "amz_queue";
				if ( $this->db->get_var("show tables like '$table_name'") == $table_name ) {
					$this->_update_db_tables(array(
						'opt_name' 		=> 'check_alter_tables',
						'operation'		=> $table_name,
						'table'			=> $table_name,
						'queries'		=> array(
							'nb_tries'		=> array(
								'main' 			=> "ALTER TABLE " . $table_name . " %s COLUMN `nb_tries` SMALLINT(1) UNSIGNED NOT NULL;",
								'verify' 		=> "SHOW COLUMNS FROM " . $table_name . " LIKE 'nb_tries';",
								'field_name'	=> 'nb_tries',
								'field_type'	=> 'smallint(1)',
							),
							'nb_tries_prev'	=> array(
								'main' 			=> "ALTER TABLE " . $table_name . " %s COLUMN `nb_tries_prev` SMALLINT(1) UNSIGNED NOT NULL;",
								'verify' 		=> "SHOW COLUMNS FROM " . $table_name . " LIKE 'nb_tries_prev';",
								'field_name'	=> 'nb_tries_prev',
								'field_type'	=> 'smallint(1)',
							),
							'from_op'		=> array(
								'main' 			=> "ALTER TABLE " . $table_name . " %s COLUMN `from_op` VARCHAR(30) NOT NULL;",
								'verify' 		=> "SHOW COLUMNS FROM " . $table_name . " LIKE 'from_op';",
								'field_name'	=> 'from_op',
								'field_type'	=> 'varchar(30)',
							),
						),
					));
				}

			} // end if need_check_alter_tables

			// added 2018-04-11
			if ( $need_check_alter_table_amz_queue['status'] || $force ) { // if need_check_alter_tables

				$table_name = $this->db->prefix . "amz_queue";
				if ( $this->db->get_var("show tables like '$table_name'") == $table_name ) {
					$this->_update_db_tables(array(
						'opt_name' 		=> 'check_alter_table_amz_queue',
						'operation'		=> $table_name,
						'table'			=> $table_name,
						'queries'		=> array(
							// columns
							'product_title'		=> array(
								'main' 			=> "ALTER TABLE " . $table_name . " %s COLUMN `product_title` TEXT NULL;",
								'verify' 		=> "SHOW COLUMNS FROM " . $table_name . " LIKE 'product_title';",
								'field_name'	=> 'product_title',
								'field_type'	=> 'text',
							),
							'country'	=> array(
								'main' 			=> "ALTER TABLE " . $table_name . " %s COLUMN `country` VARCHAR(10) NOT NULL DEFAULT '';",
								'verify'		=> "SHOW COLUMNS FROM " . $table_name . " LIKE 'country';",
								'field_name'	=> 'country',
								'field_type'	=> 'varchar(10)',
							),
						),
						// !!!must be after queries to be sure that all columns exists!
						// index_name, index_type, index_cols: all are mandatory
						'indexes'		=> array(
							'country'			=> array(
								'main' 			=> "ALTER TABLE " . $table_name . " %s (`country`);",
								'verify'		=> "SHOW INDEX FROM " . $table_name . " WHERE 1=1 and Key_name LIKE 'country';",
								'index_name'	=> 'country',
								'index_type'	=> 'key',
								'index_cols'	=> array('country'),
							),
						),
					));
				}

			} // end if need_check_alter_tables

			// installed version less than 9.0 / ex. 8.4.1.3
			// update cronjobs prefix in wp_options / option name like 'cron'
			if ( $need_check_cronjobs_prefix['status'] || $force ) {
				$this->update_cronjobs();

				$this->plugin_integrity_update_time('check_cronjobs_prefix', array(
					'timeout'	=> time(),
					'status'		=> 'valid',
					'html'		=> __('Check cronjobs prefix: OK.', $this->localizationName),
				));
			}

			// installed version less than 9.0 / ex. 8.4.1.3
			$this->update_db_version('9.0');

			// update installed version to latest
			$this->update_db_version();
			return true;
		}

		public function _update_db_tables( $pms=array() )  {
			//require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			//$status = dbDelta($sql);

			extract( $pms );

			global $wpdb;
			// queries columns
			foreach ( (array) $queries as $skey => $sql ) {
				if ( ! isset($sql['main']) ) continue 1;

				$do_main = 'add';
				if ( isset($sql['verify']) ) {
					$status = $wpdb->get_row( $sql['verify'], ARRAY_A );
					if ( ! empty($status) && isset($status['Field'], $status['Type']) ) {

						//'image_sizes' == strtolower($status['Field'])
						if ( isset($sql['field_type']) ) {
							if ( strtolower($sql['field_type']) == strtolower( $status['Type'] ) )
								$do_main = false;
							else
								$do_main = 'modify';
						}
					}
				} // end if verify

				if ( !empty($do_main) ) {
					$sql['main'] = sprintf( $sql['main'], strtoupper( $do_main ) );
					$status = $wpdb->query( $sql['main'] );
					//var_dump('<pre>', $sql, $status, '</pre>');
				}
			} // end foreach

			// queries indexes
			//ADD KEY newkeyname | DROP KEY oldkeyname, ADD KEY newkeyname
			if ( isset($indexes) ) { foreach ( (array) $indexes as $skey => $sql ) {
				if ( ! isset($sql['main']) ) continue 1;

				$index_name = isset($sql['index_name']) ? $sql['index_name'] : $skey;
				$index_type = isset($sql['index_type']) ? $sql['index_type'] : 'key';
				$index_cols = isset($sql['index_cols']) ? $sql['index_cols'] : array();

				$do_main = 'add';
				if ( isset($sql['verify']) ) {
					$status = $wpdb->get_results( $sql['verify'], ARRAY_A );

					$cols = array();
					if ( ! empty($status) ) {
						foreach ($status as $idxKey => $idxVal) {
							$cols[] = $idxVal['Column_name'];
						}
						$cols = array_unique( array_filter( $cols) );
						$diff = array_diff($index_cols, $cols);

						if ( ! empty($diff) )
							$do_main = 'modify';
						else
							$do_main = false;
					}
				} // end if verify

				if ( !empty($do_main) ) {
					$do_main2 = array();
					if ( 'modify' == $do_main ) {
						$do_main2[] = 'DROP ' . strtoupper($index_type) . ' ' . $index_name;
					}
					$do_main2[] = 'ADD ' . strtoupper($index_type) . ' ' . $index_name;
					$do_main = implode(', ', $do_main2);

					$sql['main'] = sprintf( $sql['main'], $do_main );
					$status = $wpdb->query( $sql['main'] );
					//var_dump('<pre>', $sql, $status, '</pre>');
				}
			} } // end foreach & if

			//if ( $this->db->prefix . "psp_link_redirect" == $operation ) {
			//	echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			//}

			$this->plugin_integrity_update_time($opt_name, array(
				'timeout'	=> time(),
				'status'		=> 'valid',
				'html'		=> __('Check plugin tables (alter): OK.', $this->localizationName),
			));
		}

		public function update_options_prefix( $what='use_old' ) {
			$ret = array('status' => 'invalid', 'msg' => 'unknown msg.');
   
			$db = $this->db;
			$table = $db->prefix . 'options';

			if ( 'use_new' == $what ) {
				return array_merge($ret, array('status' => 'valid', 'msg' => 'successfull: you choose to use the new version settings, disregarding the old version settings.'));
			}
			else if ( 'use_old' == $what ) {

				// old version settings
				$sql = "select option_id, option_name, option_value from $table where 1=1 and option_name regexp '^wwcAmzAff' order by option_name asc;";
				$res = $db->get_results( $sql, OBJECT );
				if ( empty($res) ) {
					return array_merge($ret, array('status' => 'valid', 'msg' => 'error or none found: trying to retrieve old version settings.'));
				}

				// new version 9.0 settings
				$sql90 = "select option_name, option_value from $table where 1=1 and option_name regexp '^WooZone' order by option_name asc;";
				$res90 = $db->get_results( $sql90, OBJECT_K );
				if ( empty($res90) ) {
					return array_merge($ret, array('status' => 'valid', 'msg' => 'error or none found: trying to retrieve new version 9.0 settings.'));
				}
				foreach ($res90 as $key => $val) {
						$res90["$key"] = $val->option_value;
				}
	
				$ccupd = 0; $ccdel = 0;
				foreach ($res as $val) {
					$option_id 		= $val->option_id;
					$option_name 	= $val->option_name;
					$option_value 	= $val->option_value;
					
					$option_name_new = str_replace('wwcAmzAff', $this->alias, $option_name);
					
					// delete current new version setting if exist
					//$sqldel = "delete from $table where 1=1 and option_name = %s;";
					//$sqldel = $db->prepare( $sqldel, $option_name_new );
					//$resdel = $db->query( $sqldel );
					//if ( $resdel ) ++$ccdel;
					
					$option_value = maybe_unserialize( $option_value );
					$option_value = maybe_unserialize( $option_value ); // old version prior to 9.0 had a bug of double serialize for some options
					$option_value = maybe_serialize( $option_value );

					// add new option based on old setting value
					if ( isset($res90["$option_name_new"]) ) { // already exists
						$sqlupd = "update $table set option_value = %s where 1=1 and option_name = %s;";
						$sqlupd = $db->prepare( $sqlupd, $option_value, $option_name_new );
						$resupd = $db->query( $sqlupd );
						if ( $resupd ) ++$ccupd;
					}
					else {
						$sqlupd = "insert into $table (option_name, option_value) values (%s, %s);";
						$sqlupd = $db->prepare( $sqlupd, $option_name_new, $option_value );
						$resupd = $db->query( $sqlupd );
						if ( $resupd ) ++$ccupd;
					}
   
					// replace new version setting with old version setting
					// !!! THIS WOULD REPLACE OLD VERSION SETTINGS - MAYBE WE SHOULD KEEP OLD VERSION SETTINGS FOR NOW
					//$sqlupd = "update $table set option_name = %s where 1=1 and option_id = %s;";
					//$sqlupd = $db->prepare( $sqlupd, $option_name_new, $option_id );
					//$resupd = $db->query( $sqlupd );
					//if ( $resupd ) ++$ccupd;
				}
				return array_merge($ret, array('status' => 'valid', 'msg' => 'successfull: you choose to use the old version settings, the new version settings were replaced.'));
			}
			return $ret;
		}

		public function update_cronjobs() {
			$ret = array('status' => 'invalid', 'msg' => 'unknown msg.');

			$db = $this->db;
			$table = $db->prefix . 'options';

			$sql = "SELECT option_id, option_name, option_value FROM $table WHERE 1=1 and option_name = 'cron';";
			$res = $db->get_results( $sql, OBJECT );
			if ( empty($res) ) {
				return array_merge($ret, array('status' => 'valid', 'msg' => 'not found'));
			}
 
			foreach ($res as $val) {
				$option_id 		= $val->option_id;
				$option_name 	= $val->option_name;
				$option_value 	= $val->option_value;

				$option_value = maybe_unserialize( $option_value );
				if ( empty($option_value) || !is_array($option_value) ) continue 1;
 
				foreach ($option_value as $kk => $vv) {
					if ( !is_array($vv) ) continue 1;  
					foreach ($vv as $kk2 => $vv2) {
						if ( preg_match('/^wwcAmzAff/iu', $kk2) ) { // wwcAmzAff | WooZone
							unset($option_value["$kk"]["$kk2"]);
						}
					}
				}
				
				foreach ($option_value as $kk => $vv) {
					if ( empty($vv) ) {
						unset($option_value["$kk"]);
					}
				}
				
				$option_value = serialize( $option_value );

				$sqlupd = "update $table set option_value = %s where 1=1 and option_id = %s;";
				$sqlupd = $db->prepare( $sqlupd, $option_value, $option_id );
				$resupd = $db->query( $sqlupd );
			}
			
			//$sql = "SELECT option_id, option_name, option_value FROM $table WHERE 1=1 and option_name = 'cron';";
			//$res = $db->get_results( $sql, OBJECT );
			//var_dump('<pre>', $res, '</pre>'); die('debug...'); 
			
			return array_merge($ret, array('status' => 'valid', 'msg' => 'ok'));
		}


		/** 
		 * Plugin is ACTIVE
		 */
		// verify plugin is ACTIVE (the right way)
		public function is_plugin_active( $plugin_name, $pms=array() ) {
			$pms = array_replace_recursive(array(
				'verify_active_for_network_only'		=> false,
				'verify_network_only_plugin'			=> false,
				'plugin_file'							=> array(), // verification is made by OR between items
				'plugin_class'							=> array(), // verification is made by OR between items
			), $pms);
			extract( $pms );

			switch ( strtolower($plugin_name) ) {
				case 'woocommerce':
					$plugin_file = array( 'woocommerce/woocommerce.php', 'envato-wordpress-toolkit/woocommerce.php' );
					$plugin_class = array( 'WooCommerce' );
					break;

				case 'woozone':
					$plugin_file = array( 'woozone/plugin.php' );
					$plugin_class = array( 'WooZone' );
					break;

				case 'psp':
					$plugin_file = array( 'premium-seo-pack/plugin.php' );
					$plugin_class = array( 'psp' );
					break;

				case 'w3totalcache':
					$plugin_file = array( 'w3-total-cache/w3-total-cache.php' );
					break;

				// Additional Variation Images Plugin for WooCommerce
				case 'avi':
					$plugin_file = array( 'additional-variation-images/plugin.php' );
					$plugin_class = array( 'AVI' );
					break;

				default:
					break;
			}

			$is_active = array();

			// verify plugin is active base on plugin main file 
			if ( ! empty($plugin_file) ) {
				if ( ! is_array($plugin_file) )
					$plugin_file = array( $plugin_file );

				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

				$cc = false;
				foreach ($plugin_file as $_plugin_file) {
					// check if a plugin is site wide or network active only
					if ( $verify_active_for_network_only ) {
						if ( is_plugin_active_for_network( $_plugin_file ) )
							$cc = true;
					}
					// check if a plugin is a Network-Only-Plugin
					else if ( $verify_network_only_plugin ) {
						if ( is_network_only_plugin( $_plugin_file ) )
							$cc = true;
					}
					// check if a plugin is active (the right way)
					else {
						if ( is_plugin_active( $_plugin_file ) )
							$cc = true;
					}
				}
				$is_active[] = $cc;
			}

			// verify plugin class exists!
			if ( ! empty($plugin_class) ) {
				if ( ! is_array($plugin_class) )
					$plugin_class = array( $plugin_class );

				$cc = false;
				foreach ($plugin_class as $_plugin_class) {
					if ( class_exists( $_plugin_class ) )
						$cc = true;
				}
				$is_active[] = $cc;
			}

			// final verification
			if ( empty($is_active) ) return false;
			foreach ($is_active as $_is_active) {
				if ( ! $_is_active ) return false;
			}
			return true;
		}
		public function is_plugin_active_for_network_only( $plugin_name, $pms=array() ) {
			$pms = array_replace_recursive(array(
				'verify_active_for_network_only'		=> true,
			), $pms);
			return $this->is_plugin_active( $plugin_name, $pms );
		}
		public function is_plugin_network_only_plugin( $plugin_name, $pms=array() ) {
			$pms = array_replace_recursive(array(
				'verify_network_only_plugin'				=> true,
			), $pms);
			return $this->is_plugin_active( $plugin_name, $pms );
		}
		
		public function is_woocommerce_installed() {
			if ( in_array( 'envato-wordpress-toolkit/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_multisite() )
			{
				return true;
			} else {
				$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
				if ( !empty($active_plugins) && is_array($active_plugins) ) {
					foreach ( $active_plugins as $key => $val ) {
						if ( ($status = preg_match('/^woocommerce[^\/]*\/woocommerce\.php$/imu', $val))!==false && $status > 0 ) {
							return true;
						}
					}
				}
				return false;
			}
		}


		/**
		 * check plugin integrity: 2017-feb-28
		 */
		// what: check_database (includes: check_tables, check_alter_tables, check_cronjobs_prefix)
		public function plugin_integrity_check( $what='all', $force=false ) {
			$what = ! is_array($what) ? array('check_database') : $what;

			if ( in_array('check_database', $what) ) {
				$this->update_db( $force );
			}
		}

		public function plugin_integrity_get_last_status( $what ) {
			$ret = array(
				'status'				=> true,
				'html'				=> '',
			);

			// verify plugin integrity
			$plugin_integrity = get_option( 'WooZone_integrity_check', array() );
			$plugin_integrity = is_array($plugin_integrity) ? $plugin_integrity : array();

			$_status = true; $_html = array();
			if ( isset($plugin_integrity[ "$what" ]) && ! empty($plugin_integrity[ "$what" ]) ) {
				$__ = $plugin_integrity[ "$what" ];
				$_status = isset($__['status']) && 'valid' == $__['status'] ? true : false;
				$_html[] = $__['html'];
			}
			else {
				if ( 'check_database' == $what ) {
					foreach ($plugin_integrity as $key => $val) {
						if ( ! in_array($key, array('check_tables', 'check_alter_tables', 'check_cronjobs_prefix', 'check_table_amz_locale_reference', 'check_table_amz_amzkeys')) ) {
							continue 1;
						}

						$_status = $_status && ( isset($val['status']) && 'valid' == $val['status'] ? true : false );
						if ( ! empty($val['html']) ) {
							$_html[] = $val['html'];
						}
					}
				}
			}

			//$html = '<div><div>' . implode('</div><div>', $_html) . '</div></div>';
			$html = implode('&nbsp;&nbsp;&nbsp;&nbsp;', $_html);
			$ret = array_merge( $ret, array('status' => $_status, 'html' => $html) );
			return $ret;
		}

		// what: check_tables, check_alter_tables, check_cronjobs_prefix
		public function plugin_integrity_need_verification( $what ) {
			$ret = array(
				'status'			=> false,
				'data'				=> array(),
			);

			// verify plugin integrity
			$plugin_integrity = get_option( 'WooZone_integrity_check', array() );
			$plugin_integrity = is_array($plugin_integrity) ? $plugin_integrity : array();
			$ret = array_merge( $ret, array('data' => $plugin_integrity) );

			if ( isset($plugin_integrity[ "$what" ]) && ! empty($plugin_integrity[ "$what" ]) ) {
				if ( ( $plugin_integrity[ "$what" ]['timeout'] + $this->ss['check_integrity'][ "$what" ] ) > time() ) {
					$ret = array_merge( $ret, array('status' => false) ); // don't need verification yet!
					//var_dump('<pre>',$ret,'</pre>'); 
					return $ret;
				}
			}

			$ret = array_merge( $ret, array('status' => true) );
			return $ret;
		}

		public function plugin_integrity_update_time( $what, $data=array() ) {
			$plugin_integrity = get_option( 'WooZone_integrity_check', array() );
			$plugin_integrity = is_array($plugin_integrity) ? $plugin_integrity : array();

			$data = ! is_array($data) ? array() : $data;

			if ( ! isset($plugin_integrity[ "$what" ]) ) {
				$plugin_integrity[ "$what" ] = array(
					'timeout'	=> time(),
					'status'		=> 'invalid',
					'html'		=> '',
				);
			}
			$plugin_integrity[ "$what" ] = array_replace_recursive($plugin_integrity[ "$what" ], $data);
			update_option( 'WooZone_integrity_check', $plugin_integrity );
		}
		
		public function woocommerce_image_replace_src( $html='' ) {
			//return str_replace( "http", "http__", $html);
			return $html;
		}
		public function woocommerce_image_replace_src_revert( $html='' ) {
			//return str_replace( "http__", "http", $html);
			return $html;
		}
	
		public function is_debug_mode_allowed() {
			$ip = $this->get_client_ip();

			$debug_ip = isset($this->amz_settings['debug_ip']) && ! empty($this->amz_settings['debug_ip'])
				? trim($this->amz_settings['debug_ip']) : '';
			if ( ! empty($debug_ip) ) {
				$debug_ip = explode(',', $debug_ip);
				$debug_ip = array_map("trim", $debug_ip);

				if ( in_array($ip, $debug_ip) ) {
					return true;
				}
			}
			return false;
		}
	
		public function debug_cache_images() {
			if ( $this->is_debug_mode_allowed() ) {
				$html = array();
				$html[] = '<div style="background-color: #3498db; color: #fff; position: fixed; bottom: 25px; right: 25px; max-width: 200px; font-size: 10px;">';
				$html[] = 		'<table style="border-spacing: 2px; margin: 0px; border: 0px;">';
				$html[] = 			'<thead style="line-height: 10px;">';
				$html[] =				'<tr>';
				if ( isset($_SESSION['WooZoneCachedContor']) ) {
					foreach ($_SESSION['WooZoneCachedContor'] as $key => $val) {
						$html[] = 				'<th>' . str_replace('_', ' ', $key) . '</th>';
					}
				}
				$html[] =				'</tr>';
				$html[] = 			'</thead>';
				$html[] = 			'<tbody style="line-height: 10px;">';
				$html[] = 				'<tr>';
				if ( isset($_SESSION['WooZoneCachedContor']) ) {
					foreach ($_SESSION['WooZoneCachedContor'] as $key => $val) {
						$html[] = 				'<td>' . $val . '</td>';
					}
				}
				$html[] = 				'</tr>';
				$html[] = 			'</tbody>';
				$html[] = 		'</table>';
				$html[] = '</div>';

				$html = implode(PHP_EOL, $html);
				return $html;
			}
			return false;
		}
	

		/**
		 * 
		 * Cacheit related - 2017-08-16
		 */
		public function _attachment_url( $url='', $post_id=0 ) 
		{
			$orig_url = $url;

			if( in_array( $orig_url, array_keys($this->duplicate_images) ) ){
				if( isset($this->duplicate_images[$orig_url]) ){
					return $this->duplicate_images[$orig_url];
				}
			}

			// mandatory - must be amazon product
			$post = get_post($post_id);
   
			if ( isset($post->post_parent) && $post->post_parent
				&& $this->verify_product_is_amazon_valid($post->post_parent) === 0
			) {
				return $url;
			}

			// mandatory rule - must have amazon url
			$rules = array();
			$rules[0] = strpos( $url, self::$amazon_images_path );
			$rules = $rules[0];

			if ( $rules ) {
				$uploads = wp_get_upload_dir();
				$url = str_replace( $uploads['baseurl'] . '/', '', $url );
	
				if( $this->is_ssl() == true ) {
					$uploads['baseurl'] = str_replace( 'http://', 'https://', $uploads['baseurl']);  
					$url = str_replace( $uploads['baseurl'] . '/', '', $url );
				}
			}
			$url = $this->amazon_url_to_ssl( $url );
			if ( ! is_admin() ) {
				$url = $this->woocommerce_image_replace_src( $url );
			}


			$this->duplicate_images[$orig_url] = $url;
			//var_dump( "<pre>", $this->duplicate_images  , "</pre>" ); 
			return $url;
		}
		
		public function _attachment_url__( $url='', $post_id=0 ) {
			
			return $this->_attachment_url( $url, $post_id );
			// END HERE - CODE BELLOW NOT EXECUTED

			$uniqueid = md5( $post_id . $url );
			$thecache = $this->cacheit['imgurl']->get_row($uniqueid);

			if ( isset($thecache['v']) ) {
				$this->cacheit['imgurl']->add_row($uniqueid, array(
					//'hitsc' 			=> isset($thecache['hitsc']) ? ($thecache['hitsc'] + 1) : 1,
				));
				$_SESSION['WooZoneCachedContor']['hitscache']++;
				return $thecache['v'];
			}

			$this->cacheit['imgurl']->add_row($uniqueid, array(
				//'hits' 				=> isset($thecache['hits']) ? ($thecache['hits'] + 1) : 1,
				//'post_id'		=> $post_id,
				//'url'				=> $url,
				'v' => $url,
			));

			// mandatory - must be amazon product
			$post = get_post($post_id);

			$this->cacheit['imgurl']->add_row($uniqueid, array(
				//'post_parent'		=> $post->post_parent,
			));

			if ( isset($post->post_parent) && $post->post_parent
				&& $this->verify_product_is_amazon_valid($post->post_parent) === 0
			) {
				//$this->cacheit['imgurl']->save_cache(); // NON amazon product => don't save it to cache
				$this->cacheit['imgurl']->del_row($uniqueid);
				$_SESSION['WooZoneCachedContor']['nonamazon']++;
				return $url;
			}

			// mandatory rule - must have amazon url
			$rules = array();
			$rules[0] = strpos( $url, self::$amazon_images_path );
			$rules = $rules[0];

			if ( $rules ) {
				$uploads = wp_get_upload_dir();
				$url = str_replace( $uploads['baseurl'] . '/', '', $url );
				if( $this->is_ssl() == true ) {
					$uploads['baseurl'] = str_replace( 'http://', 'https://', $uploads['baseurl']);  
					$url = str_replace( $uploads['baseurl'] . '/', '', $url );
				}
			}
			$url = $this->amazon_url_to_ssl( $url );
			if ( ! is_admin() ) {
				$url = $this->woocommerce_image_replace_src( $url );
			}

			$this->cacheit['imgurl']->add_row($uniqueid, array(
				//'url'				=> $url,
				'v' => $url,
			));
			$this->cacheit['imgurl']->save_cache();
			$_SESSION['WooZoneCachedContor']['hits']++;

			//var_dump('<pre>',$url,'</pre>');
			return $url;
		}

		public function _calculate_image_srcset( $sources=array(), $size_array=array(), $image_src='', $image_meta=array(), $attachment_id=0 ) {

			if ( empty($sources) ) return $sources;

			// mandatory - must be amazon product
			$post = get_post($attachment_id);
			
			if ( isset($post->post_parent) && $post->post_parent
				&& $this->verify_product_is_amazon_valid($post->post_parent) === 0
			) {
				return $sources;
			}

			$uploads = wp_get_upload_dir();
			foreach ( $sources as &$source ) {
				// mandatory rule - must have amazon url
				$rules = array();
				$rules[0] = strpos( $source['url'], self::$amazon_images_path );
				$rules = $rules[0];

				if ( $rules ) {
					$source['url'] = str_replace( $uploads['baseurl'] . '/', '', $source['url'] );
				}
				$source['url'] = $this->amazon_url_to_ssl( $source['url'] );
				if ( ! is_admin() ) {
					$source['url'] = $this->woocommerce_image_replace_src( $source['url'] );
				}
			}

			//var_dump('<pre>',$sources,'</pre>');  
			return $sources;
		}

		public function _calculate_image_srcset__( $sources=array(), $size_array=array(), $image_src='', $image_meta=array(), $attachment_id=0 ) {

			return $this->_calculate_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id );
			// END HERE - CODE BELLOW NOT EXECUTED

			if ( empty($sources) ) return $sources;

			$uniqueid = md5( $attachment_id . serialize($sources) );
			$thecache = $this->cacheit['imgsources']->get_row($uniqueid);
			
			if ( isset($thecache['v']) ) {
				$this->cacheit['imgsources']->add_row($uniqueid, array(
					//'hitsc' 			=> isset($thecache['hitsc']) ? ($thecache['hitsc'] + 1) : 1,
				));
				$_SESSION['WooZoneCachedContor']['hitscache']++;
				return $thecache['v'];
			}

			$this->cacheit['imgsources']->add_row($uniqueid, array(
				//'hits' 					=> isset($thecache['hits']) ? ($thecache['hits'] + 1) : 1,
				//'attachment_id'	=> $attachment_id,
				//'sources'			=> $sources,
				'v' => $sources,
			));

			// mandatory - must be amazon product
			$post = get_post($attachment_id);
			
			$this->cacheit['imgsources']->add_row($uniqueid, array(
				//'post_parent'		=> $post->post_parent,
			));

			if ( isset($post->post_parent) && $post->post_parent
				&& $this->verify_product_is_amazon_valid($post->post_parent) === 0
			) {
				//$this->cacheit['imgsources']->save_cache(); // NON amazon product => don't save it to cache
				$this->cacheit['imgsources']->del_row($uniqueid);
				$_SESSION['WooZoneCachedContor']['nonamazon']++;
				return $sources;
			}

			$uploads = wp_get_upload_dir();
			foreach ( $sources as &$source ) {
				// mandatory rule - must have amazon url
				$rules = array();
				$rules[0] = strpos( $source['url'], self::$amazon_images_path );
				$rules = $rules[0];

				if ( $rules ) {
					$source['url'] = str_replace( $uploads['baseurl'] . '/', '', $source['url'] );
				}
				$source['url'] = $this->amazon_url_to_ssl( $source['url'] );
				if ( ! is_admin() ) {
					$source['url'] = $this->woocommerce_image_replace_src( $source['url'] );
				}
			}

			$this->cacheit['imgsources']->add_row($uniqueid, array(
				//'sources'			=> $sources,
				'v' => $sources,
			));
			$this->cacheit['imgsources']->save_cache();
			$_SESSION['WooZoneCachedContor']['hits']++;

			//var_dump('<pre>',$sources,'</pre>');  
			return $sources;
		}

		public function verify_product_is_amazon__($prod_id) {
			// verify we are in woocommerce product
			if ( is_object($prod_id) ) {
				$product = $prod_id;
			} else if( function_exists('wc_get_product') ){
				$product = wc_get_product( $prod_id );
			} else if( function_exists('get_product') ){
				$product = get_product( $prod_id );
			}

			if ( isset($product) && is_object($product) ) {
				$prod_id = 0;
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}

				if ( $prod_id ) {
					// verify is amazon product!
					$asin = get_post_meta($prod_id, '_amzASIN', true);
					
					if ( $asin!==false && strlen($asin) > 0 ) {
						return 1;
					}
					return 0;
				}
			}
			return false;
		}

		public function verify_product_is_amazon($prod_id) {

			return $this->verify_product_is_amazon__($prod_id);
			// END HERE - CODE BELLOW NOT EXECUTED

			// verify we are in woocommerce product
			if ( is_object($prod_id) ) {
				$product = $prod_id;
			} else if( function_exists('wc_get_product') ){
				$product = wc_get_product( $prod_id );
			} else if( function_exists('get_product') ){
				$product = get_product( $prod_id );
			}

			if ( isset($product) && is_object($product) ) {
				$prod_id = 0;
				if ( method_exists( $product, 'get_id' ) ) {
					$prod_id = (int) $product->get_id();
				} else if ( isset($product->id) && (int) $product->id > 0 ) {
					$prod_id = (int) $product->id;
				}

				if ( $prod_id ) {

					$thecache = $this->cacheit['amzvalid']->get_row($prod_id);
					
					if ( isset($thecache['v']) ) {
						$this->cacheit['amzvalid']->add_row($prod_id, array(
							//'hitsc' 			=> isset($thecache['hitsc']) ? ($thecache['hitsc'] + 1) : 1,
						));
						$_SESSION['WooZoneCachedContor']['hitscache']++;
						return $thecache['v'];
					}
		
					$this->cacheit['amzvalid']->add_row($prod_id, array(
						//'hits' 				=> isset($thecache['hits']) ? ($thecache['hits'] + 1) : 1,
						//'post_id'		=> $prod_id,
					));

					// verify is amazon product!
					$asin = get_post_meta($prod_id, '_amzASIN', true);

					if ( $asin!==false && strlen($asin) > 0 ) {
						$this->cacheit['amzvalid']->add_row($prod_id, array(
							//'isvalid' 			=> 1,
							//'asin'				=> $asin,
							'v' => 1,
						));
						$this->cacheit['amzvalid']->save_cache();
						$_SESSION['WooZoneCachedContor']['hits']++;
						return 1;
					}

					$this->cacheit['amzvalid']->add_row($prod_id, array(
						//'isvalid' 			=> 0,
						//'asin'				=> $asin,
						'v' => 0,
					));
					$this->cacheit['amzvalid']->save_cache();
					$_SESSION['WooZoneCachedContor']['hits']++;
					return 0;
				}
			}
			return false;
		}

		public function translatable_strings()
		{
			if( isset($this->amz_settings) && count($this->amz_settings) > 0 ){
				if( isset($this->amz_settings['string_trans']) && count($this->amz_settings['string_trans']) > 0 ){
					$cc = 0;
					foreach ($this->expressions as $key => $value) {
						if( isset($this->amz_settings['string_trans'][$cc]) ){
							$this->expressions[$key] = $this->amz_settings['string_trans'][$cc];
						}

						$cc++;
					}
				}
			}
		}

		public function _translate_string( $string='' )
		{
			if( count($this->expressions) > 0 ){
				if( in_array( $string, array_keys($this->expressions)) ){
					return $this->expressions[$string];
				}
			}

			return $string;
		}

		// update 2017-nov
		public function get_country2mainaffid( $country, $pms=array() ) {
			$pms = array_replace_recursive(array(
				'country2mainaffid' => true, // true = country to mainaffid OR false = mainaffid to country
				'com2us' 			=> true,
				'toupper' 			=> true,
				'uk2gb' 			=> false,
			), $pms);
			extract( $pms );

			$ret = '';

			if ( ! isset($country) || empty($country) ) {
				return $ret;
			}

			$arr = $country2mainaffid ? $this->country2mainaffid : array_flip( $this->country2mainaffid );
			if ( isset($arr["$country"]) ) {
				$ret = $arr["$country"];
			}

			if ( $com2us && ('com' == $ret) ) {
				$ret = 'us';
			}
			if ( $uk2gb && ('co.uk' == $ret) ) {
				$ret = 'gb';
			}

			if ( $toupper ) {
				$ret = strtoupper( $ret );
			}
			return $ret;
		}

		// update 2017-nov
		public function get_mainaffid2country( $mainaffid, $pms=array() ) {
			$pms = array_replace_recursive(array(
				'country2mainaffid' => false, // true = country to mainaffid OR false = mainaffid to country
				'com2us' 			=> false,
				'toupper' 			=> false,
				'withPrefixPoint' 	=> false,
			), $pms);
			extract( $pms );

			$ret = $this->get_country2mainaffid( $mainaffid, $pms );

			if ( $withPrefixPoint && ! empty($ret) ) {
				$ret = '.' . $ret;
			}
			return $ret;
		}

		public function init_plugin_attributes() {
			// product type
			$this->p_type = 
				isset($this->amz_settings['onsite_cart'])
				&& $this->amz_settings['onsite_cart'] == "no"
				? 'external' : 'simple';

			// make products without an offerlistingid as external
			$this->product_offerlistingid_missing_external = isset($this->amz_settings['product_offerlistingid_missing_external'])
				&& ( $this->amz_settings['product_offerlistingid_missing_external'] == 'yes' )
				? true : false;

			// ( delete | put in trash ) products (or variations childs) when syncing them
			$this->product_offerlistingid_missing_delete = isset($this->amz_settings['product_offerlistingid_missing_delete'])
				&& ( $this->amz_settings['product_offerlistingid_missing_delete'] == 'yes' )
				? true : false;

			// import amazon product missing offerlistingid
			$this->import_product_offerlistingid_missing = ! isset($this->amz_settings["import_product_offerlistingid_missing"])
				|| ( $this->amz_settings["import_product_offerlistingid_missing"] == 'yes' )
				? true : false;

			// import amazon product variation childs missing offerlistingid
			$this->import_product_variation_offerlistingid_missing = ! isset($this->amz_settings["import_product_variation_offerlistingid_missing"])
				|| ( $this->amz_settings["import_product_variation_offerlistingid_missing"] == 'yes' )
				? true : false;

			// product buy url is the original amazon url!
			$this->product_buy_is_amazon_url = 
				!isset($this->amz_settings['product_buy_is_amazon_url'])
				|| (
					isset($this->amz_settings['product_buy_is_amazon_url'])
					&& $this->amz_settings['product_buy_is_amazon_url'] == 'yes'
				)
				? true : false;

			// get & show product short url (from bitly api)
			$this->product_url_short = 
				isset($this->amz_settings['product_url_short'])
				&& $this->amz_settings['product_url_short'] == 'yes'
				? true : false;

			// disable amazon checkout?
			$this->disable_amazon_checkout = isset($this->amz_settings['disable_amazon_checkout'])
				&& 'yes' == $this->amz_settings['disable_amazon_checkout'] ? true : false;

			// remote amazon images
			$is_ari = !isset($this->amz_settings['remote_amazon_images'])
				|| 'yes' == $this->amz_settings['remote_amazon_images'] ? true : false;
			//$is_ari = $is_ari && 'gimi' == $this->dev ? $is_ari : false; //IN DEVELOPMENT!
			//$is_ari = false; //DE-ACTIVATE!
			$this->is_remote_images = $is_ari;

			// product: delete | move to trash - ( when syncing or | delete zero priced bug fix)
			$this->products_force_delete = 
				isset($this->amz_settings['products_force_delete'])
				&& $this->amz_settings['products_force_delete'] == 'yes'
				? true : false;

			// activate debugbar
			$this->debug_bar_activate = 
				isset($this->amz_settings['debug_bar_activate'])
				&& $this->amz_settings['debug_bar_activate'] == 'no'
				? false : true;

			// gdpr - 25 may 2018
			$this->gdpr_rules_is_activated = 
				isset($this->amz_settings['gdpr_rules_is_activated'])
				? (string) $this->amz_settings['gdpr_rules_is_activated'] : 'no';

			$this->frontend_hide_onsale_default_badge = 
				isset($this->amz_settings['frontend_hide_onsale_default_badge'])
				? (string) $this->amz_settings['frontend_hide_onsale_default_badge'] : 'no';

			$this->frontend_show_free_shipping =
				isset($this->amz_settings['frontend_show_free_shipping'])
				? (string) $this->amz_settings['frontend_show_free_shipping'] : 'yes';

			$opt_badges_activated = array(
				'new' 			=> 'New',
				'onsale' 		=> 'On Sale',
				'freeshipping' 	=> 'Free Shipping',
				'amazonprime' 	=> 'Amazon Prime',
			);
			$this->badges_activated = 
				isset($this->amz_settings['badges_activated'])
				? (array) $this->amz_settings['badges_activated'] : array_keys( $opt_badges_activated );
			$this->badges_activated = $this->clean_multiselect( $this->badges_activated );

			$opt_badges_where = array(
				'product_page' 			=> 'product page',
				'sidebar' 				=> 'sidebar',
				'minicart' 				=> 'minicart',
				'box_related_products' 	=> 'box related products',
				'box_cross_sell' 		=> 'box cross sell',
			);
			$this->badges_where = 
				isset($this->amz_settings['badges_where'])
				? (array) $this->amz_settings['badges_where'] : array_keys( $opt_badges_where );
			$this->badges_where = $this->clean_multiselect( $this->badges_where );
		}

		public function get_amazon_variations_nb( $prodvar=array() ) {
			if ( empty($prodvar) || ! is_array($prodvar) ) {
				return 0;
			}

			//$variations = array();
			if ( isset($prodvar['ASIN']) ) {
				//$variations[] = $prodvar;
				return 1;
			}
			else {
				//$variations = (array) $prodvar;
				return count( $prodvar );
			}
		}


		/**
		 * BITLY related - 2018-jan
		 */
		public function bitly_api_shorten( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'longUrl' 	=> '',
				'domain' 	=> '',
			), $pms);
			extract( $pms );

			$ret = array(
				'status' 	=> 'invalid',
				'msg' 		=> '',
				'short_url' => '',
			);

			if ( '' == $longUrl ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> 'longUrl is empty!',
				));
				return $ret;
			}

			$access_token = get_option( 'WooZone_bitly_access_token', '' );

			if ( '' == $access_token ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> 'bitly access token wasn\'t found!',
				));
				return $ret;
			}

			//:: make request to api
			$longUrl = $this->is_ssl() == true ? 'https:' . $longUrl : 'http:' . $longUrl;
			$uri = $this->bitly_oauth_api . "v3/shorten?access_token=" . $access_token . "&format=json&longUrl=" . urlencode($longUrl);
			if ( $domain != '' ) {
				$uri .= "&domain=" . $domain;
			}

			$input_params = array(
				'header'                        => true,
				'followlocation'                => true,
			);
			$output_params = array(
				'parse_headers'                 => true,
				'resp_is_json'                  => true,
				'resp_add_http_code'            => true,
			);
			$output = $this->curl( $uri, $input_params, $output_params, true );
			//var_dump('<pre>', $output , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			//:: end make request to api
  
			if ( $output['status'] === 'invalid' ) {
				$msg = sprintf( __('curl error; http code: %s; details: %s', 'psp'), $output['http_code'], $output['data'] );
				//var_dump('<pre>', $msg , '</pre>'); echo __FILE__ . ":" . __LINE__; die . PHP_EOL;
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg,
				));
				return $ret;
			}

			$output = $output['data'];
			//var_dump('<pre>', $output , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			$output = json_decode( $output, true );

			if ( ! is_array($output) || ! isset($output['data'], $output['data']['url']) ) {

				$msg = 'bitly error; short url was not found in api response!';
				if ( is_array($output) && isset($output['status_code']) ) {
					$msg = sprintf( __('bitly error; status_code: %s; status_txt: %s', 'psp'), $output['status_code'], $output['status_txt'] );
				}

				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg,
				));
				return $ret;
			}
  
			if (1) {
				$result = array();

				$result['url'] = $output['data']['url'];
				//$result['hash'] = $output['data']['hash'];
				//$result['global_hash'] = $output['data']['global_hash'];
				//$result['long_url'] = $output['data']['long_url'];
				//$result['new_hash'] = $output['data']['new_hash'];

				//$ret['short_url'] = $result['url'];
				$ret = array_replace_recursive($ret, array(
					'status' 	=> 'valid',
					'msg' 		=> 'bitly short url was generated successfully.',
					'short_url' => $result['url'],
				));
				//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				return $ret;
			}
		}

		public function product_url_hash( $data ) {
			return hash_hmac( 'sha256', $data, 'woozone' );
		}

		public function product_url_from_bitlymeta( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'ret_what' 		=> 'only_get_meta', // only_get_meta | do_request | force_do_request
				'product' 		=> null,
				'orig_url' 		=> '',
				'country' 		=> '',
			), $pms);
			extract( $pms );
			//var_dump('<pre>', $ret_what, $orig_url, $country, $product , '</pre>'); //echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$ret = array(
				'status' 	=> 'invalid',
				'msg' 		=> '',
				'orig_url' 	=> $orig_url,
				'short_url' => $orig_url,
			);

			//:: get product id
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
			if ( empty($product_id) ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> 'invalid input parameter product!',
				));
				return $ret;
			}

			//:: get product current url or amazon link (if not provided as input parameter)
			if ( '' == $orig_url ) {
				$prod_link = $this->_product_buy_url_asin( array(
					'product_id' 		=> $product_id,
				));
				$orig_url = $prod_link['link'];
				//$country = $prod_link['country'];
			}

			if ( '' == $orig_url ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> 'product url is empty!',
				));
				return $ret;
			}

			//:: get amazon store country from product url (if not provided as input parameter)
			if ( '' == $country ) {
				$mstat = preg_match('~^//www\.amazon\.([a-z\.]{2,6})/gp/product/~imu', $orig_url, $mfound);
				//var_dump('<pre>jimmy', $orig_url, $mstat, $mfound , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				if ( $mstat ) {
					$country = $mfound[1];
				}
			}

			if ( '' == $country ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> 'country is empty!',
				));
				return $ret;
			}
			//var_dump('<pre>', $ret_what, $orig_url, $country, $product_id , '</pre>'); //echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			//:: product current url
			$orig_hash = $this->product_url_hash( $orig_url );

			$meta = get_post_meta( $product_id, '_amzaff_bitly', true );
			$meta2 = is_array($meta) && isset($meta["$country"]) ? $meta["$country"] : false;
			$meta_short_url = is_array($meta2) && ! empty($meta2['short_url']) ? (string) $meta2['short_url'] : '';

			//:: short url is Found!
			if ( '' != $meta_short_url ) {
				$meta_orig_hash = is_array($meta2) && ! empty($meta2['orig_hash']) ? (string) $meta2['orig_hash'] : '';

				// short url exists & is based on the same original url as the one product currently has!
				if ( $orig_hash === $meta_orig_hash ) {
					$ret = array_replace_recursive($ret, array(
						'status' 	=> 'valid',
						'msg' 		=> 'success.',
						'short_url' => $meta_short_url,
					));
					if ( 'only_get_meta' == $ret_what ) {
						return $ret;
					}
				}
				else {
					$ret = array_replace_recursive($ret, array(
						'msg' 		=> 'current product url hash is different than meta original url hash!',
					));
					if ( 'only_get_meta' == $ret_what ) {
						return $ret;
					}
				}
			}
			//:: short url NOT Found!
			else {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> 'no meta short url was found!',
				));
				if ( 'only_get_meta' == $ret_what ) {
					return $ret;
				}
			}

			//:: try to do a request to bitly api
			if (
				( 'force_do_request' == $ret_what )
				|| ( ( 'do_request' == $ret_what ) && ( 'invalid' == $ret['status'] ) )
			) {

				$ret['status'] = 'invalid'; // reset status to be sure we retrieve the right status for bitly request

				$bitly_stat = $this->bitly_api_shorten(array(
					'longUrl' 		=> $orig_url,
				));
				//var_dump('<pre>', $bitly_stat , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				if ( 'invalid' == $bitly_stat['status'] ) {
					$msg = $ret['msg'] . ' ' . $bitly_stat['msg'];

					$meta = is_array($meta) ? $meta : array();
					$meta["$country"] = array(
						'short_url' 	=> '',
						'orig_hash' 	=> $orig_hash,
						'req_msg' 		=> $msg,
					);
					update_post_meta( $product_id, '_amzaff_bitly', $meta );

					$ret = array_replace_recursive($ret, array(
						'msg' 		=> $msg,
					));
					return $ret;
				}

				$bitly_url = $bitly_stat['short_url'];

				$meta = is_array($meta) ? $meta : array();
				$meta["$country"] = array(
					'short_url' 	=> $bitly_url,
					'orig_hash' 	=> $orig_hash,
				);
				update_post_meta( $product_id, '_amzaff_bitly', $meta );

				$msg = $ret['msg'] . ' ' . 'success.';
				$ret = array_replace_recursive($ret, array(
					'status' 	=> 'valid',
					'msg' 		=> $msg,
					'short_url' => $bitly_url,
				));
			}
			return $ret;
		}


		// update 2018-feb
		private function check_table_generic( $table, $force=false, $pms=array() ) {
			$pms = array_replace_recursive( array(
				'must_have_rows' => true,
			), $pms);
			extract( $pms );

			$table_ = $this->db->prefix . $table;

			$need_check_tables = $this->plugin_integrity_need_verification('check_table_'.$table);

			if ( ! $need_check_tables['status'] && ! $force ) {
				return true; // don't need verification yet!
			}

			// default sql - tables & tables data!
			require_once( $this->cfg['paths']['plugin_dir_path'] . 'modules/setup_backup/default-sql.php' );

			// retrieve all database tables & clean prefix
			$dbTables = $this->db->get_results( "show tables;", OBJECT_K );
			$dbTables = array_keys( $dbTables );
			if ( empty($dbTables) || ! is_array($dbTables) ) {

				$this->plugin_integrity_update_time('check_table_'.$table, array(
					'status'	=> 'invalid',
					'html'		=> sprintf( __('Check plugin table %s: error requesting tables list.', $this->localizationName), $table_ ),
				));
				return false; //something was wrong!
			}

			// table exists?
			if ( ! in_array( $table_, $dbTables) ) {

				$this->plugin_integrity_update_time('check_table_'.$table, array(
					'status'	=> 'invalid',
					'html'		=> sprintf( __('Check plugin table %s: missing.', $this->localizationName), $table_ ),
				));
				return false; //something was wrong!
			}

			// table has rows?
			if ( $must_have_rows ) {
				$query = "select count(a.ID) as nb from $table_ as a where 1=1;";
				$res = $this->db->get_var( $query );
				//var_dump('<pre>', $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				if ( ($res === false) || ! $res ) {

					$this->plugin_integrity_update_time('check_table_'.$table, array(
						'status'	=> 'invalid',
						'html'		=> sprintf( __('Check plugin table %s: is empty - no rows found.', $this->localizationName), $table_ ),
					));
					return false; //something was wrong!
				}
			}

			// all fine
			$this->plugin_integrity_update_time('check_table_'.$table, array(
				'timeout'	=> time(),
				'status'	=> 'valid',
				'html'		=> sprintf( __('Check plugin table %s: installed ok.', $this->localizationName), $table_ ),
			));
			return true; // all is fine!
		}

		// update 2018-feb - get webservice object
		public function get_ws_object_new( $provider, $what='helper', $pms=array() ) {
			$pms = array_replace_recursive(array(
				'the_plugin' 	=> null,
				'settings' 		=> array(),
				'params_new' 	=> array(),
			), $pms);
			extract( $pms );

			if ( 'new_helper' == $what ) {
				require_once( $the_plugin->cfg['paths']['plugin_dir_path'] . 'aa-framework/amz.helper.class.php' );

				$amzHelper = null;
				if ( class_exists('WooZoneAmazonHelper') ) {
					$amzHelper = new WooZoneAmazonHelper( $the_plugin, $params_new );
					//$amzHelper = WooZoneAmazonHelper::getInstance( $the_plugin );
				}
				return $amzHelper;
			}
			else if ( 'new_ws' == $what ) {
				// load the amazon webservices client class
				require_once( $the_plugin->cfg['paths']['plugin_dir_path'] . '/lib/scripts/amazon/aaAmazonWS.class.php' );

				$aaAmazonWS = null;
				if ( class_exists('aaAmazonWS') ) {
					try {
						// create new amazon instance
						///*
						$aaAmazonWS = new aaAmazonWS(
							$params_new['AccessKeyID'],
							$params_new['SecretAccessKey'],
							$params_new['country'],
							$params_new['main_aff_id']
						);
						//*/
						// debug
						//$aaAmazonWS = new aaAmazonWS( '', '', '', '' );
					} catch (Exception $e) {
						// Check 
						$msg = '';
						if (isset($e->faultcode)) { // error occured!
							$msg = $e->faultcode .  ' : ' . (isset($e->faultstring) ? $e->faultstring : $e->getMessage());
						}
						else if ( is_callable(array($e, 'getMessage')) ) {
							$msg = $e->getMessage();
						}
						//var_dump('<pre>', $msg , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
						$this->wsStatus = array_replace_recursive( $this->wsStatus, array(
							'status' 	=> 'invalid',
							'exception' => $e,
							'msg' 		=> $msg,
						));
					}
				}
				if ( is_object($aaAmazonWS) ) {
					$aaAmazonWS->set_the_plugin( $the_plugin, $settings );
				}
				return $aaAmazonWS;
			}

			return $this->get_ws_object( $provider, $what );
		}

		public function build_score_html_container( $score=0, $pms=array() ) {
			$pms = array_replace_recursive(array(
				'show_score'		=> true,
				'css_style'			=> '',
			), $pms);
			extract( $pms );

			$_css_style = ( '' != $css_style ? ' ' . $css_style : '' );

			$size_class = 'size_';
			if ( $score >= 20 && $score < 40 ) {
				$size_class .= '20_40';
			}
			else if ( $score >= 40 && $score < 60 ) {
				$size_class .= '40_60';
			}
			else if ( $score >= 60 && $score < 80 ) {
				$size_class .= '60_80';
			}
			else if ( $score >= 80 && $score <= 100 ) {
				$size_class .= '80_100';
			}
			else {
				$size_class .= '0_20';
			}

			$html = array();
			$html[] = '<div class="WooZone-progress"' . $_css_style . '>';
			$html[] = 		'<div class="WooZone-progress-bar ' . ( $size_class ) . '" style="width:' . ( $score ) . '%"></div>';
			if ( $show_score ) {
				$html[] =	'<div class="WooZone-progress-score">' . ( $score ) . '%</div>';
			}
			$html[] = '</div>';
			return implode('', $html);
		}

		// made for version 9.3 - from single amazon keys to multiple
		public function fix_multikeys_from_single() {

			$ret = -1;
			$found = get_option('WooZone_fix_multikeys_from_single', false);

			//:: already fixed
			if ( $found ) {
				return $ret;
			}

			$ret = -2;

			$this->check_table_generic( 'amz_amzkeys', true, array() ); // update 2018-feb

			//:: get main plugin settings
			$settings = get_option( $this->alias . '_amazon' ); // 'WooZone_amazon'
			$settings = maybe_unserialize( $settings );
			$settings = !empty($settings) && is_array($settings) ? $settings : array();

			//:: save pair in table of multiple amazon keys
			$save_opt = $settings;
			if ( isset($save_opt['AccessKeyID']) && isset($save_opt['SecretAccessKey'])
				&& ! empty($save_opt['AccessKeyID']) && ! empty($save_opt['SecretAccessKey'])
			) {
				$AccessKeyID = $save_opt['AccessKeyID'];
				$SecretAccessKey = $save_opt['SecretAccessKey'];

				//:: verify if you try with aateam demo keys
				$demo_keys = $this->get_aateam_demo_keys();
				$demo_keys = isset($demo_keys['pairs']) ? $demo_keys['pairs'] : array();

				foreach ( $demo_keys as $demokey ) {
					if ( ($AccessKeyID == $demokey[0]) && ($SecretAccessKey == $demokey[1]) ) {
						$AccessKeyID = 'aateam demo access key';
						$SecretAccessKey = 'aateam demo secret access key';
					}
				}

				//:: save keys in table
				$insert_id = $this->amzkeysObj->add_key_indb( $AccessKeyID, $SecretAccessKey );
				$ret = $insert_id;
			}

			update_option('WooZone_fix_multikeys_from_single', true);
			return $ret;
		}

		// update 2018-mar-09
		// set version for plugin assets (css & js files)
		public function plugin_asset_get_version( $asset_type='css', $pms=array() ) {
			//return ''; //DEBUG
			$ret = $this->version();

			if ( defined('WOOZONE_DEV_SERVER') && WOOZONE_DEV_SERVER ) {
				$ret .= '&time=' . time();
			}
			return $ret;
		}
		public function plugin_asset_get_path( $asset_type='css', $path='', $is_wp_enqueue=false, $pms=array() ) {
			$pms = array_replace_recursive(array(
				'id' 			=> '',
				'with_wrapper' 	=> true,
			), $pms);
			extract( $pms );

			$path = trim( $path );
			if ( empty($path) ) {
				return '';
			}

			if ( $is_wp_enqueue ) {
				return $path;
			}

			if ( false !== preg_match('/(\.js|\.css)$/', $path) ) {
				$path .= '?';
			}
			$path .= 'ver=' . $this->plugin_asset_get_version( $asset_type );

			//if ( defined('WOOZONE_DEV_SERVER') && WOOZONE_DEV_SERVER ) {
			//	$path .= '&time=' . time();
			//}

			if ( ! $with_wrapper ) {
				return $path;
			}

			$str = '';
			if ( 'css' == $asset_type ) {
				$str = "<link {ID} type='text/css' href='$path' rel='stylesheet' media='all' />";
			}
			else if ( 'js' == $asset_type ) {
				$str = "<script {ID} type='text/javascript' src='$path'></script>";
			}

			if ( ! empty($id) ) {
				$str = str_replace('{ID}', "id='" . $id . "'", $str);
			}
			else {
				$str = str_replace('{ID}', '', $str);
			}

			return $str;
		}

		public function get_product_import_country( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'product_id'			=> 0,
				'country' 				=> '', //amazon location
				'use_fallback_location' => true,
				'filter_choose_country' => true,
			), $pms);
			extract( $pms );

			$ret = array(
				'country' 	=> $country,
				'text' 		=> '',
			);

			$countries = $this->get_ws_object( $this->cur_provider )->get_countries('country');

			//:: product import country
			$text = __('product was imported from amazon location %s', 'WooZone');
			if ( empty($country) ) {
				if ( ! empty($product_id) ) {
					$country = get_post_meta( $product_id, '_amzaff_country', true );
				}
			}
			if ( empty($country) && $use_fallback_location ) {
				$country = $this->amz_settings['country'];
				$text = __('current amazon location in amazon config module is %s', 'WooZone');
			}

			$country_name = isset($countries["$country"]) ? $countries["$country"] : '';
			$text = sprintf( $text, $country_name );

			if ( empty($country) ) {
				$ret = array_replace_recursive($ret, array(
					'text' 	=> $text,
				));
				return $ret;
			}

			//:: do verify sync option regarding amazon location?
			if ( $filter_choose_country ) {
				$ss = get_option($this->alias . '_sync_options', array());
				$ss = maybe_unserialize($ss);
				$ss = $ss !== false ? $ss : array();

				$sync_choose_country = isset($ss['sync_choose_country']) ? $ss['sync_choose_country'] : 'default';

				if ( 'import_country' != $sync_choose_country ) {
					$country_orig = $country;
					$country = $this->amz_settings['country'];

					if ( $country_orig != $country ) {
						$text = __('current amazon location in amazon config module is %s, but product was imported from amazon location %s. go to <Synchronization log Settings module>, <Amazon location for sync> option and choose <Use product import country> if you want all products like this to be synced based on their import country', 'WooZone');

						$country_name = isset($countries["$country"]) ? $countries["$country"] : '';
						$country_orig_name = isset($countries["$country_orig"]) ? $countries["$country_orig"] : '';

						$text = sprintf( $text, $country_name, $country_orig_name );
					}
				}
			}

			$ret = array_replace_recursive($ret, array(
				'country' 	=> $country,
				'text' 		=> $text,
			));
			return $ret;
		}

		public function get_product_import_country_flag( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'product_id'			=> 0,
				'country' 				=> '', //amazon location
				'use_fallback_location' => true,
				'filter_choose_country' => true,
				'asin' 					=> '',
				'with_link' 			=> true,
			), $pms);
			extract( $pms );

			$ret = array(
				'country' 		=> $country,
				'image' 		=> '',
				'link' 			=> '',
				'image_link'	=> '',
			);

			//:: product import country
			$getCountry = $this->get_product_import_country( array(
				'product_id'			=> $product_id,
				'country' 				=> $country,
				'use_fallback_location' => $use_fallback_location,
			));
			$text = $getCountry['text'];
			$country = $getCountry['country'];

			$ret = array_replace_recursive($ret, array(
				'country' 	=> $country,
			));

			if ( empty($country) ) {
				return $ret;
			}

			//:: try to get the image flag
			$ret['country'] = $country;

			$img_base_url = $this->cfg['paths']["plugin_dir_url"] . 'modules/amazon/images/flags/';

			$flag = $this->get_country2mainaffid( $country );

			$img = '<img class="" src="%s" height="12">';
			$img = sprintf( $img, $img_base_url . $flag . '-flag.gif' );

			$product_buy_url = $this->_product_buy_url_asin( array(
				'product_id' 		=> $product_id,
				'redirect_asin' 	=> $asin,
				'force_country' 	=> $country,
			));
			$prod_link = $product_buy_url['link'];
			
			$prod_link_html = '<a href="%s" target="_blank" class="aa-tooltip" title="%s">%s</a>';
			$prod_link_html = sprintf( $prod_link_html, $prod_link, $text, $img );

			$ret = array_replace_recursive($ret, array(
				'image' 		=> $img,
				'link' 			=> $prod_link,
				'image_link' 	=> $prod_link_html,
			));
			return $ret;
		}

		// convert $variationNumber into number
		public function convert_variation_number_to_number( $variationNumber ) {
			if ( $variationNumber == 'yes_all' ) {
				$variationNumber = (int) $this->ss['max_per_product_variations'];
			}
			elseif ( $variationNumber == 'no' ) {
				$variationNumber = 0;
			}
			else {
				$variationNumber = explode(  "_", $variationNumber );
				$variationNumber = end( $variationNumber );
			}
			$variationNumber = (int) $variationNumber;
			return $variationNumber;
		}

		public function get_product_metas( $product_id, $metas=array(), $pms=array() ) {
			global $wpdb;

			$pms = array_replace_recursive( array(
				'remove_prefix' => '_amzaff_',
			), $pms);
			extract( $pms );

			if ( empty($metas) ) {
				return array();
			}

			$prods2meta = array();

			//foreach ( (array) $metas as $meta) {
			//	$meta_ = str_replace('_amzaff_', '', $meta);
			//	$prods2meta["$meta_"] = get_post_meta( $product_id, $meta, true );
			//}

			$what_metas = $metas;
			$what_metas_ = implode(',', array_map(array($this, 'prepareForInList'), $what_metas));

			$query = "select pm.meta_key, pm.meta_value from $wpdb->postmeta as pm where 1=1 and pm.post_id = $product_id and pm.meta_key in ( $what_metas_ ) order by pm.meta_key asc;";
			$res = $wpdb->get_results( $query, OBJECT_K );
			if ( ! empty($res) ) {
				foreach ( $res as $kk => $vv ) {

					$kk_ = $kk;
					if ( ! empty($remove_prefix) ) {
						$kk_ = str_replace($remove_prefix, '', $kk);
					}

					$prods2meta["$kk_"] = $vv->meta_value;
				}
			}
			return $prods2meta;
		}


		//====================================================
		//== AMAZON : MAKE A LOOKUP REQUEST & CACHE IT IN DB

		// setup amazon object for making request
		public function setupAmazonHelper( $params=array() ) {

			//:: GET SETTINGS
			//$settings = $this->settings();
			//$settings = $this->amz_settings;

			//:: SETUP
			$params_new = array();
			foreach ( $params as $key => $val ) {
				if ( in_array($key, array(
					'AccessKeyID', 'SecretAccessKey', 'country', 'main_aff_id',
					'overwrite_settings'
				)) ) {
					$params_new["$key"] = $val;
				}
			}

			$this->amzHelper = $this->get_ws_object_new( $this->cur_provider, 'new_helper', array(
				'the_plugin' => $this,
				'params_new' => $params_new,
			));

			if ( is_object($this->amzHelper) ) {
			}
		}

		// make a request to amazon with a list of asins (all from the same country)
		public function amazon_request_make_lookup( $asins, $country='', $pms=array() ) {
			$pms = array_replace_recursive(array(
			), $pms);
			extract( $pms );


			//:: init
			$ret = array(
				'status' 			=> 'invalid',
				'msg' 				=> '',
				'code' 				=> '',
				'amz_code' 			=> '',
				'amz_response' 		=> array(),
			);
			$amz_code = '';

			$countries = $this->get_ws_object( $this->cur_provider )->get_countries('country');
			$country_name = isset($countries["$country"]) ? $countries["$country"] : '';


			//:: validation
			if ( empty($asins) || ! is_array($asins) ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> "no asins provided!",
				));
				return $ret;
			}
			//var_dump('<pre>', $asins, $country, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;


			//:: SETUP AMAZON & MAKE REQUEST
			$params_new = array();
			if ( ! empty($country) ) {
				$params_new['country'] = $country;
			}
			//var_dump('<pre>', $params_new , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			$this->setupAmazonHelper( $params_new );
	 
			$rsp = $this->get_ws_object( $this->cur_provider )->api_main_request(array(
				'what_func' 			=> 'api_make_request',
				'amz_settings'			=> $this->amz_settings,
				'from_file'				=> str_replace($this->cfg['paths']['plugin_dir_path'], '', __FILE__),
				'from_func'				=> __FUNCTION__ != __METHOD__ ? __METHOD__ : __FUNCTION__,
				'requestData'			=> array(
					'asin'					=> $asins,
				),
				'optionalParameters'	=> array(),
				'responseGroup'			=> 'Large,ItemAttributes,OfferFull,Offers,Variations,Reviews,PromotionSummary,SalesRank',
				'method'				=> 'lookup',
			));
			$amz_response = $rsp['response'];

			$respStatus = $this->get_ws_object( $this->cur_provider )->is_amazon_valid_response( $amz_response );

			$ret = array_replace_recursive($ret, array(
				'code' 	=> $respStatus['code'],
			));

			$msg = $respStatus['code'] . ' - ' . $respStatus['msg'];


			//:: ERROR
			if ( 'valid' != $respStatus['status'] ) {

	 			if ( isset($respStatus['amz_code'])
	 				&& in_array( strtolower($respStatus['amz_code']), array(
	 					'aws:client.requestthrottled',
	 					'woozone:aws.init.issue',
	 					'woozone:aws.request.dropped'
	 				))
	 			) {
	 				$amz_code = 'throttled';
	 			}

				$msg = 'amazon error : ' . $respStatus['code'] . ' - ' . $respStatus['msg'];

				// throttled
				$status = 'invalid';
				if ( 'throttled' == $amz_code ) {
					$status = 'throttled';
				}
				// some other error
				else if ( $respStatus['code'] < 3 ) {
					$status = 'invalid';
				}
				// product not found (all of them from asins)
				else {
					$status = 'notfound';
				}

				$ret = array_replace_recursive($ret, array(
					'status' 	=> $status,
					'amz_code' 	=> $amz_code,
					'msg' 		=> $msg,
				));
				return $ret;
	 		}


	 		//:: SUCCESS
	 		// fix an amazon issue with items
			$amazonItems = array();
			if ( isset($amz_response['Items']['Item']['ASIN']) ) {
				$amazonItems[] = $amz_response['Items']['Item'];
			} else {
				$amazonItems = $amz_response['Items']['Item'];  				
			}
			$amazonItems = (array) $amazonItems;

			// new array with ASIN as key
			$products = array();
			foreach ( $amazonItems as $idx => $amazonItem ) {
				$itemAsin = isset($amazonItem['ASIN']) ? $amazonItem['ASIN'] : '';

				if ( $this->get_ws_object( $this->cur_provider )->is_valid_product_data( $amazonItem ) ) {
					$products["$itemAsin"] = $amazonItem;
				}
			}

			if ( count($asins) <= 1 ) {
				foreach ( $asins as $asin ) {
					if ( isset($products["$asin"], $products["$asin"]['ASIN']) ) {
						$status = 'valid';
						$msg = sprintf( 'asin %s was successfully found on amazon country = %s', $asin, $country_name );
					}
					else {
						$status = 'notfound';
						$msg = sprintf( 'asin %s was not found on amazon country = %s', $asin, $country_name );
					}
					break;
				}
			}
			else {
				$status = count($asins) == count( array_keys($products) ) ? 'valid' : 'semivalid';

				$msg = array();
				if ( ! empty($products) ) {
					$msg[] = sprintf( 'asins %s were successfully found on amazon country = %s', implode(', ', array_keys($products)), $country_name );
				}
				$asins_notfound = array_diff( $asins, array_keys($products) );
				if ( ! empty($asins_notfound) ) {
					$msg[] = sprintf( 'asins %s were not found on amazon country = %s', implode(', ', $asins_notfound), $country_name );
				}
				$msg = implode(' and ', $msg);
			}

			$ret = array_replace_recursive($ret, array(
				'status' 		=> $status,
				'msg' 			=> $msg,
				'amz_response' 	=> $products,
			));
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			return $ret;
		}

		// update 2018-mar-28 - cache amazon requests
		public function amazon_request_get_cache( $cache_name, $cache_type, $pms=array() ) {
			global $wpdb;

			$pms = array_replace_recursive(array(
			), $pms);
			extract( $pms );

			$table = $wpdb->prefix . 'amz_amazon_cache';
			$how_often = $this->ss['sync_amazon_requests_cache_exp']; //'INTERVAL 1 HOUR';

			if ( is_array($cache_name) ) {
				$cache_name_ = implode(',', array_map(array($this, 'prepareForInList'), $cache_name));
				$sql = "select cache_name, response from $table where 1=1 and cache_name in ( $cache_name_ ) and cache_type = %s and ( response_date > DATE_SUB( NOW(), $how_often ) );";
				$sql = $wpdb->prepare( $sql, $cache_type );
				$res = $wpdb->get_results( $sql, OBJECT_K );
				if ( empty($res) ) {
					return array();
				}

				$ret = array();
				foreach ($res as $key => $val) {
					$ret["$key"] = maybe_unserialize( $val->response );
				}
				return $ret;
			}
			else {
				$sql = "select response from $table where 1=1 and cache_name = %s and cache_type = %s and ( response_date > DATE_SUB( NOW(), $how_often ) ) limit 1;";
				$sql = $wpdb->prepare( $sql, $cache_name, $cache_type );
				$res = $wpdb->get_var( $sql );
				if ( empty($res) ) {
					return $res;
				}

				$ret = maybe_unserialize( $res );
				return $ret;
			}
			return false;
		}

		public function amazon_request_save_cache( $cache_name, $cache_type, $pms=array() ) {
			global $wpdb;

			$pms = array_replace_recursive(array(
				'country' 	=> '',
				'response' 	=> array(),
			), $pms);
			extract( $pms );

			$table = $wpdb->prefix . 'amz_amazon_cache';

			// delete old cache
			$sql = "delete from $table where 1=1 and cache_name = %s and cache_type = %s;";
			$sql = $wpdb->prepare( $sql, $cache_name, $cache_type );
			$res = $wpdb->query( $sql );

			// insert (ignore) new response as cache
			$this->db_custom_insert(
				$table,
				array(
					'values' => array(
						'cache_name' 	=> $cache_name,
						'cache_type' 	=> $cache_type,
						'country' 		=> $country,
						'response' 		=> maybe_serialize( $response ),
					),
					'format' => array(
						'%s', '%s', '%s', '%s'
					)
				),
				true
			);
			return true;
		}


		//====================================================
		//== SYNCHRONIZATION of products

		public function get_product_sync_rules() {
			return array(
				'price'                 => __('Price', $this->localizationName),
				'title'                 => __('Title', $this->localizationName),
				'url'                   => __('Buy URL', $this->localizationName),
				'desc'                  => __('Description', $this->localizationName),
				'sku'                   => __('SKU', $this->localizationName),
				'sales_rank'            => __('Sales Rank', $this->localizationName),
				'reviews'               => __('Reviews', $this->localizationName),
				'short_desc'            => __('Short description', $this->localizationName),
				'new_variations'        => __('New Variations', $this->localizationName),
			);
		}

		public function syncproduct_build_last_stats_column( $row=array() ) {
			$row = array_replace_recursive( array(
				'asin' => '',
				'sync_nb' => 0,
				'sync_last_status' => '',
				'sync_last_status_msg' => '',
				'sync_trash_tries' => 0,
				'sync_import_country' => '',
				'sync_current_cycle' => '',
				'first_updated_date' => '',
			), $row);
			extract( $row );

			$ret = array();

			$sync_last_status_text = array();
			if ( ! empty($sync_last_status_msg) ) {

				$sync_last_status_text[] = __('The last sync status for this product:<br />', $this->localizationName);

				if ( is_array($sync_last_status_msg) ) {

					$sync_rules = $this->get_product_sync_rules();

					if ( isset($sync_last_status_msg['msg']) ) {
						$sync_last_status_text[] = $sync_last_status_msg['msg'] . '<br />';
					}

					foreach ( array('notfound', 'updated') as $val ) {

						if ( isset($sync_last_status_msg["_variations_$val"])
							&& ! empty($sync_last_status_msg["_variations_$val"])
						) {

							if ( ! isset($sync_last_status_msg['rules']) || ! is_array($sync_last_status_msg['rules']) ) {
								$sync_last_status_msg['rules'] = array();
							}

							if ( ! isset($sync_last_status_msg['rules']["_variations_$val"]) ) {
								$sync_last_status_msg['rules']["_variations_$val"]
								 = $sync_last_status_msg["_variations_$val"];
							}
						}
					}

					if ( isset($sync_last_status_msg['rules']) && ! empty($sync_last_status_msg['rules']) ) {

						$sync_rules_text = array();
						$vars_changed_titles = array(
							'_variations_updated' => __('- %s variations were updated (ID, Asin) : %s', $this->localizationName),
							'_variations_notfound' => __('- %s variations were not found (ID, Asin) : %s', $this->localizationName),
						);

						$nbupdated = 0;
						$vars_changed_cc = 0;

						foreach ( $sync_last_status_msg['rules'] as $kk => $vv ) {

							if ( 'yes' == $vv['status'] ) {

								if ( 'new_variations' == $kk ) {

									$sync_rules_text[] = sprintf( __("- Field %s was updated ( %d new variations added ).", $this->localizationName), $sync_rules["$kk"], $vv['new_added'] );

									if ( isset($vv['msg']) && ! empty($vv['msg']) ) {
										$sync_rules_text[] = $vv['msg'];
									}
									$nbupdated++;
									continue 1;
								}
								else if ( in_array($kk, array('_variations_notfound', '_variations_updated')) ) {
									if ( isset($vv) && ! empty($vv) ) {

										$sync_rules_text[] = sprintf(
											$vars_changed_titles["$kk"],
											count( $vv['vars'] ),
											implode(', ', array_map(array($this, 'prepareForPairView'), $vv['vars'], array_keys($vv['vars'])))
										);
										$nbupdated++;
										$vars_changed_cc++;
									}
									continue 1;
								}

								$sync_rules_text[] = sprintf( __("- Field %s was updated.", $this->localizationName), $sync_rules["$kk"] );
								$nbupdated++;
							}
							else {

								if ( 'new_variations' == $kk ) {

									if ( isset($vv['msg']) && ! empty($vv['msg']) ) {
										$sync_rules_text[] = $vv['msg'];
									}
								}
							}
						}
						// end foreach

						if ( $nbupdated ) {
							$sync_last_status_text[] = 'The following fields were updated: <br />';
							$sync_last_status_text[] = implode('<br /><br />', $sync_rules_text);
						}
						else {
							$sync_last_status_text[] = __("It seems no product field need updated.", $this->localizationName);
						}
					}
				}
				else {
					$sync_last_status_text[] = $sync_last_status_msg;
				}
			}
			else {
				$sync_last_status_text[] = __('This product was not synced yet.', $this->localizationName);
			}
			$sync_last_status_text = implode('<br />', $sync_last_status_text);

			$ret['text_last_sync_title'] = $sync_last_status_text;

			$sync_last_status_text = strip_tags($sync_last_status_text, '<br><br/><br />');



			$text_syncs_nb_title = __('The number represents the total successfull synchronizations for this product.', $this->localizationName);
			$text_syncs_nb = sprintf( __('<span>%s</span> SYNCS', $this->localizationName), $sync_nb );


			$text_last_sync_niceinfo = array();
			$text_last_sync_niceinfo[] = $sync_import_country;


			$tmpp_css = '';
			if ( '' != $sync_last_status ) {
				switch ($sync_last_status) {
					case 'updated':
						$tmpp_css = 'updated';
						break;

					case 'notupdated':
					case 'valid':
						$tmpp_css = 'notupdated';
						break;

					case 'notfound':
						$tmpp_css = 'notfound';
						break;

					// invalid | throttled
					default:
						$tmpp_css = 'error';
						break;
				}
				$tmpp_css = 'sync-' . $tmpp_css;
			}

			//$text_last_sync_niceinfo[] = '<a href="#" title="' . $sync_last_status_text . '" class="WooZone-sync-last-status-text ' . $tmpp_css . '">' . $text_syncs_nb . '</a>';
			$text_last_sync_niceinfo[] = '<span class="WooZone-sync-last-status-text ' . $tmpp_css . '" title="' . $text_syncs_nb_title . '">' . $text_syncs_nb . '</span>';
			$text_last_sync_niceinfo[] = '<a href="#" class="WooZone-simplemodal-trigger" title="' . $sync_last_status_text . '"><i class="fa fa-eye-slash"></i></a>';

			if ( $sync_trash_tries ) {
				$text_last_sync_niceinfo[] = sprintf( __('<span title="the number of consecutive synchronizations requests which returned error for this product. it is related to Put amazon products in trash when syncing after... from amazon config module / bug fixes tab">(%d)</span>', $this->localizationName), (int) $sync_trash_tries );
			}

			// identify current cycle
			if ( ( $sync_current_cycle == $first_updated_date ) && ! empty($first_updated_date) ) {
					$text_last_sync_niceinfo[] = sprintf( __('<span class="sync-current-cycle" title="this row was parsed by the cronjob current sync cycle"><i class="fa fa-circle"></i></span>', $this->localizationName) );
			}

			$text_last_sync_niceinfo = implode( PHP_EOL, $text_last_sync_niceinfo );

			$ret['text_last_sync_niceinfo'] = $text_last_sync_niceinfo;
			return $ret;
		}

		public function syncproduct_sanitize_last_status( $status ) {
			if ( '0' === (string) $status || '1' === (string) $status ) {
				return $status ? 'valid' : 'invalid';
			}
			return $status;
		}

		public function syncproduct_is_sync_needed( $pms=array() ) {
			$pms = array_replace_recursive( array(
				'current_time' => time(),
				'recurrence' => 0,
				'product_id' => 0,
				'sync_last_date' => false,
			), $pms);
			extract( $pms );

			if ( false === $sync_last_date ) {
				if ( $product_id ) {
					$sync_last_date = get_post_meta( $product_id, '_amzaff_sync_last_date', true );
				}
			}

			if ( empty($sync_last_date) || ( $current_time >= ($sync_last_date + $recurrence) ) ) {
				return true;
			}
			return false;
		}


		//====================================================
		//== TEMPLATES SYSTEM

		public function doing_it_wrong( $function, $message, $version ) {

			$message .= ' Backtrace: ' . wp_debug_backtrace_summary();

			if ( is_ajax() ) {
				do_action( 'doing_it_wrong_run', $function, $message, $version );
				error_log( "{$function} was called incorrectly. {$message}. This message was added in plugin version {$version}." );
			} else {
				_doing_it_wrong( $function, $message, $version );
			}
		}

		public function tplsystem_get_template( $template_name, $pms=array() ) {

			$pms = array_replace_recursive( array(
				'template_path' 	=> '',
				'default_path' 		=> '',
			), $pms);
			extract( $pms );

			$located = $this->tplsystem_locate_template( $template_name, $pms );

			clearstatcache();
			if ( ! file_exists( $located ) ) {
				$this->doing_it_wrong(
					__FUNCTION__,
					sprintf( __( '%s does not exist.', 'woozone' ), '<code>' . $located . '</code>' ),
					'10.0.5'
				);
				return ;
			}

			// third party plugins can override the template file here
			$located = apply_filters( 'woozone_get_template', $located, $template_name, $pms );

			do_action( 'woozone_get_template_before', $located, $template_name, $pms );

			include $located;

			do_action( 'woozone_get_template_after', $located, $template_name, $pms );
		}

		public function tplsystem_get_template_html( $template_name, $pms=array() ) {

			ob_start();
			$this->tplsystem_get_template( $template_name, $pms );
			return ob_get_clean();
		}

		public function tplsystem_locate_template( $template_name, $pms=array() ) {

			$pms = array_replace_recursive( array(
				'template_path' 	=> '',
				'default_path' 		=> '',
			), $pms);
			extract( $pms );

			// your active theme
			if ( ! $template_path ) {
				$template_path = apply_filters( 'woozone_template_path', 'woozone/' );
			}

			// our plugin default templates folder
			if ( ! $default_path ) {
				$default_path = $this->cfg['paths']['plugin_dir_path'] . 'templates/';
			}

			// the loading order:
			// 		your active theme/$template_path/$template_name
			// 		$default_path/$template_name
			$located = locate_template(
				array(
					trailingslashit( $template_path ) . $template_name,
				),
				false //don't load the template in wp function
			);

			// get our default template
			if ( ! $located || ( defined('WOOZONE_TEMPLATE_DEBUG_MODE') && WOOZONE_TEMPLATE_DEBUG_MODE ) ) {
				$located = $default_path . $template_name;
			}

			// third party plugins can override the template file here
			$located = apply_filters( 'woozone_locate_template', $located, $template_name, $pms );

			return $located;
		}


		//====================================================
		//== BADGES / FLAGS

		public function is_product_freeshipping( $post_id, $pms=array() ) {

			$pms = array_replace_recursive( array(
				'current_amazon_aff' 	=> array(),
			), $pms);
			extract( $pms );

			$ret = array(
				'status' 	=> false,
				'html' 		=> '',
				'link' 		=> '',
			);

			$contents = '';

			if ( empty($current_amazon_aff) || ! is_array($current_amazon_aff) ) {
				$current_amazon_aff = $this->_get_current_amazon_aff();
			}

			$_tag = '';
			$_affid = $current_amazon_aff['user_country']['key'];
			if ( isset($this->amz_settings['AffiliateID']["$_affid"]) ) {
				$_tag = '&tag=' . $this->amz_settings['AffiliateID']["$_affid"];
			}
			$tag = $_tag;

			$meta = get_post_meta($post_id, '_amzaff_isSuperSaverShipping', true);
			if ( !empty($meta) ) {
				
				$link = '//www.amazon' . $current_amazon_aff['user_country']['website'] . '/gp/help/customer/display.html/ref=mk_sss_dp_1?ie=UTF8&amp;pop-up=1&amp;nodeId=527692' . $tag;

				ob_start();
		?>
				<span class="WooZone-free-shipping">
					&amp; <b><?php echo $this->_translate_string( 'FREE Shipping' ); ?></b>.
					<a class="link" onclick="return WooZone.popup(this.href,'AmazonHelp','width=550,height=550,resizable=1,scrollbars=1,toolbar=0,status=0');" target="AmazonHelp" href="<?php echo $link; ?>"><?php echo $this->_translate_string( 'Details' ); ?></a>
				</span>
		<?php
				$contents .= ob_get_clean();

				$ret = array_replace_recursive( $ret, array(
					'status' 	=> true,
					'html' 		=> $contents,
					'link' 		=> $link,
				));
			}

			return $ret;
		}

		public function is_product_amazonprime( $post_id, $pms=array() ) {

			$pms = array_replace_recursive( array(
			), $pms);
			extract( $pms );

			$ret = array(
				'status' 	=> false,
				'html' 		=> '',
				'link' 		=> '',
			);

			$contents = '';

			$meta = get_post_meta($post_id, '_amzaff_isAmazonPrime', true);
			if ( ! empty($meta) ) {
				$ret = array_replace_recursive( $ret, array(
					'status' 	=> true,
				));
			}

			//$ret['status'] = true; //DEBUG
			return $ret;
		}

		// return: false | true (if it has that badge)
		public function product_badge_is( $product, $badge_type ) {

			$prod_id = 0;
			if ( in_array($badge_type, array('new', 'onsale')) ) {

				if ( ! is_object( $product) ) {
					$product = wc_get_product( $product );
				}
				if ( ! is_object( $product) ) {
					return false;
				}

				if ( is_object($product) ) {
					if ( method_exists( $product, 'get_id' ) ) {
						$prod_id = (int) $product->get_id();
					} else if ( isset($product->id) && (int) $product->id > 0 ) {
						$prod_id = (int) $product->id;
					}
				}
			}
			else {
				$prod_id = (int) $product;
			}

			// is product?
			if ( $prod_id <=0 ) return false;

			// is amazon product?
			$redirect_asin = get_post_meta($prod_id, '_amzASIN', true);
			if ( empty($redirect_asin) ) {
				return false;
			}
			//if ( !$this->verify_product_is_amazon($prod_id) ) {
			//	return false;
			//}

			$status = false;
			switch( $badge_type ) {

				case 'new':

					$oneday = 86400; //seconds
					$today = time();

					//$post_date = strtotime( $product->post_date );
					$post_date = null;
					if ( is_object($product->get_date_created()) ) {
						$post_date = $product->get_date_created()->getTimestamp();
					}
					//var_dump('<pre>', $today, $post_date, $today - $post_date ,'</pre>');
					if ( is_null($post_date) ) {
						return false;
					}

					if ( ( $today - $post_date ) <= $oneday ) {
						$status = true;
					}
					break;

				case 'onsale':

					$is_onsale = $product->is_on_sale();
					if ( $is_onsale ) {
						$status = true;
					}
					break;

				case 'freeshipping':

					$is_fs = $this->is_product_freeshipping( $prod_id );
					$status = $is_fs['status'];
					break;

				case 'amazonprime':

					$is_amzp = $this->is_product_amazonprime( $prod_id );
					$status = $is_amzp['status'];
					break;
			}

			return $status;
		}

		public function product_badge_is_new( $product ) {
			return $this->product_badge_is( $product, 'new' );
		}

		public function product_badge_is_onsale( $product ) {
			return $this->product_badge_is( $product, 'onsale' );
		}

		public function product_badge_is_freeshipping( $product ) {
			return $this->product_badge_is( $product, 'freeshipping' );
		}

		public function product_badge_is_amazonprime( $product ) {
			return $this->product_badge_is( $product, 'amazonprime' );
		}

		public function clean_multiselect( $val=array() ) {
			$val = array_filter( array_unique( $val ) );
			return $val;
		}

		public function is_plugin_avi_active() {
			return $this->is_plugin_active( 'AVI' );
		}
	}
}
// __DIR__ - uses PHP 5.3 or higher
// require_once( __DIR__ . '/functions.php');
require_once( dirname(__FILE__) . '/functions.php');