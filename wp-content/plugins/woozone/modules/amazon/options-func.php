<?php

/*
http://docs.aws.amazon.com/AWSECommerceService/latest/DG/CHAP_response_elements.html 
$('div.informaltable > table tr').each(function(i, el) {
	var $this = $(el), $td = $this.find('td:first'),
	$a = $td.find('a'), text = $a.attr('name');

	if ( typeof text == 'undefined' || text == '' ){
		text = $td.find('.code').text();
	}
	if ( typeof text != 'undefined' && text != '' ) {
		var text2 = text; //text.match(/([A-Z]?[^A-Z]*)/g).slice(0,-1).join(' ');
		console.log( '\''+text+'\' => \''+text+'\',' );
	}
});
*/

function WooZone_attributesList() {
	require_once( 'lists.inc.php' );
	return $attrList;
}

function WooZone_imageSizes() {
	global $WooZone;
	
	$ret = array();
	$list = $WooZone->u->get_image_sizes();
	foreach ($list as $k => $v) {
		$ret["$k"] = $k . ' ' . sprintf( '(%s X %s)', $v['width'], $v['height'] );
	}
	return $ret;
}

function WooZoneAffIDsHTML( $istab = '' ) {
	global $WooZone;
	
	$html         = array();
	$img_base_url = $WooZone->cfg['paths']["plugin_dir_url"] . 'modules/amazon/images/flags/';
	
	$config = $WooZone->settings();
 
	$theHelper = $WooZone->get_ws_object_new( $WooZone->cur_provider, 'new_helper', array(
		'the_plugin' => $WooZone,
	));
	//:: disabled on 2018-feb
	//require_once( $WooZone->cfg['paths']['plugin_dir_path'] . 'aa-framework/amz.helper.class.php' );
	//if ( class_exists('WooZoneAmazonHelper') ) {
	//	//$theHelper = WooZoneAmazonHelper::getInstance( $aiowaff );
	//	$theHelper = new WooZoneAmazonHelper( $WooZone );
	//}
	//:: end disabled on 2018-feb
	$what = 'main_aff_id';
	$list = is_object($theHelper) ? $theHelper->get_countries( $what ) : array();
	
	ob_start();
?>
	<style type="text/css">
		.WooZone-form .WooZone-form-row .WooZone-form-item.large .WooZone-div2table {
			display: table;
			width: 420px;
		}
			.WooZone-form .WooZone-form-row .WooZone-form-item.large .WooZone-div2table .WooZone-div2table-tr {
				display: table-row;
			}
				.WooZone-form .WooZone-form-row .WooZone-form-item.large .WooZone-div2table .WooZone-div2table-tr > div {
					display: table-cell;
					padding: 5px;
				}
	</style>
	<div class="panel-body <?php echo WooZone()->alias;?>-panel-body <?php echo WooZone()->alias;?>-form-row <?php echo ($istab!='' ? ' '.$istab : ''); ?>">
	<label class="<?php echo  WooZone()->alias;?>-form-label">Your Affiliate IDs</label>
	<div class="<?php echo  WooZone()->alias;?>-form-item large">
	<span class="formNote">Your Affiliate ID probably ends in -20, -21 or -22. You get this ID by signing up for Amazon Associates.</span>
	<div class="<?php echo  WooZone()->alias;?>-aff-ids <?php echo  WooZone()->alias;?>-div2table">
		<?php
		foreach ($list as $globalid => $country_name) {
			$flag = 'com' == $globalid ? 'us' : $globalid;
			$flag = strtoupper($flag);
		?>
		<div class="<?php echo  WooZone()->alias;?>-div2table-tr">
			<div>
				<img src="<?php echo $img_base_url . $flag; ?>-flag.gif" height="20">
			</div>
			<div>
				<input type="text" value="<?php echo isset($config['AffiliateID']["$globalid"]) ? $config['AffiliateID']["$globalid"] : ''; ?>" name="AffiliateID[<?php echo $globalid; ?>]" id="AffiliateID[<?php echo $globalid; ?>]" placeholder="ENTER YOUR AFFILIATE ID FOR <?php echo $flag; ?>">
			</div>
			<div class="WooZone-country-name">
				<?php echo $country_name; ?>
			</div>
		</div>
		<?php
		}
		?>
	</div>
<?php
	$html[] = ob_get_clean();

	$html[] = '<h3>Some hints and information:</h3>';
	$html[] = '- The link will use IP-based Geolocation to geographically target your visitor to the Amazon store of his/her country (according to their current location). <br />';
	$html[] = '- You don\'t have to specify all affiliate IDs if you are not registered to all programs. <br />';
	$html[] = '- The ASIN is unfortunately not always globally unique. That\'s why you sometimes need to specify several ASINs for different shops. <br />';
	$html[] = '- If you have an English website, it makes most sense to sign up for the US, UK and Canadian programs. <br />';
	$html[] = '</div>';
	$html[] = '</div>';
	
	return implode("\n", $html);
}

