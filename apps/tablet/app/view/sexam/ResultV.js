/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-18
 * Time: 下午11:09
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.sexam.ResultV', {
    extend:'Ext.dataview.List',
    xtype:'resultv',
    requires:[
        'Ext.TitleBar'
    ],
    config:{
        showAnimation:'slide',
        items:[
            {
                xtype:'titlebar',
                title:'查看试卷',
                docked:'top',
                items:[
                    {
                        xtype:'button',
                        id:'resultBackFinishPaperBtn',
                        ui:'back',
                        text:'返回'
                    }
                ]
            }
        ]
    }
});