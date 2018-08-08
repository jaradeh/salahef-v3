<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true); 

// Deactivate WordPress importer plugin
if(class_exists('WP_Import'))
{
	deactivate_plugins(array('wordpress-importer/wordpress-importer.php'));
}

// include wordpress-importer plugin
// Try to use custom version of the plugin
if(!class_exists( 'WP_Import' ))
{
    require_once EPICO_THEME_LIB . '/includes/wordpress-importer/wordpress-importer.php';
}

if ( class_exists( 'WP_Import' ) ) {
	class Epico_Import extends WP_Import {
		/**
		 * Hook in tabs.
		 */

		//list of uploaded placeholder images
		private $media_ids = array();
		private $medias = array();

		public function __construct() {

			add_action( 'import_start', array( $this, 'post_importer_compatibility' ) );

			add_filter( 'intermediate_image_sizes_advanced', array( &$this, 'epico_disable_image_sizes' ) );

		}

		//override original dispatch function
		function dispatch() {
			//do nothing
		}

		/**
		 * Added to http_request_timeout filter to force timeout at 200 seconds during import
		 * @return int 500
		 */
		function bump_request_timeout( $val ) {
			return 500;
		}

		//this function is a modifed compy of import_start function in wordpress-importer.php of wordpress-importer plugin
		//added code define a mechanism to change EPICO_DEMO_IMAGE & EPICO_DEMO_IMAGE_ID in read XML file.
		/**
		 * Parses the WXR file and prepares us for the task of processing parsed data
		 *
		 * @param string $file Path to the WXR file for importing
		 */
		function import_start( $file ) {
			add_filter( 'http_request_timeout', array( &$this, 'bump_request_timeout' ) );

			if ( ! is_file($file) ) {
				echo '<p><strong>' . esc_html__( 'Sorry, there has been an error.', 'vitrine' ) . '</strong><br />';
				echo esc_html__( 'The file does not exist, please try again.', 'vitrine' ) . '</p>';
				$this->footer();
				die();
			}

			$import_data = $this->parse( $file );

			if ( is_wp_error( $import_data ) ) {
				echo '<p><strong>' . esc_html__( 'Sorry, there has been an error.', 'vitrine' ) . '</strong><br />';
				echo esc_html( $import_data->get_error_message() ) . '</p>';
				$this->footer();
				die();
			}
			
			set_time_limit(0);
			
			$this->version = $import_data['version'];
			$this->get_authors_from_import( $import_data );
			$this->posts = $import_data['posts'];
			$this->terms = $import_data['terms'];
			$this->categories = $import_data['categories'];
			$this->tags = $import_data['tags'];
			$this->base_url = esc_url( $import_data['base_url'] );

			wp_defer_term_counting( true );
			wp_defer_comment_counting( true );

			/*epico codes */
			$this->epico_add_placeholder_attachment();
			$processed_image_result = $this->epico_process_images($this->posts, $this->terms);
			$this->posts = $processed_image_result[0];
			$this->terms = $processed_image_result[1];

			/*End of Epico codes */

			do_action( 'import_start' );
		}

		function import_end() {

			/* Epico codes */
			remove_filter( 'intermediate_image_sizes_advanced', array( &$this, 'epico_disable_image_sizes' ) );


			wp_import_cleanup( $this->id );

			wp_cache_flush();
			foreach ( get_taxonomies() as $tax ) {
				delete_option( "{$tax}_children" );
				_get_term_hierarchy( $tax );
			}

			wp_defer_term_counting( false );
			wp_defer_comment_counting( false );

			do_action( 'import_end' );
		}

		/**
		 * Update _thumbnail_id meta to new, imported attachment IDs
		 */
		function remap_featured_images() {
			// featured images don't need updating here!
		}

		function process_menu_item( $item ) {
			// skip draft, orphaned menu items
			if ( 'draft' == $item['status'] )
				return;

			$menu_slug = false;
			if ( isset($item['terms']) ) {
				// loop through terms, assume first nav_menu term is correct menu
				foreach ( $item['terms'] as $term ) {
					if ( 'nav_menu' == $term['domain'] ) {
						$menu_slug = $term['slug'];
						break;
					}
				}
			}

			//Added to proccess menu
			// Create an array to store all the post meta in
			$menu_item_meta = array();

			/* Epico codes - modify orignal code*/
			foreach ( $item['postmeta'] as $meta ){
				${$meta['key']} = $meta['value'];
				$menu_item_meta[$meta['key']] = $meta['value'];
			} //End of adding code to proccess menu
			/* end of epico codes */

			// no nav_menu term associated with this menu item
			if ( ! $menu_slug ) {
				return;
			}

			$menu_id = term_exists( $menu_slug, 'nav_menu' );
			if ( ! $menu_id ) {
				printf( esc_html__( 'Menu item skipped due to invalid menu slug: %s', 'vitrine' ), esc_html( $menu_slug ) );
				echo '<br />';
				return;
			} else {
				$menu_id = is_array( $menu_id ) ? $menu_id['term_id'] : $menu_id;
			}
				

			if ( 'taxonomy' == $_menu_item_type && isset( $this->processed_terms[intval($_menu_item_object_id)] ) ) {
				$_menu_item_object_id = $this->processed_terms[intval($_menu_item_object_id)];
			} else if ( 'post_type' == $_menu_item_type && isset( $this->processed_posts[intval($_menu_item_object_id)] ) ) {
				$_menu_item_object_id = $this->processed_posts[intval($_menu_item_object_id)];
			} else if ( 'custom' != $_menu_item_type ) {
				// associated object is missing or not imported yet, we'll retry later
				$this->missing_menu_items[] = $item;
				return;
			}

			if ( isset( $this->processed_menu_items[intval($_menu_item_menu_item_parent)] ) ) {
				$_menu_item_menu_item_parent = $this->processed_menu_items[intval($_menu_item_menu_item_parent)];
			} else if ( $_menu_item_menu_item_parent ) {
				$this->menu_item_orphans[intval($item['post_id'])] = (int) $_menu_item_menu_item_parent;
				$_menu_item_menu_item_parent = 0;
			}

			// wp_update_nav_menu_item expects CSS classes as a space separated string
			$_menu_item_classes = maybe_unserialize( $_menu_item_classes );
			if ( is_array( $_menu_item_classes ) )
				$_menu_item_classes = implode( ' ', $_menu_item_classes );

			$args = array(
				'menu-item-object-id' => $_menu_item_object_id,
				'menu-item-object' => $_menu_item_object,
				'menu-item-parent-id' => $_menu_item_menu_item_parent,
				'menu-item-position' => intval( $item['menu_order'] ),
				'menu-item-type' => $_menu_item_type,
				'menu-item-title' => $item['post_title'],
				'menu-item-url' => $_menu_item_url,
				'menu-item-description' => $item['post_content'],
				'menu-item-attr-title' => $item['post_excerpt'],
				'menu-item-target' => $_menu_item_target,
				'menu-item-classes' => $_menu_item_classes,
				'menu-item-xfn' => $_menu_item_xfn,
				'menu-item-status' => $item['status']
			);

			$id = wp_update_nav_menu_item( $menu_id, 0, $args );
			if ( $id && ! is_wp_error( $id ) )
				$this->processed_menu_items[intval($item['post_id'])] = (int) $id;

			// Add Custom Meta not already covered by $args 
			// Remove all default $args from $menu_item_meta array
			foreach ( $args as $a => $arg ) {
				unset( $menu_item_meta[ '_' . str_replace('-', '_', $a) ]);
			}

			unset ( $menu_item_meta['_menu_item_menu_item_parent'] );

			$menu_item_meta = array_diff_assoc( $menu_item_meta, $args );

			// update any other post meta
			if ( ! empty ( $menu_item_meta ) ) foreach( $menu_item_meta as $key => $value ) {
				update_post_meta( (int) $id, $key, maybe_unserialize( $value ) );
			}

		}


		//this function is a modifed copy of post_importer_compatibility in woocommerce/includes/admin/class-wc-admin-importers.php
		//Above function failed due to lack of  $_POST['import_id'] and We should define it again to handle WC taxonomies 
		public function post_importer_compatibility() {
			global $wpdb;
			
			if(!class_exists('Woocommerce'))
				return;

			/* Epico codes */
			$demo_name = $_REQUEST['demo_name'];

			if ( empty( $demo_name ) || !class_exists( 'WXR_Parser' ) ) {
				return;
			}

			$file        = EPICO_THEME_LIB ."/admin/dummydata/".$demo_name . "/" . $demo_name .".xml";
			/* End of Epico codes */


			$parser      = new WXR_Parser();
			$import_data = $parser->parse( $file );

			if ( isset( $import_data['posts'] ) ) {
				$posts = $import_data['posts'];

				if ( $posts && sizeof( $posts ) > 0 ) {
					foreach ( $posts as $post ) {
						if ( 'product' === $post['post_type'] ) {
							if ( ! empty( $post['terms'] ) ) {
								foreach ( $post['terms'] as $term ) {
									if ( strstr( $term['domain'], 'pa_' ) ) {
										if ( ! taxonomy_exists( $term['domain'] ) ) {
											$attribute_name = wc_sanitize_taxonomy_name( str_replace( 'pa_', '', $term['domain'] ) );

											// Create the taxonomy
											if ( ! in_array( $attribute_name, wc_get_attribute_taxonomies() ) ) {
												$attribute = array(
													'attribute_label'   => $attribute_name,
													'attribute_name'    => $attribute_name,
													'attribute_type'    => 'select',
													'attribute_orderby' => 'menu_order',
													'attribute_public'  => 0,
												);
												$wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute );
												delete_transient( 'wc_attribute_taxonomies' );

											}
											
											//Epico codes
											//Due to compatiblity with envato rules(do not use register_taxonomy in theme codes!)
											//We do it through our defined action in our core plugin
											do_action( "epico_wc_register_taxonomy_before_import",  $term['domain'] );
											//End of Epico codes
	

										}
									}
								}
							}
						}
					}
				}
			}
		}

		/* Disable sizes */
		function epico_disable_image_sizes($sizes) {
	        
            return array();

		}

	    function epico_add_placeholder_attachment() {

	        define('IMGPATH', EPICO_THEME_LIB . '/admin/dummydata/img/placeholders/');
	        $directory  = EPICO_THEME_LIB_URI . '/admin/dummydata/img/placeholders/';
	        $path       = wp_upload_dir();
	        $images     = glob(IMGPATH.'{*.jpg,*.JPG,*.png}', GLOB_BRACE);

	        foreach ($images as $image) {

	            $filename     = basename($image);
	            $media_exists = get_page_by_title($filename, 'OBJECT', 'Attachment');

	            if ($media_exists == null) {
	                if(wp_mkdir_p($path['path'])) {
	                    $file = $path['path'] . '/' . $filename;
	                } else {
	                    $file = $path['basedir'] . '/' . $filename;
	                }

	                copy($image, $file);
	                $wp_filetype = wp_check_filetype($filename, null );

	                $attachment = array(
	                	'guid'			 => $path['url'] . '/' . basename( $filename ),
	                    'post_mime_type' => $wp_filetype['type'],
	                    'post_title'     => sanitize_file_name($filename),
	                    'post_content'   => '',
	                    'post_status'    => 'inherit'
	                );
	                $attach_id   = wp_insert_attachment( $attachment, $file);

	                //Update attachment metadata such as dimension
	               	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
	                wp_update_attachment_metadata( $attach_id, $attach_data );      

	                $this->media_ids[] =  $attach_id;
	                $this->medias[] = wp_get_attachment_url($attach_id );

	            } else {
	                 $this->media_ids[] = $media_exists->ID;
	                 $this->medias[] =  wp_get_attachment_url($media_exists->ID);
	            }
	        }

	    }


		public function epico_process_images($posts, $terms) {

			$new_posts = $posts;
			$new_terms = $terms;

			$post_json_format = json_encode( $new_posts );
			$term_json_format = json_encode( $new_terms );

			//choose random images/image ids to replace
			$random_id = $this->media_ids[array_rand($this->media_ids)];

			$rand_keys = array_rand($this->media_ids,3);
			$random_3id[] = $this->media_ids[$rand_keys[0]];
			$random_3id[] = $this->media_ids[$rand_keys[1]];
			$random_3id[] = $this->media_ids[$rand_keys[2]];

			$rand_keys = array_rand($this->media_ids,5);
			$random_5id[] = $this->media_ids[$rand_keys[0]];
			$random_5id[] = $this->media_ids[$rand_keys[1]];
			$random_5id[] = $this->media_ids[$rand_keys[2]];
			$random_5id[] = $this->media_ids[$rand_keys[3]];
			$random_5id[] = $this->media_ids[$rand_keys[4]];

			$random_image = $this->medias[array_rand($this->medias)];
			$random_image_json_string = strlen($random_image). ':\"' . $random_image . '\"';

			$post_json_format = str_replace('EPICO_DEMO_IMAGE_ID, EPICO_DEMO_IMAGE_ID, EPICO_DEMO_IMAGE_ID, EPICO_DEMO_IMAGE_ID, EPICO_DEMO_IMAGE_ID', implode(",", $random_5id), $post_json_format);
			$post_json_format = str_replace('EPICO_DEMO_IMAGE_ID,EPICO_DEMO_IMAGE_ID,EPICO_DEMO_IMAGE_ID', implode(",", $random_3id), $post_json_format);
			$post_json_format = str_replace('EPICO_DEMO_IMAGE_ID', $random_id, $post_json_format);
			$post_json_format = str_replace('16:\"EPICO_DEMO_IMAGE\"' ,$random_image_json_string, $post_json_format);
			$post_json_format = str_replace('EPICO_DEMO_IMAGE' ,$random_image, $post_json_format);
			$new_posts = json_decode($post_json_format ,true);



			$term_json_format = str_replace('EPICO_DEMO_IMAGE_ID', $random_id, $term_json_format);
			$new_terms = json_decode($term_json_format ,true);

			$result = array($new_posts, $new_terms);

			return $result;
		}

		function get_medias() {
			return $this->medias;
		}

		function get_media_ids() {
			return $this->media_ids;
		}
	}

} // class_exists( 'WP_Import' )

?>
