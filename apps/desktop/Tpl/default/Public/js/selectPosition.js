function SelectPosition(callback,uid)
{
	_config.selectPosition='';
	var windows = { object:null, title:_lang.selectposition, features:"titlebutton=close,width=300,height=200,button=OK|CANCEL,isModal=yes",width:500,height:400 };
	var win=_window.OpenSelectPostion(windows.title,windows.features,uid);
	win.OnOK = function(){
		
		if(!_config.selectPosition || _config.selectPosition.indexOf('-')===-1){
			alert(_lang.selectposition);
			return;
		}
		var position={}
		var arr=_config.selectPosition.split('-');
		position.uid=arr[2];
		if(arr[0]=='d'){
			position.container='icosContainer_body_'+arr[1];
		}else if(arr[0]=='f'){
			position.container='icosContainer_folder_'+arr[1];
		}
		if( typeof callback=='function') callback(position);
		win.Close();
	}
	win.OnCANCEL = function(){
		win.Close();
	}
};

_window.OpenSelectPostion=function(title,features,uid)
{
	var obj=new _window(features,'SelectPosition');
	obj.CreatSelectPostionWin(title,features,uid);
	return obj;
};
_window.prototype.CreatSelectPostionWin=function(title,features,uid)
{
	this.board=document.createElement("div");
	this.board.className='window '+this.className;
	this.board.style.position="absolute";
	this.board.style.zIndex=_window.zIndex;
	this.board.style.visibility="hidden";
	//this.desktop=_config.currentDesktop;
	jQuery('#MsgContainer').empty().show();
	document.getElementById('MsgContainer').appendChild(this.board);
	if(this.isModal){
		this.modal=document.createElement("div");
		this.modal.className='MODAL';
		this.modal.style.zIndex=_window.zIndex-1;
		document.getElementById('MsgContainer').appendChild(this.modal);
	}
	if(!_window.clientHeight)
	{
		this.board.style.left = "100%";
		this.board.style.top = "100%";
		_window.clientWidth = this.board.offsetLeft;
		_window.clientHeight = this.board.offsetTop;
		
	}
	 var styles=new Array("LEFT_TOP","TOP","RIGHT_TOP","RIGHT","RIGHT_BOTTOM","BOTTOM","LEFT_BOTTOM","LEFT");
	styles[styles.length]="TITLE";
	styles[styles.length]="CONTENT";
	this.sides=new Array();
	if(this.button)
	{
		styles[4]="RIGHT_BOTTOM_BY_BUTTON";
		styles[5]="BOTTOM_BY_BUTTON";
		styles[6]="LEFT_BOTTOM_BY_BUTTON";
		styles[styles.length]="BUTTON";
	}
	
	
	var self=this;
	
	if(this.resize!="no") 	{
		var resizes=this.resize.split('|');
		for (var i=0;i<resizes.length;i++){
			styles[styles.length]=resizes[i];
		}
	}
	
	for(var i=0;i<styles.length;i++)
	{
		var obj=document.createElement("div");
		obj.className=styles[i];
		obj.style.position="absolute";
		obj.innerHTML='<div class="'+styles[i]+'_inner inner" style="position:absolute;"></div>';
		this.board.appendChild(obj);
		switch(styles[i])
		{
			case "CONTENT":
				this.contentCase=obj;
				obj.style.width=this.bodyWidth+"px";
				obj.style.fontSize='12px';
				if(this.bodyHeight>0) obj.style.height=this.bodyHeight+"px";
				this.SetContent_SelectPostion(uid);
				obj.style.left=(this.sides[7].width)+"px";
				obj.style.top=(this.sides[1].height)+"px";
				this.width=this.bodyWidth+this.sides[3].width+this.sides[7].width;
				this.height=this.bodyHeight+this.sides[1].height+this.sides[5].height;
				this.minWidth=250;
				this.minHeight=200;
				this.board.style.height=this.height+"px";
				this.board.style.width=this.width+"px";
				break;
			case "TITLE":
				this.titleCase=obj;
				obj.style.height=obj.offsetHeight+'px';
				obj.style.width=this.bodyWidth+'px';
				this.SetTitle(title);
				if(this.moveable)
				{
					jQuery(this.titleCase).bind('mousedown',function(e){self.Mousedown(e?e:window.event);});
					jQuery(this.titleCase).bind('mouseup',function(e){self.Mouseup(e?e:window.event);});
				}
				
				break;
			case "BUTTON":
				this.buttonCase=obj;
				obj.style.width=this.bodyWidth+"px";
				obj.style.left=this.sides[7].width+'px';
				obj.style.bottom="0px";
				this.buttonCase.dx=obj.offsetWidth-this.width;
				if(this.minWidth<this.buttonCase.dx) this.minWidth=this.buttonCase.dx;
				var buttons=this.button.split("|");
				for(var j=0;j<buttons.length;j++)
				{
					var ox=document.createElement("button");
					ox.className=buttons[j];
					ox.title=buttons[j];
					obj.appendChild(ox);
					jQuery(ox).bind('click',function(e){eval(self.string+".On"+this.title+"()")});
					this.buttons[buttons[j]]=ox;
				}
				break;
			case "RESIZE":
				//obj.style.cursor="url('dzz/images/cur/aero_nwse.cur'),auto";
				jQuery(obj).bind('mousedown',function(e){self.resize='yes';self.PreResize(e?e:window.event);});
				break;
			case "RESIZE-X":
					this.resizexCase=obj;
					obj.style.cursor="e-resize";
					jQuery(obj).bind('mousedown',function(e){self.resize='resize-x';self.PreResize(e?e:window.event);});
					
				break;
			case "RESIZE-Y":
					this.resizeyCase=obj;
					obj.style.cursor="s-resize";
					jQuery(obj).bind('mousedown',function(e){self.resize='resize-y';self.PreResize(e?e:window.event);});
					
				break;
			case "BUTTON":
				this.buttonCase=obj;
				obj.style.width=this.bodyWidth+"px";
				obj.style.left=this.sides[7].width+'px';
				obj.style.bottom="0px";
				this.buttonCase.dx=obj.offsetWidth-this.width;
				if(this.minWidth<this.buttonCase.dx) this.minWidth=this.buttonCase.dx;
				var buttons=this.button.split("|");
				for(var j=0;j<buttons.length;j++)
				{
					var ox=document.createElement("button");
					ox.className=buttons[j];
					ox.title=buttons[j];
					obj.appendChild(ox);
					jQuery(ox).bind('click',function(e){eval(self.string+".On"+this.title+"()")});
					this.buttons[buttons[j]]=ox;
				}
				break;
			default:
					this.sides[i]=obj;
					this.sides[i].width=obj.offsetWidth;
					this.sides[i].height=obj.offsetHeight;
					if(this.moveable){
						obj.style.cursor="move";
						jQuery(obj).bind('mousedown',function(e){self.Mousedown(e?e:window.event);});
						jQuery(obj).bind('mouseup',function(e){self.Mouseup(e?e:window.event);});
					}
				
				break;
		}
	}
	
		this.sides[1].dx=this.sides[0].width+this.sides[2].width;
		if(this.width>this.sides[1].dx) this.sides[1].style.width=(this.width-this.sides[1].dx)+"px";
		this.sides[3].dy=this.sides[2].height+this.sides[4].height;
		if(this.height>this.sides[3].dy) this.sides[3].style.height=(this.height-this.sides[3].dy)+"px";
		this.sides[5].dx=this.sides[4].width+this.sides[6].width;
		if(this.width>this.sides[5].dx) this.sides[5].style.width=(this.width-this.sides[5].dx)+"px";
		this.sides[7].dy=this.sides[6].height+this.sides[0].height;
		if(this.height>this.sides[7].dy) this.sides[7].style.height=(this.height-this.sides[7].dy)+"px";
		
		
		
		this.sides[0].style.left="0px";
		this.sides[0].style.top="0px";
		this.sides[1].style.left=this.sides[0].width+"px";
		this.sides[1].style.top="0px";
		this.sides[2].style.right="0px";
		this.sides[2].style.top="0px";
		this.sides[3].style.right="0px";
		this.sides[3].style.top=this.sides[2].height+"px";
		this.sides[4].style.right="0px";
		this.sides[4].style.bottom="0px";
		this.sides[5].style.left=this.sides[6].width+"px";
		this.sides[5].style.bottom="0px";
		this.sides[6].style.left="0px";
		this.sides[6].style.bottom="0px";
		this.sides[7].style.left="0px";
		this.sides[7].style.top=this.sides[0].height+"px";
	
	this.left=parseInt((_window.clientWidth-this.width)/2);
	this.top=parseInt((_window.clientHeight-this.height)/2);
	if(this.left<0) this.left=0;
	if(this.top<0) this.top=0;
	
	this.board.style.left=this.left+"px";
	this.board.style.top=this.top+"px";
	this.board.style.visibility="visible";
	this.status=1;
	
};

