<?php
/*
 * The template for displaying vendor dashboard
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/dashboard.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version   3.0.0
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly
    exit;
}
?>
<div class="col-md-12">
    <?php do_action('wcmp_dashboard_widget', 'full'); ?>
</div>

<div class="col-md-8">
    <?php do_action('wcmp_dashboard_widget', 'normal'); ?>
</div>

<div class="col-md-4">
    <?php do_action('wcmp_dashboard_widget', 'side'); ?>
</div>