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
			<tr style="height: 40px;">
				<td style="width: 60px">服务号：</td>
				<td style="width: 160px"><input style="width:400px" name="serviceID" class="mini-textbox" onvalidation="onCheckFwh" required="true" /></td>
			</tr>
			<tr style="height: 40px;">
				<td>服务类：</td>
				<td><input style="width:400px" name="class" class="mini-textbox" required="true" /></td>
			</tr>
			<tr style="height: 40px;">
				<td>服务方法：</td>
				<td><input style="width:400px" name="method" class="mini-textbox" required="true" /></td>
			</tr>
			<tr style="height: 40px;">
				<td>服务描述：</td>
				<td><input style="width:400px" name="serviceDes" class="mini-textbox" /></td>
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
		var json = mini.encode([ o ]);
		$.ajax({
				url : "admin.php?m=service&a=upServiceById&id=<?php echo $id;?>",
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
				url : "admin.php?m=service&a=getServiceById&id="+ data.id,
				cache : false,
				success : function(text) {
					var o = mini.decode(text);
					form.setData(o);
					form.setChanged(false);
				}
			});
		}
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
	
	function onCheckFwh(e){
		if(e.value != ''){
			$.post('admin.php?m=service&a=checkServiceByServiceid&id=<?php echo $id;?>',
				{serviceID:e.value, time:new Date().getTime()},
				function(data){
					if(data == 0){
						 alert('该服务号已经存在，请重新输入！');
					}
				});
		}
	}
	
</script>
</body>
</html>


