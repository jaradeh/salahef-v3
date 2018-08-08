<?php
/*
* Define class Modules Manager List
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;
if (class_exists('WooZoneAdvancedSearch') != true) {
	class WooZoneAdvancedSearch
	{
		/*
		* Some required plugin information
		*/
		const VERSION = '1.0';
		
		/*
		* Store some helpers config             
		*/
		public $cfg = array();
		public $module = array();
		public $networks = array();
		private $amz_setup = null;
		public $the_plugin = null;
		
		/*
		* Required __construct() function that initalizes the AA-Team Framework
		*/
		public function __construct($cfg, $module)
		{
			global $WooZone;
			
			$this->the_plugin = $WooZone;
			$this->cfg    = $cfg;
			$this->module = $module;
			$this->amz_setup = $WooZone->settings();
		}
		
		public function moduleValidation() {
			$ret = array(
				'status'			=> false,
				'html'				=> ''
			);
			
			// AccessKeyID, SecretAccessKey, AffiliateId, main_aff_id
			
			// find if user makes the setup
			$module_settings = $this->the_plugin->settings();

			$module_mandatoryFields = array(
				'AccessKeyID'			=> false,
				'SecretAccessKey'		=> false,
				'main_aff_id'			=> false
			);
			if ( isset($module_settings['AccessKeyID']) && !empty($module_settings['AccessKeyID']) ) {
				$module_mandatoryFields['AccessKeyID'] = true;
			}
			if ( isset($module_settings['SecretAccessKey']) && !empty($module_settings['SecretAccessKey']) ) {
				$module_mandatoryFields['SecretAccessKey'] = true;
			}
			if ( isset($module_settings['main_aff_id']) && !empty($module_settings['main_aff_id']) ) {
				$module_mandatoryFields['main_aff_id'] = true;
			}
			$mandatoryValid = true;
			foreach ($module_mandatoryFields as $k=>$v) {
				if ( !$v ) {
					$mandatoryValid = false;
					break;
				}
			}
			
			if ( !$mandatoryValid ) {
				$error_number = 1; // from config.php / errors key
				
				$ret['html'] = $this->the_plugin->print_module_error( $this->module, $error_number, 'Error: Unable to use Advanced Search module, yet!' );
				return $ret;
			}
			
			if( !$this->the_plugin->is_woocommerce_installed() ) {  
				$error_number = 2; // from config.php / errors key
				
				$ret['html'] = $this->the_plugin->print_module_error( $this->module, $error_number, 'Error: Unable to use Advanced Search module, yet!' );
				return $ret;
			}
			
			$db_protocol_setting = isset($this->amz_setup['protocol']) ? $this->amz_setup['protocol'] : 'auto';
			if( ( !extension_loaded('soap') && !class_exists("SOAPClient") && !class_exists("SOAP_Client") )
				&& in_array($db_protocol_setting, array('soap')) ) {
				$error_number = 3; // from config.php / errors key
				
				$ret['html'] = $this->the_plugin->print_module_error( $this->module, $error_number, 'Error: Unable to use Advanced Search module, yet!' );
				return $ret;	
			}

			if( !(extension_loaded("curl") && function_exists('curl_init')) ) {  
				$error_number = 4; // from config.php / errors key
				
				$ret['html'] = $this->the_plugin->print_module_error( $this->module, $error_number, 'Error: Unable to use Advanced Search module, yet!' );
				return $ret;
			}
			
			$ret['status'] = true;
			return $ret;
		}
		
		public function printSearchInterface()
		{
			// find if user makes the setup
			$moduleValidateStat = $this->moduleValidation();
			if ( !$moduleValidateStat['status'] || !is_object($this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )) || is_null($this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )) )
				echo $moduleValidateStat['html'];
			else{ 

				WooZone()->print_demo_request();
?>
<style type="text/css">#wwcAmzAff-advanced-search {display: none;}</style>
<?php
	echo WooZone_asset_path( 'css', $this->module['folder_uri'] . 'extra-style.css', false );
	echo WooZone_asset_path( 'js', $this->module['folder_uri'] . 'advanced-search.class.js', false );
?>

<div id="WooZone-advanced-search">
	
	<div id="WooZone-layout-table" border="0" width="100%" cellspacing="0" cellpadding="15">
		
			<table class="col1">
				<ul class="WooZone-categories-list">
					<?php /*<li class="on"><a href="#All" data-categ="All" data-nodeid="All" data-categ_nicename="All"><?php _e('All', $this->the_plugin->localizationName);?></a></li>*/ ?>
					<?php
					$categs = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->getAmazonCategs();
					if( count($categs) > 0 ){
						$ii = 0;
						foreach ($categs as $key => $value){

							$css_ = ! $ii ? ' class="on"' : '';
							if ( 'AllCategories' == $key ) {
								$key = 'All';
							}

							$nodeid = $value['browseNode'];
							$categ_name = $value['department']; //preg_replace('/([A-Z])/', ' $1', $key)
					?>
							<li<?php echo $css_; ?>><a href="#<?php echo $key;?>" data-categ="<?php echo $key;?>" data-nodeid="<?php echo $nodeid;?>" data-categ_nicename="<?php echo $categ_name;?>"><?php echo $categ_name;?></a></li>
					<?php
							$ii++;
						} // end foreach
					}
					?>
				</ul>
			</table>
			<table class="col2">
				<div class="WooZone-parameters-list" id="WooZone-parameters-container"> <p>loading ...</p></div>
			</table>
			<table class="col3">
				<div class="WooZone-product-list"><!-- dinamyc content here --></div>
			</table>
		
	</div>
</div>
<?php
			}
		}
	}
}
// Initalize the your WooZoneAdvancedSearch
//$WooZoneAdvancedSearch = new WooZoneAdvancedSearch($this->cfg, $module);