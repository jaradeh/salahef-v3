/*
	Document   :  aaFreamwork
	Created on :  August, 2013
	Author     :  Andrei Dinca, AA-Team http://codecanyon.net/user/AA-Team
*/

// Initialization and events code for the app
WooZone = (function ($) {
	"use strict";

	var option = {
		'prefix': "WooZone"
	};
	
	var settings		= null;
	var 
		t                   = null,
		ajaxBox             = null,
		section             = 'dashboard',
		subsection          = '',
		in_loading_section  = null,
		topMenu             = null,
		debug_level         = 0,
		loading             = null,
		maincontainer       = null,
		mainloading         = null,
		lightbox            = null,
		installDefaultIsRunning = false,
		installDefaultMsg = 'If you already configured the plugin settings with <Amazon config> module, these settings will be overwritten with the default one. Installing default settings should be used when you activate the plugin for the first time. Are you sure you want to proceed?';

	$.fn.center = function() {
		this.css("position","absolute");
		this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + "px");
		return this;
	};
	
	function init() 
	{
		$(document).ready(function(){
			
			t = $("div#WooZone");
			ajaxBox = t.find('#WooZone-ajax-response');
			topMenu = t.find('nav.WooZone-nav');
			
			if (t.size() > 0 ) {
				//fixLayoutHeight();
			}
			
			// plugin depedencies if default!
			if ( $("li#WooZone-nav-depedencies").length > 0 ) {
				section = 'depedencies';
			}
			
			maincontainer = $("#WooZone-wrapper");
			mainloading = $("#WooZone-main-loading");
			lightbox = $("#WooZone-lightbox-overlay");

			// plugin settings
			settings = t.find('#WooZone-plugin-settings').html();
			//settings = JSON.stringify(settings);
			settings = typeof settings != 'undefined'
				? JSON && JSON.parse(settings) || $.parseJSON(settings) : settings;
			
			triggers();
		});
	}
	
	function ajax_loading( label ) 
	{
		// append loading
		loading = $('<div class="WooZone-loader-wrapper"><div class="WooZone-loader-holder"><div class="WooZone-loader"></div> ' + ( label ) + '</div></div>');
		ajaxBox.html(loading);
	}

	function take_over_ajax_loader( label, target )
	{
		loading = $('<div class="WooZone-loader-take-over-wrapper"><div class="WooZone-loader-holder"><div class="WooZone-loader"></div> ' + ( label ) + '</div></div>');
		
		if( typeof target != 'undefined' ) {
			target.append(loading);
		}else{
			t.append(loading);
	   }
	}

	function take_over_ajax_loader_close()
	{
		t.find(".WooZone-loader-take-over-wrapper").remove();
	}
	
	function makeRequest( callback ) 
	{
		// fix for duble loading of js function
		if( in_loading_section == section ){
			return false;
		}
		in_loading_section = section;
		
		// do not exect the request if we are not into our ajax request pages
		if( ajaxBox.size() == 0 ) return false;

		ajax_loading( "Loading section: " + section );
		var data = {
			'action': 'WooZoneLoadSection',
			'section': section
		}; 
		
		jQuery.post(ajaxurl, data, function (response) {
			
			if( response.status == 'redirect' ){
				window.location = response.url;
				return;
			}
			
			if (response.status == 'ok') {
				$("h1.WooZone-section-headline").html(response.headline);
				//return true;
				loading.fadeOut( 350, function(){

					ajaxBox.attr( 'class', "WooZone-section-"  + section );
					
					ajaxBox.html(response.html);
				
					makeTabs();
					
					if( typeof WooZoneDashboard != "undefined" ){
						WooZoneDashboard.init();
					}
					
					// find new open
					var new_open = topMenu.find('li#WooZone-nav-' + section);
					topMenu.find("a.active").removeClass("active");
					new_open.find("a").addClass("active");

					//console.log( new_open.find("a")  );
					
					// callback - subsection!
					if ( $.isArray(callback) && $.isFunction( callback[0] ) ) {
						if ( callback.length == 1 ) {
							callback[0]();
						}
						else if ( callback.length == 2 ) {
							callback[0]( callback[1] );
						}
					}
				
					multiselect_left2right();
				});
			}
		},
		'json');
	}
	
	function installDefaultOptions($btn) {
		if ( installDefaultIsRunning ) return false;
		installDefaultIsRunning = true;
		
		var is_makeinstall = typeof $btn.data('makeinstall') != 'undefined' ? true : false;
		//console.log( is_makeinstall ); return false; 

		var theForm = $btn.parents('form').eq(0),
			value = $btn.val(),
			statusBoxHtml = theForm.find('div.WooZone-message'); // replace the save button value with loading message
		$btn.val('installing default settings ...').removeClass('blue').addClass('gray');
		if (theForm.length > 0) { // serialiaze the form and send to saving data
			var data = {
				'action'				: 'WooZoneInstallDefaultOptions',
				'options'			: theForm.serialize(),
				'is_makeinstall'	: is_makeinstall ? 1 : 0
			}; // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function (response) {
				if (response.status == 'ok') {
					statusBoxHtml.addClass('WooZone-success').html(response.html).fadeIn().delay(3000).fadeOut();
					
					// from default install
					if ( is_makeinstall ) {
						setTimeout(function () {
							var currentLoc 	= window.location.href,
								  newLoc		= currentLoc.replace(/#.*$/, '#!/dashboard');

							newLoc = currentLoc.replace(/page=.*$/, 'page=WooZone_wizard');

							//window.location = '';
							window.location.replace( newLoc );
							window.location.reload();
							
							// replace the save button value with default message
							setTimeout( function() {
								$btn.val(value).removeClass('gray').addClass('blue');
								take_over_ajax_loader_close();							
							}, 500);
						},
						1500);
					}
					// choose to install settings
					else {
						setTimeout(function () {
							var currentLoc 	= window.location.href,
								  newLoc		= currentLoc.replace('#makeinstall', '');
							window.location.replace( newLoc );
							window.location.reload();
							
							// replace the save button value with default message
							$btn.val(value).removeClass('gray').addClass('blue');
							take_over_ajax_loader_close();							
						},
						2000);
					}
				} else {
					statusBoxHtml.addClass('WooZone-error').html(response.html).fadeIn().delay(13000).fadeOut();
					
					// replace the save button value with default message
					$btn.val(value).removeClass('gray').addClass('blue');
					take_over_ajax_loader_close();
				}
			},
			'json');
		}
	}
	
	function saveOptions ($btn, callback) 
	{
		var theForm = $btn.parents('form').eq(0),
			theForm_id			= theForm.attr('id'),
			value = $btn.val(),
			statusBoxHtml = theForm.find('div#WooZone-status-box'); // replace the save button value with loading message
		$btn.val('saving setings ...').removeClass('green').addClass('gray');
		
		multiselect_left2right(true);

		var options       = theForm.serializeArray();
		//console.log( $.param( options ) ); return false;

		// Because serializeArray() ignores unset checkboxes and radio buttons, also empty selects
		var el            = { inputs: null, selects: null };
		el.inputs         = theForm.find('input[type=checkbox]:not(:checked)');
		el.selects        = theForm.find('select:not(:selected)');
		el.selects_m      = theForm.find('select[multiple]:not(:selected)');
		//for (var kk = 0, arr = ['inputs', 'selects'], len = arr.length; kk < len; kk++) {
		//    var vv = arr[kk], $vv = el[vv];
		
		for (var kk in el) {
			if ( $.inArray(kk, ['selects_m']) > -1 ) {
				options = options.concat(el[kk].map(
					function() {
						return {"name": this.name, "value": this.value}
					}).get()
				);
			}
		}
		//console.log( $.param( options ) ); return false;
		
		if (theForm.length > 0) { // serialiaze the form and send to saving data
			var data = {
				'action': 'WooZoneSaveOptions',
				'options': $.param( options ), //theForm.serialize(),
			};
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function (response) {
				if (response.status == 'ok') {

					statusBoxHtml.addClass('WooZone-success').html(response.html).fadeIn().delay(3000).fadeOut();
					if (section == 'synchronization') {
						updateCron();
					}
					
				}
				// replace the save button value with default message
				$btn.val(value).removeClass('gray').addClass('green');

				if( typeof callback == 'function' ){
					callback.call();
				}
			},
			'json');
		}
	}

	function moduleChangeStatus($btn) 
	{
		var value = $btn.text(),
			the_status = $btn.hasClass('activate') ? 'true' : 'false';
		// replace the save button value with loading message
		$btn.text('saving setings ...');
		var data = {
			'action': 'WooZoneModuleChangeStatus',
			'module': $btn.attr('rel'),
			'the_status': the_status
		};
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function (response) {
			if (response.status == 'ok') {
				window.location.reload();
			}
		},
		'json');
	}
	
	function updateCron() 
	{
		var data = {
			'action': 'WooZoneSyncUpdate'
		}; // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function (response) {},
		'json');
	}
	
	function fixLayoutHeight() 
	{
		var win = $(window),
			WooZoneWrapper = $("#WooZone-wrapper"),
			minusHeight = 40,
			winHeight = win.height(); // show the freamwork wrapper and fix the height
		WooZoneWrapper.css('min-height', parseInt(winHeight - minusHeight)).show();
		$("div#WooZone-ajax-response").css('min-height', parseInt(winHeight - minusHeight - 240)).show();
	}
	
	function activatePlugin( $that ) 
	{
		var requestData = {
			'ipc': $('#productKey').val(),
			'email': $('#yourEmail').val()
		};
		if (requestData.ipc == "") {
			alert('Please type your Item Purchase Code!');
			return false;
		}
		$that.replaceWith('Validating your IPC <em>( ' + (requestData.ipc) + ' )</em>  and activating  Please be patient! (this action can take about <strong>10 seconds</strong>)');
		var data = {
			'action': 'WooZoneTryActivate',
			'ipc': requestData.ipc,
			'email': requestData.email
		}; // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function (response) {
			if (response.status == 'OK') {
				var currentLoc 	= window.location.href,
					newLoc 		= currentLoc.replace(/page=.*$/, 'page=WooZone_wizard');
  
				window.location.replace( newLoc );
				return false;
				//window.location.reload();
			} else {
				alert(response.msg);
				return false;
			}
		},
		'json');
	}
	
	function ajax_list()
	{
		var make_request = function( action, params, callback ){
			var loading = $("#WooZone-main-loading");
			loading.show();
 
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, {
				'action'        : 'WooZoneAjaxList',
				'ajax_id'       : $(".WooZone-table-ajax-list").find('.WooZone-ajax-list-table-id').val(),
				'sub_action'    : action,
				'params'        : params
			}, function(response) {
   
				if( response.status == 'valid' )
				{
					$("#WooZone-table-ajax-response").html( response.html );

					aateam_tooltip();
					loading.fadeOut('fast');
				}
			}, 'json');
		}

		$(".WooZone-table-ajax-list").on('change', 'select[name=WooZone-post-per-page]', function(e){
			e.preventDefault();

			make_request( 'post_per_page', {
				'post_per_page' : $(this).val()
			} );
		})

		.on('change', 'select[name=WooZone-filter-post_type]', function(e){
			e.preventDefault();

			make_request( 'post_type', {
				'post_type' : $(this).val()
			} );
		})
		
		.on('change', 'select[name=WooZone-filter-post_parent]', function(e){
			e.preventDefault();

			make_request( 'post_parent', {
				'post_parent' : $(this).val()
			} );
		})

		.on('click', 'a.WooZone-jump-page', function(e){
			e.preventDefault();

			make_request( 'paged', {
				'paged' : $(this).attr('href').replace('#paged=', '')
			} );
		})

		.on('click', '.WooZone-post_status-list a', function(e){
			e.preventDefault();

			make_request( 'post_status', {
				'post_status' : $(this).attr('href').replace('#post_status=', '')
			} );
		})
		
		.on('change', 'select.WooZone-filter-general_field', function(e){
			e.preventDefault();
			
			var $this       = $(this),
				filter_name = $this.data('filter_field'),
				filter_val  = $this.val();

			make_request( 'general_field', {
				'filter_name'    : filter_name,
				'filter_val'     : filter_val
			} );
		})
		
		.on('click', 'ul.WooZone-filter-general_field a', function(e){
			e.preventDefault();
 
			var $this       = $(this),
				$parent_ul  = $this.parents('ul').first(),
				filter_name = $parent_ul.data('filter_field'),
				filter_val  = $this.data('filter_val');

			make_request( 'general_field', {
				'filter_name'    : filter_name,
				'filter_val'     : filter_val
			} );
		})        
		
		.on('click', 'input[name=WooZone-search-btn]', function(e){
			e.preventDefault();
			
			make_request( 'search', {
				'search_text' : $(this).parent().find('#WooZone-search-text').val()
			} );
		});
	}
	
	function amzCheckAWS()
	{
		$('body').on('click', '.WooZoneCheckAmzKeys', function (e) {
			e.preventDefault();

			$('#AccessKeyID').val( $.trim( $('#AccessKeyID').val() ) );
			$('#SecretAccessKey').val( $.trim( $('#SecretAccessKey').val() ) );
			$('.WooZone-aff-ids input').each(function() {
				$(this).val( $.trim( $(this).val() ) );
			});

			var that = $(this),
				old_value = that.val(),
				submit_btn = that.parents('form').eq(0).find('input[type=submit]');
			
			that.removeClass('blue').addClass('gray');
			that.val('Checking your keys ...'); 
			
			saveOptions(submit_btn, function(){
				
				jQuery.post(ajaxurl, {
					'action' : 'WooZoneCheckAmzKeys'
				}, function(response) {

						/*
						var msg = '<p>' + response.msg + "<p>";

						if ( response.status == 'valid' ) {
							msg += '<p>WooCommerce Amazon Affiliates was able to connect to Amazon with the specified AWS Key Pair and Associate ID</p>';

							swal(
								'Good job!',
								msg,
								'success'
							);
						}
						else{
							msg += '<p>WooCommerce Amazon Affiliates was not able to connect to Amazon with the specified AWS Key Pair and Associate ID. Please triple-check your AWS Keys and Associate ID.</p>';

							if( msg.indexOf('aws:Client.AWS.InvalidAssociate') > -1 ){
								msg += 	'<p><strong>Don\'t panic</strong>, this error is easy to fix, please follow the instructions from ';
								msg += 		'<a href="http://support.aa-team.com/knowledgebase-details/198">here</a>.';
								msg += '</p>';
							}

							swal(
								'Opps!',
								msg,
								'error'
							);
						}
						*/
						var msg = response.msg;
						if ( 'valid' == response.status ) {
							swal(
								'Good job!',
								msg,
								'success'
							);
						} else{
							swal(
								'Opps!',
								msg,
								'error'
							);
						}

						that.val( old_value ).removeClass('gray').addClass('blue');
				}, 'json');
			});
		});
	}
	
	function removeHelp()
	{
		$("#WooZone-help-container").remove();  
	}
	
	function showHelp( that )
	{
		removeHelp();

		var help_type = that.data('helptype');
		var operation = that.data('operation');
		var html = $('<div class="WooZone-panel-widget" id="WooZone-help-container" />');
		
		var btn_close_text = ( operation == 'help' ? 'Close HELP' : 'Close Feedback' );
		html.append("<a href='#close' class='WooZone-button red' id='WooZone-close-help'>" + btn_close_text + "</a>")
		if( help_type == 'remote' ){
			var url = that.data('url');
			var content_wrapper = $("#WooZone-content");
			
			html.append( '<iframe src="' + ( url ) + '" style="width:100%; height: 100%;border: 1px solid #d7d7d7;" frameborder="0" id="WooZone-iframe-docs"></iframe>' )
			
			content_wrapper.append(html);
			
			// feedback iframe related!
			//var $iframe = $('#WooZone-iframe-docs'),
		}
	}
	
	function hashChange_old()
	{
		if ( location.href.indexOf("WooZone#") != -1 ) {
			// Alerts every time the hash changes!
			if(location.hash != "") {
				section = location.hash.replace("#", '');
				
				var __tmp = section.indexOf('#');
				if ( __tmp == -1 ) subsection = '';
				else { // found subsection block!
						subsection = section.substr( __tmp+1 );
						section = section.slice( 0, __tmp );
					}
				} 
	 
				if ( subsection != '' )
				makeRequest([
					function (s) { scrollToElement( s ) },
					'#'+subsection
				]);
			else 
				makeRequest();
			return false;
		}
		if ( location.href.indexOf("=WooZone") != -1 ) {
			makeRequest();
			return false;
		}
	}
	function hashChange_old2() {
		if (location.hash != "") {
			section = location.hash.replace("#!/", '');
			if( t.size() > 0 ) {
				makeRequest();
			}
		}else{
			if( t.size() > 0 && location.search == "?page=WooZone" ){
				makeRequest();
			}
		}
	}
	function hashChange() {
		// main container exists?
		if( t.size() <= 0 ) {
			return false;
		}

		if (location.hash != "") {
			section = location.hash.replace("#!/", '');
			
			if (1) {
				var __tmp = section.indexOf('#');
				if ( __tmp == -1 ) {
					subsection = '';
				} else { // found subsection block!
					subsection = section.substr( __tmp+1 );
					section = section.slice( 0, __tmp );
				}
 
				if ( subsection != '' ) {
					var __re = /tab:([0-9a-zA-Z_-]*)/gi; //new RegExp("tab:([0-9a-zA-Z_-]*)", "gi");
					// is tab?
					if ( __re.test(subsection) ) {
						var __match = subsection.match(__re); //__re.exec(subsection); //null;
						sub_istab = typeof (__match[0]) != 'undefined' ? __match[0].replace('tab:', '') : '';

						if ( sub_istab == '' ) return false;
						makeRequest([
							function (s) { 
								$('.tabsHeader').find('a[title="'+s+'"]').click();
							},
							sub_istab
						]);
					}
					// other?
					else {
						var whatPms = {
							what		: subsection
						};
 
						makeRequest([
							function (pms) {
								var pms 	= pms || {},
									  what 	= misc.hasOwnProperty(pms, 'what') ? pms.what : '';

								if ( 'makeinstall' == what ) {
									take_over_ajax_loader( "installing default settings ..." );
									$('.WooZone-installDefaultOptions').data('makeinstall', 'yes').trigger('click');
									/*
									if ( confirm( installDefaultMsg ) ) {
										$('.WooZone-installDefaultOptions').data('makeinstall', 'yes').trigger('click');
									} else {
										var currentLoc 	= window.location.href,
											  newLoc		= currentLoc.replace('#makeinstall', '');
										window.location.replace( newLoc );
										take_over_ajax_loader_close();
									}
									*/
								}
							},
							whatPms
						]);
					}
				} else { 
					makeRequest();
				}
			}
		}else{
			if( location.search == "?page=WooZone" ){
				makeRequest();
			}
		}
	}

	function multiselect_left2right( autselect ) {
		var $allListBtn = $('.multisel_l2r_btn');
		var autselect = autselect || false;
 
		if ( $allListBtn.length > 0 ) {
			$allListBtn.each(function(i, el) {
 
				var $this = $(el), $multisel_available = $this.prevAll('.WooZone-multiselect-available').find('select.multisel_l2r_available'), $multisel_selected = $this.prevAll('.WooZone-multiselect-selected').find('select.multisel_l2r_selected');
 
				if ( autselect ) {
					$multisel_selected.find('option').each(function() {
						$(this).prop('selected', true);
					});
					$multisel_available.find('option').each(function() {
						$(this).prop('selected', false);
					});
				} else {

				$this.on('click', '.moveright', function(e) {
					e.preventDefault();
					$multisel_available.find('option:selected').appendTo($multisel_selected);
				});
				$this.on('click', '.moverightall', function(e) {
					e.preventDefault();
					$multisel_available.find('option').appendTo($multisel_selected);
				});
				$this.on('click', '.moveleft', function(e) {
					e.preventDefault();
					$multisel_selected.find('option:selected').appendTo($multisel_available);
				});
				$this.on('click', '.moveleftall', function(e) {
					e.preventDefault();
					$multisel_selected.find('option').appendTo($multisel_available);
				});
				
				}
			});
		}
	}
	
	function makeTabs()
	{
		$('ul.WooZone-tabs-header').each(function() {
			// For each set of tabs, we want to keep track of
			// which tab is active and it's associated content
			var $active, $content, $links = $(this).find('a');

			// If the location.hash matches one of the links, use that as the active tab.
			// If no match is found, use the first link as the initial active tab.
			var __tabsWrapper = $(this), __currentTab = $(this).find('li#WooZone-tabs-current').attr('title');
			$active = $( $links.filter('[title="'+__currentTab+'"]')[0] || $links[0] );
			$active.addClass('active');
			$content = $( '.'+($active.attr('title')) );

			// Hide the remaining content
			$links.not($active).each(function () {
				$( '.'+($(this).attr('title')) ).hide();
			});

			// Bind the click event handler
			$(this).on('click', 'a', function(e){
				// Make the old tab inactive.
				$active.removeClass('active');
				$content.hide();

				// Update the variables with the new link and content
				__currentTab = $(this).attr('title');
				__tabsWrapper.find('li#WooZone-tabs-current').attr('title', __currentTab);
				$active = $(this);
				$content = $( '.'+($(this).attr('title')) );

				// Make the tab active.
				$active.addClass('active');
				$content.show();

				// Prevent the anchor's default click action
				e.preventDefault();
			});
		});
	}
	
	function make_select_menu()
	{
		//console.log( maincontainer  );
	}

	function PopupCenter(url, title, w, h) 
	{
		// Fixes dual-screen position                         Most browsers      Firefox
		var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
		var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

		var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
		var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

		var left = ((width / 2) - (w / 2)) + dualScreenLeft;
		var top = ((height / 2) - (h / 2)) + dualScreenTop;
		var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

		// Puts focus on the newWindow
		if (window.focus) {
			newWindow.focus();
		}
	}

	function auth_bitly()
	{
		//WooZone-bitly-auth

		$("body").on('keyup', '#client_id', function(){

			var that = $(this),
				auth_btn = $("#WooZone-bitly-auth"),
				redirect_uri = $("#redirect_url").val(),
				the_url = 'https://bitly.com/oauth/authorize?client_id=#0#&redirect_uri=#1#',
				client_id = that.val();

			the_url = the_url.replace( "#0#", client_id );
			the_url = the_url.replace( "#1#", redirect_uri );

			auth_btn.click( function( e ){
				e.preventDefault();

				PopupCenter( the_url, 'Bitly oAuth', '900', '500' );  
			});
		});

		//setInterval( auth_bitly, 500 );
	}

	function generatePassword()
	{
		var length = 32,
			charset = "abcdefghijklmnopqrstuvwxyz0123456789",
			retVal = "";
		for (var i = 0, n = charset.length; i < length; ++i) {
			retVal += charset.charAt(Math.floor(Math.random() * n));
		}
		return retVal;
	}

	function triggers() 
	{
		amzCheckAWS();

		auth_bitly();

		make_select_menu();

		$(window).resize(function () {
			//fixLayoutHeight();
		});

		$("body").on( 'click', '.WooZone-generate-password', function(e){
			e.preventDefault();
			 
			var that = $(this),
				input = that.prev('input'),
				form = that.parents('form').eq(0);

			input.val( generatePassword() );

			form.find(".WooZone-form-button-success").click();
		});

		$("body").on( 'click', '.WooZone-readonly_select', function(){
			 $(this).select();
		});

		$("body").on('mousemove', '.WooZone-loader-wrapper, .WooZone-loader-take-over-wrapper', function( event ) {
			
			var pageCoords = "( " + event.pageX + ", " + event.pageY + " )";
			var clientCoords = "( " + event.clientX + ", " + event.clientY + " )";
			var parent = $(this).parent();
			var parentPos = parent.offset();
			//$( "span:first" ).text( "( event.pageX, event.pageY ) : " + pageCoords );
			//$( "span:last" ).text( "( event.clientX, event.clientY ) : " + clientCoords );
 
			event.pageY = event.pageY - 100;
			if( typeof parent != 'undefined' && parent.attr('id') != 'WooZone' ) {
				event.pageY = event.pageY - parentPos.top + 31;
			}
			
			$(this).find(".WooZone-loader-holder").css( 'top', event.pageY + 'px' );
			
		});


		$('body').on('click', '.WooZone_activate_product', function (e) {
			e.preventDefault();
			activatePlugin($(this));
		});
		$('body').on('click', '.WooZone-saveOptions', function (e) {
			e.preventDefault();
			saveOptions($(this));
		});
		$('body').on('click', '.WooZone-installDefaultOptions', function (e) {
			e.preventDefault();
			installDefaultOptions($(this));
		});
		$('.WooZone-message_activate').on('click', '.submit a.button-primary', function (e) {
			if ( $('form#WooZone_setup_box').length > 0 ) {
				take_over_ajax_loader( "installing default settings ..." );
				$('.WooZone-installDefaultOptions').data('makeinstall', 'yes').trigger('click');
				/*
				if ( confirm( installDefaultMsg ) ) {
					$('.WooZone-installDefaultOptions').data('makeinstall', 'yes').trigger('click');
				} else {
					var currentLoc 	= window.location.href,
						  newLoc		= currentLoc.replace('#makeinstall', '');
					window.location.replace( newLoc );
					take_over_ajax_loader_close();
				}
				*/
			}
		});
		
		$('body').on('click', '#' + option.prefix + "-module-manager a", function (e) {
			e.preventDefault();
			moduleChangeStatus($(this));
		}); // Bind the event.
		
		// Bind the hashchange event.
		/*
		$(window).on('hashchange', function(){
			hashChange();
		});
		hashChange();
		*/
		// Alerts every time the hash changes!
		$(window).hashchange(function () {
			hashChange();
		});
		// Trigger the event (useful on page load).
		$(window).hashchange();
		
		ajax_list();
		
		$("body").on('click', "a.WooZone-show-feedback", function(e){
			e.preventDefault();
			
			showHelp( $(this) );
		});
		
		$("body").on('click', "a.WooZone-show-docs-shortcut", function(e){
			e.preventDefault();
			
			$("a.WooZone-show-docs").click();
		});
		
		$("body").on('click', "a.WooZone-show-docs", function(e){
			e.preventDefault();
			
			showHelp( $(this) );
		});
		
		 $("body").on('click', "a#WooZone-close-help", function(e){
			e.preventDefault();
			
			removeHelp();
		});
		
		multiselect_left2right();




		$('body').on('click', 'input#WooZone-item-check-all', function(){
			var that = $(this),
				checkboxes = $('#WooZone-list-table-posts input.WooZone-item-checkbox');

			if( that.is(':checked') ){
				checkboxes.prop('checked', true);
			}
			else{
				checkboxes.prop('checked', false);
			}
		});

		$("body").on("click", "#WooZone-list-rows a", function(e){
			e.preventDefault();
			$(this).parent().find('table').toggle("slow");
		});

		// publish / unpublish row
		$('body').on('click', ".WooZone-do_item_publish", function(e){
			e.preventDefault();
			var that = $(this),
				row = that.parents('tr').eq(0),
				id  = row.data('itemid');
				
			do_item_action( id, 'publish' );
		});

		// delete row       
		$('body').on('click', ".WooZone-do_item_delete", function(e){
			e.preventDefault();
			var that = $(this),
				row = that.parents('tr').eq(0),
				id  = row.data('itemid');

			//row.find('code').eq(0).text()
			if(confirm('Delete row with ID# '+id+' ? This action cannot be rollback !' )){
				do_item_action( id, 'delete' );
			}
		});
		
		$('body').on('click', '#WooZone-do_bulk_delete_rows', function(e){
			e.preventDefault();

			if (confirm('Are you sure you want to delete the selected rows ? This action cannot be rollback !'))
				do_bulk_delete_rows();
		});
		
		//all checkboxes are checked by default!
		$('.WooZone-form .WooZone-table input.WooZone-item-checkbox').attr('checked', 'checked');
				
		// inline edit
		inline_edit();

		// simplemodal
		$('body').on('click', '.WooZone-simplemodal-trigger', function(e) {
			//$(this).modal({overlayClose:true});
			$.modal( $(this).data('original-title') );
			return false; 
		});
		aateam_tooltip();
	}

	function do_item_action( itemid, sub_action )
	{
		var sub_action = sub_action || '';

		lightbox.fadeOut('fast');
		mainloading.fadeIn('fast');
		
		jQuery.post(ajaxurl, {
			'action'        : 'WooZoneAjaxList_actions',
			'itemid'        : itemid,
			'sub_action'    : sub_action,
			'ajax_id'       : $(".WooZone-table-ajax-list").find('.WooZone-ajax-list-table-id').val(),
			'debug_level'   : debug_level
		}, function(response) {
			if( response.status == 'valid' ){
				mainloading.fadeOut('fast');
				//window.location.reload();
				$("#WooZone-table-ajax-response").html( response.html );
				return false;
			}
			mainloading.fadeOut('fast');
			alert('Problems occured while trying to execute action: '+sub_action+'!');
		}, 'json');
	}

	function do_bulk_delete_rows() {
		var ids = [], __ck = $('.WooZone-form .WooZone-table input.WooZone-item-checkbox:checked');
		__ck.each(function (k, v) {
			ids[k] = $(this).attr('name').replace('WooZone-item-checkbox-', '');
		});
		ids = ids.join(',');
		if (ids.length<=0) {
			alert('You didn\'t select any rows!');
			return false;
		}
		
		lightbox.fadeOut('fast');
		mainloading.fadeIn('fast');

		jQuery.post(ajaxurl, {
			'action'        : 'WooZoneAjaxList_actions',
			'id'            : ids,
			'sub_action'    : 'bulk_delete',
			'ajax_id'       : $(".WooZone-table-ajax-list").find('.WooZone-ajax-list-table-id').val(),
			'debug_level'   : debug_level
		}, function(response) {
			if( response.status == 'valid' ){
				mainloading.fadeOut('fast');
				//window.location.reload();
				$("#WooZone-table-ajax-response").html( response.html );
				return false;
			}
			mainloading.fadeOut('fast');
			alert('Problems occured while trying to execute action: '+'bulk_delete_rows'+'!');
		}, 'json');
	}

	// inline edit fields
	var inline_edit = function() {

		function make_request( pms ) {
			var pms         = pms || {},
				replace     = misc.hasOwnProperty( pms, 'replace' ) ? pms.replace : null,
				itemid      = misc.hasOwnProperty( pms, 'itemid' ) ? pms.itemid : 0,
				table       = misc.hasOwnProperty( pms, 'table' ) ? pms.table : '',
				field       = misc.hasOwnProperty( pms, 'field' ) ? pms.field : '',
				new_val     = misc.hasOwnProperty( pms, 'new_val' ) ? pms.new_val : '',
				el_type     = misc.hasOwnProperty( pms, 'el_type' ) ? pms.el_type : '',
				new_text    = misc.hasOwnProperty( pms, 'new_text' ) ? pms.new_text : '';
				
			//console.log( row, itemid, field_name, field_value ); return false;             
			loading( replace, 'show' );

			jQuery.post(ajaxurl, {
				'action'        : 'WooZoneAjaxList_actions',
				'itemid'        : itemid,
				'sub_action'    : 'edit_inline',
				'table'         : table,
				'field_name'    : field,
				'field_value'   : new_val,
				'ajax_id'       : $(".WooZone-table-ajax-list").find('.WooZone-ajax-list-table-id').val(),
				'debug_level'   : debug_level

			}, function(response) {

				loading( replace, 'close' );
				var orig     = replace.prev('.WooZone-edit-inline'),
					just_new = 'input' == el_type ? new_val : new_text;
				orig.html( just_new );

				// success
				if( response.status == 'valid' ){
					replace.hide();
					orig.show();
					return false;
				}

				// error
				replace.hide();
				orig.show();
				//alert('Problems occured while trying to execute action: '+sub_action+'!');

			}, 'json');
		};
		
		function loading( row, status ) {
			if ( 'close' == status ) {
				row.find('i.WooZone-edit-inline-loading').remove();
			}
			else {
				row.prepend( $('<i class="WooZone-edit-inline-loading WooZone-icon-content_spinner" />') );
			}
		};

		$(document).on(
			{
				mouseenter: function(e) {
					$(this).addClass('WooZone-edit-inline-hover');
				},
				mouseleave: function(e) {
					$(this).removeClass('WooZone-edit-inline-hover');
				}
			},
			'.WooZone-edit-inline'
		);

		$(document).on('click', '.WooZone-edit-inline', function(e) {
			var that    = $(this),
				replace = that.next('.WooZone-edit-inline-replace');
				
			that.hide();
			replace.show().focus();
			replace.find('input,select').focus();
		});

		function change_and_blur(e) {
			var that = $(this);
			clearTimeout(change_and_blur.timeout);
			change_and_blur.timeout = null;
			change_and_blur.timeout = setTimeout(function(){
				__();
			}, 200);
 
			function __() {
				//var that        = $(this);
				var parent      = that.parent(),
					row         = that.parents('tr').first(),
					itemid      = row.data('itemid'),
					table       = parent.data('table'),
					field       = that.prop('name').replace('WooZone-edit-inline[', '').replace(']', ''),
					new_val     = that.val(),
					el_type     = e.target.tagName.toLowerCase(),
					new_text    = 'select' == el_type ? that.find('option:selected').text() : '';
	 
				make_request({
					'replace'       : parent,
					'itemid'        : itemid,
					'table'         : table,
					'field'         : field,
					'new_val'       : new_val,
					'el_type'       : el_type,
					'new_text'      : new_text 
				});
			}
		}
		// $(document).on('change', '.WooZone-edit-inline-replace input, .WooZone-edit-inline-replace select', change_and_blur);
		$(document).on('blur', '.WooZone-edit-inline-replace input, .WooZone-edit-inline-replace select', change_and_blur);
	};

	(function responsiveMenu() {
		$( document ).ready(function() {
			$('.WooZone-responsive-menu').toggle(function() {
				$('.WooZone-nav').show();
			}, function() {
				$('.WooZone-nav').hide();
			});
		});
	})();
	
	// demo keys
	function verify_products_demo_keys() {
		console.log( 'You can no longer import products using our demo keys.' );
		window.location.reload();
	}


	function scrollToElement(child, parent, pms) {
		parent = typeof(parent) != 'undefined' && parent !== null ? parent : 'html, body';

		//time = typeof(time) != 'undefined' ? time : 1000;
		//verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
		var time = typeof pms == 'object' && misc.hasOwnProperty(pms, 'time') ? pms.time : 1000,
			verticalOffset = typeof pms == 'object' && misc.hasOwnProperty(pms, 'verticalOffset') ? pms.verticalOffset : 0,
			scrollTop = typeof pms == 'object' && misc.hasOwnProperty(pms, 'scrollTop') ? pms.scrollTop : '',
			useMethod = typeof pms == 'object' && misc.hasOwnProperty(pms, 'useMethod') ? pms.useMethod : 'animation';

		var $parent = $(parent),
			$child = $(child);
		if ( $parent.length <= 0 || $child.length <= 0 ) return false;
		
		$parent.scrollTop(0);

		if ( scrollTop == '' ) {
			var offset = $child.position(),
				offsetTop = parseInt( parseInt(offset.top) + parseInt(verticalOffset) ),
				poffset = $parent.position(),
				poffsetTop = parseInt(poffset.top),
				scrollTop = parseInt( offsetTop - poffsetTop );

			if ( useMethod == 'animation' ) {
				$parent.animate({
					'scrollTop': scrollTop
				}, time);
			} else {
				$parent.scrollTop( scrollTop );
			}
		} else {
			scrollTop = parseInt( scrollTop );
			$parent.scrollTop( scrollTop );
		}
	}


	function aateam_tooltip( selector ) {
		/*if ( typeof jQuery.fn.tipsy != "undefined" ) { // verify tipsy plugin is defined in jQuery namespace!
			$('a.aa-tooltip').tipsy({
				gravity: 'e'
			});

			$('.WooZone-tooltip-trigger').tipsy({
				html: true,
				gravity: 'n'
			});

			// simplemodal
			$('.WooZone-simplemodal-trigger').tipsy({
				html: true,
				gravity: 'n'
			});
		}*/

		var selector_def 	= '.aa-tooltip, .WooZone-tooltip-trigger, .WooZone-simplemodal-trigger',
			selector 		= ( selector_def + ', ' + selector ) || selector_def;
		//console.log( selector );
		tippy( selector, {
			dynamicTitle: true,
			delay: 10,
			arrow: true,
			arrowType: 'round',
			size: 'large',
			duration: 100,
			animation: 'scale'
		});
	}


	// :: MISC
	var misc = {
		hasOwnProperty: function(obj, prop) {
			var proto = obj.__proto__ || obj.constructor.prototype;
			return (prop in obj) &&
			(!(prop in proto) || proto[prop] !== obj[prop]);
		}
	}

	init();
	
	return {
		'aateam_tooltip' 				: aateam_tooltip,
		'init' 							: init,
		'makeTabs' 						: makeTabs,
		'to_ajax_loader' 				: take_over_ajax_loader,
		'to_ajax_loader_close' 			: take_over_ajax_loader_close,
		'verify_products_demo_keys' 	: verify_products_demo_keys,
		'scrollToElement' 				: scrollToElement
	}
})(jQuery);