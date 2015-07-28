/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 上午8:36
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.exam.ExamV', {
//    extend:'Ext.dataview.List',
    extend:'Ext.Container',
    xtype:'examv',
    requires:[
        'Ext.TitleBar',
        'Ext.Button',
        'ExamTeacher.store.exam.ExamS',
        'Ext.dataview.List'
    ],
    config:{
        layout:'vbox',
        items:[
//顶部titleBar
            {
                xtype:'titlebar',
                title:'考试管理',
                docked:'top',
                items:[
                    {
                        xtype:'button',
                        align:'right',
                        id:'createExamBtn',
                        text:'创建考试'
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
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;考试名',
                        flex:26
                    },
                    {
                        xtype:'label',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;试卷名',
                        flex:12
                    },
                    {
                        xtype:'label',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;科目',
                        flex:12
                    },
                    {
                        xtype:'label',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;开始时间',
                        flex:16
                    },
                    {
                        xtype:'label',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;时长',
                        flex:12
                    },
                    {
                        xtype:'label',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;操作',
                        flex:22
                    }
                ]
            },
//list
            {
                xtype:'list',
                id:'examList',
                flex:1,
                cls:'listCls',
                pressedCls: '',
                disableSelection:'false',
                itemTpl:[
//            '<div style="float:left;margin-left: 20px;background: #abcdef">考试信息id：{id}</div>',
                    '<div style="float:left;padding:10px;width:26%;">{name}</div>',
                    '<div style="float:left;padding:10px;width:12%;">{papername}</div>',
                    '<div style="float:left;padding:10px;width:12%;">{coursename}</div>',
                    '<div style="float:left;padding:10px;width:16%;font-size:13px;">{starttime}</div>',
                    '<div style="float:left;padding:10px;width:12%;font-size:13px;">{duration}分钟</div>',
//            '<div style="float:left;margin-left: 20px;background: #abcdef">试卷id：{paperid}</div><br>',
                    '<div style="float:left;padding:10px;width:22%;">',
                        '<div style="float:left;margin:0 0 10px 0;font-size:14px;color: #abcdef"><tpl if="examine_status==\'考试完毕\'">{correct_status}<tpl else>{examine_status}</tpl></div> ',
                        '<tpl if="examine_status==\'未开考\'"><button name="EditExamV" class="listBtn" style="float:left;">编辑考试</button></tpl> ',
                        '<tpl if="examine_status==\'未开考\'"><button name="DeleteList" class="listBtn" style="float:left;">撤销考试</button></tpl> ',
                        '<tpl if="examine_status==\'考试完毕\' && correct_status!=\'已经全部批改完\'"><button name="CorrectList" class="listBtn" style="float:left;">批改考试</button></tpl>',
                        '<tpl if="correct_status==\'已经全部批改完\'"><button name="GradeList" class="listBtn" style="float:left;">查看成绩</button></tpl>',
                    '</div>'
                ],
                store:{
                    type:'ExamS'
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
//                        if(!isTab3){      //返回该tab时无需再加载
//                            isTab3 = true;
                            ExamTeacher.app.setStoreProperty(this, 'ExamSId', rootUrl, {act:'getExamines'});
//                        }
                    }
                }
            }
        ]
////list
//        Cls:'listCls',
//        pressedCls: '',
//        disableSelection:'false',
//        itemTpl:[
////            '<div style="float:left;margin-left: 20px;background: #abcdef">考试信息id：{id}</div>',
//            '<div style="float:left;margin-left: 20px;background: #abcdef">考试名字：{name}</div>',
//            '<div style="float:left;margin-left: 20px;background: #abcdef">开始时间：{starttime}</div>',
//            '<div style="float:left;margin-left: 20px;background: #abcdef">时长：{duration}</div>',
//            '<div style="float:left;margin-left: 20px;background: #abcdef">年级：{gradename}</div>',
//            '<div style="float:left;margin-left: 20px;background: #abcdef">科目名：{coursename}</div>',
////            '<div style="float:left;margin-left: 20px;background: #abcdef">试卷id：{paperid}</div><br>',
//            '<div style="float:right;background: #abcdef;margin-top: 10px">状态：{flag}</div><br> ',
//            '<button name="EditPaper" style="display: <tpl if="flag!=0">none</tpl>;float: right; margin:10px 0">编辑考试</button> ',
//            '<button name="DeleteList" style="display: <tpl if="flag!=0">none</tpl>;float: right;margin:10px 0">删除考试</button> ',
//            '<button name="CorrectList" style="display: <tpl if="flag!=1">none</tpl>;float: right;margin:10px 0">批改考试</button>',
//            '<button name="GradeList" style="display: <tpl if="flag!=2">none</tpl>;float: right;margin:10px 0">查看成绩</button> '
//        ],
//        store:{
//            type:'ExamS'
//        },
//        plugins: [
//            {
//                xclass:'Ext.plugin.ListPaging',
//                autoPaging:false,
//                loadMoreText:'加载更多...',
//                noMoreRecordsText:'再无更多'
//            }
//        ],
//        listeners:{
//            painted:function(){
//                if(!isTab3){      //返回该tab时无需再加载
//                    isTab3 = true;
//                    ExamTeacher.app.setStoreProperty(this, 'ExamSId', rootUrl, {act:'getExamines'});
//                }
//            }
//        }
    }
});