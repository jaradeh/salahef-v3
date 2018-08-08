<?php
/*
 * The template for displaying vendor products
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/product-manager/products.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version   3.0.0
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly
    exit;
}
global $WCMp;
$vendor = get_wcmp_vendor(get_current_vendor_id());
do_action('before_wcmp_vendor_dashboard_product_list_table');
?>
<div class="col-md-12 all-products-wrapper">
    <div class="panel panel-default panel-pading">
        <div class="product_filters">
            <?php
            $statuses = apply_filters('wcmp_vendor_dashboard_product_list_filters_status', array(
                'all' => __('All', 'dc-woocommerce-multi-vendor'),
                'publish' => __('Published', 'dc-woocommerce-multi-vendor'),
                'pending' => __('Pending', 'dc-woocommerce-multi-vendor'),
                'draft' => __('Draft', 'dc-woocommerce-multi-vendor')
            ));
            $current_status = isset($_GET['post_status']) ? $_GET['post_status'] : 'all';
            echo '<ul class="subsubsub by_status nav nav-pills">';
            //$array_keys = array_keys($statuses);
            foreach ($statuses as $key => $label) {
                if($key == 'all'){
                    $count_pros = count($vendor->get_products(array('post_status'=>array('publish', 'pending','draft'))));
                }else{
                    $count_pros = count($vendor->get_products(array('post_status'=>$key)));
                }
                if($count_pros){
                    echo '<li><a href="' . add_query_arg(array('post_status' => sanitize_title($key)), wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_products_endpoint', 'vendor', 'general', 'products'))) . '" class="' . ( $current_status == $key ? 'current' : '' ) . '">' . $label .' ( '. $count_pros .' ) </a></li>';
                }
            }
            echo '</ul><br class="clear" />';
            ?>
        </div>
        <table id="product_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <?php
            $tbl_header_footer = '';
            if ($products_table_headers) {
                foreach ($products_table_headers as $key => $value) {
                    $tbl_header_footer .= '<th data-lable="' . $key . '">' . $value . '</th>';
                }
            }
            ?>
            <thead><tr><?php echo $tbl_header_footer; ?></tr></thead>
            <tfoot><tr><?php echo $tbl_header_footer; ?></tr></tfoot>
        </table>
        <div class="wcmp-action-container">
            <a href="<?php echo wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product'));?>" class="btn btn-default"><?php echo __('Add Product', 'dc-woocommerce-multi-vendor');?></a>
        </div>
    </div>
</div>
<?php do_action('after_wcmp_vendor_dashboard_product_list_table'); ?>
<script>
    jQuery(document).ready(function ($) {
        var product_table;
        var columns = [];
        var filter_by_category_list = [];
<?php
if ($products_table_headers) {
    $enable_ordering = apply_filters('wcmp_vendor_dashboard_product_list_table_orderable_columns', array('name', 'date'));
    foreach ($products_table_headers as $key => $value) {
        $orderable = 'false';
        if (in_array($key, $enable_ordering)) {
            $orderable = 'true';
        }
        ?>
                obj = {};
                obj['data'] = '<?php echo $key; ?>';
                obj['className'] = '<?php echo $key; ?>';
                obj['orderable'] = <?php echo $orderable; ?>;
                columns.push(obj);
    <?php
    }
}
$args = array('orderby' => 'name', 'order' => 'ASC');
$terms = get_terms('product_cat', $args);
foreach ($terms as $term) {
    ?>
            obj = {};
            obj['key'] = '<?php echo $term->term_id; ?>';
            obj['label'] = '<?php echo addslashes($term->name); ?>';
            filter_by_category_list.push(obj);
<?php }
?>
        product_table = $('#product_table').DataTable({
            'ordering': <?php echo isset($table_init['ordering']) ? trim($table_init['ordering']) : 'true'; ?>,
            'searching': <?php echo isset($table_init['searching']) ? trim($table_init['searching']) : 'true'; ?>,
            "processing": true,
            "serverSide": true,
            responsive: true,
            "language": {
                "emptyTable": "<?php echo isset($table_init['emptyTable']) ? trim($table_init['emptyTable']) : __('No products found!', 'dc-woocommerce-multi-vendor'); ?>",
                "processing": "<?php echo isset($table_init['processing']) ? trim($table_init['processing']) : __('Processing...', 'dc-woocommerce-multi-vendor'); ?>",
                "info": "<?php echo isset($table_init['info']) ? trim($table_init['info']) : __('Showing _START_ to _END_ of _TOTAL_ products', 'dc-woocommerce-multi-vendor'); ?>",
                "infoEmpty": "<?php echo isset($table_init['infoEmpty']) ? trim($table_init['infoEmpty']) : __('Showing 0 to 0 of 0 products', 'dc-woocommerce-multi-vendor'); ?>",
                "lengthMenu": "<?php echo isset($table_init['lengthMenu']) ? trim($table_init['lengthMenu']) : __('Number of rows _MENU_', 'dc-woocommerce-multi-vendor'); ?>",
                "zeroRecords": "<?php echo isset($table_init['zeroRecords']) ? trim($table_init['zeroRecords']) : __('No matching products found', 'dc-woocommerce-multi-vendor'); ?>",
                "search": "<?php echo isset($table_init['search']) ? trim($table_init['search']) : __('Search:', 'dc-woocommerce-multi-vendor'); ?>",
                "paginate": {
                    "next": "<?php echo isset($table_init['next']) ? trim($table_init['next']) : __('Next', 'dc-woocommerce-multi-vendor'); ?>",
                    "previous": "<?php echo isset($table_init['previous']) ? trim($table_init['previous']) : __('Previous', 'dc-woocommerce-multi-vendor'); ?>"
                },
            },
            "drawCallback": function(settings){
                $( "#product_cat" ).detach();
                $('thead tr th.image').removeClass('sorting_asc');
                var product_cat_sel = $('<select id="product_cat" class="wcmp-filter-dtdd wcmp_filter_product_cat form-control">').appendTo("#product_table_length");
                product_cat_sel.append($("<option>").attr('value', '').text('<?php echo trim(__('Select a Category', 'dc-woocommerce-multi-vendor')); ?>'));
                $(filter_by_category_list).each(function () {
                    product_cat_sel.append($("<option>").attr('value', this.key).text(this.label));
                });
                if(settings.oAjaxData.product_cat){
                    product_cat_sel.val(settings.oAjaxData.product_cat);
                }
            },
            "ajax": {
                url: '<?php echo add_query_arg( 'action', 'wcmp_vendor_product_list', $WCMp->ajax_url() ); ?>',
                type: "post",
                data: function (data) {
                    data.post_status = "<?php echo isset($_GET['post_status']) ? trim($_GET['post_status']) : 'all' ?>";
                    data.product_cat = $('#product_cat').val();
                },
                error: function(xhr, status, error) {
                    $("#product_table tbody").append('<tr class="odd"><td valign="top" colspan="<?php echo count($products_table_headers); ?>" class="dataTables_empty" style="text-align:center;">'+error+' - <a href="javascript:window.location.reload();"><?php _e('Reload', 'dc-woocommerce-multi-vendor'); ?></a></td></tr>');
                    $("#product_table_processing").css("display","none");
                }
            },
            "columns": columns,
            "createdRow": function (row, data, index) {
                $(row).addClass('vendor-product');
            }
        });
        new $.fn.dataTable.FixedHeader( product_table );
        $(document).on('change', '#product_cat', function () {
            product_table.ajax.reload();
        });
    });
</script>