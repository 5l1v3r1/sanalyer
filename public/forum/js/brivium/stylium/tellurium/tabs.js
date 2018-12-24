!function($, window, document, _undefined)
{
	// Tabs 
	var totalWidth = 0;
		curLeft = 0;
		tempWidth = 0;
		tabsWidth = 0;
		surPlus = 0;

	function general()
	{
		curLeft = 0;
		tempWidth = 0;
		surPlus = 0;
		if($('#brTabsWrap').length)
		{
			$('#brTabsWrap').each(function()
			{
				totalWidth = 0;
				$(this).find('.tabs > li').each(function()
				{
					totalWidth += $(this).outerWidth();
				});

				if($(this).outerWidth() >= totalWidth)
				{
					$(this).find('.tabs').removeAttr('style');
					$(this).before($(this).find('.tabs'));
					$(this).remove();
					$(this).find('.tabs > li').removeClass('temp');
				}
				else
				{
					$(this).find('.next').removeClass('hide');
					$(this).find('.prev').addClass('hide');
					$(this).find('.tabs').each(function()
					{
						var tabs = $(this);
						$(this).find('> li').removeClass('temp');
						$(this).find('> li').first().addClass('temp');
						$(this).css({
							'left': 0,
							'width': totalWidth
						});
						brTabs(tabs);
					});
				}
			});
		}
		else
		{
			$('.tabs').each(function()
			{
				totalWidth = 0;
				var tabs = $(this);
				tabs.children().each(function()
				{
					totalWidth += $(this).outerWidth();
				});

				if(tabs.outerWidth() < totalWidth)
				{
					brTabs(tabs);
				}
			});
		}
	}

	function brTabs(tabs)
	{
		if(!tabs.closest('#brTabsWrap').length)
		{		
			tabs.wrap('<div id="brTabsWrap"><div class="tabsList"></div></div>');
		}

		var brTabs = tabs.closest('#brTabsWrap');

		tabs.css({				
			'width': totalWidth,
			'position': 'absolute',
			'top': 0,
			'left': 0
		});

		if(!tabs.find('> li.active:first-child').length)
		{
			tabs.find('.active').prependTo(tabs);
		}
		if(!tabs.find('.active').hasClass('temp'))
		{
			tabs.find('> li').removeClass('temp');
			tabs.find('.active').addClass('temp');
		}

		if(!brTabs.find('.tabsControl').length)
		{
			brTabs.prepend('<div class="tabsControl prev hide"><span></span></div>');
			brTabs.append('<div class="tabsControl next"><span></span></div>');
		}

		tabsWidth = brTabs.find('.tabsList').width();
		surPlus = totalWidth - tabsWidth;

		brTabs.find('.prev').click(function()
		{
			prev($(this), brTabs);
		});

		brTabs.find('.next').click(function()
		{
			next($(this), brTabs);
		});
	}

	function prev(button, brTabs)
	{
		curLeft = brTabs.find('.tabs').position().left;

		if(!button.hasClass('hide') && curLeft < 0 && !button.hasClass('stop'))
		{
			button.addClass('stop');
			prevTempWidth = brTabs.find('.tabs .temp').prev().width();

			brTabs.find('.tabs').animate({
				left: curLeft + prevTempWidth
			}, 300, function()
			{
				curLeft = brTabs.find('.tabs').position().left;
				brTabs.find('.tabs .temp').removeClass('temp').prev().addClass('temp');	
				brTabs.find('.next').removeClass('hide');
				if(curLeft >= 0)
				{
					button.addClass('hide');
				}
				button.removeClass('stop');
			});
		}
	}

	function next(button, brTabs)
	{
		curLeft = brTabs.find('.tabs').position().left;
		if(!button.hasClass('hide') && curLeft*(-1) < surPlus && !button.hasClass('stop'))
		{
			button.addClass('stop');
			tempWidth = brTabs.find('.tabs .temp').width();

			brTabs.find('.tabs').animate({
				left: curLeft - tempWidth
			}, 300, function()
			{	
				curLeft = brTabs.find('.tabs').position().left;
				brTabs.find('.tabs .temp').removeClass('temp').next().addClass('temp');	
				brTabs.find('.prev').removeClass('hide');
				if((curLeft*(-1)) >= surPlus)
				{
					button.addClass('hide');
				}
				button.removeClass('stop');
			});
		}
	}

	$(document).ready(function()
	{
		
		$('.tabs').each(function()
		{
			if($(this).closest('.xenForm').length)
			{
				$(this).closest('.xenForm').before($(this));
			}
		});
		general();
	});

	$(window).resize(function()
	{
		general();
	});
}
(jQuery, this, document);