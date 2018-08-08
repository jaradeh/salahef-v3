<?php

global $WooZone;

echo json_encode(array(
	$tryed_module['db_alias'] => array(
		
		/* define the form_sizes  box */
		'sync_options' => array(
			'title' => 'Synchronization log Settings',
			'icon' => '{plugin_folder_uri}images/16.png',
			'size' => 'grid_4', // grid_1|grid_2|grid_3|grid_4
			'header' => true, // true|false
			'toggler' => false, // true|false
			'buttons' => true, // true|false
			'style' => 'panel', // panel|panel-widget
			
			// create the box elements array
			'elements' => array(

				'interface_max_products' => array(
					'type' => 'text',
					'std' => '50',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Products per page',
					'desc' => 'Number of products per page to show for pagination in the interface: all = all products are displayed; >0 = number of products to be displayed'
					//'desc' => 'Maximum number of products to show in the interface (usefull when you have too many products and the interface breaks): all = all products are displayed; 0 = no products is displayed; >0 = number of products to be displayed'
				),
				
				'sync_choose_country' => array(
					'type' => 'select',
					'std' => 'default',
					'size' => 'large',
					'force_width' => '350',
					'title' => 'Amazon location for sync',
					'desc' => 'With this setting you can choose which "amazon location" to use to sync products. Available values: <br /><span style="color: red;">Use current "amazon location" setting</span> : is the current setting from amazon config module<br /><span style="color: red;">Use product import country</span> : is the "amazon location" setting which was active when you\'ve imported the product.',
					'options' => array(
						'default' => 'Use current "amazon location" setting (DEFAULT)',
						'import_country' => 'Use product import country'
					)
				),

				'syncfront_activate' => array(
					'type' => 'select',
					'std' => 'no',
					'size' => 'large',
					'force_width' => '100',
					'title' => 'Sync on Frontend',
					'desc' => 'This option will activate the product synchronization when any client navigate on a product details page on website frontend - the condition based on product last sync date and recurrence must be met, otherwise the product isn\'t synced. Requests are made by ajax and clients will be notified by an overlay only if product data was refreshed by the sync process.',
					'options' => array(
						'yes' => 'YES',
						'no' => 'NO'
					)
				),

				'sync_cronjob_type' => array(
					'type' => 'select',
					'std' => 'product_and_variations',
					'size' => 'large',
					'force_width' => '350',
					'title' => 'Cronjob Sync Type',
					'desc' => '<u>Default version</u> <br />The "Products per request" from SYNCHRONISATION SETTINGS from Synchronization logs module take into consideration that each product or product variation represents an item. <br /><br /><u>New version (recommended)</u> <br /> The "Products per request" from SYNCHRONISATION SETTINGS from Synchronization logs module : in this case only a product (no matter how many variations it has) is considered an item. So basicaly we sync a product and all it\' variations (if it\'s variable). This "New version" is mostly recommended if you have much more variations than products (like 3 or greater, more variations than products).',
					'options' => array(
						'default' => 'Default version',
						'product_and_variations' => 'New version'
					)
				),

				'sync_cronjob_prods_orderby' => array(
					'type' => 'select',
					'std' => 'id',
					'size' => 'large',
					'force_width' => '350',
					'title' => 'Cronjob Sync Prods Orderby',
					'desc' => 'Here you must choose how you want our cronjob to parse the products. <br /><u>Product ID (ASC)</u>  (recommended) <br />Products are parsed from the oldest one imported to the latest one, in ascendent order. <br /><br /><u>Product Page Views (DESC)</u> <br />Products are parsed based on each product number of page views (we consider a product page view when a client access the product details page on website frontend), so from the one with the most page views to the one with the lowest page views. <br /><br /><u>Product Page Views > 0 (DESC)</u> <br /> Practically it\'s the same as <i>Product Page Views (DESC)</i> with the only difference that we\'ll only parse the products which have at least one page views.',
					'options' => array(
						'id' => 'Product ID (ASC)',
						'product_page_views' => 'Product Page Views (DESC)',
						'product_page_views_positive' => 'Product Page Views > 0 (DESC)'
					)
				),

			)
		)
	)
));