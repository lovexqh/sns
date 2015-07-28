/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-6-7
 * Time: 下午4:12
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.store.question.IComposeS', {
    extend:'Ext.data.Store',
    requires:[
    ],
    alias:'store.IComposeS',
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