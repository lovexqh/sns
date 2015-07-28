/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-19
 * Time: 上午10:35
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.sgrade.QuestionGradeListV', {
    extend:'Ext.dataview.List',
    xtype:'questiongradelistv',
    requires:[
        'Ext.TitleBar',
        'ExamTeacher.store.sgrade.QuestionGradeListS'
    ],
    config:{
//顶部titleBar
        items:[
            {
                xtype:'titlebar',
                title:'试题成绩',
                docked:'top',
                items:[
                    {
                        xtype:'button',
                        id:'questionBackMainV',
                        ui:'back',
                        text:'返回'
                    }
                ]
            },
//列名
            {
                xtype:'container',
                docked:'top',
                height:50,
                layout:'hbox',
                defaults:{
                    style:'line-height:50px;'
                },
                items:[
                    {
                        xtype:'label',
//                        style:'background:#abcdef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;题干',
                        flex:70
                    },
//                    {
//                        xtype:'label',
////                        style:'background:#ab11ef;',
//                        baseCls:'labelCenter',
//                        html:'&nbsp;&nbsp;类型',
//                        flex:15
//                    },
                    {
                        xtype:'label',
//                        style:'background:#abcd11;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;满分',
                        flex:15
                    },
                    {
                        xtype:'label',
//                        style:'background:#abcd11;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;得分',
                        flex:15
                    }
                ]
            }
        ],
//list
        cls:'listCls',
        pressedCls: '',
        disableSelection:'false',
        itemTpl:[
            '<div style="float:left;padding:10px; width:70%;">{content}</div>' ,
//            '<div style="float:left;padding:10px; width:15%;">{grade}</div>' ,
            '<div style="float:left;padding:10px; width:15%;">{qScore}</div>' ,
            '<div style="float:left;padding:10px; width:15%;">{score}</div>'
        ],
        store:{
            type:'QuestionGradeListS'
        },
        listeners:{
            painted:function(){
                ExamTeacher.app.setStoreProperty(this, 'QuestionGradeListSId', rootUrl, {act:'correct', sid:userID, pid:paperID});
            }
        }
    }
});