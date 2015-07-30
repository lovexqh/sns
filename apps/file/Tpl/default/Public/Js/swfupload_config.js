/* SWFUpload */
var OPTIONS_SWF_UPLOAD = {
	flash_url : _PUBLIC_+"/js/swfupload/swfupload.swf",
	upload_url: '',//service url
	post_params: {"PHPSESSID" : ''},
	file_size_limit : "10 MB",
	file_types : "*.*",
	file_types_description : "",
	file_upload_limit : 5,
	file_queue_limit : 0,
	file_post_name : 'files',
	custom_settings : {
		progressTarget : "fsUploadProgress",
		cancelButtonId : "btnCancel"
	},
	debug: false,

	// Button settings
	button_image_url: _THEME_+"/images/swfupload/btn_upload.png",
	button_width: "80",
	button_height: "27",
	button_placeholder_id: "upload_box_btn",
	button_text: '<span class="btn_upload">浏览文件</span>',
	button_text_style: ".btn_upload { font-size: 12px; color:#ffffff}",
	button_text_left_padding: 15,
	button_text_top_padding: 3,

	// The event handler functions are defined in handlers.js

	file_queued_handler : fileQueued
	//queue_complete_handler : queueComplete	// Queue plugin event
};

var OPTIONS_SWF_UPLOAD_FILE = $.extend({},OPTIONS_SWF_UPLOAD);
var OPTIONS_SWF_UPLOAD_IMG = $.extend({},OPTIONS_SWF_UPLOAD);

//上传文档配置
OPTIONS_SWF_UPLOAD_FILE.upload_url= U("home/widget/addonsRequest");
OPTIONS_SWF_UPLOAD_FILE.post_params={
	addon:'FileUploads',
	hook:'uploadFile',
	app:''//记录发布文档的应用类型 目前有 weibo group
};
OPTIONS_SWF_UPLOAD_FILE.file_types = "*.jpg;*.gif;*.png;*.jpeg;*.bmp;*.zip;*.rar;*.doc;*.xls;*.ppt;*.docx;*.xlsx;*.pptx;*.pdf";

OPTIONS_SWF_UPLOAD_FILE.html =	'<div id="upload_box">'
		+	'	<div id="upload_box_btn_container"><div id="upload_box_btn"></div><div id="upload_box_msg">可以上传 <span id="allow_file_no">'+OPTIONS_SWF_UPLOAD_FILE.file_upload_limit+'</span> 个文件</div></div>'
		+	'	<div id="upload_box_files"></div>'
		+	'	<div id="upload_box_tags"><input type="text" class="input" autocomplete="off" name="tags" id="weibo_file_tags" value="请输入描述标签" onfocus="if(this.value == \'请输入描述标签\') this.value=\'\';" onblur="if(this.value==\'\') this.value=\'请输入描述标签\';" /></div>'
		+	'	<div id="upload_box_file_types"><p>上传文件类型：'+OPTIONS_SWF_UPLOAD_FILE.file_types+'</p><p>单个文件大小：'+OPTIONS_SWF_UPLOAD_FILE.file_size_limit+'</p></div>'
		+	'	<script type="text/javascript">var OPTIONS_SWF_UPLOAD_NOW = OPTIONS_SWF_UPLOAD_FILE; swfu = new SWFUpload(OPTIONS_SWF_UPLOAD_NOW);$("#weibo_file_tags").autocomplete(\''+U('weibo/Index/getTags')+'\',{width: 260,highlight: false,multiple: true,multipleSeparator:" ",scroll: true,scrollHeight: 300,autoFill:true,matchContains:true, onselected:function(item){}});</script>'
		+	'</div>';

OPTIONS_SWF_UPLOAD_FILE.type = 5;//文档类型为5
OPTIONS_SWF_UPLOAD_FILE.title = '';

//上传图片配置
OPTIONS_SWF_UPLOAD_IMG.upload_url= U("home/widget/addonsRequest");
OPTIONS_SWF_UPLOAD_IMG.post_params={
	addon:'WeiboType',
	hook:'uploadImage',
	app:''
};
OPTIONS_SWF_UPLOAD_IMG.file_types = "*.jpg;*.gif;*.png;*.jpeg;*.bmp;";

