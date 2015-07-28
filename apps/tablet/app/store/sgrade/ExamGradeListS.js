/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-18
 * Time: 下午11:48
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.sgrade.ExamGradeListS', {
    extend:'Ext.data.Store',
    alias:'store.ExamGradeListS',
    config:{
        storeId:'ExamGradeListSId',
        fields:[
            'name',
            'coursename',
            'typename',
            'result'
        ],
//        data:[
//            {title:'第1场',course:'语文',createTime:'2013-5-10',grade:'99'},
//            {title:'第3场',course:'数学',createTime:'2013-5-10',grade:'96'},
//            {title:'第4场',course:'英语',createTime:'2013-5-10',grade:'92'}
//        ],
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