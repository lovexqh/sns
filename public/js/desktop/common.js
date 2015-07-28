/*
 * function.js 1.0.0 - New Wave Javascript
 *
 * Copyright (c) 2013 ZzStudio (gridinfo.com.cn)
 * Author xiaobosun@gridinfo.com.cn
 *
 * $Date: 2013-05-10 13:05:17 $
 */

/*
 *分享微博话题按钮方法中扩展方法
*/
(function($){
    $.fn.extend({
        insertAtCaret: function(myValue){
            var $t=$(this)[0];
            if (document.selection) {
                this.focus();
                sel = document.selection.createRange();
                sel.text = myValue;
                this.focus();
            }
            else 
                if ($t.selectionStart || $t.selectionStart == '0') {
                    var startPos = $t.selectionStart;
                    var endPos = $t.selectionEnd;
                    var scrollTop = $t.scrollTop;
                    $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
                    this.focus();
                    $t.selectionStart = startPos + myValue.length;
                    $t.selectionEnd = startPos + myValue.length;
                    $t.scrollTop = scrollTop;
                }
                else {
                    this.value += myValue;
                    this.focus();
                }
        }
    })  
})(jQuery);

/**
 * 载入js方法
 * @param url js地址
 */
function loadJs(url, callback){
	var done = false;
	var script = document.createElement('script');
	script.type = 'text/javascript';
	script.language = 'javascript';
	script.src = url;
	script.onload = script.onreadystatechange = function(){
		if (!done && (!script.readyState || script.readyState == 'loaded' || script.readyState == 'complete')){
			done = true;
			script.onload = script.onreadystatechange = null;
			if (callback){
				callback.call(script);
			}
		}
	}
	document.getElementsByTagName("head")[0].appendChild(script);
}

/**
 * 让js支持in_array方法
 */
Array.prototype.S = String.fromCharCode(2);  
Array.prototype.in_array = function(e) {  
    var r = new RegExp(this.S+e+this.S);  
    return (r.test(this.S+this.join(this.S)+this.S));  
};  

/**
 * 重写alert方法
 * @param status error|info|question|warning|default
 */
Alert = function(title, message, status) {
	//如果是iframe内部打开则
	if(typeof(iframe)!='undefined'){
		parent.Alert(title, message, status);
		return;
	}
	if('undefined' == typeof(message) || '' == message){
		message = title;
		title = '来自网页的消息';
	}
	$.messager.alert(title, message, status);
}
/**
 * 重写confirm方法
 * @param callback 回调函数
 */
Confirm = function(title, message, callback) {
	//如果是iframe内部打开则
	if(typeof(iframe)!='undefined'){
		parent.Confirm(title, message, callback);
		return;
	}
	//否则
	if('undefined' == typeof(callback)){
		callback = message;
		message = title;
		title = '来自网页的消息';
	}
	$.messager.confirm(title, message, callback);
};
/**
 * 重写prompt方法
 * @param callback 回调函数
 */
Prompt = function(title, message, callback) {
	//如果是iframe内部打开则
	if(typeof(iframe)!='undefined'){
		parent.Prompt(title, message, callback);
		return;
	}
	//否则
	if('undefined' == typeof(callback)){
		callback = message;
		message = title;
		title = '来自网页的消息';
	}
	$.messager.prompt(title, message, callback);
};

/**
 * 定义ui
 * @param
 */
var ui = {};
/**
 * 扩展ui
 * @param
 */
jQuery.extend(ui, {
	progress:function(options){
 		return $.messager.progress(options);
	},
	message:function(options){
 		Alert(options.title, options.msg, options.showType)
	},
	dialog:function(options){
		$('#ui-dialog').empty();
		$('#ui-dialog').append('<div id="dialog-modal"></div>');
		if(typeof(options.href)!='undefined' && options.href != ''){
			var n = options.href;
			var width = options.width ? options.width : '100%';
			var height = options.height ? options.height : '100%';
			dialogframe = document.createElement("iframe"), dialogframe.name = "ifm_dialog", dialogframe.id = "ifm_dialog", dialogframe.className = "dialogIframe", dialogframe.frameBorder = 0, dialogframe.marginHeight = 0, dialogframe.marginWidth = 0, dialogframe.width = width, dialogframe.height = height, dialogframe.scrolling = 'no', dialogframe.src = n;
			document.getElementById('dialog-modal').appendChild(dialogframe);
			options.href = null;
		}
		$('#dialog-modal').dialog(options); 
	},
	error:function(msg){
		//title,msg,width,height,timeout,style
		//ui.message.fade({msg:msg,timeout:2000,style:'c'});
		var messager = parent && parent.$ && parent.$.messager ? parent.$.messager : $.messager;
		messager.alert('提示',msg,'error');
	},
	success:function(msg){
		ui.message.fade({title:'来自网页的消息',timeout:2000,msg:msg,style:'c'});
	},
	confirm:function(_this,msg){
		Confirm(msg,function(r){
			if(r){
				eval($(_this).attr('callback'));
			}
		});
	},
	load:function(){
		var init = 0
		var loadingBox = '<div class="html_clew_box" id="ui_loading" style="display:none"><div class="html_clew_box_con"><span class="ico_waiting">加载中……</span></div></div>';
		if( !init ){
			$('body').append( loadingBox );
			init = 1;
		}
		
		$( '#ui_loading' ).css({
			right:100+"px",
			top:($(document).scrollTop())+"px"
		});
		$( '#ui_loading' ).fadeIn("slow");
	},
	
	loaded:function(){
		var loadingBox = '#ui_loading';
		$( loadingBox ).fadeOut("slow");
	},
	
	quicklogin:function(){
		ui.box.load( U('home/Public/quick_login') ,{title:'快速登录'});
	},
	emotions:function(o){
		var $talkPop = $('div.talkPop');
		var $body = $('body');
		var $o = $(o);
		if($talkPop.html() !== null) {
			$('#emotions').remove();
		}
		if (1 != $talkPop.data('type')) {
			$talkPop.hide();
		}
		this.emotdata = $body.data("emotdata");
		this.html = '<div class="talkPop alL" id="emotions">'
				 + '<div style="position: relative; height: 7px; line-height: 3px;z-index:99">'
				 + '<img src="' + _THEME_ + '/images/zw_img.gif" style="margin-left: 10px; position: absolute;" class="talkPop_arrow"></div>'
				 + '<div class="talkPop_box">'
				 + '<div class="close" style="height:30px;line-height:30px;background-color:#F8FAFC;padding:0 10px;position:relative;*width:420px"><a onclick=" $(\'#emotions\').remove()" class="del" href="javascript:void(0)" title="关闭"> </a><span>常用表情</span></div>'
				 + '<div class="faces_box" id="emot_content"><img src="'+ _THEME_+'/images/icon_waiting.gif" width="20" class="alM"></div></div></div>';
		target_set = $o.attr('target_set');
		$body.append(this.html);
		var position = $o.offset();
		$('#emotions').css({"top":position.top+"px","left":position.left+"px","z-index":99999999});
		
		var _this = this;
		if(!this.emotdata){
			$.post( U('home/user/emotions'),{target:$(this).attr('target_set')} ,function(txt){
				txt = eval('('+txt+')');
				$body.data("emotdata",txt);
				emotionShowContent(txt);
			})
		}else{
			emotionShowContent(this.emotdata);
		};
		
		// $body.live('click',function(event){
		// 	if( $(event.target).attr('target_set')!=target_set ){
		// 		$('#emotions').remove();
		// 	}
		// })
	},
	emotions_c:function(emot,target){
	    $("#"+target).insertAtCaret(emot);
		$("#"+target).focus();
		$('#emotions').remove();
	}
});

