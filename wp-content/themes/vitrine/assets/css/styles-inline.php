<?php
//Main colors
$acc    = epico_opt('style-accent-color');//Accent color
$hc     = epico_opt('style-highlight-color');//Highlight color
$lc     = epico_opt('style-link-color');//Link color
$lhc    = epico_opt('style-link-hover-color');//Link hover color

//Preloader
$preloader_bg_color = epico_opt('preloader_bg_color');
$preloader_color = epico_opt('preloader_color');
$preloader_box_color = epico_opt('preloader_box_color');
$preloader_text_color = epico_opt('preloader_text_color');

//topbar
$topbar_bg_color = epico_opt('topbar_bg_color');
$topbar_border_color = epico_opt('topbar_border_color');

//cart bg color
$shop_cart_bg_color = epico_opt('shop-cart-bg-color');

// toggle sidebar bg color 
$toggle_sidebar_bg_color = epico_opt('toggle-sidebar-bg-color');


//Initial menu value
$initialMenuBgColor = epico_opt('initial-menu-background-color');
$initialMenuTextColor = epico_opt('initial-menu-text-color');
$initialMenuTextHoverColor = epico_opt('initial-menu-text-hover-color');
$initialMenuHoverColor =  epico_opt('initial-menu-text-bg-hover-color');//initial menu hover color
$initialMenuBorderColor =  epico_opt('initial-menu-border-color');

//Menu Styles
$menuBgColor = epico_opt('menu-background-color') ;
$menuTextColor =  epico_opt('menu-text-color');
$menuTextHoverColor =  epico_opt('menu-text-hover-color');
$MenuHoverColor    = epico_opt('menu-text-bg-hover-color');//menu hover color
$menuBorderColor =  epico_opt('menu-border-color');

//Submenu Styles
$submenuBgColor = epico_opt('submenu-background-color') ;
$submenuTextColor =  epico_opt('submenu-text-color');
$submenuHeadingColor =  epico_opt('submenu-heading-color');


$menuOpacity=  epico_opt('menu-opacity');
if ((isset($menuOpacity) && !empty($menuOpacity)) || ($menuOpacity == "0") ) {
    $menuOpacity=  epico_opt('menu-opacity')/100;
} else {
    $menuOpacity = 0.98;
}

if(epico_get_meta('menu') == 'custom')
{
    //Initial menu value
    $initialMenuBgColor = epico_get_meta('initial-menu-background-color');
    $initialMenuTextColor = epico_get_meta('initial-menu-text-color');
    $initialMenuTextHoverColor = epico_get_meta('initial-menu-text-hover-color');
    $initialMenuHoverColor =  epico_get_meta('initial-menu-text-bg-hover-color');
    $initialMenuBorderColor =  epico_get_meta('initial-menu-border-color');

    if(epico_opt('header-style') == 'epico-menu')
    {
        //Menu Styles
        $menuBgColor = epico_get_meta('menu-background-color') ;
        $menuTextColor =  epico_get_meta('menu-text-color');
        $menuTextHoverColor =  epico_get_meta('menu-text-hover-color');
        $MenuHoverColor    = epico_get_meta('menu-text-bg-hover-color');//menu hover color
        $menuBorderColor =  epico_get_meta('menu-border-color');
    }

}

?>

/* cart bg color */
header.type9 .widget.widget_woocommerce-dropdown-cart ,
header.type1 .widget.widget_woocommerce-dropdown-cart ,
header.type2_3 .widget.widget_woocommerce-dropdown-cart ,
header.type4_5_6 .widget.widget_woocommerce-dropdown-cart {
    background-color:#1e1e1e;
    background-color: <?php echo esc_attr($shop_cart_bg_color); ?>;
}

/* toggle sidebar bg color */ 
header .sidebartogglebtn {
    background-color: <?php echo esc_attr($toggle_sidebar_bg_color); ?>;
}

/* Menu */
aside.vertical_menu_area {
    background-color: <?php echo esc_attr($menuBgColor); ?>;
}

.vertical_menu_enabled .vertical_background_image {
    opacity: <?php echo esc_attr($menuOpacity); ?>;
}

#menuBgColor {
    background-color: <?php echo esc_attr($menuBgColor); ?>;
}

/* background image in vertical menu opacity */
.vertical_menu_enabled #menuBgColor {
    opacity: <?php echo esc_attr($menuOpacity); ?>;
}

#epHeader #headerFirstState {
    border-bottom: 1px solid <?php echo esc_attr($initialMenuBorderColor); ?>;
}

header  #headerFirstState .sidebartogglebtn,
header #headerFirstState .widget.widget_woocommerce-dropdown-cart {
    border: 1px solid <?php echo esc_attr($initialMenuBorderColor); ?>;
}

#epHeader #headerSecondState {
    border-bottom: 1px solid <?php echo esc_attr($menuBorderColor); ?>;
}

header  #headerSecondState .sidebartogglebtn,
header #headerSecondState .widget.widget_woocommerce-dropdown-cart {
    border: 1px solid <?php echo esc_attr($menuBorderColor); ?>;
}

header.underlineHover #headerSecondState .navigation > ul > li:hover > a,
header.underlineHover #headerSecondState .navigation li.active > a,
header.underlineHover #headerSecondState .navigation > ul > li.current_page_item > a,
header.underlineHover #headerSecondState .navigation > ul > li.current-menu-ancestor > a,
header.fillhover #headerSecondState .navigation > ul > li:hover > a span,
header.fillhover #headerSecondState .navigation li.active > a span,
header.fillhover #headerSecondState .navigation > ul > li.current_page_item > a span ,
header.fillhover #headerSecondState .navigation > ul > li.current-menu-ancestor > a span ,
header.borderhover #headerSecondState .navigation > ul > li:hover > a span,
header.borderhover #headerSecondState .navigation > ul > li.current_page_item > a span,
header.borderhover #headerSecondState .navigation > ul > li.current-menu-ancestor > a span {
    color: <?php echo esc_attr($menuTextHoverColor); ?>;
}

