$(document).ready(function(e) {
    //表单验证
	$("#followuser").validate({
	onkeyup  : false,
	onclick  : false,
	onfocusout : false,
		invalidHandler : function(){
			return false;
		},
		showErrors : function(errorMap, errorList) {
			var msg = "";   
			$.each(errorList, function(i,v){   
			  msg += (v.message+"\r\n");   
			});   
			if(msg!="")
			ui.success(msg);
		},
		submitHandler : function(){
			if( $("input[name='followuid[]']:checked").size() ==0){
				ui.error('请选择要关注的人');
				return false;
			}
			//表单的处理
		   mask.show();		   
		   $.ajax({			   
			   url:$('#followuser').attr('action'),
			   dataType:'json',
			   type:$('#followuser').attr('method'),
			   data:$('#followuser').serialize(),
			   error:function(){
				   mask.hide();
				   ui.error('请求错误!');
			   },
			   success:function(data){
				   mask.hide();
				   ui.success('关注成功!');
				   window.location.reload();
			   }
		   })
			return false;//阻止表单提交
		}
	});
});