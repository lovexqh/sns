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
<div class="so_main">

  <div id="search_div" <?php if(($isSearch)  !=  "1"): ?>style="display:none;"<?php endif; ?>>
    <div class="page_tit">搜索动态 [ <a href="javascript:void(0);" onclick="searchFeed();">隐藏</a> ]</div>
    <div class="form2">
    <form method="post" action="<?php echo U('admin/Content/doSearchFeed');?>">
    <input type="hidden" name="isSearch" value="1"/>
    <dl class="lineD">
      <dt>ID：</dt>
      <dd>
        <input name="feed_id" type="text" value="<?php echo ($feed_id); ?>">
        <p>多个时使用英文的“,”分割</p>
      </dd>
    </dl>
    
    <?php if($isSearch != 1) $uid = ''; ?>
    <dl class="lineD">
      <dt>用户ID：</dt>
      <dd>
        <input name="uid" type="text" value="<?php echo ($uid); ?>">
        <p>用户ID,多个时使用英文的","分割</p>
      </dd>
    </dl>
    
    <dl class="lineD">
      <dt>类型：</dt>
      <dd>
        <select name="type">
            <option value="" selected> - </option>
            <?php if(is_array($types)): ?><?php $i = 0;?><?php $__LIST__ = $types?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><option value="<?php echo ($vo); ?>" <?php if($vo==$type) echo 'selected'; ?>><?php echo ($vo); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
        </select>
      </dd>
    </dl>
    <div class="page_btm">
      <input type="submit" class="btn_b" value="确定" />
    </div>
    </form>
  </div>
  </div>
  
  <div class="page_tit">动态管理</div>
  <div class="Toolbar_inbox">
    <div class="page right"><?php echo ($html); ?></div>
    <a href="javascript:void(0);" class="btn_a" onclick="searchFeed();">
        <span class="search_action"><?php if(($isSearch)  !=  "1"): ?>搜索动态<?php else: ?>搜索完毕<?php endif; ?></span>
    </a>
    <a href="javascript:void(0);" class="btn_a" onclick="deleteRecord();"><span>删除记录</span></a>
  </div>
  <div class="list">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th style="width:30px;">
        <input type="checkbox" id="checkbox_handle" onclick="checkAll(this)" value="0">
        <label for="checkbox"></label>
    </th>
    <th class="line_l">ID</th>
    <th class="line_l">用户ID</th>
    <th class="line_l">用户名</th>
    <th class="line_l">类型</th>
    <th class="line_l">标题</th>
    <th class="line_l">时间</th>
    <th class="line_l">操作</th>
  </tr>
  <?php if(is_array($data)): ?><?php $i = 0;?><?php $__LIST__ = $data?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><tr overstyle='on' id="feed_<?php echo ($vo["feed_id"]); ?>">
        <td><input type="checkbox" name="checkbox" id="checkbox2" onclick="checkon(this)" value="<?php echo ($vo["feed_id"]); ?>"></td>
        <td><?php echo ($vo["feed_id"]); ?></td>
        <td><?php echo ($vo["uid"]); ?></td>
        <td><?php echo (getUserName($vo["uid"])); ?></td>
        <td><?php echo ($vo["type"]); ?></td>
        <td><?php echo ($vo["title"]); ?></td>
        <td><?php echo (date("Y-m-d H:i",$vo["ctime"])); ?></td>
        <td><a href="javascript:void(0);" onclick="deleteRecord('<?php echo ($vo["feed_id"]); ?>')">删除</a></td>
      </tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
  </table>
  </div>
  <div class="Toolbar_inbox">
    <div class="page right"><?php echo ($html); ?></div>
    <a href="javascript:void(0);" class="btn_a" onclick="searchFeed();">
        <span class="search_action"><?php if(($isSearch)  !=  "1"): ?>搜索动态<?php else: ?>搜索完毕<?php endif; ?></span>
    </a>
    <a href="javascript:void(0);" class="btn_a" onclick="deleteRecord();"><span>删除记录</span></a>
  </div>
</div>

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
    
    function deleteRecord(ids) {
        var length = 0;
    	if(ids) {
    		length = 1;    		
    	}else {
    		ids    = getChecked();
    		length = ids.length;
            ids    = ids.toString();
    	}
    	
    	if(ids=='') {
    		ui.error('请先选择一个动态');
    		return ;
    	}
    	if(confirm('您将删除'+length+'条记录，删除后无法恢复，确定继续？')) {
    		$.post("<?php echo U('admin/Content/doDeleteFeed');?>",{ids:ids},function(res){
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
            $('#feed_'+ids[i]).remove();
        }
    }
    
    //搜索用户
    var isSearchHidden = <?php if(($isSearch)  !=  "1"): ?>1<?php else: ?>0<?php endif; ?>;
    function searchFeed() {
        if(isSearchHidden == 1) {
            $("#search_div").slideDown("fast");
            $(".search_action").html("搜索完毕");
            isSearchHidden = 0;
        }else {
            $("#search_div").slideUp("fast");
            $(".search_action").html("搜索动态");
            isSearchHidden = 1;
        }
    }
</script>

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