!function($, window, document, _undefined)
{
	if (XenForo.InlineModForm)
	{
		XenForo.InlineModForm.prototype.positionOverlay = function(checkbox)
		{
			var lastCheckBox = checkbox;
			if (checkbox.checked || this.$form.find('input:checkbox:checked.InlineModCheck').length)
			{
				console.info('Position overlay next to %o', checkbox);

				if (!this.overlay)
				{
					this.createOverlay();
				}
				var isHidden = false;
				if($(checkbox).is(':hidden'))
				{	
					isHidden = true;
					checkbox = $(checkbox).closest('.privateControls');
				}
				
				var overlay = this.overlay,
					offset = $(checkbox).offset(),
					left = offset.left,
					top = offset.top + 50;

				if (XenForo.isRTL())
				{
					left -= overlay.getOverlay().outerWidth() - 5;
				}
				else
				{
					left -= 16; // checkbox width
				}
				
				left = left - overlay.getOverlay().outerWidth() + 17;
				if (overlay.getOverlay().outerWidth() + left > $(window).width() || left < 0)
				{
					left = 5;
					top += 34;
				}

				if (!overlay.isOpened())
				{
					overlay.getConf().left = left;
					overlay.getConf().top = top - $(XenForo.getPageScrollTagName()).scrollTop();
					overlay.load();
				}
				else
				{
					overlay.getOverlay().animate(
					{
						left:left,
						top: top
					}, ((this.lastCheck && !XenForo.isTouchBrowser()) ? XenForo.speed.normal : 0), 'easeOutBack');
					
					$('html, body').animate({
					   scrollTop: top-200
					}, 100);
				}
			}
			else if (this.overlay && this.overlay.isOpened())
			{
				this.overlay.close();
			}

			this.lastCheck = lastCheckBox;
		}
	}
}
(jQuery, this, document);