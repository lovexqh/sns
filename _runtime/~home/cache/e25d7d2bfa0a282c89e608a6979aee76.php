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
<div class="sidesnow">
		<aside style="position: relative;">
			<img src="__THEME__/desktop/images/index/esn_14.png" class="pngfix" />
			<img src="__THEME__/desktop/images/index/android.png" style="position: absolute;width: 100px;height: 100px;top: 275px;left: 115px;" />
			<img src="__THEME__/desktop/images/index/IOS.png" style="position: absolute;width: 100px;height: 100px;top: 275px;left: 234px;" />
            <span style="position: absolute;top: 380px;left: 115px;text-align: center;display: inline-block;width: 100px;background-color: #DFDFDF;border-radius: 8px;height: 22px;line-height: 22px;">Android</span>
            <span style="position: absolute;top: 380px;left: 234px;text-align: center;display: inline-block;width: 100px;background-color: #DFDFDF;border-radius: 8px;height: 22px;line-height: 22px;">IOS</span>
        </aside>
	  <!-- end .sidesnow --></div>
	  <?php if( !isset($_SESSION["userInfo"])): ?><article class="loginbar">
	  	<div id="login_tips" class="hide"></div>
		<form name="loginform" action="<?php echo U('home/Public/doAjaxLogin');?>" method="post" class="loginbox" onSubmit="return checkLogin(this)">
			<ul>
				<li class="usr">
					<i class="icon-usr"></i>
					<input type="text" id="email" name="email" class="ipt-txt" default="邮箱/身份证号/手机号/昵称" onkeyup="checkRelevance(this)" maxlength="45" /></li>
				<li class="pwd">
					<i class="icon-pwd"></i>
					<input type="password" id="password" name="password" class="ipt-txt" default="密码" maxlength="20" /></li>
				<?php $logincount = $_COOKIE['logincount']; ?>
				<li id="codeWarp" style="display:<?php if($logincount < 3): ?>none<?php endif; ?>" class="code">
					<input type="text" id="verify" name="verify" class="ipt-txt" title="验证码" default="验证码" />
					<a href="###" onClick="changeverify('verifyimg')"><img src="__ROOT__/public/captcha.php" id="verifyimg" alt="换一张" class="img-verify" /></a>
					<a  href="###" onClick="changeverify('verifyimg')">换一换</a></li>
				<li class="remember">
					<label><input id="remember" name="remember" type="checkbox" value="1" />记住登录状态</label></li>
				<li>
					<button type="submit" class="btn-sub">登录</button><a href="<?php echo U('home/Public/sendPassword');?>">忘记密码?</a>|<a href="<?php echo U('home/Public/register');?>">注册</a></li>
			</ul>
		</form>
	  <!-- end .loginbox --></article><?php endif; ?>
	  <?php if(isset($_SESSION["userInfo"])): ?><div class="learnbox">
			<button type="button" class="btn-learn" onclick="window.location.href='<?php echo U('desktop');?>'">进入体验</button>
		<!-- end .loginbox --></div><?php endif; ?>
  <!-- end .containerInner --></div>
<!-- end .container --></div>

