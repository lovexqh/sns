/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-18
 * Time: 下午5:49
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.sexam.FinishPaperS', {
    extend:'Ext.data.Store',
    alias:'store.FinishPaperS',
    requires:[
    ],
    config:{
        fields:[
            'title',
            'createTime'
        ],
        data:[
            {title:'第1题',createTime:'2013-5-10'},
            {title:'第2题',createTime:'2013-5-10'},
            {title:'第3题',createTime:'2013-5-10'},
            {title:'第4题',createTime:'2013-5-10'},
            {title:'第5题',createTime:'2013-5-10'}
        ]
    }
});