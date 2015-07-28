<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if(($ts['site']['page_title'])  !=  ""): ?><?php echo ($ts['site']['page_title']); ?> <?php echo ($ts['site']['site_name']); ?> <?php else: ?>
<?php echo ($ts['site']['site_name']); ?><?php endif; ?></title>
<link rel="shortcut icon" href="__THEME__/favicon.ico" />
<meta name="keywords" content="<?php echo ($ts['site']['site_header_keywords']); ?>" />
<meta name="description"
	content="<?php echo ($ts['site']['site_header_description']); ?>" />
<script>
	var _UID_   = <?php echo (int) $uid; ?>;
	var _MID_   = <?php echo (int) $mid; ?>;
	var _ROOT_  = '__ROOT__';
	var _THEME_ = '__THEME__';
	var _PUBLIC_ = '__PUBLIC__';
	var _LENGTH_ = <?php echo (int) $GLOBALS['ts']['site']['length']; ?>;
	var _LANG_SET_ = '<?php echo LANG_SET; ?>';
	var $CONFIG = {  };
		$CONFIG['uid'] = _UID_;
		$CONFIG['mid'] = _MID_;
		$CONFIG['root_path'] =_ROOT_;
		$CONFIG['theme_path'] = _THEME_;
		$CONFIG['public_path'] = _PUBLIC_;
		$CONFIG['weibo_length'] = <?php echo (int) $GLOBALS['ts']['site']['length']; ?>;
		$CONFIG['lang'] =  '<?php echo LANG_SET; ?>';
    var bgerr;
    try { document.execCommand('BackgroundImageCache', false, true);} catch(e) {  bgerr = e;}
</script>
<!-- 全局风格CSS -->
<link href="__THEME__/public.css?20110429" rel="stylesheet"
	type="text/css" />
<link href="__THEME__/layout.css?20110429" rel="stylesheet"
	type="text/css" />
<link href="__THEME__/main.css?20110429" rel="stylesheet"
	type="text/css" />
<link href="__PUBLIC__/js/tbox/box.css" rel="stylesheet" type="text/css" />
<!-- 核心JS加载 -->
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/tbox/box.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/scrolltopcontrol.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/weibo.js"></script>
<script src="__PUBLIC__/js/jquery.jgrow.min.js"></script>
<script src="__PUBLIC__/js/jquery.isotope.min.js"></script>


<!-- 编辑器样式文件 -->
<link href="__PUBLIC__/js/editor/editor/theme/base-min.css" rel="stylesheet" />
<!--[if lt IE 8]>
<link
	href="__PUBLIC__/js/editor/editor/theme/cool/editor-pkg-sprite-min.css"
	rel="stylesheet" />
<![endif]-->
<!--[if gte IE 8]>
<link
	href="__PUBLIC__/js/editor/editor/theme/cool/editor-pkg-min-datauri.css"
	rel="stylesheet" />
<![endif]-->

<!--[if lt IE 9]>
<script src="__PUBLIC__/js/html5.js"></script>
<![endif]-->

<style type="text/css">
.headerHolder{
	width:100%;
	height:33px;
	line-height:33px;
	z-index:99999999;position:fixed;_position:absolute;width:100%;min-width:1000px;
	left:0;top:0;
	background:url(__THEME__/member/images/header_src.png) repeat-x left -100px;
}

.header .logo{float:left;width:150px;}
.header .logo a{height: 40px;width: 150px;display: block;text-decoration: none}
.header .logo a:hover{display: block;text-decoration: none}

/*导航下拉共用箭头小图标*/
.header .nav ul li .ico_arrow,.header .person li .ico_arrow{background:url(__THEME__/member/images/ico_arrow.gif) no-repeat 0 0;*background:url(images/ico_arrow_1.gif) no-repeat 0 -2px;_background:url(__THEME__/member/images/ico_arrow.gif) no-repeat 0 0;width:13px;height:12px;display:inline-block}
.header .nav ul li .arrow-bg,.header .person li .arrow-bg{background:url(__THEME__/member/images/ico_arrow_hover.gif) no-repeat 0 0;*background:url(images/ico_arrow_1.gif) no-repeat 0 -2px;_background:url(__THEME__/member/images/ico_arrow.gif) no-repeat 0 0;width:13px;height:12px;display:inline-block}
.header .nav ul li.hover .ico_arrow,.header .person li.hover .ico_arrow{background-image:url(__THEME__/member/images/ico_arrow_hover.gif);background-repeat:no-repeat;width:13px;height:12px;display:inline-block}
.header .nav ul li .ico_new,.header .person li .ico_new{position:absolute;top:-10px;right:0px;z-index:10}

