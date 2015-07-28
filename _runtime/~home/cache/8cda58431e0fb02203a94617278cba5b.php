<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">
.person_Follow h2 a{
	font-family:宋体;
	font-weight:normal;
	font-size:12px;
}
.person_Follow li{
	display:inline-block;
	float:left;
	margin-right:10px;
	margin-top:5px;
	margin-bottom:5px;
}
.person_Follow .interest_info{
	display:block;	
}
.person_Follow .uname a{
	line-height:22px;
}
</style>
<div class="person_Follow app_line">
    <h2><?php echo ($uname); ?>关注的人<span class="right"><a href="<?php echo U('home/space/follow',array('uid'=>$uid,'type'=>'following'));?>">更多&gt;&gt;</a> </span></h2>
    <ul class="interest_person">
    <?php if(is_array($list["data"])): ?><?php $i = 0;?><?php $__LIST__ = $list["data"]?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li>
            <div class="userPic"><?php echo getUserSpace($vo["fid"],'','','{uavatar}') ?></div>
            <div class="clear"></div>
            <div class="interest_info">
                <div class="uname"><?php echo getUserSpace($vo["fid"],'','','{uname}') ?></div>
                <?php if($vo['fid'] != $mid) { ?>
                <?php if($vo['followState'] == 'unfollow'){ ?>
                <a class="guanzhu" onclick="spaceFollow($(this),<?php echo ($vo['fid']); ?>)" href="javascript:void(0);">加关注</a>
                <?php }else{ ?>
                已关注
                <?php } ?>
                <?php } ?>
            </div>
        </li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </ul>
</div>
<script>
//空间关注操作
function spaceFollow(obj,uid){
    var html = '';
    var type="dofollow";
    $('#follow_state').html( '<img src="'+ _THEME_+'/images/icon_waiting.gif" width="15">' );
    $.post( U('weibo/Operate/follow') ,{uid:uid,type:type},function(txt){
        if(txt=='12'){
            obj.parent().html('已关注').addClass('cGray2');
        }else if(txt=='13'){
            obj.parent().html('关注您的').addClass('cGray2');
        }else if(txt=='00'){
            ui.error('对方不允许你关注');
        }else{
            ui.error('系统错误请稍后再试');
        }
    });
}

</script>