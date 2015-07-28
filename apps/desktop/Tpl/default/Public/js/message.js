
function _message(cookie){
	this.id='messageBubble';
	this.cookie=cookie*1;
	this.zIndex=++_message.zIndex;
	this.width=jQuery('#'+this.id).width();
	this.height=jQuery('#'+this.id).height();
	this.left= getcookie('message_left')!=''?parseInt(getcookie('message_left')) : (_config.screenWidth-this.width)/2;
	this.top=getcookie('message_top')!=''? parseInt(getcookie('message_top')) : _config.desktopHeight-this.height-10;
	this.board=document.getElementById(this.id);
	this.board.style.zIndex=this.zIndex;
	this.popid='messageBubble_bubbleMsgList';
	this.popwidth=jQuery('#messageBubble_bubbleMsgList').width();
	this.popheight=jQuery('#messageBubble_bubbleMsgList').height();
	if((this.top+this.height)>_config.screenHeight) this.top=_config.screenHeight-this.height;
	if((this.left+this.width)>_config.screenWidth) this.left=_config.screenWidth-this.width;
	_message.message=this;
}
_message.zIndex=9999;
_message.delay=3000;
_message.init=function(cookie){
	if(getcookie['noannounce_dzz'] || !document.getElementById('messageBubble')) return;
	var obj=new _message(cookie);
	obj.board.style.left=obj.left+'px';
	obj.board.style.top=obj.top+'px';
	obj.board.style.display='block';
	jQuery('#messageBubble_bubblePanel').bind('mousedown',function(e){_message.message.Mousedown(e?e:window.event);});
	jQuery('#messageBubble_bubblePanel').bind('mouseup',function(e){_message.message.Mouseup(e?e:window.event);});
	jQuery(obj.board).find('.content').bind('click',function(){
		var key=jQuery(this).attr('id').replace('announce_','');
		jQuery('#announce_title').html(this.innerHTML);
		jQuery('#announce_summary').html(jQuery('#announce_summary_data_'+key).html());
		var pos=jQuery('#messageBubble').position();
		if((pos.top-obj.popheight)<32){ jQuery('#messageBubble_bubbleMsgList').css('top',obj.height);}
		else jQuery('#messageBubble_bubbleMsgList').css('top',-210);
		jQuery('#messageBubble_bubbleMsgList').fadeIn('slow');
		jQuery(document).bind('mousedown.temp',function(e){
						//var obj = event.srcElement ? event.srcElement : event.target;
						e=e?e:window.event;
						var obj = e.srcElement ? e.srcElement :e.target;
					if(obj.tagName!='A'){
						jQuery('#messageBubble_bubbleMsgList').hide();
						jQuery(document).unbind('.temp');
					}
				});
			
	});
	_message.intvalTime=setInterval(_message.scrollannounce,_message.delay);
	jQuery(obj.board).bind('mouseover',function(){
		clearInterval(_message.intvalTime);
	});
	jQuery(obj.board).bind('mouseout',function(){
		_message.intvalTime=setInterval(_message.scrollannounce,_message.delay);
	});
	jQuery('#messageBubble_bubblePanel_close').bind('click',function(){
		clearInterval(_message.intvalTime);
		jQuery('#messageBubble').fadeOut('slow');
		var cookie=parseInt(_message.message.cookie);
		setcookie('noannounce_dzz',1,3600*cookie);
	});
	return obj;
}
_message.scrollannounce=function(){
	var el=jQuery('#subject_bubbleContainer');
	
	var top=parseInt(el.css('top'));
	var height=el.height();
	var stepheight=height/el.find('.content').length;
	top-=stepheight;
	if((top+height)<=0) {
		el.css('top',20+'px');el.animate({'top':'0px'},500);
		var key=0;
		jQuery('#announce_title').html(jQuery('#announce_'+key).html());
		jQuery('#announce_summary').html(jQuery('#announce_summary_data_'+key).html());
	}else{
		 el.animate({'top':top+'px'},500);
		 var key=Math.round((Math.abs(top)/stepheight));
		jQuery('#announce_title').html(jQuery('#announce_'+key).html());
		jQuery('#announce_summary').html(jQuery('#announce_summary_data_'+key).html());
	}
}
_message.resize=function(){
	if(_message.message){
		_message.message.board.style.top= _config.desktopHeight-_message.message.height-10+'px';
		_message.message.board.style.left=(_config.screenWidth-this.width)/2+'px';
	}
}

