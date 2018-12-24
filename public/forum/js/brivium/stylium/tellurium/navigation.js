!function($, window, document, _undefined)
{
	XenForo.updateVisibleNavigationTabs = function(){
		return false;
	},

	XenForo.updateVisibleNavigationLinks = function()
	{
		return false;
	},

	$('#navigation').ready(function()
	{
		var navigation = $('#navigation');
			navTabs = navigation.find('.navTabs');
			publicTabs = navTabs.find('.publicTabs');
			navTab = navTabs.find('.navTab');
			visitorTabs = navTabs.find('.visitorTabs');
			publicInnerTabs = publicTabs.find('> .navTab');
			visitorInnerTabs = visitorTabs.find('> .navTab');
			navLink = navTab.find('.navLink');

		$('.navTabs.showAll .publicTabs').bind('mousewheel DOMMouseScroll', function(e) {
		    var scrollTo = null;

		    if (e.type == 'mousewheel') {
		        scrollTo = (e.originalEvent.wheelDelta * -1);
		    }
		    else if (e.type == 'DOMMouseScroll') {
		        scrollTo = 40 * e.originalEvent.detail;
		    }

		    if (scrollTo) {
		        e.preventDefault();
		        $(this).scrollTop(scrollTo + $(this).scrollTop());
		    }
		});

		// Out click element

		$(XenForo._isWebkitMobile ? document.body.children : document).on('click', function(clickEvent)
		{
			if (!$(clickEvent.target).closest('.navTabs .publicTabs .navTab.brShow').length)
			{
				$('.navTabs .navTab.brShow .tabLinks').stop(true,true).slideUp(300).fadeOut(300);
				$('.navTabs .navTab.brShow').removeClass('brShow');
			}
		});

		function check()
		{
			var maxWidth =  0;
			publicInnerTabs.each(function()
			{
				if($(this).find('.navLink').length)
				{
					$(this).find('.navLink').css({
						'text-transform': 'uppercase',
						'font-weight': 'bold'
					});
					$(this).find('.navLink').attr('data-width', $(this).find('.navLink').outerWidth());
					maxWidth += parseInt($(this).find('.navLink').attr('data-width'));
					$(this).find('.navLink').removeAttr('style');
				}
			});
			maxWidth = maxWidth + visitorTabs.outerWidth() + 1;

			if(maxWidth >= navTabs.width())
			{
				mobile();
			}
			else
			{
				desktop();
			}
		}
		check();

		function mobile()
		{
			$('body').addClass('navMobile');
			publicTabs.outerHeight($(window).height());
			navTabs.hasClass('showAll') == false ? navTabs.addClass('showAll') : '';
			navTabs.find('.brHiddenMenu').length == 0 ? navTabs.prepend('<div class="brHiddenMenu"><span class="menuIcon"></span></div>') : '';
			publicTabs.find('.brPublicTabInner').length == 0 ? publicTabs.wrapInner('<div class="brPublicTabInner"></div>') : '';
			publicTabs.find('.brNavHeader').length == 0 ? publicTabs.prepend('<div class="brNavHeader"><span class="brBack">'+XenForo.phrases.back+'</span></div>') : '';

			publicTabs.find('.brBack').click(function()
			{
				hideMenu();
			});

			publicTabs.find('.OverlayTrigger').click(function()
			{
				hideMenu();
			});

			navTab.each(function()
			{
				if(!$(this).closest('.visitorTabs').length)
				{
					$(this).addClass('mawbutton');
					$(this).mawbutton({
						speed : 500,
						scale : 3,
						effect : "ripple",
						transitionEnd:function(){
						}
					});
				}

				if(!$(this).find('.brArrow').length && $(this).find('.tabLinks').length && $(this).find('.navLink').length)
				{
					$(this).prepend('<span class="brArrow"></span>');
					$(this).find('.brArrow').click(function(e)
					{
						e.preventDefault();
						e.stopPropagation();
						arrowClick($(this));
					});
				}

				$(this).click(function()
				{
					arrowClick($(this).find('.brArrow'));
				});

				subNavCount = $(this).find('.tabLinks .secondaryContent li').length;
				if(!$(this).find('.subNavCount').length && subNavCount > 0)
				{
					$(this).append('<span class="subNavCount">'+subNavCount+'</span>');
				}

				$(this).find('.tabLinks .secondaryContent li a').click(function(e)
				{
					e.stopPropagation();

					$(this).mawbutton({
						speed : 500,
						scale : 3,
						effect : "ripple",
						transitionEnd:function(){
						}
					});
				});
			});

			navTabs.find('.brHiddenMenu').click(function()
			{
				showNav();
			});
		}

		function desktop()
		{
			$('body').removeClass('navMobile');
			if(navTabs.hasClass('showAll'))
			{
				$(this).removeClass('mawbutton');
				navTabs.removeClass('showAll');
				navTabs.find('.brHiddenMenu').remove();
				publicTabs.removeAttr('style');
				publicTabs.find('.selected .tabLinks').removeAttr('style');
				publicTabs.find('.brNavHeader').remove();
				$('#brExposeMask').remove();
			}
			navTab.find('.subNavCount').remove();
			if($('.brPublicTabInner').length)
			{
				publicInnerTabs.unwrap();
			}

			// Hover to show submenu
			navTab.each(function()
			{
				var $this = $(this);
				$(this).find('.navLink').hover(function()
				{
					hover($this);
				});
				$(this).find('a[rel="Menu"]').hover(function()
				{
					$('.navTabs .navTab.brShow .tabLinks').stop(true,true).slideUp(300).fadeOut(300);
					$('.navTabs .navTab.brShow').removeClass('brShow');
				});
			});
		}

		function hover(navTab)
		{
			if(!navTab.hasClass('brShow') && !navTab.hasClass('selected') && navTab.closest('.publicTabs').length)
			{
				if(navTab.siblings().hasClass('brShow'))
				{
					navTab.siblings().removeClass('brShow');
					navTab.siblings(':not(.selected)').find('.tabLinks').stop(true, true).slideUp(200).fadeOut(200);
				}
				navTab.addClass('brShow');
				navTab.find('.tabLinks').stop(true, true).slideDown(200).fadeIn(200);
				$('.Menu').stop(true, true).slideUp(200);
			}
		}

		function showNav()
		{
			navTabs.find('.publicTabs').addClass('brShow');
			navTabs.find('.publicTabs').stop().animate({left: 0}, 500, 'easeOutCubic');
			navTabs.find('#brExposeMask').length == 0 ? navTabs.append('<div id="brExposeMask"></div>') : '';
			$('#brExposeMask').css({
				'width': $(document).width(),
				'height': $(document).height()
			});

			$('#brExposeMask').fadeIn(500);
			$('#brExposeMask').click(function()
			{
				hideMenu($(this));
			});
		}

		function arrowClick(events)
		{
			var	navTab = events.closest('.navTab');
				tabLinks = navTab.find('.tabLinks');

			if(!navTab.hasClass('toggle'))
			{
				if(navTab.siblings('.toggle').length)
				{
					navTab.siblings().removeClass('toggle');
					navTab.siblings().find('.tabLinks').stop(true,true).slideUp(300);
				}
				navTab.addClass('toggle');
				tabLinks.stop(true,true).slideDown(300);	
			}
			else
			{
				navTab.removeClass('toggle');
				tabLinks.stop(true,true).slideUp(300);
			}
		}

		function hideMenu()
		{
			if (publicTabs.hasClass('brShow')) 
			{
				publicTabs.removeClass('brShow');
				publicTabs.stop().animate({left: -publicTabs.width()}, 500, 'easeOutCubic');
			};
			$('#brExposeMask').stop().fadeOut(500);
		}

		$(window).resize(function()
		{
			check();
			hideMenu();

			$('#brExposeMask').css({
				'width': $(document).width(),
				'height': $(document).height()
			});
		});
	});

	XenForo.brNavTab = function ($navTab){this.__construct($navTab);};
	XenForo.brNavTab.prototype =
	{
		__construct: function($navTab)
		{
			if(!$navTab.find('.tabLinks .secondaryContent').hasClass('pageWidth'))
			{
				$navTab.find('.tabLinks .secondaryContent').addClass('pageWidth');
			}
			$navTab.find('.tabLinks .secondaryContent').removeClass('blockLinksList');
			// $navTab.find('.secondaryContent a').removeAttr('rel');
			// $navTab.find('.Popup').each(function()
			// {
			// 	$(this).find('> a').prependTo($(this).parent());
			// 	$(this).parent().after($(this).find('li'));
			// 	$(this).remove();
			// });
		}
	};
	XenForo.register('.navTab', 'XenForo.brNavTab');
}
(jQuery, this, document);