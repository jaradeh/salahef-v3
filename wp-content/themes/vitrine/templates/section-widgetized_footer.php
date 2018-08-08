<?php

    $footerWidgets = epico_opt('footer_widgets');

    if($footerWidgets == 1){

        $widgetSpan1 = 12 ;
    }
    else if ($footerWidgets == 2) {

        $widgetSpan1 = 6 ;
        $widgetSpan2 = 6 ;
    }
    else if ($footerWidgets == 3) {

        $widgetSpan1 = 8 ;
        $widgetSpan2 = 4 ;
    }
    else if ($footerWidgets == 4) {

        $widgetSpan1 = 4 ;
        $widgetSpan2 = 8 ;

    }
    else if ($footerWidgets == 5 ) {
    
        $widgetSpan1 = 4 ;
        $widgetSpan2 = 4 ;
        $widgetSpan3 = 4 ;
    }
	 else if ($footerWidgets == 6 ) {

        $widgetSpan1 = 3 ;
        $widgetSpan2 = 3 ;
        $widgetSpan3 = 3 ;
        $widgetSpan4 = 3 ;
		
    } else if ($footerWidgets == 7 ) {
    
        $widgetSpan1 = 3 ;
        $widgetSpan2 = 3 ;
        $widgetSpan3 = 6 ;

    }
    else if ($footerWidgets == 8 ) {
    
        $widgetSpan1 = 6 ;
        $widgetSpan2 = 3 ;
        $widgetSpan3 = 3 ;
    }
	else if ($footerWidgets == 9 ) {
    
        $widgetSpan1 = 3 ;
        $widgetSpan2 = 3 ;
        $widgetSpan3 = 2 ;
        $widgetSpan4 = 2 ;
		$widgetSpan5 = 2 ;
		
    }else if ($footerWidgets == 10 ) {
    
        $widgetSpan1 = 2 ;
        $widgetSpan2 = 2 ;
        $widgetSpan3 = 2 ;
        $widgetSpan4 = 3 ;
		$widgetSpan5 = 3 ;
		
    }else if ($footerWidgets == 11 ) {
    
        $widgetSpan1 = 12 ;
        $widgetSpan2 = 3 ;
        $widgetSpan3 = 3 ;
        $widgetSpan4 = 3 ;
		$widgetSpan5 = 3 ;
		
    }
	  else if ($footerWidgets == 12 ) {
    
        $widgetSpan1 = 2 ;
        $widgetSpan2 = 2 ;
        $widgetSpan3 = 2 ;
        $widgetSpan4 = 2 ;
		$widgetSpan5 = 2 ;
        $widgetSpan6 = 2 ;
		
    } else if ($footerWidgets == 13 ) {
    
        $widgetSpan1 = 12 ;
        $widgetSpan2 = 3 ;
        $widgetSpan3 = 3 ;
        $widgetSpan4 = 2 ;
		$widgetSpan5 = 2 ;
        $widgetSpan6 = 2 ;
		
    } else if ($footerWidgets == 14 ) {
    
        $widgetSpan1 = 6 ;
        $widgetSpan2 = 6 ;
        $widgetSpan3 = 3 ;
        $widgetSpan4 = 3 ;
		$widgetSpan5 = 3 ;
        $widgetSpan6 = 3 ;
		
    }else if ($footerWidgets == 15 ) {
    
        $widgetSpan1 = 6 ;
        $widgetSpan2 = 6 ;
        $widgetSpan3 = 3 ;
        $widgetSpan4 = 3 ;
		$widgetSpan5 = 2 ;
        $widgetSpan6 = 2 ;
        $widgetSpan7 = 2 ;
		
    }
?>

