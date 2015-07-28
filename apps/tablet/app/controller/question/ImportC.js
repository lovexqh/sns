/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-6-3
 * Time: 上午10:05
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.question.ImportC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.question.IComposeV'
    ],
    config:{
        refs:{
            importVBackModifyListVBtn:'#importVBackModifyListVBtn',         //返回ModifyListV按钮
            importVList:'#importVListId',
            previewImportBtn:'#previewImportBtn'
        },
        control:{
            importVBackModifyListVBtn:{                                         //返回ModifyListV
                tap:'onImportVBackModifyListVBtnTap'
            },
            importVList:{
                itemtap:'onImportVListTap'
            },
            previewImportBtn:{
                tap:'onPreviewImportBtnTap'
            }
        },
        routes:{                //路由
            'ImportVBackModifyList':'ImportVBackModifyList',                 //返回ModifyListV
            'IComposeV':'showIComposeV'                                         //显示预览页面
        }
    },
//事件
//返回ModifyListV
    onImportVBackModifyListVBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'ImportVBackModifyList'}));
    },
//预览页面
    onPreviewImportBtnTap:function(){
        console.log(importArr);
        importStore = Ext.create('ExamTeacher.store.question.IComposeS');
        for(var i=0; i<importArr.length; i++) {
            if(importArr[i]){
                importStore.add({
                    id:importArr[i].get('id'),
                    content:importArr[i].get('content'),
                    stylename:importArr[i].get('stylename'),
                    knowledge:importArr[i].get('knowledge'),
                    createtime:importArr[i].get('createtime'),
                    coursename:importArr[i].get('coursename'),
                    gradename:importArr[i].get('gradename')
                });
                console.log(importArr[i].get('content'));
            }
        }
        console.log(importStore);
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'IComposeV'}));
    },
//列表点击事件
    onImportVListTap:function(tapList, index, target, record, e, eOpts){
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
                importArr[questionID] = record;
                console.log(importArr);
                console.log('数组长度'+importArr.length);
            }else{
                clickTarget.innerHTML = '选择';
                record.set('state', '');
                console.log('这里这里'+record.get('state'));
                importArr.splice(questionID,1);
                console.log('删除'+questionID+'后：');
                console.log('数组长度'+importArr.length);
                console.log(importArr);
            }
        }
    },
//路由
//返回ModifyListV
    ImportVBackModifyList:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            isImportReturn = 1;
            Ext.Viewport.animateActiveItem('modifylistv',{
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    },
//显示预览页面
    showIComposeV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            var icomposev = Ext.getCmp('icomposeVId');
            if(!icomposev){
                var icomposev = Ext.create( 'ExamTeacher.view.question.IComposeV');
            }
            icomposev.setListStore(importStore);
            Ext.Viewport.animateActiveItem(icomposev,{
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    }
});