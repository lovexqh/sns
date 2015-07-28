<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?> 
<script src="js/CommonLibs/EditForm.js" type="text/javascript"></script>
<div style="width:100%;height:30px;padding:5px 0px;clear:both;border:0px;background:none;">
    <div style="width:50%;float:left;text-align:left;">
    	<?php if($data["ss"]==2) { ?>
    	<a class="mini-button" iconCls="icon-add" style="text-align:center;" onclick="newRow()">增加</a>
    	<?php } ?>
        <a class="mini-button" iconCls="icon-remove" style="text-align:center;" onclick="delRow()">删除</a>
    </div>
    <div style="width:50%;float:left;text-align:right;">
		<input id="key" class="mini-textbox" emptyText="请输入专业名" style="width:150px;" onenter="onKeyEnter"/>   
        <a class="mini-button" onclick="search()">查询</a>
	</div>            
</div>
<!--Layout-->
<div id="datagrid1" class="mini-datagrid" style="width:100%;height:92%;"
url="admin.php?m=specialty&a=getSpecialtyList&eid=<?php echo $data['eid'];?>&ss=<?php echo $data['ss'];?>" idField="id" pageSize="50" multiSelect="true" >
	<div property="columns">
		<div type="checkcolumn"></div>
		<div field="zyh" width="60" headerAlign="center">专业号</div>
		<div field="zymc" width="120" headerAlign="center">专业名称</div>
		<div field="zyjc" width="60" headerAlign="center">专业简称</div>
		<div field="xjzyh" width="100" headerAlign="center">校级专业号</div>
		<div field="xjzym" width="100" headerAlign="center">校级专业名</div>
		<div field="xz" width="100" headerAlign="center">学制</div>
		<div name="action" width="120" headerAlign="center" align="center" renderer="onActionRenderer" cellStyle="padding:0;">编辑</div>
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
             url: "admin.php?m=specialty&a=SpecialtyAdd&yxid=<?php echo $data['eid'];?>",
             title: "添加专业信息", width: 785, height: 265,
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
                 url: "admin.php?m=specialty&a=SpecialtyAdd&id="+row.id,
                 title: "编辑专业信息", width: 785, height: 265,
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
                url: "admin.php?m=specialty&a=getSpecialty_by_id&id=" + row.id,
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
                    ids.push(r.id);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                	url: "admin.php?m=specialty&a=delSpecialty",
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