header.underlineHover #headerFirstState .navigation > ul > li:hover > a,
header.underlineHover #headerFirstState .navigation > ul > li.active > a,
header.underlineHover #headerFirstState .navigation > ul > li.current_page_item > a,
header.underlineHover #headerFirstState .navigation > ul > li.current-menu-ancestor > a,
header.fillhover #headerFirstState .navigation > ul > li:hover > a span,
header.fillhover #headerFirstState .navigation li.active > a span,
header.fillhover #headerFirstState .navigation > ul > li.current_page_item > a span ,
header.fillhover #headerFirstState .navigation > ul > li.current-menu-ancestor > a span ,
header.borderhover #headerFirstState .navigation > ul > li:hover > a span,
header.borderhover #headerFirstState .navigation > ul > li.active > a span,
header.borderhover #headerFirstState .navigation > ul > li.current_page_item > a span,
header.borderhover #headerFirstState .navigation > ul > li.current-menu-ancestor > a span {
    color: <?php echo esc_attr($initialMenuTextHoverColor); ?>;
}

header.underlineHover #headerSecondState .navigation ul > li hr {  
    background-color:  <?php echo esc_attr($menuTextHoverColor); ?>;
}

header.underlineHover #headerFirstState .navigation ul > li hr {  
    background-color:  <?php echo esc_attr($initialMenuTextHoverColor); ?>;
}

/* Submenu */

<?php if(!empty($submenuBgColor)) { ?>
header .navigation li div.menu-item-wrapper,
header .navigation li ul {
    background-color : <?php echo esc_attr($submenuBgColor); ?>;
}
<?php } ?>

<?php if(!empty($submenuTextColor)) { ?>

header .navigation li.mega-menu-parent > .menu-item-wrapper > ul > li.special-last-child > ul > li:last-of-type:before, header .navigation li li > a {
    color : <?php echo esc_attr($submenuTextColor); ?>;
}

header .navigation > ul > li:not(.mega-menu-parent) li.menu-item-has-children:before,
header .navigation > ul > li:not(.mega-menu-parent) li.menu-item-has-children:after {
    background : <?php echo esc_attr($submenuTextColor); ?>;
}

header.submenu_underlined .navigation ul li li > a span:not(.icon) span.menu_title:before {
    background-color : <?php echo esc_attr($submenuTextColor); ?>;
}

<?php } ?>

<?php if(!empty($submenuHeadingColor)) { ?>
header .navigation li.mega-menu-parent div > ul > li.menu-item-has-children > a,
header .navigation li.mega-menu-parent div > ul > li:not(.menu-item-has-children) > a {
    color : <?php echo esc_attr($submenuHeadingColor); ?>;
}

header.submenu_underlined .navigation li li > a:before,
header .navigation li.mega-menu-parent li ul li.bottom-line:before,
header .navigation li.mega-menu-parent div > ul > li.menu-item-has-children > a:after {
    background-color : <?php echo esc_attr($submenuHeadingColor); ?>;
}

<?php } ?>

header .search-button, aside.vertical_menu_area .search-button ,
header .navigation > ul > li > a , .vertical_menu_enabled .vertical_menu_area .vertical_menu_navigation li a {
    color: <?php echo esc_attr($menuTextColor); ?>;
}

.vertical_menu_enabled .vertical_menu_area .vertical_menu_navigation li a:hover,
.vertical_menu_enabled .vertical_menu_area .vertical_menu_navigation li.active a.mp-back ,
.vertical_menu_enabled .vertical_menu_area .vertical_menu_navigation li.active > a {
    color: <?php echo esc_attr($menuTextHoverColor); ?> !important;
}

.vertical_menu_enabled .vertical_menu_area .vertical_menu_navigation li:hover,
.vertical_menu_enabled .vertical_menu_area .vertical_menu_navigation li.active {
    background-color: <?php echo esc_attr($MenuHoverColor); ?> !important;
}

#headerFirstState .menuBgColor
{
    background-color: <?php echo esc_attr($initialMenuBgColor); ?>;
}

header .navigation > ul > li .spanHover{ 
    background-color:<?php echo esc_attr($MenuHoverColor); ?>;
}

header .navigation > ul > li:hover .spanHover { 
    background-color:<?php echo esc_attr($MenuHoverColor); ?> !important;
}

#headerFirstState .search-button,
#headerFirstState .navigation > ul > li > a {
    color: <?php echo esc_attr($initialMenuTextColor); ?>;
}
        
header #headerFirstState .navigation > ul > li .spanHover  {
    background-color:<?php echo esc_attr($initialMenuHoverColor); ?> !important;
}

header.borderhover #headerFirstState .navigation > ul > li > a:before,
header.borderhover #headerFirstState .navigation > ul > li.active > a:before,
header.borderhover #headerFirstState .navigation > ul > li.current_page_item > a:before,
header.borderhover #headerFirstState .navigation > ul > li.current-menu-ancestor > a:before {
    background-color:<?php echo esc_attr($initialMenuHoverColor); ?>;
}

header.borderhover .navigation > ul > li > a:before,
header.borderhover .navigation > ul > li.active > a:before,
header.borderhover .navigation > ul > li.current_page_item > a:before,
header.borderhover .navigation > ul > li.current-menu-ancestor > a:before {
    background-color:<?php echo esc_attr($MenuHoverColor); ?>;
}

/* Anchor */
a{ color:<?php echo esc_attr($lc); ?>; }
a:hover{ color:<?php echo esc_attr($lhc); ?>; }

/* Text Selection */
::-moz-selection { background: <?php echo esc_attr($hc); ?>; /* Firefox */ }
::selection { background: <?php echo esc_attr($hc); ?>; /* Safari */ }

