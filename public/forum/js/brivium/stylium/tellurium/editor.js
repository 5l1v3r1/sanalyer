!function($, window, document, _undefined)
{
	$(function()
	{		
		$(XenForo._isWebkitMobile ? document.body.children : document).on('click', function(clickEvent)
		{
			if (!$(clickEvent.target).closest('.brHideItems').length)
			{
				$('.brMobile.redactor_box .brHideItems > ul').fadeOut(300).removeClass('show');
			}
		});

		$('.redactor_textCtrl').contents().find('body').focus(function()
		{
			$('.brMobile.redactor_box .brHideItems > ul').fadeOut(300).removeClass('show');
		});
	});

	// Editor form customize
	XenForo.brEditor = function ($toolbar){this.__construct($toolbar);};
	XenForo.brEditor.prototype =
	{
		__construct: function($toolbar)
		{
			this.$toolbar = $toolbar;
			toolbar = $toolbar;
			var $minWidth = 0;
			this.$minWidth;
			minWidth = $minWidth;
			var self = this;
			setTimeout(function()
			{
				toolbar.find('li.redactor_btn_group').each(function()
				{
					minWidth += $(this).outerWidth(true);
					$(this).attr('data-width', $(this).outerWidth(true));
				});
				self.check();
			}, 1);
			$(window).resize($.context(this, 'check'));
		},

		check: function()
		{
			var self = this;
			var	temp = 0,
				maxWidth = toolbar.width()-70;

			var group = toolbar.find('li.redactor_btn_group');
			group.each(function()
			{
				temp += parseInt($(this).attr('data-width'));
				if(temp > maxWidth)
				{
					self.mobile();
					$(this).appendTo(hideItemsUl);
				}
				else if($(this).closest('.brHideItems').length)
				{
					hideItems.before($(this));
				}
			});
			if(minWidth < maxWidth)
			{
				this.desktop();
			}
			else
			{
				var minWidthUl = 0;
				hideItemsUl.find('> li').each(function()
				{
					if(minWidthUl < parseInt($(this).attr('data-width')))
					{
						minWidthUl = parseInt($(this).attr('data-width'));
					}
				});
				hideItemsUl.css('min-width', minWidthUl);
				hideItemsUl.hide();
			}

			toolbar.closest('.redactor_box').find('.redactor_textCtrl').contents().find('body').click(function(){
				hideItemsUl.fadeOut(300).removeClass('show');
			});
		},

		mobile: function()
		{
			if(!toolbar.closest('.redactor_box').hasClass('brMobile'))
			{
				toolbar.closest('.redactor_box').addClass('brMobile');
			}
			if(!toolbar.find('.brHideItems').length)
			{
				toolbar.append('<li class="brHideItems"><span class="brControl"></span><ul></ul></li>');
				var $hideItems = toolbar.find('.brHideItems');
					this.$hideItems = $hideItems;
					hideItems = $hideItems;
					hideItemsUl = hideItems.find('ul');
				var control = toolbar.find('.brControl');
				control.click($.context(this, 'show'));
			}
		},

		desktop: function()
		{
			toolbar.closest('.redactor_box').removeClass('brMobile');
			toolbar.find('.brHideItems').hide();
		},

		show: function(e)
		{
			e.stopPropagation();
			if(!hideItemsUl.hasClass('show'))
			{
				hideItemsUl.addClass('show');
				hideItemsUl.fadeIn(400, 'easeInOutQuint');
			}
			else
			{
				hideItemsUl.fadeOut(300).removeClass('show');
			}
		}	
	};
	XenForo.register('.redactor_toolbar', 'XenForo.brEditor');
	
	
	XenForo.BbCodeWysiwygEditorExtension = function($textarea) { this.__construct($textarea); };
	XenForo.BbCodeWysiwygEditorExtension.prototype =
	{
		__construct: function($textarea)
		{
			this.$textarea = $textarea;
			$(document).bind(
			{
				EditorInit: $.context(this, 'editorInitFunc')
			});
		},
		
		editorInitFunc: function(e, data)
		{
			this.parentCallback = data.config.callback;
			data.config.callback = $.context(this, 'editorInitOfExtension');
		},
		
		editorInitOfExtension: function(ed)
		{
			this.parentCallback.apply(this, arguments);
			if(ed.$box.length)
			{
				var $redactorToolbar = ed.$box.find('.redactor_toolbar');
				XenForo.create('XenForo.brEditor', $redactorToolbar);
			}
		}
	};
	XenForo.register('textarea.BbCodeWysiwygEditor', 'XenForo.BbCodeWysiwygEditorExtension');
}
(jQuery, this, document);