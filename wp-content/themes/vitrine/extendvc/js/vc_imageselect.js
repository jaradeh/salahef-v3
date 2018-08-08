!function ($) {

    $('.ep-imageselect-container').each(function () {

        var $list = $(this),
        $input = $list.find('input'),
        $images = $list.find('.ep-image'),
        $inputval = $input.val();
       
        if ($inputval.length !== 0) {
            $list.find("span.image-" + $inputval).addClass('selected');
        }
        else
        {
            $list.find("span:first-child").addClass('selected');
        }

        $images.click(function () {
           
            if($(this).hasClass('selected'))
            {
                $(this).removeClass('selected');
                $input.val('');
            }
            else
            {
                $input.val($(this).attr('data-name'));
                $images.removeClass('selected');
                $(this).addClass('selected');
            }
            $input.trigger( "change" );
        });

    });



}(window.jQuery);