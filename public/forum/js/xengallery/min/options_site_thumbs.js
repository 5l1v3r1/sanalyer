/*
 * XenForo options_site_thumbs.min.js
 * Copyright 2010-2016 XenForo Ltd.
 * Released under the XenForo License Agreement: http://xenforo.com/license-agreement
 */
(function(b){XenForo.MediaSiteThumbListener=function(a){this.__construct(a)};XenForo.MediaSiteThumbListener.prototype={__construct:function(a){a.one("change",b.context(this,"createChoice"));this.$element=a;if(!this.$base)this.$base=a.clone()},createChoice:function(){var a=this.$base.clone(),c=this.$element.parent().children().length;a.find("input[name], select[name]").each(function(){var a=b(this);a.attr("name",a.attr("name").replace(/\[(\d+)\]/,"["+c+"]"))});a.find("*[id]").each(function(){var a=
b(this);a.removeAttr("id");XenForo.uniqueId(a);XenForo.formCtrl&&XenForo.formCtrl.clean(a)});a.xfInsert("insertAfter",this.$element);this.__construct(a)}};XenForo.register("li.MediaSiteThumbListener","XenForo.MediaSiteThumbListener")})(jQuery,this,document);
