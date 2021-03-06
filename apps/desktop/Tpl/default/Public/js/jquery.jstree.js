function _filemanage(n, t, i, r) {
    this.id = n + "_" + t,
    this.fid = n,
    this.winid = t,
    this.string = "_filemanage.cons." + this.id,
    this.view = _filemanage.view,
    this.disp = _filemanage.disp,
    this.asc = _filemanage.asc,
    this.detailper = _filemanage.detailper,
    this.data = i,
    this.container = r,
    this.odata = [],
    _window.windows[t].filemanageid = this.id,
    _filemanage.cons[this.id] = this
}
function Search(n, t) {
    var r = {},
    i;
    for (i in n) n[i].name.toLowerCase().indexOf(t.toLowerCase()) !== -1 && (r[i] = n[i]);
    return r
}
function Sort(n, t, i) {
    var u = [],
    e,
    r,
    f;
    if (!n) return [];
    for (r in n) switch (parseInt(t)) {
    case 0:
        u[u.length] = n[r].type == "folder" ? " " + n[r].name + " ___" + r: n[r].name + "___" + r;
        break;
    case 1:
        u[u.length] = n[r].size + "___" + r;
        break;
    case 2:
        u[u.length] = n[r].type == "folder" ? " ___" + r: n[r].ext + n[r].type + "___" + r;
        break;
    case 3:
        u[u.length] = n[r].dateline + "___" + r
    }
    sarr1 = u.sort(),
    e = {};
    if (i) for (r = 0; r < u.length; r++) f = u[r].split("___"),
    e["icos_" + f[f.length - 1]] = n[f[f.length - 1]];
    else for (r = u.length - 1; r >= 0; r--) f = u[r].split("___"),
    e["icos_" + f[f.length - 1]] = n[f[f.length - 1]];
    return e
}
function get_ico_template(n, t) {
    var r;
    switch (t) {
    case "image":
        r = '<img class="imageclass" src="{img}" style="display:none;" title="{name}" onload="_ico.image_resize(this,{width},{height},{ispng});">';
        break;
    case "video":
        r = n < 3 ? '<img class="videoclass{width}_{height}" src="{img}"  title="{name}" >': '<img class="videoclass" src="{img}" style="display:none;" title="{name}" onload="_ico.image_resize(this,{width},{height},{ispng});">';
        break;
    default:
        r = '<img class="radius" src="{img}" style="display:none;" title="{name}" onload="_ico.image_resize(this,{width},{height},{ispng});">'
    }
    var u = '<table width="{divwidth}" height="{divheight}" style="table-layout:fixed;"><tr> <td  align="center" valign="middle" style="overflow:hidden;"  width="{width_10}">' + r + '</td>  <td align="left" valign="middle"><div class="IcoText_div" style="width:{text_width}px;"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td valign="middle" align="left"><a id="file_text_{icoid}" class=IcoText_folder">{name}</a></td></tr></table></div></td></tr></table><div icoid="{icoid}" class="icoblank" style="position:absolute;;left:0px;top:0px; background:url(dzz/images/b.gif);width:{divwidth}px; height:{divheight}px;z-index:10;"></div>',
    f = '<table width="{divwidth}" height="{divheight}" style="table-layout:fixed;"><tr>    <td  align="center" valign="middle" style="overflow:hidden;" height="{divheight_45}">' + r + '</td></tr>  <tr height="45"><td align="center" valign="middle"><div  class="IcoText_div" style="width:{text_width}px;height:35px;"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td valign="middle" align="center"><a id="file_text_{icoid}" class=IcoText_folder">{name}</a></td></tr></table></div></td></tr></table><div icoid="{icoid}" class="icoblank" style="position:absolute;;left:0px;top:0px; background:url(dzz/images/b.gif);width:{divwidth}px; height:{divheight}px;z-index:10;"></div>',
    i = "";
    switch (n) {
    case 0:
        i = f.replace(/{divwidth}/g, 150),
        i = i.replace(/{divheight}/g, 160),
        i = i.replace(/{divheight_2}/g, 158),
        i = i.replace(/{divwidth_2}/g, 158),
        i = i.replace(/{divheight_45}/g, 115),
        i = i.replace(/{width}/g, 100),
        i = i.replace(/{height}/g, 100),
        i = i.replace(/{width_10}/g, 90),
        i = i.replace(/{text_width}/g, 135);
        break;
    case 1:
        i = f.replace(/{divwidth}/g, 100),
        i = i.replace(/{divheight}/g, 103),
        i = i.replace(/{divheight_2}/g, 101),
        i = i.replace(/{divwidth_2}/g, 98),
        i = i.replace(/{divheight_45}/g, 58),
        i = i.replace(/{width}/g, 50),
        i = i.replace(/{height}/g, 50),
        i = i.replace(/{width_10}/g, 60),
        i = i.replace(/{text_width}/g, 85),
        i = i.replace(/{text_height}/g, 35);
        break;
    case 2:
        i = u.replace(/{divwidth}/g, 180),
        i = i.replace(/{divheight}/g, 70),
        i = i.replace(/{divheight_2}/g, 68),
        i = i.replace(/{divwidth_2}/g, 178),
        i = i.replace(/{width}/g, 50),
        i = i.replace(/{height}/g, 50),
        i = i.replace(/{width_10}/g, 75),
        i = i.replace(/{text_width}/g, 95);
        break;
    case 3:
        i = u.replace(/{divwidth}/g, 220),
        i = i.replace(/{divheight}/g, 42),
        i = i.replace(/{divheight_2}/g, 40),
        i = i.replace(/{divwidth_2}/g, 218),
        i = i.replace(/{width}/g, 32),
        i = i.replace(/{height}/g, 32),
        i = i.replace(/{width_10}/g, 42),
        i = i.replace(/{text_width}/g, 173)
    }
    return i
}
jQuery.cookie = function(n, t, i) {
    var o, r, f, e, u, s;
    if (typeof t != "undefined") {
        i = i || {},
        t === null && (t = "", i.expires = -1),
        o = "",
        i.expires && (typeof i.expires == "number" || i.expires.toUTCString) && (typeof i.expires == "number" ? (r = new Date, r.setTime(r.getTime() + i.expires * 864e5)) : r = i.expires, o = "; expires=" + r.toUTCString());
        var h = i.path ? "; path=" + i.path: "",
        c = i.domain ? "; domain=" + i.domain: "",
        l = i.secure ? "; secure": "";
        document.cookie = [n, "=", encodeURIComponent(t), o, h, c, l].join("")
    } else {
        f = null;
        if (document.cookie && document.cookie != "") for (e = document.cookie.split(";"), u = 0; u < e.length; u++) {
            s = jQuery.trim(e[u]);
            if (s.substring(0, n.length + 1) == n + "=") {
                f = decodeURIComponent(s.substring(n.length + 1));
                break
            }
        }
        return f
    }
},
(function(n) {
    function t(t) {
        if (typeof t.data != "string") return;
        var r = t.handler,
        i = t.data.toLowerCase().split(" ");
        t.handler = function(t) {
            var o, h;
            if (this !== t.target && (/textarea|select/i.test(t.target.nodeName) || t.target.type === "text")) return;
            var f = t.type !== "keypress" && n.hotkeys.specialKeys[t.which],
            s = String.fromCharCode(t.which).toLowerCase(),
            c,
            u = "",
            e = {};
            for (t.altKey && f !== "alt" && (u += "alt+"), t.ctrlKey && f !== "ctrl" && (u += "ctrl+"), t.metaKey && !t.ctrlKey && f !== "meta" && (u += "meta+"), t.shiftKey && f !== "shift" && (u += "shift+"), f ? e[u + f] = !0 : (e[u + s] = !0, e[u + n.hotkeys.shiftNums[s]] = !0, u === "shift+" && (e[n.hotkeys.shiftNums[s]] = !0)), o = 0, h = i.length; o < h; o++) if (e[i[o]]) return r.apply(this, arguments)
        }
    }
    n.hotkeys = {
        version: "0.8",
        specialKeys: {
            8 : "backspace",
            9 : "tab",
            13 : "return",
            16 : "shift",
            17 : "ctrl",
            18 : "alt",
            19 : "pause",
            20 : "capslock",
            27 : "esc",
            32 : "space",
            33 : "pageup",
            34 : "pagedown",
            35 : "end",
            36 : "home",
            37 : "left",
            38 : "up",
            39 : "right",
            40 : "down",
            45 : "insert",
            46 : "del",
            96 : "0",
            97 : "1",
            98 : "2",
            99 : "3",
            100 : "4",
            101 : "5",
            102 : "6",
            103 : "7",
            104 : "8",
            105 : "9",
            106 : "*",
            107 : "+",
            109 : "-",
            110 : ".",
            111 : "/",
            112 : "f1",
            113 : "f2",
            114 : "f3",
            115 : "f4",
            116 : "f5",
            117 : "f6",
            118 : "f7",
            119 : "f8",
            120 : "f9",
            121 : "f10",
            122 : "f11",
            123 : "f12",
            144 : "numlock",
            145 : "scroll",
            191 : "/",
            224 : "meta"
        },
        shiftNums: {
            "`": "~",
            "1": "!",
            "2": "@",
            "3": "#",
            "4": "$",
            "5": "%",
            "6": "^",
            "7": "&",
            "8": "*",
            "9": "(",
            "0": ")",
            "-": "_",
            "=": "+",
            ";": ": ",
            "'": '"',
            ",": "<",
            ".": ">",
            "/": "?",
            "\\": "|"
        }
    },
    n.each(["keydown", "keyup", "keypress"],
    function() {
        n.event.special[this] = {
            add: t
        }
    })
})(jQuery),
(function() {
    if (jQuery && jQuery.jstree) return;
    var n = !1,
    t = !1,
    i = !1; (function(r) {
        r.vakata = {},
        r.vakata.css = {
            get_css: function(n, t, i) {
                n = n.toLowerCase();
                var u = i.cssRules || i.rules,
                r = 0;
                do {
                    if (u.length && r > u.length + 5) return ! 1;
                    if (u[r].selectorText && u[r].selectorText.toLowerCase() == n) return t === !0 ? (i.removeRule && i.removeRule(r), i.deleteRule && i.deleteRule(r), !0) : u[r]
                } while ( u [++ r ]);
                return ! 1
            },
            add_css: function(n, t) {
                return r.jstree.css.get_css(n, !1, t) ? !1 : (t.insertRule ? t.insertRule(n + " { }", 0) : t.addRule(n, null, 0), r.vakata.css.get_css(n))
            },
            remove_css: function(n, t) {
                return r.vakata.css.get_css(n, !0, t)
            },
            add_sheet: function(n) {
                var t = !1,
                i = !0;
                if (n.str) return n.title && (t = r("style[id='" + n.title + "-stylesheet']")[0]),
                t ? i = !1 : (t = document.createElement("style"), t.setAttribute("type", "text/css"), n.title && t.setAttribute("id", n.title + "-stylesheet")),
                t.styleSheet ? i ? (document.getElementsByTagName("head")[0].appendChild(t), t.styleSheet.cssText = n.str) : t.styleSheet.cssText = t.styleSheet.cssText + " " + n.str: (t.appendChild(document.createTextNode(n.str)), document.getElementsByTagName("head")[0].appendChild(t)),
                t.sheet || t.styleSheet;
                if (n.url) if (document.createStyleSheet) try {
                    t = document.createStyleSheet(n.url)
                } catch(u) {} else return t = document.createElement("link"),
                t.rel = "stylesheet",
                t.type = "text/css",
                t.media = "all",
                t.href = n.url,
                document.getElementsByTagName("head")[0].appendChild(t),
                t.styleSheet
            }
        };
        var u = [],
        e = -1,
        o = {},
        f = {};
        r.fn.jstree = function(n) {
            var f = typeof n == "string",
            i = Array.prototype.slice.call(arguments, 1),
            t = this;
            if (f) {
                if (n.substring(0, 1) == "_") return t;
                this.each(function() {
                    var f = u[r.data(this, "jstree_instance_id")],
                    e = f && r.isFunction(f[n]) ? f[n].apply(f, i) : f;
                    if (typeof e != "undefined" && (n.indexOf("is_") === 0 || e !== !0 && e !== !1)) return t = e,
                    !1
                })
            } else this.each(function() {
                var t = r.data(this, "jstree_instance_id"),
                s = [],
                f = n ? r.extend({},
                !0, n) : {},
                c = r(this),
                e = !1,
                h = [];
                s = s.concat(i),
                c.data("jstree") && s.push(c.data("jstree")),
                f = s.length ? r.extend.apply(null, [!0, f].concat(s)) : f,
                typeof t != "undefined" && u[t] && u[t].destroy(),
                t = parseInt(u.push({}), 10) - 1,
                r.data(this, "jstree_instance_id", t),
                f.plugins = r.isArray(f.plugins) ? f.plugins: r.jstree.defaults.plugins.slice(),
                f.plugins.unshift("core"),
                f.plugins = f.plugins.sort().join(",,").replace(/(,|^)([^,]+)(,,\2)+(,|$)/g, "$1$2$4").replace(/,,+/g, ",").replace(/,$/, "").split(","),
                e = r.extend(!0, {},
                r.jstree.defaults, f),
                e.plugins = f.plugins,
                r.each(o,
                function(n) {
                    r.inArray(n, e.plugins) === -1 ? (e[n] = null, delete e[n]) : h.push(n)
                }),
                e.plugins = h,
                u[t] = new r.jstree._instance(t, r(this).addClass("jstree jstree-" + t), e),
                r.each(u[t]._get_settings().plugins,
                function(n, i) {
                    u[t].data[i] = {}
                }),
                r.each(u[t]._get_settings().plugins,
                function(n, i) {
                    o[i] && o[i].__init.apply(u[t])
                }),
                setTimeout(function() {
                    u[t] && u[t].init()
                },
                0)
            });
            return t
        },
        r.jstree = {
            defaults: {
                plugins: []
            },
            _focused: function() {
                return u[e] || null
            },
            _reference: function(n) {
                if (u[n]) return u[n];
                var t = r(n);
                return ! t.length && typeof n == "string" && (t = r("#" + n)),
                t.length ? u[t.closest(".jstree").data("jstree_instance_id")] || null: null
            },
            _instance: function(n, t, i) {
                this.data = {
                    core: {}
                },
                this.get_settings = function() {
                    return r.extend(!0, {},
                    i)
                },
                this._get_settings = function() {
                    return i
                },
                this.get_index = function() {
                    return n
                },
                this.get_container = function() {
                    return t
                },
                this.get_container_ul = function() {
                    return t.children("ul:eq(0)")
                },
                this._set_settings = function(n) {
                    i = r.extend(!0, {},
                    i, n)
                }
            },
            _fn: {},
            plugin: function(n, t) {
                t = r.extend({},
                {
                    __init: r.noop,
                    __destroy: r.noop,
                    _fn: {},
                    defaults: !1
                },
                t),
                o[n] = t,
                r.jstree.defaults[n] = t.defaults,
                r.each(t._fn,
                function(t, i) {
                    i.plugin = n,
                    i.old = r.jstree._fn[t],
                    r.jstree._fn[t] = function() {
                        var u, n = i,
                        f = Array.prototype.slice.call(arguments),
                        o = new r.Event("before.jstree"),
                        e = !1;
                        if (this.data.core.locked === !0 && t !== "unlock" && t !== "is_locked") return;
                        do {
                            if (n && n.plugin && r.inArray(n.plugin, this._get_settings().plugins) !== -1) break;
                            n = n.old
                        } while ( n );
                        if (!n) return;
                        if (t.indexOf("_") === 0) u = n.apply(this, f);
                        else {
                            u = this.get_container().triggerHandler(o, {
                                func: t,
                                inst: this,
                                args: f,
                                plugin: n.plugin
                            });
                            if (u === !1) return;
                            typeof u != "undefined" && (f = u),
                            u = n.apply(r.extend({},
                            this, {
                                __callback: function(n) {
                                    this.get_container().triggerHandler(t + ".jstree", {
                                        inst: this,
                                        args: f,
                                        rslt: n,
                                        rlbk: e
                                    })
                                },
                                __rollback: function() {
                                    return e = this.get_rollback()
                                },
                                __call_old: function(t) {
                                    return n.old.apply(this, t ? Array.prototype.slice.call(arguments, 1) : f)
                                }
                            }), f)
                        }
                        return u
                    },
                    r.jstree._fn[t].old = i.old,
                    r.jstree._fn[t].plugin = n
                })
            },
            rollback: function(n) {
                n && (r.isArray(n) || (n = [n]), r.each(n,
                function(n, t) {
                    u[t.i].set_rollback(t.h, t.d)
                }))
            }
        },
        r.jstree._fn = r.jstree._instance.prototype = {},
        r(function() {
            var u = navigator.userAgent.toLowerCase(),
            e = (u.match(/.+?(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [0, "0"])[1],
            f = ".jstree ul, .jstree li { display:block; margin:0 0 0 0; padding:0 0 0 0; list-style-type:none; } .jstree li { display:block; min-height:18px; line-height:18px; white-space:nowrap; margin-left:9px; min-width:18px; } .jstree-rtl li { margin-left:0; margin-right:9px; } .jstree > ul > li { margin-left:0px; } .jstree-rtl > ul > li { margin-right:0px; } .jstree ins { display:inline-block; text-decoration:none; width:18px; height:18px; margin:0 0 0 0; padding:0; } .jstree a { display:inline-block; line-height:16px; height:16px; color:black; white-space:nowrap; text-decoration:none; padding:1px 2px; margin:0; } .jstree a:focus { outline: none; } .jstree a > ins { height:16px; width:16px; } .jstree a > .jstree-icon { margin-right:3px; } .jstree-rtl a > .jstree-icon { margin-left:3px; margin-right:0; } li.jstree-open > ul { display:block; } li.jstree-closed > ul { display:none; } ";
            if (/msie/.test(u) && parseInt(e, 10) == 6) {
                n = !0;
                try {
                    document.execCommand("BackgroundImageCache", !1, !0)
                } catch(o) {}
                f += ".jstree li { height:18px; margin-left:0; margin-right:0; } .jstree li li { margin-left:18px; } .jstree-rtl li li { margin-left:0px; margin-right:18px; } li.jstree-open ul { display:block; } li.jstree-closed ul { display:none !important; } .jstree li a { display:inline; border-width:0 !important; padding:0px 2px !important; } .jstree li a ins { height:16px; width:16px; margin-right:3px; } .jstree-rtl li a ins { margin-right:0px; margin-left:3px; } "
            }
            /msie/.test(u) && parseInt(e, 10) == 7 && (t = !0, f += ".jstree li a { border-width:0 !important; padding:0px 2px !important; } "),
            !/compatible/.test(u) && /mozilla/.test(u) && parseFloat(e, 10) < 1.9 && (i = !0, f += ".jstree ins { display:-moz-inline-box; } .jstree li { line-height:12px; } .jstree a { display:-moz-inline-box; } .jstree .jstree-no-icons .jstree-checkbox { display:-moz-inline-stack !important; } "),
            r.vakata.css.add_sheet({

                str: f,
                title: "jstree"
            })
        }),
        r.jstree.plugin("core", {
            __init: function() {
                this.data.core.locked = !1,
                this.data.core.to_open = this.get_settings().core.initially_open,
                this.data.core.to_load = this.get_settings().core.initially_load
            },
            defaults: {
                html_titles: !1,
                animation: 500,
                initially_open: [],
                initially_load: [],
                open_parents: !0,
                notify_plugins: !0,
                rtl: !1,
                load_open: !1,
                strings: {
                    loading: "Loading ...",
                    new_node: "New node",
                    multiple_selection: "Multiple selection"
                }
            },
            _fn: {
                init: function() {
                    this.set_focus(),
                    this._get_settings().core.rtl && this.get_container().addClass("jstree-rtl").css("direction", "rtl"),
                    this.get_container().html("<ul><li class='jstree-last jstree-leaf'><ins>&#160;</ins><a class='jstree-loading' href='#'><ins class='jstree-icon'>&#160;</ins>" + this._get_string("loading") + "</a></li></ul>"),
                    this.data.core.li_height = this.get_container_ul().find("li.jstree-closed, li.jstree-leaf").eq(0).height() || 18,
                    this.get_container().delegate("li > ins", "click.jstree", r.proxy(function(n) {
                        var t = r(n.target);
                        this.toggle_node(t)
                    },
                    this)).bind("mousedown.jstree", r.proxy(function() {
                        this.set_focus()
                    },
                    this)).bind("dblclick.jstree",
                    function() {
                        var t;
                        if (document.selection && document.selection.empty) document.selection.empty();
                        else if (window.getSelection) {
                            t = window.getSelection();
                            try {
                                t.removeAllRanges(),
                                t.collapse()
                            } catch(i) {}
                        }
                    }),
                    this._get_settings().core.notify_plugins && this.get_container().bind("load_node.jstree", r.proxy(function(n, t) {
                        var u = this._get_node(t.rslt.obj),
                        i = this;
                        u === -1 && (u = this.get_container_ul());
                        if (!u.length) return;
                        u.find("li").each(function() {
                            var n = r(this);
                            n.data("jstree") && r.each(n.data("jstree"),
                            function(t, u) {
                                i.data[t] && r.isFunction(i["_" + t + "_notify"]) && i["_" + t + "_notify"].call(i, n, u)
                            })
                        })
                    },
                    this)),
                    this._get_settings().core.load_open && this.get_container().bind("load_node.jstree", r.proxy(function(n, t) {
                        var i = this._get_node(t.rslt.obj),
                        u = this;
                        i === -1 && (i = this.get_container_ul());
                        if (!i.length) return;
                        i.find("li.jstree-open:not(:has(ul))").each(function() {
                            u.load_node(this, r.noop, r.noop)
                        })
                    },
                    this)),
                    this.__callback(),
                    this.load_node( - 1,
                    function() {
                        this.loaded(),
                        this.reload_nodes()
                    })
                },
                destroy: function() {
                    var t, n = this.get_index(),
                    f = this._get_settings(),
                    i = this;
                    r.each(f.plugins,
                    function(n, t) {
                        try {
                            o[t].__destroy.apply(i)
                        } catch(r) {}
                    }),
                    this.__callback();
                    if (this.is_focused()) for (t in u) if (u.hasOwnProperty(t) && t != n) {
                        u[t].set_focus();
                        break
                    }
                    n === e && (e = -1),
                    this.get_container().unbind(".jstree").undelegate(".jstree").removeData("jstree_instance_id").find("[class^='jstree']").andSelf().attr("class",
                    function() {
                        return this.className.replace(/jstree[^ ]*|$/ig, "")
                    }),
                    r(document).unbind(".jstree-" + n).undelegate(".jstree-" + n),
                    u[n] = null,
                    delete u[n]
                },
                _core_notify: function(n, t) {
                    t.opened && this.open_node(n, !1, !0)
                },
                lock: function() {
                    this.data.core.locked = !0,
                    this.get_container().children("ul").addClass("jstree-locked").css("opacity", "0.7"),
                    this.__callback({})
                },
                unlock: function() {
                    this.data.core.locked = !1,
                    this.get_container().children("ul").removeClass("jstree-locked").css("opacity", "1"),
                    this.__callback({})
                },
                is_locked: function() {
                    return this.data.core.locked
                },
                save_opened: function() {
                    var n = this;
                    this.data.core.to_open = [],
                    this.get_container_ul().find("li.jstree-open").each(function() {
                        this.id && n.data.core.to_open.push("#" + this.id.toString().replace(/^#/, "").replace(/\\\//g, "/").replace(/\//g, "\\/").replace(/\\\./g, ".").replace(/\./g, "\\.").replace(/\:/g, "\\:"))
                    }),
                    this.__callback(n.data.core.to_open)
                },
                save_loaded: function() {},
                reload_nodes: function(n) {
                    var t = this,
                    f = !0,
                    i = [],
                    u = [];
                    n || (this.data.core.reopen = !1, this.data.core.refreshing = !0, this.data.core.to_open = r.map(r.makeArray(this.data.core.to_open),
                    function(n) {
                        return "#" + n.toString().replace(/^#/, "").replace(/\\\//g, "/").replace(/\//g, "\\/").replace(/\\\./g, ".").replace(/\./g, "\\.").replace(/\:/g, "\\:")
                    }), this.data.core.to_load = r.map(r.makeArray(this.data.core.to_load),
                    function(n) {
                        return "#" + n.toString().replace(/^#/, "").replace(/\\\//g, "/").replace(/\//g, "\\/").replace(/\\\./g, ".").replace(/\./g, "\\.").replace(/\:/g, "\\:")
                    }), this.data.core.to_open.length && (this.data.core.to_load = this.data.core.to_load.concat(this.data.core.to_open))),
                    this.data.core.to_load.length && (r.each(this.data.core.to_load,
                    function(n, t) {
                        if (t == "#") return ! 0;
                        r(t).length ? i.push(t) : u.push(t)
                    }), i.length && (this.data.core.to_load = u, r.each(i,
                    function(n, i) {
                        t._is_loaded(i) || (t.load_node(i,
                        function() {
                            t.reload_nodes(!0)
                        },
                        function() {
                            t.reload_nodes(!0)
                        }), f = !1)
                    }))),
                    this.data.core.to_open.length && r.each(this.data.core.to_open,
                    function(n, i) {
                        t.open_node(i, !1, !0)
                    }),
                    f && (this.data.core.reopen && clearTimeout(this.data.core.reopen), this.data.core.reopen = setTimeout(function() {
                        t.__callback({},
                        t)
                    },
                    50), this.data.core.refreshing = !1, this.reopen())
                },
                reopen: function() {
                    var n = this;
                    this.data.core.to_open.length && r.each(this.data.core.to_open,
                    function(t, i) {
                        n.open_node(i, !1, !0)
                    }),
                    this.__callback({})
                },
                refresh: function(n) {
                    var t = this;
                    this.save_opened(),
                    n || (n = -1),
                    n = this._get_node(n),
                    n || (n = -1),
                    n !== -1 ? n.children("UL").remove() : this.get_container_ul().empty(),
                    this.load_node(n,
                    function() {
                        t.__callback({
                            obj: n
                        }),
                        t.reload_nodes()
                    })
                },
                loaded: function() {
                    this.__callback()
                },
                set_focus: function() {
                    if (this.is_focused()) return;
                    var n = r.jstree._focused();
                    n && n.unset_focus(),
                    this.get_container().addClass("jstree-focused"),
                    e = this.get_index(),
                    this.__callback()
                },
                is_focused: function() {
                    return e == this.get_index()
                },
                unset_focus: function() {
                    this.is_focused() && (this.get_container().removeClass("jstree-focused"), e = -1),
                    this.__callback()
                },
                _get_node: function(n) {
                    var t = r(n, this.get_container());
                    return t.is(".jstree") || n == -1 ? -1 : (t = t.closest("li", this.get_container()), t.length ? t: !1)
                },
                _get_next: function(n, t) {
                    return n = this._get_node(n),
                    n === -1 ? this.get_container().find("> ul > li:first-child") : n.length ? t ? n.nextAll("li").size() > 0 ? n.nextAll("li:eq(0)") : !1 : n.hasClass("jstree-open") ? n.find("li:eq(0)") : n.nextAll("li").size() > 0 ? n.nextAll("li:eq(0)") : n.parentsUntil(".jstree", "li").next("li").eq(0) : !1
                },
                _get_prev: function(n, t) {
                    n = this._get_node(n);
                    if (n === -1) return this.get_container().find("> ul > li:last-child");
                    if (!n.length) return ! 1;
                    if (t) return n.prevAll("li").length > 0 ? n.prevAll("li:eq(0)") : !1;
                    if (n.prev("li").length) {
                        n = n.prev("li").eq(0);
                        while (n.hasClass("jstree-open")) n = n.children("ul:eq(0)").children("li:last");
                        return n
                    }
                    var i = n.parentsUntil(".jstree", "li:eq(0)");
                    return i.length ? i: !1
                },
                _get_parent: function(n) {
                    n = this._get_node(n);
                    if (n == -1 || !n.length) return ! 1;
                    var t = n.parentsUntil(".jstree", "li:eq(0)");
                    return t.length ? t: -1
                },
                _get_children: function(n) {
                    return n = this._get_node(n),
                    n === -1 ? this.get_container().children("ul:eq(0)").children("li") : n.length ? n.children("ul:eq(0)").children("li") : !1
                },
                get_path: function(n, t) {
                    var i = [],
                    r = this;
                    return n = this._get_node(n),
                    n === -1 || !n || !n.length ? !1 : (n.parentsUntil(".jstree", "li").each(function() {
                        i.push(t ? this.id: r.get_text(this))
                    }), i.reverse(), i.push(t ? n.attr("id") : this.get_text(n)), i)
                },
                _get_string: function(n) {
                    return this._get_settings().core.strings[n] || n
                },
                is_open: function(n) {
                    return n = this._get_node(n),
                    n && n !== -1 && n.hasClass("jstree-open")
                },
                is_closed: function(n) {
                    return n = this._get_node(n),
                    n && n !== -1 && n.hasClass("jstree-closed")
                },
                is_leaf: function(n) {
                    return n = this._get_node(n),
                    n && n !== -1 && n.hasClass("jstree-leaf")
                },
                correct_state: function(n) {
                    n = this._get_node(n);
                    if (!n || n === -1) return ! 1;
                    n.removeClass("jstree-closed jstree-open").addClass("jstree-leaf").children("ul").remove(),
                    this.__callback({
                        obj: n
                    })
                },
                open_node: function(t, i, r) {
                    t = this._get_node(t);
                    if (!t.length) return ! 1;
                    if (!t.hasClass("jstree-closed")) return i && i.call(),
                    !1;
                    var f = r || n ? 0 : this._get_settings().core.animation,
                    u = this;
                    this._is_loaded(t) ? (this._get_settings().core.open_parents && t.parentsUntil(".jstree", ".jstree-closed").each(function() {
                        u.open_node(this, !1, !0)
                    }), f && t.children("ul").css("display", "none"), t.removeClass("jstree-closed").addClass("jstree-open").children("a").removeClass("jstree-loading"), f ? t.children("ul").stop(!0, !0).slideDown(f,
                    function() {
                        this.style.display = "",
                        u.after_open(t)
                    }) : u.after_open(t), this.__callback({
                        obj: t
                    }), i && i.call()) : (t.children("a").addClass("jstree-loading"), this.load_node(t,
                    function() {
                        u.open_node(t, i, r)
                    },
                    i))
                },
                after_open: function(n) {
                    this.__callback({
                        obj: n
                    })
                },
                close_node: function(t, i) {
                    t = this._get_node(t);
                    var r = i || n ? 0 : this._get_settings().core.animation,
                    u = this;
                    if (!t.length || !t.hasClass("jstree-open")) return ! 1;
                    r && t.children("ul").attr("style", "display:block !important"),
                    t.removeClass("jstree-open").addClass("jstree-closed"),
                    r ? t.children("ul").stop(!0, !0).slideUp(r,
                    function() {
                        this.style.display = "",
                        u.after_close(t)
                    }) : u.after_close(t),
                    this.__callback({
                        obj: t
                    })
                },
                after_close: function(n) {
                    this.__callback({
                        obj: n
                    })
                },
                toggle_node: function(n) {
                    return n = this._get_node(n),
                    n.hasClass("jstree-closed") ? this.open_node(n) : n.hasClass("jstree-open") ? this.close_node(n) : void 0
                },
                open_all: function(n, t, i) {

                    n = n ? this._get_node(n) : -1,
                    (!n || n === -1) && (n = this.get_container_ul()),
                    i ? n = n.find("li.jstree-closed") : (i = n, n = n.is(".jstree-closed") ? n.find("li.jstree-closed").andSelf() : n.find("li.jstree-closed"));
                    var r = this;
                    n.each(function() {
                        var n = this;
                        r._is_loaded(this) ? r.open_node(this, !1, !t) : r.open_node(this,
                        function() {
                            r.open_all(n, t, i)
                        },
                        !t)
                    }),
                    i.find("li.jstree-closed").length === 0 && this.__callback({
                        obj: i
                    })
                },
                close_all: function(n, t) {
                    var i = this;
                    n = n ? this._get_node(n) : this.get_container(),
                    (!n || n === -1) && (n = this.get_container_ul()),
                    n.find("li.jstree-open").andSelf().each(function() {
                        i.close_node(this, !t)
                    }),
                    this.__callback({
                        obj: n
                    })
                },
                clean_node: function(n) {
                    n = n && n != -1 ? r(n) : this.get_container_ul(),
                    n = n.is("li") ? n.find("li").andSelf() : n.find("li"),
                    n.removeClass("jstree-last").filter("li:last-child").addClass("jstree-last").end().filter(":has(li)").not(".jstree-open").removeClass("jstree-leaf").addClass("jstree-closed"),
                    n.not(".jstree-open, .jstree-closed").addClass("jstree-leaf").children("ul").remove(),
                    this.__callback({
                        obj: n
                    })
                },
                get_rollback: function() {
                    return this.__callback(),
                    {
                        i: this.get_index(),
                        h: this.get_container().children("ul").clone(!0),
                        d: this.data
                    }
                },
                set_rollback: function(n, t) {
                    this.get_container().empty().append(n),
                    this.data = t,
                    this.__callback()
                },
                load_node: function(n) {
                    this.__callback({
                        obj: n
                    })
                },
                _is_loaded: function() {
                    return ! 0
                },
                create_node: function(n, t, i, u, f) {
                    n = this._get_node(n),
                    t = typeof t == "undefined" ? "last": t;
                    var o = r("<li />"),
                    s = this._get_settings().core,
                    e;
                    if (n !== -1 && !n.length) return ! 1;
                    if (!f && !this._is_loaded(n)) return this.load_node(n,
                    function() {
                        this.create_node(n, t, i, u, !0)
                    }),
                    !1;
                    this.__rollback(),
                    typeof i == "string" && (i = {
                        data: i
                    }),
                    i || (i = {}),
                    i.attr && o.attr(i.attr),
                    i.metadata && o.data(i.metadata),
                    i.state && o.addClass("jstree-" + i.state),
                    i.data || (i.data = this._get_string("new_node")),
                    r.isArray(i.data) || (e = i.data, i.data = [], i.data.push(e)),
                    r.each(i.data,
                    function(n, t) {
                        e = r("<a />"),
                        r.isFunction(t) && (t = t.call(this, i)),
                        typeof t == "string" ? e.attr("href", "#")[s.html_titles ? "html": "text"](t) : (t.attr || (t.attr = {}), t.attr.href || (t.attr.href = "#"), e.attr(t.attr)[s.html_titles ? "html": "text"](t.title), t.language && e.addClass(t.language)),
                        e.prepend("<ins class='jstree-icon'>&#160;</ins>"),
                        !t.icon && i.icon && (t.icon = i.icon),
                        t.icon && (t.icon.indexOf("/") === -1 ? e.children("ins").addClass(t.icon) : e.children("ins").css("background", "url('" + t.icon + "') center center no-repeat")),
                        o.append(e)
                    }),
                    o.prepend("<ins class='jstree-icon'>&#160;</ins>"),
                    n === -1 && (n = this.get_container(), t === "before" && (t = "first"), t === "after" && (t = "last"));
                    switch (t) {
                    case "before":
                        n.before(o),
                        e = this._get_parent(n);
                        break;
                    case "after":
                        n.after(o),
                        e = this._get_parent(n);
                        break;
                    case "inside":
                    case "first":
                        n.children("ul").length || n.append("<ul />"),
                        n.children("ul").prepend(o),
                        e = n;
                        break;
                    case "last":
                        n.children("ul").length || n.append("<ul />"),
                        n.children("ul").append(o),
                        e = n;
                        break;
                    default:
                        n.children("ul").length || n.append("<ul />"),
                        t || (t = 0),
                        e = n.children("ul").children("li").eq(t),
                        e.length ? e.before(o) : n.children("ul").append(o),
                        e = n
                    }
                    return (e === -1 || e.get(0) === this.get_container().get(0)) && (e = -1),
                    this.clean_node(e),
                    this.__callback({
                        obj: o,
                        parent: e
                    }),
                    u && u.call(this, o),
                    o
                },
                get_text: function(n) {
                    n = this._get_node(n);
                    if (!n.length) return ! 1;
                    var t = this._get_settings().core.html_titles;
                    return n = n.children("a:eq(0)"),
                    t ? (n = n.clone(), n.children("INS").remove(), n.html()) : (n = n.contents().filter(function() {
                        return this.nodeType == 3
                    })[0], n.nodeValue)
                },
                set_text: function(n, t) {
                    n = this._get_node(n);
                    if (!n.length) return ! 1;
                    n = n.children("a:eq(0)");
                    if (this._get_settings().core.html_titles) {
                        var i = n.children("INS").clone();
                        return n.html(t).prepend(i),
                        this.__callback({
                            obj: n,
                            name: t
                        }),
                        !0
                    }
                    return n = n.contents().filter(function() {
                        return this.nodeType == 3
                    })[0],
                    this.__callback({
                        obj: n,
                        name: t
                    }),
                    n.nodeValue = t
                },
                rename_node: function(n, t) {
                    n = this._get_node(n),
                    this.__rollback(),
                    n && n.length && this.set_text.apply(this, Array.prototype.slice.call(arguments)) && this.__callback({
                        obj: n,
                        name: t
                    })
                },
                delete_node: function(n) {
                    n = this._get_node(n);
                    if (!n.length) return ! 1;
                    this.__rollback();
                    var t = this._get_parent(n),
                    i = r([]),
                    u = this;
                    return n.each(function() {
                        i = i.add(u._get_prev(this))
                    }),
                    n = n.detach(),
                    t !== -1 && t.find("> ul > li").length === 0 && t.removeClass("jstree-open jstree-closed").addClass("jstree-leaf"),
                    this.clean_node(t),
                    this.__callback({
                        obj: n,
                        prev: i,
                        parent: t
                    }),
                    n
                },
                prepare_move: function(n, t, i, u, e) {
                    var o = {};
                    o.ot = r.jstree._reference(n) || this,
                    o.o = o.ot._get_node(n),
                    o.r = t === -1 ? -1 : this._get_node(t),
                    o.p = typeof i == "undefined" || i === !1 ? "last": i;
                    if (!e && f.o && f.o[0] === o.o[0] && f.r[0] === o.r[0] && f.p === o.p) {
                        this.__callback(f),
                        u && u.call(this, f);
                        return
                    }
                    o.ot = r.jstree._reference(o.o) || this,
                    o.rt = r.jstree._reference(o.r) || this;
                    if (o.r === -1 || !o.r) {
                        o.cr = -1;
                        switch (o.p) {
                        case "first":
                        case "before":
                        case "inside":
                            o.cp = 0;
                            break;
                        case "after":
                        case "last":
                            o.cp = o.rt.get_container().find(" > ul > li").length;
                            break;
                        default:
                            o.cp = o.p
                        }
                    } else {
                        if (!/^(before|after)$/.test(o.p) && !this._is_loaded(o.r)) return this.load_node(o.r,
                        function() {
                            this.prepare_move(n, t, i, u, !0)
                        });
                        switch (o.p) {
                        case "before":
                            o.cp = o.r.index(),
                            o.cr = o.rt._get_parent(o.r);
                            break;
                        case "after":
                            o.cp = o.r.index() + 1,
                            o.cr = o.rt._get_parent(o.r);
                            break;
                        case "inside":
                        case "first":
                            o.cp = 0,
                            o.cr = o.r;
                            break;
                        case "last":
                            o.cp = o.r.find(" > ul > li").length,
                            o.cr = o.r;
                            break;
                        default:
                            o.cp = o.p,
                            o.cr = o.r
                        }
                    }
                    o.np = o.cr == -1 ? o.rt.get_container() : o.cr,
                    o.op = o.ot._get_parent(o.o),
                    o.cop = o.o.index(),
                    o.op === -1 && (o.op = o.ot ? o.ot.get_container() : this.get_container()),
                    !/^(before|after)$/.test(o.p) && o.op && o.np && o.op[0] === o.np[0] && o.o.index() < o.cp && o.cp++,
                    o.or = o.np.find(" > ul > li:nth-child(" + (o.cp + 1) + ")"),
                    f = o,
                    this.__callback(f),
                    u && u.call(this, f)
                },
                check_move: function() {
                    var n = f,
                    t = !0,
                    i = n.r === -1 ? this.get_container() : n.r;
                    return ! n || !n.o || n.or[0] === n.o[0] ? !1 : n.op && n.np && n.op[0] === n.np[0] && n.cp - 1 === n.o.index() ? !1 : (n.o.each(function() {
                        if (i.parentsUntil(".jstree", "li").andSelf().index(this) !== -1) return t = !1,
                        !1
                    }), t)
                },
                move_node: function(n, t, i, u, e, o) {
                    if (!e) return this.prepare_move(n, t, i,
                    function(n) {
                        this.move_node(n, !1, !1, u, !0, o)
                    });
                    u && (f.cy = !0);
                    if (!o && !this.check_move()) return ! 1;
                    this.__rollback();
                    var s = !1;
                    u ? (s = n.o.clone(!0), s.find("*[id]").andSelf().each(function() {
                        this.id && (this.id = "copy_" + this.id)
                    })) : s = n.o,
                    n.or.length ? n.or.before(s) : (n.np.children("ul").length || r("<ul />").appendTo(n.np), n.np.children("ul:eq(0)").append(s));
                    try {
                        n.ot.clean_node(n.op),
                        n.rt.clean_node(n.np),
                        n.op.find("> ul > li").length || n.op.removeClass("jstree-open jstree-closed").addClass("jstree-leaf").children("ul").remove()
                    } catch(h) {}
                    return u && (f.cy = !0, f.oc = s),
                    this.__callback(f),
                    f
                },
                _get_move: function() {
                    return f
                }
            }
        })
    })(jQuery),
    (function(n) {
        var i, t, r;
        n(function() { / msie / .test(navigator.userAgent.toLowerCase()) ? (t = n('<textarea cols="10" rows="2"></textarea>').css({
                position: "absolute",
                top: -1e3,
                left: 0
            }).appendTo("body"), r = n('<textarea cols="10" rows="2" style="overflow: hidden;"></textarea>').css({
                position: "absolute",
                top: -1e3,
                left: 0
            }).appendTo("body"), i = t.width() - r.width(), t.add(r).remove()) : (t = n("<div />").css({
                width: 100,
                height: 100,
                overflow: "auto",
                position: "absolute",
                top: -1e3,
                left: 0
            }).prependTo("body").append("<div />").find("div").css({
                width: "100%",
                height: 200
            }), i = 100 - t.width(), t.parent().remove())
        }),
        n.jstree.plugin("ui", {
            __init: function() {
                this.data.ui.selected = n(),
                this.data.ui.last_selected = !1,
                this.data.ui.hovered = null,
                this.data.ui.to_select = this.get_settings().ui.initially_select,
                this.get_container().delegate("a", "click.jstree", n.proxy(function(t) {
                    t.preventDefault(),
                    t.currentTarget.blur(),
                    n(t.currentTarget).hasClass("jstree-loading") || this.select_node(t.currentTarget, !0, t)
                },
                this)).delegate("a", "mouseenter.jstree", n.proxy(function(t) {
                    n(t.currentTarget).hasClass("jstree-loading") || this.hover_node(t.target)
                },
                this)).delegate("a", "mouseleave.jstree", n.proxy(function(t) {
                    n(t.currentTarget).hasClass("jstree-loading") || this.dehover_node(t.target)
                },
                this)).bind("reopen.jstree", n.proxy(function() {
                    this.reselect()
                },
                this)).bind("get_rollback.jstree", n.proxy(function() {
                    this.dehover_node(),
                    this.save_selected()
                },
                this)).bind("set_rollback.jstree", n.proxy(function() {
                    this.reselect()
                },
                this)).bind("close_node.jstree", n.proxy(function(t, i) {
                    var e = this._get_settings().ui,
                    r = this._get_node(i.rslt.obj),
                    f = r && r.length ? r.children("ul").find("a.jstree-clicked") : n(),
                    u = this;
                    if (e.selected_parent_close === !1 || !f.length) return;
                    f.each(function() {
                        u.deselect_node(this),
                        e.selected_parent_close === "select_parent" && u.select_node(r)
                    })
                },
                this)).bind("delete_node.jstree", n.proxy(function(n, t) {
                    var f = this._get_settings().ui.select_prev_on_delete,
                    i = this._get_node(t.rslt.obj),
                    r = i && i.length ? i.find("a.jstree-clicked") : [],
                    u = this;
                    r.each(function() {
                        u.deselect_node(this)
                    }),
                    f && r.length && t.rslt.prev.each(function() {
                        if (this.parentNode) return u.select_node(this),
                        !1
                    })
                },
                this)).bind("move_node.jstree", n.proxy(function(n, t) {
                    t.rslt.cy && t.rslt.oc.find("a.jstree-clicked").removeClass("jstree-clicked")
                },
                this))
            },
            defaults: {
                select_limit: -1,
                select_multiple_modifier: "ctrl",
                select_range_modifier: "shift",
                selected_parent_close: "select_parent",
                selected_parent_open: !0,
                select_prev_on_delete: !0,
                disable_selecting_children: !1,
                initially_select: []
            },
            _fn: {
                _get_node: function(t, i) {
                    if (typeof t == "undefined" || t === null) return i ? this.data.ui.selected: this.data.ui.last_selected;
                    var r = n(t, this.get_container());
                    return r.is(".jstree") || t == -1 ? -1 : (r = r.closest("li", this.get_container()), r.length ? r: !1)
                },
                _ui_notify: function(n, t) {
                    t.selected && this.select_node(n, !1)
                },
                save_selected: function() {
                    var n = this;
                    this.data.ui.to_select = [],
                    this.data.ui.selected.each(function() {
                        this.id && n.data.ui.to_select.push("#" + this.id.toString().replace(/^#/, "").replace(/\\\//g, "/").replace(/\//g, "\\/").replace(/\\\./g, ".").replace(/\./g, "\\.").replace(/\:/g, "\\:"))
                    }),
                    this.__callback(this.data.ui.to_select)
                },
                reselect: function() {
                    var i = this,
                    t = this.data.ui.to_select;
                    t = n.map(n.makeArray(t),
                    function(n) {
                        return "#" + n.toString().replace(/^#/, "").replace(/\\\//g, "/").replace(/\//g, "\\/").replace(/\\\./g, ".").replace(/\./g, "\\.").replace(/\:/g, "\\:")
                    }),
                    n.each(t,
                    function(n, t) {
                        t && t !== "#" && i.select_node(t)
                    }),
                    this.data.ui.selected = this.data.ui.selected.filter(function() {
                        return this.parentNode
                    }),
                    this.__callback()
                },
                refresh: function() {
                    return this.save_selected(),
                    this.__call_old()
                },
                hover_node: function(n) {
                    n = this._get_node(n);
                    if (!n.length) return ! 1;
                    n.hasClass("jstree-hovered") || this.dehover_node(),
                    this.data.ui.hovered = n.children("a").addClass("jstree-hovered").parent(),
                    this._fix_scroll(n),
                    this.__callback({
                        obj: n
                    })
                },
                dehover_node: function() {
                    var n = this.data.ui.hovered,
                    t;
                    if (!n || !n.length) return ! 1;
                    t = n.children("a").removeClass("jstree-hovered").parent(),
                    this.data.ui.hovered[0] === t[0] && (this.data.ui.hovered = null),
                    this.__callback({
                        obj: n
                    })
                },
                select_node: function(n, t, i) {
                    n = this._get_node(n);
                    if (n == -1 || !n || !n.length) return ! 1;
                    var r = this._get_settings().ui,
                    e = r.select_multiple_modifier == "on" || r.select_multiple_modifier !== !1 && i && i[r.select_multiple_modifier + "Key"],
                    s = r.select_range_modifier !== !1 && i && i[r.select_range_modifier + "Key"] && this.data.ui.last_selected && this.data.ui.last_selected[0] !== n[0] && this.data.ui.last_selected.parent()[0] === n.parent()[0],
                    u = this.is_selected(n),
                    f = !0,
                    o = this;
                    if (t) {
                        if (r.disable_selecting_children && e && (n.parentsUntil(".jstree", "li").children("a.jstree-clicked").length || n.children("ul").find("a.jstree-clicked:eq(0)").length)) return ! 1;
                        f = !1;
                        switch (!0) {
                        case s:
                            this.data.ui.last_selected.addClass("jstree-last-selected"),
                            n = n[n.index() < this.data.ui.last_selected.index() ? "nextUntil": "prevUntil"](".jstree-last-selected").andSelf(),
                            r.select_limit == -1 || n.length < r.select_limit ? (this.data.ui.last_selected.removeClass("jstree-last-selected"), this.data.ui.selected.each(function() {
                                this !== o.data.ui.last_selected[0] && o.deselect_node(this)
                            }), u = !1, f = !0) : f = !1;
                            break;
                        case u && !e: this.deselect_all(),
                            u = !1,
                            f = !0;
                            break;
                        case ! u && !e: (r.select_limit == -1 || r.select_limit > 0) && (this.deselect_all(), f = !0);
                            break;
                        case u && e: this.deselect_node(n);
                            break;
                        case ! u && e: (r.select_limit == -1 || this.data.ui.selected.length + 1 <= r.select_limit) && (f = !0)
                        }
                    }
                    f && !u && (s || (this.data.ui.last_selected = n), n.children("a").addClass("jstree-clicked"), r.selected_parent_open && n.parents(".jstree-closed").each(function() {
                        o.open_node(this, !1, !0)
                    }), this.data.ui.selected = this.data.ui.selected.add(n), this._fix_scroll(n.eq(0)), this.__callback({
                        obj: n,
                        e: i
                    }))
                },
                _fix_scroll: function(n) {
                    var t = this.get_container()[0],
                    r;
                    if (t.scrollHeight > t.offsetHeight) {
                        n = this._get_node(n);
                        if (!n || n === -1 || !n.length || !n.is(":visible")) return;
                        r = n.offset().top - this.get_container().offset().top,
                        r < 0 && (t.scrollTop = t.scrollTop + r - 1),
                        r + this.data.core.li_height + (t.scrollWidth > t.offsetWidth ? i: 0) > t.offsetHeight && (t.scrollTop = t.scrollTop + (r - t.offsetHeight + this.data.core.li_height + 1 + (t.scrollWidth > t.offsetWidth ? i: 0)))
                    }
                },
                deselect_node: function(n) {
                    n = this._get_node(n);
                    if (!n.length) return ! 1;
                    this.is_selected(n) && (n.children("a").removeClass("jstree-clicked"), this.data.ui.selected = this.data.ui.selected.not(n), this.data.ui.last_selected.get(0) === n.get(0) && (this.data.ui.last_selected = this.data.ui.selected.eq(0)), this.__callback({
                        obj: n
                    }))
                },
                toggle_select: function(n) {
                    n = this._get_node(n);
                    if (!n.length) return ! 1;
                    this.is_selected(n) ? this.deselect_node(n) : this.select_node(n)
                },
                is_selected: function(n) {
                    return this.data.ui.selected.index(this._get_node(n)) >= 0
                },
                get_selected: function(t) {
                    return t ? n(t).find("a.jstree-clicked").parent() : this.data.ui.selected
                },
                deselect_all: function(t) {
                    var i = t ? n(t).find("a.jstree-clicked").parent() : this.get_container().find("a.jstree-clicked").parent();
                    i.children("a.jstree-clicked").removeClass("jstree-clicked"),
                    this.data.ui.selected = n([]),
                    this.data.ui.last_selected = !1,
                    this.__callback({
                        obj: i
                    })
                }
            }
        }),
        n.jstree.defaults.plugins.push("ui")
    })(jQuery),
    (function(n) {
        n.jstree.plugin("crrm", {
            __init: function() {
                this.get_container().bind("move_node.jstree", n.proxy(function(n, t) {
                    if (this._get_settings().crrm.move.open_onmove) {
                        var i = this;
                        t.rslt.np.parentsUntil(".jstree").andSelf().filter(".jstree-closed").each(function() {
                            i.open_node(this, !1, !0)
                        })
                    }
                },
                this))
            },
            defaults: {
                input_width_limit: 200,
                move: {
                    always_copy: !1,
                    open_onmove: !0,
                    default_position: "last",
                    check_move: function() {
                        return ! 0
                    }
                }
            },
            _fn: {
                _show_input: function(t, i) {
                    t = this._get_node(t);
                    var e = this._get_settings().core.rtl,
                    s = this._get_settings().crrm.input_width_limit,
                    o = t.children("ins").width(),
                    h = t.find("> a:visible > ins").width() * t.find("> a:visible > ins").length,
                    u = this.get_text(t),
                    f = n("<div />", {
                        css: {
                            position: "absolute",
                            top: "-200px",
                            left: e ? "0px": "-1000px",
                            visibility: "hidden"
                        }
                    }).appendTo("body"),
                    r = t.css("position", "relative").append(n("<input />", {
                        value: u,
                        "class": "jstree-rename-input",
                        css: {
                            padding: "0",
                            border: "1px solid silver",
                            position: "absolute",
                            left: e ? "auto": o + h + 4 + "px",
                            right: e ? o + h + 4 + "px": "auto",
                            top: "0px",
                            height: this.data.core.li_height - 2 + "px",
                            lineHeight: this.data.core.li_height - 2 + "px",
                            width: "150px"
                        },
                        blur: n.proxy(function() {
                            var r = t.children(".jstree-rename-input"),
                            n = r.val();
                            n === "" && (n = u),
                            f.remove(),
                            r.remove(),
                            this.set_text(t, u),
                            this.rename_node(t, n),
                            i.call(this, t, n, u),
                            t.css("position", "")
                        },
                        this),
                        keyup: function(n) {
                            var t = n.keyCode || n.which;
                            if (t == 27) {
                                this.value = u,
                                this.blur();
                                return
                            }
                            if (t == 13) {
                                this.blur();
                                return
                            }
                            r.width(Math.min(f.text("pW" + this.value).width(), s))
                        },
                        keypress: function(n) {
                            var t = n.keyCode || n.which;
                            if (t == 13) return ! 1
                        }
                    })).children(".jstree-rename-input");
                    this.set_text(t, ""),
                    f.css({
                        fontFamily: r.css("fontFamily") || "",
                        fontSize: r.css("fontSize") || "",
                        fontWeight: r.css("fontWeight") || "",
                        fontStyle: r.css("fontStyle") || "",
                        fontStretch: r.css("fontStretch") || "",
                        fontVariant: r.css("fontVariant") || "",
                        letterSpacing: r.css("letterSpacing") || "",
                        wordSpacing: r.css("wordSpacing") || ""
                    }),
                    r.width(Math.min(f.text("pW" + r[0].value).width(), s))[0].select()
                },
                rename: function(n) {
                    n = this._get_node(n),
                    this.__rollback();
                    var t = this.__callback;
                    this._show_input(n,
                    function(n, i, r) {
                        t.call(this, {
                            obj: n,
                            new_name: i,
                            old_name: r
                        })
                    })
                },
                create: function(t, i, r, u, f) {
                    var o, e = this;
                    return t = this._get_node(t),
                    t || (t = -1),
                    this.__rollback(),
                    o = this.create_node(t, i, r,
                    function(t) {
                        var i = this._get_parent(t),
                        r = n(t).index();
                        u && u.call(this, t),
                        i.length && i.hasClass("jstree-closed") && this.open_node(i, !1, !0),
                        f ? e.__callback({
                            obj: t,
                            name: this.get_text(t),
                            parent: i,
                            position: r
                        }) : this._show_input(t,
                        function(n, t) {
                            e.__callback({
                                obj: n,
                                name: t,
                                parent: i,
                                position: r
                            })
                        })
                    })
                },
                remove: function(n) {
                    n = this._get_node(n, !0);
                    var i = this._get_parent(n),
                    t = this._get_prev(n);
                    this.__rollback(),
                    n = this.delete_node(n),
                    n !== !1 && this.__callback({
                        obj: n,
                        prev: t,
                        parent: i
                    })
                },
                check_move: function() {
                    if (!this.__call_old()) return ! 1;
                    var n = this._get_settings().crrm.move;
                    return n.check_move.call(this, this._get_move()) ? !0 : !1
                },
                move_node: function(n, t, i, r, u, f) {
                    var e = this._get_settings().crrm.move;
                    if (!u) return typeof i == "undefined" && (i = e.default_position),
                    i === "inside" && !e.default_position.match(/^(before|after)$/) && (i = e.default_position),
                    this.__call_old(!0, n, t, i, r, !1, f); (e.always_copy === !0 || e.always_copy === "multitree" && n.rt.get_index() !== n.ot.get_index()) && (r = !0),
                    this.__call_old(!0, n, t, i, r, !0, f)
                },
                cut: function(n) {
                    n = this._get_node(n, !0);
                    if (!n || !n.length) return ! 1;
                    this.data.crrm.cp_nodes = !1,
                    this.data.crrm.ct_nodes = n,
                    this.__callback({
                        obj: n
                    })
                },
                copy: function(n) {
                    n = this._get_node(n, !0);
                    if (!n || !n.length) return ! 1;
                    this.data.crrm.ct_nodes = !1,
                    this.data.crrm.cp_nodes = n,
                    this.__callback({
                        obj: n
                    })
                },
                paste: function(n) {
                    n = this._get_node(n);
                    if (!n || !n.length) return ! 1;
                    var t = this.data.crrm.ct_nodes ? this.data.crrm.ct_nodes: this.data.crrm.cp_nodes;
                    if (!this.data.crrm.ct_nodes && !this.data.crrm.cp_nodes) return ! 1;
                    this.data.crrm.ct_nodes && (this.move_node(this.data.crrm.ct_nodes, n), this.data.crrm.ct_nodes = !1),
                    this.data.crrm.cp_nodes && this.move_node(this.data.crrm.cp_nodes, n, !1, !0),
                    this.__callback({
                        obj: n,
                        nodes: t
                    })
                }
            }
        })
    })(jQuery),
    (function(n) {
        var t = [];
        n.jstree._themes = !1,
        n.jstree.plugin("themes", {
            __init: function() {
                this.get_container().bind("init.jstree", n.proxy(function() {
                    var n = this._get_settings().themes;
                    this.data.themes.dots = n.dots,
                    this.data.themes.icons = n.icons,
                    this.set_theme(n.theme, n.url)
                },
                this)).bind("loaded.jstree", n.proxy(function() {
                    this.data.themes.dots ? this.show_dots() : this.hide_dots(),
                    this.data.themes.icons ? this.show_icons() : this.hide_icons()
                },
                this))
            },
            defaults: {
                theme: "default",
                url: !1,
                dots: !0,
                icons: !0
            },
            _fn: {
                set_theme: function(i, r) {
                    if (!i) return ! 1;
                    r || (r = n.jstree._themes + i + "/style.css"),
                    n.inArray(r, t) == -1 && (n.vakata.css.add_sheet({
                        url: r
                    }), t.push(r)),
                    this.data.themes.theme != i && (this.get_container().removeClass("jstree-" + this.data.themes.theme), this.data.themes.theme = i),
                    this.get_container().addClass("jstree-" + i),
                    this.data.themes.dots ? this.show_dots() : this.hide_dots(),
                    this.data.themes.icons ? this.show_icons() : this.hide_icons(),
                    this.__callback()
                },
                get_theme: function() {
                    return this.data.themes.theme
                },
                show_dots: function() {
                    this.data.themes.dots = !0,
                    this.get_container().children("ul").removeClass("jstree-no-dots")
                },
                hide_dots: function() {
                    this.data.themes.dots = !1,
                    this.get_container().children("ul").addClass("jstree-no-dots")
                },
                toggle_dots: function() {
                    this.data.themes.dots ? this.hide_dots() : this.show_dots()
                },
                show_icons: function() {
                    this.data.themes.icons = !0,
                    this.get_container().children("ul").removeClass("jstree-no-icons")
                },
                hide_icons: function() {
                    this.data.themes.icons = !1,
                    this.get_container().children("ul").addClass("jstree-no-icons")
                },
                toggle_icons: function() {
                    this.data.themes.icons ? this.hide_icons() : this.show_icons()
                }
            }
        }),
        n(function() {
            n.jstree._themes === !1 && n("script").each(function() {
                if (this.src.toString().match(/jquery\.jstree[^\/]*?\.js(\?.*)?$/)) return n.jstree._themes = this.src.toString().replace(/jquery\.jstree[^\/]*?\.js(\?.*)?$/, "") + "themes/",
                !1
            }),
            n.jstree._themes === !1 && (n.jstree._themes = "themes/")
        }),
        n.jstree.defaults.plugins.push("themes")
    })(jQuery),
    (function(n) {
        function i(t, i) {
            var r = n.jstree._focused(),
            u;
            if (r && r.data && r.data.hotkeys && r.data.hotkeys.enabled) {
                u = r._get_settings().hotkeys[t];
                if (u) return u.call(r, i)
            }
        }
        var t = [];
        n.jstree.plugin("hotkeys", {
            __init: function() {
                if (typeof n.hotkeys == "undefined") throw "jsTree hotkeys: jQuery hotkeys plugin not included.";
                if (!this.data.ui) throw "jsTree hotkeys: jsTree UI plugin not included.";
                n.each(this._get_settings().hotkeys,
                function(r, u) {
                    u !== !1 && n.inArray(r, t) == -1 && (n(document).bind("keydown", r,
                    function(n) {
                        return i(r, n)
                    }), t.push(r))
                }),
                this.get_container().bind("lock.jstree", n.proxy(function() {
                    this.data.hotkeys.enabled && (this.data.hotkeys.enabled = !1, this.data.hotkeys.revert = !0)
                },
                this)).bind("unlock.jstree", n.proxy(function() {
                    this.data.hotkeys.revert && (this.data.hotkeys.enabled = !0)
                },
                this)),
                this.enable_hotkeys()
            },
            defaults: {
                up: function() {
                    var n = this.data.ui.hovered || this.data.ui.last_selected || -1;
                    return this.hover_node(this._get_prev(n)),
                    !1
                },
                "ctrl+up": function() {
                    var n = this.data.ui.hovered || this.data.ui.last_selected || -1;
                    return this.hover_node(this._get_prev(n)),
                    !1
                },
                "shift+up": function() {
                    var n = this.data.ui.hovered || this.data.ui.last_selected || -1;
                    return this.hover_node(this._get_prev(n)),
                    !1
                },
                down: function() {
                    var n = this.data.ui.hovered || this.data.ui.last_selected || -1;
                    return this.hover_node(this._get_next(n)),
                    !1
                },
                "ctrl+down": function() {
                    var n = this.data.ui.hovered || this.data.ui.last_selected || -1;
                    return this.hover_node(this._get_next(n)),
                    !1
                },
                "shift+down": function() {
                    var n = this.data.ui.hovered || this.data.ui.last_selected || -1;
                    return this.hover_node(this._get_next(n)),
                    !1
                },
                left: function() {
                    var n = this.data.ui.hovered || this.data.ui.last_selected;
                    return n && (n.hasClass("jstree-open") ? this.close_node(n) : this.hover_node(this._get_prev(n))),
                    !1
                },
                "ctrl+left": function() {
                    var n = this.data.ui.hovered || this.data.ui.last_selected;
                    return n && (n.hasClass("jstree-open") ? this.close_node(n) : this.hover_node(this._get_prev(n))),
                    !1
                },
                "shift+left": function() {
                    var n = this.data.ui.hovered || this.data.ui.last_selected;
                    return n && (n.hasClass("jstree-open") ? this.close_node(n) : this.hover_node(this._get_prev(n))),
                    !1
                },
                right: function() {
                    var n = this.data.ui.hovered || this.data.ui.last_selected;
                    return n && n.length && (n.hasClass("jstree-closed") ? this.open_node(n) : this.hover_node(this._get_next(n))),
                    !1
                },
                "ctrl+right": function() {
                    var n = this.data.ui.hovered || this.data.ui.last_selected;
                    return n && n.length && (n.hasClass("jstree-closed") ? this.open_node(n) : this.hover_node(this._get_next(n))),
                    !1
                },
                "shift+right": function() {
                    var n = this.data.ui.hovered || this.data.ui.last_selected;
                    return n && n.length && (n.hasClass("jstree-closed") ? this.open_node(n) : this.hover_node(this._get_next(n))),
                    !1
                },
                space: function() {
                    return this.data.ui.hovered && this.data.ui.hovered.children("a:eq(0)").click(),
                    !1
                },
                "ctrl+space": function(n) {
                    return n.type = "click",
                    this.data.ui.hovered && this.data.ui.hovered.children("a:eq(0)").trigger(n),
                    !1
                },
                "shift+space": function(n) {
                    return n.type = "click",
                    this.data.ui.hovered && this.data.ui.hovered.children("a:eq(0)").trigger(n),
                    !1
                },
                f2: function() {
                    this.rename(this.data.ui.hovered || this.data.ui.last_selected)
                },
                del: function() {
                    this.remove(this.data.ui.hovered || this._get_node(null))
                }
            },
            _fn: {
                enable_hotkeys: function() {
                    this.data.hotkeys.enabled = !0
                },
                disable_hotkeys: function() {
                    this.data.hotkeys.enabled = !1
                }
            }
        })
    })(jQuery),
    (function(n) {
        n.jstree.plugin("json_data", {
            __init: function() {
                var n = this._get_settings().json_data;
                n.progressive_unload && this.get_container().bind("after_close.jstree",
                function(n, t) {
                    t.rslt.obj.children("ul").remove()
                })
            },
            defaults: {
                data: !1,
                ajax: !1,
                correct_state: !0,
                progressive_render: !1,
                progressive_unload: !1
            },
            _fn: {
                load_node: function(n, t, i) {
                    var r = this;
                    this.load_node_json(n,
                    function() {
                        r.__callback({
                            obj: r._get_node(n)
                        }),
                        t.call(this)
                    },
                    i)
                },
                _is_loaded: function(t) {
                    var i = this._get_settings().json_data;
                    return t = this._get_node(t),
                    t == -1 || !t || !i.ajax && !i.progressive_render && !n.isFunction(i.data) || t.is(".jstree-open, .jstree-leaf") || t.children("ul").children("li").length > 0
                },
                refresh: function(t) {
                    t = this._get_node(t);
                    var i = this._get_settings().json_data;
                    return t && t !== -1 && i.progressive_unload && (n.isFunction(i.data) || !!i.ajax) && t.removeData("jstree_children"),
                    this.__call_old()
                },
                load_node_json: function(t, i, r) {
                    var u = this.get_settings().json_data,
                    f,
                    e = function() {},
                    o = function() {};
                    t = this._get_node(t);
                    if (t && t !== -1 && (u.progressive_render || u.progressive_unload) && !t.is(".jstree-open, .jstree-leaf") && t.children("ul").children("li").length === 0 && t.data("jstree_children")) {
                        f = this._parse_json(t.data("jstree_children"), t),
                        f && (t.append(f), u.progressive_unload || t.removeData("jstree_children")),
                        this.clean_node(t),
                        i && i.call(this);
                        return
                    }
                    if (t && t !== -1) {
                        if (t.data("jstree_is_loading")) return;
                        t.data("jstree_is_loading", !0)
                    }
                    switch (!0) {
                    case ! u.data && !u.ajax: throw "Neither data nor ajax settings supplied.";
                    case n.isFunction(u.data):
                        u.data.call(this, t, n.proxy(function(n) {
                            n = this._parse_json(n, t),
                            n ? (t === -1 || !t ? this.get_container().children("ul").empty().append(n.children()) : (t.append(n).children("a.jstree-loading").removeClass("jstree-loading"), t.removeData("jstree_is_loading")), this.clean_node(t), i && i.call(this)) : (t === -1 || !t ? u.correct_state && this.get_container().children("ul").empty() : (t.children("a.jstree-loading").removeClass("jstree-loading"), t.removeData("jstree_is_loading"), u.correct_state && this.correct_state(t)), r && r.call(this))
                        },
                        this));
                        break;
                    case !! u.data && !u.ajax || !!u.data && !!u.ajax && (!t || t === -1) : (!t || t == -1) && (f = this._parse_json(u.data, t), f ? (this.get_container().children("ul").empty().append(f.children()), this.clean_node()) : u.correct_state && this.get_container().children("ul").empty()),
                        i && i.call(this);
                        break;
                    case ! u.data && !!u.ajax || !!u.data && !!u.ajax && t && t !== -1 : e = function(n, i, f) {
                            var e = this.get_settings().json_data.ajax.error;
                            e && e.call(this, n, i, f),
                            t != -1 && t.length ? (t.children("a.jstree-loading").removeClass("jstree-loading"), t.removeData("jstree_is_loading"), i === "success" && u.correct_state && this.correct_state(t)) : i === "success" && u.correct_state && this.get_container().children("ul").empty(),
                            r && r.call(this)
                        },
                        o = function(r, f, o) {
                            var s = this.get_settings().json_data.ajax.success;
                            s && (r = s.call(this, r, f, o) || r);
                            if (r === "" || r && r.toString && r.toString().replace(/^[\s\n]+$/, "") === "" || !n.isArray(r) && !n.isPlainObject(r)) return e.call(this, o, f, "");
                            r = this._parse_json(r, t),
                            r ? (t === -1 || !t ? this.get_container().children("ul").empty().append(r.children()) : (t.append(r).children("a.jstree-loading").removeClass("jstree-loading"), t.removeData("jstree_is_loading")), this.clean_node(t), i && i.call(this)) : t === -1 || !t ? u.correct_state && (this.get_container().children("ul").empty(), i && i.call(this)) : (t.children("a.jstree-loading").removeClass("jstree-loading"), t.removeData("jstree_is_loading"), u.correct_state && (this.correct_state(t), i && i.call(this)))
                        },
                        u.ajax.context = this,
                        u.ajax.error = e,
                        u.ajax.success = o,
                        u.ajax.dataType || (u.ajax.dataType = "json"),
                        n.isFunction(u.ajax.url) && (u.ajax.url = u.ajax.url.call(this, t)),
                        n.isFunction(u.ajax.data) && (u.ajax.data = u.ajax.data.call(this, t)),
                        n.ajax(u.ajax)
                    }
                },
                _parse_json: function(t, i, r) {
                    var u = !1,
                    c = this._get_settings(),
                    h = c.json_data,
                    l = c.core.html_titles,
                    f,
                    e,
                    a,
                    s,
                    o;
                    if (!t) return u;
                    h.progressive_unload && i && i !== -1 && i.data("jstree_children", u);
                    if (n.isArray(t)) {
                        u = n();
                        if (!t.length) return ! 1;
                        for (e = 0, a = t.length; e < a; e++) f = this._parse_json(t[e], i, !0),
                        f.length && (u = u.add(f))
                    } else {
                        typeof t == "string" && (t = {
                            data: t
                        });
                        if (!t.data && t.data !== "") return u;
                        u = n("<li />"),
                        t.attr && u.attr(t.attr),
                        t.metadata && u.data(t.metadata),
                        t.state && u.addClass("jstree-" + t.state),
                        n.isArray(t.data) || (f = t.data, t.data = [], t.data.push(f)),
                        n.each(t.data,
                        function(i, r) {
                            f = n("<a />"),
                            n.isFunction(r) && (r = r.call(this, t)),
                            typeof r == "string" ? f.attr("href", "#")[l ? "html": "text"](r) : (r.attr || (r.attr = {}), r.attr.href || (r.attr.href = "#"), f.attr(r.attr)[l ? "html": "text"](r.title), r.language && f.addClass(r.language)),
                            f.prepend("<ins class='jstree-icon'>&#160;</ins>"),
                            !r.icon && t.icon && (r.icon = t.icon),
                            r.icon && (r.icon.indexOf("/") === -1 ? f.children("ins").addClass(r.icon) : f.children("ins").css("background", "url('" + r.icon + "') center center no-repeat")),
                            u.append(f)
                        }),
                        u.prepend("<ins class='jstree-icon'>&#160;</ins>"),
                        t.children && (h.progressive_render && t.state !== "open" ? u.addClass("jstree-closed").data("jstree_children", t.children) : (h.progressive_unload && u.data("jstree_children", t.children), n.isArray(t.children) && t.children.length && (f = this._parse_json(t.children, i, !0), f.length && (o = n("<ul />"), o.append(f), u.append(o)))))
                    }
                    return r || (s = n("<ul />"), s.append(u), u = s),
                    u
                },
                get_json: function(t, i, r, u) {
                    var v = [],
                    h = this._get_settings(),
                    a = this,
                    s,
                    e,
                    o,
                    l,
                    f,
                    c;
                    return t = this._get_node(t),
                    (!t || t === -1) && (t = this.get_container().find("> ul > li")),
                    i = n.isArray(i) ? i: ["id", "class"],
                    !u && this.data.types && i.push(h.types.type_attr),
                    r = n.isArray(r) ? r: [],
                    t.each(function() {
                        o = n(this),
                        s = {
                            data: []
                        },
                        i.length && (s.attr = {}),
                        n.each(i,
                        function(n, t) {
                            e = o.attr(t),
                            e && e.length && e.replace(/jstree[^ ]*/ig, "").length && (s.attr[t] = (" " + e).replace(/ jstree[^ ]*/ig, "").replace(/\s+$/ig, " ").replace(/^ /, "").replace(/ $/, ""))
                        }),
                        o.hasClass("jstree-open") && (s.state = "open"),
                        o.hasClass("jstree-closed") && (s.state = "closed"),
                        o.data() && (s.metadata = o.data()),
                        l = o.children("a"),
                        l.each(function() {
                            f = n(this),
                            r.length || n.inArray("languages", h.plugins) !== -1 || f.children("ins").get(0).style.backgroundImage.length || f.children("ins").get(0).className && f.children("ins").get(0).className.replace(/jstree[^ ]*|$/ig, "").length ? (c = !1, n.inArray("languages", h.plugins) !== -1 && n.isArray(h.languages) && h.languages.length && n.each(h.languages,
                            function(n, t) {
                                if (f.hasClass(t)) return c = t,
                                !1
                            }), e = {
                                attr: {},
                                title: a.get_text(f, c)
                            },
                            n.each(r,
                            function(n, t) {
                                e.attr[t] = (" " + (f.attr(t) || "")).replace(/ jstree[^ ]*/ig, "").replace(/\s+$/ig, " ").replace(/^ /, "").replace(/ $/, "")
                            }), n.inArray("languages", h.plugins) !== -1 && n.isArray(h.languages) && h.languages.length && n.each(h.languages,
                            function(n, t) {
                                if (f.hasClass(t)) return e.language = t,
                                !0
                            }), f.children("ins").get(0).className.replace(/jstree[^ ]*|$/ig, "").replace(/^\s+$/ig, "").length && (e.icon = f.children("ins").get(0).className.replace(/jstree[^ ]*|$/ig, "").replace(/\s+$/ig, " ").replace(/^ /, "").replace(/ $/, "")), f.children("ins").get(0).style.backgroundImage.length && (e.icon = f.children("ins").get(0).style.backgroundImage.replace("url(", "").replace(")", ""))) : e = a.get_text(f),
                            l.length > 1 ? s.data.push(e) : s.data = e
                        }),
                        o = o.find("> ul > li"),
                        o.length && (s.children = a.get_json(o, i, r, !0)),
                        v.push(s)
                    }),
                    v
                }
            }
        })
    })(jQuery),
    (function(n) {
        n.jstree.plugin("languages", {
            __init: function() {
                this._load_css()
            },
            defaults: [],
            _fn: {
                set_lang: function(t) {
                    var r = this._get_settings().languages,
                    i = !1,
                    u = ".jstree-" + this.get_index() + " a";
                    if (!n.isArray(r) || r.length === 0) return ! 1;
                    if (n.inArray(t, r) == -1) {
                        if (!r[t]) return ! 1;
                        t = r[t]
                    }
                    return t == this.data.languages.current_language ? !0 : (i = n.vakata.css.get_css(u + "." + this.data.languages.current_language, !1, this.data.languages.language_css), i !== !1 && (i.style.display = "none"), i = n.vakata.css.get_css(u + "." + t, !1, this.data.languages.language_css), i !== !1 && (i.style.display = ""), this.data.languages.current_language = t, this.__callback(t), !0)
                },
                get_lang: function() {
                    return this.data.languages.current_language
                },
                _get_string: function(t, i) {
                    var u = this._get_settings().languages,
                    r = this._get_settings().core.strings;
                    return n.isArray(u) && u.length && (i = i && n.inArray(i, u) != -1 ? i: this.data.languages.current_language),
                    r[i] && r[i][t] ? r[i][t] : r[t] ? r[t] : t
                },
                get_text: function(t, i) {
                    t = this._get_node(t) || this.data.ui.last_selected;
                    if (!t.size()) return ! 1;
                    var r = this._get_settings().languages,
                    u = this._get_settings().core.html_titles;
                    return n.isArray(r) && r.length ? (i = i && n.inArray(i, r) != -1 ? i: this.data.languages.current_language, t = t.children("a." + i)) : t = t.children("a:eq(0)"),
                    u ? (t = t.clone(), t.children("INS").remove(), t.html()) : (t = t.contents().filter(function() {
                        return this.nodeType == 3
                    })[0], t.nodeValue)
                },
                set_text: function(t, i, r) {
                    t = this._get_node(t) || this.data.ui.last_selected;
                    if (!t.size()) return ! 1;
                    var u = this._get_settings().languages,
                    e = this._get_settings().core.html_titles,
                    f;
                    return n.isArray(u) && u.length ? (r = r && n.inArray(r, u) != -1 ? r: this.data.languages.current_language, t = t.children("a." + r)) : t = t.children("a:eq(0)"),
                    e ? (f = t.children("INS").clone(), t.html(i).prepend(f), this.__callback({
                        obj: t,
                        name: i,
                        lang: r
                    }), !0) : (t = t.contents().filter(function() {
                        return this.nodeType == 3
                    })[0], this.__callback({
                        obj: t,
                        name: i,
                        lang: r
                    }), t.nodeValue = i)
                },
                _load_css: function() {
                    var t = this._get_settings().languages,
                    r = "/* languages css */",
                    u = ".jstree-" + this.get_index() + " a",
                    i;
                    if (n.isArray(t) && t.length) {
                        for (this.data.languages.current_language = t[0], i = 0; i < t.length; i++) r += u + "." + t[i] + " {",
                        t[i] != this.data.languages.current_language && (r += " display:none; "),
                        r += " } ";
                        this.data.languages.language_css = n.vakata.css.add_sheet({
                            str: r,
                            title: "jstree-languages"
                        })
                    }
                },
                create_node: function(t, i, r, u) {
                    return this.__call_old(!0, t, i, r,
                    function(t) {
                        var i = this._get_settings().languages,
                        f = t.children("a"),
                        r;
                        if (n.isArray(i) && i.length) {
                            for (r = 0; r < i.length; r++) f.is("." + i[r]) || t.append(f.eq(0).clone().removeClass(i.join(" ")).addClass(i[r]));
                            f.not("." + i.join(", .")).remove()
                        }
                        u && u.call(this, t)
                    })
                }
            }
        })
    })(jQuery),
    (function(n) {
        n.jstree.plugin("cookies", {
            __init: function() {
                if (typeof n.cookie == "undefined") throw "jsTree cookie: jQuery cookie plugin not included.";
                var i = this._get_settings().cookies,
                t; ! i.save_loaded || (t = n.cookie(i.save_loaded), t && t.length && (this.data.core.to_load = t.split(","))),
                !i.save_opened || (t = n.cookie(i.save_opened), t && t.length && (this.data.core.to_open = t.split(","))),
                !i.save_selected || (t = n.cookie(i.save_selected), t && t.length && this.data.ui && (this.data.ui.to_select = t.split(",")));
                this.get_container().one((this.data.ui ? "reselect": "reopen") + ".jstree", n.proxy(function() {
                    this.get_container().bind("open_node.jstree close_node.jstree select_node.jstree deselect_node.jstree", n.proxy(function(n) {
                        this._get_settings().cookies.auto_save && this.save_cookie((n.handleObj.namespace + n.handleObj.type).replace("jstree", ""))
                    },
                    this))
                },
                this))
            },
            defaults: {
                save_loaded: "jstree_load",
                save_opened: "jstree_open",
                save_selected: "jstree_select",
                auto_save: !0,
                cookie_options: {}
            },
            _fn: {
                save_cookie: function(t) {
                    if (this.data.core.refreshing) return;
                    var i = this._get_settings().cookies;
                    if (!t) {
                        i.save_loaded && (this.save_loaded(), n.cookie(i.save_loaded, this.data.core.to_load.join(","), i.cookie_options)),
                        i.save_opened && (this.save_opened(), n.cookie(i.save_opened, this.data.core.to_open.join(","), i.cookie_options)),
                        i.save_selected && this.data.ui && (this.save_selected(), n.cookie(i.save_selected, this.data.ui.to_select.join(","), i.cookie_options));
                        return
                    }
                    switch (t) {
                    case "open_node":
                    case "close_node":
                        !i.save_opened || (this.save_opened(), n.cookie(i.save_opened, this.data.core.to_open.join(","), i.cookie_options)),
                        !i.save_loaded || (this.save_loaded(), n.cookie(i.save_loaded, this.data.core.to_load.join(","), i.cookie_options));
                        break;
                    case "select_node":
                    case "deselect_node":
                        !!i.save_selected && this.data.ui && (this.save_selected(), n.cookie(i.save_selected, this.data.ui.to_select.join(","), i.cookie_options))
                    }
                }
            }
        })
    })(jQuery),
    (function(n) {
        n.jstree.plugin("sort", {
            __init: function() {
                this.get_container().bind("load_node.jstree", n.proxy(function(n, t) {
                    var i = this._get_node(t.rslt.obj);
                    i = i === -1 ? this.get_container().children("ul") : i.children("ul"),
                    this.sort(i)
                },
                this)).bind("rename_node.jstree create_node.jstree create.jstree", n.proxy(function(n, t) {
                    this.sort(t.rslt.obj.parent())
                },
                this)).bind("move_node.jstree", n.proxy(function(n, t) {
                    var i = t.rslt.np == -1 ? this.get_container() : t.rslt.np;
                    this.sort(i.children("ul"))
                },
                this))
            },
            defaults: function(n, t) {
                return this.get_text(n) > this.get_text(t) ? 1 : -1
            },
            _fn: {
                sort: function(t) {
                    var r = this._get_settings().sort,
                    i = this;
                    t.append(n.makeArray(t.children("li")).sort(n.proxy(r, i))),
                    t.find("> li > ul").each(function() {
                        i.sort(n(this))
                    }),
                    this.clean_node(t)
                }
            }
        })
    })(jQuery),
    (function(n) {
        var u = !1,
        t = !1,
        r = !1,
        i = !1,
        e = !1,
        f = !1,
        s = !1,
        o = !1,
        h = !1;
        n.vakata.dnd = {
            is_down: !1,
            is_drag: !1,
            helper: !1,
            scroll_spd: 10,
            init_x: 0,
            init_y: 0,
            threshold: 5,
            helper_left: 5,
            helper_top: 10,
            user_data: {},
            drag_start: function(t, i, r) {
                n.vakata.dnd.is_drag && n.vakata.drag_stop({});
                try {
                    t.currentTarget.unselectable = "on",
                    t.currentTarget.onselectstart = function() {
                        return ! 1
                    },
                    t.currentTarget.style && (t.currentTarget.style.MozUserSelect = "none")
                } catch(u) {}
                return n.vakata.dnd.init_x = t.pageX,
                n.vakata.dnd.init_y = t.pageY,
                n.vakata.dnd.user_data = i,
                n.vakata.dnd.is_down = !0,
                n.vakata.dnd.helper = n("<div id='vakata-dragged' />").html(r),
                n(document).bind("mousemove", n.vakata.dnd.drag),
                n(document).bind("mouseup", n.vakata.dnd.drag_stop),
                !1
            },
            drag: function(t) {
                if (!n.vakata.dnd.is_down) return;
                if (!n.vakata.dnd.is_drag) if (Math.abs(t.pageX - n.vakata.dnd.init_x) > 5 || Math.abs(t.pageY - n.vakata.dnd.init_y) > 5) n.vakata.dnd.helper.appendTo("body"),
                n.vakata.dnd.is_drag = !0,
                n(document).triggerHandler("drag_start.vakata", {
                    event: t,
                    data: n.vakata.dnd.user_data
                });
                else return;
                if (t.type === "mousemove") {
                    var u = n(document),
                    r = u.scrollTop(),
                    i = u.scrollLeft();
                    t.pageY - r < 20 ? (f && s === "down" && (clearInterval(f), f = !1), f || (s = "up", f = setInterval(function() {
                        n(document).scrollTop(n(document).scrollTop() - n.vakata.dnd.scroll_spd)
                    },
                    150))) : f && s === "up" && (clearInterval(f), f = !1),
                    n(window).height() - (t.pageY - r) < 20 ? (f && s === "up" && (clearInterval(f), f = !1), f || (s = "down", f = setInterval(function() {
                        n(document).scrollTop(n(document).scrollTop() + n.vakata.dnd.scroll_spd)
                    },
                    150))) : f && s === "down" && (clearInterval(f), f = !1),
                    t.pageX - i < 20 ? (e && o === "right" && (clearInterval(e), e = !1), e || (o = "left", e = setInterval(function() {
                        n(document).scrollLeft(n(document).scrollLeft() - n.vakata.dnd.scroll_spd)
                    },
                    150))) : e && o === "left" && (clearInterval(e), e = !1),
                    n(window).width() - (t.pageX - i) < 20 ? (e && o === "left" && (clearInterval(e), e = !1), e || (o = "right", e = setInterval(function() {
                        n(document).scrollLeft(n(document).scrollLeft() + n.vakata.dnd.scroll_spd)
                    },
                    150))) : e && o === "right" && (clearInterval(e), e = !1)
                }
                n.vakata.dnd.helper.css({
                    left: t.pageX + n.vakata.dnd.helper_left + "px",
                    top: t.pageY + n.vakata.dnd.helper_top + "px"
                }),
                n(document).triggerHandler("drag.vakata", {
                    event: t,
                    data: n.vakata.dnd.user_data
                })
            },
            drag_stop: function(t) {
                e && clearInterval(e),
                f && clearInterval(f),
                n(document).unbind("mousemove", n.vakata.dnd.drag),
                n(document).unbind("mouseup", n.vakata.dnd.drag_stop),
                n(document).triggerHandler("drag_stop.vakata", {
                    event: t,
                    data: n.vakata.dnd.user_data
                }),
                n.vakata.dnd.helper.remove(),
                n.vakata.dnd.init_x = 0,
                n.vakata.dnd.init_y = 0,
                n.vakata.dnd.user_data = {},
                n.vakata.dnd.is_down = !1,
                n.vakata.dnd.is_drag = !1
            }
        },
        n(function() {
            var t = "#vakata-dragged { display:block; margin:0 0 0 0; padding:4px 4px 4px 24px; position:absolute; top:-2000px; line-height:16px; z-index:10000; } ";
            n.vakata.css.add_sheet({
                str: t,
                title: "vakata"
            })
        }),
        n.jstree.plugin("dnd", {
            __init: function() {
                this.data.dnd = {
                    active: !1,
                    after: !1,
                    inside: !1,
                    before: !1,
                    off: !1,
                    prepared: !1,
                    w: 0,
                    to1: !1,
                    to2: !1,
                    cof: !1,
                    cw: !1,
                    ch: !1,
                    i1: !1,
                    i2: !1,
                    mto: !1
                },
                this.get_container().bind("mouseenter.jstree", n.proxy(function(t) {
                    if (n.vakata.dnd.is_drag && n.vakata.dnd.user_data.jstree) {
                        this.data.themes && (r.attr("class", "jstree-" + this.data.themes.theme), i && i.attr("class", "jstree-" + this.data.themes.theme), n.vakata.dnd.helper.attr("class", "jstree-dnd-helper jstree-" + this.data.themes.theme));
                        if (t.currentTarget === t.target && n.vakata.dnd.user_data.obj && n(n.vakata.dnd.user_data.obj).length && n(n.vakata.dnd.user_data.obj).parents(".jstree:eq(0)")[0] !== t.target) {
                            var f = n.jstree._reference(t.target),
                            e;
                            f.data.dnd.foreign ? (e = f._get_settings().dnd.drag_check.call(this, {
                                o: u,
                                r: f.get_container(),
                                is_root: !0
                            }), (e === !0 || e.inside === !0 || e.before === !0 || e.after === !0) && n.vakata.dnd.helper.children("ins").attr("class", "jstree-ok")) : (f.prepare_move(u, f.get_container(), "last"), f.check_move() && n.vakata.dnd.helper.children("ins").attr("class", "jstree-ok"))
                        }
                    }
                },
                this)).bind("mouseup.jstree", n.proxy(function(t) {
                    if (n.vakata.dnd.is_drag && n.vakata.dnd.user_data.jstree && t.currentTarget === t.target && n.vakata.dnd.user_data.obj && n(n.vakata.dnd.user_data.obj).length && n(n.vakata.dnd.user_data.obj).parents(".jstree:eq(0)")[0] !== t.target) {
                        var i = n.jstree._reference(t.currentTarget),
                        r;
                        i.data.dnd.foreign ? (r = i._get_settings().dnd.drag_check.call(this, {
                            o: u,
                            r: i.get_container(),
                            is_root: !0
                        }), (r === !0 || r.inside === !0 || r.before === !0 || r.after === !0) && i._get_settings().dnd.drag_finish.call(this, {
                            o: u,
                            r: i.get_container(),
                            is_root: !0
                        })) : i.move_node(u, i.get_container(), "last", t[i._get_settings().dnd.copy_modifier + "Key"])
                    }
                },
                this)).bind("mouseleave.jstree", n.proxy(function(t) {
                    if (t.relatedTarget && t.relatedTarget.id && t.relatedTarget.id === "jstree-marker-line") return ! 1;
                    n.vakata.dnd.is_drag && n.vakata.dnd.user_data.jstree && (this.data.dnd.i1 && clearInterval(this.data.dnd.i1), this.data.dnd.i2 && clearInterval(this.data.dnd.i2), this.data.dnd.to1 && clearTimeout(this.data.dnd.to1), this.data.dnd.to2 && clearTimeout(this.data.dnd.to2), n.vakata.dnd.helper.children("ins").hasClass("jstree-ok") && n.vakata.dnd.helper.children("ins").attr("class", "jstree-invalid"))
                },
                this)).bind("mousemove.jstree", n.proxy(function(t) {
                    if (n.vakata.dnd.is_drag && n.vakata.dnd.user_data.jstree) {
                        var i = this.get_container()[0];
                        t.pageX + 24 > this.data.dnd.cof.left + this.data.dnd.cw ? (this.data.dnd.i1 && clearInterval(this.data.dnd.i1), this.data.dnd.i1 = setInterval(n.proxy(function() {
                            this.scrollLeft += n.vakata.dnd.scroll_spd
                        },
                        i), 100)) : t.pageX - 24 < this.data.dnd.cof.left ? (this.data.dnd.i1 && clearInterval(this.data.dnd.i1), this.data.dnd.i1 = setInterval(n.proxy(function() {
                            this.scrollLeft -= n.vakata.dnd.scroll_spd
                        },
                        i), 100)) : this.data.dnd.i1 && clearInterval(this.data.dnd.i1),
                        t.pageY + 24 > this.data.dnd.cof.top + this.data.dnd.ch ? (this.data.dnd.i2 && clearInterval(this.data.dnd.i2), this.data.dnd.i2 = setInterval(n.proxy(function() {
                            this.scrollTop += n.vakata.dnd.scroll_spd
                        },
                        i), 100)) : t.pageY - 24 < this.data.dnd.cof.top ? (this.data.dnd.i2 && clearInterval(this.data.dnd.i2), this.data.dnd.i2 = setInterval(n.proxy(function() {
                            this.scrollTop -= n.vakata.dnd.scroll_spd
                        },
                        i), 100)) : this.data.dnd.i2 && clearInterval(this.data.dnd.i2)
                    }
                },
                this)).bind("scroll.jstree", n.proxy(function() {
                    n.vakata.dnd.is_drag && n.vakata.dnd.user_data.jstree && r && i && (r.hide(), i.hide())
                },
                this)).delegate("a", "mousedown.jstree", n.proxy(function(n) {
                    if (n.which === 1) return this.start_drag(n.currentTarget, n),
                    !1
                },
                this)).delegate("a", "mouseenter.jstree", n.proxy(function(t) {
                    n.vakata.dnd.is_drag && n.vakata.dnd.user_data.jstree && this.dnd_enter(t.currentTarget)
                },
                this)).delegate("a", "mousemove.jstree", n.proxy(function(i) {
                    n.vakata.dnd.is_drag && n.vakata.dnd.user_data.jstree && ((!t || !t.length || t.children("a")[0] !== i.currentTarget) && this.dnd_enter(i.currentTarget), typeof this.data.dnd.off.top == "undefined" && (this.data.dnd.off = n(i.target).offset()), this.data.dnd.w = (i.pageY - (this.data.dnd.off.top || 0)) % this.data.core.li_height, this.data.dnd.w < 0 && (this.data.dnd.w += this.data.core.li_height), this.dnd_show())
                },
                this)).delegate("a", "mouseleave.jstree", n.proxy(function(t) {
                    if (n.vakata.dnd.is_drag && n.vakata.dnd.user_data.jstree) {
                        if (t.relatedTarget && t.relatedTarget.id && t.relatedTarget.id === "jstree-marker-line") return ! 1;
                        r && r.hide(),
                        i && i.hide(),
                        this.data.dnd.mto = setTimeout(function(n) {
                            return function() {
                                n.dnd_leave(t)
                            }
                        } (this), 0)
                    }
                },
                this)).delegate("a", "mouseup.jstree", n.proxy(function(t) {
                    n.vakata.dnd.is_drag && n.vakata.dnd.user_data.jstree && this.dnd_finish(t)
                },
                this)),
                n(document).bind("drag_stop.vakata", n.proxy(function() {
                    this.data.dnd.to1 && clearTimeout(this.data.dnd.to1),
                    this.data.dnd.to2 && clearTimeout(this.data.dnd.to2),
                    this.data.dnd.i1 && clearInterval(this.data.dnd.i1),
                    this.data.dnd.i2 && clearInterval(this.data.dnd.i2),
                    this.data.dnd.after = !1,
                    this.data.dnd.before = !1,
                    this.data.dnd.inside = !1,
                    this.data.dnd.off = !1,
                    this.data.dnd.prepared = !1,
                    this.data.dnd.w = !1,
                    this.data.dnd.to1 = !1,
                    this.data.dnd.to2 = !1,
                    this.data.dnd.i1 = !1,
                    this.data.dnd.i2 = !1,
                    this.data.dnd.active = !1,
                    this.data.dnd.foreign = !1,
                    r && r.css({
                        top: "-2000px"
                    }),
                    i && i.css({
                        top: "-2000px"
                    })
                },
                this)).bind("drag_start.vakata", n.proxy(function(t, i) {
                    if (i.data.jstree) {
                        var r = n(i.event.target);
                        r.closest(".jstree").hasClass("jstree-" + this.get_index()) && this.dnd_enter(r)
                    }
                },
                this));
                var f = this._get_settings().dnd;
                f.drag_target && n(document).delegate(f.drag_target, "mousedown.jstree-" + this.get_index(), n.proxy(function(t) {
                    u = t.target,
                    n.vakata.dnd.drag_start(t, {
                        jstree: !0,
                        obj: t.target
                    },
                    "<ins class='jstree-icon'></ins>" + n(t.target).text()),
                    this.data.themes && (r && r.attr("class", "jstree-" + this.data.themes.theme), i && i.attr("class", "jstree-" + this.data.themes.theme), n.vakata.dnd.helper.attr("class", "jstree-dnd-helper jstree-" + this.data.themes.theme)),
                    n.vakata.dnd.helper.children("ins").attr("class", "jstree-invalid");
                    var f = this.get_container();
                    this.data.dnd.cof = f.offset(),
                    this.data.dnd.cw = parseInt(f.width(), 10),
                    this.data.dnd.ch = parseInt(f.height(), 10),
                    this.data.dnd.foreign = !0,
                    t.preventDefault()
                },
                this)),
                f.drop_target && n(document).delegate(f.drop_target, "mouseenter.jstree-" + this.get_index(), n.proxy(function(t) {
                    this.data.dnd.active && this._get_settings().dnd.drop_check.call(this, {
                        o: u,
                        r: n(t.target),
                        e: t
                    }) && n.vakata.dnd.helper.children("ins").attr("class", "jstree-ok")
                },
                this)).delegate(f.drop_target, "mouseleave.jstree-" + this.get_index(), n.proxy(function() {
                    this.data.dnd.active && n.vakata.dnd.helper.children("ins").attr("class", "jstree-invalid")
                },
                this)).delegate(f.drop_target, "mouseup.jstree-" + this.get_index(), n.proxy(function(t) {
                    this.data.dnd.active && n.vakata.dnd.helper.children("ins").hasClass("jstree-ok") && this._get_settings().dnd.drop_finish.call(this, {
                        o: u,
                        r: n(t.target),
                        e: t
                    })
                },
                this))
            },
            defaults: {
                copy_modifier: "ctrl",
                check_timeout: 100,
                open_timeout: 500,
                drop_target: ".jstree-drop",
                drop_check: function() {
                    return ! 0
                },
                drop_finish: n.noop,
                drag_target: ".jstree-draggable",
                drag_finish: n.noop,
                drag_check: function() {
                    return {
                        after: !1,
                        before: !1,
                        inside: !0
                    }
                }
            },
            _fn: {
                dnd_prepare: function() {
                    if (!t || !t.length) return;
                    this.data.dnd.off = t.offset(),
                    this._get_settings().core.rtl && (this.data.dnd.off.right = this.data.dnd.off.left + t.width());
                    if (this.data.dnd.foreign) {
                        var n = this._get_settings().dnd.drag_check.call(this, {
                            o: u,
                            r: t
                        });
                        return this.data.dnd.after = n.after,
                        this.data.dnd.before = n.before,
                        this.data.dnd.inside = n.inside,
                        this.data.dnd.prepared = !0,
                        this.dnd_show()
                    }
                    return this._is_loaded(t) ? (this.prepare_move(u, t, "inside"), this.data.dnd.inside = this.check_move()) : this.data.dnd.inside = !1,
                    this.data.dnd.prepared = !0,
                    this.dnd_show()
                },
                dnd_show: function() {
                    if (!this.data.dnd.prepared) return;
                    var u = ["inside"],
                    t = !1,
                    e = this._get_settings().core.rtl,
                    f;
                    u = this.data.dnd.w < this.data.core.li_height / 3 ? ["before", "inside", "after"] : this.data.dnd.w <= this.data.core.li_height * 2 / 3 ? this.data.dnd.w < this.data.core.li_height / 2 ? ["inside", "before", "after"] : ["inside", "after", "before"] : ["after", "inside", "before"],
                    n.each(u, n.proxy(function(i, r) {
                        if (this.data.dnd[r]) return n.vakata.dnd.helper.children("ins").attr("class", "jstree-ok"),
                        t = r,
                        !1
                    },
                    this)),
                    t === !1 && n.vakata.dnd.helper.children("ins").attr("class", "jstree-invalid"),
                    f = e ? this.data.dnd.off.right - 18 : this.data.dnd.off.left + 10;
                    switch (t) {
                    case "inside":
                        r.css({
                            left:
                            f + (e ? -4 : 4) + "px",
                            top: this.data.dnd.off.top + this.data.core.li_height / 2 - 5 + "px"
                        }).show(),
                        i && i.hide();
                        break;
                    default:
                        r.hide(),
                        i && i.hide()
                    }
                    return h = t,
                    t
                },
                dnd_open: function() {
                    this.data.dnd.to2 = !1,
                    this.open_node(t, n.proxy(this.dnd_prepare, this), !0)
                },
                dnd_finish: function(n) {
                    this.data.dnd.foreign ? (this.data.dnd.after || this.data.dnd.before || this.data.dnd.inside) && this._get_settings().dnd.drag_finish.call(this, {
                        o: u,
                        r: t,
                        p: h
                    }) : (this.dnd_prepare(), this.move_node(u, t, h, n[this._get_settings().dnd.copy_modifier + "Key"])),
                    u = !1,
                    t = !1,
                    r.hide(),
                    i && i.hide()
                },
                dnd_enter: function(i) {
                    this.data.dnd.mto && (clearTimeout(this.data.dnd.mto), this.data.dnd.mto = !1);
                    var r = this._get_settings().dnd;
                    this.data.dnd.prepared = !1,
                    t = this._get_node(i),
                    r.check_timeout ? (this.data.dnd.to1 && clearTimeout(this.data.dnd.to1), this.data.dnd.to1 = setTimeout(n.proxy(this.dnd_prepare, this), r.check_timeout)) : this.dnd_prepare(),
                    r.open_timeout ? (this.data.dnd.to2 && clearTimeout(this.data.dnd.to2), t && t.length && t.hasClass("jstree-closed") && (this.data.dnd.to2 = setTimeout(n.proxy(this.dnd_open, this), r.open_timeout))) : t && t.length && t.hasClass("jstree-closed") && this.dnd_open()
                },
                dnd_leave: function(u) {
                    this.data.dnd.after = !1,
                    this.data.dnd.before = !1,
                    this.data.dnd.inside = !1,
                    n.vakata.dnd.helper.children("ins").attr("class", "jstree-invalid"),
                    r.hide(),
                    i && i.hide(),
                    t && t[0] === u.target.parentNode && (this.data.dnd.to1 && (clearTimeout(this.data.dnd.to1), this.data.dnd.to1 = !1), this.data.dnd.to2 && (clearTimeout(this.data.dnd.to2), this.data.dnd.to2 = !1))
                },
                start_drag: function(t, f) {
                    u = this._get_node(t),
                    this.data.ui && this.is_selected(u) && (u = this._get_node(null, !0));
                    var o = u.length > 1 ? this._get_string("multiple_selection") : this.get_text(u),
                    e = this.get_container();
                    this._get_settings().core.html_titles || (o = o.replace(/</ig, "&lt;").replace(/>/ig, "&gt;")),
                    n.vakata.dnd.drag_start(f, {
                        jstree: !0,
                        obj: u
                    },
                    "<ins class='jstree-icon'></ins>" + o),
                    this.data.themes && (r && r.attr("class", "jstree-" + this.data.themes.theme), i && i.attr("class", "jstree-" + this.data.themes.theme), n.vakata.dnd.helper.attr("class", "jstree-dnd-helper jstree-" + this.data.themes.theme)),
                    this.data.dnd.cof = e.offset(),
                    this.data.dnd.cw = parseInt(e.width(), 10),
                    this.data.dnd.ch = parseInt(e.height(), 10),
                    this.data.dnd.active = !0
                }
            }
        }),
        n(function() {
            var u = "#vakata-dragged ins { display:block; text-decoration:none; width:16px; height:16px; margin:0 0 0 0; padding:0; position:absolute; top:4px; left:4px;  -moz-border-radius:4px; border-radius:4px; -webkit-border-radius:4px; } #vakata-dragged .jstree-ok { background:green; } #vakata-dragged .jstree-invalid { background:red; } #jstree-marker { padding:0; margin:0; font-size:12px; overflow:hidden; height:12px; width:8px; position:absolute; top:-30px; z-index:10001; background-repeat:no-repeat; display:none; background-color:transparent; text-shadow:1px 1px 1px white; color:black; line-height:10px; } #jstree-marker-line { padding:0; margin:0; line-height:0%; font-size:1px; overflow:hidden; height:1px; width:100px; position:absolute; top:-30px; z-index:10000; background-repeat:no-repeat; display:none; background-color:#456c43;  cursor:pointer; border:1px solid #eeeeee; border-left:0; -moz-box-shadow: 0px 0px 2px #666; -webkit-box-shadow: 0px 0px 2px #666; box-shadow: 0px 0px 2px #666;  -moz-border-radius:1px; border-radius:1px; -webkit-border-radius:1px; }";
            n.vakata.css.add_sheet({
                str: u,
                title: "jstree"
            }),
            r = n("<div />").attr({
                id: "jstree-marker"
            }).hide().html("&raquo;").bind("mouseleave mouseenter",
            function(n) {
                return r.hide(),
                i.hide(),
                n.preventDefault(),
                n.stopImmediatePropagation(),
                !1
            }).appendTo("body"),
            i = n("<div />").attr({
                id: "jstree-marker-line"
            }).hide().bind("mouseup",
            function(n) {
                if (t && t.length) return t.children("a").trigger(n),
                n.preventDefault(),

                n.stopImmediatePropagation(),
                !1
            }).bind("mouseleave",
            function(u) {
                var f = n(u.relatedTarget);
                if (f.is(".jstree") || f.closest(".jstree").length === 0) if (t && t.length) return t.children("a").trigger(u),
                r.hide(),
                i.hide(),
                u.preventDefault(),
                u.stopImmediatePropagation(),
                !1
            }).appendTo("body"),
            n(document).bind("drag_start.vakata",
            function(n, t) {
                t.data.jstree && (r.show(), i && i.show())
            }),
            n(document).bind("drag_stop.vakata",
            function(n, t) {
                t.data.jstree && (r.hide(), i && i.hide())
            })
        })
    })(jQuery),
    (function(n) {
        n.jstree.plugin("checkbox", {
            __init: function() {
                this.data.checkbox.noui = this._get_settings().checkbox.override_ui,
                this.data.ui && this.data.checkbox.noui && (this.select_node = this.deselect_node = this.deselect_all = n.noop, this.get_selected = this.get_checked),
                this.get_container().bind("open_node.jstree create_node.jstree clean_node.jstree refresh.jstree", n.proxy(function(n, t) {
                    this._prepare_checkboxes(t.rslt.obj)
                },
                this)).bind("loaded.jstree", n.proxy(function() {
                    this._prepare_checkboxes()
                },
                this)).delegate(this.data.ui && this.data.checkbox.noui ? "a": "ins.jstree-checkbox", "click.jstree", n.proxy(function(n) {
                    n.preventDefault(),
                    this._get_node(n.target).hasClass("jstree-checked") ? this.uncheck_node(n.target) : this.check_node(n.target);
                    if (this.data.ui && this.data.checkbox.noui) this.save_selected(),
                    this.data.cookies && this.save_cookie("select_node");
                    else return n.stopImmediatePropagation(),
                    !1
                },
                this))
            },
            defaults: {
                override_ui: !1,
                two_state: !1,
                real_checkboxes: !1,
                checked_parent_open: !0,
                real_checkboxes_names: function(n) {
                    return ["check_" + (n[0].id || Math.ceil(Math.random() * 1e4)), 1]
                }
            },
            __destroy: function() {
                this.get_container().find("input.jstree-real-checkbox").removeClass("jstree-real-checkbox").end().find("ins.jstree-checkbox").remove()
            },
            _fn: {
                _checkbox_notify: function(n, t) {
                    t.checked && this.check_node(n, !1)
                },
                _prepare_checkboxes: function(t) {
                    t = !t || t == -1 ? this.get_container().find("> ul > li") : this._get_node(t);
                    if (t === !1) return;
                    var u, f = this,
                    i, r = this._get_settings().checkbox.two_state,
                    e = this._get_settings().checkbox.real_checkboxes,
                    o = this._get_settings().checkbox.real_checkboxes_names;
                    t.each(function() {
                        i = n(this),
                        u = i.is("li") && (i.hasClass("jstree-checked") || e && i.children(":checked").length) ? "jstree-checked": "jstree-unchecked",
                        i.find("li").andSelf().each(function() {
                            var t = n(this),
                            i;
                            t.children("a" + (f.data.languages ? "": ":eq(0)")).not(":has(.jstree-checkbox)").prepend("<ins class='jstree-checkbox'>&#160;</ins>").parent().not(".jstree-checked, .jstree-unchecked").addClass(r ? "jstree-unchecked": u),
                            e && (t.children(":checkbox").length ? t.children(":checkbox").addClass("jstree-real-checkbox") : (i = o.call(f, t), t.prepend("<input type='checkbox' class='jstree-real-checkbox' id='" + i[0] + "' name='" + i[0] + "' value='" + i[1] + "' />"))),
                            r ? (t.hasClass("jstree-checked") || t.children(":checked").length) && t.addClass("jstree-checked").children(":checkbox").prop("checked", !0) : (u === "jstree-checked" || t.hasClass("jstree-checked") || t.children(":checked").length) && t.find("li").andSelf().addClass("jstree-checked").children(":checkbox").prop("checked", !0)
                        })
                    }),
                    r || t.find(".jstree-checked").parent().parent().each(function() {
                        f._repair_state(this)
                    })
                },
                change_state: function(t, i) {
                    t = this._get_node(t);
                    var u = !1,
                    r = this._get_settings().checkbox.real_checkboxes;
                    if (!t || t === -1) return ! 1;
                    i = i === !1 || i === !0 ? i: t.hasClass("jstree-checked");
                    if (this._get_settings().checkbox.two_state) i ? (t.removeClass("jstree-checked").addClass("jstree-unchecked"), r && t.children(":checkbox").prop("checked", !1)) : (t.removeClass("jstree-unchecked").addClass("jstree-checked"), r && t.children(":checkbox").prop("checked", !0));
                    else {
                        if (i) {
                            u = t.find("li").andSelf();
                            if (!u.filter(".jstree-checked, .jstree-undetermined").length) return ! 1;
                            u.removeClass("jstree-checked jstree-undetermined").addClass("jstree-unchecked"),
                            r && u.children(":checkbox").prop("checked", !1)
                        } else {
                            u = t.find("li").andSelf();
                            if (!u.filter(".jstree-unchecked, .jstree-undetermined").length) return ! 1;
                            u.removeClass("jstree-unchecked jstree-undetermined").addClass("jstree-checked"),
                            r && u.children(":checkbox").prop("checked", !0),
                            this.data.ui && (this.data.ui.last_selected = t),
                            this.data.checkbox.last_selected = t
                        }
                        t.parentsUntil(".jstree", "li").each(function() {
                            var t = n(this);
                            if (i) {
                                if (t.children("ul").children("li.jstree-checked, li.jstree-undetermined").length) return t.parentsUntil(".jstree", "li").andSelf().removeClass("jstree-checked jstree-unchecked").addClass("jstree-undetermined"),
                                r && t.parentsUntil(".jstree", "li").andSelf().children(":checkbox").prop("checked", !1),
                                !1;
                                t.removeClass("jstree-checked jstree-undetermined").addClass("jstree-unchecked"),
                                r && t.children(":checkbox").prop("checked", !1)
                            } else {
                                if (t.children("ul").children("li.jstree-unchecked, li.jstree-undetermined").length) return t.parentsUntil(".jstree", "li").andSelf().removeClass("jstree-checked jstree-unchecked").addClass("jstree-undetermined"),
                                r && t.parentsUntil(".jstree", "li").andSelf().children(":checkbox").prop("checked", !1),
                                !1;
                                t.removeClass("jstree-unchecked jstree-undetermined").addClass("jstree-checked"),
                                r && t.children(":checkbox").prop("checked", !0)
                            }
                        })
                    }
                    return this.data.ui && this.data.checkbox.noui && (this.data.ui.selected = this.get_checked()),
                    this.__callback(t),
                    !0
                },
                check_node: function(n) {
                    if (this.change_state(n, !1)) {
                        n = this._get_node(n);
                        if (this._get_settings().checkbox.checked_parent_open) {
                            var t = this;
                            n.parents(".jstree-closed").each(function() {
                                t.open_node(this, !1, !0)
                            })
                        }
                        this.__callback({
                            obj: n
                        })
                    }
                },
                uncheck_node: function(n) {
                    this.change_state(n, !0) && this.__callback({
                        obj: this._get_node(n)
                    })
                },
                check_all: function() {
                    var t = this,
                    n = this._get_settings().checkbox.two_state ? this.get_container_ul().find("li") : this.get_container_ul().children("li");
                    n.each(function() {
                        t.change_state(this, !1)
                    }),
                    this.__callback()
                },
                uncheck_all: function() {
                    var t = this,
                    n = this._get_settings().checkbox.two_state ? this.get_container_ul().find("li") : this.get_container_ul().children("li");
                    n.each(function() {
                        t.change_state(this, !0)
                    }),
                    this.__callback()
                },
                is_checked: function(n) {
                    return n = this._get_node(n),
                    n.length ? n.is(".jstree-checked") : !1
                },
                get_checked: function(n, t) {
                    return n = !n || n === -1 ? this.get_container() : this._get_node(n),
                    t || this._get_settings().checkbox.two_state ? n.find(".jstree-checked") : n.find("> ul > .jstree-checked, .jstree-undetermined > ul > .jstree-checked")
                },
                get_unchecked: function(n, t) {
                    return n = !n || n === -1 ? this.get_container() : this._get_node(n),
                    t || this._get_settings().checkbox.two_state ? n.find(".jstree-unchecked") : n.find("> ul > .jstree-unchecked, .jstree-undetermined > ul > .jstree-unchecked")
                },
                show_checkboxes: function() {
                    this.get_container().children("ul").removeClass("jstree-no-checkboxes")
                },
                hide_checkboxes: function() {
                    this.get_container().children("ul").addClass("jstree-no-checkboxes")
                },
                _repair_state: function(n) {
                    n = this._get_node(n);
                    if (!n.length) return;
                    if (this._get_settings().checkbox.two_state) {
                        n.find("li").andSelf().not(".jstree-checked").removeClass("jstree-undetermined").addClass("jstree-unchecked").children(":checkbox").prop("checked", !0);
                        return
                    }
                    var r = this._get_settings().checkbox.real_checkboxes,
                    i = n.find("> ul > .jstree-checked").length,
                    u = n.find("> ul > .jstree-undetermined").length,
                    t = n.find("> ul > li").length;
                    t === 0 ? n.hasClass("jstree-undetermined") && this.change_state(n, !1) : i === 0 && u === 0 ? this.change_state(n, !0) : i === t ? this.change_state(n, !1) : (n.parentsUntil(".jstree", "li").andSelf().removeClass("jstree-checked jstree-unchecked").addClass("jstree-undetermined"), r && n.parentsUntil(".jstree", "li").andSelf().children(":checkbox").prop("checked", !1))
                },
                reselect: function() {
                    if (this.data.ui && this.data.checkbox.noui) {
                        var i = this,
                        t = this.data.ui.to_select;
                        t = n.map(n.makeArray(t),
                        function(n) {
                            return "#" + n.toString().replace(/^#/, "").replace(/\\\//g, "/").replace(/\//g, "\\/").replace(/\\\./g, ".").replace(/\./g, "\\.").replace(/\:/g, "\\:")
                        }),
                        this.deselect_all(),
                        n.each(t,
                        function(n, t) {
                            i.check_node(t)
                        }),
                        this.__callback()
                    } else this.__call_old()
                },
                save_loaded: function() {
                    var n = this;
                    this.data.core.to_load = [],
                    this.get_container_ul().find("li.jstree-closed.jstree-undetermined").each(function() {
                        this.id && n.data.core.to_load.push("#" + this.id)
                    })
                }
            }
        }),
        n(function() {
            var t = ".jstree .jstree-real-checkbox { display:none; } ";
            n.vakata.css.add_sheet({
                str: t,
                title: "jstree"
            })
        })
    })(jQuery),
    (function(n) {
        n.vakata.xslt = function(t, i, r) {
            var f = "",
            o, e, u, s;
            if (document.recalc) return o = document.createElement("xml"),
            e = document.createElement("xml"),
            o.innerHTML = t,
            e.innerHTML = i,
            n("body").append(o).append(e),
            setTimeout(function(t, i, r) {
                return function() {
                    r.call(null, t.transformNode(i.XMLDocument)),
                    setTimeout(function(t, i) {
                        return function() {
                            n(t).remove(),
                            n(i).remove()
                        }
                    } (t, i), 200)
                }
            } (o, e, r), 100),
            !0;
            return typeof window.DOMParser != "undefined" && typeof window.XMLHttpRequest != "undefined" && typeof window.XSLTProcessor == "undefined" && (t = (new DOMParser).parseFromString(t, "text/xml"), i = (new DOMParser).parseFromString(i, "text/xml")),
            typeof window.DOMParser != "undefined" && typeof window.XMLHttpRequest != "undefined" && typeof window.XSLTProcessor != "undefined" ? (u = new XSLTProcessor, s = n.isFunction(u.transformDocument) ? typeof window.XMLSerializer != "undefined": !0, s ? (t = (new DOMParser).parseFromString(t, "text/xml"), i = (new DOMParser).parseFromString(i, "text/xml"), n.isFunction(u.transformDocument) ? (f = document.implementation.createDocument("", "", null), u.transformDocument(t, i, f, null), r.call(null, (new XMLSerializer).serializeToString(f)), !0) : (u.importStylesheet(i), f = u.transformToFragment(t, document), r.call(null, n("<div />").append(f).html()), !0)) : !1) : !1
        };
        var i = {
            nest: '<?xml version="1.0" encoding="utf-8" ?><xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" ><xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" standalone="no" indent="no" media-type="text/html" /><xsl:template match="/">\t<xsl:call-template name="nodes">\t\t<xsl:with-param name="node" select="/root" />\t</xsl:call-template></xsl:template><xsl:template name="nodes">\t<xsl:param name="node" />\t<ul>\t<xsl:for-each select="$node/item">\t\t<xsl:variable name="children" select="count(./item) &gt; 0" />\t\t<li>\t\t\t<xsl:attribute name="class">\t\t\t\t<xsl:if test="position() = last()">jstree-last </xsl:if>\t\t\t\t<xsl:choose>\t\t\t\t\t<xsl:when test="@state = \'open\'">jstree-open </xsl:when>\t\t\t\t\t<xsl:when test="$children or @hasChildren or @state = \'closed\'">jstree-closed </xsl:when>\t\t\t\t\t<xsl:otherwise>jstree-leaf </xsl:otherwise>\t\t\t\t</xsl:choose>\t\t\t\t<xsl:value-of select="@class" />\t\t\t</xsl:attribute>\t\t\t<xsl:for-each select="@*">\t\t\t\t<xsl:if test="name() != \'class\' and name() != \'state\' and name() != \'hasChildren\'">\t\t\t\t\t<xsl:attribute name="{name()}"><xsl:value-of select="." /></xsl:attribute>\t\t\t\t</xsl:if>\t\t\t</xsl:for-each>\t<ins class="jstree-icon"><xsl:text>&#xa0;</xsl:text></ins>\t\t\t<xsl:for-each select="content/name">\t\t\t\t<a>\t\t\t\t<xsl:attribute name="href">\t\t\t\t\t<xsl:choose>\t\t\t\t\t<xsl:when test="@href"><xsl:value-of select="@href" /></xsl:when>\t\t\t\t\t<xsl:otherwise>#</xsl:otherwise>\t\t\t\t\t</xsl:choose>\t\t\t\t</xsl:attribute>\t\t\t\t<xsl:attribute name="class"><xsl:value-of select="@lang" /> <xsl:value-of select="@class" /></xsl:attribute>\t\t\t\t<xsl:attribute name="style"><xsl:value-of select="@style" /></xsl:attribute>\t\t\t\t<xsl:for-each select="@*">\t\t\t\t\t<xsl:if test="name() != \'style\' and name() != \'class\' and name() != \'href\'">\t\t\t\t\t\t<xsl:attribute name="{name()}"><xsl:value-of select="." /></xsl:attribute>\t\t\t\t\t</xsl:if>\t\t\t\t</xsl:for-each>\t\t\t\t\t<ins>\t\t\t\t\t\t<xsl:attribute name="class">jstree-icon \t\t\t\t\t\t\t<xsl:if test="string-length(attribute::icon) > 0 and not(contains(@icon,\'/\'))"><xsl:value-of select="@icon" /></xsl:if>\t\t\t\t\t\t</xsl:attribute>\t\t\t\t\t\t<xsl:if test="string-length(attribute::icon) > 0 and contains(@icon,\'/\')"><xsl:attribute name="style">background:url(<xsl:value-of select="@icon" />) center center no-repeat;</xsl:attribute></xsl:if>\t\t\t\t\t\t<xsl:text>&#xa0;</xsl:text>\t\t\t\t\t</ins>\t\t\t\t\t<xsl:copy-of select="./child::node()" />\t\t\t\t</a>\t\t\t</xsl:for-each>\t\t\t<xsl:if test="$children or @hasChildren"><xsl:call-template name="nodes"><xsl:with-param name="node" select="current()" /></xsl:call-template></xsl:if>\t\t</li>\t</xsl:for-each>\t</ul></xsl:template></xsl:stylesheet>',
            flat: '<?xml version="1.0" encoding="utf-8" ?><xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" ><xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" standalone="no" indent="no" media-type="text/xml" /><xsl:template match="/">\t<ul>\t<xsl:for-each select="//item[not(@parent_id) or @parent_id=0 or not(@parent_id = //item/@id)]">\t\t<xsl:call-template name="nodes">\t\t\t<xsl:with-param name="node" select="." />\t\t\t<xsl:with-param name="is_last" select="number(position() = last())" />\t\t</xsl:call-template>\t</xsl:for-each>\t</ul></xsl:template><xsl:template name="nodes">\t<xsl:param name="node" />\t<xsl:param name="is_last" />\t<xsl:variable name="children" select="count(//item[@parent_id=$node/attribute::id]) &gt; 0" />\t<li>\t<xsl:attribute name="class">\t\t<xsl:if test="$is_last = true()">jstree-last </xsl:if>\t\t<xsl:choose>\t\t\t<xsl:when test="@state = \'open\'">jstree-open </xsl:when>\t\t\t<xsl:when test="$children or @hasChildren or @state = \'closed\'">jstree-closed </xsl:when>\t\t\t<xsl:otherwise>jstree-leaf </xsl:otherwise>\t\t</xsl:choose>\t\t<xsl:value-of select="@class" />\t</xsl:attribute>\t<xsl:for-each select="@*">\t\t<xsl:if test="name() != \'parent_id\' and name() != \'hasChildren\' and name() != \'class\' and name() != \'state\'">\t\t<xsl:attribute name="{name()}"><xsl:value-of select="." /></xsl:attribute>\t\t</xsl:if>\t</xsl:for-each>\t<ins class="jstree-icon"><xsl:text>&#xa0;</xsl:text></ins>\t<xsl:for-each select="content/name">\t\t<a>\t\t<xsl:attribute name="href">\t\t\t<xsl:choose>\t\t\t<xsl:when test="@href"><xsl:value-of select="@href" /></xsl:when>\t\t\t<xsl:otherwise>#</xsl:otherwise>\t\t\t</xsl:choose>\t\t</xsl:attribute>\t\t<xsl:attribute name="class"><xsl:value-of select="@lang" /> <xsl:value-of select="@class" /></xsl:attribute>\t\t<xsl:attribute name="style"><xsl:value-of select="@style" /></xsl:attribute>\t\t<xsl:for-each select="@*">\t\t\t<xsl:if test="name() != \'style\' and name() != \'class\' and name() != \'href\'">\t\t\t\t<xsl:attribute name="{name()}"><xsl:value-of select="." /></xsl:attribute>\t\t\t</xsl:if>\t\t</xsl:for-each>\t\t\t<ins>\t\t\t\t<xsl:attribute name="class">jstree-icon \t\t\t\t\t<xsl:if test="string-length(attribute::icon) > 0 and not(contains(@icon,\'/\'))"><xsl:value-of select="@icon" /></xsl:if>\t\t\t\t</xsl:attribute>\t\t\t\t<xsl:if test="string-length(attribute::icon) > 0 and contains(@icon,\'/\')"><xsl:attribute name="style">background:url(<xsl:value-of select="@icon" />) center center no-repeat;</xsl:attribute></xsl:if>\t\t\t\t<xsl:text>&#xa0;</xsl:text>\t\t\t</ins>\t\t\t<xsl:copy-of select="./child::node()" />\t\t</a>\t</xsl:for-each>\t<xsl:if test="$children">\t\t<ul>\t\t<xsl:for-each select="//item[@parent_id=$node/attribute::id]">\t\t\t<xsl:call-template name="nodes">\t\t\t\t<xsl:with-param name="node" select="." />\t\t\t\t<xsl:with-param name="is_last" select="number(position() = last())" />\t\t\t</xsl:call-template>\t\t</xsl:for-each>\t\t</ul>\t</xsl:if>\t</li></xsl:template></xsl:stylesheet>'
        },
        t = function(n) {
            return n.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&apos;")
        };
        n.jstree.plugin("xml_data", {
            defaults: {
                data: !1,
                ajax: !1,
                xsl: "flat",
                clean_node: !1,
                correct_state: !0,
                get_skip_empty: !1,
                get_include_preamble: !0
            },
            _fn: {
                load_node: function(n, t, i) {
                    var r = this;
                    this.load_node_xml(n,
                    function() {
                        r.__callback({
                            obj: r._get_node(n)
                        }),
                        t.call(this)
                    },
                    i)
                },
                _is_loaded: function(t) {
                    var i = this._get_settings().xml_data;
                    return t = this._get_node(t),
                    t == -1 || !t || !i.ajax && !n.isFunction(i.data) || t.is(".jstree-open, .jstree-leaf") || t.children("ul").children("li").size() > 0
                },
                load_node_xml: function(t, i, r) {
                    var u = this.get_settings().xml_data,
                    f = function() {},
                    e = function() {};
                    t = this._get_node(t);
                    if (t && t !== -1) {
                        if (t.data("jstree_is_loading")) return;
                        t.data("jstree_is_loading", !0)
                    }
                    switch (!0) {
                    case ! u.data && !u.ajax: throw "Neither data nor ajax settings supplied.";
                    case n.isFunction(u.data):
                        u.data.call(this, t, n.proxy(function(r) {
                            this.parse_xml(r, n.proxy(function(r) {
                                r && (r = r.replace(/ ?xmlns="[^"]*"/ig, ""), r.length > 10 ? (r = n(r), t === -1 || !t ? this.get_container().children("ul").empty().append(r.children()) : (t.children("a.jstree-loading").removeClass("jstree-loading"), t.append(r), t.removeData("jstree_is_loading")), u.clean_node && this.clean_node(t), i && i.call(this)) : t && t !== -1 ? (t.children("a.jstree-loading").removeClass("jstree-loading"), t.removeData("jstree_is_loading"), u.correct_state && (this.correct_state(t), i && i.call(this))) : u.correct_state && (this.get_container().children("ul").empty(), i && i.call(this)))
                            },
                            this))
                        },
                        this));
                        break;
                    case !! u.data && !u.ajax || !!u.data && !!u.ajax && (!t || t === -1) : (!t || t == -1) && this.parse_xml(u.data, n.proxy(function(r) {
                            r ? (r = r.replace(/ ?xmlns="[^"]*"/ig, ""), r.length > 10 && (r = n(r), this.get_container().children("ul").empty().append(r.children()), u.clean_node && this.clean_node(t), i && i.call(this))) : u.correct_state && (this.get_container().children("ul").empty(), i && i.call(this))
                        },
                        this));
                        break;
                    case ! u.data && !!u.ajax || !!u.data && !!u.ajax && t && t !== -1 : f = function(n, i, f) {
                            var e = this.get_settings().xml_data.ajax.error;
                            e && e.call(this, n, i, f),
                            t !== -1 && t.length ? (t.children("a.jstree-loading").removeClass("jstree-loading"), t.removeData("jstree_is_loading"), i === "success" && u.correct_state && this.correct_state(t)) : i === "success" && u.correct_state && this.get_container().children("ul").empty(),
                            r && r.call(this)
                        },
                        e = function(r, e, o) {
                            r = o.responseText;
                            var s = this.get_settings().xml_data.ajax.success;
                            s && (r = s.call(this, r, e, o) || r);
                            if (r === "" || r && r.toString && r.toString().replace(/^[\s\n]+$/, "") === "") return f.call(this, o, e, "");
                            this.parse_xml(r, n.proxy(function(r) {
                                r && (r = r.replace(/ ?xmlns="[^"]*"/ig, ""), r.length > 10 ? (r = n(r), t === -1 || !t ? this.get_container().children("ul").empty().append(r.children()) : (t.children("a.jstree-loading").removeClass("jstree-loading"), t.append(r), t.removeData("jstree_is_loading")), u.clean_node && this.clean_node(t), i && i.call(this)) : t && t !== -1 ? (t.children("a.jstree-loading").removeClass("jstree-loading"), t.removeData("jstree_is_loading"), u.correct_state && (this.correct_state(t), i && i.call(this))) : u.correct_state && (this.get_container().children("ul").empty(), i && i.call(this)))
                            },
                            this))
                        },
                        u.ajax.context = this,
                        u.ajax.error = f,
                        u.ajax.success = e,
                        u.ajax.dataType || (u.ajax.dataType = "xml"),
                        n.isFunction(u.ajax.url) && (u.ajax.url = u.ajax.url.call(this, t)),
                        n.isFunction(u.ajax.data) && (u.ajax.data = u.ajax.data.call(this, t)),
                        n.ajax(u.ajax)
                    }
                },
                parse_xml: function(t, r) {
                    var u = this._get_settings().xml_data;
                    n.vakata.xslt(t, i[u.xsl], r)
                },
                get_xml: function(i, r, u, f, e) {
                    var o = "",
                    c = this._get_settings(),
                    v = this,
                    h,
                    l,
                    s,
                    y,
                    a;
                    return i || (i = "flat"),
                    e || (e = 0),
                    r = this._get_node(r),
                    (!r || r === -1) && (r = this.get_container().find("> ul > li")),
                    u = n.isArray(u) ? u: ["id", "class"],
                    !e && this.data.types && n.inArray(c.types.type_attr, u) === -1 && u.push(c.types.type_attr),
                    f = n.isArray(f) ? f: [],
                    e || (c.xml_data.get_include_preamble && (o += '<?xml version="1.0" encoding="UTF-8"?>'), o += "<root>"),
                    r.each(function() {
                        o += "<item",
                        s = n(this),
                        n.each(u,
                        function(n, i) {
                            var r = s.attr(i); (!c.xml_data.get_skip_empty || typeof r != "undefined") && (o += " " + i + '="' + t((" " + (r || "")).replace(/ jstree[^ ]*/ig, "").replace(/\s+$/ig, " ").replace(/^ /, "").replace(/ $/, "")) + '"')
                        }),
                        s.hasClass("jstree-open") && (o += ' state="open"'),
                        s.hasClass("jstree-closed") && (o += ' state="closed"'),
                        i === "flat" && (o += ' parent_id="' + t(e) + '"'),
                        o += ">",
                        o += "<content>",
                        y = s.children("a"),
                        y.each(function() {
                            h = n(this),
                            a = !1,
                            o += "<name",
                            n.inArray("languages", c.plugins) !== -1 && n.each(c.languages,
                            function(n, i) {
                                if (h.hasClass(i)) return o += ' lang="' + t(i) + '"',
                                a = i,
                                !1
                            }),
                            f.length && n.each(f,
                            function(n, i) {
                                var r = h.attr(i); (!c.xml_data.get_skip_empty || typeof r != "undefined") && (o += " " + i + '="' + t((" " + r || "").replace(/ jstree[^ ]*/ig, "").replace(/\s+$/ig, " ").replace(/^ /, "").replace(/ $/, "")) + '"')
                            }),
                            h.children("ins").get(0).className.replace(/jstree[^ ]*|$/ig, "").replace(/^\s+$/ig, "").length && (o += ' icon="' + t(h.children("ins").get(0).className.replace(/jstree[^ ]*|$/ig, "").replace(/\s+$/ig, " ").replace(/^ /, "").replace(/ $/, "")) + '"'),
                            h.children("ins").get(0).style.backgroundImage.length && (o += ' icon="' + t(h.children("ins").get(0).style.backgroundImage.replace("url(", "").replace(")", "").replace(/'/ig, "").replace(/"/ig, "")) + '"'),
                            o += ">",
                            o += "<![CDATA[" + v.get_text(h, a) + "]\]>",
                            o += "</name>"
                        }),
                        o += "</content>",
                        l = s[0].id || !0,
                        s = s.find("> ul > li"),
                        l = s.length ? v.get_xml(i, s, u, f, l) : "",
                        i == "nest" && (o += l),
                        o += "</item>",
                        i == "flat" && (o += l)
                    }),
                    e || (o += "</root>"),
                    o
                }
            }
        })
    })(jQuery),
    (function(n) {
        n.expr[":"].jstree_contains = function(n, t, i) {
            return (n.textContent || n.innerText || "").toLowerCase().indexOf(i[3].toLowerCase()) >= 0
        },
        n.expr[":"].jstree_title_contains = function(n, t, i) {
            return (n.getAttribute("title") || "").toLowerCase().indexOf(i[3].toLowerCase()) >= 0
        },
        n.jstree.plugin("search", {
            __init: function() {
                this.data.search.str = "",
                this.data.search.result = n(),
                this._get_settings().search.show_only_matches && this.get_container().bind("search.jstree",
                function(t, i) {
                    n(this).children("ul").find("li").hide().removeClass("jstree-last"),
                    i.rslt.nodes.parentsUntil(".jstree").andSelf().show().filter("ul").each(function() {
                        n(this).children("li:visible").eq( - 1).addClass("jstree-last")
                    })
                }).bind("clear_search.jstree",
                function() {
                    n(this).children("ul").find("li").css("display", "").end().end().jstree("clean_node", -1)
                })
            },
            defaults: {
                ajax: !1,
                search_method: "jstree_contains",
                show_only_matches: !1
            },
            _fn: {
                search: function(t, i) {
                    if (n.trim(t) === "") {
                        this.clear_search();
                        return
                    }
                    var r = this.get_settings().search,
                    e = this,
                    f = function() {},
                    u = function() {};
                    this.data.search.str = t;
                    if (!i && r.ajax !== !1 && this.get_container_ul().find("li.jstree-closed:not(:has(ul)):eq(0)").length > 0) {
                        this.search.supress_callback = !0,
                        f = function() {},
                        u = function(n, t, i) {
                            var r = this.get_settings().search.ajax.success;
                            r && (n = r.call(this, n, t, i) || n),
                            this.data.search.to_open = n,
                            this._search_open()
                        },
                        r.ajax.context = this,
                        r.ajax.error = f,
                        r.ajax.success = u,
                        n.isFunction(r.ajax.url) && (r.ajax.url = r.ajax.url.call(this, t)),
                        n.isFunction(r.ajax.data) && (r.ajax.data = r.ajax.data.call(this, t)),
                        r.ajax.data || (r.ajax.data = {
                            search_string: t
                        }),
                        (!r.ajax.dataType || /^json/.exec(r.ajax.dataType)) && (r.ajax.dataType = "json"),
                        n.ajax(r.ajax);
                        return
                    }
                    this.data.search.result.length && this.clear_search(),
                    this.data.search.result = this.get_container().find("a" + (this.data.languages ? "." + this.get_lang() : "") + ":" + r.search_method + "(" + this.data.search.str + ")"),
                    this.data.search.result.addClass("jstree-search").parent().parents(".jstree-closed").each(function() {
                        e.open_node(this, !1, !0)
                    }),
                    this.__callback({
                        nodes: this.data.search.result,
                        str: t
                    })
                },
                clear_search: function() {
                    this.data.search.result.removeClass("jstree-search"),
                    this.__callback(this.data.search.result),
                    this.data.search.result = n()
                },
                _search_open: function() {
                    var u = this,
                    f = !0,
                    i = [],
                    r = [];
                    this.data.search.to_open.length && (n.each(this.data.search.to_open,
                    function(t, u) {
                        if (u == "#") return ! 0;
                        n(u).length && n(u).is(".jstree-closed") ? i.push(u) : r.push(u)
                    }), i.length && (this.data.search.to_open = r, n.each(i,
                    function(n, t) {
                        u.open_node(t,
                        function() {
                            u._search_open(!0)
                        })
                    }), f = !1)),
                    f && this.search(this.data.search.str, !0)
                }
            }
        })
    })(jQuery),
    (function(n) {
        n.vakata.context = {
            hide_on_mouseleave: !1,
            cnt: n("<div id='vakata-contextmenu' />"),
            vis: !1,
            tgt: !1,
            par: !1,
            func: !1,
            data: !1,
            rtl: !1,
            show: function(t, i, r, u, f, e, o) {
                n.vakata.context.rtl = !!o;
                var c = n.vakata.context.parse(t),
                s,
                h;
                if (!c) return;
                n.vakata.context.vis = !0,
                n.vakata.context.tgt = i,
                n.vakata.context.par = e || i || null,
                n.vakata.context.data = f || null,
                n.vakata.context.cnt.html(c).css({
                    visibility: "hidden",
                    display: "block",
                    left: 0,
                    top: 0
                });
                if (n.vakata.context.hide_on_mouseleave) n.vakata.context.cnt.one("mouseleave",
                function() {
                    n.vakata.context.hide()
                });
                s = n.vakata.context.cnt.height(),
                h = n.vakata.context.cnt.width(),
                r + h > n(document).width() && (r = n(document).width() - (h + 5), n.vakata.context.cnt.find("li > ul").addClass("right")),
                u + s > n(document).height() && (u = u - (s + i[0].offsetHeight), n.vakata.context.cnt.find("li > ul").addClass("bottom")),
                n.vakata.context.cnt.css({
                    left: r,
                    top: u
                }).find("li:has(ul)").bind("mouseenter",
                function() {
                    var r = n(document).width(),
                    u = n(document).height(),
                    i = n(this).children("ul").show();
                    r !== n(document).width() && i.toggleClass("right"),
                    u !== n(document).height() && i.toggleClass("bottom")
                }).bind("mouseleave",
                function() {
                    n(this).children("ul").hide()
                }).end().css({
                    visibility: "visible"
                }).show(),
                n(document).triggerHandler("context_show.vakata")
            },
            hide: function() {
                n.vakata.context.vis = !1,
                n.vakata.context.cnt.attr("class", "").css({
                    visibility: "hidden"
                }),
                n(document).triggerHandler("context_hide.vakata")
            },
            parse: function(t, i) {
                if (!t) return ! 1;
                var r = "",
                f = !1,
                u = !0;
                return i || (n.vakata.context.func = {}),
                r += "<ul>",
                n.each(t,
                function(t, i) {
                    if (!i) return ! 0;
                    n.vakata.context.func[t] = i.action,
                    !u && i.separator_before && (r += "<li class='vakata-separator vakata-separator-before'></li>"),
                    u = !1,
                    r += "<li class='" + (i._class || "") + (i._disabled ? " jstree-contextmenu-disabled ": "") + "'><ins ",
                    i.icon && i.icon.indexOf("/") === -1 && (r += " class='" + i.icon + "' "),
                    i.icon && i.icon.indexOf("/") !== -1 && (r += " style='background:url(" + i.icon + ") center center no-repeat;' "),
                    r += ">&#160;</ins><a href='#' rel='" + t + "'>",
                    i.submenu && (r += "<span style='float:" + (n.vakata.context.rtl ? "left": "right") + ";'>&raquo;</span>"),
                    r += i.label + "</a>",
                    i.submenu && (f = n.vakata.context.parse(i.submenu, !0), f && (r += f)),
                    r += "</li>",
                    i.separator_after && (r += "<li class='vakata-separator vakata-separator-after'></li>", u = !0)
                }),
                r = r.replace(/<li class\='vakata-separator vakata-separator-after'\><\/li\>$/, ""),
                r += "</ul>",
                n(document).triggerHandler("context_parse.vakata"),
                r.length > 10 ? r: !1
            },
            exec: function(t) {
                return n.isFunction(n.vakata.context.func[t]) ? (n.vakata.context.func[t].call(n.vakata.context.data, n.vakata.context.par), !0) : !1
            }
        },
        n(function() {
            var t = "#vakata-contextmenu { display:block; visibility:hidden; left:0; top:-200px; position:absolute; margin:0; padding:0; min-width:180px; background:#ebebeb; border:1px solid silver; z-index:10000; *width:180px; } #vakata-contextmenu ul { min-width:180px; *width:180px; } #vakata-contextmenu ul, #vakata-contextmenu li { margin:0; padding:0; list-style-type:none; display:block; } #vakata-contextmenu li { line-height:20px; min-height:20px; position:relative; padding:0px; } #vakata-contextmenu li a { padding:1px 6px; line-height:17px; display:block; text-decoration:none; margin:1px 1px 0 1px; } #vakata-contextmenu li ins { float:left; width:16px; height:16px; text-decoration:none; margin-right:2px; } #vakata-contextmenu li a:hover, #vakata-contextmenu li.vakata-hover > a { background:gray; color:white; } #vakata-contextmenu li ul { display:none; position:absolute; top:-2px; left:100%; background:#ebebeb; border:1px solid gray; } #vakata-contextmenu .right { right:100%; left:auto; } #vakata-contextmenu .bottom { bottom:-1px; top:auto; } #vakata-contextmenu li.vakata-separator { min-height:0; height:1px; line-height:1px; font-size:1px; overflow:hidden; margin:0 2px; background:silver; /* border-top:1px solid #fefefe; */ padding:0; } ";
            n.vakata.css.add_sheet({
                str: t,
                title: "vakata"
            }),
            n.vakata.context.cnt.delegate("a", "click",
            function(n) {
                n.preventDefault()
            }).delegate("a", "mouseup",
            function() { ! n(this).parent().hasClass("jstree-contextmenu-disabled") && n.vakata.context.exec(n(this).attr("rel")) ? n.vakata.context.hide() : n(this).blur()
            }).delegate("a", "mouseover",
            function() {
                n.vakata.context.cnt.find(".vakata-hover").removeClass("vakata-hover")
            }).appendTo("body"),
            n(document).bind("mousedown",
            function(t) {
                n.vakata.context.vis && !n.contains(n.vakata.context.cnt[0], t.target) && n.vakata.context.hide()
            }),
            typeof n.hotkeys != "undefined" && n(document).bind("keydown", "up",
            function(t) {
                if (n.vakata.context.vis) {
                    var i = n.vakata.context.cnt.find("ul:visible").last().children(".vakata-hover").removeClass("vakata-hover").prevAll("li:not(.vakata-separator)").first();
                    i.length || (i = n.vakata.context.cnt.find("ul:visible").last().children("li:not(.vakata-separator)").last()),
                    i.addClass("vakata-hover"),
                    t.stopImmediatePropagation(),
                    t.preventDefault()
                }
            }).bind("keydown", "down",
            function(t) {
                if (n.vakata.context.vis) {
                    var i = n.vakata.context.cnt.find("ul:visible").last().children(".vakata-hover").removeClass("vakata-hover").nextAll("li:not(.vakata-separator)").first();
                    i.length || (i = n.vakata.context.cnt.find("ul:visible").last().children("li:not(.vakata-separator)").first()),
                    i.addClass("vakata-hover"),
                    t.stopImmediatePropagation(),
                    t.preventDefault()
                }
            }).bind("keydown", "right",
            function(t) {
                n.vakata.context.vis && (n.vakata.context.cnt.find(".vakata-hover").children("ul").show().children("li:not(.vakata-separator)").removeClass("vakata-hover").first().addClass("vakata-hover"), t.stopImmediatePropagation(), t.preventDefault())
            }).bind("keydown", "left",
            function(t) {
                n.vakata.context.vis && (n.vakata.context.cnt.find(".vakata-hover").children("ul").hide().children(".vakata-separator").removeClass("vakata-hover"), t.stopImmediatePropagation(), t.preventDefault())
            }).bind("keydown", "esc",
            function(t) {
                n.vakata.context.hide(),
                t.preventDefault()
            }).bind("keydown", "space",
            function(t) {
                n.vakata.context.cnt.find(".vakata-hover").last().children("a").click(),
                t.preventDefault()
            })
        }),
        n.jstree.plugin("contextmenu", {
            __init: function() {
                this.get_container().delegate("a", "contextmenu.jstree", n.proxy(function(t) {
                    t.preventDefault(),
                    n(t.currentTarget).hasClass("jstree-loading") || this.show_contextmenu(t.currentTarget, t.pageX, t.pageY)
                },
                this)).delegate("a", "click.jstree", n.proxy(function() {
                    this.data.contextmenu && n.vakata.context.hide()
                },
                this)).bind("destroy.jstree", n.proxy(function() {
                    this.data.contextmenu && n.vakata.context.hide()
                },
                this)),
                n(document).bind("context_hide.vakata", n.proxy(function() {
                    this.data.contextmenu = !1
                },
                this))
            },
            defaults: {
                select_node: !1,
                show_at_node: !0,
                items: {
                    create: {
                        separator_before: !1,
                        separator_after: !0,
                        label: "Create",
                        action: function(n) {
                            this.create(n)
                        }
                    },
                    rename: {
                        separator_before: !1,
                        separator_after: !1,
                        label: "Rename",
                        action: function(n) {
                            this.rename(n)
                        }
                    },
                    remove: {
                        separator_before: !1,
                        icon: !1,
                        separator_after: !1,
                        label: "Delete",
                        action: function(n) {
                            this.is_selected(n) ? this.remove() : this.remove(n)
                        }
                    }
                }
            },
            _fn: {
                show_contextmenu: function(t, i, r) {
                    t = this._get_node(t);
                    var f = this.get_settings().contextmenu,
                    o = t.children("a:visible:eq(0)"),
                    e = !1,
                    u = !1;
                    f.select_node && this.data.ui && !this.is_selected(t) && (this.deselect_all(), this.select_node(t, !0)),
                    (f.show_at_node || typeof i == "undefined" || typeof r == "undefined") && (e = o.offset(), i = e.left, r = e.top + this.data.core.li_height),
                    u = t.data("jstree") && t.data("jstree").contextmenu ? t.data("jstree").contextmenu: f.items,
                    n.isFunction(u) && (u = u.call(this, t)),
                    this.data.contextmenu = !0,
                    n.vakata.context.show(u, o, i, r, this, t, this._get_settings().core.rtl),
                    this.data.themes && n.vakata.context.cnt.attr("class", "jstree-" + this.data.themes.theme + "-context")
                }
            }
        })
    })(jQuery),
    (function(t) {
        t.jstree.plugin("types", {
            __init: function() {
                var i = this._get_settings().types;
                this.data.types.attach_to = [],
                this.get_container().bind("init.jstree", t.proxy(function() {
                    var u = i.types,
                    f = i.type_attr,
                    n = "",
                    r = this;
                    t.each(u,
                    function(i, u) {
                        t.each(u,
                        function(n) { / ^(max_depth | max_children | icon | valid_children) $ / .test(n) || r.data.types.attach_to.push(n)
                        });
                        if (!u.icon) return ! 0; (u.icon.image || u.icon.position) && (n += i == "default" ? ".jstree-" + r.get_index() + " a > .jstree-icon { ": ".jstree-" + r.get_index() + " li[" + f + '="' + i + '"] > a > .jstree-icon { ', u.icon.image && (n += " background-image:url(" + u.icon.image + "); "), n += u.icon.position ? " background-position:" + u.icon.position + "; ": " background-position:0 0; ", n += "} ")
                    }),
                    n !== "" && t.vakata.css.add_sheet({
                        str: n,
                        title: "jstree-types"
                    })
                },
                this)).bind("before.jstree", t.proxy(function(n, i) {
                    var r, e, u = this._get_settings().types.use_data ? this._get_node(i.args[0]) : !1,
                    f = u && u !== -1 && u.length ? u.data("jstree") : !1;
                    if (f && f.types && f.types[i.func] === !1) return n.stopImmediatePropagation(),
                    !1;
                    if (t.inArray(i.func, this.data.types.attach_to) !== -1) {
                        if (!i.args[0] || !i.args[0].tagName && !i.args[0].jquery) return;
                        r = this._get_settings().types.types,
                        e = this._get_type(i.args[0]);
                        if ((r[e] && typeof r[e][i.func] != "undefined" || r["default"] && typeof r["default"][i.func] != "undefined") && this._check(i.func, i.args[0]) === !1) return n.stopImmediatePropagation(),
                        !1
                    }
                },
                this)),
                n && this.get_container().bind("load_node.jstree set_type.jstree", t.proxy(function(n, i) {
                    var f = i && i.rslt && i.rslt.obj && i.rslt.obj !== -1 ? this._get_node(i.rslt.obj).parent() : this.get_container_ul(),
                    r = !1,
                    u = this._get_settings().types;
                    t.each(u.types,
                    function(n, t) {
                        t.icon && (t.icon.image || t.icon.position) && (r = n === "default" ? f.find("li > a > .jstree-icon") : f.find("li[" + u.type_attr + "='" + n + "'] > a > .jstree-icon"), t.icon.image && r.css("backgroundImage", "url(" + t.icon.image + ")"), r.css("backgroundPosition", t.icon.position || "0 0"))
                    })
                },
                this))
            },
            defaults: {
                max_children: -1,
                max_depth: -1,
                valid_children: "all",
                use_data: !1,
                type_attr: "rel",
                types: {
                    "default": {
                        max_children: -1,
                        max_depth: -1,
                        valid_children: "all"
                    }
                }
            },
            _fn: {
                _types_notify: function(n, t) {
                    t.type && this._get_settings().types.use_data && this.set_type(t.type, n)
                },
                _get_type: function(n) {
                    return n = this._get_node(n),
                    !n || !n.length ? !1 : n.attr(this._get_settings().types.type_attr) || "default"
                },
                set_type: function(n, t) {
                    t = this._get_node(t);
                    var i = !t.length || !n ? !1 : t.attr(this._get_settings().types.type_attr, n);
                    return i && this.__callback({
                        obj: t,
                        type: n
                    }),
                    i
                },
                _check: function(n, i, r) {
                    i = this._get_node(i);
                    var u = !1,
                    s = this._get_type(i),
                    e = 0,
                    h = this,
                    f = this._get_settings().types,
                    o = !1;
                    if (i === -1) {
                        if (!f[n]) return;
                        u = f[n]
                    } else {
                        if (s === !1) return;
                        o = f.use_data ? i.data("jstree") : !1,
                        o && o.types && typeof o.types[n] != "undefined" ? u = o.types[n] : !!f.types[s] && typeof f.types[s][n] != "undefined" ? u = f.types[s][n] : !!f.types["default"] && typeof f.types["default"][n] != "undefined" && (u = f.types["default"][n])
                    }
                    return t.isFunction(u) && (u = u.call(this, i)),
                    n === "max_depth" && i !== -1 && r !== !1 && f.max_depth !== -2 && u !== 0 && i.children("a:eq(0)").parentsUntil(".jstree", "li").each(function(t) {
                        if (f.max_depth !== -1 && f.max_depth - (t + 1) <= 0) return u = 0,
                        !1;
                        e = t === 0 ? u: h._check(n, this, !1);
                        if (e !== -1 && e - (t + 1) <= 0) return u = 0,
                        !1;
                        e >= 0 && (e - (t + 1) < u || u < 0) && (u = e - (t + 1)),
                        f.max_depth >= 0 && (f.max_depth - (t + 1) < u || u < 0) && (u = f.max_depth - (t + 1))
                    }),
                    u
                },
                check_move: function() {
                    if (!this.__call_old()) return ! 1;
                    var n = this._get_move(),
                    o = n.rt._get_settings().types,
                    e = n.rt._check("max_children", n.cr),
                    f = n.rt._check("max_depth", n.cr),
                    u = n.rt._check("valid_children", n.cr),
                    s = 0,
                    i = 1,
                    r;
                    if (u === "none") return ! 1;
                    if (t.isArray(u) && n.ot && n.ot._get_type) {
                        n.o.each(function() {
                            if (t.inArray(n.ot._get_type(this), u) === -1) return i = !1,
                            !1
                        });
                        if (i === !1) return ! 1
                    }
                    if (o.max_children !== -2 && e !== -1) {
                        s = n.cr === -1 ? this.get_container().find("> ul > li").not(n.o).length: n.cr.find("> ul > li").not(n.o).length;
                        if (s + n.o.length > e) return ! 1
                    }
                    if (o.max_depth !== -2 && f !== -1) {
                        i = 0;
                        if (f === 0) return ! 1;
                        if (typeof n.o.d == "undefined") {
                            r = n.o;
                            while (r.length > 0) r = r.find("> ul > li"),
                            i++;
                            n.o.d = i
                        }
                        if (f - n.o.d < 0) return ! 1
                    }
                    return ! 0
                },
                create_node: function(n, i, r, u, f, e) {
                    if (!e && (f || this._is_loaded(n))) {
                        var s = typeof i == "string" && i.match(/^before|after$/i) && n !== -1 ? this._get_parent(n) : this._get_node(n),
                        o = this._get_settings().types,
                        l = this._check("max_children", s),
                        a = this._check("max_depth", s),
                        h = this._check("valid_children", s),
                        c;
                        typeof r == "string" && (r = {
                            data: r
                        }),
                        r || (r = {});
                        if (h === "none") return ! 1;
                        if (t.isArray(h)) if (!r.attr || !r.attr[o.type_attr]) r.attr || (r.attr = {}),
                        r.attr[o.type_attr] = h[0];
                        else if (t.inArray(r.attr[o.type_attr], h) === -1) return ! 1;
                        if (o.max_children !== -2 && l !== -1) {
                            c = s === -1 ? this.get_container().find("> ul > li").length: s.find("> ul > li").length;
                            if (c + 1 > l) return ! 1
                        }
                        if (o.max_depth !== -2 && a !== -1 && a - 1 < 0) return ! 1
                    }
                    return this.__call_old(!0, n, i, r, u, f, e)
                }
            }
        })
    })(jQuery),
    (function(n) {
        n.jstree.plugin("html_data", {
            __init: function() {
                this.data.html_data.original_container_html = this.get_container().find(" > ul > li").clone(!0),
                this.data.html_data.original_container_html.find("li").andSelf().contents().filter(function() {
                    return this.nodeType == 3
                }).remove()
            },
            defaults: {
                data: !1,
                ajax: !1,
                correct_state: !0
            },
            _fn: {
                load_node: function(n, t, i) {
                    var r = this;
                    this.load_node_html(n,
                    function() {
                        r.__callback({
                            obj: r._get_node(n)
                        }),
                        t.call(this)
                    },
                    i)
                },
                _is_loaded: function(t) {
                    return t = this._get_node(t),
                    t == -1 || !t || !this._get_settings().html_data.ajax && !n.isFunction(this._get_settings().html_data.data) || t.is(".jstree-open, .jstree-leaf") || t.children("ul").children("li").size() > 0
                },
                load_node_html: function(t, i, r) {
                    var f, u = this.get_settings().html_data,
                    e = function() {},
                    o = function() {};
                    t = this._get_node(t);
                    if (t && t !== -1) {
                        if (t.data("jstree_is_loading")) return;
                        t.data("jstree_is_loading", !0)
                    }
                    switch (!0) {
                    case n.isFunction(u.data):
                        u.data.call(this, t, n.proxy(function(r) {
                            r && r !== "" && r.toString && r.toString().replace(/^[\s\n]+$/, "") !== "" ? (r = n(r), r.is("ul") || (r = n("<ul />").append(r)), t == -1 || !t ? this.get_container().children("ul").empty().append(r.children()).find("li, a").filter(function() {
                                return ! this.firstChild || !this.firstChild.tagName || this.firstChild.tagName !== "INS"
                            }).prepend("<ins class='jstree-icon'>&#160;</ins>").end().filter("a").children("ins:first-child").not(".jstree-icon").addClass("jstree-icon") : (t.children("a.jstree-loading").removeClass("jstree-loading"), t.append(r).children("ul").find("li, a").filter(function() {
                                return ! this.firstChild || !this.firstChild.tagName || this.firstChild.tagName !== "INS"
                            }).prepend("<ins class='jstree-icon'>&#160;</ins>").end().filter("a").children("ins:first-child").not(".jstree-icon").addClass("jstree-icon"), t.removeData("jstree_is_loading")), this.clean_node(t), i && i.call(this)) : t && t !== -1 ? (t.children("a.jstree-loading").removeClass("jstree-loading"), t.removeData("jstree_is_loading"), u.correct_state && (this.correct_state(t), i && i.call(this))) : u.correct_state && (this.get_container().children("ul").empty(), i && i.call(this))
                        },
                        this));
                        break;
                    case ! u.data && !u.ajax: (!t || t == -1) && (this.get_container().children("ul").empty().append(this.data.html_data.original_container_html).find("li, a").filter(function() {
                            return ! this.firstChild || !this.firstChild.tagName || this.firstChild.tagName !== "INS"
                        }).prepend("<ins class='jstree-icon'>&#160;</ins>").end().filter("a").children("ins:first-child").not(".jstree-icon").addClass("jstree-icon"), this.clean_node()),
                        i && i.call(this);
                        break;
                    case !! u.data && !u.ajax || !!u.data && !!u.ajax && (!t || t === -1) : (!t || t == -1) && (f = n(u.data), f.is("ul") || (f = n("<ul />").append(f)), this.get_container().children("ul").empty().append(f.children()).find("li, a").filter(function() {
                            return ! this.firstChild || !this.firstChild.tagName || this.firstChild.tagName !== "INS"
                        }).prepend("<ins class='jstree-icon'>&#160;</ins>").end().filter("a").children("ins:first-child").not(".jstree-icon").addClass("jstree-icon"), this.clean_node()),
                        i && i.call(this);
                        break;
                    case ! u.data && !!u.ajax || !!u.data && !!u.ajax && t && t !== -1 : t = this._get_node(t),
                        e = function(n, i, f) {
                            var e = this.get_settings().html_data.ajax.error;
                            e && e.call(this, n, i, f),
                            t != -1 && t.length ? (t.children("a.jstree-loading").removeClass("jstree-loading"), t.removeData("jstree_is_loading"), i === "success" && u.correct_state && this.correct_state(t)) : i === "success" && u.correct_state && this.get_container().children("ul").empty(),
                            r && r.call(this)
                        },
                        o = function(r, f, o) {
                            var s = this.get_settings().html_data.ajax.success;
                            s && (r = s.call(this, r, f, o) || r);
                            if (r === "" || r && r.toString && r.toString().replace(/^[\s\n]+$/, "") === "") return e.call(this, o, f, "");
                            r ? (r = n(r), r.is("ul") || (r = n("<ul />").append(r)), t == -1 || !t ? this.get_container().children("ul").empty().append(r.children()).find("li, a").filter(function() {
                                return ! this.firstChild || !this.firstChild.tagName || this.firstChild.tagName !== "INS"
                            }).prepend("<ins class='jstree-icon'>&#160;</ins>").end().filter("a").children("ins:first-child").not(".jstree-icon").addClass("jstree-icon") : (t.children("a.jstree-loading").removeClass("jstree-loading"), t.append(r).children("ul").find("li, a").filter(function() {
                                return ! this.firstChild || !this.firstChild.tagName || this.firstChild.tagName !== "INS"
                            }).prepend("<ins class='jstree-icon'>&#160;</ins>").end().filter("a").children("ins:first-child").not(".jstree-icon").addClass("jstree-icon"), t.removeData("jstree_is_loading")), this.clean_node(t), i && i.call(this)) : t && t !== -1 ? (t.children("a.jstree-loading").removeClass("jstree-loading"), t.removeData("jstree_is_loading"), u.correct_state && (this.correct_state(t), i && i.call(this))) : u.correct_state && (this.get_container().children("ul").empty(), i && i.call(this))
                        },
                        u.ajax.context = this,
                        u.ajax.error = e,
                        u.ajax.success = o,
                        u.ajax.dataType || (u.ajax.dataType = "html"),
                        n.isFunction(u.ajax.url) && (u.ajax.url = u.ajax.url.call(this, t)),
                        n.isFunction(u.ajax.data) && (u.ajax.data = u.ajax.data.call(this, t)),
                        n.ajax(u.ajax)
                    }
                }
            }
        }),
        n.jstree.defaults.plugins.push("html_data")
    })(jQuery),
    (function(n) {
        n.jstree.plugin("themeroller", {
            __init: function() {
                var t = this._get_settings().themeroller;
                this.get_container().addClass("ui-widget-content").addClass("jstree-themeroller").delegate("a", "mouseenter.jstree",
                function(i) {
                    n(i.currentTarget).hasClass("jstree-loading") || n(this).addClass(t.item_h)
                }).delegate("a", "mouseleave.jstree",
                function() {
                    n(this).removeClass(t.item_h)
                }).bind("init.jstree", n.proxy(function(n, t) {
                    t.inst.get_container().find("> ul > li > .jstree-loading > ins").addClass("ui-icon-refresh"),
                    this._themeroller(t.inst.get_container().find("> ul > li"))
                },
                this)).bind("open_node.jstree create_node.jstree", n.proxy(function(n, t) {
                    this._themeroller(t.rslt.obj)
                },
                this)).bind("loaded.jstree refresh.jstree", n.proxy(function() {
                    this._themeroller()
                },
                this)).bind("close_node.jstree", n.proxy(function(n, t) {
                    this._themeroller(t.rslt.obj)
                },
                this)).bind("delete_node.jstree", n.proxy(function(n, t) {
                    this._themeroller(t.rslt.parent)
                },
                this)).bind("correct_state.jstree", n.proxy(function(n, i) {
                    i.rslt.obj.children("ins.jstree-icon").removeClass(t.opened + " " + t.closed + " ui-icon").end().find("> a > ins.ui-icon").filter(function() {
                        return this.className.toString().replace(t.item_clsd, "").replace(t.item_open, "").replace(t.item_leaf, "").indexOf("ui-icon-") === -1
                    }).removeClass(t.item_open + " " + t.item_clsd).addClass(t.item_leaf || "jstree-no-icon")
                },
                this)).bind("select_node.jstree", n.proxy(function(n, i) {
                    i.rslt.obj.children("a").addClass(t.item_a)
                },
                this)).bind("deselect_node.jstree deselect_all.jstree", n.proxy(function() {
                    this.get_container().find("a." + t.item_a).removeClass(t.item_a).end().find("a.jstree-clicked").addClass(t.item_a)
                },
                this)).bind("dehover_node.jstree", n.proxy(function(n, i) {
                    i.rslt.obj.children("a").removeClass(t.item_h)
                },
                this)).bind("hover_node.jstree", n.proxy(function(n, i) {
                    this.get_container().find("a." + t.item_h).not(i.rslt.obj).removeClass(t.item_h),
                    i.rslt.obj.children("a").addClass(t.item_h)
                },
                this)).bind("move_node.jstree", n.proxy(function(n, t) {
                    this._themeroller(t.rslt.o),
                    this._themeroller(t.rslt.op)
                },
                this))
            },
            __destroy: function() {
                var i = this._get_settings().themeroller,
                t = ["ui-icon"];
                n.each(i,
                function(n, i) {
                    i = i.split(" "),
                    i.length && (t = t.concat(i))
                }),
                this.get_container().removeClass("ui-widget-content").find("." + t.join(", .")).removeClass(t.join(" "))
            },
            _fn: {
                _themeroller: function(n) {
                    var t = this._get_settings().themeroller;
                    n = !n || n == -1 ? this.get_container_ul() : this._get_node(n).parent(),
                    n.find("li.jstree-closed").children("ins.jstree-icon").removeClass(t.opened).addClass("ui-icon " + t.closed).end().children("a").addClass(t.item).children("ins.jstree-icon").addClass("ui-icon").filter(function() {
                        return this.className.toString().replace(t.item_clsd, "").replace(t.item_open, "").replace(t.item_leaf, "").indexOf("ui-icon-") === -1
                    }).removeClass(t.item_leaf + " " + t.item_open).addClass(t.item_clsd || "jstree-no-icon").end().end().end().end().find("li.jstree-open").children("ins.jstree-icon").removeClass(t.closed).addClass("ui-icon " + t.opened).end().children("a").addClass(t.item).children("ins.jstree-icon").addClass("ui-icon").filter(function() {
                        return this.className.toString().replace(t.item_clsd, "").replace(t.item_open, "").replace(t.item_leaf, "").indexOf("ui-icon-") === -1
                    }).removeClass(t.item_leaf + " " + t.item_clsd).addClass(t.item_open || "jstree-no-icon").end().end().end().end().find("li.jstree-leaf").children("ins.jstree-icon").removeClass(t.closed + " ui-icon " + t.opened).end().children("a").addClass(t.item).children("ins.jstree-icon").addClass("ui-icon").filter(function() {
                        return this.className.toString().replace(t.item_clsd, "").replace(t.item_open, "").replace(t.item_leaf, "").indexOf("ui-icon-") === -1
                    }).removeClass(t.item_clsd + " " + t.item_open).addClass(t.item_leaf || "jstree-no-icon")
                }
            },
            defaults: {
                opened: "ui-icon-triangle-1-se",
                closed: "ui-icon-triangle-1-e",
                item: "ui-state-default",
                item_h: "ui-state-hover",
                item_a: "ui-state-active",
                item_open: "ui-icon-folder-open",
                item_clsd: "ui-icon-folder-collapsed",
                item_leaf: "ui-icon-document"
            }
        }),
        n(function() {
            var t = ".jstree-themeroller .ui-icon { overflow:visible; } .jstree-themeroller a { padding:0 2px; } .jstree-themeroller .jstree-no-icon { display:none; }";
            n.vakata.css.add_sheet({
                str: t,
                title: "jstree"
            })
        })
    })(jQuery),
    (function(n) {
        n.jstree.plugin("unique", {
            __init: function() {
                this.get_container().bind("before.jstree", n.proxy(function(t, i) {
                    var u = [],
                    f = !0,
                    r,
                    e;
                    return i.func == "move_node" && i.args[4] === !0 && i.args[0].o && i.args[0].o.length && (i.args[0].o.children("a").each(function() {
                        u.push(n(this).text().replace(/^\s+/g, ""))
                    }), f = this._check_unique(u, i.args[0].np.find("> ul > li").not(i.args[0].o), "move_node")),
                    i.func == "create_node" && (i.args[4] || this._is_loaded(i.args[0])) && (r = this._get_node(i.args[0]), i.args[1] && (i.args[1] === "before" || i.args[1] === "after") && (r = this._get_parent(i.args[0]), (!r || r === -1) && (r = this.get_container())), typeof i.args[2] == "string" ? u.push(i.args[2]) : !i.args[2] || !i.args[2].data ? u.push(this._get_string("new_node")) : u.push(i.args[2].data), f = this._check_unique(u, r.find("> ul > li"), "create_node")),
                    i.func == "rename_node" && (u.push(i.args[1]), e = this._get_node(i.args[0]), r = this._get_parent(e), (!r || r === -1) && (r = this.get_container()), f = this._check_unique(u, r.find("> ul > li").not(e), "rename_node")),
                    f ? void 0 : (t.stopPropagation(), !1)
                },
                this))
            },
            defaults: {
                error_callback: n.noop
            },
            _fn: {
                _check_unique: function(t, i, r) {
                    var u = [];
                    i.children("a").each(function() {
                        u.push(n(this).text().replace(/^\s+/g, ""))
                    });
                    if (!u.length || !t.length) return ! 0;
                    return u = u.sort().join(",,").replace(/(,|^)([^,]+)(,,\2)+(,|$)/g, "$1$2$4").replace(/,,+/g, ",").replace(/,$/, "").split(","),
                    u.length + t.length != u.concat(t).sort().join(",,").replace(/(,|^)([^,]+)(,,\2)+(,|$)/g, "$1$2$4").replace(/,,+/g, ",").replace(/,$/, "").split(",").length ? (this._get_settings().unique.error_callback.call(null, t, i, r), !1) : !0
                },
                check_move: function() {
                    if (!this.__call_old()) return ! 1;
                    var t = this._get_move(),
                    i = [];
                    return t.o && t.o.length ? (t.o.children("a").each(function() {
                        i.push(n(this).text().replace(/^\s+/g, ""))
                    }), this._check_unique(i, t.np.find("> ul > li").not(t.o), "check_move")) : !0
                }
            }
        })
    })(jQuery),
    (function(r) {
        r.jstree.plugin("wholerow", {
            __init: function() {
                if (!this.data.ui) throw "jsTree wholerow: jsTree UI plugin not included.";
                this.data.wholerow.html = !1,
                this.data.wholerow.to = !1,
                this.get_container().bind("init.jstree", r.proxy(function() {
                    this._get_settings().core.animation = 0
                },
                this)).bind("open_node.jstree create_node.jstree clean_node.jstree loaded.jstree", r.proxy(function(n, t) {
                    this._prepare_wholerow_span(t && t.rslt && t.rslt.obj ? t.rslt.obj: -1)
                },
                this)).bind("search.jstree clear_search.jstree reopen.jstree after_open.jstree after_close.jstree create_node.jstree delete_node.jstree clean_node.jstree", r.proxy(function(n, t) {
                    this.data.to && clearTimeout(this.data.to),
                    this.data.to = setTimeout(function(n, t) {
                        return function() {
                            n._prepare_wholerow_ul(t)
                        }
                    } (this, t && t.rslt && t.rslt.obj ? t.rslt.obj: -1), 0)
                },
                this)).bind("deselect_all.jstree", r.proxy(function() {
                    this.get_container().find(" > .jstree-wholerow .jstree-clicked").removeClass("jstree-clicked " + (this.data.themeroller ? this._get_settings().themeroller.item_a: ""))
                },
                this)).bind("select_node.jstree deselect_node.jstree ", r.proxy(function(n, t) {
                    t.rslt.obj.each(function() {
                        var n = t.inst.get_container().find(" > .jstree-wholerow li:visible:eq(" + parseInt((r(this).offset().top - t.inst.get_container().offset().top + t.inst.get_container()[0].scrollTop) / t.inst.data.core.li_height, 10) + ")");
                        n.children("a").attr("class", t.rslt.obj.children("a").attr("class"))
                    })
                },
                this)).bind("hover_node.jstree dehover_node.jstree", r.proxy(function(n, t) {
                    this.get_container().find(" > .jstree-wholerow .jstree-hovered").removeClass("jstree-hovered " + (this.data.themeroller ? this._get_settings().themeroller.item_h: ""));
                    if (n.type === "hover_node") {
                        var i = this.get_container().find(" > .jstree-wholerow li:visible:eq(" + parseInt((t.rslt.obj.offset().top - this.get_container().offset().top + this.get_container()[0].scrollTop) / this.data.core.li_height, 10) + ")");
                        i.children("a").attr("class", t.rslt.obj.children(".jstree-hovered").attr("class"))
                    }
                },
                this)).delegate(".jstree-wholerow-span, ins.jstree-icon, li", "click.jstree",
                function(n) {
                    var t = r(n.currentTarget);
                    if (n.target.tagName === "A" || n.target.tagName === "INS" && t.closest("li").is(".jstree-open, .jstree-closed")) return;
                    t.closest("li").children("a:visible:eq(0)").click(),
                    n.stopImmediatePropagation()
                }).delegate("li", "mouseover.jstree", r.proxy(function(n) {
                    return n.stopImmediatePropagation(),
                    r(n.currentTarget).children(".jstree-hovered, .jstree-clicked").length ? !1 : (this.hover_node(n.currentTarget), !1)
                },
                this)).delegate("li", "mouseleave.jstree", r.proxy(function(n) {
                    if (r(n.currentTarget).children("a").hasClass("jstree-hovered").length) return;
                    this.dehover_node(n.currentTarget)
                },
                this)),
                (t || n) && r.vakata.css.add_sheet({
                    str: ".jstree-" + this.get_index() + " { position:relative; } ",
                    title: "jstree"
                })
            },
            defaults: {},
            __destroy: function() {
                this.get_container().children(".jstree-wholerow").remove(),
                this.get_container().find(".jstree-wholerow-span").remove()
            },
            _fn: {
                _prepare_wholerow_span: function(n) {
                    n = !n || n == -1 ? this.get_container().find("> ul > li") : this._get_node(n);
                    if (n === !1) return;
                    n.each(function() {
                        r(this).find("li").andSelf().each(function() {
                            var n = r(this);
                            if (n.children(".jstree-wholerow-span").length) return ! 0;
                            n.prepend("<span class='jstree-wholerow-span' style='width:" + n.parentsUntil(".jstree", "li").length * 18 + "px;'>&#160;</span>")
                        })
                    })
                },
                _prepare_wholerow_ul: function() {
                    var n = this.get_container().children("ul").eq(0),
                    i = n.html();
                    n.addClass("jstree-wholerow-real"),
                    this.data.wholerow.last_html !== i && (this.data.wholerow.last_html = i, this.get_container().children(".jstree-wholerow").remove(), this.get_container().append(n.clone().removeClass("jstree-wholerow-real").wrapAll("<div class='jstree-wholerow' />").parent().width(n.parent()[0].scrollWidth).css("top", (n.height() + (t ? 5 : 0)) * -1).find("li[id]").each(function() {
                        this.removeAttribute("id")
                    }).end()))
                }
            }
        }),
        r(function() {
            var u = ".jstree .jstree-wholerow-real { position:relative; z-index:1; } .jstree .jstree-wholerow-real li { cursor:pointer; } .jstree .jstree-wholerow-real a { border-left-color:transparent !important; border-right-color:transparent !important; } .jstree .jstree-wholerow { position:relative; z-index:0; height:0; } .jstree .jstree-wholerow ul, .jstree .jstree-wholerow li { width:100%; } .jstree .jstree-wholerow, .jstree .jstree-wholerow ul, .jstree .jstree-wholerow li, .jstree .jstree-wholerow a { margin:0 !important; padding:0 !important; } .jstree .jstree-wholerow, .jstree .jstree-wholerow ul, .jstree .jstree-wholerow li { background:transparent !important; }.jstree .jstree-wholerow ins, .jstree .jstree-wholerow span, .jstree .jstree-wholerow input { display:none !important; }.jstree .jstree-wholerow a, .jstree .jstree-wholerow a:hover { text-indent:-9999px; !important; width:100%; padding:0 !important; border-right-width:0px !important; border-left-width:0px !important; } .jstree .jstree-wholerow-span { position:absolute; left:0; margin:0px; padding:0; height:18px; border-width:0; padding:0; z-index:0; }";
            i && (u += ".jstree .jstree-wholerow a { display:block; height:18px; margin:0; padding:0; border:0; } .jstree .jstree-wholerow-real a { border-color:transparent !important; } "),
            (t || n) && (u += ".jstree .jstree-wholerow, .jstree .jstree-wholerow li, .jstree .jstree-wholerow ul, .jstree .jstree-wholerow a { margin:0; padding:0; line-height:18px; } .jstree .jstree-wholerow a { display:block; height:18px; line-height:18px; overflow:hidden; } "),
            r.vakata.css.add_sheet({
                str: u,
                title: "jstree"
            })
        })
    })(jQuery),
    (function(n) {
        var i = ["getChildren", "getChildrenCount", "getAttr", "getName", "getProps"],
        t = function(t, i) {
            var r = !0;
            return t = t || {},
            i = [].concat(i),
            n.each(i,
            function(i, u) {
                if (!n.isFunction(t[u])) return r = !1,
                !1
            }),
            r
        };
        n.jstree.plugin("model", {
            __init: function() {
                if (!this.data.json_data) throw "jsTree model: jsTree json_data plugin not included.";
                this._get_settings().json_data.data = function(r, u) {
                    var f = r == -1 ? this._get_settings().model.object: r.data("jstree_model");
                    if (!t(f, i)) return u.call(null, !1);
                    this._get_settings().model.async ? f.getChildren(n.proxy(function(n) {
                        this.model_done(n, u)
                    },
                    this)) : this.model_done(f.getChildren(), u)
                }
            },
            defaults: {
                object: !1,
                id_prefix: !1,
                async: !1
            },
            _fn: {
                model_done: function(t, i) {
                    var u = [],
                    r = this._get_settings(),
                    f = this;
                    n.isArray(t) || (t = [t]),
                    n.each(t,
                    function(t, i) {
                        var e = i.getProps() || {};
                        e.attr = i.getAttr() || {},
                        i.getChildrenCount() && (e.state = "closed"),
                        e.data = i.getName(),
                        n.isArray(e.data) || (e.data = [e.data]),
                        f.data.types && n.isFunction(i.getType) && (e.attr[r.types.type_attr] = i.getType()),
                        e.attr.id && r.model.id_prefix && (e.attr.id = r.model.id_prefix + e.attr.id),
                        e.metadata || (e.metadata = {}),
                        e.metadata.jstree_model = i,
                        u.push(e)
                    }),
                    i.call(null, u)
                }
            }
        })
    })(jQuery)
})(),
_filemanage.cons = {},
_filemanage.view = 1,
_filemanage.disp = 0,
_filemanage.asc = 1,
_filemanage.detailper = [53, 15, 20, 17],
_filemanage.onmousemove = null,
_filemanage.onmouseup = null,
_filemanage.onselectstart = 1,
_filemanage.Arrange = function(n, t, i) {
    var r = _filemanage.cons[n];
    r.view = parseInt(t),
    r.showIcos(i),
    jQuery("#right_contextmenu .menu-icon-iconview").each(function() {
        this.src = jQuery(this).attr("view") == t ? IMGPATH+"icons/select.png": IMGPATH+"icons/notselect.png"
    })
},
_filemanage.Disp = function(n, t, i) {
    var u = document.getElementById("searchInput_" + i).value,
    r;
    u == _lang.fsearch && (u = "");
    if (!_window.windows[i].filemanageid) return;
    r = _filemanage.cons[n],
    r.disp = parseInt(t),
    r.showIcos(i, u),
    jQuery("#right_contextmenu .menu-icon-disp").each(function() {
        this.src = jQuery(this).attr("disp") == t ? IMGPATH+"icons/select.png": IMGPATH+"icons/notselect.png"
    }),
    jQuery("#right_contextmenu .menu-icon-disp").each(function() {
        this.src = jQuery(this).attr("disp") == t ? IMGPATH+"icons/select.png": IMGPATH+"icons/notselect.png"
    })
},
_filemanage.searchsubmit = function(n) {
    var i = document.getElementById("searchInput_" + n).value,
    t;
    i == _lang.fsearch && (i = "");
    if (!_window.windows[n].filemanageid) return;
    t = _filemanage.cons[_window.windows[n].filemanageid];
    if (!t) return;
    t.showIcos(n, i)
},
_filemanage.prototype.delIcos = function(n) {
    var u = this,
    f, r, t, i;
    this.asc = this.asc ? 1 : 0,
    containerid = "content_" + this.winid,
    this.view < 4 ? (f = jQuery("#icosContainer_" + containerid + "_" + this.id + " >.file-icoitem[icoid=" + n.icoid + "]"), f.remove()) : (r = jQuery("#detail_" + containerid + "_" + this.id + " .detail_tr[icoid=" + n.icoid + "]"), r.remove());
    if (n.type == "folder") {
        _config.sendConfig();
        for (t in _filemanage.cons) if (t.indexOf("f-" + n.oid) == 0) {
            _filemanage.cons[t].winid == "_W_sys_filemanage" ? (jQuery("#content__W_sys_filemanage").empty(), _window.windows._W_sys_filemanage.filemanageid = null, jQuery(_window.windows._W_sys_filemanage.titleCase).find(".operator").hide()) : _window.windows[_filemanage.cons[t].winid].Close();
            for (i in _filemanage.cons[t]) delete _filemanage.cons[t][i];
            delete _filemanage.cons[t]
        }
        jQuery("#jstree_area").jstree("correct_state", jQuery("#" + u.fid)),
        jQuery("#jstree_area").jstree("refresh", jQuery("#" + u.fid))
    }
    delete this.data[n.icoid]
},
_filemanage.prototype.reCreateIcos = function(n, t) {
    var e = this,
    h, s, f, i, u, r, o;
    this.asc = this.asc ? 1 : 0,
    containerid = "content_" + this.winid,
    this.data[n.icoid] = n,
    this.view < 4 ? (h = get_ico_template(this.view, n.type), s = n.img.substr(n.img.length - 4) == ".png" ? !0 : !1, u = h.replace(/\{name\}/g, n.name), u = u.replace(/\{icoid\}/g, n.icoid), u = u.replace(/\{ispng\}/g, s), u = u.replace(/\{img\}/g, n.img), t ? (f = jQuery("#icosContainer_" + containerid + "_" + this.id + ' .file-icoitem[icoid="' + t.icoid + '"]'), f.attr("icoid", n.icoid), delete this.data[t.icoid], f.unbind(), f.bind("contextmenu",
    function(n) {
        return _contextmenu.right_ico(n ? n: window.event, jQuery(this).attr("icoid"), e.id),
        !1
    }), f.each(function() {
        _config.Permission("drag", e.container, jQuery(this).attr("icoid")) && _Drag.init(jQuery(this).attr("icoid"), this, e.id, e.container)
    }), f.bind("mouseover",
    function() {
        jQuery(this).addClass("border_background"),
        this.style.background = _ico._defaultbgcolor
    }), f.bind("mouseout",
    function() {
        return jQuery(this).removeClass("border_background"),
        this.style.background = "",
        !1
    }), f.bind("click",
    function(t) {
        var i = t.srcElement ? t.srcElement: t.target;
        return i.type == "text" || i.type == "textarea" ? !0 : (_config.Permission("open", "", n.icoid) && (n.type == "folder" ? e.winid == "_W_sys_filemanage" ? jQuery("#jstree_area").jstree("select_node", jQuery("#f-" + n.oid + "-" + _config.uid), !0) : OpenFolderWin(n.icoid) : _ico.Open(n.icoid, n)), !1)
    })) : f = jQuery("#icosContainer_" + containerid + "_" + this.id + ' .file-icoitem[icoid="' + n.icoid + '"]'), f.html(u)) : (i = "", i += '<td class="detail_item_td "  valign="middle" width="' + this.detailper[0] + '%" style="overflow:hidden">', i += '<div class="detail_item_td_div detail_item_td_name" icoid="{icoid}">', i += '<img class="detail_item_name_icon" src="{img}">', i += '<span id="file_text_{icoid}" class="detail_text detail_item_name_text">{name}</span>', i += "</div>", i += "</td>", i += '<td class="detail_item_td detail_item_td_size" valign="middle" width="' + this.detailper[1] + '%" style="overflow:hidden">', i += '<div class="detail_item_td_div">', i += '<div class="detail_text detail_item_size_text">{size}</div>', i += "</div>", i += "</td>", i += '<td class="detail_item_td detail_item_td_type" valign="middle" width="' + this.detailper[2] + '%" style="overflow:hidden">', i += '<div class="detail_item_td_div">', i += '<div class="detail_text detail_item_size_type">{type}</div>', i += "</div>", i += "</td>", i += '<td class="detail_item_td detail_item_td_type" valign="middle" width="' + this.detailper[3] + '%" style="overflow:hidden">', i += '<div class="detail_item_td_div">', i += '<div class="detail_text detail_item_size_type">{dateline}</div>', i += "</div>", i += "</td>", u = "", r = i.replace(/\{name\}/g, n.name), r = r.replace(/\{icoid\}/g, n.icoid), r = r.replace(/\{img\}/g, n.img), r = r.replace(/\{size\}/g, n.fsize), r = r.replace(/\{type\}/g, n.ftype), r = r.replace(/\{dateline\}/g, n.fdateline), u += r, t ? (o = jQuery("#icosContainer_" + containerid + "_" + this.id + ' .detail_tr[icoid="' + t.icoid + '"]'), o.attr("icoid", n.icoid), delete this.data[t.icoid]) : o = jQuery("#icosContainer_" + containerid + "_" + this.id + ' .detail_tr[icoid="' + n.icoid + '"]'), o.html(u), o.find(".detail_item_td_name").each(function() {
        _config.Permission("drag", e.container, jQuery(this).attr("icoid")) && _Drag.init(jQuery(this).attr("icoid"), this, e.id, e.container)
    }), o.find(".detail_item_td_name").bind("contextmenu",
    function(n) {
        return _contextmenu.right_ico(n ? n: window.event, jQuery(this).attr("icoid"), e.id),
        !1
    }), o.find(".detail_item_td_name").bind("click",
    function(t) {
        var i = t.srcElement ? t.srcElement: t.target;
        if (i.type == "text" || i.type == "textarea") return ! 0;
        _config.Permission("open", "", n.icoid) && (n.type == "folder" ? winid == "_W_sys_filemanage" ? jQuery("#jstree_area").jstree("select_node", jQuery("#f-" + n.oid + "-" + _config.uid), !0) : OpenFolderWin(n.icoid) : _ico.Open(n.icoid, n))
    })),
    n.type == "folder" && (_config.sendConfig(), jQuery("#jstree_area").jstree("refresh", jQuery("#" + this.fid)))
},
_filemanage.prototype.uploader_CreateIcos = function(n) {
    var c = this,
    o, e, u, t, r, i, f;
    this.asc = this.asc ? 1 : 0,
    containerid = "content_" + this.winid,
    this.data[n.icoid] = n;
    if (this.view < 4) o = get_ico_template(this.view, n.type),
    e = n.img.substr(n.img.length - 4) == ".png" ? !0 : !1,
    r = o.replace(/\{name\}/g, n.name),
    r = r.replace(/\{icoid\}/g, n.icoid),
    r = r.replace(/\{ispng\}/g, e),
    r = r.replace(/\{img\}/g, n.img),
    u = jQuery('<div icoid="' + n.icoid + '" class="file-icoitem">' + r + "</div>").appendTo("#icosContainer_" + containerid + "_" + this.id),
    jQuery("#file_text_" + n.icoid).addClass("text_div_upload").css("height", 35),
    f = '<div class="upload_text">' + n.name + "</div>",
    f += '<div id="upload_progress_' + n.icoid + '" class="upload_text_back"></div>',
    jQuery("#file_text_" + n.icoid).html(f),
    u.bind("mouseover",
    function() {
        jQuery(this).addClass("border_background"),
        this.style.background = _ico._defaultbgcolor
    }),
    u.bind("mouseout",
    function() {
        return jQuery(this).removeClass("border_background"),
        this.style.background = "",
        !1
    });
    else {
        t = "",
        t += '<tr class="detail_tr"  icoid="{icoid}">',
        t += '<td class="detail_item_td "  valign="middle" width="' + this.detailper[0] + '%" style="overflow:hidden">',
        t += '<div class="detail_item_td_div detail_item_td_name" icoid="{icoid}">',
        t += '<img class="detail_item_name_icon" src="{img}">',
        t += '<span id="file_text_{icoid}" class="detail_text detail_item_name_text">{name}</span>',
        t += "</div>",
        t += "</td>",
        t += '<td class="detail_item_td detail_item_td_size" valign="middle" width="' + this.detailper[1] + '%" style="overflow:hidden">',
        t += '<div class="detail_item_td_div">',
        t += '<div class="detail_text detail_item_size_text">{size}</div>',
        t += "</div>",
        t += "</td>",
        t += '<td class="detail_item_td detail_item_td_type" valign="middle" width="' + this.detailper[2] + '%" style="overflow:hidden">',
        t += '<div class="detail_item_td_div">',
        t += '<div class="detail_text detail_item_size_type">{type}</div>',
        t += "</div>",
        t += "</td>",
        t += '<td class="detail_item_td detail_item_td_type" valign="middle" width="' + this.detailper[3] + '%" style="overflow:hidden">',
        t += '<div class="detail_item_td_div">',
        t += '<div class="detail_text detail_item_size_type">{dateline}</div>',
        t += "</div>",
        t += "</td>",
        t += "</tr>",
        r = "",
        i = t.replace(/\{name\}/g, n.name),
        i = i.replace(/\{icoid\}/g, n.icoid),
        i = i.replace(/\{img\}/g, n.img),
        i = i.replace(/\{size\}/g, n.fsize),
        i = i.replace(/\{type\}/g, n.ftype),
        i = i.replace(/\{dateline\}/g, n.fdateline),
        r += i;
        var h = jQuery(r).appendTo("#detail_" + containerid + "_" + this.id + " table"),
        u = h.find(".detail_item_td_name"),
        s = u.find("img").css({
            display: "line-block",
            "float": "left",
            "padding-top": (u.height() - u.find("img").height()) / 2
        });
        jQuery("#file_text_" + n.icoid).addClass("text_div_upload_detail").css({
            width: u.innerWidth() - s.width() - 2,
            height: u.innerHeight() - 2,
            "margin-left": "0px"
        }),
        f = '<div class="upload_text" >' + n.name + "</div>",
        f += '<div id="upload_progress_' + n.icoid + '" class="upload_text_back"></div>',
        jQuery("#file_text_" + n.icoid).html(f)
    }
},
_filemanage.prototype.CreateIcos = function(n) {
    var u = this,
    s, o, f, t, r, i, e;
    this.asc = this.asc ? 1 : 0,
    containerid = "content_" + this.winid,
    this.data[n.icoid] = n,
    this.view < 4 ? (s = get_ico_template(this.view, n.type), o = n.img.substr(n.img.length - 4) == ".png" ? !0 : !1, r = s.replace(/\{name\}/g, n.name), r = r.replace(/\{icoid\}/g, n.icoid), r = r.replace(/\{ispng\}/g, o), r = r.replace(/\{img\}/g, n.img), f = jQuery('<div icoid="' + n.icoid + '" class="file-icoitem">' + r + "</div>").appendTo("#icosContainer_" + containerid + "_" + this.id), f.bind("contextmenu",
    function(n) {
        return _contextmenu.right_ico(n ? n: window.event, jQuery(this).attr("icoid"), u.id),
        !1
    }), f.each(function() {
        _config.Permission("drag", u.container, jQuery(this).attr("icoid")) && _Drag.init(jQuery(this).attr("icoid"), this, u.id, u.container)
    }), f.bind("mouseover",
    function() {
        jQuery(this).addClass("border_background"),
        this.style.background = _ico._defaultbgcolor
    }), f.bind("mouseout",
    function() {
        return jQuery(this).removeClass("border_background"),
        this.style.background = "",
        !1
    }), f.bind("click",
    function(t) {
        var i = t.srcElement ? t.srcElement: t.target;
        return i.type == "text" || i.type == "textarea" ? !0 : (n.type == "folder" ? u.winid == "_W_sys_filemanage" ? jQuery("#jstree_area").jstree("select_node", jQuery("#f-" + n.oid + "-" + _config.uid), !0) : OpenFolderWin(n.icoid) : _ico.Open(n.icoid, n), !1)
    })) : (t = "", t += '<tr class="detail_tr"  icoid="{icoid}">', t += '<td class="detail_item_td "  valign="middle" width="' + this.detailper[0] + '%" style="overflow:hidden">', t += '<div class="detail_item_td_div detail_item_td_name" icoid="{icoid}">', t += '<img class="detail_item_name_icon" src="{img}">', t += '<span id="file_text_{icoid}" class="detail_text detail_item_name_text">{name}</span>', t += "</div>", t += "</td>", t += '<td class="detail_item_td detail_item_td_size" valign="middle" width="' + this.detailper[1] + '%" style="overflow:hidden">', t += '<div class="detail_item_td_div">', t += '<div class="detail_text detail_item_size_text">{size}</div>', t += "</div>", t += "</td>", t += '<td class="detail_item_td detail_item_td_type" valign="middle" width="' + this.detailper[2] + '%" style="overflow:hidden">', t += '<div class="detail_item_td_div">', t += '<div class="detail_text detail_item_size_type">{type}</div>', t += "</div>", t += "</td>", t += '<td class="detail_item_td detail_item_td_type" valign="middle" width="' + this.detailper[3] + '%" style="overflow:hidden">', t += '<div class="detail_item_td_div">', t += '<div class="detail_text detail_item_size_type">{dateline}</div>', t += "</div>", t += "</td>", t += "</tr>", r = "", i = t.replace(/\{name\}/g, n.name), i = i.replace(/\{icoid\}/g, n.icoid), i = i.replace(/\{img\}/g, n.img), i = i.replace(/\{size\}/g, n.fsize), i = i.replace(/\{type\}/g, n.ftype), i = i.replace(/\{dateline\}/g, n.fdateline), r += i, e = jQuery(r).appendTo("#detail_" + containerid + "_" + this.id + " table"), e.find(".detail_item_td_name").bind("contextmenu",
    function(n) {
        return _contextmenu.right_ico(n ? n: window.event, jQuery(this).attr("icoid"), u.id),
        !1
    }), e.find(".detail_item_td_name").each(function() {
        _config.Permission("drag", u.container, jQuery(this).attr("icoid")) && _Drag.init(jQuery(this).attr("icoid"), this, u.id, u.container)
    }), e.find(".detail_item_td_name").bind("click",
    function(t) {
        var i = t.srcElement ? t.srcElement: t.target;
        if (i.type == "text" || i.type == "textarea") return ! 0;
        n.type == "folder" && _config.Permission("open", "", n.icoid) ? u.winid == "_W_sys_filemanage" ? jQuery("#jstree_area").jstree("select_node", jQuery("#f-" + n.oid + "-" + _config.uid), !0) : OpenFolderWin(n.icoid) : _ico.Open(n.icoid, n)
    })),
    n.type == "folder" && (_config.sendConfig(), jQuery("#jstree_area").jstree("correct_state", jQuery("#" + u.fid)), jQuery("#jstree_area").jstree("refresh", jQuery("#" + this.fid)))
},
_filemanage.prototype.showIcos = function(n, t) {
    var i = this,
    v, c, o, u, y, w, r, a, f, b, h, e, s, p, l;
    this.asc = this.asc ? 1 : 0,
    _window.windows[this.winid].filemanageid = this.id,
    containerid = "content_" + this.winid,
    v = this.id.split("-"),
    v[0] == "f" || v[0] == "d" ? _config.Permission("newfolder", this.container) ? jQuery(_window.windows[this.winid].titleCase).find(".operator").show() : jQuery(_window.windows[this.winid].titleCase).find(".operator").hide() : jQuery(_window.windows[this.winid].titleCase).find(".operator").hide(),
    jQuery("#" + containerid).empty(),
    c = document.createElement("div"),
    c.className = "icosContainer",
    c.style.height = "100%",
    c.id = "icosContainer_" + containerid + "_" + this.id,
    jQuery(c).bind("contextmenu",
    function(t) {
        return _contextmenu.right_folder(t ? t: window.event, n),
        !1
    }),
    document.getElementById(containerid).appendChild(c),
    _config.Permission("upload", this.container) && (_config.uploader[this.winid] = new qq.FileUploaderBasic({
        action: DZZSCRIPT + "?mod=system&op=dzzcp&do=upload",
        params: {
            container: i.container,
            ukey: _config.ukey,
            uid: _config.uid
        },
        allowedExtensions: _config.space.attachextensions,
        sizeLimit: isNaN(parseInt(_config.space.maxattachsize)) ? 0 : parseInt(_config.space.maxattachsize),
        minSizeLimit: 0,
        button: document.getElementById("button_UPLOAD" + i.winid),
        debug: !1,
        onSubmit: function(n, t) {
            i.odata["upload_" + i.winid + "_" + n] = {
                icoid: "upload_" + i.winid + "_" + n,
                name: t,
                img: IMGPATH+"extimg/unknow.png"
            },
            i.uploader_CreateIcos(i.odata["upload_" + i.winid + "_" + n])
        },
        onProgress: function(n, t, r, u) {
            jQuery("#upload_progress_upload_" + i.winid + "_" + n).css("width", Math.floor(r / u * 100) + "%")
        },
        onComplete: function(n, t, r) {
            jQuery("#upload_progress_upload_" + i.winid + "_" + n).css("width", "100%");
            if (r.success) _ico.createIco(r, i.odata["upload_" + i.winid + "_" + n]);
            else {
                delete i.odata["upload_" + i.winid + "_" + n],
                delete i.data["upload_" + i.winid + "_" + n];
                var u = jQuery('<div class="upload_failure_close"></div>').appendTo("#file_text_upload_" + i.winid + "_" + n);
                u.bind("click",
                function() {
                    jQuery(c).find(".file-icoitem[icoid=upload_" + i.winid + "_" + n + "]").remove(),
                    jQuery(c).find(".detail_tr[icoid=upload_" + i.winid + "_" + n + "]").remove()
                }),
                jQuery("#upload_progress_upload_" + i.winid + "_" + n).css("background", "#f9d5de"),
                jQuery("#file_text_upload_" + i.winid + "_" + n).css("border", "1px solid #f5bccd"),
                jQuery("#file_text_upload_" + i.winid + "_" + n).attr("title", r.error)
            }
        },
        showMessage: function(n) {
            Alert(n)
        }
    }), jQuery.browser.msie || (_config.Droper[this.winid] = new qq.UploadDropZone({
        element: c,
        onEnter: function(n) {
            qq.stopPropagation(n)
        },
        onLeave: function(n) {
            qq.stopPropagation(n)
        },
        onLeaveNotDescendants: function() {},
        onDrop: function(n) {
            var t = n.dataTransfer.getData("text/plain");
            t ? ajaxget(_config.systemurl + "&op=dzzcp&do=newlink&container=" + i.container + "&handlekey=handle_add_newlink&uid=" + _config.uid + "&ukey=" + _config.ukey + "&link=" + t) : _config.uploader[i.winid]._uploadFileList(n.dataTransfer.files)
        }
    }))),
    o = jQuery(c),
    u = Sort(this.data, this.disp, this.asc),
    t && t != _lang.fsearch ? u = Search(u, t) : document.getElementById("searchInput_" + n).value = _lang.fsearch;
    if (this.view < 4) {
        for (e in u) y = u[e].img.substr(u[e].img.length - 4) == ".png" ? !0 : !1,
        w = get_ico_template(this.view, u[e].type),
        h = w.replace(/\{name\}/g, u[e].name),
        h = h.replace(/\{icoid\}/g, u[e].icoid),
        h = h.replace(/\{ispng\}/g, y),
        h = h.replace(/\{img\}/g, u[e].img),
        l = jQuery("<div></div>").appendTo(o),
        l.attr("icoid", u[e].icoid).addClass("file-icoitem").html(h),
        l.bind("contextmenu",
        function(n) {
            return _contextmenu.right_ico(n ? n: window.event, jQuery(this).attr("icoid"), i.id),
            !1
        }),
        _config.Permission("drag", this.container, u[e].icoid) && _Drag.init(u[e].icoid, l.get(0), i.id, i.container),
        l.bind("mouseover",
        function() {
            return jQuery(this).addClass("border_background"),
            this.style.background = _ico._defaultbgcolor,
            !1
        }),
        l.bind("mouseout",
        function() {
            return jQuery(this).removeClass("border_background"),
            this.style.background = "",
            !1
        }),
        l.bind("click",
        function(t) {
            var r, i;
            t = t ? t: window.event,
            r = t.srcElement ? t.srcElement: t.target;
            if (r.type == "text" || r.type == "textarea") return ! 0;
            i = jQuery(this).attr("icoid"),
            _config.Permission("open", "", i) && (u["icos_" + i].type == "folder" ? n == "_W_sys_filemanage" ? jQuery("#jstree_area").jstree("select_node", jQuery("#f-" + u["icos_" + i].oid + "-" + _config.uid), !0) : OpenFolderWin(u["icos_" + i].icoid) : _ico.Open(i, u["icos_" + i]))
        });
        o.css("overflow", "auto")
    } else {
        r = "",
        r += '<table width="100%" border="0"   style="table-layout:fixed;overflow:hidden">',
        r += '<tr class="detail_header_tr">',
        r += '<td disp="0" class=" detail_header detail_header_0 ' + (this.disp == 0 ? "detail_header_hover": "") + '" width="' + this.detailper[0] + '%">',
        r += '<div class="detail_header_td_div">',
        r += '<span class="detail_header_title">' + _lang.detail.name + "</span>",
        r += '<div disp="0"  class="detail_header_drag" ></div>',
        this.disp == 0 && (r += '<a class="detail_header_asc detail_header_asc_' + this.asc + '" style="display:inline-block;" ></a>'),
        r += "</div>",
        r += "</td>",
        r += '<td disp="1" class=" detail_header detail_header_1 ' + (this.disp == 1 ? "detail_header_hover": "") + '"  width="' + this.detailper[1] + '%">',
        r += '<div class="detail_header_td_div">',
        r += '<span class="detail_header_title">' + _lang.detail.size + "</span>",
        this.disp == 1 && (r += '<a class="detail_header_asc detail_header_asc_' + this.asc + '" style="display:inline-block;" ></a>'),
        r += '<div disp="1"  class="detail_header_drag" ></div>',
        r += "</div>",
        r += "</td>",
        r += '<td disp="2" class=" detail_header detail_header_2 ' + (this.disp == 2 ? "detail_header_hover": "") + '"  width="' + this.detailper[2] + '%">',
        r += '<div class="detail_header_td_div">',
        r += '<span class="detail_header_title">' + _lang.detail.type + "</span>",
        this.disp == 2 && (r += '<a class="detail_header_asc detail_header_asc_' + this.asc + '" style="display:inline-block;" ></a>'),
        r += '<div disp="2"  class="detail_header_drag" style="float:right" ></div>',
        r += "</div>",
        r += "</td>",
        r += '<td disp="3" class=" detail_header detail_header_3 ' + (this.disp == 3 ? "detail_header_hover": "") + '"  width="' + this.detailper[3] + '%">',
        r += '<div class="detail_header_td_div">',
        r += '<span class="detail_header_title">' + _lang.detail.dateline + "</span>",
        this.disp == 3 && (r += '<a class="detail_header_asc detail_header_asc_' + this.asc + '" style="display:inline-block;" ></a>'),
        r += "</div>",
        r += "</td>",
        r += "</tr>",
        r += "</table>",
        a = "",
        a += '<table width="100%" border="0"   style="table-layout:fixed;overflow:hidden">',
        f = "",
        f += '<tr class="detail_tr" icoid="{icoid}">',
        f += '<td class="detail_item_td "  valign="middle" width="' + this.detailper[0] + '%" style="overflow:hidden">',
        f += '<div class="detail_item_td_div detail_item_td_name" icoid="{icoid}">',
        f += '<img class="detail_item_name_icon" src="{img}">',
        f += '<span id="file_text_{icoid}" class="detail_text detail_item_name_text">{name}</span>',
        f += "</div>",
        f += "</td>",
        f += '<td class="detail_item_td detail_item_td_size" valign="middle" width="' + this.detailper[1] + '%" style="overflow:hidden">',
        f += '<div class="detail_item_td_div">',
        f += '<div class="detail_text detail_item_size_text">{size}</div>',
        f += "</div>",
        f += "</td>",
        f += '<td class="detail_item_td detail_item_td_type" valign="middle" width="' + this.detailper[2] + '%" style="overflow:hidden">',
        f += '<div class="detail_item_td_div">',
        f += '<div class="detail_text detail_item_size_type">{type}</div>',
        f += "</div>",
        f += "</td>",
        f += '<td class="detail_item_td detail_item_td_type" valign="middle" width="' + this.detailper[3] + '%" style="overflow:hidden">',
        f += '<div class="detail_item_td_div">',
        f += '<div class="detail_text detail_item_size_type">{dateline}</div>',
        f += "</div>",
        f += "</td>",
        f += "</tr>",
        b = "</table>",
        h = "";
        for (e in u) s = f.replace(/\{name\}/g, u[e].name),
        s = s.replace(/\{icoid\}/g, u[e].icoid),
        s = s.replace(/\{img\}/g, u[e].img),
        s = s.replace(/\{size\}/g, u[e].fsize),
        s = s.replace(/\{type\}/g, u[e].ftype),
        s = s.replace(/\{dateline\}/g, u[e].fdateline),
        h += s;
        p = jQuery('<div class="filemanage_header ">' + r + "</div>").appendTo(o),
        l = jQuery('<div id="detail_' + containerid + "_" + this.id + '" style="height:' + (_window.windows[this.winid].bodyHeight - p.height()) + 'px;overflow-x:hidden;overflow-y:auto">' + a + h + b + "</div>").appendTo(o),
        o.css("overflow", "hidden"),
        o.find(".detail_item_td_name").bind("contextmenu",
        function(n) {
            return _contextmenu.right_ico(n ? n: window.event, jQuery(this).attr("icoid"), i.id),
            !1
        }),
        o.find(".detail_item_td_name").each(function() {
            _config.Permission("drag", i.container, jQuery(this).attr("icoid")) && _Drag.init(jQuery(this).attr("icoid"), this, i.id, i.container)
        }),
        o.find(".detail_tr").bind("mouseover",
        function() {
            return jQuery(this).addClass("detail_tr_hover"),
            !1
        }),
        o.find(".detail_tr").bind("mouseout",
        function() {
            return jQuery(this).removeClass("detail_tr_hover"),
            !1
        }),
        o.find(".detail_item_td_name").bind("click",
        function(n) {
            var r, t;
            n = n ? n: window.event,
            r = n.srcElement ? n.srcElement: n.target;
            if (r.type == "text" || r.type == "textarea") return ! 0;
            t = jQuery(this).attr("icoid"),
            _config.Permission("open", "", t) && (u["icos_" + t].type == "folder" ? i.winid == "_W_sys_filemanage" ? jQuery("#jstree_area").jstree("select_node", jQuery("#f-" + u["icos_" + t].oid + "-" + _config.uid), !0) : OpenFolderWin(u["icos_" + t].icoid) : _ico.Open(t, u["icos_" + t]))
        }),
        o.find(".detail_header").bind("click",
        function() {
            var t = parseInt(jQuery(this).attr("disp"));
            i.asc = t == i.disp ? i.asc ? 0 : 1 : 1,
            i.disp = t,
            i.showIcos(i.winid, document.getElementById("searchInput_" + n).value)
        })
    }
    jQuery(_window.windows[this.winid].titleCase).find(".arrange").each(function() {
        jQuery(this).removeClass(jQuery(this).attr("index") + "2").addClass(jQuery(this).attr("index") + "1")
    }),
    jQuery("#" + this.winid + "_view" + this.view).removeClass(jQuery("#" + this.winid + "_view" + this.view).attr("index") + "1").addClass(jQuery("#" + this.winid + "_view" + this.view).attr("index") + "2")
},
_filemanage.prototype.tddrager_start = function(n) {
    this.XX = n.clientX,
    document.getElementById("_blank").style.cursor = "e-resize",
    jQuery("#_blank").show();
    var t = this;
    this.AttachEvent(n),
    eval("document.onmousemove=function(e){" + this.string + ".tddraging(e?e:window.event);};"),
    eval("document.onmouseup=function(e){" + this.string + ".tddraged(e?e:window.event);};")
},
_filemanage.prototype.tddraging = function() {
    document.body.style.cursor = "e-resize"
},
_filemanage.prototype.tddraged = function(n) {
    var o, f, e, t, r, s, h, u, i;
    for (this.DetachEvent(n), jQuery("#_blank").hide(), document.getElementById("_blank").style.cursor = "url('dzz/images/cur/aero_arrow.cur'),auto", document.body.style.cursor = "url('dzz/images/cur/aero_arrow.cur'),auto", o = n.clientX - this.XX, f = _window.windows[this.winid].bodyWidth - jQuery("#jstree_area").width(), current_width = f * this.detailper[this.tddrager_disp] / 100, e = o + current_width, e < 50 && (e = 50), t = [], i = 0; i < 4; i++) t[i] = f * this.detailper[i] / 100;
    r = e - current_width;
    if (o > 0) {
        for (t[this.tddrager_disp + 1] - r > 50 ? t[this.tddrager_disp + 1] -= r: (s = r + (t[this.tddrager_disp + 1] - 50), t[this.tddrager_disp + 1] = 50, this.tddrager_disp + 1 + 1 < 4 && (t[this.tddrager_disp + 1 + 1] - s > 50 ? t[this.tddrager_disp + 1 + 1] -= r: (h = s + (t[this.tddrager_disp + 1 + 1] - 50), t[this.tddrager_disp + 1 + 1] = 50, this.tddrager_disp + 1 + 1 + 1 < 4 && (t[this.tddrager_disp + 1 + 1 + 1] - h > 50 ? t[this.tddrager_disp + 1 + 1 + 1] -= r: t[this.tddrager_disp + 1 + 1 + 1] = 50)))), u = 0, i = 0; i < 4; i++) i != this.tddrager_disp && (u += t[i]);
        t[this.tddrager_disp] = f - u
    } else t[this.tddrager_disp] = e,
    t[this.tddrager_disp + 1] -= r;
    for (u = 0, i = 0; i < 4; i++) i != this.tddrager_disp && (u += t[i]);
    for (t[this.tddrager_disp] = f - u, i = 0; i < 4; i++) this.detailper[i] = Math.floor(t[i] / f * 100);
    this.showIcos(this.winid)
},
_filemanage.prototype.DetachEvent = function() {
    document.body.style.cursor = "url('dzz/images/cur/aero_arrow.cur'),auto",
    document.onmousemove = _filemanage.onmousemove,
    document.onmouseup = _filemanage.onmouseup,
    document.onselectstart = _filemanage.onselectstart,
    alert("detach")
},
_filemanage.prototype.AttachEvent = function(n) {
    _filemanage.onmousemove = document.onmousemove,
    _filemanage.onmouseup = document.onmouseup,
    _filemanage.onselectstart = document.onselectstart,
    n.preventDefault ? n.preventDefault() : document.onselectstart = function() {
        return ! 1
    }
}