<?php 
! defined( 'ABSPATH' ) and exit;
// load the modules managers class
$module_class_path = $module['folder_path'] . 'init.php';

if ( is_file($module_class_path) ) {

	require_once( 'init.php' );
		
	$WooZoneMultipleAmazonKeys = new WooZoneMultipleAmazonKeys($this->cfg, $module);
	
	$__module_is_setup_valid = $WooZoneMultipleAmazonKeys->moduleValidation();
	$__module_is_setup_valid = (bool) $__module_is_setup_valid['status'];
		
	// print the lists interface 
	if ( !WooZone()->can_import_products() ) {
		echo WooZone()->demo_products_import_end_html();
	}
	else {
		echo $WooZoneMultipleAmazonKeys->printSearchInterface();
	}
}