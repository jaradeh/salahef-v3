<?php
/*-----------------------------------------------------------------------------------

    Theme Shortcodes
    
-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/*  Allowed tags for wp_kses
/*-----------------------------------------------------------------------------------*/

$GLOBALS["allowed_tags"]= array(
    'strong' => array(),
    'br' => array(),
    'em' => array(),
    'a' => array(
        'href' => array(),
        'title' => array()
    ),
);

/*-----------------------------------------------------------------------------------*/
/*  theme setting font 
/*-----------------------------------------------------------------------------------*/
$GLOBALS["available_fonts"]= array(
    $bodyFont = key((Array)json_decode(epico_opt('font-body'))),
    $navFont  = key((Array)json_decode(epico_opt('font-navigation'))),
    $headFont = key((Array)json_decode(epico_opt('font-headings'))),
    $ShortcodeFont = key((Array)json_decode(epico_opt('font-shortcode')))
);
/*-----------------------------------------------------------------------------------*/
/*  Shortcode forms ajax handler
/*-----------------------------------------------------------------------------------*/

function epico_sc_popup()
{   
    include('forms.php');
    die();
}

add_action('wp_ajax_ep_sc_popup', 'epico_sc_popup');

/*-----------------------------------------------------------------------------------*/
/*  Shortcode helpers
/*-----------------------------------------------------------------------------------*/

//Generate ID for shortcodes
function epico_sc_id($key)
{
    $globalKey = "ep_sc_$key";

    if(array_key_exists($globalKey, $GLOBALS))
        $GLOBALS[$globalKey]++;
    else
        $GLOBALS[$globalKey] = 1;

    $id    = $GLOBALS[$globalKey];
    return $key . '_' . $id;
}

/*-----------------------------------------------------------------------------------*/
/*  Separators
/*-----------------------------------------------------------------------------------*/

function epico_sc_separator( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'size'   => 'full',  // small, small-center, medium, medium-center
        'pxthickness' => '1',
        'color' => '#888',
        'separator_margin_bottom' => '30',
        'separator_margin_top' => '30',
        'border_style' => 'solid',
    ), $atts));

    $id = epico_sc_id('vc_separator');
    $class =  array();

    switch($size)
    {
        case 'small':
            $class[] = 'hr-small';
            break;
        case 'small-center':
            $class[] = 'hr-small hr-center';
            break;
        case 'extra-small':
            $class[] = 'hr-extra-small';
            break;
        case 'extra-small-center':
            $class[] = 'hr-extra-small hr-center   ';
            break;
        case 'medium':
            $class[] = 'hr-medium';
            break;
        case 'medium-center':
            $class[] = 'hr-medium hr-center';
            break;
        case 'full':
            $class[] = ' full';
            break;   
    }

    $hasStyle = '' != $color || '' != $pxthickness;
    
    if($hasStyle)
    { ?>

        <style type="text/css" media="all">

            <?php echo "hr#$id"; ?>
                {
                    border-color: <?php echo esc_attr($color); ?>;
                    margin-top: <?php echo esc_attr($separator_margin_top); ?>px;
                    margin-bottom: <?php echo esc_attr($separator_margin_bottom); ?>px;
                    border-style: <?php echo esc_attr($border_style); ?>;
                    border-width: <?php echo esc_attr($pxthickness); ?>px;
                    <?php if ($pxthickness == 1) { ?>
                        border-bottom-width:0px;         
                    <?php } ?>
                }

        </style>

    <?php
    }//if($hasStyle)

    ob_start();
    ?>

        <hr id="<?php echo esc_attr($id); ?>"  class="vc_separator <?php echo implode(' ',$class)?>" />

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Title with horizontal line
/*-----------------------------------------------------------------------------------*/

function epico_sc_title( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'title'   => 'Title',
        'title_align'   => 'separator_align_center',
        'title_font_size'   => '20',
        'title_weight'   => 'bold',
        'title_border_enable'   => 'disable',
        'pxthickness' => '1',
        'title_color' => '#464646',
        'color' => '#888',
        'separator_margin_bottom' => '30',
        'separator_margin_top' => '30',
        'border_style' => 'solid',
    ), $atts));
    
    $id = epico_sc_id('vc_text_separator');
       
    $hasStyle = '' != $color || '' != $pxthickness;
    
    if($hasStyle)
    {
    ?>
    <style type="text/css" media="all">
        <?php echo "#$id.vc_separator"; ?>
            {
            margin-top: <?php echo esc_attr($separator_margin_top); ?>px;
            margin-bottom: <?php echo esc_attr($separator_margin_bottom); ?>px;
            } 
        <?php echo "#$id.vc_separator .vc_sep_holder .vc_sep_line"; ?>
            {
                border-color: <?php echo esc_attr($color); ?>;
                border-style: <?php echo esc_attr($border_style); ?>;
            }
        <?php echo "#$id.vc_separator.separator_align_left .title , #$id.vc_separator.separator_align_right .title , #$id.vc_separator.separator_align_center .title"; ?>
            {
               border-left-color: <?php echo esc_attr($color); ?>;
               border-right-color: <?php echo esc_attr($color); ?>;
            }
        <?php echo "#$id.vc_separator .vc_sep_holder .vc_sep_line"; ?>
            {
                border-top-width: <?php echo esc_attr($pxthickness); ?>px;
                height:<?php echo esc_attr($pxthickness); ?>px;
                top:<?php echo ceil(((int)esc_attr(-($pxthickness)))/2); ?>px;
            }
        <?php echo "#$id.vc_separator .title"; ?>
            {
                font-size: <?php echo esc_attr($title_font_size); ?>px;
                font-weight: <?php echo esc_attr($title_weight); ?>; 
                color:<?php echo esc_attr($title_color); ?>;

            }
    </style>
    <?php
    }//if($hasStyle)

    ob_start();
    ?>

    <div id=<?php echo esc_attr($id); ?> class="vc_separator <?php echo esc_attr($title_align); ?> <?php echo esc_attr($title_border_enable); ?>">
        <span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
        <div class="title"><?php echo esc_attr($title); ?></div>
        <span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Team Member
/*-----------------------------------------------------------------------------------*/

