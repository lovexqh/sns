$(document).ready(function(){
    $("#top_nav").mouseleave(function() {
    	$(".nav").slideUp(500);
    })
    $('.mess_list').mouseleave(function(){
    	$(".mess_list").hide();
    	$(".mess").css('background-color','');
    });
    $(".setting_list").mouseleave(function(){
    	$(".setting_list").hide();
    	$(".setting").css('background-color','');
    });
    //申请加入
    $('.addI').live("click",function(){
    	var societyId = $(this).attr('sid');
    	ui.box.load(U('society/index/joinSocietyIndex&societyId='+societyId+'&enter='+1),{title : '圈子申请'});
    });
    $('.add').live("click",function(){
        var societyId = $(this).attr('sid');
        ui.box.load(U('society/index/joinSociety&societyId='+societyId+'&enter='+1),{title : '圈子申请'});
    });
    $('a[rel=face]').click(function(){
    	var title = $(this).attr('title');
    	var uid = $(this).attr('uid');
    	top.OpenBrowser (_URL_+_ROOT_+'/@'+uid, title , 'width=1024,height=550,titlebutton=close|max|min');
    });
    $('.manager_operate_top').mouseenter(function() {
    	var topicid = $(this).attr('topicid');
    	$("#operate_menu_top_"+topicid).show();
    })
    $('.manager_operate_top').mouseleave(function() {
    	var topicid = $(this).attr('topicid');
    	$("#operate_menu_top_"+topicid).hide();
    })
    $('.manager_operate').mouseenter(function() {
    	var topicid = $(this).attr('topicid');
    	$("#operate_menu_"+topicid).show();
    })
    $('.manager_operate').mouseleave(function() {
    	var topicid = $(this).attr('topicid');
    	$("#operate_menu_"+topicid).hide();
    }) 
    $(".topic_pic img").click(function(){
		var top_id = $(this).attr('topicid');
		var imgnum = $(this).attr('imgnum');
		$('#topic_'+top_id+" .topic_pic_img").css('display','none');
		$("#bigpic_"+top_id+"_"+imgnum).css('display','inline');
	});
	$(".big_pic_fold").click(function(){
		var top_id = $(this).attr('topicid');
		$('#topic_'+top_id+" .topic_pic_img").css('display','inline');
		$('#topic_'+top_id+" .big_pic_area").css('display','none');
	});
	$(".pic_click_right").click(function(){
		var topicid = $(this).attr('topicid');
		var imgnum = $(this).attr('imgnum');
		if(!topicid || !imgnum){
			ui.error("操作失败!");
			return false;
		}
		var nextnum = parseInt(imgnum) + 1;
		$('#bigpic_'+topicid+'_'+imgnum).hide();
		$('#bigpic_'+topicid+'_'+nextnum).show();
	});
//	document.onclick = function(e){
//		e = e || window.event;
//		var dom = e.srcElement|| e.target;
//		if(dom.class !="_sign" && dom.class !="_icon" && dom.class !="_tags" && dom.class !="_report"){
//			$("._sign").css('display','none');
//			$("._icon").css('display','none');
//			$("._tags").css('display','none');
//			$("._report").css('display','none');
//		}
//	};
	$(".pic_click_left").click(function(){
		var topicid = $(this).attr('topicid');
		var imgnum = $(this).attr('imgnum');
		if(!topicid || !imgnum){
			ui.error("操作失败!");
			return false;
		}
		var lastnum = parseInt(imgnum) - 1;
		$('#bigpic_'+topicid+'_'+imgnum).hide();
		$('#bigpic_'+topicid+'_'+lastnum).show();
	});
	$("a[rel='face']").userCard();
	$('.card_content .info a').live('click',function(){
		var url = $(this).attr('href');
		$(this).attr('href','javascript:void(0);');
		var title = $(this).html() ? $(this).html() : '个人标签';
		top.OpenBrowser(url,title, 'width=1024,height=600,titlebutton=close|max|min');
	});
	$('a[rel=user]').live('click',function(){
    	var title = $(this).attr('title');
    	var uid = $(this).attr('uid');
    	top.OpenBrowser (_URL_+_ROOT_+'/@'+uid, title , 'width=1024,height=550,titlebutton=close|max|min');
    });
});
function block(){
	var none = $(".nav").css('display');
	if(none=='none'){
		$(".nav").slideDown(500);
	}else{
		$(".nav").slideUp(500);
	}
}

