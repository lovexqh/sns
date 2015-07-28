/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-18
 * Time: 下午5:38
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.sexam.FinishPaperC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.sexam.ResultV'
    ],
    config:{
        refs: {
            finishPaperBackMainBtn:'#finishPaperBackMainBtn',            //返回首页按钮
            finishpaperv:'finishpaperv'                                     //列表
        },
        control: {
            finishPaperBackMainBtn: {                                       //返回按钮
                tap: 'onfinishPaperBackMainBtnTap'
            },
            finishpaperv:{                                                    //列表点击事件
                itemtap:'onFinishPaperItemTap'
            }
        },
        routes: {                   //MainV路由
            'finishPaperBackMainV': 'finishPaperBackMainV',         //返回首页
            'ResultV':'showResultV'                                         //显示答题结果页面
        }
    },
//事件
    onfinishPaperBackMainBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'finishPaperBackMainV'}));
    },
    onFinishPaperItemTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'ResultV'}));
    },
//路由
    finishPaperBackMainV:function(){
        if(role!=3 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('smainv', {
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    },
    showResultV:function(){
        if(role!=3 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('resultv', {
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    }
});