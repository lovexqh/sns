// ----------------------------------------------------------
// ******************  ESN V1.0  ****************************
// 作者：ZzStudio
// 版本：1.0
// 网站：http://www.gridinfo.com.cn
// 版权：版权gridinfo.com.cn所有,任何人未经允许，不得使用和修改此代码
// **********************************************************
// ----------------------------------------------------------

function _select(container)
{
	this.id=this.name=container;
	this.string="_select.icos."+this.id;
	this.board=document.getElementById(container);
	_select.icos[this.id]=this;
};
_select.delay=500;
_select.width=120;
_select.height=120;
_select.icos={};

_select.onmousemove=null;
_select.onmouseup=null;
_select.tach=null;
_select.onselectstart=1;

_select.Cut=function(icoid){
	//处理原对象 去除样式
	if(_config.cut.iscut>0 && _config.cut.icos.length>0){
		for(var i in _config.cut.icos){
			jQuery('.Icoblock[icoid='+_config.cut.icos[i]+']').removeClass('iscut');
		}
	}
	//添加新对象到剪切板，并且设置剪切样式
	_config.cut.iscut=1;
	if(jQuery.inArray(icoid,_config.selectall.icos)>-1){
		_config.cut.icos=_config.selectall.icos;
	}else{
		_config.cut.icos=[icoid];
	}
	for(var i in _config.cut.icos){
		jQuery('.Icoblock[icoid='+_config.cut.icos[i]+']').addClass('iscut');
	}
}
_select.Copy=function(icoid){
	//处理原对象 去除样式
	if(_config.cut.iscut>0 && _config.cut.icos.length>0){
		for(var i in _config.cut.icos){
			jQuery('.Icoblock[icoid='+_config.cut.icos[i]+']').removeClass('iscut');
		}
	}
	//添加新对象到剪切板，并且设置剪切样式
	_config.cut.iscut=0;
	if(jQuery.inArray(icoid,_config.selectall.icos)>-1){
		_config.cut.icos=_config.selectall.icos;
	}else{
		_config.cut.icos=[icoid];
	}
	
}
_select.Paste=function(container,XX,YY){
	if(_config.cut.icos.length<1) return ;//剪贴板无数据，返回;
	//判断默认应用不能更换容器
	var desktop='';
	dektop=container.replace('icosContainer_body_','d:').replace('icosContainer_folder_','f:');
	for(var i=0;i<_config.cut.icos.length;i++){
		if(_config.sourcedata.icos[_config.cut.icos[i]] && _config.sourcedata.icos[_config.cut.icos[i]].uid<0){
			if(_config.cut.iscut>0 && _config.sourcedata.icos[_config.cut.icos[i]].desktop!=desktop){
				_config.cut.icos.splice(i,1);
				jQuery('.Icoblock[icoid='+_config.cut.icos[i]+']').removeClass('iscut');
				showPrompt(null,'',_lang.default_app_not_move,1000);
			}
		}
	}
	if(!_config.Permission_xiezuo(container)){
			showPrompt(null,'',_lang.paste_in_here_forbidden,1000);
			return ;
	}
	var data={"sourcetype":"icoid","icoid":_config.cut.icos.join(','),"ticoid":0,"container":container,iscut:_config.cut.iscut>0?1:2};
	//console.log(_config.saveurl+'&do=move&uid='+_config.space.uid+'&'+jQuery.param(data));
	jQuery.getJSON(_config.saveurl+'&do=move&uid='+_config.space.uid+'&'+jQuery.param(data),function(json){
		if(json.msg=='success'){
				if(json.iscopy>0){
					for(var i in json.icoarr){
						_config.sourcedata.icos[json.icoarr[i].icoid]=json.icoarr[i];
					}
					for(var i in json.folderarr){
						_config.sourcedata.folder[json.folderarr[i].fid]=json.folderarr[i];
					}
				}
				_config.cut.successicos=[];
				_config.cut.osuccessicos=[];
				for(var ico in json.successicos){
					_config.cut.osuccessicos.push(ico);
					_config.cut.successicos.push(json.successicos[ico]);
				}
				
				if(_config.cut.iscut>0){
					var cuticos=_config.cut.icos;
					for(var j in cuticos){
						_select.SelectedStyle(_config.selectall.container,cuticos[j],false);
					}
					for(var i in _config.cut.icos){
						jQuery('.Icoblock[icoid='+_config.cut.icos[i]+']').removeClass('iscut');
					}
					_config.cut.icos=[];
				}
				_select.IcoPasteTo(_config.cut.successicos,_config.cut.osuccessicos,container,XX,YY,json.iscopy,_config.cut.iscut);
				
		}else{
			Alert(json.msg);
		}
	});
};
_select.IcoPasteTo=function(successicos,osuccessicos,container,XX,YY,iscopy,iscut){
	if(container.indexOf('icosContainer_body_')!==-1 ){//是桌面图标容器
		var navid=container.replace('icosContainer_body_','');
		_select.resetConfigData(osuccessicos,successicos,'icosContainer_body_'+navid,iscopy,iscut);
		var pos=_config.screenList[navid].icos.length-successicos.length;
		var odata={"pos":pos,"left":XX-this.tl,"top":YY-this.tt};
		if(iscopy<1) _select.IcoRemoveFromContainer(osuccessicos);
		for(var i=0;i < successicos.length;i++){
			_ico.CIco(successicos[i],'icosContainer_body_'+navid,odata.pos+i);
		}
		if(_config.screenList[navid].iconposition>3) _ico.refreshList('icosContainer_body_'+navid);
		 
		 for(var id in _filemanage.cons){
			if(id.indexOf('d-'+navid+'-')===0){
				//alert(this.icoid);//_config.sourcedata.icos[this.icoid].icoid);
				for(var i=0;i < successicos.length;i++){
					_filemanage.cons[id].CreateIcos(_config.sourcedata.icos[successicos[i]]);
				}
			}
		}
	}else if(container.indexOf('icosContainer_folder_')!==-1){//是目录窗体容器
		var fid=container.replace('icosContainer_folder_','');
		_select.resetConfigData(osuccessicos,successicos,'icosContainer_folder_'+fid,iscopy,iscut);
		if(iscopy<1) _select.IcoRemoveFromContainer(osuccessicos);
		
		for(var id in _filemanage.cons){
			if(id.indexOf('f-'+fid+'-')===0){
				for(var i=0;i < successicos.length;i++){
					_filemanage.cons[id].CreateIcos(_config.sourcedata.icos[successicos[i]]);
				}
			}
		}
	}
};
_select.IcoRemoveFromContainer=function(oicos){
	for(var i=0;i<oicos.length;i++){
		var self=_ico.icos[oicos[i]];
		if(self){
			if(self.container=='_dock'){ //dock条
			//	alert(_config.dockList.join(','));
				//pos超过此的图标全部向左移动一格
				jQuery('#_dock').find('.Icoblock').each(function(){
					var id1=this.id.replace('icon_','');
					var obj1=_ico.icos[id1];
					if(obj1.pos>self.pos) {
						obj1.pos-=1;
						var pos=obj1.getpos(obj1.pos,'_dock');
						obj1.changeXY(pos[0],pos[1],1);
					}
				});
				//删除图标
				_config.setDockSize();
			 }else if(self.container.indexOf('icosContainer_body_')!==-1){//桌面容器
				//移除screenicolist；
				 _ico.refreshList(self.container);
			 }
			 if(self.type=='folder' && _window.windows['_W_'+self.sourceid]){
				 _window.windows['_W_'+self.sourceid].Close();
			 }
			jQuery(self.board).remove();
			for(var key in _ico.icos[self.id]) delete this[key];
			delete _ico.icos[self.id];
		}
	
		//删除资源管理器和目录内的元素，维护目录树
		for(var id in _filemanage.cons){
			var filmanage=_filemanage.cons[id];
			if(filmanage.data[filmanage.currentpage] && filmanage.data[filmanage.currentpage][oicos[i]]){
				_filemanage.cons[id].delIcos(_config.sourcedata.icos[oicos[i]]);
			}
		}
	}
};

