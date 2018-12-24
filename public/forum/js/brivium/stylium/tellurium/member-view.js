!function($, window, document, _undefined)
{	
	XenForo.brProfileTabs = function ($tabs){this.__construct($tabs);};
	XenForo.brProfileTabs .prototype =
	{
		__construct: function($tabs)
		{
			this.$tabs = $tabs;
			tabs = $tabs;
			tab = tabs.find('> li');
			this.custom();
		},
		
		custom: function()
		{
			tab.click(function()
			{
				if($(this).is(':first-child'))
				{
					$('#brOnlyProfilePage').show();
				}
				else
				{
					$('#brOnlyProfilePage').hide();
				}
			});
		}
	};
	XenForo.register('.brProfilePanes .tabs', 'XenForo.brProfileTabs');

	XenForo.brPageNavLinkGroup = function ($element){this.__construct($element);};
	XenForo.brPageNavLinkGroup .prototype =
	{
		__construct: function($element)
		{
			if($element.height() < 20)
			{
				$element.hide();
			}
		}
	};
	XenForo.register('.pageNavLinkGroup', 'XenForo.brPageNavLinkGroup');
}
(jQuery, this, document);