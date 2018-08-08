<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $css_animation
 * @var $css
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Column_text
 */

$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$textWithAnimation = '';

if($text_animation != 'none' && $text_animation != '')
{
	$textWithAnimation = ' shortcodeAnimation';
	
    if($responsive_animation != '')
    {
        $textWithAnimation .= ' no-responsive-animation';
    }

}
        

$el_class = $this->getExtraClass( $el_class );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_text_column wpb_content_element ' . esc_attr($textWithAnimation) . esc_attr($el_class) . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
$output .= "\n\t" . '<div class="' . esc_attr( $css_class ) . '" data-delay="' . esc_attr($text_animation_delay) . '" data-animation="' . esc_attr($text_animation) . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
$output .= "\n\t\t\t" . wpb_js_remove_wpautop( $content, true );
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( $this->getShortcode() );

echo $output;