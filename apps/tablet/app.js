/*
    This file is generated and updated by Sencha Cmd. You can edit this file as
    needed for your application, but these edits will have to be merged by
    Sencha Cmd when it performs code generation tasks such as generating new
    models, controllers or views and when running "sencha app upgrade".

    Ideally changes to this file would be limited and most work would be done
    in other places (such as Controllers). If Sencha Cmd cannot merge your
    changes and its generated code, it will produce a "merge conflict" that you
    will need to resolve manually.
*/

// DO NOT DELETE - this directive is required for Sencha Cmd packages to work.
//@require @packageOverrides

//<debug>

var chinese = '汉语好认识：'
Ext.Loader.setPath({
    'Ext': 'touch/src',
    'ExamTeacher': 'app'
});
//</debug>

//登陆后返回用户id、role、schoolId
var userID;
var role;
var schoolId;
var phaseId;                        //学段id
var classId;                        //学生班级id
//var courseOptionsArr = [];       //年级数组
//QuestionListV选择的试题 QuestionList
var composeArr = [];
var composeStore = '';
//ImportV添加的试题
var importArr = [];
var importStore = '';
//获得token后的url
var rootUrl = '';
var phaseUrl = '';      //获取学校信息
//tab是否已加载完
//var isTab2 = '';
var isTab3 = '';
var isTab4 = '';
//设置LibV和QuestionV的SelectId
var selectCourseId;
var selectGradeId;
var selectStyleId;
//CreatePaperC获取创建试卷时的总分
var paperScore;
//CreatePaperC设置试卷id，用于ComposeC添加实体
var paperId;
//studentC批改考试
var correctPaperId;     //试卷id
var correctStudentId;  //学生id

//班级arr，ChooseClassV用
var classArr = [];

//  ------------------- 学生
//查看试题结果时用试题id
var paperID;

//var correctArr = [];
//var correctStore;       //所有试题的store
//// -------------------------------- 学生
////已操作答题页面Arr
//var answerVArr = [];
////正在操作的answerV页面页数
//var nowAnswerVNum = 0;

Ext.application({
    name: 'ExamTeacher',

    requires: [
        'Ext.MessageBox'
//        'ExamTeacher.store.question.PaperSelectStore'
    ],

    controllers:[
        'LoginC',
        'PreviewC',
        'MainC',
        'question.PaperC',
        'exam.ExamC',
        'exam.StudentC',
        'exam.StudentQuestionC',
        'grade.GradeC',
        'grade.GradeListC',
        'grade.ErrorListC',
        'question.ComposeC',
        'exam.EditExamC',
        'exam.CorrectQuestionC',
        'question.CreatePaperC',
        'question.QuestionListC',
        'question.ModifyListC',
        'question.ImportC',
        'ExamTeacher.controller.question.IComposeC',
        'ExamTeacher.controller.exam.CreateExamC',
        'ExamTeacher.controller.exam.ChooseClassC',


        'sexam.AnswerC',
        'sexam.ExamC',
        'sexam.FinishPaperC',
        'sexam.QuestionListC',
        'sexam.ResultC',
        'sgrade.ExamGradeListC',
        'sgrade.QuestionGradeListC',
        'ExamTeacher.controller.lib.LibC',
        'ExamTeacher.controller.lib.CreateQuestionC'
    ],

    stores:[
//        'question.PaperListStore'
//        'question.PaperSelectStore'
    ],

    views: [
        'LoginV',
        'PreviewV',
        'MainV'
    ],

    icon: {
        '57': 'resources/icons/Icon.png',
        '72': 'resources/icons/Icon~ipad.png',
        '114': 'resources/icons/Icon@2x.png',
        '144': 'resources/icons/Icon~ipad@2x.png'
    },

    isIconPrecomposed: true,

    startupImage: {
        '320x460': 'resources/startup/320x460.jpg',
        '640x920': 'resources/startup/640x920.png',
        '768x1004': 'resources/startup/768x1004.png',
        '748x1024': 'resources/startup/748x1024.png',
        '1536x2008': 'resources/startup/1536x2008.png',
        '1496x2048': 'resources/startup/1496x2048.png'
    },

    launch: function() {
        // Destroy the #appLoadingIndicator element
        Ext.fly('appLoadingIndicator').destroy();
        Ext.fly('appName').destroy();
        Ext.fly('logo').destroy();

        // Initialize the main view
        Ext.Viewport.add(Ext.create('ExamTeacher.view.LoginV'));
//        Ext.Viewport.add(Ext.create('ExamTeacher.view.PreviewV'));
//        Ext.Viewport.add(Ext.create('ExamTeacher.view.MainV'));
    },

    onUpdated: function() {
        Ext.Msg.confirm(
            "Application Update",
            "This application has just successfully been updated to the latest version. Reload now?",
            function(buttonId) {
                if (buttonId === 'yes') {
                    window.location.reload();
                }
            }
        )
    },
//登陆后改变rootUrl后设置store
    setStoreProperty:function(storeTarget, storeId, url_param, params){
        storeTarget.setMasked({xtype: 'loadmask', message: '请稍候...' });
        var store = Ext.getStore(storeId);
        console.log("setStoreProperty："+Ext.getClassName(store));
        store.getProxy().setUrl(url_param);
        store.setProxy({
            extraParams:params
        });
        store.loadPage(1, function (records, operation, success){}, storeTarget);
    }
});
