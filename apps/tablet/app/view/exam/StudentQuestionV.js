/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 上午10:23
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.exam.StudentQuestionV', {
    extend:'Ext.dataview.List',
    xtype:'studentquestionv',
    requires:[
        'Ext.TitleBar',
        'Ext.Button',
        'Ext.Toolbar',
        'ExamTeacher.store.exam.StudentQuestionS'
    ],
    config:{
        showAnimation:'slide',
//顶部titleBar
        items:[
            {
                xtype:'titlebar',
                title:'批改试卷',
                docked:'top',
                items:[
                    {
                        xtype:'button',
                        id:'StudentQuestionVBackStudentVBtn',
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
//        itemTpl:'{title}==================<br><div style="margin-top: 10px">{createTime}</div> ',
//        fields:[
//            'title',
//            'createTime'
//        ],
        cls:'listCls',
        pressedCls: '',
        disableSelection:'false',
        itemTpl:[
            '<div style="float:left;padding:10px;width:80%;"><div style="float: left; width: 130px"> 试题内容：</div>{content}</div><br>',
//            '<div style="float:left;padding:10px;width:80%;"><div style="float: left; width: 130px"> 选项 (为空表示不是选择题)：</div>{options}</div><br>',
//            '<div style="float:left;padding:10px;width:80%;"><div style="float: left; width: 130px"> 0代表单选，1代表多选,2判断题：</div>{flag}</div><br>',
            '<div style="float:left;padding:10px;width:80%;"><div style="float: left; width: 130px"> 学生答案：</div>{studentAnswer}</div><br>',
            '<div style="float:left;padding:10px;width:80%;"><div style="float: left; width: 130px"> 正确答案：</div>{rightAnswer}</div><br>',
//            '<div style="float:left;padding:10px;width:80%;"><div style="float: left; width: 130px"> 0错1对：</div>{result}</div><br>',
            '<div style="float:left;padding:10px;width:80%;"><div style="float: left; width: 130px"> 得分：</div><input id="{id}" value="{score}"></div><br>',
            '<div style="float:left;padding:10px;width:80%;"><button name="submit">提交</button></div>'
        ],
        store:{
            type:'StudentQuestionS'
        },
//        data:[
//            {title:'试题1',createTime:'2013-5-10'},
//            {title:'试题2',createTime:'2013-5-10'},
//            {title:'试题3',createTime:'2013-5-10'},
//            {title:'试题4',createTime:'2013-5-10'}
//        ],
//        onItemDisclosure:true,
        listeners:{
            painted:function(){
                ExamTeacher.app.setStoreProperty(this, 'StudentQuestionSId', rootUrl, {act:'correct', sid:correctStudentId, pid:correctPaperId});
            }
        }
    }
});