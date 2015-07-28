<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php if(($ts['site']['page_title'])  !=  ""): ?><?php echo ($ts['site']['page_title']); ?> <?php echo ($ts['site']['site_name']); ?><?php else: ?><?php echo ($ts['site']['site_name']); ?><?php endif; ?></title>
<meta name="keywords" content="<?php echo ($ts['site']['site_header_keywords']); ?>" />
<meta name="description" content="<?php echo ($ts['site']['site_header_description']); ?>" />
<!--[if IE 9]>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<![endif]-->
<?php $agent = empty( $_SERVER["HTTP_USER_AGENT"] ) ? $HTTP_SERVER_VARS['HTTP_USER_AGENT'] : $_SERVER["HTTP_USER_AGENT"];
if(strpos($agent, "MSIE 9.0")){ ?>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<?php } ?>
<meta name="generator" content="GSN {VERSION}" />
<meta name="author" content="GSN" />
<meta name="copyright" content="2012-2013 gridinfo.com.cn." />
<meta name="MSSmartTagsPreventParsing" content="True" />
<meta http-equiv="MSThemeCompatible" content="Yes" />
<base href="__ROOT__" />

 <!--{csstemplate}-->
 	<script type="text/javascript" src="../Public/js/jquery-1.7.min.js"></script>
    <script type="text/javascript" src="../Public/js/jquery.json-2.3.js"></script>
    <script type="text/javascript" src="__PUBLIC__/js/common.js?<?php echo ($verhash); ?>"></script>
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/base.css?<?php echo ($verhash); ?>" />
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/common.css?<?php echo ($verhash); ?>" />
    
    <script language="javascript" type="text/javascript">
	var _UID_   = <?php echo (int) $uid; ?>;
	var _MID_   = <?php echo (int) $mid; ?>;
	var _URL_   = '<?php echo getSiteUrl();?>';
	var _ROOT_  = '__ROOT__';
	var _THEME_ = '__THEME__';
	var _PUBLIC_ = '__PUBLIC__';
	var _APP_PUBLIC_ = '../Public';
	var _LENGTH_ = <?php echo (int) $GLOBALS['ts']['site']['length']; ?>;
	var _LANG_SET_ = '<?php echo LANG_SET; ?>';
	</script>
    
<?php if($basescript == 'dsk'): ?><script type="text/javascript" src="../Public/js/fonteffect.js?<?php echo ($verhash); ?>"></script>
    <?php if($curmodule == 'dskindex'): ?><script type="text/javascript" src="__ROOT__/apps/desktop/Lang/zh-cn/common.js?<?php echo ($verhash); ?>"></script>
    <script type="text/javascript" src="../Public/js/_config.js?<?php echo ($verhash); ?>"></script>
    <script type="text/javascript" src="../Public/js/index.js?<?php echo ($verhash); ?>"></script>
	
	<script type="text/javascript" src="__PUBLIC__/js/editor/kissy-min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/editor/uibase-min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/editor/dd-min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/editor/component-min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/editor/overlay-min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/editor/editor/editor-all-pkg-min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/editor/editor/biz/ext/editor-plugin-pkg-min.js"></script>
    
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/index.css?<?php echo ($verhash); ?>" />
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/message/message.css?<?php echo ($verhash); ?>" />
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/window/widget/widget.css?<?php echo ($verhash); ?>" />
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/widget/_widget.css?<?php echo ($verhash); ?>" />
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/menu/default/_menu.css?<?php echo ($verhash); ?>" />
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/thame/<?php echo ($space["thame"]["folder"]); ?>/style.css?<?php echo ($verhash); ?>" id="css_thame_<?php echo ($space["thame"]["folder"]); ?>" />
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/window/<?php echo ($space["thame"]["window"]); ?>/_Window.css?<?php echo ($verhash); ?>" id="css_window_<?php echo ($space["thame"]["window"]); ?>" />
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/browser/<?php echo ($space["thame"]["browser"]); ?>/_Browser.css?<?php echo ($verhash); ?>" id="css_browser_<?php echo ($space["thame"]["browser"]); ?>" />
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/navbar/<?php echo ($space["thame"]["topbar"]); ?>/_navbar.css?<?php echo ($verhash); ?>" id="css_topbar" />
	<link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/member/<?php echo ($space["thame"]["member"]); ?>/_member.css?<?php echo ($verhash); ?>" id="css_memberbar" />
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/filemanage/<?php echo ($space["thame"]["filemanage"]); ?>/_filemanage.css?<?php echo ($verhash); ?>" id="css_filemanage_<?php echo ($space["thame"]["filemanage"]); ?>" />
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/dock/<?php echo ($space["thame"]["dock"]); ?>/style.css?<?php echo ($verhash); ?>" id="css_dock" />
    <link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/openfile/window_jd/_file.css?<?php echo ($verhash); ?>" /> 
	<link rel="stylesheet" type="text/css" href="__THEME__/desktop/styles/navbar/public/_navbar.css?<?php echo ($verhash); ?>" /><?php endif; ?><?php endif; ?>
    <style>body{background:none}</style>
	<script type="text/javascript">
	var DZZSCRIPT="__ROOT__";
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
<script type="text/javascript" src="../Public/js/common.js?<?php echo ($verhash); ?>"></script>

<!--[if lt IE 9]>
<script src="../Public/js/html5.js"></script>
<![endif]-->

</head>
<body id="nv_dsk" unselectable="on" onselectstart="return event.srcElement.type== 'text';">
<div id="append_parent" style="z-index:9999;"></div><div id="ajaxwaitid" style="z-index:9999;"></div>


<link rel="stylesheet" href="__PUBLIC__/themes/edustyle/desktop/css/scroll.css" type="text/css"/>
<script type="text/javascript" src="__PUBLIC__/js/desktop/jquery.mousewheel.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/desktop/jquery.jscrollpane.min.js"></script>
<style type="text/css">
.gf_list .jspVerticalBar{
	right:4px;
	width:6px;	
}
.gf_list .jspTrack{
	background-color:#b3b3b3;
}
.gf_list .jspDrag{
	background-color:#f0f0f0;
}
.bottomBar{display: none;}
</style>
<!-- 滚动条样式插件 end -->
<script type="text/javascript">
if(window != top){top.window.onbeforeunload=null;top.location = location;};
_config.self='<?php echo ($space["self"]); ?>';
_config.myuid='<?php echo ($mid); ?>';
_config.uid='<?php echo ($mid); ?>';
_config.leavealert=parseInt('0');<!-- <?php echo ($sysconfig[leavealert]); ?> -->
_config.ajaxurl="<?php echo U('desktop/System/ajax');?>";
_config.systemurl="<?php echo U('desktop/System/index');?>";
_config.screenWidth=document.documentElement.clientWidth;
_config.screenHeight= document.documentElement.clientHeight;
JSMENU['zIndex'] = {'win':9200,'menu':9300,'dialog':9400,'prompt':9500};

