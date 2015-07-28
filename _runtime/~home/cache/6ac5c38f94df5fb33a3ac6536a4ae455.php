<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">
.person_Fans h2 a{
	font-family:宋体;
	font-weight:normal;
	font-size:12px;
}
.fans_person li{
	display:inline-block;
	float:left;
	margin-right:10px;
	margin-top:5px;
	margin-bottom:5px;
}
</style>
<div class="person_Fans app_line">
    <h2>关注<?php echo ($uname); ?>的人<span class="right"><a href="<?php echo U('home/space/follow',array('uid'=>$uid,'type'=>'follower'));?>">更多&gt;&gt;</a> </span></h2>
    <ul class="fans_person">
    <?php if(is_array($list["data"])): ?><?php $i = 0;?><?php $__LIST__ = $list["data"]?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li>
            <div class="userPic"><?php echo getUserSpace($vo["fid"],'','','{uavatar}') ?></div>
            <div class="clear"></div>
            <span style="display:block; line-height:22px; text-align:center;"><?php echo getUserSpace($vo["fid"],'','','{uname}') ?></span>
        </li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </ul>
    <div class="clear"></div>
</div>