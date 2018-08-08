<?php
/**
 * Woocommerce Compare page
 *
 * @author Your Inspiration Themes
 * @package YITH Woocommerce Compare
 * @version 1.1.4
 */

/* Epico code */
global $product;
/* End of Epico code */

// remove the style of woocommerce
if( defined('WOOCOMMERCE_USE_CSS') && WOOCOMMERCE_USE_CSS ) wp_dequeue_style('woocommerce_frontend_styles');

$widths = array();
foreach( $products as $product ) $widths[] = '{ "sWidth": "205px", resizeable:true }';

$table_text = get_option( 'yith_woocompare_table_text' );
yit_wpml_register_string( 'Plugins', 'plugin_yit_compare_table_text', $table_text );
$localized_table_text = yit_wpml_string_translate( 'Plugins', 'plugin_yit_compare_table_text', $table_text );

?>
<h1>
    <?php echo esc_html($localized_table_text); ?>
</h1>

<?php do_action( 'yith_woocompare_before_main_table' ); ?>

<table class="compare-list" cellpadding="0" cellspacing="0"<?php if ( empty( $products ) ) echo ' style="width:100%"' ?>>
    <thead>
    <tr>
        <th>&nbsp;</th>
        <?php foreach( $products as $i => $product ) : ?>
            <td></td>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>&nbsp;</th>
        <?php foreach( $products as $i => $product ) : ?>
            <td></td>
        <?php endforeach; ?>
    </tr>
    </tfoot>
    <tbody>

    <?php if ( empty( $products ) ) : ?>

        <tr class="no-products">
            <td><?php esc_html_e( 'No products added in the compare table.', 'vitrine' ) ?></td>
        </tr>

    <?php else : ?>
        <tr class="remove">
            <th>&nbsp;</th>
            <?php foreach( $products as $i => $product ) : $product_class = ( $i % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product->get_id() ?>
                <td class="<?php echo esc_attr($product_class); ?>">
                    <a href="<?php echo esc_url(add_query_arg( 'redirect', 'view', $this->remove_product_url( $product->get_id() ) )) ?>" data-product_id="<?php echo esc_attr($product->get_id()); ?>"><?php esc_html_e( 'Remove', 'vitrine' ) ?> <span class="remove">x</span></a>
                </td>
            <?php endforeach ?>
        </tr>

        <?php foreach ( $fields as $field => $name ) : ?>

            <tr class="<?php echo esc_attr($field); ?>">

                <th>
                    <?php echo esc_html($name); ?>
                    <?php if ( $field == 'image' ) echo '<div class="fixed-th"></div>'; ?>
                </th>

                <?php foreach( $products as $i => $product ) : $product_class = ( $i % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product->get_id(); ?>
                    <td class="<?php echo esc_attr($product_class); ?>"><?php
                        switch( $field ) {

                            case 'image':
                                echo '<div class="image-wrap">' . wp_get_attachment_image( $product->fields[$field], 'yith-woocompare-image' ) . '</div>';
                                break;

                            case 'add-to-cart':
                                woocommerce_template_loop_add_to_cart();
                                break;

                            default:
                                echo empty( $product->fields[$field] ) ? '&nbsp;' : $product->fields[$field];
                                break;
                        }
                        ?>
                    </td>
                <?php endforeach ?>

            </tr>

        <?php endforeach; ?>

        <?php if ( $repeat_price == 'yes' && isset( $fields['price'] ) ) : ?>
            <tr class="price repeated">
                <th><?php echo $fields['price'] ?></th>

                <?php foreach( $products as $i => $product ) : $product_class = ( $i % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product->get_id() ?>
                    <td class="<?php echo esc_attr($product_class) ?>"><?php echo $product->fields['price'] ?></td>
                <?php endforeach; ?>

            </tr>
        <?php endif; ?>

        <?php if ( $repeat_add_to_cart == 'yes' && isset( $fields['add-to-cart'] ) ) : ?>
            <tr class="add-to-cart repeated">
                <th><?php echo $fields['add-to-cart'] ?></th>

                <?php foreach( $products as $i => $product ) : $product_class = ( $i % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product->get_id() ?>
                    <td class="<?php echo esc_attr($product_class) ?>"><?php woocommerce_template_loop_add_to_cart(); ?></td>
                <?php endforeach; ?>

            </tr>
        <?php endif; ?>

    <?php endif; ?>

    </tbody>
</table>

<?php do_action( 'yith_woocompare_after_main_table' ); ?>