/*左导航*/
.header .nav {padding-left:15px; float:left;}
.header .nav ul{list-style-type:none;margin: 0px;height:30px}
.header .nav ul li{font-size:14px;color:#333;float:left;position:relative;display:block;margin:0 5px 0 0;list-style-type: none;z-index:999}
.header .nav ul li a{font-size:12px; color:#333; display:block; padding:0 10px;}

/*导航搜索区*/
.quick_search_form{
	position:absolute;
	left:440px;
	top:0px;
	margin:0px;
	padding:0px;
}
.soso{float:left;height:24px; line-height:26px;border:1px solid #ccc;background-color:#fff;width:199px;position:relative;top:3px;box-shadow: 0 1px 0 0 #F0F0F0 inset}
.soso label{padding-left: 5px; margin-top: 0px;position: absolute;color:#999;cursor: text;width:170px;background-color:#fff;height:24px; line-height:24px;}
.so_text{width:173px;*width:170px;border:0 none;background-color:#fff;color:#999;-moz-border-radius: 1px;-khtml-border-radius: 1px;-webkit-border-radius: 1px;border-radius: 1px;margin: 2px;outline: 0 none;padding-left:5px;}
.line-text{border:0;height:20px;width:180px}

/*右导航*/
.header .person {padding-left:10px;float:right}
.header .person{list-style-type:none;margin: 0px;height:37px}
.header .per-info{list-style-type:none;margin-right: 300px;margin-top:6px;font-weight:bold}
.header .person li{font-size:12px;color:#333;float:left;position:relative;display:block;margin:0;list-style-type: none;z-index:9}
.header .person li a.username{ overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 65px;word-wrap:normal}

/*导航链接样式*/
.header .nav ul li a.application{line-height:16px;padding:8px 8px;_padding:8px 5px;display: block;color:#333;overflow:hidden;}
.header .person li a.application{line-height:16px;padding:8px 8px;_padding:8px 5px;display: block;color:#333}
.header .nav ul li.hover a.application{padding:6px 8px 12px;_padding:6px 5px 11px;color:#143149}
.header .nav ul li.hover a.application:hover{padding:6px 8px 12px;_padding:6px 5px 11px;text-decoration:none}
.header .person li.hover a.application{padding:6px 8px 12px;_padding:8px 5px 11px;color:#143149}
.header .person li.hover a.application:hover{padding:6px 8px 12px;_padding:8px 5px 11px; text-decoration:none}

.ico_app_add{height:16px;width:16px;background-position:-87px -35px;margin-top:-1px;vertical-align:middle}

/*新消息列表*/
.header .person li.hover .dropmenu .message{color:#999;clear:both;border-bottom:1px solid #ccc;width:170px;padding:0}
.header .person li.hover .dropmenu-right .message{color:#999;clear:both;border-bottom:1px solid #ccc;width:170px;padding:0}
.header .person li.hover .dropmenu .message_list_new{border-bottom:1px solid #ccc;overflow:hidden;*zoom:1;*width:170px}
.header .person li.hover .dropmenu .message_list_new li{color: #666;padding: 0 10px;float:none}
.header .person li.hover .dropmenu .message_list_new li a{display: inline;padding: 0 7px;color:#333;}
.header .person li.hover .dropmenu .message_list_new li a:hover{background:none;text-decoration:underline;display:inline}

.header .person li.hover .dropmenu-right.message_list_new{border-bottom:1px solid #ccc;overflow:hidden;*zoom:1;*width:170px}
.header .person li.hover .dropmenu-right .message_list_new li{color: #666;padding: 0 10px;float:none}
.header .person li.hover .dropmenu-right .message_list_new li a{display: inline;padding: 0 7px;color:#333;}
.header .person li.hover .dropmenu-right.message_list_new li a:hover{background:none;text-decoration:underline}

/*具体背景加样式*/
.header .nav ul li.hover .dropmenu,.header .person li.hover .dropmenu{background-color: #fff;border: 1px solid #95C3D8;border-top:0 none}
.header .nav ul li.hover .dropmenu a:hover,.header .person li.hover .dropmenu a:hover{background-color: #B0E0FA;text-decoration: none;display:block}
.header .nav ul li.hover .dropmenu .app_list dd a:hover {background-color:#B0E0FA}
.header .nav ul li a.fb14:hover,.header .person li a.fb14:hover{background-color:#3A90BE;text-decoration:none;white-space:nowrap;display: block}

/*导航当前状态样式*/
.header .nav ul li a.fb14,.header .person li a.fb14{color:#333;white-space:nowrap;line-height:17px;padding:8px 10px 8px;display: block;*padding:8px 10px 8px}
.header .nav ul li a.fb14:hover,.header .person li a.fb14:hover{background-color:#f0f0f0;text-decoration:none;white-space:nowrap;display: block;line-height:17px;padding:7px 10px 6px;*padding:7px 10px 4px;}
.header .nav ul li a.nav-bg,.header .person li a.nav-bg{color:#494949;white-space:nowrap;line-height:16px;padding:14px 10px 10px;display: block;font-size:14px}
.header .nav ul li a.info-bg,.header .person li a.info-bg{color:#B85B00;white-space:nowrap;line-height:16px;padding:13px 10px 10px;display: block;font-size:14px;font-weight:bold}
.header .nav ul li a.nav-bg:hover,.header .person li a.nav-bg:hover{background-color:#dbdbdb;text-decoration:none;white-space:nowrap;display: block;padding:12px 10px 9px}
.header .nav ul li a.info-bg:hover,.header .person li a.info-bg:hover{background-color:#dbdbdb;text-decoration:none;white-space:nowrap;display: block;padding:12px 10px 9px}
.header .nav ul li.on,.header .person li.on {background-color:#143149}
.header .nav ul li.on a.fb14,.header .person li.on a.fb14{color:#333}
.header .nav ul li.hover a.fb14,.header .person li.hover a.fb14{color:#193E5C}

/*次导航*/
.header .nav_sub{background: none repeat scroll 0 0; position: absolute; right: 10px;top:0;white-space: nowrap;height:26px;overflow:hidden}
.header .nav_sub a:link,.header .nav_sub a:visited{color:#333}
.header .nav_sub_hover{height:auto}
.header .nav_sub ul li{float:left}
.header .nav_sub dl{padding:4px 5px 5px 5px}
.header .nav_sub dl dt{padding:0 5px;margin-bottom:5px}
.header .nav_sub dl dd{line-height:22px}
.header .nav_sub dl dd a{display:block;padding:0 5px}
.header .nav_sub dl dd a:hover{display:block;background-color:#274c66}

.header .nav ul li .msg_top{background: url(__THEME__/member/images/msg.gif) no-repeat 0px 0px;display: block;height: 18px;text-align: center;padding-left: 8px;text-decoration: none;position: absolute;top:3px;left:47px;font-weight: bold}
.header .nav ul li .msg_top a{display: block;background: url(__THEME__/images/msg.gif) no-repeat right 0px;height: 18px;padding-right: 9px;margin-right: -6px;text-decoration: none;line-height: 18px;float: left;color: #fff;white-space: nowrap;font-size:10px}
.header .nav ul li.hover .msg_top<?php echo $_GET["./background: url(__THEME__/images/msg.gif) no-repeat 0px 0px;display: block;height: 18px;text-align: center;line-height: 18px;padding-left: 8px;color: #fff;text-decoration: none;position: absolute;top:3px;left:47px;"];?>

/*消息提醒框样式*/
.header .layer_massage_box{background:url(__THEME__/memberimages/layer_bg1.png) repeat scroll 0 0 transparent;border-radius:3px 3px 3px 3px;border:#797979 solid 1px;padding:10px;position:absolute;right:52px;top:33px;width:180px}
.header .layer_massage_box .del{position:absolute;right:0;top:0}
.header .layer_massage_box li{line-height:23px}
</style>

<?php if(isset($_SESSION["system"])): ?><style type="text/css">
.headerHolder{
	display:none;
}
.containerOuter .wrap{
	padding:0;
}
</style><?php endif; ?>

<?php echo Addons::hook('public_head',array('uid'=>$uid));?>
</head>

<body>
<script type="text/javascript" src="../Public/js/video.js"></script>
<link href="../Public/css/space.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
//切换图片
function switchPic(id,type,picurl){
	if( type=='close' ){
		$("#pic_show_"+id).hide();
		$("#pic_mini_show_"+id).show();
	}else{
		
		if( $("#pic_show_"+id).find('.imgSmall').attr('src')==''){
			$("#pic_mini_show_"+id).find('.loadimg').show();
			var img = new Image;
			img.src = picurl+'?time='+new Date();
			img.onload = function(){
				if( this.width>450 ){
					$("#pic_show_"+id).find('.imgSmall').css('width','450px');
				}
				$("#pic_show_"+id).find('.imgSmall').attr('src',this.src);
				$("#pic_mini_show_"+id).find('.loadimg').hide();
				$("#pic_show_"+id).show();
				$("#pic_mini_show_"+id).hide();	
			};
		}else{
			$("#pic_show_"+id).show();
			$("#pic_mini_show_"+id).hide();	
		}
	}
}
</script>
<style type="text/css">
<!--
.STYLE1 {
	color: #000000
}
#follow_state .btn_relation{
	margin-left:0;
}
-->
</style>
</head><body>
<!-- 页面主体内容begin -->
<div id="wrapper">
  <div class="header">
    <div class="header_tx">
      <div class="tx_sc"><img src="<?php echo (getUserFace($uid,b)); ?>" alt="头像"></div>
      <ul class="guanzhu">
        <li class="gz_sz"><a
          href="<?php echo U('home/space/follow',array('uid'=>$uid,'type'=>'following'));?>"><strong
          style="font-family:Arial, Helvetica, sans-serif; font-size:16px;color:#666"><?php echo (intval($spaceCount["following"])); ?></strong><br>
          <font style="color:#666">我关注</font></a></li>
        <li class="gz_sz_jgx"></li>
        <li class="gz_sz"><a
          href="<?php echo U('home/space/follow',array('uid'=>$uid,'type'=>'follower'));?>"><strong
          style="font-family:Arial, Helvetica, sans-serif; font-size:16px;color:#666"><?php echo (intval($spaceCount["follower"])); ?></strong><br>
          <font style="color:#666">关注我</font></a></li>
        <li class="gz_sz_jgx"></li>
        <li class="gz_sz"><a
          href="<?php echo U('home/Space/index',array('uid'=>$uid));?>"><strong
          style="font-family:Arial, Helvetica, sans-serif; font-size:16px;color:#666"><?php echo (intval($spaceCount["miniblog"])); ?></strong><br>
          <font style="color:#666">微广播</font></a></li>
      </ul>
    </div>
    <div class="nav">
      <ul>
        <?php if(is_array($nav_menu)): ?><?php $$key = 0;?><?php $__LIST__ = $nav_menu?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$$key;?><?php $mod = ($$key % 2 )?><li><a href="<?php echo ($vo); ?>" title="<?php echo ($key); ?>"><?php echo ($key); ?></a></li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
      </ul>
    </div>
    <div class="personal_tag">
      <div style="line-height:45px; white-space:nowrap;"><strong style="font-size:16px"><?php echo (getUserName($uid)); ?>(<?php if(($userinfo['identitytype'] == 2)): ?>老师<?php else: ?>学生<?php endif; ?>)</strong> 微广播地址:<?php echo ($userinfo['微广播地址']); ?></div>
      <div style="line-height:25px">个性简介：
      <?php if(empty($summary)): ?>该用户暂无任何签名
      <?php else: ?>
      	<?php echo ($summary); ?><?php endif; ?>
      </div>
      <div style="line-height:25px;overflow:hidden;width=430px;height:25px;">标签：
      <?php if(empty($usertags)): ?><span>该用户暂无任何标签</span><?php endif; ?>
      <?php if(is_array($usertags)): ?><?php $i = 0;?><?php $__LIST__ = $usertags?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><a
      style=" color:#000000;background-color: #FF6A6A ;padding: 3px 8px;border-radius: 2px 2px 2px 2px;"
      tagid="<?php echo ($vo["tag_id"]); ?>"
      href="javascript:;"
      onClick="top.OpenBrowser('<?php echo U('home/user/searchtag',array('k'=>urlencode($vo['tag_name'])));?>','标签 - <?php echo ($vo["tag_name"]); ?>', 'width=1024,height=600,titlebutton=close|max|min');"
      title="标签 - <?php echo ($vo["tag_name"]); ?>"><?php echo ($vo["tag_name"]); ?></a>&nbsp;&nbsp;<?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
      </div>
      <?php if($mid!=$uid && $mid!=0){ ?>
      <?php if($isBlackList){ ?>
      已加入黑名单， <a href="javascript:void(0)"
      onclick="ui.confirm(this,'确定要将此用户从你黑名单中解除?')"
      callback="setBlacklist(<?php echo ($uid); ?>,'del')">解除</a>
      </dd>
      <?php }else{ ?>
      <dd style="width:90%; text-align:left; padding-top:5px"
      id="follow_state"><script>document.write(followState('<?php echo ($followstate); ?>'))</script> 
      </dd>
      <?php } ?>
      <?php } ?>
    </div>
    <div class="personal_tool">
      <ul>
      <?php if($mid != $uid): ?><li class="personal_tool_menu">
        <a href="javascript:void(0);" onclick="followGroupSelectorBox('<?php echo ($uid); ?>')"><img src="../Public/images/szfz.png"></a>
        </li>
        <li class="personal_tool_menu">
         <a href="javascript:void(0);" onclick="weibo.quickpublish('快来看看 @<?php echo (getUserName($uid)); ?> 的微广播')">
        <img
          src="../Public/images/txhy.png"></a>
        </li>
        <li class="personal_tool_menu">
        <a href="javascript:void(0);" onclick="ui.sendmessage(<?php echo ($uid); ?>)"><img
          src="../Public/images/fsx.png"></a>
        </li>
        <li class="personal_tool_menu">
        <a href="javascript:void(0);" onclick="ui.confirm(this,'您确定要将此用户加入黑名单?')"
          callback="setBlacklist(<?php echo ($uid); ?>,'add')"><img src="../Public/images/jhmd.png"></a>
        </li><?php endif; ?>
      </ul>
    </div>
  </div>
  <div class="clear"></div>

<script type="text/javascript" src="__PUBLIC__/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/weibo.js"></script>
<script>
var weibo = $.weibo({
      timeStep : 10000
});
</script>
<style>
<!--
body{background:none;}
-->
</style>
  <div class="content">
    <div class="personal_msg">
      <ul>
        <!-- <li class="identity">> 学生</li>-->
        <li class="sex"> 真实姓名  <?php echo empty($userinfo['真实姓名'])?'未知姓名':$userinfo['真实姓名']; ?></li>
        <li class="sex"> 性别  <?php echo empty($userinfo['性别'])?'未知性别':$userinfo['性别']; ?></li>
        <li class="address"> 现居 <?php echo empty($userinfo['所在地'])?'未知地区':$userinfo['所在地']; ?></li>
        <?php if(($userinfo['identitytype'] == 2)): ?><li class="address" title="<?php echo ($userinfo['部门']); ?>"> 部门 <?php echo empty($userinfo['部门'])?'未知部门': mStr($userinfo['部门'],7); ?></li>
        <li class="address" title="<?php echo ($userinfo['职务']); ?>"> 职务 <?php echo empty($userinfo['职务'])?'':mStr($userinfo['职务'],7); ?></li>
        <?php else: ?>
        <li class="address" style="overflow:hidden;"> 院系  <?php echo empty($userinfo['院系'])?'未知院系':$userinfo['院系']; ?></li>
        <li class="address"> 班级  <?php echo empty($userinfo['班级'])?'未知班级':$userinfo['班级']; ?></li><?php endif; ?>
        <li class="personal_msg_xq"><a
          href="<?php echo U('home/space/profile',array('uid'=>$uid));?>">>详细资料</a></li>
      </ul>
    </div>
    <?php echo Addons::hook('home_space_middle', array('uid'=>$uid));?>
    
    <div>
      <div class="c_left">
        <div class="weibo_menu_tabs">
          <ul>
            <?php if(is_array($space_menu)): ?><?php $i = 0;?><?php $__LIST__ = $space_menu?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li><a id="feed_colleague_item" rel="colleague"
              style="width:65px; text-align:center; color:#333" class="<?php if($type == $key): ?>on<?php endif; ?>" href="<?php echo U('home/Space/index',
                array('uid'=>$_GET['uid'], 'type'=>$key));?>"><span><?php echo ($vo); ?></span></a></li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
          </ul>
        </div>
        <div class="clear clearfix"></div>
        <!-- 个人主页微广播分类 -->
        <?php if(($type)  ==  "weibo"): ?><div class="weibo_menu_tag" id="MenuSub">
            <?php if(is_array($weibo_menu)): ?><?php $i = 0;?><?php $__LIST__ = $weibo_menu?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><ul>
                <li><a id="feed_colleague_item" rel="colleague" class="<?php if($weibo_type == $key): ?>on<?php endif; ?>
                  "
                  href="<?php echo U('home/Space/index',array('uid'=>$uid,'weibo_type'=>$key));?>"><span><?php echo ($vo); ?></span></a> </li>
              </ul><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
          </div><?php endif; ?>
        <?php if(($type)  ==  "teach"): ?><div class="summary-list task_list">
                    	<ul>
              <?php if(empty($data)): ?><li style="line-height:80px;">还没有发布教研</li><?php endif; ?>
              <?php if(is_array($data)): ?><?php $i = 0;?><?php $__LIST__ = $data?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li class="win">
                  <div class="mid">
                    <strong>教研标题:<a href="<?php echo U('teach/Index/showTeaching', array('meetingId'=>$vo['meetingId']));?>" target="_blank"><?php echo ($vo['meetingName']); ?></a></strong>
                    <span>教研类型:<?php echo (getSelectItem($vo["teachingtype"])); ?> 举行方式:<?php echo (getSelectItem($vo["dostyle"])); ?> 创建人:<?php echo (getUserSpace($vo["uid"])); ?></span>
                    <span><?php echo ($vo["auditStatus"]); ?> 隶属于:<?php echo (getSchoolName($vo['schoolid'])); ?> 开始于:<?php echo (dateFormat($vo['startTime'])); ?> 结束于:<?php echo (dateFormat($vo['endTime'])); ?></span>
                  </div>
                  <div class="rt">                               
                    <a href="<?php echo U('teach/Index/showTeaching', array('meetingId'=>$vo['meetingId']));?>" target="_blank">查看详情</a><i class="vline">|</i> 
                        
                    <?php echo W('Weibo',array('tpl_name'=>'teaching_share_weibo','button_title'=>'分享'));?>
                    <?php $tpl_data = array('author'=>getUserName($vo[uid]),'title'=>$vo[meetingName],'url'=>U('teach/Index/showTeaching',array('meetingId'=>$vo[meetingId])),'type'=>6);
                    $tpl_data = urlencode(serialize($tpl_data)); ?>
                    <a href="javascript:void(0)" onclick="_widget_weibo_start('', '<?php echo ($tpl_data); ?>');"  class="link-act">分享</a>
                    <?php if($vo['uid']!=$mid){ ?>
                    <i class="vline">|</i>
                    <?php echo W('Favorite',array('fid'=>$vo['meetingId'],'app'=>'teach','mod'=>'Index','act'=>'showTeaching','type'=>'link','count'=>1));?>  
                    <?php } ?>
                  </div>
                </li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
            </ul>
            
<script type="text/javascript">var is_relink = true;</script>
<script language="javascript" src="__PUBLIC__/js/desktop/relink.js"></script>
<script>replace_link('.win');</script>
<script language="javascript">
<!--
function joinMetting(meetingId){
	ui.box.load(U('teaching/Index/alertPwdWindow')+"&meetingId="+meetingId,{title:"加入会议"});
}
//-->
</script>
          </div><?php endif; ?>
        <?php if(($type)  ==  "prepare"): ?><div class="summary-list task_list">
                        <ul>
              <?php if(empty($data)): ?><li style="line-height:80px;">还没有发布备课</li><?php endif; ?>
              <?php if(is_array($data)): ?><?php $i = 0;?><?php $__LIST__ = $data?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li class="win">
                  <div class="mid">
                    <strong>备课名称：<a href="<?php echo U('prepare/Index/show', array('id'=>$vo['id'],'stype'=>$vo['stype']));?>" target="_blank"><?php echo ($vo['title']); ?></a></strong>
                    <?php if($vo['type'] == 1): ?><span>备课类型：个人备课
                        <?php if(($relate == 2) or ($vo['uid'] != $mid)): ?>备课人：<?php echo getUserSpace($vo["uid"],'','_blank','{uname}') ?> <?php echo (getUserGroupIcon($vo["uid"])); ?><?php endif; ?>
                        </span>
                    <?php elseif($vo['type'] == 2): ?>
                        <span>备课类型：集体备课
                        <?php if(($relate == 2) or ($vo['uid'] != $mid)): ?>备课人：<?php echo getUserSpace($vo["uid"],'','_blank','{uname}') ?> <?php echo (getUserGroupIcon($vo["uid"])); ?><?php endif; ?>
                        </span><?php endif; ?>
                    <span>
                      <?php if($vo['status'] != 1): ?>状态：未发布<?php endif; ?>
                      <?php if($relate == 1): ?><?php echo ($vo["auditStatus"]); ?><?php endif; ?>
                      最新更新时间：<?php echo (friendlyDate($vo['mtime'])); ?>
                    </span>
                  </div>
                  <div class="rt">
                    <a href="'<?php echo U('prepare/Index/show', array('id'=>$vo['id'],'stype'=>$vo['stype']));?>" target="_blank">查看详情</a><i class="vline">|</i>
                    <?php echo W('Weibo',array('tpl_name'=>'prepare_share_weibo','button_title'=>'分享'));?>
                    <?php $tpl_data = array('author'=>getUserName($uid),'title'=>$vo[title],'url'=>U('prepare/Index/show',array('id'=>$vo['id'],'stype'=>$vo['stype'])),'type'=>1);
                    $tpl_data = urlencode(serialize($tpl_data)); ?>
                    <a href="javascript:;" onclick="_widget_weibo_start('', '<?php echo ($tpl_data); ?>', '<?php echo ($param_data); ?>');">分享</a>
                    <?php if($vo['uid']!=$mid){ ?>
                    <i class="vline">|</i>
                    <?php if($vo['stype'] == 1): ?><?php echo W('Favorite',array('fid'=>$vo['id'],'app'=>'prepare','mod'=>'Index','act'=>'show','type'=>'link','count'=>1,'myuid'=>$vo['uid'],'appconfig'=>'prepareknowledge'));?>
                    <?php elseif($vo['stype'] == 2): ?>
                   <?php echo W('Favorite',array('fid'=>$vo['id'],'app'=>'prepare','mod'=>'Index','act'=>'show','type'=>'link','count'=>1,'myuid'=>$vo['uid'],'appconfig'=>'preparechapter'));?><?php endif; ?>
                    <?php } ?>
                  </div>
                </li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
            </ul>
<script type="text/javascript">var is_relink = true;</script>
<script language="javascript" src="__PUBLIC__/js/desktop/relink.js"></script>
<script>replace_link('.win');</script>
          </div><?php endif; ?>
        <?php if(($type)  ==  "resource"): ?><div class="summary-list task_list">
                        <ul>
              <?php if(empty($data)): ?><li style="line-height:80px;">还没有上传资源</li><?php endif; ?>
              <?php if(is_array($data)): ?><?php $i = 0;?><?php $__LIST__ = $data?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li>
                  <div class="mid">
                    <strong>资源标题：<a href="<?php echo U('resource/Index/showresource',array('id'=>$vo['id'],iframe=>'yes'));?>" target="_blank"><?php echo (getShort($vo["title"],11)); ?></a></strong>
                    <span>分类信息：<?php echo (getDictionary($vo['class'])); ?> 资源类型：<?php echo (getDictionary($vo['type'])); ?></span>
                    <span>审核：<?php if($vo[state]==0){ ?>待审核<?php }else if($vo[state]==1){ ?>审核通过<?php }else{ ?><font style="color:#F00;">驳回</font><?php } ?>
                      
                      上传时间：<?php echo (date("Y-m-d",$vo["time"])); ?>
                    </span>
                  </div>
                  <div class="rt">
                  	<a href="<?php echo U('resource/Index/showresource',array('id'=>$vo['id'],iframe=>'yes'));?>" target="_blank">查看详情</a><i class="vline">|</i> 
                        
                    <?php echo W('Weibo',array('tpl_name'=>'resource_share_weibo','button_title'=>'分享'));?>
                    <?php $tpl_data = array('author'=>getUserName($vo[uid]),'title'=>$vo['title'],'url'=>U('resource/Index/showresource',array('id'=>$vo['id'])),'type'=>0);
                    $tpl_data = urlencode(serialize($tpl_data)); ?>
                    <a href="javascript:void(0)" onclick="_widget_weibo_start('', '<?php echo ($tpl_data); ?>');"  class="link-act">分享</a>
                    <?php if($vo['uid']!=$mid){ ?>
                    <i class="vline">|</i>
                    <?php echo W('Favorite',array('fid'=>$vo['id'],'app'=>'resource','mod'=>'Index','act'=>'showresource','type'=>'link','count'=>1));?>  
                    <?php } ?>
                  </div>
                </li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
            </ul>
          </div><?php endif; ?>
        <!-- 微博内容显示 -->
        <div class="weibo_list"> 
          <!-- /个人主页微广播分类 -->
          <?php if($list): ?><div class="feedBox"><?php echo W('WeiboList', array('mid'=>$mid, 'list'=>$list['data'], 'insert'=>0, 'hidden_user'=>1));?></div>
            <div class="page"><?php echo ($list["html"]); ?></div>
            <?php else: ?>
            <?php echo Addons::hook('home_space_list', array('uid'=>$uid,'type'=>$type));?><?php endif; ?>
        </div>
      </div>
      <div class="c_right main_friend">
  <div class="main_friend_pho"> <?php echo Addons::hook('home_space_right_bottom', array('uid'=>$uid));?></div>
  <div class="clear"></div>
  <div class="main_friend_more">我关注的人共(<?php echo (intval($spaceCount["following"])); ?>)</div>
</div>
<script language="javascript">
<!-- 
$('.main_friend_pho').find('h2').each(function(i, e) {
	var title = $(this).text().replace($(this).find('a').text(),"");
	var href = $(this).find('a').attr('href');
	$(this).on('click','a',function(){
		top.OpenWindow('url',href,title,'','titlebutton=close,width=1035,height=600');
		return false;
	});
	$(this).find('a').attr('href','javascript:;');
});
//-->
</script>
      <div class="clear"></div>
    </div>
  </div>
  <div class="clear"></div>
</div>
<!-- 页面主体内容end -->

<!-- 内容 end -->
<?php if(is_array($ts['ad']['footer'])): ?><?php $i = 0;?><?php $__LIST__ = $ts['ad']['footer']?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><div class="ad_footer"><div class="ke-post"><?php echo ($vo['content']); ?></div></div><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
<div class="footer_bg">
<div class="footer">
	<div class="menu">
		<?php foreach($ts['footer_document'] as $k => $v) {
            $v['url'] = isset($v['url']) ? $v['url'] : U('home/Public/document',array('id'=>$v['document_id']));
            $ts['footer_document'][$k] = '<a href="'.$v['url'].'" target="_blank">'.$v['title'].'</a>';
        }
        echo implode('&nbsp;&nbsp;|&nbsp;&nbsp', $ts['footer_document']); ?>
	</div>
	<div>
		<?php echo ($ts['site']['site_icp']); ?>
		<?php if(isMobile()) { ?>
			<a href="<?php echo U('home/Public/toWap');?>">访问WAP版</a>
		<?php } ?>
	</div>
</div>
</div>
</div><!-- /wrap -->
</div><!-- /containerOuter -->
<script language="javascript" src="__PUBLIC__/js/desktop/relink.js"></script>
<?php $ts['cnzz'] = getCnzz(false);
if (!empty($ts['cnzz'])) { ?>
<div style="display:none;">
<script src="http://s87.cnzz.com/stat.php?id=<?php echo ($ts['cnzz']['cnzz_id']); ?>&web_id=<?php echo ($ts['cnzz']['cnzz_id']); ?>" language="JavaScript" charset="gb2312"></script>
</div>
<?php } ?>
<?php echo Addons::hook('public_footer');?>
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1000083231'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1000083231' type='text/javascript'%3E%3C/script%3E"));</script>
</body>
</html>
<script type="text/javascript">var is_relink=true;</script>
<script language="javascript" src="__PUBLIC__/js/desktop/relink.js"></script>
<script type="text/javascript">replace_link('.personal_photo');</script>