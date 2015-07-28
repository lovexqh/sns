/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 下午6:05
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.grade.GradeListC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.grade.GradeListV'
    ],
    config:{
        refs:{
            backGradeVBtn:'#backGradeVBtn'      //点击返回按钮
        },
        control:{
            backGradeVBtn:{                       //返回按钮点击事件
                tap:'onBackGradeVBtnTap'
            }
        },
        routes:{
            'backGradeV':'backGradeV'
        }
    },
//事件
//返回
    onBackGradeVBtnTap:function(){
        console.log('\n\r返回GradeV');
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'backGradeV'}));
    },
//路由
//返回
    backGradeV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('mainv',{
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    }
});