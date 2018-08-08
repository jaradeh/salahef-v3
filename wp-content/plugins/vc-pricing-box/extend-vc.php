<?php
require_once vc_path_dir('SHORTCODES_DIR', 'vc-column.php');
class WPBakeryShortCode_Mnky_Pricing_Box extends WPBakeryShortCode_VC_Column {
	
	protected $controls_css_settings = 'tc vc_control-container';

	protected $controls_list = array('add', 'edit', 'clone', 'delete');
	public function __construct( $settings ) {
		parent::__construct( $settings );
	}


	public function mainHtmlBlockParams( $width, $i ) {
		return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] . ' wpb_sortable wpb_content_holder wpb_content_element"' . $this->customAdminBlockParams();
	}

	public function containerHtmlBlockParams( $width, $i ) {
		return 'class="wpb_column_container vc_container_for_children"';
	}

	public function getColumnControls( $controls, $extended_css = '' ) {
		return $this->getColumnControlsModular($extended_css);
	}

	/*---------------------------------------------------------------*/
	/* Generate pricing box front-end output
	/*---------------------------------------------------------------*/

	protected function content($atts, $content = null) {

		$output = $el_class = $title = $currency = $price = $time = $meta = $add_badge = $box_style = $bg_color = $badge_bg = $badge_color = $badge_text = $color = $css_animation = $css_animation_delay = $meta = '';
			
			extract(shortcode_atts(array(
				'el_class' => '',
				'title' => 'Starter Plan',
				'currency' => '$',
				'price' => '10',
				'time' => '/mo',
				'meta' => 'Great for small business',
				'add_badge' => 'off',
				'box_style' => 'box-style-1',
				'bg_color' => '#FFFFFF',
				'color' => '#5E5E5E',
				'badge_text' => 'Best Offer',
				'header_bg' => '#5E5E5E',
				'header_color' => '#FFFFFF',
				'badge_bg' => '#DD3333',
				'badge_color' => '#FFFFFF',
				'border_color' => '',
				'hover_effect' => '',
				'effect_active' => '',
				'link' => '',
				'css_animation' => '',
				'css_animation_delay' => ''
			), $atts));
			
			$el_class = $this->getExtraClass($el_class);
			$box_style = $this->getExtraClass($box_style);
			$hover_effect = $this->getExtraClass($hover_effect);
			$effect_active = $this->getExtraClass($effect_active);
			
			$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'pricing-box'.$box_style.$hover_effect.$effect_active.$el_class, $this->settings['base']);
			$css_class .= $this->getCSSAnimation($css_animation);
			($css_animation != '' && $css_animation_delay != '') ? $css_class .= $this->getExtraClass($css_animation_delay) : '';
			
			$link = ($link=='||') ? '' : $link;
			$link = vc_build_link($link);
			$a_href = $link['url'];
			$a_title = $link['title'];
			($link['target'] != '') ? $a_target = $link['target'] : $a_target = '_self';
				
			$title = '<span class="plan-title">'.$title.'</span>';
			$currency = '<span class="plan-currency">'.$currency.'</span>';
			$price = '<span class="plan-price">'.$price.'</span>';
			$time = '<span class="plan-time">'.$time.'</span>';
			($meta != '') ? $meta = '<span class="plan-meta">'.$meta.'</span>' : $meta = '';
			($border_color != '') ? $border_color = ' border:1px solid '.$border_color.';' : $border_color = '';
			
			$output .= '<div class="'.$css_class.'" >';
				$output .= '<div class="pricing-box-inner" style="background-color:'.$bg_color.'; color:'.$color.';'.$border_color.'">';
				
					if($add_badge == 'on') {
						$output .= '<div class="plan-badge" style="background-color:'.$badge_bg.'; color:'.$badge_color.';"><span>'.$badge_text.'</span></div>';
					}
				
					if($box_style == ' box-style-1') {
						$output .= '<div class="plan-header">';
							$output .= $title . $currency . $price . $time . $meta;
						$output .= '</div>';
						$output .= '<span class="plan-divider" style="background-color:'.$color.';"></span>';
					} elseif($box_style == ' box-style-2') {
						$output .= '<div class="plan-header" style="background-color:'.$header_bg.'; color:'.$header_color.';">';
							$output .= $title . $currency . $price . $time . $meta;
						$output .= '</div>';
						$output .= '<div class="plan-arrow" style="border-bottom-color:'.$bg_color.';"></div>';
					} elseif($box_style == ' box-style-3') {
						$output .= '<div class="plan-header">';
							$output .= '<span class="plan-title-wrapper" style="background-color:'.$header_bg.'; color:'.$header_color.';">';
								$output .= $title . $meta;
							$output .= '</span>';
							$output .= '<div class="plan-arrow" style="border-top-color:'.$header_bg.';"></div>';
							$output .= $currency . $price . $time;
						$output .= '</div>';
						$output .= '<span class="plan-divider" style="background-color:'.$color.';"></span>';
					} elseif($box_style == ' box-style-4') {
						$output .= '<div class="plan-header" style="background-color:'.$header_bg.'; color:'.$header_color.';">';
							$output .= $title . $meta;
							$output .= '<span class="plan-price-wrapper" style="background-color:'.$header_bg.'; color:'.$header_color.'; border-color:'.$bg_color.'; ">';
								$output .= $currency . $price . $time;
							$output .= '</span>';

						$output .= '</div>';
					}
					
					$output .= '<div class="plan-features">';
						$output .= wpb_js_remove_wpautop($content);
					$output .= '</div>'; //.plan-features
				$output .= '</div>'; //.pricing-box-container
			$output .= '</div>'; //.pricing-box
			
			if ( $a_href != '' ) {
				$output = '<a href="'.$a_href.'" title="'.esc_attr($a_title).'" target="'.$a_target.'">' . $output . '</a>';
			}

		return $output;
    }
}



