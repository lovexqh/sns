<?php if (!defined('THINK_PATH')) exit();?><ul class="feed_list" <?php if(($insert)  ==  "1"): ?>id="feed_list"<?php endif; ?>>
<?php if(is_array($list)): ?><?php $i = 0;?><?php $__LIST__ = $list?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li class="lineD_btm" id="list_li_<?php echo ($vo["weibo_id"]); ?>">
  	<!-- 头像 -->
    <?php if($hidden_user != 1): ?><?php switch($type): ?><?php case "transpond":  ?><div class="head_pic"> <strong><?php echo ($vo["transpond"]); ?></strong> <a href="###"><?php echo L('tran_post');?></a> </div><?php break;?>
          <?php case "comment":  ?><div class="head_pic"> <strong><?php echo ($vo["comment"]); ?></strong> <a href="###"><?php echo L('reply');?></a> </div><?php break;?>
          <?php case "normal":  ?><div class="userPic">
              <?php echo getUserSpace($vo["uid"],'','','{uavatar}') ?>
            </div><?php break;?><?php endswitch;?><?php endif; ?>
    <div class="feed_c" <?php if($hidden_user == 1): ?>style="margin-left:10px;"<?php endif; ?>>
      <!-- 姓名、内容 -->
      <div class="msgCnt">
        <?php if($hidden_user != 1): ?><h4><?php echo getUserSpace($vo["uid"],'','','{uname}') ?><?php echo (getUserGroupIcon($vo["uid"])); ?>：</h4><?php endif; ?>
        <?php if($show == 'detail'): ?><p style="vertical-align:top;display:inline"><?php echo (format($vo["content"],true)); ?></p>
        <?php else: ?>
            <?php $detail_more = get_str_length($vo['content'], true) > 140 ? ' <a href="' . U('home/space/detail',array('id'=>$vo['weibo_id'])) . '" target="_blank">查看更多&raquo;</a>' : ''; ?>
        	<p style="vertical-align:top;display:inline"><?php echo (format(getShort($vo["content"],140,'...'),true)); ?><?php echo ($detail_more); ?></p><?php endif; ?>
      </div>
      <?php if( $vo['transpond_id'] ){ ?>
	  <?php if($vo['expend']){ ?>
          <div class="feed_quote"> <img class="q_ico_arrow" src="__THEME__/images/zw_img.gif" />
            <div class="q_tit"><img class="q_tit_l" src="__THEME__/images/zw_img.gif" /></div>
            <div class="q_con">
            <h4 style="vertical-align:top"><?php echo getUserSpace($vo["expend"]["uid"],'null','','@{uname}') ?><?php echo (getUserGroupIcon($vo["expend"]["uid"])); ?>：</h4>
            <?php $expend_detail_more = get_str_length($vo['expend']['content'], true) > 140 ? ' <a href="' . U('home/space/detail',array('id'=>$vo['expend']['weibo_id'])) . '" target="_blank">查看更多&raquo;</a>' : ''; ?>
            <p style="vertical-align:top;display:inline"><?php echo (format(getShort($vo["expend"]["content"],140,'...'),true)); ?><?php echo ($expend_detail_more); ?></p> 
            <div><?php echo ($vo["expend"]["expend"]); ?></div>
            <!--转发-->
            <div class="comment">
                <span class="right">
                <a href="javascript:void(0);" onClick="weibo.transpond(<?php echo ($vo['expend']['weibo_id']); ?>);"><?php echo L('source_tran');?>(<?php echo ($vo["expend"]["transpond"]); ?>)</a>
                <!--i class="vline">|</i>
                <a href="javascript:void(0);" rel="comment" minid="<?php echo ($vo['expend']['weibo_id']); ?>" ><?php echo L('source_comment');?>(<?php echo ($vo["expend"]["comment"]); ?>)</a-->
                </span>
                  <cite> <a href="<?php echo U('home/space/detail',array('id'=>$vo['expend']['weibo_id']));?>"><?php echo (friendlyDate($vo["expend"]["ctime"])); ?></a></cite> 
                  <?php echo L('come_from');?><cite><?php echo getFrom($vo['expend']['from'], $vo['expend']['from_data']);?></cite>
                  <?php if(($denounce)  ==  "1"): ?><?php if($mid != $vo['mid']): ?><i class="vline">|</i> <cite><a href="javascript:void(0)" onclick="denounce('weibo',<?php echo ($vo["expend"]["weibo_id"]); ?>,'<?php echo (addslashes(keyWordFilter(t(getShort($vo["expend"]["content"],140,'...'))))); ?>','<?php echo ($vo["expend"]["uid"]); ?>',<?php echo ($mid); ?>);"><?php echo L('report');?></a></cite><?php endif; ?><?php endif; ?>
            </div>
            <!--End 转发-->
            </div>
            <div class="q_btm" ><img class="q_btm_l" src="__THEME__/images/zw_img.gif" /></div>
          </div>
	   <?php }else{ ?>
          <div class="feed_quote"> <img class="q_ico_arrow" src="__THEME__/images/zw_img.gif" />
            <div class="q_tit"><img class="q_tit_l" src="__THEME__/images/zw_img.gif" /></div>
           <?php if($mid != $vo['mid']): ?><div class="q_con"><?php echo L('weibo_del');?></div><?php endif; ?>
            <div class="q_btm"><img class="q_btm_l" src="__THEME__/images/zw_img.gif" /></div>
          </div>
	   <?php } ?>
      <?php }else{ ?>
      <?php echo ($vo["expend"]); ?>
      <?php } ?>
      <div class="feed_c_btm"> 
          <span class="right">
          	<?php if($show == 'detail'): ?><?php echo Addons::hook('weibo_bottom_middle', array('weibo_id'=>$vo['weibo_id'], 'weibo'=>$vo));?><?php endif; ?>

            
			
            <!-- 删除微博end -->
			<!--转发链接start  -->
		<?php if($mid != $vo['uid']): ?><a href="javascript:void(0)" onclick="weibo.transpond(<?php echo ($vo["weibo_id"]); ?>)"><?php echo L('tran_post');?>(<?php echo ($vo["transpond"]); ?>)</a><?php endif; ?>
           <!-- 转发链接 end-->
            <?php if($mid != $vo['uid']){ ?>
            	<i class="vline">|</i>
	            <!-- 收藏 start -->
				<?php if($vo['is_favorited']){ ?>
					<?php if( ACTION_NAME == 'collection' ){ ?>
						<a href="javascript:void(0)" onclick="weibo.unFavorite(<?php echo ($vo["weibo_id"]); ?>,this)"><?php echo L('cancel_fav');?></a>
					<?php }else{ ?>
						<a href="javascript:void(0)" onclick="weibo.delFavorite(<?php echo ($vo["weibo_id"]); ?>,this)" title="<?php echo L('faved');?>"><?php echo L('cancel_fav');?></a>
					<?php } ?>
				<?php }else{ ?>
					<a href="javascript:void(0)" onclick="weibo.favorite(<?php echo ($vo["weibo_id"]); ?>,this)"><?php echo L('fav');?></a>
				<?php } ?>
				<!-- 收藏 end -->
            <?php } ?>
            <!-- 评论 start-->
            <?php if($mid != $vo['uid']): ?><i class="vline">|</i>
			<a href="javascript:void(0)" rel="comment" minid="<?php echo ($vo["weibo_id"]); ?>" ><?php echo L('comment');?>(<?php echo ($vo["comment"]); ?>)</a><?php endif; ?>
          	<!-- 评论end -->
          	 <?php if($ts['isSystemAdmin']){ ?>
          	 <i class="vline">&nbsp;</i>
            <a href="javascript:void(0)" onclick="ui.confirm(this,'<?php echo L('del_confirm');?>')" callback="weibo.deleted(<?php echo ($vo["weibo_id"]); ?>)"><?php echo L('del');?></a>
            <i class="vline">&nbsp;</i>
            <?php }else if($vo['uid'] == $mid){ ?>
            <i class="vline">&nbsp;</i>
			<a href="javascript:void(0)" onclick="ui.confirm(this,'<?php echo L('del_confirm');?>')" callback="weibo.deleted(<?php echo ($vo["weibo_id"]); ?>)"><?php echo L('del');?></a>
           
			<?php } ?>
          	<?php if($show == 'detail'): ?><?php echo Addons::hook('weibo_bottom_right', array($vo['weibo_id'], $vo));?><?php endif; ?>
		  </span> 
		  <cite><?php echo (friendlyDate($vo["ctime"])); ?></cite> 
		  <?php echo L('come_from');?><cite><?php echo getFrom($vo['from'], $vo['from_data']);?></cite>
		  <?php if(($denounce)  ==  "1"): ?><?php if($mid != $vo['uid']): ?><i class="vline">|</i> <cite><a href="javascript:void(0)" onclick="denounce('weibo',<?php echo ($vo["weibo_id"]); ?>,'<?php echo (addslashes(keyWordFilter(t(getShort($vo["content"],140,'...'))))); ?>','<?php echo ($vo["uid"]); ?>',<?php echo ($mid); ?>);"><?php echo L('report');?></a></cite><?php endif; ?><?php endif; ?>
	  </div>
      <div id="comment_list_<?php echo ($vo["weibo_id"]); ?>" style=""></div>
    </div>
  </li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
</ul>