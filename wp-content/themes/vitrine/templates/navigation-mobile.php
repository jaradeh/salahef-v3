<?php  if ( class_exists( 'WooCommerce' ) && class_exists('YITH_WCWL') && epico_opt('responsive-wishlist-display') == 1 ) {
    $cart_widget_enable = ((epico_opt('shop-enable-cart') == 1 && epico_opt('catalog_mode') == 0) ? ' enable_cart_widget':'');
} ?>

<?php
$mobile_menu_color = epico_opt('mobile_menu-color');

    $location = '';
    if ( has_nav_menu( 'mobile-nav' ) ) {
	    $location= 'mobile-nav';
    }
    elseif ( has_nav_menu( 'primary-nav' ) ) {
	    $location = 'primary-nav';
    }

if ( has_nav_menu( $location ) ) { ?>
    <div id="mobile-menu-button" class="navigation-button hidden-desktop no_djax">
        <span></span>
    </div>
    <nav id="mobile-menu-items" class="navigation-mobile <?php if ( $mobile_menu_color == 0 ) { ?>light<?php }  ?> " style="display:none;">
        <?php
        wp_nav_menu(array(
            'container' =>'',
            'theme_location' => $location,
            'fallback_cb' => false,
            'items_wrap'        => '<div class="mobile-menu-container hidden-desktop"><ul id="%1$s" class="%2$s">%3$s</ul></div>',
            'walker'      =>   new epico_Mobbile_Nav_Walker(),
        ));
        ?>

        <?php
        if ( class_exists('WooCommerce')  && epico_opt('shop-login-link') == 1 && epico_opt('topbar_display') != 0 ) { ?>
            <div class="mobile-menu-login-link">
            <?php echo epico_get_myaccount_link(); ?>
            </div>
        <?php } ?>

        <?php
        if ( class_exists( 'WooCommerce' ) && class_exists('YITH_WCWL') && epico_opt('responsive-wishlist-display') == 1 ) {?>
        <div class="responsive-wishlist hidden-desktop<?php echo esc_attr($cart_widget_enable); ?>">
        <?php
        /*  woocomerce wishlist widget */
        $instance = array(
            "title" => "",
            "type" => 'dark',
        );

        the_widget( "epico_Woocommerce_Wishlist_Widget", $instance );
        ?>
    </div>
        <?php } ?>

        <?php if(epico_opt('menu-search') == 1)
        {
            ?>
            <div id="mobile-search-form">
                <?php
                    get_search_form();
                ?>
            </div>
            <?php
        } ?>

        <?php if ( epico_opt('topbar_display') != 0 && (epico_opt('topbar-language-link-1') ||  epico_opt('topbar-language-link-2') || epico_opt('topbar-language-link-3')) ) { ?>
        <div class="lang-sel">
            <?php if ( epico_opt('topbar-language-link-1')) { ?>
                    <a href="<?php epico_eopt('topbar-language-link-1'); ?>"><?php epico_eopt('topbar-language-1'); ?></a>
            <?php } ?>
            <?php if ( epico_opt('topbar-language-link-2')) { ?>
                    <a href="<?php epico_eopt('topbar-language-link-2'); ?>"><?php epico_eopt('topbar-language-2'); ?></a>
            <?php } ?>
            <?php if ( epico_opt('topbar-language-link-3')) { ?>
                    <a href="<?php epico_eopt('topbar-language-link-3'); ?>"><?php epico_eopt('topbar-language-3'); ?></a>
            <?php } ?>
        </div>
        <?php } ?>
    </nav>

    <span id="mobile-menu-overlay" class="hidden-desktop"></span>
<?php } ?>