/*---------------------------------------------------------------*/
/* Register shortcode within Visual Composer interface
/*---------------------------------------------------------------*/

$add_css_animation = array(
	'type' => 'dropdown',
	'heading' => __( 'CSS Animation', 'js_composer' ),
	'param_name' => 'css_animation',
	'admin_label' => true,
	'value' => array(
		__( 'No', 'js_composer' ) => '',
		__( 'Top to bottom', 'js_composer' ) => 'top-to-bottom',
		__( 'Bottom to top', 'js_composer' ) => 'bottom-to-top',
		__( 'Left to right', 'js_composer' ) => 'left-to-right',
		__( 'Right to left', 'js_composer' ) => 'right-to-left',
		__( 'Appear from center', 'js_composer' ) => "appear"
	),
	'description' => __( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'js_composer' )
);

$add_css_animation_delay = array(
	"type" => "dropdown",
	"heading" => __( 'CSS Animation Delay', 'js_composer' ),
	"param_name" => "css_animation_delay",
	"value" => array(
		'0ms' => '', 
		'100ms' => 'delay-100', 
		'200ms' => 'delay-200', 
		'300ms' => 'delay-300', 
		'400ms' => 'delay-400', 
		'500ms' => 'delay-500', 
		'600ms' => 'delay-600', 
		'700ms' => 'delay-700', 
		'800ms' => 'delay-800', 
		'900ms' => 'delay-900', 
		'1000ms' => 'delay-1000', 
		'1100ms' => 'delay-1100', 
		'1200ms' => 'delay-1200', 
		'1300ms' => 'delay-1300', 
		'1400ms' => 'delay-1400', 
		'1500ms' => 'delay-1500', 
		'1600ms' => 'delay-1600',
		'1700ms' => 'delay-1700',
		'1800ms' => 'delay-1800', 
		'1900ms' => 'delay-1900', 
		'2000ms' => 'delay-2000'
	),
	"dependency" => Array('element' => "css_animation", 'not_empty' => true)
);
	