function toggleFullScreen(videoElement) {
	if (!document.mozFullScreen && !document.webkitFullScreen) {
	  if (videoElement.mozRequestFullScreen) {
		videoElement.mozRequestFullScreen();
	  } else {
		videoElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
	  }
	} else {
	  if (document.mozCancelFullScreen) {
		document.mozCancelFullScreen();
	  } else {
		document.webkitCancelFullScreen();
	  }
	}
};
</script>
<div id="videocss_loaded" class="videocss_loaded_flag" style="z-index:-9999999;position:absolute;" ></div>
<iframe id='hideframe' name='hideframe' src="about:blank" frameBorder='0' marginHeight='0' marginWidth='0' width='0' height='0' allowtransparency="true" style="display:none;z-index:-99999"></iframe>
<div id="_blank" style="display:none; background: url(../Public/images/b.gif); z-index:10000;width:100%;height:100%;margin:0;padding:0; right: 0px; bottom: 0px;position: absolute; top:0px; left: 0px;"></div>

<div id="MsgContainer" style="display:none; background: url(../Public/images/b.gif); z-index:99999;width:100%;height:100%;margin:0;padding:0; right: 0px; bottom: 0px;position: absolute; top:0px; left: 0px;"></div>
<div id='input_' style="position:relative;word-wrap: break-all; word-break: normal;display:none; background:'' "></div>
<div id="loading_info" style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;margin:0;padding:0;overflow:hidden; z-index: 99999;background:#BADAF1;">
   <table height="100%" width="100%"><tbody><tr><td align="center" valign="middle"><div class="loading_img"><div class="loading_process"></div></div></td></tr></tbody></table>
</div>

<?php if($mid == $uid): ?><div id="navbar_userdetail">
    <div class="LEFT_TOP" ></div>
    <div class="TOP"></div>
    <div class="RIGHT_TOP" ></div>
    <div class="RIGHT" ></div>
    <div class="RIGHT_BOTTOM"></div>
    <div class="BOTTOM" ></div>
    <div class="LEFT_BOTTOM" ></div>
    <div class="LEFT"></div>
    <div id="navbar_userdetail_content"></div>
</div><?php endif; ?>
<!-- 顶部工具栏 start -->
<div id="navbar" style="z-index:1000">
    <div id="navbar_back"></div>
    <div class="nav_mask nav_mask2"></div>
    <div class="nav_mask nav_mask3"></div>
    <div id="nav-logo" class="navlogo_container">
        <a class="navlogo" href="__APP__"></a>
    </div>
    
    <div id="sysindicator_Container" class="sysindicator_Container">
        <?php if(is_array($navbar)): ?><?php $i = 0;?><?php $__LIST__ = $navbar?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$value): ?><?php ++$i;?><?php $mod = ($i % 2 )?><a id="indicator_sys_<?php echo ($key); ?>" navid="<?php echo ($key); ?>" index="<?php echo ($i); ?>"  href="__ROOT__/<?php echo ($value[navurl]); ?>" class="navItem sysindicator sysindicator_<?php echo ($i); ?>" title="<?php echo (text($value[navname])); ?>" hideFoucs>
             <table cellpadding="0" cellspacing="0" height="100%">
             <tr>
             <?php if(($value[avaliable] != 1) AND ($value[navicon])): ?><td><img class="sysindicator_icon" src="$value[navicon]" onload="fixpng(this)" /> </td><?php endif; ?>
             <?php if(($value[avaliable] != 2)): ?><td><div class="sysindicator_navname nav_text"><?php echo ($value[navname]); ?></div> </td><?php endif; ?>
             </tr>
             </table>
            </a><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </div>
    
    <div id="nav_userinfo" class="userinfo_container cl">
        <?php if(($mid < 0) OR ($mid == '')): ?><a href="member.php?mod=logging&action=login" onClick="_login.logging();return false;" class="nav_text login  userinfo" ><?php echo L('login');?></a>
            <a href="member.php?mod=<?php echo ($_G[setting][regname]); ?>" class="nav_text reglink userinfo" onclick="_config.leavealert=0;"><?php echo L('register');?></a>
        <?php else: ?>
        	<a class="userinfo sys_thame" href="javascript:return false;" onclick="_login.click('sys_theme');return false;" title="<?php echo L('theme_config');?>">&nbsp;</a>
            <a class="userinfo  sys_config" href="javascript:return false;" onclick="_login.click('sys_config');return false;" title="<?php echo L('system_config');?>">&nbsp;</a>
            <!--<strong class="user_vwmy vwmy<?php if(($setting['connect']['allow']) AND ($space[conisbind])): ?>qq<?php endif; ?> userinfo"><a <?php if(($uid > 0) AND ($uid != $mid)): ?>href="home.php?mod=space&amp;uid=<?php echo ($uid); ?>" c="1"<?php endif; ?> title="<?php echo L('click_to_detail');?>"><span class="nav_text"> <?php echo getUserName($uid);?></span></a></strong> 
            <div class="nav_mask user_avatar_mask"></div>
             <a class="userinfo  user_avatar" href="home.php?mod=space&uid=$uid" target="_blank"><img src="<?php echo getUserFace($mid,'s');?>"></a> --><?php endif; ?>
    </div>
    
    <div id="navbar_container" class="navbar_container">
        <div id="indicator_Container"></div>
    </div>
</div>
<!-- 顶部工具栏 end -->
<!-- 背景颜色图片 start -->
<div id="wrapper_div" style=" width:100%; height:100%;right: 0px; bottom: 0px;position: absolute; top: 0px; left: 0px;margin:0;padding:0;overflow:hidden;  z-index:-9999; background:#099">
    <img src="../Public/images/b.gif" name="imgbg" id="imgbg" style="right: 0px; bottom: 0px; top: 0px; left: 0px; z-index: -1;margin:0;padding:0;overflow:hidden; position: absolute;" height="100%" width="100%">
    <iframe id='wrapper_frame' name='wrapper_frame' src="about:blank" frameBorder='0' marginHeight='0' marginWidth='0' width='0' height='0' allowtransparency="true"></iframe>
