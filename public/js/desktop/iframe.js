//优先关闭进度条
//parent.ui.progress.close();
var iframe = true;
//启用加载
$(document).ready(function(e) {
	setTimeout(_init, 800);
});
//初始化函数
function _init(){
	/*获取内容高度*/
	vheight = $(document).height()+100;
	setTimeout(function(){
		$('iframe',parent.document).each(function(i, e) {
			if(vheight>0){
				$(this).height(vheight);
			}else if($(this).attr('src') == window.location.href){
				$(this).height(Math.max($(document).height(),window.screen.height));	
			}
		});
	},800);
	
	/*重新给框架内的a标签事件进行重置*/
	$('.summary-list').find('a').each(function(i, e) {
		if('undefined' != typeof($(this).attr('onclick'))){
			//设置白名单
			var func = new Array('ui','tabs','Alert','Confirm','Prompt');
			//设置黑名单
			var funh = new Array('ui.emotions');
			//获取onclick的方法名
			var fn = $(this).attr('onclick');
			fn = fn.substr(0,fn.indexOf('('));
			if(fn.indexOf('.')!=-1){
				fn = fn.substr(0,fn.indexOf('.'));
			}
			//如果是白名单的函数则增加parent
			if(func.in_array(fn)&&$(this).attr('onclick').indexOf('ui.emotions')==-1)
				$(this).attr('onclick','parent.' + $(this).attr('onclick'))
		}else{
			$(this).attr('target', '_parent');
		}
	});
	/*给分页功能加点击效果*/
	$('.page').find('a').each(function(i, e) {
		$(this).on('click',function(){
			$("body",parent.document).find('.panel-body').scrollTop(0);
			parent.mask.show();
			//parent.ui.progress.show({title:'分页数据载入中...'}); 
		});
	});
	/*隐藏加载遮罩层*/
	if('block' == $("body",parent.document).find('.mask').css('display') && 'block' == $("body",parent.document).find('.mask-msg').css('display')){
		parent.mask.hide();
	}
}

/*获取当前打开页面iframe的高度，并重新赋值*/
function refresh_iframe($height){
	$('#tabs-main',parent.document).find('.panel').each(function(index, element) {
        if($(this).css('display')=='block'){
			$(this).find('iframe').scroll().height($(this).find('iframe').scroll().height()+($height));
		}
	});
}