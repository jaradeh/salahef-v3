jQuery(document).ready(function ($) {

    if (typeof coupon_manager_js_script_data === 'undefined') {
        return false;
    }
    
    $("#frontend_coupon_manager_accordion").accordion({
        heightStyle: "content",
        collapsible: true,
        activate: function (event, ui) {
            if (ui.newHeader.hasClass('variations')) {
                resetVariationsAttributes();
            }
        }
    });

    $('.img_tip').each(function () {
        $(this).qtip({
            content: $(this).attr('data-desc'),
            position: {
                my: 'center left',
                at: 'center right',
                viewport: $(window)
            },
            show: {
                event: 'mouseover',
                solo: true,
            },
            hide: {
                inactive: 6000,
                fixed: true
            },
            style: {
                classes: 'qtip-dark qtip-shadow qtip-rounded qtip-dc-css'
            }
        });
    });

    $('#expiry_date').each(function () {
        $(this).datepicker({
            dateFormat: 'yy-mm-d',
            changeMonth: true,
            changeYear: true
        });
    });

    $("#product_ids").select2({
        placeholder: coupon_manager_js_script_data.messages.search_product
    });

    $("#exclude_product_ids").select2({
        placeholder: coupon_manager_js_script_data.messages.search_product
    });

    $("#product_categories").select2({
        placeholder: coupon_manager_js_script_data.messages.any_category
    });

    $("#exclude_product_categories").select2({
        placeholder: coupon_manager_js_script_data.messages.no_category
    });

    function coupon_manager_form_validate() {
        $is_valid = true;
        $('.woocommerce-error,.woocommerce-message').remove();
        var title = $.trim($('#coupon_manager_form').find('#title').val());
        if (title.length == 0) {
            $is_valid = false;
            $('#coupon_manager_form').prepend('<div class="woocommerce-error" tabindex="-1">' + coupon_manager_js_script_data.messages.no_title + '</div>');
            $('.woocommerce-error').focus();
        }
        return $is_valid;
    }

    // Draft Product
    $('#coupon_manager_draft_button').click(function (event) {
        event.preventDefault();

        // Validations
        $is_valid = coupon_manager_form_validate();

        if ($is_valid) {
            $('#coupon_manager_form').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
            var data = {
                action: 'frontend_coupon_manager',
                coupon_manager_form: $('#coupon_manager_form').serialize(),
                status: 'draft'
            }
            $.post(coupon_manager_js_script_data.ajax_url, data, function (response) {
                if (response) {
                    $response_json = $.parseJSON(response);
                    if ($response_json.status) {
                        window.location = $response_json.redirect;
                    } else {
                        $('.woocommerce-error,.woocommerce-message').remove();
                        $('#coupon_manager_form').prepend('<div class="woocommerce-error" tabindex="-1">' + $response_json.message + '</div>');
                        $('.woocommerce-error').focus();
                    }
                    if ($response_json.id)
                        $('#coupon_id').val($response_json.id);
                    $('#coupon_manager_form').unblock();
                }
            });
        }
    });

    // Submit Product
    $('#coupon_manager_submit_button').click(function (event) {
        event.preventDefault();

        // Validations
        $is_valid = coupon_manager_form_validate();

        if ($is_valid) {
            $('#coupon_manager_form').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
            var data = {
                action: 'frontend_coupon_manager',
                coupon_manager_form: $('#coupon_manager_form').serialize(),
                status: 'submit'
            }
            $.post(coupon_manager_js_script_data.ajax_url, data, function (response) {
                if (response) {
                    $response_json = $.parseJSON(response);
                    if ($response_json.status) {
                        window.location = $response_json.redirect;
                    } else {
                        $('.woocommerce-error,woocommerce-message').remove();
                        $('#coupon_manager_form').prepend('<div class="woocommerce-error" tabindex="-1">' + $response_json.message + '</div>');
                        $('.woocommerce-error').focus();
                    }
                    if ($response_json.id)
                        $('#coupon_id').val($response_json.id);
                    $('#coupon_manager_form').unblock();
                }
            });
        }
    });
});