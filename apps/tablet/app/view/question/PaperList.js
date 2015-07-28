/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 上午7:33
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.question.PaperList', {
    extend:'Ext.dataview.List',
    xtype:'paperlist',
    requires:[
        'ExamTeacher.store.question.PaperListStore',
        'ExamTeacher.store.question.PaperSelectStore',
        'Ext.plugin.ListPaging'
    ],
    stores:[
        'question.PaperListStore'
    ],
    config:{
        id:'paperListId',
        showAnimation:'slide',
        cls:'listCls',
        pressedCls: '',
        disableSelection:'false',
//        defaults:{
//            style:'margin:10px 0'

//        },
        itemTpl:[
//            '<div style="float:left;margin-left: 20px;background: #abcdef;width: 40%;">试卷id：{id}</div>',
            '<div style="float:left;padding:10px;width:32%;">{name}</div>',
            '<div style="float:left;padding:10px;width:17%;">{coursename}</div>',
            '<div style="float:left;padding:10px;width:17%;">{gradename}</div>',
            '<div style="float:left;padding:10px;width:17%;font-size: 13px;">{createtime}</div>',
            '<button name="Edit" class="listBtn" style="float:left;margin:10px 20px 0 0;">编辑</button>',
            '<button name="delete" class="listBtn" style="float:left;margin-top:10px;">删除</button>'
        ],
        store:{
            type:'PaperListStore'
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
//                if(!isTab2){      //返回该tab时无需再加载
//                    isTab2 = true;
                    console.log(chinese+rootUrl);
//                    ExamTeacher.app.setStoreProperty(this, 'paperSelectStoreId', rootUrl, {act:'getPaperFilter'});
                    ExamTeacher.app.setStoreProperty(this, 'paperListStoreId', rootUrl, {act:'getPapers', schoolid:schoolId, uid:userID});
//                }
            }
        }
    }
});