OPTIONS_SWF_UPLOAD_IMG.html =	'<div id="upload_box">'
		+	'	<div id="upload_box_btn_container"><div id="upload_box_btn"></div><div id="upload_box_msg">可以上传 <span id="allow_file_no">'+OPTIONS_SWF_UPLOAD_IMG.file_upload_limit+'</span> 个文件</div></div>'
		+	'	<div id="upload_box_files"></div>'
		+	'	<div id="upload_box_tags"><input type="text" class="input" autocomplete="off" name="tags" id="weibo_file_tags" value="请输入描述标签" onfocus="if(this.value == \'请输入描述标签\') this.value=\'\';" onblur="if(this.value==\'\') this.value=\'请输入描述标签\';" /></div>'
		+	'	<div id="upload_box_file_types"><p>上传文件类型：'+OPTIONS_SWF_UPLOAD_IMG.file_types+'</p><p>单个文件大小：'+OPTIONS_SWF_UPLOAD_IMG.file_size_limit+'</p></div>'
		+	'	<script type="text/javascript">var OPTIONS_SWF_UPLOAD_NOW = OPTIONS_SWF_UPLOAD_IMG; swfu = new SWFUpload(OPTIONS_SWF_UPLOAD_NOW);$("#weibo_file_tags").autocomplete(\''+U('weibo/Index/getTags')+'\',{width: 260,highlight: false,multiple: true,multipleSeparator:" ",scroll: true,scrollHeight: 300,autoFill:true,matchContains:true, onselected:function(item){}});</script>'
		+	'</div>';

OPTIONS_SWF_UPLOAD_IMG.type = 1;//图片类型为1
OPTIONS_SWF_UPLOAD_IMG.title = '';




function fileQueued(file)
{
	switch(OPTIONS_SWF_UPLOAD_NOW.type)
	{
		case OPTIONS_SWF_UPLOAD_IMG.type://图片上传
			var html = '<div class="upload_box_image" id="'+file.id+'">'
			 + '	<div class="upload_box_file_icon">等待上传</div>'
			 + '	<div class="upload_box_file_info">'
			 //+ '		<p>文件名：<span>'+file.name+'</span></p>'
			 //+ '		<p>大　小：<span>'+get_byte(file.size)+'</span></p>'
			 + '	</div>'
			 + '	<div class="upload_box_file_tool">'+get_byte(file.size)+' <a class="upload_box_file_tool_del" href="javascript:;" onclick="remove_file(\''+file.id+'\',\'\')">删除</a></div>'
			 + '	<div class="upload_box_file_progress"><div></div></div><div class="clear"></div>'
			 + '</div>';
			break;
		case OPTIONS_SWF_UPLOAD_FILE.type://文档上传
			var html = '<div class="upload_box_file" id="'+file.id+'">'
			 + '	<div class="upload_box_file_icon">'+file.type.toLowerCase().replace('.','')+'</div>'
			 + '	<div class="upload_box_file_info">'
			 + '		<p>文件名：<span>'+file.name+'</span></p>'
			 + '		<p>大　小：<span>'+get_byte(file.size)+'</span></p>'
			 + '	</div>'
			 + '	<div class="upload_box_file_tool"><a class="upload_box_file_tool_del" href="javascript:;" onclick="remove_file(\''+file.id+'\',\'\')">删除</a> <span>等待上传</span></div>'
			 + '	<div class="upload_box_file_progress"><div></div></div><div class="clear"></div>'
			 + '</div>';
			break;
	}
	//$('.upload_box_image').ZY_tooltip('normal');
	$('#upload_box_files').append(html);


}

function fileDialogComplete(numFilesSelected, numFilesQueued) {

	if (numFilesQueued > 0)
	{
		$('#allow_file_no').text(this.getSetting('file_upload_limit')-$('.upload_box_file').length);
		$('#upload_box_files').show();
		$('#upload_box_files').append('<div class="clear"></div>');


		this.startUpload();
	}
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
	var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
	switch(OPTIONS_SWF_UPLOAD_NOW.type)
	{
		case OPTIONS_SWF_UPLOAD_IMG.type://图片上传
			$('#'+file.id+' .upload_box_file_icon').html(Math.ceil((bytesLoaded / bytesTotal) * 10000)/100+'%').addClass('loading');
			break;
		case OPTIONS_SWF_UPLOAD_FILE.type://文档上传
			$('#'+file.id+' .upload_box_file_progress div').width(percent+'%');
			$('#'+file.id+' .upload_box_file_tool>span').html(Math.ceil((bytesLoaded / bytesTotal) * 10000)/100+'%');
                        var jindu = Math.ceil((bytesLoaded / bytesTotal) * 10000)/100;
                        if(jindu === 100){
                            $('#'+file.id+' .upload_box_file_tool>span').html("转换中");
                        }
			break;
	}

}

