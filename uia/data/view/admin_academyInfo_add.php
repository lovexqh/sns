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
		<input class="mini-hidden" name="xxid" value="<?php echo $school['id'];?>" />
		<table style="width: 100%;">
			<tr style="height: 30px;">
				<td style="width: 100px">学校名称：</td>
				<td style="width: 140px"><input class="mini-textbox" name="xxmc" value="<?php echo $school['xxmc'];?>" readonly="readonly" /></td>
				<td style="width: 100px">院系名称：</td>
				<td style="width: 140px"><input name="yxmc" class="mini-textbox" onvalidation="onCheckYm" required="true" /></td>
				<td style="width: 100px">院系编码：</td>
				<td style="width: 140px"><input name="yxbm" class="mini-textbox" onvalidation="onCheckYxbm" required="true" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>院系英文名称：</td>
				<td><input name="yxywmc" class="mini-textbox" /></td>
				<td>院系简称：</td>
				<td><input name="yxjc" class="mini-textbox" /></td>
				<td>院系英文简称：</td>
				<td><input name="yxywjc" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>院系简拼：</td>
				<td><input name="yxjp" class="mini-textbox" /></td>	
				<td>院系地址：</td>
				<td><input name="yxdz" class="mini-textbox" /></td>
				<td>隶属单位号：</td>
				<td><input name="lsdwh" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>建立年月：</td>
				<td><input name="jlny" class="mini-datepicker" dateFormat="yyyyMMdd" /></td>
				<td>院系联系人：</td>
				<td><input name="yxlxr" class="mini-textbox" /></td>
				<td>院系联系电话：</td>
				<td><input name="yxlxdh" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>院系类别：</td>
				<td><input name="yxlbm" class="mini-textbox"  /></td>
				<td>院系简介：</td>
				<td><input name="yxjj" class="mini-textbox" /></td>
				<td>注释说明</td>
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
		$.ajax({
			url : "admin.php?m=college&a=upCollegeById&yxid=<?php echo $yxid;?>",
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

//标准方法接口定义
function SetData(data) {
	if (data.action == "edit") {
		//跨页面传递的数据对象，克隆后才可以安全使用
		data = mini.clone(data);

		$.ajax({
			url : "admin.php?m=college&a=getCollegeById&id=" + data.id,
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

function onCheckYm(e){
	if(e.value != ''){
		$.post('admin.php?m=college&a=checkCollegeByYxmc&schoolid=<?php echo $school['id'];?>',
			{yxmc:e.value,yxid:<?php echo $yxid;?>},
			function(data){
				if(data == 0){
					alert('院系名称已经存在，请重新输入');
				}
			});
	}
}

function onCheckYxbm(e){
	if(e.value != ''){
		$.post('admin.php?m=college&a=checkCollegeByYxbm&schoolid=<?php echo $school['id'];?>',
			{yxbm:e.value,yxid:<?php echo $yxid;?>},
			function(data){
				if(data == 0){
					alert('院系编码已经存在，请重新输入');
				}
			});
	}
}
</script>

<?php include $this->gettpl('footer');?>