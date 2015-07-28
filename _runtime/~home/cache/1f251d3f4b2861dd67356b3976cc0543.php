<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en"><head>
<meta charset="utf-8">
<title><?php if(($ts['site']['page_title'])  !=  ""): ?><?php echo ($ts['site']['page_title']); ?> <?php echo ($ts['site']['site_name']); ?><?php else: ?><?php echo ($ts['site']['site_name']); ?><?php endif; ?></title>
<meta name="keywords" content="<?php echo ($ts['site']['site_header_keywords']); ?>" />
<meta name="description" content="<?php echo ($ts['site']['site_header_description']); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="__PUBLIC__/js/desktop/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/jquery.callback.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/common.js"></script>
<!--script type="text/javascript" src="__PUBLIC__/js/desktop/jquery.slide.js"></script-->
<script type="text/javascript" src="__PUBLIC__/js/desktop/jquery.mousewheel.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/jquery.jscrollpane.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/jquery.validate.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/jquery.metadata.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/lang/validate.message_cn.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/index.js"></script>
<link rel="shortcut icon" href="__ROOT__/favicon.ico" type="image/x-icon"/>
<link rel="stylesheet" href="__THEME__/desktop/css/reset.css" type="text/css"/>
<link rel="stylesheet" href="__THEME__/desktop/css/scroll.css" type="text/css"/>
<link rel="stylesheet" href="__THEME__/desktop/css/index.css?{time()}" type="text/css"/>
<link rel="stylesheet" media="all and (orientation:portrait)" href="__THEME__/desktop/css/portrait.css?{time()}">
<link rel="stylesheet" media="all and (orientation:landscape)" href="__THEME__/desktop/css/landscape.css?{time()}">
<!--[if lt IE 9]>
<script src="__PUBLIC__/js/desktop/html5.js"></script>
<![endif]-->
<!--[if lt IE 7]>
<script type="text/javascript" src="__PUBLIC__/js/desktop/pngfix.js"></script>
<script language="javascript">
$(document).ready(function(e) {
	DD_belatedPNG.fix('.pngfix');
});
</script>
<![endif]-->
<script>
	var _UID_   = <?php echo (int) $uid; ?>;
	var _MID_   = <?php echo (int) $mid; ?>;
	var _ROOT_  = '__ROOT__';
	var _THEME_ = '__THEME__';
	var _PUBLIC_ = '__PUBLIC__';
	var _LENGTH_ = <?php echo (int) $GLOBALS['ts']['site']['length']; ?>;
	var _LANG_SET_ = '<?php echo LANG_SET; ?>';
</script>
<script language="javascript">
$(function(){
	if ($.browser.msie && $.browser.version < 10 ){
    	$('<style type="text/css">.bodyContainer{ position:relative; }</style>').appendTo('head');
		if($.browser.version < 10){
			$('#system_warring').html('社区小助手很遗憾的通知您，您目前使用的浏览器版本太低暂不支持本系统。建议您下载我们最新版本的 <a href="http://soft.hao123.com/index.php?ct=stat&ac=aladdin&bd=1&id=881&f=aHR0cDovL3NvZnRkb3dubG9hZC5oYW8xMjMuY29tL2hhbzEyMy1zb2Z0LW9ubGluZS1iY3Mvc29mdC9DLzIwMTMtMTAtMTdfQ2hyb21lU3RhbmRhbG9uZVNldHVwLmV4ZQ==" target="_blank">谷歌浏览器</a> 或 <a href="http://soft.hao123.com/index.php?ct=stat&ac=aladdin&bd=1&id=883" target="_blank">火狐浏览器</a> <a href="http://soft.hao123.com/index.php?ct=stat&ac=aladdin&bd=1&id=881&f=aHR0cDovL3NvZnRkb3dubG9hZC5oYW8xMjMuY29tL2hhbzEyMy1zb2Z0LW9ubGluZS1iY3Mvc29mdC9DLzIwMTMtMTAtMTdfQ2hyb21lU3RhbmRhbG9uZVNldHVwLmV4ZQ==" class="btn" target="_blank">点此下载</a><a href="###" onClick="$(\'.system_warring\').slideUp(\'fast\');" class="close">关闭</a>');	
		}
		$('#system_warring').show();
	}
});
</script>
</head>
<body onResize="ReSet()" onLoad="ReSet()">
<!-- system warring -->
<div id="system_warring" class="system_warring" style="display:none"></div>
<!-- end .system_warring -->
<div id="slidebackgroud" class="slideback">
<?php if(empty($backgroup)): ?><img src="__THEME__/desktop/images/index/1.jpg" />
<?php else: ?>
	<div class="content-box">
		<ul class="slide-content">
		<?php if(is_array($backgroup)): ?><?php $i = 0;?><?php $__LIST__ = $backgroup?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li><img src="<?php echo ($vo["img"]); ?>" description="<?php echo ($vo["title"]); ?>" /></li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
		</ul>
		<div class="slide-nav">
			<a class="prev" href="#"><i>&#8249;</i></a>
			<a class="next" href="#"><i>&#8250;</i></a>
		</div>
	</div><?php endif; ?>
<!-- end .slideback --></div>

