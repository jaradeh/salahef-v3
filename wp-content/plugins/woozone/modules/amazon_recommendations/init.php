<?php
/*
* Define class WooZoneSearchRecommendations
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;

if (class_exists('WooZoneSearchRecommendations') != true) {
    class WooZoneSearchRecommendations
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

        /*
        * Required __construct() function that initalizes the AA-Team Framework
        */
        public function __construct()
        {
        	global $WooZone;

        	$this->the_plugin = $WooZone;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/amazon_recommendations/';
			$this->module = $this->the_plugin->cfg['modules']['amazon_recommendations'];

			if (is_admin()) {
	            add_action('admin_menu', array( &$this, 'adminMenu' ));
			}
        }

		/**
	    * Singleton pattern
	    *
	    * @return WooZoneSearchRecommendations Singleton instance
	    */
	    static public function getInstance()
	    {
	        if (!self::$_instance) {
	            self::$_instance = new self;
	        }

	        return self::$_instance;
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
    			$this->the_plugin->alias . " " . __('Amazon Recommendations', $this->the_plugin->localizationName),
	            __('System Status', $this->the_plugin->localizationName),
	            'manage_options',
	            $this->the_plugin->alias . "_amazon_recommendations",
	            array($this, 'display_index_page')
	        );

			return $this;
		}

		public function display_index_page()
		{
			$this->printBaseInterface();
		}
		
		public function get_countries_selector( $default='com' )
		{
			$html = array();

			$flag_base_url = WooZone()->cfg['modules']['amazon']['folder_uri'] . 'images/flags/';

			$html[] = '<div class="' . ( WooZone()->alias ) . '-country-selector" data-default="' . ( $default ) . '">';
			$html[] = 	'<div class="' . ( WooZone()->alias ) . '-country-current">';
			$html[] = 		'<img src="' . ( $flag_base_url ) . '" /> <span>---</span>';
			$html[] = 		'<div class="dashicons dashicons-arrow-down-alt2"></div>';
			$html[] = 	'</div>';
			$html[] = 	'<ul>';
			$html[] = 		'<li data-country="com.au"><img src="' . ( $flag_base_url ) . 'AU-flag.gif" /> <span>Australia</span></li>';
			$html[] = 		'<li data-country="com.br"><img src="' . ( $flag_base_url ) . 'BR-flag.gif" /> <span>Brazil (Brasil)</span></li>';
			$html[] = 		'<li data-country="ca"><img src="' . ( $flag_base_url ) . 'CA-flag.gif" /> <span>Canada</span></li>';
			$html[] = 		'<li data-country="cn"><img src="' . ( $flag_base_url ) . 'CN-flag.gif" /> <span>China (中国大陆)</span></li>';
			$html[] = 		'<li data-country="fr"><img src="' . ( $flag_base_url ) . 'FR-flag.gif" /> <span>France</span></li>';
			$html[] = 		'<li data-country="de"><img src="' . ( $flag_base_url ) . 'DE-flag.gif" /> <span>Germany (Deutschland)</span></li>';
			$html[] = 		'<li data-country="in"><img src="' . ( $flag_base_url ) . 'IN-flag.gif" /> <span>India</span></li>';
			$html[] = 		'<li data-country="it"><img src="' . ( $flag_base_url ) . 'IT-flag.gif" /> <span>Italy (Italia)</span></li>';
			$html[] = 		'<li data-country="co.jp"><img src="' . ( $flag_base_url ) . 'JP-flag.gif" /> <span>Japan (日本)</span></li>';
			$html[] = 		'<li data-country="com.mx"><img src="' . ( $flag_base_url ) . 'MX-flag.gif" /> <span>Mexico (México)</span></li>';
			//$html[] = 	'<li data-country="nl"><img src="' . ( $flag_base_url ) . '" /> <span>Netherlands (Nederland)</span></li>';
			$html[] = 		'<li data-country="es"><img src="' . ( $flag_base_url ) . 'ES-flag.gif" /> <span>Spain (España)</span></li>';
			$html[] = 		'<li data-country="co.uk"><img src="' . ( $flag_base_url ) . 'UK-flag.gif" /> <span>United Kingdom</span></li>';
			$html[] = 		'<li data-country="com"><img src="' . ( $flag_base_url ) . 'US-flag.gif" /> <span>United States</span></li>';
			$html[] = 	'</ul>';
			$html[] = '</div>';
			

			return implode( "\n", $html );
		}

		/*
		* printBaseInterface, method
		* --------------------------
		*
		* this will add the base DOM code for you options interface
		*/
		private function printBaseInterface()
		{
			global $wpdb;
			
			$amz_settings = get_option( 'WooZone_amazon' );
			$plugin_data = get_plugin_data( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'plugin.php' );

			$default = $amz_settings['country'];
?>
		<?php echo WooZone_asset_path( 'js', $this->module_folder . 'app.class.js', false ); ?>
		<div id="<?php echo WooZone()->alias?>">
			
			<div class="<?php echo WooZone()->alias?>-content"> 

				<?php
				// show the top menu
				WooZoneAdminMenu::getInstance()->make_active('info|amazon_recommendations')->show_menu();
				?>

				<!-- Content -->
				<section class="<?php echo WooZone()->alias?>-main">
					
					<?php 
					echo WooZone()->print_section_header(
						$this->module['amazon_recommendations']['menu']['title'],
						$this->module['amazon_recommendations']['description'],
						$this->module['amazon_recommendations']['help']['url']
					);
					?>
					
					<div class="panel panel-default WooZone-panel">
						<div class="panel-body WooZone-panel-body">

						<!-- Content Area -->
						<div id="WooZone-content-area">
							<div class="WooZone-grid_4">
	                        	<div class="WooZone-panel">
									<div class="WooZone-panel-content WooZone-amazon-recommendations">
										<label>
											<span>Select your preferred country website:</span>
											<?php echo $this->get_countries_selector( $default );?>
										</label>

										<label>
											<span>Departments:</span>
											<?php 
												$categs = WooZone()->wp_filesystem->get_contents( $this->module['folder_path'] . "/categs.json" );
												if( !$categs ){
													$categs = file_get_contents($this->module_folder . "/categs.json" );
												}
												
												$categs = json_decode( $categs, true );
												foreach ( $categs as $key => $value ) {
													foreach ($value as $key2 => $list) {
												?>
													<select class="WooZone-categs-by-country" style="<?php echo $key2 == $default ? 'display:inline-block' : '' ?>" data-country="<?php echo $key2;?>">
												<?php
														foreach ($list as $key3 => $value3) {
														 	echo '<option value="' . ( $key3 ) . '">' . ( $value3 ) . '</option>';
														}
													}
												?>
													</select>
												<?php
												}
											?>
										</label>

										<label>
											<span>Keyword:</span>
											<input type="text" name="WooZone-recommendations-keyword" class="WooZone-text-input" value="" placeholder="your keyword here ..." />
											<input type="hidden" id="WooZone-hidden-insane-url" value="<?php echo admin_url('admin.php?page=WooZone_insane_import&keyword=');?>" />
											<input type="hidden" id="WooZone-default-country" value="<?php echo $default;?>" />
										</label>
										<label>
											<span></span>
											<input type="button" class="button button-primary button-large" value="Search" />
										</label>


										<div class="WooZone-amazon-recommendations-results"></div>
									</div>
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>

<?php
		}

		/*
		* ajax_request, method
		* --------------------
		*
		* this will create requesto to 404 table
		*/
		public function ajax_request()
		{
		}
	}
}

// Initialize the WooZoneSearchRecommendations class
$WooZoneSearchRecommendations = WooZoneSearchRecommendations::getInstance();
