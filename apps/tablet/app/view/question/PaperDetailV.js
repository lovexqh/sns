/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-11
 * Time: 上午10:58
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.question.PaperDetailV', {
    extend:'Ext.dataview.List',
    xtype:'paperdetailv',
    requires:[
        'Ext.TitleBar',
        'Ext.Button'
    ],
    config:{
        items:[
            {
                xtype:'titlebar',
                title:'试卷详情',
                docked:'top',
                items:[
                    {
                        xtype:'button',
                        ui:'back',
                        id:'detailBackExam',
                        text:'返回'
                    }
                ]
            }
        ],
//list
        pressedCls: '',
        disableSelection:'false',
        itemTpl:'{title}==================<br>' +
            '<div style="margin-top: 10px">{createTime}</div>',
        fields:[
            'title',
            'createTime'
        ],
        data:[
            {title:'试题1',createTime:'2013-5-10'},
            {title:'试题2',createTime:'2013-5-10'},
            {title:'试题3',createTime:'2013-5-10'},
            {title:'试题4',createTime:'2013-5-10'}
        ]
    }
});