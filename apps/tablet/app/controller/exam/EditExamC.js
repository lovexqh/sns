/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-11
 * Time: 上午11:45
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.exam.EditExamC', {
    extend:'Ext.app.Controller',
    requires:[
//        'ExamTeacher.view.exam.EditPaperV'
    ],
    config:{
        refs:{
            editExamBackMainBtn:'#editExamBackMainBtn',          //返回按钮
            saveEditBtn:'#saveEditBtn'           //编辑按钮
        },
        control:{
            editExamBackMainBtn:{                             //返回按钮点击事件
                tap:'onEditExamBackMainBtnTap'
            },
            saveEditBtn:{                             //编辑
                tap:'onsaveEditBtnTap'
            }
        },
        routes:{                                          //路由
            'EditExamVbackMainV':'EditExamVbackMainV'
        }
    },
//事件
//返回首页
    onEditExamBackMainBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'EditExamVbackMainV'}));
    },
//编辑
    onsaveEditBtnTap:function(){
        //稍后
        Ext.Viewport.setMasked({ xtype: 'loadmask', message: '请稍候...'});

        var examName = Ext.getCmp('examNameId').getValue();         //考试名
        var paperName = Ext.getCmp('paperNameId').getValue();       //试卷名
        var courseName = Ext.getCmp('courseNameId').getValue();     //科目
        var gradeName = Ext.getCmp('gradeNameId').getValue();       //年级
        var className = Ext.getCmp('classNameId').getValue();       //班级
        var startTime = Ext.getCmp('startTimeId').getValue();       //开始时间
        var duration = Ext.getCmp('durationId').getValue();         //时长

        Ext.Ajax.request({
            url:rootUrl + '&act=createExamine',
            method:'POST',
            timeout:10000,       //超时时间
            params:{
                sTmie:startTime,
                duration:duration,
                name:examName,
                courseid:courseName
            },
            success:function(response){
                var respText = response.responseText;
                if(respText){
                    Ext.Viewport.setMasked(false);
                    Ext.Msg.alert('通知', '上传成功！', Ext.emptyFn);
                }
            }
        });
    },
//路由
//返回首页
    EditExamVbackMainV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('mainv', {
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    }
});