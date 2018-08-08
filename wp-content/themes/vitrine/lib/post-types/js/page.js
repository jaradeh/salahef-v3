(function ($) {


	function ImageFields()
    {
        var $imageSec    = $('.section-page-type-switch2'),
            $fields      = $imageSec.find('.text-input '),
            $dupBtn      = $('<a class="duplicate-button" href="#">Add Image</a>'),
            $remBtn      = $('<a class="remove-button" href="#">Remove</a>');

        //Click handler for remove button
        $remBtn.click(function(e){
            e.preventDefault();

            var $this = $(this);

            $this.parent().remove();

            $fields = $imageSec.find('.text-input');

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
            $fields        = $imageSec.find('.text-input ');
            var $lastField = $fields.filter(':last'),
                $clone     = $lastField.clone(true);

            //Clear the value (if any)
            $clone.find('input[type="text"]').val('');

            $lastField.after($clone);

            //Refresh
            $fields        = $imageSec.find('.text-input ');
            //Add 'remove' button to all fields
            //Rest of 'remove' buttons will get cloned
            if($fields.length == 2)
                $fields.append($remBtn.clone(true));
        });
    }


    function PageMenuSections()
    {
        var $menu  = $('select[name="menu"]'),
            $initialMenu    = $('.section.section-initial-menu-color'),
            $secondMenu = $('.section.section-menu-color');
            
        function changeHandler()
        {
            var selected = $menu.find(':selected').val();

            if('custom' == selected)
            {

                $initialMenu.slideDown('fast');
                $initialMenu.next('hr').slideDown('fast');
                $secondMenu.slideDown('fast');
                $secondMenu.next('hr').slideDown('fast');
                
            }
            else {
            
                $initialMenu.slideUp('fast');
                $initialMenu.next('hr').slideUp('fast');
                $secondMenu.slideUp('fast');
                $secondMenu.next('hr').slideUp('fast');
            }
        }

        $menu.change(changeHandler);
        changeHandler();
    }

    function PageTemplateSections()
    {
        var $templates  = $('select#page_template'),
            $blogMetaBox    = $('#blog_meta_box'),
            $postdivrich = $('#postdivrich'),
            $vcEditor = $('#wpb_visual_composer');
			
        function changeHandler()
        {
            var selected = $templates.find(':selected').val();

            if('main-page.php' == selected)
            {

				setTimeout(function() {
					$postdivrich.addClass('hiddeneditor');
					$vcEditor.addClass('hiddeneditor');
                    $('#poststuff .composer-switch').addClass('hide-box');
					$('#snap_to_scroll_meta_box').addClass('hide-box');
				},200);
				$blogMetaBox.slideUp('fast');
				
            }
            else {
			
			     $('#snap_to_scroll_meta_box').removeClass('hide-box');
				var $container = $('.ep-main'),
					$pageType = $container.find('select[name="page-type-switch"]'),  
					$selected = $pageType.find('option:selected'),
					val = $selected.val();
			
				if ( val === 'blog-section')  {
					$postdivrich.addClass('hiddeneditor');
					$vcEditor.addClass('hiddeneditor');
					$('#poststuff .composer-switch').addClass('hide-box');
					$blogMetaBox.slideDown('fast');
				} else {
					$postdivrich.removeClass('hiddeneditor');
					$vcEditor.removeClass('hiddeneditor');
					$('#poststuff .composer-switch').removeClass('hide-box');
					$(window).scrollTop($(window).scrollTop()+10);//trick to fix bug of editor - when editor shown again, the editor content was disorganized 
					$blogMetaBox.slideDown('fast');
				}
			
            }
        }

        $templates.change(changeHandler);
        changeHandler();
    }

	function headerType()
    {
        var $container = $('.ep-main'),
			$headerType = $container.find('select[name="header-type-switch"]'),
			$titleShow = $container.find('select[name="title-bar"]'),
			$headerSec = $container.find('.section-header-type-switch'),
			$titleSec = $container.find('.section-title-bar'),
			$titleColorSec = $container.find('.section-header-text-color'),
			$headerBgSec = $container.find('.section-header-background-image'),
			$customTitleSec = $container.find('#field-title-text,#field-subtitle-text').parent();
			
		//  Slide Up/Down Title Options
		$titleShow.change(function(){
			var $titleSelected = $titleShow.find('option:selected'),
			tVal = $titleSelected.val();
			
			if( tVal === "1" )
			{
				$customTitleSec.slideDown('fast');
			}
			else if ( tVal === "0" || tVal === "2" ) {
				$customTitleSec.slideUp('fast');
			}
		}).change();
		//End Slide Up/Down Title Options
		
		//  Slide Up/Down Title Options
		$headerType.change(function(){
			var hVal = $(this).find('option:selected').val();
			
			if( hVal === "1" || hVal === "2")
			{
				$titleSec.slideUp('fast').next('hr').hide();
				$headerBgSec.slideUp('fast').next('hr').hide();
				$titleColorSec.slideUp('fast');
				$headerSec.next('hr').hide();
			}
			else {
				$titleSec.slideDown('fast').next('hr').show();
				$headerBgSec.slideDown('fast').next('hr').show();
				$titleColorSec.slideDown('fast');
				$headerSec.next('hr').show();
			}
		}).change();
		//End Slide Up/Down Title Options
	}
	
	function pageType()
    {
        var $container = $('.ep-main'),
            $pageType = $container.find('select[name="page-type-switch"],select[name="page-position-switch"],select[name="blog-type-switch"]'),
            $sec = $container.find('.section-page-position-switch,.section-page-sidebar, .section-blog-sidebar ,.section-footer-widget-area,.section-revolutionslider,.section-footer-map,.section-parallax-options,.section-blog-type-switch,.section-Overlay-options,.section-video-options'),
			$postdivrich = $('#postdivrich'),
            $vcForm = $('#wpb_visual_composer'),
			$pagePositionType = $container.find('select[name="page-position-switch"]');
            $blogType = $container.find('select[name="blog-type-switch"]');
		
        $pageType.change(function(){
            var $selected = $pageType.find('option:selected'),
                val = $selected.val(),
                $vcbtn = $('#poststuff .composer-switch'),
                $selected = $container.find('.section-'+val),
				
				$positionSelected = $pagePositionType.find('option:selected'),
				positionVal = $positionSelected.val();
                
                // Blog Type option    Creative - Toggle
                $blogTypeSelected = $blogType.find('option:selected'),
				blogTypeVal = $blogTypeSelected.val();

				/*  Slide Up/Down Editor For Blog */ 
				if( val === 'blog-section')
				{
				    $postdivrich.addClass('hiddeneditor');
				    $vcForm.addClass('hiddeneditor');
				    $vcbtn.slideUp('fast');
				}
				else {
				    $postdivrich.removeClass('hiddeneditor');
				    $vcbtn.slideDown('fast');
				    $vcForm.removeClass('hiddeneditor');					
				}

				if ( val == 'custom-section' && positionVal == '1' )
				{
					$selected = $container.find('.section-page-position-switch');	
				}
				else if ( val == 'custom-section'  && positionVal == '0' )
				{
					$selected = $container.find('.section-page-position-switch,.section-page-sidebar,.section-footer-widget-area,.section-footer-map,.section-revolutionslider');
				}
				else if ( val == 'blog-section' && positionVal == '1' )
				{
					$selected = $container.find('.section-page-position-switch');	
				}
				else if (val == 'blog-section' && positionVal == '0' && blogTypeVal == '1') //blogTypeVal = 1 for clasic blog
				{
				    $selected = $container.find('.section-page-position-switch,.section-blog-sidebar , .section-blog-type-switch ,.section-footer-widget-area,.section-footer-map,.section-revolutionslider');
				}
				else if (val == 'blog-section' && positionVal == '0' && blogTypeVal == '0') //blogTypeVal = 1 for toggle blog
				{
				    $selected = $container.find('.section-page-position-switch,.section-blog-type-switch,.section-footer-widget-area,.section-footer-map,.section-revolutionslider');
				}
				
            $sec.not($selected).slideUp('fast').next('hr').hide();
            $selected.slideDown('fast').next('hr').show();
			
			$positionSelected.not($selected).slideUp('fast').next('hr').hide();
			$positionSelected.slideDown('fast').next('hr').show();
			
        }).change();

    }
	
	function colorInput() {
	    if (!$.fn.wpColorPicker)
            return;
        
		$('#blog_meta_box .colorinput, #menu_meta_box .colorinput, #page_header_meta_box .colorinput').each(function () {
            $(this).wpColorPicker( { palettes : false});
        });
	
	}

    function topSlider() {
        var $container = $('#slider_meta_box'),
            $intro_types = $container.find('.intro_type');
            

        $intro_types.find('a').on('click',function(){
            $intro_types.find('a').removeClass('selected');
            $(this).addClass('selected');
            intro_typ_change();
        });

        intro_typ_change();

        // Home type  
        function intro_typ_change() {
            var $selected = $container.find('a.selected');

            if ( $selected.hasClass('slider-revolutionSlider') ) {
                $(".section-slider-revolutionSlider").slideDown('fast').next('hr').show();
                $(".section-epico-slider").slideUp('fast').next('hr').hide();
                $(".section-epico-slider-overlay").slideUp('fast').next('hr').hide();
                $(".section-slider-start-btn").slideUp('fast').next('hr').hide();
                $("input[name='rev-slider-container']").closest(".field").slideDown('fast');
            } else if($selected.hasClass('epico-slider')) {
                $(".section-slider-revolutionSlider").slideUp('fast').next('hr').hide();
                $(".section-epico-slider").slideDown('fast').next('hr').show();
                $(".section-epico-slider-overlay").slideDown('fast').next('hr').show();
                $(".section-slider-start-btn").slideDown('fast').next('hr').show();
                $("input[name='rev-slider-container']").closest(".field").slideUp('fast');
            }
            else
            {
                $(".section-slider-revolutionSlider").slideUp('fast').next('hr').hide();
                $(".section-epico-slider").slideUp('fast').next('hr').hide();
                $(".section-epico-slider-overlay").slideDown('fast').next('hr').show();
                $(".section-slider-start-btn").slideDown('fast').next('hr').show();     
            }
        };

    }


    function ColorPicker() {
        if (!$.fn.wpColorPicker)
            return;

        $('#slider_meta_box .colorinput').each(function () {
            $(this).wpColorPicker( { palettes : false});
        });

    }

    function snap_to_scroll_dependencies() {
        var $snap_to_scroll = $('input[name="snap-to-scroll"]').siblings('.switch'),
            $top_slider = $('#slider_meta_box');


        function toggle_slider_section() {

            if(Math.round( $snap_to_scroll.val()) == 0)
            {
                $top_slider.show();
            }
            else
            {
                $top_slider.hide();
            }
        }

        $snap_to_scroll.on('change', toggle_slider_section);
        toggle_slider_section();

    }


    $(document).ready(function () {
        PageTemplateSections();
        PageMenuSections();
		ImageFields();
		headerType();
		pageType();
		colorInput();
		topSlider();
		ColorPicker();
        snap_to_scroll_dependencies()
		setTimeout(function(){
			if($('#wpb_visual_composer').css('display') === undefined || $('#wpb_visual_composer').css('display') == 'none') {
				$('.composer-switch .wpb_switch-to-composer').trigger('click');
			}
		},100);
		
    });

})(jQuery);