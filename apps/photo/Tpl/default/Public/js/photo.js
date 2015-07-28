//删除单张照片
function delphoto(){
	if(confirm('你确定要删除这张照片么？')){
		$.post(U('photo/Manage/delete_photo'),{id:photo_id,albumId:album_id},function(data){
			if(data==1){
				if(nextid==photo_id||nextid==''){
					location.href=U('photo/Index/album')+'&id='+album_id+'&uid='+_UID_;
				}else{
					location.href=U('photo/Index/photo')+'&id='+nextid+'&aid='+album_id+'&uid='+_UID_;
				}
				return;
			}else{
				ui.error('删除失败！');
			}
		});
	}
}

//将我的一张照片设置为该专辑}的封面
function setcover(){
	if(confirm('你要将这张照片设置为封面么？')){
		$.post(U('photo/Manage/set_cover'),{photoId:photo_id,albumId:album_id},function(data){
			if(data==1){
				ui.success('封面设置成功！');
			}else if(data==-1){
				ui.error('该照片不存在！');
			}else{
				ui.error('当前封面已是该照片，或设置失败！');
			}
		});
	}
}

function do_update_photo(){
	var id		=	$('#photoId').val();
	var name	=	$('#name').val();
	var albumId	=	$('#albumId').val();
	if(!name || getLength(name.replace(/\s+/g,"")) == 0){
		alert('照片名字不能为空！');
        return false;
	}
	
	$.post(U('photo/Manage/do_update_photo'),{id:id,name:name,albumId:albumId},function(data){
	    if(data.result==1){
			if(albumId!=albumIdold){
				if(nextid==id||nextid==''){
					location.href=U('photo/Index/album')+'&id='+album_id+'&uid='+_UID_;
				}else{
					location.href=U('photo/Index/photo')+'&id='+nextid+'&aid='+album_id+'&uid='+_UID_;
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
			ui.success('照片信息无变化！');
		}
	}, 'json');
}

//照片旋转
function dbrotate(roll){
	var info = "<div style='width:200px; height:90px;padding-top:30px; line-height:25px;'><img src='"+_THEME_+"/images/loading_a.gif' /><br/>转换中...</div>";
	ui.loading();
	$.post(U('photo/Manage/do_rotate'),{id:photo_id,roll:roll},function(data){
		var bigsrc = _ROOT_+"/thumb.php?w=560&h=560&t=f&url=http://"+document.domain+_ROOT_+"/data/uploads/"+savepath+"&r=1&s="+Math.random();
		var smallsrc = _ROOT_+"/thumb.php?w=50&h=50&t=f&url=http://"+document.domain+_ROOT_+"/data/uploads/"+savepath+"&r=1&s="+Math.random();
		$('#small_pic').attr('src',smallsrc);
		$('#big_pic').attr('src',bigsrc);
		setTimeout(function(){
			ui.close();		
		},3000);
	});	
}