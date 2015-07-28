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
		<input class="mini-hidden" name="app_id" value="<?php echo $id;?>" />
		<input class="mini-hidden" name="last_update_date" value="<?php echo $date;?>" />
		<table style="width: 100%;">
			<tr style="height: 30px;">
				<td style="width: 100px">应用名：</td>
				<td style="width: 140px"><input name="app_name" class="mini-textbox" onvalidation="onCheckName" required="true" /></td>
				<td style="width: 100px">应用别名：</td>
				<td style="width: 140px"><input name="app_alias" class="mini-textbox" onvalidation="onCheckAlias" required="true" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>描述：</td>
				<td colspan="3"><input name="description" class="mini-textbox" width="428" /></td>
			</tr>
			<tr style="height: 30px;">
				<td style="text-align: right; padding-top: 5px; padding-right: 20px;" colspan="4">
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
	if(<?php echo $id;?>){		
		$.ajax({
			url : "admin.php?m=application&a=upAppById&app_id=<?php echo $id;?>",
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
	}else{
		$.ajax({
			url : "admin.php?m=application&a=addApp",
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
	
}

//标准方法接口定义
function SetData(data) {
	if (data.action == "edit") {
		//跨页面传递的数据对象，克隆后才可以安全使用
		data = mini.clone(data);

		$.ajax({
			url : "admin.php?m=application&a=getAppById&app_id=<?php echo $id;?>",
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

function onCheckName(e){
	if(e.value != ''){
		$.post('admin.php?m=application&a=checkAppByName',
			{app_name:e.value,time:new Date().getTime() },
			function(data){
				if(data == 0){
					alert('你输入的应用名已经存在，请重新输出');
				}
			});
	}
}

function onCheckAlias(e){
	if(e.value != ''){
		$.post('admin.php?m=application&a=checkAppByAlias',
			{app_alias:e.value, time:new Date().getTime()},
			function(data){
				if(data == 0){
					alert('你输入的应用别名已经存在，请重新输出');
				}
			});
	}
}
</script>

<?php include $this->gettpl('footer');?>
