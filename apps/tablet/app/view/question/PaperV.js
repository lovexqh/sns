/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-9
 * Time: 下午9:15
 * To change this template use File | Settings | File Templates.
 */
//课程id
var courseId = 1;
//年级id
var gradeId = 1;
Ext.define('ExamTeacher.view.question.PaperV', {
    extend:'Ext.Container',
    xtype:'paperv',
    requires:[
        'Ext.TitleBar',
        'Ext.dataview.List',
        'Ext.Button',
        'ExamTeacher.view.question.PaperList',
        'Ext.field.Select'
    ],
    config:{
        layout:'vbox',
        id:'questionVId',
        items:[
//顶部titlebar
            {
                xtype:'titlebar',
                docked:'top',
                title:'试卷管理',
                items:[
                    {
                        xtype:'button',
                        align:'right',
                        id:'paperCreateBtn',
                        text:'创建'
                    }
                ]
            },
//分类选择container
            {
                xtype:'container',
                style:'background:#fff',
                height:46,
                layout:'hbox',
                defaults:{
                    width:190
                },
                items:[
    //年级
                    {
                        xtype:'selectfield',
                        cls:'selectFileldCls',
                        id:'gradeSelectId',
                        placeHolder:'不限年级',
                        listeners:{
                            change:function( selectThis, newValue, oldValue, eOpts ){
                                gradeId = newValue;
                                //获取科目信息
                                Ext.Ajax.request({
                                    url:phaseUrl+'&act=getCourse',
                                    method:'POST',
                                    timeout:10000,       //超时时间
                                    params:{
                                        jbid:34
                                    },
                                    success:function(response){
                                        var respText = Ext.decode(response.responseText);
                                        console.log(respText);
                                        if(respText){
                                            var courseArr = [];
                                            for(var i=0; i<respText.length; i++){
                                                courseArr[i] = {text: respText[i].kmmc, value: respText[i].id};
                                            }
                                            courseArr.unshift({text:"不限科目", value:0});
                                            console.log(courseArr);
                                            var courseSelect = Ext.getCmp('courseSelectId');
                                            courseSelect.setOptions([]);
                                            courseSelect.setOptions(courseArr);
                                        } else {

                                        }
                                    },
                                    failure:function(result, response){
                                    }
                                });
                            }
                        }
                    },
    //科目
                    {
                        xtype:'selectfield',
                        cls:'selectFileldCls',
                        id:'courseSelectId',
                        placeHolder:'不限科目',
                        listeners:{
                            change:function( selectThis, newValue, oldValue, eOpts ){
                                courseId = newValue;
                            }
                        }
                    },
    //搜索按钮
                    {
                        xtype:'button',
                        text:'搜索',
                        width:150,
                        height:36,
                        margin:'5 0 5 20',
                        style:'background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #5bc9f4), color-stop(1, #1a5da0));color:#fff',
                        handler:function(){
                            console.log('科目id：'+courseId+'课程id：'+gradeId);
                            var paperList = Ext.getCmp('paperListId');
                            var store = paperList.getStore();
                            store.removeAll();
                            ExamTeacher.app.setStoreProperty(paperList, 'paperListStoreId', rootUrl, {act:'getPapers', course:courseId, grade:gradeId, schoolid:schoolId, uid:userID});
                        }
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
//                        style:'background:#abcdef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;试卷名',
                        flex:32
                    },
                    {
                        xtype:'label',
//                        style:'background:#abcd11;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;科目',
                        flex:17
                    },
                    {
                        xtype:'label',
//                        style:'background:#11cdef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;年级',
                        flex:17
                    },
                    {
                        xtype:'label',
//                        style:'background:#ab11ef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;创建时间',
                        flex:17
                    },
                    {
                        xtype:'label',
//                        style:'background:#1111ef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;操作',
                        flex:17
                    }
                ]
            },
//试题列表
            {
                xtype:'paperlist',
                flex:1
            }
        ],
        listeners:{
            painted:function(){
            //设置年级数组
                var gradeSelect = Ext.getCmp('gradeSelectId');
                gradeSelect.setOptions(gradeArr);
            }
        }
    }
});