jQuery.extend(weibo.plugin, {
	meitu:function(element, options){
	   
	    
	}
});


jQuery.extend(weibo.plugin.meitu, {
   
	  html:'<div id="photo_merge""><div class="tips"  style="text-align:center; padding:200px;"><img src="'+ _THEME_+'/images/icon_waiting.gif" width="20" class="alM">加载中...</div></div>',
	  htmled:'<div id="photo_editor""><div class="tips"  style="text-align:center; padding:200px;"><img src="'+ _THEME_+'/images/icon_waiting.gif" width="20" class="alM">加载中...</div></div><div id="img_path" style="display:none"></div>',
	  click:function(options){
	 
	  //加载flash,为了兼容浏览器，这里使用swfObj.js为函数，在Hooks中调用
	  weibo.publish_type_box(this.html,options);
	  $('div.talkPop_box').css({'width':'528px'});
	    
	   var flashvars = { 
                  base: Plugin_path_meitu+'/html/'  ,
      }; 
	
	  //创建一个SWF对象舞台，expressInstall.swf为版本更新
	   swfobject.embedSWF(Plugin_path_meitu+'/html/SinaCollage.swf', "photo_merge", "100%", "470", "7", '#FFFFFF',"expressInstall.swf", flashvars); 
	
	  $('#weibo_close_handle').remove();
		//打开时先删除原有temp的图片
		weibo.closeCallback();
		weibo.closeCallback(function(){
		   $.post( U('home/widget/addonsRequest'),{'addon':'Meitu','hook':'cancelPublish'});
		})
       
  },
  edit:function(url,options){
	   
	    weibo.publish_type_box(this.htmled,options);
		$('div.talkPop_box').css({'width':'528px'});
		$('#img_path').html(url);
	    var flashvars = { 
                base: Plugin_path_meitu+'/html/'  ,
			   
      }; 
	  editurl=url;
	//创建一个SWF对象舞台，expressInstall.swf为版本更新
	   swfobject.embedSWF(Plugin_path_meitu+'/html/PhotoEditor.swf', "photo_editor", "528", "325", "7", '#FFFFFF',"expressInstall.swf", flashvars); 
	  },
	
});
//这里的param统一通过Flex外调函数来传入数据的，这里仅传入uploadURL

function fire(a,b,c,d)
{
	
	if(a=='SinaCollage'){
           if(c=='more||template||publish'||c=='more||photo||publish')
		   {
			var filename=getRndStr(15);
             var p={'uploadURL':'uploadURL.php?filename='+filename};
			 
		     //与Flex通信,这里调用了Adobe的Swfobj.js。所以要兼容IE
            
			   swfobject.getObjectById("photo_merge").editPhoto(p);
			
			
			times=setInterval(function(){isok(filename)},400);
		   }else if((c=='more||template||cancel')||c=='more||close'||c=='more||photo||cancel')
		    {
             weibo.reset();
		   }
	}else{
		
		var url=_PUBLIC_+'/../data/uploads/temp/'+$('#img_path').html()+'.jpg';
		
		 if(b=='photoEditorInit'){
           
		 
			  
           var p={imageURL:url,uploadURL:'editorURL.php?filename='+$('#img_path').html()};
		    if (navigator.appName.indexOf("Microsoft")!=-1){ //兼容IE
			    swfobject.getObjectById("photo_editor").editPhoto(p);
			}else{
				photo_editor.editPhoto(p);
				}
		   }
		   else if(c=='editor||close'){
			   weibo.reset()
			}
		  else if(c=='editor||cancel'){
			     html = '<div max-width:200px; max-height:200px"><input name="publish_type_data[]" type="hidden" style="width:86%" value="'+url+'"><div id="upload_select_file" class="layer_upload_file" style="background-color:#E8F0FF;width:485px;"><div class="btn_b buttonObj" onclick="weibo.plugin.meitu.edit(\''+url+'\',\'#meitu\')">美化图片</div><div class="btn_b buttonObj" onclick="weibo.reset();" style="margin-left:20px;">放弃</div></div><img src="'+url+'"></div>';
							
							if($('#content_publish').val()==''){
								$('#content_publish').val('#美图分享#');
							}
                            $("#publish_type_content").html('<input name="plugin_id" type="hidden" value="1">'+html );
							$('div.talkPop').data('type', 1);
							
                               weibo.publish_type_val(1);
							   weibo.checkInputLength(_LENGTH_);
			    }else if(c=='editor||upload'){
				    
				     times_ed=setInterval(function(){isitor()},400);
				}
		
		
		
	     
		   
		   
		
		}
}
       
//判断文件是否上传
function isitor(){
	var filename=$('#img_path').html();
	
	 $.post( U('home/widget/addonsRequest'),{'addon':'Meitu','hook':'isitor',filename:filename},function(res){
					txt = eval("(" + res + ")");	
					if(txt.ok=='1'){
						 clearInterval( times_ed ); 
						 
						 
						  html = '<div max-width:200px; max-height:200px"><input name="publish_type_data[]" type="hidden" style="width:86%" value="'+txt.urlpath+'"><div id="upload_select_file" class="layer_upload_file" style="background-color:#E8F0FF;width:485px;"><div class="btn_b buttonObj" onclick="weibo.reset();" style="margin-left:20px;">放弃</div></div><img src="'+txt.url+'"></div>';
							
							if($('#content_publish').val()==''){
								$('#content_publish').val('#美图分享#');
							}
                           $("#publish_type_content").html('<input name="plugin_id" type="hidden" value="1">'+html );
							$('div.talkPop').data('type', 1);
							
                               weibo.publish_type_val(1);
							   weibo.checkInputLength(_LENGTH_);
							  
						 
						}
	 																						
	});
	
	}
//判断文件是否上传
function isok(filename){
	 $.post( U('home/widget/addonsRequest'),{'addon':'Meitu','hook':'isok',filename:filename},function(res){
					txt = eval("(" + res + ")");	
					if(txt.ok=='1'){
						 clearInterval( times ); 
						  html = '<div max-width:200px; max-height:200px"><input name="publish_type_data[]" type="hidden" style="width:86%" value="'+txt.urlpath+'"><div id="upload_select_file" class="layer_upload_file" style="background-color:#E8F0FF;width:485px;"><div class="btn_b buttonObj" onclick="weibo.plugin.meitu.edit(\''+txt.imgpath+'\',\'#meitu\')">美化图片</div><div class="btn_b buttonObj" onclick="weibo.reset();" style="margin-left:20px;">放弃</div></div><img src="'+txt.url+'"></div>';
							
							if($('#content_publish').val()==''){
								$('#content_publish').val('#美图分享#');
							}
                           $("#publish_type_content").html('<input name="plugin_id" type="hidden" value="1">'+html );
							$('div.talkPop').data('type', 1);
							
                               weibo.publish_type_val(1);
							   weibo.checkInputLength(_LENGTH_);
							  
						 
						}
	 																						
	});
}


function getRndStr(k)
{
	var s=[];
    var a=parseInt(Math.random()*25)+(Math.random()>0.5?65:97);
    for (var i=0;i<k;i++)
    {
		s[i]=Math.random()>0.5?parseInt(Math.random()*9):
        String.fromCharCode(parseInt(Math.random()*25)+(Math.random()>0.5?65:97));}
        return s.join(""); 
}