<div class="containerMain">
	<!--<div class="containerFooter">-->
		<!--<aside>-->
		  <!--<p>一分钟了解“道学·网络空间”<i class="icon-up"></i></p>-->
		<!--</aside>-->
		<!--<div class="footerback"></div>-->
	<!--&lt;!&ndash; end .containerFooter &ndash;&gt;</div>-->
	
	<!--<div class="containerHeader">-->
		<!--<aside style="width:800px;">-->
			<!--<ul style="width:800px;">-->
				<!--<li data-id="cc-about" class="sele"><a href="#about">关于社区</a></li>-->
				<!--<li data-id="cc-schoolinfo"><a href="#schoolinfo">学校概况</a></li>-->
				<!--<li data-id="cc-baseinfo"><a href="#baseinfo">基层动态</a></li>-->
				<!--<li data-id="cc-schoolnews"><a href="#schoolnews">公示通知</a></li>-->
				<!--<li data-id="cc-client"><a href="#client">客户端登陆</a></li>-->
				<!--<li data-id="cc-suggestion"><a href="#suggestion">给我们提建议</a></li>-->
				<!--<li><i class="icon-down"></i></li>-->
			<!--</ul>-->
		<!--</aside>-->
	<!--&lt;!&ndash; end .containerHeader &ndash;&gt;</div>-->
	<div class="clientMain">
		<div class="containerInner">
		
			<a name="cc-about"></a>
			<div class="mainWarp">
				<h3>什么是道学·网络空间?</h3>
				<p>道学网络空间实际上是再造了一个网络上的“大学”，是“虚拟”和“物理”环境的完美结合，是思想传播的窗口，老师的讲台和实验室，学生的课桌和舞台，校友永久的回忆录。</p>
				<div class="tc mt30">
					<img src="__THEME__/desktop/images/index/nindex1.jpg" width="786" height="387" />
				</div>
			<!-- end .mainWarp --></div>
			<div class="line"></div>			
			
			<a name="cc-schoolinfo"></a>
			<div class="mainWarp">
				<h3>吉林建筑大学校园门户</h3>
				<dl>
					<dt class="pic-left">
					<img src="__THEME__/desktop/images/index/nindex2.jpg" width="470" height="271" border="0" usemap="#Mapxxjj" />
					  <map name="Mapxxjj" id="Mapxxjj">
						<area shape="rect" coords="6,201,83,237" href="http://www.jlju.edu.cn/index.php?m=content&amp;c=index&amp;a=lists&amp;catid=21" target="_blank" />
						<area shape="rect" coords="100,203,183,239" href="http://www.jlju.edu.cn/index.php?m=content&amp;c=index&amp;a=lists&amp;catid=23" target="_blank" />
						<area shape="rect" coords="192,202,279,239" href="http://www.jlju.edu.cn/index.php?m=content&amp;c=index&amp;a=lists&amp;catid=52" target="_blank" />
						<area shape="rect" coords="290,203,371,236" href="http://www.jlju.edu.cn/index.php?m=content&amp;c=index&amp;a=lists&amp;catid=24" target="_blank" />
						<area shape="rect" coords="383,203,465,236" href="http://www.jlju.edu.cn/index.php?m=content&amp;c=index&amp;a=lists&amp;catid=25" target="_blank" />
					  </map>
					</dt>
					<dd>
						<span style="font-size:25px; font-weight:bold; color:#333">学校概况</span>
					</dd>
					<dd>
						<p>各类资源，各种格式，要怎么处理？资源广场提供涵盖各个学科最丰富、最全面的学习资料、资源，支持各种标准格式，将自有资源和外采资源优势互补，同一到一个界面。为广大师生提供最专业的信息交互平台。</p>
                        <p><a href="http://www.jlju.edu.cn/index.php?m=content&c=index&a=lists&catid=21" target="_blank">&gt;&gt; 进入学校简介</a></p>
					</dd>
					<dd><i class="arrow-l"></i></dd>
				</dl>
				<div class="clear"></div>
				
				<a name="cc-baseinfo"></a>
				<dl>
					<dt class="pic-right">
						<div style="width:405px;height:auto;border:1px solid #dcdcdc;background:white;position:relative;">
							<table width="405" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td colspan="2"><img src="__THEME__/desktop/images/index/nindex3.jpg" /></td>
							  </tr>
							  <?php if(is_array($jcdt)): ?><?php $i = 0;?><?php $__LIST__ = $jcdt?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vojc): ?><?php ++$i;?><?php $mod = ($i % 2 )?><tr>
								<td width="320" height="30" style="padding-left:20px;"><a href="<?php echo ($vojc['link']); ?>" target="_blank"><?php echo ($vojc['shorttitle']); ?></a></td>
								<td width="85"><?php echo ($vojc['pubDate'][0]); ?></td>
							  </tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>	
							  <tr>
								<td colspan="2" height="10">&nbsp;</td>
							  </tr>								  
							</table>
						</div>
					</dt>
					<dd>
						<span style="font-size:25px; font-weight:bold; color:#333">基层动态</span>
					</dd>
					<dd>
						<p>近距离参与校园活动，随时更新活动状态，建大信息一手掌握，做真正的建大人。</p>
                        <p><a href="http://www.jlju.edu.cn/index.php?m=content&c=index&a=lists&catid=19" target="_blank">&gt;&gt; 进入基层动态</a></p>
					</dd>
					<dd><i class="arrow-r"></i></dd>
				</dl>
				<div class="clear" style="height:50px;"></div>
				
				<dl>
					<dt class="pic-left">
						<div style="width:405px;height:auto;border:1px solid #dcdcdc;background:white;position:relative;">
							<table width="405" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td><img src="__THEME__/desktop/images/index/nindex4.jpg" /></td>
							  </tr>
							  <?php if(is_array($jdxw)): ?><?php $i = 0;?><?php $__LIST__ = $jdxw?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vojc): ?><?php ++$i;?><?php $mod = ($i % 2 )?><tr>
								<td align="center"><a href="<?php echo ($vojc['link']); ?>" style="display:block;padding:0px;padding:5px 10px;border:1px solid #e6e6e6;width:354px;height:230px;overflow:hidden;" target="_blank"><img width="354" height="230" src="<?php echo ($vojc['pic'][0]); ?>" /></a></td>							
							  </tr>
							   <tr>
								<td align="left" style="padding:10px;"><?php echo ($vojc['description']); ?></td>							
							  </tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>								  						  
							</table>
						</div>
					
					</dt>
					<dd>
						<span style="font-size:25px; font-weight:bold; color:#333">建大新闻</span>
					</dd>
					<dd>
						<p>拥有学校自己的记者团队，努力建设成为舆论引导有力、报道出新出彩、管理科学规范、事业健康发展的派出机构，为全校师生提供最新鲜的建大新闻，了解你我身边的建大实事。</p>
                        <p><a href="http://www.jlju.edu.cn/index.php?m=content&c=index&a=lists&catid=11" target="_blank">&gt;&gt; 进入建大新闻</a></p>
					</dd>
					<dd><i class="arrow-l"></i></dd>
				</dl>
				<div class="clear"></div>
				
				<dl>
					<dt class="pic-right">					
						<img src="__THEME__/desktop/images/index/nindex6.jpg" width="571" height="174" border="0" usemap="#Mapxysz" />
						<map name="Mapxysz" id="Mapxysz">
						  <area shape="rect" coords="12,11,125,38" href="http://219.217.90.67" target="_blank" />
						  <area shape="rect" coords="159,12,277,38" href="http://dept.jliae.edu.cn/tumu/" target="_blank" />
						  <area shape="rect" coords="300,14,423,37" href="http://dept.jliae.edu.cn/shizheng/index.asp" target="_blank" />
						  <area shape="rect" coords="445,12,559,40" href="http://dept2.jliae.edu.cn/trafic/default.asp" target="_blank" />
						  <area shape="rect" coords="8,45,135,68" href="http://58.155.183.65:8080/jsj/index.jsp" target="_blank" />
						  <area shape="rect" coords="150,45,280,68" href="http://219.217.80.240:8888/index.php" target="_blank" />
						  <area shape="rect" coords="301,47,418,66" href="http://dept.jliae.edu.cn/cailiao/" target="_blank" />
						  <area shape="rect" coords="445,45,556,66" href="http://ck.jlju.edu.cn/" target="_blank" />
						  <area shape="rect" coords="33,73,105,96" href="http://glxy.jliae.edu.cn:8080/manageSchool/web/web/index.jsp" target="_blank" />
						  <area shape="rect" coords="168,74,259,95" href="http://dept.jliae.edu.cn/yishuxueyuan/" target="_blank" />
						  <area shape="rect" coords="328,74,395,97" href="http://dept.jliae.edu.cn/social/" target="_blank" />
						  <area shape="rect" coords="464,72,538,97" href="http://58.155.180.253/" target="_blank" />
						  <area shape="rect" coords="17,105,129,127" href="#http://dept3.jlju.edu.cn/sxzzb/" target="_blank" />
						  <area shape="rect" coords="174,104,253,128" href="http://dept2.jliae.edu.cn/jichubu/default.asp" target="_blank" />
						  <area shape="rect" coords="327,105,394,129" href="http://dept3.jlju.edu.cn/tiyu/" target="_blank" />
						  <area shape="rect" coords="458,102,547,132" href="http://adult.jlju.edu.cn/" target="_blank" />
						  <area shape="rect" coords="33,136,119,156" href="http://dept.jlju.edu.cn:8082/" target="_blank" />
						</map>
					</dt>
					<dd>
						<span style="font-size:25px; font-weight:bold; color:#333">院系设置</span>
					</dd>
					<dd>
						<p>学校设有建筑与规划学院、土木工程学院、交通科学与工程学院等18个院部，开设本科专业45个，涵盖工、管、理、艺、文、法六大学科门类。</p>                        
					</dd>
					<dd><i class="arrow-r"></i></dd>
				</dl>
				<div class="clear"></div>
				
				<dl>
					<dt class="pic-left">
					<img  src="__THEME__/desktop/images/index/nindex7.jpg" width="488" height="257" border="0" usemap="#Mapxskj" />
					<map name="Mapxskj" id="Mapxskj">
						<area shape="rect" coords="43,20,158,50" href="http://219.217.80.198:8081/jljgkyc/" target="_blank" />
						<area shape="rect" coords="263,18,402,44" href="http://dept.jliae.edu.cn/wlsyjxzx/best/" target="_blank" />
						<area shape="rect" coords="203,98,381,128" href="http://www.dbacrc.org/" target="_blank" />
						<area shape="rect" coords="21,55,194,78" href="http://dept.jliae.edu.cn:8081/" target="_blank" />
						<area shape="rect" coords="212,45,449,68" href="http://dept3.jlju.edu.cn/handi/index.asp" target="_blank" />
						<area shape="rect" coords="222,131,370,154" href="http://dept3.jlju.edu.cn/gjs/" target="_blank" />
					</map>
					
					</dt>
					<dd>
						<span style="font-size:25px; font-weight:bold; color:#333">学术科技</span>
					</dd>
					<dd>
						<p>学校在北方寒地建筑节能、先进建筑结构材料和绿色建筑材料、先进工程
