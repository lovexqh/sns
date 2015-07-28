<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?> 
<script src="js/CommonLibs/EditForm.js" type="text/javascript"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="js/jquery-1.4.4.min.js" type="text/javascript"></script>
<!--Layout-->
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="390px" rowspan="2" valign="top">
    	<h3 align="left">身份已选角色</h3>
    	<div id="datagrid1" class="mini-datagrid" style="width:100%;height:250px;"
			url="admin.php?m=identity&a=getRoleByIdentity&identityId=<?php echo $identityId;?>"  idField="IdentityID" pageSize="50" onSelectionChanged="onUserSelect" selectOnLoad="false" multiSelect="true" >
			<div property="columns">
				<div type="checkcolumn"></div>
				<div field="RoleName" headerAlign="center" width="100">角色名称</div>
			</div>
		</div>		
      </td>
    <td width="20" height="86" align="center" valign="middle">&nbsp;</td>
    <td width="390px" rowspan="2" valign="top">
      <h3 align="left">身份可选角色</h3>
        <div id="datagrid2" class="mini-datagrid" style="width:97%;height:250px;"
			url="admin.php?m=identity&a=getOtherRoleByIdentity&identityId=<?php echo $identityId;?>" idField="IdentityID" onSelectionChanged="onNoUserSelect" selectOnLoad="false" pageSize="50" multiSelect="true" >
			<div property="columns">
				<div type="checkcolumn"></div>
				<div field="RoleName" headerAlign="center" width="100">角色名称</div>
			</div>
		</div>
      </td>
  </tr>
  <tr>
  	<td align="center" valign="top">
  		<a class="mini-button" iconCls="icon-add" style="text-align:left;width: 30" onclick="addRole()"><<</a>
  		<br /><br />
		<a class="mini-button" iconCls="icon-remove" style="text-align:left;" onclick="delRole()">>></a>
  	</td>
  </tr>
   <tr>
    <td width="350px" rowspan="2" valign="top" colspan="3">
    	<h3 align="left">身份功能</h3>
    	<div id="datagrid3" class="mini-datagrid" style="width:100%;height:220px;"
			url="admin.php?m=identity&a=getAppByIdentity&identityId=<?php echo $identityId;?>"  idField="checkcolumn" pageSize="50" multiSelect="true" >
			<div property="columns">
				<div field="App_alias" headerAlign="center" width="60">角色功能</div>
				<div field="RoleExtend" headerAlign="center" width="100">功能权限</div>
			</div>
		</div>		
      </td>
  </tr>
</table>

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
    			ids.push(r.RoleID);
    		}
    		grid1.loading("操作中，请稍后......");
    		$.ajax({
    			url : "admin.php?m=identity&a=addRole",
    			type : 'POST',
    			data : {
    				roleid : ids,
    				identityId : <?php echo $identityId;?>
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
    			ids.push(r.RoleID);
    		}
    		//var id = ids.join(',');
    		grid1.loading("操作中，请稍后......");
    		$.ajax({
    			url : "admin.php?m=identity&a=delRole",
    			type : 'POST',
    			data : {
    				roleid : ids,
    				identityId : <?php echo $identityId;?>
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

