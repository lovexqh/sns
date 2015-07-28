<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?> <!--Layout-->
<script src="js/CommonLibs/EditForm.js" type="text/javascript"></script>
<div id="layout1" class="mini-layout" style="width:100%;height:100%;text-align:left;">
	<div style="width:100%;height:30px;padding:5px 0px;clear:both;border:0px;background:none;">
       
		<!--Splitter-->
		<div class="mini-splitter" style="width:100%;height:100%;" borderStyle="border:0;">
			<div showCollapseButton="false" style="border:0px;" size="400" >
          <div style="width:100%;text-align:right;float:right;position:absolute;float:right;">
            <select id = "icons" style = "height:20px;">
            <option value="0" selected="">数据类型</option>
            <option value="1">数据项</option>
        </select>
            <input id="key" class="mini-textbox" emptyText="请输入数据项" style="width:97px;" onenter="onKeyEnter"/>
            <a id="search_option" class="mini-button" onclick="search()">查询</a>
            <a id="search_option" class="mini-button" onclick="reload()">显示全部</a>
        </div>
				<div style="width:100%;height:30px;padding:5px 0px;clear:both;border:0px;background:none;">
				    <div style="width:50%;float:left;text-align:left;">
				    	<a class="mini-button" iconCls="icon-add" style="text-align:center;" onclick="newRow()">增加</a>
				        <a class="mini-button" iconCls="icon-remove" style="text-align:center;" onclick="delRow()">删除</a>
				    </div>
				</div>
				<!--Layout-->
				<div id="datagrid1" class="mini-datagrid" style="width:100%;height:92%;" url="admin.php?m=character&a=getCharacterList" idField="dataid" onselectionchanged="onSelectionChanged" pageSize="50" multiSelect="true" >
					<div property="columns">
						<div type="checkcolumn"></div>
						<div field="dataid" width="80" allowSort="true">数据类型ID</div>
						<div field="datacn" width="120" allowSort="true" headerAlign="center">数据类型中文描述</div>
						<div field="dataen" width="80" allowSort="true" headerAlign="center">数据类型英文描述<!--combobox editor--></div>
						<div name="action" width="120" headerAlign="center" align="center" renderer="onActionRenderer" cellStyle="padding:0;">修改</div>
					</div>
				</div>
			</div>



			<div showCollapseButton="false" style="border:0px;" size="500">

				<!--Edit-->
				<div id="editPage" class="mini-tabs bg-toolbar" activeIndex="0" style="width:100%;height:100%;">
					<div title="数据项" url="admin.php?m=character&a=getcharacterByDataid" ></div>

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();

    function reload(){
    var grid = mini.get("datagrid1");
    grid.load();
    }

    function onSelectionChanged(e) {
        var grid = e.sender;
        var record = grid.getSelected();
        showTab(record);
    }

	function search() {
        var key = mini.get("key").getValue();
        var opt = document.getElementById("icons").value;
        // var key_arr = new Array()
        //     key_arr[0] =key;
        //     key_arr[1] =grid;
        //console.log(key);
        //console.log(opt);
        grid.load({ key: key,opt:opt});
        //grid.load({ opt: opt});
    }

    function onKeyEnter(e) {
        search();
    }
  
    function newRow() {
   	 mini.open({
            url: "admin.php?m=character&a=typeAdd",
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
        if (row) {
            mini.open({
                url: "admin.php?m=character&a=chacteredit&dataid="+row.dataid,
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
               url: "admin.php?m=character&a=y&dataid=" + row.id,
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
        var id = "tab$" + record.dataid;
        var tab = tabs.getTab(id);
        if (!tab) {
            tab = {};
            tab.name = id;
            tab.title = record.datacn;
            tab.showCloseButton = true;
            tab.url = "admin.php?m=character&a=getcharacterByDataid&dataid="+record.dataid;
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
    	//console.log(rows);
        if (rows.length > 0) {
            if (confirm("确定删除选中记录？")) {
                var ids = [];
                for (var i = 0, l = rows.length; i < l; i++) {
                    var r = rows[i];
                    ids.push(r.dataid);
                }
                var id = ids.join(',');
                console.log(id);
                grid.loading("操作中，请稍后......");
                $.ajax({
                	url: "admin.php?m=character&a=deltype",
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

</script>

<?php include $this->gettpl('footer');?>

