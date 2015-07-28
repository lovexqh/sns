/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-6-8
 * Time: 上午9:49
 * To change this template use File | Settings | File Templates.
 */
//供ChooseClassC上传班级用，考试id
var createExamId;
Ext.define('ExamTeacher.controller.exam.CreateExamC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.exam.ChooseClassV'
    ],
    config:{
        refs:{
            saveCreateBtn:'#saveCreateBtn',          //上传按钮
            createExamBackMainBtn:'#createExamBackMainBtn',
            chooseClassBtn:'#chooseClassBtn'        //选择班级Btn
        },
        control:{
            saveCreateBtn:{                             //上传按钮点击事件
                tap:'onSaveCreateBtnTap'
            },
            createExamBackMainBtn:{
                tap:'oncreateExamBackMainBtnTap'
            },
            chooseClassBtn:{
                tap:'onchooseClassBtnTap'
            }
        },
        routes:{
            'ChooseClassV':'showChooseClassV'           //选择班级
        }
    },
//事件
//调用EditExamC返回
    oncreateExamBackMainBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'EditExamVbackMainV'}));
    },
    onchooseClassBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'ChooseClassV'}));
    },
//创建并选择班级
    onSaveCreateBtnTap:function(){
    //稍后
        Ext.Viewport.setMasked({ xtype: 'loadmask', message: '请稍候...'});

        var CreateexamName = Ext.getCmp('CreateexamNameId').getValue();         //考试名
//        var CreatepaperName = Ext.getCmp('CreatepaperNameId').getValue();       //试卷名
        var CreatepaperId = Ext.getCmp('CreatepaperNameId').record.get(Ext.getCmp('CreatepaperNameId').getDisplayField());
        var CreatecourseId = Ext.getCmp('CreatecourseNameId').getValue();     //科目
        var CreatecourseName = Ext.getCmp('CreatecourseNameId').record.get(Ext.getCmp('CreatecourseNameId').getDisplayField());
        var CreategradeName = Ext.getCmp('CreategradeNameId').getValue();       //年级
//        var CreateclassName = Ext.getCmp('CreateclassNameId').getValue();       //班级
        var CreatestartTime = Ext.getCmp('CreatestartTimeId').getValue();       //开始时间
        var Createduration = Ext.getCmp('CreatedurationId').getValue();         //时长

//        var date = Date.parse( CreatestartTime )
        console.log(CreatestartTime);

        Ext.Ajax.request({
            url:rootUrl + '&act=createExamine',
            method:'POST',
            timeout:10000,       //超时时间
            params:{
                sTime:CreatestartTime,
                duration:Createduration,
                name:CreateexamName,
                paperid:CreatepaperId,
                courseid:CreatecourseId,
                coursename:CreatecourseName
            },
            success:function(response){
                var respText = response.responseText;
                if(respText){
                    Ext.Viewport.setMasked(false);
//                    Ext.Msg.alert('通知', '上传成功！', Ext.emptyFn);
                    createExamId = respText;
                    ExamTeacher.app.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'ChooseClassV'}));
                } else {
                    Ext.Viewport.setMasked(false);
                    Ext.Msg.alert('警告', '上传失败', Ext.emptyFn);
                }
            }
        });
//
//        console.log(CreateexamName+CreatepaperName+CreatecourseName);
    },
//路由
//选择班级
    showChooseClassV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('chooseclassv', {
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    }
});