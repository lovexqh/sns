/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-19
 * Time: 上午10:46
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.sgrade.QuestionGradeListS', {
    extend:'Ext.data.Store',
    alias:'store.QuestionGradeListS',
    config:{
        storeId:'QuestionGradeListSId',
        fields:[
            'content',
            'qScore',
            'score'
        ],
//        data:[
//            {title:'第1题',grade:'99'},
//            {title:'第3题',grade:'95'},
//            {title:'第4题',grade:'92'}
//        ]
        proxy: {
            type: 'ajax',
            pageParam: 'pageNo',
            reader:{
                type:'json',
                rootProperty:''
            }
        }
    }
});