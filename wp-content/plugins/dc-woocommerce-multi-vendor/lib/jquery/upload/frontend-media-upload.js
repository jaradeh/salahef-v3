(function ($) {
    var wcmp_media_frame;
    var target;
    $('.wcmp_upload_btn').on('click', function(e) {
        e.preventDefault();
        target = $(this).data('target');
        // If the media frame already exists, reopen it.
        if ( wcmp_media_frame ) {
            wcmp_media_frame.open();
            return;
        }
        wcmp_media_frame = wp.media({
            multiple: false,  
            library: { type: 'image' }
        });
        // When an image is selected in the media frame...
        wcmp_media_frame.on( 'select', function() {
            // Get media attachment details from the frame state
            var attachment = wcmp_media_frame.state().get('selection').first().toJSON();
//            if(target == "vendor-cover"){ 
//                $('#'+target+'-img').css('background-image', 'url(' + attachment.url + ')');
//            }else{ 
                $('#'+target+'-img').attr('src', attachment.url);
            //}
            $('#'+target+'-img-url').attr('value', attachment.url);
            $('#'+target+'-img-id').val(attachment.id);
        });
        // Finally, open the modal on click
        wcmp_media_frame.open();
    });
})(jQuery);