var XFMG = window.XFMG || {};

!function($, window, document, _undefined)
{
	"use strict";

	XFMG.ItemSlider = XF.Element.newHandler({
		options: {
			auto: false,
			loop: false,
			pager: false,
			item: 6
		},

		init: function()
		{
			if ($.fn.lightSlider)
			{
				this.$target.lightSlider({
					auto: this.options.auto,
					loop: this.options.loop,
					pager: this.options.pager,
					item: this.options.item,
					addClass: 'lightSlider--loaded',
					slideMargin: 5,
					galleryMargin: 0,
					slideMove: 2,
					speed: Modernizr.flexbox ? 400 : 0, // older IE has some animation issues
					rtl: XF.isRtl()
				});
			}
			else
			{
				console.error('Lightslider must be loaded first.');
			}
		}
	});

	XF.Element.register('item-slider', 'XFMG.ItemSlider');
}
(jQuery, window, document);