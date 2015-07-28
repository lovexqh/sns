/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-9
 * Time: 下午5:14
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.MainC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.PreviewV'
    ],
    config:{
        refs:{
            previewv:'previewv'
//            backBtn:'#backBtn'
        },
        control:{
//            backBtn: {                          //跳转到试卷管理
//                tap: 'onbackBtnTap'
//            }
        },
        routes: {                               //previewV路由
//            'backPreviewV':'backShowPreviewV'
        }
    }
//    onbackBtnTap:function(){
//        console.log('点击了');
//        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'backPreviewV'}));
//    },
//    backShowPreviewV:function(){
//        console.log('回来了mainc');
//        Ext.Viewport.animateActiveItem(this.getPreviewv(), {
//            type:'slide',
//            direction:'right',
//            duration:200
//        });
//    }
});