<?php if($footerWidgets && (is_active_sidebar('footer-widget-1') || is_active_sidebar('footer-widget-2') || is_active_sidebar('footer-widget-3') || is_active_sidebar('footer-widget-4') || is_active_sidebar('footer-widget-5') || is_active_sidebar('footer-widget-6')|| is_active_sidebar('footer-widget-7'))){ ?>
<div id="footer-widget-<?php echo $footerWidgets; ?>" class=" footer-widgetized ep-section <?php if ( epico_opt('footer-widget-style') == 1 ) { ?> light <?php } ?> <?php if ( epico_opt('footer-widget-banner') ) { ?>  footer-has-banner <?php } ?><?php if ( epico_opt('footer-widget_width') == "1") { ?>fullwidth <?php } ?>">
    <div class="section-container">
        <div class="section-content-container">
            <?php if ( epico_opt('footer-widget-banner')  && epico_opt('footer-widget-gradianet') ) { ?>
                <div class="footer-widgetized-gradient">
            <?php } ?>

                    <div class="footer-widgetized-wrap wrap <?php if ( epico_opt('footer-widget_width') !== "1") { ?> container <?php } ?>">
                        <div class="clearfix">

                            <?php if ( epico_opt('footer_title') || epico_opt('footer_subtitle')  ) { ?>

                                <div class="titleSpace">
                                    <?php if ( epico_opt('footer_title') ) { ?>
                                        <div class="title"><h3><?php epico_eopt('footer_title'); ?></h3></div>
                                    <?php } if (  epico_opt('footer_subtitle') ) { ?>
                                        <div class="subtitle"><?php epico_eopt('footer_subtitle'); ?></div>
                                    <?php } ?>
                                </div>

                            <?php } ?>

                        </div>

                        <div class="clearfix">
                            <!-- widgetized Area -->
                            <div class="wpb_row vc_row-fluid">
                                <div class="wpb_column vc_column_container vc_col-sm-<?php echo esc_attr($widgetSpan1) ?>">
                                    <div class="vc_column-inner">
                                        <!--  Footer Widgetised 1 -->
                                        <?php if ( !function_exists( 'dynamic_sidebar' ) || dynamic_sidebar( 'footer-widget-1') ){}  ?>
                                    </div>
                                </div>

                                <?php if(!($footerWidgets == 1)){ ?>
								
                                <div class="wpb_column vc_column_container vc_col-sm-<?php echo esc_attr($widgetSpan2) ?>">
                                    <div class="vc_column-inner ">
                                        <!--  Footer Widgetised 2 -->
                                        <?php	if ( !function_exists( 'dynamic_sidebar' ) || dynamic_sidebar( 'footer-widget-2') ){} ?>
                                    </div>
                                </div>
                                      
                                    <?php if( $footerWidgets == 5 || $footerWidgets == 6|| $footerWidgets == 7|| $footerWidgets == 8|| $footerWidgets == 9|| $footerWidgets == 10|| $footerWidgets == 11 || $footerWidgets == 12 || $footerWidgets == 13 || $footerWidgets == 14 || $footerWidgets == 15 ) { ?>
                                        <div class="wpb_column vc_column_container vc_col-sm-<?php echo esc_attr($widgetSpan3) ?>">
                                            <div class="vc_column-inner ">
                                                <!--  Footer Widgetised 3 -->
                                                <?php	if ( !function_exists( 'dynamic_sidebar' ) || dynamic_sidebar( 'footer-widget-3') ){} ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                            
                                    <?php if( $footerWidgets == 6 ||$footerWidgets == 9 || $footerWidgets == 10 || $footerWidgets == 11|| $footerWidgets == 12 || $footerWidgets == 13 || $footerWidgets == 14|| $footerWidgets == 15  ) { ?>
                                        <div class="wpb_column vc_column_container vc_col-sm-<?php echo esc_attr($widgetSpan4) ?>">
                                            <div class="vc_column-inner ">
                                                <!--  Footer Widgetised 3 -->
                                                <?php	if ( !function_exists( 'dynamic_sidebar' ) || dynamic_sidebar( 'footer-widget-4') ){} ?>
                                            </div>
                                        </div>
                                    <?php } ?>

									<?php if( $footerWidgets == 9 || $footerWidgets == 10 || $footerWidgets == 11|| $footerWidgets == 12|| $footerWidgets == 13 || $footerWidgets == 14 || $footerWidgets == 15 ) { ?>
                                        <div class="wpb_column vc_column_container vc_col-sm-<?php echo esc_attr($widgetSpan5) ?>">
                                            <div class="vc_column-inner ">
                                                <!--  Footer Widgetised 3 -->
                                                <?php	if ( !function_exists( 'dynamic_sidebar' ) || dynamic_sidebar( 'footer-widget-5') ){} ?>
                                            </div>
                                        </div>
                                    <?php } ?>
									<?php if( $footerWidgets == 12 || $footerWidgets == 13 || $footerWidgets == 14 || $footerWidgets == 15  ) { ?>
                                        <div class="wpb_column vc_column_container vc_col-sm-<?php echo esc_attr($widgetSpan6) ?>">
                                            <div class="vc_column-inner ">
                                                <!--  Footer Widgetised 3 -->
                                                <?php	if ( !function_exists( 'dynamic_sidebar' ) || dynamic_sidebar( 'footer-widget-6') ){} ?>
                                            </div>
                                        </div>
                                    <?php } ?>
									<?php if( $footerWidgets == 15 ) { ?>
                                        <div class="wpb_column vc_column_container vc_col-sm-<?php echo esc_attr($widgetSpan7) ?>">
                                            <div class="vc_column-inner ">
                                                <!--  Footer Widgetised 3 -->
                                                <?php	if ( !function_exists( 'dynamic_sidebar' ) || dynamic_sidebar( 'footer-widget-7') ){} ?>
                                            </div>
                                        </div>
                                    <?php } ?>
									
                                <?php } ?>

                            </div>
                        </div>
                    </div>
            <?php if ( epico_opt('footer-widget-banner')  && epico_opt('footer-widget-gradianet') ) { ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>