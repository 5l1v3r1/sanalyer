/* Darkimmortal's TaigaChat */

var taigachat_decay = 1.25;
var taigachat_refreshtime = 5;

var taigachat_initialFired = false;
var taigachat_reverse = false;
var taigachat_initialTime = 0;
var taigachat_lastRefresh = 0;
var taigachat_lastRefreshServer = 0;
var taigachat_lastMessage = 0;
//var taigachat_refreshTimer = false;
var taigachat_nextRefresh = 0;
var taigachat_isRefreshing = false;

var taigachat_lastPostTime = 0;
var taigachat_lastPostMessage = "";
var taigachat_scrolled = false;

var taigachat_PopupMenu = XenForo.PopupMenu;
taigachat_PopupMenu.setMenuPosition = function(caller){
	//console.info('setMenuPosition(%s)', caller);

	var controlLayout, // control coordinates
		menuLayout, // menu coordinates
		contentLayout, // #content coordinates
		$content,
		$window,
		proposedLeft,
		proposedTop;

	controlLayout = this.$control.coords('outer');

	this.$control.removeClass('BottomControl');

	// set the menu to sit flush with the left of the control, immediately below it
	this.$menu.removeClass('BottomControl').css(
	{
		left: controlLayout.left,
		top: controlLayout.top + controlLayout.height
	});

	menuLayout = this.$menu.coords('outer');

	$content = $('#content .pageContent');
	if ($content.length)
	{
		contentLayout = $content.coords('outer');
	}
	else
	{
		contentLayout = $('body').coords('outer');
	}

	$window = $(window);
	$window.sT = $window.scrollTop();
	$window.sL = $window.scrollLeft();

	/*
	 * if the menu's right edge is off the screen, check to see if
	 * it would be better to position it flush with the right edge of the control
	 */
	if (menuLayout.left + menuLayout.width > contentLayout.left + contentLayout.width)
	{
		proposedLeft = controlLayout.left + controlLayout.width - menuLayout.width;
		// must always position to left with mobile webkit as the menu seems to close if it goes off the screen
		if (proposedLeft > $window.sL || XenForo._isWebkitMobile)
		{
			this.$menu.css('left', proposedLeft);
		}
	}

	/*
	 * if the menu's bottom edge is off the screen, check to see if
	 * it would be better to position it above the control
	 */
	//if (menuLayout.top + menuLayout.height > $window.height() + $window.sT)
	{
		proposedTop = controlLayout.top - menuLayout.height-500;
		//if (proposedTop > $window.sT)
		{
			this.$control.addClass('BottomControl');
			this.$menu.addClass('BottomControl');
			this.$menu.css('top', proposedTop);
		}
	}
};


