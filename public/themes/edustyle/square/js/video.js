// Flash Player
var isIE  = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;
var isWin = (navigator.appVersion.toLowerCase().indexOf("win") != -1) ? true : false;
var isOpera = (navigator.userAgent.indexOf("Opera") != -1) ? true : false;
var isSafari = (navigator.userAgent.indexOf("Safari") != -1) ? true : false;

//是否为移动端
var isMobile = {  
    Android: function() {  
        return navigator.userAgent.match(/Android/i) ? true : false;  
    },  
    BlackBerry: function() {  
        return navigator.userAgent.match(/BlackBerry/i) ? true : false;  
    },  
    iOS: function() {  
        return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false;  
    },  
    Windows: function() {  
        return navigator.userAgent.match(/IEMobile/i) ? true : false;  
    },  
    any: function() {  
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Windows());  
    }  
};

//显示视频
function showplayer(divid, w, h, swffile, thumbfile){
	var temp = swffile.replace('http://','');
	var host = temp.substring(0,temp.indexOf('/'));

	var content ='';
	if( isMobile.any() ){
		if(host.indexOf(':')){
			host = host.substring(0,host.indexOf(':'));
			host += ":1935";
		}
		swffile = "http://"+host+"/vod/_definst_"+temp.substring(temp.indexOf('/'));
	
		content = "<video src=\""+swffile+"/playlist.m3u8\" controls autoplay poster = \""+thumbfile+"\" width=\""+w+"\" height=\""+h+"\"></video>";
	}else{
		content="<object tabindex=\"0\" name=\"mediaplayer\" id=\"mediaplayer\" bgcolor=\"#000000\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" height=\""+h+"\" width=\""+w+"\">"+
				"<param name=\"_cx\" value=\"12700\" \/>"+
				"<param name=\"_cy\" value=\"7143\" \/>"+
				"<param name=\"Movie\" value=\""+_THEME_+"/images/player.swf\" \/>"+
				"<param name=\"Src\" value=\""+_THEME_+"/images/player.swf\" \/>"+
				"<param name=\"Play\" value=\"0\" \/>"+
				"<param name=\"Loop\" value=\"-1\" \/>"+
				"<param name=\"Quality\" value=\"High\" \/>"+
				"<param name=\"SAlign\" value=\"LT\" \/>"+
				"<param name=\"Menu\" value=\"-1\" \/>"+
				"<param name=\"Base\" value=\"\" \/>"+
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
				"<param name=\"flashvars\" value=\"skin="+_THEME_+"/images/beelden.zip&netstreambasepath=&id=mediaplayer&file="+swffile+"&controlbar.position=bottom&autostart=true\" \/>"+
				"<embed src=\""+_THEME_+"/images/player.swf\" type=\"application\/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" wmode=\"opaque\" width=\""+w+"\" height=\""+h+"\" flashvars=\"skin="+_THEME_+"/images/beelden.zip&netstreambasepath=&id=mediaplayer&file="+swffile+"&controlbar.position=bottom&autostart=true\"></embed>"+
				"<\/object>";
	}
	document.getElementById(divid).innerHTML=content;
}

$(document).ready(function(){
	
//清空评论输入框的默认内容
	$(".comInput").focus(function(event){
//		alert("nihao");
		if($(this).val() == "文明上网，登陆评论！"){
			$(this).empty();
		}
	});
}); 
function url_encode(str,is_binary){var result="";var i=0;var x;var shiftreg=0;var count=-1;for(i=0;i<str.length;i++){c=str.charAt(i);if('A'<=c&&c<='Z')x=str.charCodeAt(i)-65;else if('a'<=c&&c<='z')x=str.charCodeAt(i)-97+26;else if('0'<=c&&c<='9')x=str.charCodeAt(i)-48+52;else if(c=='+')x=62;else if(c=='/')x=63;else continue;count++;switch(count%4){case 0:shiftreg=x;continue;case 1:v=(shiftreg<<2)|(x>>4);shiftreg=x&0x0F;break;case 2:v=(shiftreg<<4)|(x>>2);shiftreg=x&0x03;break;case 3:v=(shiftreg<<6)|(x>>0);shiftreg=x&0x00;break}if(!is_binary&&(v<32||v>126)&&(v!=0x0d)&&(v!=0x0a)){result=result+"<";result=result+"0123456789ABCDEF".charAt((v/16)&0x0F);result=result+"0123456789ABCDEF".charAt((v/1)&0x0F);result=result+">"}else result=result+String.fromCharCode(v)}return result.toString()}