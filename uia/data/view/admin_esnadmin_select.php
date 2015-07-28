<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?> 
<script src="js/CommonLibs/EditForm.js" type="text/javascript"></script>
<div style="width:100%;height:30px;padding:5px 0px;clear:both;border:0px;background:none;">
	<div style="width:50%;float:left;text-align:left;">
    	<a class="mini-button" iconCls="icon-add" style="text-align:center;" onclick="newRow()">设为管理员</a>
    </div>
    <div style="width:50%;float:right;text-align:right;">
		<input id="key" class="mini-textbox" emptyText="请输入用户名" style="width:150px;" onenter="onKeyEnter"/>   
        <a class="mini-button" onclick="search()">查询</a>
        <a class="mini-button" onclick="showAll()">显示全部</a>
	</div>            
</div>
<!--Layout-->
<div id="datagrid1" class="mini-datagrid" style="width:100%;height:92%;"
url="admin.php?m=admins&a=getMemberList&deptid=<?php echo $data['depid'];?>&logschoolid=<?php echo $data['logschoolid'];?>&updeptid=<?php echo $data['updepid'];?>&schoolid=<?php echo $data['schoolid'];?>" idField="uid" pageSize="50" multiSelect="true" >
	<div property="columns">
		<div type="checkcolumn"></div>
		<div field="username" width="60" headerAlign="center">用户名</div>
		<div field="email" width="100" headerAlign="center">Email</div>
		<div field="xm" width="60" headerAlign="center">姓名</div>
		<div field="sfzjh" width="90" headerAlign="center">身份证件号</div>
		<div field="xxmc" width="80" headerAlign="center">学校名称</div>
	</div>
</div>

<script type="text/javascript">	
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
	
    function showAll(){
    	grid.load();
    }
    
	function search() {
        var key = mini.get("key").getValue();
        grid.load({ key: key });
    }
    function onKeyEnter(e) {
        search();
    }

    function CloseWindow(action) {
		if (window.CloseOwnerWindow)
			return parent.window.CloseOwnerWindow(action);
		else
			parent.window.close();
	}
    
    function newRow(){
    	var rows = grid.getSelecteds();
        if (rows.length > 0) {
            if (confirm("确定要设置选中的用户为管理员吗？")) {
                var ids = [];
                for (var i = 0, l = rows.length; i < l; i++) {
                    var r = rows[i];
                    ids.push(r.uid);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                	url: "admin.php?m=admins&a=addadmin",
                	type: 'POST',
    				data : {
    					uid : id
    				},
                    success: function (text) {
                    	alert("设置成功！");
                    	CloseWindow("save");
                    },
                    error: function () {
                    }
                });
            }
        } else {
            alert("请选中一条记录");
        }
    }
 
</script>
<?php include $this->gettpl('foot');?>