$(document).ready(function(){
	
	$(window).focus(taigachat_focus);
	
	$("#taigachat_message").keypress(function (e) {
		if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
			sendShout();
			return false;
		}
		return true;
	});
	
	$("#taigachat_smilies").hover(function(){
		if($(".taigachat_smilie > img").length > 0)
			return;
			
		$(".taigachat_smilie").each(function(){
			if($(this).hasClass('mceSmilieSprite')){
				var img = $("<img />");
				img.attr("src", "styles/default/xenforo/clear.png");
				img.attr("alt", $(this).attr("data-alt"));
				img.attr("title", $(this).attr("data-title"));
				img.attr("class", $(this).attr("class").replace("taigachat_smilie", ""));
				$(this).append(img);
			} else {
				var img = $("<img />");
				img.attr("src", $(this).attr("data-src"));
				img.attr("alt", $(this).attr("data-alt"));
				img.attr("title", $(this).attr("data-title"));
				$(this).append(img);
			}
		});     
		$(this).unbind('hover');
	});
	
	$("#taigachat_send").click(sendShout);
	
	refreshShoutbox(true, true, false);
	
	$("#taigachat_send, #taigachat_message").removeAttr('disabled').removeClass('disabled');
	//$("#taigachat_message").val("");
	
	$("#taigachat_message").focus(function(e){
		if($("#taigachat_toolbar:visible").length == 0){
			$("#taigachat_toolbar").slideDown(500);
		}
	});
		
	$(".taigachat_smilie").live('click', function(e){
		e.stopPropagation();
		if($("#taigachat_message").val() == $("#taigachat_message").attr("placeholder")){
			$("#taigachat_message").removeClass("prompt").val("");
		}
		$("#taigachat_message").insertAroundCaret(" " + $(this).children("img").attr("alt") + " ", "");
		return true;
	});
	
	$(".taigachat_bbcode").live('click', function(e){
		e.stopPropagation();
		if($("#taigachat_message").val() == $("#taigachat_message").attr("placeholder")){
			$("#taigachat_message").removeClass("prompt").val("");
		}
		var bbcode = $(this).attr("data-code");
		var position = bbcode.length;
		var ins = getCaretLength($("#taigachat_message").get(0)) > 0;
		$("#taigachat_message").insertAroundCaret(bbcode.substring(0, bbcode.indexOf('][')+1), bbcode.substring(bbcode.indexOf('][')+1, bbcode.length));
		if(bbcode.indexOf('=][') != -1){
			position = bbcode.indexOf('=][')+1;
		} else {			
			position = bbcode.indexOf('][')+1;
		}
		
		if(!ins)
			setCaretPosition($("#taigachat_message").get(0), getCaretPosition($("#taigachat_message").get(0)) - (bbcode.length - position));
		else		
			setCaretPosition($("#taigachat_message").get(0), getCaretPosition($("#taigachat_message").get(0)) + bbcode.length - position);
		return true;		
	});
	
	XenForo.register('.taigachat_Popup', 'taigachat_PopupMenu');
	setTimeout(function(){
		// Add the icon/styling without XenForo registering its own events etc.
		$(".taigachat_Popup").addClass("Popup");
	}, 50);
});


function sendShout(){
	
	// silently prevent same message within 5 seconds
	if(taigachat_lastPostTime + 5000 > new Date().getTime() && taigachat_lastPostMessage == $("#taigachat_message").val())
		 return;    
		 
	if($("#taigachat_message").val().length == 0 || $("#taigachat_message").val() == $("#taigachat_message").attr("placeholder")) return;
	$("#taigachat_send, #taigachat_message").attr('disabled', true).addClass('disabled');

		 
		 
	taigachat_lastPostMessage = $("#taigachat_message").val();
	taigachat_lastPostTime = new Date().getTime();
	
	XenForo.ajax(
		"index.php?taigachat/post.json", 
		{
			message: $("#taigachat_message").val(),
			sidebar: taigachat_sidebar ? "1" : "0",
			lastrefresh: taigachat_lastRefreshServer
		}, 
		function(json){
			if(XenForo.hasResponseError(json) !== false){				
				return true;
			}
			
			$("#taigachat_message").val("");	
			
			handleListResponse(json, false, true);
			
			$("#taigachat_send, #taigachat_message").removeAttr('disabled').removeClass('disabled');
			$("#taigachat_message").blur();
			$("#taigachat_message").focus();
			
			//taigachat_nextRefresh = 0;
			//if(taigachat_refreshTimer)
			//	clearTimeout(taigachat_refreshTimer);		
			//refreshShoutbox(false, true, false);
		},
		{cache: false}
	);
}

function taigachat_focus(e){	
	// workaround the .blur/.focus on #taigachat_message being passed down
	//if(typeof e.target == "undefined" || e.target == window)
		//refreshShoutbox(false, true, true);
		taigachat_nextRefresh = 0;
}

