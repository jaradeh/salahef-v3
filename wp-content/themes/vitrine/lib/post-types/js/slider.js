(function ($) {
    function slide_dependencies() {
        var $metaBox = $('.ep-main');
        var $caption_style = $metaBox.find('.caption-style-field .imageList a');
        var $background_type = $metaBox.find('.section-background .imageList a');
        var $caption_image_icon_link = $metaBox.find('.section-caption-image-icon .imageList a');


        $caption_style.on('click',function() {
            apply_dependencies();
        });

        $background_type.on('click',function() {
            apply_dependencies();
        });


        $caption_image_icon_link.on('click',function() {
            apply_dependencies();
        });

    }

    function apply_dependencies() {

        var $metaBox = $('.ep-main');
        var $caption_style = $metaBox.find('.caption-style-field .imageList a.selected');
        var $subtile2_font_section = $metaBox.find('.section.section-subtitle2-font');
        var $background_type = $metaBox.find('.section-background .imageList a.selected');
        var $background_style = $metaBox.find('.section-caption .caption-dark-light-field');
        var $caption_image_icon_link = $metaBox.find('.section-caption-image-icon .imageList a.selected');

        var $caption_image_section = $metaBox.find('.section-caption-image');
        var $caption_icon_section = $metaBox.find('.section-caption-icon');
        var $caption_image_icon_section = $metaBox.find('.section-caption-image-icon');

        if(!$caption_style.hasClass('style1'))
        {
            $caption_image_icon_section.show().next('hr').fadeIn();
        }

        if($caption_image_icon_link.hasClass('image') )
        {
           $caption_image_icon_link.parents('.section').siblings('.section-caption-image').fadeIn('fast').next('hr').fadeIn();
           $caption_image_icon_link.parents('.section').siblings('.section-caption-icon').fadeOut('fast').next('hr').fadeOut();
        }
        else
        {
           $caption_image_icon_link.parents('.section').siblings('.section-caption-image').fadeOut('fast').next('hr').fadeOut();
           $caption_image_icon_link.parents('.section').siblings('.section-caption-icon').fadeIn('fast').next('hr').fadeIn();
            
        }

        if($background_type.hasClass('image') )
        {
           $caption_image_icon_link.parents('.section').siblings('.section-background-image').fadeIn('fast').next('hr').fadeIn();
           $caption_image_icon_link.parents('.section').siblings('.section-background-video').fadeOut('fast').next('hr').fadeOut();

        }
        else
        {
           $caption_image_icon_link.parents('.section').siblings('.section-background-video').fadeIn('fast').next('hr').fadeIn();
           $caption_image_icon_link.parents('.section').siblings('.section-background-image').fadeOut('fast').next('hr').fadeOut();
        }


        if($caption_image_icon_link.hasClass('image') )
        {
           $caption_image_icon_link.parents('.section').siblings('.section-caption-image').fadeIn('fast').next('hr').fadeIn();
           $caption_image_icon_link.parents('.section').siblings('.section-caption-icon').fadeOut('fast').next('hr').fadeOut();
        }
        else
        {
           $caption_image_icon_link.parents('.section').siblings('.section-caption-image').fadeOut('fast').next('hr').fadeOut();
           $caption_image_icon_link.parents('.section').siblings('.section-caption-icon').fadeIn('fast').next('hr').fadeIn();
            
        }

        if($caption_style.hasClass('style1'))
        {
           $caption_style.parents('.imageSelect').siblings('.caption-align-field').fadeIn('fast');
           $subtile2_font_section.fadeIn('fast').next('hr').fadeIn('fast');
           $caption_image_section.add($caption_icon_section).add($caption_image_icon_section).hide().next('hr').fadeOut();
        }
        else if($caption_style.hasClass('style5'))
        {
           $caption_style.parents('.imageSelect').siblings('.caption-align-field').fadeOut('fast');
           $subtile2_font_section.fadeOut('fast').next('hr').fadeOut('fast');
        }
        else
        {
            $caption_style.parents('.imageSelect').siblings('.caption-align-field').fadeIn('fast');
            $subtile2_font_section.fadeOut('fast').next('hr').fadeOut('fast');
        }

        if($caption_style.hasClass('style4') || $caption_style.hasClass('style5'))
        {
            $background_style.fadeIn('fast');
        }
        else
        {
            $background_style.fadeOut('fast');
        }
        
    }

    function font_dependencies() {
        var $select_font_type_title = $('select[name="title-font-type"]'),
            $select_font_type_subtitle = $('select[name="subtitle-font-type"]'),
            $select_font_type_subtitle2 = $('select[name="subtitle2-font-type"]'),

            $select_font_title = $('select[name="title-font"]').closest('.field'),
            $select_font_subtitle = $('select[name="subtitle-font"]').closest('.field'),
            $select_font_subtitle2 = $('select[name="subtitle2-font"]').closest('.field'),

            $select_custom_font_title_url = $('input[name="title-custom-font-url"]').closest('.field'),
            $select_custom_font_subtitle_url = $('input[name="subtitle-custom-font-url"]').closest('.field'),
            $select_custom_font_subtitle2_url = $('input[name="subtitle2-custom-font-url"]').closest('.field'),

            $select_custom_font_title_name = $('input[name="title-custom-font-name"]').closest('.field'),
            $select_custom_font_subtitle_name = $('input[name="subtitle-custom-font-name"]').closest('.field'),
            $select_custom_font_subtitle2_name = $('input[name="subtitle2-custom-font-name"]').closest('.field');

            $select_font_weight_title = $('select[name="title-font-weight"]').closest('.field'),
            $select_font_weight_subtitle = $('select[name="subtitle-font-weight"]').closest('.field'),
            $select_font_weight_subtitle2 = $('select[name="subtitle2-font-weight"]').closest('.field');

            $select_font_weight_custom_title = $('input[name="title-custom-font-weight"]').closest('.field'),
            $select_font_weight_custom_subtitle = $('input[name="subtitle-custom-font-weight"]').closest('.field'),
            $select_font_weight_custom_subtitle2 = $('input[name="subtitle2-custom-font-weight"]').closest('.field');

            $select_font_title.add($select_font_subtitle).add($select_font_subtitle2).add($select_custom_font_title_url)
            .add($select_custom_font_subtitle_url).add($select_custom_font_subtitle2_url)
            .add($select_custom_font_title_name).add($select_custom_font_subtitle_name).add($select_custom_font_subtitle2_name).hide();
            set_font_type();

        function set_font_type()
        {
            //Title font
            var selected = $select_font_type_title.find('option:selected').val();
            if(selected == 'default')
            {
                $select_font_title.hide();
                $select_custom_font_title_url.hide()
                $select_custom_font_title_name.hide();
                $select_font_weight_title.hide();
                $select_font_weight_custom_title.hide();
            }
            else if(selected == 'google')
            {
                $select_font_title.show();
                $select_custom_font_title_url.hide()
                $select_custom_font_title_name.hide();
                $select_font_weight_title.show();
                $select_font_weight_custom_title.hide();
            }
            else
            {
                $select_font_title.hide();
                $select_custom_font_title_url.show()
                $select_custom_font_title_name.show();
                $select_font_weight_title.hide();
                $select_font_weight_custom_title.show();
            }


            // Subtitle font
            var selected = $select_font_type_subtitle.find('option:selected').val();
            if(selected == 'default')
            {
                $select_font_subtitle.hide();
                $select_custom_font_subtitle_url.hide()
                $select_custom_font_subtitle_name.hide();
                $select_font_weight_subtitle.hide();
                $select_font_weight_custom_subtitle.hide();
            }
            else if(selected == 'google')
            {
                $select_font_subtitle.show();
                $select_custom_font_subtitle_url.hide()
                $select_custom_font_subtitle_name.hide();
                $select_font_weight_subtitle.show();
                $select_font_weight_custom_subtitle.hide();

            }
            else
            {
                $select_font_subtitle.hide();
                $select_custom_font_subtitle_url.show()
                $select_custom_font_subtitle_name.show();
                $select_font_weight_subtitle.hide();
                $select_font_weight_custom_subtitle.show();
            }


            // Subtitle2 font
            var selected = $select_font_type_subtitle2.find('option:selected').val();
            if(selected == 'default')
            {
                $select_font_subtitle2.hide();
                $select_custom_font_subtitle2_url.hide()
                $select_custom_font_subtitle2_name.hide();
                $select_font_weight_subtitle2.hide();
                $select_font_weight_custom_subtitle2.hide();
            }
            else if(selected == 'google')
            {
                $select_font_subtitle2.show();
                $select_custom_font_subtitle2_url.hide()
                $select_custom_font_subtitle2_name.hide();
                $select_font_weight_subtitle2.show();
                $select_font_weight_custom_subtitle2.hide();
            }
            else
            {
                $select_font_subtitle2.show();
                $select_custom_font_subtitle2_url.show()
                $select_custom_font_subtitle2_name.show();
                $select_font_weight_subtitle2.hide();
                $select_font_weight_custom_subtitle2.show();
            }

            set_font_weight_events();
        }

        $select_font_type_title.change(set_font_type);
        $select_font_type_subtitle.change(set_font_type);
        $select_font_type_subtitle2.change(set_font_type);
        set_font_weight_events();

        function set_font_weight_events()
        {
            $select_font_title.on('change',function(){
                set_font_weight(1);
            });

            $select_font_subtitle.on('change',function(){
                set_font_weight(2);
            });

            $select_font_subtitle2.on('change',function(){
                set_font_weight(3);
            });

            $select_font_type_title.on('change',function(){
                var $font_type = $select_font_type_title.val();
                if($font_type == 'google')
                {
                    set_font_weight(1);
                }
            });

            $select_font_type_subtitle.on('change',function(){
                var $font_type = $select_font_type_subtitle.val();
                if($font_type == 'google')
                {
                    set_font_weight(2);
                }
            });

            $select_font_type_subtitle2.on('change',function(){
                var $font_type = $select_font_type_subtitle2.val();
                if($font_type == 'google')
                {
                    set_font_weight(3);
                }
            });

        }

        function set_font_weight(type) {

            if(type == 1) //title
            {
                var parsed_Font = $.parseJSON($select_font_title.find('select').val()),
                    variants = parsed_Font[$select_font_title.find('select option:selected').text()];
                $select_font_weight_title.find('option').remove();
                $.each(variants, function(key, value) {   
                    $select_font_weight_title.find('select').append($("<option></option>").attr("value",value).text(value));
                });

                $select_font_weight_title.find('select option:first').attr('selected','selected').trigger('change');

            }
            
            if (type==2)
            {
                var parsed_Font = $.parseJSON($select_font_subtitle.find('select').val()),
                    variants = parsed_Font[$select_font_subtitle.find('select option:selected').text()];
                $select_font_weight_subtitle.find('option').remove();
                $.each(variants, function(key, value) {   
                    $select_font_weight_subtitle.find('select').append($("<option></option>").attr("value",value).text(value));
                });

                $select_font_weight_subtitle.find('select option:first').attr('selected','selected').trigger('change');
            }

            if (type==3)
            {
                var parsed_Font = $.parseJSON($select_font_subtitle2.find('select').val()),
                    variants = parsed_Font[$select_font_subtitle2.find('select option:selected').text()];
                $select_font_weight_subtitle2.find('option').remove();
                $.each(variants, function(key, value) {   
                    $select_font_weight_subtitle2.find('select').append($("<option></option>").attr("value",value).text(value));
                });

                $select_font_weight_subtitle2.find('select option:first').attr('selected','selected').trigger('change');
            }

        }

        function set_custom_font_weight(type) {
            
            var variants = { "300": "300", "400": "400", "500": "500", "600": "600", "700": "700" };

            if(type == 1)
            {
                $select_font_weight_title.find('option').remove();
                $.each(variants, function(key, value) {   
                    $select_font_weight_title.find('select').append($("<option></option>").attr("value",key).text(value));
                });

                $select_font_weight_title.find('select option:first').attr('selected','selected').trigger('change');
            }
            else if(type==2)
            {

                $select_font_weight_subtitle.find('option').remove();
                $.each(variants, function(key, value) {   
                    $select_font_weight_subtitle.find('select').append($("<option></option>").attr("value",key).text(value));
                });

                $select_font_weight_subtitle.find('select option:first').attr('selected','selected').trigger('change');

            }
            else
            {
                $select_font_weight_subtitle2.find('option').remove();
                $.each(variants, function(key, value) {   
                    $select_font_weight_subtitle2.find('select').append($("<option></option>").attr("value",key).text(value));
                });

                $select_font_weight_subtitle2.find('select option:first').attr('selected','selected').trigger('change');
            }

        }

    }

    function ColorPicker() {
        if (!$.fn.wpColorPicker)
            return;

        $('#slider_meta_box .colorinput').each(function () {
            $(this).wpColorPicker( { palettes : false});
        });

    }

    $(document).ready(function () {
        $('#postdivrich').addClass('hide-box');
        $('#postimagediv').addClass('hide-box');
        slide_dependencies();
        apply_dependencies();
        font_dependencies();
        ColorPicker();
    });

})(jQuery);