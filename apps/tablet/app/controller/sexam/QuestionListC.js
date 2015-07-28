/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-13
 * Time: 上午11:21
 * To change this template use File | Settings | File Templates.
 */
// -------------------------------- 学生
//已操作答题页面Arr
var answerVArr = [];
//正在操作的answerV页面页数
var nowAnswerVNum = 0;
// ----- AnswerCVS
//试题number
var pageNO;
//试题总数
var pageTotle;
Ext.define('ExamTeacher.controller.sexam.QuestionListC', {
    extend:'Ext.app.Controller',
//    requires:[
//        'ExamStudent.view.exam.ExamListV'
//    ],
    config:{
        refs: {
            squestionlistv:'squestionlistv',                     //examv列表
            questionListBackMain:'#questionListBackMain'    //返回按钮
        },
        control: {
            squestionlistv: {                        //列表点击事件
                itemtap: 'onSQuestionListTap'
            },
            questionListBackMain:{
                tap:'onQuestionListBackMain'
            }
        },
        routes: {                               //MainV路由
            'AnswerV': 'showAnswerV',         //参加考试
            'QuestionListBackSMainV':'QuestionListBackSMainV'//返回首页
        }
    },
//事件
//列表点击事件 显示试题页面
    onSQuestionListTap:function(tapList, index, target, record, e, eOpts){
        pageNO = index+1;
        pageTotle = tapList.getStore().getCount();
        console.log('\n\r试题总数：'+pageTotle);
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'AnswerV'}));
    },
//返回按钮
    onQuestionListBackMain:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'QuestionListBackSMainV'}));
    },
//路由
//跳转到答题页面
    showAnswerV:function(){
        if(role!=3 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            if(!answerVArr[0]){
                answerVArr[0] = Ext.create('ExamTeacher.view.sexam.AnswerV');
            }
            Ext.Viewport.animateActiveItem(answerVArr[0], {
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    },
//返回首页
    QuestionListBackSMainV:function(){
        if(role!=3 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('smainv', {
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    }
});