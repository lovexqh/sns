/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 下午10:13
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.grade.ErrorListV', {
    extend:'Ext.dataview.List',
    xtype:'errorlistv',
    requires:[
        'Ext.TitleBar',
        'Ext.Button',
        'ExamTeacher.store.grade.ErrorListS'
    ],
    config:{
        id:'errorList',
//        style:'background:#abcdef',
        layout:'fit',
        items:[
            {
                xtype:'titlebar',
                title:'错误率',
                items:[
                    {
                        xtype:'button',
                        ui:'back',
                        docked:'top',
                        id:'backGradeVBtn2',
                        text:'返回'
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
                        flex:55
                    },
                    {
                        xtype:'label',
//                        style:'background:#ab11ef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;做题人数',
                        flex:15
                    },
                    {
                        xtype:'label',
//                        style:'background:#abcd11;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;错题人数',
                        flex:15
                    },
                    {
                        xtype:'label',
//                        style:'background:#abcd11;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;正确率',
                        flex:15
                    }
                ]
            }
        ],
//list
//        flex:1,
        cls:'listCls',
        pressedCls: '',
        disableSelection:'false',
//        style:'background:#abcdef',
        itemTpl:[
            '<div style="float:left; padding:10px; width:55%;">{content}</div>',
            '<div style="float:left; padding:10px; width:15%;">{total}</div>',
            '<div style="float:left; padding:10px; width:15%;">{wrong}</div>',
            '<div style="float:left; padding:10px; width:15%;">{q}</div>'
        ],
        store:{
            type:'ErrorListS'
        },
        listeners:{
            painted:function(){
                ExamTeacher.app.setStoreProperty(this, 'ErrorListSId', rootUrl, {act:'questionsWithWrong'});
            }
        }

//        itemTpl:'{title}==================' +
//            '{grade}<br>' +
//            '<div style="margin-top: 10px">{createTime}</div>',
//        fields:[
//            'title',
//            'createTime'
//        ],
//        data:[
//            {title:'错误率1',grade:'100',createTime:'2013-5-10'},
//            {title:'错误率2',grade:'99',createTime:'2013-5-10'},
//            {title:'错误率3',grade:'98',createTime:'2013-5-10'},
//            {title:'错误率4',grade:'97',createTime:'2013-5-10'},
//            {title:'错误率5',grade:'96',createTime:'2013-5-10'},
//            {title:'错误率6',grade:'95',createTime:'2013-5-10'}
//        ]
//        onItemDisclosure:true
    }
});