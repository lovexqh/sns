/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-6-7
 * Time: 下午4:14
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.question.IComposeC', {
    extend:'Ext.app.Controller',
    config:{
        refs:{
            iComposeVbackQuestionVBtn:'#iComposeVbackQuestionVBtn',    //返回按钮
            icomposeBtn:'#icomposeBtn'                    //添加试题按钮
        },
        control:{
            iComposeVbackQuestionVBtn:{                         //点击返回试题列表
                tap:'oniComposeVbackQuestionVBtnTap'
            },
            icomposeBtn:{
                tap:'oniComposeBtnTap'
            }
        },
        routes:{                                        //路由
            'backImportV':'backImportV'
        }
    },
//事件
//返回事件
    oniComposeVbackQuestionVBtnTap:function(){
        console.log('\n\r返回import');
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'backImportV'}));
    },
//合成试卷
    oniComposeBtnTap:function(){
    //store
        var composeStore = Ext.getCmp('icomposeVId').getStore();
    //分数
        var scoreInputs = document.getElementsByName('icomposeInputName');
    //判断试题总分与试卷总分是否相同
//        var totleScore = 0;
//        for(var i = 0; i<composeStore.getCount(); i++){
//            totleScore += parseInt(scoreInputs[i].value);
//        }
//        console.log(totleScore);
//        if(totleScore == paperScore){
    //稍后
            Ext.Viewport.setMasked({xtype:'loadmask', message: '请稍候...'});
            var upStr = '';
    //合成传递参数
            for(var i = 0; i<composeStore.getCount(); i++){
                upStr += composeStore.getAt(i).get('id')+','+scoreInputs[i].value+'^';
            }
            console.log(upStr);
    //Ajax提交
            Ext.Ajax.request({
                url:rootUrl+'&act=importQuestions',
                method:'POST',
                timeout:10000,       //超时时间
                params:{
                    pid:modifyPaperId,
                    q:upStr
                },
                success:function(response){
                    console.log('提交了：');
                    Ext.Viewport.setMasked(false);
                    Ext.Msg.alert('通知', '导入试题成功！', Ext.emptyFn);
                },
                failure:function(result, response){
                    console.log('提交失败了：');
                }
            });
//        }else{
//            Ext.Msg.alert('警告', '试题总分与试卷分数不相等！', Ext.emptyFn);
//        }
    },
//路由
//返回
    backImportV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('importv',{
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    }
});