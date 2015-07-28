/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-11
 * Time: 上午7:40
 * To change this template use File | Settings | File Templates.
 */
//var scoreInputs;
Ext.define('ExamTeacher.view.question.ComposeV', {
    extend:'Ext.dataview.List',
    xtype:'composev',
    requires:[
        'Ext.TitleBar',
        'ExamTeacher.store.question.ComposeS'
//        'ExamTeacher.controller.question.ComposeC'
    ],
    config:{
        id:'composeVId',
        items:[
//顶部titlebar
            {
                xtype:'titlebar',
                docked:'top',
                title:'预览',
                items:[
                    {
                        xtype:'button',
                        ui:'back',
                        id:'backQuestionVBtn',
                        text:'返回'
                    },
                    {
                        xtype:'button',
                        id:'composeBtn',
                        align:'right',
                        text:'上传'
                    }
                ]
            },
//列名
            {
                xtype:'container',
                height:50,
                layout:'hbox',
                docked:'top',
                defaults:{
                    style:'line-height:50px;'
                },
                items:[
                    {
                        xtype:'label',
//                        style:'background:#abcdef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;题干',
                        flex:40
                    },
                    {
                        xtype:'label',
//                        style:'background:#abcd11;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;科目',
                        flex:10
                    },
                    {
                        xtype:'label',
//                        style:'background:#11cdef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;年级',
                        flex:10
                    },
                    {
                        xtype:'label',
//                        style:'background:#ab11ef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;题型',
                        flex:10
                    },
                    {
                        xtype:'label',
//                        style:'background:#1111ef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;创建时间',
                        flex:20
                    },
                    {
                        xtype:'label',
//                        style:'background:#11cc11;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;分数',
                        flex:10
                    }
                ]
            }
        ],
        showAnimation:'slide',
        id:'composeVId',
        cls:'listCls',
        pressedCls: '',
        disableSelection:'false',
        itemTpl:[
            '<div style="float:left; padding:10px; width:40%;">{content}</div>',
            '<div style="float:left; padding:10px; width:10%;">{coursename}</div>',
            '<div style="float:left; padding:10px; width:10%;">{gradename}</div>',
            '<div style="float:left; padding:10px; width:10%;">{stylename}</div>',
            '<div style="float:left;padding:10px; width:20%; font-size:13px;">{createtime}</div>',
            '<input type="text" name="composeInputName" style="float:left; padding:10px; width:10%; border:none;" />'
        ]
    },
    setListStore:function(composeS){
        this.setStore(composeS);
    }
});