// force = ignore focus event delay and ignore document focus
// unsync = out-of-sync request, do not restart timer
function refreshShoutbox(initial, force, unsync){
	
	// Assert initial refresh will only happen once
	if(initial){
		if(taigachat_initialFired) 
			return;
		taigachat_initialFired = true;
		taigachat_initialTime = new Date().getTime();
	} else {
		// Assert we aren't refreshing within 4 seconds of the first refresh - i.e. document focus event
		if(taigachat_initialTime + 4000 > new Date().getTime() && !force)
			return;
	}
	// Stop refresh spam
	if(taigachat_lastRefresh + 2000 > new Date().getTime())
		 return;	
		 
	// Stop focus refresh spam
	if(force && unsync && taigachat_lastRefresh + 6000 > new Date().getTime())
		return;
	
	if(taigachat_initialTime + 50 * 60 * 1000 < new Date().getTime() && !initial){
		// time for a CSRF token refresh...
		XenForo._CsrfRefresh.refresh();
		taigachat_refreshtime = 10;    
		restartTimer();
		taigachat_initialTime = new Date().getTime();
		return;
	}
	
	taigachat_lastRefresh = new Date().getTime();    	
		
	if((XenForo._hasFocus && taigachat_autorefresh) || force){
		
		taigachat_isRefreshing = true;
		
		XenForo.ajax(
			"index.php?taigachat/list.json", 
			{
				sidebar: taigachat_sidebar ? "1" : "0",
				lastrefresh: taigachat_lastRefreshServer
			}, 
			function(json, textStatus){				
				
				taigachat_isRefreshing = false;
				
				if (XenForo.hasResponseError(json))
				{
					return false;
				}

				handleListResponse(json, initial, unsync);
							
				if(initial){        
					//taigachat_refreshTime = 5;
					//restartTimer();
					setInterval(checkRefresh, 250);
				}
				
				
			},  
			{
				global: false, 
				cache: false, 
				error: function(xhr, textStatus, errorThrown){
					try
					{
						success.call(null, $.parseJSON(xhr.responseText), textStatus);
					}
					catch (e)
					{
						// workarounds :3
						if(xhr.responseText.substr(0, 1) == '{')
							XenForo.handleServerError(xhr, textStatus, errorThrown);
					}
				}
			}
		); // ajax
	} // if focused etc
	else {
		if(!unsync){
			decayRefreshTime();
			restartTimer();
		}
	}   
	
	
}


function handleListResponse(json, initial, unsync){
	
	taigachat_lastRefreshServer = parseInt(json.lastrefresh, 10) || 0;
	
	// error'd
	if(!XenForo.hasTemplateHtml(json) && taigachat_lastRefreshServer == 0){
		XenForo.hasResponseError(json);
		//taigachat_autorefresh = false;
		return false;
	}	
				
	var gotNew = 0;
	var reverse = parseInt(json.reverse, 10) == 1 ? true : false;
	taigachat_reverse = reverse;
	
	
	// Grab the chat elements, reverse if not in top to bottom order
	var lis = $(json.templateHtml).filter("li").get();
	if(!reverse)
		lis = lis.reverse();
		
		
	$(lis).each(function(){
		if($("#"+$(this).attr("id")).length == 0){
			gotNew++;
			if(!reverse)
				$(this).attr("style", "visibility:hidden").addClass("taigachat_new").prependTo("#taigachat_box > ol");
			else
				$(this).attr("style", "visibility:hidden").addClass("taigachat_new").appendTo("#taigachat_box > ol");
				
		}
	});
	
	if(initial || gotNew > 2 || taigachat_lastMessage + 15000 > new Date().getTime()){
		$("#taigachat_box > ol > li.taigachat_new").removeClass("taigachat_new").css({visibility:"visible"}).show();
		
		// wee bit of a workaround here
		setTimeout(function(){
			if(taigachat_reverse)
				scrollChatBottom();
		}, 200);
		
	} else {                
		$("#taigachat_box > ol > li.taigachat_new").removeClass("taigachat_new").css({visibility:"visible",display:"none"}).fadeIn(600);                
	}

	if(taigachat_reverse){         
		var total = $("#taigachat_box > ol > li").length;
		total -= taigachat_limit;
		if(total > 0)
			$("#taigachat_box > ol > li").slice(0, total).remove();       
	} else {
		$("#taigachat_box > ol > li").slice(taigachat_limit).remove();    
	}
	
	if(initial || gotNew>0){
		XenForo.register('.Popup', 'XenForo.PopupMenu', 'XenForoActivatePopups');
		XenForo.register(
			'a.OverlayTrigger, input.OverlayTrigger, button.OverlayTrigger, label.OverlayTrigger, a.username, a.avatar',
			'XenForo.OverlayTrigger'
		);
		XenForo.activate(document);
		

		//XenForo._TimestampRefresh.refresh();
		if(reverse)
			scrollChatBottom();
		
		taigachat_refreshtime = 5;    
		restartTimer();
		
	} else {    
		if(!unsync){   
			decayRefreshTime();
			restartTimer();                        
		}
	}
	
	
		
	// don't count initial load against anti fade
	if(gotNew > 0 && !initial){                
		taigachat_lastMessage = new Date().getTime();
	}
				
}

