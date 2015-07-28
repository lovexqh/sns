function checkInDom(n, t) {
    return n ? n.id == t ? !0 : n.tagName == "BODY" ? !1 : checkInDom(n.parentNode, t) : !1
}
function contains(n, t) {
    return n.contains ? n != t && n.contains(t) : !!(n.compareDocumentPosition(t) & 16)
}
function checkHover(n, t) {
    return getEvent(n).type == "mouseover" ? !contains(t, getEvent(n).relatedTarget || getEvent(n).fromElement) && !((getEvent(n).relatedTarget || getEvent(n).fromElement) === t) : !contains(t, getEvent(n).relatedTarget || getEvent(n).toElement) && !((getEvent(n).relatedTarget || getEvent(n).toElement) === t)
}
function array_merge() {
    for (var f = Array.prototype.slice.call(arguments), s = f.length, t, e = {},
    i = "", h = 0, u = 0, n = 0, o = 0, c = Object.prototype.toString, r = !0, n = 0; n < s; n++) if (c.call(f[n]) !== "[object Array]") {
        r = !1;
        break
    }
    if (r) {
        for (r = [], n = 0; n < s; n++) r = r.concat(f[n]);
        return r
    }
    for (n = 0, o = 0; n < s; n++) {
        t = f[n];
        if (c.call(t) === "[object Array]") for (u = 0, h = t.length; u < h; u++) e[o++] = t[u];
        else for (i in t) t.hasOwnProperty(i) && (parseInt(i, 10) + "" === i ? e[o++] = t[i] : e[i] = t[i])
    }
    return e
}
function serialize(n) {
    var h = function(n) {
        for (var i = 0,
        t = 0,
        u = n.length,
        r = "",
        t = 0; t < u; t++) r = n.charCodeAt(t),
        i += r < 128 ? 1 : r < 2048 ? 2 : 3;
        return i
    },
    e = function(n) {
        var r = typeof n,
        u, f, t, i;
        if (r === "object" && !n) return "null";
        if (r === "object") {
            if (!n.constructor) return "object";
            t = n.constructor.toString(),
            u = t.match(/(\w+)\(/),
            u && (t = u[1].toLowerCase()),
            i = ["boolean", "number", "string", "array"];
            for (f in i) if (t == i[f]) {
                r = i[f];
                break
            }
        }
        return r
    },
    f = e(n),
    t,
    s = "",
    r,
    u,
    o,
    i;
    switch (f) {
    case "function":
        t = "";
        break;
    case "boolean":
        t = "b:" + (n ? "1": "0");
        break;
    case "number":
        t = (Math.round(n) == n ? "i": "d") + ":" + n;
        break;
    case "string":
        t = "s:" + h(n) + ':"' + n + '"';
        break;
    case "array":
    case "object":
        t = "a",
        r = 0,
        u = "";
        for (i in n) if (n.hasOwnProperty(i)) {
            s = e(n[i]);
            if (s === "function") continue;
            o = i.match(/^[0-9]+$/) ? parseInt(i, 10) : i,
            u += this.serialize(o) + this.serialize(n[i]),
            r++
        }
        t += ":" + r + ":{" + u + "}";
        break;
    case "undefined":
    default:
        t = "N"
    }
    return f !== "object" && f !== "array" && (t += ";"),
    t
}
function mb_cutstr_nohtml(n, t, i) {
    var f = 0,
    u = "",
    r;
    i || (i = "");
    if (!n) return;
    for (n = n.replace(/(\<[^\<]*\>|\r|\n|\s|\[.+?\])/ig, " "), t = t - i.length, r = 0; r < n.length; r++) {
        f += n.charCodeAt(r) < 0 || n.charCodeAt(r) > 255 ? 3 : 1;
        if (f > t) {
            u += i;
            break
        }
        u += n.substr(r, 1)
    }
    return u
}
function GetIeVersion() {
    var n = new RegExp("MSIE ([^;]*);", "i");
    return n.test(navigator.appVersion) ? parseInt(RegExp.$1) : 0
}
function OpenBrowser(n, t, i) {
    features = i ? i: windows.sys_browser.features;
    var r = null,
    u = 0;
    _window.windows._W_sys_browser && (r = _window.windows._W_sys_browser),
    r ? (r.Focus(), n && r.SetBrowserContent(n, t)) : (windows.sys_browser.object = _window.OpenBrowser(n, t, features), _task.Ctask("sys_browser"))
}
function OpenFileManage(n) {
    features = n ? n: windows.sys_filemanage.features;
    var r = null,
    u = 0;
    _window.windows._W_sys_filemanage && (r = _window.windows._W_sys_filemanage),
    r ? r.Focus() : windows.sys_filemanage.object = _window.OpenFileManage(windows.sys_filemanage.title, features)
}
function OpenFolderWin(n, t) {
    var f = _config.sourcedata.icos[n],
    i,
    u,
    e,
    r;
    if (!f) return;
    features = t ? t: windows.Folder.features,
    i = null,
    u = _ico.getTopFid(f.oid);
    for (e in _window.windows) {
        r = _window.windows[e];
        if (r.type == "folder" && r.topfid[r.topfid.length - 1] == u[u.length - 1]) {
            i = r;
            break
        }
    }
    i ? i.fid * 1 == f.oid * 1 ? i.Focus() : (i.Csequence < i.Sequence.length && i.Sequence.splice(i.Csequence, i.Sequence.length - i.Csequence), i.Csequence = i.Sequence.push(n), _task.resetTask(i.taskid, n), i.icoid = n, i.fid = f.oid, i.taskid = n, i.topfid = u, i.SetFolderTitle(f.name), i.SetFolderContent(n)) : (_window.OpenFolderWin(n, u, features), _task.Ctask(n))
}
function OpenAppWin(n, t, i, r, u, f) {
    var e = _config.sourcedata.icos[n],
    h,
    s,
    o;
    f || (f = _config.currentDesktop);
    if (!e) return;
    features = t ? t: windows.App.features,
    h = 0,
    s = 0,
    e.wwidth * 1 > 0 && (h = e.wwidth * 1 > _config.screenWidth ? _config.screenWidth: e.wwidth * 1),
    e.wheight * 1 > 0 && (s = e.wheight * 1 > _config.screenHeight - 60 ? _config.screenHeight - 60 : e.wheight * 1),
    h > 0 && (features = features.replace("width=" + _window.getFeature(features, "width"), "width=" + h)),
    features = features.replace("titlebutton=" + _window.getFeature(features, "titlebutton"), "titlebutton=" + e.titlebuttons.replace(/,/g, "|")),
    typeof r != "undefined" && (features = features.replace("left=" + _window.getFeature(features, "left"), ""), features += ",left=" + r),
    typeof u != "undefined" && (features = features.replace("top=" + _window.getFeature(features, "top"), ""), features += ",top=" + u),
    s > 0 && (features = features.replace("height=" + _window.getFeature(features, "height"), "height=" + s)),
    _window.windows["_W_" + n] ? (o = _window.windows["_W_" + n], o.Focus()) : (o = _window.OpenAppWin(f, n, features), _config.setWindowToSave(o)),
    e.havetask > 0 && _task.Ctask(n),
    getcookie("view_" + e.type + "_" + e.oid) || jQuery.get(_config.dataurl + "&do=updateview&id=" + e.oid + "&type=" + e.type)
}
function OpenPicWin(n, t) {
    var i = _config.sourcedata.icos[n];
    if (i.type != "image") {
        _ico.Open(n);
        return
    }
    features = t ? t: windows.sys_pic.features,
    _window.windows._W_sys_pic ? (_window.windows._W_sys_pic.SetPicTitle(n, i.url, i.name), _window.windows._W_sys_pic.SetPicContent(n, i.url, "REFRESH")) : (_window.OpenPicWin(n, features), _task.Ctask("sys_pic")),
    getcookie("view_" + i.type + "_" + i.oid) || jQuery.get(_config.dataurl + "&do=updateview&id=" + i.oid + "&type=" + i.type)
}
function OpenWindow(n, t, i, r, u) {
	//去掉域名后的地址 孙晓波2013-7-6
	if(typeof(t)!='undefined' && t.indexOf(document.domain)!=-1){
		//添加如果有端口操作，同时把端口也去掉的处理   RickerYU 2013-7-18
		var portlen = 0;
		if(window.location.port != ''){
			portlen = window.location.port.length + 1;
		}
		t = t.substring(t.indexOf(document.domain)+document.domain.length + portlen);
	}		

    var e, s, l, f, a, v, h, o, c;
    t ? (e = "[url]" + t, s = i == "" ? windows.url.title: i, n = "url") : (e = "[id]" + n, s = windows[n].title),
    l = u ? u: windows[n].features,
    f = null,
    o = 0,
    n == "url" ? (a = encodeURIComponent(e).replace(/\./g, "_").replace(/%/g, "_"), _window.windows["_W_" + a] && (f = _window.windows["_W_" + a])) : _window.windows["_W_" + n] && (f = _window.windows["_W_" + n]),
    f && f.Focus();
    if (n == "sys_deskconfig") {
        v = "";
        for (h in _navbar.navbars) _navbar.navbars[h]._blank < 1 && (v += '<label><input id="defaultDesktop_' + h + '" name="defaultDesktop" value="' + h + '" type="radio" />' + _navbar.navbars[h].name + "</label>");
        jQuery("#defaultDesktopRadioSet").html(v)
    }
    o = 0;
    if (f) switch (f.status) {
    case 0:
        f.Show(),
        o = t ? 1 : 2;
        break;
    case 1:
        f.Focus(),
        o = t ? 1 : 2
    }
    switch (o) {
    case 0:
        switch (windows[n].type) {
        case 0:
            f = _window.Open(n, e, s, l, r);
            break;
        case 1:
            f = _window.Alert(e, s, l);
            break;
        case 2:
            f = _window.Confirm(e, s, l)
        }
        f.OnCANCEL = f.Close;
        switch (n) {
        case "sys_deskconfig":
            f.OnOK = function() {
                for (var t, n = 0; n < f.form.defaultDesktop.length; n++) if (f.form.defaultDesktop[n].checked) break;
                t = f.form.defaultDesktop[n].value,
                _config.defaultDesktop != t && (_config.defaultDesktop = t, _config.saveItem.current = 1, _config.saveTimer && window.clearTimeout(_config.saveTimer), _config.saveTimer = setTimeout(function() {
                    _config.sendConfig()
                },
                _config.savetime)),
                f.Close()
            };
            break;
        case "sys_restore":
            f.OnOK = function() {
                var n = [];
                f.form.restoreAppList.checked && (n[n.length] = "applist"),
                f.form.restoreTheme.checked && (n[n.length] = "thame"),
                f.form.restoreDesktopsettingBtn.checked && (n[n.length] = "defaultDesktop"),
                n.length > 0 && jQuery.get(_config.dataurl + "&do=restore&type=" + n.join("_") + "&t=" + new Date,
                function() {
                    window.location.reload()
                }),
                f.Close()
            }
        }
        break;
    case 1:
        f.SetContent(e),
        f.SetTitle(s)
    }
    if (n == "sys_deskconfig") for (c = 0; c < f.form.defaultDesktop.length; c++) if (f.form.defaultDesktop[c].value == _config.defaultDesktop) {
        f.form.defaultDesktop[c].checked = !0;
        break
    }
    n != "url" && (windows[n].object = f),
    in_array(n, ["sys_addApp", "sys_theme", "sys_market", "sys_widget"]) && _task.Ctask(n)
}
/*
 * 打开个人空间页面
 * 孙晓波 添加于 2013-6-6
 * h:要打开的链接 t:标题
 */
function OpenSpaceWin(h, t, i){
	features = i ? i: 'width=1024,height=600,titlebutton=close|max|min';
	//OpenWindow('url','__ROOT__/@{$mid}','个人主页','','titlebutton=close|max|min,width=980,height=600');return false;
	OpenBrowser(h,t,features);
}

/**
 * 重新设置桌面边距及宽度
 * 孙晓波 添加于 2013-6-6
 * w 要减去增加的宽度 a 为增加删除的类型 west:删除 east:增加
 */
function reset_desktop(w,a){
	var left,width;
	//获取当前桌面的宽度
	jQuery("div[id^='_body_']").each(function(i, e) {
		if(typeof(jQuery(this).offset().left)!='undefined' && jQuery(this).offset().left == 0){
			left = a == 'west' ? jQuery(this).find('.icosContainer').offset().left-w : jQuery(this).find('.icosContainer').offset().left+w;
			width = a == 'west' ? jQuery(this).find('.icosContainer').width()+w : jQuery(this).find('.icosContainer').width()-w;
		}
	});
	//动态设置桌面宽度	
	jQuery("div[id^='_body_']").each(function(i, e) {
		if(typeof(left)!='undefined'){
			//jQuery(this).find('.icosContainer').animate({left:left+"px"});
			jQuery(this).find('.icosContainer').css({'left':left});
		}
		if(typeof(width)!='undefined'){
			jQuery(this).find('.icosContainer').width(width);
		}
	});
}

function Alert(n, t, i) {
    var r = _window.OpenMsgWin("alert", windows.Alert.title, n, windows.Alert.features);
    r.OnOK = function() {
        r.closeTimer && window.clearTimeout(r.closeTimer),
        r.Close(),
        typeof i == "function" && i()
    },
    t > 0 && (r.closeTimer = window.setTimeout(function() {
        r.Close(),
        typeof i == "function" && i()
    },
    t)),
    jQuery(r.titleCase).find(".CLOSE").unbind().bind("click",
    function() {
        r.Close(),
        typeof i == "function" && i()
    })
}
function Confirm(n, t) {
    var i = _window.OpenMsgWin("confirm", windows.confirm.title, n, windows.confirm.features);
    i.OnOK = function() {
        i.Close(),
        typeof t == "function" && t()
    },
    i.OnCANCEL = function() {
        i.Close()
    },
    jQuery(i.titleCase).find(".CLOSE").unbind().bind("click",
    function() {
        i.Close()
    }),
    _config.Confirm = "waiting"
}
document.oncontextmenu = function(n) {
    n = n ? n: window.event;
    var t = n.srcElement ? n.srcElement: n.target;
    return t.type == "text" || t.type == "textarea" ? !0 : !1
},
document.onselectstart = function(n) {
    n = n ? n: window.event;
    var t = n.srcElement ? n.srcElement: n.target;
    return t.type == "text" || t.type == "textarea" ? !0 : !1
},
_config.setMouseDownHide = function(n) {
    jQuery(document).bind("mousedown." + n,
    function(t) {
        t = t ? t: window.event;
        var i = t.srcElement ? t.srcElement: t.target;
        checkInDom(i, n) == !1 && (jQuery("#" + n).hide(), jQuery(document).unbind("mousedown." + n))
    })
},
window.onresize = function() {
    _window.clientWidth = _config.screenWidth = document.documentElement.clientWidth,
    _window.clientHeight = _config.screenHeight = document.documentElement.clientHeight,
    _config.desktopHeight = _config.screenHeight - _config.dockHeight,
    _ico.resizeDesktops()
},
fixPNG = function(n, t) {
    var i = n.find("img");
    i.each(function() {
        png = this.src,
        this.src = t,
        this.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + png + "',sizingMethod='scale')"
    })
},
fixpng = function(n) {
    if (ieVersion > 0 && ieVersion < 7) {
        var t = n.src;
        n.onload = null,
        t.substr(t.lastIndexOf(".")).toLowerCase() == ".png" && (n.src = "dzz/images/b.gif", n.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + t + "',sizingMethod='scale')")
    }
};
var ieVersion = GetIeVersion(),
windows = [];
windows.sys_addApp = {
    object: null,
    type: 0,
    title: _lang.WinTitle.addApp,
    features: "width=500,height=400,titlebutton=close",
    x: 170,
    y: 50
},
windows.sys_browser = {
    object: null,
    type: 0,
    title: _lang.WinTitle.browser,
    features: "width=800,height=500,titlebutton=close|max|min",
    x: 170,
    y: 50
},
windows.sys_market = {
    object: null,
    type: 0,
    title: _lang.WinTitle.market,
    features: "width=900,height=500,titlebutton=close|max|min",
    x: 170,
    y: 50
},
windows.sys_theme = {
    object: null,
    type: 0,
    title: _lang.WinTitle.theme,
    features: "width=800,height=500,titlebutton=close|max|min",
    x: 170,
    y: 50
},
windows.sys_widget = {
    object: null,
    type: 0,
    title: _lang.WinTitle.widget,
    features: "width=800,height=500,titlebutton=close|max|min",
    x: 170,
    y: 50
},
windows.ControlPanel = {
    object: null,
    type: 0,
    title: _lang.WinTitle.ControlPanel,
    features: "resize=no,titlebutton=close,width=345,height=-1",
    x: 335,
    y: parseInt(Math.random() * 300)
},
windows.sys_hotkey = {
    object: null,
    type: 0,
    title: _lang.WinTitle.hotkey,
    features: "resize=no,titlebutton=close,width=380,height=-1",
    x: 335,
    y: parseInt(Math.random() * 300)
},
windows.sys_restore = {
    object: null,
    type: 0,
    title: _lang.WinTitle.restore,
    features: "resize=no,width=380,height=-1,button=OK|CANCEL,titlebutton=close",
    x: 335,
    y: parseInt(Math.random() * 300)
},
windows.sys_deskconfig = {
    object: null,
    type: 0,
    title: _lang.WinTitle.deskconfig,
    features: "resize=no,width=380,height=-1,button=OK|CANCEL,titlebutton=close",
    x: 335,
    y: parseInt(Math.random() * 300)
},
windows.Alert = {
    object: null,
    type: 1,
    title: _lang.WinTitle.message,
    features: "resize=no,titlebutton=close,width=400,height=-1,button=OK,isModal=yes"
},
windows.confirm = {
    object: null,
    type: 2,
    title: _lang.WinTitle.message,
    features: "resize=no,titlebutton=close,width=400,height=-1,button=OK|CANCEL,isModal=yes"
},
windows.sys_login = {
    object: null,
    title: _lang.WinTitle.login,
    features: "class=sys_login,titlebutton=close,width=400,height=300",
    x: 335,
    y: 200
},
windows.App = {
    object: null,
    title: "",
    features: "titlebutton=home|refresh|detail|close|max|min,width=800,height=500",
    width: 800,
    height: 500
},
windows.url = {
    object: null,
    type: 0,
    title: _lang.browser,
    features: "titlebutton=close|max|min,width=800,height=500",
    width: 800,
    height: 500
},
windows.sys_filemanage = {
    object: null,
    title: _lang.WinTitle.filemanage,
    features: "titlebutton=close|max|min,width=800,height=500",
    width: 800,
    height: 500
},
windows.OpenFile = {
    object: null,
    title: "",
    features: "class=window_jd,titlebutton=close,width=500,height=400,button=OK|CANCEL,isModal=yes",
    width: 500,
    height: 400
},
windows.sys_pic = {
    object: null,
    title: _lang.WinTitle.pic,
    features: "titlebutton=home|refresh|detail|score|comment|report|close|max|min,width=800,height=500",
    width: 800,
    height: 500
},
windows.Folder = {
    object: null,
    title: "",
    features: "titlebutton=close|max|min,width=800,height=500",
    width: 800,
    height: 500
}
//url解密函数
function url_encode(str, is_binary) {
	var result = "";
	var i = 0;
	var x;
	var shiftreg = 0;
	var count = -1;
	for (i = 0; i < str.length; i++) {
		c = str.charAt(i);
		if ('A' <= c && c <= 'Z')
			x = str.charCodeAt(i) - 65;
		else if ('a' <= c && c <= 'z')
			x = str.charCodeAt(i) - 97 + 26;
		else if ('0' <= c && c <= '9')
			x = str.charCodeAt(i) - 48 + 52;
		else if (c == '+')
			x = 62;
		else if (c == '/')
			x = 63;
		else
			continue;
		count++;
		switch (count % 4) {
		case 0:
			shiftreg = x;
			continue;
		case 1:
			v = (shiftreg << 2) | (x >> 4);
			shiftreg = x & 0x0F;
			break;
		case 2:
			v = (shiftreg << 4) | (x >> 2);
			shiftreg = x & 0x03;
			break;
		case 3:
			v = (shiftreg << 6) | (x >> 0);
			shiftreg = x & 0x00;
			break
		}
		if (!is_binary && (v < 32 || v > 126) && (v != 0x0d) && (v != 0x0a)) {
			result = result + "<";
			result = result + "0123456789ABCDEF".charAt((v / 16) & 0x0F);
			result = result + "0123456789ABCDEF".charAt((v / 1) & 0x0F);
			result = result + ">"
		} else
			result = result + String.fromCharCode(v)
	}
	return result.toString()
}