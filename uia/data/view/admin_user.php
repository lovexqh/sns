<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?> <!--Layout-->
11111111111111111111
3333333333333
<div id="layout1" class="mini-layout" style="width:100%;height:100%;text-align:left;">
	<div style="width:100%;height:30px;padding:5px 0px;clear:both;border:0px;background:none;">
		<!--Splitter-->
		<div class="mini-splitter" style="width:100%;height:100%;" borderStyle="border:0;">
			<div showCollapseButton="false" style="border:0px;" size="400" >
				<div style="width:100%;height:30px;padding:5px 0px;clear:both;border:0px;background:none;">
				    <div style="width:50%;float:left;text-align:left;">
				        <a class="mini-button" iconCls="icon-remove" style="text-align:center;" onclick="delRow()">删除</a>
				    </div>
				    <div style="width:50%;float:left;text-align:left;">
				        <a class="mini-button" iconCls="icon-add" style="text-align:center;" onclick="addRow()">+++</a>
				    </div>
				    <div style="width:50%;float:left;text-align:right;">
						<input id="key" class="mini-textbox" emptyText="请输入用户名/邮箱" style="width:130px;" onenter="onKeyEnter"/>   
				        <a class="mini-button" onclick="search()">查询</a>
					</div>            
				</div>
				<!--Layout-->
				<div id="datagrid1" class="mini-datagrid" style="width:100%;height:92%;"
				url="admin.php?m=user&a=getUserList" idField="uid" onselectionchanged="onSelectionChanged" pageSize="50" multiSelect="true" >
					<div property="columns">
						<div type="checkcolumn"></div>
						<div field="username" width="80" headerAlign="center">用户名</div>
						<div field="email" width="120" headerAlign="center">Email</div>
						<div field="realname" width="80" headerAlign="center">真实姓名</div>
						<div field="identityname" width="60" headerAlign="center">身份</div>
						<div name="action" width="60" headerAlign="center" align="center" renderer="onActionRenderer" cellStyle="padding:0;">编辑</div>
					</div>
				</div>
			</div>
			<div showCollapseButton="false" style="border:0px;" size="500">
				<!--Edit-->
				<div id="editPage" class="mini-tabs bg-toolbar" activeIndex="0" style="width:100%;height:100%;">
					<div title="用户角色" url="admin.php?m=user&a=getUseridByUser" ></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	
    mini.parse();
    console.info(mini);
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
   
    function addRow() {
        mini.open({
            url: "admin.php?m=user&a=userAdd",
            title: "编辑用户信息", width: 360, height: 220,
            onload: function () {
                var iframe = this.getIFrameEl();
                var data = { action: "edit"};
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
                url: "admin.php?m=user&a=userAdd&userid="+row.uid,
                title: "编辑用户信息", width: 360, height: 220,
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
               url: "admin.php?m=user&a=getUserByUid&userid=" + row.id,
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
    
    function onActionRenderer(e) {
        var grid = e.sender;
        var record = e.record;
        var uid = record._uid;
        var rowIndex = e.rowIndex;

        var s = '<a class="Edit_Button" href="javascript:editRow(\'' + uid + '\')">修改</a>'
        return s;
    }
    
    function showTab(record) {
        var tabs = mini.get("editPage");
        var id = "tab$" + record.uid;
        var tab = tabs.getTab(id);
        if (!tab) {
            tab = {};
            tab.name = id;
            tab.title = record.username;
            tab.showCloseButton = true;
            tab.url = "admin.php?m=user&a=getUseridByUser&userid="+record.uid;
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
                    ids.push(r.uid);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                	url: "admin.php?m=user&a=delUser",
                	type: 'POST',
    				data : {
    					uid : id
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