</div>
<!-- 背景颜色图片 end -->

<!-- 左侧个人信息停靠栏 start -->
<div id="dock_member" style="position:absolute; top:50%; left:0px;height:360px;margin:0;padding:0;z-index:2999;">
	 <div style="position:absolute;top:-220px;height:100%;z-index:2999;" id="dock_member_container">
	 	<div class="dock_member_collapse"><a href="javascript:;"><i class="arrow-west"></i></a></div>
		<div class="dock_member_top"></div>
		<div class="dock_member_content">
		<dl>
			<dt class="userpic">
				<a class="userface" onmouseover="jQuery(this).parent().addClass('over');" onmouseout="jQuery(this).parent().removeClass('over');" href="javascript:;" onclick="_login.click('my_setting');return false;"><img src="<?php echo (getUserFace($mid)); ?>"></a>
				<a class="uploadface" onmouseover="jQuery(this).parent().addClass('over');" onmouseout="jQuery(this).parent().removeClass('over');" href="javascript:;" onclick="OpenWindow('url','<?php echo U('home/Account/index',array('position'=>'face'));?>','个人设置','','titlebutton=close|max|min,width=1035,height=600');">上传头像</a>
				<a href="javascript:;" onclick="OpenSpaceWin('<?php echo U('home/Space/index', array('uid' => $uid));?>','个人主页');return false;" class="username"><?php echo (getUserName($mid)); ?></a>
			</dt>
			<dd class="btnbox">
				<a title="查看关注" class="button home" href="javascript:;" onclick="OpenWindow('url','<?php echo U('home/Space/follow',array('type'=>'following','uid'=>$mid)), false, false;?>','查看关注','','titlebutton=close|max|min,width=1035,height=600');return false;"></a>
				<a title="找人" class="button friend" href="javascript:;" onclick="OpenWindow('url','<?php echo U('home/User/findfriend'), false, false;?>','查找好友','','titlebutton=close|max|min,width=1035,height=600');return false;"></a>
				<a title="消息" class="button layout" href="javascript:;" onclick="OpenWindow('url','<?php echo U('home/Message/index'), false, false;?>','消息','','titlebutton=close|max|min,width=1035,height=600');return false;"></a>
			</dd>
			<dd><a title="个人主页" class="pagelink" href="javascript:;" onclick="OpenSpaceWin('<?php echo U('home/Space/index', array('uid' => $uid));?>','个人主页');return false;">个人主页</a></dd>
			<?php $classlist = getClassSpace(); ?>
			<?php if(($classlist == 1)): ?><dd><a title="班级空间" class="pagelink" href="javascript:;" onclick="OpenWindow('url','<?php echo U('home/Search/search');?>','班级空间','','titlebutton=close|min,width=880,height=550');return false;">班级空间</a></dd><?php endif; ?>
			<?php $organize = getCourseSpace(); ?>
			<!--<?php if(!empty($organize)): ?>-->
			<dd><a title="" class="pagelink" href="javascript:;" onclick="OpenWindow('url','<?php echo U('home/Space/courseSpace');?>','学科空间','','titlebutton=close,width=430,height=290');return false;">学科空间</a></dd>
			<!--<?php endif; ?>-->
			<?php if(($isSystemAdmin)  ==  "TRUE"): ?><dd><a title="后台管理" class="pagelink" href="<?php echo U('admin/index/index');?>" target="_blank">后台管理</a></dd><?php endif; ?>
			<dd><a href="<?php echo U('home/Public/logout');?>" class="pagelink">退出系统</a></dd>
			<dd>
				<a title="小工具" class="sys_thame"  href="javascript:;" onclick="OpenWindow('url','<?php echo U('home/Account/weiboshare');?>','小工具','','titlebutton=close|max|min,width=1035,height=600');return false;">&nbsp;</a>
				<a title="系统设置" class="sys_config" onclick="_login.click('my_setting');return false;" href="javascript:return false;">&nbsp;</a>
				<div class="clearfix"></div>
			</dd>
		</dl>
		</div>
		<div class="dock_member_bottom"></div>
	 </div>
</div>
<!-- 左侧个人信息停靠栏 end -->
		
<!-- 桌面主要信息 start -->
<div id="desktop_wrapper" style="position:absolute;top:0px;left:0px;bottom:0px;right:0px;width:100%;height:100%;margin:0;padding:0;overflow:hidden;">
    <div id="_bodys" style="position:absolute;top:0px;width:100%;height:100%;bottom:0px;right:0px;left:0px;margin:0;padding:0;overflow:visible;">
        <?php if(is_array($nav)): ?><?php $i = 0;?><?php $__LIST__ = $nav?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$value): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php if(($value[navurl]) AND ($value[target] == '_blank')): ?><?php else: ?>
			<div id="_body_<?php echo ($value[navid]); ?>" index="<?php echo ($key); ?>" navid="<?php echo ($value[navid]); ?>" url="<?php echo ($value[navurl]); ?>" class="desktop <?php if(($value[isdefault])): ?>navdefault<?php endif; ?>" style="width: 100%; height: 100%; padding: 0pt; margin: 0pt; overflow: hidden; position: absolute;left:0px;  <?php if(($value[isdefault])): ?>filter:alpha(opacity=100);opacity:1;<?php else: ?>filter:alpha(opacity=0);opacity:0;<?php endif; ?>"></div><?php endif; ?><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
	</div>
</div>
<!-- 桌面主要信息 end -->
<div id="remind_widget" >
<?php echo Addons::hook("desktop_user_remind_show",$param=array());?>
</div>      
<div id="dock_opbar" class="dock_opbar_d" title="<?php echo L('show_hide_dock');?>"><a></a></div>
<div id="dzzcopyright" class="dzzcopyright" onMouseover="showMenu(this.id)"><a style="font-size:12px; font-family:'Arial Black', Gadget, sans-serif;color:#FFF; text-decoration:none;">&copy;</a></div>
<div id="dzzcopyright_menu" style="z-index:99999;display:none;">
	 <div class="copyright radius">
	 <p>Powered by <a href="http://www.gridinfo.com.cn/" target="_blank">ESNDesktop</a></p>
	</div>
  </div>
