<?php
/**
 * The template for displaying pending vendor dashboard
 *
 * Override this template by copying it to yourtheme/dc-product-vendor/shortcode/pending_vendor_dashboard.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version     3.1.0
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly
    exit;
}
global $WCMp, $wp;

echo '<div class="pending-vendor-dashboard">';
do_action('before_wcmp_pending_vendor_dashboard');

$WCMp->template->get_template('vendor-dashboard/dashboard-header.php');

do_action('wcmp_vendor_dashboard_navigation', array());

$is_single = !is_null($WCMp->endpoints->get_current_endpoint_var()) ? '-single' : '';
?>

<div id="page-wrapper" class="side-collapse-container">
    <div id="current-endpoint-title-wrapper" class="current-endpoint-title-wrapper">
        <div class="current-endpoint">
            <?php echo $WCMp->vendor_hooks->wcmp_create_vendor_dashboard_breadcrumbs($WCMp->endpoints->get_current_endpoint()); ?>
        </div>
    </div>
    <!-- /.row -->
    <div class="content-padding gray-bkg <?php echo $WCMp->endpoints->get_current_endpoint() ? $WCMp->endpoints->get_current_endpoint().$is_single : 'dashboard'; ?>">
        <div class="notice-wrapper">
            <?php wc_print_notices(); ?>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
				<div class="panel wcmp-pending-vendor-notice">
                    <?php echo apply_filters( 'wcmp_pending_vendor_dashboard_message', __('Congratulations! You have successfully applied as a Vendor. Please wait for further notifications from the admin.', 'dc-woocommerce-multi-vendor') ); ?>
                </div>
			</div>
        </div>
    </div>
</div>

<?php
$WCMp->template->get_template('vendor-dashboard/dashboard-footer.php');

do_action('after_wcmp_pending_vendor_dashboard');
echo '</div>';
