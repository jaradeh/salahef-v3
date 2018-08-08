<?php
/*
* Define class WooZoneDebugBar
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;
	  
if (class_exists('WooZoneDirectImport') != true) { class WooZoneDirectImport {

	const VERSION = '1.0';
	public $the_plugin = null;

	private $module_folder = '';
	private $module = '';

	static protected $_instance;

	private $plugin_icon_url = '';

	public $localizationName;

	private $settings;


	/*
	 * Required __construct() function that initalizes the AA-Team Framework
	 */
	public function __construct( $parent ) {
		//global $WooZone;
		//$this->the_plugin = $WooZone;
		$this->the_plugin = $parent;

		$this->plugin_icon_url = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'icon_16.png';

		$this->localizationName = $this->the_plugin->localizationName;
			
		$this->settings = $this->the_plugin->settings();

		add_action( 'wp_ajax_WooZoneDirectImport', array( $this, 'ajax_request' ) );
		add_action( 'wp_ajax_nopriv_WooZoneDirectImport', array( $this, 'ajax_request' ) );
	}

	/**
	 * Singleton pattern
	 *
	 * @return WooZoneBitly Singleton instance
	 */
	static public function getInstance( $parent ) {
		if (!self::$_instance) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	}



	//================================================
	//== Ajax Requests
	public function ajax_request( $retType='die', $pms=array() ) {
		$requestData = array(
			'action' 	=> isset($_REQUEST['sub_action']) ? (string) $_REQUEST['sub_action'] : '',
			'accesskey' => isset($_REQUEST['accesskey']) ? (string) $_REQUEST['accesskey'] : '',
		);
		extract($requestData);
		//var_dump('<pre>', $requestData , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

		$ret = array(
			'status'        => 'invalid',
			'msg'          => __('Invalid action!', 'WooZone'),
		);

		if ( empty($action)
			|| !in_array($action, array(
				'get_imported_products',
				'add_product',
				'check_if_asin_exists',
				'get_site_categories',
				//'check_authorization',
			))
		) {
			die(json_encode($ret));
		}


		//:: validate access key
		$opValidateAccess = $this->validate_accesskey( $accesskey );
		if ( 'invalid' == $opValidateAccess['status'] ) {
			$ret = array_replace_recursive($ret, $opValidateAccess);

			if ( $retType == 'return' ) { return $ret; }
			else { die( json_encode( $ret ) ); }
		}


		$ret['msg'] = 'ok.';

		if ( 'get_imported_products' == $action ) {

			$list = $this->the_plugin->getAllProductsMeta('text', '_amzASIN');
			$list = explode("\n", $list);
			$list = implode(',', $list);

			$ret = array_replace_recursive($ret, array(
				'status'	=> 'valid',
				'asins_imported' => $list,
			));
		}
		else if ( 'get_site_categories' == $action ) {

			ob_start();
			wp_dropdown_categories( array(
				'taxonomy' => 'product_cat',
				'hierarchical' => 1,
				'show_option_all' => 'Auto detect categories from Amazon',
				'id' => 'WooZoneDirectImport-dropdown-categ',
			) );

			$dropdown = ob_get_clean();
			//die($dropdown);

			$ret = array_replace_recursive($ret, array(
				'status'	=> 'valid',
				'html' => $dropdown
			));
		}
		else if ( 'check_authorization' == $action ) {
			$request = $this->the_plugin->wp_filesystem->get_contents( 'php://input' );
			if ( !$request ) {
				$request = file_get_contents( 'php://input' );
			}
			$request = json_decode( $request, true );
			//var_dump( "<pre>", $request  , "</pre>" ) ; 
			//die( __FILE__ . ":" . __LINE__  );
		}
		else if ( 'check_if_asin_exists' == $action ) {

			$asins = $this->the_plugin->wp_filesystem->get_contents( 'php://input' );
			if ( !$asins ) {
				$asins = file_get_contents( 'php://input' );
			}
			$asins = json_decode( $asins, true );

			//:: verify if product already is imported?
			$opAsinExist = WooZone_product_by_asin( $asins );

			// Temporary disable duplicate products
			if (1) {
				//var_dump('<pre>', $opAsinExist , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				if ( isset($opAsinExist["$asins[0]"]) && ! empty($opAsinExist["$asins[0]"]) ) {

					$products_url = array();
					foreach ($opAsinExist as $product) {
						if( isset($product->ID) ){
							$products_url[] = '<a href="' . ( get_permalink( $product->ID ) ) . '" target="_blank">' . $product->ID . "</a>";
						}
					}
					
					$ret = array_replace_recursive($ret, array(
						'msg' => sprintf( 'The Product is Already Imported: %s', implode(" ", $products_url) ),
					));

					if ( $retType == 'return' ) { return $ret; }
					else { die( json_encode( $ret ) ); }
				}
			}

			$ret['status'] = 'valid';
			die( json_encode( $ret ) );
		}
		else if ( 'add_product' == $action ) {

			$DEBUG = false; //DEBUG = true (only when you know what you're doing on this code)


			$requestData = array_replace_recursive( $requestData, array(
				// 0 = use browse nodes to build a category structure like on amazon
				'idcateg' 	=> isset($_REQUEST['idcateg']) ? (int) $_REQUEST['idcateg'] : 0,
			));
			extract($requestData);
			//var_dump('<pre>', $requestData , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$ret = array_replace_recursive($ret, array(
				'product_id' => 0,
			));


			if ( $DEBUG ) {
				//require_once( '_test/product.inc.php' );
				$product = $this->the_plugin->cfg['paths']['scripts_dir_path'] . '/directimport/_test/';
				$product .= 'B0769XD5YC.json';
				$product = file_get_contents( $product );
			}
			else {
				$product = $this->the_plugin->wp_filesystem->get_contents( 'php://input' );
				if ( !$product ) {
					$product = file_get_contents( 'php://input' );
				}
			}

			//die( var_dump( "<pre>", $product  , "<pre>" ) . PHP_EOL .  __FILE__ . ":" . __LINE__  );
			$product = json_decode( $product, true );
			//die( var_dump( "<pre>", $product  , "<pre>" ) . PHP_EOL .  __FILE__ . ":" . __LINE__  );


			//:: verify product has an asin?
			$opValidProduct = $this->is_valid_product_asin( $product );
			if ( ! $opValidProduct ) {
				$ret = array_replace_recursive($ret, array(
					'msg' => 'Product ASIN is missing!',
				));

				if ( $retType == 'return' ) { return $ret; }
				else { die( json_encode( $ret ) ); }
			}
			$asin = $product['ASIN'];


			//:: verify if product already is imported?
			$opAsinExist = WooZone_product_by_asin( array($asin) );

			if (1) {
				//var_dump('<pre>', $opAsinExist , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				if ( isset($opAsinExist["$asin"]) && ! empty($opAsinExist["$asin"]) ) {
					$ret = array_replace_recursive($ret, array(
						'msg' => sprintf( 'The Product is Already Imported: ASIN %s already exist(s) in the database!', $asin ),
					));

					if ( $retType == 'return' ) { return $ret; }
					else { die( json_encode( $ret ) ); }
				}
			}

			//:: build & verify product data
			$retProd = $product;
			$retProd = $this->build_product_data( $retProd );
			//var_dump('<pre>', $retProd , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			$retProd = $this->the_plugin->get_ws_object( $this->the_plugin->cur_provider )->build_product_data( $retProd );
			//var_dump('<pre>', $retProd , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			if ( ! $this->is_valid_product_data($retProd) ) {
				$ret = array_replace_recursive($ret, array(
					'msg' => 'Product data is invalid!',
				));

				if ( $retType == 'return' ) { return $ret; }
				else { die( json_encode( $ret ) ); }
			}


			//:: import product
			$import_stat = $this->the_plugin->addNewProduct( $retProd, array(
				'import_to_category' 	=> $idcateg ? $idcateg : 'amz',
			) );
			$insert_id = (int) $import_stat['insert_id'];

			update_post_meta( $insert_id, '_amzaff_direct_import', true );

			// Successfully adding product in database
			$_msg = array();
			if ( $insert_id ) {

				$ret['status'] = 'valid';
				$ret['product_id'] = $insert_id;

				$ret['msg_summary'] = 'Product was Successfully added into the DB with ID: '. $insert_id.' . Click here to <a href="' . ( get_permalink( $insert_id ) ) . '" target="_blank"> view the product </a>';
				$ret['msg'] = $ret['msg_summary'];

				$_msg[] = '<span style="display: block;height: 0px;"></span>';

				// download images
				$import_type = 'default';
				if ( isset($this->settings['import_type']) && $this->settings['import_type']=='asynchronous' ) {
					$import_type = $this->settings['import_type' ];
				}

				if ( !empty($import_type) && $import_type=='default' ) {
					if ( !$this->the_plugin->is_remote_images ) {

						// assets download module
						// Initialize the WooZoneAssetDownload class
						require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . '/modules/assets_download/init.php' );
						$WooZoneAssetDownload = new WooZoneAssetDownload(true);
						//$WooZoneAssetDownload = WooZoneAssetDownload::getInstance();

						$assets_stat = $WooZoneAssetDownload->product_assets_download( $insert_id );
						$_msg[] = $assets_stat['msg'];
					}
				}
			}
			// Error when trying to insert product in database
			else {
				$ret['msg_summary'] = 'Error trying to add product to database.';
				$ret['msg'] = $ret['msg_summary'];
			}

			//$opStatusMsg = $this->the_plugin->opStatusMsgGet();
			//$_msg[] = $opStatusMsg['msg'];
			$_msg[] = $import_stat['msg'];

			$_msg = implode('<br />', $_msg);
			$ret['msg_full'] = $_msg;

			$ret = array_merge($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> $_msg,
				'product_id' => $insert_id,
			));
		}

		if ( $retType == 'return' ) { return $ret; }
		else { die( json_encode( $ret ) ); }
	}



	//================================================
	//== Import Product

	public function is_valid_product_asin( $product=array() ) {
		if ( empty($product) || !is_array($product) ) return false;
		
		$rules = isset($product['ASIN']) && !empty($product['ASIN']);
		return $rules ? true : false;
	}

	public function is_valid_product_data( $product=array() ) {
		if ( empty($product) || !is_array($product) ) return false;
		
		$rules = isset($product['ASIN']) && !empty($product['ASIN']);
		$rules = isset($product['Title']) && !empty($product['Title']);
		return $rules ? true : false;
	}

	public function build_product_data( $product=array(), $pms=array() ) {

		$pms = array_replace_recursive( array(
			'_is_variation_child' => false,
		), $pms );
		extract( $pms );

		// attributes
		$item_attributes = isset($product['item_attributes']) ? (array) $product['item_attributes'] : array();

		// short description
		$short_desc = isset($product['short_description']) && is_array($product['short_description'])
			? $product['short_description'] : array();
		$short_desc = array_map( 'strip_tags', $short_desc );
		$short_desc = array_map( 'trim', $short_desc );


		//:: main item
		$item = array(
			'ASIN'                  	=> isset($product['ASIN']) ? trim( $product['ASIN'] ) : '',
			'ParentASIN'            	=> isset($product['ParentASIN']) ? trim( $product['ParentASIN'] ) : '',

			'SalesRank'             	=> isset($product['SalesRank']) ? trim( $product['SalesRank'] ) : 999999,
			'DetailPageURL'         	=> isset($product['DetailPageURL']) ? trim( $product['DetailPageURL'] ) : '',

			'Tags' 						=> array(),
			'CustomerReviews' 			=> array(),

			'OfferSummary'          	=> array(),
			'Offers' 					=> array(),
			'VariationSummary' 			=> array(),
		);

		//if ( empty($item['ParentASIN']) ) {
		//	$item['ParentASIN'] = $item['ASIN'];
		//}


		//:: product country
		$country = $this->get_country_from_url( $item['DetailPageURL'] );
		//var_dump('<pre>', $country , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;


		//:: full description
		$desc = isset($product['description']) ? $product['description'] : '';

		$item['EditorialReviews'] = array(
			'EditorialReview' => array(
				'Content' => $desc,
			)
		);


		//:: attributes
		$item['ItemAttributes'] = $item_attributes;
		$item['ItemAttributes'] = array_replace_recursive( $item['ItemAttributes'], array(
			//'Binding'               	=> isset($product['binding']) ? $product['binding'] : '', //not needed

			'Title'                 	=> isset($product['title']) ? trim( stripslashes( $product['title'] ) ) : '',
			'Brand'                 	=> isset($product['brand']) ? trim( $product['brand'] ) : '',

			// short description
			'Feature'               	=> $short_desc,

			'SKU'                   	=> isset($product['SKU']) ? trim( $product['SKU'] ) : '',
		));


		//:: categories
		$categories = isset($product['categories']) ? $product['categories'] : array();
		$categories = array_reverse( $categories );
		$categories = $this->product_categories_clean( $categories );

		$categories_new = array();
		if ( ! empty($categories) ) {
			$categories_new = $this->product_categories_build( $categories );
		}

		$item['BrowseNodes'] = $categories_new;


		//:: images
		$images = isset($product['images']) ? $product['images'] : array();
		$images = $this->product_images_clean( $images );

		if ( $_is_variation_child ) {
			$images = array_slice($images, 0, 1);
		}

		$images_new = array();
		if ( ! empty($images) ) {
			$images_new = $this->product_images_build( $images );
		}

		$item = array_replace_recursive( $item, array(
			'ImageSets' => array(
				'ImageSet' => isset($images_new['ImageSet']) ? $images_new['ImageSet'] : array(),
			),
			//'SmallImage' => isset($images_new['SmallImage']) ? $images_new['SmallImage'] : array(),
			//'LargeImage' => isset($images_new['LargeImage']) ? $images_new['LargeImage'] : array(),
		));


		//:: price
		$price = $this->product_price( $product, array(
			'country' => $country,
		));


		//:: offer
		$offer = $this->product_offer( $product, array(
			'country' => $country,
			'price' => $price,
		));


		//:: variations
		$variations = $this->product_variations_build( $product );

		$vars_dim = array();
		$vars_dim_len = count($variations['vars_dim']);
		if ( $vars_dim_len ) {
			$vars_dim = $vars_dim_len > 1 ? $variations['vars_dim'] : $variations['vars_dim'][0];
		}

		$variations_new = array(
			'Variations' => array(
				'TotalVariations' => count( $variations['vars'] ),
				'TotalVariationPages' => count( $variations['vars'] ) ? 1 : 0,
				'VariationDimensions' => array(
					'VariationDimension' => $vars_dim,
				),
				'Item' => $variations['vars'],
			),
		);

		$_is_variable = count( $variations['vars'] ) ? true : false;

		$item = array_replace_recursive( $item, $variations_new );


		//:: set price & offer
		$item['ItemAttributes'] = array_replace_recursive( $item['ItemAttributes'], array(
			'ListPrice' => $price['ListPrice'],
		));

		$item = array_replace_recursive( $item, array(
			'Offers' => isset($offer['Offers']) ? $offer['Offers'] : array(),
		));


		//:: variable product
		if ( $_is_variable ) {

			if ( isset($item['ItemAttributes']['ListPrice']) ) {
				unset( $item['ItemAttributes']['ListPrice'] );
			}


			$offer = $this->product_offer( $product, array(
				'country' => $country,
				'price' => $price,
				'_is_variable' => true,
			));
			//var_dump('<pre>', $offer, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$item['Offers'] = array();
			$item = array_replace_recursive( $item, array(
				'Offers' => isset($offer['Offers']) ? $offer['Offers'] : array(),
			));


			$variation_summary = $this->product_variation_summary( $product, array(
				'price' => $price,
			));
			//var_dump('<pre>', $variation_summary, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			$item = array_replace_recursive( $item, array(
				'VariationSummary' => isset($variation_summary['VariationSummary']) ? $variation_summary['VariationSummary'] : array(),
			));
		}
		else if ( $_is_variation_child ) {

			if ( isset($item['OfferSummary']) ) {
				unset( $item['OfferSummary'] );
			}
			if ( isset($item['VariationSummary']) ) {
				unset( $item['VariationSummary'] );
			}
		}


		//:: return
		if ( ! $_is_variation_child ) {
			//var_dump('<pre>', $item , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
		}
		return $item;
	}

	//:: categories
	private function product_categories_clean( $categories=array() ) {

		if ( empty($categories) || ! is_array($categories) ) return array();

		foreach ( $categories as $key => $current ) {

			$categ_id = isset($current['id']) ? $current['id'] : 0;
			$categ_name = isset($current['name']) ? trim( $current['name'] ) : '';

			if ( ! $categ_id || empty($categ_name) ) {
				unset( $categories["$key"]);
			}
		}
		return $categories;
	}

	private function product_categories_build( $categories=array() ) {

		$current = array_shift( $categories );

		$item = array();
		$item['BrowseNode'] = array(
			'BrowseNodeId' => $current['id'],
			'Name' => $current['name'],
			'Ancestors' => array(),
		);

		if ( empty($categories) ) {
			if ( isset($item['BrowseNode']['Ancestors']) ) {
				unset( $item['BrowseNode']['Ancestors'] );
			}
			return $item;
		}

		$_stat = $this->product_categories_build( $categories );
		$item['BrowseNode']['Ancestors'] = $_stat;
		return $item;
	}

	//:: images
	private function product_images_clean( $images=array() ) {

		if ( empty($images) || ! is_array($images) ) return array();

		$images_ = array();
		foreach ( $images as $key => $image ) {
			$large = isset($image['large']) ? $image['large'] : array();
			$url = isset($image['url']) ? $image['url'] : '';

			if ( empty($large) || ! is_array($large) || empty($url) ) {
				continue 1;
			}

			$width = isset($large['width']) ? $large['width'] : '';
			$height = isset($large['height']) ? $large['height'] : '';

			if ( empty($width) || empty($height) ) {
				continue 1;
			}

			$images_[] = array(
				'url' => $url,
				'width' => $width,
				'height' => $height,
			);
		}

		//$images = array_map( 'trim', $images );
		//$images = array_unique( array_filter( $images ) );
		//return $images;

		return $images_;
	}

	private function product_images_build( $images=array() ) {

		$ret = array(
			'ImageSet' => array(),
			'SmallImage' => array(),
			'LargeImage' => array(),
		);

		if ( empty($images) ) return $ret;

		// key => (height, width)
		$sizes_wh = array(
			//array( '_' => 30, 'Units' => 'pixels' )
			'SwatchImage' => array( 30, 0 ),
			'SmallImage' => array( 75, 0 ),
			'ThumbnailImage' => array( 75, 0 ),
			'TinyImage' => array( 110, 0 ),
			'MediumImage' => array( 160, 0 ),
			'LargeImage' => array( 500, 0 ),
		);

		$new = array();
		$cc = 0;
		foreach ( $images as $image ) {

			$image_link = $image['url'];
			$large_h = $image['height'];
			$large_w = $image['width'];

			$sizes_wh['LargeImage'][0] = $large_h;
			$sizes_wh['LargeImage'][1] = $large_w;

			foreach ( $sizes_wh as $image_size => $image_wh ) {
				$height = $image_wh[0];
				$width = $image_wh[1];

				if ( 'LargeImage' != $image_size ) {
					$width = ( $height * $large_w ) / $large_h;
					$width = (int) floor( $width );
				}

				$sufix = "_SL$height.";
				if ( 'LargeImage' == $image_size ) {
					$sufix = '';
				}

				$new[$cc]["$image_size"] = array(
					'URL' => $this->product_image_size_name( $image_link, $sufix ),
					'Height' => $height,
					'Width' => $width,
				);
			}

			$cc++;
		}

		$ret = array_replace_recursive( $ret, array(
			'ImageSet' => $new,
			'SmallImage' => isset($new[0]['SmallImage']) ? $new[0]['SmallImage'] : array(),
			'LargeImage' => isset($new[0]['LargeImage']) ? $new[0]['LargeImage'] : array(),
		));

		//die( var_dump( "<pre>", $ret  , "<pre>" ) . PHP_EOL .  __FILE__ . ":" . __LINE__  ); 
		return $ret;
	}

	private function product_image_size_name( $image_link, $sufix='_SL30.' ) {

		if ( '' == $sufix ) {
			return $image_link;
		}

		//https://images-na.ssl-images-amazon.com/images/I/41pQyhJ3xIL.jpg
		$regex = '~(\.)([a-zA-Z]{1,5})$~imu';
		$image_link = preg_replace( $regex, '${1}' . $sufix . '${2}', $image_link );
		//preg_match( $regex, $image_link, $m );
		//var_dump('<pre>',$image_link, $m ,'</pre>');
		return $image_link;
	}

	//:: variations
	private function product_variations_build( $product=array() ) {

		$ret = array(
			'vars' => array(),
			'vars_dim' => array(),
		);

		$vars_dim = isset($product['variations_dimensions']) ? $product['variations_dimensions'] : array();
		$vars = isset($product['variations']) ? $product['variations'] : array();

		if ( empty($vars) || empty($vars_dim) ) return $ret;

		$parent_asin = $product['ASIN'];

		$all_vars_comb = $this->product_variations_get_combinations( $vars_dim );

		//:: loop through vars
		$vars_dim_new = array();
		$vars_new = array();
		foreach ( $vars as $idx => $var_item ) {

			$var_item_ = $this->build_product_data( $var_item, array(
				'_is_variation_child' => true,
			));

			$var_asin = $var_item_['ASIN'];
			if ( empty($var_asin) ) {
				continue 1;
			}

			$var_comb = isset($all_vars_comb["$var_asin"]) ? $all_vars_comb["$var_asin"] : array();
			if ( empty($var_comb) ) {
				continue 1;
			}

			$vars_dim_new = array_merge( $vars_dim_new, array_keys( $var_comb) );

			$var_comb_ = $this->product_variations_set_combination( $var_comb );

			$var_item_['ParentASIN'] = $parent_asin;
			$var_item_['VariationAttributes'] = array(
				'VariationAttribute' => $var_comb_,
			);

			if ( isset($var_item_['Variations']) ) {
				unset( $var_item_['Variations'] );
			}
			//var_dump('<pre>',$var_item_ ,'</pre>');
			$vars_new[] = $var_item_;
		}

		$vars_dim_new = array_unique( array_filter( $vars_dim_new ) );


		//:: return
		$ret = array_replace_recursive( $ret, array(
			'vars' 	=> $vars_new,
			'vars_dim' => $vars_dim_new,
		));
		return $ret;
	}

	private function product_variations_get_combinations( $vars_dim=array() ) {

		$__ = array( 'dimCombinations', 'dimtoValueMap', 'dimensionDisplayText', 'dimensionList' );
		foreach ( $__ as $what ) {
			$$what = isset($vars_dim["$what"]) && is_array($vars_dim["$what"]) && ! empty($vars_dim["$what"])
				? $vars_dim["$what"] : array();
		}
		//var_dump('<pre>', compact( $__ ) , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

		$comb = array();

		// main foreach
		foreach ( $dimCombinations as $key => $asin ) {

			if ( empty($asin) ) {
				continue 1;
			}

			$key_ = trim( $key );
			$key_ = explode( ':', $key_ );
			$key_ = array_map( 'trim', $key_ );

			$_comb = array();

			// secondary foreach
			foreach ( $key_ as $kk => $vv ) {

				$__ = isset($dimensionList["$kk"]) ? trim( $dimensionList["$kk"] ) : '';
				if ( '' == $__ ) {
					continue 1;
				}

				$__2 = isset($dimtoValueMap["$__"]) ? (array) $dimtoValueMap["$__"] : array();
				if ( empty($__2) ) {
					continue 1;
				}

				$__3 = isset($__2["$vv"]) ? trim( $__2["$vv"] ) : '';
				if ( '' == $__3 ) {
					continue 1;
				}

				$__4 = isset($dimensionDisplayText["$__"]) ? trim( $dimensionDisplayText["$__"] ) : $__;

				$_comb["$__4"] = $__3;
			}
			// end secondary foreach 

			if ( count($key_) == count($_comb) ) {
				$comb["$asin"] = $_comb;
			}
		}
		// end main foreach

		//var_dump('<pre>', $comb , '</pre>'); echo __FILE__ . ":" .__LINE__;die . PHP_EOL;
		return $comb;
	}

	private function product_variations_set_combination( $var_comb=array() ) {

		$new = array();
		foreach ( $var_comb as $key => $val ) {
			$new[] = array(
				'Name' => $key,
				'Value' => $val,
			);
		}
		return $new;
	}

	//:: price
	private function product_price( $product=array(), $pms=array() ) {

		$pms = array_replace_recursive( array(
			'country' => '',
		), $pms );
		extract( $pms );

		$noprice = $this->product_price_format( '', '' );
		$ret = array(
			'ListPrice' => $noprice,
			'OfferListingPrice' => $noprice,
			'VariationSummaryPrice' => $noprice,
		);

		//:: init
		$__ = array( 'amazon_price', 'list_price' );
		$__2 = array( 'amazon_price_' => array(), 'list_price_' => array() );

		foreach ( $__ as $what ) {
			$$what = isset($product["$what"]) ? trim( $product["$what"] ) : '';

			$__2["{$what}_"] = $this->product_price_format( $$what, $country );
		}
		extract( $__2 );
		//var_dump('<pre>', compact( $__ ), $__2, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;


		//:: regular & sale price
		$price_final = $amazon_price_['Amount'];
		$price_cut = $list_price_['Amount'];

		if ( ! empty($price_final) && ! empty($price_cut) ) {
			$ret['ListPrice'] = $list_price_;
			$ret['OfferListingPrice'] = $amazon_price_;
		}
		else if ( ! empty($price_final) ) {
			$ret['ListPrice'] = $amazon_price_;
			$ret['OfferListingPrice'] = $amazon_price_;
		}
		else if ( ! empty($price_cut) ) {
			$ret['ListPrice'] = $list_price_;
			$ret['OfferListingPrice'] = $list_price_;
		}

		return $ret;
	}

	private function product_price_format( $price, $country ) {

		$ret = array(
			'Amount' => '0',
			'CurrencyCode' => '',
			'FormattedPrice' => '',
		);

		if ( '' == $price ) {
			return $ret;
		}

		$_formatted = $price;

		//:: find price & currency in string
		$regex = '~([^0-9]*)([0-9,.]*)~im';
		$find = preg_match( $regex, $price, $m );
		//var_dump('<pre>', $price, $find, $m , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
		if ( is_array($m) && ! empty($m) ) {
			if ( isset($m[0]) ) {
				$_formatted = $m[0];
			}
			if ( isset($m[1]) ) {
				$_currency = $m[1];
			}
			if ( isset($m[2]) ) {
				$_amount = $m[2];
			}
		}

		//:: formatted price
		$ret['FormattedPrice'] = $_formatted;

		//:: currency
		if ( isset($_currency) ) {
			$_currency = trim( $_currency );
			$_currency = $this->get_price_currency_code( $_currency );
			$ret['CurrencyCode'] = $_currency;
		}

		//:: amount
		if ( isset($_amount) ) {

			$_amount = $this->get_price_format_bycountry( $_amount, $country );
			$_amount = $_amount * 100;

			$_amount = number_format( $_amount, 0, '.', '' );
			//$_amount = number_format( $_amount, 2, '.', '' );

			$ret['Amount'] = $_amount;
		}

		return $ret;
	}

	//:: Offer/OfferListing/OfferListingId
	private function product_offer( $product=array(), $pms=array() ) {

		$pms = array_replace_recursive( array(
			'country' => '',
			'price' => array(),
			'_is_variable' => false,
		), $pms );
		extract( $pms );

		$ret = array(
			'Offers' => array(
				'TotalOffers' => 1,
				'TotalOfferPages' => 1,
				'Offer' => array(
					'Merchant' => array(
						'Name' => 'Amazon.' . $country,
					),
					'OfferAttributes' => array(
						'Condition' => 'New',
					),
					'OfferListing' => array(
						'OfferListingId' => 'directimport',
						'Price' => $price['OfferListingPrice'],
					),
				),
			),
		);

		if ( $_is_variable ) {
			$ret = array(
				'Offers' => array(
					'TotalOffers' => 1,
					'TotalOfferPages' => 1,
				),
			);
		}
		return $ret;
	}

	//:: VariationSummary
	private function product_variation_summary( $product=array(), $pms=array() ) {

		$pms = array_replace_recursive( array(
			'price' => array(),
		), $pms );
		extract( $pms );

		if ( ! isset($price['OfferListingPrice']) ) {
			$ret = array(
				'VariationSummary' => array(),
			);
		}
		else {
			$ret = array(
				'VariationSummary' => array(
					'LowestPrice' => $price['OfferListingPrice'],
				),
			);
		}
		return $ret;
	}


	//================================================
	//== Utils

	private function validate_accesskey( $accesskey='' ) {

		$ret = array(
			'status' 	=> 'invalid',
			'msg' 		=> __('Unknown error occured!', 'WooZone'),
		);

		if ( empty($accesskey) ) {
			$ret = array_replace_recursive($ret, array(
				'msg' => 'Request access: you are using an invalid access key',
			));
			return $ret;
		}

		$directimport_opt = get_option('WooZone_direct_import', array());
		$accesskey_db = isset($directimport_opt['api_secret']) ? $directimport_opt['api_secret'] : '';

		if ( empty($accesskey_db) ) {
			$ret = array_replace_recursive($ret, array(
				'msg' => 'Request access: no valid key found in website database',
			));
			return $ret;
		}

		if ( $accesskey != $accesskey_db ) {
			$ret = array_replace_recursive($ret, array(
				'msg' => 'Request Access: key(s) don\'t match. Re-authorize <a target="_blank" href="' . ( admin_url( 'admin.php?page=WooZone_direct_import' ) ) . '">here</a>',
			));
			return $ret;
		}

		$ret = array_replace_recursive($ret, array(
			'status' => 'valid',
			'msg' => 'request access: ok',
		));
		return $ret;
	}

	private function get_country_from_url( $url ) {
		$country = isset($this->settings['country']) ? $this->settings['country'] : '';
		if ( ! empty($url) ) {
			$country = $this->the_plugin->get_country_from_url( $url );
			if ( ! empty($country) ) {
				$country = $country;
			}
		}
		//var_dump('<pre>', $url, $country , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
		return $country;
	}

	private function get_price_format_bycountry( $amount, $country ) {

		//countries = array( 'com', 'ca', 'cn', 'de', 'in', 'it', 'es', 'fr', 'co.uk', 'co.jp', 'com.mx', 'com.br', 'com.au' );

		// price like: EUR15,20 (decimals with , and thousands with .)
		if ( in_array( $country, array('de', 'it', 'es', 'fr', 'com.br') ) ) {
			$amount = str_replace( ',', '.', str_replace('.', '', $amount) );
		}
		// price like: £15.20 (decimals with . and thousands with ,)
		else {
			$amount = str_replace(',', '', $amount);
		}
		return (float) $amount;
	}

	private function get_price_currency_code( $currency ) {
		$codes = array(
			'$' => 'USD',
		);

		if ( isset($codes["$currency"]) ) {
			return $codes["$currency"];
		}
		return $currency;
	}

} }
 
// Initialize the WooZoneDirectImport class
//$WooZoneDirectImport = WooZoneDirectImport::getInstance();