function showOI_search_tip(){
	$("#search_tip").css('display','none');
	$('.others .search_bk input[name=societyName]').focus();
} 
function showOI_search_bk_f(){
	$("#search_tip").css('display','none');
}
function showOI_search_bk_b(){
	var text = $(".others .search_bk input[name=societyName]").val();
	text = rightTrim(leftTrim(text));
	if (text == null || text == ""){
		$("#search_tip").css('display','block');
	}
}
function messView_topic_reply(){
	var hei =  $(".ke-textarea-wrap").offset().top;
	window.scrollBy(0,hei-200);
	_KISSY_['messView_content'].focus();
}
function index_fabiao(){
	var hei =  $("#title").offset().top;
	window.scrollBy(0,hei-200)
	$('#title').focus();
}

function top_btn_bS(){
	var societyId = $('input[name=societyId]').val();
	var sign = leftTrim(rightTrim($('textarea[name="sign"]').val()));
	if( !sign ){
		ui.error("圈子签名不能为空!");
		return false;
	}
	if( getBytesCount(sign)>160 ){
		ui.error("圈子签名不能超过160个字符!");
		return false;
	}
	$.post(U('society/Index/ajaxSetting'), {
		societyId : societyId,
		type      : 1,
		content   : sign
	}, function(data) {
		var data = eval('(' + data + ')');
		if(data.status==1){
			location.href=document.location.href;
		}else{
			ui.error(data.msg);
		}
	});
}
function top_btn_bT(){
	var societyId = $('input[name=societyId]').val();
	var tags = leftTrim(rightTrim($('input[name=tags]').val()));
	if( !tags ){
		ui.error("圈子标签不能为空!");
		return false;
	}
	tags = tags.replace(/，/g,',');
	tags = tags.replace(/ /g,'');
	tagsList = tags.split(',');
	if(tagsList.length>5){
		ui.error("圈子标签数最多5个！");
		return false;
	}
	for(var i=0;i<tagsList.length;i++){
		if(!tagsList[i]){
			ui.error("标签中逗号与逗号之间不能为空！");
			return false;
		}
		if(getBytesCount(tagsList[i])>8){
			ui.error("标签中逗号与逗号之间长度不能超过8个字符！");
			return false;
		}
	}
	$.post(U('society/Index/ajaxSetting'), {
		societyId : societyId,
		type      : 2,
		content   : tags
	}, function(data) {
		var data = eval('(' + data + ')');
		if(data.status==1){
			location.href=document.location.href;
		}else{
			ui.error(data.msg);
		}
	});
}

function top_btn_bR(){
	var societyId = $('input[name=societyId]').val();
	var report = leftTrim(rightTrim($('textarea[name="report"]').val()));
	if( !report ){
		ui.error("圈子公告不能为空!");
		return false;
	}
	if( getBytesCount(report)>400 ){
		ui.error("圈子公告不能超过400个字符!");
		return false;
	}
	$.post(U('society/Index/ajaxSetting'), {
		societyId : societyId,
		type      : 3,
		content   : report
	}, function(data) {
		var data = eval('(' + data + ')');
		if(data.status==1){
			location.href=document.location.href;
		}else{
			ui.error(data.msg);
		}
	});
}
function messView_commentbutton(){
	var content   = getEditorContent('messView_content');
	var societyid = $("#societyid").val();
	var messageid = $("#messageid").val();
	var uid       = $("#uid").val();
	var toid      = $("#toid").val();
	var touid     = $("#touid").val();
	$.post(U('society/Index/addComment'),{p_societyid:societyid,p_messageid:messageid,p_content:content,p_uid:uid,p_toid:toid,p_touid:touid},function(data){
		var data = eval('(' + data + ')');
		if(data.status==0){
			window.location.reload();
		}else{
			ui.error(data.msg);
		}
	});
}

function messView_delete_son(id){
	if (confirm('确定删除该评论？')) {
		var sonid = id;
		$.post(U('society/Index/deleteSon'),{sonid:sonid},function(data){
			var data = eval('(' + data + ')');
			if(data.status==0){
				location.href=document.location.href;
			}else{
				ui.error(data.msg);
			}
		});
	}
}