.woocommerce #content input.button,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button,
.woocommerce-page #content input.button,
.woocommerce-page a.button,
.woocommerce-page button.button,
.woocommerce-page input.button,
.woocommerce.single-product .nice-select ul.list li:first-child:hover,
.woocommerce a.button,.woocommerce-page a.button,
.woocommerce a.button.alt,.woocommerce-page a.button.alt,
.woocommerce #respond input#submit.alt:hover,
.woocommerce a.button.alt:hover,
.woocommerce button.button.alt:hover,
.woocommerce input.button.alt:hover,
.woocommerce input.button#place_order,
.product.woocommerce.add_to_cart_inline a.added_to_cart,
#ep-modal.shown a[rel="next"]:hover,
#ep-modal.shown a[rel="prev"]:hover,
.widget.widget_woocommerce-wishlist a span.wishlist_items_number,
.vertical_menu_enabled .vertical_menu_area .widget.widget_woocommerce-dropdown-cart .cartContentsCount,
.woocommerce ul.products li.product a.added_to_cart, 
.woocommerce div.product .woocommerce-tabs ul.tabs li.active:before,
.woocommerce div.product .woocommerce-tabs ul.tabs li:before,
#product-fullview-thumbs .swiper-button-prev:hover:before,
#product-fullview-thumbs .swiper-button-prev:hover:after,
#product-fullview-thumbs .swiper-button-next:hover:before,
#product-fullview-thumbs .swiper-button-next:hover:after,
.woocommerce.single-product .nice-select ul.list li:first-child:hover,
#prev-product a[rel="next"]:hover,
#next-product a[rel="prev"]:hover,
.woocommerce ul.products.infoOnClick li.product span.show-hover,
div.woocommerce.single-product2 .product-fullview-thumbs .swiper-button-prev:hover:before,
div.woocommerce.single-product2 .product-fullview-thumbs .swiper-button-prev:hover:after,
div.woocommerce.single-product2 .product-fullview-thumbs .swiper-button-next:hover:before,
div.woocommerce.single-product2 .product-fullview-thumbs .swiper-button-next:hover:after,
.masonryBlog .swiper-button-prev:hover:after,
.masonryBlog .swiper-button-next:hover:after,
.masonryBlog .swiper-button-prev:hover:before,
.masonryBlog .swiper-button-next:hover:before,
.blog-masonry-container.ep_quote,
.progress_bar .progressbar_percent:after,
.progress_bar .progressbar_percent,
.post-meta .hr-extra-small.hr-margin-small,
.cblog .readmore_button:hover,
.touchevents #comment-text .button.button-large,
#comment-text .button.button-large:hover,
.touchevents .woocommerce #commentform .button.button-large,
.woocommerce #commentform .button.button-large:hover,
.testimonials .quot-icon,
.tabletBlog .moretag:hover, .desktopBlog .moretag:hover,
.pieChart .dot-container .dot,
.wpb_toggle.wpb_toggle_title_active:after, #content h4.wpb_toggle.wpb_toggle_title_active:after,
.team-member:hover .member-plus,
.iconbox.circle .icon span.glyph,
.iconbox.rectangle .icon span.glyph,
.custom-title .shape-container .hover-line,
.portfolioSection #loader:before, .portfolioSection #loader:after,
.showcase .item-list li span:before,
.toggleSidebar .cartSidebarHeader .cartContentsCount,
.lazy-load-hover-container:before, .lazy-load-hover-container:after,
.widget.widget_woocommerce-dropdown-cart li .qbutton.chckoutbtn,
header .widget.widget_woocommerce-dropdown-cart .cartContentsCount,
.woocommerce form.register input.button,
.woocommerce form.login input.button,
.woocommerce form.login input.button:hover,
.woocommerce form.register input.button:hover,
.woocommerce button.button.alt,
input[type="submit"].dokan-btn-theme,
a.dokan-btn-theme, .dokan-btn-theme,
input[type="submit"].dokan-btn-theme:hover,
a.dokan-btn-theme:hover,
.dokan-btn-theme:hover,
.dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li.active,
.dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li:hover,
.dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li.dokan-common-links a:hover,
input[type="submit"].dokan-btn-theme:hover,
.dokan-btn-theme:hover, input[type="submit"].dokan-btn-theme:focus,
a.dokan-btn-theme:focus, .dokan-btn-theme:focus, input[type="submit"].dokan-btn-theme:active,
a.dokan-btn-theme:active, .dokan-btn-theme:active, input[type="submit"].dokan-btn-theme.active,
a.dokan-btn-theme.active, .dokan-btn-theme.active, .open .dropdown-toggleinput[type="submit"].dokan-btn-theme,
.open .dropdown-togglea.dokan-btn-theme, .open .dropdown-toggle.dokan-btn-theme,
.widget-area .product-categories li.cat-item.current-cat > a:before,
#ep-modal .woocommerce #customer_login a.register-link:before,
.galleryexternallink {
    background-color: <?php echo esc_attr($acc); ?>;
}

.nice-select .option:hover,
.woocommerce .woocommerce-error a:hover, .woocommerce .woocommerce-message a:hover, .woocommerce .woocommerce-info a:hover,
table.compare-list .add-to-cart td a:hover,
.sidebar .widget_shopping_cart_content a.checkout.wc-forward.button,
.widget_shopping_cart_content a.wc-forward.button,
.nice-select .option:hover,
.toggleSidebar.cartSidebarContainer .cart-bottom-box .buttons a.checkout {
    background-color: <?php echo esc_attr($acc); ?> !important;
}

