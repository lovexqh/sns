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
			<li class="checked"><i>2</i><span>填写基本信息</span></li>
			<li class="checked"><i>3</i><span>验证基本信息</span></li>
			<li class="checked"><i>4</i><span>看看推荐的人</span></li>
			<li><i>5</i><span>注册完成</span></li>
		</ul>
		<div class="clear"></div>
	<!-- end .progressBar --></div>
	<div class="scrollBox">
		<div class="followuser">
		<form action="<?php echo U('home/Public/followuser');?>" method="post" >
		<input type="hidden" name="followuser" value="1" />
			<div class="userlist">
				<ul>
<?php if(is_array($users)): ?><?php $i = 0;?><?php $__LIST__ = $users?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li>
						<div class="userPic">
							<a title="" href="javascript:;"><img src="<?php echo (getUserFace($vo["uid"],'m')); ?>" card="1"><i></i></a>
						</div>
						<input name="followuid[]" type="checkbox" value="<?php echo ($vo["uid"]); ?>" class="hide" />
						<a href="<?php echo U('home/space/index',array('uid'=>$vo['uid']));?>" target="_blank"><?php echo ($vo["uname"]); ?></a>
						(<?php echo ($vo["count"]); ?>人关注)
					</li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
				</ul>
				<div class="clear"></div>
			</div>
			<div class="bottomBox">
				<h3>关注其它人才能收到TA们分享的新鲜事。关注的人越多，交流越精彩。</h3>
				<div class="right">
					<label><input type="checkbox" name="all" />全选</label>
					<a href="javascript:;" onclick="random_next()">换一批</a>
				</div>
				<div class="clear"></div>
				<div class="buttonBar">
					<button class="btn next" type="submit">下一步</button>
					<a href="<?php echo U('home/public/regSuccess');?>">跳过&gt;&gt; </a>
				</div>
			</div>
		</form>
		<!-- end .followuser --></div>
	</div><!-- end .scrollBox -->
	<script language="javascript">
	$(document).ready(function(e) {
		//初始化头像的a链接
		$('.userlist').find('.userPic>a').attr('href','javascript:;');
		//初始化全选功能
		$('.followuser').on('click',"input:checkbox[name='all']",function(){
			var checked = $(this).attr('checked');
			if(checked=='checked' || checked==true){
				chk_user($('.userlist>ul>li'),1);
			}else{
				chk_user($('.userlist>ul>li'),0);
			}
		});
		//初始化所有头像点击事件
		$('.userlist').on('click','.userPic>a',function(){
			var checked = $(this).parent().parent().find("input:checkbox").attr('checked');
			if(typeof(checked)!='undefined' && (checked=='checked' || checked==true)){
				chk_user($(this),0);
			}else{
				chk_user($(this),1);
			}
		});
		//选中方法
		function chk_user($this,status){
			status = (typeof(status)=='undefined' ? 0 : status);
			if($this.parent().parent().find("input:checkbox:checked").val() && !status){
				$this.parent().parent().find('.userPic').removeClass('chk');
				$this.parent().parent().find("input:checkbox").removeAttr('checked');
			}else{
				$this.parent().parent().find('.userPic').addClass('chk');
				$this.parent().parent().find("input:checkbox").attr('checked','true');
			}
		}
	});
	//换一批功能
	function random_next(){
		$.get(U('home/Public/followuser',['r='+Math.random()]),function(html){
			$('.userlist').html($(html).find('.userlist').html());
		});
	}
	</script>
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