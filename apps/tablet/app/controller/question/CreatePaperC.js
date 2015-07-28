/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-11
 * Time: 下午4:08
 * To change this template use File | Settings | File Templates.
 */
//var paperId;
Ext.define('ExamTeacher.controller.question.CreatePaperC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.question.QuestionListV'
    ],
    config:{
        refs:{
            createBackMainBtn:'#createBackMainBtn',        //考试列表
//            choseQusetionBtn:'#choseQusetionBtn',           //选择试题
            createPaperBtn:'#createPaperBtn'                //保存试卷按钮
        },
        control:{
            createBackMainBtn:{                               //考试列表点击事件
                tap:'onCreateBackMainBtnTap'
            },
            createPaperBtn:{
                tap:'onCreatePaperBtnTap'
            }
        },
        routes:{                                                //路由
            'CreateBackMain':'CreateBackMain',              //返回首页
            'QusetionListV':'QusetionListV'                 //保存后选择试题
        }
    },
//事件
//返回首页
    onCreateBackMainBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'CreateBackMain'}));
    },
//保存创建好的试卷
    onCreatePaperBtnTap:function(){
        var paperName = Ext.getCmp('createNameId').getValue();                                                      //试卷名
        paperScore = Ext.getCmp('createScore').getValue();                                                         //试卷总分
        var uid = userID;                                                                                             //老师id
        var gname = Ext.getCmp('createGradeId').record.get(Ext.getCmp('createGradeId').getDisplayField()) ;     //年级名
        var gradeid = Ext.getCmp('createGradeId').getValue();                                                       //年级id
        var cname = Ext.getCmp('createCourseId').record.get(Ext.getCmp('createCourseId').getDisplayField()) ;   //科目名
        var cid = Ext.getCmp('createCourseId').getValue();                                                          //科目id
        console.log('gradeid: '+gradeid);
        if (paperName && paperScore && !isNaN(paperScore) && gradeid && cid) {
    //稍后
            Ext.Viewport.setMasked({xtype: 'loadmask', message: '请稍候...'});
            Ext.Ajax.request({
                url:rootUrl + '&act=addPaper',
                method:'POST',
                timeout:10000,       //超时时间
                params:{
                    schoolid:schoolId,
                    name:paperName,
                    uid:uid,
                    totalsocore:paperScore,
                    gradename:gname,
                    gradeid:gradeid,
                    cname:cname,
                    cid:cid
                },
                success:function(response){
                    var respText = response.responseText;
                    if(respText){
                        Ext.Viewport.setMasked(false);
                        paperId = respText;
                        console.log(paperId);
//                    console.log(Ext.getClassName(this.getParent()));
//                        Ext.getCmp('createPaperVID').destroy();
                        ExamTeacher.app.getController('ExamTeacher.controller.question.CreatePaperC').getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'QusetionListV'}));
//                        Ext.getCmp('createPaperVID').destroy();
                        Ext.getCmp('createNameId').setValue('');
                        Ext.getCmp('createScore').setValue('');
                    }
                }
            });
        }else{
            Ext.Msg.alert('警告', '试卷信息填写不正确！', Ext.emptyFn);
        }
    },
//路由
//返回首页
    CreateBackMain:function(){
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
//保存后选择试题
    QusetionListV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
    //通过store设置select 切勿删除
            var selectStore = Ext.getStore('QuestionSelectStoreId');
            if(!selectStore){
                Ext.create('ExamTeacher.store.question.QuestionSelectStore');
                console.log('\n\r创建');
            }else{
                console.log('\n\r已创建');
            }
            Ext.Viewport.animateActiveItem('questionlistv', {
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    }
});