.woocommerce ul.products li.product a:hover h3,
.woocommerce ul.products li.product a:hover h2,
.woocommerce ul.products.infoOnClick li.product .hover-content a:hover h3,
.woocommerce ul.products li.product .price ins, .woocommerce-page ul.products li.product .price ins,
.woocommerce .cart-collaterals .cart_totals tr.order-total strong,
.woocommerce table.shop_table td.product-subtotal span,
.woocommerce table.shop_table form.woocommerce-shipping-calculator a,
.woocommerce div.product p.stock,
.woocommerce ul.products.instantShop li.product .compare.button:hover:before,
.product-buttons .shop_wishlist_button.wishlist-link:before,
.project-detail li:last-child .project-subtitle a:hover,
.blog-masonry-container .post-author-meta .post-author a:hover ,.blog-masonry-container .post-author-meta .meta-comment-count a:hover,
.star-rating span, .star-rating span,
.widget-area .product-categories li.current-cat > a,
.widget-area .product-subcategories li.current-cat > a,
table.compare-list .price td .amount,
li.product .product-buttons .compare.button:hover:before,
table.compare-list td ins .amount,
table.compare-list .stock td span,
.woocommerce ul.products li.product:not(.disable-hover):hover span:hover a, .woocommerce-page ul.products li.product:not(.disable-hover):hover span.product-button ~ span:hover a,
.product_meta> span a:hover,
.widget_ranged_price_filter li.current ,.widget_ranged_price_filter li.current a,
.widget_order_by_filter li.current, .widget_order_by_filter li.current a,
.woocommerce .widget_shopping_cart .total .amount, .woocommerce.widget_shopping_cart .total .amount,
.woocommerce ul.cart_list li .quantity, .woocommerce ul.product_list_widget li .quantity,
ul.cart_list li .amount, .woocommerce ul.cart_list li .amount, ul.product_list_widget li .amount,.woocommerce ul.product_list_widget li .amount,
.woocommerce form .form-row .required,
.woocommerce table.shop_table tfoot td, 
.woocommerce div.product form.cart table.group_table label a:hover,
.woocommerce .product .summary .price, .woocommerce-page .product .summary .price ,
div.woocommerce.single-product2 ul.products li.product .price,
div.woocommerce.single-product2 ul.products li.product .price ins,
div.woocommerce.single-product2 ul.products li.product .price del,
#blogSingle .social_links_list i.icon-facebook:hover,
#PDetail .social_links_list i.icon-facebook:hover ,
#portfolioDetailAjax .social_links_list i.icon-facebook:hover,
.ep-newsletter .widget_wysija_cont .wysija-submit:hover,
.comment-reply-title small a,
#blogSingle .post-tags a:hover,
#blogSingle span.post-author a:hover,
#blogSingle span.post-categories a:hover,
div.wpcf7-mail-sent-ok,
#respond-wrap  .graylabel.inputfocus , #respond .graylabel.inputfocus , #review_form .graylabel.inputfocus,
.wpcf7-form .graylabel.inputfocus,
.wpcf7-form .label.inputfocus , #respond-wrap .label.inputfocus , #respond .label.inputfocus , #review_form  .label.inputfocus,
.search-item .count,
.pageNavigation .more-link-arrow:hover,
.sticky .accordion_box10 .blogTitle , .sticky .accordion_box2 .accordion_title,
.iconPchart .icon,
.pieChart .perecent,
.wpb_toggle.wpb_toggle_title_active .title,
.vc_tta-accordion .vc_tta-panel:hover  span.vc_tta-title-text,
.vc_tta-accordion .vc_tta-panel.vc_active .vc_tta-icon ,
.vc_tta-accordion .vc_tta-panel.vc_active  span.vc_tta-title-text,
.team-member .more-link-arrow:hover,
.team-member .icons li:hover a,
.iconbox.transparentbackground .icon span.glyph,
.iconbox .glyph,
.portfolio_detail_creative .home:hover,
.lightStyle #portfoliSingle .like .jm-post-like.ep-icon:before,
#portfoliSingle .like .jm-post-like.liked.ep-icon:before,
.portfolioSection .like .jm-post-like.liked.ep-icon:before,
#portfoliSingle .like a.jm-post-like.ep-icon:hover:before,
.postphoto .like .jm-post-like.liked.ep-icon:before,
.isotope.lightStyle .postphoto .like .jm-post-like.liked.ep-icon:before,
.portfolio_text .portfolio_text_meta .like .jm-post-like.liked.ep-icon:before,
.cblog span.post-author a:hover,
.cblog span.post-comments a:hover,
.cblog h2.post-title a:hover,
.cblog span.post-categories a,
#PDetail span.icon-icons2:hover,
.showcase .showcase-item .showcase-link:hover,
.toggleSidebar.cartSidebarContainer .cart-bottom-box .total .amount,
#commentform p.logged-in-as a, .comment-edit-link, .comments-list .comment-reply-link,
.navigation-mobile a:hover,
ul li.woocommerce-MyAccount-navigation-link a:hover,
ul li.woocommerce-MyAccount-navigation-link.is-active a,
#ep-modal.quickview-modal #modal-content .product_title:hover,
.widget_product_tag_cloud.collapse .show_more_tags:hover,
.widget_product_categories .cats-toggle:hover,
.widget_product_categories .cats-toggle.toggle-active,
.footer-widgetized .product-categories li.current-cat > a{
    color: <?php echo esc_attr($acc); ?>;
}

.woocommerce div.product form.cart .group_table .woocommerce-grouped-product-list-item__price,
.woocommerce div.product form.cart .group_table .price,
.woocommerce .woocommerce-error a, .woocommerce .woocommerce-message a, .woocommerce .woocommerce-info a,
.woocommerce nav.woocommerce-pagination ul li a span , .woocommerce nav.woocommerce-pagination ul li span.current,
.woocommerce nav.woocommerce-pagination ul li:hover a, .woocommerce nav.woocommerce-pagination ul li:hover span,
.woocommerce p.stars.selected a:not(.active):before,
.woocommerce p.stars.selected a.active:before, .woocommerce p.stars:hover a:before,
.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a,
.woocommerce .woocommerce-breadcrumb a:hover,
.page-breadcrumb .woocommerce-breadcrumb a:hover,
.vc_tta-tab.vc_active a span,
.vc_tta-tab.vc_active .vc_tta-icon,
.yith-wcwl-add-to-wishlist .yith-wcwl-add-button a:hover,
.yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a:hover,
.yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a:hover,
.yith-wcwl-wishlistaddedbrowse:before, .yith-wcwl-wishlistexistsbrowse:before,
.yith-wcwl-wishlistaddedbrowse:hover,
.summary.entry-summary .button.compare.added:before,
.lang-sel ul.lang_link > li:hover a,
.summary.entry-summary .button.compare:hover {
    color: <?php echo esc_attr($acc); ?>!important;
}


