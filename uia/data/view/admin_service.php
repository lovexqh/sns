<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
﻿<?php include $this->gettpl('header');?> 
<script src="js/CommonLibs/EditForm.js" type="text/javascript"></script>
<div id="layout1" class="mini-layout" style="width:100%;height:100%;text-align:left;">
	<div title="center" region="center" bodyStyle="overflow:hidden;" style="border:0;">
		<!--Splitter-->
		<div class="mini-splitter" style="width:100%;height:103%;" borderStyle="border:0;">
			<div showCollapseButton="false" style="border:0px;" size="560" >
				<div style="width:100%;height:30px;padding:5px 0px;clear:both;border:0px;background:none;">
				    <div style="width:50%;float:left;text-align:left;">
				    	<a class="mini-button" iconCls="icon-add" style="text-align:center;" onclick="newRow()">增加</a>
				        <a class="mini-button" iconCls="icon-remove" style="text-align:center;" onclick="delRow()">删除</a>
				    </div>
				    <div style="width:50%;float:left;text-align:right;">
						<input id="key" class="mini-textbox" emptyText="请输入服务关键字" style="width:150px;" onenter="onKeyEnter"/>   
				        <a class="mini-button" onclick="search()">查询</a>
				        <a class="mini-button" onclick="findAll()">显示全部</a>
					</div>            
				</div>
				<!--Layout-->
				<div id="datagrid1" class="mini-datagrid" style="width:100%;height:90%;"
				url="admin.php?m=service&a=getServiceList" idField="id" onselectionchanged="onSelectionChanged" pageSize="50" multiSelect="true" >
					<div property="columns">
						<div type="indexcolumn" width="12px"></div>
						<div type="checkcolumn" width="16px"></div>
						<div field="serviceID" width="30" headerAlign="center" allowSort="true">服务号</div>
						<div field="method" width="60" headerAlign="center" allowSort="true">方法名</div>
						<div field="serviceDes" width="100" headerAlign="center" allowSort="true">服务描述</div>
						<div field="service_type" width="20" headerAlign="center" allowSort="true">种类</div>
						<div name="action" width="15" headerAlign="center" align="center" renderer="onActionRenderer" cellStyle="padding:0;">编辑</div>
					</div>
				</div>
			</div>
			<div showCollapseButton="false" style="border:0px;" >
				<div id="paramPage" class="mini-tabs bg-toolbar" activeIndex="0" style="width:100%;height:97%;">
					<div title="服务参数" url="admin.php?m=service&a=showParam" ></div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
	
    function onActionRenderer(e) {
        var grid = e.sender;
        var record = e.record;
        var uid = record._uid;
        var rowIndex = e.rowIndex;
        var s = '<a class="Edit_Button" href="javascript:editRow(\'' + uid + '\')">修改</a>'
        return s;
    }
    
    function search() {
        var key = mini.get("key").getValue();
        grid.load({ key: key });
    }
    
    function findAll(){
    	grid.load();
    }
    
    function onSelectionChanged(e) {  
        var grid = e.sender;
        var record = grid.getSelected();
        showTab(record);
    }
    
    function showTab(record) {
        var tabs = mini.get("paramPage");
        var id = "tab$" + record.id;
        var tab = tabs.getTab(id);
        if (!tab) {
            tab = {};
            tab.name = id;
            tab.title = record.serviceID;
            tab.showCloseButton = true;
            tab.url = "admin.php?m=service&a=showParam&id="+record.id;
            tabs.addTab(tab);
        }
        tabs.activeTab(tab);
    }

    function newRow() {            
    	 mini.open({
             url: "admin.php?m=service&a=serviceAdd",
             title: "添加服务信息", width: 500, height: 260,
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
                url: "admin.php?m=service&a=ServiceAdd&id="+row.id,
                title: "编辑服务信息", width: 500, height: 260,
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
                    ids.push(r.id);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                	url: "admin.php?m=service&a=delService",
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

