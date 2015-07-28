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
    var ids = new Array();
    $.each($('table input:checked'), function(i, n){
        ids.push( $(n).val() );
    });
    return ids;
}

function deleteField(ids) {
	var length = 0;
    if(ids) {
        length = 1;         
    }else {
        ids    = getChecked();
        length = ids.length;
        ids    = ids.toString();
    }
    if(ids=='') {
        ui.error('请先选择一个字段');
        return ;
    }
    if(confirm('您将删除'+length+'条记录，删除后无法恢复，确定继续？')) {
        $.post("<?php echo U('admin/User/deleteField');?>",{ids:ids},function(res){
            if(res=='1') {
                ui.success('删除成功');
                removeItem(ids);
            }else {
                ui.error('删除失败');
            }
        });
    }
}

function removeItem(ids) {
    ids = ids.split(',');
    for(i = 0; i < ids.length; i++) {
        $('#field_'+ids[i]).remove();
    }
}

function changeform(form){
	$(form).submit();
}
</script>
<div class="so_main">
  <div class="page_tit">资料配置</div>
  <div class="Toolbar_inbox">
    <a href="<?php echo U('admin/user/addField');?>" class="btn_a"><span>添加字段</span></a>
    <a href="javascript:void(0);" class="btn_a" onclick="deleteField();"><span>删除字段</span></a>
    <div class="btn_a select_box"><span>
    <form action="<?php echo U('admin/user/setField');?>" method="post">
    <select name="utypeid" class="" onchange="changeform(this.form)">
    <option value="0">全部</option>
    <?php if(is_array($user_groups)): ?><?php $i = 0;?><?php $__LIST__ = $user_groups?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><option value="<?php echo ($vo["user_group_id"]); ?>" <?php if($_REQUEST['utypeid']==$vo[user_group_id]){echo "selected";} ?> ><?php echo ($vo["title"]); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </select>
    </form>
    </span></div>

  </div>
  <div class="list">
  	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th style="width:30px;">
        <input type="checkbox" id="checkbox_handle" onclick="checkAll(this)" value="0">
        <label for="checkbox"></label>
    </th>
    <th class="line_l">字段键名</th>
    <th class="line_l">字段名称</th>
    <th class="line_l">所属类型</th>
    <th class="line_l">所属模块</th>
    <th class="line_l">是否启用</th>
    <th class="line_l">空间显示</th>
    <th class="line_l">操作</th>
  </tr>
	<?php if(is_array($list)): ?><?php $i = 0;?><?php $__LIST__ = $list?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><tr overstyle='on' id="field_<?php echo ($vo["id"]); ?>">
	    <td><input type="checkbox" name="checkbox" id="checkbox2" onclick="checkon(this)" value="<?php echo ($vo["id"]); ?>"></td>
	    <td><?php echo ($vo["fieldkey"]); ?></td>
	    <td><?php echo ($vo["fieldname"]); ?></td>
        <td><?php echo empty($utype[$vo['utypeid']])?'全部':$utype[$vo['utypeid']]; ?></td>
	    <td><?php switch($vo["module"]): ?><?php case "base":  ?>基本资料<?php break;?><?php case "intro":  ?>个人情况<?php break;?><?php case "contact":  ?>联系方式<?php break;?><?php endswitch;?></td>
	    <td><?php if(($vo["status"])  ==  "1"): ?><a href="<?php echo U('admin/user/setStatus',array('id'=>$vo['id']));?>">是</a><?php else: ?><a href="<?php echo U('admin/user/setStatus',array('id'=>$vo['id']));?>">否</a><?php endif; ?></td>
	    <td><?php if(($vo["showspace"])  ==  "1"): ?><a href="<?php echo U('admin/user/setSpace',array('id'=>$vo['id']));?>">是</a><?php else: ?><a href="<?php echo U('admin/user/setSpace',array('id'=>$vo['id']));?>">否</a><?php endif; ?></td>
	    <td><a href="javascript:void(0);" onclick="deleteField('<?php echo ($vo['id']); ?>');">删除</a></td>
	  </tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
   </table>
  </div>
  <div class="Toolbar_inbox">
    <a href="<?php echo U('admin/user/addfield');?>" class="btn_a"><span>添加字段</span></a>
    <a href="javascript:void(0);" class="btn_a" onclick="deleteField();"><span>删除字段</span></a>
    <div class="btn_a select_box"><span>
    <form action="<?php echo U('admin/user/setField');?>" method="post">
    <select name="utypeid" class="" onchange="changeform(this.form)">
    <option value="0">全部</option>
    <?php if(is_array($user_groups)): ?><?php $i = 0;?><?php $__LIST__ = $user_groups?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><option value="<?php echo ($vo["user_group_id"]); ?>" <?php if($_REQUEST['utypeid']==$vo[user_group_id]){echo "selected";} ?> ><?php echo ($vo["title"]); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </select>
    </form>
    </span></div>
  </div>
</div>
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