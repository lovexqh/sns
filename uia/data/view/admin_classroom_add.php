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
		<input class="mini-hidden" name="jxlid" value="<?php echo $jxlid;?>" />
		<table style="width: 100%;">
			<tr style="height: 30px;">
				<td style="width: 100px">教学楼：</td>
				<td style="width: 140px"><input name="jxlm" class="mini-textbox" value="<?php echo $jxlmc;?>" readonly="readonly" /></td>
				<td style="width: 100px">教室号：</td>
				<td style="width: 140px"><input name="jsh" class="mini-textbox" onvalidation="onCheckJsh" required="true" /></td>
				<td style="width: 100px">教室名：</td>
				<td style="width: 140px"><input name="jsm" class="mini-textbox" onvalidation="onCheckJsm" required="true" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>所在楼层：</td>
				<td><input name="szlc" class="mini-textbox" /></td>
				<td>教室用途：</td>
				<td><input name="jsyt" class="mini-textbox" /></td>
				<td>座位数：</td>
				<td><input name="zws" class="mini-spinner" minValue="0" maxValue="300" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>有效座位数：</td>
				<td><input name="yxzws" class="mini-spinner" minValue="0" maxValue="300" /></td>	
				<td>考试座位数：</td>
				<td><input name="kszws" class="mini-spinner" minValue="0" maxValue="300" /></td>
				<td>教室类型：</td>
				<td><input name="jslx" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>教室描述：</td>
				<td><input name="jsms" class="mini-textbox" /></td>
				<td>教室管理部门：</td>
				<td><input name="jsglbm" class="mini-textbox" /></td>
				<td>学年度：</td>
				<td><input name="xnd" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>开课学期：</td>
				<td><input name="kkxq" class="mini-textbox" /></td>
				<td>匹配教室类型：</td>
				<td><input name="ppjslx" class="mini-textbox" /></td>
				<td>满足教室类型：</td>
				<td><input name="mzjslx" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>优先级：</td>
				<td><input name="yxj" class="mini-textbox" /></td>
				<td>备注：</td>
				<td><input name="bz" class="mini-textbox" /></td>
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
	if(<?php echo $id;?>){		
		$.ajax({
			url : "admin.php?m=classroom&a=upClassroomById&id=<?php echo $id;?>",
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
			url : "admin.php?m=classroom&a=addClassroom",
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
			url : "admin.php?m=classroom&a=getClassroomById&id=<?php echo $id;?>",
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

function onCheckJsh(e){
	if(e.value != ''){
		$.post('admin.php?m=classroom&a=checkClassroomByJsh',
			{jsh:e.value,time:new Date().getTime() },
			function(data){
				if(data == 0){
					alert('你输入的教室号已经存在，请重新输出');
				}
			});
	}
}

function onCheckJsm(e){
	if(e.value != ''){
		$.post('admin.php?m=classroom&a=checkClassroomByJsm',
			{jsm:e.value, time:new Date().getTime()},
			function(data){
				if(data == 0){
					alert('你输入的教室名在该教学楼已经存在，请重新输出');
				}
			});
	}
}
</script>

<?php include $this->gettpl('footer');?>