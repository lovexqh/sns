//根据条件重写url方法 孙晓波 2013-7-8添加
if(typeof top.OpenBrowser == "function"){
	$('.username a').each(function(i, e) {
		//判断是不是click
		if(typeof($(this).attr('onclick'))!='undefined'){
			$(this).attr('href','javascript:;');
		}
		if(typeof($(this).attr('href'))!='undefined' 
			&& $(this).attr('href').indexOf('javascript:')==-1){
			
			//分页时
			if(typeof($(this).parent().attr('class'))!='undefined' && $(this).parent().attr('class').indexOf('page')!=-1){

			}else{
				var url = $(this).attr('href');
				var title = $.trim($(this).attr('title'))!='' ? $.trim($(this).attr('title')) : $.trim($(this).text());
				if(title=='') title = $(document)[0].title;
				$(this).attr('onclick',"top.OpenBrowser ('"+url+"','"+title+"', 'width=1024,height=550,titlebutton=close|max|min');");
				$(this).attr('href','javascript:;');
				$(this).removeAttr('target');
			}
			
		}else{

		}
	});
}