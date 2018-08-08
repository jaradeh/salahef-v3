<?php
/*
* Define class WooZoneSpeedOptimizator
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;

if (class_exists('WooZoneBaseInterfaceSync') != true) {
	global $WooZone;
	require( $WooZone->cfg['paths']['plugin_dir_path'] . 'modules/synchronization/base_interface.php' );
}

if (class_exists('WooZoneSpeedOptimizator') != true) {
	class WooZoneSpeedOptimizator extends WooZoneBaseInterfaceSync
	{
		/*
		* Some required plugin information
		*/
		const VERSION = '1.0';
		
		static protected $_instance;
		
		public $skip_taxonomies;


		/*
		* Required __construct() function that initalizes the AA-Team Framework
		*/
		public function __construct()
		{
			parent::__construct();

			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/speed_optimization/';
			$this->module_folder_path = $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/speed_optimization/';
			$this->module = $this->the_plugin->cfg['modules']['speed_optimization'];
			$this->skip_taxonomies = array('pa_color', 'color');

			if (is_admin()) {
				add_action('admin_menu', array( &$this, 'adminMenu' ));
			}

			// ajax helper
			add_action('wp_ajax_WooZoneSpeedOptimizatorAjax', array( &$this, 'here_ajax_request' ));

			if( is_admin() && isset($_REQUEST['page']) && $_REQUEST['page'] == 'WooZone_speed_optimization' )
			{
				//wp_enqueue_style( $this->the_plugin->alias . '-speed-optimization', WooZone_asset_path( 'css', $this->module_folder . 'style.css', true ), array(), WooZone_asset_version( 'css' ) );
			}
		}

		/**
		* Singleton pattern
		*
		* @return WooZoneTailSyncMonitor Singleton instance
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
		   self::getInstance()->_registerAdminPages();
		}

		/**
		* Register plug-in module admin pages and menus
		*/
		protected function _registerAdminPages()
		{ 
			add_submenu_page(
				$this->the_plugin->alias,
				$this->the_plugin->alias . " " . __('Speed Optimization', $this->the_plugin->localizationName),
				__('Speed Optimization'),
				'manage_options',
				$this->the_plugin->alias . "_speed_optimization",
				array($this, 'display_index_page')
			);

			return $this;
		}

		public function display_index_page()
		{
			$this->printBaseInterface();
		}
		
		
		/*
		* printBaseInterface, method
		* --------------------------
		*
		* this will add the base DOM code for you options interface
		*/
		public function printBaseInterface( $module='speed_optimization' ) {
			global $wpdb, $product;
			
			$ss = self::$settings;

			$mod_vars = array();

			// Sync
			$mod_vars['mod_menu'] = 'info|speed_optimization';
			$mod_vars['mod_title'] = __('Speed Optimization', $this->the_plugin->localizationName);
 
			extract($mod_vars);

			$module_data = $this->the_plugin->cfg['modules']["$module"];
			$module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . "modules/$module/";

			$module_data_sync = $this->the_plugin->cfg['modules']["synchronization"];
			$module_folder_sync = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/synchronization/';
?>
		<script type="text/javascript">
			var speed_optimization_msg = {
				'loading': '<?php _e('Loading ...', 'woozone'); ?>',
				'delete_img': '<?php _e('Deleting the image ...', 'woozone'); ?>',
				'optimize_attr': '<?php _e('Optimizing attributes ...', 'woozone'); ?>',
				'delete_variation': '<?php _e('Deleting the variation ...', 'woozone'); ?>',
				'delete_variations': '<?php _e('Deleting selected variations ...', 'woozone'); ?>',
				'remove_categ': '<?php _e('Removing category from product ...', 'woozone'); ?>',
				'remove_categs': '<?php _e('Removing selected categories from product ...', 'woozone'); ?>',
				'start_optimize_info': '<?php _e('If you have a large number of products, this operation can take a while, please be patient.', 'woozone'); ?>',
				'done': '<?php _e('Done', 'woozone'); ?>',
				'done_all_products': '<?php _e('All products are optimized as you requested.', 'woozone'); ?>',
				'no_variations_msg': '<?php _e('This product has no variations.', 'woozone'); ?>',
				'no_variations_disclaimer': '<?php _e('<strong>DISCLAIMER:</strong> If you remove all variations, the products will be converted to simple products and you will be unable to use the OnSite Cart & 90 Days Cookie! Please disable them in Amazon Config.', 'woozone'); ?>',
				'confirm': {
					'image': '<?php _e('Are you sure you want to delete this image?', 'woozone'); ?>',
					'attr': '<?php _e('Are you sure you want to optimize the attributes?', 'woozone'); ?>',
					'categ': '<?php _e('Are you sure you want to remove the product from this category?', 'woozone'); ?>',
					'categs': '<?php _e('Are you sure you want to remove the product from the selected categories?', 'woozone'); ?>',
					'variation': '<?php _e('Are you sure you want to delete this variation?', 'woozone'); ?>',
					'variations': '<?php _e('Are you sure you want to delete the selected variations?', 'woozone'); ?>',
					'mass_optimize_empty_products': '<?php _e('Please select products from the list to mass-optimize.', 'woozone'); ?>',
				},
				'confirm_ok': '<?php _e('OK', 'woozone'); ?>',
				'confirm_cancel': '<?php _e('Cancel', 'woozone'); ?>',
				'confirm_close': '<?php _e('Close', 'woozone'); ?>',
				'confirm_resume': '<?php _e('Resume', 'woozone'); ?>',
				'confirm_stop': '<?php _e('STOP', 'woozone'); ?>'
			}
		</script>
		<?php
			echo WooZone_asset_path( 'js', $module_folder_sync . 'app.synchronization.js', false );
			echo WooZone_asset_path( 'js', $module_folder . 'app.product_optimization.class.js', false );
		?>
		 
		<div id="<?php echo WooZone()->alias?>" class="<?php echo WooZone()->alias?>-speed-optimization">

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
						
						<div class="panel-heading WooZone-panel-heading">
							<h2><?php echo $mod_title; ?></h2>
							<span class="WooZone-total-products-wrapper">
								<?php _e('Total products', $this->the_plugin->localizationName);?>: <span class="wzone_count_total_products"></span> (<span class="wzone_countv"></span> variations)
							</span>
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
												   'loading'              => __('Loading..', 'WooZone'),
											   );
											?>
											<div id="WooZone-lang-translation" style="display: none;"><?php echo htmlentities(json_encode( $lang )); ?></div>
	
											<?php if (1) { ?>
											<div class="WooZone-sync-info WooZone-box-stats">
												<button class="<?php echo WooZone()->alias; ?>-optimise-all-attributes" data-type="mass-optimize"><?php _e('Mass Speed Optimize Products', 'woozone'); ?></button>
												  <?php echo $this->get_pagination(array(
														'position'      => 'top',
														'with_wrapp'    => true
												  )); ?>
											</div>
											<?php } ?>
											
											<div class="WooZone-sync-table <?php echo ( 'stats_prod' ); ?>">
											  <table cellspacing="0">
												<thead>
													<tr class="WooZone-sync-table-header">
														<th style="width:3%;"><input type="checkbox" class="check-uncheck-all"/></th>
														<th style="width:3%;"><?php _e('ID', $this->the_plugin->localizationName);?></th>
														<th style="width:16%;"><?php _e('Product', $this->the_plugin->localizationName);?></th>
														<th style="width:47%;"><?php _e('Title', $this->the_plugin->localizationName);?></th>
														<th style=""><?php _e('Score', $this->the_plugin->localizationName);?></th>
														<th style=""><?php _e('Action', $this->the_plugin->localizationName);?></th>
														<th style=""><?php _e('Date Added', $this->the_plugin->localizationName);?></th>
													</tr>
												</thead>
												<tbody><!-- ajax content --></tbody>
											  </table>
											</div>
											<?php if (1) { ?>
											<div class="WooZone-sync-info WooZone-box-stats">
												<button class="<?php echo WooZone()->alias; ?>-optimise-all-attributes" data-type="mass-optimize"><?php _e('Mass Speed Optimize Products', 'woozone'); ?></button>
												  <?php echo $this->get_pagination(array(
														'position'      => 'bottom',
														'with_wrapp'    => true
												  )); ?>
											</div>
											<?php } ?>
											
											<?php $this->optimization_notes_legend(); ?>
										</div>
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

		public function get_type_score( $type, $count, $mode=null, $product_id = 0 )
		{
			if( $type == 'children' ) {
				$type_name = __('Variations', 'woozone');
				$remove_percent = '40%';
			}else if( $type == 'attachments' ) {
				$type_name = __('Images', 'woozone');
				$remove_percent = '10%';
			}else if( $type == 'categories' ) {
				$type_name = __('Categories', 'woozone');
				$remove_percent = '20%';
			}else if( $type == 'woo_attributes' ) {
				$type_name = __('Attributes', 'woozone');
				$remove_percent = '30%';
			}
			  
			$score = $this->build_score( array($type => $count ), true, $product_id );
			$_the_score_int = floor( $score['score'] );
			
			$final_score = 'A';
			$note_msg = __('That\'s Great!', 'woozone');
			
			if( $_the_score_int == 2 ) {
				$final_score = 'B';
				$note_msg = sprintf( __('We recommend optimizing the %s', 'woozone'), $type_name );
			}else if( $_the_score_int == 3 ) {
				$final_score = 'C';
				$note_msg = sprintf( __('Poor! <i>Needs more work</i>', 'woozone'), $remove_percent . '%', $type_name );
			}else if( $_the_score_int == 4 ) {
				$final_score = 'D';
				$note_msg = __('Bad', 'woozone');
			}
			
			if( $mode == 'box' ) {
				
				$html = array();
				
				$html[] = '<div class="' . ( WooZone()->alias ) . '-optimizer-notice score-' . ( $final_score ) . '">';
				$html[] = 	'<span class="type-score">' . ( $final_score ) . '</span>';
				$html[] = 	'<h4>' . ( __('This product has:', 'woozone') ) . '</h4>';
				$html[] = 	'<div><strong><span>' . ( $count ) . '</span> ' . ( $type_name ) . '</strong> <p>(' . ( $note_msg ) . ')</p></div>';
				$html[] = '</div>';
				
				return implode("\n", $html);
				
			}
			
			return $final_score;
		}
		
		public function optimization_notes_legend()
		{
			$html = array();
			
			$legend_notes = array(
				'A' => array(
					'note' => __('A', 'woozone'),
					'score' => '100',
					'hint' => __('Optimized!', 'woozone'),
				),
				'B' => array(
					'note' => __('B', 'woozone'),
					'score' => '75 / 100',
					'hint' => __('Needs work', 'woozone'),
				),
				'C' => array(
					'note' => __('C', 'woozone'),
					'score' => '50 / 100',
					'hint' => __('Poor!', 'woozone') . ' <span><i>' . __('(needs more work)', 'woozone') . '</i></span>',
				),
				'D' => array(
					'note' => __('D', 'woozone'),
					'score' => '25 / 100',
					'hint' => __('Bad', 'woozone'),
				),
			);
			
			
			$html[] = '<div class="WooZone-speedoptimization-legend">';
			$html[] = '<strong>' . ( __('LEGEND', 'woozone') ) . '</strong>';
			$html[] = '<ul>';
			
			foreach( $legend_notes as $note => $legend ) {
				$html[] = '<li class="WooZone-legend-score-' . ( $legend['note'] ) . '">';
				$html[] = 	'<span class="WooZone-legend-final-score">' . ( $note ) . '</span>';
				$html[] = 	'<span class="WooZone-legend-info">';
				$html[] = 		'<span class="WooZone-legend-score">' . __('SCORE', 'woozone') . ' ' . ( $legend['score'] ) . '</span>';
				$html[] = 		'<span class="WooZone-legend-hint">' . ( $legend['hint'] ) . '</span>';
				$html[] = 	'</span>';
				$html[] = '</li>';
			} 
			
			$html[] = '</ul>';
			$html[] = '</div>';
			
			return implode("\n", $html);
		}


		/**
		 * Ajax requests
		 */
		public function here_ajax_request()
		{
			global $wpdb;
			$request = array(
				'action' => isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : ''
			);
			extract($request);

			$ret = array(
				'status'    => 'invalid',
				'msg'       => '',
			);
			
			$info_msg = array(
				'attachments' => __('Images occupy a significant amount of visual space. As a result, optimizing images can often yield some of the largest byte savings and performance improvements for your website. Eliminate unnecessary image resources!', 'woozone'),
				'woo_attributes' => __('Amazon products come with a lot of attributes. Only one product can have hundred of attributes. This might cause your site to slow down. That’s why we developed a tool that helps you optimize the attributes in just a click!', 'woozone'),
				'children' => __('If your product has many variations , this will surely increase your website loading time. We recomend keeping maximum <strong>5 variations</strong> per product.<br/><br/><strong>DISCLAIMER:</strong> If you remove all variations, the products will be converted to simple products and you will be unable to use the OnSite Cart & 90 Days Cookie! Please disable them in Amazon Config.' ,'woozone'),
				'categories' => __('If you’re using the "Use Category from Amazon" option upon import, you might notice that the whole categories tree gets imported with the product. Using this tool, you can leave fewer categories. This increases your website speed.', 'woozone'),
				'mass-optimize' => __('This tool will help you mass optimize all your products based on the options you setup here.', 'woozone'),
			);
			
			if( $action == 'get_mass_optimize_popup' ) {
				$request['products'] = $_REQUEST['product'] != '' ? explode('&', $_REQUEST['product']) : null;
				
				$html = array();
				
				$html[] = '<div id="' . WooZone()->alias . '-speed-optimization-lightbox">';
				$html[] = '<div class="' . WooZone()->alias . '-lightbox-content ' . WooZone()->alias . '-mass-optimize-content">';
				
				$html[] = '<div class="' . ( WooZone()->alias ) . '-mass-optimizer">';
				$html[] = '<h1>' . __('MASS OPTIMIZE ALL PRODUCTS', 'woozone') . '</h1>';
				
				$html[] = '<div class="' . ( WooZone()->alias ) . '-lightbox-the-content">';
					
					/*$html[] = '<div id="' . WooZone()->alias . '-optimizer-notice">';
					$html[] = 	$this->get_type_score( false, false, 'notice' );
					$html[] = '</div>';*/
					
					$html[] = '<div class="' . ( WooZone()->alias ) . '-optimizer-notice important">';
					$html[] = 	'<span class="type-score"><i class="fa fa-exclamation"></i></span>';
					$html[] = 	'<h4>' . ( __('Important', 'woozone') ) . '</h4>';
					$html[] = 	'<div><strong>' . ( __('This feature does not have a rollback option!', 'woozone') ) . '</strong></div>';
					$html[] = '</div>';
					
					$html[] = 		'<form name="mass-optimize-options" method="post">';
					
					$html[] = 		'<div class="' . ( WooZone()->alias ) . '-mass-optimize-option-wrapper">';
					$html[] =			'<label><strong>' . ( __('IMAGES', 'woozone') ) . '</strong> <span>' . ( __('(Select how many images to leave on each product)', 'woozone') ) . '</span>';
					$html[] = 				'<select name="mass-optimize-images">';
					$html[] = 					'<option value="only_featured">' . ( __('Leave only featured image', 'woozone') ) . '</option>';
					
												for( $i = 1; $i<=10; $i++ ) {
													$html[] = '<option value="' . ( $i ) . '">' . ( $i ) . '</option>';
												}
					
					$html[] = 				'</select>';
					$html[] = 			'</label>';
					$html[] = 		'</div>';
					
					$html[] = 		'<div class="' . ( WooZone()->alias ) . '-mass-optimize-option-wrapper">';
					$html[] =			'<label><strong>' . ( __('ATTRIBUTES', 'woozone') ) . '</strong> <span>' . ( __('(Select if you want to optimize attributes for all products)', 'woozone') ) . '</span>';
					$html[] = 				'<select name="mass-optimize-attributes">';
					$html[] = 					'<option value="yes">' . ( __('YES', 'woozone') ) . '</option>';
					$html[] = 					'<option value="no">' . ( __('NO', 'woozone') ) . '</option>';
					$html[] = 				'</select>';
					$html[] = 			'</label>';
					$html[] = 		'</div>';
					
					$html[] = 		'<div class="' . ( WooZone()->alias ) . '-mass-optimize-option-wrapper">';
					$html[] =			'<label><strong>' . ( __('VARIATIONS', 'woozone') ) . '</strong> <span>' . ( __('(Select how many variations to leave on each product)', 'woozone') ) . '</span>';
					$html[] = 				'<select name="mass-optimize-variations">';
												for( $i = 30; $i>=1; $i-- ) {
													$html[] = '<option value="' . ( $i ) . '" ' . ( $i==5 ? 'selected="selected"' : '' ) . '>' . ( $i ) . '</option>';
												}
					$html[] = 					'<option value="0">' . ( __('No variations', 'woozone') ) . '</option>';
					$html[] = 				'</select>';
					$html[] = 			'</label>';
					$html[] = 		'</div>';
					
					$html[] = 		'<div class="' . ( WooZone()->alias ) . '-mass-optimize-option-wrapper">';
					$html[] =			'<label><strong>' . ( __('CATEGORIES', 'woozone') ) . '</strong> <span>' . ( __('(Select how many categories to leave on each product)', 'woozone') ) . '</span>';
					$html[] = 				'<select name="mass-optimize-categories">';
												for( $i = 10; $i>=1; $i-- ) {
													$html[] = '<option value="' . ( $i ) . '" ' . ( $i==3 ? 'selected="selected"' : '' ) . '>' . ( $i ) . '</option>';
												}
					$html[] = 				'</select>';
					$html[] = 			'</label>';
					$html[] = 		'</div>';
					
					$html[] = 		'</form>';
					
				$html[] =   '</div>';
				$html[] =   '</div>';
					
					$html[] = '<div class="' . WooZone()->alias . '-info-box">';
					$html[] = 	'<span class="' . WooZone()->alias . '-info"><i class="fa fa-info" aria-hidden="true"></i>' . ( $info_msg['mass-optimize'] ) . '</span>';
					$html[] = 	'<a href="#" class="' . WooZone()->alias . '-small-popup-btn ' . WooZone()->alias . '-close-popup-btn">' . ( __('CANCEL', 'woozone') ) . '</a>';
					$html[] = 	'<a href="#" class="' . WooZone()->alias . '-big-popup-btn ' . WooZone()->alias . '-mass-optimize-btn">' . ( __('MASS OPTIMIZE', 'woozone') ) . '</a>';
					$html[] = '</div>';
									
				$html[] = '</div>';
				$html[] = '</div>';
				
				$ret = array_merge($ret, array(
					'status'    => 'valid',
					'html'       => implode( "\n", $html ),
				));
			}
			
			if( $action == 'get_product_popup' ) {
				$request['product'] = (int)$_REQUEST['product'] > 0 ? (int)$_REQUEST['product'] : 0;
				$get_variations = $this->show_product_variations( $request['product'], true );
				
				// load default
				$attachments = get_attached_media( 'image', $request['product'] );
				 
				$html = array();
				
				$html[] = '<div id="' . WooZone()->alias . '-speed-optimization-lightbox">';
				$html[] = '<div class="' . WooZone()->alias . '-lightbox-content">';
				
				$html[] = '<h1>' . __('You have the possibility to optimize the following', 'woozone') . '</h1>';
				$html[] = '<div id="' . WooZone()->alias . '-total-score">';
				$html[] = 	$this->print_stats( array('id' => $request['product'], 'childs_btn' => $get_variations['count']) );
				$html[] = '</div>';
				 
				$images_score = $this->get_type_score( 'attachments', count($attachments) );
				$attributes_score = $this->get_type_score( 'woo_attributes', $this->count_woo_terms_by_post_id( $request['product'], true ), false, $request['product'] );
				$variations_score = $this->get_type_score( 'children', $get_variations['count'] );
				$_get_categories = $this->get_prod_categories( $request['product'] );
				$categories_score = $this->get_type_score( 'categories', $_get_categories['count'] );
				
				$html[] = '<ul class="' . WooZone()->alias . '-optimize-options">';
				$html[] = 	'<li id="speed-optimize_images-score" data-product="' . ( $request['product'] ) . '" data-type="attachments" class="active"><span class="score-' . ( strtolower($images_score) ) . '">' . ( strtolower($images_score) ) . '</span> ' . ( __('Images', 'woozone') ) . '</li>';
				$html[] = 	'<li id="speed-optimize_attributes-score" data-product="' . ( $request['product'] ) . '" data-type="woo_attributes"><span class="score-' . ( strtolower($attributes_score) ) . '">' . ( strtolower($attributes_score) ) . '</span> ' . ( __('Attributes', 'woozone') ) . '</li>';
				$html[] = 	'<li id="speed-optimize_variations-score" data-product="' . ( $request['product'] ) . '" data-type="children"><span class="score-' . ( strtolower($variations_score) ) . '">' . ( strtolower($variations_score) ) . '</span> ' . ( __('Variations', 'woozone') ) . '</li>';
				$html[] = 	'<li id="speed-optimize_categories-score" data-product="' . ( $request['product'] ) . '" data-type="categories"><span class="score-' . ( strtolower($categories_score) ) . '">' . ( strtolower($categories_score) ) . '</span> ' . ( __('Categories', 'woozone') ) . '</li>';
				$html[] = '</ul>';
				
				$html[] = '<div class="' . ( WooZone()->alias ) . '-lightbox-the-content">';
					
					$html[] = '<div id="' . WooZone()->alias . '-optimizer-notice">';
					$html[] = 	$this->get_type_score( 'attachments', count($attachments), 'box' );
					$html[] = '</div>';
					
					// load default tab - images
					// Product Images Panel
					$html[] =   '<div class="' . ( WooZone()->alias ) . '-image-optimizer">';
	
					if( count($attachments) > 0 ) {
						
						$html[] =   '<ul>';
						foreach ($attachments as $att_id => $att) {
							$post_thumbnail_id = get_post_thumbnail_id( $request['product'] );
							$extra_class = array();
							$image_size = wp_get_attachment_metadata( $att_id );
							if( $image_size['width'] > $image_size['height'] ) {
								$extra_class = array('class' => 'height-auto');
							}  
							$thumbnail = wp_get_attachment_image( $att_id, array(75, 75), false, $extra_class );
							$full_img = wp_get_attachment_image_src( $att_id, 'full' );
							$full_img = $full_img[0];
							
							$html[] =   '<li>';
							$html[] =       '<a href="' . ( $full_img ) . '" target="_blank">';
							$html[] =           $thumbnail;
							$html[] =       '</a>';
							$html[] =       '<div class="' . ( WooZone()->alias ) . '-img-options">';
							if( $post_thumbnail_id == $att_id ){
								$html[] =       '<span class="' . ( WooZone()->alias ) . '-is-futured">' . ( __('Featured Image', 'woozone') ) . '</span>';
							}else{
								$html[] =       '<a href="#" data-attachment="' . ( $att_id ) . '" data-product="' . ( $request['product'] ) . '" class="' . ( WooZone()->alias ) . '-delete-img">' . ( __('Delete', 'woozone') ) . '</a>';
							}
	
							$html[] =       '</div>';
							$html[] =   '</li>';
						}
						$html[] =   '</ul>';
						
					}else{
						
						$html[] = '<h3 style="text-align:center;">' . __('This product has no images.', 'woozone') . '</h3>';
						
					}
					
	
					$html[] =   '</div>';
					
					$html[] = '<div class="' . WooZone()->alias . '-info-box">';
					$html[] = 	'<span class="' . WooZone()->alias . '-info"><i class="fa fa-info" aria-hidden="true"></i>' . ( $info_msg['attachments'] ) . '</span>';
					$html[] = 	'<a href="#" class="' . WooZone()->alias . '-big-popup-btn ' . WooZone()->alias . '-close-popup-btn">' . ( __('CLOSE', 'woozone') ) . '</a>';
					$html[] = '</div>';
									
				$html[] = '</div>';
				
				$html [] = $this->optimization_notes_legend();
				
				$html[] = '</div>';
				$html[] = '</div>';
				
				$ret = array_merge($ret, array(
					'status'    => 'valid',
					'html'       => $this->the_plugin->_parse_page_fix_amazon( implode( "\n", $html ) ),
				));
			}

			if ( $action == 'get_optimize_type_data' ) {
				
				$request = array(
					'product'	=> (int)$_REQUEST['product'] > 0 ? (int)$_REQUEST['product'] : 0,
					'type'		=> in_array($_REQUEST['type'], array('attachments','children','woo_attributes','categories')) ? $_REQUEST['type'] : 0
				);
				extract($request);
				
				$ret = array(
					'status'    => 'invalid',
					'msg'       => '',
				);
				
				if( $product > 0 && $type != '' ) {
					
					if ( $type == 'attachments' ) {
						
						$attachments = get_attached_media( 'image', $product );
						 
						$html[] = $this->get_type_score( 'attachments', count($attachments), 'box' );
						
						// Product Images Panel
						$html[] =   '<div class="' . ( WooZone()->alias ) . '-image-optimizer">';
		
						if( count($attachments) > 0 ){
							
							$html[] =   '<ul>';
							foreach ($attachments as $att_id => $att) {
								$post_thumbnail_id = get_post_thumbnail_id( $product );
								$thumbnail = wp_get_attachment_image( $att_id, array(75, 75) );
								$full_img = wp_get_attachment_image_src( $att_id, 'full' );
								$full_img = $full_img[0];
		
								$html[] =   '<li>';
								$html[] =       '<a href="' . ( $full_img ) . '" target="_blank">';
								$html[] =           $thumbnail;
								$html[] =       '</a>';
								$html[] =       '<div class="' . ( WooZone()->alias ) . '-img-options">';
								if( $post_thumbnail_id == $att_id ){
									$html[] =       '<span class="' . ( WooZone()->alias ) . '-is-futured">Featured Image</span>';
								}else{
									$html[] =       '<a href="#" data-attachment="' . ( $att_id ) . '" data-product="' . ( $request['product'] ) . '" class="' . ( WooZone()->alias ) . '-delete-img">delete</a>';
								}
		
								$html[] =       '</div>';
								$html[] =   '</li>';
							}
							$html[] =   '</ul>';
							
						}else{
							
							$html[] = '<h3 style="text-align:center;">' . __('This product has no images.', 'woozone') . '</h3>';
							
						}
						
		
						$html[] =   '</div>';
						
						$html[] = '<div class="' . WooZone()->alias . '-info-box">';
						$html[] = 	'<span class="' . WooZone()->alias . '-info"><i class="fa fa-info" aria-hidden="true"></i>' . ( $info_msg[ $_REQUEST['type'] ] ) . '</span>';
						$html[] = 	'<a href="#" class="' . WooZone()->alias . '-big-popup-btn ' . WooZone()->alias . '-close-popup-btn">' . ( __('CLOSE', 'woozone') ) . '</a>';
						$html[] = '</div>';
						
					}else if ( $type == 'woo_attributes' ) {
						// check if attributes already cached
						$count_cached_attributes = $this->count_cached_terms( $request['product'] );
						
						$html[] = $this->get_type_score( 'woo_attributes', $this->count_woo_terms_by_post_id( $request['product'], true ), 'box', $request['product'] );
						
						// Attributes Panel
						$html[] =   '<div class="' . ( WooZone()->alias ) . '-attributes-optimizer">';
						if( !get_post_meta($request['product'], '_cached_product_terms', true) ) {
							$html[] = 		'<a href="#" data-product="' . ( $product ) . '" class="' . ( WooZone()->alias ) . '-optimize-attributes">' . __('Optimize Attributes', 'woozone') . '</a>';
						}
						$html[] = 		'<p class="WooZone-optimised-attributes" ' . ( $count_cached_attributes && $count_cached_attributes > 0 ? 'style="display:block;"' : '') . '><i class="fa fa-check" aria-hidden="true"></i> &nbsp; <span id="WooZone-optimised-attributes">' . ( $count_cached_attributes && $count_cached_attributes > 0 ? $count_cached_attributes : '' ) . '</span> ' . __('Optimized attributes', 'woozone') .' </p>';
						
						$html[] = 		'';
		
						$html[] =   '</div>';
						
						$html[] = '<div class="' . WooZone()->alias . '-info-box">';
						$html[] = 	'<span class="' . WooZone()->alias . '-info"><i class="fa fa-info" aria-hidden="true"></i>' . ( $info_msg[ $_REQUEST['type'] ] ) . '</span>';
						$html[] = 	'<a href="#" class="' . WooZone()->alias . '-big-popup-btn ' . WooZone()->alias . '-close-popup-btn">' . ( __('CLOSE', 'woozone') ) . '</a>';
						$html[] = '</div>';
						
					}else if ( $type == 'children' ) {
						
						$get_variations = $this->show_product_variations( $product );
						
						$html[] = $this->get_type_score( 'children', $get_variations['count'], 'box' );
						
						// Variations Panel
						$html[] =   '<div class="' . ( WooZone()->alias ) . '-variations-optimizer">';
						
						$html[] = 		'<div id="WooZone-product-variations-wrapper">';
						$html[] = 			$get_variations['html'];
						$html[] = 		'</div>';
		
						$html[] =   '</div>';
						
						$html[] = '<div class="' . WooZone()->alias . '-info-box">';
						$html[] = 	'<span class="' . WooZone()->alias . '-info"><i class="fa fa-info" aria-hidden="true"></i>' . ( $info_msg[ $_REQUEST['type'] ] ) . '</span>';
						$html[] = 	'<a href="#" class="' . WooZone()->alias . '-big-popup-btn ' . WooZone()->alias . '-close-popup-btn">' . ( __('CLOSE', 'woozone') ) . '</a>';
						$html[] = '</div>';
						
					}else if( $type == 'categories' ) {
						
						$get_categories = $this->get_prod_categories( $product );
						
						$html[] = $this->get_type_score( 'categories', $get_categories['count'], 'box' );
						
						// Categories Panel
						$html[] =   '<div class="' . ( WooZone()->alias ) . '-categories-optimizer">';
						$html[] = 		$get_categories['html'];
						$html[] =   '</div>';
						
						$html[] = '<div class="' . WooZone()->alias . '-info-box">';
						$html[] = 	'<span class="' . WooZone()->alias . '-info"><i class="fa fa-info" aria-hidden="true"></i>' . ( $info_msg[ $_REQUEST['type'] ] ) . '</span>';
						$html[] = 	'<a href="#" class="' . WooZone()->alias . '-big-popup-btn ' . WooZone()->alias . '-close-popup-btn">' . ( __('CLOSE', 'woozone') ) . '</a>';
						$html[] = '</div>';
						
					}
					
					$ret = array_merge($ret, array(
						'status'    => 'valid',
						'html'       => $this->the_plugin->_parse_page_fix_amazon( implode( "\n", $html ) ),
					));	
				}
			}

			if ( $action == 'delete_image' ) {

				$request['attachment'] = (int)$_REQUEST['attachment'] > 0 ? (int)$_REQUEST['attachment'] : 0;
				$request['product'] = (int)$_REQUEST['product'] > 0 ? (int)$_REQUEST['product'] : 0;
				
				if( $request['product'] > 0 && $request['attachment'] > 0 ){
					if( $this->delete_image( $request['product'], $request['attachment'] ) ) {
						$ret = array_merge($ret, array(
							'status'    => 'valid',
							'msg'       => '',
						));
					}
				}
 
			}

			if ( $action == 'optimise_attributes' ) {
				
				$request['product'] = (int)$_REQUEST['product'] > 0 ? (int)$_REQUEST['product'] : 0;
				
				$ret = $this->optimize_attributes( $request['product'] );
				
			}

			if ( $action == 'delete_variation' ) {
				
				$request['product'] = (int)$_REQUEST['product'] > 0 ? (int)$_REQUEST['product'] : 0;
				$request['variation'] = (int)$_REQUEST['variation'] > 0 ? (int)$_REQUEST['variation'] : 0;
				 
				if( $request['variation'] > 0 ){
					$variation_img = get_post_thumbnail_id( $request['variation'] );
					
					if( $variation_img > 0 ) {
						// check if other products/variations has image/gallery set with this attachment id
						$check_img_set = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(meta_id) FROM ".$wpdb->prefix."postmeta WHERE post_id!=%d AND (meta_key='_thumbnail_id' OR meta_key='_product_image_gallery') AND meta_value=%d", $request['variation'], $variation_img ) );
						
						if( $check_img_set == 0 ) {
							wp_delete_attachment( $variation_img, true );
						}
					}
					
					if( wp_delete_post( $request['variation'], true ) ) {
						$variations_count = $this->show_product_variations( $request['product'], true );
						
						// If no variations left, set product type to "Simple"{
						if( $variations_count['count'] == 0 ) {
							// get simple product type id
							$simple_type_id = $wpdb->query("SELECT term_id from " . $wpdb->prefix . "terms where name='simple'");
							wp_set_object_terms( $request['product'], $simple_type_id, 'product_type' );
						}
						
						$ret = array_merge($ret, array(
							'status'    => 'valid',
							'new_count' => $variations_count['count'],
							'msg'       => __('Variation deleted.', 'woozone'),
						));
					}
				}
 
			}

			if( $action == 'delete_selected_variations' ) {
				
				$request['product'] = (int)$_REQUEST['product'] > 0 ? (int)$_REQUEST['product'] : 0;
				$request['variations'] = $_REQUEST['variations'] != '' ? explode('&', $_REQUEST['variations']) : null;
				$request['variations_img'] = $_REQUEST['variations_img'] != '' ? explode('&', $_REQUEST['variations_img']) : null;
				 
				if( is_array($request['variations']) && count($request['variations']) > 0 ) {
					$variations_removed = array();
					  
					foreach( $request['variations'] as $key => $variation ) {
						$_variation = explode('=', $variation);
						$variation = (int) end($_variation);
						
						$variation_img = get_post_thumbnail_id( $variation );
						  
						if( $variation_img > 0 ) {
							// check if other products/variations has image/gallery set with this attachment id
							$check_img_set = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(meta_id) FROM ".$wpdb->prefix."postmeta WHERE post_id!=%d AND (meta_key='_thumbnail_id' OR meta_key='_product_image_gallery') AND meta_value=%d", $variation, $variation_img ) );
							
							if( $check_img_set == 0 ) {
								wp_delete_attachment( $variation_img, true );
							}
						}
						
						if( wp_delete_post( $variation, true ) ) {
							$variations_removed[] = $variation;
						}
					}
					
					if( count($variations_removed) > 0 ) {
						$count_variations = $this->show_product_variations( $request['product'], true );
						
						// If no variations left, set product type to "Simple"{
						if( $count_variations['count'] == 0 ) {
							// get simple product type id
							$simple_type_id = $wpdb->query("SELECT term_id from " . $wpdb->prefix . "terms where name='simple'");
							wp_set_object_terms( $request['product'], $simple_type_id, 'product_type' );
						}
						
						$ret = array_merge($ret, array(
							'status'    => 'valid',
							'new_count' => $count_variations['count'],
							'variations_removed' => $variations_removed,
							'msg'       => sprintf( __('%s variations removed.', 'woozone'), count($variations_removed) )
						));
					}else{
						$ret = array_merge($ret, array(
							'status'    => 'valid',
							'msg'       => __('Error removing variations!', 'woozone')
						));
					}
				}else{
					$ret = array_merge($ret, array(
						'status'    => 'valid',
						'msg'       => __('No variations selected. Use the checkboxes above to select what variations to delete.', 'woozone')
					));
				}
			}

			if ( $action == 'remove_from_categ' ) {
				
				$request['product'] = (int)$_REQUEST['product'] > 0 ? (int)$_REQUEST['product'] : 0;
				$request['categ'] = (int)$_REQUEST['categ'] > 0 ? (int)$_REQUEST['categ'] : 0;
				 
				if( $request['product'] > 0 && $request['categ'] > 0 ){
					if( wp_remove_object_terms( $request['product'], $request['categ'], 'product_cat' ) ) {
						$categ_count = $this->get_prod_categories( $request['product'], true );
						
						$ret = array_merge($ret, array(
							'status'    => 'valid',
							'new_count' => $categ_count['count'],
							'msg'       => __('Category removed from product.', 'woozone'),
						));
					}
				}
 
			}

			if ( $action == 'remove_from_categs' ) {
				
				$request['product'] = (int)$_REQUEST['product'] > 0 ? (int)$_REQUEST['product'] : 0;
				$request['categs'] = $_REQUEST['categs'] != '' ? explode('&', $_REQUEST['categs']) : null;
				 
				if( is_array($request['categs']) && count($request['categs']) > 0 ) {
					$categs_removed = array();
					  
					foreach( $request['categs'] as $key => $_categ ) {
						$_categ = explode('=', $_categ);
						$categ = (int) end($_categ);
						  
						if( wp_remove_object_terms( $request['product'], $categ, 'product_cat' ) ) {
							$categs_removed[] = $categ;
						}
					}
					  
					if( count($categs_removed) > 0 ) {
						$categ_count = $this->get_prod_categories( $request['product'], true );
						
						$ret = array_merge($ret, array(
							'status'    => 'valid',
							'new_count' => $categ_count['count'],
							'categs_removed' => $categs_removed,
							'msg'       => sprintf( __('%s categories removed from product.', 'woozone'), count($categs_removed) )
						));
					}else{
						$ret = array_merge($ret, array(
							'status'    => 'valid',
							'msg'       => __('Error removing categories!', 'woozone')
						));
					}
				}else{
					$ret = array_merge($ret, array(
						'status'    => 'valid',
						'msg'       => __('No categories where selected. Use the checkboxes above to select what categories to remove.', 'woozone')
					));
				}

			}

			if( $action == 'get_all_scores' ) {
				
				$request['product'] = (int)$_REQUEST['product'] > 0 ? (int)$_REQUEST['product'] : 0;
				$request['type'] = isset($_REQUEST['type']) && $_REQUEST['type'] != '' ? $_REQUEST['type'] : false;
				$request['where'] = isset($_REQUEST['where']) && $_REQUEST['where'] != '' ? $_REQUEST['where'] : false;
				$type_count = array();
				
				if( isset($request['product']) && $request['product'] > 0 ) {
					$ret = array(
						'new_total_score' => '',
						'new_type_score' => '',
						'new_notice_score' => '',
					);
					
					$type_count['attachments'] = count( get_attached_media( 'image', $request['product'] ) );
						
					$type_count['woo_attributes'] = $this->count_woo_terms_by_post_id( $request['product'], true );
						
					$get_variation_count = $this->show_product_variations( $request['product'], true );
					$type_count['children'] = $get_variation_count['count'];
					
					$get_categories = $this->get_prod_categories( $request['product'] );
					$type_count['categories'] = $get_categories['count'];
					 
					if( $request['type'] != false ) {
						// Get New Type Score
						$ret['new_type_score'] = $this->get_type_score( $request['type'], $type_count[$request['type']] );
						
						// Get New Notice Score
						$ret['new_notice_score'] = $this->get_type_score( $request['type'], $type_count[$request['type']], 'box' );
						
						// recount type
						$ret['count_type'] = $type_count[$request['type']];
					}
					
					// Get New Total Score
					if( $request['where'] == 'mass_optimize' ) {
						$ret['new_total_score'] = $this->print_stats( array('id' => $request['product'], 'childs_btn' => $type_count['children']), array(), 90, true );
					}else{ 
						$ret['new_total_score'] = $this->print_stats( array('id' => $request['product'], 'childs_btn' => $type_count['children']) ); 
					}
					
					$ret = array_merge($ret, array(
						'status'    => 'valid'
					));
				}

			}

			if( $action == 'mass_optimize_products' ) {
				
				//$request['optimize_options'] = $_REQUEST['optimize_options'] != '' ? explode('&', $_REQUEST['optimize_options']) : null;
				$request['products'] = $_REQUEST['products'] != '' ? explode('&', $_REQUEST['products']) : null;
				$products2optimize = array();
				
				if( isset($request['products']) && is_array($request['products']) && count($request['products']) > 0 /*&&
					isset($request['optimize_options']) && is_array($request['optimize_options']) && count($request['optimize_options']) > 0*/ ) {
					$optimised = array();
					
					foreach( $request['products'] as $product ) {
						$_prod = explode('=', $product);
						$products2optimize[] = end( $_prod );
					}
					  
					$html[] = '<div class="' . ( WooZone()->alias ) . '-speed-optimise-top-section">';
					$html[] = 	'<span class="optimise-count"><span id="optimise-current-prod">0</span>/0</span>';
					$html[] = 	'<div class="optimise-progress-bar-wrapper"><span></span></div>';
					$html[] = 	'<a href="#" class="WooZone-btn-optimize-stop">' . ( __('STOP', 'woozone') ) . '</a>';
					$html[] = '</div>';
					
					$html[] = '<div class="optimize-current-product">' . ( $this->get_prod2mass_optimize( $products2optimize[0] ) ) . '</div>';
					
					$html[] = '<hr/>';
					
					if( isset($products2optimize[1]) ) {
						$html[] = '<h3 class="optimize-next-product-title">' . ( __('Next Product to be processed', 'woozone') ) . '</h3>';
						$html[] = '<div class="optimize-next-product-wrapper"><div class="optimize-next-product-bg"></div><div class="optimize-next-product">' . ( $this->get_prod2mass_optimize( $products2optimize[1] ) ) . '</div></div>';
					}
					
					$ret = array_merge($ret, array(
						'status' 	=> 'valid',
						//'next_prod' => isset($products2optimize[2]) ? $products2optimize[2] : 0,
						'html' 		=> implode("\n", $html)
					));
				}
			}

			if( $action == 'mass_optimize_next_prod' ) {
				
				$request['product'] = $_REQUEST['product'] != '' ? $_REQUEST['product'] : null;
				$next_prod = $this->get_prod2mass_optimize( $request['product'] );
				
				if( $next_prod ) {
					$ret = array_merge($ret, array(
						'status' => 'valid',
						'next_prod' => $next_prod
					));
				}
				
			}

			if( $action == 'mass_optimize' ) {
					
				$request['type'] = $_REQUEST['type'] != '' ? $_REQUEST['type'] : null;
				
				if( $request['type'] == 'attachments' ) {
					
					$request['option'] = $_REQUEST['option'] != '' ? $_REQUEST['option'] : null;
					$request['product'] = $_REQUEST['product'] != '' ? $_REQUEST['product'] : null;
					
					if( isset($request['option']) && isset($request['product']) ) {
						
						$attachments = get_attached_media( 'image', $request['product'] );
						  
						if( isset($attachments) && count($attachments) > 0 && (count($attachments) > (int)$request['option']) ) {
							$deleted_attachments = 0;
							$featured_img_id = get_post_thumbnail_id( $request['product'] );
							
							foreach( $attachments as $attach_id => $attachment ) {
								// skip featured
								if( $attach_id == $featured_img_id ) continue;
								
								// leave only $request['option'] images
								if( $request['option'] != 'only_featured' && $request['option'] > 0 ) {
									if( (count($attachments) - $deleted_attachments) == $request['option'] ) break;
								}
								
								if( $this->delete_image($request['product'], $attach_id) ) {
									$deleted_attachments++;
									
									if( $request['option'] == 'only_featured' ) {
										unset($attachments[$attach_id]);
									}
								}
							}
							  
							if( (count($attachments) - $deleted_attachments) == $request['option'] ) {
								$ret['status'] = 'valid';
							}else if( $request['option'] == 'only_featured' && count($attachments) == 1 && array_key_exists($featured_img_id, $attachments) ) {
								$ret['status'] = 'valid';
							}
							
							$ret['new_count'] = count($attachments);
							
						}else{
							$ret['status'] = 'valid';
						}
	  
					}
					 
				}

				if( $request['type'] == 'woo_attributes' ) {
					
					$request['option'] = $_REQUEST['option'] != '' ? $_REQUEST['option'] : null;
					$request['product'] = $_REQUEST['product'] != '' ? $_REQUEST['product'] : null;
					
					if( isset($request['option']) && isset($request['product']) ) {
						
						if( $request['option'] == 'yes' ) {
							$ret = $this->optimize_attributes( $request['product'] );
						}else{
							$ret['status'] = 'valid';
						}
						
					}
					
				}
				
				if( $request['type'] == 'children' ) {
					
					$request['option'] = $_REQUEST['option'] != '' ? $_REQUEST['option'] : null;
					$request['product'] = $_REQUEST['product'] != '' ? $_REQUEST['product'] : null;
					 
					if( isset($request['option']) && isset($request['product']) ) {
						$product_variations = $this->get_product_all_variations( $request['product'] );
						  
						if( isset($product_variations) && count($product_variations) > 0 && (count($product_variations) > (int)$request['option']) ) {
							 
							$deleted_variations = 0;
							
							foreach( $product_variations as $key => $variation ) {
								
								// leave only $request['option'] variations
								if( (count($product_variations) - $deleted_variations) == $request['option'] ) break;
								
								$variation_img = get_post_thumbnail_id( $variation['ID'] );
						  
								if( $variation_img > 0 ) {
									// check if other products/variations has image/gallery set with this attachment id
									$check_img_set = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(meta_id) FROM ".$wpdb->prefix."postmeta WHERE post_id!=%d AND (meta_key='_thumbnail_id' OR meta_key='_product_image_gallery') AND meta_value=%d", $variation['ID'], $variation_img ) );
									
									if( $check_img_set == 0 ) {
										wp_delete_attachment( $variation_img, true );
									}
								}
								
								if( wp_delete_post( $variation['ID'], true ) ) {
									$deleted_variations++;
									//unset($product_variations[$key]);
								}
							}
							
							if( (count($product_variations) - $deleted_variations) == $request['option'] ) {
								$ret['status'] = 'valid';
							}
							
							$ret['new_count'] = count($product_variations) - $deleted_variations;
							
							// If no variations left, set product type to "Simple"
							if( $ret['new_count'] == 0 ) {
								// get simple product type id
								$simple_type_id = $wpdb->query("SELECT term_id from " . $wpdb->prefix . "terms where name='simple'");
								wp_set_object_terms( $request['product'], $simple_type_id, 'product_type' );
							}
							
						}else{
							$ret['status'] = 'valid';
						}
						
					}
					
				}

				if( $request['type'] == 'categories' ) {
					
					$request['option'] = $_REQUEST['option'] != '' ? $_REQUEST['option'] : null;
					$request['product'] = $_REQUEST['product'] != '' ? $_REQUEST['product'] : null;
					
					if( isset($request['option']) && isset($request['product']) ) {
						
						$product_categories = wp_get_post_terms( $request['product'], 'product_cat' );
						
						if( isset($product_categories) && count($product_categories) > 0 && (count($product_categories) > (int)$request['option']) ) {
							 
							$deleted_categories = 0;
							
							foreach( $product_categories as $key => $categ ) {
								
								// leave only $request['option'] variations
								if( (count($product_categories) - $deleted_categories) == $request['option'] ) break;
								
								if( wp_remove_object_terms( $request['product'], $categ->term_id, 'product_cat' ) ) {
									$deleted_categories++;
									//unset($product_variations[$key]);
								}
							}
							
							$new_count = count($product_categories) - $deleted_categories;
							if( $new_count == $request['option'] ) {
								$ret['status'] = 'valid';
								$ret['new_count'] = $new_count;
							}
							
						}else{
							$ret['status'] = 'valid';
							$ret['new_count'] = count($product_categories);
						}
						
					}
					
				}
			}

			if( $action == 'get_score' ) {
				
				$request['product'] = isset($_REQUEST['product']) && (int) $_REQUEST['product'] > 0 ? (int) $_REQUEST['product'] : 0;
				$request['type'] = isset($_REQUEST['type']) && $_REQUEST['type'] != '' ? $_REQUEST['type'] : '';
				$request['new_count'] = isset($_REQUEST['new_count']) && (int) $_REQUEST['new_count'] > 0 ? (int) $_REQUEST['new_count'] : 0;
				
				if( $request['product'] > 0 && $request['type'] != '' ) {
					
					$ret['new_score'] =  $this->print_stats( array('id' => $request['product']), array($request['type'] => $request['new_count']) );
					$ret['status'] = 'valid';
					
				}
				
			}
			
			die(json_encode($ret));
		}

		public function optimize_attributes( $product_id )
		{
			$ret = array(
				'status' => 'valid',
				'new_count' => 0,
			);
			
			$product_attributes = $this->get_woo_attributes_by_post_id( $product_id );
				
			if( isset($product_attributes) && count($product_attributes) > 0 ) {
				$cached_terms = array();
				$count_cached_terms = 0;
				
				foreach( $product_attributes as $taxonomy => $attr ) {
					
					if( count($attr['terms']) > 0 ) {
						$_tax_name = get_taxonomy($taxonomy);
						$taxonomy_name = $_tax_name->label;
						
						foreach( $attr['terms'] as $term ) {
							$cached_terms[$taxonomy][] = array('taxonomy_name' => $taxonomy_name, 'name' => $term->name, 'slug' => $term->slug);
							
							if( !in_array( $taxonomy, $this->skip_taxonomies ) ) {
								// check if any other product variations has this term set; if not, delete it
								$args = array(
									'post_type' => 'product_variation',
									'posts_per_page' => -1,
									'meta_query' => array(
										array(
											'key' => 'attribute_' . $taxonomy,
											'value' => $term->slug,
											'compare' => '='
										)
									),
									'post_parent__in' => array($product_id),
								);
								$related_items = new WP_Query( $args );
								wp_reset_postdata();
								 
								if( $related_items->found_posts == 0 ) {
									// remove current product term
									wp_remove_object_terms( $product_id, $term->term_id, $taxonomy );
								}
								
								
								// check if any other products has this term set; if not, delete it
								$args = array(
									'post_type' => 'product',
									'posts_per_page' => -1,
									'tax_query' => array(
										array(
											'taxonomy' => $taxonomy,
											'field' => 'term_id',
											'terms' => $term->term_id
										)
									),
								);
								$related_products = new WP_Query( $args );
								wp_reset_postdata();
								
								// check if any other products has this term set; if not, delete it
								$args = array(
									'post_type' => 'product_variation',
									'posts_per_page' => -1,
									'meta_query' => array(
										array(
											'key' => 'attribute_' . $taxonomy,
											'value' => $term->slug,
											'compare' => '='
										)
									),
								);
								$related_products_variations = new WP_Query( $args );
								wp_reset_postdata();
								 
								if( $related_products->found_posts == 0 && $related_products_variations->found_posts == 0 ) {
									// delete term
									wp_delete_term($term->term_id, $taxonomy);
								}
							}

							$count_cached_terms++;
						}
						
					}
				}
			}

			if( isset($cached_terms) && is_array($cached_terms) && count($cached_terms) > 0 ) {
				$cache_terms = update_post_meta($product_id, '_cached_product_terms', $cached_terms);
				
				if( $cache_terms > 0 ) {
					$product_attributes = $this->get_woo_attributes_by_post_id( $product_id );
					$_new_product_attributes = array();
					 
					if( isset($product_attributes) && count($product_attributes) > 0 ) {
						$_product_attributes = get_post_meta( $product_id, '_product_attributes', true);
						foreach( $product_attributes as $taxonomy => $attr ) {
							 if( $attr['length'] > 0 ) {
								if( isset($_product_attributes[$taxonomy]) ) {
									$_new_product_attributes[$taxonomy] = $_product_attributes[$taxonomy];
								}
							 }
						}  
						update_post_meta( $product_id, '_product_attributes', $_new_product_attributes);
					}else{
						update_post_meta( $product_id, '_product_attributes', array());
					}
					
					$ret = array_merge($ret, array(
						'status'    => 'valid',
						'msg'       => __('Attributes optimised.', 'woozone'),
						'optimised_attributes' => $count_cached_terms,
						'new_count' => count($_new_product_attributes) > 0 ? count($_new_product_attributes) : 0,
					));
				}
			}
			 
			return $ret;
		}
		
		public function delete_image( $product_id, $attach_id )
		{
			if( $product_id > 0 && $attach_id > 0 ) {
					
				// remove the attachment of a product
				$delete_attach = wp_delete_post( $attach_id, true );
				
				// if exists, remove from gallery also
				$product_gallery = get_post_meta( $product_id, '_product_image_gallery', true );
				if( $product_gallery ){
					$product_gallery = explode( ",", $product_gallery );
					if( count($product_gallery) > 0 ){
						$_product_gallery = array();
						foreach ($product_gallery as $att_id) {
							if( $att_id != $attach_id ){
								$_product_gallery[] = $att_id;
							}
						}
						update_post_meta( $product_id, '_product_image_gallery', implode( ",", $_product_gallery ) );
					}
				}
				
			}
			
			return $delete_attach ? true : false;
		}

		public function get_prod2mass_optimize( $product_id = 0 )
		{
			$html = array();
			$optimize_options = array();
			
			if( $product_id > 0 ) {
				$optimize_options = array(
					'attachments' => __('IMAGES', 'woozone'),
					'woo_attributes' => __('ATTRIBUTES', 'woozone'),
					'children' => __('VARIATIONS', 'woozone'),
					'categories' => __('CATEGORIES', 'woozone'),
				);
				
				$get_variations = $this->show_product_variations( $product_id, true );
				$attachments = get_attached_media( 'image', $product_id );
				$_get_categories = $this->get_prod_categories( $product_id );
				
				$type_count = array(
					'attachments' => count($attachments),
					'woo_attributes' => $this->count_woo_terms_by_post_id( $product_id, true ),
					'children' => $get_variations['count'],
					'categories' => $_get_categories['count']
				);
				
				$html[] = '<ul class="' . ( WooZone()->alias ) . '-optimize-product" data-product="' . ( $product_id ) . '">';
				$html[] = 	'<li>' . ( wp_get_attachment_image( get_post_thumbnail_id($product_id), array(75, 75) ) ) . '<span class="prod-asin">ASIN: ' . ( get_post_meta( $product_id, '_amzASIN', true) ) . '</span></li>';
				 
				foreach( $optimize_options as $alias => $alias_name ) {
					$html[] = '<li id="optimize-' . ( $alias ) . '">';
					$html[] = 	'<span class="type-name">' . ( $alias_name ) . '</span>';
					$html[] = 	'<span class="type-note">' . ( $this->print_stats( array('id' => $product_id, 'childs_btn' => $get_variations['count']), array($alias => $type_count[$alias]) ) ) . '</span>';
					$html[] = 	'<img class="done-icon" src="' . ( $this->module_folder ) . '/images/icon-done.png" width="25" height="20" alt="' . ( __('done', 'woozone') ) . '"/>';
					$html[] = 	'<img class="loading-icon" src="' . ( $this->module_folder ) . '/images/icon-loading.gif" height="40" alt="' . ( __('Loading..', 'woozone') ) . '"/>';
					$html[] = '</li>';
				}
				
				$html[] = 	'<li class="total-score-sep"><i class="fa fa-arrow-right" aria-hidden="true"></i></li>';
				$html[] = 	'<li id="' . WooZone()->alias . '-total-score">';
				$html[] = 		$this->print_stats( array('id' => $product_id, 'childs_btn' => $get_variations['count']), array(), 90, true );
				$html[] = 	'</li>';
				$html[] = '</ul>';
				
				return $this->the_plugin->_parse_page_fix_amazon( implode("\n", $html) );
			}
			
			return false;
		}

		public function get_woo_attributes_by_post_id( $post_id=0, $without_variations = false )
		{
			if( $without_variations === true ) {
				$_product_attributes = get_post_meta( $post_id, '_product_attributes', true);
				$variation_attrs = array();
				  
				if( isset($_product_attributes) && is_array($_product_attributes) && count($_product_attributes) > 0 ) {
					foreach( $_product_attributes as $key => $attr ) {
						if( $attr['is_variation'] == 1 ) {
							$variation_attrs[] = $key;
						}
					}
				}
			}
			 
			$taxonomies = get_post_taxonomies( $post_id );
			$woo_attributes = array();
			if( isset($taxonomies) && is_array($taxonomies) && count($taxonomies) > 0 ){
				foreach( $taxonomies as $string ) {
					if( strpos( $string, "pa_" ) !== false ) {
						if( $without_variations === true && in_array($string, $variation_attrs) ) continue;
						
						$woo_attributes[] = $string;
					}
				}
			}
			  
			$__woo_attributes = array();
			if( isset($woo_attributes) && is_array($woo_attributes) && count($woo_attributes) > 0 ){
				foreach ( $woo_attributes as $att ) {
					$terms = wp_get_post_terms( $post_id, $att );
					
					if( count($terms) > 0 ) {
						$__woo_attributes[$att] = array(
							'length' => count($terms),
							'terms' => $terms
						);
					}
				}
			}
			 
			return $__woo_attributes;
		}

		private function count_woo_terms_by_post_id( $post_id=0, $without_variations = false )
		{
			$woo_attributes = $this->get_woo_attributes_by_post_id( $post_id, $without_variations );
			$totals = 0;
			if( isset($woo_attributes) && is_array($woo_attributes) && count($woo_attributes) > 0 ){
				foreach ($woo_attributes as $key => $value) {

					if( isset($value['length']) && (int)$value['length'] > 0 ){
						$totals = $totals + $value['length'];
					}
				}
			}

			return $totals;
		}
		
		public function count_cached_terms( $product_id=0 ) {
			$cached_terms = get_post_meta( $product_id, '_cached_product_terms', true);
			$terms_count = 0;
			
			if( $cached_terms && is_array($cached_terms) && count($cached_terms) > 0 ) {
				foreach( $cached_terms as $terms ) {
					if( is_array($terms) && count($terms) > 0 ) {
						foreach( $terms as $term ) {
							$terms_count++;
						}
					}
				}
			}
			
			return $terms_count;
		}

		public function build_score( $stats=array(), $only_one = false, $product_id = 0 )
		{
			$score = array();
			 
			/*
				children = 
				0           ==> 1
				1           ==> 2
				>= 2 < 5     ==> 3
				>= 5         ==> 4
			*/
			if( isset($stats['children']) ){
				if( $stats['children'] == 0 ){
					$score['children'] = 1;
				}

				if( $stats['children'] == 1  ){
					$score['children'] = 2;
				}

				if( $stats['children'] >= 2 && $stats['children'] < 5  ){
					$score['children'] = 3;
				}

				if( $stats['children'] >= 5  ){
					$score['children'] = 4;
				}
			}

			/*
				attachments = 
					<= 1        ==> 1
					> 1 <= 5     ==> 2
					> 5 < 10    ==> 3
					>= 10        ==> 4
			*/
			
			if( isset($stats['attachments']) ){
				if( $stats['attachments'] <= 1 ){
					$score['attachments'] = 1;
				}

				if( $stats['attachments'] > 1 && $stats['attachments'] <= 5 ){
					$score['attachments'] = 2;
				}

				if( $stats['attachments'] > 5 && $stats['attachments'] < 10  ){
					$score['attachments'] = 3;
				}

				if( $stats['attachments'] >= 10 ){
					$score['attachments'] = 4;
				}
			}
			 
			/* 
				woo_attributes = 

					if attrs == static 
						==> 1

					else
						<= 3         ==> 1
						> 3 <= 6     ==> 2
						> 6 < 10    ==> 3
						>= 10        ==> 4
			*/
			if( isset($stats['woo_attributes']) ){  
				
				if( $product_id > 0 && get_post_meta($product_id, '_cached_product_terms', true) ) {
					
					$score['woo_attributes'] = 1;
					
				}else{
				
					if( $stats['woo_attributes'] <= 3 ){
						$score['woo_attributes'] = 1;
					}
	
					if( $stats['woo_attributes'] > 3 && $stats['woo_attributes'] <= 6 ){
						$score['woo_attributes'] = 2;
					}
	
					if( $stats['woo_attributes'] > 6 && $stats['woo_attributes'] < 10  ){
						$score['woo_attributes'] = 3;
					}
	
					if( $stats['woo_attributes'] >= 10 ){
						$score['woo_attributes'] = 4;
					}
					
				}

				// if attrs == static, need to implement this
			}

			/*
				categories
					<= 1        ==> 1
					> 1 <= 2    ==> 2
					> 2 <= 6    ==> 3
					> 6         ==> 4
			*/
			if( isset($stats['categories']) ){
				if( $stats['categories'] <= 1 ){
					$score['categories'] = 1;
				}

				if( $stats['categories'] > 1 && $stats['categories'] <= 2 ){
					$score['categories'] = 2;
				}

				if( $stats['categories'] > 2 && $stats['categories'] <= 6  ){
					$score['categories'] = 3;
				}

				if( $stats['categories'] > 6 ){
					$score['categories'] = 4;
				}
			}
			  
			if( $only_one == true )
			{
				return array( 'score' => end($score) );
			}
			
			/*
				not all the rules have the same importance, so we need to create a multiplication algorithm

				::: from 100% :::
					children: 30%
					attachments: 10%
					woo_attributes: 40%
					categories: 20%
			*/
			$multiplication = array(
				'children' => 50,
				'attachments' => 5,
				'woo_attributes' => 35,
				'categories' => 20
			);

			if( count($score) > 0 ){
				foreach ( $score as $score_key => $score_value ) {
					if( isset($multiplication[$score_key]) ){
						$score[$score_key] = $score[$score_key] * $multiplication[$score_key];
					}
				}
			}
			
			if( count($score) > 0 ){
				$totals = 0;
				foreach ( $score as $the_score ) {
					$totals += $the_score;
				}
			}
			  
			$totals_percent = $totals / 4;
			$totals_score = $totals_percent / 100 * 4;
  
			return array(
				'percent'   => $totals_percent,
				'score'     => $totals_score
			);
		}

		public function print_stats( $row=array(), $stats=array(), $elm_height = 60, $score_hint=false )
		{
			$only_one = true;
			
			if( count($stats) == 0 ) {
				$only_one = false;
				$attachments = get_attached_media( 'image', $row['id'] );
				$count_woo_terms = (int) $this->count_woo_terms_by_post_id( $row['id'], true );
				$categories = wp_get_post_terms( $row['id'], 'product_cat' );
				$stats = array(
					'children'          => intval(preg_replace('/[^0-9]+/', '', $row['childs_btn']), 10),
					'attachments'       => count($attachments),
					'woo_attributes'    => $count_woo_terms,
					'categories'        => count($categories)
				);
			}
			
			$score = $this->build_score( $stats, $only_one, $row['id'] );
			
			$score_hint_msg = array(
				1 => __('Optimized!', 'woozone'),
				2 => __('Needs work', 'woozone'),
				3 => __('Poor!', 'woozone'),
				4 => __('Bad', 'woozone'),
			);
			  
			$_the_score_int = round( $score['score'] ) - 1;
			$_top_pos = $_the_score_int * $elm_height;
			
			$html = array();
			$html[] = '<div class="' . ( WooZone()->alias ) . '-the-score-wrapper">';
			
			if( count($stats) > 1 ) {
				$html[] =   '<div class="' . ( WooZone()->alias ) . '-the-score-details">';
				$html[] =       '<label><span>' . ( __('Variations', 'woozone') ) . ':</span> ' . ( $stats['children'] ) . '</label>';
				$html[] =       '<label><span>' . ( __('Images', 'woozone') ) . ':</span> ' . ( $stats['attachments'] ) . '</label>';
				$html[] =       '<label><span>' . ( __('Attributes', 'woozone') ) . ':</span> ' . ( $stats['woo_attributes'] ) . '</label>';
				$html[] =       '<label><span>' . ( __('Categories', 'woozone') ) . ':</span> ' . ( $stats['categories'] ) . '</label>';
				$html[] =   '</div>';
			}
			  
			$html[] =   '<div class="' . ( WooZone()->alias ) . '-the-score">';
			$html[] =       '<div class="' . ( WooZone()->alias ) . '-all-scores-container">';
			$html[] =           '<span class="' . ( WooZone()->alias ) . '-all-cursor" ' . (isset($score['percent']) ? 'style="top: ' . ( $score['percent'] ) . '%"' : '') . '></span>';
			$html[] =           '<ul class="' . ( WooZone()->alias ) . '-all-scores" style="top: -' . ( $_top_pos ) . 'px">';
			$html[] =               '<li>A</li>';
			$html[] =               '<li>B</li>';
			$html[] =               '<li>C</li>';
			$html[] =            	'<li>D</li>';
			$html[] =           '</ul>';
			$html[] =       '</div>';
			$html[] =   '</div>';
			$html[] = 	$score_hint === true ? '<span class="score-hint hint-note-' . ( round( $score['score'] ) ) . '">' . ( $score_hint_msg[round( $score['score'] )] ) . '</span>' : '';
			$html[] = '</div>';
			
			return implode( "\n", $html );
		}

		public function print_actions( $row=array() )
		{
			$html = array();
			$html[] = '<a href="#" class="' . ( WooZone()->alias ) . '-optimize-product" data-product="' . ( $row['id'] ) . '">Optimize Product</a>';

			return implode( "\n", $html );
		}
		
		public function get_price_html( $product_id ) {
			$sale_price = get_post_meta( $product_id, '_sale_price', true);
			$regular_price = get_post_meta( $product_id, '_regular_price', true);
			
			if ( $regular_price === '' ) {
				$price = apply_filters( 'woocommerce_empty_price_html', '', $this );
			} elseif ( $sale_price > 0 ) {
				$price = wc_format_sale_price( $regular_price, $sale_price );
			} else {
				$price = wc_price( $regular_price );
			}
			
			return apply_filters( 'woocommerce_get_price_html', $price, $this );
		}
		
		public function get_product_all_variations($prod_id) 
		{
			$variations = array();
			$prod_available_variation_atts = array();
			
			// verify we are in woocommerce product
			if ( is_object($prod_id) ) {
				$product = $prod_id;
			} else {
				$product = new WC_Product_Variable( $prod_id ); // WC_Product
			}
			  
			if ( is_object($product) ) {  
				
				$args = array(
					'post_type'     => 'product_variation',
					'post_status'   => array( 'private', 'publish', 'draft' ),
					'numberposts'   => -1,
					'post_parent'   => $prod_id // get parent post-ID
				);
				$_variations = get_posts( $args );
				
				if( count($_variations) > 0 ) {
					
					// get variation available attributes
					$prod_available_variation_atts = get_post_meta($prod_id, '_product_attributes', true);
					if( isset($prod_available_variation_atts) && is_array($prod_available_variation_atts) && count($prod_available_variation_atts) > 0 ) {
						foreach( $prod_available_variation_atts as $atts ) {
							if( $atts['is_variation'] ) {
								$variation_available_atts[] = $atts['name'];
							}
						}
					}
				
					if( isset($_variations) && is_array($_variations) && count($_variations) > 0 ) {
						foreach ($_variations as $variation) {
							$variation = (array) $variation;
							
							if( isset($variation_available_atts) && count($variation_available_atts) > 0 ) {
								foreach( $variation_available_atts as $attr ) {
									$variation['attributes'][$attr] = get_post_meta($variation['ID'], 'attribute_' . $attr, true);
								}
							}
							
							$variations[$variation['ID']] = array(
								'ID' => $variation['ID'],
								'image_id' => get_post_thumbnail_id( $variation['ID'] ),
								'attributes' => isset($variation['attributes']) && count($variation['attributes']) > 0 ? $variation['attributes'] : array(),
								'price' => $this->get_price_html($variation['ID'])
							);
						}
					}
				}
				
				return $variations;
			}
			return array();
		}
		
		public function show_product_variations( $product_id, $only_count = false ) 
		{
			$ret = array(
				'count' => 0,
				'html' => '<h3 style="text-align:center;">' . __('This product has no variations.', 'woozone') . '</h3>'
			);
			
			$html = array();
			$variations = array();
			$variation_available_atts = array();
			$variation_atts = array();
			$product_variations = $this->get_product_all_variations( $product_id );
			  
			if( $only_count ) {
				return array_merge($ret, array(
					'count' => count($product_variations),
				));
			}
			
			if( is_array($product_variations) && count($product_variations) > 0 ) {
				$html[] = '<form name="product_variations" method="post">';
				
					$html[] = '<div class="variations_table_head">';
					$html[] = 	'<div class="table-head" style="width:5%;"><input class="select-unselect_all" type="checkbox" /></div>';
					$html[] = 	'<div class="table-head" style="width:83%;">' . __('Variation', 'woozone') . '</div>';
					$html[] = 	'<div class="table-head" style="width:11%;">' . __('Action', 'woozone') . '</div>';
					$html[] = '</div>';
					
					$html[] = '<div class="table-wrapper">';
						$html[] = '<table cellspacing="0" cellpadding="0" width="100%">';
						
						foreach( $product_variations as $variation_id => $variation ) {
							  
							// Build product title
							$html_attrs = array();
							
							if( isset($variation['attributes']) && count($variation['attributes']) > 0 ) {
								foreach( $variation['attributes'] as $attr_key => $attr_name ) {
									$html_attrs[] = ucwords(str_replace('pa_', '', $attr_key)) . ' (' . $attr_name . ')';
								}
							}
							
							$html[] = '<tr id="variation-' . ( $variation_id ) . '">';
							$html[] = 	'<td width="5%">';
							$html[] = 		'<input type="checkbox" name="variation_id" value="' . ( $variation_id ) . '"/>';
							//$html[] = 		'<input type="hidden" name="variation_img" value="' . ( $variation['image_id'] ) . '"/>';
							$html[] = 	'</td>';
							$html[] = 	'<td width="83%" class="title">';
							$html[] = 		wp_get_attachment_image( $variation['image_id'], array(75,75) );
							$html[] = 		'<strong>#' . ( $variation_id ) . '</strong> ';
							$html[] = 		count($html_attrs) > 0 ? ': '. implode(', ', $html_attrs) : '';
											if( $variation['price'] != '' ) {
												$html[] = ' &mdash; <i>'. ( __('Price', 'woozone') ) . ': ' . $variation['price'] . '</i>';	
											}
							$html[] = 	'</td>';
							$html[] = 	'<td width="12%" align="center"><a href="#" class="' . ( WooZone()->alias ) . '-btn-delete ' . ( WooZone()->alias ) . '-delete-variation" data-variation="' . ( $variation_id ) . '" data-variation-img="' . ( $variation['image_id'] ) . '" data-product="' . ( $product_id ) . '">' . __('Delete', 'woozone') . '</a></td>';
							$html[] = '</tr>';
						}
						
						$html[] = '</table>';
					$html[] = '</div>';
				
					$html[] = '<p><a href="#" class="' . ( WooZone()->alias ) . '-btn-delete ' . ( WooZone()->alias ) . '-delete-variations" data-product="' . ( $product_id ) . '">' . ( __('Delete selected', 'woozone') ) . '</a></p>';
				
				$html[] = '</form>';
								
				$ret = array_merge($ret, array(
					'count' => count($product_variations), 
					'html' => $this->the_plugin->_parse_page_fix_amazon( implode( "\n", $html ) )
				));
			}

			return $ret;
		}

		public function get_prod_categories( $product_id, $only_count = false ) 
		{
			$ret = array(
				'count' => 0,
				'html' => '<h3 style="text-align:center;"> ' . ( __('This product has no categories.', 'woozone') ) . ' </h3>',
			);
			
			$html = array();
			$categories = array();
			$post_terms = wp_get_post_terms( $product_id, 'product_cat' );
			
			
			if( $post_terms && count($post_terms) > 0 ) {
				if( $only_count ) {
					$ret = array_merge($ret, array(
						'count' => count($post_terms),
					));
					
					return $ret;
				}
					 
				$html[] = '<form name="product_categories" method="post">';
					$html[] = '<input type="hidden" name="product_id" value="' . ( $product_id ) . '"/>';
					
					
					
					$html[] = '<div class="variations_table_head">';
					$html[] = 	'<div class="table-head" style="width:5%;"><input class="select-unselect_all" type="checkbox" /></div>';
					$html[] = 	'<div class="table-head" style="width:83%;">' . __('Category', 'woozone') . '</div>';
					$html[] = 	'<div class="table-head" style="width:11%;">' . __('Action', 'woozone') . '</div>';
					$html[] = '</div>';
					
					$html[] = '<div class="table-wrapper">';
						$html[] = '<table cellspacing="0" cellpadding="0" width="100%">';
						
						
						foreach( $post_terms as $term ) {
							$html[] = '<tr id="term-' . ( $term->term_id ) . '">';
							$html[] = 	'<td width="5%"><input type="checkbox" name="term_id" value="' . ( $term->term_id ) . '"/></td>';
							$html[] = 	'<td width="83%" class="title">' . ( $term->name ) . '</td>';
							$html[] = 	'<td width="12%" align="center"><a href="#" class="' . ( WooZone()->alias ) . '-btn-delete ' . ( WooZone()->alias ) . '-btn-remove-from-categ" data-product="' . ( $product_id ) . '" data-categ="' . ( $term->term_id ) . '">' . __('Remove', 'woozone') . '</a></td>';
							$html[] = '</tr>';
						}
						
						$html[] = '</table>';
					$html[] = '</div>';
				
					$html[] = '<p><a href="#" class="' . ( WooZone()->alias ) . '-btn-delete ' . ( WooZone()->alias ) . '-remove-categs" data-product="' . ( $product_id ) . '">' . ( __('Remove selected', 'woozone') ) . '</a></p>';
				
				$html[] = '</form>';
			
				$ret = array_merge($ret, array(
					'count' => count($post_terms), 
					'html' => implode( "\n", $html )
				));
			}
			
			return $ret;
		}
	}
}

// Initialize the WooZoneSpeedOptimizator class
$WooZoneSpeedOptimizator = WooZoneSpeedOptimizator::getInstance();
