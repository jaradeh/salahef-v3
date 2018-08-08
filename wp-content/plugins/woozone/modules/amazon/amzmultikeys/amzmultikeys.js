/*
Document   :  Auto Import
Author     :  Andrei Dinca, AA-Team http://codecanyon.net/user/AA-Team
*/

// Initialization and events code for the app
WooZoneAmzMultiKeys = (function($) {
	"use strict";

	// public
	var debug_level                     = 0,
		maincontainer                   = null,
		lang                            = null,
		tpl_main						= null,
		tpl_delete_confirm 				= null,
		tpl_add_confirm 				= null;


	function aateam_tooltip() {
		WooZone.aateam_tooltip();
	}


	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function() {

			maincontainer = $("#WooZone #WooZone-multikeys");

			// language messages
			lang = maincontainer.find('#WooZone-lang-translation').length
				? maincontainer.find('#WooZone-lang-translation').html()
				: $('#WooZone-wrapper #WooZone-lang-translation').html();
			//lang = JSON.stringify(lang);
			lang = typeof lang != 'undefined'
				? JSON && JSON.parse(lang) || $.parseJSON(lang) : lang;

			load_templates();

			triggers();

			aateam_tooltip();
		});
	})();

	function load_templates() {
		tpl_main = maincontainer.find('#WooZone-multikeys-tpl');

		// get templates
		var _tpl_delete_confirm		= tpl_main.find('#WooZone-tpl-delete-confirm').html(),
			_tpl_add_confirm		= tpl_main.find('#WooZone-tpl-add-confirm').html();

		tpl_delete_confirm 			= $(_tpl_delete_confirm);
		tpl_add_confirm 			= $(_tpl_add_confirm);
	};


	//:: TRIGGERS
	function triggers() {

		//:: goto save settings
		maincontainer.on('click', '#goto-save-settings', function(e) {
			e.preventDefault();

			setTimeout(function() {
				WooZone.scrollToElement( $('.WooZone-saveOptions'), null, { 'verticalOffset' : 0, 'time' : 500 } );
			}, 50);
		});

		//:: reload keys list
		maincontainer.on('click', '.WooZone-mk-action-reload', function(e) {
			e.preventDefault();

			load_available_keys();
		});
		//load_available_keys(); // default page load

		//:: publish key
		maincontainer.on('click', '.WooZone-mk-action-publish', function(e) {
			e.preventDefault();

			var $this 	= $(this),
				row 	= $this.parents('tr:first').eq(0),
				itemid 	= row.data('itemid');

			publish_key( itemid );
		});

		//:: delete key
		maincontainer.on('click', '.WooZone-mk-action-delete', function(e) {
			e.preventDefault();

			var $this 		= $(this),
				row 		= $this.parents('tr:first').eq(0),
				itemid 		= row.data('itemid'),
				row_next 	= row.next('tr.next'),
				_tpl 		= tpl_delete_confirm.clone();

			row.parents('table:first').find('tr.next').hide();

			row_next.find('td').html( _tpl );
			row_next.show();
		});

		maincontainer.on('click', '.WooZone-mk-action-delete-no', function(e) {
			e.preventDefault();

			var $this 		= $(this),
				row 		= $this.parents('tr:first').eq(0);

			row.hide();
		});

		maincontainer.on('click', '.WooZone-mk-action-delete-yes', function(e) {
			e.preventDefault();

			var $this 		= $(this),
				row 		= $this.parents('tr:first').eq(0),
				row_prev 	= row.prev('tr.item'),
				itemid 		= row_prev.data('itemid');

			delete_key( itemid );
		});

		//:: check key
		maincontainer.on('click', '.WooZone-mk-action-check', function(e) {
			e.preventDefault();

			var $this 		= $(this),
				row 		= $this.parents('tr:first').eq(0),
				itemid 		= row.data('itemid'),
				row_next 	= row.next('tr.next');

			row.parents('table:first').find('tr.next').hide();

			check_key( itemid, row_next );
		});

		//:: check new key
		maincontainer.on('click', '.WooZone-mk-action-checknew', function(e) {
			e.preventDefault();

			var $this = $(this);
			check_newkey( $this );
		});

		//:: add key
		maincontainer.on('click', '.WooZone-mk-action-addkeys', function(e) {
			e.preventDefault();

			var $this = $(this);
			add_key( $this );
		});

		maincontainer.on('click', '.WooZone-mk-action-addkeys-no', function(e) {
			e.preventDefault();

			var $this 		= $(this),
				wrapp_msg 	= $this.parents('.status-msg:first').eq(0);

			wrapp_msg.html('');
		});

		maincontainer.on('click', '.WooZone-mk-action-addkeys-yes', function(e) {
			e.preventDefault();

			var $this = $(this);
			add_key_force( $this );
		});
	}


	//:: FUNCTIONS
	// reload keys list
	function load_available_keys() {
		var data = [];

		loading( 'show' );

		data.push({name: 'action', value: 'WooZone_AmzMultiKeysAjax'});
		data.push({name: 'sub_action', value: 'load_available_keys'});
		data.push({name: 'debug_level', value: debug_level});
		
		data = $.param( data ); // turn the result into a query string
		
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {

			if( response.status == 'valid' ){
				maincontainer.find('.WooZone-multikeys-table > tbody').html( response.html );
			}

			aateam_tooltip();
			loading( 'close' );
			
		}, 'json');
	}

	// publish key
	function publish_key( itemid ) {
		var data = [];

		loading( 'show' );

		data.push({name: 'action', value: 'WooZone_AmzMultiKeysAjax'});
		data.push({name: 'sub_action', value: 'publish_key'});
		data.push({name: 'itemid', value: itemid});
		data.push({name: 'debug_level', value: debug_level});
		
		data = $.param( data ); // turn the result into a query string
		
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {

			if( response.status == 'valid' ){
				maincontainer.find('.WooZone-multikeys-table > tbody').html( response.html );
			}
			
			loading( 'close' );
			
		}, 'json');
	}

	// delete key
	function delete_key( itemid ) {
		var data = [];

		loading( 'show' );

		data.push({name: 'action', value: 'WooZone_AmzMultiKeysAjax'});
		data.push({name: 'sub_action', value: 'delete_key'});
		data.push({name: 'itemid', value: itemid});
		data.push({name: 'debug_level', value: debug_level});
		
		data = $.param( data ); // turn the result into a query string
		
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {

			if( response.status == 'valid' ){
				maincontainer.find('.WooZone-multikeys-table > tbody').html( response.html );
			}
			
			loading( 'close' );
			
		}, 'json');
	}

	// check key
	function check_key( itemid, row_next ) {
		var data = [];

		loading( 'show' );

		data.push({name: 'action', value: 'WooZone_AmzMultiKeysAjax'});
		data.push({name: 'sub_action', value: 'check_key'});
		data.push({name: 'itemid', value: itemid});
		data.push({name: 'country', value: $('#country').val()});
		data.push({name: 'debug_level', value: debug_level});
		
		data = $.param( data ); // turn the result into a query string
		
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {

			if ( misc.hasOwnProperty( response, 'status' ) ) {

				var msg = build_check_keys_message( response.status, response.msg );

				row_next.find('td').html( msg );
				row_next.show();
			}
			
			loading( 'close' );
			
		}, 'json');
	}

	// check new key
	function check_newkey( that ) {
		var data = [];

		loading( 'show' );

		data.push({name: 'action', value: 'WooZone_AmzMultiKeysAjax'});
		data.push({name: 'sub_action', value: 'check_newkey'});
		data.push({name: 'AccessKeyID', value: $('#AccessKeyID').val()});
		data.push({name: 'SecretAccessKey', value: $('#SecretAccessKey').val()});
		data.push({name: 'country', value: $('#country').val()});
		data.push({name: 'debug_level', value: debug_level});
		
		data = $.param( data ); // turn the result into a query string
		
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {

			if ( misc.hasOwnProperty( response, 'status' ) ) {

				var msg = build_check_keys_message( response.status, response.msg );
				var parent = that.parent(),
					wrapp_msg = parent.find('.status-msg');

				wrapp_msg.html( msg );
			}
			
			loading( 'close' );
			
		}, 'json');
	}

	// add key
	function add_key( that ) {
		var data = [];

		loading( 'show' );

		data.push({name: 'action', value: 'WooZone_AmzMultiKeysAjax'});
		data.push({name: 'sub_action', value: 'add_key'});
		data.push({name: 'AccessKeyID', value: $('#AccessKeyID').val()});
		data.push({name: 'SecretAccessKey', value: $('#SecretAccessKey').val()});
		data.push({name: 'country', value: $('#country').val()});
		data.push({name: 'debug_level', value: debug_level});
		
		data = $.param( data ); // turn the result into a query string
		
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {

			if ( misc.hasOwnProperty( response, 'status' ) ) {

				var msg = '';
				var parent = that.parent(),
					wrapp_msg = parent.find('.status-msg');

				// check keys on amazon is valid => keys added
				if ( 'valid' == response.status ) {

					msg = build_check_keys_message( response.status, response.msg );

					wrapp_msg.html( msg );

					$('#AccessKeyID').val('');
					$('#SecretAccessKey').val('');

					maincontainer.find('.WooZone-multikeys-table > tbody').html( response.html );
				}
				// check keys on amazin is invalid
				else {

					msg = build_check_keys_message( response.status, response.msg );

					if ( 'invalid' == response.check_amz_status ) {

						var _tpl = tpl_add_confirm.clone();

						wrapp_msg.html( _tpl );

						wrapp_msg.append( $('<div>').html( msg ) );
					}
					else {
						wrapp_msg.html( msg );
					}
				}
			}
			
			loading( 'close' );
			
		}, 'json');
	}

	// add key force
	function add_key_force( that ) {
		var data = [];

		loading( 'show' );

		data.push({name: 'action', value: 'WooZone_AmzMultiKeysAjax'});
		data.push({name: 'sub_action', value: 'add_key_force'});
		data.push({name: 'AccessKeyID', value: $('#AccessKeyID').val()});
		data.push({name: 'SecretAccessKey', value: $('#SecretAccessKey').val()});
		data.push({name: 'country', value: $('#country').val()});
		data.push({name: 'debug_level', value: debug_level});
		
		data = $.param( data ); // turn the result into a query string
		
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {

			if ( misc.hasOwnProperty( response, 'status' ) ) {

				var msg = build_check_keys_message( response.status, response.msg );
				var wrapp_msg = that.parents('.status-msg:first').eq(0);

				wrapp_msg.html( msg );

				// check keys on amazon is valid => keys added
				if ( 'valid' == response.status ) {
					$('#AccessKeyID').val('');
					$('#SecretAccessKey').val('');

					maincontainer.find('.WooZone-multikeys-table > tbody').html( response.html );
				}
			}
			
			loading( 'close' );
			
		}, 'json');
	}

	//:: LOADING
	function loading( status, msg ) {
		var msg = msg || '';

		if ( '' == msg && 'show' == status ) {
			msg = lang.loading;
		}

		//if ( '' != msg ) {
		//	mainloader.find('.WooZone-country-loader-text').html( msg );
		//}

		if ( 'show' == status ) {
			//mainloader.fadeIn('fast');
			WooZone.to_ajax_loader( msg );
		}
		else {
			//mainloader.fadeOut('fast');
			WooZone.to_ajax_loader_close();
		}
	}


	//:: CHECK KEYS MESSAGE
	function build_check_keys_message( status, html ) {
		/*
		var msg = '<p>' + html + "<p>";

		// success
		if ( 'valid' == status ) {
			msg += '<p>WooCommerce Amazon Affiliates was able to connect to Amazon with the specified AWS Key Pair and Associate ID</p>';
		}
		// error
		else {
			msg += '<p>WooCommerce Amazon Affiliates was not able to connect to Amazon with the specified AWS Key Pair and Associate ID. Please triple-check your AWS Keys and Associate ID.</p>';

			if( msg.indexOf('aws:Client.AWS.InvalidAssociate') > -1 ){
				msg += 	'<p><strong>Don\'t panic</strong>, this error is easy to fix, please follow the instructions from ';
				msg += 		'<a href="http://support.aa-team.com/knowledgebase-details/198" target="_blank">here</a>.';
				msg += '</p>';
			}
		}
		*/
		var msg = html;

		// success
		if ( 'valid' == status ) {
			msg = '<div class="WooZone-message WooZone-success">' + msg + '</div>';
		}
		// error
		else {
			msg = '<div class="WooZone-message WooZone-error">' + msg + '</div>';
		}

		msg = '<div class="WooZone-server-status">' + msg + '</div>';

		return msg;
	}


	//:: MISC
	var misc = {

		hasOwnProperty: function(obj, prop) {
			var proto = obj.__proto__ || obj.constructor.prototype;
			return (prop in obj) &&
			(!(prop in proto) || proto[prop] !== obj[prop]);
		},

		arrayHasOwnIndex: function(array, prop) {
			return array.hasOwnProperty(prop) && /^0$|^[1-9]\d*$/.test(prop) && prop <= 4294967294; // 2^32 - 2
		},

		size: function(obj) {
			var size = 0;
			for (var key in obj) {
				if (misc.hasOwnProperty(obj, key)) size++;
			}
			return size;
		}
	}

	// external usage
	return {
		//"background_loading": background_loading
	}
})(jQuery);

