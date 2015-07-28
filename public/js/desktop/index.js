var ismain = 0;
function ReSet(){
	$('.slideback').find('img').height($(window).height());
	if($('.slideback').find('img').width()<$(window).width()) $('.slideback').find('img').width($(window).width());
	$('.slideback').find('.content-box').width($(window).width()).height($(window).height()).fadeIn('fast');
	$('.slideback').show();
	$('.container>.containerInner').height($(window).height()-$('.headerback').height()-$('.footerback').height());
	
	if (typeof initEvent != 'undefined'
			&& initEvent instanceof Function) {
		initEvent();
	}
}

$(document).ready(function(e) {
	/*顶部下拉菜单*/
	var timer;
	$('.menubar>li>a').each(function(i, e) {
		if(typeof($(this).find("i[class='arrow-drop']"))!='undefined'){
			$(this).parent().mouseover(function(){
				clearTimeout(timer);
				$(this).find('ul').show();	
			}).mouseout(function(){
				$this = $(this);
				timer = setTimeout(function(){
					$this.find('ul').hide();
				},500);
			});
			
		}
	});
	
	/*绑定顶部建议按钮*/
	$('.suggest').on('click','a',function(){
		if(typeof showSuggest == 'function'){
			showSuggest();	
		}else{
			window.location.href = U('home', ['s=suggest']);	
		}
	});
});

/**
 * ajax登录方法
 * param form表单对象
 **/
function checkLogin(form){
	var email = form.email;
	var password = form.password;
	var verify = form.verify;
	var remember = form.remember;
	if($.trim($(email).val())=='' || $.trim($(password).val())==''){
		$('#login_tips').html('帐号和密码不能为空！').fadeIn('fast');
		$(email).focus();
		return false;
	}
	if($(email).val().length<2){
		$('#login_tips').html('账号长度必须大于'+$(email).val().length+'位').fadeIn();
		$(email).focus();
		return false;
	}
	if($(password).val().length<6){
		$('#login_tips').html('密码长度必须大于6位').fadeIn('fast');
		$(password).focus();
		return false;
	}
	
	var loginnum = 0;
	if(getCookie("logincount")!=null){
		loginnum = getCookie("logincount")
	}
	if(loginnum<3){
		loginnum++;
		setCookie("logincount",loginnum,"s600");
	}
	if(loginnum>3){
		if($.trim($(verify).val())==''){
			$('#login_tips').html('验证码不能为空！').fadeIn('fast');
			$(verify).focus();
			return false;
		}
	}

	$.post($(form).attr('action'),$(form).serialize(),function(data){
		json = eval('('+data+')');
		//登录成功
		if(json.status==1){
			delCookie("logincount");
			$('#login_tips').html('<font color=#ffcc1c>登录成功，页面跳转中...</font>').show('fast');
			window.location.href=U('desktop/Index/index');
		}else{
			$('#login_tips').html(json.message).fadeIn('fast');
			/*显示验证码*/
			if(loginnum>=3){
				if($(form).find('#codeWarp').css('display')=='none'){
					$(form).find('#codeWarp').show('fast');
					setCookie("logincount",loginnum++,"s600");
				}
			}
		}
	});
	return false;
}

/**
 * ajax联想相关邮箱后缀
 * param val邮件地址
 **/
function checkRelevance(obj){
	var val = obj.value;
	var $relevance = $('.email-relevance');

	//绑定方向事件
	$(obj).keydown(function(event){
		if(event.keyCode == 40){//down
			$relevance.find('li:first>a').addClass('sele').focus();
			return;
		}
		if(event.keyCode == 38){//up
			$relevance.find('li:last>a').addClass('sele').focus();
			return;
		}
	});
	
	if(typeof(val)!='undefined' && val!='' && val.indexOf('@')!=-1){
		$.post(U('home/Public/mailsuffix'),{email:val},function(data){
			var data = eval('('+data+')');
			if(data.data.length){
				var li = '';
				for(var i=0;i<data.data.length;i++){
					var text = data.data[i];
					text = text.replace(data.suffix,'<strong>'+data.suffix+'</strong>');
					li += '<li><a href="javascript:;">'+text+'</a></li>';	
				}
				if($('.email-relevance').length==0){
					$('body').append('<div class="email-relevance"><ul></ul></div>');
				}
				$relevance.css({left:$(obj).offset().left+95,top:$(obj).offset().top+44});
				$relevance.find('ul').html(li);
				$relevance.fadeIn('fast');
				
				//绑定a标签事件
				$relevance.find('a').live('click',function(){
					$(obj).val(val.substring(0,val.indexOf('@'))+$(this).text());
					$relevance.fadeOut('fast');
				});
				
				$(document).click(function(){
					$relevance.find('ul>li').remove();
					$relevance.fadeOut('fast');
				});
				
			}else{
				$relevance.find('ul>li').remove();
				$relevance.fadeOut('fast');
			}
		});
	}else{
		$relevance.find('ul>li').remove();
		$relevance.fadeOut('fast');
	}
}

$('.email-relevance').live('keydown',function(event){
	if(event.keyCode == 40){//down
		var index = $(this).find('.sele').parent().index();
		$(this).find('li:eq('+(index+1)+')').find('a').addClass('sele').focus();
		$(this).find('li:eq('+index+')').find('a').removeClass('sele');
		return;
	}
	if(event.keyCode == 38){//up
		var index = $(this).find('.sele').parent().index();
		$(this).find('li:eq('+(index-1)+')').find('a').addClass('sele').focus();
		$(this).find('li:eq('+index+')').find('a').removeClass('sele');
		return;
	}
	if(event.keyCode == 8){//backspace
		$('#email').focus();
		return;
	}
	if(event.keyCode == 13){//Enter
		setTimeout(function(){
			$('.email-relevance').fadeOut('fast');	
		},500);
		return;
	}
});