function epico_sc_team_member( $atts, $content = null ) {

    extract(shortcode_atts(array(
        'name'   => 'JOHN DOE',
        'job_title' => 'Designer',
        'image'  => '',
        'signature'  => '',
        'style'  => 'dark',
        'team_color_preset'  => 'd02d48',
        'team_color'  => '#cccccc',
        'description'  => '',
        'url'    => '',
        'target' => '_self',
        'team_animation' => 'none',
        'team_animation_delay' => '0',
        'responsive_animation' => 'disable',
        'team_icon1'  => '',
        'team_icon2'  => '',
        'team_icon3'  => '',
        'team_icon4'  => '',
        'team_icon5'  => '',
        'team_icon_url1'  => '',
        'team_icon_url2'  => '',
        'team_icon_url3'  => '',
        'team_icon_url4'  => '',
        'team_icon_url5'  => '',
        'team_icon_target1'  => '_self',
        'team_icon_target2'  => '_self',
        'team_icon_target3'  => '_self',
        'team_icon_target4'  => '_self',
        'team_icon_target5'  => '_self',
    ), $atts));
    
    if (is_numeric($image)) {
        $image = wp_get_attachment_url($image);
    }

    if (is_numeric($signature)) {
        $signature = wp_get_attachment_url($signature);
    }
    
    $hasTeamIcon = '' != $team_icon1 || '' != $team_icon2  || '' != $team_icon3 || '' != $team_icon4 || '' != $team_icon5;
     
    $hasStyle = '' != $team_color || '' != $team_color_preset;
    $id     = epico_sc_id('team_member');

    ob_start();

    if($hasStyle)
    {
    ?>
    <style type="text/css" media="all">
        <?php
        $color = "";
        if(strlen(esc_attr($team_color_preset)))
        {
            if($team_color_preset == 'custom')
            {
                $color = $team_color;
            }
            else
            {
                $color = "#" . $team_color_preset;
            }
        }
        
        echo "#$id.team-member .member-line"; ?>
        {
            background-color:<?php echo esc_attr($color); ?>;
        }
        <?php
        echo "#$id.team-member:hover .member-plus"; ?>
        {
            background-color: <?php echo esc_attr($color); ?>;
        }
        <?php
        echo "#$id.team-member .icons li:hover a"; ?>
        {
            color: <?php echo esc_attr($color); ?>;
        }

    </style>
    <?php
    }//if($hasStyle)

    ob_start();
    ?>
        <div id="<?php echo esc_attr($id); ?>" class="team-member <?php if( strlen($style)) { echo esc_attr($style);  } ?> <?php if($team_animation != 'none') { echo 'shortcodeAnimation'; if($responsive_animation != '') { echo ' no-responsive-animation';} } ?>"  <?php if($team_animation != 'none') { ?> data-delay="<?php echo esc_attr($team_animation_delay); ?>" data-animation="<?php echo esc_attr($team_animation); ?>" <?php } ?>>

            <?php if($image)
            {
            ?>
            <div class="member-pic-container">

                <div class="member-line"></div>

                <div class="member-pic">
                    <?php if($image)
                        {
                        ?>
                            <img class ="bg-image" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($name) ?>">
                        <?php
                        }
                    ?>
                </div>

                <div class="member-plus">
                    <span class="member-plus-line"></span>
                </div>
                            
                <?php if ($hasTeamIcon) { ?>
                        <ul class="icons">
                           
                            <?php if ($team_icon1) { ?>
                                <li>
                                    <a href="<?php echo esc_url($team_icon_url1); ?>" title="<?php echo esc_attr($job_title); ?>" <?php if($team_icon_target1 != '') {?> target="<?php echo esc_attr($team_icon_target1); ?>"<?php } ?>>
                                        <span class="icon-<?php echo esc_attr($team_icon1); ?>" ></span>
                                    </a>
                                </li>
                             <?php } ?>
                             
                            <?php if ($team_icon2) { ?>
                                <li>
                                    <a href="<?php echo esc_url($team_icon_url2); ?>" title="<?php echo esc_attr($job_title); ?>" <?php if($team_icon_target2 != '') {?> target="<?php echo esc_attr($team_icon_target2); ?>"<?php } ?>>
                                        <span class="icon-<?php echo esc_attr($team_icon2); ?>" ></span>
                                    </a>
                                </li>
                            <?php } ?>
                            
                            <?php if ($team_icon3) { ?>
                                <li>
                                    <a href="<?php echo esc_url($team_icon_url3); ?>" title="<?php echo esc_attr($job_title); ?>" <?php if($team_icon_target3 != '') {?> target="<?php echo esc_attr($team_icon_target3); ?>"<?php } ?>>
                                        <span class="icon-<?php echo esc_attr($team_icon3); ?>" ></span>
                                    </a>
                                </li>
                            <?php } ?>
                            
                            <?php if ($team_icon4) { ?>
                                <li>
                                    <a href="<?php echo esc_url($team_icon_url4); ?>" title="<?php echo esc_attr($job_title); ?>" <?php if($team_icon_target4 != '') {?>target="<?php echo esc_attr($team_icon_target4); ?>"<?php } ?>>
                                        <span class="icon-<?php echo esc_attr($team_icon4); ?>" ></span>
                                    </a>
                                </li>
                            <?php } ?>
                            
                            <?php if ($team_icon5) { ?>
                                <li>
                                    <a href="<?php echo esc_url($team_icon_url5); ?>" title="<?php echo esc_attr($job_title); ?>" <?php if($team_icon_target5 != '') {?>target="<?php echo esc_attr($team_icon_target5); ?>"<?php } ?>>
                                        <span class="icon-<?php echo esc_attr($team_icon5); ?>" ></span>
                                    </a>
                                </li> 
                            <?php } ?>
                            
                        </ul>
                <?php } ?>

                <div class="overlay"></div>
            </div>

            <div class="member-info">

                 <span class="member-name"> <?php echo esc_attr($name); ?></span>

                <cite><?php echo esc_attr($job_title); ?></cite>

                <div class="member-description">

                    <p><?php echo wp_kses( $description, $GLOBALS["allowed_tags"] ); ?></p>

                    <?php
                    if($url && function_exists( 'vc_build_link' ))
                    {
                        $link = vc_build_link( $atts['url'] );
                        if ( strlen( $link['url'] ) ) { 
                        ?>
                        <a href="<?php echo esc_url($link['url']); ?>" <?php if($link['target'] != '') {?>target="<?php echo esc_attr( $link['target'] ); ?>"<?php } ?> class="more-link-arrow" title="<?php echo esc_attr($link['title']); ?>">[ <?php echo esc_attr($link['title']); ?> ]</a>
                        <?php
                        }
                    }
                    ?>

                    <?php if('' != esc_url($signature)){ ?>

                        <div class="signature">

                            <img src="<?php echo esc_url($signature); ?>" alt="<?php echo esc_attr($name) ?>">

                        </div>

                    <?php } ?>
                    
                </div>

            </div>

            <?php
            }
            ?>

        </div>
    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/* Testimonials shortcode 
/*-----------------------------------------------------------------------------------*/

function epico_sc_testimonial ($atts, $content = null) {
	$class =  '';
    extract(shortcode_atts(array(
		"layout" =>"style1",
        "style"         => "Dark",
        "testimonial_color_preset"          => "2e2e2e",
		'visible_items' => '1',
        "testimonial_color"         => "",
        "testimonial_animation"     => "none",
        "testimonial_animation_delay" => "0",
        "responsive_animation" => "disable",
    ), $atts));

	if ($layout == 'style1')
		$class .= "testimonials";
	
	if ($layout == 'style2')
		$class .= "carousel";
	
	
	$class .= ' testimonials-' . $layout;
	$class .= ' testimonials-' . $visible_items;

    $animation_params = "";
    $testimonialWithAnimation = '';
    
    if( $testimonial_animation != 'none') {
        $testimonialWithAnimation = ' shortcodeAnimation';
        $animation_params = " data-delay='" . esc_attr($testimonial_animation_delay) . "' data-animation='" . esc_attr($testimonial_animation) . "'";
    
        if($responsive_animation != '')
        {
            $testimonialWithAnimation .= ' no-responsive-animation';
        }
    };

    $id = epico_sc_id('testimonial');
    
    $hasStyle = '' != $testimonial_color_preset || '' != $testimonial_color ;
   
	if($layout == 'style1'){
    if($hasStyle)
    {
    ?>
    <style type="text/css" media="all">
        <?php
        $color = "";
        if(strlen(esc_attr($testimonial_color_preset)))
        {
            if($testimonial_color_preset == 'custom')
            {
                $color = $testimonial_color;
            }
            else
            {
                $color = "#" . $testimonial_color_preset;
            }
        }
        
        echo "#$id.testimonials:after"; ?>
        {
            border-top: 3px solid <?php echo esc_attr($color); ?>;
        }
        <?php
        echo "#$id.testimonials:before"; ?>
        {
            border-top: 3px solid <?php echo esc_attr($color); ?>;
        }
        <?php
        echo "#$id.testimonials .quot-icon"; ?>
        {
            background-color:<?php echo esc_attr($color); ?>;
        }   
        <?php
        echo "#$id.testimonials .quot-icon:before"; ?>
        {
            border-top-color:<?php echo esc_attr($color); ?>;
            border-bottom-color:<?php echo esc_attr($color); ?>;
            border-left-color:<?php echo esc_attr($color); ?>;
        }  
        <?php
        echo "#$id.testimonials .quot-icon:after"; ?>
        {
            border-bottom-color:<?php echo esc_attr($color); ?>;
        } 
        
    </style>
    <?php
    }//if($hasStyle)
	}
    ob_start();
    
    ?> 

    <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?> <?php echo esc_attr($testimonialWithAnimation); if ($style == 'light') { echo ' skin-light'; }?>" data-id="<?php echo esc_attr($id);?>" <?php echo esc_attr($animation_params); ?>>
        <?php if ($layout == 'style1'){  ?>
        <div class="quot-icon-container">
            <span class="quot-icon"></span>
            <span class="quot-icon"></span>
        </div>
		<?php } ?>
        <div class="swiper-container swiper-container-<?php echo esc_attr($id)?> clearfix"<?php  if($layout == 'style2'){ ?> data-visibleitems="<?php if (strlen($visible_items)) { echo esc_attr($visible_items); }?>" <?php } ?>>
            <div class="swiper-wrapper">
                <?php
				
				echo do_shortcode($content);  ?>
            </div>
        </div>

        <!-- Next Arrows -->
        <div class="arrows-button-next no-select arrows-button-next-<?php echo esc_attr($id)?>">
             <?php if ($layout == 'style1'){ ?>
            <span class="text">
                <?php esc_html_e('NEXT', 'vitrine'); ?>
            </span>
			 <?php } ?> 
        </div>

        <!-- Arrows divider -->
        <div class="arrow-button-divider"></div>

        <!-- Prev Arrows -->
        <div class="arrows-button-prev no-select arrows-button-prev-<?php echo esc_attr($id)?>">
             <?php if ($layout == 'style1'){ ?>
            <span class="text">
                <?php esc_html_e('PREV', 'vitrine'); ?>
            </span>
			  <?php } ?> 
        </div>

    </div>

    <?php
    
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/* Testimonial item shortcode 
/*-----------------------------------------------------------------------------------*/

function epico_sc_testimonial_item ($atts, $content = null) {

    extract(shortcode_atts(array(
        "author"         => "",
        "text"         => "",
        "job"         => "",
        "image_url"         => "",
    ), $atts));
    
    $html = $authorimg = "";
    if (is_numeric($image_url)) {
        $authorimg = wp_get_attachment_url($image_url);
    }

    ob_start();

    ?>

    <div class="swiper-slide testimonial">
        <div class="quote">
		
		<blockquote><?php echo esc_attr($text); ?> </blockquote>
            <div class="head">
                <div class="author-image" style="background-image:url(<?php echo esc_attr($authorimg); ?>)"></div>
                <div class="author">
                    <h4 class="name"><?php echo esc_attr($author); ?> </h4>
                    <cite class="job"><?php echo esc_attr($job); ?> </cite>
                </div>
            </div>
			
        </div>                 
    </div>

    <?php

    return ob_get_clean();
}



/*-----------------------------------------------------------------------------------*/
/*  Pie Chart
/*-----------------------------------------------------------------------------------*/

function epico_sc_piechart($atts ,$content=null)
{
    extract(shortcode_atts(array(
        'title'    => '',
        'title_color'    => '',
        'subtitle' => '',
        'subtitle_color' => '',
        'piechart_percent' => '70',
        'piechart_percent_display' => 'enable',
        'piechart_color_preset' => 'd02d48',
        'piechart_color'=> '',
        'main_color'=> '',
        'piechart_icon' => '',
        'piechart_animation' => 'none',
        'piechart_animation_delay' => '0',
        'responsive_animation' => 'disable',
    ), $atts));

    if($piechart_color_preset !=  'custom')
    {
        $piechart_color = "#". $piechart_color_preset;
    }

    $hasTitle   = '' != $title;
    $class = "pieChart easyPieChart";
    $pieChartWithAnimation = '';
    $id = epico_sc_id('piechart');
    $responsiveid = epico_sc_id('piechartResponsive');


    if($piechart_animation != 'none')
    {
        $pieChartWithAnimation = ' shortcodeAnimation';

        if($responsive_animation != '')
        {
            $pieChartWithAnimation .= ' no-responsive-animation';
        }    
    }


    ob_start();
    ?>

    <div id="<?php echo esc_attr($id); ?>" class="pieChartBox <?php echo esc_attr($pieChartWithAnimation); ?> <?php if($piechart_percent_display == 'disable'){?> disablepercent <?php } ?>" <?php if(strlen(esc_attr($piechart_animation))) { ?> data-delay="<?php echo esc_attr($piechart_animation_delay); ?>" data-animation="<?php echo esc_attr($piechart_animation); ?>" <?php } ?> <?php if(esc_attr($piechart_color)){ ?> data-barColor=<?php echo esc_attr($piechart_color); }?>>
        <div class="<?php if($piechart_icon){?>iconPchart <?php } ?><?php echo esc_attr($class); ?>" data-percent="<?php echo esc_attr($piechart_percent); ?>">
            <?php if($piechart_icon){?><span class="icon icon-<?php echo esc_attr($piechart_icon); ?>" <?php if (strlen(esc_attr($main_color))) { ?> style="color:<?php echo esc_attr($main_color); ?>;" <?php } ?>></span><?php } ?>
            <?php if($piechart_percent_display == 'enable'){?><span class="perecent" <?php if (strlen(esc_attr($main_color))) { ?> style="color:<?php echo esc_attr($main_color); ?>;" <?php } ?>><?php echo esc_attr($piechart_percent); ?><span class="piechart_percent">%</span></span><?php } ?>
            <div class="dot-container">
                <div class="dot" <?php if (strlen(esc_attr($main_color))) { ?> style="background-color:<?php echo esc_attr($main_color); ?>;" <?php } ?>></div>
            </div>
        </div>
        <p class="title" <?php if (strlen(esc_attr($title_color))) { ?> style="color:<?php echo esc_attr($title_color); ?>;" <?php } ?>><?php echo esc_attr($title); ?></p>
        <p class="subtitle" <?php if (strlen(esc_attr($subtitle_color))) { ?> style="color:<?php echo esc_attr($subtitle_color); ?>;" <?php } ?>><?php echo esc_attr($subtitle); ?></p>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Horizontal progress bar
/*-----------------------------------------------------------------------------------*/

function epico_sc_progressbar($atts ,$content=null)
{
    extract(shortcode_atts(array(
        'title'    => '',
        'title_color'    => '',
        'percent' => '50',
        'active_bg_color' => '',
        'inactive_bg_color' => '',
        'progressbar_animation' => 'none',
        'progressbar_animation_delay' => '0',
        'responsive_animation' => 'disable',
    ), $atts));

    $hasStyle = '' != $title_color || '' != $active_bg_color || '' != $inactive_bg_color ;
    $id = epico_sc_id('progressbar');
    $progressbarWithAnimation = '';

    if($progressbar_animation != 'none')
    {
        $progressbarWithAnimation = ' shortcodeAnimation';

        if($responsive_animation != '')
        {
            $progressbarWithAnimation .= ' no-responsive-animation';
        }
    }
     
    ob_start();
    if($hasStyle)
    {
    ?>
    <style type="text/css" media="all">
        <?php if(strlen(esc_attr($title_color)))
        {
            echo "#$id.progress_bar  .progress_title "; ?>
            {
                color: <?php echo esc_attr($title_color); ?>;
            }

            <?php echo "#$id.progress_bar  .progress_percent_value"; ?>
            {
                color: <?php echo esc_attr($title_color); ?>;
            }

        <?php
        }
        if(strlen(esc_attr($active_bg_color)))
        {
            echo "#$id.progress_bar .progressbar_percent, #$id.progress_bar .progressbar_percent:after"; ?>
            {
                background-color: <?php echo esc_attr($active_bg_color); ?>;
            }
        <?php
        }

        if(strlen(esc_attr($inactive_bg_color)))
        {
            echo "#$id.progress_bar .progressbar_holder:after"; ?>
            {
                background-color: <?php echo esc_attr($inactive_bg_color); ?>;
            }
        <?php
        }
        ?>
    </style>
    <?php
    }
    ?>

    <div id="<?php echo esc_attr($id); ?>"  class="progress_bar <?php echo esc_attr($progressbarWithAnimation); ?>" data-delay="<?php echo esc_attr($progressbar_animation_delay); ?>" data-animation="<?php echo esc_attr($progressbar_animation); ?>">
        <div class="progressbar_holder">
            <?php if($title){?><span class="progress_title" ><?php if(esc_attr($title)){ echo esc_attr($title); } ?></span><?php } ?>
            <span class="progress_percent_value" ><?php echo esc_attr($percent); ?>%</span>
            <div class="progressbar_percent" data-percentage="<?php if(esc_attr($percent)){ echo esc_attr($percent); } ?>" ></div>
        </div>
    </div>
    
    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Social Icon 
/*-----------------------------------------------------------------------------------*/

function epico_sc_socialIocn ($atts, $content=null)
{
    extract(shortcode_atts(array(
        'sociallink_url' => '',
        'sociallink_type'=> 'facebook',
        'sociallink_style' => 'dark',
        'sociallink_image' => '',
        'sociallink_color' => '',
    ), $atts));

    $id = epico_sc_id('socialIcon');
    if (is_numeric($sociallink_image)) {
        $sociallink_image = wp_get_attachment_url($sociallink_image);
    }
    
    if(esc_attr($sociallink_type)=='custom') {
        $sociallink_type= $id; ?>

        <style type="text/css" media="all">
            .<?php echo esc_attr($sociallink_type); ?> a:before {
                    background: <?php echo esc_url($sociallink_color); ?> !important;
                }
                span.icon.icon-<?php echo esc_attr($sociallink_type); ?>{
                    background-image: url('<?php echo esc_url($sociallink_image); ?>') !important;
                }
        </style>

    <?php
   }//if social network name was custom

    ob_start();

    ?>
    
    <div class="socialLinkShortcode iconstyle <?php echo esc_attr($sociallink_type); ?> <?php echo esc_attr($sociallink_style); ?>">
        <a id="<?php echo esc_attr($id); ?>" href="<?php echo esc_url($sociallink_url); ?>" target="_blank">
            <span class="icon icon-<?php echo esc_attr($sociallink_type); ?>"></span>
        </a>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Social Link 
/*-----------------------------------------------------------------------------------*/

function epico_sc_socialLink ($atts, $content=null)
{
    extract(shortcode_atts(array(
        'sociallink_url' => '',
        'sociallink_type'=> 'facebook',
        'sociallink_style' => 'dark',
        'sociallink_color' => '',
        'sociallink_text' => '',
    ), $atts));
    
    $id = epico_sc_id('socialLink');

    switch($sociallink_type)
    {
        case 'facebook':
            $sociallink_text = 'facebook';
            break;
        case 'twitter':
            $sociallink_text = 'Twitter';
            break;
        case 'vimeo':
            $sociallink_text = 'Vimeo';
            break;
        case 'youtube-play':
            $sociallink_text = 'YouTube';
            break;
        case 'google-plus':
            $sociallink_text = 'Google+';
            break;
        case 'dribbble':
            $sociallink_text = 'Dribbble';
            break;
        case 'tumblr':
            $sociallink_text = 'Tumblr';
            break;
        case 'linkedin':
            $sociallink_text = 'LinkedIn';
            break;
        case 'flickr':
            $sociallink_text = 'Flickr';
            break;
        case 'forrst':
            $sociallink_text = 'Forrst';
            break;
        case 'github':
            $sociallink_text = 'GitHub';
            break;
        case 'lastfm':
            $sociallink_text = 'Last.FM';
            break;
        case 'paypal':
            $sociallink_text = 'PaypPal';
            break;
        case 'feed':
            $sociallink_text = 'RSS';
            break;
        case 'wordpress':
            $sociallink_text = 'WordPress';
            break;
        case 'skype':
            $sociallink_text = 'Skype';
            break;
        case 'yahoo':
            $sociallink_text = 'Yahoo';
            break;
       case 'steam':
            $sociallink_text = 'Steam';
            break;
       case 'reddit-alien':
            $sociallink_text = 'Reddit';
            break;
       case 'stumbleupon':
            $sociallink_text = 'StumbleUpon';
            break;
        case 'pinterest':
            $sociallink_text = 'Pinterest';
            break;
        case 'deviantart':
            $sociallink_text = 'DeviantArt';
            break;
        case 'xing':
            $sociallink_text = 'Xing';
            break;
        case 'blogger':
            $sociallink_text = 'Blogger';
            break;
        case 'soundcloud':
            $sociallink_text = 'SoundCloud';
            break;
        case 'delicious':
            $sociallink_text = 'Delicious';
            break;
        case 'foursquare':
            $sociallink_text = 'Foursquare';
            break;
        case 'instagram':
            $sociallink_text = 'Instagram';
            break;
        case 'behance':
            $sociallink_text = 'Behance';
            break;
        case 'custom':
            $sociallink_text = esc_attr($sociallink_text);
            break;
        default : 
            $sociallink_text = 'facebook';
    }

    if(esc_attr($sociallink_type)=='custom') {
        $sociallink_type= $id; ?>

        <style type="text/css" media="all">
            .<?php echo esc_attr($sociallink_type); ?> a:before {
                background: <?php echo esc_url($sociallink_color); ?> !important;
            } 
        </style>

    <?php  }//if social network name was custom
  
    ob_start();
    
    ?>

    <div class="socialLinkShortcode textstyle <?php echo esc_attr($sociallink_type); ?> <?php echo esc_attr($sociallink_style); ?>">
        <a id="<?php echo esc_attr($id); ?>" href="<?php echo esc_url($sociallink_url); ?>" target="_blank">
            <span><?php echo esc_attr($sociallink_text); ?></span>
        </a>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Text-Box
/*-----------------------------------------------------------------------------------*/

function epico_sc_textbox($atts)
{
    extract(shortcode_atts(array(

        'title'  => '',
        'title_color'  => '',
        'subtitle'  => '',
        'subtitle_color'  => '',
        'title_fontsize'  => '20',
        'text_title_border'  => 'none',
        'text_border_underline_color' => '',
        'text_under_align'  => 'left',
        'content_align'  => 'left',
        'text_content'  => '',
        'text_content_color'  => '',
        'content_fontsize'  => '12',
		'font_type'  => 'default',
        'google_fonts'  => '',
        'text_animation'  => 'none',
        'text_animation_delay'  => '0',
        'responsive_animation'  => 'disable',
        'content'  => '',
        'url'  => '',
        'target' => '_self',

    ), $atts));

    $hasTitle  = '' != $title || '' != $subtitle;
    $titleIsLink = '' != $url;
    $hasContent  = '' != $text_content;
    $hasStyle = '' != $title_color || '' != $text_border_underline_color || '' != $text_content_color || '' != $subtitle_color || '' ;

    $id     = epico_sc_id('textbox');
    $class  = array();
    $class[] = 'fontSize'.$title_fontsize;
    $contentFSClass = 'contentfs'.$content_fontsize;

    switch($content_align)
    {
        case 'right':
            $class[] = 'textBoxRight';
            break;
        case 'center':
            $class[] = 'textBoxCenter';
            break;
        case 'left':
        default:
            $class[] = 'textBoxleft';

    }

    switch($text_under_align)
    {
        case 'right':
            $class[] = 'textBoxUnderRight';
            break;
        case 'center':
            $class[] = 'textBoxUnderCenter';
            break;
        case 'left':
        default:
            $class[] = 'textBoxUnderleft';

    }
    
    switch($text_title_border)
    {
        case 'right_border':
            $class[] = 'textRightBorder';
            break; 
        case 'left_border':
            $class[] = 'textLeftBorder';
            break;
        case 'top_border':
            $class[] = 'textTopBorder';
            break;
        case 'bottom_border':
            $class[] = 'textBottomBorder';
            break;
        case 'none':
        default:
            $class[] = 'textBoxNoStyle';
    }
    
    if( $text_animation != 'none') {

        $class[] = 'shortcodeAnimation';
        if($responsive_animation != '')
        {
            $class[] = 'no-responsive-animation';
        }
    }
$font_family = '';
    if($font_type != 'default')
    {

        /*---------Google fonts--------*/
        $pos = strpos($google_fonts, '%3A');
        if ($pos !== false) { 
            $font_family = substr($google_fonts, 0, $pos);
            $font_family= str_replace('font_family:','',$font_family);
            $font_family=  str_replace('%20',' ',$font_family);          
        }
        $pos = strpos($google_fonts, 'font_style');
        if ($pos !== false) { 
            $font_weight = substr($google_fonts,$pos);
            $font_weight = strtok($font_weight,'%');
            $font_weight = str_replace('font_style:','',$font_weight);     
        }
        $pos = strripos($google_fonts, '%3A');
        if ($pos !== false) { 
            $font_style = substr($google_fonts,$pos); 
            $font_style = str_replace('%3A','',$font_style);
        }
        $pos = strpos($google_fonts, '|');
        if ($pos !== false) {
            $google_fonts=substr($google_fonts, 0, $pos);
            $google_fonts=str_replace('font_family:','',$google_fonts);
        }

        //If the font is not already loaded to the page then load it to the body      
        //montserrat and Poppins are theme default fonts;
        $availableFonts=array($GLOBALS["available_fonts"]);
        if ( $google_fonts != '' && !(in_array($font_family,$availableFonts))){
             wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class($google_fonts), '//fonts.googleapis.com/css?family=' .$google_fonts);
        }

        /*------End of google fonts-----*/
    }

    ob_start();

    if($hasStyle)
    {
    ?>
    <style type="text/css" media="all">
        <?php if(strlen(esc_attr($text_border_underline_color)))
        {
            echo "#$id.textBox.textRightBorder .title"; ?>
            {
                border-color: <?php echo esc_attr($text_border_underline_color); ?>;
            }

            <?php
            echo "#$id.textBox.textLeftBorder .title "; ?>
            {
                border-color: <?php echo esc_attr($text_border_underline_color); ?>;
            }

            <?php 
            echo "#$id.textBox.textTopBorder hr"; ?>
            {
                background-color: <?php echo esc_attr($text_border_underline_color); ?>;
            }
            
            <?php 
            echo "#$id.textBox.textBottomBorder hr"; ?>
            {
                background-color: <?php echo esc_attr($text_border_underline_color); ?>;
            }
            
        <?php
        }
		if(strlen(esc_attr($font_family))) 
        {
            echo "#$id .title"; ?>
            {
				font-family:'<?php echo esc_attr($font_family); ?>'!important;
				font-weight:<?php echo esc_attr($font_weight); ?>;
				font-style:<?php echo esc_attr($font_style); ?>;
				
                
            }
        <?php
        }if(strlen(esc_attr($font_family))) 
        {
             echo "#$id .subtitle";  ?>
            {
				font-family:'<?php echo esc_attr($font_family); ?>'!important;
				font-weight:<?php echo esc_attr($font_weight); ?>;
				font-style:<?php echo esc_attr($font_style); ?>;
				
                
            }
        <?php
        }if(strlen(esc_attr($font_family))) 
        {
            echo "#$id .text"; ?>
            {
				font-family:'<?php echo esc_attr($font_family); ?>'!important;
				font-weight:<?php echo esc_attr($font_weight); ?>;
				font-style:<?php echo esc_attr($font_style); ?>;
				
                
            }
        <?php
        }
        ?>
    </style>
    <?php
    }//if($hasStyle)
    ?>

    <div id="<?php echo esc_attr($id); ?>" class="textBox  <?php if( strlen(esc_attr($subtitle)) == 0 ) { ?>  nosubtitle  <?php } ?>  <?php echo implode(' ', $class); ?>"  <?php if(strlen(esc_attr($text_animation))) { ?> data-delay="<?php echo esc_attr($text_animation_delay); ?>" data-animation="<?php echo esc_attr($text_animation); ?>" <?php } ?> >
        
        <?php if($hasTitle) { ?>

                <?php if($titleIsLink){ ?>
                    
                    <div class="clearfix">
                        <div class="title" <?php if(strlen(esc_attr($title_color))){ ?> style="color:<?php echo esc_attr($title_color); ?>;" <?php } ?>>
                            
                                <?php if( $text_title_border == "top_border" ){ ?>
                                    <!-- top border -->
                                    <hr />
                                <?php } ?>

                                 <a href="<?php echo esc_url($url); ?>" <?php if($target != '') {?>target="<?php echo esc_attr($target); ?>"<?php } ?>>
                                    <?php echo wp_kses( $title, $GLOBALS["allowed_tags"] ); ?>
                                 </a>
                                           
                                <?php if(strlen(esc_attr($subtitle))) { ?><span class="subtitle" <?php if (strlen(esc_attr($subtitle_color))) { ?> style="color:<?php echo esc_attr($subtitle_color); ?>;" <?php } ?>>
                                    <?php echo esc_attr($subtitle); ?></span>
                                <?php } ?>

                                <?php if( $text_title_border == "bottom_border" ){ ?>
                                    <!-- bottom border -->
                                    <hr />
                                <?php } ?>
                        </div>
                    </div>

                <?php } else { ?>
                     
                    <div class="clearfix">
                        <div class="title clearfix" <?php if(strlen(esc_attr($title_color))){ ?> style="color:<?php echo esc_attr($title_color); ?>;" <?php } ?>>
                            
                            <?php if( $text_title_border == "top_border" ){ ?>
                                <!-- top border -->
                                <hr />
                            <?php } ?>

                            <?php echo wp_kses( $title, $GLOBALS["allowed_tags"] ); ?>

                            <?php if(strlen(esc_textarea($subtitle))) { ?>
                                <!-- subtitle -->
                                <span class="subtitle" <?php if(strlen(esc_attr($subtitle_color))){ ?> style="color:<?php echo esc_attr($subtitle_color); ?>;" <?php } ?>><?php echo esc_attr($subtitle); ?></span>
                            <?php } ?>

                            <?php if( $text_title_border == "bottom_border" ){ ?>
                                <!-- bottom border -->
                                <hr />
                            <?php } ?>
                        
                        </div>
                    </div>

                <?php } ?>

        <?php } ?>
        
        <?php if($hasContent){ ?>
            <div class="<?php echo esc_attr($contentFSClass); ?> text" <?php if(strlen(esc_attr($text_content_color))) { ?> style="color: <?php echo esc_attr($text_content_color); ?>;" <?php } ?>><?php echo wp_kses( $text_content, $GLOBALS["allowed_tags"] ); ?></div>
        <?php } ?>
        
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Custom textbox
/*-----------------------------------------------------------------------------------*/

function epico_sc_custom_textbox($atts)
{
    extract(shortcode_atts(array(

        'title'  => '',
        'subtitle'  => '',
        'title_color'  => '',
        'subtitle_color'  => '',
        'title_fontsize'  => '20',
        'text_align'  => 'left',
        'text_title_border'  => 'none',
        'text_border_underline_color' => '',
        'text_under_align'  => 'left',
        'text_content'  => '',
        'text_content_color'  => '',
        'content_fontsize'  => '12',
        'content'  => '',
        'content_border_width'  => '',
        'url'  => '',
        'target' => '_self',
        'bg_color'  => '',
        'border_color'  => '',

    ), $atts));

    $hasTitle  = '' != $title || '' != $subtitle ;
    $titleIsLink = '' != $url;
    $hasContent  = '' != $text_content;
    $hasStyle = '' != $title_color || '' != $text_border_underline_color || '' != $text_content_color || ''  != $subtitle_color || '' != $border_color || ''!= $bg_color || '' ;

    $id     = epico_sc_id('textbox');
    $class  = array();
    $class[] = 'fontSize'.$title_fontsize;
    $contentFSClass = 'contentfs'.$content_fontsize;

    switch($text_align)
    {
        case 'right':
            $class[] = 'textBoxRight';
            break;
        case 'center':
            $class[] = 'textBoxCenter';
            break;
        case 'left':
        default:
            $class[] = 'textBoxleft';

    }

    switch($text_under_align)
    {
        case 'right':
            $class[] = 'textBoxUnderRight';
            break;
        case 'center':
            $class[] = 'textBoxUnderCenter';
            break;
        case 'left':
        default:
            $class[] = 'textBoxUnderleft';

    }
    
    switch($text_title_border)
    {
        case 'right_border':
            $class[] = 'textRightBorder';
            break; 
        case 'left_border':
            $class[] = 'textLeftBorder';
            break;
        case 'top_border':
            $class[] = 'textTopBorder';
            break;
        case 'bottom_border':
            $class[] = 'textBottomBorder';
            break;
        case 'none':
        default:
            $class[] = 'textBoxNoStyle';
    }

    ob_start();

    if($hasStyle)  { ?>

    <style type="text/css" media="all">
        <?php if(strlen(esc_attr($text_border_underline_color))) {

            echo "#$id .textBox.textRightBorder .title"; ?>
            {
                border-color: <?php echo esc_attr($text_border_underline_color); ?>;
            }

            <?php
            echo "#$id .textBox.textLeftBorder .title "; ?>
            {
                border-color: <?php echo esc_attr($text_border_underline_color); ?>;
            }

            <?php 
            echo "#$id .textBox.textTopBorder hr"; ?>
            {
                background-color: <?php echo esc_attr($text_border_underline_color); ?>;
            }
            
            <?php 
            echo "#$id .textBox.textBottomBorder hr"; ?>
            {
                background-color: <?php echo esc_attr($text_border_underline_color); ?>;
            }

        <?php } ?>
              
        <?php  if(strlen(esc_attr($border_color))) {
                   
            echo  "#$id .textBox .frame div"; ?>
            {
                background-color: <?php echo esc_attr($border_color); ?>;  
            }

        <?php } ?>

        <?php  if(strlen(esc_attr($content_border_width))) {
                   
            echo  "#$id.custom-textbox .frame.top div, #$id.custom-textbox .frame.bottom div"; ?>
            {
                height: <?php echo esc_attr($content_border_width); ?>;  
            } 

            <?php 

            echo  "#$id.custom-textbox .frame.right div, #$id.custom-textbox .frame.left div"; ?>
            {
                width: <?php echo esc_attr($content_border_width); ?>;  
            }

        <?php } ?>

    </style>
    <?php
    }//if($hasStyle)
?>

    <div id="<?php echo esc_attr($id); ?>" class="custom-textbox">
        <div class="custom-textbox-bg" <?php if (strlen(esc_attr($bg_color))) { ?> style="background-color:<?php echo esc_attr($bg_color); ?>" <?php } ?>></div>
        <div  class="textBox <?php if( strlen(esc_attr($subtitle)) == 0 ) { ?>  nosubtitle  <?php } ?> <?php echo implode(' ', $class); ?>">
        <div class="frame top">
            <div></div>
        </div>
        <div class="frame right">
            <div></div>
        </div>
        <div class="frame bottom">
            <div></div>
        </div>
        <div class="frame left">
            <div></div>
        </div>   
            <?php if($hasTitle) { ?>

                    <?php if($titleIsLink){ ?>
                        
                        <div class="clearfix">
                            <div class="title" <?php if(strlen(esc_attr($title_color))){ ?> style="color:<?php echo esc_attr($title_color); ?>;" <?php } ?>>
                                
                                    <?php if( $text_title_border == "top_border" ){ ?>
                                        <!-- top border -->
                                        <hr />
                                    <?php } ?>

                                     <a href="<?php echo esc_url($url); ?>" <?php if($target != '') {?>target="<?php echo esc_attr($target); ?>"<?php } ?>>
                                        <?php echo wp_kses( $title, $GLOBALS["allowed_tags"] ); ?>
                                     </a>          
                                               
                                    <?php if(strlen(esc_attr($subtitle))) { ?>
                                        <span class="subtitle" <?php if(strlen(esc_attr($subtitle_color))){ ?> style="color:<?php echo esc_attr($subtitle_color); ?>;" <?php } ?>><?php echo esc_attr($subtitle); ?></span>
                                    <?php } ?>

                                    <?php if( $text_title_border == "bottom_border" ){ ?>
                                        <!-- bottom border -->
                                        <hr />
                                    <?php } ?>
                            </div>
                        </div>

                    <?php } else { ?>
                         
                        <div class="clearfix">
                            <div class="title clearfix" <?php if(strlen(esc_attr($title_color))){ ?> style="color:<?php echo esc_attr($title_color); ?>;" <?php } ?>>
                                
                                <?php if( $text_title_border == "top_border" ){ ?>
                                    <!-- top border -->
                                    <hr />
                                <?php } ?>

                                <?php echo wp_kses( $title, $GLOBALS["allowed_tags"] ); ?>

                                <?php if(strlen(esc_textarea($subtitle))) { ?>
                                    <!-- subtitle -->
                                    <span class="subtitle" <?php if(strlen(esc_attr($subtitle_color))){ ?> style="color:<?php echo esc_attr($subtitle_color); ?>;" <?php } ?>><?php echo esc_attr($subtitle); ?></span>
                                <?php } ?>

                                <?php if( $text_title_border == "bottom_border" ){ ?>
                                    <!-- bottom border -->
                                    <hr />
                                <?php } ?>
                            
                            </div>
                        </div>

                    <?php } ?>

            <?php } ?>
            
            <?php if($hasContent){ ?>
                <div class="<?php echo esc_attr($contentFSClass); ?> text" <?php if(strlen(esc_attr($text_content_color))) { ?> style="color: <?php echo esc_attr($text_content_color); ?>;" <?php } ?>><?php echo wp_kses( $text_content, $GLOBALS["allowed_tags"] ); ?></div>
            <?php } ?>
            
        </div>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Custom Title 
/*-----------------------------------------------------------------------------------*/

function epico_sc_customTitle($atts)
{
    extract(shortcode_atts(array(
        'title'  => '',
        'google_fonts'  => '',
        'title_color'  => '',
        'title_fontsize' => '20',
        'hoverline_color'  => '',
        'title_background_style'  => 'iconbackground',
        'bg_title'  => '',
        'bg_title_color'  => '',
        'bg_icon_color'  => '',
        'icon'  => '',
        'shape_fill_color'  => '',
        'shape_border_color'  => '',
        'letter_spacing'  => '0',
        'style' => 'line',
        'title_animation'  => 'none',
        'title_animation_delay'  => '0',
        'responsive_animation'  => 'disable',
    ), $atts));

    $class[] = 'fontSize'.$title_fontsize;
        
    $id     = epico_sc_id('custom-title');

    $hasStyle = '' != $title_color || '' != $hoverline_color || '' != $shape_fill_color || ''  != $shape_border_color || '';
    
    switch($letter_spacing)
    {
        case '0':
            $class[] = 'letterspacing0';
            break;
        case '1':
            $class[] = 'letterspacing1';
            break;
        case '2':
            $class[] = 'letterspacing2';
            break;
        case '3':
            $class[] = 'letterspacing3';
            break;
        default:
            $class[] = 'letterspacing4';

    }

    switch($title_background_style)
    {
        case 'iconbackground':
            $class[] = 'iconbackgroundstyle';
            break;
        case 'shapebackground':
            $class[] = 'shapebackgroundstyle';
            break;
        case 'textbackground':
            $class[] = 'textbackgroundstyle';
            break;
    }
    
    if( $title_animation != 'none') {

        $class[] = 'shortcodeAnimation';

        if($responsive_animation != '')
        {
            $class[] = 'no-responsive-animation';
        }
    }

    /*--------- Google fonts --------*/
    $font_family = '';
    $font_weight = '';
    $pos = strpos($google_fonts, '%3A');
    if ($pos !== false) { 
        $font_family = substr($google_fonts, 0, $pos);
        $font_family= str_replace('font_family:','',$font_family);
        $font_family=  str_replace('%20',' ',$font_family);          
    }
    $pos = strpos($google_fonts, 'font_style');
    if ($pos !== false) { 
        $font_weight = substr($google_fonts,$pos);
        $font_weight = strtok($font_weight,'%');
        $font_weight = str_replace('font_style:','',$font_weight);     
    }
    $pos = strripos($google_fonts, '%3A');
    if ($pos !== false) { 
        $font_style = substr($google_fonts,$pos); 
        $font_style = str_replace('%3A','',$font_style);
    }
    $pos = strpos($google_fonts, '|');
    if ($pos !== false) {
        $google_fonts=substr($google_fonts, 0, $pos);
        $google_fonts=str_replace('font_family:','',$google_fonts);
    }

    //If the font is not already loaded to the page then load it to the body      
    //montserrat and Poppins are theme default fonts;
    $availableFonts=array($GLOBALS["available_fonts"]);
    if ( $google_fonts != '' && !(in_array($font_family,$availableFonts))){
         wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class($google_fonts), '//fonts.googleapis.com/css?family=' . $google_fonts);
    }

    /*------End of google fonts-----*/

    ob_start();

    if(strlen(esc_attr($shape_border_color)) && $style == "triangle" ) { ?>

         <style type="text/css" media="all">

            <?php
                echo "#$id.custom-title .shape-container .shape-line:after"; ?>
                {
                    border-color: <?php echo esc_attr($shape_border_color); ?>;
                }

            <?php echo "#$id.custom-title .shape-container .shape-line:before"; ?>
                {
                    border-color: <?php echo esc_attr($shape_border_color); ?>;
                }

        </style>
        
    <?php } ?>

    <div id="<?php echo esc_attr($id); ?>" class="custom-title <?php echo implode(' ', $class); ?>" <?php if(strlen(esc_attr($title_animation))) { ?> data-delay="<?php echo esc_attr($title_animation_delay); ?>" data-animation="<?php echo esc_attr($title_animation); ?>" <?php } ?>>
    
        <?php if(strlen($title)) { ?>


        <div class="title" style="<?php if(strlen($title_color)) { ?>color: <?php echo esc_attr($title_color); ?>; <?php } if(strlen(esc_attr($font_family))) {?> font-family:'<?php echo esc_attr($font_family); ?>'!important; font-weight:<?php echo esc_attr($font_weight); ?>; font-style:<?php echo esc_attr($font_style); ?>; <?php } ?>">
            <span>
                <?php echo wp_kses( $title, $GLOBALS["allowed_tags"] ); ?>
            </span>


            <?php if ($title_background_style == 'shapebackground' ) { ?>  


                <div class="shape-container <?php echo esc_attr($style); ?>">

                    <?php
                    if ($style == 'line') {
                        ?>
                            <div class="back-line" <?php if(strlen($title_color)) { ?> style="background-color: <?php echo esc_attr($title_color); ?>;" <?php } ?>></div>
                            <div class="hover-line" <?php if(strlen($hoverline_color)) { ?> style="background-color: <?php echo esc_attr($hoverline_color); ?>;" <?php } ?>></div>
                        <?php
                    }
                    elseif($style == 'triangle')
                    {
                        ?>
                            <div class="shape-line" <?php if(strlen($shape_border_color)) { ?> style="border-color: <?php echo esc_attr($shape_border_color); ?>;" <?php } ?>></div>
                            <div class="shape-fill" <?php if(strlen($shape_fill_color)) { ?> style="border-bottom-color: <?php echo esc_attr($shape_fill_color); ?>;" <?php } ?>></div>

                        <?php
                    }
                    else
                    {
                        ?>
                            <div class="shape-line" style="<?php if(strlen($shape_fill_color) && $style != "triangle" ) { ?> background-color: <?php echo esc_attr($shape_fill_color); ?>; <?php } ?> <?php if(strlen($shape_border_color)) { ?> border-color: <?php echo esc_attr($shape_border_color); ?>; <?php } ?>"></div>
                        <?php                  
                    }
                    ?>

                </div>

            <?php }  ?>

        </div>

            <?php if ( $title_background_style == 'textbackground' ) { ?>

            <span class="textbackground"  style="<?php if(strlen($bg_title_color)) { ?> color: <?php echo esc_attr($bg_title_color); ?>; <?php } ?> <?php if(strlen(esc_attr($font_family))) {?> font-family:'<?php echo esc_attr($font_family); ?>'!important; <?php echo 'font-weight:' . esc_attr($font_weight) . ';' ?> font-style:<?php echo esc_attr($font_style); ?>; <?php } ?>"><?php echo esc_html($bg_title); ?></span>

            <?php }  else if ( $title_background_style == 'iconbackground' ) { ?>

            <span class="iconbackground">
                <span class="glyph icon-<?php echo esc_attr($icon); ?>" <?php if(strlen($bg_icon_color)) { ?> style="color: <?php echo esc_attr($bg_icon_color); ?>;" <?php } ?>></span>
            </span>
             
            <?php } ?> 

        <?php } ?>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Image-Box
/*-----------------------------------------------------------------------------------*/

function epico_sc_imagebox($atts)
{
    extract(shortcode_atts(array(

        'image_url'  => '',
        'image_hover'  => 'enable',
        'image_hover_shadow'  => 'disable',
        'image_hover_color_preset'  => 'c0392b',
        'image_hover_color_custom'  => '',
        'image_animation'  => 'none',
        'image_animation_delay'  => '0',
        'responsive_animation'  => 'disable',
        'title'  => '',
        'image_title_size' => '20',
		'font_type'  => 'default',
        'google_fonts'  => '',
        'title_color'  => '',
        'subtitle'  => '',
        'subtitle_color'  => '',
        'image_text_color'  => '',
        'image_text_align'  => 'left',
        'image_text_background_color'  => '',
        'image_text_border'  => 'disable',
        'imagebox_content_border'  => 'enable',
        'image_text_border_color'  => '',
        'vccontent'  => '',
        'content_fontsize'  => '12',
        'url'  => '',
        'target' => '_self',

    ), $atts));

    if (is_numeric($image_url)) {
        $image_url = wp_get_attachment_url($image_url);
    }
        
    $hasTitle  = '' != $title;
    $hasUrl = '' != $url;
    $hasSubTitle  = '' != $subtitle;
    $hasStyle = '' != $title_color || '' != $subtitle_color || '' != $image_text_color || '' != $image_text_background_color || '' != $image_text_border_color || 'c0392b' != $image_hover_color_preset ;

    $hasTSContent = '' != $title || '' != $subtitle|| '' != $vccontent ;
    $hasVCContent = '' != $vccontent;
    $contentFSClass = 'contentfs'.$content_fontsize;

    $id     = epico_sc_id('imagebox');
    $class  = array();
    
    $class[] = 'fontSize'.$image_title_size;

    switch($image_text_align)
    {
        case 'right':
            $class[] = 'imageBoxRight';
            break;
        case 'center':
            $class[] = 'imageBoxCenter';
            break;
        case 'left':
        default:
            $class[] = 'imageBoxleft';
    }

    if( $image_animation != 'none') {

        $class[] = 'shortcodeAnimation';

        if($responsive_animation != '')
        {
            $class[] = 'no-responsive-animation';
        }

    }

    if ( $image_hover == 'enable') {
        $class[] = 'imgBoxHover';
    }
    
    if ( $image_hover_shadow == 'enable') {
        $class[] = 'image_hover_shadow';
    }

    if( $imagebox_content_border == 'disable' ) {

         $class[] = 'disableContentBorder';

    }
	$font_family = '';
    if($font_type != 'default')
    {

        /*---------Google fonts--------*/
        $pos = strpos($google_fonts, '%3A');
        if ($pos !== false) { 
            $font_family = substr($google_fonts, 0, $pos);
            $font_family= str_replace('font_family:','',$font_family);
            $font_family=  str_replace('%20',' ',$font_family);          
        }
        $pos = strpos($google_fonts, 'font_style');
        if ($pos !== false) { 
            $font_weight = substr($google_fonts,$pos);
            $font_weight = strtok($font_weight,'%');
            $font_weight = str_replace('font_style:','',$font_weight);     
        }
        $pos = strripos($google_fonts, '%3A');
        if ($pos !== false) { 
            $font_style = substr($google_fonts,$pos); 
            $font_style = str_replace('%3A','',$font_style);
        }
        $pos = strpos($google_fonts, '|');
        if ($pos !== false) {
            $google_fonts=substr($google_fonts, 0, $pos);
            $google_fonts=str_replace('font_family:','',$google_fonts);
        }

        //If the font is not already loaded to the page then load it to the body      
        //montserrat and Poppins are theme default fonts;
        $availableFonts=array($GLOBALS["available_fonts"]);
        if ( $google_fonts != '' && !(in_array($font_family,$availableFonts))){
             wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class($google_fonts), '//fonts.googleapis.com/css?family=' .$google_fonts);
        }

        /*------End of google fonts-----*/
    }

    ob_start();

    if($hasStyle)
    {
    ?>
    <style type="text/css" media="all">
        <?php if(strlen(esc_attr($image_text_border_color)))
        {
            echo "#$id.imageBox .content "; ?>
            {
                border:solid 1px <?php echo esc_attr($image_text_border_color); ?>;
            }

        <?php
        } 
        if(strlen(esc_attr($title_color)))
        {
            echo "#$id .content .title"; ?>
            {
                color: <?php echo esc_attr($title_color); ?>;
                
            }
            
            
        <?php
        }
        if(strlen(esc_attr($subtitle_color)))
        {
            echo "#$id  .content .subtitle "; ?>
            {
                color: <?php echo esc_attr($subtitle_color); ?>;
            }
        <?php
        }
        if(strlen(esc_attr($image_text_color)))
        {
            echo "#$id  .content .text "; ?>
            {
                color: <?php echo esc_attr($image_text_color); ?>;
            }
        <?php
        }
		if(strlen(esc_attr($font_family)))
        {
            echo "#$id .content .title"; ?>
            {
                font-family:'<?php echo esc_attr($font_family); ?>'!important;
				font-weight:<?php echo esc_attr($font_weight); ?>;
				font-style:<?php echo esc_attr($font_style); ?>;
                
            }
            
            
        <?php
        }
        if(strlen(esc_attr($font_family)))
        {
            echo "#$id  .content .subtitle "; ?>
            {
               font-family:'<?php echo esc_attr($font_family); ?>'!important;
				font-weight:<?php echo esc_attr($font_weight); ?>;
				font-style:<?php echo esc_attr($font_style); ?>;
            }
        <?php
        }
        if(strlen(esc_attr($font_family)))
        {
            echo "#$id  .content .text "; ?>
            {
               font-family:'<?php echo esc_attr($font_family); ?>'!important;
				font-weight:<?php echo esc_attr($font_weight); ?>;
				font-style:<?php echo esc_attr($font_style); ?>;  
            }
        <?php
        }
        if(strlen(esc_attr($image_text_background_color)))
        {
            echo "#$id  .content "; ?>
            {
                background-color: <?php echo esc_attr($image_text_background_color); ?>;
            }
        <?php
        }

         $color = '';
         if ( $image_hover == 'enable') {

            if( esc_attr($image_hover_color_preset) == 'custom') {
                $color = $image_hover_color_custom;
            } else {
                $color = "#" . $image_hover_color_preset ;
            }

            echo "#$id .imagebox-hover "; ?>
            {
                background-color:<?php echo(esc_attr($color)); ?>;
            }

        <?php
        }

        ?>

    </style>
    <?php
    }//if($hasStyle)
    ?>

    <?php if ($hasUrl) { ?>
        
         <a id="<?php echo esc_attr($id); ?>" class="imageBox <?php if ($hasTSContent) { ?> textBox textLeftBorder <?php } ?> <?php if( strlen(esc_attr($subtitle)) == 0 ) { ?>  nosubtitle  <?php } ?> <?php echo implode(' ', $class); ?>"  <?php if(strlen(esc_attr($image_animation))) { ?> data-delay="<?php echo esc_attr($image_animation_delay); ?>" data-animation="<?php echo esc_attr($image_animation); ?>" <?php } ?>  href="<?php echo esc_url($url); ?>" <?php if($target != '') {?>target="<?php echo esc_attr($target); ?>"<?php } ?>>

    <?php }  else { ?>          
        
         <div id="<?php echo esc_attr($id); ?>" class="imageBox <?php if ($hasTSContent) { ?> textBox textLeftBorder <?php } ?> <?php if( strlen(esc_attr($subtitle)) == 0 ) { ?>  nosubtitle  <?php } ?> <?php echo implode(' ', $class); ?>"  <?php if(strlen(esc_attr($image_animation))) { ?> data-delay="<?php echo esc_attr($image_animation_delay); ?>" data-animation="<?php echo esc_attr($image_animation); ?>" <?php } ?> >
    
    <?php } ?>  
    
    <?php if (strlen(esc_url($image_url))) { ?>
        <div class="image">

            <?php if ( $image_hover == 'enable')  { ?> 
                <!-- imagebox Hover  -->
                <div class="imagebox-hover"></div>
            <?php } ?>

            <img src="<?php echo esc_url($image_url); ?>" <?php if ( esc_attr($title) ) { ?> alt="<?php echo esc_attr($title) ?>" <?php } else { ?> alt="" <?php } ?>>
        </div>
    <?php } ?>
  
         <?php if ($hasTSContent) { ?>
            <div class="content">

                <?php if($hasTitle) { ?>

                    <div class="clearfix">
                        <div class="title clearfix" <?php if(strlen(esc_attr($title_color))){ ?> style="color:<?php echo esc_attr($title_color); ?>;" <?php } ?>>

                            <?php echo wp_kses( $title, $GLOBALS["allowed_tags"] ); ?>

                            <?php if($hasSubTitle) { ?>
                                <!-- subtitle -->
                                <span class="subtitle"><?php echo esc_attr($subtitle); ?></span>
                            <?php } ?>

                        </div>
                    </div>

                <?php } ?>
                <?php if($hasVCContent){ ?>
                
                    <div class="<?php echo esc_attr($contentFSClass); ?> text"><?php echo wp_kses( $vccontent, $GLOBALS["allowed_tags"] ); ?></div>
                    
                <?php } ?>
            </div>
        <?php } ?>
    
    <?php if ($hasUrl) { ?>
        
         </a>
       
    <?php }  else { ?>          
        
        </div>

    <?php } ?>  
    

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Animated Text
/*-----------------------------------------------------------------------------------*/

function epico_sc_animated_text($atts){
    extract(shortcode_atts(array(
        'image_url'  => '',
        'title'  => '',
        'title_front_color'  => '#ffffff',
        'title_back_color'  => '#272727',
        'animatedtext_font_size'  => '30',
        'animatedtext_style'  => 'with_image',
        'font_type'  => 'default',
        'google_fonts'  => '',
        'animatedtext_speed'  => '8',
    ), $atts));
    $image_url = wp_get_attachment_url($image_url);
    $id = epico_sc_id('animatedtext');
    
    //let us show the animation even if there is no image
        if($image_url){
        $text_height='auto';
        $imageOutput='<img src="'. esc_url($image_url).'" alt="' . esc_attr($title) . '">';//echo image tag if this is of type with image
    }else{
        $text_height= (intval(esc_attr($animatedtext_font_size)*1.5)).'vw';
        $imageOutput='';
    }
    $animation_duration= strlen($title)*intval(esc_attr($animatedtext_speed));/*Duration of cycling*/
    
 
    $font_family = '';
    if($font_type != 'default')
    {

        /*---------Google fonts--------*/
        $pos = strpos($google_fonts, '%3A');
        if ($pos !== false) { 
            $font_family = substr($google_fonts, 0, $pos);
            $font_family= str_replace('font_family:','',$font_family);
            $font_family=  str_replace('%20',' ',$font_family);          
        }
        $pos = strpos($google_fonts, 'font_style');
        if ($pos !== false) { 
            $font_weight = substr($google_fonts,$pos);
            $font_weight = strtok($font_weight,'%');
            $font_weight = str_replace('font_style:','',$font_weight);     
        }
        $pos = strripos($google_fonts, '%3A');
        if ($pos !== false) { 
            $font_style = substr($google_fonts,$pos); 
            $font_style = str_replace('%3A','',$font_style);
        }
        $pos = strpos($google_fonts, '|');
        if ($pos !== false) {
            $google_fonts=substr($google_fonts, 0, $pos);
            $google_fonts=str_replace('font_family:','',$google_fonts);
        }

        //If the font is not already loaded to the page then load it to the body      
        //montserrat and Poppins are theme default fonts;
        $availableFonts=array($GLOBALS["available_fonts"]);
        if ( $google_fonts != '' && !(in_array($font_family,$availableFonts))){
             wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class($google_fonts), '//fonts.googleapis.com/css?family=' .$google_fonts);
        }

        /*------End of google fonts-----*/
    }

    ob_start();?>
    <div class="animatedtext_content" style="height:<?php echo esc_attr($text_height);?>;">
    <div id="<?php echo esc_attr($id); ?>" class="animatedtext">
       <div class="image">
           <?php
           //sanitization performed in above lines!
            echo $imageOutput;?>
       </div>
       <span class="slideshowContent" style="font-size:<?php echo esc_attr($animatedtext_font_size).'vw';?>;">
           <span class="firstTitle" style="animation-duration:<?php echo esc_attr($animation_duration).'s'; ?>; color:<?php echo esc_attr($title_front_color); ?>; <?php if(strlen(esc_attr($font_family))) {?> font-family:'<?php echo esc_attr($font_family); ?>'!important; font-weight:<?php echo esc_attr($font_weight); ?>; font-style:<?php echo esc_attr($font_style); ?>;  <?php } ?>">
               <span class="immediate"><?php echo esc_attr($title); ?><?php echo esc_attr($title); ?><?php echo esc_attr($title); ?><?php echo esc_attr($title); ?></span> 
               <span class="immediate"><?php echo esc_attr($title); ?><?php echo esc_attr($title); ?><?php echo esc_attr($title); ?><?php echo esc_attr($title); ?></span> 
           </span> 
       </span>
    </div>
    <span class="secondTitle" style="animation-duration:<?php echo esc_attr($animation_duration).'s'; ?>; color:<?php echo esc_attr($title_back_color); ?>; font-size:<?php echo esc_attr($animatedtext_font_size).'vw';?>; <?php if(strlen(esc_attr($font_family))) {?> font-family:'<?php echo esc_attr($font_family); ?>'!important; font-weight:<?php echo esc_attr($font_weight); ?>; font-style:<?php echo esc_attr($font_style); ?>;  <?php } ?>">
         <span class="immediate"><?php echo esc_attr($title); ?><?php echo esc_attr($title); ?><?php echo esc_attr($title); ?><?php echo esc_attr($title); ?></span> 
         <span class="immediate"><?php echo esc_attr($title); ?><?php echo esc_attr($title); ?><?php echo esc_attr($title); ?><?php echo esc_attr($title); ?></span> 
    </span>
    </div>
    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Banner
/*-----------------------------------------------------------------------------------*/

function epico_sc_banner($atts)
{
    extract(shortcode_atts(array(

        'image_url'  => '',
        'title'  => '',
        'title_size' => '20',
        'title_color'  => '',
        'title_border_color' => '',
        'subtitle'  => '',
		'font_type'  => 'default',
        'google_fonts'  => '',
        'subtitle_color'  => '',
        'hover'  => 'enable',
        'hover_color_preset'  => 'd02d48',
        'hover_color'  => '',
        'style' => 'style1',
        'line'  => 'enable',
        'url'  => '',
        'link_color'  => '',
        'animation'  => 'none',
        'responsive_animation'  => 'disable',
        'delay'  => '0',

    ), $atts));

    if (is_numeric($image_url)) {
        $image_url = wp_get_attachment_url($image_url);
    }

    $id     = epico_sc_id('banner');
    $class  = array();
    
    $class[] = 'fontSize'.$title_size;

    if( $animation != 'none') {

        $class[] = 'shortcodeAnimation';

        if($responsive_animation != '')
        {
            $class[] = 'no-responsive-animation';
        }
    }

    $hasStyle = '' != $title_color || '' != $subtitle_color || 'enable' == $hover || '' != $title_border_color ;
 $font_family = '';
    if($font_type != 'default')
    {

        /*---------Google fonts--------*/
        $pos = strpos($google_fonts, '%3A');
        if ($pos !== false) { 
            $font_family = substr($google_fonts, 0, $pos);
            $font_family= str_replace('font_family:','',$font_family);
            $font_family=  str_replace('%20',' ',$font_family);          
        }
        $pos = strpos($google_fonts, 'font_style');
        if ($pos !== false) { 
            $font_weight = substr($google_fonts,$pos);
            $font_weight = strtok($font_weight,'%');
            $font_weight = str_replace('font_style:','',$font_weight);     
        }
        $pos = strripos($google_fonts, '%3A');
        if ($pos !== false) { 
            $font_style = substr($google_fonts,$pos); 
            $font_style = str_replace('%3A','',$font_style);
        }
        $pos = strpos($google_fonts, '|');
        if ($pos !== false) {
            $google_fonts=substr($google_fonts, 0, $pos);
            $google_fonts=str_replace('font_family:','',$google_fonts);
        }

        //If the font is not already loaded to the page then load it to the body      
        //montserrat and Poppins are theme default fonts;
        $availableFonts=array($GLOBALS["available_fonts"]);
        if ( $google_fonts != '' && !(in_array($font_family,$availableFonts))){
             wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class($google_fonts), '//fonts.googleapis.com/css?family=' .$google_fonts);
        }

        /*------End of google fonts-----*/
    }


    ob_start();

    if($hasStyle)
    {
    ?>
    <style type="text/css" media="all">
        <?php if(strlen(esc_attr($title_border_color)))
        {
            echo "#$id.banner .line:after"; ?>
            {
                background:<?php echo esc_attr($title_border_color); ?>;
            }

        <?php
        }        
        if(strlen(esc_attr($title_color)))
        {
            echo "#$id .title"; ?>
            {
                color: <?php echo esc_attr($title_color); ?>;
                
            }
        <?php
        }
		if(strlen(esc_attr($font_family))) 
        {
            echo "#$id .title"; ?>
            {
				font-family:'<?php echo esc_attr($font_family); ?>'!important;
				font-weight:<?php echo esc_attr($font_weight); ?>;
				font-style:<?php echo esc_attr($font_style); ?>;
				
                
            }
        <?php
        }
		if(strlen(esc_attr($font_family))) 
        {
            echo "#$id .subtitle"; ?>
            {
				font-family:'<?php echo esc_attr($font_family); ?>'!important;
				font-weight:<?php echo esc_attr($font_weight); ?>;
				font-style:<?php echo esc_attr($font_style); ?>;
				
                
            }
        <?php
        }
        if(strlen(esc_attr($subtitle_color)))
        {
            echo "#$id .subtitle "; ?>
            {
                color: <?php echo esc_attr($subtitle_color); ?>;
            }
        <?php
        }
        if(strlen(esc_attr($link_color)))
        {
            echo "#$id.banner a, #$id.banner a:hover"; ?>
            {
                color: <?php echo esc_attr($link_color); ?>;
                border-color: <?php echo esc_attr($link_color); ?>;
            }
        <?php
        }

        $color = "";
        if($hover == 'enable')
        {
            if($hover_color_preset == 'custom')
            {
                $color = $hover_color;
            }
            else
            {
                $color = "#" . $hover_color_preset;
            }

            echo "#$id:hover .hover "; ?>
            {
                background-color: <?php echo esc_attr($color); ?>;
            }
        <?php
        }
        ?> 
    </style>
    <?php
    }//if($hasStyle)
    ?>         
    
    <div id="<?php echo esc_attr($id); ?>" class="banner  <?php echo implode(' ', $class); ?>"  <?php if(strlen(esc_attr($animation))) { ?> data-delay="<?php echo esc_attr($delay); ?>" style="background-image:url('<?php echo esc_url($image_url); ?>');" data-animation="<?php echo esc_attr($animation); ?>" <?php } ?>>
    
        <?php if(strlen(esc_url($image_url))){ ?>
            <div class="image">

                <?php if ( $hover == 'enable')  { ?> 
                    <!-- imagebox Hover  -->
                    <div class="hover"></div>
                <?php } ?>

                <img src="<?php echo esc_url($image_url); ?>" <?php if ( esc_attr($title) ) { ?> alt="<?php echo esc_attr($title) ?>" <?php } else { ?> alt="" <?php } ?>>
            </div>
        <?php } ?>

        <div class="content-container">
            <div class="content">
                <?php if($title != '') { ?>

                    <?php
                    if($style == "style1")
                    {
                        if($subtitle != '') { ?>
                            <!-- subtitle -->
                            <span class="subtitle"><?php echo esc_attr($subtitle); ?></span>
                        <?php } ?>

                        <span class="title clearfix" <?php if(strlen(esc_attr($title_color))){ ?> style="color:<?php echo esc_attr($title_color); ?>;" <?php } ?>>

                            <?php echo wp_kses( $title, $GLOBALS["allowed_tags"] ); ?>

                        </span>
                    <?php
                    }
                    else
                    {   
                        ?>
                        <span class="title clearfix" <?php if(strlen(esc_attr($title_color))){ ?> style="color:<?php echo esc_attr($title_color); ?>;" <?php } ?>>

                            <?php echo wp_kses( $title, $GLOBALS["allowed_tags"] ); ?>

                        </span>
                        <?php if($subtitle != '') { ?>
                            <!-- subtitle -->
                            <span class="subtitle"><?php echo esc_attr($subtitle); ?></span>
                        <?php }

                    }
                    ?>

                    <?php  if( $line != 'disable') { ?>
                        <span class="line"></span>
                    <?php } ?>

                    <?php
                }

                if($url && function_exists( 'vc_build_link' )) {
                      
                    $link = vc_build_link( $atts['url'] );
                    if ( strlen( $link['url'] ) ) {  ?>

                        <a href="<?php echo esc_url($link['url']); ?>" <?php if($link['target'] != '') {?>target="<?php echo esc_attr( $link['target'] ); ?>"<?php } ?>><span data-hover="<?php echo esc_attr($link['title']); ?>"><?php echo esc_attr($link['title']); ?></span></a>

                    <?php
                    }

                }
                ?>
            </div>

        </div>
    </div> 
    

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Custom image-box - Creative image box
/*-----------------------------------------------------------------------------------*/

function epico_sc_custom_imagebox($atts)
{
    extract(shortcode_atts(array(

        'title'  => '',
        'subtitle'  => '',
        'image_url'  => '',
        'image_hover'  => 'enable',
        'image_hover_color_preset'  => '#c0392b',
        'image_hover_color_custom'  => '',
        'title_color'  => '',
        'subtitle_color'  => '',
        'title_border'  => 'none',
        'title_fontsize'  => '20',
        'box_position'  => 'left',
        'text_content'  => '',
        'text_content_color'  => '',
        'content_fontsize'  => '12',
        'content'  => '',
        'content_border_width'  => '2px',
        'url'  => '',
        'target' => '_self',
        'bg_color'  => '#fff',
        'border_color'  => '',
        'title_border_color'  => '#272727',

    ), $atts));

    $hasTitle  = '' != $title;
    $titleIsLink = '' != $url;
    $hasContent  = '' != $text_content;
    $hasStyle = '' != $title_color  || '' != $text_content_color || ''  != $subtitle_color || ''  !=$border_color || 'c0392b' != $image_hover_color_preset ;

    $id     = epico_sc_id('custom-imagebox');
    $class  = array();
    $class[] = 'fontSize'.$title_fontsize;
    $contentFSClass = 'contentfs'.$content_fontsize;

    if (is_numeric($image_url)) {
        $image_url = wp_get_attachment_url($image_url);
    }


    $boxclass = "";
    switch($box_position)
    {
        case 'right':
            $boxclass = 'Boxright';
            break;
        case 'left':
        default:
            $boxclass = 'Boxleft';

    }

    if ( $image_hover == 'enable') {
        $class[] = 'imgBoxHover';
    }

    ob_start();

    if($hasStyle)
    {
    ?>
        <style type="text/css" media="all">
        
        <?php 
                $color = '';
        if ( $image_hover == 'enable') {

            if( esc_attr($image_hover_color_preset) == 'custom') {
                $color = $image_hover_color_custom;
            } else {
                $color = "#" . $image_hover_color_preset ;
            }

            echo "#$id .overlay "; ?>
            {
                background-color:<?php echo(esc_attr($color)); ?>;
            }

        <?php
        }   if(strlen(esc_attr($content_border_width))) {
                   
            echo  "#$id .custom-textbox .frame.top div, #$id .custom-textbox .frame.bottom div"; ?>
            {
                height: <?php echo esc_attr($content_border_width); ?>;  
            } 

            <?php 

            echo  "#$id .custom-textbox .frame.right div, #$id .custom-textbox .frame.left div"; ?>
            {
                width: <?php echo esc_attr($content_border_width); ?>;  
            }

        <?php } ?>

    </style>

    <?php
    }//if($hasStyle)
    ?>

    <div id="<?php echo esc_attr($id); ?>" class="custom-imageBox <?php echo esc_attr($boxclass); ?> <?php echo implode(' ', $class); ?>"  >

        <?php
        //Just show custom image box when image selected
        if($image_url)
        {
        ?>
        
        <?php if (strlen(esc_url($image_url))) { ?>
            <div class="image">

                <?php if ( $image_hover == 'enable')  { ?> 
                    <!-- custom imagebox Hover  -->
                    <div class="overlay"></div>
                <?php } ?>

                <img src="<?php echo esc_url($image_url); ?>" <?php if ( esc_attr($title) ) { ?> alt="<?php echo esc_attr($title) ?>" <?php } else { ?> alt="" <?php } ?>>
            </div>
        <?php } ?>

        <div class="custom-textbox">
            <div class="custom-textbox-bg" <?php if (strlen(esc_attr($bg_color))) { ?> style="background-color:<?php echo esc_attr($bg_color); ?>" <?php } ?>></div>
            <div  class="textBox <?php if( strlen(esc_attr($subtitle)) == 0 ) { ?>  nosubtitle  <?php } ?> <?php echo implode(' ', $class); ?>">

                <div class="frame top">
                    <div <?php if (strlen(esc_attr($border_color))) { ?> style="background-color:<?php echo esc_attr($border_color); ?>" <?php } ?>></div>
                </div>
                <div class="frame right">
                    <div <?php if (strlen(esc_attr($border_color))) { ?> style="background-color:<?php echo esc_attr($border_color); ?>" <?php } ?>></div>
                </div>
                <div class="frame bottom">
                    <div <?php if (strlen(esc_attr($border_color))) { ?> style="background-color:<?php echo esc_attr($border_color); ?>" <?php } ?>></div>
                </div>
                <div class="frame left">
                    <div <?php if (strlen(esc_attr($border_color))) { ?> style="background-color:<?php echo esc_attr($border_color); ?>" <?php } ?>></div>
                </div>

                <?php if($hasTitle) { ?>

                    <?php if($titleIsLink){ ?>
                        
                        <div class="clearfix">

                            <div class="title" style="<?php if(strlen(esc_attr($title_color))){ ?> color:<?php echo esc_attr($title_color); ?>; <?php } ?>">

                                     <a href="<?php echo esc_url($url); ?>" <?php if($target != '') {?>target="<?php echo esc_attr($target); ?>"<?php } ?>>
                                        <?php echo wp_kses( $title, $GLOBALS["allowed_tags"] ); ?>
                                     </a>          
                                               
                                    <?php if(strlen(esc_attr($subtitle))) { ?>
                                        <span class="subtitle" <?php if(strlen(esc_attr($subtitle_color))){ ?> style="color:<?php echo esc_attr($subtitle_color); ?>;" <?php } ?>><?php echo esc_attr($subtitle); ?></span>
                                    <?php } ?>

                            </div>
                        </div>

                    <?php } else { ?>
                         
                        <div class="clearfix">
                            <div class="title clearfix" style="<?php if(strlen(esc_attr($title_color))){ ?> color:<?php echo esc_attr($title_color); ?>; <?php } ?>">

                                <?php echo wp_kses( $title, $GLOBALS["allowed_tags"] ); ?>

                                <?php if(strlen(esc_textarea($subtitle))) { ?>
                                    <!-- subtitle -->
                                    <span class="subtitle" <?php if(strlen(esc_attr($subtitle_color))){ ?> style="color:<?php echo esc_attr($subtitle_color); ?>;" <?php } ?>><?php echo esc_attr($subtitle); ?></span>
                                <?php } ?>
                            
                            </div>
                        </div>

                    <?php } ?>

                <?php } ?>
                
                <?php if($hasContent){ ?>
                    <div class="<?php echo esc_attr($contentFSClass); ?> text" <?php if(strlen(esc_attr($text_content_color))) { ?> style="color: <?php echo esc_attr($text_content_color); ?>;" <?php } ?>><?php echo wp_kses( $text_content, $GLOBALS["allowed_tags"] ); ?></div>
                <?php } ?>
                
            </div>
        </div>

        <?php
        }
        ?>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Icon-Box-custom ( creative iconbox ) 
/*-----------------------------------------------------------------------------------*/

function epico_sc_iconbox_custom($atts, $content=null)
{
    extract(shortcode_atts(array(
        'title'         => '',
        'title_color'         => '',
        'icon'          => 'keyboard-o',
        'bg_hover_color'         => '#d02d48',
        'icon_color'         => '#d02d48',
        'hover_style'         => 'style1',
        'bg_color'         => 'fff',
        'border_color'         => '252525',
        'image'         => '',
        'url'           => '',
        'content_text' => '',
    ), $atts));

    $id     = epico_sc_id('iconbox');
    $class  = array("custom-iconbox" , $hover_style);

    if (is_numeric($image)) {
        $class[] = 'hasimagebackground';
    }

    ob_start();
        
    $color = $bg_hover_color;
    ?>

    <style type="text/css" media="all">
        <?php
        if(strlen(esc_attr($title_color)))
        {
            echo ".custom-iconbox#$id .icon-container .title"; ?>
            {
                color: <?php echo esc_attr($title_color); ?>;
            }
        <?php
        }

        if (is_numeric($image)) {
            $image_url = wp_get_attachment_url($image);

            echo ".custom-iconbox#$id .hover-content"; ?>
            {
                background: url(<?php echo esc_attr($image_url); ?>) ;
            }
        <?php
        }

        if(strlen(esc_attr($color)))
        {

            echo "#$id .overlay:before"; ?>
            {
                background-color: <?php echo esc_attr($color); ?>;
            }

        <?php
        }
        if(strlen(esc_attr($border_color)))
        {
            echo ".custom-iconbox#$id .icon-container"; ?>
            {
                border-color: <?php echo esc_attr($border_color); ?>;
            }
        <?php
        }
        if(strlen(esc_attr($bg_color)))
        {
            echo ".custom-iconbox#$id"; ?>
            {
                background-color: <?php echo esc_attr($bg_color); ?>;
            }
        <?php
        }?>
    </style>


    <div id="<?php echo esc_attr($id); ?>" class="<?php echo implode(' ', $class); ?>">
        <div class="icon">
            <div class="icon-container">
                <span class="glyph icon-<?php echo esc_attr($icon); ?>" <?php if(strlen($icon_color)) { ?> style="color: <?php echo esc_attr($icon_color); ?>;" <?php } ?>></span>
                <?php if($title != ''){ ?>
                    <h3 class="title"><?php echo esc_attr($title); ?></h3>
                <?php } ?>
            </div>
        </div>
        <div class="hover-content">
            <span class="overlay"></span>
            <div class="icon">
                <span class="glyph icon-<?php echo esc_attr($icon); ?>"></span>
            </div>
            <?php if($title != ''   ){ ?>
                <h3 class="title"><?php echo esc_attr($title); ?></h3>
            <?php } ?>
            
            <!-- iconbox content -->
            <div class="content-wrap">
                <?php if(strlen(esc_attr($content_text))) { ?>
                    
                    <div class="content">
                        <?php echo wp_kses( $content_text, $GLOBALS["allowed_tags"] ); ?>
                    </div>
                
                <?php } ?>
            

                <?php
                if($url && function_exists( 'vc_build_link' ))
                {
                    $link = vc_build_link( $atts['url'] );
                    if ( strlen( $link['url'] ) ) { 
                    ?>
                    <div class="more-link">
                        <a href="<?php echo esc_url($link['url']); ?>" <?php if($link['target'] != '') {?>target="<?php echo esc_attr( $link['target'] ); ?>"<?php } ?> title="<?php echo esc_attr($link['title']); ?>"><?php echo esc_attr($link['title']); ?></a>
                    </div>
                <?php }
                }
                ?>

            </div>
        </div>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Icon-Box-top No border 
/*-----------------------------------------------------------------------------------*/

function epico_sc_iconbox_noborder($atts, $content=null)
{
    extract(shortcode_atts(array(
        'title'         => '',
        'title_color'   => '',
        'icon_animation' => 'none',
        'icon_animation_delay' => '0',
        'responsive_animation' => 'disable',
        'icon'          => 'keyboard-o',
        'icon_color'         => '',
        'icon_border_color'         => '',
        'icon_position' => 'top',
        'alignment' => 'right_alignment',
        'url'           => '',
        'content_text' => '',
        'content_color' => '',
    ), $atts));

    $hasTitle  = '' != $title;
    $hasStyle = '' != $title_color || '' != $icon_color || '' != $content_color ;

    $id     = epico_sc_id('iconbox');
    $class  = array("iconbox");

    if( strlen($icon_position)) {

        $class[] = 'iconbox-top';
        
    }
    
    if( $icon_animation != 'none') {

        $class[] = ' shortcodeAnimation';

        if($responsive_animation != '')
        {
            $class[] = 'no-responsive-animation';
        }
    }
    
    if( strlen($alignment)) {

        $class[] = " $alignment";

    }
    
    ob_start();
    ?>

    <div id="<?php echo esc_attr($id); ?>" class="<?php echo implode(' ', $class); ?>"  <?php if(strlen($icon_animation)) { ?> data-delay="<?php echo esc_attr($icon_animation_delay); ?>" data-animation="<?php echo esc_attr($icon_animation); ?>" <?php } ?>>
        <div class="icon">
            <span class="glyph icon-<?php echo esc_attr($icon); ?>" <?php if(strlen($icon_color)) { ?> style="<?php if(strlen($icon_border_color)) { ?> border-color: <?php echo esc_attr($icon_border_color); ?>; background-color: <?php echo esc_attr($icon_border_color); ?>;<?php } ?> color: <?php echo esc_attr($icon_color); ?>;" <?php } ?>></span>
        </div>
        <div class="content-wrap">
            <?php if($hasTitle){ ?>
                <h3 class="title" <?php if(strlen($title_color)) { ?> style="color: <?php echo esc_attr($title_color); ?>;" <?php } ?>><?php echo esc_attr($title); ?></h3>
            <?php } ?>
            
            <!-- iconbox content -->
            <?php if(strlen(esc_attr($content_text))) { ?>
                
                <div class="content" <?php if(strlen($content_color)) { ?> style="color: <?php echo esc_attr($content_color); ?>;" <?php } ?>>
                    <?php echo wp_kses( $content_text, $GLOBALS["allowed_tags"] ); ?>
                </div>
                
            <?php } ?>

             <!-- icon box read more button -->
            <?php if($url && function_exists( 'vc_build_link' )) {
                      
                    $link = vc_build_link( $atts['url'] );
                    if ( strlen( $link['url'] ) ) {  ?>

                    <div class="more-link">
                        <a href="<?php echo esc_url($link['url']); ?>" <?php if(strlen($content_color)) { ?> style="color: <?php echo esc_attr($content_color); ?>;" <?php } ?> <?php if($link['target'] != '') {?>target="<?php echo esc_attr( $link['target'] ); ?>"<?php } ?>>[ <?php echo esc_attr($link['title']); ?> ]</a>
                    </div>

                <?php }
                }
            ?>

        </div>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Icon-Box-Rectangle
/*-----------------------------------------------------------------------------------*/

function epico_sc_iconbox_rectangle($atts, $content=null)
{
    extract(shortcode_atts(array(
        'title'         => '',
        'title_color'   => '',
        'icon_animation' => 'none',
        'icon_animation_delay' => '0',
        'responsive_animation' => 'disable',
        'icon'          => 'keyboard-o',
        'icon_color'         => '',
        'icon_border'         => 'rectangle',
        'icon_border_color'         => '',
        'icon_background_fill'         => 'fillbackground',
        'icon_position' => 'top',
        'url'           => '',
        'content_text' => '',
        'content_color' => '',
    ), $atts));

    $hasTitle  = '' != $title;
    $hasStyle = '' != $title_color || '' != $icon_color || '' != $content_color ;

    $id     = epico_sc_id('iconbox');
    $class  = array("iconbox");

    if( strlen($icon_position)) {

        $class[] = 'iconbox-top';
        
    }
    
    if( $icon_animation != 'none') {

        $class[] = ' shortcodeAnimation';
        if($responsive_animation != '')
        {
            $class[] = 'no-responsive-animation';
        }
    }
    
    if( strlen($icon_border)) {

        $class[] = " $icon_border";

    }
    
    if( strlen($icon_background_fill)) {

        $class[] = " $icon_background_fill";

    }

    ob_start();

    if($hasStyle)
    {
    ?>
    <style type="text/css" media="all">
        <?php if(strlen(esc_attr($title_color)))
        {
            echo "#$id  .title "; ?>
            {
                color: <?php echo esc_attr($title_color); ?>;
            }
        <?php
        }
        if(strlen(esc_attr($icon_color)))
        {
            echo "#$id .glyph"; ?>
            {
                color: <?php echo esc_attr($icon_color); ?>;
            }
        <?php
        }
        if(strlen(esc_attr($icon_border_color)))
        {
            echo "#$id .icon span.glyph"; ?>
            {
                border-color: <?php echo esc_attr($icon_border_color); ?>;
                background-color: <?php echo esc_attr($icon_border_color); ?>;
            }
        <?php
        }
        if(strlen(esc_attr($content_color)))
        {
            echo "#$id  .content , #$id .more-link a "; ?>
            {
                color: <?php echo esc_attr($content_color); ?>;
            }
        <?php
        }?>
    </style>
    <?php
    }//if($hasStyle)
    ?>

    <div id="<?php echo esc_attr($id); ?>" class="<?php echo implode(' ', $class); ?>"  <?php if(strlen($icon_animation)) { ?> data-delay="<?php echo esc_attr($icon_animation_delay); ?>" data-animation="<?php echo esc_attr($icon_animation); ?>" <?php } ?>>
        <div class="icon">
            <span class="glyph icon-<?php echo esc_attr($icon); ?>"></span>
        </div>
        <div class="content-wrap">
            <?php if($hasTitle){ ?>
                <h3 class="title"><?php echo esc_attr($title); ?></h3>
            <?php } ?>
            
            <!-- iconbox content -->
            <?php if(strlen(esc_attr($content_text))) { ?>
            
                <div class="content">
                    <?php echo wp_kses( $content_text, $GLOBALS["allowed_tags"] ); ?>
                </div>
                
            <?php } ?>
           
            <!-- icon box read more button -->
            <?php if($url && function_exists( 'vc_build_link' )) {
                      
                    $link = vc_build_link( $atts['url'] );
                    if ( strlen( $link['url'] ) ) {  ?>

                    <div class="more-link">
                        <a href="<?php echo esc_url($link['url']); ?>" <?php if($link['target'] != '') {?>target="<?php echo esc_attr( $link['target'] ); ?>"<?php } ?>>[ <?php echo esc_attr($link['title']); ?> ]</a>
                    </div>

                <?php }
                }
            ?>

        </div>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Icon-Box-Circle
/*-----------------------------------------------------------------------------------*/

function epico_sc_iconbox_circle($atts, $content=null)
{
    extract(shortcode_atts(array(
        'title'         => '',
        'title_color'   => '',
        'icon_animation' => 'none',
        'icon_animation_delay' => '0',
        'responsive_animation' => 'disable',
        'icon'          => 'keyboard-o',
        'icon_color'         => '',
        'icon_border'         => 'circle',
        'icon_border_color'         => '',
        'icon_background_fill'         => 'fillbackground',
        'icon_position' => 'top',
        'url'           => '',
        'content_text' => '',
        'content_color' => '',
    ), $atts));

    $hasTitle  = '' != $title;
    $hasStyle = '' != $title_color || '' != $icon_color || '' != $content_color ;

    $id     = epico_sc_id('iconbox');
    $class  = array("iconbox");
    
    if( strlen($icon_position)) {

        $class[] = 'iconbox-top';

    }
    
    if( $icon_animation != 'none') {

        $class[] = ' shortcodeAnimation';

        if($responsive_animation != '')
        {
            $class[] = 'no-responsive-animation';
        }
    }
    
    if( strlen($icon_border)) {

        $class[] = " $icon_border";

    }
    
    if( strlen($icon_background_fill)) {

        $class[] = " $icon_background_fill";

    }

    ob_start();

    if($hasStyle)
    {
    ?>
    <style type="text/css" media="all">
        <?php if(strlen(esc_attr($title_color)))
        {
            echo "#$id  .title "; ?>
            {
                color: <?php echo esc_attr($title_color); ?>;
            }
        <?php
        }
        if(strlen(esc_attr($icon_color)))
        {
            echo "#$id .glyph"; ?>
            {
                color: <?php echo esc_attr($icon_color); ?>;
            }
        <?php
        }
        if(strlen(esc_attr($icon_border_color)))
        {
            echo "#$id .icon span.glyph"; ?>
            {
                border-color: <?php echo esc_attr($icon_border_color); ?>;
                background-color: <?php echo esc_attr($icon_border_color); ?>;
            }
        <?php
        }
        if(strlen(esc_attr($content_color)))
        {
            echo "#$id  .content , #$id .more-link a "; ?>
            {
                color: <?php echo esc_attr($content_color); ?>;
            }
        <?php
        }
        ?>
    </style>
    <?php
    }//if($hasStyle)
    ?>

    <div id="<?php echo esc_attr($id); ?>" class="<?php echo implode(' ', $class); ?>"  <?php if(strlen($icon_animation)) { ?> data-delay="<?php echo esc_attr($icon_animation_delay); ?>" data-animation="<?php echo esc_attr($icon_animation); ?>" <?php } ?>>
        <div class="icon">
            <span class="glyph icon-<?php echo esc_attr($icon); ?>"></span>
        </div>
        <div class="content-wrap">
            <?php if($hasTitle){ ?>
                <h3 class="title"><?php echo esc_attr($title); ?></h3>
            <?php } ?>
           
            <!-- iconbox content -->
            <?php if(strlen(esc_attr($content_text))) { ?>

                <div class="content">
                    <?php echo wp_kses( $content_text, $GLOBALS["allowed_tags"] ); ?>
                </div>
                
            <?php } ?>
            
            <!-- icon box read more button -->
            <?php if($url && function_exists( 'vc_build_link' )) {
                      
                    $link = vc_build_link( $atts['url'] );
                    if ( strlen( $link['url'] ) ) {  ?>

                    <div class="more-link">
                        <a href="<?php echo esc_url($link['url']); ?>" <?php if($link['target'] != '') {?>target="<?php echo esc_attr( $link['target'] ); ?>"<?php } ?>>[ <?php echo esc_attr($link['title']); ?> ]</a>
                    </div>

                <?php }
                }
            ?>

        </div>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Icon-Box-left
/*-----------------------------------------------------------------------------------*/

function epico_sc_iconbox_left($atts, $content=null)
{
    extract(shortcode_atts(array(
        'title'         => '',
        'title_color'   => '',
        'icon_animation' => 'none',
        'icon_animation_delay' => '0',
        'responsive_animation' => 'disable',
        'icon'          => 'keyboard-o',
        'icon_color'         => '',
        'icon_border_color'         => '',
        'icon_position' => 'left',
        'url'           => '',
        'content_text' => '',
        'content_color' => '',
    ), $atts));

    $hasTitle  = '' != $title;
    $hasStyle = '' != $title_color || '' != $icon_color || '' != $content_color ;

    $id     = epico_sc_id('iconbox');
    $class  = array("iconbox");
    
    if( strlen($icon_position)) {

        $class[] = 'iconbox-left';

    }
    
    if( $icon_animation != 'none' && strlen($icon_animation)) {

        $class[] = ' shortcodeAnimation';

        if($responsive_animation != '')
        {
            $class[] = 'no-responsive-animation';
        }
    }

    ob_start();

    if($hasStyle)
    {
    ?>
    <style type="text/css" media="all">
        <?php if(strlen(esc_attr($title_color)))
        {
            echo "#$id  .title "; ?>
            {
                color: <?php echo esc_attr($title_color); ?>;
            }
        <?php
        }
        if(strlen(esc_attr($icon_color)))
        {
            echo "#$id .glyph"; ?>
            {
                color: <?php echo esc_attr($icon_color); ?>;
            }
        <?php
        }
        if(strlen($content_color))
        {
            echo "#$id  .content , #$id .more-link a "; ?>
            {
                color: <?php echo esc_attr($content_color); ?>;
            }
        <?php
        }?>
    </style>
    <?php
    }//if($hasStyle)
    ?>

    <div id="<?php echo esc_attr($id); ?>" class="<?php echo implode(' ', $class); ?>"  <?php if(strlen(esc_attr($icon_animation))) { ?> data-delay="<?php echo esc_attr($icon_animation_delay); ?>" data-animation="<?php echo esc_attr($icon_animation); ?>" <?php } ?>>
        <div class="icon">
            <span class="glyph icon-<?php echo esc_attr($icon); ?>"></span>
        </div>
        <div class="content-wrap">
            <?php if(esc_attr($hasTitle)){ ?>
                <h3 class="title"><?php echo esc_attr($title); ?></h3>
            <?php } ?>
            <!-- iconbox content -->
            <?php if(strlen(esc_attr($content_text))) { ?>
                
                <div class="content">
                    <?php echo wp_kses( $content_text, $GLOBALS["allowed_tags"] ); ?>
                </div>
                    
            <?php } ?>      
            
            <!-- icon box read more button -->
            <?php if($url && function_exists( 'vc_build_link' )) {
                      
                    $link = vc_build_link( $atts['url'] );
                    if ( strlen( $link['url'] ) ) {  ?>

                    <div class="more-link">
                        <a href="<?php echo esc_url($link['url']); ?>" <?php if($link['target'] != '') {?>target="<?php echo esc_attr( $link['target'] ); ?>"<?php } ?>>[ <?php echo esc_attr($link['title']); ?> ]</a>
                    </div>

                <?php }
                }
            ?>

        </div>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Countdown
/*-----------------------------------------------------------------------------------*/

function epico_sc_countdown($atts, $content=null)
{
    extract(shortcode_atts(array(
        'end_date'  => '',
        'fontsize' => '28' ,
        'color'   =>  '' ,
        'label_color'   =>  '' ,
		'alignment'   =>  'center' ,
        'animation' => 'none' ,
        'animation_delay' => '1000',
        'responsive_animation' => 'disable',
    ), $atts));

    if($end_date == '')
        return;

    $id     = epico_sc_id('countdown');

    $class  = array("countdown-timer");

    if( $animation != 'none') {
        $class[] = 'shortcodeAnimation';

        if( $responsive_animation != '')
        {
            $class[] = 'no-responsive-animation';
        }
    }

    $colorStyle = $labelColorStyle = '';

    if($color != '')
    {
        $colorStyle = ' style="color:' . $color .'"';
    }

    if($label_color != '')
    {
        $labelColorStyle = ' style="color:' . $label_color .'"';
    }

	switch($alignment) {
        case 'right':
            $class[] = 'right';
            break;
        case 'center':
            $class[] = 'center';
            break;
        case 'left':
            $class[] = 'left';
            break;
    }



    ob_start();

    ?>
    
    <div id="<?php echo esc_attr($id); ?>" class="<?php echo implode(' ', $class); ?>" <?php if(strlen(esc_attr($animation))) { ?> data-delay="<?php echo esc_attr($animation_delay); ?>" data-animation="<?php echo esc_attr($animation); ?>" <?php } ?> data-end="<?php echo esc_attr($end_date); ?>" style="font-size:<?php echo $fontsize; ?>px">
        <div class="time-block">
            <span class="days number"<?php echo $colorStyle; ?>>0</span>
            <span class="label"<?php echo $labelColorStyle; ?>><?php echo esc_html__("Days", "vitrine"); ?></span>
        </div>
        <div class="time-block">
            <span class="hours number"<?php echo $colorStyle; ?>>0</span>
            <span class="label"<?php echo $labelColorStyle; ?>><?php echo esc_html__("Hours", "vitrine"); ?></span>
        </div>
        <div class="time-block">
            <span class="minutes number"<?php echo $colorStyle; ?>>0</span>
            <span class="label"<?php echo $labelColorStyle; ?>><?php echo esc_html__("Mins", "vitrine"); ?></span>
        </div>
        <div class="time-block">
            <span class="seconds number"<?php echo $colorStyle; ?>>0</span>
            <span class="label"<?php echo $labelColorStyle; ?>><?php echo esc_html__("Secs", "vitrine"); ?></span>
        </div>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Counter Box
/*-----------------------------------------------------------------------------------*/

function epico_sc_conterbox($atts, $content=null)
{
    extract(shortcode_atts(array(
        'counter_number'  => '500',
        'counter_number_color' => '',
        'counter_text_color' => '' ,
        'counter_animation' => 'none' ,
        'counter_text'   =>  esc_html__('Description', 'vitrine'),
        'counter_text2'   =>  '' ,
        'counter_animation_delay' => '1000',
        'responsive_animation' => 'disable',
    ), $atts));

    $hasStyle = '' != $counter_number_color || '' != $counter_text_color ;
    $counter_number = intval($counter_number);
    $id     = epico_sc_id('conterbox');

    $class  = array("counterBox");

    if( $counter_animation != 'none') {

        $class[] = 'shortcodeAnimation';

        if( $responsive_animation != '')
        {
            $class[] = 'no-responsive-animation';
        }
    }

    ob_start();

    ?>
    
    <div id="<?php echo esc_attr($id); ?>" class="<?php echo implode(' ', $class); ?>" <?php if(strlen(esc_attr($counter_animation))) { ?> data-delay="<?php echo esc_attr($counter_animation_delay); ?>" data-animation="<?php echo esc_attr($counter_animation); ?>" <?php } ?> data-countNmber="<?php echo esc_attr($counter_number); ?>">
        <span class="counterBoxNumber highlight" <?php if(strlen(esc_attr($counter_number_color))){ ?> style="color:<?php echo esc_attr($counter_number_color); ?>;" <?php } ?>>0<?php //echo esc_attr($counter_number); ?></span>
        <h4 class="counterBoxDetails" <?php if(strlen(esc_attr($counter_text_color))){ ?> style="color:<?php echo esc_attr($counter_text_color); ?>;" <?php } ?>><?php echo esc_attr($counter_text); ?></h4>


        <?php if(strlen(esc_attr($counter_text2))){ ?>

            <div class="counterBoxDetails2" <?php if(strlen(esc_attr($counter_text_color))){ ?> style="color:<?php echo esc_attr($counter_text_color); ?>;" <?php } ?>><?php echo esc_attr($counter_text2); ?></div>

        <?php } ?>

    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Embed Video
/*-----------------------------------------------------------------------------------*/

function epico_sc_embed_video($atts)
{
	$class[] = '';
    extract(shortcode_atts(array(

        'video_display_type'  => 'local_video',
        'video_autoplay'  => 'enable',
        'video_poster_image'  => '',
        'video_background_image'  => '',
        'video_webm'  => '',
        'video_mp4'  => '',
        'video_ogv'  => '',
        'video_vimeo_id'  => '',
        'video_youtube_id'  => '',
		'alignment'  => '',
        'video_play_button_color'  => 'light',
        'animation'  => 'none',
        'delay'  => '0',
        'responsive_animation'  => 'disable',
        'el_aspect'  => '169',
        'text' => '', // Just used in Product detail video ( not included in VC )
    ), $atts));

    switch($alignment) {
        case 'right':
            $class[] = ' right';
            break;
        case 'center':
            $class[] = ' center';
            break;
        case 'left':
            $class[] = ' left';
            break;
    }

    if($video_display_type == 'local_video' || $video_display_type == 'local_video_popup' )
    {
        if($video_webm == '' && $video_mp4 == '' && $video_ogv == '')
        {
            return;
        }
    }
    elseif($video_display_type == 'embeded_video_vimeo' || $video_display_type == 'embeded_video_vimeo_popup')
    {
        if($video_vimeo_id == '')
        {
            return;
        }
    }
    elseif($video_display_type == 'embeded_video_youtube' || $video_display_type == 'embeded_video_youtube_popup')
    {
        if($video_youtube_id == '')
        {
            return;
        }
    }

    /* Video ID extractor*/
    $vimeoURL=$video_vimeo_id;
    $vimeoId=preg_replace("/[^0-9]/","",$vimeoURL);

    // detect youtube id form url 
    $youtubeURL= $video_youtube_id;
    if ($youtubeURL && ( $video_display_type == "embeded_video_youtube_popup" ||  $video_display_type == "embeded_video_youtube" )) {
        $youtubeId = explode("?v=", $youtubeURL); // For videos like http://www.youtube.com/watch?v=...
        if (empty($youtubeId[1])){
        $youtubeId = explode("/v/", $youtubeURL); // For videos like http://www.youtube.com/watch/v/..
        }else{
        $youtubeId = explode("&", $youtubeId[1]); // Deleting any other params
        }       
        $youtubeId = $youtubeId[0];        
    }
 
    if (is_numeric($video_background_image)) {
        $video_background_image = wp_get_attachment_url($video_background_image);
    }

    if (is_numeric($video_poster_image)) {
        $video_poster_image = wp_get_attachment_url($video_poster_image);
    }

    $id     = epico_sc_id('embed_video');

    ob_start();

    ?>    

    <?php if( $video_display_type == 'local_video_popup' ) { ?>

        <!-- Hidden video div -->
        <div style="display:none;" id="video<?php echo esc_attr($id); ?>">
            <video class="lg-video-object lg-html5 video-js vjs-default-skin" controls preload="none" <?php if($video_poster_image) {?> poster="<?php echo esc_url($video_poster_image); ?>" <?php } ?>>

                <?php if ( esc_attr($video_webm) ) { ?>
                    <source src="<?php echo esc_attr($video_webm); ?>" type="video/webm">
                <?php } ?>

                <?php if ( esc_attr($video_mp4) ) { ?>
                    <source src="<?php echo esc_attr($video_mp4); ?>" type="video/mp4">
                <?php } ?>

                <?php if ( esc_attr($video_ogv) ) { ?>
                    <source src="<?php echo esc_attr($video_ogv); ?>" type="video/ogv">
                <?php } ?>    
           
            </video>
        </div>

    <?php } ?>

    <?php if( $video_display_type == 'local_video_popup' || $video_display_type == 'embeded_video_youtube_popup' || $video_display_type == 'embeded_video_vimeo_popup' ) { ?>

        <div id="<?php echo esc_attr($id); ?>" class="video_embed_container <?php if( $animation != 'none') { ?>  shortcodeAnimation <?php if($responsive_animation != '') { echo ' no-responsive-animation';} } ?>"  <?php if(strlen(esc_attr($animation))) { ?> data-delay="<?php echo esc_attr($delay); ?>" data-animation="<?php echo esc_attr($animation); ?>" <?php } ?>>

            <?php if( $video_display_type == 'local_video_popup' ) { ?>


                <!-- data-src should not be provided when you use html5 videos -->
                <a data-html="#video<?php echo esc_attr($id); ?>">

                    <?php if (esc_attr($video_background_image)) { ?>
                        <img src="<?php echo esc_url($video_background_image); ?>" alt="" />
                    <?php } ?>

                    <div class="play-button <?php  echo esc_attr($video_play_button_color); echo implode($class);?>">
                        <span class="glyph icon  icon-play2"></span>
                    </div>
                    <?php
                    if($text != '')
                        echo '<span class="text">' . $text . '</span>';
                    ?>

                </a>

            <?php }  else if ( $video_display_type == 'embeded_video_youtube_popup' ) { ?> 

                <!-- Youtube popUp -->
                <a class="image" href="https://youtu.be/<?php echo esc_attr($youtubeId); ?>">      

                    <?php if (esc_url($video_background_image))  { ?>
                        <img src="<?php echo esc_url($video_background_image); ?>" alt="" />
                    <?php } ?>

                     <div class="play-button <?php echo esc_attr($video_play_button_color); ?>">
                        <span class="glyph icon  icon-play2"></span>
                    </div>
                    <?php
                    if($text != '')
                        echo '<span class="text">' . $text . '</span>';
                    ?>

                </a>
        
            <?php }  else if ( $video_display_type == 'embeded_video_vimeo_popup' ) { ?> 
            
                <!-- Vimeo popUp -->
                <a class="image" href="https://vimeo.com/<?php echo esc_attr($vimeoId); ?>" >

                    <?php if (esc_url($video_background_image))  { ?>
                        <img src="<?php echo esc_url($video_background_image); ?>" alt="" />
                    <?php } ?>

                     <div class="play-button <?php echo esc_attr($video_play_button_color); ?>">
                        <span class="glyph icon  icon-play2"></span>
                    </div>
                    <?php
                    if($text != '')
                        echo '<span class="text">' . $text . '</span>';
                    ?>

                </a>

            <?php } ?>

        </div> 

    <?php } ?>

    <?php if( $video_display_type == 'local_video' ) { ?>
        
        <!-- HTML5 Video popUp -->
        <div class="inline_video video_embed_container">
            <video id="player1" class="video" width="320" height="240" <?php if($video_poster_image) { ?>poster="<?php echo esc_url($video_poster_image); ?>"<?php } ?> controls="controls" preload="auto"  <?php if ($video_autoplay == 'enable') { ?> autoplay <?php } ?> >
    
                <?php if ( esc_attr($video_webm) ) { ?>
                    <source src="<?php echo esc_url($video_webm); ?>" type="video/webm">
                <?php } ?>

                <?php if ( esc_attr($video_mp4) ) { ?>
                    <source src="<?php echo esc_url($video_mp4); ?>" type="video/mp4">
                <?php } ?>

                <?php if ( esc_attr($video_ogv) ) { ?>
                    <source src="<?php echo esc_url($video_ogv); ?>" type="video/ogv">
                <?php } ?>    

                <object width="320" height="240" type="application/x-shockwave-flash" data="<?php echo get_template_directory_uri(); ?>/js/flashmediaelement.swf">
                    <param name="movie" value="<?php echo get_template_directory_uri(); ?>/js/flashmediaelement.swf" />
                    <param name="flashvars" value="controls=true&file='<?php echo esc_url($video_mp4); ?>" />

                    <?php if ($video_poster_image) { ?>

                        <img src="<?php echo esc_url($video_poster_image); ?>" width="1920" height="800" title="No video playback capabilities" alt="Video thumb" />

                    <?php } ?>    

                </object>
            </video>    

            <?php if (esc_attr($video_poster_image)) { ?>

                <div class="play-button <?php echo esc_attr($video_play_button_color); ?>">
                    <span class="glyph icon  icon-play2"></span>
                </div>

            <?php } ?>
            
    
        </div>

    <?php } else if ( $video_display_type == 'embeded_video_vimeo' ) { ?>

        <div id="<?php echo esc_attr($id); ?>">

            <?php 
                $video_w = 500;
                $video_h = $video_w / 1.61; //1.61 golden ratio
                $link = 'http://vimeo.com/'.$vimeoId;
                $el_aspect = 'vc_video-aspect-ratio-'.$el_aspect;
                global $wp_embed;
                $embed = $wp_embed->run_shortcode('[embed  width="' . esc_attr($video_w) . '"     height="' . esc_attr($video_h) . '"]' . $link . '[/embed]' ); 
            ?>
        
            <div class="wpb_video_widget wpb_content_element vc_clearfix <?php echo esc_attr($el_aspect); ?>">
                <div class="wpb_wrapper">
                    <div class="wpb_video_wrapper"> <?php echo $embed ; ?> </div>
                </div>
            </div>
        </div>  

    <?php } else if ( $video_display_type == 'embeded_video_youtube' ) { ?>

        <div id="<?php echo esc_attr($id); ?>">

            <?php 
                $el_aspect = 'vc_video-aspect-ratio-169';
            ?>

            <div class="wpb_video_widget wpb_content_element vc_clearfix <?php echo esc_attr($el_aspect); ?>">
                <div class="wpb_wrapper">
                    <div class="wpb_video_wrapper"> 
                        <iframe title="YouTube video player" src="https://www.youtube.com/embed/<?php echo esc_attr($youtubeId); ?>" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Audio SoundCloud
/*-----------------------------------------------------------------------------------*/

function epico_sc_audio_soundcloud($atts, $content=null)
{
    extract(shortcode_atts(array(
        'soundcloud_id'  => '',
        'soundcloud_height'=>  'auto',
        'soundcloud_style'=> 'full_width_thumbnail',
        'soundcloud_color'=> '',
    ), $atts));

    $id= epico_sc_id('audio_soundcloud');?>
    
    <?php
    ob_start();
    ?>

    <div class="soundcloud_shortcode" id="<?php echo esc_attr($id); ?>">
    <?php
         if(esc_attr($soundcloud_style)=='full_width_thumbnail'){
          echo  '<iframe width="100%" height="'.esc_attr($soundcloud_height).'" scrolling="no" frameborder="no"  src="https://w.soundcloud.com/player/?url='.esc_attr($soundcloud_id).'&amp;visual=true"></iframe>';
        }else{
          echo  '<iframe width="100%" height="auto" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='.esc_attr($soundcloud_id).'&amp;color='.str_replace("#","",esc_attr($soundcloud_color)).'"></iframe>';
        }?>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Tabs
/*-----------------------------------------------------------------------------------*/

function epico_sc_tab_group($atts, $content=null)
{
    $GLOBALS['ep_sc_tab'] = array();
    do_shortcode($content);
    $tabs = $GLOBALS['ep_sc_tab'];

    ob_start();
    ?>
    <div class="tabs">
    <?php if(count($tabs)){ ?>
        <ul class="head clearfix">
            <?php foreach($tabs as $tab){ ?>
            <li><?php echo esc_attr($tab[0]); ?></li>
            <?php } ?>
        </ul>
        <div class="content">
            <?php foreach($tabs as $tab){ ?>
                <div class="tab-content"><?php echo esc_attr($tab[1]); ?></div>
            <?php } ?>
        </div>
    <?php } ?>
    </div>
    <?php
    return ob_get_clean();
}

function epico_sc_tab($atts, $content=null)
{
    $tabCnt = count($GLOBALS['ep_sc_tab']) + 1;

    extract(shortcode_atts(array(
        'title' => "Tab $tabCnt",
    ), $atts));

    $GLOBALS['ep_sc_tab'][] = array($title, do_shortcode($content));
}

/*-----------------------------------------------------------------------------------*/
/*  Button
/*-----------------------------------------------------------------------------------*/

function epico_sc_button($atts, $content = null)
{
    extract(shortcode_atts(array(
        'title'            => '',
        'text'             => esc_html__('View Page', 'vitrine'),
        'url'              => '#',
        'size'           => 'small',
        'button_text_color'  => '',
        'button_border_color' => '#3B5998',
        'button_hover_color' => '',
        'alignment'  => 'left',
		'position'  => 'row',
        'button_icon'  => '',
        'button_icon_position'  => '',
        'button_hover_style'  => 'style1',
        'button_border_radius'  => '1px',
        'button_border_width'  => '1px',
        'button_bg_style'  => 'transparent',
        'button_text_hover_color'  => '#333',
        'button_bg_color'  => '#3B5998',
        'button_bg_border_color'  => '#3B5998',
    ), $atts));
    
    //button_bg_color is for style1 and button_bg_border_color is for style2
    $button_bg_color=$button_bg_border_color;
    
    $class  = array();
    $class[] = "ep_button";
    
    switch($size) {
        case 'small':
            $class[] = 'button-small';
            break;
        case '':
            $class[] = 'button-small';
            break;
        case 'large':
            $class[] = 'button-large';
            break;
    }

    switch($alignment) {
		case 'right':
            $class[] = 'right';
            break;
        case 'center':
            $class[] = 'center';
            break;
        case 'left':
            $class[] = 'left';
            break;
    }   
	switch($position) {
		case 'row':
            $class[] = 'row';
            break;
        case 'separate':
            $class[] = 'separate';
            break;
    }

    switch($button_hover_style) {
        case 'style1':
            $class[] = 'style1';
            break;
        case 'style2':
            $class[] = 'style2';
            break;
        case 'style3_border':
            $class[] = 'style3_border';
            break;
    }

    switch($button_bg_style) {
        case 'fill':
            $class[] ='fill';
            break;
        case 'transparent':
            $class[] ='transparent';
            break;
    }
    
     if(strlen($button_icon)) { 
        $class[] ='hasIcon';
     }

    switch($button_icon_position) {
        case 'left':
            $class[] ='buttoniconleft';
            break;
        case 'right':
            $class[] ='buttoniconright';
            break;
    }

    $id = epico_sc_id('button');       
    $hasStyle = '' != $button_text_color || '' != $button_hover_color || '' !=  $button_border_radius || '' != $button_border_width ; 

    if($url && function_exists( 'vc_build_link' ))
    {
        $link = vc_build_link( $url );
    }

    ob_start();

    if($hasStyle)  { ?>

    <style type="text/css" media="all">

        <?php if( strlen(esc_attr($button_hover_style)) != 'style3_border') { ?>

            <?php if(strlen(esc_attr($button_border_radius))) {
                echo  "#$id.ep_button"; ?>
                {
                    border-radius:<?php echo esc_attr($button_border_radius); ?>;
                }    
    
            <?php } ?>

        <?php } ?>
        
        <?php if(strlen(esc_attr($button_border_width))) {
            echo  "#$id.ep_button"; ?>
            {
                border-width:<?php echo esc_attr($button_border_width); ?>;
            }    
    
        <?php } ?>

        <?php 
        if(strlen($button_text_hover_color)) {
            echo  "#$id.ep_button span.txt:before,#$id.ep_button span.icon .hovericon"; ?>
            {
               color:<?php echo esc_attr($button_text_hover_color); ?>;
            }    
        <?php } ?>

        <?php if(strlen(esc_attr($button_bg_color))) {

            echo  "#$id.ep_button.style1, #$id.ep_button.style2"; ?>
            {
                border-color:<?php echo esc_attr($button_bg_color); ?>;
            }

            <?php 
            if($button_hover_style == 'style2')
            {

                if($button_bg_style == 'transparent') {

                    echo  "#$id.ep_button:hover"; ?>
                    {
                       background-color:<?php echo esc_attr($button_bg_color); ?>;
                    } 

            <?php
                } else if ($button_bg_style == 'fill') {

                    echo  "#$id.ep_button"; ?>
                    {
                       background-color:<?php echo esc_attr($button_bg_color); ?>;
                    } 

                 <?php   
                }
            }

        } ?>

    </style>

    <?php
    }//if($hasStyle)
    ?>  

	
	<?php  if ($position == "row") { ?>
        <div class="inlinestyle <?php echo esc_attr($alignment);?> ">
    <?php }   ?>
  
<?php if ($alignment == "center") { ?>
        <div class="centeralignment">
    <?php } ?>
    
        <a id="<?php echo esc_attr($id); ?>" class="<?php echo implode(' ', $class); ?>"  <?php if(strlen($button_text_color)) { ?> style="color: <?php echo esc_attr($button_text_color); ?>;" <?php } ?> href="<?php echo esc_url($link['url']); ?>" title="<?php echo esc_attr($title); ?>" <?php if($link['target'] != '') {?>target="<?php echo esc_attr( $link['target'] ); ?>"<?php } ?>>
    
        <?php  if( $button_hover_style == "style3_border") { ?>

            <div class="frame top" <?php if(strlen($button_border_color)) { ?> style="background-color: <?php echo esc_attr($button_border_color); ?>;" <?php } ?>>
                <div <?php if(strlen($button_hover_color)) { ?> style="background-color: <?php echo esc_attr($button_hover_color); ?>;" <?php } ?>></div>
            </div>
            <div class="frame right" <?php if(strlen($button_border_color)) { ?> style="background-color: <?php echo esc_attr($button_border_color); ?>;" <?php } ?>>
                <div <?php if(strlen($button_hover_color)) { ?> style="background-color: <?php echo esc_attr($button_hover_color); ?>;" <?php } ?>></div>
            </div>
            <div class="frame bottom" <?php if(strlen($button_border_color)) { ?> style="background-color: <?php echo esc_attr($button_border_color); ?>;" <?php } ?>>
                <div <?php if(strlen($button_hover_color)) { ?> style="background-color: <?php echo esc_attr($button_hover_color); ?>;" <?php } ?>></div>
            </div>
            <div class="frame left" <?php if(strlen($button_border_color)) { ?> style="background-color: <?php echo esc_attr($button_border_color); ?>;" <?php } ?>>
                <div <?php if(strlen($button_hover_color)) { ?> style="background-color: <?php echo esc_attr($button_hover_color); ?>;" <?php } ?>></div>
            </div>

        <?php } ?>
   
            <span class="txt" data-hover="<?php echo esc_attr($text); ?>">
                <?php echo esc_attr($text); ?>
            </span>

            <?php if(strlen($button_icon)) { ?>
                <span class="icon" <?php if(strlen($button_icon_position)) { ?> data-float="<?php echo esc_attr($button_icon_position); ?>" <?php } ?> data-hover="icon-<?php echo esc_attr($button_icon); ?>">
                    <span class="firsticon glyph icon-<?php echo esc_attr($button_icon); ?>"></span>
                    <span class="hovericon glyph icon-<?php echo esc_attr($button_icon); ?>"></span>
                </span>
            <?php } ?>

        </a>
        <div class="clearfix"></div>

    <?php if ($alignment == "center") { ?>
        </div>
    <?php } ?>   
	<?php  if ($position == "row") { ?>
        </div>
    <?php }   ?>


    
    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  VC Toggle Counter Box
/*-----------------------------------------------------------------------------------*/

function epico_sc_vctoggle($atts, $content=null)
{
    extract(shortcode_atts(array(
        'title' => '',
        'open'  => 'false',
    ), $atts));

    $id     = epico_sc_id('vc_toggle');
     
    ob_start();
    ?> 

    <div class="toggle_wrap<?php if ($open == 'true') { ?> wpb_toggle_open  <?php } ?>">
        <div class="wpb_toggle<?php if ($open == 'true') { ?> wpb_toggle_title_active<?php } ?>">
            <div class="border-bottom">
                <div class="icon icon-plus"></div>
                <div class="icon icon-minus"></div>
                <div class="title"><?php echo esc_attr($title); ?></div>
            </div>
        </div>
        <div class="toggle_content_wrap">
            <div class="wpb_toggle_content" <?php if ($open == 'true') { ?> style="display: block;"  <?php } ?>>
                <?php echo wpb_js_remove_wpautop($content); ?>
            </div>
        </div>
    </div>

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  VC carousel
/*-----------------------------------------------------------------------------------*/

function epico_sc_imagecarousel($atts, $content=null)
{
    extract(shortcode_atts(array(
        "nav_style" => "dark",
        "images"   => "",
        "hover_color" => "c0392b",
        "custom_hover_color" => "",
        "visible_items" => "1",
		"image_size" => '',
        "image_size_width" => '',
        "image_size_height" => '',
		"image_size_crop" => '',
        "zoom" => "",
        "gutter" => '',
        "naxt_prev_btn"  => "show",
        'enterance_animation'=> 'fadeInFromBottom',
        'responsive_animation' => 'disable',
        'is_autoplay' => 'on',
    ), $atts));

    $id = epico_sc_id('image_carousel');

    $gutter = ($gutter == 'no' ? 'no-gutter':''); 

    //Make an array of image IDs
    $image_ids = array();
    if($images)
    {
        $image_ids = explode(",",esc_attr($images));
    }
	
	if($image_size == 'custom' && $image_size_width != '' && $image_size_height != '')
	{
		if($image_size_crop == 'yes')
            {
                $image_size_crop = true;
            }
            else
            {
                $image_size_crop = false;
            }

        $image_size = array($image_size_width, $image_size_height, $image_size_crop);

    
	}
    else{
		$image_size = esc_attr($image_size);
	}
    
    ob_start();

    if($hover_color != 'c0392b')  { 

        $color = 'c0392b';
        if ( isset( $hover_color ) ) {
            if($hover_color != 'custom')
            {
                $color = "#" . $hover_color;
            }
            else
            {
                if(isset( $custom_hover_color ))
                {
                    $color = $custom_hover_color;
                }

            }
        }

        ?>

        <style type="text/css" media="all">

            <?php echo  "#$id.carousel .swiper-slide .image-container:before"; ?>
            {
                background-color:<?php echo esc_attr($color); ?>;
            }    

        </style>

    <?php
    }
    ?>

    <div id="<?php echo esc_attr($id); ?>" class="carousel <?php if ( $enterance_animation != 'default') { echo 'hasAnimation'; if($responsive_animation != ''){ echo ' no-responsive-animation';} } ?> <?php echo esc_attr($enterance_animation); ?> <?php echo esc_attr($gutter); ?> <?php if (strlen($nav_style)) { echo esc_attr($nav_style); }?> <?php echo ($zoom != ""? "zoom-hover":""); ?>" data-id="<?php echo esc_attr($id); ?>"   data-autoplay="<?php echo esc_attr($is_autoplay); ?>" >
        <div class="swiper-container swiper-container<?php echo esc_attr($id); ?> clearfix"  data-visibleitems="<?php if (strlen($visible_items)) { echo esc_attr($visible_items); }?>">
            <div class="swiper-wrapper">
                <?php
                    foreach($image_ids as $image_id) {
                        $image_url = wp_get_attachment_image_src( $image_id,  $image_size);
                        $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="'. esc_attr($image_url[1]) . '" height="'. esc_attr($image_url[2]) . '" data-src="'. esc_url($image_url[0]) . '" alt="carousel_image'. esc_attr($image_id) .'">';
                ?>

                    <div class="swiper-slide carousel_item">
                        <div class="image-container lazy-load lazy-load-on-load" style="padding-top:<?php echo epico_get_height_percentage($image); ?>%">
                            <?php
                            //sanitization performed in above lines!
                            echo $image; ?>
                        </div>
                    </div>

                <?php } ?>

            </div>
        </div>

        <?php if ( $naxt_prev_btn == 'show') { ?>

        <!-- Next Arrows -->
        <div class="arrows-button-next no-select"></div>

        <!-- Prev Arrows -->
        <div class="arrows-button-prev no-select"></div>

        <?php } ?>

    </div>

    <?php
    
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Showcase 
/*-----------------------------------------------------------------------------------*/

function epico_sc_showcase($atts,$content)
{
    extract(shortcode_atts(array(

        'title'  => '',
        'subtitle' => '',
        'nextsection'  => 'show',
        'bg_effect'  => 'kunburn',
        'direction'  => 'left-align',
        'style'  => 'dark',
        'hover_color' => '',
        'overlay' =>'show'
    ), $atts));
        
    $id = epico_sc_id('showcase');
    
    $GLOBALS["showcase_bgs"] = array();//Set/Reset
    $GLOBALS["showcase_titles"] = array();//Set/Reset

    $hasStyle = '' != $hover_color;

    ob_start();

    if($hasStyle)  { ?>

    <style type="text/css" media="all">
        <?php if(strlen(esc_attr($hover_color))) {
            echo  "#$id.showcase .item-list h6.active,";
            echo  "#$id.showcase .item-list h6:hover"; ?>
            {
                color:<?php echo esc_attr($hover_color); ?>;
            }        
        <?php } ?>
    </style>
    <?php
    }//if($hasStyle)

    $child_content = do_shortcode($content);

    if(isset($GLOBALS["showcase_bgs"][0]))
    {
        $GLOBALS["showcase_bgs"][0] = preg_replace("/showcase-bg/", "showcase-bg active firstactive", $GLOBALS["showcase_bgs"][0], 1);
    }

    ?>

<div id="<?php echo esc_attr($id); ?>" class="showcase <?php echo esc_attr($direction) . ' '. esc_attr($style) . ' ' . esc_attr($bg_effect); ?> " data-effect="<?php echo esc_attr($bg_effect) ?>">
    <div class="showcase-backgrounds<?php echo ($bg_effect == 'interactive_background')? ' interactive-background-image' : ''; ?>">
        <?php
            if( esc_attr($overlay) == "show" )
            { 
        ?>

        <div class="overlay"></div>

        <?php
            }
        ?>

        <div class="overlayMobile"></div>

        <?php
            foreach($GLOBALS["showcase_bgs"] as $bg) {
                echo $bg; // showcase_bgs performed before
            }
        ?>
    </div>
    <div class="showcase-content-container">
        <div class="container showcase-content-wrapper">

             <div class="row">
                <div class="span4 showcase-title">
                    <h3>
                        <?php echo wp_kses( $title, $GLOBALS["allowed_tags"] ); ?>
                        <span class="showcase_subtitle"><?php echo esc_attr($subtitle); ?></span>
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="span4 showcase-nav">
                    
                    <ul class="item-list">

                        <?php
                            foreach($GLOBALS["showcase_titles"] as $showcase_title_html) {
                                echo $showcase_title_html;
                            }
                        ?>

                    </ul>
                </div>
                <div class="span5 showcase-items">
                    <?php echo $child_content; ?>
                </div>
            </div>
        </div>

        <?php if(esc_attr($nextsection) == "show")
        {
        ?>
        <div class="container container-next-showcase">
            <span class="next-showcase">
                <a href="#" title="<?php esc_attr_e("NEXT",'vitrine') ?>"></a>
            </span>
        </div>
        <?php
        }
        ?>
    </div>
</div>


<?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Showcase Items 
/*-----------------------------------------------------------------------------------*/

function epico_sc_showcase_item($atts)
{
    extract(shortcode_atts(array(

        'button_text'  => '',
        'title'  => '',
        'subtitle'  => '',
        'text' => '',
        'text_bg' => 'hide',
        'bg'  => '',
        'images' => '',
        'outer_link' => ''
        
    ), $atts));
        
    $id = epico_sc_id('showcase-item');

    //Make an array of image IDs
    $image_ids = array();
    if($images)
    {
        $image_ids = explode(",",esc_attr($images));
    }
    

    //get background image
    $image_url = wp_get_attachment_url( esc_attr($bg), 'large' );
    $GLOBALS["showcase_bgs"][]  .= '<div data-bg-id="'. esc_attr($id) .'" class="showcase-bg" style="background-image:url('. esc_url($image_url) .')" ></div>';     
        
    //get the title
    $GLOBALS["showcase_titles"][]  .= '<li class="" data-bg-id="'. esc_attr($id) .'"><span data-hover="' . esc_attr($title) . ' ">'. esc_attr($title).'</span></li>';

    ob_start();
    ?>

        <div class="showcase-item" data-bg-id="<?php echo esc_attr($id); ?>">

            <div class="item-content <?php if( esc_attr($text_bg) == "show" ) { echo "text_bg"; } ?>">
              
                <span class="showcase_item_subtitle"><?php echo esc_attr($subtitle); ?></span>
                <p><?php echo wp_kses($text, $GLOBALS["allowed_tags"]); ?></p>
            </div>
            <?php
                if(!empty($image_ids))
                {
            ?>
                    <div class="item-pics  <?php if( esc_attr($text_bg) == "show" ) { echo "had_text_bg"; } ?>">
                        <div class="swiper-container">
                            <div class="swiper-wrapper ep_lightGallery">
                            <?php
                                foreach($image_ids as $image_id) {
                                    $image_url = wp_get_attachment_url( $image_id );
                                    $image_thumbnail_url = wp_get_attachment_image_src( $image_id, 'thumbnail' );
                                ?>

                                    <div class="swiper-slide ">
                                        <?php echo '<a href="'.esc_url($image_url) . '" class="galleryItem"><img alt="" src="'.esc_url($image_thumbnail_url[0]) . '" /></a>'; ?>
                                    </div>

                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
            <?php
                }

                if(strlen($outer_link) && function_exists( 'vc_build_link' ))
                {
                    $link = vc_build_link( $outer_link );
                    if ( strlen( $link['url'] ) )
                    {
                        ?>
                        <a href="<?php echo esc_url($link["url"]); ?>" <?php if($link['target'] != '') {?>target="<?php echo esc_attr( $link["target"] ); ?>"<?php } ?> class="showcase-link" title="<?php echo esc_attr($link["title"]); ?>"><?php echo  esc_attr($link["title"]); ?></a>
                        <?php
                    }
                }
            ?>

        </div>
    <?php
    return ob_get_clean();
}



/*-----------------------------------------------------------------------------------*/
/*  Portfolio
/*-----------------------------------------------------------------------------------*/

function epico_sc_portfolio($atts)
{
    extract(shortcode_atts(array(
        'type'  => 'portfolio_space',
        'title_bar' => 'show',
        'title_text' => '',
        'subtitle_text' => '',
        'portfolio_filter' => 'all',
        'filters' => '',
        'filter_display' => 'show',
        'filter_style' => 'standard',
        'filter_loadmore_style' => 'darkStyle',
        'filter_toggle_state' => 'close',
        'portfolio_posts_page' => '12',
        'enterance_animation'=> 'fadeInFromBottom',
        'responsive_animation' => 'disable',
        'portfolio_hover' => 'creativeType',
        'portfolio_hover_style' => 'lightStyle',
        'portfolio_hover_like_button' => 'show',
        'portfolio_masonry'=>'perfectMasonry'
    ), $atts));
    
    $id = epico_sc_id('portfolio');
   
    $portolio_type = $type;
    $title = $title_text;
    $subTitle = $subtitle_text;

    $pDajax = false;

    
    $portfolioId = $id;
    $id = str_replace('portfolio_', '', $id);
    $portfolioLoop =  'pLoop_'.$id;
    $pLoadMore = 'pLoadMore_'.$id;
    $pagedNum = 'paged_'.$id;

    $catArr = array();
    
    $portfolioItemNumber = 0;
    /* get Portfolio Skills if there is no selected skills */
    if($portfolio_filter == 'all')
    {
       $args = array(
            'fields' => 'ids', 
        );
        
        $filters = get_terms('skills', $args);
        $catArr  = $filters;
        $portfolioItemNumber = wp_count_posts('portfolio') -> publish;
    }
    else
    {
        $catArr  = explode(',', $filters) ;

        $args = array( 
        'fields' =>'ids', //we don't really need all post data so just id wil do fine.
        'posts_per_page' => -1, //-1 to get all post
        'post_type' => 'portfolio', 
        'tax_query' => array(
            array(
                'taxonomy' => 'skills',
                'field' => 'slug',
                'terms' => $catArr
            )
        )
        );
        $ps = get_posts( $args );
        $portfolioItemNumber = count($ps);

        $args = array(
            'fields' => 'ids',
            'slug'   => $catArr,
        );
        
        $filters = get_terms('skills', $args);
        
    }
    

    if(count($catArr) == 0 || count($catArr) > 1)
    {
        if ( $portfolio_filter == 'all' ) {
            // Generate All Filter Taxonomy
            $listCatsArgs = array('title_li' => '', 'taxonomy' => 'skills', 'walker' => new epico_portfolio_walker(), 'echo' => 0, 'include' => '' );
        } else {
             // Generate  Selected Taxonomy
            $listCatsArgs = array('title_li' => '', 'taxonomy' => 'skills', 'walker' => new epico_portfolio_walker(), 'echo' => 0, 'include' => implode("," , $filters ));           
        }

        $catList = '<li class="current"><span class="filter_item active" data-filter="*" >'.esc_html__('All', 'vitrine').'<span class="filterline"></span><span class="post-count">'. sprintf("%02d", $portfolioItemNumber) .'</span></span></li>';
        $catList .= wp_list_categories($listCatsArgs);
    }
    
    
    if ( $portfolio_filter== 'custom' ) {


        $portfolio_skills = array();
        $cat_args = array(
            'orderby'       => 'term_id', 
            'order'         => 'ASC',
            'hide_empty'    => false,
            'slug' => $catArr
        );

        $terms = get_terms('skills', $cat_args);
        foreach($terms as $taxonomy){
             $portfolio_skills[] = $taxonomy->term_id;
        }

        // Add some parameters for the JS - portfolio load more .
        $queryArgs = array (
            'post_type'      => 'portfolio',//post type, I used 'product'
            'post_status'   => 'publish', // just tried to find all published post
            'posts_per_page' =>   -1,
            'fields' => 'ids',
            'pageVar' => 'list1',
            'tax_query' => array(
                array(
                    'taxonomy' => 'skills',
                    'terms'    => $portfolio_skills
                )
            )
        );

    } else {

        // Add some parameters for the JS - portfolio load more .
        $queryArgs = array (
            'post_type'      => 'portfolio',//post type, I used 'product'
            'post_status'   => 'publish', // just tried to find all published post
            'posts_per_page' =>   -1,
             'fields' => 'ids',
            'pageVar' => 'list1',
        );
    }

    $query = new WP_Query($queryArgs);
    $ppaged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
    $pMax = $query-> max_num_pages;
    $countPosts = $query -> found_posts;
    $maxPages =  ceil ($countPosts / $portfolio_posts_page)  ;
    wp_reset_postdata();
    ob_start();
        
    ?>

    <!-- Portfolio Section  -->
    <div id="<?php echo esc_attr($portfolioId); ?>" data-portfolio-type="<?php echo esc_attr($type); ?>" data-value="<?php echo esc_attr($portfolioId); ?>"  data-id="<?php echo esc_attr($id); ?>" data-startPage="<?php echo esc_attr($ppaged); ?>" data-maxPages="<?php echo esc_attr($maxPages); ?>" data-nextLink="<?php echo next_posts($pMax, false); ?>" class="epicoSection portfolioSection <?php echo esc_attr($filter_loadmore_style); ?> <?php echo esc_attr($type); ?> <?php if ($portfolio_hover_like_button == 'hide') { ?> hideLikeBtn <?php } ?>" data-layout-style="<?php echo esc_attr($portfolio_masonry);?>">

        <div  class="portfoliowrap wrap <?php if ( !empty( $subTitle ) ) { ?> hassubtitle <?php } ?> ">

            <div class="container title_container <?php  if ( ($title_bar == 'show' && !empty( $title ) && !empty($subTitle)) || $filter_display == 'show' ) { ?> portfolio_height <?php } ?> clearfix">

                <?php  if ( (!empty( $subTitle ) || !empty( $title ) ) || $title_bar == 'show' ) { ?>
                
                        <?php if ( $title_bar == 'show' ) { ?>
                            <div class="titleSpace">
                    
                                <?php if ( !empty( $title )) { ?>
                                
                                    <div class="title"><h3><?php echo esc_attr($title); ?></h3></div>
                    
                                <?php } if ( !empty( $subTitle ) ) { ?>
                         
                                    <div class="subtitle"><?php echo esc_attr($subTitle); ?></div>
                         
                                <?php } ?>
                        
                            </div>
                        <?php }  ?>
                        
                    
                <?php }  ?>
                
            </div>

            <?php  if ( $filter_display == 'show' ){
                        if ( count($catArr)!== 1 ){ ?>

                    <!-- portfolio filter - desktop -->
                    <div class="container title_container visible-desktop clearfix">
                        <div class="portfolio-header">
                        
                            <ul class="filters option-set subnavigation clearfix <?php echo esc_attr($filter_style); ?>-style toggleClicked<?php if($filter_toggle_state == "open"){ echo " openToggle"; } ?>" data-option-key="filter">

                                <?php
                                //Sanitized in above lines!
                                echo $catList;

                                if($filter_style == 'toggle') {
                                    ?>
                                    <li class="filterToggle<?php if($filter_toggle_state == "open"){ echo " closed"; } ?>">
                                        <div class="toggleLineContainer">
                                            <span class="lineBarFirst"></span>
                                            <span class="lineBarSecond"></span>
                                        </div>
                                        <div class="filterRightLine"></div>
                                        <div class="filterLeftLine"></div>
                                    </li>
                                    <?php
                                }

                                ?>

                            </ul>
                        </div>
                    </div>

                    <!-- portfolio filter - tablet & phone -->
                    <div class="hidden-desktop clearfix">
                        <ul class="filterstablet responsive_filter_dropdown portfolio-filter" data-option-key="filter">
                            <li class="">
                                <div>
                                    <span class="text"><?php esc_html_e('All', 'vitrine') ?></span>
                                    <span class="icon icon-angle-down"></span>
                                </div>
                                <ul class="portfolio-filter-items">
                                    <?php
                                    //Sanitized in above lines!
                                    echo $catList; ?>
                                </ul>
                            </li>
                        </ul>
                    </div>

            <?php }
                } ?>
                
            <div class="portfolio_wrap">

                <!-- portfolio items  -->
                <div class="isotope <?php echo esc_attr($portfolio_hover) . ' ' . esc_attr($portfolio_hover_style). ' ' . esc_attr($enterance_animation); if($responsive_animation != ''){ echo ' no-responsive-animation'; }; ?>" data-skills="<?php if($portfolio_filter == 'all' ) { echo 'all'; } else {echo implode(" ",$filters); } ?>">
                    <div id="<?php echo esc_attr($portfolioLoop); ?>">

                        <?php

                            $paged1 = isset( $_GET[$pagedNum] ) ? (int) $_GET[$pagedNum] : 1;

                            $queryArgs = array(

                                'post_type'      => 'portfolio',
                                'posts_per_page' => $portfolio_posts_page,
                                'paged'          => $paged1
                            );


                            if ( $portfolio_filter== 'custom' ) {

                                //Taxonomy filter
                                if(count($catArr))
                                {

                                    $queryArgs['tax_query'] =  array(
                                        // Note: tax_query expects an array of arrays!
                                        array(
                                            'taxonomy' => 'skills',
                                            'field'    => 'slug',
                                            'terms'    => $catArr
                                        ));
                                }
                            }

                            $count = 1;
                            $query = new WP_Query($queryArgs);

                            while ($query->have_posts()) {

                                $query->the_post();

                                epico_get_template_part( 'templates/portfolio-thumbnail', [ '$portolio_type' => $portolio_type  , '$portfolioLoop' => $portfolioLoop , '$count' => $count , '$portfolio_masonry' => $portfolio_masonry , '$pDajax ' => $pDajax  ] );

                                $count++;
                            }

                            wp_reset_postdata();

                        ?>
                    </div>
                </div>
            </div>
            <?php

                $query = new WP_Query($queryArgs);
                if ( $query-> have_posts()) { ?>

                    <!-- portfolio load more button -->
                    <div class="pLoadMore <?php echo esc_attr($pLoadMore); ?>  clearfix">
                        <div class="readmore clearfix">
                            <div class="loadMore" data-id="<?php echo esc_attr($id); ?>">
                                <span class="text load-more-text"><?php esc_html_e("Load more",'vitrine'); ?></span>
                                <span class="text loading-text"><?php esc_html_e("Loading...",'vitrine'); ?></span>
                            </div>
                        </div>
                    </div>

            <?php } ?>

            <?php wp_reset_postdata(); ?>

        </div>
    </div>
    <!-- End Portfolio  -->

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Portfolio inner
/*-----------------------------------------------------------------------------------*/

function epico_sc_portfolio_inner($atts)
{
    extract(shortcode_atts(array(
        'type'  => 'portfolio_space',
        'title_bar' => 'show',
        'title_text' => '',
        'subtitle_text' => '',
        'portfolio_filter' => 'all',
        'filters' => '',
        'filter_display' => 'show',
        'filter_style' => 'standard',
        'filter_loadmore_style' => 'darkStyle',
        'filter_toggle_state' => 'close',
        'portfolio_posts_page' => '12',
        'enterance_animation'=>'fadeInFromBottom',
        'responsive_animation' => 'disable',
        'portfolio_hover' => 'creativeType',
        'portfolio_hover_style' => 'lightStyle',
        'portfolio_hover_like_button' => 'show',
        'portfolio_masonry'=>'perfectMasonry'
    ), $atts));

    $id = epico_sc_id('portfolio');
   
    $portolio_type = $type;
    $title = $title_text;
    $subTitle = $subtitle_text;
    
    $portfolioId = $id;
    $id = str_replace('portfolio_', '', $id);
    $portfolioLoop =  'pLoop_'.$id;
    $pLoadMore = 'pLoadMore_'.$id;
    $pagedNum = 'paged_'.$id;
    
    $pDajax = true;

    $catArr = array();

    $portfolioItemNumber = 0;
    /* get Portfolio Skills if there is no selected skills */
    if($portfolio_filter == 'all')
    {
       $args = array(
            'fields' => 'ids', 
        );
        
        $filters = get_terms('skills', $args);
        $catArr  = $filters;
        $portfolioItemNumber = wp_count_posts('portfolio') -> publish;
    }
    else
    {
        $catArr  = explode(',', $filters) ;

        $args = array( 
        'fields' =>'ids', //we don't really need all post data so just id wil do fine.
        'posts_per_page' => -1, //-1 to get all post
        'post_type' => 'portfolio', 
        'tax_query' => array(
            array(
                'taxonomy' => 'skills',
                'field' => 'slug',
                'terms' => $catArr
            )
        )
        );
        $ps = get_posts( $args );
        $portfolioItemNumber = count($ps);

        $args = array(
            'fields' => 'ids',
            'slug'   => $catArr,
        );
        
        
        $filters = get_terms('skills', $args);
        
    }
    

    if(count($catArr) == 0 || count($catArr) > 1)
    {
        if ( $portfolio_filter == 'all' ) {
            // Generate All Filter Taxonomy
            $listCatsArgs = array('title_li' => '', 'taxonomy' => 'skills', 'walker' => new epico_portfolio_walker(), 'echo' => 0, 'include' => '' );
        } else {
            $listCatsArgs = array('title_li' => '', 'taxonomy' => 'skills', 'walker' => new epico_portfolio_walker(), 'echo' => 0, 'include' => implode("," , $filters) );           
        }

        $catList = '<li class="current"><span class="filter_item active" data-filter="*" >'.esc_html__('All', 'vitrine').'<span class="filterline"></span><span class="post-count">'. sprintf("%02d", $portfolioItemNumber) .'</span></span></li>';
        $catList .= wp_list_categories($listCatsArgs);
    }
    
    
    if ( $portfolio_filter == 'custom' ) {

        $portfolio_skills = array();
        $cat_args = array(
            'orderby'       => 'term_id', 
            'order'         => 'ASC',
            'hide_empty'    => false,
            'slug' => $catArr
        );

        $terms = get_terms('skills', $cat_args);
        foreach($terms as $taxonomy){
             $portfolio_skills[] = $taxonomy->term_id;
        }

        // Add some parameters for the JS - portfolio load more .
        $queryArgs = array (
            'post_type'      => 'portfolio',//post type, I used 'product'
            'post_status'   => 'publish', // just tried to find all published post
            'posts_per_page' =>   -1,
            'fields' => 'ids',
            'pageVar' => 'list1',
            'tax_query' => array(
                array(
                    'taxonomy' => 'skills',
                    'terms'    => $portfolio_skills
                )
            )
        );

    } else {

        // Add some parameters for the JS - portfolio load more .
        $queryArgs = array (
            'post_type'      => 'portfolio',//post type, I used 'product'
            'post_status'   => 'publish', // just tried to find all published post
             'fields' => 'ids',
            'posts_per_page' =>   -1,
            'pageVar' => 'list1',
        );
    }

    $query = new WP_Query($queryArgs);
    $ppaged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
    $pMax = $query-> max_num_pages;
    $countPosts = $query -> post_count;
    $maxPages =  ceil ($countPosts / $portfolio_posts_page)  ;
    wp_reset_postdata();
    ob_start();
        
    ?>

    <!-- Portfolio Section  -->
    <div id="<?php echo esc_attr($portfolioId); ?>" data-portfolio-type="<?php echo esc_attr($type); ?>" data-value="<?php echo esc_attr($portfolioId); ?>" data-id="<?php echo esc_attr($id); ?>" data-startPage="<?php echo esc_attr($ppaged); ?>" data-maxPages="<?php echo esc_attr($maxPages); ?>" data-nextLink="<?php echo next_posts($pMax, false); ?>" class="epicoSection portfolioSection <?php echo esc_attr($filter_loadmore_style); ?> <?php echo esc_attr($type); ?> <?php if ($portfolio_hover_like_button == 'hide') { ?> hideLikeBtn <?php } ?>">

        <div class="portfoliowrap wrap <?php if ( !empty( $subTitle ) ) { ?> hassubtitle <?php } ?>">

        <div class="pDWrap clearfix">

            <!-- portfolio Detail loader-->
            <div id="loader"></div>
            
            <div id="pDError"><?php esc_html_e('Sorry, we ran into a technical problem (unknown error). Please try again...', 'vitrine') ?> </div>

             <div class="navWrap pDNavigation">
             
                <a href="#" title="<?php esc_attr_e('NEXT', 'vitrine'); ?>"  class="next no_djax">
                    <div class="arrows-button-next no-select">
                        <span class="text">
                            <?php esc_html_e('NEXT', 'vitrine'); ?>
                        </span>
                    </div>
                </a>
                <!-- Back to portfolio -->
                <a id="PDclosePortfolio" class="no_djax" href="#" title="<?php esc_attr_e('Back to portfolio', 'vitrine'); ?>">
                    <div>
                        <span class="backToPortfolio" data-name="grid2"></span>
                    </div>
                </a>

                <!-- Prev Arrows -->
                <a href="#" title="<?php esc_attr_e('PREV', 'vitrine'); ?>"  class="previous no_djax">
                    <div class="arrows-button-prev no-select">
                        <span class="text">
                            <?php esc_html_e('PREV', 'vitrine'); ?>
                        </span>
                    </div>
                </a>

            </div>

            <!-- portfolio Detail Content -->
            <div id="portfolioDetailAjax"></div>

        </div>
        
        <div class="container title_container <?php  if ( $title_bar == 'show'  || $filter_display == 'show' ) { ?> portfolio_height <?php } ?> clearfix">

                <?php  if ( (!empty( $subTitle ) || !empty( $title ) ) || $title_bar == 'show' ) { ?>
                
                    <?php if ( $title_bar == 'show' ) { ?>
                        <div class="titleSpace">
                    
                            <?php if ( !empty( $title )) { ?>
                                
                                <div class="title"><h3><?php echo esc_attr($title); ?></h3></div>
                    
                            <?php } if ( !empty( $subTitle ) ) { ?>
                         
                                <div class="subtitle"><?php echo esc_attr($subTitle); ?></div>
                         
                            <?php } ?>
                        
                        </div>
                    <?php }  ?>
                    
                <?php }  ?>
                
            </div>

            <?php  if ( $filter_display == 'show' ){
                        if ( count($catArr)!== 1 ){ ?>

                    <!-- portfolio filter - desktop -->
                    <div class="container title_container visible-desktop clearfix">
                        <div class="portfolio-header">
                        
                            <ul class="filters option-set subnavigation clearfix <?php echo esc_attr($filter_style); ?>-style toggleClicked<?php if($filter_toggle_state == "open"){ echo " openToggle"; } ?>" data-option-key="filter">

                                <?php
                                //Sanitized in above lines!
                                echo $catList;

                                if($filter_style == 'toggle') {
                                    ?>
                                    <li class="filterToggle<?php if($filter_toggle_state == "open"){ echo " closed"; } ?>">
                                        <div class="toggleLineContainer">
                                            <span class="lineBarFirst"></span>
                                            <span class="lineBarSecond"></span>
                                        </div>
                                        <div class="filterRightLine"></div>
                                        <div class="filterLeftLine"></div>
                                    </li>
                                    <?php
                                }

                                ?>

                            </ul>
                        </div>
                    </div>

                    <!-- portfolio filter - tablet & phone -->
                    <div class="hidden-desktop clearfix">
                        <ul class="filterstablet  responsive_filter_dropdown portfolio-filter" data-option-key="filter">
                            <li class="">
                                <div>
                                    <span class="text"><?php esc_html_e('All', 'vitrine') ?></span>
                                    <span class="icon icon-angle-down"></span>
                                </div>
                                <ul class="portfolio-filter-items">
                                    <?php 
                                    //Sanitized in above lines!
                                    echo $catList; ?>
                                </ul>
                            </li>
                        </ul>
                    </div>

            <?php }
                } ?>
                
            <div id="isotopePortfolio" class="portfolio_wrap">
                <!-- portfolio items  -->
                <div class="isotope ajaxPDetail <?php echo esc_attr($portfolio_hover) . ' ' . esc_attr($portfolio_hover_style). ' ' . esc_attr($enterance_animation); if($responsive_animation != ''){ echo ' no-responsive-animation'; }; ?>" data-skills="<?php if($portfolio_filter == 'all') { echo 'all'; } else {echo implode(" ",$filters); } ?>" layout-style="<?php echo esc_attr($portfolio_masonry);?>">
                    <div id="<?php echo esc_attr($portfolioLoop); ?>" >

                        <?php

                            $paged1 = isset( $_GET[$pagedNum] ) ? (int) $_GET[$pagedNum] : 1;

                            $queryArgs = array(

                                'post_type'      => 'portfolio',
                                'posts_per_page' => $portfolio_posts_page,
                                'paged'          => $paged1
                            );


                            if ( $portfolio_filter== 'custom' ) {

                                //Taxonomy filter
                                if(count($catArr))
                                {

                                    $queryArgs['tax_query'] =  array(
                                        // Note: tax_query expects an array of arrays!
                                        array(
                                            'taxonomy' => 'skills',
                                            'field'    => 'slug',
                                            'terms'    => $catArr
                                        ));
                                }
                            }

                            $count = 1;
                            $query = new WP_Query($queryArgs);

                            while ($query->have_posts()) {

                                $query->the_post();

                                epico_get_template_part( 'templates/portfolio-thumbnail', [ '$portolio_type' => $portolio_type  , '$portfolioLoop' => $portfolioLoop , '$count' => $count , '$portfolio_masonry' => $portfolio_masonry , '$pDajax ' => $pDajax  ] );

                                $count++;
                            }

                            wp_reset_postdata();

                        ?>
                    </div>
                </div>
            </div>
            <?php

                $query = new WP_Query($queryArgs);
                if ( $query-> have_posts()) { ?>

                    <!-- portfolio load more button -->
                    <div class="pLoadMore <?php echo esc_attr($pLoadMore); ?>  clearfix">
                        <div class="readmore clearfix">
                            <div class="loadMore" data-id="<?php echo esc_attr($id); ?>">
                                <span class="text load-more-text"><?php esc_html_e("Load more",'vitrine'); ?></span>
                                <span class="text loading-text"><?php esc_html_e("Loading...",'vitrine'); ?></span>
                            </div>
                        </div>
                    </div>

            <?php } ?>

            <?php wp_reset_postdata(); ?>

        </div>
    </div>
    <!-- End Portfolio  -->

    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Gallery
/*-----------------------------------------------------------------------------------*/

function epico_sc_gallery($atts)
{
    extract(shortcode_atts(array(
        'type'  => 'portfolio_space',
        'title_bar' => 'show',
        'title_text' => '',
        'subtitle_text'=>'',
        'portfolio_filter' => 'all',
        'filters' => '',
        'filter_display' => 'show',
        'filter_style' => 'standard',
        'filter_loadmore_style' => 'darkStyle',
        'filter_toggle_state' => 'close',
        'gallery_posts_page' => '12',
        'portfolio_hover_like_button' => 'show',
        'portfolio_hover' => 'simpleGallery',
        'portfolio_hover_style' =>'lightStyle',
        'gallery_pop_up' => 'darkPopUp',
        'enterance_animation'=> 'fadeInFromBottom',
        'responsive_animation' => 'disable',
        'animation_style'=>'lg-fade',
        'portfolio_masonry'=>'perfectMasonry'
    ), $atts));

    $id = epico_sc_id('portfolio');
   
    $portolio_type = $type;
    $title = $title_text;

    $pDajax = false;

    
    $portfolioId = $id;
    $id = str_replace('portfolio_', '', $id);
    $portfolioLoop =  'pLoop_'.$id;
    $pLoadMore = 'pLoadMore_'.$id;
    $pagedNum = 'paged_'.$id;

    $catArr = array();
    $portfolioItemNumber = 0;
    /* get all of the gallery categories if there is none of them was selected*/
    if($portfolio_filter == 'all')
    {
       $args = array(
            'fields' => 'ids', 
        );
        
        $filters = get_terms('gallery_cat', $args);
        $catArr  = $filters;
        $portfolioItemNumber = wp_count_posts('gallery') -> publish;
    }
    else
    {
        $catArr  = explode(',', $filters) ;

        $args = array( 
        'fields' =>'ids', //we don't really need all post data so just id will do fine.
        'posts_per_page' => -1, //-1 to get all post
        'post_type' => 'gallery', 
        'tax_query' => array(
            array(
                'taxonomy' => 'gallery_cat',
                'field' => 'slug',
                'terms' => $catArr
            )
        )
        );
        $ps = get_posts( $args );
        $portfolioItemNumber = count($ps);

        $args = array(
            'fields' => 'ids',
            'slug'   => $catArr,
        );
        
        $filters = get_terms('gallery_cat', $args);
        
    }
    

    if(count($catArr) == 0 || count($catArr) > 1)
    {
        if ( $portfolio_filter == 'all' ) {
            // Generate All Filter Taxonomy
            $listCatsArgs = array('title_li' => '', 'taxonomy' => 'gallery_cat', 'walker' => new epico_portfolio_walker(), 'echo' => 0, 'include' => '' );
        } else {
             // Generate  Selected Taxonomy
            $listCatsArgs = array('title_li' => '', 'taxonomy' => 'gallery_cat', 'walker' => new epico_portfolio_walker(), 'echo' => 0, 'include' => implode("," , $filters ));           
        }


        $catList = '<li class="current"><span class="filter_item active" data-filter="*" >'.esc_html__('All', 'vitrine').'<span class="filterline"></span><span class="post-count">'. sprintf("%02d", $portfolioItemNumber) .'</span></span></li>';
        $catList .= wp_list_categories($listCatsArgs);

    }
    
    
    if ( $portfolio_filter== 'custom' ) {


        $gallery_cats = array();
        $cat_args = array(
            'orderby'       => 'term_id', 
            'order'         => 'ASC',
            'hide_empty'    => false,
            'slug' => $catArr
        );

        $terms = get_terms('gallery_cat', $cat_args);
        foreach($terms as $taxonomy){
             $gallery_cats[] = $taxonomy->term_id;
        }

        // Add some parameters for the JS - gallery load more .
        $queryArgs = array (
            'post_type'      => 'gallery',
            'post_status'   => 'publish', // just tried to find all published post
            'posts_per_page' =>   -1,
            'fields' => 'ids',
            'pageVar' => 'list1',
            'tax_query' => array(
                array(
                    'taxonomy' => 'gallery_cat',
                    'terms'    => $gallery_cats
                )
            )
        );

    } else {

        // Add some parameters for the JS - gallery load more .
        $queryArgs = array (
            'post_type'      => 'gallery',//post type, I used 'product'
            'post_status'   => 'publish', // just tried to find all published post
            'posts_per_page' =>   -1,
             'fields' => 'ids',
            'pageVar' => 'list1',
        );
    }

    $query = new WP_Query($queryArgs);
    $ppaged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
    $pMax = $query-> max_num_pages;
    $countPosts = $query -> found_posts;
    $maxPages =  ceil ($countPosts / $gallery_posts_page)  ;
    wp_reset_postdata();
    ob_start();
        
    ?>

    <!-- gallery Section  -->
    <div id="<?php echo esc_attr($portfolioId); ?>" data-portfolio-type="<?php echo esc_attr($type); ?>" data-value="<?php echo esc_attr($portfolioId); ?>"  data-id="<?php echo esc_attr($id); ?>" data-startPage="<?php echo esc_attr($ppaged); ?>" data-maxPages="<?php echo esc_attr($maxPages); ?>" data-nextLink="<?php echo next_posts($pMax, false); ?>" class="epicoSection portfolioSection <?php echo esc_attr($filter_loadmore_style); ?> <?php echo esc_attr($type); ?> <?php if ($portfolio_hover_like_button == 'hide') { ?> hideLikeBtn <?php } ?>" data-layout-style="<?php echo esc_attr($portfolio_masonry);?>">

        <div  class="portfoliowrap wrap">

            <div class="container title_container <?php  if ( ($title_bar == 'show' && !empty( $title )) || $filter_display == 'show' ) { ?> portfolio_height <?php } ?> clearfix">

                <?php  if ( ( !empty( $title ) ) || $title_bar == 'show' ) { ?>
                
                        <?php if ( $title_bar == 'show' ) { ?>
                            <div class="titleSpace">
                    
                                <?php if ( !empty( $title )) { ?>
                                
                                    <div class="title"><h3><?php echo esc_attr($title); ?></h3></div>
                                    
                                <?php } ?>
                        
                            </div>
                        <?php }  ?>
                        
                    
                <?php }  ?>
                
            </div>

            <?php  if ( $filter_display == 'show' ){
                        if ( count($catArr)!== 1 ){ ?>

                    <!-- gallery filter - desktop -->
                    <div class="container title_container visible-desktop clearfix">
                        <div class="portfolio-header">
                        
                            <ul class="filters option-set subnavigation clearfix <?php echo esc_attr($filter_style); ?>-style toggleClicked<?php if($filter_toggle_state == "open"){ echo " openToggle"; } ?>" data-option-key="filter">

                                <?php

                                echo $catList;

                                if($filter_style == 'toggle') {
                                    ?>
                                    <li class="filterToggle<?php if($filter_toggle_state == "open"){ echo " closed"; } ?>">
                                        <div class="toggleLineContainer">
                                            <span class="lineBarFirst"></span>
                                            <span class="lineBarSecond"></span>
                                        </div>
                                        <div class="filterRightLine"></div>
                                        <div class="filterLeftLine"></div>
                                    </li>
                                    <?php
                                }

                                ?>

                            </ul>
                        </div>
                    </div>

                    <!-- gallery filter - tablet & phone -->
                    <div class="hidden-desktop clearfix">
                        <ul class="filterstablet responsive_filter_dropdown portfolio-filter" data-option-key="filter">
                            <li class="">
                                <div>
                                    <span class="text"><?php esc_html_e('All', 'vitrine') ?></span>
                                    <span class="icon icon-angle-down"></span>
                                </div>
                                <ul class=portfolio-filter-items">
                                    <?php echo $catList; ?>
                                </ul>
                            </li>
                        </ul>
                    </div>

            <?php }
                } ?>
                
            <div class="portfolio_wrap">

                <!-- gallery items  -->
                <div  class="isotope <?php echo esc_attr($portfolio_hover) . ' ' . esc_attr($portfolio_hover_style). ' ' . esc_attr($enterance_animation).' '. esc_attr($gallery_pop_up); if($responsive_animation != ''){ echo ' no-responsive-animation'; }; ?>" data-categories="<?php if($portfolio_filter == 'all' ) { echo 'all'; } else {echo implode(" ",$filters); } ?>" data-animation-type="<?php echo esc_attr($animation_style);?>">
                    <div id="<?php echo esc_attr($portfolioLoop); ?>" class="ep_lightGallery">

                        <?php

                            $paged1 = isset( $_GET[$pagedNum] ) ? (int) $_GET[$pagedNum] : 1;

                            $queryArgs = array(

                                'post_type'      => 'gallery',
                                'posts_per_page' => $gallery_posts_page,
                                'paged'          => $paged1
                            );


                            if ( $portfolio_filter== 'custom' ) {

                                //Taxonomy filter
                                if(count($catArr))
                                {

                                    $queryArgs['tax_query'] =  array(
                                        // Note: tax_query expects an array of arrays!
                                        array(
                                            'taxonomy' => 'gallery_cat',
                                            'field'    => 'slug',
                                            'terms'    => $catArr
                                        ));
                                }
                            }

                            $count = 0;
                            $query = new WP_Query($queryArgs);

                            while ($query->have_posts()) {

                                $query->the_post();

                                epico_get_template_part( 'templates/gallery-thumbnail', [ '$portolio_type' => $portolio_type  , '$portfolioLoop' => $portfolioLoop , '$count' => $count , '$portfolio_masonry' => $portfolio_masonry  ] );
                            
                                $count++;
                            }

                            wp_reset_postdata();

                        ?>
                    </div>
                </div>
            </div>
            <?php

                $query = new WP_Query($queryArgs);
                if ( $query-> have_posts()) { ?>

                    <!-- gallery load more button -->
                    <div class="pLoadMore <?php echo esc_attr($pLoadMore); ?>  clearfix">
                        <div class="readmore clearfix">
                            <div class="loadMore" data-id="<?php echo esc_attr($id); ?>">
                                <span class="text load-more-text"><?php esc_html_e("Load more",'vitrine'); ?></span>
                                <span class="text loading-text"><?php esc_html_e("Loading...",'vitrine'); ?></span>
                            </div>
                        </div>
                    </div>

            <?php } ?>

            <?php wp_reset_postdata(); ?>

        </div>
    </div>
    <!-- End gallery  -->

    <?php
    return ob_get_clean();
}


/*-----------------------------------------------------------------------------------*/
/*  Carousel Gallery
/*-----------------------------------------------------------------------------------*/

function epico_sc_gallery_carousel($atts, $content=null) {
    extract(shortcode_atts(array(
        "nav_style" => "dark",
        "hover_color" => "c0392b",
        "custom_hover_color" => "",
        "visible_items" => "1",
        "zoom" => "",
        "gutter" => '',
        "naxt_prev_btn"  => "show",
        'enterance_animation'=> 'fadeInFromBottom',
        'responsive_animation' => 'disable',
        'is_autoplay' => 'on',
        'portfolio_filter' => 'all',
        'filters' => ''
    ), $atts));

    $id = epico_sc_id('image_carousel');
    $gutter = ($gutter == 'no' ? 'no-gutter':''); 
	$catArr  = explode(',', $filters) ;
	
	// Add some parameters for the JS - gallery load more .
	$queryArgs = array (
		'post_type'      => 'gallery',
		'post_status'   => 'publish', // just tried to find all published post
		'posts_per_page' =>   -1,
		'fields' => 'ids'
	);
		
    if ( $portfolio_filter== 'custom' ) {

        //Taxonomy filter
        if(count($catArr))
        {

            $queryArgs['tax_query'] =  array(
                // Note: tax_query expects an array of arrays!
                array(
                    'taxonomy' => 'gallery_cat',
                    'field'    => 'slug',
                    'terms'    => $catArr
                ));
        }
    }

    ob_start();

    if($hover_color != 'c0392b')  { 

        $color = 'c0392b';
        if ( isset( $hover_color ) ) {
            if($hover_color != 'custom')
            {
                $color = "#" . $hover_color;
            }
            else
            {
                if(isset( $custom_hover_color ))
                {
                    $color = $custom_hover_color;
                }

            }
        }

        ?>

        <style type="text/css" media="all">

            <?php echo  "#$id.carousel .swiper-slide .image-container:before"; ?>
            {
                background-color:<?php echo esc_attr($color); ?>;
            }    

        </style>

    <?php
    }
    ?>

    <div id="<?php echo esc_attr($id); ?>" class="gallery_carousel carousel <?php if ( $enterance_animation != 'default') { echo 'hasAnimation'; if($responsive_animation != ''){ echo ' no-responsive-animation';} } ?> <?php echo esc_attr($enterance_animation); ?> <?php echo esc_attr($gutter); ?> <?php if (strlen($nav_style)) { echo esc_attr($nav_style); }?> <?php echo ($zoom != ""? "zoom-hover":""); ?>" data-id="<?php echo esc_attr($id); ?>"   data-autoplay="<?php echo esc_attr($is_autoplay); ?>" >
        <div class="swiper-container swiper-container<?php echo esc_attr($id); ?> clearfix"  data-visibleitems="<?php if (strlen($visible_items)) { echo esc_attr($visible_items); }?>">
            <div class="swiper-wrapper carousel_gallery" >
			
                 <?php
				 
                    $count = 0;
                    $query = new WP_Query($queryArgs);

                    while ($query->have_posts()) {
						
                            $query->the_post();

							$id = get_the_ID();

							/* Gallery title , subtitle and gallery custom purchase link */
                            $galleryId = get_the_title($id);
                            $galleryTitleSwitch = get_post_meta( $id , "title-bar", true );
                            $galleryTitle = get_post_meta( $id , "title-text", true );
                            $galleryExternalLink = get_post_meta( $id , "gallery-external-link", true );
                            $galleryExternalLinkText = get_post_meta( $id , "gallery-external-link-text", true );
                            $gallerySubTitle = get_post_meta( $id , "subtitle-text", true );


                            if ( $galleryTitleSwitch  == '1' ) { // Show custom title
                                
                                $title = $galleryTitle;
 
                                if($galleryExternalLink && $galleryExternalLinkText) {
                                    $tilteDOM = "<h4> $galleryTitle </h4><p> $gallerySubTitle </p><p class='galleryexternalwrap'><a href='$galleryExternalLink' target='_blank' class='galleryexternallink'> $galleryExternalLinkText  </a></p>";
                                } else {
                                    $tilteDOM = "<h4> $galleryTitle </h4><p> $gallerySubTitle </p>";
                                }

                            } else {  // Show post type title 

                                $title = $galleryId;

                                if($galleryExternalLink && $galleryExternalLinkText) {
                                    $tilteDOM = "<h4> $galleryId </h4><p class='galleryexternalwrap'><a  href='$galleryExternalLink' target='_blank' class='galleryexternallink'> $galleryExternalLinkText </a></p>";
                                } else {
                                    $tilteDOM = "<h4> $galleryId </h4>";
                                }

                            }


							//Creating gallery thumbnail image
							$gallery_url[]='';
							$gallery_url = get_post_meta($id,'_thumbnail_id',false);
							if(!empty($gallery_url)){ 
								$gallery_url = wp_get_attachment_image_src($gallery_url[0], 'large', false);
							}else{//If we had no gallery image
								$gallery_url[0]= get_template_directory_uri() . '/assets/img/placeholders/noImage.jpg';
							}

							$image = get_the_post_thumbnail( $id, 'large'); 
                            $image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );

							?> 


                            <div class="swiper-slide carousel_item  galleryItem  postphoto"  >
                                <div class="image-container">
                                    <a href="<?php echo esc_url($gallery_url[0]); ?>"  data-sub-html="<?php echo  esc_attr($tilteDOM); ?>" data-src="<?php echo esc_url($gallery_url[0]); ?>" style="padding-top:<?php echo epico_get_height_percentage($image); ?>%"  title="<?php echo esc_attr($title); ?>" class="galleryCarouselLink overlay thumbnail-square">
							            
							            <image src="<?php echo esc_url($gallery_url[0]); ?>"/>

                                        <div class="hoverContent">
                                            <div class="title-wrap">
                                                <div class="titleContainer">
                                                    <div class="hover-title" ><?php echo $title ?></div>
                                                </div>
                                            </div>
                                        </div> 
                                    </a> 
                                    <div class="lazy-load lazy-load-on-load bg-lazy-load" data-src="<?php echo esc_url( $image_url[0] )  ?>" style="padding-top:<?php echo epico_get_height_percentage($image); ?>%">
                                        <a href="<?php echo esc_url($gallery_url[0]); ?>"></a>
                                    </div>
                                </div>
                            </div>


                            <?php

                        $count++;
                    }

                    wp_reset_postdata(); ?>

            </div>
        </div>

        <?php if ( $naxt_prev_btn == 'show') { ?>

        <!-- Next Arrows -->
        <div class="arrows-button-next no-select"></div>

        <!-- Prev Arrows -->
        <div class="arrows-button-prev no-select"></div>

        <?php } ?>

    </div>

    <?php
    
    return ob_get_clean();
}


/*-----------------------------------------------------------------------------------*/
/*  Newsletter(subscribtion form)
/*-----------------------------------------------------------------------------------*/

function epico_sc_newsletter($atts)
{
    
    extract(shortcode_atts(array(  
        'mail_poet_form' => '1',
        'mail_poet_button_style'  => 'darkStyle',
    ), $atts));

    if(!class_exists('WYSIJA_NL_Widget'))
        return;

    ob_start();?>
    <div class="ep-newsletter <?php echo esc_attr($mail_poet_button_style); ?>">
        <?php
          $widgetNL = new WYSIJA_NL_Widget(true);
          echo $widgetNL->widget(array('form' => esc_attr($mail_poet_form), 'form_type' => 'php'));
        ?>
    </div>  
    <?php
    return ob_get_clean();
}

/*-----------------------------------------------------------------------------------*/
/*  Woocommerce shortcodes
/*-----------------------------------------------------------------------------------*/
    
    //single product 1
    function epico_sc_product( $atts ) {
        $atts = shortcode_atts(array(
            'id'  => '',
            'sku'  => '',
            'style' => 'style1',
            'columns' => '1',
            'border'  => 'enable',
            'image_size'  => 'shop_single',
            'image_size_width' => '',
            'image_size_height' => '',
            'image_size_crop' => '',
            'hover_image'  => 'show',
            'quickview'  => 'enable',
            'compare'  => 'enable',
            'wishlist'  => 'enable',
            'badges' => 'enable',
            'hover_price' => 'enable',
            'hover_color' => '',
            'custom_hover_color' => '',
            'animation' => 'none',
            'delay' => '0',
            'hover_description' => 'enable',
            /* reset values that not related to single product shortcode */
            'carousel' => 'disable',
            'layout_mode' => 'fitRows',
            'enterance_animation' => 'default',
            'responsive_animation' => 'disable',
            'is_autoplay' => 'off',
            'nav_style' => 'dark',
            'gutter' => '',


        ), $atts);

		if(!class_exists("woocommerce"))
			return;
		
        if ( $atts['id'] == '' ) {
            return '';
        }

        $query_args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => 1,
            'no_found_rows'  => 1,
            'meta_query'          => WC()->query->get_meta_query()
        );

        if ( $atts['sku'] != '' ) {
            $query_args['meta_query'][] = array(
                'key'       => '_sku',
                'value'     => $atts['sku'],
                'compare'   => '='
            );
        }

        $query_args['p'] = $atts['id'];

        if($atts['image_size'] == 'custom' && $atts['image_size_width'] != '' && $atts['image_size_height'] != '')
        {
            if($atts['image_size_crop'] == 'yes')
            {
                $atts['image_size_crop'] = true;
            }
            else
            {
                $atts['image_size_crop'] = false;
            }

            $atts['image_size'] = array($atts['image_size_width'], $atts['image_size_height'], $atts['image_size_crop']);
        }

        return epico_product_loop( $query_args, $atts, 'single_product' );
    }


    //Single product 2
    function epico_sc_product_2( $atts ) {
        global $woocommerce;
        extract(shortcode_atts(array(

            'id'  => '',
            'sku'  => '',
            'border'  => 'enable',
            'image'  => 'enable',
            'image_size'  => 'shop_single',
            'image_size_width' => '',
            'image_size_height' => '',
            'image_size_crop' => '',
            'quickview'  => 'enable',
            'wishlist'  => 'enable',
            'compare'  => 'enable',
            'badges' => 'enable',
            'rating' => 'enable',
            'bg_color' => '#f2f2f2',
            'font_color' => '',
            'animation' => 'none',
            'delay' => '0',
            'responsive_animation' => 'disable',
        ), $atts));

		if(!class_exists("woocommerce"))
			return;
		
        if ( $id == '' ) {
            return '';
        }

        $post = get_post($id);

        $meta_query = WC()->query->get_meta_query();

        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 1,
            'no_found_rows'  => 1,
            'post_status'    => 'publish',
            'meta_query'     => $meta_query
        );

        if ( $sku != '' ) {
            $args['meta_query'][] = array(
                'key'       => '_sku',
                'value'     => $sku,
                'compare'   => '='
            );
        }

        $args['p'] = $id;

        $class = array();
        $class[] = 'add-to-cart-hovered';
        $class[] = 'single-product-shortcode';

        $style = '';
        if( $bg_color != '') 
        {
            $style = ' style="background-color:' . esc_attr($bg_color) . '"';
        }

        $fontstyle = '';
        if( $font_color != '') 
        {
            $fontstyle = ' style="color:' . esc_attr($font_color) . '"';
        }

        if($image_size == 'custom' && $image_size_width != '' && $image_size_height != '')
        {
            if($image_size_crop == 'yes')
            {
                $image_size_crop = true;
            }
            else
            {
                $image_size_crop = false;
            }

            $image_size = array($image_size_width, $image_size_height, $image_size_crop);
        }

        ob_start();

        $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

        if ( $products->have_posts() ) : ?>

			<?php do_action( "woocommerce_shortcode_before_single_product_2_loop" ); ?>

            <?php // Use <ul> tag instead of calling woocommerce_product_loop_start(); to detect WC shortcodes  ?>
            <ul class="products">

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                    <?php

                    global $product;

                    $attachment_ids = $product-> get_gallery_image_ids();
                    $image_num = count($attachment_ids) + ( has_post_thumbnail() ? 1 : 0 );

                    if ( has_post_thumbnail() )                 
                    {
                        array_unshift($attachment_ids , get_post_thumbnail_id()); // add post thumbnail in front of attachment_ids array
                    }


                     ?>
                    <li  <?php post_class( $class); echo $style; ?>>

                        <?php
                        echo '<a href="' . esc_url(get_the_permalink()) . '" title="' . esc_attr(get_the_title()) .'"><h3' . $fontstyle . '>' . get_the_title() . '</h3></a>';

                        if($badges != 'disable')
                        {
                            if ( $product -> is_on_sale() ) {

                                echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'vitrine' ) . '</span>', $post, $product );
                            
                            } 

                            if ( ! $product-> is_in_stock() ) {          
                                echo '<div class="out_of_stock_badge_loop">' . esc_html__( 'Out of stock', 'vitrine' ) .'</div>';            
                            }

                        }

                        if ( $post->post_excerpt ) {

                            echo '<div class="description woocommerce-product-details__short-description" itemprop="description"' . $fontstyle .'>' . apply_filters( 'woocommerce_short_description', $post->post_excerpt ) . '</div>';
                        
                        }

                        $rating_html = wc_get_rating_html( $product->get_average_rating() );

                        if ( $rating != 'disable' &&  $rating_html) {
                            echo $rating_html;
                        } ?>


                            <div class="add_to_cart_btn_wrap   <?php if($image == 'disable') {  ?> disableImageModernSingleProduct <?php } ?>">

                                <?php if($image != 'disable') {  ?>

                                    <div class="images">
                                        <?php
                                        $image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );

                                        if($image_size == 'full')
                                        {
                                            
                                            $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full' );
                                            $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="' . esc_attr($image_attributes[1]) . '" height="'. esc_attr($image_attributes[2]) .'" data-src="'.esc_url($image_attributes[0]).'" alt="'.esc_attr($image_title).'" />';

                                        }
                                        else
                                        {
                                            if(function_exists('wc_get_image_size')) {

                                                $image_dimension = wc_get_image_size($image_size);

                                                $image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
                                                $image_attributes = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], false, true);
                                                
                                                if($image_attributes[0]){
                                                    $img_url = $image_attributes[0];
                                                }
                                                else {
                                                    $img_url = $image_link;
                                                }

                                                $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="' . esc_attr($image_attributes[1]) .'" height="' . esc_attr($image_attributes[2]) . '" data-src="'.esc_url($img_url).'" alt="'. esc_attr($image_title) .'" />';

                                            } else {

                                                $image = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', $image_size ) );

                                            }
                                        }
                                        ?>
                                        <div class="product-fullview-thumbs <?php echo( $image_num == 1 ? 'lazy-load lazy-load-on-load" style="padding-top:' . epico_get_height_percentage($image) .'%;"':'') ?>">
                                            <?php
                                            if( $image_num == 1 ) {
                                                echo $image;//img tag
                                            }
                                            else
                                            {
                                            ?>

                                            <div class="swiper-container clearfix">
                                                <div class="swiper-wrapper">

                                                    <?php
                                                    foreach ( $attachment_ids as $attachment_id ) {

                                                        $image_title = esc_attr( get_the_title( $attachment_id ) );

                                                        if($image_size == 'full')
                                                        {
                                                            
                                                            $image_attributes = wp_get_attachment_image_src( $attachment_id , 'full' );
                                                            $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="' . esc_attr($image_attributes[1]) . '" height="'. esc_attr($image_attributes[2]) .'" data-src="'.esc_url($image_attributes[0]).'" alt="'.esc_attr($image_title).'" />';

                                                        }
                                                        else
                                                        {
                                                            if(function_exists('wc_get_image_size')) {

                                                                $image_dimension = wc_get_image_size($image_size);

                                                                $image_link  = wp_get_attachment_url( $attachment_id );
                                                                $image_attributes = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], false, true);
                                                                
                                                                if($image_attributes[0]){
                                                                    $img_url = $image_attributes[0];
                                                                }
                                                                else {
                                                                    $img_url = $image_link;
                                                                }
                                                         
                                                                $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="' . esc_attr($image_attributes[1]) .'" height="' . esc_attr($image_attributes[2]) . '" data-src="'.esc_url($img_url).'" alt="'.esc_attr($image_title).'" />';


                                                            } else {

                                                                $image = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', $image_size ) );

                                                            }

                                                        }


                                                        //echo slide
                                                        $slide = '<div class="swiper-slide lazy-load lazy-load-on-load" style="padding-top:' . epico_get_height_percentage($image) .'%;">' . $image .'</div>';
                                                        echo apply_filters( 'woocommerce_single_product_image_html', $slide, $post->ID );

                                                    }

                                                    ?>
                                                </div>
                                                <div class="swiper-button-next"></div>
                                                <div class="swiper-button-prev"></div>
                                            </div>
 
                                            <?php
                                            }
                                            ?>
                                       </div>
                                    </div>
                                <?php } ?>

                                <?php if ( $price_html = $product->get_price_html() ) : ?>
                                    <span class="price"><?php echo $price_html; ?></span>
                                <?php endif; ?>

                                <div class="product-buttons">
                                
                                    <?php

                                    $button =  apply_filters( 'woocommerce_loop_add_to_cart_link',
                                        sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s %s">%s</a>',
                                            esc_url( $product->add_to_cart_url() ),
                                            esc_attr( $product->get_id() ),
                                            esc_attr( $product->get_sku() ),
                                            esc_attr( isset( $quantity ) ? $quantity : 1 ),
                                            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                            esc_attr( $product->get_type()),
                                            esc_attr( $product->get_type() == 'simple' && 'yes'  === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ? 'ajax_add_to_cart' : 'swiper-no-swiping'),//Epico codes
                                            ( $product->add_to_cart_text() )
                                        ),
                                    $product);

                                    $button = apply_filters('epico_loop_modern_add_to_cart_link', $button);

                                   	$catalog_mode =  epico_opt('catalog_mode');
                             
									if($catalog_mode == 0){
										echo $button;
									}
        
                                    if($wishlist != 'disable')
                                    {
                                        epico_shop_page_wishlist_button();
                                    }
                                    
                                    if( $quickview != 'disable') 
                                    {
                                        epico_add_quick_view_button();
                                    }

                                    if( class_exists('YITH_Woocompare') && get_option('yith_woocompare_compare_button_in_products_list') == 'yes' && $compare != 'disable') 
                                    {
                                        epico_add_compare_button();
                                    }
                                    
                                    ?>
                                </div>

                            
                            </div>
                    </li>
                </ul>

                <?php endwhile; // end of the loop. ?>

        <?php endif;

        wp_reset_postdata();

        $css_class = 'woocommerce single-product2';

        if ( !isset( $border ) || (isset( $border ) && $border != 'disable') ) {
            $css_class .= ' with-border';
        }

        if( $animation != 'none') {
            $css_class .= ' shortcodeAnimation';

            if($responsive_animation != '')
            {
                $css_class .= ' no-responsive-animation';
            }
        }

        return '<div class="' . esc_attr( $css_class ) . '"  ' . (strlen(esc_attr($animation)) ?  'data-delay="' . esc_attr($delay) . '" data-animation="' . esc_attr($animation) : '') . '">' . ob_get_clean() . '</div>';
    }

    function epico_products( $atts ) {
        $atts = shortcode_atts( array(
            'columns' => '1',
            'orderby' => 'title',
            'order'   => 'asc',
            'ids'     => '',
            'skus'    => '',
            'enterance_animation' => 'fadeIn',
            'responsive_animation' => 'disable',
            'layout_mode' => 'masonry',
            'carousel' => 'enable',
            'is_autoplay' => '',
            'nav_style' => '',
            'gutter' => '',
            'style' => 'style1',
            'border'  => 'enable',
            'image_size'  => 'shop_single',
            'image_size_width' => '',
            'image_size_height' => '',
            'image_size_crop' => '',
            'hover_image'  => 'show',
            'quickview'  => 'enable',
            'wishlist'  => 'enable',
            'compare'  => 'enable',
            'badges' => 'enable',
            'hover_price' => 'enable',
            'hover_description' => 'enable',
            'hover_color' => '',
            'custom_hover_color' => '',
            'animation' => 'none',
            'delay' =>'0'
        ), $atts );

		if(!class_exists("woocommerce"))
			return;
		
        $query_args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'orderby'             => $atts['orderby'],
            'order'               => $atts['order'],
            'posts_per_page'      => -1,
            'meta_query'          => WC()->query->get_meta_query()
        );

        if ( ! empty( $atts['skus'] ) ) {
            $query_args['meta_query'][] = array(
                'key'     => '_sku',
                'value'   => array_map( 'trim', explode( ',', $atts['skus'] ) ),
                'compare' => 'IN'
            );
        }

        if ( ! empty( $atts['ids'] ) ) {
            $query_args['post__in'] = array_map( 'trim', explode( ',', $atts['ids'] ) );
        }

        if($atts['image_size'] == 'custom' && $atts['image_size_width'] != '' && $atts['image_size_height'] != '')
        {
            if($atts['image_size_crop'] == 'yes')
            {
                $atts['image_size_crop'] = true;
            }
            else
            {
                $atts['image_size_crop'] = false;
            }

            $atts['image_size'] = array($atts['image_size_width'], $atts['image_size_height'], $atts['image_size_crop']);
        }

        return epico_product_loop( $query_args, $atts, 'products' );
    }

    function epico_recent_products( $atts ) {
        $atts = shortcode_atts( array(
            'per_page'  => '12',
            'columns'   => '1',
            'orderby'   => 'date',
            'order'     => 'desc',
			'category' => '',  // Slugs
			'tags' => '', 
            'enterance_animation' => 'fadeIn',
            'responsive_animation' => 'disable',
            'layout_mode' => 'masonry',
            'carousel' => 'enable',
            'is_autoplay' => '',
            'nav_style' => '',
            'gutter' => '',
            'style' => 'style1',
            'border'  => 'enable',
            'image_size'  => 'shop_single',
            'image_size_width' => '',
            'image_size_height' => '',
            'image_size_crop' => '',
            'hover_image'  => 'show',
            'quickview'  => 'enable',
            'wishlist'  => 'enable',
            'compare'  => 'enable',
            'badges' => 'enable',
            'hover_price' => 'enable',
            'hover_description' => 'enable',
            'hover_color' => '',
            'custom_hover_color' => '',
            'animation' => 'none',
            'delay' =>'0'
        ), $atts );

		if(!class_exists("woocommerce"))
			return;
		
		
		$tax='';
		$term='';
		if ($atts['tags']!=''){
			$tax= 'product_tag';
			$term= $atts['tags'];
		} 
		if ($atts['category'] !=''){
			$tax= 'product_cat';
			$term= $atts['category'];
		} 
			
		
		if(($atts['category'] == '') && ($atts['tags'] == ''))
		{
			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts['per_page'],
				'orderby'             => $atts['orderby'],
				'order'               => $atts['order'],
				'meta_query'          => WC()->query->get_meta_query()
			);
		}
		elseif (($atts['category'] != '')  && ($atts['tags'] != ''))
		{ 
			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts['per_page'],
				'orderby'             => $atts['orderby'],
				'order'               => $atts['order'],
				'meta_query'          => WC()->query->get_meta_query(),
				'tax_query'=>array(
					'relation' => 'AND',
					array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => array_map( 'sanitize_title', explode( ',', $atts['category'] )), //  display products of the specific categories.
					'operator' => 'IN'
					),
					array(
					'taxonomy' => 'product_tag',
					'field' => 'slug',
					'terms'    => array_map( 'sanitize_title', explode( ',', $atts['tags'] ) ), //  display products of the specific tags.
					'operator' => 'IN'
					)
				) 
				
			);
		}
		else
		{ 
			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts['per_page'],
				'orderby'             => $atts['orderby'],
				'order'               => $atts['order'],
				'meta_query'          => WC()->query->get_meta_query(),
				'tax_query'=>array(
					array(
					'taxonomy' => $tax,
					'field' => 'slug',
					'terms'    => array_map( 'sanitize_title', explode( ',', $term ) ), //display products of the specific categories or tags.
					'operator' => 'IN'
					)
				) 
				
			);
		}
        
		if($atts['image_size'] == 'custom' && $atts['image_size_width'] != '' && $atts['image_size_height'] != '')
        {
            if($atts['image_size_crop'] == 'yes')
            {
                $atts['image_size_crop'] = true;
            }
            else
            {
                $atts['image_size_crop'] = false;
            }

            $atts['image_size'] = array($atts['image_size_width'], $atts['image_size_height'], $atts['image_size_crop']);
        }

        return epico_product_loop( $query_args, $atts, 'recent_products' );
    }

    function epico_sale_products( $atts ) {
        $atts = shortcode_atts( array(
            'per_page' => '12',
            'columns'  => '1',
            'orderby'  => 'title',
            'order'    => 'asc',
            'enterance_animation' => 'fadeIn',
            'responsive_animation' => 'disable',
            'layout_mode' => 'masonry',
            'carousel' => 'enable',
            'is_autoplay' => '',
            'nav_style' => '',
            'gutter' => '',
            'style' => 'style1',
            'border'  => 'enable',
            'image_size'  => 'shop_single',
            'image_size_width' => '',
            'image_size_height' => '',
            'image_size_crop' => '',
            'hover_image'  => 'show',
            'quickview'  => 'enable',
            'wishlist'  => 'enable',
            'compare'  => 'enable',
            'badges' => 'enable',
            'hover_price' => 'enable',
            'hover_description' => 'enable',
            'hover_color' => '',
            'custom_hover_color' => '',
            'animation' => 'none',
            'delay' =>'0'
        ), $atts );

		if(!class_exists("woocommerce"))
			return;
		
        $query_args = array(
            'posts_per_page'    => $atts['per_page'],
            'orderby'           => $atts['orderby'],
            'order'             => $atts['order'],
            'no_found_rows'     => 1,
            'post_status'       => 'publish',
            'post_type'         => 'product',
            'meta_query'        => WC()->query->get_meta_query(),
            'post__in'          => array_merge( array( 0 ), wc_get_product_ids_on_sale() )
        );

        if($atts['image_size'] == 'custom' && $atts['image_size_width'] != '' && $atts['image_size_height'] != '')
        {
            if($atts['image_size_crop'] == 'yes')
            {
                $atts['image_size_crop'] = true;
            }
            else
            {
                $atts['image_size_crop'] = false;
            }

            $atts['image_size'] = array($atts['image_size_width'], $atts['image_size_height'], $atts['image_size_crop']);
        }

        return epico_product_loop( $query_args, $atts, 'sale_products' );
    }

    function epico_best_selling_products( $atts ) {
        $atts = shortcode_atts( array(
            'per_page' => '12',
            'columns'  => '1',
			'category'  => '', //slug
			'tags'  => '', //slug
            'enterance_animation' => 'fadeIn',
            'responsive_animation' => 'disable',
            'layout_mode' => 'masonry',
            'carousel' => 'enable',
            'is_autoplay' => '',
            'nav_style' => '',
            'gutter' => '',
            'style' => 'style1',
            'border'  => 'enable',
            'image_size'  => 'shop_single',
            'image_size_width' => '',
            'image_size_height' => '',
            'image_size_crop' => '',
            'hover_image'  => 'show',
            'quickview'  => 'enable',
            'wishlist'  => 'enable',
            'compare'  => 'enable',
            'badges' => 'enable',
            'hover_price' => 'enable',
            'hover_description' => 'enable',
            'hover_color' => '',
            'custom_hover_color' => '',
            'animation' => 'none',
            'delay' =>'0'
        ), $atts );

		if(!class_exists("woocommerce"))
			return;
		
		
		$tax='';
		$term='';
		if ($atts['tags']!=''){
			$tax= 'product_tag';
			$term= $atts['tags'];
		} 
		if ($atts['category'] !=''){
			$tax= 'product_cat';
			$term= $atts['category'];
		} 
		
        if(($atts['category'] == '') && ($atts['tags'] == ''))
		{
			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts['per_page'],
				'orderby'             => 'meta_value_num',
				'meta_query'          => WC()->query->get_meta_query()
			);
		}
		elseif (($atts['category'] != '')  && ($atts['tags'] != ''))
		{
			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts['per_page'],
				'orderby'             => 'meta_value_num',
				'meta_query'          => WC()->query->get_meta_query(),
				'tax_query'=>array(
					'relation' => 'AND',
					array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => array_map( 'sanitize_title', explode( ',', $atts['category'] )), // display products of the specific categories.
					'operator' => 'IN'
					),
					array(
					'taxonomy' => 'product_tag',
					'field' => 'slug',
					'terms' => array_map( 'sanitize_title', explode( ',', $atts['tags'] )), // display products of the specific tags.
					'operator' => 'IN'
					)
				) 
				
			);
		}
		else
		{
			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts['per_page'],
				'orderby'             => 'meta_value_num',
				'meta_query'          => WC()->query->get_meta_query(),
				'tax_query'=>array(
					array(
					'taxonomy' => $tax,
					'field' => 'slug',
					'terms' => array_map( 'sanitize_title', explode( ',', $term )), // display products of the specific categories or tags.
					'operator' => 'IN'
					)
				) 
				
			);
		}

        if($atts['image_size'] == 'custom' && $atts['image_size_width'] != '' && $atts['image_size_height'] != '')
        {
            if($atts['image_size_crop'] == 'yes')
            {
                $atts['image_size_crop'] = true;
            }
            else
            {
                $atts['image_size_crop'] = false;
            }

            $atts['image_size'] = array($atts['image_size_width'], $atts['image_size_height'], $atts['image_size_crop']);
        }

        return epico_product_loop( $query_args, $atts, 'best_selling_products' );
    }

    function epico_top_rated_products( $atts ) {
        $atts = shortcode_atts( array(
            'per_page' => '12',
            'columns'  => '1',
            'orderby'  => 'title',
            'order'    => 'asc',
			'category'    => '', //slug
			'tags'    => '', //slug
            'enterance_animation' => 'fadeIn',
            'responsive_animation' => 'disable',
            'layout_mode' => 'masonry',
            'carousel' => 'enable',
            'is_autoplay' => '',
            'nav_style' => '',
            'gutter' => '',
            'style' => 'style1',
            'border'  => 'enable',
            'image_size'  => 'shop_single',
            'image_size_width' => '',
            'image_size_height' => '',
            'image_size_crop' => '',
            'hover_image'  => 'show',
            'quickview'  => 'enable',
            'wishlist'  => 'enable',
            'compare'  => 'enable',
            'badges' => 'enable',
            'hover_price' => 'enable',
            'hover_description' => 'enable',
            'hover_color' => '',
            'custom_hover_color' => '',
            'animation' => 'none',
            'delay' =>'0'
        ), $atts );

		if(!class_exists("woocommerce"))
			return;

		$tax='';
		$term='';
		if ($atts['tags']!=''){
			$tax= 'product_tag';
			$term= $atts['tags'];
		} 
		if ($atts['category'] !=''){
			$tax= 'product_cat';
			$term= $atts['category'];
		} 
		
		
        if(($atts['category'] == '') && ($atts['tags'] == ''))
		{
			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts['per_page'],
				'orderby'             => $atts['orderby'],
				'order'               => $atts['order'],
				'meta_query'          => WC()->query->get_meta_query()
			);
		}
		elseif (($atts['category'] != '')  && ($atts['tags'] != ''))
		{
			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts['per_page'],
				'orderby'             => $atts['orderby'],
				'order'               => $atts['order'],
				'meta_query'          => WC()->query->get_meta_query(),
				'tax_query'=>array(
					'relation' => 'AND',
					array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => array_map( 'sanitize_title', explode( ',', $atts['category'] )), // display products of the specific categories.
					'operator' => 'IN'
					),
					array(
					'taxonomy' => 'product_tag',
					'field' => 'slug',
					'terms' => array_map( 'sanitize_title', explode( ',', $atts['tags'] )), // display products of the specific tags.
					'operator' => 'IN'
					)
				) 
			);
		}
		else
		{
			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts['per_page'],
				'orderby'             => $atts['orderby'],
				'order'               => $atts['order'],
				'meta_query'          => WC()->query->get_meta_query(),
				'tax_query'=>array(
					array(
					'taxonomy' => $tax,
					'field' => 'slug',
					'terms' => array_map( 'sanitize_title', explode( ',', $term )), // display products of the specific categories or tags.
					'operator' => 'IN'
					)
				) 
			);
		}
		

        if($atts['image_size'] == 'custom' && $atts['image_size_width'] != '' && $atts['image_size_height'] != '')
        {
            if($atts['image_size_crop'] == 'yes')
            {
                $atts['image_size_crop'] = true;
            }
            else
            {
                $atts['image_size_crop'] = false;
            }

            $atts['image_size'] = array($atts['image_size_width'], $atts['image_size_height'], $atts['image_size_crop']);
        }

        add_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );

        $return = epico_product_loop( $query_args, $atts, 'top_rated_products' );

        remove_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );

        return $return;
    }

    function epico_featured_products( $atts ) {
        $atts = shortcode_atts( array(
            'per_page' => '12',
            'columns'  => '1',
            'orderby'  => 'date',
            'order'    => 'desc',
			'category'    => '',//slug
			'tags'    => '',//slug
            'enterance_animation' => 'fadeIn',
            'responsive_animation' => 'disable',
            'layout_mode' => 'masonry',
            'carousel' => 'enable',
            'is_autoplay' => '',
            'nav_style' => '',
            'gutter' => '',
            'style' => 'style1',
            'border'  => 'enable',
            'image_size'  => 'shop_single',
            'image_size_width' => '',
            'image_size_height' => '',
            'image_size_crop' => '',
            'hover_image'  => 'show',
            'quickview'  => 'enable',
            'wishlist'  => 'enable',
            'compare'  => 'enable',
            'badges' => 'enable',
            'hover_price' => 'enable',
            'hover_description' => 'enable',
            'hover_color' => '',
            'custom_hover_color' => '',
            'animation' => 'none',
            'delay' =>'0'
        ), $atts );

		if(!class_exists("woocommerce"))
			return;
		
		$tax='';
		$term='';
		if ($atts['tags']!=''){
			$tax= 'product_tag';
			$term= $atts['tags'];
		} 
		if ($atts['category'] !=''){
			$tax= 'product_cat';
			$term= $atts['category'];
		} 
		
		
        if(($atts['category'] == '') && ($atts['tags'] == ''))
		{
			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts['per_page'],
				'orderby'             => $atts['orderby'],
				'order'               => $atts['order'],
				'tax_query' => array(
					array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
					),
				),
			);
		} 
		elseif (($atts['category'] != '')  && ($atts['tags'] != ''))
		{
			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts['per_page'],
				'orderby'             => $atts['orderby'],
				'order'               => $atts['order'],
				'tax_query'=>array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
					),
					array(
						'taxonomy' => 'product_cat',
						'field' => 'slug',
						'terms' => array_map( 'sanitize_title', explode( ',', $atts['category'] )), //  display products of the specific categories.
						'operator' => 'IN'
					),
					array(
						'taxonomy' => 'product_tag',
						'field' => 'slug',
						'terms' => array_map( 'sanitize_title', explode( ',', $atts['tags'] )), //  display products of the specific tags.
						'operator' => 'IN'
					),
				) 
			);
		} 
		else
		{
			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts['per_page'],
				'orderby'             => $atts['orderby'],
				'order'               => $atts['order'],
				'tax_query'=>array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
					),
					array(
						'taxonomy' => $tax,
						'field' => 'slug',
						'terms' => array_map( 'sanitize_title', explode( ',', $term )), //  display products of the specific categories or tags.
						'operator' => 'IN'
					),
				) 
			);
		} 

        if($atts['image_size'] == 'custom' && $atts['image_size_width'] != '' && $atts['image_size_height'] != '')
        {
            if($atts['image_size_crop'] == 'yes')
            {
                $atts['image_size_crop'] = true;
            }
            else
            {
                $atts['image_size_crop'] = false;
            }

            $atts['image_size'] = array($atts['image_size_width'], $atts['image_size_height'], $atts['image_size_crop']);
        }

        return epico_product_loop( $query_args, $atts, 'featured_products' );
    }

    function epico_product_attribute( $atts ) {
        $atts = shortcode_atts( array(
            'per_page'  => '12',
            'columns'   => '4',
            'orderby'   => 'date',
            'order'     => 'desc',
            'attribute' => '',
            'filter'    => '',
            'enterance_animation' => 'fadeIn',
            'responsive_animation' => 'disable',
            'layout_mode' => 'masonry',
            'carousel' => 'enable',
            'is_autoplay' => '',
            'nav_style' => '',
            'gutter' => '',
            'style' => 'style1',
            'border'  => 'enable',
            'image_size'  => 'shop_single',
            'image_size_width' => '',
            'image_size_height' => '',
            'image_size_crop' => '',
            'hover_image'  => 'show',
            'quickview'  => 'enable',
            'wishlist'  => 'enable',
            'compare'  => 'enable',
            'badges' => 'enable',
            'hover_price' => 'enable',
            'hover_description' => 'enable',
            'hover_color' => '',
            'custom_hover_color' => '',
            'animation' => 'none',
            'delay' =>'0'
        ), $atts );

		if(!class_exists("woocommerce"))
			return;

        $query_args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $atts['per_page'],
            'orderby'             => $atts['orderby'],
            'order'               => $atts['order'],
            'meta_query'          => WC()->query->get_meta_query(),
            'tax_query'           => array(
                array(
                    'taxonomy' => strstr( $atts['attribute'], 'pa_' ) ? sanitize_title( $atts['attribute'] ) : 'pa_' . sanitize_title( $atts['attribute'] ),
                    'terms'    => array_map( 'sanitize_title', explode( ',', $atts['filter'] ) ),
                    'field'    => 'slug'
                )
            )
        );

        return epico_product_loop( $query_args, $atts, 'product_attribute' );
    }

    function epico_product_category( $atts ) {
        $atts = shortcode_atts( array(
            'per_page' => '12',
            'columns'  => '1',
            'orderby'  => 'title',
            'order'    => 'desc',
            'category' => '',  // Slugs
            'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'. ( Visual composer has not used that )
            'enterance_animation' => 'fadeIn',
            'responsive_animation' => 'disable',
            'layout_mode' => 'masonry',
            'carousel' => 'enable',
            'is_autoplay' => '',
            'nav_style' => '',
            'gutter' => '',
            'style' => 'style1',
            'border'  => 'enable',
            'image_size'  => 'shop_single',
            'image_size_width' => '',
            'image_size_height' => '',
            'image_size_crop' => '',
            'hover_image'  => 'show',
            'quickview'  => 'enable',
            'wishlist'  => 'enable',
            'compare'  => 'enable',
            'badges' => 'enable',
            'hover_price' => 'enable',
            'hover_description' => 'enable',
            'hover_color' => '',
            'custom_hover_color'=> '',
            'animation' => 'none',
            'delay' =>'0'
        ), $atts );

        extract($atts);

		if(!class_exists("woocommerce"))
			return;

        if ( ! $category ) {
            return '';
        }

        // Default ordering args
        $ordering_args = WC()->query->get_catalog_ordering_args( $orderby, $order );
        $meta_query    = WC()->query->get_meta_query();
        $query_args    = array(
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'orderby'               => $ordering_args['orderby'],
            'order'                 => $ordering_args['order'],
            'posts_per_page'        => $per_page,
            'meta_query'            => $meta_query,
            'tax_query'             => array(
                array(
                    'taxonomy'      => 'product_cat',
                    'terms'         => array_map( 'sanitize_title', explode( ',', $category ) ),
                    'field'         => 'slug',
                    'operator'      => $operator
                )
            )
        );

        if ( isset( $ordering_args['meta_key'] ) ) {
            $query_args['meta_key'] = $ordering_args['meta_key'];
        }

        if($atts['image_size'] == 'custom' && $atts['image_size_width'] != '' && $atts['image_size_height'] != '')
        {
            if($atts['image_size_crop'] == 'yes')
            {
                $atts['image_size_crop'] = true;
            }
            else
            {
                $atts['image_size_crop'] = false;
            }

            $atts['image_size'] = array($atts['image_size_width'], $atts['image_size_height'], $atts['image_size_crop']);
        }

        $return =  epico_product_loop( $query_args, $atts, 'product_cat' );

        // Remove ordering query arguments
        WC()->query->remove_ordering_args();

        return $return;

    }

    //utility fucntion to handle woocommerce loops used in woocommerce shortcodes
    function epico_product_loop( $query_args, $atts, $loop_name ) {
        global $woocommerce_loop;
		$catalog_mode =  epico_opt('catalog_mode');

        $products                    = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $query_args, $atts ) );
        $columns                     = absint( $atts['columns'] );
        $woocommerce_loop['columns'] = $columns;
        $gutter = ($atts['gutter'] == 'no' ? 'no-gutter':'');
        $nav_style = $atts['nav_style'];
        $carouselClass = 'no-carousel';
        $carousel = 'enable';
        $autoplay = $atts['is_autoplay'];
        $style = $atts['style'];
        $border = $atts['border'];
        $image_size  = $atts['image_size'];
        $image_size_width  = $atts['image_size_width'];
        $image_size_height  = $atts['image_size_height'];
        $image_size_crop  = $atts['image_size_crop'];
        $hover_image  = $atts['hover_image'];
        $quickview  = $atts['quickview'];
        $compare  = $atts['compare'];
        $hover_price = $atts['hover_price'];
        $hover_description = $atts['hover_description'];
        $enterance_animation = $atts['enterance_animation'];
        $responsive_animation = $atts['responsive_animation'];
        $layout_mode = $atts['layout_mode'];
        $wishlist  = $atts['wishlist'];
        $badges = $atts['badges'];
        $hover_color = $atts['hover_color'];
        $custom_hover_color = $atts['custom_hover_color'];
        $animation = $atts['animation'];
        $delay = $atts['delay'];
        
		if(!class_exists("woocommerce"))
			return;

        if($atts['carousel'] == 'enable')
        {
            $carouselClass = 'carousel';
        }

        $hover_layer = '<div class="hover_layer"></div>';
        if ( isset( $atts['hover_color'] ) ) {
            if($atts['hover_color'] != 'custom' && $atts['hover_color'] != '')
            {
                $hover_layer = '<div class="hover_layer" style="background-color:#' . esc_attr($atts['hover_color']) . ';"></div>';
            }
            else
            {
                if(isset( $atts['custom_hover_color'] ) && $atts['custom_hover_color'] != '')
                {
                    $hover_layer = '<div class="hover_layer" style="background-color:' . esc_attr($atts['custom_hover_color']). ';"></div>';
                }

            }
        }

        if($image_size == 'custom' && $image_size_width != '' && $image_size_height != '')
        {
            if($image_size_crop == 'yes')
            {
                $image_size_crop = true;
            }
            else
            {
                $image_size_crop = false;
            }

            $image_size = array($image_size_width, $image_size_height, $image_size_crop);
        }

        $product_style = '';
        if($style == 'style1')
        {
            $product_style = 'buttonsOnHover';
        }
        elseif($style == 'style1-center')
        {
            $product_style = 'buttonsOnHover centered';
        } 
        elseif($style == 'style2')
        {
            $product_style = 'infoOnHover';
        } 
        elseif($style == 'style3')
        {
            $product_style = 'infoOnClick';
        } 
        elseif($style == 'style4')
        {
            $product_style = 'instantShop';
        }

        ob_start();

        if ( $products->have_posts() ) : ?>

            <?php do_action( "woocommerce_shortcode_before_{$loop_name}_loop" ); ?>

            <?php // Use <ul> tag instead of calling woocommerce_product_loop_start(); to detect WC shortcodes  ?>
            <ul class="products <?php echo esc_attr($product_style); echo ' shop-' . esc_attr($columns) . 'column'; ?>">

            <?php if($atts['carousel'] == 'enable') { ?>
                    <div class="swiper-container" data-visibleitems="<?php echo esc_attr($columns); ?>">
                        <div class="swiper-wrapper">
            <?php }

            while ( $products->have_posts() ) : $products->the_post();
                
                global $product,$post;
                $class = array();

                if( count( $product-> get_gallery_image_ids() ) && $hover_image == 'show' )
                {
                    $class[] = 'has-gallery';
                }

                if ( $border == 'enable') {
                    $class[] = 'with-border';
                }

                if( $hover_description == 'disable')  {
                    $class[] = 'disableHoverDescription';
                }

                if( $responsive_animation != '')
                {
                    $class[] = 'no-responsive-animation';
                }

 
                if($style == 'style1' || $style == 'style1-center')
                {
                ?>
                <li <?php post_class( $class); ?>>
                    <div class="productwrap">
                        <?php 
                        if($badges != 'disable')
                        {
                            if ( $product->is_on_sale() ) {

                                echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'vitrine' ) . '</span>', $post, $product );
                            
                            }

                            if ( !$product->is_in_stock() ) {          
                                    
                                echo '<div class="out_of_stock_badge_loop">' . esc_html__( 'Out of stock', 'vitrine' ) .'</div>';            
                                    
                            }
                        }
                        ?>

                        <div class="add_to_cart_btn_wrap lazy-load-hover-container">
                            <?php 

                                //echo '<a href="' . esc_url(get_the_permalink()) . '" class="product-link" title="' . esc_attr(get_the_title()) .'"></a>'; 
                                 echo '<a href="' . esc_url(get_the_permalink()) . '" class="product-link"></a>'; 

                            $image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
                            if($image_size == 'full')
                            {
                                $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full' );
                                $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="' . esc_attr($image_attributes[1]) . '" height="'. esc_attr($image_attributes[2]) .'" data-src="'.esc_url($image_attributes[0]).'" alt="'. esc_attr($image_title) .'" />';
                            }
                            else
                            {
                                if(function_exists('wc_get_image_size')) {

                                    $image_dimension = wc_get_image_size($image_size);

                                    $image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
                                    $image_attributes = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], false, true);
                                    
                                    if($image_attributes[0]){
                                        $img_url = $image_attributes[0];
                                    }
                                    else {
                                        $img_url = $image_link;
                                    }
                                    

                                    $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="' . esc_attr($image_attributes[1]).'" height="' . esc_attr($image_attributes[2]) . '" data-src="' . esc_url($img_url) . '" alt="' . esc_attr($image_title) . '" />';

                                } else {

                                    $image = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', $image_size ) );

                                }
                            }

                            ?>
                            <div class="imageswrap productthumbnail lazy-load lazy-load-on-load" style="padding-top: <?php echo esc_attr(epico_get_height_percentage($image)); ?>%;">
                                <?php
                                //Sanitization performed in above lines!
                                echo $image;
                                ?>
                            </div>
                            <?php

                            $attachment_ids = $product-> get_gallery_image_ids();
                            if(count($attachment_ids) >= 1 && $hover_image == 'show')
                            {

                                $image_src = '';
                                $image = '';
                                $attachment_id = $attachment_ids[0];

                                if($image_size == 'full')
                                {
                                    $image_src = wp_get_attachment_image_src( $attachment_id , 'full' );
                                    $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_src[0]).'"></div>';

                                }
                                else
                                {
                                    if(function_exists('wc_get_image_size')) {

                                        $image_dimension = wc_get_image_size($image_size);

                                        $image_link  = wp_get_attachment_url( $attachment_id);
                                        $img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);
                                        
                                        if(!$img_url) {
                                            $img_url = $image_link;
                                        }

                                        $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($img_url).'"></div>';

                                    } else {

                                        $image_url = wp_get_attachment_image_src($attachment_id, apply_filters( 'single_product_large_thumbnail_size', $image_size ) );
                                        if($image_url != false )
                                        {
                                            $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_url[0]).'"></div>';
                                        }
                                        else
                                        {
                                            $image_src = wp_get_attachment_image_src( $attachment_id , 'full' );
                                            $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_src[0]).'"></div>';
                                        }

                                    }
                                }
                                //Sanitization performed in above lines!
                                echo $image;

                            }
                            ?>
                            
                            <div class="product-buttons">
                                <?php
								
                                $button	= apply_filters( 'woocommerce_loop_add_to_cart_link',
                                    sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s %s">%s</a>',
                                        esc_url( $product->add_to_cart_url() ),
                                        esc_attr( $product->get_id() ),
                                        esc_attr( $product->get_sku() ),
                                        esc_attr( isset( $quantity ) ? $quantity : 1 ),
                                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                        esc_attr( $product->get_type() ),
										esc_attr( $product->get_type() == 'simple' && 'yes'  === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ? 'ajax_add_to_cart' : 'swiper-no-swiping'),//Epico codes
                                        ( $product->add_to_cart_text() )
                                    ),
                                $product );
								
								if($catalog_mode == 0)
								{
									echo $button;
								}
                                if($wishlist != 'disable')
                                {
                                    epico_shop_page_wishlist_button();
                                }
								
                                if( $quickview != 'disable') 
                                {
                                    epico_add_quick_view_button();
                                }


                                if( class_exists('YITH_Woocompare') && get_option('yith_woocompare_compare_button_in_products_list') == 'yes' && $compare != 'disable') 
                                {
                                    epico_add_compare_button();
                                }

                                ?>
                            </div>
                        </div>
                        <div class="wrap_after_thumbnail">
                            <?php
                            echo '<a href="' . get_the_permalink() . '" ><h3>' . get_the_title() . '</h3></a>';

                            if ( $price_html = $product->get_price_html() ) { ?>
                                <span class="price"><?php echo $price_html; ?></span>
                            <?php }


                            if ( $rating_html = wc_get_rating_html( $product->get_average_rating() ) ) {
                                echo $rating_html;
                            } 

                            ?>

                        </div>
                    </div>
                </li>
            <?php
                }
        
                elseif($style == 'style2')
                {
                ?>
                <li <?php post_class( $class); ?>>
                    <div class="productwrap">
                        <?php echo $hover_layer; ?>
                        <?php 
                        if($badges != 'disable')
                        {
                            if ( $product->is_on_sale() ) {

                                echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'vitrine' ) . '</span>', $post, $product );
                            
                            }

                            if ( !$product->is_in_stock() ) {          
                                    
                                echo '<div class="out_of_stock_badge_loop">' . esc_html__( 'Out of stock', 'vitrine' ) .'</div>';            
                                    
                            }
                        }
                        ?>

                        <div class="add_to_cart_btn_wrap lazy-load-hover-container">
                            <?php 
                            
                            //echo '<a href="' . esc_url(get_the_permalink()) . '" class="product-link" title="' . esc_attr(get_the_title()) .'"><span class="hidden-desktop">' . esc_html__('go to detail','vitrine') .'</span></a>'; 
                            echo '<a href="' . esc_url(get_the_permalink()) . '" class="product-link"><span class="hidden-desktop">' . esc_html__('go to detail','vitrine') .'</span></a>'; 


                            $image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
                            if($image_size == 'full')
                            {
                                $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full' );
                                $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="' . esc_attr($image_attributes[1]) . '" height="'. esc_attr($image_attributes[2]) .'" data-src="'.esc_url($image_attributes[0]).'" alt="'. esc_attr($image_title) .'" />';
                            }
                            else
                            {
                                if(function_exists('wc_get_image_size')) {

                                    $image_dimension = wc_get_image_size($image_size);

                                    $image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
                                    $image_attributes = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], false, true);
                                    
                                    if($image_attributes[0]){
                                        $img_url = $image_attributes[0];
                                    }
                                    else {
                                        $img_url = $image_link;
                                    }

                                    $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="' . esc_attr($image_attributes[1]) .'" height="' . esc_attr($image_attributes[2]) . '" data-src="'.esc_url($img_url).'" alt="'. esc_attr($image_title) .'" />';

                                } else {

                                    $image = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', $image_size ) );

                                }
                            }

                            ?>
                            <div class="imageswrap productthumbnail lazy-load lazy-load-on-load" style="padding-top: <?php echo epico_get_height_percentage($image); ?>%;">
                                <?php
                                echo $image;//img tag - Sanitized in above lines!
                                ?>
                            </div>
                            <?php

                            $attachment_ids = $product-> get_gallery_image_ids();
                            if(count($attachment_ids) >= 1  && $hover_image == 'show')
                            {
                                $image_src = '';
                                $image = '';
                                $attachment_id = $attachment_ids[0];

                                if($image_size == 'full')
                                {
                                    $image_src = wp_get_attachment_image_src( $attachment_id , 'full' );
                                    $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_src[0]).'"></div>';

                                }
                                else
                                {
                                    if(function_exists('wc_get_image_size')) {

                                        $image_dimension = wc_get_image_size($image_size);

                                        $image_link  = wp_get_attachment_url( $attachment_id);
                                        $img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);
                                        
                                        if(!$img_url) {
                                            $img_url = $image_link;
                                        }

                                        $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($img_url).'"></div>';

                                    } else {

                                        $image_url = wp_get_attachment_image_src($attachment_id, apply_filters( 'single_product_large_thumbnail_size', $image_size ) );
                                        if($image_url != false )
                                        {
                                            $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_url[0]).'"></div>';
                                        }
                                        else
                                        {
                                            $image_src = wp_get_attachment_image_src( $attachment_id , 'full' );
                                            $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_src[0]).'"></div>';
                                        }

                                    }
                                }

                                echo $image;//img tag - Sanitized in above lines!
                            }
                            ?>
                            
                            <div class="product-buttons">
                                <?php
								
                                $button	= apply_filters( 'woocommerce_loop_add_to_cart_link',
                                    sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s %s">%s</a>',
                                        esc_url( $product->add_to_cart_url() ),
                                        esc_attr( $product->get_id() ),
                                        esc_attr( $product->get_sku() ),
                                        esc_attr( isset( $quantity ) ? $quantity : 1 ),
                                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                        esc_attr( $product->get_type() ),
										esc_attr( $product->get_type() == 'simple' && 'yes'  === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ? 'ajax_add_to_cart' : 'swiper-no-swiping'),//Epico codes
                                        ( $product->add_to_cart_text() )
                                    ),
                                $product );
																
								if($catalog_mode == 0)
								{
									echo $button;
								}

                                if($wishlist != 'disable')
                                {
                                    epico_shop_page_wishlist_button();
                                }
                                
                                if( $quickview != 'disable') 
                                {
                                    epico_add_quick_view_button();
                                }

                                if( class_exists('YITH_Woocompare') && get_option('yith_woocompare_compare_button_in_products_list') == 'yes' && $compare != 'disable') 
                                {
                                    epico_add_compare_button();
                                }

                                ?>
                            </div>
                        </div>
                        <div class="wrap_after_thumbnail">
                            <?php
                            if ( $price_html = $product->get_price_html() ) { ?>
                                <span class="price"><?php echo $price_html; ?></span>
                            <?php }

                            echo '<h3>' . get_the_title() . '</h3>';
                            if ( $price_html = $product->get_price_html() ) {
                                if(strpos($price_html,"amount") > 0)
                                {
                                    $price_html = str_replace("&ndash;","",$price_html); // remove dash "-" used in variable products
                                }
                             ?>

                             <?php if ( $hover_price != 'disable') { ?>

                                <span class="price"><?php echo $price_html; ?></span>

                             <?php } ?>


                            <?php }

                            if ( $rating_html = wc_get_rating_html( $product->get_average_rating() ) ) {
                                echo $rating_html;
                            } 

                            ?>

                        </div>
                    </div>
                </li>
                <?php
                }
                elseif($style == 'style3')
                {
                ?>
                <li <?php post_class( $class);?> data-productid=<?php echo ($product->get_id()); ?>>
                    <div class="productwrap">
                        <?php 
                        if($badges != 'disable')
                        {
                            if ( $product->is_on_sale() ) {

                                echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'vitrine' ) . '</span>', $post, $product );
                            
                            }

                            if ( !$product->is_in_stock() ) {          
                                    
                                echo '<div class="out_of_stock_badge_loop">' . esc_html__( 'Out of stock', 'vitrine' ) .'</div>';            
                                    
                            }
                        }
                        ?>
                        <div class="add_to_cart_btn_wrap lazy-load-hover-container">
                            <?php
                            $image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
                            if($image_size == 'full')
                            {
                                $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full' );
                                $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="' . esc_attr($image_attributes[1]) . '" height="'. esc_attr($image_attributes[2]) .'" data-src="'. esc_url($image_attributes[0]) .'" alt="' . esc_attr($image_title) .'" />';
                            }
                            else
                            {
                                if(function_exists('wc_get_image_size')) {

                                    $image_dimension = wc_get_image_size($image_size);

                                    $image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
                                    $image_attributes = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], false, true);
                                    
                                    if($image_attributes[0]){
                                        $img_url = $image_attributes[0];
                                    }
                                    else {
                                        $img_url = $image_link;
                                    }

                                    $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="' . esc_attr($image_attributes[1]) .'" height="' . esc_attr($image_attributes[2]) . '" data-src="'.esc_url($img_url).'" alt="'. esc_attr($image_title) .'" />';

                                } else {

                                    $image = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', $image_size ) );

                                }
                            }
                            ?>
                            <div class="imageswrap productthumbnail  lazy-load lazy-load-on-load" style="padding-top: <?php echo esc_attr(epico_get_height_percentage($image)); ?>%;">
                                <?php
                                //Sanitization performed in above lines!
                                echo $image;
                                ?>
                            </div>
                            <?php

                            $attachment_ids = $product-> get_gallery_image_ids();
                            if(count($attachment_ids) >= 1  && $hover_image == 'show')
                            {
                                $image_src = '';
                                $image = '';
                                $attachment_id = $attachment_ids[0];

                                if($image_size == 'full')
                                {
                                    $image_src = wp_get_attachment_image_src( $attachment_id , 'full' );
                                    $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_src[0]).'"></div>';

                                }
                                else
                                {
                                    if(function_exists('wc_get_image_size')) {

                                        $image_dimension = wc_get_image_size($image_size);

                                        $image_link  = wp_get_attachment_url( $attachment_id);
                                        $img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);
                                        
                                        if(!$img_url) {
                                            $img_url = $image_link;
                                        }

                                        $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($img_url).'"></div>';

                                    } else {

                                        $image_url = wp_get_attachment_image_src($attachment_id, apply_filters( 'single_product_large_thumbnail_size', $image_size ) );
                                        if($image_url != false )
                                        {
                                            $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_url[0]).'"></div>';
                                        }
                                        else
                                        {
                                            $image_src = wp_get_attachment_image_src( $attachment_id , 'full' );
                                            $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_src[0]).'"></div>';
                                        }

                                    }
                                }

                                echo $image; // img tag - Sanitized in above lines!
                            }
                            ?>
                            <span class="show-hover"></span>
                            <div class="hover-content no-select">
                                <a href="<?php echo get_the_permalink(); ?>" ><h3><?php echo get_the_title(); ?></h3></a>
                                <?php

                                if ( $price_html = $product->get_price_html() ) {
                                   ?> <span class="price"><?php echo $price_html; ?></span>
                                <?php } 

                                if( $hover_description != 'disable') 
                                {
                                    wc_get_template( 'single-product/short-description.php' );
                                }

                                ?>
                            </div>
                        </div>
                        <div class="wrap_after_thumbnail">
                            <?php
                            echo '<a href="' . get_the_permalink() .'" ><h3>' . get_the_title() . '</h3></a>';
                            if ( $price_html = $product->get_price_html() ) { ?>
                                <span class="price"><?php echo $price_html; ?></span>
                            <?php } ?>

                        </div>  
                    </div>
                </li>
                <?php
                } 
                else // instant shop style
                {
                ?>
                <li <?php post_class( $class); ?>>
                    <div class="productwrap">
                        <?php 
                        if($badges != 'disable')
                        {
                            if ( $product->is_on_sale() ) {

                                echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'vitrine' ) . '</span>', $post, $product );
                            
                            }

                            if ( !$product->is_in_stock() ) {          
                                    
                                echo '<div class="out_of_stock_badge_loop">' . esc_html__( 'Out of stock', 'vitrine' ) .'</div>';            
                                    
                            }
                        }
                        ?>

                        <div class="add_to_cart_btn_wrap lazy-load-hover-container">
                            <?php 
                                //echo '<a href="' . esc_url(get_the_permalink()) . '" class="product-link" title="' . esc_attr(get_the_title()) .'"></a>'; 
                                echo '<a href="' . esc_url(get_the_permalink()) . '" class="product-link"></a>'; 

                            $image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
                            if($image_size == 'full')
                            {
                                $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full' );
                                $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="' . esc_attr($image_attributes[1]) . '" height="'. esc_attr($image_attributes[2]) .'" data-src="'.esc_url($image_attributes[0]).'" alt="'. esc_attr($image_title) .'" />';
                            }
                            else
                            {
                                if(function_exists('wc_get_image_size')) {

                                    $image_dimension = wc_get_image_size($image_size);

                                    $image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
                                    $image_attributes = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], false, true);
                                    
                                    if($image_attributes[0]){
                                        $img_url = $image_attributes[0];
                                    }
                                    else {
                                        $img_url = $image_link;
                                    }
                                    

                                    $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="' . esc_attr($image_attributes[1]) .'" height="' . esc_attr($image_attributes[2]) . '" data-src="'.esc_url($img_url).'" alt="'. esc_attr($image_title) .'" />';

                                } else {

                                    $image = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', $image_size ) );

                                }
                            }

                            ?>
                            <div class="imageswrap productthumbnail lazy-load lazy-load-on-load" style="padding-top: <?php echo esc_attr(epico_get_height_percentage($image)); ?>%;">
                                <?php
                                //Sanitization performed in above lines!
                                echo $image;
                                ?>
                            </div>
                            <?php

                            $attachment_ids = $product-> get_gallery_image_ids();
                            if(count($attachment_ids) >= 1 && $hover_image == 'show')
                            {

                                $image_src = '';
                                $image = '';
                                $attachment_id = $attachment_ids[0];

                                if($image_size == 'full')
                                {
                                    $image_src = wp_get_attachment_image_src( $attachment_id , 'full' );
                                    $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_src[0]).'"></div>';

                                }
                                else
                                {
                                    if(function_exists('wc_get_image_size')) {

                                        $image_dimension = wc_get_image_size($image_size);

                                        $image_link  = wp_get_attachment_url( $attachment_id);
                                        $img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);
                                        
                                        if(!$img_url) {
                                            $img_url = $image_link;
                                        }

                                        $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($img_url).'"></div>';

                                    } else {

                                        $image_url = wp_get_attachment_image_src($attachment_id, apply_filters( 'single_product_large_thumbnail_size', $image_size ) );
                                        if($image_url != false )
                                        {
                                            $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_url[0]).'"></div>';
                                        }
                                        else
                                        {
                                            $image_src = wp_get_attachment_image_src( $attachment_id , 'full' );
                                            $image = '<div class="hover-image lazy-load lazy-load-hover bg-lazy-load" data-src="'.esc_url($image_src[0]).'"></div>';
                                        }

                                    }
                                }
                                //Sanitization performed in above lines!
                                echo $image;

                            }
                            ?>
							<div class="product-buttons">
								<?php
                                if( $quickview != 'disable') 
                                {
                                    epico_add_quick_view_button();
                                }
								?>
							</div>
                        </div>
                        <div class="wrap_after_thumbnail">
							<div class="product-buttons">
								<?php
                                if($wishlist != 'disable')
                                {
                                    epico_shop_page_wishlist_button();
                                }
								?>
							</div>
                            <?php
                            echo '<a href="' . get_the_permalink() . '" ><h3 class="instant_shop_heading">' . get_the_title() . '</h3></a>';
                            ?>

                            <div class="instant_shop_button">

                                <?php if ( $price_html = $product->get_price_html() ) { ?>
                                    <span class="price"><?php echo $price_html; ?></span>
                  
                                    <?php

                                        $button =  apply_filters( 'woocommerce_loop_add_to_cart_link',
                                            sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s product_type_%s %s">%s</a>',
                                                esc_url( $product->add_to_cart_url() ),
                                                esc_attr( $product->get_id() ),
                                                esc_attr( $product->get_sku() ),
                                                esc_attr( isset( $quantity ) ? $quantity : 1 ),
                                                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                                esc_attr( $product->get_type() ),
												esc_attr( $product->get_type() == 'simple' && 'yes'  === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ? 'ajax_add_to_cart' : 'swiper-no-swiping'),//Epico codes
                                                ( $product->add_to_cart_text() )
                                            ),
                                        $product );

                                        $button = apply_filters('epico_loop_instant_shop_add_to_cart_link', $button);
                                        echo $button;
                                    ?>

                            <?php } else { ?>
                                
                                    <span class="price"></span>

                                    <div class="no_price">
                                        <?php
										
                                            $button = apply_filters( 'woocommerce_loop_add_to_cart_link',
                                                sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s product_type_%s %s">%s</a>',
                                                    esc_url( $product->add_to_cart_url() ),
                                                    esc_attr( $product->get_id() ),
                                                    esc_attr( $product->get_sku() ),
                                                    esc_attr( isset( $quantity ) ? $quantity : 1 ),
                                                    $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                                    esc_attr( $product->get_type() ),
													esc_attr( $product->get_type() == 'simple' && 'yes'  === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ? 'ajax_add_to_cart' : 'swiper-no-swiping'),//Epico codes
                                                    ( $product->add_to_cart_text() )
                                                ),
                                            $product );

                                            $button = apply_filters('epico_loop_instant_shop_add_to_cart_link', $button);
                                            echo $button;
                                        ?>
                                    </div>

                            <?php } ?>

                            </div>

                            <?php 
                                if ( $rating_html = wc_get_rating_html( $product->get_average_rating() ) ) {
                                    echo $rating_html;
                                } 
                            ?>


                        </div>
                    </div>
                </li>
                <?php
                }
            endwhile; // end of the loop. ?>
                        
            <?php if($atts['carousel'] == 'enable') { ?>
                    </div>
                </div>
                <div class="arrows-button-next"></div>
                <div class="arrows-button-prev"></div>
            <?php } ?>

            </ul>
            <?php //Use </ul> tag instead of calling woocommerce_product_loop_end(); to detect WC shortcodes ?>

            <?php do_action( "woocommerce_shortcode_after_{$loop_name}_loop" ); ?>

        <?php endif;

        woocommerce_reset_loop();
        wp_reset_postdata();


       $animation_class = '';
        if( $animation != 'none') {
            $animation_class = ' shortcodeAnimation';
        }

        if($responsive_animation != '')
        {
            $animation_class .= ' no-responsive-animation';
        }

        return '<div class="woocommerce wc-shortcode ' . esc_attr($gutter) . ' ' . ($enterance_animation != 'default' ? esc_attr($enterance_animation) : '') .  ' ' . ($atts['carousel'] == 'enable') . ' ' . esc_attr($carouselClass . $animation_class . ' ' . $nav_style) . '"' . ' data-layoutMode="'. esc_attr($layout_mode).'" ' . ($autoplay != '' ? 'data-autoplay="on"':'') . (strlen(esc_attr($animation)) ?  ' data-delay="' . esc_attr($delay) . '" data-animation="' . esc_attr($animation) : '') . '">' . ob_get_clean() . '</div>';
    }


    //Product categories
    function epico_product_categories ( $atts ) {
        global $woocommerce_loop;

        $atts = shortcode_atts( array(
            'number'     => null,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'columns'    => '1',
            'image_size'  => 'shop_single',
            'image_size_width' => '',
            'image_size_height' => '',
            'image_size_crop' => '',
            'hide_empty' => 1,
            'parent'     => '',
            'ids'        => '',
            'border'     => '',
            'gutter' => '',
            'count'      => '',
            'description'      => 'enable',
            'hover_color'=> 'c0392b',
            'custom_hover_color'=> '',
            'style' => '#333',
            'animation' => 'none',
            'delay' => '0',
            'responsive_animation' => 'disable',
            'hover_text_color' => '#333',
        ), $atts );

        extract($atts);
		if(!class_exists("woocommerce"))
			return;

        $gutter = ($gutter == 'no' ? 'no-gutter':'');

        if ( isset( $ids ) ) {
            $ids = explode( ',', $ids );
            $ids = array_map( 'trim', $ids );
        } else {
            $ids = array();
        }

        $hide_empty = ( $hide_empty == true || $hide_empty == 1 ) ? 1 : 0;

        // get terms and workaround WP bug with parents/pad counts
        $args = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
            'include'    => $ids,
            'pad_counts' => true,
            'child_of'   => $parent
        );

        $product_categories = get_terms( 'product_cat', $args );

        if( is_wp_error( $product_categories ) ) {
            return;
        }

        if ( '' !== $parent ) {
            $product_categories = wp_list_filter( $product_categories, array( 'parent' => $parent ) );
        }

        if ( $hide_empty ) {
            foreach ( $product_categories as $key => $category ) {
                if ( $category->count == 0 ) {
                    unset( $product_categories[ $key ] );
                }
            }
        }

        if ( $number ) {
            $product_categories = array_slice( $product_categories, 0, $number );
        }

        $columns = absint( $columns );
        $woocommerce_loop['columns'] = $columns;

        $class = array();
        if( $animation != 'none') {
            $class[] = 'shortcodeAnimation';

            if($responsive_animation != '')
            {
                $class[] = 'no-responsive-animation';
            }
        }

        $color = 'c0392b';
        if ( isset( $hover_color ) ) {
            if($hover_color != 'custom')
            {
                $color = "#" . $hover_color;
            }
            else
            {
                if(isset( $custom_hover_color ))
                {
                    $color = $custom_hover_color;
                }

            }
        }

        if($image_size == 'custom' && $image_size_width != '' && $image_size_height != '')
        {
            if($image_size_crop == 'yes')
            {
                $image_size_crop = true;
            }
            else
            {
                $image_size_crop = false;
            }

            $image_size = array($image_size_width, $image_size_height, $image_size_crop);
        }

        ob_start();

        // Reset loop/columns globals when starting a new loop
        $woocommerce_loop['loop'] = $woocommerce_loop['column'] = '';

        if ( $product_categories ) {

            // Use <ul> tag instead of calling woocommerce_product_loop_start(); to detect WC shortcodes  ?>
            <ul class="products <?php echo 'shop-' . esc_attr($columns) . 'column' ?>">

            <?php
            foreach ( $product_categories as $category ) {
                wc_get_template( 'content-product_cat.php', array(
                    'category' => $category,
                    'border' => $border,
                    'count' => $count,
                    'description' => $description,
                    'hover_color' => $color,
                    'style' => $style,
                    'image_size' => $image_size,
                    'hover_text_color'=>$hover_text_color,
                ) );
            }
            ?>
            </ul>
            <?php //Use </ul> tag instead of calling woocommerce_product_loop_end(); to detect WC shortcodes
        }

        woocommerce_reset_loop();

        return '<div class="woocommerce wc-categories  ' . esc_attr($gutter . ' ' . implode(' ', $class)) . '"  ' . (strlen(esc_attr($animation)) ?  'data-delay="' . esc_attr($delay) . '" data-animation="' . esc_attr($animation) : '') . '">' . ob_get_clean() . '</div>';
    }


    function epico_product_page( $atts ) {
		if(!class_exists("woocommerce"))
			return;		
		
        if ( empty( $atts ) ) {
            return '';
        }

        if ( ! isset( $atts['id'] ) && ! isset( $atts['sku'] ) ) {
            return '';
        }

        $args = array(
            'posts_per_page'      => 1,
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'no_found_rows'       => 1
        );

        if ( isset( $atts['sku'] ) ) {
            $args['meta_query'][] = array(
                'key'     => '_sku',
                'value'   => sanitize_text_field( $atts['sku'] ),
                'compare' => '='
            );

            $args['post_type'] = array( 'product', 'product_variation' );
        }

        if ( isset( $atts['id'] ) ) {
            $args['p'] = absint( $atts['id'] );
        }

        $single_product = new WP_Query( $args );

        $preselected_id = '0';

        // check if sku is a variation
        if ( isset( $atts['sku'] ) && $single_product->have_posts() && $single_product->post->post_type === 'product_variation' ) {

            $variation = new WC_Product_Variation( $single_product->post->ID );
            $attributes = $variation->get_variation_attributes();

            // set preselected id to be used by JS to provide context
            $preselected_id = $single_product->post->ID;

            // get the parent product object
            $args = array(
                'posts_per_page'      => 1,
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'no_found_rows'       => 1,
                'p'                   => $single_product->post->post_parent
            );

            $single_product = new WP_Query( $args );
        ?>
            <script type="text/javascript">
                jQuery( document ).ready( function( $ ) {
                    var $variations_form = $( '[data-product-page-preselected-id="<?php echo esc_attr( $preselected_id ); ?>"]' ).find( 'form.variations_form' );

                    <?php foreach( $attributes as $attr => $value ) { ?>
                        $variations_form.find( 'select[name="<?php echo esc_attr( $attr ); ?>"]' ).val( '<?php echo esc_attr($value); ?>' );
                    <?php } ?>
                });
            </script>
        <?php
        }

				// For "is_single" to always make load comments_template() for reviews.
		$single_product->is_single = true;

        ob_start();

		global $wp_query;

		// Backup query object so following loops think this is a product page.
		$previous_wp_query = $wp_query;
		$wp_query          = $single_product;

		wp_enqueue_script( 'wc-single-product' );

		while ( $single_product->have_posts() ) {
			$single_product->the_post()
			?>
			<div class="single-product" data-product-page-preselected-id="<?php echo esc_attr( $preselected_id ); ?>">
				<?php wc_get_template_part( 'content', 'single-product' ); ?>
            </div>
			<?php
		}

		// restore $previous_wp_query and reset post data.
		$wp_query = $previous_wp_query;
        wp_reset_postdata();

        /*--- Added by epico ---*/
        $classes = array();
        //Check wishlist
        if (class_exists('YITH_WCWL'))
        {
            $classes[] = 'wishlist-enable';
        }

        //Check compare
        if (class_exists('YITH_Woocompare'))
        {
            $classes[] = 'compare-enable';
        }
        

        //Product gallery
        if(function_exists('is_woocommerce')) {
            global $product;
            if(is_product())
            {
                $attachment_ids = $product-> get_gallery_image_ids();
                if(count($attachment_ids) > 0)
                {
                    $classes[] = 'have_gallery';
                }
            }

            $classes[] = 'shop-'. epico_opt('shop-column') .'column';
            
        }    

        /**/

        return '<div class="woocommerce product-page-shortcode ' . implode(" ", $classes) .  '">' . ob_get_clean() . '</div>';
    }

