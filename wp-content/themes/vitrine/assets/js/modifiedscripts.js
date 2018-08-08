
//Woocommerce Zoom
(function ($) {

    'use strict';

    var dw, dh, rw, rh, lx, ly;

    var timeoutId;

    var defaults = {

        // The text to display within the notice box if an error occurs loading the zoom image.
        errorNotice: 'The image could not be loaded',

        // The time (in milliseconds) to display the error notice.
        errorDuration: 2500,

        // Prevent clicks on the zoom image link.
        preventClicks: true,

        // Callback function to execute when the flyout is displayed.
        onShow: $.noop,

        // Callback function to execute when the flyout is removed.
        onHide: $.noop,

        // Callback function to execute when the cursor is moved while over the image.
        onMove: $.noop

    };

    /**
     * EasyZoom
     * @constructor
     * @param {Object} target
     * @param {Object} options (Optional)
     */
    function EasyZoom(target, options) {
        this.$target = $(target);
        this.opts = $.extend({}, defaults, options, this.$target.data());

        this.isOpen === undefined && this._init();
    }

    /**
     * Init
     * @private
     */
    EasyZoom.prototype._init = function() {
        this.$link   = this.$target.find('a');
        this.$image  = this.$target.find('img');

        this.$flyout = $('<div class="easyzoom-flyout" />');
        this.$notice = $('<div class="easyzoom-notice" />');

        this.$target.on({
            'mousemove.easyzoom touchmove.easyzoom': $.proxy(this._onMove, this),
            'mouseleave.easyzoom touchend.easyzoom': $.proxy(this._onLeave, this),
            'mouseenter.easyzoom touchstart.easyzoom': $.proxy(this._onEnter, this)
        });

        this.opts.preventClicks && this.$target.on('click.easyzoom', function(e) {
            e.preventDefault();
        });
    };

    /**
     * Show
     * @param {MouseEvent|TouchEvent} e
     * @param {Boolean} testMouseOver (Optional)
     */
    EasyZoom.prototype.show = function(e, testMouseOver) {
        var w1, h1, w2, h2;
        var self = this;
        clearTimeout(timeoutId);

        if (!this.$target.hasClass('is-ready')) {
            return this._loadImage(this.$target.data("zoom-image"), function() {
                if (self.isMouseOver || !testMouseOver) {
                    self.show(e);
                }
            });
        }
        this.$target.parents('.zoom-container').find('.easyzoom-flyout').remove();//Add this line to remove remained old objects
        this.$target.parents('.zoom-container').prepend(this.$flyout);

        w1 = this.$target.width();
        h1 = this.$target.height();

        w2 = this.$flyout.width();
        h2 = this.$flyout.height();

        dw = this.$zoom.width() - w2;
        dh = this.$zoom.height() - h2;

        /* ### Added by Epico ### */
        if( dw <= 0 || dh <=0)
        {
            this.$target.addClass("no-zoom");
        }

        rw = dw / w1;
        rh = dh / h1;

        this.isOpen = true;
		this.$flyout.addClass('shown');

        this.opts.onShow.call(this);

        e && this._move(e);
    };

    /**
     * On enter
     * @private
     * @param {Event} e
     */
    EasyZoom.prototype._onEnter = function(e) {
        var touches = e.originalEvent.touches;

        this.isMouseOver = true;

        if (!touches || touches.length == 1) {
            e.preventDefault();
            this.show(e, true);
        }
    };

    /**
     * On move
     * @private
     * @param {Event} e
     */
    EasyZoom.prototype._onMove = function(e) {
        if (!this.isOpen) return;

        e.preventDefault();
        this._move(e);
    };

    /**
     * On leave
     * @private
     */
    EasyZoom.prototype._onLeave = function() {
        this.isMouseOver = false;
        this.isOpen && this.hide();
    };

    /**
     * On load
     * @private
     * @param {Event} e
     */
    EasyZoom.prototype._onLoad = function(e) {
        // IE may fire a load event even on error so test the image dimensions
        if (!e.target.width) return;

        this.isReady = true;

        this.$notice.detach();
        this.$flyout.html(this.$zoom);
        this.$target.removeClass('is-loading').addClass('is-ready');

        e.data.call && e.data();
    };

    /**
     * On error
     * @private
     */
    EasyZoom.prototype._onError = function() {
        var self = this;

        this.$notice.text(this.opts.errorNotice);
        this.$target.removeClass('is-loading').addClass('is-error');

        this.detachNotice = setTimeout(function() {
            self.$notice.detach();
            self.detachNotice = null;
        }, this.opts.errorDuration);
    };

    /**
     * Load image
     * @private
     * @param {String} href
     * @param {Function} callback
     */
    EasyZoom.prototype._loadImage = function(href, callback) {
        var zoom = new Image;

        this.$target
            .addClass('is-loading');

        this.$zoom = $(zoom)
            .on('error', $.proxy(this._onError, this))
            .on('load', callback, $.proxy(this._onLoad, this));

        zoom.style.position = 'absolute';
        zoom.src = href;
    };

    /**
     * Move
     * @private
     * @param {Event} e
     */
    EasyZoom.prototype._move = function(e) {

        if (e.type.indexOf('touch') === 0) {
            var touchlist = e.touches || e.originalEvent.touches;
            lx = touchlist[0].pageX;
            ly = touchlist[0].pageY;
        } else {
            lx = e.pageX || lx;
            ly = e.pageY || ly;
        }

        var offset  = this.$target.offset();
        var pt = ly - offset.top;
        var pl = lx - offset.left;
        var xt = Math.ceil(pt * rh);
        var xl = Math.ceil(pl * rw);

        // Close if outside
        if (xl < 0 || xt < 0 || xl > dw || xt > dh) {
            this.hide();
        } else {
            var top = xt * -1;
            var left = xl * -1;

            this.$zoom.css({
                top: top,
                left: left
            });

            this.opts.onMove.call(this, top, left);
        }

    };

    /**
     * Hide
     */
    EasyZoom.prototype.hide = function() {
        if (!this.isOpen) return;

        this.$flyout.removeClass('shown');
        this.isOpen = false;
        var elem = this.$flyout;
        timeoutId = setTimeout(function(){
        	elem.remove();
        }, 300);

        this.opts.onHide.call(this);
    };

    // jQuery plugin wrapper
    $.fn.easyZoom = function(options) {
        return this.each(function() {
            var api = $.data(this, 'easyZoom');

            if (!api) {
                $.data(this, 'easyZoom', new EasyZoom(this, options));
            } else if (api.isOpen === undefined) {
                api._init();
            }
        });
    };

    // AMD and CommonJS module compatibility
    if (typeof define === 'function' && define.amd){
        define(function() {
            return EasyZoom;
        });
    } else if (typeof module !== 'undefined' && module.exports) {
        module.exports = EasyZoom;
    }

})(jQuery);


