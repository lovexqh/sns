/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-15
 * Time: 上午8:16
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.question.ModifyListC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.question.ImportV',
        'ExamTeacher.store.question.QuestionSelectStore'
    ],
    config:{
        refs:{
            modifyBackMainBtn:'#modifyBackMainBtn',         //创建按钮
            importBtn:'#importBtn',                            //导入按钮
            modifyList:'#modifyListId'                        //列表
        },
        control:{
            modifyBackMainBtn:{                                //点击创建按钮
                tap:'onModifyBackMainBtnTap'
            },
            importBtn:{                                //点击导入按钮
                tap:'onImportBtn'
            },
            modifyList:{
                itemtap:'onModifyListTap'
            }
        },
        routes:{                //路由
            'modifyBackMainV':'modifyBackMainV',             //返回首页
            'ImportV':'ShowImportV'             //显示导入页面
        }
    },
//事件
//返回首页
    onModifyBackMainBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'modifyBackMainV'}));
    },
//导入
    onImportBtn:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'ImportV'}));
    },
//删除
    onModifyListTap:function(tapList, index, target, record, e, eOpts){
        console.log('删除id:');
        if(e.target.name == 'delete' ){
            //稍后
            Ext.Viewport.setMasked({ xtype: 'loadmask', message: '请稍候...'});
            var questionid = record.get('id');
            console.log('删除id:'+questionid);
            Ext.Ajax.request({
                url:rootUrl + '&act=dQuestionFromPaper',
                method:'POST',
                timeout:10000,       //超时时间
                params:{
                    qid:questionid,
                    pid:modifyPaperId
                },
                success:function(response){
                    var respText = Ext.decode(response.responseText);
                    console.log(respText);
                    if(respText == 1){
                        Ext.Viewport.setMasked(false);
                        tapList.getStore().removeAt(index);
                        Ext.Msg.alert('通知', "删除成功", Ext.emptyFn);
                    } else {
                        Ext.Viewport.setMasked(false);
                        Ext.Msg.alert('通知', "删除失败", Ext.emptyFn);
                    }
                }
            });
        }
    },
//路由
//返回首页
    modifyBackMainV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('mainv',{
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    },
//跳转到ImportV
    ShowImportV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            var questionSelectStore = Ext.getStore('QuestionSelectStoreId');
            if(!questionSelectStore){
                Ext.create('ExamTeacher.store.question.QuestionSelectStore');
                console.log('\n\rModifyListC创建QuestionSelectStore');
            }else{
                console.log('\n\rModifyListC已创建QuestionSelectStore');
            }
            Ext.Viewport.animateActiveItem('importv',{
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    }
});