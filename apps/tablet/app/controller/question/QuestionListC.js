/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-13
 * Time: 上午9:20
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.question.QuestionListC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.store.question.ComposeS',
        'ExamTeacher.view.question.ComposeV'
    ],
    config:{
        refs:{
            questionlistv:'questionlistv',                        //列表
            questionBackCreateBtn:'#questionBackCreateBtn',      //返回按钮
            previewPaperBtn:'#previewPaperBtn'                    //预览按钮
        },
        control:{
            questionlistv:{
                itemtap:'onQuestionListVTap'
            },
            questionBackCreateBtn:{                                //点击创建按钮
                tap:'onQuestionBackCreateBtnTap'
            },
            previewPaperBtn:{                                       //点击预览按钮
                tap:'onpreviewPaperBtnTap'
            }
        },
        routes:{                //路由
            'questionBackCreate':'showquestionBackCreate',      //返回create页面
//            'questionBackMainV':'questionBackMainV',        //返回MainV
            'ComposeV':'showComposeV'                               //展示预览页面
        }
    },
//事件
//点击列表
    onQuestionListVTap:function(tapList, index, target, record, e, eOpts){
        var clickTarget = e.target;
        console.log('\n\r点击list：'+clickTarget.name);
        console.log('选择id：'+record.get('id'));
        var questionID = record.get('id');
        if(clickTarget.name == 'choose'){
            console.log('选中');
            if(clickTarget.innerHTML == '选择'){
                clickTarget.innerHTML = '已选择';
//改变store以便“加载更多”
                record.set('state', '已选择');
                console.log('这里这里'+record.get('state'));
                composeArr[questionID] = record;
                console.log(composeArr);
                console.log('数组长度'+composeArr.length);
            }else{
                clickTarget.innerHTML = '选择';
                record.set('state', '');
                console.log('这里这里'+record.get('state'));
                composeArr.splice(questionID,1);
                console.log('删除'+questionID+'后：');
                console.log('数组长度'+composeArr.length);
                console.log(composeArr);
            }
        }
    },
//返回按钮点击事件
    onQuestionBackCreateBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'questionBackCreate'}));
//        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'questionBackMainV'}));
    },
//预览试卷
    onpreviewPaperBtnTap:function(){
        console.log(composeArr);
        composeStore = Ext.create('ExamTeacher.store.question.ComposeS');
        for(var i=0; i<composeArr.length; i++) {
            if(composeArr[i]){
                composeStore.add({
                    id:composeArr[i].get('id'),
                    content:composeArr[i].get('content'),
                    stylename:composeArr[i].get('stylename'),
                    knowledge:composeArr[i].get('knowledge'),
                    createtime:composeArr[i].get('createtime'),
                    coursename:composeArr[i].get('coursename'),
                    gradename:composeArr[i].get('gradename')
                });
                console.log(composeArr[i].get('content'));
            }
        }
        console.log(composeStore);
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'ComposeV'}));
    },
//路由
//返回create页面
    showquestionBackCreate:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('createpaperv',{
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    },
//    questionBackMainV:function(){
//        if(role!=2 ){
//            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
//        }else{
//            Ext.Viewport.animateActiveItem('mainv',{
//                type:'slide',
//                direction:'right',
//                duration:200
//            });
//        }
//    },
//预览页面
    showComposeV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            var composev = Ext.getCmp('composeVId');
            if(!composev){
                var composev = Ext.create( 'ExamTeacher.view.question.ComposeV');
            }
            composev.setListStore(composeStore);
            Ext.Viewport.animateActiveItem(composev,{
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    }
});