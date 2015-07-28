/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 下午4:56
 * To change this template use File | Settings | File Templates.
 */
//查看考试成绩的id
var examGradeId;
Ext.define('ExamTeacher.controller.grade.GradeC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.grade.GradeV',
        'ExamTeacher.view.grade.GradeListV',
        'ExamTeacher.view.grade.ErrorListV'
    ],
    config:{
        refs:{
            gradeVInnerList:'#gradeVInnerListId'                 //考试列表
        },
        control:{
            gradeVInnerList:{                               //考试列表点击事件
                itemtap:'onGradeVInnerListTap'
            }
        },
        routes:{                                   //路由
            'GradeListV':'showGradeListV',
            'ErrorListV':'showErrorListV'
        }
    },
//list的itemTap事件
    onGradeVInnerListTap:function(tapList, index, target, record, e, eOpts){
        if(e.target.name == 'seeGrade'){              //查看成绩
            console.log(e.target);
            examGradeId = record.get('id');
            console.log(examGradeId);
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'GradeListV'}));
        }else if(e.target.name == 'seeWrong'){      //查看错误
            console.log(e.target);
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'ErrorListV'}));
        }
    },
//路由
//跳转到GradeListV
    showGradeListV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            //创建页面
            var gradeListV = Ext.getCmp('gradeListId');
            if(!gradeListV){
                console.log('创建gradeListV');
                gradeListV = Ext.create('ExamTeacher.view.grade.GradeListV');
            }
            //调用store
//        ExamTeacher.app.setStoreProperty('GradeListSId', {infoid:examGradeId}, gradeListV);
            Ext.Viewport.animateActiveItem(gradeListV,{
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    },
//跳转到ErrorListV
    showErrorListV:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('errorlistv',{
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    }
});