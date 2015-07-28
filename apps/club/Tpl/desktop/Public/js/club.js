function creatNewClub(){
	ui.box.load(U('club/Index/creatClub'), {title:'创建社团'});
}

function editClubInfo(id){
	ui.box.load(U('club/Index/editClub&clubid='+id), {title:'修改社团信息'});
}

function applyJoinClub(clubid){
	ui.box.load(U('club/Member/applyJoinClub&clubid='+clubid), {title:'申请加入社团'});
}

//function autoScroll(obj){
//	$(obj).find("ul:first").animate({marginTop:"-25px"},1000,function(){
//		$(this).css({marginTop:"0px"}).find("li:first").appendTo(this);
//	});
//}
//
//$(document).ready(function(){
//	setInterval('autoScroll("#notice_show_div")',5000)
//});

//	var textRoll=function(){
//		$('#notice_show_div p:last').css({'height':'0px','opacity':'0'}).insertBefore('#notice_show_div p:first').animate({'height':'35px','opacity': '1'},1000);
//	}
//	$(function(){
//		//触发上下文字滚动事件
//		var roll=setInterval('textRoll()',2000);
//		$("#notice_show_div p").hover(function() {
//			clearInterval(roll);
//			}, function() {
//				roll = setInterval('textRoll()', 2000)
//			});
//	});

function scroll_news(){
    $(function(){
    	$('#notice_show_div p').eq(0).fadeOut(1000,function(){
    		$(this).clone().appendTo($(this).parent()).fadeIn(1000);
    		$(this).remove();
    	});
    });
}
$(function(){
//触发上下文字滚动事件
	var roll=setInterval('scroll_news()',5000);
	$("#notice_show_div p").hover(function() {
		clearInterval(roll);
	}, function() {
		roll = setInterval('scroll_news()', 5000)
	});
});

$(function () { 
	var scrollTimer = null; 
	var delay = 3000; 
	$('#scrollNews').hover(function () { 
		clearInterval(scrollTimer); 
	}, function () { 
		scrollTimer = setInterval(function () { 
			scrollNews(); 
		}, delay); 
	}).triggerHandler('mouseout'); 
}); 
//滚动新闻 
function scrollNews() { 
	var $news = $('#scrollNews>ul'); 
	var $lineHeight = $news.find('li:first').height(); 
	$news.animate({ 'marginTop': -$lineHeight + 'px' }, 600, function () { 
	$news.css({ margin: 0 }).find('li:first').appendTo($news); 
	}); 
}

function chkFocuc(){
	var title = $("#title").val();
	if("请输入标题" == title){
		$("#title").val("");
	}
}

function chkBlur(){
	var title = $("#title").val();
	if("" == title){
		$("#title").val("请输入标题");
	}
}

function topicSearchFocuc(){
	var topicKey = $("#topicKey").val();
	if("帖子搜索" == topicKey){
		$("#topicKey").val("");
	}
}

function topicSearchBlur(){
	var topicKey = $("#topicKey").val();
	if("" == topicKey){
		$("#topicKey").val("帖子搜索");
	}
}

function chkSearch(form){
	var topicKey = $("#topicKey").val();
	if(topicKey == '' || topicKey == '帖子搜索'){
		ui.error("请输入搜索关键词！"); 
		return false;
	}else{
		return true
	}
}

function operateTopic(id, type, num){
	$.post(U('club/Topic/operateTopic'),{id:id, type:type, num:num},function(result){
		switch(result){
			case "0":
				ui.error('操作失败');
				break;
			case "1":
				window.location.reload(); 
				break;
		}
   });
}

function delTopic(id){
	if(confirm("确定要删除该帖子吗？")){
		$.post(U('club/Topic/delTopic'),{id:id},function(result){
			switch(result){
				case "0":
					ui.error('删除失败');
					break;
				case "1":
					ui.success('删除成功!');
					//$('#topic_'+id).remove();
					window.location.reload(); 
					break;
			}
	   });
	}
}

