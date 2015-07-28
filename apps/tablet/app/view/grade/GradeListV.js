/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 下午5:10
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.grade.GradeListV', {
    extend:'Ext.Container',
    xtype:'gradelistv',
    requires:[
        'Ext.dataview.List',
        'Ext.TitleBar',
        'Ext.Button',
        'ExamTeacher.store.grade.GradeListS'
    ],
    config:{
        layout:'vbox',
        id:'gradeListId',
//        style:'background:#abcdef',
        items:[
//顶部titleBar
            {
                xtype:'titlebar',
                title:'查看成绩',
                docked:'top',
                items:[
                    {
                        xtype:'button',
                        ui:'back',
                        id:'backGradeVBtn',
                        text:'返回'
                    }
                ]
            },
//列名
            {
                xtype:'container',
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
                        html:'&nbsp;&nbsp;学生名',
                        flex:50
                    },
                    {
                        xtype:'label',
//                        style:'background:#ab11ef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;提交时间',
                        flex:25
                    },
                    {
                        xtype:'label',
//                        style:'background:#abcd11;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;得分',
                        flex:25
                    }
                ]
            },
//list
            {
                xtype:'list',
                flex:1,
                cls:'listCls',
                pressedCls: '',
                disableSelection:'false',
                itemTpl:[
//                    '<div style="float:left;margin-left: 20px;background: #abcdef">考试id：{examineid}</div>',
                    '<div style="float:left; padding:10px; width:50%;">{uname}</div>',
//                    '<div style="float:left;margin-left: 20px;background: #abcdef">开考时间：{starttime}</div>',
                    '<div style="float:left; padding:10px; width:25%;">{submittime}</div>',
                    '<div style="float:left; padding:10px; width:25%;">{score}</div>'
                ],
                store:{
                    type:'GradeListS'
                },
                listeners:{
                    painted:function(){
                        ExamTeacher.app.setStoreProperty(this, 'GradeListSId', rootUrl, {act:'getExamineResult', infoid:examGradeId});
                    }
                }
            }
        ]
//list
//        pressedCls: '',
//        disableSelection:'false',
////        itemTpl:'{title}==================' +
////            '{grade}<br>' +
////            '<div style="margin-top: 10px">{createTime}</div>',
//        itemTpl:[
//            '<div style="float:left;margin-left: 20px;background: #abcdef">考试id：{examineid}</div>',
//            '<div style="float:left;margin-left: 20px;background: #abcdef">学生名字：{name}</div>',
//            '<div style="float:left;margin-left: 20px;background: #abcdef">开考时间：{starttime}</div>',
//            '<div style="float:left;margin-left: 20px;background: #abcdef">提交时间：{submittime}</div>',
//            '<div style="float:left;margin-left: 20px;background: #abcdef">得分：{score}</div>'
//        ],
//        store:{
//            type:'GradeListS'
//        },
//        listeners:{
//            painted:function(){
//                ExamTeacher.app.setStoreProperty(this, 'GradeListSId', rootUrl, {act:'getExamineResult', infoid:examGradeId});
//            }
//        }
    }
});