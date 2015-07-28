///import core
///commands 另存文件
///commandsName  Savetofile
///commandsTitle  另存文件
/**
 * 打开文件
 * @function
 * @name baidu.editor.execCommand
 * @param   {String}   cmdName     Savetofile另存文件
 */
UE.commands['savetofile'] = {
    execCommand : function(){ 
		var me = this;
		var params={};
		switch(me.filecode){
			case 'UTF-8':
				params.fileencode={'UTF-8':['UTF-8','UTF-8','selected'],"ANSI":['ANSI','GBK',''],"BIG-5":['BIG-5','BIG-5','']};
				break;
			case 'EUC-CN':case 'GB2312':
				params.fileencode={'UTF-8':['UTF-8','UTF-8',''],"ANSI":['ANSI','GBK','selected'],"BIG-5":['BIG-5','BIG-5','']};
				break;
			case 'BIG-5':case 'BIG5':
				params.fileencode={'UTF-8':['UTF-8','UTF-8',''],"ANSI":['ANSI','GBK',''],"BIG-5":['BIG-5','BIG-5','selected']};
				break;
		}
		var exts={"All":['All Documents(*.*)',['DZZDOC','HTM','HTML','SHTM','SHTML','HTA','HTC','XHTML','STM','SSI','JS','JSON','AS','ASC','ASR','XML','XSL','XSD','DTD','XSLT','RSS','RDF','LBI','DWT','ASP','ASA','ASPX','ASCX','ASMX','CONFIG','CS','CSS','CFM','CFML','CFC','TLD','TXT','PHP','PHP3','PHP4','PHP5','PHP-DIST','PHTML','JSP','WML','TPL','LASSO','JSF','VB','VBS','VTM','VTML','INC','SQL','JAVA','EDML','MASTER','INFO','INSTALL','THEME','CONFIG','MODULE','PROFILE','ENGINE'],'selected'],"dzzdoc":['Dzz Documents(*.DZZDOC)',['DZZDOC'],''],"html":['HTML Documents(*.HTML,*.HTM,*.HTA,*.HTC,*.XHTML)',['HTML','HTM','HTA','HTC','XHTML'],''],"txt":['Text Files(*.TXT)',['TXT'],''],"css":['Style Sheets(*.CSS)',['CSS'],''],"js":['JavaScript Documents(*.JS,*.JSON)',['JS','JSON'],''],"jsp":['Java Server Pages(*.JSP,*.JST)',['JSP','JST'],''],"java":['Java Files(*.JAVA)',['JAVA'],''],"asp":['Active Server Pages(*.ASP,*.ASA)',['ASP','ASA'],''],"aspx":['Active Server Plus Pages(*.ASPX,*.ASCX,*.ASMX)',['ASPX','ASCX','ASMX'],''],"php":['PHP Files(*.PHP,*.PHP3,*.PHP4,*.PHP5)',['PHP','PHP3','PHP4','PHP5'],''],"sql":['SQL Files(*.SQL)',['SQL'],''],"vbs":['VBScript Files(*.VBS)',['VBS'],'']};
		
		top.OpenFile('saveto','另存为',exts,{cmd:'saveto',multiple:false,fileencode:params.fileencode},function(data){
			acceptdata(data);
		});
    },
    notNeedUndo : 1
};