function upload(){
	var reobj = swfu.getFile(0);
	if(reobj == null){
		alert('请选择上传文件');
	}else{
		swfu.startUpload();
	}
	return false;
}

function chkMemberSearch(form){
	var memberKey = $("#memberKey").val();
	if(memberKey == '' || memberKey == '成员搜索'){
		ui.error("请输入搜索关键词！"); 
		return false;
	}else{
		return true
	}
}

function memberSearchFocuc(){
	var memberKey = $("#memberKey").val();
	if("成员搜索" == memberKey){
		$("#memberKey").val("");
	}
}

function memberSearchBlur(){
	var memberKey = $("#memberKey").val();
	if("" == memberKey){
		$("#memberKey").val("成员搜索");
	}
}

function searchClassTable(uid){
	ui.box.load(U('club/Member/classTable&uid='+uid), {title:'查课程'});
}

function chgMemberDept(id,deptid,memid){
	if(deptid == 0){
		var ope = "选择部门"
	}else{
		var ope = "修改部门"
	}
	ui.box.load(U('club/Member/chgMemberDept&clubid='+id+'&deptid='+deptid+'&memid='+memid), {title:ope});
}

function noDeptAuditPass(id){
	if(confirm("确定审核通过吗？")){
		$.post(U('club/Member/doChgMemberDept'),{memid:id},function(result){
			switch(result){
				case "0":
					ui.error('操作失败');
					break;
				case "1":
					ui.success('操作成功!');
					window.location.reload(); 
					break;
			}
	   });
	}
}

function chkDateSearch(form){
	var starttime = $("#starttime").val();
	var endtime = $("#endtime").val();
	if(!starttime){
		ui.error("请选择开始日期！"); 
		return false;
	}else if(!endtime){
		ui.error("请选择结束日期！"); 
		return false;
	}else if(starttime > endtime){
		ui.error("开始日期应小于结束日期！"); 
		return false;
	}else{
		return true;
	}
}

function chkNoclassSearch(){
	var searchtime = $("#searchtime").val();
	var class1 = $("input[name='class1']").is(':checked');
	var class2 = $("input[name='class2']").is(':checked');
	var class3 = $("input[name='class3']").is(':checked');
	var class4 = $("input[name='class4']").is(':checked');
	var class5 = $("input[name='class5']").is(':checked');
	var class6 = $("input[name='class6']").is(':checked');
	if(!searchtime){
		ui.error("请选择日期！"); 
		return false;
	}
	if(!class1 && !class2 && !class3 && !class4 && !class5 && !class6){
		ui.error("请选择上课节次！"); 
		return false;
	}
	$.post(U('club/Member/chkSearchtime'),{searchtime:searchtime},function(result){
		if(result==0){
			ui.error('日期非本学期!');
			return false;
		}else{
			$('#account_search_form').submit();
		}
	});
	
}

function delAccount(id){
	if(confirm("确定要删除该名目吗？")){
		$.post(U('club/Account/delAccount'),{id:id},function(result){
			switch(result){
				case "0":
					ui.error('删除失败');
					break;
				case "1":
					ui.success('删除成功!');
					window.location.reload(); 
					break;
			}
	   });
	}
}

function delAccountItem(itemid, accountid){
	if(confirm("确定要删除该款项吗？")){
		$.post(U('club/Account/delAccountItem'),{itemid:itemid,accountid:accountid},function(result){
			switch(result){
				case "0":
					ui.error('删除失败');
					break;
				case "1":
					ui.success('删除成功!');
					window.location.reload(); 
					break;
			}
	   });
	}
}