<div id="dock_bottom_back" class="dock_bottom_back" style="position:absolute;">
	<div class="dock_bottom_back_inner1 inner"></div>
	<div class="dock_bottom_back_inner2 inner"></div>
	<div class="dock_bottom_back_inner3 inner"></div>
</div>
<div id="dock_bottom" style="left:50%; bottom:0px;position:absolute;height:70px;margin:0;padding:0;z-index:1000" >
	 <div style="position:absolute;left:50%;height:100%;z-index:10" id="dock_app_container">
		 <table cellpadding="0" border="0" cellspacing="0" height="100%"><tr>
			 <td valign="top"><div id="_dock" class="dock" > </div></td>
			 <td valign="bottom"><div id="docksro" style="display:none;"></div></td>
			 <td valign="top"><div id="_stick" class="dock"></div></td>
			 <td valign="top"><div id="_system" class="dock"></div></td><!-- 原资源管理器 -->
		</tr></table>
	 </div>
	 <div style="left:0px;bottom:0px;position:absolute;z-index:0" id="dock_container" class="dock_container">
		<table class="dock_table" border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
		  <tr>
			<td class="dock_container_left" id="dock_container_left"><div></div></td>
			<td id="dock_container_center" class="dock_container_center">&nbsp;</td>
			<td class="dock_container_right" id="dock_container_right"><div></div></td>
		  </tr>
		</table>
	</div>
</div>

   	
<div id="stick_container" style="position:absolute;overflow:hidden;display:none"></div>
  
<div id="start_menu" class="menu" style="display:none;" >
	<div class='menu-item startmenu' onClick="_login.click('index')"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/home.png" /><span class="menu-text"><?php echo L('set_home');?></span></div>
	<div class='menu-item startmenu' onClick="_login.click('fav')"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/star_1.png" /><span class="menu-text"><?php echo L('add_site_favorite');?></span></div>
	<div class='menu-item startmenu' onClick="_login.click('sys_market','_container')"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/appshop.png" /><span class="menu-text"><?php echo L('appmarket');?></span></div>
	<div class='menu-item startmenu' onClick="_login.click('sys_config')"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/cog.png" /><span class="menu-text"><?php echo L('system_config');?></span></div>
	 <div class='menu-item startmenu' onClick="_login.click('sys_browser')"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/world.png" /><span class="menu-text"><?php echo L('browser');?></span></div>
	<div class='menu-item startmenu' onClick="_login.click('logout')"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/door.png" /><span class="menu-text"><?php echo L('logout');?></span></div>
</div>
<div id='right_navbar' class="menu" style="display:none;">
	<div class='menu-item' onClick="_navbar.setNavbarTop()"><a herf='#' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/pin.png" /><span class="menu-text"><?php echo L('navbar_topfix');?></span></a></div>
</div>
<div id='paixu_down' class="menu" style="display:none;">
   <div class='menu-item' onClick="_filemanage.Disp('_filemanageid','0','_winid')"><img disp="0" class="menu-icon menu-icon-disp menu_icon_filemanagedisp_0" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanagedisp_0');?></span></div>
	<div class='menu-item' onClick="_filemanage.Disp('_filemanageid','1','_winid')"><img disp="1" class="menu-icon menu-icon-disp menu_icon_filemanagedisp_1" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanagedisp_1');?></span></div>
	<div class='menu-item' onClick="_filemanage.Disp('_filemanageid','2','_winid')"><img disp="2" class="menu-icon menu-icon-disp menu_icon_filemanagedisp_2" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanagedisp_2');?></span></div>
	<div class='menu-item' onClick="_filemanage.Disp('_filemanageid','3','_winid')"><img disp="3" class="menu-icon menu-icon-disp menu_icon_filemanagedisp_3" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanagedisp_3');?></span></div>
</div>
<div id='paixu_down_file' class="menu" style="display:none;">
   <div class='menu-item' onClick="_file.Disp('0','_winid')"><img disp="0" class="menu-icon menu-icon-disp menu_icon_filemanagedisp_0" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanagedisp_0');?></span></div>
	<div class='menu-item' onClick="_file.Disp('1','_winid')"><img disp="1" class="menu-icon menu-icon-disp menu_icon_filemanagedisp_1" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanagedisp_1');?></span></div>
	<div class='menu-item' onClick="_file.Disp('2','_winid')"><img disp="2" class="menu-icon menu-icon-disp menu_icon_filemanagedisp_2" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanagedisp_2');?></span></div>
	<div class='menu-item' onClick="_file.Disp('3','_winid')"><img disp="3" class="menu-icon menu-icon-disp menu_icon_filemanagedisp_3" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanagedisp_3');?></span></div>
</div>
<div id='right_img' class="menu" style="display:none;">
	<div class='menu-item'><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/wallpaper.png" /><span class="menu-text"><?php echo L('setwallpaper');?></span><span class="menu-rightarrow"></span>
		<div class=" menu " style="display:none">
			<div class='menu-item' onClick="_config.setback('_imgurl',1,'','backimg');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();return false;"><img  class="menu-icon"  src="../Public/images/b.gif" /><span class="menu-text"><?php echo L('custom_option_layout_1');?></span></div>
			<div class='menu-item' onClick="_config.setback('_imgurl',2,'','backimg');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();return false;"><img  class="menu-icon"  src="../Public/images/b.gif" /><span class="menu-text"><?php echo L('custom_option_layout_2');?></span></div>
			<div class='menu-item' onClick="_config.setback('_imgurl',3,'','backimg');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();return false;"><img  class="menu-icon"  src="../Public/images/b.gif" /><span class="menu-text"><?php echo L('custom_option_layout_3');?></span></div>
			
		</div>
	</div>
</div>
<div id='other_down' class="menu" style="display:none;">
	<div class='menu-item' onclick="_ico.NewIco('Newlink','_container');document.getElementById('other_down').innerHTML=_contextmenu.other_down_html;jQuery('#other_down').hide();jQuery('#shadow').hide();" ><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/newlink.png" /><span class="menu-text"><?php echo L('newlink');?></span></div>
	<!-- 文件夹应用工具栏中的新建下拉菜单创建文档功能 -->
	<!--div class='menu-item' onclick="_ico.NewIco('NewDzzDoc','_container');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();" ><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/dzzdoc.png" /><span class="menu-text"><?php echo L('newdzzdoc');?></span></div>
	<div class='menu-item' onclick="_ico.NewIco('NewTxt','_container');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();" ><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/txt.png" /><span class="menu-text"><?php echo L('newtxt');?></span></div-->
	<div class='menu-item appmarket' onClick="_login.click('sys_market','_container');document.getElementById('other_down').innerHTML=_contextmenu.other_down_html;jQuery('#other_down').hide();jQuery('#shadow').hide();"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/appshop.png" /><span class="menu-text"><?php echo L('appmarket');?></span></div>
