/*
 * XenForo category_list.min.js
 * Copyright 2010-2016 XenForo Ltd.
 * Released under the XenForo License Agreement: http://xenforo.com/license-agreement
 */
(function(){XenForo.CategoryList=function(a){this.__construct(a)};XenForo.CategoryList.prototype={__construct:function(a){a.sapling();a.data("liststyle")=="collapsibleExpanded"&&a.data("sapling").expand()}};XenForo.register(".CategoryList","XenForo.CategoryList")})(jQuery,this,document);
(function(a,k,l,e){a.sapling=function(f,d){var c=this,b=a(f),e={multiexpand:!0,animation:!1},g=function(a){a.addClass("sapling-expanded")},h=function(a){a.removeClass("sapling-expanded")},j=function(i){i.target.nodeName!=="A"&&(a(this).hasClass("sapling-expanded")?h(a(this)):(c.settings.multiexpand||b.find(".sapling-expanded").not(a(this).parents()).trigger("click"),g(a(this))))};c.settings={};c.init=function(){c.settings=a.extend({},e,d);c.settings.animation&&(g=function(a){a.children("ul, ol").slideDown(function(){a.addClass("sapling-expanded")})},
h=function(a){a.children("ul, ol").slideUp(function(){a.removeClass("sapling-expanded")})});b.addClass("sapling-list");b.children("li").addClass("sapling-top-level");b.find("li").each(function(){var b=a(this),c=b.children("ul, ol");c.index()!==-1&&(b.addClass("sapling-item"),b.bind("click.sapling",j),c.bind("click.sapling",function(a){if(a.target.nodeName!="A")return!1}))})};c.expand=function(){g(b.find(".sapling-item"))};c.collapse=function(){h(b.find(".sapling-expanded"))};c.destroy=function(){b.removeClass("sapling-list");
b.children("li").removeClass("sapling-top-level");b.find("li").each(function(){var b=a(this),c=b.children("ul, ol");c.index()!==-1&&(b.removeClass("sapling-item"),b.unbind(".sapling"),c.removeAttr("style"),c.unbind(".sapling"))});b.find(".sapling-expanded").removeClass("sapling-expanded");b.data("sapling",null)};c.init()};a.fn.sapling=function(f){return this.each(function(){var d=a(this).data("sapling");if(d===e||d===null)d=new a.sapling(this,f),a(this).data("sapling",d)})}})(jQuery,window,document);