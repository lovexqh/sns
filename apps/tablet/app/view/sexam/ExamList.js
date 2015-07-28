/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-12
 * Time: 上午10:57
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.sexam.ExamList', {
    extend:'Ext.dataview.List',
    xtype:'examlist',
    requires:[
        'Ext.TitleBar',
        'ExamTeacher.store.sexam.ExamListS',
//        'Ext.dataview.List',
        'Ext.Label'
    ],
    config:{
//顶部titleBar
        items:[
            {
                xtype:'titlebar',
                title:'考试管理',
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
                        html:'&nbsp;&nbsp;操作',
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
            '<div style="float:left; padding:10px; width:55%;">{name}</div>' ,
            '<div style="float:left; padding:10px; width:15%;">{coursename}</div>' ,
            '<div style="float:left; padding:10px; width:15%;">{typename}</div>' ,
            '<div style="float:left; padding:10px; width:15%;">',
                '<button name="QuestionListV" class="listBtn" style="display:<tpl if="flag == 0">block<tpl else>none</tpl>; float:left;">开始考试</button> ' ,
                '<button name="FinishPaperV" class="listBtn" style="display:<tpl if="flag == 1">block<tpl else>none</tpl>; float:left;">未批改</button> ' ,
                '<button name="GradeListV" class="listBtn" style="display:<tpl if="flag == 2">block<tpl else>none</tpl>; float:left;">查看成绩</button> ',
           '</div>'
        ],
        store:{
            type:'ExamListS'
        },
        listeners:{
            painted:function(){
                ExamTeacher.app.setStoreProperty(this, 'sexamlistsId', rootUrl, {act:'studentsExamineList', uid:userID, cid:classId});
            }
        }
    }
});