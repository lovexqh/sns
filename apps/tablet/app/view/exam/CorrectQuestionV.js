/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-31
 * Time: 下午1:30
 * To change this template use File | Settings | File Templates.
 */
//var correctArr = [];
Ext.define('ExamTeacher.view.exam.CorrectQuestionV', {
    extend:'Ext.Container',
    xtype:'correctquestionv',
    requires:[
        'Ext.TitleBar',
        'Ext.Button',
        'Ext.Panel'
    ],
    config:{
        layout:'vbox',
        items:[
            {
                xtype:'titlebar',
                title:'批改考试',
//                docked:'top',
                items:[
                    {
                        xtype:'button',
                        ui:'back',
                        id:'CorrectQuestionVBackStudentQuestionVBtn',
                        text:'返回'
                    }
                ]
            },
            {
                xtype:'panel',
//                style:'background:#abcdef',
                flex:1,
                tpl:'nihao'+'{content}',
                data:{
                    content:correctStore
                },
//                listeners:{
//                    initialize:function(){
//                        this.getData().content=correctArr[1].content;
//                    }
//                }
            }
//            {
//                xtype:'list',
//                flex:1,
//                style:'background:#abcdef',
//                itemTpl:[
//                    '{content}'
//                ],
//                store:correctStore
//            }
        ]
    }
});