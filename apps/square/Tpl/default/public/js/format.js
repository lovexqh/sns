//根据条件重写url方法 孙晓波 2013-7-8添加
if(typeof top.OpenBrowser == "function"){
	$(document).find('a').each(function(i, e) {
		if(typeof($(this).attr('onclick'))!='undefined'){
			$(this).attr('href','javascript:;');
		}
		if(typeof($(this).attr('href'))!='undefined' && $(this).attr('href').indexOf('javascript:')==-1){
			var url = $(this).attr('href');
			var title = $.trim($(this).attr('title'))!='' ? $.trim($(this).attr('title')) : $.trim($(this).text());
			if(title=='') title = $(document)[0].title;
			$(this).attr('onclick',"top.OpenBrowser ('"+url+"','"+title+"', 'width=1024,height=600,titlebutton=close|max|min');");
			$(this).attr('href','javascript:;');
		}
	});
}