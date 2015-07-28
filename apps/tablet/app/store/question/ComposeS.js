/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-20
 * Time: 下午1:42
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.question.ComposeS', {
    extend:'Ext.data.Store',
    requires:[
    ],
    alias:'store.ComposeS',
    config:{
        fields:[
            'id',
            'content',
            'stylename',
            'knowledge',
            'createtime',
            'coursename',
            'gradename'
        ],
        autoLoad:true
    }
});