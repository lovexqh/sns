/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-18
 * Time: 下午4:44
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.sexam.QuestionListS', {
    extend:'Ext.data.Store',
    alias:'store.QuestionListS',
    requires:[
    ],
    config:{
        storeId:'SQuestionListSId',
        fields:[
            'content',
            'aid'
        ],
        proxy: {
            type: 'ajax',
            limitParam: 'limit',      //设置limit参数，默认为limit
            pageParam: 'pageNo',     //设置page参数，默认为page
            reader:{
                type:'json',
                rootProperty:''
            }
        }
    }
});