function messView_reply1_pub_btn(id){
	var content   = $("#reply1_edit_"+id).html();
	var societyid = $("#societyid").val();
	var messageid = $("#messageid").val();
	var touid     = $(".reply1_pub_btn[replyid="+id+"]").attr("toid");
	var uid       = $("#uid").val();
	if(leftTrim(rightTrim(content))==''){
		ui.error('评论内容不能为空！');
		return false;
	}
	$.post(U('society/Index/addCommentSon'),{p_societyid:societyid,p_messageid:messageid,p_content:content,p_uid:uid,p_toid:id,p_touid:touid},function(data){
		var data = eval('(' + data + ')');
		if(data.status==0){
			window.location.reload();
		}else{
			ui.error(data.msg);
		}
	});
}
function top_nav_forward(){
	var hei = parseInt($(".nav_center div").height());
    var m_top =parseInt($(".nav_center div").css('margin-top').replace('px',''));
    $(".nav_center div").css('margin-top',(m_top-136)+'px');
    m_top =parseInt($(".nav_center div").css('margin-top').replace('px',''));
    if((m_top+hei)<=0){
        $(".nav_center div").css('margin-top','0px');
    }
}
function top_nav_back(){
	var hei = parseInt($(".nav_center div").height());
    var m_top =parseInt($(".nav_center div").css('margin-top').replace('px',''));
    $(".nav_center div").css('margin-top',(m_top+136)+'px');
    m_top =parseInt($(".nav_center div").css('margin-top').replace('px',''));
    if(m_top>0){
        var j = Math.floor(hei/136);
        if(j*136==hei){
            $(".nav_center div").css('margin-top','-'+(j-1)*136+'px');
        }else{
            $(".nav_center div").css('margin-top','-'+j*136+'px');
        }
    }
}

function top_close_sign(){
	$('._tags').hide();
	$('._icon').hide();
	$('._sign').hide();
}
function right_close_report(){
	$('.editRep').hide();
}
function top_mess(){
	$(".mess_list").show();
	$(".setting_list").css('display','none');
	$(".setting").css('background-color','');
	$(".mess").css('background-color','#4582C5');
}

function checkUs(){
	$("#tip_sUser").css('display','none');
	$('.open .search_user input[name=userName]').focus();
}
function addT(){
	var text = $(".open .search_user input[name=userName]").val();
	text = rightTrim(leftTrim(text));
	if (text == null || text == ""){
		$("#tip_sUser").css('display','block');
	}
}

function leftTrim(text) {
    if (text == null || text == "")
        return text;// 如果text无内容,返回text
    var leftIndex = 0;// 定义最左非空格字符的索引下标(空格字符数)
    while (text.substring(leftIndex, leftIndex + 1) == " ")
        // 直至找到最左的非空格的字符,要么进行
        leftIndex++;// 最右非空格字符的索引下标后移
    return text.substring(leftIndex, text.length);// 返回
}

function rightTrim(text) {
    if (text == null || text == "")
        return text;// 如果text无内容,返回text
    var rightIndex = text.length;// 定义最右非空格字符的索引下标
    while (text.substring(rightIndex - 1, rightIndex) == " ")
        // 直至找到最右的非空格的字符,要么进行
        rightIndex--;// 最右非空格字符的索引下标前移
    return text.substring(0, rightIndex);// 返回
}

function showMyMess(){
    var societyId = $("input[name=societyId]").val();
    ui.box.load(U('society/index/showMyMess&societyId='+societyId), {title : '我的消息'});
}

function showOthers(){
    ui.box.load(U('society/index/showOthers'), {title : '查看更多圈子'});
}

function openSociety(){
    ui.box.load(U('society/index/openSociety'), {title : '创建圈子'});
}

function inviteFriends(){
    ui.box.load(U('society/index/societyList'), {title : '邀请加入'});
}

function show_news(societyId){
	ui.box.load(U('society/index/more&societyId='+societyId), {title : '圈子消息'});
}

function more(){
    var societyId = $("input[name=societyId]").val();
    ui.box.load(U('society/index/more&societyId='+societyId), {title : '圈子消息'});
}

function setting(){
    var societyId = $("input[name=societyId]").val();
    ui.box.load(U('society/index/setting&societyId='+societyId), {title : '圈子设置'});
}

function member(){
    var societyId = $("input[name=societyId]").val();
    ui.box.load(U('society/index/memberManager&societyId='+societyId), {title : '成员管理'});
}

