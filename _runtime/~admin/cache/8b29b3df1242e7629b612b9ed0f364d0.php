<?php if (!defined('THINK_PATH')) exit();?>  <!-------- 搜索用户 -------->
  <div id="searchUser_div" <?php if(($type)  !=  "searchUser"): ?>style="display:none;"<?php endif; ?>>
  	<div class="page_tit">搜索用户 [ <a href="javascript:void(0);" onclick="searchUser();">隐藏</a> ]</div>
	
	<div class="form2">
	<form method="post">
	<?php if($type != 'searchUser') $uid = ''; ?>
    <dl class="lineD">
      <dt>用户ID：</dt>
      <dd>
        <input name="uid" id="uid" type="text" value="<?php echo ($uid); ?>">
        <p>用户ID,多个时使用英文的","分割</p>
      </dd>
    </dl>
	
    <dl class="lineD">
      <dt>真实姓名：</dt>
      <dd>
        <input name="realname" id="realname" type="text" value="<?php echo ($realname); ?>">
        <p>支持模糊查询</p>
      </dd>
    </dl>

    <dl class="lineD">
      <dt>手机号：</dt>
      <dd>
        <input name="phone" id="phone" type="text" value="<?php echo ($phone); ?>">
        <p>支持模糊查询</p>
      </dd>
    </dl>

    <dl class="lineD">
      <dt>认证原因：</dt>
      <dd>
        <input name="reason" id="reason" type="text" value="<?php echo ($reason); ?>">
        <p>支持模糊查询</p>
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
	<a href="javascript:void(0);" class="btn_a" onclick="searchUser();">
		<span class="searchUser_action"><?php if(($type)  !=  "searchUser"): ?>搜索用户<?php else: ?>搜索完毕<?php endif; ?></span>
	</a>
	<?php if(($verified)  ==  "0"): ?><a href="javascript:void(0);" class="btn_a" onclick="c.verified()"><span>通过认证</span></a>
		<a href="javascript:void(0);" class="btn_a" onclick="c.del()"><span>驳回认证</span></a>
	<?php else: ?>
		<a href="javascript:void(0);" class="btn_a" onclick="c.del()"><span>取消认证</span></a><?php endif; ?>
  </div>
  <div class="list">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th style="width:30px;">
		<input type="checkbox" id="checkbox_handle" onclick="checkAll(this)" value="0">
    	<label for="checkbox"></label>
	</th>
    <th class="line_l">ID</th>
    <th class="line_l" style="width:160px">用户信息</th>
    <th class="line_l">真实姓名</th>
    <th class="line_l">手机号</th>
    <th class="line_l">认证原因</th>
	<?php if(($verified)  ==  "1"): ?><th class="line_l">认证资料</th><?php endif; ?>
	<th class="line_l">认证附件</th>
    <th class="line_l">状态</th>
    <th class="line_l">操作</th>
  </tr>
  <?php if(is_array($data1)): ?><?php $i = 0;?><?php $__LIST__ = $data1?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><tr overstyle='on' id="user_<?php echo ($vo['uid']); ?>">
	    <td><input type="checkbox" name="checkbox" id="checkbox2" onclick="checkon(this)" value="<?php echo ($vo["uid"]); ?>"></td>
	    <td><?php echo ($vo["uid"]); ?></td>
	    <td>
			<div style="float:left;margin-right:10px;border:1px solid #8098A8;padding:1px;"><?php echo getUserSpace($vo["uid"],'','_blank','{uavatar=s}') ?></div>
			<div style="float:left"><?php echo getUserSpace($vo["uid"],'fn','_blank','{uname}') ?><br><?php echo ($vo["email"]); ?></div></td>
		<td><?php echo ($vo["realname"]); ?></td>
	    <td><?php echo ($vo["phone"]); ?></td>
	    <td><?php echo ($vo["reason"]); ?></td>
	    <?php if(($verified)  ==  "1"): ?><td><?php echo ($vo["info"]); ?></td><?php endif; ?>
	    <td><a href="<?php echo U('home/Attach/download',array('id'=>$vo['attach_id'],'uid'=>$vo['uid']));?>"><?php echo ($vo["attachment"]); ?></a></td>
	    <td><?php if(($vo['verified'])  ==  "0"): ?>待认证<?php else: ?>已认证<?php endif; ?></td>
	    <td>
	    	<?php if(($vo['verified'])  ==  "0"): ?><a href="javascript:void(0)" onclick="c.verified(<?php echo ($vo["uid"]); ?>)">通过</a>
				<a href="javascript:void(0)" onclick="c.del(<?php echo ($vo["uid"]); ?>)">驳回</a>
	    	<?php else: ?>
	    		<a href="<?php echo Addons::adminPage('addVerifiedUser', array('uid'=>$vo['uid']));?>">编辑</a>
	    		<a href="javascript:void(0)" onclick="c.del(<?php echo ($vo["uid"]); ?>)">取消</a><?php endif; ?>
		</td>
	  </tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
  </table>
  </div>
  <div class="Toolbar_inbox">
  	<div class="page right"><?php echo ($html); ?></div>
	<a href="javascript:void(0);" class="btn_a" onclick="searchUser();">
		<span class="searchUser_action"><?php if(($type)  !=  "searchUser"): ?>搜索用户<?php else: ?>搜索完毕<?php endif; ?></span>
	</a>
	<?php if(($verified)  ==  "0"): ?><a href="javascript:void(0);" class="btn_a" onclick="c.verified()"><span>通过认证</span></a>
		<a href="javascript:void(0);" class="btn_a" onclick="c.del()"><span>驳回认证</span></a>
	<?php else: ?>
		<a href="javascript:void(0);" class="btn_a" onclick="c.del()"><span>取消认证</span></a><?php endif; ?>
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
			var val = $(n).val();
			if (val > 0) {
				uids.push( $(n).val() );
			}
		});
		return uids;
	}

	var ctrl = function(){
	}
	ctrl.prototype = {
	    del:function(uid)
	    {
	    	var uid = uid ? uid : getChecked();
	        if(uid == '' || uid ==0){
	        	ui.error("请选择要驳回认证的用户");return false;
	        }
	        ui.box.load( '<?php echo Addons::adminPage('deleteVerifiedTab');?>' + '&uid=' + uid + '&verified=<?php echo $verified; ?>',{title:'取消认证'});
	    },
	    verified:function(id)
	    {
	    	var id = id ? id : getChecked();
	        if(id == '' || id ==0){
	        	ui.error("请选择要通过认证的用户");return false;
	        }
	        ui.box.load( '<?php echo Addons::adminPage('doVerifiedTab');?>' + '&uid=' + id,{title:'请填写认证资料：'});
	    }
	}
	var c = new ctrl();
</script>