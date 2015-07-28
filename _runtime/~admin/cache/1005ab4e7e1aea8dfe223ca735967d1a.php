<?php if (!defined('THINK_PATH')) exit();?><?php if($isAdmin != '1'){ ?>
	<!doctype html>
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
	<script>
	function Jump(){
		window.location.href = '<?php echo ($jumpUrl); ?>';
	}
	document.onload = setTimeout("Jump()" , <?php echo ($waitSecond); ?>* 1000);
	</script>
	<div class="successBox statusBox">
		<div class="statusTopBorder"></div>
	<?php if(($status)  ==  "1"): ?><h2><?php echo ($message); ?></h2>
		<div class="statusContent">
			<i class="icon-success"></i>
		<?php if(isset($closeWin)): ?><p>系统将在 <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动关闭，如果不想等待,直接点击 <A HREF="<?php echo ($jumpUrl); ?>">这里</A> 关闭</p><?php endif; ?>
		<?php if(!isset($closeWin)): ?><p>系统将在 <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动跳转,如果不想等待,直接点击 <A HREF="<?php echo ($jumpUrl); ?>">这里</A> 跳转<br/>
			或者 <a href="<?php if(APP_NAME=='admin' || ACTION_NAME=='Admin'){echo U('admin/Index/index');}else{echo "__ROOT__/" ;} ?>">返回首页</a></p><?php endif; ?>
		</div><?php endif; ?>
	<?php if(($status)  ==  "0"): ?><h2 style="color:red"><?php echo ($message); ?></h2>
		<div class="statusContent">
			<i class="icon-error"></i>
		<?php if(isset($closeWin)): ?><p>系统将在 <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动关闭，如果不想等待,直接点击 <A HREF="<?php echo ($jumpUrl); ?>">这里</A> 关闭</p><?php endif; ?>
		<?php if(!isset($closeWin)): ?><p>系统将在 <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动跳转,如果不想等待,直接点击 <A HREF="<?php echo ($jumpUrl); ?>">这里</A> 跳转<br/>
		  或者 <a href="<?php if(APP_NAME=='admin' || ACTION_NAME=='Admin'){echo U('admin/Index/index');}else{echo "__ROOT__/" ;} ?>">返回首页</a></p><?php endif; ?>
		</div><?php endif; ?>
	<!-- end .registerBox --></div>
	  <!-- end .containerInner --></div>
<!-- end .container --></div>
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1000083231'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1000083231' type='text/javascript'%3E%3C/script%3E"));</script>
</body>
</html>

<?php }else { ?>
	<?php if($isAdmin != '1'){ ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php if(($ts['site']['page_title'])  !=  ""): ?><?php echo ($ts['site']['page_title']); ?> <?php echo ($ts['site']['site_name']); ?><?php else: ?><?php echo ($ts['site']['site_name']); ?><?php endif; ?></title>
<meta name="keywords" content="<?php echo ($ts['site']['site_header_keywords']); ?>" />
<meta name="description" content="<?php echo ($ts['site']['site_header_description']); ?>" />
<meta name="generator" content="GSN {VERSION}" />
<meta name="author" content="GSN" />
<meta name="copyright" content="2012-2013 gridinfo.com.cn." />
<meta name="MSSmartTagsPreventParsing" content="True" />
<meta http-equiv="MSThemeCompatible" content="Yes" />
<base href="__ROOT__" />
<link rel="stylesheet" type="text/css" href="__THEME__/desktop/css/themes/dskui/ui.css?<?php echo ($verhash); ?><?php echo time();?>" />
<link rel="stylesheet" type="text/css" href="__THEME__/desktop/css/themes/icon.css?<?php echo ($verhash); ?><?php echo time();?>" />
<link rel="stylesheet" type="text/css" href="__THEME__/desktop/css/common.css?<?php echo ($verhash); ?><?php echo time();?>" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/desktop/tbox/box.css" />
<link rel="stylesheet" href="__THEME__/desktop/css/scroll.css" type="text/css"/>
<script type="text/javascript" src="__PUBLIC__/js/desktop/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/jquery.callback.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/jquery.mousewheel.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/jquery.jscrollpane.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/mask.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/jquery.json-2.3.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/common.js?<?php echo ($verhash); ?><?php echo time();?>"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/load.js?<?php echo ($verhash); ?><?php echo time();?>"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/easyui/easyloader.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/tbox/box.js"></script>
<script language="javascript" type="text/javascript">
if(typeof easyloader == 'object') {
	easyloader.locale = "zh_CN";
}
</script>
<!--[if IE 6]>
<script type="text/javascript" src="__PUBLIC__/js/desktop/ie6.js?<?php echo ($verhash); ?>"></script>
<![endif]-->
<!--[if lt IE 9]>
<script src="__PUBLIC__/js/html5.js"></script>
<![endif]-->
<script language="javascript" type="text/javascript">
var _UID_   = <?php echo (int) $uid; ?>;
var _MID_   = <?php echo (int) $mid; ?>;
var _URL_   = '<?php echo getSiteUrl();?>';
var _ROOT_  = '__ROOT__';
var _THEME_ = '__THEME__';
var _PUBLIC_ = '__PUBLIC__';
var _APP_INDEX_ = '<?php echo ($GLOBALS['ts']['_app']); ?>/<?php echo ($GLOBALS['ts']['_mod']); ?>/<?php echo ($GLOBALS['ts']['_act']); ?>';
var _APP_PUBLIC_ = '../Public';
var _LENGTH_ = <?php echo (int) $GLOBALS['ts']['site']['length']; ?>;
var _LANG_SET_ = '<?php echo LANG_SET; ?>';
var DZZSCRIPT="<?php echo U('desktop/Index/index');?>";
var STYLEID = '<?php echo ($style["styleid"]); ?>';
var STATICURL = '__THEME__';
var IMGDIR = '__THEME__/images/common';
var VERHASH = '<?php echo ($verhash); ?>';
var charset = 'utf-8';
var discuz_uid = '<?php echo ($uid); ?>';
var cookiepre = '<?php echo ($config["cookie"]["cookiepre"]); ?>';
var cookiedomain = '<?php echo ($config["cookie"]["cookiedomain"]); ?>';
var cookiepath = '<?php echo ($config["cookie"]["cookiepath"]); ?>';
var showusercard = '<?php echo ($setting["showusercard"]); ?>';
var attackevasive = '<?php echo ($config["security"]["attackevasive"]); ?>';
var disallowfloat = '<?php echo ($setting["disallowfloat"]); ?>';
var creditnotice = "<?php if($setting['creditnotice']){echo $setting['creditnames'];} ?>";
var defaultstyle = '<?php echo ($style["defaultextstyle"]); ?>';
var REPORTURL = '$_G[currenturl_encode]';
var SITEURL = '__ROOT__';
var JSPATH = '../Public/js/';
var IMGPATH = '../Public/images/';
</script>

<!-- 判断是否为tabs框架页打开 start -->
<?php if(!empty($_REQUEST['iframe'])){
	$layout = 'class="iframe"';
	$uitabs = ''; ?>
<script type="text/javascript" src="__PUBLIC__/js/desktop/iframe.js?<?php echo ($verhash); ?><?php echo time();?>"></script>
<?php if($_REQUEST['iframe'] == 'content'){ ?>
<script>
$("body",parent.document).find('.panel-body').animate({scrollTop: ($("body",parent.document).find('.panel-nav-map').height()+55)}, 'slow');
</script>
<?php } ?>
<?php }else{
	$layout = 'class="easyui-layout"';
	$uitabs = 'easyui-tabs'; ?>
<script>
$(document).ready(function(e) {
	//$.mask();
});
</script>
<?php } ?>
<!-- 判断是否为tabs框架页打开 end -->
</head>
<body id="nv_dsk" unselectable="on" onselectstart="return event.srcElement.type== 'text';" <?php echo ($layout); ?>>
<div id="append_parent" style="z-index:9999;"></div><div id="ajaxwaitid" style="z-index:9999;"></div>

<?php }else { ?>
<body>
<link href="__THEME__/public.css?20110429" rel="stylesheet" type="text/css" />
<link href="__THEME__/layout.css?20110429" rel="stylesheet" type="text/css" />
<link href="__THEME__/main.css" rel="stylesheet" type="text/css" />
<?php } ?>
<script>
function Jump(){
    window.location.href = '<?php echo ($jumpUrl); ?>';
}
document.onload = setTimeout("Jump()" , <?php echo ($waitSecond); ?>* 1000);
</script>
<link href="<?php echo ($publicCss); ?>" rel="stylesheet" type="text/css">
<base target="_self" />

<?php if(($status)  ==  "1"): ?><div class="Prompt">
  <div class="Prompt_top"></div>
  <div class="Prompt_con">
    <dl>
      <dt>提示信息</dt>
      <dd><span class="Prompt_ok"></span></dd>
      <dd>
        <h2><?php echo ($message); ?></h2>
        <?php if(isset($closeWin)): ?><p>系统将在 <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动关闭，如果不想等待,直接点击 <A HREF="<?php echo ($jumpUrl); ?>">这里</A> 关闭</p><?php endif; ?>
        <?php if(!isset($closeWin)): ?><p>系统将在 <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动跳转,如果不想等待,直接点击 <A HREF="<?php echo ($jumpUrl); ?>">这里</A> 跳转<br/>
            或者 <a href="<?php if(APP_NAME=='admin' || ACTION_NAME=='Admin'){echo U('admin/Index/index');}else{echo "__ROOT__/" ;} ?>">返回首页</a></p><?php endif; ?>
      </dd>
    </dl>
    <div class="c"></div>
    </div>
    <div class="Prompt_btm"></div>
  </div><?php endif; ?>
<?php if(($status)  ==  "0"): ?><div class="Prompt">
    <div class="Prompt_top"></div>
  <div class="Prompt_con">
    <dl>
      <dt>提示信息</dt>
      <dd><span class="Prompt_x"></span></dd>
      <dd>
      <h2 style="color:red"><?php echo ($message); ?></h2>
        <?php if(isset($closeWin)): ?><p>系统将在 <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动关闭，如果不想等待,直接点击 <A HREF="<?php echo ($jumpUrl); ?>">这里</A> 关闭</p><?php endif; ?>
      <?php if(!isset($closeWin)): ?><p>系统将在 <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动跳转,如果不想等待,直接点击 <A HREF="<?php echo ($jumpUrl); ?>">这里</A> 跳转<br/>
          或者 <a href="<?php if(APP_NAME=='admin' || ACTION_NAME=='Admin'){echo U('admin/Index/index');}else{echo "__ROOT__/" ;} ?>">返回首页</a></p><?php endif; ?>
      </dd>
    </dl>
    <div class="c"></div>
    </div>
    <div class="Prompt_btm"></div>
  </div><?php endif; ?>
<?php if($isAdmin != '1'){ ?>
    <!-- 底部状态栏 start -->
<div region="south" id="bottomBar" class="bottomBar" style="text-align:center; clear:both; line-height: 30px; background:none; text-align: center;">
    <?php echo ($ts['site']['site_icp']); ?>
    <div id="site_analytics_code" style="display:none;">
        <?php echo (base64_decode($site["site_analytics_code"])); ?>
    </div>
</div>
<!-- 底部状态栏 end -->
<!-- 底部状态栏 end -->
<!--tab-main右键菜单 start-->
<div id="tabs-header-contextmenu" class="easyui-menu" data-options="onClick:menuHandler" style="width:120px; display:none">  
	<div data-options="name:'closeall'">关闭全部选项卡</div> 
	<div data-options="name:'closeother'">关闭其他选项卡</div> 
</div> 
<!--tab-main右键菜单 end-->

<!--tab-main标题列表 start-->
<div id="tabs-header-list" class="easyui-menu"></div>
<!--tab-main标题列表 end-->

<!--UI Dialog start-->
<div id="ui-dialog"></div>
<!--UI Dialog end-->
<!-- Ricker Yu add 2013-9-3 ajax得到视频反馈信息操作
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery.get('<?php echo U("video/Index/ajaxVideo");?>',
			{time:new Date().getTime()},
			function(data){
				
			});
});
</script>
-->
<?php $redirect = base64_decode($_GET['redirect']); ?>
<?php $title = $_GET['title']; ?>
<script type="text/javascript">
<!--
//执行动态打开新tab
$(function(){
	title = "<?php echo empty($_GET['title'])?'内容详情':$_GET['title']; ?>";
	url = "<?php echo ($redirect); ?>";
	if((url.indexOf('app')!=-1 && url.indexOf('mod')!=-1 && url.indexOf('act')!=-1) || url.indexOf('/app/')!=-1){
        if ($('#tabs-main').tabs('exists',title)){
            $('#tabs-main').tabs('select', title);
            tabs.refresh(title,url);
        } else {
            var content = '<iframe scrolling="no" frameborder="0" src="'+url+'" style="width:100%;height:100%;"></iframe>';
            $('#tabs-main').tabs('add',{
                title:title,
                content:content,
                closable:true
            });
        }
		try{
			if(typeof(iframe)!='undefined'){
				parent.mask.show();	
			}else{
				mask.show();
			}
		}catch(e){}}});
//-->
</script>

<!-- Ricker Yu add 2013-9-3 ajax得到视频反馈信息操作 － end -->
</body>
</html>

<?php }else { ?>
    </body>
<?php } ?>
<?php } ?>