_select.resetConfigData=function(oicos,icos,tcontainer,iscopy,iscut){
	
	if(iscut>0){
		//获取原容器
		var desktop=_config.sourcedata.icos[oicos[0]].desktop;
		//处理原容器
		if(desktop=='d:-1'){
			var arr=new Array();
			var icoids=_config.dockList;
			for(var i in icoids){
				if(jQuery.inArray(icoids[i],oicos)<0){
					arr.push(icoids[i]);
				}
			}
			_config.dockList=arr;
			_config.setDockSize();
		 }else if(desktop.indexOf('d:')!==-1){//桌面容器
			//移除screenicolist；
			var navid=desktop.replace('d:','');
			var arr=new Array();
			var icoids=_config.screenList[navid].icos;
			for(var i in icoids){
				if(jQuery.inArray(icoids[i],oicos)<0){
					arr.push(icoids[i]);
				}
			}
			_config.screenList[navid].icos=arr;
		  }else if(desktop.indexOf('f:')!==-1){//桌面容器//目录窗口
			//移除foldericolist；
			var fid=desktop.replace('f:','');
			var arr=new Array();
			var icoids=_config.sourcedata.folder[fid].ids;
			for(var i in icoids){
				if(jQuery.inArray(icoids[i],oicos)<0){
					arr.push(icoids[i]);
				}
			}
			_config.sourcedata.folder[fid].ids=arr;
			if(fid==_config.recyclefid) _ico.checkRecycleStatus();
		 }
	}
	
//处理目标容器

		 if(tcontainer=='_dock'){
			  _config.dockList=_config.dockList.concat(icos);
			  if(iscopy<1){
				   for(var i in icos){
						 _config.sourcedata.icos[icos[i]].desktop='d:-1';
						 if(_config.sourcedata.icos[icos[i]].type=='folder' ){
							 _config.sourcedata.folder[_config.sourcedata.icos[icos[i]].oid].pfid=0;
							  _config.sourcedata.folder[_config.sourcedata.icos[icos[i]].oid].desktop='d:-1';
						 }
					}
			  }
		   }else if(tcontainer.indexOf('icosContainer_body_')!==-1){//桌面容器
		   		var navid=tcontainer.replace('icosContainer_body_','');
		   		_config.screenList[navid].icos=_config.screenList[navid].icos.concat(icos);
				if(iscopy<1){
					 for(var i in icos){
						 _config.sourcedata.icos[icos[i]].desktop='d:'+navid;
						 if(_config.sourcedata.icos[icos[i]].type=='folder' ){
							 _config.sourcedata.folder[_config.sourcedata.icos[icos[i]].oid].pfid=0;
							  _config.sourcedata.folder[_config.sourcedata.icos[icos[i]].oid].desktop='d:'+navid;
						 }
					}
				}
		   }else if(tcontainer.indexOf('icosContainer_folder_')!==-1){//目录窗口 
		   		var fid=parseInt(tcontainer.replace('icosContainer_folder_',''));
				if(!_config.sourcedata.folder[fid].ids){
					_config.sourcedata.folder[fid].ids=icos;
				}else{
					_config.sourcedata.folder[fid].ids=_config.sourcedata.folder[fid].ids.concat(icos);
				}
				if(iscopy<1){
					for(var i in icos){
						 _config.sourcedata.icos[icos[i]].desktop='f:'+fid;
						 if(_config.sourcedata.icos[icos[i]].type=='folder' ){
							 _config.sourcedata.folder[_config.sourcedata.icos[icos[i]].oid].pfid=fid;
							  _config.sourcedata.folder[_config.sourcedata.icos[icos[i]].oid].desktop='f:'+fid;
						 }
					}
				}
				if(fid==_config.recyclefid) _ico.checkRecycleStatus();
		   }
};
_select.init=function(container){
	
	var obj= new _select(container);
	jQuery(obj.board).on('mousedown',function(e){
		e=e?e:window.event;
		var tag = e.srcElement ? e.srcElement :e.target;
		
		if(tag.type=="text"||tag.type=="textarea"){
			return true;
		}		
		obj.Mousedown(e?e:window.event);
	});
	jQuery(obj.board).on('mouseup',function(e){
		e=e?e:window.event;
		var tag = e.srcElement ? e.srcElement :e.target;
		if(tag.type=="text"||tag.type=="textarea"){
			return true;
		}		
		obj.Mouseup(e?e:window.event);
	});
		jQuery(obj.board).on('click',function(e){
			//清空数据
			//if(_hotkey.ctrl<1) return true;
			e=e?e:window.event;
			var tag = e.srcElement ? e.srcElement :e.target;
			if(tag.type=="text"||tag.type=="textarea"){
				return true;
			}	
			if(this.id==_config.selectall.container){
				_config.selectall.container=this.id;
				_config.selectall.icos=[];
				_config.selectall.position={};
				jQuery(obj.board).find('.Icoblock').removeClass('Icoselected');
			}
		});
	
	return obj;
};
_select.prototype.DetachEvent=function(e)
{
	if(!_select.tach) return;
	document.body.style.cursor="url('dzz/images/cur/aero_arrow.cur'),auto";
	document.onmousemove=_select.onmousemove;
	document.onmouseup=_select.onmouseup;
	document.onselectstart=_select.onselectstart;
	try{
		if(this.board.releaseCapture) this.board.releaseCapture();
	}catch(e){};
	_select.tach=0;
	_select.finishblank=0;
	
};
_select.prototype.AttachEvent=function(e)
{ 
	if(_select.tach) return
	_select.onmousemove=document.onmousemove;
	_select.onmouseup=document.onmouseup;
	_select.onselectstart=document.onselectstart;
	try{
		document.onselectstart=function(){return false;}
		if(e.preventDefault) e.preventDefault();
		else{
			if(this.board.setCapture) this.board.setCapture();
		}
	}catch(e){};
	_select.tach=1;
};
_select.prototype.Duplicate=function()
{
	this.copy=document.createElement('div');
	
	this.board.appendChild(this.copy);
	this.copy.style.cssText="position:absolute;left:0px;top:0px;width:0px;height:0px;filter:Alpha(opacity=50);opacity:0.5;z-index:2000;overflow:hidden;background:#000;border:1px solid #000;";
	//jQuery(this.copy).find('#text'+this.id).html('&nbsp;');
};
_select.prototype.Mousedown=function(e)
{
	this.mousedowndoing=false;
	var XX=e.clientX;
	var YY=e.clientY;
	//alert('down');
	_select.even=e?e:window.event;
	var self=this;
	if(_hotkey.alt>0) return true;
	if(jQuery.browser.mozilla) e.preventDefault();
	this.mousedownTimer=setTimeout(function(){self.PreMove(XX,YY);},200);
};

