/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-16
 * Time: 下午4:03
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.grade.GradeS', {
    extend:'Ext.data.Store',
    alias:'store.GradeS',
//    requires:[
//        'Ext.data.proxy.JsonP'
//    ],
    config:{
        storeId:'GradeSId',
        fields:[
            'id',
            'name',
            'starttime',
            'duration',
            'gradename',
            'coursename',
            'typename'
//            'flag'
        ],
        proxy: {
            type: 'ajax',
            pageParam: 'pageNo',     //设置page参数，默认为page
//            url:rootUrl + '&a=getFinishedExamine',
            reader:{
                type:'json',
                rootProperty:''
            }
        },
        pageSize:3,
//        autoLoad:true
        listeners:{
            load:function( storeThis, records, successful, operation, eOpts ){
//格式化时间
                for(var i = 0; i< records.length; i++){
                    records[i].set('starttime', new Date(parseInt(records[i].get('starttime')) * 1000).toLocaleString().substr(0,30));
                }
            }
        }
    }
});