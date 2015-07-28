var swfu;
var index = 0;
var count= 0;
var prefileid = '';
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
        	 if(prefileid != "") {
                 swfu.cancelUpload(prefileid, null); //取消之前的FileID                 
             }
        	 prefileid = file.id; //保存当前ID为fileid
             $('#showfilename').html(file.name);
        },

        uploadComplete: function (file) {
            //递归实现自动批量上传
            //this.startUpload();

        },

        uploadStart: function (file) {
        	$('#updateButton').css('display','none');
            //开始上传此文件
            Uploader.updateStatus(file.id, "开始上传");
        },

        uploadProgress: function (file, bytesLoaded, bytesTotal) {
        	var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
            $('#showprocess').css('width',percent + "%");
            $('#showprecent').html(percent + "%");

        },

        uploadSuccess: function (file, serverData) {
        	ts_upload_success(serverData);
//        	var data = eval('('+serverData+')');
//        	alert(data);
//        	$("#savename").val(data.savename);
//			$("#savepath").val(data.webpath);
//			$("#size").val(data.size);
//			//$("#fcode").val(data.filecode);
//			$("#name").val(data.name);
            	
//            $.post(U('club/Document/uploadDocument'),{title:data.name,filesize:data.size,filetype:data.ext,fileurl:"{$con['server']}" + data.webpath + data.savename},function(data){
//    				
//    			//判断返回的data值(将在action中控制ajax提交时将返回执行成功时的状态值)
//    			if(data>0){
//    				//parent.ui.success(data.info);	
//    				//ui.box.close();
//    				ui.success('上传成功');
//    				window.location.reload();
//    			}else{
//    				//parent.ui.error(data.info);	
//    				//ui.box.close();
//    				ui.error('上传失败');
//    			}
//    		});
        	
        },

        fileQueueError: function (file, errorCode, message) {

        }
    }
};



