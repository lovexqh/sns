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
<script type="text/javascript">
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

function move(id, direction) {
	var baseid  = direction == 'up' ? $('#'+id).prev().attr('id') : $('#'+id).next().attr('id');
    if(!baseid) {
        direction == 'up' ? ui.error('已经是最前面了') : ui.error('已经是最后面了');
    }else {
        $.post("<?php echo U('admin/Plugin/doMedalOrder');?>", {id:id, baseid:baseid}, function(res){
            if(res == '1') {
                //交换位置
                direction == 'up' ? $('#'+id).insertBefore('#'+baseid) : $("#"+id).insertAfter('#'+baseid);
                ui.success('保存成功');
            }else {
                ui.error('保存失败');
            }
        });
    }
}
</script>
<?php foreach($list as $type=>$value){ ?>
	<div class="so_main">
  <div class="page_tit"><?php echo ($value['name']); ?></div>
  <div class="Toolbar_inbox">
  </div>
  <div class="list">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th class="line_l">插件名称</th>
    <th class="line_l">作者</th>    
    <th class="line_l">版本号</th>
    <th class="line_l">描述</th>
    <th class="line_l">操作</th>
	<th class="line_l">卸载</th>
  </tr>
  
  <?php if(empty($value['data'])) { ?>
    <tr>
    <td>暂无<?php echo ($value['name']); ?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
	<td></td>
    
    </tr>
  <?php } ?>
  
  <?php if(is_array($value["data"])): ?><?php $i = 0;?><?php $__LIST__ = $value["data"]?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><tr overstyle='on' id="<?php echo ($vo['name']); ?>">
        <td><?php echo ($vo['pluginName']); ?></td>
        <td><?php echo ($vo['author']); ?>
        	<?php if(isset($vo['site'])){ ?>
        	<br /> <a href="<?php echo ($vo['site']); ?>"><?php echo ($vo['site']); ?></a>
        	<?php } ?>
        </td>
        <td><?php echo ($vo['version']); ?></td>
        <td><?php echo ($vo['info']); ?></td>
        <td>
          <?php if($type == "valid"){ ?>
         	<?php $stop_href = U('admin/Addons/stopAddon',array('addonId'=>$vo['addonId']));
	        $stop_alert_1 = '确定停用该插件?'; ?>
            <a href="javascript:void(0);" onclick="if(confirm('<?php echo ($stop_alert_1); ?>') /*&& confirm('<?php echo ($stop_alert_2); ?>')*/) location.href='<?php echo ($stop_href); ?>';return false;">停用</a>
          <?php }else{ ?>
            <?php $install_href = U('admin/Addons/startAddon',array('name'=>$vo['name']));
	        if($vo['sqlfile']){
	        	$install_alert_1 = '初次启用该插件时将会进行sql操作，且无法恢复，强烈建议您备份数据库后再启用，确定继续?';
	        }else{
	        	$install_alert_1 = '确定启用该插件？';
	        } ?>
			<?php if(isset($vo['addonId'])){ ?>
			<a href="javascript:void(0);" onclick="if(confirm('<?php echo ($install_alert_1); ?>')) location.href='<?php echo ($install_href); ?>';return false;">启用</a>
			 <?php }else{ ?>
			 <a href="javascript:void(0);" onclick="if(confirm('<?php echo ($install_alert_1); ?>')) location.href='<?php echo ($install_href); ?>';return false;">安装</a>
			 <?php } ?>
            
         <?php } ?>

         <?php if(isset($vo['admin']) && $type == "valid"){ ?>
          <?php $href = U('admin/Addons/admin',array('pluginid'=>$vo['addonId'])); ?>
            <a href="javascript:void(0);" onclick="location.href='<?php echo ($href); ?>';return false;">管理</a>
		  <?php } ?>
		</td>
        <td>
		<?php if(isset($vo['addonId'])){ ?>
		    <?php $uninstall_href = U('admin/Addons/uninstallAddon',array('name'=>$vo['name']));
	        $uninstall_alert_1 = '确定卸载该插件?';
	        $uninstall_alert_2 = '卸载后, 您将丢失所有插件相关的数据, 强烈建议您备份数据库后再卸载!! 确定继续?'; ?>
            <a href="javascript:void(0);" onclick="if(confirm('<?php echo ($uninstall_alert_1); ?>') && confirm('<?php echo ($uninstall_alert_2); ?>') ) location.href='<?php echo ($uninstall_href); ?>';return false;">卸载</a>
		<?php } ?>
		</td>
      </tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
  </table>
    <div class="Toolbar_inbox">
  </div>
  </div>
</div>
<?php } ?>
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