<div class="container bodyContainer">
  <div class="headerback"></div>
  <header>
    <h1><a href="<?php echo U('home');?>">吉林建筑大学 道教·网络空间</a></h1>
	<ul class="menubar">		
		<?php if( !isset($_SESSION["userInfo"])): ?><li><a href="<?php echo U('home/Public/register');?>">注册</a></li>
		<li><a href="<?php echo U('home/Public/login');?>">登录</a></li><?php endif; ?>
		<?php if(isset($_SESSION["userInfo"])): ?><li><a href="<?php echo U('home/Public/logout');?>">退出</a></li><?php endif; ?>
		<!--<li class="suggest"><a href="javascript:;">给我们建议<sup>new</sup></a></li>-->
	</ul>
  </header>
  
  <div class="containerInner">
<style>
.containerInner{padding-top:50px;}
</style>
<script language="javascript" type="text/javascript">
<!-- 
if (top.location != self.location) top.location = self.location; 
// -->
</script>
<?php $regInfo=model('Xdata')->lget('register'); ?>
<div class="registerBox publicLoginBox">
	<h2>快速登录</h2>
	<div class="loginForm">
	<form id="loginForm" name="loginForm" action="<?php echo U('home/Public/doLogin');?>" method="post">
<?php if ($invite_code) { ?>
		<input type="hidden" name="invite_code" value="<?php echo ($invite_code); ?>">
<?php } ?>
		<ul class="">
		  <li>
			<span class="uh">帐　号：</span>
			<input type="text" class="ipt-txt" value="<?php echo ($cookie_email); ?>" id="email" name="email" />
		  </li>
		  <li>
			<span class="uh">密　码：</span>
			<input type="password" class="ipt-txt" name="password" id="password" />
		  </li>
		  <?php if(($login_verify_on)  ==  "1"): ?><li>
			<span class="uh">验证码：</span>
			<input type="text" class="ipt-code" id="verify" name="verify"  value="" />
			<img src="__ROOT__/public/captcha.php?<?php echo time(); ?>" id="verifyimg" alt="换一张" onclick="changeverify()" />
			<a href="###" onclick="changeverify()">换一换</a>
		  </li><?php endif; ?>
		  <li>
			<span class="uh">&nbsp;</span>
			  <label><input name="remember" type="checkbox" value="1" class="ipt-chk" /> 记住登录状态</label>
			   | <a class="fuc0" target="_blank" href="<?php echo U('home/Public/sendPassword');?>">忘记密码？</a>
		  </li>
		  <li>
			<span class="uh">&nbsp;</span> 
			<button type="submit" class="btn btn-sub">登录</button>
		  </li>
		</ul>
	</form>
	<!-- end .loginForm --></div>
	<div class="rightBox">
		<p>还未开通?赶快注册一个!</p>
		<a href="<?php echo U('home/Public/register');?>" class="btn_reg mt10">注册新账号</a>
	</div>
	<div class="footer" style="color: white;bottom:0px;">
    <!-- 底部状态栏 start -->
<div region="south" id="bottomBar" class="bottomBar" style="text-align:center; clear:both; line-height: 30px; background:none; text-align: center;">
    <?php echo ($ts['site']['site_icp']); ?>
    <div id="site_analytics_code" style="display:none;">
        <?php echo (base64_decode($site["site_analytics_code"])); ?>
    </div>
</div>
<!-- 底部状态栏 end -->
<!-- 底部状态栏 end -->
</div>
<!--[if lt IE 8]>
<script language="javascript">
$(document).ready(function(e) {
	$('.registerBox').height($(window).height()-$('.headerback').height());
});
</script>
<![endif]-->
<script>
(function($){
	$(window).load(function(){
		$('.scrollBox').jScrollPane();
	});
})(jQuery);

function initEvent(){
	//设置注册页面的高度
	if($('.registerBox').length){
		$('.registerBox').height($(window).height()-$('.headerback').height());
		
		if($('.scrollBox').length){
			//添加height的回调
			$.addCallback("height", function(){
				$('.scrollBox').jScrollPane('update');
			});
			$('.scrollBox').height(
				$('.registerBox').height()-210
			)
			//移除height的回调
			$.removeCallback('height');
		}
	}
}
</script>

<!-- end .registerBox --></div>
<script type="text/javascript">
$(document).ready(function(e) {
	var validator = $("#loginForm").validate({
		rules: {
			email: {
				required: true,
				minlength: 2
			},
			password: {
				required: true,
				minlength: 5
			}
<?php if(($login_verify_on)  ==  "1"): ?>,verify: {
				required: true,
				remote: {
					url: "<?php echo U('home/Public/isVerifyAvailable');?>",
					type: "post",
					data: {
						verify: function() {
							return $("#verify").val();
						}
					}
				}
			}<?php endif; ?>
		},
		messages: {
			email: {
				required: "<i class='warring'></i>请输入登录账号",
				minlength: jQuery.format("<i class='warring'></i>您的账号长度至少要超过{0}位")
			},
			password: {
				required: "<i class='warring'></i>请输入登录密码",
				rangelength: jQuery.format("<i class='warring'></i>请输入长度最少是{0}位字符的密码")
			}
<?php if(($login_verify_on)  ==  "1"): ?>,verify: {
				required: "<i class='warring'></i>请输入验证码",
				remote: jQuery.format("<i class='error'></i>{0} 验证码不正确")
			}<?php endif; ?>
		},

		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		},

		submitHandler: function(form) {
			form.submit();
		}
	});
});
</script>
  <!-- end .containerInner --></div>
<!-- end .container --></div>
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1000083231'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1000083231' type='text/javascript'%3E%3C/script%3E"));</script>
</body>
</html>