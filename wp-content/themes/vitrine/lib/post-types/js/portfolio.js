(function ($) {

    function ImageFields() {
        var $imageSec = $('.section-gallery'),
            $fields = $imageSec.find('.upload-field'),
            $dupBtn = $('<a class="duplicate-button" href="#">Add Image</a>'),
            $remBtn = $('<span class="remove-button"><a class=" close" href="#"><span class="close-icon"></span></a></span>');

        //Click handler for remove button
        $remBtn.click(function (e) {
            e.preventDefault();

            var $this = $(this);

            $this.parent().remove();

            $fields = $imageSec.find('.upload-field');

            if ($fields.length < 2)
                //Remove the button
                $fields.find('.remove-button').remove();
        });


        //Add remove button if there is more than one image field
        if ($fields.length > 1)
            $fields.append($remBtn.clone(true));

        //Add duplicate button after last upload field
        $fields.filter(':last').after($dupBtn);

        $dupBtn.click(function (e) {
            e.preventDefault();

            //Don't try to reuse $fields var above ;)
            $fields = $imageSec.find('.upload-field');
            var $lastField = $fields.filter(':last'),
                $clone = $lastField.clone(true);

            //Clear the value (if any)
            $clone.find('input[type="text"]').val('');
            $clone.find('.upload-thumb').removeClass('show');
            $clone.find('img').attr('src','');

            $lastField.after($clone);

            //Refresh
            $fields = $imageSec.find('.upload-field');
            //Add 'remove' button to all fields
            //Rest of 'remove' buttons will get cloned
            if ($fields.length == 2)
                $fields.append($remBtn.clone(true));
        });
    }

    function AttributesFields() {

        for (i = 0; i < $(".attribute-value").length; i++) {
            $(".section-attribute").prepend($(".attribute-value")[i]);
            $(".section-attribute").prepend($(".attribute-title")[i]);
        }
        $(".section-attribute").prepend($(".section-attribute .section-head"));

        var $attributeSec = $('.section-attribute'),
            $fields = $attributeSec.find('.attribute-value'),
            $dupBtn = $('<a class="duplicate-button" href="#">Add Attribute</a>'),
            $remBtn = $('<a class="remove-button" href="#">Remove</a>');

        //Click handler for remove button
        $remBtn.click(function (e) {
            e.preventDefault();

            var $this = $(this);

            $this.parent().prev().remove();
            $this.parent().remove();

            $fields = $attributeSec.find('.attribute-value');

            if ($fields.length < 2)
                //Remove the button
                $fields.find('.remove-button').remove();
        });


        //Add remove button if there is more than one image field
        if ($fields.length > 1)
            $fields.append($remBtn.clone(true));

        //Add duplicate button after last upload field
        $fields.filter(':last').after($dupBtn);

        $dupBtn.click(function (e) {
            e.preventDefault();

            //Don't try to reuse $fields var above ;)
            $fields = $attributeSec.find('.attribute-value');
            var $lastField = $fields.filter(':last'),
                $clone2 = $lastField.prev().clone(true);
            $clone1 = $lastField.clone(true);

            //Clear the value (if any)
            $clone1.find('input[type="text"]').val('');
            $clone2.find('input[type="text"]').val('');

            $lastField.after($clone1);
            $lastField.after($clone2);

            //Refresh
            $fields = $attributeSec.find('.attribute-value');
            //Add 'remove' button to all fields
            //Rest of 'remove' buttons will get cloned
            if ($fields.length == 2)
                $fields.append($remBtn.clone(true));
        });
    }

    function PostFormats() {
        var $formats = $('input[name="post_format"]'),
            $metaBox = $('.ep-main'),
            $metaBoxParent = $metaBox.parents('.postbox').eq(0),
            $sections = $metaBox.find('.section')
            $titleShow = $metaBox.find('select[name="title-bar"]'),
            $titleSec = $metaBox.find('#field-title-text,#field-subtitle-text').parent();

        //  Slide Up/Down Title Options
        $titleShow.change(function () {
            var $titleSelected = $titleShow.find('option:selected'),
	        tVal = $titleSelected.val();

            if (tVal === "1") {
                $titleSec.slideDown('fast');
            }
            else if (tVal === "0" || tVal === "2") {
                $titleSec.slideUp('');
            }
        }).change();

        function changeHandler() {
            var selected = $formats.filter(':checked').val(),
                $titleBar = $metaBox.find('.section-title-bar'),
				$featureSize = $metaBox.find('.section-featured-size'),
                $portfolioDetailStyle = $metaBox.find('.section-portfolio-detail-style'),
                $attribute = $metaBox.find('.section-attribute'),
                $pSocailShare = $metaBox.find('.section-portfolio-social-share'),
                $ProjectDetails = $metaBox.find('.section-project-title'),
                $GalleryExternalLink = $metaBox.find('.section-gallery-external-link'),


                $composerSwitch = $('.composer-switch');

                $postdivrich = $('#postdivrich');
                $visualComposerdiv = $('#wpb_visual_composer');

                $secExtraClass = $metaBox.find('.section-extra_class');

			
			// Changing the name of standard type post format in portfolio to Gallery	
			$('label.post-format-icon.post-format-standard').text('Gallery');

			if(selected=='0'){ //If the format of portfolio detail was standard
			    var $sec = $metaBox.find('.section-gallery');//Keep the gallery section and hide the rest
			}else{
			    var $sec = $metaBox.find('.section-' + selected);
			}

            if (selected == "link") {
                $visualComposerdiv.slideUp('fast');
                $composerSwitch.addClass('hideComposerSwitch');
                $postdivrich.slideUp('fast'); // if link Post Format click WordPress text Editor hide.
            } else {
                $composerSwitch.removeClass('hideComposerSwitch');
                if($composerSwitch.hasClass('vc_backend-status'))
                    $visualComposerdiv.slideDown('fast');
                else
                    $postdivrich.slideDown('fast');

                $(window).scrollTop($(window).scrollTop()+10);//trick to fix bug of editor - when editor shown again, the editor content was disorganized
            }

            $sections.not($sec).not($secExtraClass).slideUp('fast').next('hr').hide();
            $sec.add($titleBar).add($featureSize).add($GalleryExternalLink).add($portfolioDetailStyle).add($attribute).add($pSocailShare).add($ProjectDetails).slideDown('fast').next('hr').show();
        }

        $formats.change(changeHandler);
        changeHandler();
    }

    function portfolioDetailType()
    {
        $("div.section-portfolio-detail-style .imageList a").on("click",function(){
            if($(this).hasClass("portfolio_detail_creative"))
                $("div.section-attribute").slideUp('fast').next("hr").hide();
            else
                $("div.section-attribute").slideDown('fast').next("hr").show();
        });

        if($("div.section-portfolio-detail-style .imageList a.selected").hasClass("portfolio_detail_creative"))
            $("div.section-attribute").slideUp('fast').next("hr").hide();
        else
            $("div.section-attribute").slideDown('fast').next("hr").show();
    }
	
    function portfolio_social_share_dependencies()
    {
        var $portfolio_social_share_inherit = $('input[name="social_share_inherit"]').siblings('.switch'),
            $portfolio_social_share_display = $portfolio_social_share_inherit.closest('.field').next('.field');

        function toggle_portfolio_social_share_section() {

            if(Math.round( $portfolio_social_share_inherit.val()) == 0)
            {
                $portfolio_social_share_display.hide();
            }
            else
            {
                $portfolio_social_share_display.show();
            }
        }

        $portfolio_social_share_inherit.on('change', toggle_portfolio_social_share_section);
        toggle_portfolio_social_share_section();
    }

    $(document).ready(function () {
        ImageFields();
        AttributesFields();
        PostFormats();
        portfolioDetailType();
		portfolio_social_share_dependencies();
    });

})(jQuery);