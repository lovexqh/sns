var APP={
	video:"视频点播",
	poster:"我的招贴",
	vote:"我的投票",
	photo:"我的相册",
	event:"我的活动",
	blog:"博客",
	gift:"我的礼物",
	group:"我的社团",
	timeline:"时光轴",
	monitor:"平安校园",
	tv:"校园电视台",
	teaching:"阳光教研",
	resource:"阳光资源",
	airclass:"空中课堂",
	exercise:"益智园地",
	favorite:"我的收藏",
	desktop:"Web桌面",
	teach:"阳光研训",
	teaching:"学习工具",
	prepare:"我的备课",
	enc:"ENC"
};
//如果页面开始没有定义 is_relink 变量，则执行
if(typeof(top.OpenBrowser) == "function" && typeof(is_relink)=='undefined'){
	replace_link();
}

// 替换页面所有的个人空间链接（条件为链接上有uid属性的情况下）
function replace_link(_id){
	if(typeof(_id)=="undefined") _id = 'body';
	$(_id).find('a').each(function(i, e) {
		var href = typeof($(this).attr('href'))!='undefined' ? $(this).attr('href') : "";
		//替换个人空间
		if(typeof($(this).attr('uid'))!='undefined'
			&& $(this).attr('href').indexOf('javascript:')==-1){
			var url = $(this).attr('href');
			//$(this).attr('onclick',"top.OpenBrowser('"+url+"','个人主页', 'width=1024,height=600,titlebutton=close|max|min');");
			$(this).attr('href','javascript:void(0);');
			var title = $(this).attr('title') ? $(this).attr('title') : '个人主页';
			$(this).bind('click',function(){
				top.OpenBrowser(url,title, 'width=1024,height=600,titlebutton=close|max|min');
			});
			$(this).removeAttr('target');
		}else if(href.indexOf('javascript:')!=-1){
			return;
		}else{
			var _click = $(this).attr('onclick')==null ? '' : $(this).attr('onclick');
			if(_click.indexOf('top.Open')!=-1){
				$(this).removeAttr('target')
				return;
			}
			//替换新窗口打开
			var _type = $(this).attr('type')==null ? '' : $(this).attr('type');
			//新增属性，将替换type属性
			if(_type=="") _type = $(this).attr('rel')==null ? '' : $(this).attr('rel');
			if(_type.toLowerCase().indexOf('_blank')!=-1 || _type.toLowerCase().indexOf('browser')!=-1){
				title = "";
				title = $(this).attr('title')==null ? $(this).text() : $(this).attr('title'); 
				var url = $(this).attr('href');
				$(this).attr('href','javascript:void(0);');
				$(this).bind('click',function(){
					top.OpenBrowser(url,title, 'width=1024,height=600,titlebutton=close|max|min');
					return;
				});
			}
			//替换新窗口打开
			var _target = $(this).attr('target')==null ? '' : $(this).attr('target');
			if(_target.toLowerCase().indexOf('_blank')!=-1){
				var url = $(this).attr('href');
				//孙晓波（Byron）修改于2013-8-6 (使应用界面打开) begin
				us = url.split('#');
				url = us[0];
				
				if(_type.toLowerCase().indexOf('_blank')==-1){
					url = url + (url.indexOf('&')!=-1||url.indexOf('?')!=-1 ? '&' : '?') + "show_title=" + $.trim($(this).attr('title')) + "&show_code=8cb1b9b8d40c3dca2cfe1cc31e12570f"; //将标题添加至url上
				}
				if(us.length>1) url += "#"+us[1];
				var title = $.trim($(this).attr('title'))!='' ? $.trim($(this).attr('title')) : $.trim($(this).text());
				if(title=='') title = $(document)[0].title;
				
				appname = GetRequest('app',url);
				
				var appalias;
				if(typeof(appname)!='undefined'){
                    appalias = $(this).attr('title') ? $(this).attr('title') : APP[appname];
					//appalias = APP[appname];
				}else{
					//获取URL重写的URL
					if(url.indexOf('app/')!=-1){
						appname = url.substring(url.indexOf('app/')+4);
						if(appname.indexOf('?')!=-1){
							appname_temp=appname.split('?');
							appname=appname_temp[0];
						}
						if(appname.indexOf('/')!=-1){
							appname_temp=appname.split('/');
							appname=appname_temp[0];
						}
						//appalias = APP[appname];
                        appalias = $(this).attr('title') ? $(this).attr('title') : APP[appname];
					}
				}
				if(typeof(appalias)!='undefined'){
		//$(this).attr('onclick',"top.OpenWindow('url','"+url+"','"+appalias+"','','titlebutton=close|max,width=1035,height=600');return false;");
					$(this).bind('click',function(){
						top.OpenWindow('url',url,appalias,'','titlebutton=close|max,width=1035,height=600');
					});
				}else{
					$(this).bind('click',function(){
						top.OpenBrowser(url,title,'width=1024,height=600,titlebutton=close|max|min');
					});
				//$(this).attr('onclick',"top.OpenBrowser('"+url+"','"+title+"','width=1024,height=600,titlebutton=close|max|min');");
				}
				// end
				$(this).attr('href','javascript:void(0);');
				$(this).removeAttr('target');
			}
		}
	});	
}
//JS获取URL参数值
function GetRequest(key,url) {
	if(typeof(url)=='undefined'){
		url = location.search; //获取url中"?"符后的字串
	}else{
		url = url.substr(url.indexOf('?'));	
	}
	var theRequest = new Object();
	if (url.indexOf("?") != -1) {
		var str = url.substr(1);
		strs = str.split("&");
		for(var i = 0; i < strs.length; i ++) {
			theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
		}
	}
	return theRequest[key];
}