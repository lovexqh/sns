/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-15
 * Time: 下午4:31
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.question.ModifyListStore', {
    extend:'Ext.data.Store',
    alias:'store.ModifyListStore',
    requires:[
        'Ext.data.proxy.JsonP'
    ],
    config:{
        storeId:'paperSelectStoreId',
        fields:[
            {name: 'content', type: 'String'},
            {name: 'options', type: 'String'},
            {name: 'imgpath', type: 'String'},
            {name: 'answer', type: 'String'},
            {name: 'flag', type: 'String'},
            {name: 'score', type: 'String'}
        ],
//        id:'paperSelectStoreId',
        proxy: {
            type: 'ajax',
            pageParam: 'pageNo',     //设置page参数，默认为page
//            url:rootUrl + '&a=getPaperInfo',
            reader:{
                type:'json',
                rootProperty:''
            }
        }
    }
});