function emotionShowContent(data){  //显示表情内容
	var content ='';
	$.each(data,function(i,n){
		content+='<a href="javascript:void(0)" title="'+n.title+'" onclick="ui.emotions_c(\''+n.emotion+'\',\''+target_set+'\')"><img src="'+ _THEME_ +'/images/expression/'+n.type+'/'+ n.filename +'" width="24" height="24" /></a>';
	});
	content+='<div class="c"></div>';
	$('#emot_content').html(content);
};
/**
 * 定义ui.message
 * @param
 */
jQuery.extend(ui.progress, {
	/**
	 * 重新封装progress.show方法
	 * @param options title:进度条窗口的标题,
	 *				  msg:进度条上面提示的文字,
	 *		  		  text:进度条上的文字,
	 *		  		  interval:进度条展示的时间，默认300
	 */
	show:function(options){
		if('undefined' == typeof(options)){
			options = {};
		}
		if('undefined' == typeof(options.title)){
			options.title = '数据载入中，请稍等...';
		}
		if('undefined' == typeof(options.text)){
			options.text = 'Loading...';
		}
		var win = $.messager.progress(options);
		//默认5秒自动关闭
		setTimeout(function(){  
			$.messager.progress('close');  
		},5000);
	},
	close:function(){
		$.messager.progress('close');
	}
});
/**
 * 定义ui.message
 * @param
 */
jQuery.extend(ui.message, {
	/**
	 * 重新封装show方法
	 * @param options title:无title属性的时候会显示默认值，如果title：null那么将不会显示标题,
	 *				  msg:消息模块中要显示的信息，支持HTML代码,
	 *		  		  width:窗口宽度,
	 *		  		  height:窗口高度,码,
	 *		  		  timeout:消息窗口自动关闭的时间，设置为0时则不会自动关闭,
	 *		  		  style:自定义的展示样式（前期不建议使用）
	 */
	show:function(options){
		if('undefined' == typeof(options.title)){
			options.title = '来自网页的消息';
		}
		if('undefined' == typeof(options.showType)){
			options.showType = 'show';
		}
		if('undefined' != typeof(options.style)){
			switch(options.style){
				case 'tl':
					options.style = {
						right:'',
						left:0,
						top:document.body.scrollTop+document.documentElement.scrollTop,
						bottom:''
					};
				break
				case 'tc':
					options.style = {
						right:'',
						top:document.body.scrollTop+document.documentElement.scrollTop,
						bottom:''
					};
				break
				case 'tr':
					options.style = {
						left:'',
						right:0,
						top:document.body.scrollTop+document.documentElement.scrollTop,
						bottom:''
					};
				break
				case 'cl':
					options.style = {
						left:0,
						right:'',
						bottom:''
					};
				break
				case 'c':
					options.style = {
						right:'',
						bottom:''
					};
				break
				case 'cr':
					options.style = {
						left:'',
						right:0,
						bottom:''
					};
				break
				case 'bl':
					options.style = {
						left:0,
						right:'',
						top:'',
						bottom:-document.body.scrollTop-document.documentElement.scrollTop
					};
				break
				case 'bc':
					options.style = {
						right:'',
						top:'',
						bottom:-document.body.scrollTop-document.documentElement.scrollTop
					};
				break
				case 'br':
					options.style = {
						left:'',
						right:0,
						top:'',
						bottom:-document.body.scrollTop-document.documentElement.scrollTop
					};
				break
				default:
			}
		}
	    var messager = parent && parent.$ && parent.$.messager ? parent.$.messager : $.messager;
		messager.show({
			title:options.title,
			msg:options.msg,
			timeout:options.timeout,
			width:options.width,
			height:options.height,
			showType:options.showType,
			showSpeed:options.showSpeed,
			style:options.style
		});
	},
	slide:function(options){
		options.showType='slide';
	    ui.message.show(options);
	},
	fade:function(options){
	    options.showType='fade';
	    ui.message.show(options);
	}
});

/**
 * 定义panel
 */
var panel = {};
/**
 * 扩展ui
 * @param
 */
jQuery.extend(panel, {
	/**
	 * 收起功能
	 * @param id:要收起的元素父级ID status:要收起的方式
	 * status的值 north, south, east, west
	 */
	collapse:function(id,status){
		$('#'+id).layout('collapse',status);
	},
	/**
	 * 展开功能
	 * @param id:要展开的ID status:要展开的方式
	 */
	extend:function(id,status){
		$('#'+id).layout('extend',status);
	}
});

/**
 * Tabs Open方法
 */
var tabs = {};
/**
 * 扩展tabs
 * @param
 */
