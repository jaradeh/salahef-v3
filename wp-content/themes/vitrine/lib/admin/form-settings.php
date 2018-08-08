<?php

include_once EPICO_THEME_LIB . '/google-fonts.php';

function epico_admin_get_defaults()
{
    static $values = array();

    if(count($values))
        return $values;

    //Extract key-value pairs from settings
    $settings = epico_admin_get_form_settings();
    $panels   = $settings['panels'];

    foreach($panels as $panel)
    {
        foreach($panel['sections'] as $section)
        {
            foreach($section['fields'] as $fieldKey => $field)
            {
                $values[$fieldKey] = epico_array_value('value', $field);
            }
        }
    }

    return $values;
    }

    function epico_admin_get_appearance_value($name){

    $savedThemeOption =get_option('theme_scooter_options');

    return $savedThemeOption[$name];
    }

    function epico_admin_get_color_option_attr($colors)
    {
    $tmp = json_encode($colors);
    $tmp = esc_attr($tmp);
    return "data-colors=\"$tmp\"";
    }

    function epico_admin_get_form_settings()
    {
    static $settings = array();//Cache the settings

    if(count($settings))
        return $settings;

    $generalSettingsPanel = array(
        'title' => esc_html__('General Settings', 'vitrine'),
        'sections' => array(
			'page_breadcrumb' => array(
                'title'   => esc_html__('Header BreadCrumb', 'vitrine'),
                'tooltip' => esc_html__('You can enable or disable BreadCrumb to show BreadCrumb in Header of Page.', 'vitrine'),
                'fields'  => array(
                    'page_breadcrumb' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                )
            ),//Enable/disable breadcrumb on pages
            'favicon' => array(
                'title'   => esc_html__('Custom Favicon', 'vitrine'),
                'tooltip' => esc_html__('Specify custom favicon URL or upload a new one here.', 'vitrine'),
                'fields'  => array(
                    'favicon' => array(
                        'type' => 'upload',
                        'title' => esc_html__('Upload Favicon', 'vitrine'),
                        'class' =>"favicon",
                        'referer' => 'ep-settings-favicon'
                    ),
                )
            ),//Favicon sec
            'scrolling-easing' => array(
                'title'   => esc_html__('Scrolling Easing', 'vitrine'),
                'tooltip' => esc_html__('Adjust the ease and the speed of vertical scrolling for pages.', 'vitrine'),
                'fields'  => array(
                    'scrolling-easing' => array(
                        'label'  => esc_html__('Scrolling Types', 'vitrine'),
                        'type'   => 'select',
                        'options'=> array(
                                'linear' => 'linear',
                                'easeInQuad'=> 'Ease In Quad',
                                'easeOutQuad'=> 'Ease Out Quad',
                                'easeInOutQuad'=> 'Ease In Out Quad',
                                'easeInCubic' => 'Ease In Cubic',
                                'easeOutCubic' => 'Ease Out Cubic',
                                'easeInOutCubic' => 'Ease In Out Cubic',
                                'easeInQuart' => 'Ease In Quart',
                                'easeOutQuart' => 'Ease Out Quart',
                                'easeInOutQuart' => 'Ease In Out Quart',
                                'easeInQuint' => 'Ease In Quint',
                                'easeOutQuint' => 'Ease Out Quint',
                                'easeInOutQuint' => 'Ease In Out Quint',
                                'easeInSine' => 'Ease In Sine',
                                'easeOutSine' => 'Ease Out Sine',
                                'easeInOutSine' => 'Ease In Out Sine',
                                'easeInExpo' => 'Ease In Expo',
                                'easeOutExpo' => 'Ease Out Expo',
                                'easeInOutExpo' => 'Ease In Out Expo',
                                'easeInCirc' => 'Ease In Circ',
                                'easeOutCirc' => 'Ease Out Circ',
                                'easeInOutCirc' => 'Ease In Out Circ',
                                'easeInElastic' => 'Ease In Elastic',
                                'easeOutElastic' => 'Ease Out Elastic',
                                'easeInOutElastic' => 'Ease In Out Elastic',
                                'easeInBack' => 'Ease In Back',
                                'easeOutBack' => 'Ease Out Back',
                                'easeInOutBack' => 'Ease In Out Back',
                                'easeInBounce' => 'Ease In Bounce',
                                'easeOutBounce' => 'Ease Out Bounce',
                                'easeInOutBounce' => 'Ease In Out Bounce'

                            ),
                        'default'=> 'easeInOutQuart',
                    ),
                    'scrolling-speed' => array(
                        'title'  => esc_html__('Scrolling Duration', 'vitrine'),
                        'label'  => esc_html__('ms', 'vitrine'),
                        'default'   => '1000',
                        'type'   => 'range',
                        'min'   => '5',
                        'max'   => '5000',
                        'step'   => '50',
                    ),
                )
            ),//page Scrolling Speed And Easing
            'login-logo' => array(
                'title'   => esc_html__('Control Panel Login Logo', 'vitrine'),
                'tooltip' => esc_html__('Upload your Admin Panel login logo. ( best size : 302px X 62px ) ( PNG , JPG , GIF )', 'vitrine'),
                'fields'  => array(
                    'login-logo' => array(
                        'type' => 'upload',
                        'title' => esc_html__('Control Panel Login Logo', 'vitrine'),
                        'referer' => 'ep-settings-login-logo'
                    ),
                )
            ),// Control Panel Login logo
            'ajax_page_transition' => array(
                'title'   => esc_html__('Ajax page transition', 'vitrine'),
                'tooltip' => esc_html__('You can enable or disable ajax in case of navigating between pages.', 'vitrine'),
                'fields'  => array(
                    'ajax_page_transition' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    ),
                )
            ),//Enable/disable ajax page transiiton
        )
    );//$generalSettingsPanel


       $presetColors = array();

    $presetColors['default'] = epico_admin_get_color_option_attr(
        array('style-accent-color'=>'#df3030',
              'style-highlight-color'=>'#df3030',
              'style-link-color'=>'#df3030',
              'style-link-hover-color'=>'#cbcbcb'));

    $presetColors['red'] = epico_admin_get_color_option_attr(
        array('style-accent-color'=>'#eb2130',
            'style-highlight-color'=>'#eb2130',
            'style-link-color'=>'#eb2130',
            'style-link-hover-color'=>'#333333'));

    $presetColors['orange'] = epico_admin_get_color_option_attr(
        array('style-accent-color'=>'#fe4d2c',
            'style-highlight-color'=>'#fe4d2c',
            'style-link-color'=>'#fe4d2c',
            'style-link-hover-color'=>'#333333'));

    $presetColors['pink'] = epico_admin_get_color_option_attr(
        array('style-accent-color'=>'#eb2071',
            'style-highlight-color'=>'#eb2071',
            'style-link-color'=>'#eb2071',
            'style-link-hover-color'=>'#333333'));

    $presetColors['yellow'] = epico_admin_get_color_option_attr(
        array('style-accent-color'=>'#ffdb0d',
            'style-highlight-color'=>'#ffdb0d',
            'style-link-color'=>'#ffdb0d',
            'style-link-hover-color'=>'#333333'));

    $presetColors['green'] = epico_admin_get_color_option_attr(
        array('style-accent-color'=>'#96d639',
            'style-highlight-color'=>'#96d639',
            'style-link-color'=>'#96d639',
            'style-link-hover-color'=>'#333333'));

    $presetColors['emerald'] = epico_admin_get_color_option_attr(
        array('style-accent-color'=>'#4dac46',
            'style-highlight-color'=>'#4dac46',
            'style-link-color'=>'#4dac46',
            'style-link-hover-color'=>'#333333'));

    $presetColors['teal'] = epico_admin_get_color_option_attr(
        array('style-accent-color'=>'#23d692',
            'style-highlight-color'=>'#23d692',
            'style-link-color'=>'#23d692',
            'style-link-hover-color'=>'#333333'));

    $presetColors['skyBlue'] = epico_admin_get_color_option_attr(
        array('style-accent-color'=>'#45c1e5',
            'style-highlight-color'=>'#45c1e5',
            'style-link-color'=>'#45c1e5',
            'style-link-hover-color'=>'#333333'));

    $presetColors['blue'] = epico_admin_get_color_option_attr(
        array('style-accent-color'=>'#073b87',
            'style-highlight-color'=>'#073b87',
            'style-link-color'=>'#073b87',
            'style-link-hover-color'=>'#333333'));

    $presetColors['purple'] = epico_admin_get_color_option_attr(
        array('style-accent-color'=>'#423c6c',
            'style-highlight-color'=>'#423c6c',
            'style-link-color'=>'#423c6c',
            'style-link-hover-color'=>'#333333'));

    $presetColors['golden'] = epico_admin_get_color_option_attr(
        array('style-accent-color'=>'#dbbe7c',
            'style-highlight-color'=>'#dbbe7c',
            'style-link-color'=>'#dbbe7c',
            'style-link-hover-color'=>'#333333'));

    $customColor = array('style-accent-color'=>epico_admin_get_appearance_value('style-accent-color'),
        'style-highlight-color'=>epico_admin_get_appearance_value('style-highlight-color'),
        'style-link-color'=>epico_admin_get_appearance_value('style-link-color'),
        'style-link-hover-color'=>epico_admin_get_appearance_value('style-link-hover-color'));

    $presetColors['custom'] = epico_admin_get_color_option_attr( $customColor);

    $appearancePanel = array(
        'title' => esc_html__('Color Scheme', 'vitrine'),
        'sections' => array(

            'theme-style' => array(
                'title'   => esc_html__('Pre-defined Colors', 'vitrine'),
                'tooltip' => esc_html__('Choose one of our pre-defined color schemes or choose custom color.', 'vitrine'),
                'fields'  => array(
                    'style-preset-color' => array(
                        'type'   => 'select',
                        'options'=> array('default' => 'Default Theme Colors', 'red' => 'Red', 'orange' => 'Orange', 'pink' => 'Pink', 'yellow' => 'Yellow', 'green' => 'Green', 'emerald' => 'Emerald', 'teal' => 'Teal', 'skyBlue' => 'Sky Blue', 'blue' => 'Blue', 'golden' => 'Golden','custom'=>'Custom'),
                        'option-attributes' => $presetColors
                    ),
                )
            ),//theme-style sec
            'accent-color' => array(
                'title'   => esc_html__('Accent color for theme elements.', 'vitrine'),
                'tooltip' => esc_html__('Accent color for page elements', 'vitrine'),
                'fields'  => array(
                    'style-accent-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Choose', 'vitrine'),
                        'value'  => '#ff4c2f'
                    ),
                )
            ),//accent-color sec
            'highlight-color' => array(
                'title'   => esc_html__('Highlight Color', 'vitrine'),
                'tooltip' => esc_html__('Color for highlighted elements', 'vitrine'),
                'fields'  => array(
                    'style-highlight-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Choose', 'vitrine'),
                        'value'  => '#ff4c2f'
                    ),
                )
            ),//highlight-color sec
            'link-color' => array(
                'title'   => esc_html__('Link Color', 'vitrine'),
                'tooltip' => esc_html__('Choose link or on-hover mode color.', 'vitrine'),
                'fields'  => array(
                    'style-link-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Normal Color', 'vitrine'),
                        'value'  => '#ff4c2f'
                    ),
                    'style-link-hover-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('On-hover background color', 'vitrine'),
                        'value'  => '#cbcbcb'
                    ),
                )
            ),//link-color sec

        )
    );//$themeStylePanel

    $preloaderPanel = array(
        'title' => esc_html__('Preloader | Page Transitions', 'vitrine'),
        'sections' => array(
            'loader_display' => array(
                'title'   => esc_html__('Switch preloader | page transition', 'vitrine'),
                'tooltip' => esc_html__('You can switch between preloader and page transition effect. This loader would be shown before your website is loaded completely.', 'vitrine'),
                'fields'  => array(
                    'loader_display' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Preloader', 'vitrine'),
                        'state1' => esc_html__('Page Transition', 'vitrine'),
                        'value'  => 1,
                        'default' => 0,
                    ),
                )
            ),//Enable Loader
            'page-transition-type' => array(
                'title'   => esc_html__('Page transitions style', 'vitrine'),
                'tooltip' => esc_html__('Choose the style of page transition.', 'vitrine'),
                'fields'  => array(
                    'page-transition-type' => array(
                        'type' => 'visual-select',
                        'options'=> array(
                            'fade' => 'fade',
                            'fade-up' => 'fade-up',
                            'fade-up-medium' => 'fade-up-medium',
                            'fade-right' => 'fade-right',
                            'fade-right-medium' => 'fade-right-medium',
                            'fade-down' => 'fade-down',
                            'fade-down-medium' => 'fade-down-medium',
                            'fade-left' => 'fade-left',
                            'fade-left-medium' => 'fade-left-medium',
                            'scaleup' => 'scaleup',
                            'none' => 'none',
                        ),
                        'class' => 'page-transition',
                        'value' => 'fade',
                    ),
                )
            ),//page transition Style
            'preloader-type' => array(
                'title'   => esc_html__('preloader type', 'vitrine'),
                'tooltip' => esc_html__('Choose the type of preloader. Please notice that "Display just Between pages" works when "AJAX PAGE TRANSITION" is enable ', 'vitrine'),
                'fields'  => array(
                    'preloader_display' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Display always', 'vitrine'),
                        'state1' => esc_html__('Display just Between pages', 'vitrine'),
                        'value'  => 0,
                        'default' => 1,
                    ),
                    'preloader-type' => array(
                        'type' => 'visual-select',
                        'options'=> array('creative' => 'creative','simple' => 'simple', 'circular' => 'circular', 'sniper' => 'sniper'),
                        'class' => 'preloader-style',
                        'value' => 'simple',
                    ),
                )
            ),//loader type Style
            'preloader_color' => array(
                'title'   => esc_html__('Preloader color', 'vitrine'),
                'tooltip' => esc_html__('Preloader colors', 'vitrine'),
                'fields'  => array(
                    'preloader_color' => array(
                        'type'   => 'color',
                        'class' => 'preloader-color',
                        'label'  => esc_html__('Color', 'vitrine'),
                        'value'  => '#c7c7c7'
                    ),
                    'preloader_box_color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Box Color', 'vitrine'),
                        'value'  => '#f7f7f7'
                    ),
                    'preloader_bg_color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Background Color', 'vitrine'),
                        'value'  => '#efefef'
                    ),
                )
            ),//Preloaders color            
           'preloader-text' => array(
                'title'   => esc_html__('Preloader Text', 'vitrine'),
                'tooltip' => esc_html__('This text will be shown as website preloader text', 'vitrine'),
                'fields'  => array(
                    'preloader-text' => array(
                        'type' => 'text',
                        'placeholder' => esc_html__('Add preloader text here', 'vitrine'),
                    ),
                    'preloader_text_color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Color', 'vitrine'),
                        'value'  => '#000'
                    ),
                )
            ),// topbar more info buttons Text
            'preloader-logo' => array(
                'title'   => esc_html__('Preloader Image', 'vitrine'),
                'tooltip' => esc_html__('Choose an image to make it appear in preloader page. (PNG, GIF, JPG)', 'vitrine'),
                'fields'  => array(
                    'preloader-logo' => array(
                        'type' => 'upload',
                        'title' => esc_html__('Upload preloader logo', 'vitrine'),
                        'referer' => 'ep-settings-preloader'
                    ),
                )
            ),//preloader logo

        )
    );//$Pre loader Panel

    
    $topbarPanel = array(
        'title' => esc_html__('Top Bar', 'vitrine'),
        'sections' => array(
            'topbar_display' => array(
                'title'   => esc_html__('Enable top bar', 'vitrine'),
                'tooltip' => esc_html__('You can enable or disable the top bar here. Top bar is the bar that sticks to top of your page.', 'vitrine'),
                'fields'  => array(
                    'topbar_display' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 0,
                        'default' => 0,
                    ),
                )
            ),//Enable Top bar 
            'boxed_topbar' => array(
                'title'   => esc_html__('Boxed Topbar', 'vitrine'),
                'tooltip' => esc_html__('You can choose the top bar to be boxed or full-width', 'vitrine'),
                'fields'  => array(
                    'boxed_topbar' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 1,
                        'default' => 0,
                    ),
                )
            ),//Boxed tobbar
            'topbar_style' => array(
                'title'   => esc_html__('Top bar style', 'vitrine'),
                'tooltip' => esc_html__('Top bar style', 'vitrine'),
                'fields'  => array(
                    'topbar_bg_color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Background Color', 'vitrine'),
                        'value'  => '#1e1e1e'
                    ),
                    'topbar_border_color' => array(
                        'type'   => 'color',
                        'class' =>'topbar-border-color',
                        'label'  => esc_html__('Border Color', 'vitrine'),
                        'value'  => 'rgba(238,238,238,0)'
                    ),
                    'topbar_style' => array(
                        'type'   => 'switch',
                        'state1' => esc_html__('Light', 'vitrine'),
                        'state0' => esc_html__('Dark', 'vitrine'),
                        'value'  => 0,
                        'default' => 0,
                    ),
                )
            ),//topbar background color
           'topbar_icon' => array(
                'title'   => esc_html__('Top bar icon', 'vitrine'),
                'tooltip' => esc_html__('Select an icon for your topbar (notice).', 'vitrine'),
                'fields'  => array(
                    'topbar_icon' => array(
                        'type'   => 'icon',
                        'title' => esc_html__('Top bar icon', 'vitrine'),
                        'desc'  => esc_html__('Select an icon for the top bar', 'vitrine'),
                        'flags' => 'attribute',
                    ),
                )
            ),// topbar Icon 
           'topbar_title' => array(
                'title'   => esc_html__('Top bar title', 'vitrine'),
                'tooltip' => esc_html__('Insert bold title.', 'vitrine'),
                'fields'  => array(
                    'topbar_title' => array(
                        'type' => 'text',
                        'placeholder' => esc_html__('Add top bar title here', 'vitrine'),
                    ),
                    'topbar_text' => array(
                        'type' => 'text',
                        'placeholder' => esc_html__('Add top bar text here', 'vitrine'),
                    ),
                )
            ),// topbar Title 
            'topbar-wishlist-display' => array(
                'title'   => esc_html__('Top bar wishlist', 'vitrine'),
                'tooltip' => esc_html__('You can enable or disable the wishlist here.', 'vitrine'),
                'fields'  => array(
                    'topbar-wishlist-display' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 1,
                        'default' => 1,
                    ),
                )
            ),//Enable Top bar soicals icon
            'responsive-wishlist-display' => array(
                'title'   => esc_html__('Show wishlist in responsive', 'vitrine'),
                'tooltip' => esc_html__('You can enable or disable the wishlist in responsive mode.', 'vitrine'),
                'fields'  => array(
                    'responsive-wishlist-display' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 1,
                        'default' => 0,
                    ),
                )
            ),//Enable Top bar soicals icon
           'topbar-language-link' => array(
                'title'   => esc_html__('Language selection shortcode', 'vitrine'),
                'tooltip' => esc_html__('This section lets you to add links to other languages of your website. Language name has to be 5 letters max.', 'vitrine'),
                'fields'  => array(
                    'topbar-language-1' => array(
                        'type' => 'text',
                        'placeholder' => esc_html__('Language', 'vitrine'),
                        'label'  => esc_html__('1st Language', 'vitrine'),
                    ),
                    'topbar-language-link-1' => array(
                        'type' => 'text',
                        'placeholder' => esc_html__('Link', 'vitrine'),
                        'label'  => esc_html__('1st Language URL', 'vitrine'),
                    ),
                    'topbar-language-2' => array(
                        'type' => 'text',
                        'placeholder' => esc_html__('Language', 'vitrine'),
                        'label'  => esc_html__('2nd Language', 'vitrine'),
                    ),
                    'topbar-language-link-2' => array(
                        'type' => 'text',
                        'placeholder' => esc_html__('Link', 'vitrine'),
                        'label'  => esc_html__('2nd Language URL', 'vitrine'),
                    ),
                    'topbar-language-3' => array(
                        'type' => 'text',
                        'placeholder' => esc_html__('Language', 'vitrine'),
                        'label'  => esc_html__('3rd Language', 'vitrine'),
                    ),
                    'topbar-language-link-3' => array(
                        'type' => 'text',
                        'placeholder' => esc_html__('Link', 'vitrine'),
                        'label'  => esc_html__('3rd Language URL', 'vitrine'),
                    ),
                ),
            ),//Language selection shortcode
            'topbar-social-display' => array(
                'title'   => esc_html__('Top bar social icons', 'vitrine'),
                'tooltip' => esc_html__('You can enable or disable the top bar social icon here.', 'vitrine'),
                'fields'  => array(
                    'topbar-social-display' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 1,
                        'default' => 1,
                    ),
                )
            ),//Enable Top bar soicals icon
            'shop-login-link' => array(
                'title'   => esc_html__('Login/My-account link', 'vitrine'),
                'tooltip' => esc_html__('Display login/My-account link.', 'vitrine'),
                'fields'  => array(
                    'shop-login-link' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disabled', 'vitrine'),
                        'state1' => esc_html__('Enabled', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                )
            ),//Shop login Link   
        )
    );//topbar Panel
    
    $gf = new epico_GoogleFonts(epico_path_combine(EPICO_THEME_LIB, 'googlefonts.php'));
    $fontNames = $gf->GetFontNames();

    $menuPanel = array(
        'title' => esc_html__('Header | Menu ', 'vitrine'),
        'sections' => array(
            'header-type' => array(
                'title'   => esc_html__('Header Type', 'vitrine'),
                'tooltip' => esc_html__('Select header type', 'vitrine'),
                'fields'  => array(
                    'header-type' => array(
                        'type' => 'visual-select',
                        'options' => array('type-1'=>1,'type-2'=>2,'type-3'=>3,'type-4'=>4,'type-5'=>5,'type-6'=>6,'type-9'=>9,'type-7'=>7,'type-8'=>8),
                        'class' => 'header-type',
                        'value' => 1,
                    ),
                )
            ),// Menu types
            'menu-container' => array(
                'title'   => esc_html__('Header Menu Style', 'vitrine'),
                'tooltip' => esc_html__('Select menu style', 'vitrine'),
                'fields'  => array(
                    'menu-container' => array(
                        'type'   => 'switch',
                        'state1' => esc_html__('Full Width', 'vitrine'),
                        'state0' => esc_html__('Boxed', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    ),
                )
            ),//Menu in Container Or not
            'header-style' => array(
                'title'   => esc_html__('Header Menu Style', 'vitrine'),
                'tooltip' => esc_html__('Select menu style', 'vitrine'),
                'fields'  => array(
                    'header-style' => array(
                        'type' => 'visual-select',
                        'options'=> array('fixed-menu' => 'fixed-menu','epico-menu' => 'epico-menu'),
                        //'options'=> array('fixed-menu' => 'fixed-menu', 'scroll-sticky' => 'scroll-sticky','epico-menu' => 'epico-menu'), remove Bugy scroll sticky menu styles temperory 
                        'class' => 'menu-style',
                        'value' => 'fixed-menu',
                    ),
                )
            ),//Menu Style
            'menu-hover-style' => array(
                'title'   => esc_html__('Menu Hover Style', 'vitrine'),
                'tooltip' => esc_html__('Choose menu hover style.', 'vitrine'),
                'fields'  => array(
                    'menu-hover-style' => array(
                        'type' => 'visual-select',
                        'options'=> 
                            array (
                                'hover_style4' => 3, // fade - simple 
                                'hover_style3' => 2, // Underline - simple 
                                'hover_style2' => 0, // Boxed - Added In vertex 
                                'hover_style1' => 1, // Flat  - Added In vertex 
                            ),
                        'class' => 'menu-hover-style',
                        'value' => 3,
                    ),
                )
            ),//menu Style
            'vertical-menu-social-display' => array(
                'title'   => esc_html__('Vertical menu social icons', 'vitrine'),
                'tooltip' => esc_html__('You can enable or disable the vertical menu social icons here.', 'vitrine'),
                'fields'  => array(
                    'vertical-menu-social-display' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 1,
                        'default' => 1,
                    ),
                     'vertical-menu-social-icon-style' => array(
                        'type'   => 'switch',
                        'state1' => esc_html__('Light Icon', 'vitrine'),
                        'state0' => esc_html__('Dark Icon', 'vitrine'),
                        'value'  => 1,
                        'default' => 0,
                    ),
                )
            ),//Enable Top bar soicals icon
            'logo' => array(
                'title'   => esc_html__('Initial Logo', 'vitrine'),
                'tooltip' => esc_html__('It\'s the logo that will only be shown when you load the page from the top. It will be fade-out and replaced with another Logo when you scroll down.', 'vitrine'),
                'fields'  => array(
                    'logo' => array(
                        'type' => 'upload',
                        'title' => esc_html__('Upload Logo', 'vitrine'),
                        'referer' => 'ep-settings-logo'
                    ),
                )
            ),//Logo sec
            'initial-menu-color' => array(
                'title'   => esc_html__('Initial Menu Colors', 'vitrine'),
                'tooltip' => esc_html__('Choose the color and set the opacity for initial menu.', 'vitrine'),
                'fields'  => array(
                    'initial-menu-background-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Background Color', 'vitrine'),
                        'value'  => '#ffffff'
                    ),
                    'initial-menu-text-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Text Color', 'vitrine'),
                        'value'  => '#000000'
                    ),
                    'initial-menu-text-hover-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Text Hover Color', 'vitrine'),
                        'value'  => '#000000',
                        'class'  => 'menu-hover-color'
                    ),
                    'initial-menu-text-bg-hover-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('on-hover Background Color', 'vitrine'),
                        'value'  => '#f83333',
                        'class'  => 'menu-bg-hover-color'
                    ),
                    'initial-menu-border-color' => array(
                        'type'   => 'color',
                        'class' =>'initial-border-color',
                        'label'  => esc_html__('Border Color', 'vitrine'),
                        'value'  => '#eee'
                    ),
                )
            ),//initial menu colors Sec
            'logo-second' => array(
                'title'   => esc_html__('Logo', 'vitrine'),
                'tooltip' => esc_html__('It\'s your primary menu and will be shown when you scroll down.', 'vitrine'),
                'fields'  => array(
                    'logo-second' => array(
                        'type' => 'upload',
                        'title' => esc_html__('Upload Secound Logo', 'vitrine'),
                        'referer' => 'ep-settings-logo'
                    ),
                )
            ),//Secound Logo sec
            'vertical_menu_background' => array(
                'title'   => esc_html__('Menu Background', 'vitrine'),
                'tooltip' => esc_html__('Select image that Shown In Menu Background', 'vitrine'),
                'fields'  => array(
                    'vertical_menu_background' => array(
                        'type' => 'upload',
                        'title' => esc_html__('Upload Menu Background', 'vitrine'),
                        'referer' => 'ep-settings-vertical-background'
                    ),
                )
            ),//Logo sec
            'vertical_menu_copyright' => array(
                'title'   => esc_html__('Vertical Menu Copyright', 'vitrine'),
                'tooltip' => esc_html__('Enter vertical menu copyright text.', 'vitrine'),
                'fields'  => array(
                    'vertical_menu_copyright' => array(
                        'type' => 'text',
                        'label' => esc_html__('Copyright Text', 'vitrine'),
                        'value' => '&copy; 2017 EpicoMedia | Built With The Vitrine Theme'
                    ),
                )
            ),//vertical menu copyright
            'menu-color' => array(
                'title'   => esc_html__('Menu Colors', 'vitrine'),
                'tooltip' => esc_html__('Choose the color and set the opacity for menu.', 'vitrine'),
                'fields'  => array(
                    'menu-background-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Background Color', 'vitrine'),
                        'value'  => '#ffffff'
                    ),
                    'menu-text-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Text Color', 'vitrine'),
                        'value'  => '#000000'
                    ),
                    'menu-text-hover-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Text Hover Color', 'vitrine'),
                        'value'  => '#000000',
                        'class'  => 'menu-hover-color'
                    ),
                    'menu-text-bg-hover-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('on-hover Background Color', 'vitrine'),
                        'value'  => '#e8e8e8',
                        'class'  => 'menu-bg-hover-color'
                    ),
                    'menu-border-color' => array(
                        'type'   => 'color',
                        'class' =>'border-color',
                        'label'  => esc_html__('Border Color', 'vitrine'),
                        'value'  => '#eee'
                    ),
                    'menu-opacity' => array(
                        'title'  => esc_html__('Background image Opacity', 'vitrine'),
                        'label'  => esc_html__('%', 'vitrine'),
                        'class' => 'menu-opacity',
                        'default'   => '30',
                        'type'   => 'range',
                        'min'   => '0',
                        'max'   => '100',
                        'step'   => '1',
                    ),
                )
            ),//menu colors Sec
            'submenu-hover-style' => array(
                'title'   => esc_html__('Submenu Hover Style', 'vitrine'),
                'tooltip' => esc_html__('Choose submenu hover style.', 'vitrine'),
                'fields'  => array(
                    'submenu-hover-style' => array(
                        'type' => 'visual-select',
                        'options'=> 
                            array (
                                'hover_style1' => 0, // simple
                                'hover_style2' => 1, // Underline
                            ),
                        'class' => 'submenu-hover-style',
                        'value' => 0,
                    ),
                )
            ),//submenu Style
            'submenu-color' => array(
                'title'   => esc_html__('Submenu Colors', 'vitrine'),
                'tooltip' => esc_html__('Choose the color and set the background for submenus.', 'vitrine'),
                'fields'  => array(
                    'submenu-background-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Background Color', 'vitrine'),
                        'value'  => '#fff'
                    ),
                    'submenu-text-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Text Color', 'vitrine'),
                        'value'  => '#222'
                    ),
                    'submenu-heading-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Heading Color ( Used in mega menu)', 'vitrine'),
                        'value'  => '#111'
                    ),
                )
            ),//submenu colors Sec
            'mobile_menu-color' => array(
                'title'   => esc_html__('Mobile Menu Color', 'vitrine'),
                'tooltip' => esc_html__('Choose the color and set the background for mobiles menu.', 'vitrine'),
                'fields'  => array(
                    'mobile_menu-color' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Light', 'vitrine'),
                        'state1' => esc_html__('Dark', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                )
            ),//submenu colors Sec
            'font-navigation' => array(
                'title'   => esc_html__('Menu Font', 'vitrine'),
                'tooltip' => esc_html__('Select your favorite font for the menu.', 'vitrine'),
                'fields'  => array(
                    'font-navigation-type' => array(
                        'type'   => 'select',
                        'options'=> array('default' => 'Theme default font', 'google' => 'Google fonts','custom' => 'Custom font'),
                        'value'  => 'default'
                    ),
                    'font-navigation' => array(
                        'type'   => 'select',
                        'options'=> $fontNames,
                        'value'  => 'Poppins'
                    ),
                    'custom-font-url-navigation' => array(
                        'type' => 'text',
                        'label' => esc_html__('Font URL', 'vitrine'),
                        'placeholder' => 'i.e. http://fonts.googleapis.com/css?family=Dosis'
                    ),
                    'custom-font-name-navigation' => array(
                        'type' => 'text',
                        'label' => esc_html__('Font Name', 'vitrine'),
                        'placeholder' => "'Dosis', sans-serif"
                    ),
                )
            ),//Shortcode title's Font
            'menu-search' => array(
                'title'   => esc_html__('Search Form', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable search in the header. Choose an icon style for search.', 'vitrine'),
                'fields'  => array(
                    'menu-search' => array(
                        'type'   => 'switch',
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                ),
            ),//menu Style
            'search_post_type' => array(
                'title'   => esc_html__('Search post type', 'vitrine'),
                'tooltip' => esc_html__('set up site search for posts or for products. ', 'vitrine'),
                'fields'  => array(
                    'search_post_type' => array(
                       'type'     => 'select',
						'title'    => esc_html__('Search post type', 'vitrine'), 
							'options'  => array(
							    'product' => esc_html__('Product', 'vitrine'), 
							    'post' => esc_html__('Post', 'vitrine'), 
							),
						'default' => 'product',
                    ),
                )
            ),// search for post or products 
            'shop-enable-cart' => array(
                'title'   => esc_html__('Cart Button', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable menu cart menu button', 'vitrine'),
                'fields'  => array(
                    'shop-enable-cart' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disabled', 'vitrine'),
                        'state1' => esc_html__('Enabled', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                    'shop-cart-style' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Light icon', 'vitrine'),
                        'state1' => esc_html__('Dark icon', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                    'shop-cart-bg-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Background Color', 'vitrine'),
                        'value'  => '#fdfdfd'
                    ),
                )
            ),//Cart button in Menu  and Enable disable cart bar Option 
            'ep-toggle-sidebar' => array(
                'title'   => esc_html__('Toggle Sidebar Button', 'vitrine'),
                'tooltip' => esc_html__('You can enable or disable Toggle Sidebar, Toggle Sidebar is shown as a toggled item, its button will be shown in the menu.
                    This sidebar is not available in Left/right menu', 'vitrine'),
                'fields'  => array(
                    'ep-toggle-sidebar' => array(
                        'type'   => 'switch',
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    ),
                    'ep-toggle-sidebar-style' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Light icon', 'vitrine'),
                        'state1' => esc_html__('Dark icon', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                    'toggle-sidebar-bg-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Background Color', 'vitrine'),
                        'value'  => '#1e1e1e'
                    ),
                )
            ),//epico toggle Sidebar
            'responsivelogo' => array(
                'title'   => esc_html__('Responsive Logo', 'vitrine'),
                'tooltip' => esc_html__('It\'s the logo that will only be shown in responsive mode (Mobile and tablets)', 'vitrine'),
                'fields'  => array(
                    'responsivelogo' => array(
                        'type' => 'upload',
                        'title' => esc_html__('Upload Logo', 'vitrine'),
                        'referer' => 'ep-settings-logo'
                    ),
                )
            ),//Logo sec
        )
    );//$menuPanel End    
    $fontsPanel = array(
        'title' => esc_html__('Fonts', 'vitrine'),
        'sections' => array(

            'font-body' => array(
                'title'   => esc_html__('Theme Main Font', 'vitrine'),
                'tooltip' => esc_html__('Select the font that you want to be used for most of theme elements, if you need a custom font make sure that you enter the .css file address correctly.', 'vitrine'),
                'fields'  => array(
                    'font-body-type' => array(
                        'type'   => 'select',
                        'options'=> array('default' => 'Theme default font', 'google' => 'Google fonts','custom' => 'Custom font'),
                        'value'  => 'default'
                    ),
                    'font-body' => array(
                        'type'   => 'select',
                        'options'=> $fontNames,
                        'value'  => 'Lato'
                    ),
                    'custom-font-url-body' => array(
                        'type' => 'text',
                        'label' => esc_html__('Font URL', 'vitrine'),
                        'placeholder' => 'i.e. http://fonts.googleapis.com/css?family=Dosis'
                    ),
                    'custom-font-name-body' => array(
                        'type' => 'text',
                        'label' => esc_html__('Font Name', 'vitrine'),
                        'placeholder' => "'Dosis', sans-serif"
                    ),
                )
            ),
            'font-headings' => array(
                'title'   => esc_html__('Headings Font', 'vitrine'),
                'tooltip' => esc_html__('Select a font for titles and headings, if you need a custom font make sure that you enter the .css file address correctly..', 'vitrine'),
                'fields'  => array(
                    'font-headings-type' => array(
                        'type'   => 'select',
                        'options'=> array('default' => 'Theme default font', 'google' => 'Google fonts','custom' => 'Custom font'),
                        'value'  => 'default'
                    ),
                    'font-headings' => array(
                        'type'   => 'select',
                        'options'=> $fontNames,
                        'value'  => 'Poppins'
                    ),
                    'custom-font-url-headings' => array(
                        'type' => 'text',
                        'label' => esc_html__('Font URL', 'vitrine'),
                        'placeholder' => 'i.e. http://fonts.googleapis.com/css?family=Dosis'
                    ),
                    'custom-font-name-headings' => array(
                        'type' => 'text',
                        'label' => esc_html__('Font Name', 'vitrine'),
                        'placeholder' => "'Dosis', sans-serif"
                    ),
                )
            ),
            'font-shortcode' => array(
                'title'   => esc_html__('Shortcode Title Font', 'vitrine'),
                'tooltip' => esc_html__('Select a font for shortcode title, if you need a custom font make sure that you enter the .css file address correctly.', 'vitrine'),
                'fields'  => array(
                    'font-shortcode-type' => array(
                        'type'   => 'select',
                        'options'=> array('default' => 'Theme default font', 'google' => 'Google fonts','custom' => 'Custom font'),
                        'value'  => 'default'
                    ),
                    'font-shortcode' => array(
                        'type'   => 'select',
                        'options'=> $fontNames,
                        'value'  => 'Poppins'
                    ),
                    'custom-font-url-shortcode' => array(
                        'type' => 'text',
                        'label' => esc_html__('Font URL', 'vitrine'),
                        'placeholder' => 'i.e. http://fonts.googleapis.com/css?family=Dosis'
                    ),
                    'custom-font-name-shortcode' => array(
                        'type' => 'text',
                        'label' => esc_html__('Font Name', 'vitrine'),
                        'placeholder' => "'Dosis', sans-serif"
                    ),
                )
            ),
        )

    );//$fontsPanel

    $sidebarPanel = array(
        'title' => esc_html__('Sidebar', 'vitrine'),
        'sections' => array(
            'custom-sidebar' => array(
                'title'   => esc_html__('Custom Sidebar', 'vitrine'),
                'tooltip' => esc_html__(' Select a sidebar for your pages. You can customise each sidebar widget from widget panel.', 'vitrine'),
                'fields'  => array(
                    'custom_sidebars' => array(
                        'type' => 'csv',
                        'placeholder' => esc_html__('Enter a sidebar name', 'vitrine'),
                    ),
                )
            ),//custom-sidebar sec
            'sidebar-position' => array(
                'title'   => esc_html__('Page Sidebar Position', 'vitrine'),
                'tooltip' => esc_html__('Choose the default sidebar position for those pages that have a sidebar.', 'vitrine'),
                'fields'  => array(
                    'sidebar-position' => array(
                        'type' => 'visual-select',
                        'options' => array('left-side'=>1, 'right-side'=>2),
                        'class' => 'page-sidebar',
                        'value' => 2,
                    ),
                )
            ),//sidebar-position sec            
            'blog-sidebar-position' => array(
                'title'   => esc_html__('Blog and Blog Detail sidebar position', 'vitrine'),
                'tooltip' => esc_html__('Here you can disable or enable the sidebar for your blog and blog detail.', 'vitrine'),
                'fields'  => array(
                    'blog-sidebar-position' => array(
                        'type' => 'visual-select',
                        'options' => array('no-sidebar'=>'no-sidebar', 'main-sidebar'=>'main-sidebar'),
                        'class' => 'page-sidebar',
                        'value' => 'main-sidebar' ,
                    )
                )
            ),//blog-sidebar-position sec
        )
    );//$sidebarPanel
	
	$CookieLawpanel = array(
        'title' => esc_html__('Cookie Law', 'vitrine'),
        'sections' => array(
            'cookies_info' => array(
                'title'   => esc_html__('Show cookies info', 'vitrine'),
                'tooltip' => esc_html__('Under EU privacy regulations, websites must make it clear to visitors what information about them is being stored. This specifically includes cookies. Turn on this option and user will see info box at the bottom of the page that your web-site is using cookies.', 'vitrine'),
                'fields'  => array(
                    'cookies_info' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    ),
                   
                )
            ),//Enable/disable Cookie Law
			'cookies_text_message' => array(
                'title'   => esc_html__('Cookies bar text', 'vitrine'),
                'tooltip' => esc_html__('Write some information about cookies usage that will be shown in the bar. ', 'vitrine'),
                'fields'  => array(
                    'cookies_text_message' => array(
                        'type' => 'textarea',
                        'label' => esc_html__('cookies Popup text', 'vitrine'),
                        'value' => 'We use cookies to improve your experience on our website. By browsing this website, you agree to our use of cookies'
                    ),
                )
 			 ),// cookies popup message
			 'cookies_policy_page' => array(
                'title'   => esc_html__('Cookies detail page', 'vitrine'),
                'tooltip' => esc_html__('Choose page that will contain detailed information about your Privacy Policy', 'vitrine'),
                'fields'  => array(
                    'cookies_policy_page' => array(
                        'type' => 'selectpage',
						'label' => esc_html__('cookies policy page', 'vitrine'),
                    ),
                )
            ),
        ),
    );//$Cookie Law
	
	$woocomerceSettingsPanel = array(
        'title' => esc_html__('WooCommerce', 'vitrine'),
        'sections' => array(
            'shop-column' => array(
                'title'   => esc_html__('Woocommerce Columns Number', 'vitrine'),
                'tooltip' => esc_html__('Choose the number of products in a row on WooCommerce shop page.', 'vitrine'),
                'fields'  => array(
                    'shop-column' => array(
                        'type' => 'visual-select',
                        'options' => array('five'=>5, 'four'=>4, 'three'=>3, 'two'=>2),
                        'class' => 'shop-columns',
                        'value' => 5,
                    ),
                )
            ), //shop layout
			'shop-column-responsive' => array(
                'title'   => esc_html__('Woocomerce Column Number in mobile', 'vitrine'),
                'tooltip' => esc_html__('Choose the number of products in a row on WooCommerce shop page for mobile.', 'vitrine'),
                'fields'  => array(
                    'shop-column-responsive' => array(
                        'type' => 'select',
                        'label' => esc_html__('shop columns in mobile', 'vitrine'),
                        'options' => array(
                            '1' => '1',
                            '2' => '2'
                            ),
                        'value' => '1'
                    ),
                )
            ), //shop layout in mobile
            'shop-item-per-page' => array (
                'title'   => esc_html__('Shop items per page', 'vitrine'),
                'tooltip' => esc_html__('Choose the number of products in a page on WooCommerce shop pages.', 'vitrine'),
                'fields'  => array(
                    'shop-item-per-page' => array(
                        'type' => 'select',
                        'label' => esc_html__('Items Per Page', 'vitrine'),
                        'options' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                            '11' => '11',
                            '12' => '12',
                            '13' => '13',
                            '14' => '14',
                            '15' => '15',
                            '16' => '16',
                            '17' => '17',
                            '18' => '18',
                            '19' => '19',
                            '20' => '20',
                            '21' => '21',
                            '22' => '22',
                            '23' => '23',
                            '24' => '24',
                            '25' => '25',
                            '26' => '26',
                            '27' => '27',
                            '28' => '28',
                            '29' => '29',
                            '30' => '30',
                            ),
                        'value' => '15'
                    ),
                    'ajax_shop_pagination' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable AJAX pagination', 'vitrine'),
                        'state1' => esc_html__('Enable AJAX pagination', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                )
            ),//shop columns
            'shop-layout' => array(
                'title'   => esc_html__('Layout', 'vitrine'),
                'tooltip' => esc_html__('Choose an layout for shop products in main shop page.', 'vitrine'),
                'fields'  => array(
                    'shop-layout' => array(
                        'type' => 'visual-select',
                        'options' => array('fitRows'=>'fitRows', 'masonry'=>'masonry'),
                        'class' => 'shop-layouts',
                        'value' => 'fitRows',
                    ),
                )
            ),//shop product style
            'shop-product-style' => array(
                'title'   => esc_html__('Product style', 'vitrine'),
                'tooltip' => esc_html__('Choose a style(Masonry/ fitrows) for products in main shop page. Enable or disable gutter/border between products in shop main page .', 'vitrine'),
                'fields'  => array(
                    'shop-product-style' => array(
                        'type' => 'visual-select',
                        'options' => array('buttonsOnHover'=>'buttonsOnHover', 'centered'=>'centered', 'infoOnHover'=>'infoOnHover', 'infoOnClick'=>'infoOnClick','instantShop'=>'instantShop'),
                        'class' => 'shop-styles',
                        'value' => 'classic',
                    ),
                    'product-hover-color' => array(
                        'type' => 'visual-select',
                        'options' => array(
                            "_c0392b" => "c0392b",
                            "_e74c3c" => "e74c3c",
                            "_d35400" => "d35400",
                            "_e67e22" => "e67e22",
                            "_f39c12" => "f39c12",
                            "_f1c40f" => "f1c40f",
                            "_1abc9c" => "1abc9c",
                            "_2ecc71" => "2ecc71",
                            "_3498db" => "3498db",
                            "_01558f" => "01558f",
                            "_9b59b6" => "9b59b6",
                            "_ecf0f1" => "ecf0f1",
                            "_bdc3c7" => "bdc3c7",
                            "_7f8c8d" => "7f8c8d",
                            "_95a5a6" => "95a5a6",
                            "_34495e" => "34495e",
                            "_2e2e2e" => "2e2e2e",
                            "_custom-color" => "custom"
                        ),
                        'class' => 'product_hover_preset',
                        'value' => 'c0392b',
                    ),
                    'product-hover-custom-color' => array(
                        'type'   => 'color',
                        'class' => 'product_hover_custom_preset',
                        'label'  => esc_html__('Custom hover Color', 'vitrine'),
                        'value'  => '#fff'
                    ),
                    'shop-product-gutter' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('No Gutter', 'vitrine'),
                        'state1' => esc_html__('With Gutter', 'vitrine'),
                        'value'  => 0,
                        'default' => 1
                    ),
                    'shop-product-border' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('No border', 'vitrine'),
                        'state1' => esc_html__('With Border', 'vitrine'),
                        'value'  => 0,
                        'default' => 1
                    ),
                    'shop-product-rating' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Hide rating', 'vitrine'),
                        'state1' => esc_html__('Show rating', 'vitrine'),
                        'value'  => 0,
                        'class'  => 'product_rating',
                        'default' => 0
                    ),
                    'shop-entrance-animation' => array(
                        'type' => 'select',
                        'label' => esc_html__('Entrance animation', 'vitrine'), 
                        'options' => array(
                            'fadeIn' => esc_html__('FadeIn', 'vitrine'),
                            'fadeInFromBottom' => esc_html__('FadeIn From Bottom', 'vitrine'),
                            'fadeInFromTop' => esc_html__('FadeIn From Top', 'vitrine'),
                            'fadeInFromRight' => esc_html__('FadeIn From Right', 'vitrine'),
                            'fadeInFromLeft' => esc_html__('FadeIn From Left', 'vitrine'),
                            'zoomIn' => esc_html__('Zoom-in', 'vitrine'),
                            'default' => esc_html__('No animation', 'vitrine'),
                        ),
                        'default'=> 'default',
                    ),
                )
            ),//shop filter
            'shop-enable-fullwidth' => array(
                'title'   => esc_html__('Full-width shop page', 'vitrine'),
                'tooltip' => esc_html__('If enabled, the shop page will be full-width', 'vitrine'),
                'fields'  => array(
                    'shop-enable-fullwidth' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disabled', 'vitrine'),
                        'state1' => esc_html__('Enabled', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    )
                )
            ),//shop sidebar position
            'shop-sidebar-position' => array(
                'title'   => esc_html__('Shop Sidebar Position', 'vitrine'),
                'tooltip' => esc_html__('Choose the default sidebar position in WooCommerce pages.', 'vitrine'),
                'fields'  => array(
                    'shop-sidebar-position' => array(
                        'type' => 'visual-select',
                        'options' => array('none'=>0, 'left-side'=>1, 'right-side'=>2),
                        'class' => 'page-sidebar',
                        'value' => 2,
                    ),
                )
            ),// shop full width 
            'shop-filter' => array(
                'title'   => esc_html__('Shop Filter', 'vitrine'),
                'tooltip' => esc_html__('Enable filter for shop page, please pay attention that there is no need to add WooCommerce categories,product search,on sale,in stock and active filters widget on filter sidebar. Those widgets are going to be added automatically.', 'vitrine'),
                'fields'  => array(
                    'shop-filter' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disabled', 'vitrine'),
                        'state1' => esc_html__('Enabled', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                    'shop-filter-categories' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Hide categories', 'vitrine'),
                        'state1' => esc_html__('Show categories', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                    'shop-filter-subcategories' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Hide subcategories', 'vitrine'),
                        'state1' => esc_html__('Show subcategories', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                    'shop-filter-on-sale' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Hide on sale filter', 'vitrine'),
                        'state1' => esc_html__('Show  on sale filter', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                    'shop-filter-in-stock' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Hide in stock filter', 'vitrine'),
                        'state1' => esc_html__('Show in stock filter', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                    'shop-filter-active-filters' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Hide active filters', 'vitrine'),
                        'state1' => esc_html__('Show active filters', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                    'shop-filter-search' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Hide search', 'vitrine'),
                        'state1' => esc_html__('Show search', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    )
                )
            ),//Shop sidebar position sec
            'shop-ordering' => array(
                'title'   => esc_html__('Ordering', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable default ordering', 'vitrine'),
                'fields'  => array(
                    'shop-ordering' => array(
                        'type'   => 'switch',
                        'class' => 'shop-ordering-field',
                        'state0' => esc_html__('Disabled', 'vitrine'),
                        'state1' => esc_html__('Enabled', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    )
                )
            ),//Catalog Mode
            'catalog_mode' => array(
            'title'   => esc_html__('Catalog Mode', 'vitrine'),
            'tooltip' => esc_html__('Enable/disable Catalog mode of your shop(Hide add to cart, disable "cart" and "Checkout" pages. also, wishlist and Compare buttons are shown in "In separate rows" mode.)', 'vitrine'),
            'fields'  => array(
                    'catalog_mode' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disabled', 'vitrine'),
                        'state1' => esc_html__('Enabled', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    ),
					'catalog_mode_price' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Show Price', 'vitrine'),
                        'state1' => esc_html__('Hide Price', 'vitrine'),
                        'value'  => 1,
                    ),
                )
					
            ),//Enable or Disable default ordering (if topbar filter is disabled)
            'shop-enable-quickview' => array(
                'title'   => esc_html__('Quick view', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable quick view', 'vitrine'),
                'fields'  => array(
                    'shop-enable-quickview' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disabled', 'vitrine'),
                        'state1' => esc_html__('Enabled', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    )
                )
            ),//Enable or Disable Quickview
			'woocommerce-notices' => array(
                'title'   => esc_html__('Show/Hide Add-to-cart notices(in ajax add-to-cart)', 'vitrine'),
                'tooltip' => esc_html__('If you Hide this and ajax-add-to-cart was enabled in woocomerce, add-to-cart notices would be hide', 'vitrine'),
                'fields'  => array(
                    'woocommerce-notices' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Hide', 'vitrine'),
                        'state1' => esc_html__('Show', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    )
                )
            ),//percentage_sale
            'percentage_sale'=> array(
				'title' => esc_html__('Percentage sale badge', 'vitrine'),
				'tooltip' => esc_html__('Enable/ Disable Percentage sale badge on products', 'vitrine'),
				'fields'  => array(
                    'percentage_sale' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disabled', 'vitrine'),
                        'state1' => esc_html__('Enabled', 'vitrine'),
                        'value'  => 0,
                    ),
                )
            ),
			'product-hover-image' => array(
                'title'   => esc_html__('Show/Hide hover image of product', 'vitrine'),
                'tooltip' => esc_html__('If you enable this, The first image of gallery will be shown as hover of each product', 'vitrine'),
                'fields'  => array(
                    'product-hover-image' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Hide', 'vitrine'),
                        'state1' => esc_html__('Show', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    )
                )
            ),
        )
    );//woocomerce Settings Panel
	$productDetailSettingsPanel = array(
        'title' => esc_html__('Product Detail', 'vitrine'),
        'sections' => array(
            'product-detail-style' => array(
                'title'   => esc_html__('Product detail style', 'vitrine'),
                'tooltip' => esc_html__('Choose product detail style', 'vitrine'),
                'fields'  => array(
                    'product-detail-style' => array(
                        'type' => 'visual-select',
                        'options' => array('pd_classic'=>'pd_classic','pd_ep_classic'=>'pd_ep_classic','pd_background'=>'pd_background', 'pd_top'=>'pd_top', 'pd_classic_sidebar'=>'pd_classic_sidebar'),
                        'class' => 'product-detail',
                        'value' => 'pd_classic',
                    ),
                )
            ),//Product detail bg color
            'product-detail-sidebar' => array(
                'title'   => esc_html__('Product detail sidebar position', 'vitrine'),
                'tooltip' => esc_html__('Choose product detail sidebar position', 'vitrine'),
                'fields'  => array(
                    'product-detail-sidebar-position' => array(
                        'type' => 'visual-select',
                        'options' => array('left'=>'left','right'=>'right'),
                        'class' => 'product-detail-sidebar',
                        'value' => 'right',
                    ),
                    'product-detail-sidebar-responsive' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Hide sidebar in responsive', 'vitrine'),
                        'state1' => esc_html__('show sidebar in responsive', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    ),
                )
            ),//Product detail bg color
            'product-detail-bg' => array(
                'title'   => esc_html__('Product detail background-color', 'vitrine'),
                'tooltip' => esc_html__('Choose a color for background of product detail page and thumbnails', 'vitrine'),
                'fields'  => array(
                    'product-detail-bg' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Background Color', 'vitrine'),
                        'value'  => '#fff'
                    ),
                )
            ), //Variable Title
			'variable_title' => array(
                'title'   => esc_html__('Variable Title in Product details', 'vitrine'),
                'tooltip' => esc_html__('Enable/Disable this option to show Variable Title in Product details', 'vitrine'),
                'fields'  => array(
                    'variable_title' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    )
                )
            ),'product_navigation' => array(
                'title'   => esc_html__('product navigation in Product details', 'vitrine'),
                'tooltip' => esc_html__('Enable/Disable this option to next/pervious products in Product details', 'vitrine'),
                'fields'  => array(
                    'product_navigation' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    )
                )
            ),
			'product_breadcrumb' => array(
                'title'   => esc_html__('product breadcrumb in Product details', 'vitrine'),
                'tooltip' => esc_html__('Enable/Disable this option to show breadcrunb in Product details', 'vitrine'),
                'fields'  => array(
                    'product_breadcrumb' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    )
                )
            ),//product gallery
            'product_wishlist_compare_style' => array(
                'title'   => esc_html__('product detail wishlist,compare buttons', 'vitrine'),
                'tooltip' => esc_html__('style of wshlist and comapre buttons', 'vitrine'),
                'fields'  => array(
                    'product_wishlist_compare_style' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('In a row', 'vitrine'),
                        'state1' => esc_html__('In separate rows', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    )
                )
            ),//Product detail - Compare & wishlist buttons
            'product_gallery_popup' => array(
                'title'   => esc_html__('product gallery popup', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable product gallery popup on product detail pages', 'vitrine'),
                'fields'  => array(
                    'product_gallery_popup' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disabled', 'vitrine'),
                        'state1' => esc_html__('Enabled', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    ),
                    'product_gallery_style' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Light button', 'vitrine'),
                        'state1' => esc_html__('Dark button', 'vitrine'),
                        'value'  => 0,
                    )
                )
            ),'product_gallery_autoplay' => array(
                'title'   => esc_html__('product gallery autoplay', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable product gallery autoplay on product detail pages', 'vitrine'),
                'fields'  => array(
                    'product_gallery_autoplay' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                )
            ),//Product Gallery Autoplay
            //Enable or Disable related products
            'related_product' => array(
                'title'   => esc_html__('Related products', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable related products on product page', 'vitrine'),
                'fields'  => array(
                    'related_product' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disabled', 'vitrine'),
                        'state1' => esc_html__('Enabled', 'vitrine'),
                        'value'  => 1,
                    ),
					'related_product_display' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Carousel mode', 'vitrine'),
                        'state1' => esc_html__('Grid mode', 'vitrine'),
                        'value'  => 1,
                    ),
					'related_product_width' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Full width', 'vitrine'),
                        'state1' => esc_html__('Container', 'vitrine'),
                        'value'  => 1,
                    )
                )
            ),
			'shop-enable-fixed-addtocart' => array(
                'title'   => esc_html__('Sticky add to Cart', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable Sticky add to cart button. It will be displayed after scrolling to bottom of page to have better accessibility', 'vitrine'),
                'fields'  => array(
                    'shop-enable-fixed-addtocart' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disabled', 'vitrine'),
                        'state1' => esc_html__('Enabled', 'vitrine'),
                        'value'  => 1,
                        'default' => 1
                    ),
                )
            ),
        )
    );//woocomerce Settings Panel
	$maintenanceSettingsPanel = array(
        'title' => esc_html__('Maintenance Settings', 'vitrine'),
        'sections' => array(
            'maintenance' => array(
                'title'   => esc_html__('Maintenance mode', 'vitrine'),
                'tooltip' => esc_html__('If enabled, non-logged users can not access the site.', 'vitrine'),
                'fields'  => array(
                    'maintenance_mode' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    ),
					'maintenance_page' => array(
						'label' => esc_html__('Maintenance page','vitrine'),
                        'type' => 'selectpage',
						'default' => 1, 
                        'maintenance' => 1, 
                    ), 
                )
            ),
        ),
    );//maintenance Settings Panel
    
    $socialSettingsPanel = array(
        'title' => esc_html__('Social', 'vitrine'),
        'sections' => array(
            'rss' => array(
                'title'   => esc_html__('RSS', 'vitrine'),
                'tooltip' => esc_html__('RSS', 'vitrine'),
                'fields'  => array(
                    'rss_url' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('On', 'vitrine'),
                        'state1' => esc_html__('Off', 'vitrine'),
                        'value'  => 0
                    ),
                    'social_rss_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('RSS Feed', 'vitrine'),
                        'value' => get_bloginfo('rss2_url'),
                    ),
               )
            ),
			'share_buttons' => array(
                'title'   => esc_html__('Social Share buttons', 'vitrine'),
                'tooltip' => esc_html__('Enable/disable Social share buttons on products/post/portfolio. This setting could be overrided in each products/post/portfolio item', 'vitrine'),
                'fields'  => array(
                    'social_share_display' => array(
                        'type'   => 'switch',
						'label' => esc_html__('Social Share Buttons', 'vitrine'),
                        'state0' => esc_html__('Off', 'vitrine'),
                        'state1' => esc_html__('On', 'vitrine'),
                        'value'  => 1
                    ),
					'social_share_facebook' => array(
                        'type'   => 'switch',
						'label' => esc_html__('Facebook', 'vitrine'),
                        'state0' => esc_html__('Off', 'vitrine'),
                        'state1' => esc_html__('On', 'vitrine'),
                        'value'  => 1
                    ),
					'social_share_google' => array(
                        'type'   => 'switch',
						'label' => esc_html__('Google+', 'vitrine'),
                        'state0' => esc_html__('Off', 'vitrine'),
                        'state1' => esc_html__('On', 'vitrine'),
                        'value'  => 1
                    ),
					'social_share_mail' => array(
                        'type'   => 'switch',
						'label' => esc_html__('Email', 'vitrine'),
                        'state0' => esc_html__('Off', 'vitrine'),
                        'state1' => esc_html__('On', 'vitrine'),
                        'value'  => 1
                    ),
					'social_share_twitter' => array(
                        'type'   => 'switch',
						'label' => esc_html__('Twitter', 'vitrine'),
                        'state0' => esc_html__('Off', 'vitrine'),
                        'state1' => esc_html__('On', 'vitrine'),
                        'value'  => 1
                    ),
					'social_share_pinterest' => array(
                        'type'   => 'switch',
						'label' => esc_html__('Pinterest', 'vitrine'),
                        'state0' => esc_html__('Off', 'vitrine'),
                        'state1' => esc_html__('On', 'vitrine'),
                        'value'  => 1
                    ),
                )
            ),
            'socials' => array(
                'title'   => esc_html__('Social Network URLs', 'vitrine'),
                'tooltip' => esc_html__('Enter your social network addresses in respective fields. You can clear fields to hide icons from the website user interface', 'vitrine'),
                'fields'  => array(
                    'social_facebook_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Facebook', 'vitrine'),
                    ),//Facebook
                    'social_twitter_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Twitter', 'vitrine'),
                    ),//twitter
                    'social_vimeo_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Vimeo', 'vitrine'),
                    ),//vimeo
                    'social_youtube_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('YouTube', 'vitrine'),
                    ),//youtube
                    'social_googleplus_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Google+', 'vitrine'),
                    ),//Google+
                    'social_dribbble_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Dribbble', 'vitrine'),
                    ),//dribbble
                    'social_tumblr_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Tumblr', 'vitrine'),
                    ),//Tumblr
                    'social_linkedin_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('LinkedIn', 'vitrine'),
                    ),//LinkedIn
                    'social_flickr_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Flickr', 'vitrine'),
                    ),//flickr
                    'social_github_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('GitHub', 'vitrine'),
                    ),//GitHub
                    'social_lastfm_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Last.fm', 'vitrine'),
                    ),//Last.fm
                    'social_paypal_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('PayPal', 'vitrine'),
                    ),//Paypal
                    'social_skype_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Skype', 'vitrine'),
                    ),//skype
                    'social_wordpress_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('WordPress', 'vitrine'),
                    ),//WordPress
                    'social_yahoo_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Yahoo', 'vitrine'),
                    ),//yahoo
                    'social_deviantart_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('DeviantART', 'vitrine'),
                    ),//DeviantArt
                    'social_steam_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Steam', 'vitrine'),
                    ),//Steam
                    'social_reddit_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Reddit', 'vitrine'),
                    ),//reddit
                    'social_stumbleupon_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('StumbleUpon', 'vitrine'),
                    ),//StumbleUpon
                    'social_pinterest_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Pinterest', 'vitrine'),
                    ),//Pinterest
                    'social_xing_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Xing', 'vitrine'),
                    ),//XING
                    'social_blogger_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Blogger', 'vitrine'),
                    ),//Blogger
                    'social_soundcloud_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('SoundCloud', 'vitrine'),
                    ),//SoundCloud
                    'social_delicious_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Delicious', 'vitrine'),
                    ),//delicious
                    'social_foursquare_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Foursquare', 'vitrine'),
                    ),//Foursquare
                    'social_instagram_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Instagram', 'vitrine'),
                    ),//Instagram
                    'social_behance_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('Behance', 'vitrine'),
                    ),//Instagram
                )
            ),//Social links 
            'customSocial1' => array(
                'title'   => esc_html__('First custom social network', 'vitrine'),
                'tooltip' => esc_html__('Enter the social network that was not listed. The best size for logo is 25x25 pixels. ', 'vitrine'),
                'fields'  => array(
                     'social_custom1_title' => array(
                        'title' => esc_html__('Title', 'vitrine'),
                        'type' => 'text',
                        'label' => esc_html__('The name of custom social network', 'vitrine'),
                    ),
                     'social_custom1_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('The URL of custom social network', 'vitrine'),
                    ),
                    'social_custom1_image' => array(
                        'type' => 'upload',
                        'label' => esc_html__('Logo Image', 'vitrine'),
                        'title' => esc_html__('Upload logo image', 'vitrine'),
                        'referer' => 'ep-settings-custom1-logo'
                    ),
                    'social_custom1_color' => array(
                        'type'  => 'color',
                        'label' => esc_html__('Accent color', 'vitrine'),
                        'value'  => '#a7a7a7'
                    ),              
                )
            ),//custom Social Links
            'customSocial2' => array(
                'title'   => esc_html__('Second custom social network', 'vitrine'),
                'tooltip' => esc_html__('Enter the social network that was not listed. The best size for logo is 25x25 pixels. ', 'vitrine'),
                'fields'  => array(
                     'social_custom2_title' => array(
                        'title' => esc_html__('Title', 'vitrine'),
                        'type' => 'text',
                        'label' => esc_html__('The name of custom social network', 'vitrine'),
                    ),
                     'social_custom2_url' => array(
                        'type' => 'text',
                        'label' => esc_html__('The URL of custom social network', 'vitrine'),
                    ),
                    'social_custom2_image' => array(
                        'type' => 'upload',
                        'label' => esc_html__('Logo Image', 'vitrine'),
                        'title' => esc_html__('Upload logo image', 'vitrine'),
                        'referer' => 'ep-settings-custom2-logo'
                    ),
                    'social_custom2_color' => array(
                        'type'  => 'color',
                        'label' => esc_html__('Accent color', 'vitrine'),
                        'value'  => '#a7a7a7'
                    ),              
                )
            ),//custom Social Links
        ),
    );

    $footerSettingsPanel = array(
        'title' => esc_html__('Footer Settings', 'vitrine'),
        'sections' => array(
            'footer_title' => array(
                'title'   => esc_html__('Footer Title And Subtitle', 'vitrine'),
                'tooltip' => esc_html__('Enter footer title and subtitle text here. ', 'vitrine'),
                'fields'  => array(
                    'footer_title' => array(
                        'type' => 'text',
                        'label' => esc_html__('Title Text', 'vitrine'),
                        'value' => ''
                    ),//footer_title sec
                     'footer_subtitle' => array(
                        'type' => 'text',
                        'label' => esc_html__('Subtitle Text', 'vitrine'),
                        'value' => ''
                    ),//footer_subtitle sec
                )
            ),//widget-areas sec
            'widget-areas' => array(
                'title'   => esc_html__('Widget Areas', 'vitrine'),
                'tooltip' => esc_html__('If you select those styles with columns a widget area will appear on the top of footer', 'vitrine'),
                'fields'  => array(
                    'footer-widget-area' => array(
                        'label' => esc_html__('Footer Widget Area', 'vitrine'),
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    ),
                    'footer_widgets' => array(
                        'type' => 'visual-select',
                        'options' => array('one'=>1, 'Six-Six'=>2, 'eight-four'=>3, 'four-eight'=>4 , 'four-four-four'=>5 , 'three-three-three-three'=>6 , 'three-three-six'=>7  , 'six-three-three'=>8 , 'three-three-two-two-two'=>9 , 'two-two-two-three-three'=>10, 'one-three-three-three-three'=>11 , 'two-two-two-two-two-two'=>12 , 'one-three-three-two-two-two'=>13 , 'six-six-three-three-three-three'=>14 , 'six-six-three-three-two-two-two'=>15),
                        'class' => 'footer-widgets',
                        'value' => 0,
                    ),
                )
            ),//product widget-areas sec
			'product_widget_area' => array(
			 	'title'   => esc_html__('Products & categories Widget Areas', 'vitrine'),
                'tooltip' => esc_html__('Footer Widget Area visibility on Product Details and categories page', 'vitrine'),
                'fields'  => array(
					'product_widget_area' => array(
                        'label' => esc_html__('Product Widget Area', 'vitrine'),
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
               		),
					'category_widget_area' => array(
                        'label' => esc_html__('Categories Widget Area', 'vitrine'),
                        'type'   => 'switch',
                        'state0' => esc_html__('Disable', 'vitrine'),
                        'state1' => esc_html__('Enable', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
               		),
			   	)
			),//widget-areas sec
			'footer-widget-banner' => array(
                'title'   => esc_html__('Widget Area Background', 'vitrine'),
                'tooltip' => esc_html__('Upload an image to be shown as Widget area background', 'vitrine'),
                'fields'  => array(
                    'footer-widget-banner' => array(
                        'type' => 'upload',
                        'label' => esc_html__('Background Image', 'vitrine'),
                        'title' => esc_html__('Upload Footer banner', 'vitrine'),
                        'referer' => 'ep-settings-footer-banner'
                    ),
                    'footer-widget-gradianet' => array(
                        'label' => esc_html__('Gradianet Overlay', 'vitrine'),
                        'type'   => 'switch',
                        'state0' => esc_html__('Disabled', 'vitrine'),
                        'state1' => esc_html__('Enabled', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    ),
                    'footer-widget-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Background Color', 'vitrine'),
                        'value'  => 'rgba(255,255,255,0)'
                    ),
                    'footer-widget-style' => array(
                        'label' => esc_html__('Footer Widget Area Style', 'vitrine'),
                        'type'   => 'switch',
                        'state0' => esc_html__('Dark', 'vitrine'),
                        'state1' => esc_html__('Light', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    ),
					'footer-widget_width' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Container', 'vitrine'),
                        'state1' => esc_html__('Full width', 'vitrine'),
                        'value'  => 0,
						'default'=>0
                    )
                )
            ),//Footer widget banner sec
            'copyright-message' => array(
                'title'   => esc_html__('Copyright', 'vitrine'),
                'tooltip' => esc_html__('Enter footer copyright text. ', 'vitrine'),
                'fields'  => array(
                    'footer-copyright' => array(
                        'type' => 'text',
                        'label' => esc_html__('Copyright Text', 'vitrine'),
                        'value' => '&copy; 2017 EpicoMedia | Built With The Vitrine Theme'
                    ),//footer_copyright sec
                )
            ),//widget-areas sec
            'footerStyle' => array(
                'title'   => esc_html__('Footer Style', 'vitrine'),
                'tooltip' => esc_html__('You can choose between Dark And Light', 'vitrine'),
                'fields'  => array(
                    'footerStyle' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Light', 'vitrine'),
                        'state1' => esc_html__('Dark', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    )
                )
            ),
            'footerFullwidth' => array(
                'title'   => esc_html__('Footer Full-width', 'vitrine'),
                'tooltip' => esc_html__('If enabled, the footer will be full-width', 'vitrine'),
                'fields'  => array(
                    'footerFullwidth' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('disable', 'vitrine'),
                        'state1' => esc_html__('enable', 'vitrine'),
                        'value'  => 0,
                        'default' => 0
                    )
                )
            ),
        ),
    );
    
    $googleMapsPanel = array(
        'title' => esc_html__('Google Maps', 'vitrine'),
        'sections' => array(
            'googleApiKey' => array(
                'title'   => esc_html__('Google API Key', 'vitrine'),
                'tooltip' => esc_html__('Add a google map API key to prevent your map from crushing. To know how, go <a style="font-size: 13px;color: #31b0b4; display: inline-block; background: none;" href="https://goo.gl/xYBcsu">here</a>', 'vitrine'),
                'fields'  => array(
                    'googleApiKey' => array(
                        'type'   => 'switch',
                        'state0' => esc_html__('Epico Media API Key', 'vitrine'),
                        'state1' => esc_html__('Custom API Key', 'vitrine'),
                        'value'  => 0
                    ),
                    'epicoApiKey' => array(
                        'type' => 'label',
                        'class' => 'epicoApiKey',
                        'title' => esc_html__('NOTICE:', 'vitrine'),
                        'desc' => esc_html__('"Epico Media API Key" option may not always show map because it is a shared API, better use "Custom API Key"', 'vitrine'),
                    ),
                    'customApiKey' => array(
                            'type' => 'text',
                            'label' => esc_html__('Google API Key', 'vitrine'),
                            'placeholder' => esc_html__('Google API key', 'vitrine'),
                    ),
                )
            ),//google map API section
        ),
    );

    $extraSettingsPanel = array(
        'title' => esc_html__('Custom Scripts', 'vitrine'),
        'sections' => array(

            'additional-js' => array(
                'title'   => esc_html__('Additional JavaScript', 'vitrine'),
                'tooltip' => esc_html__('Enter custom JavaScript code such as Google Analytics code here. Please note that you should not include &lt;script&gt; tags in your scripts.', 'vitrine'),
                'fields'  => array(
                    'additional-js' => array(
                        'type' => 'textarea'
                    ),
                )
            ),//additional-js sec
            'additional-css' => array(
                'title'   => esc_attr__('Custom CSS', 'vitrine'),
                'tooltip' => esc_attr__('Enter custom CSS code such as style overrides here. Please note that you should not include &lt;style&gt; tags in your css code.', 'vitrine'),
                'fields'  => array(
                    'additional-css' => array(
                        'type' => 'textarea'
                    ),
                )
            ),//additional-js sec
        ),
    );

    $panels = array(
        'general'    => $generalSettingsPanel,
        'preloader'       => $preloaderPanel,
        'topbar'       => $topbarPanel,
        'menu'       => $menuPanel,
        'appearance' => $appearancePanel,
        'fonts'      => $fontsPanel,
        'social'     => $socialSettingsPanel,
        'footer'     => $footerSettingsPanel,
        'woocomerce'     => $woocomerceSettingsPanel,
        'productDetail'     => $productDetailSettingsPanel,
		'Cookie' => 	$CookieLawpanel,
        'sidebar'    => $sidebarPanel,
        'extra'      => $extraSettingsPanel,
        'Maintenance'      => $maintenanceSettingsPanel,
        'maps'      => $googleMapsPanel,
    );

    $tabs = array(
        'general'           => array( 'text' => esc_html__('General Settings', 'vitrine'), 'panel' => 'general'),
        'preloader'         => array( 'text' => esc_html__('Preloader | Page transitions', 'vitrine'), 'panel' => 'preloader'),
        'topbar'      => array( 'text' => esc_html__('Top Bar', 'vitrine'), 'panel' => 'topbar'),
        'menu'              => array( 'text' => esc_html__('Header | Menu', 'vitrine'), 'panel' => 'menu'),
        'appearance'        => array( 'text' => esc_html__('Color Scheme', 'vitrine'), 'panel' => 'appearance'),
        'fonts'             => array( 'text' => esc_html__('Fonts', 'vitrine'), 'panel'  => 'fonts'),
        'footer'            => array( 'text' => esc_html__('Footer', 'vitrine'), 'panel'  => 'footer'),
        'sidebar'           => array( 'text' => esc_html__('Sidebar', 'vitrine'), 'panel' => 'sidebar'),
        'woocomerce'        => array( 'text' => esc_html__('WooCommerce', 'vitrine'), 'panel' => 'woocomerce'),
        'productDetail'        => array( 'text' => esc_html__('Product Detail', 'vitrine'), 'panel' => 'productDetail'),
		'Cookie'			=> array( 'text' => esc_html__('Cookie Law', 'vitrine'), 'panel' => 'Cookie'),
        'social'            => array( 'text' => esc_html__('Social', 'vitrine'),  'panel' => 'social'),
        'Maintenance'       => array( 'text' => esc_html__('Maintenance', 'vitrine'),  'panel' => 'Maintenance'),
        'extra'             => array( 'text' => esc_html__('Additional Scripts', 'vitrine'),  'panel' => 'extra'),
        'maps'             => array( 'text' => esc_html__('Google Maps', 'vitrine'),  'panel' => 'maps'),
    );

    $tabGroups = array(
        'theme-settings' => array( 'text' => esc_html__('Theme Settings', 'vitrine'), 'tabs' => array('general',  'appearance' , 'preloader', 'topbar' ,  'menu' , 'fonts', 'sidebar', 'woocomerce', 'productDetail' , 'Cookie' , 'footer', 'maps',  'social', 'Maintenance' /* , 'api' */  ), 'icon' => 'dashicons-admin-generic' ),
        'advanced-settings' => array( 'text' => esc_html__('Advanced Options', 'vitrine'), 'tabs' => array('extra'), 'icon' => 'dashicons-admin-settings' )
    );

    $settings = array(
        'document-url' => 'https://epicomedia.help/Documentations/Vitrine/',
        'support-url'  => 'https://epicomedia.help/',
        'tabs-title'   => esc_html__('Theme Options', 'vitrine'),
        'tab-groups'   => $tabGroups,
        'tabs'         => $tabs,
        'panels'       => $panels,
    );

    return $settings;
}