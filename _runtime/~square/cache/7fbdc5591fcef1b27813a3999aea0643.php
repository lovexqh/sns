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
<script type="text/javascript" src="__THEME__/square/js/video.js"></script>
</head>
<body>
<link rel="stylesheet" href="__PUBLIC__/js/zTree/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="__PUBLIC__/js/jquery.ztree.core-3.5.js"></script>
<SCRIPT type="text/javascript">
		var IDMark_A = "_a";
		var setting = {
			view: {
				showLine: false,
				showIcon: false,
				selectedMulti: false,
				dblClickExpand: false,
				addDiyDom: addDiyDom
				},
			data: {
				simpleData: {
					enable: true
				}
			},
			callback: {
				onClick: onClick
			}
		};
		var sethid = {
			data: {
				simpleData: {
					enable: true
				}
			},
			callback: {
				onClick: onClick
			}
		};

		var zNodes =[
			<?php if(is_array($category)): ?><?php $i = 0;?><?php $__LIST__ = $category?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$obj): ?><?php ++$i;?><?php $mod = ($i % 2 )?>{ id:<?php echo ($obj[id]); ?>, pId:<?php echo ($obj[p_id]); ?>, name:"<?php echo ($obj[category_name]); ?>",iconOpen:"../Public/images/open.png",icon:"../Public/images/close.png",url:"", target:"_self", click:"noteClick(<?php echo ($obj['id']); ?>)"<?php if(is_array($path)): ?><?php $i = 0;?><?php $__LIST__ = $path?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$v): ?><?php ++$i;?><?php $mod = ($i % 2 )?><?php if($v['id']==$obj['id']){ ?>,open:"true"<?php } ?><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>},<?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
		];
		function onClick(e, treeId, treeNode) {
			var zTree = $.fn.zTree.getZTreeObj("hidtree"),
			nodes = zTree.getSelectedNodes(),
			v = "";
			nodes.sort(function compare(a,b){return a.id-b.id;});
			for (var i=0, l=nodes.length; i<l; i++) {
				v += nodes[i].id + ",";
			}
			if (v.length > 0 ) v = v.substring(0, v.length-1);
			var cityObj = $("#square");
			cityObj.attr("value", v);
		}

		function showMenu() {
			var cityObj = $("#square");
			var cityOffset = $("#square").offset();
			$("#menuContent").css({left:cityOffset.left + "px", top:cityOffset.top + cityObj.outerHeight() + "px"}).slideDown("fast");

			$("body").bind("mousedown", onBodyDown);
		}
		function hideMenu() {
			$("#menuContent").fadeOut("fast");
			$("body").unbind("mousedown", onBodyDown);
		}
		function onBodyDown(event) {
			if (!(event.target.id == "menuBtn" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length>0)) {
				hideMenu();
			}
		}
		function addDiyDom(treeId, treeNode) {
			var aObj = $("#" + treeNode.tId + IDMark_A);
			var id = treeNode.id;
			var editStr = "<a style='color:#0066FF; margin-left:100px;float:right;' href='<?php echo U('square/admin/modifycategory',array('id'=>'"+id+"'));?>'>编辑</a>" +
				"<a href='javascript:void(0);' onclick='deleteAppGroup("+id+");'  style='color:#0066FF; margin-left:100px;float:right;' >删除</a>";
			aObj.after(editStr);
		}
		$(document).ready(function(){
			$.fn.zTree.init($("#navtree"), setting, zNodes);
			zTree_Menu = $.fn.zTree.getZTreeObj("navtree");
			
			$.fn.zTree.init($("#hidtree"), sethid, zNodes);
			zTree_Menu = $.fn.zTree.getZTreeObj("hidtree");
		});
		//-->
	</SCRIPT>
<style type="text/css">
.ztree{ font-size:12px;}
.ztree li,.ztree li ul { margin-top:10px;}
</style>
<div class="so_main">
	<div class="page_tit"><?php echo ($ts['app']['app_alias']); ?></div>
