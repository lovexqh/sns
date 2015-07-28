<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<?php if($id) { ?>

<div class="mainbox">
	<table class="opt">
    	<tr>
        	<th>服务名：<?php echo $service['serviceID'];?></th>
        	<td>&nbsp;</td>
        	<th>方法名：<?php echo $service['method'];?></th>
        </tr>
        <tr>
        	<th>服务描述：</th>
        	<th><?php echo $service['serviceDes'];?></th>
        	<th></th>
        </tr>
	</table>
</div>
<table style="width:100%;height:83%;" border="0" cellspacing="0" cellpadding="0">
	<tr>
  		<td style="width:55%;height:100%;" valign="top">
   			<h3 style="float: left" align="left">输入参数列表</h3>
   			<div style="text-align:right;">
				<a class="mini-button" iconCls="icon-add" style="text-align:center;" onclick="addInput()">增加</a>
				<a class="mini-button" iconCls="icon-remove" style="text-align:center;" onclick="delInput()">删除</a>
			</div>
    		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:93%;margin: auto;"
				url="admin.php?m=service&a=getInputParamList&id=<?php echo $id;?>" idField="id" pageSize="50" allowCellEdit="true" allowCellSelect="true" multiSelect="true" >
				<div property="columns">
					<div type="checkcolumn"></div>
					<div field="csmc" width="60">参数名称</div>
					<div field="csms" width="80">参数描述</div>
					<div field="bt" width="30" align="center">是否必填</div>
				</div>
			</div>		
   		 </td>
   		 <td>&nbsp;&nbsp;</td>
		 <td style="width:55%;height:100%;" valign="top">
      		<h3 style="float:left;" align="left">输出参数列表</h3>
      		<div style="text-align:right;">
				<a class="mini-button" iconCls="icon-add" style="text-align:center;" onclick="addOutput()">增加</a>
				<a class="mini-button" iconCls="icon-remove" style="text-align:center;" onclick="delOutput()">删除</a>
			</div>
        	<div id="datagrid2" class="mini-datagrid" style="width:100%;height:93%;margin: auto;"
				url="admin.php?m=service&a=getOutputParamList&id=<?php echo $id;?>" idField="id" pageSize="50" multiSelect="true" >
				<div property="columns">
					<div type="checkcolumn"></div>
					<div field="csmc" width="60">参数名称</div>
					<div field="csms" width="80">参数描述</div>
				</div>
			</div>
      	</td>
	</tr>
</table>

<?php } else { ?>
<h3 align="left">选择服务查看参数</h3>
<?php } ?>

<script type="text/javascript">	
    mini.parse();
    var grid1 = mini.get("datagrid1");
    var grid2 = mini.get("datagrid2");
    grid1.load();
    grid2.load();
    
    function addInput(){
    	mini.open({
            url: "admin.php?m=service&a=InputParamAdd&id=<?php echo $id;?>",
            title: "添加输入参数", width: 300, height: 240,
            onload: function () {
                var iframe = this.getIFrameEl();
                var data = { action: "new"};
                iframe.contentWindow.SetData(data);
            },
            ondestroy: function (action) {
                grid1.reload();
            }
        });
    }
    
    function delInput(){
    	var rows = grid1.getSelecteds();
        if (rows.length > 0) {
            if (confirm("确定删除选中记录？")) {
                var ids = [];
                for (var i = 0, l = rows.length; i < l; i++) {
                    var r = rows[i];
                    ids.push(r.id);
                }
                var id = ids.join(',');
                grid1.loading("操作中，请稍后......");
                $.ajax({
                	url: "admin.php?m=service&a=delParam",
                	type: 'POST',
    				data : {
    					id : id
    				},
                    success: function (text) {
                        grid1.reload();
                    },
                    error: function () {
                    }
                });
            }
        } else {
            alert("请选中一条记录");
        }
    }
    
    function delOutput(){
    	var rows = grid2.getSelecteds();
        if (rows.length > 0) {
            if (confirm("确定删除选中记录？")) {
                var ids = [];
                for (var i = 0, l = rows.length; i < l; i++) {
                    var r = rows[i];
                    ids.push(r.id);
                }
                var id = ids.join(',');
                grid2.loading("操作中，请稍后......");
                $.ajax({
                	url: "admin.php?m=service&a=delParam",
                	type: 'POST',
    				data : {
    					id : id
    				},
                    success: function (text) {
                        grid2.reload();
                    },
                    error: function () {
                    }
                });
            }
        } else {
            alert("请选中一条记录");
        }
    }
    
    function addOutput(){
    	mini.open({
            url: "admin.php?m=service&a=OutputParamAdd&id=<?php echo $id;?>",
            title: "添加输出参数", width: 300, height: 180,
            onload: function () {
                var iframe = this.getIFrameEl();
                var data = { action: "new"};
                iframe.contentWindow.SetData(data);
            },
            ondestroy: function (action) {
                grid2.reload();
            }
        });
    }
    
</script>
<?php include $this->gettpl('footer');?>