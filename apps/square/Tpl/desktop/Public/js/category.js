//弹出创建专辑窗口
function create_category_tab(uid){
	ui.box.load(U('video/Manage/create_category_tab')+'&uid='+uid,{title:'创建专辑'});
}
//执行创建专辑操作
function do_create_category(){
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
	$.post(U('video/Manage/do_create_category'),{name:name,privacy:privacy,privacy_data:password},function(data){
		if(data == -1){
			ui.error('该专辑名已存在！');
		}else if(data){
			win.refresh_left();
			parent.setCategoryOption(data,privacy)
			ui.box.close();
			ui.success('创建成功！');
		}else{
			ui.box.close();
			ui.error('创建失败！');
		}
	});
}
//添加专辑下拉菜单
function setCategoryOption(data,privacy){
	var obj	=	eval('(' + data + ')');
	$('#categorylist').append('<option value="'+ obj.categoryId +'" selected="selected" style="background-color:yellow">'+ obj.categoryName +'</option>');
	//父级的权限列表设置功能
	setPrivate(privacy,0);
}
//动态更新权限表格的内容
function setPrivate(privacy,status){
	if(status){
		$.post(U('video/Manage/get_category'),{id:privacy},function(data){
			if(typeof(data)=='object'){
				if(data.privacy>1){
					$('#private').parent().parent().hide();
					$('#square').hide();
				}else{
					$('#private').parent().parent().show();
					$('#square').show();
				}
			}
		},'json');
	}else{
	$('#private').val(privacy);
		if(privacy==1){
			$('#private').parent().parent().show();
			$('#square').show();
		}else{
			$('#private').parent().parent().hide();
			$('#square').hide();
		}
	}
}