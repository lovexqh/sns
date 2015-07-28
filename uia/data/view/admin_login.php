<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<script type="text/javascript">
function $(id) {
	return document.getElementById(id);
}
</script>

<div class="container" style=" margin:auto; padding:0;padding-top:20px; width:1156px; background:url(images/loginbg.jpg) no-repeat;height:600px;">
	<!-- <form action="admin.php?m=user&a=login" method="post" id="loginform" <?php if($iframe) { ?>target="_self"<?php } else { ?>target="_top"<?php } ?>> -->
	<form action="admin.php?m=user&a=login" method="post" id="loginform" target="_self">
		<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
		<input type="hidden" name="seccodehidden" value="<?php echo $seccodeinit;?>" />
		<input type="hidden" name="iframe" value="<?php echo $iframe;?>" />
		<table class="mainbox">
			<tr>
				<!--<td class="loginbox">
					<h1>UCenter</h1>
					<p>统一身份应用是一个能沟通多个应用的桥梁，使各应用共享一个用户数据库，实现统一登录，注册，用户管理。</p>
				</td>-->
                <td width="300"> &nbsp;</td>
				<td class="login">
					<?php if($errorcode == UC_LOGIN_ERROR_FOUNDER_PW) { ?><div class="errormsg loginmsg"><p>超级管理员密码错误</p></div>
					<?php } elseif($errorcode == UC_LOGIN_ERROR_ADMIN_PW) { ?><div class="errormsg loginmsg"><p><em>登录失败!</em><br />用户名无效，或密码错误。</p></div>
					<?php } elseif($errorcode == UC_LOGIN_ERROR_ADMIN_NOT_EXISTS) { ?><div class="errormsg loginmsg"><p>该管理员不存在</p></div>
					<?php } elseif($errorcode == UC_LOGIN_ERROR_SECCODE) { ?><div class="errormsg loginmsg"><p>验证码输入错误</p></div>
					<?php } elseif($errorcode == UC_LOGIN_ERROR_FAILEDLOGIN) { ?><div class="errormsg loginmsg"><p>密码重试次数过多，请十五分钟后再重新尝试</p></div>
					<?php } ?>
					<p style="text-align:left;"><input type="radio" name="isfounder" value="0" class="radio" checked="checked" onclick="$('username').value=''; $('username').readOnly = false; $('username').disabled = false; $('username').focus();" id="admin" /><label for="admin">管理员</label></p>
					<p id="usernamediv">用户名:<input type="text" name="username" class="txt" tabindex="1" id="username" value="<?php echo $username;?>" /></p>
					<p>密　码:<input type="password" name="password" class="txt" tabindex="2" id="password" value="<?php echo $password;?>" /></p>
					<p>验证码:<input type="text" name="seccode" class="txt" tabindex="2" id="seccode" value="" style="margin-right:5px;width:85px;" /><img width="70" height="21" src="admin.php?m=seccode&seccodeauth=<?php echo $seccodeinit;?>&<?php echo rand();?>" /></p>
					<p class="loginbtn"><input type="submit" name="submit" value="登 录" class="btn" tabindex="3" /></p>
				</td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript">
	$('username').readOnly = false;
	$('username').readOnly = false;
	$('username').focus();
</script>
<div class="footer">Powered by Ruijie-Grid <?php echo UC_SERVER_VERSION;?> &copy; 2001 - 2011 <a href="http://www.ruijie-grid.com/" target="_blank">Ruijie</a> Inc.</div>
<?php include $this->gettpl('footer');?>