_select.prototype.Mouseup=function(e)
{
	if(_select.tach) this.DetachEvent(e);
	if(!this.mousedowndoing) {
		clearTimeout(this.mousedownTimer);
	}else this.Moved(e);
};
_select.prototype.PreMove=function(XX,YY)
{
	jQuery('#_blank').empty().show();
	if (this.move=="no") return;
	this.Duplicate();
	_select.oldxx=XX;
	_select.oldyy=YY;
	var self=this;
	this.mousedowndoing=true;
	if (typeof(this.MoveTimer)!="undefined") clearTimeout(this.MoveTimer);
	var p=jQuery(this.board).offset();
	this.tl=XX-p.left;
	this.tt=YY-p.top;
	this.oldx=XX;
	this.oldy=YY;
	this.copy.style.left=this.tl+'px';
	this.copy.style.top=this.tt+'px';
	
	//清空数据
	if(_hotkey.ctrl>0 && _config.selectall.container==this.id){
		
	}else{
		jQuery('#'+_config.selectall.container).find('.icoblank').parent().removeClass('Icoselected');
		_config.selectall.container=this.id;
		_config.selectall.icos=[];
		_config.selectall.position={};
	}
		//计算此容器内的所有ico的绝对位置，并且存入_config.selectall.position中；
		jQuery(this.board).find('.icoblank').each(function(){
			var el=jQuery(this);
			var p=el.offset();
			var icoid=el.attr('icoid');
			if(!jQuery.isNumeric(icoid)) return;
			if(icoid){
				_config.selectall.position[icoid]={icoid:icoid,left:p.left,top:p.top,width:el.width(),height:el.height()};
			}
		});
	if(!_select.tach) this.AttachEvent(_select.even);
	document.onmousemove=function(e){self.Move(e?e:window.event);return false;};
	document.onmouseup=function(e){self.Moved(e?e:window.event);return false;};
};
_select.prototype.Move=function(e)
{
	
	if(!_select.tach) return;
	var XX=e.clientX;
	var YY=e.clientY;
	
	var flag=0;
	if(XX-this.oldx>0){
		this.copy.style.width=(XX-this.oldx)+"px";
	}else{
		this.copy.style.width=Math.abs(XX-this.oldx)+"px";
		this.copy.style.left=this.tl+(XX-this.oldx)+"px";
	}
	if(YY-this.oldy>0){
		this.copy.style.height=(YY-this.oldy)+"px";
	}else{
		this.copy.style.height=Math.abs(YY-this.oldy)+"px";
		this.copy.style.top=this.tt+(YY-this.oldy)+"px";
	}
	if(!jQuery.browser.msie){
		//if(Math.abs(_select.oldxx-XX)>20 || Math.abs(_select.oldyy-YY)>20){
			if(XX>this.oldx && YY > this.oldy){
				this.setSelected(true);
			}else{
				this.setSelected();
			}
	/*	}else{
			_select.oldxx=XX;
			_select.oldyy=YY;
		}*/
	}
};
_select.prototype.Moved=function(e)
{
	var self=this;
	jQuery('#_blank').hide();
	
	var XX=e.clientX;
	var YY=e.clientY;
	if(jQuery.browser.msie){
		if(XX>this.oldx && YY > this.oldy){
			this.setSelected(true);
		}else{
			this.setSelected();
		}
	}
	if(_select.tach)	this.DetachEvent(e);
	jQuery(this.copy).remove();
	
};
_select.prototype.setSelected=function(flag){
	_select.sum++;
	var p=jQuery(this.copy).offset();
	var icos=[];
	var copydata={left:p.left,top:p.top,width:jQuery(this.copy).width(),height:jQuery(this.copy).height()};
	for(var icoid in _config.selectall.position){
		if(!_config.Permission_view('icoid',icoid)){
			continue;
		}
		var data=_config.selectall.position[icoid];
		if(_select.checkInArea(copydata,data,flag)){
			_select.SelectedStyle(this.id,icoid,true);
		}else if(_hotkey.ctrl<1){
			_select.SelectedStyle(this.id,icoid,false);
		}
	}
};
_select.checkInArea=function(copydata,data,flag){
	var rect={minx:0,miny:0,maxx:0,maxy:0}
	rect.minx=Math.max(data.left,copydata.left);
	rect.miny =Math.max(data.top,copydata.top) ;
	rect.maxx =Math.min(data.left+data.width,copydata.left+copydata.width) ;
	rect.maxy =Math.min(data.top+data.height,copydata.top+copydata.height) ;
	if(!flag){
		if(rect.minx>rect.maxx || rect.miny>rect.maxy){
			return false;
		}else{
			return true
		}
	}else{
		if(rect.minx>rect.maxx || rect.miny>rect.maxy){
			return false;
		}else{
			var area=(rect.maxx-rect.minx)*(rect.maxy-rect.miny);
			var dataarea=data.width*data.height;
			if(dataarea==area) return true;
			else return false;
		}
	}
};
_select.SelectedStyle=function(container,icoid,flag){
	var icos=_config.selectall.icos||[];
	var el=jQuery('#'+container).find('.icoblank[icoid='+icoid+']').parent();
	if(flag){
		el.addClass('Icoselected');
		if(_config.selectall.container=='') _config.selectall.container=container;
		if(_config.selectall.container==container){
			if(jQuery.inArray(icoid,_config.selectall.icos)<0){
			 	_config.selectall.icos.push(icoid);
			}
		}else{
			jQuery('#'+_config.selectall.container).find('.Icoblock').removeClass('Icoselected');
			_config.selectall.container=container;
			_config.selectall.icos=[icoid];
			_config.selectall.position={};
		}
	}else{
		var arr=[];
		if(_config.selectall.container==container){
			for(var i in icos){
				if(icos[i]!=icoid) arr.push(icos[i]);
			}
		}
		_config.selectall.icos=arr;
		el.removeClass('Icoselected');	
	}
};