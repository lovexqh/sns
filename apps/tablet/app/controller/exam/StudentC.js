/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 上午9:59
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.exam.StudentC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.MainV',
        'ExamTeacher.view.exam.StudentQuestionV'
    ],
    config:{
        refs:{
            studentv:'studentv',                  //学生名列表
            backExamVBtn:'#backExamVBtn'        //返回考试列表页面
        },
        control:{
            backExamVBtn:{
                tap:'onBackBtnTap'              //返回按钮点击事件
            },
            studentv:{
                itemtap:'onStudentListTap'
            }
        },
        routes:{
            'backExamV':'backExamV',
            'StudentQuestionV':'showStudentQuestionV'
        }
    },
//事件
//点击回退按钮
    onBackBtnTap:function(){
        console.log('\n\r点击studentV返回按钮');
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'backExamV'}));
    },
//Studentlist的itemTap事件
    onStudentListTap:function(tapList, index, target, record, e, eOpts){
//        correctPaperId = tapList.getStore().getAt(index).get('examineid');       //考试id
        correctPaperId = tapList.getStore().getAt(index).get('paperid');       //试卷id
        correctStudentId = tapList.getStore().getAt(index).get('uid');       //试卷id
//        console.log('考试id：'+examineid+'试卷id：'+paperid+ '学生id：'+uid);
        if(e.target.name != 'button'){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'StudentQuestionV'}));
        }
    },
//路由
//返回到主页
    backExamV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('mainv', {
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    },
//前往TestV界面
    showStudentQuestionV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            console.log('显示TestV');
            Ext.Viewport.animateActiveItem('studentquestionv', {
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    }
});