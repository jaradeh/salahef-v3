/*
Document   :  404 Monitor
Author     :  Andrei Dinca, AA-Team http://codecanyon.net/user/AA-Team
*/

// Initialization and events code for the app
WooZoneSearchRecommendations = (function ($) {
    "use strict";

    // public
    var maincontainer = null;

	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
			maincontainer = $(".WooZone-amazon-recommendations");
			triggers();
		});
	})();

	function launch_search()
	{
		var keyword = maincontainer.find('[name="WooZone-recommendations-keyword"]').val();
		if( keyword == '' ){
			alert('You need to use some keyword!');
			return true;
		}

		maincontainer.find(".WooZone-amazon-recommendations-results").html('');
		maincontainer.find(".WooZone-categs-by-country:visible option").each(function(){
			var that = $(this),
				parent = that.parent('select'),
				val = that.val(),
				country = parent.data('country'),
				make_search = false;

			if( parent.val() == 'aps' ){
				make_search = true;
			}else{
				if( parent.val() == val ){
					make_search = true;
				}
			}

			if( make_search === true ){
				var mkt = 1;
				var data = {
					q: keyword,
					"search-alias": val,
					client: "amazon-search-ui"
				};

				if( country == 'co.uk' ){
					mkt = 3;
				}
				if( country == 'com.br' ){
					mkt = 526970;
					country = 'com';
				}
				if( country == 'ca' ){
					mkt = 7;
					country = 'com';
				}
				if( country == 'cn' ){
					mkt = 3240;
				}
				if( country == 'fr' ){
					mkt = 5;
					country = 'co.uk';
				}
				if( country == 'de' ){
					mkt = 4;
					country = 'co.uk';
				}

				if( country == 'it' ){
					mkt = 35691;
					country = 'co.uk';
				}
				if( country == 'in' ){
					mkt = 44571;
					country = 'co.uk';
				}
				if( country == 'co.jp' ){
					mkt = 6;
				}
				if( country == 'com.au' ){
					mkt = 111172;
					country = 'co.jp';
				}
				if( country == 'com.mx' ){
					mkt = 771770;
					country = 'com';
				}
				if( country == 'es' ){
					mkt = 44551;
					country = 'co.uk';
				}

				var _url = "//completion.amazon." + ( country ) + "/search/complete";
				data['mkt'] = mkt;
				var insane_url = $("#WooZone-hidden-insane-url").val();
				$.ajax({
					url: _url,
					dataType: "jsonp",
					data: data,
					success: function(response) {
						var html = [];

						if( response[1].length > 0 ){
							html.push('<div>');
							html.push(	'<h4>' + ( $.trim(that.text()) ) + ':</h4>');

							$.each( response[1], function(key, value){
								html.push('<a href="' + ( insane_url ) + ( value ) + '" target="_blank">' + ( value ) + '</a>');
							});
							html.push('</div>');
						}else{
							html.push('<div>');
							html.push(	'<h4>' + ( $.trim(that.text()) ) + ':</h4>');
							html.push(	'No results found!');
							html.push('</div>');
						}

						maincontainer.find(".WooZone-amazon-recommendations-results").append( html.join("\n") );
					}
		        });
			}
		});
	}

	function triggers()
	{
		maincontainer.on("click", '.WooZone-country-current', function(){
			var that = $(this),
				list = that.next('ul');

			if( that.hasClass('is_open') ){
				list.hide();
				that.removeClass('is_open');

			}else{
				list.show();
				that.addClass('is_open');
			} 
		});

		maincontainer.on("click", '.WooZone-country-selector ul li', function(){
			var that = $(this),
				list = that.parent('ul'),
				country = that.data('country'),
				current = list.prev('.WooZone-country-current');

			current.find("img").attr( 'src', that.find("img").attr('src') );
			current.find("span").text( that.find("span").text() );

			list.find('.is_select').removeClass('is_select');
			that.addClass('is_select');

			list.hide();
			current.removeClass('is_open');

			maincontainer.find(".WooZone-categs-by-country").hide();
			maincontainer.find(".WooZone-categs-by-country[data-country='" + ( country ) + "']").show();
		});

		maincontainer.find(".WooZone-country-selector").each(function(){
			var that = $(this),
				_default = that.data('default');

			that.find("[data-country='" + ( _default ) + "']").click();
		});

		maincontainer.on("click", '.button', function(){
			launch_search();
		});
	}

	// external usage
	return {
    }
})(jQuery);