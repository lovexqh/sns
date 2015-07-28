_config = function(n) {
	_config.iconpositions_u = n.iconpositions_u || {},
	_config.iconpositions_0 = n.iconpositions_0 || {},
	_config.dockList = n.docklist || [],
	_config.screenList = n.screenlist || [],
	_config.navids = n.navids || {},
	_config.sourceids = n.sourceids || [],
	_config.sourcedata = n.sourcedata || [],
	_config.formhash = n.formhash,
	_config.thame = n.thame || {},
	_config.dockTaskList = [],
	_config.iconview = n.iconview || {},
	_config.sitename = n.sysconfig.sitename,
	_config.marketurl = n.sysconfig.marketurl,
	_config.widgeturl = n.sysconfig.widgeturl,
	_config.systhameurl = n.sysconfig.systhameurl,
	_config.sysaddappurl = n.sysconfig.addappurl,
	_config.sysbrowserurl = n.sysconfig.sysbrowserurl || "about:blank",
	_config.dataurl = n.sysconfig.dataurl,
	_config.loginurl = n.sysconfig.loginurl,
	_config.logouturl = n.sysconfig.logouturl,
	_config.ucenterurl = n.sysconfig.ucenterurl,
	_config.spacename = n.spacename || "",
	_config.friend = n.friend * 1 || 0,
	_config.ukey = n.ukey,
	_config.space = n.space,
	_config.defaultDesktop = n.currentDesktop,
	_config.currentDesktop = _config.defaultDesktop || 1
},
_config.txtexts = ["WEBDOC", "HTM", "HTML", "SHTM", "SHTML", "HTA", "HTC", "XHTML", "STM", "SSI", "JS", "JSON", "AS", "ASC", "ASR", "XML", "XSL", "XSD", "DTD", "XSLT", "RSS", "RDF", "LBI", "DWT", "ASP", "ASA", "ASPX", "ASCX", "ASMX", "CONFIG", "CS", "CSS", "CFM", "CFML", "CFC", "TLD", "TXT", "PHP", "PHP3", "PHP4", "PHP5", "PHP-DIST", "PHTML", "JSP", "WML", "TPL", "LASSO", "JSF", "VB", "VBS", "VTM", "VTML", "INC", "SQL", "JAVA", "EDML", "MASTER", "INFO", "INSTALL", "THEME", "CONFIG", "MODULE", "PROFILE", "ENGINE"],
_config.jqxhr = {},
_config.uploader = {},
_config.Droper = {},
_config.taskOpen = {},
_config.taskWidget = {},
_config.navbar_moving = 0,
_config.jqxhr.dzzsuccess = !0,
_config.jqxhr.retrynum = 0,
_config.saveItem = {},
_config.saveItem.folder = [],
_config.savetime = 1e4,
_config.saveTimer = null,
_config.delay = 500,
_config.titleBars = {},
_config.folderList = [],
_config.sendDataTo = function(n, t) {
	window.frames[n] && window.frames[n].window.acceptdata(t)
},
_config.titleButtons = ["HOME", "EDIT", "REFRESH", "DETAIL", "CLOSE", "MAX", "RESTORE", "MIN"],
_config.getConfig = function(n, t, i) {
	//console.log(n + "&t=" + +new Date);
	jQuery.getJSON(n + "&t=" + +new Date,
	function(n) {
		_config(n),
		_config.setOpenOldWin(),
		_widget.setOldWidget(),
		_config.initthame(),
		_login.Clogin(),
		t && in_array(t, _config.navids) && (_config.currentDesktop = t),
		_navbar.init(),
		_ico.CIcolist(),
		i && _ico.Open(i)
	})
},
_config.Permission = function(n, t, i) {
	var u;
	if (_config.space.self == 2) return ! 0;
	switch (n) {
	case "edit":
	case "rename":
	case "delete":
		return _config.space.self < 1 ? !1 : _config.myuid && _config.sourcedata.icos[i].uid == _config.myuid && t.indexOf("icosContainer_body_sys_") === -1 ? !0 : !1;
	case "download":
		if (_config.sourcedata.icos[i].friend == 2) {
			if (_config.space.self < 1) return ! 1
		} else if (_config.sourcedata.icos[i].friend == 1) if (_config.space.isfriend < 1 && _config.space.self < 1) return ! 1;
		return ! 0;
	case "open":
		if (_config.sourcedata.icos[i].friend == 2) {
			if (_config.space.self < 1) return ! 1
		} else if (_config.sourcedata.icos[i].friend == 1) if (_config.space.isfriend < 1 && _config.space.self < 1) return ! 1;
		return ! 0;
	case "newfolder":
		if (_config.space.self < 1) return ! 1;
		if (t.replace("icosContainer_body_", "").indexOf("sys_") !== -1) return ! 1;
		if (t.indexOf("icosContainer_folder_") !== -1) {
			u = t.replace("icosContainer_folder_", "");
			if (_config.myuid > 0 && _config.myuid != _config.sourcedata.folder[u].uid) return ! 1
		}
		return _config.space.allownewfolder < 1 ? !1 : !0;
	case "newlink":
		if (_config.space.self < 1) return ! 1;
		if (t.indexOf("icosContainer_body_sys_") !== -1) return ! 1;
		if (t.indexOf("icosContainer_folder_") !== -1) {
			u = t.replace("icosContainer_folder_", "");
			if (_config.myuid && _config.sourcedata.folder[u] && _config.myuid != _config.sourcedata.folder[u].uid) return ! 1
		}
		return _config.space.allownewlink && _config.space.allownewlink < 1 ? !1 : !0;
	case "upload":
		if (_config.space.self < 1) return ! 1;
		if (t.indexOf("icosContainer_body_sys_") !== -1) return ! 1;
		if (t.indexOf("icosContainer_folder_") !== -1) {
			u = t.replace("icosContainer_folder_", "");
			if (_config.myuid && _config.sourcedata.folder[u] && _config.myuid != _config.sourcedata.folder[u].uid) return ! 1
		}
		return _config.space.allowupload && _config.space.allowupload < 1 ? !1 : !0;
	case "widget_create":
		return _config.space.self < 1 ? !1 : t.indexOf("icosContainer_body_sys_") !== -1 ? !1 : _config.space.allowwidget < 1 ? !1 : !0;
	case "widget_OP":
		return _config.space.self < 1 ? !1 : t.indexOf("icosContainer_body_sys_") !== -1 ? !1 : !0;
	case "drop":
		if (_config.space.self < 1) return ! 1;
		if (t.indexOf("icosContainer_body_sys_") !== -1) return ! 1;
		if (t.indexOf("icosContainer_folder_") !== -1) {
			u = t.replace("icosContainer_folder_", "");
			if (_config.myuid && _config.sourcedata.folder[u] && _config.sourcedata.folder[u].uid != _config.myuid) return ! 1
		}
		return ! 0;
	case "drag":
		return _config.space.self < 1 ? !1 : t.indexOf("icosContainer_body_sys_") !== -1 ? !1 : _config.myuid > 0 && _config.sourcedata.icos[i] && _config.sourcedata.icos[i].uid != _config.myuid ? !1 : !0;
	default:
		return ! 0
	}
},
_config.taskOpen_run = function() {
	if (_config.taskOpen[_config.currentDesktop]) {
		for (var n = 0; n < _config.taskOpen[_config.currentDesktop].length; n++) _ico.OpenWin(_config.taskOpen[_config.currentDesktop][n].icoid, _config.taskOpen[_config.currentDesktop][n].navid, _config.taskOpen[_config.currentDesktop][n].tab, _config.taskOpen[_config.currentDesktop][n].left, _config.taskOpen[_config.currentDesktop][n].top);
		delete _config.taskOpen[_config.currentDesktop]
	}
},
_config.setOpenOldWin = function() {
	var n, t;
	for (n in _config.screenList.screenlist_u) {
		_config.taskOpen[n] = [];
		for (t in _config.screenList.screenlist_u[n].wins) _config.taskOpen[n].push(_config.screenList.screenlist_u[n].wins[t])
	}
	for (n in _config.screenList.screenlist_0) {
		_config.taskOpen[n] = [];
		for (t in _config.screenList.screenlist_0[n].wins) _config.taskOpen[n].push(_config.screenList.screenlist_0[n].wins[t])
	}
},
_config.setWindowToSave = function(n) {
	if (n.type !== "widget" || _config.space.self < 1) return;
	var t;
	t = n.desktop.indexOf("sys_") === -1 ? _config.screenList.screenlist_u[n.desktop].wins: _config.screenList.screenlist_0[n.desktop].wins,
	t || (t = {}),
	t[n.icoid] = {
		icoid: n.icoid,
		navid: n.desktop,
		tab: n.tab,
		left: n.left,
		top: n.top
	},
	n.desktop.indexOf("sys_") === -1 && _config.space.self > 0 ? (_config.screenList.screenlist_u[n.desktop].wins = t, _config.saveItem.screenlist = 1) : n.desktop.indexOf("sys_") !== -1 && _config.space.self > 1 && (_config.screenList.screenlist_0[n.desktop].wins = t, _config.saveItem.screenlist = 1)
},
_config.sendConfig = function(n) {
	var t, r, i;
	n || (n = !1);
	if (_config.space.self) {
		t = {},
		_config.saveItem.docklist && (t.docklist = _config.dockList.join(",")),
		_config.saveItem.iconpositions && (t.iconpositions_u = serialize(_config.iconpositions_u), t.iconpositions_0 = serialize(_config.iconpositions_0)),
		_config.saveItem.current && (t.current = _config.defaultDesktop),
		_config.saveItem.friend && (t.friend = _config.friend),
		_config.saveItem.thame && (t.thame = _config.thame.system.id, t.custom_backimg = _config.thame.custom.custom_backimg, t.custom_url = _config.thame.custom.custom_url ? _config.thame.custom.custom_url: "", t.custom_window = _config.thame.custom.custom_window ? _config.thame.custom.custom_window: "", t.custom_browser = _config.thame.custom.custom_browser ? _config.thame.custom.custom_browser: "", t.custom_topbar = _config.thame.custom.custom_topbar ? _config.thame.custom.custom_topbar: "", t.custom_filemanage = _config.thame.custom.custom_filemanage ? _config.thame.custom.custom_filemanage: "", t.custom_dock = _config.thame.custom.custom_dock ? _config.thame.custom.custom_dock: "", t.custom_btype = _config.thame.custom.custom_btype > 0 ? _config.thame.custom.custom_btype: 0),
		_config.saveItem.screenlist && (_config.space.uid && (t.screenlist = serialize(_config.screenList.screenlist_u)), _config.space.self > 1 && (t.screenlist_0 = serialize(_config.screenList.screenlist_0)));
		if (_config.saveItem.folder.length > 0) {
			for (r = [], i = 0; i < _config.saveItem.folder.length; i++) r[_config.saveItem.folder[i]] = _config.sourcedata.folder[_config.saveItem.folder[i]];
			r && (t.folderlist = serialize(r))
		}
		//console.log(_config.dataurl + "&do=save&uid=" + _config.uid + "&ukey=" + _config.ukey + "&t=" + +new Date);
		jQuery.isEmptyObject(t) || (t.uid = _config.space.uid, jQuery.ajax({
			type: "post",
			async: n,
			url: _config.dataurl + "&do=save&uid=" + _config.uid + "&ukey=" + _config.ukey + "&t=" + +new Date,
			data: t,
			dataType: "json",
			success: function(n) {
				//console.log(n.data);
				//Alert(_lang.need_refresh);'数据已经在别处被修改，请刷新页面，保持数据同步！'
				n.msg == "refresh" ? (/*Alert(_lang.need_refresh)*/window.location.reload(), window.onbeforeunload = null, window.location.reload()) : n.msg == "success" ? (_config.saveItem = {},
				_config.saveItem.folder = [], _config.jqxhr.dzzsuccess = !0, _config.jqxhr.retrynum = 0) : _config.jqxhr.retrynum < 10 ? _config.saveTimer = setTimeout(function() {
					_config.sendConfig(),
					_config.jqxhr.retrynum++
				},
				1e4) : Alert(n.msg + _lang.save_error)
			},
			error: function() {
				_config.jqxhr.dzzsuccess = !1,
				_config.jqxhr.retrynum < 10 ? _config.saveTimer = setTimeout(function() {
					_config.sendConfig(),
					_config.jqxhr.retrynum++
				},
				1e4) : Alert(n.msg + '1' +_lang.save_error)
			}
		}))
	}
},
/*初始化主题皮肤*/
_config.initthame = function(n) {
	var u, r, t, f, i;
	n == !0 && (_config.thame.system.folder && _config.loadcss(_THEME_ + "/desktop/styles/thame/" + _config.thame.system.folder + "/style.css", "thame_" + _config.thame.system.folder), u = _config.thame.custom.custom_dock || _config.thame.system.dock, u && _config.loadcss(_THEME_ + "/desktop/styles/dock/" + u + "/style.css", "dock"), r = _config.thame.custom.custom_window || _config.thame.system.window, r && _config.loadcss(_THEME_ + "/desktop/styles/window/" + r + "/_Window.css", "window_" + r), t = _config.thame.custom.custom_browser || _config.thame.system.browser, t && _config.loadcss(_THEME_ + "/desktop/styles/browser/" + t + "/_Browser.css", t), f = _config.thame.custom.custom_topbar || _config.thame.system.topbar, f && _config.loadcss(_THEME_ + "/desktop/styles/navbar/" + f + "/_navbar.css", "topbar"), i = _config.thame.custom.custom_filemanage || _config.thame.system.filemanage, i && _config.loadcss(_THEME_ + "/desktop/styles/filemanage/" + i + "/_filemanage.css", "filemanage_" + i)),
	_config.thame.custom.custom_url ? _config.setback("", 0, _config.thame.custom.custom_url, 1) : _config.thame.custom.custom_backimg ? (_config.thame.custom.custom_btype == 0 && (_config.thame.custom.custom_btype = 1), _config.setback(_config.thame.custom.custom_backimg, _config.thame.custom.custom_btype, "", 1)) : _config.thame.system.url ? _config.setback("", 0, _config.thame.system.url, 1) : _config.thame.system.backimg && (_config.thame.system.btype == 0 && (_config.thame.system.btype = 1), _config.setback(_config.thame.system.backimg, _config.thame.system.btype, "", 1))
},
_config.setthame = function(n) {
	_config.thame.system = n,
	_config.thame.custom = {
		custom_backimg: "",
		custom_url: "",
		custom_window: "",
		custom_browser: "",
		custom_topbar: "",
		custom_filemanage: "",
		custom_dock: "",
		custom_btype: ""
	},
	_config.initthame(!0),
	_config.saveItem.thame = 1,
	_config.saveTimer && window.clearTimeout(_config.saveTimer),
	_config.saveTimer = setTimeout(function() {
		_config.sendConfig()
	},
	_config.savetime)
},
/*载入初始化css*/
_config.loadcss = function(n, t) {
	return n && jQuery.get(n,
	function() {
		if (document.getElementById("css_" + t)) document.getElementById("css_" + t).href = n;
		else {
			css1 = document.createElement("link"),
			css1.id = "css_" + t,
			css1.type = "text/css",
			css1.rel = "stylesheet",
			css1.href = n;
			var i = document.getElementsByTagName("head")[0];
			i.appendChild(css1)
		}
		window.setTimeout(function() {
			_config.setDockSize(),
			_navbar.setNavSize()
		},
		_config.delay)
	}),
	!1
},
/*设置停靠栏*/
_config.setdock = function(n) {
	_config.thame.custom.custom_dock = n,
	n = _THEME_ + "/desktop/styles/dock/" + n + "/style.css",
	_config.loadcss(n, "dock"),
	_config.saveItem.thame = 1,
	_config.saveTimer && window.clearTimeout(_config.saveTimer),
	_config.saveTimer = setTimeout(function() {
		_config.sendConfig()
	},
	_config.savetime)
},
_config.setwindowstyle = function(n) {
	_config.thame.custom.custom_window = n;
	var t = "window_" + n;
	n = _THEME_ + "/desktop/styles/window/" + n + "/_Window.css",
	_config.loadcss(n, t),
	_config.saveItem.thame = 1,
	_config.saveTimer && window.clearTimeout(_config.saveTimer),
	_config.saveTimer = setTimeout(function() {
		_config.sendConfig()
	},
	_config.savetime)
},
_config.setbrowserstyle = function(n) {
	_config.thame.custom.custom_browser = n;
	var t = "browser_" + n;
	n = _THEME_ + "/desktop/styles/browser/" + n + "/_Browser.css",
	_config.loadcss(n, t),
	_config.saveItem.thame = 1,
	_config.saveTimer && window.clearTimeout(_config.saveTimer),
	_config.saveTimer = setTimeout(function() {
		_config.sendConfig()
	},
	_config.savetime)
},
_config.settopbarstyle = function(n) {
	_config.thame.custom.custom_topbar = n;
	var t = "topbar_" + n;
	n = _THEME_ + "/desktop/styles/navbar/" + n + "/_navbar.css?"+new Date().getTime(),
	_config.loadcss(n, "topbar"),
	_config.saveItem.thame = 1,
	_config.saveTimer && window.clearTimeout(_config.saveTimer),
	_config.saveTimer = setTimeout(function() {
		_config.sendConfig()
	},
	_config.savetime)
},
_config.setfilemanagestyle = function(n) {
	_config.thame.custom.custom_filemanage = n;
	var t = "filemanage_" + n;
	n = _THEME_ + "/desktop/styles/filemanage/" + n + "/_filemanage.css",
	_config.loadcss(n, t),
	_config.saveItem.thame = 1,
	_config.saveTimer && window.clearTimeout(_config.saveTimer),
	_config.saveTimer = setTimeout(function() {
		_config.sendConfig()
	},
	_config.savetime)
},
_config.deleteback = function(n) {
	_config.thame.custom.custom_backimg == n && (_config.thame.custom.custom_backimg = "", _config.setback(_config.thame.system.backimg, _config.thame.system.btype, _config.thame.system.url, 1), _config.saveItem.thame = 1, _config.saveTimer && window.clearTimeout(_config.saveTimer), _config.saveTimer = setTimeout(function() {
		_config.sendConfig()
	},
	_config.savetime))
},
_config.setback = function(n, t, i, r) {
	if (r == "backimg") n && (_config.thame.custom.custom_backimg = n, _config.thame.custom.custom_url = "", t ? _config.thame.custom.custom_btype = t: t = _config.thame.custom.custom_btype || _config.thame.system.btype || 1),
	parseInt(t) == 0 && (t = 1);
	else {
		if (r == "btype") {
			_config.thame.custom.custom_btype = t,
			_config.thame.custom.custom_url ? _config.setback("", t, _config.thame.custom.custom_url, "url") : _config.thame.custom.custom_backimg ? _config.setback(_config.thame.custom.custom_backimg, t, "", "backimg") : _config.thame.system.url ? _config.setback("", t, _config.thame.system.url, "url") : _config.thame.system.backimg && _config.setback(_config.thame.system.backimg, t, "", "backimg");
			return
		}
		if (r == "url") {
			_config.thame.custom.custom_url = i;
			if (!i) {
				_config.thame.custom.custom_url ? _config.setback("", t, _config.thame.custom.custom_url, "url") : _config.thame.custom.custom_backimg ? _config.setback(_config.thame.custom.custom_backimg, t, "", "backimg") : _config.thame.system.url ? _config.setback("", t, _config.thame.system.url, "url") : _config.thame.system.backimg && _config.setback(_config.thame.system.backimg, t, "", "backimg");
				return
			}
		}
	}
	r != 1 && (_config.saveItem.thame = 1, _config.saveTimer && window.clearTimeout(_config.saveTimer), _config.saveTimer = setTimeout(function() {
		_config.sendConfig()
	},
	_config.savetime));
	if (i) if (i.lastIndexOf(".") !== -1) {
		var u = i.substr(i.lastIndexOf(".")).toLowerCase();
		u == ".jpg" || u == ".png" || u == ".jpeg" || u == ".gif" ? n = i: t = 0
	} else t = 0;
	switch (parseInt(t)) {
	case 0:
		document.getElementById("wrapper_frame").src = i,
		document.getElementById("wrapper_frame").width = "100%",
		document.getElementById("wrapper_frame").height = "100%",
		document.getElementById("imgbg").src = _THEME_ + "/desktop/images/b.gif";
		break;
	case 1:
		document.getElementById("wrapper_frame").src = "about:blank",
		n.indexOf("#") === 0 ? (document.getElementById("wrapper_div").style.background = n, document.getElementById("imgbg").src = _THEME_ + "/desktop/images/b.gif") : document.getElementById("imgbg").src = n,
		document.getElementById("wrapper_frame").width = "0",
		document.getElementById("wrapper_frame").height = "0";
		break;
	case 2:
		document.getElementById("imgbg").src = _THEME_ + "/desktop/images/b.gif",
		document.getElementById("wrapper_frame").src = "about:blank",
		document.getElementById("wrapper_frame").width = "0",
		document.getElementById("wrapper_frame").height = "0",
		document.getElementById("wrapper_div").style.background = n.indexOf("#") === 0 ? n: "url(" + n + ")";
		break;
	case 3:
		document.getElementById("imgbg").src = _THEME_ + "/desktop/images/b.gif",
		document.getElementById("wrapper_frame").src = "about:blank",
		document.getElementById("wrapper_frame").width = "0",
		document.getElementById("wrapper_frame").height = "0",
		document.getElementById("wrapper_div").style.background = n.indexOf("#") === 0 ? n: "url(" + n + ") 50% 50% no-repeat"
	}
},
_config.setDockSize = function() {
	var n, r, i, f, e, t, u;
	n = _config.dockList.length * _ico.divwidth,
	n < _ico.divwidth && (n = _ico.divwidth),
	r = document.getElementById("dock_app_container"),
	r.oncontentmenu = function() {
		return ! 1
	},
	i = n + jQuery("#_stick").width() + jQuery("#_system").width(),
	jQuery("#_stick").width() > 10 || jQuery("#_system").width() > 10 ? jQuery("#docksro").show() : jQuery("#docksro").hide(),
	jQuery("#docksro").is(":visible") && (i += jQuery("#docksro").width()),
	r.style.width = i + "px",
	r.style.marginLeft = -i / 2 + "px",
	f = document.getElementById("_dock"),
	f.style.width = n + "px",
	e = document.getElementById("dock_container"),
	t = (jQuery("#dock_container_left").width() > 1 ? jQuery("#dock_container_left").width() : 23) + jQuery("#dock_app_container").width() + (jQuery("#dock_container_right").width() > 1 ? jQuery("#dock_container_right").width() : 23),
	e.style.width = t + "px",
	u = document.getElementById("dock_bottom"),
	u.style.width = t + "px",
	u.style.marginLeft = -t / 2 + "px",
	document.getElementById("dock_app_container").oncontextmenu = function() {
		return ! 1
	},
	document.getElementById("dock_container").oncontextmenu = function() {
		return ! 1
	}
},
_config.addFavorite = function(n, t) {
	try {
		window.external.addFavorite(n, t)
	} catch(i) {
		try {
			window.sidebar.addPanel(t, n, "")
		} catch(i) {
			alert(_lang.prompt1.ctrlD)
		}
	}
},
_config.setHomepage = function(n) {
	jQuery.browser.msie ? (document.body.style.behavior = "url(#default#homepage)", document.body.setHomePage(n)) : alert(_lang.prompt1.sethome, "notice")
},
_config.dock_up_down = function(n) {
	if (n == "up") {
		jQuery("#dock_bottom").show(),
		jQuery("#dock_bottom_back").show();
		if (!_window.dockhide) return;
		jQuery("#dock_bottom").animate({
			bottom: "0px"
		},
		_config.delay),
		jQuery("#dock_bottom_back").animate({
			bottom: "0px"
		},
		_config.delay),
		_window.dockhide = 0,
		document.getElementById("dock_opbar").className = "dock_opbar_d"
	} else if (n == "down") {
		if (_window.dockhide) return;
		jQuery("#dock_bottom").animate({
			bottom: -_config.dockHeight + "px"
		},
		_config.delay),
		jQuery("#dock_bottom_back").animate({
			bottom: -jQuery("#dock_bottom_back").height() + "px"
		},
		_config.delay),
		_window.dockhide = 1,
		document.getElementById("dock_opbar").className = "dock_opbar_u"
	} else _window.dockhide ? (jQuery("#dock_bottom").show(), jQuery("#dock_bottom_back").show(), jQuery("#dock_bottom").animate({
		bottom: "0px"
	},
	_config.delay), jQuery("#dock_bottom_back").animate({
		bottom: "0px"
	},
	_config.delay), _window.dockhide = 0, document.getElementById("dock_opbar").className = "dock_opbar_d") : (jQuery("#dock_bottom").animate({
		bottom: -_config.dockHeight + "px"
	},
	_config.delay), jQuery("#dock_bottom_back").animate({
		bottom: -jQuery("#dock_bottom_back").height() + "px"
	},
	_config.delay), _window.dockhide = 1, document.getElementById("dock_opbar").className = "dock_opbar_u")
},
_config.parseaudio = function(n, t) {
	switch (t) {
	case "mp3":
	case "wma":
	case "mid":
	case "wav":
		return '<object classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6" width="100%" height="64"><param name="invokeURLs" value="0"><param name="autostart" value="0" /><param name="url" value="' + n + '" /><embed src="' + n + '" autostart="0" type="application/x-mplayer2" width="100%" height="64"></embed></object>';
	case "ra":
	case "rm":
	case "ram":
		var i = "media_".random(3);
		return '<object classid="clsid:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA" width="100%" height="32"><param name="autostart" value="0" /><param name="src" value="' + n + '" /><param name="controls" value="controlpanel" /><param name="console" value="' + i + '_" /><embed src="' + n + '" autostart="0" type="audio/x-pn-realaudio-plugin" controls="ControlPanel" console="' + i + '_" width="100%" height="32"></embed></object>'
	}
	return ! 1
},
_config.openExt = ["flv", "swf"],
_config.parsemedia = function(n, t) {
	switch (t) {
	case "mp3":
	case "wma":
	case "ra":
	case "ram":
	case "wav":
	case "mid":
		return _config.parseaudio(n, t);
	case "rm":
	case "rmvb":
	case "rtsp":
		var i = "media_".random(3);
		return '<object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="100%" height="100%"><param name="autostart" value="0" /><param name="src" value="' + n + '" /><param name="controls" value="imagewindow" /><param name="console" value="' + i + '_" /><embed src="' + n + '" autostart="0" type="audio/x-pn-realaudio-plugin" controls="imagewindow" console="' + i + '_" width="100%" height="100%"></embed></object><br /><object classid="clsid:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA" width="100%" height="32"><param name="src" value="' + n + '" /><param name="controls" value="controlpanel" /><param name="console" value="' + i + '_" /><embed src="' + n + '" autostart="0" type="audio/x-pn-realaudio-plugin" controls="controlpanel" console="' + i + '_" width="100%" height="32"></embed></object>';
	case "flv":
		return AC_FL_RunContent("width", "100%", "height", "100%", "allowNetworking", "internal", "allowScriptAccess", "never", "src", STATICURL + "image/common/flvplayer.swf", "flashvars", "file=" + SITEURL + encodeURI(n), "quality", "high", "wmode", "transparent", "allowfullscreen", "true");
	case "swf":
		return AC_FL_RunContent("width", "100%", "height", "100%", "allowNetworking", "internal", "allowScriptAccess", "never", "src", n, "quality", "high", "bgcolor", "#ffffff", "wmode", "transparent", "allowfullscreen", "true");
	case "asf":
	case "asx":
	case "wmv":
	case "mms":
	case "avi":
	case "mpg":
	case "mpeg":
		return '<object classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6" width="100%" height="100%"><param name="invokeURLs" value="0"><param name="autostart" value="0" /><param name="url" value="' + n + '" /><embed src="' + n + '" autostart="0" type="application/x-mplayer2" width="100%" height="100%"></embed></object>';
	case "mov":
		return '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="100%" height="100%"><param name="autostart" value="false" /><param name="src" value="' + n + '" /><embed src="' + n + '" autostart="false" type="video/quicktime" controller="true" width="100%" height="100%"></embed></object>';
	default:
		return ! 1
	}
	return ! 1
}