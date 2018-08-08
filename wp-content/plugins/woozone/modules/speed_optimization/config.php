<?php
/**
 * Config file, return as json_encode
 * http://www.aa-team.com
 * =======================
 *
 * @author		AA-Team
 * @version		1.0
 */
echo json_encode(
	array(
		'speed_optimization' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 4,
				'show_in_menu' => false,
				'title' => 'Speed Optimization',
				'icon' => 'light'
			),
			'in_dashboard' => array(
				'icon' 	=> 'light',
				'url'	=> admin_url("admin.php?page=WooZone_speed_optimization")
			),
			'help' => array(
				'type' => 'remote',
				'url' => 'http://docs.aa-team.com/woocommerce-amazon-affiliates/documentation/server-status/'
			),
			'description' => 'This module will help you mass optimize all your amazon products based on the options you setup here in order to have a better website speed / loading time.',
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=WooZone_speed_optimization',
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