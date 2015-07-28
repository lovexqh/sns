<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo ($ts['site']['site_name']); ?>管理后台</title>
<link href="__PUBLIC__/admin/style.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/js/tbox/box.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	var _UID_ = "<?php echo ($uid); ?>";
	var _PUBLIC_ = "__PUBLIC__";	
</script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/tbox/box.js"></script>
</head>
<body>
<div class="so_main">
  <!-------- 节点列表 -------->
  <div class="page_tit">节点列表</div>
  <div class="Toolbar_inbox">
  	<div class="page right"><?php echo ($html); ?></div>
	<a href="<?php echo U('admin/User/addNode');?>" class="btn_a"><span>添加节点</span></a>
	<a href="javascript:void(0);" class="btn_a" onclick="deleteNode();"><span>删除节点</span></a>
  </div>
  <div class="list">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th style="width:30px;">
		<input type="checkbox" id="checkbox_handle" onclick="checkAll(this)" value="0">
    	<label for="checkbox"></label>
	</th>
    <!--<th class="line_l">ID</th>-->
    <th class="line_l">应用名</th>
    <th class="line_l">模块名</th>
    <th class="line_l">方法名</th>
    <th class="line_l">关联方法</th>
    <th class="line_l">说明</th>
    <th class="line_l">操作</th>
  </tr>
  <?php if(is_array($data)): ?><?php $i = 0;?><?php $__LIST__ = $data?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><tr overstyle='on' id="node_<?php echo ($vo['node_id']); ?>">
	    <td><input type="checkbox" name="checkbox" id="checkbox2" onclick="checkon(this)" value="<?php echo ($vo["node_id"]); ?>"></td>
	    <!--<td><?php echo ($vo["id"]); ?></td>-->
	    <td><?php echo ($vo["app_name"]); ?><br /><?php echo ($vo["app_alias"]); ?></td>
		<td><?php echo ($vo["mod_name"]); ?><br /><?php echo ($vo["mod_alias"]); ?></td>
	    <td><?php echo ($vo["act_name"]); ?><br /><?php echo ($vo["act_alias"]); ?></td>
	    <td>
	        <?php if(empty($vo['sub_node'])){ ?>无关联方法<?php } ?>
	    	<?php if(is_array($vo['sub_node'])): ?><?php $i = 0;?><?php $__LIST__ = $vo['sub_node']?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo2): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php echo ($vo2["act_name"]); ?><br /><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
	    </td>
	    <td><?php echo ($vo["description"]); ?><?php if(empty($vo['description'])){ ?>无<?php } ?></td>
	    <td>
			<a href="<?php echo U('admin/User/editNode', array('nid'=>$vo['node_id']));?>">编辑</a> 
	    	<a href="javascript:void(0);" onclick="deleteNode(<?php echo ($vo['node_id']); ?>);">删除</a>
		</td>
	  </tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
  </table>

  </div>
  <div class="list_btm">选择：<a href="javascript:void(0)">全部</a> - <a href="#">反选</a> - <a href="#">不选</a></div>
  <div class="Toolbar_inbox">
	<div class="page right"><?php echo ($html); ?></div>
    <a href="<?php echo U('admin/User/addNode');?>" class="btn_a"><span>添加节点</span></a>
	<a href="javascript:void(0);" class="btn_a" onclick="deleteNode();"><span>删除节点</span></a>
  </div>
</div>

<script>
	//鼠标移动表格效果
	$(document).ready(function(){
		$("tr[overstyle='on']").hover(
		  function () {
		    $(this).addClass("bg_hover");
		  },
		  function () {
		    $(this).removeClass("bg_hover");
		  }
		);
	});
	
	function checkon(o){
		if( o.checked == true ){
			$(o).parents('tr').addClass('bg_on') ;
		}else{
			$(o).parents('tr').removeClass('bg_on') ;
		}
	}
	
	function checkAll(o){
		if( o.checked == true ){
			$('input[name="checkbox"]').attr('checked','true');
			$('tr[overstyle="on"]').addClass("bg_on");
		}else{
			$('input[name="checkbox"]').removeAttr('checked');
			$('tr[overstyle="on"]').removeClass("bg_on");
		}
	}

	//获取已选择的ID数组
	function getChecked() {
		var ids = new Array();
		$.each($('table input:checked'), function(i, n){
			ids.push( $(n).val() );
		});
		return ids;
	}

	//删除节点
	function deleteNode(ids) {
		var length = 0;
		if(ids) {
		    length = 1;         
		}else {
		    ids    = getChecked();
		    length = ids.length;
		    ids    = ids.toString();
		}
		if(ids=='') {
		    ui.error('请先选择一个节点');
		    return ;
		}
		if(ids == '' || !confirm('删除成功后将无法恢复，确认继续？')) return false;
		
		$.post("<?php echo U('admin/User/doDeleteNode');?>", {ids:ids}, function(res){
			if(res == '1') {
				ids = ids.toString().split(',');
				for(i = 0; i < ids.length; i++) {
					$('#node_'+ids[i]).remove();
				}
				ui.success('删除成功');
			}else {
				ui.error('保存失败');
			}
		});
	}
	
	//搜索用户
	var isSearchHidden = <?php if(($type)  !=  "searchUser"): ?>1<?php else: ?>0<?php endif; ?>;
	function searchUser() {
		if(isSearchHidden == 1) {
			$("#searchUser_div").slideDown("fast");
			$(".searchUser_action").html("搜索完毕");
			isSearchHidden = 0;
		}else {
			$("#searchUser_div").slideUp("fast");
			$(".searchUser_action").html("搜索用户");
			isSearchHidden = 1;
		}
	}
</script>

<!-- 底部状态栏 start -->
<div region="south" id="bottomBar" class="bottomBar" style="text-align:center; clear:both; line-height: 30px; background:none; text-align: center;">
    <?php echo ($ts['site']['site_icp']); ?>
    <div id="site_analytics_code" style="display:none;">
        <?php echo (base64_decode($site["site_analytics_code"])); ?>
    </div>
</div>
<!-- 底部状态栏 end -->
<!-- 底部状态栏 end -->
</body>
</html>