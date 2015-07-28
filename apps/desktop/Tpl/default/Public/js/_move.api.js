// ******************  dzzjs V1.00  ******************
// 作者：dzzjs1.0
// 版本：1.00
// 网站：http://www.dzz.cc
// 邮件：admin@dzz.cc
// 版权：版权归dzz.cc所有,任何人未经允许，不得使用和修改此代码
// **********************************************************
// ----------------------------------------------------------
function fixpng(obj) {
			if(jQuery.browser.msie && jQuery.browser.version>0 && jQuery.browser.version<7){
				var png = obj.src;	
				obj.onload=null;
				obj.src = 'dzz/images/b.gif';
				obj.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + png + "',sizingMethod='scale')";
			}
	};
function _move(obj,win){
	
	if(obj.id){
		this.id=this.name="_M_"+obj.id;
	}else{
		this.id=this.name="_M_"+(++_move.mIndex);
	}
	this.string="_move.moves."+this.id;
	this.board=obj;
	
	this.win=win;
	_move.moves[this.id]=this;
}
_move.mIndex=0;
_move.moves={};
_move.onmousemove=null;
_move.onmouseup=null;
_move.tach=null;
_move.create=function(obj){
	var wname = window.name;
	if (wname == "") throw Error("window name error!");
	wname=jQuery.evalJSON(decodeURIComponent(wname));
	var winid=wname.winid;
	if(top._window.windows[winid]) win=top._window.windows[winid];
	else if(top._widget.widgets[winid]) win=top._widget.widgets[winid];
	if(win){
		var obj1=new _move(obj,win);
		
		jQuery(obj).bind('mousedown',function(e){
			obj1.Mousedown(e?e:window.event);
		});
		jQuery(obj).bind('mouseup',function(e){
			obj1.Mouseup(e?e:window.event);
			
		});
		return obj1;
	}
	return false;
	
};
_move.prototype.setWinSize=function(width,height){
	win.ResizeTo(width,height);
}
_move.prototype.DetachEvent=function(e)
{
	if(!_move.tach) return;
	document.onmousemove=_move.onmousemove;
	document.onmousemove=_move.onmousemove;
	document.onmouseup=_move.onmouseup;
	document.onselectstart=_move.onselectstart;
	
	_move.tach=0;
	jQuery('#_blank').hide();
};
_move.prototype.AttachEvent=function(e)
{
	if(_move.tach) return;
	_move.onmousemove=document.onmousemove;
	_move.onmouseup=document.onmouseup;
	_move.onselectstart=document.onselectstart;
	if(e.preventDefault) e.preventDefault();
	else
	{
		document.onselectstart=function(){return false;}
		if(this.board.setCapture) this.board.setCapture();
		if(this.board.releaseCapture) this.board.releaseCapture();
	}
	_move.tach=1;
};
_move.prototype.Mousedown=function(e)
{
	e=e?e:window.event;
	if(jQuery.browser.msie){
		if(e.button>1) return;
	}else{
		if(e.button>0) return;
	}
	var self=this;
	var XX=e.clientX;
	var YY=e.clientY;

	var tag = e.srcElement ? e.srcElement :e.target;
	if(tag.type=="text"||tag.type=="textarea"){
		return true;
	}
	this.mousedowndoing=false;
	if(!_move.tach) this.AttachEvent(e);
	_move.even=e;
	var self=this;
	this.mousedownTimer=setTimeout(function(){self.PreMove(XX,YY);},200);
	
	return false
	//this.PreMove(_move.even,XX,YY);
	//eval("document.onmouseup=function(e){"+this.string+".Moved(e?e:window.event);};");
};
_move.prototype.Mouseup=function(e)
{
	e=e?e:window.event;
	if(jQuery.browser.msie){
		if(e.button>1) return;
	}else{
		if(e.button>0) return;
	}
	if(_move.tach) this.DetachEvent(e);
	if(!this.mousedowndoing) {
		clearTimeout(this.mousedownTimer);
	
	}else this.Moved(e);
};
_move.prototype.PreMove=function(XX,YY)
{
	
	this.mousedowndoing=true;
	if(!_move.tach) this.AttachEvent(_move.even?_move.even:window.event);
	jQuery('#_blank').show();
	this.moveX=XX;
	this.moveY=YY;
	
	document.body.style.cursor="url('dzz/images/cur/aero_arrow.cur'),auto";
	eval("document.onmousemove=function(e){"+this.string+".Move(e?e:window.event);return false;};");
	eval("document.onmouseup=function(e){"+this.string+".Moved(e?e:window.event);return false;};");
	return false;
};
_move.prototype.Move=function(e)
{
	//if(!_move.tach) this.AttachEvent(e);
	// window.captureEvents(e.MOUSEDOWN|e.MOUSEMOVE|e.MOUSEUP);;
	if(!_move.tach) return ;
	var XX=e.clientX;
	var YY=e.clientY;
	if(XX<0) XX=0;
	if(YY<0) YY=0;
	if(XX>top._config.screenWidth) XX=top._config.screenWidth;
	if(YY>top._config.screenHeight) YY=top._config.screenHeight;
	dx=XX-this.moveX;
	dy=YY-this.moveY;

	this.win.left+=dx;
	this.win.top+=dy;
	if(this.win.left+this.moveX<0) this.win.left=-this.moveX;
	if(this.win.left+this.moveX>top._config.screenWidth) this.win.left=top._config.screenWidth-this.moveX;
	if(this.win.top+this.moveY<0) this.win.top=-this.moveY;
	if(this.win.top+this.moveY>top._config.screenHeight) this.win.top=top._config.screenHeight-this.moveY;
	
	this.win.board.style.left=this.win.left+'px';
	this.win.board.style.top=this.win.top+'px';
};
_move.prototype.Moved=function(e)
{	
	jQuery('#_blank').hide();
	if(_move.tach)	this.DetachEvent(e);
	var XX=e.clientX;
	var YY=e.clientY;
	dx=XX-this.moveX;
	dy=YY-this.moveY;
	this.win.left+=dx;
	this.win.top+=dy;
	if(this.win.left+this.moveX<0) this.win.left=-this.moveX;
	if(this.win.left+this.moveX>top._config.screenWidth) this.win.left=top._config.screenWidth-this.moveX;
	if(this.win.top+this.moveY<0) this.win.top=-this.moveY;
	if(this.win.top+this.moveY>top._config.screenHeight) this.win.top=top._config.screenHeight-this.moveY;
	this.win.board.style.left=this.win.left+'px';
	this.win.board.style.top=this.win.top+'px';
	top._config.setWindowToSave(this.win);
};