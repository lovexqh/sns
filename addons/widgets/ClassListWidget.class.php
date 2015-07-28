<?php

class ClassListWidget extends Widget{
	//type:select
	public function render( $data ){
		//初始化参数
		if(empty($data['type']))		$data['type']		= 'select';
		if(empty($data['form_name']))	$data['form_name']	= 'classlist';
		if(empty($data['form_id']))		$data['form_id']	= 'classlist';
		if(empty($data['uid']))			$data['uid']		= $_SESSION['mid'];

		//创建默认分类
		$pre	=	C('DB_PREFIX');
		if(D()->table("{$pre}app_class")->where("uid='".$data['uid']."'")->count()==0){
			$class['cTime']		=	time();
			$class['mTime']		=	time();
			$class['uid']		=	$data['uid'];
			$class['class_name']=	getShort(getUserName($data['uid']),5).'的根分类';
			$class['privacy']	=	1;
			D()->table("{$pre}app_class")->add($class);
		}

		//获取专辑列表数据
		$data['data']	=	M('app_class')->field('class_id,parentid,class_name,(SELECT \'floder\') AS iconSkin')->where("uid='".$data['uid']."'")->findAll();
		return $this->renderFile( $data );
	}
	/*
		Widget模版在第一次初始化的时候，写不进数据，故先改成如下形式。
	*/
	protected function renderFile( $data ){
		$out	=	'<link rel="stylesheet" href="'.__PUBLIC__.'/js/zTree/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="'.__PUBLIC__.'/js/jquery.ztree.core-3.4.js"></script>
<script type="text/javascript" src="'.__PUBLIC__.'/js/jquery.ztree.excheck-3.4.js"></script>
<style type="text/css">
ul.ztree {margin-top: 10px;border: 1px solid #617775;background: #f5f5f5;width:220px;height:360px;overflow-y:scroll;overflow-x:auto;}

.ztree li span.floder_ico_open{
	margin-right:2px; background-position:-110px -16px; vertical-align:top; *vertical-align:middle
}
.ztree li span.floder_ico_close,.ztree li span.floder_ico_docu{
	margin-right:2px; background-position:-110px 0; vertical-align:top; *vertical-align:middle
}
</style>
<SCRIPT type="text/javascript">
<!--
var setting = {
	view: {
		dblClickExpand: false,
		selectedMulti: false
	},
	data: {
		simpleData: {
			enable: true,
			idKey: "class_id",
			pIdKey: "parentid",
			rootPId: 0
		},
		key: {
			name: "class_name"
		}
	},
	callback: {
		onClick: onClick
	}
};

var zNodes = eval('.json_encode($data['data']).');

function onClick(e, treeId, treeNode) {
	$("#'.$data['form_id'].'").val(treeNode.class_id);
	var selObj = $("#'.$data['form_id'].'Sel");
	selObj.attr("value", treeNode.class_name);
}

function showMenu() {
	var selObj = $("#'.$data['form_id'].'Sel");
	var selOffset = $("#'.$data['form_id'].'Sel").offset();
	$("#menuContent").css({left:selOffset.left + "px", top:selOffset.top + selObj.outerHeight() + "px"}).show();
	$("body").bind("mousedown", onBodyDown);
}
function hideMenu() {
	$("#menuContent").fadeOut();
	$("body").unbind("mousedown", onBodyDown);
}
function onBodyDown(event) {
	if(event.target.id=="parentTree"){
		return;
	}
	if(event.target.id.indexOf("switch")>0){
		return;
	}
	hideMenu();
}
$(document).ready(function(){
	$.fn.zTree.init($("#'.$data['form_id'].'Tree"), setting, zNodes);
});

//弹出创建专辑窗口
function create_class_tab(cid){
	if(cid!=""&&cid>0)
		ui.box.load("'.U("home/Tool/create_class_tab").'&cid="+cid,{title:"创建分类"});
	else
		alert("请先选择父级分类！");
}

//执行创建专辑操作
function do_create_class(){
	var parentid	=	$("#cpopparentid").val();
	var name		=	$("#cpopname").val().replace(/\s+/g,"");
	var privacy		=	$("#cpopprivacy").val();
	var password	=	$("#cpoptextfield3").val();

	if(!name)	{
		alert("名称不能为空！");
		return false;
	}else if(name.length > 12)	{
		alert("名称不能超过12个字！");
		return false;
	}
	$.post(U("home/Tool/do_create_class"),{parentid:parentid,name:name,privacy:privacy,privacy_data:password},function(data){
		if(data == -1){
			ui.error("该分类名已存在！");
		}else if(data){
			//parent.setCategoryOption(data)
			ui.box.close();
			ui.success("创建成功！");
		}else{
			ui.box.close();
			ui.error("创建失败！");
		}
	});
}
</script>
//-->
</SCRIPT>';

		$out .= '<input type="hidden" id="'.$data['form_id'].'" name="'.$data['form_name'].'" value="0" />
        <input id="'.$data['form_id'].'Sel" type="text" readonly value="" style="width:120px;" class="text" onClick="showMenu();" />
        <button type="button" onClick="showMenu();" class="btn_w">选择</button>
        <a href="###" onclick="create_class_tab($(\'#'.$data[form_id].'\').val());">[创建分类]</a>';

        $out .= '<div id="menuContent" class="menuContent" style="display:none; position: absolute;">
	<ul id="'.$data['form_id'].'Tree" class="ztree" style="margin-top:0; width:180px; height: 300px;"></ul>
</div>';

		return	$out;
	}
}
?>