/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-12
 * Time: 下午2:19
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.sexam.ExamListS', {
    extend:'Ext.data.Store',
    alias:'store.ExamListS',
    requires:[
    ],
    config:{
        storeId:'sexamlistsId',
        fields:[
//            'title',
//            'createTime',
//            'course',
//            'state',
//            'seeEx',
//            'seeCo',
//            'seeGr'
            'id',
            'name',
            'coursename',
            'typename',
            'flag'
        ],
//        data:[
//            {title:'第1场',course:'语文',createTime:'2013-5-10',state:'即将考试',seeEx:'block', seeCo:'none',seeGr:'none'},
//            {title:'第3场',course:'数学',createTime:'2013-5-10',state:'未批改',seeEx:'none',seeCo:'block',seeGr:'none'},
//            {title:'第4场',course:'英语',createTime:'2013-5-10',state:'已批改',seeEx:'none',seeCo:'block',seeGr:'block'}
//        ],
//        pageSize:3,
        proxy: {
            type: 'ajax',
            limitParam: 'limit',      //设置limit参数，默认为limit
            pageParam: 'pageNo',     //设置page参数，默认为page
            reader:{
                type:'json',
                rootProperty:''
            }
        }
//        listeners:{
//            load:function( storeThis, records, successful, operation, eOpts ){
////格式化时间
//                for(var i = 0; i< records.length; i++){
//                    records[i].set('createtime', new Date(parseInt(records[i].get('createtime')) * 1000).toLocaleString().substr(0,30));
//                }
//            }
//        }
    }
});