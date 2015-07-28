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
  <div class="page_tit">用户管理</div>
  <!-------- 搜索用户 -------->
  <div id="searchUser_div" <?php if(($type)  !=  "searchUser"): ?>style="display:none;"<?php endif; ?>>
  	<div class="page_tit">搜索用户 [ <a href="javascript:void(0);" onclick="searchUser();">隐藏</a> ]</div>
	
	<div class="form2">
	<form method="post" action="<?php echo U('admin/User/doSearchUser');?>">
    <dl class="lineD">
      <dt>Email：</dt>
      <dd>
        <input name="email" id="email" type="text" value="<?php echo ($email); ?>">
        <p>用户进行登录的帐号,多个时使用英文的","分割</p>
      </dd>
    </dl>
	
	<?php if($type != 'searchUser') $uid = ''; ?>
    <dl class="lineD">
      <dt>用户ID：</dt>
      <dd>
        <input name="uid" id="uid" type="text" value="<?php echo ($uid); ?>">
        <p>用户ID,多个时使用英文的","分割</p>
      </dd>
    </dl>
	
    <dl class="lineD">
      <dt>昵称：</dt>
      <dd>
        <input name="uname" id="uname" type="text" value="<?php echo ($uname); ?>">
        <p>多个时使用英文的","分割</p>
      </dd>
    </dl>
	 
	<dl>
      <dt>用户组：</dt>
      <dd>
      	<a href="javascript:void(0);" onclick="folder('user_group', this);"><?php echo empty($user_group_id) ? '展开' : '收起'; ?></a>
        <div id="search_user_group" <?php if(empty($user_group_id)){ ?>style="display:none;"<?php } ?>>
			<?php echo W('SelectUserGroup', array('user_group_id'=>$user_group_id));?>
		</div>
      </dd>
    </dl>
    
    <dl class="lineD">
      <dt>性别：</dt>
      <dd>
      	<input name="sex" type="radio" value="" <?php if(($sex)  ==  ""): ?>checked<?php endif; ?>>全部
        <input name="sex" type="radio" value="1" <?php if(($sex)  ==  "1"): ?>checked<?php endif; ?>>男
        <input name="sex" type="radio" value="0" <?php if(($sex)  ==  "0"): ?>checked<?php endif; ?>>女
      </dd>
    </dl>
	
	<dl class="lineD">
      <dt>是否激活：</dt>
      <dd>
      	<input name="is_active" type="radio" value="" <?php if(($is_active)  ==  ""): ?>checked<?php endif; ?>>全部
        <input name="is_active" type="radio" value="1" <?php if(($is_active)  ==  "1"): ?>checked<?php endif; ?>>是
        <input name="is_active" type="radio" value="0" <?php if(($is_active)  ==  "0"): ?>checked<?php endif; ?>>否
      </dd>
    </dl>
    <div class="page_btm">
      <input type="submit" class="btn_b" value="确定" />
    </div>
	</form>
  </div>
  </div>
  
  <!-------- 用户列表 -------->
  <div class="Toolbar_inbox">
  	<div class="page right"><?php echo ($html); ?></div>
	<a href="<?php echo U('admin/User/addUser');?>" class="btn_a"><span>添加用户</span></a>
	<a href="javascript:void(0);" class="btn_a" onclick="searchUser();">
		<span class="searchUser_action"><?php if(($type)  !=  "searchUser"): ?>搜索用户<?php else: ?>搜索完毕<?php endif; ?></span>
	</a>
  	<a href="javascript:void(0);" class="btn_a" onclick="changeUserGroup();"><span>转移用户组</span></a>
	<a href="javascript:void(0);" class="btn_a" onclick="deleteUser();"><span>删除用户</span></a>
  </div>
  <div class="list">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th style="width:30px;">
		<input type="checkbox" id="checkbox_handle" onclick="checkAll(this)" value="0">
    	<label for="checkbox"></label>
	</th>
    <th class="line_l" style="80px">ID</th>
    <th class="line_l" style="300px">用户信息</th>
    <?php /*<th class="line_l">部门信息</th>*/ ?>
    <th class="line_l">用户组</th>
    <th class="line_l">注册时间</th>
    <th class="line_l">状态</th>
    <th class="line_l">操作</th>
  </tr>
  <?php if(is_array($data)): ?><?php $i = 0;?><?php $__LIST__ = $data?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><tr overstyle='on' id="user_<?php echo ($vo['uid']); ?>">
	  	<?php if(0 == $vo['admin_level'] && $mid != $vo['uid']): ?><td><input type="checkbox" name="checkbox" id="checkbox2" onclick="checkon(this)" value="<?php echo ($vo["uid"]); ?>"></td>
		<?php else: ?>
	    	<td><input type="checkbox" name="checkbox" id="checkbox2" value="" disabled></td><?php endif; ?>
	    <td><?php echo ($vo["uid"]); ?></td>
	    <td>
			<div style="float:left;margin-right:10px;border:1px solid #8098A8;height:30px;padding:1px;width:30px;"><img src="<?php echo (getUserFace($vo["uid"],'s')); ?>" width="30"></div>
			<div style="float:left"><?php echo getUserSpace($vo["uid"],'fn','_blank','{uname}') ?><br><?php echo ($vo["email"]); ?></div></td>
		<?php /*<td>
			<?php if(empty($vo['department'])){ ?>暂无部门信息<?php } ?>
			<?php if(is_array($vo['department'])): ?><?php $i = 0;?><?php $__LIST__ = $vo['department']?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$dept): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php echo ($dept['dept_title']); ?><br /><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
		</td>*/ ?>
	    <td>
			<?php if(empty($vo['user_group'])){ ?>暂无用户组信息<?php } ?>
			<?php if(is_array($vo['user_group'])): ?><?php $i = 0;?><?php $__LIST__ = $vo['user_group']?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$group): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php echo ($group['user_group_title']); ?><br /><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
		</td>
	    <td><?php echo (date("Y-m-d H:i",$vo["ctime"])); ?></td>
	    <td><?php if(($vo['is_active'])  ==  "0"): ?>未激活<?php else: ?>激活</else><?php endif; ?></td>
	    <td>
			<a href="<?php echo U('admin/User/editUser', array('uid'=>$vo['uid']));?>">编辑</a>
			<?php if(0 == $vo['admin_level'] && $mid != $vo['uid']): ?><a href="javascript:void(0);" onclick="deleteUser(<?php echo ($vo['uid']); ?>);">删除</a>
			<?php else: ?>
				<span>删除</span><?php endif; ?>
		</td>
	  </tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
  </table>
  </div>

  <div class="Toolbar_inbox">
	<div class="page right"><?php echo ($html); ?></div>
	<a href="<?php echo U('admin/User/addUser');?>" class="btn_a"><span>添加用户</span></a>
	<a href="javascript:void(0);" class="btn_a" onclick="searchUser();">
		<span class="searchUser_action"><?php if(($type)  !=  "searchUser"): ?>搜索用户<?php else: ?>搜索完毕<?php endif; ?></span>
	</a>
  	<a href="javascript:void(0);" class="btn_a" onclick="changeUserGroup();"><span>转移用户组</span></a>
	<a href="javascript:void(0);" class="btn_a" onclick="deleteUser();"><span>删除用户</span></a>
  </div>
