///import core
///commandsName  attachment
///commandsTitle  附件上传
UE.commands["attachment"] = {
     execCommand : function(){ 
	var me = this;
	alert('attach');
       	top.OpenFile('open','插入附件',{"attach":['附件',['ATTACH','IMAGE'],'']},{cmd:'insertattachment',multiple:true},function(data){
			acceptdata(data);
		}); 
    },
    notNeedUndo : 1
};