/*
 * DOMParser HTML extension
 * 2012-09-04
 * 
 * By Eli Grey, http://eligrey.com
 * Public domain.
 * NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
 */

/*! @source https://gist.github.com/1129031 */
/*global document, DOMParser*/

DOMParserFlag = false;
if (DOMParserFlag == false) {

    function DOMParser() {
        "use strict";

        var DOMParser_proto = DOMParser.prototype,
            real_parseFromString = DOMParser_proto.parseFromString;

        // Firefox/Opera/IE throw errors on unsupported types
        try {
            // WebKit returns null on unsupported types
            if ((new DOMParser).parseFromString("", "text/html")) {
                // text/html parsing is natively supported
                return;
            }
        } catch (ex) { }
        DOMParser_proto.parseFromString = function (markup, type) {
            if (/^\s*text\/html\s*(?:;|$)/i.test(type)) {
                var
                  doc = document.implementation.createHTMLDocument("")
                ;
                if (markup.toLowerCase().indexOf('<!doctype') > -1) {
                    doc.documentElement.innerHTML = markup;
                }
                else {
                    doc.body.innerHTML = markup;
                }
                return doc;
            } else {
                return real_parseFromString.apply(this, arguments);
            }
        };
    }
    DOMParserFlag = true;
}

/*
* jQuery djax
*
* @version v0.122
*
* Copyright 2012, Brian Zeligson
* Released under the MIT license.
* http://www.opensource.org/licenses/mit-license.php
*
* Homepage:
*   http://beezee.github.com/djax.html
*
* Authors:
*   Brian Zeligson
*
* Contributors:
*  Gary Jones @GaryJones
*
* Maintainer:
*   Brian Zeligson github @beezee
*
*/