jQuery.extend(tabs, {
	/**
	 * 打开链接功能
	 * @param title:要显示的标题 status:要收起的方式
	 * status的值 north, south, east, west
	 */
	open:function(title,url){
		if ($('#tabs-main').tabs('exists',title)){
			$('#tabs-main').tabs('select', title);
			//tabs.refresh({tabTitle:title,url:url});
		} else {
			$('#tabs-main').tabs('add',{
				title:title,
				//iconCls:'icon-gears',
				href:url,
				closable:true,
				//content:content
				extractor:function(data){
					var tmp = $('<div></div>').html(data);
					
					//分页事件转换
					tmp.find('.page>a').each(function(i, e) {
						$(this).attr('onclick','tabs.page("'+$(this).attr('href')+'");');
						$(this).attr('href','javascript:;');
					});
					
					data = tmp.find('#tabs-content').html();
					tmp.remove();
					return data;
				}
			});
		}
	},
	iframe:function(title,url){
		//启动遮罩层
		mask.show();
		url = url + (url.indexOf('&')!=-1||url.indexOf('?')!=-1 ? '&' : '?') + "iframe=yes";
		$("body",parent.document).find('.panel-body').scrollTop(0);
		if ($('#tabs-main').tabs('exists',title)){
			$('#tabs-main').tabs('select', title);
			
			tabs.refresh(title,url); 
		} else {
			if (url){  
				var content = '<iframe scrolling="no" name="tabs-context" onLoad="iframe_height(this);" frameborder="0" src="' + url + '" style="width:100%; height:100%;"></iframe>';  
			} else {  
				var content = '未实现';  
			}

			$('#tabs-main').tabs('add',{
				title:title,
				//iconCls:'icon-gears',
				closable:true,
				content:content
			});
			var panel = $('#tabs-main').tabs('getTab', title);
			panel.css({"width":"100%"});
		}
	},
	content:function(title,url){
		//启动遮罩层
		mask.show();
		url = url + (url.indexOf('&')!=-1||url.indexOf('?')!=-1 ? '&' : '?') + "iframe=yes"; 
		if ($('#tabs-main').tabs('exists',title)){
			$('#tabs-main').tabs('select', title);
			tabs.refresh(title,url); 
		} else {
			var content = '<iframe scrolling="no" frameborder="0" src="'+url+'" style="width:100%;height:100%;"></iframe>';  
			$('#tabs-main').tabs('add',{
				title:title,
				content:content,
				closable:true
			});
		}
	},
	/**    
	 * 将tab内容部分转换为ifreame
	 */
	toIframe:function(title,url){
		var cur_tab = $('#tabs-main').tabs('getTab',title);
		if(url && cur_tab.find('iframe').length == 0){
			url = url + (url.indexOf('&')!=-1||url.indexOf('?')!=-1 ? '&' : '?') +Math.random()*100;
			var content = '<iframe scrolling="no" onLoad="iframe_height(this);" frameborder="0" src="'+url+'" style="width:100%;height:100%;"></iframe>';
			cur_tab.html(content);
		}
		//转换到目标tab
		$('#tabs-main').tabs('select', title);
	},
	/**    
	 * 将tab内容部分转换为ifreame
	 */
	redirect:function(title,url,callback){
		var tab = $('#tabs-main').tabs('getSelected');
		cur_title = $('#tabs-main').tabs('getTabIndex',tab);
		//关闭当前的tab
		$('#tabs-main').tabs('close', cur_title);
		var cur_tab = $('#tabs-main').tabs('getTab',title);
		if(cur_tab){
			if(url && cur_tab.find('iframe').length == 0){
				url = url + (url.indexOf('&')!=-1||url.indexOf('?')!=-1 ? '&' : '?') +Math.random()*100;
				var content = '<iframe scrolling="no" onLoad="iframe_height(this);" frameborder="0" src="'+url+'" style="width:100%;height:100%;"></iframe>';
				cur_tab.html(content);
			}
			//转换到目标tab
			$('#tabs-main').tabs('select', title);
		}else{
			tabs.content(title,url);
		}
		tabs.refresh();
		//回调功能
		if(typeof(callback)!='undefined') 
			eval(callback);
	},
	/**    
	 * 刷新tab
	 * @cfg 
	 *example: {tabTitle:'tabTitle',url:'refreshUrl'}
	 *如果tabTitle为空，则默认刷新当前选中的tab
	 *如果url为空，则默认以原来的url进行reload
	 */
//	refresh:function(title,url){
//		var refresh_tab = title ? $('#tabs-main').tabs('getTab',title) : $('#tabs-main').tabs('getSelected');
//		if(refresh_tab){
//			if(refresh_tab.find('iframe').length == 0) {
//				url = url ? url : refresh_tab.attr("url");
//				url = url ? url : window.location.href;
//				url = url ? url + (url.indexOf('&') != -1 || url.indexOf('?') != -1 ? '&' : '?') + 'iframe=yes&' + Math.random() * 100 : '';
//				refresh_tab.html('<iframe scrolling="no" onLoad="iframe_height(this);" frameborder="0" src="' + url + '" style="width:100%;height:100%;"></iframe>');
//			} else {
//				var _refresh_ifram = refresh_tab.find('iframe')[0];
//				var refresh_url = url ? url : _refresh_ifram.src;
//				//_refresh_ifram.src = refresh_url;
//				_refresh_ifram.contentWindow.location.href = refresh_url;
//			}
//		}
//		/*关闭遮罩层*/
//		mask.hide();
//	},
	refresh:function(title,url){
		var refresh_tab = title ? $('#tabs-main').tabs('getTab',title) : $('#tabs-main').tabs('getSelected');
		if(refresh_tab){
			if(refresh_tab.find('iframe').length == 0) {
				/*url = url ? url : refresh_tab.attr("url");
				url = url ? url : window.location.href;
				url = url ? url + (url.indexOf('&') != -1 || url.indexOf('?') != -1 ? '&' : '?') + 'iframe=yes&' + Math.random() * 100 : '';
				refresh_tab.html('<iframe scrolling="no" onLoad="iframe_height(this);" frameborder="0" src="' + url + '" style="width:100%;height:100%;"></iframe>');*/
				
				if(typeof(_APP_INDEX_)!='undefined'){
					$.get(U(_APP_INDEX_, ['t='+Math.random()]),function(data){
						var html = $(data).find('#tabs-content').html();
						if(typeof(iframe)!='undefined')
							$(parent.document).find("#tabs-content").html(html);
						else
							$(document).find("#tabs-content").html(html);
					});
				}
			} else {
				var _refresh_ifram = refresh_tab.find('iframe')[0];
				var refresh_url = url ? url : _refresh_ifram.src;
				//_refresh_ifram.src = refresh_url;
				_refresh_ifram.contentWindow.location.href = refresh_url;
			}
		}
		/*关闭遮罩层*/
		mask.hide();
	},
	/**    
	 * 选择显示tab
	 * @cfg 
	 *example: title
	 */
	select:function(title){
		$('#tabs-main').tabs('select', title);
		//tabs.refresh({tabTitle:title}); 
	},
	/**    
	 * 关闭tab
	 * @cfg 
	 *example: title
	 */
	close:function(title){
		if('undefined' == typeof(title)){
			var tab = $('#tabs-main').tabs('getSelected');
			title = $('#tabs-main').tabs('getTabIndex',tab);
		}
		if(typeof(tab)=='undefined'){
			var tab	=	$('#tabs-main').tabs('getTab',title);
		}
		try{
			var tabclosable = tab.panel('options').closable;
		}catch(e){
			var tabclosable=0
		}
		if(tabclosable){
			$('#tabs-main').tabs('close', title);
		}
	},
	page:function(url){
		$('#tabs-main').find('.panel').each(function(i, e) {
			if($(this).css('display')!='none'){
				 $.ajax({
					url: url,
					type: 'GET',
					complete: function(response) {
						if(response.status != 200) {
							Alert('你提交的URL无效,请刷新页面后重试！');
						}
					},
					success:function(data){
						$(e).find('.panel-body').html($(data).find('#tabs-content').html());
					}
				 });
			}
		});
	}

});

