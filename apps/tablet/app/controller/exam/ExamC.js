/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 上午9:12
 * To change this template use File | Settings | File Templates.
 */
//考试id，供studentV调用显示参加考试的学生列表
var examId;
//试卷id，供EditExamV显示
var examRecord;
Ext.define('ExamTeacher.controller.exam.ExamC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.exam.ExamV',
        'ExamTeacher.view.exam.StudentV',
        'ExamTeacher.view.question.PaperDetailV',
//        'ExamTeacher.view.question.CreatePaperV'
        'ExamTeacher.view.exam.EditExamV',
        'ExamTeacher.view.exam.CreateExamV'
    ],
    config:{
        refs:{
            examList:'#examList',                           //考试列表
            createExamBtn:'#createExamBtn'              //创建考试
        },
        control:{
            examList:{                                 //考试列表点击事件
                itemtap:'onExamListTap'
            },
            createExamBtn:{                             //创建考试
                tap:'onCreateExamBtnTap'
            }
        },
        routes:{                                    //路由
            'StudentV':'showStudentV',           //批改考试
//            'PaperDetailV':'showPaperDetailV',  //查看试卷
            'EditExamV':'showEditExamV',          //编辑考试
            'examToGradeListV':'showExamToGradeListV',   //查看成绩
            'CreateExamV':'showCreateExamV'     //创建考试
        }
    },
//事件
//考试列表list点击
    onExamListTap:function(tapList, index, target, record, e, eOpts){
        console.log('\n\r点击list；'+e.target.name);
        var buttonName = e.target.name;
        examId = record.get('id');
        if(buttonName == 'EditExamV'){                      //编辑考试
            examRecord = record;
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'EditExamV'}));
        }else if(buttonName == 'CorrectList'){              //批改考试
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'StudentV'}));
        }else if(buttonName == 'GradeList'){                //查看成绩
            examGradeId = examId;
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'examToGradeListV'}));
        } else if (buttonName == 'DeleteList'){
            console.log('删除考试');
            this.deleteExam(examId);
        }
    },
//创建考试
    onCreateExamBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'CreateExamV'}));
    },
    deleteExam:function(examID){
//稍后
        Ext.Viewport.setMasked({ xtype: 'loadmask', message: '请稍候...'});
        Ext.Ajax.request({
            url:rootUrl + '&act=undoExamine',
            method:'POST',
            timeout:10000,       //超时时间
            params:{
                infoid:examID
            },
            success:function(response){
                var respText = Ext.decode(response.responseText);
                if(respText){
                    Ext.Viewport.setMasked(false);
                    Ext.Msg.alert('通知', respText, Ext.emptyFn);
                } else {
                    Ext.Viewport.setMasked(false);
                    Ext.Msg.alert('通知', respText, Ext.emptyFn);
                }
            }
        });
    },
//路由
//批改考试
    showStudentV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            console.log('显示studentV');
            //创建页面
            var studentV = Ext.getCmp('studentVId');
            if(!studentV){
                console.log('创建studentV');
                studentV = Ext.create('ExamTeacher.view.exam.StudentV');
            }
            Ext.Viewport.animateActiveItem(studentV, {
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    },
//编辑考试
    showEditExamV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('editexamv', {
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    },
//查看成绩
    showExamToGradeListV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('gradelistv',{
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    },
//创建考试
    showCreateExamV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('createexamv',{
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    }
});