function yaoqingMember(id){
	ui.box.load(U('society/Index/yaoqingMember&societyId='+id),{title : '邀请加入'});
}

function sendEmail(name){
	var societyId = $("input[name=societyId]").val();
	ui.box.load(U('society/Index/sendEmail&name='+name+'&societyId='+societyId),{title : '邀请注册'});
}

function tuichuquanzi(){
    var societyId = $("input[name=societyId]").val();
    ui.box.load(U('society/index/tuichuquanzi&societyId='+societyId), {title : '退出圈子'});
}

//文档下载次数
function downLoad(id){
	var count = $(".s_downCount_"+id).html();
	$(".s_downCount_"+id).html(parseInt(count)+1);
	window.location.href = (U('society/Index/download&id='+id+'&enter=1'));
	
}

//文档删除
function deleteShare(id,societyId){
    if (confirm('确定要删除该文档吗？')) {
        $.post(U('society/Index/deleteShare'), {
            id : id
        }, function(result) {
        	window.location.href = (U('society/Index/share&societyId='+societyId));
//            $("#"+id).remove();
//            $("#list>li").removeClass('odd');
//            $("#list>li:odd").addClass('odd');
        });
    }
}

//新鲜事删除
function delTopic(id,societyId,uid,M_uid){
	if (confirm('确定要删除该帖子吗？')) {
		$.post(U('society/Index/deleteMessage'), {
			id : id,
			societyId : societyId
		}, function(data) {
			 var data = eval('(' + data + ')');
             if(data.status==0){
//                 $("#topic_"+id).remove();
            	 window.location.href = (U('society/Index/index&societyId='+societyId));
//                 var count = $('.total_center span:first').html();
//                 var My_count = $('.total_center span:last').html();
//                 $('.total_center span:first').html(parseInt(count)-1);
//                 if(uid==M_uid){
//                	 $('.total_center span:last').html(parseInt(My_count)-1);
//                 }
             }else{
            	 ui.error(data.msg);
             }
		});
	}
}

//删除印象
function delWall(id,societyId){
	if (confirm('确定要删除该印象吗？')) {
		$.post(U('society/Index/deleteWall'), {
			id : id,
			societyId : societyId
		}, function(data) {
			var data = eval('(' + data + ')');
			if(data.status==0){
				window.location.href = (U('society/Index/wall&societyId='+societyId));
			}else{
				ui.error(data.msg);
			}
		});
	}
}

//通过申请
function top_tongGuoNews(newsId,societyId){
	$.post(U('society/Index/tongGuoNews'),{ newsId : newsId , societyId : societyId},function(data){
		var data = eval('(' + data + ')');
        if(data.status==1){
        	$('li[name='+newsId+']').hide();
      		$('li[name='+newsId+']').remove();
      		var count = $('.nav_right li:first span').html();
      		$('.nav_right li:first span').html(count-1);
      		var s_count = $('.nav_center #s_id_'+societyId+' span').html();
      		$('.nav_center #s_id_'+societyId+' span').html(s_count-1)
        }else if(data.status==-99){
        	ui.error(data.msg);
            $('li[name='+newsId+']').hide();
      		$('li[name='+newsId+']').remove();
      		var count = $('.nav_right li:first span').html();
      		$('.nav_right li:first span').html(count-1);
      		var s_count = $('.nav_center #s_id_'+societyId+' span').html();
      		$('.nav_center #s_id_'+societyId+' span').html(s_count-1)
        }else{
        	ui.error(data.msg);
        }
	});
}

