/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-18
 * Time: 下午11:42
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.sgrade.ExamGradeList', {
    extend:'Ext.dataview.List',
    xtype:'examgradelist',
    requires:[
        'Ext.TitleBar',
        'ExamTeacher.store.sgrade.ExamGradeListS'
    ],
    config:{
//顶部titleBar
        items:[
            {
                xtype:'titlebar',
                title:'考试成绩',
                docked:'top'
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
                        html:'&nbsp;&nbsp;考试名',
                        flex:55
                    },
                    {
                        xtype:'label',
//                        style:'background:#abcd11;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;科目',
                        flex:15
                    },
                    {
                        xtype:'label',
//                        style:'background:#ab11ef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;类型',
                        flex:15
                    },
                    {
                        xtype:'label',
//                        style:'background:#1111ef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;成绩',
                        flex:15
                    }
                ]
            }
        ],
//list
        cls:'listCls',
//        itemTpl:['{title}===',
//            '<div style="margin-top: 10px">{grade}</div>'
//        ],
        itemTpl:[
            '<div style="float:left; padding:10px; width:55%;">{name}</div>' ,
            '<div style="float:left; padding:10px; width:15%;">{coursename}</div>' ,
            '<div style="float:left; padding:10px; width:15%;">{typename}</div>' ,
            '<div style="float:left; padding:10px; width:15%;">{result}</div>'],
        store:{
            type:'ExamGradeListS'
        },
        listeners:{
            painted:function(){
                ExamTeacher.app.setStoreProperty(this, 'ExamGradeListSId', rootUrl, {act:'studentsExamineList', uid:userID, cid:1});
            }
        }
    }
});