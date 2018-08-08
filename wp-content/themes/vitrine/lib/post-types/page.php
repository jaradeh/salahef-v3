<?php

get_template_part('post-type');

class epico_Page extends epico_PostType
{
    function __construct()
    {
        parent::__construct('page');
    }

    function epico_EnqueueScripts()
    {
        wp_enqueue_script('hoverIntent');
        wp_enqueue_script('jquery-easing');

        wp_enqueue_script('nouislider');
        wp_enqueue_style('nouislider');

        //Include wpcolorpicker + its patch to support alpha chanel
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script('colorpickerAlpha');

        wp_enqueue_style('theme-admin');
        wp_enqueue_script('theme-admin');

        wp_enqueue_script('page', EPICO_THEME_LIB_URI . '/post-types/js/page.js', array('jquery'), THEME_VERSION);

    }

    private function epico_GetSidebars()
    {
        $sidebars = array('no-sidebar' => esc_html__('No Sidebar', 'vitrine') , 'main-sidebar' => esc_html__('Blog Sidebar', 'vitrine'), 'page-sidebar' => esc_html__('Page Sidebar', 'vitrine'));

        if(epico_opt('custom_sidebars') != '')
        {
            $arr = explode(',', epico_opt('custom_sidebars'));

            foreach($arr as $bar)
                $sidebars[$bar] = str_replace('%666', ',', $bar);
        }

        $sidebars = array_unique($sidebars);
        return $sidebars;
    }