_message.prototype.DetachEvent=function(e)
{
	if(!_message.tach) return;
	document.body.style.cursor="url('dzz/images/cur/aero_arrow.cur'),auto";
	document.onmousemove=_message.onmousemove;
	document.onmouseup=_message.onmouseup;
	document.onselectstart=_message.onselectstart;
	
	if(this.board.releaseCapture) this.board.releaseCapture();
	
	_message.tach=0;
	
};
_message.prototype.AttachEvent=function(e)
{ 
	if(_message.tach) return;
	
	_message.onmousemove=document.onmousemove;
	_message.onmouseup=document.onmouseup;
	_message.onselectstart=document.onselectstart;
	if(e.preventDefault) e.preventDefault();
	else{
		document.onselectstart=function(){return false;};
		if(this.board.setCapture) this.board.setCapture();
	}
	_message.tach=1;
};

_message.prototype.Createblank=function()
{
	//生成桌面层
	document.getElementById('_blank').style.display='';
};
_message.prototype.Focus=function()
{
	if(this.zIndex<_message.zIndex) this.board.style.zIndex=_message.zIndex;
}

_message.prototype.Mousedown=function(e)
{
	this.Focus();
	if(jQuery.browser.msie){
		if(e.button>1) return;
	}else{
		if(e.button>0) return;
	}
	this.mousedowndoing=false;
	
	var XX=e.clientX;
	var YY=e.clientY;

	//alert('down');
	if(!_message.tach) this.AttachEvent(e);
	_message.even=e;
	var self=this;
	this.mousedownTimer=setTimeout(function(){self.PreMove(XX,YY);},200);
}

_message.prototype.Mouseup=function(e)
{

	//alert('mouseup');
	if(_message.tach) this.DetachEvent(e);
	if(!this.mousedowndoing) {
		clearTimeout(this.mousedownTimer);
	
	}else this.Moved(e);
};

_message.prototype.PreMove=function(XX,YY)
{
	if (this.move=="no") return;

	this.Focus();
    
	var self=this;
	this.Createblank();
	this.mousedowndoing=true;
	if (typeof(this.MoveTimer)!="undefined") clearTimeout(this.MoveTimer);
	var p=jQuery(this.board).offset();
	this.tl=XX-p.left;
	this.tt=YY-p.top;
	
	//var XX=e.clientX;
	//var YY=e.clientY;
	
		if (XX>document.body.clientWidth) XX=document.documentElement.clientWidth;
		if(XX<=0) XX=0;
		if (YY>(document.body.clientHeight)) YY=document.documentElement.clientHeight;
		if(YY<=0) YY=0;
		
	
	//document.body.style.cursor="move";
	this.moveX=XX-this.left;
	this.moveY=YY-this.top;
	//alert(_ico.tach);
	//e=getEvent();
	//alert(_ico.even.clientX);
	//document.onmouseup=this.Moved(e);
	if(!_ico.tach) this.AttachEvent(_ico.even);
	document.onmousemove=function(e){self.Move(e?e:window.event);return false;};
	document.onmouseup=function(e){self.Moved(e?e:window.event);return false;};
	
	 return false;
};
_message.prototype.Move=function(e)
{
	if(!e) e=getEvent();
	if(!_message.tach) return;
	var XX=e.clientX;
	var YY=e.clientY;
	if (XX-this.tl+this.width>_config.screenWidth) XX=_config.screenWidth+this.tl-this.width;
	if(XX<=this.tl) XX=this.tl;
	if (YY-this.tt+this.height>_config.screenHeight) YY=_config.screenHeight+this.tt-this.height;
	if(YY-this.tt<=0) YY=this.tt;
	if(this.move!="move-y") {this.board.style.left=(XX-this.tl)+"px";this.left=XX-this.tl;}
	if(this.move!="move-x") {this.board.style.top=(YY-this.tt)+"px";this.top=YY-this.tt;}
};
_message.prototype.Moved=function(e)
{
	document.getElementById('_blank').style.display='none';
	if(!e) e=getEvent();
	setcookie('message_left',this.left,60*60*24*365);
	setcookie('message_top',this.top,60*60*24*365);
	if(_message.tach)	this.DetachEvent(e);
}