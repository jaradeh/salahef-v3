/*
Document   :  Sync Monitor
Author     :  Andrei Dinca, AA-Team http://codecanyon.net/user/AA-Team
*/

// Initialization and events code for the app
WooZoneSpeedOptimization = (function ($) {
    "use strict";

    // public
    var debug_level = 0;
    var maincontainer = null;
    var loading = null;
    var mass_optimize = {};
    	mass_optimize.counter = 0,
    	mass_optimize.progress_size = 0,
    	mass_optimize.new_progress = 0,
    	mass_optimize.form_options = null,
	    mass_optimize.products_list = [],
	    mass_optimize.timeout_next_prod = null,
	    mass_optimize.running = false;
	
	$.fn.center = function() {
		this.css("position","absolute");
	    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + "px");
	    return this;
	}; 
   
	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
 		    
			maincontainer = $(".WooZone-speed-optimization");
			loading = maincontainer.find(".WooZone-loader-take-over-wrapper");
			
            triggers();
		});
	})();
	
    function take_over_ajax_loader_close(elm, callback)
    {
    	if( elm.length > 0 ) {
    		$(elm).find(".WooZone-loader-take-over-wrapper").remove();
    	}else{
        	maincontainer.find(".WooZone-loader-take-over-wrapper").remove();
       }
    }

    function take_over_ajax_loader( label, target )
    {
        loading = $('<div class="WooZone-loader-take-over-wrapper"><div class="WooZone-loader-holder"><div class="WooZone-loader"></div> ' + ( label ) + '</div></div>');
        
        if( typeof target != 'undefined' ) {
            target.append(loading);
        }else{
            maincontainer.append(loading);
       }
       
       $('.WooZone-loader-holder').center();
    }
    
    function take_over_confirm( label, notice )
    {
        var confirm_dialog =  '<div class="WooZone-confirm-take-over">';
        	confirm_dialog += 	'<div class="WooZone-confirm-box">';
        	confirm_dialog += 		'<p>' + ( label ) + '</p>';
        	if( !notice ) {
        		confirm_dialog += 		'<div class="WooZone-action-buttons">';
        		confirm_dialog += 			'<a href="#" class="btn-confirm btn-ok">' + speed_optimization_msg.confirm_ok + '</a>';
        		confirm_dialog += 			'<a href="#" class="btn-confirm cancel">' + speed_optimization_msg.confirm_cancel + '</a>';
        		confirm_dialog += 			'<i class="fa fa-check success-icon"></i>';
        		confirm_dialog += 		'</div>';
        	}
        	confirm_dialog += 		'<a href="#" class="x-close" ' + ( notice ? 'style="display:block;"' : '' ) + '><i class="fa fa-times"></i></a>';
        	confirm_dialog += 	'</div>';
        	confirm_dialog += '</div>';
        	
        maincontainer.append( $(confirm_dialog) );
        
        $('.WooZone-confirm-take-over .WooZone-confirm-box').center();
    }
    
    function take_over_confirm_close()
    {
        maincontainer.find(".WooZone-confirm-take-over").remove();
    }
    
    function take_over_info_show( msg )
    {
    	var confirm_box = $('.WooZone-confirm-box');
    	
    	confirm_box.find('p').text( msg );
    	confirm_box.find('a.btn-ok').remove();
    	confirm_box.find('a.cancel').remove();
    	confirm_box.find('.success-icon').show();
    	confirm_box.find('a.x-close').show();
    }

    function load_optimize_product_panel( that )
    {
        take_over_ajax_loader( speed_optimization_msg.loading );
        
        var subaction = 'get_product_popup',
        	product = that.data('product');
        
        if( that.data('type') && that.data('type') == 'mass-optimize' ) {
        	subaction = 'get_mass_optimize_popup';
        	product = $('.WooZone-sync-table input[name="product_id"]').serialize()
        }
        
        jQuery.post( ajaxurl, {
            'action'        : 'WooZoneSpeedOptimizatorAjax',
            'subaction'     : subaction,
            'product'       : product

        }, function(response) {
            if( response.status == 'valid' ){
                maincontainer.append( response.html );
                
                $('#WooZone-speed-optimization-lightbox').show();
                $('#WooZone-speed-optimization-lightbox .WooZone-lightbox-content').center();
            }
          
            take_over_ajax_loader_close();
        }, 'json' );
        
    }
    
    function load_optimize_type_data( that )
    {
        take_over_ajax_loader( speed_optimization_msg.loading );
        
        jQuery.post( ajaxurl, {
            'action'        : 'WooZoneSpeedOptimizatorAjax',
            'subaction'     : 'get_optimize_type_data',
            'product'       : that.data('product'),
            'type'			: that.data('type')

        }, function(response) {
            if( response.status == 'valid' ){
                maincontainer.find('.WooZone-lightbox-the-content').html( response.html );
                that.parent().find('li').removeClass('active');
                that.addClass('active');
                $('#WooZone-speed-optimization-lightbox .WooZone-lightbox-content').center();
            }
          
            take_over_ajax_loader_close();
        }, 'json' );
        
    }
    
    function update_score( product_id, type, new_count, callback )
    {
    	var note_elm = maincontainer.find('.WooZone-lightbox-content').find('.optimize-current-product').find('#optimize-' + type).find('.type-note');
    	
    	take_over_ajax_loader( '', note_elm );
    	
        jQuery.post( ajaxurl, {
            'action'        : 'WooZoneSpeedOptimizatorAjax',
            'subaction'     : 'get_score',
            'product'       : product_id,
            'type'			: type,
            'new_count'		: new_count

        }, function(response) {
            if( response.status == 'valid' ) {
            	note_elm.html( response.new_score );
            }
            
            if( typeof callback == 'function' ) {
            	callback();
            }
        }, 'json' );
    }
	
	function update_all_scores( product_id, where, callback )
    {
    	//take_over_ajax_loader( speed_optimization_msg.loading );
        $('.WooZone-loader-take-over-wrapper').center();
        
        if( where == 'mass_optimize' ) {
        	take_over_ajax_loader( '', $('#WooZone-total-score') );
        }
        
        jQuery.post( ajaxurl, {
            'action'        : 'WooZoneSpeedOptimizatorAjax',
            'subaction'     : 'get_all_scores',
            'product'       : product_id,
            'type'			: $('.WooZone-optimize-options').find('li.active').data('type'),
            'where'			: where

        }, function(response) {
            if( response.status == 'valid' ) {
            	
            	if( where == 'mass_optimize' ) {
            		
            		$('#WooZone-total-score').html( response.new_total_score );
            		
            	}else{
            		
	            	maincontainer.find('.WooZone-sync-table tr[data-id="' + product_id + '"] td').eq(4).html(response.new_total_score);
	            	
	            	if( $('.WooZone-mass-optimize-content').length == 0 ) {
		            	$('#WooZone-total-score').html( response.new_total_score );
		            	$('.WooZone-optimize-options').find('li.active span').remove();
		            	$('.WooZone-optimize-options').find('li.active').prepend( '<span class="score-' + response.new_type_score.toLowerCase() + '">' + response.new_type_score + '</span>' );
		            	
		            	$('.WooZone-optimizer-notice').remove();
		            	maincontainer.find('.WooZone-lightbox-the-content').prepend( response.new_notice_score );
	            	}
	            	
	            }
	            
	            if( typeof callback == 'function' ) {
	            	callback();
	            }
            }
          
          	if( where == 'mass_optimize' ) {
          		take_over_ajax_loader_close( '#WooZone-total-score' );
          	}else{
            	take_over_ajax_loader_close();
           }
        }, 'json' );
    }
    
    function delete_image( that )
    {
        take_over_ajax_loader( speed_optimization_msg.delete_img );

        jQuery.post( ajaxurl, {
            'action'        : 'WooZoneSpeedOptimizatorAjax',
            'subaction'     : 'delete_image',
            'attachment'    : that.data('attachment'),
            'product'       : that.data('product')

        }, function(response) {
            if( response.status == 'valid' ){
                that.parents('li').eq(0).remove();
                update_all_scores( that.data('product') );
                take_over_confirm_close();
            }
          	
            take_over_ajax_loader_close();
        }, 'json' );
	
    }
    
    function optimize_attributes( that )
    {
    	take_over_ajax_loader( speed_optimization_msg.optimize_attr );
		
        jQuery.post( ajaxurl, {
            'action'        : 'WooZoneSpeedOptimizatorAjax',
            'subaction'     : 'optimise_attributes',
            'product'       : that.data('product')

        }, function(response) {
            if( response.status == 'valid' ){
            	$('#WooZone-optimised-attributes').text(response.optimised_attributes);
            	$('.WooZone-optimised-attributes').show();
            	$('a.WooZone-optimize-attributes').remove();
                
                update_all_scores( that.data('product') );
                                
                take_over_confirm_close();
            }
     
        }, 'json' );
    }
    
    function delete_variation( that )
    {
        take_over_ajax_loader( speed_optimization_msg.delete_variation );

        jQuery.post( ajaxurl, {
            'action'        : 'WooZoneSpeedOptimizatorAjax',
            'subaction'     : 'delete_variation',
            'product'		: that.data('product'),
            'variation'     : that.data('variation'),
            'variation_img'	: that.data('variation-img')

        }, function(response) {
            if( response.status == 'valid' ){
                $('#variation-' + that.data('variation')).remove();
                update_all_scores( that.data('product') );
                
                if( response.new_count == '0' ) {
                	$('#WooZone-product-variations-wrapper').html('<h3 style="text-align:center;">' + speed_optimization_msg.no_variations_msg + '</h3>');
                	$('.WooZone-sync-table-row[data-id="' + that.data('product') + '"]').find('.WooZone-variable-prod').remove();
                }
            }
            
          	take_over_info_show( response.msg );
            take_over_ajax_loader_close();
        }, 'json' );
	
    }
    
    function delete_variations( that )
    {	
		take_over_ajax_loader( speed_optimization_msg.delete_variations );
        
        jQuery.post( ajaxurl, {
            'action'        : 'WooZoneSpeedOptimizatorAjax',
            'subaction'     : 'delete_selected_variations',
            'product'		: that.data('product'),
            'variations'    : $('form[name="product_variations"] input[name="variation_id"]').serialize(),
            'variations_img'    : $('form[name="product_variations"] input[name="variation_img"]').serialize()

        }, function(response) {
            if( response.status == 'valid' ){
            	$.each(response.variations_removed, function(k, val) {
            		$('#variation-' + val).remove();
            	});
            	update_all_scores( that.data('product') );
            	
            	if( response.new_count == 0 ) {
            		$('#WooZone-product-variations-wrapper').html('<h3 style="text-align:center;">' + speed_optimization_msg.no_variations_msg + '</h3>');
	            	$('.WooZone-sync-table-row[data-id="' + that.data('product') + '"]').find('.WooZone-variable-prod').remove();
            	}
            }
            
            take_over_info_show( response.msg );
            take_over_ajax_loader_close();
        }, 'json' );
        
    }
    
    function remove_from_categ( that )
    {
        take_over_ajax_loader( speed_optimization_msg.remove_categ );

        jQuery.post( ajaxurl, {
            'action'        : 'WooZoneSpeedOptimizatorAjax',
            'subaction'     : 'remove_from_categ',
            'product'		: that.data('product'),
            'categ'			: that.data('categ')

        }, function(response) {
            if( response.status == 'valid' ){
            	$('#term-' + that.data('categ')).remove();
            	update_all_scores( that.data('product') );
            }
            
          	take_over_info_show( response.msg );
            take_over_ajax_loader_close();
        }, 'json' );
	
    }
    
    function remove_from_categs( that )
    {
        take_over_ajax_loader( speed_optimization_msg.remove_categs );

        jQuery.post( ajaxurl, {
            'action'    : 'WooZoneSpeedOptimizatorAjax',
            'subaction' : 'remove_from_categs',
            'product'	: that.data('product'),
            'categs'    : $('form[name="product_categories"] input[name="term_id"]').serialize(),

        }, function(response) {
            if( response.status == 'valid' ){
            	$.each(response.categs_removed, function(k, val) {
            		$('#term-' + val).remove();
            	});
            	update_all_scores( that.data('product') );
            }
          	
          	take_over_info_show( response.msg );
            take_over_ajax_loader_close();
        }, 'json' );
	
    }
    
    function start_mass_optimize_prod_type( product_id, type, option, callback )
    {
    	if( mass_optimize.running == true ) {
	    	$('#optimize-' + type).find('.loading-icon').show();
	    	$('#optimize-' + type).find('.done-icon').hide();
	    	
	    	jQuery.post( ajaxurl, {
	            'action'    : 'WooZoneSpeedOptimizatorAjax',
	            'subaction' : 'mass_optimize',
	            'type'		: type,
	            'option': option[type],
	            'product': product_id
	        }, function(response) {
	            if( response.status == 'valid' ){
	            	
	            	if( response.status == 'valid' ) {
	            		update_score( product_id, type, response.new_count );
	            	}
	            	
	            	$('#optimize-' + type).find('.loading-icon').hide();
	            	$('#optimize-' + type).find('.done-icon').show();
	            	
	            	if( typeof callback == 'function' ) {
		        		callback( response );
		        		return;
		        	}
		        	
	            	start_mass_optimize_prod_type( product_id, 'woo_attributes', option, function(response) {
	            		start_mass_optimize_prod_type( product_id, 'children', option, function(response) {
	            			start_mass_optimize_prod_type( product_id, 'categories', option, function(response) {
	            				
				            	// Update Type Score
				            	//if( response.new_count > 0 ) {
				            		update_score( product_id, 'categories', response.new_count, function() {
				            			// Final Update Total Score
				            			update_all_scores( product_id );
						            	update_all_scores( product_id, 'mass_optimize', function() {
						            		
						            		// Remove optimized product and show next product
						            		mass_optimize.timeout_next_prod = setTimeout( function() {
						            			
						            			// UPDATE Progress bar
								            	$('#optimise-current-prod').text( mass_optimize.counter );
								            	mass_optimize.new_progress = parseFloat(mass_optimize.new_progress) + parseFloat(mass_optimize.progress_size);
								            	$('.optimise-progress-bar-wrapper span').animate({'width' : mass_optimize.new_progress + '%'}, 'slow');
								            	
						            			$('.optimize-current-product').html( $('.optimize-next-product').html() );
								            	
								            	take_over_ajax_loader( '', $('.optimize-next-product') );
							            		
								            	// [REMOVE OPTIMIZED PRODUCT FROM LIST]
								            	var product_id_index = mass_optimize.products_list.indexOf(product_id);
								            	if( product_id_index > -1 ) {
								            		mass_optimize.products_list.splice( product_id_index, 1 );
								            	}
								            	
								            	if( mass_optimize.products_list.length > 0 ) {
								            		//console.log('[Products list]: ' + mass_optimize.products_list);
								            		//console.log('[NEXT]: ' + mass_optimize.products_list[0]);
								            		
								            		if( typeof mass_optimize.products_list[1] != 'undefined' ) {
								            			jQuery.post( ajaxurl, {
												            'action'    : 'WooZoneSpeedOptimizatorAjax',
												            'subaction' : 'mass_optimize_next_prod',
												            'product': mass_optimize.products_list[1],
												        }, function(response) {
												            if( response.status == 'valid' ){
												            	$('.optimize-next-product').html( response.next_prod );
												            	
												            	// [NEXT PRODUCT] Recursive function
												            	start_mass_optimize_prod();
										            		}
										           		}, 'json' );
										           	}else{
										           		$('.optimize-next-product-title').remove();
									           			$('.optimize-next-product').remove();
									           			
										           		// [NEXT PRODUCT] Recursive function
										            	start_mass_optimize_prod();
										           	}
									           	}else{
									           		// If optimize is finished, show done message
									           		$('.optimize-current-product').remove();
									           		$('.optimize-next-product-title').remove();
									           		$('.optimize-next-product').remove();
									           		$('.WooZone-mass-optimizer').append( '<h3 style="text-align:center;">' + speed_optimization_msg.done + '<i class="fa fa-check success-icon" style="font-size:20px; margin-left:10px; color:#2ecc71;"></i></h3><h3 style="text-align:center;">' + speed_optimization_msg.done_all_products + '</h3>' );
									           		
									           		$('.WooZone-btn-optimize-stop').text( speed_optimization_msg.confirm_close );
	        										$('.WooZone-btn-optimize-stop').addClass('WooZone-close-popup-btn');
									           	}
									           	
						            		}, 1000);
						            	});
				            		});
				            	//}
				            	
	            			});
	            		});
	            	});
	            	
	            	mass_optimize.counter++;
	            }
	            
			}, 'json');
		}
    }
    
    function start_mass_optimize_prod()
    {
    	var options = {
    		'attachments': mass_optimize.form_options.find('select[name="mass-optimize-images"]').val(),
    		'woo_attributes': mass_optimize.form_options.find('select[name="mass-optimize-attributes"]').val(),
    		'children': mass_optimize.form_options.find('select[name="mass-optimize-variations"]').val(),
    		'categories': mass_optimize.form_options.find('select[name="mass-optimize-categories"]').val()
    	};
    	
		var product_id = mass_optimize.products_list[0];
		
		start_mass_optimize_prod_type( product_id, 'attachments', options );
		
    }
    
    function mass_optimize_products( that )
    {
    	take_over_ajax_loader( speed_optimization_msg.loading );
    	
    	mass_optimize.form_options = $('form[name="mass-optimize-options"]');
    	
    	// empty product list before start a new list
    	mass_optimize.products_list = [];
    	
    	$('.WooZone-sync-table input[name="product_id"]:checked').each(function() {
    		mass_optimize.products_list.push( $(this).val() );
    	});
		
		mass_optimize.progress_size = (100 / mass_optimize.products_list.length).toFixed(2);
		mass_optimize.new_progress = 0;
		mass_optimize.counter = 0;
		
        jQuery.post( ajaxurl, {
            'action'    : 'WooZoneSpeedOptimizatorAjax',
            'subaction' : 'mass_optimize_products',
            'products': $('.WooZone-sync-table input[name="product_id"]:checked').serialize()
        }, function(response) {
            if( response.status == 'valid' ){
            	$('.WooZone-mass-optimize-content .WooZone-mass-optimizer').html( response.html );
            	$('.WooZone-mass-optimize-content').find('.WooZone-info').html( '<i class="fa fa-info"></i>' + speed_optimization_msg.start_optimize_info );
            	$('.WooZone-mass-optimize-content').find('.WooZone-info').css({'display':'block', 'width':'auto'});
            	$('.WooZone-mass-optimize-content').find('.WooZone-info-box a').remove();
            	$('.WooZone-mass-optimize-content').find('.optimise-count').html( '<span id="optimise-current-prod">0</span>/' + $('.WooZone-sync-table input[name="product_id"]:checked').length );
            	
            	mass_optimize.running = true;
            	
            	start_mass_optimize_prod();
            }
            take_over_ajax_loader_close();
        }, 'json' );
    }

    function triggers()
    {   
        maincontainer.on('click', 'a.WooZone-optimize-product, .WooZone-optimise-all-images, .WooZone-optimise-all-attributes', function(e){
            e.preventDefault();
            
            if( $(this).hasClass('WooZone-optimise-all-attributes') && $('.WooZone-sync-table input[name="product_id"]:checked').length == 0 ) {
            	
            	take_over_confirm( speed_optimization_msg.confirm.mass_optimize_empty_products, true );
            	
            }else{
            	
            	load_optimize_product_panel( $(this) );
            
            }
        });
        
        maincontainer.on('click', 'a.WooZone-close-popup-btn', function(e){
        	e.preventDefault();
        	
        	$('#WooZone-speed-optimization-lightbox').remove();
        });

        maincontainer.on('click', 'a.WooZone-delete-img', function(e){
            e.preventDefault();
            var that = $(this);
			
			take_over_confirm( speed_optimization_msg.confirm.image );
			
			$('.WooZone-confirm-box a.btn-ok').bind('click', function(e) {
	        	e.preventDefault();
	        	delete_image( that );
	        });
        });
        
        maincontainer.on('click', 'a.WooZone-optimize-attributes', function(e){
            e.preventDefault();
			var that = $(this);
			
			take_over_confirm( speed_optimization_msg.confirm.attr );
			
			$('.WooZone-confirm-box a.btn-ok').bind('click', function(e) {
	        	e.preventDefault();
	        	optimize_attributes( that );
	        });
        });
        
        maincontainer.on('click', 'a.WooZone-delete-variation', function(e){
            e.preventDefault();
			var that = $(this);
			
			take_over_confirm( speed_optimization_msg.confirm.variation );
			
			$('.WooZone-confirm-box a.btn-ok').bind('click', function(e) {
	        	e.preventDefault();
	        	delete_variation( that );
	        });
        });
        
        maincontainer.on('click', 'a.WooZone-delete-variations', function(e){
            e.preventDefault();
			var that = $(this);
			
			take_over_confirm( speed_optimization_msg.confirm.variations );
			
			$('.WooZone-confirm-box a.btn-ok').bind('click', function(e) {
	        	e.preventDefault();
	        	delete_variations( that );
	        });
        });
        
        maincontainer.on('click', '.select-unselect_all', function(){
            var checkBoxes = $('.WooZone-lightbox-the-content form table').find('input[type="checkbox"]');
        	checkBoxes.prop("checked", !checkBoxes.prop("checked"));
        });
        
        maincontainer.on('click', 'a.WooZone-show-variations', function(e){
            e.preventDefault();
            $('#WooZone-product-variations-wrapper').toggle();
        });
        
         maincontainer.on('click', 'a.WooZone-btn-remove-from-categ', function(e){
            e.preventDefault();
			var that = $(this);
			
			take_over_confirm( speed_optimization_msg.confirm.categ );
			
			$('.WooZone-confirm-box a.btn-ok').bind('click', function(e) {
	        	e.preventDefault();
	        	remove_from_categ( that );
	        });
        });
        
        maincontainer.on('click', 'a.WooZone-remove-categs', function(e){
            e.preventDefault();
			var that = $(this);
			
			take_over_confirm( speed_optimization_msg.confirm.categs );
			
			$('.WooZone-confirm-box a.btn-ok').bind('click', function(e) {
	        	e.preventDefault();
	        	remove_from_categs( that );
	        });
        });
        
        maincontainer.on('click', 'input[type="checkbox"].check-uncheck-all', function(e) {
        	var checkBoxes  = maincontainer.find('.WooZone-sync-table tbody input[type="checkbox"]');
        	checkBoxes .prop("checked", !checkBoxes .prop("checked"));
        });
        
        $(window).scroll(function() {
        	if( $('#WooZone-speed-optimization-lightbox .WooZone-lightbox-content').length > 0 ) {
        		$('#WooZone-speed-optimization-lightbox .WooZone-lightbox-content').center();
        	}
        	if( $('.WooZone-confirm-take-over .WooZone-confirm-box').length > 0 ) {
        		$('.WooZone-confirm-take-over .WooZone-confirm-box').center();
        	}
        });
        
        maincontainer.on('click', '.WooZone-optimize-options li', function(e) {
        	e.preventDefault();
        	load_optimize_type_data( $(this) );
        });
        
        maincontainer.on('click', '.WooZone-confirm-box a.cancel, .WooZone-confirm-box a.btn-close, .WooZone-confirm-box a.x-close', function(e) {
        	e.preventDefault();
        	take_over_confirm_close();
        });
        
        maincontainer.on('click', '.WooZone-mass-optimize-btn', function(e) {
        	e.preventDefault();
        	mass_optimize_products( $(this) );
        });
        
        maincontainer.on('click', '.WooZone-btn-optimize-stop', function(e) {
        	e.preventDefault();
        	
        	mass_optimize.running = false;
        	clearTimeout( mass_optimize.timeout_next_prod );
        	
        	$(this).text( speed_optimization_msg.confirm_close );
        	$(this).addClass('WooZone-close-popup-btn');
        	
        });

        maincontainer.on('change', 'select[name="mass-optimize-variations"]', function(e) {
            if( $(this).val() == 0 ) {
                $(this).parent().append( '<p>' + speed_optimization_msg.no_variations_disclaimer + '</p>' );
            }else{
                $(this).parent().find('p').remove();
            }
        });
    }

	// external usage
	return {
    }
})(jQuery);
