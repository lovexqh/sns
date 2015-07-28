<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?> 
<script src="js/CommonLibs/EditForm.js" type="text/javascript"></script>
<div id="layout1" class="mini-layout" style="width:100%;height:100%;text-align:left;">
	<div title="center" region="center" bodyStyle="overflow:hidden;" style="border:0;">
		<!--Splitter-->
		<div class="mini-splitter" style="width:100%;height:100%;" borderStyle="border:0;">
			<div showCollapseButton="false" style="border:0px;width:50%;">
				<div style="width:100%;height:30px;padding:5px 0px;clear:both;border:0px;background:none;">
				    <div style="width:50%;float:left;text-align:left;">
				    	<a class="mini-button" iconCls="icon-add" style="text-align:center;" onclick="newRow()">新建管理员</a>
				    	<a class="mini-button" iconCls="icon-add" style="text-align:center;" onclick="addFromDsk()">添加社区用户管理员</a>
				        <a class="mini-button" iconCls="icon-remove" style="text-align:center;" onclick="delRow()">删除</a>
				    </div>
				    <div style="width:50%;float:left;text-align:right;">
						<input id="key" class="mini-textbox" emptyText="请输入管理员名" style="width:110px;" onenter="onKeyEnter"/>   
				        <a class="mini-button" onclick="search()">查询</a>
					</div>            
				</div>
				<!--Layout-->
				<div id="datagrid1" class="mini-datagrid" style="width:100%;height:92%;"
					url="admin.php?m=admins&a=getNewAdminList" idField="id" onrowclick="onSelection" pageSize="50" multiSelect="true" >
					<div property="columns">
						<div type="checkcolumn"></div>
						<div field="xxmc" width="100" headerAlign="center">学校</div>
						<div field="username" width="80" headerAlign="center">管理员名</div>
						<div field="name" width="60" headerAlign="center">姓名</div>
						<div field="contact" width="100" headerAlign="center">联系方式</div>
						<div field="adminType" width="100" headerAlign="center">类型</div>
						<div name="action" width="80" headerAlign="center" align="center" renderer="onActionRenderer1" cellStyle="padding:0;">查看</div>
					</div>
				</div>
			</div>
			<div showCollapseButton="false" style="border:0px;" >
				<!--Edit-->
				<div id="editPage" class="mini-tabs bg-toolbar" activeIndex="0" style="width:100%;height:100%;">
					<div title="数据中心管理员角色" url="admin.php?m=admins&a=getAdminRole" ></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();

    function onActionRenderer1(e) {
        var grid = e.sender;
        var record = e.record;
        var uid = record._uid;
        var rowIndex = e.rowIndex;

        var s = '<a class="Edit_Button" href="javascript:editnewadmin()">编辑</a> '
        return s;
    }
    
    function editnewadmin(){
    	var row = grid.getSelected();
        if (row) {
            mini.open({
                url: "admin.php?m=admins&a=NewAdminAdd&id="+row.id,
                title: "编辑管理员信息", width: 300, height: 320,
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
    }
    
    function search() {
        var key = mini.get("key").getValue();
        grid.load({ key: key });
    }
    
    function chgpdw(){
    	
    }
    
    function newRow(){
    	mini.open({
    		url: "admin.php?m=admins&a=NewAdminAdd",
            title: "添加新管理员", width: 300, height: 320,
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
    
    function addFromDsk(){
    	mini.open({
            url: "admin.php?m=admins&a=adminAdd",
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
    
    function delRow(){
    	var rows = grid.getSelecteds();
        if (rows.length > 0) {
            if (confirm("确定删除选中记录？")) {
                var ids = [];
                for (var i = 0, l = rows.length; i < l; i++) {
                    var r = rows[i];
                    ids.push(r.id);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                	url: "admin.php?m=admins&a=delNewAdmin",
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
            alert("请选中至少一条记录");
        }
    }
    
    function onSelection(e) {  
        var grid = e.sender;
        var record = grid.getSelected();
        showTab(record);
    }
    
    function showTab(record) {
        var tabs = mini.get("editPage");
        var id = "tab$" + record.id;
        var tab = tabs.getTab(id);
        if (!tab) {
            tab = {};
            tab.name = id;
            tab.title = record.name;
            tab.showCloseButton = true;
            tab.url = "admin.php?m=admins&a=getAdminRole&id="+record.id;
            tabs.addTab(tab);
        }
        tabs.activeTab(tab);
    }

</script>
<?php include $this->gettpl('footer');?>

