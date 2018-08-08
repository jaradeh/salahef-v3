!function ($) {

    $('.ep-icon-container').each(function () {

        var $list = $(this),
        $input = $list.find('input'),
        $icons = $list.find('.ep-icon'),
        $selected = $(),
        $inputval = $input.val();
       
        if ($inputval.length !== 0) {
            $selected = $list.find("span.icon-" + $inputval).addClass('selected');
        }
        //Scroll iconContainer to show the selected icon
        setTimeout(function(){
            if($selected.length)
            {
                var $offset = $selected.offset().top - $list.offset().top;
                if($offset >0)
                {
                    $list.stop().animate({
                        scrollTop: $offset + "px"
                    }, 10); 
                }        
            }
        },600)


        $icons.click(function () {
            if($(this).hasClass('selected'))
            {
                $(this).removeClass('selected');
                $input.val('');
            }
            else
            {
                $input.val($(this).attr('data-name'));
                $icons.removeClass('selected');
                $(this).addClass('selected');
            }
            $input.trigger( "change" );
        });

    });


}(window.jQuery);