</div>
<div id='right_ico' class="menu" style="display:none;">
	<div class="menu-item open" onClick="_ico.Open('_icoid');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><a herf='#' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/open.png" /><span class="menu-text"><?php echo L('open_app');?></span></a></div>
	<div id="right_ico_delete" class="menu-item delete" onClick="_ajax.delIco('_icoid');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><a herf='#' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/recycle.png" /><span class="menu-text"><?php echo L('uninstall_app');?></span></a></div> 
	<div id="right_ico_rename" class="menu-item rename" onClick="_ico.Rename('_icoid','_filemanageid');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><a herf='#' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/rename.png" /><span class="menu-text"><?php echo L('rename');?></span></a></div>
	<div id="right_ico_edit" class="menu-item edit" onClick="_ico.Edit('_icoid');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><a herf='#' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/edit.png" /><span class="menu-text"><?php echo L('edit');?></span></a></div>
	<div id="right_ico_attach" class="menu-item download" onClick="_ico.downAttach('_icoid');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();return false;"><a herf='#' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/download.png" /><span class="menu-text"><?php echo L('download');?></span></a></div>
	<div class='menu-item setwallpaper'><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/wallpaper.png" /><span class="menu-text"><?php echo L('setwallpaper');?></span><span class="menu-rightarrow"></span>
		<div class=" menu " style="display:none">
			<div class='menu-item' onClick="_config.setback('_imageurl',1,'','backimg');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();return false;"><img  class="menu-icon"  src="../Public/images/b.gif" /><span class="menu-text"><?php echo L('custom_option_layout_1');?></span></div>
			<div class='menu-item' onClick="_config.setback('_imageurl',2,'','backimg');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();return false;"><img  class="menu-icon"  src="../Public/images/b.gif" /><span class="menu-text"><?php echo L('custom_option_layout_2');?></span></div>
			<div class='menu-item' onClick="_config.setback('_imageurl',3,'','backimg');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();return false;"><img  class="menu-icon"  src="../Public/images/b.gif" /><span class="menu-text"><?php echo L('custom_option_layout_3');?></span></div>
			
		</div>
	</div>
	<!-- 应用在桌面的右键上设为挂件功能 -->
	<div class="menu-item setwidget" style="display:none" onClick="_widget.setToWidget('_icoid');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><a herf='#' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/setwidget.png" /><span class="menu-text"><?php echo L('setwidget');?></span></a></div>
</div>

<div id='right_folder' class=" menu " style="position:absolute;display:none">
	<div class='menu-item ' ><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/view.png" /><span class="menu-text"><?php echo L('iconviev');?></span><span class="menu-rightarrow"></span>
		<div class=" menu " style="display:none">
			<div class='menu-item' onClick="_filemanage.Arrange('_filemanageid','0','_winid')"><img view="0" class="menu-icon menu-icon-iconview menu_icon_filemanageview_0" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanageview_0');?></span></div>
			<div class='menu-item' onClick="_filemanage.Arrange('_filemanageid','1','_winid')"><img view="1" class="menu-icon menu-icon-iconview menu_icon_filemanageview_1" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanageview_1');?></span></div>
			<div class='menu-item' onClick="_filemanage.Arrange('_filemanageid','2','_winid')"><img view="2" class="menu-icon menu-icon-iconview menu_icon_filemanageview_2" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanageview_2');?></span></div>
			<div class='menu-item' onClick="_filemanage.Arrange('_filemanageid','3','_winid')"><img view="3" class="menu-icon menu-icon-iconview menu_icon_filemanageview_3" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanageview_3');?></span></div>
			<div class='menu-item' onClick="_filemanage.Arrange('_filemanageid','4','_winid')"><img view="4" class="menu-icon menu-icon-iconview menu_icon_filemanageview_4" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanageview_4');?></span></div>
	   </div>
	   <span  class="menu-shadow"></span>
	</div>
	<div class='menu-item sort' ><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/view.png" /><span class="menu-text"><?php echo L('sort');?></span><span class="menu-rightarrow"></span>
		<div class=" menu " style="display:none">
			<div class='menu-item' onClick="_filemanage.Disp('_filemanageid','0','_winid')"><img disp="0" class="menu-icon menu-icon-disp menu_icon_filemanagedisp_0" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanagedisp_0');?></span></div>
			<div class='menu-item' onClick="_filemanage.Disp('_filemanageid','1','_winid')"><img disp="1" class="menu-icon menu-icon-disp menu_icon_filemanagedisp_1" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanagedisp_1');?></span></div>
			<div class='menu-item' onClick="_filemanage.Disp('_filemanageid','2','_winid')"><img disp="2" class="menu-icon menu-icon-disp menu_icon_filemanagedisp_2" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanagedisp_2');?></span></div>
			<div class='menu-item' onClick="_filemanage.Disp('_filemanageid','3','_winid')"><img disp="3" class="menu-icon menu-icon-disp menu_icon_filemanagedisp_3" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('menu_filemanagedisp_3');?></span></div>
	   </div>
	   <span  class="menu-shadow"></span>
	</div>
	<div class='menu-item create' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/create.png" /><span class="menu-text"><?php echo L('newcreate');?></span><span class="menu-rightarrow"></span>
		<div class=" menu " style="display:none">
			<div class='menu-item' onClick="_ico.NewIco('Newfolder','_container');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/newfolder.png" /><span class="menu-text"><?php echo L('newfolder');?></span></div>
			<div class='menu-item' onclick="_ico.NewIco('Newlink','_container');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();" ><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/newlink.png" /><span class="menu-text"><?php echo L('newlink');?></span></div>
			<div class='menu-item' onclick="_ico.NewIco('NewTxt','_container');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();" style="display:none"><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/txt.png" /><span class="menu-text"><?php echo L('newtxt');?></span></div>
	   </div>
	   <span  class="menu-shadow"></span>
	</div>
	<div class='menu-item upload' onClick="jQuery('#right_contextmenu').css('z-index',-99999999);jQuery('#shadow').hide();return true;" style="display:none"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/upload.png" /><span class="menu-text"><?php echo L('upload');?></span></div>
	<div class='menu-item appmarket' onClick="_login.click('sys_market','_container');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/appshop.png" /><span class="menu-text"><?php echo L('appmarket');?></span></div>
