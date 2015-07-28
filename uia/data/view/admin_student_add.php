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
		<input class="mini-hidden" name="identityid" value="<?php echo $identityid;?>" />
		<input class="mini-hidden" name="schoolid" value="<?php echo $schoolid;?>" />
		<input class="mini-hidden" name="yxid" value="<?php echo $yxid;?>" />
		<input class="mini-hidden" name="zyid" value="<?php echo $zyid;?>" />
		<input class="mini-hidden" name="bjid" value="<?php echo $classid;?>" />
		<table style="width: 100%;">
			<tr>
				<td>院系</td>
				<td><input class="mini-textbox" value="<?php echo $yxmc;?>" readonly="readonly" /></td>
				<td>专业</td>
				<td><input class="mini-textbox" value="<?php echo $zymc;?>" readonly="readonly" /></td>
				<td>班级</td>
				<td><input id="btnEdit1" name="chgclass" class="mini-buttonedit"  text="<?php echo $bm;?>" onbuttonclick="onButtonEdit" allowInput="false" /></td>
			</tr>
			<tr style="height: 30px;">
				<td style="width: 100px">学号：</td>
				<td style="width: 140px"><input name="xh" class="mini-textbox" onvalidation="onCheckXh" required="true" /></td>
				<td style="width: 100px">姓名：</td>
				<td style="width: 140px"><input name="xm" class="mini-textbox" required="true" /></td>
				<td style="width: 100px">性别：</td>
				<td style="width: 140px"><input type="comboboxcolumn" name="xbm" class="mini-combobox" data="Genders" showNullItem="true" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>身份证件号：</td>
				<td><input name="sfzjh" class="mini-textbox" required="true" /></td>
				<td>高考考生号：</td>
				<td><input name="gkksh" class="mini-textbox" /></td>
				<td>录取号：</td>
				<td><input name="lqh" class="mini-textbox" /></td>	
			</tr>
			<tr style="height: 30px;">
				<td>出生日期：</td>
				<td><input name="csrq" class="mini-datepicker" dateFormat="yyyyMMdd" /></td>
				<td>民族：</td>
				<td><input name="mz" class="mini-textbox" /></td>
				<td>政治面貌：</td>
				<td><input name="zzmm" class="mini-combobox" textField="itemcn" valueField="itemcn" url="admin.php?m=student&a=showFromDB" showNullItem="true" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>培养层次：</td>
				<td><input name="pyccjd" class="mini-combobox" data="pyccjd" showNullItem="true" /></td>
				<td>联系电话：</td>
				<td><input name="lxdh" class="mini-textbox" /></td>
				<td>通信地址：</td>
				<td><input name="txdz" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>电子信箱：</td>
				<td><input name="dzxx" class="mini-textbox" /></td>
				<td>学籍号：</td>
				<td><input name="xjh" class="mini-textbox" /></td>
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
var Genders = [ {
	id : 1,
	text : '男'
}, {
	id : 2,
	text : '女'
} ];

var pyccjd = [ {
	id : '研究生',
	text : '研究生'
}, {
	id : '本科',
	text : '本科'
}, {
	id : '专科',
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
	if(<?php echo $identityid;?>){		
		$.ajax({
			url : "admin.php?m=student&a=upStudentByIdentityid&identityid=<?php echo $identityid;?>",
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
			url : "admin.php?m=student&a=addStudent",
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
			url : "admin.php?m=student&a=getStudentByIdentityid&identityid=<?php echo $identityid;?>"
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

function onCheckSfzjh(e){
	if(e.value != ''){
		$.post('admin.php?m=student&a=checkStudentBySfzjh',
			{sfzjh:e.value,time:new Date().getTime() },
			function(data){
				if(data == 0){
					alert('你输入的身份证号已经存在，请重新输出');
				}
			});
	}
}

function onCheckXh(e){
	if(e.value != ''){
		$.post('admin.php?m=student&a=checkStudentByXh',
			{xh:e.value, time:new Date().getTime()},
			function(data){
				if(data == 0){
					alert('你输入的学号已经存在，请重新输出');
				}
			});
	}
}

function onButtonEdit(e) {
	var btnEdit = this;
		mini.open({
        url: "admin.php?m=student&a=select_tree&schoolid=<?php echo $schoolid;?>",
        showMaxButton: false,
        title: "选择班级",
        width: 400,
        height: 350,
        ondestroy: function (action) {
            if (action == "ok") {
                var iframe = this.getIFrameEl();
                var data = iframe.contentWindow.GetData();
                data = mini.clone(data);
                if (data) {
                    btnEdit.setValue(data.id);
                    btnEdit.setText(data.text);
                }
            }
        }
});

}
</script>

<?php include $this->gettpl('footer');?>
