//删除单张图片
function delphoto(classid){
	if(confirm('你确定要删除这张图片么？')){
		$.post(U('space/Upload/delete_photo'),{id:photo_id,albumId:album_id,classid:classid},function(data){
			if(data==1){
				if(nextid==photo_id||nextid==''){
					location.href=U('space/Upload/album')+'&id='+album_id+'&classid='+classid;
				}else{
					location.href=U('space/Upload/photo')+'&id='+nextid+'&aid='+album_id+'&classid='+classid;
				}
				return;
			}else{
				ui.error('删除失败！');
			}
		});
	}
}

//将我的一张图片设置为该专辑}的封面
function setcover(classid){
	if(confirm('你要将这张图片设置为封面么？')){
		$.post(U('space/Upload/set_cover'),{photoId:photo_id,albumId:album_id,classid:classid},function(data){
			if(data==1){
				ui.success('封面设置成功！');
			}else if(data==-1){
				ui.error('该图片不存在！');
			}else{
				ui.error('当前封面已是该图片，或设置失败！');
			}
		});
	}
}

//编辑图片
function editphotoTab(classid){
	ui.box.load(U('space/Upload/edit_photo_tab')+'&aid='+album_id+'&pid='+photo_id+'&classid='+classid,{title:'编辑图片'});
}
function do_update_photo(){
	var id		=	$('#photoId').val();
	var name	=	$('#name').val();
	var albumId	=	$('#albumId').val();
	var classid	=	$('#classid').val();
	if(!name || getLength(name.replace(/\s+/g,"")) == 0){
		alert('图片名字不能为空！');
        return false;
	}
	$.post(U('space/Upload/do_update_photo'),{id:id,name:name,albumId:albumId,classid:classid},function(data){
	    if(data.result==1){
			if(albumId!=albumIdold){
				if(nextid==id||nextid==''){
					location.href=U('space/Upload/album')+'&id='+album_id+'&classid='+classid;
				}else{
					location.href=U('space/Upload/photo')+'&id='+nextid+'&aid='+album_id+'&classid='+classid;
				}
				return;
			}else{
				// $('.photoName').html(name);
				$('.photoName').html(data.message);
			}
			ui.box.close();
			ui.success('修改成功！');
		}else{
			ui.box.close();
			ui.error('图片信息无变化！');
		}
	}, 'json');
}

//图片旋转
function dbrotate(roll,classid){
	var info = "<div style='width:200px; height:90px;padding-top:30px; line-height:25px;'><img src='"+_THEME_+"/images/loading_a.gif' /><br/>转换中...</div>";
	ui.loading();
	$.post(U('space/Upload/do_rotate'),{id:photo_id,roll:roll,classid:classid},function(data){
		var bigsrc = _ROOT_+"/thumb.php?w=560&h=560&t=f&url=http://"+document.domain+_ROOT_+"/data/uploads/"+savepath+"&r=1&s="+Math.random();
		var smallsrc = _ROOT_+"/thumb.php?w=50&h=50&t=f&url=http://"+document.domain+_ROOT_+"/data/uploads/"+savepath+"&r=1&s="+Math.random();
		$('#small_pic').attr('src',smallsrc);
		$('#big_pic').attr('src',bigsrc);
		setTimeout(function(){
			ui.close();		
		},3000);
	});	
}