<div class="tit_tab">
<ul>
	<li><a <?php if(($square)  ==  "blog"): ?>class="on"<?php endif; ?> href="<?php echo U('square/admin/category',array(cate=>'blog'));?>">文章栏目管理</a></li>
	<li><a <?php if(($square)  ==  "video"): ?>class="on"<?php endif; ?> href="<?php echo U('square/admin/category',array(cate=>'video'));?>">视频栏目管理</a></li>
	<li><a <?php if(($square)  ==  "resource"): ?>class="on"<?php endif; ?> href="<?php echo U('square/admin/category',array(cate=>'resource'));?>">资源栏目管理</a></li>
	<li><a <?php if(($square)  ==  "tool"): ?>class="on"<?php endif; ?> href="<?php echo U('square/admin/category',array(cate=>'tool'));?>">工具栏目管理</a></li>
</ul>
</div>
	<div class="Toolbar_inbox">
		<div id="searchObject_div" <?php if(($isSearch)  !=  "1"): ?>style="display:none;"<?php endif; ?>>
			<div class="page_tit">增加子栏目 [ <a href="javascript:void(0);" onclick="searchObject();">隐藏</a> ]</div>
			<div class="form2">
				<form action="__URL__&act=addcategory" method="POST">
					<dl class="lineD">
					  <dt>栏目中文名称：</dt>
					  <dd>
						<input name="category_name" class="txt" >
					  </dd>
					</dl>
					 <dl class="lineD">
					  <dt>栏目英文名称：</dt>
					  <dd>
						<input name="category_ename" class="txt" >
					  </dd>
					</dl>
					<dl class="lineD">
					  <dt>所属父类：</dt>
					  <dd>
						 <input name="p_id" id="square" class="text mr5" type="text" readonly value="" style="width:120px;"/>
						 <a id="menuBtn" href="#" onClick="showMenu(); return false;">选择</a>
					  </dd>
					</dl>
					<div class="page_btm">
					  <input type="submit" class="btn_b" value="确定" />
					</div>
				</form>
			</div>
			<div id="menuContent" class="menuContent" style=" display:none; position: absolute; background:#FFFFFF; border:#CCCCCC 1px solid;">
				<UL id="hidtree" class="ztree" style="margin-top:0; width:160px;overflow-x: auto; overflow-y: hidden;"></UL>
			</div>
		</div>
			<div class="Toolbar_inbox">
				<div class="page right"><?php echo ($html); ?></div>
				<span>栏目列表</span>
				<a href="javascript:void(0);" class="btn_a" onclick="searchObject();">
					<span class="searchObject_action"><?php if(($isSearch)  !=  "1"): ?>增加栏目<?php else: ?>取消<?php endif; ?></span>
				</a>
			</div>
		</div>
		<div class="list" >
			<UL id="navtree" class="ztree" style=" width:600px;"></UL>
		</div>
		<div class="tit_tab"></div>
		<span>&nbsp;</span>
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
    
    function deleteAppGroup(ids) {

        var length = 0;
        if(ids) {	
            length = 1;
        }else {
            ids    = getChecked();
            length = ids[0] == 0 ? ids.length - 1 : ids.length;
            ids    = ids.toString();
        }
        if(ids=='') {
            ui.error('请先选择一条数据！');
            return ;
        }
        if(confirm('您将删除'+length+'条记录，删除后无法恢复，确定继续？')) {
            $.post("<?php echo U('square/admin/doDelCategory');?>",{ids:ids},function(res){
                if(res=='1') {
                    ui.success('删除成功');
                    removeItem(ids);
                    window.location.reload();
                }else {
                    ui.error('删除失败');
                }
            });
        }
    }
    
    function removeItem(ids) {
        ids = ids.split(',');
        for(i = 0; i < ids.length; i++) {
            $('#'+ids[i]).remove();
        }
    }
	function move(group_id, direction) {
	var baseid  = direction == 'up' ? $('#'+group_id).prev().attr('id') : $('#'+group_id).next().attr('id');
    if(!baseid) {
        direction == 'up' ? ui.error('已经是最前面了') : ui.error('已经是最后面了');
    }else {
        $.post("<?php echo U('admin/Apps/doGroupAppOrder');?>", {group_id:group_id, baseid:baseid}, function(res){

            if(res == '1') {
                //交换位置
                direction == 'up' ? $('#'+group_id).insertBefore('#'+baseid) : $("#"+group_id).insertAfter('#'+baseid);
                ui.success('保存成功');
            }else {
			
                ui.error('保存失败');
            }
        });
    }
	}
	//增加子栏目