</div>
<div id='right_body' class=" menu " style="position:absolute;display:none">
	<div class='menu-item ' ><img id="menu_icon_view" class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/view.png" /><span class="menu-text"><?php echo L('iconviev');?></span><span class="menu-rightarrow"></span>
		<div class=" menu " style="display:none">
		<?php if(is_array($iconview)): ?><?php $i = 0;?><?php $__LIST__ = $iconview?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$value): ?><?php ++$i;?><?php $mod = ($i % 2 )?><div class='menu-item' onClick="_ico.Arrange('<?php echo ($value[id]); ?>','_container','iconview')"><img id="menu_icon_iconview_<?php echo ($value[id]); ?>" class="menu-icon menu-icon-iconview" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo ($value[name]); ?></span></div><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
	   </div>
	   <span  class="menu-shadow"></span>
	</div>
	<div class='menu-item notfolder position' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/position.png" /><span class="menu-text"><?php echo L('position');?></span><span class="menu-rightarrow"></span>
		<div class=" menu " style="display:none">
			<div class='menu-item' onClick="_ico.Arrange('0','_container','position')"><img id="menu_icon_position_0" class="menu-icon menu-icon-position" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('position_lefttop');?></span></div>
			<div class='menu-item' onClick="_ico.Arrange('2','_container','position')"><img id="menu_icon_position_2" class="menu-icon menu-icon-position" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('position_leftbottom');?></span></div>
			<div class='menu-item' onClick="_ico.Arrange('1','_container','position')"><img id="menu_icon_position_1" class="menu-icon menu-icon-position" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('position_righttop');?></span></div>
			<div class='menu-item' onClick="_ico.Arrange('3','_container','position')"><img id="menu_icon_position_3" class="menu-icon menu-icon-position" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('position_rightbottom');?></span></div>
	   </div>
	   <span  class="menu-shadow"></span>
	</div>
	<div class='menu-item notfolder autolist' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/autolist.png" /><span class="menu-text"><?php echo L('autolist');?></span><span class="menu-rightarrow"></span>
		<div class=" menu " style="display:none">
			<div class='menu-item' onClick="_ico.Arrange('0','_container','autolist')"><img id="menu_icon_autolist_0" class="menu-icon menu-icon-autolist" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('autolist_0');?></span></div>
			<div class='menu-item' onClick="_ico.Arrange('1','_container','autolist')"><img id="menu_icon_autolist_1" class="menu-icon menu-icon-autolist" onload="fixpng(this)" src="../Public/images/icons/notselect.png" /><span class="menu-text"><?php echo L('autolist_1');?></span></div>
			
	   </div>
	   <span  class="menu-shadow"></span>
	</div>
	<!-- 右键新建功能 -->
    <!--
	<div class='menu-item create' style="display:" ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/create.png" /><span class="menu-text"><?php echo L('newcreate');?></span><span class="menu-rightarrow"></span>
		<div class=" menu " style="display:none">
			<div class='menu-item' onClick="_ico.NewIco('Newfolder','_container');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/newfolder.png" /><span class="menu-text"><?php echo L('newfolder');?></span></div>
			<div class='menu-item' onclick="_ico.NewIco('Newlink','_container');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();" ><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/newlink.png" /><span class="menu-text"><?php echo L('newlink');?></span></div>
			<!--div class='menu-item' onclick="_ico.NewIco('NewDzzDoc','_container');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();" ><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/dzzdoc.png" /><span class="menu-text"><?php echo L('newdzzdoc');?></span></div-->
			<!--<div class='menu-item' onclick="_ico.NewIco('NewTxt','_container');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();" style="display:none"><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/txt.png" /><span class="menu-text"><?php echo L('newtxt');?></span></div>
	   </div>
	   <span  class="menu-shadow"></span>
	</div>
    -->
	<div class='menu-item notfolder upload' onClick="jQuery('#right_contextmenu').css('z-index',-99999999);jQuery('#shadow').hide();" style="display:none"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/upload.png" /><span class="menu-text"><?php echo L('refresh');?></span></div>
	<div class='menu-item notfolder appmarket' onClick="_login.click('sys_market','_container');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/appshop.png" /><span class="menu-text"><?php echo L('appmarket');?></span></div>
	<!-- 挂件市场 -->
	<!--<div class='menu-item notfolder widgetmarket' onClick="_login.click('sys_widget');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/widget.png" /><span class="menu-text"><?php echo L('widgetmarket');?></span></div>-->
	<!--div class='menu-item newwidget' onclick="_widget.New('_container');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><img  class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/setwidget.png" /><span class="menu-text"><?php echo L('newwidget');?></span></div-->
	<div class='menu-item notfolder config' onClick="_login.click('sys_config');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/config.png" /><span class="menu-text"><?php echo L('system_config');?></span></div>
	
	<!--div class='menu-item notfolder filenamage' onClick="OpenFileManage();jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/filemanage.png" /><span class="menu-text"><?php echo L('filemanage');?></span></div-->
</div>

<div id='task_right_Ico' class="menu " style="display:none">
	<div class='menu-item' onClick="_task.Focus('_taskid');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><a herf='#' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/huanyuan.png" /><span class="menu-text"><?php echo L('task_right_win');?></span></a></div>
	<div class='menu-item' onClick="_task.Max('_taskid');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><a herf='#' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/zuida.png" /><span class="menu-text"><?php echo L('task_right_max');?></span></a></div>
	 <div class='menu-item' onClick="_task.Min('_taskid');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><a herf='#' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/zuixiao.png" /><span class="menu-text"><?php echo L('task_right_min');?></span></a></div>
	<div class='menu-item' onClick="_task.Close('_taskid');jQuery('#right_contextmenu').hide();jQuery('#shadow').hide();"><a herf='#' ><img class="menu-icon" onload="fixpng(this)" src="../Public/images/icons/guanbi.png" /><span class="menu-text"><?php echo L('task_right_close');?> </span></a></div>
</div>
    
