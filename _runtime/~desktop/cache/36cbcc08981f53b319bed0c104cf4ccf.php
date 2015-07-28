<?php if (!defined('THINK_PATH')) exit();?><!-- 桌面提醒事宜 start -->
<div id="dock_right_remind" class="dock_right_remind" style="position:absolute; ">
	<div class="contains">
    
    	<div class="right">
        	<div class="right_contains">
            	<ul>
                <?php if(is_array($maxmenu)): ?><?php $i = 0;?><?php $__LIST__ = $maxmenu?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><li>
                    	<i <?php if($default == $key): ?>class="arrow"<?php endif; ?>></i>
                    	<a href="javascript:;" class="icon_<?php echo ($vo["key"]); ?> <?php if($default == $key): ?>focus<?php endif; ?>" sx="<?php echo ($vo["key"]); ?>" title="<?php echo ($vo["title"]); ?>"></a>
                    </li><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
                </ul>
                <div class="notice_remind">我的消息</div>
            </div>
        	<div class="right_background" style="height: 76px;"></div>
            <div class="clearfix"></div>
        </div>
        
        <?php if(is_array($maxmenu)): ?><?php $i = 0;?><?php $__LIST__ = $maxmenu?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><div id="remind_<?php echo ($vo["key"]); ?>" width="<?php echo ($vo["width"]); ?>" height="<?php echo ($vo["height"]); ?>" class="left" style="<?php if($default != $key): ?>display:none<?php endif; ?>">
            <div class="remind_panel">
                <div class="left_contains remind_height_in">数据载入中...</div>
                <div class="bg"></div>
                <a class="left_close">X</a>
            </div>
        </div><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
        
    </div>
</div>

<script language="javascript">
$(document).ready(function(e) {
	$remind = $('#dock_right_remind');
	
	//关闭按钮
    $remind.find(".left_close").click(function(e) {
        $(this).parent().parent().hide();
		$remind.find('.right_contains li').each(function(i, e) {
            $(this).find('i').removeClass('arrow');
			$(this).find('a').removeClass('focus');
        });
    });
	
	//右侧切换按钮
	$(".right_contains li").click(function(e) {
		$(this).parent().find('i').removeClass('arrow');
		$(this).children('i').addClass('arrow');
		$(this).parent().find('li').each(function(index, element) {
            $(this).children('a').removeClass('focus');
        });
		$(this).children('a').addClass('focus');
		//隐藏所有层信息
		$remind.find('.left').hide();
		var _panel = $(this).children('a').attr('sx');
        $("#remind_"+_panel).fadeIn('fast',function(){
			getPanelData({
				panel:_panel,
				width:$(this).attr('width'),
				height:$(this).attr('height')	
			});	
		});
		$('.notice_remind').html($(this).children('a').attr('title'));
    });
	
	var dp = '<?php echo ($default); ?>';
	if(dp!=''){
		getPanelData({
			panel:dp,
			width:parseInt("<?php echo ($maxmenu[$default]['width']); ?>"),
			height:parseInt("<?php echo ($maxmenu[$default]['height']); ?>")
		});
	}
});
//获取面板里面的数据信息
function getPanelData(){
	var panel,width,height;
	var arg = arguments;
	if (typeof(arg[0]) == 'object'){
		panel = arg[0].panel;
		width = (typeof(arg[0].width)=='undefined'?400:parseInt(arg[0].width));
		height = (typeof(arg[0].height)=='undefined'?300:parseInt(arg[0].height));
	}
	$.ajax({
		type: "POST",
		url: U('home/Public/displayAddons',['addon=Message','hook=getPanelData']),
		data: {
			panel:panel
		},
		dataType: "html",
		success: function (data) {
			var obj= $('#remind_'+panel);
			obj.find('.left_contains').html(data);
			//设置高度
			var h = obj.find('.left_contains').height();
			//if(h>height) height = h;
			obj.width(width).height(height+20);
			obj.find('.bg').width(width).height(height+20);
			obj.find('.left_contains').width(width-20).height(height);
			obj.find('.scroll_panel').height(height-40).jScrollPane();
		},
		error: function (msg) {
		    //Alert(msg);
		},
		complete: function(){
			//左侧tab页面切换
			$('.top_title li').click(function(e) {
				var id = $(this).children('a').attr('sx');
				var panel_contains = $(this).parent().parent();
				$(this).parent().find('li').removeClass('onfocs');
				$(this).addClass('onfocs');
				panel_contains.find('.scroll_panel').each(function(){
					if($(this).attr('id')!=	"jsp_"+id){
						$(this).hide()	
					}else{
						$(this).fadeIn(0).jScrollPane('update');
					}
				});
			});
		}
	});
}
</script>