function WooZone_attributes_clean_duplicate( $istab = '' ) {
	global $WooZone;
   
	$html = array();
	
	$html[] = '<div class="WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row attr-clean-duplicate' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = '<label for="clean_duplicate_attributes" class="WooZone-form-label">' . __('Clean duplicate attributes:', $WooZone->localizationName) . '</label>';

	$options = $WooZone->settings();
	$val = '';
	if ( isset($options['clean_duplicate_attributes']) ) {
		$val = $options['clean_duplicate_attributes'];
	}
		
	ob_start();
?>
	<div class="WooZone-form-item">
		<select id="clean_duplicate_attributes" name="clean_duplicate_attributes" style="width:120px; margin-left: 18px;">
			<?php
			foreach (array('yes' => 'YES', 'no' => 'NO') as $kk => $vv){
				echo '<option value="' . ( $vv ) . '" ' . ( $val == $vv ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<input type="button" class="' . ( WooZone()->alias ) . '-form-button-small ' . ( WooZone()->alias ) . '-form-button-primary" id="WooZone-attributescleanduplicate" value="' . ( __('clean Now ', $WooZone->localizationName) ) . '">
	<div style="width: 100%; display: none; margin-top: 10px; " class="WooZone-response-options  WooZone-callout WooZone-callout-info"></div>';

	$html[] = '</div>';

	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {
		$("body").on("click", "#WooZone-attributescleanduplicate", function(){

			$.post(ajaxurl, {
				'action' 		: 'WooZone_AttributesCleanDuplicate',
				'sub_action'	: 'attr_clean_duplicate'
			}, function(response) {

				var $box = $('.attr-clean-duplicate'), 
					$res = $box.find('.WooZone-response-options');
				$res.html( response.msg_html ).show();
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

function WooZone_category_slug_clean_duplicate( $istab = '' ) {
	global $WooZone;
   
	$html = array();
	
	$html[] = '<div class="WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row category-slug-clean-duplicate' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = '<label class="WooZone-form-label" for="clean_duplicate_category_slug">' . __('Clean duplicate category slug:', $WooZone->localizationName) . '</label>';

	$options = $WooZone->settings();
	$val = '';
	if ( isset($options['clean_duplicate_category_slug']) ) {
		$val = $options['clean_duplicate_category_slug'];
	}
		
	ob_start();
?>
	<div class="WooZone-form-item">
		<select id="clean_duplicate_category_slug" name="clean_duplicate_category_slug" style="width:120px; margin-left: 18px;">
			<?php
			foreach (array('yes' => 'YES', 'no' => 'NO') as $kk => $vv){
				echo '<option value="' . ( $vv ) . '" ' . ( $val == $vv ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<input type="button" class="' . ( WooZone()->alias ) . '-form-button-small ' . ( WooZone()->alias ) . '-form-button-primary" id="WooZone-categoryslugcleanduplicate" value="' . ( __('clean Now ', $WooZone->localizationName) ) . '">
	<div style="width: 100%; display: none; margin-top: 10px; " class="WooZone-response-options  WooZone-callout WooZone-callout-info"></div>';

	$html[] = '</div>';

	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {
		$("body").on("click", "#WooZone-categoryslugcleanduplicate", function(){

			$.post(ajaxurl, {
				'action' 		: 'WooZone_CategorySlugCleanDuplicate',
				'sub_action'	: 'category_slug_clean_duplicate'
			}, function(response) {

				var $box = $('.category-slug-clean-duplicate'), $res = $box.find('.WooZone-response-options');
				$res.html( response.msg_html ).show();
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

function WooZone_clean_orphaned_amz_meta( $istab = '' ) {
	global $WooZone;
   
	$html = array();
	
	$html[] = '<div class="WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row clean_orphaned_amz_meta' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = '<label class="WooZone-form-label" for="clean_orphaned_amz_meta">' . __('Clean orphaned AMZ meta:', $WooZone->localizationName) . '</label>';

	$options = $WooZone->settings();
	$val = '';
	if ( isset($options['clean_orphaned_amz_meta']) ) {
		$val = $options['clean_orphaned_amz_meta']; 
	}
		
	ob_start();
?>
	<div class="WooZone-form-item">
		<select id="clean_orphaned_amz_meta" name="clean_orphaned_amz_meta" style="width:120px; margin-left: 18px;">
			<?php
			foreach (array('yes' => 'YES', 'no' => 'NO') as $kk => $vv){
				echo '<option value="' . ( $vv ) . '" ' . ( $val == $vv ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<input type="button" class="' . ( WooZone()->alias ) . '-form-button-small ' . ( WooZone()->alias ) . '-form-button-primary" id="WooZone-cleanduplicateamzmeta" value="' . ( __('clean Now ', $WooZone->localizationName) ) . '">
	<div style="width: 100%; display: none; margin-top: 10px; " class="WooZone-response-options  WooZone-callout WooZone-callout-info"></div>';

	$html[] = '</div>';
	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {

		$("body").on("click", "#WooZone-cleanduplicateamzmeta", function(){
			console.log( $('#AccessKeyID').val() ); 
			//var tokenAnswer = prompt('Please enter security token - The security token is your AccessKeyID');
			//if( tokenAnswer == $('#AccessKeyID').val() ) {
			if (1) {
				var confirm_response = confirm("CAUTION! PERFORMING THIS ACTION WILL DELETE ALL YOUR AMAZON PRODUCT METAS! THIS ACTION IS IRREVERSIBLE! Are you sure you want to clear all amazon product meta?");
				if( confirm_response == true ) {
					$.post(ajaxurl, {
						'action' 		: 'WooZone_clean_orphaned_amz_meta',
						'sub_action'	: 'clean_orphaned_amz_meta'
					}, function(response) {
						
						var $box = $('.clean_orphaned_amz_meta'), $res = $box.find('.WooZone-response-options');
						$res.html( response.msg_html ).show();
						if ( response.status == 'valid' )
							return true;
						return false;
					}, 'json');
				}
			}
			//else {
			//	alert('Security token invalid!');
			//}
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

function WooZone_delete_zeropriced_products( $istab = '' ) {
	global $WooZone;
   
	$html = array();
	
	$html[] = '<div class="WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row  delete_zeropriced_products' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = '<label class="WooZone-form-label" for="delete_zeropriced_products">' . __('Delete zero priced products:', $WooZone->localizationName) . '</label>';

	$options = $WooZone->settings();
	$val = '';
	if ( isset($options['delete_zeropriced_products']) ) {
		$val = $options['delete_zeropriced_products']; 
	}
		
	ob_start();
?>
	<div class="WooZone-form-item">
		<select id="delete_zeropriced_products" name="delete_zeropriced_products" style="width:120px; margin-left: 18px;">
			<?php
			foreach (array('yes' => 'YES', 'no' => 'NO') as $kk => $vv){
				echo '<option value="' . ( $vv ) . '" ' . ( $val == $vv ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<input type="button" class="' . ( WooZone()->alias ) . '-form-button-small ' . ( WooZone()->alias ) . '-form-button-primary" id="WooZone-delete_zeropriced_products" value="' . ( __('delete now! ', $WooZone->localizationName) ) . '">
	<span class="WooZone-form-note" style="display: inline-block; margin-left: 1.5rem;">This action is influenced by "Product : Delete | Move to Trash" option /Plugin SETUP tab</span>
	<div style="width: 100%; display: none; margin-top: 10px; " class="WooZone-response-options  WooZone-callout WooZone-callout-info"></div>';

	$html[] = '</div>';
	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {
		$("body").on("click", "#WooZone-delete_zeropriced_products", function(){
			var confirm_response = confirm("Are you sure you want to delete all zero priced products?");
			if( confirm_response == true ) {

				var loop_max = 10, // number of max steps (10 products will be made per step => total = 10 * 10 = 100 products)
					  loop_step = 0; // current step
				var $box = $('.delete_zeropriced_products'), $res = $box.find('.WooZone-response-options');

				function __doit() {
					loop_step++;
					if ( loop_step > loop_max ) {
						$res.append( 'WORK DONE. If there are posts remained, try again.' ).show();
						return true;
					}
					
					$res.append( 'WORK IN PROGRESS...' ).show();

					$.post(ajaxurl, {
						'action' 		: 'WooZone_delete_zeropriced_products',
						'sub_action'	: 'delete_zeropriced_products'
					}, function(response) {

						$res.html( response.msg_html ).show();

						var remained = parseInt( response.nb_remained );
						if ( remained ) {
							__doit();
						} else {
							$res.append( 'WORK DONE.' ).show();
						}

						//if ( response.status == 'valid' ) {
						//	return true;
						//}
						//return false;
					}, 'json');
				}
				__doit();

			} // end confirm
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

function WooZone_clean_orphaned_prod_assets( $istab = '' ) {
	global $WooZone;
   
	$html = array();
	
	$html[] = '<div class="WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row clean_orphaned_prod_assets' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = '<label class="WooZone-form-label" for="clean_orphaned_prod_assets">' . __('Clean orphaned WooZone Product Assets:', $WooZone->localizationName) . '</label>';

	$options = $WooZone->settings();
	$val = '';
	if ( isset($options['clean_orphaned_prod_assets']) ) {
		$val = $options['clean_orphaned_prod_assets']; 
	}
		
	ob_start();
?>
	<div class="WooZone-form-item">
		<select id="clean_orphaned_prod_assets" name="clean_orphaned_prod_assets" style="width:120px; margin-left: 18px;">
			<?php
			foreach (array('yes' => 'YES', 'no' => 'NO') as $kk => $vv){
				echo '<option value="' . ( $vv ) . '" ' . ( $val == $vv ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<input type="button" class="' . ( WooZone()->alias ) . '-form-button-small ' . ( WooZone()->alias ) . '-form-button-primary" id="WooZone-clean_orphaned_prod_assets" value="' . ( __('clean Now', $WooZone->localizationName) ) . '">
	<span class="WooZone-form-note" style="display: inline-block; margin-left: 1.5rem;">This option will clean orphan product assets from woozone tables: wp_amz_assets & wp_amz_products.</span>
	<div style="width: 100%; display: none; margin-top: 10px; " class="WooZone-response-options WooZone-callout WooZone-callout-info"></div>';

	$html[] = '</div>';
	//$html[] = '<span class="WooZone-form-note" style="/* margin-left: 20rem; */">This Affiliate id will be use in API request and if user are not from any of available amazon country.</span>';
	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {
		$("body").on("click", "#WooZone-clean_orphaned_prod_assets", function(){
			var confirm_response = confirm("Are you sure you want to delete all orphaned amazon products assets?");
			if( confirm_response == true ) {
				$.post(ajaxurl, {
					'action'        : 'WooZone_clean_orphaned_prod_assets',
					'sub_action'    : 'clean_orphaned_prod_assets'
				}, function(response) {
					var $box = $('.clean_orphaned_prod_assets'), $res = $box.find('.WooZone-response-options');
					$res.html( response.msg_html ).show();
					if ( response.status == 'valid' )
						return true;
					return false;
				}, 'json');
			}
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

function WooZone_clean_orphaned_prod_assets_wp( $istab = '' ) {
	global $WooZone;
   
	$html = array();
	
	$html[] = '<div class="WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row clean_orphaned_prod_assets_wp' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = '<label class="WooZone-form-label" for="clean_orphaned_prod_assets_wp">' . __('Clean orphaned Wordpress Product Attachments:', $WooZone->localizationName) . '</label>';

	$options = $WooZone->settings();
	$val = '';
	if ( isset($options['clean_orphaned_prod_assets_wp']) ) {
		$val = $options['clean_orphaned_prod_assets_wp']; 
	}
		
	ob_start();
?>
	<div class="WooZone-form-item">
		<select id="clean_orphaned_prod_assets_wp" name="clean_orphaned_prod_assets_wp" style="width:120px; margin-left: 18px;">
			<?php
			foreach (array('yes' => 'YES', 'no' => 'NO') as $kk => $vv){
				echo '<option value="' . ( $vv ) . '" ' . ( $val == $vv ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<input type="button" class="' . ( WooZone()->alias ) . '-form-button-small ' . ( WooZone()->alias ) . '-form-button-primary" id="WooZone-clean_orphaned_prod_assets_wp" value="' . ( __('clean Now', $WooZone->localizationName) ) . '">
	<span class="WooZone-form-note" style="display: inline-block; margin-left: 1.5rem; color: red;">This option will clean orphan product assets from wordpress tables: wp_posts & wp_postmeta.</span>
	<div style="width: 100%; display: none; margin-top: 10px; " class="WooZone-response-options WooZone-callout WooZone-callout-info"></div>';

	$html[] = '</div>';
	//$html[] = '<span class="WooZone-form-note" style="/* margin-left: 20rem; */">This Affiliate id will be use in API request and if user are not from any of available amazon country.</span>';
	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {
		$("body").on("click", "#WooZone-clean_orphaned_prod_assets_wp", function(){
			var confirm_response = confirm("Are you sure you want to delete all orphaned wordpress products attachments?");
			if( confirm_response == true ) {
				$.post(ajaxurl, {
					'action'        : 'WooZone_clean_orphaned_prod_assets_wp',
					'sub_action'    : 'clean_orphaned_prod_assets_wp'
				}, function(response) {
					var $box = $('.clean_orphaned_prod_assets_wp'), $res = $box.find('.WooZone-response-options');
					$res.html( response.msg_html ).show();
					if ( response.status == 'valid' )
						return true;
					return false;
				}, 'json');
			}
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

function WooZone_fix_product_attributes( $istab = '' ) {
	global $WooZone;
   
	$html = array();
	
	$html[] = '<div class="WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row fix-product-attributes' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = '<label class="WooZone-form-label" for="fix_product_attributes">' . __('Fix Product Attributes (woocommerce 2.4 update):', $WooZone->localizationName) . '</label>';

	$options = $WooZone->settings();
	$val = '';
	if ( isset($options['fix_product_attributes']) ) {
		$val = $options['fix_product_attributes'];
	}
		
	ob_start();
?>
	<div class="WooZone-form-item">
		<select id="fix_product_attributes" name="fix_product_attributes" style="width:120px; margin-left: 18px;">
			<?php
			foreach (array('yes' => 'YES', 'no' => 'NO') as $kk => $vv){
				echo '<option value="' . ( $vv ) . '" ' . ( $val == $vv ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<input type="button" class="' . ( WooZone()->alias ) . '-form-button-small ' . ( WooZone()->alias ) . '-form-button-primary" id="WooZone-fix_product_attributes" value="' . ( __('fix Now ', $WooZone->localizationName) ) . '">
	<div style="width: 100%; display: none; margin-top: 10px; " class="WooZone-response-options  WooZone-callout WooZone-callout-info"></div>';

	$html[] = '</div>';
	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {
		var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>'

		$("body").on("click", "#WooZone-fix_product_attributes", function(){

			$.post(ajaxurl, {
				'action' 		: 'WooZone_fix_product_attributes',
				'sub_action'	: 'fix_product_attributes'
			}, function(response) {

				var $box = $('.fix-product-attributes'), $res = $box.find('.WooZone-response-options');
				$res.html( response.msg_html ).show();
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

function WooZone_fix_node_childrens( $istab = '' ) {
	global $WooZone;
   
	$html = array();
	
	$html[] = '<div class="WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row fix-node-childrens' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = '<label class="WooZone-form-label" for="fix_node_childrens">' . __('Clear Search old Node Childrens:', $WooZone->localizationName) . '</label>';

	$options = $WooZone->settings();
	$val = '';
	if ( isset($options['fix_node_childrens']) ) {
		$val = $options['fix_node_childrens'];
	}
		
	ob_start();
?>
	<div class="WooZone-form-item">
		<select id="fix_node_childrens" name="fix_node_childrens" style="width:120px; margin-left: 18px;">
			<?php
			foreach (array('yes' => 'YES', 'no' => 'NO') as $kk => $vv){
				echo '<option value="' . ( $vv ) . '" ' . ( $val == $vv ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<input type="button" class="' . ( WooZone()->alias ) . '-form-button-small ' . ( WooZone()->alias ) . '-form-button-primary" id="WooZone-fix_node_childrens" value="' . ( __('Clear Now ', $WooZone->localizationName) ) . '">
	<div style="width: 100%; display: none; margin-top: 10px; " class="WooZone-response-options  WooZone-callout WooZone-callout-info"></div>';

	$html[] = '</div>';
	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {
		var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>'

		$("body").on("click", "#WooZone-fix_node_childrens", function(){

			$.post(ajaxurl, {
				'action' 		: 'WooZone_fix_node_childrens',
				'sub_action'	: 'fix_node_childrens'
			}, function(response) {

				var $box = $('.fix-node-childrens'), $res = $box.find('.WooZone-response-options');
				$res.html( response.msg_html ).show();
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

function WooZone_amazon_countries( $istab = '', $is_subtab='', $what='array' ) {
	global $WooZone;
	
	$html         = array();
	$img_base_url = $WooZone->cfg['paths']["plugin_dir_url"] . 'modules/amazon/images/flags/';
	
	$config = $WooZone->settings();

	$theHelper = $WooZone->get_ws_object_new( $WooZone->cur_provider, 'new_helper', array(
		'the_plugin' => $WooZone,
	));
	//:: disabled on 2018-feb
	//require_once( $WooZone->cfg['paths']['plugin_dir_path'] . 'aa-framework/amz.helper.class.php' );
	//if ( class_exists('WooZoneAmazonHelper') ) {
	//	//$theHelper = WooZoneAmazonHelper::getInstance( $aiowaff );
	//	$theHelper = new WooZoneAmazonHelper( $WooZone );
	//}
	//:: end disabled on 2018-feb
	$list = is_object($theHelper) ? $theHelper->get_countries( $what ) : array();
	
	if ( in_array($what, array('country', 'main_aff_id')) ) {
		return $list;
	}
	return implode(', ', array_values($list));
}

// WooZone_insane_last_reports Warning: Illegal string offset 'request_amazon' issue
function WooZone_fix_issue_request_amazon( $istab = '' ) {
	global $WooZone;
   
	$html = array();
	
	$html[] = '<div class="WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row fix_issue_request_amazon2' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = '<label class="WooZone-form-label" for="fix_issue_request_amazon">' . __('Fix Request Amazon Issue:', $WooZone->localizationName) . '</label>';

	$options = $WooZone->settings();
	$val = '';
	if ( isset($options['fix_issue_request_amazon']) ) {
		$val = $options['fix_issue_request_amazon'];
	}
		
	ob_start();
?>
		<select id="fix_issue_request_amazon" name="fix_issue_request_amazon" style="width:120px; margin-left: 18px;">
			<?php
			foreach (array('yes' => 'YES', 'no' => 'NO') as $kk => $vv){
				echo '<option value="' . ( $vv ) . '" ' . ( $val == $vv ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<input type="button" class="' . ( WooZone()->alias ) . '-form-button-small ' . ( WooZone()->alias ) . '-form-button-primary" id="WooZone-fix_issue_request_amazon" value="' . ( __('fix Now ', $WooZone->localizationName) ) . '">
	<div style="width: 100%; display: none; margin-top: 10px; " class="WooZone-response-options  WooZone-callout WooZone-callout-info"></div>';

	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {
		var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>'

		$("body").on("click", "#WooZone-fix_issue_request_amazon", function(){

			$.post(ajaxurl, {
				'action' 		: 'WooZone_fix_issues',
				'sub_action'	: 'fix_issue_request_amazon'
			}, function(response) {

				var $box = $('.fix_issue_request_amazon2'), $res = $box.find('.WooZone-response-options');
				$res.html( response.msg_html ).show();
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

// Fix Sync Issue
function WooZone_fix_issue_sync( $istab = '' ) {
	global $WooZone;
   
	$html = array();

	$options = $WooZone->settings();

	$html[] = '<div class="WooZone-bug-fix WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row fix_issue_sync-wrapp' . ($istab!='' ? ' '.$istab : '') . '" style="line-height: 35px;">';

	// products in trash after X tries
	$val_trash = $WooZone->sync_tries_till_trash;
	if ( isset($options['fix_issue_sync'], $options['fix_issue_sync']['trash_tries']) ) {
		$val_trash = $options['fix_issue_sync']['trash_tries'];
	}
	
	$html[] = '<div>';
	$html[] = '<label style="display: inline; float: none;" for="fix_issue_sync-trash_tries">' . __('Put amazon products in trash when syncing after: ', $WooZone->localizationName) . '</label>';

	ob_start();
?>
		<select id="fix_issue_sync-trash_tries" name="fix_issue_sync[trash_tries]" style="width: 120px; margin-left: 18px;">
			<?php
			foreach (array(1 => 'First try', 2 => 'Second try', 3 => 'Third try', 4 => '4th try', 5 => '5th try', -1 => 'Never') as $kk => $vv){
				echo '<option value="' . ( $kk ) . '" ' . ( $val_trash == $kk ? 'selected="selected"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	//$html[] = '<input type="button" class="WooZone-button green" style="width: 160px;" id="fix_issue_sync-save_setting" value="' . ( __('Verify how many', $WooZone->localizationName) ) . '">';
	$html[] = '<span style="margin: 0px; margin-left: 10px; display: block;" class="response_save"></span>';
	$html[] = '</div>';
	
	// restore products with status
	$val_restore = 'publish';
	if ( isset($options['fix_issue_sync'], $options['fix_issue_sync']['restore_status']) ) {
		$val_restore = $options['fix_issue_sync']['restore_status'];
	}
	
	$html[] = '<div>';
	$html[] = '<input type="button" class="WooZone-form-button-small WooZone-form-button-primary" style="vertical-align:middle;line-height:12px;" id="fix_issue_sync-fix_now" value="' . ( __('Restore now', $WooZone->localizationName) ) . '">';
	$html[] = '<label style="display: inline; float: none;" for="fix_issue_sync-restore_status">' . __('trashed amazon products (and variations) | their NEW status: ', $WooZone->localizationName) . '</label>';

	ob_start();
?>
		<select id="fix_issue_sync-restore_status" name="fix_issue_sync[restore_status]" style="width: 120px; margin-left: 18px;">
			<?php
			foreach (array('publish' => 'Publish', 'draft' => 'Draft') as $kk => $vv){
				echo '<option value="' . ( $kk ) . '" ' . ( $val_restore == $kk ? 'selected="selected"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<span style="margin: 0px; margin-left: 10px; display: block;" class="response_fixnow"></span>';
	$html[] = '</div>';

	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {
		var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>'

		$("body").on("click", "#fix_issue_sync-save_setting", function(){

			$.post(ajaxurl, {
				'action' 		: 'WooZone_fix_issues',
				'sub_action'	: 'sync_tries_trash'
			}, function(response) {

				var $box = $('.fix_issue_sync-wrapp'), $res = $box.find('.response_save');
				$res.html( response.msg_html );
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});

		// restore status
		$("body").on("click", "#fix_issue_sync-fix_now", function(){

			$.post(ajaxurl, {
				'action' 		: 'WooZone_fix_issues',
				'sub_action'	: 'sync_restore_status',
				'what'			: 'verify'
			}, function(response) {

				var $box = $('.fix_issue_sync-wrapp'), $res = $box.find('.response_fixnow');
				$res.html( response.msg_html );
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});
		
		$("body").on("click", "#fix_issue_sync-fix_now_cancel", function(){
			var $box = $('.fix_issue_sync-wrapp'), $res = $box.find('.response_fixnow');
			$res.html('');
		});

		$("body").on("click", "#fix_issue_sync-fix_now_doit", function(){

			$.post(ajaxurl, {
				'action' 		: 'WooZone_fix_issues',
				'sub_action'	: 'sync_restore_status',
				'what'			: 'doit',
				'post_status'	: $('#fix_issue_sync-restore_status').val(),
			}, function(response) {

				var $box = $('.fix_issue_sync-wrapp'), $res = $box.find('.response_fixnow');
				$res.html( response.msg_html );
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

// reset products stats
function WooZone_reset_products_stats( $istab = '' ) {
	global $WooZone;
   
	$html = array();
	
	$html[] = '<div class="WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row reset_products_stats2' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = '<label class="WooZone-form-label" for="reset_products_stats">' . __('Reset products stats:', $WooZone->localizationName) . '</label>';

	$options = $WooZone->settings();
	$val = '';
	if ( isset($options['reset_products_stats']) ) {
		$val = $options['reset_products_stats'];
	}
		
	ob_start();
?>
		<select id="reset_products_stats" name="reset_products_stats" style="width:120px; margin-left: 18px;">
			<?php
			foreach (array('yes' => 'YES', 'no' => 'NO') as $kk => $vv){
				echo '<option value="' . ( $vv ) . '" ' . ( $val == $vv ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<input type="button" class="' . ( WooZone()->alias ) . '-form-button-small ' . ( WooZone()->alias ) . '-form-button-primary" id="WooZone-reset_products_stats" value="' . ( __('reset Now ', $WooZone->localizationName) ) . '">
	<div style="width: 100%; display: none; margin-top: 10px; " class="WooZone-response-options  WooZone-callout WooZone-callout-info"></div>';

	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {
		var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>'

		$("body").on("click", "#WooZone-reset_products_stats", function(){

			$.post(ajaxurl, {
				'action' 		: 'WooZone_fix_issues',
				'sub_action'	: 'reset_products_stats'
			}, function(response) {

				var $box = $('.reset_products_stats2'), $res = $box.find('.WooZone-response-options');
				$res.html( response.msg_html ).show();
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

// from version 9.0 options prefix changed from wwcAmzAff to WooZone
function WooZone_options_prefix_change( $istab = '' ) {
	global $WooZone;
   
	$html = array();
	
	$html[] = '<div class="WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row options_prefix_change2' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = '<label class="WooZone-form-label" for="options_prefix_change">' . __('Version 9.0 options prefix change:', $WooZone->localizationName) . '</label>';

	$options = $WooZone->settings();
	$val = '';
	if ( isset($options['options_prefix_change']) ) {
		$val = $options['options_prefix_change'];
	}
		
	ob_start();
?>
		<select id="options_prefix_change" name="options_prefix_change" style="width:240px; margin-left: 18px;">
			<?php
			$arr_sel = array(
				//'default' 		=> 'Default (keep new version 9.0 settings)',
				'use_new'		=> 'Keep new version 9.0 settings',
				'use_old'		=> 'Restore old version prior to 9.0 settings'
			);
			foreach ($arr_sel as $kk => $vv){
				echo '<option value="' . ( $kk ) . '" ' . ( $val == $kk ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<input type="button" class="' . ( WooZone()->alias ) . '-form-button-small ' . ( WooZone()->alias ) . '-form-button-primary" id="WooZone-options_prefix_change" value="' . ( __('do it now', $WooZone->localizationName) ) . '">
	<div style="width: 100%; display: none; margin-top: 10px; " class="WooZone-response-options  WooZone-callout WooZone-callout-info"></div>';

	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {
		var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>'

		$("body").on("click", "#WooZone-options_prefix_change", function(){

			$.post(ajaxurl, {
				'action' 			: 'WooZone_fix_issues',
				'sub_action'	: 'options_prefix_change',
				'what'			: $('#options_prefix_change').val()
			}, function(response) {

				var $box = $('.options_prefix_change2'), $res = $box.find('.WooZone-response-options');
				$res.html( response.msg_html ).show();
				if ( response.status == 'valid' ) {
					window.location.reload();
					return true;
				}
				return false;
			}, 'json');
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

// from version 9.0 options prefix changed from wwcAmzAff to WooZone
function WooZone_unblock_cron( $istab = '' ) {
	global $WooZone;
   
	$html = array();
	
	$html[] = '<div class="WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row unblock_cron' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = '<label class="WooZone-form-label" for="options_prefix_change">' . __('Unblock CRON jobs:', $WooZone->localizationName) . '</label>';

	$options = $WooZone->settings();
	$val = '';
	if ( isset($options['unblock_cron']) ) {
		$val = $options['unblock_cron'];
	}
?>
	<select id="unblock_cron" name="unblock_cron" style="width:120px; margin-left: 18px;">
			<?php
			foreach (array('yes' => 'YES', 'no' => 'NO') as $kk => $vv) {
				echo '<option value="' . ( $vv ) . '" ' . ( $val == $kk ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			}
			?>
		</select>&nbsp;&nbsp;
	<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<input type="button" class="' . ( WooZone()->alias ) . '-form-button-small ' . ( WooZone()->alias ) . '-form-button-primary" id="WooZone-unblock_cron" value="' . ( __('Unblock Now ', $WooZone->localizationName) ) . '">
	<div style="width: 100%; display: none; margin-top: 10px; " class="WooZone-response-options  WooZone-callout WooZone-callout-info"></div>';

	$html[] = '</div>';

	// view page button
	ob_start();
	?>
	<script>
	(function($) {
		var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>'

		$("body").on("click", "#WooZone-unblock_cron", function(){

			$.post(ajaxurl, {
				'action' 		: 'WooZone_fix_issues',
				'sub_action'	: 'unblock_cron'
			}, function(response) {

				var $box = $('.unblock_cron'), $res = $box.find('.WooZone-response-options');
				$res.html( response.msg_html ).show();
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

function WooZone_productinpost_extra_css() {
	/*
	.wb-buy {
		width: 176px;
		height: 28px;
		background: url(images/buy.gif) no-repeat top left;
		text-indent: -99999px;
		overflow: hidden;
		display: block;
		opacity: 0.7;
		transition: opacity 350ms ease;
	}
	*/
	ob_start();
?>
	.wb-box {
		background-color: #f9f9f9;
		border: 1px solid #ecf0f1;
		border-radius: 5px;
		font-family: 'Open Sans', sans-serif;
		margin: 20px auto;
		padding: 2%;
		width: 90%;
		max-width: 660px;
		font-family: 'Open Sans';
	}
<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}

function WooZone_asof_font_size($min=0.1, $max=2.0, $step=0.1) {
	$newarr = array();
	for ($i=$min; $i <= $max; $i += $step, $i = (float) number_format($i, 1)) {
		$newarr[ "$i" ] = $i . ' em';
	}
	return $newarr;
}

function WooZone_cache_images( $action='default', $istab = '', $is_subtab='' ) {
	global $WooZone;
	
	$req['action'] = $action;

	if ( $req['action'] == 'getStatus' ) {
			return '';
	}

	$html = array();
	
	ob_start();
?>
<div class="WooZone-form-row WooZone-im-cache <?php echo ($istab!='' ? ' '.$istab : ''); ?><?php echo ($is_subtab!='' ? ' '.$is_subtab : ''); ?>">

	<label><?php _e('Images Cache', 'psp'); ?></label>
	<div class="WooZone-form-item large">
		<span style="margin:0px 0px 0px 10px" class="response"><?php //echo WooZone_cache_images( 'getStatus' ); ?></span><br />
		<input type="button" class="WooZone-form-button WooZone-form-button-danger" style="width: 160px;" id="WooZone-im-cache-delete" value="<?php _e('Clear cache', 'psp'); ?>">
		<span class="formNote">&nbsp;</span>

	</div>
</div>
<?php
	$htmlRow = ob_get_contents();
	ob_end_clean();
	$html[] = $htmlRow;
	
	// view page button
	ob_start();
?>
	<script>
	(function($) {
		var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
		
		$(document).ready(function() {
			get_status();
		});

		$("body").on("click", "#WooZone-im-cache-delete", function(){
			cache_delete();
		});
		
		function get_status() {
			$.post(ajaxurl, {
				'action'        : 'WooZone_images_cache',
				'sub_action'    : 'getStatus'
			}, function(response) {

				var $box = $('.WooZone-im-cache'), $res = $box.find('.response');
				$res.html( response.msg_html );
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		};
		
		function cache_delete() {
			$.post(ajaxurl, {
				'action'        : 'WooZone_images_cache',
				'sub_action'    : 'cache_delete'
			}, function(response) {

				var $box = $('.WooZone-im-cache'), $res = $box.find('.response');
				$res.html( response.msg_html );
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		}
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;

	return implode( "\n", $html );
}

function WooZone_variation_number() {
	$ret = array(
		'no'        => 'NO',
		'yes_1'     => 'Yes 1 variation',
		'yes_2'     => 'Yes 2 variations',
		'yes_3'     => 'Yes 3 variations',
		'yes_4'     => 'Yes 4 variations',
		'yes_5'     => 'Yes 5 variations',
		'yes_10'    => 'Yes 10 variations',
		'yes_all'   => 'Yes All variations',
	);

	$ret = array();
	$ret['no'] = 'NO';
	for ($ii = 1; $ii < 100; $ii++) {
		$ret["yes_$ii"] = "Yes $ii variation" . ($ii > 1 ? 's' : '');
	}
	$ret['yes_all'] = 'Yes All variations';
	return $ret;
}

// reset products stats
function WooZone_reset_sync_stats( $istab = '' ) {
	global $WooZone;
   
	$html = array();
	
	$html[] = '<div class="WooZone-bug-fix panel-body WooZone-panel-body WooZone-form-row reset_sync_stats2' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = '<label class="WooZone-form-label" for="reset_sync_stats">' . __('Reset products SYNC stats:', $WooZone->localizationName) . '</label>';

	$options = $WooZone->settings();
	$val = 'yes';
	if ( isset($options['reset_sync_stats']) ) {
		$val = $options['reset_sync_stats'];
	}
		
	ob_start();
?>
		<select id="reset_sync_stats" name="reset_sync_stats" style="width: 240px; margin-left: 18px;">
			<?php
			$optionsList = array(
				'yes_all' 	=> 'YES - complete sync reset',
				'yes' 		=> 'YES - only reset last sync date',
				'no' 		=> 'NO'
			);
			foreach ($optionsList as $kk => $vv){
				echo '<option value="' . ( $kk ) . '" ' . ( $val == $kk ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$html[] = ob_get_contents();
	ob_end_clean();

	$html[] = '<input type="button" class="' . ( WooZone()->alias ) . '-form-button-small ' . ( WooZone()->alias ) . '-form-button-primary" id="WooZone-reset_sync_stats" value="' . ( __('reset Now ', $WooZone->localizationName) ) . '">';
	$html[] = '<span class="WooZone-form-note WooZone-reset-sync-help" style="display: block;"><ul><li><span>YES - complete sync reset</span> : reset all sync meta info for your amazon products</li><li><span>YES - only reset last sync date</span> : reset only the last sync date meta info for your products</li><li><span>NO</span> : don\'t reset sync for products</li></ul></span>';
	$html[] = '<div style="width: 100%; display: none; margin-top: 10px; " class="WooZone-response-options  WooZone-callout WooZone-callout-info"></div>';

	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {
		var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>'

		$("body").on("click", "#WooZone-reset_sync_stats", function(){

			$.post(ajaxurl, {
				'action' 		: 'WooZone_fix_issues',
				'sub_action'	: 'reset_sync_stats',
				'what'			: $('#reset_sync_stats').val()
			}, function(response) {

				var $box = $('.reset_sync_stats2'), $res = $box.find('.WooZone-response-options');
				$res.html( response.msg_html ).show();
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;
  
	return implode( "\n", $html );
}

function WooZone_optfunc_badges_box( $istab = '' ) {
	global $WooZone;
   
	$html = array();

	$options = $WooZone->settings();

	$html[] = '<div class="wzadmin-badges panel-body WooZone-panel-body WooZone-form-row ' . ($istab!='' ? ' '.$istab : '') . '">';

	$html[] = 	'<label class="WooZone-form-label">' . __('Badges / Flags', $WooZone->localizationName) . '</label>';

	$html[] = 	'<div>';

	//var_dump('<pre>', $options , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
	$opt_yesno = array(
		'yes' 	=> 'YES',
		'no' 	=> 'NO',
	);
	$opt_box_position = array(
		'top_left' 		=> 'top left',
		'top_right' 	=> 'top right',
		'bottom_left' 	=> 'bottom left',
		'bottom_right' 	=> 'bottom right',
	);
	$opt_badges_activated = array(
		'new' 			=> 'New',
		'onsale' 		=> 'On Sale',
		'freeshipping' 	=> 'Free Shipping',
		'amazonprime' 	=> 'Amazon Prime',
	);
	$opt_badges_where = array(
		'product_page' 			=> 'product page',
		'sidebar' 				=> 'sidebar',
		'minicart' 				=> 'minicart',
		'box_related_products' 	=> 'box related products',
		'box_cross_sell' 		=> 'box cross sell',
	);


	$frontend_hide_onsale_default_badge = isset($options['frontend_hide_onsale_default_badge'])
		? $options['frontend_hide_onsale_default_badge'] : 'no';

	$frontend_show_free_shipping = isset($options['frontend_show_free_shipping'])
		? $options['frontend_show_free_shipping'] : 'yes';

	$badges_box_position = isset($options['badges_box_position'])
		? $options['badges_box_position'] : 'top_left';

	$badges_box_offset_vertical = isset($options['badges_box_offset_vertical'])
		? $options['badges_box_offset_vertical'] : '';

	$badges_box_offset_horizontal = isset($options['badges_box_offset_horizontal'])
		? $options['badges_box_offset_horizontal'] : '';

	$badges_activated = isset($options['badges_activated'])
		? (array) $options['badges_activated'] : array();
	$badges_activated_available = array_diff( array_keys($opt_badges_activated), $badges_activated );
	//var_dump('<pre>',$badges_activated, $badges_activated_available ,'</pre>');

	$badges_where = isset($options['badges_where'])
		? (array) $options['badges_where'] : array();
	$badges_where_available = array_diff( array_keys($opt_badges_where), $badges_where );
	//var_dump('<pre>',$badges_where, $badges_where_available ,'</pre>');
	//echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

	ob_start();
?>

	<!-- Hide Woocommerce "On sale" badge -->
	<div class="wzadmin-badges-item">
		<div class="wzadmin-badges-item-title">
			<a href="#" class="WooZone-simplemodal-trigger" title="<?php _e('Choose Yes, if you want to hide the default Woocommerce \'On sale- badge\'', 'woozone'); ?>"><i class="fa fa-info-circle"></i></a>
			<span><?php _e('Hide Woocommerce Default "On sale" badge', 'woozone'); ?></span>
		</div>
		<div class="wzadmin-badges-item-property">
			<select id="frontend_hide_onsale_default_badge" name="frontend_hide_onsale_default_badge" class="small">
				<?php
				foreach ( $opt_yesno as $key => $val ) {
					$is_selected = $key == $frontend_hide_onsale_default_badge ? ' selected="true"' : '';
					echo '<option value="' . $key . '"' . $is_selected . '>' . $val . '</option>';
				}
				?>
			</select>
		</div>
	</div>

	<!-- Show "Free Shipping" text beside product price -->
	<div class="wzadmin-badges-item">
		<div class="wzadmin-badges-item-title">
			<a href="#" class="WooZone-simplemodal-trigger" title="<?php _e('Choose Yes, if you want to show \'Free Shipping\' text on frontend beside the product price on product details page (you can choose No for this, and only show the \'Free Shipping\' badge)', 'woozone'); ?>"><i class="fa fa-info-circle"></i></a>
			<span><?php _e('Show Default "Free Shipping" text beside product price', 'woozone'); ?></span>
		</div>
		<div class="wzadmin-badges-item-property">
			<select id="frontend_show_free_shipping" name="frontend_show_free_shipping" class="small">
				<?php
				foreach ( $opt_yesno as $key => $val ) {
					$is_selected = $key == $frontend_show_free_shipping ? ' selected="true"' : '';
					echo '<option value="' . $key . '"' . $is_selected . '>' . $val . '</option>';
				}
				?>
			</select>
		</div>
	</div>

	<!-- Badges Box Position -->
	<div class="wzadmin-badges-item">
		<div class="wzadmin-badges-item-title">
			<a href="#" class="WooZone-simplemodal-trigger" title="<?php _e('Badges Box Position', 'woozone'); ?>"><i class="fa fa-info-circle"></i></a>
			<span><?php _e('Badges Box Position', 'woozone'); ?></span>
		</div>
		<div class="wzadmin-badges-item-property">
			<select id="badges_box_position" name="badges_box_position" class="small">
				<?php
				foreach ( $opt_box_position as $key => $val ) {
					$is_selected = $key == $badges_box_position ? ' selected="true"' : '';
					echo '<option value="' . $key . '"' . $is_selected . '>' . $val . '</option>';
				}
				?>
			</select>
		</div>
	</div>

	<!-- Badges Box Offset vertical (px) -->
	<div class="wzadmin-badges-item">
		<div class="wzadmin-badges-item-title">
			<a href="#" class="WooZone-simplemodal-trigger" title="<?php _e('Badges Box Offset vertical (in pixels)', 'woozone'); ?>"><i class="fa fa-info-circle"></i></a>
			<span><?php _e('Badges Box Offset vertical (px)', 'woozone'); ?></span>
		</div>
		<div class="wzadmin-badges-item-property">
			<input id="badges_box_offset_vertical" name="badges_box_offset_vertical" type="text" value="<?php echo $badges_box_offset_vertical; ?>" placeholder="enter the value in pixels (ex.: 15)" class="">
		</div>
	</div>

	<!-- Badges Box Offset horizontal (px) -->
	<div class="wzadmin-badges-item">
		<div class="wzadmin-badges-item-title">
			<a href="#" class="WooZone-simplemodal-trigger" title="<?php _e('Badges Box Offset horizontal (in pixels)', 'woozone'); ?>"><i class="fa fa-info-circle"></i></a>
			<span><?php _e('Badges Box Offset horizontal (px)', 'woozone'); ?></span>
		</div>
		<div class="wzadmin-badges-item-property">
			<input id="badges_box_offset_horizontal" name="badges_box_offset_horizontal" type="text" value="<?php echo $badges_box_offset_horizontal; ?>" placeholder="enter the value in pixels (ex.: 15)" class="">
		</div>
	</div>

	<!-- Activated Badges -->
	<div class="wzadmin-badges-item wzadmin-badges-badges_activated">
		<div class="wzadmin-badges-item-title">
			<a href="#" class="WooZone-simplemodal-trigger" title="<?php _e('Activated Badges', 'woozone'); ?>"><i class="fa fa-info-circle"></i></a>
			<span><?php _e('Active Badges', 'woozone'); ?></span>
		</div>
		<div class="wzadmin-badges-item-property">

			<div class="WooZone-multiselect-half WooZone-multiselect-available" style="margin-right: 2%;">
				<h5>All badges list</h5>
				<select multiple="multiple" size="8" name="badges_activated-available[]" id="badges_activated-available" class="multisel_l2r_available">
				<?php
				foreach ( $opt_badges_activated as $key => $val ) {
					if ( in_array($key, $badges_activated_available) ) {
						$is_selected = ' selected="true"';
						echo '<option value="' . $key . '"' . $is_selected . '>' . $val . '</option>';
					}
				}
				?>
				</select>
			</div>
			<div class="WooZone-multiselect-half WooZone-multiselect-selected">
				<h5>Selected Active Badges</h5>
				<select multiple="multiple" size="8" name="badges_activated[]" id="badges_activated" class="multisel_l2r_selected">
				<?php
				foreach ( $opt_badges_activated as $key => $val ) {
					if ( in_array($key, $badges_activated) ) {
						$is_selected = ' selected="true"';
						echo '<option value="' . $key . '"' . $is_selected . '>' . $val . '</option>';
					}
				}
				?>
				</select>
			</div>
			<div style="clear:both"></div>
			<div class="multisel_l2r_btn" style="">
				<span style="display: inline-block; width: 24.1%; text-align: center;">
					<input id="standard_content-moveright" type="button" value="Move Right" class="moveright WooZone-button gray WooZone-form-button-small WooZone-form-button-info">
				</span>
				<span style="display: inline-block; width: 24.1%; text-align: center;">
					<input id="standard_content-moverightall" type="button" value="Move Right All" class="moverightall WooZone-button gray WooZone-form-button-small WooZone-form-button-info">
				</span>
				<span style="display: inline-block; width: 24.1%; text-align: center;">
					<input id="standard_content-moveleft" type="button" value="Move Left" class="moveleft WooZone-button gray WooZone-form-button-small WooZone-form-button-info">
				</span>
				<span style="display: inline-block; width: 24.1%; text-align: center;">
					<input id="standard_content-moveleftall" type="button" value="Move Left All" class="moveleftall WooZone-button gray WooZone-form-button-small WooZone-form-button-info">
				</span>
			</div>

		</div>
	</div>

<?php /*
	<!-- Badges Where -->
	<div class="wzadmin-badges-item wzadmin-badges-badges_where">
		<div class="wzadmin-badges-item-title">
			<a href="#" class="WooZone-simplemodal-trigger" title="<?php _e('Badges Where', 'woozone'); ?>"><i class="fa fa-info-circle"></i></a>
			<span><?php _e('Badges Where', 'woozone'); ?></span>
		</div>
		<div class="wzadmin-badges-item-property">

			<div class="WooZone-multiselect-half WooZone-multiselect-available" style="margin-right: 2%;">
				<h5>All badges where list</h5>
				<select multiple="multiple" size="8" name="badges_where-available[]" id="badges_where-available" class="multisel_l2r_available">
				<?php
				foreach ( $opt_badges_where as $key => $val ) {
					if ( in_array($key, $badges_where_available) ) {
						$is_selected = ' selected="true"';
						echo '<option value="' . $key . '"' . $is_selected . '>' . $val . '</option>';
					}
				}
				?>
				</select>
			</div>
			<div class="WooZone-multiselect-half WooZone-multiselect-selected">
				<h5>Your chosen badges where from list</h5>
				<select multiple="multiple" size="8" name="badges_where[]" id="badges_where" class="multisel_l2r_selected">
				<?php
				foreach ( $opt_badges_where as $key => $val ) {
					if ( in_array($key, $badges_where) ) {
						$is_selected = ' selected="true"';
						echo '<option value="' . $key . '"' . $is_selected . '>' . $val . '</option>';
					}
				}
				?>
				</select>
			</div>
			<div style="clear:both"></div>
			<div class="multisel_l2r_btn" style="">
				<span style="display: inline-block; width: 24.1%; text-align: center;">
					<input id="standard_content-moveright" type="button" value="Move Right" class="moveright WooZone-button gray WooZone-form-button-small WooZone-form-button-info">
				</span>
				<span style="display: inline-block; width: 24.1%; text-align: center;">
					<input id="standard_content-moverightall" type="button" value="Move Right All" class="moverightall WooZone-button gray WooZone-form-button-small WooZone-form-button-info">
				</span>
				<span style="display: inline-block; width: 24.1%; text-align: center;">
					<input id="standard_content-moveleft" type="button" value="Move Left" class="moveleft WooZone-button gray WooZone-form-button-small WooZone-form-button-info">
				</span>
				<span style="display: inline-block; width: 24.1%; text-align: center;">
					<input id="standard_content-moveleftall" type="button" value="Move Left All" class="moveleftall WooZone-button gray WooZone-form-button-small WooZone-form-button-info">
				</span>
			</div>

		</div>
	</div>
*/ ?>

<?php
	$html[] = ob_get_clean();

	$html[] = 	'</div>';

	$html[] = '</div>';

	return implode( "\n", $html );
}