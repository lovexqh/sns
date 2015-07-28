<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<form id="form1" method="post">
	<div id="editform" class="form">
		<input class="mini-hidden" name="fwid" value="<?php echo $id;?>" />
		<input class="mini-hidden" name="cslx" value="1" />
		<table style="width: 100%;">
			<tr style="height: 40px;">
				<td style="width: 60px;">参数名称：</td>
				<td style="width: 180px;"><input style="width:160px;" name="csmc" id="csmc" class="mini-textbox" onvalidation="onCheckCsmc" required="true" /></td>
			</tr>
			<tr style="height: 40px;">
				<td>参数描述：</td>
				<td><input style="width:160px;" name="csms" class="mini-textbox" /></td>
			</tr>
			<tr style="height: 40px;">	
				<td>是否必填：</td>
				<td><input style="width:160px;" type="comboboxcolumn" name="sfbt" class="mini-combobox" data="isnull" showNullItem="true" /></td>
			</tr>
			<tr style="height: 40px;">
				<td style="text-align: right; padding-top: 5px; padding-right: 20px;" colspan="6">
					<a class="mini-button" href="javascript:onOk()">提交</a>
					<a class="mini-button" href="javascript:onCancel()">取消</a>
				</td>
			</tr>

		</table>
	</div>
</form>
<script type="text/javascript">
	var isnull = [ 
		{id : 1, text : '必填'}, 
		{id : 2, text : '可选'} 
	];
	var servicetype = [ 
  		{id : 1, text : '整型'}, 
  		{id : 2, text : '字符串'} ,
		{id : 3, text : '日期型'}
  	];
	mini.parse();

	var form = new mini.Form("form1");

	function SaveData() {
		var o = form.getData(true);

		form.validate();
		if (form.isValid() == false)
			return;
		var json = mini.encode([ o ]);
		$.ajax({
			url : "admin.php?m=service&a=addInputParam",
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
	
	function onCheckCsmc(e){
		if(e.value != ''){
			$.post('admin.php?m=service&a=checkInputParamByCsmc&id=<?php echo $id;?>',
				{csmc:e.value,time:new Date().getTime() },
				function(data){
					if(data == 0){
						alert('你输入的参数已经存在，请重新输入！');
					}
				});
		}
	}
	
</script>
</body>
</html>


