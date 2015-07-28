/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-6-9
 * Time: 下午12:13
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.grade.ErrorListS', {
    extend:'Ext.data.Store',
    alias:'store.ErrorListS',
    config:{
        storeId:'ErrorListSId',
        fields:[
            'content',
            'total',
            'wrong',
            'q'
        ],
        proxy: {
            type: 'ajax',
            pageParam: 'pageNo',     //设置page参数，默认为page
//            url:rootUrl + '&act=questionsWithWrong',
            reader:{
                type:'json',
                rootProperty:''
            }
        },
//        autoLoad:true
//        pageSize:3,
//        autoLoad:true
//        listeners:{
//            load:function( storeThis, records, successful, operation, eOpts ){
////格式化时间
//                for(var i = 0; i< records.length; i++){
//                    records[i].set('starttime', new Date(parseInt(records[i].get('starttime')) * 1000).toLocaleString().substr(0,30));
//                }
//            }
//        }
    }
});