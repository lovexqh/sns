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
		<input class="mini-hidden" name="xxid" value="<?php echo $xyz['xxid'];?>" />
		<input class="mini-hidden" name="yxid" value="<?php echo $xyz['yxid'];?>" />
		<input class="mini-hidden" name="zyid" value="<?php echo $xyz['zyid'];?>" />
		<input class="mini-hidden" name="yxbm" value="<?php echo $xyz['yxbm'];?>" />
		<input class="mini-hidden" name="zyh" value="<?php echo $xyz['zyh'];?>" />
		<table style="width: 100%;">
			<tr style="height: 30px;">
				<td style="width: 100px;">学校名称：</td>
				<td style="width: 140px;"><input name="xxmc"
					class="mini-textbox" value="<?php echo $xyz['xxmc'];?>" readonly /></td>
				<td style="width: 100px;">院系名称：</td>
				<td style="width: 140px;"><input name="yxmc" 
					class="mini-textbox" value="<?php echo $xyz['yxmc'];?>" readonly /></td>
				<td style="width: 100px;">专业名称：</td>
				<td style="width: 140px;"><input name="zymc"
					class="mini-textbox" value="<?php echo $xyz['zymc'];?>" readonly /></td>
			</tr>
			<tr style="height: 30px;">
				<td>班级名称：</td>
				<td><input name="bm" class="mini-textbox" 
					 onvalidation="onCheckBm" required="true"/></td>
				<td>班号：</td>
				<td><input name="bh" class="mini-textbox" id="bh" /></td>
				<td>班级类型：</td>
				<td><input name="bjlxm" class="mini-combobox" data="Type"  showNullItem="true"/></td>
			</tr>
			<tr style="height: 30px;">
				<td>学制：</td>
				<td><input name="xz" class="mini-combobox" data="XueZhi" showNullItem="true" /></td>
				<td>建班年月：</td>
				<td><input name="jbny" class="mini-datepicker" dateFormat="yyyyMM" /></td>
				<td>英文班名：</td>
				<td><input name="ywbm" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>班长学号：</td>
				<td><input name="bzxh" class="mini-textbox" /></td>
				<td>班主任工号：</td>
				<td><input name="bzrgh" class="mini-textbox" /></td>
				<td>辅导员：</td>
				<td><input name="fdyid" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>校区：</td>
				<td><input name="xqdm" class="mini-textbox" data="Genders" /></td>
				<td>是否定单班：</td>
				<td><input name="sfddb" class="mini-combobox" data="Genders" showNullItem="true" /></td>
				<td></td>
				<td></td>
			</tr>
			<tr style="height: 30px;">
				<td>注释说明：</td>
				<td><input name="remark" class="mini-textbox" data="Genders" /></td>
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
	var Genders = [ {
		id : 1,
		text : '是'
	}, {
		id : 2,
		text : '否'
	} ];
	var XueZhi = [ {
		id : 4,
		text : '四年'
	}, {
		id : 6,
		text : '六年'
	} ];
	var Type = [ {
		id : '01',
		text : '本科'
	}, {
		id : '02',
		text : '研究生'
	}, {
		id : '03',
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
				url : "admin.php?m=class&a=upClassById&id=<?php echo $classid;?>",
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
				url : "admin.php?m=class&a=getClassById&id="
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
	
	function onCheckBm(e){
		if(e.value != ''){
			$.post('admin.php?m=class&a=checkClassByBm&classid=<?php echo $classid;?>',
				{bm:e.value, time:new Date().getTime()},
				function(data){
					if(data == 0){
						alert('班级名称已存在');
					}
				});
		}
	}
</script>
</body>
</html>


