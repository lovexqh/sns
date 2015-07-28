<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?> 
<script src="js/CommonLibs/EditForm.js" type="text/javascript"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="js/jquery-1.4.4.min.js" type="text/javascript"></script>
<!--Layout-->
<?php if($id != 0) { ?>
<table style="width:100%;height:100%;">
  <tr style="width:100%;height:50%;">
    <td style="width:50%;" valign="top">
    	<h3 align="left">管理员已选角色</h3>
    	<div id="datagrid1" class="mini-datagrid" style="width:100%;height:90%;" showPager="false"
			url="admin.php?m=admins&a=getRoleById&id=<?php echo $id;?>"  idField="id" pageSize="50" onSelectionChanged="onUserSelect" selectOnLoad="false" multiSelect="true" >
			<div property="columns">
				<div type="checkcolumn"></div>
				<div field="roleName" headerAlign="center" width="100">角色名称</div>
			</div>
		</div>		
    </td>
    <td valign="top">
    	<div style="margin-top:80px;">
    		<a class="mini-button" iconCls="icon-add" style="text-align:left;" onclick="addRole()"><<</a>
  			<br /><br />
			<a class="mini-button" iconCls="icon-remove" style="text-align:left;" onclick="delRole()">>></a>
    	</div>
  	</td>
    <td style="width:46%;" valign="top">
      <h3 align="left">管理员可选角色</h3>
        <div id="datagrid2" class="mini-datagrid" style="width:97%;height:90%;" showPager="false"
			url="admin.php?m=admins&a=getOtherRoleById&id=<?php echo $id;?>" idField="id" onSelectionChanged="onNoUserSelect" selectOnLoad="false" pageSize="50" multiSelect="true" >
			<div property="columns">
				<div type="checkcolumn"></div>
				<div field="roleName" headerAlign="center" width="100">角色名称</div>
			</div>
		</div>
      </td>
  </tr>
  <tr style="width:100%;height:42%;">
      <td style="width:100%;height:100%;" valign="top" colspan="3">
    	<h3 align="left">管理员功能</h3>
    	<div id="datagrid3" class="mini-datagrid" style="width:100%;height:91%;" showPager="false"
			url="admin.php?m=admins&a=getAppLimit&id=<?php echo $id;?>"  idField="checkcolumn" pageSize="50" multiSelect="true" >
				<div property="columns">
				<div field="appAlias" headerAlign="center" width="100">角色功能</div>
				<div field="roleExtend" headerAlign="center" width="100">功能权限</div>
			</div>
		</div>		
      </td
  </tr>
</table>
<?php } else { ?>
<h3 align="left">请选择管理员进行角色分配</h3>
<?php } ?>

<script type="text/javascript">
	
    mini.parse();
    var grid = mini.get("datagrid1");
    var grid1 = mini.get("datagrid2");
    var grid2 = mini.get("datagrid3");
    grid.load();
    grid1.load();
    grid2.load();
	
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
    
   
    function cancelRow() {
        grid.reload();
        editWindow.hide();
    }
    
    function addRole(){
    	var rows = grid1.getSelecteds();
    	if(rows.length>0){
    		var ids = [];
    		for(var i = 0 , l = rows.length; i<l ; i++){
    			var r = rows[i];
    			ids.push(r.roleID);
    		}
    		//var id = ids.join(',');
    		grid1.loading("操作中，请稍后......");
    		$.ajax({
    			url : "admin.php?m=admins&a=addRole&adminid=<?php echo $id;?>",
    			type : 'POST',
    			data : {
    				roleid : ids
    			},
    			success: function (text){
    				grid1.reload();
    				grid.reload();
    				grid2.reload();
    			},
    			error:function(){
    			}
    		});
    	}else{
    		alert("请选中一条记录");
    	}
    }
    
    function delRole(){
    	var rows = grid.getSelecteds();
    	if(rows.length>0){
    		var ids = [];
    		for(var i = 0 , l = rows.length; i<l ; i++){
    			var r = rows[i];
    			ids.push(r.roleID);
    		}
    		//var id = ids.join(',');
    		grid1.loading("操作中，请稍后......");
    		$.ajax({
    			url : "admin.php?m=admins&a=delRole&adminid=<?php echo $id;?>",
    			type : 'POST',
    			data : {
    				roleid : ids,
    			},
    			success: function (text){
    				grid1.reload();
    				grid.reload();
    				grid2.reload();
    			},
    			error:function(){
    			}
    		});
    	}else{
    		alert("请选中一条记录");
    	}
    }
    
    function onUserSelect(e) {
        
        var grids = e.sender;
        var record = grids.getSelected();
        if (record) {
        	grid2.load({roleid: record.RoleID });
        }
    }
    
    function onNoUserSelect(e){
    	var grids = e.sender;
        var record = grids.getSelected();
        if (record) {
        	grid2.load({roleid: record.RoleID });
        }
    }

    

</script>

