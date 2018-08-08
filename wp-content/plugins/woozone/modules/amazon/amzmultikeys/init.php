<?php
/**
 * Init Amazon
 * http://www.aa-team.com
 * =======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		0.1
 */

//!defined('ABSPATH') and exit;
if (class_exists('WooZoneMultipleAmazonKeys') != true) {
	class WooZoneMultipleAmazonKeys
	{
		/*
		 * Some required plugin information
		 */
		const VERSION = '1.0';
		
		/*
		 * Store some helpers config             
		 */
		public $module = array();
		public $amz_setup = null;

		public $the_plugin = null;
		public $amzHelper = null;


		/*
		 * Required __construct() function that initalizes the AA-Team Framework
		 */
		public function __construct($module) {
			global $WooZone;
			
			$this->the_plugin = $WooZone;
			$this->amzHelper = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider );

			$this->module = $module;
			$this->amz_setup = $this->the_plugin->settings();
		}

		// setup amazon object for making request
		public function setupAmazonHelper( $params=array() ) {

			//:: GET SETTINGS
			//$settings = $this->the_plugin->settings();
			//$settings = $this->settings;

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

			$this->amzHelper = $this->the_plugin->get_ws_object_new( $this->the_plugin->cur_provider, 'new_helper', array(
				'the_plugin' => $this->the_plugin,
				'params_new' => $params_new,
			));

			if ( is_object($this->amzHelper) ) {
			}
		}

		public function get_ws_object( $provider='amazon', $what='helper' ) {
			//return $this->the_plugin->get_ws_object( $provider, $what );
			$arr = array(
				'amazon'		=> array(
					'helper'		=> $this->amzHelper,
					'ws'			=> is_object($this->amzHelper) ? $this->amzHelper->aaAmazonWS : null,
				),
			);
			return $arr["$provider"]["$what"];
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
		
		public function printSearchInterface() {
			// find if user makes the setup
			//$moduleValidateStat = $this->moduleValidation();
			//if ( !$moduleValidateStat['status'] || !is_object($this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )) || is_null($this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )) )
			//	echo $moduleValidateStat['html'];
			// MAIN ELSE
			//else{
				//WooZone()->print_demo_request();

			$html = array();
			
			$html[] = WooZone_asset_path( 'js', $this->module['folder_uri'] . 'amzmultikeys/amzmultikeys.js', false );

			// !!! CSS is in aa-framework/scss/_amazon.scss
			//$html[] = WooZone_asset_path( 'css', $this->module['folder_uri'] . 'amzmultikeys/amzmultikeys.css', false );

			$html[] = WooZone_asset_path( 'css', $this->the_plugin->cfg['paths']['freamwork_dir_url'] . 'js/jquery.simplemodal/basic.css', false );

			$html[] = "<!-- preload the images --><div style='display:none'><img src='" . $this->the_plugin->cfg['paths']['freamwork_dir_url'] . "js/jquery.simplemodal/x.png' alt='' /></div>";

			echo implode( PHP_EOL, $html );
		?>

<!-- Main Wrapper -->
<div id="WooZone-multikeys" class="panel-body WooZone-panel-body WooZone-form-row __tab1">

	<?php
		// Lang Messages
		$lang = array(
			'loading'                   => __('Loading...', 'WooZone'),
			'closing'                   => __('Closing...', 'WooZone'),
		);
	?>
	<!-- Lang Messages -->
	<div id="WooZone-lang-translation" style="display: none;"><?php echo htmlentities(json_encode( $lang )); ?></div>

	<!-- Templates -->
	<div id="WooZone-multikeys-tpl" style="display: none;">

		<div id="WooZone-tpl-delete-confirm">
			<div class="WooZone-multikeys-boxconfirm">
				<span><?php _e('Are you sure you want to delete this pair of keys?'); ?></span>
				<input type="button" value="Yes" class="WooZone-form-button-small WooZone-form-button-info WooZone-mk-action-delete-yes">
				<input type="button" value="No" class="WooZone-form-button-small WooZone-form-button-danger WooZone-mk-action-delete-no">
			</div>
		</div>

		<div id="WooZone-tpl-add-confirm">
			<div class="WooZone-multikeys-boxconfirm">
				<span><?php _e('This pair of keys don\'t seem to be valid for the current selected amazon location.'); ?></span>
				<br />
				<span><?php _e('Are you sure you want to add this pair of keys?'); ?></span>
				<input type="button" value="Yes" class="WooZone-form-button-small WooZone-form-button-info WooZone-mk-action-addkeys-yes">
				<input type="button" value="No" class="WooZone-form-button-small WooZone-form-button-danger WooZone-mk-action-addkeys-no">
			</div>
		</div>

	</div>

	<!-- Wrapper -->
	<div class="WooZone-multikeys-wrapp">
		<div class="addnewkeys">
			<?php _e('Add Multiple Amazon Keys', 'WooZone'); ?>
		</div>
		<!-- Add New Keys -->
		<div class="WooZone-multikeys-addkeys">
			<input id="AccessKeyID" name="AccessKeyID" type="text" value="" placeholder="<?php _e('ENTER AMAZON AWS ACCESS KEY'); ?>">
			<input id="SecretAccessKey" name="SecretAccessKey" type="text" value="" placeholder="<?php _e('ENTER AMAZON AWS SECRET KEY'); ?>">
			<input type="button" value="Check Amazon AWS Keys" class="WooZone-form-button WooZone-form-button-info WooZone-mk-action-checknew">
			<input type="button" value="Add Amazon AWS Keys" class="WooZone-form-button WooZone-form-button-success WooZone-mk-action-addkeys">
			<div class="status-msg"></div>
		</div>

		<div class="clear-float"></div>

		<!-- Header -->
		<div class="WooZone-multikeys-header">
			<div class="left">
				<?php _e('Amazon Access Keys', 'WooZone'); ?>
			</div>
			<div class="right">
				<input type="button" value="Reload Keys List" class="WooZone-form-button-small WooZone-form-button-info WooZone-mk-action-reload">
			</div>
		</div>
		<div class="clear-float"></div>

		<!-- List Keys Table -->
		<table cellspacing="10" cellpadding="10" class="WooZone-multikeys-table">
			<thead>
				<tr>
					<th>Action</th>
					<th>Amazon Access Key</th>
					<th>Amazon Secret Key</th>
					<th>Total Req</th>
					<th>Success Ratio</th>
					<th>Last Request</th>
				</tr>
			</thead>
			<tbody>
				<?php echo $this->load_available_keys(); ?>
			</tbody>
		</table>

		
		<!-- Footer Text -->
		<div class="WooZone-callout WooZone-callout-secondary"> 
			<?php _e('Unpublished Keys are not used to make requests to Amazon API.', 'WooZone'); ?> 
		</div>

		<div class="WooZone-callout WooZone-callout-success"> 
			<?php _e('Don\'t forget to <a href="#" id="goto-save-settings">Save the Settings</a> (at the bottom) after you finish managing your amazon keys or you\'ve changed any other setting.', 'WooZone'); ?> 
		</div>

		<div class="clear-float"></div>

	</div>
	<!-- End Wrapper -->

</div>
<!-- End Main Wrapper -->

		<?php
			//} // end else
			// END MAIN ELSE
		}



		/**
		 * Main Methods
		 */
		private function load_available_keys( $keys=false ) {

			if ( false === $keys ) {
				$keys = $this->get_available_keys();
			}

			if ( empty($keys) || ! is_array($keys) ) {
				return '';
			}

			$html = array();
			ob_start();

			// loop through available keys
			foreach ( $keys as $key_idx => $key_info ) {

				//:: row
				$key_id = $key_info['id'];
				$key_publish = $key_info['publish'];

				//:: access keys
				$access_key = $key_info['access_key'];
				$secret_key = $key_info['secret_key'];

				//:: last request time
				$last_request_time = $key_info['last_request_time'];
				if ( ! empty($last_request_time) ) {
					$last_request_time = $this->the_plugin->last_update_date('true', strtotime($last_request_time));
				}

				//:: total number of requests
				$nb_requests = (int) $key_info['nb_requests'];

				// success ratio
				$ratio_success = (float) $key_info['ratio_success'];
				$ratio_success_html = $this->the_plugin->build_score_html_container( $ratio_success, array(
					'show_score' 	=> true,
					'css_style'		=> 'style=""',
				));

				//:: last request status
				$status = '';
				if ( isset($key_info['last_request_status']) && ! empty($key_info['last_request_status']) ) {
					$status_stat = $key_info['last_request_status'];
					$status_css = 'valid' == $status_stat ? 'success' : 'error';
					$status = sprintf( '<span class="WooZone-message WooZone-%s">%s</span>', $status_css, $status_stat );
				}
				$last_msg = array();
				$last_msg[] = sprintf( __('<u>Status</u> : %s', 'WooZone'), $status );

				// output
				if ( isset($key_info['last_request_output']) && ! empty($key_info['last_request_output']) ) {
					$output = $key_info['last_request_output'];
					$output = maybe_unserialize( $output );

					$output = isset($output['msg']) ? $output['msg'] : '';
					if ( ! empty($output) ) {
						$last_msg[] = sprintf( __('<u>Message</u> <br /> %s', 'WooZone'), $output );
					}
				}
				// input
				if ( isset($key_info['last_request_input']) && ! empty($key_info['last_request_input']) ) {
					$input = $key_info['last_request_input'];
					$input = maybe_unserialize( $input );

					if ( isset($input['from_file']) ) {
						$last_msg[] = sprintf( __('<u>From File</u> : %s', 'WooZone'), $input['from_file'] );
					}
					if ( isset($input['from_func']) ) {
						$last_msg[] = sprintf( __('<u>From Func</u> : %s', 'WooZone'), $input['from_func'] );
					}

					if ( isset($input['aaAmazonWS'], $input['aaAmazonWS']['requestConfig']) ) {

						$reqparams = array();

						$requestConfig = $input['aaAmazonWS']['requestConfig'];
						if ( is_array($requestConfig) && ! empty($requestConfig) ) {
							foreach ( $requestConfig as $kk => $vv ) {
								$reqparams[] = "$kk : $vv";
							}
						}

						$responseConfig = $input['aaAmazonWS']['responseConfig'];
						if ( is_array($responseConfig) && ! empty($responseConfig) ) {
							if ( isset($responseConfig['country']) ) {
								$reqparams[] = "country : " . $responseConfig['country'];
							}
						}

						$reqparams = implode( '<br />', $reqparams );
						$last_msg[] = sprintf( __('<u>Amazon API Request Main Params</u> <br /> %s', 'WooZone'), $reqparams );
					}
				}

				if ( count($last_msg) > 1 ) {
					$last_msg = implode( '<br /><br />', $last_msg );
					$last_msg = strip_tags($last_msg, '<br><br/><br /><u>');
					$status .= '<a href="#" class="WooZone-simplemodal-trigger" title="' . $last_msg . '"><i class="fa fa-eye-slash"></i></a>';
				}
				?>
					<tr class="item <?php echo 'Y' == $key_publish ? 'published' : ''; ?>" data-itemid="<?php echo $key_id; ?>">
						<td>
							<ul>
								<?php
								// don't add buttons for aateam demo keys
								if ( 1 < $key_id ) {
								?>
								<li>
									<?php
									if ( 'N' == $key_publish ) {
									?>
										<input type="button" value="Publish" class="WooZone-button WooZone-form-button-small WooZone-form-button-success WooZone-mk-action-publish">
									<?php
									}
									else {
									?>
										<input type="button" value="Unpublish" class="WooZone-button WooZone-form-button-small WooZone-form-button-warning WooZone-mk-action-publish">
									<?php
									}
									?>
										<input type="button" value="Delete" class="WooZone-form-button-small WooZone-form-button-danger WooZone-mk-action-delete">
								</li>
								<?php
								}
								?>
								<li>
									<input type="button" value="Check Amazon Keys" class="WooZone-form-button-small WooZone-form-button-info WooZone-mk-action-check">
								</li>
							</ul>
						</td>
						<td>
							<ul>
								<li><?php echo $access_key; ?></li>
							<ul>
						</td>
						<td>
							<ul>
								<li><?php echo $secret_key; ?></li>
							<ul>
						</td>
						<td>
							<?php echo $nb_requests; ?>
						</td>
						<td>
							<?php echo $ratio_success_html; ?>
						</td>
						<td class="WooZone-server-status">
							<ul>
								<li><?php echo $last_request_time; ?></li>
								<li><?php echo $status; ?></li>
							<ul>
						</td>
					</tr>
					<tr class="next <?php echo 'Y' == $key_publish ? 'published' : ''; ?>">
						<td colspan="10"></td>
					</tr>
				<?php
			}
			// end loop through available keys

			$html[] = ob_get_clean();
			$html = implode( PHP_EOL, $html );
			return $html;
		}

		private function get_available_keys() {
			return $this->the_plugin->amzkeysObj->get_available_keys();
		}

		private function publish_key( $id=0 ) {
			$opStatus = $this->the_plugin->amzkeysObj->publish_key( $id );
			$this->choose_default_keys( 'publish' );
			return $opStatus;
		}

		private function delete_key( $id=0 ) {
			$opStatus = $this->the_plugin->amzkeysObj->delete_key( $id );
			$this->choose_default_keys( 'delete' );
			return $opStatus;
		}

		private function check_key( $pms=array() ) {
			$country = '';
			if ( ! isset($pms['country']) || empty($pms['country']) ) {
				$settings = $this->the_plugin->settings();
				$country = $settings['country'];
			}

			$pms = array_replace_recursive(array(
				'id' 				=> 0,
				'country' 			=> $country,
				'AccessKeyID' 		=> '',
				'SecretAccessKey' 	=> '',
			), $pms);
			extract( $pms );

			$ret = array(
				'status' 	=> 'invalid',
				'msg' 		=> '!default message!',
			);

			$access_keys_id = 0;

			if ( $id ) {
				$row = $this->the_plugin->amzkeysObj->get_key_by_id( $id );
				if ( isset($row['id']) ) {
					$access_keys_id = (int) $row['id'];
					$AccessKeyID = $row['access_key'];
					$SecretAccessKey = $row['secret_key'];
				}
			}
			else {
				if ( empty($AccessKeyID) ) {
					return array_replace_recursive($ret, array(
						'msg' 	=> 'Access Key is empty!',
					));
				}
				if ( empty($SecretAccessKey) ) {
					return array_replace_recursive($ret, array(
						'msg' 	=> 'Secret Key is empty!',
					));
				}
			}

			//:: check on amazon
			$this->setupAmazonHelper( array(
				'country' 			=> $country,
				'AccessKeyID' 		=> $AccessKeyID,
				'SecretAccessKey' 	=> $SecretAccessKey,
			));

			$checkStatus = $this->get_ws_object( $this->the_plugin->cur_provider )->check_amazon( 'return', array(
				'access_keys_id' 	=> $access_keys_id,
			));

			return array_replace_recursive($ret, array(
				'status' => $checkStatus['status'],
				'msg' 	=> $checkStatus['msg'],
			));
		}

		private function add_key( $pms=array() ) {
			global $wpdb;

			$country = '';
			if ( ! isset($pms['country']) || empty($pms['country']) ) {
				$settings = $this->the_plugin->settings();
				$country = $settings['country'];
			}

			$pms = array_replace_recursive(array(
				'do_check_amz' 		=> true,
				'country' 			=> $country,
				'AccessKeyID' 		=> '',
				'SecretAccessKey' 	=> '',
			), $pms);
			extract( $pms );

			$ret = array(
				'status' 	=> 'invalid',
				'msg' 		=> '!default message!',
				'check_amz_status' => 'default',
			);

			$access_keys_id = 0;

			if ( empty($AccessKeyID) ) {
				return array_replace_recursive($ret, array(
					'msg' 	=> 'Access Key is empty!',
				));
			}
			if ( empty($SecretAccessKey) ) {
				return array_replace_recursive($ret, array(
					'msg' 	=> 'Secret Key is empty!',
				));
			}

			//:: verify if you try with aateam demo keys
			$demo_keys = $this->the_plugin->get_aateam_demo_keys();
			$demo_keys = isset($demo_keys['pairs']) ? $demo_keys['pairs'] : array();

			foreach ( $demo_keys as $demokey ) {
				if ( ($AccessKeyID == $demokey[0]) && ($SecretAccessKey == $demokey[1]) ) {
					return array_replace_recursive($ret, array(
						'msg' 	=> 'You cannot add an aateam demo keys pair!',
					));
				}
			}

			//:: verify if already exists?
			$row = $this->the_plugin->amzkeysObj->get_key_by_aws( $AccessKeyID, $SecretAccessKey );
			if ( isset($row['id']) ) {
				$access_keys_id = (int) $row['id'];
			}

			if ( ! empty($access_keys_id) ) {
				return array_replace_recursive($ret, array(
					'msg' 	=> 'You already have this combination of ( Access Key, Secret Key )!',
				));
			}

			//:: DO NOT check keys aws status
			if ( ! $do_check_amz ) {

				$insert_id = $this->the_plugin->amzkeysObj->add_key_indb( $AccessKeyID, $SecretAccessKey );
				$this->choose_default_keys( 'add' );

				return array_replace_recursive($ret, array(
					'status' => 'valid',
					'msg' 	=> 'Your combination of ( Access Key, Secret Key ) was added.',
				));
			}
			//:: check keys aws status
			else {
				$checkStatus = $this->check_key( $pms );

				//:: valid pair of keys => try to add it to database
				if ( 'valid' == $checkStatus['status'] ) {

					$insert_id = $this->the_plugin->amzkeysObj->add_key_indb( $AccessKeyID, $SecretAccessKey );
					$this->choose_default_keys( 'add' );

					$msg = $checkStatus['msg'];
					$msg .= '<p>Your combination of ( Access Key, Secret Key ) was added.</p>';

					return array_replace_recursive($ret, array(
						'status' => 'valid',
						'check_amz_status' => 'valid',
						'msg' 	=> $msg,
					));
				}
				//:: invalid pair of keys
				else {
					return array_replace_recursive($ret, array(
						'check_amz_status' => 'invalid',
						'msg' 	=> $checkStatus['msg'],
					));
				}
			}
			return $ret;
		}

		private function choose_default_keys( $operation='' ) {
			global $wpdb;

			$found_keys = array(
				'AccessKeyID' 		=> 'aateam demo access key',
				'SecretAccessKey' 	=> 'aateam demo secret access key',
			);

			//:: try to find best pair of keys
			$table = $wpdb->prefix . 'amz_amzkeys';

			$order_by = 'a.id DESC';
			if ( ('delete' == $operation) || ('publish' == $operation) ) {
				$order_by = "a.ratio_success DESC, a.last_request_time DESC, a.id DESC";
			}
			else if ( 'add' == $operation ) {
				$order_by = "a.id DESC";
			}

			$row = $wpdb->get_row( "SELECT a.id, a.access_key, a.secret_key FROM " . $table . " as a WHERE 1=1 AND a.publish='Y' AND a.id > 1 ORDER BY $order_by LIMIT 1;", ARRAY_A );
			//$row_id = (int) $row['id'];
			if ( is_array($row) && isset($row['id']) ) {
				$found_keys = array(
					'AccessKeyID' 		=> $row['access_key'],
					'SecretAccessKey' 	=> $row['secret_key'],
				);
			}
			//var_dump('<pre>', $found_keys , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			//:: update main plugin settings
			$settings = get_option( $this->the_plugin->alias . '_amazon' ); // 'WooZone_amazon'
			$settings = maybe_unserialize( $settings );
			$settings = !empty($settings) && is_array($settings) ? $settings : array();

			$settings = array_replace_recursive($settings, $found_keys);
			update_option( $this->the_plugin->alias . '_amazon', $settings ); // 'WooZone_amazon'
		}



		/**
		 * Ajax Requests
		 */
		public function ajax_request( $retType='die', $pms=array() ) {
			$requestData = array(
				'action' 	=> isset($_REQUEST['sub_action']) ? (string) $_REQUEST['sub_action'] : '',
				'itemid' 	=> isset($_REQUEST['itemid']) ? (int) $_REQUEST['itemid'] : 0,
			);
			extract($requestData);
			//var_dump('<pre>', $requestData, '</pre>'); die('debug...');

			$ret = array(
				'status'        => 'invalid',
				'html'          => '<div class="WooZone-message WooZone-error"><p>' . __('Invalid action!', 'WooZone') . '</p></div>',
			);


			if ( empty($action)
				|| !in_array($action, array(
					'load_available_keys',
					'publish_key',
					'delete_key',
					'check_key',
					'check_newkey',
					'add_key',
					'add_key_force',
				))
			) {
				die(json_encode($ret));
			}

			$ret['msg'] = 'ok.';

			if ( 'publish_key' == $action ) {
				$opStatus = $this->publish_key( $itemid );
				$ret = array_replace_recursive($ret, array(
					'status'	=> 'valid',
				));
			}
			else if ( 'delete_key' == $action ) {
				$opStatus = $this->delete_key( $itemid );
				$ret = array_replace_recursive($ret, array(
					'status'	=> 'valid',
				));
			}
			else if ( 'check_key' == $action ) {
				$opStatus = $this->check_key( array(
					'id' 			=> $itemid,
					'country' 		=> isset($_REQUEST['country']) ? (string) trim( $_REQUEST['country'] ) : '',
				));
				$ret = array_replace_recursive($ret, $opStatus);
			}
			else if ( 'check_newkey' == $action ) {
				$opStatus = $this->check_key( array(
					'country' 			=> isset($_REQUEST['country']) ? (string) trim( $_REQUEST['country'] ) : '',
					'AccessKeyID' 		=> isset($_REQUEST['AccessKeyID']) ? (string) trim( $_REQUEST['AccessKeyID'] ) : '',
					'SecretAccessKey' 	=> isset($_REQUEST['SecretAccessKey']) ? (string) trim( $_REQUEST['SecretAccessKey'] ) : '',
				));
				$ret = array_replace_recursive($ret, $opStatus);
			}
			else if ( 'add_key' == $action ) {
				$opStatus = $this->add_key( array(
					'country' 			=> isset($_REQUEST['country']) ? (string) trim( $_REQUEST['country'] ) : '',
					'AccessKeyID' 		=> isset($_REQUEST['AccessKeyID']) ? (string) trim( $_REQUEST['AccessKeyID'] ) : '',
					'SecretAccessKey' 	=> isset($_REQUEST['SecretAccessKey']) ? (string) trim( $_REQUEST['SecretAccessKey'] ) : '',
				));
				$ret = array_replace_recursive($ret, $opStatus);
			}
			else if ( 'add_key_force' == $action ) {
				$opStatus = $this->add_key( array(
					'do_check_amz' 		=> false,
					'country' 			=> isset($_REQUEST['country']) ? (string) trim( $_REQUEST['country'] ) : '',
					'AccessKeyID' 		=> isset($_REQUEST['AccessKeyID']) ? (string) trim( $_REQUEST['AccessKeyID'] ) : '',
					'SecretAccessKey' 	=> isset($_REQUEST['SecretAccessKey']) ? (string) trim( $_REQUEST['SecretAccessKey'] ) : '',
				));
				$ret = array_replace_recursive($ret, $opStatus);
			}

			if ( in_array($action, array('load_available_keys', 'publish_key', 'delete_key', 'add_key', 'add_key_force')) ) {
				$opStatus_html = $this->load_available_keys();
				$ret = array_merge($ret, array(
					'html'		=> $opStatus_html,
				));
				if ( ! in_array($action, array('add_key', 'add_key_force')) ) {
					$ret = array_merge($ret, array(
						'status'	=> 'valid',
					));
				}
			}

			if ( $retType == 'return' ) { return $ret; }
			else { die( json_encode( $ret ) ); }
		}
	}
}