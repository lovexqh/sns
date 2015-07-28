function _navbar(n, t, i, r) {
	this.id = n,
	this.name = t,
	this.type = i.type || "desktop",
	this.url = i.navurl || "",
	this.target = i.target || "",
	this.icon = i.navicon | "",
	this._blank = this.type == "custom" && this.target == "_blank" ? 1 : 0,
	this.avaliable = i.avaliable * 1 || 1,
	this.margintop = i.margintop * 1 || 0,
	this.marginright = i.marginright * 1 || 0,
	this.marginbottom = i.marginbottom * 1 || 0,
	this.marginleft = i.marginleft * 1 || 0,
	this.iconview = i.iconview * 1,
	this.autolist = i.autolist > 0 ? 1 : 0,
	this.iconposition = i.iconposition * 1 || 0,
	this.backimage = i.backimg || _THEME_+"/desktop/images/b.gif",
	this.topbarshow = i.topbarshow * 1 || 0,
	this.dockshow = i.dockshow * 1 || 0,
	this.friend = i.friend * 1 || 0,
	this.zIndex = ++_navbar.zIndex,
	this.container = r,
	_navbar.navbars[this.id] = this
}
function _ico(n, t, i) {
	this.id = n,
	t.indexOf("icosContainer_body_") !== -1 ? (this.navid = t.replace("icosContainer_body_", ""), this.iconview = _config.iconview[_navbar.navbars[this.navid].iconview] || {},
	this.divwidth = parseInt(this.iconview.divwidth) || _ico.divwidth, this.divheight = parseInt(this.iconview.divheight) || _ico.divheight, this.width = parseInt(this.iconview.width) || _ico.width, this.height = parseInt(this.iconview.height) || _ico.height, this.paddingleft = parseInt(this.iconview.paddingleft) || _ico.paddingLeft, this.paddingtop = parseInt(this.iconview.paddingtop) || _ico.paddingTop) : (this.navid = null, this.iconview = {},
	this.divwidth = _ico.divwidth, this.divheight = _ico.divheight, this.width = _ico.width, this.height = _ico.height, this.paddingleft = _ico.paddingLeft, this.paddingtop = _ico.paddingTop),
	this.string = "_ico.icos." + this.id,
	this.zIndex = ++_ico.zIndex,
	this.align = this.iconview.align * 1 || 0,
	this.sourceid = _config.sourcedata.icos[n].oid || 0,
	this.type = _config.sourcedata.icos[n].type || "",
	this.idtype = _config.sourcedata.icos[n].idtype || "",
	this.app_type = _config.sourcedata.icos[n].app_type || "app",
	this.typeid = _config.sourcedata.icos[n].typeid || 0,
	this.text = _config.sourcedata.icos[n].name,
	this.img = _config.Permission("open", "", this.id) ? _config.sourcedata.icos[n].img || _THEME_+"/desktop/images/default/icodefault.png": _THEME_+"/desktop/images/default_ys/" + this.type + ".png",
	this.url = _config.sourcedata.icos[n].url || "",
	this.open = _config.sourcedata.icos[n].open * 1 || 0,
	this.notdelete = _config.sourcedata.icos[n].notdelete * 1 || 0,
	this.defaultopen = _config.sourcedata.icos[n].defaultopen * 1 || 0,
	this.wwidth = _config.sourcedata.icos[n].wwidth || 0,
	this.wheight = _config.sourcedata.icos[n].wheight || 0,
	this.move = _config.self > 0 ? "move": "no",
	this.className = _ico.ClassName,
	this.pos = i,
	this.container = t,
	_ico.icos[this.id] = this
}
function stack_run() {
	var r, n, t, i;
	if (jQuery.isEmptyObject(_ico.stack_ico)) document.getElementById("_blank").style.display = "none";
	else {
		r = "";
		for (n in _ico.stack_ico) {
			for (t = 0; t < _ico.stack_ico[n].length; t++) i = _ico.CIco(_ico.stack_ico[n][t].icoid, _ico.stack_ico[n][t].container, _ico.stack_ico[n][t].pos),
			i.defaultopen > 0 && _config.taskOpen[n].push({
				icoid: i.id,
				navid: n
			});
			delete _ico.stack_ico[n],
			stack_run()
		}
	}
}
function _Drag(n, t, i) {
	this.id = this.name = n + "_" + t,
	this.string = "_Drag.icos." + this.id,
	this.icoid = n,
	this.width = _Drag.width,
	this.height = _Drag.height,
	this.top = 0,
	this.left = 0,
	this.text = "title",
	this.img = "x/images/1.png",
	this.url = "#",
	this.filemanageid = t,
	this.container = i,
	this.data = _config.sourcedata.icos[n],
	_Drag.icos[this.id] = this
}
function _window(n, t) {
	this.id = t ? this.name = "_W_" + t: this.name = "_W_" + ++_window.wIndex,
	this.string = "_window.windows." + this.id,
	this.zIndex = ++_window.zIndex,
	this.className = _window.getFeature(n, "class") || _config.thame.custom.custom_window || _config.thame.system.window || "mac",
	this.bodyWidth = getcookie("win_width_" + this.id) != "" ? getcookie("win_width_" + this.id) : _window.getFeature(n, "width") || _window.getFeature(n, "width") || _window.Width,
	this.bodyHeight = getcookie("win_height_" + this.id) != "" ? getcookie("win_height_" + this.id) : _window.getFeature(n, "height") || _window.getFeature(n, "height") || _window.Heigh,
	this.left = _window.getFeature(n, "left") != "" ? _window.getFeature(n, "left") : getcookie("win_left_" + this.id) != "" ? parseInt(getcookie("win_left_" + this.id)) : "",
	this.top = _window.getFeature(n, "top") != "" ? _window.getFeature(n, "top") : getcookie("win_top_" + this.id) != "" ? parseInt(getcookie("win_top_" + this.id)) : "",
	this.right = _window.getFeature(n, "right"),
	this.bottom = _window.getFeature(n, "bottom"),
	this.move = _window.getFeature(n, "move").toLowerCase() || "move",
	this.moveable = this.move == "no" ? !1 : !0,
	this.button = _window.getFeature(n, "button").toUpperCase(),
	this.resize = _window.getFeature(n, "resize").toUpperCase() || "RESIZE|RESIZE-X|RESIZE-Y",
	this.resizeable = this.resize == "no" ? !1 : !0,
	this.titleButton = _window.getFeature(n, "titlebutton").toUpperCase() || "",
	this.isModal = _window.getFeature(n, "ismodal").toLowerCase() == "yes" ? !0 : !1,
	this.tabs = {},
	this.buttons = {},
	this.type = null,
	this.isHide = null,
	this.Sequence = [],
	this.Csequence = 0,
	this.bodyWidth = isNaN(parseInt(this.bodyWidth)) ? 800 : parseInt(this.bodyWidth),
	this.bodyHeight = isNaN(parseInt(this.bodyHeight)) ? 600 : parseInt(this.bodyHeight),
	this.left = isNaN(parseInt(this.left)) ? "": parseInt(this.left),
	this.top = isNaN(parseInt(this.top)) ? "": parseInt(this.top),
	_window.windows[this.id] = this
}
function _login() {	//将资源管理器更改为了应用市场
	_login.zIndex = ++_ico.zIndex,
	_login.img = _THEME_+"/desktop/images/app.png",	//_THEME_+"/desktop/images/default/filemanage.png",
	_login.url = "about:blank",
	_login.text = _lang.WinTitle.market,  //_lang.WinTitle.filemanage
	_login.container = "_system",
	_login.width = 50,
	_login.height = 50,
	_login.divwidth = 70,
	_login.divheight = 70,
	_login.ispng = 1,
	_login.className = "system"
}
function _task(n) {
	this.id = this.name = n,
	this.string = "_task.icos." + this.id,
	this.zIndex = ++_ico.zIndex,
	n == "sys_browser" ? (this.img = _THEME_+"/desktop/images/default/liulanqi.png", this.url = "about:blank", this.text = _lang.browser, this.ispng = 1) : n == "sys_market" ? (this.img = _THEME_+"/desktop/images/default/yingyong.png", this.url = _config.marketurl, this.text = _lang.WinTitle.market, this.ispng = 1) : n == "sys_widget" ? (this.img = _THEME_+"/desktop/images/default/widget.png", this.url = _config.widgeturl, this.text = _lang.WinTitle.widget, this.ispng = 1) : n == "sys_theme" ? (this.img = _THEME_+"/desktop/images/default/thame.png", this.url = _config.systhameturl, this.text = _lang.WinTitle.theme, this.ispng = 1) : n == "sys_addApp" ? (this.img = _THEME_+"/desktop/images/default/addapp.png", this.url = _config.sysaddappturl, this.text = _lang.sysaddapp, this.ispng = 1) : n == "sys_pic" ? (this.img = _THEME_+"/desktop/images/default/pic.png", this.url = "", this.text = windows[n].title, this.ispng = 1) : n == "sys_filemanage" ? (this.img = _THEME_+"/desktop/images/default/filemanage.png", this.url = "", this.text = windows[n].title, this.ispng = 1) : (this.text = _config.sourcedata.icos[n].name, this.img = _config.sourcedata.icos[n].img || _THEME_+"/desktop/images/default/icodefault.png", this.url = _config.sourcedata.icos[n].url || "about:blank", this.img.substr(this.img.length - 4) == ".png" && (this.ispng = !0)),
	_config.dockTaskList.length > _task.indocksum ? (this.container = "stick_container", this.height = _task.divheight - 4, this.width = this.height, this.divwidth = _task.divwidth, this.divheight = _task.divheight, this.className = "stick_container_item") : (this.className = _task.className, this.container = "_stick", this.width = _ico.width, this.height = _ico.height, this.divwidth = _ico.divwidth, this.divheight = _ico.divheight),
	this.pos = _config.dockTaskList.length - 1;
	if (_config.sourcedata.icos[n] && _config.sourcedata.icos[n].type == "folder") {
		var t = _ico.getTopFid(_config.sourcedata.icos[n].oid);
		this.winid = "_W_" + t[t.length - 1]
	} else this.winid = "_W_" + n;
	_task.icos[this.id] = this
}
function _widget(n) {
	this.id = this.name = "_G_" + n,
	this.string = "_widget.widgets." + this.id,
	this.zIndex = ++_widget.zIndex,
	this.gid = n,
	this.data = _config.sourcedata.widgets[n],
	this.className = this.data.classname,
	this.bodyWidth = this.data.width || _widget.Width,
	this.bodyHeight = this.data.height || _widget.Height,
	_widget.widgets[this.id] = this
}
function OpenFile(n, t, i, r) {
	n != "open" && n !== "save" && n !== "saveto" && (n = "open");
	var u = _window.OpenFile(n, t, r, _lang.OpenFile[n], windows.OpenFile.features);
	u.OnOK = function() {
		var f = document.getElementById("file_select_input").value,
		h,
		t,
		o,
		e,
		s;
		if (n == "open") {
			o = _file.data[_file.id],
			e = 0;
			for (s in o) if (o[s].name == f) {
				e = s;
				break
			}
			if (document.getElementById("file_select_input").value == "") {
				alert("please select a file!");
				return
			}
			if (e) t = {},
			t.params = r,
			t.icodata = o[e],
			t.position = _file.id;
			else if (checkeURL(f)) h = {},
			h.url = f,
			h.name = f.substr(f.lastIndexOf("/") + 1),
			t = {},
			t.params = r,
			t.icodata = h;
			else {
				alert("file not found");
				return
			}
		} else if (n == "save" || n == "saveto") {
			t = {};
			if (document.getElementById("file_select_input").value == "") {
				alert("please select a file! or input filename");
				return
			}
			o = _file.data[_file.id],
			e = 0;
			for (s in o) if (o[s].name == f) {
				e = s;
				break
			}
			if (e) if (confirm(_lang.cover_confirm + '"' + f + '?"')) t.icodata = o[e];
			else return;
			t.filecode = document.getElementById("file_code_select").value,
			t.params = r,
			f.indexOf(".") === -1 && document.getElementById("file_type_select").value != "All" && (f = f + "." + document.getElementById("file_type_select").value),
			t.name = f,
			t.position = _file.id
		}
		i && _config.sendDataTo(i, t),
		u.Close()
	},
	u.OnCANCEL = function() {
		u.Close()
	}
}
function Showtablist() {
	var r = document.createElement("div"),
	i,
	t,
	u,
	n;
	for (r.id = "tablist_show", r.className = "tablist_container", _window.windows._W_sys_browser.contentCase.appendChild(r), i = [], t = document.getElementById("tabs_container").getElementsByTagName("div"), n = 0; n < t.length; n++)(t[n].className == "tabs_td" || t[n].className == "tabs_td_active") && i.push(t[n]);
	for (u = "", n = 0; n < i.length; n++) u += '<div class="tablist_li" onclick="FocusTab(' + i[n].id.replace("tab_", "") + ')">' + document.getElementById("name_" + i[n].id.replace("tab_", "")).innerHTML + "</div>";
	r.innerHTML = u,
	jQuery(document).bind("mousedown.temp1",
	function(n) {
		n = n ? n: window.event;
		var i = n.srcElement ? n.srcElement: n.target,
		t = jQuery(i).parent().attr("id");
		t != "tablist_show" && (jQuery("#tablist_show").remove(), jQuery(document).unbind(".temp1"))
	})
}
function ChangeSequence(n) {
	var t = _tab.tabs[_window.currentTab];
	if (t.Csequence <= 1 && n == "back" || t.Csequence >= t.sequence.length && n == "next") return;
	n == "back" ? t.Csequence -= 1 : t.Csequence += 1,
	document.getElementById("address").value = t.sequence[t.Csequence - 1],
	document.getElementById("ifm_browser_" + _window.currentTab).src = t.sequence[t.Csequence - 1],
	document.getElementById("name_" + _window.currentTab).innerHTML = t.Csequence == 1 ? t.name: t.sequence[t.Csequence - 1].replace("http://", ""),
	document.getElementById("seq_back__W_sys_browser").className = t.Csequence <= 1 ? "BACK1": "BACK2",
	document.getElementById("seq_next__W_sys_browser").className = t.Csequence >= t.sequence.length ? "NEXT1": "NEXT2"
}
function geturlname() {}
function viewTab(n) {
	checkeURL(n) || (n = "http://" + n),
	checkeURL(n) ? (document.getElementById("address").value = n, window.frames["ifm_browser_" + _window.currentTab].location.href = n, _tab.tabs[_window.currentTab].url = n, n != _tab.tabs[_window.currentTab].sequence[_tab.tabs[_window.currentTab].Csequence - 1] && (document.getElementById("name_" + _window.currentTab).innerHTML = n.replace("http://", ""), _tab.tabs[_window.currentTab].sequence.splice(_tab.tabs[_window.currentTab].Csequence, _tab.tabs[_window.currentTab].sequence.length - _tab.tabs[_window.currentTab].Csequence), _tab.tabs[_window.currentTab].Csequence = _tab.tabs[_window.currentTab].sequence.push(n), _tab.tabs[_window.currentTab].Csequence > 1 && (document.getElementById("seq_back__W_sys_browser").className = "BACK2"), document.getElementById("seq_next__W_sys_browser").className = "NEXT1")) : alert(_lang.urlinvalid)
}
function FocusTab(n) {
	var i, t;
	if (!document.getElementById("tab_" + n)) return;
	i = _window.windows._W_sys_browser,
	jQuery(i.contentCase).find("iframe").hide(),
	document.getElementById("ifm_browser_" + n).style.display = "block",
	document.getElementById("address").value = _tab.tabs[n].url,
	jQuery("#tabs_container").find(".tabs_td_active").removeClass("tabs_td_active").addClass("tabs_td"),
	document.getElementById("tab_" + n).className = "tabs_td_active",
	_window.currentTab = n,
	t = _tab.tabs[_window.currentTab],
	document.getElementById("seq_back__W_sys_browser").className = t.Csequence <= 1 ? "BACK1": "BACK2",
	document.getElementById("seq_next__W_sys_browser").className = t.Csequence >= t.sequence.length ? "NEXT1": "NEXT2"
}
function CloseTab(n) {
	var r = _window.windows._W_sys_browser,
	t, i;
	r.contentCase.removeChild(document.getElementById("ifm_browser_" + n)),
	document.getElementById("tabs_container").removeChild(document.getElementById("tab_" + n)),
	delete _tab.tabs[n],
	t = 0;
	for (i in _tab.tabs) t++;
	if (t < 1) OpenBrowser(_config.sysbrowserurl, _lang.index);
	else {
		if (_window.currentTab == n) {
			for (i in _tab.tabs) _window.currentTab = i;
			FocusTab(_window.currentTab)
		}
		_window.resetTabs()
	}
	return ! 1
}
function _tab(n, t) {
	this.id = _tab.zIndex++,
	this.sequence = [],
	this.url = n,
	this.name = t,
	this.Csequence = 0,
	_tab.tabs[this.id] = this
}
function CreateTabs(n, t) {
	var u = _window.windows._W_sys_browser,
	r, i;
	checkeURL(n) || (n = "http://" + n),
	r = new _tab(n, t),
	_window.currentTab = r.id,
	r.Csequence = r.sequence.push(n),
	document.getElementById("seq_next__W_sys_browser").className = "NEXT1",
	document.getElementById("seq_back__W_sys_browser").className = "BACK1",
	jQuery(u.contentCase).find("iframe").hide(),
	jQuery("#tabs_container").find(".tabs_td_active").removeClass("tabs_td_active").addClass("tabs_td"),
	//jQuery.browser.msie ? (xframe = document.createElement('<iframe name="ifm_browser_' + _window.currentTab + '" id="ifm_browser_' + _window.currentTab + '" src="' + n + '" frameBorder="0" marginHeight="0" marginWidth="0" class="browserIframe"></iframe>'), xframe.style.overflowX = "hidden") : (xframe = document.createElement("iframe"), xframe.name = "ifm_browser_" + _window.currentTab, xframe.id = "ifm_browser_" + _window.currentTab, xframe.className = "browserIframe", xframe.frameBorder = 0, xframe.marginHeight = 0, xframe.marginWidth = 0, xframe.src = n),
	(xframe = document.createElement("iframe"), xframe.name = "ifm_browser_" + _window.currentTab, xframe.id = "ifm_browser_" + _window.currentTab, xframe.className = "browserIframe", xframe.frameBorder = 0, xframe.marginHeight = 0, xframe.marginWidth = 0, xframe.src = n);
	u.contentCase.appendChild(xframe),
	u.contentCase.style.overflow = "hidden",
	document.getElementById("address").value = n,
	i = document.createElement("div"),
	i.id = "tab_" + _window.currentTab,
	i.className = "tabs_td_active",
	i.style.cssText = "position:absolute; overflow:hidden;width:110px;height:23px;",
	document.getElementById("tabs_container").appendChild(i),
	i.innerHTML = '<div class="zuo"></div><div id="tab_con_' + _window.currentTab + '" class="bg" onmouseover="showClose(' + _window.currentTab + ",'over');\" onmouseout=\"showClose(" + _window.currentTab + ",'out');\" onclick=\"FocusTab(" + _window.currentTab + ');"><span class="name_text" id="name_' + _window.currentTab + '">' + t + '</span><div id="close_' + _window.currentTab + '"  onclick="CloseTab(' + _window.currentTab + ');return false;" class="tabs_close" title="' + _lang.Close + '" ></div></div><div class="you"></div>',
	_window.resetTabs()
}
function showClose(n, t) {
	document.getElementById("close_" + n).style.display = t == "over" ? "block": "none"
}
function checkeURL(n) {
	var r = n,
	i, t;
	return r == "about:blank" ? !0 : (i = /[a-zA-z]+:\/\/[^\s]*/, t = new RegExp(i), t.test(r) == !0 ? !0 : !1)
}
function favtodesktop() {
	OpenWindow("sys_addApp")
}
var qq;
_navbar.navbars = {},
_navbar.zIndex = 0,
_navbar.showUserDetail = function() {
	var n = jQuery("#nav_userinfo .user_vwmy,#nav_userinfo .user_avator,#navbar .user_avatar_mask");
	n.bind("click",
	function() {
		jQuery("#navbar_userdetail").show(),
		ajaxget(_config.ajaxurl + "&do=userdetail&uid=" + _config.uid, "navbar_userdetail_content"),
		jQuery(document).bind("mousedown.navbar_userdetail",
		function(n) {
			n = n ? n: window.event;
			var t = n.srcElement ? n.srcElement: n.target;
			checkInDom(t, "navbar_userdetail") == !1 && (jQuery("#navbar_userdetail").hide(), jQuery(document).unbind(".navbar_userdetail"))
		})
	})
},
_navbar.init = function() {
	var n;
	for (n in _config.screenList.screenlist_u) _navbar.Cnavbar(n, _config.screenList.screenlist_u[n].config.navname, _config.screenList.screenlist_u[n].config, "indicator_Container");
	for (n in _config.screenList.screenlist_0) _navbar.Cnavbar(n, _config.screenList.screenlist_0[n].config.navname, _config.screenList.screenlist_0[n].config, "sysindicator_Container");
	_navbar.setNavSize(),
	_navbar.showUserDetail()
},
_navbar.Cnavbar = function(n, t, i, r) {
	var u = new _navbar(n, t, i, r);
	return u.container == "indicator_Container" ? u.create() : u.container == "sysindicator_Container" && u.syscreate(),
	_config.currentDesktop == u.id && _navbar.setCurrentDesktop(u.id),
	u
},
_navbar.setNavSize = function() {
	var t = 0,
	i = jQuery("#indicator_Container"),
	r = jQuery("#navbar_container"),
	n;
	for (n in _navbar.navbars) _navbar.navbars[n].container == "indicator_Container" && (t += jQuery(_navbar.navbars[n].board).outerWidth() + parseInt(jQuery(_navbar.navbars[n].board).css("marginLeft")) + parseInt(jQuery(_navbar.navbars[n].board).css("marginRight")));
	_navbar.navwidth = t + 1,
	i.css({
		width: t + 1
	}),
	_navbar.navwidth > r.width() ? _navbar.setNavItemPosition() : jQuery("#navbar_container .indicator_op").hide()
},
_navbar.setNavItemPosition = function() {
	jQuery("#indicator_next").show()
},
_navbar.setpreDesktop = function() {
	var t = _config.currentDesktop,
	n = jQuery.inArray(t, _config.navids);
	if (n < 1) return;
	_navbar.setCurrentDesktop(_config.navids[n - 1])
},
_navbar.setnextDesktop = function() {
	var t = _config.currentDesktop,
	n = jQuery.inArray(t, _config.navids);
	if (n >= _config.navids.length - 1) return;
	_navbar.setCurrentDesktop(_config.navids[n + 1])
},
_navbar.resize = function() {},
_navbar.prototype.syscreate = function() {
	this.board = document.getElementById("indicator_" + this.id);
	var n = this;
	jQuery("#indicator_" + this.id).attr("href", "###"),
	jQuery("#indicator_" + this.id).bind("click",
	function() {
		return _navbar.setCurrentDesktop(n.id),
		!1
	}),
	this.Cdesktop()
},
_navbar.prototype.create = function() {
	this.board = document.createElement("a"),
	//this.board.id = "indicator_" + this.zIndex,
    this.board.id = "indicator_" + this.id,

	this.board.className = "navItem indicator indicator_" + this.zIndex,
	this.board.setAttribute("navid", this.id),
	this.board.setAttribute("index", this.zIndex);
	var n = this;
	jQuery(this.board).bind("click",
	function() {
		return _navbar.setCurrentDesktop(n.id),
		!1
	}),
	this.board.title = this.name,
	//this.board.innerHTML = this.zIndex,
    this.board.innerHTML = this.name,
	document.getElementById(this.container).appendChild(this.board),
	this.Cdesktop()
},
_navbar.setCurrentDesktop = function(n) {
	var t, i, u, r;
	t = _navbar.navbars[n];
	if (t._blank) {
		window.open(t.url);
		return false;
	};
	_config.navbar_moving > 0 && (_config.setCurrentDesktopTimer && window.clearTimeout(_config.setCurrentDesktopTimer), _config.setCurrentDesktopTimer = window.setTimeout(function() {
		_navbar.setCurrentDesktop(n)
	},
	50)),
	_config.navbar_moving = 2;
	var f = 0,
	e = 0,
	o = 0;
	return jQuery("#navbar .navItem").each(function() {
		o++,
		jQuery(this).attr("id") == "indicator_" + t.id && (e = o),
		jQuery(this).attr("id") == "indicator_" + _config.currentDesktop && (f = o),
		jQuery(this).removeClass("sysnav_current").removeClass("indicator_current").removeClass("sysnav_current_" + jQuery(this).attr("index")).removeClass("indicator_current_" + jQuery(this).attr("index"))
	}),
	t.container == "sysindicator_Container" ? jQuery(t.board).addClass("sysnav_current").addClass("sysnav_current_" + jQuery("#indicator_" + t.id).attr("index")) : jQuery(t.board).addClass("indicator_current").addClass("indicator_current_" + jQuery("#indicator_" + t.id).attr("index")),
	i = jQuery("#_body_" + t.id),
	u = jQuery("#_body_" + _config.currentDesktop),
	_config.currentDesktop = t.id,
	//rickeryu add 2013-10-28 导航加载的时候，刷新当前页面
	(t.url != '' && t.url != 'undefined') ? jQuery('#_body_ifm_'+t.id).attr('src',t.url) : function(){},
	//rickeryu end
	_config.taskOpen_run(),
	_widget.taskWidget_run(),
	f < e ? (i.css("left", _config.screenWidth), u.animate({
		left: -_config.screenWidth + "px"
	},
	_ico.delay,
	function() {
		_config.navbar_moving -= 1
	}), i.animate({
		left: "0px"
	},
	_ico.delay,
	function() {
		_config.navbar_moving -= 1
	})) : f == e ? (i.css("left", 0), jQuery("#navbar .navItem").each(function() {
		jQuery(this).attr("id") != "indicator_" + t.id && jQuery(this).css("left", -_config.screenWidth)
	}), _config.navbar_moving = 0) : (i.css("left", -_config.screenWidth), u.animate({
		left: _config.screenWidth + "px"
	},
	_ico.delay,
	function() {
		_config.navbar_moving -= 1
	}), i.animate({
		left: "0px"
	},
	_ico.delay,
	function() {
		_config.navbar_movin -= 1
	})),
	t.type == "custom" && (r = document.getElementById("_body_ifm_" + t.id), r.src == "about:blank" && (r.src = t.url)),
	t.setTopbarShow(),
	t.setDockShow(),
	ieVersion > 0 && ieVersion < 8 && _navbar.checkTopbar_overWindow(_config.currentDesktop),
	!1
},
_navbar.prototype.Cdesktop = function() {
	var t, n;
	if (this._blank) return;
	t = document.createElement("div"),
	t.className = "desktop",
	t.id = "_body_" + this.id,
	t.style.left = "5000px",
	t.setAttribute("navid", this.id),
	t.style.background = this.backimage.indexOf("#") === 0 ? this.backimage: "url(" + this.backimage + ")",
	document.getElementById("_bodys").appendChild(t),
	this.type == "desktop" ? this.CIcoContainer() : (n = document.createElement("iframe"), n.className = "bodyIframe", n.name = "_body_ifm_" + this.id, n.id = "_body_ifm_" + this.id, n.frameBorder = 0, n.marginHeight = 0, n.marginWidth = 0, n.allowtransparency = !0, n.src = "about:blank", n.setAttribute("link", this.url), ieVersion > 0 && ieVersion < 7 && n.setAttribute("scrolling", "yes"), document.getElementById("_body_" + this.id).appendChild(n))
},
_navbar.prototype.CIcoContainer = function() {
	var t = document.createElement("div"),
	n = this,
	i;
	t.id = "icosContainer_body_" + this.id,
	t.className = "icosContainer",
	t.style.cssText = "width:" + (_config.screenWidth - this.marginleft - this.marginright) + "px; height:" + (_config.screenHeight - this.margintop - this.marginbottom) + "px;left:" + this.marginleft + "px;top:" + this.margintop + "px;",
	document.getElementById("_body_" + this.id).appendChild(t),
	jQuery(t).bind("contextmenu",
	function(n) {
		return _contextmenu.right_body(n ? n: window.event, this.id),
		!1
	}),
	_config.Permission("upload", "icosContainer_body_" + this.id) && (i = document.createElement("div"), i.id = "input_icosContainer_body_" + this.id, i.style.cssText = "position:absolute;z-index:-999999", document.body.appendChild(i), _config.uploader["icosContainer_body_" + this.id] = new qq.FileUploaderBasic({
		action: DZZSCRIPT + "?mod=system&op=dzzcp&do=upload",
		params: {
			container: "icosContainer_body_" + n.id,
			ukey: _config.ukey,
			uid: _config.uid
		},
		allowedExtensions: _config.space.attachextensions,
		sizeLimit: isNaN(parseInt(_config.space.maxattachsize)) ? 0 : parseInt(_config.space.maxattachsize),
		minSizeLimit: 0,
		button: document.getElementById("input_icosContainer_body_" + n.id),
		debug: !1,
		onSubmit: function(t, i) {
			var u = {
				icoid: "uploader_" + n.id + "_" + t,
				name: i,
				img: _THEME_+"/desktop/images/extimg/unknow.png",
				oid: 0,
				type: "upload",
				idtype: "",
				typeid: 0
			},
			f,
			o,
			r,
			e;
			_config.sourcedata.icos[u.icoid] = u,
			f = n.id,
			o = f.indexOf("sys_") !== -1 ? _config.screenList.screenlist_0[f].icos.push(u.icoid) : _config.screenList.screenlist_u[f].icos.push(u.icoid),
			r = _ico.CIco(u.icoid, "icosContainer_body_" + n.id, o - 1),
			jQuery(r.icoblank).remove(),
			jQuery(r.board).unbind(),
			e = '<div class="upload_text">' + r.text + "</div>",
			e += '<div id="upload_progress_' + r.id + '" class="upload_text_back"></div>',
			jQuery("#text_" + r.id).html(e)
		},
		onProgress: function(t, i, r, u) {
			var f = _ico.icos["uploader_" + n.id + "_" + t];
			jQuery("#upload_progress_" + f.id).css("width", Math.floor(r / u * 100) + "%")
		},
		onComplete: function(t, i, r) {
			var u = _ico.icos["uploader_" + n.id + "_" + t],
			c,
			e,
			s,
			o,
			l,
			f,
			h;
			jQuery("#upload_progress_" + u.id).css("width", "100%");
			if (r.success) {
				c = r.container,
				delete r.container,
				e = r,
				_config.sourceids.icos.push(r.icoid),
				_config.sourcedata.icos[r.icoid] = e,
				s = [u.left, u.top],
				o = u.pos,
				jQuery(u.board).remove();
				for (l in u) delete u[l];
				delete u,
				n.id.indexOf("sys_") !== -1 ? _config.screenList.screenlist_0[n.id].icos[o] = e.icoid: _config.screenList.screenlist_u[n.id].icos[o] = e.icoid,
				f = new _ico(e.icoid, c, o),
				s && (f.left = s[0], f.top = s[1], f.nopos = 1),
				f.img.substr(f.img.length - 4) == ".png" && (f.ispng = !0),
				f.CreatIco(),
				_window.windows._W_sys_filemanage && _window.windows._W_sys_filemanage.filemanageid && _filemanage.cons[_window.windows._W_sys_filemanage.filemanageid] && _filemanage.cons[_window.windows._W_sys_filemanage.filemanageid].CreateIcos(e)
			} else h = jQuery('<div class="upload_failure_close"></div>').appendTo("#text_" + u.id),
			h.bind("click",
			function() {
				_ico.removeIcoid(u.id)
			}),
			jQuery("#upload_progress_" + u.id).css("background", "#f9d5de"),
			jQuery("#text_" + u.id).css("border", "1px solid #f5bccd"),
			jQuery("#text_" + u.id).attr("title", r.error)
		},
		showMessage: function(n) {
			Alert(n)
		}
	}), jQuery.browser.msie || (_config.Droper["icosContainer_body_" + n.id] = new qq.UploadDropZone({
		element: document.getElementById("icosContainer_body_" + n.id),
		onEnter: function(n) {
			qq.stopPropagation(n)
		},
		onLeave: function(n) {
			qq.stopPropagation(n)
		},
		onLeaveNotDescendants: function() {},
		onDrop: function(t) {
			var i = t.dataTransfer.getData("text/plain");
			i ? ajaxget(_config.systemurl + "&op=dzzcp&do=newlink&container=icosContainer_body_" + n.id + "&handlekey=handle_add_newlink&uid=" + _config.uid + "&ukey=" + _config.ukey + "&link=" + i) : _config.uploader["icosContainer_body_" + n.id]._uploadFileList(t.dataTransfer.files)
		}
	})))
},
_navbar.prototype.setTopbarShow = function(n) {
	jQuery("#navbar").unbind(),
	this.topbarshow == 1 || n == 1 ? (jQuery("#navbar").css("top", -(jQuery("#navbar").height() - 3)), jQuery("#navbar").unbind(), jQuery("#navbar").bind("mouseout",
	function(n) {
		checkHover(n ? n: window.event, document.getElementById("navbar")) && (_navbar.navTimer = setTimeout(function() {
			jQuery("#navbar").css({
				top: -(jQuery("#navbar").height() - 3)
			})
		},
		1e3))
	}), jQuery("#navbar").bind("mouseover",
	function(n) {
		checkHover(n ? n: window.event, document.getElementById("navbar")) && (_navbar.navTimer && clearTimeout(_navbar.navTimer), jQuery("#navbar").css({
			top: 0
		}))
	}), jQuery("#navbar").show()) : this.topbarshow == 0 ? (jQuery("#navbar").css({
		top: 0
	}), jQuery("#navbar").show(), jQuery("#navbar").unbind()) : this.topbarshow == 2 && jQuery("#navbar").hide()
},
_navbar.prototype.setDockShow = function() {
	this.dockshow == 0 ? (jQuery("#dock_bottom").show(), jQuery("#dock_bottom_back").show(), jQuery("#dock_opbar").show(), _config.dock_up_down("up")) : this.dockshow == 1 ? (jQuery("#dock_opbar").show(), _config.dock_up_down("down")) : this.dockshow == 2 && (jQuery("#dock_bottom").hide(), jQuery("#dock_bottom_back").hide(), jQuery("#dock_opbar").hide())
},
_navbar.checkTopbar_overWindow = function(n) {
	var t = function(n) {
		if (_navbar.navbars[n].topbarshow > 0) return ! 1;
		for (var t in _window.windows) if (_window.windows[t].desktop == n && _window.windows[t].top < jQuery("#navbar").height()) return ! 0;
		return ! 1
	};
	ieVersion > 0 && ieVersion < 8 && (t(n) ? _navbar.navbars[n].setTopbarShow(1) : _navbar.navbars[n].setTopbarShow())
},
_contextmenu = {},
_contextmenu.right_img = function(n, t) {
	var u, f, r, i, o, e;
	n = n ? n: window.event,
	u = n.clientX,
	f = n.clientY;
	if (_config.space.self < 1) return;
	r = document.getElementById("right_img").innerHTML,
	r = r.replace(/XX/g, u),
	r = r.replace(/YY/g, f),
	r = r.replace(/_imgurl/g, t),
	i = document.getElementById("right_contextmenu") ? jQuery(document.getElementById("right_contextmenu")) : jQuery('<div id="right_contextmenu" class="menu"></div>').appendTo(document.body),
	i.html(r),
	i.css({
		"z-index": _window.zIndex + 1
	}),
	i.show(),
	o = document.documentElement.clientWidth,
	e = document.documentElement.clientHeight,
	i.find(">div").each(function() {
		var n = jQuery(this),
		t = n.find(".menu"),
		r;
		t.length ? (r = n.find(".menu-shadow"), n.bind("mouseover",
		function() {
			_contextmenu.ppp && _contextmenu.ppp.hide(),
			_contextmenu.kkk && _contextmenu.kkk.hide(),
			_contextmenu.last && _contextmenu.last.removeClass("menu-active"),
			_contextmenu.kkk = r,
			_contextmenu.last = n,
			_contextmenu.ppp = t,
			n.addClass("menu-active");
			var s = n.find(".menu"),
			h = i.width() - 1;
			return suby = -5,
			u + i.width() * 2 > o && (h = h - s.width() - i.width() - 6),
			f + n.position().top + s.height() > e && (suby = suby - s.height()),
			s.css({
				left: h,
				top: suby,
				"z-index": _window.zIndex + 2,
				display: "block"
			}),
			r.css({
				display: "block",
				zIndex: _window.zIndex + 1,
				left: h,
				top: suby,
				width: s.outerWidth(),
				height: s.outerHeight()
			}),
			t.find(".menu-item").bind("mouseover",
			function() {
				jQuery(this).addClass("menu-active")
			}),
			t.find(".menu-item").bind("mouseout",
			function() {
				return jQuery(this).removeClass("menu-active"),
				!1
			}),
			!1
		}), n.bind("mouseout",
		function() {
			return n.removeClass("menu-active"),
			r.hide(),
			t.hide(),
			!1
		})) : (n.bind("mouseover",
		function() {
			return _contextmenu.last && _contextmenu.last.removeClass("menu-active"),
			_contextmenu.ppp && _contextmenu.ppp.hide(),
			_contextmenu.kkk && _contextmenu.kkk.hide(),
			jQuery(this).addClass("menu-active"),
			!1
		}), n.bind("mouseout",
		function() {
			jQuery(this).removeClass("menu-active")
		}))
	}),
	u + i.width() > o && (u = u - i.width()),
	f + i.height() > e && (f = f - i.height()),
	i.css({
		left: u,
		top: f
	}),
	jQuery("#shadow").css({
		display: "block",
		zIndex: _window.zIndex,
		left: u,
		top: f,
		width: i.outerWidth(),
		height: i.outerHeight()
	}),
	jQuery(document).bind("mousedown.right_contextmenu",
	function(n) {
		n = n ? n: window.event;
		var t = n.srcElement ? n.srcElement: n.target;
		checkInDom(t, "right_contextmenu") == !1 && (i.hide(), jQuery("#shadow").hide(), i.empty(), jQuery(document).unbind(".right_contextmenu"), _contextmenu.kkk = null, _contextmenu.ppp = null, _contextmenu.last = null)
	})
},
_contextmenu.right_body = function(n, t) {
	var o, r, i, h, s;
	n = n ? n: window.event;
	var u = n.clientX,
	f = n.clientY,
	e = {};
	if (t.indexOf("icosContainer_body_") !== -1) {
		if (t.indexOf("icosContainer_body_sys_") !== -1 && _config.space.self < 2) return;
		jQuery("#right_body").find(".notbody").remove(),
		o = t.replace("icosContainer_body_", ""),
		e = o.indexOf("sys_") !== -1 ? _config.screenList.screenlist_0[o].config: _config.screenList.screenlist_u[o].config
	} else return;
	r = document.getElementById("right_body").innerHTML,
	r = r.replace(/XX/g, u),
	r = r.replace(/YY/g, f),
	r = r.replace(/_container/g, t),
	i = document.getElementById("right_contextmenu") ? jQuery(document.getElementById("right_contextmenu")) : jQuery('<div id="right_contextmenu" class="menu"></div>').appendTo(document.body),
	i.html(r),
	i.find("#menu_icon_iconview_" + e.iconview).attr("src", _THEME_+"/desktop/images/icons/select.png"),
	i.find("#menu_icon_position_" + e.iconposition).attr("src", _THEME_+"/desktop/images/icons/select.png"),
	i.find("#menu_icon_autolist_" + e.autolist).attr("src", _THEME_+"/desktop/images/icons/select.png"),
	_config.space.self < 1 && (i.find(".appmarket").remove(), i.find(".widgetmarket").remove(), i.find(".config").remove(), i.find(".upload").remove(), i.find(".create").remove(), i.find(".autolist").remove(), i.find(".newwidget").remove()),
	_config.Permission("upload", t) && (jQuery.browser.msie ? _config.uploader[t]._createUploadButton(i.find(".upload").get(0)) : i.find(".upload").get(0).onclick = function() {
		_config.uploader[t]._button.getInput().click(),
		i.hide(),
		jQuery("#shadow").hide()
	}),
	i.css({
		"z-index": _window.zIndex + 1
	}),
	i.show(),
	h = document.documentElement.clientWidth,
	s = document.documentElement.clientHeight,
	i.find(">div").each(function() {
		var n = jQuery(this),
		t = n.find(".menu"),
		r;
		t.length ? (r = n.find(".menu-shadow"), n.bind("mouseover",
		function() {
			_contextmenu.ppp && _contextmenu.ppp.hide(),
			_contextmenu.kkk && _contextmenu.kkk.hide(),
			_contextmenu.last && _contextmenu.last.removeClass("menu-active"),
			_contextmenu.kkk = r,
			_contextmenu.last = n,
			_contextmenu.ppp = t,
			n.addClass("menu-active");
			var e = n.find(".menu"),
			o = i.width() - 1;
			return suby = -5,
			u + i.width() * 2 > h && (o = o - e.width() - i.width() - 6),
			f + n.position().top + e.height() > s && (suby = suby - e.height()),
			e.css({
				left: o,
				top: suby,
				"z-index": _window.zIndex + 2,
				display: "block"
			}),
			r.css({
				display: "block",
				zIndex: _window.zIndex + 1,
				left: o,
				top: suby,
				width: e.outerWidth(),
				height: e.outerHeight()
			}),
			t.find(".menu-item").bind("mouseover",
			function() {
				jQuery(this).addClass("menu-active")
			}),
			t.find(".menu-item").bind("mouseout",
			function() {
				return jQuery(this).removeClass("menu-active"),
				!1
			}),
			!1
		}), n.bind("mouseout",
		function() {
			return n.removeClass("menu-active"),
			r.hide(),
			t.hide(),
			!1
		})) : (n.bind("mouseover",
		function() {
			return _contextmenu.last && _contextmenu.last.removeClass("menu-active"),
			_contextmenu.ppp && _contextmenu.ppp.hide(),
			_contextmenu.kkk && _contextmenu.kkk.hide(),
			jQuery(this).addClass("menu-active"),
			!1
		}), n.bind("mouseout",
		function() {
			jQuery(this).removeClass("menu-active")
		}))
	}),
	u + i.width() > h && (u = u - i.width()),
	f + i.height() > s && (f = f - i.height()),
	i.css({
		left: u,
		top: f
	}),
	jQuery("#shadow").css({
		display: "block",
		zIndex: _window.zIndex,
		left: u,
		top: f,
		width: i.outerWidth(),
		height: i.outerHeight()
	}),
	jQuery(document).bind("mousedown.right_contextmenu",
	function(n) {
		n = n ? n: window.event;
		var t = n.srcElement ? n.srcElement: n.target;
		checkInDom(t, "right_contextmenu") == !1 && (i.hide(), jQuery("#shadow").hide(), jQuery(document).unbind(".right_contextmenu"), _contextmenu.kkk = null, _contextmenu.ppp = null, _contextmenu.last = null)
	})
},
_contextmenu.right_folder = function(n, t) {
	var r, i, c, h;
	n = n ? n: window.event;
	var u = n.clientX,
	f = n.clientY,
	o = _window.windows[t],
	s = o.filemanageid.split("-"),
	e = "";
	s[0] == "d" ? e = "icosContainer_body_" + s[1] : s[0] == "f" && (e = "icosContainer_folder_" + s[1]),
	r = document.getElementById("right_folder").innerHTML,
	r = r.replace(/XX/g, u),
	r = r.replace(/YY/g, f),
	r = r.replace(/_filemanageid/g, o.filemanageid),
	r = r.replace(/_container/g, e),
	r = r.replace(/_winid/g, t),
	i = document.getElementById("right_contextmenu") ? jQuery(document.getElementById("right_contextmenu")) : jQuery('<div id="right_contextmenu" class="menu"></div>').appendTo(document.body),
	i.html(r),
	_config.Permission("upload", e) ? jQuery.browser.msie ? _config.uploader[t]._createUploadButton(i.find(".upload").get(0)) : i.find(".upload").get(0).onclick = function() {
		_config.uploader[t]._button.getInput().click(),
		i.hide(),
		jQuery("#shadow").hide()
	}: (i.find(".upload").remove(), i.find(".create").remove(), i.find(".appmarket").remove()),
	i.css({
		"z-index": _window.zIndex + 1
	}),
	i.find(".menu_icon_filemanageview_" + _filemanage.cons[o.filemanageid].view).attr("src", _THEME_+"/desktop/images/icons/select.png"),
	i.find(".menu_icon_filemanagedisp_" + _filemanage.cons[o.filemanageid].disp).attr("src", _THEME_+"/desktop/images/icons/select.png"),
	i.show(),
	c = document.documentElement.clientWidth,
	h = document.documentElement.clientHeight,
	i.find(">div").each(function() {
		var n = jQuery(this),
		t = n.find(".menu"),
		r;
		t.length ? (r = n.find(".menu-shadow"), n.bind("mouseover",
		function() {
			_contextmenu.ppp && _contextmenu.ppp.hide(),
			_contextmenu.kkk && _contextmenu.kkk.hide(),
			_contextmenu.last && _contextmenu.last.removeClass("menu-active"),
			_contextmenu.kkk = r,
			_contextmenu.last = n,
			_contextmenu.ppp = t,
			n.addClass("menu-active");
			var e = n.find(".menu"),
			o = i.width() - 1;
			return suby = -5,
			u + i.width() * 2 > c && (o = o - e.width() - i.width() - 6),
			f + n.position().top + e.height() > h && (suby = suby - e.height()),
			e.css({
				left: o,
				top: suby,
				"z-index": _window.zIndex + 2,
				display: "block"
			}),
			r.css({
				display: "block",
				zIndex: _window.zIndex + 1,
				left: o,
				top: suby,
				width: e.outerWidth(),
				height: e.outerHeight()
			}),
			t.find(".menu-item").bind("mouseover",
			function() {
				jQuery(this).addClass("menu-active")
			}),
			t.find(".menu-item").bind("mouseout",
			function() {
				return jQuery(this).removeClass("menu-active"),
				!1
			}),
			!1
		}), n.bind("mouseout",
		function() {
			return n.removeClass("menu-active"),
			r.hide(),
			t.hide(),
			!1
		})) : (n.bind("mouseover",
		function() {
			return _contextmenu.last && _contextmenu.last.removeClass("menu-active"),
			_contextmenu.ppp && _contextmenu.ppp.hide(),
			_contextmenu.kkk && _contextmenu.kkk.hide(),
			jQuery(this).addClass("menu-active"),
			!1
		}), n.bind("mouseout",
		function() {
			jQuery(this).removeClass("menu-active")
		}))
	}),
	u + i.width() > c && (u = u - i.width()),
	f + i.height() > h && (f = f - i.height()),
	i.css({
		left: u,
		top: f
	}),
	jQuery("#shadow").css({
		display: "block",
		zIndex: _window.zIndex,
		left: u,
		top: f,
		width: i.outerWidth(),
		height: i.outerHeight()
	}),
	jQuery(document).bind("mousedown.right_contextmenu",
	function(n) {
		n = n ? n: window.event;
		var t = n.srcElement ? n.srcElement: n.target;
		checkInDom(t, "right_contextmenu") == !1 && (i.hide(), jQuery("#shadow").hide(), i.empty(), jQuery(document).unbind(".right_contextmenu"), _contextmenu.kkk = null, _contextmenu.ppp = null, _contextmenu.last = null)
	})
},
_contextmenu.other_down = function(n, t) {
	var o, h, e, c, u, f, i, r, l, a, s;
	n = n ? n: window.event,
	o = n.clientX,
	h = n.clientY,
	_contextmenu.other_down_html = document.getElementById("other_down").innerHTML,
	e = _window.windows[t];
	if (!e.filemanageid) return ! 1;
	c = _filemanage.cons[e.filemanageid];
	if (!c) return ! 1;
	u = e.filemanageid.split("-"),
	f = "",
	u[0] == "d" ? f = "icosContainer_body_" + u[1] : u[0] == "f" && (f = "icosContainer_folder_" + u[1]),
	i = document.getElementById("other_down").innerHTML,
	i = i.replace(/XX/g, o),
	i = i.replace(/YY/g, h),
	i = i.replace(/_container/g, f),
	i = i.replace(/_winid/g, t),
	document.getElementById("other_down").innerHTML = i,
	r = jQuery("#other_down"),
	r.addClass(_window.className),
	r.css({
		"z-index": _window.zIndex + 1
	}),
	r.show(),
	l = document.documentElement.clientWidth,
	a = document.documentElement.clientHeight,
	r.find(">div").each(function() {
		var n = jQuery(this);
		n.bind("mouseover",
		function() {
			return jQuery(this).addClass("menu-active"),
			!1
		}),
		n.bind("mouseout",
		function() {
			jQuery(this).removeClass("menu-active")
		})
	}),
	jQuery("#filemanage_other" + t).removeClass("filemanage_other1").addClass("filemanage_other2"),
	s = jQuery("#filemanage_other" + t).offset(),
	jQuery("#other_down").css({
		left: s.left,
		top: s.top + jQuery("#filemanage_other" + t).height()
	}),
	jQuery("#shadow").css({
		display: "block",
		zIndex: _window.zIndex,
		left: o,
		top: h,
		width: r.outerWidth(),
		height: r.outerHeight()
	}),
	jQuery(document).bind("mousedown.other_down",
	function(n) {
		n = n ? n: window.event;
		var i = n.srcElement ? n.srcElement: n.target;
		checkInDom(i, "other_down") == !1 && (r.hide(), jQuery("#shadow").hide(), document.getElementById("other_down").innerHTML = _contextmenu.other_down_html, jQuery(document).unbind(".other_down")),
		jQuery("#filemanage_other" + t).removeClass("filemanage_other2").addClass("filemanage_other1")
	})
},
_contextmenu.paixu_down = function(n, t) {
	var f, r, i, h, c, u;
	n = n ? n: window.event;
	var o = n.clientX,
	s = n.clientY,
	e = _window.windows[t];
	if (!e.filemanageid) return ! 1;
	f = _filemanage.cons[e.filemanageid];
	if (!f) return ! 1;
	r = document.getElementById("paixu_down").innerHTML,
	r = r.replace(/XX/g, o),
	r = r.replace(/YY/g, s),
	r = r.replace(/_filemanageid/g, e.filemanageid),
	r = r.replace(/_winid/g, t),
	i = document.getElementById("right_contextmenu") ? jQuery(document.getElementById("right_contextmenu")) : jQuery('<div id="right_contextmenu" class="menu"></div>').appendTo(document.body),
	i.html(r),
	i.addClass(_window.className),
	i.css({
		"z-index": _window.zIndex + 1
	}),
	i.find(".menu_icon_filemanagedisp_" + f.disp).attr("src", _THEME_+"/desktop/images/icons/select.png"),
	i.show(),
	h = document.documentElement.clientWidth,
	c = document.documentElement.clientHeight,
	i.find(">div").each(function() {
		var n = jQuery(this);
		n.bind("mouseover",
		function() {
			return jQuery(this).addClass("menu-active"),
			!1
		}),
		n.bind("mouseout",
		function() {
			jQuery(this).removeClass("menu-active")
		})
	}),
	jQuery("#filemanage_disp" + t).removeClass("filemanage_disp1").addClass("filemanage_disp2"),
	u = jQuery("#filemanage_disp" + t).offset(),
	jQuery("#right_contextmenu").css({
		left: u.left,
		top: u.top + jQuery("#filemanage_disp" + t).height(),
		width: 100
	}),
	jQuery("#shadow").css({
		display: "block",
		zIndex: _window.zIndex,
		left: o,
		top: s,
		width: i.outerWidth(),
		height: i.outerHeight()
	}),
	jQuery(document).bind("mousedown.right_contextmenu",
	function(n) {
		n = n ? n: window.event;
		var r = n.srcElement ? n.srcElement: n.target;
		checkInDom(r, "right_contextmenu") == !1 && (i.hide(), jQuery("#shadow").hide(), jQuery("#filemanage_disp" + t).removeClass("filemanage_disp2").addClass("filemanage_disp1"), jQuery(document).unbind(".right_contextmenu"))
	})
},
_contextmenu.paixu_down_file = function(n, t) {
	var i, o, s, u;
	n = n ? n: window.event;
	var e = n.clientX,
	f = n.clientY,
	r = document.getElementById("paixu_down_file").innerHTML;
	r = r.replace(/XX/g, e),
	r = r.replace(/YY/g, f),
	r = r.replace(/_winid/g, t),
	i = document.getElementById("right_contextmenu") ? jQuery(document.getElementById("right_contextmenu")) : jQuery('<div id="right_contextmenu" class="menu"></div>').appendTo(document.body),
	i.html(r),
	i.addClass(_window.className),
	i.css({
		"z-index": 99999
	}),
	i.find(".menu_icon_filemanagedisp_" + _file.disp).attr("src", _THEME_+"/desktop/images/icons/select.png"),
	i.show(),
	o = document.documentElement.clientWidth,
	s = document.documentElement.clientHeight,
	i.find(">div").each(function() {
		var n = jQuery(this);
		n.bind("mouseover",
		function() {
			return jQuery(this).addClass("menu-active"),
			!1
		}),
		n.bind("mouseout",
		function() {
			jQuery(this).removeClass("menu-active")
		})
	}),
	jQuery("#filemanage_disp" + t).removeClass("filemanage_disp1").addClass("filemanage_disp2"),
	u = jQuery("#filemanage_disp" + t).offset(),
	jQuery("#right_contextmenu").css({
		left: u.left,
		top: u.top + jQuery("#filemanage_disp" + t).height(),
		width: 100
	}),
	jQuery("#shadow").css({
		display: "block",
		zIndex: _window.zIndex,
		left: e,
		top: f,
		width: i.outerWidth(),
		height: i.outerHeight()
	}),
	jQuery(document).bind("mousedown.right_contextmenu",
	function(n) {
		n = n ? n: window.event;
		var r = n.srcElement ? n.srcElement: n.target;
		checkInDom(r, "right_contextmenu") == !1 && (i.hide(), jQuery("#shadow").hide(), jQuery("#filemanage_disp" + t).removeClass("filemanage_disp2").addClass("filemanage_disp1"), jQuery(document).unbind(".right_contextmenu"))
	})
},
_contextmenu.right_ico = function(n, t, i) {
	var r, u, h, s;
	n = n ? n: window.event;
	var e = n.clientX,
	f = n.clientY,
	o = _config.sourcedata.icos[t];
	r = document.getElementById("right_contextmenu") ? jQuery(document.getElementById("right_contextmenu")) : jQuery('<div id="right_contextmenu" class="menu"></div>').appendTo(document.body),
	u = document.getElementById("right_ico").innerHTML,
	u = u.replace(/XX/g, e),
	u = u.replace(/YY/g, f),
	u = u.replace(/_icoid/g, t),
	u = u.replace(/_imageurl/g, o.url),
	u = u.replace(/_filemanageid/g, i),
	r.html(u),
	(o.notdelete > 0 || _config.sourcedata.icos[t].uid != _config.space.uid && _config.space.self < 2) && (r.find("#right_ico_delete").remove(), r.find("#right_ico_rename").remove(), r.find("#right_ico_edit").remove(), r.find(".delete").remove(), r.find(".rename").remove(), r.find(".edit").remove(), r.find(".setwidget").remove()),
	o.type != "attach" ? r.find("#right_ico_attach").remove() : _config.Permission("open", "", t) || r.find("#right_ico_attach").remove(),
	(o.type == "attach" || o.type == "folder") && r.find(".setwidget").remove(),
	o.type != "image" && r.find(".setwallpaper").remove(),
	_config.space.self < 1 && (r.find(".delete").remove(), r.find(".rename").remove(), r.find(".edit").remove(), r.find(".setwallpaper").remove(), r.find(".setwidget").remove()),
	_config.Permission("open", "", t) || r.find(".open").remove();
	if (r.find(".menu-item").length < 1) return ! 1;
	r.addClass(_window.className),
	r.css({
		"z-index": _window.zIndex + 1
	}),
	r.show(),
	h = document.documentElement.clientWidth,
	s = document.documentElement.clientHeight,
	r.find(">div").each(function() {
		var n = jQuery(this),
		t = n.find(".menu"),
		i;
		t.length ? (i = n.find(".menu-shadow"), n.bind("mouseover",
		function() {
			_contextmenu.ppp && _contextmenu.ppp.hide(),
			_contextmenu.kkk && _contextmenu.kkk.hide(),
			_contextmenu.last && _contextmenu.last.removeClass("menu-active"),
			_contextmenu.kkk = i,
			_contextmenu.last = n,
			_contextmenu.ppp = t,
			n.addClass("menu-active");
			var u = n.find(".menu"),
			o = r.width() - 1;
			return suby = -5,
			e + r.width() * 2 > h && (o = o - u.width() - r.width() - 6),
			f + n.position().top + u.height() > s && (suby = suby - u.height() + n.height()),
			u.css({
				left: o,
				top: suby,
				"z-index": _window.zIndex + 2,
				display: "block"
			}),
			i.css({
				display: "block",
				zIndex: _window.zIndex + 1,
				left: o,
				top: suby,
				width: u.outerWidth(),
				height: u.outerHeight()
			}),
			t.find(".menu-item").bind("mouseover",
			function() {
				jQuery(this).addClass("menu-active")
			}),
			t.find(".menu-item").bind("mouseout",
			function() {
				return jQuery(this).removeClass("menu-active"),
				!1
			}),
			!1
		}), n.bind("mouseout",
		function() {
			return n.removeClass("menu-active"),
			i.hide(),
			t.hide(),
			!1
		})) : (n.bind("mouseover",
		function() {
			return _contextmenu.last && _contextmenu.last.removeClass("menu-active"),
			_contextmenu.ppp && _contextmenu.ppp.hide(),
			_contextmenu.kkk && _contextmenu.kkk.hide(),
			jQuery(this).addClass("menu-active"),
			!1
		}), n.bind("mouseout",
		function() {
			jQuery(this).removeClass("menu-active")
		}))
	}),
	e + r.width() > h && (e = e - r.width()),
	f + r.height() > s && (f = f - r.height()),
	r.css({
		left: e,
		top: f
	}),
	jQuery("#shadow").css({
		display: "block",
		zIndex: _window.zIndex,
		left: e,
		top: f,
		width: r.outerWidth(),
		height: r.outerHeight()
	}),
	jQuery(document).bind("mousedown.right_contextmenu",
	function(n) {
		n = n ? n: window.event;
		var t = n.srcElement ? n.srcElement: n.target;
		checkInDom(t, "right_contextmenu") == !1 && (r.hide(), jQuery("#shadow").hide(), r.empty(), jQuery(document).unbind(".right_contextmenu"), _contextmenu.kkk = null, _contextmenu.ppp = null, _contextmenu.last = null)
	})
},
_contextmenu.task_right_Ico = function(n, t) {
	var r, i, e, o;
	n = n ? n: window.event;
	var f = n.clientX,
	u = n.clientY,
	s = _task.icos[t];
	_contextmenu.task_right_Ico_html = document.getElementById("task_right_Ico").innerHTML,
	r = document.getElementById("task_right_Ico").innerHTML,
	r = r.replace(/XX/g, f),
	r = r.replace(/YY/g, u),
	r = r.replace(/_taskid/g, t),
	i = document.getElementById("right_contextmenu") ? jQuery(document.getElementById("right_contextmenu")) : jQuery('<div id="right_contextmenu" class="menu"></div>').appendTo(document.body),
	i.html(r),
	i.css({
		"z-index": _window.zIndex + 1
	}),
	i.show(),
	e = document.documentElement.clientWidth,
	o = document.documentElement.clientHeight,
	i.find(">div").each(function() {
		var n = jQuery(this),
		t = n.find(".menu"),
		r;
		t.length ? (r = n.find(".menu-shadow"), n.bind("mouseover",
		function() {
			_contextmenu.ppp && _contextmenu.ppp.hide(),
			_contextmenu.kkk && _contextmenu.kkk.hide(),
			_contextmenu.last && _contextmenu.last.removeClass("menu-active"),
			_contextmenu.kkk = r,
			_contextmenu.last = n,
			_contextmenu.ppp = t,
			n.addClass("menu-active");
			var s = n.find(".menu"),
			h = i.width() - 1;
			return suby = -5,
			f + i.width() * 2 > e && (h = h - s.width() - i.width() - 6),
			u + n.position().top + s.height() > o && (suby = suby - s.height()),
			s.css({
				left: h,
				top: suby,
				"z-index": _window.zIndex + 2,
				display: "block"
			}),
			r.css({
				display: "block",
				zIndex: _window.zIndex + 1,
				left: h,
				top: suby,
				width: s.outerWidth(),
				height: s.outerHeight()
			}),
			t.find(".menu-item").bind("mouseover",
			function() {
				jQuery(this).addClass("menu-active")
			}),
			t.find(".menu-item").bind("mouseout",
			function() {
				return jQuery(this).removeClass("menu-active"),
				!1
			}),
			!1
		}), n.bind("mouseout",
		function() {
			return n.removeClass("menu-active"),
			r.hide(),
			t.hide(),
			!1
		})) : (n.bind("mouseover",
		function() {
			return _contextmenu.last && _contextmenu.last.removeClass("menu-active"),
			_contextmenu.ppp && _contextmenu.ppp.hide(),
			_contextmenu.kkk && _contextmenu.kkk.hide(),
			jQuery(this).addClass("menu-active"),
			!1
		}), n.bind("mouseout",
		function() {
			jQuery(this).removeClass("menu-active")
		}))
	}),
	f + i.width() > e && (f = f - i.width()),
	u + i.height() > o && (u = u - i.height()),
	i.css({
		left: f,
		top: u
	}),
	jQuery("#shadow").css({
		display: "block",
		zIndex: _window.zIndex,
		left: f,
		top: u,
		width: i.outerWidth(),
		height: i.outerHeight()
	}),
	jQuery(document).bind("mousedown.right_contextmenu",
	function(n) {
		n = n ? n: window.event;
		var t = n.srcElement ? n.srcElement: n.target;
		checkInDom(t, "right_contextmenu") == !1 && (i.hide(), jQuery("#shadow").hide(), i.empty(), jQuery(document).unbind(".right_contextmenu"), _contextmenu.kkk = null, _contextmenu.ppp = null, _contextmenu.last = null)
	})
},
_ico.ClassName = "Icoblock",
_ico._defaultbgcolor = "#eee",
_ico.padding = 5,
_ico.Timer = 10,
_ico.icos = {},
_ico.wIndex = 1e3,
_ico.zIndex = 1e3,
_ico.clientWidth = 0,
_ico.clientHeight = 0,
_ico.onmousemove = null,
_ico.onmouseup = null,
_ico.tach = null,
_ico.onselectstart = 1,
_ico.divwidth = 70,
_ico.divheight = 70,
_ico.width = 50,
_ico.height = 50,
_ico.paddingLeft = 10,
_ico.paddingTop = 10,
_ico.Version = "dsk! js 1.0",
_ico.getPicIcos = function(n) {
	var s = {},
	u = [],
	o = 0,
	t,
	i,
	r,
	f,
	e;
	if (_ico.icos[n]) {
		i = _ico.icos[n];
		if (!i) return ! 1;
		i.container.indexOf("icosContainer_body_sys_") !== -1 ? (r = i.container.replace("icosContainer_body_", ""), t = _config.screenList.screenlist_0[r].icos) : i.container.indexOf("icosContainer_body_") !== -1 ? (r = i.container.replace("icosContainer_body_", ""), t = _config.screenList.screenlist_u[r].icos) : i.container.indexOf("icosContainer_folder_") !== -1 && (f = i.container.replace("icosContainer_folder_", ""), t = _config.sourcedata.folder[f].ids)
	} else {
		i = _config.sourcedata.icos[n];
		if (!i) return ! 1;
		for (r in _config.screenList.screenlist_u) if (in_array(n, _config.screenList.screenlist_u[r].icos)) {
			t = _config.screenList.screenlist_u[r].icos;
			break
		}
		for (r in _config.screenList.screenlist_0) if (in_array(n, _config.screenList.screenlist_0[r].icos)) {
			t = _config.screenList.screenlist_0[r].icos;
			break
		}
		for (f in _config.sourcedata.folder) if (in_array(n, _config.sourcedata.folder[f].ids)) {
			t = _config.sourcedata.folder[f].ids;
			break
		}
	}
	for (id in t) _config.sourcedata.icos[t[id]].type == "image" && (u.length < 1 ? u = [t[id]] : u.push(t[id]));
	for (e = 0; e < u.length; e++) if (u[e] == n) {
		o = e;
		break
	}
	return u.length < 1 && (u = [n], o = 0),
	s.pos = o,
	s.icos = u,
	s
},
_ico.geticoidFromfid = function(n) {
	for (var t in _config.sourcedata.icos) if (_config.sourcedata.icos[t].type == "folder" && _config.sourcedata.icos[t].oid == n) return t
},
_ico.isParentFid = function(n, t) {
	var i = !1,
	r = _config.sourcedata.folder[t];
	return i = parseInt(r.pfid) == n ? !0 : parseInt(r.pfid) > 0 ? _ico.isParentFid(n, r.pfid) : !1
},
_ico.getTopFid = function(n) {
	var r = [],
	u = _config.sourcedata.folder[n],
	t,
	i;
	if (!u) return ! 1;
	if (parseInt(u.pfid) == 0) r.push(n);
	else {
		r.push(u.fid),
		t = _ico.getTopFid(u.pfid);
		if (t) for (i = 0; i < t.length; i++) r.push(t[i])
	}
	return r
},
_ico.createIco = function(n, t, i) {
	var r = n.container,
	f, o, s, c, e, h, u;
	r || (r = "icosContainer_body_" + _config.currentDesktop),
	delete n.container,
	f = n,
	_config.sourceids.icos.push(n.icoid),
	_config.sourcedata.icos[n.icoid] = f,
	r.indexOf("icosContainer_body_") !== -1 ? (o = r.replace("icosContainer_body_", ""), s = o.indexOf("sys_") !== -1 ? _config.screenList.screenlist_0[o].icos.push(n.icoid) : _config.screenList.screenlist_u[o].icos.push(n.icoid), c = i ? _ico.CIco(n.icoid, r, s - 1) : _ico.CIco(n.icoid, r, s - 1)) : r.indexOf("icosContainer_folder_") !== -1 && (e = r.replace("icosContainer_folder_", "") * 1, _config.sourcedata.folder[e].ids || (_config.sourcedata.folder[e].ids = []), _config.sourcedata.folder[e].ids.push(n.icoid), h = _ico.getTopFid(e), u = _window.windows["_W_" + h[h.length - 1]], u && u.filemanageid && (t && _filemanage.cons[u.filemanageid] && _filemanage.cons[u.filemanageid].odata[t.icoid] ? _filemanage.cons[u.filemanageid].reCreateIcos(f, t) : _filemanage.cons[u.filemanageid].CreateIcos(f))),
	_window.windows._W_sys_filemanage && (t && _filemanage.cons[_window.windows._W_sys_filemanage.filemanageid].odata && _filemanage.cons[_window.windows._W_sys_filemanage.filemanageid].odata[t.icoid] ? _filemanage.cons[_window.windows._W_sys_filemanage.filemanageid].reCreateIcos(f, t) : _filemanage.cons[_window.windows._W_sys_filemanage.filemanageid].CreateIcos(f))
},
_ico.createFolder = function(n, t) {
	var u, f, r, i;
	_config.sourceids.icos.push(n.icoarr.icoid),
	_config.sourcedata.icos[n.icoarr.icoid] = {},
	_config.sourcedata.icos[n.icoarr.icoid] = n.icoarr,
	_config.sourceids.folder || (_config.sourceids.folder = []),
	_config.sourceids.folder.push(n.folderarr.fid),
	_config.sourcedata.folder || (_config.sourcedata.folder = {}),
	_config.sourcedata.folder[n.folderarr.fid] = n.folderarr;
	if (t.indexOf("icosContainer_body_") !== -1) {
		u = t.replace("icosContainer_body_", ""),
		f = u.indexOf("sys_") !== -1 ? _config.screenList.screenlist_0[u].icos.push(n.icoarr.icoid) : _config.screenList.screenlist_u[u].icos.push(n.icoarr.icoid),
		_config.saveItem.screenlist = 1,
		_ico.CIco(n.icoarr.icoid, t, f - 1);
		for (i in _filemanage.cons) i.indexOf("d-" + u) === 0 && _filemanage.cons[i].CreateIcos(n.icoarr)
	} else if (t.indexOf("icosContainer_folder_") !== -1) {
		r = t.replace("icosContainer_folder_", "") * 1,
		_config.sourcedata.folder[r].ids || (_config.sourcedata.folder[r].ids = []),
		_config.sourcedata.folder[r].ids.push(n.icoarr.icoid),
		_config.saveItem.folder.push(r);
		for (i in _filemanage.cons) i.indexOf("f-" + r) === 0 && _filemanage.cons[i].CreateIcos(n.icoarr)
	}
},
_ico.resizeDesktops = function() {
	var t = document.getElementById("_bodys"),
	n;
	for (n in _navbar.navbars) document.getElementById("icosContainer_body_" + n) && (document.getElementById("icosContainer_body_" + n).style.cssText = "width:" + (_config.screenWidth - _navbar.navbars[n].marginleft - _navbar.navbars[n].marginright) + "px; height:" + (_config.screenHeight - _navbar.navbars[n].margintop - _navbar.navbars[n].marginbottom) + "px;left:" + _navbar.navbars[n].marginleft + "px;top:" + _navbar.navbars[n].margintop + "px;", _ico.refreshList("icosContainer_body_" + n));
	jQuery("#_bodys .desktop").each(function() {
		var n = this.id.replace("_body_", "");
		n != _config.currentDesktop && jQuery(this).css("left", -5e3)
	}),
	_config.setDockSize()
},
_ico.NewIco = function(n, t) {
	switch (n) {
	case "Newfolder":
		_ajax.newFolder(t);
		break;
	case "Newlink":
		showWindow("addnewlink", _config.ajaxurl + "&do=newlink&container=" + t + "&uid=" + _config.uid + "&ukey=" + _config.ukey, "get", 0);
		break;
	case "NewDzzDoc":
		_ajax.newDoc(t, "dzzdoc");
		break;
	case "NewTxt":
		_ajax.newDoc(t, "txt")
	}
},
_ico.Arrange = function(n, t, i) {
	var r, f, u, e;
	switch (i) {
	case "iconview":
		t.indexOf("icosContainer_body_") !== -1 && (r = t.replace("icosContainer_body_", ""), _navbar.navbars[r].iconview = parseInt(n), r.indexOf("sys_") === -1 ? _config.screenList.screenlist_u[r].config.iconview = parseInt(n) : _config.screenList.screenlist_0[r].config.iconview = parseInt(n), _config.saveItem.screenlist = 1),
		jQuery("#right_contextmenu .menu-icon-iconview").each(function() {
			this.src = this.id == "menu_icon_iconview_" + n ? _THEME_+"/desktop/images/icons/select.png": _THEME_+"/desktop/images/icons/notselect.png"
		}),
		_ico.reCIcolist(t);
		break;
	case "position":
		if (t.indexOf("icosContainer_body_") !== -1) r = t.replace("icosContainer_body_", ""),
		_navbar.navbars[r].iconposition = parseInt(n),
		_config.screenList.screenlist_u[r] ? _config.screenList.screenlist_u[r].config.iconposition = parseInt(n) : _config.screenList.screenlist_0[r] && (_config.screenList.screenlist_0[r].config.iconposition = parseInt(n)),
		_config.saveItem.screenlist = 1;
		else if (t.indexOf("icosContainer_folder_") !== -1) return;
		jQuery("#right_contextmenu .menu-icon-position").each(function() {
			this.src = this.id == "menu_icon_position_" + n ? _THEME_+"/desktop/images/icons/select.png": _THEME_+"/desktop/images/icons/notselect.png"
		}),
		_ico.refreshList(t);
		break;
	case "autolist":
		if (t.indexOf("icosContainer_body_") !== -1) {
			r = t.replace("icosContainer_body_", ""),
			_navbar.navbars[r].autolist = parseInt(n);
			if (r.indexOf("sys_") === -1) {
				for (_config.screenList.screenlist_u[r].config.autolist = parseInt(n), f = _config.screenList.screenlist_u[r].icos, u = 0; u < f.length; u++) if (_config.iconpositions_u[f[u]]) {
					for (e in _config.iconpositions_u[f[u]]) delete _config.iconpositions_u[f[u]][e];
					delete _config.iconpositions_u[f[u]]
				}
			} else for (_config.screenList.screenlist_0[r].config.autolist = parseInt(n), f = _config.screenList.screenlist_0[r].icos, u = 0; u < f.length; u++) if (_config.iconpositions_0[f[u]]) {
				for (e in _config.iconpositions_0[f[u]]) delete _config.iconpositions_0[f[u]][e];
				delete _config.iconpositions_0[f[u]]
			}
			_config.saveItem.iconpositions = 1,
			_config.saveItem.screenlist = 1
		}
		jQuery("#right_contextmenu .menu-icon-autolist").each(function() {
			this.src = this.id == "menu_icon_autolist_" + n ? _THEME_+"/desktop/images/icons/select.png": _THEME_+"/desktop/images/icons/notselect.png"
		}),
		_ico.refreshList(t)
	}
	_config.saveTimer && window.clearTimeout(_config.saveTimer),
	_config.saveTimer = setTimeout(function() {
		_config.sendConfig()
	},
	_config.savetime)
},
_ico.deleteFolderIcos = function(n) {
	var i, t, r;
	jQuery("#" + n).empty();
	for (i in _ico.icos) {
		t = _ico.icos[i];
		if (t.container == n) {
			for (r in t) delete t[r];
			delete _ico.icos[i]
		}
	}
},
_ico.reCIcolist = function(n) {
	var f, u, e, i, r, t;
	jQuery("#" + n + " .Icoblock").remove();
	for (f in _ico.icos) {
		u = _ico.icos[f];
		if (u.container == n) {
			for (e in u) delete u[e];
			delete _ico.icos[f]
		}
	}
	for (n.indexOf("icosContainer_body_") !== -1 && (i = n.replace("icosContainer_body_", ""), r = i.indexOf("sys_") !== -1 ? _config.screenList.screenlist_0[i].icos: _config.screenList.screenlist_u[i].icos), t = 0; t < r.length; t++) _ico.CIco(r[t], n, t)
},
_ico.refreshList = function(n) {
	var r, e, u, i, t, f;
	for (n.indexOf("icosContainer_body_") !== -1 && (r = n.replace("icosContainer_body_", ""), e = _navbar.navbars[r].autolist, u = r.indexOf("sys_") !== -1 ? _config.screenList.screenlist_0[r].icos: _config.screenList.screenlist_u[r].icos), i = 0; i < u.length; i++) {
		t = _ico.icos[u[i]];
		if (!t) continue;
		t.pos = i,
		f = t.getpos(i, n, 2),
		t.left = f[0],
		t.top = f[1],
		t.board.style.left = t.left + "px",
		t.board.style.top = t.top + "px"
	}
},
_ico.prototype.getpos = function(n, t, i) {
	var y, b, p, k, r, f, u;
	y = t.indexOf("icosContainer_body_sys_") !== -1 ? _config.iconpositions_0 || {}: _config.iconpositions_u || {},
	b = jQuery("#" + t).width(),
	p = jQuery("#" + t).height();
	if (t == "_dock") return r = [0, 0],
	r[0] = n * this.divwidth,
	r[1] = 0,
	r;
	var c = this.divheight * 1,
	l = this.divwidth * 1,
	h = this.paddingtop * 1,
	s = this.paddingleft * 1,
	g = 1,
	d = 0,
	w = 0;
	t.indexOf("icosContainer_body_") !== -1 && (k = t.replace("icosContainer_body_", ""), g = _navbar.navbars[k].autolist, d = 0, w = _navbar.navbars[k] ? _navbar.navbars[k].iconposition * 1 : 0);
	if (y[this.id] && g < 1) return r = [0, 0],
	r[0] = isNaN(parseInt(y[this.id][0])) ? 0 : parseInt(y[this.id][0]),
	r[1] = isNaN(parseInt(y[this.id][1])) ? 0 : parseInt(y[this.id][1]),
	r[0] > b - this.divwidth ? r[0] = b - this.divwidth: r[0] < 0 && (r[0] = 0),
	r[1] > p - this.divheight ? r[1] = p - this.divheight: r[1] < 0 && (r[1] = 0),
	r;
	var o = Math.floor(p / (c + h)),
	e = Math.floor(b / (l + s)),
	a = b - e * (l + s),
	v = p - o * (c + h);
	i != 2 && (i = g);
	if (i > 0) {
		if (d) {
			r = [0, 0];
			switch (w) {
			case 0:
				u = Math.floor(n / e),
				f = n - u * e,
				r[0] = f * (l + s),
				r[1] = u * (c + h);
				break;
			case 2:
				u = Math.floor(n / e),
				f = e - (n - u * e) - 1,
				r[0] = f * (l + s),
				r[1] = u * (c + h) + v;
				break;
			case 1:
				u = o - Math.floor(n / e) - 1,
				f = n - Math.floor(n / e) * e,
				r[0] = f * (l + s) + a,
				r[1] = u * (c + h);
				break;
			case 3:
				u = o - Math.floor(n / e) - 1,
				f = e - (n - Math.floor(n / e) * e) - 1,
				r[0] = f * (l + s) + a,
				r[1] = u * (c + h) + v
			}
			return r[0] += 10,
			r[1] += 10,
			r
		}
		r = [0, 0];
		switch (w) {
		case 0:
			n > e * o - 1 ? (f = e - 1, u = o - 1) : (f = Math.floor(n / o), u = n - f * o),
			r[0] = f * (l + s),
			r[1] = u * (c + h);
			break;
		case 2:
			n > e * o - 1 ? (f = e - 1, u = 0) : (f = Math.floor(n / o), u = o - (n - f * o) - 1),
			r[0] = f * (l + s),
			r[1] = u * (c + h) + v;
			break;
		case 1:
			n > e * o - 1 ? (f = 0, u = o - 1) : (f = e - Math.floor(n / o) - 1, u = n - Math.floor(n / o) * o),
			r[0] = f * (l + s) + a,
			r[1] = u * (c + h);
			break;
		case 3:
			n > e * o - 1 ? (f = 0, u = 0) : (f = e - Math.floor(n / o) - 1, u = o - (n - Math.floor(n / o) * o) - 1),
			r[0] = f * (l + s) + a,
			r[1] = u * (c + h) + v
		}
		return r
	}
	if (d) switch (w) {
	case 0:
		for (r = [10, 10], u = 0; u < o; u++) for (f = 0; f < e; f++) if (_ico.getposition(t, f, u, l, c, s, h)) return r[0] = f * (l + s) + 10,
		r[1] = u * (c + h) + 10,
		r;
		return r;
	case 2:
		for (r = [10, (o - 1) * (c + h) + 10], u = 0; u < e; u++) for (f = o - 1; f >= 0; f--) if (_ico.getposition(t, f, u, l, c, s, h)) return r[0] = f * (l + s) + 10,
		r[1] = u * (c + h) + v + 10,
		r;
		return r;
	case 1:
		for (r = [(e - 1) * (l + s) + 10, 10], u = e - 1; u >= 0; u--) for (f = 0; f < o; f++) if (_ico.getposition(t, f, u, l, c, s, h)) return r[0] = f * (l + s) + a + 10,
		r[1] = u * (c + h) + 10,
		r;
		return r;
	case 3:
		for (r = [(e - 1) * (l + s) + 10, (o - 1) * (c + h) + 10], u = e - 1; u >= 0; u--) for (f = o - 1; f >= 0; f--) if (_ico.getposition(t, f, u, l, c, s, h)) return r[0] = f * (l + s) + a + 10,
		r[1] = u * (c + h) + v + 10,
		r;
		return r
	} else switch (w) {
	case 0:
		for (r = [0, 0], f = 0; f < e; f++) for (u = 0; u < o; u++) if (_ico.getposition(t, f, u, l, c, s, h)) return r[0] = f * (l + s),
		r[1] = u * (c + h),
		r;
		return r;
	case 2:
		for (r = [0, (o - 1) * (c + h)], f = 0; f < e; f++) for (u = o - 1; u >= 0; u--) if (_ico.getposition(t, f, u, l, c, s, h)) return r[0] = f * (l + s),
		r[1] = u * (c + h) + v,
		r;
		return r;
	case 1:
		for (r = [(e - 1) * (l + s), 0], f = e - 1; f >= 0; f--) for (u = 0; u < o; u++) if (_ico.getposition(t, f, u, l, c, s, h)) return r[0] = f * (l + s) + a,
		r[1] = u * (c + h),
		r;
		return r;
	case 3:
		for (r = [(e - 1) * (l + s), (o - 1) * (c + h)], f = e - 1; f >= 0; f--) for (u = o - 1; u >= 0; u--) if (_ico.getposition(t, f, u, l, c, s, h)) return r[0] = f * (l + s) + a,
		r[1] = u * (c + h) + v,
		r;
		return r
	}
},
_ico.getposition = function(n, t, i, r, u, f, e) {
	var s, o;
	for (s in _ico.icos) {
		o = _ico.icos[s];
		if (o.container == n) {
			if (o.left >= t * (r + f) && o.left < (t + 1) * (r + f) && o.top > i * (u + e) && o.top < (i + 1) * (u + e)) return ! 1;
			if (o.left >= t * (r + f) && o.left < (t + 1) * (r + f) && o.top + o.divheight > i * (u + e) && o.top + o.divheight < (i + 1) * (u + e)) return ! 1;
			if (o.left + o.divwidth > t * (r + f) && o.left + o.divwidth < (t + 1) * (r + f) && o.top > i * (u + e) && o.top < (i + 1) * (u + e)) return ! 1;
			if (o.left + o.divwidth >= t * (r + f) && o.left + o.bodyWidth < t * (r + f) && o.top + o.height > i * (u + e) && o.top + o.height < (i + 1) * (u + e)) return ! 1
		}
	}
	return ! 0
},
_ico.stack_ico = {},
_ico.setstack = function() {
	var u = _config.screenList.screenlist_0,
	r, n, i, t;
	for (n in u) {
		if (u[n].icos.length == 0) continue;
		for (i = [], t = 0; t < u[n].icos.length; t++) i.push({
			icoid: u[n].icos[t],
			container: "icosContainer_body_" + n,
			pos: t
		});
		_ico.stack_ico[n] = i
	}
	r = _config.screenList.screenlist_u;
	for (n in r) {
		if (r[n].icos.length == 0) continue;
		for (i = [], t = 0; t < r[n].icos.length; t++) i.push({
			icoid: r[n].icos[t],
			container: "icosContainer_body_" + n,
			pos: t
		});
		_ico.stack_ico[n] = i
	}
	_ico.CIcolist_current(),
	window.setTimeout(stack_run, 50)
},
_ico.CIcolist_current = function() {
	var n, t;
	if (!_ico.stack_ico[_config.currentDesktop]) return;
	for (n = 0; n < _ico.stack_ico[_config.currentDesktop].length; n++) t = _ico.CIco(_ico.stack_ico[_config.currentDesktop][n].icoid, _ico.stack_ico[_config.currentDesktop][n].container, _ico.stack_ico[_config.currentDesktop][n].pos),
	t.defaultopen > 0 && _ico.Open(t.id);
	delete _ico.stack_ico[_config.currentDesktop]
},
_ico.CIcolist = function() {
	for (var i = _config.dockList,
	t, n = 0; n < i.length; n++) t = _ico.CIco(i[n], "_dock", n),
	t.defaultopen > 0 && _ico.Open(t.id);
	_config.setDockSize(),
	jQuery("#loading_info").fadeOut("slow",
	function() {
		_ico.setstack()
	})
},
_ico.reCIco = function(n) {
	var u, t, i;
	if (_ico.icos[n]) {
		var f = _ico.icos[n].container,
		e = _ico.icos[n].pos,
		r = [_ico.icos[n].left, _ico.icos[n].top];
		jQuery(_ico.icos[n].board).remove();
		for (u in _ico.icos[n]) delete _ico.icos[n][u];
		delete _ico.icos[n],
		t = new _ico(n, f, e),
		r && (t.left = r[0], t.top = r[1], t.nopos = 1),
		t.img.substr(t.img.length - 4) == ".png" && (t.ispng = !0),
		t.CreatIco()
	}
	for (i in _filemanage.cons) _filemanage.cons[i].data[n] && _filemanage.cons[i].reCreateIcos(_config.sourcedata.icos[n])
},
_ico.CIco = function(n, t, i, r) {
	var f, u;
	if (_ico.icos[n]) {
		jQuery(_ico.icos[n].board).remove();
		for (f in _ico.icos[n]) delete _ico.icos[n][f];
		delete _ico.icos[n]
	}
	return u = new _ico(n, t, i),
	r && (u.left = r[0], u.top = r[1], u.nopos = 1),
	u.ispng = u.img.substr(u.img.length - 4) == ".png" ? !0 : !1,
	u.CreatIco(),
	u
},
_ico.image_resize = function(n, t, i, r) {
	var e = jQuery(n).width(),
	o = jQuery(n).height(),
	f,
	u;
	e / o > 1 ? (u = t, f = u * o / e) : (f = i, u = f * e / o),
	n.style.width = u + "px",
	n.style.height = f + "px",
	ieVersion > 0 && ieVersion < 7 && r && fixpng(n),
	n.style.display = "block"
},
_ico.prototype.CreatIco = function() {
	var i = this,
	o = document.createDocumentFragment(),
	n,
	t,
	r,
	u,
	f,
	e;
	this.board = document.createElement("div"),
	this.board.className = this.className,
	this.board.style.position = "absolute",
	this.board.id = "icon_" + this.id,
	this.board.style.float = "left",
	this.board.style.zIndex = this.zIndex,
	o.appendChild(this.board),
	n = "";
	switch (this.type) {
	case "image":
		n = "imageclass",
		t = '<img  class="' + n + '" src="' + this.img + '" style="display:none;" title="' + this.text + '" onload="_ico.image_resize(this,' + this.width + "," + this.height + "," + this.ispng + ');">';
		break;
	case "app":
		n = "radius",
		t = '<img  class="' + n + '" src="' + this.img + '" style="display:none;" title="' + this.text + '" onload="_ico.image_resize(this,' + this.width + "," + this.height + "," + this.ispng + ');">';
		break;
	case "video":
		document.getElementById("videocss_loaded").className = "videoclass" + this.width + "_" + this.height,
		parseInt(jQuery("#videocss_loaded").css("width")) > 1 ? (n = "videoclass" + this.width + "_" + this.height, t = '<img class="' + n + '" src="' + this.img + '"  title="' + this.text + '" >') : t = '<img  class="videoclass" src="' + this.img + '" style="display:none;" title="' + this.text + '" onload="_ico.image_resize(this,' + this.width + "," + this.height + "," + this.ispng + ');">';
		break;
	default:
		n = "radius",
		t = '<img  class="' + n + '" src="' + this.img + '" style="display:none;" title="' + this.text + '" onload="_ico.image_resize(this,' + this.width + "," + this.height + "," + this.ispng + ');">'
	}
	r = "",
	this.container != "_dock" && (u = "IcoText", this.container.indexOf("icosContainer_folder_") !== -1 && (u = "IcoText_folder"), r = this.align ? '<td align="left" valign="middle"><div id="text_' + this.id + '" class="IcoText_div" style="width:' + (this.divwidth - this.width - _ico.padding * 2 - 25) + 'px;"><a class="' + u + '" >' + mb_cutstr_nohtml(this.text, this.iconview.textlength) + "</a></div></td>": '<tr height="35px"><td align="center" valign="middle"><div id="text_' + this.id + '" class="IcoText_div" style="width:' + (this.divwidth - 15) + 'px;height:35px;"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td valign="middle" align="center"><a class="' + u + '">' + mb_cutstr_nohtml(this.text, this.iconview.textlength) + "</a></td></tr?</table></div></td></tr>"),
	this.board.innerHTML = this.align ? '<table cellpadding="0" cellspacing="0"  width="' + this.divwidth + '" height="' + this.divheight + '" ><tr> <td  align="center" valign="middle" style="overflow:hidden;" id="html_' + this.id + '" width="' + (this.width + _ico.padding * 2 + 15) + '" >' + t + "</td>  " + r + "</tr></table>": '<table cellpadding="0" cellspacing="0"  width="' + this.divwidth + '" height="' + this.divheight + '" style="table-layout:fixed;"><tr>    <td  align="center" valign="middle"  style="overflow:hidden;" id="html_' + this.id + '" height="' + (this.divheight - 45) + 'px">' + t + "</td></tr>  " + r + "</table>",
	i = this,
	this.icoblank = document.createElement("div"),
	this.icoblank.style.position = "absolute",
	this.icoblank.className = "icoblank",
	this.icoblank.title = this.text,
	this.board.appendChild(this.icoblank),
	this.icoblank.style.cssText = "position:absolute;;left:0px;top:0px; background:url('"+_THEME_+"/desktop/images/b.gif');width:" + this.divwidth + "px; height:" + this.divheight + "px;z-index:" + (this.zIndex + 1),
	this.board_background = document.createElement("div"),
	this.board_background.className = "backgound_radius",
	this.board.appendChild(this.board_background),
	this.board_background.style.cssText = "position:absolute;left:0px;top:0px;z-index:-5;width:" + (this.divwidth - 2) + "px;height:" + (this.divheight - 2) + "px;",
	f = jQuery(this.icoblank),
	f.hover(function() {
		jQuery(i.board_background).addClass("border_background"),
		i.board_background.style.background = _ico._defaultbgcolor
	},
	function() {
		jQuery(i.board_background).removeClass("border_background"),
		i.board_background.style.background = ""
	}),
	f.bind("contextmenu",
	function(n) {
		return _contextmenu.right_ico(n ? n: window.event, i.id),
		!1
	}),
	_config.Permission("open", "", this.id) && f.bind("click",
	function() {
		/*
		 * 2013-8-28 添加是应用还是链接的判断，如果是应用 按原来走，如果是链接，直接打开
		 */
		if(i.app_type == 'link'){
			window.open(i.url);
		}else{
			i.idtype=='link'? OpenBrowser(i.url,i.text,'width=1024,height=600,titlebutton=close|max|min') : _ico.Open(i.id); 
		}
		/*
		 * 2013-8-28 end
		 */
		//i.idtype=='link'? OpenBrowser(i.url,i.text,'width=1024,height=600,titlebutton=close|max|min') : _ico.Open(i.id);
		//_ico.Open(i.id)
	}),
	document.getElementById(this.container).appendChild(o),
	this.nopos || (e = this.getpos(this.pos, this.container), this.left = e[0], this.top = e[1]),
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px",
	this.board.style.width = this.divwidth + "px",
	this.board.style.height = this.divheight + "px",
	ieVersion < 9 && ieVersion > 0 && jQuery(this.board).find(".IcoText").FontEffect({
		shadow: !0,
		shadowColor: "#000",
		shadowOffsetTop: 1,
		shadowBlur: 1,
		shadowOffsetLeft: 1,
		shadowOpacity: .5
	}),
	this.board.style.overflow = "hidden",
	_config.Permission("drag", this.container, this.id) && _Drag.init(this.id, this.board, "", this.container),
	this.status = 1
},
_ico.OpenWin = function(n, t, i, r, u) {
	jQuery("#shadow").hide();
	if (_ico.icos[n]) obj = _ico.icos[n];
	else {
		if (!_config.sourcedata.icos[n]) return;
		obj = _config.sourcedata.icos[n],
		obj.id = obj.icoid,
		obj.text = obj.name
	}
	if (typeof obj == "undefined" || !obj) return;
	switch (obj.open) {
	case 0:
	case 1:
		OpenAppWin(obj.id, "", i, r, u, t);
		break;
	case 2:
		OpenBrowser(obj.url, obj.text);
		break;
	case 3:
		obj.wwidth > 0 && obj.wheight > 0 ? window.open(obj.url, obj.text, "topbar=0,resizable=1,scrollbars=yes,location=no,menubar=no,status=1,width=" + obj.wwidth + ",height=" + obj.wheight) : window.open(obj.url);
		break;
	default:
		OpenAppWin(obj.id, "", i, r, u, t)
	}
},
_ico.Open = function(n, t) {
	jQuery("#shadow").hide(),
	_ico.icos[n] ? t = _ico.icos[n] : (t = _config.sourcedata.icos[n], t.id = t.icoid, t.text = t.name);
	if (typeof t == "undefined" || !t) return;
	if (t.type == "folder") OpenFolderWin(t.id);
	else if (t.type == "image") OpenPicWin(t.id);
	else switch (parseInt(t.open)) {
	case 0:
	case 1:
		OpenAppWin(t.id);
		break;
	case 2:
		OpenBrowser(t.url, t.text);
		break;
	case 3:
		t.wwidth > 0 && t.wheight > 0 ? window.open(t.url, t.text, "topbar=0,resizable=1,scrollbars=yes,location=no,menubar=no,status=1,width=" + t.wwidth + ",height=" + t.wheight) : window.open(t.url);
		break;
	default:
		OpenAppWin(t.id)
	}
},
_ico.removeIcoid = function(n, t) {
	var u, e, s, o, r, f, i;
	if (_ico.icos[n]) {
		u = _ico.icos[n];
		if (typeof u == "undefined" || !u) return;
		if (u.container == "_dock") {
			for (r = [], i = 0; i < _config.dockList.length; i++) _config.dockList[i] != n && (r[r.length] = _config.dockList[i]);
			_config.dockList = r,
			jQuery("#_dock").find(".Icoblock").each(function() {
				var i = this.id.replace("icon_", ""),
				n = _ico.icos[i],
				t;
				n.pos > u.pos && (n.pos -= 1, t = n.getpos(n.pos, "_dock"), n.left = t[0], n.top = t[1], n.board.style.left = n.left + "px", n.board.style.top = n.top + "px")
			}),
			jQuery(u.board).remove(),
			delete _ico.icos[u.id],
			_config.setDockSize()
		} else if (u.container.indexOf("icosContainer_body_") !== -1) {
			e = u.container.replace("icosContainer_body_", ""),
			r = [];
			if (e.indexOf("sys_") === -1) {
				for (f = _config.screenList.screenlist_u[_config.currentDesktop].icos, i = 0; i < f.length; i++) f[i] != n && (r[r.length] = f[i]);
				_config.screenList.screenlist_u[e].icos = r
			} else {
				for (f = _config.screenList.screenlist_0[_config.currentDesktop].icos, i = 0; i < f.length; i++) f[i] != n && (r[r.length] = f[i]);
				_config.screenList.screenlist_0[e].icos = r
			}
			s = _navbar.navbars[e].autolist,
			s && jQuery("#" + u.container).find(".Icoblock").each(function() {
				var i = this.id.replace("icon_", ""),
				n = _ico.icos[i],
				t;
				n.pos > u.pos && (n.pos -= 1, t = n.getpos(n.pos, n.container), n.left = t[0], n.top = t[1], n.board.style.left = n.left + "px", n.board.style.top = n.top + "px")
			}),
			jQuery(u.board).remove(),
			delete _ico.icos[u.id]
		}
	}
	for (o in _filemanage.cons) _filemanage.cons[o].data[n] && _filemanage.cons[o].delIcos(_config.sourcedata.icos[n]);
	if (t) {
		for (r = [], f = _config.sourcedata.folder[t].ids, i = 0; i < f.length; i++) f[i] != n && (r[r.length] = f[i]);
		_config.sourcedata.folder[t].ids = r
	}
},
_ico.prototype.DetachEvent = function() {
	if (!_ico.tach) return;
	document.body.style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_arrow.cur'),auto",
	document.onmousemove = _ico.onmousemove,
	document.onmouseup = _ico.onmouseup,
	document.onselectstart = _ico.onselectstart,
	this.board.releaseCapture && this.board.releaseCapture(),
	_ico.tach = 0,
	_ico.finishblank = 0
},
_ico.prototype.AttachEvent = function(n) {
	if (_ico.tach) return;
	_ico.onmousemove = document.onmousemove,
	_ico.onmouseup = document.onmouseup,
	_ico.onselectstart = document.onselectstart,
	n.preventDefault ? n.preventDefault() : (document.onselectstart = function() {
		return ! 1
	},
	this.board.setCapture && this.board.setCapture()),
	_ico.tach = 1
},
_ico.prototype.Focus = function() {
	this.zIndex < _ico.zIndex && (this.board.style.zIndex = this.zIndex = ++_ico.zIndex)
},
_ico.prototype.changeXY = function(n, t, i) {
	var u, r;
	i ? (this.left = n, this.top = t) : (u = jQuery("#" + this.container).offset(), this.left = n - u.left, this.top = t - u.top),
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px";
	if (i) {
		if (this.container.indexOf("icosContainer_body_sys_") !== -1) {
			if (_config.iconpositions_0[this.id]) {
				for (r in _config.iconpositions_0[this.id]) delete _config.iconpositions_0[this.id][r];
				delete _config.iconpositions_0[this.id]
			}
		} else if (_config.iconpositions_u[this.id]) {
			for (r in _config.iconpositions_u[this.id]) delete _config.iconpositions_u[this.id][r];
			delete _config.iconpositions_u[this.id]
		}
	} else this.container.indexOf("icosContainer_body_sys_") !== -1 ? _config.iconpositions_0[this.id] = [this.left, this.top] : _config.iconpositions_u[this.id] = [this.left, this.top],
	_config.saveItem.iconpositions = 1,
	_config.saveTimer && window.clearTimeout(_config.saveTimer),
	_config.saveTimer = setTimeout(function() {
		_config.sendConfig()
	},
	_config.savetime)
},
_ico.Rename = function(n, t) {
	var o, u, r, e, i, f;
	o = _ico.icos[n] ? _ico.icos[n].container: "";
	if (!_config.Permission("rename", o, n)) {
		showDialog(_lang.prompt1.noprevelige_not_self, "notice");
		return
	}
	t != "undefined" ? (u = _filemanage.cons[t], r = jQuery("#file_text_" + n), u.oldtext = r.html(), e = u.view == 4 ? "<input class='txt' name='text'  id='input_" + n + "' style=\"width:" + (r.parent().width() - 25) + 'px; " value="' + u.oldtext + '"></textarea>': "<textarea class='textarea' name='text'  id='input_" + n + "' style=\"width:" + r.parent().width() + 'px;height:33px; ">' + u.oldtext + "</textarea>", r.html(e), jQuery("#content_" + u.winid + ' .icoblank[icoid="' + n + '"]').css("z-index", -1), i = jQuery("#input_" + n), i.select(), i.blur(function() {
		return _config.self && u.oldtext != i.attr("value") ? _ajax.Rename(n, i.attr("value"), t) : r.html(u.oldtext),
		jQuery("#content_" + u.winid + ' .icoblank[icoid="' + n + '"]').css("z-index", 10),
		!1
	})) : (r = jQuery("#text_" + n), jQuery(_ico.icos[n].board).css("overflow", "visible"), r.css("overflow", "visible"), document.onselectstart = function() {
		return ! 0
	},
	_ico.icos[n].old_text_html = r.html(), document.getElementById("input_" + n) || (e = "<textarea class='IcoText_textarea' name='text'  id='input_" + n + "' style=\"width:" + r.css("width") + '; "></textarea>', r.html(e), jQuery(_ico.icos[n].icoblank).css("z-index", -10)), document.getElementById("input_" + n).value = _ico.icos[n].text, i = jQuery("#input_" + n), f = jQuery("#input_"), f.css("width", _ico.icos[n].divwidth - 10), f.html(_ico.icos[n].text), jQuery.browser.msie || i.css("height", f.height() + 20), i.select(), document.getElementById("input_" + n).onpopertychange = function() {
		document.getElementById("input_").innerHTML = i.attr("value"),
		i.css("height", f.height() + 20)
	},
	window.addEventListener && document.getElementById("input_" + n).addEventListener("input",
	function() {
		document.getElementById("input_").innerHTML = i.attr("value"),
		i.css("height", f.height() + 20)
	},
	!1), i.blur(function() {
		return _config.self && _ico.icos[n].text != i.attr("value") ? _ajax.Rename(n, i.attr("value")) : (r.html(_ico.icos[n].old_text_html), r.css("overflow", "hidden"), jQuery(_ico.icos[n].icoblank).css("z-index", _ico.icos[n].zIndex)),
		!1
	}))
},
_ico.Edit = function(n) {
	var t;
	t = _ico.icos[n] ? _ico.icos[n].container: "";
	if (!_config.Permission("edit", t, n)) {
		showDialog(_lang.prompt1.noprevelige_not_self, "notice");
		return
	}
	showWindow("editico", _config.ajaxurl + "&do=edit&icoid=" + n + "&uid=" + _config.uid + "&ukey=" + _config.ukey, "get", 0)
},
_ico.downAttach = function(n) {
	return _config.Permission("download", "", n) && (jQuery.browser.msie ? window.open(DZZSCRIPT + "?mod=system&op=attachment&icoid=" + n) : window.frames.hideframe.location = DZZSCRIPT + "?mod=system&op=attachment&icoid=" + n),
	!1
},
_Drag.delay = 500,
_Drag.width = 120,
_Drag.height = 120,
_Drag.icos = {},
_Drag.Version = "dsk js 1.0",
_Drag.onmousemove = null,
_Drag.onmouseup = null,
_Drag.tach = null,
_Drag.onselectstart = 1,
_Drag.init = function(n, t, i, r) {
	var u = new _Drag(n, i, r);
	return u.board = t,
	u.width = jQuery(t).width(),
	u.height = jQuery(t).height(),
	jQuery(u.board).bind("mousedown",
	function(n) {
		n = n ? n: window.event;
		var t = n.srcElement ? n.srcElement: n.target;
		return t.type == "text" || t.type == "textarea" ? !0 : (u.Mousedown(n ? n: window.event), !1)
	}),
	jQuery(u.board).bind("mouseup",
	function(n) {
		n = n ? n: window.event;
		var t = n.srcElement ? n.srcElement: n.target;
		return t.type == "text" || t.type == "textarea" ? !0 : (u.Mouseup(n ? n: window.event), !1)
	}),
	u
},
_Drag.prototype.DetachEvent = function() {
	if (!_Drag.tach) return;
	document.body.style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_arrow.cur'),auto",
	document.onmousemove = _Drag.onmousemove,
	document.onmouseup = _Drag.onmouseup,
	document.onselectstart = _Drag.onselectstart,
	this.board.releaseCapture && this.board.releaseCapture(),
	_Drag.tach = 0,
	_Drag.finishblank = 0
},
_Drag.prototype.AttachEvent = function(n) {
	if (_Drag.tach) return;
	_Drag.onmousemove = document.onmousemove,
	_Drag.onmouseup = document.onmouseup,
	_Drag.onselectstart = document.onselectstart,
	n.preventDefault ? n.preventDefault() : (document.onselectstart = function() {
		return ! 1
	},
	this.board.setCapture && this.board.setCapture()),
	_Drag.tach = 1
},
_Drag.prototype.Duplicate = function() {
	this.copy = document.createElement("div"),
	document.body.appendChild(this.copy),
	this.copy.innerHTML = this.board.innerHTML;
	var n = jQuery(this.board).offset();
	this.copy.style.cssText = "position:absolute;left:" + n.left + "px;top:" + n.top + "px;width:" + this.divwidth + "px;height:" + this.divheight + "px;filter:Alpha(opacity=50);opacity:0.5;z-index:5000;overflow:hidden",
	jQuery(this.copy).find(".border_background").remove()
},
_Drag.prototype.Createblank = function() {
	var u, i;
	jQuery("#_blank").empty();
	var t = this,
	r = document.createDocumentFragment(),
	n = document.createElement("div");
	n.id = "_shadow_icosContainer_body_" + _config.currentDesktop,
	n.style.position = "absolute",
	n.style.width = "100%",
	n.style.height = "100%",
	n.style.left = "0px",
	n.style.top = "0px",
	n.style.oveflow = "visible",
	r.appendChild(n),
	u = jQuery("#_dock"),
	i = document.createElement("div"),
	i.id = "_shadow__dock",
	i.style.cssText = "position:absolute;height:" + _config.dockHeight + "px;width:" + (u.width() + 20) + "px;left:50%;bottom:0px;margin-left:" + ( - jQuery("#dock_app_container").width() / 2 - 10) + "px;",
	r.appendChild(i),
	jQuery("#icosContainer_body_" + _config.currentDesktop).find(".Icoblock").each(function() {
		var i = _ico.icos[this.id.replace("icon_", "")],
		r = document.createElement("div");
		r.id = "_shadow_icon_" + i.id,
		r.style.cssText = "position:absolute;width:" + i.divwidth + "px;height:" + i.divheight + "px;left:" + (i.left + _navbar.navbars[_config.currentDesktop].marginleft) + "px;top:" + (i.top + _navbar.navbars[_config.currentDesktop].margintop) + "px;z-index:" + i.zIndex + ";background: url("+_THEME_+"/desktop/images/b.gif);",
		t.icoid != i.id && _config.sourcedata.icos[i.id].type == "folder" && (jQuery(r).bind("mouseover",
		function() {
			var t = this.id.replace("_shadow_icon_", "");
			jQuery(_ico.icos[t].board_background).addClass("border_background").css("background", _ico._defaultbgcolor)
		}), jQuery(r).bind("mouseout",
		function() {
			var t = this.id.replace("_shadow_icon_", "");
			jQuery(_ico.icos[t].board_background).removeClass("border_background").css("background", "")
		})),
		n.appendChild(r)
	}),
	jQuery("#dock_bottom").find(".Icoblock").each(function() {
		var n = _ico.icos[this.id.replace("icon_", "")],
		r = document.createElement("div");
		r.id = "_shadow_icon_" + n.id,
		r.style.cssText = "position:absolute;width:" + n.divwidth + "px;height:" + n.divheight + "px;left:" + n.left + "px;top:" + n.top + "px;z-index:" + n.zIndex + ";background: url("+_THEME_+"/desktop/images/b.gif);",
		t.icoid != n.id && (jQuery(r).bind("mouseover",
		function() {
			var i = this.id.replace("_shadow_icon_", ""),
			t;
			_ico.icos[i].type == "folder" && jQuery(_ico.icos[i].board_background).addClass("border_background").css("background", _ico._defaultbgcolor);
			for (t in _ico.icos) {
				if (_ico.icos[i].type == "folder") return;
				_ico.icos[t].left >= _ico.icos[i].left && _ico.icos[t].container == "_dock" && (_ico.icos[t].board.style.left = _ico.icos[t].left + _ico.icos[t].divwidth + "px")
			}
		}), jQuery(r).bind("mouseout",
		function() {
			var i = this.id.replace("_shadow_icon_", ""),
			t;
			_ico.icos[i].type == "folder" && jQuery(_ico.icos[i].board_background).removeClass("border_background").css("background", "");
			for (t in _ico.icos) _ico.icos[t].left >= _ico.icos[i].left && _ico.icos[t].container == "_dock" && (_ico.icos[t].board.style.left = _ico.icos[t].left + "px")
		})),
		i.appendChild(r)
	}),
	jQuery("#_body_" + _config.currentDesktop).find(".widget_blank").each(function() {
		var u = this.id.replace("_widget_blank", ""),
		t = _widget.widgets[u],
		n,
		i;
		t.data.idtype == "pluginid" && _config.sourcedata.plugin[t.data.typeid] && _config.sourcedata.plugin[t.data.typeid].acceptdata > 0 && (n = document.createElement("div"), n.style.position = "absolute", n.style.zIndex = t.zIndex, i = jQuery(this).offset(), n.style.left = i.left + "px", n.style.top = i.top + "px", n.style.overflow = "hidden", n.style.background = "url("+_THEME_+"/desktop/images/b.gif)", n.style.width = jQuery(this).width() + "px", n.style.height = jQuery(this).height() + "px", n.id = "_widget_plugin_" + t.id, r.appendChild(n))
	}),
	jQuery("#_body_" + _config.currentDesktop).find(".window_blank").each(function() {
		var l = this.id.replace("_blank_", ""),
		n = _window.windows[l],
		u = document.createElement("div"),
		f,
		e,
		h,
		s,
		o,
		i,
		c;
		u.style.position = "absolute",
		u.style.zIndex = n.zIndex,
		f = jQuery(this).offset(),
		u.style.left = f.left + "px",
		u.style.top = f.top + "px",
		u.style.overflow = "hidden",
		u.style.background = "url("+_THEME_+"/desktop/images/b.gif)",
		u.style.width = jQuery(this).width() + "px",
		u.style.height = jQuery(this).height() + "px",
		r.appendChild(u),
		n.idtype == "pluginid" ? u.id = "_window_plugin_" + n.id: n.type == "folder" ? (u.id = "_window_icosContainer_folder_" + n.fid, s = _filemanage.cons[n.filemanageid], s.view == "4" ? jQuery(n.contentCase).find(".detail_item_td_name").each(function() {
			var o = this,
			e, i, r;
			if (parseInt(jQuery(this).attr("icoid")) > 0) e = parseInt(jQuery(this).attr("icoid"));
			else return;
			if (_config.sourcedata.icos[e].type != "folder") return;
			i = document.createElement("div"),
			i.id = "_window_icosContainer_folder_" + _config.sourcedata.icos[e].oid,
			r = jQuery(this).offset(),
			i.style.cssText = "position:absolute;width:" + jQuery(this).width() + "px;height:" + jQuery(this).height() + "px;left:" + (r.left - f.left) + "px;top:" + (r.top - f.top) + "px;z-index:" + n.zIndex + ";background: url("+_THEME_+"/desktop/images/b.gif);",
			jQuery(i).bind("mouseover",
			function() {
				var i = this.id.replace("_shadow_icon_", "");
				if (t.icoid == i) return;
				jQuery(o).css("background", _ico._defaultbgcolor)
			}),
			jQuery(i).bind("mouseout",
			function() {
				jQuery(o).css("background", "")
			}),
			u.appendChild(i)
		}) : jQuery(n.contentCase).find(".file-icoitem").each(function() {
			var o = this,
			e, i, r;
			if (parseInt(jQuery(this).attr("icoid")) > 0) e = parseInt(jQuery(this).attr("icoid"));
			else return;
			if (_config.sourcedata.icos[e].type != "folder") return;
			i = document.createElement("div"),
			i.id = "_window_icosContainer_folder_" + _config.sourcedata.icos[e].oid,
			r = jQuery(this).offset(),
			i.style.cssText = "position:absolute;width:" + jQuery(this).width() + "px;height:" + jQuery(this).height() + "px;left:" + (r.left - f.left) + "px;top:" + (r.top - f.top) + "px;z-index:" + n.zIndex + ";background: url("+_THEME_+"/desktop/images/b.gif);",
			jQuery(i).bind("mouseover",
			function() {
				var i = this.id.replace("_shadow_icon_", "");
				if (t.icoid == i) return;
				jQuery(o).addClass("border_background").css("background", _ico._defaultbgcolor)
			}),
			jQuery(i).bind("mouseout",
			function() {
				jQuery(o).removeClass("border_background").css("background", "")
			}),
			u.appendChild(i)
		})) : n.type == "filemanage" && (u.id = "_window_" + n.id, e = document.createElement("div"), e.style.position = "absolute", e.style.left = "0px", e.style.top = "0px", e.style.width = jQuery("#jstree_area").width() + "px", e.style.height = "100%", e.style.overflow = "hidden", e.style.zIndex = n.zIndex + 1, e.id = "_shadow_jstree", u.appendChild(e), jQuery("#jstree_area #position li:visible").each(function() {
			var i = this.id.split("-");
			if (i[0] == "d") {
				var t = jQuery(this).find("a:first"),
				r = t.offset(),
				n = document.createElement("div");
				n.id = "_window_icosContainer_body_" + i[1],
				n.style.cssText = "position:absolute;left:" + (r.left - f.left) + "px;top:" + (r.top - f.top) + "px;width:" + t.width() + "px;height:" + t.height() + "px;",
				jQuery(n).bind("mouseover",
				function() {
					t.addClass("jstree-hovered")
				}),
				jQuery(n).bind("mouseout",
				function() {
					t.removeClass("jstree-hovered")
				}),
				e.appendChild(n)
			} else if (i[0] == "f") {
				var t = jQuery(this).find("a:first"),
				r = t.offset(),
				n = document.createElement("div");
				n.id = "_window_icosContainer_folder_" + i[1],
				n.style.cssText = "position:absolute;left:" + (r.left - f.left) + "px;top:" + (r.top - f.top) + "px;width:" + t.width() + "px;height:" + t.height() + "px;",
				jQuery(n).bind("mouseover",
				function() {
					t.addClass("jstree-hovered")
				}),
				jQuery(n).bind("mouseout",
				function() {
					t.removeClass("jstree-hovered")
				}),
				e.appendChild(n)
			}
		}), h = 0, n.filemanageid && (s = _filemanage.cons[n.filemanageid], o = n.filemanageid.split("-"), o[0] == "d" ? (i = document.createElement("div"), i.style.position = "absolute", i.style.left = jQuery("#jstree_area").width() + "px", i.style.top = "0px", i.style.width = n.bodyWidth - jQuery("#jstree_area").width() + "px", i.style.height = "100%", i.style.overflow = "hidden", i.style.zIndex = n.zIndex + 1, i.id = "_window_icosContainer_body_" + o[1], u.appendChild(i), h = 1) : o[0] == "f" && (i = document.createElement("div"), i.style.position = "absolute", i.style.left = jQuery("#jstree_area").width() + "px", i.style.top = "0px", i.style.width = n.bodyWidth - jQuery("#jstree_area").width() + "px", i.style.height = "100%", i.style.overflow = "hidden", i.id = "_window_icosContainer_folder_" + o[1], u.appendChild(i), h = 1)), h && (c = jQuery("#jstree_area").width(), s.view == "4" ? jQuery(n.contentCase).find(".detail_item_td_name").each(function() {
			var o = this,
			e, r, u;
			if (parseInt(jQuery(this).attr("icoid")) > 0) e = parseInt(jQuery(this).attr("icoid"));
			else return;
			if (_config.sourcedata.icos[e].type != "folder") return;
			r = document.createElement("div"),
			r.id = "_window_icosContainer_folder_" + _config.sourcedata.icos[e].oid,
			u = jQuery(this).offset(),
			r.style.cssText = "position:absolute;width:" + jQuery(this).width() + "px;height:" + jQuery(this).height() + "px;left:" + (u.left - f.left - c) + "px;top:" + (u.top - f.top) + "px;z-index:" + n.zIndex + ";background: url("+_THEME_+"/desktop/images/b.gif)",
			jQuery(r).bind("mouseover",
			function() {
				var i = this.id.replace("_shadow_icon_", "");
				if (t.icoid == i) return;
				jQuery(o).css("background", _ico._defaultbgcolor)
			}),
			jQuery(r).bind("mouseout",
			function() {
				jQuery(o).css("background", "")
			}),
			i.appendChild(r)
		}) : jQuery(n.contentCase).find(".file-icoitem").each(function() {
			var o = this,
			e, r, u;
			if (parseInt(jQuery(this).attr("icoid")) > 0) e = parseInt(jQuery(this).attr("icoid"));
			else return;
			if (_config.sourcedata.icos[e].type != "folder") return;
			r = document.createElement("div"),
			r.id = "_window_icosContainer_folder_" + _config.sourcedata.icos[e].oid,
			u = jQuery(this).offset(),
			r.style.cssText = "position:absolute;width:" + jQuery(this).width() + "px;height:" + jQuery(this).height() + "px;left:" + (u.left - f.left - c) + "px;top:" + (u.top - f.top) + "px;z-index:" + n.zIndex + ";background: url("+_THEME_+"/desktop/images/b.gif);",
			jQuery(r).bind("mouseover",
			function() {
				var i = this.id.replace("_shadow_icon_", "");
				if (t.icoid == i) return;
				jQuery(o).addClass("border_background").css("background", _ico._defaultbgcolor)
			}),
			jQuery(r).bind("mouseout",
			function() {
				jQuery(o).removeClass("border_background").css("background", "")
			}),
			i.appendChild(r)
		})))
	}),
	jQuery("#navbar").find(".navItem").each(function() {
		var i = this.id.replace("indicator_", ""),
		n = jQuery(this),
		t = n.offset(),
		r = jQuery('<div id="_shadow_indicator_' + i + '" style="position:absolute;width:' + n.outerWidth() + "px;height:" + n.outerHeight() + "px;left:" + t.left + "px;top:" + t.top + 'px;z-index:10;background: url('+_THEME_+'/desktop/images/b.gif);"></div>').appendTo("#_blank");
		i != _config.currentDesktop && r.hover(function() {
			var t = this.id.replace("_shadow_", ""),
			n = "indicator_current";
			t.indexOf("indicator_sys_") !== -1 && (n = "sysnav_current"),
			jQuery("#" + t).addClass(n)
		},
		function() {
			var t = this.id.replace("_shadow_", ""),
			n = "indicator_current";
			t.indexOf("indicator_sys_") !== -1 && (n = "sysnav_current"),
			jQuery("#" + t).removeClass(n)
		})
	}),
	document.getElementById("_blank").appendChild(r),
	document.getElementById("_blank").style.display = "block",
	jQuery("#navbar").css("z-index", 6e3),
	_Drag.finishblank = 1
},
_Drag.prototype.Mousedown = function(n) {
	var r, i, t;
	if (jQuery.browser.msie) {
		if (n.button > 1) return
	} else if (n.button > 0) return;
	this.mousedowndoing = !1,
	r = n.clientX,
	i = n.clientY,
	_Drag.tach || this.AttachEvent(n),
	_Drag.even = n,
	t = this,
	this.mousedownTimer = setTimeout(function() {
		t.PreMove(r, i)
	},
	200)
},
_Drag.prototype.Mouseup = function(n) {
	if (jQuery.browser.msie) {
		if (n.button > 1) return
	} else if (n.button > 0) return;
	_Drag.tach && this.DetachEvent(n),
	this.mousedowndoing ? this.Moved(n) : clearTimeout(this.mousedownTimer)
},
_Drag.prototype.PreMove = function(n, t) {
	var r, i;
	if (this.move != "no") return this.Duplicate(),
	r = this,
	this.Createblank(),
	this.mousedowndoing = !0,
	typeof this.MoveTimer != "undefined" && clearTimeout(this.MoveTimer),
	i = jQuery(this.board).offset(),
	this.tl = n - i.left,
	this.tt = t - i.top,
	this.moveX = n - this.left,
	this.moveY = t - this.top,
	_Drag.tach || this.AttachEvent(_Drag.even),
	document.onmousemove = function(n) {
		return r.Move(n ? n: window.event),
		!1
	},
	document.onmouseup = function(n) {
		return r.Moved(n ? n: window.event),
		!1
	},
	!1
},
_Drag.prototype.Move = function(n) {
	n || (n = getEvent());
	if (!_Drag.tach) return;
	var i = n.clientX,
	t = n.clientY;
	i < 0 && (i = 0),
	t < 0 && (t = 0),
	i > _config.screenWidth && (i = _config.screenWidth),
	t > _config.screenHeight && (t = _config.screenHeight),
	this.move != "move-y" && (this.copy.style.left = i - this.tl + "px"),
	this.move != "move-x" && (this.copy.style.top = t - this.tt + "px")
},
_Drag.prototype.Moved = function(n) {
	var p = this,
	f, e, i, a, r, h, u, o, l, s, c, v;
	_Drag.finishblank && (jQuery("_blank").empty().hide(), jQuery("#navbar").css("z-index", 1e3)),
	n || (n = getEvent()),
	f = n.clientX,
	e = n.clientY,
	f < 0 && (f = 0),
	e < 0 && (e = 0),
	f > _config.screenWidth && (f = _config.screenWidth),
	e > _config.screenHeight && (e = _config.screenHeight),
	_Drag.tach && this.DetachEvent(n);
	var y = n.srcElement ? n.srcElement: n.target,
	w = this.id,
	t = jQuery(y).attr("id");
	if (!t) {
		this.reject();
		return
	}
	if (t.indexOf("_shadow_icon_") !== -1) {
		i = t.replace("_shadow_icon_", "");
		if (_config.sourcedata.icos[i].type == "folder" && !_config.Permission("drop", "icosContainer_folder_" + _config.sourcedata.icos[i].oid)) {
			this.reject();
			return
		}
		if (_ico.icos[i] && !_config.Permission("drop", _ico.icos[i].container)) {
			this.reject();
			return
		}
		if (this.data.type == "folder" && _config.sourcedata.icos[i].type == "folder") if (_ico.isParentFid(this.data.oid, _config.sourcedata.icos[i].oid) || this.data.oid == _config.sourcedata.icos[i].oid) {
			this.reject();
			return
		}
		if (_config.sourcedata.icos[i].type == "folder" && _config.sourcedata.folder[_config.sourcedata.icos[i].oid].pfid > 0 && this.data.type == "folder") {
			r = _config.sourcedata.folder[_config.sourcedata.icos[i].oid].pfid;
			if (_ico.isParentFid(_config.sourcedata.icos[this.icoid].oid, r) || _config.sourcedata.icos[this.icoid].oid == r) {
				this.reject();
				return
			}
		}
		if (this.icoid == i) {
			if (this.container.indexOf("icosContainer_body_") !== -1) {
				a = _navbar.navbars[_config.currentDesktop].autolist;
				if (a) {
					this.reject();
					return
				}
				_ico.icos[this.icoid].changeXY(f - this.tl, e - this.tt, 0),
				jQuery(this.copy).remove(),
				this.copy = null,
				jQuery("#_blank").empty().hide(),
				jQuery(".backgound_radius").removeClass("border_background").css("background", "");
				return
			}
			this.reject();
			return
		}
		if (_config.sourcedata.icos[i].type == "folder" && _config.sourcedata.icos[this.icoid].uid == -1) {
			this.reject();
			return
		}
	} else if (t.indexOf("_shadow_icosContainer_body_") !== -1) {
		if (t.indexOf("_shadow_icosContainer_body_sys_") !== -1) {
			if (_config.space.self < 2) {
				this.reject();
				return
			}
			if (_config.sourcedata.icos[this.icoid].uid == -1) {
				this.reject();
				return
			}
		}
	} else if (t.indexOf("_window_icosContainer_body_") !== -1) {
		if (t.indexOf("_window_icosContainer_body_sys_") !== -1) {
			if (_config.space.self < 2) {
				this.reject();
				return
			}
			if (_config.sourcedata.icos[this.icoid].uid == -1) {
				this.reject();
				return
			}
		}
		r = t.replace("_window_icosContainer_body_", "");
		if (this.container == "icosContainer_body_" + r) {
			this.reject();
			return
		}
	} else if (t.indexOf("_window_icosContainer_folder_") !== -1) {
		r = t.replace("_window_icosContainer_folder_", "");
		if (this.container == "icosContainer_folder_" + r) {
			this.reject();
			return
		}
		if (!_config.Permission("drop", "icosContainer_folder_" + r)) {
			this.reject();
			return
		}
		if (this.data.type == "folder") if (_ico.isParentFid(_config.sourcedata.icos[this.icoid].oid, r) || this.data.oid == r) {
			this.reject();
			return
		}
		if (_config.sourcedata.icos[this.icoid].uid == -1) {
			this.reject();
			return
		}
	} else if (t.indexOf("_shadow__dock") === -1) if (t.indexOf("_window__dock") === -1) {
		if (t.indexOf("_widget_plugin_") !== -1) {
			h = t.replace("_widget_plugin_", ""),
			u = _widget.widgets[h];
			if (u.data.idtype != "pluginid") {
				this.reject();
				return
			}
			o = u.data.typeid;
			if (_config.sourcedata.plugin[o].acceptdata > 0) {
				if (in_array(_config.sourcedata.icos[this.icoid].ext, _config.sourcedata.plugin[o].datatype)) {
					l = document.getElementById(h + "widget_ifm0").name,
					s = {},
					s.icodata = _config.sourcedata.icos[this.icoid],
					_config.sendDataTo(l, s),
					jQuery(this.copy).remove(),
					jQuery("#_blank").empty().hide();
					return
				}
				this.reject();
				return
			}
			this.reject();
			return
		}
		if (t.indexOf("_window_plugin_") !== -1) {
			h = t.replace("_window_plugin_", ""),
			u = _window.windows[h];
			if (u.idtype != "pluginid") {
				this.reject();
				return
			}
			o = u.pluginid;
			if (_config.sourcedata.plugin[o].acceptdata > 0) {
				if (in_array(_config.sourcedata.icos[this.icoid].ext, _config.sourcedata.plugin[o].datatype)) {
					l = u.tabs[u.tab].frame.name,
					s = {},
					s.icodata = _config.sourcedata.icos[this.icoid],
					_config.sendDataTo(l, s),
					jQuery(this.copy).remove(),
					jQuery("#_blank").empty().hide();
					return
				}
				this.reject();
				return
			}
			this.reject();
			return
		}
		if (t.indexOf("_shadow_indicator_") !== -1) {
			c = t.replace("_shadow_indicator_", ""),
			v = _navbar.navbars[c];
			if (c == _config.currentDesktop) {
				this.reject();
				return
			}
			if (v.type != "desktop") {
				this.reject();
				return
			}
			if (c.indexOf("sys_") !== -1 && _config.space.self < 2) {
				this.reject();
				return
			}
		} else {
			this.reject();
			return
		}
	}
	this.IcoMoveTo(t, f, e),
	jQuery("#jstree_area .jstree-hovered").removeClass("jstree-hovered")
},
_Drag.prototype.IcoMoveTo = function(n, t, i) {
	var s, e, l, h, a, c, f, o, r, u;
	if (n.indexOf("_shadow_icon_") !== -1) {
		s = n.replace("_shadow_icon_", "");
		if (_ico.icos[s]) e = _ico.icos[s],
		e.oid = _config.sourcedata.icos[s].oid;
		else {
			this.reject();
			return
		}
		r = {
			id: this.icoid,
			pos: e.pos,
			left: t - this.tl,
			top: i - this.tt
		},
		_Drag.resetConfigData(this, e, "");
		if (e.type == "folder") {
			this.IcoRemoveFromContainer();
			for (u in _filemanage.cons) u.indexOf("f-" + e.sourceid + "_") === 0 && _filemanage.cons[u].CreateIcos(_config.sourcedata.icos[this.icoid])
		} else if (e.container == "_dock") {
			this.IcoRemoveFromContainer(),
			jQuery("#_dock").find(".Icoblock").each(function() {
				var i = this.id.replace("icon_", ""),
				n = _ico.icos[i],
				t;
				n.pos >= r.pos && (n.pos += 1, t = n.getpos(n.pos, "_dock"), n.changeXY(t[0], t[1], 1))
			}),
			_ico.CIco(r.id, "_dock", r.pos),
			_config.setDockSize();
			for (u in _filemanage.cons) u.indexOf("dock-") === 0 && _filemanage.cons[u].CreateIcos(_config.sourcedata.icos[this.icoid])
		} else if (e.container.indexOf("icosContainer_body_") !== -1) {
			f = e.container.replace("icosContainer_body_", ""),
			l = _navbar.navbars[f].autolist,
			this.IcoRemoveFromContainer(),
			l ? (jQuery("#" + e.container).find(".Icoblock").each(function() {
				var i = this.id.replace("icon_", ""),
				n = _ico.icos[i],
				t;
				n.pos >= r.pos && (n.pos += 1, t = n.getpos(n.pos, n.container), n.changeXY(t[0], t[1], 1))
			}), _ico.CIco(r.id, e.container, r.pos)) : (h = _ico.CIco(r.id, e.container, r.pos, [0, 0]), h.pos = r.pos, h.changeXY(r.left, r.top, 0));
			for (u in _filemanage.cons) u.indexOf("d-" + f + "-") === 0 && _filemanage.cons[u].CreateIcos(_config.sourcedata.icos[this.icoid])
		} else if (e.container.indexOf("icosContainer_folder_") !== -1) {
			this.IcoRemoveFromContainer();
			for (u in _filemanage.cons) u.indexOf("f-" + e.sourceid + "-") === 0 && _filemanage.cons[u].CreateIcos(_config.sourcedata.icos[this.icoid])
		}
	} else if (n == "_shadow__dock" || n == "_window__dock") {
		_Drag.resetConfigData(this, "", "_dock"),
		r = {
			id: this.icoid,
			pos: _config.dockList.length - 1,
			left: t - this.tl,
			top: i - this.tt
		},
		this.IcoRemoveFromContainer(),
		_ico.CIco(r.id, "_dock", r.pos),
		_config.setDockSize();
		for (u in _filemanage.cons) u.indexOf("dock-") === 0 && _filemanage.cons[u].CreateIcos(_config.sourcedata.icos[this.icoid])
	} else if (n.indexOf("_shadow_icosContainer_body_") !== -1) {
		f = n.replace("_shadow_icosContainer_body_", ""),
		_Drag.resetConfigData(this, "", "icosContainer_body_" + f),
		o = f.indexOf("sys_") === -1 ? _config.screenList.screenlist_u[f].icos.length - 1 : _config.screenList.screenlist_0[f].icos.length - 1,
		r = {
			id: this.icoid,
			pos: o,
			left: t - this.tl,
			top: i - this.tt
		},
		this.IcoRemoveFromContainer(),
		_navbar.navbars[f].autolist ? _ico.CIco(r.id, "icosContainer_body_" + f, r.pos) : (a = _ico.CIco(r.id, "icosContainer_body_" + f, r.pos, [0, 0]), a.changeXY(r.left, r.top, 0));
		for (u in _filemanage.cons) u.indexOf("d-" + f + "-") === 0 && _filemanage.cons[u].CreateIcos(_config.sourcedata.icos[this.icoid])
	} else if (n.indexOf("_window_icosContainer_body_") !== -1) {
		f = n.replace("_window_icosContainer_body_", ""),
		_Drag.resetConfigData(this, "", "icosContainer_body_" + f),
		o = f.indexOf("sys_") === -1 ? _config.screenList.screenlist_u[f].icos.length - 1 : _config.screenList.screenlist_0[f].icos.length - 1,
		r = {
			id: this.icoid,
			pos: o,
			left: t - this.tl,
			top: i - this.tt
		},
		this.IcoRemoveFromContainer(),
		_ico.CIco(r.id, "icosContainer_body_" + f, r.pos);
		for (u in _filemanage.cons) u.indexOf("d-" + f + "-") === 0 && _filemanage.cons[u].CreateIcos(_config.sourcedata.icos[this.icoid])
	} else if (n.indexOf("_window_icosContainer_folder_") !== -1) {
		c = n.replace("_window_icosContainer_folder_", ""),
		_Drag.resetConfigData(this, "", "icosContainer_folder_" + c),
		this.IcoRemoveFromContainer();
		for (u in _filemanage.cons) u.indexOf("f-" + c + "-") === 0 && _filemanage.cons[u].CreateIcos(_config.sourcedata.icos[this.icoid])
	} else if (n.indexOf("_shadow_indicator_") !== -1) {
		f = n.replace("_shadow_indicator_", ""),
		_Drag.resetConfigData(this, "", "icosContainer_body_" + f),
		o = f.indexOf("sys_") === -1 ? _config.screenList.screenlist_u[f].icos.length - 1 : _config.screenList.screenlist_0[f].icos.length - 1,
		r = {
			id: this.icoid,
			pos: o,
			left: t - this.tl,
			top: i - this.tt
		},
		this.IcoRemoveFromContainer(),
		_ico.CIco(r.id, "icosContainer_body_" + f, r.pos);
		for (u in _filemanage.cons) u.indexOf("d-" + f + "-") === 0 && _filemanage.cons[u].CreateIcos(_config.sourcedata.icos[this.icoid]);
		_navbar.setCurrentDesktop(_config.currentDesktop)
	}
},
_Drag.prototype.reject = function() {
	var t = jQuery(this.copy),
	n;
	jQuery("#_blank").empty().hide(),
	jQuery("#navbar").css("z-index", 1e3),
	jQuery("#jstree_area .jstree-hovered").removeClass("jstree-hovered"),
	jQuery(".backgound_radius").removeClass("border_background").css("background", ""),
	n = jQuery(this.board).offset(),
	t.animate({
		left: n.left + "px",
		top: n.top + "px"
	},
	_config.delay,
	function() {
		t.remove()
	})
},
_Drag.prototype.IcoRemoveFromContainer = function() {
	var i, r, u, n, t;
	jQuery(this.copy).remove(),
	i = _ico.icos[this.icoid];
	if (this.container == "_dock") {
		jQuery("#_dock").find(".Icoblock").each(function() {
			var r = this.id.replace("icon_", ""),
			n = _ico.icos[r],
			t;
			n.pos > i.pos && (n.pos -= 1, t = n.getpos(n.pos, "_dock"), n.changeXY(t[0], t[1], 1))
		}),
		jQuery(_ico.icos[this.icoid].board).remove();
		for (n in _ico.icos[this.icoid]) delete this[n];
		delete _ico.icos[this.icoid],
		_config.setDockSize()
	} else if (this.container.indexOf("icosContainer_body_") !== -1) {
		r = this.container.replace("icosContainer_body_", ""),
		u = _navbar.navbars[r].autolist,
		u && jQuery("#" + this.container).find(".Icoblock").each(function() {
			var r = this.id.replace("icon_", ""),
			n = _ico.icos[r],
			t;
			n.pos > i.pos && (n.pos -= 1, t = n.getpos(n.pos, n.container), n.changeXY(t[0], t[1], 1))
		}),
		jQuery(_ico.icos[this.icoid].board).remove();
		for (n in _ico.icos[this.icoid]) delete this[n];
		delete _ico.icos[this.icoid]
	}
	for (t in _filemanage.cons) _filemanage.cons[t].data[this.icoid] && _filemanage.cons[t].delIcos(_config.sourcedata.icos[this.icoid]);
	jQuery("#_blank").empty().hide(),
	jQuery(".backgound_radius").removeClass("border_background").css("background", "")
},
_Drag.resetConfigData = function(n, t, i) {
	var s = [],
	u,
	f,
	r,
	e,
	o;
	if (n.icoid == t.id) return;
	if (n.container == "_dock") {
		for (u = [], r = 0; r < _config.dockList.length; r++) n.icoid != _config.dockList[r] && (u[u.length] = _config.dockList[r]);
		_config.dockList = u,
		_config.saveItem.docklist = 1,
		_config.setDockSize()
	} else if (n.container.indexOf("icosContainer_body_") !== -1) {
		e = n.container.replace("icosContainer_body_", ""),
		u = [];
		if (e.indexOf("sys_") !== -1) {
			for (f = _config.screenList.screenlist_0[e].icos, r = 0; r < f.length; r++) f[r] != n.icoid && u.push(f[r]);
			_config.screenList.screenlist_0[e].icos = u
		} else {
			for (f = _config.screenList.screenlist_u[e].icos, r = 0; r < f.length; r++) f[r] != n.icoid && u.push(f[r]);
			_config.screenList.screenlist_u[e].icos = u
		}
		_config.saveItem.screenlist = 1
	} else if (n.container.indexOf("icosContainer_folder_") !== -1) {
		var o = n.container.replace("icosContainer_folder_", ""),
		u = [],
		f = _config.sourcedata.folder[o].ids;
		for (r = 0; r < f.length; r++) f[r] != n.icoid && u.push(f[r]);
		_config.sourcedata.folder[o].ids = u,
		_config.saveItem.folder.push(o)
	}
	if (t) {
		if (t.type == "folder") _config.sourcedata.folder[t.sourceid].ids ? _config.sourcedata.folder[t.sourceid].ids.push(n.icoid) : _config.sourcedata.folder[t.sourceid].ids = new Array(n.icoid),
		_config.saveItem.folder.push(t.sourceid),
		n.data.type == "folder" && (_config.sourcedata.folder[n.data.oid].pfid = t.oid, _config.sourcedata.folder[n.data.oid].desktop = 0, _config.saveItem.folder.push(n.data.oid));
		else if (t.container == "_dock") {
			for (u = [], r = 0; r < _config.dockList.length; r++) t.id == _config.dockList[r] ? (u[u.length] = n.icoid, u[u.length] = t.id) : u[u.length] = _config.dockList[r];
			_config.dockList = u,
			_config.saveItem.docklist = 1,
			n.data.type == "folder" && (_config.sourcedata.folder[n.data.oid].pfid = 0, _config.sourcedata.folder[n.data.oid].desktop = -1, _config.saveItem.folder.push(n.data.oid)),
			_config.setDockSize()
		} else if (t.container.indexOf("icosContainer_body_") !== -1) {
			e = t.container.replace("icosContainer_body_", ""),
			u = [];
			if (e.indexOf("sys_") === -1) {
				for (f = _config.screenList.screenlist_u[e].icos, r = 0; r < f.length; r++) t.id == f[r] ? (u[u.length] = n.icoid, u[u.length] = t.id) : u[u.length] = f[r];
				_config.screenList.screenlist_u[e].icos = u
			} else {
				for (f = _config.screenList.screenlist_0[e].icos, r = 0; r < f.length; r++) t.id == f[r] ? (u[u.length] = n.icoid, u[u.length] = t.id) : u[u.length] = f[r];
				_config.screenList.screenlist_0[e].icos = u
			}
			n.data.type == "folder" && (_config.sourcedata.folder[n.data.oid].pfid = 0, _config.sourcedata.folder[n.data.oid].desktop = e, _config.saveItem.folder.push(n.data.oid)),
			_config.saveItem.screenlist = 1
		}
	} else i == "_dock" ? (_config.dockList.push(n.icoid), n.type == "folder" && (_config.sourcedata.folder[n.data.oid].pfid = 0, _config.sourcedata.folder[n.data.oid].desktop = -1, _config.saveItem.folder.push(n.data.oid)), _config.saveItem.docklist = 1) : i.indexOf("icosContainer_body_") !== -1 ? (e = i.replace("icosContainer_body_", ""), e.indexOf("sys_") === -1 ? _config.screenList.screenlist_u[e].icos.push(n.icoid) : _config.screenList.screenlist_0[e].icos.push(n.icoid), n.data.type == "folder" && (_config.sourcedata.folder[n.data.oid].pfid = 0, _config.sourcedata.folder[n.data.oid].desktop = e, _config.saveItem.folder.push(n.data.oid)), _config.saveItem.screenlist = 1) : i.indexOf("icosContainer_folder_") !== -1 && (o = parseInt(i.replace("icosContainer_folder_", "")), _config.sourcedata.folder[o].ids ? _config.sourcedata.folder[o].ids.push(n.icoid) : _config.sourcedata.folder[o].ids = new Array(n.icoid), n.data.type == "folder" && (_config.sourcedata.folder[n.data.oid].pfid = o, _config.sourcedata.folder[n.data.oid].desktop = 0, _config.saveItem.folder.push(n.data.oid)), _config.saveItem.folder.push(o));
	_config.saveTimer && window.clearTimeout(_config.saveTimer),
	_config.saveTimer = setTimeout(function() {
		_config.sendConfig()
	},
	_config.savetime)
},
_ajax = {},
jQuery.ajaxError = function(n, t, i) {
	alert("\u8bf7\u6c42" + i.url + ":" + t.status + "   " + t.statusText)
},
_ajax.newFolder = function(n) {
	var i, t;
	jQuery("#shadow").hide(),
	i = 0,
	t = 0,
	n.indexOf("icosContainer_body_") !== -1 ? i = n.replace("icosContainer_body_", "") : n.indexOf("icosContainer_folder_") !== -1 && (t = n.replace("icosContainer_folder_", "")),
	_config.sendConfig(),
	//console.log(_config.dataurl + "&do=newfolder&uid=" + _config.uid + "&pfid=" + t + "&desktop=" + i + "&ukey=" + _config.ukey + "&t=" + +new Date);
	jQuery.getJSON(_config.dataurl + "&do=newfolder&uid=" + _config.uid + "&pfid=" + t + "&desktop=" + i + "&ukey=" + _config.ukey + "&t=" + +new Date,
	function(t) {
		t.msg == "success" ? _ico.createFolder(t, n) : t.msg == "refresh" ? (Alert(_lang.need_refresh), window.onbeforeunload = null, window.location.reload()) : Alert(t.msg)
	})
},
_ajax.newDoc = function(n, t) {
	jQuery("#shadow").hide(),
	_config.sendConfig(),
	jQuery.getJSON(_config.dataurl + "&do=newdoc&uid=" + _config.uid + "&container=" + n + "&ext=" + t + "&ukey=" + _config.ukey + "&t=" + +new Date,
	function(n) {
		n.msg == "success" ? _ico.createIco(n) : n.msg == "refresh" ? (Alert(_lang.need_refresh), window.onbeforeunload = null, window.location.reload()) : Alert(n.msg)
	})
},
_ajax.Rename = function(n, t, i) {
	//console.log(_config.dataurl + "&do=rename&uid=" + _config.uid + "&ukey=" + _config.ukey + "&icoid=" + n + "&t=" + +new Date);
	jQuery.ajax({
		type: "post",
		url: _config.dataurl + "&do=rename&uid=" + _config.uid + "&ukey=" + _config.ukey + "&icoid=" + n + "&t=" + +new Date,
		data: {
			text: t
		},
		dataType: "json",
		success: function(t) {
			var u, r;
			if (t.msg == "refresh") Alert(_lang.need_refresh),
			window.onbeforeunload = null,
			window.location.reload();
			else if (t.msg == "success") {
				_config.sourcedata.icos[n].name = t.text,
				t.dataname && (_config.sourcedata[_config.sourcedata.icos[n].type][_config.sourcedata.icos[n].oid][t.dataname] = t.text),
				_ico.icos[n] && (u = _ico.CIco(n, _ico.icos[n].container, _ico.icos[n].pos));
				for (r in _filemanage.cons) _filemanage.cons[r].data[n] && _filemanage.cons[r].reCreateIcos(_config.sourcedata.icos[n])
			} else i != "undefined" && _ico.icos[n] ? (jQuery("#text_" + n).html(_ico.icos[n].old_text_html), jQuery(_ico.icos[n].icoblank).css("z-index", _ico.icos[n].zIndex)) : jQuery("#file_text_" + n).html(_filemanage.cons[i].oldtext)
		},
		error: function() {
			i != "undefined" && _ico.icos[n] ? (jQuery("#text_" + n).html(_ico.icos[n].old_text_html), jQuery(_ico.icos[n].icoblank).css("z-index", _ico.icos[n].zIndex)) : jQuery("#file_text_" + n).html(_filemanage.cons[i].oldtext),
			Alert(_lang.network_error, 3e3)
		}
	})
},
_ajax.getFolderInfo = function(n, t) {
	jQuery("#shadow").hide(),
	_config.saveItem.folder.length > 0 && _config.sendConfig(),
	jQuery.getJSON(_config.dataurl + "&do=getfolderinfo&uid=" + _config.uid + "&fid=" + n + "&ukey=" + _config.ukey + "&t=" + +new Date,
	function(i) {
		var r, u, f;
		if (i.msg == "success") {
			if (i.sourceids) for (r in i.sourceids) i.sourceids[r].length && (_config.sourceids[r] = array_merge(_config.sourceids[r], i.sourceids[r]));
			if (i.sourcedata) for (u in i.sourcedata) for (f in i.sourcedata[u]) _config.sourcedata[u][f] = i.sourcedata[u][f];
			_ico.reCIcolist("icosContainer_folder_" + n),
			_config.sourcedata.folder[n].opened = 1,
			jQuery("#window_content_loadding_" + t).fadeOut()
		} else i.msg == "refresh" ? (Alert(_lang.need_refresh), window.onbeforeunload = null, window.location.reload()) : Slert(i.msg)
	})
},
_ajax.delIco = function(n, t) {
	var r, u, e, f, i;
	jQuery("#shadow").hide(),
	i = 0,
	u = 0;
	if (!t && _config.sourcedata.icos[n].type == "folder" && _config.sourcedata.folder[_config.sourcedata.icos[n].oid] && _config.sourcedata.folder[_config.sourcedata.icos[n].oid].ids && _config.sourcedata.folder[_config.sourcedata.icos[n].oid].ids.length > 0) {
		Confirm(_lang.delete_folder_1 + _config.sourcedata.icos[n].name + _lang.delete_folder_2,
		function() {
			_ajax.delIco(n, 1)
		});
		return
	}
	if (_ico.icos[n]) r = _ico.icos[n].container,
	r.indexOf("icosContainer_body_") !== -1 ? u = r.replace("icosContainer_body_", "") : r == "_dock" && (u = -1);
	else {
		i = 0;
		for (e in _filemanage.cons) if (_filemanage.cons[e].data[n]) {
			f = _filemanage.cons[e].fid.split("-");
			if (f[0] == "f") {
				i = f[1];
				break
			}
		}
		_config.saveItem.folder.length > 0 && (_config.jqxhr.dzzsuccess = !1, _config.sendConfig())
	}
	//console.log(_config.dataurl + "&do=deleteIco&icoid=" + n + "&uid=" + _config.uid + "&ukey=" + _config.ukey + "&pfid=" + i + "&desktop=" + u + "&t=" + +new Date);
	_config.jqxhr.dzzsuccess ? jQuery.getJSON(_config.dataurl + "&do=deleteIco&icoid=" + n + "&uid=" + _config.uid + "&ukey=" + _config.ukey + "&pfid=" + i + "&desktop=" + u + "&t=" + +new Date,
	function(t) {
		t.msg == "success" ? _ico.removeIcoid(n, i) : t.msg == "refresh" ? Confirm(_lang.need_refresh,
		function() {
			window.onbeforeunload = null,
			window.location.reload()
		}) : Alert(t.msg)
	}) : Alert(_lang.network_error)
},
_window.Max = [],
_window.Version = "dskdesktop js 1.0",
_window.Width = 400,
_window.Height = -1,
_window.Timer = 0,
_window.zIndex = 4e3,
_window.wIndex = 4e3,
_window.windows = {},
_window.clientWidth = document.documentElement.clientWidth,
_window.clientHeight = document.documentElement.clientHeight,
_window.onmousemove = null,
_window.onmouseup = null,
_window.onselectstart = 1,
_window.sum = 0,
_window.ctrl = 0,
_window.alt = 0,
_window.hidetime = 500,
_window.getFeature = function(n, t) {
	var i = new RegExp("(^|,|\\s)" + t + "\\s*=\\s*([^,]*)(\\s|,|$)", "i");
	return i.test(n) ? RegExp.$2: ""
},
_window.getMaxNumber = function() {
	for (var t = 0,
	n = 0; n < arguments.length; n++) arguments[n] > t && (t = arguments[n]);
	return t
},
_window.getCurrentWindowId = function() {
	var i = 0,
	t = null,
	n;
	for (n in _window.windows) ! _window.windows[n].isHide && _window.windows[n].desktop == _config.currentDesktop && _window.windows[n].zIndex > i && (t = n);
	return t
},
_window.currentWindow = function(n) {
	var i = _window.getCurrentWindowId(),
	t;
	if (!i) return;
	t = _window.windows[i];
	switch (n) {
	case "Close":
		t.Close();
		break;
	case "Max":
		t.MAX ? t.Restore() : t.Max();
		break;
	case "Min":
		t.Min()
	}
},
_window.CloseAppwinAll = function() {
	for (var n in _window.windows) _window.windows[n].Close()
},
_window.showDesktop = function() {
	var n;
	jQuery("#shadow").hide();
	if (_window.desktophide) {
		for (n in _window.windows) _window.windows[n].board.style.display = "block",
		_window.windows[n].isHide = 0,
		_window.windows[n].status = 1;
		_window.desktophide = 0
	} else {
		for (n in _window.windows) _window.windows[n].board.style.display = "none",
		_window.windows[n].isHide = 1,
		_window.windows[n].status = 0;
		_window.desktophide = 1
	}
},
_window.OpenPicWin = function(n, t) {
	var i = new _window(t, "sys_pic");
	return i.taskid = "sys_pic",
	i.type = "image",
	i.icoid = n,
	i.CreatPicWin(n, _config.sourcedata.icos[n].url, _config.sourcedata.icos[n].name),
	i
},
_window.prototype.CreatPicWin = function(n, t, i) {
	var e, u, o, f, r;
	this.board = document.createElement("div"),
	this.board.className = this.className + " window",
	this.board.style.position = "absolute",
	this.board.id = this.id,
	this.board.style.zIndex = this.zIndex,
	this.board.style.visibility = "hidden",
	this.desktop = _config.currentDesktop,
	document.getElementById("_body_" + _config.currentDesktop).appendChild(this.board),
	_window.clientHeight || (this.board.style.left = "100%", this.board.style.top = "100%", _window.clientWidth = this.board.offsetLeft, _window.clientHeight = this.board.offsetTop),
	e = ["LEFT_TOP", "TOP", "RIGHT_TOP", "RIGHT", "RIGHT_BOTTOM", "BOTTOM", "LEFT_BOTTOM", "LEFT", "SHADOW_TOP", "SHADOW_RIGHT", "SHADOW_BOTTOM", "SHADOW_LEFT"],
	this.sides = [],
	e[e.length] = "TITLE",
	e[e.length] = "CONTENT",
	u = this,
	r = document.createElement("div");
	if (this.resize != "no") for (o = this.resize.split("|"), f = 0; f < o.length; f++) e[e.length] = o[f];
	for (f = 0; f < e.length; f++) {
		r = document.createElement("div"),
		r.className = e[f],
		r.style.position = "absolute",
		r.innerHTML = '<div class="' + e[f] + '_inner inner" style="position:absolute;"></div>',
		this.board.appendChild(r);
		switch (e[f]) {
		case "TITLE":
			this.titleCase = r,
			r.style.height = r.offsetHeight + "px",
			r.style.width = this.bodyWidth + "px",
			this.SetPicTitle(n, t, i),
			jQuery(this.titleCase).bind("dblclick",
			function() {
				return u.MAX ? u.Restore() : u.Max(),
				!1
			}),
			this.moveable && (jQuery(this.titleCase).bind("mousedown",
			function(n) {
				u.Mousedown(n ? n: window.event)
			}), jQuery(this.titleCase).bind("mouseup",
			function(n) {
				u.Mouseup(n ? n: window.event)
			}));
			break;
		case "CONTENT":
			this.contentCase = r,
			r.style.width = this.bodyWidth + "px",
			this.bodyHeight > 0 && (r.style.height = this.bodyHeight + "px"),
			r.style.left = this.sides[7].width + "px",
			r.style.top = this.sides[1].height + "px",
			this.width = this.bodyWidth + this.sides[3].width + this.sides[7].width,
			this.height = this.bodyHeight + this.sides[1].height + this.sides[5].height,
			this.board.style.height = this.height + "px",
			this.board.style.width = this.width + "px",
			this.SetPicContent(n, t),
			this.blank = document.createElement("div"),
			this.blank.id = "_blank_" + this.id,
			this.blank.style.zIndex = -1,
			this.blank.className = "window_blank",
			jQuery(this.blank).bind("click",
			function() {
				return u.Focus(),
				!1
			}),
			this.contentCase.appendChild(this.blank);
			break;
		case "RESIZE":
			r.style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_nwse.cur'),auto",
			jQuery(r).bind("mousedown",
			function(n) {
				u.resize = "yes",
				u.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-X":
			this.resizexCase = r,
			r.style.cursor = "e-resize",
			jQuery(r).bind("mousedown",
			function(n) {
				u.resize = "resize-x",
				u.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-Y":
			this.resizeyCase = r,
			r.style.cursor = "s-resize",
			jQuery(r).bind("mousedown",
			function(n) {
				u.resize = "resize-y",
				u.PreResize(n ? n: window.event)
			});
			break;
		default:
			this.sides[f] = r,
			this.sides[f].width = r.offsetWidth,
			this.sides[f].height = r.offsetHeight,
			this.moveable && (r.style.cursor = "move", jQuery(r).bind("mousedown",
			function(n) {
				u.Mousedown(n ? n: window.event)
			}), jQuery(r).bind("mouseup",
			function(n) {
				u.Mouseup(n ? n: window.event)
			}))
		}
	}
	this.sides[1].dx = this.sides[0].width + this.sides[2].width,
	this.width > this.sides[1].dx && (this.sides[1].style.width = this.width - this.sides[1].dx + "px"),
	this.sides[3].dy = this.sides[2].height + this.sides[4].height,
	this.height > this.sides[3].dy && (this.sides[3].style.height = this.height - this.sides[3].dy + "px"),
	this.sides[5].dx = this.sides[4].width + this.sides[6].width,
	this.width > this.sides[5].dx && (this.sides[5].style.width = this.width - this.sides[5].dx + "px"),
	this.sides[7].dy = this.sides[6].height + this.sides[0].height,
	this.height > this.sides[7].dy && (this.sides[7].style.height = this.height - this.sides[7].dy + "px"),
	this.sides[0].style.left = "0px",
	this.sides[0].style.top = "0px",
	this.sides[1].style.left = this.sides[0].width + "px",
	this.sides[1].style.top = "0px",
	this.sides[2].style.right = "0px",
	this.sides[2].style.top = "0px",
	this.sides[3].style.right = "0px",
	this.sides[3].style.top = this.sides[2].height + "px",
	this.sides[4].style.right = "0px",
	this.sides[4].style.bottom = "0px",
	this.sides[5].style.left = this.sides[6].width + "px",
	this.sides[5].style.bottom = "0px",
	this.sides[6].style.left = "0px",
	this.sides[6].style.bottom = "0px",
	this.sides[7].style.left = "0px",
	this.sides[7].style.top = this.sides[0].height + "px",
	this.minWidth = _window.getMaxNumber(this.sides[0].width + this.sides[2].width, this.sides[3].width + this.sides[7].width, this.sides[4].width + this.sides[6].width) + this.minTitleWidth,
	this.minHeight = _window.getMaxNumber(this.sides[0].height + this.sides[6].height, this.sides[1].height + this.sides[5].height, this.sides[2].height + this.sides[4].height) + 2,
	this.left = this.left ? parseInt(this.left) : this.right ? _window.clientWidth - this.width - parseInt(this.right) : parseInt((_window.clientWidth - this.width) / 2),
	this.top = this.top ? parseInt(this.top) : this.bottom ? _window.clientHeight - this.height - parseInt(this.bottom) : parseInt((_window.clientHeight - this.height) / 2),
	this.left < 0 && (this.left = 0),
	_navbar.navbars[this.desktop].topbarshow == 0 && ieVersion > 0 && ieVersion < 8 ? this.top < jQuery("#navbar").height() && (this.top = jQuery("#navbar").height()) : this.top < 0 && (this.top = 0),
	this.left += document.documentElement.scrollLeft,
	this.top += document.documentElement.scrollTop,
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px",
	this.board.style.visibility = "visible",
	jQuery(this.board).bind("click",
	function() {
		return u.Focus(),
		!1
	}),
	this.status = 1
},
_window.prototype.SetPicTitle = function(n, t, i) {
	var f = this,
	h = 0,
	o = document.createElement("div"),
	s,
	c,
	u,
	r,
	e;
	for (o.className = "titleBar", s = document.createElement("div"), s.className = "titleButtonBar", c = this.titleButton.split("|"), u = 0; u < _config.titleButtons.length; u++) r = document.createElement("a"),
	r.className = _config.titleButtons[u],
	r.setAttribute("bname", _config.titleButtons[u]),
	r.title = _lang.titleButton[_config.titleButtons[u]],
	r.style.display = in_array(_config.titleButtons[u], c) ? "block": "none",
	_config.titleButtons[u] == "MAX" && (r.style.display = this.MAX ? "none": "block"),
	_config.titleButtons[u] == "RESTORE" && (r.style.display = this.MAX ? "block": "none"),
	jQuery(r).bind("click",
	function() {
		switch (this.className) {
		case "CLOSE":
			f.Close();
			break;
		case "MAX":
			f.Max();
			break;
		case "RESTORE":
			f.Restore();
			break;
		case "MIN":
			f.Min();
			break;
		case "REFRESH":
			f.SetPicContent(n, t, "REFRESH");
			break;
		case "DETAIL":
			f.SetPicContent(n, t, "DETAIL");
			break;
		case "HOME":
			f.SetPicContent(n, t, "HOME")
		}
		return ! 1
	}),
	s.appendChild(r),
	h += r.offsetWidth + parseInt(jQuery(r).css("margin-left"));
	o.appendChild(s),
	e = document.createElement("div"),
	e.className = "titleText",
	e.innerHTML = i || "dzz.cc",
	o.appendChild(e),
	this.minTitleWidth = h + 50,
	this.titleCase.innerHTML = "",
	this.titleCase.appendChild(o)
},
_window.prototype.adjust = function() {
	if (this.type != "pic") return;
	if (!document.getElementById("picWin_img")) return;
	var n = document.getElementById("picWin_img"),
	i = n.getAttribute("w"),
	t = n.getAttribute("h"),
	u = this.bodyWidth,
	r = this.bodyHeight,
	e = u / r,
	f = i / t;
	e > f ? r < t ? (n.height = r, n.width = i / t * r) : (n.width = i, n.height = t) : u < i ? (n.width = u, n.height = t / i * u) : (n.width = i, n.height = t)
},
_window.prototype.createPic = function(n, t) {
	var i = this,
	u = this.bodyHeight - 20,
	r = function(n) {
		var e;
		if (n.complete) {
			var o = loading.width,
			s = loading.height,
			h = o / s,
			i = this.bodyWidth - 20;
			i = o > i ? i: o,
			e = i / h,
			e > u && (e = u, i = e * h),
			f(t, Math.ceil(i), Math.ceil(e), o, s)
		} else setTimeout(function() {
			r(loading)
		},
		50)
	},
	e = function(n) {
		document.getElementById("img_loading_" + this.id) ? (i.loading = document.getElementById("img_loading_" + this.id), i.loading.style.left = (i.bodyWidth - 42) / 2 + "px", i.loading.style.top = (i.bodyHeight - 42) / 2 + "px", i.loading.style.display = "") : (i.loading = document.createElement("img"), i.loading.id = "img_loading_" + this.id, i.loading.src = _THEME_+"/desktop/images/default/imageloading.gif", i.loading.style.opacity = "0.8", i.loading.style.filter = "alpha(opacity=80)", i.loading.style.position = "absolute", i.loading.style.left = (i.bodyWidth - 42) / 2 + "px", i.loading.style.top = (i.bodyHeight - 42) / 2 + "px", i.loading.style.zIndex = 1e5, i.tabs.HOME.board.appendChild(i.loading)),
		loading = new Image,
		setTimeout(function() {
			r(loading)
		},
		100),
		loading.src = n
	},
	f = function(t, r, u, f, e) {
		jQuery(i.loading).fadeOut(),
		i.tabs.HOME.board.innerHTML = '<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr><td align="center" valign="middle"><img id="picWin_img" src="' + t + '" width="' + r + '" height="' + u + '" w="' + f + '" h="' + e + '" /></td></tr></table>';
		var o = _ico.getPicIcos(n);
		o.pos == 0 && o.icos.length > 1 ? (i.picop_next = document.createElement("a"), i.picop_next.className = "picop_div_next", i.picop_next.innerHTML = '<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr><td align="right" valign="middle"><div id="picop_next" class="picop_next"></div></td></tr></table>', i.picop_next.title = "\u4e0b\u4e00\u5f20", jQuery(i.picop_next).bind("click",
		function() {
			OpenPicWin(o.icos[o.pos + 1])
		}), i.tabs.HOME.board.appendChild(i.picop_next)) : o.pos == o.icos.length - 1 && o.icos.length > 1 ? (i.picop_pre = document.createElement("a"), i.picop_pre.className = "picop_div_pre", i.picop_pre.innerHTML = '<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr><td align="left" valign="middle"><div id="picop_pre" class="picop_pre"></div></td></tr></table>', i.picop_pre.title = "\u4e0a\u4e00\u5f20", jQuery(i.picop_pre).bind("click",
		function() {
			OpenPicWin(o.icos[o.pos - 1])
		}), i.tabs.HOME.board.appendChild(i.picop_pre)) : o.pos > 0 && (i.picop_next = document.createElement("div"), i.picop_next.className = "picop_div_next", i.picop_next.title = "\u4e0b\u4e00\u5f20", i.picop_next.innerHTML = '<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr><td align="right" valign="middle"><div id="picop_next" class="picop_next"></div></td></tr></table>', jQuery(i.picop_next).bind("click",
		function() {
			OpenPicWin(o.icos[o.pos + 1])
		}), i.tabs.HOME.board.appendChild(i.picop_next), i.picop_pre = document.createElement("div"), i.picop_pre.className = "picop_div_pre", i.picop_pre.title = "\u4e0a\u4e00\u5f20", i.picop_pre.innerHTML = '<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr><td align="left" valign="middle"><div id="picop_pre" class="picop_pre"></div></td></tr></table>', jQuery(i.picop_pre).bind("click",
		function() {
			OpenPicWin(o.icos[o.pos - 1])
		}), i.tabs.HOME.board.appendChild(i.picop_pre)),
		jQuery(i.picop_pre).hover(function() {
			jQuery("#picop_pre").show()
		},
		function() {
			jQuery("#picop_pre").hide()
		}),
		jQuery(i.picop_next).hover(function() {
			jQuery("#picop_next").show()
		},
		function() {
			jQuery("#picop_next").hide()
		});
		jQuery(i.tabs.HOME.board).on("contextmenu",
		function(n) {
			return _contextmenu.right_img(n ? n: window.event, t),
			!1
		})
	};
	e(t)
},
_window.prototype.SetPicContent = function(n, t, i) {
	var u, f, r, h, c, o, e, s;
	i || (i = "HOME");
	if (i == "REFRESH") {
		for (u in this.tabs) jQuery(this.tabs[u].board) && (jQuery(this.tabs[u].board).remove(), delete this.tabs[u].board),
		this.tabs[u].frame && (window.frames["ifm_" + u + "_" + this.id] && (window.frames["ifm_" + u + "_" + this.id].location = "about:blank"), jQuery(this.tabs[u].frame).remove(), delete this.tabs[u].frame),
		delete this.tabs[u];
		i = "HOME"
	}
	jQuery(this.contentCase).find(".tabdiv").css("z-index", -99999).find("iframe").css({
		width: "1px",
		height: "1px"
	}),
	jQuery("#tablediv_" + i + this.id).css("z-index", 10).find("iframe").css({
		width: "100%",
		height: "100%"
	}),
	jQuery(this.titleCase).find(".titleButtonBar A").each(function() {
		jQuery(this).removeClass(jQuery(this).attr("bname") + "1")
	}),
	jQuery(this.titleCase).find("." + i).addClass(i + "1");
	if (!this.tabs[i]) {
		this.tabs[i] = {},
		f = document.createElement("div"),
		f.className = "tabdiv",
		f.id = "tablediv_" + i + this.id,
		f.style.zIndex = 10,
		this.tabs[i].board = f,
		r = t;
		switch (i) {
		case "HOME":
			this.tabs[i].board.style.overflow = "hidden",
			this.icoid = n,
			this.contentCase.appendChild(this.tabs[i].board),
			this.createPic(n, t),
			this.contentCase.style.overflow = "hidden",
			this.contentCase.style.background = "#000",
			this.Focus();
			return;
		case "DETAIL":
			r = _config.systemurl + "&op=detail&id=" + this.icoid
		}
		r.substr(r.lastIndexOf(".")).toLowerCase() == ".swf" ? (this.tabs[i].board.innerHTML = AC_FL_RunContent("width", "100%", "height", "100%", "allowNetworking", "internal", "allowScriptAccess", "never", "src", r, "quality", "high", "bgcolor", "#ffffff", "wmode", "transparent", "allowfullscreen", "true"), this.tabs[i].board.style.overflow = "hidden") : r.substr(r.lastIndexOf(".")).toLowerCase() == ".flv" ? (this.tabs[i].board.innerHTML = AC_FL_RunContent("width", "100%", "height", "100%", "allowNetworking", "internal", "allowScriptAccess", "never", "src", STATICURL + "image/common/flvplayer.swf", "flashvars", "file=" + encodeURI(r), "quality", "high", "bgcolor", "#ffffff", "wmode", "transparent", "allowfullscreen", "true"), this.tabs[i].board.style.overflow = "hidden", this.bodyHeight < 0 && (this.bodyHeight = 300), jQuery(this.loadding).fadeOut()) : this.tabs[i].frame ? jQuery(this.tabs[i].frame).src != r && (window.frames["ifm_" + i + "_" + this.id].location = r) : (this.loadding || (this.loadding = jQuery('<div id="window_content_loadding' + this.id + '" class="window_content_loadding"><div class="window_content_loadding_back"><table width="100%" height="100%"><tr><td align="center" valign="middle"><div class="window_content_loadding_back_img"></div></td></tr></table></div><div class="window_content_loadding_moving"></div></div>').appendTo(this.contentCase).get(0)), h = {
			uid: _config.space.uid,
			ukey: _config.ukey,
			username: _config.space.username,
			password: _config.space.password,
			self: _config.space.self,
			winid: this.id,
			tab: i,
			time: +new Date
		},
		c = encodeURIComponent(jQuery.toJSON(h)), o = _ico.icos[n] && _ico.icos[n].haveflash > 0 ? "appIframe app_swf": "appIframe", this.loaddingTimer = setTimeout(function() {
			jQuery(e.loadding).fadeOut()
		},
		5e3), e = this, s = jQuery('<iframe frameborder="0" name="' + c + '" id="ifm_' + i + "_" + this.id + '" marginheight="0" marginwidth="0" allowtransparency="true" src="' + r + '" class="' + o + '"  style="overflow-x: hidden;" onload="jQuery(\'#window_content_loadding' + this.id + "').fadeOut();clearTimeout(" + e.loaddingTimer + ');"></iframe>').appendTo(this.tabs[i].board), this.bodyHeight < 0 && (this.bodyHeight = 300), this.tabs[i].frame = s.get(0)),
		this.contentCase.appendChild(f)
	}
	this.Focus()
},
_window.OpenFolderWin = function(n, t, i) {
	var r = new _window(i, t);
	return r.type = "folder",
	r.fid = _config.sourcedata.icos[n].oid,
	r.topfid = t,
	r.icoid = n,
	r.taskid = n,
	r.CreatFolderWin(n, _config.sourcedata.icos[n].name),
	r
},
_window.prototype.CreatFolderWin = function(n, t) {
	var f, r, e, u, i;
	this.Csequence = this.Sequence.push(n),
	this.board = document.createElement("div"),
	this.board.className = "window " + (_config.thame.custom.custom_filemanage || _config.thame.system.filemanage || "dzz") + "_filemanage",
	this.board.style.position = "absolute",
	this.board.id = this.id,
	this.board.style.zIndex = this.zIndex,
	this.board.style.visibility = "hidden",
	this.desktop = _config.currentDesktop,
	document.getElementById("_body_" + _config.currentDesktop).appendChild(this.board),
	_window.clientHeight || (this.board.style.left = "100%", this.board.style.top = "100%", _window.clientWidth = this.board.offsetLeft, _window.clientHeight = this.board.offsetTop),
	f = ["LEFT_TOP", "TOP", "RIGHT_TOP", "RIGHT", "RIGHT_BOTTOM", "BOTTOM", "LEFT_BOTTOM", "LEFT", "SHADOW_TOP", "SHADOW_RIGHT", "SHADOW_BOTTOM", "SHADOW_LEFT"],
	this.sides = [],
	f[f.length] = "TITLE",
	f[f.length] = "CONTENT",
	r = this,
	i = document.createElement("div");
	if (this.resize != "no") for (e = this.resize.split("|"), u = 0; u < e.length; u++) f[f.length] = e[u];
	for (u = 0; u < f.length; u++) {
		i = document.createElement("div"),
		i.className = f[u],
		i.style.position = "absolute",
		i.innerHTML = '<div class="' + f[u] + '_inner inner" style="position:absolute;"></div>',
		this.board.appendChild(i);
		switch (f[u]) {
		case "TITLE":
			this.titleCase = i,
			i.style.height = i.offsetHeight + "px",
			i.style.width = this.bodyWidth + "px",
			this.SetFolderTitle(t),
			jQuery(this.titleCase).bind("dblclick",
			function() {
				return r.MAX ? r.Restore() : r.Max(),
				!1
			}),
			this.moveable && (jQuery(this.titleCase).bind("mousedown",
			function(n) {
				r.Mousedown(n ? n: window.event)
			}), jQuery(this.titleCase).bind("mouseup",
			function(n) {
				r.Mouseup(n ? n: window.event)
			}));
			break;
		case "CONTENT":
			this.contentCase = i,
			i.style.width = this.bodyWidth + "px",
			this.bodyHeight > 0 && (i.style.height = this.bodyHeight + "px"),
			this.SetFolderContent(n),
			i.style.left = this.sides[7].width + "px",
			i.style.top = this.sides[1].height + "px",
			this.width = this.bodyWidth + this.sides[3].width + this.sides[7].width,
			this.height = this.bodyHeight + this.sides[1].height + this.sides[5].height,
			this.board.style.height = this.height + "px",
			this.board.style.width = this.width + "px",
			this.blank = document.createElement("div"),
			this.blank.id = "_blank_" + this.id,
			this.blank.style.zIndex = -1,
			this.blank.className = "window_blank",
			this.moveable ? (jQuery(this.blank).bind("mousedown",
			function(n) {
				r.Mousedown(n ? n: window.event)
			}), jQuery(this.blank).bind("mouseup",
			function(n) {
				r.Mouseup(n ? n: window.event)
			})) : jQuery(this.blank).bind("mousedown",
			function() {
				r.Focus()
			}),
			this.contentCase.appendChild(this.blank);
			break;
		case "RESIZE":
			i.style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_nwse.cur'),auto",
			jQuery(i).bind("mousedown",
			function(n) {
				r.resize = "yes",
				r.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-X":
			this.resizexCase = i,
			i.style.cursor = "e-resize",
			jQuery(i).bind("mousedown",
			function(n) {
				r.resize = "resize-x",
				r.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-Y":
			this.resizeyCase = i,
			i.style.cursor = "s-resize",
			jQuery(i).bind("mousedown",
			function(n) {
				r.resize = "resize-y",
				r.PreResize(n ? n: window.event)
			});
			break;
		default:
			this.sides[u] = i,
			this.sides[u].width = i.offsetWidth,
			this.sides[u].height = i.offsetHeight,
			this.moveable && (i.style.cursor = "move", jQuery(i).bind("mousedown",
			function(n) {
				r.Mousedown(n ? n: window.event)
			}), jQuery(i).bind("mouseup",
			function(n) {
				r.Mouseup(n ? n: window.event)
			}))
		}
	}
	this.sides[1].dx = this.sides[0].width + this.sides[2].width,
	this.width > this.sides[1].dx && (this.sides[1].style.width = this.width - this.sides[1].dx + "px"),
	this.sides[3].dy = this.sides[2].height + this.sides[4].height,
	this.height > this.sides[3].dy && (this.sides[3].style.height = this.height - this.sides[3].dy + "px"),
	this.sides[5].dx = this.sides[4].width + this.sides[6].width,
	this.width > this.sides[5].dx && (this.sides[5].style.width = this.width - this.sides[5].dx + "px"),
	this.sides[7].dy = this.sides[6].height + this.sides[0].height,
	this.height > this.sides[7].dy && (this.sides[7].style.height = this.height - this.sides[7].dy + "px"),
	this.sides[0].style.left = "0px",
	this.sides[0].style.top = "0px",
	this.sides[1].style.left = this.sides[0].width + "px",
	this.sides[1].style.top = "0px",
	this.sides[2].style.right = "0px",
	this.sides[2].style.top = "0px",
	this.sides[3].style.right = "0px",
	this.sides[3].style.top = this.sides[2].height + "px",
	this.sides[4].style.right = "0px",
	this.sides[4].style.bottom = "0px",
	this.sides[5].style.left = this.sides[6].width + "px",
	this.sides[5].style.bottom = "0px",
	this.sides[6].style.left = "0px",
	this.sides[6].style.bottom = "0px",
	this.sides[7].style.left = "0px",
	this.sides[7].style.top = this.sides[0].height + "px",
	this.minWidth = _window.getMaxNumber(this.sides[0].width + this.sides[2].width, this.sides[3].width + this.sides[7].width, this.sides[4].width + this.sides[6].width) + this.minTitleWidth,
	this.minHeight = _window.getMaxNumber(this.sides[0].height + this.sides[6].height, this.sides[1].height + this.sides[5].height, this.sides[2].height + this.sides[4].height) + 2,
	this.left = this.left ? parseInt(this.left) : this.right ? _window.clientWidth - this.width - parseInt(this.right) : parseInt((_window.clientWidth - this.width) / 2),
	this.top = this.top ? parseInt(this.top) : this.bottom ? _window.clientHeight - this.height - parseInt(this.bottom) : parseInt((_window.clientHeight - this.height) / 2),
	this.left < 0 && (this.left = 0),
	_navbar.navbars[this.desktop].topbarshow == 0 && ieVersion > 0 && ieVersion < 8 ? this.top < jQuery("#navbar").height() && (this.top = jQuery("#navbar").height()) : this.top < 0 && (this.top = 0),
	this.left += document.documentElement.scrollLeft,
	this.top += document.documentElement.scrollTop,
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px",
	this.board.style.visibility = "visible",
	this.status = 1,
	jQuery(this.board).bind("mousedown",
	function() {
		r.Focus()
	})
},
_window.prototype.SetFolderTitle = function() {
	var o, d, v, a, y, k, s, u, e, b, c, f, t, i, l, w;
	jQuery(this.titleCase).empty();
	var p = document.createDocumentFragment(),
	r = this,
	g = 0,
	h = document.createElement("div");
	for (h.className = "titleBar", p.appendChild(h), o = document.createElement("div"), o.className = "titleButtonBar", h.appendChild(o), d = this.titleButton.split("|"), t = 0; t < _config.titleButtons.length; t++) i = document.createElement("a"),
	i.className = _config.titleButtons[t],
	i.setAttribute("bname", _config.titleButtons[t]),
	i.title = _lang.titleButton[_config.titleButtons[t]],
	i.style.display = in_array(_config.titleButtons[t], d) ? "block": "none",
	jQuery(i).bind("click",
	function() {
		switch (this.className) {
		case "CLOSE":
			r.Close();
			break;
		case "MAX":
			r.Max();
			break;
		case "RESTORE":
			r.Restore();
			break;
		case "MIN":
			r.Min()
		}
		return ! 1
	}),
	o.appendChild(i);
	for (v = document.createElement("a"), v.className = "folderimg", v.innerHTML = '<img src="' + _config.sourcedata.icos[this.icoid].img + '" onload="fixpng(this)">', o.appendChild(v), h.appendChild(o), a = document.createElement("div"), a.className = "titleText", y = "", t = this.topfid.length - 1; t >= 0; t--) k = _ico.geticoidFromfid(this.topfid[t]),
	y += '<a node="' + k + '" class="title_path_item">' + mb_cutstr_nohtml(_config.sourcedata.folder[this.topfid[t]].fname, 30, "...") + "</a>",
	t > 0 && (y += '<span class="title_path_item_speacer">&nbsp;->&nbsp;</span>');
	for (a.innerHTML = y || "dzz.cc", h.appendChild(a), s = document.createElement("div"), s.className = "folderBar", p.appendChild(s), u = document.createElement("div"), u.className = "folderButtonBar", s.appendChild(u), e = "", e += this.Csequence <= 1 ? "<a id='seq_back_" + this.id + "' title='" + _lang.window.back + "' class='BACK1'></a>": "<a id='seq_back_" + this.id + "' title='" + _lang.window.back + "' class='BACK2' onclick=\"" + this.string + ".ChangeSequence('back','folder')\"></a>", e += this.Csequence < this.Sequence.length ? "<a id='seq_next_" + this.id + "' title='" + _lang.window.next + "' class='NEXT2' onclick=\"" + this.string + ".ChangeSequence('next','folder')\"></a>": "<a id='seq_next_" + this.id + "' title='" + _lang.window.next + "' class='NEXT1' ></a>", b = "", t = 0; t < 5; t++) c = "",
	c = _filemanage.view == t ? "filemanage_view" + t + "_2": "filemanage_view" + t + "_1",
	b += '<a id="' + this.id + "_view" + t + '" index="filemanage_view' + t + '_" iconview="' + t + '" title="' + _lang.filemanage_view[t] + '" class="arrange ' + c + '"></a>';
	for (e += b, u.innerHTML = e, jQuery(u).find(".arrange").bind("click",
	function() {
		return _filemanage.cons[r.filemanageid].view = parseInt(jQuery(this).attr("iconview")),
		_filemanage.cons[r.filemanageid].showIcos(r.id, document.getElementById("searchInput_" + r.id).value),
		jQuery(r.titleCase).find(".arrange").each(function() {
			jQuery(this).removeClass(jQuery(this).attr("index") + "2").addClass(jQuery(this).attr("index") + "1")
		}),
		jQuery(this).removeClass(jQuery(this).attr("index") + "1").addClass(jQuery(this).attr("index") + "2"),
		!1
	}), i = document.createElement("a"), i.title = _lang.filemanage_paixu, i.id = "filemanage_disp" + this.id, i.className = "filemanage_disp1", jQuery(i).bind("click",
	function(n) {
		_contextmenu.paixu_down(n ? n: window.event, r.id)
	}), u.appendChild(i), f = ["NEWFOLDER"/*, "UPLOAD"*/], t = 0; t < f.length; t++) i = document.createElement("a"),
	i.className = f[t] + " operator",
	i.setAttribute("bname", f[t]),
	i.title = _lang.folderTabs[f[t]],
	f[t] == "UPLOAD" && (i.id = "button_" + f[t] + this.id),
	jQuery(i).bind("mousedown",
	function() {
		return jQuery(this).removeClass(jQuery(this).attr("bname")).addClass(jQuery(this).attr("bname") + "_disable"),
		!1
	}),
	jQuery(i).bind("mouseup",
	function() {
		switch (jQuery(this).attr("bname")) {
		case "NEWFOLDER":
			if (r.filemanageid) {
				var t = "",
				n = r.filemanageid.split("-");
				n[0] == "f" ? _ajax.newFolder("icosContainer_folder_" + n[1]) : n[0] == "d" && _ajax.newFolder("icosContainer_body_" + n[1])
			}
		}
		jQuery(this).addClass(jQuery(this).attr("bname")).removeClass(jQuery(this).attr("bname") + "_disable")
	}),
	u.appendChild(i);
	i = document.createElement("a"),
	i.title = _lang.filemanage_other,
	i.id = "filemanage_other" + this.id,
	i.className = "filemanage_other1 operator",
	jQuery(i).bind("click",
	function(n) {
		_contextmenu.other_down(n ? n: window.event, r.id)
	}),
	u.appendChild(i),
	l = document.createElement("div"),
	l.className = "filemanage_search ",
	l.innerHTML = '<div class="searchInputContainer"><input onkeydown="if(event.keyCode==13){_filemanage.searchsubmit(\'' + this.id + "');}\"  onblur=\"if(this.value==''){this.value='" + _lang.fsearch + "';}\" onfocus=\"if(this.value=='" + _lang.fsearch + "'){this.value='';}else{this.select();}\" value=\"" + _lang.fsearch + '" id="searchInput_' + this.id + '" class="searchInput"><a onclick="_filemanage.searchsubmit(\'' + this.id + '\');return false;" href="javascript:void(0);return false;" class="searchBtn" id="searchBtn_' + this.id + '"></a></div>',
	u.appendChild(l),
	this.titleCase.appendChild(p),
	jQuery(this.titleCase).find(".title_path_item").each(function() {
		jQuery(this).bind("click",
		function() {
			OpenFolderWin(jQuery(this).attr("node"))
		})
	}),
	w = 0,
	jQuery(s).find("a").each(function() {
		w += jQuery(this).outerWidth() * 1
	}),
	this.minTitleWidth = w
},
_window.prototype.SetFolderContent = function(n) {
	var u, i, t, r;
	document.getElementById("content_" + this.id) || jQuery('<div id="content_' + this.id + '" style="width:100%;height:100%;position:absolute">').appendTo(jQuery(this.contentCase));
	if (_config.sourcedata.folder[this.fid].opened) {
		for (u = {},
		i = _config.sourcedata.folder[this.fid].ids, t = 0; t < i.length; t++) u[i[t]] = _config.sourcedata.icos[i[t]];
		r = _filemanage.cons["f-" + this.fid + "-" + _config.uid + "_" + this.id] ? _filemanage.cons["f-" + this.fid + "-" + _config.uid + "_" + this.id] : new _filemanage("f-" + this.fid + "-" + _config.uid, this.id, u, "icosContainer_folder_" + this.fid),
		r.showIcos(this.id)
	} else _config.sendConfig(),
	ajaxget(_config.systemurl + "&op=explorer&do=filemanage&id=f-" + this.fid + "-" + _config.uid + "&icoid=" + n + "&winid=" + this.id + "&t=" + +new Date, "content_" + this.id, "content_" + this.id);
	this.Focus(1)
},
_window.OpenFileManage = function(n, t) {
	var i = new _window(t, "sys_filemanage");
	return i.type = "filemanage",
	i.taskid = "sys_filemanage",
	i.Sequence = [],
	i.fid = null,
	i.CreatFileManageWin("sys_filemanage", n),
	i
},
_window.prototype.CreatFileManageWin = function(n, t) {
	var f, r, e, u, i;
	this.board = document.createElement("div"),
	this.board.className = "window " + (_config.thame.custom.custom_filemanage || _config.thame.system.filemanage || "dzz") + "_filemanage",
	this.board.style.position = "absolute",
	this.board.id = this.id,
	this.board.style.zIndex = this.zIndex,
	this.board.style.visibility = "hidden",
	this.desktop = _config.currentDesktop,
	document.getElementById("_body_" + _config.currentDesktop).appendChild(this.board),
	_window.clientHeight || (this.board.style.left = "100%", this.board.style.top = "100%", _window.clientWidth = this.board.offsetLeft, _window.clientHeight = this.board.offsetTop),
	f = ["LEFT_TOP", "TOP", "RIGHT_TOP", "RIGHT", "RIGHT_BOTTOM", "BOTTOM", "LEFT_BOTTOM", "LEFT", "SHADOW_TOP", "SHADOW_RIGHT", "SHADOW_BOTTOM", "SHADOW_LEFT"],
	this.sides = [],
	f[f.length] = "TITLE",
	f[f.length] = "CONTENT",
	r = this,
	i = document.createElement("div");
	if (this.resize != "no") for (e = this.resize.split("|"), u = 0; u < e.length; u++) f[f.length] = e[u];
	for (u = 0; u < f.length; u++) {
		i = document.createElement("div"),
		i.className = f[u],
		i.style.position = "absolute",
		i.innerHTML = '<div class="' + f[u] + '_inner inner" style="position:absolute;"></div>',
		this.board.appendChild(i);
		switch (f[u]) {
		case "TITLE":
			this.titleCase = i,
			i.style.height = i.offsetHeight + "px",
			i.style.width = this.bodyWidth + "px",
			this.SetFileManageTitle(t),
			jQuery(this.titleCase).bind("dblclick",
			function() {
				return r.MAX ? r.Restore() : r.Max(),
				!1
			}),
			this.moveable && (jQuery(this.titleCase).bind("mousedown",
			function(n) {
				r.Mousedown(n ? n: window.event)
			}), jQuery(this.titleCase).bind("mouseup",
			function(n) {
				r.Mouseup(n ? n: window.event)
			}));
			break;
		case "CONTENT":
			this.contentCase = i,
			i.style.width = this.bodyWidth + "px",
			this.bodyHeight > 0 && (i.style.height = this.bodyHeight + "px"),
			i.style.left = this.sides[7].width + "px",
			i.style.top = this.sides[1].height + "px",
			this.width = this.bodyWidth + this.sides[3].width + this.sides[7].width,
			this.height = this.bodyHeight + this.sides[1].height + this.sides[5].height,
			this.board.style.height = this.height + "px",
			this.board.style.width = this.width + "px",
			this.blank = document.createElement("div"),
			this.blank.id = "_blank_" + this.id,
			this.blank.style.zIndex = -1,
			this.blank.className = "window_blank",
			this.moveable ? (jQuery(this.blank).bind("mousedown",
			function(n) {
				r.Mousedown(n ? n: window.event)
			}), jQuery(this.blank).bind("mouseup",
			function(n) {
				r.Mouseup(n ? n: window.event)
			})) : jQuery(this.blank).bind("mousedown",
			function() {
				r.Focus(1)
			}),
			this.contentCase.appendChild(this.blank);
			break;
		case "RESIZE":
			i.style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_nwse.cur'),auto",
			jQuery(i).bind("mousedown",
			function(n) {
				r.resize = "yes",
				r.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-X":
			this.resizexCase = i,
			i.style.cursor = "e-resize",
			jQuery(i).bind("mousedown",
			function(n) {
				r.resize = "resize-x",
				r.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-Y":
			this.resizeyCase = i,
			i.style.cursor = "s-resize",
			jQuery(i).bind("mousedown",
			function(n) {
				r.resize = "resize-y",
				r.PreResize(n ? n: window.event)
			});
			break;
		default:
			this.sides[u] = i,
			this.sides[u].width = i.offsetWidth,
			this.sides[u].height = i.offsetHeight,
			this.moveable && (i.style.cursor = "move", jQuery(i).bind("mousedown",
			function(n) {
				r.Mousedown(n ? n: window.event)
			}), jQuery(i).bind("mouseup",
			function(n) {
				r.Mouseup(n ? n: window.event)
			}))
		}
	}
	this.sides[1].dx = this.sides[0].width + this.sides[2].width,
	this.width > this.sides[1].dx && (this.sides[1].style.width = this.width - this.sides[1].dx + "px"),
	this.sides[3].dy = this.sides[2].height + this.sides[4].height,
	this.height > this.sides[3].dy && (this.sides[3].style.height = this.height - this.sides[3].dy + "px"),
	this.sides[5].dx = this.sides[4].width + this.sides[6].width,
	this.width > this.sides[5].dx && (this.sides[5].style.width = this.width - this.sides[5].dx + "px"),
	this.sides[7].dy = this.sides[6].height + this.sides[0].height,
	this.height > this.sides[7].dy && (this.sides[7].style.height = this.height - this.sides[7].dy + "px"),
	this.sides[0].style.left = "0px",
	this.sides[0].style.top = "0px",
	this.sides[1].style.left = this.sides[0].width + "px",
	this.sides[1].style.top = "0px",
	this.sides[2].style.right = "0px",
	this.sides[2].style.top = "0px",
	this.sides[3].style.right = "0px",
	this.sides[3].style.top = this.sides[2].height + "px",
	this.sides[4].style.right = "0px",
	this.sides[4].style.bottom = "0px",
	this.sides[5].style.left = this.sides[6].width + "px",
	this.sides[5].style.bottom = "0px",
	this.sides[6].style.left = "0px",
	this.sides[6].style.bottom = "0px",
	this.sides[7].style.left = "0px",
	this.sides[7].style.top = this.sides[0].height + "px",
	this.minWidth = _window.getMaxNumber(this.sides[0].width + this.sides[2].width, this.sides[3].width + this.sides[7].width, this.sides[4].width + this.sides[6].width) + this.minTitleWidth,
	this.minHeight = _window.getMaxNumber(this.sides[0].height + this.sides[6].height, this.sides[1].height + this.sides[5].height, this.sides[2].height + this.sides[4].height) + 2,
	this.left = this.left ? parseInt(this.left) : this.right ? _window.clientWidth - this.width - parseInt(this.right) : parseInt((_window.clientWidth - this.width) / 2),
	this.top = this.top ? parseInt(this.top) : this.bottom ? _window.clientHeight - this.height - parseInt(this.bottom) : parseInt((_window.clientHeight - this.height) / 2),
	this.left < 0 && (this.left = 0),
	_navbar.navbars[this.desktop].topbarshow == 0 && ieVersion > 0 && ieVersion < 8 ? this.top < jQuery("#navbar").height() && (this.top = jQuery("#navbar").height()) : this.top < 0 && (this.top = 0),
	this.left += document.documentElement.scrollLeft,
	this.top += document.documentElement.scrollTop,
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px",
	this.board.style.visibility = "visible",
	this.status = 1,
	this.SetFileManageContent(),
	jQuery(this.board).bind("mousedown",
	function() {
		r.Focus()
	})
},
_window.prototype.SetFileManageTitle = function(n) {
	var h, b, v, o, s, u, e, w, l, f, i, t, c, p;
	jQuery(this.titleCase).empty();
	var y = document.createDocumentFragment(),
	r = this,
	k = 0,
	a = document.createElement("div");
	for (a.className = "titleBar", y.appendChild(a), h = document.createElement("div"), h.className = "titleButtonBar", a.appendChild(h), b = this.titleButton.split("|"), i = 0; i < _config.titleButtons.length; i++) t = document.createElement("a"),
	t.className = _config.titleButtons[i],
	t.setAttribute("bname", _config.titleButtons[i]),
	t.title = _lang.titleButton[_config.titleButtons[i]],
	t.style.display = in_array(_config.titleButtons[i], b) ? "block": "none",
	jQuery(t).bind("click",
	function() {
		switch (this.className) {
		case "CLOSE":
			r.Close();
			break;
		case "MAX":
			r.Max();
			break;
		case "RESTORE":
			r.Restore();
			break;
		case "MIN":
			r.Min()
		}
		return ! 1
	}),
	h.appendChild(t);
	for (v = document.createElement("a"), v.className = "folderimg", v.innerHTML = '<img id="title_img" src=_THEME_+"/desktop/images/default/filemanage.png" onload="fixpng(this)">', h.appendChild(v), o = document.createElement("div"), o.className = "titleText", o.id = "title_text_" + this.id, o.innerHTML = n || "dzz.cc", a.appendChild(o), s = document.createElement("div"), s.className = "folderBar", y.appendChild(s), u = document.createElement("div"), u.className = "folderButtonBar", s.appendChild(u), e = "", e += this.Csequence <= 1 ? "<a id='seq_back_" + this.id + "' title='" + _lang.window.back + "' class='BACK1' onclick=\"" + this.string + ".ChangeSequence('back','filemanage')\"></a>": "<a id='seq_back_" + this.id + "' title='" + _lang.window.back + "' class='BACK2' onclick=\"" + this.string + ".ChangeSequence('back','filemanage')\"></a>", e += this.Csequence < this.Sequence.length ? "<a id='seq_next_" + this.id + "' title='" + _lang.window.next + "' class='NEXT2' onclick=\"" + this.string + ".ChangeSequence('next','filemanage')\"></a>": "<a id='seq_next_" + this.id + "' title='" + _lang.window.next + "' class='NEXT1' onclick=\"" + this.string + ".ChangeSequence('next','filemanage')\"></a>", w = "", i = 0; i < 5; i++) l = "",
	l = _filemanage.view == i ? "filemanage_view" + i + "_2": "filemanage_view" + i + "_1",
	w += '<a id="' + this.id + "_view" + i + '" index="filemanage_view' + i + '_" iconview="' + i + '" title="' + _lang.filemanage_view[i] + '" class="arrange ' + l + '"></a>';
	e += w,
	u.innerHTML = e,
	jQuery(u).find(".arrange").bind("click",
	function() {
		return _filemanage.cons[r.filemanageid] ? (_filemanage.cons[r.filemanageid].view = parseInt(jQuery(this).attr("iconview")), _filemanage.cons[r.filemanageid].showIcos(r.id, document.getElementById("searchInput_" + r.id).value), jQuery(r.titleCase).find(".arrange").each(function() {
			jQuery(this).removeClass(jQuery(this).attr("index") + "2").addClass(jQuery(this).attr("index") + "1")
		}), jQuery(this).removeClass(jQuery(this).attr("index") + "1").addClass(jQuery(this).attr("index") + "2"), !1) : !1
	}),
	t = document.createElement("a"),
	t.title = _lang.filemanage_paixu,
	t.id = "filemanage_disp" + this.id,
	t.className = "filemanage_disp1",
	jQuery(t).bind("click",
	function(n) {
		_contextmenu.paixu_down(n ? n: window.event, r.id)
	}),
	u.appendChild(t);
	if (_config.space.self) {
		for (f = ["NEWFOLDER", "UPLOAD"], i = 0; i < f.length; i++) t = document.createElement("a"),
		t.className = f[i] + " operator",
		t.setAttribute("bname", f[i]),
		t.title = _lang.folderTabs[f[i]],
		f[i] == "UPLOAD" && (t.id = "button_" + f[i] + this.id),
		jQuery(t).bind("mousedown",
		function() {
			switch (jQuery(this).attr("bname")) {
			case "NEWFOLDER":
				if (r.filemanageid) {
					var i = "",
					t = r.filemanageid.split("-");
					t[0] == "f" ? _ajax.newFolder("icosContainer_folder_" + t[1]) : t[0] == "d" && _ajax.newFolder("icosContainer_body_" + t[1])
				}
			}
			return jQuery(this).removeClass(jQuery(this).attr("bname")).addClass(jQuery(this).attr("bname") + "_disable"),
			!1
		}),
		jQuery(t).bind("mouseup",
		function() {
			return jQuery(this).addClass(jQuery(this).attr("bname")).removeClass(jQuery(this).attr("bname") + "_disable"),
			!1
		}),
		u.appendChild(t);
		t = document.createElement("a"),
		t.title = _lang.filemanage_other,
		t.id = "filemanage_other" + this.id,
		t.className = "filemanage_other1 operator",
		jQuery(t).bind("click",
		function(n) {
			_contextmenu.other_down(n ? n: window.event, r.id)
		}),
		u.appendChild(t)
	}
	c = document.createElement("div"),
	c.className = "filemanage_search",
	c.innerHTML = '<div class="searchInputContainer"><input onkeydown="if(event.keyCode==13){_filemanage.searchsubmit(\'' + this.id + "');}\"  onblur=\"if(this.value==''){this.value='" + _lang.fsearch + "';}\" onfocus=\"if(this.value=='" + _lang.fsearch + "'){this.value='';}else{this.select();}\" value=\"" + _lang.fsearch + '" id="searchInput_' + this.id + '" class="searchInput"><a onclick="_filemanage.searchsubmit(\'' + this.id + '\');return false;" href="javascript:void(0);return false;" class="searchBtn" id="searchBtn_' + this.id + '"></a></div>',
	u.appendChild(c),
	this.titleCase.appendChild(y),
	p = 0,
	jQuery(s).find("a").each(function() {
		p += jQuery(this).outerWidth() * 1
	}),
	this.minTitleWidth = p
},
_window.prototype.SetFileManageContent = function() {
	var t = this,
	f = document.createElement("div"),
	u,
	r,
	i;
	f.id = "jstree_area",
	f.className = "filemanage-left",
	this.contentCase.appendChild(f),
	u = document.createElement("div"),
	u.id = "content_" + this.id,
	u.className = "filemanage-right",
	this.contentCase.appendChild(u),
	r = document.createElement("div"),
	r.id = "filemanage-ltdrager",
	r.className = "filemanage-ltdrager",
	r.innerHTML = '<div class="inner"></div>',
	jQuery(r).bind("mousedown",
	function(n) {
		t.ltdrager_start(n ? n: window.event)
	}),
	this.contentCase.appendChild(r),
	i = "dzz/system",
	jQuery("#jstree_area").jstree({
		plugins: ["themes", "json_data", "ui", "types", "cookies", "hotkeys"],
		json_data: {
			ajax: {
				url: _config.systemurl + "&op=explorer",
				data: function(n) {
					return {
						"do": "get_children",
						uid: _config.space.uid,
						id: n.attr ? n.attr("id") : 0,
						t: +new Date
					}
				},
				success: function(n) {
					var t, r, i;
					if (!n) return;
					t = n[n.length - 1];
					if (t.icosdata) for (r in t.icosdata) _config.sourcedata.icos[r] = t.icosdata[r],
					_config.sourceids.icos.push(r);
					if (t.folderdata) for (i in t.folderdata) _config.sourcedata.folder || (_config.sourcedata.folder = {}),
					_config.sourcedata.folder[i] = t.folderdata[i],
					_config.sourceids.folderids || (_config.sourceids.folderids = []),
					_config.sourceids.folderids.push(i)
				}
			}
		},
		themes: {
			theme: "default",
			dots: !1
		},
		types: {
			max_depth: -2,
			max_children: -2,
			valid_children: ["drive"],
			types: {
				"default": {
					valid_children: "none",
					icon: {
						image: i + "/images/file.png"
					}
				},
				folder: {
					valid_children: ["default", "folder"],
					icon: {
						image: i + "/images/folder.png"
					}
				},
				type: {
					valid_children: [],
					icon: {
						image: i + "/images/type.png"
					},
					start_drag: !1,
					move_node: !1,
					delete_node: !1,
					create_node: !1,
					rename: !1,
					remove: !1
				},
				position: {
					valid_children: [],
					icon: {
						image: i + "/images/position.png"
					},
					start_drag: !1,
					move_node: !1,
					delete_node: !1,
					create_node: !1,
					rename: !1,
					remove: !1
				},
				blog: {
					valid_children: [],
					icon: {
						image: i + "/images/blog.png"
					},
					start_drag: !1,
					move_node: !1,
					delete_node: !1,
					create_node: !1,
					rename: !1,
					remove: !1
				},
				image: {
					valid_children: [],
					icon: {
						image: i + "/images/image.png"
					},
					start_drag: !1,
					move_node: !1,
					delete_node: !1,
					create_node: !1,
					rename: !1,
					remove: !1
				},
				link: {
					valid_children: [],
					icon: {
						image: i + "/images/link.png"
					},
					start_drag: !1,
					move_node: !1,
					delete_node: !1,
					create_node: !1,
					rename: !1,
					remove: !1
				},
				music: {
					valid_children: [],
					icon: {
						image: i + "/images/music.png"
					},
					start_drag: !1,
					move_node: !1,
					delete_node: !1,
					rename: !1,
					create_node: !1,
					remove: !1
				},
				video: {
					valid_children: [],
					icon: {
						image: i + "/images/video.png"
					},
					start_drag: !1,
					move_node: !1,
					delete_node: !1,
					rename: !1,
					create_node: !1,
					remove: !1
				},
				app: {
					valid_children: [],
					icon: {
						image: i + "/images/app.png"
					},
					start_drag: !1,
					move_node: !1,
					delete_node: !1,
					rename: !1,
					create_node: !1,
					remove: !1
				},
				other: {
					valid_children: [],
					icon: {
						image: i + "/images/other.png"
					},
					start_drag: !1,
					move_node: !1,
					delete_node: !1,
					rename: !1,
					create_node: !1,
					remove: !1
				},
				user: {
					valid_children: [],
					icon: {
						image: i + "/images/user.png"
					},
					start_drag: !1,
					move_node: !1,
					delete_node: !1,
					create_node: !1,
					rename: !1,
					remove: !1
				},
				friend: {
					valid_children: [],
					icon: {
						image: i + "/images/friend.png"
					},
					start_drag: !1,
					move_node: !1,
					delete_node: !1,
					rename: !1,
					create_node: !1,
					remove: !1
				},
				alluser: {
					valid_children: [],
					icon: {
						image: i + "/images/alluser.png"
					},
					start_drag: !1,
					move_node: !1,
					delete_node: !1,
					create_node: !1,
					rename: !1,
					remove: !1
				},
				desktop: {
					valid_children: ["folder"],
					icon: {
						image: i + "/images/desktop.png"
					},
					start_drag: !1,
					move_node: !1,
					delete_node: !1,
					remove: !1,
					rename: !1
				}
			}
		}
	}).bind("select_node.jstree",
	function(n, i) {
		var o, e, r, s;
		i.rslt.obj.hasClass("jstree-closed") && jQuery("#jstree_area").jstree("open_node", i.rslt.obj);
		if (i.rslt.obj.attr("rel") != "type" && i.rslt.obj.attr("rel") != "position" && i.rslt.obj.attr("rel") != "user") {
			_filemanage.cons[i.rslt.obj.attr("id") + "_" + t.id] ? (o = _filemanage.cons[i.rslt.obj.attr("id") + "_" + t.id], o.showIcos(t.id)) : (_config.sendConfig(), e = i.rslt.obj.attr("icoid"), e ? _config.Permission("open", "", e) ? ajaxget(_config.systemurl + "&op=explorer&do=filemanage&id=" + i.rslt.obj.attr("id") + "&icoid=" + i.rslt.obj.attr("icoid") + "&winid=" + t.id + "&t=" + +new Date, "content_" + t.id, "content_" + t.id) : jQuery("#content_" + t.id).empty() : ajaxget(_config.systemurl + "&op=explorer&do=filemanage&id=" + i.rslt.obj.attr("id") + "&icoid=" + i.rslt.obj.attr("icoid") + "&winid=" + t.id + "&t=" + +new Date, "content_" + t.id, "content_" + t.id));
			if (t.Csequence < t.Sequence.length) t.Csequence <= 1 ? jQuery("#seq_back_" + t.id).removeClass("BACK2").addClass("BACK1") : jQuery("#seq_back_" + t.id).removeClass("BACK1").addClass("BACK2"),
			jQuery("#seq_next_" + t.id).removeClass("NEXT1").addClass("NEXT2");
			else {
				for (r = 0; r < t.Sequence.length; r++) t.Sequence[r] == i.rslt.obj.attr("id") && t.Sequence.splice(r, 1);
				t.Csequence = t.Sequence.push(i.rslt.obj.attr("id")),
				t.Csequence <= 1 ? jQuery("#seq_back_" + t.id).removeClass("BACK2").addClass("BACK1") : jQuery("#seq_back_" + t.id).removeClass("BACK1").addClass("BACK2"),
				jQuery("#seq_next_" + t.id).removeClass("NEXT2").addClass("NEXT1")
			}
			var u = jQuery("#jstree_area").jstree("get_path", i.rslt.obj),
			h = jQuery("#jstree_area").jstree("get_path", i.rslt.obj, !0),
			f = "";
			for (r = 0; r < u.length; r++) f += '<a node="' + h[r] + '" class="title_path_item">' + mb_cutstr_nohtml(u[r], 30, "...") + "</a>",
			r != u.length - 1 && (f += '<span class="title_path_item_speacer">&nbsp;->&nbsp;</span>');
			jQuery("#title_text_" + t.id).html(f),
			jQuery("#title_text_" + t.id + " .title_path_item").each(function() {
				jQuery(this).bind("click",
				function() {
					jQuery("#jstree_area").jstree("select_node", jQuery("#" + jQuery(this).attr("node")), !0)
				})
			})
		} else jQuery("#content_" + t.id).empty(),
		t.filemanageid = null,
		jQuery(t.titleCase).find(".operator").hide();
		s = jQuery("#jstree_area").jstree("get_path", i.rslt.obj, !1),
		i.rslt.obj.siblings().each(function() {
			jQuery("#jstree_area").jstree("close_node", jQuery(this))
		})
	}),
	this.contentCase.style.overflow = "visible",
	this.Focus(1)
},
_window.prototype.ChangeSequence = function(n, t) {
	if (this.Csequence <= 1 && n == "back" || this.Csequence >= this.Sequence.length && n == "next") return;
	n == "back" ? this.Csequence -= 1 : this.Csequence += 1,
	t == "folder" ? (_task.resetTask(this.taskid, this.Sequence[this.Csequence - 1]), this.icoid = this.Sequence[this.Csequence - 1], this.fid = _config.sourcedata.icos[this.Sequence[this.Csequence - 1]].oid, this.topfid = _ico.getTopFid(this.fid), this.taskid = this.Sequence[this.Csequence - 1], this.SetFolderTitle(_config.sourcedata.icos[this.Sequence[this.Csequence - 1]].name), this.SetFolderContent(this.Sequence[this.Csequence - 1])) : (this.Csequence <= 1 ? jQuery("#seq_back_" + this.id).removeClass("BACK2").addClass("BACK1") : jQuery("#seq_back_" + this.id).removeClass("BACK1").addClass("BACK2"), this.Csequence < this.Sequence.length ? jQuery("#seq_next_" + this.id).removeClass("NEXT1").addClass("NEXT2") : jQuery("#seq_next_" + this.id).removeClass("NEXT2").addClass("NEXT1"), jQuery("#jstree_area").jstree("select_node", jQuery("#" + this.Sequence[this.Csequence - 1]), !0))
},
_window.prototype.ltdrager_start = function(n) {
	this.XX = n.clientX,
	this.AttachEvent(n),
	document.getElementById("_blank").style.cursor = "e-resize",
	jQuery("#_blank").show();
	var t = this;
	this.ltdrager_left = jQuery("#jstree_area").width(),
	this.ltdrager_right = this.bodyWidth - this.ltdrager_left,
	eval("document.onmousemove=function(e){" + this.string + ".ltdraging(e?e:window.event);};"),
	eval("document.onmouseup=function(e){" + this.string + ".ltdraged(e?e:window.event);};")
},
_window.prototype.ltdraging = function(n) {
	var i = n.clientX - this.XX,
	t = this.ltdrager_left + i,
	r = this.ltdrager_right - i;
	t < 100 && (t = 100),
	t > this.bodyWidth - 200 && (t = this.bodyWidth - 200),
	jQuery("#jstree_area").css("width", t),
	jQuery("#filemanage-ltdrager").css("left", t),
	jQuery("#content_" + this.id).css("marginLeft", t)
},
_window.prototype.ltdraged = function(n) {
	this.DetachEvent(n),
	jQuery("#_blank").hide(),
	document.getElementById("_blank").style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_arrow.cur'),auto"
},
_window.OpenAppWin = function(n, t, i, r) {
	var f = _config.sourcedata.icos[t],
	u = new _window(i, t);
	return u.type = f.open == 1 ? "widget": "appwin",
	f.idtype == "pluginid" && (u.idtype = "pluginid", u.pluginid = f.typeid),
	f.type == "attach" && jQuery.inArray(f.ext, _config.openExt) < 0 && jQuery.inArray(_config.sourcedata.icos[t].ext.toUpperCase(), _config.txtexts) < 0 && (u.titleButton = "REFRESH|DETAIL|CLOSE|MAX|MIN"),
	f.type == "attach" && jQuery.inArray(_config.sourcedata.icos[t].ext.toUpperCase(), _config.txtexts) > -1 && _config.space.self > 0 && (u.titleButton = "HOME|EDIT|DETAIL|CLOSE|MAX|MIN", u.homerefresh = 1),
	u.desktop = n,
	u.icoid = t,
	u.taskid = t,
	u.CreatAppWin(t, f.url, f.name, r),
	u
},
_window.prototype.CreatAppWin = function(n, t, i, r) {
	var o, s, e, u, f;
	this.board = document.createElement("div"),
	this.board.className = this.type == "widget" ? "X_widget": this.className + " window",
	this.board.style.position = "absolute",
	this.board.id = this.id,
	this.board.style.zIndex = this.zIndex,
	this.board.style.visibility = "hidden",
	document.getElementById("_body_" + this.desktop).appendChild(this.board),
	_window.clientHeight || (this.board.style.left = "100%", this.board.style.top = "100%", _window.clientWidth = this.board.offsetLeft, _window.clientHeight = this.board.offsetTop),
	o = ["LEFT_TOP", "TOP", "RIGHT_TOP", "RIGHT", "RIGHT_BOTTOM", "BOTTOM", "LEFT_BOTTOM", "LEFT", "SHADOW_TOP", "SHADOW_RIGHT", "SHADOW_BOTTOM", "SHADOW_LEFT"],
	this.sides = [],
	o[o.length] = "TITLE",
	o[o.length] = "CONTENT",
	f = this,
	u = document.createElement("div");
	if (this.resize != "no") for (s = this.resize.split("|"), e = 0; e < s.length; e++) o[o.length] = s[e];
	for (e = 0; e < o.length; e++) {
		u = document.createElement("div"),
		u.className = o[e],
		u.style.position = "absolute",
		u.innerHTML = '<div class="' + o[e] + '_inner inner" style="position:absolute;"></div>',
		this.board.appendChild(u);
		switch (o[e]) {
		case "TITLE":
			this.titleCase = u,
			u.style.height = u.offsetHeight + "px",
			u.style.width = this.bodyWidth + "px",
			this.SetAppTitle(n, t, i),
			jQuery(this.titleCase).bind("dblclick",
			function() {
				return f.MAX ? f.Restore() : f.Max(),
				!1
			}),
			this.moveable && (jQuery(this.titleCase).bind("mousedown",
			function(n) {
				return f.Mousedown(n ? n: window.event),
				!1
			}), jQuery(this.titleCase).bind("mouseup",
			function(n) {
				return f.Mouseup(n ? n: window.event),
				!1
			}));
			break;
		case "CONTENT":
			this.contentCase = u,
			u.style.width = this.bodyWidth + "px",
			this.bodyHeight > 0 && (u.style.height = this.bodyHeight + "px"),
			this.SetAppWinContent(n, t, r),
			u.style.left = this.sides[7].width + "px",
			u.style.top = this.sides[1].height + "px",
			this.width = this.bodyWidth + this.sides[3].width + this.sides[7].width,
			this.height = this.bodyHeight + this.sides[1].height + this.sides[5].height,
			this.board.style.height = this.height + "px",
			this.board.style.width = this.width + "px",
			this.blank = document.createElement("div"),
			this.blank.id = "_blank_" + this.id,
			this.blank.className = "window_blank",
			this.blank.style.background = "url("+_THEME_+"/desktop/images/b.gif)",
			jQuery(this.blank).bind("mousedown",
			function() {
				return f.Focus(),
				!1
			}),
			this.contentCase.appendChild(this.blank);
			break;
		case "RESIZE":
			u.style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_nwse.cur'),auto",
			jQuery(u).bind("mousedown",
			function(n) {
				f.resize = "yes",
				f.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-X":
			this.resizexCase = u,
			u.style.cursor = "e-resize",
			jQuery(u).bind("mousedown",
			function(n) {
				f.resize = "resize-x",
				f.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-Y":
			this.resizeyCase = u,
			u.style.cursor = "s-resize",
			jQuery(u).bind("mousedown",
			function(n) {
				f.resize = "resize-y",
				f.PreResize(n ? n: window.event)
			});
			break;
		default:
			this.sides[e] = u,
			this.sides[e].width = u.offsetWidth,
			this.sides[e].height = u.offsetHeight,
			this.moveable && (u.style.cursor = "move", jQuery(u).bind("mousedown",
			function(n) {
				f.Mousedown(n ? n: window.event)
			}), jQuery(u).bind("mouseup",
			function(n) {
				f.Mouseup(n ? n: window.event)
			}))
		}
	}
	this.sides[1].dx = this.sides[0].width + this.sides[2].width,
	this.width > this.sides[1].dx && (this.sides[1].style.width = this.width - this.sides[1].dx + "px"),
	this.sides[3].dy = this.sides[2].height + this.sides[4].height,
	this.height > this.sides[3].dy && (this.sides[3].style.height = this.height - this.sides[3].dy + "px"),
	this.sides[5].dx = this.sides[4].width + this.sides[6].width,
	this.width > this.sides[5].dx && (this.sides[5].style.width = this.width - this.sides[5].dx + "px"),
	this.sides[7].dy = this.sides[6].height + this.sides[0].height,
	this.height > this.sides[7].dy && (this.sides[7].style.height = this.height - this.sides[7].dy + "px"),
	this.sides[0].style.left = "0px",
	this.sides[0].style.top = "0px",
	this.sides[1].style.left = this.sides[0].width + "px",
	this.sides[1].style.top = "0px",
	this.sides[2].style.right = "0px",
	this.sides[2].style.top = "0px",
	this.sides[3].style.right = "0px",
	this.sides[3].style.top = this.sides[2].height + "px",
	this.sides[4].style.right = "0px",
	this.sides[4].style.bottom = "0px",
	this.sides[5].style.left = this.sides[6].width + "px",
	this.sides[5].style.bottom = "0px",
	this.sides[6].style.left = "0px",
	this.sides[6].style.bottom = "0px",
	this.sides[7].style.left = "0px",
	this.sides[7].style.top = this.sides[0].height + "px",
	this.minWidth = _window.getMaxNumber(this.sides[0].width + this.sides[2].width, this.sides[3].width + this.sides[7].width, this.sides[4].width + this.sides[6].width) + this.minTitleWidth,
	this.minHeight = _window.getMaxNumber(this.sides[0].height + this.sides[6].height, this.sides[1].height + this.sides[5].height, this.sides[2].height + this.sides[4].height) + 2,
	this.left = this.left ? parseInt(this.left) : this.right ? _window.clientWidth - this.width - parseInt(this.right) : parseInt((_window.clientWidth - this.width) / 2),
	this.top = this.top ? parseInt(this.top) : this.bottom ? _window.clientHeight - this.height - parseInt(this.bottom) : parseInt((_window.clientHeight - this.height) / 2),
	this.left < 0 && (this.left = 0),
	_navbar.navbars[this.desktop].topbarshow == 0 && ieVersion > 0 && ieVersion < 8 ? this.top < jQuery("#navbar").height() && (this.top = jQuery("#navbar").height()) : this.top < 0 && (this.top = 0),
	this.left += document.documentElement.scrollLeft,
	this.top += document.documentElement.scrollTop,
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px",
	this.board.style.visibility = "visible",
	this.type == "widget" && (f = this, jQuery(f.titleCase).hide(), (_config.space.self && f.desktop.indexOf("sys_") === -1 || _config.space.self > 1) && jQuery(this.board).hover(function() {
		jQuery(f.titleCase).show()
	},
	function() {
		jQuery(f.titleCase).hide()
	})),
	this.status = 1
},
_window.prototype.SetAppTitle = function(n, t, i) {
	var u = this,
	c = 0,
	o = document.createElement("div"),
	s,
	h,
	f,
	r,
	e;
	for (o.className = "titleBar", s = document.createElement("div"), s.className = "titleButtonBar", h = this.titleButton.split("|"), f = 0; f < _config.titleButtons.length; f++) r = document.createElement("a"),
	r.className = _config.titleButtons[f],
	r.setAttribute("bname", _config.titleButtons[f]),
	r.title = _lang.titleButton[_config.titleButtons[f]],
	r.style.display = in_array(_config.titleButtons[f], h) ? "block": "none",
	jQuery(r).bind("click",
	function() {
		switch (this.className) {
		case "CLOSE":
			u.Close();
			break;
		case "MAX":
			u.Max();
			break;
		case "RESTORE":
			u.Restore();
			break;
		case "MIN":
			u.Min();
			break;
		case "REFRESH":
			u.SetAppWinContent(n, t, "REFRESH");
			break;
		case "EDIT":
			u.SetAppWinContent(n, t, "EDIT");
			break;
		case "HOME":
			u.SetAppWinContent(n, t, "HOME");
			break;
		case "DETAIL":
			u.SetAppWinContent(n, t, "DETAIL")
		}
		return ! 1
	}),
	s.appendChild(r),
	c += r.offsetWidth + parseInt(jQuery(r).css("margin-left"));
	o.appendChild(s),
	this.type != "widget" && (e = document.createElement("div"), e.className = "titleText", e.innerHTML = i || "dzz.cc", o.appendChild(e), this.titleText = e),
	this.minTitleWidth = c + 50,
	this.titleCase.appendChild(o)
},
_window.prototype.SetAppWinContent = function(n, t, i) {
	var f, u, r, e, c, l, s, o, h;
	i || (i = "HOME"),
	_config.sourcedata.icos[n].type == "attach" && jQuery.inArray(_config.sourcedata.icos[n].ext, _config.openExt) < 0 && jQuery.inArray(_config.sourcedata.icos[n].ext.toUpperCase(), _config.txtexts) < 0 && (i = "DETAIL");
	if (i == "REFRESH") {
		for (f in this.tabs) jQuery(this.tabs[f].board) && (jQuery(this.tabs[f].board).remove(), delete this.tabs[f].board),
		this.tabs[f].frame && (window.frames["ifm_" + f + "_" + this.id] && (window.frames["ifm_" + f + "_" + this.id].location = "about:blank"), jQuery(this.tabs[f].frame).remove(), delete this.tabs[f].frame),
		delete this.tabs[f];
		i = this.tab ? this.tab: "HOME"
	}
	this.tab = i,
	jQuery(this.contentCase).find(".tabdiv").css("z-index", -99999).find("iframe").css({
		width: "1px",
		height: "1px"
	}),
	jQuery("#tablediv_" + i + this.id).css("z-index", 10).find("iframe").css({
		width: "100%",
		height: "100%"
	}),
	jQuery(this.titleCase).find(".titleButtonBar A").each(function() {
		jQuery(this).removeClass(jQuery(this).attr("bname") + "1")
	}),
	jQuery(this.titleCase).find("." + i).addClass(i + "1"),
	this.homefresh > 0 && i == "HOME" && (this.tabs.HOME && this.tabs.HOME.board && (jQuery(this.tabs.HOME.board).remove(), delete this.tabs.HOME.board), this.tabs.HOME && this.tabs.HOME.frame && (jQuery(this.tabs.HOME.frame).remove(), delete this.tabs.HOME.frame), delete this.tabs.HOME, this.homefresh = 0);
	if (!this.tabs[i]) {
		this.tabs[i] = {},
		u = document.createElement("div"),
		u.className = "tabdiv",
		u.id = "tablediv_" + i + this.id,
		u.style.zIndex = 10,
		this.tabs[i].board = u,
		r = t;
		switch (i) {
		case "HOME":
			r = _config.sourcedata.icos[n].type == "attach" && jQuery.inArray(_config.sourcedata.icos[n].ext.toUpperCase(), _config.txtexts) > -1 ? DZZSCRIPT + "?mod=ueditor&op=dtxt&icoid=" + _config.sourcedata.icos[n].icoid: t;
			break;
		case "EDIT":
			_config.sourcedata.icos[n].type == "attach" && jQuery.inArray(_config.sourcedata.icos[n].ext.toUpperCase(), _config.txtexts) > -1 && (r = DZZSCRIPT + "?mod=ueditor&op=edit&type=1&uid=" + _config.space.uid + "&icoid=" + _config.sourcedata.icos[n].icoid);
			break;
		case "DETAIL":
			r = _config.systemurl + "&op=detail&id=" + this.icoid
		}
		e = "",
		jQuery.inArray(_config.sourcedata.icos[n].ext, _config.openExt) > -1 && i != "DETAIL" && (e = _config.parsemedia(r, _config.sourcedata.icos[n].ext)),
		e ? (u.innerHTML = e, u.style.overflow = "hidden", this.bodyHeight < 0 && (this.bodyHeight = 300), jQuery(this.loadding).fadeOut()) : this.tabs[i].frame ? this.homerefresh > 0 ? this.tabs[i].frame.location = r + "&t=" + +new Date: this.tabs[i].frame.src != r && (this.tabs[i].frame.src = r) : (this.loadding || (this.loadding = jQuery('<div id="window_content_loadding' + this.id + '" class="window_content_loadding"><div class="window_content_loadding_back"><table width="100%" height="100%"><tr><td align="center" valign="middle"><div class="window_content_loadding_back_img"></div></td></tr></table></div><div class="window_content_loadding_moving"></div></div>').appendTo(this.contentCase).get(0)), c = {
			uid: _config.space.uid,
			ukey: _config.ukey,
			username: _config.space.username,
			password: _config.space.password,
			self: _config.space.self,
			winid: this.id,
			tab: i,
			time: +new Date
		},
		l = encodeURIComponent(jQuery.toJSON(c)), s = _ico.icos[n] && _ico.icos[n].haveflash > 0 ? "appIframe app_swf": "appIframe", this.loaddingTimer = setTimeout(function() {
			jQuery(o.loadding).fadeOut()
		},
		5e3), o = this, h = jQuery('<iframe frameborder="0" name="' + l + '" id="ifm_' + i + "_" + this.id + '" marginheight="0" marginwidth="0" allowtransparency="true" src="' + r + '" class="' + s + '"  style="overflow-x: hidden;" onload="jQuery(\'#window_content_loadding' + this.id + "').fadeOut();clearTimeout(" + o.loaddingTimer + ');"></iframe>').appendTo(u), this.tabs[i].frame = h.get(0), this.bodyHeight < 0 && (this.bodyHeight = 300), u.style.overflow = "hidden"),
		this.contentCase.appendChild(u)
	}
	this.Focus()
},
_window.OpenBrowser = function(n, t, i) {
	var r = new _window(i, "sys_browser");
	return r.type = "sys_browser",
	r.taskid = "sys_browser",
	r.CreateBrowser("sys_browser", n, t),
	r
},
_window.prototype.SetBrowserTitle = function(n) {
	var i = this,
	c = 0,
	e = document.createElement("div"),
	o,
	h,
	r,
	t,
	f,
	u,
	n,
	s;
	for (e.className = "titleBar", this.titleCase.appendChild(e), o = document.createElement("div"), o.className = "titleButtonBar", e.appendChild(o), h = this.titleButton.split("|"), r = 0; r < _config.titleButtons.length; r++) t = document.createElement("a"),
	t.className = _config.titleButtons[r],
	t.title = _lang.titleButton[_config.titleButtons[r]],
	t.style.display = in_array(_config.titleButtons[r], h) ? "block": "none",
	jQuery(t).bind("click",
	function() {
		switch (this.className) {
		case "CLOSE":
			i.Close();
			break;
		case "MAX":
			i.Max();
			break;
		case "RESTORE":
			i.Restore();
			break;
		case "MIN":
			i.Min()
		}
		return ! 1
	}),
	o.appendChild(t),
	c += t.offsetWidth + parseInt(jQuery(t).css("margin-left"));
	this.minTitleWidth = c + 50,
	f = document.createElement("div"),
	f.className = "titleText",
	u = "",
	u += '<div class="title_icon"><img src="'+_THEME_+'/desktop/images/default/liulanqi.png" onload="fixpng(this)"></div>',
	u += '<div class="title_text">' + _lang.browser + "</div>",
	f.innerHTML = u,
	e.appendChild(f),
	n = "",
	n += "<div id='seq_back_" + this.id + "' title='" + _lang.window.back + "' class='BACK1' onclick=\"ChangeSequence('back')\"></div>",
	n += "<div id='seq_next_" + this.id + "' title='" + _lang.window.next + "' class='NEXT1' onclick=\"ChangeSequence('next')\"></div>",
	this.title = '<table width="100%" cellpadding="0" cellspacing="0" align="left" style="table-layout:fix;text-align:left"><tr><td width="25">&nbsp;</td><td width="60" valign="top">' + n + '</td><td class="input_add" title="' + _lang.favtodesktop + '" onclick="return false">&nbsp;</td><td class="input_zuo" >&nbsp;</td><td id="input_container" class="input_bg" align="left"  width="40%" valign="top"><input  id="address" name="address" class="input_addr" type="text" value="" onfocus="this.select();" onkeydown="if(event.keyCode==13){viewTab(this.value);}"></td><td class="input_you" onclick="viewTab(document.getElementById(\'address\').value)">&nbsp;</td><td id="size_drag" width="5"  >&nbsp;</td><td id="tabs_cover">&nbsp;</td><td width="55">&nbsp;</td><td width="20">&nbsp;</td></tr></table><div id="add_tabs" onclick="CreateTabs(\'about:blank\',\'' + _lang.blank + '\');" ></div><div id="tablist" onclick="Showtablist()" ></div>',
	this.headdiv = document.createElement("div"),
	this.headdiv.className = "titleButton",
	this.headdiv.innerHTML = this.title,
	this.titleCase.appendChild(this.headdiv),
	s = document.createElement("div"),
	s.id = "tabs_container",
	s.style.cssText = "position:absolute;overflow:hidden;width:" + document.getElementById("tabs_cover").offsetWidth + "px;height:" + document.getElementById("tabs_cover").offsetHeight + "px;top:0px;left:" + document.getElementById("tabs_cover").offsetLeft + "px;z-index:1;",
	this.headdiv.appendChild(s),
	i = this;
	jQuery("#size_drag").on("mousedown",
	function(n) {
		return i.Drag_start(n ? n: window.event),
		!1
	});
	jQuery("#size_drag").on("mouseup",
	function(n) {
		i.Draged(n ? n: window.event)
	})
},
_window.prototype.SetBrowserContent = function(n, t) {
	CreateTabs(n, t),
	this.Focus()
},
_window.prototype.CreateBrowser = function(n, t, i) {
	var e, u, o, f, r;
	this.board = document.createElement("div"),
	this.board.className = "X_Browser",
	this.board.style.position = "absolute",
	this.board.style.zIndex = this.zIndex,
	this.desktop = _config.currentDesktop,
	parseInt(_config.currentDesktop) < 0 ? document.getElementById("_body_fix_" + Math.abs(parseInt(_config.currentDesktop))).appendChild(this.board) : document.getElementById("_body_" + _config.currentDesktop).appendChild(this.board),
	u = this,
	_window.clientHeight || (this.board.style.left = "100%", this.board.style.top = "100%", _window.clientWidth = this.board.offsetLeft, _window.clientHeight = this.board.offsetTop),
	e = ["LEFT_TOP", "TOP", "RIGHT_TOP", "RIGHT", "RIGHT_BOTTOM", "BOTTOM", "LEFT_BOTTOM", "LEFT", "SHADOW_TOP", "SHADOW_RIGHT", "SHADOW_BOTTOM", "SHADOW_LEFT"],
	u = this,
	this.sides = [],
	this.sides.topbar = document.createElement("div"),
	this.sides.topbar.width = 0,
	this.sides.topbar.height = 0,
	e[e.length] = "TITLE",
	e[e.length] = "CONTENT";
	if (this.resize != "no") for (o = this.resize.split("|"), f = 0; f < o.length; f++) e[e.length] = o[f];
	for (f = 0; f < e.length; f++) {
		r = document.createElement("div"),
		r.className = e[f],
		r.style.position = "absolute",
		this.board.appendChild(r);
		switch (e[f]) {
		case "TITLE":
			this.width = this.bodyWidth + this.sides[3].width + this.sides[7].width,
			this.height = this.bodyHeight + this.sides[1].height + this.sides[5].height,
			this.titleCase = r,
			r.style.height = this.sides[1].height + "px",
			r.style.width = this.width + "px",
			this.SetBrowserTitle(n),
			jQuery(this.titleCase).bind("dblclick",
			function() {
				return u.MAX ? u.Restore() : u.Max(),
				!1
			}),
			this.moveable && (jQuery(this.titleCase).bind("mousedown",
			function(n) {
				u.Mousedown(n ? n: window.event)
			}), jQuery(this.titleCase).bind("mouseup",
			function(n) {
				u.Mouseup(n ? n: window.event)
			}));
			break;
		case "CONTENT":
			this.contentCase = r,
			r.style.width = this.bodyWidth + "px",
			this.bodyHeight > 0 && (r.style.height = this.bodyHeight + "px"),
			r.style.left = this.sides[7].width + "px",
			r.style.top = this.sides[1].height + "px",
			this.minWidth = 500,
			this.minHeight = 200,
			this.board.style.height = this.height + "px",
			this.board.style.width = this.width + "px",
			this.titleCase.style.width = "100%",
			this.SetBrowserContent(t, i),
			this.contentCase.style.overflow = "hidden",
			this.blank = document.createElement("div"),
			this.blank.style.position = "absolute",
			this.blank.style.zIndex = -1,
			this.blank.id = "_shadow_" + this.id,
			this.blank.style.left = "0px",
			this.blank.style.top = "0px",
			this.blank.style.height = "100%",
			this.blank.style.width = "100%",
			this.blank.style.overflow = "hidden",
			this.blank.style.bakground = "url("+_THEME_+"/desktop/images/b.gif)",
			jQuery(this.blank).bind("mousedown",
			function() {
				u.Focus()
			}),
			this.contentCase.appendChild(this.blank);
			break;
		case "RESIZE":
			r.style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_nwse.cur'),auto",
			jQuery(r).bind("mousedown",
			function(n) {
				u.resize = "yes",
				u.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-X":
			r.style.height = this.height + "px",
			this.resizexCase = r,
			r.style.cursor = "e-resize",
			jQuery(r).bind("mousedown",
			function(n) {
				u.resize = "resize-x",
				u.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-Y":
			r.style.width = this.width + "px",
			this.resizeyCase = r,
			r.style.cursor = "s-resize",
			jQuery(r).bind("mousedown",
			function(n) {
				u.resize = "resize-y",
				u.PreResize(n ? n: window.event)
			});
			break;
		default:
			this.sides[f] = r,
			this.sides[f].width = r.offsetWidth,
			this.sides[f].height = r.offsetHeight,
			this.moveable && (r.style.cursor = "move", jQuery(r).bind("mousedown",
			function(n) {
				u.Mousedown(n ? n: window.event)
			}), jQuery(r).bind("mouseup",
			function(n) {
				u.Mouseup(n ? n: window.event)
			}))
		}
	}
	this.sides[1].dx = this.sides[0].width + this.sides[2].width,
	this.width > this.sides[1].dx && (this.sides[1].style.width = this.width - this.sides[1].dx + "px"),
	this.sides[3].dy = this.sides[2].height + this.sides[4].height,
	this.height > this.sides[3].dy && (this.sides[3].style.height = this.height - this.sides[3].dy + "px"),
	this.sides[5].dx = this.sides[4].width + this.sides[6].width,
	this.width > this.sides[5].dx && (this.sides[5].style.width = this.width - this.sides[5].dx + "px"),
	this.sides[7].dy = this.sides[6].height + this.sides[0].height,
	this.height > this.sides[7].dy && (this.sides[7].style.height = this.height - this.sides[7].dy + "px"),
	this.sides[0].style.left = "0px",
	this.sides[0].style.top = "0px",
	this.sides[1].style.left = this.sides[0].width + "px",
	this.sides[1].style.top = "0px",
	this.sides[2].style.right = "0px",
	this.sides[2].style.top = "0px",
	this.sides[3].style.right = "0px",
	this.sides[3].style.top = this.sides[2].height + "px",
	this.sides[4].style.right = "0px",
	this.sides[4].style.bottom = "0px",
	this.sides[5].style.left = this.sides[6].width + "px",
	this.sides[5].style.bottom = "0px",
	this.sides[6].style.left = "0px",
	this.sides[6].style.bottom = "0px",
	this.sides[7].style.left = "0px",
	this.sides[7].style.top = this.sides[0].height + "px",
	this.left = this.left ? parseInt(this.left) : this.right ? _window.clientWidth - this.width - parseInt(this.right) : parseInt((_window.clientWidth - this.width) / 2),
	this.top = this.top ? parseInt(this.top) : this.bottom ? _window.clientHeight - this.height - parseInt(this.bottom) : parseInt((_window.clientHeight - this.height) / 2),
	this.left < 0 && (this.left = 0),
	_navbar.navbars[this.desktop].topbarshow == 0 && ieVersion > 0 && ieVersion < 8 ? this.top < jQuery("#navbar").height() && (this.top = jQuery("#navbar").height()) : this.top < 0 && (this.top = 0),
	this.left += document.documentElement.scrollLeft,
	this.top += document.documentElement.scrollTop,
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px",
	this.board.style.visibility = "visible",
	this.status = 1,
	this.board.onmousedown = function(n) {
		u.Focus(n ? n: window.event)
	}
},
_window.prototype.Drag_start = function(n) {
	this.XX = n.clientX,
	document.getElementById("_blank").style.cursor = "e-resize",
	this.inputwidth = document.getElementById("input_container").offsetWidth,
	this.tabs_containerwidth = document.getElementById("tabs_container").offsetWidth,
	this.tabs_containerleft = document.getElementById("tabs_container").offsetLeft;
	var t = this;
	this.AttachEvent(n),
	eval("document.onmousemove=function(e){" + this.string + ".Drag(e?e:window.event)};"),
	eval("document.onmouseup=function(e){" + this.string + ".Draged(e?e:window.event);};")
},
_window.tabwidth = 130,
_window.resetTabs = function() {
	var r = 0,
	f, n;
	for (f in _tab.tabs) r++;
	_window.tabwidth = Math.floor(document.getElementById("tabs_cover").offsetWidth / r),
	_window.tabwidth < 60 && (_window.tabwidth = 60),
	_window.tabwidth > 130 && (_window.tabwidth = 130);
	var u = r * _window.tabwidth - document.getElementById("tabs_cover").offsetWidth,
	t = document.getElementById("tabs_container").getElementsByTagName("div"),
	i = 0;
	for (n = 0; n < t.length; n++)(t[n].className == "tabs_td_active" || t[n].className == "tabs_td") && (t[n].style.left = _window.tabwidth < 130 ? i * _window.tabwidth - u + "px": i * _window.tabwidth + "px", t[n].style.width = _window.tabwidth - 5 + "px", i++);
	_window.Setrun()
},
_window.Setrun = function() {
	for (var r = [], u = document.getElementById("tabs_container").getElementsByTagName("div"), t, i, n = 0; n < u.length; n++)(u[n].className == "tabs_td" || u[n].className == "tabs_td_active") && r.push(u[n]);
	_window.tabwidth * r.length > document.getElementById("tabs_cover").offsetWidth ? (r[0].offsetLeft < 0 ? (document.getElementById("leftrun") || (t = document.createElement("div"), t.id = "leftrun", t.className = "leftrun_s", _window.windows._W_sys_browser.headdiv.appendChild(t), t.onclick = function() {
		for (var t = [], i = document.getElementById("tabs_container").getElementsByTagName("div"), f, r, u, n = 0; n < i.length; n++)(i[n].className == "tabs_td" || i[n].className == "tabs_td_active") && t.push(i[n]);
		for (f = 0, r = 0, n = 0; n < t.length; n++) {
			u = t[n];
			if (n == 0) if (t[0].offsetLeft < 0) r = t[0].offsetLeft + _window.tabwidth < 0 ? _window.tabwidth: Math.abs(t[0].offsetLeft);
			else break;
			u.style.left = u.offsetLeft + r + "px"
		}
		_window.Setrun()
	}), document.getElementById("leftrun").style.left = document.getElementById("tabs_cover").offsetLeft + "px", document.getElementById("leftrun").style.display = "block") : document.getElementById("leftrun") && (document.getElementById("leftrun").style.display = "none"), r[r.length - 1].offsetLeft + _window.tabwidth > document.getElementById("tabs_container").offsetWidth ? (document.getElementById("rightrun") || (i = document.createElement("div"), i.id = "rightrun", i.className = "rightrun_s", _window.windows._W_sys_browser.headdiv.appendChild(i), i.onclick = function() {
		for (var r = [], i = document.getElementById("tabs_container").getElementsByTagName("div"), f, u, t, n = 0; n < i.length; n++)(i[n].className == "tabs_td" || i[n].className == "tabs_td_active") && r.push(i[n]);
		for (f = 0, u = 0, n = r.length - 1; n >= 0; n--) {
			t = r[n];
			if (f == 0) if (t.offsetLeft + _window.tabwidth > document.getElementById("tabs_container").offsetWidth) u = t.offsetLeft + _window.tabwidth - document.getElementById("tabs_container").offsetWidth > _window.tabwidth ? _window.tabwidth: t.offsetLeft + _window.tabwidth - document.getElementById("tabs_cover").offsetWidth;
			else break;
			t.style.left = t.offsetLeft - u + "px",
			f++
		}
		_window.Setrun()
	}), document.getElementById("rightrun").style.display = "") : document.getElementById("rightrun") && (document.getElementById("rightrun").style.display = "none")) : (document.getElementById("rightrun") && (document.getElementById("rightrun").style.display = "none"), document.getElementById("leftrun") && (document.getElementById("leftrun").style.display = "none"))
},
_window.prototype.Drag = function(n) {
	var t = n.clientX - this.XX;
	this.inputwidth + t >= 130 && this.inputwidth + t < this.width - 400 && (document.getElementById("input_container").style.width = (this.inputwidth + t) / this.width * 100 + "%", document.getElementById("tabs_container").style.left = document.getElementById("tabs_cover").offsetLeft + "px", document.getElementById("tabs_container").style.width = document.getElementById("tabs_cover").offsetWidth + "px", document.getElementById("leftrun") && (document.getElementById("leftrun").style.left = document.getElementById("tabs_cover").offsetLeft + "px"), _window.resetTabs())
},
_window.prototype.Draged = function(n) {
	this.DetachEvent(n),
	document.getElementById("_blank").style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_arrow.cur'),auto"
},
_window.Open = function(n, t, i, r, u) {
	var e, f;
	return n == "url" && (e = n, n = encodeURIComponent(t).replace(/\./g, "_").replace(/%/g, "_")),
	f = new _window(r, n),
	n && !e && (f.type = "syswin", f.taskid = n),
	f.Create(t, i, n, u),
	f
},
_window.prototype.SetTitle = function(n) {
	var f = this,
	s = 0,
	e = document.createElement("div"),
	u,
	o,
	i,
	t,
	r;
	for (e.className = "titleBar", this.titleCase.appendChild(e), u = document.createElement("div"), u.className = "titleButtonBar", e.appendChild(u), o = this.titleButton.split("|"), i = 0; i < _config.titleButtons.length; i++) t = document.createElement("a"),
	t.className = _config.titleButtons[i],
	t.setAttribute("bname", _config.titleButtons[i]),
	t.title = _lang.titleButton[_config.titleButtons[i]],
	t.style.display = in_array(_config.titleButtons[i], o) ? "block": "none",
	jQuery(t).bind("click",
	function() {
		switch (this.className) {
		case "CLOSE":
			f.Close();
			break;
		case "MAX":
			f.Max();
			break;
		case "RESTORE":
			f.Restore();
			break;
		case "MIN":
			f.Min()
		}
		return ! 1
	}),
	u.appendChild(t),
	s += t.offsetWidth + parseInt(jQuery(t).css("margin-left"));
	r = document.createElement("div"),
	r.className = "titleText",
	r.innerHTML = n || "dzz.cc",
	e.appendChild(r),
	this.minTitleWidth = s + 50
},
_window.prototype.Create = function(n, t, i, r) {
	var e, f, l, o, u, c, s, h;
	this.isModal && (this.modal = document.createElement("div"), this.modal.className = "MODAL", this.modal.style.position = "absolute", this.modal.style.zIndex = this.zIndex, this.modal.style.width = document.documentElement.scrollWidth + "px", this.modal.style.height = document.documentElement.scrollHeight + "px", this.modal.style.left = "0px", this.modal.style.top = "0px", document.getElementById("_body_" + _config.currentDesktop).appendChild(this.modal), this.zIndex = ++_window.zIndex),
	this.board = document.createElement("div"),
	this.board.className = this.className,
	this.board.style.position = "absolute",
	this.board.style.zIndex = this.zIndex,
	this.board.style.visibility = "hidden",
	this.desktop = _config.currentDesktop,
	document.getElementById("_body_" + _config.currentDesktop).appendChild(this.board),
	_window.clientHeight || (this.board.style.left = "100%", this.board.style.top = "100%", _window.clientWidth = this.board.offsetLeft, _window.clientHeight = this.board.offsetTop),
	e = ["LEFT_TOP", "TOP", "RIGHT_TOP", "RIGHT", "RIGHT_BOTTOM", "BOTTOM", "LEFT_BOTTOM", "LEFT", "SHADOW_TOP", "SHADOW_RIGHT", "SHADOW_BOTTOM", "SHADOW_LEFT"],
	this.sides = [],
	this.button && (e[4] = "RIGHT_BOTTOM_BY_BUTTON", e[5] = "BOTTOM_BY_BUTTON", e[6] = "LEFT_BOTTOM_BY_BUTTON", e[e.length] = "BUTTON"),
	e[e.length] = "TITLE",
	e[e.length] = "CONTENT",
	f = this;
	if (this.resize != "no") for (l = this.resize.split("|"), o = 0; o < l.length; o++) e[e.length] = l[o];
	for (o = 0; o < e.length; o++) {
		u = document.createElement("div"),
		u.className = e[o],
		u.style.position = "absolute",
		u.innerHTML = '<div class="' + e[o] + '_inner inner" style="position:absolute;"></div>',
		this.board.appendChild(u);
		switch (e[o]) {
		case "TITLE":
			this.titleCase = u,
			u.style.height = u.offsetHeight + "px",
			u.style.width = this.bodyWidth + "px",
			this.SetTitle(t),
			jQuery(this.titleCase).bind("dblclick",
			function() {
				return f.MAX ? f.Restore() : f.Max(),
				!1
			}),
			this.moveable && (jQuery(this.titleCase).bind("mousedown",
			function(n) {
				f.Mousedown(n ? n: window.event)
			}), jQuery(this.titleCase).bind("mouseup",
			function(n) {
				f.Mouseup(n ? n: window.event)
			}));
			break;
		case "CONTENT":
			this.contentCase = u,
			u.style.width = this.bodyWidth + "px",
			this.bodyHeight > 0 && (u.style.height = this.bodyHeight + "px"),
			this.SetContent(n, i, r),
			u.style.left = this.sides[7].width + "px",
			u.style.top = this.sides[1].height + "px",
			this.titleCase.style.width = this.bodyWidth + "px",
			this.width = this.bodyWidth + this.sides[3].width + this.sides[7].width,
			this.height = this.bodyHeight + this.sides[1].height + this.sides[5].height,
			this.minWidth = _window.getMaxNumber(this.sides[0].width + this.sides[2].width, this.sides[3].width + this.sides[7].width, this.sides[4].width + this.sides[6].width) + this.minTitleWidth,
			this.minHeight = _window.getMaxNumber(this.sides[0].height + this.sides[6].height, this.sides[1].height + this.sides[5].height, this.sides[2].height + this.sides[4].height) + 2,
			this.board.style.height = this.height + "px",
			this.board.style.width = this.width + "px",
			this.blank = document.createElement("div"),
			this.blank.id = "_blank_" + this.id,
			this.blank.className = "window_blank",
			jQuery(this.blank).bind("mousedown",
			function() {
				f.Focus()
			}),
			this.contentCase.appendChild(this.blank);
			break;
		case "BUTTON":
			for (this.buttonCase = u, u.style.width = this.bodyWidth + "px", u.style.left = this.sides[7].width + "px", u.style.bottom = "0px", this.buttonCase.dx = u.offsetWidth - this.width, this.minWidth < this.buttonCase.dx && (this.minWidth = this.buttonCase.dx), c = this.button.split("|"), s = 0; s < c.length; s++) h = document.createElement("button"),
			h.className = c[s],
			h.title = c[s],
			u.appendChild(h),
			jQuery(h).bind("click",
			function() {
				eval(f.string + ".On" + this.title + "()")
			}),
			this.buttons[c[s]] = h;
			break;
		case "RESIZE":
			u.style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_nwse.cur'),auto",
			jQuery(u).bind("mousedown",
			function(n) {
				f.resize = "yes",
				f.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-X":
			this.resizexCase = u,
			u.style.cursor = "e-resize",
			jQuery(u).bind("mousedown",
			function(n) {
				f.resize = "resize-x",
				f.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-Y":
			this.resizeyCase = u,
			u.style.cursor = "s-resize",
			jQuery(u).bind("mousedown",
			function(n) {
				f.resize = "resize-y",
				f.PreResize(n ? n: window.event)
			});
			break;
		default:
			this.sides[o] = u,
			this.sides[o].width = u.offsetWidth,
			this.sides[o].height = u.offsetHeight,
			this.moveable && (u.style.cursor = "move", jQuery(u).bind("mousedown",
			function(n) {
				f.Mousedown(n ? n: window.event)
			}), jQuery(u).bind("mouseup",
			function(n) {
				f.Mouseup(n ? n: window.event)
			}))
		}
	}
	this.sides[1].dx = this.sides[0].width + this.sides[2].width,
	this.width > this.sides[1].dx && (this.sides[1].style.width = this.width - this.sides[1].dx + "px"),
	this.sides[3].dy = this.sides[2].height + this.sides[4].height,
	this.height > this.sides[3].dy && (this.sides[3].style.height = this.height - this.sides[3].dy + "px"),
	this.sides[5].dx = this.sides[4].width + this.sides[6].width,
	this.width > this.sides[5].dx && (this.sides[5].style.width = this.width - this.sides[5].dx + "px"),
	this.sides[7].dy = this.sides[6].height + this.sides[0].height,
	this.height > this.sides[7].dy && (this.sides[7].style.height = this.height - this.sides[7].dy + "px"),
	this.sides[0].style.left = "0px",
	this.sides[0].style.top = "0px",
	this.sides[1].style.left = this.sides[0].width + "px",
	this.sides[1].style.top = "0px",
	this.sides[2].style.right = "0px",
	this.sides[2].style.top = "0px",
	this.sides[3].style.right = "0px",
	this.sides[3].style.top = this.sides[2].height + "px",
	this.sides[4].style.right = "0px",
	this.sides[4].style.bottom = "0px",
	this.sides[5].style.left = this.sides[6].width + "px",
	this.sides[5].style.bottom = "0px",
	this.sides[6].style.left = "0px",
	this.sides[6].style.bottom = "0px",
	this.sides[7].style.left = "0px",
	this.sides[7].style.top = this.sides[0].height + "px",
	this.left = this.left ? parseInt(this.left) : this.right ? _window.clientWidth - this.width - parseInt(this.right) : parseInt((_window.clientWidth - this.width) / 2),
	this.top = this.top ? parseInt(this.top) : this.bottom ? _window.clientHeight - this.height - parseInt(this.bottom) : parseInt((_window.clientHeight - this.height) / 2),
	this.left < 0 && (this.left = 0),
	_navbar.navbars[this.desktop].topbarshow == 0 && ieVersion > 0 && ieVersion < 8 ? this.top < jQuery("#navbar").height() && (this.top = jQuery("#navbar").height()) : this.top < 0 && (this.top = 0),
	this.left += document.documentElement.scrollLeft,
	this.top += document.documentElement.scrollTop,
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px",
	this.board.style.visibility = "visible",
	this.status = 1
},
_window.prototype.SetContent = function(n, t, i) {
	var u, o, r, s, h, e, f;
	if (in_array(t, ["sys_addApp", "sys_theme", "sys_market", "sys_widget", "sys_filemamage"])) {
		u = "";
		switch (t) {
		case "sys_addApp":
			u = _config.sysaddappurl + "&url=" + escape(document.getElementById("address").value);
			break;
		case "sys_theme":
			u = _config.systhameurl;
			break;
		case "sys_market":
			u = encodeURI(_config.marketurl + "&container=" + i);
			break;
		case "sys_widget":
			u = encodeURI(_config.widgeturl)
		}
		n = "[url]" + u
	}
	o = n.slice(0, 5),
	r = n.slice(5),
	o == "[url]" ? r.substr(r.lastIndexOf(".")).toLowerCase() == ".swf" ? (this.contentCase.innerHTML = AC_FL_RunContent("width", "100%", "height", "100%", "allowNetworking", "internal", "allowScriptAccess", "never", "src", r, "quality", "high", "bgcolor", "#ffffff", "wmode", "transparent", "allowfullscreen", "true"), this.contentCase.style.overflow = "hidden", this.bodyHeight < 0 && (this.bodyHeight = 400)) : r.substr(r.lastIndexOf(".")).toLowerCase() == ".flv" ? (this.contentCase.innerHTML = AC_FL_RunContent("width", "100%", "height", "100%", "allowNetworking", "internal", "allowScriptAccess", "never", "src", STATICURL + "image/common/flvplayer.swf", "flashvars", "file=" + encodeURI(r), "quality", "high", "bgcolor", "#ffffff", "wmode", "transparent", "allowfullscreen", "true"), this.contentCase.style.overflow = "hidden", this.bodyHeight < 0 && (this.bodyHeight = 400)) : (this.loadding || (this.loadding = jQuery('<div id="window_content_loadding' + this.id + '" class="window_content_loadding"><div class="window_content_loadding_back"><table width="100%" height="100%"><tr><td align="center" valign="middle"><div class="window_content_loadding_back_img"></div></td></tr></table></div><div class="window_content_loadding_moving"></div></div>').appendTo(this.contentCase).get(0)), this.iframe ? (this.contentCase.firstChild != this.iframe && this.contentCase.replaceChild(this.iframe, this.contentCase.firstChild), this.iframe.src = r) : (s = {
		uid: _config.space.uid,
		ukey: _config.ukey,
		username: _config.space.username,
		password: _config.space.password,
		self: _config.space.self,
		winid: this.id,
		time: +new Date
	},
	h = encodeURIComponent(jQuery.toJSON(s)), this.loaddingTimer = setTimeout(function() {
		jQuery(f.loadding).fadeOut()
	},
	5e3), f = this, e = jQuery('<iframe frameborder="0" name="' + h + '" id="ifm_' + this.id + '" marginheight="0" marginwidth="0" allowtransparency="true" src="' + r + '" class="appIframe"  style="overflow-x: hidden;" onload="jQuery(\'#window_content_loadding' + this.id + "').fadeOut();clearTimeout(" + f.loaddingTimer + ');"></iframe>').appendTo(this.contentCase), this.iframe = e.get(0), this.bodyHeight < 0 && (this.bodyHeight = 300))) : (this.form ? this.contentCase.firstChild != this.form && this.contentCase.replaceChild(this.form, this.contentCase.firstChild) : (this.form = document.createElement("form"), this.form.className = "FORM", this.form.method = "post", this.form.onsubmit = function() {
		return ! 1
	},
	this.contentCase.hasChildNodes() ? this.contentCase.replaceChild(this.form, this.contentCase.firstChild) : this.contentCase.appendChild(this.form)), n.slice(0, 4) == "[id]" ? (this.oldcontent = document.getElementById(n.slice(4)), e = jQuery(this.oldcontent).clone(), ieVersion > 0 && ieVersion < 7 && fixPNG(e, _THEME_+"/desktop/images/b.gif"), this.form.innerHTML = e.html()) : this.form.innerHTML = n, this.bodyHeight < 0 && (this.bodyHeight = jQuery(this.contentCase).height()), this.contentCase.style.overflow = "hidden", f = this, jQuery(this.contentCase).bind("mousedown",
	function() {
		f.Focus()
	})),
	this.Focus()
},
_window.OpenMsgWin = function(n, t, i, r) {
	var u = new _window(r, n);
	return n == "alert" ? u.type = "alert": n == "confirm" && (u.type = "confirm"),
	u.CreatMsgWin(n, t, i),
	u
},
_window.prototype.CreatMsgWin = function(n, t, i) {
	var u, e, c, f, r, h, o, s;
	this.board = document.createElement("div"),
	this.board.className = this.className,
	this.board.style.position = "absolute",
	this.board.style.zIndex = _window.zIndex,
	this.board.style.visibility = "hidden",
	this.desktop = _config.currentDesktop,
	jQuery("#MsgContainer").empty().show(),
	document.getElementById("MsgContainer").appendChild(this.board),
	this.isModal && (this.modal = document.createElement("div"), this.modal.className = "MODAL", this.modal.style.zIndex = _window.zIndex - 1, document.getElementById("MsgContainer").appendChild(this.modal)),
	_window.clientHeight || (this.board.style.left = "100%", this.board.style.top = "100%", _window.clientWidth = this.board.offsetLeft, _window.clientHeight = this.board.offsetTop),
	u = ["LEFT_TOP", "TOP", "RIGHT_TOP", "RIGHT", "RIGHT_BOTTOM", "BOTTOM", "LEFT_BOTTOM", "LEFT"],
	this.sides = [],
	this.button && (u[4] = "RIGHT_BOTTOM_BY_BUTTON", u[5] = "BOTTOM_BY_BUTTON", u[6] = "LEFT_BOTTOM_BY_BUTTON", u[u.length] = "BUTTON"),
	u[u.length] = "TITLE",
	u[u.length] = "CONTENT",
	e = this;
	if (this.resize != "no") for (c = this.resize.split("|"), f = 0; f < c.length; f++) u[u.length] = c[f];
	for (f = 0; f < u.length; f++) {
		r = document.createElement("div"),
		r.className = u[f],
		r.style.position = "absolute",
		r.innerHTML = '<div class="' + u[f] + '_inner inner" style="position:absolute;"></div>',
		this.board.appendChild(r);
		switch (u[f]) {
		case "CONTENT":
			this.contentCase = r,
			r.style.width = this.bodyWidth + "px",
			this.bodyHeight > 0 && (r.style.height = this.bodyHeight + "px"),
			this.SetContent_msgWin(n, i),
			r.style.left = this.sides[7].width + "px",
			r.style.top = this.sides[1].height + "px",
			this.width = this.bodyWidth + this.sides[3].width + this.sides[7].width,
			this.height = this.bodyHeight + this.sides[1].height + this.sides[5].height,
			this.minWidth = _window.getMaxNumber(this.sides[0].width + this.sides[2].width, this.sides[3].width + this.sides[7].width, this.sides[4].width + this.sides[6].width) + this.minTitleWidth,
			this.minHeight = _window.getMaxNumber(this.sides[0].height + this.sides[6].height, this.sides[1].height + this.sides[5].height, this.sides[2].height + this.sides[4].height) + 2,
			this.board.style.height = this.height + "px",
			this.board.style.width = this.width + "px";
			break;
		case "TITLE":
			this.titleCase = r,
			r.style.height = r.offsetHeight + "px",
			r.style.width = this.bodyWidth + "px",
			this.SetTitle(t),
			this.moveable ? (jQuery(this.titleCase).bind("mousedown",
			function(n) {
				e.Mousedown(n ? n: window.event)
			}), jQuery(this.titleCase).bind("mouseup",
			function(n) {
				e.Mouseup(n ? n: window.event)
			})) : jQuery(this.titleCase).bind("mousedown",
			function() {
				e.Focus()
			});
			break;
		case "BUTTON":
			for (this.buttonCase = r, r.style.width = this.bodyWidth + "px", r.style.left = this.sides[7].width + "px", r.style.bottom = "0px", this.buttonCase.dx = r.offsetWidth - this.width, this.minWidth < this.buttonCase.dx && (this.minWidth = this.buttonCase.dx), h = this.button.split("|"), o = 0; o < h.length; o++) s = document.createElement("button"),
			s.className = h[o],
			s.title = h[o],
			r.appendChild(s),
			jQuery(s).bind("click",
			function() {
				eval(e.string + ".On" + this.title + "()")
			}),
			this.buttons[h[o]] = s;
			break;
		case "RESIZE":
			r.style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_nwse.cur'),auto",
			jQuery(r).bind("mousedown",
			function(n) {
				e.resize = "yes",
				e.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-X":
			this.resizexCase = r,
			r.style.cursor = "e-resize",
			jQuery(r).bind("mousedown",
			function(n) {
				e.resize = "resize-x",
				e.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-Y":
			this.resizeyCase = r,
			r.style.cursor = "s-resize",
			jQuery(r).bind("mousedown",
			function(n) {
				e.resize = "resize-y",
				e.PreResize(n ? n: window.event)
			});
			break;
		default:
			this.sides[f] = r,
			this.sides[f].width = r.offsetWidth,
			this.sides[f].height = r.offsetHeight,
			this.moveable && (r.style.cursor = "move", jQuery(r).bind("mousedown",
			function(n) {
				e.Mousedown(n ? n: window.event)
			}), jQuery(r).bind("mouseup",
			function(n) {
				e.Mouseup(n ? n: window.event)
			}))
		}
	}
	this.sides[1].dx = this.sides[0].width + this.sides[2].width,
	this.width > this.sides[1].dx && (this.sides[1].style.width = this.width - this.sides[1].dx + "px"),
	this.sides[3].dy = this.sides[2].height + this.sides[4].height,
	this.height > this.sides[3].dy && (this.sides[3].style.height = this.height - this.sides[3].dy + "px"),
	this.sides[5].dx = this.sides[4].width + this.sides[6].width,
	this.width > this.sides[5].dx && (this.sides[5].style.width = this.width - this.sides[5].dx + "px"),
	this.sides[7].dy = this.sides[6].height + this.sides[0].height,
	this.height > this.sides[7].dy && (this.sides[7].style.height = this.height - this.sides[7].dy + "px"),
	this.sides[0].style.left = "0px",
	this.sides[0].style.top = "0px",
	this.sides[1].style.left = this.sides[0].width + "px",
	this.sides[1].style.top = "0px",
	this.sides[2].style.right = "0px",
	this.sides[2].style.top = "0px",
	this.sides[3].style.right = "0px",
	this.sides[3].style.top = this.sides[2].height + "px",
	this.sides[4].style.right = "0px",
	this.sides[4].style.bottom = "0px",
	this.sides[5].style.left = this.sides[6].width + "px",
	this.sides[5].style.bottom = "0px",
	this.sides[6].style.left = "0px",
	this.sides[6].style.bottom = "0px",
	this.sides[7].style.left = "0px",
	this.sides[7].style.top = this.sides[0].height + "px",
	this.left = parseInt((_window.clientWidth - this.width) / 2),
	this.top = parseInt((_window.clientHeight - this.height) / 2),
	this.left < 0 && (this.left = 0),
	_navbar.navbars[this.desktop].topbarshow == 0 && ieVersion > 0 && ieVersion < 8 ? this.top < jQuery("#navbar").height() && (this.top = jQuery("#navbar").height()) : this.top < 0 && (this.top = 0),
	this.left += document.documentElement.scrollLeft,
	this.top += document.documentElement.scrollTop,
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px",
	this.board.style.visibility = "visible",
	this.status = 1
},
_window.prototype.SetContent_msgWin = function(n, t) {
	this.form ? this.contentCase.firstChild != this.form && this.contentCase.replaceChild(this.form, this.contentCase.firstChild) : (this.form = document.createElement("form"), this.form.className = "FORM", this.form.method = "post", this.form.onsubmit = function() {
		return ! 1
	},
	this.contentCase.hasChildNodes() ? this.contentCase.replaceChild(this.form, this.contentCase.firstChild) : this.contentCase.appendChild(this.form)),
	this.form.innerHTML = t,
	this.bodyHeight < 0 && (this.bodyHeight = jQuery(this.contentCase).height()),
	this.contentCase.style.overflow = "hidden";
	if (this.moveable) {
		var i = this;
		jQuery(this.contentCase).bind("mousedown",
		function(n) {
			i.Mousedown(n ? n: window.event)
		}),
		jQuery(this.contentCase).bind("mouseup",
		function(n) {
			i.Mouseup(n ? n: window.event)
		})
	}
	this.Focus()
},
_window.prototype.Close = function() {
	if(jQuery("#ifm_HOME_"+_window.getCurrentWindowId()).length>0 && typeof jQuery("#ifm_HOME_"+_window.getCurrentWindowId())[0].contentWindow != 'undefined' && typeof jQuery("#ifm_HOME_"+_window.getCurrentWindowId())[0].contentWindow.windowClose == 'function') jQuery("#ifm_HOME_"+_window.getCurrentWindowId())[0].contentWindow.windowClose();
	var t, n;
	if (this.needsave > 0) if (!confirm(_lang.need_save_confirm)) return ! 1;
	if (this.type == "widget") if (this.desktop.indexOf("sys_") === -1) {
		if (_config.screenList.screenlist_u[this.desktop]) {
			for (n in _config.screenList.screenlist_u[this.desktop].wins[this.icoid]) delete _config.screenList.screenlist_u[this.desktop].wins[this.icoid][n];
			delete _config.screenList.screenlist_u[this.desktop].wins[this.icoid],
			_config.saveItem.screenlist = 1
		}
	} else if (_config.screenList.screenlist_0[this.desktop]) {
		for (n in _config.screenList.screenlist_0[this.desktop].wins[this.icoid]) delete _config.screenList.screenlist_0[this.desktop].wins[this.icoid][n];
		delete _config.screenList.screenlist_0[this.desktop].wins[this.icoid],
		_config.saveItem.screenlist = 1
	}
	if (this.tabs) for (t in this.tabs) this.tabs[t].frame && (jQuery(this.tabs[t].frame).remove(), delete this.tabs[t].frame),
	delete this.tabs[t];
	jQuery(this.board).remove(),
	jQuery("#MsgContainer").hide(),
	this.modal && jQuery(this.modal).remove();
	if (this.id == "_W_sys_browser") for (n in _tab.tabs) window.frames["ifm_browser_" + n] && jQuery("#ifm_browser_" + n).remove(),
	delete _tab.tabs[n];
	_task.icos[this.taskid] && _task.Dtask(_task.icos[this.taskid]),
	delete _window.windows[this.id];
	for (n in this) delete this[n];
	return _navbar.navbars[_config.currentDesktop].setDockShow(),
	_navbar.navbars[_config.currentDesktop].setTopbarShow(),
	ieVersion > 0 && ieVersion < 8 && _navbar.checkTopbar_overWindow(_config.currentDesktop),
	!1
},
_window.prototype.Min = function() {
	if (this.minmine == "no") return;
	jQuery(this.board).hide(),
	this.isHide = 1,
	this.status = 0,
	_navbar.navbars[_config.currentDesktop].setDockShow(),
	_navbar.navbars[_config.currentDesktop].setTopbarShow()
},
_window.prototype.Showhide = function() {
	this.Focus(),
	this.board.style.display = "block",
	this.isHide = 0,
	this.status = 1
},
_window.prototype.Windowmenu = function() {},
_window.prototype.Max = function() {
	var n, t, i;
	if (this.maxmine == "no") return;
	n = document.documentElement.clientWidth,
	t = document.documentElement.clientHeight,
	this.board.style.height = t + this.sides[8].height + this.sides[10].height + "px",
	this.board.style.width = n + this.sides[9].width + this.sides[11].width + "px",
	this.board.style.left = -this.sides[11].width + "px",
	this.board.style.top = -this.sides[8].height + "px",
	this.oldleft = this.left,
	this.left = -this.sides[11].width,
	this.oldtop = this.top,
	this.top = -this.sides[8].height,
	this.oldwidth = this.width,
	this.width = n + this.sides[9].width + this.sides[11].width,
	this.oldheight = this.height,
	this.height = t + this.sides[8].height + this.sides[10].height,
	this.oldbodyWidth = this.bodyWidth,
	this.bodyWidth = n + this.sides[9].width + this.sides[11].width - this.sides[7].width - this.sides[3].width,
	this.oldbodyHeight = this.bodyHeight,
	this.bodyHeight = t + this.sides[8].height + this.sides[10].height - this.sides[5].height - this.sides[1].height - jQuery(this.topbarCase).height(),
	this.contentCase.style.width = this.bodyWidth + "px",
	this.contentCase.style.height = this.bodyHeight + "px",
	this.id != "_W_sys_browser" && (this.titleCase.style.width = this.bodyWidth + "px"),
	this.button && (this.buttonCase.style.width = n + this.sides[9].width + this.sides[11].width - this.sides[7].width - this.sides[3].width + "px", this.buttonCase.style.bottom = "0px"),
	typeof this.topbarCase != "undefined" && (this.topbarCase.style.width = n + this.sides[9].width + this.sides[11].width - this.sides[0].width - this.sides[2].width + "px"),
	this.sides[0].style.left = "0px",
	this.sides[0].style.top = "0px",
	this.sides[1].style.left = this.sides[0].width + "px",
	this.sides[1].style.width = n + this.sides[9].width + this.sides[11].width - this.sides[0].width - this.sides[2].width + "px",
	this.sides[1].style.top = "0px",
	this.sides[2].style.right = "0px",
	this.sides[2].style.top = "0px",
	this.sides[3].style.right = "0px",
	this.sides[3].style.top = this.sides[2].height + "px",
	this.sides[3].style.height = t + this.sides[8].height + this.sides[10].height - this.sides[2].height - this.sides[4].height + "px",
	this.sides[4].style.right = "0px",
	this.sides[4].style.bottom = "0px",
	this.sides[5].style.left = this.sides[6].width + "px",
	this.sides[5].style.width = n + this.sides[9].width + this.sides[11].width - this.sides[4].width - this.sides[6].width + "px",
	this.sides[5].style.bottom = "0px",
	this.sides[6].style.left = "0px",
	this.sides[6].style.bottom = "0px",
	this.sides[7].style.left = "0px",
	this.sides[7].style.top = this.sides[0].height + "px",
	this.sides[7].style.height = t + this.sides[8].height + this.sides[10].height - this.sides[0].height - this.sides[6].height + "px",
	this.move = "no",
	this.MAX = 1,
	_window.Max[this.id] = 1,
	this.Focus(),
	this.id == "_W_sys_browser" && (document.getElementById("tabs_container").style.left = document.getElementById("tabs_cover").offsetLeft + "px", document.getElementById("tabs_container").style.width = document.getElementById("tabs_cover").offsetWidth + "px", document.getElementById("leftrun") && (document.getElementById("leftrun").style.left = document.getElementById("tabs_cover").offsetLeft + "px"), _window.resetTabs()),
	i = jQuery(this.titleCase),
	i.find(".RESTORE").show(),
	i.find(".MAX").hide(),
	jQuery("#detail_content_" + this.id + "_" + this.filemanageid).css("height", this.bodyHeight - jQuery(this.contentCase).find(".filemanage_header").height()),
	_config.dock_up_down("down"),
	jQuery("#navbar").hide()
},
_window.prototype.Restore = function() {
	if (!this.MAX) return;
	this.width = this.oldwidth,
	this.height = this.oldheight,
	this.left = this.oldleft,
	this.top = this.oldtop,
	this.bodyWidth = this.oldbodyWidth,
	this.bodyHeight = this.oldbodyHeight,
	this.contentCase.style.width = this.bodyWidth + "px",
	this.contentCase.style.height = this.bodyHeight + "px",
	typeof this.topbarCase != "undefined" && (this.topbarCase.style.width = this.sides.topbar.width + "px"),
	this.id != "_W_sys_browser" && (this.titleCase.style.width = this.bodyWidth + "px"),
	this.board.style.height = this.height + "px",
	this.board.style.width = this.width + "px",
	this.button && (this.buttonCase.style.width = this.width - this.sides[7].width + "px", this.buttonCase.style.bottom = "0px"),
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px",
	this.sides[1].dx = this.sides[0].width + this.sides[2].width,
	this.width > this.sides[1].dx && (this.sides[1].style.width = this.width - this.sides[1].dx + "px"),
	this.sides[3].dy = this.sides[2].height + this.sides[4].height,
	this.height > this.sides[3].dy && (this.sides[3].style.height = this.height - this.sides[3].dy + "px"),
	this.sides[5].dx = this.sides[4].width + this.sides[6].width,
	this.width > this.sides[5].dx && (this.sides[5].style.width = this.width - this.sides[5].dx + "px"),
	this.sides[7].dy = this.sides[6].height + this.sides[0].height,
	this.height > this.sides[7].dy && (this.sides[7].style.height = this.height - this.sides[7].dy + "px"),
	this.sides[0].style.left = "0px",
	this.sides[0].style.top = "0px",
	this.sides[1].style.left = this.sides[0].width + "px",
	this.sides[1].style.top = "0px",
	this.sides[2].style.right = "0px",
	this.sides[2].style.top = "0px",
	this.sides[3].style.right = "0px",
	this.sides[3].style.top = this.sides[2].height + "px",
	this.sides[4].style.right = "0px",
	this.sides[4].style.bottom = "0px",
	this.sides[5].style.left = this.sides[6].width + "px",
	this.sides[5].style.bottom = "0px",
	this.sides[6].style.left = "0px",
	this.sides[6].style.bottom = "0px",
	this.sides[7].style.left = "0px",
	this.sides[7].style.top = this.sides[0].height + "px",
	this.move = "move",
	this.MAX = 0,
	_window.Max[this.id] = 0,
	this.Focus(),
	this.id == "_W_sys_browser" && (document.getElementById("tabs_container").style.left = document.getElementById("tabs_cover").offsetLeft + "px", document.getElementById("tabs_container").style.width = document.getElementById("tabs_cover").offsetWidth + "px", document.getElementById("leftrun") && (document.getElementById("leftrun").style.left = document.getElementById("tabs_cover").offsetLeft + "px"), _window.resetTabs());
	var n = jQuery(this.titleCase);
	n.find(".RESTORE").hide(),
	n.find(".MAX").show(),
	jQuery("#detail_content_" + this.id + "_" + this.filemanageid).css("height", this.bodyHeight - jQuery(this.contentCase).find(".filemanage_header").height()),
	_navbar.navbars[_config.currentDesktop].setDockShow(),
	_navbar.navbars[_config.currentDesktop].setTopbarShow(),
	ieVersion > 0 && ieVersion < 8 && _navbar.checkTopbar_overWindow(_config.currentDesktop)
},
_window.prototype.Hidden = function() {
	this.board.style.zIndex = -99999
},
_window.prototype.Show = function() {
	this.oldcase && this.form.appendChild(this.oldcontent),
	document.body.appendChild(this.board),
	this.status = 1
},
_window.prototype.DetachEvent = function() {
	if (!_window.tach) return;
	document.onmousemove = _window.onmousemove,
	document.onmousemove = _window.onmousemove,
	document.onmouseup = _window.onmouseup,
	document.onselectstart = _window.onselectstart,
	this.board.releaseCapture && this.board.releaseCapture(),
	_window.tach = 0,
	jQuery("#_blank").hide()
},
_window.prototype.AttachEvent = function(n) {
	if (_window.tach) return;
	_window.onmousemove = document.onmousemove,
	_window.onmouseup = document.onmouseup,
	_window.onselectstart = document.onselectstart,
	n.preventDefault ? n.preventDefault() : (document.onselectstart = function() {
		return ! 1
	},
	this.board.setCapture && this.board.setCapture()),
	_window.tach = 1
},
_window.prototype.Focus = function(n) {
	return _window.desktophide == 1 && _window.showDesktop(),
	this.zIndex < _window.zIndex && (this.board.style.zIndex = this.zIndex = ++_window.zIndex),
	this.desktop && this.desktop != _config.currentDesktop && _navbar.setCurrentDesktop(this.desktop),
	jQuery(".window").removeClass("window_current"),
	jQuery("#" + this.id).addClass("window_current"),
	n || (jQuery(".window_blank").each(function() {
		var n = this.id.replace("_blank_", "");
		_window.windows[n] && _window.windows[n].type != "folder" && _window.windows[n].type != "filemanage" && _window.windows[n].type != "image" && jQuery(this).css("z-index", 100)
	}), jQuery(this.blank).css("z-index", -1)),
	this.isHide = 0,
	this.status = 1,
	jQuery(this.board).show(),
	!1
},
_window.prototype.PreResize = function(n) {
	if (this.move == "no") return;
	typeof this.ResizeTimer != "undefined" && clearTimeout(this.ResizeTimer),
	this.Focus(),
	this.resizeX = n.clientX - this.width - 4,
	this.resizeY = n.clientY - this.height - 4,
	this.AttachEvent(n),
	document.getElementById("_blank").style.cursor = this.resize == "resize-x" ? "e-resize": this.resize == "resize-y" ? "s-resize": "se-resize",
	document.getElementById("_blank").style.display = "";
	var t = this;
	eval("document.onmousemove=function(e){" + this.string + ".Resize(e?e:window.event);};"),
	eval("document.onmouseup=function(e){" + this.string + ".Resized(e?e:window.event);};")
},
_window.prototype.Resize = function(n) {
	var i = 0,
	r = 0,
	u, t;
	this.resize != "resize-y" && (u = n.clientX - this.resizeX - 4, u + this.left > _config.screenWidth && (u = _config.screenWidth - this.left), i = u - this.width, this.bodyWidth + i < this.minWidth && (i = this.minWidth - this.bodyWidth), this.width += i * 1, this.board.style.width = this.width + "px", this.sides[1].style.width = this.width - this.sides[1].dx + "px", this.sides[5].style.width = this.width - this.sides[5].dx + "px", this.bodyWidth += i * 1, this.id != "_W_sys_browser" && (this.titleCase.style.width = this.bodyWidth + "px"), this.contentCase.style.width = this.bodyWidth + "px", typeof this.topbarCase != "undefined" && (this.topbarCase.style.width = this.bodyWidth + "px"), this.id == "_W_sys_browser" && (document.getElementById("tabs_container").style.left = document.getElementById("tabs_cover").offsetLeft + "px", document.getElementById("tabs_container").style.width = document.getElementById("tabs_cover").offsetWidth + "px", document.getElementById("leftrun") && (document.getElementById("leftrun").style.left = document.getElementById("tabs_cover").offsetLeft + "px"), _window.resetTabs()), this.id == "_W_openfile" && (jQuery("#right_top").css("width", this.bodyWidth - jQuery("#openfile_left").width()), jQuery("#right_bottom").css("width", this.bodyWidth - jQuery("#openfile_left").width()))),
	this.resize != "resize-x" && (t = n.clientY - this.resizeY, t = t > this.minHeight * 1 ? t: this.minHeight * 1, t + this.top > _config.screenHeight && (t = _config.screenHeight - this.top), r = t - this.height, this.bodyHeight + r < this.minHeight * 1 && (i = this.minHeight * 1 - this.bodyHeight), this.height += r * 1, this.board.style.height = this.height + "px", this.sides[3].style.height = this.height - this.sides[3].dy + "px", this.sides[7].style.height = this.height - this.sides[7].dy + "px", this.bodyHeight += r * 1, this.id == "_W_openfile" && jQuery("#right_top").css("height", this.bodyHeight - jQuery("#right_bottom").height()), this.contentCase.style.height = this.bodyHeight + "px", jQuery("#detail_content_" + this.id + "_" + this.filemanageid).css("height", this.bodyHeight - jQuery(this.contentCase).find(".filemanage_header").height()))
},
_window.prototype.Resized = function(n) {
	this.DetachEvent(n),
	document.getElementById("_blank").style.display = "none",
	document.getElementById("_blank").style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_arrow.cur'),auto",
	setcookie("win_width_" + this.id, this.bodyWidth * 1, 31536e3),
	setcookie("win_height_" + this.id, this.bodyHeight * 1, 31536e3)
},
_window.prototype.ResizeTo = function(n, t) {
	var r = n * 1 - this.bodyWidth * 1,
	i = t * 1 - this.bodyHeight * 1;
	r && (this.width += r * 1, this.board.style.width = this.width + "px", this.sides[1].style.width = this.width - this.sides[1].dx + "px", this.sides[5].style.width = this.width - this.sides[5].dx + "px", this.titleCase.style.width = this.bodyWidth + "px", this.buttonCase && (this.buttonCase.style.width = this.width - this.buttonCase.dx + "px"), this.bodyWidth += r * 1, this.blank && (this.blank.style.height = this.bodyWidth + "px"), this.contentCase.style.width = this.bodyWidth + "px", this.contentCase.style.overflow = "hidden"),
	i && (this.height += i * 1, this.board.style.height = this.height + "px", this.sides[3].style.height = this.height - this.sides[3].dy + "px", this.sides[7].style.height = this.height - this.sides[7].dy + "px", this.bodyHeight += i * 1, this.blank && (this.blank.style.height = this.bodyHeight + "px"), this.contentCase.style.height = this.bodyHeight + "px", this.contentCase.style.overflow = "hidden"),
	setcookie("win_width_" + this.id, this.bodyWidth * 1, 31536e3),
	setcookie("win_height_" + this.id, this.bodyHeight * 1, 31536e3)
},
_window.prototype.ResizeBy = function(n, t) {
	n && (this.width += n * 1, this.left -= Math.ceil(n / 2), this.board.style.width = this.width + "px", this.board.style.left = this.left + "px", this.blank && (this.blank.style.width = this.width + "px"), this.sides[1].style.width = this.width - this.sides[1].dx + "px", this.sides[5].style.width = this.width - this.sides[5].dx + "px", this.buttonCase && (this.buttonCase.style.width = this.width - this.buttonCase.dx + "px"), this.bodyWidth += n * 1, this.contentCase.style.width = this.bodyWidth + "px"),
	t && (this.height += t * 1, this.top -= Math.ceil(t / 2), this.board.style.height = this.height + "px", this.board.style.top = this.top + "px", this.blank && (this.blank.style.height = this.height + "px"), this.sides[3].style.height = this.height - this.sides[3].dy + "px", this.sides[7].style.height = this.height - this.sides[7].dy + "px", this.bodyHeight += t * 1, this.contentCase.style.height = this.bodyHeight + "px")
},
_window.prototype.ActResizeBy = function(n, t, i) {
	var u, r;
	jQuery.browser.msie ? this.ResizeBy(n, t) : (n != 0 || t != 0) && (u = n / 10, u = u > 0 ? Math.ceil(u) : Math.floor(u), r = t / 10, r = r > 0 ? Math.ceil(r) : Math.floor(r), this.ResizeBy(u, r), n -= u, t -= r, i && this.ResizeTimer && clearTimeout(this.ResizeTimer), this.ResizeTimer = window.setTimeout(this.string + ".ActResizeBy(" + n + "," + t + ")", 50))
},
_window.prototype.Mousedown = function(n) {
	var t;
	if (jQuery.browser.msie) {
		if (n.button > 1) return
	} else if (n.button > 0) return;
	this.Focus();
	var u = this,
	i = n.clientX,
	r = n.clientY;
	if (!this.moveable) return ! 0;
	t = n.srcElement ? n.srcElement: n.target;
	if (t.type == "text" || t.type == "textarea") return ! 0;
	this.mousedowndoing = !1,
	this.AttachEvent(n),
	_window.even = n,
	this.mousedownTimer = setTimeout(function() {
		u.PreMove(i, r)
	},
	200)
},
_window.prototype.Mouseup = function(n) {
	_window.tach && this.DetachEvent(n),
	this.mousedowndoing || clearTimeout(this.mousedownTimer)
},
_window.prototype.PreMove = function(n, t) {
	jQuery("#_blank").empty().show(),
	this.mousedowndoing = !0,
	this.moveX = n - this.left,
	this.moveY = t - this.top,
	eval("document.onmousemove=function(e){" + this.string + ".Move(e?e:window.event);};"),
	eval("document.onmouseup=function(e){" + this.string + ".Moved(e?e:window.event);};")
},
_window.prototype.Move = function(n) {
	if (!this.mousedowndoing) return;
	var i = n.clientX,
	t = n.clientY;
	i < 0 && (i = 0),
	t < 0 && (t = 0),
	i > _config.screenWidth && (i = _config.screenWidth),
	t > _config.screenHeight && (t = _config.screenHeight),
	this.move != "move-y" && (this.board.style.left = i - this.moveX + "px", this.left = i - this.moveX),
	this.move != "move-x" && (this.board.style.top = t - this.moveY + "px", this.top = t - this.moveY)
},
_window.prototype.Moved = function(n) {
	var i, t, u, r;
	_window.tach && this.DetachEvent(n),
	i = n.clientX,
	t = n.clientY,
	i < 0 && (i = 0),
	t < 0 && (t = 0),
	i > _config.screenWidth && (i = _config.screenWidth),
	t > _config.screenHeight && (t = _config.screenHeight),
	u = this.move == "move-y" ? null: i - this.moveX,
	r = this.move == "move-x" ? null: t - this.moveY,
	this.board.style.left = u + "px",
	this.board.style.top = r + "px",
	_config.setWindowToSave(this),
	setcookie("win_left_" + this.id, this.left * 1, 31536e3),
	setcookie("win_top_" + this.id, this.top * 1, 31536e3),
	ieVersion > 0 && ieVersion < 8 && _navbar.checkTopbar_overWindow(_config.currentDesktop)
},
_window.prototype.DisableButton = function(n, t) {
	n = n.toUpperCase(),
	this.buttons[n].disabled = !0,
	this.buttons[n].className = (t ? t: "DISABLED") + " " + n
},
_window.prototype.EnableButton = function(n) {
	n = n.toUpperCase(),
	this.buttons[n].disabled = !1,
	this.buttons[n].className = n
},
_window.prototype.OnOK = function() {
	this.Close()
},
_window.prototype.OnCANCEL = function() {
	this.Close()
},
_login.menuwidth = 150,
_login.Clogin = function() {
	_login(),
	_login.create()
},
_login.logging = function() {
	_window.showDesktop(),
	showWindow("login", _config.loginurl)
},
_login.create = function() {
	var t, i, n;
	_login.board = document.createElement("div"),
	_login.board.className = _login.className,
	_login.board.style.position = "absolute",
	t = ieVersion > 0 && ieVersion < 7 && _login.ispng ? "<img  src='"+_THEME_+"/desktop/images/b.gif' width=" + _login.width + " height=" + _login.height + "  style=\"_filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + _login.img + "', sizingMethod='scale');\" title=\"" + _login.text + '">': '<img src="' + _login.img + '" style="width:' + _login.width + "px;height:" + _login.height + 'px;" title="' + _login.text + '">',
	i = "",
	_login.board.innerHTML = "<table width='" + _login.divwidth + "' height='" + _login.divheight + "' align='center'><tr><td align='center' valign='middle' style=\"overflow:hidden;\" >" + t + "</td>" + i + "</tr></table>",
	_login.icoblank = document.createElement("div"),
	_login.icoblank.style.position = "absolute",
	_login.icoblank.className = "icoblank",
	_login.icoblank.id = "_shadow_" + _login.id,
	_login.board.appendChild(_login.icoblank),
	_login.icoblank.style.cssText = "position:absolute;;left:0px;top:0px; background:url('"+_THEME_+"/desktop/images/b.gif');width:" + _login.divwidth + "px; height:" + _login.divheight + "px;z-index:" + (_login.zIndex + 1),
	_login.board_background = document.createElement("div"),
	_login.board_background.className = "backgound_radius",
	_login.board.appendChild(_login.board_background),
	_login.board_background.style.cssText = "position:absolute;left:0px;top:0px;z-index:-5;width:" + (_login.divwidth - 2) + "px;height:" + (_login.divheight - 2) + "px;",
	n = jQuery(_login.icoblank),
	n.bind("mouseover",
	function() {
		jQuery(_login.board_background).addClass("border_background"),
		_login.board_background.style.background = _ico._defaultbgcolor
	}),
	n.bind("mouseout",
	function() {
		jQuery(_login.board_background).removeClass("border_background"),
		_login.board_background.style.background = ""
	}),
	n.bind("click",
	function() {
		OpenWindow('url',U('desktop/market', ['container=icosContainer_body_1']),'应用市场','','titlebutton=close,width=900,height=530');
		//OpenFileManage();
	}),
	document.getElementById(_login.container).appendChild(_login.board),
	_login.left = 0,
	_login.top = 0,
	_login.board.style.left = _login.left + "px",
	_login.board.style.top = _login.top + "px",
	_login.board.style.width = _login.divwidth + "px",
	_login.board.style.height = _login.divheight + "px",
	_login.board.style.zIndex = _login.zIndex,
	_login.board.style.overflow = "hidden",
	jQuery("#" + _login.container).css("width", _login.divwidth)
},
_login.click = function(n, t, option) {
	jQuery("#shadow").hide();
	switch (n) {
	case "index":
		_config.setHomepage(location.href);
		break;
	case "fav":
		_config.addFavorite(location.href, document.title);
		break;
	case "sys_market":
		OpenWindow("sys_market", "", "", t);
		break;
	case "sys_widget":
		OpenWindow("sys_widget");
		break;
	case "sys_config":
		OpenWindow("ControlPanel");
		break;
	case "sys_theme":
		OpenWindow("sys_theme");
		break;
	case "sys_browser":
		OpenBrowser(_config.sysbrowserurl, _lang.browser);
		break;
	case "my_setting":	//个人设置 2013/6/6 孙晓波添加
		OpenWindow('url',U('home/Account/index'),'个人设置','','titlebutton=close|max|min,width=1035,height=600');
		break;
	case "url":
		alert(U(option.url));
		if(typeof(option.setting)=='undefined') option.setting = 'titlebutton=close|max|min,width=1035,height=600';
		OpenWindow('url',U(option.url),option.title,'',option.setting);
		break;
	case "logout":
		_config.sendConfig(),
		jQuery.get(_config.logouturl + "&formhash=" + _config.formhash + "&t=" + new Date,
		function() {
			window.onbeforeunload = null,
			window.location.reload()
		})
	}
	return ! 1
},
_task.icos = {},
_task.className = "task_Icoblank",
_task.indocksum = 5,
_task.istaskup = 0,
_task.divheight = 24,
_task.divwidth = 150,
_task.padding = 5,
_task.Ctasklist = function() {
	var i, t, n;
	jQuery("#_stick").empty(),
	jQuery("#stick_container").empty();
	for (i in _task.icos) delete _task.icos[i];
	for (t = _config.dockTaskList, _config.dockTaskList = [], n = 0; n < t.length; n++) _task.Ctask(t[n]);
	_task.taggle_task_up()
},
_task.Ctask = function(n) {
	if (_task.icos[n]) return _task.icos[n];
	_config.dockTaskList.push(n);
	var t = new _task(n);
	return t.create(),
	t
},
_task.taggle_task_up = function() {
	var r, n, t, i;
	_task.istaskup ? _config.dockTaskList.length <= _task.indocksum ? (jQuery("#dock_task_up_ico").remove(), jQuery("#_stick").css("width", _config.dockTaskList.length * _ico.divwidth), _task.istaskup = 0) : jQuery("#_stick").css("width", _task.indocksum.length * _ico.divwidth) : _config.dockTaskList.length > _task.indocksum ? (jQuery("#_stick").css("width", _task.indocksum * _ico.divwidth + _ico.divwidth), r = document.createElement("div"), n = jQuery(r), n.attr("id", "dock_task_up_ico"), n.css({
		left: _task.indocksum * _ico.divwidth,
		position: "absolute"
	}), t = ieVersion > 0 && ieVersion < 7 ? "<img  src='"+_THEME_+"/desktop/images/b.gif' width=" + (_ico.width + 10) + " height=" + (_ico.height + 10) + "  style=\"_filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+_THEME_+"/desktop/images/up.png', sizingMethod='scale');\" >": '<img class="radius" src=_THEME_+"/desktop/images/up.png" style="width:' + (_ico.width + 10) + "px;height:" + (_ico.height + 10) + 'px;" >', i = "<table width='" + _ico.divwidth + "' height='" + _ico.divheight + "' ><tr><td width='" + _ico.divwidth + "' height='" + _ico.divheight + "'  align='center' valign='middle' style=\"overflow:hidden;\" >" + t + "</td></tr></table>", n.html(i), n.appendTo("#_stick").show(), n.bind("click",
	function() {
		_task.setStickContainer()
	}), _task.istaskup = 1) : jQuery("#_stick").css("width", _config.dockTaskList.length * _ico.divwidth ? _config.dockTaskList.length * _ico.divwidth: 1),
	_config.setDockSize()
},
_task.setStickContainer = function() {
	var n = jQuery("#stick_container");
	if (_config.dockTaskList.length > _task.indocksum) {
		var i = _config.dockTaskList.length - _task.indocksum,
		r = i * _task.divheight,
		t = jQuery("#dock_task_up_ico").offset();
		t.left + _task.divwidth > _config.screenWidth && (t.left = _config.screenWidth - _task.divwidth - 15),
		n.css({
			width: _task.divwidth + "px",
			height: r + "px",
			bottom: _config.dockHeight + "px",
			left: t.left + "px",
			margin: _task.padding + "px"
		}),
		document.getElementById("stick_container").style.display == "none" ? (n.show(), jQuery(document).bind("mousedown.stick_container",
		function(n) {
			var i = jQuery("#stick_container"),
			t = i.offset(),
			u = n.clientX,
			r = n.clientY; (u < t.left || u > t.left + i.width() || r < t.top || r > t.top + i.height()) && (jQuery("#stick_container").hide(), jQuery(document).unbind(".stick_container"))
		})) : n.hide()
	} else n.hide()
},
_task.Dtask = function(n) {
	for (var i = [], t = 0; t < _config.dockTaskList.length; t++) _config.dockTaskList[t] != n.id && (i[i.length] = _config.dockTaskList[t]);
	_config.dockTaskList = i,
	_task.Ctasklist()
},
_task.resetTask = function(n, t) {
	for (var r = [], i = 0; i < _config.dockTaskList.length; i++) _config.dockTaskList[i] == n && (_config.dockTaskList[i] = t);
	_task.Ctasklist()
},
_task.prototype.create = function() {
	var i, r, n, t;
	this.board = document.createElement("div"),
	this.board.className = this.className,
	this.board.style.position = "absolute",
	i = ieVersion > 0 && ieVersion < 7 && this.ispng ? "<img  src='"+_THEME_+"/desktop/images/b.gif' width=" + this.width + " height=" + this.height + "  style=\"_filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + this.img + "', sizingMethod='scale');\" title=\"" + this.text + '">': '<img class="radius" src="' + this.img + '" style="width:' + this.width + "px;height:" + this.height + 'px;" title="' + this.text + '">',
	r = this.container == "stick_container" ? '<td valign=\'middle\' style="white-space:nowrap;"><div style="line-height:' + this.height + "px\"><a class='IcoText_folder'>" + this.text + "</a></div></td>": "",
	this.board.innerHTML = this.container != "stick_container" ? "<table width='" + this.divwidth + "' height='" + this.divheight + "' style=\"table-layout:fixed;\" ><tr><td align='center' valign='middle' style=\"overflow:hidden;\" id='html_" + this.id + "'>" + i + "</td>" + r + "</tr></table>": "<table width='" + this.divwidth + "' height='" + this.divheight + '\' style="table-layout:fixed;" ><tr><td width="' + this.width + '" height="' + this.height + "\" align='center' valign='middle' style=\"overflow:hidden;\" id='html_" + this.id + "'>" + i + "</td>" + r + "</tr></table>",
	n = this,
	this.icoblank = document.createElement("div"),
	this.icoblank.style.position = "absolute",
	this.icoblank.className = "task_icoblank",
	this.icoblank.title = this.text,
	this.board.appendChild(this.icoblank),
	this.icoblank.style.cssText = "position:absolute;;left:0px;top:0px; background:url('"+_THEME_+"/desktop/images/b.gif');width:" + this.divwidth + "px; height:" + this.divheight + "px;z-index:" + (this.zIndex + 1),
	this.board_background = document.createElement("div"),
	this.board_background.className = "backgound_radius",
	this.board.appendChild(this.board_background),
	this.board_background.style.cssText = "position:absolute;left:0px;top:0px;z-index:-5;width:" + (this.divwidth - 2) + "px;height:" + (this.divheight - 2) + "px;",
	t = jQuery(this.icoblank),
	t.bind("mouseover",
	function() {
		jQuery(n.board_background).addClass("border_background"),
		n.board_background.style.background = _ico._defaultbgcolor
	}),
	t.bind("mouseout",
	function() {
		jQuery(n.board_background).removeClass("border_background"),
		n.board_background.style.background = ""
	}),
	t.bind("click",
	function() {
		_window.windows[n.winid].Focus()
	}),
	this.container != "stick_container" && t.bind("contextmenu",
	function(t) {
		return _contextmenu.task_right_Ico(t ? t: window.event, n.id),
		!1
	}),
	document.getElementById(this.container).appendChild(this.board),
	this.container == "_stick" ? (this.left = this.pos * this.divwidth, this.top = 0) : (this.top = (this.pos - _task.indocksum) * this.divheight + _task.padding, this.left = _task.padding),
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px",
	this.board.style.width = this.divwidth + "px",
	this.board.style.height = this.divheight + "px",
	this.board.style.zIndex = this.zIndex,
	this.board.style.overflow = "hidden",
	_task.taggle_task_up()
},
_task.prototype.Taskmenu = function(n) {
	var r = n.clientX,
	i = n.clientY,
	e = document.getElementById("task_right_Ico").innerHTML.replace(/_taskid/g, this.id),
	t = jQuery("#task_right_Ico").clone(),
	u,
	f;
	return t.html(e),
	jQuery("#shadow_center").empty(),
	t.appendTo("#shadow_center"),
	ieVersion > 0 && ieVersion < 7 && fixPNG(t, _THEME_+"/desktop/images/b.gif"),
	t.find(".menu-item").bind("mouseover",
	function() {
		jQuery(this).addClass("menu-active")
	}),
	t.find(".menu-item").bind("mouseout",
	function() {
		jQuery(this).removeClass("menu-active")
	}),
	u = document.documentElement.clientWidth,
	f = document.documentElement.clientHeight,
	r + jQuery("#shadow").width() > u && (r = r - jQuery("#shadow").width()),
	i + jQuery("#shadow").height() > f && (i = i - jQuery("#shadow").height()),
	jQuery("#shadow").css({
		display: "block",
		zIndex: 9998,
		left: r,
		top: i
	}).show(),
	jQuery("#shadow_center").css({
		width: t.outerWidth(),
		height: t.outerHeight()
	}),
	jQuery(document).bind("mousedown.task",
	function(n) {
		n = n ? n: window.event;
		var t = n.srcElement ? n.srcElement: n.target,
		r = jQuery(t).parent().parent().parent().attr("id"),
		i = jQuery(t).parent().attr("id");
		r != "task_right_Ico" && i != "task_right_Ico" && (jQuery("#shadow").hide(), jQuery(document).unbind(".task"))
	}),
	!1
},
_task.Focus = function(n) {
	jQuery("#shadow").hide();
	var t = _task.icos[n];
	_window.windows[t.winid].MAX ? _window.windows[t.winid].Restore() : _window.windows[t.winid].Focus()
},
_task.Max = function(n) {
	jQuery("#shadow").hide();
	var t = _task.icos[n];
	_window.windows[t.winid].MAX != 1 ? _window.windows[t.winid].Max() : _window.windows[t.winid].Focus()
},
_task.Min = function(n) {
	jQuery("#shadow").hide();
	var t = _task.icos[n];
	_window.windows[t.winid].Min()
},
_task.Close = function(n) {
	jQuery("#shadow").hide();
	var t = _task.icos[n];
	_window.windows[t.winid].Close()
},
_widget.widgets = {},
_widget.zIndex = 0,
_widget.Width = 100,
_widget.Height = 100,
_widget.clientWidth = document.documentElement.clientWidth,
_widget.clientHeight = document.documentElement.clientHeight,
_widget.onmousemove = null,
_widget.onmouseup = null,
_widget.onselectstart = 1,
_widget.New = function(n) {
	showWindow("addwidget", _config.ajaxurl + "&do=widget&container=" + n + "&uid=" + _config.uid + "&ukey=" + _config.ukey, "get", 0)
},
_widget.setToWidget = function(n) {
	var t = "icosContainer_body_" + _config.currentDesktop;
	showWindow("addwidget", _config.ajaxurl + "&do=widget&icoid=" + n + "&container=" + t + "&uid=" + _config.uid + "&ukey=" + _config.ukey, "get", 0)
},
_widget.CWidget = function(n) {
	var i, t;
	return _widget.widgets["_G_" + n] && (i = {
		left: _widget.widgets["_G_" + n].left,
		top: _widget.widgets["_G_" + n].top,
		zIndex: _widget.widgets["_G_" + n].zIndex
	}),
	t = new _widget(n),
	i && i.zIndex && (t.left = i.left, t.top = i.top, t.zIndex = i.zIndex),
	t.Creat(),
	t
},
_widget.prototype.Creat = function() {
	var f, r, u, t, i, o, e, n;
	for (jQuery("#widget_" + this.id).remove(), n = this, this.board = document.createElement("div"), this.board.id = "widget_" + this.id, this.board.className = "widget " + this.className, this.board.style.position = "absolute", this.board.style.zIndex = this.zIndex, this.board.style.visibility = "hidden", this.desktop = _config.currentDesktop, document.getElementById("icosContainer_body_" + _config.currentDesktop).appendChild(this.board), _widget.clientHeight || (this.board.style.left = "100%", this.board.style.top = "100%", _widget.clientWidth = this.board.offsetLeft, _widget.clientHeight = this.board.offsetTop), f = ["LEFT_TOP", "TOP", "RIGHT_TOP", "RIGHT", "RIGHT_BOTTOM", "BOTTOM", "LEFT_BOTTOM", "LEFT", "LOGO"], t = 0; t < f.length; t++) r = document.createElement("div"),
	r.className = f[t],
	r.style.position = "absolute",
	_config.Permission("widget_OP", "icosContainer_body_" + this.desktop) ? (jQuery(r).bind("mousedown",
	function(t) {
		n.Mousedown(t ? t: window.event)
	}), jQuery(r).bind("mouseup",
	function(t) {
		n.Mouseup(t ? t: window.event)
	})) : jQuery(r).bind("mousedown",
	function() {
		n.Focus()
	}),
	this.board.appendChild(r); (_config.Permission("widget_OP", "icosContainer_body_" + this.desktop) || this.data.href) && (this.blank = document.createElement("div"), this.blank.id = "_widget_blank" + this.id, this.blank.className = "widget_blank", this.blank.style.cssText = "position:absolute;width:100%;height:100%;background:url("+_THEME_+"/desktop/images/b.gif);z-index:100", this.data.href && (this.blank.style.cursor = "pointer", jQuery(this.blank).bind("click",
	function() {
		return _widget.Open(n.data),
		!1
	})), _config.Permission("widget_OP", "icosContainer_body_" + this.desktop) && (jQuery(this.blank).bind("mousedown",
	function(t) {
		return n.Mousedown(t ? t: window.event),
		!1
	}), jQuery(this.blank).bind("mouseup",
	function(t) {
		return n.Mouseup(t ? t: window.event),
		!1
	})), this.board.appendChild(this.blank));
	if (_config.Permission("widget_OP", "icosContainer_body_" + this.desktop)) for (u = ["EDIT", "DELETE", "MOVE"], t = 0; t < u.length; t++) {
		i = document.createElement("a"),
		i.className = u[t] + " titlebar",
		i.title = _lang.widget[u[t]];
		switch (u[t]) {
		case "EDIT":
			jQuery(i).bind("click",
			function() {
				showWindow("addwidget", _config.ajaxurl + "&do=widget&container=icosContainer_body_" + n.desktop + "&uid=" + _config.uid + "&ukey=" + _config.ukey + "&gid=" + n.gid, "get", 0)
			});
			break;
		case "DELETE":
			jQuery(i).bind("click",
			function() {
				showWindow("deletewidget", _config.ajaxurl + "&do=widget_delete&container=icosContainer_body_" + n.desktop + "&uid=" + _config.uid + "&ukey=" + _config.ukey + "&gid=" + n.gid, "get", 0)
			});
			break;
		case "MOVE":
			jQuery(i).bind("mousedown",
			function(t) {
				return n.Mousedown(t ? t: window.event),
				jQuery(n.blank).css("z-index", 100),
				!1
			}),
			jQuery(i).bind("mouseup",
			function(t) {
				n.Mouseup(t ? t: window.event)
			})
		}
		this.board.appendChild(i)
	}
	this.contentCase = document.createElement("div"),
	this.contentCase.className = "CONTENT",
	this.board.appendChild(this.contentCase);
	switch (this.data.type) {
	case "image":
		this.contentCase.innerHTML = '<img src="' + encodeURI(this.data.url) + '" width="100%" height="100%" onload="fixpng(this)">',
		this.bodyHeight < 0 && (this.bodyHeight = jQuery(this.contentCase).height()),
		this.contentCase.style.overflow = "hidden";
		break;
	case "flash":
		this.data.url.substr(this.data.url.lastIndexOf(".")).toLowerCase() == ".swf" ? (this.contentCase.innerHTML = AC_FL_RunContent("width", "100%", "height", "100%", "allowNetworking", "internal", "allowScriptAccess", "never", "src", this.data.url, "quality", "high", "bgcolor", "", "wmode", "transparent", "allowfullscreen", "true"), this.contentCase.style.overflow = "hidden") : this.data.url.substr(this.data.url.lastIndexOf(".")).toLowerCase() == ".flv" && (this.contentCase.innerHTML = AC_FL_RunContent("width", "100%", "height", "100%", "allowNetworking", "internal", "allowScriptAccess", "never", "src", STATICURL + "image/common/flvplayer.swf", "flashvars", "file=" + encodeURI(this.data.url), "quality", "high", "bgcolor", "#ffffff", "wmode", "transparent", "allowfullscreen", "true")),
		this.bodyHeight < 0 && (this.bodyHeight = jQuery(this.contentCase).height()),
		this.contentCase.style.overflow = "hidden";
		break;
	case "link":
		o = {
			uid: _config.space.uid,
			ukey: _config.ukey,
			username: _config.space.username,
			password: _config.space.password,
			self: _config.space.self,
			winid: this.id,
			time: +new Date
		},
		e = encodeURIComponent(jQuery.toJSON(o)),
		jQuery(this.contentCase).append('<iframe frameborder="0"  id="' + this.id + 'widget_ifm0" name="' + e + '" marginheight="0" marginwidth="0" allowtransparency="true" src="' + this.data.url + '" class="appIframe"  style="overflow-x: hidden;"></iframe>'),
		this.contentCase.style.overflow = "hidden"
	}
	this.width = this.bodyWidth,
	this.height = this.bodyHeight,
	this.left = this.left ? parseInt(this.left) : parseInt((_widget.clientWidth - this.width) / 2),
	this.top = this.top ? parseInt(this.top) : parseInt((_widget.clientHeight - this.height) / 2),
	this.left += document.documentElement.scrollLeft,
	this.top += document.documentElement.scrollTop,
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px",
	this.height <= 0 && (this.height = 100),
	this.width <= 0 && (this.width = 100),
	this.board.style.height = this.height + "px",
	this.board.style.width = this.width + "px",
	this.board.style.visibility = "visible",
	jQuery(this.board).bind("mouseover",
	function() {
		jQuery(this).find(".titlebar").show()
	}),
	jQuery(this.board).bind("mouseout",
	function() {
		jQuery(this).find(".titlebar").hide()
	}),
	this.status = 1,
	n = this,
	this.save()
},
_widget.prototype.DetachEvent = function() {
	if (!_widget.tach) return;
	document.onmousemove = _widget.onmousemove,
	document.onmousemove = _widget.onmousemove,
	document.onmouseup = _widget.onmouseup,
	document.onselectstart = _widget.onselectstart,
	this.board.releaseCapture && this.board.releaseCapture(),
	_widget.tach = 0
},
_widget.prototype.AttachEvent = function(n) {
	if (_widget.tach) return;
	_widget.onmousemove = document.onmousemove,
	_widget.onmouseup = document.onmouseup,
	_widget.onselectstart = document.onselectstart,
	n.preventDefault ? n.preventDefault() : (document.onselectstart = function() {
		return ! 1
	},
	this.board.setCapture && this.board.setCapture()),
	_widget.tach = 1
},
_widget.prototype.Focus = function() {
	var t = this;
	return this.zIndex < _widget.zIndex && (this.board.style.zIndex = this.zIndex = ++_widget.zIndex),
	jQuery("#icosContainer_body_" + this.desktop + " .widget_blank").css("z-index", 100),
	!this.data.href && this.data.type != "image" && jQuery(this.blank).css("z-index", -1),
	!1
},
_widget.prototype.Createblank = function() {
	jQuery("#_blank").empty();
	var n = this;
	jQuery("#navbar").find(".navItem").each(function() {
		var i = this.id.replace("indicator_", ""),
		n = jQuery(this),
		t = n.offset(),
		r = jQuery('<div id="_shadow_indicator_' + i + '" style="position:absolute;width:' + n.outerWidth() + "px;height:" + n.outerHeight() + "px;left:" + t.left + "px;top:" + t.top + 'px;z-index:10;background: url('+_THEME_+'/desktop/images/b.gif);"></div>').appendTo("#_blank");
		i != _config.currentDesktop && r.hover(function() {
			var t = this.id.replace("_shadow_", ""),
			n = "indicator_current";
			t.indexOf("indicator_sys_") !== -1 && (n = "sysnav_current"),
			jQuery("#" + t).addClass(n)
		},
		function() {
			var t = this.id.replace("_shadow_", ""),
			n = "indicator_current";
			t.indexOf("indicator_sys_") !== -1 && (n = "sysnav_current"),
			jQuery("#" + t).removeClass(n)
		})
	}),
	document.getElementById("_blank").style.display = "block",
	jQuery("#navbar").css("z-index", 6e3),
	_widget.finishblank = 1
},
_widget.prototype.Mousedown = function(n) {
	var r, i, t;
	if (jQuery.browser.msie) {
		if (n.button > 1) return
	} else if (n.button > 0) return;
	this.mousedowndoing = !1,
	r = n.clientX,
	i = n.clientY,
	_widget.tach || this.AttachEvent(n),
	_widget.even = n,
	t = this,
	this.mousedownTimer = setTimeout(function() {
		t.PreMove(r, i)
	},
	200)
},
_widget.prototype.Mouseup = function(n) {
	if (jQuery.browser.msie) {
		if (n.button > 1) return
	} else if (n.button > 0) return;
	_widget.tach && this.DetachEvent(n),
	this.mousedowndoing ? this.Moved(n) : clearTimeout(this.mousedownTimer),
	this.Focus()
},
_widget.prototype.PreMove = function(n, t) {
	this.oldleft = this.left,
	this.oldtop = this.top,
	this.mousedowndoing = !0,
	this.Createblank(),
	this.moveX = n - this.left,
	this.moveY = t - this.top,
	document.body.style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_arrow.cur'),auto",
	_widget.tach || this.AttachEvent(_widget.even),
	eval("document.onmousemove=function(e){" + this.string + ".Move(e?e:window.event);};"),
	eval("document.onmouseup=function(e){" + this.string + ".Moved(e?e:window.event);};")
},
_widget.prototype.Move = function(n) {
	if (!_widget.tach) return;
	var i = n.clientX,
	t = n.clientY;
	i < 0 && (i = 0),
	t < 0 && (t = 0),
	i > _config.screenWidth && (i = _config.screenWidth),
	t > _config.screenHeight && (t = _config.screenHeight),
	this.move != "move-y" && (this.board.style.left = i - this.moveX + "px", this.left = i - this.moveX),
	this.move != "move-x" && (this.board.style.top = t - this.moveY + "px", this.top = t - this.moveY)
},
_widget.prototype.Moved = function(n) {
	var i, t, r, f;
	_widget.tach && this.DetachEvent(n),
	_widget.finishblank && (jQuery("#_blank").hide(), jQuery("#navbar").css("z-index", 1e3)),
	i = n.clientX,
	t = n.clientY,
	i < 0 && (i = 0),
	t < 0 && (t = 0),
	i > _config.screenWidth && (i = _config.screenWidth),
	t > _config.screenHeight && (t = _config.screenHeight);
	var e = i - this.moveX,
	o = t - this.moveY,
	s = n.srcElement ? n.srcElement: n.target,
	u = jQuery(s).attr("id");
	if (u.indexOf("_shadow_indicator_") !== -1) {
		r = u.replace("_shadow_indicator_", ""),
		f = _navbar.navbars[r];
		if (r == _config.currentDesktop) {
			this.reject();
			return
		}
		if (f.type != "desktop") {
			this.reject();
			return
		}
		if (r.indexOf("sys_") !== -1 && _config.space.self < 2) {
			this.reject();
			return
		}
		this.MoveTo(r)
	} else this.left = e,
	this.top = o,
	this.board.style.left = e + "px",
	this.board.style.top = o + "px",
	this.save()
},
_widget.prototype.MoveTo = function(n) {
	var i = jQuery(this.board).clone(!0),
	t;
	jQuery(this.board).remove();
	if (this.desktop.indexOf("sys_") === -1) {
		for (t in _config.screenList.screenlist_u[this.desktop].widgets[this.gid]) delete _config.screenList.screenlist_u[this.desktop].widgets[this.gid][t];
		delete _config.screenList.screenlist_u[this.desktop].widgets[this.gid]
	} else {
		for (t in _config.screenList.screenlist_0[this.desktop].widgets[this.gid]) delete _config.screenList.screenlist_0[this.desktop].widgets[this.gid][t];
		delete _config.screenList.screenlist_0[this.desktop].widgets[this.gid]
	}
	this.desktop = n,
	this.board = i.appendTo("#icosContainer_body_" + n).get(0),
	this.left = this.oldleft,
	this.top = this.oldtop,
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px",
	this.save()
},
_widget.prototype.reject = function() {
	jQuery("#_blank").empty().hide(),
	jQuery("#navbar").css("z-index", 1e3);
	var n = this;
	jQuery(this.board).animate({
		left: this.oldleft + "px",
		top: this.oldtop + "px"
	},
	_config.delay,
	function() {
		n.left = n.oldleft,
		n.top = n.oldtop
	})
},
_widget.prototype.save = function() {
	var n;
	n = this.desktop.indexOf("sys_") === -1 ? _config.screenList.screenlist_u[this.desktop].widgets || {}: _config.screenList.screenlist_0[this.desktop].widgets || {},
	n[this.gid] = {
		gid: this.gid,
		navid: this.desktop,
		left: this.left,
		top: this.top,
		zIndex: this.zIndex
	},
	this.desktop.indexOf("sys_") === -1 && _config.space.self > 0 ? (_config.screenList.screenlist_u[this.desktop].widgets = n, _config.saveItem.screenlist = 1) : this.desktop.indexOf("sys_") !== -1 && _config.space.self > 1 && (_config.screenList.screenlist_0[this.desktop].widgets = n, _config.saveItem.screenlist = 1)
},
_widget.taskWidget_run = function() {
	if (_config.taskWidget[_config.currentDesktop]) {
		for (var n = 0; n < _config.taskWidget[_config.currentDesktop].length; n++) _widget.OpenWidget(_config.taskWidget[_config.currentDesktop][n].gid, _config.taskWidget[_config.currentDesktop][n].navid, _config.taskWidget[_config.currentDesktop][n].left, _config.taskWidget[_config.currentDesktop][n].top, _config.taskWidget[_config.currentDesktop][n].zIndex);
		delete _config.taskWidget[_config.currentDesktop]
	}
},
_widget.setOldWidget = function() {
	var n, t, i;
	for (n in _config.screenList.screenlist_u) {
		_config.taskWidget[n] = [];
		for (t in _config.screenList.screenlist_u[n].widgets) i = _config.screenList.screenlist_u[n].widgets[t].zIndex % 100 + Math.round(_config.screenList.screenlist_u[n].widgets[t].zIndex / 100),
		_config.screenList.screenlist_u[n].widgets[t].zIndex = i,
		_config.taskWidget[n].push(_config.screenList.screenlist_u[n].widgets[t])
	}
	for (n in _config.screenList.screenlist_0) {
		_config.taskWidget[n] = [];
		for (t in _config.screenList.screenlist_0[n].widgets) i = _config.screenList.screenlist_0[n].widgets[t].zIndex % 100 + Math.round(_config.screenList.screenlist_0[n].widgets[t].zIndex / 100),
		_config.screenList.screenlist_0[n].widgets[t].zIndex = i,
		_config.taskWidget[n].push(_config.screenList.screenlist_0[n].widgets[t])
	}
},
_widget.OpenWidget = function(n, t, i, r, u) {
	var f = _widget.CWidget(n);
	f.left = i,
	f.top = r,
	f.zIndex = u,
	_widget.zIndex < u && (_widget.zIndex = u),
	f.Creat()
},
_widget.Delete = function(n) {
	var t = _widget.widgets["_G_" + n],
	i;
	if (t.desktop.indexOf("sys_") === -1 && _config.space.self > 0) {
		for (i in _config.screenList.screenlist_u[t.desktop].widgets[n]) delete _config.screenList.screenlist_u[t.desktop].widgets[n][i];
		delete _config.screenList.screenlist_u[t.desktop].widgets[n],
		_config.saveItem.screenlist = 1
	} else if (t.desktop.indexOf("sys_") !== -1 && _config.space.self > 1) {
		for (i in _config.screenList.screenlist_0[t.desktop].widgets[n]) delete _config.screenList.screenlist_0[t.desktop].widgets[n][i];
		delete _config.screenList.screenlist_0[t.desktop].widgets[n],
		_config.saveItem.screenlist = 1
	}
	jQuery("#widget__G_" + n).remove();
	for (i in t) delete t[i];
	delete t
},
_widget.Open = function(n) {
	switch (parseInt(n.open)) {
	case 0:
		OpenWindow("url", n.href, "");
		break;
	case 1:
		OpenWindow("url", n.href, "", "", "class=X_widget,titlebutton=close");
		break;
	case 2:
		OpenBrowser(n.href, n.href);
		break;
	case 3:
		window.open(n.href)
	}
},
_widget.prototype.Close = function() {
	showWindow("deletewidget", _config.ajaxurl + "&do=widget_delete&container=icosContainer_body_" + this.desktop + "&uid=" + _config.uid + "&ukey=" + _config.ukey + "&gid=" + this.gid, "get", 0)
},
_widget.prototype.ResizeTo = function(n, t) {
	this.bodyWidth = n,
	this.bodyHeight = t,
	this.board.style.width = this.bodyWidth + "px",
	this.board.style.height = this.bodyHeight + "px"
},
qq = qq || {},
qq.extend = function(n, t) {
	for (var i in t) n[i] = t[i]
},
qq.indexOf = function(n, t, i) {
	if (n.indexOf) return n.indexOf(t, i);
	i = i || 0;
	var r = n.length;
	for (i < 0 && (i += r); i < r; i++) if (i in n && n[i] === t) return i;
	return - 1
},
qq.getUniqueId = function() {
	var n = 0;
	return function() {
		return n++
	}
} (),
qq.attach = function(n, t, i) {
	n.addEventListener ? n.addEventListener(t, i, !1) : n.attachEvent && n.attachEvent("on" + t, i)
},
qq.detach = function(n, t, i) {
	n.removeEventListener ? n.removeEventListener(t, i, !1) : n.attachEvent && n.detachEvent("on" + t, i)
},
qq.preventDefault = function(n) {
	n.preventDefault ? n.preventDefault() : n.returnValue = !1
},
qq.stopPropagation = function(n) {
	n.stopPropagation ? n.stopPropagation(n) : window.event && (window.event.cancelBubble = !0)
},
qq.insertBefore = function(n, t) {
	t.parentNode.insertBefore(n, t)
},
qq.remove = function(n) {
	n.parentNode.removeChild(n)
},
qq.contains = function(n, t) {
	return n == t ? !0 : n.contains ? n.contains(t) : !!(t.compareDocumentPosition(n) & 8)
},
qq.toElement = function() {
	var n = document.createElement("div");
	return function(t) {
		n.innerHTML = t;
		var i = n.firstChild;
		return n.removeChild(i),
		i
	}
} (),
qq.css = function(n, t) {
	t.opacity != null && typeof n.style.opacity != "string" && typeof n.filters != "undefined" && (t.filter = "alpha(opacity=" + Math.round(100 * t.opacity) + ")"),
	qq.extend(n.style, t)
},
qq.hasClass = function(n, t) {
	var i = new RegExp("(^| )" + t + "( |$)");
	return i.test(n.className)
},
qq.addClass = function(n, t) {
	qq.hasClass(n, t) || (n.className += " " + t)
},
qq.removeClass = function(n, t) {
	var i = new RegExp("(^| )" + t + "( |$)");
	n.className = n.className.replace(i, " ").replace(/^\s+|\s+$/g, "")
},
qq.setText = function(n, t) {
	n.innerText = t,
	n.textContent = t
},
qq.children = function(n) {
	var i = [],
	t = n.firstChild;
	while (t) t.nodeType == 1 && i.push(t),
	t = t.nextSibling;
	return i
},
qq.getByClass = function(n, t) {
	var i;
	if (n.querySelectorAll) return n.querySelectorAll("." + t);
	var u = [],
	r = n.getElementsByTagName("*"),
	f = r.length;
	for (i = 0; i < f; i++) qq.hasClass(r[i], t) && u.push(r[i]);
	return u
},
qq.obj2url = function(n, t, i) {
	var u = [],
	e = "&",
	o = function(n, i) {
		var r = t ? /\[\]$/.test(t) ? t: t + "[" + i + "]": i;
		r != "undefined" && i != "undefined" && u.push(typeof n == "object" ? qq.obj2url(n, r, !0) : Object.prototype.toString.call(n) === "[object Function]" ? encodeURIComponent(r) + "=" + encodeURIComponent(n()) : encodeURIComponent(r) + "=" + encodeURIComponent(n))
	},
	f,
	r;
	if (!i && t) e = /\?/.test(t) ? /\?$/.test(t) ? "": "&": "?",
	u.push(t),
	u.push(qq.obj2url(n));
	else if (Object.prototype.toString.call(n) === "[object Array]" && typeof n != "undefined") for (r = 0, f = n.length; r < f; ++r) o(n[r], r);
	else if (typeof n != "undefined" && n !== null && typeof n == "object") for (r in n) o(n[r], r);
	else u.push(encodeURIComponent(t) + "=" + encodeURIComponent(n));
	return u.join(e).replace(/^&/, "").replace(/%20/g, "+")
},
qq = qq || {},
qq.FileUploaderBasic = function(n) {
	this._options = {
		debug: !1,
		action: "/server/upload",
		params: {},
		button: null,
		multiple: !0,
		maxConnections: 3,
		allowedExtensions: [],
		sizeLimit: 0,
		minSizeLimit: 0,
		onSubmit: function() {},
		onProgress: function() {},
		onComplete: function() {},
		onCancel: function() {},
		messages: {
			typeError: "{file} has invalid extension. Only {extensions} are allowed.",
			sizeError: "{file} is too large, maximum file size is {sizeLimit}.",
			minSizeError: "{file} is too small, minimum file size is {minSizeLimit}.",
			emptyError: "{file} is empty, please select files again without it.",
			onLeave: "The files are being uploaded, if you leave now the upload will be cancelled."
		},
		showMessage: function() {}
	},
	qq.extend(this._options, n),
	this._filesInProgress = 0,
	this._handler = this._createUploadHandler(),
	this._options.button && (this._button = this._createUploadButton(this._options.button)),
	this._preventLeaveInProgress()
},
qq.FileUploaderBasic.prototype = {
	setParams: function(n) {
		this._options.params = n
	},
	getInProgress: function() {
		return this._filesInProgress
	},
	_createUploadButton: function(n) {
		var t = this;
		return new qq.UploadButton({
			element: n,
			multiple: this._options.multiple && qq.UploadHandlerXhr.isSupported(),
			onChange: function(n) {
				t._onInputChange(n)
			}
		})
	},
	_createUploadHandler: function() {
		var n = this,
		t, i;
		return t = qq.UploadHandlerXhr.isSupported() ? "UploadHandlerXhr": "UploadHandlerForm",
		i = new qq[t]({
			debug: this._options.debug,
			action: this._options.action,
			maxConnections: this._options.maxConnections,
			onProgress: function(t, i, r, u) {
				n._onProgress(t, i, r, u);
				n._options.onProgress(t, i, r, u)
			},
			onComplete: function(t, i, r) {
				n._onComplete(t, i, r);
				n._options.onComplete(t, i, r)
			},
			onCancel: function(t, i) {
				n._onCancel(t, i);
				n._options.onCancel(t, i)
			}
		})
	},
	_preventLeaveInProgress: function() {
		var n = this;
		qq.attach(window, "beforeunload",
		function(t) {
			if (!n._filesInProgress) return;
			var t = t || window.event;
			return t.returnValue = n._options.messages.onLeave,
			n._options.messages.onLeave
		})
	},
	_onSubmit: function() {
		this._filesInProgress++
	},
	_onProgress: function() {},
	_onComplete: function(n, t, i) {
		this._filesInProgress--,
		i.error && this._options.showMessage(i.error)
	},
	_onCancel: function() {
		this._filesInProgress--
	},
	_onInputChange: function(n) {
		this._handler instanceof qq.UploadHandlerXhr ? this._uploadFileList(n.files) : this._validateFile(n) && this._uploadFile(n),
		this._button.reset()
	},
	_uploadFileList: function(n) {
		for (var t = 0; t < n.length; t++) if (!this._validateFile(n[t])) return;
		for (t = 0; t < n.length; t++) this._uploadFile(n[t])
	},
	_uploadFile: function(n) {
		var t = this._handler.add(n),
		i = this._handler.getName(t);
		this._options.onSubmit(t, i) !== !1 && (this._onSubmit(t, i), this._handler.upload(t, this._options.params))
	},
	_validateFile: function(n) {
		var t, i;
		n.value ? t = n.value.replace(/.*(\/|\\)/, "") : (t = n.fileName != null ? n.fileName: n.name, i = n.fileSize != null ? n.fileSize: n.size);
		if (this._isAllowedExtension(t)) {
			if (i === 0) return this._error("emptyError", t),
			!1;
			if (i && this._options.sizeLimit && i > this._options.sizeLimit) return this._error("sizeError", t),
			!1;
			if (i && i < this._options.minSizeLimit) return this._error("minSizeError", t),
			!1
		} else return this._error("typeError", t),
		!1;
		return ! 0
	},
	_error: function(n, t) {
		function i(n, t) {
			r = r.replace(n, t)
		}
		var r = this._options.messages[n];
		i("{file}", this._formatFileName(t)),
		i("{extensions}", this._options.allowedExtensions.join(", ")),
		i("{sizeLimit}", this._formatSize(this._options.sizeLimit)),
		i("{minSizeLimit}", this._formatSize(this._options.minSizeLimit)),
		this._options.showMessage(r)
	},
	_formatFileName: function(n) {
		return n.length > 33 && (n = n.slice(0, 19) + "..." + n.slice( - 13)),
		n
	},
	_isAllowedExtension: function(n) {
		var r = -1 !== n.indexOf(".") ? n.replace(/.*[.]/, "").toLowerCase() : "",
		i = this._options.allowedExtensions,
		t;
		if (!i.length) return ! 0;
		for (t = 0; t < i.length; t++) if (i[t].toLowerCase() == r) return ! 0;
		return ! 1
	},
	_formatSize: function(n) {
		var t = -1;
		do n = n / 1024,
		t++;
		while (n > 99);
		return Math.max(n, .1).toFixed(1) + (["kB", "MB", "GB", "TB", "PB", "EB"])[t]
	}
},
qq.FileUploader = function(n) {
	qq.FileUploaderBasic.apply(this, arguments),
	qq.extend(this._options, {
		element: null,
		listElement: null,
		template: '<div class="qq-uploader"><div class="qq-upload-drop-area"><span>Drop files here to upload</span></div><div class="qq-upload-button">Upload a file</div><ul class="qq-upload-list"></ul></div>',
		fileTemplate: '<li><span class="qq-upload-file"></span><span class="qq-upload-spinner"></span><span class="qq-upload-size"></span><a class="qq-upload-cancel" href="#">Cancel</a><span class="qq-upload-failed-text">Failed</span><span class="qq-upload-failed-text">Success</span></li>',
		classes: {
			button: "qq-upload-button",
			drop: "qq-upload-drop-area",
			dropActive: "qq-upload-drop-area-active",
			list: "qq-upload-list",
			file: "qq-upload-file",
			spinner: "qq-upload-spinner",
			size: "qq-upload-size",
			cancel: "qq-upload-cancel",
			success: "qq-upload-success",
			fail: "qq-upload-fail"
		}
	}),
	qq.extend(this._options, n),
	this._element = this._options.element,
	this._element.innerHTML = this._options.template,
	this._listElement = this._options.listElement || this._find(this._element, "list"),
	this._classes = this._options.classes,
	this._button = this._createUploadButton(this._find(this._element, "button")),
	this._bindCancelEvent(),
	this._setupDragDrop()
},
qq.extend(qq.FileUploader.prototype, qq.FileUploaderBasic.prototype),
qq.extend(qq.FileUploader.prototype, {
	_find: function(n, t) {
		var i = qq.getByClass(n, this._options.classes[t])[0];
		if (!i) throw new Error("element not found " + t);
		return i
	},
	_setupDragDrop: function() {
		var t = this,
		n = this._find(this._element, "drop"),
		i = new qq.UploadDropZone({
			element: n,
			onEnter: function(i) {
				qq.addClass(n, t._classes.dropActive),
				qq.stopPropagation(i)
			},
			onLeave: function(n) {
				qq.stopPropagation(n)
			},
			onLeaveNotDescendants: function() {
				qq.removeClass(n, t._classes.dropActive)
			},
			onDrop: function(i) {
				n.style.display = "none",
				qq.removeClass(n, t._classes.dropActive),
				t._uploadFileList(i.dataTransfer.files)
			}
		});
		n.style.display = "none",
		qq.attach(document, "dragenter",
		function(t) {
			if (!i._isValidFileDrag(t)) return;
			n.style.display = "block"
		}),
		qq.attach(document, "dragleave",
		function(t) {
			if (!i._isValidFileDrag(t)) return;
			var r = document.elementFromPoint(t.clientX, t.clientY); (!r || r.nodeName == "HTML") && (n.style.display = "none")
		})
	},
	_onSubmit: function(n, t) {
		qq.FileUploaderBasic.prototype._onSubmit.apply(this, arguments),
		this._addToList(n, t)
	},
	_onProgress: function(n, t, i, r) {
		var e, f, o, s, u;
		qq.FileUploaderBasic.prototype._onProgress.apply(this, arguments),
		e = this._getItemByFileId(n),
		f = this._find(e, "size"),
		f.style.display = "inline",
		o = this._find(e, "spinner"),
		s = Math.round(i / r * 100) + "%",
		o.style.width = s,
		u = i != r ? Math.round(i / r * 100) + "% from " + this._formatSize(r) : this._formatSize(r),
		qq.setText(f, u)
	},
	_onComplete: function(n, t, i) {
		qq.FileUploaderBasic.prototype._onComplete.apply(this, arguments);
		var r = this._getItemByFileId(n);
		qq.remove(this._find(r, "cancel")),
		qq.remove(this._find(r, "spinner")),
		i.success ? qq.addClass(r, this._classes.success) : qq.addClass(r, this._classes.fail)
	},
	_addToList: function(n, t) {
		var i = qq.toElement(this._options.fileTemplate),
		r;
		i.qqFileId = n,
		r = this._find(i, "file"),
		qq.setText(r, this._formatFileName(t)),
		this._find(i, "size").style.display = "none",
		this._listElement.appendChild(i)
	},
	_getItemByFileId: function(n) {
		var t = this._listElement.firstChild;
		while (t) {
			if (t.qqFileId == n) return t;
			t = t.nextSibling
		}
	},
	_bindCancelEvent: function() {
		var n = this,
		t = this._listElement;
		qq.attach(t, "click",
		function(t) {
			var r, i;
			t = t || window.event,
			r = t.target || t.srcElement,
			qq.hasClass(r, n._classes.cancel) && (qq.preventDefault(t), i = r.parentNode, n._handler.cancel(i.qqFileId), qq.remove(i))
		})
	}
}),
qq.UploadDropZone = function(n) {
	this._options = {
		element: null,
		onEnter: function() {},
		onLeave: function() {},
		onLeaveNotDescendants: function() {},
		onDrop: function() {},
		onDrop1: function() {}
	},
	qq.extend(this._options, n),
	this._element = this._options.element,
	this._disableDropOutside(),
	this._attachEvents()
},
qq.UploadDropZone.prototype = {
	_disableDropOutside: function() {
		qq.UploadDropZone.dropOutsideDisabled || (qq.attach(document, "dragover",
		function(n) {
			n.dataTransfer && (n.dataTransfer.dropEffect = "none", qq.preventDefault(n))
		}), qq.UploadDropZone.dropOutsideDisabled = !0)
	},
	_attachEvents: function() {
		var n = this;
		qq.attach(n._element, "dragover",
		function(n) {
			var t = n.dataTransfer.effectAllowed;
			n.dataTransfer.dropEffect = t == "move" || t == "linkMove" ? "move": "copy",
			qq.stopPropagation(n),
			qq.preventDefault(n)
		}),
		qq.attach(n._element, "dragenter",
		function(t) {
			n._options.onEnter(t)
		}),
		qq.attach(n._element, "dragleave",
		function(t) {
			n._options.onLeave(t);
			n._options.onLeaveNotDescendants(t)
		}),
		qq.attach(n._element, "drop",
		function(t) {
			if (n._isValidFileDrag(t)) {
				qq.preventDefault(t);
				n._options.onDrop(t)
			} else {
				qq.preventDefault(t);
				n._options.onDrop1(t)
			}
		})
	},
	_isValidFileDrag: function(n) {
		var t = n.dataTransfer,
		i = navigator.userAgent.indexOf("AppleWebKit") > -1;
		return t && t.effectAllowed != "none" && (t.files || !i && t.types.contains && t.types.contains("Files"))
	}
},
qq.UploadButton = function(n) {
	this._options = {
		element: null,
		multiple: !1,
		name: "file",
		onChange: function() {},
		hoverClass: "qq-upload-button-hover",
		focusClass: "qq-upload-button-focus"
	},
	qq.extend(this._options, n),
	this._element = this._options.element,
	qq.css(this._element, {
		position: "relative",
		overflow: "hidden",
		direction: "ltr"
	}),
	this._input = this._createInput()
},
qq.UploadButton.prototype = {
	getInput: function() {
		return this._input
	},
	reset: function() {
		this._input.parentNode && qq.remove(this._input),
		qq.removeClass(this._element, this._options.focusClass),
		this._input = this._createInput()
	},
	_createInput: function() {
		var n = document.createElement("input"),
		t;
		return this._options.multiple && n.setAttribute("multiple", "multiple"),
		n.setAttribute("type", "file"),
		n.setAttribute("name", this._options.name),
		qq.css(n, {
			position: "absolute",
			right: 0,
			top: 0,
			fontFamily: "Arial",
			fontSize: "118px",
			margin: 0,
			padding: 0,
			cursor: "pointer",
			opacity: 0
		}),
		this._element.appendChild(n),
		t = this,
		qq.attach(n, "change",
		function() {
			t._options.onChange(n)
		}),
		qq.attach(n, "mouseover",
		function() {
			qq.addClass(t._element, t._options.hoverClass)
		}),
		qq.attach(n, "mouseout",
		function() {
			qq.removeClass(t._element, t._options.hoverClass)
		}),
		qq.attach(n, "focus",
		function() {
			qq.addClass(t._element, t._options.focusClass)
		}),
		qq.attach(n, "blur",
		function() {
			qq.removeClass(t._element, t._options.focusClass)
		}),
		window.attachEvent && n.setAttribute("tabIndex", "-1"),
		n
	}
},
qq.UploadHandlerAbstract = function(n) {
	this._options = {
		debug: !1,
		action: "/upload.php",
		maxConnections: 999,
		onProgress: function() {},
		onComplete: function() {},
		onCancel: function() {}
	},
	qq.extend(this._options, n),
	this._queue = [],
	this._params = []
},
qq.UploadHandlerAbstract.prototype = {
	log: function(n) {
		this._options.debug && window.console && console.log("[uploader] " + n)
	},
	add: function() {},
	upload: function(n, t) {
		var r = this._queue.push(n),
		i = {};
		qq.extend(i, t),
		this._params[n] = i,
		r <= this._options.maxConnections && this._upload(n, this._params[n])
	},
	cancel: function(n) {
		this._cancel(n),
		this._dequeue(n)
	},
	cancelAll: function() {
		for (var n = 0; n < this._queue.length; n++) this._cancel(this._queue[n]);
		this._queue = []
	},
	getName: function() {},
	getSize: function() {},
	getQueue: function() {
		return this._queue
	},
	_upload: function() {},
	_cancel: function() {},
	_dequeue: function(n) {
		var r = qq.indexOf(this._queue, n),
		t,
		i;
		this._queue.splice(r, 1),
		t = this._options.maxConnections,
		this._queue.length >= t && r < t && (i = this._queue[t - 1], this._upload(i, this._params[i]))
	}
},
qq.UploadHandlerForm = function() {
	qq.UploadHandlerAbstract.apply(this, arguments),
	this._inputs = {}
},
qq.extend(qq.UploadHandlerForm.prototype, qq.UploadHandlerAbstract.prototype),
qq.extend(qq.UploadHandlerForm.prototype, {
	add: function(n) {
		n.setAttribute("name", "qqfile");
		var t = "qq-upload-handler-iframe" + qq.getUniqueId();
		return this._inputs[t] = n,
		n.parentNode && qq.remove(n),
		t
	},
	getName: function(n) {
		return this._inputs[n].value.replace(/.*(\/|\\)/, "")
	},
	_cancel: function(n) {
		this._options.onCancel(n, this.getName(n));
		delete this._inputs[n];
		var t = document.getElementById(n);
		t && (t.setAttribute("src", "javascript:false;"), qq.remove(t))
	},
	_upload: function(n, t) {
		var f = this._inputs[n],
		i;
		if (!f) throw new Error("file with passed id was not added, or already uploaded or cancelled");
		var e = this.getName(n),
		r = this._createIframe(n),
		u = this._createForm(r, t);
		return u.appendChild(f),
		i = this,
		this._attachLoadEvent(r,
		function() {
			i.log("iframe loaded");
			var t = i._getIframeContentJSON(r);
			i._options.onComplete(n, e, t);
			i._dequeue(n),
			delete i._inputs[n],
			setTimeout(function() {
				qq.remove(r)
			},
			1)
		}),
		u.submit(),
		qq.remove(u),
		n
	},
	_attachLoadEvent: function(n, t) {
		qq.attach(n, "load",
		function() {
			if (!n.parentNode) return;
			if (n.contentDocument && n.contentDocument.body && n.contentDocument.body.innerHTML == "false") return;
			t()
		})
	},
	_getIframeContentJSON: function(n) {
		var i = n.contentDocument ? n.contentDocument: n.contentWindow.document,
		t;
		this.log("converting iframe's innerHTML to JSON"),
		this.log("innerHTML = " + i.body.innerHTML);
		try {
			t = eval("(" + i.body.innerHTML + ")")
		} catch(r) {
			t = {}
		}
		return t
	},
	_createIframe: function(n) {
		var t = qq.toElement('<iframe src="javascript:false;" name="' + n + '" />');
		return t.setAttribute("id", n),
		t.style.display = "none",
		document.body.appendChild(t),
		t
	},
	_createForm: function(n, t) {
		var i = qq.toElement('<form method="post" enctype="multipart/form-data"></form>'),
		r = qq.obj2url(t, this._options.action);
		return i.setAttribute("action", r),
		i.setAttribute("target", n.name),
		i.style.display = "block",
		document.body.appendChild(i),
		i
	}
}),
qq.UploadHandlerXhr = function() {
	qq.UploadHandlerAbstract.apply(this, arguments),
	this._files = [],
	this._xhrs = [],
	this._loaded = []
},
qq.UploadHandlerXhr.isSupported = function() {
	var n = document.createElement("input");
	return n.type = "file",
	"multiple" in n && typeof File != "undefined" && typeof(new XMLHttpRequest).upload != "undefined"
},
qq.extend(qq.UploadHandlerXhr.prototype, qq.UploadHandlerAbstract.prototype),
qq.extend(qq.UploadHandlerXhr.prototype, {
	add: function(n) {
		if (! (n instanceof File)) throw new Error("Passed obj in not a File (in qq.UploadHandlerXhr)");
		return this._files.push(n) - 1
	},
	getName: function(n) {
		var t = this._files[n];
		return t.fileName != null ? t.fileName: t.name
	},
	getSize: function(n) {
		var t = this._files[n];
		return t.fileSize != null ? t.fileSize: t.size
	},
	getLoaded: function(n) {
		return this._loaded[n] || 0
	},
	_upload: function(n, t) {
		var e = this._files[n],
		u = this.getName(n),
		o = this.getSize(n),
		i,
		r,
		f;
		this._loaded[n] = 0,
		i = this._xhrs[n] = new XMLHttpRequest,
		r = this,
		i.upload.onprogress = function(t) {
			if (t.lengthComputable) {
				r._loaded[n] = t.loaded;
				r._options.onProgress(n, u, t.loaded, t.total)
			}
		},
		i.onreadystatechange = function() {
			i.readyState == 4 && r._onComplete(n, i)
		},
		t = t || {},
		t.qqfile = u,
		f = qq.obj2url(t, this._options.action),
		i.open("POST", f, !0),
		i.setRequestHeader("X-Requested-With", "XMLHttpRequest"),
		i.setRequestHeader("X-File-Name", encodeURIComponent(u)),
		i.setRequestHeader("Content-Type", "application/octet-stream"),
		i.send(e)
	},
	_onComplete: function(n, t) {
		var i, u, r;
		if (!this._files[n]) return;
		i = this.getName(n),
		u = this.getSize(n);
		this._options.onProgress(n, i, u, u);
		if (t.status == 200) {
			this.log("xhr - server response received"),
			this.log("responseText = " + t.responseText);
			try {
				r = eval("(" + t.responseText + ")")
			} catch(f) {
				r = {}
			}
			this._options.onComplete(n, i, r)
		} else this._options.onComplete(n, i, {});
		this._files[n] = null,
		this._xhrs[n] = null,
		this._dequeue(n)
	},
	_cancel: function(n) {
		this._options.onCancel(n, this.getName(n));
		this._files[n] = null,
		this._xhrs[n] && (this._xhrs[n].abort(), this._xhrs[n] = null)
	}
}),
_window.OpenFile = function(n, t, i, r, u) {
	var f = new _window(u, "openfile");
	return f.CreatOpenFile(n, r, t, i, u),
	f
},
_window.prototype.CreatOpenFile = function(n, t, i, r) {
	var o, s, h, e, f;
	this.board = document.createElement("div"),
	this.board.className = "openfile",
	this.board.style.position = "absolute",
	this.board.style.zIndex = _window.zIndex,
	this.board.style.visibility = "hidden",
	jQuery("#MsgContainer").empty().show(),
	document.getElementById("MsgContainer").appendChild(this.board),
	this.isModal && (this.modal = document.createElement("div"), this.modal.className = "MODAL", this.modal.style.zIndex = _window.zIndex - 1, document.getElementById("MsgContainer").appendChild(this.modal)),
	_window.clientHeight || (this.board.style.left = "100%", this.board.style.top = "100%", _window.clientWidth = this.board.offsetLeft, _window.clientHeight = this.board.offsetTop),
	o = ["LEFT_TOP", "TOP", "RIGHT_TOP", "RIGHT", "RIGHT_BOTTOM", "BOTTOM", "LEFT_BOTTOM", "LEFT"],
	this.sides = [],
	o[o.length] = "TITLE",
	o[o.length] = "CONTENT",
	s = this;
	if (this.resize != "no") for (h = this.resize.split("|"), e = 0; e < h.length; e++) o[o.length] = h[e];
	for (e = 0; e < o.length; e++) {
		f = document.createElement("div"),
		f.className = o[e],
		f.style.position = "absolute",
		f.innerHTML = '<div class="' + o[e] + '_inner inner" style="position:absolute;"></div>',
		this.board.appendChild(f);
		switch (o[e]) {
		case "CONTENT":
			this.contentCase = f,
			f.style.width = this.bodyWidth + "px",
			this.bodyHeight > 0 && (f.style.height = this.bodyHeight + "px"),
			this.SetContent_OpenFile(n, i, r),
			f.style.left = this.sides[7].width + "px",
			f.style.top = this.sides[1].height + "px",
			this.width = this.bodyWidth + this.sides[3].width + this.sides[7].width,
			this.height = this.bodyHeight + this.sides[1].height + this.sides[5].height,
			this.minWidth = 500,
			this.minHeight = 400,
			this.board.style.height = this.height + "px",
			this.board.style.width = this.width + "px";
			break;
		case "TITLE":
			this.titleCase = f,
			f.style.height = f.offsetHeight + "px",
			f.style.width = this.bodyWidth + "px",
			this.SetOpenFileTitle(t),
			this.moveable && (jQuery(this.titleCase).bind("mousedown",
			function(n) {
				s.Mousedown(n ? n: window.event)
			}), jQuery(this.titleCase).bind("mouseup",
			function(n) {
				s.Mouseup(n ? n: window.event)
			}));
			break;
		case "RESIZE":
			f.style.cursor = "url('"+_THEME_+"/desktop/images/cur/aero_nwse.cur'),auto",
			jQuery(f).bind("mousedown",
			function(n) {
				s.resize = "yes",
				s.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-X":
			this.resizexCase = f,
			f.style.cursor = "e-resize",
			jQuery(f).bind("mousedown",
			function(n) {
				s.resize = "resize-x",
				s.PreResize(n ? n: window.event)
			});
			break;
		case "RESIZE-Y":
			this.resizeyCase = f,
			f.style.cursor = "s-resize",
			jQuery(f).bind("mousedown",
			function(n) {
				s.resize = "resize-y",
				s.PreResize(n ? n: window.event)
			});
			break;
		default:
			this.sides[e] = f,
			this.sides[e].width = f.offsetWidth,
			this.sides[e].height = f.offsetHeight,
			this.moveable && (f.style.cursor = "move", jQuery(f).bind("mousedown",
			function(n) {
				s.Mousedown(n ? n: window.event)
			}), jQuery(f).bind("mouseup",
			function(n) {
				s.Mouseup(n ? n: window.event)
			}))
		}
	}
	this.sides[1].dx = this.sides[0].width + this.sides[2].width,
	this.width > this.sides[1].dx && (this.sides[1].style.width = this.width - this.sides[1].dx + "px"),
	this.sides[3].dy = this.sides[2].height + this.sides[4].height,
	this.height > this.sides[3].dy && (this.sides[3].style.height = this.height - this.sides[3].dy + "px"),
	this.sides[5].dx = this.sides[4].width + this.sides[6].width,
	this.width > this.sides[5].dx && (this.sides[5].style.width = this.width - this.sides[5].dx + "px"),
	this.sides[7].dy = this.sides[6].height + this.sides[0].height,
	this.height > this.sides[7].dy && (this.sides[7].style.height = this.height - this.sides[7].dy + "px"),
	this.sides[0].style.left = "0px",
	this.sides[0].style.top = "0px",
	this.sides[1].style.left = this.sides[0].width + "px",
	this.sides[1].style.top = "0px",
	this.sides[2].style.right = "0px",
	this.sides[2].style.top = "0px",
	this.sides[3].style.right = "0px",
	this.sides[3].style.top = this.sides[2].height + "px",
	this.sides[4].style.right = "0px",
	this.sides[4].style.bottom = "0px",
	this.sides[5].style.left = this.sides[6].width + "px",
	this.sides[5].style.bottom = "0px",
	this.sides[6].style.left = "0px",
	this.sides[6].style.bottom = "0px",
	this.sides[7].style.left = "0px",
	this.sides[7].style.top = this.sides[0].height + "px",
	this.left = parseInt((_window.clientWidth - this.width) / 2),
	this.top = parseInt((_window.clientHeight - this.height) / 2),
	this.left < 0 && (this.left = 0),
	this.top < 0 && (this.top = 0),
	this.board.style.left = this.left + "px",
	this.board.style.top = this.top + "px",
	this.board.style.visibility = "visible",
	this.status = 1
},
_window.prototype.SetOpenFileTitle = function(n) {
	var c, y, f, s, u, e, v, t, h, i, o;
	jQuery(this.titleCase).empty();
	var a = document.createDocumentFragment(),
	r = this,
	p = 0,
	l = document.createElement("div");
	for (l.className = "titleBar", a.appendChild(l), c = document.createElement("div"), c.className = "titleButtonBar", l.appendChild(c), y = this.titleButton.split("|"), t = 0; t < _config.titleButtons.length; t++) i = document.createElement("a"),
	i.className = _config.titleButtons[t],
	i.setAttribute("bname", _config.titleButtons[t]),
	i.title = _lang.titleButton[_config.titleButtons[t]],
	i.style.display = in_array(_config.titleButtons[t], y) ? "block": "none",
	jQuery(i).bind("click",
	function() {
		switch (this.className) {
		case "CLOSE":
			r.Close();
			break;
		case "MAX":
			r.Max();
			break;
		case "RESTORE":
			r.Restore();
			break;
		case "MIN":
			r.Min()
		}
		return ! 1
	}),
	c.appendChild(i);
	for (f = document.createElement("div"), f.className = "titleText", f.id = "title_text_" + this.id, f.innerHTML = n || "dzz.cc", l.appendChild(f), s = document.createElement("div"), s.className = "folderBar", a.appendChild(s), u = document.createElement("div"), u.className = "folderButtonBar", s.appendChild(u), e = "", e += "<a id='seq_back_" + this.id + "' title='" + _lang.window.back + "' class='BACK1' onclick=\"_file.setcseq('back')\"></a>", e += "<a id='seq_next_" + this.id + "' title='" + _lang.window.next + "' class='NEXT1' onclick=\"_file.setcseq('next')\"></a>", v = "", t = 0; t < 5; t++) h = "",
	h = _file.view == t ? "filemanage_view" + t + "_2": "filemanage_view" + t + "_1",
	v += '<a id="' + this.id + "_view" + t + '" index="filemanage_view' + t + '_" iconview="' + t + '" title="' + _lang.filemanage_view[t] + '" class="arrange ' + h + '"></a>';
	e += v,
	u.innerHTML = e,
	jQuery(u).find(".arrange").bind("click",
	function() {
		return _file.view = parseInt(jQuery(this).attr("iconview")),
		_file.showIcos(document.getElementById("searchInput_" + r.id).value),
		jQuery(r.titleCase).find(".arrange").each(function() {
			jQuery(this).removeClass(jQuery(this).attr("index") + "2").addClass(jQuery(this).attr("index") + "1")
		}),
		jQuery(this).removeClass(jQuery(this).attr("index") + "1").addClass(jQuery(this).attr("index") + "2"),
		!1
	}),
	i = document.createElement("a"),
	i.title = _lang.filemanage_paixu,
	i.id = "filemanage_disp" + this.id,
	i.className = "filemanage_disp1",
	jQuery(i).bind("click",
	function(n) {
		_contextmenu.paixu_down_file(n ? n: window.event, r.id)
	}),
	u.appendChild(i),
	o = document.createElement("div"),
	o.className = "filemanage_search",
	o.innerHTML = '<div class="searchInputContainer"><input onkeydown="if(event.keyCode==13){_file.searchsubmit(\'' + this.id + "');}\"  onblur=\"if(this.value==''){this.value='" + _lang.fsearch + "';}\" onfocus=\"if(this.value=='" + _lang.fsearch + "'){this.value='';}else{this.select();}\" value=\"" + _lang.fsearch + '" id="searchInput_' + this.id + '" class="searchInput"><a onclick="_file.searchsubmit(\'' + this.id + '\');return false;" href="javascript:void(0);return false;" class="searchBtn" id="searchBtn_' + this.id + '"></a></div>',
	u.appendChild(o),
	this.titleCase.appendChild(a)
},
_window.prototype.SetContent_OpenFile = function(n, t, i) {
	var a = this,
	s = document.createElement("div"),
	f,
	c,
	h,
	e,
	u,
	r,
	l,
	o;
	s.id = "openfile_left",
	s.className = "openfile-left",
	this.contentCase.appendChild(s),
	jQuery('<div id="left_inner" class="left-inner"><div id="left_content" class="left-content"></div></div>').appendTo(s),
	f = "",
	c = 0;
	for (h in _config.screenList.screenlist_u) f += c == 0 ? '<div id="d-' + h + "-" + _config.space.uid + '" class="left-item first current">': '<div id="d-' + h + "-" + _config.space.uid + '" class="left-item">',
	c++,
	f += '<div class="left-item-img"><img src="'+_THEME_+'/desktop/images/default/desktop.png"></div>',
	f += '<div class="left-item-text">' + _config.screenList.screenlist_u[h].config.navname + "</div>",
	f += "</div>";
	document.getElementById("left_content").innerHTML = f;
	jQuery("#left_content .left-item").on("click",
	function() {
		_file.getdata(this.id),
		jQuery(this).removeClass("hover")
	});
	jQuery("#left_content .left-item").hover(function() {
		if (jQuery(this).hasClass("current")) return;
		jQuery(this).addClass("hover")
	},
	function() {
		jQuery(this).removeClass("hover")
	}),
	e = document.createElement("div"),
	e.id = "content_right",
	e.className = "openfile-right",
	this.contentCase.appendChild(e),
	u = document.createElement("div"),
	u.id = "right_bottom",
	u.style.bottom = "0px",
	u.style.cssText = "position:absolute;bottom:0px;left:0px;width:" + (this.bodyWidth - jQuery(s).width()) + "px;",
	u.className = "right-bottom",
	r = "",
	r += '<table width="100%" height="100%" cellpadding="0"  cellspacing="0">',
	r += '<tr height="30px">',
	r += '<td align="left" width="80">' + _lang.OpenFile.filename + "</td>",
	r += '<td align="center" ><input id="file_select_input" class="file-select-input" type="text" style="width:100%"></td>',
	r += '<td align="center"  width="80"><div id="file_OK" class="file-ok">' + _lang.OpenFile["ok_" + n] + "</div></td>",
	r += "</tr>",
	r += '<tr height="30px">',
	r += '<td align="left" width="80">' + _lang.OpenFile.filetype + "</td>";
	if (t) r += t == "video" ? '<td align="center" ><input  class="file-type-input" value="' + _lang.OpenFile[t] + '" type="text" style="width:100%"></td>': '<td align="center" ><input  class="file-type-input" value="' + t + '" type="text" style="width:100%"></td>';
	else {
		r += '<td align="center" >',
		r += '<select id="file_type_select" class="file-type-input" style="width:100%" onchange="_file.file_type_select_change(this.value);">';
		for (l in _lang.OpenFile.Filetype) r += '<option value="' + l + '">' + _lang.OpenFile.Filetype[l][0] + "</option>";
		r += "</select></td>"
	}
	r += '<td align="center"  width="80"><div id="file_CANCEL" class="file-cancel">' + _lang.OpenFile.cancel + "</div></td>",
	r += "</tr>",
	(n == "save" || n == "saveto") && (r += '<tr height="30px">', r += '<td align="left" width="80">' + _lang.OpenFile.fileEncode + "</td>", r += '<td align="center" >', r += '<select id="file_code_select" class="file-code-input" style="width:100%">', r += i.fileencode == "UTF-8" ? '<option value="UTF-8" selected="selected">UTF-8</option>': '<option value="UTF-8">UTF-8</option>', r += i.fileencode == "EUC-CN" || i.fileencode == "GB2312" ? '<option value="GBK" selected="selected">ANSI</option>': '<option value="GBK">ANSI</option>', r += i.fileencode == "BIG-5" || i.fileencode == "BIG5" ? '<option value="BIG5" selected="selected">BIG-5</option>': '<option value="BIG5">BIG-5</option>', r += "</select></td>", r += '<td align="center"  width="80">&nbsp;</td>', r += "</tr>"),
	r += "</table>",
	u.innerHTML = r,
	e.appendChild(u),
	a = this;
	jQuery("#file_OK").on("click",
	function() {
		eval(a.string + ".OnOK()")
	});
	jQuery("#file_CANCEL").on("click",
	function() {
		eval(a.string + ".OnCANCEL()")
	});
	o = document.createElement("div"),
	o.id = "right_top",
	o.className = "right-top",
	o.style.cssText = "position:relative;left:0px;width:" + (this.bodyWidth - jQuery(s).width()) + "px;height:" + (this.bodyHeight - jQuery(u).height()) + "px;",
	o.innerHTML = '<div class="right-top-inner" style="position:absolute"><div id="right_list" class="right-list"></div></div>',
	e.appendChild(o),
	_file.init(n, t, this.id)
},
_file = {},
_file.data = {},
_file.init = function(n, t, i) {
	_file.type = n,
	_file.exts = t ? t.split(",") : [],
	_file.view = 1,
	_file.disp = 0,
	_file.asc = 1,
	_file.winid = i,
	_file.detailper = [53, 15, 20, 17],
	_file.filepath = [],
	_file.Cseq = 0;
	var r = 1;
	parseInt(_config.currentDesktop) > 0 && (r = _config.currentDesktop),
	_file.getdata("d-" + r + "-" + _config.space.uid)
},
_file.file_type_select_change = function(n) {
	var t = document.getElementById("file_select_input").value;
	t && n != "All" && (t.lastIndexOf(".") !== -1 && (t = t.substr(0, t.lastIndexOf("."))), t = t + "." + n, document.getElementById("file_select_input").value = t),
	_file.showIcos(document.getElementById("searchInput__W_openfile").value, n)
},
_file.Search = function(n, t) {
	var r = {},
	i;
	for (i in n) n[i].name.toLowerCase().indexOf(t.toLowerCase()) !== -1 && (r[i] = n[i]);
	return r
},
_file.Searchext = function(n, t) {
	var f = _lang.OpenFile.Filetype[t][1],
	r = {},
	i,
	u;
	for (i in n) u = n[i].ext.toUpperCase(),
	(n[i].type == "folder" || jQuery.inArray(u, f) > -1) && (r[i] = n[i]);
	return r
},
_file.Disp = function(n) {
	var t = document.getElementById("searchInput_" + _file.winid).value;
	t == _lang.fsearch && (t = ""),
	_file.disp = parseInt(n),
	_file.showIcos(t),
	jQuery("#right_contextmenu .menu-icon-disp").each(function() {
		this.src = jQuery(this).attr("disp") == n ? _THEME_+"/desktop/images/icons/select.png": _THEME_+"/desktop/images/icons/notselect.png"
	}),
	jQuery("#right_contextmenu .menu-icon-disp").each(function() {
		this.src = jQuery(this).attr("disp") == n ? _THEME_+"/desktop/images/icons/select.png": _THEME_+"/desktop/images/icons/notselect.png"
	})
},
_file.searchsubmit = function(n) {
	var t = document.getElementById("searchInput_" + n).value;
	t == _lang.fsearch && (t = ""),
	_file.showIcos(t, document.getElementById("file_type_select").value)
},
_file.getdata = function(n) {
	jQuery("#left_content .left-item").removeClass("current"),
	jQuery("#" + n).addClass("current"),
	_file.id = n,
	jQuery.inArray(n, _file.filepath) > -1 ? (_file.filepath.splice(jQuery.inArray(n, _file.filepath), 1), _file.Cseq = _file.filepath.length > 0 ? _file.filepath.length - 1 : 0, _file.showIcos()) : jQuery.getJSON(_config.systemurl + "&op=file&id=" + n + "&uid=" + _config.space.uid + "&ukey=" + _config.ukey + "&t=" + +new Date,
	function(t) {
		var r, i;
		if (t.msg) alert(t.msg);
		else {
			r = {};
			for (i in t) jQuery.inArray("video", _file.exts) > -1 && t[i].type == "video" && (r[i] = t[i], _config.sourcedata.icos[i] = t[i]),
			t[i].type == "folder" && (r[i] = t[i], _config.sourcedata.icos[i] = t[i]),
			_file.exts.length ? t[i].ext && jQuery.inArray(t[i].ext, _file.exts) > -1 && (r[i] = t[i], _config.sourcedata.icos[i] = t[i]) : t[i].type == "attach" && (r[i] = t[i], _config.sourcedata.icos[i] = t[i]);
			_file.data[n] = r,
			_file.Cseq = _file.filepath.push(n) - 1,
			_file.showIcos()
		}
	}),
	_file.Cseq < 1 ? jQuery("#seq_back_" + _file.winid).removeClass("BACK2").addClass("BACK1") : jQuery("#seq_back_" + _file.winid).removeClass("BACK1").addClass("BACK2"),
	_file.Cseq < _file.filepath.length - 1 ? jQuery("#seq_next_" + _file.winid).removeClass("NEXT1").addClass("NEXT2") : jQuery("#seq_next_" + _file.winid).removeClass("NEXT2").addClass("NEXT1")
},
_file.setcseq = function(n) {
	if (_file.Cseq < 1 && n == "back" || _file.Cseq >= _file.filepath.length - 1 && n == "next") return;
	n == "back" ? _file.Cseq -= 1 : _file.Cseq += 1,
	_file.id = _file.filepath[_file.Cseq],
	jQuery("#left_content .left-item").removeClass("current"),
	jQuery("#" + _file.id).addClass("current"),
	_file.showIcos(),
	_file.Cseq < 1 ? jQuery("#seq_back_" + _file.winid).removeClass("BACK2").addClass("BACK1") : jQuery("#seq_back_" + _file.winid).removeClass("BACK1").addClass("BACK2"),
	_file.Cseq < _file.filepath.length - 1 ? jQuery("#seq_next_" + _file.winid).removeClass("NEXT1").addClass("NEXT2") : jQuery("#seq_next_" + _file.winid).removeClass("NEXT2").addClass("NEXT1")
},
_file.showIcos = function(n, t) {
	var s, r, c, a, i, l, u, v, o, f, e, y, h;
	document.getElementById("file_type_select") ? t || (t = document.getElementById("file_type_select").value || "All") : t = "All",
	containerid = "right_list",
	jQuery("#file_OK").html(_lang.OpenFile["ok_" + _file.type]);
	jQuery("#file_OK").off("click").on("click",
	function() {
		_window.windows._W_openfile.OnOK()
	});
	jQuery("#file_CANCEL").off("click").on("click",
	function() {
		_window.windows._W_openfile.OnCANCEL()
	});
	s = jQuery("#" + containerid).empty(),
	r = Sort(_file.data[_file.id], _file.disp, _file.asc),
	n && n != _lang.fsearch ? r = _file.Search(r, n) : document.getElementById("searchInput_" + _file.winid).value = _lang.fsearch,
	t != "All" && (r = _file.Searchext(r, t));
	if (_file.view < 4) {
		for (f in r) c = r[f].img.substr(r[f].img.length - 4) == ".png" ? !0 : !1,
		a = get_ico_template(_file.view, r[f].type),
		o = a.replace(/\{name\}/g, r[f].name),
		o = o.replace(/\{icoid\}/g, r[f].icoid),
		o = o.replace(/\{ispng\}/g, c),
		o = o.replace(/\{img\}/g, r[f].img),
		h = jQuery('<div icoid="' + r[f].icoid + '" class="file-icoitem">' + o + "</div>").appendTo(s),
		h.bind("mouseover",
		function() {
			return jQuery(this).addClass("border_background"),
			this.style.background = _ico._defaultbgcolor,
			!1
		}),
		h.bind("mouseout",
		function() {
			return jQuery(this).removeClass("border_background"),
			this.style.background = "",
			!1
		}),
		h.bind("click",
		function(n) {
			var i, t;
			n = n ? n: window.event,
			i = n.srcElement ? n.srcElement: n.target;
			if (i.type == "text" || i.type == "textarea") return ! 0;
			jQuery("#right_list .file-icoitem").each(function() {
				jQuery(this).removeClass("icoitem-selected")
			}),
			jQuery(this).addClass("icoitem-selected"),
			t = jQuery(this).attr("icoid"),
			_file.selected = t;
			if (r["icos_" + t].type == "folder") {
				jQuery("#file_OK").html(_lang.OpenFile.ok_open);
				jQuery("#file_OK").off("click").on("click",
				function() {
					_file.getdata("f-" + r["icos_" + t].oid + "-" + _config.space.uid)
				})
			} else {
				document.getElementById("file_select_input").value = r["icos_" + t].name,
				jQuery("#file_OK").html(_lang.OpenFile["ok_" + _file.type]);
				jQuery("#file_OK").off("click").on("click",
				function() {
					_window.windows._W_openfile.OnOK()
				})
			}
		}),
		h.bind("dblclick",
		function(n) {
			var i, t;
			n = n ? n: window.event,
			i = n.srcElement ? n.srcElement: n.target;
			if (i.type == "text" || i.type == "textarea") return ! 0;
			t = jQuery(this).attr("icoid"),
			_file.selected = t,
			r["icos_" + t].type == "folder" ? _file.getdata("f-" + r["icos_" + t].oid + "-" + _config.space.uid) : (document.getElementById("file_select_input").value = r["icos_" + t].name, _window.windows._W_openfile.OnOK())
		});
		s.css("overflow", "auto")
	} else {
		i = "",
		i += '<table width="100%" border="0"   style="table-layout:fixed;overflow:hidden">',
		i += '<tr class="detail_header_tr">',
		i += '<td disp="0" class=" detail_header detail_header_0 ' + (_file.disp == 0 ? "detail_header_hover": "") + '" width="' + _file.detailper[0] + '%">',
		i += '<div class="detail_header_td_div">',
		i += '<span class="detail_header_title">' + _lang.detail.name + "</span>",
		i += '<div disp="0"  class="detail_header_drag" ></div>',
		_file.disp == 0 && (i += '<a class="detail_header_asc detail_header_asc_' + _file.asc + '" style="display:inline-block;" ></a>'),
		i += "</div>",
		i += "</td>",
		i += '<td disp="1" class=" detail_header detail_header_1 ' + (_file.disp == 1 ? "detail_header_hover": "") + '"  width="' + _file.detailper[1] + '%">',
		i += '<div class="detail_header_td_div">',
		i += '<span class="detail_header_title">' + _lang.detail.size + "</span>",
		_file.disp == 1 && (i += '<a class="detail_header_asc detail_header_asc_' + _file.asc + '" style="display:inline-block;" ></a>'),
		i += '<div disp="1"  class="detail_header_drag" ></div>',
		i += "</div>",
		i += "</td>",
		i += '<td disp="2" class=" detail_header detail_header_2 ' + (_file.disp == 2 ? "detail_header_hover": "") + '"  width="' + _file.detailper[2] + '%">',
		i += '<div class="detail_header_td_div">',
		i += '<span class="detail_header_title">' + _lang.detail.type + "</span>",
		_file.disp == 2 && (i += '<a class="detail_header_asc detail_header_asc_' + _file.asc + '" style="display:inline-block;" ></a>'),
		i += '<div disp="2"  class="detail_header_drag" style="float:right" ></div>',
		i += "</div>",
		i += "</td>",
		i += '<td disp="3" class=" detail_header detail_header_3 ' + (_file.disp == 3 ? "detail_header_hover": "") + '"  width="' + _file.detailper[3] + '%">',
		i += '<div class="detail_header_td_div">',
		i += '<span class="detail_header_title">' + _lang.detail.dateline + "</span>",
		_file.disp == 3 && (i += '<a class="detail_header_asc detail_header_asc_' + _file.asc + '" style="display:inline-block;" ></a>'),
		i += "</div>",
		i += "</td>",
		i += "</tr>",
		i += "</table>",
		l = "",
		l += '<table width="100%" border="0"   style="table-layout:fixed;overflow:hidden">',
		u = "",
		u += '<tr class="detail_tr" icoid="{icoid}">',
		u += '<td class="detail_item_td "  valign="middle" width="' + _file.detailper[0] + '%" style="overflow:hidden">',
		u += '<div class="detail_item_td_div detail_item_td_name" icoid="{icoid}">',
		u += '<img class="detail_item_name_icon" src="{img}">',
		u += '<span id="file_text_{icoid}" class="detail_text detail_item_name_text">{name}</span>',
		u += "</div>",
		u += "</td>",
		u += '<td class="detail_item_td detail_item_td_size" valign="middle" width="' + _file.detailper[1] + '%" style="overflow:hidden">',
		u += '<div class="detail_item_td_div">',
		u += '<div class="detail_text detail_item_size_text">{size}</div>',
		u += "</div>",
		u += "</td>",
		u += '<td class="detail_item_td detail_item_td_type" valign="middle" width="' + _file.detailper[2] + '%" style="overflow:hidden">',
		u += '<div class="detail_item_td_div">',
		u += '<div class="detail_text detail_item_size_type">{type}</div>',
		u += "</div>",
		u += "</td>",
		u += '<td class="detail_item_td detail_item_td_type" valign="middle" width="' + _file.detailper[3] + '%" style="overflow:hidden">',
		u += '<div class="detail_item_td_div">',
		u += '<div class="detail_text detail_item_size_type">{dateline}</div>',
		u += "</div>",
		u += "</td>",
		u += "</tr>",
		v = "</table>",
		o = "";
		for (f in r) e = u.replace(/\{name\}/g, r[f].name),
		e = e.replace(/\{icoid\}/g, r[f].icoid),
		e = e.replace(/\{img\}/g, r[f].img),
		e = e.replace(/\{size\}/g, r[f].fsize),
		e = e.replace(/\{type\}/g, r[f].ftype),
		e = e.replace(/\{dateline\}/g, r[f].fdateline),
		o += e;
		y = jQuery('<div class="filemanage_header ">' + i + "</div>").appendTo(s),
		h = jQuery('<div id="detail_' + containerid + '" style="height:auto;overflow:hidden">' + l + o + v + "</div>").appendTo(s),
		s.find(".detail_tr").hover(function() {
			return jQuery(this).addClass("detail_tr_hover"),
			!1
		},
		function() {
			return jQuery(this).removeClass("detail_tr_hover"),
			!1
		}),
		s.find(".detail_item_td_name").bind("click",
		function(n) {
			var i, t;
			n = n ? n: window.event,
			i = n.srcElement ? n.srcElement: n.target;
			if (i.type == "text" || i.type == "textarea") return ! 0;
			t = jQuery(this).attr("icoid"),
			jQuery("#right_list .detail_item_td_name").each(function() {
				jQuery(this).removeClass("detail_item_td_name_selected")
			}),
			jQuery(this).addClass("detail_item_td_name_selected"),
			t = jQuery(this).attr("icoid"),
			_file.selected = t;
			if (r["icos_" + t].type == "folder") jQuery("#file_OK").off("click").on("click",
			function() {
				_file.getdata("f-" + r["icos_" + t].oid + "-" + _config.space.uid)
			});
			else {
				document.getElementById("file_select_input").value = r["icos_" + t].name;
				jQuery("#file_OK").off("click").on("click",
				function() {
					_window.windows._W_openfile.OnOK()
				})
			}
		}),
		s.find(".detail_item_td_name").bind("dblclick",
		function(n) {
			var i, t;
			n = n ? n: window.event,
			i = n.srcElement ? n.srcElement: n.target;
			if (i.type == "text" || i.type == "textarea") return ! 0;
			t = jQuery(this).attr("icoid"),
			_file.selected = t,
			r["icos_" + t].type == "folder" ? _file.getdata("f-" + r["icos_" + t].oid + "-" + _config.space.uid) : (document.getElementById("file_select_input").value = r["icos_" + t].name, _window.windows._W_openfile.OnOK())
		}),
		s.find(".detail_header").bind("click",
		function() {
			var n = parseInt(jQuery(this).attr("disp"));
			_file.asc = n == _file.disp ? _file.asc ? 0 : 1 : 1,
			_file.disp = n,
			_file.showIcos()
		})
	}
	jQuery(_window.windows[_file.winid].titleCase).find(".arrange").each(function() {
		jQuery(this).removeClass(jQuery(this).attr("index") + "2").addClass(jQuery(this).attr("index") + "1")
	}),
	jQuery("#" + _file.winid + "_view" + _file.view).removeClass(jQuery("#" + _file.winid + "_view" + _file.view).attr("index") + "1").addClass(jQuery("#" + _file.winid + "_view" + _file.view).attr("index") + "2")
},
_tab.tabs = {},
_tab.zIndex = 0,
_op = {};
jQuery(document).on("keydown",
function(n) {
	var t;
	n.which != "" ? t = n.which: n.charCode != "" ? t = n.charCode: n.keyCode != "" && (t = n.keyCode);
	switch (t) {
	case 17:
		_op.ctrl = 1;
		break;
	case 18:
		_op.alt = 1;
		break;
	case 16:
		_op.shift = 1
	}
});
jQuery(document).on("keyup",
function(n) {
	var t, i;
	n.which != "" ? t = n.which: n.charCode != "" ? t = n.charCode: n.keyCode != "" && (t = n.keyCode);
	switch (t) {
	case 17:
		_op.ctrl = 0;
		break;
	case 18:
		_op.alt = 0;
		break;
	case 16:
		_op.shift = 0;
		break;
	case 67:
		_op.alt && _window.currentWindow("Close"),
		_op.alt = 0;
		break;
	case 77:
		_op.alt && _window.currentWindow("Max"),
		_op.alt = 0;
		break;
	case 78:
		_op.alt && _window.currentWindow("Min"),
		_op.alt = 0;
		break;
	case 81:
		_op.alt && _op.shift && _window.CloseAppwinAll(),
		_op.alt = 0,
		_op.shift = 0;
		break;
	case 37:
		_op.ctrl && _op.alt && _navbar.setpreDesktop();
		break;
	case 39:
		_op.ctrl && _op.alt && _navbar.setnextDesktop();
		break;
	case 49:
	case 50:
	case 51:
	case 52:
	case 53:
	case 54:
	case 55:
	case 56:
	case 57:
	case 97:
	case 98:
	case 99:
	case 100:
	case 101:
	case 120:
	case 103:
	case 104:
	case 105:
		_op.alt && _op.ctrl && (i = 0, i = t < 97 ? t - 48 : t - 96, jQuery.inArray(i + "", _config.navids) > -1 && _navbar.setCurrentDesktop(i));
		break;
	case 68:
		_op.alt && _op.ctrl && _window.showDesktop();
		break;
	case 69:
		_op.alt && _op.ctrl && _login.click("logout")
	}
})