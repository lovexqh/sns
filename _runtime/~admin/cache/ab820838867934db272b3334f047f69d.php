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
  
  <div class="page_tit">知识点管理</div>
  <div class="tit_tab">
    <ul>
    <li><a <?php if(($isadd)  !=  "1"): ?>class="on"<?php endif; ?> href="<?php echo U('admin/Category/knowledge');?>">管理知识点</a></li>
    <li><a <?php if(($isadd)  ==  "1"): ?>class="on"<?php endif; ?> href="<?php echo U('admin/Category/modifyknowledge');?>">添加知识点</a></li>
    </ul>
  </div>
  
  <div id="searchClassification_div" <?php if(($type)  !=  "searchClassification"): ?>style="display:none;"<?php endif; ?>>
  	<div class="page_tit">搜索知识点 [ <a href="javascript:void(0);" onclick="searchClassification();">隐藏</a> ]</div>
	
	<div class="form2">
	<form method="post">
	<?php if($type != 'searchClassification') $uid = ''; ?>
    <dl class="lineD">
      <dt>知识点内容：</dt>
      <dd>
        <input name="edukey" id="edukey" type="text" value="<?php echo ($edukey); ?>">
        <p>请填写要搜索的知识点内容，支持模糊查询</p>
      </dd>
    </dl>
    <div class="page_btm">
      <input type="submit" class="btn_b" value="确定" />
    </div>
	</form>
  </div>
  </div>
  
  <!-------- 知识点列表 -------->
  <div class="Toolbar_inbox">
  	<div class="page right"><?php echo ($dataList["html"]); ?></div>
    <form method="post">
	<a href="javascript:void(0);" class="btn_a" onclick="searchClassification();">
		<span class="searchClassification_action"><?php if(($type)  !=  "searchClassification"): ?>搜索知识点<?php else: ?>搜索完毕<?php endif; ?></span>
	</a>
    <div class="btn_a select_box"><span>
    <select name="publisher" class="dataPublisher" onchange="getSelect('publisher',0,this.value)">
    <option value="0">请选择出版(或发行)者</option>
    <?php if(is_array($dataPublisher)): ?><?php $i = 0;?><?php $__LIST__ = $dataPublisher?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$val): ?><?php ++$i;?><?php $mod = ($i % 2 )?><option value="<?php echo ($val['DataCode']); ?>" <?php if($publisher == $val['DataCode']) echo 'selected'; ?>><?php echo ($val['DataName']); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </select>
    </span></div>
    
    <div class="btn_a select_box" <?php if(($subject)  !=  "show"): ?><?php else: ?>style="display:none"<?php endif; ?>><span>
    <select name="subject" class="publisher" onchange="getSelect('subject',$('.dataPublisher').val(),this.value)">
    <option value="0">全部</option>
	<?php if(is_array($dataSubject)): ?><?php $i = 0;?><?php $__LIST__ = $dataSubject?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$val): ?><?php ++$i;?><?php $mod = ($i % 2 )?><option value="<?php echo ($val['Subject']); ?>" <?php if($subject == $val['Subject']) echo 'selected'; ?>><?php echo ($val['SubjectName']); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </select>
    </span></div>
    
    <div class="btn_a select_box" <?php if(($grade)  !=  "show"): ?><?php else: ?>style="display:none"<?php endif; ?>><span>
    <select name="grade" class="subject">
    <option value="0">全部</option>
    <?php if(is_array($dataGrade)): ?><?php $i = 0;?><?php $__LIST__ = $dataGrade?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$val): ?><?php ++$i;?><?php $mod = ($i % 2 )?><option value="<?php echo ($val['Grade']); ?>" <?php if($grade == $val['Grade']) echo 'selected'; ?>><?php echo ($val['GradeName']); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </select>
    </span></div>
    
    <a href="javascript:void(0);" class="btn_a" onclick="$(this).parent().submit();"><span>过 滤</span></a>
    </form>
  </div>
  <div class="list">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th style="width:30px;">
		<input type="checkbox" id="checkbox_handle" onclick="checkAll(this)" value="0">
    	<label for="checkbox"></label>
	</th>
    <th class="line_l">ID</th>
    <th class="line_l">出版(或发行)者</th>
    <th class="line_l">科目</th>
    <th class="line_l">年级</th>
    <th class="line_l">知识点</th>
    <th class="line_l">操作</th>
  </tr>
  <?php if(is_array($dataList["data"])): ?><?php $i = 0;?><?php $__LIST__ = $dataList["data"]?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$vo): ?><?php ++$i;?><?php $mod = ($i % 2 )?><tr overstyle='on' id="row_<?php echo ($vo['CourseID']); ?>">
	    <td><input type="checkbox" name="checkbox" id="checkbox2" onclick="checkon(this)" value="<?php echo ($vo["CourseID"]); ?>"></td>
	    <td><?php echo ($vo["CourseID"]); ?></td>
	    <td><?php echo ($vo["PublisherName"]); ?></td>
		<td><?php echo ($vo["SubjectName"]); ?></td>
	    <td><?php echo ($vo["GradeName"]); ?></td>
	    <td><?php echo ($vo["Course"]); ?></td>
	    <td>
            <a href="<?php echo U('admin/Category/modifyknowledge',array('id'=>$vo['CourseID']));?>">编辑</a>
            <a href="javascript:void(0)" onclick="deleteKnowledge('<?php echo ($vo["CourseID"]); ?>')">删除</a>
		</td>
	  </tr><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
  </table>
  </div>
  <div class="Toolbar_inbox">
  	<div class="page right"><?php echo ($dataList["html"]); ?></div>
	<form method="post">
	<a href="javascript:void(0);" class="btn_a" onclick="searchClassification();">
		<span class="searchClassification_action"><?php if(($type)  !=  "searchClassification"): ?>搜索知识点<?php else: ?>搜索完毕<?php endif; ?></span>
	</a>
    <div class="btn_a select_box"><span>
    <select name="publisher" class="dataPublisher" onchange="getSelect('publisher',0,this.value)">
    <option value="0">请选择出版(或发行)者</option>
    <?php if(is_array($dataPublisher)): ?><?php $i = 0;?><?php $__LIST__ = $dataPublisher?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$val): ?><?php ++$i;?><?php $mod = ($i % 2 )?><option value="<?php echo ($val['DataCode']); ?>" <?php if($publisher == $val['DataCode']) echo 'selected'; ?>><?php echo ($val['DataName']); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </select>
    </span></div>
    
    <div class="btn_a select_box" <?php if(($subject)  !=  "show"): ?><?php else: ?>style="display:none"<?php endif; ?>><span>
    <select name="subject" class="publisher" onchange="getSelect('subject',$('.dataPublisher').val(),this.value)">
    <option value="0">全部</option>
	<?php if(is_array($dataSubject)): ?><?php $i = 0;?><?php $__LIST__ = $dataSubject?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$val): ?><?php ++$i;?><?php $mod = ($i % 2 )?><option value="<?php echo ($val['Subject']); ?>" <?php if($subject == $val['Subject']) echo 'selected'; ?>><?php echo ($val['SubjectName']); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </select>
    </span></div>
    
    <div class="btn_a select_box" <?php if(($grade)  !=  "show"): ?><?php else: ?>style="display:none"<?php endif; ?>><span>
    <select name="grade" class="subject">
    <option value="0">全部</option>
    <?php if(is_array($dataGrade)): ?><?php $i = 0;?><?php $__LIST__ = $dataGrade?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$val): ?><?php ++$i;?><?php $mod = ($i % 2 )?><option value="<?php echo ($val['Grade']); ?>" <?php if($grade == $val['Grade']) echo 'selected'; ?>><?php echo ($val['GradeName']); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
    </select>
    </span></div>
    
    <a href="javascript:void(0);" class="btn_a" onclick="$(this).parent().submit();"><span>过 滤</span></a>
    </form>
    
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

	//搜索知识点
	var isSearchHidden = <?php if(($type)  !=  "searchClassification"): ?>1<?php else: ?>0<?php endif; ?>;
	function searchClassification() {
		if(isSearchHidden == 1) {
			$("#searchClassification_div").slideDown("fast");
			$(".searchClassification_action").html("搜索完毕");
			isSearchHidden = 0;
		}else {
			$("#searchClassification_div").slideUp("fast");
			$(".searchClassification_action").html("搜索知识点");
			isSearchHidden = 1;
		}
	}

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

	//获取已选择知识点的ID数组
	function getChecked() {
		var ids = new Array();
		$.each($('table input:checked'), function(i, n){
			var val = $(n).val();
			if (val > 0) {
				ids.push( $(n).val() );
			}
		});
		return ids;
	}
	
	function getSelect(key,pvalue,value){
		if(pvalue==0){
			$('.subject option').remove();
			$('.publisher').parent().parent().hide();
		}
		$('.grade option').remove();
		$('.subject').parent().parent().hide();
		
		if(key!=''&&value!=''&&value!=0){
			$.post("<?php echo U('admin/Category/doKnowledgeSelect');?>",{key:key,pvalue:pvalue,value:value},function(text){
				if(text!=''){
					$('.'+key).parent().parent().show();	
				}
				var json = eval(text);
				$('.'+key+' option').remove();
				$('.'+key).append("<option value='0'>全部</option>");
				for(var i=0; i<json.length; i++)
				{
					$('.'+key).append("<option value='"+json[i].cloumn+"'>"+json[i].text+"</option>");
				}
				//为Select追加一个Option(下拉项)   
			});
		}
	}
    
    function deleteKnowledge(ids) {
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
			$.post("<?php echo U('admin/Category/doDeleteKnowledge');?>",{ids:ids},function(res){
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
            $('#row_'+ids[i]).remove();
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