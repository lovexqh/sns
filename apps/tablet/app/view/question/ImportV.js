/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-6-3
 * Time: 上午9:51
 * To change this template use File | Settings | File Templates.
 */
//当前页面数据是否已经从网上取得完毕，以后页面载入时无需再请求网络数据
var isImportVShowed = false;
Ext.define('ExamTeacher.view.question.ImportV', {
    extend:'Ext.Container',
    xtype:'importv',
    requires:[
        'Ext.TitleBar',
        'Ext.dataview.List',
//        'ExamTeacher.store.question.QuestionSelectStore',
        'ExamTeacher.store.question.QuestionListStore'
    ],
    config:{
        layout:'vbox',
        items:[
//顶部titlebar
            {
                xtype:'titlebar',
                docked:'top',
                title:'选择试题',
                items:[
                    {
                        xtype:'button',
                        id:'importVBackModifyListVBtn',
                        ui:'back',
                        text:'返回'
                    },
                    {
                        xtype:'button',
                        id:'previewImportBtn',
                        align:'right',
                        text:'预览'
                    }
                ]
            },
//中间选择selectField
            {
                xtype:'container',
                docked:'top',
                style:'background:#fff',
                height:46,
                layout:'hbox',
                defaults:{
                    width:190
                },
                items:[
          //年级select
                    {
                        xtype: 'selectfield',
                        cls:'selectFileldCls',
                        id:'importGradeId',
                        placeHolder:'不限年级',
                        listeners:{
                            change:function( selectThis, newValue, oldValue, eOpts ){
                                gradeId = newValue;
                //获取并设置科目信息
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
                                            var courseSelect = Ext.getCmp('importCourseId');
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
          //科目select
                    {
                        xtype: 'selectfield',
                        cls:'selectFileldCls',
                        id:'importCourseId',
                        placeHolder:'不限科目',
                        listeners:{
                            change:function( selectThis, newValue, oldValue, eOpts ){
                                courseId = newValue;
                            }
                        }
                    },
                    //类型select
                    {
                        xtype: 'selectfield',
                        cls:'selectFileldCls',
                        id:'importStyleId',
                        placeHolder:'不限类型',
                        listeners:{
                            change:function( selectThis, newValue, oldValue, eOpts ){
                                styleId = newValue;
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
                            console.log('科目id：'+courseId+'课程id：'+gradeId+'类型id：'+styleId);
                            var questionListV = Ext.getCmp('importVListId');
                            console.log(Ext.getClassName(questionListV));
                            var store = questionListV.getStore();
                            store.removeAll();
                            ExamTeacher.app.setStoreProperty(questionListV, 'QuestionListStoreId', rootUrl, {act:'getQuestions',course:courseId, grade:gradeId, style:styleId});
                        }
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
                        flex:40
                    },
                    {
                        xtype:'label',
//                        style:'background:#abcd11;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;科目',
                        flex:10
                    },
                    {
                        xtype:'label',
//                        style:'background:#11cdef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;年级',
                        flex:10
                    },
                    {
                        xtype:'label',
//                        style:'background:#ab11ef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;题型',
                        flex:10
                    },
                    {
                        xtype:'label',
//                        style:'background:#1111ef;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;创建时间',
                        flex:20
                    },
                    {
                        xtype:'label',
//                        style:'background:#11cc11;',
                        baseCls:'labelCenter',
                        html:'&nbsp;&nbsp;操作',
                        flex:10
                    }
                ]
            },
//list
            {
                xtype:'list',
                id:'importVListId',
                flex:1,
                showAnimation:'slide',
//        style:'background:#abcdef',
                cls:'listCls',
                pressedCls: '',
                disableSelection:'false',
                itemTpl:[
//            '<div style="float:left;margin-left: 20px;background: #abcdef">试题id：{id}</div>',
                    '<div style="float:left; padding:10px; width:40%;">{content}</div>',
                    '<div style="float:left; padding:10px; width:10%;">{coursename}</div>',
                    '<div style="float:left; padding:10px; width:10%;">{gradename}</div>',
                    '<div style="float:left; padding:10px; width:10%;">{stylename}</div>',
//            '<div style="float:left;margin-left: 20px;background: #abcdef">知识点：{knowledge}</div>',
                    '<div style="float:left;padding:10px; width:20%; font-size:13px;">{createtime}</div>',
                    '<button name="choose" class="listBtn" style="float:left;margin-top:10px;"><tpl if="!state">选择<tpl else>已选择</tpl></button>'
                ],
                store:{
                    type:'QuestionListStore'
                },
                plugins: [
                    {
                        xclass:'Ext.plugin.ListPaging',
                        autoPaging:false,
                        loadMoreText:'加载更多...',
                        noMoreRecordsText:'再无更多',
                        refreshFn:function(loaded,arguments){
                            loaded.getList().getStore().loadPage(1);
                        }
                    }
                ],
                listeners:{
                    painted:function(){
                        if(!isImportVShowed){

                    //设置年级数组
                            var gradeSelect = Ext.getCmp('importGradeId');
                            gradeSelect.setOptions(gradeArr);
                    //设置题型
                            var typeSelect = Ext.getCmp('importStyleId');
                            typeSelect.setOptions(styleArr);

                            isImportVShowed = true;
//                            ExamTeacher.app.setStoreProperty(this, 'QuestionSelectStoreId', rootUrl, {act:'getQuestionFilter'});
                            ExamTeacher.app.setStoreProperty(this, 'QuestionListStoreId', rootUrl, {act:'getQuestions'});
                            selectCourseId = 'importCourseId';
                            selectGradeId = 'importGradeId';
                            selectStyleId = 'importStyleId';
                        }
                    }
                }
            }
        ]
    }
});