.woocommerce .woocommerce-error a, .woocommerce .woocommerce-message a, .woocommerce .woocommerce-info a,
.ep-newsletter .widget_wysija_cont .wysija-submit:hover,
.iconbox.circle .icon span.glyph,
.iconbox.rectangle .icon span.glyph,
.showcase .item-pics:hover .swiper-button-prev:hover,
.showcase .item-pics:hover .swiper-button-next:hover,
input[type="submit"].dokan-btn-theme,
a.dokan-btn-theme, .dokan-btn-theme,
input[type="submit"].dokan-btn-theme:hover,
a.dokan-btn-theme:hover,
.dokan-btn-theme:hover,
.dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li.active,
.dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li:hover,
.dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li.dokan-common-links a:hover,
input[type="submit"].dokan-btn-theme:hover,
.dokan-btn-theme:hover, input[type="submit"].dokan-btn-theme:focus,
a.dokan-btn-theme:focus, .dokan-btn-theme:focus, input[type="submit"].dokan-btn-theme:active,
a.dokan-btn-theme:active, .dokan-btn-theme:active, input[type="submit"].dokan-btn-theme.active,
a.dokan-btn-theme.active, .dokan-btn-theme.active, .open .dropdown-toggleinput[type="submit"].dokan-btn-theme,
.open .dropdown-togglea.dokan-btn-theme, .open .dropdown-toggle.dokan-btn-theme,
.widget-area .product-categories li.cat-item.current-cat > a:before,
.widget-area .product-categories li.cat-item a:hover:before,
.widget-area .product-categories li.cat-item a:hover:before,
.galleryexternallink {
    border-color:<?php echo esc_attr($acc); ?>;
}

.woocommerce .blockUI.blockOverlay:after,
.woocommerce .loader:after,
table.compare-list .remove td .blockUI.blockOverlay:after,
.summary.entry-summary .button.compare .blockUI.blockOverlay:after,
.woocommerce .yith-woocompare-widget .products-list .blockUI.blockOverlay:after,
.woocommerce #respond input#submit.loading:after,
.woocommerce button.button.loading:after,
.woocommerce input.button.loading:after,
.woocommerce a.button.loading:after,
.wc-loading:after,
.showcase .swiper-button-prev:hover:before,
.showcase .swiper-button-next:hover:before,
.mejs-overlay-loading:after {
    border-right-color : <?php echo esc_attr($acc); ?>;
}

.sticky .blogAccordion .rightBorder {
    border-right-color:<?php echo esc_attr($acc); ?> !important;
}

.testimonials .quot-icon:before,
.testimonials:after,
.testimonials:before,
.vc_tta-tabs-position-bottom li.vc_tta-tab:hover,
.vc_tta-tabs-position-bottom li.vc_tta-tab.vc_active,
.showcase .swiper-button-prev:hover:before,
.showcase .swiper-button-next:hover:before {
    border-top-color: <?php echo esc_attr($acc); ?>;
}


.woocommerce-account .woocommerce-MyAccount-content .form-row input.input-text:focus,
.woocommerce-account .woocommerce-MyAccount-content .form-row textarea:focus,
.woocommerce form.login input.input-text:focus,
.woocommerce form.register input.input-text:focus,
.woocommerce form.checkout_coupon .form-row-first input:focus,
.woocommerce form.checkout .form-row input.input-text:focus, .woocommerce form.checkout .form-row textarea:focus,
.testimonials .quot-icon:after,
.testimonials .quot-icon:before,
.wpcf7-form-control-wrap input[type="email"]:focus,
.wpcf7-form-control-wrap input[type="text"]:focus ,
.wpcf7-form-control-wrap textarea:focus,
#respond-wrap .input-text input:focus,
#respond-wrap .input-textarea textarea:focus ,
#respond .input-text input:focus,
#respond .input-textarea textarea:focus ,
#review_form input:focus,
#review_form  textarea:focus,
.vc_tta-tabs-position-left li.vc_tta-tab.vc_active,
.vc_tta-tabs-position-right li.vc_tta-tab.vc_active,
.vc_tta-tabs-position-top li.vc_tta-tab.vc_active,
.vc_tta-tabs-position-top li.vc_tta-tab.vc_active:hover,
.vc_tta-accordion .vc_tta-panel.vc_active .vc_tta-panel-heading,
.vc_tta-accordion .vc_tta-panel:hover .vc_tta-panel-heading,
.wpb-js-composer .vc_tta.vc_general.vc_tta-accordion .vc_tta-panel.vc_active .vc_tta-panel-heading,
.wpb-js-composer .vc_tta.vc_general.vc_tta-accordion .vc_tta-panel:hover .vc_tta-panel-heading,
.custom-title .shape-container.triangle .shape-line, 
.custom-title .shape-container.triangle .shape-line:after, 
.custom-title .shape-container.triangle .shape-line:before {
    border-bottom-color:<?php echo esc_attr($acc); ?>;
}

.wpb_heading,
.testimonials .quot-icon:before,
.textLeftBorder.fontSize123 .title,
.textLeftBorder .title {
    border-left-color : <?php echo esc_attr($acc); ?>;
}

.touchevents #comment-text .button.button-large,
#comment-text .button.button-large:hover,
.touchevents .woocommerce #commentform .button.button-large,
.woocommerce #commentform .button.button-large:hover,
.woocommerce .widget_layered_nav ul.imagelist li.chosen a img,
#product-fullview-thumbs .swiper-button-prev:hover, #product-fullview-thumbs .swiper-button-next:hover,
div.woocommerce.single-product2 .product-fullview-thumbs .swiper-button-prev:hover,
div.woocommerce.single-product2 .product-fullview-thumbs .swiper-button-next:hover,
.tabletBlog .moretag:hover, .desktopBlog .moretag:hover,
.custom-title .shape-container.square .shape-line ,
.custom-title .shape-container.rotated_square .shape-line , 
.custom-title .shape-container.circle .shape-line {
    border-color:<?php echo esc_attr($acc); ?>;
}


<?php
//woocommerce
if(class_exists('Woocommerce'))
{
    if(is_product())
    {

        if(epico_get_meta('product_detail_style_inherit') == '1')
        {
            $pd_bg = epico_get_meta('product_detail_bg'); // Product detail bg in product page
        }
        else
        {
            $pd_bg =  epico_opt('product-detail-bg');// Product detail bg in theme settings
        }

        ?>
        /* woocomerce */ 
        .pd_background.main-content.pageTopSpace,
        .pd_top.main-content.pageTopSpace,
        body.single .layout .pd_background .product-detail-bg,
        body.single .layout .pd_top .product-detail-bg {
            background-color:<?php echo esc_attr($pd_bg); ?>;
        }

        <?php
    }
}
?>

