/*
colpick Color Picker
Copyright 2013 Jose Vargas. Licensed under GPL license. Based on Stefan Petre's Color Picker www.eyecon.ro, dual licensed under the MIT and GPL licenses

For usage and examples: colpick.com/plugin
 */

(function(c){var m=function(){var d={showEvent:"click",onShow:function(){},onBeforeShow:function(){},onHide:function(){},onChange:function(){},onSubmit:function(){},colorScheme:"light",color:"3289c7",livePreview:!0,flat:!1,layout:"full",submit:1,submitText:"OK",height:156},p=function(a,b){var l=n(a);c(b).data("colpick").fields.eq(1).val(l.r).end().eq(2).val(l.g).end().eq(3).val(l.b).end()},g=function(a,b){c(b).data("colpick").fields.eq(4).val(Math.round(a.h)).end().eq(5).val(Math.round(a.s)).end().eq(6).val(Math.round(a.b)).end()},
f=function(a,b){c(b).data("colpick").fields.eq(0).val(e(a))},h=function(a,b){c(b).data("colpick").selector.css("backgroundColor","#"+e({h:a.h,s:100,b:100}));c(b).data("colpick").selectorIndic.css({left:parseInt(c(b).data("colpick").height*a.s/100,10),top:parseInt(c(b).data("colpick").height*(100-a.b)/100,10)})},s=function(a,b){c(b).data("colpick").hue.css("top",parseInt(c(b).data("colpick").height-c(b).data("colpick").height*a.h/360,10))},m=function(a,b){c(b).data("colpick").currentColor.css("backgroundColor",
"#"+e(a))},t=function(a,b){c(b).data("colpick").newColor.css("backgroundColor","#"+e(a))},q=function(a){a=c(this).parent().parent();var b;if(0<this.parentNode.className.indexOf("_hex")){b=a.data("colpick");var l=this.value,d=6-l.length;if(0<d){for(var k=[],m=0;m<d;m++)k.push("0");k.push(l);l=k.join("")}b.color=b=u(l);p(b,a.get(0));g(b,a.get(0))}else 0<this.parentNode.className.indexOf("_hsb")?(a.data("colpick").color=b=x({h:parseInt(a.data("colpick").fields.eq(4).val(),10),s:parseInt(a.data("colpick").fields.eq(5).val(),
10),b:parseInt(a.data("colpick").fields.eq(6).val(),10)}),p(b,a.get(0)),f(b,a.get(0))):(b=a.data("colpick"),l=parseInt(a.data("colpick").fields.eq(1).val(),10),d=parseInt(a.data("colpick").fields.eq(2).val(),10),k=parseInt(a.data("colpick").fields.eq(3).val(),10),l={r:Math.min(255,Math.max(0,l)),g:Math.min(255,Math.max(0,d)),b:Math.min(255,Math.max(0,k))},b.color=b=r(l),f(b,a.get(0)),g(b,a.get(0)));h(b,a.get(0));s(b,a.get(0));t(b,a.get(0));a.data("colpick").onChange.apply(a.parent(),[b,e(b),n(b),
a.data("colpick").el,0])},v=function(a){c(this).parent().removeClass("colpick_focus")},w=function(){c(this).parent().parent().data("colpick").fields.parent().removeClass("colpick_focus");c(this).parent().addClass("colpick_focus")},G=function(a){a.preventDefault?a.preventDefault():a.returnValue=!1;var b=c(this).parent().find("input").focus();a={el:c(this).parent().addClass("colpick_slider"),max:0<this.parentNode.className.indexOf("_hsb_h")?360:0<this.parentNode.className.indexOf("_hsb")?100:255,y:a.pageY,
field:b,val:parseInt(b.val(),10),preview:c(this).parent().parent().data("colpick").livePreview};c(document).mouseup(a,y);c(document).mousemove(a,z)},z=function(a){a.data.field.val(Math.max(0,Math.min(a.data.max,parseInt(a.data.val-a.pageY+a.data.y,10))));a.data.preview&&q.apply(a.data.field.get(0),[!0]);return!1},y=function(a){q.apply(a.data.field.get(0),[!0]);a.data.el.removeClass("colpick_slider").find("input").focus();c(document).off("mouseup",y);c(document).off("mousemove",z);return!1},H=function(a){a.preventDefault?
a.preventDefault():a.returnValue=!1;var b={cal:c(this).parent(),y:c(this).offset().top};c(document).on("mouseup touchend",b,A);c(document).on("mousemove touchmove",b,B);a="touchstart"==a.type?a.originalEvent.changedTouches[0].pageY:a.pageY;q.apply(b.cal.data("colpick").fields.eq(4).val(parseInt(360*(b.cal.data("colpick").height-(a-b.y))/b.cal.data("colpick").height,10)).get(0),[b.cal.data("colpick").livePreview]);return!1},B=function(a){var b="touchmove"==a.type?a.originalEvent.changedTouches[0].pageY:
a.pageY;q.apply(a.data.cal.data("colpick").fields.eq(4).val(parseInt(360*(a.data.cal.data("colpick").height-Math.max(0,Math.min(a.data.cal.data("colpick").height,b-a.data.y)))/a.data.cal.data("colpick").height,10)).get(0),[a.data.preview]);return!1},A=function(a){p(a.data.cal.data("colpick").color,a.data.cal.get(0));f(a.data.cal.data("colpick").color,a.data.cal.get(0));c(document).off("mouseup touchend",A);c(document).off("mousemove touchmove",B);return!1},I=function(a){a.preventDefault?a.preventDefault():
a.returnValue=!1;var b={cal:c(this).parent(),pos:c(this).offset()};b.preview=b.cal.data("colpick").livePreview;c(document).on("mouseup touchend",b,C);c(document).on("mousemove touchmove",b,D);"touchstart"==a.type?(pageX=a.originalEvent.changedTouches[0].pageX,a=a.originalEvent.changedTouches[0].pageY):(pageX=a.pageX,a=a.pageY);q.apply(b.cal.data("colpick").fields.eq(6).val(parseInt(100*(b.cal.data("colpick").height-(a-b.pos.top))/b.cal.data("colpick").height,10)).end().eq(5).val(parseInt(100*(pageX-
b.pos.left)/b.cal.data("colpick").height,10)).get(0),[b.preview]);return!1},D=function(a){var b;"touchmove"==a.type?(pageX=a.originalEvent.changedTouches[0].pageX,b=a.originalEvent.changedTouches[0].pageY):(pageX=a.pageX,b=a.pageY);q.apply(a.data.cal.data("colpick").fields.eq(6).val(parseInt(100*(a.data.cal.data("colpick").height-Math.max(0,Math.min(a.data.cal.data("colpick").height,b-a.data.pos.top)))/a.data.cal.data("colpick").height,10)).end().eq(5).val(parseInt(100*Math.max(0,Math.min(a.data.cal.data("colpick").height,
pageX-a.data.pos.left))/a.data.cal.data("colpick").height,10)).get(0),[a.data.preview]);return!1},C=function(a){p(a.data.cal.data("colpick").color,a.data.cal.get(0));f(a.data.cal.data("colpick").color,a.data.cal.get(0));c(document).off("mouseup touchend",C);c(document).off("mousemove touchmove",D);return!1},J=function(a){a=c(this).parent();var b=a.data("colpick").color;a.data("colpick").origColor=b;m(b,a.get(0));a.data("colpick").onSubmit(b,e(b),n(b),a.data("colpick").el)},F=function(a){a.stopPropagation();
a=c("#"+c(this).data("colpickId"));a.data("colpick").onBeforeShow.apply(this,[a.get(0)]);var b=c(this).offset(),d=b.top+this.offsetHeight,b=b.left,p=K(),f=a.width();b+f>p.l+p.w&&(b-=f);a.css({left:b+"px",top:d+"px"});!1!=a.data("colpick").onShow.apply(this,[a.get(0)])&&a.show();c("html").mousedown({cal:a},E);a.mousedown(function(a){a.stopPropagation()})},E=function(a){!1!=a.data.cal.data("colpick").onHide.apply(this,[a.data.cal.get(0)])&&a.data.cal.hide();c("html").off("mousedown",E)},K=function(){var a=
"CSS1Compat"==document.compatMode;return{l:window.pageXOffset||(a?document.documentElement.scrollLeft:document.body.scrollLeft),w:window.innerWidth||(a?document.documentElement.clientWidth:document.body.clientWidth)}},x=function(a){return{h:Math.min(360,Math.max(0,a.h)),s:Math.min(100,Math.max(0,a.s)),b:Math.min(100,Math.max(0,a.b))}},L=function(){var a=c(this).parent(),b=a.data("colpick").origColor;a.data("colpick").color=b;p(b,a.get(0));f(b,a.get(0));g(b,a.get(0));h(b,a.get(0));s(b,a.get(0));t(b,
a.get(0))};return{init:function(a){a=c.extend({},d,a||{});if("string"==typeof a.color)a.color=u(a.color);else if(void 0!=a.color.r&&void 0!=a.color.g&&void 0!=a.color.b)a.color=r(a.color);else if(void 0!=a.color.h&&void 0!=a.color.s&&void 0!=a.color.b)a.color=x(a.color);else return this;return this.each(function(){if(!c(this).data("colpickId")){var b=c.extend({},a);b.origColor=a.color;var d="collorpicker_"+parseInt(1E3*Math.random());c(this).data("colpickId",d);d=c('<div class="colpick"><div class="colpick_color"><div class="colpick_color_overlay1"><div class="colpick_color_overlay2"><div class="colpick_selector_outer"><div class="colpick_selector_inner"></div></div></div></div></div><div class="colpick_hue"><div class="colpick_hue_arrs"><div class="colpick_hue_larr"></div><div class="colpick_hue_rarr"></div></div></div><div class="colpick_new_color"></div><div class="colpick_current_color"></div><div class="colpick_hex_field"><div class="colpick_field_letter">#</div><input type="text" maxlength="6" size="6" /></div><div class="colpick_rgb_r colpick_field"><div class="colpick_field_letter">R</div><input type="text" maxlength="3" size="3" /><div class="colpick_field_arrs"><div class="colpick_field_uarr"></div><div class="colpick_field_darr"></div></div></div><div class="colpick_rgb_g colpick_field"><div class="colpick_field_letter">G</div><input type="text" maxlength="3" size="3" /><div class="colpick_field_arrs"><div class="colpick_field_uarr"></div><div class="colpick_field_darr"></div></div></div><div class="colpick_rgb_b colpick_field"><div class="colpick_field_letter">B</div><input type="text" maxlength="3" size="3" /><div class="colpick_field_arrs"><div class="colpick_field_uarr"></div><div class="colpick_field_darr"></div></div></div><div class="colpick_hsb_h colpick_field"><div class="colpick_field_letter">H</div><input type="text" maxlength="3" size="3" /><div class="colpick_field_arrs"><div class="colpick_field_uarr"></div><div class="colpick_field_darr"></div></div></div><div class="colpick_hsb_s colpick_field"><div class="colpick_field_letter">S</div><input type="text" maxlength="3" size="3" /><div class="colpick_field_arrs"><div class="colpick_field_uarr"></div><div class="colpick_field_darr"></div></div></div><div class="colpick_hsb_b colpick_field"><div class="colpick_field_letter">B</div><input type="text" maxlength="3" size="3" /><div class="colpick_field_arrs"><div class="colpick_field_uarr"></div><div class="colpick_field_darr"></div></div></div><div class="colpick_submit"></div></div>').attr("id",
d);d.addClass("colpick_"+b.layout+(b.submit?"":" colpick_"+b.layout+"_ns"));"light"!=b.colorScheme&&d.addClass("colpick_"+b.colorScheme);d.find("div.colpick_submit").html(b.submitText).click(J);b.fields=d.find("input").change(q).blur(v).focus(w);d.find("div.colpick_field_arrs").mousedown(G).end().find("div.colpick_current_color").click(L);b.selector=d.find("div.colpick_color").on("mousedown touchstart",I);b.selectorIndic=b.selector.find("div.colpick_selector_outer");b.el=this;b.hue=d.find("div.colpick_hue_arrs");
huebar=b.hue.parent();var e=navigator.userAgent.toLowerCase(),k="Microsoft Internet Explorer"===navigator.appName,n=k?parseFloat(e.match(/msie ([0-9]{1,}[\.0-9]{0,})/)[1]):0,e="#ff0000 #ff0080 #ff00ff #8000ff #0000ff #0080ff #00ffff #00ff80 #00ff00 #80ff00 #ffff00 #ff8000 #ff0000".split(" ");if(k&&10>n)for(k=0;11>=k;k++)n=c("<div></div>").attr("style","height:8.333333%; filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr="+e[k]+", endColorstr="+e[k+1]+'); -ms-filter: "progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='+
e[k]+", endColorstr="+e[k+1]+')";'),huebar.append(n);else stopList=e.join(","),huebar.attr("style","background:-webkit-linear-gradient(top,"+stopList+"); background: -o-linear-gradient(top,"+stopList+"); background: -ms-linear-gradient(top,"+stopList+"); background:-moz-linear-gradient(top,"+stopList+"); -webkit-linear-gradient(top,"+stopList+"); background:linear-gradient(to bottom,"+stopList+"); ");d.find("div.colpick_hue").on("mousedown touchstart",H);b.newColor=d.find("div.colpick_new_color");
b.currentColor=d.find("div.colpick_current_color");d.data("colpick",b);p(b.color,d.get(0));g(b.color,d.get(0));f(b.color,d.get(0));s(b.color,d.get(0));h(b.color,d.get(0));m(b.color,d.get(0));t(b.color,d.get(0));b.flat?(d.appendTo(this).show(),d.css({position:"relative",display:"block"})):(d.appendTo(document.body),c(this).on(b.showEvent,F),d.css({position:"absolute"}))}})},showPicker:function(){return this.each(function(){c(this).data("colpickId")&&F.apply(this)})},hidePicker:function(){return this.each(function(){c(this).data("colpickId")&&
c("#"+c(this).data("colpickId")).hide()})},setColor:function(a,b){b="undefined"===typeof b?1:b;if("string"==typeof a)a=u(a);else if(void 0!=a.r&&void 0!=a.g&&void 0!=a.b)a=r(a);else if(void 0!=a.h&&void 0!=a.s&&void 0!=a.b)a=x(a);else return this;return this.each(function(){if(c(this).data("colpickId")){var d=c("#"+c(this).data("colpickId"));d.data("colpick").color=a;d.data("colpick").origColor=a;p(a,d.get(0));g(a,d.get(0));f(a,d.get(0));s(a,d.get(0));h(a,d.get(0));t(a,d.get(0));d.data("colpick").onChange.apply(d.parent(),
[a,e(a),n(a),d.data("colpick").el,1]);b&&m(a,d.get(0))}})}}}(),v=function(d){d=parseInt(-1<d.indexOf("#")?d.substring(1):d,16);return{r:d>>16,g:(d&65280)>>8,b:d&255}},u=function(d){return r(v(d))},r=function(d){var c={h:0,s:0,b:0},g=Math.min(d.r,d.g,d.b),f=Math.max(d.r,d.g,d.b),g=f-g;c.b=f;c.s=0!=f?255*g/f:0;c.h=0!=c.s?d.r==f?(d.g-d.b)/g:d.g==f?2+(d.b-d.r)/g:4+(d.r-d.g)/g:-1;c.h*=60;0>c.h&&(c.h+=360);c.s*=100/255;c.b*=100/255;return c},n=function(d){var c,g,f;c=d.h;var h=255*d.s/100;d=255*d.b/100;
if(0==h)c=g=f=d;else{var h=(255-h)*d/255,e=c%60*(d-h)/60;360==c&&(c=0);60>c?(c=d,f=h,g=h+e):120>c?(g=d,f=h,c=d-e):180>c?(g=d,c=h,f=h+e):240>c?(f=d,c=h,g=d-e):300>c?(f=d,g=h,c=h+e):360>c?(c=d,g=h,f=d-e):f=g=c=0}return{r:Math.round(c),g:Math.round(g),b:Math.round(f)}},w=function(d){var e=[d.r.toString(16),d.g.toString(16),d.b.toString(16)];c.each(e,function(d,c){1==c.length&&(e[d]="0"+c)});return e.join("")},e=function(c){return w(n(c))};c.fn.extend({colpick:m.init,colpickHide:m.hidePicker,colpickShow:m.showPicker,
colpickSetColor:m.setColor});c.extend({colpick:{rgbToHex:w,rgbToHsb:r,hsbToHex:e,hsbToRgb:n,hexToHsb:u,hexToRgb:v}})})(jQuery);