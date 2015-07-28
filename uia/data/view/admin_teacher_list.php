<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?> 
<script src="js/CommonLibs/EditForm.js" type="text/javascript"></script>
<?php if($data["updepid"]) { ?>
<div style="width:100%;height:30px;padding:5px 0px;clear:both;border:0px;background:none;">
    <div style="width:50%;float:left;text-align:left;">
    	<?php if($depid) { ?>
    	<a class="mini-button" iconCls="icon-add" style="text-align:center;" onclick="newRow()">增加</a>
    	<?php } ?>
    	<a class="mini-button" iconCls="icon-remove" style="text-align:center;" onclick="delRow()">删除</a>
    	<?php if($data['schoolid'] && $updepid == -1) { ?>
    	<a class="mini-button" iconCls="icon-remove" style="text-align:center;" onclick="doUpload()">导入Excel</a>
    	<?php } ?>
    </div>
    <div style="width:50%;float:left;text-align:right;">
		<input id="key" class="mini-textbox" emptyText="请输入关键字" style="width:150px;" onenter="onKeyEnter"/>   
        <a class="mini-button" onclick="search()">查询</a>
        <a class="mini-button" onclick="showAll()">显示全部</a>
        <!--<a href="admin.php?m=teacher&a=teacherList&deptId=<?php echo $data['depid'];?>&schoolid=<?php echo $data['schoolid'];?>&updepid=<?php echo $data['updepid'];?>&deptIdNull=yes" class="mini-button">未分配部门人员</a>-->
        <a class="mini-button" onclick="showDeptNull()">未分配部门人员</a>
	</div>            
</div>
<!--Layout-->
<div id="datagrid1" class="mini-datagrid" style="width:100%;height:92%;"
url="admin.php?m=teacher&a=getTeacherList&deptId=<?php echo $data['depid'];?>&schoolid=<?php echo $data['schoolid'];?>&updepid=<?php echo $data['updepid'];?>&deptIdNull=<?php echo $data['deptIdNull'];?>" idField="identityid" pageSize="50" multiSelect="true" >
	<div property="columns">
		<div type="checkcolumn"></div>
		<div field="zgh" width="80" headerAlign="center">职工号</div>
		<div field="xm" width="80" headerAlign="center">姓名</div>
		<div field="xb" width="40" headerAlign="center">性别</div>
		<div field="sfzjh" width="120" headerAlign="center">身份证号</div>
		<div field="csrq" width="80" dateFormat="yyyyMM-dd" headerAlign="center">出生日期</div>
		<div field="zw" width="80" headerAlign="center">职务</div>
		<div field="zc" width="80" headerAlign="center">职称</div>
		<div field="bmmc" width="160" headerAlign="center">部门名称</div>
		<div name="action" width="60" headerAlign="center" align="center" renderer="onActionRenderer" cellStyle="padding:0;">编辑</div>
	</div>
</div>
<?php } else { ?>
<h3 style="text-align: left;">请选择学校查看老师</h3>
<?php } ?>



<script type="text/javascript">
	
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
	
    function showDeptNull(){
    	mini.open({
            url: "admin.php?m=teacher&a=teacherDeptNull",
            title: "未分配部门人员", width: 900, height: 600,
            onload: function () {
                var iframe = this.getIFrameEl();
                var data = { action: "new"};
            },
            ondestroy: function (action) {
                grid.reload();
            }
        });
    }
    
	function search() {
        var key = mini.get("key").getValue();
        grid.load({ key: key });
    }
	function showAll(){
		grid.load();
	}
    function onKeyEnter(e) {
        search();
    }
    function onActionRenderer(e) {
        var grid = e.sender;
        var record = e.record;
        var uid = record._uid;
        var rowIndex = e.rowIndex;

        var s = '<a class="Edit_Button" href="javascript:editRow(\'' + uid + '\')">修改</a>'
        return s;
    }


    function newRow() {            
    	 mini.open({
             url: "admin.php?m=teacher&a=TeacherAdd&depid=<?php echo $data['depid'];?>",
             title: "添加老师信息", width: 800, height: 265,
             onload: function () {
                 var iframe = this.getIFrameEl();
                 var data = { action: "new"};
                 iframe.contentWindow.SetData(data);
             },
             ondestroy: function (action) {

                 grid.reload();
             }
         });
	}
    
    function editRow(row_uid) {
    	
    	 var row = grid.getSelected();
         if (row) {
             mini.open({
                 //url: "admin.php?m=teacher&a=getTeacher_by_identityid&schoolid=<?php echo $data['schoolid'];?>&deptid=<?php echo $data['depid'];?>&identityid=" + row.identityid,
                 url: "admin.php?m=teacher&a=TeacherAdd&schoolid=<?php echo $data['schoolid'];?>&identityid="+row.identityid,
                 title: "编辑老师信息", width: 800, height: 265,
                 onload: function () {
                     var iframe = this.getIFrameEl();
                     var data = { action: "edit", id: row.id };
                     iframe.contentWindow.SetData(data);
                     
                 },
                 ondestroy: function (action) {
                     grid.reload();
                     
                 }
             });
             
         } else {
             alert("请选中一条记录");
         }
         
    	
    	
        var row = grid.getRowByUID(row_uid);
        if (row) {
           
            var form = new mini.Form("#editform");
            form.clear();
            form.loading();
            $.ajax({
                url: "admin.php?m=teacher&a=getTeacher_by_identityid&schoolid=<?php echo $data['schoolid'];?>&deptid=<?php echo $data['depid'];?>&identityid=" + row.identityid,
                success: function (text) {
                    form.unmask();
					var o = mini.decode(text);
                    form.setData(o);
                    editWindow.show();                    
                },
                error: function () {
                    alert("表单加载错误");
                    form.unmask();
                }
            });

        }
    }
   
    function cancelRow() {
        grid.reload();
        editWindow.hide();
    }
    function delRow(row_uid) {
    	var rows = grid.getSelecteds();
        if (rows.length > 0) {
            if (confirm("确定删除选中记录？")) {
                var ids = [];
                for (var i = 0, l = rows.length; i < l; i++) {
                    var r = rows[i];
                    ids.push(r.identityid);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                	url: "admin.php?m=teacher&a=delTeacher",
                	type: 'POST',
    				data : {
    					identityid : id
    				},
                    success: function (text) {
                        grid.reload();
                    },
                    error: function () {
                    }
                });
            }
        } else {
            alert("请选中一条记录");
        }
    }
    function doUpload(){
    	mini.open({
    		url: "admin.php?m=teacher&a=loadExcel&schoolid=<?php echo $data['schoolid'];?>",
    		title: "导入Excel", width: 800, height: 300,
    		 ondestroy: function (action) {
                 grid.reload();                    
             }
    	});
    }
</script>
<?php include $this->gettpl('footer');?>
