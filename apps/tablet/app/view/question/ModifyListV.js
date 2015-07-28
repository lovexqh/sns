/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-14
 * Time: 上午11:08
 * To change this template use File | Settings | File Templates.
 */
//用于判断是否是返回时调用painted
var isImportReturn = 0;
Ext.define('ExamTeacher.view.question.ModifyListV', {
//    extend:'Ext.dataview.List',
    extend:'Ext.Container',
    xtype:'modifylistv',
    requires:[
        'ExamTeacher.store.question.ModifyListStore',
        'Ext.TitleBar'
    ],
    config:{
        id:'modifyListVId',
        layout:'vbox',
        pressedCls: '',
        disableSelection:'false',
        items:[
//顶部titleBar
            {
                xtype:'titlebar',
                title:'编辑试卷',
                docked:'top',
                items:[
                    {
                        xtype:'button',
                        id:'modifyBackMainBtn',
                        ui:'back',
                        text:'返回'
                    },
                    {
                        xtype:'button',
                        id:'importBtn',
                        align:'right',
                        text:'导入'
                    }
//                    {
//                        xtype:'button',
//                        id:'saveModifyBtn',
//                        align:'right',
//                        text:'保存'
//                    }
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
                        html:'&nbsp;&nbsp;题干',
                        flex:70
                    },
                    {
                        xtype:'label',
//                        style:'background:#abcd11;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;题型',
                        flex:10
                    },
                    {
                        xtype:'label',
//                        style:'background:#11cdef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;分值',
                        flex:10
                    },
                    {
                        xtype:'label',
//                        style:'background:#1111ef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;操作',
                        flex:10
                    }
                ]
            },
//list
            {
                xtype:'list',
                id:'modifyListId',
                flex:1,
                cls:'listCls',
                pressedCls: '',
                disableSelection:'false',
                itemTpl:[
                    '<div style="float:left; padding:10px; width:70%;">{content}</div>',
//                    '<div style="float:left;margin: 0 20px 10px 0;background: #abcdef">选择题选项：{options}</div>',
//                    '<div style="float:left;margin: 0 20px 10px 0;background: #abcdef">图片路径：{imgpath}</div>',
//                    '<div style="float:left;margin: 0 20px 10px 0;background: #abcdef">试题答案：{answer}</div>',
                    '<div style="float:left; padding:10px; width:10%;">{flag}</div>',
                    '<div style="float:left; padding:10px; width:10%;">{score}</div>',
                    '<button name="delete" class="listBtn" style="float:left;margin-top:10px;">删除</button>',
                    '<tpl if="imgpath"><img src="{imgpath}" style="margin-left:10px;" width="100px" height="50px" alt="{imgpath}"/></tpl>'
                ],
                store:{
                    type:'ModifyListStore'
                },
                listeners:{
                    painted:function(){
                        if (isImportReturn == 0) {
                            ExamTeacher.app.setStoreProperty(this, 'paperSelectStoreId', rootUrl, {act:'getPaperInfo',paperid:modifyPaperId});
                        }
                        isImportReturn = 0;
                    }
                }
            }
        ]
    }
});