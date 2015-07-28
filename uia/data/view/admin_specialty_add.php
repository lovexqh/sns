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
		<input class="mini-hidden" name="id" value="<?php echo $id;?>" />
		<input class="mini-hidden" name="xxid" value="<?php echo $xxid;?>" />
		<input class="mini-hidden" name="yxid" value="<?php echo $yxid;?>" />
		<table style="width: 100%;">
			<tr style="height: 30px;">
				<td style="width: 100px">院系：</td>
				<td style="width: 140px"><input class="mini-textbox" value="<?php echo $yxmc;?>" readonly="readonly" /></td>
				<td style="width: 100px">专业号：</td>
				<td style="width: 140px"><input name="zyh" class="mini-textbox" onvalidation="onCheckZyh" required="true" /></td>
				<td style="width: 100px">专业名称：</td>
				<td style="width: 140px"><input name="zymc" class="mini-textbox" onvalidation="onCheckZymc" required="true" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>专业简称：</td>
				<td><input name="zyjc" class="mini-textbox" /></td>
				<td>校级专业号：</td>
				<td><input name="xjzyh" class="mini-textbox" /></td>
				<td>校级专业名：</td>
				<td><input name="xjzym" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>专业英文名称：</td>
				<td><input name="zyywmc" class="mini-textbox" /></td>	
				<td>学制：</td>
				<td><input name="xz" class="mini-textbox" /></td>
				<td>学科门类：</td>
				<td><input name="xkmlm" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>专业层次：</td>
				<td><input type="comboboxcolumn" name="zyccm" class="mini-combobox" data="zycc" showNullItem="true" /></td>
				<td>建立年月：</td>
				<td><input name="jlny" class="mini-datepicker" dateFormat="yyyyMMdd" /></td>
				<td>专业简介：</td>
				<td><input name="zyjj" class="mini-textbox" /></td>
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
var zycc = [ {
	id : 1,
	text : '研究生'
}, {
	id : 2,
	text : '本科'
}, {
	id : 3,
	text : '专科'
} ];

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
			url : "admin.php?m=specialty&a=upSpecialtyById&id=<?php echo $id;?>",
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
			url : "admin.php?m=specialty&a=addSpecialty",
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
			url : "admin.php?m=specialty&a=getSpecialtyById&id=<?php echo $id;?>",
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

function onCheckZyh(e){
	if(e.value != ''){
		$.post('admin.php?m=specialty&a=checkSpecialtyByZyh',
			{zyh:e.value,time:new Date().getTime() },
			function(data){
				if(data == 0){
					alert('你输入的专业号已经存在，请重新输出');
				}
			});
	}
}

function onCheckZymc(e){
	if(e.value != ''){
		$.post('admin.php?m=specialty&a=checkSpecialtyByZymc',
			{zymc:e.value, time:new Date().getTime()},
			function(data){
				if(data == 0){
					alert('你输入的专业名称已经存在，请重新输出');
				}
			});
	}
}
</script>

<?php include $this->gettpl('footer');?>
