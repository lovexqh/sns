/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-6-6
 * Time: 下午5:22
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.lib.CreateQuestionC', {
    extend:'Ext.app.Controller',
    requires:[
    ],
    config:{
        refs:{
            createQuestionBackSMainVBtn:'#createQuestionBackSMainVBtn',    //返回按钮
            saveQuestionBtn:'#saveQuestionBtn',                                   //上传试题
            createCon:'createCon',
            createA:'createA',
            createB:'createB',
            createC:'createC',
            createD:'createD',
            createAnswer:'createAnswer',
            createDifficulty:'createDifficulty',
//            createImportant:'createImportant',
            createGrade:'createGrade'
//            createVersion:'createVersion'
        },
        control:{
            createQuestionBackSMainVBtn:{                         //点击返回试题列表
                tap:'onCreateQuestionBackSMainVBtnTap'
            },
            saveQuestionBtn:{                                       //创建试题
                tap:'onSaveQuestionBtnTap'
            }
        },
        routes:{                                        //路由
            'CreateQuestionBackSMainV':'CreateQuestionBackSMainV'
        }
    },
//事件
//返回事件
    onCreateQuestionBackSMainVBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'CreateQuestionBackSMainV'}));
    },
//创建试题
    onSaveQuestionBtnTap:function(){
        var createCon = Ext.getCmp('createCon').getValue();                //题干
//        var createA = Ext.getCmp('createA').getValue();                    //A
//        var createB = Ext.getCmp('createB').getValue();                    //B
//        var createC = Ext.getCmp('createC').getValue();                    //C
//        var createD = Ext.getCmp('createD').getValue();                    //D
        var options = Ext.getCmp('options').getValue();                    //选择题选项
        var createAnswer = Ext.getCmp('createAnswer').getValue();         //答案
//        var createDifficulty = Ext.getCmp('createDifficulty').getValue();//难度
//        var schoolId = Ext.getCmp('schoolId').getValue();                 //学校id
        var createGradeId = Ext.getCmp('createGrade').getValue();           //年级id
        var gradename = Ext.getCmp('createGrade').record.get(Ext.getCmp('createGrade').getDisplayField()) ;     //年级名
        var courseid = Ext.getCmp('courseId').getValue();           //科目id
        var coursename = Ext.getCmp('courseId').record.get(Ext.getCmp('courseId').getDisplayField()) ;     //科目名
//        var createVersion = Ext.getCmp('createVersion').getValue();       //版本
        if(createType && createCon && createAnswer && createGrade ){
    //稍后
            Ext.Viewport.setMasked({ xtype: 'loadmask', message: '请稍候...'});
//            var options = createA+'^'+createB+'^'+createC+'^'+createD;
            Ext.Ajax.request({
                url:rootUrl + '&act=addQuestion',
                method:'POST',
                timeout:10000,       //超时时间
                params:{
                    schoolid:schoolId,
                    content:createCon,
                    answer:createAnswer,
                    options:options,
//                    uid:userID,
//                    level:createDifficulty,
//                    weight:createImportant,
                    styleid:createType,
                    gradeid:createGradeId,
                    gradename:gradename,
                    courseid:courseid,
                    coursename:coursename,

//                    versionid:createVersion,
                    schoolid:schoolId
                },
                success:function(response){
                    var respText = response.responseText;
                    if(respText){
                        Ext.Viewport.setMasked(false);
                        Ext.Msg.alert('通知', '上传成功！', Ext.emptyFn);
                    }
                }
            });
        }else{
            Ext.Msg.alert('警告', '试题信息不完整！', Ext.emptyFn);
        }
    },
//路由
//返回SMainV
    CreateQuestionBackSMainV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('mainv',{
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    }
});