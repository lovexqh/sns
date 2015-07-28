/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-13
 * Time: 上午10:49
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.sexam.ExamC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.sexam.FinishPaperV',
        'ExamTeacher.view.sexam.QuestionListV'
    ],
    config:{
        refs: {
            examlist:'examlist'            //examv列表
        },
        control: {
            examlist: {                 //列表点击事件
                itemtap: 'onExamListTap'
            }
        },
        routes: {                   //MainV路由
            'SQuestionListV': 'showSQuestionListV',    //参加考试
            'FinishPaperV': 'showFinishPaperV',         //查看考试
            'SGradeListV': 'showSGradeListV'            //查看成绩
        }
    },
//列表点击事件
    onExamListTap:function(tapList, index, target, record, e, eOpts){
        var buttonName = e.target.name;
//        if(buttonName == 'QuestionListV'){
        if(buttonName == 'QuestionListV'){
            console.log('\n\r参加考试');
            paperID = record.get('id');
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'SQuestionListV'}));
//        }else if(buttonName == 'FinishPaperV'){
//            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'FinishPaperV'}));
        }else if(buttonName == 'GradeListV'){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'SGradeListV'}));
        }
    },
//路由
//参加考试
    showSQuestionListV:function(){
        if(role!= 3 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('squestionlistv', {
                type:'slide',
//            direction:'left',
                duration:200
            });
        }
    },
//批改考试
    showFinishPaperV:function(){
        if(role!= 3 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('finishpaperv', {
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    },
//显示试题成绩列表
    showSGradeListV:function(){
        if(role!= 3 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('questiongradelistv', {
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    }
});