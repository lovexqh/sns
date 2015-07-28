/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-16
 * Time: 上午10:48
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.question.QuestionSelectStore', {
    extend:'Ext.data.Store',
//    alias:'store.QuestionSelectStore',
    requires:[
        'Ext.data.proxy.JsonP'
    ],
    config:{
        storeId:'QuestionSelectStoreId',
        fields:[
//            {name: 'courses', type: 'Array'},
//            {name: 'grades', type: 'Array'},
//            {name: 'styles', type: 'Array'}
        ],
        proxy: {
            type: 'ajax',
//            limitParam: 'limit',      //设置limit参数，默认为limit
            pageParam: 'pageNo',     //设置page参数，默认为page
            reader:{
                type:'json',
                rootProperty:''
            }
        },
        listeners:{
            load:function( storeThis, records, successful, operation, eOpts ){
                console.log(storeThis.getData());
                console.log(records[0]);
            }
        }
//        listeners:{
//            load:function( storeThis, records, successful, operation, eOpts ){
//                console.log('questionSelect被调用');
//                var courseArr = records[0].get('courses');
//                var gradeArr = records[0].get('grades');
//                var styleArr = records[0].get('styles');
////设置课程select
//                var courseSelect = Ext.getCmp(selectCourseId);
//                var courseOptionsArr = [];
//                for(var i=0; i<courseArr.length; i++){
//                    courseOptionsArr[i] = {text: courseArr[i].coursename, value: courseArr[i].id};
//                }
//                courseOptionsArr.unshift({text:"选择科目", value:0});
//                courseSelect.setOptions(courseOptionsArr);
////设置年级select
//                var gradeSelect = Ext.getCmp(selectGradeId);
//                var gradeOptionsArr = [];
//                for(var i=0; i<gradeArr.length; i++){
//                    gradeOptionsArr[i] = {text: gradeArr[i].gradename, value: gradeArr[i].id};
//                }
//                gradeOptionsArr.unshift({text:"选择年级", value:0});
//                gradeSelect.setOptions(gradeOptionsArr);
////设置类型select
//                var styleSelect = Ext.getCmp(selectStyleId);
//                var styleOptionsArr = [];
//                for(var i=0; i<styleArr.length; i++){
//                    styleOptionsArr[i] = {text: styleArr[i].stylename, value: styleArr[i].id};
//                }
//                styleOptionsArr.unshift({text:"选择类型", value:0});
//                styleSelect.setOptions(styleOptionsArr);
//            }
//        }
    }
});