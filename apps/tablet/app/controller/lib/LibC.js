/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-6-6
 * Time: 下午5:18
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.lib.LibC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.lib.CreateQuestionV'
    ],
    config:{
        refs:{
            createQuestionBtn:'#createQuestionBtn',    //返回按钮
            libList:'#libListId'                         //试题list
        },
        control:{
            createQuestionBtn:{                         //点击返回试题列表
                tap:'onCreateQuestionBtnTap'
            },
            libList:{
                itemtap:'onLibListTap'
            }
        },
        routes:{                                        //路由
            'CreateQuestionV':'showCreateQuestionV'
        }
    },
//事件
//返回事件
    onCreateQuestionBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'CreateQuestionV'}));
    },
//删除事件
    onLibListTap:function(tapList, index, target, record, e, eOpts){
        if(e.target.name == 'deleteLib' ){
    //稍后
            Ext.Viewport.setMasked({ xtype: 'loadmask', message: '请稍候...'});
            var questionid = record.get('id');
            console.log('删除id:'+questionid);
            Ext.Ajax.request({
                url:rootUrl + '&act=deleteQuestion',
                method:'POST',
                timeout:10000,       //超时时间
                params:{
                    questionid:questionid
                },
                success:function(response){
                    var respText = Ext.decode(response.responseText);
                    console.log(respText);
                    if(respText==1){
                        Ext.Viewport.setMasked(false);
                        Ext.Msg.alert('通知', '删除成功', Ext.emptyFn);
                        tapList.getStore().removeAt(index);
                    }else{
                        Ext.Msg.alert('通知', '删除失败', Ext.emptyFn);
                    }
                }
            });
        }
    },
//路由
//返回SMainV路由
    showCreateQuestionV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('createquestionv',{
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    }
});