!function ($) {

    $('.ep-checkbox-container').each(function () {

        var $container = $(this),
        $input = $container.find('input.wpb_vc_param_value'),
        $checkboxes = $container.find('.ep-checkbox-field'),
        $values = $input.val(),
        $values_array = [];

        $values_array = $input.val().split(",")

        //reset all selected value
        $input.find('.ep-checkbox-field').prop('checked', false);
        var i;
        for (i = 0; i < $values_array.length; ++i) {
            $container.find('.ep-checkbox-field[value="'+ $values_array[i] +'"]').prop('checked', true);
        }

        $checkboxes.click(function() {
            if($(this).prop('checked')) {
                $values_array.push($(this).val());
            }
            else
            {
                removedItem = $(this).val();
                $values_array = $.grep($values_array, function(value) {
                  return value != removedItem;
                });
            }
            var inputvalue = $values_array.join(",");
            if(inputvalue.substring(0,1) == ',')
            {
                $input.val(inputvalue.substring(1));
            }
            else
            {
                $input.val(inputvalue);
            }
            
            $input.trigger( "change" );
        });

    });


}(window.jQuery);