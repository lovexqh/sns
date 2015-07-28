<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
﻿﻿<?php include $this->gettpl('header');?>
<?php if($iframe) { ?>
<script type="text/javascript">
	var uc_menu_data = new Array();
	o = document.getElementById('header_menu_menu');
	elems = o.getElementsByTagName('A');
	for(i = 0; i<elems.length; i++) {
		uc_menu_data.push(elems[i].innerHTML);
		uc_menu_data.push(elems[i].href);
	}
	try {
		parent.uc_left_menu(uc_menu_data);
		parent.uc_modify_sid('<?php echo $sid;?>');
	} catch(e) {}
</script>
<?php } ?>
<div class="container">
	<h3>登录信息</h3>
	<ul class="memlist fixwidth">
	  <li>
	  	当前登录用户:&nbsp;&nbsp;<?php echo $loguser;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:chgPwd()">修改密码</a>
	  </li>
	</ul>
	<h3>统一身份 统计信息</h3>
	<ul class="memlist fixwidth">
	  <li><em><?php if($user['allowadminuser'] || $user['isfounder']) { ?><a href="admin.php?m=user&a=ls">用户总数</a><?php } else { ?>用户总数<?php } ?>:</em><?php echo $members;?>
	</li>
	</ul>
<h3>系统信息</h3>
	<ul class="memlist fixwidth">
		<li><em>操作系统及 PHP:</em><?php echo $serverinfo;?></li>
		<li><em>服务器软件:</em><?php echo $_SERVER['SERVER_SOFTWARE'];?></li>
		<li><em>MySQL 版本:</em><?php echo $dbversion;?></li>
		<li><em>上传许可:</em><?php echo $fileupload;?></li>
		<li><em>当前数据库尺寸:</em><?php echo $dbsize;?></li>		
		<li><em>主机名:</em><?php echo $_SERVER['SERVER_NAME'];?> (<?php echo $_SERVER['SERVER_ADDR'];?>:<?php echo $_SERVER['SERVER_PORT'];?>)</li>
		<li><em>magic_quote_gpc:</em><?php echo $magic_quote_gpc;?></li>
		<li><em>allow_url_fopen:</em><?php echo $allow_url_fopen;?></li>		
	</ul>
	<h3>开发团队</h3>
	<ul class="memlist fixwidth">
		<li>
			<em>版权所有:</em>
			<em class="memcont"><a href="http://www.gridinfo.com.cn/" target="_blank">山东锐杰网格信息技术有限公司</a></em>
		</li>
		
	</ul>
</div>

<?php echo $ucinfo;?>

<script type="text/javascript">
	mini.parse();
	
	function chgPwd() {
   	 mini.open({
            url: "admin.php?m=frame&a=pwdChg&username=<?php echo $loguser;?>",
            title: "修改密码", width: 260, height: 280,
            onload: function () {
                var iframe = this.getIFrameEl();
                var data = { action: "new"};
                //iframe.contentWindow.SetData(data);
            },
            ondestroy: function (action) {
                //grid.reload();
            }
        });
	}
</script>

<?php include $this->gettpl('footer');?>