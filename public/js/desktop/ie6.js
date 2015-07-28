/*
 * ie6.js 1.0.0 - New Wave Javascript
 *
 * Copyright (c) 2013 ZzStudio (gridinfo.com.cn)
 * Author xiaobosun@gridinfo.com.cn
 *
 * $Date: 2013-05-10 13:05:17 $
 */
 
jQuery(document).ready(function(e) {
	//重新定义所有图标类样式
	$("*[class^='icon-'],*[class*=' icon-']").each(function(i, e) {
		$(this).addClass('icon');
	});
	$("*[class^='arrow-'],*[class*=' arrow-']").each(function(i, e) {
		$(this).addClass('arrow');
	});
	$("*[class^='btns-'],*[class*=' btns-']").each(function(i, e) {
		$(this).addClass('btns');
	});
	$("*[class^='btnm-'],*[class*=' btnm-']").each(function(i, e) {
		$(this).addClass('btnm');
	});
	$("*[class^='btnb-'],*[class*=' btnb-']").each(function(i, e) {
		$(this).addClass('btnb');
	});
	
	/*初始化摘要列表的功能小部件鼠标移上事件*/
	$('.tip-widget').hover(function(){
		$(this).addClass('hover');
	},function(){
		$(this).removeClass('hover');
	});
});