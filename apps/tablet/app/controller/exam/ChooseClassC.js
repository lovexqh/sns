/**
 * Created with JetBrains WebStorm.
 * User: Administrator
 * Date: 13-6-18
 * Time: 下午1:43
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.exam.ChooseClassC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.exam.ChooseClassV'
    ],
    config:{
        refs:{
            saveChooseBtn:'#saveChooseBtn',          //上传按钮
            ChooseBackCreateBtn:'#ChooseBackCreateBtn',
            chooseClassBtn:'#chooseClassBtn'        //选择班级Btn
        },
        control:{
            saveChooseBtn:{                             //上传按钮点击事件
                tap:'onSaveChooseBtnTap'
            },
            ChooseBackCreateBtn:{
                tap:'onChooseBackCreateBtnTap'
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
//返回
    onChooseBackCreateBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'EditExamVbackMainV'}));
    },
    onchooseClassBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'ChooseClassV'}));
    },
//创建并选择班级
    onSaveChooseBtnTap:function(){
        console.log('上传班级');
//        var form = Ext.ComponentQuery.query('formpanel')[0],
        var form = Ext.getCmp('CheckPanel');
        var cid = form.getValues().classes;
        console.log(cid.length);
        var classesJson = '{';
        for(var i=0; i< cid.length; i++){
            if(cid[i] != null){
                classesJson +=cid[i]+',';
//                console.log(jsonEntity);
            }
        }
        classesJson = classesJson.substring(0,classesJson.length-1)+ '}';
        console.log(classesJson);
        var eid = createExamId;                                     //试卷id
        console.log(eid);
        var sid = schoolId;                                         //学校id
        console.log(sid);
        var jbid = Ext.getCmp('CreategradeNameId').getValue();    //级部id
        console.log(jbid);

        //稍后
        Ext.Viewport.setMasked({ xtype: 'loadmask', message: '请稍候...'});

//        var CreateexamName = Ext.getCmp('CreateexamNameId').getValue();         //考试名
//        var CreatepaperName = Ext.getCmp('CreatepaperNameId').getValue();       //试卷名
//        var CreatecourseName = Ext.getCmp('CreatecourseNameId').getValue();     //科目
//        var CreategradeName = Ext.getCmp('CreategradeNameId').getValue();       //年级
////        var CreateclassName = Ext.getCmp('CreateclassNameId').getValue();       //班级
//        var CreatestartTime = Ext.getCmp('CreatestartTimeId').getValue();       //开始时间
//        var Createduration = Ext.getCmp('CreatedurationId').getValue();         //时长

        Ext.Ajax.request({
            url:rootUrl + '&act=selectClass',
            method:'POST',
            timeout:10000,       //超时时间
            params:{
                classes:classesJson,
                eid:eid,
                sid:sid,
                jbid:jbid
            },
            success:function(response){
                var respText = response.responseText;
                if(respText){
                    Ext.Viewport.setMasked(false);
                    Ext.Msg.alert('通知', '上传成功！', Ext.emptyFn);
                    ExamTeacher.app.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'ChooseClassV'}));
                } else {
                    Ext.Viewport.setMasked(false);
                    Ext.Msg.alert('通知', '上传失败！', Ext.emptyFn);
                }
            }
        });
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