///import core
///commands 插入桌面视频
///commandsName  openvideo
///commandsTitle  插入桌面视频
/**
 * 打开文件
 * @function
 * @name baidu.editor.execCommand
 * @param   {String}   cmdName     openvideo插入桌面视频
 */
UE.commands['openvideo'] = {
    execCommand : function(){ 
	var me = this;
		top.OpenFile('open','插入视频',{"video":['视频',['VIDEO','WAV','MID','RM','RMVB','RTSP','FLV','SWF','ASF','ASX','WMV','MMS','AVI','MPG','MPEG','MOV'],'selected']},{cmd:'insertvideo',multiple:true},function(data){
			acceptdata(data);
		}); 
    },
    notNeedUndo : 1
};
