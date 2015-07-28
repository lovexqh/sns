<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo ($ts['site']['site_name']); ?>管理后台</title>
<link href="__PUBLIC__/admin/style.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/js/tbox/box.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	var _UID_ = "<?php echo ($uid); ?>";
	var _PUBLIC_ = "__PUBLIC__";	
</script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/tbox/box.js"></script>
</head>
<body>
<div class="so_main">
  <div class="page_tit">注册配置</div>
  
  <div class="form2">
  	<form method="post" action="<?php echo U('admin/Global/doSetRegisterOpt');?>">
    <dl class="lineD">
      <dt>注册方式：</dt>
      <dd>
        <label>
        	<input name="register_type" type="radio" value="closed" <?php if(($register_type)  ==  "closed"): ?>checked<?php endif; ?>>关闭注册
        </label>
        <br>
        <label>
        	<input name="register_type" type="radio" value="invite" <?php if(($register_type)  ==  "invite"): ?>checked<?php endif; ?>>邀请注册
        </label>		
        <br />
        <label>
        	<input name="register_type" type="radio" value="open" <?php if(($register_type)  ==  "open"): ?>checked<?php endif; ?>>公开注册
        </label>
      </dd>
    </dl>
    <dl class="lineD">
        <dt>邀请方式：</dt>
        <dd>
            <label><input name="invite_set" type="radio" value="close" <?php if(($invite_set)  ==  "close"): ?>checked<?php endif; ?>>关闭邀请</label><br>
            <label><input name="invite_set" type="radio" value="invitecode" <?php if(($invite_set)  ==  "invitecode"): ?>checked<?php endif; ?>>使用邀请码</label><br>      
            <label><input name="invite_set" type="radio" value="common" <?php if(($invite_set)  ==  "common"): ?>checked<?php endif; ?>>普通邀请</label>
            <p>当“注册方式”为“邀请注册”时有效</p>
        </dd>
    </dl>
    <dl class="lineD">
      <dt>注册后需要邮件激活：</dt>
      <dd>
        <label>
        	<input name="register_email_activate" type="radio" value="0" <?php if(($register_email_activate)  ==  "0"): ?>checked<?php endif; ?>>否
        </label>
        <br>
        <label>
        	<input name="register_email_activate" type="radio" value="1" <?php if(($register_email_activate)  ==  "1"): ?>checked<?php endif; ?>>是
        </label>
      </dd>
    </dl>
    <dl>
      <dt>默认关注的用户ID：</dt>
      <dd>
        <input name="register_auto_friend" type="text" value="<?php echo ($register_auto_friend); ?>">
        <p>多个时以英文的“,”分割</p>
    </dl>
    <!-- 
    <dl class="lineD">
      <dt>开启真实姓名检查：</dt>
      <dd>
        <label>
        	<input name="register_realname_check" type="radio" value="0" <?php if(($register_realname_check)  ==  "0"): ?>checked<?php endif; ?>>否
        </label>
        <br>
        <label>
        	<input name="register_realname_check" type="radio" value="1" <?php if(($register_realname_check)  ==  "1"): ?>checked<?php endif; ?>>是
        </label>
      </dd>
    </dl>
    <dl class="lineD">
      <dt>姓氏：</dt>
      <dd>
        <textarea name="register_lastname" cols="50" rows="5"><?php echo ($register_lastname); ?></textarea>
        <p>以英文的“,”分割</p>
      </dd>
    </dl>
     -->
    <div class="page_btm">
      <input type="submit" class="btn_b" value="确定" />
    </div>
    </form>
  </div>

</div>
<!-- 底部状态栏 start -->
<div region="south" id="bottomBar" class="bottomBar" style="text-align:center; clear:both; line-height: 30px; background:none; text-align: center;">
    <?php echo ($ts['site']['site_icp']); ?>
    <div id="site_analytics_code" style="display:none;">
        <?php echo (base64_decode($site["site_analytics_code"])); ?>
    </div>
</div>
<!-- 底部状态栏 end -->
<!-- 底部状态栏 end -->
</body>
</html>