//删除单张照片
function delphoto(pid,almid,from){
	if(confirm('你确定要删除这张照片么？')){

		$.post(U('photo/Manage/delete_photo'),
            {id:pid,albumId:almid,from:from},
            function(data){
                var obj = eval('('+ data +')');
                if(obj.status == 1){
                    if(obj.from == 'upload'){
                        $('#photo_'+pid).remove();
                        uploadnum = uploadnum - 1;
                        if(uploadnum <= 0){
                            $('.nopic').show();
                            $('#startupload').hide();
                        }

                    }else{
                        document.location.href = U('photo/Index/photos');
                    }
                }else{
                    alert('删除失败！');
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
    var album_cover = $('#album_cover').val();

	if(!name || getLength(name.replace(/\s+/g,"")) == 0){
		alert('照片名字不能为空！');
        return false;
	}
	
	$.post(U('photo/Manage/do_update_photo'),{id:id,name:name,albumId:albumId,album_cover:album_cover},function(data){
	    if(data.result==1){
			document.location.href = U('photo/Index/photos');
		}else{
			ui.box.close();
			ui.success('照片信息无变化！');
		}
	}, 'json');
}

//照片旋转
function dbrotate(roll){
	var info = "<div style='width:200px; height:90px;padding-top:30px; line-height:25px;'><img src='"+_THEME_+"/images/loading_a.gif' /><br/>转换中...</div>";
	mask.show();
	$.post(U('photo/Manage/do_rotate'),{id:photo_id,roll:roll},function(data){
		var bigsrc = _ROOT_+"/thumb.php?w=560&h=560&t=f&url=http://"+document.domain+_ROOT_+"/data/uploads/"+savepath+"&r=1&s="+Math.random();
		var smallsrc = _ROOT_+"/thumb.php?w=50&h=50&t=f&url=http://"+document.domain+_ROOT_+"/data/uploads/"+savepath+"&r=1&s="+Math.random();
		$('#small_pic').attr('src',smallsrc);
		$('#big_pic').attr('src',bigsrc);
		setTimeout(function(){
			mask.hide();		
		},3000);
	});	
}

//-------------------相册的js----------------------------------------

//弹出创建专辑窗口
function create_album_tab(cid){
    ui.box.load(U('photo/Manage/create_album_tab',['id='+cid]),{title:'相册管理'});
}
//执行创建专辑操作
function do_create_album(){
    var name		=	$('#name').val().replace(/\s+/g,"");
    var privacy		=	$('#privacy').val();
    var password	=	$('#textfield3').val();

    if(!name)	{
        alert('名称不能为空！');
        return false;
    }else if(name.length > 12)	{
        alert('名称不能超过12个字！');
        return false;
    }
    $.post(U('photo/Manage/do_create_album'),
        {name:name,privacy:privacy,privacy_data:password,id:$('#albumid').val()},
        function(data){
            var obj = eval('('+data+')');
            if(obj.status == 0){
                alert(obj.info);
            }else{
                if(obj.type == 'add'){
                    $('#albumlist').append('<option value="'+ obj.albumId +'" selected="selected" style="background-color:yellow">'+ obj.albumName +'</option>');
                }else{
                    $('#albumname').html(obj.albumName);
                }
                ui.box.close();
                window.location.reload();
            }
        }
    );
}

function delete_album(albumid){
    var url = U('photo/Manage/delete_album',['id='+albumid]);
    if(confirm('删除该相册的同时，将删除其所有照片，确定删除？')){
        $.post(url,
            function(data){
                var obj = eval('('+data+')');
                if(obj.status == 1){
                    alert(obj.info);
                    document.location.href = U('photo/Index/photos');
                }else{
                    alert(obj.info);
                }
            })
    }
}

//编辑照片
function editphotoBox(pid,almid){
    ui.box.load(U('photo/Manage/edit_photo_tab')+'&aid='+almid+'&pid='+pid,{title:'编辑照片'});
}
