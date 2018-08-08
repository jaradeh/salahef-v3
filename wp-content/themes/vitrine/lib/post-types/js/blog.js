(function ($) {

    function ImageFields()
    {
        var $imageSec    = $('.section-gallery'),
            $fields      = $imageSec.find('.upload-field'),
            $dupBtn      = $('<a class="duplicate-button" href="#">Add Image</a>'),
            $remBtn      = $('<span class="remove-button"><a class=" close" href="#"><span class="close-icon"></span></a></span>');

        //Click handler for remove button
        $remBtn.click(function(e){
            e.preventDefault();

            var $this = $(this);

            $this.parent().remove();

            $fields = $imageSec.find('.upload-field');

            if($fields.length < 2)
            //Remove the button
                $fields.find('.remove-button').remove();
        });


        //Add remove button if there is more than one image field
        if($fields.length > 1)
            $fields.append($remBtn.clone(true));

        //Add duplicate button after last upload field
        $fields.filter(':last').after($dupBtn);

        $dupBtn.click(function(e){
            e.preventDefault();

            //Don't try to reuse $fields var above ;)
            $fields        = $imageSec.find('.upload-field');
            var $lastField = $fields.filter(':last'),
                $clone     = $lastField.clone(true);

            //Clear the value (if any)
            $clone.find('input[type="text"]').val('');
            $clone.find('.upload-thumb').removeClass('show');
            $clone.find('img').attr('src','');

            $lastField.after($clone);

            //Refresh
            $fields        = $imageSec.find('.upload-field');
            //Add 'remove' button to all fields
            //Rest of 'remove' buttons will get cloned
            if($fields.length == 2)
                $fields.append($remBtn.clone(true));
        });
    }

    function MediaType()
    {
        var $container = $('.ep-main'),

            /* Show default options for standard type, this section has nothing to do with click on post types this is the forst state*/
            $mediaType = $container.find('.section-media .imageList a.selected'),
            $mediaTypeVal = $mediaType.text();
            $sec       = $container.find('.section-gallery,.section-video,.section-audio,.section-quote');

            $selected = $container.find('.section-' + $mediaTypeVal);

            if ($mediaTypeVal == 'video_gallery') {
                $selected = $container.find('.section-video,.section-gallery');
            }
            else if ($mediaTypeVal == 'audio_gallery') {
                $selected = $container.find('.section-audio,.section-gallery');
            }
         
            $sec.not($selected).slideUp('fast').next('hr').hide();;
            $selected.slideDown('fast').next('hr').show();


        /* Show options based on user selected type*/
        $(document).on('click', '.section-media .imageList a', function () {
            
            //remove classes necessary for type quote
			$('#postdivrich').removeClass('hiddeneditor');
			$('#wpb_visual_composer').removeClass('hiddeneditor');
			$('#poststuff .composer-switch').removeClass('hide-box');

            var $select = $(this),
            val = $(this).text();

            $selected = $container.find('.section-' + val);

            if (val == 'video_gallery') {
                $selected = $container.find('.section-video,.section-gallery');
            }
            else if (val == 'audio_gallery') {
                $selected = $container.find('.section-audio,.section-gallery');
            }
            else if (val == 'quote') {
                $selected = $container.find('.section-quote')

            }

            $sec.not($selected).slideUp('fast').next('hr').hide();;
            $selected.slideDown('fast').next('hr').show();

        });

    }
	
    function post_social_share_dependencies()
    {
        var $post_social_share_inherit = $('input[name="social_share_inherit"]').siblings('.switch'),
            $post_social_share_display = $post_social_share_inherit.closest('.field').next('.field');

        function toggle_post_social_share_section() {

            if(Math.round( $post_social_share_inherit.val()) == 0)
            {
                $post_social_share_display.hide();
            }
            else
            {
                $post_social_share_display.show();
            }
        }

        $post_social_share_inherit.on('change', toggle_post_social_share_section);
        toggle_post_social_share_section();
    }
	
	
    $(document).ready(function () {
		$("#formatdiv").hide();
        ImageFields();
        MediaType();
		post_social_share_dependencies()
    });

})(jQuery);