var mask = {};
/**
 * 扩展mask遮罩
 * @param
 */
jQuery.extend(mask, {
	/**
	 * 打开遮罩层
	 * @param option
	 * option的配置
	 	maskMsg string 提示信息 加载…… 
		zIndex number zIndex值 100000 
		timeout number 超时(毫秒) 30000 
		opacity number 透明程度.(0-1) 0.6 
	 */
	show:function(id, option){
		if('undefined' == typeof(id)){
			$.mask();
		}else{
			$("#"+id).mask();
		}
	},
	hide:function(id){
		if('undefined' == typeof(id)){
			$.mask.hide();
		}else{
			$("#"+id).mask("hide");
		}
	}
});

var win = {};
/**
 * 扩展win窗口相关方法
 * @param
 */
jQuery.extend(win, {
	/**
	 * 刷新左侧
	 * @param option
	 * option的配置 
	 */
	refresh_left:function(option){
		if(typeof(iframe)!='undefined'){
			parent.win.refresh_left(option);
			return;
		}
		if(typeof(_APP_INDEX_)!='undefined'){
			$.get(U(_APP_INDEX_, ['t='+Math.random()]),function(data){
				var html = $(data).filter('div.westbar').html();
				if(typeof(iframe)!='undefined')
					$(parent.document).find("div.westbar").html(html);
				else
					$(document).find("div.westbar").html(html);
			});
		}
	}
});

/**
 * 主体Tabs的右键菜单
 * @param
 */
function menuHandler(item){  
	if(item.name=='closeall'){
		var count = $('#tabs-main').tabs('tabs').length;	
		for (var i=(count-1);i>=0;i--){	
			var tab	=	$('#tabs-main').tabs('getTab',i);
			var tabclosable = tab.panel('options').closable;	
			if(tabclosable){
				$('#tabs-main').tabs('close',i);
			}
		}

	}else if(item.name=='closeother'){
		var sltabs	=	$('#tabs-main').tabs('getSelected');
		var sltindex = $('#tabs-main').tabs('getTabIndex',sltabs);
		var sltitle	=	sltabs.panel('options').title;
		var count = $('#tabs-main').tabs('tabs').length;	
		for (var i=(count-1);i>=0;i--){	
			var tab	=	$('#tabs-main').tabs('getTab',i);
			var tabtitle = tab.panel('options').title;
			var tabclosable = tab.panel('options').closable;
			if(tabclosable){
				if(sltitle!=tabtitle){
					$('#tabs-main').tabs('close',i);  
				}					
			}

		}			
	}
}

/**
 * 添加收藏方法
 * @param select对象 或者 name
 */
function add_Favorite(appname,action,method,fid,appconfig, _this){
	if(appname != '' && fid != ''){
		$.post(U('home/User/add_favorite'),
			{appname:appname,action:action,method:method,fid:fid,appconfig:appconfig,time:new Date().getTime()},
			function(data){
				if(data){
					var pattern =new RegExp("\\((.| )+?\\)","igm");
					
					if(typeof(iframe)!='undefined'){
						parent.ui.success('收藏成功！');
					}else{
						ui.success('收藏成功！');
					}
					if(typeof($(_this).html())!='undefined' && $.trim($(_this).html())!=''){
						$(_this).html($(_this).html().replace($(_this).text(),'已收藏'));
						$(_this).attr('title','取消收藏');
						var num = parseInt($(_this).parent().find('span').attr('favnums')) + 1;
						$(_this).parent().find('span').html(num);
						$(_this).parent().find('span').attr('favnums',num);
						console.log(($(_this).parent().text().match(pattern)))

					}else{
						$(_this).val('已收藏');
					}
					$(_this).attr("onclick","remove_Favorite('"+data+"',this);");
				}else{
					if(typeof(iframe)!='undefined'){
						parent.ui.error('收藏失败！');
					}else{
						ui.error('收藏失败！');
					}
				}
			}
		);
	}
	
}
/**
 * 取消收藏方法
 * @param select对象 或者 name
 */
function remove_Favorite(id, _this){
	$.post(U('home/User/remove_Favorite'),
		{id:id,time:new Date().getTime()},
		function(data){
			if(data.id){
				if(typeof(iframe)!='undefined'){
					parent.ui.success('取消成功！');
				}else{
					ui.success('取消成功！');
				}
				if(typeof($(_this).html())!='undefined' && $.trim($(_this).html())!=''){
					$(_this).html($(_this).html().replace($(_this).text(),'收藏'));
					$(_this).attr('title','加入收藏');
					var num = parseInt($(_this).parent().find('span').attr('favnums')) - 1;
					$(_this).parent().find('span').html(num);
					$(_this).parent().find('span').attr('favnums',num);
				}else{
					$(_this).val('收藏');
				}
				$(_this).attr("onclick","add_Favorite('"+data.appname+"','"+data.action+"','"+data.method+"','"+data.fid+"','"+data.appconfig+"',this);");
			}else{
				if(typeof(iframe)!='undefined'){
					parent.ui.error('移除失败！');
				}else{
					ui.error('移除失败！');
				}
			}
		},'json');
}

/**
 * select验证模式
 * @param select对象 或者 name
 */
