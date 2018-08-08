<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) { ?>

<div class="u-columns col2-set" id="customer_login">

	<div class="u-column1 col-1">

<?php } else { ?>

	<div class="u-column1 col-1 without_registration" id="customer_login">
<?php } ?>


		<h2><?php esc_html_e( 'Login', 'vitrine' ); ?></h2>

		<form method="post" class="login">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" placeholder="<?php esc_attr_e( 'Username', 'vitrine' ); ?>*"  value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
			</p>
			<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password"  autocomplete="current-password" placeholder="<?php esc_attr_e( 'Password', 'vitrine' ); ?> *"/>
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<p class="form-row form-row-wide">
				<?php wp_nonce_field( 'woocommerce-login' ); ?>
                <input type="submit" class="woocommerce-Button button" name="login" value="<?php esc_attr_e( 'Login', 'vitrine' ); ?>" />
				<label for="rememberme" class="inline rememberme">
					<input class="woocommerce-Input woocommerce-Input--checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php esc_html_e( 'Remember me', 'vitrine' ); ?>
				</label>
				<span class="lost_password">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'vitrine' ); ?></a>
				</span>
			</p>


			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) { ?>

	</div>

	<div class="u-column2 col-2">

		<h2><?php esc_html_e( 'Register', 'vitrine' ); ?></h2>

        <form method="post" class="woocommerce-form woocommerce-form-register register">

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username"  placeholder="<?php esc_attr_e( 'Username', 'vitrine' ); ?> *" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
				</p>

			<?php endif; ?>

			<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
				<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email"  autocomplete="email"  placeholder="<?php esc_attr_e( 'Email address', 'vitrine' ); ?> *" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password"  placeholder="<?php esc_attr_e( 'Password', 'vitrine' ); ?> *" />
				</p>

			<?php endif; ?>

			<?php do_action( 'woocommerce_register_form' ); ?>
			<?php do_action( 'register_form' ); ?>

			<p class="woocommerce-FormRow form-row form-row-wide">
				<?php wp_nonce_field( 'woocommerce-register' ); ?>
                <input type="submit" class="woocommerce-Button button" name="register" value="<?php esc_attr_e( 'Register', 'vitrine' ); ?>" />
			</p>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>

	</div>

</div>
<?php } else { ?>
</div>
<?php } ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
