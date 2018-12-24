!function($, window, document, _undefined)
{
	XenForo.BRQCTInsert = function($element) { this.__construct($element); };
	XenForo.BRQCTInsert.prototype =
	{
		__construct: function($element)
		{
			this.$element = $element;
			this.$element.change($.context(this,'change'));
		},
		change: function()
		{
			$forumId = this.$element.val();
			if($forumId != 0)
			{
				XenForo.ajax(
					"index.php?brqct-create-thread/BRQCT.json",
					{
						node_id: $forumId,
						xfToken: $('#ctrl_xfToken').val(),
					},
					$.context(this, 'insert')
				);
			}
		},
		insert: function(ajaxData)
		{
			if (XenForo.hasResponseError(ajaxData))
			{
				return false;
			}

			$templateHtml = $(ajaxData.templateHtml);
			$parent = this.$element.closest(".BRQCT");
			$container = $parent.find('#BRQCT_Tellurium');

			if ($container.length == 0)
			{
				$container = $parent.find('form');
			}

			new XenForo.ExtLoader(ajaxData, $.context(function()
			{
				$templateHtml.xfInsert('replaceAll', $container);
			}));
		}
	}

	XenForo.register('.brSelectForum ','XenForo.BRQCTInsert');
}(jQuery, this, document);