/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 上午9:46
 * To change this template use File | Settings | File Templates.
 */
//当前显示的examID
var nowExamID = '';
Ext.define('ExamTeacher.view.exam.StudentV', {
    extend:'Ext.dataview.List',
    xtype:'studentv',
    requires:[
        'Ext.TitleBar',
        'Ext.Button',
        'Ext.Toolbar',
        'ExamTeacher.store.exam.StudentS'
    ],
    config:{
        id:'studentVId',
        showAnimation:'slide',
//顶部titleBar
        items:[
            {
                xtype:'titlebar',
                title:'考试管理 学生',
                docked:'top',
                items:[
                    {
                        xtype:'button',
                        id:'backExamVBtn',
                        ui:'back',
                        text:'返回'
                    }
                ]
            }
//            {
//                xtype:'toolbar',
//                docked:'bottom',
//                items:[
//                    {
//                        xtype: 'spacer'
//                    },
//                    {
//                        xtype:'button',
//                        text:'批改'
//                    }
//                ]
//            }
        ],
        cls:'listCls',
        itemTpl:[
            '<div style="float:left; padding:10px;">学生名称：{uname}</div>'
//            '<div style="float:left;margin-left: 20px;background: #abcdef">考试id：{examineid}</div>',
//            '<div style="float:left;margin-left: 20px;background: #abcdef">paperid：{paperid}</div>'
            ],
        store:{
            type:'StudentS'
        },
        onItemDisclosure:function(record, listitem, index, e){
            console.log('record:'+record.get('question')+'; listitem:'+Ext.getClassName(listitem)+'; index:'+index+': e:'+e);
//            e.target.style.background = 'none';
        },
        listeners:{
            painted:function(){
                if(nowExamID != examId){
                    nowExamID = examId;
                    ExamTeacher.app.setStoreProperty(this, 'studentSId', rootUrl, {act:'getNeedRemarkStus', infoid:examId});
                }
            }
        }
    }
});