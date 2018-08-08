<?php 
  $headerType = epico_opt('header-type');
  $headerStyle = epico_opt('header-style'); 
  $heaerStyleDefault = 'fixed-menu';
  $search = epico_opt('menu-search');
  $menuInContainer = epico_opt('menu-container');

  //get menu hover Style option
  $menu_hover_style = epico_opt('menu-hover-style');
  if ( $menu_hover_style == '0') {
    $menuHoverStyle = 'borderhover';
  } else if ( $menu_hover_style == '1') {
    $menuHoverStyle = 'fillhover';
  } 
  else if( $menu_hover_style == '2') {
    $menuHoverStyle = 'underlineHover';  
  } else {
    $menuHoverStyle = 'fadeHover';  
  }

  //get submenu hover Style option
  $submenu_hover_style = epico_opt('submenu-hover-style');
  if ( $submenu_hover_style == '1') {
    $submenu_hover_style = 'submenu_underlined';
  }
  else
  {
    $submenu_hover_style = '';
  }

  if ( $headerType == 1 ) {

    $headerTypeClass = ' type1';

  } else if ( $headerType == 7 || $headerType == 8 ) { // left & rightSidebar

    $headerTypeClass = ' type7';

  } else if ( $headerType == 9) { // Logo Center 

    $headerTypeClass = ' type9';

  } else if ( $headerType == 2 ||  $headerType == 3  ) {

    if ( $headerType == 2 ) {

        $headerTypeClass = 'type2_3 type2';

    } else if ( $headerType == 3) {

        $headerTypeClass = 'type2_3 type3';
    }

  } else if ( $headerType == 4 ||  $headerType == 5 || $headerType == 6 ) {

    if ( $headerType == 4 ) {

        $headerTypeClass = 'type4_5_6 type4';

    } else if ( $headerType == 5) {

        $headerTypeClass = 'type4_5_6 type5';

    } else if ( $headerType ==  6 ) {
    
        $headerTypeClass = 'type4_5_6 type6';
    }

  }

  // shop cart - Enable Or disable option
  $shop_cart = epico_opt('shop-enable-cart');

  // catalog mode option
  $catalog_mode = epico_opt('catalog_mode');

  // responsive logo
  $responsivelogo = epico_opt('responsivelogo');
  
