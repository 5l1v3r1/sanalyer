function headerHeadlineStop() {
    clearInterval(slideTimer)
}

function headerHeadlineStart() {
    slideTimer = setInterval(function() {
        headerHeadlineCurrent++, headerHeadlineCurrent >= headerHeadlineLimit && (headerHeadlineCurrent = 0), updateHeadlineText()
    }, 5e3)
}

function updateHeadlineText() {
    isButtonEnable = !1, text = "", $(".header__tabbar--left__headline--list__title").removeClass("is-active"), $(".header__tabbar--left__headline--list__title").eq(headerHeadlineCurrent).addClass("is-active"), text = $(".header__tabbar--left__headline--list__title").eq(headerHeadlineCurrent).find("a").attr("title"), $(".header__tabbar--left__headline--list__title").eq(headerHeadlineCurrent).find("a").writeText(text), isButtonEnable = !0
}

function readURL(e) {
    if (e.files && e.files[0]) {
        var t = new FileReader;
        t.onload = function(e) {
            $(".avatar-image").attr("src", e.target.result)
        }, t.readAsDataURL(e.files[0])
    }
}

function loadThread() {
    var e = $("body").find(".thread-section"),
        t = $(e).attr("data-thread-id");
    e.length > 0 && $.ajax({
		headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
        type: "POST",
        url: "/ajax/comments",
        dataType: "HTML",
        data: {
            thread_id: t
        },
        success: function(t) {
            $(e).append(t), $(e).attr("class", "thread-section-loaded")
        }
    })
}

function insertCommentStatus(e, t) {
    var n = "";
    if (0 == t.find(".comment-status-msg").hasClass("success"))
        if ("success" == e ? n = "Yorumunuz başarılı bir şekilde gönderildi. Editör onayından geçtikten sonra onaylanacaktır." : "error" == e && (n = t.parents(".content-comments__form--sub").length > 0 ? "10 karakterden kısa" : "30 karakterden kısa"), t.find(".comment-status-msg").length > 0) t.find(".comment-status-msg").removeClass("error success").addClass(e).html(n);
        else {
            var i = "";
            i += '<div class="comment-status-msg ' + e + '">', i += n, i += "</div>", t.prepend(i)
        }
}

