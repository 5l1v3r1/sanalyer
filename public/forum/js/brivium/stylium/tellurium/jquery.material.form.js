$(function(){
	$.fn.hasAttr = function(attribute){
		var attr = this.attr(attribute);
		if (typeof attr !== typeof undefined && attr !== false) 
		   return true;
		return false;
	}

	$.fn.materialForm = function() {
	    // Selects
	    this.find('select:not(.chosen-container):not(.TitlePrefix)').each(function(i){
			var selectWidth = $(this).width();
	    	var type = ($(this).attr('multiple') ? 'checkbox' : 'radio');
	    	var name = id = $(this).attr('name');
	    	var $wrap = $(this).wrap("<div class='material-select'></div>").parent();
	    	if(type == 'checkbox'){
	    		name += '[]';
	    		var $bar = $('<span class="material-bar"></span>');
	    		$wrap.append($bar).addClass('checkbox');
	    	}
	    	/*else{
	    		var $title = $('<span class="material-title">'+placeholder+'</span>');
	    		$wrap.prepend($title);
	    	}*/

	    	var $label = $('<label for="select-'+i+'"><span>'+XenForo.phrases.select_menu+'</span><strong></strong></label>');
	    	var $checkbox = $('<input type="checkbox" id="select-'+i+'">');

	    	$wrap.prepend($checkbox);
	    	$wrap.prepend($label);
	    	
	    	var $list = $('<ul class="'+type+'"></ul>');
	    	$wrap.append($list);

	    	var selected_length = 0;
	    	var length = $(this).children('option').length;
	    	var $selected;
	    	$(this).children('option').each(function(j){
	    		var title = $(this).text();
	    		var value = $(this).val();
	    		
	    		var selected = $(this).hasAttr('selected');

	    		var $list_item = $('<li></li>');
	    		$list.append($list_item);

	    		var $label = $('<label for="'+id+'-'+j+'">'+title+'</label>');
	    		var $input = $('<input type="'+type+'" value="'+value+'" name="'+name+'" id="'+id+'-'+j+'">');
	    		if(selected){
	    			$selected = $input.prop('checked', true);
	    			selected_length++;
	    		}
	    		
	    		$list_item.append($input);
	    		$list_item.append($label);
	    	});
	    	if($bar){
	    		var percentage = selected_length / length * 100;
	    		$bar.width(percentage + '%');
	    	}
	    	else{
	    		if(selected_length){
	    			$label.children('span').text($selected.next('label').text())
	    			$wrap.addClass('filled');
	    		}
	    	}
	    	$(this).remove();
	    });

		$(document).on('click', function(e) {
		    if ( $(e.target).closest('.material-select').length === 0 ) {
		        // cancel highlighting 
		        $('.material-select > input').prop('checked', false);
		    }
		});

		$('.material-select > input').on('change', function(){
			var changed_id = $(this).attr('id');
			$('.material-select > input').each(function(){
				var this_id = $(this).attr('id');
				if(changed_id != this_id)
					$(this).prop('checked', false);
			});
		});

		$('.material-select ul input').on('change', function(){
			if($(this).closest('.material-select.checkbox').length){
				var $ul = $(this).closest('ul');
				var length = $ul.find('input').length;
				var checked_length = $ul.find('input:checked').length;
				var percentage = checked_length / length * 100;
				$(this).closest('.material-select').find('.material-bar').width(percentage + '%');
			}
			else{
				var $material_select = $(this).closest('.material-select')
				var $label = $material_select.children('label').children('span');
				var $next = $(this).next('label');
				$label.text($next.text());
				$material_select.children('input').prop('checked', false);
				$material_select.addClass('filled');
			}
		});
	};
});