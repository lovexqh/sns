<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<style>
td {
	text-align: left;
	padding-left: 10px;
}
</style>
<form id="form1" method="post">
	<div id="editform" class="form">
		<input name="schoolid" class="mini-hidden" value='<?php echo $schoolid;?>'/>
		<input name="updeptid" class="mini-hidden" value='<?php echo $updeptid;?>'>
		<table>
		<tr>
			<td>部门名：</td>
			<td><input id="DepartName" name="DepartName"
				class="mini-textbox" style="width:450px;" onvalidation="onCheckName" required="true" />	
		</tr>
		<tr>
			<td>部门负责人：</td>
			<td><input id="DeptManager" style="width:450px;" name="DeptManager" class="mini-textbox" /></td>
		</tr>
		<tr>
			<td>部门联系电话：</td>
			<td><input id="DeptPhone" style="width:450px;" name="DeptPhone" class="mini-textbox" /></td>
		</tr>
		<tr>
			<td>部门简介：</td>
			<td><input id="DeptComment"  name="DeptComment" class="mini-textarea" style="width:450px;" /></td>
		</tr>
		<tr style="height: 30px;">
			<td style="text-align: right; padding-top: 5px; padding-right: 20px;" colspan="6">
				<a class="mini-button" href="javascript:onOk()">提交</a>
				<a class="mini-button" href="javascript:onCancel()">取消</a>
			</td>
		</tr>
	</table>
	</div>
</form>
<script type="text/javascript">

	mini.parse();

	var form = new mini.Form("form1");

	function SaveData() {
		var form = new mini.Form("form1");
		var o = form.getData(true);

		form.validate();
		if (form.isValid() == false)
			return;
		//Ajax开始更新的操作
		var json = mini.encode([ o ]);
		$.ajax({
				url : "admin.php?m=dept&a=doAddDept",
				type: 'POST',
				data : {
					data : json
				},
				cache : false,
				success : function(text) {
					CloseWindow("save");
				},
				error : function(jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);
					CloseWindow();
				}
			});
		
	}

	function GetData() {
		var o = form.getData();
		return o;
	}
	
	function CloseWindow(action) {
		if (action == "close" && form.isChanged()) {
			if (confirm("数据被修改了，是否先保存？")) {
				return false;
			}
		}
		if (window.CloseOwnerWindow)
			return window.CloseOwnerWindow(action);
		else
			window.close();
	}
	function onOk(e) {
		SaveData();
	}
	
	function onCancel(e) {
		CloseWindow("cancel");
	}
	
	function onCheckName(e){
		if(e.value != ''){
			$.post('admin.php?m=dept&a=checkName&schoolid=<?php echo $schoolid;?>',
				{deptname:e.value},
				function(data){
					if(data == 0){
						alert('部门名称已存在');
					}
				});
		}
	}
</script>
</body>
</html>