/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-13
 * Time: 下午3:23
 * To change this template use File | Settings | File Templates.
*/
Ext.define('ExamTeacher.store.question.PaperListStore', {
    extend:'Ext.data.Store',
    alias:'store.PaperListStore',
//    requires:[
//        'Ext.data.proxy.JsonP'
//    ],
    config:{
        storeId:'paperListStoreId',
        fields:[
            'id',
            'name',
            'coursename',
            'createtime',
            'gradename'
        ],
//        autoLoad:true,
        pageSize:3,
        clearOnPageLoad:false,
        proxy: {
            type: 'ajax',
            limitParam: 'limit',      //设置limit参数，默认为limit
            pageParam: 'pageNo',     //设置page参数，默认为page
            reader:{
                type:'json',
                rootProperty:''
            }
        },
        listeners:{
            load:function( storeThis, records, successful, operation, eOpts ){
//格式化时间
                for(var i = 0; i< records.length; i++){
                    records[i].set('createtime', new Date(parseInt(records[i].get('createtime')) * 1000).toLocaleString().substr(0,30));
                }
            }
        }
    }
});