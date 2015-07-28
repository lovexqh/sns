var swfu;
var index = 0;
var count= 0;
var prefileid = '';
var isfile = false;
var Uploader = {
    getFileSize: function (num) {
        if (isNaN(num)) {
            return false;
        }
        num = parseInt(num);
        var units = [" B", " KB", " MB", " GB"];
        for (var i = 0; i < units.length; i += 1) {
            if (num < 1024) {
                num = num + "";
                if (num.indexOf(".") != -1 && num.indexOf(".") != 3) {
                    num = num.substring(0, 4);
                } else {
                    num = num.substring(0, 3);
                }
                break;
            } else {
                num = num / 1024;
            }
        }
        return num + units[i];
    },

    updateStatus: function (fileId, html) {
        //  $("#file_tr_" + fileId).get(0).cells[2].innerHTML = html;
    },

    updateOpt: function (fileId, html) {
        //$("#file_tr_" + fileId).get(0).cells[3].innerHTML = html;
    },

    Handler: {
    	file_dialog_complete:function(file){
    		if(parseInt(file) > 1){
    			alert('一次只能上传一个文件');
    		}
    	},
    	fileQueued: function (file) {
    		 swfu.customSettings.queue = this.customSettings.queue || new Array();
             while (swfu.customSettings.queue.length > 0) {
            	 swfu.cancelUpload(swfu.customSettings.queue.pop(), false);
             }
             swfu.customSettings.queue.push(file.id);
             isfile = true;
             $('.uptdt').html(file.name);
             $('.showfile').show();
             $('#videoname').val(file.name);
        },

        uploadStart: function (file) {
        	//$('#updateButton').css('display','none');
            //开始上传此文件
            Uploader.updateStatus(file.id, "开始上传");
            $('.fileoper').show();
            $('#spanSWFUploadButton').hide();
        },

        uploadProgress: function (file, bytesLoaded, bytesTotal) {
        	var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
            $('.process').css('width',percent + "%");
            $('.showprocess').html(percent + "%");

        },

        uploadSuccess: function (file, serverData) {
        	if(serverData != ''){
        		var obj = eval('('+serverData+')');
        		$('#fcode').val(obj.filcode);
            	$('#outfilename').val(obj.outfilename);
            	$('#filename').val(obj.oldname);
            	$('#savepath').val(obj.filedir);
            	
            	$.post($('#save_video').attr('action'),$('#save_video').serialize(),function(data){
    				var data = eval('(' + data + ')');
    				//判断返回的data值(将在action中控制ajax提交时将返回执行成功时的状态值)
    				if(data.status>0){
    					alert('视频上传成功！');
    					//document.location.href = U('square/Video/usercate',['uid='+_UID_]);
    					//parent.tabs.redirect('我的视频',U('video/Index/videos',['iframe=yes']));
    					window.close();
    					
    				}else{
    					alert('视频上传失败，请重新上传！');
    				}
    			});
        	}else{
        		alert('视频上传失败，请重新上传！');	
        	}
        },

        fileQueueError: function (file, errorCode, message) {

        },
        
        uploadCancel:function(){
        	swfu.customSettings.queue = swfu.customSettings.queue || new Array();
            while (swfu.customSettings.queue.length > 0) {
           	 swfu.cancelUpload(swfu.customSettings.queue.pop(), false);
            }
        	$('.process').css('width',"0%");
            $('.showprocess').html("0%");
            $('.fileoper').hide();
            $('.uptdt').html();
            $('.showfile').hide();
            $('#videoname').val('');
            $('#spanSWFUploadButton').show();
            isfile = false;
        }
    }
};


