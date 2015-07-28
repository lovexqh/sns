/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-9
 * Time: 下午2:23
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.PreviewV', {
    extend:'Ext.Container',
    xtype:'previewv',
    requires:[
        'Ext.TitleBar',
        'Ext.Button',
        'Ext.Img'
    ],
    config:{
        style:'background:#c3e9fa',
        showAnimation:'slide',
        defaults:{
            centered:true
        },
        items:[
            {
                xtype:'titlebar',
                title:'请选择功能'
            },
//            {
//                xtype:'button',
//                id:'questionM',
//                text:'题库管理',
//                width:200,
//                margin:'50 0 0 200'
//            },
            {
                xtype:'container',
                centered:true,
                style:'width:80%;height:80%;background:#abcdef',
                items:[
//题库管理
                    {
                        xtype:'container',
                        id:'libId',
                        style:'float:left;background:#623dba;width:50%;height:50%;border-right:5px solid #c3e9fa;border-bottom:5px solid #c3e9fa',
                        listeners:{
                            tap:{
                                element: 'element',
                                fn:function() {
                                    console.log('1');
                                }
                            }
                        },
                        defaults:{
                            centered:true
                        },
                        items:[
                            {
                                xtype:'img',
                                src:'resources/images/preview/lib.png',
                                height: 64,
                                width: 64
                            },
                            {
                                xtype:'label',
                                style:'color:#fff;margin-top:110px',
                                html:'题库管理'
                            }
                        ],
                        initialize:function(){
                            this.callParent(arguments);
                            var me = this;
                            var someItem = Ext.getCmp('libId');
                            someItem.element.on({
                                scope : me,
                                tap : function(e, t) {
                                    someItem.fireEvent('tapContainer', me, e, t)
                                }
                            })
                        }
                    },
//试卷管理
                    {
                        xtype:'container',
                        id:'paperId',
                        style:'float:right;background:#d04726;width:50%;height:50%;border-left:5px solid #c3e9fa;border-bottom:5px solid #c3e9fa',
                        listeners:{
                            tap:{
                                element: 'element',
                                fn:function() {
                                    console.log('2');
                                }
                            }
                        },
                        defaults:{
                            centered:true
                        },
                        items:[
                            {
                                xtype:'img',
                                src:'resources/images/preview/paper.png',
                                height: 64,
                                width: 64
                            },
                            {
                                xtype:'label',
                                style:'color:#fff;margin-top:110px',
                                html:'试卷管理'
                            }
                        ],
                        initialize:function(){
                            this.callParent(arguments);
                            var me = this;
                            var someItem = Ext.getCmp('paperId');
                            someItem.element.on({
                                scope : me,
                                tap : function(e, t) {
                                    someItem.fireEvent('tapContainer', me, e, t)
                                }
                            })
                        }
                    },
//考试管理
                    {
                        xtype:'container',
                        id:'examId',
                        style:'float:left;background:#bb1f48;width:50%;height:50%;border-top:5px solid #c3e9fa;border-right:5px solid #c3e9fa',
                        listeners:{
                            tap:{
                                element: 'element',
                                fn:function() {
                                    console.log('3');
                                }
                            }
                        },
                        defaults:{
                            centered:true
                        },
                        items:[
                            {
                                xtype:'img',
                                src:'resources/images/preview/exam.png',
                                height: 64,
                                width: 64
                            },
                            {
                                xtype:'label',
                                style:'color:#fff;margin-top:110px',
                                html:'考试管理'
                            }
                        ],
                        initialize:function(){
                            this.callParent(arguments);
                            var me = this;
                            var someItem = Ext.getCmp('examId');
                            someItem.element.on({
                                scope : me,
                                tap : function(e, t) {
                                    someItem.fireEvent('tapContainer', me, e, t)
                                }
                            })
                        }
                    },
//成绩管理
                    {
                        xtype:'container',
                        id:'gradeId',
                        style:'float:right;background:#68a52f;width:50%;height:50%;border-top:5px solid #c3e9fa;border-left:5px solid #c3e9fa',
                        listeners:{
                            tap:{
                                element: 'element',
                                fn:function() {
                                    console.log('4');
                                }
                            }
                        },
                        defaults:{
                            centered:true
                        },
                        items:[
                            {
                                xtype:'img',
                                src:'resources/images/preview/grade.png',
                                height: 64,
                                width: 80
                            },
                            {
                                xtype:'label',
                                style:'color:#fff;margin-top:110px',
                                html:'成绩管理'
                            }
                        ],
                        initialize:function(){
                            this.callParent(arguments);
                            var me = this;
                            var someItem = Ext.getCmp('gradeId');
                            someItem.element.on({
                                scope : me,
                                tap : function(e, t) {
                                    someItem.fireEvent('tapContainer', me, e, t)
                                }
                            })
                        }
                    }
                ]
            }

//            {
//                xtype:'button',
//                id:'examM',
//                text:'试卷管理',
//                width:200,
//                margin:'50 0 0 200'
//            },
//            {
//                xtype:'button',
//                id:'correctM',
//                text:'考试管理',
//                width:200,
//                margin:'50 0 0 200'
//            },
//            {
//                xtype:'button',
//                id:'statisticsM',
//                text:'成绩管理',
//                width:200,
//                margin:'50 0 0 200'
//            }
        ]
    }
});