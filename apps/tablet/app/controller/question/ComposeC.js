/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-11
 * Time: 上午8:02
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.question.ComposeC', {
    extend:'Ext.app.Controller',
    requires:[
    ],
    config:{
        refs:{
            backQuestionVBtn:'#backQuestionVBtn',    //返回按钮
            composeBtn:'#composeBtn'                    //添加试题按钮
        },
        control:{
            backQuestionVBtn:{                         //点击返回试题列表
                tap:'onBackQuestionVBtnTap'
            },
            composeBtn:{
                tap:'onComposeBtnTap'
            }
        },
        routes:{                                        //路由
            'backQuestionV':'backQuestionV'
        }
    },
//事件
//返回事件
    onBackQuestionVBtnTap:function(){
        console.log('\n\r返回首页');
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'backQuestionV'}));
    },
//合成试卷
    onComposeBtnTap:function(){
//store
        var composeStore = Ext.getCmp('composeVId').getStore();
//分数
        var scoreInputs = document.getElementsByName('composeInputName');
//判断试题总分与试卷总分是否相同
        var totleScore = 0;
        for(var i = 0; i<composeStore.getCount(); i++){
            totleScore += parseInt(scoreInputs[i].value);
        }
        console.log(totleScore);
        if(totleScore == paperScore){
//稍后
            Ext.Viewport.setMasked({xtype:'loadmask', message: '请稍候...'});
            var upStr = '';
//合成传递参数
//            var store = Ext.getStore('QuestionListStoreId');
            for(var i = 0; i<composeStore.getCount(); i++){
                upStr += composeStore.getAt(i).get('id')+','+scoreInputs[i].value+'^';
//                store.getAt(composeStore.getAt(i).get('id')).set('state', '');
//                console.log(store.getAt(composeStore.getAt(i).get('id')));
            }
            console.log(upStr);
//Ajax提交
            Ext.Ajax.request({
                url:rootUrl+'&act=importQuestions',
                method:'POST',
                timeout:10000,       //超时时间
                params:{
                    pid:paperId,
                    q:upStr
                },
                success:function(response){
                    console.log('提交了：');
                    Ext.Viewport.setMasked(false);
                    Ext.Msg.alert('通知', '创建试卷成功！', Ext.emptyFn);
                },
                failure:function(result, response){
                    console.log('提交失败了：');
                }
            });
        }else{
            Ext.Msg.alert('警告', '试题总分与试卷分数不相等！', Ext.emptyFn);
        }
//        var store = Ext.getStore('QuestionListStoreId');
//        store.removeAll();
//        store.getProxy().clear();
    },
//路由
//返回
    backQuestionV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('questionlistv',{
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    }
});