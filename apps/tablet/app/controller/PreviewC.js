/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-9
 * Time: 下午3:27
 * To change this template use File | Settings | File Templates.
 */
var tabIndex;
Ext.define('ExamTeacher.controller.PreviewC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.MainV',
        'ExamTeacher.store.question.PaperListStore'
    ],
    config:{
        refs: {
            previewv:'previewv',
            mainV:'mainv',
            libId:'#libId',                      //题库管理
            paperId:'#paperId',                 //试卷管理
            examId:'#examId',                   //考试管理
            gradeId:'#gradeId'                  //成绩管理
        },
        control: {
            libId:{
                tapContainer:'containerTap'
            },
            paperId:{
                tapContainer:'containerTap'
            },
            examId:{
                tapContainer:'containerTap'
            },
            gradeId:{
                tapContainer:'containerTap'
            }
        },
        routes: {                       //previewV路由
            'MainV': 'showMainV'
        }
    },
//事件
//点击container事件 为什么调用2次？
    containerTap:function(me, e, t){
        console.log(me.id);
        switch (me.id){
            case 'libId':
                tabIndex = 0; break;
            case 'paperId':
                tabIndex = 1; break;
            case 'examId':
                tabIndex = 2; break;
            case 'gradeId':
                tabIndex = 3;
        }
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'MainV'}));
    },
//    onButtonTap: function(button, e, options){
//        console.log(button.getText());
//        switch (button.getText()){
//            case '题库管理':
//                tabIndex = 0; break;
//            case '试卷管理':
//                tabIndex = 1; break;
//            case '考试管理':
//                tabIndex = 2; break;
//            case '成绩管理':
//                tabIndex = 3;
//        }
//        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'MainV'}));
//    },
//路由
//跳转到MainV
    showMainV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            var mainV;
            if(this.getMainV()){        //若mainV已创建
                console.log("已创建mainV");
                mainV = this.getMainV();
            }else{                      //若mainV未创建
                console.log("未创建先创建mainV");
                mainV = Ext.create('ExamTeacher.view.MainV');
            }
            mainV.setActive(tabIndex);
            Ext.Viewport.animateActiveItem(mainV, {
                type:'slide',
                direction:'left',
                duration:200
            });
//通过store设置paperSelect；实例化之后才能调用
            var selectStore = Ext.getStore('paperSelectStoreId');
            if(!selectStore){
                Ext.create('ExamTeacher.store.question.PaperSelectStore');
                console.log('\n\r创建PaperSelectStore');
            }else{
                console.log('\n\r已创建PaperSelectStore');
            }
//通过store设置questionSelect；实例化之后才能调用
            var questionSelectStore = Ext.getStore('QuestionSelectStoreId');
            if(!questionSelectStore){
                Ext.create('ExamTeacher.store.question.QuestionSelectStore');
                console.log('\n\r创建QuestionSelectStore');
            }else{
                console.log('\n\r已创建QuestionSelectStore');
            }
        }
    }
});