</div>
<script>
	//鼠标移动表格效果
	$(document).ready(function(){
		$("tr[overstyle='on']").hover(
		  function () {
		    $(this).addClass("bg_hover");
		  },
		  function () {
		    $(this).removeClass("bg_hover");
		  }
		);
	});
	
	function checkon(o){
		if( o.checked == true ){
			$(o).parents('tr').addClass('bg_on') ;
		}else{
			$(o).parents('tr').removeClass('bg_on') ;
		}
	}
	
	function checkAll(o){
		if( o.checked == true ){
			$('input[name="checkbox"]').attr('checked','true');
			$('tr[overstyle="on"]').addClass("bg_on");
		}else{
			$('input[name="checkbox"]').removeAttr('checked');
			$('tr[overstyle="on"]').removeClass("bg_on");
		}
	}

	//获取已选择用户的ID数组
	function getChecked() {
		var uids = new Array();
		$.each($('table input:checked'), function(i, n){
			uids.push( $(n).val() );
		});
		return uids;
	}

	//转移部门
	function changeDepartment(uids) {
		var uids = uids ? uids : getChecked();
		uids = uids.toString();
		if(!uids) {
			ui.error('请先选择用户');
			return false;
		}

		if(!confirm('转移成功后，已选择用户原来的部门信息将被覆盖，确定继续？')) return false;
		
		ui.box.load("<?php echo U('admin/User/changeDepartment');?>&uids="+uids, {title:'转移部门'});
	}
	
	//转移用户组
	function changeUserGroup(uids) {
		var uids = uids ? uids : getChecked();
		uids = uids.toString();
		if(!uids) {
			ui.error('请先选择用户');
			return false;
		}

		if(!confirm('转移成功后，已选择用户原来的用户组信息将被覆盖，确定继续？')) return false;
		
		ui.box.load("<?php echo U('admin/User/changeUserGroup');?>&uids="+uids, {title:'转移用户组'});
	}
	
	//删除用户
	function deleteUser(uid) {
		var length = 0;
		if(uid) {
		    length = 1;         
		}else {
		    uid    = getChecked();
		    length = uid.length;
		    uid    = uid.toString();
		}
		if(uid=='') {
		    ui.error('请先选择一个用户');
		    return ;
		}
		if(uid == '' || !confirm('删除成功后将无法恢复，确认继续？')) return false;
		
		$.post("<?php echo U('admin/User/doDeleteUser');?>", {uid:uid}, function(res){
			if(res == '1') {
				uid = uid.toString().split(',');
				for(i = 0; i < uid.length; i++) {
					$('#user_'+uid[i]).remove();
				}
				ui.success('操作成功');
			}else {
				ui.error('操作失败');
			}
		});
	}
	
	//搜索用户
	var isSearchHidden = <?php if(($type)  !=  "searchUser"): ?>1<?php else: ?>0<?php endif; ?>;
	function searchUser() {
		if(isSearchHidden == 1) {
			$("#searchUser_div").slideDown("fast");
			$(".searchUser_action").html("搜索完毕");
			isSearchHidden = 0;
		}else {
			$("#searchUser_div").slideUp("fast");
			$(".searchUser_action").html("搜索用户");
			isSearchHidden = 1;
		}
	}
	
	function folder(type, _this) {
		$('#search_'+type).slideToggle('fast');
		if ($(_this).html() == '展开') {
			$(_this).html('收起');
		}else {
			$(_this).html('展开');
		}
	}
</script>

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