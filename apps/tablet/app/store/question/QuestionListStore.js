/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-16
 * Time: 上午9:57
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.question.QuestionListStore', {
    extend:'Ext.data.Store',
    alias:'store.QuestionListStore',
    requires:[
        'Ext.data.proxy.JsonP'
    ],
    config:{
        storeId:'QuestionListStoreId',
        fields:[
            'id',
            'content',
            'stylename',
            'knowledge',
            'createtime',
            'coursename',
            'gradename',
            'state'
        ],
        proxy: {
            type: 'ajax',
            pageParam: 'pageNo',     //设置page参数，默认为page
            reader:{
                type:'json',
                rootProperty:''
            }
        },
        pageSize:3,
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