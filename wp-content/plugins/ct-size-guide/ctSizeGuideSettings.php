<?php

/**
 * Adds the woocommerce settings tab
 * @author jacek
 */
class ctSizeGuideSettings {

	/**
	 * Inits object
	 */

	public function __construct() {
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'addSettingsTab' ), 90 );
		add_action( 'woocommerce_settings_tabs_size_guide_tab', array( $this, 'settingsTab' ) );
		add_action( 'woocommerce_update_options_size_guide_tab', array( $this, 'updateSettings' ) );
	}

	/**
	 * Adds tab
	 *
	 * @param $settings_tabs
	 *
	 * @return mixed
	 */

	public function addSettingsTab( $settings_tabs ) {
		$settings_tabs['size_guide_tab'] = __( 'Size guide', 'ct-sgp' );

		return $settings_tabs;
	}

	/**
	 * Adds fields
	 */

	function settingsTab() {
		woocommerce_admin_fields( $this->getSettings() );
	}

	function getSettings() {
		$settings = array(
			'section_title' => array(
				'name' => __( 'Size guide settings', 'ct-sgp' ),
				'type' => 'title',
				'id'   => 'wc_size_guide_tab_section_title'
			),
			array(
				'name'    => __( 'Style', 'ct-sgp' ),
				'desc'    => __( 'Chose the style of popup window (checkout previews in the plugin <a href="http://createit.support/documentation/size-guide/#doc-7007" target="_blank">documentation</a>).', 'ct-sgp' ),
				'id'      => 'wc_size_guide_style',
				'type'    => 'select',
				'class'   => 'chosen_select',
				'css'     => 'min-width:300px;',
				'std'     => CT_SIZEGUIDE_ASSETS . 'css/ct.sizeguide.style1.css',
				'default' => CT_SIZEGUIDE_ASSETS . 'css/ct.sizeguide.style1.css',
				'options' => apply_filters( 'ct_sizeguide_styles', array(
					CT_SIZEGUIDE_ASSETS . 'css/ct.sizeguide.style1.css' => __( 'Minimalistic', 'ct-sgp' ),
					CT_SIZEGUIDE_ASSETS . 'css/ct.sizeguide.style2.css' => __( 'Classic', 'ct-sgp' ),
					CT_SIZEGUIDE_ASSETS . 'css/ct.sizeguide.style3.css' => __( 'Modern', 'ct-sgp' )
				) ),
			),
			array(
				'name'    => __( 'Open guide with:', 'ct-sgp' ),
				'desc'    => __( 'Chose whether to display a simple link or a button to open the size guide.', 'ct-sgp' ),
				'id'      => 'wc_size_guide_button_style',
				'type'    => 'select',
				'class'   => 'chosen_select',
				'css'     => 'min-width:300px;',
				'std'     => 'ct-trigger-button',
				'default' => 'ct-trigger-button',
				'options' => array(
					'ct-trigger-link'   => __( 'Link', 'ct-sgp' ),
					'ct-trigger-button' => __( 'Button', 'ct-sgp' ),
				),
			),
			array(
				'name'    => __( 'Hide Size Guide', 'ct-sgp' ),
				'desc'    => __( 'Check this field to hide size guide when the product is out of stock', 'ct-sgp' ),
				'id'      => 'wc_size_guide_hide',
				'type'    => 'checkbox',
				//'class' => 'chosen_select', // #429: Custom theme button issue
				'css'     => 'min-width:300px;',
				'std'     => 'no',
				'default' => 'no'
			),
			array(
				'name'    => __( 'Button/link position:', 'ct-sgp' ),
				'desc'    => __( 'For manual embed, [ct_size_guide] shortcode can be placed anywhere you want. More info can be found <a href="http://createit.support/documentation/size-guide/#doc-7007" target="_blank">here</a>.', 'ct-sgp' ),
				'id'      => 'wc_size_guide_button_position',
				'type'    => 'select',
				'class'   => 'chosen_select',
				'css'     => 'min-width:300px;',
				'std'     => 'ct-position-summary',
				'default' => 'ct-position-summary',
				'options' => array(
					'ct-position-price'       => __( 'Under Price', 'ct-sgp' ),
					'ct-position-summary'     => __( 'Above the product summary tabs', 'ct-sgp' ),
					'ct-position-add-to-cart' => __( 'After Add To Cart button', 'ct-sgp' ),
					'ct-position-info'        => __( 'After Product Info', 'ct-sgp' ),
					'ct-position-tab'         => __( 'Make it a tab', 'ct-sgp' ),
					'ct-position-shortcode'   => __( 'Embed manually (shortcode)', 'ct-sgp' ),
				),
			),
			array(
				'name'        => __( 'Button/link hook priority', 'ct-sgp' ),
				'desc'        => __( 'Priority of the action that outputs the button/link. Using this you can adjust the position - check the <a href="http://createit.support/documentation/size-guide/#button-priority">documentation</a> for more information.', 'ct-sgp' ),
				'id'          => 'wc_size_guide_button_priority',
				'css'         => 'max-width:60px;',
				'type'        => 'number',
				'default'     => 60,
				'std'         => 60,
				'placeholder' => 60,
			),
			array(
				'name'    => __( 'Button/link label', 'ct-sgp' ),
				'id'      => 'wc_size_guide_button_label',
				'type'    => 'text',
				'default' => 'Size Guide',
				'std'     => 'Size Guide',
				//'placeholder' => 'Size Guide'
			),
			array(
				'name'    => __( 'Button/link align', 'ct-sgp' ),
				'id'      => 'wc_size_guide_button_align',
				'type'    => 'select',
				'class'   => 'chosen_select',
				'css'     => 'min-width:300px;',
				'std'     => 'ct-align-left',
				'default' => 'ct-align-left',
				'options' => array(
					'left'  => __( 'Left', 'ct-sgp' ),
					'right' => __( 'Right', 'ct-sgp' ),
				),
			),
			array(
				'name'    => __( 'Button/link clearing', 'ct-sgp' ),
				'desc'    => __( 'Allow floating elements on the sides of the link/button?', 'ct-sgp' ),
				'id'      => 'wc_size_guide_button_clear',
				'type'    => 'checkbox',
				'class'   => 'chosen_select',
				'css'     => 'min-width:300px;',
				'std'     => 'no',
				'default' => 'no'
			),
			array(
				'name'    => __( 'Button class', 'ct-sgp' ),
				'desc'    => __( 'Add a custom class to the button. If You want to use default class, type: button_sg', 'ct-sgp' ),
				'id'      => 'wc_size_guide_button_class',
				'type'    => 'text',
				'default' => 'button_sg',
				'std'     => 'button_sg',
				//'placeholder' => 'button_sg'
			),
			array(
				'name'        => __( 'Margin left', 'ct-sgp' ),
				'desc'        => __( 'Enter the left margin of the link/button', 'ct-sgp' ),
				'id'          => 'wc_size_guide_button_margin_left',
				'css'         => 'max-width:60px;',
				'type'        => 'number',
				'default'     => 0,
				'placeholder' => 0,
			),
			array(
				'name'        => __( 'Margin top', 'ct-sgp' ),
				'desc'        => __( 'Enter the top margin of the link/button', 'ct-sgp' ),
				'id'          => 'wc_size_guide_button_margin_top',
				'css'         => 'max-width:60px;',
				'type'        => 'number',
				'default'     => 0,
				'placeholder' => 0,
			),
			array(
				'name'        => __( 'Margin right', 'ct-sgp' ),
				'desc'        => __( 'Enter the right margin of the link/button', 'ct-sgp' ),
				'id'          => 'wc_size_guide_button_margin_right',
				'css'         => 'max-width:60px;',
				'type'        => 'number',
				'default'     => 0,
				'placeholder' => 0,
			),
			array(
				'name'        => __( 'Margin bottom', 'ct-sgp' ),
				'desc'        => __( 'Enter the bottom margin of the link/button', 'ct-sgp' ),
				'id'          => 'wc_size_guide_button_margin_bottom',
				'css'         => 'max-width:60px;',
				'type'        => 'number',
				'default'     => 0,
				'placeholder' => 0,
			),
			array(
				'name'    => __( 'Popup overlay color', 'ct-sgp' ),
				'desc'    => __( 'Click to pick the color of the popup background overlay', 'ct-sgp' ),
				'id'      => 'wc_size_guide_overlay_color',
				'css'     => 'max-width:70px;',
				'type'    => 'color',
				'default' => '000000',
			),
			array(
				'name'        => __( 'Padding left', 'ct-sgp' ),
				'desc'        => __( 'Enter the left padding of the content in the popup window', 'ct-sgp' ),
				'id'          => 'wc_size_guide_modal_padding_left',
				'css'         => 'max-width:60px;',
				'type'        => 'number',
				'default'     => 0,
				'placeholder' => 0,
			),
			array(
				'name'        => __( 'Padding top', 'ct-sgp' ),
				'desc'        => __( 'Enter the top padding of the content in the popup window', 'ct-sgp' ),
				'id'          => 'wc_size_guide_modal_padding_top',
				'css'         => 'max-width:60px;',
				'type'        => 'number',
				'default'     => 0,
				'placeholder' => 0,
			),
			array(
				'name'        => __( 'Padding right', 'ct-sgp' ),
				'desc'        => __( 'Enter the right padding of the content in the popup window', 'ct-sgp' ),
				'id'          => 'wc_size_guide_modal_padding_right',
				'css'         => 'max-width:60px;',
				'type'        => 'number',
				'default'     => 0,
				'placeholder' => 0,
			),
			array(
				'name'        => __( 'Padding bottom', 'ct-sgp' ),
				'desc'        => __( 'Enter the bottom padding of the content in the popup window', 'ct-sgp' ),
				'id'          => 'wc_size_guide_modal_padding_bottom',
				'css'         => 'max-width:60px;',
				'type'        => 'number',
				'default'     => 0,
				'placeholder' => 0,
			),

			array(
				'name'    => __( 'Table hover', 'ct-sgp' ),
				'desc'    => __( 'Do you want to use hover effect on tables?', 'ct-sgp' ),
				'id'      => 'wc_size_guide_hovers_on_tables',
				'type'    => 'checkbox',
				'default' => true,
			),

			array(
				'name' => __( 'Background for hovers line', 'ct-sgp' ),
				'desc' => __( 'Set background color for hover lines', 'ct-sgp' ),
				'id'   => 'wc_size_guide_lines_hover_color',
				'css'     => 'max-width:70px;',
				'type' => 'color',
				'default' => '#999999',
			),

			array(
				'name' => __( 'Background for active cell on hover', 'ct-sgp' ),
				'desc' => __( 'Set background color for hover lines', 'ct-sgp' ),
				'id'   => 'wc_size_guide_active_hover_color',
				'css'     => 'max-width:70px;',
				'type' => 'color',
				'default' => '#2C72AD',
			),

			array(
				'name'    => __( 'Responsible tables', 'ct-sgp' ),
				'desc'    => __( 'This option disables responsive view of tables', 'ct-sgp' ),
				'id'      => 'wc_size_guide_display_mobile_table',
				'css'     => 'max-width:200px',
				'type'    => 'select',
				'options' => array(
					'ct-size-guide--Responsive'    => __( 'Responsive', 'ct-sgpt' ),
					'ct-size-guide--NonResponsive' => __( 'Non responsive', 'ct-sgpt' ),
				),
			),
			'section_end'   => array(
				'type' => 'sectionend',
				'id'   => 'wc_size_guide_tab_section_end'
			)
		);

		return apply_filters( 'wc_size_guide_tab_settings', $settings );
	}

	function wcSizeGuidePopupOverlayColorGlobal() {
		$settings                     = $this->getSettings();
		$wcSizeGuidePopupOverlayColor = get_option( $settings[13]['id'] ) != null ? get_option( $settings[13]['id'] ) : '#000000';

		return $wcSizeGuidePopupOverlayColor;
	}

	function updateSettings() {
		woocommerce_update_options( $this->getSettings() );
	}
}

new ctSizeGuideSettings;