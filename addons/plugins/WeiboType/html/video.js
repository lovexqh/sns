// Flash Player
var isIE  = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;
var isWin = (navigator.appVersion.toLowerCase().indexOf("win") != -1) ? true : false;
var isOpera = (navigator.userAgent.indexOf("Opera") != -1) ? true : false;
var isSafari = (navigator.userAgent.indexOf("Safari") != -1) ? true : false;

jQuery.extend(weibo.plugin, {
	video:function(element, options){
	   
	    
	}
});

jQuery.extend(weibo.plugin.video, {
    type:'新浪播客、优酷网、土豆网、酷6网、搜狐',
	html:function(){
	    return '<dl id="video_input" class="layer_upload_video"><dt>请输入'+weibo.plugin.video.type+'的视频播放页链接： </dt><dd><input name="publish_type_data" type="text" style="width: 320px" class="text mr5" value="" /><input type="button" class="btn_b" onclick="weibo.plugin.video.add_video()" value="添加"></dd></dl><div style="display:none"    id="video_add_complete">添加完成</div>';
	},
	click:function(options){
	   weibo.publish_type_box(this.html(),options)
	},
	add_video:function(){
		var video_url = $("input[name='publish_type_data']").val();
		$.post( U('home/widget/addonsRequest'),{addon:'WeiboType',hook:'paramUrl',url:video_url},function(txt){
			txt = eval('('+txt+')');
			if(txt.boolen){
				$('#video_input').hide();
				$('#video_add_complete').show();
				$("#content_publish").val( $("#content_publish").val( ) + ' ' + txt.data + ' ');
                weibo.publish_type_val(txt.publish_type);
                weibo.checkInputLength(_LENGTH_);
				$('div .talkPop').hide();
			}else{
				ui.error("只支持"+weibo.plugin.video.type);
			}
		})
	}
});

function switchVideo(id,type,host,flashvar){
	if( type == 'close' ){
		$("#video_mini_show_"+id).show();
		$("#video_content_"+id).html( '' );
		$("#video_show_"+id).hide();
	}else{
		$("#video_mini_show_"+id).hide();
		$("#video_content_"+id).html( showFlash(host,flashvar) );
		$("#video_show_"+id).show();
	}
}

//显示视频
function showFlash( host, flashvar) {
	var flashAddr = {
		'youku.com' : 'http://player.youku.com/player.php/sid/FLASHVAR/v.swf',
		'ku6.com' : 'http://player.ku6.com/refer/FLASHVAR/v.swf',
		'sina.com.cn' : 'http://you.video.sina.com.cn/api/sinawebApi/outplayrefer.php/vid=FLASHVAR/s.swf',
		'tudou.com' : 'http://www.tudou.com/v/FLASHVAR/&autoPlay=true/v.swf',
		'youtube.com' : 'http://www.youtube.com/v/FLASHVAR',
		'5show.com' : 'http://www.5show.com/swf/5show_player.swf?flv_id=FLASHVAR',
		'sohu.com' : 'http://share.vrs.sohu.com/FLASHVAR/v.swf',
		'mofile.com' : 'http://tv.mofile.com/cn/xplayer.swf?v=FLASHVAR',
		'yinyuetai.com' : 'http://player.yinyuetai.com/video/player/FLASHVAR/v_0.swf',
		'music' : 'FLASHVAR',
		'flash' : 'FLASHVAR'
	};
	var videoFlash = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="430" height="400">'
        + '<param value="transparent" name="wmode"/>'
		+ '<param value="FLASHADDR" name="movie" />'
		+ '<embed src="FLASHADDR" wmode="transparent" allowfullscreen="true" type="application/x-shockwave-flash" width="430" height="400"></embed>'
		+ '</object>';
	var newWideo = 	"<object tabindex=\"0\" name=\"mediaplayer\" id=\"mediaplayer\" bgcolor=\"#000000\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" height=\"390\" width=\"445\">"+
					"<param name=\"_cx\" value=\"12700\" \/>"+
					"<param name=\"_cy\" value=\"7143\" \/>"+
					"<param name=\"Movie\" value=\""+_THEME_+"/images/player.swf\" \/>"+
					"<param name=\"Src\" value=\""+_THEME_+"/images/player.swf\" \/>"+
					"<param name=\"WMode\" value=\"Opaque\" \/>"+
					"<param name=\"Play\" value=\"0\" \/>"+
					"<param name=\"Loop\" value=\"-1\" \/>"+
					"<param name=\"Quality\" value=\"High\" \/>"+
					"<param name=\"SAlign\" value=\"LT\" \/>"+
					"<param name=\"Menu\" value=\"-1\" \/>"+
					"<param name=\"Base\" value=\"\" \/>"+
					"<param name=\"AllowScriptAccess\" value=\"always\" \/>"+
					"<param name=\"Scale\" value=\"NoScale\" \/>"+
					"<param name=\"DeviceFont\" value=\"0\" \/>"+
					"<param name=\"EmbedMovie\" value=\"0\" \/>"+
					"<param name=\"BGColor\" value=\"\" \/>"+
					"<param name=\"SWRemote\" value=\"\" \/>"+
					"<param name=\"MovieData\" value=\"\" \/>"+
					"<param name=\"SeamlessTabbing\" value=\"1\" \/>"+
					"<param name=\"Profile\" value=\"0\" \/>"+
					"<param name=\"ProfileAddress\" value=\"\" \/>"+
					"<param name=\"ProfilePort\" value=\"0\" \/>"+
					"<param name=\"AllowNetworking\" value=\"all\" \/>"+
					"<param name=\"AllowFullScreen\" value=\"true\" \/>"+
					"<param name=\"AllowFullScreenInteractive\" value=\"\" \/>"+
					"<param name=\"IsDependent\" value=\"0\" \/>"+
					"<param name=\"allowfullscreen\" value=\"true\" \/>"+
					"<param name=\"allowscriptaccess\" value=\"always\" \/>"+
					"<param name=\"seamlesstabbing\" value=\"true\" \/>"+
					"<param name=\"wmode\" value=\"opaque\" \/>"+
					"<param name=\"flashvars\" value=\"skin="+_THEME_+"/images/beelden.zip&netstreambasepath=&id=mediaplayer&file=FLASHVAR&controlbar.position=bottom&autostart=true\" \/>"+
					"<embed src=\""+_THEME_+"/images/player.swf\" type=\"application\/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" wmode=\"opaque\" width=\"430\" height=\"400\" flashvars=\"skin="+_THEME_+"/images/beelden.zip&netstreambasepath=&id=mediaplayer&file=FLASHVAR&controlbar.position=bottom&autostart=true\"></embed>"+
					"<\/object>";
	
	var flashHtml = videoFlash;

	flashvar = encodeURI(flashvar);
	if(flashAddr[host]) {
		var flash = flashAddr[host].replace('FLASHVAR', flashvar);
		flashHtml = flashHtml.replace(/FLASHADDR/g, flash);
	}else{
		flashHtml = newWideo;
		flashHtml = flashHtml.replace(/FLASHVAR/g, flashvar);
	}

	return flashHtml;
}
