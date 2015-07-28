/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-9
 * Time: 下午9:31
 * To change this template use File | Settings | File Templates.
 */
//修改试卷的id
var modifyPaperId;
Ext.define('ExamTeacher.controller.question.PaperC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.question.PaperV',
//        'ExamTeacher.view.question.QuestionList',
        'ExamTeacher.view.question.PaperList',
        'ExamTeacher.view.question.ComposeV',
        'ExamTeacher.view.question.PaperDetailV',
        'ExamTeacher.view.question.CreatePaperV',
        'ExamTeacher.view.question.ModifyListV'
    ],
    config:{
        refs:{
            paperCreateBtn:'#paperCreateBtn',             //创建按钮
            paperlist:'paperlist'                           //列表
        },
        control:{
            paperCreateBtn:{                                //点击创建按钮
                tap:'onPaperCreateBtn'
            },
            paperlist:{
                itemtap:'onPaperListTap',                 //点击列表
                disclose:'onDisclosure'                   //点击箭头
            }
        },
        routes:{                //路由
            'ainPaperDetailV':'showAgainPaperDetailV',//显示试卷详细内容
            'CreatePaperV':'showCreatePaperV',           //显示“创建试题”页面
            'ModifyListV':'showModifyListV'           //显示“修改试卷”页面
        }
    },
//查看试卷详情
    onPaperListTap:function(tapList, index, target, record, e, eOpts){
//        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'PaperDetailV'}));
        console.log('\n\r点击list；'+e.target.name);
        console.log(tapList.getStore());
        var buttonName = e.target.name;
//        examGradeId = record.get('id');
        var paperid = record.get('id');
        if(buttonName == 'Edit'){                                       //修改
            modifyPaperId = record.get('id');
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'ModifyListV'}));
        }else if(buttonName == 'delete'){                              //删除
//            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'StudentV'}));
            console.log('删除！！！！！！！！！！！！');
            //稍后
            Ext.Viewport.setMasked({ xtype: 'loadmask', message: '请稍候...'});
//            var questionid = record.get('id');
//            console.log('删除id:'+questionid);
            Ext.Ajax.request({
                url:rootUrl + '&act=deletePaper',
                method:'POST',
                timeout:10000,       //超时时间
                params:{
                    paperid:paperid,
                    schoolid:schoolId,
                    uid:userID
                },
                success:function(response){
                    var respText = response.responseText;
                    console.log(respText);
                    if(respText == 1){
                        tapList.getStore().removeAt(index);
                        Ext.Viewport.setMasked(false);
                        Ext.Msg.alert('通知', "删除成功", Ext.emptyFn);
                    } else if (respText == 2) {
                        Ext.Viewport.setMasked(false);
                        Ext.Msg.alert('通知', "删除失败", Ext.emptyFn);
                    }
                }
            });
        }
    },
//    onDisclosure:function( Itemthis, record, target, index, e, eOpts ){
//        console.log('点击箭头'+record.get('id'));
//        passValue = record.get('id');
//        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'ModifyListV'}));
//    },
//创建考试
    onPaperCreateBtn:function(){
        console.log("\n\r创建试卷");
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'CreatePaperV'}));
    },
//路由
//显示试卷详细页面
    showAgainPaperDetailV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('paperdetailv',{
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    },
//显示“创建考试”页面
    showCreatePaperV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('createpaperv',{
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    },
//显示‘修改试卷’页面
    showModifyListV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            console.log('\n\r修改试卷');
            //创建页面
            var modify = Ext.getCmp('modifyListVId');
            if(!modify){
                console.log('创建modify');
                modify = Ext.create('ExamTeacher.view.question.ModifyListV');
            }
            //调用store
//        ExamTeacher.app.setStoreProperty(modify, 'paperSelectStoreId', rootUrl, {act:'getPaperInfo',paperid:passValue});
            Ext.Viewport.animateActiveItem(modify,{
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    },
//滑动动画
    slideFn:function(direction, obj, LContainer){
        LContainer.animateActiveItem(obj,{
            type:'slide',
            direction:direction,
            duration:200
        });
    }
});