/**
 * changeCategory
 * 用户分类改变时
 * @param _this  dom this
 * @access public
 * @return void
 */
function changeCategory( _this ){
	if( 0 == _this.val()){
		getMessage(_this.attr('name'));
	}
}
function getMessage(name) {
	var newCategory;
	var br="<br />";
	Prompt('添加分类', '请填写分类名', function(r){
		if (r){
			//执行添加分类操作
			addCategory(r,name);
		}else{
			$('select[comboname="'+name+'"]').combobox('select',1);
		}
	});
}
/**
 * addCategory
 * 添加分类
 * @param category $category
 * @access public
 * @return void
 */
function addCategory(category,name){
    // 在发表博客时, 添加新分类
    if(category.length>10){
    	Alert('分类名称不能大于10个中文字符');
		return;
    }
    if(getLength(category.replace(/\s+/g,"")) == 0){
        Alert('分类名不能为空');
		return;
    }
	if( category != "" ){
		$.post( U('blog/Index/addCategory'),{name:category},function(txt){
			if( -1 == txt ){
				Alert('添加失败', 'error');
			}else if(  -2  == txt){
				Alert('分类名冲突', 'warning');
			}else if( -3 == txt  ){
				Alert('添加失败，分类名不能为空', 'error');
			}else{
				$('select[comboname="'+name+'"]').combobox({ 
					url:U('blog/Index/getCategory', ['add=true']), 
					valueField:'id', 
					textField:'name'
				}); 
				$('select[comboname="'+name+'"]').combobox('select',txt);
				return;
			}
		});
	}else{
		Alert('请输入分类名', 'warning');
        return;
	}
}

/**
 * addBlog
 * 添加文章
 * @param 
 * @access public
 * @return void
 */
function addBlog(form){
	
	$(form).form('submit', {
		url: $(form).attr('action'),
		onSubmit: function(){
			var isValid = $(this).form('validate');
			if (isValid){
				//检测内容
				if(getEditorContent('content') == '' || checkPostContent(getEditorContent('content')) == 0){
					Alert('警告',"文章内容不能为空",'warning');
					$("#content").focus();
					isValid = false;
				}else{
					//如果各种验证都通过了，则启用遮罩层
					parent.mask.show();
					$(form.content).val(getEditorContent('content'));
				}
			}
			return isValid;	// return false will stop the form submission
		},
		success: function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			//判断返回的data值(将在action中控制ajax提交时将返回执行成功时的状态值)
			parent.mask.hide();
			if(data.status>0){
				parent.tabs.redirect('我发布的文章',U('blog/Index/my',['iframe=yes']));
			}else{
				Alert('错误',data.info,'error');	
			}
		}
	});
	return false;
}