function chgMemberType(id, word){
	var str = "";
	var type = 3;
	if(word == 1){
		str = "确定要取消该成员为管理员吗？";
		type = 3;
	}else if(word == 2){
		str = "确定要将该用户设为管理员吗？";
		type = 2;
	}else if(word == 3){
		str = "确定要执行 换届 操作吗？";
		type = 4;
	}else if(word == 4){
		str = "确定要执行 踢出 操作吗？";
		type = 5;
	}
	if(confirm(str)){
		$.post(U('club/Member/chgMemberType'),{id:id,type:type},function(result){
			switch(result){
				case "0":
					ui.error('操作失败');
					break;
				case "1":
					ui.success('操作成功!');
					window.location.reload(); 
					break;
				case "2":
					alert('先设至少一名管理员,再执行该操作');
					break;
			}
	   });
	}
}

//成员审核拒绝
function auditReject(id){
	if(confirm("确定要拒绝该成员吗？")){
		$.post(U('club/Member/auditRejectMember'),{id:id},function(result){
			switch(result){
				case "0":
					ui.error('操作失败');
					break;
				case "1":
					ui.success('操作成功!');
					window.location.reload(); 
					break;
			}
	   });
	}
}
function ClubClick(type){
	setTagStyle(type);
	$("#allClub_condition").val("请输入您要查找的社团名称");
	$.post(U('club/Index/allClub'),
			{club_type:type},
			function(data){
				var c = eval('('+data+')');
				var club = c.club;
				var club_html="";
				var num = 1;
				if(club.length == 0){
					club_html='<div style="height:20px;text-align:center;font-size:15px;">'+
								'<span>当前标签下不存在社团，您可以浏览其他标签下的社团...</span>'+
							  '</div>';
					$("#allClub_content").html(club_html);
					return;
				}
				$.each(club,function(key,val){
					if(num % 4 == 1){
						club_html=club_html+'<div class="allClub_content_single">';
					}else if((num % 4 == 2) || (num % 4 == 3)){
						club_html=club_html+'<div class="allClub_content_single">';					
					}else{
						club_html=club_html+'<div class="allClub_content_single">';						
					}
					club_html=club_html+'<table class="allClub_table">'+
				  							'<tr>'+
				  								'<td rowspan="3" class="allClub_table_td"><a href="'+U('club/Topic/index',['id='+val.id])+'"><img style="width:60px;height:60px;" src="'+val.logo+'"></a></td>'+
				  								'<td colspan="2" style="width:110px;">'+
													'<table class="allClub_table_td_title">'+
													'<tr>'+
														'<td>'+
															'<a class="allClub_a" href="'+U('club/Topic/index',['id='+val.id])+'" title="'+val.title+'">'+
																'<span class="allClub_title">'+(val.title.length > 6 ? val.title.substring(0,6)+'...':val.title)+'</span>'+
															'</a>'+
														'</td>'+
													'</tr>'+
													'</table>'+
				  								'</td>'+	
				  							'</tr>'+
				  							'<tr>'+
			  									'<td>成&nbsp;&nbsp;&nbsp;&nbsp;员</td>'+
			  									'<td>：'+val.membercount+'人</td>'+
			  								'</tr>'+
			  								'<tr>'+
			  									'<td>创建时间</td>'+
			  									'<td>：'+val.ctime+'</td>'+
			  							'</tr>'+
				  						'</table>'+
				  					'</div>';
					num++;
				});
				num = 1;
				$("#allClub_content").html(club_html);
				//记录当前社团类型
				$("#allClub_type").val(type);
	});
}

function inputCondition(){
	var condition = $("#allClub_condition").val();
	if($.trim(condition) == "请输入您要查找的社团名称"){
		$("#allClub_condition").val("");
	}
}

function searchCondition(){
	var condition = $("#allClub_condition").val();
	if((condition == null) || ($.trim(condition) == "")){
		$("#allClub_condition").val("请输入您要查找的社团名称");
	}
}

function searchAllClub(){
	var condition = $("#allClub_condition").val();
	if($.trim(condition) == "请输入您要查找的社团名称" || $.trim(condition) == ""){
		ui.error("请输入您要查找的社团名称");
		return;
	}
	window.location.href=U('club/Index/searchClub',['title='+condition]);
}