/*-----------------------------------------------------------------------------------*/
/*  Instagram feed
/*-----------------------------------------------------------------------------------*/

function epico_sc_instgram_feed($atts)
{
    extract(shortcode_atts(array(
        'user' => 'croixmurphy',
        'posts_count' => '10',
        'column' => '1',
        'image_resolution' => 'thumbnail',
        'gutter' => '',
        'carousel' => 'disable',
        'naxt_prev_btn' => 'show',
        'nav_style' => 'light',
        'hover_color' => '',
        'custom_hover_color' => '',
        'like' => '',
        'comment' => '',
        'enterance_animation'=> 'fadeInFromBottom',
        'responsive_animation' => 'disable',
        'delay' => '0',
    ), $atts));
    
        $id = epico_sc_id('instagram_feed');

        $media_array = epicomedia_scrape_instagram($user, $posts_count);

        //if(!isset($result->data) || $profile->meta->code != '200')
        //{
        //    ob_start();
        //    //print the error message
        //    echo '<div class="instagram-feed">';
        //    echo 'Instagram Error : ' . $result->meta->error_message;
        //    echo '</div>';
        //    return ob_get_clean();
        //}


        $gutter = ($gutter == 'no' ? 'no-gutter':'');

        //if(count($media_array -> $item) <= $column) {
        //    $carousel = 'disable';
        //}

        if($posts_count < $column) {
            $post_count = $column;
        }

        $carouselClass= '';
        $carouselItemClass = '';
        if($carousel == 'enable')
        {
            $carouselClass = 'carousel instagram-carousel';

            $carouselItemClass = 'insta-media';
        }

        $color = '#c0392b';
        if ( isset( $hover_color ) ) {
            if($hover_color != 'custom' && $hover_color != '')
            {
                $color = "#" . $hover_color;
            }
            else
            {
                if(isset( $custom_hover_color ) && $custom_hover_color != '')
                {
                    if(strpos($custom_hover_color, '#') === false)
                    {
                        $custom_hover_color  = '#' . $custom_hover_color;
                    }

                    $color = $custom_hover_color;
                }

            }
        }
        ?>
        <style type="text/css" media="all">
        <?php
        echo ".instagram-feed#$id li .hover"; ?>
        {
            background-color: <?php echo esc_attr($color); ?>;
        }
        </style>
        <?php

        ob_start();
            
        ?>

        <div class="instagram-feed <?php echo  $enterance_animation; if($responsive_animation != '') { echo ' no-responsive-animation'; } ?>" id="<?php echo esc_attr($id); ?>">

            <!-- header instagram  -->
            <ul class="<?php echo 'column-' . esc_attr($column) . ' ' . esc_attr($gutter) . ' ' . esc_attr($carouselClass) . ' ' . esc_attr($nav_style); ?><?php if ( $enterance_animation != 'default') { ?> hasAnimation <?php } ?>">
                <?php
                if($carousel == 'enable') { ?>
                        <div class="swiper-container" data-visibleitems="<?php echo esc_attr($column); ?>">
                            <div class="swiper-wrapper lazy-load-hover-container">
                <?php 
                }
                $i = 1;

                foreach ($media_array as $item) {

                    if($i > $posts_count)
                    {
                        break;
                    }
                    $i++; 


                    if($image_resolution == 'low_resolution' || $image_resolution == 'low_resolution_crop' || $image_resolution == 'standard_resolution'|| $image_resolution == 'standard_resolution_crop' ){
                         $image =  $item['large'];
                    } else {
                        $image =  $item[$image_resolution];
                    }

                  
                    $media_tag = "<img src=\"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\" class=\"media\" data-src=\"{$image}\" />";
                   
                    $content = '<li' . ($carouselItemClass != ''? ' class="' . esc_attr($carouselItemClass) . '"':'') . '>';
                    $content .= '<a  href="' . esc_url($item['link']) . '" target="_blank">';

                    $content .=  '<img hasan src="'. esc_url( $image ) .'" />';

                    $content .= '<div class="hover"></div>
                              <div class="content">';

                    if($like == 'enable')
                    {
                        $content .= '<span class="like">' . epicomedia_pretty_number( $item['likes'] ) . '</span>';
                    }
                    
                    if($comment == 'enable')
                    {
                        $content .= '<span class="comment">' . epicomedia_pretty_number( $item['comments'] ) . '</span>';
                    }

                   $content .= '</div>';

                    // output media
                    echo $content . '</a></li>';

                }

                if($carousel == 'enable') { ?>
                            </div>
                        </div>
                <?php
                }

                if($naxt_prev_btn == 'show') { ?>
                    <div class="arrows-button-next"></div>
                    <div class="arrows-button-prev"></div>
                <?php 
                }

                ?>
            </ul>
        </div>

        <?php
        return ob_get_clean();
}


