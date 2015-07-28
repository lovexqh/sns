
function comment_add_app(cid,pcid,idtype,id) {
	var obj = $E('comment_ul_'+pcid);
	var newdl = document.createElement("dl");
	newdl.id = 'comment_'+cid+'_li';
	newdl.className = 'bbda cl';
	if(!obj) {
		try{ hideWindow('c_'+pcid+'_reply');}catch(e){};
		return;
	}
	var x = new Ajax();
	x.get(DZZSCRIPT+'?mod=comment&op=ajax&do=comment&inajax=1&cid='+cid, function(s){
		newdl.innerHTML = s;
	});
	
	if($E('comment_prepend_'+pcid)){
		obj = obj.firstChild;
		while (obj && obj.nodeType != 1){
			obj = obj.nextSibling;
		}
		obj.parentNode.insertBefore(newdl, obj);
	} else {
		obj.appendChild(newdl);
	}
	
	if($E('comment_message_'+idtype+'_'+id)) {
		$E('comment_message_'+idtype+'_'+id).value= '';
	}
	if($E('comment_replynum_'+idtype+'_'+id) && pcid==0) {
		var a = parseInt($E('comment_replynum_'+idtype+'_'+id).innerHTML);
		var b = a + 1;
		$E('comment_replynum_'+idtype+'_'+id).innerHTML = b + '';
	}
	
	if(pcid && $E('reply_'+pcid)) $E('reply_'+pcid).style.display='block';

}
function comment_edit(cid,message) {
		var obj = $E('comment_'+ cid );
		obj.innerHTML = message;
}
function comment_delete(cid,pcid,idtype,id) {
	var obj = $E('comment_'+ cid +'_li');
	obj.style.display = "none";
	if($E('comment_replynum_'+idtype+'_'+id) && pcid==0) {
		var a = parseInt($E('comment_replynum_'+idtype+'_'+id).innerHTML);
		var b = a - 1;
		$E('comment_replynum_'+idtype+'_'+id).innerHTML = b + '';
	}
	if(pcid>0){
		var obj=$E('comment_ul_'+pcid);
		var objs=obj.getElementsByTagName('dl');
		
		var flag=0;
		for(var i=0;i<objs.length;i++){
			if(objs[i].style.display!='none') {flag=1;break;}
		}
		if(flag==0) $E('reply_'+pcid).style.display='none';
	}
}
function showFace(showid, target, dropstr) {
	if($E(showid + '_menu') != null) {
		$E(showid+'_menu').style.display = '';
	} else {
		var faceDiv = document.createElement("div");
		faceDiv.id = showid+'_menu';
		faceDiv.className = 'facel p_pop';
		faceDiv.style.position = 'absolute';
		faceDiv.style.zIndex = 1001;
		var faceul = document.createElement("ul");
		for(i=1; i<31; i++) {
			var faceli = document.createElement("li");
			faceli.innerHTML = '<img src="' + STATICURL + 'image/smiley/comcom/'+i+'.gif" onclick="insertFace(\''+showid+'\','+i+', \''+ target +'\', \''+dropstr+'\')" style="cursor:pointer; position:relative;" />';
			faceul.appendChild(faceli);
		}
		faceDiv.appendChild(faceul);
		$E('append_parent').appendChild(faceDiv)
	}
	setMenuPosition(showid, 0);
	doane();
	_attachEvent(document.body, 'click', function(){if($E(showid+'_menu')) $E(showid+'_menu').style.display = 'none';});
}

function insertFace(showid, id, target, dropstr) {
	var faceText = '[em:'+id+':]';
	
	if($E(target) != null) {
		insertContent(target, faceText);
		if(dropstr) {
			$E(target).value = $E(target).value.replace(dropstr, "");
		}
	}
}
function insertImage(text) {
	text = "\n[img]" + text + "[/img]\n";
	insertContent('message', text);
}

function insertContent(target, text) {
	var obj = $E(target);
	selection = document.selection;
	checkFocus(target);
	if(!isUndefined(obj.selectionStart)) {
		var opn = obj.selectionStart + 0;
		obj.value = obj.value.substr(0, obj.selectionStart) + text + obj.value.substr(obj.selectionEnd);
	} else if(selection && selection.createRange) {
		var sel = selection.createRange();
		sel.text = text;
		sel.moveStart('character', -strlen(text));
	} else {
		obj.value += text;
	}
	
}
function checkFocus(target) {
	var obj = $E(target);
	if(!obj.hasfocus) {
		obj.focus();
	}
}
function checkAll(type, form, value, checkall, changestyle) {
	var checkall = checkall ? checkall : 'chkall';
	for(var i = 0; i < form.elements.length; i++) {
		var e = form.elements[i];
		if(type == 'option' && e.type == 'radio' && e.value == value && e.disabled != true) {
			e.checked = true;
		} else if(type == 'value' && e.type == 'checkbox' && e.getAttribute('chkvalue') == value) {
			e.checked = form.elements[checkall].checked;
			if(changestyle) {
				multiupdate(e);
			}
		} else if(type == 'prefix' && e.name && e.name != checkall && (!value || (value && e.name.match(value)))) {
			e.checked = form.elements[checkall].checked;
			if(changestyle && e.parentNode && e.parentNode.tagName.toLowerCase() == 'li') {
				e.parentNode.className = e.checked ? 'checked' : '';
			}
		}
	}
}