(function($){

    function ThemeStyles()
    {
        var $style = $('select[name="style-preset-color"]');

        $style.change(OnStyleChange);

        function OnStyleChange()
        {
            var $selected = $style.find(':selected'),
                cAttr     = $selected.attr('data-colors'),
                styleName = $selected.val();

            if(cAttr == undefined)
                return;

            var colors    = JSON.parse(cAttr);

            for (var key in colors) {
                if (!colors.hasOwnProperty(key) || undefined == key )
                    continue;

                var color   = colors[key],
                    $input  = $('input[name="'+key+'"]'),
                    $parent = $input.parent(),
                    $picker = $parent.find('.color-selector'),
                    $view   = $parent.find('.color-view'),
                    $pickerBg = $picker.find('div');

                if(styleName == "custom"){
                    $view.css({"display":"none"});
                    $picker.css({"display":'block'});
                    $input.wpColorPicker('color', '');
                    $pickerBg.css('backgroundColor', color);
                    $input.val(color);
                    $input.prop("readonly",false);
                    $input.css({'cursor':'text','font-style':'normal','color':'#666666'});

                }else{
                    $view.css({"display":"block","background-color":color});
                    $picker.css({"display":'none'});
                    $input.val(color);
                    $input.wpColorPicker('color', color);
                    $input.prop("readonly",true);
                    $input.css({'cursor':'not-allowed','font-style':'italic','color':'#9B9B9B'});
                }
            }

        }

    }

    jQuery(function(){
        ThemeStyles();
        var saveButton = $('.save-button'),
            wpAdminBarHeight = 0,
            topOffset = 0;

        if($('#wpadminbar').length)
            wpAdminBarHeight = $("#wpadminbar").outerHeight();

        $(window).scroll(function () {
            topOffset = $('#ep-wrap').offset().top + saveButton.outerHeight() - $(window).scrollTop();
            if(topOffset > 1)
                saveButton.removeClass("fixedSaveButton hidefixedSaveButton");
            else if(topOffset < 1 && topOffset >-50)
                saveButton.addClass("hidefixedSaveButton");
            else if(topOffset < -50)  
                saveButton.removeClass("hidefixedSaveButton").addClass("fixedSaveButton");
        });
        
    });


})(jQuery);