<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?> 
<script src="js/CommonLibs/EditForm.js" type="text/javascript"></script>
<div style="width:100%;height:6%;border:0px;background:none;">
    <div style="width:50%;float:left;text-align:left;">
    		<a class="mini-button" iconCls="icon-add" style="text-align:center;" onclick="newRow()">从注册用户添加管理员</a>
            <a class="mini-button" iconCls="icon-remove" style="text-align:center;" onclick="delRow()">删除</a>
    </div>
        <div style="width:50%;float:left;text-align:right;">
			<input id="key" class="mini-textbox" emptyText="请输入管理员用户名" style="width:150px;" onenter="onKeyEnter"/>   
            <a class="mini-button" onclick="search()">查询</a>
		</div>            
</div>
<!--Layout-->
<div id="datagrid1" class="mini-datagrid" style="width:100%;height:94%;margin:auto;"
url="admin.php?m=esnadmin&a=getEsnAdmin" idField="id" pageSize="50" multiSelect="true" >
	<div property="columns">
		<div type="checkcolumn"></div>
		<div field="username" width="60" headerAlign="center">用户名</div>
		<div field="name" width="60" headerAlign="center">姓名</div>
		<div field="contact" width="80" headerAlign="center">联系方式</div>
		<div field="email" width="100" headerAlign="center">Email</div>
		<div field="xxmc" width="80" headerAlign="center">学校</div>

	</div>
</div>


<script type="text/javascript">
	
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
	
	function search() {
        var key = mini.get("key").getValue();
        grid.load({ key: key });
    }
    function onKeyEnter(e) {
        search();
    }

    function newRow() {            
    	 mini.open({
             url: "admin.php?m=esnadmin&a=adminAdd",
             title: "添加注册用户为管理员", width: 900, height: 600,
             onload: function () {
                 var iframe = this.getIFrameEl();
                 var data = { action: "new"};
             },
             ondestroy: function (action) {
                 grid.reload();
             }
         });
	}
   
    function cancelRow() {
        grid.reload();
        editWindow.hide();
    }
    function delRow(row_uid) {
    	var rows = grid.getSelecteds();
        if (rows.length > 0) {
            if (confirm("确定删除选中的管理员吗？")) {
                var ids = [];
                for (var i = 0, l = rows.length; i < l; i++) {
                    var r = rows[i];
                    ids.push(r.id);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                	url: "admin.php?m=esnadmin&a=delAdmin",
                	type: 'POST',
    				data : {
    					id : id
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

</script>
<?php include $this->gettpl('footer');?>

