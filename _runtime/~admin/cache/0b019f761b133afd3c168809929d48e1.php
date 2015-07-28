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
  <div class="page_tit">用户类型管理</div>
  <div class="Toolbar_inbox">
  	<div class="page right"><?php echo ($html); ?></div>
    <?php if(UC_SYNC){ ?>
    <a href="javascript:void(0);" class="btn_a" onclick="sync();"><span>同步类型</span></a>
    <?php }else{ ?>
  	<a href="javascript:void(0);" class="btn_a" onclick="add();"><span>添加类型</span></a>
	<a href="javascript:void(0);" class="btn_a" onclick="del();"><span>删除类型</span></a>
    <?php } ?>
  </div>
  
  <div class="list">
  <table id="user_group_list" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th style="width:30px;">
		<input type="checkbox" id="checkbox_handle" onclick="checkAll(this)" value="0">
    	<label for="checkbox"></label>
	</th>
    <th class="line_l">ID</th>
    <th class="line_l">用户类型名称</th>
    <th class="line_l">用户类型图标</th>
    <?php if(!UC_SYNC){ ?>
    <th class="line_l">创建时间</th>
    <th class="line_l">操作</th>
    <?php }else{ ?>
    <th class="line_l">是否开放注册</th>
    <?php } ?>
  </tr>
  <?php if(is_array($user_groups)): ?><?php $i = 0;?><?php $__LIST__ = $user_groups?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><tr overstyle='on' id="group_<?php echo ($vo["user_group_id"]); ?>">
	    <td><input type="checkbox" name="checkbox" id="checkbox2" onclick="checkon(this)" value="<?php echo ($vo["user_group_id"]); ?>"></td>
	    <td><?php echo ($vo["user_group_id"]); ?></td>
	    <td>
			<div id="user_group_name_<?php echo ($vo["user_group_id"]); ?>" style="float:left"><?php echo ($vo["title"]); ?></div>
		</td>
		<td id="user_group_icon_<?php echo ($vo["user_group_id"]); ?>"><?php if($vo['icon'] != ""){ ?> <img src="__THEME__/member/images/usergroup/<?php echo ($vo["icon"]); ?>"> <?php }else{ ?> &nbsp;<?php } ?></td>
	    <?php if(!UC_SYNC){ ?>
        <td><?php echo (date("Y-m-d H:i",$vo["ctime"])); ?></td>
	    <td>
			<a href="javascript:void(0);" onclick="edit(<?php echo ($vo['user_group_id']); ?>);">编辑</a> 
	    	<a href="javascript:void(0);" onclick="del(<?php echo ($vo['user_group_id']); ?>);">删除</a>  
		</td>
        <?php }else{ ?>
        <td><?php echo $vo['isreg']>0?"<img src='__THEME__/member/images/ico_success.gif' />":"<img src='__THEME__/member/images/ico_error.gif' />" ?></td>
        <?php } ?>
	  </tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
  </table>

  </div>
  <div class="Toolbar_inbox">
	<div class="page right"><?php echo ($html); ?></div>
  	<?php if(UC_SYNC){ ?>
    <a href="javascript:void(0);" class="btn_a" onclick="sync();"><span>同步类型</span></a>
    <?php }else{ ?>
  	<a href="javascript:void(0);" class="btn_a" onclick="add();"><span>添加类型</span></a>
	<a href="javascript:void(0);" class="btn_a" onclick="del();"><span>删除类型</span></a>
    <?php } ?>
  </div>
  
  <?php if(UC_SYNC){ ?>
  	<div>
    	<p style="color:red">注：用户类型的数据管理，请到统一身份模块下的角色管理中进行更改。</p>
    </div>
  <?php } ?>
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

	//获取已选择用户的ID数组
	function getChecked() {
		var gids = new Array();
		$.each($('input:checked'), function(i, n){
			gids.push( $(n).val() );
		});
		return gids;
	}

	//添加类型
	function add() {
		ui.box.load("<?php echo U('admin/User/editUserGroup');?>", {title:'添加类型'});
	}
	
	//编辑类型
	function edit(gid) {
		ui.box.load("<?php echo U('admin/User/editUserGroup');?>&gid="+gid, {title:'编辑类型'});
	}
	
	//删除类型
	function del(gid) {
		gid = gid ? gid : getChecked();
		gid = gid.toString();
		if(gid == '') return false;

		//检查类型是否为空
		$.post("<?php echo U('admin/User/isUserGroupEmpty');?>", {gid:gid}, function(res){
			if(res == '0') {
				ui.error('所选的用户类型不为空，不允许删除');
				return false;
			}
			
			//用户确认删除
			if(!confirm('删除成功后将无法恢复，确认继续？')) return false;
			
			//提交删除
			$.post("<?php echo U('admin/User/doDeleteUserGroup');?>", {gid:gid}, function(res){
			if(res == '1') {
				gid = gid.split(',');
				$.each(gid, function(i,n){
					$('#group_'+n).remove();
				});
				
				ui.success('删除成功');
			}else {
				ui.error('删除失败');
			}
		});
		});
		
		
	}
	
	//同步类型数据
	function sync(){
		//提交同步
		$.post("<?php echo U('admin/User/doSyncUserGroup');?>", {}, function(res){
			if(res == '1') {
				ui.success('同步成功');
				setTimeout("location.reload()",3000);}else {
				ui.error('同步失败');
			}
		});
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