function setTagStyle(type){
	if(type == 1){
		$("#schoolClub").addClass("allClub_selected_tag");
		$("#popularClub").removeClass("allClub_selected_tag");
		$("#popularClub").addClass("allClub_content_tag_index");
		$("#academyClub").removeClass("allClub_selected_tag");
		$("#academyClub").addClass("allClub_content_tag_index");
	}else if(type == 2){
		$("#academyClub").addClass("allClub_selected_tag");
		$("#popularClub").removeClass("allClub_selected_tag");
		$("#popularClub").addClass("allClub_content_tag_index");
		$("#schoolClub").removeClass("allClub_selected_tag");
		$("#schoolClub").addClass("allClub_content_tag_index");
	}else if(type == 3){
		$("#popularClub").addClass("allClub_selected_tag");
		$("#academyClub").removeClass("allClub_selected_tag");
		$("#academyClub").addClass("allClub_content_tag_index");
		$("#schoolClub").removeClass("allClub_selected_tag");
		$("#schoolClub").addClass("allClub_content_tag_index");
	}
}

function showHotTopic(direction){
	var marginleft = parseInt($('.clubEvent_show_window_inner').css('margin-left'));
	var width = parseInt($('.clubEvent_show_info').css('width')) + parseInt($('.clubEvent_show_info').css('margin-left'));
	var widthwindow = parseInt($('.clubEvent_show_window').css('width'));
	var count = widthwindow/width;
	var eventCount = $("#eventCLubCount").val();
	if (!$('.clubEvent_show_window_inner').is(':animated')){
		if(direction == "left"){
			if(marginleft <= -((eventCount - count)*width)){
				return;
			}
			//$('.clubEvent_show_window_inner').css('margin-left',marginleft - width);
			$('.clubEvent_show_window_inner').animate({"margin-left":marginleft - width});
		}else{
			if(marginleft == 0){
				return;
			}
			//$('.clubEvent_show_window_inner').css('margin-left',marginleft + width);
			$('.clubEvent_show_window_inner').animate({"margin-left":marginleft + width});
		}
	}
}

function autoShowClubEvent(){
	var marginleft = parseInt($('.clubEvent_show_window_inner').css('margin-left'));
	var width = parseInt($('.clubEvent_show_info').css('width'));
	var widthwindow = parseInt($('.clubEvent_show_window').css('width'));
	var count = widthwindow/width;
	var eventCount = $("#eventCLubCount").val();
		if(marginleft > -((eventCount -4)*width)){
			showHotTopic("left");
		}
		if(marginleft <= -((eventCount -4)*width)){
			if (!$('.clubEvent_show_window_inner').is(':animated')){
				$('.clubEvent_show_window_inner').animate({"margin-left":0});
			}
		}
}

//启动时钟
var timer1;//全局变量
function startClock1()
{
	timer1 = window.setInterval(autoShowClubEvent1,5000);
}
//停止时钟
function stopClock1()
{
	window.clearInterval(timer1);
}
function showHotTopic1(direction){
	var marginleft = parseInt($('.clubEvent_show_window_inner1').css('margin-left'));
	var width = parseInt($('.clubEvent_show_info1').css('width')) + parseInt($('.clubEvent_show_info1').css('margin-left'));
	var widthwindow = parseInt($('.clubEvent_show_part').css('width'));
	var count = widthwindow/width;
	var eventCount = $("#eventCLubCount1").val();
	if (!$('.clubEvent_show_window_inner1').is(':animated')){
		if(direction == "left"){
			if(marginleft <= -((eventCount - count)*width)){
				return;
			}
			//$('.clubEvent_show_window_inner').css('margin-left',marginleft - width);
			$('.clubEvent_show_window_inner1').animate({"margin-left":marginleft - width});
		}else{
			if(marginleft == 0){
				return;
			}
			//$('.clubEvent_show_window_inner').css('margin-left',marginleft + width);
			$('.clubEvent_show_window_inner1').animate({"margin-left":marginleft + width});
		}
	}
}