/* topbar */
<?php
if ( epico_opt('topbar_bg_color')) {  ?>

#topbar  {
    background-color: <?php echo esc_attr($topbar_bg_color); ?>
}

<?php } 
if ( epico_opt('topbar_border_color')) {  ?>

#topbar {
    border-bottom-color: <?php echo esc_attr($topbar_border_color); ?>
}

<?php } ?>


/* preloader */
<?php

if ( epico_opt('preloader_bg_color')) {  ?>

#preloader {
    background-color: <?php echo esc_attr($preloader_bg_color); ?>
}

<?php } ?>

<?php if ( epico_opt('preloader_color')) {  ?>

#preloader .ball {
    background:<?php echo esc_attr($preloader_color); ?>;
}

.preloader_circular .path {
    stroke:<?php echo esc_attr($preloader_color); ?>;
}

#preloader-simple .rect {
    stroke:<?php echo esc_attr($preloader_color); ?>;
}

#preloader_box .rect {
    stroke:<?php echo esc_attr($preloader_color); ?>;
}

<?php } ?>

<?php if ( epico_opt('preloader_box_color')) {  ?>

#preloader_box {
    background: <?php echo esc_attr($preloader_box_color); ?>
}

<?php } ?>

<?php if ( epico_opt('preloader_text_color')) {  ?>

.preloader-text {
    color: <?php echo esc_attr($preloader_text_color); ?>
}

<?php } ?>

<?php 
$footerwidgetbanner = epico_opt('footer-widget-banner');

if ($footerwidgetbanner != '') {
?>

.footer-widgetized .section-container {
    background: <?php if($footerwidgetbanner) { ?> url(<?php echo esc_url($footerwidgetbanner); ?>) repeat bottom center <?php } ?> !important;
    background-size: cover !important;
}

<?php }
$footerwidgetBgColor = epico_opt('footer-widget-color');
if ($footerwidgetBgColor != '') {
?>
.footer-widgetized .section-container:before {
    background-color: <?php echo esc_attr($footerwidgetBgColor); ?>;
    content : "";
    position:absolute;
    width:100%;
    height:100%;
    top:0;
    left:0;
    z-index:0;
}
<?php } ?>



/*######## Set font ########*/

/* General font */
<?php
//Fonts
$bodyFontType = epico_opt('font-body-type');
$bodyFont = key((Array)json_decode(epico_opt('font-body')));
$bodyCustomFontUrl = epico_opt('custom-font-url-body');
$bodyCustomFontName = epico_opt('custom-font-name-body');

$navFontType  = epico_opt('font-navigation-type');
$navFont  = key((Array)json_decode(epico_opt('font-navigation')));
$navCustomFontUrl = epico_opt('custom-font-url-navigation');
$navCustomFontName = epico_opt('custom-font-name-navigation');

$headFontType = epico_opt('font-headings-type');
$headFont = key((Array)json_decode(epico_opt('font-headings')));
$headCustomFontUrl = epico_opt('custom-font-url-headings');
$headCustomFontName = epico_opt('custom-font-name-headings');

$ShortcodeFontType = epico_opt('font-shortcode-type');
$ShortcodeFont = key((Array)json_decode(epico_opt('font-shortcode')));
$ShortcodeCustomFontUrl = epico_opt('custom-font-url-shortcode');
$ShortcodeCustomFontName = epico_opt('custom-font-name-shortcode');
?>

