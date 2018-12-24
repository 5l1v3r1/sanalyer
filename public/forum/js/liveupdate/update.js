
var LiveUpdate = {};

/*!
 Tinycon - A small library for manipulating the Favicon
 Tom Moor, http://tommoor.com
 Copyright (c) 2012 Tom Moor
 MIT Licensed
 @version 0.6.1
 */
!function(){var a={},b=null,c=null,d=document.title,e=null,f=null,g={},h=window.devicePixelRatio||1,i=16*h,j={width:7,height:9,font:10*h+"px arial",colour:"#ffffff",background:"#F03D25",fallback:!0,crossOrigin:!0,abbreviate:!0},k=function(){var a=navigator.userAgent.toLowerCase();return function(b){return-1!==a.indexOf(b)}}(),l={ie:k("msie"),chrome:k("chrome"),webkit:k("chrome")||k("safari"),safari:k("safari")&&!k("chrome"),mozilla:k("mozilla")&&!k("chrome")&&!k("safari")},m=function(){for(var a=document.getElementsByTagName("link"),b=0,c=a.length;c>b;b++)if((a[b].getAttribute("rel")||"").match(/\bicon\b/))return a[b];return!1},n=function(){for(var a=document.getElementsByTagName("link"),b=document.getElementsByTagName("head")[0],c=0,d=a.length;d>c;c++){var e="undefined"!=typeof a[c];e&&(a[c].getAttribute("rel")||"").match(/\bicon\b/)&&b.removeChild(a[c])}},o=function(){if(!c||!b){var a=m();c=b=a?a.getAttribute("href"):"/favicon.ico"}return b},p=function(){return f||(f=document.createElement("canvas"),f.width=i,f.height=i),f},q=function(a){n();var b=document.createElement("link");b.type="image/x-icon",b.rel="icon",b.href=a,document.getElementsByTagName("head")[0].appendChild(b)},s=function(a,b){if(!p().getContext||l.ie||l.safari||"force"===g.fallback)return t(a);var c=p().getContext("2d"),b=b||"#000000",d=o();e=document.createElement("img"),e.onload=function(){c.clearRect(0,0,i,i),c.drawImage(e,0,0,e.width,e.height,0,0,i,i),(a+"").length>0&&u(c,a,b),v()},!d.match(/^data/)&&g.crossOrigin&&(e.crossOrigin="anonymous"),e.src=d},t=function(a){g.fallback&&(document.title=(a+"").length>0?"("+a+") "+d:d)},u=function(a,b){"number"==typeof b&&b>99&&g.abbreviate&&(b=w(b));var d=(b+"").length-1,e=g.width*h+6*h*d,f=g.height*h,j=i-f,k=i-e-h,m=16*h,n=16*h,o=2*h;a.font=(l.webkit?"bold ":"")+g.font,a.fillStyle=g.background,a.strokeStyle=g.background,a.lineWidth=h,a.beginPath(),a.moveTo(k+o,j),a.quadraticCurveTo(k,j,k,j+o),a.lineTo(k,m-o),a.quadraticCurveTo(k,m,k+o,m),a.lineTo(n-o,m),a.quadraticCurveTo(n,m,n,m-o),a.lineTo(n,j+o),a.quadraticCurveTo(n,j,n-o,j),a.closePath(),a.fill(),a.beginPath(),a.strokeStyle="rgba(0,0,0,0.3)",a.moveTo(k+o/2,m),a.lineTo(n-o/2,m),a.stroke(),a.fillStyle=g.colour,a.textAlign="right",a.textBaseline="top",a.fillText(b,2===h?29:15,l.mozilla?7*h:6*h)},v=function(){p().getContext&&q(p().toDataURL())},w=function(a){for(var b=[["G",1e9],["M",1e6],["k",1e3]],c=0;c<b.length;++c)if(a>=b[c][1]){a=x(a/b[c][1])+b[c][0];break}return a},x=function(a,b){var c=new Number(a);return c.toFixed(b)};a.setOptions=function(a){g={};for(var b in j)g[b]=a.hasOwnProperty(b)?a[b]:j[b];return this},a.setImage=function(a){return b=a,v(),this},a.setBubble=function(a,b){return a=a||"",s(a,b),this},a.reset=function(){q(c)},a.setOptions(j),window.Tinycon=a}();

!function($, window, document, _undefined)
{
	LiveUpdate.SetupAutoPolling = function()
	{
		if (!$('html').hasClass('LoggedIn'))
			return;

		LiveUpdate.pollInterval = $('html').data('pollinterval') * 1000;
		if (!LiveUpdate.pollInterval)
		{
			LiveUpdate.pollInterval = 10000;
			$('html').data('pollinterval', 10)
		}

		$(document).bind('XFAjaxSuccess', LiveUpdate.AjaxSuccess);

		LiveUpdate.AjaxSuccess();
		setInterval(LiveUpdate.PollServer, LiveUpdate.pollInterval / 2);
	}

	LiveUpdate.PollServer = function()
	{
		if (!LiveUpdate.xhr && new Date().getTime() - LiveUpdate.lastAjaxCompleted > LiveUpdate.pollInterval)
    	{
    		var ajaxStart = $(document).data('events').ajaxStart[0].handler;
    		$(document).unbind('ajaxStart', ajaxStart);
    		LiveUpdate.xhr = XenForo.ajax('index.php?liveupdate', {}, function(){}, {
    			error: function(xhr, responseText, errorThrown)
    			{
    				delete(LiveUpdate.xhr);
    				switch (responseText)
					{
						case 'timeout':
						{
							console.warn(XenForo.phrases.server_did_not_respond_in_time_try_again);
							break;
						}
						case 'parsererror':
						{
							console.error('PHP ' + xhr.responseText);
							break;
						}
					}
					return false;
    			},
    			timeout: LiveUpdate.pollInterval
    		});
    		$(document).bind('ajaxStart', ajaxStart);
    	}
	}

	LiveUpdate.AjaxSuccess = function(ajaxData)
	{
		var count = parseInt($('#ConversationsMenu_Counter span.Total').text()) + parseInt($('#AlertsMenu_Counter span.Total').text());

		Tinycon.setBubble(count);

  		LiveUpdate.lastAjaxCompleted = new Date().getTime();

  		delete(LiveUpdate.xhr);
	}

	$(document).ready(LiveUpdate.SetupAutoPolling);
}
(jQuery, this, document);