<?php

require_once('string.php');

class epico_Framework {
    /**
     * Includes (require_once) php file(s) inside selected folder
     */
    public static function Require_Files($path, $fileName)
    {

        if(is_string($fileName))
        {
            require_once(epico_path_combine($path, $fileName) . '.php');
        }
        elseif(is_array($fileName))
        {
            foreach($fileName as $name)
            {
                require_once(epico_path_combine($path, $name) . '.php');
            }
        }
        else
        {
            //Throw error
            throw new Exception('Unknown parameter type');
        }
    }
}

//Include framework files

epico_Framework::Require_Files( EPICO_THEME_LIB,
    array('constants',
          'utilities',
          'color',
          'breadcrumb',
          'scripts',
          'support',
          'retina-upload',
          'sidebars',
          'plugins-handler',
          'nav-walker',
          'menu/menu-setting',
          'menus',
          'portfolio-nav-walker',
          'shortcodes/shortcodes',
          'admin/admin',
          'demo-installer',
          'woocommerce/attribute-setting',
    ));

//Add post types

epico_Framework::Require_Files( EPICO_THEME_LIB . '/post-types',
    array('portfolio', 'blog', 'page', 'slider', 'gallery','product'
));

//Add widgets

epico_Framework::Require_Files( EPICO_THEME_LIB . '/widgets',
    array(
    'widget-flickr',
    'widget-instagram',
    'widget-video',
    'widget-progress',
    'widget-facebook',
	 'widget-woocommerce-wishlist',
   'widget-advanced-layered-nav',
   'widget-woocommerce-ranged-price-filter',
   'widget-woocommerce-on-sale-filter',
   'widget-woocommerce-in-stock-filter',
   'widget-woocommerce-layered-nav-filters',
   'widget-woocommerce-order-by-filter',
   'widget-woocommerce-rating-filter'
));

//Demo

if(file_exists(EPICO_THEME_DIR . '/demo.php'))
    include_once(EPICO_THEME_DIR . '/demo.php');
