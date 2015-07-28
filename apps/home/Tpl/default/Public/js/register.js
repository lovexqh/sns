$(function(){
	$('#regform').checkForm();
	//验证基础用户学号
	/*$('#regform').find("input").each(function(i, e) {
        if($(this).attr('type')=='text'){
			$(this).bind('blur',function(){
				var url = $(this).attr('url');
				var item = $(this);
				var val = $(this).val();
				var obj = $(this).parent().parent();
				var joinname = $(this).attr('joinname');
				var name = $(this).attr('name');
				if(typeof(url)!='underfined'){
					
					var param = {};
					param[name]=val;
					if(typeof(joinname)!='underfined'){
						param[joinname]=$('#'+joinname).val();
					}

					$.post(url,param,function(data){
						if(data!=''){
							if('success'==data){
								$(item).attr('success','success');
								obj.find('._error').find('#error_msg').html('');
								obj.find('._error').hide();
								obj.find('._success').show();
							}else{
								$(item).attr('success','error');
								obj.find('._success').hide();
								obj.find('._error').find('#error_msg').html(data);
								obj.find('._error').show();
							}
						}
					});
				}
			});
		}
    });
	
    //绑定身份点事的事件
	$("#user_role").find("input[name='usertype']").each(function(i, e) {
        $(this).bind('click',function(){
			$(e).parent().parent().parent().find('.baseinfo').each(function(k, o) {
                if(i == k){
					$(o).show();
				}else{
					$(o).hide();
				}
            });
			
		});
    });*/

});

//检查信息完整度的表单
function checkBaseInfo(form){
	var status = '';
	var usertype = 0;
	$(form.usertype).each(function(i, e) {
       if($(this).attr('checked')){
			usertype = $(this).val();
	   }
    });
	switch(usertype){
		case '2':	//验证老师
			status = checkTeacher(form);
			break;
		case '3':	//验证学生
			status = checkSchool(form);
			break;
		case '4':	//验证家长
			alert(3);
			break;
		default:
			status = false;	
			break;	
	}
	if('success' == status){
		return true;
	}else{
		return false;
	}
}
//验证选择老师时的资料完整度
function checkTeacher(form){
	var error = '';
	
	if(error!=''){
		if('error' != error)
			ui.error(error);
		return 'error';
	}else{
		return 'success';
	}
}
//验证选择学生时的资料完整度
function checkSchool(form){
	var error = '';
	var userno = form.userno;
	var nickname = form.nickname;
	var guardername = form.guardername;
	if($(userno).val()==''){
		error = '学号必须填写！';
		$(userno).focus();	
	}else{
		var nickname_val = $(nickname).val();
		var guardername_val = $(guardername).val();
		if(typeof(nickname_val)!='underfined'){
			var _nickname = $(nickname).attr('success');
			if('success' != _nickname){
				error = '请填写你正确的真实姓名！';
			}
		}else if(typeof(guardername_val)!='underfined'){
			var _guardername = $(guardername).attr('success');
			if('success' != _guardername){
				error = '请填写你正确的监护人真实姓名！';
			}
		}
	}
	if(error!=''){
		if('error' != error)
			ui.error(error);
		return 'error';
	}else{
		return 'success';
	}
}

//选择生日
function selectMonth(){
	var Year = $('#birthday_year').val();
	var Month = $('#birthday_month').val();
	var monthDay   =  new  Array(31,28,31,30,31,30,31,31,30,31,30,31);
	var monthDayNum;
	if(Year%400==0||(Year%4==0&&Year%100!=0)) monthDay[1]=   29;
	monthDayNum   =   monthDay[Month-1];

	var i;
	var daysout = '';
	for(i=1;i<=monthDayNum;i++){
		daysout+='<option value='+i+'>'+i+'</option>';
	}
	$('#birthday_day').html(daysout);
}

function insert_birth(){
	$("#birthday").removeClass("errorInput");
	$(".error_birthday").hide();
	$("#success_birthday").show();
}

function areaopt_plugin_fun(){
	$("#areaval").removeClass("errorInput");
	$(".error_areaval").hide();
	$("#success_areaval").show();
}
function work_check(){
	$("#school_check").hide();
	$("#school_name").val("null");
	if($("#work_name").val() == "null") $("#work_name").val("");
	$("#work_check").show();
}
function school_check(){
	$("#work_check").hide();
	$("#work_name").val("null");
	if($("#school_name").val() == "null") $("#school_name").val("");
	$("#school_check").show();
}
function other_check(){
	$(".the_check").hide();
	$("#work_name").val("null");
	$("#school_name").val("null");
}

function service_dialog(){

	ymPrompt.win({message:APP+'/Information/service',width:600,height:290,title:'服务条款',iframe:true})


}
