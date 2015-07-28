/*
	[Dzz!] (C)2001-2009 gridinfo.com.cn
	This is NOT a freeware, use is subject to license terms

	$Id: market.js 20130411  01:11:24Z ZzStudio $
*/

var auto_search_keyword='';
var auto_search_timer=null;
function autosearch(){
	var keyword=document.getElementById('searchInput').value;
	if(keyword=='' || auto_search_keyword==keyword) return;
	else {
		auto_search_keyword=keyword;
		if(auto_search_timer) window.clearTimeout(auto_search_timer);
		auto_search_timer=setTimeout(function(){
			ajaxget(_URL_+U('desktop/Market/ajax',['do=autosearch&keyword='+encodeURI(keyword)]),'searchSuggestList','searchSuggestList','','block');
		},300);
	}
}
function searchsubmit(){
	var keyword=encodeURI(document.getElementById('searchInput').value);
	control_click(null,'search',null,null,null,1,null,keyword);
	if(auto_search_timer) window.clearTimeout(auto_search_timer);
	document.getElementById('searchSuggestList').style.display='none';
	if(keyword) ajaxget(_URL_+U('desktop/Market/ajax',['do=updatesearch&keyword='+keyword]));
}

function resetCatHeight(){
	resizeTimer=null;
	var catHeight=jQuery('#cat_nav').find('.catNavTrigger').length*37+10;
	if(catHeight+parseInt(jQuery('#cat_nav').css('top'))>(document.documentElement.clientHeight)){
		jQuery('#cat_nav_down').show();
		
	}else{
		jQuery('#cat_nav_down').hide();
		if((document.documentElement.clientHeight-catHeight)<35) jQuery('#cat_nav').css('top',document.documentElement.clientHeight-catHeight);
		else {
			jQuery('#cat_nav').css('top',35);
			jQuery('#cat_nav_up').hide();
		}
	}
	if(parseInt(jQuery('#cat_nav').css('top'))<35){
		jQuery('#cat_nav_up').show();
	}else{
		jQuery('#cat_nav_up').hide();
	}
}

