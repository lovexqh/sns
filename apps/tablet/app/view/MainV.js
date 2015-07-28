/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-9
 * Time: 下午3:17
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.MainV', {
    extend:'Ext.tab.Panel',
    xtype:'mainv',
    requires:[
        'ExamTeacher.view.lib.LibV',
        'ExamTeacher.view.question.PaperV',
        'ExamTeacher.view.exam.ExamV',
        'ExamTeacher.view.grade.GradeV'
    ],
    config:{
//        activeItem:1,
        tabBarPosition:'bottom',
        items:[
//第1个选项卡 题库管理
            {
                iconCls:'libIcon',
                title:'题库管理',
                layout:'fit',
                items:[
                    {
                        xtype:'libv'
                    }
                ]
            },
//第2个选项卡 试卷管理
            {
                iconCls:'paperIcon',
                title:'试卷管理',
                layout:'fit',
                items:[
                    {
                        xtype:'paperv'
                    }
                ]
            },
//第3个选项卡 考试管理
            {
                iconCls:'examIcon',
                title:'考试管理',
                layout:'fit',
                items:[
                    {
                        xtype:'examv'
                    }
                ]
            },
//第4个选项卡 成绩管理
            {
                iconCls:'gradeIcon',
                title:'成绩管理',
                layout:'fit',
                items:[
                    {
                        xtype:'gradev'
                    }
                ]
            }
        ]
    },
//设置激活tab
    setActive:function(tabIndex){
        this.setActiveItem(tabIndex);
        console.log('设置了'+tabIndex+Ext.getClassName(this));
    }
});