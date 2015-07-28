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

function setStatus(app_id, status) {    
	$.post("<?php echo U('admin/Apps/doSetStatus');?>",{app_id:app_id,status:status},function(res){
		if(res=='1') {
			ui.success('保存成功');
			
			var statusArray = new Array("关闭","默认","可选");
			$('#status_'+app_id).html(statusArray[status]);
			statusArray[status] = '';
			var html = '';
			for (s in statusArray) {
				if(statusArray[s]=='') continue;
				html += '<a href="javascript:void(0);" onclick="setStatus('+app_id+','+s+')">设为'+statusArray[s]+'</a> ';
			}
			$('#setStatus_'+app_id).html(html);
		}else {
			ui.error('保存失败');
		}
	});
}

function move(app_id, direction) {
	var baseid  = direction == 'up' ? $('#'+app_id).prev().attr('id') : $('#'+app_id).next().attr('id');
    if(!baseid) {
        direction == 'up' ? ui.error('已经是最前面了') : ui.error('已经是最后面了');
    }else {
        $.post("<?php echo U('admin/Apps/doAppOrder');?>", {app_id:app_id, baseid:baseid}, function(res){
            if(res == '1') {
                //交换位置
                direction == 'up' ? $('#'+app_id).insertBefore('#'+baseid) : $("#"+app_id).insertAfter('#'+baseid);
                ui.success('保存成功');
            }else {
                ui.error('保存失败');
            }
        });
    }
}
</script>
<div class="so_main">
  <div class="page_tit">应用列表<span> - 已安装应用</span></div>
  <div class="Toolbar_inbox">
    <div class="page right"><?php echo ($html); ?></div>
    <form method="post">
    <div class="btn_a select_box"><span>
    <select name="publisher" class="dataPublisher" onchange="getSelect('publisher',0,this.value)">
    <option value="0">全部</option>
    <?php if(is_array($dataPublisher)): ?><?php $i = 0;?><?php $__LIST__ = $dataPublisher?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$val): ?><?php ++$i;?><?php $mod = ($i % 2 )?><option value="<?php echo ($val['DataCode']); ?>" <?php if($publisher == $val['DataCode']) echo 'selected'; ?>><?php echo ($val['DataName']); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </select>
    </span></div>
    </form>
  </div>
  <div class="list">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th class="line_l">应用图标</th>
    <th class="line_l">应用名称</th>
    <th class="line_l">应用别名</th>
    <th class="line_l">应用类型</th>
    <th class="line_l">作者</th>
    <th class="line_l">贡献者</th>
    <th class="line_l">状态</th>
    <th class="line_l">状态设置</th>
    <th class="line_l">排序</th>
    <th class="line_l">操作</th>
  </tr>
  
  <?php if(empty($data)) { ?>
    <tr><td>暂无待安装应用</td></tr>
  <?php } ?>
  <?php $status_alias = array('0'=>'关闭','1'=>'默认','2'=>'可选'); ?>
  
  <?php if(is_array($data)): ?><?php $i = 0;?><?php $__LIST__ = $data?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><tr overstyle='on' id="<?php echo ($vo['app_id']); ?>">
        <td><img src="<?php echo getAppIconUrl($vo['icon_url'],$vo['app_name']);?>" width="64" /></td>
        <td>
            <?php if(!empty($vo['homepage_url'])){ ?>
            <a href="<?php echo ($vo['homepage_url']); ?>" target="_blank"><?php echo ($vo['app_name']); ?></a>
            <?php }else { ?>
            <?php echo ($vo['app_name']); ?>
            <?php } ?>
        </td>
        <td><?php echo ($vo['app_alias']); ?></td>
        <td><?php echo ($vo['category']); ?></td>
        <td>
            <?php if(!empty($vo['author_homepage_url'])){ ?>
            <a href="<?php echo ($vo['author_homepage_url']); ?>" target="_blank"><?php echo ($vo['author_name']); ?></a>
            <?php }else { ?>
            <?php echo ($vo['author_name']); ?>
            <?php } ?>
        </td>
        <td><?php echo ($vo['contributor_name']); ?></td>
        <td id="status_<?php echo ($vo['app_id']); ?>"><?php echo ($status_alias[$vo['status']]); ?></td>
        <td id="setStatus_<?php echo ($vo['app_id']); ?>">
            <?php if(is_array($status_alias)): ?><?php $i = 0;?><?php $__LIST__ = $status_alias?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$s): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php if($key==$vo['status']) continue; ?>
	            <a href="javascript:void(0);" onclick="setStatus('<?php echo ($vo['app_id']); ?>','<?php echo ($key); ?>')">设为<?php echo ($s); ?></a><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
        </td>
        <td>
            <a href="javascript:void(0)" class="ico_top" onclick="move('<?php echo ($vo['app_id']); ?>','up');"><img src="__PUBLIC__/admin/images/zw_img.gif"></a>&nbsp;&nbsp;
            <a href="javascript:void(0)" class="ico_btm" onclick="move('<?php echo ($vo['app_id']); ?>','down');"><img src="__PUBLIC__/admin/images/zw_img.gif"></a>
        </td>
        <td>
            <?php $uninstall_href = U('admin/Apps/uninstall',array('app_id'=>$vo['app_id']));
	        $uninstall_alert_1 = '卸载程序将会移除所有的应用数据，且无法恢复，确定继续?';
	        $uninstall_alert_2 = '卸载后, 您将丢失所有的应用数据, 强烈建议您备份数据库后再卸载应用!! 确定继续?'; ?>
            <a href="<?php echo U('admin/Apps/edit',array('app_id'=>$vo['app_id']));?>">编辑</a> 
            <a href="javascript:void(0);" onclick="if(confirm('<?php echo ($uninstall_alert_1); ?>') && confirm('<?php echo ($uninstall_alert_2); ?>')) location.href='<?php echo ($uninstall_href); ?>';return false;">卸载</a>
        </td>
      </tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
  </table>
  </div>
  <div class="Toolbar_inbox">
    <div class="page right"><?php echo ($html); ?></div>
    <span>&nbsp;</span>
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