function select_validate(obj){
	$(obj).parent().focus();
	return false;
}
/**
 * select验证模式
 * @param _select jquery选择器
 */
function check_focus(_select){
	$("body",parent.document).find('.panel-body').scrollTop($(_select).offset().top);
}

/**
 * 替换新桌面页面上的链接
 * @param obj dom对象
 */
function replace_link(obj){
	if(typeof($(obj).attr('onclick'))=='undefined'){
		
		var title =htmlspecialchars(typeof($(obj).attr('title'))!='undefined' ? $(obj).attr('title') : $(obj).text());
		//判断是否为弹出窗口链接
		if((typeof($(obj).attr('target'))!='undefined' && $(obj).attr('target')=='_blank') || typeof($(obj).attr('uid'))!='undefined'){
			$(obj).attr('onclick',"top.OpenBrowser('"+$(obj).attr('href')+"', '"+title+"');");
			$(obj).attr('href','javascript:;');
			$(obj).removeAttr('target');
		}else{
			if(typeof($(obj).attr('class'))!='undefined' && $(obj).attr('class').toLowerCase().indexOf('spacewin')!=-1){
				$(obj).attr('onclick','top.OpenSpaceWin("'+$(obj).attr('href')+'", "'+title+'");');
			}else{
				if(typeof(iframe)!='undefined'){
					$(obj).attr('onclick','parent.tabs.content("'+title+'", "'+$(obj).attr('href')+'");');
				}else{
					$(obj).attr('onclick','tabs.content("'+title+'", "'+$(obj).attr('href')+'");');
				}
			}
			$(obj).attr('href','javascript:;');
		}
	}
}
/* 转义title中的特殊字符 */
function htmlspecialchars(str)  
{  
    str = str.replace(/&/g, '&amp;');
    str = str.replace(/</g, '&lt;');
    str = str.replace(/>/g, '&gt;');
    str = str.replace(/"/g, '&quot;');
    str = str.replace(/'/g, '&#039;');
    return str;
}


/*自动设置ifreame高度*/
function iframe_height(iframe){
	var height = $(iframe).contents().find('body').height();
	//$(iframe).contents().find('.panel-body').css("width", "100%");
	$(iframe).parent().css({"width":"100%"});
	if (typeof (height) != 'undefined') {
		$(iframe).css({
			"width": "100%",
			"height": height
		});
	}
}

//模拟ts U函数
function U(url,params){
	var website = _ROOT_+'/index.php';
	url = url.split('/');
	if(url[0]=='' || url[0]=='@')
		url[0] = APPNAME;
	if (!url[1])
		url[1] = 'Index';
	if (!url[2])
		url[2] = 'index';
	website = website+'?app='+url[0]+'&mod='+url[1]+'&act='+url[2];
	if(params){
		params = params.join('&');
		website = website + '&' + params;
	}
	return website;
}

/**
 * 更换注册码
 */
function changeverify(id){
	if(typeof(id)=='undefined') id = 'verifyimg';
    var date = new Date();
    var ttime = date.getTime();
    var url = _PUBLIC_+"/captcha.php";
    $('#'+id).attr('src',url+'?'+ttime);
}

/**
 * 写cookies
 * @param name,value,time
 */
function setCookie(name,value,time)
{
	if(typeof(time)=='undefined'){
		var Days = 30;
		var exp = new Date();
		exp.setTime(exp.getTime() + Days*24*60*60*1000);
	}else{
		var strsec = getsec(time);
		var exp = new Date();
		exp.setTime(exp.getTime() + strsec*1);
	}
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
/**
 * 获取时间
 * @param str
 */
function getsec(str)
{
   var str1=str.substring(1,str.length)*1;
   var str2=str.substring(0,1);
   if (str2=="s")
   {
        return str1*1000;
   }
   else if (str2=="h")
   {
       return str1*60*60*1000;
   }
   else if (str2=="d")
   {
       return str1*24*60*60*1000;
   }
}
//这是有设定过期时间的使用示例：
//s20是代表20秒
//h是指小时，如12小时则是：h12
//d是天数，30天则：d30

/**
 * 读取cookies
 * @param name
 */
function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
        return (arr[2]);
    else
        return null;
}

/**
 * 删除cookies
 * @param name
 */
function delCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null)
        document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}
//使用示例
//setCookie("name","hayden");
//alert(getCookie("name"));


