function clickshow(){
	$('#add_photo').toggle();
}
//弹出创建专辑窗口
function create_album_tab(classid){
	ui.box.load(U('space/Upload/create_album_tab')+'&classid='+classid,{title:'创建班级相册'});
}
//执行创建专辑操作
function do_create_album(){
	var name		=	$('#name').val().replace(/\s+/g,"");
    var classid		=	$('#classid').val();
	if(!name)	{ 
		alert('名称不能为空！');
		return false;
	}else if(name.length > 12)	{ 
		alert('名称不能超过12个字！');
		return false;
	}
	$.post(U('space/Upload/do_create_album'),{name:name,classid:classid},function(data){
		if(data == -1){
			ui.error('该相册名已存在！');
		}else if(data == 0){
			ui.box.close();
			ui.error('创建失败！');
		}else if(data){
			parent.setAlbumOption(data);
			ui.box.close();
			ui.success('创建成功！');
		}
	});
}
//添加专辑下拉菜单
function setAlbumOption(data){
	var obj	=	eval('(' + data + ')');
	$('#albumlist').append('<option value="'+ obj.albumId +'" selected="selected" style="background-color:yellow">'+ obj.albumName +'</option>');
}