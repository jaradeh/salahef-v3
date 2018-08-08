(function ($) {

    function video_type_dependencies()
    {
        var $video_type  = $('select[name="video_type"]'),
            $self_hosted_video_section    = $('.section.section-video_extensions'),
            $vimeo_section    = $('.section.section-video_vimeo_id'),
            $youtube_section    = $('.section.section-video_youtube_id'),
            $play_button_style_section    = $('.section.section-video_play_button_color');
			
        function changeHandler()
        {

            var selected = $video_type.find(':selected').val();

            if('none' == selected)
            {
                $youtube_section.add($youtube_section.next('hr')).slideUp('fast');
                $vimeo_section.add($vimeo_section.next('hr')).slideUp('fast');
                $self_hosted_video_section.add($self_hosted_video_section.next('hr')).slideUp('fast');
                $play_button_style_section.slideUp('fast');
            }
            else if('local_video_popup' == selected)
            {
                $youtube_section.add($youtube_section.next('hr')).slideUp('fast');
                $vimeo_section.add($vimeo_section.next('hr')).slideUp('fast');
				$self_hosted_video_section.add($self_hosted_video_section.next('hr')).slideDown('fast');
                $play_button_style_section.slideDown('fast');
				
            }
            else if ('embeded_video_vimeo_popup' == selected) { // Vimeo
                $youtube_section.add($youtube_section.next('hr')).slideUp('fast');
                $vimeo_section.add($vimeo_section.next('hr')).slideDown('fast');
                $self_hosted_video_section.add($self_hosted_video_section.next('hr')).slideUp('fast');
                $play_button_style_section.slideDown('fast');
            }
            else // Youtube
            {
                $youtube_section.add($youtube_section.next('hr')).slideDown('fast');
                $vimeo_section.add($vimeo_section.next('hr')).slideUp('fast');
                $self_hosted_video_section.add($self_hosted_video_section.next('hr')).slideUp('fast');
                $play_button_style_section.slideDown('fast');
            }

        }

        $video_type.change(changeHandler);
        changeHandler();
    }


    function product_detail_style_dependencies()
    {
        var $product_detail_inherit = $('input[name="product_detail_style_inherit"]').siblings('.switch'),
            $product_detail = $product_detail_inherit.closest('.field').siblings('.field.product-detail'),
            $color_field = $product_detail_inherit.closest('.field').siblings('.field.color-field');
            $sidebar_field = $product_detail_inherit.closest('.section-product_detail_style').siblings('.section-product_detail_sidebar_position');
            $sidebar_field_hr = $sidebar_field.next('hr');


        function toggle_product_detail_section() {

            if(Math.round( $product_detail_inherit.val()) == 0)
            {
                $product_detail.hide();
                $color_field.hide();
                $sidebar_field.hide();
                $sidebar_field_hr.hide();
            }
            else
            {
                $product_detail.show();
                var $selected_product_detail = $product_detail.find('a.selected');
                if($selected_product_detail.hasClass('pd_background') || $selected_product_detail.hasClass('pd_top'))
                {
                    $color_field.show();
                    $sidebar_field.hide();
                    $sidebar_field_hr.hide();
                }
                else if($selected_product_detail.hasClass('pd_classic_sidebar'))
                {
                    $color_field.hide();
                    $sidebar_field.show();
                    $sidebar_field_hr.show();
                }
                else
                {
                    $color_field.hide();
                    $sidebar_field.hide();
                    $sidebar_field_hr.hide();
                }
            }
        }

        $product_detail_inherit.on('change', toggle_product_detail_section);
        toggle_product_detail_section();

        $product_detail.find('a').on('click',function(){
            if($(this).hasClass('pd_background') || $(this).hasClass('pd_top'))
            {
                $color_field.show();
                $sidebar_field.hide();
                $sidebar_field_hr.hide();
            }
            else if($(this).hasClass('pd_classic_sidebar'))
            {
                $color_field.hide();
                $sidebar_field.show();
                $sidebar_field_hr.show();
            }
            else
            {
                $color_field.hide();
                $sidebar_field.hide();
                $sidebar_field_hr.hide();
            }
        })
    }
	
    function product_social_share_dependencies()
    {
        var $product_social_share_inherit = $('input[name="social_share_inherit"]').siblings('.switch'),
            $product_social_share_display = $product_social_share_inherit.closest('.field').next('.field');


        function toggle_product_social_share_section() {

            if(Math.round( $product_social_share_inherit.val()) == 0)
            {
                $product_social_share_display.hide();
            }
            else
            {
                $product_social_share_display.show();
            }
        }

        $product_social_share_inherit.on('change', toggle_product_social_share_section);
        toggle_product_social_share_section();
    }

    function ColorPicker() {
        if (!$.fn.wpColorPicker)
            return;

        $('#product_meta_box .colorinput').each(function () {
            $(this).wpColorPicker( { palettes : false});
        });

    }

    $(document).ready(function () {
        ColorPicker();
        video_type_dependencies();
        product_detail_style_dependencies();
        product_social_share_dependencies();
    });

})(jQuery);