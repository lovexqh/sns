/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-19
 * Time: 上午10:24
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.sgrade.ExamGradeListC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.sgrade.QuestionGradeListV'
    ],
    config:{
        refs: {
            examgradelist:'examgradelist'            //成绩列表
        },
        control: {
            examgradelist: {                            //列表点击事件
                itemtap: 'onExamGradeListTap'
            }
        },
        routes: {                   //MainV路由
            'QuestionGradeListV': 'showQuestionGradeListV'    //参加考试
        }
    },
//事件
    onExamGradeListTap:function(tapList, index, target, record, e, eOpts){
//        console.log(record.get('id'));
        paperID = record.get('id');
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'QuestionGradeListV'}));
    },
//路由
//显示试题成绩列表
    showQuestionGradeListV:function(){
        if(role!=3 ){
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