/*jslint browser: true, indent: 4, maxerr: 50, sub: true */
/*jshint bitwise:true, curly:true, eqeqeq:true, forin:true, immed:true, latedef:true, noarg:true, noempty:true, nomen:true, nonew:true, onevar:true, plusplus:true, regexp:true, smarttabs:true, strict:true, trailing:true, undef:true, white:true, browser:true, jquery:true, indent:4, maxerr:50, */
/*global jQuery */

// ==ClosureCompiler==
// @compilation_level ADVANCED_OPTIMIZATIONS
// @output_file_name jquery.djax.js
// @externs_url http://closure-compiler.googlecode.com/svn/trunk/contrib/externs/jquery-1.7.js
// ==/ClosureCompiler==
// http://closure-compiler.appspot.com/home

(function ($, exports) {
	'use strict';

    //exceptionClasses : list of classes of body that would be exceptions
    //exceptionURLs : list of urls that would be exceptions
	$.fn.djax = function (selector, exceptions, exceptionURLs) {

		// If browser doesn't support pushState, abort now
		if (!history.pushState) {
			return $(this);
		}

		var self = this,
		    blockSelector = selector,
		    excludes = (exceptions && exceptions.length) ? exceptions : [],

           //Added By Epico *************************************
	       excludeURLs = (exceptionURLs && exceptionURLs.length) ? exceptionURLs : [],
           //**************************************************************************

			djaxing = false;

		// Ensure that the history is correct when going from 2nd page to 1st
		window.history.replaceState(
			{
				'url' : window.location.href,
				'title' : $('title').text()
			},
			$('title').text(),
			window.location.href
		);
		
		self.clearDjaxing = function() {
			self.djaxing = false;
		}

		// Exclude the link exceptions
		self.attachClick = function (element, event) {

			var link = $(element),
				exception = false;

            if(link.attr("target") !== 'undefined' && link.attr("target") == '_blank' || link.hasClass('no_djax'))
                exception = true;

            if( exception == false) {
                $.each(excludes, function (index, exclusion) {
                    if (link.attr('href').indexOf(exclusion) !== -1 ) {
                        exception = true;
                        return false;
                    }
                });
            }

            if( exception == false) {
                $.each(excludeURLs, function (index, exclusion) {
                    if(link.attr("href") == exclusion ) {
                        exception = true;
                        return false;
                    }
                });
            }

			// If the link is one of the exceptions, return early so that
			// the link can be clicked and a full page load as normal
			if (exception) {
				return $(element);
			}

			// From this point on, we handle the behaviour
			event.preventDefault();

			// If we're already doing djaxing, return now and silently fail
			if (self.djaxing) {
				setTimeout(self.clearDjaxing, 1000);
				return $(element);
			}

			$(window).trigger('djaxClick', [element]);
			self.reqUrl = link.attr('href');
			self.triggered = false;
			self.navigate(link.attr('href'), true);

		};

		// Handle the navigation
		self.navigate = function (url, add) {				

			var blocks = $(blockSelector);

			self.djaxing = true;

			// Get the new page
			$(window).trigger(
				'djaxLoading',
				[{
					'url' : url
				}]
			);

			var replaceBlocks = function (response) {

				if (url !== self.reqUrl) {
					self.navigate(self.reqUrl, false);
					return true;
				}

				var result = $(response),
					newBlocks = result.find(blockSelector);

				if (add) {
					window.history.pushState(
						{
							'url' : url,
							'title' : $(result).filter('title').text()
						},
						$(result).filter('title').text(),
						url
					);
				}

				// Set page title as new page title
				$('title').text($(result).filter('title').text());

			    // added By Epico **********************************************************

				var parser = new DOMParser();
				var doc = parser.parseFromString(response, "text/html");
				var docClass = doc.body.getAttribute('class');
				$('body').removeClass().addClass(docClass);
				//pageid attribute for updating wp admin toolbar edit link
				$('body').data("pageid", doc.body.getAttribute('data-pageid'));

				//update meta tags
				var metaTags = doc.getElementsByTagName('meta');
				for (var i = 0, n = metaTags.length; i < n; i++) {
				    if (metaTags[i].getAttribute("name") !== null) {
				        $('head meta[name="' + metaTags[i].getAttribute("name") + '"]').attr("content", metaTags[i].getAttribute("content"))
				    }
				    else if (metaTags[i].getAttribute("property") !== null) {
				        $('head meta[property="' + metaTags[i].getAttribute("property") + '"]').attr("content", metaTags[i].getAttribute("content"))
				    }
				    else (metaTags[i].getAttribute("itemprop") !== null)
				    {
				        $('head meta[itemprop="' + metaTags[i].getAttribute("property") + '"]').attr("content", metaTags[i].getAttribute("content"))
				    }
				}

				//**************************************************************************

				// Loop through each block and find new page equivalent
				blocks.each(function () {

					var id = '#' + $(this).attr('id'),
					    newBlock = newBlocks.filter(id),
					    block = $(this);
					
					if (newBlock.length) {
					    if (block.html() !== newBlock.html()) {

					        // Edit By Epico **********************************************************

							var dom = $(doc);
							var scripts = dom.find("script");
                            var links = dom.find('link[rel="stylesheet"]');
							var styles = dom.find('style');
                            $('body').trigger('djax_updated',[block, newBlock, scripts, links, styles]);

					        //*************************************************************************

						}
					} else {
						block.remove();
					}

				});

				// Loop through new page blocks and add in as needed
				$.each(newBlocks, function () {

					var newBlock = $(this),
					    id = '#' + $(this).attr('id'),
					    $previousSibling;

					// If there is a new page block without an equivalent block
					// in the old page, we need to find out where to insert it
					if (!$(id).length) {

						// Find the previous sibling
						$previousSibling = $(result).find(id).prev();

						if ($previousSibling.length) {
							// Insert after the previous element
							newBlock.insertAfter('#' + $previousSibling.attr('id'));
						} else {
							// There's no previous sibling, so prepend to parent instead
							newBlock.prependTo('#' + newBlock.parent().attr('id'));
						}
					}

									// Only add a class to internal links
					$('a', newBlock).filter(function () {
						return this.hostname === location.hostname;
					}).on('click', function (event) {
						return self.attachClick(this, event);
					});

				});



				// Trigger djaxLoad event as a pseudo ready()
				if (!self.triggered) {
					$(window).trigger(
						'djaxLoad',
						[{
							'url' : url,
							'title' : $(result).filter('title').text(),
							'response' : response
						}]
					);
					self.triggered = true;
					self.djaxing = false;
				}
			};
            // Edit By Epico **********************************************************
			$.ajax({
                url : url,
                data: { djax_req: true },
                beforeSend: function() {
                    $('body').trigger('djax_before_transition');
                },
                success : function (response) {
    				replaceBlocks(response);
                },
                error : function (response) {
                    // handle error
                    console.log('error', response);
                    replaceBlocks(response['responseText']);
                }

			});
            //*************************************************************************
            
		}; /* End self.navigate */

		// Only add a class to internal links
		$(this).find('a').filter(function () {
			return this.hostname === location.hostname;
		}).on('click', function (event) {
			return self.attachClick(this, event);
		});

		// On new page load
		$(window).bind('popstate', function (event) {
			self.triggered = false;
			if (event.originalEvent.state) {
				self.reqUrl = event.originalEvent.state.url;
				self.navigate(event.originalEvent.state.url, false);
			}
		});

	    // added By Epico  ********************************************************

	    self.reInit = function() {
		    $(this).find('a').filter(function () {
			    return this.hostname === location.hostname;
		    }).on('click', function (event) {
			    return self.attachClick(this, event);
		    });
		    return this;
	    };

	    return this;

	    //*************************************************************************

	};

}(jQuery, window));