//滚动管理员
function autoShowManager(){
	var wid = parseInt($(".manager ul").width());
    var m_left =parseInt($(".manager ul").css('margin-left').replace('px',''));
    if((m_left-270+wid)<=0){
    	$('.manager ul').animate({"margin-left":0});
    }else{
    	$('.manager ul').animate({"margin-left":m_left-270});
    }
}
//获取字节长度
function getBytesCount(str){
	var bytesCount = 0;
	if (str != null){
		for (var i = 0; i < str.length; i++){
			var c = str.charAt(i);
			if (/^[\u0000-\u00ff]$/.test(c)){
				bytesCount += 1;
			}else{
				bytesCount += 2;
			}
		}
	}
	return bytesCount;
}
//获取字节长度
function getLen(a,cc){
	cc=parseInt(cc);
	var i=0,count=0;
	while(i<a.length){
		if (a.charCodeAt(i)>0 && a.charCodeAt(i)<255){
			count++;
		}else
			count+=2;
		if(count<=cc)
			i++;
		else break;
	}
	if(getBytesCount(a)>cc){
		return a.substr(0,i)+'...'; 
	}else{
		return a.substr(0,i); 
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

function ckeckIcon(){
	var icon = $('._icon input[name=icon]').val();
	if(icon==null||icon==''){
		ui.error('您没有选择文件！');
		return false;
	}
	$('._icon form').submit();
}

function top_setting(){
	$(".setting_list").show();
	$(".mess_list").css('display','none');
	$(".mess").css('background-color','');
	$(".setting").css('background-color','#4582C5');
}

function wall_tip(){
	$(".tip").css('display','none');
	$(".wall_input>input").focus();
}

function wall_wall_input(){
	var text = $(".wall_input>input").val();
	text = rightTrim(leftTrim(text));
	if (text == null || text == ""){
		$(".tip").css('display','block');
	}
}

function wall_input_a(){
	var content = $("input[name=content]").val();
	var societyId = $("input[name=societyId]").val();
	content = rightTrim(leftTrim(content));
	if (content == null || content == ""){
		if(getBytesCount(content)>100){
			ui.error('最多可输入100个字符！');
		}else{
			ui.error("您还没有输入任何内容！！");
		}
	}else{
		$.post(U('society/Index/addWall'), {
			societyId : societyId,
			content : content
		}, function(result) {
			var data = eval('(' + result + ')');
			if (data.msg>0) {
				$(".wall_input>input").val('');
				$(".tip").css('display','block');
				window.location.href = (U('society/Index/wall&societyId='+societyId));
			}else{
				ui.error(data.msg);
			}
		});
	}
}

function index_linkbuttons(){
	var title = $("#title").val();
	title = rightTrim(leftTrim(title));
	if(title.length>25){
		ui.error('标题不得大于25个字符！');
		return false;
	}
	var content = getEditorContent('index_content');
	var societyId = $("#societyid").val();
	$.post(U('society/Index/addMessage'),{
		p_title:title,
		p_content:content,
		p_societyId:societyId,
		id:''
	},function(data){
		var data = eval('(' + data + ')');
		if(data.status==0){
			window.location.href = (U('society/Index/index&societyId='+societyId));
			$('#title').val('');
		}else{
			ui.error(data.msg);
		}
	});
}
//点击修改圈子签名
function top_sign(e){
	$('.editRep').hide();
	$('._tags').hide();
	$('._icon').hide();
	var sign = $('._sign textarea[name=sign]').html();
	var sign1 = $('._sign textarea[name=sign]').val();
	$('._sign').show();
	$('._sign textarea[name=sign]').val(sign);
}
function top_tags(e){
	$('._icon').hide();
	$('._sign').hide();
	$('.editRep').hide();
	var sign = $('._tags input[name=tags]').attr('pro_val');
	$('._tags').show();
	$('._tags input[name=tags]').val(sign);
}
function top_report(e){
	$('._icon').hide();
	$('._sign').hide();
	$('._tags').hide();
	var sign = $('.editRep textarea[name=report]').html();
	var sign1 = $('.editRep textarea[name=report]').val();
	$('.editRep').show();
	$('.editRep textarea[name=report]').val(sign);
}
function top_edit_icon(e){
	$('._sign').hide();
	$('._tags').hide();
	$('.editRep').hide();
	$('._icon').show();
}

(function($) {
	$.fn.userCard = function(options) {
		var defaults = {
			status : 'on',
			//小名片模版
			cardTpl : '<div class="card_layer"><div class="bg"><div class="effect">'
					+ '<table><tbody><tr><td>'
					+ '<div class="card_content">'
					+ '<div class="card_name clearfix">'
					+ '<dl class="name clearfix">'
					+ '<dt><img class="picborder_r" title="{uname}" uid="{uid}" imgtype="head" src="{face}"></dt>'
					+ '<dd>'
					+ '<p>{space_link}{realname}</p>'
					+ '<p>{location}</p>'
					+ '<div>'
					+ '<ul class="userdata">'
					+ '<li><a href="javascript:void(0);" onclick="top.OpenBrowser (\'{following_url}\',\'我关注\', \'width=1024,height=550,titlebutton=close|max|min\');">我关注</a>({followed_count})</li>'
					+ '<li><a href="javascript:void(0);" onclick="top.OpenBrowser (\'{follower_url}\',\'关注我\', \'width=1024,height=550,titlebutton=close|max|min\');">关注我</a>({followers_count})</li>'
					+ '<li><a href="javascript:void(0);" onclick="top.OpenBrowser (\'{space_url}\',\'微广播\', \'width=1024,height=550,titlebutton=close|max|min\');">微广播</a>({weibo_count})</li>'
					+ '</ul>'
					+ '</div>'
					+ '</dd>'
					+ '</dl>'
					+ '<dl class="info clearfix">'
					+ '<dt></dt>'
					+ '<dd>'
					+ '<ul>'
					+ '<li></li>'
					+ '</ul>'
					+ '</dd>'
					+ '<dd>个人标签：{tags}</dd>'
					+ '</dl>'
					+ '<div class="links clearfix" id="space_card_toolbar"><br /></div>'
					+ '</div>'
					+ '</div>'
					+ '</td></tr></tbody></table>'
					+ '<div id="space_card_arrow" class="arrow arrow_b"></div></div></div></div>'
		}
		var options = $.extend(defaults, options);
		var windows = $(window).width();
		var _this = this;
		var element;
		_this.t = null;
		_this.v = null;

		//初始化小名片
		if (!element) {
			element = $("<div/>").css({
				display : 'none',
				position : 'absolute',
				'background-color' : 'white',
				'z-index' : 9999999
			}).attr('id', 'space_card_content').appendTo(document.body)
					.bind('mouseenter', function() {
						clearTimeout(_this.v);
					}).bind('mouseleave', function() {
						$(this).hide();
					}).show();
		}

		//小名片展示
		var userInfoList = [], postion;
		$(this).live('mouseover',function() {
							//如果链接的class含有 nocard 则不弹出小名片.
							if ($(this).attr('class').indexOf('nocard') >= 0)
								return false;

							clearTimeout(_this.v);
							var width = $(this).width();
							var height = $(this).height();
							var position = $(this).offset();
							position.width = width;
							position.height = height;

							uid = $(this).attr('uid');
							if (!uid)
								return;
							_this.t = setTimeout(
									function() {
										$('#space_card_content')
												.html(
														'<div class="card_layer"><img src="'+  _PUBLIC_ +'/js/tbox/images/icon_waiting.gif" width="20"></div>')
												.show();
										if (uid) {
											//if( userInfoList[uid] ){
											//	_this.parseShow(userInfoList[uid],position.top);
											//}else{
											_this.ajax = $.getJSON(
												U('home/Space/showSpaceCard',['uid='+ uid ]),
												function(result) {
													if (result.status) {
														userInfoList[uid] = result.data;
														_this.parseShow(userInfoList[uid],position);
													}
												});
											//}
										}
										;
									}, 200);
						}).live('mouseout', function(event) {
					clearTimeout(_this.t);
					_this.v = setTimeout(function() {
						if (_this.ajax)
							_this.ajax.abort();
						element.hide();
					}, 100);
				});

		_this.parseShow = function(data, position) {

			//渲染小名片
			parseHtml = options['cardTpl'].replace(/\{(.+?)}/gi, function(
					s, t) {
				var type = s.replace('{', '').replace('}', '');
				return data[type];
			});
			$('#space_card_content').html(parseHtml);

			//小名片的位置.在顶端朝下.在右侧朝左.
			var scrollTop = $(document).scrollTop();
			var windowWidth = $(window).width();
			//当前元素距离顶部的距离
			var cardTop = position.top - scrollTop;
			//当前元素距离右侧的距离
			var cardRight = windowWidth - position.left;
			//设置小名片位置 - 朝左
			if (cardRight < 400) {
				element.css({
					top : position.top + 145,
					left : position.left - 320
				}).show();
				$('#space_card_arrow').removeClass('arrow_b').addClass(
						'arrow_r');
				//设置小名片位置 - 朝下
			} else if (cardTop < 300) {
				element.css({
					top : position.top + 155 + position.height,
					left : position.left + 5
				}).show();
				$('#space_card_arrow').removeClass('arrow_b').addClass(
						'arrow_t');
				//设置小名片位置 - 朝上
			} else {
				element.css({
					top : position.top,
					left : position.left
				}).show();
			}

			var isLogin = "1";
			if (isLogin) {
				//渲染关注按钮
				if (data['follow_state'] == 'unfollow') {
					$('#space_card_toolbar').html('<span><a href="javascript:;" class="btn_b" onclick="space_card_dofollow(\'dofollow\','
											+ uid
											+ ',\''
											+ data['uname']
											+ '\')">加关注</a></span>');
				} else if (data['follow_state'] == 'havefollow') {
					$('#space_card_toolbar').html('<span>已关注 | <a href="javascript:;" onclick="space_card_dofollow(\'unflollow\','
											+ uid
											+ ',\''
											+ data['uname']
											+ '\')">取消</a></span>'
											+ '<a href="javascript:void(0);" onclick="ui.sendmessage('
											+ uid
											+ ');$(\'#space_card_content\').hide();">发私信</a>');
				} else if (data['follow_state'] == 'eachfollow') {
					$('#space_card_toolbar').html( '<span>互相关注 | <a href="javascript:;" onclick="space_card_dofollow(\'unflollow\','
											+ uid
											+ ',\''
											+ data['uname']
											+ '\')">取消</a></span>'
											+ '<a href="javascript:void(0);" onclick="ui.sendmessage('
											+ uid
											+ ');$(\'#space_card_content\').hide();">发私信</a>');
				}
			}
		}
	};
})(jQuery);

function space_card_dofollow(type, uid, username) {
	var html = '';
	$('#space_card_toolbar').html(
			'<img src="'+ _THEME_+'/images/icon_waiting.gif" width="15">');
	$
			.post(
					U('weibo/Operate/follow'),
					{
						uid : uid,
						type : type
					},
					function(txt) {
						if (txt == '12') {
							$('#space_card_toolbar')
									.html(
											'<span>已关注 | <a href="javascript:;" onclick="space_card_dofollow(\'unflollow\','
													+ uid
													+ ',\''
													+ username
													+ '\')">取消</a></span>'
													+ '<a href="javascript:void(0);" onclick="ui.sendmessage('
													+ uid
													+ ');$(\'#space_card_content\').hide();">发私信</a>');
							//userInfoList[uid]['follow_state'] = 'havefollow';
							followGroupSelectorBox(uid);
						} else if (txt == '13') {
							$('#space_card_toolbar')
									.html(
											'<span>互相关注 | <a href="javascript:;" onclick="space_card_dofollow(\'unflollow\','
													+ uid
													+ ',\''
													+ username
													+ '\')">取消</a></span>'
													+ '<a href="javascript:void(0);" onclick="ui.sendmessage('
													+ uid
													+ ');$(\'#space_card_content\').hide();">发私信</a>');
							//userInfoList[uid]['follow_state'] = 'eachfollow';
							followGroupSelectorBox(uid);
						} else if (txt == '01') {
							$('#space_card_toolbar')
									.html(
											'<span><a href="javascript:;" class="btn_b" onclick="space_card_dofollow(\'dofollow\','
													+ uid
													+ ',\''
													+ username
													+ '\')">加关注</a></span>');
							//userInfoList[uid]['follow_state'] = 'unfollow';
						} else if (txt == '14') {
							ui.error('关注人数已超过配置最大数量，关注失败！');
						} else {
							ui.error('系统错误，请稍后再试！');
						}
					});
}
//绑定如下格式的a标签-可以显示小名片 <a href="#" rel="face" uid="1">##</a>
if(typeof top.OpenBrowser == "function"){
	$('.userdata a').each(function(i, e) {
		//判断是不是click
		if(typeof($(this).attr('onclick'))!='undefined'){
			$(this).attr('href','javascript:;');
		}
		if(typeof($(this).attr('href'))!='undefined' 
			&& $(this).attr('href').indexOf('javascript:')==-1){
			
			//分页时
			if(typeof($(this).parent().attr('class'))!='undefined' && $(this).parent().attr('class').indexOf('page')!=-1){
	
			}else if($(this).attr('target') == '_self'){
				
			}else{
				var url = $(this).attr('href');
				var title = $.trim($(this).attr('title'))!='' ? $.trim($(this).attr('title')) : $.trim($(this).text());
				if(title=='') title = $(document)[0].title;
				$(this).attr('onclick',"top.OpenBrowser ('"+url+"','"+title+"', 'width=1024,height=600,titlebutton=close|max|min');");
				$(this).attr('href','javascript:;');
				$(this).remove('target');
			}
		}
	});
}