function sendComment() {
    var e = $("form.active"),
        t = e.serializeArray();
    e.find(".content-comments__form__send").attr("disabled", !0), $.ajax({
		headers: {
				  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
        type: "POST",
        url: "/ajax/comment-send",
        dataType: "HTML",
        data: $.param(t),
        success: function(t) {
            e.find("textarea").val(""), e.find("textarea").attr("disabled", !0), insertCommentStatus("success", e.find(".content-comments__form__comment").parents(".content-comments__form__row"))
        }
    })
}

function voteCalc(e, t) {
    var n, i = e.text().split(t),
        i = parseInt(i[1]);
    return isNaN(i) === !1 ? (i += 1, n = t, n += i) : n = t + "1", n
}! function(e, t) {
    "object" == typeof module && "object" == typeof module.exports ? module.exports = e.document ? t(e, !0) : function(e) {
        if (!e.document) throw new Error("jQuery requires a window with a document");
        return t(e)
    } : t(e)
}("undefined" != typeof window ? window : this, function(e, t) {
    function n(e) {
        var t = "length" in e && e.length,
            n = J.type(e);
        return "function" !== n && !J.isWindow(e) && (!(1 !== e.nodeType || !t) || ("array" === n || 0 === t || "number" == typeof t && t > 0 && t - 1 in e))
    }

    function i(e, t, n) {
        if (J.isFunction(t)) return J.grep(e, function(e, i) {
            return !!t.call(e, i, e) !== n
        });
        if (t.nodeType) return J.grep(e, function(e) {
            return e === t !== n
        });
        if ("string" == typeof t) {
            if (se.test(t)) return J.filter(t, e, n);
            t = J.filter(t, e)
        }
        return J.grep(e, function(e) {
            return W.call(t, e) >= 0 !== n
        })
    }

    function r(e, t) {
        for (;
            (e = e[t]) && 1 !== e.nodeType;);
        return e
    }

    function a(e) {
        var t = he[e] = {};
        return J.each(e.match(pe) || [], function(e, n) {
            t[n] = !0
        }), t
    }

    function o() {
        Q.removeEventListener("DOMContentLoaded", o, !1), e.removeEventListener("load", o, !1), J.ready()
    }

    function s() {
        Object.defineProperty(this.cache = {}, 0, {
            get: function() {
                return {}
            }
        }), this.expando = J.expando + s.uid++
    }

    function l(e, t, n) {
        var i;
        if (void 0 === n && 1 === e.nodeType)
            if (i = "data-" + t.replace(xe, "-$1").toLowerCase(), n = e.getAttribute(i), "string" == typeof n) {
                try {
                    n = "true" === n || "false" !== n && ("null" === n ? null : +n + "" === n ? +n : be.test(n) ? J.parseJSON(n) : n)
                } catch (r) {}
                ye.set(e, t, n)
            } else n = void 0;
        return n
    }

    function c() {
        return !0
    }

    function d() {
        return !1
    }

    function u() {
        try {
            return Q.activeElement
        } catch (e) {}
    }

    function f(e, t) {
        return J.nodeName(e, "table") && J.nodeName(11 !== t.nodeType ? t : t.firstChild, "tr") ? e.getElementsByTagName("tbody")[0] || e.appendChild(e.ownerDocument.createElement("tbody")) : e
    }

    function p(e) {
        return e.type = (null !== e.getAttribute("type")) + "/" + e.type, e
    }

    function h(e) {
        var t = Ie.exec(e.type);
        return t ? e.type = t[1] : e.removeAttribute("type"), e
    }

    function m(e, t) {
        for (var n = 0, i = e.length; i > n; n++) ve.set(e[n], "globalEval", !t || ve.get(t[n], "globalEval"))
    }

    function g(e, t) {
        var n, i, r, a, o, s, l, c;
        if (1 === t.nodeType) {
            if (ve.hasData(e) && (a = ve.access(e), o = ve.set(t, a), c = a.events)) {
                delete o.handle, o.events = {};
                for (r in c)
                    for (n = 0, i = c[r].length; i > n; n++) J.event.add(t, r, c[r][n])
            }
            ye.hasData(e) && (s = ye.access(e), l = J.extend({}, s), ye.set(t, l))
        }
    }

    function v(e, t) {
        var n = e.getElementsByTagName ? e.getElementsByTagName(t || "*") : e.querySelectorAll ? e.querySelectorAll(t || "*") : [];
        return void 0 === t || t && J.nodeName(e, t) ? J.merge([e], n) : n
    }

    function y(e, t) {
        var n = t.nodeName.toLowerCase();
        "input" === n && ke.test(e.type) ? t.checked = e.checked : ("input" === n || "textarea" === n) && (t.defaultValue = e.defaultValue)
    }

    function b(t, n) {
        var i, r = J(n.createElement(t)).appendTo(n.body),
            a = e.getDefaultComputedStyle && (i = e.getDefaultComputedStyle(r[0])) ? i.display : J.css(r[0], "display");
        return r.detach(), a
    }

    function x(e) {
        var t = Q,
            n = He[e];
        return n || (n = b(e, t), "none" !== n && n || (Pe = (Pe || J("<iframe frameborder='0' width='0' height='0'/>")).appendTo(t.documentElement), t = Pe[0].contentDocument, t.write(), t.close(), n = b(e, t), Pe.detach()), He[e] = n), n
    }

    function w(e, t, n) {
        var i, r, a, o, s = e.style;
        return n = n || Re(e), n && (o = n.getPropertyValue(t) || n[t]), n && ("" !== o || J.contains(e.ownerDocument, e) || (o = J.style(e, t)), Ve.test(o) && qe.test(t) && (i = s.width, r = s.minWidth, a = s.maxWidth, s.minWidth = s.maxWidth = s.width = o, o = n.width, s.width = i, s.minWidth = r, s.maxWidth = a)), void 0 !== o ? o + "" : o
    }

    function C(e, t) {
        return {
            get: function() {
                return e() ? void delete this.get : (this.get = t).apply(this, arguments)
            }
        }
    }

    function $(e, t) {
        if (t in e) return t;
        for (var n = t[0].toUpperCase() + t.slice(1), i = t, r = Xe.length; r--;)
            if (t = Xe[r] + n, t in e) return t;
        return i
    }

    function k(e, t, n) {
        var i = ze.exec(t);
        return i ? Math.max(0, i[1] - (n || 0)) + (i[2] || "px") : t
    }

    function T(e, t, n, i, r) {
        for (var a = n === (i ? "border" : "content") ? 4 : "width" === t ? 1 : 0, o = 0; 4 > a; a += 2) "margin" === n && (o += J.css(e, n + Ce[a], !0, r)), i ? ("content" === n && (o -= J.css(e, "padding" + Ce[a], !0, r)), "margin" !== n && (o -= J.css(e, "border" + Ce[a] + "Width", !0, r))) : (o += J.css(e, "padding" + Ce[a], !0, r), "padding" !== n && (o += J.css(e, "border" + Ce[a] + "Width", !0, r)));
        return o
    }

    function _(e, t, n) {
        var i = !0,
            r = "width" === t ? e.offsetWidth : e.offsetHeight,
            a = Re(e),
            o = "border-box" === J.css(e, "boxSizing", !1, a);
        if (0 >= r || null == r) {
            if (r = w(e, t, a), (0 > r || null == r) && (r = e.style[t]), Ve.test(r)) return r;
            i = o && (Y.boxSizingReliable() || r === e.style[t]), r = parseFloat(r) || 0
        }
        return r + T(e, t, n || (o ? "border" : "content"), i, a) + "px"
    }

    function A(e, t) {
        for (var n, i, r, a = [], o = 0, s = e.length; s > o; o++) i = e[o], i.style && (a[o] = ve.get(i, "olddisplay"), n = i.style.display, t ? (a[o] || "none" !== n || (i.style.display = ""), "" === i.style.display && $e(i) && (a[o] = ve.access(i, "olddisplay", x(i.nodeName)))) : (r = $e(i), "none" === n && r || ve.set(i, "olddisplay", r ? n : J.css(i, "display"))));
        for (o = 0; s > o; o++) i = e[o], i.style && (t && "none" !== i.style.display && "" !== i.style.display || (i.style.display = t ? a[o] || "" : "none"));
        return e
    }

    function E(e, t, n, i, r) {
        return new E.prototype.init(e, t, n, i, r)
    }

    function S() {
        return setTimeout(function() {
            Ye = void 0
        }), Ye = J.now()
    }

    function F(e, t) {
        var n, i = 0,
            r = {
                height: e
            };
        for (t = t ? 1 : 0; 4 > i; i += 2 - t) n = Ce[i], r["margin" + n] = r["padding" + n] = e;
        return t && (r.opacity = r.width = e), r
    }

    function M(e, t, n) {
        for (var i, r = (nt[t] || []).concat(nt["*"]), a = 0, o = r.length; o > a; a++)
            if (i = r[a].call(n, t, e)) return i
    }

    function D(e, t, n) {
        var i, r, a, o, s, l, c, d, u = this,
            f = {},
            p = e.style,
            h = e.nodeType && $e(e),
            m = ve.get(e, "fxshow");
        n.queue || (s = J._queueHooks(e, "fx"), null == s.unqueued && (s.unqueued = 0, l = s.empty.fire, s.empty.fire = function() {
            s.unqueued || l()
        }), s.unqueued++, u.always(function() {
            u.always(function() {
                s.unqueued--, J.queue(e, "fx").length || s.empty.fire()
            })
        })), 1 === e.nodeType && ("height" in t || "width" in t) && (n.overflow = [p.overflow, p.overflowX, p.overflowY], c = J.css(e, "display"), d = "none" === c ? ve.get(e, "olddisplay") || x(e.nodeName) : c, "inline" === d && "none" === J.css(e, "float") && (p.display = "inline-block")), n.overflow && (p.overflow = "hidden", u.always(function() {
            p.overflow = n.overflow[0], p.overflowX = n.overflow[1], p.overflowY = n.overflow[2]
        }));
        for (i in t)
            if (r = t[i], Ze.exec(r)) {
                if (delete t[i], a = a || "toggle" === r, r === (h ? "hide" : "show")) {
                    if ("show" !== r || !m || void 0 === m[i]) continue;
                    h = !0
                }
                f[i] = m && m[i] || J.style(e, i)
            } else c = void 0;
        if (J.isEmptyObject(f)) "inline" === ("none" === c ? x(e.nodeName) : c) && (p.display = c);
        else {
            m ? "hidden" in m && (h = m.hidden) : m = ve.access(e, "fxshow", {}), a && (m.hidden = !h), h ? J(e).show() : u.done(function() {
                J(e).hide()
            }), u.done(function() {
                var t;
                ve.remove(e, "fxshow");
                for (t in f) J.style(e, t, f[t])
            });
            for (i in f) o = M(h ? m[i] : 0, i, u), i in m || (m[i] = o.start, h && (o.end = o.start, o.start = "width" === i || "height" === i ? 1 : 0))
        }
    }

    function N(e, t) {
        var n, i, r, a, o;
        for (n in e)
            if (i = J.camelCase(n), r = t[i], a = e[n], J.isArray(a) && (r = a[1], a = e[n] = a[0]), n !== i && (e[i] = a, delete e[n]), o = J.cssHooks[i], o && "expand" in o) {
                a = o.expand(a), delete e[i];
                for (n in a) n in e || (e[n] = a[n], t[n] = r)
            } else t[i] = r
    }

    function O(e, t, n) {
        var i, r, a = 0,
            o = tt.length,
            s = J.Deferred().always(function() {
                delete l.elem
            }),
            l = function() {
                if (r) return !1;
                for (var t = Ye || S(), n = Math.max(0, c.startTime + c.duration - t), i = n / c.duration || 0, a = 1 - i, o = 0, l = c.tweens.length; l > o; o++) c.tweens[o].run(a);
                return s.notifyWith(e, [c, a, n]), 1 > a && l ? n : (s.resolveWith(e, [c]), !1)
            },
            c = s.promise({
                elem: e,
                props: J.extend({}, t),
                opts: J.extend(!0, {
                    specialEasing: {}
                }, n),
                originalProperties: t,
                originalOptions: n,
                startTime: Ye || S(),
                duration: n.duration,
                tweens: [],
                createTween: function(t, n) {
                    var i = J.Tween(e, c.opts, t, n, c.opts.specialEasing[t] || c.opts.easing);
                    return c.tweens.push(i), i
                },
                stop: function(t) {
                    var n = 0,
                        i = t ? c.tweens.length : 0;
                    if (r) return this;
                    for (r = !0; i > n; n++) c.tweens[n].run(1);
                    return t ? s.resolveWith(e, [c, t]) : s.rejectWith(e, [c, t]), this
                }
            }),
            d = c.props;
        for (N(d, c.opts.specialEasing); o > a; a++)
            if (i = tt[a].call(c, e, d, c.opts)) return i;
        return J.map(d, M, c), J.isFunction(c.opts.start) && c.opts.start.call(e, c), J.fx.timer(J.extend(l, {
            elem: e,
            anim: c,
            queue: c.opts.queue
        })), c.progress(c.opts.progress).done(c.opts.done, c.opts.complete).fail(c.opts.fail).always(c.opts.always)
    }

    function j(e) {
        return function(t, n) {
            "string" != typeof t && (n = t, t = "*");
            var i, r = 0,
                a = t.toLowerCase().match(pe) || [];
            if (J.isFunction(n))
                for (; i = a[r++];) "+" === i[0] ? (i = i.slice(1) || "*", (e[i] = e[i] || []).unshift(n)) : (e[i] = e[i] || []).push(n)
        }
    }

    function I(e, t, n, i) {
        function r(s) {
            var l;
            return a[s] = !0, J.each(e[s] || [], function(e, s) {
                var c = s(t, n, i);
                return "string" != typeof c || o || a[c] ? o ? !(l = c) : void 0 : (t.dataTypes.unshift(c), r(c), !1)
            }), l
        }
        var a = {},
            o = e === bt;
        return r(t.dataTypes[0]) || !a["*"] && r("*")
    }

    function U(e, t) {
        var n, i, r = J.ajaxSettings.flatOptions || {};
        for (n in t) void 0 !== t[n] && ((r[n] ? e : i || (i = {}))[n] = t[n]);
        return i && J.extend(!0, e, i), e
    }

    function L(e, t, n) {
        for (var i, r, a, o, s = e.contents, l = e.dataTypes;
            "*" === l[0];) l.shift(), void 0 === i && (i = e.mimeType || t.getResponseHeader("Content-Type"));
        if (i)
            for (r in s)
                if (s[r] && s[r].test(i)) {
                    l.unshift(r);
                    break
                }
        if (l[0] in n) a = l[0];
        else {
            for (r in n) {
                if (!l[0] || e.converters[r + " " + l[0]]) {
                    a = r;
                    break
                }
                o || (o = r)
            }
            a = a || o
        }
        return a ? (a !== l[0] && l.unshift(a), n[a]) : void 0
    }

    function P(e, t, n, i) {
        var r, a, o, s, l, c = {},
            d = e.dataTypes.slice();
        if (d[1])
            for (o in e.converters) c[o.toLowerCase()] = e.converters[o];
        for (a = d.shift(); a;)
            if (e.responseFields[a] && (n[e.responseFields[a]] = t), !l && i && e.dataFilter && (t = e.dataFilter(t, e.dataType)), l = a, a = d.shift())
                if ("*" === a) a = l;
                else if ("*" !== l && l !== a) {
            if (o = c[l + " " + a] || c["* " + a], !o)
                for (r in c)
                    if (s = r.split(" "), s[1] === a && (o = c[l + " " + s[0]] || c["* " + s[0]])) {
                        o === !0 ? o = c[r] : c[r] !== !0 && (a = s[0], d.unshift(s[1]));
                        break
                    }
            if (o !== !0)
                if (o && e["throws"]) t = o(t);
                else try {
                    t = o(t)
                } catch (u) {
                    return {
                        state: "parsererror",
                        error: o ? u : "No conversion from " + l + " to " + a
                    }
                }
        }
        return {
            state: "success",
            data: t
        }
    }

    function H(e, t, n, i) {
        var r;
        if (J.isArray(t)) J.each(t, function(t, r) {
            n || kt.test(e) ? i(e, r) : H(e + "[" + ("object" == typeof r ? t : "") + "]", r, n, i)
        });
        else if (n || "object" !== J.type(t)) i(e, t);
        else
            for (r in t) H(e + "[" + r + "]", t[r], n, i)
    }

    function q(e) {
        return J.isWindow(e) ? e : 9 === e.nodeType && e.defaultView
    }
    var V = [],
        R = V.slice,
        B = V.concat,
        z = V.push,
        W = V.indexOf,
        K = {},
        G = K.toString,
        X = K.hasOwnProperty,
        Y = {},
        Q = e.document,
        Z = "2.1.4",
        J = function(e, t) {
            return new J.fn.init(e, t)
        },
        ee = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,
        te = /^-ms-/,
        ne = /-([\da-z])/gi,
        ie = function(e, t) {
            return t.toUpperCase()
        };
    J.fn = J.prototype = {
        jquery: Z,
        constructor: J,
        selector: "",
        length: 0,
        toArray: function() {
            return R.call(this)
        },
        get: function(e) {
            return null != e ? 0 > e ? this[e + this.length] : this[e] : R.call(this)
        },
        pushStack: function(e) {
            var t = J.merge(this.constructor(), e);
            return t.prevObject = this, t.context = this.context, t
        },
        each: function(e, t) {
            return J.each(this, e, t)
        },
        map: function(e) {
            return this.pushStack(J.map(this, function(t, n) {
                return e.call(t, n, t)
            }))
        },
        slice: function() {
            return this.pushStack(R.apply(this, arguments))
        },
        first: function() {
            return this.eq(0)
        },
        last: function() {
            return this.eq(-1)
        },
        eq: function(e) {
            var t = this.length,
                n = +e + (0 > e ? t : 0);
            return this.pushStack(n >= 0 && t > n ? [this[n]] : [])
        },
        end: function() {
            return this.prevObject || this.constructor(null)
        },
        push: z,
        sort: V.sort,
        splice: V.splice
    }, J.extend = J.fn.extend = function() {
        var e, t, n, i, r, a, o = arguments[0] || {},
            s = 1,
            l = arguments.length,
            c = !1;
        for ("boolean" == typeof o && (c = o, o = arguments[s] || {}, s++), "object" == typeof o || J.isFunction(o) || (o = {}), s === l && (o = this, s--); l > s; s++)
            if (null != (e = arguments[s]))
                for (t in e) n = o[t], i = e[t], o !== i && (c && i && (J.isPlainObject(i) || (r = J.isArray(i))) ? (r ? (r = !1, a = n && J.isArray(n) ? n : []) : a = n && J.isPlainObject(n) ? n : {}, o[t] = J.extend(c, a, i)) : void 0 !== i && (o[t] = i));
        return o
    }, J.extend({
        expando: "jQuery" + (Z + Math.random()).replace(/\D/g, ""),
        isReady: !0,
        error: function(e) {
            throw new Error(e)
        },
        noop: function() {},
        isFunction: function(e) {
            return "function" === J.type(e)
        },
        isArray: Array.isArray,
        isWindow: function(e) {
            return null != e && e === e.window
        },
        isNumeric: function(e) {
            return !J.isArray(e) && e - parseFloat(e) + 1 >= 0
        },
        isPlainObject: function(e) {
            return "object" === J.type(e) && !e.nodeType && !J.isWindow(e) && !(e.constructor && !X.call(e.constructor.prototype, "isPrototypeOf"))
        },
        isEmptyObject: function(e) {
            var t;
            for (t in e) return !1;
            return !0
        },
        type: function(e) {
            return null == e ? e + "" : "object" == typeof e || "function" == typeof e ? K[G.call(e)] || "object" : typeof e
        },
        globalEval: function(e) {
            var t, n = eval;
            e = J.trim(e), e && (1 === e.indexOf("use strict") ? (t = Q.createElement("script"), t.text = e, Q.head.appendChild(t).parentNode.removeChild(t)) : n(e))
        },
        camelCase: function(e) {
            return e.replace(te, "ms-").replace(ne, ie)
        },
        nodeName: function(e, t) {
            return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
        },
        each: function(e, t, i) {
            var r, a = 0,
                o = e.length,
                s = n(e);
            if (i) {
                if (s)
                    for (; o > a && (r = t.apply(e[a], i), r !== !1); a++);
                else
                    for (a in e)
                        if (r = t.apply(e[a], i), r === !1) break
            } else if (s)
                for (; o > a && (r = t.call(e[a], a, e[a]), r !== !1); a++);
            else
                for (a in e)
                    if (r = t.call(e[a], a, e[a]), r === !1) break; return e
        },
        trim: function(e) {
            return null == e ? "" : (e + "").replace(ee, "")
        },
        makeArray: function(e, t) {
            var i = t || [];
            return null != e && (n(Object(e)) ? J.merge(i, "string" == typeof e ? [e] : e) : z.call(i, e)), i
        },
        inArray: function(e, t, n) {
            return null == t ? -1 : W.call(t, e, n)
        },
        merge: function(e, t) {
            for (var n = +t.length, i = 0, r = e.length; n > i; i++) e[r++] = t[i];
            return e.length = r, e
        },
        grep: function(e, t, n) {
            for (var i, r = [], a = 0, o = e.length, s = !n; o > a; a++) i = !t(e[a], a), i !== s && r.push(e[a]);
            return r
        },
        map: function(e, t, i) {
            var r, a = 0,
                o = e.length,
                s = n(e),
                l = [];
            if (s)
                for (; o > a; a++) r = t(e[a], a, i), null != r && l.push(r);
            else
                for (a in e) r = t(e[a], a, i), null != r && l.push(r);
            return B.apply([], l)
        },
        guid: 1,
        proxy: function(e, t) {
            var n, i, r;
            return "string" == typeof t && (n = e[t], t = e, e = n), J.isFunction(e) ? (i = R.call(arguments, 2), r = function() {
                return e.apply(t || this, i.concat(R.call(arguments)))
            }, r.guid = e.guid = e.guid || J.guid++, r) : void 0
        },
        now: Date.now,
        support: Y
    }), J.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(e, t) {
        K["[object " + t + "]"] = t.toLowerCase()
    });
    var re = function(e) {
        function t(e, t, n, i) {
            var r, a, o, s, l, c, u, p, h, m;
            if ((t ? t.ownerDocument || t : H) !== D && M(t), t = t || D, n = n || [], s = t.nodeType, "string" != typeof e || !e || 1 !== s && 9 !== s && 11 !== s) return n;
            if (!i && O) {
                if (11 !== s && (r = ye.exec(e)))
                    if (o = r[1]) {
                        if (9 === s) {
                            if (a = t.getElementById(o), !a || !a.parentNode) return n;
                            if (a.id === o) return n.push(a), n
                        } else if (t.ownerDocument && (a = t.ownerDocument.getElementById(o)) && L(t, a) && a.id === o) return n.push(a), n
                    } else {
                        if (r[2]) return Z.apply(n, t.getElementsByTagName(e)), n;
                        if ((o = r[3]) && w.getElementsByClassName) return Z.apply(n, t.getElementsByClassName(o)), n
                    }
                if (w.qsa && (!j || !j.test(e))) {
                    if (p = u = P, h = t, m = 1 !== s && e, 1 === s && "object" !== t.nodeName.toLowerCase()) {
                        for (c = T(e), (u = t.getAttribute("id")) ? p = u.replace(xe, "\\$&") : t.setAttribute("id", p), p = "[id='" + p + "'] ", l = c.length; l--;) c[l] = p + f(c[l]);
                        h = be.test(e) && d(t.parentNode) || t, m = c.join(",")
                    }
                    if (m) try {
                        return Z.apply(n, h.querySelectorAll(m)), n
                    } catch (g) {} finally {
                        u || t.removeAttribute("id")
                    }
                }
            }
            return A(e.replace(le, "$1"), t, n, i)
        }

        function n() {
            function e(n, i) {
                return t.push(n + " ") > C.cacheLength && delete e[t.shift()], e[n + " "] = i
            }
            var t = [];
            return e
        }

        function i(e) {
            return e[P] = !0, e
        }

        function r(e) {
            var t = D.createElement("div");
            try {
                return !!e(t)
            } catch (n) {
                return !1
            } finally {
                t.parentNode && t.parentNode.removeChild(t), t = null
            }
        }

        function a(e, t) {
            for (var n = e.split("|"), i = e.length; i--;) C.attrHandle[n[i]] = t
        }

        function o(e, t) {
            var n = t && e,
                i = n && 1 === e.nodeType && 1 === t.nodeType && (~t.sourceIndex || K) - (~e.sourceIndex || K);
            if (i) return i;
            if (n)
                for (; n = n.nextSibling;)
                    if (n === t) return -1;
            return e ? 1 : -1
        }

        function s(e) {
            return function(t) {
                var n = t.nodeName.toLowerCase();
                return "input" === n && t.type === e
            }
        }

        function l(e) {
            return function(t) {
                var n = t.nodeName.toLowerCase();
                return ("input" === n || "button" === n) && t.type === e
            }
        }

        function c(e) {
            return i(function(t) {
                return t = +t, i(function(n, i) {
                    for (var r, a = e([], n.length, t), o = a.length; o--;) n[r = a[o]] && (n[r] = !(i[r] = n[r]))
                })
            })
        }

        function d(e) {
            return e && "undefined" != typeof e.getElementsByTagName && e
        }

        function u() {}

        function f(e) {
            for (var t = 0, n = e.length, i = ""; n > t; t++) i += e[t].value;
            return i
        }

        function p(e, t, n) {
            var i = t.dir,
                r = n && "parentNode" === i,
                a = V++;
            return t.first ? function(t, n, a) {
                for (; t = t[i];)
                    if (1 === t.nodeType || r) return e(t, n, a)
            } : function(t, n, o) {
                var s, l, c = [q, a];
                if (o) {
                    for (; t = t[i];)
                        if ((1 === t.nodeType || r) && e(t, n, o)) return !0
                } else
                    for (; t = t[i];)
                        if (1 === t.nodeType || r) {
                            if (l = t[P] || (t[P] = {}), (s = l[i]) && s[0] === q && s[1] === a) return c[2] = s[2];
                            if (l[i] = c, c[2] = e(t, n, o)) return !0
                        }
            }
        }

        function h(e) {
            return e.length > 1 ? function(t, n, i) {
                for (var r = e.length; r--;)
                    if (!e[r](t, n, i)) return !1;
                return !0
            } : e[0]
        }

        function m(e, n, i) {
            for (var r = 0, a = n.length; a > r; r++) t(e, n[r], i);
            return i
        }

        function g(e, t, n, i, r) {
            for (var a, o = [], s = 0, l = e.length, c = null != t; l > s; s++)(a = e[s]) && (!n || n(a, i, r)) && (o.push(a), c && t.push(s));
            return o
        }

        function v(e, t, n, r, a, o) {
            return r && !r[P] && (r = v(r)), a && !a[P] && (a = v(a, o)), i(function(i, o, s, l) {
                var c, d, u, f = [],
                    p = [],
                    h = o.length,
                    v = i || m(t || "*", s.nodeType ? [s] : s, []),
                    y = !e || !i && t ? v : g(v, f, e, s, l),
                    b = n ? a || (i ? e : h || r) ? [] : o : y;
                if (n && n(y, b, s, l), r)
                    for (c = g(b, p), r(c, [], s, l), d = c.length; d--;)(u = c[d]) && (b[p[d]] = !(y[p[d]] = u));
                if (i) {
                    if (a || e) {
                        if (a) {
                            for (c = [], d = b.length; d--;)(u = b[d]) && c.push(y[d] = u);
                            a(null, b = [], c, l)
                        }
                        for (d = b.length; d--;)(u = b[d]) && (c = a ? ee(i, u) : f[d]) > -1 && (i[c] = !(o[c] = u))
                    }
                } else b = g(b === o ? b.splice(h, b.length) : b), a ? a(null, o, b, l) : Z.apply(o, b)
            })
        }

        function y(e) {
            for (var t, n, i, r = e.length, a = C.relative[e[0].type], o = a || C.relative[" "], s = a ? 1 : 0, l = p(function(e) {
                    return e === t
                }, o, !0), c = p(function(e) {
                    return ee(t, e) > -1
                }, o, !0), d = [function(e, n, i) {
                    var r = !a && (i || n !== E) || ((t = n).nodeType ? l(e, n, i) : c(e, n, i));
                    return t = null, r
                }]; r > s; s++)
                if (n = C.relative[e[s].type]) d = [p(h(d), n)];
                else {
                    if (n = C.filter[e[s].type].apply(null, e[s].matches), n[P]) {
                        for (i = ++s; r > i && !C.relative[e[i].type]; i++);
                        return v(s > 1 && h(d), s > 1 && f(e.slice(0, s - 1).concat({
                            value: " " === e[s - 2].type ? "*" : ""
                        })).replace(le, "$1"), n, i > s && y(e.slice(s, i)), r > i && y(e = e.slice(i)), r > i && f(e))
                    }
                    d.push(n)
                }
            return h(d)
        }

        function b(e, n) {
            var r = n.length > 0,
                a = e.length > 0,
                o = function(i, o, s, l, c) {
                    var d, u, f, p = 0,
                        h = "0",
                        m = i && [],
                        v = [],
                        y = E,
                        b = i || a && C.find.TAG("*", c),
                        x = q += null == y ? 1 : Math.random() || .1,
                        w = b.length;
                    for (c && (E = o !== D && o); h !== w && null != (d = b[h]); h++) {
                        if (a && d) {
                            for (u = 0; f = e[u++];)
                                if (f(d, o, s)) {
                                    l.push(d);
                                    break
                                }
                            c && (q = x)
                        }
                        r && ((d = !f && d) && p--, i && m.push(d))
                    }
                    if (p += h, r && h !== p) {
                        for (u = 0; f = n[u++];) f(m, v, o, s);
                        if (i) {
                            if (p > 0)
                                for (; h--;) m[h] || v[h] || (v[h] = Y.call(l));
                            v = g(v)
                        }
                        Z.apply(l, v), c && !i && v.length > 0 && p + n.length > 1 && t.uniqueSort(l)
                    }
                    return c && (q = x, E = y), m
                };
            return r ? i(o) : o
        }
        var x, w, C, $, k, T, _, A, E, S, F, M, D, N, O, j, I, U, L, P = "sizzle" + 1 * new Date,
            H = e.document,
            q = 0,
            V = 0,
            R = n(),
            B = n(),
            z = n(),
            W = function(e, t) {
                return e === t && (F = !0), 0
            },
            K = 1 << 31,
            G = {}.hasOwnProperty,
            X = [],
            Y = X.pop,
            Q = X.push,
            Z = X.push,
            J = X.slice,
            ee = function(e, t) {
                for (var n = 0, i = e.length; i > n; n++)
                    if (e[n] === t) return n;
                return -1
            },
            te = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
            ne = "[\\x20\\t\\r\\n\\f]",
            ie = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",
            re = ie.replace("w", "w#"),
            ae = "\\[" + ne + "*(" + ie + ")(?:" + ne + "*([*^$|!~]?=)" + ne + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + re + "))|)" + ne + "*\\]",
            oe = ":(" + ie + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + ae + ")*)|.*)\\)|)",
            se = new RegExp(ne + "+", "g"),
            le = new RegExp("^" + ne + "+|((?:^|[^\\\\])(?:\\\\.)*)" + ne + "+$", "g"),
            ce = new RegExp("^" + ne + "*," + ne + "*"),
            de = new RegExp("^" + ne + "*([>+~]|" + ne + ")" + ne + "*"),
            ue = new RegExp("=" + ne + "*([^\\]'\"]*?)" + ne + "*\\]", "g"),
            fe = new RegExp(oe),
            pe = new RegExp("^" + re + "$"),
            he = {
                ID: new RegExp("^#(" + ie + ")"),
                CLASS: new RegExp("^\\.(" + ie + ")"),
                TAG: new RegExp("^(" + ie.replace("w", "w*") + ")"),
                ATTR: new RegExp("^" + ae),
                PSEUDO: new RegExp("^" + oe),
                CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + ne + "*(even|odd|(([+-]|)(\\d*)n|)" + ne + "*(?:([+-]|)" + ne + "*(\\d+)|))" + ne + "*\\)|)", "i"),
                bool: new RegExp("^(?:" + te + ")$", "i"),
                needsContext: new RegExp("^" + ne + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + ne + "*((?:-\\d)?\\d*)" + ne + "*\\)|)(?=[^-]|$)", "i")
            },
            me = /^(?:input|select|textarea|button)$/i,
            ge = /^h\d$/i,
            ve = /^[^{]+\{\s*\[native \w/,
            ye = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
            be = /[+~]/,
            xe = /'|\\/g,
            we = new RegExp("\\\\([\\da-f]{1,6}" + ne + "?|(" + ne + ")|.)", "ig"),
            Ce = function(e, t, n) {
                var i = "0x" + t - 65536;
                return i !== i || n ? t : 0 > i ? String.fromCharCode(i + 65536) : String.fromCharCode(i >> 10 | 55296, 1023 & i | 56320)
            },
            $e = function() {
                M()
            };
        try {
            Z.apply(X = J.call(H.childNodes), H.childNodes), X[H.childNodes.length].nodeType
        } catch (ke) {
            Z = {
                apply: X.length ? function(e, t) {
                    Q.apply(e, J.call(t))
                } : function(e, t) {
                    for (var n = e.length, i = 0; e[n++] = t[i++];);
                    e.length = n - 1
                }
            }
        }
        w = t.support = {}, k = t.isXML = function(e) {
            var t = e && (e.ownerDocument || e).documentElement;
            return !!t && "HTML" !== t.nodeName
        }, M = t.setDocument = function(e) {
            var t, n, i = e ? e.ownerDocument || e : H;
            return i !== D && 9 === i.nodeType && i.documentElement ? (D = i, N = i.documentElement, n = i.defaultView, n && n !== n.top && (n.addEventListener ? n.addEventListener("unload", $e, !1) : n.attachEvent && n.attachEvent("onunload", $e)), O = !k(i), w.attributes = r(function(e) {
                return e.className = "i", !e.getAttribute("className")
            }), w.getElementsByTagName = r(function(e) {
                return e.appendChild(i.createComment("")), !e.getElementsByTagName("*").length
            }), w.getElementsByClassName = ve.test(i.getElementsByClassName), w.getById = r(function(e) {
                return N.appendChild(e).id = P, !i.getElementsByName || !i.getElementsByName(P).length
            }), w.getById ? (C.find.ID = function(e, t) {
                if ("undefined" != typeof t.getElementById && O) {
                    var n = t.getElementById(e);
                    return n && n.parentNode ? [n] : []
                }
            }, C.filter.ID = function(e) {
                var t = e.replace(we, Ce);
                return function(e) {
                    return e.getAttribute("id") === t
                }
            }) : (delete C.find.ID, C.filter.ID = function(e) {
                var t = e.replace(we, Ce);
                return function(e) {
                    var n = "undefined" != typeof e.getAttributeNode && e.getAttributeNode("id");
                    return n && n.value === t
                }
            }), C.find.TAG = w.getElementsByTagName ? function(e, t) {
                return "undefined" != typeof t.getElementsByTagName ? t.getElementsByTagName(e) : w.qsa ? t.querySelectorAll(e) : void 0
            } : function(e, t) {
                var n, i = [],
                    r = 0,
                    a = t.getElementsByTagName(e);
                if ("*" === e) {
                    for (; n = a[r++];) 1 === n.nodeType && i.push(n);
                    return i
                }
                return a
            }, C.find.CLASS = w.getElementsByClassName && function(e, t) {
                return O ? t.getElementsByClassName(e) : void 0
            }, I = [], j = [], (w.qsa = ve.test(i.querySelectorAll)) && (r(function(e) {
                N.appendChild(e).innerHTML = "<a id='" + P + "'></a><select id='" + P + "-\f]' msallowcapture=''><option selected=''></option></select>", e.querySelectorAll("[msallowcapture^='']").length && j.push("[*^$]=" + ne + "*(?:''|\"\")"), e.querySelectorAll("[selected]").length || j.push("\\[" + ne + "*(?:value|" + te + ")"), e.querySelectorAll("[id~=" + P + "-]").length || j.push("~="), e.querySelectorAll(":checked").length || j.push(":checked"), e.querySelectorAll("a#" + P + "+*").length || j.push(".#.+[+~]")
            }), r(function(e) {
                var t = i.createElement("input");
                t.setAttribute("type", "hidden"), e.appendChild(t).setAttribute("name", "D"), e.querySelectorAll("[name=d]").length && j.push("name" + ne + "*[*^$|!~]?="), e.querySelectorAll(":enabled").length || j.push(":enabled", ":disabled"), e.querySelectorAll("*,:x"), j.push(",.*:")
            })), (w.matchesSelector = ve.test(U = N.matches || N.webkitMatchesSelector || N.mozMatchesSelector || N.oMatchesSelector || N.msMatchesSelector)) && r(function(e) {
                w.disconnectedMatch = U.call(e, "div"), U.call(e, "[s!='']:x"), I.push("!=", oe)
            }), j = j.length && new RegExp(j.join("|")), I = I.length && new RegExp(I.join("|")), t = ve.test(N.compareDocumentPosition), L = t || ve.test(N.contains) ? function(e, t) {
                var n = 9 === e.nodeType ? e.documentElement : e,
                    i = t && t.parentNode;
                return e === i || !(!i || 1 !== i.nodeType || !(n.contains ? n.contains(i) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(i)))
            } : function(e, t) {
                if (t)
                    for (; t = t.parentNode;)
                        if (t === e) return !0;
                return !1
            }, W = t ? function(e, t) {
                if (e === t) return F = !0, 0;
                var n = !e.compareDocumentPosition - !t.compareDocumentPosition;
                return n ? n : (n = (e.ownerDocument || e) === (t.ownerDocument || t) ? e.compareDocumentPosition(t) : 1, 1 & n || !w.sortDetached && t.compareDocumentPosition(e) === n ? e === i || e.ownerDocument === H && L(H, e) ? -1 : t === i || t.ownerDocument === H && L(H, t) ? 1 : S ? ee(S, e) - ee(S, t) : 0 : 4 & n ? -1 : 1)
            } : function(e, t) {
                if (e === t) return F = !0, 0;
                var n, r = 0,
                    a = e.parentNode,
                    s = t.parentNode,
                    l = [e],
                    c = [t];
                if (!a || !s) return e === i ? -1 : t === i ? 1 : a ? -1 : s ? 1 : S ? ee(S, e) - ee(S, t) : 0;
                if (a === s) return o(e, t);
                for (n = e; n = n.parentNode;) l.unshift(n);
                for (n = t; n = n.parentNode;) c.unshift(n);
                for (; l[r] === c[r];) r++;
                return r ? o(l[r], c[r]) : l[r] === H ? -1 : c[r] === H ? 1 : 0
            }, i) : D
        }, t.matches = function(e, n) {
            return t(e, null, null, n)
        }, t.matchesSelector = function(e, n) {
            if ((e.ownerDocument || e) !== D && M(e), n = n.replace(ue, "='$1']"), !(!w.matchesSelector || !O || I && I.test(n) || j && j.test(n))) try {
                var i = U.call(e, n);
                if (i || w.disconnectedMatch || e.document && 11 !== e.document.nodeType) return i
            } catch (r) {}
            return t(n, D, null, [e]).length > 0
        }, t.contains = function(e, t) {
            return (e.ownerDocument || e) !== D && M(e), L(e, t)
        }, t.attr = function(e, t) {
            (e.ownerDocument || e) !== D && M(e);
            var n = C.attrHandle[t.toLowerCase()],
                i = n && G.call(C.attrHandle, t.toLowerCase()) ? n(e, t, !O) : void 0;
            return void 0 !== i ? i : w.attributes || !O ? e.getAttribute(t) : (i = e.getAttributeNode(t)) && i.specified ? i.value : null
        }, t.error = function(e) {
            throw new Error("Syntax error, unrecognized expression: " + e)
        }, t.uniqueSort = function(e) {
            var t, n = [],
                i = 0,
                r = 0;
            if (F = !w.detectDuplicates, S = !w.sortStable && e.slice(0), e.sort(W), F) {
                for (; t = e[r++];) t === e[r] && (i = n.push(r));
                for (; i--;) e.splice(n[i], 1)
            }
            return S = null, e
        }, $ = t.getText = function(e) {
            var t, n = "",
                i = 0,
                r = e.nodeType;
            if (r) {
                if (1 === r || 9 === r || 11 === r) {
                    if ("string" == typeof e.textContent) return e.textContent;
                    for (e = e.firstChild; e; e = e.nextSibling) n += $(e)
                } else if (3 === r || 4 === r) return e.nodeValue
            } else
                for (; t = e[i++];) n += $(t);
            return n
        }, C = t.selectors = {
            cacheLength: 50,
            createPseudo: i,
            match: he,
            attrHandle: {},
            find: {},
            relative: {
                ">": {
                    dir: "parentNode",
                    first: !0
                },
                " ": {
                    dir: "parentNode"
                },
                "+": {
                    dir: "previousSibling",
                    first: !0
                },
                "~": {
                    dir: "previousSibling"
                }
            },
            preFilter: {
                ATTR: function(e) {
                    return e[1] = e[1].replace(we, Ce), e[3] = (e[3] || e[4] || e[5] || "").replace(we, Ce), "~=" === e[2] && (e[3] = " " + e[3] + " "), e.slice(0, 4)
                },
                CHILD: function(e) {
                    return e[1] = e[1].toLowerCase(), "nth" === e[1].slice(0, 3) ? (e[3] || t.error(e[0]), e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && t.error(e[0]), e
                },
                PSEUDO: function(e) {
                    var t, n = !e[6] && e[2];
                    return he.CHILD.test(e[0]) ? null : (e[3] ? e[2] = e[4] || e[5] || "" : n && fe.test(n) && (t = T(n, !0)) && (t = n.indexOf(")", n.length - t) - n.length) && (e[0] = e[0].slice(0, t), e[2] = n.slice(0, t)), e.slice(0, 3))
                }
            },
            filter: {
                TAG: function(e) {
                    var t = e.replace(we, Ce).toLowerCase();
                    return "*" === e ? function() {
                        return !0
                    } : function(e) {
                        return e.nodeName && e.nodeName.toLowerCase() === t
                    }
                },
                CLASS: function(e) {
                    var t = R[e + " "];
                    return t || (t = new RegExp("(^|" + ne + ")" + e + "(" + ne + "|$)")) && R(e, function(e) {
                        return t.test("string" == typeof e.className && e.className || "undefined" != typeof e.getAttribute && e.getAttribute("class") || "")
                    })
                },
                ATTR: function(e, n, i) {
                    return function(r) {
                        var a = t.attr(r, e);
                        return null == a ? "!=" === n : !n || (a += "", "=" === n ? a === i : "!=" === n ? a !== i : "^=" === n ? i && 0 === a.indexOf(i) : "*=" === n ? i && a.indexOf(i) > -1 : "$=" === n ? i && a.slice(-i.length) === i : "~=" === n ? (" " + a.replace(se, " ") + " ").indexOf(i) > -1 : "|=" === n && (a === i || a.slice(0, i.length + 1) === i + "-"))
                    }
                },
                CHILD: function(e, t, n, i, r) {
                    var a = "nth" !== e.slice(0, 3),
                        o = "last" !== e.slice(-4),
                        s = "of-type" === t;
                    return 1 === i && 0 === r ? function(e) {
                        return !!e.parentNode
                    } : function(t, n, l) {
                        var c, d, u, f, p, h, m = a !== o ? "nextSibling" : "previousSibling",
                            g = t.parentNode,
                            v = s && t.nodeName.toLowerCase(),
                            y = !l && !s;
                        if (g) {
                            if (a) {
                                for (; m;) {
                                    for (u = t; u = u[m];)
                                        if (s ? u.nodeName.toLowerCase() === v : 1 === u.nodeType) return !1;
                                    h = m = "only" === e && !h && "nextSibling"
                                }
                                return !0
                            }
                            if (h = [o ? g.firstChild : g.lastChild], o && y) {
                                for (d = g[P] || (g[P] = {}), c = d[e] || [], p = c[0] === q && c[1], f = c[0] === q && c[2], u = p && g.childNodes[p]; u = ++p && u && u[m] || (f = p = 0) || h.pop();)
                                    if (1 === u.nodeType && ++f && u === t) {
                                        d[e] = [q, p, f];
                                        break
                                    }
                            } else if (y && (c = (t[P] || (t[P] = {}))[e]) && c[0] === q) f = c[1];
                            else
                                for (;
                                    (u = ++p && u && u[m] || (f = p = 0) || h.pop()) && ((s ? u.nodeName.toLowerCase() !== v : 1 !== u.nodeType) || !++f || (y && ((u[P] || (u[P] = {}))[e] = [q, f]), u !== t)););
                            return f -= r, f === i || f % i === 0 && f / i >= 0
                        }
                    }
                },
                PSEUDO: function(e, n) {
                    var r, a = C.pseudos[e] || C.setFilters[e.toLowerCase()] || t.error("unsupported pseudo: " + e);
                    return a[P] ? a(n) : a.length > 1 ? (r = [e, e, "", n], C.setFilters.hasOwnProperty(e.toLowerCase()) ? i(function(e, t) {
                        for (var i, r = a(e, n), o = r.length; o--;) i = ee(e, r[o]), e[i] = !(t[i] = r[o])
                    }) : function(e) {
                        return a(e, 0, r)
                    }) : a
                }
            },
            pseudos: {
                not: i(function(e) {
                    var t = [],
                        n = [],
                        r = _(e.replace(le, "$1"));
                    return r[P] ? i(function(e, t, n, i) {
                        for (var a, o = r(e, null, i, []), s = e.length; s--;)(a = o[s]) && (e[s] = !(t[s] = a))
                    }) : function(e, i, a) {
                        return t[0] = e, r(t, null, a, n), t[0] = null, !n.pop()
                    }
                }),
                has: i(function(e) {
                    return function(n) {
                        return t(e, n).length > 0
                    }
                }),
                contains: i(function(e) {
                    return e = e.replace(we, Ce),
                        function(t) {
                            return (t.textContent || t.innerText || $(t)).indexOf(e) > -1
                        }
                }),
                lang: i(function(e) {
                    return pe.test(e || "") || t.error("unsupported lang: " + e), e = e.replace(we, Ce).toLowerCase(),
                        function(t) {
                            var n;
                            do
                                if (n = O ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang")) return n = n.toLowerCase(), n === e || 0 === n.indexOf(e + "-");
                            while ((t = t.parentNode) && 1 === t.nodeType);
                            return !1
                        }
                }),
                target: function(t) {
                    var n = e.location && e.location.hash;
                    return n && n.slice(1) === t.id
                },
                root: function(e) {
                    return e === N
                },
                focus: function(e) {
                    return e === D.activeElement && (!D.hasFocus || D.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                },
                enabled: function(e) {
                    return e.disabled === !1
                },
                disabled: function(e) {
                    return e.disabled === !0
                },
                checked: function(e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && !!e.checked || "option" === t && !!e.selected
                },
                selected: function(e) {
                    return e.parentNode && e.parentNode.selectedIndex, e.selected === !0
                },
                empty: function(e) {
                    for (e = e.firstChild; e; e = e.nextSibling)
                        if (e.nodeType < 6) return !1;
                    return !0
                },
                parent: function(e) {
                    return !C.pseudos.empty(e)
                },
                header: function(e) {
                    return ge.test(e.nodeName)
                },
                input: function(e) {
                    return me.test(e.nodeName)
                },
                button: function(e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && "button" === e.type || "button" === t
                },
                text: function(e) {
                    var t;
                    return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || "text" === t.toLowerCase())
                },
                first: c(function() {
                    return [0]
                }),
                last: c(function(e, t) {
                    return [t - 1]
                }),
                eq: c(function(e, t, n) {
                    return [0 > n ? n + t : n]
                }),
                even: c(function(e, t) {
                    for (var n = 0; t > n; n += 2) e.push(n);
                    return e
                }),
                odd: c(function(e, t) {
                    for (var n = 1; t > n; n += 2) e.push(n);
                    return e
                }),
                lt: c(function(e, t, n) {
                    for (var i = 0 > n ? n + t : n; --i >= 0;) e.push(i);
                    return e
                }),
                gt: c(function(e, t, n) {
                    for (var i = 0 > n ? n + t : n; ++i < t;) e.push(i);
                    return e
                })
            }
        }, C.pseudos.nth = C.pseudos.eq;
        for (x in {
                radio: !0,
                checkbox: !0,
                file: !0,
                password: !0,
                image: !0
            }) C.pseudos[x] = s(x);
        for (x in {
                submit: !0,
                reset: !0
            }) C.pseudos[x] = l(x);
        return u.prototype = C.filters = C.pseudos, C.setFilters = new u, T = t.tokenize = function(e, n) {
            var i, r, a, o, s, l, c, d = B[e + " "];
            if (d) return n ? 0 : d.slice(0);
            for (s = e, l = [], c = C.preFilter; s;) {
                (!i || (r = ce.exec(s))) && (r && (s = s.slice(r[0].length) || s), l.push(a = [])), i = !1, (r = de.exec(s)) && (i = r.shift(), a.push({
                    value: i,
                    type: r[0].replace(le, " ")
                }), s = s.slice(i.length));
                for (o in C.filter) !(r = he[o].exec(s)) || c[o] && !(r = c[o](r)) || (i = r.shift(), a.push({
                    value: i,
                    type: o,
                    matches: r
                }), s = s.slice(i.length));
                if (!i) break
            }
            return n ? s.length : s ? t.error(e) : B(e, l).slice(0)
        }, _ = t.compile = function(e, t) {
            var n, i = [],
                r = [],
                a = z[e + " "];
            if (!a) {
                for (t || (t = T(e)), n = t.length; n--;) a = y(t[n]), a[P] ? i.push(a) : r.push(a);
                a = z(e, b(r, i)), a.selector = e
            }
            return a
        }, A = t.select = function(e, t, n, i) {
            var r, a, o, s, l, c = "function" == typeof e && e,
                u = !i && T(e = c.selector || e);
            if (n = n || [], 1 === u.length) {
                if (a = u[0] = u[0].slice(0), a.length > 2 && "ID" === (o = a[0]).type && w.getById && 9 === t.nodeType && O && C.relative[a[1].type]) {
                    if (t = (C.find.ID(o.matches[0].replace(we, Ce), t) || [])[0], !t) return n;
                    c && (t = t.parentNode), e = e.slice(a.shift().value.length)
                }
                for (r = he.needsContext.test(e) ? 0 : a.length; r-- && (o = a[r], !C.relative[s = o.type]);)
                    if ((l = C.find[s]) && (i = l(o.matches[0].replace(we, Ce), be.test(a[0].type) && d(t.parentNode) || t))) {
                        if (a.splice(r, 1), e = i.length && f(a), !e) return Z.apply(n, i), n;
                        break
                    }
            }
            return (c || _(e, u))(i, t, !O, n, be.test(e) && d(t.parentNode) || t), n
        }, w.sortStable = P.split("").sort(W).join("") === P, w.detectDuplicates = !!F, M(), w.sortDetached = r(function(e) {
            return 1 & e.compareDocumentPosition(D.createElement("div"))
        }), r(function(e) {
            return e.innerHTML = "<a href='#'></a>", "#" === e.firstChild.getAttribute("href")
        }) || a("type|href|height|width", function(e, t, n) {
            return n ? void 0 : e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
        }), w.attributes && r(function(e) {
            return e.innerHTML = "<input/>", e.firstChild.setAttribute("value", ""), "" === e.firstChild.getAttribute("value")
        }) || a("value", function(e, t, n) {
            return n || "input" !== e.nodeName.toLowerCase() ? void 0 : e.defaultValue
        }), r(function(e) {
            return null == e.getAttribute("disabled")
        }) || a(te, function(e, t, n) {
            var i;
            return n ? void 0 : e[t] === !0 ? t.toLowerCase() : (i = e.getAttributeNode(t)) && i.specified ? i.value : null
        }), t
    }(e);
    J.find = re, J.expr = re.selectors, J.expr[":"] = J.expr.pseudos, J.unique = re.uniqueSort, J.text = re.getText, J.isXMLDoc = re.isXML, J.contains = re.contains;
    var ae = J.expr.match.needsContext,
        oe = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
        se = /^.[^:#\[\.,]*$/;
    J.filter = function(e, t, n) {
        var i = t[0];
        return n && (e = ":not(" + e + ")"), 1 === t.length && 1 === i.nodeType ? J.find.matchesSelector(i, e) ? [i] : [] : J.find.matches(e, J.grep(t, function(e) {
            return 1 === e.nodeType
        }))
    }, J.fn.extend({
        find: function(e) {
            var t, n = this.length,
                i = [],
                r = this;
            if ("string" != typeof e) return this.pushStack(J(e).filter(function() {
                for (t = 0; n > t; t++)
                    if (J.contains(r[t], this)) return !0
            }));
            for (t = 0; n > t; t++) J.find(e, r[t], i);
            return i = this.pushStack(n > 1 ? J.unique(i) : i), i.selector = this.selector ? this.selector + " " + e : e, i
        },
        filter: function(e) {
            return this.pushStack(i(this, e || [], !1))
        },
        not: function(e) {
            return this.pushStack(i(this, e || [], !0))
        },
        is: function(e) {
            return !!i(this, "string" == typeof e && ae.test(e) ? J(e) : e || [], !1).length
        }
    });
    var le, ce = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,
        de = J.fn.init = function(e, t) {
            var n, i;
            if (!e) return this;
            if ("string" == typeof e) {
                if (n = "<" === e[0] && ">" === e[e.length - 1] && e.length >= 3 ? [null, e, null] : ce.exec(e), !n || !n[1] && t) return !t || t.jquery ? (t || le).find(e) : this.constructor(t).find(e);
                if (n[1]) {
                    if (t = t instanceof J ? t[0] : t, J.merge(this, J.parseHTML(n[1], t && t.nodeType ? t.ownerDocument || t : Q, !0)), oe.test(n[1]) && J.isPlainObject(t))
                        for (n in t) J.isFunction(this[n]) ? this[n](t[n]) : this.attr(n, t[n]);
                    return this
                }
                return i = Q.getElementById(n[2]), i && i.parentNode && (this.length = 1, this[0] = i), this.context = Q, this.selector = e, this
            }
            return e.nodeType ? (this.context = this[0] = e, this.length = 1, this) : J.isFunction(e) ? "undefined" != typeof le.ready ? le.ready(e) : e(J) : (void 0 !== e.selector && (this.selector = e.selector, this.context = e.context), J.makeArray(e, this))
        };
    de.prototype = J.fn, le = J(Q);
    var ue = /^(?:parents|prev(?:Until|All))/,
        fe = {
            children: !0,
            contents: !0,
            next: !0,
            prev: !0
        };
    J.extend({
        dir: function(e, t, n) {
            for (var i = [], r = void 0 !== n;
                (e = e[t]) && 9 !== e.nodeType;)
                if (1 === e.nodeType) {
                    if (r && J(e).is(n)) break;
                    i.push(e)
                }
            return i
        },
        sibling: function(e, t) {
            for (var n = []; e; e = e.nextSibling) 1 === e.nodeType && e !== t && n.push(e);
            return n
        }
    }), J.fn.extend({
        has: function(e) {
            var t = J(e, this),
                n = t.length;
            return this.filter(function() {
                for (var e = 0; n > e; e++)
                    if (J.contains(this, t[e])) return !0
            })
        },
        closest: function(e, t) {
            for (var n, i = 0, r = this.length, a = [], o = ae.test(e) || "string" != typeof e ? J(e, t || this.context) : 0; r > i; i++)
                for (n = this[i]; n && n !== t; n = n.parentNode)
                    if (n.nodeType < 11 && (o ? o.index(n) > -1 : 1 === n.nodeType && J.find.matchesSelector(n, e))) {
                        a.push(n);
                        break
                    }
            return this.pushStack(a.length > 1 ? J.unique(a) : a)
        },
        index: function(e) {
            return e ? "string" == typeof e ? W.call(J(e), this[0]) : W.call(this, e.jquery ? e[0] : e) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        },
        add: function(e, t) {
            return this.pushStack(J.unique(J.merge(this.get(), J(e, t))))
        },
        addBack: function(e) {
            return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
        }
    }), J.each({
        parent: function(e) {
            var t = e.parentNode;
            return t && 11 !== t.nodeType ? t : null
        },
        parents: function(e) {
            return J.dir(e, "parentNode")
        },
        parentsUntil: function(e, t, n) {
            return J.dir(e, "parentNode", n)
        },
        next: function(e) {
            return r(e, "nextSibling")
        },
        prev: function(e) {
            return r(e, "previousSibling")
        },
        nextAll: function(e) {
            return J.dir(e, "nextSibling")
        },
        prevAll: function(e) {
            return J.dir(e, "previousSibling")
        },
        nextUntil: function(e, t, n) {
            return J.dir(e, "nextSibling", n)
        },
        prevUntil: function(e, t, n) {
            return J.dir(e, "previousSibling", n)
        },
        siblings: function(e) {
            return J.sibling((e.parentNode || {}).firstChild, e)
        },
        children: function(e) {
            return J.sibling(e.firstChild)
        },
        contents: function(e) {
            return e.contentDocument || J.merge([], e.childNodes)
        }
    }, function(e, t) {
        J.fn[e] = function(n, i) {
            var r = J.map(this, t, n);
            return "Until" !== e.slice(-5) && (i = n), i && "string" == typeof i && (r = J.filter(i, r)), this.length > 1 && (fe[e] || J.unique(r), ue.test(e) && r.reverse()), this.pushStack(r)
        }
    });
    var pe = /\S+/g,
        he = {};
    J.Callbacks = function(e) {
        e = "string" == typeof e ? he[e] || a(e) : J.extend({}, e);
        var t, n, i, r, o, s, l = [],
            c = !e.once && [],
            d = function(a) {
                for (t = e.memory && a, n = !0, s = r || 0, r = 0, o = l.length, i = !0; l && o > s; s++)
                    if (l[s].apply(a[0], a[1]) === !1 && e.stopOnFalse) {
                        t = !1;
                        break
                    }
                i = !1, l && (c ? c.length && d(c.shift()) : t ? l = [] : u.disable())
            },
            u = {
                add: function() {
                    if (l) {
                        var n = l.length;
                        ! function a(t) {
                            J.each(t, function(t, n) {
                                var i = J.type(n);
                                "function" === i ? e.unique && u.has(n) || l.push(n) : n && n.length && "string" !== i && a(n)
                            })
                        }(arguments), i ? o = l.length : t && (r = n, d(t))
                    }
                    return this
                },
                remove: function() {
                    return l && J.each(arguments, function(e, t) {
                        for (var n;
                            (n = J.inArray(t, l, n)) > -1;) l.splice(n, 1), i && (o >= n && o--, s >= n && s--)
                    }), this
                },
                has: function(e) {
                    return e ? J.inArray(e, l) > -1 : !(!l || !l.length)
                },
                empty: function() {
                    return l = [], o = 0, this
                },
                disable: function() {
                    return l = c = t = void 0, this
                },
                disabled: function() {
                    return !l
                },
                lock: function() {
                    return c = void 0, t || u.disable(), this
                },
                locked: function() {
                    return !c
                },
                fireWith: function(e, t) {
                    return !l || n && !c || (t = t || [], t = [e, t.slice ? t.slice() : t], i ? c.push(t) : d(t)), this
                },
                fire: function() {
                    return u.fireWith(this, arguments), this
                },
                fired: function() {
                    return !!n
                }
            };
        return u
    }, J.extend({
        Deferred: function(e) {
            var t = [
                    ["resolve", "done", J.Callbacks("once memory"), "resolved"],
                    ["reject", "fail", J.Callbacks("once memory"), "rejected"],
                    ["notify", "progress", J.Callbacks("memory")]
                ],
                n = "pending",
                i = {
                    state: function() {
                        return n
                    },
                    always: function() {
                        return r.done(arguments).fail(arguments), this
                    },
                    then: function() {
                        var e = arguments;
                        return J.Deferred(function(n) {
                            J.each(t, function(t, a) {
                                var o = J.isFunction(e[t]) && e[t];
                                r[a[1]](function() {
                                    var e = o && o.apply(this, arguments);
                                    e && J.isFunction(e.promise) ? e.promise().done(n.resolve).fail(n.reject).progress(n.notify) : n[a[0] + "With"](this === i ? n.promise() : this, o ? [e] : arguments)
                                })
                            }), e = null
                        }).promise()
                    },
                    promise: function(e) {
                        return null != e ? J.extend(e, i) : i
                    }
                },
                r = {};
            return i.pipe = i.then, J.each(t, function(e, a) {
                var o = a[2],
                    s = a[3];
                i[a[1]] = o.add, s && o.add(function() {
                    n = s
                }, t[1 ^ e][2].disable, t[2][2].lock), r[a[0]] = function() {
                    return r[a[0] + "With"](this === r ? i : this, arguments), this
                }, r[a[0] + "With"] = o.fireWith
            }), i.promise(r), e && e.call(r, r), r
        },
        when: function(e) {
            var t, n, i, r = 0,
                a = R.call(arguments),
                o = a.length,
                s = 1 !== o || e && J.isFunction(e.promise) ? o : 0,
                l = 1 === s ? e : J.Deferred(),
                c = function(e, n, i) {
                    return function(r) {
                        n[e] = this, i[e] = arguments.length > 1 ? R.call(arguments) : r, i === t ? l.notifyWith(n, i) : --s || l.resolveWith(n, i)
                    }
                };
            if (o > 1)
                for (t = new Array(o), n = new Array(o), i = new Array(o); o > r; r++) a[r] && J.isFunction(a[r].promise) ? a[r].promise().done(c(r, i, a)).fail(l.reject).progress(c(r, n, t)) : --s;
            return s || l.resolveWith(i, a), l.promise()
        }
    });
    var me;
    J.fn.ready = function(e) {
        return J.ready.promise().done(e), this
    }, J.extend({
        isReady: !1,
        readyWait: 1,
        holdReady: function(e) {
            e ? J.readyWait++ : J.ready(!0)
        },
        ready: function(e) {
            (e === !0 ? --J.readyWait : J.isReady) || (J.isReady = !0, e !== !0 && --J.readyWait > 0 || (me.resolveWith(Q, [J]), J.fn.triggerHandler && (J(Q).triggerHandler("ready"), J(Q).off("ready"))))
        }
    }), J.ready.promise = function(t) {
        return me || (me = J.Deferred(), "complete" === Q.readyState ? setTimeout(J.ready) : (Q.addEventListener("DOMContentLoaded", o, !1), e.addEventListener("load", o, !1))), me.promise(t)
    }, J.ready.promise();
    var ge = J.access = function(e, t, n, i, r, a, o) {
        var s = 0,
            l = e.length,
            c = null == n;
        if ("object" === J.type(n)) {
            r = !0;
            for (s in n) J.access(e, t, s, n[s], !0, a, o)
        } else if (void 0 !== i && (r = !0, J.isFunction(i) || (o = !0), c && (o ? (t.call(e, i), t = null) : (c = t, t = function(e, t, n) {
                return c.call(J(e), n)
            })), t))
            for (; l > s; s++) t(e[s], n, o ? i : i.call(e[s], s, t(e[s], n)));
        return r ? e : c ? t.call(e) : l ? t(e[0], n) : a
    };
    J.acceptData = function(e) {
        return 1 === e.nodeType || 9 === e.nodeType || !+e.nodeType
    }, s.uid = 1, s.accepts = J.acceptData, s.prototype = {
        key: function(e) {
            if (!s.accepts(e)) return 0;
            var t = {},
                n = e[this.expando];
            if (!n) {
                n = s.uid++;
                try {
                    t[this.expando] = {
                        value: n
                    }, Object.defineProperties(e, t)
                } catch (i) {
                    t[this.expando] = n, J.extend(e, t)
                }
            }
            return this.cache[n] || (this.cache[n] = {}), n
        },
        set: function(e, t, n) {
            var i, r = this.key(e),
                a = this.cache[r];
            if ("string" == typeof t) a[t] = n;
            else if (J.isEmptyObject(a)) J.extend(this.cache[r], t);
            else
                for (i in t) a[i] = t[i];
            return a
        },
        get: function(e, t) {
            var n = this.cache[this.key(e)];
            return void 0 === t ? n : n[t]
        },
        access: function(e, t, n) {
            var i;
            return void 0 === t || t && "string" == typeof t && void 0 === n ? (i = this.get(e, t), void 0 !== i ? i : this.get(e, J.camelCase(t))) : (this.set(e, t, n), void 0 !== n ? n : t)
        },
        remove: function(e, t) {
            var n, i, r, a = this.key(e),
                o = this.cache[a];
            if (void 0 === t) this.cache[a] = {};
            else {
                J.isArray(t) ? i = t.concat(t.map(J.camelCase)) : (r = J.camelCase(t), t in o ? i = [t, r] : (i = r, i = i in o ? [i] : i.match(pe) || [])), n = i.length;
                for (; n--;) delete o[i[n]]
            }
        },
        hasData: function(e) {
            return !J.isEmptyObject(this.cache[e[this.expando]] || {})
        },
        discard: function(e) {
            e[this.expando] && delete this.cache[e[this.expando]]
        }
    };
    var ve = new s,
        ye = new s,
        be = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
        xe = /([A-Z])/g;
    J.extend({
        hasData: function(e) {
            return ye.hasData(e) || ve.hasData(e)
        },
        data: function(e, t, n) {
            return ye.access(e, t, n)
        },
        removeData: function(e, t) {
            ye.remove(e, t)
        },
        _data: function(e, t, n) {
            return ve.access(e, t, n)
        },
        _removeData: function(e, t) {
            ve.remove(e, t)
        }
    }), J.fn.extend({
        data: function(e, t) {
            var n, i, r, a = this[0],
                o = a && a.attributes;
            if (void 0 === e) {
                if (this.length && (r = ye.get(a), 1 === a.nodeType && !ve.get(a, "hasDataAttrs"))) {
                    for (n = o.length; n--;) o[n] && (i = o[n].name, 0 === i.indexOf("data-") && (i = J.camelCase(i.slice(5)), l(a, i, r[i])));
                    ve.set(a, "hasDataAttrs", !0)
                }
                return r
            }
            return "object" == typeof e ? this.each(function() {
                ye.set(this, e)
            }) : ge(this, function(t) {
                var n, i = J.camelCase(e);
                if (a && void 0 === t) {
                    if (n = ye.get(a, e), void 0 !== n) return n;
                    if (n = ye.get(a, i), void 0 !== n) return n;
                    if (n = l(a, i, void 0), void 0 !== n) return n
                } else this.each(function() {
                    var n = ye.get(this, i);
                    ye.set(this, i, t), -1 !== e.indexOf("-") && void 0 !== n && ye.set(this, e, t)
                })
            }, null, t, arguments.length > 1, null, !0)
        },
        removeData: function(e) {
            return this.each(function() {
                ye.remove(this, e)
            })
        }
    }), J.extend({
        queue: function(e, t, n) {
            var i;
            return e ? (t = (t || "fx") + "queue", i = ve.get(e, t), n && (!i || J.isArray(n) ? i = ve.access(e, t, J.makeArray(n)) : i.push(n)), i || []) : void 0
        },
        dequeue: function(e, t) {
            t = t || "fx";
            var n = J.queue(e, t),
                i = n.length,
                r = n.shift(),
                a = J._queueHooks(e, t),
                o = function() {
                    J.dequeue(e, t)
                };
            "inprogress" === r && (r = n.shift(), i--), r && ("fx" === t && n.unshift("inprogress"), delete a.stop, r.call(e, o, a)), !i && a && a.empty.fire()
        },
        _queueHooks: function(e, t) {
            var n = t + "queueHooks";
            return ve.get(e, n) || ve.access(e, n, {
                empty: J.Callbacks("once memory").add(function() {
                    ve.remove(e, [t + "queue", n])
                })
            })
        }
    }), J.fn.extend({
        queue: function(e, t) {
            var n = 2;
            return "string" != typeof e && (t = e, e = "fx", n--), arguments.length < n ? J.queue(this[0], e) : void 0 === t ? this : this.each(function() {
                var n = J.queue(this, e, t);
                J._queueHooks(this, e), "fx" === e && "inprogress" !== n[0] && J.dequeue(this, e)
            })
        },
        dequeue: function(e) {
            return this.each(function() {
                J.dequeue(this, e)
            })
        },
        clearQueue: function(e) {
            return this.queue(e || "fx", [])
        },
        promise: function(e, t) {
            var n, i = 1,
                r = J.Deferred(),
                a = this,
                o = this.length,
                s = function() {
                    --i || r.resolveWith(a, [a])
                };
            for ("string" != typeof e && (t = e, e = void 0), e = e || "fx"; o--;) n = ve.get(a[o], e + "queueHooks"), n && n.empty && (i++, n.empty.add(s));
            return s(), r.promise(t)
        }
    });
    var we = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
        Ce = ["Top", "Right", "Bottom", "Left"],
        $e = function(e, t) {
            return e = t || e, "none" === J.css(e, "display") || !J.contains(e.ownerDocument, e)
        },
        ke = /^(?:checkbox|radio)$/i;
    ! function() {
        var e = Q.createDocumentFragment(),
            t = e.appendChild(Q.createElement("div")),
            n = Q.createElement("input");
        n.setAttribute("type", "radio"), n.setAttribute("checked", "checked"), n.setAttribute("name", "t"), t.appendChild(n), Y.checkClone = t.cloneNode(!0).cloneNode(!0).lastChild.checked, t.innerHTML = "<textarea>x</textarea>", Y.noCloneChecked = !!t.cloneNode(!0).lastChild.defaultValue
    }();
    var Te = "undefined";
    Y.focusinBubbles = "onfocusin" in e;
    var _e = /^key/,
        Ae = /^(?:mouse|pointer|contextmenu)|click/,
        Ee = /^(?:focusinfocus|focusoutblur)$/,
        Se = /^([^.]*)(?:\.(.+)|)$/;
    J.event = {
        global: {},
        add: function(e, t, n, i, r) {
            var a, o, s, l, c, d, u, f, p, h, m, g = ve.get(e);
            if (g)
                for (n.handler && (a = n, n = a.handler, r = a.selector), n.guid || (n.guid = J.guid++), (l = g.events) || (l = g.events = {}), (o = g.handle) || (o = g.handle = function(t) {
                        return typeof J !== Te && J.event.triggered !== t.type ? J.event.dispatch.apply(e, arguments) : void 0
                    }), t = (t || "").match(pe) || [""], c = t.length; c--;) s = Se.exec(t[c]) || [], p = m = s[1], h = (s[2] || "").split(".").sort(), p && (u = J.event.special[p] || {}, p = (r ? u.delegateType : u.bindType) || p, u = J.event.special[p] || {}, d = J.extend({
                    type: p,
                    origType: m,
                    data: i,
                    handler: n,
                    guid: n.guid,
                    selector: r,
                    needsContext: r && J.expr.match.needsContext.test(r),
                    namespace: h.join(".")
                }, a), (f = l[p]) || (f = l[p] = [], f.delegateCount = 0, u.setup && u.setup.call(e, i, h, o) !== !1 || e.addEventListener && e.addEventListener(p, o, !1)), u.add && (u.add.call(e, d), d.handler.guid || (d.handler.guid = n.guid)), r ? f.splice(f.delegateCount++, 0, d) : f.push(d), J.event.global[p] = !0)
        },
        remove: function(e, t, n, i, r) {
            var a, o, s, l, c, d, u, f, p, h, m, g = ve.hasData(e) && ve.get(e);
            if (g && (l = g.events)) {
                for (t = (t || "").match(pe) || [""], c = t.length; c--;)
                    if (s = Se.exec(t[c]) || [], p = m = s[1], h = (s[2] || "").split(".").sort(), p) {
                        for (u = J.event.special[p] || {}, p = (i ? u.delegateType : u.bindType) || p, f = l[p] || [], s = s[2] && new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)"), o = a = f.length; a--;) d = f[a], !r && m !== d.origType || n && n.guid !== d.guid || s && !s.test(d.namespace) || i && i !== d.selector && ("**" !== i || !d.selector) || (f.splice(a, 1), d.selector && f.delegateCount--, u.remove && u.remove.call(e, d));
                        o && !f.length && (u.teardown && u.teardown.call(e, h, g.handle) !== !1 || J.removeEvent(e, p, g.handle), delete l[p])
                    } else
                        for (p in l) J.event.remove(e, p + t[c], n, i, !0);
                J.isEmptyObject(l) && (delete g.handle, ve.remove(e, "events"))
            }
        },
        trigger: function(t, n, i, r) {
            var a, o, s, l, c, d, u, f = [i || Q],
                p = X.call(t, "type") ? t.type : t,
                h = X.call(t, "namespace") ? t.namespace.split(".") : [];
            if (o = s = i = i || Q, 3 !== i.nodeType && 8 !== i.nodeType && !Ee.test(p + J.event.triggered) && (p.indexOf(".") >= 0 && (h = p.split("."), p = h.shift(), h.sort()), c = p.indexOf(":") < 0 && "on" + p, t = t[J.expando] ? t : new J.Event(p, "object" == typeof t && t), t.isTrigger = r ? 2 : 3, t.namespace = h.join("."), t.namespace_re = t.namespace ? new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, t.result = void 0, t.target || (t.target = i), n = null == n ? [t] : J.makeArray(n, [t]), u = J.event.special[p] || {}, r || !u.trigger || u.trigger.apply(i, n) !== !1)) {
                if (!r && !u.noBubble && !J.isWindow(i)) {
                    for (l = u.delegateType || p, Ee.test(l + p) || (o = o.parentNode); o; o = o.parentNode) f.push(o), s = o;
                    s === (i.ownerDocument || Q) && f.push(s.defaultView || s.parentWindow || e)
                }
                for (a = 0;
                    (o = f[a++]) && !t.isPropagationStopped();) t.type = a > 1 ? l : u.bindType || p, d = (ve.get(o, "events") || {})[t.type] && ve.get(o, "handle"), d && d.apply(o, n), d = c && o[c], d && d.apply && J.acceptData(o) && (t.result = d.apply(o, n), t.result === !1 && t.preventDefault());
                return t.type = p, r || t.isDefaultPrevented() || u._default && u._default.apply(f.pop(), n) !== !1 || !J.acceptData(i) || c && J.isFunction(i[p]) && !J.isWindow(i) && (s = i[c], s && (i[c] = null), J.event.triggered = p, i[p](), J.event.triggered = void 0, s && (i[c] = s)), t.result
            }
        },
        dispatch: function(e) {
            e = J.event.fix(e);
            var t, n, i, r, a, o = [],
                s = R.call(arguments),
                l = (ve.get(this, "events") || {})[e.type] || [],
                c = J.event.special[e.type] || {};
            if (s[0] = e, e.delegateTarget = this, !c.preDispatch || c.preDispatch.call(this, e) !== !1) {
                for (o = J.event.handlers.call(this, e, l), t = 0;
                    (r = o[t++]) && !e.isPropagationStopped();)
                    for (e.currentTarget = r.elem, n = 0;
                        (a = r.handlers[n++]) && !e.isImmediatePropagationStopped();)(!e.namespace_re || e.namespace_re.test(a.namespace)) && (e.handleObj = a, e.data = a.data, i = ((J.event.special[a.origType] || {}).handle || a.handler).apply(r.elem, s), void 0 !== i && (e.result = i) === !1 && (e.preventDefault(), e.stopPropagation()));
                return c.postDispatch && c.postDispatch.call(this, e), e.result
            }
        },
        handlers: function(e, t) {
            var n, i, r, a, o = [],
                s = t.delegateCount,
                l = e.target;
            if (s && l.nodeType && (!e.button || "click" !== e.type))
                for (; l !== this; l = l.parentNode || this)
                    if (l.disabled !== !0 || "click" !== e.type) {
                        for (i = [], n = 0; s > n; n++) a = t[n], r = a.selector + " ", void 0 === i[r] && (i[r] = a.needsContext ? J(r, this).index(l) >= 0 : J.find(r, this, null, [l]).length), i[r] && i.push(a);
                        i.length && o.push({
                            elem: l,
                            handlers: i
                        })
                    }
            return s < t.length && o.push({
                elem: this,
                handlers: t.slice(s)
            }), o
        },
        props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "),
            filter: function(e, t) {
                return null == e.which && (e.which = null != t.charCode ? t.charCode : t.keyCode), e
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function(e, t) {
                var n, i, r, a = t.button;
                return null == e.pageX && null != t.clientX && (n = e.target.ownerDocument || Q, i = n.documentElement, r = n.body, e.pageX = t.clientX + (i && i.scrollLeft || r && r.scrollLeft || 0) - (i && i.clientLeft || r && r.clientLeft || 0), e.pageY = t.clientY + (i && i.scrollTop || r && r.scrollTop || 0) - (i && i.clientTop || r && r.clientTop || 0)), e.which || void 0 === a || (e.which = 1 & a ? 1 : 2 & a ? 3 : 4 & a ? 2 : 0), e
            }
        },
        fix: function(e) {
            if (e[J.expando]) return e;
            var t, n, i, r = e.type,
                a = e,
                o = this.fixHooks[r];
            for (o || (this.fixHooks[r] = o = Ae.test(r) ? this.mouseHooks : _e.test(r) ? this.keyHooks : {}), i = o.props ? this.props.concat(o.props) : this.props, e = new J.Event(a), t = i.length; t--;) n = i[t], e[n] = a[n];
            return e.target || (e.target = Q), 3 === e.target.nodeType && (e.target = e.target.parentNode), o.filter ? o.filter(e, a) : e
        },
        special: {
            load: {
                noBubble: !0
            },
            focus: {
                trigger: function() {
                    return this !== u() && this.focus ? (this.focus(), !1) : void 0
                },
                delegateType: "focusin"
            },
            blur: {
                trigger: function() {
                    return this === u() && this.blur ? (this.blur(), !1) : void 0
                },
                delegateType: "focusout"
            },
            click: {
                trigger: function() {
                    return "checkbox" === this.type && this.click && J.nodeName(this, "input") ? (this.click(), !1) : void 0
                },
                _default: function(e) {
                    return J.nodeName(e.target, "a")
                }
            },
            beforeunload: {
                postDispatch: function(e) {
                    void 0 !== e.result && e.originalEvent && (e.originalEvent.returnValue = e.result)
                }
            }
        },
        simulate: function(e, t, n, i) {
            var r = J.extend(new J.Event, n, {
                type: e,
                isSimulated: !0,
                originalEvent: {}
            });
            i ? J.event.trigger(r, null, t) : J.event.dispatch.call(t, r), r.isDefaultPrevented() && n.preventDefault()
        }
    }, J.removeEvent = function(e, t, n) {
        e.removeEventListener && e.removeEventListener(t, n, !1)
    }, J.Event = function(e, t) {
        return this instanceof J.Event ? (e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || void 0 === e.defaultPrevented && e.returnValue === !1 ? c : d) : this.type = e, t && J.extend(this, t), this.timeStamp = e && e.timeStamp || J.now(), void(this[J.expando] = !0)) : new J.Event(e, t)
    }, J.Event.prototype = {
        isDefaultPrevented: d,
        isPropagationStopped: d,
        isImmediatePropagationStopped: d,
        preventDefault: function() {
            var e = this.originalEvent;
            this.isDefaultPrevented = c, e && e.preventDefault && e.preventDefault()
        },
        stopPropagation: function() {
            var e = this.originalEvent;
            this.isPropagationStopped = c, e && e.stopPropagation && e.stopPropagation()
        },
        stopImmediatePropagation: function() {
            var e = this.originalEvent;
            this.isImmediatePropagationStopped = c, e && e.stopImmediatePropagation && e.stopImmediatePropagation(), this.stopPropagation()
        }
    }, J.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout",
        pointerenter: "pointerover",
        pointerleave: "pointerout"
    }, function(e, t) {
        J.event.special[e] = {
            delegateType: t,
            bindType: t,
            handle: function(e) {
                var n, i = this,
                    r = e.relatedTarget,
                    a = e.handleObj;
                return (!r || r !== i && !J.contains(i, r)) && (e.type = a.origType, n = a.handler.apply(this, arguments), e.type = t), n
            }
        }
    }), Y.focusinBubbles || J.each({
        focus: "focusin",
        blur: "focusout"
    }, function(e, t) {
        var n = function(e) {
            J.event.simulate(t, e.target, J.event.fix(e), !0)
        };
        J.event.special[t] = {
            setup: function() {
                var i = this.ownerDocument || this,
                    r = ve.access(i, t);
                r || i.addEventListener(e, n, !0), ve.access(i, t, (r || 0) + 1)
            },
            teardown: function() {
                var i = this.ownerDocument || this,
                    r = ve.access(i, t) - 1;
                r ? ve.access(i, t, r) : (i.removeEventListener(e, n, !0), ve.remove(i, t))
            }
        }
    }), J.fn.extend({
        on: function(e, t, n, i, r) {
            var a, o;
            if ("object" == typeof e) {
                "string" != typeof t && (n = n || t, t = void 0);
                for (o in e) this.on(o, t, n, e[o], r);
                return this
            }
            if (null == n && null == i ? (i = t, n = t = void 0) : null == i && ("string" == typeof t ? (i = n, n = void 0) : (i = n, n = t, t = void 0)), i === !1) i = d;
            else if (!i) return this;
            return 1 === r && (a = i, i = function(e) {
                return J().off(e), a.apply(this, arguments)
            }, i.guid = a.guid || (a.guid = J.guid++)), this.each(function() {
                J.event.add(this, e, i, n, t)
            })
        },
        one: function(e, t, n, i) {
            return this.on(e, t, n, i, 1)
        },
        off: function(e, t, n) {
            var i, r;
            if (e && e.preventDefault && e.handleObj) return i = e.handleObj, J(e.delegateTarget).off(i.namespace ? i.origType + "." + i.namespace : i.origType, i.selector, i.handler), this;
            if ("object" == typeof e) {
                for (r in e) this.off(r, t, e[r]);
                return this
            }
            return (t === !1 || "function" == typeof t) && (n = t, t = void 0), n === !1 && (n = d), this.each(function() {
                J.event.remove(this, e, n, t)
            })
        },
        trigger: function(e, t) {
            return this.each(function() {
                J.event.trigger(e, t, this)
            })
        },
        triggerHandler: function(e, t) {
            var n = this[0];
            return n ? J.event.trigger(e, t, n, !0) : void 0
        }
    });
    var Fe = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
        Me = /<([\w:]+)/,
        De = /<|&#?\w+;/,
        Ne = /<(?:script|style|link)/i,
        Oe = /checked\s*(?:[^=]|=\s*.checked.)/i,
        je = /^$|\/(?:java|ecma)script/i,
        Ie = /^true\/(.*)/,
        Ue = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,
        Le = {
            option: [1, "<select multiple='multiple'>", "</select>"],
            thead: [1, "<table>", "</table>"],
            col: [2, "<table><colgroup>", "</colgroup></table>"],
            tr: [2, "<table><tbody>", "</tbody></table>"],
            td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
            _default: [0, "", ""]
        };
    Le.optgroup = Le.option, Le.tbody = Le.tfoot = Le.colgroup = Le.caption = Le.thead, Le.th = Le.td, J.extend({
        clone: function(e, t, n) {
            var i, r, a, o, s = e.cloneNode(!0),
                l = J.contains(e.ownerDocument, e);
            if (!(Y.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || J.isXMLDoc(e)))
                for (o = v(s), a = v(e), i = 0, r = a.length; r > i; i++) y(a[i], o[i]);
            if (t)
                if (n)
                    for (a = a || v(e), o = o || v(s), i = 0, r = a.length; r > i; i++) g(a[i], o[i]);
                else g(e, s);
            return o = v(s, "script"), o.length > 0 && m(o, !l && v(e, "script")), s
        },
        buildFragment: function(e, t, n, i) {
            for (var r, a, o, s, l, c, d = t.createDocumentFragment(), u = [], f = 0, p = e.length; p > f; f++)
                if (r = e[f], r || 0 === r)
                    if ("object" === J.type(r)) J.merge(u, r.nodeType ? [r] : r);
                    else if (De.test(r)) {
                for (a = a || d.appendChild(t.createElement("div")), o = (Me.exec(r) || ["", ""])[1].toLowerCase(), s = Le[o] || Le._default, a.innerHTML = s[1] + r.replace(Fe, "<$1></$2>") + s[2], c = s[0]; c--;) a = a.lastChild;
                J.merge(u, a.childNodes), a = d.firstChild, a.textContent = ""
            } else u.push(t.createTextNode(r));
            for (d.textContent = "", f = 0; r = u[f++];)
                if ((!i || -1 === J.inArray(r, i)) && (l = J.contains(r.ownerDocument, r), a = v(d.appendChild(r), "script"), l && m(a), n))
                    for (c = 0; r = a[c++];) je.test(r.type || "") && n.push(r);
            return d
        },
        cleanData: function(e) {
            for (var t, n, i, r, a = J.event.special, o = 0; void 0 !== (n = e[o]); o++) {
                if (J.acceptData(n) && (r = n[ve.expando], r && (t = ve.cache[r]))) {
                    if (t.events)
                        for (i in t.events) a[i] ? J.event.remove(n, i) : J.removeEvent(n, i, t.handle);
                    ve.cache[r] && delete ve.cache[r]
                }
                delete ye.cache[n[ye.expando]]
            }
        }
    }), J.fn.extend({
        text: function(e) {
            return ge(this, function(e) {
                return void 0 === e ? J.text(this) : this.empty().each(function() {
                    (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) && (this.textContent = e)
                })
            }, null, e, arguments.length)
        },
        append: function() {
            return this.domManip(arguments, function(e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = f(this, e);
                    t.appendChild(e)
                }
            })
        },
        prepend: function() {
            return this.domManip(arguments, function(e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = f(this, e);
                    t.insertBefore(e, t.firstChild)
                }
            })
        },
        before: function() {
            return this.domManip(arguments, function(e) {
                this.parentNode && this.parentNode.insertBefore(e, this)
            })
        },
        after: function() {
            return this.domManip(arguments, function(e) {
                this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
            })
        },
        remove: function(e, t) {
            for (var n, i = e ? J.filter(e, this) : this, r = 0; null != (n = i[r]); r++) t || 1 !== n.nodeType || J.cleanData(v(n)), n.parentNode && (t && J.contains(n.ownerDocument, n) && m(v(n, "script")), n.parentNode.removeChild(n));
            return this
        },
        empty: function() {
            for (var e, t = 0; null != (e = this[t]); t++) 1 === e.nodeType && (J.cleanData(v(e, !1)), e.textContent = "");
            return this
        },
        clone: function(e, t) {
            return e = null != e && e, t = null == t ? e : t, this.map(function() {
                return J.clone(this, e, t)
            })
        },
        html: function(e) {
            return ge(this, function(e) {
                var t = this[0] || {},
                    n = 0,
                    i = this.length;
                if (void 0 === e && 1 === t.nodeType) return t.innerHTML;
                if ("string" == typeof e && !Ne.test(e) && !Le[(Me.exec(e) || ["", ""])[1].toLowerCase()]) {
                    e = e.replace(Fe, "<$1></$2>");
                    try {
                        for (; i > n; n++) t = this[n] || {}, 1 === t.nodeType && (J.cleanData(v(t, !1)), t.innerHTML = e);
                        t = 0
                    } catch (r) {}
                }
                t && this.empty().append(e)
            }, null, e, arguments.length)
        },
        replaceWith: function() {
            var e = arguments[0];
            return this.domManip(arguments, function(t) {
                e = this.parentNode, J.cleanData(v(this)), e && e.replaceChild(t, this)
            }), e && (e.length || e.nodeType) ? this : this.remove()
        },
        detach: function(e) {
            return this.remove(e, !0)
        },
        domManip: function(e, t) {
            e = B.apply([], e);
            var n, i, r, a, o, s, l = 0,
                c = this.length,
                d = this,
                u = c - 1,
                f = e[0],
                m = J.isFunction(f);
            if (m || c > 1 && "string" == typeof f && !Y.checkClone && Oe.test(f)) return this.each(function(n) {
                var i = d.eq(n);
                m && (e[0] = f.call(this, n, i.html())), i.domManip(e, t)
            });
            if (c && (n = J.buildFragment(e, this[0].ownerDocument, !1, this), i = n.firstChild, 1 === n.childNodes.length && (n = i), i)) {
                for (r = J.map(v(n, "script"), p), a = r.length; c > l; l++) o = n, l !== u && (o = J.clone(o, !0, !0), a && J.merge(r, v(o, "script"))), t.call(this[l], o, l);
                if (a)
                    for (s = r[r.length - 1].ownerDocument, J.map(r, h), l = 0; a > l; l++) o = r[l], je.test(o.type || "") && !ve.access(o, "globalEval") && J.contains(s, o) && (o.src ? J._evalUrl && J._evalUrl(o.src) : J.globalEval(o.textContent.replace(Ue, "")))
            }
            return this
        }
    }), J.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function(e, t) {
        J.fn[e] = function(e) {
            for (var n, i = [], r = J(e), a = r.length - 1, o = 0; a >= o; o++) n = o === a ? this : this.clone(!0), J(r[o])[t](n), z.apply(i, n.get());
            return this.pushStack(i)
        }
    });
    var Pe, He = {},
        qe = /^margin/,
        Ve = new RegExp("^(" + we + ")(?!px)[a-z%]+$", "i"),
        Re = function(t) {
            return t.ownerDocument.defaultView.opener ? t.ownerDocument.defaultView.getComputedStyle(t, null) : e.getComputedStyle(t, null)
        };
    ! function() {
        function t() {
            o.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute", o.innerHTML = "", r.appendChild(a);
            var t = e.getComputedStyle(o, null);
            n = "1%" !== t.top, i = "4px" === t.width, r.removeChild(a)
        }
        var n, i, r = Q.documentElement,
            a = Q.createElement("div"),
            o = Q.createElement("div");
        o.style && (o.style.backgroundClip = "content-box", o.cloneNode(!0).style.backgroundClip = "", Y.clearCloneStyle = "content-box" === o.style.backgroundClip, a.style.cssText = "border:0;width:0;height:0;top:0;left:-9999px;margin-top:1px;position:absolute", a.appendChild(o), e.getComputedStyle && J.extend(Y, {
            pixelPosition: function() {
                return t(), n
            },
            boxSizingReliable: function() {
                return null == i && t(), i
            },
            reliableMarginRight: function() {
                var t, n = o.appendChild(Q.createElement("div"));
                return n.style.cssText = o.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0", n.style.marginRight = n.style.width = "0", o.style.width = "1px", r.appendChild(a), t = !parseFloat(e.getComputedStyle(n, null).marginRight), r.removeChild(a), o.removeChild(n), t
            }
        }))
    }(), J.swap = function(e, t, n, i) {
        var r, a, o = {};
        for (a in t) o[a] = e.style[a], e.style[a] = t[a];
        r = n.apply(e, i || []);
        for (a in t) e.style[a] = o[a];
        return r
    };
    var Be = /^(none|table(?!-c[ea]).+)/,
        ze = new RegExp("^(" + we + ")(.*)$", "i"),
        We = new RegExp("^([+-])=(" + we + ")", "i"),
        Ke = {
            position: "absolute",
            visibility: "hidden",
            display: "block"
        },
        Ge = {
            letterSpacing: "0",
            fontWeight: "400"
        },
        Xe = ["Webkit", "O", "Moz", "ms"];
    J.extend({
            cssHooks: {
                opacity: {
                    get: function(e, t) {
                        if (t) {
                            var n = w(e, "opacity");
                            return "" === n ? "1" : n
                        }
                    }
                }
            },
            cssNumber: {
                columnCount: !0,
                fillOpacity: !0,
                flexGrow: !0,
                flexShrink: !0,
                fontWeight: !0,
                lineHeight: !0,
                opacity: !0,
                order: !0,
                orphans: !0,
                widows: !0,
                zIndex: !0,
                zoom: !0
            },
            cssProps: {
                "float": "cssFloat"
            },
            style: function(e, t, n, i) {
                if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                    var r, a, o, s = J.camelCase(t),
                        l = e.style;
                    return t = J.cssProps[s] || (J.cssProps[s] = $(l, s)), o = J.cssHooks[t] || J.cssHooks[s], void 0 === n ? o && "get" in o && void 0 !== (r = o.get(e, !1, i)) ? r : l[t] : (a = typeof n, "string" === a && (r = We.exec(n)) && (n = (r[1] + 1) * r[2] + parseFloat(J.css(e, t)), a = "number"), void(null != n && n === n && ("number" !== a || J.cssNumber[s] || (n += "px"), Y.clearCloneStyle || "" !== n || 0 !== t.indexOf("background") || (l[t] = "inherit"), o && "set" in o && void 0 === (n = o.set(e, n, i)) || (l[t] = n))))
                }
            },
            css: function(e, t, n, i) {
                var r, a, o, s = J.camelCase(t);
                return t = J.cssProps[s] || (J.cssProps[s] = $(e.style, s)), o = J.cssHooks[t] || J.cssHooks[s], o && "get" in o && (r = o.get(e, !0, n)), void 0 === r && (r = w(e, t, i)), "normal" === r && t in Ge && (r = Ge[t]), "" === n || n ? (a = parseFloat(r), n === !0 || J.isNumeric(a) ? a || 0 : r) : r
            }
        }), J.each(["height", "width"], function(e, t) {
            J.cssHooks[t] = {
                get: function(e, n, i) {
                    return n ? Be.test(J.css(e, "display")) && 0 === e.offsetWidth ? J.swap(e, Ke, function() {
                        return _(e, t, i)
                    }) : _(e, t, i) : void 0
                },
                set: function(e, n, i) {
                    var r = i && Re(e);
                    return k(e, n, i ? T(e, t, i, "border-box" === J.css(e, "boxSizing", !1, r), r) : 0)
                }
            }
        }), J.cssHooks.marginRight = C(Y.reliableMarginRight, function(e, t) {
            return t ? J.swap(e, {
                display: "inline-block"
            }, w, [e, "marginRight"]) : void 0
        }), J.each({
            margin: "",
            padding: "",
            border: "Width"
        }, function(e, t) {
            J.cssHooks[e + t] = {
                expand: function(n) {
                    for (var i = 0, r = {}, a = "string" == typeof n ? n.split(" ") : [n]; 4 > i; i++) r[e + Ce[i] + t] = a[i] || a[i - 2] || a[0];
                    return r
                }
            }, qe.test(e) || (J.cssHooks[e + t].set = k)
        }), J.fn.extend({
            css: function(e, t) {
                return ge(this, function(e, t, n) {
                    var i, r, a = {},
                        o = 0;
                    if (J.isArray(t)) {
                        for (i = Re(e), r = t.length; r > o; o++) a[t[o]] = J.css(e, t[o], !1, i);
                        return a
                    }
                    return void 0 !== n ? J.style(e, t, n) : J.css(e, t)
                }, e, t, arguments.length > 1)
            },
            show: function() {
                return A(this, !0)
            },
            hide: function() {
                return A(this)
            },
            toggle: function(e) {
                return "boolean" == typeof e ? e ? this.show() : this.hide() : this.each(function() {
                    $e(this) ? J(this).show() : J(this).hide()
                })
            }
        }), J.Tween = E, E.prototype = {
            constructor: E,
            init: function(e, t, n, i, r, a) {
                this.elem = e, this.prop = n, this.easing = r || "swing", this.options = t, this.start = this.now = this.cur(), this.end = i, this.unit = a || (J.cssNumber[n] ? "" : "px")
            },
            cur: function() {
                var e = E.propHooks[this.prop];
                return e && e.get ? e.get(this) : E.propHooks._default.get(this)
            },
            run: function(e) {
                var t, n = E.propHooks[this.prop];
                return this.options.duration ? this.pos = t = J.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : this.pos = t = e, this.now = (this.end - this.start) * t + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : E.propHooks._default.set(this), this
            }
        }, E.prototype.init.prototype = E.prototype, E.propHooks = {
            _default: {
                get: function(e) {
                    var t;
                    return null == e.elem[e.prop] || e.elem.style && null != e.elem.style[e.prop] ? (t = J.css(e.elem, e.prop, ""), t && "auto" !== t ? t : 0) : e.elem[e.prop]
                },
                set: function(e) {
                    J.fx.step[e.prop] ? J.fx.step[e.prop](e) : e.elem.style && (null != e.elem.style[J.cssProps[e.prop]] || J.cssHooks[e.prop]) ? J.style(e.elem, e.prop, e.now + e.unit) : e.elem[e.prop] = e.now
                }
            }
        }, E.propHooks.scrollTop = E.propHooks.scrollLeft = {
            set: function(e) {
                e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
            }
        }, J.easing = {
            linear: function(e) {
                return e
            },
            swing: function(e) {
                return .5 - Math.cos(e * Math.PI) / 2
            }
        }, J.fx = E.prototype.init,
        J.fx.step = {};
    var Ye, Qe, Ze = /^(?:toggle|show|hide)$/,
        Je = new RegExp("^(?:([+-])=|)(" + we + ")([a-z%]*)$", "i"),
        et = /queueHooks$/,
        tt = [D],
        nt = {
            "*": [function(e, t) {
                var n = this.createTween(e, t),
                    i = n.cur(),
                    r = Je.exec(t),
                    a = r && r[3] || (J.cssNumber[e] ? "" : "px"),
                    o = (J.cssNumber[e] || "px" !== a && +i) && Je.exec(J.css(n.elem, e)),
                    s = 1,
                    l = 20;
                if (o && o[3] !== a) {
                    a = a || o[3], r = r || [], o = +i || 1;
                    do s = s || ".5", o /= s, J.style(n.elem, e, o + a); while (s !== (s = n.cur() / i) && 1 !== s && --l)
                }
                return r && (o = n.start = +o || +i || 0, n.unit = a, n.end = r[1] ? o + (r[1] + 1) * r[2] : +r[2]), n
            }]
        };
    J.Animation = J.extend(O, {
            tweener: function(e, t) {
                J.isFunction(e) ? (t = e, e = ["*"]) : e = e.split(" ");
                for (var n, i = 0, r = e.length; r > i; i++) n = e[i], nt[n] = nt[n] || [], nt[n].unshift(t)
            },
            prefilter: function(e, t) {
                t ? tt.unshift(e) : tt.push(e)
            }
        }), J.speed = function(e, t, n) {
            var i = e && "object" == typeof e ? J.extend({}, e) : {
                complete: n || !n && t || J.isFunction(e) && e,
                duration: e,
                easing: n && t || t && !J.isFunction(t) && t
            };
            return i.duration = J.fx.off ? 0 : "number" == typeof i.duration ? i.duration : i.duration in J.fx.speeds ? J.fx.speeds[i.duration] : J.fx.speeds._default, (null == i.queue || i.queue === !0) && (i.queue = "fx"), i.old = i.complete, i.complete = function() {
                J.isFunction(i.old) && i.old.call(this), i.queue && J.dequeue(this, i.queue)
            }, i
        }, J.fn.extend({
            fadeTo: function(e, t, n, i) {
                return this.filter($e).css("opacity", 0).show().end().animate({
                    opacity: t
                }, e, n, i)
            },
            animate: function(e, t, n, i) {
                var r = J.isEmptyObject(e),
                    a = J.speed(t, n, i),
                    o = function() {
                        var t = O(this, J.extend({}, e), a);
                        (r || ve.get(this, "finish")) && t.stop(!0)
                    };
                return o.finish = o, r || a.queue === !1 ? this.each(o) : this.queue(a.queue, o)
            },
            stop: function(e, t, n) {
                var i = function(e) {
                    var t = e.stop;
                    delete e.stop, t(n)
                };
                return "string" != typeof e && (n = t, t = e, e = void 0), t && e !== !1 && this.queue(e || "fx", []), this.each(function() {
                    var t = !0,
                        r = null != e && e + "queueHooks",
                        a = J.timers,
                        o = ve.get(this);
                    if (r) o[r] && o[r].stop && i(o[r]);
                    else
                        for (r in o) o[r] && o[r].stop && et.test(r) && i(o[r]);
                    for (r = a.length; r--;) a[r].elem !== this || null != e && a[r].queue !== e || (a[r].anim.stop(n), t = !1, a.splice(r, 1));
                    (t || !n) && J.dequeue(this, e)
                })
            },
            finish: function(e) {
                return e !== !1 && (e = e || "fx"), this.each(function() {
                    var t, n = ve.get(this),
                        i = n[e + "queue"],
                        r = n[e + "queueHooks"],
                        a = J.timers,
                        o = i ? i.length : 0;
                    for (n.finish = !0, J.queue(this, e, []), r && r.stop && r.stop.call(this, !0), t = a.length; t--;) a[t].elem === this && a[t].queue === e && (a[t].anim.stop(!0), a.splice(t, 1));
                    for (t = 0; o > t; t++) i[t] && i[t].finish && i[t].finish.call(this);
                    delete n.finish
                })
            }
        }), J.each(["toggle", "show", "hide"], function(e, t) {
            var n = J.fn[t];
            J.fn[t] = function(e, i, r) {
                return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(F(t, !0), e, i, r)
            }
        }), J.each({
            slideDown: F("show"),
            slideUp: F("hide"),
            slideToggle: F("toggle"),
            fadeIn: {
                opacity: "show"
            },
            fadeOut: {
                opacity: "hide"
            },
            fadeToggle: {
                opacity: "toggle"
            }
        }, function(e, t) {
            J.fn[e] = function(e, n, i) {
                return this.animate(t, e, n, i)
            }
        }), J.timers = [], J.fx.tick = function() {
            var e, t = 0,
                n = J.timers;
            for (Ye = J.now(); t < n.length; t++) e = n[t], e() || n[t] !== e || n.splice(t--, 1);
            n.length || J.fx.stop(), Ye = void 0
        }, J.fx.timer = function(e) {
            J.timers.push(e), e() ? J.fx.start() : J.timers.pop()
        }, J.fx.interval = 13, J.fx.start = function() {
            Qe || (Qe = setInterval(J.fx.tick, J.fx.interval))
        }, J.fx.stop = function() {
            clearInterval(Qe), Qe = null
        }, J.fx.speeds = {
            slow: 600,
            fast: 200,
            _default: 400
        }, J.fn.delay = function(e, t) {
            return e = J.fx ? J.fx.speeds[e] || e : e, t = t || "fx", this.queue(t, function(t, n) {
                var i = setTimeout(t, e);
                n.stop = function() {
                    clearTimeout(i)
                }
            })
        },
        function() {
            var e = Q.createElement("input"),
                t = Q.createElement("select"),
                n = t.appendChild(Q.createElement("option"));
            e.type = "checkbox", Y.checkOn = "" !== e.value, Y.optSelected = n.selected, t.disabled = !0, Y.optDisabled = !n.disabled, e = Q.createElement("input"), e.value = "t", e.type = "radio", Y.radioValue = "t" === e.value
        }();
    var it, rt, at = J.expr.attrHandle;
    J.fn.extend({
        attr: function(e, t) {
            return ge(this, J.attr, e, t, arguments.length > 1)
        },
        removeAttr: function(e) {
            return this.each(function() {
                J.removeAttr(this, e)
            })
        }
    }), J.extend({
        attr: function(e, t, n) {
            var i, r, a = e.nodeType;
            if (e && 3 !== a && 8 !== a && 2 !== a) return typeof e.getAttribute === Te ? J.prop(e, t, n) : (1 === a && J.isXMLDoc(e) || (t = t.toLowerCase(), i = J.attrHooks[t] || (J.expr.match.bool.test(t) ? rt : it)), void 0 === n ? i && "get" in i && null !== (r = i.get(e, t)) ? r : (r = J.find.attr(e, t), null == r ? void 0 : r) : null !== n ? i && "set" in i && void 0 !== (r = i.set(e, n, t)) ? r : (e.setAttribute(t, n + ""), n) : void J.removeAttr(e, t))
        },
        removeAttr: function(e, t) {
            var n, i, r = 0,
                a = t && t.match(pe);
            if (a && 1 === e.nodeType)
                for (; n = a[r++];) i = J.propFix[n] || n, J.expr.match.bool.test(n) && (e[i] = !1), e.removeAttribute(n)
        },
        attrHooks: {
            type: {
                set: function(e, t) {
                    if (!Y.radioValue && "radio" === t && J.nodeName(e, "input")) {
                        var n = e.value;
                        return e.setAttribute("type", t), n && (e.value = n), t
                    }
                }
            }
        }
    }), rt = {
        set: function(e, t, n) {
            return t === !1 ? J.removeAttr(e, n) : e.setAttribute(n, n), n
        }
    }, J.each(J.expr.match.bool.source.match(/\w+/g), function(e, t) {
        var n = at[t] || J.find.attr;
        at[t] = function(e, t, i) {
            var r, a;
            return i || (a = at[t], at[t] = r, r = null != n(e, t, i) ? t.toLowerCase() : null, at[t] = a), r
        }
    });
    var ot = /^(?:input|select|textarea|button)$/i;
    J.fn.extend({
        prop: function(e, t) {
            return ge(this, J.prop, e, t, arguments.length > 1)
        },
        removeProp: function(e) {
            return this.each(function() {
                delete this[J.propFix[e] || e]
            })
        }
    }), J.extend({
        propFix: {
            "for": "htmlFor",
            "class": "className"
        },
        prop: function(e, t, n) {
            var i, r, a, o = e.nodeType;
            if (e && 3 !== o && 8 !== o && 2 !== o) return a = 1 !== o || !J.isXMLDoc(e), a && (t = J.propFix[t] || t, r = J.propHooks[t]), void 0 !== n ? r && "set" in r && void 0 !== (i = r.set(e, n, t)) ? i : e[t] = n : r && "get" in r && null !== (i = r.get(e, t)) ? i : e[t]
        },
        propHooks: {
            tabIndex: {
                get: function(e) {
                    return e.hasAttribute("tabindex") || ot.test(e.nodeName) || e.href ? e.tabIndex : -1
                }
            }
        }
    }), Y.optSelected || (J.propHooks.selected = {
        get: function(e) {
            var t = e.parentNode;
            return t && t.parentNode && t.parentNode.selectedIndex, null
        }
    }), J.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function() {
        J.propFix[this.toLowerCase()] = this
    });
    var st = /[\t\r\n\f]/g;
    J.fn.extend({
        addClass: function(e) {
            var t, n, i, r, a, o, s = "string" == typeof e && e,
                l = 0,
                c = this.length;
            if (J.isFunction(e)) return this.each(function(t) {
                J(this).addClass(e.call(this, t, this.className))
            });
            if (s)
                for (t = (e || "").match(pe) || []; c > l; l++)
                    if (n = this[l], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(st, " ") : " ")) {
                        for (a = 0; r = t[a++];) i.indexOf(" " + r + " ") < 0 && (i += r + " ");
                        o = J.trim(i), n.className !== o && (n.className = o)
                    }
            return this
        },
        removeClass: function(e) {
            var t, n, i, r, a, o, s = 0 === arguments.length || "string" == typeof e && e,
                l = 0,
                c = this.length;
            if (J.isFunction(e)) return this.each(function(t) {
                J(this).removeClass(e.call(this, t, this.className))
            });
            if (s)
                for (t = (e || "").match(pe) || []; c > l; l++)
                    if (n = this[l], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(st, " ") : "")) {
                        for (a = 0; r = t[a++];)
                            for (; i.indexOf(" " + r + " ") >= 0;) i = i.replace(" " + r + " ", " ");
                        o = e ? J.trim(i) : "", n.className !== o && (n.className = o)
                    }
            return this
        },
        toggleClass: function(e, t) {
            var n = typeof e;
            return "boolean" == typeof t && "string" === n ? t ? this.addClass(e) : this.removeClass(e) : this.each(J.isFunction(e) ? function(n) {
                J(this).toggleClass(e.call(this, n, this.className, t), t)
            } : function() {
                if ("string" === n)
                    for (var t, i = 0, r = J(this), a = e.match(pe) || []; t = a[i++];) r.hasClass(t) ? r.removeClass(t) : r.addClass(t);
                else(n === Te || "boolean" === n) && (this.className && ve.set(this, "__className__", this.className), this.className = this.className || e === !1 ? "" : ve.get(this, "__className__") || "")
            })
        },
        hasClass: function(e) {
            for (var t = " " + e + " ", n = 0, i = this.length; i > n; n++)
                if (1 === this[n].nodeType && (" " + this[n].className + " ").replace(st, " ").indexOf(t) >= 0) return !0;
            return !1
        }
    });
    var lt = /\r/g;
    J.fn.extend({
        val: function(e) {
            var t, n, i, r = this[0];
            return arguments.length ? (i = J.isFunction(e), this.each(function(n) {
                var r;
                1 === this.nodeType && (r = i ? e.call(this, n, J(this).val()) : e, null == r ? r = "" : "number" == typeof r ? r += "" : J.isArray(r) && (r = J.map(r, function(e) {
                    return null == e ? "" : e + ""
                })), t = J.valHooks[this.type] || J.valHooks[this.nodeName.toLowerCase()], t && "set" in t && void 0 !== t.set(this, r, "value") || (this.value = r))
            })) : r ? (t = J.valHooks[r.type] || J.valHooks[r.nodeName.toLowerCase()], t && "get" in t && void 0 !== (n = t.get(r, "value")) ? n : (n = r.value, "string" == typeof n ? n.replace(lt, "") : null == n ? "" : n)) : void 0
        }
    }), J.extend({
        valHooks: {
            option: {
                get: function(e) {
                    var t = J.find.attr(e, "value");
                    return null != t ? t : J.trim(J.text(e))
                }
            },
            select: {
                get: function(e) {
                    for (var t, n, i = e.options, r = e.selectedIndex, a = "select-one" === e.type || 0 > r, o = a ? null : [], s = a ? r + 1 : i.length, l = 0 > r ? s : a ? r : 0; s > l; l++)
                        if (n = i[l], !(!n.selected && l !== r || (Y.optDisabled ? n.disabled : null !== n.getAttribute("disabled")) || n.parentNode.disabled && J.nodeName(n.parentNode, "optgroup"))) {
                            if (t = J(n).val(), a) return t;
                            o.push(t)
                        }
                    return o
                },
                set: function(e, t) {
                    for (var n, i, r = e.options, a = J.makeArray(t), o = r.length; o--;) i = r[o], (i.selected = J.inArray(i.value, a) >= 0) && (n = !0);
                    return n || (e.selectedIndex = -1), a
                }
            }
        }
    }), J.each(["radio", "checkbox"], function() {
        J.valHooks[this] = {
            set: function(e, t) {
                return J.isArray(t) ? e.checked = J.inArray(J(e).val(), t) >= 0 : void 0
            }
        }, Y.checkOn || (J.valHooks[this].get = function(e) {
            return null === e.getAttribute("value") ? "on" : e.value
        })
    }), J.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function(e, t) {
        J.fn[t] = function(e, n) {
            return arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
        }
    }), J.fn.extend({
        hover: function(e, t) {
            return this.mouseenter(e).mouseleave(t || e)
        },
        bind: function(e, t, n) {
            return this.on(e, null, t, n)
        },
        unbind: function(e, t) {
            return this.off(e, null, t)
        },
        delegate: function(e, t, n, i) {
            return this.on(t, e, n, i)
        },
        undelegate: function(e, t, n) {
            return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
        }
    });
    var ct = J.now(),
        dt = /\?/;
    J.parseJSON = function(e) {
        return JSON.parse(e + "")
    }, J.parseXML = function(e) {
        var t, n;
        if (!e || "string" != typeof e) return null;
        try {
            n = new DOMParser, t = n.parseFromString(e, "text/xml")
        } catch (i) {
            t = void 0
        }
        return (!t || t.getElementsByTagName("parsererror").length) && J.error("Invalid XML: " + e), t
    };
    var ut = /#.*$/,
        ft = /([?&])_=[^&]*/,
        pt = /^(.*?):[ \t]*([^\r\n]*)$/gm,
        ht = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/,
        mt = /^(?:GET|HEAD)$/,
        gt = /^\/\//,
        vt = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/,
        yt = {},
        bt = {},
        xt = "*/".concat("*"),
        wt = e.location.href,
        Ct = vt.exec(wt.toLowerCase()) || [];
    J.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: wt,
            type: "GET",
            isLocal: ht.test(Ct[1]),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": xt,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {
                xml: /xml/,
                html: /html/,
                json: /json/
            },
            responseFields: {
                xml: "responseXML",
                text: "responseText",
                json: "responseJSON"
            },
            converters: {
                "* text": String,
                "text html": !0,
                "text json": J.parseJSON,
                "text xml": J.parseXML
            },
            flatOptions: {
                url: !0,
                context: !0
            }
        },
        ajaxSetup: function(e, t) {
            return t ? U(U(e, J.ajaxSettings), t) : U(J.ajaxSettings, e)
        },
        ajaxPrefilter: j(yt),
        ajaxTransport: j(bt),
        ajax: function(e, t) {
            function n(e, t, n, o) {
                var l, d, v, y, x, C = t;
                2 !== b && (b = 2, s && clearTimeout(s), i = void 0, a = o || "", w.readyState = e > 0 ? 4 : 0, l = e >= 200 && 300 > e || 304 === e, n && (y = L(u, w, n)), y = P(u, y, w, l), l ? (u.ifModified && (x = w.getResponseHeader("Last-Modified"), x && (J.lastModified[r] = x), x = w.getResponseHeader("etag"), x && (J.etag[r] = x)), 204 === e || "HEAD" === u.type ? C = "nocontent" : 304 === e ? C = "notmodified" : (C = y.state, d = y.data, v = y.error, l = !v)) : (v = C, (e || !C) && (C = "error", 0 > e && (e = 0))), w.status = e, w.statusText = (t || C) + "", l ? h.resolveWith(f, [d, C, w]) : h.rejectWith(f, [w, C, v]), w.statusCode(g), g = void 0, c && p.trigger(l ? "ajaxSuccess" : "ajaxError", [w, u, l ? d : v]), m.fireWith(f, [w, C]), c && (p.trigger("ajaxComplete", [w, u]), --J.active || J.event.trigger("ajaxStop")))
            }
            "object" == typeof e && (t = e, e = void 0), t = t || {};
            var i, r, a, o, s, l, c, d, u = J.ajaxSetup({}, t),
                f = u.context || u,
                p = u.context && (f.nodeType || f.jquery) ? J(f) : J.event,
                h = J.Deferred(),
                m = J.Callbacks("once memory"),
                g = u.statusCode || {},
                v = {},
                y = {},
                b = 0,
                x = "canceled",
                w = {
                    readyState: 0,
                    getResponseHeader: function(e) {
                        var t;
                        if (2 === b) {
                            if (!o)
                                for (o = {}; t = pt.exec(a);) o[t[1].toLowerCase()] = t[2];
                            t = o[e.toLowerCase()]
                        }
                        return null == t ? null : t
                    },
                    getAllResponseHeaders: function() {
                        return 2 === b ? a : null
                    },
                    setRequestHeader: function(e, t) {
                        var n = e.toLowerCase();
                        return b || (e = y[n] = y[n] || e, v[e] = t), this
                    },
                    overrideMimeType: function(e) {
                        return b || (u.mimeType = e), this
                    },
                    statusCode: function(e) {
                        var t;
                        if (e)
                            if (2 > b)
                                for (t in e) g[t] = [g[t], e[t]];
                            else w.always(e[w.status]);
                        return this
                    },
                    abort: function(e) {
                        var t = e || x;
                        return i && i.abort(t), n(0, t), this
                    }
                };
            if (h.promise(w).complete = m.add, w.success = w.done, w.error = w.fail, u.url = ((e || u.url || wt) + "").replace(ut, "").replace(gt, Ct[1] + "//"), u.type = t.method || t.type || u.method || u.type, u.dataTypes = J.trim(u.dataType || "*").toLowerCase().match(pe) || [""], null == u.crossDomain && (l = vt.exec(u.url.toLowerCase()), u.crossDomain = !(!l || l[1] === Ct[1] && l[2] === Ct[2] && (l[3] || ("http:" === l[1] ? "80" : "443")) === (Ct[3] || ("http:" === Ct[1] ? "80" : "443")))), u.data && u.processData && "string" != typeof u.data && (u.data = J.param(u.data, u.traditional)), I(yt, u, t, w), 2 === b) return w;
            c = J.event && u.global, c && 0 === J.active++ && J.event.trigger("ajaxStart"), u.type = u.type.toUpperCase(), u.hasContent = !mt.test(u.type), r = u.url, u.hasContent || (u.data && (r = u.url += (dt.test(r) ? "&" : "?") + u.data, delete u.data), u.cache === !1 && (u.url = ft.test(r) ? r.replace(ft, "$1_=" + ct++) : r + (dt.test(r) ? "&" : "?") + "_=" + ct++)), u.ifModified && (J.lastModified[r] && w.setRequestHeader("If-Modified-Since", J.lastModified[r]), J.etag[r] && w.setRequestHeader("If-None-Match", J.etag[r])), (u.data && u.hasContent && u.contentType !== !1 || t.contentType) && w.setRequestHeader("Content-Type", u.contentType), w.setRequestHeader("Accept", u.dataTypes[0] && u.accepts[u.dataTypes[0]] ? u.accepts[u.dataTypes[0]] + ("*" !== u.dataTypes[0] ? ", " + xt + "; q=0.01" : "") : u.accepts["*"]);
            for (d in u.headers) w.setRequestHeader(d, u.headers[d]);
            if (u.beforeSend && (u.beforeSend.call(f, w, u) === !1 || 2 === b)) return w.abort();
            x = "abort";
            for (d in {
                    success: 1,
                    error: 1,
                    complete: 1
                }) w[d](u[d]);
            if (i = I(bt, u, t, w)) {
                w.readyState = 1, c && p.trigger("ajaxSend", [w, u]), u.async && u.timeout > 0 && (s = setTimeout(function() {
                    w.abort("timeout")
                }, u.timeout));
                try {
                    b = 1, i.send(v, n)
                } catch (C) {
                    if (!(2 > b)) throw C;
                    n(-1, C)
                }
            } else n(-1, "No Transport");
            return w
        },
        getJSON: function(e, t, n) {
            return J.get(e, t, n, "json")
        },
        getScript: function(e, t) {
            return J.get(e, void 0, t, "script")
        }
    }), J.each(["get", "post"], function(e, t) {
        J[t] = function(e, n, i, r) {
            return J.isFunction(n) && (r = r || i, i = n, n = void 0), J.ajax({
                url: e,
                type: t,
                dataType: r,
                data: n,
                success: i
            })
        }
    }), J._evalUrl = function(e) {
        return J.ajax({
            url: e,
            type: "GET",
            dataType: "script",
            async: !1,
            global: !1,
            "throws": !0
        })
    }, J.fn.extend({
        wrapAll: function(e) {
            var t;
            return J.isFunction(e) ? this.each(function(t) {
                J(this).wrapAll(e.call(this, t))
            }) : (this[0] && (t = J(e, this[0].ownerDocument).eq(0).clone(!0), this[0].parentNode && t.insertBefore(this[0]), t.map(function() {
                for (var e = this; e.firstElementChild;) e = e.firstElementChild;
                return e
            }).append(this)), this)
        },
        wrapInner: function(e) {
            return this.each(J.isFunction(e) ? function(t) {
                J(this).wrapInner(e.call(this, t))
            } : function() {
                var t = J(this),
                    n = t.contents();
                n.length ? n.wrapAll(e) : t.append(e)
            })
        },
        wrap: function(e) {
            var t = J.isFunction(e);
            return this.each(function(n) {
                J(this).wrapAll(t ? e.call(this, n) : e)
            })
        },
        unwrap: function() {
            return this.parent().each(function() {
                J.nodeName(this, "body") || J(this).replaceWith(this.childNodes)
            }).end()
        }
    }), J.expr.filters.hidden = function(e) {
        return e.offsetWidth <= 0 && e.offsetHeight <= 0
    }, J.expr.filters.visible = function(e) {
        return !J.expr.filters.hidden(e)
    };
    var $t = /%20/g,
        kt = /\[\]$/,
        Tt = /\r?\n/g,
        _t = /^(?:submit|button|image|reset|file)$/i,
        At = /^(?:input|select|textarea|keygen)/i;
    J.param = function(e, t) {
        var n, i = [],
            r = function(e, t) {
                t = J.isFunction(t) ? t() : null == t ? "" : t, i[i.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
            };
        if (void 0 === t && (t = J.ajaxSettings && J.ajaxSettings.traditional), J.isArray(e) || e.jquery && !J.isPlainObject(e)) J.each(e, function() {
            r(this.name, this.value)
        });
        else
            for (n in e) H(n, e[n], t, r);
        return i.join("&").replace($t, "+")
    }, J.fn.extend({
        serialize: function() {
            return J.param(this.serializeArray())
        },
        serializeArray: function() {
            return this.map(function() {
                var e = J.prop(this, "elements");
                return e ? J.makeArray(e) : this
            }).filter(function() {
                var e = this.type;
                return this.name && !J(this).is(":disabled") && At.test(this.nodeName) && !_t.test(e) && (this.checked || !ke.test(e))
            }).map(function(e, t) {
                var n = J(this).val();
                return null == n ? null : J.isArray(n) ? J.map(n, function(e) {
                    return {
                        name: t.name,
                        value: e.replace(Tt, "\r\n")
                    }
                }) : {
                    name: t.name,
                    value: n.replace(Tt, "\r\n")
                }
            }).get()
        }
    }), J.ajaxSettings.xhr = function() {
        try {
            return new XMLHttpRequest
        } catch (e) {}
    };
    var Et = 0,
        St = {},
        Ft = {
            0: 200,
            1223: 204
        },
        Mt = J.ajaxSettings.xhr();
    e.attachEvent && e.attachEvent("onunload", function() {
        for (var e in St) St[e]()
    }), Y.cors = !!Mt && "withCredentials" in Mt, Y.ajax = Mt = !!Mt, J.ajaxTransport(function(e) {
        var t;
        return Y.cors || Mt && !e.crossDomain ? {
            send: function(n, i) {
                var r, a = e.xhr(),
                    o = ++Et;
                if (a.open(e.type, e.url, e.async, e.username, e.password), e.xhrFields)
                    for (r in e.xhrFields) a[r] = e.xhrFields[r];
                e.mimeType && a.overrideMimeType && a.overrideMimeType(e.mimeType), e.crossDomain || n["X-Requested-With"] || (n["X-Requested-With"] = "XMLHttpRequest");
                for (r in n) a.setRequestHeader(r, n[r]);
                t = function(e) {
                    return function() {
                        t && (delete St[o], t = a.onload = a.onerror = null, "abort" === e ? a.abort() : "error" === e ? i(a.status, a.statusText) : i(Ft[a.status] || a.status, a.statusText, "string" == typeof a.responseText ? {
                            text: a.responseText
                        } : void 0, a.getAllResponseHeaders()))
                    }
                }, a.onload = t(), a.onerror = t("error"), t = St[o] = t("abort");
                try {
                    a.send(e.hasContent && e.data || null)
                } catch (s) {
                    if (t) throw s
                }
            },
            abort: function() {
                t && t()
            }
        } : void 0
    }), J.ajaxSetup({
        accepts: {
            script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        contents: {
            script: /(?:java|ecma)script/
        },
        converters: {
            "text script": function(e) {
                return J.globalEval(e), e
            }
        }
    }), J.ajaxPrefilter("script", function(e) {
        void 0 === e.cache && (e.cache = !1), e.crossDomain && (e.type = "GET")
    }), J.ajaxTransport("script", function(e) {
        if (e.crossDomain) {
            var t, n;
            return {
                send: function(i, r) {
                    t = J("<script>").prop({
                        async: !0,
                        charset: e.scriptCharset,
                        src: e.url
                    }).on("load error", n = function(e) {
                        t.remove(), n = null, e && r("error" === e.type ? 404 : 200, e.type)
                    }), Q.head.appendChild(t[0])
                },
                abort: function() {
                    n && n()
                }
            }
        }
    });
    var Dt = [],
        Nt = /(=)\?(?=&|$)|\?\?/;
    J.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function() {
            var e = Dt.pop() || J.expando + "_" + ct++;
            return this[e] = !0, e
        }
    }), J.ajaxPrefilter("json jsonp", function(t, n, i) {
        var r, a, o, s = t.jsonp !== !1 && (Nt.test(t.url) ? "url" : "string" == typeof t.data && !(t.contentType || "").indexOf("application/x-www-form-urlencoded") && Nt.test(t.data) && "data");
        return s || "jsonp" === t.dataTypes[0] ? (r = t.jsonpCallback = J.isFunction(t.jsonpCallback) ? t.jsonpCallback() : t.jsonpCallback, s ? t[s] = t[s].replace(Nt, "$1" + r) : t.jsonp !== !1 && (t.url += (dt.test(t.url) ? "&" : "?") + t.jsonp + "=" + r), t.converters["script json"] = function() {
            return o || J.error(r + " was not called"), o[0]
        }, t.dataTypes[0] = "json", a = e[r], e[r] = function() {
            o = arguments
        }, i.always(function() {
            e[r] = a, t[r] && (t.jsonpCallback = n.jsonpCallback, Dt.push(r)), o && J.isFunction(a) && a(o[0]), o = a = void 0
        }), "script") : void 0
    }), J.parseHTML = function(e, t, n) {
        if (!e || "string" != typeof e) return null;
        "boolean" == typeof t && (n = t, t = !1), t = t || Q;
        var i = oe.exec(e),
            r = !n && [];
        return i ? [t.createElement(i[1])] : (i = J.buildFragment([e], t, r), r && r.length && J(r).remove(), J.merge([], i.childNodes))
    };
    var Ot = J.fn.load;
    J.fn.load = function(e, t, n) {
        if ("string" != typeof e && Ot) return Ot.apply(this, arguments);
        var i, r, a, o = this,
            s = e.indexOf(" ");
        return s >= 0 && (i = J.trim(e.slice(s)), e = e.slice(0, s)), J.isFunction(t) ? (n = t, t = void 0) : t && "object" == typeof t && (r = "POST"), o.length > 0 && J.ajax({
            url: e,
            type: r,
            dataType: "html",
            data: t
        }).done(function(e) {
            a = arguments, o.html(i ? J("<div>").append(J.parseHTML(e)).find(i) : e)
        }).complete(n && function(e, t) {
            o.each(n, a || [e.responseText, t, e])
        }), this
    }, J.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function(e, t) {
        J.fn[t] = function(e) {
            return this.on(t, e)
        }
    }), J.expr.filters.animated = function(e) {
        return J.grep(J.timers, function(t) {
            return e === t.elem
        }).length
    };
    var jt = e.document.documentElement;
    J.offset = {
        setOffset: function(e, t, n) {
            var i, r, a, o, s, l, c, d = J.css(e, "position"),
                u = J(e),
                f = {};
            "static" === d && (e.style.position = "relative"), s = u.offset(), a = J.css(e, "top"), l = J.css(e, "left"), c = ("absolute" === d || "fixed" === d) && (a + l).indexOf("auto") > -1, c ? (i = u.position(), o = i.top, r = i.left) : (o = parseFloat(a) || 0, r = parseFloat(l) || 0), J.isFunction(t) && (t = t.call(e, n, s)), null != t.top && (f.top = t.top - s.top + o), null != t.left && (f.left = t.left - s.left + r), "using" in t ? t.using.call(e, f) : u.css(f)
        }
    }, J.fn.extend({
        offset: function(e) {
            if (arguments.length) return void 0 === e ? this : this.each(function(t) {
                J.offset.setOffset(this, e, t)
            });
            var t, n, i = this[0],
                r = {
                    top: 0,
                    left: 0
                },
                a = i && i.ownerDocument;
            return a ? (t = a.documentElement, J.contains(t, i) ? (typeof i.getBoundingClientRect !== Te && (r = i.getBoundingClientRect()), n = q(a), {
                top: r.top + n.pageYOffset - t.clientTop,
                left: r.left + n.pageXOffset - t.clientLeft
            }) : r) : void 0
        },
        position: function() {
            if (this[0]) {
                var e, t, n = this[0],
                    i = {
                        top: 0,
                        left: 0
                    };
                return "fixed" === J.css(n, "position") ? t = n.getBoundingClientRect() : (e = this.offsetParent(), t = this.offset(), J.nodeName(e[0], "html") || (i = e.offset()), i.top += J.css(e[0], "borderTopWidth", !0), i.left += J.css(e[0], "borderLeftWidth", !0)), {
                    top: t.top - i.top - J.css(n, "marginTop", !0),
                    left: t.left - i.left - J.css(n, "marginLeft", !0)
                }
            }
        },
        offsetParent: function() {
            return this.map(function() {
                for (var e = this.offsetParent || jt; e && !J.nodeName(e, "html") && "static" === J.css(e, "position");) e = e.offsetParent;
                return e || jt
            })
        }
    }), J.each({
        scrollLeft: "pageXOffset",
        scrollTop: "pageYOffset"
    }, function(t, n) {
        var i = "pageYOffset" === n;
        J.fn[t] = function(r) {
            return ge(this, function(t, r, a) {
                var o = q(t);
                return void 0 === a ? o ? o[n] : t[r] : void(o ? o.scrollTo(i ? e.pageXOffset : a, i ? a : e.pageYOffset) : t[r] = a)
            }, t, r, arguments.length, null)
        }
    }), J.each(["top", "left"], function(e, t) {
        J.cssHooks[t] = C(Y.pixelPosition, function(e, n) {
            return n ? (n = w(e, t), Ve.test(n) ? J(e).position()[t] + "px" : n) : void 0
        })
    }), J.each({
        Height: "height",
        Width: "width"
    }, function(e, t) {
        J.each({
            padding: "inner" + e,
            content: t,
            "": "outer" + e
        }, function(n, i) {
            J.fn[i] = function(i, r) {
                var a = arguments.length && (n || "boolean" != typeof i),
                    o = n || (i === !0 || r === !0 ? "margin" : "border");
                return ge(this, function(t, n, i) {
                    var r;
                    return J.isWindow(t) ? t.document.documentElement["client" + e] : 9 === t.nodeType ? (r = t.documentElement, Math.max(t.body["scroll" + e], r["scroll" + e], t.body["offset" + e], r["offset" + e], r["client" + e])) : void 0 === i ? J.css(t, n, o) : J.style(t, n, i, o)
                }, t, a ? i : void 0, a, null)
            }
        })
    }), J.fn.size = function() {
        return this.length
    }, J.fn.andSelf = J.fn.addBack, "function" == typeof define && define.amd && define("jquery", [], function() {
        return J
    });
    var It = e.jQuery,
        Ut = e.$;
    return J.noConflict = function(t) {
        return e.$ === J && (e.$ = Ut), t && e.jQuery === J && (e.jQuery = It), J
    }, typeof t === Te && (e.jQuery = e.$ = J), J
}), $(".search-submit").click(function() {
    $(".header__appbar--right__search").hasClass("open") ? $("#header-search").submit() : ($(".header__appbar--right__search").addClass("open"), $(".header__appbar--right__search__input").focus(), $(".header__appbar--christmas").addClass("opacity"))
}), $(".search-close").click(function() {
    $(".header__appbar--right__search").removeClass("open"), $(".header__appbar--christmas").removeClass("opacity")
}), $(".header__appbar--left__nav").click(function() {
    $("body").toggleClass("drawer-active")
}), $(".drawer__header__close").click(function() {
    $("body").toggleClass("drawer-active")
}), $("body").on("click", ".overlay", function() {
    $("body").removeAttr("class")
}), $(function() {
    $("body").on({
        click: function(e) {
            if ($(this).hasClass("share-dropdown-button") && isMobile) var t = "right-bottom";
            else var t = $(this).data("align");
            var n = $(this).data("target"),
                i = $(this).height(),
                r = ($(this).width(), 0),
                a = !1,
                o = !1;
            if ("right-bottom" == t) {
                var s = r.top + i,
                    l = 0;
                a = !0
            } else if ("right-top" == t) {
                var s = 0,
                    l = 0;
                a = !0
            } else if ("left-top" == t) {
                var s = 0,
                    l = 0;
                o = !0
            } else if ("left-bottom" == t) {
                var s = r.top + i,
                    c = 0;
                o = !0
            }
            $(this).hasClass("is-active") ? ($(this).removeClass("is-active"), $("." + n).removeClass("is-visible")) : ($(".has-dropdown").removeClass("is-active"), $(".dropdown-container").removeClass("is-visible"), $(this).addClass("is-active"), $("." + n).addClass("is-visible"), o ? $("." + n).css({
                top: s,
                left: c,
                "transform-origin": "left top"
            }) : $("." + n).css({
                top: s,
                right: l,
                "transform-origin": "right top"
            })), e.stopPropagation()
        }
    }, ".has-dropdown"), $("body").on({
        click: function(e) {
            e.stopPropagation()
        }
    }, ".dropdown-container__item"), $(document).click(function() {
        $(".has-dropdown").removeClass("is-active"), $(".dropdown-container").removeClass("is-visible")
    })
}), $(function() {
    var e = $(".header"),
        t = $(".header__searchbar"),
        n = ($(".header__appbar"), $(".header__tabbar"), $(".content-header")),
        i = ($(".share-container"), 1048),
        r = null,
        a = null,
        o = null,
        s = null,
        l = null;
    $(window).on("scroll", function() {
        if (o = $(this), r = o.scrollTop(), r > a ? (l = null, null == s && r >= e.height() ? s = r + e.height() : r >= s && r >= e.height() && ($("body").removeClass("scroll-up").addClass("scroll-down"), t.hasClass("is-active") && t.removeClass("is-active"))) : (s = null, null == l ? l = r - e.height() : (r <= l || r <= e.height()) && $("body").removeClass("scroll-down").addClass("scroll-up")), o.width() >= i && (r > a ? (l = null, null == s ? s = r + e.height() : r >= s && n.length > 0 && n.addClass("is-active")) : (s = null, null == l ? l = r - e.height() : r <= l && n.length > 0 && r <= e.height() && n.removeClass("is-active"))), a = r, !isMobile || isMobile) {
            var c = $("body");
            c.hasClass("scroll-down") ? e.hasClass("s-passive") || e.addClass("is-passive") : c.hasClass("scroll-up") && e.hasClass("is-passive") && e.removeClass("is-passive")
        }
    })
}), $("body").on({
    click: function() {
        var e = $(this).attr("data-action"),
            t = $(".content-body__detail").css("font-size"),
            n = $(".content-body__detail").css("line-height");
        t = parseInt(t), n = parseInt(n), "plus" == e ? t < 28 && n < 39 && (t += 2, n += 2) : t > 12 && n > 23 && (t -= 2, n -= 2), $(".content-body__detail").css({
            "font-size": t + "px",
            "line-height": n + "px"
        })
    }
}, ".content-font__item"), $(function() {
    $(".rating-circle").length > 0 && $.each($(".rating-circle"), function() {
        $(this).find("span").fadeTo("slow", 1);
        var e = $(this).data("score") / 2,
            t = 20 * e / 100 * 180,
            n = t,
            i = n,
            r = 2 * n;
        $(this).find(".circle .circle-fill, .circle .circle-mask.circle-full").css("transform", "rotate(" + i + "deg)"), $(this).find(".circle .circle-fill.fix").css("transform", "rotate(" + r + "deg)"), $(this).find(".circle .circle-fill, .circle .circle-mask.circle-full").css("-webkit-transform", "rotate(" + i + "deg)"), $(this).find(".circle .circle-fill.fix").css("-webkit-transform", "rotate(" + r + "deg)"), $(this).find(".circle .circle-fill, .circle .circle-mask.circle-full").css("transform", "rotate(" + i + "deg)"), $(this).find(".circle .circle-fill.fix").css("transform", "rotate(" + r + "deg)")
    })
});
var headerHeadlineCurrent = 0,
    headerHeadlineLimit = 6,
    headerHeadlineLimit = 6,
    headerHeadlineCurrent = 0,
    text = "",
    isButtonEnable = !0;