/*-----------------------------------------------------------------------------------*/
/*  Masonry Blog - Cart blog 
/*-----------------------------------------------------------------------------------*/

function epico_sc_blog_masonry( $atts, $content = null ){
    extract( shortcode_atts( array(
        'blog_column'            => '3',
        'blog_category'          => '',
        'blog_post_number'       => '16' ,
        'blog_foreground_color'  => 'dark',
        'blog_layout_mode'  => 'masonry',
        'blog_background_color'  => '#f8f8f8',
        'quote_blog_background_color'  => '#073B87',
        'quote_blog_text_color'  => '#fff',
        'blog_category_author' => 'yes',
        'blog_filter'=> 'all',
        'blog_style'=> 'inline_interaction',
        'enterance_animation'=> 'fadeInFromBottom',
        'responsive_animation' => 'disable',
        'blog_category_visibility'=> 'yes',
        'blog_multimedia_icon_style'=> 'light',
        'load_more_style'=> 'dark',
        'blog_image_size'=> 'large',
    ), $atts ) );

    $query=$width=$subStr = $col=$id='';

    $i=0;

    $id = epico_sc_id('blog-masonry');
    $postpage = isset( $_GET['postpage'] ) ? (int) $_GET['postpage'] : 1;


    if($blog_category == '')
    {
        $category_array = '';
        $arrg = array(
            'posts_per_page' => $blog_post_number,
            'paged'          => $postpage,
            'post_type' => 'post',
        );
    }
    else
    {
        $category_array = explode(',', $blog_category);
        $arrg = array(
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'slug',
                    'terms' => $category_array,
                )
            ),
            'posts_per_page' => $blog_post_number,
            'paged'          => $postpage,
            'post_type' => 'post',
        );
    }

    $query = new WP_Query($arrg);

    $postCount = $query-> found_posts;
    $max=ceil ($postCount / $blog_post_number);

    
    if($blog_column=='3') {
        $width=100/3 ;
        $col = 3;
    } else {
        $width=100/4;
        $col = 4 ;
    }
    
    ob_start();

    ?>

    <style type="text/css" media="all">

        .<?php echo esc_attr($id); ?> .blog-masonry-container {
            background-color: <?php echo esc_attr($blog_background_color); ?>;      
        }
  
        .<?php echo esc_attr($id); ?> .blog-masonry-container.ep_quote {
            background-color: <?php echo esc_attr($quote_blog_background_color); ?>;
        }

        .<?php echo esc_attr($id); ?> .blog-masonry-container .video-img{
            width:100%;
        }
        
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .like-count,
        .<?php echo esc_attr($id); ?> .blog-masonry-container .blog-masonry-content .post-share:hover .share-hover i{
            color: <?php echo esc_attr($blog_background_color); ?>;
        }

        .<?php echo esc_attr($id); ?> .blog-masonry-container.ep_quote .icon,
        .<?php echo esc_attr($id); ?> .blog-masonry-container.ep_quote .blog-masonry-content .blog-excerpt {
            color: <?php echo esc_attr($quote_blog_text_color); ?>;
        }

    </style>

    <div id="<?php echo esc_attr($id) ?>" class="blogLoop <?php echo esc_attr($id);?> masonry-blog isotope clearfix <?php echo esc_attr($blog_style);?> blogcolumn<?php echo esc_attr($blog_column); ?>  <?php if ( $enterance_animation != 'default') { echo 'hasAnimation'; if($responsive_animation != '') { echo ' no-responsive-animation';} } ?> <?php echo esc_attr($enterance_animation); ?>" data-columnnumber="<?php echo esc_attr($blog_column); ?>" data-layoutmode="<?php echo esc_attr($blog_layout_mode); ?>" data-id="<?php echo esc_attr($id) ?>" data-page="1" data-maxpages="<?php echo esc_attr($max)?>">

        <?php while ($query->have_posts()) {
            $i++;
            $query->the_post();
            global $post;

            if(strlen(get_the_excerpt())>100){
                $subStr = '...';
            }else{
                $subStr='';
            }

            $format = get_post_meta( get_the_ID() ,'media', true);

            //Fetch featured image as cover
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id() , 'epico_blog_thumbnail-fixed-height' )[0];

        ?>

        <div class="isotope-item">
            <div class="blog_item">
                <div class="blog-masonry-container ep_lightGallery <?php echo esc_attr($blog_foreground_color); ?> <?php echo 'ep_'.esc_attr($format);?>" <?php if($format=='quote' && ! (empty($thumb[0])) ) { ?> style="background-image:url(<?php echo esc_attr($thumb);?>);" <?php } ?> >
                
                    <?php if ($format=='audio') {

                            $audio = get_post_meta( get_the_ID() ,'audio-url', true); ?>
                         <?php
                            if($audio != null) { ?>

                            <?php if($blog_style=='popup_interaction') { ?>

                            <?php
                                if( $blog_image_size == "large"){
                                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id() , 'large');  
                                }
                                else{
                                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id() , 'epico_thumbnail-auto-height');  
                                }

                                if($thumb != false ){
                                    $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="'. esc_attr($thumb[1]) . '" height="'. esc_attr($thumb[2]) . '" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" data-src="'.esc_url($thumb[0]).'" alt="" />';
                                } else {
                                    $image = '<div class="cartBlogAudioPlaceHolder"></div>';
                                }
                            ?>

                            <?php  if( $thumb != false ) { ?> 

                                    <div class="soundcloud-format galleryItem" data-iframe="true" data-src="https://w.soundcloud.com/player/?visual=true&url=<?php echo esc_attr($audio); ?>">
                                        <div class="image-container lazy-load lazy-load-on-load" style="padding-top:<?php echo epico_get_height_percentage($image); ?>%">
                                           <div class="play-button-wrap">
                                                <a>
                                                    <div class="play-button <?php echo esc_attr($blog_multimedia_icon_style); ?>">
                                                        <span class="glyph icon icon-music-note3"></span>
                                                    </div>
                                                </a>
                                            </div>
                                            <?php
                                            //Sanitization performed in above lines!
                                            echo $image;
                                            ?>
                                        </div>
                                    </div>

                            <?php  } ?> 

                            <?php } else { 

                                 echo do_shortcode('[audio_soundcloud soundcloud_id="'.esc_attr($audio).'"]');

                                } ?>

                        <?php } ?>
                    
                   <?php } else if ($format=='gallery') {
                       
                        $images_urls = get_post_meta( get_the_ID() ,'gallery', true);
                        if(is_array($images_urls))
                        {
                            $images = implode(",",$images_urls);
                        }
                        else
                        {
                            $images = $images_urls;
                        }
                                 
                        $images=explode(",",$images); 
                        ?>

                        <div class=" masonryBlog">
                            <?php
                            if ($images[0]!=''){?><!-- If we have images -->
                            <div class=" swiper-container clearfix" data-visibleitems="1">            
                                <div class="slides swiper-wrapper">
                                    <?php
                                    foreach($images as $img){
                                        $img_id = epico_get_image_id($img);

                                        if($blog_image_size == "large"){
                                            
                                            $img = wp_get_attachment_image_src( $img_id , 'large');
                                        }
                                        else
                                        {
                                            $img = wp_get_attachment_image_src( $img_id , 'epico_thumbnail-auto-height');
                                        }
                                
                                      
                                     ?>
                                        <div class="swiper-slide">
                                            <div class="blogGalleryContainer" style="background-image:url(<?php print_r(esc_url($img[0])); ?>);">
                                                <span class="imageWrapper"></span>
                                            </div>
                                        </div>
                              <?php }?>
                                </div>
                                <?php if ( count($images) > 1) { ?>
                                <!-- If we need navigation buttons -->
                                <div class="swiper-button-prev arrows-button-prev"></div>
                                <div class="swiper-button-next arrows-button-next"></div>
                                <?php } ?>

                            </div>
                           <?php }else{?><!-- If we don't have images -->
                               <div class="cartBlogPlaceHolder"></div>
                          <?php }?>
                       </div>
                <?php
                    } else if ( $format=='video') {
                        $videoUrl=get_post_meta( get_the_ID(), 'video-id', true);
                        $host = get_post_meta( get_the_ID() ,'video-type', true);

                        if( $blog_style=='popup_interaction'){ ?>

                           <?php

                                if( $blog_image_size == "large"){
                                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id() , 'large');  
                                }
                                else{
                                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id() , 'epico_thumbnail-auto-height');  
                                }
                                if ( $thumb != false) {  // if set features image
                                    $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="'. esc_attr($thumb[1]) . '" height="'. esc_attr($thumb[2]) . '" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" data-src="'.esc_url($thumb[0]).'" alt="" />';
                                }

                           ?>

                           <?php if ( $thumb != false) { // if set features image ?> 
                                <div class="video-format"  data-id="<?php echo esc_attr($id); ?>"> 
                                    <div class="video_buttons_wrap">
                                        <a class="galleryItem" href="<?php echo esc_attr($videoUrl); ?>">
                                            <div class="image-container lazy-load lazy-load-on-load" style="padding-top:<?php echo esc_attr(epico_get_height_percentage($image)); ?>%">
                                                <?php
                                                //Sanitization performed in above lines!
                                                echo $image; ?>
                                            </div>
                                            <div class="play-button <?php echo esc_attr($blog_multimedia_icon_style); ?>">
                                                <span class="glyph icon  icon-play2"></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                           <?php } ?>

                    <?php } else {

                        $video_display_type =   "embeded_video_$host";
                        if( $video_display_type == 'local_video' ) {

                        } else if ( $video_display_type == 'embeded_video_vimeo' ) { ?>

                            <div data-id="<?php echo esc_attr($id); ?>">
                                <?php echo do_shortcode('[embed_video video_display_type="embeded_video_vimeo" video_vimeo_id="'.esc_attr($videoUrl).'"]'); ?>
                            </div>  

                        <?php } else if ( $video_display_type == 'embeded_video_youtube' ) {

                                // detect youtube id form url 
                                $video_id = explode("?v=", $videoUrl); // For videos like http://www.youtube.com/watch?v=...
                                if (empty($video_id[1])) {
                                    $video_id = explode("/v/", $videoUrl); // For videos like http://www.youtube.com/watch/v/..
                                }
                                if(!empty($video_id[1])){
                                        $video_id = explode("&", $video_id[1]); // Deleting any other params
                                        $video_id = $video_id[0]; 
                                }else{
                                    $video_id=$videoUrl;
                                }
                               
                                    

                            ?>

                         
                            <div data-id="<?php echo esc_attr($id); ?>">

                                <?php 
                                    $el_aspect = 'vc_video-aspect-ratio-169';
                                ?>

                                  <div class="wpb_video_widget wpb_content_element vc_clearfix <?php echo esc_attr($el_aspect); ?>">
                                    <div class="wpb_wrapper">
                                        <div class="wpb_video_wrapper"> 
                                            <iframe title="YouTube video player" src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>" frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php }

                        }

                    } elseif($format=='quote') {?>
                            
                    <?php
                    } else { //format is standard or combined formats like(video/sound)
                        if (has_post_thumbnail()) {
                              if( $blog_image_size == "large"){
                                  $thumb = wp_get_attachment_image_src( get_post_thumbnail_id() , 'large');  
                                }
                                else{
                                  $thumb = wp_get_attachment_image_src( get_post_thumbnail_id() , 'epico_thumbnail-auto-height');  
                                };
                            $image = '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" width="'. esc_attr($thumb[1]) . '" height="'. esc_attr($thumb[2]) . '" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" data-src="'. esc_url($thumb[0]) .'" alt="" />';
                               
                            ?>
                                <div class="image-container lazy-load lazy-load-on-load" style="padding-top:<?php echo esc_attr(epico_get_height_percentage($image)); ?>%">
                                    <?php
                                    //Sanitization performed in above lines!
                                    echo $image; ?>
                                </div>     
                            <?php
                        } else {?><!-- If we don't have images -->
                               <div class="cartBlogPlaceHolder"></div>
                        <?php 
                        }
                    }
                 
                 if($format=='quote') {
                       echo '<a class="quote-wrap-link" href="'.esc_url(get_the_permalink()) .'">';
                 }?>
                    <div class="blog-masonry-content">
                    
                        <?php if($format!='quote') { ?>
                            <span class="blog-details">
                            <?php
                            if ($blog_category_visibility=='yes'){
                                $terms = get_the_category($post->ID);
                                $catcounter=0;
                                if($terms)
                                    foreach ($terms as $term){

                                    $catcounter++;
                                    if ($catcounter<2 && (($term->cat_name) != 'Uncategorized'))
                                    {
                                ?>
                                    <span class="blog-cat">
                                       <a href="<?php echo esc_url( get_category_link( get_cat_ID($term->cat_name)))?>" title='<?php echo esc_attr($term->cat_name) ?>'>
                                       <?php echo esc_attr($term->cat_name) ?>
                                       </a>
                                    </span>
                                <?php }

                                } 
                            }?>
                            </span>
                        <?php
                        }
                        $archive_year  = get_the_time('Y');
                        $archive_month = get_the_time('m');
                        $archive_day   = get_the_time('d');

                        ?>
                        
                        <?php if($format!='quote') {?>
                        <a class="link_to_details" href="<?php the_permalink(); ?>">
                            <h2 class="blog-title"> <?php the_title(); ?></h2>
                            <span class="blog-date">
                                <span><?php the_time(get_option('date_format')) ?></span>
                            </span>
                            <p class="blog-excerpt"> <?php echo mb_substr(get_the_excerpt(),0, 100).$subStr; ?></p>
                            </a>
                        <?php 
                        }
                        else
                        {

                            $quote_content = get_post_meta( get_the_ID(), "quote_content", true );
                            $quote_author = get_post_meta( get_the_ID(), "quote_author", true );
                            if (!empty( $quote_content )&& !empty( $quote_author ) ) {
                                echo '<div class="icon ep-icon icon-quotes-right"></div>';
                            }?>
                            
                            <p class="blog-excerpt" style="color:<?php echo esc_attr($quote_blog_text_color);?>;"> <?php echo esc_html($quote_content); ?></p>
                            <p class="quote-author" style="color:<?php echo esc_attr($quote_blog_text_color);?>;"> <?php echo esc_html($quote_author); ?></p>
                        <?php 
                        }
                         ?>
                    </div>
                    <?php if($format=='quote') {
                        echo '</a>';
                    }

                     if( $format!='quote') { ?>
                        <?php if($blog_category_author=='yes') { ?>
                            <div class="post-author-meta">

                                <span class="ep-icon icon-user"></span>
                                <span class="post-author "><?php the_author_posts_link(); ?></span>
                                <span class="ep-icon icon-bubble"></span>
                                <span class="meta-comment-count"><a href="<?php comments_link(); ?>"> <?php comments_number( esc_html__('0', 'vitrine'), esc_html__('1', 'vitrine'), esc_html__('%', 'vitrine') ); ?></a></span>

                            </div>
                        <?php } ?>

                    <?php } ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

                <!-- Single Page Navigation-->
                <div class="pageNavigation cartBlog clearfix <?php echo esc_attr($load_more_style); ?>">
                    <div class="navNext"><?php next_posts_link(__('&larr; Older Entries', 'vitrine')) ?></div>
                    <div class="navPrevious"><?php previous_posts_link(__('Newer Entries &rarr;', 'vitrine')) ?></div>
                </div>


    <?php
    wp_reset_postdata();

    return ob_get_clean();

}
