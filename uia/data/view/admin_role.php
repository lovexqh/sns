<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?> <!--Layout-->
<div id="layout1" class="mini-layout" style="width:100%;height:100%;text-align:left;">
	<div title="center" region="center" bodyStyle="overflow:hidden;" style="border:0;">
		<!--Splitter-->
		<div class="mini-splitter" style="width:100%;height:100%;" borderStyle="border:0;">
			<div showCollapseButton="false" style="border:0px;" size="300" >
				<div style="width:100%;height:30px;padding:5px 0px;clear:both;border:0px;background:none;">
				    <div style="width:50%;float:left;text-align:left;">
				    	<a class="mini-button" iconCls="icon-add" style="text-align:center;" onclick="newRow()">增加</a>
				        <a class="mini-button" iconCls="icon-remove" style="text-align:center;" onclick="delRow()">删除</a>
				    </div>
				    <div style="width:50%;float:left;text-align:left;">
						<input id="key" class="mini-textbox" emptyText="请输入角色名" style="width:97px;" onenter="onKeyEnter"/>   
				        <a class="mini-button" onclick="search()">查询</a>
					</div>            
				</div>
				<!--Layout-->
				<div id="datagrid1" class="mini-datagrid" style="width:100%;height:92%;"
				url="admin.php?m=role&a=getRoleList" idField="RoleID" onselectionchanged="onSelectionChanged" pageSize="50" multiSelect="true" >
					<div property="columns">
						<div type="checkcolumn"></div>
						<div field="RoleName" width="80" allowSort="true">角色名</div>
					</div>
				</div>
			</div>
			<div showCollapseButton="false" style="border:0px;" >
				<!--Edit-->
				<div id="editPage" class="mini-tabs bg-toolbar" activeIndex="0" style="width:100%;height:100%;">
					<div title="角色编辑" url="admin.php?m=role&a=editRole" ></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    
    function onSelectionChanged(e) {  
        var grid = e.sender;
        var record = grid.getSelected();
        showTab(record);
    }
	
	function search() {
        var key = mini.get("key").getValue();
        grid.load({ key: key });
    }
    function onKeyEnter(e) {
        search();
    }
   
    function newRow() {            
    	 mini.open({
             url: "admin.php?m=role&a=RoleAdd",
             title: "添加角色信息", width: 300, height: 100,
             onload: function () {
                 var iframe = this.getIFrameEl();
                 var data = { action: "new"};
                 
             },
             ondestroy: function (action) {
                 grid.reload();
             }
         });
	}
    
    function showTab(record) {
        var tabs = mini.get("editPage");
        var id = "tab$" + record.RoleID;
        var tab = tabs.getTab(id);
        if (!tab) {
            tab = {};
            tab.name = id;
            tab.title = record.RoleName;
            tab.showCloseButton = true;
            tab.url = "admin.php?m=role&a=editRole&RoleID="+record.RoleID;
            tabs.addTab(tab);
        }
        tabs.activeTab(tab);
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
                    ids.push(r.RoleID);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                	url: "admin.php?m=role&a=delRole",
                	type: 'POST',
    				data : {
    					RoleID : id
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
