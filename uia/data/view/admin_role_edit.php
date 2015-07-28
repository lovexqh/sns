<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<?php if($roleName) { ?>

<div class="mainbox">
	<table class="opt">
    	<tr>
        	<td>角色名:</td>
        	<td><?php echo $roleName;?></td>
        </tr>
	</table>
</div>
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="306" rowspan="2" valign="top">
    	<h3 style="float: left" align="left">已选功能列表</h3>
    	<a class="mini-button" style="float: right" onclick="saveDate()">保存</a>
    	<div id="datagrid1" class="mini-datagrid" style="width:400px;height:400px;"
			url="admin.php?m=role&a=getFunList&RoleID=<?php echo $roleId;?>" idField="App_id" pageSize="50" allowCellEdit="true" allowCellSelect="true" multiSelect="true" >
			<div property="columns">
				<div type="checkcolumn"></div>
				<div field="App_name" width="60">
					功能名<!--textbox editor-->
				</div>
				<div field="app_alias" width="60">
					功能别名<!--textbox editor-->
				</div>
				<div header="权限" field="RoleExtend">
                	<div property="columns">
                    	<div type="checkboxcolumn" field="insert" trueValue="I" falseValue="" width="30">增加</div>
                    	<div type="checkboxcolumn" field="delete" trueValue="D" falseValue="" width="30">删除</div>
                    	<div type="checkboxcolumn" field="update" trueValue="U" falseValue="" width="30">修改</div>
                    	<div type="checkboxcolumn" field="select" trueValue="S" falseValue="" width="30">查看</div>
                    	<div type="checkboxcolumn" field="release" trueValue="R" falseValue="" width="30">发布</div>
                    	<div type="checkboxcolumn" field="audit" trueValue="A" falseValue="" width="30">审核</div>
                	</div>
            	</div>
				<!--
				<div field="RoleExtend" width="200">
					权限
				</div>
				-->
			</div>
		</div>		
      </td>
    <td width="111" height="86" align="center" valign="middle">&nbsp;</td>
    <td width="383" rowspan="2" valign="top">
      <h3 align="left">可选功能列表</h3>
        <div id="datagrid2" class="mini-datagrid" style="width:400px;height:400px;"
			url="admin.php?m=role&a=getOtherFunList&RoleID=<?php echo $roleId;?>" idField="App_id" pageSize="50" multiSelect="true" >
		<div property="columns">
			<div type="checkcolumn"></div>
			<div field="app_name" width="80">
				功能名<!--textbox editor-->
			</div>
			<div field="app_alias" width="80">
				功能别名<!--textbox editor-->
			</div>
			<div field="description" width="180">
				描述<!--textbox editor-->
			</div>
		</div>
      </td>
  </tr>
  <tr>
  	<td align="center" valign="top">
  		<a class="mini-button" iconCls="icon-add" style="text-align:left;" onclick="addFunc()"><<</a>
  		<br /><br />
		<a class="mini-button" iconCls="icon-remove" style="text-align:left;" onclick="removeFunc()">>></a>
  	</td>
  </tr>
</table>

<?php } else { ?>
<h3 align="left">选择角色进行编辑</h3>
<?php } ?>

<script type="text/javascript">	
    mini.parse();
    var grid1 = mini.get("datagrid1");
    var grid2 = mini.get("datagrid2");
    grid1.load();
    grid2.load();
    
    function saveDate(){
    	var data = grid1.getChanges();
        var json = mini.encode(data);
        grid1.loading("保存中，请稍后......");
        $.ajax({
            url: "admin.php?m=role&a=ajaxSavaData",
            data: { data: json },
            type: "post",
            success: function (text) {
                grid1.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText);
            }
        });
    }
    
    function addFunc(){
    	var rows = grid2.getSelecteds();
        if (rows.length > 0) {     
        	var funcs = [];
            for (var i = 0, l = rows.length; i < l; i++) {
                var r = rows[i];
                funcs.push(r.app_name);
            }
            grid2.loading("操作中，请稍后......");
            $.ajax({
            	url: "admin.php?m=role&a=ajaxAddFunc",
                type: 'POST',
    			data : {
    				RoleID : <?php echo $roleId;?>,
    				func : funcs
    			},
                success: function (text) {
                	grid1.reload();
                    grid2.reload();
                },
                error: function () {
                }
            });
        } else {
            alert("请选中至少一条记录");
        }
    }

    function removeFunc(){
    	var rows = grid1.getSelecteds();
        if (rows.length > 0) {     
        	var funcs = [];
            for (var i = 0, l = rows.length; i < l; i++) {
                var r = rows[i];
                funcs.push(r.App_name);
            }
            grid1.loading("操作中，请稍后......");
            $.ajax({
            	url: "admin.php?m=role&a=ajaxDelFunc",
                type: 'POST',
    			data : {
    				RoleID : <?php echo $roleId;?>,
    				func : funcs
    			},
                success: function (text) {
                	grid1.reload();
                    grid2.reload();
                },
                error: function () {
                }
            });
        } else {
            alert("请选中至少一条记录");
        }
    }
</script>
<?php include $this->gettpl('footer');?>