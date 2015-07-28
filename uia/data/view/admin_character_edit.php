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

				<td style="width: 100px;">数据类型ID：</td>
				<td><input name="dataid" required="true" class="mini-textbox"  style="width: 160px;" /></td>
				<td style="width: 100px;">数据类型中文描述：</td>
				<td><input name="datacn" required="true" class="mini-textbox"  style="width: 160px;" /></td>

			</tr>
			<tr style="height: 30px;">
				<td>数据类型英文描述：</td>
				<td><input name="dataen" required="true" class="mini-textbox" style="width: 160px;" /></td>
				<td>datastandard：</td>
				<td><input name="datastandard" class="mini-textbox" style="width: 160px;" /></td>

			</tr>
			<tr style="height: 30px;">
				<td>datastandard：</td>
				<td><input name="datastandard" class="mini-textbox" style="width: 160px;" /></td>
				<td>备注：</td>
				<td><input name="remark" class="mini-textbox" style="width: 160px;" /></td>

			</tr>

			<tr>
			<!-- <?php if($uid == null ) { ?> -->

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


	mini.parse();

	var form = new mini.Form("form1");


	function SaveData() {
		var o = form.getData(true);

		form.validate();
		if (form.isValid() == false)
			return;
		//Ajax开始更新的操作
		var json = mini.encode([o]);
		console.log(json);
		$.ajax({
				url : "admin.php?m=character&a=uptypeBydataid&dataid=<?php echo $dataid;?>",
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
	//alert("a");
		if (data.action == "edit") {
			//跨页面传递的数据对象，克隆后才可以安全使用
			data = mini.clone(data);
			//alert("a");
			$.ajax({
				url : "admin.php?m=character&a=gettypeBydataid&dataid=<?php echo $dataid;?>",
				cache : false,
				success : function(text) {
					//console.log(text);
					var o = mini.decode(text);
					//console.log(o[0]);
					form.setData(o[0]);
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





</script>
</body>
</html>

