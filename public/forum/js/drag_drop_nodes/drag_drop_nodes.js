!function($, window, document, _undefined)
{
	XenForo.FilterListItem.prototype.__construct = function($item)
	{
		this.$item = $item;
		this.$textContainer = this.$item.find('h4 em').first();
		this.text = this.$textContainer.text();

		this.$item.find('input.Toggler').click(function(e)
		{
			$(this).closest('h4').toggleClass('disabled', !this.checked);
		});
	};
	
	XenForo.FilterList.prototype.filter = function(e)
	{
		var val = this.$filter.data('XenForo.Prompt').val(),
			prefixMatch = this.$prefixMatch.is(':checked');

		if (this.$filter.hasClass('prompt') || val === '')
		{
			this.$groups.show();
			this.$listItems.show();
			this.applyFilter(this.FilterListItems);
			this.$listCounter.text(this.$listItems.length);
			if (this.lookUpUrl)
			{
				$('.PageNav').show();
			}

			this.removeAjaxResults();
			this.showHideNoResults(false);
			this.deleteCookie();
			return;
		}

		console.log('Filtering on \'%s\'', val);

		this.setCookie();

		if (this.lookUpUrl)
		{
			XenForo.ajax(this.lookUpUrl,
				{ _filter: { value: val, prefix: prefixMatch ? 1 : 0 } },
				$.context(this, 'filterAjax'),
				{ type: 'GET' }
			);
			return;
		}

		var $groups,
			visible = this.applyFilter(this.FilterListItems);

		this.$listCounter.text(visible);

		// hide empty groups
		this.$groups.each(function(i, group)
		{
			var $group = $(group);

			if ($group.find('li.listItem:visible').length == 0)
			{
				$group.hide();
			}
			else
			{
				$group.show();
			}
		});

		this.removeAjaxResults();
		this.showHideNoResults(visible ? false : true);
		
		$.each($('.listItem'), function() {
			if ($(this).css('display') == 'block') {
				$(this).parents('li').css('display', 'block');
			}
		});
	};
	
	$(document).ready(function() {
		$('form.nodesDisplayOrder ol.FilterList').nestedSortable({
			handle: '.sortable-handle',
			items: 'li',
			toleranceElement: '> div',
			forcePlaceholderSize: true,
			placeholder: 'listItem placeholder',
			update: function(event, ui) {
				$.each($(".listItem"), function() {
					$(this).removeClass("_depth0 _depth1 _depth2 _depth3 _depth4 _depth5 _depth6 _depth7 _depth8 _depth9");
					$(this).addClass("_depth" + $(this).parents(".listItem").size())
				});
			}
		});
		
		$("form.nodesDisplayOrder").submit(function(e) {
			$form = $(this);
			
			$.each($("form.nodesDisplayOrder .listItem"), function() {
				var nodeId = $(this).attr('id').substr(1);
				
				var parentId = 0;
				
				var parent = $(this).parents('li').first();
				if (parent.size()) {
					parentId = parent.attr('id').substr(1);
				}
				
				var displayOrder = $(this).index() + 1;
				
				$form.append($("<input>").attr("type", "hidden").attr("name", "nodes[" + nodeId + "][parent_node_id]").val(parentId));
				$form.append($("<input>").attr("type", "hidden").attr("name", "nodes[" + nodeId + "][display_order]").val(displayOrder));
			});
		});
	});
}
(jQuery, this, document);