_window.prototype.SetContent_SelectPostion=function(uid)
{
	var self=this;
	
	//ajaxget(_config.systemurl+'&op=file&id=d-1-'+_config.space.uid+'&uid='+_config.space.uid+'&ukey='+_config.ukey+'&t='+new Date().getTime(),'right_list','right_list');
	var CURRENT_PATH='dzz/system';
	jQuery(this.contentCase).jstree({ 
					// List of active plugins
					"plugins" : [ 
						"themes","json_data","ui","types","cookies","hotkeys" 
					],
			
					// I usually configure the plugin that handles the data first
					// This example uses JSON as it is most common
					"json_data" : { 
						// This tree is ajax enabled - as this is most common, and maybe a bit more complex
						// All the options are almost the same as jQuery's AJAX (read the docs)
						"ajax" : {
							// the URL to fetch the data
							"url" : _config.systemurl+'&op=explorer',
							// the `data` function is executed in the instance's scope
							// the parameter is the node being loaded 
							// (may be -1, 0, or undefined when loading the root nodes)
							"data" : function (n) { 
								// the result is fed to the AJAX request `data` option
								return { 
									"do" : "get_children",
									'uid': uid?uid:_config.myuid, 
									"id" : n.attr ? n.attr("id") : 0 ,
									'range':'my',
									't': new Date().getTime()
								}
							}
							/*"success":function(data){
								if(!data) return;
							}*/
						}
					},
					"themes":{
						"theme" : "default",
						"dots":false
					},
					// Using types - most of the time this is an overkill
					// read the docs carefully to decide whether you need types
					"types" : {
						// I set both options to -2, as I do not need depth and children count checking
						// Those two checks may slow jstree a lot, so use only when needed
						"max_depth" : -2,
						"max_children" : -2,
						// I want only `drive` nodes to be root nodes 
						// This will prevent moving or creating any other type as a root node
						"valid_children" : [ "drive" ,"folder"],
						"types" : {
							// The default type
							"default" : {
								// I want this type to have no children (so only leaf nodes)
								// In my case - those are files
								"valid_children" : "none",
								// If we specify an icon for the default type it WILL OVERRIDE the theme icons
								"icon" : {
									"image" : CURRENT_PATH+"/images/file.png"
								}
							},
							// The `folder` type
							"folder" : {
								// can have files and other folders inside of it, but NOT `drive` nodes
								"valid_children" : [ "default", "folder" ],
								"icon" : {
									"image" : CURRENT_PATH+"/images/folder.png"
								}
							},
							// The `drive` nodes 
							"type" : {
								// can have files and folders inside, but NOT other `drive` nodes
								"valid_children" : [],
								"icon" : {
									"image" : CURRENT_PATH+"/images/type.png"
								},
								// those prevent the functions with the same name to be used on `drive` nodes
								// internally the `before` event is used
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"create_node" : false,
								"rename" : false,
								"remove" : false
							},
							"position" : {
								// can have files and folders inside, but NOT other `drive` nodes
								"valid_children" : [],
								"icon" : {
									"image" : CURRENT_PATH+"/images/position.png"
								},
								// those prevent the functions with the same name to be used on `drive` nodes
								// internally the `before` event is used
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"create_node" : false,
								"rename" : false,
								"remove" : false
							},
							
							"blog" : {
								// can have files and folders inside, but NOT other `root` nodes
								"valid_children" : [  ],
								"icon" : {
									"image" : CURRENT_PATH+"/images/blog.png"
								},
								// those prevent the functions with the same name to be used on `drive` nodes
								// internally the `before` event is used
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"create_node" : false,
								"rename" : false,
								"remove" : false
							},
							"image" : {
								// can have files and folders inside, but NOT other `root` nodes
								"valid_children" : [  ],
								"icon" : {
									"image" : CURRENT_PATH+"/images/image.png"
								},
								// those prevent the functions with the same name to be used on `drive` nodes
								// internally the `before` event is used
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"create_node" : false,
								"rename" : false,
								"remove" : false
							},
							"link" : {
								// can have files and folders inside, but NOT other `root` nodes
								"valid_children" : [  ],
								"icon" : {
									"image" : CURRENT_PATH+"/images/link.png"
								},
								// those prevent the functions with the same name to be used on `drive` nodes
								// internally the `before` event is used
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"create_node" : false,
								"rename" : false,
								"remove" : false
							},
							"music" : {
								// can have files and folders inside, but NOT other `root` nodes
								"valid_children" : [  ],
								"icon" : {
									"image" : CURRENT_PATH+"/images/music.png"
								},
								// those prevent the functions with the same name to be used on `drive` nodes
								// internally the `before` event is used
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"rename" : false,
								"create_node" : false,
								"remove" : false
							},
							"video" : {
								// can have files and folders inside, but NOT other `root` nodes
								"valid_children" : [  ],
								"icon" : {
									"image" : CURRENT_PATH+"/images/video.png"
								},
								// those prevent the functions with the same name to be used on `drive` nodes
								// internally the `before` event is used
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"rename" : false,
								"create_node" : false,
								"remove" : false
							},
							"app" : {
								// can have files and folders inside, but NOT other `root` nodes
								"valid_children" : [  ],
								"icon" : {
									"image" : CURRENT_PATH+"/images/app.png"
								},
								// those prevent the functions with the same name to be used on `drive` nodes
								// internally the `before` event is used
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"rename" : false,
								"create_node" : false,
								"remove" : false
							},
							"other" : {
								// can have files and folders inside, but NOT other `root` nodes
								"valid_children" : [  ],
								"icon" : {
									"image" : CURRENT_PATH+"/images/other.png"
								},
								// those prevent the functions with the same name to be used on `drive` nodes
								// internally the `before` event is used
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"rename" : false,
								"create_node" : false,
								"remove" : false
							},
							"user" : {
								// can have files and folders inside, but NOT other `root` nodes
								"valid_children" : [ ],
								"icon" : {
									"image" : CURRENT_PATH+"/images/user.png"
								},
								// those prevent the functions with the same name to be used on `drive` nodes
								// internally the `before` event is used
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"create_node" : false,
								"rename" : false,
								"remove" : false
							},
							"friend" : {
								// can have files and folders inside, but NOT other `root` nodes
								"valid_children" : [ ],
								"icon" : {
									"image" : CURRENT_PATH+"/images/friend.png"
								},
								// those prevent the functions with the same name to be used on `drive` nodes
								// internally the `before` event is used
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"rename" : false,
								"create_node" : false,
								"remove" : false
							},
							"alluser" : {
								// can have files and folders inside, but NOT other `root` nodes
								"valid_children" : [ ],
								"icon" : {
									"image" : CURRENT_PATH+"/images/alluser.png"
								},
								// those prevent the functions with the same name to be used on `drive` nodes
								// internally the `before` event is used
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"create_node" : false,
								"rename" : false,
								"remove" : false
							},
							"desktop" : {
								// can have files and folders inside, but NOT other `root` nodes
								"valid_children" : ["folder" ],
								"icon" : {
									"image" : CURRENT_PATH+"/images/desktop.png"
								},
								// those prevent the functions with the same name to be used on `drive` nodes
								// internally the `before` event is used
								"start_drag" : false,
								"move_node" : false,
								"delete_node" : false,
								"remove" : false,
								"rename" : false
							}
						}
					}
					
					// UI & core - the nodes to initially select and open will be overwritten by the cookie plugin
			
					/*// the UI plugin - it handles selecting/deselecting/hovering nodes
					"ui" : {
						// this makes the node with ID node_4 selected onload
						"initially_select" : [ "node_1" ]
					},
					// the core plugin - not many options here
					"core" : { 
						// just open those two nodes up
						// as this is an AJAX enabled tree, both will be downloaded from the server
						"initially_open" : [ "node_2" , "node_3" ] 
					}*/
				})			
				.bind("select_node.jstree", function (event, data) {
						// `data.rslt.obj` is the jquery extended node that was clicked
						_config.selectPosition=data.rslt.obj.attr('id');
						if(data.rslt.obj.hasClass("jstree-closed")) { jQuery(self.contentCase).jstree('open_node',data.rslt.obj); }
						data.rslt.obj.siblings().each(function(){
							jQuery(self.contentCase).jstree('close_node',jQuery(this));
						});
				});
	
};