//单文件上传成功
function uploadSuccess(file, serverData) {
	serverData=$.parseJSON(serverData);
	if(serverData.boolen)
	{
		switch(OPTIONS_SWF_UPLOAD_NOW.type)
		{
                   
			case OPTIONS_SWF_UPLOAD_IMG.type://图片上传
				$('#'+file.id+' .upload_box_file_icon').html('<a href="'+serverData.picurl+'" target="_blank"><img src="'+serverData.picurl+'" /></a>').removeClass('loading');
				$('#'+file.id).append('<input type="hidden" name="publish_type_data[]" value="'+serverData.type_data+'">');
				break;
			case OPTIONS_SWF_UPLOAD_FILE.type://文档上传
				$('#'+file.id+' .upload_box_file_tool>span').text('上传成功');
				$('#'+file.id).append('<input type="hidden" name="publish_type_data[]" value="'+serverData.file_id+'">');
				break;
		}

		$('#'+file.id+' .upload_box_file_tool_del')
		.attr('onclick','')
		.attr('filedbid',serverData.file_id)
		.attr('fileid',file.id)
		.attr('filepath',serverData.type_data)
		.click(function(){
			remove_file($(this).attr('fileid'),$(this).attr('filedbid'),$(this).attr('filepath'));
		});

	}
	else
	{
		$('#'+file.id).addClass('error');
		$('#'+file.id+' .upload_box_file_tool>span').text('上传失败');
	}

	$('#'+file.id+' .upload_box_file_progress').hide('fast');

	$('#weibo_close_handle').hide();

	//设置微博类型
	$('#miniblog_publish input[name="publish_type"]').val(OPTIONS_SWF_UPLOAD_NOW.type);
	this.startUpload();
}

//全部上传成功
function uploadComplete(file) {
	if (this.getStats().files_queued === 0) {
		$('#upload_box_btn_start').hide().text('开始上传');
		$('#content_publish').val($('#content_publish').val()+OPTIONS_SWF_UPLOAD_NOW.title).focus();
	}
}

function fileQueueError(file, errorCode, message)
{
	switch(errorCode.toString())
	{
		case '-100':
			ui.error('超出允许上传的文档数');
			break;
		case '-110':
			ui.error('单个文件只能上传 '+OPTIONS_SWF_UPLOAD_NOW.file_size_limit+' 内的文件');
			break;
		default:
			ui.error('上传发生错误 error:'+errorCode+' message:'+message);
			break;
	}
}

function remove_file(fileid,filedbid,filepath)//fileid=dom file id,filedbid database id
{
	$('#'+fileid).hide('fast',function(){
		$(this).remove();
		if(!$('#upload_box_files>div').length) $('#upload_box_files').hide();
	});
	swfu.cancelUpload(fileid);
	$('#allow_file_no').text(swfu.getSetting('file_upload_limit')-$('.upload_box_file').length);


	if(filedbid!='')
	{
		$.post(U('file/File/deleteFiles'),{id:filedbid,filepath:filepath},function(result){});//服务端删除
	}
}

function upload_file()
{
	var obj = $('#upload_box_btn_start');
	if(obj.text()=='开始上传')
	{
		swfu.startUpload();
		obj.text('停止上传');
	}
	else
	{
		swfu.stopUpload();
		obj.text('开始上传');
	}
}



/* public */
function get_byte(bytes)
{
	if(bytes<1024)
		return bytes+'b';
	else if(bytes<1048576)
		return parseInt(bytes/1024)+'Kb';
	else if(bytes<1073741824)
		return parseInt(bytes/1048576*10)/10 + 'Mb';
}
