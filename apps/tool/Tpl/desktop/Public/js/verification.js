/*上传附件时表单验证*/
function checkmyuploads(form){
	var isValid = true;
	if(isValid){
		if($(form.title).val()==''){
			$(form.title).focus();
			check_focus(form.title);
			isValid = false;
		}else if($(form.tags).val()==''){
			$(form.tags).focus();
			check_focus(form.tags);
			isValid = false;
		}else if($(form.version).val()==''){
			$(form.version).focus();
			check_focus(form.version);
			isValid = false;
		}else if(getEditorContent('content') == '' || checkPostContent(getEditorContent('content')) == 0){
			//瞬移到当前输入框内 （参数为 jquery 选择器）
			check_focus('iframe[title="kissy-editor"]');
			//编辑器控件做特殊处理
			$("#content").focus();
			
			isValid = false;
		}else{
			//如果各种验证都通过了，则启用遮罩层
			try{
				parent.mask.show();
			}
			catch(e){}
			$(form.content).val(getEditorContent('content'));
		}
	}
	if(isValid){
		$("input[name='class']").each(function(index, element) {
			if($(this).attr('checked')){
				if(index==0){
					if($(form.section).val()==''){
						check_focus($("#navtree"));
						Alert('警告',"请选择分类",'warning');
						isValid = false;
					}	
				}else if(index==1){
					
				}else if(index==2){
					
				}	
			}
		});
	}
	if(isValid){
		if(swfu.getStats().files_queued=='' || swfu.getStats().files_queued==0){
			check_focus($("#fsUploadProgress1"));
			Alert('警告',"请选择上传工具",'warning');
			isValid = false;
		}else{
			swfu.startUpload();
		}
	}
	return false;
}

/*资源编辑上传验证*/
function checkedit(form){
	$(form).form('submit', {
		url: $(form).attr('action'),
		onSubmit: function(){
			var isValid = true;
			if(isValid){
				if($(form.title).val()==''){
					$(form.title).focus();
					check_focus(form.title);
					isValid = false;
				}else if($(form.tags).val()==''){
					$(form.tags).focus();
					check_focus(form.tags);
					isValid = false;
				}else if($(form.version).val()==''){
					$(form.version).focus();
					check_focus(form.version);
					isValid = false;
				}else if(getEditorContent('content') == '' || checkPostContent(getEditorContent('content')) == 0){
					//瞬移到当前输入框内 （参数为 jquery 选择器）
					check_focus('iframe[title="kissy-editor"]');
					//编辑器控件做特殊处理
					$("#content").focus();
					isValid = false;
				}else{
					//如果各种验证都通过了，则启用遮罩层
					try{
						parent.mask.show();
					}
					catch(e){}
					$(form.content).val(getEditorContent('content'));
				}
				
			}
			if(isValid){
				$("input[name='class']").each(function(index, element) {
					if($(this).attr('checked')){
						if(index==0){
							if($(form.section).val()==''){
								check_focus($("#navtree"));
								Alert('警告',"请选择分类",'warning');
								isValid = false;
							}	
						}else if(index==1){
							
						}else if(index==2){
							
						}	
					}
				});
			}return isValid;	// return false will stop the form submission
		},
		success: function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			//判断返回的data值(将在action中控制ajax提交时将返回执行成功时的状态值)
			parent.mask.hide();
			if(data.status>0){
				parent.ui.success(data.info);	
				parent.tabs.redirect('我上传的工具',U('tool/Index/my',['iframe=yes']));
			}else{
				Alert('错误',data.info,'error');	
			}
		}
	});
	return false;
}

/*批量上传*/
/*上传附件时表单验证*/
function checkbatchmyuploads(form){
	//执行ajax提交
	$(form).form('submit', {
		url: $(form).attr('action'),
		onSubmit: function(){
			var isValid = $(this).form('validate');
			return isValid;	// return false will stop the form submission
		},
		success: function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			//判断返回的data值(将在action中控制ajax提交时将返回执行成功时的状态值)
			parent.mask.hide();
			parent.tabs.redirect('我上传的工具',U('tool/Index/my',['iframe=yes']));
			win.refresh_left();
		}
	});
	return false;
}


/*基础资源、专题资源、其他资源*/
function cate_change(id){
	switch (id){
		case 1:
			$("#rclass1").show();
			$("#rclass2").hide();
			$("#rclass3").hide();
			break;
		case 2:
			$("#rclass1").hide();
			$("#rclass2").show();
			$("#rclass3").hide();
			break;
		case 3:
			$("#rclass1").hide();
			$("#rclass2").hide();
			$("#rclass3").show();
			break;
	}
}


/*资源搜索框*/
function searchsubmit(){
	var key = $('#searchInput').val();
	tabs.iframe('搜索',U('tool/Index/search',["tags="+key]));
}


/*删除资源操作*/
function delResource(id){
	if(confirm("是否确定删除这条工具")) {
		$.post(U('tool/Index/delResource'),{id:id},function(data){
			if(data==1)	{
				if(typeof(iframe)!='undefined'){
					 parent.ui.success('删除成功！');
				  }else{
					 ui.success('删除成功！');
				  }
			  	  win.refresh_left();
				  parent.tabs.redirect('我上传的工具',U('tool/Index/my', ['iframe=yes']));
			}else if(data==2){
				  if(typeof(iframe)!='undefined'){
					parent.ui.error('删除失败！'); 
				  }else{
					ui.error('删除失败！');
				  }
			}
		});
	}
}