function autoShowClubEvent1(){
	var marginleft = parseInt($('.clubEvent_show_window_inner1').css('margin-left'));
	var width = parseInt($('.clubEvent_show_info1').css('width'));
	var widthwindow = parseInt($('.clubEvent_show_part').css('width'));
	var count = widthwindow/width;
	var eventCount = $("#eventCLubCount1").val();
	if(marginleft > -((eventCount -3)*width)){
		showHotTopic1("left");
	}
	if(marginleft <= -((eventCount -3)*width)){
		if (!$('.clubEvent_show_window_inner1').is(':animated')){
			$('.clubEvent_show_window_inner1').animate({"margin-left":0});
		}
	}
}

function delEvent(id, clubid){
	if(confirm("确定要删除该风采吗？")){
		$.post(U('club/Event/delEvent'),{id:id},function(result){
			switch(result){
				case "0":
					ui.error('删除失败');
					break;
				case "1":
					ui.success('删除成功!');
					window.location.href=U('club/Event/index',['id='+clubid]);
					break;
			}
	   });
	}
}

//启动时钟
var timer;//全局变量
function startClock()
{
	timer = window.setInterval(autoShowClubEvent,5000);
}
//停止时钟
function stopClock()
{
	alert("ddd");
	window.clearInterval(timer);
}

function searchClub(){
	var condition = $("#allClub_condition").val();
	if($.trim(condition) == "请输入您要查找的社团名称" || $.trim(condition) == ""){
		ui.error("请输入您要查找的社团名称");
		return;
	}
	window.location.href=U('club/Index/searchClub',['title='+condition]);
}
function EnterPress(e){
	var e = e||window.event;
	if(e.keyCode == 13){
		searchClub();
	}
}

function EnterPress1(e){
	var e = e||window.event;
	if(e.keyCode == 13){
		searchAllClub();
	}
}
//发布公告
function publishNotice(){
	$(".notice_publish").css("display","block");
}
//提交公告
function publishNoticeAction(){
	var club_id = $("#notice_clubid").val();
	var notice_content=$("#club_content").val().trim();
	//content不允许为空
	if(notice_content == null || notice_content.length == 0){
		ui.error("请输入公告内容，再发布！"); 
		return;
	}
	notice_content = notice_content.replace(/<\/?[^>]*>/g,'');
	if( getLength(notice_content)>200 ){
		ui.error("公告内容不能超过200字");
		return false;
	}
	$.post(U('club/notice/saveNotice'),
		{clubid:club_id,content:notice_content},
		function(data){
			if(data != null){
				$(".notice_publish").css("display","none");
				$("#club_content").val('');
				window.location.href=U('club/Notice/index',['id='+club_id]);
			}else{
				ui.error('删除失败');
			}
	});
}
//删除公告
function delNoticeAction(id){
	if(confirm("确定删除该条公告？")){
		$.post(U('club/Notice/delNotice'),{id:id},function(result){
			switch(result){
				case "0":
					ui.error('删除失败');
					break;
				case "1":
					ui.success('删除成功!');
					//$('#notice_one_'+id).remove();
					window.location.reload(); 
					break;
			}
	   });
	}
}
//取消发布公告
function cancelAction(){
	$(".notice_publish").css("display","none");
}

//显示大图片
var picTimer;
function openWindow(id,i)
{
	picTimer = window.setTimeout("showBigPic("+id+","+i+")", 500);
}

function cancelWindow(id,i){
	window.clearTimeout(picTimer);
	showSmallPic(id,i);
}

