<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<style>
td {
	text-align: left;
	padding-left: 10px;
}
</style>
<form id="form1" method="post" onsubmit="return chkForm()">
	<div id="editform" class="form">
		<input class="mini-hidden" name="id" value="<?php echo $id;?>" />
		<table style="width: 100%;">
			<tr style="height: 30px;">
				<td>学校：</td>
				<td><input name="schoolid" class="mini-combobox" textField="xxmc" valueField="id" url="admin.php?m=admins&a=getSchool" showNullItem="true" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>管理员帐号：</td>
				<td><input name="username" class="mini-textbox" onvalidation="onCheckUsername" required="true" /></td>
			</tr>
			<?php if($id == 0) { ?>
			<tr style="height: 30px;">	
				<td>密码：</td>
				<td><input name="password" class="mini-password" required="true" /></td>
			</tr>
			<?php } ?>
			<tr style="height: 30px;">
				<td>姓名：</td>
				<td><input name="name" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>联系方式：</td>
				<td><input name="contact" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>注释说明：</td>
				<td><input name="remark" class="mini-textbox" /></td>
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
		var o = form.getData(true);

		form.validate();
		if (form.isValid() == false)
			return;
		//Ajax开始更新的操作
		var json = mini.encode([ o ]);
		$.ajax({
			url : "admin.php?m=admins&a=addNewAdmin",
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

	function SetData(data) {
		if (data.action == "edit") {
			data = mini.clone(data);
			$.ajax({
				url : "admin.php?m=admins&a=getAdminById&id="+ data.id,
				cache : false,
				success : function(text) {
					var o = mini.decode(text);
					form.setData(o);
					form.setChanged(false);

				}
			});
		}
	}
	
	//标准方法接口定义
	function SetDataLimit(data) {
		if (data.action == "edit") {
			//跨页面传递的数据对象，克隆后才可以安全使用
			data = mini.clone(data);

			$.ajax({
				url : "admin.php?m=admins&a=getNewAdminById&id=<?php echo $id;?>"
						+ data.id,
				cache : false,
				success : function(text) {
					var o = mini.decode(text);
					form.setData(o);
					form.setChanged(false);
				}
			});
		}
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
		//chkPwd();
		SaveData();
	}
	function onCancel(e) {
		CloseWindow("cancel");
	}
	
	function onCheckUsername(e){
		if(e.value != ''){
			$.post('admin.php?m=admins&a=checkAdminByUsername&id=<?php echo $id;?>',
				{username:e.value,time:new Date().getTime() },			////////////////////////
				function(data){
					if(data == 0){
						alert('您输入的管理员帐号已经存在，请重新输入');
					}
				});
		}
	}
	/*
	function chkPwd(){
		var pwd1 = getValue("pwd1");
		var pwd2 = getValue("pwd2");
		if(pwd1 != pwd2){
			alert("两次输入的密码不一致，请重新输入！");
			return false;
		}
		return true;
	}
	*/
</script>
</body>
</html>

