///import core
///commands 保存文件
///commandsName  Savefile
///commandsTitle  保存文件
/**
 * 打开文件
 * @function
 * @name baidu.editor.execCommand
 * @param   {String}   cmdName     Savefile保存文件
 */
UE.commands['savefile'] = {
    execCommand : function(){ 
		var me = this;
		if(me.fileicoid>0){
			me.sync();
			document.getElementById('create_save').value=0;
			document.getElementById('save_cmd').value='save';
			document.getElementById('fileicoid').value=me.fileicoid;
			document.getElementById('ueditor_form').submit();
		}else{
			var exts={"All":['All Documents(*.*)',['DZZDOC','HTM','HTML','SHTM','SHTML','HTA','HTC','XHTML','STM','SSI','JS','JSON','AS','ASC','ASR','XML','XSL','XSD','DTD','XSLT','RSS','RDF','LBI','DWT','ASP','ASA','ASPX','ASCX','ASMX','CONFIG','CS','CSS','CFM','CFML','CFC','TLD','TXT','PHP','PHP3','PHP4','PHP5','PHP-DIST','PHTML','JSP','WML','TPL','LASSO','JSF','VB','VBS','VTM','VTML','INC','SQL','JAVA','EDML','MASTER','INFO','INSTALL','THEME','CONFIG','MODULE','PROFILE','ENGINE'],'selected'],"dzzdoc":['Dzz Documents(*.DZZDOC)',['DZZDOC'],''],"html":['HTML Documents(*.HTML,*.HTM,*.HTA,*.HTC,*.XHTML)',['HTML','HTM','HTA','HTC','XHTML'],''],"txt":['Text Files(*.TXT)',['TXT'],''],"css":['Style Sheets(*.CSS)',['CSS'],''],"js":['JavaScript Documents(*.JS,*.JSON)',['JS','JSON'],''],"jsp":['Java Server Pages(*.JSP,*.JST)',['JSP','JST'],''],"java":['Java Files(*.JAVA)',['JAVA'],''],"asp":['Active Server Pages(*.ASP,*.ASA)',['ASP','ASA'],''],"aspx":['Active Server Plus Pages(*.ASPX,*.ASCX,*.ASMX)',['ASPX','ASCX','ASMX'],''],"php":['PHP Files(*.PHP,*.PHP3,*.PHP4,*.PHP5)',['PHP','PHP3','PHP4','PHP5'],''],"sql":['SQL Files(*.SQL)',['SQL'],''],"vbs":['VBScript Files(*.VBS)',['VBS'],'']};
		
			top.OpenFile('save','保存',exts,{cmd:'save',multiple:false,fileencode:{'UTF-8':['UTF-8','UTF-8','selected'],"ANSI":['ANSI','GBK',''],"BIG-5":['BIG-5','BIG-5','']}},function(data){
				acceptdata(data);
			});
		}
    },
    notNeedUndo : 1
};
