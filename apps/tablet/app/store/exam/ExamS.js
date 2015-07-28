/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-16
 * Time: 下午1:21
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.exam.ExamS', {
    extend:'Ext.data.Store',
    alias:'store.ExamS',
    requires:[
        'Ext.data.proxy.JsonP'
    ],
    config:{
        storeId:'ExamSId',
        fields:[
            'id',
            'name',
            'starttime',
            'duration',
            'flag',
//            'gradename',
            'coursename',
//            'typename',
            'paperid',
            'papername',
            'correct_status',       //批改状态
            'corrects',             //批改人数
            'peoples',              //参加人数
            'examine_status'        //考试状态
//            'seeEd',
//            'seeDe',
//            'seeCo',
//            'seeGr'
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
//        autoLoad:true,
        listeners:{
            load:function( storeThis, records, successful, operation, eOpts ){
//格式化时间
                for(var i = 0; i< records.length; i++){
                    var startTime = records[i].get('starttime');
                    var nowTime = new Date();
                    if(nowTime > startTime){

                    }else{

                    }
                    records[i].set('starttime', new Date(parseInt(records[i].get('starttime')) * 1000).toLocaleString().substr(0,30));
                }
            }
        }
    }
});