<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>

<form id="form1" method="post">
	<div id="editform" class="form">
		<table style="width: 100%;">	
			<tr style="height: 30px;">
				<td>角色名称：</td>
				<td><input name="RoleName" class="mini-textbox" onvalidation="onCheckRoleName" required="true" /></td>
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
	//Ajax开始添加的操作
	var json = mini.encode([ o ]);
	$.ajax({
		url : "admin.php?m=role&a=addRole",
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

function CloseWindow(action) {
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

function onCheckRoleName(e){
	if(e.value != ''){
		$.post('admin.php?m=role&a=checkRoleByRoleName',
			{RoleName:e.value,time:new Date().getTime() },
			function(data){
				if(data == 0){
					alert('你输入的角色已经存在，请重新输出');
				}
			});
	}
}
</script>

<?php include $this->gettpl('footer');?>
