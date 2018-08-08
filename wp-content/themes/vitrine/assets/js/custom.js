; (function ($, window, document, undefined) {

    'use strict';

    function VitrineTheme() {
        var self = this;

        self.$window = $(window);
        self.$document = $(document);
        self.$body = $('body');
        self.windowHeight = self.$window.height();
        self.windowWidth = self.$window.width();
        self.documentHeight = self.$document.height();
        self.msie = window.navigator.userAgent.indexOf("self.msie ");
        self.msie11 = navigator.userAgent.match(/Trident.*rv\:11\./);
        self.isTouchDevice = (Modernizr.touchevents) ? true : false;
        self.isMobile = (self.isTouchDevice && self.windowWidth <= 767) ? true : false;
        self.isTablet = (self.isTouchDevice && self.windowWidth >=768 && self.windowWidth <= 1140) ? true : false;

        // Blog page number
        self.blogPageNum = 0;
        //Header
        self.$epHeader = $("#epHeader");

        self.scrolingToSection = false;
        self.externalClicked = false;
        self.$scrolId; // this value Save Value of specific id From top
        self.enableScrollId;
        //menu
        self.menuArray = [];
        //portfolio Options
        self.pageRefresh = true;
        self.content = false;
        self.ajaxLoading = false;
        self.wrapperHeight;

        // Djax
        self.djax;
        self.assets = {
            script: [],
            css: []
        }

        self.$scrollpals = $("html, body");
        self.wcNoticeTimer;
        self.resizeId;

        self.sliderWidth;
        self.imagePosition;

        self.projectContainer;
        self.pDError = $('#pDError');
        self.pDetailNav = $('.navWrap');

        self.$portfolioID;
        self.$portfolioBackLink;
        self.$skills;

		self.account_popup();

        // coolies Bar  
		self.cookiesBar();

        // Initialize scripts
        self.init();

    };

    VitrineTheme.prototype = {

        /**
         *  Initialize
         */
        init: function () {
            var self = this;

            self.initExtensions();
            self.bind();

        },

        initExtensions: function () {
            var self = this;

            self.page_transition();

            self.scriptUpdateActions();

            self.lazyLoadOnLoad('#main-content, .toggleSidebar');

            self.fix_IOS_double_tap_issue();

            self.embed_video_lightgallery();
            self.product_gallery_popup_lightGallery();
            self.snap_to_scroll();

            self.djaxifyRequests();

            self.portfolio_next_prev();

            self.homeHeight();

            self.epico_singlePage();

            //portfolio Feature Image Slider
            self.portfolioLike();
            self.portfolioSlider();

            //Run gallery
            self.galleryStart();
			
            // carosel gallery 
            self.carousel_gallery();

			//sisde bar category widget toggle
			self.cat_widget_update();
			self.cat_widget();

            //shortcodes
            self.epico_shortcode(false);

            //portfolio & portfolio details Functions
            // id 0 for First Load
            self.portfolioIsotope(0);
            self.portfolioDHashChange();//Portfolio Detail Run When Hash Change functions
            self.portfolioLoadMore();//portfolio Load more Function
            self.pDNavigationNext();// linking to Next Portfolio Detail 
            self.pDNavigationPrevious(); // linking to Previous Portfolio Detail 
            self.Social_link(); // social links in Portfolio Detail 
            self.pDCloseProject(); // close Portfolio Detail
            self.pDInitialize();
            self.portfolioDetailNavigationLoading();


            //blog Functions 
            self.epico_blogMasonry();//Masonry blog

            self.blogLoadMore();//Blog Load More Function
            self.blogToggle();//Blog Toggle
            self.blogPostSlider();// Blog post Slider

            self.parallaxImg();//section parallax
            self.interactiveBackgroundImg();

            self.initVideoBackground();
            self.videoBackgroundSize();

            self.fitVideo();//video Fit To All Screen
            self.commentRespond();

            // WPML MENU
            self.wpml_menu();

            self.nav();

            self.initialMenuArray();
            self.updateMenuOnActiveSection();

            self.epico_carousel();
            self.shortcodeAnimation();

            //Ajaxify Search form
            self.ajaxify_search();

            self.minPageHeightSet();

            //woocomerce
            self.product_detail_height();
            self.product_thumbnails();
            self.addToCartEvents();
            self.woocommerce_filter();
            self.runIsotopeInProducts();
            self.single_product2_slider();
            self.products_infoOnclick();
            self.single_product2_scrollbar();
            self.woocommerce_cats();
            self.product_tabs();
            self.product_variation();
            self.product_quickview();
            self.product_image_zoom();
            self.product_hover();
            self.product_quantity();
            self.wishlist_widget_update();
            self.wishlist_button();
            self.card_widget_update();
            self.sync_fixed_add_to_cart();
            self.fixed_add_to_cart_functionality();
            self.product_next_prev_button();
            self.wishlist_remove();
            self.woocommerce_ajax_wrapper();
            self.disable_price_slider_keydown_event();
            self.search_box_toggle();// product Search box 
            self.initSelectElements();
            self.woocommerce_variation_attributes();
            self.product_size_guide();
            self.show_more_tag();// show more tag button for tag widget

            if ($('#fullScreenSlider').length) {
                setTimeout(function () {
                    self.fullScreenSliderInit();
                    self.fullScreenSlider();
                }, 600);
            }

            if ($('#fullScreenImage').length) {
                setTimeout(function () {
                    self.fullScreenImageInit();
                }, 600);
            }

            self.mobileNavigation();

            self.togglesidebar();    // Toggle sidebar
            self.togglesidebar_scrollbar();  //toggle sidebar Scrool bar
            self.toggle_sidebar_menu();
            self.coveringLevelVerticalMenu();    //vertical menu - covering level
            self.addToCart();    // open toggle sidebar when click On Add to cart Buttons
            self.addToCart_variation_group(); // Adding to cart variation Product

            //wpadminbar And topbar
            self.epico_topbar();
			
			//Topbar language
			self.epico_topbar_lang();

            //User additional script
            self.epico_additionalScript();

            //Scrolling  
            self.epico_scrolling();

            //social share's pop up 
            self.social_share_pop_up();

            //contact form 7
            self.contactform7();
			
            //remove footer in creative portfolio detail
            self.remove_footer_creative_portfolio_detail();

            //remove left/right menu in creative portfolio detail
            self.remove_left_right_menu_creative_portfolio_detail();

            //portfolio detail title
            self.portfolio_detail_title();

            //Search form
            self.search_form();

            if ($('#googleMap').length)
                self.googleMap(); // Footer Google Map

            self.scrollToTopButton();

            //Header transform
            self.headerTransformation();

            // compare
            self.compare();

            //Hide the preloader
            self.preloader_hide();

            // instagram animation
            self.instagramAnimation();

            // Detect scrollbar width
            self.getScrollBarWidth();

            self.lazyLoadOnHover();

            self.wc_notices();
            self.disable_djax_on_cart_page();
            self.update_widget_cart_on_cart_page();


            //Slider parallax
            self.sliderParallax();

            //Custom title
            self.customTitleParallax();

            self.portfolio_detail_header_parallax();

            //Fixed add to cart
            self.fixed_add_to_cart_visibility();


       
            if(!($('.woocommerce-checkout,.woocommerce-cart').length)) {

                $('.woocommerce-error').insertAfter('#epHeader'); //replace woocomerce error in all pages except check out page and cart page 
                $(".woocommerce-error li:first").before('<a class="remove_error_message" href="#"></a>'); // ADD remove button for error message

            }

            // remove button functionality for error message 
            $(".woocommerce-error .remove_error_message").on('click', function (e) {
                e.preventDefault();
                $(".woocommerce-error").remove();
            });


        },

        bind: function () {
            var self = this;
            //bind scroll functions
            self.$window.scroll(function () {

                //Activate menu item
                self.updateMenuOnActiveSection();

            });

            //bind resize functions
            self.$window.resize(function () {

                self.update_doc_height();
                self.update_win_dimension();

                self.product_detail_height();

                if ($('#fullScreenSlider').length)
                    self.fullScreenSliderInit();

                if ($('#fullScreenImage').length)
                    self.fullScreenImageInit()

                self.fullWidthSection(); // FullWidth colorize Section - shortcode

                self.homeHeight();
                self.minPageHeightSet();

                // blog toggle
                $('.blogAccordion').each(function () {
                    var postVar = self.blogToggleArray($(this));
                    // set toggle mode When Page Loaded
                    self.blogToggleSet(postVar);
                });

                self.nav();

                // id 1 Portfolio Resize 
                self.portfolioIsotope(1);

                self.runIsotopeInProducts();

                self.videoBackgroundSize();

                // Mobile Menu
                self.mobileNavigationContainerHeight();

                //Set showcases height
                self.showcase_height();

                // set margin for Creative portfolio detail
                self.set_margin_creative_portfolio_detail();


                self.epico_blogMasonry();

                //Use settimeout to run function only when resizing is finished
                if ($('.carousel').length > 0) {
                    clearTimeout(self.resizeId);
                    self.resizeId = setTimeout(function () {
                        self.updateCarousel();
                    }, 300);
                }

            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  Update document height, window height & width
        /*-----------------------------------------------------------------------------------*/
        update_doc_height: function () {
            var self = this;
            self.documentHeight = Math.max(
                self.$document.height(),
                self.windowHeight,
                document.documentElement.clientHeight /* For opera: */
            );

            self.$window.trigger('document-height-changed');
        },

        update_win_dimension: function () {
            var self = this;
            self.windowHeight = self.$window.height();
            self.windowWidth = self.$window.width();
        },
        /*-----------------------------------------------------------------------------------*/
        /*  Set product detail appropriate height
        /*-----------------------------------------------------------------------------------*/

        product_detail_height: function () {
            var self = this;

            var topSpace = self.pageTopSpace();

            if (self.windowHeight <= 1000 ) {
                // min -height for bg product detail 
                $('.woocommerce div.product.pd_background .product-detail-bg').css("min-height", self.windowHeight - topSpace);
            }
           

            if ($('div.product.pd_top').length <= 0)
                return;


            // max-height - top product detail 
            topSpace = topSpace + 60; // 60 is breadcrumb height+ paddings

            if ($('#product-thumbs').length > 0) {
                topSpace = topSpace + 100; // 100 is height of product thumbs
            }
            $('.woocommerce div.product.pd_top div.images img').css("max-height", self.windowHeight - topSpace);

        },
        
        /*-----------------------------------------------------------------------------------*/
        /*  Show More tag
        /*-----------------------------------------------------------------------------------*/

        show_more_tag: function () {

            if ($('.widget_product_tag_cloud .tagcloud').length <= 0)
                return;

            var $product_tag_height;
            $product_tag_height = $('.widget_product_tag_cloud .tagcloud').height();
            if ($product_tag_height > 80 ) {
                $('.widget_product_tag_cloud').addClass("collapse");
                $('.widget_product_tag_cloud .tagcloud').after("<span class='show_more_tags'>Show More</span>");
            }

            $('.widget_product_tag_cloud .show_more_tags').on('click', function (e) {
                e.preventDefault();
                $('.widget_product_tag_cloud').removeClass("collapse");
            });

        },

        /*-----------------------------------------------------------------------------------*/
        /*  Product detail size guide
        /*-----------------------------------------------------------------------------------*/

        product_size_guide: function () {
            var self = this;
			var $size_guide = $('#ct_size_guide,.ct-size-guide');
			
            if(!self.$body.hasClass('single-product'))
                return;

            var $size_guide_modal = self.$document.find('#ep-modal'),
                $size_guide_wrapper = $size_guide_modal.find('.modal-content-wrapper'),
                $size_guide_content = $size_guide_modal.find('#modal-content');

            if ($size_guide_modal.length <= 0 || $size_guide.length <= 0 || $size_guide.hasClass('ct_sg_tabbed'))
                return;

            $('.button_sg').on('click', function (e) {
                e.preventDefault();

                var $this = $(this);

                $size_guide_modal.addClass('hidden-nav');

                self.$body.addClass('modal-open'); // disable scrollbar
                $size_guide_modal.addClass('size-guide-modal');

                if (!$size_guide_modal.removeClass('closed').hasClass('open')) {
                    $size_guide_modal.removeClass('loading').addClass('open');
                }

                var $data = $size_guide.removeClass('mfp-hide');

                $size_guide_content.html($data);
                $size_guide_modal.addClass('shown').prepend('<div class="mfp-bg"></div>'); // content is ready, so show it
            });


            // Close quickview by click outside of content
            $size_guide_modal.on('click', function (e) {
                if (!$size_guide_content.is(e.target) && $size_guide_content.has(e.target).length === 0 ) {
                    self.close_size_guide();
                }
            });

            // Close quickview by click close button
            self.$document.on('click', '#ep-modal.size-guide-modal #modal-close', function (e) {
                e.preventDefault();
                self.close_size_guide();
            });

            // Close box with esc key
            self.$document.keyup(function (e) {
                if (e.keyCode === 27)
                    self.close_size_guide();
            });
        },

        close_size_guide: function () {
            var self = this;

            var $size_guide_modal = self.$document.find('#ep-modal.size-guide-modal'),
                $size_guide_content = $size_guide_modal.find('#modal-content');

            $size_guide_modal.removeClass('shown loading open').addClass('closed');

            setTimeout(function () {
                self.$body.removeClass('modal-open');
                $size_guide_modal.removeClass('size-guide-modal');
            }, 300)

            setTimeout(function () {
                var $data = $size_guide_content.html();
                $('#main').append($data);
				var $size_guide = $('#ct_size_guide,.ct-size-guide');
                $size_guide.addClass('mfp-hide')
                $size_guide_content.html('');
                $size_guide_modal.find('.mfp-bg').remove();
            }, 800);
        },

        /*-----------------------------------------------------------------------------------*/
        /*  ReInit variation functionality by recalling woocommerce wc_variation_form function
        /*-----------------------------------------------------------------------------------*/

        reInitVariation: function ($container) {
            var $form_variation = $container.find('.variations_form');

            if ($form_variation.length > 0) {
                $form_variation.wc_variation_form().find('.variations select:eq(0)').change();
                $form_variation.trigger('check_variations');
            }

        },

        /*-----------------------------------------------------------------------------------*/
        /*  niceSelect For Restyle SELECT OPTION 
        /*-----------------------------------------------------------------------------------*/

        initSelectElements: function (Action) {
            var self = this;
            if ($('.woocommerce').length != 0) {
                $('.woocommerce-ordering .orderby').niceSelect(Action);
                $('form.cart ul.variations select').not('.hide-attr-select').niceSelect(Action);
            }

            // Calculate shipping section in cart page 
            // archive of post
            // widget select categories 
            // woocommerce drop down widgets - layerd Nav
            $('section.shipping-calculator-form select, .widget_archive select, .widget_categories select.postform, .widget_product_categories select.dropdown_product_cat,.widget_layered_nav select,.widget select').niceSelect(Action);

            // this Code added : For bug Fix call select in Ajax Request in Filter drop down
            $('.widget_product_categories select.dropdown_product_cat,select.dropdown_layered_nav').niceSelect();

        },

        /*-----------------------------------------------------------------------------------*/
        /* woocommerce variation select
        /*-----------------------------------------------------------------------------------*/
        /*function woocommerce_variation_pre_select() {

  }*/

        woocommerce_variation_img_attributes: function () {
            setTimeout(function () {

                $('form.cart').find('.variations select').each(function (index, el) {

                    var $select = $(this),
                        $image_attr = $select.siblings('.image-attr'),
                        attr_values = new Array();

                    $select.find('option').each(function () {
                        attr_values.push($(this).val());
                    });

                    $image_attr.find('div.swiper-slide').removeClass('active');

                    if ($image_attr.length) {
                        $.each(attr_values, function () {
                            if (this != '')
                                $image_attr.find('div[data-value=' + this + ']').addClass('active');
                        })
                    }

                    $image_attr.find('.swiper-slide:not(.active)').addClass('deactive');

                    // Update nice select 
                    $('form.cart .variations select').not('.hide-attr-select').niceSelect('update');


                });
            }, 200)
        },

        woocommerce_variation_attributes_trigger: function () {
            var self = this;

            if ($('form.cart .variations select').length <= 0)
                return;

            self.$body.trigger('update_variation_values');

        },

        woocommerce_variation_attributes: function () {
            var self = this;

            if ($('form.cart .variations select').length <= 0)
                return;


            self.$body.unbind('update_variation_values', self.woocommerce_variation_img_attributes);
            self.$body.on('update_variation_values', self.woocommerce_variation_img_attributes);

            var $attr_container = $('.woocommerce form.cart .variations .attr-container'),
                $attr_slides = $attr_container.find('.swiper-slide');


            //Run swiper when count of attribute values is greater than 5
            if ($attr_slides.length > 5) {

                var $next_button = $attr_container.find('.swiper-button-next'),
                    $prev_button = $attr_container.find('.swiper-button-prev');
                var $attr_slider = new Swiper('.woocommerce form.cart .variations .swiper-container', {
                    speed: 400,
                    longSwipesMs: 300,
                    touchAngle: 90,
                    grabCursor: true,
                    touchRatio: 3,
                    slidesPerView: 5,
                    followFinger: false,
                    preventClicks: false,
                    slideToClickedSlide: true,
                    spaceBetween: 0,
                    centeredSlides: false,
                    nextButton: $next_button,
                    prevButton: $prev_button,
                });
            }
            else {
                if ($('div.product').hasClass('pd_top')) {
                    $attr_container.addClass('centered');
                }
            }

            $attr_slides.on('click', function () {

                if ($(this).hasClass('active')) {
                    //active clicked item
                    $attr_slides.removeClass('selected');
                    $(this).addClass('selected');
                }

                var $term = $(this).data('value');

                //change select element to trigger events
                $attr_container.siblings('select').find('option[value="' + $term + '"]').prop('selected', true);
                $attr_container.siblings('select').trigger('change');

                //show attribute value
                $attr_container.closest('.value').siblings('span.image-label').find('span.attr-value').html($term);
            });

            if ($('form.variations_form').length) {
                if ($('form.variations_form').siblings('.yith-wcwl-add-to-wishlist').length) {
                    $('.single_variation_wrap a.single_add_to_cart_button').after($('.yith-wcwl-add-to-wishlist').eq(0));
                }

                $('.yith-wcwl-add-to-wishlist').css('visibility', 'visible');

            }

            // On clicking the reset variation button
            $(document.body).on('click', '.reset_variations', function (event) {

                $attr_slides.removeClass('selected deactive');

                $('.nice-select').each(function () {

                    var $this = $(this);

                    var $dropdownLi = $this.find('.list li');

                    //reset DropDown 
                    $dropdownLi.removeClass('selected');
                    var $chooseAnoptionText = $this.find('.list li:first-child').text();

                    var $currentText = $this.find('.current');
                    $currentText.html($chooseAnoptionText);

                });

            })

        },

        /*-----------------------------------------------------------------------------------*/
        /*  woocomerce -  product quantity input
        /*-----------------------------------------------------------------------------------*/

        product_quantity: function () {
            var self = this;

            self.$document.on("click", '.quantity .quantity-button', function () {

                var $button = $(this);
                var $quantityInput = $(this).siblings('.woocommerce .quantity input[type="number"]');


                var oldValue = $quantityInput.val();

                if ($button.hasClass("plus")) {
                    var newVal = parseFloat(oldValue) + 1;
                }
                else {
                    if (oldValue > 0) {
                        var newVal = parseFloat(oldValue) - 1;
                    } else {
                        newVal = 0;
                    }
                }

                $quantityInput.val(newVal).trigger('change');

            });

            $(document).on('change', '.woocommerce .quantity input[type="number"]', function () {
                $(this).parent('.quantity').siblings('.add_to_cart_button').attr('data-quantity', $(this).val());
            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  product  detail - tabs
        /*-----------------------------------------------------------------------------------*/
        //Modified version of Woocommerce/assets/js/frontend/single-product.js
        product_tabs: function () {

            var self = this,
                ratingInitCount = 1; // Control to init rating Run Once time 

            // wc_single_product_params is required to continue, ensure the object exists
            if (typeof wc_single_product_params === 'undefined') {
                return false;
            }

            // Tabs
            $('body')
            .on('init', '.wc-tabs-wrapper, .woocommerce-tabs', function () {
                $('.wc-tab, .woocommerce-tabs .panel:not(.panel .panel)').hide();

                var hash = window.location.hash;
                var url = window.location.href;
                var $tabs = $(this).find('.wc-tabs, ul.tabs').first();

                if (hash.toLowerCase().indexOf('comment-') >= 0 || hash === '#reviews' || hash === '#tab-reviews') {
                    $tabs.find('li.reviews_tab a').click();
                } else if (url.indexOf('comment-page-') > 0 || url.indexOf('cpage=') > 0) {
                    $tabs.find('li.reviews_tab a').click();
                } else {
                    $tabs.find('li:first a').click();
                }
            })
            //Epico codes (custom version of click event for tabs)
            .on('click', '.wc-tabs li a, ul.tabs li a', function () {
                var $this = $(this),
                    $currentPanelID = $this.attr('href'),
                    $currentPanel = $('.woocommerce-tabs').find($currentPanelID),
                    $visiblePanel = $currentPanel.siblings('.panel').filter(':visible');


                $this.parent().siblings().removeClass('active').end().addClass('active');

                if ($visiblePanel.length <= 0) {
                    $currentPanel.addClass('current').fadeIn(300, function () {
                        self.update_doc_height();
                    });
                }
                else {
                    $visiblePanel.stop().fadeOut(300, function () {
                        $currentPanel.siblings('.panel').removeClass('current');
                        $currentPanel.addClass('current').stop().fadeIn(300, function () {
                            self.update_doc_height();
                        });
                    });
                }


                return false;
            })
            // Review link
            .on('click', 'a.woocommerce-review-link', function () {
                $('.reviews_tab a').click();
                return true;
            } )
            //Epico code : modified version
            .on('init', '#rating', function () {
                if (ratingInitCount == 1) { // Control to init rating Run Once time 
                    $('#rating').hide().before('<p class="stars review_rating"><span><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span></p>');
                    ratingInitCount++;
                }
            } )
            .on( 'click', '#respond p.stars a', function() {
                var $star       = $( this ),
                    $rating     = $( this ).closest( '#respond' ).find( '#rating' ),
                    $container  = $( this ).closest( '.stars' );

                $rating.val( $star.text() );
                $star.siblings( 'a' ).removeClass( 'active' );
                $star.addClass( 'active' );
                $container.addClass( 'selected' );

                return false;
            } )
            .on('click', '#respond #submit', function () {
                var $rating = $(this).closest('#respond').find('#rating'),
                    rating = $rating.val();

                if ($rating.length > 0 && !rating && wc_single_product_params.review_rating_required === 'yes') {
                    window.alert(wc_single_product_params.i18n_required_rating_text);

                    return false;
                }
            })

            //Epico code : modified version
            if (ratingInitCount === 1) { // Control to init rating Run Once time 
                //Init Tabs and Star Ratings    
                $('.wc-tabs-wrapper, .woocommerce-tabs, #rating').trigger('init');
                ratingInitCount++;
            }

        },

        /*-----------------------------------------------------------------------------------*/
        /*  Woocommerce categories
        /*-----------------------------------------------------------------------------------*/

        woocommerce_cats: function () {
            var self = this;
            if ($(".product-category a").length <= 0)
                return;

            self.epico_interactive_background($('.product-category a'));
        },

        /*-----------------------------------------------------------------------------------*/
        /*  product  Quick view
        /*-----------------------------------------------------------------------------------*/

        qucikviewdescription_scroolbar: function () {
            var self = this;
            self.epico_scrollbar('#ep-modal.quickview-modal div.summary .woocommerce-product-details__short-description');
        },

        product_quickview: function () {
            var self = this;

            var $quickview_modal = self.$document.find('#ep-modal'),
                $quickview_wrapper = $quickview_modal.find('.modal-content-wrapper'),
                $quickview_content = $quickview_modal.find('#modal-content'),
                $quickview_next = $quickview_modal.find('a[rel="next"]'),
                $quickview_prev = $quickview_modal.find('a[rel="prev"]'),
                $items = $('ul.products li.product');

            if ($quickview_modal.length <= 0)
                return;

            $('.quick-view-button').on('click', function (e) {
                e.preventDefault();

                var $this = $(this),
                    $product_id = $this.data('product_id');

                $this.closest('li.product').addClass('qv-active');

                //put a delay to load images after css transitions
                setTimeout(function () {

                    // next and Prev Buttons - in Quick view 
                    var $next_item = $items.filter('.qv-active').next('li.product'),
                        $prev_item = $items.filter('.qv-active').prev('li.product');

                    if ($next_item.length <= 0) {
                        $next_item = $items.eq(0);
                    }

                    if ($prev_item.length <= 0) {
                        $prev_item = $items.eq($items.length - 1);
                    }

                    if ($this.closest('.products').find('li.product').length <= 1) {
                        $quickview_modal.addClass('hidden-nav');
                    }
                    else {
                        if (self.windowWidth > 767) {
                            $quickview_next.find('img').remove();
                            $quickview_prev.find('img').remove();

                            var $next_img = $next_item.find('img').eq(0).clone(),
                                $prev_img = $prev_item.find('img').eq(0).clone();
                            $next_img.insertAfter($quickview_next.find('span'));
                            $prev_img.insertAfter($quickview_prev.find('span'));
                        }
                    }

                }, 400);

                self.$body.addClass('modal-open'); // disable scrollbar
                $quickview_modal.addClass('quickview-modal');

                if (!$quickview_modal.removeClass('closed').hasClass('open')) {
                    $quickview_modal.removeClass('loading').addClass('open');
                }

                var ajaxurl,
                    data = {
                        product_id: $product_id
                    };
                // Use new WooCommerce endpoint URL if available
                if (typeof wc_add_to_cart_params !== 'undefined') {
                    ajaxurl = wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'load_quick_view'); // WooCommerce Ajax endpoint URL (available since 2.4)
                } else {
                    ajaxurl = epico_theme_vars.ajax_url;
                    data['action'] = 'load_quick_view';
                }

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: data,
                    dataType: 'html',
                    cache: false,
                    headers: { 'cache-control': 'no-cache' },
                    success: function (data) {
                        $quickview_content.html(data);
                        $quickview_modal.addClass('shown'); // content is ready, so show it
                        setTimeout(function () {
                            self.product_thumbnails($quickview_content,true); // enable gallery slider of product
                        }, 200);
                        self.initSelectElements();
                        self.woocommerce_variation_attributes(); // enable variation attributes
                        self.woocommerce_variation_attributes_trigger(); // update image attributes
                        self.reInitVariation($quickview_content); // Variation Form
                        self.qucikviewdescription_scroolbar(); // qucik view descriptions scrollbar
                        self.addToCart(); // add to cart - open side bar add to cart
                    }
                });
            });


            // Close quickview by click outside of content
            $quickview_modal.on('click', function (e) {
                if (!$quickview_wrapper.is(e.target) && $quickview_wrapper.has(e.target).length === 0 && !$quickview_next.is(e.target) && $quickview_next.has(e.target).length === 0 && !$quickview_prev.is(e.target) && $quickview_prev.has(e.target).length === 0) {
                    self.close_quick_view();
                }
            });

            // Close quickview by click close button
            self.$document.on('click', '#ep-modal.quickview-modal #modal-close', function (e) {
                e.preventDefault();
                self.close_quick_view();
            });

            // Close box with esc key
            self.$document.keyup(function (e) {
                if (e.keyCode === 27)
                    self.close_quick_view();
            });

            $quickview_next.on('click', function () {

                var $next_item = $items.filter('.qv-active').next('li.product');
                if ($next_item.length <= 0) {
                    $next_item = $items.eq(0);
                }

                $quickview_modal.removeClass('shown');
                $items.filter('.qv-active').removeClass('qv-active');
                $next_item.find('a.quick-view-button').trigger('click');
            });

            $quickview_prev.on('click', function () {
                var $prev_item = $items.filter('.qv-active').prev('li.product');
                if ($prev_item.length <= 0) {
                    $prev_item = $items.eq($items.length - 1);
                }

                $quickview_modal.removeClass('shown');
                $items.filter('.qv-active').removeClass('qv-active')
                $prev_item.find('a.quick-view-button').trigger('click');
            });
        },

        close_quick_view: function () {
            var self = this;

            var $quickview_modal = self.$document.find('#ep-modal.quickview-modal'),
                $quickview_content = $quickview_modal.find('#modal-content');

            $quickview_modal.removeClass('shown loading open').addClass('closed');
            $('li.product.qv-active').removeClass('qv-active');

            setTimeout(function () {
                self.$body.removeClass('modal-open');
                $quickview_modal.removeClass('quickview-modal');
            }, 300)

            setTimeout(function () {
                $quickview_content.html('');
            }, 800);
        },

        /*-----------------------------------------------------------------------------------*/
        /*  product variation
        /*-----------------------------------------------------------------------------------*/

        product_variation: function () {
			var self = this;
            if ($("form.variations_form").length <= 0 || $('#product-fullview-thumbs .swiper-container').length <= 0)
                return;

            //Use passed parameter for getting variation images
            //variation.image_link : original image size
            //variation.image_src : smaller image ( shop_single size )
            $.fn.wc_variations_image_update = function (variation) {

                
                //check existnace of image for this variation
                if (variation && variation.image.url && variation.image.url.length > 1) {

                    //Find the index of variation slide and slide to it
                    var index = $("#product-fullview-thumbs").find('.swiper-slide[data-variableimageurl^="' + variation.image.url + '"]').data('slide'),
                        $image_slider = $('#product-fullview-thumbs .swiper-container')[0].swiper;
						
					if (index == undefined)
                        return;

                    if ($image_slider == 'undefined')
                        return;
					
					if (self.windowWidth < 768) {
						index = index + 1; // increment to point to correct slide in loop mode slider
					}

                    $image_slider.slideTo(index);
                    $('#product-thumbs').add($('#product-fullview-thumbs')).addClass('stop-by-variations');//Stop slider when select an varaition that has image
                    $image_slider.stopAutoplay();

                }
            };
        },

        /*-----------------------------------------------------------------------------------*/
        /*  product card widget,Cart page
        /*-----------------------------------------------------------------------------------*/
        disable_djax_on_cart_page: function () {
            var self = this;

            // just do it in cart page
            if (!self.$body.hasClass('woocommerce-cart') || !self.$body.hasClass('ajax_page_transition'))
                return;

            var removeDjax = function () {
                $('.woocommerce-cart-form .cart_item a.remove').addClass('no_djax');
            }

            removeDjax();

            $(document).on('updated_wc_div', removeDjax);
        },

        card_widget_update: function () {
            var self = this;

            $(".mini_cart_item a.remove").addClass('no_djax');
            self.$document.off("click",".mini_cart_item a.remove").addClass('no_djax');//unbind all functions binded to click to prevent unwanted behaviors
            self.$document.on("click", ".mini_cart_item a.remove", function (e) {

                e.preventDefault();
                var $this = $(this);
                var $productKey = $(this).data("item-key");

                $this.closest('li').addClass('loading');
                $this.siblings('.wc-loading').removeClass('hide');

                // Ajax action
                $.ajax({
                    url: epico_theme_vars.ajax_url,
                    dataType: 'json',
                    type: 'POST',
                    cache: false,
                    headers: { 'cache-control': 'no-cache' },
                    data: {
                        'action': 'cart_remove_item',
                        'item_key': $productKey
                    },
                    success: function (response) {

                        if (response.status === '1') {
                            $this.siblings('.wc-loading').addClass('hide');
                            $this.addClass('removed').closest('li').addClass('removed').removeClass('loading');

                            // Update cart item count
                            $('.cartContentsCount').html(response.cart_count);

                            // Is the cart empty?
                            if (response.cart_count == 0) {
                                $('.product_list_widget').each(function () {
                                    $('.cart-bottom-box').addClass('hide');
                                });
                            } else {
                                // Update cart subtotal
                                $('.cart-bottom-box .amount').html($(response.cart_subtotal).html());
                            }
                        }

                        // Wait 3 second to get chance of undoing item 
                        setTimeout(function () {
                            if ($this.closest('li').hasClass('removed')) {
                                $this.closest('li').find('.undo').hide('slow');

                                //after removing item from cart sidebar in cart page, cart page should be refreshed
                                if (self.$body.hasClass('woocommerce-cart')) {
                                    $(document).trigger('wc_update_cart');
                                }
                            }
                        }, 3100);

                        setTimeout(function () {
                            if ($this.hasClass('removed')) {
                                $this.closest('li').addClass('removed_completly');
                            }
                        }, 3500);

                        $(document).trigger('wc_cart_updated');

                    }
                });
            });

            var ajaxCompleteHandler = function (event, xhr, settings) {

                if (settings.url.indexOf('undo_item') > 0) {
                    setTimeout(function () {
                        $(document.body).trigger('wc_fragment_refresh');
                    }, 50)

                }
            }

            $(document).ajaxComplete(ajaxCompleteHandler);

            $(".mini_cart_item a.undo").addClass('no_djax');//unbind all functions binded to click to prevent unwanted behaviors
            self.$document.off("click",".mini_cart_item a.undo");//unbind all functions binded to click to prevent unwanted behaviors
            self.$document.on("click", ".mini_cart_item a.undo", function (e) {
                e.preventDefault();
                var $this = $(this),
                    $product_key = $(this).data("item-key");

                $this.closest('li').removeClass('removed').addClass('loading');
                $this.siblings('a.remove').removeClass('removed');
                $this.siblings('.wc-loading').removeClass('hide');

                // Ajax action
                $.ajax({
                    url: epico_theme_vars.ajax_url,
                    dataType: 'json',
                    type: 'POST',
                    cache: false,
                    headers: { 'cache-control': 'no-cache' },
                    data: {
                        'action': 'undo_removed_item',
                        'item_key': $product_key
                    },
                    success: function (response) {

                        if (response.status === '1') {
                            $this.siblings('.remove').removeClass('removed').end().closest('li').removeClass('removed loading');
                            $this.siblings('.wc-loading').addClass('hide');

                            // Update cart item count & cart subtotal
                            $('.cartContentsCount').html(response.cart_count);
                            $('.cart-bottom-box .amount').html($(response.cart_subtotal).html());
                            $('.cart-bottom-box').removeClass('hide');

                            //after undoing item from cart sidebar in cart page, cart page should be refreshed
                            if (self.$body.hasClass('woocommerce-cart')) {
                                $(document).trigger('wc_update_cart');
                            }
                            $(document).trigger('wc_cart_updated');
                        }
                    }
                });
            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  product  wishlist widget
        /*-----------------------------------------------------------------------------------*/

        wishlist_widget_update: function () {
            var self = this;
            //Update it when a product added or removed to/from wishlist
            self.$body.on('added_to_wishlist removed_from_wishlist', function () {
                $.ajax({
                    url: epico_theme_vars.ajax_url,
                    data: {
                        'action': 'get_wishlist_quantity',
                        'security': epico_theme_vars.nonce
                    },
                    method: 'GET',//Use get to retrive data from server faster
                    success: function (data) {
                        $(".widget_woocommerce-wishlist a span.wishlist_items_number").html(data['wishlist_count_products']);
                    }
                });

                var $clicked_add_to_wishlist = self.$document.find('.add_to_wishlist.adding');
                $clicked_add_to_wishlist.find('.wc-loading').addClass('hide');
                $clicked_add_to_wishlist.fadeOut(400).siblings('.wishlist-link').fadeIn(400);

                self.addToCart();
                self.minPageHeightSet();
            });
        },

        /*-----------------------------------------------------------------------------------*/
        /* Wishlist remove item
        /*-----------------------------------------------------------------------------------*/
        wishlist_button: function () {
            var self = this;

            self.$document.on('click', '.add_to_wishlist', function () {
                if ($(this).hasClass('shop_wishlist_button')) {
                    $(this).addClass('adding').find('.wc-loading').removeClass('hide');

                }
            });
        },

        /*-----------------------------------------------------------------------------------*/
        /* Wishlist remove item
        /*-----------------------------------------------------------------------------------*/

        wishlist_remove: function () {
            var self = this;

            self.$document.on('click', '.remove_from_wishlist', function (e) {
                e.preventDefault();
                $(this).addClass('show-loading').find('.wc-loading').removeClass('hide');
            });
        },


        /*-----------------------------------------------------------------------------------*/
        /* Filter drop down ( select ) functionality after ajax request 
        /*-----------------------------------------------------------------------------------*/

        layer_nav_ajax_dropdown: function () {

            $('.dropdown_layered_nav').change(function () {

                var $slug = jQuery( this ).val();
                var $filtername = jQuery(this).closest('select').attr('data-filtername');

                var url = window.location.href; // get current url 
                $slug = '?filter_' + $filtername + '=' + $slug;
                url += $slug;
                location.href = url;

            });

        },

        /*-----------------------------------------------------------------------------------*/
        /* price slider filter - woocommerce code without any changes this code use for Djax
        /*-----------------------------------------------------------------------------------*/
        disable_price_slider_keydown_event: function () {
            $(".widget_price_filter").on('click', function () {
                $(this).find('.ui-slider-handle').unbind('keydown');
            });
        },

        price_slider_filter: function () {
            /****** this code added ****/
            if (!$('.woocommerce-page').length) {
                return 0;
            }

            /**********/
            var price_slider = function () {
                // Get markup ready for slider
                $('input#min_price, input#max_price').hide();
                $('.price_slider, .price_label').show();

                // Price slider uses jquery ui
                var min_price = $('.price_slider_amount #min_price').data('min'),
                    max_price = $('.price_slider_amount #max_price').data('max'),
                    current_min_price = parseInt(min_price, 10),
                    current_max_price = parseInt(max_price, 10);

                /***** this section modified by epico *****/
                if ($('.price_slider_amount #min_price').val()) {
                    current_min_price = parseInt($('.price_slider_amount #min_price').val(), 10);
                }
                if ($('.price_slider_amount #max_price').val()) {
                    current_max_price = parseInt($('.price_slider_amount #max_price').val(), 10);
                }
                /**********/

                $(document.body).bind('price_slider_create price_slider_slide', function (event, min, max) {
                    if (woocommerce_price_slider_params.currency_pos === 'left') {

                        $('.price_slider_amount span.from').html(woocommerce_price_slider_params.currency_symbol + min);
                        $('.price_slider_amount span.to').html(woocommerce_price_slider_params.currency_symbol + max);

                    } else if (woocommerce_price_slider_params.currency_pos === 'left_space') {

                        $('.price_slider_amount span.from').html(woocommerce_price_slider_params.currency_symbol + ' ' + min);
                        $('.price_slider_amount span.to').html(woocommerce_price_slider_params.currency_symbol + ' ' + max);

                    } else if (woocommerce_price_slider_params.currency_pos === 'right') {

                        $('.price_slider_amount span.from').html(min + woocommerce_price_slider_params.currency_symbol);
                        $('.price_slider_amount span.to').html(max + woocommerce_price_slider_params.currency_symbol);

                    } else if (woocommerce_price_slider_params.currency_pos === 'right_space') {

                        $('.price_slider_amount span.from').html(min + ' ' + woocommerce_price_slider_params.currency_symbol);
                        $('.price_slider_amount span.to').html(max + ' ' + woocommerce_price_slider_params.currency_symbol);

                    }

                    $(document.body).trigger('price_slider_updated', [min, max]);
                });

                $('.price_slider').slider({
                    range: true,
                    animate: true,
                    min: min_price,
                    max: max_price,
                    values: [current_min_price, current_max_price],
                    create: function () {

                        $('.price_slider_amount #min_price').val(current_min_price);
                        $('.price_slider_amount #max_price').val(current_max_price);

                        $(document.body).trigger('price_slider_create', [current_min_price, current_max_price]);
                    },
                    slide: function (event, ui) {

                        $('input#min_price').val(ui.values[0]);
                        $('input#max_price').val(ui.values[1]);

                        $(document.body).trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
                    },
                    change: function (event, ui) {

                        $(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
                    }
                });
            }

            // woocommerce_price_slider_params is required to continue, ensure the object exists
            if (typeof woocommerce_price_slider_params === 'undefined') {
                return false;
            }

            if (typeof $.fn.slider != 'undefined') {
                price_slider();
            }
            else {
                //Wait a bit to add scripts compelety to DOM and then run function
                setTimeout(function () {
                    if (typeof $.fn.slider != 'undefined') {
                        price_slider();
                    }
                }, 1000)
            }
        },

        /*-----------------------------------------------------------------------------------*/
        /*  product  detail - zoom effect 
        /*-----------------------------------------------------------------------------------*/

        product_image_zoom: function () {
            var self = this;
            if (self.windowWidth <= 979)
                return;

            var $easyzoom = $('.easyzoom').easyZoom();
        },

        /*-----------------------------------------------------------------------------------*/
        /*  product hover
        /*-----------------------------------------------------------------------------------*/

        product_hover: function () {
            var self = this;
            //check existance of woocommerce class (shop page or a normal page with shortcode)
            if ($('.woocommerce').length <= 0)
                return;

            var $productAddToCart = $('ul.products li.product span.product-button');
            $productAddToCart.each(function () {
                var $product = $(this).closest('li.product'),
                    $buttons_container = $(this).closest('.product-buttons'),
                    $container = $(this).closest('.woocommerce');

                if (!$product.hasClass('single-product-shortcode')) {

                    //Click
                    $(this).find('.product_type_simple').on('click', function () {
                        $product.addClass('disable-hover');
                    });

                    //disable hover on touch devices
                    if (self.isTouchDevice) {
                        $product.addClass('disable-hover');
                    }
                    else {
                        //Hover effect
                        $(this).mouseenter(function () {
                            $product.addClass('add-to-cart-hovered');
                        });

                        //Exit hover state of the cart button when mouse leaves the buttons container
                        $buttons_container.mouseleave(function () {
                            $product.removeClass('add-to-cart-hovered');
                        });
                    }

                }

            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  product  detail - gallery 
        /*-----------------------------------------------------------------------------------*/

        product_thumbnails: function ($target, isQuickview) {
            var self = this;

            if ($target === undefined)
                $target = self.$body;

            var $thumbnails = $target.find("#product-thumbs");
			var $thumbnailsitem = $target.find("#product-thumbs .swiper-slide"); 

			if ($thumbnailsitem.length <= 1) {
				//popup gallery in simple products when just have a gallery image without featured image.
				var $thumbnailslider = $target.find("#product-fullview-thumbs");
				$thumbnailslider.find('.enable-popup').addClass('swiper-slide');
			
			}
			if ($thumbnailsitem.length == 1) {
				
				$("#product-thumbs").remove();
			} 

            if (($thumbnailsitem.length > 1) ) {
                var $fullview = $target.find("#product-fullview-thumbs"),
                    $thumbnailSlides = $thumbnails.find('.swiper-slide'),
                    slidesNum = $thumbnailSlides.length,
                    visibleNum = 3,
                    $productThumbContainer = $thumbnails.find(".swiper-container"),
                    $productThumbWraper = $thumbnails.find(".swiper-wrapper"),
                    $productimageContainer = $fullview.find('.swiper-container'),
                    direction = 'vertical',
                    productDetail = 'classic';

                if(!isQuickview)
                {
                    if($('#main div.pd_top').length)
                        productDetail = 'top';
                    else if($('#main div.pd_classic_sidebar').length)
                        productDetail = 'classic_sidebar';
                    else if($('#main div.pd_ep_classic').length)
                        productDetail = 'ep_classic';
                    else if($('#main div.pd_classic').length)
                        productDetail = 'classic';
                }

                if (self.windowWidth > 979) {

                    // Use horizontal gallery in Quickview & fullwidth product detail style
                    if (isQuickview || productDetail == 'top' || productDetail == 'classic_sidebar') {
                        direction = 'horizontal';
                        if (slidesNum <= 2) {
                            $productThumbContainer.css({ "width": ($thumbnailSlides.outerWidth() + 10) * slidesNum, "margin": "0 auto" }); // 10px  margin bottom For Each items 
                        }
                        $productThumbWraper.css("width", ($thumbnailSlides.outerWidth() + 10) * slidesNum); // 10px  margin bottom For Each items 

                    } else {
                        $productThumbWraper.css("height", ($thumbnailSlides.outerHeight() + 10) * slidesNum); // 10px  margin bottom For Each items 
                    }


                    if (productDetail == 'top')
                        visibleNum = 7; // 7 gallery item in fullwidth style
                    else if (productDetail == 'ep_classic' || productDetail == 'classic')
                        visibleNum = 5; // 5 gallery item in classic styles


                    if(isQuickview)
                        visibleNum = 3;


                    var $productFullviewSwiper = $productimageContainer[0].swiper,
                        $productThumbsSwiper = new Swiper($productThumbContainer, {
                            speed: 700,
                            longSwipesMs: 800,
                            touchAngle: 90,
                            grabCursor: true,
                            touchRatio: 3,
                            slidesPerView: 'auto',
                            preventClicks: false,
                            slideToClickedSlide: true,
                            spaceBetween: 10,
                            direction: direction,
                            centeredSlides: true,
                            preloadImages: false,
                            lazyLoading: true,
                            onInit: function () {

                                if (slidesNum > visibleNum) {
                                    $thumbnails.addClass('initial-slides-position');
                                }
                            },
                            onSetTranslate: function (swiper, translate) {

                                if (swiper.activeIndex < visibleNum - 1) //keep items visible until click on last visible item
                                {
                                    $productThumbWraper.css("transform", "translate3d(0px, 0px, 0px)");
                                    $productThumbWraper.css("-webkit-transform", "translate3d(0px, 0px, 0px)");
                                }

                            },
                            onSlideChangeStart: function (swiper) {
								if($productFullviewSwiper !== "undefined")
                                    $productFullviewSwiper.slideTo(swiper.activeIndex);
                            }
                        });

                }

                var isLoop = false,
                    slider_speed = 600,
                    $nextbtn = $productimageContainer.find('.swiper-button-next'),
                    $prevbtn = $productimageContainer.find('.swiper-button-prev');

                if (self.windowWidth < 768) {
                    isLoop = true;
                }

                if (productDetail == 'top') {
                    slider_speed = 800;
                }


                if ($('#product-thumbs .auto-play').length) {
                    var $autoplayDuration = 6500;
                }
                else {
                    $autoplayDuration = 0;
                }

                var $productThumbsFullviewSwiper = new Swiper($productimageContainer, {
                    autoplay: $autoplayDuration,
                    autoplayDisableOnInteraction: false,
                    speed: slider_speed,
                    longSwipesMs: 700,
                    touchAngle: 30,
                    loop: isLoop,
                    spaceBetween: 0,
                    followFinger: true,
                    nextButton: $nextbtn,
                    prevButton: $prevbtn,
                    onSlideChangeStart: function (swiper) {
                        if (self.windowWidth > 979 && !self.isTouchDevice) {
                            if (!swiper.isBeginning) {
                                $productThumbsSwiper.slideTo(swiper.activeIndex);
                            }
                        }

                    },
                    onReachBeginning: function (swiper) {
                        if (self.windowWidth > 979 && !self.isTouchDevice) {
                            $productThumbsSwiper.slideTo(0);
                        }
                    }

                });

				$productFullviewSwiper = $productThumbsFullviewSwiper;

                if (self.windowWidth > 979 && !self.isTouchDevice) {
                    //stop and start slider on hover in product detail
                    $productimageContainer.hover(function () {

                        $productThumbsFullviewSwiper.stopAutoplay();

                    }, function () {

                        $productThumbsFullviewSwiper.startAutoplay();

                    });

                    //Start autoplay if clicked when slider is disabled by product variation choosing
                    $thumbnails.add($fullview).on('click', function () {
                        if ($(this).hasClass('stop-by-variations')) {
                            $productThumbsFullviewSwiper.startAutoplay();
                            $thumbnails.add($fullview).removeClass('stop-by-variations');
                        }
                    });
                }

            }
        },

        /*-----------------------------------------------------------------------------------*/
        /*  product - Info on click style
        /*-----------------------------------------------------------------------------------*/

        products_infoOnclick: function () {
            var self = this;

            if ($('ul.infoOnClick').length <= 0)
                return;

            var $container = $('ul.infoOnClick li');

            $container.find('span.show-hover').on('click', function () {
                var $product_Id = $(this).parents('li.product').attr('data-productid'); //data product ID
                $product_Id = $('li.product[data-productid =' + $product_Id + ']');
                $(this).parents('ul.products').find($product_Id).find('span.show-hover').toggleClass('show').closest('li').toggleClass('show-hover-content');
            });

            self.epico_scrollbar($container.find('.woocommerce-product-details__short-description'));

        },

        /*-----------------------------------------------------------------------------------*/
        /*  Single product 2 slider
        /*-----------------------------------------------------------------------------------*/

        single_product2_slider: function () {
            var self = this;
            if ($('div.woocommerce.single-product2').length <= 0)
                return;

            var $container = $('div.woocommerce.single-product2');

            var $single_product_slider = new Swiper('div.woocommerce.single-product2 .swiper-container', {
                speed: 600,
                longSwipesMs: 700,
                touchAngle: 30,
                loop: true,
                spaceBetween: 0,
                followFinger: false,
                preloadImages: false,
                lazyLoading: true,
            });

            //load image of duplicate slides
            setTimeout(function () {
                //Remove is-loading class and prepare it for images lazy loading
                $container.find('.swiper-slide-duplicate.lazy-load-on-load').removeClass('is-loading');
                self.lazyLoadOnLoad($container);
            }, 500);


            $container.find('.swiper-button-next').bind('click', function (e) {
                e.preventDefault();
                if (typeof $(this).closest('.swiper-container')[0].swiper.slideNext == 'function')
                    $(this).closest('.swiper-container')[0].swiper.slideNext();
            });

            $container.find('.swiper-button-prev').bind('click', function (e) {
                e.preventDefault();
                if (typeof $(this).closest('.swiper-container')[0].swiper.slidePrev == 'function')
                    $(this).closest('.swiper-container')[0].swiper.slidePrev();
            });

        },

        /*-----------------------------------------------------------------------------------*/
        /*  Single product 2 description scroll bar 
        /*-----------------------------------------------------------------------------------*/

        single_product2_scrollbar: function () {
            var self = this;

            self.epico_scrollbar('div.woocommerce.single-product2 > ul.products li.product .description');
        },

        /*-----------------------------------------------------------------------------------*/
        /*  carousel Shortcode
        /*-----------------------------------------------------------------------------------*/
 epico_carousel: function ($container) {

            var self = this,
                $carousel;

            if ($container === undefined)
                $carousel = $('.carousel');
            else
                $carousel = $container.find('.carousel');

            if ($carousel.length <= 0)
                return;

            // FullWidth Carousel remove col12 paddings
            $carousel.parents('.fullWidth').find('.vc_col-sm-12').css({
                'padding-right': '0px',
                'padding-left': '0px',
            });

            $carousel.each(function () {

                var $this = $(this),
                    $container = $this.find('.products'),
                    wc_shortcode_with_border = $this.find('li').hasClass('with-border'),
                    set_visible_number = parseInt($this.find('.swiper-container').attr('data-visibleitems')),
                    visibleItems = 1;

                visibleItems = self.getVisibleItemNum($this, set_visible_number);

                var loopSlideNum = visibleItems + 1,
                    gutter = 20,
                    slideClass = 'swiper-slide',
                    autoplay = 0;
                    if ($this.data('autoplay') == 'on') {
                    autoplay = 5000;
                    }

                if ($this.hasClass('wc-shortcode')) // wc-shortcode carousels
                {
                    if ($this.hasClass('no-gutter') || visibleItems == 1) {
                        gutter = 0;
                    }

                    slideClass = 'product';
                    autoplay = 0;
                    if ($this.data('autoplay') == 'on') {
                        autoplay = 3000;
                    }

                } 
				 else if ($this.hasClass('testimonials-style2')) // testimonials shortcode (testimonials-style2)
                {
                        gutter = 10;
                }
				 
                else if ($this.hasClass('instagram-carousel')) {
                    if ($this.hasClass('no-gutter')) {
                        gutter = 0;
                    }
                    else {
                        gutter = 10;
                    }

                    slideClass = 'insta-media';
                    autoplay = 0;

                } else // image carousels
                {

                    if ($this.hasClass('no-gutter')) {

                        gutter = 0;

                    } else {

                        gutter = 10;

                    }

                }

                // disable loop for "gallery carousel" element - is buggy 
                var $loop = true;
                if ($this.find('.swiper-container').parent().hasClass('gallery_carousel')) {
                    $loop = false;
                }

                var swiper = new Swiper($this.find('.swiper-container'), {
                    autoplay: autoplay,
                    touchAngle: 45,
                    speed: 600,
                    longSwipesMs: 800,
                    loop: $loop,
                    slidesPerView: visibleItems,
                    loopedSlides: loopSlideNum,
                    autoplayDisableOnInteraction: false,
                    slideClass: slideClass,
                    spaceBetween: gutter,
                    watchSlidesVisibility: true,
                    roundLengths: false,
                    preloadImages: false,
                    onInit: function () {
                        if ($container.length) { // wc-shortcode carousels
                            self.showAnimation($container, 1); // secound Parametr determines this code Runs In Carousel mode
                            self.setCarouselItemsWidth($this, visibleItems, gutter);
                        }
                        else {
                            self.showAnimation($this, 2);   // "2" used for animation of image Carousel
                        }

                        $this.find('.arrows-button-next,.arrows-button-prev').css('opacity', 1);

                    },
                    onSlideChangeEnd: function (swiper) { // after carousel slider changed, runs Product animation  , secound Parametr determines this code Runs In Carousel mode

                        if ($this.hasClass('wc-shortcode')) {
                            self.showAnimation($container, 1);
                        }
                        else if ($this.hasClass('instagram-carousel')) { // instagram carousel
                            self.showAnimation($this, 3);   // "3" used for animation of image  instagram carousel 
                        }
                        else {
                            self.showAnimation($this, 2);   // 2 used for image Carousel animation 
                        }

                    },
                    onAutoplayStop: function (swiper) {

                        if (autoplay != 0) {
                            swiper.startAutoplay();
                        }

                    },
                    onTouchEnd: function (swiper) { // after carousel touch En run Product animation , secound Parametr shown this code Run In Carousel

                        if ($this.hasClass('wc-shortcode')) {
                            self.showAnimation($container, 1);
                        }

                        if ($this.hasClass('instagram-carousel')) { // instagram carousel
                            self.showAnimation($this, 3);   // 3 used for image  instagram carousel  animation 
                        } else {
                            self.showAnimation($this, 2);   // 2 used for image Carousel animation 
                        }


                    },
                    onTransitionStart: function (swiper) {
                        if ($this.hasClass('wc-shortcode') && wc_shortcode_with_border) {
                            $this.find('li.last-visible-item').removeClass("last-visible-item");
                            var $last_visible_item = $this.find('.swiper-slide-visible').last().addClass("last-visible-item");
                        }
                    }

                });

                //load image of duplicate slides
                setTimeout(function () {
                    //Remove is-loading class and prepare it for images lazy loading
                    $this.find('.swiper-slide-duplicate .lazy-load-on-load').removeClass('is-loading');
                    self.lazyLoadOnLoad($this.find('.swiper-slide-duplicate'));
                }, 500);

            });

            $carousel.find('.arrows-button-next').bind('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                $(this).siblings('.swiper-container')[0].swiper.slideNext();
            });

            $carousel.find('.arrows-button-prev').bind('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                $(this).siblings('.swiper-container')[0].swiper.slidePrev();

            });

        },

        //Set more accurate width for products width
        setCarouselItemsWidth: function ($container, visibleItems, gutter) {
            var self = this;

            var strip = function (number) {
                return (parseFloat(number).toPrecision(12));
            }

            $container.find('li.product').css('width', '');
            //Get not rounded width of element
            var carouselWidth = $container.outerWidth(),
                width = 0;


            if (self.windowWidth <= 1140) {
                if (visibleItems > 3) {
                    visibleItems = 3;//3column in vertical tablets
                }
                if (self.windowWidth <= 979) {
                    if (visibleItems >= 2) {
                        visibleItems = 2;//2column in mobile devices
                    }
                    if (self.windowWidth <= 767) {
                        visibleItems = 1;//1column in mobile devices for wc shortcodes
                    }
                }
            }

            carouselWidth = carouselWidth - (gutter * (visibleItems - 1));
            width = strip(carouselWidth / visibleItems);


            $container.find('li.product').css('cssText', 'width:' + width + 'px !important');
        },

        getVisibleItemNum: function ($container, visibleItems) {
            var self = this;

            if (($container.hasClass('wc-shortcode')) || ($container.hasClass('testimonials-style2')) ) {
                if (self.windowWidth <= 1140) {
                    if (visibleItems > 3) {
                        visibleItems = 3; //3columns in vertical tablet devices
                    }
                    //2column in mobiles
                    if (self.windowWidth <= 979) {
                        if (visibleItems >= 2) {
                            visibleItems = 2;//2columns in mobile devices
                        }

                        if (self.windowWidth <= 767) {
                            visibleItems = 1;//1column in mobile devices
                        }
                    }
                }
            } 
            else {
                if (self.windowWidth <= 979) {
                    if (visibleItems > 3) {
                        visibleItems = 3; //3columns in vertical tablet devices
                    }
                    //2column in mobiles
                    if (self.windowWidth <= 767) {
                        if (visibleItems >= 2) {
                            visibleItems = 2;//2columns in mobile devices
                        }

                        if (self.windowWidth <= 480) {
                            visibleItems = 1;//1column in mobile devices
                        }
                    }
                }

            }

            return visibleItems;
        },

        updateCarousel: function () {
            var self = this;
            $('.carousel').each(function () {

                var $this = $(this),
                    $swiperContainer = $this.find('.swiper-container');

                if ($swiperContainer[0].swiper != undefined) {
                    var visibleItems = self.getVisibleItemNum($this, $swiperContainer.attr('data-visibleitems'));

                    $swiperContainer[0].swiper.params.slidesPerView = visibleItems;
                    $swiperContainer[0].swiper.params.loopedSlides = visibleItems + 1;

                    if ($this.hasClass('wc-shortcode')) // wc-shortcode carousels
                    {
                        var gutter = 20;
                        if ($this.hasClass('no-gutter') || visibleItems == 1)
                            gutter = 0;

                        $swiperContainer[0].swiper.update(true);
                        self.setCarouselItemsWidth($this, visibleItems, gutter);
                        self.showAnimation($this.find('.products'), 1); // secound Parametr determine this code Run In Carousel
                    } 
					
					 else if (($this.hasClass('testimonials-style2')) || ($this.hasClass('instagram-carousel'))) // testimonials-shortcode(testimonials-style2) and instagram-carousel
                    {
						$swiperContainer[0].swiper.update(true);
					
                    } 
					
                    else {
                        self.showAnimation($this, 2); 
						$swiperContainer[0].swiper.update(true);// 2 used for image Carousel animation
                    }

                }
            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  progress bar with animation Function
        /*-----------------------------------------------------------------------------------*/

        progressbar: function () { // progressbar run in document ready and call after Ajax

            var self = this;

            var $progressbar = $('.progress_bar:not(.shortcodeAnimation), .progress_bar.shortcodeAnimation.no-responsive-animation');

            if (!$progressbar.length) return;

            self.progressbarAnimate($progressbar);
        },

        /* Animate progressbar */
        progressbarAnimate: function ($element) { // call when its appeared on viewport

            var self = this;

            $element.each(function () {
                var $this = $(this),
                    percentage = $this.find('.progressbar_percent').data('percentage');
                $this.find('.progress_percent_value').addClass("complete");
                $this.find('.progressbar_percent').css('width', percentage + '%');

            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  Easy Pie Chart Function
        /*-----------------------------------------------------------------------------------*/

        piechart: function () { // piechart run in document ready and call after Ajax

            var self = this;

            var $pieChartBox = $('.pieChartBox:not(.shortcodeAnimation), .pieChartBox.shortcodeAnimation.no-responsive-animation');

            if (!$pieChartBox.length) return;

            self.piechartAnimate($pieChartBox);
        },

        /* PieChart With Animation*/
        piechartAnimate: function ($element) { // call when its appeared on viewport
            var self = this;

            var animation = false;
            if (self.windowWidth > 1140) {
                animation = { duration: 2500, enabled: true };
            }

            $element.each(function () {
                var $this = $(this);
                var $dot = $this.find('.dot-container');
                $this.find('.easyPieChart').easyPieChart({
                    scaleColor: false,
                    barColor: $this.attr('data-barColor'),
                    lineWidth: 2,
                    trackColor: 'rgba(0,0,0,0)',
                    lineCap: 'round',
                    easing: 'easeOutQuint',
                    animate: animation,
                    size: 145,
                    onStep: function (from, to, percent) {
                        $dot.css({ transform: 'rotate(' + (percent * 3.6 + 6) + 'deg)' });
                    }
                });

                if (animation == false) {
                    var percent = $this.find('.easyPieChart').data('percent');
                    $dot.css({ transform: 'rotate(' + (percent * 3.6 + 6) + 'deg)' });
                }
            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  Count-down
        /*-----------------------------------------------------------------------------------*/
        countdown: function() {
            $(".countdown-timer").each(function() {
                var $this = $(this);
                if($this.hasClass('initiated'))
                    return;

                $this.addClass('initiated');

                var updateCountDown = function() {
                    var date = Date.parse($this.data("end")) / 1000,
                    now = Math.floor($.now() / 1000),
                    $day = $this.find(".days"),
                    $hours = $this.find(".hours"),
                    $min = $this.find(".minutes"),
                    $second = $this.find(".seconds"),
                    day_distance = date - now;

                    if (day_distance > 0) {
                        var day = Math.floor(day_distance / 86400);
                        day_distance -= 60 * day * 60 * 24;
                        var hours = Math.floor(day_distance / 3600);
                        day_distance -= 60 * hours * 60;
                        var min = Math.floor(day_distance / 60);
                        day_distance -= 60 * min,
                        10 > day && (day = "0" + day),
                        10 > hours && (hours = "0" + hours),
                        10 > min && (min = "0" + min),
                        10 > day_distance && (day_distance = "0" + day_distance),
                        1 > $day.length || "00" == day ? $day.parent().hide() : $day.text(day),
                        $hours.text(hours),
                        $min.text(min),
                        $second.text(day_distance)
                    }
                };
                setInterval(updateCountDown, 1000),
                updateCountDown()
            })
        },


        /*-----------------------------------------------------------------------------------*/
        /*  Counter Box
        /*-----------------------------------------------------------------------------------*/

        counterBox: function () { // counterBox run in document ready and call after Ajax
            var self = this;

            var $counterBoxContainers = $('.counterBox:not(.shortcodeAnimation), .counterBox.shortcodeAnimation.no-responsive-animation');
            if (!$counterBoxContainers.length) return;
            self.counterBoxAnimate($counterBoxContainers);
        },

        /* Counter Box With Animation */
        counterBoxAnimate: function ($element) { // call in appear function - run when element come to screen view

            $element.each(function () {
                var countNmber = $(this).attr('data-countNmber');
                $(this).find('.counterBoxNumber').countTo({
                    from: 0,
                    to: countNmber,
                    speed: 2500,
                    refreshInterval: 10,
                });
            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  Animation For Image & Text Shortcode
        /*-----------------------------------------------------------------------------------*/

        shortcodeAnimation: function () {
            var self = this,
                $shortcodes;

            var animate_shortcodes = function (item) {
                var $this = item,
                    $delay = $this.attr('data-delay');

                if ($this.attr('data-animation') != 'none') {

                    $this.css('transition-delay', $delay + 'ms');

                    $this.addClass('do_animate');

                    if ($this.hasClass("counterBox") || $this.hasClass("pieChartBox") || $this.hasClass("progress_bar")) {

                        //Run Counter Box Animation along left animations
                        if ($this.hasClass("counterBox")) {
                            self.counterBoxAnimate($this);
                        }

                        //Run Pie Chart  Animation along left animations
                        if ($this.hasClass("pieChartBox")) {
                            self.piechartAnimate($this);
                        }

                        //Run Progress bar  Animation along left animations
                        if ($this.hasClass("progress_bar")) {
                            self.progressbarAnimate($this);
                        }
                    }

                }

            }

            var aniamte_shortcodes_in_snap_to_scroll = function () {
                var $active_slide = $('div.ep-section.visible'),
                    $inactive_slide = $('div.ep-section').not('.visible');

                if(self.isMobile || self.isTablet)
                    $shortcodes = $active_slide.find('.shortcodeAnimation:not(.no-responsive-animation)');
                else
                    $shortcodes = $active_slide.find('.shortcodeAnimation');

                $shortcodes.each(function () {
                    var $shortcode = $(this);
                    animate_shortcodes($shortcode);
                });

                $inactive_slide.find('.shortcodeAnimation').removeClass("do_animate");
            }


            if (!self.$body.hasClass('snap-to-scroll') || (self.$body.hasClass('snap-to-scroll') && self.windowWidth <= 1140)) {

                if(self.isMobile || self.isTablet)
                    $shortcodes = $('.shortcodeAnimation:not(.no-responsive-animation)');
                else
                    $shortcodes = $('.shortcodeAnimation');

                $shortcodes.waypoint({
                    handler: function () {
                        var $item = $(this.element);
                        animate_shortcodes($item);
                        this.destroy();
                    },
                    offset: '90%'
                });

            }
            else {
                self.$document.on('snap_to_scroll_slide_end', function () {
                    aniamte_shortcodes_in_snap_to_scroll();
                });

                aniamte_shortcodes_in_snap_to_scroll();

            }
        },

        /*----------------------------------------------------*/
        /* full Width colorize section 
        /*----------------------------------------------------*/

        fullWidthSection: function () {

            var self = this;

            var $containerWidth = $('.container').width(),
            $offset_block = ((self.windowWidth - parseInt($containerWidth)) / 2);
            $('.fullWidth').each(function () {
                if (self.windowWidth < 768) {
                    $(this).css({
                        'margin-left': -($offset_block + 60),
                        'padding-left': ($offset_block + 60),
                        'padding-right': ($offset_block + 60)
                    });
                } else {
                    $(this).css({
                        'margin-left': -$offset_block,
                        'padding-left': $offset_block,
                        'padding-right': $offset_block
                    });
                }
            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  tabs
        /*-----------------------------------------------------------------------------------*/

        tabs_tour_accordion: function () {

            var self = this;

            if ($('.vc_tta-tabs .vc_tta-panel:not(.vc_active) .portfolio_wrap .isotope').length > 0)
                $('.vc_tta-tabs .vc_tta-panel:not(.vc_active) .portfolio_wrap .isotope').removeClass('isotope');

            // tabs_tour_accordion Height 
            self.tabs_tour_accordion_height();

            //Tab & Tour
            $('.vc_tta-container .vc_tta-tabs .vc_tta-tabs-list a').addClass('no_djax').on('click', function (e) {
                e.preventDefault();

                var $current_tab = $(this),
                    $container = $current_tab.closest('.vc_tta-container'),
                    $currentPanelID = $($current_tab.attr('href')),
                    $previous_tab_id = $($current_tab.closest('.vc_tta-tabs-list').find('.vc_active a').attr('href'));

                if ($current_tab.closest('li').hasClass('vc_active'))
                    return;

                //activate new tab
                $container.find('.vc_tta-tabs-list li.vc_active').removeClass('vc_active');
                $current_tab.closest('li').addClass('vc_active');
                $previous_tab_id.removeClass('vc_active');

                //setTimeout(function () {
                $container.find('.vc_tta-panel').removeClass('show');
                    //$previous_tab_id.removeClass('show');
                $currentPanelID.addClass('show');
                $currentPanelID.addClass('vc_active');
                fix_tab_content_scripts($currentPanelID);
                //}, 300);
                setTimeout(function () {
                   
                 

                    //id 2 for First Load
                    self.portfolioIsotope(2);

                }, 50);

            });

            //Accordion
            $('.vc_tta-accordion .vc_tta-panel-heading a').on('click', function (e) {
                e.preventDefault();

                var $current_tab = $(this),
                    $container = $current_tab.closest('.vc_tta-accordion'),
                    $active_tab_id = $($current_tab.attr('href')),
                    $previous_tab_id = $($current_tab.closest('.vc_tta-panels').find('.vc_active a').attr('href'));

                //activate new tab
                if ($container.hasClass('vc_tta-o-all-clickable')) {
                    $active_tab_id.toggleClass('vc_active');
                    $active_tab_id.find('.vc_tta-panel-body').slideToggle();
                }
                else {
                    if ($active_tab_id.hasClass('vc_active'))
                        return;

                    $container.find('.vc_tta-panel.vc_active').removeClass('vc_active');
                    $active_tab_id.addClass('vc_active');

                    $previous_tab_id.find('.vc_tta-panel-body').slideUp();
                    $active_tab_id.find('.vc_tta-panel-body').slideDown();
                }

            });

            var fix_tab_content_scripts = function ($active_tab_id) {
                if ($active_tab_id.find('.products.isotope').length > 0)
                    $active_tab_id.find('.products.isotope').isotope('destroy');

                if ($active_tab_id.find('.portfolio_wrap .isotope').length > 0)
                    $active_tab_id.find('.portfolio_wrap .isotope').removeClass('isotope');

                var $tabs_parent = $active_tab_id.closest('.vc_tta-panels');


                //Products
                self.product_thumbnails();
                self.product_variation();
                self.single_product2_slider();

                //Portfolio
                if ($tabs_parent.find('.vc_tta-tabs .vc_tta-panel:not(.vc_active) .portfolio_wrap .portfolioswiper').length && typeof $tabs_parent.find('.vc_tta-tabs .vc_tta-panel:not(.vc_active) .portfolio_wrap .portfolioswiper')[0].swiper != undefined)
                    $tabs_parent.find('.vc_tta-tabs .vc_tta-panel:not(.vc_active) .portfolio_wrap .portfolioswiper')[0].swiper.destroy();

                self.portfolioSlider();
                $active_tab_id.find('.portfolio_wrap').find('> div').eq(0).addClass('isotope'); // Add isotope class to portfolio to work properly
                self.portfolioIsotope(0); // pass parametr 0 cause run isotope in "first load" situation

                self.runIsotopeInProducts($active_tab_id);

                //Carousel
                self.epico_carousel($active_tab_id);

                //shortcodes
                self.epico_shortcode(true);

                //product "info on click" style
                self.products_infoOnclick();

                // tabs_tour_accordion Height 
                self.tabs_tour_accordion_height();

            }
        },

        tabs_tour_accordion_height: function () {

            $('.vc_tta-container .vc_general').each(function () {
                var $this = $(this);
                //Tabs & tour
                if ($this.hasClass('vc_tta-tabs')) {
                    var childs_height = [];
                    $this.find('.vc_tta-panel').each(function () {
                        childs_height.push($(this).outerHeight());
                    });
                    $this.find('.vc_tta-panels').css('min-height', Math.min.apply(null, childs_height));


                    $this.find('.vc_tta-panel.vc_active').addClass('show');
                }
                    //Accordion
                else if ($this.hasClass('vc_tta-accordion')) {
                    $this.find('.vc_tta-panel:not(.vc_active) .vc_tta-panel-body').slideUp();
                }
            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  toggle - FAQ
        /*-----------------------------------------------------------------------------------*/

        faq: function () {

            var $faqContainers = $('.toggle_wrap');

            if (!$faqContainers.length) return;

            $faqContainers.each(function () {
                var $container = $(this),
                    $titles = $container.find('.wpb_toggle'),
                    $contents = $container.find('.toggle_content_wrap');

                if ($container.hasClass('wpb_toggle_open')) {
                    $contents.slideDown();

                } else {
                    $contents.slideUp();

                }

                $titles.off("click").on('click',function (e) {
                    var $this = $(this);

                    $this.toggleClass('wpb_toggle_title_active');
                    var $parent = $this.parent()

                    $parent.toggleClass('wpb_toggle_open');

                    if ($parent.hasClass('wpb_toggle_open')) {
                        $parent.find('.toggle_content_wrap').slideDown();

                    } else {
                        $parent.find('.toggle_content_wrap').slideUp();

                    }
                });
            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  google map
        /*-----------------------------------------------------------------------------------*/

        //Footer Google Map
        googleMap: function () {

            var $gmapScript,
                customKey = epico_theme_vars.customApiKey,
                apiKey;
            if (parseInt(epico_theme_vars.ApiKey) != 0 && customKey != '') {
                $gmapScript = $('script[src="https://maps.googleapis.com/maps/api/js?key='.concat(customKey).concat('&callback=gmap_draw"]'));
                apiKey = customKey;
            } else {
                apiKey = 'AIzaSyCp7ygrkAHm3AfCdbhVyHfaAconfYNlON4';
                $gmapScript = $('script[src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCp7ygrkAHm3AfCdbhVyHfaAconfYNlON4&callback=gmap_draw"]'); // Epico media API Key
            }

            if ($gmapScript.length <= 0) {
                var s = document.createElement("script");
                s.type = "text/javascript";
                s.src = 'https://maps.googleapis.com/maps/api/js?key='.concat(apiKey).concat('&callback=gmap_draw');
                $("head").prepend(s);
            }
            else {
                gmap_draw();
            }

            window.gmap_draw = function () {

                if (typeof google === 'object' && typeof google.maps === 'object' && typeof $.fn.gmap3 == 'function') { // check if Google Maps API is loaded
                    if ($("#googleMap").length) {
                        $("#googleMap .section-content-container").gmap3({
                            map: {
                                options: {
                                    zoom: parseInt(epico_theme_vars.zoomLevel),
                                    disableDefaultUI: true, //  disabling zoom in touch devices
                                    disableDoubleClickZoom: true, //  disabling zoom by double click on map 
                                    center: new google.maps.LatLng(epico_theme_vars.cityMapCenterLat, epico_theme_vars.cityMapCenterLng),
                                    draggable: false, //  disable map dragging 
                                    mapTypeControl: true,
                                    navigationControl: false,
                                    scrollwheel: false,
                                    streetViewControl: false,
                                    panControl: false,
                                    zoomControl: false,
                                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                                    mapTypeControlOptions: {
                                        mapTypeIds: [google.maps.MapTypeId.ROADMAP, "Gray"]
                                    }
                                }
                            },
                            styledmaptype: {
                                id: "Gray",
                                options: {
                                    name: "Gray"
                                },

                                styles: [
                                {
                                    "featureType": "administrative",
                                    "elementType": "labels.text.fill",
                                    "stylers": [
                                        {
                                            "color": "#444444"
                                        }
                                    ]
                                },
                                        {
                                            "featureType": "landscape",
                                            "elementType": "all",
                                            "stylers": [
                                                {
                                                    "color": "#ebebeb"
                                                }
                                            ]
                                        },
                                {
                                    "featureType": "poi",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "road",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "saturation": -100
                                        },
                                        {
                                            "lightness": 45
                                        }
                                    ]
                                },
                                {
                                    "featureType": "road.highway",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "simplified"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "road.arterial",
                                    "elementType": "labels.icon",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "transit",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "water",
                                    "elementType": "all",
                                    "stylers": [
                                        {
                                            "color": "#81c4de"
                                        },
                                        {
                                            "visibility": "on"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "water",
                                    "elementType": "geometry.fill",
                                    "stylers": [
                                        {
                                            "visibility": "on"
                                        },
                                        {
                                            "color": "#a3daf1"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "water",
                                    "elementType": "labels.text",
                                    "stylers": [
                                        {
                                            "color": "#a3daf1"
                                        }
                                    ]
                                }
                                ]
                            },
                            marker: {
                                values: [{
                                    'latLng': [epico_theme_vars.cityMapCenterLat, epico_theme_vars.cityMapCenterLng]
                                }],
                                options: {
                                    'icon': new google.maps.MarkerImage(epico_theme_vars.iconMap)
                                }
                            }
                        });

                        if ((parseInt(epico_theme_vars.customMap)) == 1) {
                            $('#googleMap .section-content-container').gmap3('get').setMapTypeId("Gray");//Display Gray Map On Load  if we don't have this line map loads in default
                        }

                    }
                }
            };

        },

        /*-----------------------------------------------------------------------------------*/
        /*  Navigation
        /*-----------------------------------------------------------------------------------*/

        nav: function () {

            var self = this;

            if(self.windowWidth <= 1140)
                return;

            var $checkFixed = self.$epHeader.attr("data-fixed"); // Check menu fixed option is enable (1) or disable (0)

            var $menuItem = $('.navigation > ul li.menu-item-has-children');

            $menuItem.each(function () {

                var $menuWrapper = $(this).find('.menu-item-wrapper'),
                    $secondlevelItems = $menuWrapper.find('> ul > li'),
                    $rightOffset = 0;//store the offset of megamenu from right side of screen

                if ($(this).hasClass('mega-menu-parent')) {
                    $menuWrapper.find('.special-last-child').closest('.menu-item-wrapper').addClass('has-special-last-child');

                    $menuWrapper.width($secondlevelItems.length * $secondlevelItems.eq(0).outerWidth());
                    $menuWrapper.height($menuWrapper.find('> ul').height());
                    $menuWrapper.css('margin-left', '');//Reset margin-left to calculate correct position
                    var $rightOffset = self.windowWidth - ($menuWrapper.offset().left + $menuWrapper.outerWidth());
                    if ($rightOffset < 0) {
                        $menuWrapper.css('margin-left', $rightOffset); // cause mega menu be in window 
                    }
                }
                else {
                    var $subMenuWidth = $(this).find('> ul').eq(0).outerWidth();
                    var $rightOffset = self.windowWidth - ($(this).offset().left + $subMenuWidth);
                    if ($rightOffset < $subMenuWidth) {
                        $(this).addClass('left-submenus');
                    }
                }
            });


            //Mega menu
            $menuItem.mouseover(function () {
                if (!$(this).hasClass('hover'))
                    $(this).addClass("hover");

            }).mouseout(function () {
                $(this).removeClass("hover");
            });

        },

        UpdateCurrentMenuAncestorClass: function () {
            var self = this;
            if(self.windowWidth <= 1140)
                return;

            $('header .navigation > ul > li').removeClass('current-menu-ancestor');
            $('header .navigation > ul > li').removeClass('current-menu-item');
            $('header .navigation li li ul li.active').parents('.navigation > ul > li.menu-item-has-children').addClass('current-menu-ancestor');
        },

        /*-----------------------------------------------------------------------------------*/
        /* FullScreen Slider
        /*-----------------------------------------------------------------------------------*/
        fullScreenSliderInit: function () {

            var self = this;

            if (self.msie > 0 || self.msie11) { // Change home slider position if browser be ie
                $("#home").css('position', 'static');
            }

            self.windowHeight = self.$window.height();
            var wpAdminBarHeight = 0;
            if ($('#wpadminbar').length)
                wpAdminBarHeight = 32;

            if (self.windowWidth > 979) { // because in screen biger than tablet when wpadminbar is enable Added scroll bar.
                self.windowHeight = self.windowHeight - wpAdminBarHeight;
                $('#home').css('height', self.windowHeight);
            }
            else {
                $('#home').css('height', '');
            }

            $('#fullScreenSlider .swiper-container').css('height', self.windowHeight);
            $('#fullScreenSlider .caption').each(function (i) {
                //get height of its childeren as height of caption ( becouse caption is absolute, we need height of chideren to centralize it)
                var captionHeight = 0,
                    $this = $(this);
                $this.children('.caption-container').each(function () {
                    captionHeight = captionHeight + $(this).outerHeight();
                });

                if ($this.hasClass('bottom') && self.windowWidth > 1140) {
                    var bottomOffset = 20;

                    if ($('#caption-start').length)
                        bottomOffset = 40;

                    if (captionHeight >= self.windowHeight / 3) {
                        $this.css('padding-top', (self.windowHeight - captionHeight - 2 * bottomOffset) + 'px');
                    }
                    else {
                        $this.css('padding-top', (((self.windowHeight) / 3) * 2 - bottomOffset) + 'px');

                    }
                }
                else {
                    $this.css('padding-top', (self.windowHeight - captionHeight) / 2 + 'px');
                }

            });

            self.homeSlideButton();

            //set appropriate width of slider
            self.sliderWidth = parseInt(self.windowWidth);
            if (self.$body.hasClass('vertical_menu_enabled') && self.windowWidth > 979) {
                self.sliderWidth = self.sliderWidth - $('aside.vertical_menu_area').outerWidth();
            }
            self.imagePosition = self.sliderWidth - 170;

        },

        fullScreenImageInit: function () {
            var self = this;

            //set caption position when have just one slide
            var $caption = $('#fullScreenImage .caption');
            //get height of its childeren as height of caption ( becouse caption is absolute, we need height of chideren to centralize it)
            var captionHeight = 0;
            $caption.children('.caption-container').each(function () {
                captionHeight = captionHeight + $(this).outerHeight();
            });

            if ($caption.hasClass('bottom')) {
                var bottomOffset = 20;

                if ($('#caption-start').length)
                    bottomOffset = 50;

                if (captionHeight >= self.windowHeight / 3) {
                    $caption.css('padding-top', (self.windowHeight - captionHeight - 2 * bottomOffset) + 'px');
                }
                else {
                    $caption.css('padding-top', (((self.windowHeight) / 3) * 2 - bottomOffset) + 'px');

                }

            }
            else {
                $caption.css('padding-top', (self.windowHeight - captionHeight) / 2 + 'px');
            }

            $caption.addClass('show');

            self.homeSlideButton();
        },

        fullScreenSlider: function () {

            var self = this;

            var $slider = $('#fullScreenSlider');
            var $slides,
                $second_slide,
                $first_slide,
                $firstDuplicateSlide,
                $secondDuplicateSlide,
                firstDupslideHasVideo,
                secondDupslideHasVideo;

            if ($slider.hasClass('epico')) {

                var $slideNum = $slider.find('.swiper-slide').length + 2,
                    $initiated = false;

                var swiper = new Swiper('#fullScreenSlider .swiper-container', {
                    loop: true,
                    speed: 700,
                    initialSlide: 0,
                    keyboardControl: true,
                    autoplay: 4500,
                    autoplayDisableOnInteraction: false,
                    effect: 'fade',
                    simulateTouch: false,
                    onlyExternal: true,//Add this to disable touch behavior in touch devices
                    slidesPerView: 1,
                    onInit: function () {
                        $initiated = true;
                        $slides = $slider.find('.swiper-slide');
                        $second_slide = $slides.eq(2);
                        $first_slide = $slides.eq(1);
                        $firstDuplicateSlide = $slides.filter('.swiper-slide-duplicate').eq(0);
                        $secondDuplicateSlide = $slides.filter('.swiper-slide-duplicate').eq(1);
                        firstDupslideHasVideo = $firstDuplicateSlide.hasClass('has-duplicate-video');
                        secondDupslideHasVideo = $secondDuplicateSlide.hasClass('has-duplicate-video');
                        //Trick for set appropriate position of mejs-container of video
                        $slider.find('.mejs-container').css("position", "fixed");
                        setTimeout(function () {
                            $slider.find('.mejs-container').css("position", "relative");
                        }, 10);

                        //remove video of first(duplicate) slide
                        if (firstDupslideHasVideo) {
                            $firstDuplicateSlide.find('.swiper-slide-image').remove();
                        }

                        //remove video of last(duplicate) slide
                        if (secondDupslideHasVideo) {
                            $secondDuplicateSlide.find('.swiper-slide-image').remove();
                        }

                        if ($.browser.mozilla == true) {
                            $slider.find('.swiper-wrapper').add($slides).css("top", "-0.1px");
                        }

                    },
                    onSetTranslate: function (swiper, translate) {
                        if ($initiated) // check it becouse onSetTranslate fire before onInit
                        {
                            //Reset
                            $slides.filter('.swiper-slide-prev2').removeClass('swiper-slide-prev2');
                            $slides.filter('.swiper-slide-next2').removeClass('swiper-slide-next2');
                            $slides.filter('.fake-active').removeClass('fake-active');
                            $first_slide.removeClass('have-transition');
                            $slides.eq($slideNum - 2).css('z-index', '').removeClass('have-transition');

                            if ($secondDuplicateSlide.hasClass('swiper-slide-active'))//at end of slider
                            {
                                if (!$second_slide.hasClass('swiper-slide-prev')) {
                                    $second_slide.addClass('swiper-slide-next2');
                                    $first_slide.addClass('swiper-slide-active fake-active');
                                    if (secondDupslideHasVideo)
                                        $first_slide.addClass('have-transition');
                                }
                                if (!$firstDuplicateSlide.hasClass('has-duplicate-video'))
                                    $firstDuplicateSlide.addClass('swiper-slide-prev2');
                            }
                            else if ($firstDuplicateSlide.hasClass('swiper-slide-active')) {
                                $slides.eq($slideNum - 3).addClass('swiper-slide-prev2');
                                $slides.eq($slideNum - 2).addClass('swiper-slide-active fake-active');
                                if (firstDupslideHasVideo)
                                    $slides.eq($slideNum - 2).addClass('have-transition');

                                if (!secondDupslideHasVideo)
                                    $secondDuplicateSlide.addClass('swiper-slide-next2');
                            }

                            //correct position of slide when we have video at end slide
                            if (firstDupslideHasVideo) {
                                if ($firstDuplicateSlide.hasClass('swiper-slide-prev'))
                                    $slides.eq($slideNum - 2).addClass('swiper-slide-prev2');

                                //Correct an exception when have 3slide( just happen in left-side sliding)
                                if ($slideNum == 5 && $slides.eq($slideNum - 3).hasClass('swiper-slide-active') && swiper.activeIndex > swiper.previousIndex)
                                    $slides.eq($slideNum - 2).css('cssText', 'z-index: 1 !important;'); // Hide it ( send it to back)

                            }

                            var $prevSlides = $slides.filter('.swiper-slide-prev,.swiper-slide-prev2');
                            var $nextSlides = $slides.filter('.swiper-slide-next,.swiper-slide-next2');

                            //set posiitons after starting slider
                            $slides.css("-webkit-transform", "");
                            $slides.filter('.swiper-slide-duplicate.has-duplicate-video').css("transform", "");

                            $slides.not('.swiper-slide-prev,.swiper-slide-prev2,.swiper-slide-next,.swiper-slide-next2,.swiper-slide-active,.swiper-slide-duplicate.has-duplicate-video').css("transform", "matrix(1,0,0,1," + self.sliderWidth + ",0)");
                            $slides.not('.swiper-slide-prev,.swiper-slide-prev2,.swiper-slide-next,.swiper-slide-next2,.swiper-slide-active,.swiper-slide-duplicate.has-duplicate-video').find('.swiper-slide-image').css("transform", "matrix(1,0,0,1,-" + self.imagePosition + ",0)");

                            $prevSlides.css("transform", "matrix(1,0,0,1,-" + self.sliderWidth + ",0)");
                            $prevSlides.find('.swiper-slide-image').css("transform", "matrix(1,0,0,1," + self.imagePosition + ",0)");

                            $nextSlides.css("transform", "matrix(1,0,0,1," + self.sliderWidth + ",0)");
                            $nextSlides.find('.swiper-slide-image').css("transform", "matrix(1,0,0,1,-" + self.imagePosition + ",0)");

                            $slides.filter('.swiper-slide-active').css("transform", "matrix(1,0,0,1,0,0)");
                            $slides.filter('.swiper-slide-active').find('.swiper-slide-image').css("transform", "matrix(1,0,0,1,0,0)");

                        }
                        else {
                            //set posiitons before starting slider(we seperate it because of $slides is not initiated at this time and we should use $slider object)
                            $slider.find('.swiper-slide').css("-webkit-transform", "");
                            $slider.find('.swiper-slide').not('.swiper-slide-prev,.swiper-slide-prev2,.swiper-slide-next,.swiper-slide-next2,.swiper-slide-active').css("transform", "matrix(1,0,0,1," + self.sliderWidth + ",0)");
                            $slider.find('.swiper-slide').not('.swiper-slide-prev,.swiper-slide-prev2,.swiper-slide-next,.swiper-slide-next2,.swiper-slide-active').find('.swiper-slide-image').css("transform", "matrix(1,0,0,1,-" + self.imagePosition + ",0)");

                            $slider.find('.swiper-slide-prev,.swiper-slide-prev2').css("transform", "matrix(1,0,0,1,-" + self.sliderWidth + ",0)");

                            $slider.find('.swiper-slide-prev .swiper-slide-image').css("transform", "matrix(1,0,0,1," + self.imagePosition + ",0)");
                            $slider.find('.swiper-slide-prev2 .swiper-slide-image').css("transform", "matrix(1,0,0,1," + self.imagePosition + ",0)");

                            $slider.find('.swiper-slide-next').css("transform", "matrix(1,0,0,1," + self.sliderWidth + ",0)");
                            $slider.find('.swiper-slide-next2').css("transform", "matrix(1,0,0,1," + self.sliderWidth + ",0)");

                            $slider.find('.swiper-slide-next .swiper-slide-image').css("transform", "matrix(1,0,0,1,-" + self.imagePosition + ",0)");
                            $slider.find('.swiper-slide-next2 .swiper-slide-image').css("transform", "matrix(1,0,0,1,-" + self.imagePosition + ",0)");
                            $slider.find('.swiper-slide-active').css("transform", "matrix(1,0,0,1,0,0)");
                            $slider.find('.swiper-slide-active .swiper-slide-image').css("transform", "matrix(1,0,0,1,0,0)");
                        }

                    }
                });

                $slider.find('.swiper-button-next').add('#fullScreenSlider .arrows-button-next').bind('click', function (e) {
                    e.preventDefault();
                    swiper.slideNext();
                });

                $slider.find('.swiper-button-prev').add('#fullScreenSlider .arrows-button-prev').bind('click', function (e) {
                    e.preventDefault();
                    swiper.slidePrev();
                });


                swiper.off('onClick');
                swiper.off('onTouchStart');
                swiper.off('onTouchEnd');
                swiper.off('onTouchMove');
                swiper.off('onTouchMoveOpposite');

            }
            else if ($slider.hasClass('slide')) {
                var $slideNum = $slider.find('.swiper-slide').length + 2,
                    $initiated = false;

                var swiper = new Swiper('#fullScreenSlider .swiper-container', {
                    loop: true,
                    loopedSlides: 0,
                    speed: 1050,
                    initialSlide: 0,
                    keyboardControl: true,
                    autoplay: 4500,
                    followFinger: true,
                    longSwipesMs: 600,
                    mousewheelControl: false,
                    autoplayDisableOnInteraction: false,
                    nextButton: '#fullScreenSlider .arrows-button-next',
                    prevButton: '#fullScreenSlider .arrows-button-prev',
                    slidesPerView: 1,
                    onInit: function () {
                        $initiated = true;
                        $first_slide = $slider.find('.swiper-slide').eq(1);
                        $firstDuplicateSlide = $slider.find('.swiper-slide-duplicate').eq(0);
                        $secondDuplicateSlide = $slider.find('.swiper-slide-duplicate').eq(1);
                        firstDupslideHasVideo = $firstDuplicateSlide.hasClass('has-duplicate-video');
                        secondDupslideHasVideo = $secondDuplicateSlide.hasClass('has-duplicate-video');
                        //Trick for set appropriate position of mejs-container of video
                        $slider.find('.mejs-container').css("position", "fixed");
                        setTimeout(function () {
                            $slider.find('.mejs-container').css("position", "relative");
                        }, 10);

                        //remove video of first(duplicate) slide
                        if (firstDupslideHasVideo) {
                            $firstDuplicateSlide.find('.swiper-slide-image').remove();
                        }

                        //remove video of last(duplicate) slide
                        if (secondDupslideHasVideo) {
                            $secondDuplicateSlide.find('.swiper-slide-image').remove();
                        }

                    },
                    onTouchStart: function (swiper) {
                        if (secondDupslideHasVideo) {
                            //get back first slide to initial position
                            if (swiper.activeIndex == 2)
                                $first_slide.css('transform', "");
                        }


                        if (firstDupslideHasVideo) {
                            //get back last slide to initial position
                            if (swiper.activeIndex == 2)
                                $slider.find('.swiper-slide').eq($slideNum - 2).css('transform', '');
                        }
                    },
                    onSetTranslate: function (swiper, translate) {
                        if ($initiated) // check it becouse onSetTranslate fire before onInit
                        {

                            if (secondDupslideHasVideo) {

                                //send first slide to last slide position to be as a duplicate slide
                                if (swiper.activeIndex == $slideNum - 2) {

                                    $first_slide.css('transform', 'translate3d(' + ((swiper.activeIndex) * self.sliderWidth) + 'px,0,0)');
                                }
                                else if (swiper.activeIndex == 1) {
                                    $first_slide.css('transform', ""); //get back first slide to initial position
                                }
                            }

                            if (firstDupslideHasVideo) {

                                //send last slide to first slide position to be as a duplicate slide
                                if (swiper.activeIndex == 1) {
                                    $slider.find('.swiper-slide').eq($slideNum - 2).css('transform', 'translate3d(-' + (($slideNum - 2) * self.sliderWidth) + 'px,0,0)')
                                }
                                else if (swiper.activeIndex == $slideNum - 2)//get back last slide to initial position
                                {
                                    $slider.find('.swiper-slide').eq($slideNum - 2).css('transform', '');
                                }

                            }

                            if ($secondDuplicateSlide.hasClass('swiper-slide-active')) {
                                $first_slide.addClass('swiper-slide-active');

                            } else if ($firstDuplicateSlide.hasClass('swiper-slide-active')) {
                                $slider.find('.swiper-slide').eq($slideNum - 2).addClass('swiper-slide-active');
                            }
                        }
                    },

                });
            }
            else {
                var $slideNum = $slider.find('.swiper-slide').length + 2,
                    $initiated = false;

                var swiper = new Swiper('#fullScreenSlider .swiper-container', {
                    loop: true,
                    speed: 1000,
                    initialSlide: 0,
                    keyboardControl: true,
                    autoplay: 4500,
                    autoplayDisableOnInteraction: false,
                    effect: 'fade',
                    simulateTouch: false,
                    onlyExternal: false,
                    mousewheelControl: false,
                    slidesPerView: 1,
                    onInit: function () {
                        $initiated = true;
                        $slides = $slider.find('.swiper-slide');
                        $second_slide = $slides.eq(2);
                        $first_slide = $slides.eq(1);
                        $firstDuplicateSlide = $slides.filter('.swiper-slide-duplicate').eq(0);
                        $secondDuplicateSlide = $slides.filter('.swiper-slide-duplicate').eq(1);
                        firstDupslideHasVideo = $firstDuplicateSlide.hasClass('has-duplicate-video');
                        secondDupslideHasVideo = $secondDuplicateSlide.hasClass('has-duplicate-video');
                        //Trick for set appropriate position of mejs-container of video
                        $slider.find('.mejs-container').css("position", "fixed");
                        setTimeout(function () {
                            $slider.find('.mejs-container').css("position", "relative");
                        }, 10);


                        //remove video of first(duplicate) slide
                        if (firstDupslideHasVideo) {
                            $firstDuplicateSlide.find('.swiper-slide-image').remove();
                        }

                        //remove video of last(duplicate) slide
                        if (secondDupslideHasVideo) {
                            $secondDuplicateSlide.find('.swiper-slide-image').remove();
                        }


                        if ($.browser.mozilla == true) {
                            $slider.find('.swiper-wrapper').add($slides).css("top", "-0.1px");
                        }


                    },
                    onSetTranslate: function (swiper, translate) {
                        if ($initiated) // check it becouse onSetTranslate fire before onInit
                        {
                            //Reset
                            $slides.css("visibility", "").filter('.fake-active').removeClass('fake-active');

                            if ($secondDuplicateSlide.hasClass('swiper-slide-active')) {
                                //Active slides
                                $first_slide.addClass('fake-active swiper-slide-active');
                                if (secondDupslideHasVideo)
                                    $first_slide.nextAll().css({ 'visibility': 'hidden', 'opacity': '0' });
                            }

                            //correct position of slide when slide are at the end of slide
                            if ($firstDuplicateSlide.hasClass('swiper-slide-active')) {
                                $slides.eq($slideNum - 2).addClass('fake-active swiper-slide-active');
                                if (firstDupslideHasVideo)
                                    $slides.eq($slideNum - 2).prevAll().css({ 'visibility': 'hidden', 'opacity': '0' });
                            }
                        }
                    }
                });

                $slider.find('.swiper-button-next').add('#fullScreenSlider .arrows-button-next').bind('click', function (e) {
                    e.preventDefault();
                    swiper.slideNext();
                });

                $slider.find('.swiper-button-prev').add('#fullScreenSlider .arrows-button-prev').bind('click', function (e) {
                    e.preventDefault();
                    swiper.slidePrev();
                });
            }

        },

        homeSlideButton: function () {
            var self = this;

            $("#caption-start").on('click', function () {
                var $next_section = $(this).parents('section#home').next('#startHere');

                self.scroll_to($next_section, 1);
            });

        },

        /*-----------------------------------------------------------------------------------*/
        /*  blog toggle & blog toggle load more 
        /*-----------------------------------------------------------------------------------*/

        // blog toggle click 
        blogToggleClick: function (postVar) {

            var self = this;

            var $toggleMode = parseInt(postVar.$accordion.attr("data-value"));

            if ($toggleMode === 0) {

                var $accordionHeight = "350px";
                if (self.windowWidth > 480)
                    $accordionHeight = "520px";

                postVar.$content.fadeIn();


                postVar.$frameTitle.css({
                    opacity: 0.3,
                    'background-color': '#fff',
                    height: '160px'
                }),

                //post title And Date animation css
                postVar.$monthTitle.css({
                    'border-left-color': '#fff',
                    left: '-90px'
                });


                postVar.$titleImage.toggleClass('accordion_closed'),
                postVar.$accordion.toggleClass('accordionClosed'),


                // change data Value 
                postVar.$accordion.attr("data-value", "1");

            } else if ($toggleMode === 1 || isNaN($toggleMode)) {

                postVar.$content.fadeOut('fast');
                postVar.$frameTitle.css({
                    opacity: 1,
                    'background-color': 'transparent',
                    height: '100%'
                })

                //post title And Date animation css
                postVar.$day.css({
                    width: '130px'
                }),

                postVar.$monthTitle.css({
                    'border-left-color': '#fff',
                    left: '0px'
                }),

                postVar.$monthTitle.find('.monthYear').css({
                    left: '0px',
                }),

                postVar.$monthTitle.find('.blogTitle').css({
                    left: '0px',
                });

                postVar.$titleImage.toggleClass('accordion_closed');
                postVar.$accordion.toggleClass('accordionClosed'),

                // change data Value    
                postVar.$accordion.attr("data-value", "0");
            }

            self.reInitDjax();

        },

        // blog toggle default set 
        blogToggleSet: function (postVar) {
            var self = this;
            if (postVar.$flag === 0) {
                // Set Close Mode 
                postVar.$content.slideUp(function () {
                    self.parallaxImg();
                });

                postVar.$titleImage.toggleClass('accordion_closed');

            } else if (postVar.$flag === 1 || isNaN(postVar.$flag)) {

                postVar.$accordion_box10.add(postVar.$accordionBox).animate(
                    { height: postVar.$imgHeight },
                    {
                        queue: false,
                        duration: 500
                    })

                postVar.$frameTitle.css({
                    opacity: 0.3,
                    'background-color': '#fff',
                    height: '160px'
                })

            }

        },

        blogToggleArray: function ($thisAccordion) {

            var $accordion = $thisAccordion,
            $titleImage = $accordion.find('.image'),
            $imgH = $titleImage.find('img'),
            $noImage = $titleImage.find('.noImage'),
            $content = $accordion.find('.accordion_content'),
            $accordion_box2 = $accordion.find('.accordion_box2'),
            $accordion_box10 = $accordion.find('.accordion_box10'),
            $flag = parseInt($accordion.attr("data-value")),
            $blogClose = $accordion.find('.blogClose'),
            $minus = $accordion.find('.minus'),
            $plus = $accordion.find('.plus'),
            $accordionBox = $accordion.find('.accordionBox'),
            $frameTitle = $accordion.find('.frameTitle'),
            $day = $accordion.find('.accordion_title'),
            $monthTitle = $accordion.find('.leftBorder');


            var postVar = {
                $accordion: $accordion,
                $titleImage: $titleImage,
                $imgH: $imgH,
                $noImage: $noImage,
                $content: $content,
                $accordion_box2: $accordion_box2,
                $accordion_box10: $accordion_box10,
                $flag: $flag,
                $minus: $minus,
                $plus: $plus,
                $accordionBox: $accordionBox,
                $frameTitle: $frameTitle,
                $day: $day,
                $monthTitle: $monthTitle,
            };

            return postVar;
        },

        // blog toggle
        blogToggle: function () {

            var self = this;

            $('.blogAccordion').each(function () {

                var $accordion = $(this),
                    $titleImage = $accordion.find('.image'),
                    $imgH = $titleImage.find('img'),
                    $noImage = $titleImage.find('.noImage'),
                    $content = $accordion.find('.accordion_content'),
                    $accordion_box2 = $accordion.find('.accordion_box2'),
                    $accordion_box10 = $accordion.find('.accordion_box10'),
                    $flag = parseInt($accordion.attr("data-value")),
                    $blogClose = $accordion.find('.blogClose'),
                    $minus = $accordion.find('.minus'),
                    $plus = $accordion.find('.plus'),
                    $accordionBox = $accordion.find('.accordionBox'),
                    $frameTitle = $accordion.find('.frameTitle'),
                    $day = $accordion.find('.accordion_title'),
                    $monthTitle = $accordion.find('.leftBorder');

                var postVar = {
                    $accordion: $accordion,
                    $titleImage: $titleImage,
                    $imgH: $imgH,
                    $noImage: $noImage,
                    $content: $content,
                    $accordion_box2: $accordion_box2,
                    $accordion_box10: $accordion_box10,
                    $flag: $flag,
                    $minus: $minus,
                    $plus: $plus,
                    $accordionBox: $accordionBox,
                    $frameTitle: $frameTitle,
                    $day: $day,
                    $monthTitle: $monthTitle,
                };

                // set toggle mode When Page Loaded
                self.blogToggleSet(postVar);

                $minus.add($plus).add($blogClose).click(function () {
                    // toggle Post When Click Event Occur 
                    self.blogToggleClick(postVar);
                });

            });

        },

        /* blog toggle loadmore */
        blogToggleLoadmore: function () {

            var self = this;

            $(".posts-page-" + (self.blogPageNum + 1)).find('.blogAccordion').each(function () {
                var $accordion = $(this),
                    $title = $accordion.find('.accordion_title'),
                    $titleImage = $accordion.find('.image'),
                    $imgH = $titleImage.find('img'),
                    $noImage = $titleImage.find('.noImage'),
                    $content = $accordion.find('.accordion_content'),
                    $accordion_box2 = $accordion.find('.accordion_box2'),
                    $accordion_box10 = $accordion.find('.accordion_box10'),
                    $flag = parseInt($accordion.attr("data-value")),
                    $blogClose = $accordion.find('.blogClose'),
                    $minus = $accordion.find('.minus'),
                    $plus = $accordion.find('.plus'),
                    $accordionBox = $accordion.find('.accordionBox'),
                    $frameTitle = $accordion.find('.frameTitle'),
                    $day = $accordion.find('.accordion_title'),
                    $monthTitle = $accordion.find('.leftBorder');

                var postLoadVar = {
                    $accordion: $accordion,
                    $titleImage: $titleImage,
                    $imgH: $imgH,
                    $noImage: $noImage,
                    $content: $content,
                    $accordion_box2: $accordion_box2,
                    $accordion_box10: $accordion_box10,
                    $flag: $flag,
                    $minus: $minus,
                    $plus: $plus,
                    $accordionBox: $accordionBox,
                    $frameTitle: $frameTitle,
                    $day: $day,
                    $monthTitle: $monthTitle,
                };

                // set toggle mode When Page Loaded
                self.blogToggleSet(postLoadVar);

                $minus.add($plus).add($blogClose).click(function () {
                    // toggle Post When Click Event Occur 
                    self.blogToggleClick(postLoadVar);
                });

            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  Blog Load More Function 
        /*-----------------------------------------------------------------------------------*/

        blogLoadMore: function () {

            var self = this,
                $loadBTN = $('.pageNavigation');

            if (typeof paged_data == 'undefined' || $loadBTN.length < 1)
                return;

            var max = 1;

            if ($loadBTN.hasClass('cartBlog')) {
                var $uniqueId = "#" + $loadBTN.siblings('.isotope').attr('data-id'),
                    $blog = $($uniqueId).first();
                max = $blog.data('maxpages');
            }
            else {
                max = parseInt(paged_data.maxPages);
            }

            if (max < 2)
                return;

            //Replace links with load more button
            $loadBTN.html('<div class="readmore clearfix"><div class="loadMore loadmoreactive"><span class="text load-more-text">' + paged_data.loadMoreText + '</span><span class="text loading-text">' + paged_data.loadingText + '</span><span class="text no-more-text">' + paged_data.noMorePostsText + '</span></div></div>');



            $('.loadMore').click(function () {
                var $btn = $(this);
                $loadBTN = $(this).parents('.pageNavigation');

                if ($loadBTN.hasClass('cartBlog')) {// It is a card blog

                    var $uniqueId = " #" + $loadBTN.siblings('.isotope').attr('data-id'),
                        $blog = $($uniqueId).first(),
                        $isCardBlog = true;
                    // Next line finds the first hidden page   
                    var startPage = $blog.data('page'),
                        nextPage = startPage + 1,
                        max = $blog.data('maxpages'),
                        isLoading = false;
                    //Next line stores the pages that appeared by far
                    $blog.data('page', nextPage);


                } else {// It is classic blog
                    var startPage = parseInt(paged_data.startPage),
                    nextPage = startPage + 1,
                    max = parseInt(paged_data.maxPages),
                    isLoading = false;
                    var $blog = "#blogLoop",
                        $uniqueId = ' .post';
                }

                if (max < 2) return;

                //Activate loadmore button 
                if (nextPage > max)
                    $btn.removeClass('loadingactive').addClass('loadmoreactive');

                if (nextPage > max || isLoading)
                    return;

                isLoading = true;

                //Set loading text
                $(this).removeClass('loadmoreactive').addClass('loadingactive');
                var $pageContainer = $('<div class="posts-page-' + nextPage + '"></div>');

                //Next line is for creating a valid link to next page           
                paged_data.nextLink = paged_data.nextLink.replace(/\/page\/[0-9]+/, '/?postpage=' + nextPage);
                paged_data.nextLink = paged_data.nextLink.replace(/\?postpage=[0-9]+/, '?postpage=' + nextPage);

                $pageContainer.load(paged_data.nextLink + $uniqueId, function () {

                    //Insert the posts container before the load more button
                    $pageContainer.waitForImages(function () { //loads gallery in classic blog load more
                        var $content;
                        if ($isCardBlog) {
                            $content = $($pageContainer.html());
                            $content = $($content.html());
                        } else {
                            $content = $pageContainer;
                        }

                        $content.hide().appendTo($blog).fadeIn('fast');

                        if ($isCardBlog) {
                            var $container = $($uniqueId);
                            $container.isotope('insert', $content);
                            setTimeout(function () {
                                // call isotope animation ( defualt and custom mode )
                                self.isotopeAnimation($blog);
                                self.lazyLoadOnLoad($container);
                                self.parallaxImg();
                                self.epico_blogMasonry(true);//reload isotope and swiper for card blog
                            }, 500);
                        }

                        // Update page number and nextLink.
                        paged_data.startPage = paged_data.startPage.replace(/[0-9]+/, startPage + 1);

                        if (nextPage < max) {
                            $btn.removeClass('loadingactive').addClass('loadmoreactive');
                        } else if (nextPage >= max) {
                            $btn.removeClass('loadingactive loadmoreactive').addClass('nomoreactive');
                        }

                        isLoading = false;

                        if (!($isCardBlog)) {
                            self.blogPageNum = nextPage;
                            self.blogPageNum--;

                            self.blogToggleLoadmore();
                            self.blogPostSlider();
                            self.fitVideo();
                        }

                    });


                });

            });

            self.reInitDjax();
        },

        /*-----------------------------------------------------------------------------------*/
        /*  Home Height 
        /*-----------------------------------------------------------------------------------*/

        homeHeight: function (callback) {

            var self = this;

            var $wpAdminBarHeight = $('#wpadminbar').height();

            //Wordpress Admin Bar Height
            var checkWpBar = function () {
                var $HSlMHeight = self.windowHeight;
                if (!isNaN($wpAdminBarHeight)) {
                    $HSlMHeight = $HSlMHeight - $wpAdminBarHeight;
                }
                return $HSlMHeight;
            }

            var $HSlMVal = checkWpBar();

            // Portfolio detail swiperslider Fullscreen 
            if ($('.portfolio_detail_full_width .pDHeader-standard #portfolio-detail-parallax-container').length > 0 || $('.portfolio_detail_boxed .pDHeader-standard #portfolio-detail-parallax-container').length > 0) {

                var $marginHeader = 75,
                    $marginSwiper = 75;

                if ($('.portfolio_detail_boxed .pDHeader-standard #portfolio-detail-parallax-container').length > 0) {
                    $marginHeader = 95;
                    $marginSwiper = 55;
                }


                $(".pDHeader-standard").css({ height: ($HSlMVal - $marginHeader) + 'px' });
                $(".pDHeader-standard .swiper-container").css({ height: ($HSlMVal - $marginSwiper) + 'px' });

                // Add buttom Padding For Portfolio Fullwidth and Portfolio boxed when Page Not Scroll Defualt
                //Set it for tablet devices and desktops
                if (self.$body.height() < self.windowHeight && self.windowWidth > 1140) {

                    var $bodyheight = self.$body.height();
                    var $windowsHeight = self.windowHeight;
                    var $calc = $windowsHeight - self.$bodyheight;

                    $calc = $calc + 5;

                    $("#PDetail .pDcontent").css({ "padding-bottom": $calc });

                }

            }

            // Image Fullscreen 
            if ($('.fullScreenImage').length !== 0) {
                $("#fullScreenImage").css({ height: $HSlMVal + 'px' });
            }

            if (self.windowWidth > 1140) {
                // Revolution Slider
                if ($('#homeHeight').height() > 0) {

                    var $LHeight = $('#homeHeight').height();
                    $LHeight = $LHeight - 6;

                    if (!isNaN($wpAdminBarHeight)) {
                        $LHeight = $LHeight - $wpAdminBarHeight;
                    }

                    $LHeight = 0;
                    // FullScreen Slider -- FullScreen Video  -- FullScreen GoolgeMap 
                } else if ($('#fullScreenSlider').length !== 0 || $('#fullScreenImage').length !== 0) {
                    if (self.msie > 0 || !!self.msie11) { // If Internet Explorer, Margin-top 0

                        if ($('#fullScreenImage').length !== 0) {
                            $("#main").css({ marginTop: $HSlMVal + 'px' });
                        } else {
                            $("#main").css({ marginTop: 0 + 'px' });
                        }

                    } else {
                        $("#main").css({ paddingTop: $HSlMVal + 'px' });
                    }

                    if ($('#fullScreenImage').length !== 0) {

                        if (self.msie > 0 || !!self.msie11) { // If Internet Explorer, Margin-top 0

                            $('#home .homeWrap .fullScreenImage').css({ position: 'static' })
                        }
                    }

                    $HSlMVal = 0;
                }
                else {//Reset the top margin for pages without intro

                    $("#main").css({ marginTop: 0 + 'px' });
                }

            } else {
                $('#main').css({ marginTop: 0 });
            }

            return true;

        },

        /*------------------------------------------------------------------------------*/
        /*  phone Navigation
        /*------------------------------------------------------------------------------*/

        mobileNavigation: function () {

            var self = this;

            $('#mobile-menu-button').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var $this = $(this),
                    $menu = $('#mobile-menu-items');

                if ($this.hasClass('active')) {
                    $menu.slideUp('300', 'easeInOutCirc');
                    $this.removeClass('active');
                    $('#mobile-menu-overlay').removeClass('active');
                }
                else {
                    $menu.slideDown('300', 'easeInOutCirc');
                    $this.addClass('active');
                    $('#mobile-menu-overlay').addClass('active');
                }
            });


            $('.toggle_submneu').on('click', function () {
                var $toggle = $(this);

                if ($toggle.hasClass('open')) {
                    $toggle.closest('li').removeClass('submenu-open');
                    $toggle.siblings('ul.sub-menu').slideUp('300', 'easeInOutCirc');
                }
                else {
                    $toggle.closest('li').addClass('submenu-open');
                    $toggle.siblings('ul.sub-menu').slideDown('300', 'easeInOutCirc');
                }

                $toggle.toggleClass('open');
            });

            $('.navigation-mobile a,#mobile-menu-overlay').on('click', function (e) {
                e.stopPropagation();
                $('#mobile-menu-button').trigger('click');
            });

            self.mobileNavigationContainerHeight();

        },

        mobileNavigationContainerHeight: function () {
            var self = this;
            // This Code Use When Menu Item Height Higher than Device Height
            var nav_new_height = self.$window.outerHeight() - $('#epHeader').outerHeight();
            $("#mobile-menu-items").css('max-height', nav_new_height);
        },

        /*------------------------------------------------------------------------------*/
        /*  Add To Cart - Open Toggle Sidebar When click On Add to cart Buttons 
        /*------------------------------------------------------------------------------*/
  
        addToCart: function () {

            var self = this,
                $toggleSidebarContainer = $('.toggleSidebarContainer'),
                $cartSidebarContainer = $('.cartSidebarContainer');
				  
            // Open the Toggle Sidebar When Click On Add To Cart Buttons
            self.$document.on('click', ".add_to_cart_button:not(.product_type_variable) , .single_add_to_cart_button", function (e) {
                //Ignore affilate products
				
				var $this = $(this);
				if(! $this.is('.ajax_add_to_cart')){
				    $this.parents('form').submit();
                    return;  
			    }
				
                if ( $this.is('.affilate-product') || $this.parent('.fixed-add-to-cart').is('.affilate-product'))
                    return 0;
	

                e.preventDefault();

                if ($(this).parent().parent('.fixed-add-to-cart-container').find('.go-to-add-to-cart').length || $(this).hasClass('disabled')) { // for fixed add to cart in variable and grouped product detail - prevent Add to cart open
                    return 0;
                }

                if (self.$body.hasClass('modal-open')) { // is quick view window first wait 300ms then close it and then open sidebar cart.

                    setTimeout(function () {
                        self.close_quick_view();
                    }, 300);

                    if (!self.$body.hasClass('vertical_menu_enabled')) {

                        setTimeout(function () {

                            self.$epHeader.addClass('sidebarToggleOpen');
                            $toggleSidebarContainer.addClass('sidebarToggleOpen');
                            $cartSidebarContainer.addClass('sidebarToggleOpen');
                            $('.cartSidebarbtn').addClass('active');
                            $('.scrollToTop').addClass('toggleOpen');

                        }, 600);
                    }

                } else {

                    if ($(this).parent().parent('.fixed-add-to-cart-container').length) { // is quick view window first wait 300ms then close it and then open sidebar cart.

                        $('.fixed-add-to-cart-container .fixed-add-to-cart').addClass('toggleOpen');
                        $('.scrollToTop').addClass('toggleOpen');

                    }
  
                    if (!self.$body.hasClass('vertical_menu_enabled')) {
                        self.$epHeader.addClass('sidebarToggleOpen');
                        $toggleSidebarContainer.addClass('sidebarToggleOpen');
                        $cartSidebarContainer.addClass('sidebarToggleOpen');
                        $('.cartSidebarbtn').addClass('active');
                    }

                }
			
            });
        },

        /*------------------------------------------------------------------------------*/
        /*  product variation adding to cart
        /*------------------------------------------------------------------------------*/

        addToCartEvents: function () {
            var self = this;

            // Updating cart and show loading
            $(document.body).on('adding_to_cart', function () {
                $('.cartSidebarContainer .cartSidebarWrap').addClass('updatingCart');
            });

            $(document.body).on('added_to_cart', function () {
                $('.cartSidebarContainer .cartSidebarWrap').removeClass('updatingCart');
                self.reInitDjax();
                self.card_widget_update();
            });
            self.$document.on('added_to_cart wc_cart_updated', function () {
                setTimeout(function () {
                    self.epico_scrollbar('.toggleSidebar .cart_list.product_list_widget');
                }, 500);

                $(".mini_cart_item a.remove, .mini_cart_item a.undo").addClass('no_djax');
            });
        },

        addToCart_variation_group: function () {
            var self = this;

            // Ajax variation Product - add to cart
            self.$document.on('click', 'table.variations button.single_add_to_cart_button, .single_add_to_cart_button.product_type_variable , .single_add_to_cart_button.product_type_grouped', function (e) {
				
				if(!$(this).is('.ajax_add_to_cart')){
					$(this).parents('form').submit();
		             return;  
				}
				
                var b = this;
                e.preventDefault();
                // AJAX add to cart request
                var $thisbutton = $(this);

                if ($thisbutton.hasClass('disabled'))
                    return;

                if ($thisbutton.hasClass('product_type_variable') || $thisbutton.hasClass('product_type_grouped') || $thisbutton.parent('table.variations')) {

                    $thisbutton.addClass('loading');

                    var $productForm = $thisbutton.closest('form');

                    if (!$productForm.length) {
                        return;
                    }

                    var data = {
                        product_id: $productForm.find("input[name*='add-to-cart']").val(),
                        product_variation_group_data: $productForm.serialize() // get variation and Group value 
                    };

                    // Trigger event
                    $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

                    // Ajax action
                    $.ajax({
                        type: "POST",
                        url: "?add-to-cart=" + data.product_id + "&ep-ajax-add-to-cart=1",
                        data: data.product_variation_group_data,
                        dataType: "html",
                        cache: false,
                        headers: { 'cache-control': 'no-cache' },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            console.log('Varation Product  Add to cart Faild!!');
                        },
                        success: function (response) {

                            if (!response) {
                                return;
                            }

                            if (response.error && response.product_url) {
                                window.location = response.product_url;
                                return;
                            }

                            // Redirect to cart option
                            if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {

                                window.location = wc_add_to_cart_params.cart_url;
                                return;

                            } else {
                       
                                    //var $response = $('<div>' + response + '</div>'); // Wrap the returned HTML string so we can get the replacement elements
                                    $(".widget_shopping_cart_content").html(response)
                                    var $response = $(".widget_shopping_cart_content");

                                    // Get replacement elements/values
                                    var fragments = {
                                        ".cartContentsCount": $response.find(' .cartContentsCount'), // Count Of Items in Cart
                                        ".cart-bottom-box": $response.find('.cart-bottom-box'), // Count Of Items in Cart
                                        ".product_list_widget": $response.find('.product_list_widget'), // Cart items Detail
                                        ".wc-notice-content": $response.find('.woocommerce-error, .woocommerce-message') // Cart items Detail
                                    };

                                    // Replace elements
                                    $.each(fragments, function (selector, $element) {
                                        if ($element.length) {
                                            $(selector).each(function () {
                                                if ($(this).parents('.widget_shopping_cart_content').length) { // Check Only Replace ( Update ) Dom in the Cart Sidebar 
                                                    $(this).replaceWith($element);
                                                }
                                            });
                                        }
                                    });

                                    // Update Cart Count in the Menus 
                                    $(".widget_woocommerce-dropdown-cart .cart-contents .cartContentsCount").replaceWith('<div class="cartContentsCount">' + $response.find('.cartContentsCount').text() + '</div>');

                                // wait All Replace Elemets Complete then trigger added_to_cart ( cause Fading Effect seen )   
                                setTimeout(function () {
                                    $(document.body).trigger("added_to_cart");
                                    $thisbutton.removeClass('loading');
                                    $thisbutton.removeClass('added');
                                }, 100);
                            }
                        }
                    });

                    return false;
                }

                return true;
            });
        },
        /*------------------------------------------------------------------------------*/
        /*  Notices : accoutnt pag and form validation of checkout field are ignored
        /*------------------------------------------------------------------------------*/
        wc_notices: function () {
            var self = this;
            if(self.$body.hasClass('no_wc_notices'))
                return;

            /* Detect notices on adding simple product to cart */
            self.$body.on('added_to_cart', function (event, fragments) {
                if (fragments === undefined || (fragments["div.widget_shopping_cart_content"] === undefined && $(fragments[".wc-notice-content"]).length <= 0))
                    return;
				var replacedBefore= false;
				
				var $returned_message = $(fragments["div.widget_shopping_cart_content"]).find('.woocommerce-error, .woocommerce-message');
				
				if($(fragments[".wc-notice-content"]).length > 0)
					replacedBefore = true;
				
				//check existance of new notice
				if ($returned_message.length > 0 || replacedBefore == true) {
					self.update_notices($returned_message, replacedBefore);
                }
                
            });

        },

        update_notices: function ($wc_notices_in_cart,replacedBefore) {
            var self = this,
			$wc_notices_container = $('#ep_wc_notices');

            setTimeout(function () {
				
				if(replacedBefore == true)
				{
					$wc_notices_container.wrapInner('<div class="wc-notice-content"></div>');
				} else
				{
					$wc_notices_container.html($wc_notices_in_cart);
				}
                
				$wc_notices_container.addClass('show');
				self.reInitDjax();
                self.wcNoticeTimer = setTimeout(function () {
					$wc_notices_container.removeClass('show');
                }, 6000);

            }, 470)
        },


        /*------------------------------------------------------------------------------*/
        /*  Toggle Sidebar container  + cart container
        /*------------------------------------------------------------------------------*/

        togglesidebar: function () {

            var self = this;

            var $toggleSidebarContainer = $('.toggleSidebarContainer'),
                $toggleSidebarWidgetbar = $('.toggleSidebarWidgetbar'),
                $cartSidebarContainer = $('.cartSidebarContainer'),
                $fixedAddToCartContainer = $('.fixed-add-to-cart-container .fixed-add-to-cart'),
                $scrollToTop = $('.scrollToTop'),
                //Buttons
                $sideBarToggleBtn = $('.sidebartogglebtn'),
                $cartToggleButton = $('.cartSidebarbtn');

            var close_sidebar = function () {
                if ($toggleSidebarContainer.hasClass('sidebarToggleOpen')) {
                    self.$epHeader.removeClass('sidebarToggleOpen');
                    $toggleSidebarContainer.removeClass('sidebarToggleOpen');
                    $toggleSidebarWidgetbar.removeClass('sidebarToggleOpen');
                    $cartSidebarContainer.removeClass('sidebarToggleOpen');

                    $fixedAddToCartContainer.removeClass('toggleOpen');
                    $scrollToTop.removeClass('toggleOpen');

                    //Allow snap-to-scroll + scrolling
                    self.$body.removeClass('disable-snap-to-scroll');
                }
            }

            var open_sidebar = function (type) {
                self.$epHeader.addClass('sidebarToggleOpen');
                $toggleSidebarContainer.addClass('sidebarToggleOpen');
                $fixedAddToCartContainer.addClass('toggleOpen');
                $scrollToTop.addClass('toggleOpen');

                //Disallow snap-to-scroll + disable scroll
                self.$body.addClass('disable-snap-to-scroll');

                if (type == 'cart') {
                    $cartSidebarContainer.addClass('sidebarToggleOpen');
                }
                else {
                    $toggleSidebarWidgetbar.addClass('sidebarToggleOpen');
                }
            }

            self.$document.on('click', '#sidebar-open-overlay, .cartSidebarContainer #cart-close-btn,.toggleSidebarWidgetbar #toggle-sidebar-close-btn', function () {
                close_sidebar();
            });

            $cartToggleButton.on('click', function () {
                if ((self.$body.hasClass('vertical_menu_enabled') && self.windowWidth > 1140) || self.$body.hasClass('woocommerce-cart'))
                    return;
                open_sidebar('cart');
            });

            $sideBarToggleBtn.on('click', function () {
                open_sidebar('sidebar');
            });

            //close sidebar when click on links inside cart/sidebar
            self.$window.on('djaxLoading', function () {
                close_sidebar();
            })

            // toggle sidebar button animation
            $(".sidebartogglebtn").bind("webkitAnimationEnd mozAnimationEnd animationEnd", function () {
                $(this).removeClass("sidebartogglebtnanimate");
            })

            $(".sidebartogglebtn").mouseenter(function () {
                $(this).addClass("sidebartogglebtnanimate");
            })

        },

        togglesidebar_scrollbar: function () {
            var self = this;
            self.epico_scrollbar('.toggleSidebarWidgetbar');
            self.epico_scrollbar('.toggleSidebar .cart_list.product_list_widget');

            //run again scrollbar after updating contetn of cart sidebar
            self.$body.on( 'wc_fragments_refreshed', function(){
                self.epico_scrollbar('.toggleSidebar .cart_list.product_list_widget');
            } );

        },

        /*------------------------------------------------------------------------------*/
        /*  Scrollbar
        /*------------------------------------------------------------------------------*/
        epico_scrollbar: function (element) {
            var $element = $(element);

            if (!$element)
                return;

            $element.each(function () {
                var $this = $(this),
                    $scrollContainer = $this.find('.swiper-container');

                if ($scrollContainer.length > 0) {
                    if ($scrollContainer[0].swiper != undefined) {
                        $scrollContainer[0].swiper.updateSlidesSize();
                        return;
                    }
                }

                $this.wrapInner('<div class="swiper-container sw-scrollbar"><div class="swiper-wrapper"><div class="swiper-slide"></div></div><div class="swiper-scrollbar"></div></div>');
                $scrollContainer = $this.find('.swiper-container');

                var swiper = new Swiper($scrollContainer, {
                    scrollbar: '.swiper-scrollbar',
                    direction: 'vertical',
                    slidesPerView: 'auto',
                    mousewheelControl: true,
                    freeMode: true,
                    touchReleaseOnEdges: true,
                    mousewheelReleaseOnEdges: true,
                    scrollbarDraggable: true,
                    mousewheelSensitivity: .6,
                });
            })

        },

        /*------------------------------------------------------------------------------*/
        /*  Toggle Sidebar - Menu widget 
        /*------------------------------------------------------------------------------*/
        toggle_sidebar_menu: function (element) {
			//clickable submenu
            $('.toggleSidebarWidgetbar li.menu-item-has-children > a').on('click', function (e) {

                e.preventDefault();

                var $this = $(this);
                if ($this.siblings("ul.sub-menu").hasClass('activeSubmenu')) {


                    $this.siblings(".sub-menu.activeSubmenu").css({
                        height: '0',
                        color: '#fff',
                    });

                    $this.parent().parent("ul.sub-menu").height('auto');

                    $this.siblings("ul.sub-menu").removeClass('activeSubmenu');
                    $this.removeClass('activeSubmenu');


                } else {

                    if ($this.parents('.activeSubmenu').length) {

                        // level 3

                        // calculate submenu height
                        var $level2Menu = $this.parents('.activeSubmenu').find('> li');
                        $level2Menu = $level2Menu.length;
                        var $level2height = $level2Menu * 53;

                        var $level3Menu = $this.siblings('ul.sub-menu').find('> li');
                        $level3Menu = $level3Menu.length;
                        var $level3height = $level3Menu * 53;

                        // close Other Open SubMenu
                        var $ULparent = $this;
                        $ULparent.parent().parent().find('a.activeSubmenu').removeClass('activeSubmenu');
                        $ULparent.parent().parent().find('ul.activeSubmenu').height(0).removeClass('activeSubmenu');

                        // level 2 Height 
                        $this.parents('.activeSubmenu').css({
                            height: $level2height + $level3height
                        });

                        // level 3 height 
                        $this.siblings("ul.sub-menu").css({
                            height: $level3height
                        });

                    } else {

                        // level 2
                        // calculate submenu height
                        var $level2Menu = $this.siblings('ul.sub-menu').find('> li');
                        $level2Menu = $level2Menu.length;
                        var $level2height = $level2Menu * 53;
                        $this.siblings("ul.sub-menu").css({
                            height: $level2height
                        });

                    }

                    $this.siblings("ul.sub-menu").addClass('activeSubmenu');
                    $this.addClass('activeSubmenu');
                }
                setTimeout(function () {
                    $('.toggleSidebarWidgetbar >.swiper-container')[0].swiper.updateSlidesSize();
                }, 500);


            });
        },

        /*------------------------------------------------------------------------------*/
        /*  vertical sidebar
        /*------------------------------------------------------------------------------*/

        coveringLevelVerticalMenu: function () {

            var $vertical_menu_item = $(".vertical_menu_enabled .vertical_menu_navigation li.menu-item-has-children > a:not('.mp-back')");
            $vertical_menu_item.addClass('no_djax');

            $vertical_menu_item.on('click', function (e) {

                var $this = $(this);
                e.preventDefault();

                // Hide Li element
                $this.parent().siblings().addClass('hide-for-submenu');

                $this.addClass('hide-for-submenu');

                $this.parent().addClass('removeActiveHover');

                var $subMenu = $this.siblings('.mp-level');

                if ($subMenu.length) {
                    $this.siblings('.mp-level').addClass('mp-level-open');
                }

            });

            $(".vertical_menu_enabled .vertical_menu_navigation li a.mp-back").on('click', function (e) {

                e.preventDefault();

                var $this = $(this);

                $this.parent().removeClass('mp-level-open');

                //show parent li element
                $this.parent().parent().find('a').removeClass('hide-for-submenu');

                $this.parent().parent().removeClass('removeActiveHover');

                $this.parent().parent().siblings().removeClass('hide-for-submenu')

            });

        },

        /*----------------------------------------------------------------------------------*/
        /* Interactive background
        /*-----------------------------------------------------------------------------------*/
        interactiveBackgroundImg: function () {
            var self = this;
            if ($('.interactive-background').length <= 0)
                return;

            self.epico_interactive_background($('.interactive-background .section-container'), { sensitivity: 100, duration: 10000, zoom: false, initialZoom : true });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  parallax  
        /*-----------------------------------------------------------------------------------*/

        parallaxImg: function () {

            var self = this;

            if (self.$body.hasClass('snap-to-scroll') || $('.parallax').length <= 0 || self.msie > 0 || !!self.msie11)
                return;


            if (self.windowWidth > 1140 && !self.isTouchDevice) {
                var $parallaxContainers = $('.parallax'),
                    scrollPosition = 0;

                var parallax_handler = function () {
                    $parallaxContainers.each(function () {
                        var $el = $(this),
                            speed = parseFloat($el.attr('data-speed')),
                            elementHeight = $el.data('height'),
                            elementTop = $el.data('offsetTop'),
                            elementBottom = elementTop + elementHeight,
                            factorMult = 0;

                        //When element is out of viewport
                        if (elementTop > (scrollPosition + self.windowHeight) || elementBottom < scrollPosition)
                            return;

                        var parallax = (scrollPosition - elementTop) / self.windowHeight;
                        factorMult = Math.round((parallax) * speed * 100) / 100;
                        $el.find(".parallax-img").css({
                            'transform': 'translate3d(0,' + factorMult + '%,0)'
                        });
                    });
                }

                var parallax_init = function () {
                    self.windowHeight = self.$window.height();
                    $parallaxContainers.each(function () {
                        var $el = $(this);
                        $el.data('offsetTop', $el.offset().top);
                        $el.data('height', $el.outerHeight(true));
                    });
                }

                var do_scroll = function () {
                    $parallaxContainers.each(function () {
                        var $el = $(this);
                        $el.data('offsetTop', $el.offset().top);
                    });
                    scrollPosition = self.$window.scrollTop();
                    window.requestAnimationFrame(parallax_handler);
                }

                parallax_init();
                parallax_handler();


                self.$window.on('scroll', do_scroll).on('resize', parallax_init);
                self.$window.one('djaxClick', function () {
                    self.$window.unbind('scroll', do_scroll).unbind('resize', parallax_init);
                });

            }
        },

        //Parallax by mouse position
        epico_interactive_background: function ($elements, options) {
            var self = this;

            if (self.windowWidth <= 1140 || self.isTouchDevice)
                return;

            var transform = function ($target, x, y, scaleRatio) {
                    $target.css('transform', 'matrix(1, 0, 0,1,' + x + ',' + y + ') scale(' + scaleRatio +',' + scaleRatio +')');
            }

            var transformLeave = function ($target, x, y, scaleRatio) {
                $target.css('transform', 'matrix(1, 0, 0,1,' + x + ',' + y + ') scale(' + scaleRatio + ',' + scaleRatio + ')');
            }

            return $elements.each(function () {

                var settings = $.extend({
                    target: '> .interactive-background-image img',
                    sensitivity: 20,
                    duration: 1400,//ms
                    zoom: true,
                    initialZoom : false,
                }, options);

                var el = $(this);
                //Prevent from multiple running
                if (el.find(settings.target).length === 0 || el.hasClass('interactive-background-active')) {
                    return true;
                }

                var target = el.find(settings.target),
                    h, w, width, cx, x, y, scaleRatio = 1;

                //set different transition duration for element
                if (settings.duration != 1400) {
                    target.css('transition-duration', settings.duration + 'ms');
                }

                el.addClass('interactive-background-active');
                el.on('mouseenter interactive_bg_init', function (e) {
                    if (w !== el.width()) {
                        w = el.width();
                        h = el.height();
                        cx = settings.sensitivity / w;

                        if(settings.zoom || settings.initialZoom)
                        {
                            scaleRatio = (w + settings.sensitivity) / w;
                        }

                        //set initial scale
                        if (settings.initialZoom) {
                            target.css('transform', 'scale(' + scaleRatio + ',' + scaleRatio + ')');
                        }
                    }
                }).on('mousemove', function (e) {
                    x = (-cx * (e.pageX - (target.offset().left + w / 2)));
                    y = (-cx * (e.pageY - (target.offset().top + h / 2)));
                    transform(target, x, y, scaleRatio);
                }).on('mouseleave', function (e) {
                    if(settings.initialZoom)
                    {
                        transformLeave(target, 0, 0,scaleRatio);
                    }
                    else
                    {
                        transformLeave(target, 0, 0,1);
                    }
                    
                });

                if (settings.initialZoom) {
                    el.trigger('interactive_bg_init');
                }
            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  Blog Post Slider 
        /*-----------------------------------------------------------------------------------*/

        blogPostSlider: function () {

            // blog post slider - swoper slider
            $('.bpSwiper').not('.disabled_swiper').each(function () {

                var $bd_s_nextbtn = $(this).find('.arrows-button-next'); // Next btns
                var $bd_s_prevbtn = $(this).find('.arrows-button-prev');// Previous Btns
                $bd_s_nextbtn.add($bd_s_prevbtn).css({ 'opacity': '1' });

                var swiper = new Swiper($(this), {

                    loop: true,
                    speed: 650,
                    nextButton: $bd_s_nextbtn,
                    prevButton: $bd_s_prevbtn,
                    onSlideChangeStart: function (swiper) {

                        //Unset height
                        $('.bpSwiper .swiper-wrapper').css({ height: '' });
                        //Calc Height
                        var $bpSwiperWidth = $('.bpSwiper').width(), // Container Width
                        $imgeWidth = $(swiper.slides[swiper.activeIndex]).find('img').attr('width'), // initial Images Width
                        $imgeHidth = $(swiper.slides[swiper.activeIndex]).find('img').attr('height'), // initial image width
                        $imgeNewHeight = ($bpSwiperWidth * $imgeHidth) / $imgeWidth; // Calc image height in container

                        $('.bpSwiper .swiper-wrapper').css({ height: $imgeNewHeight });
                        $('.bpSwiper').css({ height: $imgeNewHeight });

                    },


                });

            });

        },

        /*-----------------------------------------------------------------------------------*/
        /*  portfolio like
        /*-----------------------------------------------------------------------------------*/
        portfolioLike: function () {
            var self = this;

            self.$body.on('click', '.jm-post-like', function (e) {
                e.preventDefault();
                var $like = $(this),
                    post_id = $like.data("post_id");
                $.ajax({
                    type: "post",
                    url: epico_theme_vars.ajax_url,
                    data: "action=jm-post-like&nonce=" + epico_theme_vars.nonce + "&jm_post_like=&post_id=" + post_id,
                    success: function (count) {
                        if (count.indexOf("already") !== -1) {
                            var lecount = count.replace("already", "");
                            if (lecount === "0") {
                                lecount = "Like"
                            }
                            $like.prop('title', 'Like');
                            $like.removeClass("liked");
                            $like.find(".count").addClass("no_like").html(lecount);
                        } else {
                            $like.prop('title', 'Unlike');
                            $like.addClass("liked");
                            $like.find(".count").removeClass("no_like").html(count);
                        }
                    },
                })
            })
        },
        /*-----------------------------------------------------------------------------------*/
        /*  portfolio feature Images Slider 
        /*-----------------------------------------------------------------------------------*/

        portfolioSlider: function () {

            if($('.portfolioswiper').length <= 0)
                return;

            var self = this;

            //wait for calculate correct width of portfolio item
            setTimeout(function () {
                //portfolio feature images slider
                $('.portfolioswiper').each(function () {
                    var $slider = $(this);

                    //Don't run slider on sliders with less than two slides!
                    if ($slider.find('.pSlide').length < 2) {
                        return;
                    }

                    //generate random value for slide show Speed
                    var $autoplayDuration = 3000 + Math.floor(Math.random() * 4000);

                    var portfolioSwiper = new Swiper($slider, {
                        autoplay: $autoplayDuration,
                        autoplayDisableOnInteraction: false,
                        speed: 1000,
                        initialSlide: 0,
                        loop: true,
                        effect: 'fade',
                        fade: {
                            crossFade: false
                        }
                    });

                    //load image of duplicate slides
                    setTimeout(function () {
                        //Remove is-loading class and prepare it for images lazy loading
                        $slider.find('.swiper-slide-duplicate .lazy-load-on-load').removeClass('is-loading');
                        self.lazyLoadOnLoad($slider.find('.swiper-slide-duplicate'));
                    }, 500);

                });
            }, 1000)
        },

        /*-----------------------------------------------------------------------------------*/
        /*  portfolio Detail
        /*-----------------------------------------------------------------------------------*/

        portfolio_detail_header_parallax: function () {

            var self = this;

            if ($('#portfolio-detail-parallax-container').length <= 0 || self.msie > 0 || !!self.msie11)
                return;

            var $window_y = self.$window.scrollTop();

            if (self.windowWidth > 1140 && !self.isTouchDevice) {

                self.windowHeight = self.$window.height();
                var $slider = $('#portfolio-detail-parallax-container'),
                    scrollPosition = 0,
                    tick = false;

                var home_parallax_handler = function () {
                    tick = true;

                    //When element is out of viewport
                    if (self.windowHeight < scrollPosition) {
                        tick = false;
                        return;
                    }

                    $slider.css({
                        'transform': 'translate3d(0,' + scrollPosition * 0.6 + 'px,0)'
                    });

                    tick = false;
                }
                home_parallax_handler();

                var requestTick = function () {
                    if (tick == false) {
                        scrollPosition = self.$window.scrollTop();
                        window.requestAnimationFrame(home_parallax_handler);
                    }
                }

                self.$window.on('scroll', requestTick);

                self.$window.one('djaxClick', function () {
                    self.$window.unbind('scroll', requestTick);
                });
            }

        },

        portfolio_detail_title: function () {

            if ($('.pDHeader-title').length < 1)
                return

            var $title = $('.pDHeader-title');
            $title.addClass('bg-animated active');

        },

        portfolio_next_prev: function () {
            var self = this;

            if(self.$body.hasClass('ajax_page_transition'))
            {
                self.$window.bind('djaxClick', function(e, data) {
                    var link = $(data);
                    //fetch data for ajax request of navigations
                    if (link.hasClass('portfolioLink')) {
                        self.$skills = link.parents('.isotope').data('skills');
                        self.$portfolioBackLink = $(location).attr('href');
                        self.$portfolioID = link.data('pid');

                    }
                    else if (link.hasClass('portfolioDetailNavLink')) {
                        self.$skills = link.data('skills');
                        self.$portfolioBackLink = $("#PDbackToPortfolio").attr('href');
                        self.$portfolioID = link.data('pid');
                    }

                });
            }
            else
            {

                $('.portfolio.isotope-item a, a.portfolioDetailNavLink').on('click', function(e) {
                    
                    e.preventDefault();
                    var $link = $(this);

                    //fetch data for ajax request of navigations
                    if($link.hasClass('portfolioLink'))
                    {
                        self.$skills = $link.parents('.isotope').data('skills');
                        self.$portfolioBackLink = $(location).attr('href');
                        self.$portfolioID = $link.data('pid');
                        
                    }
                    else if($link.hasClass('portfolioDetailNavLink'))
                    {
                        self.$skills  = $link.data('skills');
                        self.$portfolioBackLink = $("#PDbackToPortfolio").attr('href');
                        self.$portfolioID = $link.data('pid');
                    }

                    //Save portfolio required information in sessionStorage for next/prev and back button in portfolio detail
                    if (typeof(window.sessionStorage) !== "undefined") {
                        window.sessionStorage.setItem( 'skills', self.$skills );
                        window.sessionStorage.setItem( 'portfolioBackLink', self.$portfolioBackLink );
                        window.sessionStorage.setItem( 'pid', self.$portfolioID );
                    }
                    window.location = $link.attr('href');
                    
                });

            }

        },

        remove_footer_creative_portfolio_detail: function () {

            //Hide footer in portfolio detail
            if ($('.portfolio_detail_boxed').length || $('.portfolio_detail_full_width').length || $('.portfolio_detail_full_width').length || $('.portfolio_detail_ default').length) {

                $('footer').css('display', 'block');
                $('#googleMap').css('display', 'none');
                $('.footer-widgetized').css('display', 'none');

            } else if ($('.portfolio_detail_creative').length) {

                $('footer').css('display', 'none');
                $('#googleMap').css('display', 'none');
                $('.footer-widgetized').css('display', 'none');

            } else {

                $('footer').css('display', '');
                $('#googleMap').css('display', 'block');
                $('.footer-widgetized').css('display', 'block');

            }

        },

        remove_left_right_menu_creative_portfolio_detail: function () {
            var self = this;

            if ($('.portfolio_detail_creative').length) {
                $('.vertical_menu_area').add($('#topbar')).addClass('hide_menu');
                self.$body.addClass('removePadding disableScroll');
            }
            else {
                $('.vertical_menu_area').add($('#topbar')).removeClass('hide_menu');
                self.$body.removeClass('removePadding disableScroll');
            }
        },

        set_margin_creative_portfolio_detail: function () {

            var self = this;

            if ($('.portfolio_detail_creative').length && self.windowWidth > 768) {

                var $portfolio_detail_creative_height = $('.pd_creative_fixed_content').height();

                var $portfolio_detail_creative_space = (self.windowHeight - $portfolio_detail_creative_height) / 2;

                if ($portfolio_detail_creative_space < 0) {

                    $portfolio_detail_creative_space = 0;

                }

                $('.portfolio_detail_creative').css({
                    'margin-top': $portfolio_detail_creative_space,
                });

            }
        },

        /* Portfolio Details swiper Slider */
        pDSwiper: function () {
            var self = this;

            if ($('#pDSwiper').length < 1)
                return;


            if ($('.portfolio_detail_creative').length) {

                if (('#pDSwiper').length) {
                    if (self.windowWidth <= 979) {
                        var $nextbtn = $('#pDSwiper').find('.pd-arrows-button-next'),
                        $prevbtn = $('#pDSwiper').find('.pd-arrows-button-prev');

                        var swiper = new Swiper('#pDSwiper', {
                            slidesPerView: '1',
                            autoplay: 5000,
                            loopedSlides: 1,
                            paginationClickable: true,
                            spaceBetween: 0,
                            loop: true,
                            nextButton: $nextbtn,
                            prevButton: $prevbtn,
                        });
                    }
                    else {
                        var swiper = new Swiper('#pDSwiper', {
                            slidesPerView: 'auto',
                            paginationClickable: true,
                            spaceBetween: 15,
                        });
                    }

                }

            } else {

                if ($('#pDSwiper').find('.swiper-slide').length > 1) {

                    var $nextbtn = $('#pDSwiper').find('.arrows-button-next');
                    var $prevbtn = $('#pDSwiper').find('.arrows-button-prev');
                    //portfolio detail swiper slider
                    var swiper = new Swiper('#pDSwiper', {
                        autoplay: 5000,
                        autoplayDisableOnInteraction: false,
                        speed: 700,
                        longSwipesMs: 600,
                        keyboardControl: true,
                        loop: true,
                        slidesPerView: 1,
                        nextButton: $nextbtn,
                        prevButton: $prevbtn,
                        spaceBetween: 0
                    });
                }

            }
        },

        /*-----------------------------------------------------------------------------------*/
        /*  testimonial
        /*-----------------------------------------------------------------------------------*/

        testimonial: function () {

            var self = this;
            var $testimonials = $('.testimonials'); //Slide testimonial

            if (!$testimonials.length) return;

            $testimonials.each(function () {

                var $testimonialid = $(this).attr('data-id');//get the carousel id that save in Data-id
                var $nextbtn = $(this).find('.arrows-button-next-' + $testimonialid);
                var $prevbtn = $(this).find('.arrows-button-prev-' + $testimonialid);
                var swiper = new Swiper('.swiper-container-' + $testimonialid, {
                    autoplay: 5000,
                    autoplayDisableOnInteraction: false,
                    effect: 'fade',
                    fade: {
                        crossFade: true
                    },
                    speed: 1200,
                    loop: true,
                    slidesPerView: 1,
                    simulateTouch: false,
                    nextButton: $nextbtn,
                    prevButton: $prevbtn,
                    spaceBetween: 0
                });

            });

            self.epico_scrollbar('.testimonials.testimonials-style1 .quote blockquote');

        },

        /*-----------------------------------------------------------------------------------*/
        /*  Comment Respond
        /*-----------------------------------------------------------------------------------*/

        commentRespond: function () {

            var $respond = $('#respond'), $respondWrap = $('#respond-wrap'),
                $cancelCommentReply = $respond.find('#cancel-comment-reply-link'),
                $commentParent = $respond.find('input[name="comment_parent"]');

            $('.comment-reply-link').each(function () {
                var $this = $(this),
                    $parent = $this.parents().eq(2);

                $this.click(function () {
                    var commId = $this.parents('.comment').attr('data-id');

                    $commentParent.val(commId);
                    $respond.insertAfter($parent);
                    $cancelCommentReply.show();

                    return false;
                });
            });

            $cancelCommentReply.click(function (e) {
                e.preventDefault();

                $cancelCommentReply.hide();

                $respond.appendTo($respondWrap);
                $commentParent.val(0);
            });
        },

        /*-----------------------------------------------------------------------------------*/
        /*  Forms 
        /*-----------------------------------------------------------------------------------*/

        Forms: function () {
            var self = this;

            var $respond = $('#respond'), $respondWrap = $('#respond-wrap'), $cancelCommentReply = $respond.find('#cancel-comment-reply-link'),
                $commentParent = $respond.find('input[name="comment_parent"]');

            $('.comment-reply-link').each(function () {
                var $this = $(this),
                    $parent = $this.parent().parent();

                $this.click(function () {
                    var commId = $this.parents('.comment').find('.comment_id').html();

                    $commentParent.val(commId);
                    $respond.insertAfter($parent);
                    $cancelCommentReply.show();

                    return false;
                });
            });

            $cancelCommentReply.click(function (e) {
                $cancelCommentReply.hide();

                $respond.appendTo($respondWrap);
                $commentParent.val(0);

                e.preventDefault();
            });

            self.ContactForm('#respond');

        },

        /*-----------------------------------------------------------------------------------*/
        /*  Scrolling function 
        /*-----------------------------------------------------------------------------------*/

        scroll_to: function (location, introCheck, time) {

            var self = this;

            //exit if url is detail of portfolio inner
            if (location.toString().indexOf("#!portfolio-detail/") != -1)
                return;

            if (location !== "#") {

                var scrollto;

                // introCheck 3 is for go to top Page ( top Button )
                // introCheck 2 is for logo
                //introcheck 5 is for next button in showcase 
                if (introCheck == 1 || introCheck == 2 || introCheck == 4 || introCheck == 5) {

                    if (introCheck !== 4 && introCheck !== 5) { // this code run when link from external to internal
                        // get internal id ( hash ) From Query string 
                        var sPageURL = window.location.search.substring(1);
                        var sURLVariables = sPageURL.split('&');
                        for (var i = 0; i < sURLVariables.length; i++) {
                            var sParameterName = sURLVariables[i].split('=');
                            if (sParameterName[0] == 'sectionid') {
                                location = '#'.concat(sParameterName[1]);
                            }
                        }
                    }

                    var $location = $(location);

                    if ($location.length) {
                        var offsetTop = $location.offset().top,
                            done = $location.closest('.layout').offset().top,
                            menuHeight = $('#headerFirstState').outerHeight(),
                            topbar = 0;

                            if($('#headerSecondState').length > 0)
                            {
                                menuHeight = $('#headerSecondState').outerHeight();
                            }


                        offsetTop = offsetTop - done;

                        if (self.$body.hasClass('has-topbar')) { // Top bar height
                            topbar = 33;
                        }

                        if ($('.vertical_menu_area').length || introCheck == 5) { // If vertical menu Or next button On showcase 
                            scrollto = offsetTop - topbar;

                        } else {
                            scrollto = offsetTop - menuHeight + topbar;
                        }

                        if(self.windowWidth <= 1140)
                        {
                            scrollto = offsetTop - 70;//70px is responsive menu height
                        }

                    }
                }

                if (introCheck === 1 || introCheck === 4 || introCheck === 5) {
                    var scrolltime = parseInt(epico_theme_vars.scrolling_speed);
                    if (time) {
                        scrolltime = time;
                    }
                    //scroll to inside id 
                    self.$scrollpals.animate(
                        {
                            scrollTop: scrollto
                        }, {
                            duration: scrolltime,
                            easing: epico_theme_vars.scrolling_easing,
                            complete: function () {
                                self.scrolingToSection = false;
                                self.externalClicked = false;
                            },
                            queue: false
                        }
                    );

                } else if (introCheck === 2 || introCheck === 3) {
                    var scrolltime = 1500;
                    if (time) {
                        scrolltime = time;
                    }
                    //scroll to top of page
                    self.$scrollpals.animate(
                        {
                            scrollTop: 0
                        }, {
                            duration: scrolltime,
                            easing: epico_theme_vars.scrolling_easing,
                            queue: false
                        }
                    );
                }
            }
        },

        /*-----------------------------------------------------------------------------------*/
        /*  fitvid 
        /*-----------------------------------------------------------------------------------*/

        fitVideo: function () {
            $(".container").fitVids();
        },

        /*-----------------------------------------------------------------------------------*/
        /*  portfolio Isotope function
        /*-----------------------------------------------------------------------------------*/

        portfolioIsotope: function ($pIsotopeContainer) {

            var self = this;

            var $firstTimeLoad = true;

            if ($pIsotopeContainer === 0) { // id 0 for First Load

                // first load 
                $pIsotopeContainer = $('.portfoliowrap .isotope:not(.products)');

            } else if ($pIsotopeContainer === 1) { // id 1 for Resizing

                // resizing
                $firstTimeLoad = false;
                $pIsotopeContainer = $('.portfoliowrap .isotope:not(.products)');

            } else if ($pIsotopeContainer === 2) { // id 2 for tabs

                // resizing
                $firstTimeLoad = false;
                $pIsotopeContainer = $('.vc_active .portfoliowrap .isotope:not(.products)');


            } else {

                // when click load more button
                $firstTimeLoad = false;
                $pIsotopeContainer = $($pIsotopeContainer);

            }

                $pIsotopeContainer.each(function () {

                var $this = $(this);
               
                // Check Portfolio is FullWidth Or not
                if ($this.parents('.fullWidth').length) {
                    var $portfolio_fullwidth = true;

                    // If Fullwidth Padding right and left Must be 0
                    $this.parents('.fullWidth').find('.vc_col-sm-12').css({
                        'padding-right': '0px',
                        'padding-left': '0px',
                    });

                }

                var $uniqueId = parseInt($(this).closest('.portfolioSection').attr('data-id')),
                    $portfolioID = '#portfolio_'.concat($uniqueId),
                    isotopeItem = $('.isotope-item');

                //Portfolio Style
                var $portfoliostyle = $this.parents('.portfolioSection').attr('data-portfolio-type'),
                    $portfolioLayout = $this.parents('.portfolioSection').attr('data-layout-style');

                // Remove margins
                isotopeItem.css({
                    'margin-left': 0,
                    'margin-right': 0
                });

                // Sets column number depending on window width  
                var setColNum = function () {
                    var columnNum = 1;

                    if ($portfolio_fullwidth == true) {

                        if (self.windowWidth > 1360) {
                            columnNum = 6; // portfolio Fullwidth - 6 col
                        } else if (self.windowWidth > 999) {
                            columnNum = 4;
                        } else {
                            columnNum = 2;
                        }

                    } else {

                        if (self.windowWidth > 1200) {
                            columnNum = 4;// portfolio in Container - 4 col
                        } else if (self.windowWidth > 999) {
                            columnNum = 4;
                        } else {
                            columnNum = 2;
                        }
                    }

                    return columnNum;
                }

                var getColWidthValue;

                // Gets column number and divides to get column width 
                var getColWidth = function () {

                    if ($portfolio_fullwidth == true) { //fullwidth

                        var $device_width = self.$window.width();

                        if (self.$body.hasClass('vertical_menu_enabled') && self.windowWidth > 1140) {
                            $device_width = $device_width - 255;
                        }

                    } else {
                        if ($this.parents('.vc_tta-panel-body').length > 0)// in tab
                        {
                            var $device_width = $this.parents('.vc_tta-panel-body').width()
                        }
                        else// in Container
                        {
                            var $device_width = $this.parents('.container').width();
                        }
                    }

                    // body width based on horizonal scroolbar Enable or Disable .
                    if (self.$body.height() > self.windowHeight) {
                        var w = $device_width;
                    } else {
                        if (self.windowWidth > 1140) {

                            var w = $device_width;

                        } else {
                            var w = $device_width;
                        }
                    }

                    if ($portfoliostyle == 'portfolio_space' || $portfoliostyle == 'portfolio_text') {

                        if ($($this.parents('.fullWidth')).length) {
                            w = w - 13;
                        }

                    }

                    var columnNum = setColNum(),
                        colWidth = Math.floor(w / columnNum);

                    return colWidth;
                }

                getColWidthValue = getColWidth();

                // Run isotope plugin
                var callIsotope = function () {
                    var colWidth = getColWidthValue;

                    // text portfolio 
                    var $textPortfolioMetaHeight = 0,
                        $portfoliostyle = $this.parents('.portfolioSection').attr('data-portfolio-type'),
                        $portfolioLayout = $this.parents('.portfolioSection').attr('data-layout-style');

                    if ($portfoliostyle == 'portfolio_text') {
                        //Item meta Height ( For text Portfolio )
                        $textPortfolioMetaHeight = 77;

                        $textPortfolioMetaHeight = $textPortfolioMetaHeight + colWidth / 2; /*  ( colWidth / 2  ) set for perfect mesonry to remove white space and increase Accuracy  */
                    }

                    if ($this.parents('.vc_tta-panel').length) {
                        if (!($this.parents('.vc_tta-panel.vc_active').length)) {
                            return;
                        } 
                    }

                    if (self.windowWidth >= 768 && $portfolioLayout != 'masonry') {

                        $this.isotope({
                            itemSelector: '.isotope-item',
                            layoutMode: 'perfectMasonry',
                            perfectMasonry: {
                                layout: 'vertical',
                                columnWidth: colWidth,
                                gutterWidth: 20,
                                //liquid: true,
                                rowHeight: (colWidth / 2) + $textPortfolioMetaHeight, /* row height set for perfect mesonry to remove white space and ( / 2 ) is for increase Accuracy  */
                            },
                        });
                    }
                    else {
                        $this.isotope({
                            itemSelector: '.isotope-item',
                            layoutMode: 'masonry',
                        });
                    }


                    // call isotope animation ( defualt and custom mode )
                    self.isotopeAnimation($this);

                }

                // Sets dynamic size of isotope brick 
                var setBrickSize = function ($isotopeItem) {
                    var colWidth = getColWidthValue;
                    var gutterpadding = 0;

                    if ($portfoliostyle == 'portfolio_space' || $portfoliostyle == 'portfolio_text') {

                        // Padding For Space Portfolio
                        gutterpadding = 15;

                    }
                    var $textPortfolioMetaHeight = 0;
                    if ($portfoliostyle == 'portfolio_text')
                        $textPortfolioMetaHeight = 77;

                    var columnNum = setColNum();
                    // Set width of each brick
                    $this.find('.isotope-item').each(function () {
                        var $brick = $(this),
                            $brickphoto = $brick.find('.postphoto');

                        if ($portfolioLayout == 'masonry') {
                            $brickphoto.css({
                                'width': (colWidth - gutterpadding) + 'px'
                            });
                        }
                        else {
                            if ($brick.hasClass('big')) {
                                if (self.windowWidth > 979) {
                                    $brickphoto.css({
                                        'width': ((colWidth * 2) - gutterpadding) + 'px',
                                        'height': ((colWidth * 2) - gutterpadding + $textPortfolioMetaHeight) + 'px'
                                    });
                                }
                                else if (self.windowWidth >= 768) {
                                    $brickphoto.css({
                                        'width': (colWidth - gutterpadding) + 'px',
                                        'height': (colWidth - gutterpadding) + 'px'
                                    });

                                }
                                else {
                                    $brickphoto.css({
                                        'width': ((colWidth * 2) - gutterpadding) + 'px',
                                        'height': ((colWidth * 2) - gutterpadding) + 'px'
                                    });
                                }

                            }
                            else if ($brick.hasClass('wide')) {
                                //recalculate height of wide items(height should be 1/4 in desktop(big and small screen desktops) or 1/2 in responsive)
                                //The fallowing code convert 1/6 to 1/4 or 1/2
                                var factor = 4;

                                if (self.windowWidth <= 999) {
                                    factor = 2;
                                }

                                $brickphoto.css({
                                    'width': ((colWidth * columnNum) - gutterpadding) + 'px',
                                    'height': (((colWidth * columnNum) / factor) - gutterpadding) + 'px'
                                });

                            } else if ($brick.hasClass('slim')) {

                                if (self.windowWidth > 979) {

                                    $brickphoto.css({
                                        'width': (colWidth - gutterpadding) + 'px',
                                        'height': (((colWidth) * 2) - gutterpadding + $textPortfolioMetaHeight) + 'px'
                                    });

                                }
                                else if (self.windowWidth >= 768) {
                                    $brickphoto.css({
                                        'width': (colWidth - gutterpadding) + 'px',
                                        'height': (((colWidth) * 2) - gutterpadding + $textPortfolioMetaHeight) + 'px'
                                    });
                                }
                                else {

                                    $brickphoto.css({
                                        'width': ((colWidth * 2) - gutterpadding) + 'px',
                                        'height': (((colWidth) * 3) - gutterpadding) + 'px'
                                    });

                                }

                            } else if ($brick.hasClass('hslim')) {

                                $brickphoto.css({
                                    'width': ((colWidth * 2) - gutterpadding) + 'px',
                                    'height': ((colWidth) - gutterpadding) + 'px'
                                });

                            } else {

                                if (self.windowWidth >= 768) {

                                    $brickphoto.css({
                                        'width': (colWidth - gutterpadding) + 'px',
                                        'height': ((colWidth) - gutterpadding) + 'px'
                                    });

                                } else {

                                    $brickphoto.css({
                                        'width': ((colWidth * 2) - gutterpadding) + 'px',
                                        'height': (((colWidth * 2) - gutterpadding)) + 'px',
                                    });

                                }

                            }
                        }
                    });
                }

                // Call isotope functions in correct order
                var runIsotope = function () {

                    setBrickSize($this.find('.isotope-item'));
                    callIsotope();

                }

                // Run Isotope on load
                runIsotope();

                if ($firstTimeLoad === true) { // This Part Of Code Obnly Load When Page Load not In Resizeing and Resize id 0 for first load

                    //portfolio Filter
                    if (self.windowWidth > 979) { // if Desktop
                        var $pFilterItem = $portfolioID.concat(' .filters .filter_item');
                    } else {
                        var $pFilterItem = $portfolioID.concat(' .filterstablet .filter_item');
                    }


                    var $pThis = $portfolioID.concat(' .isotope');

                    var $pFilterToggle = $portfolioID.concat(' .filterToggle');
                    var $pFilter = $portfolioID.concat(' .filters');
                    var i = 0;

                    $($pFilterToggle).click(function () {
                        $(this).toggleClass('closed');
                        $($pFilter).removeClass('toggleClicked').addClass('toggleClicked');
                        $($pFilter).toggleClass('openToggle');
                        i++;

                    });

                    $($pFilterItem).click(function (e) {
                        e.preventDefault();
                        $($pFilter).removeClass('toggleClicked');

                        var $pFilterText = $portfolioID.concat(' .portfolio-filter span.text');
                        $($pFilterItem).removeClass("active");

                        var $selector = $(this).attr('data-filter');

                        $($pFilterItem).each(function () {
                            var $filterselect = $(this).attr('data-filter');
                            if ($filterselect == $selector) {
                                $(this).addClass("active");
                            }
                        });

                        if ($this.hasClass('fadeInFromBottom') || $this.hasClass('fadeInFromTop') || $this.hasClass('fadeInFromRight') || $this.hasClass('fadeInFromLeft') || $this.hasClass('zoomIn')) {

                            $this.find('.postphoto').addClass('categoryReload');
                            $this.find('.portfolio_text_meta ').removeClass('textVisiblity');

                            if ($this.hasClass('slideIn')) {
                                $this.find('.postphoto').css({ 'transform': 'scale(.4)', 'transition-delay': '0s' });
                            }

                            $this.find('.postphoto').removeClass('isAnimated');
                            $this.find('.postphoto').removeClass('isEaseInAnimated');
                            $this.find('.postphoto').removeClass('isZoomInAnimated')

                            setTimeout(function () {

                                $this.isotope({ filter: $selector }, function () {

                                    if ($this.hasClass('slideIn')) {
                                        $this.find('.postphoto').css({ 'transform': 'translate3d(0,100px,0)', 'transition-delay': '0s' });
                                    }

                                    $this.find('.postphoto').removeClass('categoryReload');

                                    self.isotopeAnimation($this);

                                });

                            }, 300);

                        } else {

                            $this.isotope({ filter: $selector });

                        }

                        $($pFilterText).html($(this).html());

                        self.parallaxImg();
                        return false;

                    });
                }

            });


            // filter drop down in responsive Device
            $('.filterstablet').click(function (e) {
                $(this).toggleClass('responsive_filter_dropdown');
            });

            $('.filterstablet .cat-item').click(function (e) {
                $('.filterstablet').toggleClass('responsive_filter_dropdown');
            });

            // close tablet filter drop down in all elements except '.filterstablet'
            $('body').click(function (e) {
                if ($(e.target).is('ul.filterstablet, ul.filterstablet *')) {
                    return;
                }

                $('.filterstablet').addClass('responsive_filter_dropdown');
            });


        },

        /*-----------------------------------------------------------------------------------*/
        /*  initialize portfolio functions
        /*-----------------------------------------------------------------------------------*/

        portfolioDHashChange: function () {
            var self = this;

            self.$window.bind('hashchange', function () {
                self.pDInitialize();
                self.pageRefresh = false;

            });
        },

        pDInitialize: function () {
            var self = this,
                root = '#!portfolio-detail/',
                rootLength = root.length,
                hash = $(window.location).attr('hash'),
                portfolioGrid = $('#isotopePortfolio');

            if (hash.substr(0, rootLength) != root)
                return;

            self.projectContainer = $('#portfolioDetailAjax');

            if (!self.projectContainer.length)
                return;

            var loader = $('.portfolioSection #loader');
            self.pDError = $('#pDError');
            self.pDetailNav = $('.navWrap');

            hash = $(window.location).attr('hash');
            var href = location.href.replace(location.hash, "");


            if (hash.substr(0, rootLength) != root) {

                return;

            } else {

                hash = $(window.location).attr('hash');
                var url = hash.replace(/[#\!]/g, '');

                portfolioGrid.find('.ajaxPDetail .isotope-item.current').removeClass('current');

                // if Url has Portfolio Detail Address
                if (self.pageRefresh == true && hash.substr(0, rootLength) == root) {

                    $('html,body').stop().animate(
                        { scrollTop: (self.projectContainer.offset().top) + 'px' },
                        800,
                        'easeOutExpo',
                        function () {
                            self.loadPortfolioDetail(url, href);
                            self.pDetailNav.fadeOut('100');
                        }
                    );

                    // open Portfolio Detail When Click On Portfolio Items or trough portfolio navigation
                } else if (self.pageRefresh == false && hash.substr(0, rootLength) == root) {

                    $('html,body').stop().animate(
                        { scrollTop: (self.projectContainer.offset().top) + 'px' },
                        800,
                        'easeOutExpo',
                        function () {
                            if (self.content == false) {
                                self.loadPortfolioDetail(url, href);
                            } else {
                                self.projectContainer.animate({ opacity: 0, height: self.wrapperHeight }, function () {
                                    self.loadPortfolioDetail(url, href);
                                });
                            }
                            self.pDetailNav.fadeOut('100');
                        }
                    );

                }


                // ADD ACTIVE CLASS TO CURRENTLY CLICKED PROJECT 
                portfolioGrid.find('.ajaxPDetail .isotope-item a[href$="' + hash + '"]').parents('.isotope-item').addClass('current');

            }

        },

        // load portfolio detail 
        loadPortfolioDetail: function (url, href) {
            var self = this,
                loader = $('.portfolioSection #loader');

            if ($('#portfolioDetailAjax').height() < 500) {

                self.projectContainer.animate(
                        { height: '500px' },
                        {
                            queue: false,
                            duration: 250
                        }
                );
            }

            loader.css("top", (self.windowHeight / 2) + "px").fadeIn();

            url = url.replace("portfolio-detail/", "");
            url = epico_theme_vars.url + '?portfolio=' + url + '&inner=1 #portfoliSingle';

            if (!self.ajaxLoading) {
                self.ajaxLoading = true;
                self.projectContainer.load(url, function (xhr, statusText, request) {
                    if (statusText == "success") {

                        self.ajaxLoading = false;
                        self.pDError.hide();
                        $('#portfoliSingle').waitForImages(function () {
                            self.pDSwiper();// Call swiper Slider
                            self.hideLoader();
                            self.Social_link();
                            self.homeHeight();

                        });

                        self.fitVideo();

                        //shortcodes
                        self.epico_shortcode(false);
                        self.shortcodeAnimation();
                        self.social_share_pop_up();

                    }

                    if (statusText == "error") {

                        self.pDError.show();
                        self.hideLoader();

                    }

                });

            }
        },

        portfolioDetailNavigationLoading: function () {
            var self = this;

            if(!self.$body.hasClass('single-portfolio'))
                return;

            if(!self.$body.hasClass('ajax_page_transition'))
            {
                if (typeof(window.sessionStorage) !== "undefined") {
                    self.$skills = window.sessionStorage.getItem( 'skills' );
                    self.$portfolioBackLink = window.sessionStorage.getItem( 'portfolioBackLink' );
                    self.$portfolioID = window.sessionStorage.getItem( 'pid' );
                }
            }

            //use default database order to get next/prev item when there is no needed information of current portfolio
            if(self.$skills == undefined) {

                self.$skills = 'all';
                self.$portfolioBackLink = undefined;
                self.$portfolioID = self.$body.data('pageid');
            }

            if(self.$portfolioBackLink == undefined)
            {
                self.$portfolioBackLink = epico_theme_vars.home_url;
            }

            var requestActionName = 'load_pd_navigation';

            if(self.$body.find('.portfolio_detail_creative').length > 0)
            {
                requestActionName = 'load_cpd_navigation';
            }

            $.ajax({
                url: epico_theme_vars.ajax_url,
                data: {
                    'action': requestActionName,
                    'pid': self.$portfolioID,
                    'skill_ids': self.$skills,
                    'back_url': self.$portfolioBackLink,
                    'security': epico_theme_vars.nonce
                },
                async: false,
                success: function (data) {

                    self.$body.find('#PDnavigation').css('display','block').html(data);
                    self.$body.find('.home').addClass('hide-home');

                    if(!self.$body.hasClass('ajax_page_transition'))
                    {
                        self.portfolio_next_prev();
                    }
                    else
                    {
                        self.reInitDjax();
                    }
                }
            });

        },

        hideLoader: function () {
            var self = this,
                loader = $('.portfolioSection #loader');

            loader.delay(400).fadeOut('fast', function () {
                self.showProject();
            });
        },


        showProject: function () {
            var self = this,
                portfolioGrid = $('#isotopePortfolio');

            if (self.content == false) {

                //load  portfolio detail by click on portfolio items
                self.wrapperHeight = self.projectContainer.children('#portfoliSingle').outerHeight() + 'px';
                self.projectContainer.animate({ opacity: 1, height: self.wrapperHeight }, function () {

                    var scrollPostition = $('html,body').scrollTop();
                    self.pDetailNav.fadeIn(400);
                    self.content = true;

                    self.parallaxImg();

                });

            } else {
                //load next and prev portfolio detail by Click navigation 
                self.wrapperHeight = self.projectContainer.children('#portfoliSingle').outerHeight() + 'px';
                self.projectContainer.animate({ opacity: 1, height: self.wrapperHeight }, function () {

                    var scrollPostition = $('html,body').scrollTop();
                    self.pDetailNav.fadeIn(400);

                    self.parallaxImg();

                });
            }


            var portfolioIndex = portfolioGrid.find('.ajaxPDetail .isotope-item.current').index();
            var portfolioLength = $('.ajaxPDetail .isotope-item').length - 1;


            if (portfolioIndex == portfolioLength) {

                $('.pDNavigation .next').css('display', 'none');
                $('.pDNavigation .previous').css('display', 'block');

            } else if (portfolioIndex == 0) {

                $('.pDNavigation .previous').css('display', 'none');
                $('.pDNavigation .next').css('display', 'block');

            } else {

                $('.pDNavigation .next,.pDNavigation .previous').css('display', 'block');

            }


            //showing the title
            $('.pDHeader-title').addClass('bg-animated active');

        },

        deletePortfolioDetail: function () {
            var self = this,
                portfolioGrid = $('#isotopePortfolio');

            self.projectContainer.animate(
                { height: '0px' },
                {
                    queue: false,
                    duration: 1000,
                    complete: function () {
                        self.projectContainer.empty();
                        self.parallaxImg();// reset parallax image positions
                    }
                }
            );

            self.projectContainer.animate(
                { opacity: 0 },
                {
                    queue: false,
                    duration: 600
                }
            );

            self.pDetailNav.fadeOut(600);
            self.parallaxImg();

            location = '#_'; // remove URL hash 
            portfolioGrid.find('.ajaxPDetail .isotope-item.current').removeClass('current');

        },

        // linking to Next Portfolio Detail 
        pDNavigationNext: function () {
            var self = this,
                portfolioGrid = $('#isotopePortfolio');

            $('.pDNavigation .next').click(function (e) {

                var current = portfolioGrid.find('.ajaxPDetail .isotope-item.current');
                var next = current.next('.ajaxPDetail .isotope-item');
                var target = $(next).find('a.overlay').attr('href');
                $(this).attr('href', target);

                location = target;

                if (next.length === 0) {
                    return false;
                }

                current.removeClass('current');
                next.addClass('current');

            });

        },

        // Social links 
        Social_link: function () {
            if ($('.woocommercepage').length == 0 && $('.social_share_toggle').parents('div.product').length == 0) {
                $('.social_share_toggle').find('.social_links_list').addClass('openToggle');
            }
        },

        // linking to previous Portfolio Detail 
        pDNavigationPrevious: function () {
            var self = this,
                portfolioGrid = $('#isotopePortfolio');

            $('.pDNavigation .previous').click(function (e) {
                var current = portfolioGrid.find('.ajaxPDetail .isotope-item.current');
                var prev = current.prev('.ajaxPDetail .isotope-item');
                var target = $(prev).find('a.overlay').attr('href');
                $(this).attr('href', target);

                location = target;

                if (prev.length === 0) {
                    return false;
                }

                current.removeClass('current');
                prev.addClass('current');

                e.preventDefault();

            });

        },

        // Closing the Portfolio detail 
        pDCloseProject: function () {
            var self = this,
                loader = $('.portfolioSection #loader');
            $('#PDclosePortfolio').click(function (e) {
                self.deletePortfolioDetail();
                loader.fadeOut();
                e.preventDefault();
            });

        },

        /*-----------------------------------------------------------------------------------*/
        /*  Portfolio Load More Function 
        /*-----------------------------------------------------------------------------------*/

        portfolioLoadMore: function () {

            var self = this;

            var $index = 0;
            var $nextPageIndex = []; // array for save for each portfolio index
            var $maxPPgae = [];// array for save maximum pages For each portfolio sextions

            $('.portfolioSection').each(function () {

                $(this).attr('data-index', $index);
                var $portfolioId = $(this).attr('data-value'),
                $uniqueId = $(this).attr('data-id'),
                $pLoadMore = '.pLoadMore_'.concat($uniqueId),
                $pLoadMoreBtnWrap = $(this).find($pLoadMore),
                $startPage = $(this).attr('data-startPage'),
                $maxPages = $(this).attr('data-maxPages'),
                $nextLink = $(this).attr('data-nextLink');

                if ($pLoadMoreBtnWrap.length < 1)
                    return;

                var startPage = parseInt($startPage);
                $nextPageIndex[$index] = startPage + 1;

                $maxPPgae[$index] = parseInt($maxPages);

                var isLoading = false;

                var $pLoadMoreBtn = $pLoadMoreBtnWrap.find('.loadMore');

                if (parseInt($maxPPgae[$index].toString()) < 2) {

                    $pLoadMoreBtnWrap.fadeOut('fast');

                    return;
                };

                //Active loadmore button 
                $pLoadMoreBtn.removeClass('loadingactive').addClass('loadmoreactive');


                if (parseInt($nextPageIndex[$index].toString()) > parseInt($maxPPgae[$index].toString())) {
                    $pLoadMoreBtn.closest('.pLoadMore').fadeOut('fast');
                }

                var resTimer = 0;

                $pLoadMoreBtn.click(function () {

                    var $dataIndex = parseInt($(this).closest('.portfolioSection').attr('data-index'));

                    if (parseInt($nextPageIndex[$dataIndex].toString()) > parseInt($maxPPgae[$dataIndex].toString()) || isLoading)
                        return;

                    isLoading = true;
                    $uniqueId = $(this).attr('data-id');
                    var $portfolioLoop = '#pLoop_'.concat($uniqueId);
                    var $pagedNum = 'paged_'.concat($uniqueId);
                    var $pItemsWrap = $portfolioLoop;

                    //active loading state
                    $pLoadMoreBtn.removeClass('loadmoreactive').addClass('loadingactive');

                    var $pageContainer = $('<div class="loadItemsWrap"></div>');

                    $nextLink = $nextLink.replace(/\/page\/[0-9]+/, '/?' + $pagedNum + '=' + parseInt($nextPageIndex[$dataIndex].toString()));
                    $nextLink = $nextLink.replace(/paged=[0-9]+/, $pagedNum + '=' + parseInt($nextPageIndex[$dataIndex].toString()));
                    $nextLink = $nextLink.replace(/paged_[0-9]+=[0-9]+/, $pagedNum + '=' + parseInt($nextPageIndex[$dataIndex].toString()));

                    $pageContainer.load($nextLink + ' ' + $portfolioLoop + ' .isotope-item', function () {

                        // remove loadItemsWrap div From Loaded items 
                        $pageContainer = $pageContainer.find('.isotope-item').unwrap();

                        //Insert the posts container before the load more button
                        $pageContainer.appendTo($pItemsWrap);

                        // Update page number and nextLink.
                        $nextPageIndex[$dataIndex]++;

                        if (/\/page\/[0-9]+/.test($nextLink))
                            $nextLink = $nextLink.replace(/\/page\/[0-9]+/, '/page/' + parseInt($nextPageIndex[$dataIndex].toString()));
                        else {

                            var str1 = $pagedNum.concat('=[0-9]+');
                            var re = new RegExp(str1);

                            $nextLink = $nextLink.replace(re, $pagedNum + '=' + parseInt($nextPageIndex[$dataIndex].toString()));

                        }
                        if (parseInt($nextPageIndex[$dataIndex].toString()) <= parseInt($maxPPgae[$dataIndex].toString()))
                            $pLoadMoreBtn.removeClass('loadingactive').addClass('loadmoreactive');
                        else if (parseInt($nextPageIndex[$dataIndex].toString()) > parseInt($maxPPgae[$dataIndex].toString())) {
                            $pLoadMoreBtn.closest('.pLoadMore').fadeOut('fast');
                        }

                        isLoading = false;

                        var $items = $($pItemsWrap).find('.isotope-item');
                        var $container = $($pItemsWrap).closest('.isotope');

                        $container.isotope('appended', $items, function () {
                            $container.isotope('reLayout');
                        });

                        var $pIsotopeContainer = '#portfolio_'.concat($uniqueId).concat(' .isotope');
                        self.portfolioIsotope($pIsotopeContainer);
                        self.parallaxImg(); // reset parallax image positions
                        self.portfolioSlider();//feature image slider
                        self.social_share_pop_up();
                        self.reInitDjax();
                        self.lazyLoadOnLoad($container);
                        //Destroy previous gallery then re-run it
                        self.$document.find(".ep_lightGallery").each(function () {
                            $(this).data('lightGallery').destroy(true);
                        });

                        self.galleryStart();

                    });
                });

                $index++;

            });
        },

        /*----------------------------------------------------------------------------------*/
        /*  portfolio / Gallery / instagram animation 
        /*-----------------------------------------------------------------------------------*/

        animationDelay: function (counter, $selector) {
            var base_delay = 0.2;
            return base_delay * counter;
        },

        setAnimationForItems: function ($container, $items, $item, $delay) {
            //Select one of available animations
            if ($container.hasClass('fadeInFromBottom') || $container.hasClass('fadeInFromTop') || $container.hasClass('fadeInFromRight') || $container.hasClass('fadeInFromLeft')) {
                $container.find('ul li').addClass('isEaseInAnimated');
                $item.addClass('isAnimated');
                $item.css({ 'transition-delay': $delay + 's' });
            } else if ($container.hasClass('zoomIn')) {
                $container.find('.postphoto').addClass('isZoomInAnimated');
                $item.addClass('isAnimated');
                $item.css({ 'transition-delay': $delay + 's' });
            } else if ($container.hasClass('default')) {
                $container.find('.postphoto').addClass('isDefault');
                $item.addClass('isAnimated');
            }
        },

        /*  Gallery / portfolio animation / cart blog  */
        isotopeAnimation: function ($container) {
            var self = this,
                $this = $container,
                counter = 0,
                $selector;

            if((self.isMobile || self.isTablet) && $container.hasClass('no-responsive-animation'))
                return;

            $container.find('.isotope-item').not('.isotope-hidden').find('.postphoto,.blog_item').not('.isAnimated').waypoint({
                handler: function () {
                    var $this = $(this.element);
                    $this.each(function () {
                        var $item = $(this);
                        setTimeout(function () {
                            //Ask self.animationDelay() for the amount of delay per each item
                            var delay = self.animationDelay(counter, $item);

                            // Select all items
                            var $allItems = $container.find('.postphoto');

                            //Select one of available animations
                            self.setAnimationForItems($container, $allItems, $item, delay);


                            //check to see if gallery or portfolio is of type portfolio_text
                            $item.siblings('.portfolio_text_meta').css({ 'transition-delay': delay + 's' });
                            setTimeout(function () {
                                $item.siblings('.portfolio_text_meta').addClass('textVisiblity');
                            }, 500)

                            //Reset counter per each iteration.
                            counter = counter + 1;

                        }, 50);
                        counter = 0;
                    });
                    this.destroy();
                },
                offset: '95%'
            });
        },

        /* Instagram animation  */
        instagramAnimation: function () {
            var self = this;

            var instagramAnimationBase = function ($container) {
                var $selector,
                counter = 0;

                if ($container.find('ul').hasClass('instagram-carousel')) { // Selector for carousel mode 
                    $selector = '.swiper-slide-visible:not(.isAnimated)';
                } else { // selector for grid mode 
                    $selector = 'ul li:not(.isAnimated)';
                }
                $container.find($selector).waypoint({
                    handler: function () {
                        var $this = $(this.element);

                        $this.each(function () {
                            var $item = $(this);
                            setTimeout(function () {

                                //Ask self.animationDelay() for the amount of delay per each item
                                var delay = self.animationDelay(counter, $item);

                                // Select all items 
                                var $allItems = $container.find('ul li');

                                //Select one of available animations
                                self.setAnimationForItems($container, $allItems, $item, delay);

                                //Reset counter per each iteration.
                                counter = counter + 1;

                            }, 50);
                            counter = 0;
                        });
                        this.destroy();
                    },
                    offset: '95%'
                });
            }

            $('#main .instagram-feed').each(function () {

                var $container = $(this),
                    counter = 0;

                if((self.isMobile || self.isTablet) && $container.hasClass('no-responsive-animation'))
                    return true;

                instagramAnimationBase($container);

            });

            //Remove class of animation because there is no need for animation in toggle-sidebar
            $('.toggleSidebar .instagram-feed').removeClass('fadeInFromBottom fadeInFromTop fadeInFromRight fadeInFromLeft zoomIn');
        },

        //  products and Carousel ( image carousel , Instagram carousel , Products carousel )
        showAnimation: function ($container, $carousel) {

            var self = this,
                counter = 0,
                $carouselItem,
                $duplicateSlider;

            var showAnimationBase = function (that) {
                $(that).each(function () {
                    var $delay;
                    setTimeout(function () {
                        //Ask self.animationDelay() for the amount of delay per each item
                        //Select one of available animations
                        if ($container.hasClass('fadeInFromBottom') || $container.hasClass('fadeInFromTop') || $container.hasClass('fadeInFromRight') || $container.hasClass('fadeInFromLeft')) {

                            $container.find('.productwrap , .swiper-slide').addClass('isEaseInAnimated'); // Find .productWrap For Shop widge , Use Swiper-slide for image carousel
                            $(that).addClass('isAnimated');

                            $delay = 0.3 * counter;
                            $(that).css({ 'transition-delay': $delay + 's' });

                        } else if ($container.hasClass('zoomIn')) {
                            $container.find('.productwrap , .swiper-slide').addClass('isZoomInAnimated');
                            $(that).addClass('isAnimated');

                            $delay = 0.2 * counter;
                            $(that).css({ 'transition-delay': $delay + 's' });

                        } else {
                            $container.find('.productwrap , .swiper-slide').addClass('fadeIn');
                            $(that).addClass('isAnimated');

                            $delay = 0.2 * counter;
                            $(that).css({ 'transition-delay': $delay + 's' });

                        }

                        //Reset counter per each iteration.
                        counter = counter + 1;

                    }, 50);

                    counter = 0;

                });
            }

            //Set animation for duplicate slides seperatly to do not consider in animation delays
            var setAnimationBaseForDuplicatSlides = function ($that) {
                $that.css({ 'transition-delay': '0s' });
                $that.addClass('isAnimated');

                if ($container.hasClass('fadeInFromBottom') || $container.hasClass('fadeInFromTop') || $container.hasClass('fadeInFromRight') || $container.hasClass('fadeInFromLeft')) {
                    $container.find('.productwrap , .swiper-slide').addClass('isEaseInAnimated'); // Find .productWrap For Shop widge , Use Swiper-slide for image carousel
                } else if ($container.hasClass('zoomIn')) {
                    $container.find('.productwrap , .swiper-slide').addClass('isZoomInAnimated');
                } else {
                    $container.find('.productwrap , .swiper-slide').addClass('fadeIn');
                }
            }

            if((self.isMobile || self.isTablet) && $container.hasClass('no-responsive-animation'))
                return true;


            if ($carousel == 1) { //this code For carousel

                $carouselItem = $container.find('li.product');

                if (!$carouselItem.hasClass('swiper-slide-visible')) {
                    return 0;
                }

                $container.find('.swiper-slide-visible .productwrap:not(.isAnimated)').waypoint({
                    handler: function () {
                        var $item = $(this.element);

                        $duplicateSlider = $item.closest('li').siblings('li[data-swiper-slide-index="' + $item.closest('li').data("swiper-slide-index") + '"]').find('.productwrap')

                        $container = $item.parents('.woocommerce.wc-shortcode');

                        //load animation on slide and its duplicate slide
                        showAnimationBase($item);
                        setAnimationBaseForDuplicatSlides($duplicateSlider);
                        this.destroy();
                    },
                    offset: '95%'
                });

            } else if ($carousel == 2) { // For Image Carousel 

                $carouselItem = $container.find('div.swiper-slide');

                if (!$carouselItem.hasClass('swiper-slide-visible')) {
                    return 0;
                }

                if ($container.hasClass('hasAnimation')) {

                    $container.find('.swiper-slide-visible:not(.isAnimated)').waypoint({
                        handler: function () {
                            var $item = $(this.element);

                            $duplicateSlider = $item.siblings('div[data-swiper-slide-index="' + $item.data("swiper-slide-index") + '"]')

                            $container = $item.parents('.carousel');

                            //load animation on slide and its duplicate slide
                            showAnimationBase($item);
                            setAnimationBaseForDuplicatSlides($duplicateSlider);
                            this.destroy();
                        },
                        offset: '95%'
                    });

                }

            } else if ($carousel == 3) { // For Instagram Carousel 

                $carouselItem = $container.find('li.insta-media');

                if (!$carouselItem.hasClass('swiper-slide-visible')) {
                    return 0;
                }

                if ($container.hasClass('hasAnimation')) {
                    var $notAnimatedSlide = $container.find('.swiper-slide-visible:not(.isAnimated)');

                    $notAnimatedSlide.waypoint({
                        handler: function () {
                            var $item = $(this.element);

                            $duplicateSlider = $item.siblings('li[data-swiper-slide-index="' + $item.data("swiper-slide-index") + '"]');
                            $container = $item.parents('.carousel');
                            //load animation on slide and its duplicate slide
                            showAnimationBase($item);
                            setAnimationBaseForDuplicatSlides($duplicateSlider);
                            this.destroy();
                        },
                        offset: '95%'
                    });

                }

            } else { //this code For gird ( no Carousel)

                if (!$container.hasClass('main-shop-loop')) {
                    $container = $container.closest('.woocommerce.wc-shortcode');
                }

                if (!$container.hasClass('default')) { // default = No animation for Product Grid

                    $container.find('.productwrap:not(.isAnimated)').waypoint({
                        handler: function () {
                            var $item = $(this.element);
                            showAnimationBase($item);
                            this.destroy();
                        },
                        offset: '95%'
                    });

                }

            }
        },

        /*----------------------------------------------------------------------------------*/
        /*  Lazy loading images 
        /*-----------------------------------------------------------------------------------*/

        lazyLoadOnLoad: function (that) {

            var $lazyLoadCntainers = $(that).find('.lazy-load-on-load');

            if ($lazyLoadCntainers.length > 0) {
                $lazyLoadCntainers.each(function () {
                    var $this = $(this);

                    if (!$this.hasClass('lazy-loaded') && !$this.hasClass('is-loading')) {
                        $this.addClass('is-loading');
                        var $img, src;

                        if ($this.hasClass('bg-lazy-load')) {
                            src = $this.data('src');

                        }
                        else {
                            $img = $this.find('img');
                            src = $img.data('src');
                        }

                        var img = new Image();

                        img.onload = function () {
                            if ($this.hasClass('bg-lazy-load'))
                                $this.css('background-image', 'url(' + src + ')').removeAttr('data-src');
                            else
                                $img.attr('src', src).removeAttr('data-src');

                            setTimeout(function () {
                                $this.addClass('lazy-loaded');
                            }, 100);
                        }

                        img.src = src;
                    }
                })

            }
        },

        lazyLoadOnHover: function () {
            var self = this;

            if (self.isTouchDevice)
                return;

            var $items = $('.lazy-load-hover-container');

            $items.on('mouseenter', function () {
                var $this = $(this).find('.lazy-load-hover');
                if ($this.length > 0) {
                    if (!$this.hasClass('lazy-loaded') && !$this.hasClass('is-loading')) {
                        $this.addClass('is-loading');
                        $this.closest('.lazy-load-hover-container').addClass('is-loading');

                        var $img, src;

                        if ($this.hasClass('bg-lazy-load')) {
                            src = $this.data('src');
                        }
                        else {
                            $img = $this.find('img');
                            src = $img.data('src');
                        }

                        var img = new Image();

                        img.onload = function () {
                            if ($this.hasClass('bg-lazy-load'))
                                $this.css('background', 'url(' + src + ')').removeAttr('data-src', '');
                            else
                                $img.attr('src', src).removeAttr('data-src', '');

                            $this.closest('.lazy-load-hover-container').removeClass('is-loading');

                            setTimeout(function () {
                                $this.addClass('lazy-loaded');
                            }, 100);
                        }

                        img.src = src;
                    }
                }
            })
        },

        abortImageLoading: function() {
            $('.lazy-load.is-loading:not(.lazy-loaded) img').attr('src','');
        },

        /*----------------------------------------------------------------------------------*/
        /*  products isotope  
        /*-----------------------------------------------------------------------------------*/

        runIsotopeInProducts: function ($mainContainer) {
            var self = this;
            // Gets column number and divides to get column width 
            var getColWidthProduct = function ($container) {
                var setContainereWidth = true,
                    device_width;
                // Check Shop is FullWidth Or not
                if ($container.hasClass('fullwidthshop')) {
                    var $product_fullwidth = true;
                }

                if ($product_fullwidth == true) { //fullwidth

                    if ($container.parents('.wc-ajax-content').length > 0)// in main shop page
                    {
                        device_width = $container.parents('.wc-ajax-content').width();
                    }

                } else {  // in Container
                    if ($container.parents('.vc_tta-panel-body').length > 0)// in tab
                    {
                        device_width = $container.find('.woocommerce').width()
                    }
                    else// in Container
                    {
                        if ($container.parents('.wpb_wrapper').length) {
                            device_width = $container.parents('.wpb_wrapper').width();
                        }
                        else if ($container.parents('.wc-ajax-content').length) {
                            device_width = $container.parents('.wpb_wrapper').width();
                            setContainereWidth = false;
                        }

                    }
                }

                var w = device_width;

                var columnNum;

                if ($container.hasClass('shop-5column')) {
                    columnNum = 5;
                } else if ($container.parent().hasClass('shop-4column')) {
                    columnNum = 4;
                } else if ($container.parent().hasClass('shop-3column')) {
                    columnNum = 3;
                } else {
                    columnNum = 2;
                }

                if (self.windowWidth <= 1140) {
                    if (columnNum > 3) {
                        columnNum = 3;//3column in horizontal tablet
                    }

                    if (self.windowWidth > 767 && self.windowWidth <= 979) {
                        if (columnNum > 2) {
                            columnNum = 2;//2column in vertical tablets
                        }
                    }
                    else if (self.windowWidth <= 767) {
                        columnNum = 1;//1column in mobile devices
                    }


                }

                if (setContainereWidth == true) {
                    $container.width(w); // set width for isotope ul 
                }

                var colWidth = Math.floor(w / columnNum);

                return colWidth;
            }

            if ($mainContainer === undefined)
                $mainContainer = self.$body;

            //WC shortcodes + main shop page loop
            //$mainContainer.find('.woocommerce.wc-shortcode:not(.carousel) .products, .wc-ajax-content > .products.main-shop-loop').each(function () {
            $mainContainer.find('.woocommerce.wc-shortcode:not(.carousel) .products, .products.main-shop-loop').each(function () {
                var $container = $(this);
                //Reset width to calculate colWidth correctly in resize
                $container.css('width', '');
                var colWidth = getColWidthProduct($container),
                    layout = 'fitrows';

                if ($container.is('.main-shop-loop')) {
                    layout = $container.data('layoutmode');
                }
                else {
                    layout = $container.parent().data('layoutmode');
                }

                if (layout != 'fitRows') {
                    layout = 'masonry';
                }

                $container.isotope({
                    itemSelector: '.product',
                    layoutMode: layout,
                }, function () {
                    // show products items with animations in isotope call back
                    self.showAnimation($container);
                });

            });
        },

        /*----------------------------------------------------------------------------------*/
        /*  call Shortcode functions
        /*-----------------------------------------------------------------------------------*/

        epico_shortcode: function ($comesfromtab) {
            var self = this;

            self.testimonial();
            self.piechart();
            self.countdown();
            self.counterBox();
            self.fullWidthSection();

            self.faq();
            self.progressbar();
            self.showcase();
            self.custom_textbox();
            self.customTitleParallax();

            if ($comesfromtab != true) { // prevent to run Again in tab tour accordian
                self.tabs_tour_accordion();
            }

        },

        /*----------------------------------------------------------------------------------*/
        /*  mediaelementplayer ( Html Video )
        /*-----------------------------------------------------------------------------------*/

        initVideoBackground: function () {

            var self = this;

            if (typeof $.fn.mediaelementplayer == 'function') {
                $('.video').mediaelementplayer({
                    enableKeyboard: false,
                    iPadUseNativeControls: false,
                    pauseOtherPlayers: false,
                    iPhoneUseNativeControls: false,
                    AndroidUseNativeControls: false,
                    features: ['playpause', 'progress', 'current', 'duration', 'tracks', 'volume', 'fullscreen'],
                    success: function (mediaElement, domObject) {
                        // fade in play buttons and poster image In Video Html 5 inline 
                        $('.mejs-poster').addClass('fadeIn');

                    },
                });

                //mobile check
                if (navigator.userAgent.match(/(Android|iPod|iPhone|iPad|IEMobile|Opera Mini)/)) {
                    self.videoBackgroundSize();
                    $('.videoHomePreload').show();
                    $('.videoWrap').remove();
                }

                // append play button In poster image wrap ( for inline Video ) - we need set this div In this Position For hidding Play buttons when click On video
                var $play_button = $('.inline_video.video_embed_container .play-button');
                $('.inline_video.video_embed_container .play-button').remove();

                var $mejs_poster = $('.mejs-poster');
                $play_button.appendTo($mejs_poster);
            }
        },

		
		
        /*----------------------------------------------------------------------------------*/
        /*  light galley for product detail
        /*-----------------------------------------------------------------------------------*/
		
        product_gallery_popup_lightGallery:function(){
			if (typeof $.fn.lightGallery !== 'function' || $('#product-fullview-thumbs .enable-popup').length <= 0)
				return;

	  		var $product_popup_gallery = $('#product-fullview-thumbs');
			$product_popup_gallery.lightGallery({
				selector: '.enable-popup',
				getCaptionFromTitleOrAlt : false
			});
			
			$('.popup-button').on('click',function() {
				$product_popup_gallery.find('.swiper-slide').trigger('click.lgcustom');
			});
			
			$product_popup_gallery.on('onSlideClick.lg',function() {
				$product_popup_gallery.data('lightGallery').goToNextSlide();
			});
        },
		

        /*----------------------------------------------------------------------------------*/
        /*  light galley for video
        /*-----------------------------------------------------------------------------------*/

        embed_video_lightgallery: function () {

            $('.video_embed_container').each(function (i) {

                var $this = $(this);

                var $video_embed_id = $this.attr('id');
                if (typeof $.fn.lightGallery == 'function') {
                    $this.not('.inline_video').lightGallery({
                        counter: false,
                        addClass: 'videoPopUp',
                        galleryId: $video_embed_id,
                    });
                }
            });

        },


        /*----------------------------------------------------------------------------------*/
        /*  light galley Carousel
        /*-----------------------------------------------------------------------------------*/

        carousel_gallery: function () {

            if (typeof $.fn.lightGallery !== 'function' || $('.carousel_gallery').length <= 0)
                return;

            var $carousel_gallery = $('.carousel_gallery');
            $carousel_gallery.lightGallery({
                thumbnail: true,
                selector: '.galleryCarouselLink',
            });

        },

        /*----------------------------------------------------------------------------------*/
        /*  Video background size
        /*-----------------------------------------------------------------------------------*/

        videoBackgroundSize: function () {

            $('.videoWrap').each(function (i) {

                var $sectionWidth = $(this).closest('.videoHome ').outerWidth();
                var $vcVideoWrap = $(this).parents('.vc_videowrap');

                if ($vcVideoWrap.length) {

                    var $sectionHeight = $vcVideoWrap.find('.vc_videocontent').outerHeight();

                    $(this).width($sectionWidth);
                    $vcVideoWrap.height($sectionHeight);

                } else {

                    var $sectionHeight = $(this).closest('.videoHome').outerHeight();
                    $(this).width($sectionWidth);
                    $(this).height($sectionHeight);

                }

                // calculate scale ratio
                var videoWidthOriginal = 1280,  // original video dimensions
                videoHeightOriginal = 720,
                vidRatio = 1280 / 720,
                scale_h = $sectionWidth / videoWidthOriginal,
                scale_v = ($sectionHeight) / videoHeightOriginal,
                scale = scale_h > scale_v ? scale_h : scale_v;

                // limit minimum width
                var minVideoWidth = vidRatio * ($sectionHeight + 20);

                if (scale * videoWidthOriginal < minVideoWidth) { scale = minVideoWidth / videoWidthOriginal; }

                $(this).find('video').width(Math.ceil(scale * videoWidthOriginal + 2));
                $(this).find('video').height(Math.ceil(scale * videoHeightOriginal + 2));

                $(this).scrollLeft(($(this).find('video').width() - $sectionWidth) / 2);
                $(this).find('.mejs-overlay, .mejs-poster').scrollTop(($(this).find('video').height() - ($sectionHeight)) / 2);
                $(this).scrollTop(($(this).find('video').height() - ($sectionHeight)) / 2);

            });

        },

        /*----------------------------------------------------------------------------------*/
        /*   top space for blog and portfolio in main page
        /*-----------------------------------------------------------------------------------*/

        // page Top Space depends On menu Height
        pageTopSpace: function () {
            var topSpace = 0,
                topbarHeight = 0;

            if ($('#topbar').length) {
                topbarHeight = 33;
            }

            if ($('header').hasClass('type2_3')) {

                topSpace = 125 + topbarHeight;

            } else if ($('header').hasClass('type4_5_6')) {

                topSpace = 85 + topbarHeight;

            } else {

                topSpace = 58 + topbarHeight;

            }

            return topSpace;
        },


        /*----------------------------------------------------------------------------------*/
        /*  set min-height For Blog and blog Detail 
        /*-----------------------------------------------------------------------------------*/

        minPageHeightSet: function () {
            var self = this;

            if (self.windowWidth > 1140) {

                var $pageFooterHeight = $('.footer-bottom').height(),
                    topbarHeight = $('#topbar').height();

                // Add Google Map Height  too fooret height
                if ($('#googleMap').length) {
                    $pageFooterHeight = $pageFooterHeight + $('#googleMap').height();
                }

                // Add Footer Widget section height too Footer height
                if ($('.footer-widgetized').length) {
                    $pageFooterHeight = $pageFooterHeight + $('.footer-widgetized').height();
                }

                var $pageMainHeight,
                        $wholePageHeight,
                        $pageMainHeight2;

                //check if page is without slider, set a min-height for page
                if ($('#fullScreenSlider').length <= 0 && $('#blogSingle').length <= 0) {
                    $pageMainHeight = $('#main').height();
                    $wholePageHeight = $pageMainHeight + $pageFooterHeight;
                    $pageMainHeight2 = self.windowHeight - $pageFooterHeight - topbarHeight;

                    $('#main').css({
                        'min-height': $pageMainHeight2 + "px",
                    });

                } else if ($('#blogSingle').length) {

                    $pageMainHeight = $('#blogSingle').height();
                    $wholePageHeight = $pageMainHeight + $pageFooterHeight;
                    $pageMainHeight2 = self.windowHeight - $pageFooterHeight - topbarHeight;

                    $('#blogSingle').css({
                        'min-height': $pageMainHeight2 + "px"
                    });

                }
                else {
                    $('#main').css({ 'min-height': "0px" });
                }
            }
        },

        /*----------------------------------------------------------------------------------*/
        /*  socail share icon
        /*-----------------------------------------------------------------------------------*/

        socailshare: function () {
            // Google Plus like button
            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
            po.src = 'https://apis.google.com/js/plusone.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
        },

        /*-----------------------------------------------------------------------------------*/
        /*  social share's pop up 
        /*-----------------------------------------------------------------------------------*/

        social_share_pop_up: function () {

            $(".social_links a, .socialShareContainer a").click(function (e) {
                e.preventDefault();

                var $url = $(this).attr('href'),
                    $title = $(this).attr('title'),
                    newwindow = window.open($url, $title, 'height=300,width=600');

                if (window.focus) { newwindow.focus() }
                return false;
            });
        },

        /*----------------------------------------------------------------------------------*/
        /*  WPML
        /*-----------------------------------------------------------------------------------*/

        wpml_menu: function () {

            if ($('.headerWrap .menu-item-language').length) {
                $('.headerWrap .menu-item-language').append($("<span class='spanHover'></span>"));
            }
        },


        /*----------------------------------------------------------------------------------*/
        /*   Showcase
        /*-----------------------------------------------------------------------------------*/

        // Set Showcase Height
        showcase_height: function () {
            var self = this;

            if(self.windowWidth <= 1140)
                return;

            var $showcase_wrapper = $('.showcase').find('.showcase-content-wrapper'),
                $empty_space = self.windowHeight - $showcase_wrapper.height(),
                $showcaseHeight;

            if (self.$body.hasClass('has-topbar') && !self.$body.hasClass('snap-to-scroll')) {
                $showcaseHeight = self.windowHeight - 33;
            } else {
                var $showcaseHeight = self.windowHeight;
            }

            //set showcase height
            $('.showcase').css({ height: $showcaseHeight });

            //set center align vertically
            if ($empty_space >= 0) {   //Add 30px more to have more distance from top for better looking of content
                $showcase_wrapper.css({ "margin-top": ($empty_space / 2) + 30 + "px" });
            }

        },

        showcase_appear: function () {
            var self = this;

            $('.showcase').each(function () {

                var $showcase_item = $(this);

                if ($showcase_item.data('effect') == 'kunburn') {
                    //tablet
                    if (self.windowWidth <= 1140) {
                        $showcase_item.find('.showcase-bg').removeClass('bg-animated active');
                        $showcase_item.find('.showcase-item').removeClass('active');
                        $showcase_item.find('.item-list li').removeClass('active');

                        //activate and animate
                        $showcase_item.find('.showcase-bg').first().addClass('bg-animated active');
                        $showcase_item.find('.showcase-item').first().addClass('active');
                        $showcase_item.find('.item-list li').first().addClass('active');

                    }
                    else {

                        $showcase_item.addClass('show');
                        var thumbInView = new Waypoint.Inview({
                            element: $showcase_item,
                            enter: function () {
                                var $item = $(this.element);
                                //reset
                                //use firstactive class to show background immediately after loading page
                                if ($item.find('.firstactive').length > 0) {
                                    $item.find('.firstactive').removeClass('firstactive');
                                }
                                else {
                                    $item.find('.showcase-bg').removeClass('bg-animated active');
                                }

                                $item.find('.showcase-item').removeClass('active');
                                $item.find('.item-list li').removeClass('active');

                                $item.find('.showcase-item').first().addClass('active');
                                $item.find('.item-list li').first().addClass('active');

                                //activate and animate
                                //There is should be a delay between removeing and adding classes to run animations again
                                setTimeout(function () {
                                    $item.find('.showcase-bg').first().addClass('bg-animated active');
                                }, 50);
                            },
                            offset: '95%'
                        });

                    }
                }
                else {
                    //use firstactive class to show background immediately after loading page
                    $showcase_item.find('.firstactive').removeClass('firstactive');

                    //activate and animate
                    $showcase_item.addClass('show');
                    $showcase_item.find('.showcase-bg').first().addClass('active');
                    $showcase_item.find('.showcase-item').first().addClass('active');
                    $showcase_item.find('.item-list li').first().addClass('active');
                    self.epico_interactive_background($showcase_item, { target: '> .showcase-backgrounds .showcase-bg', sensitivity: 80, zoom: false, initialZoom : true });
                }

            });
        },

        showcase: function () {

            var self = this;

            var $showcases = $('.showcase');

            if (!$showcases.length)
                return;

            // Set showcase height when the page loaded
            self.showcase_height();

            //active showcases
            self.showcase_appear();

            self.epico_scrollbar($('.showcase').find('.item-content p'));

            $(".showcase .next-showcase a").click(function (e) {
                e.preventDefault();

                var $next_section = $(this).parents('.showcase').nextAll('.showcase').first(); // if Two showcases are in One row

                if (!$next_section.length) {
                    $next_section = $(this).parents('.showcase').parent().parent().nextAll('div').first().find('.showcase'); // if two showcases are in one row (in same or different span)
                }

                if (!$next_section.length) {
                    $next_section = $(this).parents('.ep-section').nextAll('.ep-section').first(); // if two showcases are in different row
                }

                if (!$next_section.length) {
                    $next_section = $(this).parents('.customSection').nextAll('.customSection').first();// if two showcases are in different pages tandemly
                }

                if (!$next_section.length) return;

                self.scroll_to($next_section, 5);

            });

            $('.showcase').each(function () {
                var $showcase = $(this),
                    $showcase_effect = $showcase.data('effect'),
                    $backgrounds = $showcase.find('.showcase-backgrounds'),
                    $contents = $showcase.find('.showcase-items'),
                    $items_list = $showcase.find('.item-list li');

                $items_list.on('hover', function () {
                    var $this = $(this);

                    if (!$this.hasClass('active')) {

                        var $bgid = $this.data('bg-id');

                        //Activate hovered item
                        $items_list.removeClass('active');
                        $this.addClass('active');

                        //Activate background
                        var $prev_bg_item = $backgrounds.find('.active');
                        var $next_bg_item = $backgrounds.find('[data-bg-id="' + $bgid + '"]');
                        $prev_bg_item.removeClass('active');

                        if ($showcase_effect == 'kunburn') {
                            var $direction = Array('left-top', 'left-bottom', 'right-top', 'right-bottom');
                            var $random_dir = $direction[Math.floor(Math.random() * $direction.length)];

                            $next_bg_item.addClass('active bg-animated ' + $random_dir);

                            setTimeout(function () {
                                $prev_bg_item.removeClass('bg-animated left-top left-bottom right-top right-bottom');
                            }, 600);
                        }
                        else {
                            $next_bg_item.addClass('active');
                        }


                        //Activate content
                        var $prev_content_item = $contents.find('.active');
                        var $next_content_item = $contents.find('[data-bg-id="' + $bgid + '"]');
                        $next_content_item.addClass('active');
                        $prev_content_item.removeClass('active');
                    }

                });

            });

            var slidesPerView = 4;
            if(self.windowWidth <= 1140)
            {
                slidesPerView = 3;
            }
            $('.showcase .item-pics .swiper-container').each(function () {
                var $swiper = new Swiper($(this), {
                    slidesPerView: slidesPerView,
                    speed: 700,
                    paginationClickable: true,
                    spaceBetween: 2,
                    grabCursor: true
                });
            });

            $('.showcase .swiper-button-next').on('click', function (e) {
                e.preventDefault();
                $(this).siblings('.swiper-container')[0].swiper.slideNext();
            });

            $('.showcase .swiper-button-prev').on('click', function (e) {
                e.preventDefault();
                $(this).siblings('.swiper-container')[0].swiper.slidePrev();
            });


        },

        /*----------------------------------------------------------------------------------*/
        /*   custom Textbox
        /*-----------------------------------------------------------------------------------*/

        custom_textbox: function () {

            $('.custom-textbox').waypoint({
                handler: function () {
                    var $item = $(this.element);
                    $item.addClass('bg-animated active');
                    this.destroy();
                },
                offset: '95%'
            });
        },

        /*----------------------------------------------------------------------------------*/
        /*   Custom title
        /*-----------------------------------------------------------------------------------*/

        customTitleParallax: function () {

            var self = this;

            if ($('.custom-title').length <= 0 || self.msie > 0 || !!self.msie11 || self.windowWidth <= 1140 || self.isTouchDevice)
                return;

            var $titles = $('.custom-title');
            self.windowHeight = self.$window.height();
            var scrollPosition = 0;

            var parallax_handler = function () {
                $titles.each(function () {
                    var $el = $(this),
                        elementTop = $el.data('offsetTop'),
                        factorMult = 0;

                    var offsetDiff = elementTop - scrollPosition;
                    factorMult = 1 - ((((self.windowHeight / 3) - offsetDiff) / (self.windowHeight / 3)) * 1.1);

                    if (offsetDiff < (self.windowHeight / 3) && factorMult >= 0 && factorMult <= 1) {
                        //title
                        $el.css({ 'opacity': factorMult });
                    }

                    var $parallaxed_Shape = $(this).find('.shape-container');
                    if ($parallaxed_Shape.length) {

                        //shape
                        factorMult = (elementTop - scrollPosition - (self.windowHeight / 4)) * .15;

                        // Parallax For Custom Shape  in top of [ $self.windowHeight / 4 ]  size 
                        if (elementTop < (self.windowHeight / 4)) {

                            factorMult = factorMult + (self.windowHeight / 16);
                            $parallaxed_Shape.css({ transform: 'translate3d(0,' + factorMult + 'px,0)' });

                        } else {

                            // Parallax For Custom Shape 
                            if (factorMult > -10) {
                                $parallaxed_Shape.css({ transform: 'translate3d(0,' + factorMult + 'px,0)' });
                            }

                        }
                    }
                });
            }

            var parallax_init = function () {
                $titles.each(function () {
                    var $el = $(this);
                    if ($el.find('.title >span').length) {
                        $el.data('offsetTop', $el.find('.title >span').offset().top);
                    }
                });
            }

            var requestTick = function () {
                parallax_init();
                scrollPosition = self.$window.scrollTop();
                window.requestAnimationFrame(parallax_handler);
            }

            var resizeHandler = function () {
                self.windowHeight = self.$window.height();
                parallax_init();
            }

            parallax_init();
            parallax_handler();

            self.$window.on('scroll', requestTick).resize(resizeHandler);

            self.$window.one('djaxClick', function () {
                self.$window.unbind('scroll', requestTick).unbind('resize', resizeHandler);
            });
        },


        /*----------------------------------------------------------------------------------*/
        /*   reInitialise the VC post grid
        /*-----------------------------------------------------------------------------------*/

        vcGridReInit: function () {
            if ($.fn.vcGrid)
                $.fn.vcGrid.call($('[data-vc-grid-settings]'));
        },

        /*----------------------------------------------------------------------------------*/
        /*   Workaround for double tap issue on IOS
        /*-----------------------------------------------------------------------------------*/
        fix_IOS_double_tap_issue: function () {
            if (!navigator.platform.match(/(iPhone|iPod|iPad)/i))
                return;

            var $elements = $('.buttons, .carousel .arrows-button-prev, .carousel .arrows-button-next,#blogSingle .arrows-button-next,#blogSingle .arrows-button-prev, .swiper-button-prev, .swiper-button-next,.woocommerce.infoOnHover li.product, .woocommerce.infoOnHover li.product .hover-image,.woocommerce.infoOnHover li.product a.product-link');
            $elements = $elements.add('#mobile-menu-button,.cartSidebarbtn,.sidebartogglebtn,#cart-close-btn,#toggle-sidebar-close-btn,#sidebar-open-overlay,.responsive-wishlist a, .popup_interaction .soundcloud-format .play-button-wrap, .blog-masonry-container .play-button,#ep-modal,#ep-modal.quickview-modal #modal-close, #ep-modal a[rel="next"], #ep-modal a[rel="prev"]');
            $elements = $elements.add('.banner a,.gallery .postphoto, .galleryItem a, .portfolio a, .portfolio a, .readmore .loadMore, .woocommerce ul.products li.product .product-buttons > span a, .pDWrap .pDNavigation a,.pDWrap #PDclosePortfolio');
            $elements.on('touchstart mouseenter focus', function (e) {
                if (e.type == 'touchstart') {
                    e.stopImmediatePropagation();
                }
            });
        },

        /*----------------------------------------------------------------------------------*/
        /*   Ajax(djax) request handling
        /*-----------------------------------------------------------------------------------*/

        page_transition: function () {
            var self = this;

            if(!self.$body.hasClass('ajax_page_transition'))
                return;

            self.$body.on('djax_updated', function (e, oldBlock, newBlock, scripts, links, styles) {

                self.updateStyles(links, styles);

                //Replace content
                oldBlock.after(newBlock);
                oldBlock.remove();

                //update scripts ( wait a bit to add new content completly)
                setTimeout(function () {
                    self.updateScripts(scripts);
                }, 300);
            });

            self.$body.on('djax_before_transition', function () {
                setTimeout(function () {
                    self.scroll_to('top', 3, 100);
                }, 500);

                self.preloader_show();
            });

        },

        scriptUpdateActions: function () {
            var self = this;
            if(!self.$body.hasClass('ajax_page_transition'))
                return;

            //Put scripts of current page to assets list,it will be updated with djax
            $('script').each(function () {
                if ($(this).attr("src") !== undefined)
                    self.assets.script.push($(this));
            });

            self.$body.on('scripts_updated', function () {
                self.reInitScripts();
                self.preloader_hide();
            });

        },

        reInitScripts: function () {
            var self = this;
            self.snap_to_scroll();

            self.fix_IOS_double_tap_issue();

            self.lazyLoadOnHover();

            self.lazyLoadOnLoad('#main-content');

            self.homeHeight();

            //Initialise the VC post grid
            self.vcGridReInit();

            self.epico_singlePage();

            self.portfolioSlider();//portfolio Feature Image Slider

            //shortcodes
            self.epico_shortcode(false);

            //portfolio & portfolio details Functions
            //id 0 for First Load
            self.portfolioIsotope(0);
            self.portfolioDHashChange();//Portfolio Detail Run When Hash Change functions
            self.portfolioLoadMore();//portfolio Load more Function
            self.pDNavigationNext();// linking to Next Portfolio Detail 
            self.pDNavigationPrevious(); // linking to Previous Portfolio Detail 
            self.Social_link(); // social links in Portfolio Detail 
            self.pDCloseProject(); // close Portfolio Detail
            self.pDInitialize();
            self.portfolio_next_prev();
            self.portfolioDetailNavigationLoading();

            self.minPageHeightSet();

            //blog Functions 
            self.blogLoadMore();//Blog Load More Function
            self.blogToggle();//Blog Toggle
            self.blogPostSlider(); // blog Single Slider

            self.epico_blogMasonry();

            self.parallaxImg();//section parallax
            self.interactiveBackgroundImg(); // Section Interactive background

            self.initVideoBackground();
            self.videoBackgroundSize();
            self.embed_video_lightgallery();
            self.product_gallery_popup_lightGallery();

            self.fitVideo();//video Fit To All Screen
            self.commentRespond();

            // WPML MENU
            self.wpml_menu();

            self.epico_carousel();
            self.shortcodeAnimation();

            //woocomerce
			self.wc_notices();
            self.product_thumbnails();
            self.runIsotopeInProducts();
            self.woocommerce_filter();
            self.single_product2_slider();
            self.products_infoOnclick();
            self.single_product2_scrollbar();
            self.woocommerce_cats();
            self.reInitVariation($('#main-content'));
            self.update_widget_cart_on_cart_page();
            self.product_tabs();
            self.product_variation();
            self.product_image_zoom();
            self.product_hover();
            self.product_quickview();
            self.wishlist_widget_update();
            self.card_widget_update();
            self.disable_price_slider_keydown_event();
            self.price_slider_filter();
            self.sync_fixed_add_to_cart();
            self.fixed_add_to_cart_functionality();
            self.fixed_add_to_cart_visibility();
            self.product_next_prev_button();
            self.wishlist_remove();
            self.woocommerce_ajax_wrapper();
            self.search_box_toggle();// product Search box 
            self.compare();
            self.initSelectElements();
            self.woocommerce_variation_attributes();
            self.woocommerce_variation_attributes_trigger();
            self.product_size_guide();
            self.cat_widget_update();
            self.cat_widget();
            self.show_more_tag();// show more tag button for tag widget

            // Detect scrollbar width
            self.getScrollBarWidth();

            if ($('#fullScreenSlider').length) {
                self.fullScreenSliderInit();
                self.fullScreenSlider();
            }

            if ($('#fullScreenImage').length) {
                self.fullScreenImageInit();
            }

            self.nav();
            self.UpdateCurrentMenuAncestorClass();

            self.togglesidebar_scrollbar(); //toggle sidebar Scrool bar
            self.addToCart();    // open toggle sidebar when click On Add to cart Buttons

            //vertical menu - covering level
            self.coveringLevelVerticalMenu();

            //wpadminbar And topbar
            self.epico_topbar();
			
			//Topbar language
			self.epico_topbar_lang();

            //User additional script
            self.epico_additionalScript();

            //social share's pop up 
            self.social_share_pop_up();

            //remove footer in creative portfolio detail
            self.remove_footer_creative_portfolio_detail();

            //remove left/right menu in creative portfolio detail
            self.remove_left_right_menu_creative_portfolio_detail();

            // gallery
            self.galleryStart();

            // carosel gallery 
            self.carousel_gallery();

            // instagram animation
            self.instagramAnimation();

            //portfolio detail title
            self.portfolio_detail_title();

            if (self.enableScrollId == true) {
                if (!$('#portfoliSingle').length) {
                    self.scroll_to(self.$scrolId, 1); //scroll to inside id  in Front Page
                }
            }

            //contact form 7
            self.contactform7();

            //update wp-toolbar edit link
            self.updateToolbarEditLink();

            if ($('#googleMap').length)
                self.googleMap(); // Footer Google Map

            //woocommerce
            self.product_detail_height();

            // top button 
            self.scrollToTopButton();

            //Header transform
            self.headerTransformation();

            self.disable_djax_on_cart_page();

            self.reinit_checkout_countery_select2();

        },

        updateScripts: function ($scripts) {
            var self = this;

            //remove old plain scripts
            $('body script').each(function () {
                if ($(this).attr("src") === undefined)
                    $(this).remove();
            })

            //remove needless scripts for newly requested page
            var removedIndex = [];
            for (var i = 0; i < self.assets.script.length; i++) {
                var $elem = $(self.assets.script[i]);
                if ($elem.attr("src") !== undefined) {

                    if ($scripts.filter('script[src="' + $elem.attr("src") + '"]').length <= 0) {
                        removedIndex.push(i);
                        self.$body.find('script[src="' + $elem.attr("src") + '"]').remove();
                    }
                    else {
                        $scripts = $scripts.not('script[src="' + $elem.attr("src") + '"]');
                    }

                }

            }

            //use reverese order, becouse removing item from last does not change real indexes of array
            for (var i = removedIndex.length -1; i >= 0; i--) {
                self.assets.script.splice(removedIndex[i], 1);
            }

            //Adding scripts of newly requested page
            var custom_js_script = document.body.querySelectorAll('script[src*="custom.js"], script[src*="custom.min.js"]')[0];

            $scripts.each(function (index, val) {
                if ($(this).attr("src") === undefined) {
                    var ele = document.createElement('script'),
						type = $(this).attr("type");

					if(type === undefined) {
						type = "text/javascript";
					}
                    ele.setAttribute("type", type);
                    if ($(this).attr("id") !== undefined) {
                        ele.setAttribute("id", $(this).attr("id"));
                    }
                    ele.text = $(this).html();
                    var first = document.body.getElementsByTagName('script')[0];
                    first.parentNode.insertBefore(ele, first);

                    $scripts = $scripts.not($(this));
                }
            });

            //Adding new scripts recursively to prevent error on dependent scripts
            var recursiveAddingScript = function (scriptsList) {

                if (scriptsList.length > 0) {
                    var $newscript = scriptsList.eq(0);

                    //add it
                    var ele = document.createElement('script');
                    ele.setAttribute("type", "text/javascript");
                    ele.setAttribute("src", $newscript.attr("src"));
                    ele.onload = function () {
                        //remove it from list and call this function on next script
                        scriptsList = scriptsList.not($newscript);
                        recursiveAddingScript(scriptsList);
                    }
                    custom_js_script.parentNode.insertBefore(ele, custom_js_script);
                    self.assets.script.push(ele);
                }
                else {
                    self.$body.trigger('scripts_updated');
                }
            }
            recursiveAddingScript($scripts);

        },


        updateStyles: function ($links, $styles) {
            var self = this;
            //remove old style
            $('head style').each(function () {
                //Check whether style is google map style( Do not remove google map styles becouse it's added by lazy loading)
                if ($(this).html().indexOf('gm-style') === -1) {
                    $(this).remove();
                }
            });

            //remove needless styles for newly requested page
            var removedIndex = [];
            for (var i = 0; i < self.assets.css.length; i++) {
                var $elem = $(self.assets.css[i]);

                if ($links.filter('link[href="' + $elem.attr("href") + '"]').length <= 0) {
                    removedIndex.push(i)
                    $('head').find('link[href="' + $elem.attr("href") + '"]').remove();
                }

            }
            for (var i = 0; i < removedIndex.length; i++) {
                self.assets.css.splice(removedIndex[i], 1);
            }

            $links.filter('link').each(function (index, val) {

                if ($('head').find('link[href="' + $(this).attr("href") + '"]').length <= 0) {

                    var ele = document.createElement('link');
                    ele.setAttribute("type", "text/css");
                    ele.setAttribute("rel", "stylesheet");
                    ele.setAttribute("media", $(this).attr('media'))
                    ele.setAttribute("href", $(this).attr("href"));
                    document.head.appendChild(ele);
                    self.assets.css.push(ele);

                }
            });

            //Adding styles of newly requested page
            if (typeof $styles != 'undefined') {
                $styles.each(function (index, val) {
                    document.head.appendChild(this);
                });
            }
        },


        djaxifyRequests: function () {
            var self = this;

            if(!self.$body.hasClass('ajax_page_transition'))
            {
                self.djax = undefined;
                self.assets = undefined;
                return;
            }

            //active djax
            self.djax = self.$body.djax(
                '.main-content', // trackable container
                ['wp-login', '/wp-content/', 'admin', 'resources', 'remove_from_wishlist=', 'add_to_wishlist', '#', 'wpml', '?locaklink', '?post_type=product&add-to-cart=', 'add-to-cart=', '#wpcf7-f4-o1'], // list of exception url fragments
                no_ajax_objects.no_ajax_pages); 

            self.$document.on('djaxClick',function(){
                self.abortImageLoading();
            });
        },

        reInitDjax: function () { // run Djax 
            var self = this;
            if(!self.$body.hasClass('ajax_page_transition'))
                return;

            self.djax.reInit();
        },

        /*-----------------------------------------------------------------------------------*/
        /*  Ajaxify Search
        /*-----------------------------------------------------------------------------------*/
        ajaxify_search: function () {
            var self = this;

            if(!self.$body.hasClass('ajax_page_transition'))
                return;

            //Woocommerce search
            self.$document.find("div.search-form form").submit(function (e) {
                var $this = $(this);
                //e.preventDefault(); //search form not clickable when "Ajax page transition" is enabled.

                var keyword = $this.find('input[name="s"]').val();
                if (keyword != '') {
                    var url = $this.attr('action');
                    $(this).siblings('a').attr('href', url + '?s=' + keyword).trigger('click');

                }
            });
        },

        /*----------------------------------------------------------------------------------*/
        /*   Wordpress toolbar edit link update
        /*-----------------------------------------------------------------------------------*/
        updateToolbarEditLink: function (content) {
            var self = this;

            if ($("#wp-admin-bar-edit").length > 0) {
                // set up edit link when wp toolbar is enabled
                var page_id = self.$body.data('pageid');
                var old_link = $('#wp-admin-bar-edit a').attr("href");
                var new_link = old_link.replace(/(post=).*?(&)/, '$1' + page_id + '$2');
                $('#wp-admin-bar-edit a').attr("href", new_link);
            }
        },

        /*----------------------------------------------------------------------------------*/
        /*   preloader
        /*-----------------------------------------------------------------------------------*/

        preloader_show: function () {

            if ($("#preloader").length > 0) {
                if ($("#preloader").hasClass('firstload')) {
                    $("#preloader").removeClass('firstload');
                }

                $("#preloader").css({ 'display': 'block' });
                setTimeout(function () {
                    $("#preloader").removeClass('hide-preloader creative').addClass('simple');
                }, 10);

            }
            else //FadeOut the content smoothly when there is no preloader
            {
                $('.main-content').removeClass('show');
            }

        },

        preloader_hide: function () {
            var self = this;

            /* no-page-transition class prevent from running animation of page transition at first time */
            self.$body.removeClass('no-page-transition');
            $('.main-content').addClass('show');
            // fixing issue : fade up animation in monitor with high height cause 
            $('body.no-preloader.fade-up .footer-bottom').addClass('fadeIssueFixed');
            $("#preloader").addClass('hide-preloader');
            setTimeout(function () {
                $("#preloader").css({ 'display': 'none' });
            }, 510);

        },


        /*----------------------------------------------------------------------------------*/
        /*   wpadminbar And Topbar
        /*-----------------------------------------------------------------------------------*/


        epico_topbar: function () {
            var self = this;

            if ($("#wpadminbar").length != 0 && self.windowWidth > 1140) {

                if ($("#topbar").length != 0) {

                    if (!(self.$epHeader.hasClass('closedtopbar'))) {
                        // topbar Enable With wpadminbar 
                        self.$epHeader.add(".navigation-mobile").add("#homeHeight").addClass('menuSpaceWpNoti');
                        $("#topbar").addClass('topbarSpaceWp');
                    }

                } else {

                    // topbar disable With wpadminbar 
                    self.$epHeader.add("#homeHeight").addClass('menuSpaceWp');

                }

            } else if ($("#topbar").length != 0 && !$("#topbar").hasClass('closed-topbar')) {

                // topbar Enable
                self.$epHeader.add("#homeHeight").addClass('menuSpaceNoti');

            }

            if ($("#wpadminbar").length != 0 && $(".vertical_menu_enabled").length != 0) {

                $('#home .homeWrap .fullScreenImage').css({
                    'margin-top': "32px",
                });

            }

        },
		
		
        /*----------------------------------------------------------------------------------*/
        /*   Topbar language
        /*-----------------------------------------------------------------------------------*/
		
		epico_topbar_lang: function () {			
			var href = window.location.href;
			var elements = $('.lang_link > li > a[href="' + href + '"]'); 
			var $lang_active = elements.parent("li").addClass('active');
			if( $lang_active.length){
				$('#language1').prependTo(".lang_link");
				$lang_active.prependTo(".lang-sel");
			}
		},

        /*----------------------------------------------------------------------------------*/
        /*   User additional script
        /*-----------------------------------------------------------------------------------*/

        epico_additionalScript: function () {
            //Run extra scripts here
            if ('' != epico_theme_vars.additionaljs)
                eval(epico_theme_vars.additionaljs);
        },

        /*----------------------------------------------------------------------------------*/
        /*   Scrolling
        /*-----------------------------------------------------------------------------------*/

        epico_scrolling: function () {
            var self = this;
            $(".navigation li a , #mobile-menu-items li a , .vertical_menu_navigation li a").click(function (e) {
                var $this = $(this),
                    hashAttr = $this.attr('data-hash');

                $(".navigation li , #mobile-menu-items li , .vertical_menu_navigation li").removeClass('active current_page_item');


                if (typeof hashAttr === typeof undefined || hashAttr === false) {

                    //add active class for first and secoud menu 
                    var href = $this.attr('href');
                    var elements = $('a[href="' + href + '"]');  

                    elements.parent().addClass('active');

                }
                else {
                    $('.navigation li a[data-hash="' + hashAttr + '"]').add('#mobile-menu-items li a[data-hash="' + hashAttr + '"]').add('.vertical_menu_navigation li a[data-hash="' + hashAttr + '"]').parent().addClass('active');//Active item in all menus(Specially for epico-menu)
                }

                if (!$this.hasClass('locallink')) {
                    self.externalClicked = true;
                }

                if ($this.hasClass('externalLink')) {

                    self.$scrolId = '#' + hashAttr; // section id that scroll into From External Page in internal page

                } else if ($this.hasClass('locallink')) {

                    e.preventDefault();
                    self.scrolingToSection = true;
                    self.scroll_to('#' + hashAttr, 4); //scroll to inside id  in Front Page
                }

                if ($this.hasClass('externalLink')) {
                    self.enableScrollId = true;
                } else {
                    self.enableScrollId = false;
                }

            });

            $("header a.locallink.logo , header.type2_3 .locallink.logo a  ,aside .locallink.logo,header .locallink.home , aside .locallink.home").click(function (e) {
                var $this = $(this),
                    scroll = $this.attr('href');

                e.preventDefault();
                scroll = scroll.substring(scroll.indexOf("#"), scroll.length);
                self.scroll_to(scroll, 2);  //scroll to top of page
            });

            var pathname = window.location.href;

            if (!window.location.origin) { // Internet Explorer Origion
                window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port : '');
            }

            var $originpathname = window.location.origin + window.location.pathname;

            if (pathname.search("#") > 0) {
                pathname = pathname.substring(pathname.indexOf("#"), pathname.length);
                if ($originpathname !== pathname && pathname !== '#home') {
                    if ($(".page-template-main-page-php").length) {
                        self.scroll_to(pathname, 1);
                    }
                }
            }
        },

        //Initial list of menu items
        initialMenuArray: function () {
            var self = this;

            var aChildren = self.$epHeader.find(".navigation li a").add(".vertical_menu_area .vertical_menu_navigation ul a"); // find the a children of the list items

            for (var i = 0; i < aChildren.length; i++) {

                var aChild = aChildren[i];
                if ($(aChild).hasClass('locallink')) {

                    var ahref = $(aChild).attr("data-hash");
                    if (self.menuArray.indexOf(ahref) == -1)
                        self.menuArray.push(ahref);

                }
            } // above loop fills the menuArray with attribute href values
        },

        /* add active class for menu when page Scrolling */
        updateMenuOnActiveSection: function () {
            var self = this;

            //Exit if this page is not main page
            if (!self.$body.hasClass('home') || !self.$body.hasClass('page-template-main-page-php'))
                return;

            //check navigating flag to know it is on scrolling to a section or not ( by click from user)
            if (self.scrolingToSection == false && self.externalClicked == false) {

                var $window_y = self.$window.scrollTop(); // get the offset of the window from the top of page
                for (var i = 0; i < self.menuArray.length; i++) {
                    var theID = "#" + self.menuArray[i];
                    var theHash = self.menuArray[i];

                    if ($(theID).length) {

                        var divPos = $(theID).offset().top; // get the offset of the div from the top of page
                        var divHeight = $(theID).height(); // get the height of the div in question
                        var menusize = 87;

                        if ($(".vertical_menu_area").length)
                            menusize = 0;

                        if ($("#wpadminbar").length != 0) { // wpadminbar 
                            menusize = menusize + 36;
                        }

                        //Set divPos to 0 becouse #home section is parallax and doesn not exist from page normally
                        if (theID == '#home') {
                            divPos = 0;
                        }

                        if (self.$body.hasClass('home')) {
                            self.$epHeader.find(".navigation li.current_page_item").removeClass("current_page_item");
                            $("aside.vertical_menu_area .vertical_menu_navigation ul li.current_page_item").removeClass("current_page_item");
                        }

                        if ($window_y >= (divPos - menusize) && $window_y < (divPos + divHeight - menusize)) {
                            self.$epHeader.find(".navigation a[data-hash='" + theHash + "']").parent().addClass("active");
                            $("aside.vertical_menu_area nav.vertical_menu_navigation li a[data-hash='" + theHash + "']").parent().addClass("active");
                        } else {
                            self.$epHeader.find(".navigation a[data-hash='" + theHash + "']").parent().removeClass("active");
                            $("aside.vertical_menu_area nav.vertical_menu_navigation li a[data-hash='" + theHash + "']").parent().removeClass("active");
                        }
                    }
                }
            }
        },

        /*----------------------------------------------------------------------------------*/
        /*   Singlepages initialize
        /*-----------------------------------------------------------------------------------*/

        epico_singlePage: function () {
            var self = this,
                $portfolio_detail = $('#portfoliSingle');

            if ($portfolio_detail.length) {

                self.pDSwiper();// portfolio Detail swiper Slider

                if ($portfolio_detail.hasClass('portfolio_detail_creative')) {

                    self.epico_scrollbar('#portfoliSingle .pd_creative_fixed_content .desc');

                    // set margin for Creative portfolio detail
                    self.set_margin_creative_portfolio_detail();

                    $portfolio_detail.waitForImages(function () {

                        $portfolio_detail.addClass('show');

                    });
                }

            } else if ($('#blogSingle').length || $('.cblog').length) {
                self.blogPostSlider(); // Blog Post slider
                self.socailshare(); // socail share
            }
        },

        /*----------------------------------------------------------------------------------*/
        /*   Search form
        /*-----------------------------------------------------------------------------------*/

        search_form: function () {
            var self = this,
                $searchButton = $('.search-button');

            if ($searchButton.length <= 0)
                return;

            var $searchForm = $('#search-form');
            var $searchInput = $searchForm.find('input[type="text"]');

            self.$document.on('click', function (e) {
                if (!$(e.target).is($searchInput)) {
                    if ($(e.target).is($searchButton)) {
                        $searchForm.toggleClass('showing');
                        setTimeout(function () {
                            $searchInput.focus();
                            var tmpStr = $searchInput.val();
                            $searchInput.val('');
                            $searchInput.val(tmpStr);
                        }, 100);
                    }
                    else {
                        $searchForm.removeClass('showing');
                    }

                }

            });
        },

        /*----------------------------------------------------------------------------------*/
        /*  Contact form 7 - Restyles
        /*-----------------------------------------------------------------------------------*/

        contactform7: function () {

            //contact form 7 
            if ($('.wpcf7').length) {

                $(".wpcf7 input").focus(function () {
                    $(this).parent('.wpcf7-form-control-wrap').siblings('.label').addClass('inputfocus');
                    $(this).parent('.wpcf7-form-control-wrap').siblings('.graylabel').addClass('inputfocus');
                });


                $(".wpcf7 input").focusout(function () {
                    $(this).parent('.wpcf7-form-control-wrap').siblings('.label').removeClass('inputfocus');
                    $(this).parent('.wpcf7-form-control-wrap').siblings('.graylabel').removeClass('inputfocus');
                });



                $(".wpcf7 textarea").focus(function () {
                    $(this).parent('.wpcf7-form-control-wrap').siblings('.label').addClass('inputfocus');
                    $(this).parent('.wpcf7-form-control-wrap').siblings('.graylabel').addClass('inputfocus');
                });


                $(".wpcf7 textarea").focusout(function () {
                    $(this).parent('.wpcf7-form-control-wrap').siblings('.label').removeClass('inputfocus');
                    $(this).parent('.wpcf7-form-control-wrap').siblings('.graylabel').removeClass('inputfocus');
                });


            }


            // blog detail
            if ($('.comment-respond').length) {

                $(".comment-respond input").focus(function () {
                    $(this).siblings('.label').addClass('inputfocus');
                    $(this).siblings('.graylabel').addClass('inputfocus');
                });


                $(".comment-respond input").focusout(function () {
                    $(this).siblings('.label').removeClass('inputfocus');
                    $(this).siblings('.graylabel').removeClass('inputfocus');
                });

                $(".comment-respond textarea").focus(function () {
                    $(this).siblings('.label').addClass('inputfocus');
                    $(this).siblings('.graylabel').addClass('inputfocus');
                });


                $(".comment-respond textarea").focusout(function () {
                    $(this).siblings('.label').removeClass('inputfocus');
                    $(this).siblings('.graylabel').removeClass('inputfocus');
                });

            }

            // product detail
            if ($('#review_form').length) {


                $("#review_form input").focus(function () {
                    $(this).siblings('.label').addClass('inputfocus');
                    $(this).siblings('.graylabel').addClass('inputfocus');
                });


                $("#review_form input").focusout(function () {
                    $(this).siblings('.label').removeClass('inputfocus');
                    $(this).siblings('.graylabel').removeClass('inputfocus');
                });

                $("#review_form textarea").focus(function () {
                    $(this).siblings('.label').addClass('inputfocus');
                    $(this).siblings('.graylabel').addClass('inputfocus');
                });


                $("#review_form textarea").focusout(function () {
                    $(this).siblings('.label').removeClass('inputfocus');
                    $(this).siblings('.graylabel').removeClass('inputfocus');
                });

            }

        },


        /*----------------------------------------------------------------------------------*/
        /*  Home parallax
        /*-----------------------------------------------------------------------------------*/

        sliderParallax: function () {

            var self = this;

            if ($('.sliderParallax').length <= 0 || self.msie > 0 || !!self.msie11)
                return;

            if (self.windowWidth > 1140 && !self.isTouchDevice && $("#home").length > 0) {

                self.windowHeight = self.$window.height();
                var $slider = $('#home .slider-wrap'),
                    $start = $slider.find('#caption-start'),
                    latestKnownScrollPosition = 0,
                    tick = false;

                var update_slider_position = function () {

                    //When element is out of viewport
                    if (self.windowHeight < latestKnownScrollPosition) {
                        tick = false;
                        return;
                    }

                    $slider.css({
                        'transform': 'matrix(1, 0, 0, 1, 0, ' + latestKnownScrollPosition * 0.6 + ')'
                    });

                    tick = false;
                }
                update_slider_position();
                var requestTick = function () {
                    if (tick == false) {

                        window.requestAnimationFrame(update_slider_position);
                    }
                    tick = true;
                }

                var onScroll = function () {
                    latestKnownScrollPosition = window.scrollY;
                    requestTick();
                }

                self.$window.on('scroll', onScroll);

                self.$window.one('djaxClick', function () {
                    self.$window.unbind('scroll', onScroll);
                });
            }

        },


        /*----------------------------------------------------------------------------------*/
        /*  Header transform
        /*-----------------------------------------------------------------------------------*/

        headerTransformation: function () {

            var self = this;

            var isFixedMenu = self.$epHeader.data("fixed") === 'fixed-menu';

            if (self.windowWidth <= 1140 || self.documentHeight <= 500 || isFixedMenu || self.$body.hasClass('vertical_menu_enabled'))
                return;

            var latestKnownScrollPosition = 0,
                tick = false,
                changeStateThreshold = self.windowHeight * 0.1;

            var update_menu_state = function () {

                if (changeStateThreshold > latestKnownScrollPosition) {
                    self.$epHeader.removeClass('state2');
                }
                else {
                    self.$epHeader.addClass('state2');
                }

                tick = false;
            }

            update_menu_state();
            var requestTick = function () {
                if (tick == false) {
                    window.requestAnimationFrame(update_menu_state);
                    latestKnownScrollPosition = window.scrollY;
                }
                tick = true;
            }

            self.$window.on('scroll', requestTick);

        },

        /*----------------------------------------------------------------------------------*/
        /* Next/prev button for product
        /*-----------------------------------------------------------------------------------*/

        product_next_prev_button: function () {
            var self = this;

            if (self.$body.hasClass('single-product')) {
                var $original_next_button = $('#main span#next-product'),
                    $original_prev_button = $('#main span#prev-product');
                if ($original_next_button.length > 0) {
                    if ($('.toggleSidebarContainer').siblings('span#next-product').length > 0 || $('.toggleSidebarContainer').siblings('span#prev-product').length > 0) {
                        if ($original_next_button.find('a').length > 0) {
                            $('.toggleSidebarContainer').siblings('span#next-product').addClass('visible').empty('').html($original_next_button.html());
                            self.reInitDjax();
                        }
                        else {
                            $('.toggleSidebarContainer').siblings('span#next-product').removeClass('visible');

                        }

                        if ($original_prev_button.find('a').length > 0) {
                            $('.toggleSidebarContainer').siblings('span#prev-product').addClass('visible').empty('').html($original_prev_button.html());
                            self.reInitDjax();
                        }
                        else {
                            $('.toggleSidebarContainer').siblings('span#prev-product').removeClass('visible');
                        }
                    }
                    else {
                        $('.toggleSidebarContainer').after($original_next_button).after($original_prev_button);
                        setTimeout(function () {
                            $original_next_button.addClass('visible');
                            $original_prev_button.addClass('visible');
                        }, 200);
                    }
                }
            }
            else {
                $('span#next-product').removeClass('visible');
                $('span#prev-product').removeClass('visible');
            }
        },

        /*----------------------------------------------------------------------------------*/
        /* Fixed add to cart
        /*-----------------------------------------------------------------------------------*/

        fixed_add_to_cart_functionality: function () {

            var self = this;

            if ($('div.product').length <= 0 || !self.$body.hasClass('fixed-add-to-cart-enable'))
                return;

            if ($('div.product').hasClass('product-type-variable') || $('div.product').hasClass('product-type-grouped')) {
                $('a.single_add_to_cart_button').removeClass('add_to_cart_button').addClass('go-to-add-to-cart'); // change type of add-to-cart button
            }
            else {
                $('a.single_add_to_cart_button').addClass('add_to_cart_button').removeClass('go-to-add-to-cart'); // change type of add-to-cart button
            }

            if ($('div.product').hasClass('product-type-external')) {
                $('.fixed-add-to-cart').addClass('affilate-product');
            }
            else {
                $('.fixed-add-to-cart').removeClass('affilate-product');
            }

            //update add to cart button text
            if ($('.fixed-add-to-cart-container a.single_add_to_cart_button .txt').empty()) {

                var $add_to_cart_button = $('a.single_add_to_cart_button');
                if ($add_to_cart_button.eq(0).find('.txt').html()) {
                    var $add_to_cart_text = $add_to_cart_button.eq(0).find('.txt').html().trim();
                } else { // Add this line code becuse some translate plugin remove span.text and this cuase error 
                    var $add_to_cart_text = $add_to_cart_button.eq(0).html().trim();
                }

                $('.fixed-add-to-cart-container a.single_add_to_cart_button .txt').html($add_to_cart_text).attr('data-hover', $add_to_cart_text);
                $('.fixed-add-to-cart-container a.single_add_to_cart_button').attr('title', $add_to_cart_text);
            }

            $('.fixed-add-to-cart .go-to-add-to-cart').on('click', function (e) {
                e.preventDefault();

                var $top;

                if ($('ul.variations').length > 0) {
                    $top = $('ul.variations').offset().top;
                }
                else {
                    $top = $('.single_add_to_cart_button').eq(0).offset().top;
                }

                $top = $top - 100;

                self.$scrollpals.stop().animate(
                    { scrollTop: $top },
                    1000,
                    'easeOutQuad'
                );

            });

            $('.fixed-add-to-cart .single_add_to_cart_button').not('.go-to-add-to-cart').on('click', function (e) {
                e.preventDefault();
                $('.single_add_to_cart_button')[0].click();
            });

            //wishlist button
            $('.fixed-add-to-cart .yith-wcwl-add-button a').add('.woocommerce div.product div.summary .add_to_wishlist').on('click', function () {
                $('.fixed-add-to-cart .ajax-loading').css('visibility', 'visible');
            });

            $('.fixed-add-to-cart .yith-wcwl-add-button a').on('click', function () {
                $('.woocommerce div.product div.summary .add_to_wishlist').eq(0).trigger('click');
            });

            //compare button
            $('.fixed-add-to-cart a.compare').on('click', function (e) {
                e.preventDefault();
                $('.woocommerce div.product div.summary a.compare').eq(0).trigger('click');
            });


        },

        sync_fixed_add_to_cart: function () {

            var self = this;

            if ($('div.product').length <= 0 || !self.$body.hasClass('fixed-add-to-cart-enable'))
                return;

            //Check wishlist status at page loading
            if ($('.yith-wcwl-add-button').hasClass('show')) {
                $('.fixed-add-to-cart .yith-wcwl-add-button').removeClass('hide').addClass('show');
                $('.fixed-add-to-cart .yith-wcwl-wishlistaddedbrowse').removeClass('show').addClass('hide');
            }
            else {
                $('.fixed-add-to-cart .yith-wcwl-add-button').removeClass('show').addClass('hide');
                $('.fixed-add-to-cart .yith-wcwl-wishlistaddedbrowse').removeClass('hide').addClass('show');
            }


            //listen to event of add to cart button
            $(document.body).on('adding_to_cart', function () {
                self.$document.find('.single_add_to_cart_button').addClass('loading');
            });

            $(document.body).on('added_to_cart', function () {
                $('a.added_to_cart').removeClass('hide');
                $('.single_add_to_cart_button').addClass('added').removeClass('loading');
            });

            //listen to event of add to wishlist
            $(document.body).on('added_to_wishlist', function () {
                $('.fixed-add-to-cart .yith-wcwl-add-button').removeClass('show').addClass('hide');
                $('.fixed-add-to-cart .ajax-loading').css('visibility', 'hidden');
                $('.fixed-add-to-cart .yith-wcwl-wishlistaddedbrowse').removeClass('hide').addClass('show');
            });

            setTimeout(function () {
                // When the variation is revealed
                $("form.variations_form").on('show_variation', function (event, variation, purchasable) {
                    $('.fixed-add-to-cart a.added_to_cart').addClass('hide');
                    $('.fixed-add-to-cart .single_add_to_cart_button').removeClass('added');
                });

            }, 200);


        },

        fixed_add_to_cart_visibility: function () {

            var self = this;

            if ($('div.product').length <= 0 || !self.$body.hasClass('fixed-add-to-cart-enable') || $('div.product').hasClass('outofstock')) {
                $('.fixed-add-to-cart, .scrollToTop').removeClass('visible');
                return;
            }


            var latestKnownScrollPosition = 0,
                tick = false,
                visibility_threshold = 50,
                visibility_range_start = $('.single_add_to_cart_button').eq(0).offset().top,
                visibility_range_end = self.documentHeight - self.windowHeight - visibility_threshold,//50px before footer
                $fixed_add_to_cart = $('.fixed-add-to-cart'),
                $scrollToTop = $(".scrollToTop"),
                isVerticalMenu = self.$body.hasClass('vertical_menu_enabled');

            if ($('ul.variations').length > 0) {
                visibility_range_start = $('ul.variations').offset().top;
            }



            var update_fixedaddtocart_position = function () {

                if (latestKnownScrollPosition >= visibility_range_start && (latestKnownScrollPosition <= visibility_range_end || isVerticalMenu)) {
                    $fixed_add_to_cart.addClass('visible');
                    $scrollToTop.addClass('visible');
                }
                else {
                    $fixed_add_to_cart.removeClass('visible');
                    $scrollToTop.removeClass('visible');
                }

                tick = false;
            }


            update_fixedaddtocart_position();

            var update_range = function () {
                visibility_range_end = self.documentHeight - self.windowHeight - visibility_threshold;
            }

            var do_update_range = function () {
                setTimeout(update_range, 1000); // wait 1s to get correct document height
            }
            do_update_range()// update end of visibility range

            self.$window.on('document-height-changed', update_range);

            var requestTick = function () {
                if (tick == false) {
                    window.requestAnimationFrame(update_fixedaddtocart_position);
                }
                tick = true;
            }

            var onScroll = function () {
                latestKnownScrollPosition = self.$window.scrollTop();
                requestTick();
            }

            self.$window.on('scroll', onScroll);

            self.$window.one('djaxClick', function () {
                self.$window.unbind('scroll', onScroll).unbind('document-height-changed', update_range);
            });

        },

        /*----------------------------------------------------------------------------------*/
        /*  Update woocommerce content by ajax
        /*-----------------------------------------------------------------------------------*/

        woocommerce_filter: function () {
            var self = this;

            $('.shop-filter-toggle').on('click', function () {
                var $this = $(this),
                    $search_keyword = $(".shop-filter .search-keyword");

                $this.toggleClass('open').removeClass('closed');
                $this.siblings('.shop-filter').toggleClass('open');

                if (self.windowWidth < 1025) {
                    if ($this.hasClass('open')) {
                        $('.shopFilterCategoriesBtn').addClass('closed')
                    }
                    else {
                        $('.shopFilterCategoriesBtn').removeClass('closed')
                    }
                }

                // Search keyword 
                if ($this.hasClass('open')) {
                    $search_keyword.addClass('hide');
                    if ($this.siblings('.shop-filter').find(".special-filter.cat").hasClass("opencat")) {
                        $this.siblings('.shop-filter').find(".special-filter.cat").removeClass('opencat');
                        $this.siblings('.shopFilterCategoriesBtn').removeClass('opencat');
                    }

                } else {
                    $search_keyword.removeClass('hide');
                }
            });

            if (self.windowWidth < 1025) {

                $('.shopFilterCategoriesBtn').on('click', function () {
                    var $this = $(this),
                        $search_keyword = $(".shop-filter .search-keyword");

                    $this.toggleClass('opencat').removeClass('closed');
                    $this.siblings('.shop-filter').find(".special-filter.cat").toggleClass('opencat');

                    if ($this.hasClass('opencat')) {
                        $('.shop-filter-toggle').addClass('closed')
                    }
                    else {
                        $('.shop-filter-toggle').removeClass('closed')
                    }

                    // Search keyword 
                    if ($this.hasClass('opencat')) {
                        $search_keyword.addClass('hide');
                        if ($this.siblings('.shop-filter').hasClass("open")) {
                            $this.siblings('.shop-filter').removeClass('open');
                            $this.siblings('.shop-filter-toggle').removeClass('open');
                        }
                    } else {
                        $search_keyword.removeClass('hide');
                    }
                });

                var close_filter_and_cat = function () {
                    $('.shop-filter').removeClass('open');
                    $('.shop-filter-toggle').removeClass('open closed');
                    $(".shop-filter .search-keyword").addClass('hide');
                    $('.shop-filter').find(".special-filter.cat").removeClass('opencat');
                    $('.shopFilterCategoriesBtn').removeClass('opencat closed');
                }

                self.$body.on('wc-content-updating', close_filter_and_cat);
                self.$document.on('click', '.woocommerce .shop-filter .special-filter.cat', close_filter_and_cat);


                if(!$('.woocommerce .shop-filter .sidebar').length)
                {
                    var wc_close_sidebar = function () {
                        $('.woocommerce .wc-sidebar-btn').removeClass('active').siblings('.sidebar').removeClass('show');
                    }

                    self.$body.on('wc-content-updating', wc_close_sidebar);

                    self.$document.on('click', '.woocommerce .wc-sidebar-btn', function () {
                        var $this= $(this);

                        if(!$this.hasClass('active'))
                            $this.siblings('.sidebar').addClass('show');
                        else
                            $this.siblings('.sidebar').removeClass('show');

                        $this.toggleClass('active');
                    });                    
                }


                self.woocommerce_filter_responsive();
            }

        },

       woocommerce_filter_responsive: function () {
            var self = this,
                $shopFilterSidebar = $('.woocommerce .shop-filter .sidebar'),
                $shopSidebar = $('#woocommerce-sidebar .sidebar');

            if (self.windowWidth > 1140 || (!$shopFilterSidebar.length && !$shopSidebar.length))
                return;

            if ($shopFilterSidebar.length)
            {
                $shopSidebar.contents().appendTo($shopFilterSidebar);
                $shopFilterSidebar.prepend($('.woocommerce .shop-filter .special-filter .widget.widget_on_sale_filter'));
                $shopFilterSidebar.prepend($('.woocommerce .shop-filter .special-filter .widget.widget_in_stock_filter'));
            }
            else if ($shopSidebar.length)
            {
                $('#woocommerce-sidebar').contents().appendTo(".woocommerce .shop-filter");
            }       
            
        },


        go_to_top_shop: function () {
           var self = this;
           setTimeout(function () {
               self.scroll_to('.shop_top_padding', 1, 400);
           }, 500)

       },

       /*----------------------------------------------------------------------------------*/
        /*  Update woocommerce content by ajax
        /*-----------------------------------------------------------------------------------*/
        //Disable djax on widget links
        woocommerce_disable_djax_on_widget_links: function () {
            var self = this;

            if(!self.$body.hasClass('ajax_page_transition'))
                return;


            if (self.$body.hasClass('woocommerce'))
                self.$document.find('.widget_layered_nav li a, .widget_layered_nav_filters li a, .widget_product_categories li a, .widget.woocommerce.widget_product_tag_cloud a, .widget.woocommerce.widget_ranged_price_filter a,.on-sale-filter a,.in-stock-filter a, .widget_order_by_filter a,.shop-filter .search-keyword a, nav.woocommerce-pagination li a, .widget_rating_filter li a').addClass('no_djax');
        },

        //Activate tag on page refresh
        woocommerce_active_tag: function () {
            var url = window.location.href;
            $('.widget.woocommerce.widget_product_tag_cloud a[href="' + url + '"]').addClass('current-cat');

        },

        woocommerce_ajax_wrapper: function () {
            var self = this;

            if (!self.$body.hasClass('woocommerce') || self.$body.hasClass('single-product'))
                return;

            var is_active_ajax_request = function () {
                if (xhr && xhr.readyState != 4)
                    return true;

                return false;
            }

            var hide_wrapper_loading = function () {
                $('.wc-ajax-content').css('opacity', 1);
                $('.wc-ajax-wrapper > .wc-loading').addClass('hide');
            }

            var show_wrapper_loading = function () {
                $('.wc-ajax-content').css('opacity', 0);
                $('.wc-ajax-wrapper > .wc-loading').removeClass('hide');
            }

            var display_bottom_filters = function (opacity) {
                var $current_active_filters = $('.widget.widget_layered_nav_filters'),
                    $result_count = $('.woocommerce-result-count');

                $current_active_filters.add($result_count).css('opacity', opacity);
            }

            var update_widgets = function ($response) {

                //Update search form
                var form = $response.find('form.woocommerce-product-search');
                $('form.woocommerce-product-search').attr('data-type', form.attr('data-type')).attr('action', form.attr('action'));


                //Update search keyword
                var $search_keyword = $response.find('.search-keyword a');
                if ($search_keyword.length > 0) {
                    $('.search-keyword').html($search_keyword).removeClass('hide').addClass('show');
                }
                else {
                    $('.search-keyword').removeClass('show');
                }
				
                var $shop_filter = $response.find('.shop-filter'),
					$result_count = $response.find('p.woocommerce-result-count'),
                    $res_ordering = $response.find('.woocommerce-ordering select'),
                    $res_shop_filters = $response.find('.shop-filter .widget-area'),
                    $res_shop_cats = $response.find('#shop-filter-cat'),
                    $res_shop_sidebar = $response.find('#woocommerce-sidebar'),
                    $res_shop_bottom_filters = $response.find('.shop-filter .bottomPartFilter');

                //Wait .2s to complete animations
                setTimeout(function () {
					if($shop_filter.length <= 0 )
					{
						$('.shop-filter, .shop-filter-toggle').addClass('hidden');
					}	
					else
					{
						$('.shop-filter, .shop-filter-toggle').removeClass('hidden');
					}
					
					if($res_shop_cats.length > 0 )
					{
						$('#shop-filter-cat').html($res_shop_cats.html());
					}
					
                    
					$('.shop-filter .widget-area').html($res_shop_filters.html());

                    // Update filters in shop with filter
					if (self.windowWidth < 1025) {
					    $('.woocommerce-sidebar .sidebar.widget-area').html($res_shop_sidebar.html()); // In Responsive devices 
					} else {
                        $('#woocommerce-sidebar').html($res_shop_sidebar.html());
                    }

                    $('.woocommerce-result-count').html($result_count.html());
                    $('.woocommerce-ordering select').html($res_ordering.html());
                    $('.shop-filter .bottomPartFilter').html($res_shop_bottom_filters.html());
					self.cat_widget_update();


                    setTimeout(function () {
                        self.woocommerce_disable_djax_on_widget_links();
                        self.disable_price_slider_keydown_event();
                        self.price_slider_filter();
                        display_bottom_filters(1);
                        self.woocommerce_filter_responsive();
                        self.initSelectElements('update');
                        self.layer_nav_ajax_dropdown();
						self.cat_widget();
                    }, 100);
                }, 200);

            }

            var update_content = function ($response, categoryChanged) {
                var $new_block = $response.find('.wc-ajax-content'),
                    $new_category_header = $response.find('#header'),
                    $new_category_header_style = $new_category_header.prev('style'),
                    $current_category_header = $('#header'),
					$current_category_header_style = $current_category_header.prev('style'),
                    $new_top_space_classes = $response.find('#main-content').attr("class"); // get new top space classes 


                if ($new_block.length > 0) {
                    $('.wc-ajax-content').html($new_block.html());
                }
                else {
                    $new_block = $response.find('.woocommerce-info').addClass('no-match');
                    $('.wc-ajax-content').html($new_block);
                }

                //update category header and top space classes
				if(categoryChanged) {
					if ($new_category_header.length > 0) {
						if ( $current_category_header.length > 0 ) {
							
							$current_category_header_style.replaceWith($new_category_header_style);
							$current_category_header.after($new_category_header.addClass('hide'));
							//wait a bit to add content to DOM completely
							setTimeout(function () {
								$new_category_header.removeClass('hide');
							}, 100);
							//wait a bit to animate completely
							setTimeout(function () {
								$current_category_header.remove();
							}, 400);
							
						}
						else {
							$('#pageHeight').before($new_category_header_style);
							$('#pageHeight').before($new_category_header.addClass('hideCompletly'));
							//wait a bit to add content to DOM completely
							setTimeout(function () {
								$new_category_header.removeClass('hideCompletly');
							}, 200);
						}

					}
					else {
						$current_category_header.addClass('hideCompletly');
						setTimeout(function () {
							$current_category_header.remove();
							$current_category_header_style.remove();
						}, 300);
					}

                    // Replace New top Space classes
					if ($new_top_space_classes.length > 0) {
					    $('#main-content').addClass($new_top_space_classes);
					}

				}

                //wait a bit to add content to DOM completely
                setTimeout(function () {
                    self.runIsotopeInProducts();
                    self.lazyLoadOnLoad('#main-content');
                    self.lazyLoadOnHover();
                    self.product_hover();
                    self.product_quickview();
                    self.reInitDjax();
                    self.products_infoOnclick();
                }, 50)

            }

            //Helper function to get content by url
            //is_search is a flag to show search results
            var get_woocommerce_content = function (pageUrl, categoryChanged) {

                //Prevent from multiple ajax requests
                if (is_active_ajax_request()) {
                    xhr.abort();
                }

                self.abortImageLoading();

                if (pageUrl) {

                    self.$body.trigger('wc-content-updating');

                    if(self.windowWidth <= 1140){
                        self.go_to_top_shop();
                    }
                    
                    show_wrapper_loading();

                    display_bottom_filters(0);

                    // Make sure the URL has a trailing-slash before query args (fix 301 redirect)
                    pageUrl = pageUrl.replace(/\/?(\?|#|$)/, '/$1');

                    //Update history of browser ( for browser next/prev button)
                    window.history.pushState({ 'url': pageUrl, 'title': '' }, '', pageUrl);

                    xhr = $.ajax({
                        url: pageUrl,
                        dataType: 'html',
                        data: { ajax_shop_req: true },
                        cache: false,
                        headers: { 'cache-control': 'no-cache' },
                        method: 'POST',

                        error: function (XMLHttpRequest, textStatus, error) {
                            console.log('AJAX error - ' + error);
                        },
                        success: function (response) {
                            // Update shop content
                            var $response = $(response);
                            update_content($response, categoryChanged);
                            update_widgets($response);

                        },
                        complete: function () {
                            hide_wrapper_loading();
                            self.compare();

                        }
                    });

                }
            }

            self.woocommerce_disable_djax_on_widget_links();
            self.woocommerce_active_tag();

            var xhr;//xmlHttpRequest

            //Woocommerce pagination + woocommerce back-to-shop link
            self.$document.on('click', '.wc-ajax-content:not(.disable_pagination) nav.woocommerce-pagination li a, .back-to-shop', function (e) {
                if (e.handled !== true) // This will prevent event triggering more then once
                {
                    e.handled = true;
                    e.preventDefault();
                    self.go_to_top_shop();
                    get_woocommerce_content($(this).attr('href'),true);
                }
            });


            //Woocommerce Layered nav + Layered nav filter (filter by and active filters widgets) + on sale filter + in stock filter + sorting filter
            self.$document.on('click', '.widget_layered_nav li a, .widget_layered_nav_filters li a,.on-sale-filter a,.in-stock-filter a', function (e) {
                if (e.handled !== true) // This will prevent event triggering more then once
                {
                    e.handled = true;
                    e.preventDefault();

                    if (!is_active_ajax_request()) {
                        $(this).closest('li').addClass('pending');
                    }
                    else {
                        $(this).closest('ul').find('.pending').removeClass('pending').toggleClass('chosen');
                    }

                    $(this).closest('li').toggleClass('chosen');
                    get_woocommerce_content($(this).attr('href'));
                }
            });

            //Woocommerce rating filter
            self.$document.on('click', '.widget_rating_filter li a', function (e) {
                if (e.handled !== true) // This will prevent event triggering more then once
                {

                    e.handled = true;
                    e.preventDefault();
                    if ($(this).closest('ul').find('li.chosen').is($(this).closest('li'))) {
                        $(this).closest('li').toggleClass('chosen');
                    }
                    else {
                        $(this).closest('li').toggleClass('chosen');
                    }

                    get_woocommerce_content($(this).attr('href'));
                }

            });

            //Woocommerce ranged price filter, sorting widget
            self.$document.on('click', '.widget_ranged_price_filter li a,.widget_order_by_filter li a', function (e) {
                if (e.handled !== true) // This will prevent event triggering more then once
                {

                    e.handled = true;
                    e.preventDefault();
                    $(this).closest('ul').find('li.current').removeClass('current');
                    $(this).closest('li').toggleClass('current');
                    get_woocommerce_content($(this).attr('href'));
                }

            });

            //Woocommerce tag cloud widget
            self.$document.on('click', '.widget.woocommerce.widget_product_tag_cloud a', function (e) {
                if (e.handled !== true) // This will prevent event triggering more then once
                {

                    e.handled = true;
                    e.preventDefault();
                    $(this).closest('.tagcloud').find('.current-tag').removeClass('current-tag');
                    $(this).addClass('current-tag');
                    get_woocommerce_content($(this).attr('href'));
                }

            });

            //Woocommerce categories              
            self.$document.on('click', '.widget_product_categories li a', function (e) {

                if ($(this).parents('.shop_is_categories_style').length) { // When shop page is set to show just categories or subcategories - filter ajax not work
                    return;
                }

                if (e.handled !== true) // This will prevent event triggering more then once
                {

                    e.handled = true;
                    e.preventDefault();
                    $(this).closest('.widget_product_categories').find('.current-cat').removeClass('current-cat');
                    $(this).closest('li').addClass('current-cat');
                    get_woocommerce_content($(this).attr('href'), true);
                }

            });

            //Woocommerce categories
            self.$document.on('select2-open', '.widget_product_categories .dropdown_product_cat:not(.change_event_removed)', function (e) {
                $(this).addClass('change_event_removed');
                $('.dropdown_product_cat').unbind('change');
            });
            self.$document.on('change', '.widget_product_categories select', function (e) {
                if (e.handled !== true) // This will prevent event triggering more then once
                {

                    e.handled = true;
                    var selected = $(this).find('option:selected').val(),
                        url,
                        home_url = epico_theme_vars.home_url;

                    if (home_url.indexOf('?') > 0) {
                        url = home_url + '&product_cat=' + selected;
                    } else {
                        url = home_url + '?product_cat=' + selected;
                    }

                    get_woocommerce_content(url, true);
                }

            });

            //Woocommerce price filter
            self.$document.on('click', '.widget_price_filter button.button', function (e) {
                if (e.handled !== true) // This will prevent event triggering more then once
                {

                    e.handled = true;
                    e.preventDefault();

                    var url = window.location.href,
                        min_price = $(this).siblings('#min_price').val(),
                        max_price = $(this).siblings('#max_price').val();

                    //Update/add min_price and max_price
                    url = self.updateQueryStringParameter(url, 'min_price', min_price);
                    url = self.updateQueryStringParameter(url, 'max_price', max_price);

                    get_woocommerce_content(url);
                }

            });

            //Woocommerce sort
            //Unbind previous function and bind new function to orderby select
            $('.woocommerce-ordering').off('change', 'select.orderby');
            $('.woocommerce-ordering').on('change', 'select.orderby', function () {

                var selected = $(this).find('option:selected').val();
                var url = window.location.href;

                //Update/add orderby
                url = self.updateQueryStringParameter(url, 'orderby', selected);

                get_woocommerce_content(url);

            });

            //Woocommerce search
            self.$document.find("form.woocommerce-product-search").each(function () {
                var $this = $(this);
                $this.submit(function (e) {
                    e.preventDefault();
                    var keyword = $this.find('input.search-field').val();
                    if (keyword != '') {
                        $this.find('input.search-field').blur();//Hide virtual keyboard in mobiles

                        var url = $this.attr('action');
                        //Update/add orderby
                        url = self.updateQueryStringParameter(url, 's', keyword);
                        //Do not add post_type to URL if this form is in category page
                        if ($this.attr('data-type') != 'category') {
                            url = self.updateQueryStringParameter(url, 'post_type', 'product');
                        }
                        get_woocommerce_content(url);
                    }
                });
            });

            // remove search keyword and Update Product 
            self.$document.on("click", ".shop-filter .search-keyword a", function (e) {
                if (e.handled !== true) // This will prevent event triggering more then once
                {

                    e.handled = true;
                    e.preventDefault();
                    $(this).closest('.search-keyword').toggleClass('show');
                    $('#special_layered_nav_filters').find('.search-keyword-active').removeClass('chosen');
                    get_woocommerce_content($(this).attr('href'));
                }
            });

            // Search Hint [ Press "Enter" to search ]
            self.$document.on("keyup", ".shop-filter .filter-search-form-container input[type='search']", function (e) {
                var len = $(this).val().length,
                    $search_hint = $(".shop-filter .search-hint");
                if (len >= 2) {
                    $search_hint.removeClass('hide')
                } else {
                    $search_hint.addClass('hide')
                }
            }).on("keydown", ".shop-filter .filter-search-form-container input[type='search']", function (e) {
                if (e.which === 13 && $(this).val() != '') {
                    setTimeout(function () {
                        $(".shop-filter .search-hint").addClass('hide');
                        $(".shop-filter .search-keyword").removeClass('show');
                    }, 300)
                }
            });
        },

        search_box_toggle: function () {
            var self = this;

            $(".shop-filter .search-box").on("click", function (e) {
                $(this).toggleClass('open');
                $(this).siblings('.filter-search-form-container').toggleClass('open');
                $(this).siblings('.special-filter.cat').toggleClass('hide');

                //only run on tablet and mobiles 
                if (self.windowWidth < 1025 && $('.shop-filter-toggle').hasClass('open')) {
                    $(this).parent('.shop-filter').toggleClass('open');
                }

                if (!$('.shop-filter .search-box').hasClass('open')) {
                    $(".shop-filter .search-hint").addClass('hide');
                }
            });
        },

        //a helper function to add/update querystrings of URL
        updateQueryStringParameter: function (uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            }
            else {
                return uri + separator + key + "=" + value;
            }
        },

        /*----------------------------------------------------------------------------------*/
        /*  Show minicart | Show cart on sidebar when go to page with ajax | Code : cart-fragments.js in woocommerce plugin
        /*-----------------------------------------------------------------------------------*/
        update_widget_cart_on_cart_page: function () {
            var self = this;
            //Use ajaxSend event to detect when it's needed to update cart
            var ajaxStartHandlerForCartPage = function (event, xhr, settings) {
                var main_form_action = $('form.woocommerce-cart-form').attr('action');
                if (settings.url.indexOf('get_cart_totals') > 0) {
                    $(document.body).trigger('wc_fragment_refresh');
                }
            }

            if (self.$body.hasClass('woocommerce-cart')) // just do it in cart page
            {
                $(document).ajaxSend(ajaxStartHandlerForCartPage);
            }
        },

        /*----------------------------------------------------------------------------------*/
        /* Reinitialise select2 on countery select in checkout page
        /*-----------------------------------------------------------------------------------*/
        reinit_checkout_countery_select2: function () {
            var self = this;

            if ($('.woocommerce-checkout select.country_to_state.country_select').length > 1) {

                //It's an copy of getEnhancedSelectFormatString function in woocommerce/assets/js/frontend/country-select.js
                var getEnhancedSelectFormatString = function () {
                    return {
                        'language': {
                            errorLoading: function() {
                                // Workaround for https://github.com/select2/select2/issues/4355 instead of i18n_ajax_error.
                                return wc_country_select_params.i18n_searching;
                            },
                            inputTooLong: function( args ) {
                                var overChars = args.input.length - args.maximum;

                                if ( 1 === overChars ) {
                                    return wc_country_select_params.i18n_input_too_long_1;
                                }

                                return wc_country_select_params.i18n_input_too_long_n.replace( '%qty%', overChars );
                            },
                            inputTooShort: function( args ) {
                                var remainingChars = args.minimum - args.input.length;

                                if ( 1 === remainingChars ) {
                                    return wc_country_select_params.i18n_input_too_short_1;
                                }

                                return wc_country_select_params.i18n_input_too_short_n.replace( '%qty%', remainingChars );
                            },
                            loadingMore: function() {
                                return wc_country_select_params.i18n_load_more;
                            },
                            maximumSelected: function( args ) {
                                if ( args.maximum === 1 ) {
                                    return wc_country_select_params.i18n_selection_too_long_1;
                                }

                                return wc_country_select_params.i18n_selection_too_long_n.replace( '%qty%', args.maximum );
                            },
                            noResults: function() {
                                return wc_country_select_params.i18n_no_matches;
                            },
                            searching: function() {
                                return wc_country_select_params.i18n_searching;
                            }
                        }
                    };
                }

                var init_select2_for_wc_selects = function () {

                    setTimeout(function () {

                        if ($.isFunction($.fn.select2)) {

                            var wc_country_select_select2 = function () {
                                $('select.country_select:visible, select.state_select:visible').each(function () {
                                    var select2_args = $.extend({
                                        placeholderOption: 'first',
                                        width: '100%'
                                    }, getEnhancedSelectFormatString());

                                    $(this).select2(select2_args);
                                });
                            };

                            wc_country_select_select2();
                        }

                    }, 1000);
                }

                self.$body.bind('country_to_state_changed', function () {
                    init_select2_for_wc_selects();
                });

                init_select2_for_wc_selects();
            }
        },

        /*----------------------------------------------------------------------------------*/
        /*  top Button
        /*-----------------------------------------------------------------------------------*/

        scrollToTopButton: function () {
            var self = this;

            var $scrollToTop = $(".scrollToTop");

            if ($scrollToTop.length <= 0 || self.windowWidth <= 1140)
                return;

            var latestKnownScrollPosition = 0,
                tick = false,
                visibilityThreshold = self.windowHeight * 1.7;

            //use unbind to prevent from multiple times clikc event
            $scrollToTop.find("a").unbind().click(function (e) {
                e.preventDefault();
                self.scroll_to("top", 3);  //scroll to top of page 
            });

            var update_button_visibility = function () {

                if (visibilityThreshold > latestKnownScrollPosition) {
                    $scrollToTop.removeClass('visibleScrollTop');
                }
                else {
                    $scrollToTop.addClass('visibleScrollTop');
                }

                tick = false;
            }

            update_button_visibility();

            var requestTick = function () {
                if (tick == false) {
                    window.requestAnimationFrame(update_button_visibility);
                }
                tick = true;
            }

            var onScroll = function () {
                latestKnownScrollPosition = window.scrollY;
                requestTick();
            }

            self.$window.on('scroll', onScroll);
            self.$window.one('djaxClick', function () {
                self.$window.unbind('scroll', onScroll);
            });
        },

        /*----------------------------------------------------------------------------------*/
        /* Snap to scroll
        /*-----------------------------------------------------------------------------------*/

        snap_to_scroll: function () {
            var self = this;

            if (!self.$body.hasClass('snap-to-scroll') || self.windowWidth <= 1140)
            {
                self.$window.trigger('reset-snap-to-scroll');
                return;
            }
                

            //variables
            var delta = 0,
                scrollThreshold = 1,
                animating = false,
                footerHeight,
                footerWidgetHeight = 0,
                footerAndWidgetHeight,
                isfooterVisible = false,
                sectionsAvailable = $('.ep-section'),
                $nav = null;

            sectionsAvailable.eq(0).addClass('visible');

            // Prevent child elements event bubbling 
            sectionsAvailable.find('.section-container *').on("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function (e) {
                e.stopPropagation();
            });

            var st;//set a delay between slidng sections
            //set flag of animation to false at the end of animation
            sectionsAvailable.not('.footer-widgetized').children('.section-container').on("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function (e) {
                clearTimeout(st);
                st = setTimeout(function () {
                    animating = false;
                }, 300);
                
                sectionsAvailable.filter('.visible').css('z-index', 1);

                if ($(this).closest('.ep-section').hasClass('visible')) {
                    self.$document.trigger('snap_to_scroll_slide_end');
                }

            });

            //Add navigation to page
            var numberOfNavItem = sectionsAvailable.length;
            if(sectionsAvailable.filter('.footer-widgetized').length > 0)
            {
                numberOfNavItem = numberOfNavItem - 1;//ignore the footer widget area
            }

            var $nav = '<span></span>'.repeat(numberOfNavItem);
            $("#main-content").append('<span id="snap-to-scroll-nav" class="visible-desktop">' + $nav + '</span>');
            $nav = $("#snap-to-scroll-nav");
            $nav.find('span:first').addClass('active');

            $nav.find('span').on('click',function() {
                var $this = $(this),
                    prevSectionNumber= $this.siblings('.active').index();

                if(prevSectionNumber < 0)
                    prevSectionNumber = sectionsAvailable.length;

                goToSection(prevSectionNumber, $this.index());
            });

            var activeNavItem = function (index) {
                $nav.find('span.active').removeClass('active');
                $nav.find('span').eq(index).addClass('active');
            }

            var setHeight = function () {
                self.windowHeight = self.$window.outerHeight();
                footerWidgetHeight = sectionsAvailable.find('.footer-widgetized-wrap').outerHeight();
                footerHeight = $('footer').outerHeight();
                footerAndWidgetHeight = footerHeight + footerWidgetHeight;

                sectionsAvailable.css({ 'height': self.windowHeight, 'min-height': self.windowHeight });
                sectionsAvailable.filter('.footer-widgetized').css({ 'height': footerAndWidgetHeight, 'min-height': footerAndWidgetHeight });
                $('footer').css('transform', 'translateY(' + footerWidgetHeight + 'px)');
            }

            //Add a special class to last section (if there is footer wigetbar before that) to show footer & widget-bar
            var InitSections = function () {
                //footer widget
                if (sectionsAvailable.last().hasClass('footer-widgetized')) {
                    sectionsAvailable.last().prev('.ep-section').addClass('last-section-before-footer');
                }
                else {
                    sectionsAvailable.last().addClass('last-section-before-footer');
                }

                //Add a special class to showcase section to disable some extra transition in rows that have showcase
                $('.showcase').closest('.section-container').addClass('ep-section-showcase');
            }

            var showFooter = function () {
                //show footer
                if (isfooterVisible) {
                    $('footer.footer-bottom').css('transform', 'translateY(0px)');
                }
                else {
                    if (footerWidgetHeight) {
                        $('footer.footer-bottom').css('transform', 'translateY(' + footerWidgetHeight + 'px)');
                    }
                    else {
                        $('footer.footer-bottom').css('transform', '');
                    }

                }
            }

            var showcase_bg_snap_to_scroll = function ($showcase) {
                if ($showcase === 'undefined')
                    return;

                $showcase.find('.showcase-bg').removeClass('bg-animated active');

                $showcase.find('.showcase-item').removeClass('active');
                $showcase.find('.item-list li').removeClass('active');

                $showcase.find('.showcase-item').first().addClass('active');
                $showcase.find('.item-list li').first().addClass('active');

                //activate and animate
                //There is should be a delay between removeing and adding classes to run animations again
                setTimeout(function () {
                    $showcase.find('.showcase-bg').first().addClass('bg-animated active');
                }, 50)
            }

            //Reset animation for last-section-before-footer (it changed during displaying footer)
            var resetSectionAnimations = function () {
                var visibleSection = sectionsAvailable.filter('.visible');
                if (!visibleSection.is('.last-section-before-footer')) {
                    sectionsAvailable.removeClass('visible-footer');
                }
            }

            var resetScroll = function () {
                delta = 0;
            }

            var goToSection = function (prevSectionNumber,sectionNumber) {

                if (animating == true || prevSectionNumber == sectionNumber)
                    return;

                isfooterVisible = false;


                sectionsAvailable.css('z-index', '');
                var visibleSection = sectionsAvailable.filter('.visible'); 

                if (!animating) {
                    animating = true;

                    if(prevSectionNumber < sectionNumber)
                    {
                        sectionsAvailable.eq(sectionNumber).prevAll('.ep-section').removeClass('visible').children('.section-container').removeClass('translateDown translateNone translateUp').addClass('scaleDown');
                    }
                    else
                    {
                        sectionsAvailable.eq(sectionNumber).nextAll('.ep-section').removeClass('visible').children('.section-container').removeClass('scaleDown translateNone translateUp').addClass('translateDown');
                    }

                    sectionsAvailable.eq(sectionNumber).addClass('visible').children('.section-container').removeClass('translateDown scaleDown translateUp').addClass('translateNone');
                }

                resetSectionAnimations();
                resetScroll();
                showFooter();
                showcase_bg_snap_to_scroll(sectionsAvailable.filter('.visible'));
                activeNavItem(sectionsAvailable.filter('.visible').index());

            }

            var prevSection = function (event) {

                if (animating == true)
                    return;

                isfooterVisible = false;

                //go to previous section
                typeof event !== 'undefined' && event.preventDefault();

                sectionsAvailable.css('z-index', '');
                var visibleSection = sectionsAvailable.filter('.visible');
                if (visibleSection.length <= 0)//happens when there is no widget-bar
                {
                    $('.last-section-before-footer').addClass('visible').children('.section-container').removeClass('scaleDown translateDown translateUp').addClass('translateNone');
                }

                if (!animating && !visibleSection.is(":first-of-type")) {
                    animating = true;

                    visibleSection.removeClass('visible').children('.section-container').removeClass('scaleDown translateNone translateUp').addClass('translateDown');
                    visibleSection.prevAll('.ep-section').first().addClass('visible').children('.section-container').removeClass('translateDown scaleDown translateUp').addClass('translateNone');
                }

                resetSectionAnimations();
                resetScroll();
                showcase_bg_snap_to_scroll(sectionsAvailable.filter('.visible'));
                activeNavItem(sectionsAvailable.filter('.visible').index());
            }

            var nextSection = function (event) {

                if (animating == true)
                    return;

                //go to next section
                typeof event !== 'undefined' && event.preventDefault();

                sectionsAvailable.css('z-index', '');
                var visibleSection = sectionsAvailable.filter('.visible');

                if (!animating && !isfooterVisible) {
                    animating = true;
                    if (visibleSection.hasClass("last-section-before-footer")) {
                        visibleSection.addClass('visible-footer');
                        isfooterVisible = true;
                    }

                    visibleSection.removeClass('visible').children('.section-container').removeClass('translateDown translateNone translateUp').addClass('scaleDown');
                    visibleSection.nextAll('.ep-section').first().addClass('visible').children('.section-container').removeClass('translateDown scaleDown translateUp').addClass('translateNone');

                }
                resetScroll();
                showcase_bg_snap_to_scroll(sectionsAvailable.filter('.visible'));
                activeNavItem(sectionsAvailable.filter('.visible').index());
            }

            var scrollHijacking = function (event) {
                if (self.$body.hasClass('disable-snap-to-scroll'))
                    return;

                // on mouse scroll - check if animate section
                if (event.originalEvent.detail < 0 || event.originalEvent.wheelDelta > 0) {
                    delta--;
                    (Math.abs(delta) >= scrollThreshold) && prevSection();

                } else {
                    delta++;
                    (delta >= scrollThreshold) && nextSection();
                }

                showFooter();

                return false;
            }

            var initHijacking = function () {

                // initialize section style - scrollhijacking
                var visibleSection = sectionsAvailable.filter('.visible'),
                    topSection = visibleSection.prevAll('.ep-section'),
                    bottomSection = visibleSection.nextAll('.ep-section');


                /*** should be aniamted in zero duration time ***/
                topSection.css('opacity', 1).children('.section-container').addClass('scaleDown');
                bottomSection.css('opacity', 1).children('.section-container').addClass('translateDown');
                visibleSection.css('opacity', 1).children('.section-container').addClass('translateNone');

                setTimeout(function () {
                    self.$body.removeClass('snap-to-scroll-init');
                }, 100);

                setTimeout(function () {

                    self.$window.on('DOMMouseScroll mousewheel', scrollHijacking);
                }, 1000);

                self.$document.on('keydown', function (event) {
                    if (event.which == '40') {
                        event.preventDefault();
                        nextSection();
                    } else if (event.which == '38') {
                        event.preventDefault();
                        prevSection();
                    }
                });
            }

            var resetSnapToScroll = function () {

                self.$window.unbind('DOMMouseScroll mousewheel', scrollHijacking);
                self.$document.off('keydown');
                self.$window.unbind('resize', setHeight);
                self.$document.unbind('click', '.showcase .next-showcase a', nextSection);
                $('footer.footer-bottom').css('transform', '');
                sectionsAvailable.removeClass('visible').css('height','').css('min-height','');
                sectionsAvailable.children('.section-container').removeClass('scaleDown translateNone translateUp translateDown');
            }

            var resetInResponsive = function () {
                if(self.windowWidth <= 1140)
                {
                    self.$body.addClass('snap-to-scroll-responsive');
                    self.$window.trigger('reset-snap-to-scroll');
                }
                else
                {
                    if(self.$body.hasClass('snap-to-scroll-responsive'))
                    {
                        clearTimeout(self.resizeId);
                        self.resizeId = setTimeout(function () {
                            self.snap_to_scroll();

                        }, 300);
                        self.$body.removeClass('snap-to-scroll-responsive');
                    }
                }
            }

            //showcase next button compatibility with snap to scroll
            self.$document.on('click', '.showcase .next-showcase a', nextSection);

            self.$window.on('resize', setHeight);
            self.$window.on('resize', resetInResponsive);
            //bind the animation to the window scroll event and keyboard

            self.$window.on('reset-snap-to-scroll', resetSnapToScroll);

            initHijacking();
            setHeight();
            InitSections();

        },

        /*----------------------------------------------------------------------------------*/
        /*  Masonry Blog
        /*-----------------------------------------------------------------------------------*/

        epico_blogMasonry: function (isLoadMore) {
            var self = this;

            $('.masonry-blog.isotope').each(function () {

                var $container = $(this);
                var $layoutMode = $(this).data('layoutmode');

                // calc blog wrap width 
                var $columnNumber = $(this).data('columnnumber');

                if (self.windowWidth <= 979) {
                    $columnNumber = 2;//2column in vertical tablets
                    if (self.windowWidth <= 480) {
                        $columnNumber = 1;//1column in mobile devices
                    }
                }

                var $columnGutter = 15;
                var $blog_wrap_width = $(this).parents('.vc_column-inner').width();
                var $colWidth = Math.floor($blog_wrap_width / $columnNumber) - (2 * $columnGutter);

                if (isLoadMore != true) {
                    $container.isotope({
                        itemSelector: '.isotope-item',
                        layoutMode: $layoutMode,
                    });
                }

                // call isotope animation ( defualt and custom mode )
                self.isotopeAnimation($container);

                var blog_resize_handler = function ()
                {
                    setTimeout(function () {
                        $container.isotope('reLayout');
                    }, 300);
                }
                self.$window.on('resize', blog_resize_handler);
                self.$window.one('djaxClick', function () {
                    self.$window.unbind('resize', blog_resize_handler);
                });

                $container.find('.blog-masonry-container').each(function () {

                    var $blogItems = $(this);
                    var $blogGalleryContainer = $(this).find('.blogGalleryContainer');

                    $blogItems.css({
                        'width': $colWidth,
                        'max-width': $colWidth,
                    });


                    $blogItems.find('.swiper-container').each(function () {

                        var $this = $(this);
                        if ($this.find('.swiper-slide').length > 1) {
                            var $next_button = $this.find('.swiper-button-next'),
                                $prev_button = $this.find('.swiper-button-prev'),
                                $autoplayDuration = 3000 + Math.floor(Math.random() * 4000);


                            //Prevent from running swiper multiple times on initiated items
                            if ($this[0].swiper != undefined)
                                return true;

                            // Slider For Blog : Gallert Post format 
                            var $blog_cart_slider = new Swiper($this, {
                                speed: 600,
                                longSwipesMs: 700,
                                touchAngle: 30,
                                loop: true,
                                autoplayDisableOnInteraction: false,
                                spaceBetween: 0,
                                followFinger: false,
                                nextButton: $next_button,
                                prevButton: $prev_button,
                                autoplay: $autoplayDuration,
                            });

                            var blog_cart_slider_resize_handler = function() {
                                setTimeout(function () {
                                    $blog_cart_slider.onResize();
                                }, 100);
                            }

                            self.$window.on('resize', blog_cart_slider_resize_handler);

                            self.$window.one('djaxClick',function(){
                                self.$window.unbind('resize', blog_cart_slider_resize_handler);
                            });

                            blog_cart_slider_resize_handler();// resize swiper after loading to calculate width correctly


                        }
                        else {
                            $this.find('.swiper-wrapper').addClass('disabled_swiper');
                        }

                    });
                    self.galleryStart(); //To restart pop-up video and pop-up sound
                });
            });
        },

        /*----------------------------------------------------------------------------------*/
        /*  Gallery
        /*-----------------------------------------------------------------------------------*/

        galleryStart: function () {
            var self = this;

            if (typeof $.fn.lightGallery != 'function')
                return;

            $('.ep_lightGallery').each(function () {

                var $this = $(this);

                //Let the animation be set by the user
                var $slideAnimation = $this.parent('.isotope').data('animation-type'),
                    $galleryId = $this.attr('id');

                $this.lightGallery({
                    selector: '.galleryItem',
                    counter: true,
                    mode: $slideAnimation,
                    speed: 400,
                    thumbnail: true,
                    currentPagerPosition: 'middle',
                    galleryId: $galleryId,
                    iframeMaxWidth: '80%'
                });

                $this.on('onBeforeOpen.lg', function (event) {
                    //detect if the gallery is of type light style
                    $(".isotope.lightPopUp").click(function () {
                        $('.lg').addClass('lightStyle');//when LightStyle is selected
                        $('.lg-backdrop.in').addClass('galleryBack');
                    });
                });

                $this.on('onAfterOpen.lg', function (event) {
                    $(".isotope .isotope-item").click(function () {

                        // Add Class Active Gallery 
                        $('.ep_lightGallery').removeClass('activeGallery');
                        $(this).parents('.ep_lightGallery').addClass('activeGallery');

                        //Calculating Social share information
                        var $json = $.parseJSON($(this).find('.postphoto .galleryItem a.portfolioLink').data('social-share'));

                        //Adding socailshare to the image wrap
                        $(".lg-outer .bd_socail_share").remove();
                        $(".lg-outer .lg-toolbar.group .lg-autoplay-button").after($json);
                        $('.social_share_toggle').hover(function () {//social button animation
                            $('.social_share_toggle .social_links_list').toggleClass('openToggle');
                        });

                        self.social_share_pop_up();
                        self.$body.addClass('gl-open');

                    });

                });


                $this.on('onAfterAppendSubHtml.lg', function (event) {

                    // Update socailshare
                    var $index = $('.lg-inner .lg-item.lg-current').index();
                    //Calculating Social share information
                    var $json = $.parseJSON($('.ep_lightGallery.activeGallery').find('.postphoto .galleryItem a.portfolioLink').eq($index).data('social-share'));
                    //Adding socailshare to the image wrap
                    $(".lg-outer .bd_socail_share").remove();
                    $(".lg-outer .lg-toolbar.group .lg-autoplay-button").after($json);
                    $('.social_share_toggle').hover(function () {//social button animation
                        $('.social_share_toggle .social_links_list').toggleClass('openToggle');
                    });

                    self.social_share_pop_up();

                    $('.lg-sub-html').children().show('opacity', 1);

                });

                $this.on('onBeforeClose.lg', function (event) {
                    self.$body.removeClass('gl-open');
                });

            });

        },

        /*----------------------------------------------------------------------------------*/
        /*  compare
        /*----------------------------------------------------------------------------------*/

        compare: function () {
            var self = this;

            var $compare_modal = self.$document.find('#ep-modal'),
                $compare_content = $compare_modal.find('#modal-content'),
                $compare_wrapper = $compare_modal.find('.modal-content-wrapper');

            $('.compare').on('click', function () {
                $compare_modal.addClass('compare-modal open').removeClass('closed'); // content is ready, so show it
            });

            // Close quickview by click outside of content
            $compare_modal.on('click', function (e) {
                if (!$compare_modal.hasClass('compare-modal'))
                    return;

                if (!$compare_wrapper.is(e.target) && $compare_wrapper.has(e.target).length === 0) {
                    closeCompareModal();
                }
            });

            self.$document.on('click', '#ep-modal.compare-modal #modal-close', function (e) {
                e.preventDefault();
                closeCompareModal();
            });

            var closeCompareModal = function () {
                $compare_modal.removeClass('shown loading open').addClass('closed');
                setTimeout(function () {
                    self.$body.removeClass('modal-open');
                    $compare_modal.removeClass('compare-modal');
                }, 300)

                setTimeout(function () {
                    $compare_content.html('');
                }, 800);

                var widget_list = $('.yith-woocompare-widget ul.products-list'),
                    data = {
                        action: yith_woocompare.actionview,
                        context: 'frontend'
                    };

                if (typeof $.fn.block != 'undefined') {
                    widget_list.block({ message: null, overlayCSS: { background: '#fff url(' + yith_woocompare.loader + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } });
                }

                $.ajax({
                    type: 'post',
                    url: yith_woocompare.ajaxurl.toString().replace('%%endpoint%%', yith_woocompare.actionview),
                    data: data,
                    success: function (response) {
                        // add the product in the widget
                        if (typeof $.fn.block != 'undefined') {
                            widget_list.unblock().html(response);
                        }
                        widget_list.html(response);
                    }
                });
            }

            // open popup & Run yith_woocompare_open_popup handler 
            self.$body.off('yith_woocompare_open_popup');
            self.$body.on('yith_woocompare_open_popup', function (e, data) {
                var url = data.response;

                self.$body.addClass('modal-open'); // Disable scrollbar

                $.post({
                    url: url + ' .compare-list',
                    cache: false,
                    headers: { 'cache-control': 'no-cache' },
                    success: function (response) {
                        $compare_content.html(response);
                        $compare_modal.addClass('shown');
                        self.epico_scrollbar('table.compare-list tr.description td p:first-child ');
                    }
                });

            });

            self.$document.find('ep-modal.compare-modal tr.remove a').off('click');

            // remove from table
            self.$document.on('click', '#ep-modal.compare-modal tr.remove a', function (e) {
                e.preventDefault();

                $(this).addClass('noRotate');

                var button = $(this),
                    data = {
                        action: yith_woocompare.actionremove,
                        id: button.data('product_id'),
                        context: 'frontend'
                    },
                    product_cell = $('td.product_' + data.id + ', th.product_' + data.id);

                // add ajax loader
                if (typeof $.fn.block != 'undefined') {
                    button.block({
                        message: null,
                        overlayCSS: {
                            background: '#fff url(' + yith_woocompare.loader + ') no-repeat center',
                            backgroundSize: '16px 16px',
                            opacity: 0.6
                        }
                    });
                }

                $.ajax({
                    type: 'post',
                    url: yith_woocompare.ajaxurl.toString().replace('%%endpoint%%', yith_woocompare.actionremove),
                    data: data,
                    dataType: 'html',
                    success: function (response) {

                        // in compare table
                        var table = $(response).filter('table.compare-list');
                        $('body  table.compare-list').replaceWith(table);

                        $('.compare[data-product_id="' + button.data('product_id') + '"]', window.parent.document).removeClass('added').html(yith_woocompare.button_text);

                        // removed trigger
                        self.$window.trigger('yith_woocompare_product_removed');
                    }
                });
            });


        },

        getScrollBarWidth: function () {
            var self = this;

            var inner = document.createElement('p');
            inner.style.cssText = 'width: 100%; height: 200px;';

            var outer = document.createElement('div');
            outer.style.cssText = 'width: 200px; height: 150px; position:absolute; visibility:hidden; left:0px; top:0px; overflow:hidden;';
            outer.appendChild(inner);

            document.body.appendChild(outer);
            var w1 = inner.offsetWidth;
            outer.style.overflow = 'scroll';
            var w2 = inner.offsetWidth;
            if (w1 == w2) w2 = outer.clientWidth;

            document.body.removeChild(outer);

            if ((w1 - w2) > 0) { // Detect scrollbar width
                if (self.$body.height() > self.windowHeight) { // Detect if a page has a vertical scrollbar?
                    self.$body.addClass('has-scrollbar');

                    if ((w1 - w2) == 15) { // ios device 
                        self.$body.addClass('scrollbarSize15');
                    } else if ((w1 - w2) == 17) { // chorme and firefox in windows 
                        self.$body.addClass('scrollbarSize17');
                    } else if ((w1 - w2) == 12) { // edge in windows 
                        self.$body.addClass('scrollbarSize12');
                    }
                }
            }
        },
		cat_widget_update: function() { //add toggle button to category widget + show current category/subcategory
			var $widget = $('.widget_product_categories'),
			$list = $widget.find('.product-categories');
			
			$list.find('.cat-parent').each(function()
			{
				var $this = $(this);
				if( $this.find('ul').length <= 0 )
					return true;
				
				if( $this.hasClass('current-cat-parent'))
					$this.append( '<div class="cats-toggle toggle-active"></div>' );
				else
					$this.append( '<div class="cats-toggle"></div>' );
            });
			
			
			if( $list.find('li.current-cat').length > 0 )
			{
				$list.find('li.current-cat').parents('ul').css("display","block");
				$list.find('li.current-cat ul.children').css("display","block");
				$list.find('.current-cat.cat-parent .cats-toggle').addClass('toggle-active');
			}
		},
		
		cat_widget: function(){
			var $widget = $('.widget_product_categories'),
			$list = $widget.find('.product-categories'),
			time = 500;
			
			$list.on('click', '.cats-toggle', function()
			{
				var $btn = $(this),
				$subList = $btn.prev();
				if( $subList.hasClass('list-shown') ) 
				{
					$btn.removeClass('toggle-active');
					$subList.stop().slideUp(time).removeClass('list-shown');
				}
				else 
				{
					$subList.parent().parent().find('> li > .list-shown').slideUp().removeClass('list-shown');
					$subList.parent().parent().find('> li > .toggle-active').removeClass('toggle-active');
					$btn.addClass('toggle-active');
					$subList.stop().slideDown(time).addClass('list-shown');
				}
			});
		},
		

        // cookies bar 
		cookiesBar: function () {
		    if (typeof Cookies === 'undefined') { // this is temperpry solution 
		        return false;
		    }

			if( Cookies.set('ep_cookies') == 'accepted') 
				return;
			var cookiesbar = $( '.ep-cookies-bar');
			setTimeout(function() {
			    cookiesbar.addClass('bar-display');
			    cookiesbar.on('click', '.cookies-accept-btn', function (e) {
					e.preventDefault();
					acceptCookies();
				})
			}, 2500);

			var acceptCookies = function() {
			    cookiesbar.removeClass('bar-display').addClass('bar-hide');
				Cookies.set('ep_cookies', 'accepted', { expires: 60, path: '/'} );
				
			};
        },
		


		account_popup: function(){
			var self = this,
				$account_link = $('.login-link-popup');

				$account_link.on('click', function(e) {
				e.preventDefault();

				var $link = $(this),
					$account_modal = self.$document.find('#ep-modal'),
					$account_wrapper = $account_modal.find('.modal-content-wrapper'),
					$account_content = $account_modal.find('#modal-content');

				if ($account_modal.length <= 0)
					return;
				
				
				$account_modal.addClass('hidden-nav');

				self.$body.addClass('modal-open'); // disable scrollbar
				$account_modal.addClass('account-modal');

				if (!$account_modal.removeClass('closed').hasClass('open')) {
					$account_modal.removeClass('loading').addClass('open');
				}
						
					
				var $data = $('#customer_login.hide-login').clone().removeClass('hide-login');

				$account_content.html($data);
				$account_modal.addClass('shown'); // content is ready, so show it


				// Close quickview by click outside of content
				$account_modal.on('click', function (e) {
					if (!$account_content.is(e.target) && $account_content.has(e.target).length === 0 ) {
						self.close_account_popup();
					}
				});

				// Close quickview by click close button
				self.$document.on('click', '#ep-modal.account-modal #modal-close', function (e) {
					e.preventDefault();
					self.close_account_popup();
				});

				// Close box with esc key
				self.$document.keyup(function (e) {
					if (e.keyCode === 27)
						self.close_account_popup();
				});				
					
			})

		},
        close_account_popup: function () {
            var self = this;

            var $account_modal = self.$document.find('#ep-modal.account-modal'),
                $account_content = $account_modal.find('#modal-content');

            $account_modal.removeClass('shown loading open').addClass('closed');

            setTimeout(function () {
                self.$body.removeClass('modal-open');
                $account_modal.removeClass('account-modal');
            }, 300)

            setTimeout(function () {
                $account_content.html('');
            }, 800);
        }

    };

    // Add core script to $.VitrineTheme so it can be extended
    $.VitrineTheme = VitrineTheme.prototype;


    $(document).ready(function () {
        // Initialize script
        new VitrineTheme();
    });

})(jQuery, window, document);