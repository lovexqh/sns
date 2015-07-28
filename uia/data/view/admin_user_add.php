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
		<table style="width: 100%;">
			<tr style="height: 30px;">
				<td style="width: 100px;">用户名：</td>
				<td style="width: 140px;"><input name="username"
					class="mini-textbox" onvalidation="onCheckUsername" required="true" /></td>
				<td style="width: 100px;">密码：</td>
				<td style="width: 140px;">
					<input name="password" class="mini-password" onvalidation="onCheckPassword" required="true" />
				</td>
				<td style="width: 100px;">Email：</td>
				<td style="width: 140px;"><input name="email"
					class="mini-textbox" onvalidation="onCheckEmail" required="true" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>真实姓名：</td>
				<td><input name="RealName" class="mini-textbox" onvalidation="onCheckRealname" 
					style="width: 160px;" /></td>
				<td>安全提问：</td>
				<td><input name="rmrecques" class="mini-checkbox" value="1" />清除安全提问</td>
				<td>身份：</td>
				<td><input name="identityType" class="mini-combobox" 
					url="admin.php?m=user&a=getIdentityname" textField="IdentityName"
					valueField="IdentityID" showNullItem="true" /></td>
			</tr>
			<tr>
			<!-- <?php if($uid == null ) { ?> -->
				<td>用户角色：</td>
				<td colspan="5">
					<div class="mini-checkboxlist" repeatItems="7" repeatLayout="table"
   					 textField="RoleName" valueField="RoleID"  onload="onLoad" name="role"
    				 url="admin.php?m=user&a=getRole" >
					</div>
				</td>
			<!-- <?php } ?> -->
			</tr>
			<tr style="height: 30px;">
				<td
					style="text-align: right; padding-top: 5px; padding-right: 20px;"
					colspan="6"><a class="mini-button" href="javascript:onOk()">提交</a>
					<a class="mini-button" href="javascript:onCancel()">取消</a></td>
			</tr>

		</table>
	</div>
</form>
<script type="text/javascript">
	

	var form = new mini.Form("form1");

	function SaveData() {
		var o = form.getData(true);

		form.validate();
		if (form.isValid() == false)
			return;
		//Ajax开始更新的操作
		var json = mini.encode([ o ]);
		$.ajax({
				url : "admin.php?m=user&a=upUserByUid&uid=<?php echo $userid;?>",
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

	////////////////////
	//标准方法接口定义
	function SetData(data) {
		if (data.action == "edit") {
			//跨页面传递的数据对象，克隆后才可以安全使用
			data = mini.clone(data);

			$.ajax({
				url : "admin.php?m=user&a=getUserByUid&userid=<?php echo $uid;?>",
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
		SaveData();
	}
	function onCancel(e) {
		CloseWindow("cancel");
	}
	
	function onCheckUsername(e){
		if(e.value != ''){
			$.post('admin.php?m=user&a=checkUsername',
				{username:e.value,userid:<?php echo $uid;?>},
				function(data){
					if(data == -3){
						 alert('用户名已存在，请重新输入');
					}
					else if(data == 0){
						alert('用户名输入错误，请重新输入');
					}
				});
		}
	}
	
	function onCheckPassword(e){
		if(e.value != ''){
			$.post('admin.php?m=user&a=checkPassword',
					{password:e.value},
					function(data){
						if(data != 1){
							 alert('密码不能为空');
						}
				});
		}
	}
	
	function onCheckEmail(e){
		if(e.value != ''){
			$.post('admin.php?m=user&a=checkEmail&userid=<?php echo $uid;?>',
					{email:e.value},
					function(data){
						if(data == -4){
							 alert('邮箱输入有误，请重新输入');
						}
						else if(data == -5){
							alert('邮箱格式有误，请重新输入');
						}
						else if(data == -6){
							alert('邮箱已存在，请重新输入');
						}
				});
		}
	}
	
	function onCheckRealname(e){
		if(e.value != ''){
			$.post('admin.php?m=user&a=checkRealname',
					{realname:e.value},
					function(data){
						if(data == -7){
							 alert('真实姓名输入有误，请重新输入');
						}
				});
		}
	}
</script>
</body>
</html>


