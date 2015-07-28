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
			<input name="xxid" class="mini-hidden" value="<?php echo $xx['xxid'];?>"/>
			<tr style="height: 30px;">
				<td style="width: 100px">学校名称：</td>
				<td style="width: 140px"><input name="xxmc" class="mini-textbox" value="<?php echo $xx['xxmc'];?>" readonly="readonly" /></td>
				<td style="width: 100px">校区号：</td>
				<td style="width: 140px"><input name="xqh" class="mini-textbox" /></td>
				<td style="width: 100px">教学楼名称：</td>
				<td style="width: 140px"><input name="jxlmc" class="mini-textbox" onvalidation="onCheckMc" required="true" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>教学楼号：</td>
				<td><input name="jxlh" class="mini-textbox" /></td>
				<td>教学楼简介：</td>
				<td><input name="jxljj" class="mini-textbox" /></td>
				<td>教学楼图片：</td>
				<td><input name="jxltp" class="mini-textbox"   /></td>
			</tr>
			<tr style="height: 30px;">
				<td>解释说明：</td>
				<td><input name="remark" class="mini-textbox" /></td>	
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr style="height: 30px;">
				<td
					style="text-align: right; padding-top: 5px; padding-right: 20px;"
					colspan="6"><a class="mini-button"
					href="javascript:onOk()">提交</a> <a class="mini-button"
					href="javascript:onCancel()">取消</a></td>
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
				url : "admin.php?m=teachbuliding&a=upTeachbulidingById&teaid=<?php echo $teaid;?>",
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
				url : "admin.php?m=teachbuliding&a=getTeachbulidingById&teaid="
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
		SaveData();
	}
	function onCancel(e) {
		CloseWindow("cancel");
	}
	
	function onCheckMc(e){
		if(e.value != ''){
			$.post('admin.php?m=teachbuliding&a=checkTeachbulidingByMc&teaid=<?php echo $teaid;?>',
				{jxlmc:e.value},
				function(data){
					if(data == 0){
						alert('教学楼名称已存在');
					}
				});
		}
	}
</script>
</body>
</html>


