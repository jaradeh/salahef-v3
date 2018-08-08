<?php

if ( !function_exists('register_sidebar') )
    return;

$theme_sidebars = array("Blog Sidebar", "Page Sidebar", "Toggle Sidebar", "WooCommerce Sidebar", "WooCommerce Filter Topbar", "WooCommerce Product sidebar");
$defaults = array(
    'name' => esc_html__('Blog Sidebar', 'vitrine'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widget-title">',
    'after_title' => '</h4>',
);

$footerWidgets = epico_opt('footer_widgets');

if($footerWidgets ==2 || $footerWidgets ==3 || $footerWidgets == 4)
{
    $footerWidgets =2;
}
else if ($footerWidgets == 5 || $footerWidgets == 7 || $footerWidgets == 8 )
{
    $footerWidgets = 3;

} else if ($footerWidgets == 6) 
{
    $footerWidgets = 4;
}
 else if ($footerWidgets == 9 || $footerWidgets == 10 || $footerWidgets == 11 ) 
{
    $footerWidgets = 5;
}
 else if ($footerWidgets == 12 || $footerWidgets == 13 || $footerWidgets == 14 ) 
{
    $footerWidgets = 6;
}
 else if ($footerWidgets == 15) 
{
    $footerWidgets = 7;
}


//Blog sidebar
register_sidebar(array_merge($defaults, array('id'=> 'main-sidebar')));

//Page sidebar
register_sidebar(array_merge($defaults, array('name'=>esc_html__('Page Sidebar', 'vitrine'), 'id' => 'page-sidebar')));

//Footer widgets
for($i=0; $i<$footerWidgets;$i++)
{
    register_sidebar(array_merge($defaults, array(
		'name'=> 'Footer Widget '.($i+1),
		'id'   => 'footer-widget-'. ($i+1) )
	));

    $theme_sidebars[] = 'Footer Widget ' . ($i+1);
}

// toggle sidebar
register_sidebar(array_merge($defaults, array('name'=>esc_html__('Toggle Sidebar', 'vitrine'), 'id'   => 'toggle-sidebar', 'description' => esc_html__('To edit the content of toggle sidebar you need to add widgets to this area.', 'vitrine'))));

//Woocommerce Sidebar 
register_sidebar(array_merge($defaults, array('name'=>esc_html__('WooCommerce Sidebar', 'vitrine'), 'id'   => 'woocommerce-sidebar')));

//Woocommerce Product Sidebar 
register_sidebar(array_merge($defaults, array('name'=>esc_html__('WooCommerce Product Sidebar', 'vitrine'), 'id'   => 'woocommerce-product-sidebar')));


//Woocommerce Filter Sidebar
if(epico_opt('shop-filter')) {
    register_sidebar(array_merge($defaults, array('name'=>esc_html__('WooCommerce Filter Topbar', 'vitrine'), 'id'   => 'woocommerce-filter-sidebar')));
}

//Custom Sidebars
if(epico_opt('custom_sidebars') != '')
{
    $sidebars = explode(',', epico_opt('custom_sidebars'));
    $i=0;

    foreach($sidebars as $bar)
    {
        if(!in_array($bar, $theme_sidebars) && !is_active_sidebar( $bar ))
        {
            register_sidebar(array_merge($defaults, array(
                'id'   => "custom-$i",
                'name' => str_replace('%666', ',', $bar),
            )));

            $theme_sidebars[] =  str_replace('%666', ',', $bar);
        }

        $i++;
    }
}