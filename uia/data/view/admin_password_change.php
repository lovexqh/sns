<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<style>
td {
	text-align: left;
	padding-left: 10px;
	line_height:10px;
}
</style>
<form id="form1" method="post">
	<div id="editform" class="form">
		<table style="width: 100%;">
			<tr style="height: 40px;">
				<td>原密码：</td>
				<td><input id="oldpwd" name="oldpwd" class="mini-password" required="true" /></td>
			</tr>
			<tr style="height: 40px;">
				<td>新密码：</td>
				<td><input id="newpwd" name="newpwd" class="mini-password" required="true" /></td>
			</tr>
			<tr style="height: 40px;">
				<td>重复新密码：</td>
				<td><input id="newpwd2" name="newpwd2" class="mini-password" required="true" /></td>
			</tr>
			<tr style="height: 40px;">
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
			url : "admin.php?m=frame&a=chgPwd&username=<?php echo $username;?>",
			type: 'POST',
			data : {
				data : json
			},
			cache : false,
			success : function(text) {
				if(text==0){
					alert("原密码错误！");
				}else{
					alert("密码修改成功！");
					CloseWindow("save");
				}
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
		var newpwd = mini.get("newpwd").getValue();
		var newpwd2 = mini.get("newpwd2").getValue();
		if(newpwd != newpwd2){
			alert("两次输入的新密码不一致！");
		}else{
			SaveData();
		}
		
	}
	function onCancel(e) {
		CloseWindow("cancel");
	}

</script>

<?php include $this->gettpl('footer');?>
