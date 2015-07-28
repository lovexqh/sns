<?php if (!defined('THINK_PATH')) exit();?><?php if($visitor_list): ?><div class="clear"></div>
<style type="text/css">
.visitor_person li{
	padding:0;
	margin:5px 0;
	width:49%;
	float:left;
}
.visitor_person li a.userface{
	float:left;
	display:inline-block;
}
.visitor_person li a.fn{
	display:block;
	margin-left:60px;
	line-height:22px;
}
.visitor_person li .userPic span{
	display:block;
	margin-left:60px;
	line-height:22px;
}
</style>
<div class="person_Fans app_line">
    <h2><?php echo ($visitor_title); ?></h2>
    <ul class="visitor_person">
    	<?php if(is_array($visitor_list)): ?><?php $i = 0;?><?php $__LIST__ = $visitor_list?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li>
        <div class="userPic">
            <?php echo getUserSpace($vo["uid"],'','','{uavatar=m}') ?>
            <?php echo getUserSpace($vo["uid"],'fn','','{uname}') ?>
            <span><?php echo (date('m月d日',$vo["cTime"])); ?></span>
        </div>
        </li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </ul>
    <div class="clear pb10"></div>
    <ol>
        <li style="line-height:20px">今日访问量：<!--<a href="#">--><?php echo ($day_count); ?><!--</a>--><?php if(($day_count)  !=  "0"): ?><img src="__THEME__/member/images/new.gif" /><?php endif; ?></li>
        <!--li style="line-height:20px">历史访问量：<?php echo ($all_count); ?></li-->
    </ol>
    <div class="clear"></div>
</div><?php endif; ?>