function showBigPic(id,i){
	$("#myClub_one_info_pic_"+id+"_"+i).addClass("myClub_one_info_pic_hidde");
	$("#myClub_one_info_pic_"+id+"_"+i).removeClass("myClub_one_info_pic_one");
	$("#myClub_one_info_pic_img_"+id+"_"+i).removeClass("myClub_one_info_pic_one_img");
	$("#myClub_one_info_pic_img_"+id+"_"+i).addClass("myClub_one_info_pic_one_img_hidde");
}

//显示小图片
function showSmallPic(id,i){
	$("#myClub_one_info_pic_"+id+"_"+i).removeClass("myClub_one_info_pic_hidde");
	$("#myClub_one_info_pic_"+id+"_"+i).addClass("myClub_one_info_pic_one");
	$("#myClub_one_info_pic_img_"+id+"_"+i).removeClass("myClub_one_info_pic_one_img_hidde");
	$("#myClub_one_info_pic_img_"+id+"_"+i).addClass("myClub_one_info_pic_one_img");
}

function showBanner(){
	var display = $('.show_banner').css('display');
	if(display=='block'){
		var banner = $('.banner_opera_quxiao').attr('cancelbanner');
		$('.banner_content_per>div').css('display','none');
		$('.banner_content_per>div').removeClass('banner_content_select');
		$('.banner_content_per[banner_name='+banner+']>div').addClass('banner_content_select');
		$('.banner_content_per[banner_name='+banner+']>div').css('display','block');
		$('.main_banner').css('background-image',"url('"+_ROOT_+"/apps/club/Tpl/desktop/Public/bgimg/"+banner+".jpg')");
		$('.show_banner').css('display','none');
	}else{
		$('.show_banner').css('display','block');
	}
}

function banner_select(id){
	$('.banner_content_per>div').css('display','none');
	$('.banner_content_per>div').removeClass('banner_content_select');
	$('.banner_content_per[banner_name='+id+']>div').addClass('banner_content_select');
	$('.banner_content_per[banner_name='+id+']>div').css('display','block');
	$('.main_banner').css('background-image',"url('"+_ROOT_+"/apps/club/Tpl/desktop/Public/bgimg/"+id+".jpg')");
}

function nextBanner(direction){
	 var hei = parseInt($(".banner_content_sum").height());
	 var m_top =parseInt($(".banner_content_sum").css('margin-top').replace('px',''));
	 if(direction=='left'){
		 $(".banner_content_sum").css('margin-top',(m_top+108)+'px');
		 m_top =parseInt($(".banner_content_sum").css('margin-top').replace('px',''));
		 if(m_top==108){
			 $(".banner_content_sum").css('margin-top','0px');
		 } 
	 }else if(direction=='right'){
		 $(".banner_content_sum").css('margin-top',(m_top-108)+'px');
		 m_top =parseInt($(".banner_content_sum").css('margin-top').replace('px',''));
		 if((m_top+hei)<0){
			 $(".banner_content_sum").css('margin-top',(m_top+108)+'px');
		 } 
	 }
}

function banner_opera_quxiao(){
	var banner = $('.banner_opera_quxiao').attr('cancelbanner');
	$('.banner_content_per>div').css('display','none');
	$('.banner_content_per>div').removeClass('banner_content_select');
	$('.banner_content_per[banner_name='+banner+']>div').addClass('banner_content_select');
	$('.banner_content_per[banner_name='+banner+']>div').css('display','block');
	$('.main_banner').css('background-image',"url('"+_ROOT_+"/apps/club/Tpl/desktop/Public/bgimg/"+banner+".jpg')");
	$('.show_banner').css('display','none');
}

function banner_opera_queren(id){
	//数据库操作 ajax
	var banner = $('.banner_content_select').attr('banner');
	$.post(U('club/Topic/setBanner'),{banner:banner,id:id},function(result){
		switch(result){
			case "0":
				ui.error('设置失败！');
				break;
			case "1":
				ui.success('设置成功!');
				$('.banner_opera_quxiao').attr('cancelbanner',banner);
				$('.show_banner').css('display','none');
				break;
		}
	});
}