! function(e) {
    e.fn.writeText = function(e) {
        var t = e.split(""),
            n = 0,
            i = this;
        i.text("");
        var r = setInterval(function() {
            n < t.length ? (headerHeadlineStop(), isButtonEnable = !1, i.text(i.text() + t[n++])) : (clearInterval(r), headerHeadlineStart(), isButtonEnable = !0)
        }, 20)
    }
}(jQuery), $(function() {
    headerHeadlineLimit = $(".header__tabbar--left__headline--list__title").length, headerHeadlineCurrent = 0, headerHeadlineLimit > 0 && (headerHeadlineStart(), updateHeadlineText(), $("body").on("click", ".header__tabbar--left__headline--list__nav__prev", function() {
        isButtonEnable === !0 && (headerHeadlineCurrent > 0 ? headerHeadlineCurrent-- : headerHeadlineCurrent = headerHeadlineLimit - 1, updateHeadlineText())
    }), $("body").on("click", ".header__tabbar--left__headline--list__nav__next", function() {
        isButtonEnable === !0 && (headerHeadlineCurrent >= headerHeadlineLimit - 1 ? headerHeadlineCurrent = 0 : headerHeadlineCurrent++, updateHeadlineText())
    }))
}), $(function() {
    isMobile || $("body").on("click", ".content-timeline__media__icon--play-button", function() {
        var e = $("#" + $(this).attr("data-for")),
            t = e.find("iframe"),
            n = t.attr("src") + "?autoplay=1";
        t.attr("src", n), $(this).hide(), $(this).closest(".content-timeline__media").css("z-index", 10), setTimeout(function() {
            e.fadeIn(1500)
        }, 100)
    })
}), $(function() {
    var e = $(".back-to-top");
    $(window).on("scroll", function() {
        $(this).scrollTop() > $(this).height() && "" == e.hasClass("is-visible") ? e.addClass("is-visible") : $(this).scrollTop() < $(this).height() && e.hasClass("is-visible") && e.removeClass("is-visible")
    }), e.on("click", function() {
        $("html, body").animate({
            scrollTop: 0
        }, 1e3)
    })
}), $(function() {
    $(".notification-dropdown__scrollable").bind("mousewheel DOMMouseScroll", function(e) {
        var t = null;
        "mousewheel" == e.type ? t = e.originalEvent.wheelDelta * -1 : "DOMMouseScroll" == e.type && (t = 40 * e.originalEvent.detail), t && (e.preventDefault(), $(this).scrollTop(t + $(this).scrollTop()))
    })
}), $(function() {
    function e() {
        var e = null;
        return e = i.hasClass("scroll-down") ? n.length > 0 ? n.height() : r.height() : n.length > 0 ? n.height() : a.height()
    }
    var t = $(".ads-sticky"),
        n = $(".content-header"),
        i = $("body"),
        r = $(".header__tabbar"),
        a = $(".header"),
        o = 20;
    if (t.length > 0) {
        var s = t.offset().top;
        $(window).on("scroll", function() {
            if ($(window).width() >= 1048) {
                var n = e();
                $(this).scrollTop() >= s - n ? t.css({
                    position: "fixed",
                    top: n + o + "px"
                }) : t.css({
                    position: "absolute",
                    top: o + "px"
                })
            }
        })
    }
}), $(function() {
    $("body").on("click", function() {
        "false" != getCookie("android-dropdown-v4") && createCookie("android-dropdown-v4", !1, 7)
    })
}), $(function() {
    var e = getCookie("interstitial");
    null != e && "no" == e || $(".interstitial").fadeIn("slow"), $(".interstitial__content").on("click", function() {
        $(".interstitial").fadeOut("slow")
    }), createCookie("interstitial", "no", 1, "/", null, 1)
});
var formStatus = !1;
$(".no-enter").submit(function(e) {
    var t = $(this);
    t.find(".g-recaptcha");
    if (0 == formStatus) return setTimeout(function() {
        t.isValid() && (formStatus = !0, t.submit())
    }, 2e3), !1
}), $(document).on("keypress", ".no-enter", function(e) {
    return 13 != e.keyCode
});
var $loginButton = $("#modal-signin").find(".login__form-button--signin");
$loginButton.on("click", function() {
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var e = $("#modal-signin-form"),
            t = $("#modal-signin").find(".login__notice"),
            n = e.find('input[name="email"]'),
            i = e.find('input[name="password"]');
        n.val() && i.val() ? $.ajax({
            type: "POST",
            url: "/giris",
            data: {
                email: n.val(),
                password: i.val(),
				_token: CSRF_TOKEN
            },
            dataType: "HTML",
            success: function(t) {
                e[0].submit()
            },
            error: function(e, n, i) {
                t.addClass("login__notice--error").html("Kullanıcı adı şifre hatalı.")
            }
        }) : t.addClass("login__notice--error").html("Boş Bırakılmaz.")
    })
	, $("body").on({
        change: function() {
            var e = $("#modal-edit-profile"),
                t = e.find(".login__notice"),
                n = $("#fileUploadForm")[0],
                i = new FormData(n),
                r = this;
            $(".login__avatar__image").append('<div class="avatar-loading"><img src="/assets/default/images/loading.svg"></div>'), this.files && this.files[0] && $.ajax({
                url: "/ajax/user-update-photo",
				headers: {
				  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data:i,
                type: "POST",
                enctype: "multipart/form-data",
                processData: !1,
                contentType: !1,
                cache: !1,
                success: function(e) {
                    console.log(e), "large_image_size" == e.error_reason ? t.removeClass("hide").addClass("login__notice--error").html("Fotoğrafın boyutu büyük, lütfen başka bir fotoğraf deneyin.") : "not_supoorted_type" == e.error_reason ? t.removeClass("hide").addClass("login__notice--error").html("Fotoğraf formatı hatalı, lütfen .JPG .JPEG ve .PNG formatlarında bir fotoğraf deneyin.") : (t.addClass("hide"), readURL(r)), $(".avatar-loading").remove();
                },
                error: function(e) {
                    console.log(e), t.removeClass("hide").addClass("login__notice--error").html("Bir hata oluştu. Lütfen tekrar fotoğraf yükleyin."), $(".avatar-loading").remove();
                }
            })
        }
    }, ".avatar-input"), $("body").on({
        click: function() {
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var e = $("#modal-edit-profile"),
                t = e.find('input[name="firstname"]'),
                lastname = e.find('input[name="lastname"]'),
                n = e.find('input[name="password"]'),
                i = e.find('input[name="old_password"]'),
                r = e.find('[name="biography"]'),
                a = e.find(".login__notice"),
                o = e.find(".modal-close");
            t.val() ? $.ajax({
                type: "POST",
                url: "/ajax/user-update",
                data: {
                    firstname: t.val(),
                    lastname: lastname.val(),
                    password: n.val(),
                    old_password: i.val(),
                    biography: r.val(),
					_token: CSRF_TOKEN
                },
                success: function(e) {
                    "old_password_wrong" == e.error_reason ? a.removeClass("hide").addClass("login__notice--error").html("Eski Şifre Hatalı.") : (a.addClass("hide"), o.click(), n.val(""), i.val(""))
                },
                error: function(e) {
                    console.log(e)
                }
            }) : a.removeClass("hide").addClass("login__notice--error").html("Ad ve Soyad boş bırakılamaz.")
        }
    }, ".login__form-button--signin"), $("body").on({
        click: function() {
            var e = $(this).attr("data-type"),
                t = $(this).attr("data-id"),
                n = $(this).attr("data-action"),
                i = "add" == n ? "remove" : "add",
                r = null,
                a = $(this),
                o = "add" == n ? "Favorilerime eklendi" : "Favorilerimden Çıkarıldı",
                s = "add" == n ? '<i class="material-icons">&#xE866;</i>' : '<i class="material-icons">&#xE867;</i>',
                l = $(this).attr("data-page");
            "add" == n ? r = "/ajax/favorite_add" : "remove" == n && (r = "/ajax/favorite_remove"), $.ajax({
                type: "POST",
                url: r,
                data: {
                    contentId: t,
                    contentType: e
                },
                dataType: "HTML",
                success: function(e) {
                    if ("error" == e) $(".header__appbar--right__user").trigger("click");
                    else {
                        var t = a.parents(".content-favorite");
                        if ("favorite" != l) {
                            var r = $(".content-favorite-notice");
                            $(window).width() >= 768 ? r.length > 0 ? r.find("span").text(o) : t.prepend('<div class="content-favorite-notice" style="width: 182px !important;"><span>' + o + "</span></div>") : (r.length > 0 ? r.find("span").text(o) : $("body").append('<div class="content-favorite-notice"><span>' + o + "</span></div>"), a.html(s));
                            var c = $(".content-favorite-notice");
                            c.stop(!0, !0).fadeIn(300).delay(1500).fadeOut(300, function() {
                                $(this).remove(".content-favorite-notice")
                            }), a.attr("data-action", i), "add" == n ? a.addClass("active") : a.removeClass("active")
                        } else {
                            a.parents(".content-timeline__item").remove();
                            var d = $(".favorite-count").text();
                            if (d - 1 == 0) {
                                var u = '<div class="content-title content-title--others"><h1>Favorilerim</h1><p>Favorilerinize herhangi bir şey eklemediniz :(</p></div><ul class="search-notice"><li>Favorilerinize haber, video veya liste eklememiÅŸsiniz.</li></ul>';
                                $(".content").html(u)
                            } else $(".favorite-count").html(d - 1)
                        }
                    }
                },
                error: function(e) {
                    console.log(e)
                }
            })
        }
    }, ".favorite"), checkFavorite = function(e) {
        var t = e.attr("data-id"),
            n = e.attr("data-type"),
            i = e.find(".content-favorite__button.favorite"),
            r = '<i class="material-icons">&#xE866;</i>';
        $.ajax({
			headers: {
				  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
            type: "GET",
            url: "/ajax/favorite_check",
            data: {
                contentId: t,
                contentType: n
            },
            dataType: "HTML",
            success: function(e) {
                "true" == e && ($(window).width() >= 768 ? i.addClass("active") : i.html(r), i.attr("data-action", "remove"))
            },
            error: function(e) {
                console.log(e)
            }
        })
    }, checkFavorite($("article")), $("body").on({
        mouseenter: function() {
            var e = $(this),
                t = e.find(".favorite").hasClass("active") === !1 ? "Favorilerime ekle" : "Favorilerimden Ã§Ä±kar",
                n = $(".content-favorite-notice");
            $(window).width() >= 768 && (n.length > 0 ? (n.find("span").text(t), n.stop(!0, !0).fadeIn(300)) : e.prepend('<div class="content-favorite-notice" style="width: 182px !important;"><span>' + t + "</span></div>"), $(".content-favorite-notice").fadeIn(300))
        },
        mouseleave: function() {
            $(this);
            if ($(window).width() >= 768) {
                var e = $(".content-favorite-notice");
                e.stop(!0, !0).fadeIn(300).delay(1500).fadeOut(300, function() {
                    $(this).remove(".content-favorite-notice")
                })
            }
        }
    }, ".content-favorite"),
    function(e, t) {
        "function" == typeof define && define.amd ? define(["jquery"], function(e) {
            return t(e)
        }) : "object" == typeof module && module.exports ? module.exports = t(require("jquery")) : t(e.jQuery)
    }(this, function(e) {
        ! function(e, t) {
            function n(e, t) {
                this.$form = e, this.$input = t, this.reset(), t.on("change paste", this.reset.bind(this))
            }
            var i = function() {
                    return !1
                },
                r = {
                    numHalted: 0,
                    haltValidation: function(t) {
                        this.numHalted++, e.formUtils.haltValidation = !0, t.unbind("submit", i).bind("submit", i).find('*[type="submit"]').addClass("disabled").attr("disabled", "disabled")
                    },
                    unHaltValidation: function(t) {
                        this.numHalted--, 0 === this.numHalted && (e.formUtils.haltValidation = !1, t.unbind("submit", i).find('*[type="submit"]').removeClass("disabled").removeAttr("disabled", "disabled"))
                    }
                };
            n.prototype.reset = function() {
                this.haltedFormValidation = !1, this.hasRun = !1, this.isRunning = !1, this.result = t
            }, n.prototype.run = function(e, t) {
                return "keyup" === e ? null : this.isRunning ? (this.haltedFormValidation || "submit" !== e || (r.haltValidation(), this.haltedFormValidation = !0), null) : this.hasRun ? this.result : ("submit" === e && (r.haltValidation(this.$form), this.haltedFormValidation = !0), this.isRunning = !0, this.$input.attr("disabled", "disabled").addClass("async-validation"), this.$form.addClass("async-validation"), t(function(e) {
                    this.done(e)
                }.bind(this)), null)
            }, n.prototype.done = function(e) {
                this.result = e, this.hasRun = !0, this.isRunning = !1, this.$input.removeAttr("disabled").removeClass("async-validation"), this.$form.removeClass("async-validation"), this.haltedFormValidation ? (r.unHaltValidation(this.$form), this.$form.trigger("submit")) : this.$input.trigger("validation.revalidate")
            }, e.formUtils = e.extend(e.formUtils || {}, {
                asyncValidation: function(e, t, i) {
                    var r, a = t.get(0);
                    return a.asyncValidators || (a.asyncValidators = {}), a.asyncValidators[e] ? r = a.asyncValidators[e] : (r = new n(i, t), a.asyncValidators[e] = r), r
                }
            })
        }(e),
        function(e, t) {
            "use strict";

            function n(t) {
                t && "custom" === t.errorMessagePosition && "function" == typeof t.errorMessageCustom && (e.formUtils.warn("Use of deprecated function errorMessageCustom, use config.submitErrorMessageCallback instead"), t.submitErrorMessageCallback = function(e, n) {
                    t.errorMessageCustom(e, t.language.errorTitle, n, t)
                })
            }

            function i(t) {
                if (t.errorMessagePosition && "object" == typeof t.errorMessagePosition) {
                    e.formUtils.warn("Deprecated use of config parameter errorMessagePosition, use config.submitErrorMessageCallback instead");
                    var n = t.errorMessagePosition;
                    t.errorMessagePosition = "top", t.submitErrorMessageCallback = function() {
                        return n
                    }
                }
            }

            function r(t) {
                var n = t.find("[data-validation-if-checked]");
                n.length && e.formUtils.warn('Detected use of attribute "data-validation-if-checked" which is deprecated. Use "data-validation-depends-on" provided by module "logic"'), n.on("beforeValidation", function() {
                    var n = e(this),
                        i = n.valAttr("if-checked"),
                        r = e('input[name="' + i + '"]', t),
                        a = r.is(":checked"),
                        o = (e.formUtils.getValue(r) || "").toString(),
                        s = n.valAttr("if-checked-value");
                    (!a || s && s !== o) && n.valAttr("skipped", !0)
                })
            }

            function a(t) {
                var n = {
                    se: "sv",
                    cz: "cs",
                    dk: "da"
                };
                if (t.lang in n) {
                    var i = n[t.lang];
                    e.formUtils.warn('Deprecated use of lang code "' + t.lang + '" use "' + i + '" instead'), t.lang = i
                }
            }
            e.fn.validateForm = function(t, n) {
                return e.formUtils.warn("Use of deprecated function $.validateForm, use $.isValid instead"), this.isValid(t, n, !0)
            }, e(window).on("formValidationPluginInit", function(e, t) {
                a(t), n(t), i(t)
            }).on("validatorsLoaded formValidationSetup", function(t, n) {
                n || (n = e("form")), r(n)
            })
        }(e),
        function(e) {
            "use strict";
            var t = {
                resolveErrorMessage: function(e, t, n, i, r) {
                    var a = i.validationErrorMsgAttribute + "-" + n.replace("validate_", ""),
                        o = e.attr(a);
                    return o || (o = e.attr(i.validationErrorMsgAttribute), o || (o = "function" != typeof t.errorMessageKey ? r[t.errorMessageKey] : r[t.errorMessageKey(i)], o || (o = t.errorMessage))), o
                },
                getParentContainer: function(t) {
                    if (t.valAttr("error-msg-container")) return e(t.valAttr("error-msg-container"));
                    var n = t.parent();
                    if (!n.hasClass("form-group") && !n.closest("form").hasClass("form-horizontal")) {
                        var i = n.closest(".form-group");
                        if (i.length) return i.eq(0)
                    }
                    return n
                },
                applyInputErrorStyling: function(e, t) {
                    e.addClass(t.errorElementClass).removeClass(t.successElementClass), this.getParentContainer(e).addClass(t.inputParentClassOnError).removeClass(t.inputParentClassOnSuccess), "" !== t.borderColorOnError && e.css("border-color", t.borderColorOnError)
                },
                applyInputSuccessStyling: function(e, t) {
                    e.addClass(t.successElementClass), this.getParentContainer(e).addClass(t.inputParentClassOnSuccess)
                },
                removeInputStylingAndMessage: function(e, n) {
                    e.removeClass(n.successElementClass).removeClass(n.errorElementClass).css("border-color", "");
                    var i = t.getParentContainer(e);
                    if (i.removeClass(n.inputParentClassOnError).removeClass(n.inputParentClassOnSuccess), "function" == typeof n.inlineErrorMessageCallback) {
                        var r = n.inlineErrorMessageCallback(e, !1, n);
                        r && r.html("")
                    } else i.find("." + n.errorMessageClass).remove()
                },
                removeAllMessagesAndStyling: function(n, i) {
                    if ("function" == typeof i.submitErrorMessageCallback) {
                        var r = i.submitErrorMessageCallback(n, !1, i);
                        r && r.html("")
                    } else n.find("." + i.errorMessageClass + ".alert").remove();
                    n.find("." + i.errorElementClass + ",." + i.successElementClass).each(function() {
                        t.removeInputStylingAndMessage(e(this), i)
                    })
                },
                setInlineMessage: function(t, n, i) {
                    this.applyInputErrorStyling(t, i);
                    var r, a = document.getElementById(t.attr("name") + "_err_msg"),
                        o = !1,
                        s = function(i) {
                            e.formUtils.$win.trigger("validationErrorDisplay", [t, i]), i.html(n)
                        },
                        l = function() {
                            var a = !1;
                            o.find("." + i.errorMessageClass).each(function() {
                                if (this.inputReferer === t[0]) return a = e(this), !1
                            }), a ? n ? s(a) : a.remove() : "" !== n && (r = e('<div class="' + i.errorMessageClass + ' alert"></div>'), s(r), r[0].inputReferer = t[0], o.prepend(r))
                        };
                    if (a) e.formUtils.warn("Using deprecated element reference " + a.id), o = e(a), l();
                    else if ("function" == typeof i.inlineErrorMessageCallback) {
                        if (o = i.inlineErrorMessageCallback(t, n, i), !o) return;
                        l()
                    } else {
                        var c = this.getParentContainer(t);
                        r = c.find("." + i.errorMessageClass + ".help-block"), 0 === r.length && (r = e("<span></span>").addClass("help-block").addClass(i.errorMessageClass), r.appendTo(c)), s(r)
                    }
                },
                setMessageInTopOfForm: function(t, n, i, r) {
                    var a = '<div class="{errorMessageClass} alert alert-danger"><strong>{errorTitle}</strong><ul>{fields}</ul></div>',
                        o = !1;
                    if ("function" != typeof i.submitErrorMessageCallback || (o = i.submitErrorMessageCallback(t, n, i))) {
                        var s = {
                            errorTitle: r.errorTitle,
                            fields: "",
                            errorMessageClass: i.errorMessageClass
                        };
                        e.each(n, function(e, t) {
                            s.fields += "<li>" + t + "</li>"
                        }), e.each(s, function(e, t) {
                            a = a.replace("{" + e + "}", t)
                        }), o ? o.html(a) : t.children().eq(0).before(e(a))
                    }
                }
            };
            e.formUtils = e.extend(e.formUtils || {}, {
                dialogs: t
            })
        }(e),
        function(e, t, n) {
            "use strict";
            var i = 0;
            e.fn.validateOnBlur = function(t, n) {
                var i = this,
                    r = this.find("*[data-validation]");
                return r.each(function() {
                    var r = e(this);
                    if (r.is("[type=radio]")) {
                        var a = i.find('[type=radio][name="' + r.attr("name") + '"]');
                        a.bind("blur.validation", function() {
                            r.validateInputOnBlur(t, n, !0, "blur")
                        }), n.validateCheckboxRadioOnClick && a.bind("click.validation", function() {
                            r.validateInputOnBlur(t, n, !0, "click")
                        })
                    }
                }), r.bind("blur.validation", function() {
                    e(this).validateInputOnBlur(t, n, !0, "blur")
                }), n.validateCheckboxRadioOnClick && this.find("input[type=checkbox][data-validation],input[type=radio][data-validation]").bind("click.validation", function() {
                    e(this).validateInputOnBlur(t, n, !0, "click")
                }), this
            }, e.fn.validateOnEvent = function(t, n) {
                var i = "FORM" === this[0].nodeName ? this.find("*[data-validation-event]") : this;
                return i.each(function() {
                    var i = e(this),
                        r = i.valAttr("event");
                    r && i.unbind(r + ".validation").bind(r + ".validation", function(i) {
                        9 !== (i || {}).keyCode && e(this).validateInputOnBlur(t, n, !0, r)
                    })
                }), this
            }, e.fn.showHelpOnFocus = function(t) {
                return t || (t = "data-validation-help"), this.find("textarea,input").each(function() {
                    var n = e(this),
                        r = "jquery_form_help_" + ++i,
                        a = n.attr(t);
                    n.removeClass("has-help-text").unbind("focus.help").unbind("blur.help"), a && n.addClass("has-help-txt").bind("focus.help", function() {
                        var t = n.parent().find("." + r);
                        0 === t.length && (t = e("<span />").addClass(r).addClass("help").addClass("help-block").text(a).hide(), n.after(t)), t.fadeIn()
                    }).bind("blur.help", function() {
                        e(this).parent().find("." + r).fadeOut("slow")
                    })
                }), this
            }, e.fn.validate = function(t, n, i) {
                var r = e.extend({}, e.formUtils.LANG, i || {});
                this.each(function() {
                    var i = e(this),
                        a = i.closest("form").get(0) || {},
                        o = a.validationConfig || {};
                    i.one("validation", function(e, n) {
                        "function" == typeof t && t(n, this, e)
                    }), i.validateInputOnBlur(r, e.extend({}, o, n || {}), !0)
                })
            }, e.fn.willPostponeValidation = function() {
                return (this.valAttr("suggestion-nr") || this.valAttr("postpone") || this.hasClass("hasDatepicker")) && !t.postponedValidation
            }, e.fn.validateInputOnBlur = function(n, i, r, a) {
                if (e.formUtils.eventType = a, this.willPostponeValidation()) {
                    var o = this,
                        s = this.valAttr("postpone") || 200;
                    return t.postponedValidation = function() {
                        o.validateInputOnBlur(n, i, r, a), t.postponedValidation = !1
                    }, setTimeout(function() {
                        t.postponedValidation && t.postponedValidation()
                    }, s), this
                }
                n = e.extend({}, e.formUtils.LANG, n || {}), e.formUtils.dialogs.removeInputStylingAndMessage(this, i);
                var l = this,
                    c = l.closest("form"),
                    d = e.formUtils.validateInput(l, n, i, c, a),
                    u = function() {
                        l.validateInputOnBlur(n, i, !1, "blur.revalidated")
                    };
                return "blur" === a && l.unbind("validation.revalidate", u).one("validation.revalidate", u), r && l.removeKeyUpValidation(), d.shouldChangeDisplay && (d.isValid ? e.formUtils.dialogs.applyInputSuccessStyling(l, i) : e.formUtils.dialogs.setInlineMessage(l, d.errorMsg, i)), !d.isValid && r && l.validateOnKeyUp(n, i), this
            }, e.fn.validateOnKeyUp = function(t, n) {
                return this.each(function() {
                    var i = e(this);
                    i.valAttr("has-keyup-event") || i.valAttr("has-keyup-event", "true").bind("keyup.validation", function(e) {
                        9 !== e.keyCode && i.validateInputOnBlur(t, n, !1, "keyup")
                    })
                }), this
            }, e.fn.removeKeyUpValidation = function() {
                return this.each(function() {
                    e(this).valAttr("has-keyup-event", !1).unbind("keyup.validation")
                }), this
            }, e.fn.valAttr = function(e, t) {
                return t === n ? this.attr("data-validation-" + e) : t === !1 || null === t ? this.removeAttr("data-validation-" + e) : (e = e.length > 0 ? "-" + e : "", this.attr("data-validation" + e, t))
            }, e.fn.isValid = function(t, n, i) {
                if (e.formUtils.isLoadingModules) {
                    var r = this;
                    return setTimeout(function() {
                        r.isValid(t, n, i)
                    }, 200), null
                }
                n = e.extend({}, e.formUtils.defaultConfig(), n || {}), t = e.extend({}, e.formUtils.LANG, t || {}), i = i !== !1, e.formUtils.errorDisplayPreventedWhenHalted && (delete e.formUtils.errorDisplayPreventedWhenHalted, i = !1);
                var a = function(t, r) {
                        e.inArray(t, s) < 0 && s.push(t), l.push(r), r.valAttr("current-error", t), i && e.formUtils.dialogs.applyInputErrorStyling(r, n)
                    },
                    o = [],
                    s = [],
                    l = [],
                    c = this,
                    d = function(t, i) {
                        return "submit" === i || "button" === i || "reset" === i || e.inArray(t, n.ignore || []) > -1
                    };
                if (i && e.formUtils.dialogs.removeAllMessagesAndStyling(c, n), c.find("input,textarea,select").filter(':not([type="submit"],[type="button"])').each(function() {
                        var i = e(this),
                            r = i.attr("type"),
                            s = "radio" === r || "checkbox" === r,
                            l = i.attr("name");
                        if (!d(l, r) && (!s || e.inArray(l, o) < 0)) {
                            s && o.push(l);
                            var u = e.formUtils.validateInput(i, t, n, c, "submit");
                            u.isValid ? u.isValid && u.shouldChangeDisplay && (i.valAttr("current-error", !1), e.formUtils.dialogs.applyInputSuccessStyling(i, n)) : a(u.errorMsg, i)
                        }
                    }), "function" == typeof n.onValidate) {
                    var u = n.onValidate(c);
                    e.isArray(u) ? e.each(u, function(e, t) {
                        a(t.message, t.element)
                    }) : u && u.element && u.message && a(u.message, u.element)
                }
                return e.formUtils.isValidatingEntireForm = !1, l.length > 0 && i && ("top" === n.errorMessagePosition ? e.formUtils.dialogs.setMessageInTopOfForm(c, s, n, t) : e.each(l, function(t, i) {
                    e.formUtils.dialogs.setInlineMessage(i, i.valAttr("current-error"), n)
                }), n.scrollToTopOnError && e.formUtils.$win.scrollTop(c.offset().top - 20)), !i && e.formUtils.haltValidation && (e.formUtils.errorDisplayPreventedWhenHalted = !0), 0 === l.length && !e.formUtils.haltValidation
            }, e.fn.restrictLength = function(t) {
                return new e.formUtils.lengthRestriction(this, t), this
            }, e.fn.addSuggestions = function(t) {
                var n = !1;
                return this.find("input").each(function() {
                    var i = e(this);
                    n = e.split(i.attr("data-suggestions")), n.length > 0 && !i.hasClass("has-suggestions") && (e.formUtils.suggest(i, n, t), i.addClass("has-suggestions"))
                }), this
            }
        }(e, window),
        function(e) {
            "use strict";
            e.formUtils = e.extend(e.formUtils || {}, {
                isLoadingModules: !1,
                loadedModules: {},
                registerLoadedModule: function(t) {
                    this.loadedModules[e.trim(t).toLowerCase()] = !0
                },
                hasLoadedModule: function(t) {
                    return e.trim(t).toLowerCase() in this.loadedModules
                },
                loadModules: function(t, n, i) {
                    if (e.formUtils.isLoadingModules) return void setTimeout(function() {
                        e.formUtils.loadModules(t, n, i)
                    }, 100);
                    var r = function(t, n) {
                        var r = e.split(t),
                            a = r.length,
                            o = function() {
                                a--, 0 === a && (e.formUtils.isLoadingModules = !1, "function" == typeof i && i())
                            };
                        a > 0 && (e.formUtils.isLoadingModules = !0);
                        var s = "?_=" + (new Date).getTime(),
                            l = document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0];
                        e.each(r, function(t, i) {
                            if (i = e.trim(i), 0 === i.length || e.formUtils.hasLoadedModule(i)) o();
                            else {
                                var r = n + i + (".js" === i.slice(-3) ? "" : ".js"),
                                    a = document.createElement("SCRIPT");
                                "function" == typeof define && define.amd ? require([r + (".dev.js" === r.slice(-7) ? s : "")], o) : (a.type = "text/javascript", a.onload = o, a.src = r + (".dev.js" === r.slice(-7) ? s : ""), a.onerror = function() {
                                    e.formUtils.warn("Unable to load form validation module " + r), o()
                                }, a.onreadystatechange = function() {
                                    "complete" !== this.readyState && "loaded" !== this.readyState || (o(), this.onload = null, this.onreadystatechange = null)
                                }, l.appendChild(a))
                            }
                        })
                    };
                    if (n) r(t, n);
                    else {
                        var a = function() {
                            var n = !1;
                            return e('script[src*="form-validator"]').each(function() {
                                var e = this.src.split("form-validator")[1].split("node_modules").length > 1;
                                if (!e) return n = this.src.substr(0, this.src.lastIndexOf("/")) + "/", "/" === n && (n = ""), !1
                            }), n !== !1 && (r(t, n), !0)
                        };
                        a() || e(function() {
                            var e = a();
                            e || "function" == typeof i && i()
                        })
                    }
                }
            })
        }(e),
        function(e) {
            "use strict";
            e.split = function(t, n, i) {
                i = void 0 === i || i === !0;
                var r = "[,|" + (i ? "\\s" : "") + "-]\\s*",
                    a = new RegExp(r, "g");
                if ("function" != typeof n) {
                    if (!t) return [];
                    var o = [];
                    return e.each(t.split(n ? n : a), function(t, n) {
                        n = e.trim(n), n.length && o.push(n)
                    }), o
                }
                t && e.each(t.split(a), function(t, i) {
                    if (i = e.trim(i), i.length) return n(i, t)
                })
            }, e.validate = function(t) {
                var n = e.extend(e.formUtils.defaultConfig(), {
                    form: "form",
                    validateOnEvent: !1,
                    validateOnBlur: !0,
                    validateCheckboxRadioOnClick: !0,
                    showHelpOnFocus: !0,
                    addSuggestions: !0,
                    modules: "",
                    onModulesLoaded: null,
                    language: !1,
                    onSuccess: !1,
                    onError: !1,
                    onElementValidate: !1
                });
                if (t = e.extend(n, t || {}), e(window).trigger("formValidationPluginInit", [t]), t.lang && "en" !== t.lang) {
                    var i = "lang/" + t.lang + ".js";
                    t.modules += t.modules.length ? "," + i : i
                }
                e(t.form).each(function(n, i) {
                    i.validationConfig = t;
                    var r = e(i);
                    r.trigger("formValidationSetup", [r, t]), r.find(".has-help-txt").unbind("focus.validation").unbind("blur.validation"), r.removeClass("has-validation-callback").unbind("submit.validation").unbind("reset.validation").find("input[data-validation],textarea[data-validation]").unbind("blur.validation"), r.bind("submit.validation", function(n) {
                        var i = e(this),
                            r = function() {
                                return n.stopImmediatePropagation(), !1
                            };
                        if (e.formUtils.haltValidation) return r();
                        if (e.formUtils.isLoadingModules) return setTimeout(function() {
                            i.trigger("submit.validation")
                        }, 200), r();
                        var a = i.isValid(t.language, t);
                        if (e.formUtils.haltValidation) return r();
                        if (!a || "function" != typeof t.onSuccess) return a || "function" != typeof t.onError ? !!a || r() : (t.onError(i), r());
                        var o = t.onSuccess(i);
                        return o === !1 ? r() : void 0
                    }).bind("reset.validation", function() {
                        e.formUtils.dialogs.removeAllMessagesAndStyling(r, t)
                    }).addClass("has-validation-callback"), t.showHelpOnFocus && r.showHelpOnFocus(), t.addSuggestions && r.addSuggestions(), t.validateOnBlur && (r.validateOnBlur(t.language, t), r.bind("html5ValidationAttrsFound", function() {
                        r.validateOnBlur(t.language, t)
                    })), t.validateOnEvent && r.validateOnEvent(t.language, t)
                }), "" !== t.modules && e.formUtils.loadModules(t.modules, null, function() {
                    "function" == typeof t.onModulesLoaded && t.onModulesLoaded();
                    var n = "string" == typeof t.form ? e(t.form) : t.form;
                    e.formUtils.$win.trigger("validatorsLoaded", [n, t])
                })
            }
        }(e),
        function(e, t) {
            "use strict";
            var n = e(t);
            e.formUtils = e.extend(e.formUtils || {}, {
                $win: n,
                defaultConfig: function() {
                    return {
                        ignore: [],
                        errorElementClass: "error",
                        successElementClass: "valid",
                        borderColorOnError: "#e80a39",
                        errorMessageClass: "form-error",
                        validationRuleAttribute: "data-validation",
                        validationErrorMsgAttribute: "data-validation-error-msg",
                        errorMessagePosition: "inline",
                        errorMessageTemplate: {
                            container: '<div class="{errorMessageClass} alert alert-danger">{messages}</div>',
                            messages: "<strong>{errorTitle}</strong><ul>{fields}</ul>",
                            field: "<li>{msg}</li>"
                        },
                        scrollToTopOnError: !0,
                        dateFormat: "yyyy-mm-dd",
                        addValidClassOnAll: !1,
                        decimalSeparator: ".",
                        inputParentClassOnError: "has-error",
                        inputParentClassOnSuccess: "has-success",
                        validateHiddenInputs: !1,
                        inlineErrorMessageCallback: !1,
                        submitErrorMessageCallback: !1
                    }
                },
                validators: {},
                _events: {
                    load: [],
                    valid: [],
                    invalid: []
                },
                haltValidation: !1,
                addValidator: function(e) {
                    var t = 0 === e.name.indexOf("validate_") ? e.name : "validate_" + e.name;
                    void 0 === e.validateOnKeyUp && (e.validateOnKeyUp = !0), this.validators[t] = e
                },
                warn: function(e) {
                    "console" in t ? "function" == typeof t.console.warn ? t.console.warn(e) : "function" == typeof t.console.log && t.console.log(e) : alert(e)
                },
                getValue: function(e, t) {
                    var n = t ? t.find(e) : e;
                    if (n.length > 0) {
                        var i = n.eq(0).attr("type");
                        return "radio" === i || "checkbox" === i ? n.filter(":checked").val() || "" : n.val() || ""
                    }
                    return !1
                },
                validateInput: function(t, n, i, r, a) {
                    i = i || e.formUtils.defaultConfig(), n = n || e.formUtils.LANG;
                    var o = this.getValue(t);
                    t.valAttr("skipped", !1).one("beforeValidation", function() {
                        (t.attr("disabled") || !t.is(":visible") && !i.validateHiddenInputs) && t.valAttr("skipped", 1)
                    }).trigger("beforeValidation", [o, n, i]);
                    var s = "true" === t.valAttr("optional"),
                        l = !o && s,
                        c = t.attr(i.validationRuleAttribute),
                        d = !0,
                        u = "",
                        f = {
                            isValid: !0,
                            shouldChangeDisplay: !0,
                            errorMsg: ""
                        };
                    if (!c || l || t.valAttr("skipped")) return f.shouldChangeDisplay = i.addValidClassOnAll, f;
                    var p = t.valAttr("ignore");
                    return p && e.each(p.split(""), function(e, t) {
                        o = o.replace(new RegExp("\\" + t, "g"), "")
                    }), e.split(c, function(s) {
                        0 !== s.indexOf("validate_") && (s = "validate_" + s);
                        var l = e.formUtils.validators[s];
                        if (!l) throw new Error('Using undefined validator "' + s + '". Maybe you have forgotten to load the module that "' + s + '" belongs to?');
                        if ("validate_checkbox_group" === s && (t = r.find('[name="' + t.attr("name") + '"]:eq(0)')), ("keyup" !== a || l.validateOnKeyUp) && (d = l.validatorFunction(o, t, i, n, r, a)), !d) return i.validateOnBlur && t.validateOnKeyUp(n, i), u = e.formUtils.dialogs.resolveErrorMessage(t, l, s, i, n), !1
                    }), d === !1 ? (t.trigger("validation", !1), f.errorMsg = u, f.isValid = !1, f.shouldChangeDisplay = !0) : null === d ? f.shouldChangeDisplay = !1 : (t.trigger("validation", !0), f.shouldChangeDisplay = !0), "function" == typeof i.onElementValidate && null !== u && i.onElementValidate(f.isValid, t, r, u), t.trigger("afterValidation", [f, a]), f
                },
                parseDate: function(t, n, i) {
                    var r, a, o, s, l = n.replace(/[a-zA-Z]/gi, "").substring(0, 1),
                        c = "^",
                        d = n.split(l || null);
                    if (e.each(d, function(e, t) {
                            c += (e > 0 ? "\\" + l : "") + "(\\d{" + t.length + "})"
                        }), c += "$", i) {
                        var u = [];
                        e.each(t.split(l), function(e, t) {
                            1 === t.length && (t = "0" + t), u.push(t)
                        }), t = u.join(l)
                    }
                    if (r = t.match(new RegExp(c)), null === r) return !1;
                    var f = function(t, n, i) {
                        for (var r = 0; r < n.length; r++)
                            if (n[r].substring(0, 1) === t) return e.formUtils.parseDateInt(i[r + 1]);
                        return -1
                    };
                    return o = f("m", d, r), a = f("d", d, r), s = f("y", d, r), !(2 === o && a > 28 && (s % 4 !== 0 || s % 100 === 0 && s % 400 !== 0) || 2 === o && a > 29 && (s % 4 === 0 || s % 100 !== 0 && s % 400 === 0) || o > 12 || 0 === o) && (!(this.isShortMonth(o) && a > 30 || !this.isShortMonth(o) && a > 31 || 0 === a) && [s, o, a])
                },
                parseDateInt: function(e) {
                    return 0 === e.indexOf("0") && (e = e.replace("0", "")), parseInt(e, 10)
                },
                isShortMonth: function(e) {
                    return e % 2 === 0 && e < 7 || e % 2 !== 0 && e > 7
                },
                lengthRestriction: function(t, n) {
                    var i = parseInt(n.text(), 10),
                        r = 0,
                        a = function() {
                            var e = t.val().length;
                            if (e > i) {
                                var a = t.scrollTop();
                                t.val(t.val().substring(0, i)), t.scrollTop(a)
                            }
                            r = i - e, r < 0 && (r = 0), n.text(r)
                        };
                    e(t).bind("keydown keyup keypress focus blur", a).bind("cut paste", function() {
                        setTimeout(a, 100)
                    }), e(document).bind("ready", a)
                },
                numericRangeCheck: function(t, n) {
                    var i = e.split(n),
                        r = parseInt(n.substr(3), 10);
                    return 1 === i.length && n.indexOf("min") === -1 && n.indexOf("max") === -1 && (i = [n, n]), 2 === i.length && (t < parseInt(i[0], 10) || t > parseInt(i[1], 10)) ? ["out", i[0], i[1]] : 0 === n.indexOf("min") && t < r ? ["min", r] : 0 === n.indexOf("max") && t > r ? ["max", r] : ["ok"]
                },
                _numSuggestionElements: 0,
                _selectedSuggestion: null,
                _previousTypedVal: null,
                suggest: function(t, i, r) {
                    var a = {
                            css: {
                                maxHeight: "150px",
                                background: "#FFF",
                                lineHeight: "150%",
                                textDecoration: "underline",
                                overflowX: "hidden",
                                overflowY: "auto",
                                border: "#CCC solid 1px",
                                borderTop: "none",
                                cursor: "pointer"
                            },
                            activeSuggestionCSS: {
                                background: "#E9E9E9"
                            }
                        },
                        o = function(e, t) {
                            var n = t.offset();
                            e.css({
                                width: t.outerWidth(),
                                left: n.left + "px",
                                top: n.top + t.outerHeight() + "px"
                            })
                        };
                    r && e.extend(a, r), a.css.position = "absolute", a.css["z-index"] = 9999, t.attr("autocomplete", "off"), 0 === this._numSuggestionElements && n.bind("resize", function() {
                        e(".jquery-form-suggestions").each(function() {
                            var t = e(this),
                                n = t.attr("data-suggest-container");
                            o(t, e(".suggestions-" + n).eq(0))
                        })
                    }), this._numSuggestionElements++;
                    var s = function(t) {
                        var n = t.valAttr("suggestion-nr");
                        e.formUtils._selectedSuggestion = null, e.formUtils._previousTypedVal = null, e(".jquery-form-suggestion-" + n).fadeOut("fast")
                    };
                    return t.data("suggestions", i).valAttr("suggestion-nr", this._numSuggestionElements).unbind("focus.suggest").bind("focus.suggest", function() {
                        e(this).trigger("keyup"), e.formUtils._selectedSuggestion = null
                    }).unbind("keyup.suggest").bind("keyup.suggest", function() {
                        var n = e(this),
                            i = [],
                            r = e.trim(n.val()).toLocaleLowerCase();
                        if (r !== e.formUtils._previousTypedVal) {
                            e.formUtils._previousTypedVal = r;
                            var l = !1,
                                c = n.valAttr("suggestion-nr"),
                                d = e(".jquery-form-suggestion-" + c);
                            if (d.scrollTop(0), "" !== r) {
                                var u = r.length > 2;
                                e.each(n.data("suggestions"), function(e, t) {
                                    var n = t.toLocaleLowerCase();
                                    return n === r ? (i.push("<strong>" + t + "</strong>"), l = !0, !1) : void((0 === n.indexOf(r) || u && n.indexOf(r) > -1) && i.push(t.replace(new RegExp(r, "gi"), "<strong>$&</strong>")))
                                })
                            }
                            l || 0 === i.length && d.length > 0 ? d.hide() : i.length > 0 && 0 === d.length ? (d = e("<div></div>").css(a.css).appendTo("body"), t.addClass("suggestions-" + c), d.attr("data-suggest-container", c).addClass("jquery-form-suggestions").addClass("jquery-form-suggestion-" + c)) : i.length > 0 && !d.is(":visible") && d.show(), i.length > 0 && r.length !== i[0].length && (o(d, n), d.html(""), e.each(i, function(t, i) {
                                e("<div></div>").append(i).css({
                                    overflow: "hidden",
                                    textOverflow: "ellipsis",
                                    whiteSpace: "nowrap",
                                    padding: "5px"
                                }).addClass("form-suggest-element").appendTo(d).click(function() {
                                    n.focus(), n.val(e(this).text()), n.trigger("change"), s(n)
                                })
                            }))
                        }
                    }).unbind("keydown.validation").bind("keydown.validation", function(t) {
                        var n, i, r = t.keyCode ? t.keyCode : t.which,
                            o = e(this);
                        if (13 === r && null !== e.formUtils._selectedSuggestion) {
                            if (n = o.valAttr("suggestion-nr"), i = e(".jquery-form-suggestion-" + n), i.length > 0) {
                                var l = i.find("div").eq(e.formUtils._selectedSuggestion).text();
                                o.val(l), o.trigger("change"), s(o), t.preventDefault()
                            }
                        } else {
                            n = o.valAttr("suggestion-nr"), i = e(".jquery-form-suggestion-" + n);
                            var c = i.children();
                            if (c.length > 0 && e.inArray(r, [38, 40]) > -1) {
                                38 === r ? (null === e.formUtils._selectedSuggestion ? e.formUtils._selectedSuggestion = c.length - 1 : e.formUtils._selectedSuggestion--, e.formUtils._selectedSuggestion < 0 && (e.formUtils._selectedSuggestion = c.length - 1)) : 40 === r && (null === e.formUtils._selectedSuggestion ? e.formUtils._selectedSuggestion = 0 : e.formUtils._selectedSuggestion++, e.formUtils._selectedSuggestion > c.length - 1 && (e.formUtils._selectedSuggestion = 0));
                                var d = i.innerHeight(),
                                    u = i.scrollTop(),
                                    f = i.children().eq(0).outerHeight(),
                                    p = f * e.formUtils._selectedSuggestion;
                                return (p < u || p > u + d) && i.scrollTop(p), c.removeClass("active-suggestion").css("background", "none").eq(e.formUtils._selectedSuggestion).addClass("active-suggestion").css(a.activeSuggestionCSS), t.preventDefault(), !1
                            }
                        }
                    }).unbind("blur.suggest").bind("blur.suggest", function() {
                        s(e(this))
                    }), t
                },
                LANG: {
                    errorTitle: "Form submission failed!",
                    requiredField: "Boş Bırakılmaz.",
                    requiredFields: "You have not answered all required fields",
                    badTime: "You have not given a correct time",
                    badEmail: "Geçersiz e-posta adresi.",
                    badTelephone: "You have not given a correct phone number",
                    badSecurityAnswer: "You have not given a correct answer to the security question",
                    badDate: "You have not given a correct date",
                    lengthBadStart: "The input value must be between ",
                    lengthBadEnd: " karakter.",
                    lengthTooLongStart: "Max ",
                    lengthTooShortStart: "Minumum ",
                    notConfirmed: "Şifrenizi doğrulayınız.",
                    badDomain: "Incorrect domain value",
                    badUrl: "The input value is not a correct URL",
                    badCustomVal: "The input value is incorrect",
                    andSpaces: " and spaces ",
                    badInt: "Sayısal bir değer giriniz.",
                    badSecurityNumber: "Your social security number was incorrect",
                    badUKVatAnswer: "Incorrect UK VAT Number",
                    badUKNin: "Incorrect UK NIN",
                    badUKUtr: "Incorrect UK UTR Number",
                    badStrength: "The password isn't strong enough",
                    badNumberOfSelectedOptionsStart: "You have to choose at least ",
                    badNumberOfSelectedOptionsEnd: " answers",
                    badAlphaNumeric: "The input value can only contain alphanumeric characters ",
                    badAlphaNumericExtra: " and ",
                    wrongFileSize: "The file you are trying to upload is too large (max %s)",
                    wrongFileType: "Only files of type %s is allowed",
                    groupCheckedRangeStart: "Please choose between ",
                    groupCheckedTooFewStart: "Please choose at least ",
                    groupCheckedTooManyStart: "Please choose a maximum of ",
                    groupCheckedEnd: " item(s)",
                    badCreditCard: "The credit card number is not correct",
                    badCVV: "The CVV number was not correct",
                    wrongFileDim: "Incorrect image dimensions,",
                    imageTooTall: "the image can not be taller than",
                    imageTooWide: "the image can not be wider than",
                    imageTooSmall: "the image was too small",
                    min: "min",
                    max: "max",
                    imageRatioNotAccepted: "Image ratio is not be accepted",
                    badBrazilTelephoneAnswer: "The phone number entered is invalid",
                    badBrazilCEPAnswer: "The CEP entered is invalid",
                    badBrazilCPFAnswer: "The CPF entered is invalid",
                    badPlPesel: "The PESEL entered is invalid",
                    badPlNip: "The NIP entered is invalid",
                    badPlRegon: "The REGON entered is invalid",
                    badreCaptcha: "Lütfen bot olmadığınızı doğrulayın. (Özür Dileriz)",
                    passwordComplexityStart: "Password must contain at least ",
                    passwordComplexitySeparator: ", ",
                    passwordComplexityUppercaseInfo: " uppercase letter(s)",
                    passwordComplexityLowercaseInfo: " lowercase letter(s)",
                    passwordComplexitySpecialCharsInfo: " special character(s)",
                    passwordComplexityNumericCharsInfo: " numeric character(s)",
                    passwordComplexityEnd: "."
                }
            })
        }(e, window),
        function(e) {
            e.formUtils.addValidator({
                name: "email",
                validatorFunction: function(t) {
                    var n = t.toLowerCase().split("@"),
                        i = n[0],
                        r = n[1];
                    if (i && r) {
                        if (0 === i.indexOf('"')) {
                            var a = i.length;
                            if (i = i.replace(/\"/g, ""), i.length !== a - 2) return !1
                        }
                        return e.formUtils.validators.validate_domain.validatorFunction(n[1]) && 0 !== i.indexOf(".") && "." !== i.substring(i.length - 1, i.length) && i.indexOf("..") === -1 && !/[^\w\+\.\-\#\-\_\~\!\$\&\'\(\)\*\+\,\;\=\:]/.test(i)
                    }
                    return !1
                },
                errorMessage: "",
                errorMessageKey: "badEmail"
            }), e.formUtils.addValidator({
                name: "domain",
                validatorFunction: function(e) {
                    return e.length > 0 && e.length <= 253 && !/[^a-zA-Z0-9]/.test(e.slice(-2)) && !/[^a-zA-Z0-9]/.test(e.substr(0, 1)) && !/[^a-zA-Z0-9\.\-]/.test(e) && 1 === e.split("..").length && e.split(".").length > 1
                },
                errorMessage: "",
                errorMessageKey: "badDomain"
            }), e.formUtils.addValidator({
                name: "required",
                validatorFunction: function(t, n, i, r, a) {
                    switch (n.attr("type")) {
                        case "checkbox":
                            return n.is(":checked");
                        case "radio":
                            return a.find('input[name="' + n.attr("name") + '"]').filter(":checked").length > 0;
                        default:
                            return "" !== e.trim(t)
                    }
                },
                errorMessage: "",
                errorMessageKey: function(e) {
                    return "top" === e.errorMessagePosition || "function" == typeof e.errorMessagePosition ? "requiredFields" : "requiredField"
                }
            }), e.formUtils.addValidator({
                name: "length",
                validatorFunction: function(t, n, i, r) {
                    var a = n.valAttr("length"),
                        o = n.attr("type");
                    if (void 0 === a) return alert('Please add attribute "data-validation-length" to ' + n[0].nodeName + " named " + n.attr("name")), !0;
                    var s, l = "file" === o && void 0 !== n.get(0).files ? n.get(0).files.length : t.length,
                        c = e.formUtils.numericRangeCheck(l, a);
                    switch (c[0]) {
                        case "out":
                            this.errorMessage = r.lengthBadStart + a + r.lengthBadEnd, s = !1;
                            break;
                        case "min":
                            n.hasClass("phone") ? this.errorMessage = "Telefon numarasÄ± deÄŸil." : n.hasClass("card-number") ? this.errorMessage = "Kredi kartÄ± deÄŸil." : this.errorMessage = r.lengthTooShortStart + c[1] + r.lengthBadEnd, s = !1;
                            break;
                        case "max":
                            this.errorMessage = r.lengthTooLongStart + c[1] + r.lengthBadEnd, s = !1;
                            break;
                        default:
                            s = !0
                    }
                    return s
                },
                errorMessage: "",
                errorMessageKey: ""
            }), e.formUtils.addValidator({
                name: "url",
                validatorFunction: function(t) {
                    var n = /^(https?|ftp):\/\/((((\w|-|\.|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])(\w|-|\.|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])(\w|-|\.|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/(((\w|-|\.|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/((\w|-|\.|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|\[|\]|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#(((\w|-|\.|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
                    if (n.test(t)) {
                        var i = t.split("://")[1],
                            r = i.indexOf("/");
                        return r > -1 && (i = i.substr(0, r)), e.formUtils.validators.validate_domain.validatorFunction(i)
                    }
                    return !1
                },
                errorMessage: "",
                errorMessageKey: "badUrl"
            }), e.formUtils.addValidator({
                name: "number",
                validatorFunction: function(e, t, n) {
                    if ("" !== e) {
                        var i, r, a = t.valAttr("allowing") || "",
                            o = t.valAttr("decimal-separator") || n.decimalSeparator,
                            s = !1,
                            l = t.valAttr("step") || "",
                            c = !1,
                            d = t.attr("data-sanitize") || "",
                            u = d.match(/(^|[\s])numberFormat([\s]|$)/i);
                        if (u) {
                            if (!window.numeral) throw new ReferenceError("The data-sanitize value numberFormat cannot be used without the numeral library. Please see Data Validation in http://www.formvalidator.net for more information.");
                            e.length && (e = String(numeral().unformat(e)))
                        }
                        if (a.indexOf("number") === -1 && (a += ",number"), a.indexOf("negative") === -1 && 0 === e.indexOf("-")) return !1;
                        if (a.indexOf("range") > -1 && (i = parseFloat(a.substring(a.indexOf("[") + 1, a.indexOf(";"))), r = parseFloat(a.substring(a.indexOf(";") + 1, a.indexOf("]"))), s = !0), "" !== l && (c = !0), "," === o) {
                            if (e.indexOf(".") > -1) return !1;
                            e = e.replace(",", ".")
                        }
                        if ("" === e.replace(/[0-9-]/g, "") && (!s || e >= i && e <= r) && (!c || e % l === 0)) return !0;
                        if (a.indexOf("float") > -1 && null !== e.match(new RegExp("^([0-9-]+)\\.([0-9]+)$")) && (!s || e >= i && e <= r) && (!c || e % l === 0)) return !0
                    }
                    return !1
                },
                errorMessage: "",
                errorMessageKey: "badInt"
            }), e.formUtils.addValidator({
                name: "alphanumeric",
                validatorFunction: function(t, n, i, r) {
                    var a = "^([a-zA-Z0-9",
                        o = "]+)$",
                        s = n.valAttr("allowing"),
                        l = "",
                        c = !1;
                    if (s) {
                        l = a + s + o;
                        var d = s.replace(/\\/g, "");
                        d.indexOf(" ") > -1 && (c = !0, d = d.replace(" ", ""), d += r.andSpaces || e.formUtils.LANG.andSpaces), r.badAlphaNumericAndExtraAndSpaces && r.badAlphaNumericAndExtra ? c ? this.errorMessage = r.badAlphaNumericAndExtraAndSpaces + d : this.errorMessage = r.badAlphaNumericAndExtra + d + r.badAlphaNumericExtra : this.errorMessage = r.badAlphaNumeric + r.badAlphaNumericExtra + d
                    } else l = a + o, this.errorMessage = r.badAlphaNumeric;
                    return new RegExp(l).test(t)
                },
                errorMessage: "",
                errorMessageKey: ""
            }), e.formUtils.addValidator({
                name: "custom",
                validatorFunction: function(e, t) {
                    var n = new RegExp(t.valAttr("regexp"));
                    return n.test(e)
                },
                errorMessage: "",
                errorMessageKey: "badCustomVal"
            }), e.formUtils.addValidator({
                name: "date",
                validatorFunction: function(t, n, i) {
                    var r = n.valAttr("format") || i.dateFormat || "yyyy-mm-dd",
                        a = "false" === n.valAttr("require-leading-zero");
                    return e.formUtils.parseDate(t, r, a) !== !1
                },
                errorMessage: "",
                errorMessageKey: "badDate"
            }), e.formUtils.addValidator({
                name: "checkbox_group",
                validatorFunction: function(t, n, i, r, a) {
                    var o = !0,
                        s = n.attr("name"),
                        l = e('input[type=checkbox][name^="' + s + '"]', a),
                        c = l.filter(":checked").length,
                        d = n.valAttr("qty");
                    if (void 0 === d) {
                        var u = n.get(0).nodeName;
                        alert('Attribute "data-validation-qty" is missing from ' + u + " named " + n.attr("name"))
                    }
                    var f = e.formUtils.numericRangeCheck(c, d);
                    switch (f[0]) {
                        case "out":
                            this.errorMessage = r.groupCheckedRangeStart + d + r.groupCheckedEnd, o = !1;
                            break;
                        case "min":
                            this.errorMessage = r.groupCheckedTooFewStart + f[1] + (r.groupCheckedTooFewEnd || r.groupCheckedEnd), o = !1;
                            break;
                        case "max":
                            this.errorMessage = r.groupCheckedTooManyStart + f[1] + (r.groupCheckedTooManyEnd || r.groupCheckedEnd), o = !1;
                            break;
                        default:
                            o = !0
                    }
                    if (!o) {
                        var p = function() {
                            l.unbind("click", p), l.filter("*[data-validation]").validateInputOnBlur(r, i, !1, "blur")
                        };
                        l.bind("click", p)
                    }
                    return o
                }
            })
        }(e)
    }), ! function(e, t) {
        "function" == typeof define && define.amd ? define(["jquery"], function(e) {
            return t(e)
        }) : "object" == typeof exports ? module.exports = t(require("jquery")) : t(jQuery)
    }(this, function(e) {
        ! function(e, t) {
            "use strict";
            e.formUtils.addValidator({
                name: "spamcheck",
                validatorFunction: function(e, t) {
                    var n = t.valAttr("captcha");
                    return n === e
                },
                errorMessage: "",
                errorMessageKey: "badSecurityAnswer"
            }), e.formUtils.addValidator({
                name: "confirmation",
                validatorFunction: function(t, n, i, r, a) {
                    var o, s = n.valAttr("confirm") || n.attr("name") + "_confirmation",
                        l = a.find('[name="' + s + '"]');
                    if (!l.length) return e.formUtils.warn('Password confirmation validator: could not find an input with name "' + s + '"'), !1;
                    if (o = l.val(), i.validateOnBlur && !l[0].hasValidationCallback) {
                        l[0].hasValidationCallback = !0;
                        var c = function() {
                            n.validate()
                        };
                        l.on("keyup", c), a.one("formValidationSetup", function() {
                            l[0].hasValidationCallback = !1, l.off("keyup", c)
                        })
                    }
                    return t === o
                },
                errorMessage: "",
                errorMessageKey: "notConfirmed"
            });
            var n = {
                    amex: [15, 15],
                    diners_club: [14, 14],
                    cjb: [16, 16],
                    laser: [16, 19],
                    visa: [16, 16],
                    mastercard: [16, 16],
                    maestro: [12, 19],
                    discover: [16, 16]
                },
                i = !1,
                r = !1;
            e.formUtils.addValidator({
                name: "creditcard",
                validatorFunction: function(t, a) {
                    var o = e.split(a.valAttr("allowing") || "");
                    if (r = e.inArray("amex", o) > -1, i = r && 1 === o.length, o.length > 0) {
                        var s = !1;
                        if (e.each(o, function(i, r) {
                                if (r in n) {
                                    if (t.length >= n[r][0] && t.length <= n[r][1]) return s = !0, !1
                                } else e.formUtils.warn('Use of unknown credit card "' + r + '"')
                            }), !s) return !1
                    }
                    if ("" !== t.replace(new RegExp("[0-9]", "g"), "")) return !1;
                    var l = 0;
                    return e.each(t.split("").reverse(), function(e, t) {
                        t = parseInt(t, 10), e % 2 === 0 ? l += t : (t *= 2, l += t < 10 ? t : t - 9)
                    }), l % 10 === 0
                },
                errorMessage: "",
                errorMessageKey: "badCreditCard"
            }), e.formUtils.addValidator({
                name: "cvv",
                validatorFunction: function(e) {
                    return "" === e.replace(/[0-9]/g, "") && (e += "", i ? 4 === e.length : r ? 3 === e.length || 4 === e.length : 3 === e.length)
                },
                errorMessage: "",
                errorMessageKey: "badCVV"
            }), e.formUtils.addValidator({
                name: "strength",
                validatorFunction: function(t, n) {
                    var i = n.valAttr("strength") || 2;
                    return i && i > 3 && (i = 3), e.formUtils.validators.validate_strength.calculatePasswordStrength(t) >= i
                },
                errorMessage: "",
                errorMessageKey: "badStrength",
                calculatePasswordStrength: function(e) {
                    if (e.length < 4) return 0;
                    var t = 0,
                        n = function(e, t) {
                            for (var n = "", i = 0; i < t.length; i++) {
                                for (var r = !0, a = 0; a < e && a + i + e < t.length; a++) r = r && t.charAt(a + i) === t.charAt(a + i + e);
                                a < e && (r = !1), r ? (i += e - 1, r = !1) : n += t.charAt(i)
                            }
                            return n
                        };
                    return t += 4 * e.length, t += 1 * (n(1, e).length - e.length), t += 1 * (n(2, e).length - e.length), t += 1 * (n(3, e).length - e.length), t += 1 * (n(4, e).length - e.length), e.match(/(.*[0-9].*[0-9].*[0-9])/) && (t += 5), e.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/) && (t += 5), e.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/) && (t += 10), e.match(/([a-zA-Z])/) && e.match(/([0-9])/) && (t += 15), e.match(/([!,@,#,$,%,^,&,*,?,_,~])/) && e.match(/([0-9])/) && (t += 15), e.match(/([!,@,#,$,%,^,&,*,?,_,~])/) && e.match(/([a-zA-Z])/) && (t += 15), (e.match(/^\w+$/) || e.match(/^\d+$/)) && (t -= 10), t < 0 && (t = 0), t > 100 && (t = 100), t < 20 ? 0 : t < 40 ? 1 : t <= 60 ? 2 : 3
                },
                strengthDisplay: function(t, n) {
                    var i = {
                        fontSize: "12pt",
                        padding: "4px",
                        bad: "Very bad",
                        weak: "Weak",
                        good: "Good",
                        strong: "Strong"
                    };
                    n && e.extend(i, n), t.bind("keyup", function() {
                        var t = e(this).val(),
                            n = "undefined" == typeof i.parent ? e(this).parent() : e(i.parent),
                            r = n.find(".strength-meter"),
                            a = e.formUtils.validators.validate_strength.calculatePasswordStrength(t),
                            o = {
                                background: "pink",
                                color: "#FF0000",
                                fontWeight: "bold",
                                border: "red solid 1px",
                                borderWidth: "0px 0px 4px",
                                display: "inline-block",
                                fontSize: i.fontSize,
                                padding: i.padding
                            },
                            s = i.bad;
                        0 === r.length && (r = e("<span></span>"), r.addClass("strength-meter").appendTo(n)), t ? r.show() : r.hide(), 1 === a ? s = i.weak : 2 === a ? (o.background = "lightyellow", o.borderColor = "yellow", o.color = "goldenrod", s = i.good) : a >= 3 && (o.background = "lightgreen", o.borderColor = "darkgreen", o.color = "darkgreen", s = i.strong), r.css(o).text(s)
                    })
                }
            });
            var a = function(t, n, i, r, a) {
                    var o = n.valAttr("req-params") || n.data("validation-req-params") || {},
                        s = function(t, i) {
                            t.valid ? n.valAttr("backend-valid", "true") : (n.valAttr("backend-invalid", "true"), t.message && n.attr(r.validationErrorMsgAttribute, t.message)), n.valAttr("validate-backend-on-change") || n.valAttr("validate-backend-on-change", "1").bind("keyup change", function(t) {
                                9 !== t.keyCode && 16 !== t.keyCode && e(this).valAttr("backend-valid", !1).valAttr("backend-invalid", !1)
                            }), i()
                        };
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
					o || (o = {}), "string" == typeof o && (o = e.parseJSON(o)), o[n.valAttr("param-name") || n.attr("name")] = i, 
					e.ajax({
                        url: t,
                        type: "POST",
                        cache: !1,
                        data: {
							o: o,
							_token: CSRF_TOKEN
						},
                        dataType: "json",
                        error: function(e) {
                            return s({
                                valid: !1,
                                message: "Durumla bağlantı başarısız oldu: " + e.statusText
                            }, a), !1
                        },
                        success: function(e) {
                            s(e, a)
                        }
                    })
                },
                o = 0,
                s = function() {
                    return !1
                };
            e.formUtils.addValidator({
                name: "server",
                validatorFunction: function(t, n, i, r, l) {
                    var c = n.valAttr("backend-valid"),
                        d = n.valAttr("backend-invalid"),
                        u = document.location.href;
                    return n.valAttr("url") ? u = n.valAttr("url") : "serverURL" in i && (u = i.backendUrl), !!c || !d && ("keyup" !== e.formUtils.eventType || e.formUtils.isValidatingEntireForm ? (l.addClass("validating-server-side"), n.addClass("validating-server-side"), e.formUtils.isValidatingEntireForm ? (console.log("in here"), l.bind("submit", s), e.formUtils.haltValidation = !0, o++, a(u, n, t, i, function() {
                        o--, l.unbind("submit", s).removeClass("validating-server-side").removeClass("on-blur").get(0).onsubmit = function() {}, n.removeClass("validating-server-side").valAttr("value-length", t.length), 0 === o && (e.formUtils.haltValidation = !1, l.trigger("submit"))
                    }), null) : (a(u, n, t, i, function() {
                        l.removeClass("validating-server-side"), n.removeClass("validating-server-side"), n.trigger("blur")
                    }), null)) : null)
                },
                errorMessage: "",
                errorMessageKey: "badBackend"
            }), e.formUtils.addValidator({
                name: "letternumeric",
                validatorFunction: function(t, n, i, r) {
                    var a = "^([a-zA-Z0-9ÂªÂµÂºÃ€-Ã–Ã˜-Ã¶Ã¸-ËË†-Ë‘Ë -Ë¤Ë¬Ë®Í°-Ê¹Í¶Í·Íº-Í½Î†Îˆ-ÎŠÎŒÎŽ-Î¡Î£-ÏµÏ·-ÒÒŠ-Ô§Ô±-Õ–Õ™Õ¡-Ö‡×-×ª×°-×²Ø -ÙŠÙ®Ù¯Ù±-Û“Û•Û¥Û¦Û®Û¯Ûº-Û¼Û¿ÜÜ’-Ü¯Ý-Þ¥Þ±ßŠ-ßªß´ßµßºà €-à •à šà ¤à ¨à¡€-à¡˜à¢ à¢¢-à¢¬à¤„-à¤¹à¤½à¥à¤•à¤¼-à¥¡à¥±-à¥·à¥¹-à¥¿à¦…-à¦Œà¦à¦à¦“-à¦¨à¦ª-à¦°à¦²à¦¶-à¦¹à¦½à§Žà¦¡à¦¼à¦¢à¦¼à¦¯à¦¼-à§¡à§°à§±à¨…-à¨Šà¨à¨à¨“-à¨¨à¨ª-à¨°à¨²à¨²à¨¼à¨µà¨¸à¨¼à¨¸à¨¹à¨–à¨¼-à©œà¨«à¨¼à©²-à©´àª…-àªàª-àª‘àª“-àª¨àªª-àª°àª²àª³àªµ-àª¹àª½à«à« à«¡à¬…-à¬Œà¬à¬à¬“-à¬¨à¬ª-à¬°à¬²à¬³à¬µ-à¬¹à¬½à¬¡à¬¼à¬¢à¬¼à­Ÿ-à­¡à­±à®ƒà®…-à®Šà®Ž-à®à®’-à®•à®™à®šà®œà®žà®Ÿà®£à®¤à®¨-à®ªà®®-à®¹à¯à°…-à°Œà°Ž-à°à°’-à°¨à°ª-à°³à°µ-à°¹à°½à±˜à±™à± à±¡à²…-à²Œà²Ž-à²à²’-à²¨à²ª-à²³à²µ-à²¹à²½à³žà³ à³¡à³±à³²à´…-à´Œà´Ž-à´à´’-à´ºà´½àµŽàµ àµ¡àµº-àµ¿à¶…-à¶–à¶š-à¶±à¶³-à¶»à¶½à·€-à·†à¸-à¸°à¸²à¸³à¹€-à¹†àºàº‚àº„àº‡àºˆàºŠàºàº”-àº—àº™-àºŸàº¡-àº£àº¥àº§àºªàº«àº­-àº°àº²àº³àº½à»€-à»„à»†à»œ-à»Ÿà¼€à½€-à½‡à½‰-à½¬à¾ˆ-à¾Œá€€-á€ªá€¿á-á•áš-áá¡á¥á¦á®-á°áµ-á‚á‚Žá‚ -áƒ…áƒ‡áƒáƒ-áƒºáƒ¼-á‰ˆá‰Š-á‰á‰-á‰–á‰˜á‰š-á‰á‰ -áŠˆáŠŠ-áŠáŠ-áŠ°áŠ²-áŠµáŠ¸-áŠ¾á‹€á‹‚-á‹…á‹ˆ-á‹–á‹˜-áŒáŒ’-áŒ•áŒ˜-ášáŽ€-áŽáŽ -á´á-á™¬á™¯-á™¿áš-áššáš -á›ªáœ€-áœŒáœŽ-áœ‘áœ -áœ±á€-á‘á -á¬á®-á°áž€-áž³áŸ—áŸœá  -á¡·á¢€-á¢¨á¢ªá¢°-á£µá¤€-á¤œá¥-á¥­á¥°-á¥´á¦€-á¦«á§-á§‡á¨€-á¨–á¨ -á©”áª§á¬…-á¬³á­…-á­‹á®ƒ-á® á®®á®¯á®º-á¯¥á°€-á°£á±-á±á±š-á±½á³©-á³¬á³®-á³±á³µá³¶á´€-á¶¿á¸€-á¼•á¼˜-á¼á¼ -á½…á½ˆ-á½á½-á½—á½™á½›á½á½Ÿ-ÏŽá¾€-á¾´á¾¶-á¾¼Î¹á¿‚-á¿„á¿†-á¿Œá¿-Îá¿–-ÎŠá¿ -á¿¬á¿²-á¿´á¿¶-á¿¼â±â¿â‚-â‚œâ„‚â„‡â„Š-â„“â„•â„™-â„â„¤Î©â„¨K-â„­â„¯-â„¹â„¼-â„¿â……-â…‰â…Žâ†ƒâ†„â°€-â°®â°°-â±žâ± -â³¤â³«-â³®â³²â³³â´€-â´¥â´§â´­â´°-âµ§âµ¯â¶€-â¶–â¶ -â¶¦â¶¨-â¶®â¶°-â¶¶â¶¸-â¶¾â·€-â·†â·ˆ-â·Žâ·-â·–â·˜-â·žâ¸¯ã€…ã€†ã€±-ã€µã€»ã€¼ã-ã‚–ã‚-ã‚Ÿã‚¡-ãƒºãƒ¼-ãƒ¿ã„…-ã„­ã„±-ã†Žã† -ã†ºã‡°-ã‡¿ã€-ä¶µä¸€-é¿Œê€€-ê’Œê“-ê“½ê”€-ê˜Œê˜-ê˜Ÿê˜ªê˜«ê™€-ê™®ê™¿-êš—êš -ê›¥êœ—-êœŸêœ¢-êžˆêž‹-êžŽêž-êž“êž -êžªêŸ¸-ê ê ƒ-ê …ê ‡-ê Šê Œ-ê ¢ê¡€-ê¡³ê¢‚-ê¢³ê£²-ê£·ê£»ê¤Š-ê¤¥ê¤°-ê¥†ê¥ -ê¥¼ê¦„-ê¦²ê§ê¨€-ê¨¨ê©€-ê©‚ê©„-ê©‹ê© -ê©¶ê©ºêª€-êª¯êª±êªµêª¶êª¹-êª½ê«€ê«‚ê«›-ê«ê« -ê«ªê«²-ê«´ê¬-ê¬†ê¬‰-ê¬Žê¬‘-ê¬–ê¬ -ê¬¦ê¬¨-ê¬®ê¯€-ê¯¢ê°€-íž£íž°-íŸ†íŸ‹-íŸ»è±ˆ-ï©­ï©°-ï«™ï¬€-ï¬†ï¬“-ï¬—×™Ö´×²Ö·-ï¬¨×©×-×–Ö¼×˜Ö¼-×œÖ¼×žÖ¼× Ö¼×¡Ö¼×£Ö¼×¤Ö¼×¦Ö¼-ï®±ï¯“-ï´½ïµ-ï¶ï¶’-ï·‡ï·°-ï·»ï¹°-ï¹´ï¹¶-ï»¼ï¼¡-ï¼ºï½-ï½šï½¦-ï¾¾ï¿‚-ï¿‡ï¿Š-ï¿ï¿’-ï¿—ï¿š-ï¿œ",
                        o = "]+)$",
                        s = n.valAttr("allowing"),
                        l = "";
                    if (s) {
                        l = a + s + o;
                        var c = s.replace(/\\/g, "");
                        c.indexOf(" ") > -1 && (c = c.replace(" ", ""), c += r.andSpaces || e.formUtils.LANG.andSpaces), this.errorMessage = r.badAlphaNumeric + r.badAlphaNumericExtra + c
                    } else l = a + o, this.errorMessage = r.badAlphaNumeric;
                    return new RegExp(l).test(t)
                },
                errorMessage: "",
                errorMessageKey: "requiredFields"
            }), e.formUtils.addValidator({
                name: "recaptcha",
                validatorFunction: function(e, t) {
                    return "" !== grecaptcha.getResponse(t.valAttr("recaptcha-widget-id"))
                },
                errorMessage: "",
                errorMessageKey: "badreCaptcha"
            }), e.fn.displayPasswordStrength = function(t) {
                return new e.formUtils.validators.validate_strength.strengthDisplay(this, t), this
            };
            var l = function(t, n, i) {
                if (n || (n = e("form")), "undefined" != typeof grecaptcha && !e.formUtils.hasLoadedGrecaptcha) throw new Error("reCaptcha API can not be loaded by hand, delete reCaptcha API snippet.");
                if (!e.formUtils.hasLoadedGrecaptcha && e('[data-validation~="recaptcha"]', n).length) {
                    e.formUtils.hasLoadedGrecaptcha = !0;
                    var r = "//www.google.com/recaptcha/api.js?onload=reCaptchaLoaded&render=explicit" + (i.lang ? "&hl=" + i.lang : ""),
                        a = document.createElement("script");
                    a.type = "text/javascript", a.async = !0, a.defer = !0, a.src = r, document.getElementsByTagName("body")[0].appendChild(a)
                }
            };
            t.reCaptchaLoaded = function(t) {
                t && "object" == typeof t && t.length || (t = e("form")), t.each(function() {
                    var t = e(this),
                        n = t.context.validationConfig;
                    e('[data-validation~="recaptcha"]', t).each(function() {
                        var t = e(this),
                            i = document.createElement("DIV"),
                            r = n.reCaptchaSiteKey || t.valAttr("recaptcha-sitekey"),
                            a = n.reCaptchaTheme || t.valAttr("recaptcha-theme") || "light";
                        if (!r) throw new Error("Google reCaptcha site key is required.");
                        var o = function(t) {
                                e("form").each(function() {
                                    e('[data-validation~="recaptcha"]', e(this)).each(function() {
                                        e(this).trigger("validation", t && "" !== t)
                                    })
                                })
                            },
                            s = grecaptcha.render(i, {
                                sitekey: r,
                                theme: a,
                                callback: o,
                                "expired-callback": o
                            });
                        t.valAttr("recaptcha-widget-id", s).hide().on("beforeValidation", function(e) {
                            e.stopImmediatePropagation()
                        }).parent().append(i)
                    })
                })
            }, e(t).on("validatorsLoaded formValidationSetup", l)
        }(e, window)
    }), $(function() {
        $.fn.hasAttr = function(e) {
            var t = this.attr(e);
            return "undefined" != typeof t && t !== !1
        }, $.fn.materialForm = function() {
            function e(e) {
                var t = e.attr("type");
                return "hidden" != t && "submit" != t && "checkbox" != t && "radio" != t && "file" != t ? 1 : 0
            }

            function t(e, t) {
                var n = e.attr("type");
                return n == t
            }

            function n(e) {
                e.val() ? e.addClass("filled") : e.removeClass("filled")
            }
            this.find("input, textarea").each(function(i) {
                if (0 == $(this).closest(".material-input").length && e($(this))) {
                    var r = $(this).attr("name");
                    $(this).attr("id", r);
                    var a = $(this).wrap("<div class='material-input'></div>").parent();
                    a.append("<span class='material-bar'></span>");
                    var o = $(this).prop("tagName").toLowerCase();
                    a.addClass(o);
                    var s = $(this).attr("placeholder");
                    s && (a.append("<label for='" + r + "'>" + s + "</label>"), $(this).removeAttr("placeholder")), n($(this))
                }
                if (0 == $(this).closest(".material-group").length && (t($(this), "radio") || t($(this), "checkbox"))) {
                    var r = $(this).attr("name").replace("[]", ""),
                        l = "material-group-" + r,
                        s = $(this).attr("placeholder"),
                        c = r + "-" + i,
                        d = $('<label for="' + c + '">' + s + "</label>"),
                        u = $('<div class="material-group-item"></div>');
                    if ($(this).attr("id", c), $("#" + l + $(this).val()).length) {
                        var f = $("#" + l + $(this).val());
                        f.append($(this))
                    } else var f = $(this).wrap("<div class='material-group' id='" + l + "'></div>").parent();
                    if (t($(this), "radio")) var p = $('<div class="material-radio"></div>');
                    else var p = $('<div class="material-checkbox"></div>');
                    u.append($(this)), u.append(d), u.append(p), f.append(u)
                }
            }), this.find("input, textarea").on("blur", function() {
                e($(this)) && n($(this))
            }), this.find("select").each(function(e) {
                if (0 == $(this).closest(".material-select").length) {
                    var t = $(this).attr("placeholder"),
                        n = $(this).attr("multiple") ? "checkbox" : "radio",
                        i = id = $(this).attr("name"),
                        r = $(this).wrap("<div class='material-select'></div>").parent();
                    if ("checkbox" == n) {
                        i += "[]";
                        var a = $('<span class="material-bar"></span>');
                        r.append(a).addClass("checkbox")
                    } else {
                        var o = $('<span class="material-title">' + t + "</span>");
                        r.prepend(o)
                    }
                    var s = $('<label for="select-' + e + '"><span>' + t + "</span><strong></strong></label>"),
                        l = $('<input type="checkbox" id="select-' + e + '">');
                    r.prepend(l), r.prepend(s);
                    var c = $('<ul class="' + n + '"></ul>');
                    r.append(c);
                    var d, u = 0,
                        f = $(this).children("option").length;
                    if ($(this).children("option").each(function(e) {
                            var t = $(this).text(),
                                r = $(this).val(),
                                a = $(this).hasAttr("selected"),
                                o = $("<li></li>");
                            c.append(o);
                            var s = $('<label for="' + id + "-" + e + '">' + t + "</label>"),
                                l = $('<input type="' + n + '" value="' + r + '" name="' + i + '" id="' + id + "-" + e + '">');
                            a && (d = l.prop("checked", !0), u++), o.append(l), o.append(s)
                        }), a) {
                        var p = u / f * 100;
                        a.width(p + "%")
                    } else u && (s.children("span").text(d.next("label").text()), r.addClass("filled"));
                    $(this).remove()
                }
            }), $(document).on("click", function(e) {
                0 === $(e.target).closest(".material-select").length && $(".material-select > input").prop("checked", !1)
            }), $(".material-select > input").on("change", function() {
                var e = $(this).attr("id");
                $(".material-select > input").each(function() {
                    var t = $(this).attr("id");
                    e != t && $(this).prop("checked", !1)
                })
            }), $(".material-select ul input").on("change", function() {
                if ($(this).closest(".material-select.checkbox").length) {
                    var e = $(this).closest("ul"),
                        t = e.find("input").length,
                        n = e.find("input:checked").length,
                        i = n / t * 100;
                    $(this).closest(".material-select").find(".material-bar").width(i + "%")
                } else {
                    var r = $(this).closest(".material-select"),
                        a = r.children("label").children("span"),
                        o = $(this).next("label");
                    a.text(o.text()), r.children("input").prop("checked", !1), r.addClass("filled")
                }
            })
        }
    }), $.validate({
        form: "form",
        modules: "security",
        reCaptchaSiteKey: "6LclCCATAAAAAI9zYycwBDetQNgFyP7Mg5hEZK_x",
        reCaptchaTheme: "light"
    }), $.fn.materialripple = function(e) {
        var t = {
            rippleClass: "ripple-wrapper"
        };
        $.extend(t, e), $("body").on("animationend webkitAnimationEnd oAnimationEnd", "." + t.rippleClass, function() {
            i(this)
        });
        var n = function(e, n) {
                $(e).append('<span class="added ' + t.rippleClass + '"></span>'), newRippleElement = $(e).find(".added"), newRippleElement.removeClass("added");
                n.pageX, n.pageY;
                elementWidth = $(e).outerWidth(), elementHeight = $(e).outerHeight(), elementRadius = $(e).css("border-radius"), d = Math.max(elementWidth, elementHeight), newRippleElement.css({
                    width: d,
                    height: d
                });
                var i = n.clientX - $(e).offset().left - d / 2 + $(window).scrollLeft(),
                    r = n.clientY - $(e).offset().top - d / 2 + $(window).scrollTop();
                newRippleElement.css("top", r + "px").css("left", i + "px").css("border-radius", "100%").addClass("animated")
            },
            i = function(e) {
                e.remove()
            };
        $(this).addClass("has-ripple"), $(this).bind("click", function(e) {
            n(this, e)
        })
    }, ! function(e, t, n, i) {
        var r = e(t);
        e.fn.lazyload = function(a) {
            function o() {
                var t = 0;
                l.each(function() {
                    var n = e(this);
                    if (!c.skip_invisible || n.is(":visible"))
                        if (e.abovethetop(this, c) || e.leftofbegin(this, c));
                        else if (e.belowthefold(this, c) || e.rightoffold(this, c)) {
                        if (++t > c.failure_limit) return !1
                    } else n.trigger("appear"), t = 0
                })
            }
            var s, l = this,
                c = {
                    threshold: 0,
                    failure_limit: 0,
                    event: "scroll",
                    effect: "show",
                    container: t,
                    data_attribute: "original",
                    skip_invisible: !1,
                    appear: null,
                    load: null,
                    placeholder: "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                };
            return a && (i !== a.failurelimit && (a.failure_limit = a.failurelimit, delete a.failurelimit), i !== a.effectspeed && (a.effect_speed = a.effectspeed, delete a.effectspeed), e.extend(c, a)), s = c.container === i || c.container === t ? r : e(c.container), 0 === c.event.indexOf("scroll") && s.bind(c.event, function() {
                return o()
            }), this.each(function() {
                var t = this,
                    n = e(t);
                t.loaded = !1, (n.attr("src") === i || n.attr("src") === !1) && n.is("img") && n.attr("src", c.placeholder), n.one("appear", function() {
                    if (!this.loaded) {
                        if (c.appear) {
                            var i = l.length;
                            c.appear.call(t, i, c)
                        }
                        e("<img />").bind("load", function() {
                            var i = n.attr("data-" + c.data_attribute);
                            n.hide(), n.is("img") ? n.attr("src", i) : n.css("background-image", "url('" + i + "')"), n[c.effect](c.effect_speed), t.loaded = !0;
                            var r = e.grep(l, function(e) {
                                return !e.loaded
                            });
                            if (l = e(r), c.load) {
                                var a = l.length;
                                c.load.call(t, a, c)
                            }
                        }).attr("src", n.attr("data-" + c.data_attribute))
                    }
                }), 0 !== c.event.indexOf("scroll") && n.bind(c.event, function() {
                    t.loaded || n.trigger("appear")
                })
            }), r.bind("resize", function() {
                o()
            }), /(?:iphone|ipod|ipad).*os 5/gi.test(navigator.appVersion) && r.bind("pageshow", function(t) {
                t.originalEvent && t.originalEvent.persisted && l.each(function() {
                    e(this).trigger("appear")
                })
            }), e(n).ready(function() {
                o()
            }), this
        }, e.belowthefold = function(n, a) {
            var o;
            return o = a.container === i || a.container === t ? (t.innerHeight ? t.innerHeight : r.height()) + r.scrollTop() : e(a.container).offset().top + e(a.container).height(), o <= e(n).offset().top - a.threshold
        }, e.rightoffold = function(n, a) {
            var o;
            return o = a.container === i || a.container === t ? r.width() + r.scrollLeft() : e(a.container).offset().left + e(a.container).width(), o <= e(n).offset().left - a.threshold
        }, e.abovethetop = function(n, a) {
            var o;
            return o = a.container === i || a.container === t ? r.scrollTop() : e(a.container).offset().top, o >= e(n).offset().top + a.threshold + e(n).height()
        }, e.leftofbegin = function(n, a) {
            var o;
            return o = a.container === i || a.container === t ? r.scrollLeft() : e(a.container).offset().left, o >= e(n).offset().left + a.threshold + e(n).width()
        }, e.inviewport = function(t, n) {
            return !(e.rightoffold(t, n) || e.leftofbegin(t, n) || e.belowthefold(t, n) || e.abovethetop(t, n))
        }, e.extend(e.expr[":"], {
            "below-the-fold": function(t) {
                return e.belowthefold(t, {
                    threshold: 0
                })
            },
            "above-the-top": function(t) {
                return !e.belowthefold(t, {
                    threshold: 0
                })
            },
            "right-of-screen": function(t) {
                return e.rightoffold(t, {
                    threshold: 0
                })
            },
            "left-of-screen": function(t) {
                return !e.rightoffold(t, {
                    threshold: 0
                })
            },
            "in-viewport": function(t) {
                return e.inviewport(t, {
                    threshold: 0
                })
            },
            "above-the-fold": function(t) {
                return !e.belowthefold(t, {
                    threshold: 0
                })
            },
            "right-of-fold": function(t) {
                return e.rightoffold(t, {
                    threshold: 0
                })
            },
            "left-of-fold": function(t) {
                return !e.rightoffold(t, {
                    threshold: 0
                })
            }
        })
    }(jQuery, window, document),
    function(e, t, n, i) {
        var r = e(t);
        e(n);
        e.fn.wtScroll = function(n) {
            var i = e(this),
                a = null,
                o = e(".header").height(),
                s = null,
                l = null,
                c = null,
                d = null,
                u = null,
                f = !0,
                p = 20,
                h = 0,
                m = 0,
                g = null,
                v = function(t) {
                    var r = t.next(".content-spinner");
                    r.show(), f = !1;
                    var a = t.attr("data-type"),
                        o = t.attr("data-id");
                    e.ajax({
                        method: "GET",
                        url: "/ajax_previous",
                        data: {
                            type: a,
                            id: o
                        }
                    }).done(function(t) {
                        i.append(t), "undefined" == typeof n.imageItem && e(".lazy").lazyload({
                            threshold: 200,
                            failure_limit: 20
                        }).removeClass("lazy").addClass("lazyloaded"), r.hide(), f = !0, m++;
                        var a = e(t).filter("article"),
                            o = e(a).attr("data-type"),
                            s = e(a).attr("data-id");
                       
                    })
                },
                y = function(e, t) {
                    var n = {
                        title: e,
                        path: t
                    };
                    "function" == typeof history.pushState && history.pushState({
                        data: n
                    }, n.title, n.path)
                },
                b = function(t) {
                    var n = t.attr("data-title"),
                        i = t.attr("data-url"),
                        r = t.attr("data-description"),
                        o = (t.attr("data-image"), t.attr("data-amp"));
                    e("title").text() !== n && (changeContentBottom(t), y(n, i), x(a), e("title").html(n), e("meta[name=description]").attr("content", r), e("meta[name=description]").attr("content", r), e("link[rel=amphtml]").length > 0 && o && e("link[rel=amphtml]").attr("href", o))
                },
                x = function(n) {
                    var i = n.attr("data-id"),
                        r = n.attr("data-type"),
                        a = n.find(".content-favorite__button.favorite"),
                        o = '<i class="material-icons">&#xE866;</i>';
                    e.ajax({
						headers: {
						  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
                        type: "POST",
                        url: "/ajax/favorite_check_post",
                        data: {
                            contentId: i,
                            contentType: r
                        },
                        dataType: "HTML",
                        success: function(n) {
                            "true" == n && (e(t).width() >= 768 ? a.addClass("active") : a.html(o), a.attr("data-action", "remove"))
                        },
                        error: function(e) {
                            console.log(e)
                        }
                    })
                };
            changeContentBottom = function(t) {
                "news" != t.attr("data-type") && "listing" != t.attr("data-type") || (e(".prev-image img").attr("src", t.attr("data-prevImage")), e(".prev-title").html(t.attr("data-prevTitle")))
            }, r.on({
                scroll: function() {
                    t.clearTimeout(e.data("this", "scrollTimer")), e.data(this, "scrollTimer", t.setTimeout(function() {
                        if (o != e(".header").height() && (o = e(".header").height()), a = e(n.item + ":in-viewport(" + o + ")"), a.length > 0) {
                            e(n.item).find(".share-container").addClass("is-hide"), a.find(".share-container").removeClass("is-hide"), e("body").hasClass("scroll-down") ? a.find(".share-container").addClass("is-active") : e("body").hasClass("scroll-up") && a.find(".share-container").removeClass("is-active");
                            var x = a.offset().top - o;
                            x = Math.round(x);
                            var w = a.index() == p ? a.offset().top + a.height() - e(t).height() : x + a.height(),
                                C = e(this).scrollTop(),
                                $ = w - x,
                                k = 0,
                                T = 0;
                            if (C >= x && C <= w ? (k = C - x, T = 100 * k / $, T = Math.round(T)) : T = C > w ? 100 : 0, e(".prev-progress").css("width", T + "%"), b(a), n.imageItem && (s = e(n.imageItem + ":in-viewport(" + o + ")"), s.length > 0)) {
                                l = s.offset(), c = l.top - o, d = s.height(), u = c + d;
                                var _ = s.attr("image-url");
                                e(this).scrollTop() >= c && e(this).scrollTop() <= u && _ != g && (g = _, y("", s.attr("image-url")))
                            }
                            var A = i.height() > r.height() ? i.height() - r.height() : r.height() - i.height();
                            r.scrollTop() >= A - h && f && m < p && v(a)
                        }
                    }, 200))
                }
            })
        }
    }(jQuery, window, document), $(function() {
        $(".tab-container").length > 0 && $.each($(".tab-container"), function(e, t) {
            var n = $(".tab", this).filter(".is-active").attr("data-for"),
                i = $(this).attr("data-cookie");
            $(this).find("[data-tab='" + n + "']").addClass("is-active"), $(".tab", this).click(function() {
                $(this).parents(".tab-container").addClass("is-upgraded"), $(".tab", t).removeClass("is-active"), $(".tab-content", t).removeClass("is-active"), $(this).addClass("is-active"), n = $(this).data("for"), $("div").find("[data-tab='" + n + "']").addClass("is-active"), "true" == i && n != getCookie("comment") && createCookie("comment", n, 31536e7)
            })
        })
    }), $(function() {
        $(".tooltip").length > 0 && $("body").on({
            mouseenter: function() {
                var e = $(this).offset().top,
                    t = $(this).offset().left,
                    n = $(this).data("tooltip"),
                    i = $(this).outerWidth(),
                    r = "t-" + $(this).index();
                $("body").append('<div class="tooltip-view ' + r + '" style="top:' + e + "px;left:" + t + 'px">' + n + "</div>");
                var a = $(".tooltip-view." + r).outerWidth(),
                    o = i > a ? (i - a) / 2 : (a - i) / 2;
                o *= -1, $(".tooltip-view." + r).css("margin-left", o), $(".tooltip-view." + r).animate({
                    opacity: 1,
                    marginTop: "-40px"
                }, 100)
            },
            mouseleave: function() {
                var e = "t-" + $(this).index();
                $(".tooltip-view." + e).animate({
                    opacity: 0,
                    marginTop: "-30px"
                }, 100, function() {
                    $(".tooltip-view." + e).remove()
                })
            }
        }, ".tooltip")
    }), $.belowthefold = function(e, t) {
        var n = $(window).height() + $(window).scrollTop();
        return n <= $(e).offset().top - t.threshold
    }, $.abovethetop = function(e, t) {
        var n = $(window).scrollTop();
        return n >= $(e).offset().top + $(e).height() - t.threshold
    }, $.rightofscreen = function(e, t) {
        var n = $(window).width() + $(window).scrollLeft();
        return n <= $(e).offset().left - t.threshold
    }, $.leftofscreen = function(e, t) {
        var n = $(window).scrollLeft();
        return n >= $(e).offset().left + $(e).width() - t.threshold
    }, $.inviewport = function(e, t) {
        return !($.rightofscreen(e, t) || $.leftofscreen(e, t) || $.belowthefold(e, t) || $.abovethetop(e, t))
    }, $.extend($.expr[":"], {
        "below-the-fold": function(e, t, n) {
            return $.belowthefold(e, {
                threshold: 0
            })
        },
        "above-the-top": function(e, t, n) {
            return $.abovethetop(e, {
                threshold: 0
            })
        },
        "left-of-screen": function(e, t, n) {
            return $.leftofscreen(e, {
                threshold: 0
            })
        },
        "right-of-screen": function(e, t, n) {
            return $.rightofscreen(e, {
                threshold: 0
            })
        },
        "in-viewport": function(e, t, n) {
            return $.inviewport(e, {
                threshold: n[3] || 0
            })
        }
    }),
    function(e, t, n) {
        var i = !1,
            r = "FuckAdBlock",
            a = function() {
                var e = this,
                    t = {};
                this.errors = {
                    throwError: function(e, t, n) {
                        throw 'Argument "' + e + '" of method "' + t + '" is not an "' + n + '"'
                    },
                    isObject: function(e, t, n) {
                        "object" == typeof e && Array.isArray(e) !== !0 && null !== e || this.throwError(t, n, "object")
                    },
                    isArray: function(e, t, n) {
                        Array.isArray(e) === !1 && this.throwError(t, n, "array")
                    },
                    isFunction: function(e, t, n) {
                        "function" != typeof e && this.throwError(t, n, "function")
                    },
                    isString: function(e, t, n) {
                        "string" != typeof e && this.throwError(t, n, "string")
                    },
                    isBoolean: function(e, t, n) {
                        "boolean" != typeof e && this.throwError(t, n, "boolean")
                    }
                }, this.options = {
                    set: function(n) {
                        e.errors.isObject(n, "optionsList", "options.set");
                        for (var i in n) t[i] = n[i], e.debug.log("options.set", 'Set "' + i + '" to "' + n[i] + '"');
                        return e
                    },
                    get: function(e) {
                        return t[e]
                    }
                }, this.debug = {
                    set: function(t) {
                        return i = t, e.debug.log("debug.set", 'Set debug to "' + i + '"'), e
                    },
                    isEnable: function() {
                        return i
                    },
                    log: function(t, n) {
                        i === !0 && (e.errors.isString(t, "method", "debug.log"), e.errors.isString(n, "message", "debug.log"), console.log("[" + r + "][" + t + "] " + n))
                    }
                }, this.versionToInt = function(e) {
                    for (var t = "", n = 0; n < 3; n++) {
                        var i = e[n] || 0;
                        1 === ("" + i).length && (i = "0" + i), t += i
                    }
                    return parseInt(t)
                }
            },
            o = function() {
                a.apply(this);
                var e = null,
                    t = null;
                this.setDetected = function(t) {
                    return e = t, this
                }, this.callDetected = function() {
                    return null !== e && (e(), e = null, !0)
                }, this.setUndetected = function(e) {
                    return t = e, this
                }, this.callUndetected = function() {
                    return null !== t && (t(), t = null, !0)
                }
            },
            s = function() {
                a.apply(this), this.options.set({
                    timeout: 200
                });
                var e = this,
                    t = [4, 0, 0, "beta", 3],
                    n = {},
                    i = {};
                this.getVersion = function(e) {
                    return e !== !0 ? t : void this.versionToInt(t)
                }, this.addEvent = function(e, t) {
                    return this.errors.isString(e, "name", "addEvent"), this.errors.isFunction(t, "callback", "addEvent"), void 0 === n[e] && (n[e] = []), n[e].push(t), this.debug.log("set", 'Event "' + e + '" added'), this
                }, this.on = function(e, t) {
                    return this.errors.isBoolean(e, "detected", "on"), this.errors.isFunction(t, "callback", "on"), this.addEvent(e === !0 ? "detected" : "undetected", t)
                }, this.onDetected = function(e) {
                    return this.errors.isFunction(e, "callback", "onDetected"), this.addEvent("detected", e)
                }, this.onNotDetected = function(e) {
                    return this.errors.isFunction(e, "callback", "onNotDetected"), this.addEvent("undetected", e)
                };
                var o = function(t) {
                    var i = n[t];
                    if (e.debug.isEnable() === !0) {
                        var r = void 0 !== i ? i.length : 0;
                        e.debug.log("dispatchEvent", 'Starts dispatch of events "' + t + '" (0/' + r + ")")
                    }
                    if (void 0 !== i)
                        for (var a in i) e.debug.isEnable() === !0 && e.debug.log("dispatchEvent", 'Dispatch event "' + t + '" (' + (parseInt(a) + 1) + "/" + r + ")"), i[a]();
                    return this
                };
                this.check = function(t, n) {
                    t instanceof Array == !1 && void 0 === n && (n = t, t = void 0), void 0 === t && (t = Object.keys(i)), void 0 === n && (n = {}), this.errors.isArray(t, "pluginsList", "check"), this.errors.isObject(n, "optionsList", "check"), this.debug.log("check", "Starting check");
                    var r = {},
                        a = t.length,
                        s = 0,
                        l = function(t, n, i) {
                            if (s++, e.debug.log("check", (n === !0 ? "Positive" : "Negative") + '" check of plugin "' + t + '"'), i === !0 || n === !0 || s === a) {
                                clearTimeout(f);
                                for (var l in r) r[l].instance.stop();
                                o(n === !0 ? "detected" : "undetected")
                            }
                        };
                    if (this.debug.log("check", "Starting loading plugins (0/" + a + ") (" + t.join() + ")"), 0 === a) return l("#NoPlugin", !1, !0), this;
                    for (var c in t) {
                        var d = t[c];
                        this.debug.log("check", 'Load plugin "' + d + '" (' + (parseInt(c) + 1) + "/" + a + ")");
                        var u = r[d] = {
                            name: d,
                            instance: new i[d],
                            detected: null
                        };
                        void 0 !== n[d] && u.instance.options.set(n[d]),
                            function(e, t) {
                                t.instance.setDetected(function() {
                                    t.detected = !0, e(t.name, !0)
                                }).setUndetected(function() {
                                    t.detected = !1, e(t.name, !1)
                                })
                            }(l, u)
                    }
                    for (var d in r) r[d].instance.start();
                    var f = setTimeout(function() {
                        l("#Timeout", !1, !0)
                    }, this.options.get("timeout"));
                    return this
                }, this.registerPlugin = function(e) {
                    if (this.errors.isFunction(e, "pluginClass", "registerPlugin"), this.errors.isString(e.pluginName, "pluginClass.pluginName", "registerPlugin"), this.errors.isArray(e.versionMin, "pluginClass.versionMin", "registerPlugin"), 3 !== e.versionMin.length && this.errors.throwError("pluginClass.versionMin", "registerPlugin", "array with 3 values"), void 0 === i[e.pluginName]) {
                        if (this.versionToInt(t) >= this.versionToInt(e.versionMin)) return i[e.pluginName] = e, this.debug.log("registerPlugin", 'Plugin "' + e.pluginName + '" registered'), !0;
                        throw 'The plugin "' + e.pluginName + '" (' + e.versionMin.join(".") + ") is too recent for this version of " + r + " (" + t.join(".") + ")"
                    }
                    throw 'The plugin "' + e.pluginName + '" is already registered'
                }, this.registerPlugin(l)
            };
        s.getPluginClass = function() {
            return o
        };
        var l = function() {
            s.getPluginClass().apply(this, arguments), this.options.set({
                loopTime: 50,
                baitElement: null,
                baitClass: "pub_300x250 pub_300x250m pub_728x90 text-ad textAd text_ad text_ads text-ads text-ad-links",
                baitStyle: "width:1px!important;height:1px!important;position:absolute!important;left:-10000px!important;top:-1000px!important;",
                baitParent: null
            });
            var t = {};
            this.start = function() {
                var n = this;
                if (null === this.options.get("baitElement")) {
                    t.bait = this.createBait({
                        "class": this.options.get("baitClass"),
                        style: this.options.get("baitStyle")
                    });
                    var i = this.options.get("baitParent");
                    null === i ? e.document.body.appendChild(t.bait) : i.appendChild(t.bait)
                } else t.bait = this.options.get("baitElement");
                var r = function() {
                    n.checkBait(t.bait, !0) === !0 && n.callDetected()
                };
                return t.loopTimeout = setTimeout(r, 1), t.loopInterval = setInterval(r, this.options.get("loopTime")), this
            }, this.stop = function() {
                clearInterval(t.loopTimeout), clearInterval(t.loopInterval);
                var n = this.options.get("baitParent");
                return null === n ? e.document.body.removeChild(t.bait) : n.removeChild(t.bait), this
            }, this.createBait = function(t) {
                var n = e.document.createElement("div");
                return n.setAttribute("class", t["class"]), n.setAttribute("style", t.style), n.offsetParent, n.offsetHeight, n.offsetLeft, n.offsetTop, n.offsetWidth, n.clientHeight, n.clientWidth, n
            }, this.checkBait = function(t, n) {
                var i = !1;
                if (n === !0 && null !== e.document.body.getAttribute("abp") || null === t.offsetParent || 0 == t.offsetHeight || 0 == t.offsetLeft || 0 == t.offsetTop || 0 == t.offsetWidth || 0 == t.clientHeight || 0 == t.clientWidth) i = !0;
                else {
                    var r = e.getComputedStyle(t);
                    "none" != r.getPropertyValue("display") && "hidden" != r.getPropertyValue("visibility") || (i = !0)
                }
                return i
            }
        };
        l.pluginName = "html", l.version = [1, 0, 0], l.versionMin = [4, 0, 0];
        var c = function() {
            s.getPluginClass().apply(this, arguments), this.options.set({
                baitMode: "ajax",
                baitUrl: "/ad/banner/_adsense_/_adserver/_adview_.ad.json?adzone=top&adsize=300x250&advid={RANDOM}"
            });
            var t = {};
            this.start = function() {
                var e = this;
                t.end = !1;
                var n = this.options.get("baitUrl").replace(/\{RANDOM\}/g, function() {
                    return parseInt(1e8 * Math.random())
                });
                return this._urlCheck(n, this.options.get("baitMode"), function() {
                    t.end === !1 && (t.end = !0, e.callDetected())
                }, function() {
                    t.end === !1 && (t.end = !0, e.callUndetected())
                }), this
            }, this.stop = function() {
                return t.end = !0, this
            }, this._urlCheck = function(t, n, i, r) {
                var a = !1,
                    o = function(e) {
                        a === !1 && (a = !0, e === !0 ? i() : r())
                    };
                if ("ajax" === n) {
                    var s = [!1, !1, !1, !1],
                        l = null,
                        c = function(e) {
                            if (void 0 !== e) o(e);
                            else {
                                if (0 === l) return void o(!0);
                                for (var t = 0; t < 4; t++)
                                    if (s[t] === !1) return void o(!0);
                                o(!1)
                            }
                        },
                        d = new XMLHttpRequest;
                    d.onreadystatechange = function() {
                        s[d.readyState - 1] = !0;
                        try {
                            l = d.status
                        } catch (e) {}
                        4 === d.readyState && c()
                    };
                    try {
                        d.open("GET", t, !0), d.send()
                    } catch (u) {
                        "2153644038" == u.result && c(!0)
                    }
                } else if ("import" === n) {
                    var f = document.createElement("script");
                    f.src = t, f.onerror = function() {
                        o(!0), e.document.body.removeChild(f)
                    }, f.onload = function() {
                        o(!1), e.document.body.removeChild(f)
                    }, e.document.body.appendChild(f)
                } else o(!1)
            }
        };
        if (c.pluginName = "http", l.version = [1, 0, 0], c.versionMin = [4, 0, 0], e[n] = s, void 0 === e[t]) {
            var d = e[t] = new s;
            e.addEventListener("load", function() {
                setTimeout(function() {
                    d.check()
                }, 1)
            }, !1)
        }
    }(window, "fuckAdBlock", "FuckAdBlock"),
    function(e, t) {
        "function" == typeof define && define.amd ? define(["jquery"], t) : t(e.jQuery)
    }(this, function(e) {
        function t() {
            var e = document.createElement("smartbanner"),
                t = {
                    WebkitTransition: "webkitTransitionEnd",
                    MozTransition: "transitionend",
                    OTransition: "oTransitionEnd otransitionend",
                    transition: "transitionend"
                };
            for (var n in t)
                if (void 0 !== e.style[n]) return {
                    end: t[n]
                };
            return !1
        }
        var n = navigator.userAgent,
            i = /Edge/i.test(n),
            r = function(t) {
                this.origHtmlMargin = parseFloat(e("html").css("margin-top")), this.options = e.extend({}, e.smartbanner.defaults, t);
                var r = navigator.standalone;
                if (this.options.force ? this.type = this.options.force : null !== n.match(/Windows Phone/i) && null !== n.match(/Edge|Touch/i) ? this.type = "windows" : null !== n.match(/iPhone|iPod/i) || n.match(/iPad/) && this.options.iOSUniversalApp ? null !== n.match(/Safari/i) && (null !== n.match(/CriOS/i) || null != n.match(/FxiOS/i) || window.Number(n.substr(n.indexOf("OS ") + 3, 3).replace("_", ".")) < 6) && (this.type = "ios") : n.match(/\bSilk\/(.*\bMobile Safari\b)?/) || n.match(/\bKF\w/) || n.match("Kindle Fire") ? this.type = "kindle" : null !== n.match(/Android/i) && (this.type = "android"), this.type && !r && !this.getCookie("sb-closed") && !this.getCookie("sb-installed")) {
                    this.scale = "auto" == this.options.scale ? e(window).width() / window.screen.width : this.options.scale, this.scale < 1 && (this.scale = 1);
                    var a = e("android" == this.type ? 'meta[name="google-play-app"]' : "ios" == this.type ? 'meta[name="apple-itunes-app"]' : "kindle" == this.type ? 'meta[name="kindle-fire-app"]' : 'meta[name="msApplication-ID"]');
                    if (a.length) {
                        if ("windows" == this.type) i && (this.appId = e('meta[name="msApplication-PackageEdgeName"]').attr("content")), this.appId || (this.appId = e('meta[name="msApplication-PackageFamilyName"]').attr("content"));
                        else {
                            var o = /app-id=([^\s,]+)/.exec(a.attr("content"));
                            if (!o) return;
                            this.appId = o[1]
                        }
                        this.title = this.options.title ? this.options.title : a.data("title") || e("title").text().replace(/\s*[|\-Â·].*$/, ""), this.author = this.options.author ? this.options.author : a.data("author") || (e('meta[name="author"]').length ? e('meta[name="author"]').attr("content") : window.location.hostname), this.iconUrl = a.data("icon-url"), "function" == typeof this.options.onInstall ? this.options.onInstall = this.options.onInstall : this.options.onInstall = function() {}, "function" == typeof this.options.onClose ? this.options.onClose = this.options.onClose : this.options.onClose = function() {}, this.create(), this.show(), this.listen()
                    }
                }
            };
        r.prototype = {
            constructor: r,
            create: function() {
                var t, n = this.options.url || function() {
                        switch (this.type) {
                            case "android":
                                return "market://details?id=";
                            case "kindle":
                                return "amzn://apps/android?asin=";
                            case "windows":
                                return i ? "ms-windows-store://pdp/?productid=" : "ms-windows-store:navigate?appid="
                        }
                        return "https://itunes.apple.com/" + this.options.appStoreLanguage + "/app/id"
                    }.call(this) + this.appId,
                    r = function() {
                        switch (this.type) {
                            case "android":
                                return this.options.inGooglePlay;
                            case "kindle":
                                return this.options.inAmazonAppStore;
                            case "windows":
                                return this.options.inWindowsStore
                        }
                        return this.options.inAppStore
                    }.call(this),
                    a = null == this.options.iconGloss ? "ios" == this.type : this.options.iconGloss;
                "android" == this.type && this.options.GooglePlayParams && (n += "&referrer=" + this.options.GooglePlayParams);
                var o = '<div id="smartbanner" class="' + this.type + '"><a href="#" class="sb-close"><i class="material-icons">&#xE5CD;</i></a><a href="' + n + '" class="sb-button" target="_blank"><div class="sb-container"><span class="sb-icon"></span><div class="sb-info"><strong>' + this.title + "</strong><span>" + r + '</span></div><div class="sb-gp"><span>' + this.options.button + "</span></div></div></a></div>";
                this.options.layer ? e(this.options.appendToSelector).append(o) : e(this.options.appendToSelector).prepend(o), this.options.icon ? t = this.options.icon : this.iconUrl ? t = this.iconUrl : e('link[rel="apple-touch-icon-precomposed"]').length > 0 ? (t = e('link[rel="apple-touch-icon-precomposed"]').attr("href"), null == this.options.iconGloss && (a = !1)) : e('link[rel="apple-touch-icon"]').length > 0 ? t = e('link[rel="apple-touch-icon"]').attr("href") : e('meta[name="msApplication-TileImage"]').length > 0 ? t = e('meta[name="msApplication-TileImage"]').attr("content") : e('meta[name="msapplication-TileImage"]').length > 0 && (t = e('meta[name="msapplication-TileImage"]').attr("content")), t ? (e("#smartbanner .sb-icon").css("background-image", "url(" + t + ")"), a && e("#smartbanner .sb-icon").addClass("gloss")) : e("#smartbanner").addClass("no-icon"), this.bannerHeight = e("#smartbanner").outerHeight() + 2, this.scale > 1 && (e("#smartbanner").css("top", parseFloat(e("#smartbanner").css("top")) * this.scale).css("height", parseFloat(e("#smartbanner").css("height")) * this.scale).hide(), e("#smartbanner .sb-container").css("-webkit-transform", "scale(" + this.scale + ")").css("-msie-transform", "scale(" + this.scale + ")").css("-moz-transform", "scale(" + this.scale + ")").css("width", e(window).width() / this.scale)), e("#smartbanner").css("bottom", 0)
            },
            listen: function() {
                e("#smartbanner .sb-close").on("click", e.proxy(this.close, this)), e("#smartbanner .sb-button").on("click", e.proxy(this.install, this))
            },
            show: function(t) {
                var n = e("#smartbanner");
                if (n.stop(), this.options.layer) n.animate({
                    bottom: 0,
                    display: "block"
                }, this.options.speedIn).addClass("shown").show(), e(this.pushSelector).animate({
                    paddingTop: this.origHtmlMargin + this.bannerHeight * this.scale
                }, this.options.speedIn, "swing", t);
                else if (e.support.transition) {
                    n.animate({
                        bottom: 0
                    }, this.options.speedIn).addClass("shown");
                    var i = function() {
                        e("html").removeClass("sb-animation"), t && t()
                    };
                    e(this.pushSelector).addClass("sb-animation").one(e.support.transition.end, i).emulateTransitionEnd(this.options.speedIn).css("margin-top", this.origHtmlMargin + this.bannerHeight * this.scale)
                } else n.slideDown(this.options.speedIn).addClass("shown")
            },
            hide: function(t) {
                var n = e("#smartbanner");
                if (n.stop(), this.options.layer) n.animate({
                    bottom: -1 * this.bannerHeight * this.scale,
                    display: "block"
                }, this.options.speedIn).removeClass("shown"), e(this.pushSelector).animate({
                    paddingTop: this.origHtmlMargin
                }, this.options.speedIn, "swing", t);
                else if (e.support.transition) {
                    "android" !== this.type ? n.css("bottom", -1 * this.bannerHeight * this.scale).removeClass("shown") : n.css({
                        display: "none"
                    }).removeClass("shown");
                    var i = function() {
                        e("html").removeClass("sb-animation"), t && t()
                    };
                    e(this.pushSelector).addClass("sb-animation").one(e.support.transition.end, i).emulateTransitionEnd(this.options.speedOut).css("margin-top", this.origHtmlMargin)
                } else n.slideUp(this.options.speedOut).removeClass("shown")
            },
            close: function(e) {
                e.preventDefault(), this.hide(), this.setCookie("sb-closed", "true", this.options.daysHidden), this.options.onClose(e)
            },
            install: function(e) {
                this.options.hideOnInstall && this.hide(), this.setCookie("sb-installed", "true", this.options.daysReminder), this.options.onInstall(e)
            },
            setCookie: function(e, t, n) {
                var i = new Date;
                i.setDate(i.getDate() + n), t = encodeURI(t) + (null == n ? "" : "; expires=" + i.toUTCString()), document.cookie = e + "=" + t + "; path=/;"
            },
            getCookie: function(e) {
                var t, n, i, r = document.cookie.split(";");
                for (t = 0; t < r.length; t++)
                    if (n = r[t].substr(0, r[t].indexOf("=")), i = r[t].substr(r[t].indexOf("=") + 1), n = n.replace(/^\s+|\s+$/g, ""), n == e) return decodeURI(i);
                return null
            },
            switchType: function() {
                var t = this;
                this.hide(function() {
                    t.type = "android" == t.type ? "ios" : "android";
                    var n = e("android" == t.type ? 'meta[name="google-play-app"]' : 'meta[name="apple-itunes-app"]').attr("content");
                    t.appId = /app-id=([^\s,]+)/.exec(n)[1], e("#smartbanner").detach(), t.create(), t.show()
                })
            }
        }, e.smartbanner = function(t) {
            var n = e(window),
                i = n.data("smartbanner"),
                a = "object" == typeof t && t;
            i || n.data("smartbanner", i = new r(a)), "string" == typeof t && i[t]()
        }, e.smartbanner.defaults = {
            title: null,
            author: null,
            price: "FREE",
            appStoreLanguage: "us",
            inAppStore: "On the App Store",
            inGooglePlay: "In Google Play",
            inAmazonAppStore: "In the Amazon Appstore",
            inWindowsStore: "In the Windows Store",
            GooglePlayParams: null,
            icon: null,
            iconGloss: null,
            button: "VIEW",
            url: null,
            scale: "auto",
            speedIn: 300,
            speedOut: 400,
            daysHidden: 15,
            daysReminder: 90,
            force: null,
            hideOnInstall: !0,
            layer: !1,
            iOSUniversalApp: !0,
            appendToSelector: "body",
            pushSelector: "html"
        }, e.smartbanner.Constructor = r, void 0 === e.support.transition && (e.fn.emulateTransitionEnd = function(t) {
            var n = !1,
                i = this;
            e(this).one(e.support.transition.end, function() {
                n = !0
            });
            var r = function() {
                n || e(i).trigger(e.support.transition.end)
            };
            return setTimeout(r, t), this
        }, e(function() {
            e.support.transition = t()
        }))
    }), loadThread(), $("body").on({
        click: function() {
            var e = $(this),
                t = e.data("id"),
                n = e.data("type");
            $(".content-comments__collapse"), $(".content-comments__expand");
            "more" == n && e.addClass("is-hide").next('[data-type="less"]').removeClass("is-hide"), "less" == n && e.addClass("is-hide").prev('[data-type="more"]').removeClass("is-hide"), $("#collapse-" + t).slideToggle()
        }
    }, ".content-comments__item__expand"), $("body").on({
        click: function() {
            var e = $($(this).parents(".content-comments__item")),
                t = $(e.find(".content-comments__form")),
                n = t.css("display");
            "none" == n ? t.slideDown() : t.slideUp()
        }
    }, ".content-comments__item__button--reply"), $("body").on({
        keyup: function() {
            var e = $(this).val().length,
                t = $(this).parent().parent();
            t.find(".max-char").html(e)
        }
    }, ".content-comments__form__comment"), $("body").on({
        click: function() {
            $(".modal-close").trigger("click"), createCookie("guest-comment", "yes", 1, "/", null), sendComment()
        }
    }, ".login__form-button--send-comment"), $("body").on({
        submit: function(e) {
            e.preventDefault();
            var t = $(this),
                n = $(this).find(".content-comments__form__comment"),
                i = n.val().length;
            $("form").removeClass("active"), t.addClass("active"), i <= 5e3 && (n.parents(".content-comments__form--sub").length > 0 && i >= 10 || 0 == n.parents(".content-comments__form--sub").length && i >= 30 ? "yes" == t.parents(".content-comments__form").attr("data-login") ? sendComment() : $(".modal-button[data-modal=modal-guest]").trigger("click") : insertCommentStatus("error", t.find(".content-comments__form__comment").parents(".content-comments__form__row")))
        }
    }, ".content-comments__form__element"), $("body").on({
        click: function() {
            var e = $(this),
                t = $(this).attr("data-id"),
                n = $(this).attr("data-type");
            $.ajax({
                type: "POST",
                url: "/ajax/comment-vote",
                dataType: "HTML",
                data: {
                    comment_id: t,
                    type: n
                },
                success: function(t) {
                    if ("error" != t) {
                        t = JSON.parse(t);
                        var n, i, r = e.parents(".content-comments__item__actions"),
                            a = r.find(".content-comments__item__button--like").find(".count"),
                            o = r.find(".content-comments__item__button--dislike").find(".count");
                        n = t.up_votes > 0 ? "+" + t.up_votes : "", i = t.down_votes > 0 ? "-" + t.down_votes : "", r.find(".material-button").removeClass("active"), e.find(".material-button").addClass("active"), a.html(n), o.html(i)
                    } else $(".header__appbar--right__user").trigger("click")
                },
                error: function(t) {
                    $(window).width() >= 768 ? e.prepend('<div class="comment-notice"><span>Daha Ã¶nce oy kullandÄ±nÄ±z.</span></div>') : $("body").append('<div class="comment-notice"><span>Daha Ã¶nce oy kullandÄ±nÄ±z.</span></div>');
                    var n = $(".comment-notice");
                    n.stop(!0, !0).fadeIn(300).delay(1500).fadeOut(300, function() {
                        $(this).remove(".comment-notice")
                    })
                }
            })
        }
    }, ".content-comments__item__button--like, .content-comments__item__button--dislike"), $("body").on({
        click: function() {
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var e = $(this),
                t = parseInt(e.attr("data-current-page")) + 1,
                n = $(e.parents(".thread-section-loaded")),
                i = n.attr("data-thread-id"),
                r = $(n.find(".content-spinner")),
                a = $(n.find("#thread"));
            e.attr("data-current-page", t).hide(), r.show(), $.ajax({
				headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "/ajax/commentsLoad",
                dataType: "HTML",
                data: {
                    thread_id: i,
                    page: t,
                    _token: CSRF_TOKEN
                },
                success: function(e) {
                    a.append(e), r.hide()
                }
            })
        }
    }, ".content-comments__load-more"), $("body").on({
        click: function() {
            var e = $(this),
                t = $(e.parents(".content-comments__item__reply")),
                n = t.find(".content-comments__item__reply-list");
            e.hasClass("is-more") ? (n.slideDown(), e.removeClass("is-more"), e.addClass("is-less"), e.text("diğer yanıtları gizle")) : (n.slideUp(), e.removeClass("is-less"), e.addClass("is-more"), e.text("diğer yanıtları göster"))
        }
    }, ".content-comments__item__load-more");
var toast = function(e, t) {
    var n = document.getElementById("toast-container");
    if (null === n) {
        n = document.createElement("div"), n.id = "toast-container", document.body.appendChild(n);
        var i = document.createElement("div");
        i.classList.add("toast"), n.appendChild(i)
    }
    $(".toast").html(e), $("#toast-container").stop(!0, !0).fadeIn(300).delay(t).fadeOut(300)
};
$(function() {
    function e(e) {
        var t = $(e).data("content-type"),
            n = $(e).data("content-id"),
            i = $(e).data("smile-type"),
            r = $(e).find(".content-smile__count"),
            a = parseInt($(r).text());
        $.ajax({
            type: "POST",
            url: "/smile/send",
            dataType: "HTML",
            data: {
                contentType: t,
                contentId: n,
                smileType: i
            },
            success: function() {
                a++, $(r).text(a);
                var t = $(e).parents(".content-smile");
                $(window).width() >= 768 ? t.prepend('<div class="content-smile-notice"><span>Oyunuz gÃ¶nderilmiÅŸtir.</span></div>') : $("body").append('<div class="content-smile-notice"><span>Oyunuz gÃ¶nderilmiÅŸtir.</span></div>');
                var n = $(".content-smile-notice");
                n.stop(!0, !0).fadeIn(300).delay(1500).fadeOut(300, function() {
                    $(this).remove(".content-smile-notice")
                }), $(e).find(".content-smile__count").css("color", "#0070CA")
            },
            error: function() {
                var t = $(e).parents(".content-smile");
                $(window).width() >= 768 ? t.prepend('<div class="content-smile-notice"><span>Daha Ã¶nce oy kullandÄ±nÄ±z.</span></div>') : $("body").append('<div class="content-smile-notice"><span>Daha Ã¶nce oy kullandÄ±nÄ±z.</span></div>');
                var n = $(".content-smile-notice");
                n.stop(!0, !0).fadeIn(300).delay(1500).fadeOut(300, function() {
                    $(this).remove(".content-smile-notice")
                })
            }
        })
    }
    $("body").on({
        click: function() {
            e($(this))
        }
    }, ".content-smile__item");
    var t = 0;
    $("body").on({
        mouseenter: function() {
            0 == t && (n($(this)), t = 1)
        },
        mouseleave: function() {
            1 == t && (i($(this)), t = 0)
        }
    }, ".content-body--left .content-smile");
    var n = function(e) {
            var t = e;
            if (t.hasClass("active") === !1) {
                var n, i, r = t.hasClass("horizontal");
                r === !0 ? i = 40 : n = -40, t.addClass("active"), t.find(".content-smile__list__item").css("transform", "scaleX(.4) scaleY(.4) translateY(" + n + "px) translateX(0) translateZ(0)");
                var a = 0;
                t.find(".content-smile__list__item").each(function() {
                    $(this).stop().delay(a).animate({
                        now: 1
                    }, {
                        step: function(e, t) {
                            $(this).css({
                                opacity: 1,
                                transform: "scaleX(1) scaleY(1) translateY(0) translateX(0) translateZ(0)"
                            })
                        },
                        duration: 80
                    }), a += 40
                })
            }
        },
        i = function(e) {
            var t, n, i = e,
                r = i.hasClass("horizontal");
            r === !0 ? n = 40 : t = -40, i.removeClass("active");
            var a = 0;
            $(i.find(".content-smile__list__item").get().reverse()).each(function() {
                $(this).stop().delay(a).animate({
                    now: .4
                }, {
                    step: function(e, n) {
                        $(this).css({
                            opacity: 0,
                            transform: "scaleX(0.4) scaleY(0.4) translateY(" + t + "px) translateX(0) translateZ(0)"
                        })
                    },
                    duration: 80
                }), a += 40
            })
        }
}), $(function() {
    $("body").on({
        click: function() {
            var e = $(this).data("modal");
            $(".modal").hide(), $("#" + e).show()
        }
    }, ".modal-button"), $("body").on({
        click: function() {
            $(this).parents(".modal").hide()
        }
    }, ".modal-close, .modal-overlay")
});