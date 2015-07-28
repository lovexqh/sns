/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-13
 * Time: 上午11:11
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.sexam.QuestionListV', {
    extend:'Ext.dataview.List',
    xtype:'squestionlistv',
    requires:[
        'Ext.TitleBar',
        'ExamTeacher.store.sexam.QuestionListS'
    ],
    config:{
        items:[
            {
                xtype:'titlebar',
                title:'考试题列表',
                docked:'top',
                items:[
                    {
                        xtype:'button',
                        id:'questionListBackMain',
                        ui:'back',
                        text:'返回'
                    }
                ]
            }
        ],
        showAnimation:'slide',
        itemTpl:'{content}',
        store:{
            type:'QuestionListS'
        },
//        onItemDisclosure:true,
        listeners:{
            painted:function(){
                ExamTeacher.app.setStoreProperty(this, 'SQuestionListSId', rootUrl, {act:'correct', sid:userID, pid:paperID, action:'play'});
            }
        }
    }
});