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
		<!-- <input class="mini-hidden" name="depid" value="<?php echo $depid;?>" /> -->
		<table style="width: 100%;">
			<tr style="height: 30px;">
				<td style="width: 80px;">职工编号：</td>
				<td style="width: 140px;"><input name="zgh" id="zgh" class="mini-textbox" onvalidation="onCheckZgh" /></td>
				<td style="width: 80px;">教师编号：</td>
				<td style="width: 140px;"><input name="jsh" class="mini-textbox"  /></td>
				<td>所在部门：</td>
				<td><input id="btnEdit1" name="depid" class="mini-buttonedit" value="<?php echo $depid;?>"  text="<?php echo $depName;?>" onbuttonclick="onButtonEdit" allowInput="false" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>老师姓名：</td>
				<td><input name="xm" class="mini-textbox" required="true" onvalidation="onCheckXm" /></td>
				<td>身份证号：</td>
				<td><input name="sfzjh" class="mini-textbox" id="sfzjh" style="width: 180px;" required="true" /></td>
				<td>性别：</td>
				<td><input type="comboboxcolumn" name="xbm" class="mini-combobox" data="Genders" showNullItem="true" /></td>
			</tr>
			<tr style="height: 30px;">	
				<td>出生日期：</td>
				<td><input name="csrq" class="mini-datepicker" dateFormat="yyyyMMdd"  /></td>
				<td>民族：</td>
				<td><input name="mz" class="mini-combobox" textField="itemcn" valueField="itemcn" url="admin.php?m=teacher&a=getMz" showNullItem="true" /></td>
				<td>参加工作时间：</td>
				<td><input name="cjgzsj" class="mini-datepicker" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>最高学历：</td>
				<td><input name="zgxl" class="mini-textbox" onvalidation="onCheckZgxl" /></td>
				<td>最高学位：</td>
				<td><input name="zgxw" class="mini-textbox" onvalidation="onCheckZgxw" /></td>
				<td>教职工类别：</td>
				<td><input name="jzglb" class="mini-textbox" onvalidation="onCheckJzglb" /></td>
			</tr>
			<tr style="height: 30px;">	
				<td>职务：</td>
				<td><input name="zw" class="mini-textbox" onvalidation="onCheckZw" /></td>
				<td>老师职称：</td>
				<td><input name="zc" class="mini-textbox" onvalidation="onCheckZc" /></td>
				<td>专业技术资格：</td>
				<td><input name="zyjszg" class="mini-textbox" onvalidation="onCheckZyjszg" /></td>
			</tr>
			<tr style="height: 30px;">
				<td>专业技术级别：</td>
				<td><input name="zyjszwjb" class="mini-textbox" onvalidation="onCheckZyjszwjb" /></td>
				<td>当前状态：</td>
				<td><input name="dqzt" class="mini-textbox" onvalidation="onCheckDqzt" /></td>
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
		text : '男'
	}, {
		id : 2,
		text : '女'
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
				url : "admin.php?m=teacher&a=upTeacherByIdentityid&identityid=<?php echo $identityid;?>",
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
				url : "admin.php?m=teacher&a=addTeacher",
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

	////////////////////
	//标准方法接口定义
	function SetData(data) {
		if (data.action == "edit") {
			//跨页面传递的数据对象，克隆后才可以安全使用
			data = mini.clone(data);

			$.ajax({
				url : "admin.php?m=teacher&a=getTeacherByIdentityid&identityid=<?php echo $identityid;?>"
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
			if(e.value.length != 15 && e.value.length != 18){
				alert("身份证号为15位或18位");
				return false;
			}
			$.post('admin.php?m=teacher&a=checkTeacherBySfzjh',
				{sfzjh:e.value,time:new Date().getTime() },
				function(data){
					if(data == 0){
						alert('你输入的身份证号已经存在，请重新输出');
					}
				});
		}
	}
	
	function onCheckZgh(e){
		if(e.value != ''){
			if(e.value.length > 20 ){
				alert("职工编号 不能超过20位");
				return false;
			}
			$.post('admin.php?m=teacher&a=checkTeacherByZgh',
				{zgh:e.value, time:new Date().getTime()},
				function(data){
					if(data == 0){
						alert('你输入的职工号已经存在，请重新输出');
					}
				});
		}
	}
	
	function onCheckJsh(e){
		if(e.value != ''){
			if(e.value.length > 20 ){
				alert("教师编号 不能超过20位");
				return false;
			}
		}
	}
	
	function onCheckXm(e){
		if(e.value != ''){
			if(e.value.length > 10 ){
				alert("姓名 不能超过10个字");
				return false;
			}
		}
	}
	
	function onCheckZgxl(e){
		if(e.value != ''){
			if(e.value.length > 10 ){
				alert("最高学历 不能超过10个字");
				return false;
			}
		}
	}
	
	function onCheckZgxw(e){
		if(e.value != ''){
			if(e.value.length > 10 ){
				alert("最高学位 不能超过10个字");
				return false;
			}
		}
	}
	
	function onCheckJzglb(e){
		if(e.value != ''){
			if(e.value.length > 10 ){
				alert("教职工类别 不能超过10个字");
				return false;
			}
		}
	}
	
	function onCheckZw(e){
		if(e.value != ''){
			if(e.value.length > 10 ){
				alert("职务 不能超过10个字");
				return false;
			}
		}
	}
	
	function onCheckZc(e){
		if(e.value != ''){
			if(e.value.length > 10 ){
				alert("职称 不能超过10个字");
				return false;
			}
		}
	}
	
	function onCheckZyjszg(e){
		if(e.value != ''){
			if(e.value.length > 10 ){
				alert("专业技术资格 不能超过10个字");
				return false;
			}
		}
	}
	
	function onCheckZyjszwjb(e){
		if(e.value != ''){
			if(e.value.length > 10 ){
				alert("专业技术级别 不能超过10个字");
				return false;
			}
		}
	}
	
	function onCheckDqzt(e){
		if(e.value != ''){
			if(e.value.length > 10 ){
				alert("当前状态 不能超过10个字");
				return false;
			}
		}
	}
	
	function onButtonEdit(e) {
    	var btnEdit = this;
   		mini.open({
	        url: "admin.php?m=teacher&a=select_tree&schoolid=<?php echo $schoolid;?>",
	        showMaxButton: false,
	        title: "选择树",
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
</body>
</html>


