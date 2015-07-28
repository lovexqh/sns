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
		<input id="key" class="mini-textbox" emptyText="请输入教室名" style="width:150px;" onenter="onKeyEnter"/>   
        <a class="mini-button" onclick="search()">查询</a>
	</div>            
</div>
<!--Layout-->
<div id="datagrid1" class="mini-datagrid" style="width:100%;height:92%;"
url="admin.php?m=classroom&a=getClassroomList&eid=<?php echo $data['eid'];?>&ss=<?php echo $data['ss'];?>" idField="id" pageSize="50" multiSelect="true" >
	<div property="columns">
		<div type="checkcolumn"></div>
		<div field="xqh" width="120" headerAlign="center">校区</div>
		<div field="jxlm" width="100" headerAlign="center">教学楼</div>
		<div field="jsm" width="100" headerAlign="center">教室名</div>
		<div field="szlc" width="60" headerAlign="center">所在楼层</div>
		<div field="jsyt" width="100" headerAlign="center">教室用途</div>
		<div field="zws" width="100" headerAlign="center">座位数</div>
		<div field="kszws" width="100" headerAlign="center">考试座位数</div>
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
             url: "admin.php?m=classroom&a=ClassroomAdd&jxlid=<?php echo $data['eid'];?>",
             title: "添加教室信息", width: 785, height: 265,
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
                 url: "admin.php?m=classroom&a=ClassroomAdd&id="+row.id,
                 title: "编辑教室信息", width: 785, height: 265,
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
                url: "admin.php?m=classroom&a=getClassroom_by_id&id=" + row.id,
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
                	url: "admin.php?m=classroom&a=delClassroom",
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

