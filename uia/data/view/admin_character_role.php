<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<script src="js/CommonLibs/EditForm.js" type="text/javascript"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="js/jquery-1.4.4.min.js" type="text/javascript"></script>
<!--Layout-->
<div style="width:100%;height:30px;padding:5px 0px;clear:both;border:0px;background:none;">
	<div style="width:50%;float:left;text-align:left;">
		<a class="mini-button" iconCls="icon-add" style="text-align:center;" onclick="newRowitem()">增加</a>
		<a class="mini-button" iconCls="icon-remove" style="text-align:center;" onclick="delRow()">删除</a>
	</div>
</div>
<!--Layout-->
<div id="datagrid2" class="mini-datagrid" style="width:100%;height:92%;" url="admin.php?m=character&a=getitemByDataid&dataid='<?php echo $dataid;?>'" idField="itemcode"  pageSize="50" multiSelect="true" >
	<div property="columns">
		<div type="checkcolumn"></div>
		<div field="itemcn" width="120" allowSort="true" headerAlign="center">数据项中文描述</div>
		<div field="itemen" width="80" allowSort="true" headerAlign="center">数据项英文描述<!--combobox editor--></div>
        <div field="itemcode" width="80" allowSort="true">数据项编码</div>
        <div field="dataid" width="80" allowSort="true">所属数据类型</div>
		<div name="action" width="120" headerAlign="center" align="center" renderer="onActionRenderer" cellStyle="padding:0;">修改</div>
	</div>
</div>
<script type="text/javascript">

    mini.parse();
    var grid = mini.get("datagrid2");
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

 function newRowitem() {

   	 mini.open({
            url: "admin.php?m=character&a=itemAdd&dataid=<?php echo $dataid;?>",
            title: "添加用户信息", width: 785, height: 225,
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
   		var autoid  = row.autoid;
   		//console.log(autoid);
        if (row) {
            mini.open({
                url: "admin.php?m=character&a=itemedit&autoid="+autoid,
                title: "编辑数据项信息", width: 785, height: 225,
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
               url: "admin.php?m=character&a=getcharacterByDataid&dataid=" + row.id,
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



    function cancelRow() {
        grid.reload();
        editWindow.hide();
    }
    function delRow(row_uid) {
    	var rows = grid.getSelecteds();
    	//console.log(rows);
        if (rows.length > 0) {
            if (confirm("确定删除选中记录？")) {
                var ids = [];
                for (var i = 0, l = rows.length; i < l; i++) {
                    var r = rows[i];
                    ids.push(r.autoid);
                }
                var id = ids.join(',');
                console.log(id);
                grid.loading("操作中，请稍后......");
                $.ajax({
                	url: "admin.php?m=character&a=delitem",
                	type: 'POST',
    				data : {
    					data : id
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


     function onPageChanged(e) {
        alert(e.pageIndex+":"+e.pageSize);
    }  

</script>