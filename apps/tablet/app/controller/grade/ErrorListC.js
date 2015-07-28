/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 下午10:21
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.grade.ErrorListC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.grade.ErrorListV'
    ],
    config:{
        refs:{
            backGradeVBtn:'#backGradeVBtn2'      //点击返回按钮
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
    onBackGradeVBtnTap:function(){
        console.log('\n\r返回GradeV');
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'backGradeV'}));
    }
});