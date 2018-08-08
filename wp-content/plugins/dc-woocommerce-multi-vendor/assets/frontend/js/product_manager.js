/* global product_manager_js_script_data */

var removed_person_types = [];
var removed_variations = [];
var resetMultiInputIndex;
var addMultiInputProperty;
jQuery(document).ready(function ($) {
    var block = function ($node) {
        if (!is_blocked($node)) {
            $node.addClass('processing').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
        }
    };
    var is_blocked = function ($node) {
        return $node.is('.processing') || $node.parents('.processing').length;
    };

    var unblock = function ($node) {
        $node.removeClass('processing').unblock();
    };
    if ($.isFunction($.fn.singleProductMulipleVendor)) {
        $("#product_manager_form input[name=title]").singleProductMulipleVendor({
            ajaxurl: product_manager_js_script_data.ajax_url,
            is_admin: false
        });
        $('body').on('click', '#wcmp_auto_suggest_product_title ul li a', function () {
            block($('#product_manager_form'));
            var product_id = $(this).data('product_id');
            var data = {
                action: 'wcmp_create_duplicate_product',
                product_id: product_id
            };
            $.post(product_manager_js_script_data.ajax_url, data, function (response) {
                unblock($('#product_manager_form'));
                //console.log(response);
                if (response.status) {
                    window.location.href = response.redirect_url;
                }
            });
        });
    }

    $("#frontend_product_manager_accordion").accordion({
        heightStyle: "content",
        collapsible: true,
        activate: function (event, ui) {
            if (ui.newHeader.hasClass('variations')) {
                resetVariationsAttributes();
            }
        }
    });

    $("#product_cats").select2({
        placeholder: product_manager_js_script_data.messages.choose
    });
    
    $("#wcmp-product-tags").select2({
        tags: product_manager_js_script_data.add_tags,
        tokenSeparators: [','],
        placeholder: product_manager_js_script_data.messages.choose,
    }).on("change", function(e) {
        var isNew = $(this).find('[data-select2-tag="true"]');
        if(isNew.length){
            var data = {
                action: 'wcmp_product_tag_add',
                new_tag: isNew.val()
            };
            $.ajax({
                type: 'POST',
                url: product_manager_js_script_data.ajax_url,
                data: data,
                success: function (response) {
                    if(response.status){ console.log(response);
                        isNew.replaceWith('<option selected value="'+isNew.val()+'">'+isNew.val()+'</option>'); 
                    }else{
                        if(response.message != ''){
                            $('.woocommerce-error,woocommerce-message').remove();
                            $('#product_manager_form').prepend('<div class="woocommerce-error" tabindex="-1">' + response.message + '</div>');
                            $('.woocommerce-error').focus();
                        }
                        $('#wcmp-product-tags option[value="'+isNew.val()+'"]').remove();
                    }
                }
            });
        }
    });

    $(".product_taxonomies").select2({
        placeholder: product_manager_js_script_data.messages.choose
    });

    $("#upsell_ids").select2({
        placeholder: product_manager_js_script_data.messages.choose
    });

    $("#crosssell_ids").select2({
        placeholder: product_manager_js_script_data.messages.choose
    });

    /*if( $("#children").length > 0 ) {
     $children = $("#children").select2({
     placeholder: "Choose ..."
     });
     //$children.data('select2').$container.addClass("pro_ele grouped");
     }*/

    /*function addVariationManageStockProperty() {
     $('.variation_manage_stock_ele').each(function() {
     $(this).off('change').on('change', function() {
     if($(this).is(':checked')) {
     $(this).parent().find('.variation_non_manage_stock_ele').removeClass('non_stock_ele_hide');
     } else {
     $(this).parent().find('.variation_non_manage_stock_ele').addClass('non_stock_ele_hide');
     }
     }).change();
     });
     
     $('.variation_is_virtual_ele').each(function() {
     $(this).off('change').on('change', function() {
     if($(this).is(':checked')) {
     $(this).parent().find('.variation_non_virtual_ele').addClass('non_virtual_ele_hide');
     } else {
     $(this).parent().find('.variation_non_virtual_ele').removeClass('non_virtual_ele_hide');
     }
     }).change();
     });
     
     $('.variation_is_downloadable_ele').each(function() {
     $(this).off('change').on('change', function() {
     if($(this).is(':checked')) {
     $(this).parent().find('.variation_downloadable_ele').removeClass('downloadable_ele_hide');
     $(this).parent().find('.variation_downloadable_ele').next('.upload_button').removeClass('downloadable_ele_hide');
     } else {
     $(this).parent().find('.variation_downloadable_ele').addClass('downloadable_ele_hide');
     $(this).parent().find('.variation_downloadable_ele').next('.upload_button').addClass('downloadable_ele_hide');
     }
     }).change();
     });
     }
     addVariationManageStockProperty();*/

    $('.manage_stock_ele').change(function () {
        if ($(this).is(':checked')) {
            $(this).parents('.pro_ele_block').first().find('.stock_qty_wrapper').removeClass('non_stock_ele_hide');
            $(this).parents('.pro_ele_block').first().find('.backorders_wrapper').removeClass('non_stock_ele_hide');
        } else {
            $(this).parents('.pro_ele_block').first().find('.stock_qty_wrapper').addClass('non_stock_ele_hide');
            $(this).parents('.pro_ele_block').first().find('.backorders_wrapper').addClass('non_stock_ele_hide');
        }
    });

    /*$('.variation_manage_stock').change(function () {
     if ($(this).is(':checked')) {
     $(this).parent().find('.variation_non_manage_stock').removeClass('non_stock_ele_hide');
     } else {
     $(this).parent().find('.variation_non_manage_stock').addClass('non_stock_ele_hide');
     }
     }).change();*/

    //var pro_types = [ "simple", "grouped", "external" ];
    var pro_types = ["simple"];
    if ($('#product_type').length > 0) {
        $('#product_type').change(function (e) {
            if (e.originalEvent !== undefined) {
                $("#frontend_product_manager_accordion").accordion("option", "active", 0);
                $("#frontend_product_manager_accordion").accordion("refresh");
            }
            var product_type = $(this).val();
            $('.pro_ele_head').addClass('pro_head_hide');
            $('.pro_ele_block').addClass('pro_block_hide');
            $('.pro_ele').addClass('pro_ele_hide');
            $('.frontend_product_manager_product_types > .description').addClass('pro_ele_hide');

            $('.' + product_type).removeClass('pro_ele_hide pro_block_hide pro_head_hide');
            if (product_type == 'simple')
                $('.frontend_product_manager_product_types > .description').removeClass('pro_ele_hide');

            $('#is_downloadable').change();
            // WCMp Product Types Support
            if ($.inArray(product_type, pro_types) == -1) {
                $('.simple').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
                $('.non-' + product_type).addClass('pro_ele_hide pro_block_hide pro_head_hide');
            }


            $(document.body).trigger('product_type_changed');

        }).change();

        // Downloadable
        $('#is_downloadable').change(function () {
            if ($(this).is(':checked')) {
                $('.downlodable').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
            } else {
                $('.downlodable').addClass('pro_ele_hide pro_block_hide pro_head_hide');
            }
        }).change();
        $('.is_downloadable_hidden').change(function () {
            if ($(this).val() == 'enable') {
                $('.downlodable').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
            } else {
                $('.downlodable').addClass('pro_ele_hide pro_block_hide pro_head_hide');
            }
        }).change();
        if ($('#is_downloadable').length == 0)
            $('.downlodable').addClass('downloadable_ele_hide');

        // Virtual
        $('#is_virtual').change(function () {
            if ($(this).is(':checked')) {
                $('.nonvirtual').addClass('pro_ele_hide pro_block_hide pro_head_hide');
            } else {
                $('.nonvirtual').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
            }
        }).change();
        $('.is_virtual_hidden').change(function () {
            if ($(this).val() == 'enable') {
                $('.nonvirtual').addClass('pro_ele_hide pro_block_hide pro_head_hide');
            } else {
                $('.nonvirtual').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
            }
        }).change();
    } else {
        $('.pro_ele_head').addClass('pro_head_hide');
        $('.pro_ele_block').addClass('pro_block_hide');
        $('.pro_ele').addClass('pro_ele_hide');
    }

    $('.dc_datepicker').each(function () {
        $(this).datepicker({
            dateFormat: $(this).data('date_format'),
            changeMonth: true,
            changeYear: true
        });
    });

    $("#sale_date_from").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        onClose: function (selectedDate) {
            $("#sale_date_upto").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#sale_date_upto").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        onClose: function (selectedDate) {
            $("#sale_date_from").datepicker("option", "maxDate", selectedDate);
        }
    });



    addMultiInputProperty = function (multi_input_holder) {

        var multi_input_limit = multi_input_holder.data('limit');
        if (typeof multi_input_limit == 'undefined')
            multi_input_limit = -1;
        if (multi_input_holder.children('.multi_input_block').length == 1)
            multi_input_holder.children('.multi_input_block').children('.remove_multi_input_block').css('display', 'none');
        if (multi_input_holder.children('.multi_input_block').length == multi_input_limit)
            multi_input_holder.find('.add_multi_input_block').hide();
        else
            multi_input_holder.find('.add_multi_input_block').show();
        multi_input_holder.children('.multi_input_block').each(function () {
            if ($(this)[0] != multi_input_holder.children('.multi_input_block:last')[0]) {
                $(this).children('.add_multi_input_block').remove();
            }
        });

        multi_input_holder.children('.multi_input_block').find('.add_multi_input_block').off('click').on('click', function () {

            var holder_id = multi_input_holder.attr('id');
            var holder_name = multi_input_holder.data('name');
            //var multi_input_blockCount = multi_input_holder.data('length');
            var multi_input_blockCount = $(this).parent().find('.multi_input_block_element').attr('id').split("_").pop();
            multi_input_blockCount++;
            var multi_input_blockEle = multi_input_holder.children('.multi_input_block:first').clone(false);

            multi_input_blockEle.find('textarea,input:not(input[type=button],input[type=submit],input[type=checkbox],input[type=radio])').val('');
            multi_input_blockEle.find('input[type=checkbox]').attr('checked', false);

            multi_input_blockEle.find('.dc-wp-fields-uploader,.multi_input_block_element:not(.multi_input_holder)').each(function () {
                var ele = $(this);
                var ele_name = ele.data('name');
                if (ele.hasClass('dc-wp-fields-uploader')) {
                    var uploadEle = ele;
                    ele_name = uploadEle.find('.multi_input_block_element').data('name');
                    /****/
                    uploadEle.find('a span').css('display', 'none');
                    //if (holder_id + '_' + ele_name + '_' + multi_input_blockCount === 'variations_downloadable_file_' + multi_input_blockCount) {
                    uploadEle.find('a').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_display');
                    //}
                    /*****/
                    uploadEle.find('img').attr('src', '').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_display').addClass('placeHolder').css('display', 'inline-block');
                    uploadEle.find('.multi_input_block_element').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount).attr('name', holder_name + '[' + multi_input_blockCount + '][' + ele_name + ']');
                    uploadEle.find('.upload_button').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_button').show();
                    uploadEle.find('.remove_button').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_remove_button').hide();
                    addDCUploaderProperty(uploadEle);
                } else {
                    ele.attr('name', holder_name + '[' + multi_input_blockCount + '][' + ele_name + ']');
                    ele.attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount);
                    ele.parent().parent().find('.checkbox_title').attr('for', holder_name + '_' + ele_name + '_' + multi_input_blockCount);
                }

                if (ele.hasClass('dc_datepicker')) {
                    ele.removeClass('hasDatepicker').datepicker({
                        dateFormat: ele.data('date_format'),
                        changeMonth: true,
                        changeYear: true
                    });
                } else if (ele.hasClass('time_picker')) {
                    $('.time_picker').timepicker('remove').timepicker({'step': 15});
                    ele.timepicker('remove').timepicker({'step': 15});
                }
            });

            // Nested multi-input block property
            multi_input_blockEle.find('.multi_input_holder').each(function () {
                setNestedMultiInputIndex($(this), holder_id, holder_name, multi_input_blockCount);
            });


            multi_input_blockEle.children('.remove_multi_input_block').off('click').on('click', function () {
                var remove_ele_parent = $(this).parent().parent();
                var addEle = remove_ele_parent.children('.multi_input_block').children('.add_multi_input_block').clone(true);
                $(this).parent().remove();
                remove_ele_parent.children('.multi_input_block').children('.add_multi_input_block').remove();
                remove_ele_parent.children('.multi_input_block:last').append(addEle);
                if (remove_ele_parent.children('.multi_input_block').length == multi_input_limit)
                    remove_ele_parent.find('.add_multi_input_block').hide();
                else
                    remove_ele_parent.find('.add_multi_input_block').show();
                if (remove_ele_parent.children('.multi_input_block').length == 1)
                    remove_ele_parent.children('.multi_input_block').children('.remove_multi_input_block').css('display', 'none');
            });

            multi_input_blockEle.children('.add_multi_input_block').remove();
            multi_input_holder.append(multi_input_blockEle);
            multi_input_holder.children('.multi_input_block:last').append($(this));
            if (multi_input_holder.children('.multi_input_block').length > 1)
                multi_input_holder.children('.multi_input_block').children('.remove_multi_input_block').css('display', 'block');
            if (multi_input_holder.children('.multi_input_block').length == multi_input_limit)
                multi_input_holder.find('.add_multi_input_block').hide();
            else
                multi_input_holder.find('.add_multi_input_block').show();
            multi_input_holder.data('length', multi_input_blockCount);

            //addVariationManageStockProperty();
        });

        if (!multi_input_holder.hasClass('multi_input_block_element')) {
            multi_input_holder.children('.multi_input_block').css('padding-bottom', '40px');
        }
        if (multi_input_holder.children('.multi_input_block').children('.multi_input_holder').length > 0) {
            multi_input_holder.children('.multi_input_block').css('padding-bottom', '40px');
        }

        multi_input_holder.children('.multi_input_block').children('.remove_multi_input_block').off('click').on('click', function () {
            var remove_ele_parent = $(this).parent().parent();
            var addEle = remove_ele_parent.children('.multi_input_block').children('.add_multi_input_block').clone(true);
            // For Attributes
            if ($(this).parent().find($('input[data-name="is_taxonomy"]').data('name') == 1)) {
                $taxonomy = $(this).parent().find($('input[data-name="tax_name"]')).val();
                $('select.fpm_attribute_taxonomy').find('option[value="' + $taxonomy + '"]').removeAttr('disabled');
            }
            $(this).parent().remove();
            remove_ele_parent.children('.multi_input_block').children('.add_multi_input_block').remove();
            remove_ele_parent.children('.multi_input_block:last').append(addEle);
            if (remove_ele_parent.children('.multi_input_block').length == 1)
                remove_ele_parent.children('.multi_input_block').children('.remove_multi_input_block').css('display', 'none');
            if (remove_ele_parent.children('.multi_input_block').length == multi_input_limit)
                remove_ele_parent.find('.add_multi_input_block').hide();
            else
                remove_ele_parent.find('.add_multi_input_block').show();
        });
    }

    $('.multi_input_holder').each(function () {
        var multi_input_holder = $(this);
        addMultiInputProperty(multi_input_holder);
    });

    resetMultiInputIndex = function (multi_input_holder) {

        var holder_id = multi_input_holder.attr('id');
        var holder_name = multi_input_holder.data('name');
        var multi_input_blockCount = 0;

        multi_input_holder.find('.multi_input_block').each(function () {
            $(this).find('.dc-wp-fields-uploader,.multi_input_block_element:not(.multi_input_holder)').each(function () {
                var ele = $(this);
                var ele_name = ele.data('name');
                if (ele.hasClass('dc-wp-fields-uploader')) {
                    var uploadEle = ele;
                    ele_name = uploadEle.find('.multi_input_block_element').data('name');
                    uploadEle.find('img').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_display').addClass('placeHolder');
                    uploadEle.find('.multi_input_block_element').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount).attr('name', holder_name + '[' + multi_input_blockCount + '][' + ele_name + ']');
                    uploadEle.find('.upload_button').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_button').show();
                    uploadEle.find('.remove_button').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_remove_button').hide();

                    //edited by dCube
                    var attchment_val = uploadEle.find('#' + holder_id + '_' + ele_name + '_' + multi_input_blockCount).val();
                    if (attchment_val) {
                        uploadEle.find('#variations_image_' + multi_input_blockCount + '_button').hide();
                        uploadEle.find('#variations_image_' + multi_input_blockCount + '_remove_button').show();
                    }

                } else {
                    var multiple = ele.attr('multiple');
                    if (typeof multiple !== typeof undefined && multiple !== false) {
                        ele.attr('name', holder_name + '[' + multi_input_blockCount + '][' + ele_name + '][]');
                    } else {
                        ele.attr('name', holder_name + '[' + multi_input_blockCount + '][' + ele_name + ']');
                    }
                    ele.attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount);
                    //ele.parent().parent().find('.checkbox_title').attr('for', holder_name + '_' + ele_name + '_' + multi_input_blockCount);
                }
                /*var eleid = ele.attr('id');
                 ele.parent().parent().find('.checkbox_title').attr('for', eleid);
                 console.log(ele.parent().parent().find('.checkbox_title').attr('for'));*/
            });
            $('.checkbox_title').each(function () {
                $(this).attr('for', $(this).next().find('.multi_input_block_element').attr('id'));
            });
            multi_input_blockCount++;
        });
    }

    function setNestedMultiInputIndex(nested_multi_input, holder_id, holder_name, multi_input_blockCount) {
        nested_multi_input.children('.multi_input_block:not(:last)').remove();
        var multi_input_id = nested_multi_input.attr('id');
        multi_input_id = multi_input_id.replace(holder_id + '_', '');
        var multi_input_id_splited = multi_input_id.split('_');
        var multi_input_name = '';
        for (var i = 0; i < (multi_input_id_splited.length - 1); i++) {
            if (multi_input_name != '')
                multi_input_name += '_';
            multi_input_name += multi_input_id_splited[i];
        }
        nested_multi_input.attr('data-name', holder_name + '[' + multi_input_blockCount + '][' + multi_input_name + ']');
        nested_multi_input.attr('id', holder_id + '_' + multi_input_name + '_' + multi_input_blockCount);
        nested_multi_input.children('.multi_input_block').find('.dc-wp-fields-uploader,.multi_input_block_element:not(.multi_input_holder)').each(function () {
            var ele = $(this);
            var ele_name = ele.data('name');
            if (ele.hasClass('dc-wp-fields-uploader')) {
                var uploadEle = ele;
                ele_name = uploadEle.find('.multi_input_block_element').data('name');
                uploadEle.find('img').attr('src', '').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_display').addClass('placeHolder');
                uploadEle.find('.multi_input_block_element').attr('id', holder_id + '_' + multi_input_name + '_' + multi_input_blockCount + '_' + ele_name + '_0').attr('name', holder_name + '[' + multi_input_blockCount + '][' + multi_input_name + '][0][' + ele_name + ']');
                uploadEle.find('.upload_button').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_button').show();
                uploadEle.find('.remove_button').attr('id', holder_id + '_' + ele_name + '_' + multi_input_blockCount + '_remove_button').hide();
                addDCUploaderProperty(uploadEle);
            } else {
                var multiple = ele.attr('multiple');
                if (typeof multiple !== typeof undefined && multiple !== false) {
                    ele.attr('name', holder_name + '[' + multi_input_blockCount + '][' + multi_input_name + '][0][' + ele_name + '][]');
                } else {
                    ele.attr('name', holder_name + '[' + multi_input_blockCount + '][' + multi_input_name + '][0][' + ele_name + ']');
                }
                ele.attr('id', holder_id + '_' + multi_input_name + '_' + multi_input_blockCount + '_' + ele_name + '_0');
            }

            if (ele.hasClass('dc_datepicker')) {
                ele.removeClass('hasDatepicker').datepicker({
                    dateFormat: ele.data('date_format'),
                    changeMonth: true,
                    changeYear: true
                });
            } else if (ele.hasClass('time_picker')) {
                $('.time_picker').timepicker('remove').timepicker({'step': 15});
                ele.timepicker('remove').timepicker({'step': 15});
            }
        });

        addMultiInputProperty(nested_multi_input);

        if (nested_multi_input.children('.multi_input_block').children('.multi_input_holder').length > 0)
            nested_multi_input.children('.multi_input_block').css('padding-bottom', '40px');

        nested_multi_input.children('.multi_input_block').children('.multi_input_holder').each(function () {
            setNestedMultiInputIndex($(this), holder_id + '_' + multi_input_name + '_0', holder_name + '[' + multi_input_blockCount + '][' + multi_input_name + ']', 0);
        });
    }

    // Add Taxonomy Attribute Rows.
    $('button.fpm_add_attribute').on('click', function () {
        var attribute = $('select.fpm_attribute_taxonomy').val();

        if (attribute) {
            var data = {
                action: 'generate_taxonomy_attributes',
                taxonomy: attribute
            };

            $('#attributes').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

            $.ajax({
                type: 'POST',
                url: product_manager_js_script_data.ajax_url,
                data: data,
                success: function (response) {
                    if (response) {
                        $response = $(response);
                        $('#attributes').append($response.find('.multi_input_block'));
                        addMultiInputProperty($('#attributes'));
                        resetMultiInputIndex($('#attributes'));
                        $('#product_type').change();
                        /*$('#attributes').find('.multi_input_block:last').each(function() {
                         $(this).find('input[data-name="is_variation"]').change(function() {
                         resetVariationsAttributes();
                         });
                         });*/

                        $('#attributes').find('.multi_input_block:last').find('select').select2({
                            placeholder: product_manager_js_script_data.messages.search_attribute
                        });
                        $('#attributes').find('.multi_input_block:last').find('input[type=checkbox]').each(function () {
                            $(this).wrap('<span class="checkbox-holder"></span>').after('<i class="wcmp-font ico-uncheckbox-icon"></i>');
                        });

                    }
                }
            });
        }

        if (attribute) {
            $('select.fpm_attribute_taxonomy').find('option[value="' + attribute + '"]').attr('disabled', 'disabled');
            $('select.fpm_attribute_taxonomy').val('');
        }

        $('#attributes').unblock();

        return false;
    });

    if ($('#select_attributes').length > 0) {
        $('#attributes').append($('#select_attributes').html());
        $('#select_attributes').remove();
        addMultiInputProperty($('#attributes'));
        resetMultiInputIndex($('#attributes'));
        $('#attributes').find('.multi_input_block').find('select').select2({
            placeholder: product_manager_js_script_data.messages.search_attribute
        });
    }

    $('#attributes').find('.multi_input_block').each(function () {
        if ($(this).find($('input[data-name="is_taxonomy"]').data('name') == 1)) {
            $taxonomy = $(this).find($('input[data-name="tax_name"]')).val();
            $('select.fpm_attribute_taxonomy').find('option[value="' + $taxonomy + '"]').attr('disabled', 'disabled');
        }
    });

    /*$('#attributes').find('.multi_input_block').each(function() {
     $(this).find( 'input[data-name="is_variation"]' ).change(function() {
     resetVariationsAttributes();
     });
     });*/

    /*function resetVariationsAttributes() {
     $('#variations').block({
     message: null,
     overlayCSS: {
     background: '#fff',
     opacity: 0.6
     }
     });
     var data = {
     action : 'generate_variation_attributes',
     product_manager_form : $('#product_manager_form').serialize()
     }  
     $.ajax({
     type:    'POST',
     url: product_manager_js_script_data.ajax_url,
     data: data,
     success: function(response) {
     if(response) {
     $.each($.parseJSON(response), function(attr_name, attr_value) {
     // Default Attributes
     var default_select_html = '<select name="default_attributes[attribute_'+attr_name.toLowerCase()+']" class="regular-select pro_ele attribute_ele attribute_ele_new variable multi_input_block_element" data-name="default_attribute_'+attr_name.toLowerCase()+'"><option value="">Any ' + jsUcfirst( attr_name.replace( "pa_", "" ) ) + ' ..</option>';
     $.each(attr_value, function(k, attr_val) {
     default_select_html += '<option value="'+k+'">'+attr_val+'</option>';
     });
     default_select_html += '</select>';
     $('.default_attributes_holder').each(function() {
     if($(this).find('select[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').length > 0) {
     $attr_selected_val = $(this).find('select[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').val();
     $(this).find('select[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').replaceWith($(default_select_html));
     $(this).find('select[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').val($attr_selected_val);
     } else if($(this).find('input[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').length > 0) {
     $attr_selected_val = $(this).find('input[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').val();
     $(this).find('input[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').replaceWith($(default_select_html));
     $(this).find('select[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').val($attr_selected_val);
     } else {
     $(this).append(default_select_html);
     }
     });
     
     // Variation Attributes
     var select_html = '<p class="variations_'+attr_name.toLowerCase()+' pro_title attribute_ele_new selectbox_title"><strong>' + jsUcfirst( attr_name.replace( "pa_", "" ) ) + '</strong></p><select name="attribute_'+attr_name.toLowerCase()+'" class="regular-select pro_ele attribute_ele attribute_ele_new variable multi_input_block_element" data-name="attribute_'+attr_name.toLowerCase()+'"><option value="">Any ' + jsUcfirst( attr_name.replace( "pa_", "" ) ) + ' ..</option>';
     $.each(attr_value, function(k, attr_val) {
     select_html += '<option value="'+k+'">'+attr_val+'</option>';
     });
     
     select_html += '</select>';
     $('#variations').find('.multi_input_block').each(function() {
     if($(this).find('select[data-name="attribute_'+attr_name.toLowerCase()+'"]').length > 0) {
     $attr_selected_val = $(this).find('select[data-name="attribute_'+attr_name.toLowerCase()+'"]').val();
     $(this).find('select[data-name="attribute_'+attr_name.toLowerCase()+'"]').replaceWith($(select_html));
     $(this).find('select[data-name="attribute_'+attr_name.toLowerCase()+'"]').val($attr_selected_val);
     } else if($(this).find('input[data-name="attribute_'+attr_name.toLowerCase()+'"]').length > 0) {
     $attr_selected_val = $(this).find('input[data-name="attribute_'+attr_name.toLowerCase()+'"]').val();
     $(this).find('input[data-name="attribute_'+attr_name.toLowerCase()+'"]').replaceWith($(select_html));
     $(this).find('select[data-name="attribute_'+attr_name.toLowerCase()+'"]').val($attr_selected_val);
     } else {
     $(this).prepend(select_html);
     }
     });
     });
     $('.attribute_ele_old').remove();
     $('.attribute_ele_new').addClass('attribute_ele_old').removeClass('attribute_ele_new');
     resetMultiInputIndex($('#variations'));
     $('#variations').unblock();
     }
     },
     dataType: 'html'
     });  
     }
     resetVariationsAttributes();*/

    // Creating Default attributes
    $default_attributes = $('input[data-name="default_attributes_hidden"]');
    if ($default_attributes.length > 0) {
        $default_attributes_val = $default_attributes.val();
        if ($default_attributes_val.length > 0) {
            $.each($.parseJSON($default_attributes_val), function (attr_key, attr_val) {
                $('.default_attributes_holder').append('<input type="hidden" name="default_attribute_' + attr_key + '" data-name="default_attribute_' + attr_key + '" value="' + attr_val + '" />');
            });
        }
    }

    // Creating Variation attributes
    /*$('#variations').find('.multi_input_block').each(function() {
     $multi_input_block = $(this);
     $attributes = $multi_input_block.find('input[data-name="attributes"]');
     $attributes_val = $attributes.val();
     if($attributes_val.length > 0) {
     $.each($.parseJSON($attributes_val), function(attr_key, attr_val) {
     $multi_input_block.prepend('<input type="hidden" name="'+attr_key+'" data-name="'+attr_key+'" value="'+attr_val+'" />');
     });
     }
     });*/

    // Track Deleting Variation
    /*var removed_variations = [];
     $('#variations').find('.remove_multi_input_block').click(function() {
     removed_variations.push($(this).parent().find('.variation_id').val());
     });*/

    // Variation Options
    /*$('#variations_options').change(function() {
     $variations_option = $(this).val();
     if( $variations_option ) {
     switch( $variations_option ) {
     case 'set_regular_price':
     var regular_price = prompt( "Regular Price" );
     if( regular_price != null ) {
     $('#variations').find('input[data-name="regular_price"]').each(function() {
     if( !isNaN(parseFloat(regular_price)) ) {
     $(this).val(parseFloat(regular_price));
     } else {
     //$(this).val(0);
     }
     });
     }
     break;
     
     case 'regular_price_increase':
     var regular_price = prompt( "Regular price increase by" );
     if( regular_price != null ) {
     $('#variations').find('input[data-name="regular_price"]').each(function() {
     if( !isNaN(parseFloat(regular_price)) ) {
     if( $(this).val().length > 0 ) {
     $(this).val(parseFloat($(this).val()) + parseFloat(regular_price));
     } else {
     $(this).val(parseFloat(regular_price));
     }
     }
     });
     }
     break;
     
     case 'regular_price_decrease':
     var regular_price = prompt( "Regular price decrease by" );
     if( regular_price != null ) {
     $('#variations').find('input[data-name="regular_price"]').each(function() {
     if( !isNaN(parseFloat(regular_price)) ) {
     if( $(this).val().length > 0 ) {
     $(this).val(parseFloat($(this).val()) - parseFloat(regular_price));
     } else {
     $(this).val(parseFloat(regular_price));
     }
     }
     });
     }
     break;
     
     case 'set_sale_price':
     var sale_price = prompt( "Sale Price" );
     if( sale_price != null ) {
     $('#variations').find('input[data-name="sale_price"]').each(function() {
     if( !isNaN(parseFloat(sale_price)) ) {
     $(this).val(parseFloat(sale_price));
     } else {
     //$(this).val(0);
     }
     });
     }
     break;
     
     case 'sale_price_increase':
     var sale_price = prompt( "Sale price increase by" );
     if( sale_price != null ) {
     $('#variations').find('input[data-name="sale_price"]').each(function() {
     if( !isNaN(parseFloat(sale_price)) ) {
     if( $(this).val().length > 0 ) {
     $(this).val(parseFloat($(this).val()) + parseFloat(sale_price));
     } else {
     $(this).val(parseFloat(sale_price));
     }
     }
     });
     }
     break;
     
     case 'sale_price_decrease':
     var sale_price = prompt( "Sale price decrease by" );
     if( sale_price != null ) {
     $('#variations').find('input[data-name="sale_price"]').each(function() {
     if( !isNaN(parseFloat(sale_price)) ) {
     if( $(this).val().length > 0 ) {
     $(this).val(parseFloat($(this).val()) - parseFloat(sale_price));
     } else {
     $(this).val(parseFloat(sale_price));
     }
     }
     });
     }
     break;
     }
     $(this).val('');
     }
     });*/

    // TinyMCE intialize - Description
