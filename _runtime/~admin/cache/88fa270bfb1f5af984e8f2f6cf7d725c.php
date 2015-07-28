<?php if (!defined('THINK_PATH')) exit();?><?php if($isAjax): ?><?php echo Addons::addonsHook($name,$page);?>
<?php else: ?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
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
		<div class="page_tit"><?php echo ($addonName); ?></div>
		<?php if($menu): ?><?php if(is_array($menu)): ?><div class="tit_tab">
				  	<ul>
				  	<?php if(is_array($menu)): ?><?php $i = 0;?><?php $__LIST__ = $menu?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li><a href="<?php echo Addons::adminPage($key);?>" <?php if($page == $key){ ?>class="on"<?php } ?> ><?php echo ($vo); ?></a></li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
				    </ul>
				</div><?php endif; ?>
			<?php echo Addons::addonsHook($name,$page,array(),true);?>
		<?php else: ?>
			<span>该插件无管理面板</span><?php endif; ?>
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
</html><?php endif; ?>