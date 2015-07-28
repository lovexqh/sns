<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<!--Layout-->
<style>
#form1 td{padding-top:10px;}
<style>
td {
	text-align: left;
	padding-left: 10px;
}
</style>
<?php if($id != 0) { ?>
<div style="width:50%;text-align:left;">
	<a class="mini-button" href="javascript:addModule()">添加同级模块</a>
	<a class="mini-button" href="javascript:addLow()">添加下级模块</a>
	<a class="mini-button" href="javascript:delModule()">删除该模块</a>
</div>
<div id="form1" style="padding-left: 20px;">
	<input class="mini-hidden" name="id" value="<?php echo $module['id'];?>" /> 
	<table>
		<tr>
			<td>应用名称（英文）：</td>
			<td><input name="appName" class="mini-textbox" style="width:450px;" required="true" value="<?php echo $module['appName'];?>" /></td>
		</tr>
		<tr>
			<td>应用别名（中文）：</td>
			<td><input name="appAlias" class="mini-textbox" style="width:450px;" required="true" value="<?php echo $module['appAlias'];?>" /></td>
		</tr>
		<tr>
			<td>应用描述：</td>
			<td><input name="appDesc" class="mini-textbox" style="width:450px;" value="<?php echo $module['appDesc'];?>" /></td>
		</tr>
		<tr>
			<td>应用版本：</td>
			<td><input name="appVersion" class="mini-textbox" style="width:450px;"  value="<?php echo $module['appVersion'];?>" /></td>
		</tr>
		<tr>
			<td>应用入口标识：</td>
			<td><input name="appEntry" class="mini-textbox" style="width:450px;" required="true" value="<?php echo $module['appEntry'];?>" /></td>
		</tr>
		<tr>
			<td>注释说明：</td>
			<td><input name="remark" class="mini-textbox" style="width:450px;" value="<?php echo $module['remark'];?>" /></td>
		</tr>
		<tr>
			<td style="text-align:right;">
				<a class="mini-button" href="javascript:submitForm()">提交</a>
				<a class="mini-button" href="javascript:clearForm">取消</a>
			</td>
		</tr>
	</table>
<?php } else { ?>
<h3 style="text-align: left;">请选择模块进行编辑</h3>
<?php } ?>
<script type="text/javascript">

        mini.parse();

		function submitForm() {
            //提交表单数据
            var form = new mini.Form("#form1");
            var o = form.getData();      //获取表单多个控件的数据
            console.log(o);
            var json = mini.encode([o]);   //序列化成JSON
            console.log(json);
            $.ajax({
                url: "admin.php?m=module&a=upModuleById",
                type: "post",
                data: { data: json },
                success: function (text) {
                	if(text == 0){
                		alert("部门修改成功！");
                	}else if(text == 1){
                		alert("模块名称（英文）已存在！");
                	}else if(text == 2){
                		alert("模块别名（中文）已存在！");
                	}else if(text == 3){
                		alert("应用入口标识已存在！");
                	}
                }
            });
        }

		function clearForm() {
            var form = new mini.Form("#form1");
            form.clear();
        }
        
        function addModule(){
        	mini.open({
        		url: 'admin.php?m=module&a=addModule&pid='+<?php echo $module['pid'];?>,
        		title: "添加同级模块",width: 400, height: 300,
        		ondestroy: function (action) {
                }
        	})
        }
        function addLow(){
        	mini.open({
        		url: 'admin.php?m=module&a=addModule&pid='+<?php echo $module['id'];?>,
        		title: "添加下级模块",width: 400, height: 300,
        	})
        }
        
        function delModule(){
        	if(confirm("确定要删除该模块吗？")){
        		$.ajax({
                	url: "admin.php?m=module&a=delModule",
                	type: 'POST',
    				data : {
    					id : <?php echo $module['id'];?>
    				},
                    success: function (text) {
                        alert("删除成功！");
                    },
                    error: function () {
                    }
                });
        	}
        }
    </script>
</body>
</html>
