/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-6-9
 * Time: 下午2:00
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.sexam.AnswerS', {
    extend:'Ext.data.Store',
    alias:'store.AnswerS',
    requires:[
    ],
    config:{
        storeId:'AnswerSId',
        fields:[
            'id',
            'content',
            'flag',
            'studentAnswer',
            'options',
//            {name: "options", type: "Array"},
            'typename',
            'options1',
            'options2',
            'options3',
            'options4',
            'index',
            'stylename',             //试题类型
            'aid',
            'auto'                    //是否自动批改
        ],
        proxy: {
            type: 'ajax',
            limitParam: 'limit',     //设置limit参数，默认为limit
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
//                    records[i].set('starttime', new Date(parseInt(records[i].get('starttime')) * 1000).toLocaleString().substr(0,30));
//                    records[i].set("options",records[i].get('options').split(';'));
                    if(records[i].get('stylename') == '选择题'){
                        var options = records[i].get('options').split(';');
                        records[i].set("options1",options[0]);
                        records[i].set("options2",options[1]);
                        records[i].set("options3",options[2]);
                        records[i].set("options4",options[3]);
                    }else{

                    }
                    records[i].set("index",pageNO);
//                    var index = storeThis.indexOf(records[i]);
//                    console.log('index:'+index);
//                    console.log(options[0]);
                }
            }
        }
    }
});