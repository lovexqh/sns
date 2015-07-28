
var swfu;
var index = 0;
var count= 0;
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
		//检查播放器
		preLoad: function(){
			if (!this.support.loading) {
				alert("你需要播放器9.028或以上才能使用上传插件.");
				return false;
			}
		},
		//检查加载
		loadFailed: function(){
			alert("加载插件失败");
		},
		
		//文件加载到上传队列
        fileQueued: function (file) {
            var progress = new FileProgress(file, this.customSettings.progressTarget);
			progress.setStatus("等待...");
			progress.toggleCancel(true, this);
        },

        uploadComplete: function (file) {
            //递归实现自动批量上传
            this.startUpload();

        },

        uploadStart: function (file) {
            //开始上传此文件
            //Uploader.updateStatus(file.id, "开始上传");
			try {
				/* I don't want to do any file validation or anything,  I'll just update the UI and return true to indicate that the upload should start */
				var progress = new FileProgress(file, this.customSettings.progressTarget);
				progress.setStatus("上传中...");
				progress.toggleCancel(true, this);
			}
			catch (ex) {
			}
			
			return true;
        },
        uploadProgress: function (file, bytesLoaded, bytesTotal) {
            try {
				var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
				var progress = new FileProgress(file, this.customSettings.progressTarget);
				progress.setProgress(percent);
				progress.setStatus("上传中...");
			} catch (ex) {
				this.debug(ex);
			}
        },

        uploadSuccess: function (file, serverData) {
            try {
				var progress = new FileProgress(file, this.customSettings.progressTarget);
				if(ts_upload_success(serverData)){
					var result	=	eval('('+serverData+')');
					this.customSettings.queue_upload_count++;
					progress.setComplete();
					progress.setStatus("完成.");
					savePost();
				} else {
					progress.setError();
					progress.setStatus("失败.");
				}
				progress.toggleCancel(false);
			} catch (ex) {
				this.debug(ex);
			}
        },
		
        fileQueueError: function (file, errorCode, message) {
			try {
					if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
					alert("只能上传一次." + (message === 0 ? "You have reached the upload limit." : "且只能上传" + (message > 1 ? "up to " + message + " 文件." : "1个文件.")));
					return;
				}
		
				var progress = new FileProgress(file, this.customSettings.progressTarget);
				progress.setError();
				progress.toggleCancel(false);
		
				switch (errorCode) {
				case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
					progress.setStatus("文件太大.");
					this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
					break;
				case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
					progress.setStatus("不能上传0字节文件.");
					this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
					break;
				case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
					progress.setStatus("无效的文件类型.");
					this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
					break;
				case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
					alert("You have selected too many files.  " +  (message > 1 ? "You may only add " +  message + " more files" : "You cannot add any more files."));
					break;
				default:
					if (file !== null) {
						progress.setStatus("未处理的错误");
					}
					this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
					break;
				}
			} catch (ex) {
				this.debug(ex);
			}
        },
		uploadError: function(file, errorCode, message) {
			try {
				var progress = new FileProgress(file, this.customSettings.progressTarget);
				progress.setError();
				progress.toggleCancel(false);
		
				switch (errorCode) {
				case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
					progress.setStatus("上传失败: " + message);
					this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
					break;
				case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
					progress.setStatus("配置错误");
					this.debug("Error Code: No backend file, File name: " + file.name + ", Message: " + message);
					break;
				case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
					progress.setStatus("上传失败.");
					this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
					break;
				case SWFUpload.UPLOAD_ERROR.IO_ERROR:
					progress.setStatus("Server (IO) Error");
					this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
					break;
				case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
					progress.setStatus("安全性错误");
					this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
					break;
				case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
					progress.setStatus("上传超过限制.");
					this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
					break;
				case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
					progress.setStatus("未找到文件.");
					this.debug("Error Code: The file was not found, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
					break;
				case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
					progress.setStatus("验证失败。上传跳过");
					this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
					break;
				case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
					if (this.getStats().files_queued === 0) {
						document.getElementById(this.customSettings.cancelButtonId).disabled = true;
					}
					progress.setStatus("取消");
					progress.setCancelled();
					break;
				case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
					progress.setStatus("停止");
					break;
				default:
					progress.setStatus("未处理的错误: " + error_code);
					this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
					break;
				}
			} catch (ex) {
				this.debug(ex);
			}
		}
    }
};

