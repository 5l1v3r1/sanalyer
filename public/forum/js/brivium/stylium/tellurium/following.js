!function($, window, document, _undefined)
{
	XenForo.FollowLink = function($link)
	{
		if($link.length)
		{
			var href = $link.attr('href');

			if(href.search('unfollow') >= 0)
			{
				$link.addClass('brUnFollow');
			}
		}
		$link.click(function(e)
		{
			e.preventDefault();
			$link.get(0).blur();
			XenForo.ajax(
				$link.attr('href'),
				{ _xfConfirm: 1 },
				function (ajaxData, textStatus)
				{
					if (XenForo.hasResponseError(ajaxData))
					{
						return false;
					}

					$link.xfFadeOut(XenForo.speed.fast, function()
					{
						$link
							.attr('href', ajaxData.linkUrl)
							.html(ajaxData.linkPhrase)
							.xfFadeIn(XenForo.speed.fast);
					});
					$link.toggleClass('brUnFollow')
				}
			);
		});
	};
	
	XenForo.create('XenForo.FollowLink', $('a.FollowLink'));
	//XenForo.register('a.FollowLink', 'XenForo.FollowLink');
}
(jQuery, this, document);