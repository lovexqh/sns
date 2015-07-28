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
				<td style="width: 100px;">学校代码：</td>
				<td style="width: 140px;"><input name="xxdm"
					class="mini-textbox" onvalidation="onCheckXxdm" required="true" /></td>
				<td style="width: 100px;">学校名称：</td>
				<td style="width: 140px;">
					<input name="xxmc" class="mini-textbox" onvalidation="onCheckXxmc" required="true" />
				</td>
				<td style="width: 100px;">学校英文名称：</td>
				<td style="width: 140px;"><input name="xxywmc"
					class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>学校地址：</td>
				<td><input name="xxdz" class="mini-textbox" /></td>
				<td>学校邮政编码：</td>
				<td><input name="xxyzbm" class="mini-textbox" /></td>
				<td>行政区划：</td>
				<td><input name="xzqhm" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>建校年月日：</td>
				<td><input name="jxnyr" class="mini-datepicker"
					dateFormat="yyyyMMdd" /></td>
				<td>学校性质：</td>
				<td><input name="xxxzm" class="mini-combobox"
					url="admin.php?m=school&a=getXxxzm" textField="itemcn"
					valueField="itemcode" showNullItem="true" /></td>
				<td>学校办学类型：</td>
				<td><input name="xxbxlxm" class="mini-combobox"
					url="admin.php?m=school&a=getXxbxlxm" textField="itemcn"
					valueField="itemcode" showNullItem="true" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>学校举办者：</td>
				<td><input name="xxjbzm" class="mini-combobox"
					url="admin.php?m=school&a=getXxjbzm" textField="itemcn"
					valueField="itemcode" showNullItem="true" /></td>
				<td>学校主管部门：</td>
				<td><input name="xxzgbmm" class="mini-textbox" /></td>
				<td>法定代表人：</td>
				<td><input name="fddbrh" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>法人证书：</td>
				<td><input name="frzsh" class="mini-textbox" /></td>
				<td>校长工号：</td>
				<td><input name="xzgh" class="mini-textbox" /></td>
				<td>校长姓名：</td>
				<td><input name="xzxm" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>党委负责人：</td>
				<td><input name="dwfzrh" class="mini-textbox" /></td>
				<td>组织机构码：</td>
				<td><input name="zzjgm" class="mini-textbox" /></td>
				<td>联系电话：</td>
				<td><input name="lxdh" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>传真电话：</td>
				<td><input name="czdh" class="mini-textbox" /></td>
				<td>电子信箱：</td>
				<td><input name="dzxx" class="mini-textbox" /></td>
				<td>主页地址：</td>
				<td><input name="zydz" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>历史沿革：</td>
				<td><input name="lsyg" class="mini-textbox" /></td>
				<td>学校211工程状况：</td>
				<td><input name="xx211gczk" class="mini-textbox" /></td>
				<td>985工程院校状况：</td>
				<td><input name="985gcyxzk" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>重点学校状况：</td>
				<td><input name="zdxxzk" class="mini-textbox" /></td>
				<td>研究生院状况：</td>
				<td><input name="yjsyzk" class="mini-textbox" /></td>
				<td>举办网络教育状况：</td>
				<td><input name="jbwljyzk" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>举办成人教育状况：</td>
				<td><input name="jbcrjyzk" class="mini-textbox" /></td>
				<td>学科门类数：</td>
				<td><input name="xkmls" class="mini-textbox" /></td>
				<td>国家示范性高职院校状况：</td>
				<td><input name="gjsfxgzyxzk" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>注释说明：</td>
				<td><input name="remark" class="mini-textbox" /></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
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
	

	var form = new mini.Form("form1");

	function SaveData() {
		var o = form.getData(true);

		form.validate();
		if (form.isValid() == false)
			return;
		//Ajax开始更新的操作
		var json = mini.encode([ o ]);
		$.ajax({
				url : "admin.php?m=school&a=upSchoolById&schoolid=<?php echo $schoolid;?>",
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
				url : "admin.php?m=school&a=getSchoolBySchoolid&schoolid="
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
	
	function onCheckXxdm(e){
		if(e.value != ''){
			$.post('admin.php?m=school&a=checkSchoolByXxdm&schoolid=<?php echo $schoolid;?>',
				{xxdm:e.value, time:new Date().getTime()},
				function(data){
					if(data == 0){
						 alert('学校代码已经存在，请重新输入');
					}
				});
		}
	}
	
	function onCheckXxmc(e){
		if(e.value != ''){
			$.post('admin.php?m=school&a=checkSchoolByXxmc&schoolid=<?php echo $schoolid;?>',
				{xxmc:e.value, time:new Date().getTime()},
				function(data){
					if(data == 0){
						 alert('学校名称已经存在，请重新输入');
					}
				});
		}
	}
</script>
</body>
</html>


