/*
 * XenForo media_move.min.js
 * Copyright 2010-2016 XenForo Ltd.
 * Released under the XenForo License Agreement: http://xenforo.com/license-agreement
 */
(function(b){XenForo.XenGalleryMediaMove=function(a){this.__construct(a)};XenForo.XenGalleryMediaMove.prototype={__construct:function(a){this.$element=a;a.bind({AutoComplete:b.context(this,"userChosen")});a.bind({change:b.context(this,"resetAlbums")})},resetAlbums:function(){this.$element.val().length||b(".UserAlbumsList").xfFadeUp()},userChosen:function(){var a=this.$element.val();if(a.length)this.xhr=XenForo.ajax("index.php?xengallery/load-user-albums",{username:a},b.context(this,"ajaxSuccess"))},
ajaxSuccess:function(a){if(a.error)return XenForo.alert(a.error),!1;a.templateHtml&&new XenForo.ExtLoader(a,function(){b(a.templateHtml).xfInsert("replaceAll",".UserAlbumsList","xfFadeDown",XenForo.speed.normal)})}};XenForo.register(".AlbumOwner","XenForo.XenGalleryMediaMove")})(jQuery,this,document);
