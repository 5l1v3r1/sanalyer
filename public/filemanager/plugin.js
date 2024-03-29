tinymce.PluginManager.add("filemanager", function(e) {
    function n(t) {
        0 === e.settings.external_filemanager_path.toLowerCase().indexOf(t.origin.toLowerCase()) && "responsivefilemanager" === t.data.sender && (tinymce.activeEditor.windowManager.getParams().setUrl(t.data.url), tinymce.activeEditor.windowManager.close(), window.removeEventListener ? window.removeEventListener("message", n, !1) : window.detachEvent("onmessage", n))
    }

    function t(t, i, a, s) {
        var r = window.innerWidth - 30,
            g = window.innerHeight - 60;
        if (r > 1800 && (r = 1800), g > 1200 && (g = 1200), r > 600) {
            var d = (r - 20) % 138;
            r = r - d + 10
        }
        urltype = 2, "image" == a && (urltype = 1), "media" == a && (urltype = 3);
        var o = "RESPONSIVE FileManager";
        "undefined" != typeof e.settings.filemanager_title && e.settings.filemanager_title && (o = e.settings.filemanager_title);
        var l = "key";
        "undefined" != typeof e.settings.filemanager_access_key && e.settings.filemanager_access_key && (l = e.settings.filemanager_access_key);
        var f = "";
        "undefined" != typeof e.settings.filemanager_sort_by && e.settings.filemanager_sort_by && (f = "&sort_by=" + e.settings.filemanager_sort_by);
        var m = 0;
        "undefined" != typeof e.settings.filemanager_descending && e.settings.filemanager_descending && (m = e.settings.filemanager_descending);
        var c = "";
        "undefined" != typeof e.settings.filemanager_subfolder && e.settings.filemanager_subfolder && (c = "&fldr=" + e.settings.filemanager_subfolder);
        var v = "";
        "undefined" != typeof e.settings.filemanager_crossdomain && e.settings.filemanager_crossdomain && (v = "&crossdomain=1", window.addEventListener ? window.addEventListener("message", n, !1) : window.attachEvent("onmessage", n)), tinymce.activeEditor.windowManager.open({
            title: o,
            file: e.settings.external_filemanager_path + "dialog.php?type=" + urltype + "&descending=" + m + f + c + v + "&lang=" + e.settings.language + "&akey=" + l + "&sort_by=date" + "&descending=1",
            width: r,
            height: g,
            resizable: !0,
            maximizable: !0,
            inline: 1
        }, {
            setUrl: function(n) {
                var i = s.document.getElementById(t);
                if (i.value = e.convertURL(n), "createEvent" in document) {
                    var a = document.createEvent("HTMLEvents");
                    a.initEvent("change", !1, !0), i.dispatchEvent(a)
                } else i.fireEvent("onchange")
            }
        })
    }
    return tinymce.activeEditor.settings.file_browser_callback = t, !1
});