<div id="shadow" style="display:none;position:absolute"  class="DM">
	<table cellpadding="0" cellspacing="0">
	<tr><td class="LEFT_TOP"></td><td class="TOP"></td><td class="RIGHT_TOP"></td></tr>
	<tr><td class="LEFT"></td><td class="CONTENT"><div id="shadow_center"></div></td><td class="RIGHT"></td></tr>
	<tr><td class="LEFT_BOTTOM"></td><td class="BOTTOM"></td><td class="RIGHT_BOTTOM"></td></tr>	
	</table>
</div>
<div style=" display:none;">
<style>
.backgound_radius_panel{-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;}
.active{background:#09F;}
</style>
	<div id="ControlPanel">
		<div class="backgound_radius_panel" style="height:80px;width:80px; float:left; margin:10px; cursor:pointer;" onmouseover="this.className='active backgound_radius_panel';this.style.color='#FFF';" onmouseout="this.className='backgound_radius_panel';this.style.color='';" onclick="_login.click('sys_theme')">
			<table width="100%" height="100%" cellpadding="0" cellspacing="0" >
				<tr><td align="center"><img  src="../Public/images/default/thame.png" width="50" height="50" /></td></tr>
				<tr><td align="center"><?php echo L('theme_config');?></td></tr>
			</table>
		</div>
	   <div class="backgound_radius_panel" style="height:80px;width:80px; float:left; margin:10px; cursor:pointer;" onmouseover="this.className='active backgound_radius_panel';this.style.color='#FFF';" onmouseout="this.className='backgound_radius_panel';this.style.color='';" onclick="_login.click('sys_market')">
			<table width="100%" height="100%" cellpadding="0" cellspacing="0" >
				<tr><td align="center"><img  src="../Public/images/default/yingyong.png" width="50" height="50" /></td></tr>
				<tr><td align="center"><?php echo L('appmarket');?></td></tr>
			</table>
		</div>
		 <div class="backgound_radius_panel" style="height:80px;width:80px; float:left; margin:10px; cursor:pointer;" onmouseover="this.className='active backgound_radius_panel';this.style.color='#FFF';" onmouseout="this.className='backgound_radius_panel';this.style.color='';" onclick="_login.click('sys_browser')">
			<table width="100%" height="100%" cellpadding="0" cellspacing="0" >
				<tr><td align="center"><img  src="../Public/images/default/liulanqi.png" width="50" height="50" /></td></tr>
				<tr><td align="center"><?php echo L('browser');?></td></tr>
			</table>
		</div>
		 <div class="backgound_radius_panel" style="height:80px;width:80px; float:left; margin:10px; cursor:pointer;" onmouseover="this.className='active backgound_radius_panel';this.style.color='#FFF';" onmouseout="this.className='backgound_radius_panel';this.style.color='';" onclick="OpenWindow('sys_hotkey')">
			<table width="100%" height="100%" cellpadding="0" cellspacing="0" >
				<tr><td align="center"><img  src="../Public/images/default/hotkey.png" width="50" height="50" /></td></tr>
				<tr><td align="center"><?php echo L('hotkey');?></td></tr>
			</table>
		</div>
	   <!-- <div class="backgound_radius_panel" style="height:80px;width:80px; float:left; margin:10px; cursor:pointer;" onmouseover="this.className='active backgound_radius_panel';this.style.color='#FFF';" onmouseout="this.className='backgound_radius_panel';this.style.color='';" onclick="OpenWindow('sys_restore')">
			<table width="100%" height="100%" cellpadding="0" cellspacing="0" >
				<tr><td align="center"><img  src="../Public/images/default/restore.png" width="50" height="50" /></td></tr>
				<tr><td align="center"><?php echo L('system_restore');?></td></tr>
			</table>
		</div>-->
		<div class="backgound_radius_panel" style="height:80px;width:80px; float:left; margin:10px; cursor:pointer;" onmouseover="this.className='active backgound_radius_panel';this.style.color='#FFF';" onmouseout="this.className='backgound_radius_panel';this.style.color='';" onclick="OpenWindow('sys_deskconfig')">
			<table width="100%" height="100%" cellpadding="0" cellspacing="0" >
				<tr><td align="center"><img  src="../Public/images/default/deskconfig.png" width="50" height="50" /></td></tr>
				<tr><td align="center"><?php echo L('desk_config');?></td></tr>
			</table>
		</div>
    </div>
  
    <div id="sys_hotkey">
        <table cellpadding="0" cellspacing="0" width="100%" height="350">
            <tr><td width="10">&nbsp;</td><td><?php echo L('hotkey_alt_c');?></td><td>Alt + C</td><td width="10">&nbsp;</td></tr>
            <tr><td width="10">&nbsp;</td><td><?php echo L('hotkey_alt_n');?></td><td>Alt + N</td><td width="10">&nbsp;</td></tr>
            <tr><td width="10">&nbsp;</td><td><?php echo L('hotkey_alt_m');?></td><td>Alt + M</td><td width="10">&nbsp;</td></tr>
            
            <tr><td width="10">&nbsp;</td><td><?php echo L('hotkey_alt_shift_q');?></td><td>Alt + Shift + Q</td><td width="10">&nbsp;</td></tr>
            
            <tr><td width="10">&nbsp;</td><td><?php echo L('hotkey_ctrl_alt_pre');?></td><td>Ctrl + Alt + <?php echo L('arrow_left');?></td><td width="10">&nbsp;</td></tr>
            <tr><td width="10">&nbsp;</td><td><?php echo L('hotkey_ctrl_alt_next');?></td><td>Ctrl + Alt + <?php echo L('arrow_right');?></td><td width="10">&nbsp;</td></tr>
            <tr><td width="10">&nbsp;</td><td><?php echo L('hotkey_ctrl_alt_n');?></td><td>Ctrl + Alt + (1/2/3/4/5...)</td><td width="10">&nbsp;</td></tr>
            
            <tr><td width="10">&nbsp;</td><td><?php echo L('hotkey_ctrl_alt_d');?></td><td>Ctrl + Alt + D</td><td width="10">&nbsp;</td></tr>
            
            <!--tr><td width="10">&nbsp;</td><td><?php echo L('hotkey_ctrl_alt_e');?></td><td>Ctrl + Alt + E</td><td width="10">&nbsp;</td></tr-->
        </table>
    </div>
     <div id="sys_deskconfig">
      
        <div style="border: 1px solid #A1B4B0;margin-bottom:10px;">
            <div style="height: 25px;line-height: 25px;overflow: hidden;padding: 3px 0 0 12px;border-top: none;border-bottom: 1px solid #A1B4B0;"><?php echo L('default_desktop');?></div>
            <div id="defaultDesktopRadioSet" style="margin:10px;">
              
            </div>
       </div>
    </div>
    <div id="sys_restore">
    	<div style="border: 1px solid #A1B4B0;margin-bottom:10px;">
            <div style="height: 25px;line-height: 25px;overflow: hidden;padding: 3px 0 0 12px;border-top: none;border-bottom: 1px solid #A1B4B0;"><?php echo L('restore_default_app');?></div>
            <div style="margin:10px;">
              <label style="cursor:default;line-height:25px;">
                <input id="restoreAppList" type="checkbox" />
                <?php echo L('restore_default_app_tip');?></label>
            </div>
        </div>
        <div style="border: 1px solid #A1B4B0;margin-bottom:10px;">
            <div style="height: 25px;line-height: 25px;overflow: hidden;padding: 3px 0 0 12px;border-top:none;border-bottom: 1px solid #A1B4B0;"><?php echo L('theme_layout');?></div>
            <div style="margin:10px;">
              <label style="cursor:default;line-height:25px;">
                <input id="restoreTheme" type="checkbox" />
                <?php echo L('theme_layout_tip');?></label>
            </div>
             <div style="margin:10px;">
              <label style="cursor:default;line-height:25px;">
                <input id="restoreDesktopsettingBtn" type="checkbox" />
               <?php echo L('theme_layout_tip1');?></label>
            </div>
         </div>
    </div>
</div>

<!--if condition="($announce)">
<div id="messageBubble" class="bubbleContainer" style="top: 0px; left: 0px; display: none;">
	<div id="messageBubble_bubblePanel" class="bubblePanel" >
    	<span class="icon single"></span><span id="messageBubble_bubblePanel_close" cookie="<?php echo ($announce["cookie"]); ?>" class="icon setting" title="<?php echo L('close');?>" style="z-index:550"></span>					
    	<div id="messageBubble_bubblePanel_message" class="item">
        	<span id="subject_bubbleContainer" class="body" style="top:0px; ">
            <?php if(is_array($announce[data])): ?><?php $i = 0;?><?php $__LIST__ = $announce[data]?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$value): ?><?php ++$i;?><?php $mod = ($i % 2 )?><span class="content" id="announce_<?php echo ($key); ?>"><?php echo ($value["a_title"]); ?></span><span id="announce_summary_data_<?php echo ($key); ?>" style="display:none"><?php echo ($value["a_context"]); ?></span><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
            </span>
        </div>
  	</div>
    <div id="messageBubble_bubbleMsgList" class="bubbleMsgList" style="top: -210px; display: none; ">
    	<h3 id="announce_title"><?php echo ($announce[data][0][a_title]); ?></h3>
        <div class="bubbleMsgListInner">
        	<div id="announce_summary" class="bubbleMsgListContainer">
            	<?php echo ($announce[data][0][a_context]); ?>
          	</div>
      	</div>
  	</div>
