<?php
! defined( 'ABSPATH' ) and exit;

if ( !function_exists('array_replace_recursive') ) {
		function array_replace_recursive( $base, $replacements )
		{
				foreach (array_slice(func_get_args(), 1) as $replacements) {
						$bref_stack = array(&$base);
						$head_stack = array($replacements);

						do {
								end($bref_stack);

								$bref = &$bref_stack[key($bref_stack)];
								$head = array_pop($head_stack);

								unset($bref_stack[key($bref_stack)]);

								foreach (array_keys($head) as $key) {
										if (isset($key, $bref, $bref[$key], $head[$key]) && is_array($bref[$key]) && is_array($head[$key])) {
												$bref_stack[] = &$bref[$key];
												$head_stack[] = $head[$key];
										} else {
												$bref[$key] = $head[$key];
										}
								}
						} while(count($head_stack));
				}

				return $base;
		}
}

if ( !function_exists('amzStore_bulk_wp_exist_post_by_args') ) {
	function amzStore_bulk_wp_exist_post_by_args( $args ) {
		global $WooZone;
		return $WooZone->bulk_wp_exist_post_by_args( $args );
	}
}

if ( !function_exists('WooZone_product_by_asin') ) {
	function WooZone_product_by_asin( $asins=array() ) {
		global $WooZone;
		return $WooZone->product_by_asin( $asins );
	}
}

if ( !function_exists('WooZone') ) {
	function WooZone() {
		global $WooZone;
		return $WooZone;
	}
}

if ( !function_exists('WooZone_get_plugin_data') ) {
	function WooZone_get_plugin_data( $path='' ) {
		if ( empty($path) ) {
			$path = str_replace('aa-framework/', '', plugin_dir_path( (__FILE__) )) . "plugin.php";
		}
  
		$source = file_get_contents( $path );
		$tokens = token_get_all( $source );
		$data   = array();
		if( trim($tokens[1][1]) != "" ){
			$__ = explode("\n", $tokens[1][1]);
			foreach ($__ as $key => $value) {
				$___ = explode(": ", $value);
				if( count($___) == 2 ){
					$data[trim(strtolower(str_replace(" ", '_', $___[0])))] = trim($___[1]);
				}
			}               
		}
  
		// For another way to implement it:
		//      see wp-admin/includes/update.php function get_plugin_data
		//      see wp-includes/functions.php function get_file_data
		return $data;  
	}
}

if ( !function_exists('WooZone_generateRandomString') ) {
	function WooZone_generateRandomString( $length = 10 ) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}

if ( !function_exists('WooZone_asset_path') ) {
	function WooZone_asset_path( $asset_type='css', $path='', $is_wp_enqueue=false, $pms=array() ) {
		global $WooZone;
		return $WooZone->plugin_asset_get_path( $asset_type, $path, $is_wp_enqueue, $pms );
	}
}

if ( !function_exists('WooZone_asset_version') ) {
	function WooZone_asset_version( $asset_type='css', $pms=array() ) {
		global $WooZone;
		return $WooZone->plugin_asset_get_version( $asset_type, $pms );
	}
}

if ( !function_exists('WooZone_debugbar') ) {
	function WooZone_debugbar() {
		global $WooZone;
		return $WooZone->debugbar;
	}
}

if ( !function_exists('WooZone_doing_it_wrong') ) {
	function WooZone_doing_it_wrong( $function, $message, $version ) {
		global $WooZone;
		return $WooZone->doing_it_wrong( $function, $message, $version );
	}
}

if ( !function_exists('WooZone_get_template') ) {
	function WooZone_get_template( $template_name, $pms=array() ) {
		global $WooZone;
		return $WooZone->tplsystem_get_template( $template_name, $pms );
	}
}

if ( !function_exists('WooZone_get_template_html') ) {
	function WooZone_get_template_html( $template_name, $pms=array() ) {
		global $WooZone;
		return $WooZone->tplsystem_get_template_html( $template_name, $pms );
	}
}

if ( !function_exists('WooZone_locate_template') ) {
	function WooZone_locate_template( $template_name, $pms=array() ) {
		global $WooZone;
		return $WooZone->tplsystem_locate_template( $template_name, $pms );
	}
}