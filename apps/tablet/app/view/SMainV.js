Ext.define('ExamTeacher.view.SMainV', {
    extend: 'Ext.tab.Panel',
    xtype: 'smainv',
    requires: [
        'Ext.TitleBar',
        'Ext.Video',
        'ExamTeacher.view.sexam.ExamList',
        'ExamTeacher.view.sgrade.ExamGradeList'
    ],
    config: {
        tabBarPosition: 'bottom',
        showAnimation:'slide',
        items: [
            {
                title: '考试管理',
                iconCls: 'home',
                layout:'card',
                items:[
                    {
                        xtype:'examlist'
                    }
                ]
            },
            {
                title: '成绩管理',
                iconCls: 'action',
                layout:'card',
                items:[
                    {
                        xtype:'examgradelist'
                    }
                ]
            }
        ]
    }
});
