!function ($) {
       if (!$.fn.noUiSlider)
            return;

        var $sliders = $('.vc_ui-panel-content-container').find('input[type="range"]').not('.switch').not('.slider');
        $sliders.each(function () {
            var $this = $(this),
                $parent = $this.parent(),
                $label = $('<span></span>'),
                min = 0,
                max = 100,
                start = 0,
                isSwitch = $this.hasClass('switch'),
                sliderCls = isSwitch ? 'switch' : 'slider',
                $slider = $('<div class="' + sliderCls + '"></div>'),
                $states = ['Off', 'On'],
                setupState = true;//For switches
                $inputValue = $(this).siblings('input.wpb_vc_param_value');



            //Set label
            $parent.find('.label').prepend($label);
            
            if ('value' in this.attributes)
            {
                $label.html(this.attributes['value'].value);
                $inputValue.val(this.attributes['value'].value);
            }
                

            //Set values
            if (isSwitch) {
                min = 0;
                max = 1;

                if ($this.attr('data-state0') !== undefined)
                    $states[0] = $this.attr('data-state0');

                if ($this.attr('data-state1') !== undefined)
                    $states[1] = $this.attr('data-state1');

            }
            else {

                if ($this.attr('min') !== undefined)
                    min = parseInt($this.attr('min'));

                if ($this.attr('max') !== undefined)
                    max = parseInt($this.attr('max'));

                if ($this.attr('step') !== undefined)
                    step = parseInt($this.attr('step'));
                else
                    step = 0.01;

            }

            if ('value' in this.attributes &&
                this.attributes['value'].value.length > 0)
                start = parseInt(this.attributes['value'].value);
            else
                start = min + max * 0.5;

            $this.hide();
            $slider.appendTo($parent);

            if (isSwitch) {
                $slider.noUiSlider({
                    start: start,
                    range: {
                        'min': [min],
                        'max': [max]
                    },
                    step: 1,
                    direction: "ltr",
                    behaviour: 'tap',
                    connect: "upper"
                });
            }
            else {
                $slider.noUiSlider({
                    start: [start],
                    range: {
                        'min': [min],
                        'max': [max]
                    },
                    step : step,
                    direction: "ltr",
                    behaviour: 'tap',
                    connect: "upper"
                });
            }

            $slider.on({
                slide: Handle_Change,
            });


            function Handle_Change(e) {
                var value = $slider.val();

                if (isNaN(value) || (setupState && isSwitch && start > 0 && start < 1))
                    value = min;

                if (isSwitch) {

                    $label.html($states[Math.ceil(value)]);
                }  
                else
                {
                    if(Math.ceil(step) == step && $.isNumeric(step)) 
                    {
                        $label.html(Math.ceil(value));
                        value = Math.ceil(value);
                    }
                    else
                    {
                        $label.html(value);
                    } 
                }
                $this.val(value);
                $inputValue.val(value);

                setupState = false;
            }

            var $midbar = $slider.find('.noUi-midBar'),
                left = $midbar.css('left'),
                right = $midbar.css('right');

            if (left == '0px' && right == '0px' && $slider.val() != max) {
                $midbar.css({ right: $this.width() });
            }

            var $sliderHandle = $slider.find('.noUi-handle');

            Handle_Change();

        });

}(window.jQuery);