装备制造、热能回收与利用等领域开展重大工程关键核心技术研究，为推动区域支柱产业和高新技术发展提供了有力科技支撑。</p>                       
					</dd>
					<dd><i class="arrow-l"></i></dd>
				</dl>
				<div class="clear"></div>
				<style>					.nlisty{width:429px;height:23px;line-height:23px;padding-left:25px;background:url(__THEME__/desktop/images/index/ndian.jpg) no-repeat 10px 10px;overflow:hidden;}
				.nlisty a{font-size:12px;color:#565656;display:block;width:345px;height:23px;overflow:hidden;float:left;}
				.nlisty a:hover{color:#eb6100;}
				.nlisty span{display:block;width:84px;height:23px;float:right;}
				</style>
				<a name="cc-schoolnews"></a>				
				<dl>
					<dt class="pic-left">
						<ul style="width:454px;height:auto;clear:both;background:url(__THEME__/desktop/images/index/nindex11bk.jpg) repeat-y;list-type:none;">
							<li><img src="__THEME__/desktop/images/index/nindex11t.jpg" /></li>
							<?php if(is_array($gstz)): ?><?php $i = 0;?><?php $__LIST__ = $gstz?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vogs): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li class="nlisty"><a href="<?php echo ($vogs['link']); ?>" target="_blank"><?php echo ($vogs['title']); ?></a><span><?php echo ($vogs['pubDate'][0]); ?></span></li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>							
							<li style="width:100%;height:10px;clear:both;overflow:hidden;"><img src="__THEME__/desktop/images/index/nindex11b.jpg" /></li>
						</ul>
					</dt>
					<dd>
						<span style="font-size:25px; font-weight:bold; color:#333">公示通知</span>
					</dd>
					<dd>
						<p>学校在北方寒地建筑节能、先进建筑结构材料和绿色建筑材料、先进工程
装备制造、热能回收与利用等领域开展重大工程关键核心技术研究，为推动区域支柱产业和高新技术发展提供了有力科技支撑。</p>                       
					</dd>
					<dd><i class="arrow-l"></i></dd>
				</dl>
				<div class="clear"></div>
				
				<dl>
					<dt class="pic-right">
					
						<img src="__THEME__/desktop/images/index/nindex8.jpg" width="567" height="177" border="0" usemap="#Mapkstd" />
						  <map name="Mapkstd" id="Mapkstd">
							<area shape="rect" coords="7,43,138,87" href="http://jwc.jliae.edu.cn/" target="_blank" />
							<area shape="rect" coords="149,40,279,86" href="http://yjs.jliae.edu.cn/" target="_blank" />
							<area shape="rect" coords="292,38,424,88" href="http://dept3.jlju.edu.cn/renshichu/" target="_blank" />
							<area shape="rect" coords="431,41,561,89" href="http://dept3.jlju.edu.cn/netcenter/" target="_blank" />
						  </map>
					
					</dt>
					<dd>
						<span style="font-size:25px; font-weight:bold; color:#333">快速通道</span>
					</dd>
					<dd>
						<p>教务处，研究生处，人事处，网络中心，便捷入口，轻松点击。</p>                        
					</dd>
					<dd><i class="arrow-r"></i></dd>
				</dl>
				<div class="clear"></div>
				
				<dl>
					<dt class="pic-left">
						<img src="__THEME__/desktop/images/index/nindex9.jpg" width="488" height="271" border="0" usemap="#Mapzsjy" />
						  <map name="Mapzsjy" id="Mapzsjy">
							<area shape="rect" coords="36,64,182,122" href="http://dept.jlju.edu.cn/zj/" target="_blank" />
							<area shape="rect" coords="221,45,446,104" href="http://yjs.jliae.edu.cn/" target="_blank" />
							<area shape="rect" coords="231,137,422,190" href="http://jgjy.jliae.edu.cn/" target="_blank" />
							<area shape="rect" coords="39,150,187,213" href="http://adult.jlju.edu.cn/" target="_blank" />
						  </map>
					
					</dt>
					<dd>
						<span style="font-size:25px; font-weight:bold; color:#333">招生就业</span>
					</dd>
					<dd>
						<p>打破了“时间”、“空间”的限制，招生就业功能划分更清晰，使就业人人参与成为可能，用心为学生创造更多学习就业机会。</p>                       
					</dd>
					<dd><i class="arrow-l"></i></dd>
				</dl>
				<div class="clear"></div>
				
				<dl>
					<dt class="pic-right">
						<img src="__THEME__/desktop/images/index/nindex10.jpg" width="571" height="195" border="0" usemap="#Mapqt" />
						  <map name="Mapqt" id="Mapqt">
							<area shape="rect" coords="15,37,137,73" href="http://219.217.80.144/" target="_blank" />
							<area shape="rect" coords="160,35,274,73" href="http://mail.jliae.edu.cn/" target="_blank" />
							<area shape="rect" coords="300,33,412,76" href="mailto:yuanzhang@jliae.edu.cn" target="_blank" />
							<area shape="rect" coords="440,34,558,77" href="http://oa.jliae.edu.cn/index/index.php" target="_blank" />
							<area shape="rect" coords="12,89,127,134" href="http://www.jlju.edu.cn/index.php?m=content&amp;c=index&amp;a=lists&amp;catid=13" target="_blank" />
							<area shape="rect" coords="158,90,272,133" href="http://www.jlju.edu.cn/index.php?m=content&amp;c=index&amp;a=lists&amp;catid=17" target="_blank" />
							<area shape="rect" coords="296,93,411,131" href="http://www.jlju.edu.cn/index.php?m=content&amp;c=index&amp;a=lists&amp;catid=18" target="_blank" />
							<area shape="rect" coords="444,91,555,132" href="http://www.jlju.edu.cn/" target="_blank" />
						  </map>
					
					</dt>
					<dd>
						<span style="font-size:25px; font-weight:bold; color:#333">其它功能</span>
					</dd>
					<dd>
						<p>提供更多校园网络服务功能，精彩应用，让一切操作更简单。</p>                        
					</dd>
					<dd><i class="arrow-r"></i></dd>
				</dl>
				<div class="clear"></div>
                
			<!-- end .mainWarp --></div>
			<div class="line"></div>
			
			<a name="cc-client"></a>
			<div class="mainWarp">
				<h3>道学·网络空间多版本终端，选择您喜欢的随时随地登录</h3>
				<div class="tc mt30">
					<img src="__THEME__/desktop/images/index/main_64.png" />
				</div>
				
				<div class="mobilebox">
					<div class="tc">
						<img src="__THEME__/desktop/images/index/main_67.png" />
						<input type="button" value="下载试用" class="btn-down" onclick="alert('此应用正在紧张开发中...');" />
					</div>
				</div>
				<div class="imbox">
					<div class="tc">
						<img src="__THEME__/desktop/images/index/main_69.png" />
						<input type="button" value="下载试用" class="btn-down" onclick="alert('此应用正在紧张开发中...');" />
					</div>
				</div>
				<div class="clear"></div>
			
			<!-- end .mainWarp --></div>
			<div class="line"></div>
				
			<a name="cc-suggestion"></a>
			<div class="mainWarp">
				<h3>道学·网络空间需要您的反馈、建议</h3>
				<p>尊敬的各位使用者，欢迎您的到来！道学·网络空间是一套全新的在线教学模式，系统的发展离不开您的支持，如果您发现了系统BUG，或者您有更好的想法，请按照如下两种方式提交您的问题，我们尽快予以回答，最好是截个图，更能说明问题，也便于更快的响应您提交的问题，再次感谢您对我们的支持！</p>
				<dl>
					<dt class="pic-left"><img src="__THEME__/desktop/images/index/main_70.jpg" /></dt>
					<dd>
						<span style="font-size:25px; font-weight:bold; color:#333">通道一：ENC-IM即时通</span>
					</dd>
					<dd>
						<p>即时通讯软件是我们社区的一个功能软件，具备及时聊天功能，您可以下载使用，和您社区的同事进行沟通，同时我们提供了几个客服账户，您可以联系他们。</p>
                        <p><input type="button" value="下载试用" class="btn-down" onclick="alert('此应用正在紧张开发中...');" /></p>
					</dd>
					<dd><i class="arrow-l"></i></dd>
				</dl>
				<div class="clear"></div>
				
				<dl>
					<dt class="pic-right"><img src="__THEME__/desktop/images/index/main_71.jpg" /></dt>
					<dd>
						<span style="font-size:25px; font-weight:bold; color:#333">通道二：官方微博</span>
					</dd>
					<dd>
						<p>您可以通过关注我们的官方博客，给我们留言，反馈问题及建议并得到工作人员的解答。</p>
                           <p><a href="<?php echo U('desktop', array('cur' =>'sys_4'));?>" target="_blank">&gt;&gt; 进入“官方微博”BUG反馈</a></p>
					</dd>
					<dd><i class="arrow-r"></i></dd>
				</dl>
				<div class="clear"></div>
			</div>
			
			<!-- end .mainWarp --></div>
			<div class="line"></div>
			
			<div class="footer">
				<!-- <p>Powered by <A href="http://www.gridinfo.com.cn/" target="_blank">吉林建筑大学</A> <A href="http://www.gridinfo.com.cn/" target="_blank">山东锐杰网格信息技术有限公司</A> <A href="http://www.miibeian.gov.cn/" target="_blank">鲁ICP备09072052号</A></p>-->
			</div>
		<!-- end .containerInner --></div>
		
		<a href="#top" class="top_stick" title="回顶部">&nbsp;</a>
		
<!--[if lt IE 8]>
<script language="javascript">
$(document).ready(function(e) {
	$('.mainWarp>dl').each(function(index, element) {
		$(this).find('dt').width($(this).find('dt').width());
		$(this).find('dd').width($('.mainWarp').width()-$(this).find('dt').width()-25);
		$(this).find('dd').css({float:'left'});
	});
	$('.containerHeader').find('.icon-down').css({marginTop:13});
});
</script>
<![endif]-->
<script language="javascript">
//页面重置时调用
function initEvent(){
	if(ismain==0){
		if($('.containerMain').length)
			$('.containerMain').css({top:$(window).height()-$('.footerback').height()});
	}
	//设置更多内容的滚动条
	if($('.clientMain').length)
		$('.clientMain').height($(window).height()-$('.footerback').height());
}

$mainMenu = $('.containerHeader').find('li');

/*顶部建议功能*/
function showSuggest(){
	//展开底部内容区域
	expandMain();
	
	$(".clientMain").animate({scrollTop:3979}, 'slow');
}
	
/*展开底部内容区域*/
function expandMain(){
	$('.containerMain').animate({'top':-$('.footerback').height()},function(){
		ismain  = 1;
		//给主要内容顶部的按钮赋值
		$mainMenu.each(function(i, e) {
			if(typeof($(this).attr('data-id'))!='undefined' && typeof($(this).attr('scrolltop'))=='undefined')
				$(this).attr('scrolltop',$("a[name='"+$(this).attr('data-id')+"']").offset().top-50);
		});
	});
}

$('.containerFooter>aside').on('click','p',function(){
	expandMain();	
});
$('.containerHeader>aside').on('click','.icon-down',function(){
	$('.containerMain').animate({'top':$(window).height()-$('.footerback').height()},function(){
		ismain  = 0;
	});
});

$('.clientMain').scroll(function(){
	var _num = 0,_name;
	$("a[name*='cc-']").each(function(i, e) {
		_num = _num == 0 ? $(this).offset().top : _num;
		if($(this).offset().top>0 && $(this).offset().top<299){
			_name = $(this).attr('name');
		}
		$mainMenu.each(function(i, e){
			if(typeof(_name)!='undefined'){
				if($(this).attr('data-id')==_name){
					$mainMenu.removeClass('sele');
					$(this).addClass('sele');
				}
			}
		});
	});
	
	/*回顶部*/
	if($('.clientMain').scrollTop()>$(document).height()){
		$('.top_stick').fadeIn('slow');	
	}else{
		$('.top_stick').fadeOut('slow');	
	}
});

$mainMenu.each(function(i, e) {
	$(this).on('click','a',function(){
		$(this).attr('href','javascript:;');
		$(".clientMain").animate({scrollTop:$(this).parent().attr('scrolltop')}, 'slow');
	});
});
	
//页面完成时加载
$(document).ready(function(e) {
	//判断强制返回该页时判断是否为已登录状态
	$.post(U('home/Public/isLogin', ['t='+Math.random()]),function(data){
		if(data=='true'){
			$login = $('article.loginbar');
			$login.html('<button type="button" class="btn-learn" onclick="window.location.href=\'<?php echo U('desktop');?>\'">进入体验</button>');
			$login.removeClass();
			$login.addClass('learnbox');
		}
	});
	/*背景轮换
	setTimeout(function(){
		$("#slidebackgroud").Slide({
			effect : "scroolX",
			speed : "normal",
			timer : 6000,
			claCon: "slide-content",
			claNav: "slide-nav"
		});	
	},500);
	*/
	var k = Math.round(Math.random() * 4);
	var licount = 1;
	$('#slidebackgroud').find('.slide-content>li').each(function(i, e) {
		if(i == k){
			$(this).show();
		}else{
			$(this).hide();
		}
		licount = Math.max(licount, i);
	});
	/*背景轮换上一个按钮*/
	$('#slidebackgroud').on('click','.prev',function(){
		$('#slidebackgroud')
		.find('.slide-content>li:eq('+k+')')
		.css({position:'absolute'})
		.animate({left:-$(window).width()},function(){
			$(this).css({left:0,display:'none'});
			k = (k-1)<0 ? licount : k-1 ;
			$('#slidebackgroud').find('.slide-content>li:eq('+k+')').fadeIn('fast');
		});
	});
	/*背景轮换下一个按钮*/
	$('#slidebackgroud').on('click','.next',function(){
		$('#slidebackgroud')
		.find('.slide-content>li:eq('+k+')')
		.css({position:'absolute'})
		.animate({left:$(window).width()},function(){
			$(this).css({display:'none',left:0});
			k = (k+1)>licount ? 0 : k+1 ;
			$('#slidebackgroud').find('.slide-content>li:eq('+k+')').fadeIn('fast');
		});
	});
	
	/*登录表单功能*/
	$('input').each(function(i, e) {
		$default = $(this).attr('default');
		if(typeof($default)!='undefined' && $.trim($(this).val())=='') $(this).parent().prepend("<label>"+$default+"</label>");	
		$(this).focus(function(){
			$(this).parent().find('label').hide();
		}).blur(function(){
			if($(this).val()=='') $(this).parent().find('label').show();
		});
		$(this).parent().on('click','label',function(){
			$(this).hide();
			$(this).parent().find('input').focus();	
		});
	});
	
	/*去除邮件联想列表功能*/
	$('#password').focus(function(){
		if($('.email-relevance').length>0){
			$('.email-relevance').fadeOut('fast');
		}
	});
	
	$('.containerMain').css({display:'block'});
	
	/*回到顶部*/	
	$('.top_stick').click(function(){
		$('.clientMain').animate({scrollTop:0}, 'slow');
	});
});
</script>
  <!-- end .containerInner --></div>
<!-- end .container --></div>
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1000083231'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1000083231' type='text/javascript'%3E%3C/script%3E"));</script>
</body>
</html>