jQuery.extend(weibo.plugin, {
	editphoto:function(element, options){
	   
	    
	}
});


jQuery.extend(weibo.plugin.editphoto, {
   
	  html:'<div id="photo_editor""><div class="tips"  style="text-align:center; padding:200px;"><img src="'+ _THEME_+'/images/icon_waiting.gif" width="20" class="alM">加载中...</div></div>',
	  click:function(options,url){
	  //加载flash,为了兼容浏览器，这里使用swfObj.js为函数，在Hooks中调用
	 
	 
	  weibo.publish_type_box(this.html,options);
	  $('div.talkPop_box').css({'width':'528px'});
	    
	   var flashvars = { 
                  channel: "photo_merge_channel", 
                  JSHandler: "fire_edit", 
                  initFu: "init" ,
				  changeFlashHeightFun: "setHeight" ,
				  uploadCompleteFun : "upComplat" ,
				  closePhotoEditorFun: "closeEditor" ,
				  suda: "suda" ,
				  base: "addons/plugins/Meitu/html/"  ,
      }; 
	
		
	   swfobject.embedSWF("addons/plugins/Meitu/html/editphoto.js", "photo_editor", "100%", "470", "7", '#FFFFFF',"expressInstall.swf", flashvars); 
	
	  $('#weibo_close_handle').remove();
	
    
		weibo.closeCallback();
		weibo.closeCallback(function(){
		   $.post( U('home/widget/addonsRequest'),{'addon':'Meitu','hook':'cancelPublish'});
		})
       
  },
	
});
//这里的param统一通过Flex外调函数来传入数据的，这里仅传入uploadURL

function fire_edit(a,b,c,d)
{
	alert(url)
}
         
       

//判断文件是否上传
function isok(filename){
	 $.post( U('home/widget/addonsRequest'),{'addon':'Meitu','hook':'isok',filename:filename},function(res){
					txt = eval("(" + res + ")");	
					if(txt.ok=='1'){
						 clearInterval( times ); 
						  html = '<dic max-width:200px; max-width:200px"><img src="'+txt.url+'" style="width:100%"><input name="publish_type_data[]" type="hidden" style="width:86%" value="'+txt.url+'"><div id="upload_select_file" class="layer_upload_file" style="background-color:#E8F0FF;width:528px;"><div class="btn_b buttonObj" onclick="weibo.plugin.editphoto.click(\'#meitu\')">美化图片</div><div class="btn_b buttonObj" onclick="weibo.reset();" style="margin-left:20px;">放弃</div></div></div>';
							
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
}