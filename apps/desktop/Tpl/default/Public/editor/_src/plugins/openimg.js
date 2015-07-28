///import core
///commands 插入图片
///commandsName  openimg
///commandsTitle  插入桌面图片
/**
 * 打开文件
 * @function
 * @name baidu.editor.execCommand
 * @param   {String}   cmdName     openimg打开文件
 */
UE.commands['openimg'] = {
    execCommand : function(){ 
	var me = this;
       	top.OpenFile('open','插入图片',{"image":['图像文件(*.JPG，*.JPEG，*.PNG，*.GIF)',['JPG','JPEG','PNG','GIF'],'selected']},{cmd:'insertimg',multiple:true},function(data){
			acceptdata(data);
		}); 
	},
    notNeedUndo : 1
};
