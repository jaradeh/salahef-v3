<?php
/**
 * The template for displaying rejected vendor dashboard
 *
 * Override this template by copying it to yourtheme/dc-product-vendor/shortcode/rejected_vendor_dashboard.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version     3.1.0
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly
    exit;
}
global $WCMp;

echo '<div class="rejected-vendor-dashboard">';
do_action('before_wcmp_rejected_vendor_dashboard');

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
        	<?php do_action('wcmp_rejected_vendor_dashboard_content'); ?>
        </div>
    </div>
</div>

<?php
$WCMp->template->get_template('vendor-dashboard/dashboard-footer.php');

do_action('after_wcmp_rejected_vendor_dashboard');
echo '</div>';