?>

  <?php if ( $headerType == 1  || $headerType == 4 || $headerType == 5  || $headerType == 6 ) { // defualt positon Header - top Style ?>  

          <!-- Header Navigation  -->
          <header id="epHeader" data-fixed="<?php  if ( isset($headerStyle) ) { echo esc_attr($headerStyle); } else { echo esc_attr($heaerStyleDefault); } ?>"  class="<?php if ( $search != 1 ) { ?> no-search <?php } ?><?php if ( !has_nav_menu( 'primary-nav' )  ) { ?>no-menu <?php } if ( !has_nav_menu( 'primary-nav' )  ) { ?>no-menu <?php } if ( epico_opt('ep-toggle-sidebar') == 1 ) { ?> hastogglesidebar <?php } ?><?php if ( $shop_cart == 1 && $catalog_mode == 0 ) { ?> hasDropDownCart <?php } ?><?php if ( epico_opt('topbar_display') == 1) {  ?> menuSpaceNoti <?php } ?>  <?php echo esc_attr($menuHoverStyle) ; ?>  <?php echo esc_attr($submenu_hover_style); ?> <?php echo esc_attr($headerTypeClass); ?> <?php echo esc_attr($headerStyle); ?>  <?php if ( $menuInContainer == 1 ) { ?>  fullwidthMenu  <?php } ?> " >
  	        <div class="wrap headerWrap">
                  <div id="headerFirstState">
  				    <div class="menuBgColor hidden-phone hidden-tablet"></div>

                          <?php if ( $menuInContainer == 0 ) { ?> <!-- if menu be in container -->

                              <div class="container clearfix">

                          <?php } ?> 
  				
  						    <!-- First Logo -->
  						    <?php $logo = epico_opt('logo') == "" ? EPICO_THEME_ASSETS_URI . "/content/img/logo.png" : epico_opt('logo'); ?>

  						    <a class="locallink logo" href="<?php echo get_site_url(); ?>/#home">
                                  <?php if( $responsivelogo != '') {?><img  class="firstLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
                                  <img  class="firstLogo" src="<?php echo esc_url($logo); ?>" alt="Logo" />
  							    
  						    </a>
                                  
                              <a class="externalLink logo" href="<?php echo get_site_url(); ?>">
                                  <?php if( $responsivelogo != '') {?><img  class="firstLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
                                  <img  class="firstLogo" src="<?php echo esc_url($logo); ?>" alt="Logo" />
  						    </a>

                             <?php   

                                  // Check if WooCommerce is active
                                  if ( $shop_cart == '1' && $catalog_mode == 0 && class_exists( 'WooCommerce' ) ) {
                                      
                                      /*  woocomerce drop down cart widget */
                                     //Because it pushes the entire content to a side, it should be placed outside of layout element
                                      get_template_part( 'templates/woocommerce/cart' );
                                  }
                                      
                              ?>

                              <?php
                                   if ( epico_opt('ep-toggle-sidebar') == 1 ) { 
                                        $epToggleSidebar = epico_opt('ep-toggle-sidebar-style'); // Dark Or Light Styles 
                              ?> 
                                   
                                  <div class="sidebartogglebtn hover <?php if ( $epToggleSidebar == 1 ) { ?>light<?php } ?>">
                                      <ul class="sidebartogglebtnlines">
                                          <li><hr ></li>
                                          <li><hr ></li>
                                          <li><hr ></li>
                                      </ul>
                                  </div>

                              <?php } ?>
                                         
                              
                              <?php
                              if($search == 1)
                                  {
                                      ?>
                                      <span class="search-button icon-magnifier no-select hidden-phone hidden-tablet"></span>
                                      <?php
                                  }
                              ?>
                              <?php
                              ////check if current client is on mobile
                              //$isMobile = (bool)preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows (phone|ce)|blackberry|tablet'. '|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]'.
                              //'|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT'] );

                               if ( has_nav_menu( 'primary-nav' ) ) { ?>
                              <nav class="navigation hidden-phone hidden-tablet">
  					            <?php
  					                wp_nav_menu(array(
  						                'container' =>'',
  						                'menu_class' => 'clearfix',
  						                'before'     => '',
  						                'theme_location' => 'primary-nav',
  						                'walker'     => new epico_Nav_Walker(),
  						                'fallback_cb' => false , 
                                          'after' => ''
  					                ));
  					            ?>
  				            </nav>
                              <?php } ?>


                              <?php if ( $menuInContainer == 0 ) { ?> <!-- if menu be in container -->

                                      </div>

                              <?php } ?> 
                              					       
  				    </div>	
  				
  		        <?php  if ( isset($headerStyle)) { 
  				        if ( $headerStyle == 'epico-menu' ) { ?>  

                              <div id="headerSecondState" class="hidden-phone hidden-tablet">
  			                    <div id="menuBgColor" class="hidden-phone hidden-tablet"></div>

                                  <?php if ( $menuInContainer == 0 ) { ?> <!-- if menu be in container -->

                                      <div class="container clearfix">

                                  <?php } ?> 
  			        

  				                    <!-- Secound Logo -->
  				                    <?php $logoSecond = epico_opt('logo-second') == "" ? EPICO_THEME_ASSETS_URI . "/content/img/logo.png" : epico_opt('logo-second'); ?>

  				                    <a class="locallink logo" href="<?php echo get_site_url(); ?>/#home">
                                          <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  					                    <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
  				                    </a>
                          
                                      <a class="externalLink logo" href="<?php echo get_site_url(); ?>">
                                          <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  					                    <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
  				                    </a>

                                      <?php   

                                          // Check if WooCommerce is active
                                          if ( $shop_cart == '1' && $catalog_mode == 0 && class_exists( 'WooCommerce' ) ) {
                                      
                                              /*  woocomerce drop down cart widget */
                                             //Because it pushes the entire content to a side, it should be placed outside of layout element
                                              get_template_part( 'templates/woocommerce/cart' );
                                          }
                                      
                                      ?>

                                      <?php
                                           if ( epico_opt('ep-toggle-sidebar') == 1 ) { 
                                                $epToggleSidebar = epico_opt('ep-toggle-sidebar-style'); // Dark Or Light Styles 
                                      ?> 
                                   
                                          
                                          <div class="sidebartogglebtn hover <?php if ( $epToggleSidebar == 1 ) { ?>light<?php } ?>">
                                              <ul class="sidebartogglebtnlines">
                                                  <li><hr ></li>
                                                  <li><hr ></li>
                                                  <li><hr ></li>
                                              </ul>
                                          </div>

                                      <?php } ?>

                                      
                                      <?php
                                      if($search == 1)
                                      {
                                           ?>
                                              <span class="search-button icon-magnifier no-select hidden-phone hidden-tablet"></span>
                                           <?php
                                      }
                                      ?>
                                      <?php
                                      ////check if current client is on mobile
                                      //$isMobile = (bool)preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows (phone|ce)|blackberry|tablet'. '|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]'.
                                      //'|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT'] );

                                      if ( has_nav_menu( 'primary-nav' ) ) { ?>
                                      <nav class="navigation hidden-phone hidden-tablet">
  					                    <?php
  					                        wp_nav_menu(array(
  						                        'container' =>'',
  						                        'menu_class' => 'clearfix',
  						                        'before'     => '',
  						                        'theme_location' => 'primary-nav',
  						                        'walker'     => new epico_Nav_Walker(),
  						                        'fallback_cb' => false , 
                                                  'after' => ''
  					                        ));
  					                    ?>
  				                    </nav>
                                      <?php } ?>

                                  <?php if ( $menuInContainer == 0 ) { ?> <!-- if menu be in container -->
                                     </div>
                                  <?php } ?> 

  		                    </div>	
  			    

                      <?php  } 
  		         } ?>
           
           
                  <?php
                      //Because it pushes the entire content to a side, it should be placed outside of layout element
                      get_template_part( 'templates/navigation-mobile' );

                  ?>

  	        </div>
          </header>
          <!-- Header Navigation End -->


  <?php } else if ( $headerType == 2 ||  $headerType == 3 ) {   ?>

          <!-- Header Navigation  -->
          <header id="epHeader" data-fixed="<?php  if ( isset($headerStyle) ) { echo esc_attr($headerStyle); } else { echo esc_attr($heaerStyleDefault); } ?>"  class="<?php if ( !has_nav_menu( 'primary-nav' )  ) { ?>no-menu <?php } if ( epico_opt('ep-toggle-sidebar') == 1 ) { ?> hastogglesidebar <?php } ?><?php if ( $shop_cart == 1 && $catalog_mode == 0 ) { ?> hasDropDownCart <?php } ?><?php if ( epico_opt('topbar_display') == 1) {  ?> menuSpaceNoti <?php } ?>  <?php echo esc_attr($menuHoverStyle) ; ?>  <?php echo esc_attr($headerTypeClass); ?> <?php echo esc_attr($headerStyle); ?>  <?php if ( $menuInContainer == 1 ) { ?>  fullwidthMenu  <?php } ?> " >
  	        <div class="wrap headerWrap">
                  <div id="headerFirstState">
  				    <div class="menuBgColor hidden-phone hidden-tablet"></div>

                          <?php if ( $menuInContainer == 0 ) { ?> <!-- if menu be in container -->

                              <div class="container clearfix">

                          <?php } ?> 

  						    <!-- First Logo -->
  						    <?php $logo = epico_opt('logo') == "" ? EPICO_THEME_ASSETS_URI . "/content/img/logo.png" : epico_opt('logo'); ?>

                                  <div class="locallink logo">
  						            <a href="<?php echo get_site_url(); ?>/#home">
                                          <?php if( $responsivelogo != '') {?><img  class="firstLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  							            <img  class="firstLogo" src="<?php echo esc_url($logo); ?>" alt="Logo" />
  						            </a>
                                  </div>

                                  <div class="externalLink logo">
                                      <a href="<?php echo get_site_url(); ?>">
                                          <?php if( $responsivelogo != '') {?><img  class="firstLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  							            <img  class="firstLogo" src="<?php echo esc_url($logo); ?>" alt="Logo" />
  						            </a>
                                  </div>

                              <?php   

                                  // Check if WooCommerce is active
                                  if ( $shop_cart == '1' && $catalog_mode == 0 && class_exists( 'WooCommerce' ) ) {
                                      
                                      /*  woocomerce drop down cart widget */
                                      //Because it pushes the entire content to a side, it should be placed outside of layout element
                                      get_template_part( 'templates/woocommerce/cart' );
                                  }
                                      
                              ?>
                                  
                              <?php
                                   if ( epico_opt('ep-toggle-sidebar') == 1 ) { 
                                        $epToggleSidebar = epico_opt('ep-toggle-sidebar-style'); // Dark Or Light Styles 
                              ?> 
                                   
                                  
                                  <div class="sidebartogglebtn hover <?php if ( $epToggleSidebar == 1 ) { ?>light<?php } ?>">
                                      <ul class="sidebartogglebtnlines">
                                          <li><hr ></li>
                                          <li><hr ></li>
                                          <li><hr ></li>
                                      </ul>
                                  </div>

                              <?php } ?>
                                         

                              
                              <?php
                              if($search == 1)
                                  {
                                      ?>
                                      <span class="search-button icon-magnifier no-select hidden-phone hidden-tablet"></span>
                                      <?php
                                  }
                              ?>
                              <?php if ( has_nav_menu( 'primary-nav' ) ) { ?>
                              <nav class="navigation hidden-phone hidden-tablet">
  					            <?php
  					                wp_nav_menu(array(
  						                'container' =>'',
  						                'menu_class' => 'clearfix',
  						                'before'     => '',
  						                'theme_location' => 'primary-nav',
  						                'walker'     => new epico_Nav_Walker(),
  						                'fallback_cb' => false , 
                                          'after' => ''
  					                ));
  					            ?>
  				            </nav>
                              <?php } ?>

                              <?php if ( $menuInContainer == 0 ) { ?> <!-- if menu be in container -->
  					            </div>
                              <?php } ?> 

  				    </div>	
  				

  		        <?php  if ( isset($headerStyle)) { 
  				        if ( $headerStyle == 'epico-menu' ) { ?>  
  				


                      <div id="headerSecondState" class="hidden-phone hidden-tablet">
  			            <div id="menuBgColor" class="hidden-phone hidden-tablet"></div>

                          <?php if ( $menuInContainer == 0 ) { ?> <!-- if menu be in container -->

                              <div class="container clearfix">

                          <?php } ?> 
  			        

  				            <!-- Secound Logo -->
  				            <?php $logoSecond = epico_opt('logo-second') == "" ? EPICO_THEME_ASSETS_URI . "/content/img/logo.png" : epico_opt('logo-second'); ?>

  				            <a class="locallink logo" href="<?php echo get_site_url(); ?>/#home">
                                  <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  					            <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
  				            </a>
                          
                              <a class="externalLink logo" href="<?php echo get_site_url(); ?>">
                                  <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  					            <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
  				            </a>
                  
                              <?php   

                                  // Check if WooCommerce is active
                                  if ( $shop_cart == '1' && $catalog_mode == 0 && class_exists( 'WooCommerce' ) ) {
                                      
                                      /*  woocomerce drop down cart widget */
                                      //Because it pushes the entire content to a side, it should be placed outside of layout element
                                      get_template_part( 'templates/woocommerce/cart' );
                                  }
                                      
                              ?>
                              
                              <?php
                                   if ( epico_opt('ep-toggle-sidebar') == 1 ) { 
                                        $epToggleSidebar = epico_opt('ep-toggle-sidebar-style'); // Dark Or Light Styles 
                              ?> 
                                   
                                  
                                  <div class="sidebartogglebtn hover <?php if ( $epToggleSidebar == 1 ) { ?>light<?php } ?>">
                                      <ul class="sidebartogglebtnlines">
                                          <li><hr ></li>
                                          <li><hr ></li>
                                          <li><hr ></li>
                                      </ul>
                                  </div>

                              <?php } ?>

                              
                              <?php
                              if($search == 1)
                              {
                                   ?>
                                      <span class="search-button icon-magnifier no-select hidden-phone hidden-tablet"></span>
                                   <?php
                              }
                              ?>

                              <?php if ( has_nav_menu( 'primary-nav' ) ) { ?>
                              <nav class="navigation hidden-phone hidden-tablet">
  					            <?php
  					                wp_nav_menu(array(
  						                'container' =>'',
  						                'menu_class' => 'clearfix',
  						                'before'     => '',
  						                'theme_location' => 'primary-nav',
  						                'walker'     => new epico_Nav_Walker(),
  						                'fallback_cb' => false , 
                                          'after' => ''
  					                ));
  					            ?>
  				            </nav>
                              <?php } ?>

                          <?php if ( $menuInContainer == 0 ) { ?> <!-- if menu be in container -->
                             </div>
                          <?php } ?> 

  		            </div>

  			        <?php  } 
  		         } ?>
           
           
                  <?php
                      //Because it pushes the entire content to a side, it should be placed outside of layout element
                      get_template_part( 'templates/navigation-mobile' );

                  ?>

  	        </div>
          </header>
          <!-- Header Navigation End -->

  <?php } else if ( $headerType == 9 ) {  // Logo Center In menu ?>

          <!-- Header Navigation  -->
          <header id="epHeader" data-fixed="<?php  if ( isset($headerStyle) ) { echo esc_attr($headerStyle); } else { echo esc_attr($heaerStyleDefault); } ?>"  class="<?php if ( !has_nav_menu( 'primary-nav' )  ) { ?>no-menu <?php } if ( epico_opt('ep-toggle-sidebar') == 1 ) { ?> hastogglesidebar <?php } ?><?php if ( $shop_cart == 1 && $catalog_mode == 0 ) { ?> hasDropDownCart <?php } ?><?php if ( epico_opt('topbar_display') == 1) {  ?> menuSpaceNoti <?php } ?>  <?php echo esc_attr($menuHoverStyle); ?>  <?php echo esc_attr($headerTypeClass); ?> <?php echo esc_attr($headerStyle); ?>  <?php if ( $menuInContainer == 1 ) { ?>  fullwidthMenu  <?php } ?> " >
  	        <div class="wrap headerWrap">
                  <div id="headerFirstState">
  				    <div class="menuBgColor hidden-phone hidden-tablet"></div>

                          <?php if ( $menuInContainer == 0 ) { ?> <!-- if menu be in container -->

                              <div class="container clearfix">

                          <?php } ?> 
                              <?php if ( has_nav_menu( 'primary-nav' ) ) { ?>
                              <nav class="navigation hidden-phone hidden-tablet">
  					            <?php
  					                wp_nav_menu(array(
  						                'container' =>'',
  						                'menu_class' => 'clearfix',
  						                'before'     => '',
  						                'theme_location' => 'primary-nav',
  						                'walker'     => new epico_Nav_Walker(),
  						                'fallback_cb' => false , 
                                          'after' => ''
  					                ));
  					            ?>
  				            </nav>
                              <?php } ?>
  				
  						    <!-- First Logo -->
  						    <?php $logo = epico_opt('logo') == "" ? EPICO_THEME_ASSETS_URI . "/content/img/logo.png" : epico_opt('logo'); ?>

  						    <a class="locallink logo" href="<?php echo get_site_url(); ?>/#home">
                                  <?php if( $responsivelogo != '') {?><img  class="firstLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  							    <img  class="firstLogo" src="<?php echo esc_url($logo); ?>" alt="Logo" />
  						    </a>
                                  
                              <a class="externalLink logo" href="<?php echo get_site_url(); ?>">
                                  <?php if( $responsivelogo != '') {?><img  class="firstLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  							    <img  class="firstLogo" src="<?php echo esc_url($logo); ?>" alt="Logo" />
  						    </a>
                  
                              <?php   

                                  // Check if WooCommerce is active
                                  if ( $shop_cart == '1' && $catalog_mode == 0 && class_exists( 'WooCommerce' ) ) {
                                      
                                      /*  woocomerce drop down cart widget */
                                      //Because it pushes the entire content to a side, it should be placed outside of layout element
                                      get_template_part( 'templates/woocommerce/cart' );
                                  }
                                      
                              ?>
                              <?php
                                   if ( epico_opt('ep-toggle-sidebar') == 1 ) { 
                                        $epToggleSidebar = epico_opt('ep-toggle-sidebar-style'); // Dark Or Light Styles 
                              ?> 
                                   
                                  
                                  <div class="sidebartogglebtn hover <?php if ( $epToggleSidebar == 1 ) { ?>light<?php } ?>">
                                      <ul class="sidebartogglebtnlines">
                                          <li><hr ></li>
                                          <li><hr ></li>
                                          <li><hr ></li>
                                      </ul>
                                  </div>

                              <?php } ?>
                                                              
                              
                              <?php
                              if($search == 1)
                                  {
                                      ?>
                                      <span class="search-button icon-magnifier no-select hidden-phone hidden-tablet"></span>
                                      <?php
                                  }
                              ?>
                             

                              <?php if ( $menuInContainer == 0 ) { ?> <!-- if menu be in container -->

                                      </div>

                              <?php } ?> 
                              					       
  				    </div>	
  				
  		        <?php  if ( isset($headerStyle)) { 
  				        if ( $headerStyle == 'epico-menu' ) { ?>  

                              <div id="headerSecondState" class="hidden-phone hidden-tablet">
  			                    <div id="menuBgColor" class="hidden-phone hidden-tablet"></div>

                                  <?php if ( $menuInContainer == 0 ) { ?> <!-- if menu be in container -->

                                      <div class="container clearfix">

                                  <?php } ?> 
  			        
                                      <?php if ( has_nav_menu( 'primary-nav' ) ) { ?>
                                      <nav class="navigation hidden-phone hidden-tablet">
  					                    <?php
  					                        wp_nav_menu(array(
  						                        'container' =>'',
  						                        'menu_class' => 'clearfix',
  						                        'before'     => '',
  						                        'theme_location' => 'primary-nav',
  						                        'walker'     => new epico_Nav_Walker(),
  						                        'fallback_cb' => false , 
                                                  'after' => ''
  					                        ));
  					                    ?>
  				                    </nav>
                                      <?php } ?>


  				                    <!-- Secound Logo -->
  				                    <?php $logoSecond = epico_opt('logo-second') == "" ? EPICO_THEME_ASSETS_URI . "/content/img/logo.png" : epico_opt('logo-second'); ?>

  				                    <a class="locallink logo" href="<?php echo get_site_url(); ?>/#home">
                                          <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  					                    <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
  				                    </a>
                          
                                      <a class="externalLink logo" href="<?php echo get_site_url(); ?>">
                                          <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  					                    <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
  				                    </a>

                                      <?php   

                                          // Check if WooCommerce is active
                                          if ( $shop_cart == '1' && $catalog_mode == 0 && class_exists( 'WooCommerce' ) ) {
                                      
                                              /*  woocomerce drop down cart widget */
                                              //Because it pushes the entire content to a side, it should be placed outside of layout element
                                              get_template_part( 'templates/woocommerce/cart' );
                                          }
                                      
                                      ?>

                                      <?php
                                           if ( epico_opt('ep-toggle-sidebar') == 1 ) { 
                                                $epToggleSidebar = epico_opt('ep-toggle-sidebar-style'); // Dark Or Light Styles 
                                      ?> 
                                   
                                          
                                          <div class="sidebartogglebtn hover <?php if ( $epToggleSidebar == 1 ) { ?>light<?php } ?>">
                                              <ul class="sidebartogglebtnlines">
                                                  <li><hr ></li>
                                                  <li><hr ></li>
                                                  <li><hr ></li>
                                              </ul>
                                          </div>

                                      <?php } ?>

                                      
                                      <?php
                                      if($search == 1)
                                      {
                                           ?>
                                              <span class="search-button icon-magnifier no-select hidden-phone hidden-tablet <?php if ( is_active_sidebar( 'woocommerce_dropdown_cart ' ) ) { echo "has_dropdown_cart "; } if ( class_exists( 'WooCommerce' ) && class_exists('YITH_WCWL') && epico_opt('topbar-wishlist-display') == 1 ) { echo " has_wishlist"; } ?>"></span>
                                           <?php
                                      }
                                      ?>

                                  <?php if ( $menuInContainer == 0 ) { ?> <!-- if menu be in container -->
                                     </div>
                                  <?php } ?> 

  		                    </div>	

                      <?php  } 
  		         } ?>
           
           
                  <?php
                      //Because it pushes the entire content to a side, it should be placed outside of layout element
                      get_template_part( 'templates/navigation-mobile' );

                  ?>

  	        </div>
          </header>
          <!-- Header Navigation End -->

  <?php } else if ( $headerType == 7 ) { // left menu  ?>
     
          <!-- tablet menu -->
          <header id="epHeader" class="hidden-desktop" >
              <div class="wrap headerWrap">
                  <div id="headerFirstState">
                      <div class="container clearfix">
                          <?php $logoSecond = epico_opt('logo') == "" ? EPICO_THEME_ASSETS_URI . "/content/img/logo.png" : epico_opt('logo'); ?>
                          <a class="locallink logo" href="<?php echo get_site_url(); ?>/#home">
                              <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
                              <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
                          </a>
                          <a class="externalLink logo" href="<?php echo get_site_url(); ?>">
                              <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
                              <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
                          </a>
                          <?php

                              // Check if WooCommerce is active
                              if ( $shop_cart == '1' && $catalog_mode == 0 && class_exists( 'WooCommerce' ) ) {
                                  
                                  /*  woocomerce drop down cart widget */
                                  //Because it pushes the entire content to a side, it should be placed outside of layout element
                                  get_template_part( 'templates/woocommerce/cart' );
                              }
                                  
                          ?>

                      </div>
                  </div>
          
           
                  <?php
                      //Because it pushes the entire content to a side, it should be placed outside of layout element
                      get_template_part( 'templates/navigation-mobile' );

                  ?>

              </div>
          </header>
          <!-- tablet menu End -->

      <aside class="vertical_menu_area visible-desktop left_menu hidden-tablet hidden-phone hide_menu">

          <!-- Secound Logo -->
          <?php $logoSecond = epico_opt('logo') == "" ? EPICO_THEME_ASSETS_URI . "/content/img/logo.png" : epico_opt('logo'); ?>
      
          <!-- background Image -->
          <?php $backgroundImage = epico_opt('vertical_menu_background'); ?> 
     
          <?php if ($backgroundImage) { ?>
              <div class="vertical_background_image" style="background-image:url('<?php echo esc_url($backgroundImage); ?>')"></div>    
          <?php } ?>
      
          <a class="locallink logo" href="<?php echo get_site_url(); ?>/#home">
              <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  	        <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
          </a>
          
         <a class="externalLink logo" href="<?php echo get_site_url(); ?>">
          <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  	        <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
          </a>
          
          <div class="set_nav_center">
              <div class="nav_tablecell_elemnt">
               
                  <nav class="vertical_menu_navigation">
            
                          <?php
                              wp_nav_menu(array(
  	                            'container' =>'',
  	                            'menu_class' => 'clearfix',
  	                            'before'     => '',
  	                            'theme_location' => 'primary-nav',
  	                            'walker'     => new epico_Nav_Walker(),
  	                            'fallback_cb' => false,
                                  'after' => ''
                              ));
                          ?>
                              
                  </nav>
          
              </div>
          </div>

          <div class="verticalWrapForbuttons">

              <?php   

                  // Check if WooCommerce is active
                  if ( $shop_cart == '1' && $catalog_mode == 0 && class_exists( 'WooCommerce' ) ) {
                                      
                      /*  woocomerce drop down cart widget */
                      //Because it pushes the entire content to a side, it should be placed outside of layout element
                      get_template_part( 'templates/woocommerce/cart' );
                  }
                                      
              ?>

              
              <?php
                  if($search == 1)
                  {
                          ?>
                          <span class="search-button icon-magnifier no-select hidden-phone hidden-tablet"></span>
                          <?php
                  }
              ?>

          </div>
          <?php if(epico_opt('vertical-menu-social-display') == 1) { ?>
              <!-- Footer Social Link  -->
              <div class="vertical_menu_social">
                  <?php
                      $social_icons_style = (epico_opt('vertical-menu-social-icon-style') == 1) ? 'light':'dark';
                  ?>
                  <ul class="social-icons <?php echo esc_attr($social_icons_style); ?>">
                                      
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
                          epico_socialIcon('social_paypal_url', 'icon-paypal4', 'paypal4');//paypal
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
                          epico_socialIcon('social_custom1_url', 'icon-custom1' , 'custom1');//Custom 1
      					          epico_socialIcon('social_custom2_url', 'icon-custom2' , 'custom2');//Custom 2
                      ?>
                              
                  </ul>
              </div>
          <?php } ?>
          <!-- vertical menu copyright -->
          <?php if (epico_opt('vertical_menu_copyright')) { ?>
              <div class="verticalMenuCopyRight">
                  <?php epico_eopt('vertical_menu_copyright') ?>

              </div>
          <?php } ?>
         
      </aside>

  <?php } else if ( $headerType == 8 ) { //right menu  ?>

      <!-- tablet menu -->
      <header id="epHeader" class="hidden-desktop" >
          <div class="wrap headerWrap">
              <div id="headerFirstState">
                  <div class="container clearfix">
                      <!-- Secound Logo -->
                      <?php $logoSecond = epico_opt('logo') == "" ? EPICO_THEME_ASSETS_URI . "/content/img/logo.png" : epico_opt('logo'); ?>
                      <a class="locallink logo" href="<?php echo get_site_url(); ?>/#home">
                          <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
                          <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
                      </a>
                      <a class="externalLink logo" href="<?php echo get_site_url(); ?>">
                          <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
                          <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
                      </a>
                      <?php

                          // Check if WooCommerce is active
                          if ( $shop_cart == '1' && $catalog_mode == 0 && class_exists( 'WooCommerce' ) ) {
                              
                              /*  woocomerce drop down cart widget */
                              //Because it pushes the entire content to a side, it should be placed outside of layout element
                              get_template_part( 'templates/woocommerce/cart' );
                          }
                              
                      ?>

                  </div>
              </div>
              <?php
                  //Because it pushes the entire content to a side, it should be placed outside of layout element
                  get_template_part( 'templates/navigation-mobile' );

              ?>

          </div>
      </header>
      <!-- tablet menu End -->


      <aside class="vertical_menu_area visible-desktop left_menu hidden-tablet hidden-phone hide_menu">

          <!-- Secound Logo -->
          <?php $logoSecond = epico_opt('logo') == "" ? EPICO_THEME_ASSETS_URI . "/content/img/logo.png" : epico_opt('logo'); ?>

          <!-- background Image -->
          <?php $backgroundImage = epico_opt('vertical_menu_background'); ?> 
     
          <?php if ($backgroundImage) { ?>
              <div class="vertical_background_image" style="background-image:url('<?php echo esc_url($backgroundImage); ?>')"></div>    
          <?php } ?>
      
          <a class="locallink logo" href="<?php echo get_site_url(); ?>/#home">
              <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  	        <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
          </a>
          
          <a class="externalLink logo" href="<?php echo get_site_url(); ?>">
              <?php if( $responsivelogo != '') {?><img  class="secoundLogo responsivelogo hidden-desktop" src="<?php echo esc_url($responsivelogo); ?>" alt="Logo" /><?php } ?>
  	        <img  class="secoundLogo" src="<?php echo esc_url($logoSecond); ?>" alt="Logo" />
          </a>
      
          <div class="set_nav_center">
              <div class="nav_tablecell_elemnt">
          
              <nav class="vertical_menu_navigation">
            
                      <?php
                          wp_nav_menu(array(
  	                        'container' =>'',
  	                        'menu_class' => 'clearfix',
  	                        'before'     => '',
  	                        'theme_location' => 'primary-nav',
  	                        'walker'     => new epico_Nav_Walker(),
  	                        'fallback_cb' => false,
                              'after' => ''
                          ));
                      ?>
              </nav>

              </div>  
          </div>    
          
          <div class="verticalWrapForbuttons">

              <?php   

                  // Check if WooCommerce is active
                  if ( $shop_cart == '1' && $catalog_mode == 0 && class_exists( 'WooCommerce' ) ) {
                                      
                      /*  woocomerce drop down cart widget */
                      //Because it pushes the entire content to a side, it should be placed outside of layout element
                      get_template_part( 'templates/woocommerce/cart' );
                  }
                                      
              ?>

              
              <?php
                  if($search == 1)
                  {
                          ?>
                          <span class="search-button icon-magnifier no-select hidden-phone hidden-tablet"></span>
                          <?php
                  }
              ?>

          </div>

          <?php if(epico_opt('vertical-menu-social-display') == 1) { ?>
              <!-- Footer Social Link  -->
              <div class="vertical_menu_social">
                  <?php
                      $social_icons_style = (epico_opt('vertical-menu-social-icon-style') == 1) ? 'light':'dark';
                  ?>
                  <ul class="social-icons <?php echo esc_attr($social_icons_style); ?>">
                                      
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
                          epico_socialIcon('social_paypal_url', 'icon-paypal4', 'paypal4');//paypal
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
                          epico_socialIcon('social_instagram_url', 'icon-instagram', 'instagram');//foursquare
                          epico_socialIcon('social_behance_url', 'icon-behance', 'behance');//Behance
                          epico_socialIcon('social_custom1_url', 'icon-custom1' , 'custom1');//Custom 1
      					          epico_socialIcon('social_custom2_url', 'icon-custom2' , 'custom2');//Custom 2

                      ?>
                              
                  </ul>
              </div>
          <?php } ?>

          <!-- vertical menu copyright -->
          <?php if (epico_opt('vertical_menu_copyright')) { ?>
              <div class="verticalMenuCopyRight">
                  <?php epico_eopt('vertical_menu_copyright') ?>

              </div>
          <?php } ?>

      </aside>

  <?php } ?>

  <span id="sidebar-open-overlay"></span>

  <?php

  if($search == 1)
  {
      ?>
      <div id="search-form">
          <?php
              get_search_form();
          ?>
          <span id="search-caption"><?php esc_html_e('Type and press enter', 'vitrine'); ?> </span>
      </div>
      <?php
  }
?>