/*  lato font is set by default  */
<?php if ( $bodyFontType !== 'default') { ?>
body,
.footer-bottom .copyright,
.footer-bottom .socialLinkShortcode.textstyle a span,
.woocommerce .woocommerce-ordering select, .woocommerce-page .woocommerce-ordering select,
.woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span,
.woocommerce .woocommerce-product-rating .woocommerce-review-link,
.woocommerce td.product-name dl.variation,
.woocommerce-checkout #payment div.payment_box,
.widget.widget_woocommerce-dropdown-cart .wc_cart_product_info .wc_cart_product_name,
.woocommerce table.shop_table tr .shipping-td p,
.wpb_content_element,
.preloader-text,
.toggleSidebar .cartSidebarWrap ul.cart_list li dl.variation,
#fullScreenSlider .arrows-button-prev .text, #fullScreenSlider .arrows-button-next .text,
.swiper-slide .caption-subtitle,
#fullScreenImage .caption-subtitle,
.swiper-slide .caption-subtitle2,
#fullScreenImage .caption-subtitle2,
.cblog span.post-categories,
.cblog .post-date,
.widget-area .product-categories li a,
.widget-area .product-categories li.cat-item a,
.footer-widgetized .product-categories li a,
.cblog span.post-info-separator,
.cblog span.post-author a,
.cblog span.post-comments a,
.cblog .post-content li, .singlePost .post li,
.cblog .post-content p ,
.singlePost .post p,
.postphoto .title,
.portfolio_text .portfolio_text_meta .like a,
.portfolio_text .portfolio_text_meta .like a .no_like,
.postphoto .like a,
.postphoto .like a .no_like,
.subnavigation li .filter_item .post-count,
#portfoliSingle .like a,
ul.portfolio-filter li ul li .filter_item,
.imageBox .content .text,
.textBox .text,
.iconbox .content,
.iconbox .more-link a,
.team-member .member-info cite,
.team-member .member-info p,
.pieChartBox .subtitle,
#commentform p:first-child,
.blogAccordion.quoteItem .quote_content,
.tabletBlog  .moretag, .desktopBlog .moretag,
.blogAccordion .leftBorder .monthYear,
.blogAccordion .accordion_title,
.desktopBlog .accordion_content p,
.not_found_page p ,
.pricing-box .plan-price,
.wpcf7-form-control-wrap input[type="email"],
.wpcf7-form-control-wrap input[type="text"],
.input-text input[type="text"],
.wpcf7-form-control-wrap textarea,
.input-textarea textarea,
.footer-widgetized .progressbar .title ,
footer .simple-menu li a,
.simple-menu li a,
#search-form input[type="text"],
.testimonial blockquote ,
.testimonial .name,
.testimonials .quote .job,
.page-title ,
#blogSingle span.post-categories,
#blogSingle span.post-categories a,
#blogSingle .post-date,
#blogSingle span.post-info-separator,
#blogSingle span.post-author a,
#blogSingle .post-tags,
#blogSingle .post-content
.comments-list .comment-date,
.progress_bar .progress_title,
.progress_percent_value ,
.vc_gitem-post-data h4,
.vc_gitem-post-data.vc_gitem-post-data-source-post_title div,
.ep_button,form input[type="submit"] ,
.ep_button ,
strong ,
.topbarText,
.topbar_login_link .topbar_login_text a,
.woocommerce .shop-filter .special-filter ul.product-subcategories li a,
.woocommerce .shop-filter .widget_layered_nav_filters ul li a,
.woocommerce .shop-filter .special-filter .woocommerce-result-count,
.woocommerce .widget_layered_nav_filters ul li a,
.woocommerce .widget_layered_nav ul li a,
.woocommerce .shop-filter .widget-area .on-sale-filter li a,
.widget_ranged_price_filter li,
.widget_ranged_price_filter li a,
.widget_order_by_filter li,.widget_order_by_filter li a,
.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
.woocommerce-page .tagcloud a,
.tagcloud a,
.widget_recent_comments .comment-author-link,
.widget_recent_entries .post-date,
.footer-widgetized a,
.woocommerce .product_list_widget li a,
.widget_recent_entries a,.footer-widgetized-wrap a, .footer-widgetized-wrap .widget_recent_entries a,
.widget-area a ,
ul.cart_list li .amount, .woocommerce ul.cart_list li .amount, ul.product_list_widget li .amount,.woocommerce ul.product_list_widget li .amount,
.woocommerce .widget_layered_nav_filters ul li a , .woocommerce .widget_layered_nav ul li a,
.woocommerce.widget_rating_filter .rating_product_count,
.woocommerce .widget_layered_nav ul li .count,
.woocommerce ul.products li.product.product-category h3 span,
.instagram-feed .info span,
.textwidget,.footer-widgetized-wrap .textwidget,
header .navigation li.mega-menu-parent > .menu-item-wrapper > ul > li.special-last-child > ul > li:last-of-type .subtitle,
.woocommerce #reviews #comments ol.commentlist li .comment-text p.meta time,
#review_form_wrapper form .comment-form-rating label,
.lg-sub-html p,
.widget_wysija_cont .wysija-submit,
.widget .widget_wysija_cont .wysija-submit {
    <?php
    if( $bodyFontType == 'google')
    {
        ?>
        font-family:'<?php echo esc_html($bodyFont); ?>', sans-serif;
        <?php
    }
    else // custom font
    {
        ?>
        font-family:<?php echo $bodyCustomFontName; ?>;
        <?php
    }
    ?>
}
<?php } ?>

/* Heading & titles */
/* poppins is set by default  */
<?php if ( $headFontType !== 'default') { ?>
h1,h2,h3,h4,h5,h6,
.titleSpace .title h3 ,
#header h1,
.vertical_menu_enabled .vertical_menu_area .vertical_menu_navigation a,
.toggleSidebarWidgetbar .widget-area .widget_nav_menu a,
.widget-area .search-form input[type="text"],
.toggleSidebar.cartSidebarContainer .cart-bottom-box .buttons a,
.toggleSidebar.cartSidebarContainer .cart-bottom-box .total ,
.toggleSidebar.cartSidebarContainer .cartSidebarWrap .cart_list li a,
.toggleSidebar.cartSidebarContainer .cartSidebarWrap .cart_list .empty.show-message,
.toggleSidebar .cartSidebarWrap ul.cart_list li .quantity ,
.woocommerce ul.products li.product h3, .woocommerce-page ul.products li.product h3 ,
.toggleSidebar .cartSidebarHeader,
.subnavigation .filter_item,
.swiper-slide .caption-title,
#fullScreenImage .caption-title,
.desktopBlog .blogAccordion .accordion_box10 .blogTitle,
.cblog h2.post-title a, .cblog h2.post-title, #blogSingle h1.post-title,
.cblog .readmore_button,
.cblog .quotePostType .post-image .quote_content h3,
#blogSingle .quotePostType .post-image .quote_content h3,
.portfolioSection .title h3,
.portfolio_text .portfolio_text_meta .right_meta,
.portfolio_text .portfolio_text_meta .right_meta .title,
.postphoto .overlay .hover-title,
.portfolio_detail_creative .pd_creative_fixed_content .title_container .title,
.vc_tta-tab a span ,
.blogAccordion .accordion_title .day ,
.readmore .loadMore ,
.post-pagination span,
.post-pagination a,
.footer-widgetized .widget-title,
.commentsCount,
.comment-reply-title,
.lg-sub-html h4,
#lg-counter,
span.slideshowContent  .firstTitle, .secondTitle,
.blog-masonry-container.ep_quote .blog-masonry-content .blog-excerpt, 
#blogSingle .nextNav span.postTitle,
#blogSingle .prevNav span.postTitle ,
#PDetail .nextNav span.postTitle,
#PDetail .prevNav span.postTitle,
.woocommerce ul.products li.product .onsale, .woocommerce-page ul.products li.product .onsale , .woocommerce span.onsale, .woocommerce-page span.onsale,
.woocommerce div.product p.stock,
.woocommerce div.product form.cart .variations label,
.woocommerce .product .summary .single_variation_wrap .woocommerce-variation-price .price,
.woocommerce div.product form.cart ul.variations li .label.image-label  label ,.woocommerce div.product form.cart ul.variations li .label.image-label .attr-value ,
.woocommerce .product .summary .single_variation_wrap .woocommerce-variation-availability .stock.in-stock,
.woocommerce #content input.button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, 
.woocommerce form.login input.button,
.woocommerce-page #content input.button, .woocommerce-page #respond input#submit, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button,
.woocommerce #content div.product .woocommerce-tabs ul.tabs li a, .woocommerce div.product .woocommerce-tabs ul.tabs li a, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li a, .woocommerce-page div.product .woocommerce-tabs ul.tabs li a ,
.woocommerce.woocommerce-result-count, .woocommerce-page .woocommerce-result-count ,
.woocommerce .product .summary .price, .woocommerce-page .product .summary .price ,
.woocommerce div.product p.price ins, .woocommerce div.product span.price ins,
.woocommerce div.product p.price del, .woocommerce div.product span.price del,
.woocommerce div.product form.cart .group_table .price ,
.woocommerce table.shop_table, .woocommerce-page table.shop_table ,
.woocommerce input[type='text'], .woocommerce input[type='password'], .woocommerce input[type='email'], 
.woocommerce-page input[type='text'], .woocommerce-page input[type='password'], .woocommerce-page input[type='email'],
.woocommerce input[type='tel'], .woocommerce textarea, .woocommerce-page textarea ,
.woocommerce #reviews h3,
.woocommerce ul.product_list_widget li .reviewer ,
.woocommerce ul.cart_list li .quantity, .woocommerce ul.product_list_widget li .quantity ,
.woocommerce .widget_shopping_cart .total strong, .woocommerce.widget_shopping_cart .total strong,
.woocommerce .widget_shopping_cart .total .amount, .woocommerce.widget_shopping_cart .total .amount ,
form.woocommerce-product-search input[type="search"] ,
.woocommerce .widget_price_filter .price_slider_wrapper .price_label , .woocommerce-page  .widget_price_filter .price_slider_wrapper .price_label,
.widget_price_filter .price_slider_amount .button,
.woocommerce ul.products li.product span.product-button .txt , .woocommerce-page ul.products li.product span.product-button .txt,
.select2-container .select2-choice > .select2-chosen ,
.out_of_stock_badge_loop,
.woocommerce div.product .out-of-stock,
.woocommerce-ordering .select2-container .select2-choice,
.woocommerce-checkout form.login p,
form.woocommerce-checkout p,
.woocommerce form.login .lost_password,
.woocommerce form .form-row label,
.woocommerce-checkout #payment ul.payment_methods li label,
.compare-list.dataTable p ,
table.compare-list .stock td span ,
table.dataTable tr,
table.compare-list  tr th:first-child,
.yith-woocompare-widget li,
.yith-woocompare-widget a.clear-all,
.yith-woocompare-widget ul.products-list a:not(.remove),
.widget-area .widget-title ,.widget-area .widgettitle ,
table.compare-list {
    <?php
    if( $headFontType == 'google')
    {
        ?>
        font-family:'<?php echo esc_html($headFont); ?>', sans-serif;
        <?php
    }
    else // custom font
    {
        ?>
        font-family:<?php echo $headCustomFontName; ?>;
        <?php
    }
    ?>
}
<?php } ?>


