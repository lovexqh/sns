<?php if (!defined('THINK_PATH')) exit();?><div>
将 <?php echo ($unames); ?> 转移至: 
</div>
<?php echo W('SelectUserGroup');?>
<div class="page_btm">
	<input type="button" class="btn_b" onclick="doChangeUserGroup(this)" value="确定" />
	<input type="button" class="btn_w" onclick="cancel(this)" value="取消" />
</div>

<script type="text/javascript">
function doChangeUserGroup(_this) {
	var gid = $('#user_group_id').val();
	var uid = "<?php echo ($uids); ?>";
	if(gid == '') {
		ui.error('请至少选择一个部门');
		return false;
	}

	if(!confirm('转移成功后，已选择用户原来的用户组信息将被覆盖，确定继续？')) return false;

	//提交修改
	$.post("<?php echo U('admin/User/doChangeUserGroup');?>", {uid:uid, gid:gid}, function(res){
		if(res == '1') {
			ui.box.close();
			ui.success('保存成功');
		}else {
			ui.box.close();
			ui.error('保存失败，请重试');
		}
	});
}

function cancel(_this) {
	ui.box.close();
}
</script>