<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themeforest.net/user/epicomedia/portfolio
 * @since      1.0.0
 *
 * @package    EPICO_core
 * @subpackage EPICO_core/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    EPICO_core
 * @subpackage EPICO_core/public
 * @author     EpicoMedia <help.epicomedia@gmail.com>
 */
class EPICO_core_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;


        //Use shortcodes in text widgets.
        add_filter('widget_text', 'do_shortcode');

        if(!has_action("epico_wc_register_taxonomy_before_import")) {
            add_action( "epico_wc_register_taxonomy_before_import", array( $this, "register_WC_taxonomy_before_import" ) );
        }

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in EPICO_core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The EPICO_core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ep-core-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in EPICO_core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The EPICO_core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ep-core-public.js', array( 'jquery' ), $this->version, false );

	}

    //core custom Post type 
	public function create_custom_portfolio_post_type() {

        $labels = array(
            'name' => __( 'Portfolio', 'epicomedia'),
            'singular_name' => __( 'Portfolio', 'epicomedia' ),
            'add_new' => __('Add New Item', 'epicomedia'),
            'add_new_item' => __('Add New Portfolio', 'epicomedia'),
            'edit_item' => __('Edit Portfolio', 'epicomedia'),
            'new_item' => __('New Portfolio', 'epicomedia'),
            'view_item' => __('View Portfolio', 'epicomedia'),
            'search_items' => __('Search Portfolio', 'epicomedia'),
            'not_found' =>  __('No portfolios found', 'epicomedia'),
            'not_found_in_trash' => __('No portfolios found in trash', 'epicomedia'),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' =>  $labels,
            'public' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_icon' => EPICO_THEME_ASSETS_URI  . '/img/post-format-icon/portfolio-icon.png',
            'rewrite' => array('slug' => 'portfolios', 'with_front' => true),
            'supports' => array('title',
                'editor',
                'thumbnail', 
                'tags',
                'post-formats'
            ),
            "show_in_nav_menus" => false
        );
		register_post_type( 'portfolio', $args );

		/* Register the corresponding taxonomy */
        register_taxonomy('skills', 'portfolio',
            array("hierarchical" => true,
                "label" => __( "Categories", 'epicomedia' ),
                "singular_label" => __( "Category",  'epicomedia' ),
                "rewrite" => array( 'slug' => 'skills','hierarchical' => true),
                "show_in_nav_menus" => false
            ));
	}

    // gallery custom Post type 
    public function create_custom_gallery_post_type() {

        $labels = array(
            'name' => __('Gallery', 'epicomedia'),
            'singular_name' => __('Gallery', 'epicomedia' ),
            'add_new' => __('Add New Item', 'epicomedia'),
            'add_new_item' => __('Add new gallery item', 'epicomedia'),
            'edit_item' => __('Edit Gallery', 'epicomedia'),
            'new_item' => __('New Gallery', 'epicomedia'),
            'view_item' => __('View Gallery', 'epicomedia'),
            'search_items' => __('Search Gallery', 'epicomedia'),
            'not_found' =>  __('No gallery item found', 'epicomedia'),
            'not_found_in_trash' => __('No gallery item was found in trash', 'epicomedia'),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' =>  $labels,
            'public' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'menu_icon' => EPICO_THEME_ASSETS_URI  . '/img/post-format-icon/gallery-icon.png',
            'rewrite' => array('slug' => 'gallery_cat'),
            'supports' => array('title',
                                'thumbnail', 
                                'tags',
                                'post-formats'
                                ),
        );
        register_post_type( 'gallery', $args );

        /* Register the corresponding taxonomy */
        register_taxonomy('gallery_cat', 'gallery',
            array("hierarchical" => true,
                "label" => __("Categories", 'epicomedia' ),
                "singular_label" => __("Category",  'epicomedia' ),
                "rewrite" => array( 'slug' => 'gallery_cat','hierarchical' => true),
                "show_in_nav_menus" => false
        ));
    }

    // Epico Slider custom Post type 
    public function create_custom_epicoslider_post_type() {

        $labels = array(
            'name' => __( 'Slides', 'epicomedia'),
            'singular_name' => __( 'Slide', 'epicomedia' ),
            'add_new' => __('Add New Slide', 'epicomedia'),
            'add_new_item' => __('Add New Slide', 'epicomedia'),
            'edit_item' => __('Edit Slide', 'epicomedia'),
            'new_item' => __('New Slide', 'epicomedia'),
            'view_item' => __('View Slide', 'epicomedia'),
            'search_items' => __('Search Slide', 'epicomedia'),
            'not_found' =>  __('No Slides found', 'epicomedia'),
            'not_found_in_trash' => __('No Slides found in Trash', 'epicomedia'),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' =>  $labels,
            'public' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'exclude_from_search' => false,
            'menu_icon' => EPICO_THEME_ASSETS_URI  . '/img/post-format-icon/slide-icon.png',
            'rewrite' => array('slug' => 'slides', 'with_front' => true),
            'supports' => array('title',
                'editor',
                'thumbnail', 
            ),
            "show_in_nav_menus" => false
        );

        register_post_type( 'slider', $args );

        /* Register the corresponding taxonomy */

        register_taxonomy('slider_cats', 'slider',
            array("hierarchical" => true,
                "label" => __( "Categories", 'epicomedia' ),
                "singular_label" => __( "Category",  'epicomedia' ),
                "rewrite" => array( 'slug' => 'slider_cats','hierarchical' => true),
                "show_in_nav_menus" => false
            ));
    }

    public function register_WC_taxonomy_before_import($term_domain) {
        register_taxonomy(
            $term_domain,
            apply_filters( 'woocommerce_taxonomy_objects_' . $term_domain, array( 'product' ) ),
            apply_filters( 'woocommerce_taxonomy_args_' . $term_domain, array(
                'hierarchical' => true,
                'show_ui'      => false,
                'query_var'    => true,
                'rewrite'      => false,
            ) )
        );
    }

}


