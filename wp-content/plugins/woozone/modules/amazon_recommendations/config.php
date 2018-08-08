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
		'amazon_recommendations' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 4,
				'show_in_menu' => false,
				'title' => 'Amazon Recommendations',
				'icon' => 'award'
			),
			'in_dashboard' => array(
				'icon' 	=> 'award',
				'url'	=> admin_url("admin.php?page=WooZone_amazon_recommendations")
			),
			'help' => array(
				'type' => 'remote',
				'url' => 'http://docs.aa-team.com/woocommerce-amazon-affiliates/documentation/new-module-amazon-recommendations/'
			),
			'description' => 'This module enables you to drive recommendations based on a key phrase. For example, if you have a shop featuring apple products, you can get recommendations on what other apple products to import. Also, on keyword click, it autosearches for those keywords and set them to import. ',
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=WooZone_amazon_recommendations',
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