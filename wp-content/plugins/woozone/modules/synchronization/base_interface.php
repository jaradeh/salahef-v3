<?php
/*
* Define class WooZoneBaseInterfaceSync
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;
if (class_exists('WooZoneBaseInterfaceSync') != true) {
	class WooZoneBaseInterfaceSync
	{
		/*
		* Store some helpers config
		*/
		public $the_plugin = null;
		public $alias = '';

		private $amz_settings;

		protected $module_folder = '';
		protected $module_folder_path = '';
		protected $module = '';

		static protected $_instance;
		
		protected static $sql_chunk_limit = 2000;
		
		static protected $sync_fields = array();
		static protected $sync_recurrence = array();
		static protected $sync_hour_start = array();
		static protected $sync_products_per_request = array();
		
		static protected $settings = array();
		static protected $sync_options = array();
		
		// pagination
		protected $items = array();
		protected $items_nr = 0;

		public $syncObj = null;


		/*
		 * Required __construct() function that initalizes the AA-Team Framework
		 */
		public function __construct()
		{
			global $WooZone;

			$this->the_plugin = $WooZone;
			$this->alias = $this->the_plugin->alias;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/synchronization/';
			$this->module_folder_path = $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/synchronization/';
			$this->module = isset($this->the_plugin->cfg['modules']['synchronization']) ? $this->the_plugin->cfg['modules']['synchronization'] : array();

			$this->amz_settings = $this->the_plugin->settings();

			require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . '/modules/synchronization/init.php' );
			$this->syncObj = new wwcAmazonSyncronize( $this->the_plugin );

			$this->init_sync_settings();
			$this->init_sync_options();

			// sync options
			self::$sync_fields = $this->the_plugin->get_product_sync_rules();
			self::$sync_recurrence = array(
				 //DEBUG
				//'0.5'       => __('Every 30 minutes', $this->the_plugin->localizationName),
				//1       => __('Every 1 hour', $this->the_plugin->localizationName),

				12      => __('Every 12 hours', $this->the_plugin->localizationName),
				24      => __('Every single day', $this->the_plugin->localizationName),
				48      => __('Every 2 days', $this->the_plugin->localizationName),
				72      => __('Every 3 days', $this->the_plugin->localizationName),
				96      => __('Every 4 days', $this->the_plugin->localizationName),
				120     => __('Every 5 days', $this->the_plugin->localizationName),
				144     => __('Every 6 days', $this->the_plugin->localizationName),
				168     => __('Every 1 week', $this->the_plugin->localizationName),
				336     => __('Every 2 weeks', $this->the_plugin->localizationName),
				504     => __('Every 3 weeks', $this->the_plugin->localizationName),
				720     => __('Every 1 month', $this->the_plugin->localizationName), // ~ 4 weeks + 2 days
			);
			self::$sync_hour_start = $this->the_plugin->doRange( range(0, 23) );
			self::$sync_products_per_request = $this->the_plugin->doRange( range(5, 100, 5) );

			// ajax helper
			add_action('wp_ajax_WooZoneSyncAjax', array( $this, 'ajax_request' ));
		}

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

			self::$settings = $ss;
			return self::$settings;
		}
		
		public function init_sync_options() {
			$ss = get_option($this->alias . '_sync_options', array());
			$ss = maybe_unserialize($ss);
			$ss = $ss !== false ? $ss : array();
			$ss = array_merge(array(
				'interface_max_products' => 'all',
			), $ss);

			self::$sync_options = $ss;
			return self::$sync_options;
		}

		/**
		 * Singleton pattern
		 *
		 * @return WooZoneBaseInterfaceSync Singleton instance
		 */
		static public function getInstance()
		{
			if (!self::$_instance) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		/*
		 * printBaseInterface, method
		 * --------------------------
		 *
		 * this will add the base DOM code for you options interface
		 */
		public function printBaseInterface( $module='synchronization' ) {
			global $wpdb;
			
			$ss = self::$settings;

			$mod_vars = array();

			// Sync
			$mod_vars['mod_menu'] = 'info|synchronization_log';
			$mod_vars['mod_title'] = __('Synchronization logs', $this->the_plugin->localizationName);

			// Products Stats
			if ( $module == 'stats_prod' ) {
				$mod_vars['mod_menu'] = 'info|stats_prod';
				$mod_vars['mod_title'] = __('Products stats', $this->the_plugin->localizationName);
			}
			extract($mod_vars);

			$module_data = $this->the_plugin->cfg['modules']["$module"];
			$module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . "modules/$module/";
?>

		<?php
		echo WooZone_asset_path( 'js', $this->module_folder . 'app.synchronization.js', false );
		//echo WooZone_asset_path( 'css', $this->module_folder . 'css/sync-log.css', false );
		?>

		<!-- simplemodal -->
		<?php echo WooZone_asset_path( 'css', $this->the_plugin->cfg['paths']['freamwork_dir_url'] . 'js/jquery.simplemodal/basic.css', false ); ?>
		<!-- preload the images -->
		<div style='display:none'><img src='<?php echo $this->the_plugin->cfg['paths']['freamwork_dir_url'] . "js/jquery.simplemodal/x.png"; ?>' alt='' /></div>
		
		<div id="<?php echo WooZone()->alias?>">
			<div id="WooZone-wrapper" class="<?php echo WooZone()->alias?>-content">
				
				<?php
				// show the top menu
				WooZoneAdminMenu::getInstance()->make_active($mod_menu)->show_menu(); 
				?>
				
				<!-- Content -->
				<section class="WooZone-main">
					
					<?php 
					echo WooZone()->print_section_header(
						$module_data["$module"]['menu']['title'],
						$module_data["$module"]['description'],
						$module_data["$module"]['help']['url']
					);
					?>
					
					<div class="panel panel-default WooZone-panel">
						
<?php
	if ( !WooZone()->can_import_products() ) {
		echo '<div class="panel-body WooZone-panel-body">';
		echo 	WooZone()->demo_products_import_end_html();
		echo '</div>';
	}
	else if ( WooZone()->is_aateam_demo_keys() && ! WooZone()->is_aateam_devserver() ) {
		echo '<div class="panel-body WooZone-panel-body">';
		echo 	WooZone()->demo_products_import_end_html(array(
			'is_block_demo_keys'	=> true,
		));
		echo '</div>';
	}
	else {
?>

						<div class="panel-heading WooZone-panel-heading">
							<h2><?php echo $mod_title; ?></h2>
						</div>
						
						<div class="panel-body WooZone-panel-body">
	
							<!-- Content Area -->
							<div id="WooZone-content-area">
								<div class="WooZone-grid_4">
									<div class="WooZone-panel">
										<div id="WooZone-sync-log" class="WooZone-panel-content" data-module="<?php echo $module; ?>">
	
											<?php
											   $lang = array(
												   'no_products'          => __('No products available.', 'WooZone'),
												   'sync_now'             => __('Sync now (<span>{nb}</span> remained)', 'WooZone'),
												   'sync_now_msgformat'   => __('(ASIN: {1} / ID: #{2}): {3}', 'WooZone'),
												   'sync_now_finished'    => __('Sync now is finished.', 'WooZone'),
												   'sync_now_inwork'      => __('Sync all now in progress. Please be patient till it\'s finished.', 'WooZone'),
												   'sync_now_stop_btn'    => __('stop processing.', 'WooZone'),
												   'sync_now_stopped'     => __('Sync now is stopped.', 'WooZone'),
												   'sync_now_stopping'     => __('Sync now will be stopped...', 'WooZone'),
												   'loading'              => __('Loading..', 'WooZone'),
											   );
											?>
											<div id="WooZone-lang-translation" style="display: none;"><?php echo htmlentities(json_encode( $lang )); ?></div>
	
											<!-- Main loading box >
											<div id="WooZone-main-loading">
												<div id="WooZone-loading-overlay"></div>
												<div id="WooZone-loading-box">
													<div class="WooZone-loading-text"><?php _e('Loading', $this->the_plugin->localizationName);?></div>
													<div class="WooZone-meter WooZone-animate" style="width:86%; margin: 34px 0px 0px 7%;"><span style="width:100%"></span></div>
												</div>
											</div-->
				
											<!--<div class="WooZone-sync-filters">
												<select>
													<option>Show All</option>
													<option>Show None</option>
													<option>Show What</option>
												</select>   
												<select>
													<option>Show All</option>
													<option>Show None</option>
													<option>Show What</option>
												</select>
												<a>Published <span class="count">(27)</span></a>
											</div>-->

											<?php if ( $module == 'synchronization' ) { ?>
												<div class="WooZone-sync-stats" data-what="mainstats">
												  <h3><?php _e('Synchronisation Cronjob Stats', $this->the_plugin->localizationName);?></h3>
												  <?php echo $this->sync_stats(); ?>
												</div>
											<?php } ?>

											<?php if ( $module == 'synchronization' ) { ?>
												<div class="WooZone-sync-info">

													<h3><?php _e('Synchronisation Settings', $this->the_plugin->localizationName);?></h3>
													<?php echo $this->sync_settings(array()); ?>

													<?php echo $this->get_pagination(array(
														'position' 		=> 'top',
														'with_wrapp' 	=> true,
													)); ?>

													<div class="WooZone-panel-sync-all"></div>
													<div class="WooZone-sync-inprogress WooZone-sync-inprogress-top"></div>

												</div>
											<?php } else { ?>
												<div class="WooZone-sync-info WooZone-box-stats">
													<?php echo $this->get_pagination(array(
														'position' 		=> 'top',
														'with_wrapp' 	=> true,
														'filterby' 		=> false,
													)); ?>
												</div>
											<?php } ?>


											<div class="WooZone-sync-filters">
												<span>
													<?php _e('Total products', $this->the_plugin->localizationName);?>: <span class="wzone_count_total_products"></span> (<span class="wzone_countv"></span> variations)
													<!-- | <?php _e('Synchronized products', $this->the_plugin->localizationName);?>: <span class="count">(27)</span>-->
												</span>
												<span class="right">
													<?php if ( $module == 'synchronization' ) { ?>
														<label for="sync_stop_reload"><?php _e('stop auto reload', $this->the_plugin->localizationName); ?></label>
														<input type="checkbox" name="sync_stop_reload" id="sync_stop_reload"<?php echo isset($ss['sync_stop_reload']) && !empty($ss['sync_stop_reload']) ? ' checked="checked"' : ''; ?>/>
														<strong>0</strong> <?php _e('seconds', $this->the_plugin->localizationName); ?>
													<?php } ?>
													<button class="load_prods"><?php _e('Reload products list', $this->the_plugin->localizationName);?></button>
													<?php if ( $module == 'synchronization' ) { ?>
													<button class="sync-all"><?php _e('Sync all now', $this->the_plugin->localizationName);?></button>
													<?php } ?>
												</span>
											</div>

											<div class="WooZone-sync-table <?php echo ( $module == 'synchronization' ? 'synchronization' : 'stats_prod' ); ?>">
											  <table cellspacing="0">
												<thead>
													<tr class="WooZone-sync-table-header">
														<?php if ( $module == 'synchronization' ) { ?>
														<th style=""><i class="fa fa-flag" title="<?php _e('SYNC STATUS', $this->the_plugin->localizationName);?>"></i></th>
														<th style="width:19.31%;"><?php _e('Image', $this->the_plugin->localizationName);?></th>
														<th style="width:48.44%;"><?php _e('Title', $this->the_plugin->localizationName);?></th>
														<th style="width:15.83%;"><?php _e('Sync Stats', $this->the_plugin->localizationName);?></th>
														<th style="width:8.19%;" class="wz-uppercase"><?php _e('Last Sync', $this->the_plugin->localizationName);?></th>
														<th style="width:8.46%;"><?php _e('Action', $this->the_plugin->localizationName);?></th>
														<?php } else { ?>
														<th style="width:3%;"><?php _e('ID', $this->the_plugin->localizationName);?></th>
														<th style="width:16%;"><?php _e('Image', $this->the_plugin->localizationName);?></th>
														<th style="width:40%;"><?php _e('Title', $this->the_plugin->localizationName);?></th>
														<th style="width:10%;"><?php _e('Bitly', $this->the_plugin->localizationName);?></th>
														<th style="width:7%;"><?php _e('Hits', $this->the_plugin->localizationName);?></th>
														<th style="width:7%;"><?php _e('Added to cart', $this->the_plugin->localizationName);?></th>
														<th style="width:7%;" class="wz-uppercase"><?php _e('Redirected to Amazon', $this->the_plugin->localizationName);?></th>
														<th style="width:10%;"><?php _e('Date Added', $this->the_plugin->localizationName);?></th>
														<?php } ?>
													</tr>
												</thead>
												<tbody>
												<?php
													//require_once( $this->module_folder_path . '_html.php');
												?>
												</tbody>
											  </table>
											</div>
											<!-- end WooZone-sync-table -->


											<?php if ( $module == 'synchronization' ) { ?>
												<div class="WooZone-sync-info">

													<div class="WooZone-panel-sync-all"></div>
													<div class="WooZone-sync-inprogress WooZone-sync-inprogress-bottom"></div>

													<?php echo $this->get_pagination(array(
														'position' 		=> 'bottom',
														'with_wrapp' 	=> true,
													)); ?>

													<h3><?php _e('Synchronisation Settings', $this->the_plugin->localizationName);?></h3>
													<?php echo $this->sync_settings(array('position' => 'bottom')); ?>

												</div>
											<?php } else { ?>
												<div class="WooZone-sync-info WooZone-box-stats">
													<?php echo $this->get_pagination(array(
														'position' 		=> 'bottom',
														'with_wrapp' 	=> true,
														'filterby' 		=> false,
													)); ?>
												</div>
											<?php } ?>

										</div>
									</div>
								</div>
								<div class="clear"></div>
							</div>
						</div>

<?php } // end demo keys ?>

					</div>
				</section>
			</div>
		</div>

<?php
		}

		protected function sync_settings( $pms=array() ) {
			extract($pms);
			ob_start();

			$ss = self::$settings;
?>
										<form class="WooZone-sync-settings">
										  <p><?php _e('Each product has to sync the following', $this->the_plugin->localizationName);?>:
											<?php
											foreach (self::$sync_fields as $key => $val) {
												$is_checked = 'checked="checked"';
												if ( !isset($ss['sync_fields'])
													|| ( isset($ss['sync_fields']) && !in_array($key, $ss['sync_fields']) ) ) {
													$is_checked = '';
												}
											?>
											<span>
											<label for="sync_fields[<?php echo $key; ?>]"><?php echo $val; ?></label>:
											<input type="checkbox" id="sync_fields[<?php echo $key; ?>]" name="sync_fields[<?php echo $key; ?>]" <?php echo $is_checked; ?> />
											</span>
											<?php
											}
											?>
										  </p>
										  <p>
											<!--Recurrence : <span>24h</span>
											First start a hour <span>10</span>-->
											<span>
											<?php _e('Recurrence', $this->the_plugin->localizationName);?>:
											</span>
											<select id="sync_recurrence" name="sync_recurrence" class="WooZone-filter-general_field">
											<?php
											foreach (self::$sync_recurrence as $key => $val) {
												$is_checked = '';
												if ( isset($ss['sync_recurrence']) && $key == $ss['sync_recurrence'] ) {
													$is_checked = 'selected="selected"';
												}
											?>
												<option value="<?php echo $key; ?>" <?php echo $is_checked; ?>><?php echo $val; ?></option>
											<?php
											}
											?>
											</select>
											
											<span>
											<?php _e('Products per request', $this->the_plugin->localizationName);?>:
											</span>
											<select id="sync_products_per_request" name="sync_products_per_request" class="WooZone-filter-general_field">
											<?php
											foreach (self::$sync_products_per_request as $key => $val) {
												$is_checked = '';
												if ( isset($ss['sync_products_per_request']) && $key == $ss['sync_products_per_request'] ) {
													$is_checked = 'selected="selected"';
												}
											?>
												<option value="<?php echo $key; ?>" <?php echo $is_checked; ?>><?php echo $val; ?></option>
											<?php
											}
											?>
											</select>
											
											<?php /*
											<span>
											<?php _e('First start at hour', $this->the_plugin->localizationName);?>:
											</span>
											<select id="sync_hour_start" name="sync_hour_start">
											<?php
											foreach (self::$sync_hour_start as $key => $val) {
												$is_checked = '';
												if ( isset($ss['sync_hour_start']) && $key == $ss['sync_hour_start'] ) {
													$is_checked = 'selected="selected"';
												}
											?>
												<option value="<?php echo $key; ?>" <?php echo $is_checked; ?>><?php echo $val; ?></option>
											<?php
											}
											?>
											</select>
											*/ ?>
										  </p>
										  <p>
											  <button><?php _e('Save settings', $this->the_plugin->localizationName);?></button>
										  </p>
										</form>
										
										<?php //echo $this->get_pagination($pms); ?>
<?php
			return ob_get_clean();
		}

		protected function sync_stats_get() {
			$ss = self::$settings;

			$max_retries = $this->the_plugin->ss['max_cron_sync_retries_onerror'];
			$sync_products_per_request = (int) $ss['sync_products_per_request'];
			$recurrence = $ss['sync_recurrence'];
			$recurrence_sec = (int) ( $recurrence * 3600 );

			//:: sync stats
			$optionsList = array(
				'WooZone_sync_cycle_stats' => array(),
				'WooZone_sync_last_updated_product' => 0,
				'WooZone_sync_last_selected_product' => 0,
				'WooZone_sync_first_updated_date' => false,

				'WooZone_sync_currentlist_last_product' => 0,
				'WooZone_sync_currentlist_nb_products' => 0,
				'WooZone_sync_currentlist_nb_parsed' => array(),
				'WooZone_sync_currentlist_prod_trashed' => array(),
				'WooZone_sync_currentlist_prod_trash_tries' => array(),

				'WooZone_sync_witherror_last_updated_product' => 0,
				'WooZone_sync_witherror_last_selected_product' => 0,
				'WooZone_sync_witherror_nb_products' => 0,
				'WooZone_sync_witherror_nb_parsed' => array(),
				'WooZone_sync_witherror_tries' => 0,
			);
			foreach ($optionsList as $opt_key => $opt_val) {
				$opt_key_ = str_replace('WooZone_', '', $opt_key);
				$$opt_key_ = get_option( $opt_key, $opt_val );
			}

			$sync_currentlist_nb_parsed = isset($sync_currentlist_nb_parsed['nb'])
				? $sync_currentlist_nb_parsed['nb'] : 0;
			$sync_witherror_nb_parsed = isset($sync_witherror_nb_parsed['nb'])
				? $sync_witherror_nb_parsed['nb'] : 0;

			$report_last_date = get_option('WooZone_report_last_date', false); // last report


			//:: current sync cycle/ find duration of the cron
			$sync_start_time = $sync_first_updated_date;

			$sync_duration = $sync_cycle_stats;
			$sync_duration2 = 0;
			if ( isset($sync_duration['start_time']) && ! empty($sync_duration['start_time']) ) {
				$sync_start_time = $sync_duration['start_time'];
			}
			if ( ! isset($sync_duration['end_time']) || empty($sync_duration['end_time']) ) {
				$sync_duration['end_time'] = time();
			}
			if ( isset($sync_duration['start_time'], $sync_duration['end_time'])
				&& ! empty($sync_duration['start_time'])
				&& $sync_duration['end_time'] > $sync_duration['start_time'] ) {
				//$sync_duration2 = $sync_duration['end_time'] - $sync_duration['start_time'];
				$sync_duration2 = $this->time_since( (int) $sync_duration['start_time'], (int) $sync_duration['end_time']);
			}


			//:: current sync cycle/ cron status & text
			$sync_status = 0; // in progress
			$sync_status_text = __('in progress', $this->the_plugin->localizationName);
			if ( empty($sync_currentlist_last_product) ) {

				$sync_status = 2; // not initialized yet.
				$sync_status_text = __('to be initialized', $this->the_plugin->localizationName);
			} else if ( $sync_last_updated_product >= $sync_currentlist_last_product ) {

				$sync_status = 1; // success
				$sync_status_text = __('completed', $this->the_plugin->localizationName);                
			} else if ( $sync_last_selected_product >= $sync_currentlist_last_product ) {

				$sync_status = 1; // success
				$sync_status_text = __('completed', $this->the_plugin->localizationName);                
			}


			//:: next sync cycle/ estimated time to sync all products in the cycle based on products number and products per request setting
			$nextsync_start_time = !empty($sync_start_time) ? $sync_start_time + $recurrence_sec : false;

			if ( !empty($sync_currentlist_nb_products) && !empty($sync_products_per_request) ) {

				$nextsync_start_time2 = ceil( $sync_currentlist_nb_products / $sync_products_per_request );
				// 2 minutes * 60 seconds per minute - WooZone_sync_products
				$nextsync_start_time2 = $nextsync_start_time2 * 2 * 60;
				$nextsync_start_time2 = $sync_start_time + $nextsync_start_time2;
				//var_dump('<pre>', $recurrence_sec, $nextsync_start_time, $nextsync_start_time2 , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				if ( $nextsync_start_time2 > $nextsync_start_time ) {
					$nextsync_start_time = $nextsync_start_time2;
				}
			}


			//:: current sync cycle/ remained products to be synced
			//$sync_nb_all_products = $this->syncObj->select_products( array(
			//	'count' => true,
			//	'filterby_last_updated_product' => false,
			//));
			$sync_nb_remained_products = $this->syncObj->select_products( array(
				'count' => true,
			));
			$sync_nb_all_products = $sync_currentlist_nb_products;
			//$sync_nb_remained_products = (int) ( $sync_nb_all_products - $sync_currentlist_nb_parsed );

			$opparsed = $this->sync_find_parsed_percent( array(
				'witherror' => false,
				'step' => 1,
				'sync_status' => $sync_status,
				'sync_nb_remained_products' => $sync_nb_remained_products,
				'sync_nb_all_products' => $sync_nb_all_products
			));
			extract( $opparsed );


			//:: current sync cycle - with error ones/ remained products to be synced
			$parsed_percent_witherror = '0';
			$sync_witherror_nb_remained_products = $sync_nb_all_products;
			$sync_witherror_nb_all_products = $sync_nb_all_products;

			$sync_witherror_showmsg = false;

			if ( $sync_witherror_tries ) {

				//$sync_witherror_nb_all_products = $this->syncObj->select_products( array(
				//	'witherror' => true,
				//	'count' => true,
				//	'filterby_last_updated_product' => false,
				//));
				$sync_witherror_nb_remained_products = $this->syncObj->select_products( array(
					'witherror' => true,
					'count' => true,
				));
				$sync_witherror_nb_all_products = $sync_witherror_nb_products;
				//$sync_witherror_nb_remained_products = (int) ( $sync_witherror_nb_all_products - $sync_witherror_nb_parsed );

				$opparsed = $this->sync_find_parsed_percent( array(
					'witherror' => true,
					'step' => $sync_witherror_tries,
					'max_retries' => $max_retries,
					'sync_status' => $sync_status,
					'sync_nb_remained_products' => $sync_witherror_nb_remained_products,
					'sync_nb_all_products' => $sync_witherror_nb_all_products,
				));
				extract( $opparsed );

				$sync_witherror_showmsg = true;
			}
			else {
				// success main sync process
				if ( 1 == $sync_status ) {
					$sync_witherror_showmsg = true;
					$text_sync_prods_witherror = sprintf( __('We\'ll begin the process to re-sync amazon error (ex. throttled) ones shortly', $this->the_plugin->localizationName) );
				}
			}


			//:: deleted / moved to trash - products in this cycle
			$current_prods_trashed = $sync_currentlist_prod_trashed;
			$current_prods_trashed_nb = count($current_prods_trashed);

			$percent_trashed = '0';
			if ( $current_prods_trashed_nb && $sync_nb_all_products ) {
				$percent_trashed = ( $current_prods_trashed_nb * 100 ) / $sync_nb_all_products;
				$percent_trashed = floor( $percent_trashed );
				$percent_trashed = number_format($percent_trashed, 0);
			}

			//:: marked as not found (sync tries) - products in this cycle
			$current_prods_notfound = $sync_currentlist_prod_trash_tries;
			$current_prods_notfound_nb = count($current_prods_notfound);

			$percent_notfound = '0';
			if ( $current_prods_notfound_nb && $sync_nb_all_products ) {
				$percent_notfound = ( $current_prods_notfound_nb * 100 ) / $sync_nb_all_products;
				$percent_notfound = floor( $percent_notfound );
				$percent_notfound = number_format($percent_notfound, 0);
			}

			return compact(
				'report_last_date',
				'recurrence',
				'recurrence_sec',
				'sync_start_time',
				'sync_duration', 'sync_duration2',
				'sync_currentlist_last_product',
				'sync_last_updated_product',
				'sync_last_selected_product',
				'sync_status', 'sync_status_text',
				'nextsync_start_time', 'nextsync_start_time2',
				'sync_currentlist_nb_products',
				'sync_products_per_request',
				'sync_nb_remained_products',
				'sync_nb_all_products',
				'text_sync_prods',
				'parsed_percent',
				'current_prods_trashed', 'current_prods_trashed_nb', 'percent_trashed',
				'current_prods_notfound', 'current_prods_notfound_nb', 'percent_notfound',

				'sync_witherror_tries',
				'sync_witherror_nb_remained_products',
				'sync_witherror_nb_all_products',
				'parsed_percent_witherror',
				'sync_witherror_showmsg',
				'text_sync_prods_witherror'
			);
		}

		protected function sync_stats_build_html() {
			extract( $this->sync_stats_get() );
			$ss = self::$settings;

			ob_start();
?>

<div class="WooZone-sync-cycle-header">
	<ul>
		<li>
			<?php
				echo __('Started on: ', $this->the_plugin->localizationName);
				if ( !empty($sync_start_time) ) {
					echo '<span>' . $this->the_plugin->last_update_date('true', $sync_start_time) . '</span>';
				}
			?>
		</li>
		<li>
			<?php
				echo __('Current Sync Cycle: ', $this->the_plugin->localizationName);
				echo '<span class="WooZone-sync-cycle-main-status WooZone-message ' . ( $sync_status == 1 ? 'WooZone-success' : 'WooZone-info' ) . '">' . $sync_status_text . '</span>';
			?>
		</li>
		<li>
			<?php
				echo __('Duration: ', $this->the_plugin->localizationName);
				if ( !empty($sync_duration2) ) {
					echo '<span>' . $sync_duration2 . '</span>';
				}
			?>
		</li>
	</ul>
</div>

<div class="WooZone-sync-process-progress-bar im-products">
	<div class="WooZone-sync-process-progress-marker" style="width: <?php echo $parsed_percent; ?>%;"></div>
	<div class="WooZone-sync-process-progress-percent">
		<div class="WooZone-sync-process-progress-circle-wrapp">
			<div class="WooZone-sync-process-progress-circle">
				<span><?php echo $parsed_percent; ?>%</span>
			</div>
		</div>
		<div class="WooZone-sync-process-progress-info">
			<div>
				<div>
					<div>
						<?php
							echo sprintf( __('%d remained to be synced from %d total items', $this->the_plugin->localizationName), $sync_nb_remained_products, $sync_nb_all_products );
						?>
					</div>
					<div>
						<?php
							echo sprintf( __('ID Last item in cycle: %d', $this->the_plugin->localizationName), $sync_currentlist_last_product );
						?>
					</div>
					<div>
						<?php
							echo sprintf( __('ID Last synced item: %d', $this->the_plugin->localizationName), max( $sync_last_updated_product, $sync_last_selected_product ) );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php /*<div class="WooZone-sync-process-progress-text">
		<span><?php _e('Progress', $this->the_plugin->localizationName); ?>: <span>0%</span></span>
		<span><?php _e('Parsed', $this->the_plugin->localizationName); ?>: <span></span></span>
		<span><?php _e('Elapsed time', $this->the_plugin->localizationName); ?>: <span></span></span>
	</div>*/ ?>
</div>

<?php if ( $sync_witherror_showmsg ) { ?>
<div class="WooZone-sync-process-progress-bar im-witherror">
	<div class="WooZone-sync-process-progress-marker" style="width: <?php echo $parsed_percent_witherror; ?>%;"></div>
	<div class="WooZone-sync-process-progress-text">
		<span>
		<?php 
			echo $text_sync_prods_witherror;
		?>
		</span>
	</div>
</div>
<?php } ?>

<?php if ( $current_prods_trashed_nb ) { ?>
<div class="WooZone-sync-process-progress-bar im-trash">
	<div class="WooZone-sync-process-progress-marker" style="width: <?php echo $percent_trashed; ?>%;"></div>
	<div class="WooZone-sync-process-progress-text">
		<span>
		<?php echo sprintf( __('Number of items moved in trash (or deleted): %s', $this->the_plugin->localizationName), '<span>' . $current_prods_trashed_nb . '</span>' ); ?>
		</span>
	</div>
</div>
<?php } ?>

<?php if ( $current_prods_notfound_nb ) { ?>
<div class="WooZone-sync-process-progress-bar im-notfound">
	<div class="WooZone-sync-process-progress-marker" style="width: <?php echo $percent_notfound; ?>%;"></div>
	<div class="WooZone-sync-process-progress-text">
		<span>
		<?php echo sprintf( __('Number of items marked as not found: %s', $this->the_plugin->localizationName), '<span>' . $current_prods_notfound_nb . '</span>' ); ?>
		</span>
	</div>
</div>
<?php } ?>

<div class="WooZone-sync-cycle-footer">
	<ul>
		<li><?php _e('products added after the current cycle started, are not included in the syncing process', $this->the_plugin->localizationName); ?></li>
		<li><?php _e('item = product (simple or variable) or just a variation', $this->the_plugin->localizationName); ?></li>
	</ul>
</div>

<?php
			return ob_get_clean();
		}

		protected function sync_stats() {
			extract( $this->sync_stats_get() );
			$ss = self::$settings;

			ob_start();

?>
			<table>
				<thead>
				</thead>
				<tfoot>
				</tfoot>
				<tbody>
					<tr>
						<td colspan="2">
							<?php echo $this->sync_stats_build_html(); ?>
						</td>
					</tr>
					<tr class="WooZone-sync-info-next-cycle">
						<td width="75%">
							<span class="title"><?php _e('Next Sync Cycle', $this->the_plugin->localizationName);?></span>
							<ul>
								<?php if ( !empty($nextsync_start_time) ) { ?>
								<li>
									<?php _e('Estimated Start time', $this->the_plugin->localizationName);?>:
									<span><?php
										echo $this->the_plugin->last_update_date('true', $nextsync_start_time);
									?></span><br />
									<?php _e('depends on last sync cycle start time and on (recurrence and products per request) synchronisation settings', $this->the_plugin->localizationName);?>
								</li>
								<?php } else { ?>
								<li>
									<?php _e('not available yet.', $this->the_plugin->localizationName);?>
								</li>
								<?php } ?>
							</ul>
						</td>
						<td>
							<span class="title"><?php _e('Last Report', $this->the_plugin->localizationName);?></span>
							<ul>
								<?php if ( !empty($report_last_date) ) { ?>
								<li>
									<?php _e('Generation date', $this->the_plugin->localizationName);?>:
									<span><?php
										echo $this->the_plugin->last_update_date('true', $report_last_date);
									?></span>
								</li>
								<?php } else { ?>
								<li>
									<?php _e('not available yet.', $this->the_plugin->localizationName);?>
								</li>
								<?php } ?>
							</ul>
						</td>
					</tr>
				</tbody>
			</table>
<?php
			return ob_get_clean();
		}

		protected function get_products( $pms=array() ) {
			global $wpdb;

			$DEBUG = true;

			$prod_key = '_amzASIN';

			$pms = array_merge(array(
				// synchronization | stats_prod | speed_optimization
				'module'				=> 'synchronization',

				'paged'					=> 1,
				'posts_per_page'		=> '10',
				'filterby_sync_status' 	=> '',
				'searchby_what' 		=> '',
				'searchby_value' 		=> '',
			), $pms);
			$pms['searchby_value'] = trim($pms['searchby_value']);
			extract($pms);

			$ret = array('status' => 'valid', 'html' => array(), 'nb' => 0, 'nbv' => 0);
			$nbprod = 0;
			$nbprodv = 0;

			// filterby_sync_status
			$filterby_clause = '';
			if ( ! empty($filterby_sync_status) ) {
				if ( 'neversynced' == $filterby_sync_status ) {
					$filterby_clause = "isnull(pm2.meta_value) OR pm2.meta_value = ''";
					$filterby_clause_sub = "isnull(pmsub2.meta_value) OR pmsub2.meta_value = ''";
				}
				else {
					$filterby_clause = "pm2.meta_value = '$filterby_sync_status'";
					$filterby_clause_sub = "pmsub2.meta_value = '$filterby_sync_status'";
				}
			}

			// searchby
			$searchby_clause = '1=1';
			$searchby_clause_sub = '1=1';
			if ( '' != $searchby_value && in_array($searchby_what, array('post_id', 'asin', 'post_title')) ) {

				$searchby_value_esc = esc_sql( $searchby_value);
				switch ($searchby_what) {
					case 'post_id':
						$searchby_clause = "p.ID = '$searchby_value_esc'";
						$searchby_clause_sub = "psub.ID = '$searchby_value_esc'";
						break;

					case 'asin':
						$searchby_clause = "pm.meta_value = '$searchby_value_esc'";
						$searchby_clause_sub = "pmsub.meta_value = '$searchby_value_esc'";
						break;

					case 'post_title':
						$searchby_clause = "p.post_title regexp '$searchby_value_esc'";
						$searchby_clause_sub = "psub.post_title regexp '$searchby_value_esc'";
						break;
				}
			}

			//$max_prods = $this->get_interface_max_products();
			//$q_limit = $max_prods !== 'all' ? "LIMIT 0, $max_prods" : '';
			$q_limit = '';
			
			//$posts_per_page = 1; //DEBUG
			
			if ( $module == 'speed_optimization' ) {
				$__limitClause = 'ORDER BY p.ID DESC';
			}else{
				$__limitClause = 'ORDER BY p.ID ASC';
			}
			
			$__limitClause .= $posts_per_page!='all' && $posts_per_page>0
				? " LIMIT " . (($paged - 1) * $posts_per_page) . ", " . $posts_per_page : '';

			$totalVariations = 0;
			if ( $module != 'speed_optimization' ) {
				// total variations
				if ( ! empty($filterby_clause) ) {
					$sqlTotalVar = "
					SELECT count(p.ID) as nb
					FROM $wpdb->posts as p
					RIGHT JOIN $wpdb->postmeta as pm ON p.ID = pm.post_id AND pm.meta_key='$prod_key'
					LEFT JOIN $wpdb->posts as p2 ON p.post_parent = p2.ID
					LEFT JOIN $wpdb->postmeta as pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_amzaff_sync_last_status'
					WHERE 1=1
						AND p.post_parent > 0
						AND p.post_type = 'product_variation'
						AND ( !isnull(p.ID) AND p.post_status = 'publish' )
						AND ( !isnull(p2.ID) AND p2.post_status = 'publish' )
						AND ( $filterby_clause AND $searchby_clause )
					;";
				}
				else {
					$sqlTotalVar = "
					SELECT count(p.ID) as nb
					FROM $wpdb->posts as p
					RIGHT JOIN $wpdb->postmeta as pm ON p.ID = pm.post_id AND pm.meta_key='$prod_key'
					LEFT JOIN $wpdb->posts as p2 ON p.post_parent = p2.ID
					WHERE 1=1
						AND p.post_parent > 0
						AND p.post_type = 'product_variation'
						AND ( !isnull(p.ID) AND p.post_status = 'publish' )
						AND ( !isnull(p2.ID) AND p2.post_status = 'publish' )
						AND $searchby_clause
					;";
				}
				//var_dump('<pre>', $sqlTotalVar , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				$totalVariations = (int) $wpdb->get_var( $sqlTotalVar );
			}

			// get products (simple or just the parents without their variation childs)
			$__fields = 'p.ID, p.post_title, p.post_parent, p.post_date, pm.post_id, pm.meta_value';

			if ( ! empty($filterby_clause) ) {
				$sqlTpl = "
				SELECT {FIELDS}
				FROM $wpdb->posts as p
				RIGHT JOIN $wpdb->postmeta as pm ON p.ID = pm.post_id AND pm.meta_key='$prod_key'
				LEFT JOIN $wpdb->postmeta as pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_amzaff_sync_last_status'
				WHERE 1=1
					AND p.post_parent = 0
					AND p.post_type = 'product'
					AND ( !isnull(p.ID) AND p.post_status = 'publish' )
					AND (
						( $filterby_clause AND $searchby_clause )
						OR
						( 0 < (
							SELECT count(psub.ID)
							FROM $wpdb->posts as psub
							RIGHT JOIN $wpdb->postmeta as pmsub ON psub.ID = pmsub.post_id AND pmsub.meta_key='$prod_key'
							LEFT JOIN $wpdb->postmeta as pmsub2 ON psub.ID = pmsub2.post_id AND pmsub2.meta_key = '_amzaff_sync_last_status'
							WHERE 1=1 
								AND psub.post_parent = p.ID
								AND psub.post_type = 'product_variation'
								AND psub.post_status = 'publish'
								AND ( $filterby_clause_sub AND $searchby_clause_sub )
						) )
					)
				{LIMIT};";
			}
			else if ( '1=1' != $searchby_clause ) {
				$sqlTpl = "
				SELECT {FIELDS}
				FROM $wpdb->posts as p
				RIGHT JOIN $wpdb->postmeta as pm ON p.ID = pm.post_id AND pm.meta_key='$prod_key'
				WHERE 1=1
					AND p.post_parent = 0
					AND p.post_type = 'product'
					AND ( !isnull(p.ID) AND p.post_status = 'publish' )
					AND (
						$searchby_clause
						OR
						( 0 < (
							SELECT count(psub.ID)
							FROM $wpdb->posts as psub
							RIGHT JOIN $wpdb->postmeta as pmsub ON psub.ID = pmsub.post_id AND pmsub.meta_key='$prod_key'
							WHERE 1=1 
								AND psub.post_parent = p.ID
								AND psub.post_type = 'product_variation'
								AND psub.post_status = 'publish'
								AND $searchby_clause_sub
						) )
					)
				{LIMIT};";
			}
			else {
				$sqlTpl = "
				SELECT {FIELDS}
				FROM $wpdb->posts as p
				RIGHT JOIN $wpdb->postmeta as pm ON p.ID = pm.post_id AND pm.meta_key='$prod_key'
				WHERE 1=1
					AND p.post_parent = 0
					AND p.post_type = 'product'
					AND ( !isnull(p.ID) AND p.post_status = 'publish' )
					AND $searchby_clause
				{LIMIT};";
			}

			$sql = $sqlTpl;
			$sql = str_replace("{FIELDS}", $__fields, $sql);
			$sql = str_replace("{LIMIT}", $__limitClause, $sql);
			//var_dump('<pre>', $sql , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			if ( $DEBUG ) {
				$ret['sql'] = $sql;
			}

			$res = $wpdb->get_results( $sql, OBJECT_K );
			if ( empty($res) ) {
				$ret = array_merge($ret, array(
					'nb'        => 0,
					'nbv'       => $totalVariations,
				));
				return $ret;
			}
			
			// total items
			$sqlTotal = $sqlTpl;
			$sqlTotal = str_replace("{FIELDS}", 'count(p.ID) as nb', $sqlTotal);
			$sqlTotal = str_replace("{LIMIT}", '', $sqlTotal);
			$this->items_nr = (int) $wpdb->get_var( $sqlTotal );
			
			$res_childs = array();
			$parent2child = array();
			//--------------------------
			//-- NOT USED
			/*if (0) {
				// get product variations (only childs, no parents)
				$sql_childs = "SELECT p.ID, p.post_title, p.post_parent, p.post_date FROM $wpdb->posts as p WHERE 1=1 AND p.post_status = 'publish' AND p.post_parent > 0 AND p.post_type = 'product_variation' ORDER BY p.ID ASC $q_limit;";
				$res_childs = $wpdb->get_results( $sql_childs, OBJECT_K );
				
				//var_dump('<pre>', $sql, $sql_childs, '</pre>'); die('debug...'); 
				if ( empty($res) && empty($res_childs) ) return array();
				
				// array with parents and their associated childrens
				foreach ($res_childs as $id => $val) {
					$parent = $val->post_parent;
					
					if ( !isset($parent2child["$parent"]) ) {
						$parent2child["$parent"] = array();
					}
					$parent2child["$parent"]["$id"] = $val; 
				}
			}*/
			//--------------------------
			//-- end NOT USED

			// products IDs
			$prods = array_merge(array(), array_keys($res), array_keys($res_childs));
			$prods = array_unique($prods);
			
			// get the number of product variations per each parent product
			foreach (array_chunk($prods, self::$sql_chunk_limit, true) as $current) {

				$currentP = implode(',', array_map(array($this->the_plugin, 'prepareForInList'), $current));
				$sql_childnb = "SELECT p.post_parent, count(p.ID) as nb FROM $wpdb->posts as p WHERE 1=1 AND p.post_status = 'publish' AND p.post_parent > 0 AND p.post_type = 'product_variation' AND p.post_parent IN ($currentP) GROUP BY p.post_parent ORDER BY p.post_parent ASC;";
				$res_childnb = $wpdb->get_results( $sql_childnb, OBJECT_K );
				$parent2child = $parent2child + $res_childnb; //array_replace($parent2child, $res_childnb);
			}

			// get ASINs
			$prods2asin = array();
			//--------------------------
			//-- NOT USED
			/*if (0) {
				foreach (array_chunk($prods, self::$sql_chunk_limit, true) as $current) {

					$currentP = implode(',', array_map(array($this->the_plugin, 'prepareForInList'), $current));
					$sql_getasin = "SELECT pm.post_id, pm.meta_value FROM $wpdb->postmeta as pm WHERE 1=1 AND pm.meta_key = '$prod_key' AND pm.post_id IN ($currentP) ORDER BY pm.post_id ASC;";
					$res_getasin = $wpdb->get_results( $sql_getasin, OBJECT_K );
					$prods2asin = $prods2asin + $res_getasin; //array_replace($prods2asin, $res_getasin);
				}
			}*/
			//--------------------------
			//-- end NOT USED

			// get product metas
			$__meta_toget = array();
			if ( $module == 'synchronization' ) {
				// synchronization
				$__meta_toget = array('_amzaff_sync_last_date', '_amzaff_sync_hits', '_amzaff_sync_last_status', '_amzaff_sync_last_status_msg', '_amzaff_sync_trash_tries', '_amzaff_sync_current_cycle');

				//$sync_choose_country = isset(self::$sync_options['sync_choose_country']) ? self::$sync_options['sync_choose_country'] : 'default';
				//if ( 'import_country' == $sync_choose_country ) {
					$__meta_toget[] = '_amzaff_country';
				//}
			}
			else if ( $module == 'speed_optimization' ) {
			}
			else {
				// stats products
				$__meta_toget = array('_amzaff_hits', '_amzaff_addtocart', '_amzaff_redirect_to_amazon', '_amzaff_bitly');
			}
			// get sync last date & sync hits
			$prods2meta = array();
			foreach ( (array) $__meta_toget as $meta) {
				$prods2meta["$meta"] = array();

				foreach (array_chunk($prods, self::$sql_chunk_limit, true) as $current) {
	
					$currentP = implode(',', array_map(array($this->the_plugin, 'prepareForInList'), $current));
	
					$sql_getmeta = "SELECT pm.post_id, pm.meta_value FROM $wpdb->postmeta as pm WHERE 1=1 AND pm.meta_key = '$meta' AND pm.post_id IN ($currentP) ORDER BY pm.post_id ASC;";
					$res_getmeta = $wpdb->get_results( $sql_getmeta, OBJECT_K );
					$prods2meta["$meta"] = $prods2meta["$meta"] + $res_getmeta; //array_replace($prods2meta["$meta"], $res_getmeta);
				}
			}
 
			// get Thumbs
			//$thumbs = $this->get_thumbs();
			$thumbs = array();
			foreach (array_chunk($prods, self::$sql_chunk_limit, true) as $current) {

				//$currentP = implode(',', array_map(array($this->the_plugin, 'prepareForInList'), $current));
				$thumbs_ = $this->get_thumbs( $current );
				$thumbs = $thumbs + $thumbs_; //array_replace($thumbs, $thumbs_);
			}
 
			// build html table with products rows
			$first_updated_date = (int) get_option('WooZone_sync_first_updated_date', 0);
			//$last_updated_product = (int) get_option('WooZone_sync_last_updated_product', true);
			//$last_selected_product = (int) get_option('WooZone_sync_last_selected_product', true);
			//$next_updated_product = $this->get_next_product( $last_updated_product );

			$default = array(
				'module'        => $module,
				//'last_id'       => $last_updated_product,
				//'lasts_id'      => $last_selected_product,
				//'next_id'       => $next_updated_product,
				'is_open'		=> false,
				'first_updated_date' => $first_updated_date,
				'current_time' => time(),
				'recurrence' => (int) ( self::$settings['sync_recurrence'] * 3600 ),
			);

			//$isBreakLimit = false;
			foreach ($res as $id => $val) {
					
				$prods2asin["$id"] = (object) array(
					'post_id'		=> $id,
					'meta_value'	=> $val->meta_value,
				);
				
				if ( !isset($prods2asin["$id"]) ) continue 1; // exclude products without ASIN

				$__p = $this->row_build(array_merge($default, array(
					'id'            => $id,
					'val'           => $val,
					'prods2asin'    => $prods2asin,
					'thumbs'        => $thumbs,
					'prods2meta'    => $prods2meta,
				)));
				$__p = array_merge($__p, array(
					'id'            => $id,
				));
 
				//$is_open = ( !empty($default['next_id'])
				//	&& $default['next_id']->post_type == 'product_variation'
				//	&& ( $id == (int) $default['next_id']->post_parent ) ? 1 : 0 );
				$is_open = 0;
				$childs_btn = '';
   
				// product variations if it has
				$childs_html = array();
				if ( isset($parent2child["$id"], $parent2child["$id"]->nb)
					&& $parent2child["$id"]->nb > 0 ) {

					$childs_nb = $parent2child["$id"]->nb;

					//--------------------------
					//-- NOT USED
					/*if (0) {
						$childs = $parent2child["$id"];
						$childs_nb = count($childs);
						$cc = 0;
						foreach ($childs as $childId => $childVal) {

							$__pc = $this->row_build(array_merge($default, array(
								'id'            => $childId,
								'val'           => $childVal,
								'prods2asin'    => $prods2asin,
								'thumbs'        => $thumbs,
								'prods2meta'    => $prods2meta,
								'is_open'       => $is_open,
							)));
							$__pc = array_merge($__pc, array(
								'id'            => $childId,
								'parent_id'     => $id,
								'cc'            => $cc,
								'childs_nb'     => $childs_nb,
							));
	 
							$childs_html[] = $this->row_view_html($__pc, true);
							
							$cc++;
						}
					}*/
					//--------------------------
					//-- end NOT USED

					$childs_btn = '<a href="#" class="wz-show-variations' . ($is_open ? ' sign-minus wz-force-open-vars' : ' sign-plus') . '">(<span>' . ($is_open ? '<i class="fa fa-caret-up"></i>' : '<i class="fa fa-caret-down"></i>') . '</span><span class="wz-nbvars">' . $childs_nb . '</span>)</a>';
					$nbprodv += $childs_nb;
				} // end product variations loop

				$__p['childs_btn'] = $childs_btn;
				
				$this->items["$id"] = $__p;
				
				// product
				$ret['html'][] = $this->row_view_html($__p);
				
				if ( isset($childs_html) && !empty($childs_html) ) {
					$ret['html'][] = implode(PHP_EOL, $childs_html);
				}

				$nbprod++;
			} // end products loop
			
			$nbprod = $this->items_nr;
			$nbprodv = $totalVariations;
			
			$ret = array_merge($ret, array(
				'status' 	=> 'valid',
				'nb'        => $nbprod,
				'nbv'       => $nbprodv,
			));
			/*if ( $isBreakLimit ) {
				$ret = array_merge_recursive($ret, array(
					'estimate'  => array(
						'nb'        => count($res),
						'nbv'       => count($res_childs),
					),
				));
			}*/

			return $ret;
		}

		protected function get_product_variations( $pms=array() ) {
			global $wpdb;
			
			$prod_key = '_amzASIN';
			
			$pms = array_merge(array(
				// synchronization | stats_prod | speed_optimization
				'module'			=> 'synchronization',

				'prodid'			=> 0,
			), $pms);
			extract($pms);
			
			if ( !$prodid ) return array();
			
			// get product variations
			$__fields = 'p.ID, p.post_title, p.post_parent, p.post_date, pm.post_id, pm.meta_value';
			$sqlTpl = "
			SELECT {FIELDS}
			FROM $wpdb->posts as p
			RIGHT JOIN $wpdb->postmeta as pm ON p.ID = pm.post_id AND pm.meta_key='$prod_key'
			WHERE 1=1
				AND p.post_parent = '$prodid'
				AND p.post_parent > 0
				AND p.post_type = 'product_variation'
				AND ( !isnull(p.ID) AND p.post_status = 'publish' )
			;";
			
			$sql = $sqlTpl;
			$sql = str_replace("{FIELDS}", $__fields, $sql);
			//var_dump('<pre>', $sql, '</pre>'); die('debug...');
			$res = $wpdb->get_results( $sql, OBJECT_K );
			if ( empty($res) ) return array();
			
			$res_childs = array();
			$parent2child = array();

			// products IDs
			$prods = array_merge(array(), array_keys($res), array_keys($res_childs));
			$prods = array_unique($prods);
			
			// get ASINs
			$prods2asin = array();
			
			if ( $module == 'synchronization' ) {
				$__meta_toget = array('_amzaff_sync_last_date', '_amzaff_sync_hits', '_amzaff_sync_last_status', '_amzaff_sync_last_status_msg', '_amzaff_sync_trash_tries', '_amzaff_sync_current_cycle');

				//$sync_choose_country = isset(self::$sync_options['sync_choose_country']) ? self::$sync_options['sync_choose_country'] : 'default';
				//if ( 'import_country' == $sync_choose_country ) {
					$__meta_toget[] = '_amzaff_country';
				//}
			}
			else if ( $module == 'speed_optimization' ) {

			}
			else {
				$__meta_toget = array('_amzaff_hits', '_amzaff_addtocart', '_amzaff_redirect_to_amazon');
			}
			// get sync last date & sync hits
			foreach ( (array) $__meta_toget as $meta) {
				$prods2meta["$meta"] = array();

				foreach (array_chunk($prods, self::$sql_chunk_limit, true) as $current) {
	
					$currentP = implode(',', array_map(array($this->the_plugin, 'prepareForInList'), $current));
	
					$sql_getmeta = "SELECT pm.post_id, pm.meta_value FROM $wpdb->postmeta as pm WHERE 1=1 AND pm.meta_key = '$meta' AND pm.post_id IN ($currentP) ORDER BY pm.post_id ASC;";
					$res_getmeta = $wpdb->get_results( $sql_getmeta, OBJECT_K );
					$prods2meta["$meta"] = $prods2meta["$meta"] + $res_getmeta; //array_replace($prods2meta["$meta"], $res_getmeta);
				}
			}
 
			// get Thumbs
			//$thumbs = $this->get_thumbs();
			$thumbs = array();
			foreach (array_chunk($prods, self::$sql_chunk_limit, true) as $current) {

				//$currentP = implode(',', array_map(array($this->the_plugin, 'prepareForInList'), $current));
				$thumbs_ = $this->get_thumbs( $current );
				$thumbs = $thumbs + $thumbs_; //array_replace($thumbs, $thumbs_);
			}
 
			// build html table with products rows
			$first_updated_date = (int) get_option('WooZone_sync_first_updated_date', 0);
			//$last_updated_product = (int) get_option('WooZone_sync_last_updated_product', true);
			//$last_selected_product = (int) get_option('WooZone_sync_last_selected_product', true);
			//$next_updated_product = $this->get_next_product( $last_updated_product );

			$default = array(
				'module'        => $module,
				//'last_id'       => $last_updated_product,
				//'lasts_id'      => $last_selected_product,
				//'next_id'       => $next_updated_product,
				'is_open'		=> false,
				'first_updated_date' => $first_updated_date,
				'current_time' => time(),
				'recurrence' => (int) ( self::$settings['sync_recurrence'] * 3600 ),
			);

			$ret = array('status' => 'valid', 'html' => array(), 'nb' => 0, 'nbv' => 0);
			$nbprod = 0;
			$cc = 0;
			$childs_nb = count($res);
			foreach ($res as $id => $val) {
					
				$prods2asin["$id"] = (object) array(
					'post_id'		=> $id,
					'meta_value'	=> $val->meta_value,
				);
				
				if ( !isset($prods2asin["$id"]) ) continue 1; // exclude products without ASIN
				
				$is_open = false;

				$__p = $this->row_build(array_merge($default, array(
					'id'            => $id,
					'val'           => $val,
					'prods2asin'    => $prods2asin,
					'thumbs'        => $thumbs,
					'prods2meta'    => $prods2meta,
					'is_open'       => $is_open,
				)));
				$__p = array_merge($__p, array(
					'id'            => $id,
					'parent_id'     => $prodid,
					'cc'            => $cc,
					'childs_nb'     => $childs_nb,
				));
 
				// product
				$ret['html'][] = $this->row_view_html($__p, true);
				
				$nbprod++;
				$cc++;
			} // end products loop
			
			$nbprod = $this->items_nr;
			
			$ret = array_merge($ret, array(
				'nb'        => $nbprod,
				//'nbv'       => $nbprodv,
			));
			
			return $ret;
		}

		protected function get_next_product( $last_id=0 ) {
			global $wpdb;
			
			$prod_key = '_amzASIN';
			
			$sql = "select p.ID, p.post_type, p.post_parent from $wpdb->posts as p left join $wpdb->postmeta as pm on p.ID = pm.post_id where 1=1 and p.ID > $last_id and p.post_type in ('product', 'product_variation') and p.post_status = 'publish' and pm.meta_key = '$prod_key' and !isnull(pm.meta_value) order by p.ID asc limit 1;";
			$ret = $wpdb->get_row( $sql );
			return is_null($ret) || $ret === false
				? (object) array(
					'ID'            => $last_id+1,
					'post_type'     => 'product',
					'post_parent'   => 0
				) : $ret;
		}

		protected function row_build( $pms ) {
			extract($pms);

			$title = $val->post_title;
			$asin = isset($prods2asin["$id"]) ? $prods2asin["$id"]->meta_value : __('missing', $this->the_plugin->localizationName);
				
			//$thumb = 'http://ecx.images-amazon.com/images/I/41mVXvLfOtL._SL75_.jpg';
			$thumb = isset($thumbs["$id"]) && !empty($thumbs["$id"]) ? $thumbs["$id"] : $this->get_thumb_src_default();
				
			$link_edit = sprintf( admin_url('post.php?post=%s&action=edit'), $id);
			
			$add_data = $val->post_date;
			$add_data = $this->the_plugin->last_update_date('true', strtotime($add_data));

			if ( $module == 'synchronization' ) {
				$sync_nb = isset($prods2meta['_amzaff_sync_hits']["$id"]) ? $prods2meta['_amzaff_sync_hits']["$id"]->meta_value : 0;

				$sync_data = isset($prods2meta['_amzaff_sync_last_date']["$id"]) ? $prods2meta['_amzaff_sync_last_date']["$id"]->meta_value : '';
				$sync_data_display = $this->the_plugin->last_update_date('true', $sync_data);
				
				$sync_last_status = '';
				if ( isset($prods2meta['_amzaff_sync_last_status']["$id"]) ) {
					$sync_last_status = $this->the_plugin->syncproduct_sanitize_last_status(
						$prods2meta['_amzaff_sync_last_status']["$id"]->meta_value
					);
				}

				$sync_last_status_msg = isset($prods2meta['_amzaff_sync_last_status_msg']["$id"]) ? $prods2meta['_amzaff_sync_last_status_msg']["$id"]->meta_value : '';
				$sync_last_status_msg = maybe_unserialize( $sync_last_status_msg );

				$sync_trash_tries = isset($prods2meta['_amzaff_sync_trash_tries']["$id"])
					? $prods2meta['_amzaff_sync_trash_tries']["$id"]->meta_value : 0;

				// sync_import_country
				$sync_import_country = '';
				//$sync_choose_country = isset(self::$sync_options['sync_choose_country']) ? self::$sync_options['sync_choose_country'] : 'default';
				//if ( 'import_country' == $sync_choose_country ) {
					$sync_import_country = isset($prods2meta['_amzaff_country']["$id"]) ? $prods2meta['_amzaff_country']["$id"]->meta_value : '';
				//}
				//else {
				//	$sync_import_country = $this->amz_settings['country'];
				//}

				if ( '' != $sync_import_country ) {
					$country_flag = $this->the_plugin->get_product_import_country_flag( array(
						'country' 	=> $sync_import_country,
						'asin' 		=> isset($prods2asin["$id"]) ? $prods2asin["$id"]->meta_value : '',
					));
					$sync_import_country = $country_flag['image_link'];
				}

				$sync_current_cycle = isset($prods2meta['_amzaff_sync_current_cycle']["$id"])
					? $prods2meta['_amzaff_sync_current_cycle']["$id"]->meta_value : 0;

				// statuses: NOT SYNCED | ALREADY SYNCED | NEXT TO BE SYNCED
				$sst = array(
					'not_synced'            => __('NOT SYNCED', $this->the_plugin->localizationName),
					'already_synced'        => __('ALREADY SYNCED', $this->the_plugin->localizationName),
					'next_to_synced'        => __('NEXT TO BE SYNCED', $this->the_plugin->localizationName),
				);
				//$next_id = $next_id->ID;

				$sync_status = array(
					//'css'       => ( $id < $next_id ? 'wz-synced' : ( $id == $next_id ? 'wz-next-sync' : '' ) ),
					//'text'      => ( $id < $next_id ? $sst['already_synced'] : ( $id == $next_id ? $sst['next_to_synced'] : $sst['not_synced'] ) ),
					'css'       => '',
					'text'      => $sst['not_synced'],
				);

				if ( ! empty($sync_data) && ( $current_time < ($sync_data + $recurrence) ) ) {
					$sync_status['text'] = $sst['already_synced'];
					$sync_status['css'] .= ' wz-synced';
				}

				if ( $is_open ) {
					$sync_status['css'] .= ' wz-hide-me';
				}
				if ( ! empty($sync_last_status) ) {
					$sync_status['css'] .= ' wz-last-status-' . $sync_last_status;
				}

				$sync_status['text'] .= __(' - based on recurrence', $this->the_plugin->localizationName);
				$sync_status['css'] = trim($sync_status['css']);

				$ret = compact(
					'module', 'add_data', 'title', 'asin', 'thumb', 'link_edit', 'first_updated_date',
					'sync_status', 'sync_nb', 'sync_data', 'sync_data_display', 'sync_last_status', 'sync_last_status_msg',
					'sync_import_country', 'sync_trash_tries', 'sync_current_cycle'
				);
			}
			else if ( $module == 'speed_optimization' ) {
				$sync_status = array(
					'css'       => '',
					'text'      => '',
				);
				
				$ret = compact('module', 'add_data', 'title', 'asin', 'thumb', 'link_edit', 'sync_status');
			}
			else {
				$stats_hits = isset($prods2meta['_amzaff_hits']["$id"]) ? $prods2meta['_amzaff_hits']["$id"]->meta_value : 0;
				$stats_added_to_cart = isset($prods2meta['_amzaff_addtocart']["$id"]) ? $prods2meta['_amzaff_addtocart']["$id"]->meta_value : 0;
				$stats_redirected_to_amazon = isset($prods2meta['_amzaff_redirect_to_amazon']["$id"]) ? $prods2meta['_amzaff_redirect_to_amazon']["$id"]->meta_value : 0;

				$stats_bitlylink = isset($prods2meta['_amzaff_bitly']["$id"]) ? $prods2meta['_amzaff_bitly']["$id"]->meta_value : '';
				if ( '' != $stats_bitlylink ) {
					$stats_bitlylink = maybe_unserialize( $stats_bitlylink );
				}

				$sync_status = array(
					'css'       => '',
					'text'      => '',
				);

				$ret = compact(
					'module', 'add_data', 'title', 'asin', 'thumb', 'link_edit',
					'sync_status', 'stats_hits', 'stats_added_to_cart', 'stats_redirected_to_amazon', 'stats_bitlylink'
				);
			}
			return $ret;
		}

		protected function row_view_html( $row, $is_child=false ) {

			$with_wrapper = isset($row['with_wrapper']) && ! $row['with_wrapper'] ? false : true;

			$tr_css = ' ' . $row['sync_status']['css']
				. ($is_child ? ' wz-variation' . ($row['cc'] == 0 ? ' first' : ($row['cc'] == $row['childs_nb'] - 1 ? ' last' : '')) : '');
			$data_parent_id = ($is_child ? ' data-parent_id=' . $row['parent_id'] : '');
			$childs_btn = (!$is_child ? ' ' . $row['childs_btn'] : '');
			
			$text_id = __('ID', $this->the_plugin->localizationName) . ': #';
			$text_asin = __('ASIN', $this->the_plugin->localizationName) . ': ';
			
			if ( $row['module'] == 'synchronization' ) {

				$sync_last_stats_column = $this->the_plugin->syncproduct_build_last_stats_column( array(
					'asin' => $row['asin'],
					'sync_nb' => $row['sync_nb'],
					'sync_last_status' => $row['sync_last_status'],
					'sync_last_status_msg' => $row['sync_last_status_msg'],
					'sync_trash_tries' => $row['sync_trash_tries'],
					'sync_import_country' => $row['sync_import_country'],
					'sync_current_cycle' => $row['sync_current_cycle'],
					'first_updated_date' => $row['first_updated_date'],
				));
				$text_last_sync_niceinfo = $sync_last_stats_column['text_last_sync_niceinfo'];
				$text_sync_now = __('SYNC NOW', $this->the_plugin->localizationName);

				$column_image = $row['thumb'] == '##default##' ? '<i class="WooZone-icon-assets_dwl"></i>' : '<img src="' . $row['thumb'] . '" alt="' . $row['title'] . '" />';
				//$column_image .= ' ' . $text_asin . $row['asin'] . ' / ' . $text_id . $row['id'] . $childs_btn;
				$column_image .= ' ' . $text_id . $row['id'] . ' || ' . $row['asin'] . $childs_btn;
			}
			else if ( $row['module'] == 'speed_optimization' ) {
			}
			else {
				$text_hits = '<i class="WooZone-prod-stats-number hits">' . ( $row['stats_hits'] ) . '</i>';
				$text_added_to_cart = '<i class="WooZone-prod-stats-number add-to-cart">' . ( $row['stats_added_to_cart'] ) . '</i>';
				$text_redirected_to_amazon = '<i class="WooZone-prod-stats-number redirect-to-amazon">' . ( $row['stats_redirected_to_amazon'] ) . '</i>';
			}

			$ret = '';
			if ( $row['module'] == 'synchronization' ) {
				if ( $with_wrapper ) {
					$ret = '<tr class="WooZone-sync-table-row' . $tr_css . '" data-id=' . $row['id'] . ' data-asin=' . $row['asin'] . $data_parent_id . '>';
				}
				$ret .= '
						<td><i class="fa fa-flag" title="' . $row['sync_status']['text'] . '"></i></td>
						<td class="WooZone-sync-row-prodinfo">' . $column_image . '</td>
						<td class="WooZone-sync-row-title"><a href="' . $row['link_edit'] . '" target="_blank">' . $row['title'] . '</a></td>
						<td class="WooZone-sync-row-last-status">' . $text_last_sync_niceinfo . '</td>
						<td class="WooZone-sync-row-last-date">' . $row['sync_data_display'] . '</td>
						<td class="WooZone-sync-now"><button>' . $text_sync_now . '</button></td>
				';
				if ( $with_wrapper ) {
					$ret .= '</tr>';
				}
			}
			else if ( $row['module'] == 'speed_optimization' ) {
				$speed_optimizator = WooZoneSpeedOptimizator::getInstance();
				
				if( $is_child == false ) {
					$ret = '
						<tr class="WooZone-sync-table-row' . $tr_css . '" data-id=' . $row['id'] . ' data-asin=' . $row['asin'] . $data_parent_id . '>
							<td><input type="checkbox" name="product_id" value="' . $row['id'] . '"/></td>
							<td><span>' . $row['id'] . '</span></td>
							<td>' . ($row['thumb'] == '##default##' ? '<i class="WooZone-icon-assets_dwl"></i>' : '<img src="' . $row['thumb'] . '"/>') . '<span class="WooZone-prod-info">' . $text_asin . $row['asin'] . ( trim($childs_btn) != '' ? '<br/><span class="WooZone-variable-prod">' . __('VARIABLE', 'woozone') . '</span>' : '' ) . '</span></td>
							<td><a href="' . $row['link_edit'] . '" target="_blank">' . $row['title'] . '</a></td>
							<td>' . $speed_optimizator->print_stats( $row ) . '</td>
							<td>' . $speed_optimizator->print_actions( $row )  . '</td>
							<td>' . $row['add_data'] . '</td>
						</tr>
					';
				}
			}
			else {
				$ret = '
					<tr class="WooZone-sync-table-row' . $tr_css . '" data-id=' . $row['id'] . ' data-asin=' . $row['asin'] . $data_parent_id . '>
						<td><span>' . $row['id'] . '</span></td>
						<td>' . ($row['thumb'] == '##default##' ? '<i class="WooZone-icon-assets_dwl"></i>' : '<img src="' . $row['thumb'] . '" alt="' . $row['title'] . '" />') . ' ' . $text_asin . $row['asin'] . $childs_btn . '</td>
						<td><a href="' . $row['link_edit'] . '" target="_blank">' . $row['title'] . '</a></td>
						<td>
				';

				$bitly_links = array();
				if ( is_array($row['stats_bitlylink']) ) {
					foreach ($row['stats_bitlylink'] as $bitKey => $bitVal) {

						if ( ! isset($bitVal['short_url']) || ( '' == $bitVal['short_url'] ) ) {
							continue 1;
						}

						$prod_url_info = $this->get_product_bitly_url_country_flag( $bitKey );
						$flag = $prod_url_info['img'];
						$country_name = $prod_url_info['country_name'];

						$bitly_links[] = sprintf( '<a href="%s" target="_blank" title="%s" class="bitly-country">%s</a>', $bitVal['short_url'], $country_name, $flag );
					}
				}
				//var_dump('<pre>', $row['stats_bitlylink'], $bitly_links , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				$ret .= implode( $bitly_links );

				$ret .= '
						</td>
						<td>' . $text_hits . '</td>
						<td>' . $text_added_to_cart . '</td>
						<td>' . $text_redirected_to_amazon . '</td>
						<td>' . $row['add_data'] . '</td>
					</tr>
				';
			}
			return $ret;
		}

		protected function get_interface_max_products( $use_pag=false ) {
			$max_prods = self::$sync_options['interface_max_products'];
			$max_prods = $max_prods !== 'all' ? (int) $max_prods : 'all';
			
			// when with pagination
			if ( $use_pag && 'all' != $max_prods ) {
				$__ = floor( $max_prods / 5 );
				$__ = $__ > 0 ? (int) ($__ * 5) : 1;
				
				if ( $__ > 50 && $__ < 100 ) {
					$__ = 50;
				}
				else if ( $__ > 100 && $__ < 500 ) {
					$__ = floor( $__ / 100 ) * 100;
				}
				else if ( $__ > 500 ) {
					$__ = 500;
				}
				$max_prods = $__;
			}
			
			return $max_prods;
		}

		
		/**
		 * Get Thumbnails / Thumbnail - based on WP functionality
		 */
		protected function get_thumb_src_default() {
			return '##default##';
		}

		protected function get_thumbs( $currentIds=array(), $size='thumbnail' ) {
			global $wpdb;
			
			$currentP = '';
			if ( !empty($currentIds) ) {
				$currentP = implode(',', array_map(array($this->the_plugin, 'prepareForInList'), $currentIds));
			}
			
			// get post & associated thumbnails id
			$sql = "select p.ID, pm.meta_value from $wpdb->posts as p left join $wpdb->postmeta as pm on p.ID = pm.post_id where 1=1 and pm.post_id IN ($currentP) and pm.meta_key = '_thumbnail_id' and !isnull(pm.meta_id) order by p.ID;";
			$res = $wpdb->get_results( $sql, OBJECT_K );
			if ( empty($res) ) return array();
			
			// get unique thumbnails id
			$sql_thumb = "select distinct(pm.meta_value) from $wpdb->posts as p left join $wpdb->postmeta as pm on p.ID = pm.post_id where 1=1 and pm.post_id IN ($currentP) and pm.meta_key = '_thumbnail_id' and !isnull(pm.meta_id) order by p.ID;";
			$res_thumb = $wpdb->get_results( $sql_thumb, OBJECT_K );
			$thumbsId = array_keys($res_thumb);

			// get meta fields for thumbnails
			$thumb2meta = array('_wp_attachment_metadata' => array(), '_wp_attached_file' => array());
			foreach (array_chunk($thumbsId, self::$sql_chunk_limit, true) as $current) {

				$currentP = implode(',', array_map(array($this->the_plugin, 'prepareForInList'), $current));

				$sql_getmeta = "select p.ID, pm.meta_value from $wpdb->posts as p left join $wpdb->postmeta as pm on p.ID = pm.post_id where 1=1 and pm.meta_key = '_wp_attachment_metadata' and !isnull(pm.meta_id) and pm.post_id IN ($currentP) order by p.ID;";
				$res_getmeta = $wpdb->get_results( $sql_getmeta, OBJECT_K );
				//array_replace($prods2asin, $res_getmeta);
				$thumb2meta['_wp_attachment_metadata'] = $thumb2meta['_wp_attachment_metadata'] + $res_getmeta;
				
				$sql_getmeta = "select p.ID, pm.meta_value, p.guid from $wpdb->posts as p left join $wpdb->postmeta as pm on p.ID = pm.post_id where 1=1 and pm.meta_key = '_wp_attached_file' and !isnull(pm.meta_id) and pm.post_id IN ($currentP) order by p.ID;";
				$res_getmeta = $wpdb->get_results( $sql_getmeta, OBJECT_K );
				//array_replace($prods2asin, $res_getmeta);
				$thumb2meta['_wp_attached_file'] = $thumb2meta['_wp_attached_file'] + $res_getmeta;
			}
 
			$default_meta = array(
				'uploads'       => wp_upload_dir(), // cache this wp function!
			);
			$thumbs = array();
			foreach ($thumbsId as $key) {

				$meta = array_merge($default_meta, array());
				$meta['file'] = isset($thumb2meta['_wp_attached_file']["$key"])
					? $thumb2meta['_wp_attached_file']["$key"] : '';
  
				$meta['sizes'] = isset($thumb2meta['_wp_attachment_metadata']["$key"]->meta_value)
					? $thumb2meta['_wp_attachment_metadata']["$key"]->meta_value : '';
				if ( !empty($meta['sizes']) ) {
					$meta['sizes'] = maybe_unserialize($meta['sizes']);
				}
				
				$thumbs["$key"] = $this->get_thumb_src( $meta, 'shop_thumbnail' );
				$thumbs["$key"] = isset($thumbs["$key"][0]) ? $thumbs["$key"][0] : '';
				$thumbs["$key"] = !empty($thumbs["$key"]) ? $thumbs["$key"] : $this->get_thumb_src_default();
				if ( strpos( $thumbs["$key"], $this->the_plugin->get_amazon_images_path() ) ) {
					$thumbs["$key"] = str_replace( $default_meta['uploads']['baseurl'] . '/', '', $thumbs["$key"] );
					$thumbs["$key"] = $this->the_plugin->amazon_url_to_ssl( $thumbs["$key"] );
				}
			}
	
			$post2thumb = array();
			foreach ( $res as $key => $val ) {
				$thumb_id = $val->meta_value;
				$post2thumb["$key"] = isset($thumbs["$thumb_id"]) && !empty($thumbs["$thumb_id"])
					? $thumbs["$thumb_id"] : $this->get_thumb_src_default();
			}
			return $post2thumb;
		}

		protected function get_thumb( $meta, $size='medium', $pms=array() ) {
			$image = $this->get_thumb_src( $meta, $size );
			
			$html = '';
			if ( $image ) {
				list($src, $width, $height) = $image;
				$hwstring = image_hwstring($width, $height);
				$size_class = $size;
				if ( is_array( $size_class ) ) {
					$size_class = join( 'x', $size_class );
				}
				//$attachment = get_post($attachment_id);
				$default_attr = array(
					'src'   => $src,
					'class' => "attachment-$size_class",
					'alt'   => isset($pms['alt']) ? $pms['alt'] : "$size_class", //trim(strip_tags( get_post_meta($attachment_id, '_wp_attachment_image_alt', true) )), // Use Alt field first
				);
				//if ( empty($default_attr['alt']) )
				//    $default_attr['alt'] = trim(strip_tags( $attachment->post_excerpt )); // If not, Use the Caption
				//if ( empty($default_attr['alt']) )
				//    $default_attr['alt'] = trim(strip_tags( $attachment->post_title )); // Finally, use the title
 
				$attr = wp_parse_args($attr, $default_attr);
 
				$attr = array_map( 'esc_attr', $attr );
				$html = rtrim("<img $hwstring");
				foreach ( $attr as $name => $value ) {
					$html .= " $name=" . '"' . $value . '"';
				}
				$html .= ' />';
			}
			return $html;
		}

		protected function get_thumb_src( $meta, $size='medium' ) {
			$img_url = $this->wp_get_attachment_url($meta);
  
			$width = $height = 0;
			$img_url_basename = wp_basename($img_url);
				
			// try for a new style intermediate size
			if ( $intermediate = $this->image_get_intermediate_size($meta, $size) ) {
				$img_url = str_replace($img_url_basename, $intermediate['file'], $img_url);
				$width = $intermediate['width'];
				$height = $intermediate['height'];
			}
			elseif ( $size == 'thumbnail' ) {
				// fall back to the old thumbnail
				$file = isset($meta['file']->meta_value) ? $meta['file']->meta_value : '';

				if ( ($thumb_file = $file) && $info = getimagesize($thumb_file) ) {
					$img_url = str_replace($img_url_basename, wp_basename($thumb_file), $img_url);
					$width = $info[0];
					$height = $info[1];
				}
			}
			
			if ( !$width && !$height && isset( $meta['sizes']['width'], $meta['sizes']['height'] ) ) {
				// any other type: use the real image
				$width = $meta['sizes']['width'];
				$height = $meta['sizes']['height'];
			}
			if ( $img_url) {
				// we have the actual image size, but might need to further constrain it if content_width is narrower
				list( $width, $height ) = image_constrain_size_for_editor( $width, $height, $size );
				return array( $img_url, $width, $height );
			}
			return false;
		}

		protected function wp_get_attachment_url( $meta ) {
			$url = '';

			$uploads = $meta['uploads'];
			$file = isset($meta['file']->meta_value) ? $meta['file']->meta_value : '';
			if ( !empty($file) ) {
				// Get upload directory.
				if ( $uploads && false === $uploads['error'] ) {
					// Check that the upload base exists in the file location.
					if ( 0 === strpos( $file, $uploads['basedir'] ) ) {
						// Replace file location with url location.
						$url = str_replace($uploads['basedir'], $uploads['baseurl'], $file);
					} elseif ( false !== strpos($file, 'wp-content/uploads') ) {
						$url = $uploads['baseurl'] . substr( $file, strpos($file, 'wp-content/uploads') + 18 );
					} else {
						// It's a newly-uploaded file, therefore $file is relative to the basedir.
						$url = $uploads['baseurl'] . "/$file";
					}
				}
			}

			if ( empty($url) ) {
				$url = isset($meta['sizes']->guid) ? $meta['sizes']->guid : '';
			}
			return $url;
		}

		protected function image_get_intermediate_size( $meta, $size='thumbnail' ) {
			if ( !is_array( $imagedata = $meta['sizes'] ) )
				return false;

			// get the best one for a specified set of dimensions
			if ( is_array($size) && !empty($imagedata['sizes']) ) {
				foreach ( $imagedata['sizes'] as $_size => $data ) {
					// already cropped to width or height; so use this size
					if ( ( $data['width'] == $size[0] && $data['height'] <= $size[1] ) || ( $data['height'] == $size[1] && $data['width'] <= $size[0] ) ) {
						$file = $data['file'];
						list($width, $height) = image_constrain_size_for_editor( $data['width'], $data['height'], $size );
						return compact( 'file', 'width', 'height' );
					}
					// add to lookup table: area => size
					$areas[$data['width'] * $data['height']] = $_size;
				}
				if ( !$size || !empty($areas) ) {
					// find for the smallest image not smaller than the desired size
					ksort($areas);
					foreach ( $areas as $_size ) {
						$data = $imagedata['sizes'][$_size];
						if ( $data['width'] >= $size[0] || $data['height'] >= $size[1] ) {
							// Skip images with unexpectedly divergent aspect ratios (crops)
							// First, we calculate what size the original image would be if constrained to a box the size of the current image in the loop
							$maybe_cropped = image_resize_dimensions($imagedata['width'], $imagedata['height'], $data['width'], $data['height'], false );
							// If the size doesn't match within one pixel, then it is of a different aspect ratio, so we skip it, unless it's the thumbnail size
							if ( 'thumbnail' != $_size && ( !$maybe_cropped || ( $maybe_cropped[4] != $data['width'] && $maybe_cropped[4] + 1 != $data['width'] ) || ( $maybe_cropped[5] != $data['height'] && $maybe_cropped[5] + 1 != $data['height'] ) ) )
								continue;
							// If we're still here, then we're going to use this size
							$file = $data['file'];
							list($width, $height) = image_constrain_size_for_editor( $data['width'], $data['height'], $size );
							return compact( 'file', 'width', 'height' );
						}
					}
				}
			}
 
			if ( is_array($size) || empty($size) || empty($imagedata['sizes'][$size]) )
				return false;
 
			$data = $imagedata['sizes'][$size];
			// include the full filesystem path of the intermediate file
			if ( empty($data['path']) && !empty($data['file']) ) {
				$file_url = $this->wp_get_attachment_url($meta);
				$data['path'] = path_join( dirname($imagedata['file']), $data['file'] );
				$data['url'] = path_join( dirname($file_url), $data['file'] );
			}
			return $data;
		}


		/**
		 * Pretty-prints the difference in two times.
		 *
		 * @param time $older_date
		 * @param time $newer_date
		 * @return string The pretty time_since value
		 * @original link http://binarybonsai.com/code/timesince.txt
		 */
		public function time_since( $older_date, $newer_date ) {
			return $this->interval( $newer_date - $older_date );
		}
		public function interval( $since ) {
			// array of time period chunks
			$chunks = array(
				array(60 * 60 * 24 * 365 , _n_noop('%s year', '%s years', 'WooZone')),
				array(60 * 60 * 24 * 30 , _n_noop('%s month', '%s months', 'WooZone')),
				array(60 * 60 * 24 * 7, _n_noop('%s week', '%s weeks', 'WooZone')),
				array(60 * 60 * 24 , _n_noop('%s day', '%s days', 'WooZone')),
				array(60 * 60 , _n_noop('%s hour', '%s hours', 'WooZone')),
				array(60 , _n_noop('%s minute', '%s minutes', 'WooZone')),
				array( 1 , _n_noop('%s second', '%s seconds', 'WooZone')),
			);
	
	
			if( $since <= 0 ) {
				return __('now', 'WooZone');
			}
	
			// we only want to output two chunks of time here, eg:
			// x years, xx months
			// x days, xx hours
			// so there's only two bits of calculation below:
	
			// step one: the first chunk
			for ($i = 0, $j = count($chunks); $i < $j; $i++)
				{
				$seconds = $chunks[$i][0];
				$name = $chunks[$i][1];
	
				// finding the biggest chunk (if the chunk fits, break)
				if (($count = floor($since / $seconds)) != 0)
					{
					break;
					}
				}
	
			// set output var
			$output = sprintf(_n($name[0], $name[1], $count, 'WooZone'), $count);
	
			// step two: the second chunk
			if ($i + 1 < $j)
				{
				$seconds2 = $chunks[$i + 1][0];
				$name2 = $chunks[$i + 1][1];
	
				if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0)
					{
					// add to output var
					$output .= ' '.sprintf(_n($name2[0], $name2[1], $count2, 'WooZone'), $count2);
					}
				}
	
			return $output;
		}
	
	
		/**
		 * Pagination
		 */
		protected function build_pagination_vars() {
			$ses = isset($_SESSION['WooZone_sync']) ? $_SESSION['WooZone_sync'] : array();
			$max_prods = $this->get_interface_max_products(true);

			$posts_per_page = isset($ses['posts_per_page']) ? $ses['posts_per_page'] : $max_prods;
			$paged = isset($ses['paged']) ? $ses['paged'] : 1;

			$filterby_sync_status = isset($ses['filterby_sync_status']) ? $ses['filterby_sync_status'] : '';
			
			$searchby_what = isset($ses['searchby_what']) ? $ses['searchby_what'] : '';
			$searchby_value = isset($ses['searchby_value']) ? $ses['searchby_value'] : '';
			
			return compact('ses', 'max_prods', 'posts_per_page', 'paged', 'filterby_sync_status', 'searchby_what', 'searchby_value');
		}
		protected function get_pagination( $pms=array() )
		{
			$pms = array_replace_recursive( array(
				'position' 		=> 'top',
				'with_wrapp' 	=> true,
				'filterby' 		=> true,
			), $pms);
			extract($pms);
			extract( $this->build_pagination_vars() );

			$html = array();

			$items_nr = $this->items_nr;
			$total_pages = $posts_per_page == 'all' ? 1 : ceil( $items_nr / $posts_per_page );
			
			$with_wrapp = isset($with_wrapp) && $with_wrapp ? true : false;
			
			if ($with_wrapp) {
				$css_pag = 'WooZone-sync-pagination ';
				$css_pag .= ($position == 'bottom' ? 'WooZone-sync-bottom' : 'WooZone-sync-top');
				$html[] = 	'<div class="' . $css_pag . '">';
			}

			//filterby_sync_status
			if ( $filterby ) {
				$html[] = '<div class="WooZone-sync-filterby-sync-status">';

				// sync statuses
				$html[] = 	'<select id="filterby_sync_status" name="filterby_sync_status">';
				$html[] = 		'<option value="" disabled="disabled">' . 'Filter by Product Last Sync Status' . '</option>';

				$_sync_statuses = array(
					'' => 'All',
					'throttled' => 'Amazon Response: Throttled',
					'invalid' => 'Amazon Response: Other Error',
					'notfound' => 'Product Not Found on Amazon',
					'neversynced' => 'Never synced or new product',
					'notupdated' => 'No product field needed update',
					'updated' => 'Some product fields were updated',
				);
				foreach ($_sync_statuses as $kk => $vv) {
					$html[] = 	'<option value="' . $kk . '" ' . ( $filterby_sync_status == $kk ? 'selected="selected"' : '' ) . '>' . $vv . '</option>';
				}

				$html[] = 	'</select>';

				// search by
				$html[] = '<input type="text" name="searchby_value" id="searchby_value" value="' . $searchby_value . '" placeholder="" />';

				$html[] = 	'<select id="searchby_what" name="searchby_what">';
				$html[] = 		'<option value="" disabled="disabled">' . 'Search by' . '</option>';

				$_searchby_list = array(
					'post_id' => 'Product ID',
					'asin' => 'Product ASIN',
					'post_title' => 'Product Title',
				);
				foreach ($_searchby_list as $kk => $vv) {
					$html[] = 	'<option value="' . $kk . '" ' . ( $searchby_what == $kk ? 'selected="selected"' : '' ) . '>' . $vv . '</option>';
				}

				$html[] = 	'</select>';

				$html[] = 	'<button class="searchby_button">' . __('Search', $this->the_plugin->localizationName) . '</button>';

				$html[] = '</div>';
			}

			// pages
			$html[] = '<div class="WooZone-sync-pagination-wrapp">';
			$html[] = 		'<div class="WooZone-list-table-pagination tablenav">';

			$html[] = 			'<div class="tablenav-pages">';
			$html[] = 				'<span class="displaying-num">' . ( $items_nr ) . ' items</span>';
			if( $total_pages > 1 ){
				$html[] = 				'<span class="pagination-links"><a class="first-page ' . ( $paged <= 1 ? 'disabled' : '' ) . ' WooZone-jump-page" title="Go to the first page" href="#paged=1">&laquo;</a>';
				$html[] = 				'<a class="prev-page ' . ( $paged <= 1 ? 'disabled' : '' ) . ' WooZone-jump-page" title="Go to the previous page" href="#paged=' . ( $paged > 2 ? ($paged - 1) : '' ) . '">&lsaquo;</a>';
				$html[] = 				'<span class="paging-input"><input class="current-page" title="Current page" type="text" name="paged" value="' . ( $paged ) . '" size="2" style="width: 45px;"> of <span class="total-pages">' . ( ceil( $items_nr / $posts_per_page ) ) . '</span></span>';
				$html[] = 				'<a class="next-page ' . ( $paged >= ($total_pages - 1) ? 'disabled' : '' ) . ' WooZone-jump-page" title="Go to the next page" href="#paged=' . ( $paged >= ($total_pages - 1) ? $total_pages : $paged + 1 ) . '">&rsaquo;</a>';
				$html[] = 				'<a class="last-page ' . ( $paged >=  ($total_pages - 1) ? 'disabled' : '' ) . ' WooZone-jump-page" title="Go to the last page" href="#paged=' . ( $total_pages ) . '">&raquo;</a></span>';
			}
			$html[] = 			'</div>';
			$html[] = 		'</div>';
			
			// per page
			$html[] = 		'<div class="WooZone-box-show-per-pages">';
			$html[] = 			'<select name="WooZone-post-per-page" id="WooZone-post-per-page" class="WooZone-post-per-page WooZone-filter-general_field">';

			$_range = array_merge( array(), range(5, 50, 5), range(100, 500, 100) );
			foreach( $_range as $nr => $val ){
				$html[] = 			'<option value="' . ( $val ) . '" ' . ( $posts_per_page == $val ? 'selected' : '' ). '>' . ( $val ) . '</option>';
			}

			$html[] = 				'<option value="all"' . ( $posts_per_page == 'all' ? 'selected' : '' ) . '>';
			$html[] =				__('Show All', $this->the_plugin->localizationName);
			$html[] = 				'</option>';
			$html[] =			'</select>';
			$html[] = 			'<label for="WooZone-post-per-page">' . __('per page', $this->the_plugin->localizationName) . '</label>';
			$html[] = 		'</div>';
			$html[] = '</div>';
			// end pages

			if ($with_wrapp) {
				$html[] = 	'</div>';
			}

			return implode("\n", $html);
		}
	
	
		/**
		 * Ajax requests
		 */
		public function ajax_request()
		{
			global $wpdb;
			$request = array(
				'action'                        => isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '',
				'module'                        => isset($_REQUEST['module']) ? $_REQUEST['module'] : 'synchronization',

				'sync_fields'                   => isset($_REQUEST['sync_fields']) ? $_REQUEST['sync_fields'] : array(),
				'sync_recurrence'               => isset($_REQUEST['sync_recurrence']) ? $_REQUEST['sync_recurrence'] : '',
				'sync_hour_start'               => isset($_REQUEST['sync_hour_start']) ? $_REQUEST['sync_hour_start'] : '',

				'sync_products_per_request'     => isset($_REQUEST['sync_products_per_request'])
					? (int) $_REQUEST['sync_products_per_request'] : 50,
				
				'sync_stop_reload'              => isset($_REQUEST['sync_stop_reload'])
					? (int) $_REQUEST['sync_stop_reload'] : 0,
				
				'id'                            => isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0,
				'asin'                          => isset($_REQUEST['asin']) ? (string) $_REQUEST['asin'] : '',

				'paged'     					=> isset($_REQUEST['paged']) ? (int) $_REQUEST['paged'] : 1,
				'posts_per_page'				=> isset($_REQUEST['post_per_page']) ? (string) $_REQUEST['post_per_page'] : 0,

				'filterby_sync_status' 			=> isset($_REQUEST['filterby_sync_status'])
					? (string) $_REQUEST['filterby_sync_status'] : '',

				'searchby_what' 				=> isset($_REQUEST['searchby_what'])
					? (string) $_REQUEST['searchby_what'] : '',
				'searchby_value' 				=> isset($_REQUEST['searchby_value'])
					? (string) $_REQUEST['searchby_value'] : '',

				'is_open' 						=> isset($_REQUEST['is_open']) ? (string) $_REQUEST['is_open'] : 'no',
			);
			extract($request);
			
			$ret = array(
				'status'        => 'invalid',
				'msg'           => '<div class="WooZone-sync-settings-msg WooZone-message WooZone-error">' . __('Invalid action!', $this->the_plugin->localizationName) . '</div>',
			);
			
			if ( empty($action) || !in_array($action, array(
				'cronjob_stats_mainstats', 'save_settings', 'load_products', 'sync_prod', 'auto_reload', 'paged', 'post_per_page', 'open_variations', 'filterby_sync_status', 'searchby_what'
			)) ) {
				die(json_encode($ret));
			}
			
			if ( 'cronjob_stats_mainstats' == $action ) {

				$opStatus = $this->sync_stats();
				$ret = array_merge($ret, array(
					'status'		=> 'valid',
					'html'			=> $opStatus,
				));

			} else if ( $action == 'save_settings' ) {

				if ( !empty($request['sync_fields']) ) {
					$request['sync_fields'] = array_keys($request['sync_fields']);
				}
				
				$request = array_diff_key($request, array_fill_keys(array('action', 'id', 'asin'), 1));
				update_option($this->alias . '_sync', (array) $request);
				
				$this->init_sync_settings();
				$ret = array_merge($ret, array(
					'status'    => 'valid',
					'msg'       => '<div class="WooZone-sync-settings-msg WooZone-message WooZone-success">' . __('Sync settings saved successfully.', $this->the_plugin->localizationName) . '</div>',
					'form'      => $this->sync_settings(),
				));
 
			} else if ( $action == 'load_products' ) {
				
				$new_paged = $paged < 1 ? 1 : $paged;
				$_SESSION['WooZone_sync']['paged'] = $new_paged;

				extract( $this->build_pagination_vars() );

				$productsList = $this->get_products(array(
					'module' 			=> $module,
					'paged'				=> $paged,
					'posts_per_page'	=> $posts_per_page,
					'filterby_sync_status' => $filterby_sync_status,
					'searchby_what' 	=> $searchby_what,
					'searchby_value' 	=> $searchby_value,
				));
				$html = isset($productsList['html']) ? implode(PHP_EOL, $productsList['html']) : '';
				
				$pagination = $this->get_pagination(array(
					//'position' 		=> 'bottom',
					'with_wrapp' 	=> false,
					'filterby' 		=> isset($module) && 'synchronization' == $module ? true : false,
				));

				$ret = array_replace_recursive($ret, $productsList, array(
					'status'    	=> 'valid',
					'msg'       	=> '',
					'html'      	=> $html,
					'pagination'	=> $pagination,
				));
				
			} else if ( in_array($action, array('paged', 'post_per_page', 'filterby_sync_status', 'searchby_what')) ) {
				
				if ( 'post_per_page' == $action ) {
					$new_post_per_page = $posts_per_page;
	
					if ( $new_post_per_page == 'all' ){
						$_SESSION['WooZone_sync']['posts_per_page'] = 'all';
					}
					else if ( (int)$new_post_per_page == 0 ){
						$max_prods = $this->get_interface_max_products(true);
						$_SESSION['WooZone_sync']['posts_per_page'] = $max_prods;
					}
					else {
						$_SESSION['WooZone_sync']['posts_per_page'] = (int) $new_post_per_page;
					}
	
					// reset the paged as well
					$_SESSION['WooZone_sync']['paged'] = 1;
				}
				else if ( 'filterby_sync_status' == $action ) {
					$_SESSION['WooZone_sync']['filterby_sync_status'] = $filterby_sync_status;

					// reset the paged as well
					$_SESSION['WooZone_sync']['paged'] = 1;
				}
				else if ( 'searchby_what' == $action ) {
					$_SESSION['WooZone_sync']['searchby_what'] = $searchby_what;
					$_SESSION['WooZone_sync']['searchby_value'] = $searchby_value;

					// reset the paged as well
					$_SESSION['WooZone_sync']['paged'] = 1;
				}
				else {
					$new_paged = $paged < 1 ? 1 : $paged;
					$_SESSION['WooZone_sync']['paged'] = $new_paged;
				}
				
				extract( $this->build_pagination_vars() );
				
				$productsList = $this->get_products(array(
					'module' 			=> $module,
					'paged'				=> $paged,
					'posts_per_page'	=> $posts_per_page,
					'filterby_sync_status' => $filterby_sync_status,
					'searchby_what' 	=> $searchby_what,
					'searchby_value' 	=> $searchby_value,
				));
				$html = isset($productsList['html']) ? implode(PHP_EOL, $productsList['html']) : '';
				
				$pagination = $this->get_pagination(array(
					//'position' 		=> 'bottom',
					'with_wrapp' 	=> false,
					'filterby' 		=> isset($module) && 'synchronization' == $module ? true : false,
				));

				$ret = array_replace_recursive($ret, $productsList, array(
					'status'    	=> 'valid',
					'msg'       	=> '',
					'html'      	=> $html,
					'pagination'	=> $pagination,
				));

			} else if ( $action == 'open_variations' ) {
				
				$productsList = $this->get_product_variations(array(
					'module' 			=> $module,
					'prodid'			=> $id,
				));
				$html = isset($productsList['html']) ? implode(PHP_EOL, $productsList['html']) : '';
				
				$ret = array_replace_recursive($ret, $productsList, array(
					'status'    	=> 'valid',
					'msg'       	=> '',
					'html'      	=> $html,
				));

			} else if ( $action == 'sync_prod' ) {

				$ss = self::$sync_options;
				$sync_choose_country = isset($ss['sync_choose_country']) ? $ss['sync_choose_country'] : 'default';

				//:: sync product!
				$syncProdPms = array();

				if ( empty($asin) ) {
					$asin = get_post_meta($id, '_amzASIN', true);
				}

				$country = '';
				if ( 'import_country' == $sync_choose_country ) {
					$country_db = get_post_meta($id, '_amzaff_country', true);
					if ( ! empty($country_db) && is_string($country_db) ) {
						$country = (string) $country_db;
					}
				}

				//$syncStat = $this->syncObj->syncprod_multiple_oldvers( array( $id => $asin ), $country, $syncProdPms );

				$syncProdPms = array_replace_recursive( $syncProdPms, array(
					//'use_cache' => true,
					'verify_sync_date' => false,
					'verify_sync_date_vars' => false,
					//'recurrence' => '',
				));

				//DEBUG
				/*
				$syncProdPms = array_replace_recursive( $syncProdPms, array(
					'DEBUG' => true,
					'verify_sync_date' => false,
					'verify_sync_date_vars' => false,
				));
				*/

				$syncStat = $this->syncObj->syncprod_multiple( array( $id => $asin ), $country, $syncProdPms );

				//:: get post status
				$current_post = get_post( $id, OBJECT );
				$post_status = false;
				$post_parent = 0;
				if ( is_object($current_post) ) {
					$post_status = $current_post->post_status;
					$post_parent = $current_post->post_parent;
				}
				if ( false === $post_status || 'trash' == $post_status ) {
					$ret = array_merge($ret, $syncStat, array(
						'status' => 'valid',
						'is_deleted' => 'yes',
					));
				}
				else {
					// build html table with products rows
					$first_updated_date = (int) get_option('WooZone_sync_first_updated_date', 0);
					//$last_updated_product = (int) get_option('WooZone_sync_last_updated_product', true);
					//$last_selected_product = (int) get_option('WooZone_sync_last_selected_product', true);
					//$next_updated_product = $this->get_next_product( $last_updated_product );

					$is_open = 'no' == $is_open ? false : true;
					$is_child = $post_parent ? true : false;

					$default = array(
						'module'        => $module,
						//'last_id'       => $last_updated_product,
						//'lasts_id'      => $last_selected_product,
						//'next_id'       => $next_updated_product,
						'first_updated_date' => $first_updated_date,
						'current_time' => time(),
						'recurrence' => (int) ( self::$settings['sync_recurrence'] * 3600 ),
					);

					$prods2asin = array();
					$prods2asin["$id"] = (object) array(
						'post_id'		=> $id,
						'meta_value'	=> $asin,
					);

					$thumbs = $this->get_thumbs( array( $id ) );

					$prods2meta = array();
					$__meta_toget = array('_amzaff_sync_last_date', '_amzaff_sync_hits', '_amzaff_sync_last_status', '_amzaff_sync_last_status_msg', '_amzaff_sync_trash_tries', '_amzaff_sync_current_cycle', '_amzaff_country');
					$prods2meta = $prods2meta + $this->the_plugin->get_product_metas( $id, $__meta_toget, array('remove_prefix' => '') );

					$prods2meta_new = array();
					foreach ( (array) $prods2meta as $meta => $meta_value) {
						$prods2meta_new["$meta"] = array();

						$prods2meta_new["$meta"]["$id"] = (object) array(
							'post_id' 	=> $id,
							'meta_value' => $meta_value,
						);
					}

					$sync_last_status = '';
					if ( isset($prods2meta['_amzaff_sync_last_status']) ) {
						$sync_last_status = $this->the_plugin->syncproduct_sanitize_last_status(
							$prods2meta['_amzaff_sync_last_status']
						);
					}
					$row_status_css = '';
					if ( ! empty($sync_last_status) ) {
						$row_status_css = 'wz-last-status-' . $sync_last_status;
					}

					$childs_nb = 0;
					$variations_html = '';
					if ( ! $post_parent ) {
						$has_new_variations = $this->the_plugin->get_product_variations($id);
						$has_new_variations = (int) count( $has_new_variations );
						$childs_nb = $has_new_variations;

						$productsList = $this->get_product_variations(array(
							'module' 			=> $module,
							'prodid'			=> $id,
						));
						$variations_html = isset($productsList['html']) ? implode(PHP_EOL, $productsList['html']) : '';
					}

					$childs_btn = '';
					if ( $childs_nb ) {
						$childs_btn = '<a href="#" class="wz-show-variations' . ($is_open ? ' sign-minus wz-force-open-vars' : ' sign-plus') . '">(<span>' . ($is_open ? '<i class="fa fa-caret-up"></i>' : '<i class="fa fa-caret-down"></i>') . '</span><span class="wz-nbvars">' . $childs_nb . '</span>)</a>';
					}

					$__p = $this->row_build(array_merge($default, array(
						'id'            => $id,
						'val'           => $current_post,
						'prods2asin'    => $prods2asin,
						'thumbs'        => $thumbs,
						'prods2meta'    => $prods2meta_new,
						'is_open'		=> $is_open,
					)));
					$__p = array_merge($__p, array(
						'with_wrapper' 	=> false,
						'id'            => $id,
						'childs_btn' 	=> $childs_btn,

						'parent_id'     => $post_parent,
						'cc'            => 0,
						'childs_nb'     => $childs_nb,
					));
 
					// product
					$row_html = $this->row_view_html($__p, $is_child);

					$ret = array_merge($ret, $syncStat, array(
						'status' => 'valid',
						'is_deleted' => 'no',
						'row_html' => $row_html,
						'row_status_css' => $row_status_css,
						'variations_html' => $variations_html,
					));
				}

			} else if ( $action == 'auto_reload' ) {

				$ss = get_option($this->alias . '_sync');
				$ss = $ss !== false ? $ss : array();
				$ss['sync_stop_reload'] = $request['sync_stop_reload'];

				update_option($this->alias . '_sync', $ss);
				
				$ret = array_merge($ret, array(
					'status'    => 'valid',
					'msg'       => '',
				));
 
			}

			die(json_encode($ret));
		}


		/**
		 * Utils
		 */
		public function get_product_bitly_url_country_flag( $mainaffid ) {
			$pms = array(
				'country2mainaffid' => true,
				'com2us' 			=> true,
				'toupper' 			=> true,
			);

			$flag = $this->the_plugin->get_country2mainaffid( $mainaffid, $pms );

			//$flag = 'com' == $globalid ? 'us' : $globalid;
			//$flag = strtoupper($flag);

			$img_base_url = $this->the_plugin->cfg['paths']["plugin_dir_url"] . 'modules/amazon/images/flags/';

			$img = '<img src="' . $img_base_url . $flag . '-flag.gif" height="15">';

			$countries = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->get_countries( 'country' );
			$country_name = isset($countries["$mainaffid"]) ? $countries["$mainaffid"] : '';

			return array(
				'img' 			=> $img,
				'country_name' 	=> $country_name,
			);
		}

		public function sync_find_parsed_percent( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'witherror' => false,
				'step' => 1,
				'max_retries' => 2,
				'sync_status' => '',
				'sync_nb_remained_products' => '',
				'sync_nb_all_products' => '',
			), $pms);
			extract( $pms );

			if ( ! $witherror && ( 1 == $sync_status ) ) {
				$sync_nb_remained_products = 0;
			}

			$parsed_percent = '0';
			if ( $sync_nb_remained_products <= 0 ) {
				$parsed_percent = '100';
				$sync_nb_remained_products = 0;
			}
			else if ( $sync_nb_all_products <= 0 ) {
				$parsed_percent = '0';
				$sync_nb_remained_products = 0;
				$sync_nb_all_products = 0;
			}
			else {
				$parsed_percent = ( ( $sync_nb_all_products - $sync_nb_remained_products ) * 100 ) / $sync_nb_all_products;
				$parsed_percent = floor( $parsed_percent );
				$parsed_percent = number_format($parsed_percent, 0);
			}

			if ( (float) $parsed_percent > 100 ) {
				$parsed_percent = '100';
			}
			//var_dump('<pre>', $parsed_percent , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			if ( ! $witherror ) {
				$text_sync_prods = sprintf( __('Estimated %s remained products (and product variations) of a %s total to be synced in current cycle (we do not include products added after the current cycle started)', $this->the_plugin->localizationName), '<span>' . $sync_nb_remained_products . '</span>', '<span>' . $sync_nb_all_products . '</span>' );
			}
			else {
				$step = $step > $max_retries ? $max_retries : $step;
				$text_sync_prods = sprintf( __('Re-syncing amazon errors (ex. throttled). Products: %d remained from %d total items to try - step %s from %s', $this->the_plugin->localizationName), $sync_nb_remained_products, $sync_nb_all_products, $step, $max_retries );
			}

			if ( ! $witherror ) {
				return array(
					'text_sync_prods' => $text_sync_prods,
					'parsed_percent' => $parsed_percent,
					'sync_nb_remained_products' => $sync_nb_remained_products,
					'sync_nb_all_products' => $sync_nb_all_products,
				);
			}
			else {
				return array(
					'text_sync_prods_witherror' => $text_sync_prods,
					'parsed_percent_witherror' => $parsed_percent,
					'sync_witherror_nb_remained_products' => $sync_nb_remained_products,
					'sync_witherror_nb_all_products' => $sync_nb_all_products,
				);
			}
		}
	}
}

// Initialize the WooZoneBaseInterfaceSync class
//$WooZoneBaseInterfaceSync = WooZoneBaseInterfaceSync::getInstance();