vc_map( array(
	"name"		=> __( 'Pricing', 'js_composer' ),
	"base"		=> "mnky_pricing_box",
	"icon"		=> "icon-wpb-mnky_pricing_box",
	"allowed_container_element" => false,
	"is_container" => true,
	"category"  => __('Premium', 'js_composer'),
	"description" => __('Styled pricing boxes', 'js_composer'),
	"admin_enqueue_js" => array( VCPB_PLUGIN_URL . 'assets/pricing-box-views.js' ),
	"admin_enqueue_css" => array( VCPB_PLUGIN_URL . 'assets/pricing-box-backend.css'),
	"params" => array(
			array(
				"type" => "textfield",
				"heading" => __( 'Title', 'js_composer' ),
				"param_name" => "title",
				"holder" => "h4",
				"description" => __( 'Give your plan a title.', 'js_composer' ),
				"value" => __( 'Starter Pack', 'js_composer' ),
			),
			array(
				"type" => "textfield",
				"heading" => __( 'Currency', 'js_composer' ),
				"param_name" => "currency",
				"holder" => "span",
				"description" => __( 'Enter currency symbol or text, e.g., $ or USD.', 'js_composer' ),
				"value" => __( '$', 'js_composer' )
			),	
			array(
				"type" => "textfield",
				"heading" => __( 'Price', 'js_composer' ),
				"param_name" => "price",
				"holder" => "span",
				"description" => __( 'Set the price for this plan.', 'js_composer' ),
				"value" => __( '10', 'js_composer' )
			),						
			array(
				"type" => "textfield",
				"heading" => __( 'Time', 'js_composer' ),
				"param_name" => "time",
				"holder" => "span",
				"description" => __( 'Choose time span for you plan, e.g., /mo, month or /yr.', 'js_composer' ),
				"value" => __( '/mo', 'js_composer' )
			),				
			array(
				"type" => "textfield",
				"heading" => __( 'Meta', 'js_composer' ),
				"param_name" => "meta",
				"holder" => "span",
				"description" => __( 'A short desciption or slogan for the plan.', 'js_composer' ),
				"value" => __( 'Great for small business', 'js_composer' )
			),
			array(
				"type" => "vc_link",
				"heading" => __( 'Add URL to the whole box (optional)', 'js_composer' ),
				"param_name" => "link",
			),
			$add_css_animation,
			$add_css_animation_delay,
			array(
				"type" => "textfield",
				"heading" => __( 'Extra class name', 'js_composer' ),
				"param_name" => "el_class",
				"description" => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' )
			),
			array(
				"type" => "dropdown",
				"heading" => __( 'Select style', 'js_composer' ),
				"param_name" => "box_style",
				"value" => array('Minimal' => 'box-style-1', 'Strict' => 'box-style-2', 'Header' => 'box-style-3', 'Circle' => 'box-style-4'),
				"description" => __( 'Choose style for this pricing box.', 'js_composer' ),
				"group" => __('Design', 'js_composer')
			),	
			array(
				"type" => "colorpicker",
				"heading" => __( 'Background color', 'js_composer' ),
				"param_name" => "bg_color",
				"value" => "#FFFFFF",
				"description" => __( 'Set background color for pricing box body.', 'js_composer' ),
				"group" => __('Design', 'js_composer')
			),					
			array(
				"type" => "colorpicker",
				"heading" => __( 'Text color', 'js_composer' ),
				"param_name" => "color",
				"value" => "#5E5E5E",
				"description" => __( 'Set text color for pricing box content.', 'js_composer' ),
				"group" => __('Design', 'js_composer')
			),
			array(
				"type" => "colorpicker",
				"heading" => __( 'Header background color', 'js_composer' ),
				"param_name" => "header_bg",
				"value" => "#5E5E5E",
				"group" => __('Design', 'js_composer'),
				"description" =>  __( 'Set background color for box header.', 'js_composer' ),
				"dependency" => Array('element' => "box_style", 'value' => array('box-style-2', 'box-style-3', 'box-style-4') )
			),			
			array(
				"type" => "colorpicker",
				"heading" => __( 'Header text color', 'js_composer' ),
				"param_name" => "header_color",
				"value" => "#FFFFFF",
				"group" => __('Design', 'js_composer'),
				"description" => __( 'Set color for text inside box header area.', 'js_composer' ),
				"dependency" => Array('element' => "box_style", 'value' => array('box-style-2', 'box-style-3', 'box-style-4') )
			),
			array(
				"type" => "colorpicker",
				"heading" => __( 'Border color (optional)', 'js_composer' ),
				"param_name" => "border_color",
				"description" => __( 'Add border to whole box. Leave empty for no border.', 'js_composer' ),
				"group" => __('Design', 'js_composer')
			),			
			array(
				"type" => 'checkbox',
				"heading" => __( 'Add badge?', 'js_composer' ),
				"param_name" => "add_badge",
				"group" => __('Badge', 'js_composer'),
				"description" => "Adds a nice badge to your pricing box.",
				"value" => Array(__( 'Yes, please', 'js_composer' ) => 'on')
			),			
			array(
				"type" => "colorpicker",
				"heading" => __( 'Badge background color', 'js_composer' ),
				"param_name" => "badge_bg",
				"group" => __('Badge', 'js_composer'),
				"description" => __( 'Set a background color for the badge.', 'js_composer' ),
				"dependency" => Array('element' => "add_badge", 'not_empty' => true)
			),			
			array(
				"type" => "colorpicker",
				"heading" => __( 'Badge text color', 'js_composer' ),
				"param_name" => "badge_color",
				"group" => __('Badge', 'js_composer'),
				"value" => "#fff",
				"description" => __( 'Set a text color for the badge.', 'js_composer' ),
				"dependency" => Array('element' => "add_badge", 'not_empty' => true)
			),				
			array(
				"type" => "textfield",
				"heading" => __( 'Badge text', 'js_composer' ),
				"param_name" => "badge_text",
				"value" => __( 'Best Offer', 'js_composer' ),
				"group" => __('Badge', 'js_composer'),
				"description" => __( 'What do you want your badge to say? , E.g., 50% Off or Save 30%.', 'js_composer' ),
				"dependency" => Array('element' => "add_badge", 'not_empty' => true)
			),			
			array(
				"type" => "dropdown",
				"heading" => __( 'Hover effect', 'js_composer' ),
				"param_name" => "hover_effect",
				"value" => array('None' => '', 'Emphasize' => 'box-effect-1', 'Add Shadow' => 'box-effect-2', 'Emphasize + Add Shadow' => 'box-effect-3'),
				"description" => __( 'Enable and choose a hover effect.', 'js_composer' ),
				"group" => __('Effect', 'js_composer')
			),
			array(
				"type" => 'checkbox',
				"heading" => __( 'Always active? (by default only on hover state)', 'js_composer' ),
				"param_name" => "effect_active",
				"group" => __('Effect', 'js_composer'),
				"value" => Array(__( 'Yes, please', 'js_composer' ) => 'box-effect-active'),
				"description" => __( 'Use this option, if you want to accentuate one of the boxes.', 'js_composer' ),
			)			
		),
	"js_view" => 'VcPricingView'
) );	

?>