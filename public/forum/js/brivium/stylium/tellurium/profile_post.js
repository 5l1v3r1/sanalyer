!function($, window, document, _undefined)
{
	var brCount = 0,
		brCount2 = 0,
		brCount3 = 0;

	$(window).resize(function()
	{
		brCount = 0;
		brCount3 = 0;
	});

	$(document).ready(function () {		
		$(XenForo._isWebkitMobile ? document.body.children : document).on('click', function(clickEvent)
		{
			if(!$(clickEvent.target).closest('.commentControls').length && $('.comment .commentControls.show').length)
			{
				$('.comment .commentControls').removeClass('show');
			}
		});
	});

	//Message Simple
	XenForo.brMessageInfo = function ($brMessageInfo){this.__construct($brMessageInfo);};
	XenForo.brMessageInfo .prototype =
	{
		__construct: function($brMessageInfo)
		{
			this.$brMessageInfo = $brMessageInfo;
			simpleInfo = $brMessageInfo;
			simple = simpleInfo.closest('li.messageSimple');
			simpleList = simple.closest('.messageSimpleList');

			var $this = this;

			if(simple.length)
			{
				if(!simple.find('.privateControls').length)
				{
					if(simple.closest('.xenOverlay').length)
					{
						simple.closest('.xenOverlay').addClass('brProfilePostList');
						setTimeout(function()
						{
							$this.noLogin();
						}, 1);
					}
					else
					{
						$this.noLogin();
					}
					$(window).resize($.context(this, 'noLogin'));
				}
				else
				{
					// Time line
					simple.find('.privateControls .item').removeClass('mawbutton');
					var brString = simple.find('.brTimeLine .DateTime').text();
					simple.find('.brTimeLine .DateTime').text(brString.replace(',', ''));
					
					// Other customs
					if(simple.closest('.xenOverlay').length)
					{
						simple.closest('.xenOverlay').addClass('brProfilePostList');
						setTimeout(function()
						{
							simple.find('.privateControls > *').each(function()
							{
								$(this).attr('data-width', $(this).outerWidth(true));
							});
							$this.check();
						}, 1);
					}
					else
					{
						simple.find('.privateControls > *').each(function()
						{
							$(this).attr('data-width', $(this).outerWidth(true));
						});
						this.loadPage();
					}
					$(window).resize($.context(this, 'resize'));

					simple.find('.privateControls').click($.context(this, 'brPrivateMobile'));
					$(document).click(function(e)
					{
						if(!$(e.target).closest('.privateControls').length && $('.messageSimple .privateControls.show').length)
						{
							$('.messageSimple .privateControls').removeClass('show');
						}
					});
				}
			}
		},

		noLogin: function()
		{
			if(simpleList.width() <= 480)
			{
				this.mobile();
			}
			else
			{
				this.desktop();
			}
		},

		loadPage: function()
		{
			if(!simpleList.hasClass('brMobile'))
			{
				this.check();
			}
			else
			{
				this.mobile();
				$(document).click(function(e)
				{
					if(!$(e.target).closest('.privateControls').length && simple.find('.privateControls.show').length && simpleList.hasClass('brMobile'))
					{
						simple.find('.privateControls > *').stop(false, false).hide(300);
						simple.find('.privateControls').removeClass('show');
					}
				});
			}
		},

		check: function()
		{
			if(simpleList.width() <= 440)
			{
				this.mobile();
			}
			else
			{
				var maxWidth = 0;
				if(simple.css('display') != 'none')
				{
					simple.find('.privateControls > *').each(function()
					{
						maxWidth += parseInt($(this).attr('data-width'));
					});

					maxWidth = maxWidth + simple.find('.publicControls').outerWidth(true);

					//console.log(maxWidth, messageInfo.find('.messageMeta').width());
					if(maxWidth >= simple.find('.messageMeta').width())
					{
						simple.addClass('brMaxWidth');
						this.mobile();
					}
					else
					{
						simple.removeClass('brMaxWidth');
					}
					
					brCount3++;
					var brCount4 = simpleList.find('.messageSimple').length;
					if(brCount3 == brCount4)
					{
						if(!$('.messageSimple.brMaxWidth').length)
						{
							this.desktop();
						}
					}
				}
			}
		},

		resize: function()
		{
			this.desktop();
			this.check();
		},

		mobile: function()
		{
			simpleList.addClass('brMobile');

			simpleList.find('.messageSimple').each(function()
			{
				if(!$(this).find('.messageInfo > .brTimeLine').length)
				{
					$(this).find('.messageInfo > .poster').after($(this).find('.brTimeLine'));
				}
				if($(this).find('.publicControls').length)
				{
					$(this).find('.privateControls').css('right', $(this).find('.publicControls').outerWidth());
				}
				else
				{
					$(this).find('.privateControls').css('right', 0);
				}
				$(this).find('.messageMeta').css('margin-left', $(this).find('.brTimeLine').outerWidth(true)+20);
			});
		},

		desktop: function()
		{
			if(simpleList.hasClass('brMobile'))
			{
				simpleList.removeClass('brMobile');
			}

			simpleList.find('.messageSimple').each(function()
			{
				if($(this).find('.messageInfo > .brTimeLine').length)
				{
					$(this).find('.messageInfo > .poster').after($(this).find('.brTimeLine'));
					$(this).find('> .avatar, > .placeholderContent > .avatar').before($(this).find('.messageInfo > .brTimeLine'));
				}
				$(this).find('.privateControls, .messageMeta').removeAttr('style');
			});
		},

		brPrivateMobile: function(e)
		{
			if(simpleList.hasClass('brMobile'))
			{
				if(!$(e.target).hasClass('InlineModCheck') && !$(e.target).hasClass('brCheckbox'))
				{
					var $this = $(e.target).closest('.privateControls');
					if(!$this.hasClass('show'))
					{
						$('.messageSimple .privateControls').removeClass('show');
						$('.messageSimple .commentControls').removeClass('show');
					}

					$this.addClass('show');
					return false;
				}
			}
		}
	};
	XenForo.register('.messageInfo', 'XenForo.brMessageInfo');

	// Message Simple Comment
	XenForo.brComment = function ($cmtInfo){this.__construct($cmtInfo);};
	XenForo.brComment .prototype =
	{
		__construct: function($cmtInfo)
		{
			this.$cmtInfo = $cmtInfo;
			cmtInfo = $cmtInfo;
			var comment = $cmtInfo.closest('.comment');
			this.comment =  comment;
			simple = comment.closest('.messageSimple');
			simpleList = comment.closest('.messageSimpleList');
			var self = this;
			
			if(simpleList.closest('.xenOverlay').length)
			{
				simpleList.click($.context(this, 'parentClick'));
			}
		
			if(!comment.hasClass('brCmtSubmit') && !comment.hasClass('ignored') && comment.find('.commentControls .brInner').length)
			{
				setTimeout(function()
				{
					comment.find('.commentControls .brInner > *').each(function()
					{
						$(this).attr('data-width', $(this).outerWidth(true));
					});
				}, 1);

				brCount2 = 0;
				simpleList.find('.comment:not(.brCmtSubmit):not(.ignored)').each(function()
				{
					brCount2++;
				});

				setTimeout(function()
				{
					self.check();
				}, 2);
				$(window).resize($.context(this, 'check'));
			}

			if(!comment.find('.commentControls').length)
			{
				if($(window).width() <= 480)
				{
					simpleList.addClass('brCmtRes');
				} 
				else
				{
					simpleList.removeClass('brCmtRes');
				}
			}

			if(simpleList.hasClass('brCmtRes'))
			{
				comment.find('.commentControls').click($.context(this, 'brCommentControl'));
			}
		},

		check: function()
		{
			var comment = this.comment;
			if($(window).width() <= 480)
			{
				simpleList.addClass('brCmtRes');
			}
			else
			{
				var maxComment = 0;
				brCount++;
				comment.find('.commentControls .brInner > *').each(function()
				{
					maxComment += parseInt($(this).attr('data-width'));
				});
				maxComment += comment.find('.publicControls').width();

				if(maxComment >= comment.find('.commentControls').width())
				{
					comment.addClass('brTemp');
				}
				else
				{
					comment.removeClass('brTemp');
				}

				if(brCount == brCount2)
				{
					if($('.comment.brTemp').length)
					{
						simpleList.addClass('brCmtRes');
					}
					else
					{
						simpleList.removeClass('brCmtRes');
					}
				}
			}
			comment.find('.commentControls').click($.context(this, 'brCommentControl'));
		}, 

		brCommentControl: function(e)
		{
			if(simpleList.hasClass('brCmtRes'))
			{
				var $this = $(e.currentTarget);
				if(!$this.hasClass('show'))
				{
					$('.comment .commentControls').removeClass('show');
					$('.messageSimple .privateControls').removeClass('show');
				}

				$this.addClass('show');
				$this.find('.brInner > *').stop(false, false).show(300);
			}				
		},
		
		parentClick: function(e)
		{
			if(simpleList.hasClass('brCmtRes'))
			{
				var $element = $(e.target);
				if(!$element.hasClass('brInner') && !$element.hasClass('commentControls'))
				{
					$('.comment .commentControls').removeClass('show');
					$('.messageSimple .privateControls').removeClass('show');
				}
			}
		}
	};
	XenForo.register('.commentInfo', 'XenForo.brComment');
	
	//Profile post list
	XenForo.brProfilePost = function ($brProFile){this.__construct($brProFile);};
	XenForo.brProfilePost .prototype =
	{
		__construct: function($brProFile)
		{
			this.$brProFile = $brProFile;
			this.brProFile();
		},
		
		brProFile: function()
		{
			var $brProFile = this.$brProFile;
				blockquote = $brProFile.find('blockquote').text();
				if(blockquote.length > 76)
				{
					contentText = blockquote.slice(0, 76);
					$brProFile.find('blockquote').text(contentText+'...');
				}

			$brProFile.find('.poster').before($brProFile.find('.publicControls .item'));
		}
	};
	XenForo.register('.profilePostListItem', 'XenForo.brProfilePost');
}
(jQuery, this, document);