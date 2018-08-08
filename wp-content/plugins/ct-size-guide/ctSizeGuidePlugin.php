<?php

/**
 * Plugin Name: createIT Size Guide Plugin
 * Plugin URI: http://createit.pl
 * Description: Size guide tables for WooCommerce
 * Version: 2.7
 * Author: createIT
 * Author URI: http://createit.pl
 * WC tested up to: 3.3.4
 */
class ctSizeGuidePlugin {

	protected $active = true;

	/**
	 * Initiate object
	 */

	public function __construct() {
		add_action( 'admin_init', array( $this, 'activationWooCommerceCheck' ) );

		$this->setupConsts();
		$this->loadFiles();

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'descLinks' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'registerAdminAssets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'registerAssets' ) );
	}

	/**
	 * WooCommmerce Active?
	 * @return bool
	 */

	public static function hasWooCommerce() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Setup constants
	 */

	protected function setupConsts() {
		define( 'CT_SIZEGUIDE_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'CT_SIZEGUIDE_URI', set_url_scheme( WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) ) );
		define( 'CT_SIZEGUIDE_ASSETS', CT_SIZEGUIDE_URI . '/assets/' );
	}

	/**
	 * Check if we have WooCommerce
	 */

	public function activationWooCommerceCheck() {
		if ( ! self::hasWooCommerce() ) {
			add_action( 'admin_notices', array( $this, 'showWooCommerceNotice' ) );
		}
	}

	public function showWooCommerceNotice() {
		echo '<div class="ct-notice error">
                	<p><strong>' . __( 'createIT Size Guide Plugin', 'ct-sgp' ) . '</strong> &#8211; ' . __( 'WooCommerce Plugin must be installed and activated in order to use this plugin.', 'ct-sgp' ) . '</p>
                </div>';
	}

	/**
	 * Load files
	 */

	protected function loadFiles() {
		require_once dirname( __FILE__ ) . '/ctSizeGuideCPT.php';
		require_once dirname( __FILE__ ) . '/ctSizeGuideDisplay.php';
		require_once dirname( __FILE__ ) . '/ctSizeGuideCategories.php';
		require_once dirname( __FILE__ ) . '/ctSizeGuideTable.php';
		require_once dirname( __FILE__ ) . '/ctSizeGuideSettings.php';
        require_once dirname( __FILE__ ) . '/integrations/ctSizeGuideProductCsvImport.php';

	}

	/**
	 * Add plugin links
	 */

	public function descLinks( $links ) {

		return array_merge( array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=size_guide_tab' ) . '">' . __( 'Settings', 'ct-sgp' ) . '</a>',
			'<a href="http://createit.support/documentation/size-guide/">' . __( 'Docs', 'ct-sgp' ) . '</a>',
		), $links );

	}

	/**
	 * Add assets for admin
	 */

	public function registerAdminAssets( $hook ) {
		$screen = get_current_screen();
		if ( $screen && $screen->id == 'ct_size_guide' ) {
			wp_enqueue_style( 'jquery_edittable_css', CT_SIZEGUIDE_ASSETS . 'css/jquery.edittable.min.css' );
			wp_enqueue_script( 'jquery_edittable_js', CT_SIZEGUIDE_ASSETS . 'js/jquery.edittable.min.js', array( 'jquery' ) );
			wp_enqueue_style( 'ct_size_admin_style', CT_SIZEGUIDE_ASSETS . 'css/admin.css' );
		}
        wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'ct.sg.admin.js', CT_SIZEGUIDE_ASSETS . 'js/ct.sg.admin.js', array('jquery', 'wp-color-picker') );
		wp_enqueue_style( 'ct.sizeguide.icon.css', CT_SIZEGUIDE_ASSETS . 'css/ct.sizeguide.icon.css' );

	}

	public function registerAssets() {
		wp_enqueue_style( 'ct.sizeguide.css', CT_SIZEGUIDE_ASSETS . 'css/ct.sizeguide.css' );
		if ( get_option( 'wc_size_guide_style' ) ) {
			$sg_style = get_option( 'wc_size_guide_style' );
		} else {
			$sg_style = apply_filters( 'ct_sizeguide_styles', array(
				CT_SIZEGUIDE_ASSETS . 'css/ct.sizeguide.style1.css' => __( 'Minimalistic', 'ct-sgp' ),
				CT_SIZEGUIDE_ASSETS . 'css/ct.sizeguide.style2.css' => __( 'Classic', 'ct-sgp' ),
				CT_SIZEGUIDE_ASSETS . 'css/ct.sizeguide.style3.css' => __( 'Modern', 'ct-sgp' )
			) );
			$sg_style = key( $sg_style );

		}

		wp_enqueue_style( 'ct.sizeguide.style.css', apply_filters('ct_size_guide_option_wc_size_guide_style', $sg_style));
		wp_enqueue_style( 'magnific.popup.css', CT_SIZEGUIDE_ASSETS . 'css/magnific.popup.css' );
		wp_enqueue_script( 'magnific.popup.js', CT_SIZEGUIDE_ASSETS . 'js/magnific.popup.js', array( 'jquery' ) );
		wp_enqueue_script( 'ct.sg.front.js', CT_SIZEGUIDE_ASSETS . 'js/ct.sg.front.js', array( 'jquery' ) );
        wp_enqueue_style( 'ct.sizeguide.icon.css', CT_SIZEGUIDE_ASSETS . 'css/ct.sizeguide.icon.css' );

	}

}

new ctSizeGuidePlugin();