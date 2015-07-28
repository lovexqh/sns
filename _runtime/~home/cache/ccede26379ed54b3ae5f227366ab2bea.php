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
<div class="registerPicBg">
	<img src="__THEME__/desktop/images/register/backgroup.gif" width="1026" />
<!-- end .registerPicBg --></div>
<div class="registerBox">
	<h2>新用户注册</h2>
	<div class="progressBar">
		<ul>
			<li class="checked"><i>1</i><span>选择角色</span></li>
			<li><i>2</i><span>填写基本信息</span></li>
			<li><i>3</i><span>验证基本信息</span></li>
			<li><i>4</i><span>看看推荐的人</span></li>
			<li><i>5</i><span>注册完成</span></li>
		</ul>
		<div class="clear"></div>
	<!-- end .progressBar --></div>
	
<?php $regInfo=model('Xdata')->lget('register'); ?>
<?php if($invite_info){ ?>  	  
<!-- 邀请加入显示邀请人的信息 -->
    <div class="invite_userInfo">
		<div class="left mt10 ml10" style="width:100px;">
			<a href="<?php echo U('home/Space/index', array('uid'=>$invite_info['uid']));?>" target="_blank">
               <img style="width:80px; height:80px;" src="<?php echo (getUserFace($invite_info["uid"],'big')); ?>" />
            </a>
		</div>
		<div class="left mt10 ml10">
			<div class="info">
	  		    <div class="f14px lh30 mb10"><strong>hi, 我是<?php echo (getUserName($invite_info["uid"])); ?></strong></div>
                <div class="f14px lh20">这里貌似挺给力的，快来注册一个吧！</div>
    		</div>
		</div>
    </div>	
<?php } ?> 
<!-- 邀请加入显示邀请人的信息结束 -->
	<div class="identityBox">

		<div class="realname_area">
			<div class="reg_type_word">实名注册:</div>
			<ul>
				<li>
					<a href="<?php echo U('home/Public/register', array('identity'=>'student'));?>" class="student" hidefocus style="margin-left:64px;"></a>
					<span class="identityword stuiden">我是学生</span>
				</li>
				<li>
					<a href="<?php echo U('home/Public/register', array('identity'=>'teacher'));?>" class="teacher" hidefocus style="margin-left:64px;"></a>
					<span class="identityword">我是老师</span>
				</li>
				<div class="clear"></div>
			</ul>
		</div>

		<div class="openreg_area">
			<div class="reg_type_word">开放注册:</div>
			<a href="<?php echo U('home/Public/register', array('identity'=>'visitor'));?>" style="font-size:18px;" class="guest"></a>
			<span class="guestword">访客注册</span>
		</div>
	<!-- end .identityBox --></div>
	
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
  <!-- end .containerInner --></div>
<!-- end .container --></div>
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1000083231'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1000083231' type='text/javascript'%3E%3C/script%3E"));</script>
</body>
</html>