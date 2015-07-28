//删除单张图片
function delphoto(id){
	var id = id ;
	if(confirm('你确定要删除这张图片么？')){
		$.post(U('photo/Manage/delete_photo'),{id:id},function(data){
			if(data==1){
				$('#'+'r'+id+'tu').remove();
				ui.success('删除成功！');
                resize();
			}else{
				ui.error('删除失败！');
			}
		});
	}
}