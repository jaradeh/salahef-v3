<?php
/**
 * Include the TGM_Plugin_Activation class.
 */
require_once EPICO_THEME_LIB . '/includes/class-tgm-plugin-activation.php';

function epico_register_required_plugins() {
    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
		//EpicoMedia Portfolio Post Type
		array(
			'name'			    => 'EpicoMedia core',
			'slug'			    => 'ep-core',
            'source'   				=>  epico_path_combine(EPICO_THEME_PLUGINS, 'ep-core.zip'), // The plugin source
			'required'			=> true,
			'force_activation'	=> false,
			'force_deactivation'=> true,
		),
        //Vitrine Shortcodes
        array(
            'name'              => 'Vitrine Shortcodes',
            'slug'              => 'ep-shortcodes',
            'source'            => epico_path_combine(EPICO_THEME_PLUGINS, 'ep-shortcodes.zip'),
            'required'          => true,
            'force_activation'  => false,
            'force_deactivation'=> true,
        ),
		// woocomerce  
        array(
            'name'      => 'WooCommerce',
            'slug'      => 'woocommerce',
            'required'  => true, 
        ),
        //Visual Composer 
        array(
            'name'			=> 'WPBakery Visual Composer', // The plugin name
            'slug'			=> 'js_composer', // The plugin slug (typically the folder name)
            'source'                => epico_path_combine(EPICO_THEME_PLUGINS, 'js_composer.zip'), // The plugin source
            'required'			=> true, // If false, the plugin is only 'recommended' instead of required
            'version'			=> '4.6', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'		=> '', // If set, overrides default API URL and points to an external URL
        ),
		//revolution Slider 
        array(
			'name'               => 'revolutionSlider WP',
			'slug'               => 'revslider',
			'source'             => epico_path_combine(EPICO_THEME_PLUGINS, 'revslider.zip'), // The plugin source
			'required'           => true,
		),
		//Contact Form 7
        array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'required' 	=> true,
        ),
		//Custom Product Tabs
		array(
            'name'      => 'Custom Product Tabs for WooCommerce',
            'slug'      => 'yikes-inc-easy-custom-woocommerce-product-tabs',
            'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'required' 	=> true,
        ),
        // pricing table 
		array(
			'name'     				=> 'vc-pricing-box',
			'slug'     				=> 'vc-pricing-box',
			'source'   				=>  epico_path_combine(EPICO_THEME_PLUGINS, 'vc-pricing-box.zip'), // The plugin source
			'required' 				=> true,
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 		=> 'http://codecanyon.net/item/pricing-boxes-for-visual-composer/8861372',
		),        
        // Wishlist
        array(
	        'name'      => 'YITH WooCommerce Wishlist',
	        'slug'      => 'yith-woocommerce-wishlist',
	        'required'  => false,
        ),
        // Compare
        array(
            'name'      => 'YITH WooCommerce Compare',
            'slug'      => 'yith-woocommerce-compare',
            'required'  => false,
            'force_activation'  => false,
            'force_deactivation'=> true,
        ),
		// MailPoet  
        array(
            'name'      => 'MailPoet Newsletters',
            'slug'      => 'wysija-newsletters',
            'required'  => false, 
        ),
        //Woozone plugin
        array(
            'name'              => 'WooZone - Amazone affiliate plugin',
            'slug'              => 'woozone',
            'source'            => epico_path_combine(EPICO_THEME_PLUGINS, 'woozone.zip'),
            'required'          => false,
            'force_activation'  => false,
            'force_deactivation'=> true,
            'external_url'      => 'https://codecanyon.net/item/woozone-amazon-associates-bundle-pack/11240475',
            'version'            => '9.0.6.4',
        ),
        //Size Guide
		array(
			'name'			    => 'Size Guide',
			'slug'			    => 'ct-size-guide',
			'source'            => epico_path_combine(EPICO_THEME_PLUGINS, 'ct-size-guide.zip'),
			'required'			=> false,
			'force_activation'	=> false,
			'force_deactivation'=> false,
            'external_url'      => 'https://codecanyon.net/item/woocommerce-product-size-guide/9168678',

		),
      //Envato toolkit plugin 
		array(
				'name'			    => 'Envato Market',
			    'slug'			    => 'envato-market',
				'required'			=> false,
				'force_activation'	=> false,
				'force_deactivation'=> false,
                'source'            => epico_path_combine(EPICO_THEME_PLUGINS, 'envato-market.zip'),
			),  
		);

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'domain'            => 'vitrine',         	// Text domain - likely want to be the same as your theme.
        'default_path'      => '',                      // Default absolute path to pre-packaged plugins
        'parent_slug'  => 'themes.php',            // Default parent menu slug
        'parent_slug'   => 'themes.php',            // Default parent URL slug
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'menu'              => 'install-required-plugins', // Menu slug
        'has_notices'       => true,                       // Show admin notices or not
        'is_automatic'      => false,                       // Automatically activate plugins after installation or not
        'message'           => '',                      // Message to output right before the plugins table
        'strings'       => array(
            'page_title'                                => esc_html__( 'Install Required Plugins',  'vitrine' ),
            'menu_title'                                => esc_html__( 'Install Plugins',  'vitrine' ),
            'installing'                                => esc_html__( 'Installing Plugin: %s',  'vitrine' ), // %1$s = plugin name
            'oops'                              => esc_html__( 'Something went wrong with the plugin API.',  'vitrine' ),
            'notice_can_install_required'       => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' , 'vitrine' ), // %1$s = plugin name(s)
            'notice_can_install_recommended'    => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ,  'vitrine' ), // %1$s = plugin name(s)
            'notice_cannot_install'             => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' , 'vitrine'), // %1$s = plugin name(s)
            'notice_can_activate_required'      => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ,  'vitrine'), // %1$s = plugin name(s)
            'notice_can_activate_recommended'   => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ,  'vitrine'), // %1$s = plugin name(s)
            'notice_cannot_activate'            => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ,  'vitrine'), // %1$s = plugin name(s)
            'notice_ask_to_update'              => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ,  'vitrine' ), // %1$s = plugin name(s)
            'notice_cannot_update'              => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ,  'vitrine'), // %1$s = plugin name(s)
            'install_link'                      => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ,  'vitrine' ),
             'activate_link'                    => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ,  'vitrine'),
            'return'                            => esc_html__( 'Return to required plugins installer',  'vitrine' ),
            'plugin_activated'                  => esc_html__( 'Plugin activated successfully.', 'vitrine' ),
            'complete'                          => esc_html__( 'All plugins installed and activated successfully. %s',  'vitrine' ), // %1$s = dashboard link
            'nag_type'                          => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
        )
    );

    tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'epico_register_required_plugins' );

// Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the  Settings-> Visual Composer page
function epico_vcSetAsTheme() {
    vc_set_as_theme();
}

add_action( 'vc_before_init', 'epico_vcSetAsTheme' );

// Initialising Shortcodes
if (class_exists('WPBakeryVisualComposerAbstract')) {
	function requireVcExtend(){
		require_once get_template_directory() . '/extendvc/extend-vc.php';
	}
	add_action('init', 'requireVcExtend',1);
}
