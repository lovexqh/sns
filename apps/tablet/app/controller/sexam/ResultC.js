/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-18
 * Time: 下午11:14
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.sexam.ResultC', {
    extend:'Ext.app.Controller',
    requires:[

    ],
    config:{
        refs: {
            resultBackFinishPaperBtn:'#resultBackFinishPaperBtn'            //返回首页按钮
        },
        control: {
            resultBackFinishPaperBtn: {                                       //返回按钮
                tap: 'onResultBackFinishPaperBtnTap'
            }
        },
        routes: {                   //MainV路由
            'resultBackFinishPaperV': 'showResultBackFinishPaperV'          //返回首页
        }
    },
//事件
//返回试题列表
    onResultBackFinishPaperBtnTap:function(){
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'resultBackFinishPaperV'}));
    },
//路由
//返回试题列表
    showResultBackFinishPaperV:function(){
        Ext.Viewport.animateActiveItem('finishpaperv', {
            type:'slide',
            direction:'right',
            duration:200
        });
    }
});