/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 上午10:36
 * To change this template use File | Settings | File Templates.
 */
//在CorrectQuestionV中用到
var correctIndex;       //试题在store中的index
var correctStore;       //所有试题的store
Ext.define('ExamTeacher.controller.exam.StudentQuestionC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.exam.StudentV',
        'ExamTeacher.view.exam.CorrectQuestionV'
    ],
    config:{
        refs:{
            StudentQuestionVBackStudentVBtn:'#StudentQuestionVBackStudentVBtn',        //返回学生列表页面
            studentquestionv:'studentquestionv'                                             //学生试题列表
        },
        control:{
            StudentQuestionVBackStudentVBtn:{                           //返回按钮点击事件
                tap:'onStudentQuestionVBackStudentVBtnTap'
            },
            studentquestionv:{                                              //点击学生列表
                itemtap:'onStudentQuestionVListTap'
            }
        },
        routes:{
            'StudentQuestionVBackStudentV':'StudentQuestionVBackStudentV',  //返回
            'CorrectQuestionV':'showCorrectQuestionV'
        }
    },
//事件
//点击返回按钮
    onStudentQuestionVBackStudentVBtnTap:function(){
        console.log('\n\r返回学生列表');
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'StudentQuestionVBackStudentV'}));
    },
//itemtap
    onStudentQuestionVListTap:function(tapList, index, target, record, e, eOpts){
//        correctIndex = index;
//        correctStore = tapList.getStore().getAt(1).get('content');
//        correctArr =11111111;
//        console.log(correctStore.getAt(1).get('content'));
//        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'CorrectQuestionV'}));
        if(e.target.name == 'submit'){
    //稍后
            Ext.Viewport.setMasked({xtype: 'loadmask', message: '请稍候...' });
//            var score = record.get('score');        //得分
            var qid = record.get('id');             //试题id
            var score = Ext.get(qid).getValue();
            console.log(score);
    //Ajax提交
            Ext.Ajax.request({
                url:rootUrl+ 'act=doCorrect',
                method:'POST',
                timeout:10000,       //超时时间
                params:{
                    uid:correctStudentId,
                    qid:qid,
                    score:score
                },
                success:function(response){
                    var respText = Ext.decode(response.responseText);
                    console.log(respText);
                    if(respText){
                        Ext.Viewport.setMasked(false);
                        Ext.Msg.alert('通知', respText.message, Ext.emptyFn);
                    } else {
                        Ext.Msg.alert('通知', '提交失败！', Ext.emptyFn);
                    }
                },
                failure:function(result, response){
                    Ext.Viewport.setMasked(false);
                    Ext.Msg.alert('oh, my god', '提交失败！', Ext.emptyFn);
                }
            });
        }
    },
//路由
//返回学生列表页面
    StudentQuestionVBackStudentV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('studentv', {
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    },
//跳转到批改页面
    showCorrectQuestionV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('correctquestionv', {
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    }
});