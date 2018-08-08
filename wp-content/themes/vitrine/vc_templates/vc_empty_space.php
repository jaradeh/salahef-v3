<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $height
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Empty_space
 */
$id = epico_sc_id('vc_empty_space');
$height = $el_class = $el_id = $css = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$output =  $el_class = $el_id = $css = '';
$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';

// allowed metrics: http://www.w3schools.com/cssref/css_units.asp
$regexr = preg_match( $pattern, $responsive_height, $matches);
$responsiveValue= isset( $matches[1] ) ? (float) $matches[1] : (float) WPBMap::getParam( 'vc_empty_space', 'responsive_height' );

$regexr1 = preg_match( $pattern, $height, $matches);
$value1 = isset( $matches[1] ) ? (float) $matches[1] : (float) WPBMap::getParam( 'vc_empty_space', 'height' );

$unit = isset( $matches[2] ) ? $matches[2] : 'px';
$height = $value1 . $unit;
$responsive_height = $responsiveValue . $unit;


$inline_css = ( (float) $height >= 0.0 ) ? ' style="height: ' . esc_attr( $height ) . '"' : '';
$class = 'vc_empty_space ' . $this->getExtraClass( $el_class ) . vc_shortcode_custom_css_class( $css, ' ' );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class, $this->settings['base'], $atts );
$wrapper_attributes = array();
if ( ! empty( $el_id ) ) 
    {
	    $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
    }

if($responsiveValue)
	
	{
		
		
		$output .='<style media="screen and (max-width:1140px)" type="text/css">';
		$output .= '#'. $id  .'{height: ' .esc_html( $responsive_height ). '!important;'.'}';
		$output .= '</style>';
		echo $output;
    }
?>
<div id="<?php echo $id; ?>" class="<?php echo esc_attr( trim( $css_class ) ); ?>" <?php echo implode( ' ', $wrapper_attributes ); ?> <?php echo $inline_css; ?> ><span class="vc_empty_space_inner"></span></div> 








 