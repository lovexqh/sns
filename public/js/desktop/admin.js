/*
 * admin.js
 *
 * Copyright (c) 2013 ZzStudio (gridinfo.com.cn)
 * Author yangzhiming
 *
 * $Date: 2013-06-09 $
 */

//进行管理，包括删除和关闭
function Admin(act, id, obj) {
	//alert(act);
	//获取选中的checkbox值
	//var obj = event.srcElement;
	var str = "", title = "";
	if (id) {
		str = id;
		title = ",“" + $.trim($("#list_" + str).find(".name a").html()) + "”";
	} else {
		$("input[name='checkbox2']:checkbox").each(function () {
			if ($(this).attr("checked")) {
				str += $(this).val() + ","
				title += ",“" + $.trim($("#list_" + $(this).val()).find(".name a").html()) + "”";
			}
		})
	}
	if(title) title = title.substr(1);
	if (str == "") {
		alert('未选择!');
		return false;
	}
	if(act == 'edit') {
		var title = '修改' + title, url_edit = $(obj).parents('tr').attr('editurl');
		parent.tabs.iframe(title,url_edit);
	} else {
		if(act == 'unpass') {
			Prompt("提示", "请输入驳回的理由", function(r){
				if(r) doAdmin(str, act, r)
			});
		} else if(act == 'del') {
			Confirm("提示", "确定要删除 " + title + " 吗？", function(r){
				if(r) doAdmin(str, act, r)
			});
		} else {
			doAdmin(str, act, '');
		}
	}
}

function doAdmin(str, act, msg) {
	$.post(URL + "&act=" + action_name + "_admin", {
		id: str,
		actions: act,
		msg: msg
	}, function (data) {
		//alert(data);
		if (data < 0) {
			alert('操作失败！');
			return false;
		}
		window.location.reload();
	});
}

//全选
function checkAllOrNone(o) {
	if($(o).hasClass("checkAll")) {
		$(o).removeClass("checkAll").addClass("checkNone");
	} else {
		$(o).removeClass("checkNone").addClass("checkAll");
	}
	
	var checked = $(o).hasClass("checkAll");
	
	$("[name='checkbox2']").each(function(){
		$(this).attr("checked", checked);
	});
}
