/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-31
 * Time: 下午1:36
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.exam.CorrectQuestionC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.exam.StudentQuestionV'
    ],
    config:{
        refs:{
            CorrectQuestionVBackStudentQuestionVBtn:'#CorrectQuestionVBackStudentQuestionVBtn'        //返回学生列表页面
        },
        control:{
            CorrectQuestionVBackStudentQuestionVBtn:{                           //返回按钮点击事件
                tap:'onCorrectQuestionVBackStudentQuestionVBtnTap'
            }
        },
        routes:{
            'CorrectQuestionVBackStudentQuestionV':'CorrectQuestionVBackStudentQuestionV'  //返回
        }
    },
//事件
//返回
    onCorrectQuestionVBackStudentQuestionVBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'CorrectQuestionVBackStudentQuestionV'}));
    },
//路由
//返回
    CorrectQuestionVBackStudentQuestionV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('studentquestionv', {
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    }

});