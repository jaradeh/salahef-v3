<?php
/**
 * Checkout shipping information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="woocommerce-shipping-fields">
	<?php if ( WC()->cart->needs_shipping_address() === true ) : ?>

		<?php
			if ( empty( $_POST ) ) {

				$ship_to_different_address = get_option( 'woocommerce_ship_to_destination' ) === 'shipping' ? 1 : 0;
				$ship_to_different_address = apply_filters( 'woocommerce_ship_to_different_address_checked', $ship_to_different_address );

			} else {

				$ship_to_different_address = $checkout->get_value( 'ship_to_different_address' );

			}
		?>
		<div class="row">
			<div class="span3">

            	<?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) { ?>

                    <h3><?php esc_html_e( 'Additional Information', 'vitrine' ); ?></h3>
                
                <?php } else {?>

                    <h3><?php esc_html_e( 'Shipping Details', 'vitrine' ); ?></h3>
                
                <?php }; ?>

			</div>
			<div class="span8">
                <div id="ship-to-different-address" class="form-row">
                    <input id="ship-to-different-address-checkbox" class="input-checkbox" <?php checked( $ship_to_different_address, 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" />
					<label for="ship-to-different-address-checkbox" class="checkbox"><?php esc_html_e( 'Ship to a different address?', 'vitrine' ); ?></label>
				</div>

				<div class="shipping_address">

					<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

					<?php 
					$fields = $checkout->get_checkout_fields( 'shipping' );

					foreach ( $fields as $key => $field ) {
						if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
							$field['country'] = $checkout->get_value( $field['country_field'] );
						}
						woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
					}

					?>

					<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

	<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) ) : ?>
		<div class="row">
			<div class="span3">
			<?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

				<h3><?php esc_html_e( 'Additional Information', 'vitrine' ); ?></h3>

			<?php endif; ?>
			</div>
			<div class="span8">
				<?php foreach ( $checkout->checkout_fields['order'] as $key => $field ) : ?>

					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>
