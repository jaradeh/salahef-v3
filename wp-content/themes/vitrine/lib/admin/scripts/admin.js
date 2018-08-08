(function ($) {

    var utility = {
        //Checks if element as desired attribute
        HasAttr: function ($elm, attr) {
            return typeof $elm.attr(attr) != 'undefined';
        },
        GetAttr: function ($elm, attr, def) {
            return this.HasAttr($elm, attr) ? $elm.attr(attr) : def;
        }
    };

    //Show/hide fields based on selected value
    function FieldSelector() {
        $('.field-selector select').each(function () {
            var $select = $(this),
                $section = $select.parents('.section'),
                fieldList = utility.GetAttr($select, 'data-fields', ''),
                $fields = $section.find(fieldList);

            $select.change(function () {
                var $selected = $select.find('option:selected');

                if (!utility.HasAttr($selected, 'data-show')) {
                    $fields.slideUp('fast');
                    return;
                }

                var show = $selected.attr('data-show'),
                    $items = $section.find(show);

                $fields.not($items).slideUp('fast');
                $items.slideDown('fast');
            }).change();
        });
    }

    //Handles icon selector
    function iconSelect() {
        var iconSelectFunc = function() {
            var $ep_icons_container = $('.ep-icon-container').eq(0).clone();
            $ep_icons_container.addClass('ep-container-popup');
            $ep_icons_container.find('input,.selected-icon,.select-icon-text').remove();
            $('.ep-icons').remove();
            $('body').append($ep_icons_container);

            $(document).on('click','.select-icon-text',function() {
                var $this = $(this);

                if($('.ep-container-popup').length <= 0)
                {
                    $ep_icons_container = $('.ep-icon-container').eq(0).clone();
                    $ep_icons_container.addClass('ep-container-popup');
                    $ep_icons_container.find('input,.selected-icon,.select-icon-text').remove();
                    $('.ep-icons').remove();
                    $('body').append($ep_icons_container);
                }

                var $iconInput = $this.siblings('input'),
                    $SelectediconBox = $this.siblings('.selected-icon');

                $ep_icons_container.find('.ep-icon.selected').removeClass('selected');

                if ($iconInput.attr('value') !== '') {

                    $ep_icons_container.find('.ep-icon[data-name=' + $iconInput.attr('value') + ']').addClass('selected');
                    setTimeout(function(){
                        //Scroll iconContainer to show the select icon
                        if ($ep_icons_container.find('.selected').length) {
                            var $ep_icons = $ep_icons_container.find('.ep-icons'),
                                $offset = $ep_icons.scrollTop() - $ep_icons.offset().top + $ep_icons.find('.selected').offset().top ;

                            if ($offset > 0) {
                                $ep_icons.stop().animate({
                                    scrollTop: $offset + "px"
                                }, 200);
                            }
                        }
                    },100)

                }

                $SelectediconBox.addClass('icon_container_owner');
                $ep_icons_container.addClass('show');


            });

            $(document).on('click','.ep-container-popup .ep-icon', function () {
                var $this = $(this),
                    $icon_owner = $('.icon_container_owner'),
                    $iconInput = $icon_owner.siblings('input');

                if ($this.is('.selected')) {
                    $this.removeClass('selected');
                    $iconInput.attr('value', '');
                    $icon_owner.removeClass('icon-' + $icon_owner.data('name'));
                    $icon_owner.data('name','');
                }
                else {
                    $iconInput.attr('value', $(this).attr('data-name'));
                    $this.siblings('.ep-icon').removeClass('selected');
                    
                    $icon_owner.removeClass('icon-' + $icon_owner.data('name'));
                    $icon_owner.addClass('icon-' + $(this).attr('data-name'));
                    $icon_owner.data('name',$(this).attr('data-name'));
                    $this.addClass('selected');
                }

            });

            $(document).on('click','.ep-icons .close,.ep-container-popup', function () {
                var $icon_owner = $('.icon_container_owner');

                $icon_owner.removeClass('icon_container_owner');
                $ep_icons_container.removeClass('show');
            });

        }

        iconSelectFunc();
        
    }

    function CSVInput() {

        $('.csv-input').each(function () {
            var $container = $(this),
                $hidden = $container.find('input[type="hidden"]'),
                $input = $container.find('input[type="text"]'),
                $addBtn = $container.find('.btn-add'),
                $list = $container.find('.list');

            var values = $hidden.val().length > 0 ? $hidden.val().split(',') : [];

            //Add current items to our list
            for (i = 0; i < values.length; i++) {
                var val = values[i],
                    text = val.replace('%666', ','),//Evil char 
                    $item = GetNewItem(val, text);

                $list.append($item);
                HandleCloseBtn($item);
            }

            AssembleList();

            //Handle add button
            $addBtn.click(function (e) {
                e.preventDefault();

                var val = $input.val();
                val = $.trim(val);
                $input.val('');//Clear

                if (val.length < 1)
                    return;

                var $item = GetNewItem(val.replace(",", "%666"), val);
                HandleCloseBtn($item);
                $item.hide();

                $list.prepend($item);

                AssembleList();

                $item.slideDown('fast', function () { $(window).resize(); });
            });

            function AssembleList() {
                $hidden.val('');//Clear the current list
                var vals = [];

                $list.find('.value').each(function () {
                    var value = $(this).attr('data-val');
                    vals.push(value);
                });

                $hidden.val(vals.join(','));
            }

            function HandleCloseBtn($item) {
                //Remove item on click
                $item.find('.btn-close').click(function (e) {
                    e.preventDefault();

                    $item.slideUp('fast', function () { $item.remove(); AssembleList(); $(window).resize(); });
                });
            }

            function GetNewItem(val, text) {
                return $('<div class="value" data-val="' + val + '"><span>' + text + '</span><a href="#" class="btn-close"></a></div>');
            }

        });


    }

    function ImageSelect() {
        var $controls = $('.imageSelect');

        $controls.each(function () {
            var $select = $(this),
                $input = $select.find('input'),
                $options = $select.find('a');

            if( !$select.find('.selected').length )
            {
                $select.find('a').eq(0).addClass('selected');
                $input.val($select.find('a').eq(0).html());
            }
            

            //Hide input control
            $input.hide();

            $options.click(function (e) {
                e.preventDefault();

                var $ctl = $(this);

                if ($ctl.hasClass('selected'))
                    return;

                $options.removeClass('selected');
                $ctl.addClass('selected');

                $input.val($ctl.html());
            });
        });

       function advanced_image_select() {
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

                $(document).on('click', '.ep-image',function () {
                   
                    if(!$(this).hasClass('selected'))
                    {
                        $(this).closest('span').siblings('input').val($(this).attr('data-name'));
                        $(this).siblings('.ep-image').removeClass('selected');
                        $(this).addClass('selected');
                    }
                    $input.trigger( "change" );
                });

            });
        }

        advanced_image_select();
    }

    function Chosen() {
        if (!$.fn.chosen)
            return;

        $('.chosen').chosen();
    }

    function Combobox() {
        $('.select').each(function () {
            var $this = $(this),
                $overlay = $this.find('div'),
                $select = $this.find('select');

            $select.change(function () {
                $overlay.html($select.find('option:selected').text());
            });

            $select.change();
        });
    }

    function ColorPicker() {
        if (!$.fn.wpColorPicker)
            return;

        $('#appearance .colorinput, #preloader .colorinput , #header .colorinput , #menu .colorinput , #headerstyle .colorinput , #headerStartBtn .colorinput , #footer .colorinput , #notification .colorinput, #social .colorinput , #topbar .colorinput , #woocomerce .colorinput, .epico-widget-attributes-table .colorinput, .widget-insta.colorinput,#menu-management ul.menu li .colorinput, .taxonomy-product_cat .colorinput').each(function () {
            $(this).wpColorPicker( { palettes : false});
        });

    }

    function Sliders() {
        if (!$.fn.noUiSlider)
            return;

        var $sliders = $('input[type="range"]');

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


            //Set label
            $parent.find('.label').prepend($label);

            if ('value' in this.attributes)
                $label.html(this.attributes['value'].value);

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

                setupState = false;
            }

            var $midbar = $slider.find('.noUi-midBar'),
                left = $midbar.css('left'),
                right = $midbar.css('right');

            if (left == '0px' && right == '0px' && $slider.val() != max) {
                $midbar.css({ right: $this.width() });
            }

            var $sliderHandle = $slider.find('.noUi-handle');


            if (isSwitch) {

                Handle_Change();
            }


        });

    }

    function Tooltips() {


        $('.section-tooltip').each(function () {
            var $this = $(this),
                text = $this.html(),
                $icon = $('<a href="#"></a>'),
                $wrap = $('<div class="tip_wrapper"><div class="text">' + text + '</div><div class="arrow_shade"></div><div class="arrow"></div></div>');

            $this.html('');
            $this.append($icon);
            $this.append($wrap);
            $wrap.css({ opacity: 0, display: 'none' });

            function Adjust_Tooltip() {
                $wrap.css({ right: 0, top: -(($wrap.outerHeight() - $icon.outerHeight() * 0.5) + 15) });
            }

            Adjust_Tooltip();

            $icon.click(function (e) {
                e.preventDefault();
            });

            if ($.fn.hoverIntent)
                $this.hoverIntent(InHandler, OutHandler);
            else
                $this.hover(InHandler, OutHandler);

            function InHandler() {
                $wrap.css({ display: 'block' });
                Adjust_Tooltip();
                $wrap.stop().animate({ opacity: 1 }, 200);
            }

            function OutHandler() {
                $wrap.stop().animate({ opacity: 0, }, { duration: 200, complete: function () { $wrap.css({ display: 'none' }); } });
            }

        });

    }

    function Save_Button() {
        var $btns = $('.ep-main .save-button'),
            $loadingIcons = $btns.find('.loading-icon'),
            $saveIcons = $btns.find('.save-icon'),
            $form = $('.ep-container'),
            $dummyData = $('.ep-main input[name="import_dummy_data"]');

        $btns.click(function (e) {
            var $btn = $(this);

            if ($btn.hasClass('loading')) {
                e.preventDefault();
                return;
            }

            var data = $form.find('input,textarea,select').serialize();

            $loadingIcons.css({ display: 'inline' });
            $saveIcons.hide();

            $btns.addClass('loading');


            //Todo: Save the settings
            //Test ajax call
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: data,
                success: function (data, textStatus, jqXHR) {
                    //TODO: Show proper saved message
                    OnSaveComplete();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    OnSaveComplete();

                    alert('Error occured in saving data');
                }
            });

            function OnSaveComplete() {
                $loadingIcons.hide();
                $saveIcons.css({ display: 'inline' });
                $btns.removeClass('loading');

                //Reload page if import dummy data option is selected
                if ($dummyData.length && $dummyData.val() == '1')
                    document.location.reload(true);
            }

            e.preventDefault();
        });


    }

    function Tabs() {
        var $tabs = $('.ep-tab a'),
            $active = $();

        $tabs.each(function () {
            var $this = $(this),
                href = $this.attr('href'),
                $container = $(href);

            $this.click(function (e) {
                e.preventDefault();

                if ($this.hasClass('active'))
                    return;

                $tabs.removeClass('active');
                $this.addClass('active');

                $active.fadeOut(100);
                $container.fadeIn(400);

                $active = $container;

                $(window).resize();
            });

            if ($this.hasClass('active')) {
                $this.removeClass('active');
                $this.click();
                $active = $container;
            }
            else {
                $container.fadeOut(100);
            }

        });

    }

    function Sidebar_Accordion() {
        var $panels = $('#ep-sidebar-accordion > div'),
            $head = $('#ep-sidebar-accordion > h3 a');

        $panels.hide();

        var $active = $('#ep-sidebar-accordion > h3 a.active'),
            $target = $();

        if ($active.length > 0) {
            $target = $active.parent().next();
            $target.show();
        }


        $head.click(function (e) {
            var $this = $(this);

            $target = $this.parent().next();

            if (!$this.hasClass('active')) {
                var $prev = $('#ep-sidebar-accordion > h3 a.active').parent().next();

                $head.removeClass('active');

                $prev.slideUp('slow', 'easeOutQuad');
                $target.slideDown('slow', 'easeOutQuad');
                $this.addClass('active');

            }

            e.preventDefault();
        });
    }

    function Thickbox() {
        var $currentField = $();
        var $imageField = $();
        var $imageFieldContainer = $();

        $(document).on('click','.upload-field .upload-button',function(e){
            var $this = $(this),
                $parent = $this.parent(),
                referer = 'ep-settings',
                title = 'Upload';

            if ($parent.attr('data-referer') !== undefined)
                referer = $parent.attr('data-referer');

            if ($parent.attr('data-title') !== undefined)
                title = $parent.attr('data-title');

            $currentField = $(this).prev();
            $imageField = $(this).siblings('.upload-thumb').find('img');
            $imageFieldContainer = $(this).siblings('.upload-thumb');

            var $pid;
                

            if ($('#post_ID').length <= 0) {
                $pid = $(this).parents('li.menu-item').find("input.menu-item-data-object-id");//Find ID of menu item
            } else {
                $pid = $('#post_ID');//Find ID of post type (post, page , ...)
            }

            var postId = $pid.length > 0 ? $pid.val() : '0';


            tb_show(title, 'media-upload.php?post_id=' + postId + '&referer=' + referer + '&type=image&TB_iframe=true', false);

            e.preventDefault();
        });

        $(document).on('click','.upload-thumb .close',function (e) {
            $(this).parents('.upload-thumb').removeClass("show");
            $(this).parents('.upload-field').find('input').val('');
        });

        var orig_send_to_editor = window.send_to_editor;

        window.send_to_editor = function (html) {
            if ($currentField.length) {
                var image_url = $(html).attr('href');

                //Find image id for using in upload field of product attribute
                //below code add becouse some host return a tag around img dom
                if ($(html).attr('class')) {
                    var image_url = $(html).attr('href');
                    var classes = $(html).attr('class').split(' ').filter(function (classname) { return (classname.indexOf('wp-image-') == 0); });
                } else {
                    var image_url = $(html).find('img').attr('src');
                    var classes = $(html).find('img').attr('class').split(' ').filter(function (classname) { return (classname.indexOf('wp-image-') == 0); });
                }

                var imageID = classes[0].replace('wp-image-','');
                if(imageID != undefined) {
                    $currentField.closest('.field-container').find('input[name^="attribute_extra_values"]').val(imageID);
                }

                if(image_url == undefined)
                {
                    image_url = $(html).attr('src');
                }
                $currentField.val(image_url);
                $imageField.attr({ 'src': image_url });

                if ((image_url).length) {
                    $imageFieldContainer.addClass('show');
                }

                $imageField = $();
                $currentField = $();
                tb_remove();
            }
            else {
                if (typeof orig_send_to_editor != 'undefined')
                    orig_send_to_editor(html);
            }
        }
    }

    function ImageFields() {
        var $imageSec = $('.section-home-slides'),
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

    function preloader() {

        var $preloaderTypeSelected = $('.section-preloader-type .imageSelect a.selected');
        $preloaderTypeSelected = $preloaderTypeSelected.text();

        $preloadertext = $('.section-preloader-text');
        $preloaderlogo = $('.section-preloader-logo');
        $preloaderboxcolor = $('.section-preloader_color').find('.field').eq(1);

        if ($preloaderTypeSelected == 'creative') {
            $preloadertext.add($preloaderlogo).add($preloaderboxcolor).slideDown('fast');
        } else {
            $preloadertext.add($preloaderlogo).add($preloaderboxcolor).slideUp('fast').show();
            $('.section-preloader-text').add($preloaderboxcolor).next('hr').hide();
        }

        $(document).on('click', '.section-preloader-type .imageSelect a', function () {

            var $this = $(this);
            $preloaderType = $this.text();

            if ($preloaderType == "creative") {
                $preloadertext.add($preloaderlogo).add($preloaderboxcolor).slideDown('fast');
                $('.section-preloader-text').next('hr').show();
            } else {
                $preloadertext.add($preloaderlogo).add($preloaderboxcolor).slideUp('fast');
                $('.section-preloader-text').next('hr').hide();
            }

        });

    }

    function menu() {

        var $container = $('#menu'),

            // Header Position 
            $headerPosition = $container.find('.section-header-type .imageList a.selected'),
            $headerPositionVal = $headerPosition.text();

            //HeaderTop Style
            $HeadertopStyle = $container.find('.section-header-style .imageList a.selected'),
            $HeadertopStyleVal = $HeadertopStyle.text();
            
            // Header Color 
            $HeaderColor = $container.find('.section-menu-color');
            // Header intial Color  - Only Hybrid menu 
            $HeaderTopIntialColor = $container.find('.section-initial-menu-color');
            // Header intial Logo  - Only Hybrid menu 
            $intialLogo = $container.find('.section-logo-second');
            // Header top Style 
            $headerStyle = $container.find('.section-header-style');
            // Header Top Hover Style
            $HeaderTopHoverStyle = $container.find('.section-menu-hover-style');
            // Header submenu Hover Style
            $HeaderSubmenuHoverStyle = $container.find('.section-submenu-hover-style');
            // Header menu container  styles container Or Fullwidth            
            $HeaderContainerStyle = $container.find('.section-menu-container');
            // Menu vertical background Image
            $section_vertical_menu_background = $container.find('.section-vertical_menu_background');
            // menu vertical - copyright text 
            $section_vertical_menu_copyright = $container.find('.section-vertical_menu_copyright');
            $section_vertical_menu_social = $container.find('.section-vertical-menu-social-display');
            // submenu color - mega menu Option Disable in sidebars menu
            $submenu_color = $container.find('.section-submenu-color');
            
        if ($headerPositionVal !== '7' && $headerPositionVal !== '8') {  // 7 , 8 => Header is Top 

            // slid Up Menu vertical background Image
            $section_vertical_menu_background.add($section_vertical_menu_copyright).slideUp('fast').next('hr').hide();
            $section_vertical_menu_social.slideUp('fast').next('hr').hide();
            $headerStyle.add($HeaderTopHoverStyle).add($HeaderContainerStyle).add($submenu_color).add($HeaderSubmenuHoverStyle).slideDown('fast').next('hr').show();
            $(".section-menu-color").find('.field').eq(3).show(); // Hide opacity Option in left and Right menu
            $(".section-menu-color").find('.field.menu-opacity').hide();// this feild is hidden in top menus
            $(".section-menu-color").find('.field.border-color').show();// this feild is visible in top menus 

            // Slide up intail Color panel When Epico Menu ( hybrid Menu ) is not selected
            if ($HeadertopStyleVal == "epico-menu") {

                $HeaderColor.slideDown('fast').next('hr').show();
                $intialLogo.slideDown('fast').next('hr').show();
            
            } else if ($HeadertopStyleVal == "scroll-sticky") {

                $HeaderColor.slideUp('fast').next('hr').hide();
                $intialLogo.slideUp('fast').next('hr').hide();

            } else {

                $HeaderColor.slideUp('fast').next('hr').hide();
                $intialLogo.slideUp('fast').next('hr').hide();
            }                                    

        } else if ( $headerPositionVal == '7' || $headerPositionVal == '8') { // header is left or Right 

            $headerStyle.add($HeaderTopHoverStyle).add($HeaderTopIntialColor).add($HeaderContainerStyle).add($HeaderSubmenuHoverStyle).add($submenu_color).slideUp('fast').next('hr').hide();
            $section_vertical_menu_background.add($section_vertical_menu_copyright).slideDown('fast').next('hr').show();
            $section_vertical_menu_social.slideDown('fast').next('hr').show();

            $HeaderColor.slideDown('fast'); // slide down color

            $(".section-menu-color").find('.field.menu-opacity').show();// this feild is visible in vetical menu 
            $(".section-menu-color").find('.field.border-color').hide();// this feild is hidden in vetical menu

            $(".section-menu-color").find('.field').eq(2).show();// this feild visible in vetical menu 
            $(".section-menu-color").find('.field').eq(3).show();// this feild visible in vetical menu
            $HeaderTopIntialColor.slideUp('fast').next('hr').hide(); // intial header slide up 
            $intialLogo.slideUp('fast').next('hr').hide();//intial logo
            $HeaderTopHoverStyle.slideUp('fast').next('hr').hide(); // Top hover Style slide Up 
            //$(".section-menu-color").find('.field').eq(4).hide(); // Hide opacity Option in left and Right menu

        }

        // Menu hover style
        var menu_hover_style = function() {
            var $selected = $('.menu-hover-style .imageList a.selected');
            var $menu_hover_color = $('.menu-hover-color');
            var $menu_bg_hover_color = $('.menu-bg-hover-color');



            if($selected.hasClass('hover_style4') || $selected.hasClass('hover_style3')) {

                if($selected.hasClass('hover_style3')) {
                    $menu_bg_hover_color.fadeOut(100);
                    $menu_hover_color.fadeIn(100);
                }
                else
                {
                    $menu_hover_color.add($menu_bg_hover_color).fadeOut(100);
                }
            }
            else
            {
                $menu_hover_color.add($menu_bg_hover_color).fadeIn(100);
            }
        }
        menu_hover_style();
        $(document).on('click', '.menu-hover-style .imageList a', menu_hover_style);

        // Menu Position
        $(document).on('click', '.section-header-type .imageList a', function () {
            
            var $select = $(this),
            $headerPositionVal = parseInt($(this).text());

            //HeaderTop Style
            $HeadertopStyle = $container.find('.section-header-style .imageList a.selected'),
            $HeadertopStyleVal = $HeadertopStyle.text();

            
            if ($headerPositionVal == '7' || $headerPositionVal == '8') { // header is left or Right 

                $headerStyle.add($HeaderTopHoverStyle).add($HeaderContainerStyle).add($submenu_color).add($HeaderSubmenuHoverStyle).slideUp('fast').next('hr').hide();
                $section_vertical_menu_background.add($section_vertical_menu_copyright).slideDown('fast').next('hr').show();
                $section_vertical_menu_social.slideDown('fast').next('hr').show();
                $HeaderColor.slideDown('fast'); // slid down color
                $HeaderTopIntialColor.slideUp('fast').next('hr').hide(); // intial header slide up 
                $intialLogo.slideUp('fast').next('hr').hide();//intial logo
                $HeaderTopHoverStyle.slideUp('fast').next('hr').hide(); // Top hover Style slide Up 
                //$(".section-menu-color").find('.field').eq(4).hide(); // Hide opacity Option in left and Right menu

                $(".section-menu-color").find('.field.menu-opacity').show();// this feild is visible in vetical menu 
                $(".section-menu-color").find('.field.border-color').hide();// this feild is hidden in vetical menu

            } else if ( $headerPositionVal !== '7' && $headerPositionVal !== '8' ) { // 7 , 8 => Header is't Top

                // Slide up intail Color panel When Epico Menu ( hybrid Menu ) is not selected
                if ($HeadertopStyleVal == "epico-menu") {

                    $HeaderTopIntialColor.slideDown('fast').next('hr').show();
                    $intialLogo.slideDown('fast').next('hr').show();

                } else if ($HeadertopStyleVal == "scroll-sticky") { // scroll to sticky menu

                    $HeaderTopIntialColor.slideDown('fast').next('hr').show();
                    $intialLogo.slideUp('fast').next('hr').hide();
                    $HeaderColor.slideUp('fast').next('hr').hide();

                } else { // fixed menu

                    $HeaderTopIntialColor.slideDown('fast').next('hr').show();
                    $HeaderColor.slideUp('fast').next('hr').hide();

                }
                $section_vertical_menu_social.slideUp('fast').next('hr').hide();
                $(".section-menu-color").find('.field').eq(3).show(); // Hide opacity Option in left and Right menu
                $headerStyle.add($HeaderTopHoverStyle).add($HeaderSubmenuHoverStyle).add($HeaderContainerStyle).add($submenu_color).slideDown('fast').next('hr').show();
                $section_vertical_menu_background.add($section_vertical_menu_copyright).slideUp('fast').next('hr').hide();

                $(".section-menu-color").find('.field.menu-opacity').hide();// this feild is hidden in top menus 
                $(".section-menu-color").find('.field.border-color').show();// this feild is visible in top menus
            }

        });

        // menu top style 
        $(document).on('click', '.section-header-style .imageList a', function () {

            var $select = $(this),
                val = $(this).text(),
                $MenuLogo = $container.find('.section-logo,.section-logo-second'),
                $selected = $('#menu').find('.section-' + val);
  
            if (val == 'fixed-menu' || val == 'scroll-sticky') {
                // Hide logo secoun
                $(".section-logo-second").slideUp('fast').next('hr').hide();
                $(".section-menu-color").slideUp('fast').next('hr').hide();
                $(".section-logo").slideDown('fast').next('hr').show();
                $(".section-initial-menu-color").slideDown('fast').next('hr').show();

            } else if (val == 'epico-menu') {

                $(".section-logo-second , .section-logo , .section-initial-menu-color , .section-menu-color").slideDown('fast').next('hr').show();

            }

            $selected.slideDown('fast').next('hr').show();

        }).change();

    }

    function demo_importer() {
        $(document).on('click', 'a.import', function (e) {
            e.preventDefault();
            var $import_button = $(this);
            var demo = $(this).data('demo');
            $(this).parents('form').find('input#demo_name').val(demo);
            $(this).parents('form').submit();
        });
    }

    function setRowTypeIcon ()
    {
        $('span.row_type').each(function(){
            if($(this).html() == "parallax")
            {
                $(this).removeClass('video-type interactive_background-type').addClass('parallax-type'); 
            }
            else if($(this).html() == "video")
            {
                $(this).removeClass('parallax-type interactive_background-type').addClass('video-type');
            }
            else if($(this).html() == "interactive_background")
            {
                $(this).removeClass('parallax-type video-type').addClass('interactive_background-type');
            }
            else
            {
                $(this).removeClass('parallax-type video-type interactive_background-type');
            }
        })
    }

    function menuDependencies()
    {
        $(document).on('mouseup', 'li.menu-item .menu-item-handle' ,function(e){
            mega_menu_handel();
        });
        $(document).on('change',"input[name^='is-mega-menu']",function(e) {
            var $menuItem = $(this).parents("li.menu-item");
            e.stopPropagation();
            mega_menu_handel();
        });

        mega_menu_handel();

        function mega_menu_handel(){
            //Mega menu & upload field dependencies
            var $menu = $('ul.menu.ui-sortable');

            setTimeout(function(){
                $menu.find('li.menu-item').removeClass('enable-mega-menu-of-parent');

                $menu.find('li.menu-item.menu-item-depth-0').each(function() {
                    $this = $(this);
                    var $megamenu = $this.find("input[name^='is-mega-menu']");

                    if( $megamenu.prop('checked') )
                    {

                        $this.nextUntil( '.menu-item-depth-0','li.menu-item.menu-item-depth-1,li.menu-item.menu-item-depth-2').addClass("enable-mega-menu-of-parent");
                        $this.addClass('enable-mega-menu-of-parent');
                        
                    }
                    else
                    {
                        $this.nextUntil( '.menu-item-depth-0','li.menu-item.menu-item-depth-1,li.menu-item.menu-item-depth-2').removeClass("enable-mega-menu-of-parent");
                        $this.removeClass('enable-mega-menu-of-parent');
                    }
                });
            },500);
        }

        $('#menu-to-edit').on('click',"a.item-edit",menuInitOptionHandle);

        function menuInitOptionHandle () {
            ColorPicker();
        }
    }

    function product_variation()
    {

        $(document).on('click','.woocommerce_attribute_data .add_all_attr',function(e){
            e.preventDefault();
            var $fields_container = $(this).prev('.fields-container');
            $fields_container.find('div.field-container.hide-field').each(function(index,element){
                var $elem = $(element);
                //remove image saved in the attribute field
                $elem.find('.upload-thumb').removeClass("show");
                $elem.find('.upload-field').find('input').val('');
                $elem.removeClass('hide-field'); // show it

                var $value_field = $elem.find('input[name^="x_attribute_values"]'),
                    $extra_value_field = $elem.find('input[name^="x_attribute_extra_values"]');


                var $attr_value_name = $value_field.attr('name'),
                    $attr_extra_value_name = $extra_value_field.attr('name');

                //trick : add x to disable saving it's value ( instead of removing it completly, we can retrive it with just rename them)
                $attr_value_name = $attr_value_name.replace('x_attribute_values','attribute_values');
                $attr_extra_value_name = $attr_extra_value_name.replace('x_attribute_extra_values','attribute_extra_values');

                $value_field.attr('name',$attr_value_name);
                $extra_value_field.attr('name',$attr_extra_value_name);
            });
        });

        $(document).on('click','.woocommerce_attribute_data .field-container .attr_remove',function(){
            $(this).closest('.field-container').addClass('hide-field');

            var $value_field = $(this).closest('.field-container').find('input[name^="attribute_values"]'),
                $extra_value_field = $(this).closest('.field-container').find('input[name^="attribute_extra_values"]');

            var $attr_value_name = $value_field.attr('name'),
                $attr_extra_value_name = $extra_value_field.attr('name');

            //trick : add a prefix "x" to input names to  disable saving those values ( instead of removing them completly, we rename them and it's easy to retrive them)
            $attr_value_name = $attr_value_name.replace('attribute_values','x_attribute_values');
            $attr_extra_value_name = $attr_extra_value_name.replace('attribute_extra_values','x_attribute_extra_values');

            $value_field.attr('name',$attr_value_name);
            $extra_value_field.attr('name',$attr_extra_value_name);

        });

        $(document).on('click','.woocommerce_attribute_data .field-container .upload-thumb .close',function(e){
            $(this).parents('.upload-thumb').prev('.upload-button').trigger('click');
        });



        $( '.product_attributes' ).on( 'click', '.remove_row', function() {
            var $parent = $( this ).parent().parent();
            $parent.find( '.image-attr-field-container input[type=hidden]' ).val( '' );
        });



        // Add a new attribute (via ajax) : this function is a customized copy of function defined in woocommerce/assets/js/admin/meta-boxes-product.js
        $(document).on( 'click', 'a.add_new_attr', function(e) {
            e.preventDefault();

            $( '.product_attributes' ).block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

            var $wrapper           = $( this ).closest( '.woocommerce_attribute' );
            var $fields_container  = $wrapper.find( '.fields-container' );
            var attribute          = $wrapper.data( 'taxonomy' );
            var attribute_number   = $( this ).siblings('.fields-container').find('.field-container').data( 'attr-number' ); // attr number
            var new_attribute_name = window.prompt( woocommerce_admin_meta_boxes.new_attribute_prompt );

            if ( new_attribute_name ) {

                var data = {
                    action:   'woocommerce_add_new_attribute',
                    taxonomy: attribute,
                    term:     new_attribute_name,
                    security: woocommerce_admin_meta_boxes.add_attribute_nonce
                };

                $.post( woocommerce_admin_meta_boxes.ajax_url, data, function( response ) {

                    if ( response.error ) {
                        // Error
                        window.alert( response.error );
                    } else if ( response.slug ) {

                        if( $fields_container.length <= 0)
                        {
                            $wrapper.find('.add_all_attr').before('<div class="fields-container"></div>');
                            $fields_container = $wrapper.find('.fields-container');
                        }

                        // get image for upload field
                            var new_attr = '<div class="field-container image-attr-field-container" data-attr-number=" '+ attribute_number + '">';
                            new_attr += '<a class="attr_remove"></a>';
                            new_attr += '<input type="hidden" name="attribute_values[' + attribute_number + '][]" value="' + response.term_id + '">';
                            new_attr += '<input type="hidden" name="attribute_extra_values[' + attribute_number + '][' + response.slug + ']" value="">';
                            new_attr += '<div class="field upload-field clear-after" data-title="Upload Image" data-referer="ep-attr-image">';
                            new_attr += '<label for="field-' + response.slug + '">' + response.name + '</label>';
                            new_attr += '<input type="text" id="field-' + response.slug + '" name="' + response.slug + '" value="" placeholder="">';
                            new_attr += '<a href="#" class="upload-button">Browse</a>';
                            new_attr += '<div class="upload-thumb">';
                            new_attr += '<div class="close"><span class="close-icon"></span></div>';
                            new_attr += '<img class="" src="" alt="' + response.slug + '">';
                            new_attr += '</div></div></div>';
                            $fields_container.append(new_attr);
    
                    }

                    $( '.product_attributes' ).unblock();
                });

            } else {
                $( '.product_attributes' ).unblock();
            }

            return false;
        });
    }


    function filter_widget_change_display_type() {
       // Add a new attribute (via ajax) : this function is a customized copy of function defined in woocommerce/assets/js/admin/meta-boxes-product.js
        $(document).on( 'change', 'p.display_type_container select, p.attribute_container select', function(e) {
            e.preventDefault();

            var $widget      = $(this).closest('.widget-content'),
                attribute    = $widget.find('p.attribute_container select').find('option:selected').val(),
                display_type = $widget.find('p.display_type_container select').find('option:selected').val(),
                id           = $widget.find('input[name="w_id"]').val(),
                id_base      = $widget.find('input[name="w_idbase"]').val(),
                number       = $widget.find('input[name="w_number"]').val(),
                ajax_nonce   = $widget.find('input[name="ajax_nonce"]').val(),
                $attr_table  = $widget.find('.epico-widget-attributes-table'),
                $hide_text  = $widget.find('.hide_text_container');

            if(display_type == 'image')
            {
                $hide_text.slideDown();
            }
            else
            {
                $hide_text.slideUp();
            }

            if ( attribute !== undefined &&  (display_type == 'color' || display_type == 'image') ) {

                var data = {
                    action:      'change_attribute_display_type',
                    attribute    : attribute,
                    display_type : display_type,
                    id           : id,
                    id_base      : id_base,
                    number       : number,
                    ajax_nonce   : ajax_nonce
                };
                $attr_table.slideDown();
                $attr_table.addClass('loading');
                $attr_table.find('.wc-loading').removeClass('hide');

                $.post( ajaxurl, data, function( response ) {
                    $attr_table.find('table, .no-term').remove();
                    $attr_table.prepend(response);
                    ColorPicker();
                    //wait a bit to add new elements
                    setTimeout(function() {
                        $attr_table.removeClass('loading');
                        $attr_table.find('.wc-loading').addClass('hide')
                    },100)

                });

            }
            else
            {
                $attr_table.slideUp();
            }

            return false;
        });
        
        //Run colorpicker aftar updating widget
        $( document ).on( 'widget-updated' , function( event, $widget ){
            ColorPicker();
        });
    }

    function video_widget_change_display_type() {
        var video_type_dependencies = function(video_display_type_container) {
            if(video_display_type_container == undefined) {
                video_display_type_container = '.video_display_type_container select';
            }

            var $video_display_type_container = $(video_display_type_container);

            $video_display_type_container.each(function(){
                var $widget      = $(this).closest('.epico-video-widget'),
                    display_type = $(this).find('option:selected').val();

                $widget.removeClass('local_video local_video_popup embeded_video_youtube embeded_video_youtube_popup embeded_video_vimeo embeded_video_vimeo_popup').addClass(display_type);
            });

        }

        video_type_dependencies();

        $( document ).on( 'change', '.video_display_type_container select', function(){
            video_type_dependencies(this);
        });

        $( document ).on( 'widget-updated' , function( event, $widget ) {
            video_type_dependencies();
        });
    }

    function font_dependencies() {
        var $select_font_type_body = $('select[name="font-body-type"]'),
            $select_font_type_headings = $('select[name="font-headings-type"]'),
            $select_font_type_shortcode = $('select[name="font-shortcode-type"]'),
            $select_font_type_navigation = $('select[name="font-navigation-type"]'),
            $select_font_body = $('select[name="font-body"]').closest('.field'),
            $select_font_headings = $('select[name="font-headings"]').closest('.field'),
            $select_font_shortcode = $('select[name="font-shortcode"]').closest('.field'),
            $select_font_navigation = $('select[name="font-navigation"]').closest('.field'),
            $select_custom_font_body_url = $('input[name="custom-font-url-body"]').closest('.field'),
            $select_custom_font_body_name = $('input[name="custom-font-name-body"]').closest('.field'),
            $select_custom_font_headings_url = $('input[name="custom-font-url-headings"]').closest('.field'),
            $select_custom_font_headings_name = $('input[name="custom-font-name-headings"]').closest('.field'),
            $select_custom_font_shortcode_url = $('input[name="custom-font-url-shortcode"]').closest('.field'),
            $select_custom_font_shortcode_name = $('input[name="custom-font-name-shortcode"]').closest('.field');
            $select_custom_font_navigation_url = $('input[name="custom-font-url-navigation"]').closest('.field'),
            $select_custom_font_navigation_name = $('input[name="custom-font-name-navigation"]').closest('.field');

            $select_font_body.add($select_font_headings).add($select_font_shortcode).add($select_custom_font_body_url).add($select_custom_font_body_name).add($select_custom_font_headings_url).add($select_custom_font_headings_name).add($select_custom_font_shortcode_url).add($select_custom_font_shortcode_name)
            .add($select_font_navigation).add($select_custom_font_navigation_url).add($select_custom_font_navigation_name).hide();
            set_font_type();

        function set_font_type()
        {
            //Body font
            var selected = $select_font_type_body.find('option:selected').val();
            if(selected == 'default')
            {
                $select_font_body.hide();
                $select_custom_font_body_url.hide()
                $select_custom_font_body_name.hide();
            }
            else if(selected == 'google')
            {
                $select_font_body.show();
                $select_custom_font_body_url.hide();
                $select_custom_font_body_name.hide();
            }
            else
            {
                $select_font_body.hide();
                $select_custom_font_body_url.show();
                $select_custom_font_body_name.show();
            }

            // Headings font
            var selected = $select_font_type_headings.find('option:selected').val();
            if(selected == 'default')
            {
                $select_font_headings.hide();
                $select_custom_font_headings_url.hide();
                $select_custom_font_headings_name.hide();
            }
            else if(selected == 'google')
            {
                $select_font_headings.show();
                $select_custom_font_headings_url.hide();
                $select_custom_font_headings_name.hide();
            }
            else
            {
                $select_font_headings.hide();
                $select_custom_font_headings_url.show();
                $select_custom_font_headings_name.show();
            }


            // Shortcode font
            var selected = $select_font_type_shortcode.find('option:selected').val();
            if(selected == 'default')
            {
                $select_font_shortcode.hide();
                $select_custom_font_shortcode_url.hide();
                $select_custom_font_shortcode_name.hide();
            }
            else if(selected == 'google')
            {
                $select_font_shortcode.show();
                $select_custom_font_shortcode_url.hide();
                $select_custom_font_shortcode_name.hide();
            }
            else
            {
                $select_font_shortcode.hide();
                $select_custom_font_shortcode_url.show();
                $select_custom_font_shortcode_name.show();
            }

            // Navigation font
            var selected = $select_font_type_navigation.find('option:selected').val();
            if(selected == 'default')
            {
                $select_font_navigation.hide();
                $select_custom_font_navigation_url.hide();
                $select_custom_font_navigation_name.hide();
            }
            else if(selected == 'google')
            {
                $select_font_navigation.show();
                $select_custom_font_navigation_url.hide();
                $select_custom_font_navigation_name.hide();
            }
            else
            {
                $select_font_navigation.hide();
                $select_custom_font_navigation_url.show();
                $select_custom_font_navigation_name.show();
            }
        }


        $select_font_type_body.change(set_font_type);
        $select_font_type_headings.change(set_font_type);
        $select_font_type_shortcode.change(set_font_type);
        $select_font_type_navigation.change(set_font_type);

    }

    function instagram_widget_dependencies() {

        function set_dependencies()
        {
                
            $('select.instagram-source').each(function(){
                var $instagram_widget = $(this).closest('.widget-content'),
                    $select_instagram_source = $(this);
                    $input_instagram_carousel = $instagram_widget.find('input.instagram-carousel'),
                    $hover_color = $instagram_widget.find('.instagram-hover-color span');
                //source of media
                var selected = $select_instagram_source.find('option:selected').val();
                if(selected == 'self')
                {
                    $select_instagram_source.closest('p').siblings('p.instagram-otheruser').slideUp('fast');
                }
                else
                {
                    $select_instagram_source.closest('p').siblings('p.instagram-otheruser').slideDown('fast');
                }

                // caousel
                if($input_instagram_carousel.prop('checked') == false)
                {
                    $input_instagram_carousel.closest('p').siblings('p.instagram-nav-style').slideUp('fast');
                }
                else
                {
                    $input_instagram_carousel.closest('p').siblings('p.instagram-nav-style').slideDown('fast');
                }

                // hover color
                if($hover_color.filter('.selected').data("name") == "custom")
                {
                    $hover_color.closest('.instagram-hover-color').siblings('.instagram-custom-hover-color').slideDown('fast');
                }
                else
                {
                    $hover_color.closest('.instagram-hover-color').siblings('.instagram-custom-hover-color').slideUp('fast');
                }
            })

        }

        set_dependencies();

        $(document).on( 'change', 'select.instagram-source, input.instagram-carousel', set_dependencies);
        $(document).on( 'click', '.instagram-hover-color span', set_dependencies);
    }
    
     //Toggle Sections in theme settings with switch
     function toggle_epico_admin_sections($switch_element,$toggle_element1,$toggle_element2){
         
         if($switch_element=='undefined'){// To avoid error at the beginning when we have no $switch
             return;
         }
            $switch_element= $switch_element.next('.switch');

            if($toggle_element1)
            {
                $toggle_element1 = $toggle_element1.parent();
            }

            if($toggle_element2)
            {
                $toggle_element2 = $toggle_element2.parent();
            }

            //initial state
            if( $switch_element.find('.noUi-origin').hasClass("noUi-stacking"))
            {
                if($toggle_element1)
                {
                    $toggle_element1.slideUp('fast');
                    $toggle_element1.next('hr').hide();
                }

                if($toggle_element2)
                {
                    $toggle_element2.slideDown('fast');
                    $toggle_element2.next('hr').show();
                }
            }
            else
            {
                if($toggle_element1)
                {
                    $toggle_element1.next('hr').show();
                    $toggle_element1.slideDown('fast');
                }

                if($toggle_element2)
                {
                    $toggle_element2.next('hr').hide();
                    $toggle_element2.slideUp('fast');
                }
            }
            
            // when switch was clicked
            $switch_element.on('change', function(){
                if( $switch_element.find('.noUi-origin').hasClass("noUi-stacking"))
                    {
                        if($toggle_element1)
                        {
                            $toggle_element1.slideUp('fast');
                            $toggle_element1.next('hr').hide();
                        }
                        
                        if($toggle_element2)
                        {
                            $toggle_element2.slideDown('fast');
                            $toggle_element2.next('hr').show();
                        }
                    }
                else
                    {
                        if($toggle_element1)
                        {
                            $toggle_element1.slideDown('fast');
                            $toggle_element1.next('hr').show();
                        }

                        if($toggle_element2)
                        {
                            $toggle_element2.next('hr').hide();
                            $toggle_element2.slideUp('fast');
                        }
                    }
            });
      }


    function product_detail_dependencies() {
        var $product_detail = $('.imageSelect.product-detail'),
            $product_detail_bg = $('.section.section-product-detail-bg');
            $product_detail_sidebar_position = $('.section.section-product-detail-sidebar');

        $product_detail.find('a').on('click',function(){
            product_detail_change_handler($(this));
        });

        product_detail_change_handler($product_detail.find('a.selected'));

        function product_detail_change_handler($element)
        {

            if($element.hasClass('pd_background') || $element.hasClass('pd_top'))
            {
                $product_detail_bg.add($product_detail_bg.next('hr')).slideDown();
                $product_detail_sidebar_position.add($product_detail_sidebar_position.next('hr')).slideUp();

            }
            else if($element.hasClass('pd_classic_sidebar'))
            {
                $product_detail_bg.add($product_detail_bg.next('hr')).slideUp();
                $product_detail_sidebar_position.add($product_detail_sidebar_position.next('hr')).slideDown();
            }
            else
            {
                $product_detail_bg.add($product_detail_bg.next('hr')).slideUp();
                $product_detail_sidebar_position.add($product_detail_sidebar_position.next('hr')).slideUp();
            }
        }
    }
   
    function product_style_dependencies() {
        var $product_style = $('.imageSelect.shop-styles'),
            $product_hover_color = $('.imageSelect.product_hover_preset'),
            $product_custom_hover_color = $('.product_hover_custom_preset');
            $product_rating = $('.product_rating');

        $product_style.find('a').on('click',function(){
            product_style_change_handler($(this));
        });

        $product_hover_color.find('a').on('click',function(){
            product_hover_color_handler($(this));
        });

        product_style_change_handler($product_style.find('a.selected'));
        product_hover_color_handler($product_hover_color.find('a.selected'));

        function product_style_change_handler($element)
        {

            if($element.hasClass('infoOnHover'))
            {
                $product_hover_color.slideDown();
                $product_rating.slideDown();
            }
            else if($element.hasClass('infoOnClick'))
            {
                $product_rating.slideUp();
            }
            else
            {
                $product_rating.slideDown();
                $product_hover_color.slideUp();
            }
        }


        function product_hover_color_handler($element)
        {
            if($element.hasClass('_custom-color') && $product_style.find('a.selected').hasClass('infoOnHover'))
            {
                $product_custom_hover_color.slideDown();
            }
            else
            {
                $product_custom_hover_color.slideUp();
            }
        }
    }
	
	function upload_wc_cat_header_img() {
		// Only show the "remove image" button when needed
		if ( '0' === $( '#header_image_id' ).val() ) {
			$( '.remove_wc_cat_header_image_button' ).hide();
		}

		// Uploading files
		var file_frame;

		$( document ).on( 'click', '.upload_wc_cat_header_image_button', function( event ) {

			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.downloadable_file = wp.media({
				title: 'Choose an image',
				button: {
					text: 'Use image'
				},
				multiple: false
			});

			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
				var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

				$( '#header-background-image' ).val( attachment.id );
				$( '#product_cat_background_image' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
				$( '.remove_wc_cat_header_image_button' ).show();
			});

			// Finally, open the modal.
			file_frame.open();
		});

		$( document ).on( 'click', '.remove_wc_cat_header_image_button', function() {
			$( '#product_cat_background_image' ).find( 'img' ).attr( 'src', $( '#product_cat_background_image' ).data('default-img') );
			$( '#header-background-image' ).val( '' );
			$( '.remove_wc_cat_header_image_button' ).hide();
			return false;
		});
		
		$( document ).ajaxComplete( function( event, request, options ) {
			if ( request && 4 === request.readyState && 200 === request.status && options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {
				// Clear header image and color fields on submit
				$( '#product_cat_background_image' ).find( 'img' ).attr( 'src', $( '#product_cat_background_image' ).data('default-img') );
				$( '.term-header-color-wrap .wp-picker-clear' ).trigger('click');
			}
		});


	}

    $(document).ready(function () {

        FieldSelector();
        CSVInput();
        ImageSelect();
        Save_Button();
        Thickbox();
        Tooltips();
        Sliders();
        ColorPicker();
        Combobox();
        Chosen();
        Tabs();
        Sidebar_Accordion();
        ImageFields();
        menu();
        iconSelect();
        demo_importer();
        preloader();
        product_variation();
        product_detail_dependencies();
        product_style_dependencies();
        filter_widget_change_display_type();
        instagram_widget_dependencies();
        font_dependencies();
        video_widget_change_display_type();
		upload_wc_cat_header_img();


        toggle_epico_admin_sections($('input[name="loader_display"]'),$('.field.imageSelect.preloader-style'),$('.field.imageSelect.page-transition'));//Toggle Preloder | page transiention
        toggle_epico_admin_sections($('input[name="googleApiKey"]'),$('.field.epicoApiKey strong'),$('#field-customApiKey'));//Toggle google API 
        toggle_epico_admin_sections($('input[name="rss_url"]'),$('#field-social_rss_url'),$('#field-social'));//Toggle RSS & '#field-social' this is for making switch to work, such element don't exist
		toggle_epico_admin_sections($('input[name="shop-filter"]'),$('.shop-ordering-field'),null);//woocommerce ordering
        toggle_epico_admin_sections($('input[name="shop-filter-categories"]'),null,$('input[name="shop-filter-subcategories"]'));//woocommerce ordering
        toggle_epico_admin_sections($('input[name="related_product"]'),null,$('input[name="related_product_display"],[name="related_product_width"]'));//woocommerce related product mode
        toggle_epico_admin_sections($('input[name="catalog_mode"]'),null,$('input[name="catalog_mode_price"]'));//woocommerce catalog mode
        toggle_epico_admin_sections($('input[name="social_share_display"]'),null,$('input[name="social_share_google"],[name="social_share_facebook"],[name="social_share_mail"],[name="social_share_twitter"],[name="social_share_pinterest"]'));//social share 

        setTimeout(function(){
            setRowTypeIcon();
        },800);
        menuDependencies();


        $('#vc_ui-panel-edit-element span.vc_ui-button-action').on('click',function(){
            setTimeout(function(){
                setRowTypeIcon();
            },200);
        });

    });

})(jQuery);