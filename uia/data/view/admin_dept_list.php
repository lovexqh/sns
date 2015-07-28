<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<!--Layout-->
<style>
#form1 td{padding-top:10px;}
</style>
<div style="width:50%;text-align:left;">
	<!-- <?php if($data['UpDeptID']>0) { ?> -->
    <a class="mini-button" href="javascript:addDept()">添加同级部门</a>
    <!-- <?php } ?> -->
    <!-- <?php if($data['schoolid']) { ?> -->
	<a class="mini-button" href="javascript:addLow()">添加下级部门</a>
	<!-- <?php } ?> -->
</div>
<div id="form1" style="padding-left: 20px;">
	<table>
		<tr>
			<td>部门名：</td>
			<td><input id="DepartName" name="DepartName" class="mini-textbox" style="width:450px;" required="true" value='<?php echo $data['departName'];?>' />
				<input class="mini-hidden" id="schoolid" name="schoolid" value='<?php echo $data['schoolid'];?>' /> 
				<input class="mini-hidden" id="deptID" name="deptID" value='<?php echo $data['deptID'];?>' /> 
		</tr>
		<tr>
			<td>部门编码：</td>
			<td><input id="deptCode" style="width:450px;" name="deptCode" class="mini-textbox" value='<?php echo $data['deptCode'];?>' /></td>
		</tr>
		<tr>
			<td>部门负责人：</td>
			<td><input id="DeptManager" style="width:450px;" name="DeptManager" class="mini-textbox" value='<?php echo $data['DeptManager'];?>' /></td>
		</tr>
		<tr>
			<td>部门联系电话：</td>
			<td><input id="DeptPhone" style="width:450px;" name="DeptPhone" class="mini-textbox" value='<?php echo $data['DeptPhone'];?>' /></td>
		</tr>
		<tr>
			<td>部门简介：</td>
			<td><input id="DeptComment" name="DeptComment" class="mini-textarea" style="width:450px;"  value='<?php echo $data['DeptComment'];?>' /></td>
		</tr>
		<tr>
			<td>上级部门：</td>
			<td><input id="UpDeptID" style="width:450px;" name="UpDeptID" class="mini-buttonedit" onbuttonclick="onButtonEdit" value='<?php echo $data['UpDeptID'];?>' text='<?php echo $data['UpDeptName'];?>' /></td>
		</tr>		
		<tr>
			<td style="text-align:right;">
			<a class="mini-button" href="javascript:submitForm()">提交</a>
			<a class="mini-button" href="javascript:clearForm">取消</a>
		</tr>
	</table>
</div>
<script type="text/javascript">

        mini.parse();
        function onButtonEdit(e) {
            var btnEdit = this;
            mini.open({
                url: "admin.php?m=dept&a=select_tree",
                showMaxButton: false,
                title: "选择树",
                width: 500,
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

		function submitForm() {
            //提交表单数据
            var form = new mini.Form("#form1");
            var data = form.getData();      //获取表单多个控件的数据
            console.log(data);
            var json = mini.encode(data);   //序列化成JSON
            console.log(json);
            $.ajax({
                url: "admin.php?m=dept&a=EditSubmit",
                type: "post",
                data: { submitData: json },
                success: function (text) {
					var result = eval('('+text+')');
					if(result.code){
						alert(result.msg);
						//removeTab();
					}else{
						alert(result.msg);
					}
					
                }
            });
        }
		
		function removeTab() {
	        var tabs = mini.get("mainTabs");
	        var tab = tabs.getActiveTab();
	        if (tab) {
	            tabs.removeTab(tab);
	        }
	    }
		
		function addDept() {
			mini.open({
        		url: 'admin.php?m=dept&a=addDept&updeptid=<?php echo $data['UpDeptID'];?>&schoolid=<?php echo $data['schoolid'];?>',
        		title: "添加部门",width: 785, height: 265,
        		ondestroy: function (action) {
                }
        	})
		}
		
		function addLow() {
			mini.open({
        		url: 'admin.php?m=dept&a=addDept&updeptid=<?php echo $data['deptID'];?>&schoolid=<?php echo $data['schoolid'];?>',
        		title: "添加部门",width: 785, height: 265,
        	})
		}


		function clearForm() {
            var form = new mini.Form("#form1");
            form.clear();
        }

        function labelModel() {
            var fields = form.getFields();
            for (var i = 0, l = fields.length; i < l; i++) {
                var c = fields[i];
                if (c.setReadOnly) c.setReadOnly(true);     //只读
                if (c.setIsValid) c.setIsValid(true);      //去除错误提示
                if (c.addCls) c.addCls("asLabel");          //增加asLabel外观
            }
        }
        function inputModel() {
            var fields = form.getFields();
            for (var i = 0, l = fields.length; i < l; i++) {
                var c = fields[i];
                if (c.setReadOnly) c.setReadOnly(false);
                if (c.removeCls) c.removeCls("asLabel");
            }
            mini.repaint(document.body);
        }
        
        function doUpload(){
        	mini.open({
        		url: "admin.php?m=dept&a=loadExcel&deptid=<?php echo $data['deptID'];?>&schoolid=<?php echo $data['schoolid'];?>",
        		title: "导入Excel", width: 800, height: 300,
        		 ondestroy: function (action) {
                     grid.reload();                    
                 }
        	});
        }
    </script>
</body>
</html>