/* Shortcode title font */

<?php if ( $ShortcodeFontType !== 'default') { ?>
.socialLinkShortcode.textstyle a span,
.ep_button, 
.widget_wysija_cont .updated,
.widget_wysija_cont .error,
.widget_wysija_cont .xdetailed-errors,
.banner .title,
.textBox .title,
.textBox .subtitle,
.imageBox .title .subtitle,
.custom-title .title,
.team-member .member-info .member-name,
.counterBox  .counterBoxNumber,
.counterBoxDetails,
.counterBoxDetails2,
.pieChartBox .title,
.pieChart .perecent,
.testimonials .quote .name ,
.testimonials .quote .job ,
.progress_bar .progress_title,
.progress_percent_value,
.showcase h3,
.showcase .showcase_item_subtitle,
.showcase .item-list li span,
.imageBox .content .title ,
.iconbox.iconbox-top .title,
.iconbox.iconbox-left .title,
.custom-iconbox .title,
.wpb_toggle .title,
.vc_separator ,
.animatedtext .slideshowContent,
.animatedtext .secondTitle2,
.wpcf7-form .label , #respond-wrap  .label , #respond .label , #review_form  .label ,
.wpcf7-form .graylabel , #respond-wrap .graylabel , #respond .graylabel , #review_form .graylabel,
.time-block span.number {
    <?php
    if( $ShortcodeFontType == 'google')
    {
        ?>
        font-family:'<?php echo esc_html($ShortcodeFont); ?>', sans-serif;
        <?php
    }
    else // custom font
    {
        ?>
        font-family:<?php echo $ShortcodeCustomFontName; ?>;
        <?php
    }
    ?>
}
<?php } ?>


/* Navigation */

<?php if ( $navFontType !== 'default') { ?>
.topbar_wishlist .wishlist_text,
.topbar_lang_flag .lang-sel a,
header .navigation > ul > li li > a span.menu_title_wrap,
header .navigation > ul > li > a,
.menu-list a span,
header .navigation li.mega-menu-parent div > ul > li.menu-item-has-children > a {
    <?php
    if( $navFontType == 'google')
    {
        ?>
        font-family:'<?php echo esc_html($navFont); ?>', sans-serif;
        <?php
    }
    else // custom font
    {
        ?>
        font-family:<?php echo $navCustomFontName; ?>;
        <?php
    }
    ?>
}

<?php }

$socialcolor1 = epico_opt('social_custom1_color');
if ( epico_opt('social_custom1_color')) {
?>
.socialLinkShortcode.custom1 a:before{
    background: <?php echo esc_attr($socialcolor1); ?>
}

<?php } 

$socialcolor2 = epico_opt('social_custom2_color');
if ( epico_opt('social_custom2_color')) {
?>
.socialLinkShortcode.custom2 a:before{
    background: <?php echo esc_attr($socialcolor2); ?>
}
<?php }

$socialLogo1 = epico_opt('social_custom1_image');
$socialLogo2 = epico_opt('social_custom2_image');
?>

<?php
if($socialLogo1 != '')
{
?>
span.icon.icon-custom1{
    background-image: url("<?php echo esc_url($socialLogo1); ?>");
}
<?php
}

if($socialLogo1 != '')
{
?>
span.icon.icon-custom2{
    background-image: url("<?php echo esc_url($socialLogo2); ?>");
}
<?php
}
?>

/* Snap to scroll */
#snap-to-scroll-nav span:after {
    background:<?php echo esc_attr($acc); ?>;
}


/*######## Style Overrides ########*/

<?php epico_eopt('additional-css'); ?>