var sequence=new Array();
var current_sequence=0;
nav_back_forward();
function nav_back_forward(){
	if(sequence.length<1){
		current_sequence=0;
		document.getElementById('nav_forward').className='nav_button forwardDisable';
		document.getElementById('nav_back').className='nav_button backDisable';
		jQuery('#nav_forward').unbind();
		jQuery('#nav_back').unbind();
	}else if(sequence.length==1){
		current_sequence=1;
		document.getElementById('nav_forward').className='nav_button forwardDisable';
		document.getElementById('nav_back').className='nav_button backDisable';
		jQuery('#nav_forward').unbind();
		jQuery('#nav_back').unbind();
	}else{
		 if(current_sequence==sequence.length){
			document.getElementById('nav_forward').className='nav_button forwardDisable';
			jQuery('#nav_back').unbind();
			jQuery('#nav_forward').unbind();
			document.getElementById('nav_back').className='nav_button back';
			jQuery('#nav_back').bind('click',function(){nav_back_forword_click('back');});
		}else if(current_sequence==1){
			document.getElementById('nav_back').className='nav_button backDisable';
			jQuery('#nav_back').unbind();
			jQuery('#nav_forward').unbind();
			document.getElementById('nav_forward').className='nav_button forward';
			jQuery('#nav_forward').bind('click',function(){nav_back_forword_click('forward');});
		}else{
			jQuery('#nav_forward').unbind();
			jQuery('#nav_back').unbind();
			document.getElementById('nav_forward').className='nav_button forward';
			jQuery('#nav_forward').bind('click',function(){nav_back_forword_click('forward');});
			document.getElementById('nav_back').className='nav_button back';
			jQuery('#nav_back').bind('click',function(){nav_back_forword_click('back');});
		}
	}
}
function nav_back_forword_click(direction){
	if(direction=='back'){
		current_sequence-=1;
	}else if(direction=='forward'){
		current_sequence+=1;
	}
	nav_back_forward();
	if(!sequence[current_sequence-1]) return;
	else var item=sequence[current_sequence-1];
	for(var i=0;i<item.url.length;i++){
		if(item.url[i].cache){
			if(jQuery('#'+item.url[i].id).html()=='') ajaxget(item.url[i].url,item.url[i].id,item.url[i].id);
		}else{
			ajaxget(item.url[i].url,item.url[i].id,item.url[i].id);
		}
	}
	switch(item.cmd){
		case 'all':
			//html操作
			jQuery('#appTab_all_list_orderbyContainer .currentOrderBy').removeClass('currentOrderBy');
			jQuery('#appTab_all_list_orderby_'+item.order).addClass('currentOrderBy');
			jQuery('.page').hide();
			jQuery('#all').show();
			jQuery('.catNavTrigger').removeClass('select');
			item.obj.addClass('select');
			jQuery('#appTab_all_list_orderbyContainer .currentOrderBy').removeClass('currentOrderBy');
			jQuery('#appTab_all_list_orderby_'+item.order).addClass('currentOrderBy');
			document.getElementById('nav_home').className='nav_button home';
			document.getElementById('nav_home').onclick=function(){control_click(jQuery('#cat_nav-2'),'main');}
			break;
		case 'main':
			//html操作
			
			jQuery('.page').hide();
			jQuery('#main').show();
			jQuery('.catNavTrigger').removeClass('select');
			item.obj.addClass('select');
			document.getElementById('nav_home').className='nav_button homeDisable';
			document.getElementById('nav_home').onclick=null;
			break;
		case 'introduce':
			//html操作
			jQuery('.page').hide();
			jQuery('#introduce').show();
			jQuery('.catNavTrigger').removeClass('select');
			document.getElementById('nav_home').className='nav_button home';
			document.getElementById('nav_home').onclick=function(){control_click(jQuery('#cat_nav-2'),'main');}
			break;
		case 'search':
			jQuery('.page').hide();
			jQuery('#search').show();
			jQuery('.catNavTrigger').removeClass('select');
			document.getElementById('nav_home').className='nav_button home';
			document.getElementById('nav_home').onclick=function(){control_click(jQuery('#cat_nav-2'),'main');}
			break;
	}
	
}
function control_click(el,cmd,cat,order,appid,page,sid,keyword){
	var item={};
	switch(cmd){
		case 'all':
			item.obj=el;
			item.cmd='all';
			item.cat=parseInt(el.attr('cat'));
			item.order=order;
			item.page=page||0;
			item.sid=sid||0;
			item.url=new Array();
			//alert(_URL_+U('desktop/Market/ajax',["do=appPanel_all_list_body&sort="+item.order+"&cid="+item.cat+"&page="+item.page+"&sid="+item.sid]));
			item.url[item.url.length]={"url":_URL_+U('desktop/Market/ajax',["do=appPanel_all_list_body&sort="+item.order+"&cid="+item.cat+"&page="+item.page+"&sid="+item.sid]),"id":"appPanel_all_list_body","cache":0};
			item.url[item.url.length]={"url":_URL_+U('desktop/Market/ajax',["do=jinqianzhuang&cid="+item.cat]),"id":"all_region_rt","cache":1};
			//item.url[item.url.length]={"url":_URL_+U('desktop/Market/ajax',["do=all_region_rb"]),"id":"all_region_rb","cache":1};
			//html操作
			jQuery('#appTab_all_list_orderbyContainer .currentOrderBy').removeClass('currentOrderBy');
			jQuery('#appTab_all_list_orderby_'+item.order).addClass('currentOrderBy');
			
			jQuery('.catNavTrigger').removeClass('select');
			el.addClass('select');
			jQuery('#appTab_all_list_orderbyContainer .currentOrderBy').removeClass('currentOrderBy');
			jQuery('#appTab_all_list_orderby_'+item.order).addClass('currentOrderBy');
			document.getElementById('nav_home').className='nav_button home';
			document.getElementById('nav_home').onclick=function(){control_click(jQuery('#cat_nav-2'),'main');}
			break;
		case 'main':
			item.obj=el;
			item.cmd='main';
			item.cat=-2;
			item.order=null;
			item.url=new Array();
			
			//item.url[item.url.length]={"url":DZZSCRIPT+'?mod=market&op=ajax&do=jinqizhuda',"id":"main_region_rt","cache":1};
			item.url[item.url.length]={"url":_URL_+U('desktop/Market/ajax',['do=main_region_rm']),"id":"main_region_rm","cache":1};
			item.url[item.url.length]={"url":_URL_+U('desktop/Market/ajax',['do=main_region_lt']),"id":"main_region_lt","cache":1};
			item.url[item.url.length]={"url":_URL_+U('desktop/Market/ajax',['do=main_region_lm']),"id":"main_region_lm","cache":1};
			//item.url[item.url.length]={"url":_URL_+U('desktop/Market/ajax',['do=main_region_lm2']),"id":"main_region_lm2","cache":1};
			//item.url[item.url.length]={"url":DZZSCRIPT+'?mod=market&op=ajax&do=main_region_lb',"id":"main_region_lb","cache":1};
			//html操作
			
			jQuery('.catNavTrigger').removeClass('select');
			el.addClass('select');
			document.getElementById('nav_home').className='nav_button homeDisable';
			document.getElementById('nav_home').onclick=null;
			break;
		case 'introduce':
			item.obj=el;
			item.cmd='introduce';
			item.cat=cat;
			item.appid=appid;
			item.order=null;
			item.url=new Array();
			item.url[item.url.length]={"url":_URL_+U('desktop/Market/ajax',['do=introduce_region_lt&appid='+appid]),"id":"introduce_region_lt","cache":0};
			item.url[item.url.length]={"url":_URL_+U('desktop/Market/ajax',['do=introduce_region_rt&cid='+cat+'&appid='+appid]),"id":"introduce_region_rt","cache":0};
			item.url[item.url.length]={"url":_URL_+U('desktop/Market/ajax',['do=introduce_region_rb']),"id":"introduce_region_rb","cache":1};
			//html操作
			jQuery('.catNavTrigger').removeClass('select');
			document.getElementById('nav_home').className='nav_button home';
			document.getElementById('nav_home').onclick=function(){control_click(jQuery('#cat_nav-2'),'main');}
			break;
		case 'search':
			item.cmd='search';
			item.url=new Array();
			item.url[item.url.length]={"url":_URL_+U('desktop/Market/ajax',['do=search_region_l&page='+page+'&keyword='+keyword]),"id":"search_region_l","cache":0};
			item.url[item.url.length]={"url":_URL_+U('desktop/Market/ajax',['do=search_region_rt']),"id":"search_region_rt","cache":0};
			item.url[item.url.length]={"url":_URL_+U('desktop/Market/ajax',['do=search_region_rb']),"id":"search_region_rb","cache":0};
			jQuery('.catNavTrigger').removeClass('select');
			document.getElementById('nav_home').className='nav_button home';
			document.getElementById('nav_home').onclick=function(){control_click(jQuery('#cat_nav-2'),'main');}
			break;
	}
	for(var i=0;i<item.url.length;i++){
		if(item.url[i].cache){
			if(jQuery('#'+item.url[i].id).html()=='') ajaxget(item.url[i].url,item.url[i].id,'ajaxwaited');
		}else{
			ajaxget(item.url[i].url,item.url[i].id,'ajaxwaited');
		}
	}
	jQuery('.page').hide();
	jQuery('#'+item.cmd).show();
	sequence.splice(current_sequence);
	current_sequence=sequence.push(item);
	nav_back_forward();
};
function GetIeVersion()
{
    var reg = new RegExp("MSIE ([^;]*);", "i");
    if (reg.test(navigator.appVersion)) return parseInt(RegExp.$1);
    return 0;
};
var ieVersion = GetIeVersion();
fixpng = function (obj) {
			if(ieVersion>0 && ieVersion<7 ){
				var png = obj.src;
				obj.onload=null;
				if(png.substr(png.lastIndexOf('.')).toLowerCase()=='.png'){
					obj.src = 'dzz/images/b.gif';
					obj.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + png + "',sizingMethod='scale')";
				}
			}
	};