function scrollChatBottom(){	
	//if($("#taigachat_box").get(0).scrollTop >= $("#taigachat_box").scrollHeight - $("#taigachat_box").height() - 10 || !taigachat_scrolled)
		$("#taigachat_box").get(0).scrollTop = 99999;
	taigachat_scrolled = true;
}

function restartTimer(){
	/*if(taigachat_refreshTimer)
		clearTimeout(taigachat_refreshTimer);
	taigachat_refreshTimer = setTimeout(function(){ refreshShoutbox(false, false, false); }, taigachat_refreshtime*1000);        */
	taigachat_nextRefresh = new Date().getTime() + taigachat_refreshtime * 1000;
}

function checkRefresh(){		
	
	if(taigachat_nextRefresh < new Date().getTime()){
		
		if(taigachat_isRefreshing){
			taigachat_nextRefresh = new Date().getTime();
			return;
		}
		
		refreshShoutbox(false, false, false);
		
		if(taigachat_nextRefresh < new Date().getTime())
			taigachat_nextRefresh = new Date().getTime() + 5000;
	}
}

function decayRefreshTime(){
	taigachat_refreshtime = taigachat_refreshtime * taigachat_decay;
	if(taigachat_refreshtime > taigachat_maxrefreshtime)
		taigachat_refreshtime = taigachat_maxrefreshtime;
}

// http://stackoverflow.com/questions/946534/insert-text-into-textarea-with-jquery, modified slightly
jQuery.fn.extend({
	insertAroundCaret: function(myValue, myValue2){
		return this.each(function(i) {
			if(document.selection) {
				this.focus();
				sel = document.selection.createRange();
				sel.text = myValue + sel.text + myValue2;
				this.focus();
			} else if(this.selectionStart || this.selectionStart == '0') {
				var startPos = this.selectionStart;
				var endPos = this.selectionEnd;
				var scrollTop = this.scrollTop;
				this.value = this.value.substring(0, startPos)+myValue+this.value.substring(startPos, endPos)+myValue2+this.value.substring(endPos,this.value.length);
				this.focus();
				this.selectionStart = startPos + myValue.length + myValue2.length + (endPos-startPos);
				this.selectionEnd = startPos + myValue.length + myValue2.length + (endPos-startPos);
				this.scrollTop = scrollTop;
			} else {
				this.value += myValue + myValue2;
				this.focus();
			}
		})
	}
});

// http://blog.vishalon.net/index.php/javascript-getting-and-setting-caret-position-in-textarea/
function getCaretPosition (ctrl) {
	var CaretPos = 0;    // IE Support
	if(document.selection){
		ctrl.focus ();
		var Sel = document.selection.createRange ();
		Sel.moveStart ('character', -ctrl.value.length);
		CaretPos = Sel.text.length;
	}
	// Firefox support
	else if(ctrl.selectionStart || ctrl.selectionStart == '0')
		CaretPos = ctrl.selectionStart;
	return (CaretPos);
}
function getCaretLength (ctrl) {
	var CaretPos = 0;
	if(document.selection){
		ctrl.focus ();
		var Sel = document.selection.createRange ();
		//Sel.moveStart ('character', -ctrl.value.length);
		CaretPos = Sel.text.length;
	}
	else if(ctrl.selectionEnd || ctrl.selectionEnd == '0')
		CaretPos = ctrl.selectionEnd-ctrl.selectionStart;
	return (CaretPos);
}
function setCaretPosition(ctrl, pos){
	if(ctrl.setSelectionRange){
		ctrl.focus();
		ctrl.setSelectionRange(pos,pos);
	}
	else if(ctrl.createTextRange){
		var range = ctrl.createTextRange();
		range.collapse(true);
		range.moveEnd('character', pos);
		range.moveStart('character', pos);
		range.select();
	}
}


