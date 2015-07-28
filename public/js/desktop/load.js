/*
 * function.js 1.0.0 - New Wave Javascript
 *
 * Copyright (c) 2013 ZzStudio (gridinfo.com.cn)
 * Author xiaobosun@gridinfo.com.cn
 *
 * $Date: 2013-05-10 13:05:17 $
 */
 
//document.write("<script language='javascript' type='text/javascript' src='js/easyui/jquery.easyui.min.js'><\/script>"); 

jQuery(document).ready(function(e) {
	//重新定义所有按钮样式
	$("*[class^='btns-'],*[class*=' btns-'],*[class^='btnm-'],*[class*=' btnm-'],*[class^='btnb-'],*[class*=' btnb-']").each(function(i, e) {
		if($.type($(this).find('span').html())!='string'){
			$(this).html("<span>"+$(this).html()+"</span>")
		}
	});
	/*初始化摘要列表的鼠标移上事件*/
	$('.summary-list>li').hover(function(){
		$(this).find('.tip-widget').show();	
	},function(){
		$(this).find('.tip-widget').hide();	
	});
	/*初始化所有slide属性元素*/
	$(document).on('click','.more a',function(e){
		id = $(this).attr('slide');
		$this = $(this).parent();
		if('undefined' == typeof(id)) return;
		if('none' == $('#'+id).css('display')){
			$('#'+id).slideDown('fast');
			$this.html($this.html().replace($(this).text(),'收起'));
			$this.html($this.html().replace('arrow-tip-bottom','arrow-tip-up'));
		}else{
			$('#'+id).slideUp('fast');
			$this.html($this.html().replace($(this).text(),'更多'));
			$this.html($this.html().replace('arrow-tip-up','arrow-tip-bottom'));
		}	
	});
	/*初始化所有combobox元素的onchange事件*/
	$(".easyui-combobox").combobox({ 
		onChange: function (n,o) {
			//获取当前的ID
			var name = $(this).attr('comboname');
			if(typeof($(this).attr('onchange'))!='undefined'){
				$(this).attr('onchange',$(this).attr('onchange').replace('this',"$('input[name=\""+name+"\"]')"));
				eval($(this).attr('onchange'));
			}
		} 
	}); 
	/*初始化所有a标签的元素，类型为submit的事件*/
	$('a[type="submit"]').each(function(i, e) {
		$(this).on('click',function(){
			var _obj = $(this);
			do{_obj = _obj.parent();}
			while (_obj.children('form').length==0)
			_obj.children('form').submit();
		});
	});
	/*执行tabs的点击事件*/
	$('#tabs-main').tabs({
		onSelect: function (title) {
			var panel = $(this).tabs('getTab', title);
			var height = panel.find('iframe').contents().find('body').height();
			//panel.find('iframe').contents().find('.panel-body').css("width", "100%");
			panel.css({"width":"100%"});
			if (typeof (height) != 'undefined') {
				panel.find('iframe').css({
					"width": "100%",
					"height": height
				});
			}
		}
	});
	
	/*初始化tabs内容链接*/
	$('.summary-list').find('a').each(function(i, e) {
		replace_link(this);
	});
	$('.graphic-list').find('a').each(function(i, e) {
		replace_link(this);
	});
	$('.clink_replace').find('a').each(function(i, e) {
		replace_link(this);
	});
	
	/*初始化tabs的右键菜单*/ 
	$('#tabs-main').children('.tabs-header').bind('contextmenu',function(e){  
		e.preventDefault();  
		$('#tabs-header-contextmenu').menu('show', {  
			left: e.pageX,  
			top: e.pageY  
		});  
	});
	/*初始化tabs的内容tabs列表*/
	create_tabslist();
	
	/*重置主要内容的滚动条显示*/
	$('.layout-body[region="center"]').css({'overflow-y':'scroll'});
	
	/*初始化所有combo样式鼠标事件*/
	$('.combo').on('focus',function(){
		var _select = $('select[comboname="'+$(this).find('input[class="combo-value"]').attr('name')+'"]');
		var val = $(this).find('input[class="combo-value"]').val();
		var text = typeof(_select.attr('missingMessage'))=='undefined' ? '请选择' : _select.attr('missingMessage');
		if(_select.attr('required')){
			if(val=='' || val==0){
				if(typeof(_select.parent().find('.warning-msg').html())=='undefined')
					_select.parent().append('<div class="warning-msg"></div>');
				var msg = _select.parent().find('.warning-msg');
				msg.html("<div tabindex=\"-1\" class=\"tooltip tooltip-right\">"+"<div class=\"tooltip-content\"></div>"+"<div class=\"tooltip-arrow-outer\"></div>"+"<div class=\"tooltip-arrow\"></div>"+"</div>");
				msg.find('.tooltip-content').html(text);
				msg.find('.tooltip').css({'display':'block'});
			}
		}
	});
	$('.combo').on('blur',function(){
		$(this).parent().find('.warning-msg').remove();
	});
	
	/*初始化所有combo样式鼠标事件*/
	$('button').on('focus',function(){
		var _select = $(this);
		var text = typeof(_select.attr('missingMessage'))=='undefined' ? '请选择' : _select.attr('missingMessage');
		if(_select.attr('required')){
			if(typeof(_select.parent().find('.warning-msg').html())=='undefined')
				_select.parent().append('<div class="warning-msg"></div>');
			var msg = _select.parent().find('.warning-msg');
			msg.html("<div tabindex=\"-1\" class=\"tooltip tooltip-right\">"+"<div class=\"tooltip-content\"></div>"+"<div class=\"tooltip-arrow-outer\"></div>"+"<div class=\"tooltip-arrow\"></div>"+"</div>");
			msg.find('.tooltip-content').html(text);
			msg.find('.tooltip').css({'display':'block'});
		}
	});
	$('button').on('blur',function(){
		$(this).parent().find('.warning-msg').remove();
	});
	
	/*初始化所有textarea样式鼠标事件*/
	$('textarea').on('focus',function(){
		var _select = $(this);
		var val = $(this).val();
		var text = typeof(_select.attr('missingMessage'))=='undefined' ? '请填写' : _select.attr('missingMessage');
		if(_select.attr('required')){
			if(val=='' || val.length==0){
				if(typeof(_select.parent().find('.warning-textarea').html())=='undefined')
					_select.parent().prepend('<div class="warning-textarea"></div>');
				var msg = _select.parent().find('.warning-textarea');
				msg.html("<div tabindex=\"-1\" class=\"tooltip tooltip-right\">"+"<div class=\"tooltip-content\"></div>"+"<div class=\"tooltip-arrow-outer\"></div>"+"<div class=\"tooltip-arrow\"></div>"+"</div>");
				msg.find('.tooltip-content').html(text);
				msg.find('.tooltip').css({'display':'block'});
			}
		}
		$(this).parent().parent().on('click',function(){
			$(this).parent().find('.warning-textarea').remove();
		});
		//var textarea = $(this);
		//KISSY.Event.on('body','focus',function(){
			//$(textarea).parent().find('.warning-textarea').remove();
		//});
	});
	
	$('.textarea').on('blur',function(){
		$(this).parent().find('.warning-textarea').remove();
	});
	
	/*给所有input必选内容前面跟*号*/
	$('input').each(function(i, e) {
		var option = $.trim($(this).attr("data-options"));
		if(option.indexOf('required:true')!=-1){
			//alert($(this)[0].innerHTML);
		}
	});
	
	/* 给顶部工具栏的“首页”按钮绑定事件 */
	$(".nav_button.home").attr('onclick','').click(function(){
		tabs.select(0);
	});
	/* 给顶部工具栏的"刷新"按钮绑定事件 */
	$(".nav_button.refresh").attr('onclick','').click(function(){
		tabs.refresh();
	});
	
	/*给tabs控件的tab上绑定双击事件*/
	$('.easyui-tabs').on('dblclick','a.tabs-inner',function(e){
		tabs.close();
	});
	
	/*重写滚动条样式*/
	$(window).load(function(){
		//$('.panel-body').jScrollPane();
	});
	
	/*关闭遮罩层*/
	$.mask.hide();
});

