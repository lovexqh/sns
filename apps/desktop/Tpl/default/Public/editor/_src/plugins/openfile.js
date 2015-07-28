///import core
///commands 打开文件
///commandsName  Openfile
///commandsTitle  打开文件
/**
 * 打开文件
 * @function
 * @name baidu.editor.execCommand
 * @param   {String}   cmdName     Openfile打开文件
 */
UE.commands['openfile'] = {
    execCommand : function(){ 
	var me = this;
		if(me.needsave>1 && confirm('是否保存文档？')){
			me.execCommand('savefile');
		}
		var exts={"All":['All Documents(*.*)',['DZZDOC','HTM','HTML','SHTM','SHTML','HTA','HTC','XHTML','STM','SSI','JS','JSON','AS','ASC','ASR','XML','XSL','XSD','DTD','XSLT','RSS','RDF','LBI','DWT','ASP','ASA','ASPX','ASCX','ASMX','CONFIG','CS','CSS','CFM','CFML','CFC','TLD','TXT','PHP','PHP3','PHP4','PHP5','PHP-DIST','PHTML','JSP','WML','TPL','LASSO','JSF','VB','VBS','VTM','VTML','INC','SQL','JAVA','EDML','MASTER','INFO','INSTALL','THEME','CONFIG','MODULE','PROFILE','ENGINE'],'selected'],"dzzdoc":['Dzz Documents(*.DZZDOC)',['DZZDOC'],''],"html":['HTML Documents(*.HTML,*.HTM,*.HTA,*.HTC,*.XHTML)',['HTML','HTM','HTA','HTC','XHTML'],''],"txt":['Text Files(*.TXT)',['TXT'],''],"css":['Style Sheets(*.CSS)',['CSS'],''],"js":['JavaScript Documents(*.JS,*.JSON)',['JS','JSON'],''],"jsp":['Java Server Pages(*.JSP,*.JST)',['JSP','JST'],''],"java":['Java Files(*.JAVA)',['JAVA'],''],"asp":['Active Server Pages(*.ASP,*.ASA)',['ASP','ASA'],''],"aspx":['Active Server Plus Pages(*.ASPX,*.ASCX,*.ASMX)',['ASPX','ASCX','ASMX'],''],"php":['PHP Files(*.PHP,*.PHP3,*.PHP4,*.PHP5)',['PHP','PHP3','PHP4','PHP5'],''],"sql":['SQL Files(*.SQL)',['SQL'],''],"vbs":['VBScript Files(*.VBS)',['VBS'],'']};
		top.OpenFile('open','打开文档',exts,{cmd:'open',multiple:false},function(data){
			acceptdata(data);
		});
    },
    notNeedUndo : 0
};
