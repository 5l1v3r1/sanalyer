$(document).ready(function(){
	hiddenSelectorTrigger();
});
function hiddenSelectorTrigger(){
	$('.hiddenSelector').change(function(){
		$outer = $(this).closest('.hideParent');
		if($(this).val()){
			$outer.children('.hiddenContainer').addClass('active').hide();
			$outer.children('.hiddenContainer.hiddenContainer_'+$(this).val()).show();
		}else{
			$outer.children('.hiddenContainer').hide();
		}
	});
	$('.hiddenSelector').each(function(){
		$outer = $(this).closest('.hideParent');
		if($(this).val()){
			$outer.children('.hiddenContainer').addClass('active').hide();
			$outer.children('.hiddenContainer.hiddenContainer_'+$(this).val()).removeClass('active').show();
		}else{
			$outer.children('.hiddenContainer').addClass('active').hide();
		}
	});
}
/** @param {jQuery} $ jQuery Object */
!function($, window, document, _undefined)
{
	/**
	 * Censor word listener for the options page. This handles automatically
	 * creating additional text boxes when necessary.
	 *
	 * @param jQuery li.BRMSTabListener to listen to
	 */
	XenForo.BRMSTabListener = function($element) { this.__construct($element); };
	XenForo.BRMSTabListener.prototype =
	{
		__construct: function($element)
		{
			this.$element = $element;
			$this = this;
			this.$tabKind = $element.find('select.tabKind').change($.context($this, 'rebuilHeader'));
			$tabType = $element.find('.hiddenContainer_'+ $this.$tabKind.val() +' select.tabType');
			$element.find('select.tabType').change($.context($this, 'rebuilHeader'));

			this.$tabHeader = $element.find('.textHeading .tabHeader');
			if(this.$tabKind.find('option:selected').text() && $tabType.find('option:selected').text()){
				this.$tabHeader.text($this.$tabKind.find('option:selected').text() + ' - ' + $tabType.find('option:selected').text());
			}
		},

		rebuilHeader: function()
		{
			$this = this;
			$tabHeader = this.$tabHeader;
			$tabKind = this.$tabKind;
			$tabType = this.$element.find('.hiddenContainer_'+ $tabKind.val() +' select.tabType');
			if($tabKind.find('option:selected').text() && $tabType.find('option:selected').text()){
				this.$tabHeader.text($tabKind.find('option:selected').text() + ' - ' + $tabType.find('option:selected').text());
			}
		}
	}
	XenForo.BRMSNewTabListener = function($element) { this.__construct($element); };
	XenForo.BRMSNewTabListener.prototype =
	{
		__construct: function($element)
		{
			$element.find('select.tabType').one('change', $.context(this, 'createChoice'));
			$tabKind = $element.find('select.tabKind');
			$tabType = $element.find('select.tabType');
			$tabHeader = $element.find('.textHeading .tabHeader');
			if($tabKind.val() && $tabType.val()){
				$tabHeader.text($tabKind.val() + ' - ' + $tabType.val());
			}
			this.$element = $element;
			if (!this.$base)
			{
				this.$base = $element.clone();
			}
		},

		createChoice: function()
		{
			var $new = this.$base.clone(),
				nextCounter = this.$element.parent().children().length;

			$new.find('*[name]').each(function()
			{
				var $this = $(this);
				$this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, '[' + nextCounter + ']'));
			});
			$lastOrder = $('.lastOrderHolder').val()+1;
			$new.find('.orderValue').val($lastOrder);
			$lastOrder = $('.lastOrderHolder').val($lastOrder);
			$new.find('label').each(function()
			{
				var $this = $(this);
				$this.removeAttr('for');
			});

			$new.find('*[id]').each(function()
			{
				var $this = $(this);
				$this.removeAttr('id');
				XenForo.uniqueId($this);

				if (XenForo.formCtrl)
				{
					XenForo.formCtrl.clean($this);
				}
			});
			$new.xfInsert('insertAfter', this.$element);
			hiddenSelectorTrigger();
			this.__construct($new);
		}
	};

	XenForo.BRMSCollapse = function($element) {
		$listTabs = $('.listTabs');
		$element.click(function(e){
			e.preventDefault();
			if($element.hasClass('collapsed')){
				$element.removeClass('collapsed');
				$listTabs.find('.tabContent').xfSlideDown(500);
			}else{
				$element.addClass('collapsed');
				$listTabs.find('.tabContent').xfSlideUp(500);
			}
			return false;
		});
	};
	// *********************************************************************

	XenForo.register('li.BRMSTabListener', 'XenForo.BRMSTabListener');
	XenForo.register('.BRMSCollapse', 'XenForo.BRMSCollapse');
	XenForo.register('li.BRMSNewTabListener', 'XenForo.BRMSNewTabListener');

}
(jQuery, this, document);
