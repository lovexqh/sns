/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-19
 * Time: 上午10:55
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.sgrade.QuestionGradeListC', {
    extend:'Ext.app.Controller',
    requires:[
    ],
    config:{
        refs: {
            questionBackMainV:'#questionBackMainV'       //返回首页按钮
        },
        control: {
            questionBackMainV: {                            //返回按钮点击事件
                tap: 'onQuestionBackMainVTap'
            }
        },
        routes: {                   //路由
            'SQuestionBackMainV': 'SQuestionBackMainV'    //返回路由
        }
    },
//事件
//点击返回按钮
    onQuestionBackMainVTap:function(){
//        history.back();
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'SQuestionBackMainV'}));
    },
//路由
//返回首页路由
    SQuestionBackMainV:function(){
        Ext.Viewport.animateActiveItem('smainv', {
            type:'slide',
            direction:'right',
            duration:200
        });
    }
});