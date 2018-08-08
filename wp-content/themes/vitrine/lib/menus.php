<?php

function epico_register_menus() {
    register_nav_menu( 'primary-nav', esc_html__( 'Primary Navigation', 'vitrine' ) );
	register_nav_menu( 'mobile-nav', esc_html__( 'Mobile Navigation', 'vitrine' ) );
    register_nav_menu( 'topbar-nav', esc_html__( 'Top bar Navigation', 'vitrine' ) );
    register_nav_menu( 'footer-nav', esc_html__( 'Footer Navigation', 'vitrine' ) );
}

add_action( 'init', 'epico_register_menus' );