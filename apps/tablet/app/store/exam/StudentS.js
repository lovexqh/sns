/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 下午1:00
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.exam.StudentS', {
    extend:'Ext.data.Store',
    alias:'store.StudentS',
    requires:[
//        'Ext.data.proxy.JsonP'
    ],
    config:{
        storeId:'studentSId',
        fields:[
            'uname',
            'examineid',
            'paperid',
            'uid'
        ],
        proxy: {
            type: 'ajax',
            pageParam: 'pageNo',
//            url:rootUrl + '&a=getNeedRemarkStus',
            reader:{
                type:'json',
                rootProperty:''
            }
        }
//        autoLoad:true
    }
});