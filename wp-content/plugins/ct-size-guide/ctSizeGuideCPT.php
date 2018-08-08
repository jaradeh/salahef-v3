<?php

/**
 * Register size guide post type
 * @author jacek
 */
class ctSizeGuideCPT {

	public function __construct() {
		add_action( 'init', array( $this, 'registerTypes' ) );
		register_activation_hook( dirname(__FILE__) . '/ctSizeGuidePlugin.php', array( $this, 'examplePosts' ) );

	}

	public function registerTypes() {

		if ( ! ctSizeGuidePlugin::hasWooCommerce() ) {
			return;
		}

		$labels = array(
			'name'               => _x( 'Size guides', 'post type general name', 'ct_sgp' ),
			'singular_name'      => _x( 'Size guide', 'post type singular name', 'ct_sgp' ),
			'menu_name'          => _x( 'Size guides', 'admin menu', 'ct_sgp' ),
			'name_admin_bar'     => _x( 'Size guide', 'add new on admin bar', 'ct_sgp' ),
			'add_new'            => _x( 'Add new', 'size guide', 'ct_sgp' ),
			'add_new_item'       => __( 'Add new size guide', 'ct_sgp' ),
			'new_item'           => __( 'New size guide', 'ct_sgp' ),
			'edit_item'          => __( 'Edit size guide', 'ct_sgp' ),
			'view_item'          => __( 'View size guide', 'ct_sgp' ),
			'all_items'          => __( 'All size guides', 'ct_sgp' ),
			'search_items'       => __( 'Search size guides', 'ct_sgp' ),
			'not_found'          => __( 'No size guides found.', 'ct_sgp' ),
			'not_found_in_trash' => __( 'No size guides found in trash.', 'ct_sgp' )
		);

		$args = array(
			'hierarchical'       => false,
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
            'show_in_nav_menus'  => false,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'size-guide' ),
			'capability_type'    => 'page',
			'has_archive'        => false,
			'menu_position'      => 55,
			'supports'           => array( 'title', 'editor' )
		);

		register_post_type( 'ct_size_guide', $args );

	}

	/**
	 * Add custom post types
	 */

	public function examplePosts() {

		if ( count( get_posts( array( 'post_type' => 'ct_size_guide' ) ) ) == 0 ) {

			//sample women
			$women = array(
				'post_content' => '<h3>Measuring Tips to Assure The Best Fit</h3>
            <img class="alignright size-full wp-image-2101" style="float:right" src="' . CT_SIZEGUIDE_ASSETS . 'img/size-guide-woman.jpg" alt="silhouette-woman"/><h4>Bust</h4>
            With arms at sides, place tape measure under your arms and run it around the fullest part of the bustline and across the shoulder blades.
            <br><h4>Waist</h4>
            Find the natural crease of your waist by bending to one side. Run tape measure around your natural waistline, keeping one finger between the tape and your body for a comfortable fit.
            <br><h4>Hips</h4>
            With feet together, run tape measure around the fullest part of your hips/seat, about 7 to 8 inches below your waistline.
            <br><h4>Inseam</h4>
            For full-length pants, run tape measure along the inside of your leg, from just below the crotch to about 1 inch below the ankle.',
				'post_name'    => 'sample-women-size-guide',
				'post_title'   => 'Sample women size guide',
				'post_status'  => 'publish',
				'post_type'    => 'ct_size_guide',
			);

			$womenID = wp_insert_post( $women );

			$womenTitle   = 'Premier Designer And Contemporary Apparel Sizing';
			$womenTable   = array(
				array( 'UK Size', 'Bust', 'Waist', 'Hips' ),
				array( '4', '30', '22.75', '32.75' ),
				array( '6', '31', '23.75', '33.75' ),
				array( '8', '32', '24.75', '34.75' ),
				array( '10', '34', '26.75', '36.75' ),
				array( '12', '36', '28.75', '38.75' ),
				array( '14', '38', '30.75', '40.75' ),
				array( '16', '40', '32.75', '42.75' ),
				array( '18', '43', '35.75', '45.75' )
			);
			$womenCaption = 'All dimensions are given in inches';

			$meta_table    = array();
			$meta_table[0] = array(
				'title'   => $womenTitle,
				'table'   => $womenTable,
				'caption' => $womenCaption
			);

			update_post_meta( $womenID, '_ct_sizeguide', $meta_table );

			//sample men
			$men = array(
				'post_content' => '<h3>Measuring Tips to Assure The Best Fit</h3>
            <img class="alignright size-full wp-image-2101" style="float:right;" src="' . CT_SIZEGUIDE_ASSETS . 'img/size-guide-man.jpg" alt="silhouette-woman"/><h4>Bust</h4>
            With arms at sides, place tape measure under your arms and run it around the fullest part of the bustline and across the shoulder blades.
            <br><h4>Waist</h4>
            Find the natural crease of your waist by bending to one side. Run tape measure around your natural waistline, keeping one finger between the tape and your body for a comfortable fit.
            <br><h4>Hips</h4>
            With feet together, run tape measure around the fullest part of your hips/seat, about 7 to 8 inches below your waistline.
            <br><h4>Inseam</h4>
            For full-length pants, run tape measure along the inside of your leg, from just below the crotch to about 1 inch below the ankle.',
				'post_name'    => 'sample-men-size-guide',
				'post_title'   => 'Sample men size guide',
				'post_status'  => 'publish',
				'post_type'    => 'ct_size_guide',
			);

			$menID = wp_insert_post( $men );

			$menTitle   = 'Premier Designer And Contemporary Apparel Sizing';
			$menTable   = array(
				array( 'UK Size', 'Bust', 'Waist', 'Hips' ),
				array( '4', '30', '22.75', '32.75' ),
				array( '6', '31', '23.75', '33.75' ),
				array( '8', '32', '24.75', '34.75' ),
				array( '10', '34', '26.75', '36.75' ),
				array( '12', '36', '28.75', '38.75' ),
				array( '14', '38', '30.75', '40.75' ),
				array( '16', '40', '32.75', '42.75' ),
				array( '18', '43', '35.75', '45.75' )
			);
			$menCaption = 'All dimensions are given in inches';

			$meta_table    = array();
			$meta_table[0] = array(
				'title'   => $menTitle,
				'table'   => $menTable,
				'caption' => $menCaption
			);

			update_post_meta( $menID, '_ct_sizeguide', $meta_table );

		}

	}

}

new ctSizeGuideCPT();