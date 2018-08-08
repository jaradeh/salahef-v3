<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php if(!epico_is_shop_ajax_request()) { ?>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) { ?>

       <?php  // If the `has_site_icon` function doesn't exist (ie we're on < WP 4.3) or if the site icon has not been set 
        if ( ! ( function_exists( 'has_site_icon' ) && has_site_icon() ) ) { ?>
          
            <?php if(epico_opt('favicon') != ""){ ?>
                <link rel="shortcut icon" href="<?php epico_eopt('favicon'); ?>"  />
            <?php } else { ?>
                <link rel="shortcut icon" href="<?php echo EPICO_THEME_IMAGES_URI ?>/favicon.png" />
            <?php } ?>

        <?php } ?>

    <?php } ?>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php wp_head(); ?>
    <?php } ?>
</head>
<body <?php body_class();?> <?php epico_body_attr();?>>
    
    <?php if(!epico_is_shop_ajax_request() && !epico_is_djax_request()) { ?>

        <div id="top"></div>
        
        <div class="scrollToTop visible-desktop">
            <a href="#top"></a>
        </div>
        
        <div class="layout">

        <!-- Preloader -->
        <?php if( epico_opt('loader_display') == '0' ) { ?>
    	    <div id="preloader" class="firstload <?php echo epico_opt('preloader-type'); if(epico_opt('preloader_display')) { echo ' hideInFirstLoad'; } ?>">
    	        <div id="preloader_box">
                        <div id="preloader_items">
                            <div class="preloader-items-container">
                                <div class="preloader-image" style="background-image:url(<?php epico_preloader(); ?>);"></div>
                            </div>
                        </div>

                        <div class="preloader-text-container">
                            <div class="preloader-text"><?php if ( epico_opt('preloader-text')) { epico_eopt('preloader-text'); } ?></div>
                        </div>

                        <svg width="334" height="334" viewbox="0 0 40 40" class="preloader">
                            <polygon points="0 0 0 40 40 40 40 0" class="rect" />
                        </svg>
                </div>
                <?php if ( epico_opt('preloader-type') == "simple" || epico_opt('preloader-type') == "creative"  ) { ?>
                    <!-- preloader simple  -->
                    <svg id="preloader-simple" width="50" height="50" viewbox="0 0 40 40" class="preloader">
                        <polygon points="0 0 0 40 40 40 40 0" class="rect" />
                    </svg>
                
                
                <?php } else if ( epico_opt('preloader-type') == "circular" ) { ?>
                    <!-- preloader circular  -->
                    <svg id="preloader-circular" height="50" width="50" class="preloader_circular">
                        <circle cx="25" cy="25" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" class="path"></circle>
                    </svg>
                <?php } else if( epico_opt('preloader-type') == "sniper" ) { ?>
                    <!-- preloader sniper  -->
                    <div  id="preloader-sniper"  class="sniperloader">
					  esc_html_e('Loading...', 'vitrine');
                      <div class="ball"></div>
                      <div class="ball"></div>
                      <div class="ball"></div>
                    </div>
                <?php } ?>            
    	    </div>
            
        <?php } ?>
                
        <?php if ( epico_opt('topbar_display') == 1) {  ?>


            <?php $topBarStyle = (epico_opt('topbar_style') == 1) ? 'light':'dark'; ?>
            <div id="topbar" class="hidden-phone hidden-tablet <?php echo esc_attr($topBarStyle); ?>">

                <?php if (epico_opt('boxed_topbar') == 1) { ?>
                             <div class="container">
                                 <?php } ?>

                <div class="topbarMessage ">
                    <?php if ( epico_opt('topbar_icon') ) { ?> 
                        <div class="topbarIcon icon-<?php epico_eopt('topbar_icon'); ?>"></div> 
                    <?php } ?>
                        
                    <?php if ( epico_opt('topbar_title') ) { ?>
                            <div class="topbarTitle">
                            <?php epico_eopt('topbar_title'); ?>
                            </div>
                    <?php } ?>

                    <?php if ( epico_opt('topbar_text') ) { ?>
                            <div class="topbarText">
                            <?php epico_eopt('topbar_text'); ?>
                            </div>
                    <?php } ?>
                </div>

                <?php if (epico_opt('topbar-social-display') ) { ?>
                    <div class="topbar_social">
                        <ul class="social-icons">
                                    
                            <?php
                                epico_socialIcon('social_facebook_url', 'icon-facebook' , 'facebook');//Facebook
                                epico_socialIcon('social_twitter_url', 'icon-twitter' , 'twitter'); // Twitter
                                epico_socialIcon('social_vimeo_url', 'icon-vimeo' , 'vimeo'); // Vimeo
                                epico_socialIcon('social_youtube_url', 'icon-youtube-play' , 'youtube-play'); // Youtube
                                epico_socialIcon('social_googleplus_url', 'icon-google-plus' , 'google-plus'); //Google+
                                epico_socialIcon('social_dribbble_url', 'icon-dribbble', 'dribbble');//Dribbble
                                epico_socialIcon('social_tumblr_url', 'icon-tumblr', 'tumblr');//Tumblr
                                epico_socialIcon('social_linkedin_url', 'icon-linkedin', 'linkedin');//Linkedin
                                epico_socialIcon('social_flickr_url', 'icon-flickr', 'flickr');//flickr
                                epico_socialIcon('social_github_url', 'icon-github' , 'github5');//github
                                epico_socialIcon('social_lastfm_url', 'icon-lastfm', 'lastfm');//lastfm
                                epico_socialIcon('social_paypal_url', 'icon-paypal', 'paypal');//paypal
                                if(epico_opt('rss_url') == '0'){
                                epico_socialIcon('social_rss_url', 'icon-feed', 'feed');//rss
                                }
                                epico_socialIcon('social_skype_url', 'icon-skype' , 'skype');//skype
                                epico_socialIcon('social_wordpress_url', 'icon-wordpress', 'wordpress');//wordpress
                                epico_socialIcon('social_yahoo_url', 'icon-yahoo' , 'yahoo');//Yahoo
                                epico_socialIcon('social_deviantart_url', 'icon-deviantart', 'deviantart');//Deviantart
                                epico_socialIcon('social_steam_url', 'icon-steam', 'steam');//steam
                                epico_socialIcon('social_reddit_url', 'icon-reddit-alien' , 'reddit-alien');//reddit
                                epico_socialIcon('social_stumbleupon_url', 'icon-stumbleupon' , 'stumbleupon');//stumbleupon
                                epico_socialIcon('social_pinterest_url', 'icon-pinterest', 'pinterest');//Pinterest
                                epico_socialIcon('social_xing_url', 'icon-xing', 'xing');//xing
                                epico_socialIcon('social_blogger_url', 'icon-blogger', 'blogger');//blogger
                                epico_socialIcon('social_soundcloud_url', 'icon-soundcloud', 'soundcloud');//soundcloud
                                epico_socialIcon('social_delicious_url', 'icon-delicious', 'delicious');//delicious
                                epico_socialIcon('social_foursquare_url', 'icon-foursquare', 'foursquare');//foursquare
                                epico_socialIcon('social_instagram_url', 'icon-instagram', 'instagram');//instagram
                                epico_socialIcon('social_behance_url', 'icon-behance', 'behance');//Behance
                            ?>
                            
                        </ul>
                    </div>

                <?php } ?>
                <?php 
                    if ( epico_opt('topbar-language-link-1') ||  epico_opt('topbar-language-link-2') || epico_opt('topbar-language-link-3') ) { ?>
                    <div class="topbar_lang_flag">
                        <div class="lang-sel">
							 <?php if ( epico_opt('topbar-language-link-1')) { ?>
                                    <li id="language1">
                                        <a href="<?php epico_eopt('topbar-language-link-1'); ?>"><?php epico_eopt('topbar-language-1'); ?></a>
                                    </li>
                                <?php } ?>
						       
                            <ul class="lang_link">
                                <?php if ( epico_opt('topbar-language-link-2')) { ?>
                                    <li id="language2">
                                        <a href="<?php epico_eopt('topbar-language-link-2'); ?>"><?php epico_eopt('topbar-language-2'); ?></a>
                                    </li>
                                <?php } ?>
                                <?php if ( epico_opt('topbar-language-link-3')) { ?>
                                    <li id="language3">
                                        <a href="<?php epico_eopt('topbar-language-link-3'); ?>"><?php epico_eopt('topbar-language-3'); ?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>

                <?php } ?>

                <?php  if ( class_exists( 'WooCommerce' ) && class_exists('YITH_WCWL') && epico_opt('topbar-wishlist-display') == 1 ) { ?>
                    <div class="topbar_wishlist">

                        <?php
                        the_widget( "epico_Woocommerce_Wishlist_Widget");

                        ?>
                    </div>
                <?php } ?>
                <?php 
                if ( class_exists('WooCommerce')  && epico_opt('shop-login-link') == 1 ) { ?>

					<div class="topbar_login_link">
						<div class="topbar_login">
							<div class="topbar_login_text ">
								<span> <?php echo epico_get_myaccount_link(); ?> </span>
							</div>
							<?php if(is_user_logged_in() && class_exists('Woocommerce')) { ?>
								<ul  class="topbar_login-content">
									<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
										<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
										<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
										</li>
									<?php endforeach; ?>
								</ul>								
							<?php
							}
							?>
    				    </div>
                    </div> 

				<?php } ?>

                <?php
                    wp_nav_menu(array(
                        'container' =>'',
                        'menu_class' => 'clearfix simple-menu ',
                        'before'     => '',
                        'theme_location' => 'topbar-nav',
                        'walker'     => new epico_Simple_Nav_Walker(),
                        'fallback_cb' => false , 
                        'after' => ''
                    ));
                ?>
                <?php if (epico_opt('boxed_topbar') == 1) { ?>
                             </div>
                                <?php } ?>
            </div>
        <?php } ?>

		<?php if ( epico_opt('ep-toggle-sidebar') == 1) {  ?>
			<div class="toggleSidebar toggleSidebarWidgetbar">
				<div id="toggle-sidebar-close-btn"></div> 
				<?php   dynamic_sidebar( 'toggle-sidebar' ); ?>
			</div>
		<?php } ?>

		<?php if ( class_exists( 'WooCommerce' )) {  ?>
			<div class="toggleSidebar cartSidebarContainer">
				 <div class="cartSidebarWrap">
					<div class="wc-loading"></div>
					<span class="wc-loading-bg"></span>
					<div class="widget_shopping_cart_content">
					<?php woocommerce_mini_cart(); ?>
					</div>
				</div>
			</div>
		<?php }

		// menu
		get_template_part('templates/section', 'nav');

	}

	$headerType = epico_opt('header-type');
	$headerTypeClass;

	if ( $headerType == 1 ) {

		$headerTypeClass = ' type1';

	} else if ( $headerType == 7 || $headerType == 8 ) { // left & rightSidebar

		$headerTypeClass = ' type7';

	} else if ( $headerType == 9){

		$headerTypeClass = ' type9';

	} else if ( $headerType == 2 || $headerType == 3 ) {
			
			$headerTypeClass = 'type2_3';

	} else if ( $headerType == 4 || $headerType == 5  || $headerType == 6 ) {
		
		$headerTypeClass = 'type4_5_6'; 
	}



    /* check Slider is Enable Or not  */
    $hasSlider = '';
    if(epico_get_meta('display-top-slider'))
    {
	    if ( epico_get_meta("slider-type") == 'epico-slider' ) {
	        $hasSlider = 'hasEpicoSlider';
	    } else if ( epico_get_meta("slider-type") == 'slider-revolutionSlider') {
	        $hasSlider = 'hasRevSlider';
	    }
	}
	
	if (function_exists('is_woocommerce') && is_woocommerce()) { // check woocomerce plugin is active or not
		$productDetailStyleClass = '';
		if(is_product()) {
			/* check Product details Style */
			if(epico_get_meta('product_detail_style_inherit') == '1')
			{
				$product_detail_style = epico_get_meta('product_detail_style'); // style of product detail in product page
			}
			else
			{
				$product_detail_style = epico_opt("product-detail-style"); // style of product detail in theme settings
			}
			
			
			if( $product_detail_style == "pd_background" ) {
				$productDetailStyleClass = 'pd_background';
			} else if ( $product_detail_style == "pd_top" ){
				$productDetailStyleClass = 'pd_top';
			}
		}
		
		$pageTopSpace = '';
		$headerType = epico_get_meta( "header-type-switch", true );
		$hasSpace = ( $headerType == '1' )? true : false;

		$term 			= get_queried_object();
		$cateID 		= empty( $term->term_id ) ? 0 : $term->term_id;
		$display_type_wc_setting = get_option('woocommerce_category_archive_display');
		$display_type_cat_setting = get_woocommerce_term_meta( $cateID, 'display_type' );
		//show subcategories after shop-filter
		$cat_display_header =  ((is_product_category() && $display_type_cat_setting == 'both') || (is_product_category() && $display_type_wc_setting == 'both' && $display_type_cat_setting == '')) ? true : false;
		
		if( (!is_shop() && !$cat_display_header ) || $hasSpace){
            $pageTopSpace = 'pageTopSpace';
		}
		?>
		<div class="toggleSidebarContainer">
			<div id="main-content" class="main-content <?php echo $pageTopSpace;?> <?php echo esc_attr($headerTypeClass); ?> <?php echo esc_attr($hasSlider); ?> <?php echo esc_attr($productDetailStyleClass); ?>">
				<div id="main">
		<?php
				
			get_template_part( 'templates/slider' );
			
			get_template_part( 'templates/head' );
		
		
	} elseif(is_page_template('main-page.php'))
	{

		$pageTopSpace = '';
		$headerType = epico_get_meta( "header-type-switch", true );
		$hasSpace = ( $headerType == '1' )? true : false;

		if($hasSpace){
            $pageTopSpace = 'pageTopSpace';
		}
		?>

		<div class="toggleSidebarContainer">
			<div id="main-content" class="main-content <?php echo $pageTopSpace; ?> <?php echo esc_attr($headerTypeClass); ?> <?php echo esc_attr($hasSlider); ?>">
				<div id="main">
				<?php
					get_template_part( 'templates/slider' );
				
		
	} elseif(is_page())
	{
		

		$page_type = epico_get_meta( "page-type-switch" );
		$pageTopSpace = '';
		$headerType = epico_get_meta( "header-type-switch", true );
		$hasSpace = ( $headerType == '1' )? true : false;

		if( $hasSpace ){
            $pageTopSpace = 'pageTopSpace';
		}
		?>

		<div class="toggleSidebarContainer">
			<div id="main-content" class="main-content <?php echo $pageTopSpace; ?> <?php echo esc_attr($headerTypeClass); ?> <?php echo esc_attr($hasSlider); ?> <?php if( $page_type == "blog-section" ){ ?> blogClassicPage <?php } ?>">
				<div id="main">
				<?php
		
					get_template_part( 'templates/slider' );
			
					get_template_part( 'templates/head' );
		
	} elseif(is_home()) // blog page
	{
		$pageTopSpace = '';
		$headerType = epico_get_meta( "header-type-switch", true );
		$hasSpace = ( $headerType == '1' )? true : false;

		if( $hasSpace ){
            $pageTopSpace = 'pageTopSpace';
		}
		
		?>
		<div class="toggleSidebarContainer">
			<div id="main-content" class="main-content <?php echo $pageTopSpace; ?> <?php echo esc_attr($headerTypeClass); ?> <?php echo esc_attr($hasSlider); ?> blogClassicPage">
				<div id="main">
		<?php
		
					get_template_part( 'templates/slider' );
			
					get_template_part( 'templates/head' );
		
	} elseif(is_404()) // 404 page
	{
		?>
		<div class="toggleSidebarContainer">
			<div id="main-content" class="main-content pageTopSpace <?php echo esc_attr($headerTypeClass); ?>"> 
				<div id="blogSingle" class="wrap singlePost">
		<?php
		
	} elseif(is_search()) // Search page
	{
		?>
		<div class="toggleSidebarContainer">
			<div id="main-content" class="main-content pageTopSpace <?php echo esc_attr($headerTypeClass); ?>">
				<div id="blogSingle" class="wrap epicoSection customSection singlePost">
		<?php
		
	} elseif(is_archive()) // blog archive
	{
		?>
		<div class="toggleSidebarContainer">
			<div id="main-content" class="main-content pageTopSpace <?php echo esc_attr($headerTypeClass); ?>">
				<div class="wrap epicoSection customSection singlePost archiveBlogSingle">
		<?php
	}elseif(is_singular( 'portfolio' )) // portfolio details
	{
		//global $wp_query;
		$pPostDetailType = epico_get_meta('portfolio-detail-style');

        if ( isset($_GET['inner']) ) { 
            $pPostDetailType = 'portfolio_detail_default';
        }
		
		$pPostType = get_post_format() == false ? "standard" : get_post_format();
		$pageTopSpace = '';
		if($pPostDetailType != 'portfolio_detail_creative' && $pPostType == "standard") {
			$images = epico_get_meta('image');
			if(empty($images[0]))
			{
				$pageTopSpace = "pageTopSpace";
			}
		}
		?>

		<div class="toggleSidebarContainer">
			<div id="main-content" class="main-content <?php echo $pageTopSpace ?> <?php  echo esc_attr($headerTypeClass); ?>">
				<div id="portfoliSingle" class="wrap singlePost <?php echo esc_attr($pPostDetailType); ?>">
		<?php
		
	} elseif(is_single()) // blog post details
	{
		$sidebar=epico_opt('blog-sidebar-position');
		?>
		<div class="toggleSidebarContainer">
			<div  id="main-content" class="main-content pageTopSpace <?php echo esc_attr($headerTypeClass); ?>">
				<div id="blogSingle" class="wrap epicoSection customSection singlePost <?php if( $sidebar == 'main-sidebar' ) { ?> blogHasSidebar <?php } ?>">
		<?php
		
	}
	else
	{
		?>
		<div class="toggleSidebarContainer">
			<div id="main-content" class="main-content pageTopSpace <?php echo esc_attr($headerTypeClass); ?> <?php echo esc_attr($hasSlider); ?>">
				<div id="main" class="wrap">
                    <div class="container">
		<?php
	}
	
 ?>