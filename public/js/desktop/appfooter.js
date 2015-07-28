$(function(){
	//初始化主右panel的位置
	$('#panel_right').css({'margin-left':$('#panel_left').width()});
	var panel_height = $(window).height()-($('header').height()+$('footer').height());
	$('#panel_right').height(panel_height);
	$('#panel_right').jScrollPane();
	window.onresize=resize;
})

/**
 * 描述：页面大小变化时重置方法
 * 作者：孙晓波
 * 修改日志
 * 日期      修改原因                 修改人    版本
 * 2013.2.4  创建                   孙晓波    @version 1.0
 */
function resize(){
	$('#panel_right').height($(window).height()-($('header').height()+$('footer').height()));
	$('#panel_right').jScrollPane('update');
}