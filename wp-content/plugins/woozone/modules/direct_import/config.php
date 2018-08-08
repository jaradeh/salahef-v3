<?php
/**
 * Config file, return as json_encode
 * http://www.aa-team.com
 * =======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		1.0
 */
echo json_encode(
	array(
		'direct_import' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 5,
				'show_in_menu' => true,
				'title' => 'Direct Import Chrome Extension',
				'icon' => 'auto_importpr'
			),
			'in_dashboard' => array(
				'icon' 	=> 'auto_importpr',
				'url'	=> admin_url("admin.php?page=WooZone#!/direct_import")
			),
			'help' => array(
				'type' => 'remote',
				'url' => 'http://docs.aa-team.com/woocommerce-amazon-affiliates/documentation/'
			),
			'description' => "With the WZone Direct Import extension you can set up an Affiliate Website and Import Products from Amazon without any Efforts! ",
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=WooZone_direct_import',
					'admin-ajax.php'
				),
				'frontend' => false
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy'
			),
			'css' => array(
				'admin',
				'tipsy'
			)
		)
	)
);   
