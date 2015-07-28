/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-6-8
 * Time: 下午1:57
 * To change this template use File | Settings | File Templates.
 */
//var correctArr = [];
Ext.define('ExamTeacher.store.exam.StudentQuestionS', {
    extend:'Ext.data.Store',
    alias:'store.StudentQuestionS',
    config:{
        storeId:'StudentQuestionSId',
        fields:[
            'id',
            'content',
            'rightAnswer',
            'options',
            'flag',
            'studentAnswer',
            'result',
            'score',
            'criticism'
        ],
        proxy: {
            type: 'ajax',
            pageParam: 'pageNo',
            reader:{
                type:'json',
                rootProperty:''
            }
        },
        listeners:{
            load:function( storeThis, records, successful, operation, eOpts ){
//                for(var i=0; i<records.length; i++){
//                    correctArr[i] = {content: records[i].get('content')};
//                }
//                correctArr =11111111;
//                console.log(correctArr[1].content);
            }
        }
    }
});