//    var descTinyMCE = tinymce.init({
//        selector: '#description',
//        height: 120,
//        menubar: false,
//        plugins: [
//            'advlist autolink lists link image charmap print preview anchor',
//            'searchreplace visualblocks code fullscreen',
//            'insertdatetime media table contextmenu paste code'
//        ],
//        toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify |  bullist numlist outdent indent | link image',
//        content_css: '//www.tinymce.com/css/codepen.min.css',
//        statusbar: false,
//        entity_encoding: "raw"
//    });

    function product_manager_form_validate() {
        $is_valid = true;
        $('.woocommerce-error,.woocommerce-message').remove();
        var title = $.trim($('#product_manager_form').find('#title').val());
        if (title.length == 0) {
            $is_valid = false;
            $('#product_manager_form').prepend('<div class="woocommerce-error" tabindex="-1">' + product_manager_js_script_data.messages.no_title + '</div>');
            $('.woocommerce-error').focus();
        }
        return $is_valid;
    }

    // Draft Product
    $('#pruduct_manager_draft_button').click(function (event) {
        event.preventDefault();

        // Validations
        $is_valid = product_manager_form_validate();

        if ($is_valid) {
            $(this).prop('disabled', true);
            $("#pruduct_manager_submit_button").prop('disabled', true);
            $('#product_manager_form').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
            $('#product_manager_form').find(':input:disabled').removeAttr('disabled');
            var description = get_fpm_tinymce_content('description');
            var excerpt = get_fpm_tinymce_content('excerpt');
            var data = {
                action: 'frontend_product_manager',
                product_manager_form: $('#product_manager_form').serialize(),
                description: description,
                excerpt: excerpt,
                status: 'draft',
                removed_variations: removed_variations,
                removed_person_types: removed_person_types
            }
            $.post(product_manager_js_script_data.ajax_url, data, function (response) {
                $(this).prop('disabled', false);
                $("#pruduct_manager_submit_button").prop('disabled', false);
                if (response) {
                    $response_json = $.parseJSON(response);
                    if ($response_json.status) {
                        if ($response_json.is_new == '0') {
                            $('.woocommerce-error,.woocommerce-message').remove();
                            $('#product_manager_form').prepend('<div class="woocommerce-message" tabindex="-1">' + $response_json.message + '</div>');
                            $('.woocommerce-message').focus();
                        } else {
                            window.location = $response_json.redirect;
                        }
                    } else {
                        $('.woocommerce-error,.woocommerce-message').remove();
                        $('#product_manager_form').prepend('<div class="woocommerce-error" tabindex="-1">' + $response_json.message + '</div>');
                        $('.woocommerce-error').focus();
                    }
                    if ($response_json.id)
                        $('#pro_id').val($response_json.id);
                    $('#product_manager_form').unblock();
                }
            });
        }
    });

    // Submit Product
    $('#pruduct_manager_submit_button').click(function (event) {
        event.preventDefault();

        // Validations
        $is_valid = product_manager_form_validate();

        if ($is_valid) {
            $(this).prop('disabled', true);
            $("#pruduct_manager_draft_button").prop('disabled', true);
            $('#product_manager_form').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
            $('#product_manager_form').find(':input:disabled').removeAttr('disabled');
            var description = get_fpm_tinymce_content('description');
            var excerpt = get_fpm_tinymce_content('excerpt');

            var data = {
                action: 'frontend_product_manager',
                product_manager_form: $('#product_manager_form').serialize(),
                description: description,
                excerpt: excerpt,
                status: 'submit',
                removed_variations: removed_variations,
                removed_person_types: removed_person_types
            }
            $.post(product_manager_js_script_data.ajax_url, data, function (response) {
                $(this).prop('disabled', false);
                $("#pruduct_manager_draft_button").prop('disabled', false);
                if (response) {
                    $response_json = $.parseJSON(response);
                    if ($response_json.status) {
                        if ($response_json.is_new == '0') {
                            $('.woocommerce-error,.woocommerce-message').remove();
                            $('#product_manager_form').prepend('<div class="woocommerce-message" tabindex="-1">' + $response_json.message + '</div>');
                            $('.woocommerce-message').focus();
                        } else {
                            window.location = $response_json.redirect;
                        }
                    } else {
                        $('.woocommerce-error,woocommerce-message').remove();
                        $('#product_manager_form').prepend('<div class="woocommerce-error" tabindex="-1">' + $response_json.message + '</div>');
                        $('.woocommerce-error').focus();
                    }
                    if ($response_json.id)
                        $('#pro_id').val($response_json.id);
                    $('#product_manager_form').unblock();
                }
            });
        }
    });

    function jsUcfirst(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function get_fpm_tinymce_content(id) {
        var content;
        var inputid = id;
        if (typeof tinyMCE == 'undefined') {
            var textArea = jQuery('textarea#' + inputid);
            if (textArea.length > 0 && textArea.is(':visible')) {
                content = textArea.val();
            }
        } else {
            var editor = tinyMCE.get(inputid);
            var textArea = jQuery('textarea#' + inputid);
            if (textArea.length > 0 && textArea.is(':visible')) {
                content = textArea.val();
            } else {
                //content = editor.getContent();
                tinyMCE.triggerSave();
                content = textArea.val();
            }
        }
        return content;
    }
});