//字符串长度-中文和全角符号为1，英文、数字和半角为0.5
var getLength = function(str, shortUrl) {
	str = str + '';
	if (true == shortUrl) {
		// 一个URL当作十个字长度计算
		return Math.ceil(str.replace(/((news|telnet|nttp|file|http|ftp|https):\/\/){1}(([-A-Za-z0-9]+(\.[-A-Za-z0-9]+)*(\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]*)?(\/[-A-Za-z0-9_\$\.\+\!\*\(\),;:@&=\?\/~\#\%]*)*/ig, 'xxxxxxxxxxxxxxxxxxxx')
							.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	} else {
		return Math.ceil(str.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	}
};

var subStr = function (str, len) {
    if(!str) { return ''; }
        len = len > 0 ? len*2 : 280;
    var count = 0,	//计数：中文2字节，英文1字节
        temp = '';  //临时字符串
    for (var i = 0;i < str.length;i ++) {
    	if (str.charCodeAt(i) > 255) {
        	count += 2;
        } else {
        	count ++;
        }
        //如果增加计数后长度大于限定长度，就直接返回临时字符串
        if(count > len) { return temp; }
        //将当前内容加到临时字符串
         temp += str.charAt(i);
    }
    return str;
};

//异步请求页面
function async_page(url, target, callback)
{
	if (!url) {
		return false;
	} else if (target) {
		var $target = $(target);
		//$target.html('<img src="'+_THEME_+'/images/icon_waiting.gif" width="20" style="margin:10px 50%;" />');
	}
	$.post(url,{},function(txt){
		txt = eval("(" + txt + ")");
		if (txt.status) {
			if (target) {
				$target.html(txt.data);
			}
			if (callback) {
				if (callback.match(/[(][^()]*[)]/)) {
					eval(callback);
				} else {
					eval(callback)(txt);
				}
			}
			if (txt.info) {
				ui.success(txt.info);
			}
		} else if (txt.info) {
			ui.error(txt.info);
			return false;
		}
	});
	return true;
}

//异步加载翻页
function async_turn_page(page_number, target)
{
	$(page_number).click(function(o){
		var $a = $(o.target);
		var url = $a.attr("href");
		if (url) {
			async_page(url, target);
		}
		return false;
	});
}

//表单异步处理 /* 生效条件：包含 jquery.form.js */
function async_form(form)
{
	var $form = form ? $(form) : $("form[ajax='ajax']");

	//监听 form 表单提交
	$form.bind('submit', function() {
		var callback = $(this).attr('callback');
		var options = {
		    success: function(txt) {
		    	txt = eval("("+txt+")");
				if(callback){
					if (callback.match(/[(][^()]*[)]/)) {
						eval(callback);
					} else {
						eval(callback)(txt);
					}
				}else{
					if(txt.status && txt.info){
						ui.success( txt.info );
					}else if (txt.info) {
						ui.error( txt.info );
					}						  	 
				}
		    }
		};		
    $(this).ajaxSubmit(options);
		return false;
});
}

// 复制剪贴板
function copy_clip(copy){
	var copy_clip = function(g){
		if(window.clipboardData&&(jQuery.browser.msie && jQuery.browser.version < 7)){
			window.clipboardData.clearData();
			window.clipboardData.setData("Text",g);
			return true;
		}else{
			if(jQuery.browser.msie){
				try{
					window.clipboardData.clearData();
					window.clipboardData.setData("Text",g);
					return true;
				}catch(l){
					return false;
				}
			}else{
				try{
					netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
					var d=Components.classes["@mozilla.org/widget/clipboard;1"].createInstance(Components.interfaces.nsIClipboard);
					if(!d){
						return
					}
					var n=Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);
					if(!n){
						return
					}
					n.addDataFlavor("text/unicode");
					var m={};
					var k={};
					m=Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
					var b=g;
					m.data=b;
					n.setTransferData("text/unicode",m,b.length*2);
					var a=Components.interfaces.nsIClipboard;
					if(!d){
						return false
					}
					d.setData(n,null,a.kGlobalClipboard);
					return true
				}catch(l){
					return false
				}
			}
		}
	}
	if ( copy_clip( copy ) ) {
		ui.success( "复制成功！请Ctrl+V键粘贴到要加入的页面。" );
		return true;
	} else {
		ui.error("你的浏览器不支持脚本复制或你拒绝了浏览器安全确认，请尝试手动[Ctrl+C]复制。");
		return false;
	}
}

// 图片缩放
function photo_resize(name,sizeNum){
	var newWidth = $(name).width();
    $(name +" img").each(function(){
        
        var width = sizeNum || 590;
        var images = $(this);
        
        //判断是否是IE
        if (-[1, ]) {
            image = new Image();
            image.src = $(this).attr('src');
            image.onload = function(){
                if (image.width >= width) {
                    images.click(function(){
                        tb_show("", this.src, false);
                    });
                    images.width(width);
                    images.height(width / image.width * image.height);
                }
            }
        }
        else {
            if (images.width() >= width) {
                images.click(function(){
                    tb_show("", this.src, false);
                });
                images.width(width);
                images.height(width / images.width() * images.height());
            }
        }

		
		//image.attr('rel','imageGroup');

    });
}

// 过滤html，字串检测长度
function checkPostContent(content){
	content = content.replace(/&nbsp;/g, "");
	content = content.replace(/<br>/g, "");
	content = content.replace(/<p>/g, "");
	content = content.replace(/<\/p>/g, "");
	return getLength(content);
}

/**
 * 定义调试方法（可打印当前对象的属性值）
 * @param
 */
function showPrpos(obj) { 
	// 用来保存所有的属性名称和值
	var props = "";
	// 开始遍历
	for(var p in obj){ 
		// 方法
		if(typeof(obj[p])=="function"){ 
			//obj[p]();
		}else{ 
			// p 为属性名称，obj[p]为对应属性的值
			props += p + "=" + obj[p] + ";  ";
		} 
	} 
	// 最后显示所有的属性
	alert(props);
}

/**
 * http://www.openjs.com/scripts/events/keyboard_shortcuts/
 * Version : 1.00.A
 * By Binny V A
 * 键盘绑定事件
 * License : BSD
 */
function shortcut(shortcut,callback,opt) {
	//Provide a set of default options
	var default_options = {
		'type':'keydown',
		'propagate':false,
		'target':document
	}
	if(!opt) opt = default_options;
	else {
		for(var dfo in default_options) {
			if(typeof opt[dfo] == 'undefined') opt[dfo] = default_options[dfo];
		}
	}

	var ele = opt.target
	if(typeof opt.target == 'string') ele = document.getElementById(opt.target);
	var ths = this;

	//The function to be called at keypress
	var func = function(e) {
		e = e || window.event;

		//Find Which key is pressed
		if (e.keyCode) code = e.keyCode;
		else if (e.which) code = e.which;
		var character = String.fromCharCode(code).toLowerCase();

		var keys = shortcut.toLowerCase().split("+");
		//Key Pressed - counts the number of valid keypresses - if it is same as the number of keys, the shortcut function is invoked
		var kp = 0;
		
		//Work around for stupid Shift key bug created by using lowercase - as a result the shift+num combination was broken
		var shift_nums = {
			"`":"~",
			"1":"!",
			"2":"@",
			"3":"#",
			"4":"$",
			"5":"%",
			"6":"^",
			"7":"&",
			"8":"*",
			"9":"(",
			"0":")",
			"-":"_",
			"=":"+",
			";":":",
			"'":"\"",
			",":"<",
			".":">",
			"/":"?",
			"\\":"|"
		}
		//Special Keys - and their codes
		var special_keys = {
			'esc':27,
			'escape':27,
			'tab':9,
			'space':32,
			'return':13,
			'enter':13,
			'backspace':8,

			'scrolllock':145,
			'scroll_lock':145,
			'scroll':145,
			'capslock':20,
			'caps_lock':20,
			'caps':20,
			'numlock':144,
			'num_lock':144,
			'num':144,
			
			'pause':19,
			'break':19,
			
			'insert':45,
			'home':36,
			'delete':46,
			'end':35,
			
			'pageup':33,
			'page_up':33,
			'pu':33,

			'pagedown':34,
			'page_down':34,
			'pd':34,

			'left':37,
			'up':38,
			'right':39,
			'down':40,

			'f1':112,
			'f2':113,
			'f3':114,
			'f4':115,
			'f5':116,
			'f6':117,
			'f7':118,
			'f8':119,
			'f9':120,
			'f10':121,
			'f11':122,
			'f12':123
		}


		for(var i=0; k=keys[i],i<keys.length; i++) {
			//Modifiers
			if(k == 'ctrl' || k == 'control') {
				if(e.ctrlKey) kp++;

			} else if(k ==  'shift') {
				if(e.shiftKey) kp++;

			} else if(k == 'alt') {
					if(e.altKey) kp++;

			} else if(k.length > 1) { //If it is a special key
				if(special_keys[k] == code) kp++;

			} else { //The special keys did not match
				if(character == k) kp++;
				else {
					if(shift_nums[character] && e.shiftKey) { //Stupid Shift key bug created by using lowercase
						character = shift_nums[character]; 
						if(character == k) kp++;
					}
				}
			}
		}

		if(kp == keys.length) {
			if (lock == 0) {
				lock = 1;
				setTimeout(function(){
					lock = 0;
				}, 1500);
			} else {
				return false;
			}
			callback(e);

			if(!opt['propagate']) { //Stop the event
				//e.cancelBubble is supported by IE - this will kill the bubbling process.
				e.cancelBubble = true;
				e.returnValue = false;

				//e.stopPropagation works only in Firefox.
				if (e.stopPropagation) {
					e.stopPropagation();
					e.preventDefault();
				}
				return false;
			}
		}
	}

	//Attach the function with the event
	var lock = 0;
	if(ele.addEventListener) ele.addEventListener(opt['type'], func, false);
	else if(ele.attachEvent) ele.attachEvent('on'+opt['type'], func);
	else ele['on'+opt['type']] = func;
}
/**
 * 对_select控件后面显示错误的那种信息
 * 信息内容在控件是_select.text,在显示之前定义一下
 * _select = $('#cid0');
 * _select.text = '请输入分类信息';
 * showInfo(_select);
 * 
 */
function showInfo(_select){
	if(typeof(_select.parent().find('.warning-msg').html())=='undefined')
		_select.parent().append('<div class="warning-msg"></div>');
	var msg = _select.parent().find('.warning-msg');
	msg.html("<div tabindex=\"-1\" class=\"tooltip tooltip-right\">"+"<div class=\"tooltip-content\"></div>"+"<div class=\"tooltip-arrow-outer\"></div>"+"<div class=\"tooltip-arrow\"></div>"+"</div>");
	msg.find('.tooltip-content').html(_select.text);
	msg.find('.tooltip').css({'display':'block'});
}

/**
 * 用于展开、隐藏图层
 * id 为所要显示隐藏的图层
 * e 为点击事件
 */
function retract(id,e){
	if($('#'+id).attr('width_val')==1){
		$('#'+id).animate({width:'200px'},function(){
			$(e).removeClass('layout-button-left').addClass('layout-button-right');	
		});
		$('#'+id).attr('width_val','');
		
	}else{
		$('#'+id).animate({width:'20px'},function(){
			$(e).removeClass('layout-button-right').addClass('layout-button-left');	
		});
		$('#'+id).attr('width_val',1);
	}
}

//举报
function denounce(from,aid,content,fuid,uid){
	var ui = parent && parent.ui ? parent.ui : ui;
	$.post(U('home/Widget/denounce'),{from:from,aid:aid,content:content,fuid:fuid,uid:uid},function(txt){
		ui.box.show(txt, {title:'举报',closeable:true});
	});
}

//设置黑名单
function setBlacklist(uid,type){
	$.post(U('home/Account/setBlackList') , {uid:uid,type:type} ,function(txt){
		ui.success('设置成功');
		location.reload();
	})
}

//空间关注操作
function dofollow(type,target,uid){
	var html = '';
	$('#follow_state').html( '<img src="'+ _THEME_+'/images/icon_waiting.gif" width="15">' );
	$.post( U('weibo/Operate/follow') ,{uid:uid,type:type},function(txt){
		if(txt=='14'){
			ui.error('关注人数已超过设置最大数量，关注失败！');
		}
		if(txt=='12'){
			html = followState('havefollow');
			followGroupSelectorBox(uid);
		}else if(txt=='13'){
			html = followState('eachfollow');
			followGroupSelectorBox(uid);
		}else if(txt=='00'){
			ui.error('对方不允许你关注');
			html = followState('unfollow',target,uid);
		}else{
			html = followState();
		}
		$('#follow_state').html( html );
	});
}

//列表关注操作
function dolistfollow(type,target,uid){
	var html = '';
	var target=target;
	var uid=uid;
	$("#follow_list_"+uid).html( '<img src="'+ _THEME_+'/images/icon_waiting.gif" width="15">' );
	
	$.post( U('weibo/Operate/follow') ,{uid:uid,type:type},function(txt){
		if(txt=='12'){
			//单向关注成功后
			html = followState('havefollow',target,uid);
			followGroupSelectorBox(uid);
			
			try{
				var num = parseInt($("#follower_"+uid).html());
				$("#follower_"+uid).html(num+1);
			}catch(e){}


		}else if(txt=='13'){
			//双向关注成功后
			html = followState('eachfollow',target,uid);
			followGroupSelectorBox(uid);
			
			try{
				var num = parseInt($("#follower_"+uid).html());
				$("#follower_"+uid).html(num+1);
			}catch(e){}

		}else if(txt=='00'){
			ui.error('对方不允许你关注');
			html = followState('unfollow',target,uid);
		}else{
			//取消关注后
			html = followState('',target,uid);
			
			try{
				var num = parseInt($("#follower_"+uid).html());
				$("#follower_"+uid).html(num-1);
			}catch(e){}

		}
		$("#follow_list_"+uid).html( html );
		
	});
}

//关注状态
function followState(type,target,uid){
	target = target || 'dofollow';
	uid    = uid    || _UID_;
	if(type=='havefollow'){
		html = '<div class="btn_relation"><span>已关注 | </span><a href="javascript:void(0);" title="取消对其关注" onclick="'+target+'(\'unflollow\',\''+target+'\','+uid+')">取消</a></div>';
	}else if(type=='eachfollow'){
		html = '<div class="btn_relation btn_relation2"><span>互相关注 | </span><a href="javascript:void(0);" title="取消对其关注" onclick="'+target+'(\'unflollow\',\''+target+'\','+uid+')">取消</a></div>';
	}else{
		html = '<a class="add_atn" href="javascript:void(0);" onclick="'+target+'(\'dofollow\',\''+target+'\','+uid+')">加关注</a>';
	}
	return html;
}


//班级空间教师页面关注
function dolistfollowClass(type,target,uid){
	var html = '';
	var target=target;
	var uid=uid;
	$(".follow_"+uid).each(function(i, e) {
        $(e).html( '<img src="'+ _THEME_+'/images/icon_waiting.gif" style="width:32px;height:32px">' );
    });
	$.post( U('weibo/Operate/follow') ,{uid:uid,type:type},function(txt){
		if(txt=='12'){
			html = followStateClass('havefollow',target,uid);
			followGroupSelectorBox(uid);
		}else if(txt=='13'){
			html = followStateClass('eachfollow',target,uid);
			followGroupSelectorBox(uid);
		}else if(txt=='00'){
			ui.error('对方不允许你关注');
			html = followStateClass('unfollow',target,uid);
		}else{
			html = followStateClass('',target,uid);
		}
		$(".follow_"+uid).each(function(i, e) {
        	$(e).html( html );
    	});
	});
}

//关注状态
function followStateClass(type,target,uid){
	target = target || 'dofollow';
	uid    = uid    || _UID_;
	if(type=='havefollow'){
		html = '<div style="color:#FF6600;"><span>[已关注|</span><a style="color:#FF6600;" href="javascript:void(0);" onclick="'+target+'(\'unflollow\',\''+target+'\','+uid+')">取消]</a></div>';
	}else if(type=='eachfollow'){
		html = '<div style="color:#FF6600;"><span>[互关注|</span><a style="color:#FF6600;" href="javascript:void(0);" onclick="'+target+'(\'unflollow\',\''+target+'\','+uid+')">取消]</a></div>';
	}else{
		html = '<a style="color:#FF6600;" href="javascript:void(0);" onclick="'+target+'(\'dofollow\',\''+target+'\','+uid+')">[加关注]</a>';
	}
	return html;
}


//新好友查询关注状态
function userfollowState(type,target,uid){
	target = target || 'dofollow';
	uid    = uid    || _UID_;
	if(type=='havefollow'){
		html = '<span class="follow">已关注 | <a href="javascript:void(0);" onclick="'+target+'(\'unflollow\',\''+target+'\','+uid+')">取消</a></span>';
	}else if(type=='eachfollow'){
		html = '<span class="follow">互相关注 | <a href="javascript:void(0);" onclick="'+target+'(\'unflollow\',\''+target+'\','+uid+')">取消</a></span>';
	}else{
		html = '<a class="follow" href="javascript:void(0);" onclick="'+target+'(\'dofollow\',\''+target+'\','+uid+')">加关注</a>';
	}
	return html;
}
//用户搜索列表关注操作
function userdolistfollow(type,target,uid){
	var html = '';
	var target=target;
	var uid=uid;
	$("#follow_list_"+uid).html( '<img src="'+ _THEME_+'/images/icon_waiting.gif" width="15">' );
	$.post( U('weibo/Operate/follow') ,{uid:uid,type:type},function(txt){
		if(txt=='12'){
			html = userfollowState('havefollow',target,uid);
			followGroupSelectorBox(uid);
		}else if(txt=='13'){
			html = userfollowState('eachfollow',target,uid);
			followGroupSelectorBox(uid);
		}else if(txt=='00'){
			ui.error('对方不允许你关注');
			html = userfollowState('unfollow',target,uid);
		}else{
			html = userfollowState('',target,uid);
		}
		$("#follow_list_"+uid).html( html );
	});
}

//好友分组选择-下拉
function followGroupSelector(fid){
	if($('.followGroupStatus'+fid).css('display') == 'none'){
		$.post(U('weibo/FollowGroup/selectorList'),{fid:fid},function(res){
			$('.followGroupStatus'+fid).html(res);
			$('.followGroupStatus'+fid).css('display','block');
		});
	}
}

//好友分组选择-弹窗
function followGroupSelectorBox(fid){
	ui.box.load( U('weibo/FollowGroup/selectorBox')+'&fid='+fid,{title:'设置分组'});
}

//关闭好友分组选择
function followGroupSelectorClose(fid){
	$('.followGroupStatus'+fid).hide();
	$('.followGroupStatus'+fid).html('');
}

//添加关注分组
function setFollowGroupTab(gid){
	var title = gid?'修改分组':'创建分组';
	gid = gid?'&gid='+gid:'';
	ui.box.load( U('weibo/FollowGroup/setGroupTab') + gid,{title:title});
}

//添加关注话题
function addFollowTopic(){
	var name = $("input[name='quick_name']").val();
	if(name==''){
		ui.error('请输入话题名称');
		return false;
	}
	$.post(U('weibo/operate/followtopic'),{name:name},function(txt){
		txt = eval( '(' + txt + ')' );
		if(txt.code=='12'){
			$("input[name='quick_name']").val('');
			$('.quick_win').hide();
			ui.success("添加关注话题成功");
			window.location.reload(true);
			//var html = '<li onmouseover="$(this).find(\'.del\').show()" onmouseout="$(this).find(\'.del\').hide()"><a class="del right" title="删除" href="javascript:void(0)" onclick="deleteFollowTopic(this,\''+txt.topicId+'\')"></a><a href="'+U('home/user/search',['k='+txt.name])+'">'+txt.name+'</a></li>';
			//$("ul[rel='followTopicArea']").append(html);
		}else if(txt.code=='11'){
			alert('已关注过此话题');
		}else{
			alert('关注失败');
		}

	});
}

//删除关注话题
function deleteFollowTopic(o,key){
	$.post(U('weibo/operate/unfollowtopic'),{topicId:key},function(txt){
		$(o).parents("li").remove();
	});
}

/**
 * 开始启动录制
 */
function grecord(appid,appfid){
	if($('#grecordapp').attr("ifclick") == "0"){		
		$.ajax({ 
			async: false, 
			type : "POST", 
			data :{ appid: appid, appfid: appfid },
			url : U('api/Grecord/index'),
			success : function(data) {
				var protolvalue = 'GcamStudio://'+data;
				var reg=new RegExp("\"","g");
				protolvalue = protolvalue.replace(reg,"");
				$('#grecordapp').attr("href",protolvalue); 
			} 
		}); 
		$('#grecordapp').attr("ifclick","1");
		$('#grecordtxt').click();
	}

}