</div>
</if-->

<script type="text/javascript">
jQuery(document).ready(function(){
	
	//绑定个人面板的收起展开事件
	var collapse_w;
	jQuery('#dock_member').find('.dock_member_collapse>a').attr('title', '收起');
	jQuery('#dock_member').find('.dock_member_collapse').on('click','a',function(){
		if(jQuery(this).html().indexOf('west')!=-1){	//收起功能
			//获取要收起的宽度
			collapse_w = jQuery('#dock_member').find('.dock_member_content').width()-10;
			jQuery('#dock_member').animate({left:"-"+collapse_w+"px"},function(){
				reset_desktop(collapse_w,'west');
			});
			jQuery(this).attr('title', '展开');
			jQuery(this).html(jQuery(this).html().replace('west', 'east'));
		}else{	//展开功能
			jQuery('#dock_member').animate({left:"0px"},function(){
				reset_desktop(collapse_w,'east');
			});
			jQuery(this).attr('title', '收起');
			jQuery(this).html(jQuery(this).html().replace('east', 'west'));
		}
	});

	_config.dockHeight=jQuery('#dock_bottom').height() || 0;
	
	_config.desktopHeight=_config.screenHeight-_config.dockHeight;
	_config.getConfig("<?php echo U('desktop/System/json',array('uid'=>$uid));?>",'<?php echo ($_GET[cur]); ?>','<?php echo ($_GET[openid]); ?>');
	_message.init('<?php echo ($announce[cookie]); ?>');
	var el1=jQuery('#dock_opbar');
		el1.bind('click',function(){
			_config.dock_up_down();
			return false;
		});
	/*var startmenu=jQuery('#start_menu>.menu-item');
	startmenu.bind('mouseover',function(){
		jQuery(this).addClass('menu-active');
	});
	startmenu.bind('mouseout',function(){
		jQuery(this).removeClass('menu-active');
	});*/
	/*if(jQuery.browser.msie){
		jQuery(document).bind('mousedown',function(){
			var pos=jQuery('#_bodys').position();
			if(Math.abs(pos.left%_config.screenWidth)){
				jQuery('#_bodys').css('marginLeft',Math.abs(pos.left%_config.screenWidth));
			}
			return true;
		});
	}*/
	// jQuery.fx.off = true;

	window.onbeforeunload=function(){
		_config.sendConfig();
		if(_config.leavealert>0 && _config.self>0) return _lang.confirmexit+_config.sitename+_lang.ma;
	}
	
});

</script>
<script type="text/javascript" src="../Public/js/jquery.jstree.js?<?php echo ($verhash); ?>"></script>
<script type="text/javascript" src="../Public/js/message.js?<?php echo ($verhash); ?>"></script>
<script type="text/javascript" src="../Public/js/dsk.js?<?php echo ($verhash); ?>"></script>
<!-- Ricker Yu add 2013-9-3 ajax得到视频反馈信息操作 -->
<!-- 
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery.get('<?php echo U("video/Index/ajaxVideo");?>',
			{time:new Date().getTime()},
			function(data){
				
			});
});
</script>
 -->
<!-- Ricker Yu add 2013-9-3 ajax得到视频反馈信息操作 － end -->
<style>
<!--
#cnzz_stat_icon_1000083231{display:none;}
-->
</style>
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1000083231'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1000083231' type='text/javascript'%3E%3C/script%3E"));</script>
</body>
</html>