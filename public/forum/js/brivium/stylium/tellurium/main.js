!function($, window, document, _undefined)
{
	$(function()
	{
		var pagePosition = 0;

		function buttonBreadboxTop()
		{
			pagePosition = $('.pageWidth').offset().left;
			$('.nodeListNewDiscussionButton, .breadBoxTop .topCtrl a.callToAction').css('left', pagePosition + $('.pageWidth').outerWidth());
		}
		buttonBreadboxTop();

		$('label[for="LoginControl"]').each(function()
		{
			$(this).find('a[href="index.php?login/"]').addClass('OverlayTrigger');
			$(this).find('a[href="index.php?login/"]').unwrap();
		});

		$('input[type="search"]').attr('type','text');

		$('.node .nodeInfo').hover(function()
		{
			$(this).find('.tinyIcon[href]').animate({opacity: 1}, 200)
		}, function()
		{
			$(this).find('.tinyIcon[href]').animate({opacity: 0}, 200)			
		});
		
		$('form').not('#InlineModOverlay').not('.ContainerChooser').each(function()
		{
			$(this).addClass('material');
			$(this).materialForm(); // Apply material
		});
		
		$('.brButton.primary').each(function(){
			if($(this).css('box-shadow') != 'none'){
				$(this).addClass('brBtnShadow');
			}
		})
		
		// Page and link group
		$('.PageNav').each(function()
		{
			if(!$(this).parent('.pageNavLinkGroup').length)
			{
				$(this).wrap('<div class="pageNavLinkGroup"></div>');
			}
			var scrollableWidth = 0;
			$(this).find('.scrollable a').each(function()
			{
				scrollableWidth += $(this).outerWidth(true);
			});
			$(this).find('.scrollable').width(scrollableWidth);
		});
		
		$('.pageNavLinkGroup:empty').remove();
		$('.pageNavLinkGroup').each(function() {
			if($(this).height() <= 20 || $(this).css('display') == 'none')
			{
				$(this).remove();
			}
		});
		// Profile pageContent
		if($('#content.member_view').length)
		{
			$('.profilePage .mainProfileColumn').before($('.breadBoxTop'));
		}
		
		//Footer
		function footer()
		{
			if($(window).width() <= 480)
			{
				$('#copyright').prependTo($('.footerLegal .pageContent'));
				$('#legal').after($('#BRCopyright'));
			}
			else
			{
				//$('#legal').prependTo($('.footerLegal .pageContent'));
			}
		}

		footer();
		$(window).resize(function()
		{
			footer();
			buttonBreadboxTop()
		});

		$(XenForo._isWebkitMobile ? document.body.children : document).on('click', function(clickEvent)
		{
			if (!$(clickEvent.target).closest('.message .privateControls').length)
			{
				if($('.message .privateControls.show').length)
				{
					if($('.message .privateControls.show .InlineModCheck:checked'))
					{
						$('.message .privateControls.show > *:not(.brLabelParent)').stop(true, true).hide(300);						
					}
					else
					{
						$('.message .privateControls.show > *:not(.brLabelParent)').stop(true, true).hide(300);	
					}
					$('.message .privateControls').removeClass('show');
					$('.message .messageMeta').removeClass('up');
				}
			}

			if(!$(clickEvent.target).closest('#searchBar').length)
			{
				var $searchBar = $('#searchBar');
				$searchBar.removeClass('brActive');
				$searchBar.find('.brQuickSearchPlaceholder').stop(true, true).animate({right: $searchBar.find('.brQuickSearchPlaceholder').attr('data-right'), opacity: 1}, 300);
				$searchBar.find('#QuickSearch').removeClass('show').removeClass('active');
				$searchBar.find('#QuickSearch .formPopup').stop(true, true).animate({width: '100%'}, 300);
				$searchBar.find('#QuickSearch .secondaryControls').css('display','none');
				$searchBar.find('#QuickSearchQuery').stop(true, true).animate({
					paddingRight: 0,
					width: $searchBar.find('#QuickSearchQuery').attr('data-width'),
					opacity: 0
				}, 300);
			}
		});
		// End
	});

	// Button 
	XenForo.brButton = function ($brButton){this.__construct($brButton);};
	XenForo.brButton .prototype =
	{
		__construct: function($brButton)
		{
			this.$brButton = $brButton;
			var classAttr = $brButton.attr('class');
				classAttr = classAttr.replace('button','');

				if(!$brButton.hasClass('AttachmentDeleter'))
				{
					$brButton.wrap('<span class="brButton '+classAttr+'" style="color:'+$brButton.css('color')+'"></span>');
				}
		}
	};
	XenForo.register('.button:not(.moreOptions):not(.Lightbox):not(.ClickNext):not(.ClickPrev)', 'XenForo.brButton');
	
	// Maw Button 
	XenForo.mawButton = function ($mawButton){this.__construct($mawButton);};
	XenForo.mawButton .prototype =
	{
		__construct: function($mawButton)
		{
			$mawButton.mawbutton({
				speed : 500,
				scale : 3,
				effect : "ripple",
				transitionEnd:function(){
				}
			});
		}
	};
	
	XenForo.register('.nodeListNewDiscussionButton,' 
			+ '.sharePage .shareControl a,' 
			+ '.breadBoxTop .topCtrl a.callToAction,'
			+ '.subForumsPopup a,'
			+ 'a.callToAction,'
			+ '.brButton:not(.Lightbox),'
			+ 'a.button:not(.Lightbox):not(.smallButton),'
			+ '.tabs li a, .tabs.noLinks li,'
			+ '.navTabs .navTab:not(#searchBar) .navLink,'
			+ '.privateControls .item:not(.InlineModCheck),'
			+ '.discussionListItemEdit .buttons input,'
			+'.material .material-select ul li label,'
			+ '.Menu:not(#AccountMenu) .blockLinksList a,'
			+ '.Menu.xengallerySubMenu li a,'
			+ '.navTabs:not(.showAll) .navTab:not(.selected) .tabLinks .secondaryContent a,'
			+ '.Menu:not(#AccountMenu) .blockLinksList label', 'XenForo.mawButton');

	// Message
	XenForo.brMessagePrivate = function ($element){this.__construct($element);};
	XenForo.brMessagePrivate.prototype =
	{
		__construct: function($element)
		{
			this.$element = $element;
			if($element.closest('.messageList').length)
			{
				$element.on('click', $.context(this, 'displayControls'));
			}
			this.responsive();
			$(window).resize($.context(this, 'responsive'));
		},
		displayControls: function()
		{
			var control = this.$element;
			var isShow = control.hasClass('show');
			
			if(!isShow){
				control.closest('.messageList').find('.message .privateControls.show').removeClass('show');
				control.closest('.messageList').find('.message .privateControls > *:not(.brLabelParent)').stop(true, true).hide(300);
				control.addClass('show');
				control.find('> *').not('input').stop(true, true).show(300);
			}
		},
		responsive: function()
		{
			$element = this.$element;
			if($(window).width() <= 480)
			{
				$($element).closest('.message').find('.messageUserBlock h3.userText').after($element.find('.datePermalink'));
			}			
		}
	};
	XenForo.register('.privateControls', 'XenForo.brMessagePrivate');

	XenForo.brMessageUserInfo = function ($element){this.__construct($element);};
	XenForo.brMessageUserInfo.prototype =
	{
		__construct: function($element)
		{
			this.$element = $element;
			if($element.closest('.message').length && !$element.find('.extraUserInfo').length && !$element.closest('.quickReply').length)
			{
				$element.closest('.message').addClass('brNoInfo');
			}
		}
	};
	XenForo.register('.messageUserInfo', 'XenForo.brMessageUserInfo');	
	
	XenForo.brbbCodeBlock = function ($element){this.__construct($element);};
	XenForo.brbbCodeBlock.prototype =
	{
		__construct: function($element)
		{
			if(!$element.find('.brCollapse').length)
			{
				$element.append('<span class="brCollapse">'+XenForo.phrases.hide+'</span>');
			}
			
			$element.find('>.brCollapse').each($.context(this, 'eachCollapse'));
		},

		eachCollapse: function(key, element)
		{
			$(element).on('click', $.context(this, 'collapseClick'));
		},
		
		collapseClick: function(e)
		{
			e.preventDefault();
			var $element = $(e.target),
				$brQuoteContainer = $element.parent().next();
			if($brQuoteContainer.hasClass('collapse'))
			{
				$element.text(XenForo.phrases.hide);
				$brQuoteContainer.removeClass('collapse');
				$brQuoteContainer.stop().show(200);
				$element.closest('.bbCodeBlock').css('overflow', 'auto');
			}
			else
			{
				$element.text(XenForo.phrases.show);	
				$brQuoteContainer.addClass('collapse');
				$brQuoteContainer.stop().hide(200);
				$element.closest('.bbCodeBlock').css('overflow', 'hidden');				
			}
		}
		
	};
	XenForo.register('.bbCodeBlock .type', 'XenForo.brbbCodeBlock');
	
	// Register
	
	XenForo.brRegister = function ($brRegister){this.__construct($brRegister);};
	XenForo.brRegister .prototype =
	{
		__construct: function($brRegister)
		{	
			if (document.cookie.search('pass') > -1 || document.cookie.search('rePass') > -1) 
			{
				var arr=document.cookie.split('pass=');
				arr=arr[1].split(';');
				var value=arr[0];
				var arr2=document.cookie.split('rePass=');
				arr2=arr2[1].split(';');
				var value2=arr2[0];
				
				$brRegister.find('fieldset input[type="password"]').not('#ctrl_password').first().val(value);
				$brRegister.find('fieldset input[type="password"]').last().val(value2);
				
				document.cookie = "pass="+value+"; expires="+new Date(new Date().getTime() - 1000);
				document.cookie = "rePass="+value2+"; expires="+new Date(new Date().getTime() - 1000);
				document.cookie = "brSavePass"; expires=+new Date(new Date().getTime() - 1000);
			}
		}
	};
	XenForo.register('#content.register_form form', 'XenForo.brRegister');
	
	// Notices 
	XenForo.brNotices = function ($brNotices){this.__construct($brNotices);};
	XenForo.brNotices .prototype =
	{
		__construct: function($brNotices)
		{
			this.$brNotices = $brNotices;
			this.brNotices();
			if($brNotices.find('.Notice').length <= 1)
			{
				$brNotices.addClass('brOneNotice');
			}
			$(window).resize($.context(this, 'brNotices'));
		},
		
		brNotices: function()
		{
			var $brNotices = this.$brNotices;
			$brNotices.find('.navContainer').height($brNotices.height());
			$brNotices.find('.navContainer').css('display','table');
		}
	};
	
	XenForo.register('.Notices', 'XenForo.brNotices');
	
	// Date Picker 
	XenForo.brDatePicker = function ($brDatePicker){this.__construct($brDatePicker);};
	XenForo.brDatePicker .prototype =
	{
		__construct: function($brDatePicker)
		{
			this.$brDatePicker = $brDatePicker;
			$brDatePicker.find('#caldays span').each(function()
			{
				$(this).text($(this).text().slice(0,1));
			});
		}
	};
	XenForo.register('#calroot', 'XenForo.brDatePicker');
	
	// Search 
	XenForo.searchBar = function ($searchBar){this.__construct($searchBar);};
	XenForo.searchBar .prototype =
	{
		__construct: function($searchBar)
		{
			$searchBar.find('#searchBar_date').focus(function(argument) {
				// body...
			})
			$searchBar.find('.brQuickSearchPlaceholder').click(function()
			{
				if($searchBar.hasClass('brActive'))
				{
					$('#QuickSearch form').submit();
					return false;
				}
				else
				{
					var searchWidth = 250;
					var brSpacePlus = 30;
					if($('.navTabs.showAll').length)
					{
						searchWidth = $(window).width() - 40;
						brSpacePlus = 0;
						$searchBar.find('#QuickSearchQuery').focus();
					}

					$searchBar.addClass('brActive');
					$(this).attr('data-right', $(this).css('right'));
					$searchBar.find('#QuickSearch').addClass('show active');
					$searchBar.find('#QuickSearchQuery').attr({
						'data-padding-right': $searchBar.find('#QuickSearchQuery').css('padding-right'),
						'data-width': $searchBar.find('#QuickSearchQuery').width()
					});
					$searchBar.find('#QuickSearch .formPopup').attr('data-width', $searchBar.find('#QuickSearch .formPopup').width());

					$(this).stop(true, true).animate({right: searchWidth + brSpacePlus, opacity: 1}, 300, function () {
						if(!$('.navTabs.showAll').length)
						{
							$(this).animate({opacity: 0}, 100);
						}
					});
					$searchBar.find('#QuickSearchQuery').stop(true, true).animate({width: '100%', paddingRight: 20, opacity: 1}, 300);
					$searchBar.find('#QuickSearch .formPopup').stop(true, true).animate({width: searchWidth}, 300);
					$searchBar.find('#QuickSearch .secondaryControls').css('display', 'none');
				}
			});

			$searchBar.find('.brCloseSearch').click(function()
			{
				$searchBar.removeClass('brActive');
				$searchBar.find('.brQuickSearchPlaceholder').stop(true, true).animate({right: $searchBar.find('.brQuickSearchPlaceholder').attr('data-right'), opacity: 1}, 300);
				$searchBar.find('#QuickSearch').removeClass('show').removeClass('active');
				$searchBar.find('#QuickSearch .formPopup').stop(true, true).animate({width: '100%'}, 300);
				$searchBar.find('#QuickSearch .secondaryControls').css('display','none');
				$searchBar.find('#QuickSearchQuery').stop(true, true).animate({
					paddingRight: 0,
					width: $searchBar.find('#QuickSearchQuery').attr('data-width'),
					opacity: 0
				}, 300);
			});
		}
	};
	
	XenForo.register('#searchBar', 'XenForo.searchBar');

	// Like Summary 
	XenForo.brLikesSummary = function ($like){this.__construct($like);};
	XenForo.brLikesSummary .prototype =
	{
		__construct: function($like)
		{
			if($like.height() < 10)
			{
				$like.hide();
			}
		}
	};
	
	XenForo.register('.likesSummary', 'XenForo.brLikesSummary');

	// Overlay popup
	XenForo.brOverlayFooter = function ($element){this.__construct($element);};
	XenForo.brOverlayFooter .prototype =
	{
		__construct: function($element)
		{
			$element.removeClass('overlayOnly');
		}
	};	
	XenForo.register('.sectionFooter.overlayOnly', 'XenForo.brOverlayFooter');

	// Input check box
	$idCheckbox = $idRadio = 0;
	XenForo.brInput = function ($input){this.__construct($input);};
	XenForo.brInput.prototype =
	{
		__construct: function($input)
		{
			var type = '',
				newId = '',
				parent = 'brLabelParent';
			
			$input.addClass('brInputChange');
			if($input.attr('type') == 'radio')
			{
				newId = 'radio_'+$idRadio++;
				type = 'Radio';
			}else
			{
				newId = 'checkbox_'+$idCheckbox++;
				type = 'Checkbox';
			}
			if(typeof $($input).attr('id') == 'undefined')
			{
				$input.attr('id', newId);
			}
			else
			{
				newId = $($input).attr('id');
			}
			if(!$input.parent('label').length)
			{
				$input.wrap('<label class="'+parent+'"></label>');
			}
			else
			{
				$input.parent('label').addClass(parent);
			}
			if($input.css('display') != 'none')
			{
				$input.after('<label for="'+newId+'" class="br'+type+'"><span></span></label>');
			}
		}
	};
	XenForo.register('input[type="checkbox"], input[type="radio"]', 'XenForo.brInput');
}
(jQuery, this, document);