function create_tabslist(id){
	if(typeof(id)=='undefined'){
		id = 'tabs-main';	
	}
	//创建默认tabs列表按钮
	var alink = '<a href="javascript:;" id="tabs-list" class="tabs-list">选项卡列表<i class="arrow arrow-tip-bottom"></i></a>';
	
	//创建菜单的空div	
	$('#'+id).find('.tabs-tool').prepend(alink);
	
	$('#'+id).on('mouseover', 'a#tabs-list', function(e){
		tabslist_event(id);
	});
	$('#'+id).on('click', 'a#tabs-list', function(e){
		tabslist_event(id);
	});
	$('#'+id).on('mouseout', 'a#tabs-list', function(e){
		timeset = setTimeout(function(){
			$('#tabs-header-list').menu('hide');
		},300);
	});
}

function tabslist_event(id){
	//使选项卡列表内容自适应宽
	$('#tabs-header-list').css({width:'auto'});
	$('#tabs-header-list').children('div').remove();
	var count = $('#'+id).tabs('tabs').length;
	for (var i=(count-1);i>=0;i--){	
		var tab	=	$('#'+id).tabs('getTab',i);
		var tabtitle = tab.panel('options').title;	
		$('#tabs-header-list').append("<div data-options=\"name:'"+tabtitle+"'\" name=\""+tabtitle+"\" class=\"menu-item\" style=\"height:20px;\"><div class=\"menu-text\">"+tabtitle+"</div></div>");
	}

	$('#tabs-header-list').menu('show', { 					 
		left: ($('#tabs-list').offset().left+88)-$('#tabs-header-list').width(),  
		top: $('#tabs-list').offset().top+26
	});
	$('#tabs-header-list').children('.menu-item').bind("mouseover",function(e){
		clearTimeout(timeset);
		$(this).addClass('menu-active');
		$(this).bind('click',function(e){
			$('#'+id).tabs('select',$(this).attr("name"));
			$('#tabs-header-list').menu('hide');
		});
	});
	$('#tabs-header-list').children('.menu-item').bind("mouseout",function(e){
		$(this).removeClass('menu-active');
	});	
}