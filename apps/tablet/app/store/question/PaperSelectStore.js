/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-14
 * Time: 下午3:03
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.question.PaperSelectStore', {
    extend:'Ext.data.Store',
//    requires:[
//        'Ext.data.proxy.JsonP'
//    ],
    xtype:'paperselectstore',
    config:{
//        id:'paperSelectStoreId',
        storeId:'paperSelectStoreId',
        fields:[
            {name: 'courses', type: 'Array'},
            {name: 'grades', type: 'Array'}
        ],
        proxy: {
            type:'ajax',
            limitParam: 'limit',      //设置limit参数，默认为limit
            pageParam: 'pageNo',     //设置page参数，默认为page
            reader:{
                type:'json',
                rootProperty:''
            }
        },
//        autoLoad:true,
        listeners:{
            load:function( storeThis, records, successful, operation, eOpts ){
                console.log('selectLoad被调用');
                var courseArr = records[0].get('courses');
                var gradeArr = records[0].get('grades');
//设置课程select
                var courseSelect = Ext.getCmp('courseSelectId');
                var courseOptionsArr = [];
                for(var i=0; i<courseArr.length; i++){
                    courseOptionsArr[i] = {text: courseArr[i].coursename, value: courseArr[i].id};
                }
                courseOptionsArr.unshift({text:"选择科目", value:0});
                courseSelect.setOptions(courseOptionsArr);
//设置年级select
                var gradeSelect = Ext.getCmp('gradeSelectId');
                var gradeOptionsArr = [];
                for(var i=0; i<gradeArr.length; i++){
                    gradeOptionsArr[i] = {text: gradeArr[i].gradename, value: gradeArr[i].id};
                }
                gradeOptionsArr.unshift({text:"选择年级", value:0});
                gradeSelect.setOptions(gradeOptionsArr);
            }
        }
    }
});