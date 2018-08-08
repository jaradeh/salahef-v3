<?php

/**
 * assign a cateogry to the size guide post
 * @author jacek
 */
class ctSizeGuideCategories {

	/**
	 * Inits object
	 */

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'chooseProductCategories' ) );
		add_action( 'save_post_ct_size_guide', array( $this, 'saveSizeGuideCategories' ) );
	}

	/**
	 * Register metabox
	 */

	public function chooseProductCategories() {
		add_meta_box( 'ct_sizeguideopt', __( 'Choose product categories', 'ct_sgp' ), array(
			$this,
			'renderSizeGuideCategories'
		), 'ct_size_guide', 'side' );
	}

	/**
	 * renders names of the parent terms
	 *
	 * @param $term_object
	 */
	public function getTermParents( $term_object ) {
		$term_parent = $term_object->parent;
		if ( is_integer( $term_parent ) && $term_parent != '0' ) {
			_e( ' in: ', 'ct-sgp' );
			$term_id       = $term_parent;
			$term_object   = get_term( $term_id, 'product_cat' );
			$terms_array[] = $term_object->name;
			echo $term_object->name;
			$this->getTermParents( $term_object );
		}
	}

	/**
	 * Render categoriers
	 *
	 * @param $post
	 */

	public function renderSizeGuideCategories( $post ) {
		$args = array(
			'hierarchical' => 1,
			'taxonomy'     => 'product_cat',
			'orderby'     => 'name',
		);

		$post_id = $post->ID;

		$assignedcats = get_post_meta( $post_id, '_ct_assignedcats' );
		if ( ! empty( $assignedcats ) ) {
			$assignedcats = $assignedcats[0];
		}

		$sg_cat_list = get_categories( $args );
		foreach ( $sg_cat_list as $sg_cat ) {
			$checked = false;
			if ( is_array($assignedcats) && in_array( $sg_cat->term_id, $assignedcats ) ) {
				$checked = 'checked';
			}
			echo '<input type="checkbox" name="ct_sgcategory[]" value="' . $sg_cat->term_id . '" ' . $checked . ' />';
			echo $sg_cat->name;
			$this->getTermParents( $sg_cat );
			echo '</input><br/>';
		}


	}

	/**
	 * Save categories
	 *
	 * @param $post_id
	 */

	public function saveSizeGuideCategories( $post_id ) {
		if ( isset( $_POST['ct_sgcategory'] ) ) {
			$selectedsgcats = $_POST['ct_sgcategory'];

			update_post_meta( $post_id, '_ct_assignedcats', $selectedsgcats );
			foreach ( $selectedsgcats as $selectedsgcat ) {
				update_woocommerce_term_meta( $selectedsgcat, '_ct_assignsizeguide', $post_id );
			}
		}
		else{
			update_post_meta( $post_id, '_ct_assignedcats', null );
		}
	}

}

new ctSizeGuideCategories();