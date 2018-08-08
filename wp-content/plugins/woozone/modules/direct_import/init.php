<?php
/*
* Define class WooZoneDirectImportOptions
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;
	  
if (class_exists('WooZoneDirectImportOptions') != true) {
	class WooZoneDirectImportOptions
	{
		/*
		* Some required plugin information
		*/
		const VERSION = '1.0';

		/*
		* Store some helpers config
		*/
		public $the_plugin = null;

		private $module_folder = '';
		private $module = '';

		static protected $_instance;

		public $direct_import_oauth_api; // from framework class


		/*
		* Required __construct() function that initalizes the AA-Team Framework
		*/
		public function __construct()
		{
			global $WooZone;

			$this->the_plugin = $WooZone;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/direct_import/';
			$this->module = $this->the_plugin->cfg['modules']['direct_import'];

			//$this->direct_import_oauth_api = $this->the_plugin->direct_import_oauth_api;
			$this->direct_import_oauth_api = '';

			if ( $this->the_plugin->is_admin ) {
				add_action('admin_menu', array( $this, 'adminMenu' ));
			}

			add_action( 'wp_ajax_WooZoneDirectImportOptionsAuth', array( $this, 'auth_request' ) );
		}

		/**
	    * Hooks
	    */
	    static public function adminMenu()
	    {
	       self::getInstance()
	    		->_registerAdminPages();
	    }

	    /**
	    * Register plug-in module admin pages and menus
	    */
		protected function _registerAdminPages()
    	{
    		add_submenu_page(
    			$this->the_plugin->alias,
    			$this->the_plugin->alias . " " . __('Direct Import', $this->the_plugin->localizationName),
	            __('Direct Import', $this->the_plugin->localizationName),
	            'manage_options',
	            $this->the_plugin->alias . "_direct_import",
	            array($this, 'printBaseInterface')
	        );

			return $this;
		}

		public function printBaseInterface()
		{
			global $wpdb;
?>
		<div id="<?php echo WooZone()->alias?>" class="WooZone-direct-import">
			
			<div class="<?php echo WooZone()->alias?>-content"> 
				
				<?php
				// show the top menu
				WooZoneAdminMenu::getInstance()->make_active('import|direct_import')->show_menu();
				?>
				
				<!-- Content -->
				<section class="WooZone-main">
					
					<?php 
					echo WooZone()->print_section_header(
						$this->module['direct_import']['menu']['title'],
						$this->module['direct_import']['description'],
						$this->module['direct_import']['help']['url']
					);

					require_once( $this->the_plugin->cfg['paths']['freamwork_dir_path'] . 'settings-template.class.php');

					// Initalize the your aaInterfaceTemplates
					$aaInterfaceTemplates = new aaInterfaceTemplates($this->the_plugin->cfg);

					// then build the html, and return it as string
					echo $aaInterfaceTemplates->build_page( $this->options(), $this->the_plugin->alias, $this->module);
					?>
				</section>
			</div>
		</div>

<?php
		}

		private function options()
		{
			return array(
				$this->module['db_alias'] => array(
					
					/* define the form_sizes  box */
					'direct_import' => array(
						'title' => 'Amazon settings',
						'icon' => '{plugin_folder_uri}images/amazon.png',
						'size' => 'grid_4', // grid_1|grid_2|grid_3|grid_4
						'header' => false, // true|false
						'toggler' => false, // true|false
						'buttons' => true, // true|false
						'style' => 'panel', // panel|panel-widget
						
						// create the box elements array
						'elements' => array(

							'headeline' => array(
								'type' => 'html',
								'html' => '
									<div class="WooZone-direct-import-notice">
										<h4>Getting Started</h4>
										<p>
										As you might have heard, Amazon has restricted access to their Product Advertising API to new affiliates. For now it’s just for the United States Affiliate Program. 

										Now, after you sign up and get your affiliate id you have to generate at least 3 qualified sales in order to get access to the Product Advertising API. 

										We have built this extension with this sole purpose. To give you an alternative to import products into your website without the necessity of having API keys.
										<br/><br/>
										All you have to do is install the Direct Import Extension, Authorize it on your WZone Install and import products!
										<br/><br/>
										<a target="_blank" href="https://chrome.google.com/webstore/detail/gmpiiinlandbgcfejoeaodgpfkdjnolm">Get the WZone Direct Import Extension here </a></p>
									</div>
									'
							),

							'redirect_url' => array(
								'type' => 'text_readonly',
								'std' => home_url('/'),
								'size' => 'large',
								'title' => 'Api URL',
								'desc' => '',
							),

							'api_secret' => array(
								'type' => 'generate_password',
								'std' => '',
								'size' => 'large',
								'force_width' => '350',
								'title' => 'Api SECRET',
								'desc' => 'Expected format: <code>e5cf514226dc0d222fa6703e44438c349f920c90</code>',
							),
						)
					)
				)
			);
		}

		/**
		* Singleton pattern
		*
		* @return WooZoneDirectImportOptions Singleton instance
		*/
		static public function getInstance()
		{
			if (!self::$_instance) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		public function auth_request()
		{
			$code = isset($_REQUEST['code']) ? (string) $_REQUEST['code'] : '';

			if ( $code == "" ) {
				return true;
			}

			//:: code retrieved
			if (1) {

				$direct_import_option = get_option( $this->the_plugin->alias . '_direct_import', true );

				$client_id = $direct_import_option['client_id'];
				$client_secret = $direct_import_option['client_secret'];
				$redirect_uri = $direct_import_option['redirect_url'];

				$uri = $this->direct_import_oauth_api . "oauth/access_token";

				// POST to the direct_import authentication endpoint
				$params = array();
				$params['client_id'] = $client_id;
				$params['client_secret'] = $client_secret;
				$params['code'] = $code;
				$params['redirect_uri'] = $redirect_uri;
				
				$output = "";
				$params_string = "";
				foreach ($params as $key=>$value) { 
					$params_string .= $key.'='.$value.'&'; 
				}
				rtrim($params_string,'&');

				//:: make request
				/*try {
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $uri);
					curl_setopt($ch, CURLOPT_POST, count($params));
					curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$output = curl_exec($ch);
				} catch (Exception $e) {
					if (isset($e->faultcode)) { // error occured!
						$__msg = $e->faultcode .  ' : ' . (isset($e->faultstring) ? $e->faultstring : $e->getMessage());
						var_dump('<pre>', $__msg , '</pre>'); echo __FILE__ . ":" . __LINE__; die . PHP_EOL;
					}
				}*/
				$input_params = array(
					'header'                        => true,
					'post'                          => count($params),
					'postfields'                    => $params_string,
				);
				$output_params = array(
					'parse_headers'                 => true,
					'resp_is_json'                  => true,
					'resp_add_http_code'            => true,
				);
				$output = $this->the_plugin->curl( $uri, $input_params, $output_params, true );
				//var_dump('<pre>', $output , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				//:: end make request

				if ( $output['status'] === 'invalid' ) {
					$msg = sprintf( __('curl error; http code: %s; details: %s', 'psp'), $output['http_code'], $output['data'] );
					var_dump('<pre>', $msg , '</pre>'); echo __FILE__ . ":" . __LINE__; die . PHP_EOL;
				}

				$output = $output['data'];
				parse_str( $output, $parseResult );

				//:: error occured trying to get access token
				$access_token = isset($parseResult['access_token']) ? $parseResult['access_token'] : '';
				if( '' == $access_token ) {
					$output = json_decode( $output, true );

					if ( ! $output ) {
						var_dump('<pre>', $output , '</pre>'); echo __FILE__ . ":" . __LINE__; die . PHP_EOL;
					}
					else {
						$status_txt = isset($output['status_txt']) ? $output['status_txt'] : '';

					?>
						<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
						<style>
							body {
								background: #0e3042;
								font-family: 'Open Sans', sans-serif;
							}
							div {
								padding: 25px;
								padding-left: 100px;
								background-image: url('data:image/png;base64,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');
								background-repeat: no-repeat;
								background-position: 30px 50%;
								color: #fff;
							}
							h4 {
								margin: 0;
							}
						</style>
						<body>
							<div>
								<h4>
									Invalid response from direct_import Auth service:
									<code><?php echo $status_txt;?></code>
								</h4>
							</div>
						</body>
					<?php
						die;
					}
				}
				//:: end error occured trying to get access token

				//:: valid access token retrieved
				$login = isset($parseResult['login']) ? $parseResult['login'] : '';

				update_option( $this->the_plugin->alias . '_direct_import_access_token', $access_token );
				update_option( $this->the_plugin->alias . '_direct_import_login', $login );

				?>
				<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
				<style>
					body {
						background: #0e3042;
						font-family: 'Open Sans', sans-serif;
						font-size: 22px;
						text-align: center;
						margin-top: 20%;
						color: #fff;
					}
				</style>
				<body>
					Congratulations!
				</body>

				<script type="text/javascript">
					setTimeout( function(){
						window.opener.location.reload(true);
						window.close();
					}, 1000 );
				</script>
				<?php
				die;
				//:: end valid access token retrieved
			}
			//:: end code retrieved
		}
	}
}
 
// Initialize the WooZoneDirectImportOptions class
$WooZoneDirectImportOptions = WooZoneDirectImportOptions::getInstance();
