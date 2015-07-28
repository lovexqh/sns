/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-13
 * Time: 下午1:35
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.sexam.FinishPaperV', {
    extend:'Ext.dataview.List',
    xtype:'finishpaperv',
    requires:[
        'Ext.TitleBar',
        'ExamTeacher.store.sexam.FinishPaperS'
    ],
    config:{
        showAnimation:'slide',
        items:[
            {
                xtype:'titlebar',
                title:'查看考试',
                docked:'top',
                items:[
                    {
                        xtype:'button',
                        id:'finishPaperBackMainBtn',
                        ui:'back',
                        text:'返回'
                    }
                ]
            }
        ],
        itemTpl:'{title}==================<br><div style="margin-top: 10px">{createTime}</div> ',
        store:{
            type:'FinishPaperS'
        },
        onItemDisclosure:true
    }
});