var isSearchHidden = <?php if(($isSearch)  !=  "1"): ?>1<?php else: ?>0<?php endif; ?>;
function searchObject() {
    if(isSearchHidden == 1) {
        $("#searchObject_div").slideDown("fast");
        $(".searchObject_action").html("取消");
        isSearchHidden = 0;
    }else {
        $("#searchObject_div").slideUp("fast");
        $(".searchObject_action").html("增加子栏目");
        isSearchHidden = 1;
    }
}
</script>
<script language="javascript" src="../Public/js/format.js?<?php echo ($verhash); ?>"></script> 
<!-- 底部状态栏 start -->
<div region="south" id="bottomBar" class="bottomBar" style="text-align:center; clear:both; line-height: 30px; background:none; text-align: center;">
    <?php echo ($ts['site']['site_icp']); ?>
    <div id="site_analytics_code" style="display:none;">
        <?php echo (base64_decode($site["site_analytics_code"])); ?>
    </div>
</div>
<!-- 底部状态栏 end -->
<!-- 底部状态栏 end -->
<!--tab-main右键菜单 start-->
<div id="tabs-header-contextmenu" class="easyui-menu" data-options="onClick:menuHandler" style="width:120px; display:none">  
	<div data-options="name:'closeall'">关闭全部选项卡</div> 
	<div data-options="name:'closeother'">关闭其他选项卡</div> 
</div> 
<!--tab-main右键菜单 end-->

<!--tab-main标题列表 start-->
<div id="tabs-header-list" class="easyui-menu"></div>
<!--tab-main标题列表 end-->

<!--UI Dialog start-->
<div id="ui-dialog"></div>
<!--UI Dialog end-->
<!-- Ricker Yu add 2013-9-3 ajax得到视频反馈信息操作
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery.get('<?php echo U("video/Index/ajaxVideo");?>',
			{time:new Date().getTime()},
			function(data){
				
			});
});
</script>
-->
<?php $redirect = base64_decode($_GET['redirect']); ?>
<?php $title = $_GET['title']; ?>
<script type="text/javascript">
<!--
//执行动态打开新tab
$(function(){
	title = "<?php echo empty($_GET['title'])?'内容详情':$_GET['title']; ?>";
	url = "<?php echo ($redirect); ?>";
	if((url.indexOf('app')!=-1 && url.indexOf('mod')!=-1 && url.indexOf('act')!=-1) || url.indexOf('/app/')!=-1){
        if ($('#tabs-main').tabs('exists',title)){
            $('#tabs-main').tabs('select', title);
            tabs.refresh(title,url);
        } else {
            var content = '<iframe scrolling="no" frameborder="0" src="'+url+'" style="width:100%;height:100%;"></iframe>';
            $('#tabs-main').tabs('add',{
                title:title,
                content:content,
                closable:true
            });
        }
		try{
			if(typeof(iframe)!='undefined'){
				parent.mask.show();	
			}else{
				mask.show();
			}
		}catch(e){}}});
//-->
</script>

<!-- Ricker Yu add 2013-9-3 ajax得到视频反馈信息操作 － end -->
</body>
</html>