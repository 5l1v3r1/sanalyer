!function($, window, document, _undefined)
{
	XenForo.PopupMenu.prototype.showMenu = function(e, instant)
	{
		if (this.$menu.is(':visible'))
		{
			return false;
		}

		//console.log('Show menu event type = %s', e.type);

		var $eShow = new $.Event('PopupMenuShow');
		$eShow.$menu = this.$menu;
		$eShow.instant = instant;
		$(document).trigger($eShow);

		if ($eShow.isDefaultPrevented())
		{
			return false;
		}

		this.menuVisible = true;

		this.setMenuPosition('showMenu');

		if (this.$menu.hasClass('BottomControl'))
		{
			instant = true;
		}

		if(!this.loading)
		{
			if(this.contentSrc)
			{
				this.loading = XenForo.ajax(
						this.contentSrc, '',
						$.context(this, 'loadSuccess'),
						{ type: 'GET' }
					);
					this.$menu.find('.Progress').addClass('InProgress');
					instant = true;
			}
			
			this.conversationsSrc = this.$menu.find('#ConversationsMenu').data('contentsrc');
			this.conversationsDest = this.$menu.find('#ConversationsMenu').data('contentdest');
			this.alertsSrc = this.$menu.find('#AlertsMenu').data('contentsrc');
			this.alertsDest = this.$menu.find('#AlertsMenu').data('contentdest');
			
			if(this.conversationsSrc)
			{
				this.loading = XenForo.ajax(
					this.conversationsSrc, '',
					$.context(this, 'conversationsLoadSuccess'),
					{ type: 'GET' }
				);
			}
			
			if(this.alertsSrc)
			{
				this.loading = XenForo.ajax(
					this.alertsSrc, '',
					$.context(this, 'alertsloadSuccess'),
					{ type: 'GET' }
				);
			}
		}

		this.setActiveGroup();

		this.$control.addClass('PopupOpen').removeClass('PopupClosed');

		this.$menu.stop().xfSlideDown((instant ? 0 : XenForo.speed.xfast), $.context(this, 'menuShown'));

		if (!this.menuEventsInitialized)
		{
			// TODO: make this global?
			// TODO: touch interfaces don't like this
			$(document).bind({
				PopupMenuShow: $.context(this, 'hideIfOther')
			});

			// Webkit mobile kinda does not support document.click, bind to other elements
			if (XenForo._isWebkitMobile)
			{
				$(document.body.children).click($.context(this, 'hideMenu'));
			}
			else
			{
				$(document).click($.context(this, 'hideMenu'));
			}

			var $html = $('html'), t = this, htmlSize = [$html.width(), $html.height()];
			$(window).bind(
			{
				resize: function(e) {
					// only trigger close if the window size actually changed - some mobile browsers trigger without size change
					var w = $html.width(), h = $html.height();
					if (w != htmlSize[0] || h != htmlSize[1])
					{
						htmlSize[0] = w; htmlSize[1] = h;
						t._hideMenu(e);
					}
				}
			});

			this.$menu.delegate('a', 'click', $.context(this, 'menuLinkClick'));
			this.$menu.delegate('.MenuCloser', 'click', $.context(this, 'hideMenu'));

			this.menuEventsInitialized = true;
		}
		
		// Brivium Tabs		
		if(!$('.brTabs > .brActive').length)
		{
			$('.brTabs').children().first().addClass('brActive');
			$('.brTabsContent').children().first().addClass('brActive').css('display','block');
		}
		
		var tabId = 0;
		$('.brTabs').children().each(function()
		{
			tabId++;
			$(this).attr('tab_id', 'brTab_'+tabId);
			$(this).click(function()
			{
				var thisTabId = $(this).attr('tab_id');
				if(!$(this).hasClass('brActive'))
				{
					$(this).addClass('brActive');
					$(this).siblings().removeClass('brActive');
					$('.brTabsContent').children().each(function()
					{
						if($(this).attr('tab_id') == thisTabId)
						{
							$(this).addClass('brActive');
							$(this).slideDown(200);
							$(this).siblings().removeClass('brActive');
							$(this).siblings().slideUp(200);
						}
					});
				}
			});
		});
		
		var tabContentId = 0;		
		$('.brTabsContent').children().each(function()
		{
			tabContentId++;
			$(this).attr('tab_id', 'brTab_'+tabContentId);
		});
	};
	
	XenForo.PopupMenu.prototype.conversationsLoadSuccess = function(ajaxData, textStatus)
	{
		if (XenForo.hasResponseError(ajaxData) || !XenForo.hasTemplateHtml(ajaxData) || !this.conversationsDest)
		{
			return false;
		}

		var $templateHtml = $(ajaxData.templateHtml);

		console.info('Content destination: %o', this.conversationsDest);

		var self = this;

		// append the loaded content to the destination
		$templateHtml.xfInsert(
			this.$menu.data('insertfn') || 'appendTo',
			this.conversationsDest,
			'slideDown', 0,
			function()
			{
				self.$menu.css('min-width', '199px');
				setTimeout(function() {
					self.$menu.css('min-width', '');
				}, 0);
				self.menuShown();
			}
		);
		
		$('#ConversationsMenu .listItem').each(function()
		{
			$(this).find('.avatar').after($(this).find('.posterDate'));
			$(this).find('.avatar').after($(this).find('.listItemText .DateTime'));
			$(this).wrapInner('<div class="brListItemInfo"></div>');
			$(this).find('.brListItemInfo').before($(this).find('.avatar'));
		});
	};
	
	XenForo.PopupMenu.prototype.alertsloadSuccess = function(ajaxData, textStatus)
	{
		if (XenForo.hasResponseError(ajaxData) || !XenForo.hasTemplateHtml(ajaxData) || !this.alertsDest)
		{
			return false;
		}

		var $templateHtml = $(ajaxData.templateHtml);

		console.info('Content destination: %o', this.alertsDest);

		var self = this;

		// append the loaded content to the destination
		$templateHtml.xfInsert(
			this.$menu.data('insertfn') || 'appendTo',
			this.alertsDest,
			'slideDown', 0,
			function()
			{
				self.$menu.css('min-width', '199px');
				setTimeout(function() {
					self.$menu.css('min-width', '');
				}, 0);
				self.menuShown();
			}
		);
		
		$('#AlertsMenu .listItem').each(function()
		{
			$(this).find('.avatar').after($(this).find('.username'));
			$(this).find('.avatar').after($(this).find('.listItemText .DateTime'));
		});
	};

    var _balloonCounterUpdate = XenForo.balloonCounterUpdate;
    XenForo.balloonCounterUpdate = function($balloon, newTotal) {
        if (newTotal > 0) {
        	if(!$('.navTab.account .navLink.unread').length)
        	{
           		$('.navTab.account .navLink').addClass('unread');
        	}
        } else {
        	if($('.navTab.account .navLink.unread').length)
        	{
           		$('.navTab.account .navLink').removeClass('unread');
        	}
        }
        _balloonCounterUpdate.apply(this, arguments);
    };
}
(jQuery, this, document);