    protected function epico_GetOptions()
    {

        $args = array(
            'orderby'           => 'id', 
            'order'             => 'DESC',
            'hide_empty'        => false, 
            'fields'               => 'all',
        );
        $sliderCategories = get_terms('slider_cats', $args);
        $sliderSlugName = array();
        if ( ! empty( $sliderCategories ) && ! is_wp_error( $sliderCategories ) ){
            foreach ($sliderCategories as $sliderCat) {
                $sliderSlugName[$sliderCat->slug] = $sliderCat->name;
            }
        }

        $fields = array(
		
			'header-type-switch' => array(
                'type' => 'select',
                'label'=> esc_html__('Choose your Header Type', 'vitrine'),
                'options' => array('0'=>esc_html__('Enable Header', 'vitrine') , '1'=>esc_html__('Enable Top Space', 'vitrine'), '2'=>esc_html__('Disable Top Space', 'vitrine')),
            ),
            'header-title-bar-switch' => array(
                'type' => 'select',
                'label'=> esc_html__('Section Title', 'vitrine'),
                'options' => array('2'=>esc_html__('Show post title', 'vitrine'),'1'=>esc_html__('Show custom title', 'vitrine'), '0'=>esc_html__('Don\'t show title', 'vitrine')),
				'default' =>'2'
            ),
            'title-text' => array(
                'type' => 'text',
                'label'=> esc_html__('Title Text', 'vitrine'),
                'placeholder' => esc_html__('Override title text', 'vitrine'),

            ),
            'subtitle-text' => array(
                'type' => 'text',
                'label'=> esc_html__('Subtitle Text', 'vitrine'),
                'placeholder' => esc_html__('Subtitle text', 'vitrine'),
            ),
			
			'header-background-image'=>array(
			         'type' => 'upload',
                     'label' => esc_html__('Background Image', 'vitrine'),
                     'title' => esc_html__('Upload Background image for header', 'vitrine'),
			),
			
			'header-text-color' => array(
                        'type'   => 'color',
                        'label'  => esc_html__('Header Text Color', 'vitrine'),
                        'value'  => 'rgba(0,0,0,1)',
            ),
            'page-type-switch' => array(
                'type' => 'select',
                'label'=> esc_html__('Section Type', 'vitrine'),
                'options' => array('custom-section'=>esc_html__('Custom section', 'vitrine'),'blog-section'=>esc_html__('Blog section', 'vitrine')),
            ),
             'page-position-switch' => array(
                'type' => 'select',
                'label'=> esc_html__('Choose your section to be shown in:', 'vitrine'),
                'options' => array('1'=>esc_html__('Container page', 'vitrine') , '0'=>esc_html__('External page', 'vitrine')),
            ),
            'blog-type-switch' => array(
                'type' => 'select',
                'label'=> esc_html__('Blog Type:', 'vitrine'),
                'options' => array('1'=>esc_html__('Classic Blog', 'vitrine'), '0'=>esc_html__('Toggle Blog', 'vitrine')),
            ),
            'sidebar' => array(
                'type' => 'select',
                'label'=> esc_html__('Choose the sidebar', 'vitrine'),
                'options' => $this->epico_GetSidebars(),
            ),
            'blog-sidebar' => array(
                'type' => 'select',
                'label'=> esc_html__('Blog Sidebar Display', 'vitrine'),
                'options' => array('inherit-from-theme-settings'=> esc_html__('Inherit from theme settings', 'vitrine'),'main-sidebar'=> esc_html__('Show', 'vitrine'), 'no-sidebar'=> esc_html__('Don\'t show', 'vitrine')),
            ),
           'display-top-slider' => array(
                'type'   => 'switch',
                'label'  => esc_html__('Top Slider', 'vitrine'),
                'state0' => esc_html__('Disable', 'vitrine'),
                'state1' => esc_html__('Enable', 'vitrine'),
                'value' => 0,
                'default'   => 0
            ),
           'slider-type' => array(
                'type' => 'visual-select',
                'options' => array (
                        'epico-slider' => 'epico-slider',
                        'slider-revolutionSlider'  => 'slider-revolutionSlider' 
                ),
                'class' => 'intro_type',
                'value' => 'epico-slider',
                'label' => 'Slider type' 
            ),
           'rev-slider-container' => array(
                'type'   => 'switch',
                'label'  => esc_html__('Revolution Slider Container/Fullwidth', 'vitrine'),
                'state0' => esc_html__('Fullwidth', 'vitrine'),
                'state1' => esc_html__('Container', 'vitrine'),
                'value' => 0,
                'default'   => 0
            ),
           'slider-parallax' => array(
                'type'   => 'switch',
                'label'  => esc_html__('Slider parallax effect', 'vitrine'),
                'state0' => esc_html__('Disable', 'vitrine'),
                'state1' => esc_html__('Enable', 'vitrine'),
                'value' => 1,
                'default'   => 1
            ),
            'slide-mode' => array(
                'type' => 'visual-select',
                'options' => array (
                        'epico' => 'epico',
                        'slide' => 'slide',
                        'fade' => 'fade'
                ),
                'class' => 'slide-mode',
                'value' => 'epico',
                'label' => 'Sliding Mode' 
            ),
            'epico-slider-cat' => array(
                'type'   => 'select',
                'options' => $sliderSlugName,
                'label' => 'Category Of Slides' 
            ),
            'home-rev-slide' => array(
                'type' => 'select',
                'label'  => esc_html__('Choose a revolution slider', 'vitrine'),
                'options' => epico_get_revolutionSlider_slides()
            ),
           'slider-overlay-color' => array(
                'type'   => 'color',
                'label'  => esc_html__('Overlay Color', 'vitrine'),
                'value'  => ''
            ),
            'slider-overlay-opacity' => array(
                'title'  => esc_html__('Overlay Opacity', 'vitrine'),
                'label'  => esc_html__('%', 'vitrine'),
                'type'   => 'range',
                'default'   => '50',
                'min'   => '0',
                'max'   => '100',
                'step'   => '1',
            ),
            'slider-overlay-texture' => array(
                'type' => 'visual-select',
                'title'=> esc_html__('Choose overlay texture : ', 'vitrine'),
                'options' => array(
                            'texture1'=> esc_html__('texture1', 'vitrine'),
                            'texture2'=> esc_html__('texture2', 'vitrine'),
                            'texture3'=> esc_html__('texture3', 'vitrine'),
                            'texture4'=> esc_html__('texture4', 'vitrine'),
                            'texture5'=> esc_html__('texture5', 'vitrine'),
                            'texture6'=> esc_html__('texture6', 'vitrine'),
                            'texture7'=> esc_html__('texture7', 'vitrine'),
                            'texture8'=> esc_html__('texture8', 'vitrine'),
                    ),
            ),
            'slider-start-btn' => array(
                'type'   => 'switch',
                'label' => 'Enable or disable start button',
                'state0' => esc_html__('Disabled', 'vitrine'),
                'state1' => esc_html__('Enabled', 'vitrine'),
                'value'  => 1,
                'default' => 1,
            ),
            'slider-start-style' => array(
                'type'   => 'switch',
                'label' => 'choose a style (Dark/Light) for start button',
                'state0' => esc_html__('Light', 'vitrine'),
                'state1' => esc_html__('Dark', 'vitrine'),
                'value'  => 0,
                'default' => 0,
            ),
            'slider-start-type' => array(
                'type' => 'visual-select',
                'label' => 'choose type of start button',
                'options' => array (
                        'style-1' => 'style-1',
                        'style-2' => 'style-2'
                ),
                'class' => 'slider-start-type',
                'value' => 'style-1',
            ),
            'resume-exp-section'=> array(
                'type' => 'checkbox',
                'checked' => true,
                'value' => 'visible',
                'label' => esc_html__('Experience','vitrine'),
            ),
            'extra_class' => array(
				'type' => 'text',
				'label'=> esc_html__('Extra Class Name', 'vitrine'),
				'placeholder' => esc_html__('class name ex: class1 class2', 'vitrine'),
			),// Extra class name 
           'snap-to-scroll' => array(
                'type'   => 'switch',
                'label'  => esc_html__('Enable snap to scroll', 'vitrine'),
                'state0' => esc_html__('Disable', 'vitrine'),
                'state1' => esc_html__('Enable', 'vitrine'),
                'default'   => 0
            ),
           'snap-to-scroll-nav-style' => array(
                'type'   => 'switch',
                'label'  => esc_html__('Navigation style', 'vitrine'),
                'state0' => esc_html__('Light', 'vitrine'),
                'state1' => esc_html__('Dark', 'vitrine'),
                'default'   => 0
            ),
            'footer-widget-area' => array(
                'type'   => 'select',
                'label' => esc_html__('Footer widget area visibility', 'vitrine'),
                'options'=> array('inherit' => 'Inherit from theme setting', 'enable' => 'Enable', 'disable' => 'Disable')
            ),
           'footer-map' => array(
                'type'   => 'switch',
                'label'  => esc_html__('Enable or disable map in the footer.', 'vitrine'),
                'state0' => esc_html__('Disable', 'vitrine'),
                'state1' => esc_html__('Enable', 'vitrine'),
                'default'   => 0
            ),
            'footerStyleMap' => array(
                'type'   => 'switch',
                'label'  => esc_html__('Google map style', 'vitrine'),
                'state0' => esc_html__('Standard', 'vitrine'),
                'state1' => esc_html__('Custom', 'vitrine'),
                'default'  => 1
            ),
            'footerMapLatitude' => array(
                    'type' => 'text',
                    'label' => esc_html__('Map Latitude', 'vitrine'),
                    'placeholder' => esc_html__('Google Maps latitude', 'vitrine'),
            ),
            'footerMapLongitude' => array(
                    'type' => 'text',
                    'label' => esc_html__('Map Longitude', 'vitrine'),
                    'placeholder' => esc_html__('Google Maps longitude', 'vitrine'),
            ),
            'footerMapZoom' => array(
                'type'   => 'select',
                'label' => esc_html__('Map Zoom', 'vitrine'),
                'options'=> array('1' => 'Zoom 1', '2' => 'Zoom 2', '3' => 'Zoom 3', '4' => 'Zoom 4', '5' => 'Zoom 5', '6' => 'Zoom 6', '7' => 'Zoom 7', '8' => 'Zoom 8', '9' => 'Zoom 9', '10' => 'Zoom 10', '11' => 'Zoom 11', '12' => 'Zoom 12', '13' => 'Zoom 13', '14' => 'Zoom 14', '15' => 'Zoom 15', '16' => 'Zoom 16', '17' => 'Zoom 17', '18' => 'Zoom 18', '19' => 'Zoom 19')
            ),
            'map_marker' => array(
                'type' => 'upload',
                'label' => esc_html__('Map Marker', 'vitrine'),
                'title' => esc_html__('Upload map marker logo', 'vitrine'),
                'referer' => 'ep-settings-map-marker'
            ),
        );

        // merge fields array With portfolio Skill Arrays
        $fields = array_merge ( $fields , epico_generate_portfolio_skill() );

        //Option sections
        $options = array(
            'page-type-switch' => array(
                'title'   => esc_html__('Section Type', 'vitrine'),
                'tooltip' => esc_html__('Choose a section which will be shown in Container page.   Create a new section. This section can be shown in any one of your pages.', 'vitrine'),
                'fields'  => array(
                    'page-type-switch' => $fields['page-type-switch'],
                )
            ),//Section Type
			            'page-position-switch' => array(
                'title'   => esc_html__('Section Display', 'vitrine'),
                'tooltip' => esc_html__('Choose where you want to show your section. It can be shown in Container page or be shown as an external page.', 'vitrine'),
                'fields'  => array(
                    'page-position-switch' => $fields['page-position-switch'],
                )
            ),//Open Page As Separate Page Or Front Page
            'blog-type-switch' => array(
                'title'   => esc_html__('Blog Type', 'vitrine'),
                'tooltip' => esc_html__('Choose blog style between toggle Blog Or Classic blog', 'vitrine'),
                'fields'  => array(
                    'blog-type-switch' => $fields['blog-type-switch'],
                )
            ),// Add Page Sidebar
            'page-sidebar' => array(
                'title'   => esc_html__('Page Sidebar', 'vitrine'),
                'tooltip' => esc_html__('You can choose a sidebar to be shown in this section which is created in theme settings ', 'vitrine'),
                'fields'  => array(
                    'sidebar' => $fields['sidebar'],
                )
            ),// Add Page Sidebar
            'blog-sidebar' => array(
                'title'   => esc_html__('Blog Sidebar', 'vitrine'),
                'tooltip' => esc_html__('You can enable or disable blog sidebar ', 'vitrine'),
                'fields'  => array(
                    'blog-sidebar' => $fields['blog-sidebar'],
                )
            ),// Enable blog Sidebar
            'footer-widget-area' => array(
                'title'   => esc_html__('Footer Widget Area', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable widget area in the footer.', 'vitrine'),
                'fields'  => array(
                    'footer-widget-area' => $fields['footer-widget-area'],
                )
            ),//Footer Widget Area
            'extra_class' => array(
				'title'   => esc_html__('Extra Class name', 'vitrine'),
				'tooltip' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS. use space between diffrent class name', 'vitrine'),
				'fields'  => array(
				    'extra_class' => $fields['extra_class'],
			    )
			),//Extra Class name
        );
        $headerOptions = array(
			'header-type-switch' => array(
                'title'   => esc_html__('Header Type', 'vitrine'),
                'tooltip' => esc_html__('Choose your Header Type that you want to show in your page.', 'vitrine'),
                'fields'  => array(
                    'header-type-switch' => $fields['header-type-switch'],
                )
            ),// header type
			 'title-bar' => array(
                'title'   => esc_html__('Title', 'vitrine'),
                'tooltip' => esc_html__('Choose a title to be shown at the beginning of each section', 'vitrine'),
                'fields'  => array(
                    'title-bar'    => $fields['header-title-bar-switch'],
                    'title-text'   => $fields['title-text'],
                    'subtitle-text'   => $fields['subtitle-text'],
               )
            ),//Title bar sec
			'header-background-image'=> array(
                'title'   => esc_html__('Header Background Image', 'vitrine'),
                'tooltip' => esc_html__('Choose your Header background image that you want to show in your page.', 'vitrine'),
                'fields'  => array(
                    'header-background-image' => $fields['header-background-image'],
                )
            ),// header Background image
			'header-text-color'=> array(
                'title'   => esc_html__('Header Text Color', 'vitrine'),
                'tooltip' => esc_html__('Choose Header Text Color that you want to show in Header of Page.', 'vitrine'),
                'fields'  => array(
                    'header-text-color' => $fields['header-text-color'],
                )
            ),// header Text color
		
		);
		
        $sliderOptions = array(        
            'display-top-slider' => array(
                'title'   => esc_html__('Slider Visibility', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable Slider.', 'vitrine'),
                'fields'  => array(
                    'display-top-slider' => $fields['display-top-slider']
                )
            ),//Display Header
           'slider-type' => array(
                'title'   => esc_html__('Slider Type', 'vitrine'),
                'tooltip' => esc_html__('Choose your slider type.', 'vitrine'),
                'fields'  =>  array(
                    'slider-type' => $fields['slider-type'],
                    'rev-slider-container' => $fields['rev-slider-container']
                )
            ),//Intro Full-width Slider
           'slider-parallax' => array(
                'title'   => esc_html__('Slider parallax effect', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable parallax effect of slider.', 'vitrine'),
                'fields'  =>  array(
                    'slider-parallax' => $fields['slider-parallax'],
                )
            ),//Intro Full-width Slider
            'epico-slider' => array(
                'title'   => esc_html__('Slider Modes', 'vitrine'),
                'tooltip' => esc_html__('Select sliding mode of home slider<br>Select a category of slides to be shown in slider', 'vitrine'),
                'fields'  => array(
                    'slide-mode'    => $fields['slide-mode'],
                    'epico-slider-cat'   => $fields['epico-slider-cat'],
                )
            ),
            'slider-revolutionSlider' => array(
                'title'   => esc_html__('Revolution Slider', 'vitrine'),
                'tooltip' => esc_html__('Choose an existing revolution slider slide show, to be shown in intro section', 'vitrine'),
                'fields'  =>  array(
                    'home-rev-slide' =>$fields['home-rev-slide']
                )
            ),
            'epico-slider-overlay' => array(
                'title'   => esc_html__('Homepage Slider Overlay', 'vitrine'),
                'tooltip' => esc_html__('Select an overlay color., set it\'s opacity. Select a texture to be added on the foreground of your slides.', 'vitrine'),
                'fields'  =>  array(
                    'slider-overlay-color'    => $fields['slider-overlay-color'],
                    'slider-overlay-opacity'   => $fields['slider-overlay-opacity'],
                    'slider-overlay-texture'   => $fields['slider-overlay-texture'],
                )
            ),
            'slider-start-btn' => array(
                'title'   => esc_html__('Start Button', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable start button.<br>choose a style (Dark/Light) for start button<br> Choose type of start button', 'vitrine'),
                'fields'  =>  array(
                    'slider-start-btn' => $fields['slider-start-btn'],
                    'slider-start-style' => $fields['slider-start-style'],
                    'slider-start-type' => $fields['slider-start-type']
                )
            ),//start button
        );

        $SnapToScrollOptions = array(        
            'snap-to-scroll' => array(
                'title'   => esc_html__('Snap to Scroll', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable snap to scroll for this page.', 'vitrine'),
                'fields'  => array(
                    'snap-to-scroll' => $fields['snap-to-scroll']
                )
            ),//Display Header
            'snap-to-scroll-nav-style' => array(
                'title'   => esc_html__('Navigation style', 'vitrine'),
                'tooltip' => esc_html__('Navigation style(Dark/Light)', 'vitrine'),
                'fields'  => array(
                    'snap-to-scroll-nav-style' => $fields['snap-to-scroll-nav-style']
                )
            )
        );

       $mapOptions = array(
            'footer-map' => array(
                'title'   => esc_html__('Footer Map Visibility', 'vitrine'),
                'tooltip' => esc_html__('Enable or disable map in the footer.', 'vitrine'),
                'fields'  => array(
                    'footer-map' => $fields['footer-map'],
                )
            ),//google map sec
            'footerStyleMap' => array(
                'title'   => esc_html__('Map Style', 'vitrine'),
                'tooltip' => esc_html__('You can choose one of available map styles to be shown in contact section.', 'vitrine'),
                'fields'  => array(
                    'footerStyleMap' => $fields['footerStyleMap'],
                )
            ),//google map Style sec
            'footer-properties-map' => array(
                'title'   => esc_html__('Google Map Properties', 'vitrine'),
                'tooltip' => esc_html__('Enter your map address latitude & longitude and zoom levels. Zoom value can be from 1 to 19 where 19 is the greatest and 1 the smallest.', 'vitrine'),
                'fields'  => array(
                    'footerMapLatitude' => $fields['footerMapLatitude'],
                    'footerMapLongitude' => $fields['footerMapLongitude'],
                    'footerMapZoom' => $fields['footerMapZoom'],
                )
            ),//footer Google Map properties
            'map_marker' => array(
                'title'   => esc_html__('Map Marker', 'vitrine'),
                'tooltip' => esc_html__('Upload an image to be shown as google map marker', 'vitrine'),
                'fields'  => array(
                    'map_marker' => $fields['map_marker'],
                )
            ),// Google Map Marker
        );

        $menuOptions = array(
            'menu' => array(
                'title'   => esc_html__('Menu style', 'vitrine'),
                'tooltip' => esc_html__('Override menu style for current page.', 'vitrine'),
                'fields'  => array(
                    'menu' => array(
                        'type' => 'select',
                        'label'=> esc_html__('Menu style', 'vitrine'),
                        'options' => array('default'=> esc_html__('Inherit from theme settings', 'vitrine'),'custom'=> esc_html__('Custom', 'vitrine')),
                    ),
                )
            ),
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
                ),
            ),
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
                )
            )
        );

        $metaboxes = array(
            array(
                'id' => 'page_header_meta_box',
                'title' => esc_html__('Page Header Settings', 'vitrine'),
                'context' => 'normal',
                'priority' => 'high',
                'options' => $headerOptions,
            ),//Meta box 1
			array(
                'id' => 'snap_to_scroll_meta_box',
                'title' => esc_html__('Snap to Scroll Settings', 'vitrine'),
                'context' => 'normal',
                'priority' => 'high',
                'options' => $SnapToScrollOptions,
            ),//Meta box 2
            array(
                'id' => 'blog_meta_box',
                'title' => esc_html__('Page Settings', 'vitrine'),
                'context' => 'normal',
                'priority' => 'high',
                'options' => $options,
            ),//Meta box 3
            array(
                'id' => 'slider_meta_box',
                'title' => esc_html__('Top Slider Settings', 'vitrine'),
                'context' => 'normal',
                'priority' => 'high',
                'options' => $sliderOptions,
            ),//Meta box 4
            array(
                'id' => 'gmap_meta_box',
                'title' => esc_html__('Map Settings', 'vitrine'),
                'context' => 'normal',
                'priority' => 'high',
                'options' => $mapOptions,
            ),//Meta box 5
        );

        //Add menu metabox - unset some options that is not appropriate for current menu type
        if(epico_opt('header-type') != 7 && epico_opt('header-type') != 8)
        {

            if(epico_opt('menu-hover-style') == 3)
            {
                unset($menuOptions['menu-color']['fields']['menu-text-hover-color']);
                unset($menuOptions['initial-menu-color']['fields']['initial-menu-text-hover-color']);

                unset($menuOptions['menu-color']['fields']['menu-text-bg-hover-color']);
                unset($menuOptions['initial-menu-color']['fields']['initial-menu-text-bg-hover-color']);
            }
            elseif(epico_opt('menu-hover-style') == 2)
            {
                unset($menuOptions['menu-color']['fields']['menu-text-bg-hover-color']);
                unset($menuOptions['initial-menu-color']['fields']['initial-menu-text-bg-hover-color']);
            }

            if(epico_opt('header-style') == 'fixed-menu')
            {
                unset($menuOptions['menu-color']);
            }

            $metaboxes[] = array(
                'id' => 'menu_meta_box',
                'title' => esc_html__('Menu Settings', 'vitrine'),
                'context' => 'normal',
                'priority' => 'high',
                'options' => $menuOptions
            );
        }

        return $metaboxes;


    }
}

new epico_Page();
