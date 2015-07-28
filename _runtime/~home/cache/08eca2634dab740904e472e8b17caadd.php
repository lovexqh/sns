<?php if (!defined('THINK_PATH')) exit();?><script src="__PUBLIC__/js/desktop/jquery.imagePreview.1.0.js" type="text/javascript"></script>
<script>
	var photo_preview = '<?php echo ($photo_preview); ?>';
	var photo_num = '<?php echo ($photo_num); ?>'
</script>
<script type="text/javascript">
$(document).ready(function(e) {
	if(photo_preview){
		$("a.preview").preview({
			ref:'rel'	
		});	
	}
});
</script>
 
<dl class="personal_photo">
	<div class="personal_photo_more">
	<a href="<?php echo U('photo/Upload/flash',array('iframe'=>'yes'));?>" target="_blank" title="上传照片"><img
		src="../Public/images/gd.jpg" targer="_blank" border="0"></a>
		
	</div>

	<?php if(is_array($photo_list)): ?><?php $i = 0;?><?php $__LIST__ = $photo_list?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$li): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php if($key == 0): ?><dd class="personal_photo_big"><a
		href="<?php echo U('photo/Index/photo',array(id=>$li['id'],aid=>$li['albumId'],uid=>$li['userId']));?>#show_pic"
		target="_blank"
		rel="__ROOT__/thumb.php?w=400&h=400&t=f&url=<?php echo SITE_URL;?>/data/uploads/<?php echo ($li["savepath"]); ?>"
		title="<?php echo ($li["name"]); ?>" class="preview"><img
		src="__ROOT__/thumb.php?w=222&h=154&url=<?php echo SITE_URL;?>/data/uploads/<?php echo ($li["savepath"]); ?>" /></a></dd>

	<?php else: ?>
	<dd class="photo_sml_zp"><a
		href="<?php echo U('photo/Index/photo',array(id=>$li['id'],aid=>$li['albumId'],uid=>$li['userId']));?>#show_pic"
		target="_blank"
		rel="__ROOT__/thumb.php?w=400&h=400&t=f&url=<?php echo SITE_URL;?>/data/uploads/<?php echo ($li["savepath"]); ?>"
		title="<?php echo ($li["name"]); ?>" class="preview"><img
		src="__ROOT__/thumb.php?w=126&h=76&url=<?php echo SITE_URL;?>/data/uploads/<?php echo ($li["savepath"]); ?>" /></a></dd><?php endif; ?>
	<?php if($key == 4): ?><div class="photo_sml_jg"></div><?php endif; ?><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
	<script>
	<!--
	<?php if($isowen == 1): ?>var pclass = "wu";
	<?php else: ?>
	var pclass = "none";<?php endif; ?>
	for(var i=photo_num;i<9;i++){
		if(i==0){
			document.write("<dd><ul><li class='photo_big_"+pclass+"'><?php if($mid == $uid ): ?><a href='<?php echo U('photo/Upload/all_index', array('uid'=>$uid));?>' title='添加照片' target='_blank'></a><?php endif; ?></li></ul></dd>");
		}else{
			document.write("<dd><ul><?php if($mid == $uid ): ?><a href='<?php echo U('photo/Upload/all_index', array('uid'=>$uid));?>  class='right' title='添加照片' target='_blank''><?php endif; ?><li class='photo_sml_"+pclass+"'></li><?php if($mid == $uid ): ?></a><?php endif; ?></ul></dd>");
		}
		if(i==4){
			document.write("<div class='photo_sml_jg'></div>");
		}
	}
	//-->
	</script>
</dl>