/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 下午3:14
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.grade.GradeV', {
    extend:'Ext.Container',
    xtype:'gradev',
    requires:[
        'Ext.TitleBar',
        'Ext.field.Select',
        'Ext.Label',
        'Ext.dataview.List',
        'ExamTeacher.store.grade.GradeS'
    ],
    config:{
        layout:'vbox',
        showAnimation:'slide',
        items:[
//顶部titleBar
            {
                xtype:'titlebar',
                title:'成绩管理',
                docked:'top'
            },
////分类选择container
//            {
//                xtype:'container',
//                style:'background:#fff',
//                height:46,
//                layout:'hbox',
//                defaults:{
//                    width:190
//                },
//                items:[
//                    {
//                        xtype: 'selectfield',
//                        cls:'selectFileldCls',
//                        placeHolder:'选择科目',
//                        options: [
//                            {text: '科目',  value: 'first'},
//                            {text: 'Second Option', value: 'second'},
//                            {text: 'Third Option',  value: 'third'}
//                        ]
//                    },
//                    {
//                        xtype: 'selectfield',
//                        cls:'selectFileldCls',
//                        placeHolder:'选择年级',
//                        options: [
//                            {text: '年级',  value: 'first'},
//                            {text: 'Second Option', value: 'second'},
//                            {text: 'Third Option',  value: 'third'}
//                        ]
//                    },
//    //搜索按钮
//                    {
//                        xtype:'button',
//                        text:'搜索',
//                        width:150,
//                        height:36,
//                        margin:'5 0 5 20',
//                        style:'background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #5bc9f4), color-stop(1, #1a5da0));color:#fff',
//                        handler:function(){
//                            console.log('科目id：'+courseId+'课程id：'+gradeId);
//                            var paperList = Ext.getCmp('paperListId');
//                            var store = paperList.getStore();
//                            store.removeAll();
//                            ExamTeacher.app.setStoreProperty(paperList, 'paperListStoreId', rootUrl, {act:'getPapers', course:courseId, grade:gradeId});
//                        }
//                    }
//                ]
//            },
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
                        html:'&nbsp;&nbsp;考试名',
                        flex:26
                    },
                    {
                        xtype:'label',
//                        style:'background:#abcd11;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;科目',
                        flex:12
                    },
                    {
                        xtype:'label',
//                        style:'background:#11cdef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;年级',
                        flex:12
                    },
                    {
                        xtype:'label',
//                        style:'background:#ab11ef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;开始时间',
                        flex:16
                    },
                    {
                        xtype:'label',
//                        style:'background:#ab1111;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;时长',
                        flex:12
                    },
                    {
                        xtype:'label',
//                        style:'background:#1111ef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;操作',
                        flex:22
                    }
                ]
            },
//list
            {
                xtype:'list',
                flex:1,
                id:'gradeVInnerListId',
                cls:'listCls',
                pressedCls: '',
                disableSelection:'false',
                itemTpl:[
//            '<div style="float:left;margin-left: 20px;background: #abcdef">考试信息id：{id}</div>',
                    '<div style="float:left;padding:10px;width:26%;">{name}</div>',
                    '<div style="float:left;padding:10px;width:12%;">{coursename}</div>',
                    '<div style="float:left;padding:10px;width:12%;">{gradename}</div>',
                    '<div style="float:left;padding:10px;width:16%;font-size:13px;">{starttime}</div>',
                    '<div style="float:left;padding:10px;width:12%;font-size:13px;">{duration}分钟</div>',
//            '<div style="float:left;margin-left: 20px;background: #abcdef">试卷id：{paperid}</div><br>',
                    '<div style="float:left;padding:10px 10px 10px 0;width:22%;">',
                        '<button name="seeWrong" class="listBtn" style="float:left;">查看错误</button> ',
                        '<button name="seeGrade" class="listBtn" style="float:left;">查看成绩</button> ',
                    '</div>'
                ],
                store:{
                    type:'GradeS'
                },
                plugins: [
                    {
                        xclass:'Ext.plugin.ListPaging',
                        autoPaging:false,
                        loadMoreText:'加载更多...',
                        noMoreRecordsText:'再无更多'
                    }
                ],
                listeners:{
                    painted:function(){
                        if(!isTab4){
                            isTab4 = true;
                            console.log(chinese+rootUrl);
                            ExamTeacher.app.setStoreProperty(this, 'GradeSId', rootUrl, {act:'getFinishedExamine'});
                        }
                    }
                }
            }
        ]
    }
});