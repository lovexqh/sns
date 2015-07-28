/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-27
 * Time: 下午4:38
 * To change this template use File | Settings | File Templates.
 */
////避免该页反复请求数据
//var isLivShowed = false;
//课程id
var libCourseId = 1;
//年级id
var libGradeId = 1;
//类型id
var libStyleId = 1;
Ext.define('ExamTeacher.view.lib.LibV', {
    extend:'Ext.Container',
    xtype:'libv',
    requires:[
        'Ext.TitleBar',
        'Ext.dataview.List',
        'ExamTeacher.store.question.QuestionListStore',
        'ExamTeacher.store.question.QuestionSelectStore'
    ],
    config:{
        layout:'vbox',
        showAnimation:'slide',
        items:[
//顶部titleBar
            {
                xtype:'titlebar',
                title:'题库管理',
                docked:'top',
                items:[
                    {
                        xtype:'button',
                        id:'createQuestionBtn',
                        text:'创建试题',
                        align:'right'
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
                        id:'libGradeId',
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
                                            var courseSelect = Ext.getCmp('libCourseId');
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
                        id:'libCourseId',
                        placeHolder:'不限科目',
                        listeners:{
                            change:function( selectThis, newValue, oldValue, eOpts ){
                                courseId = newValue;
                            }
                        }
                    },
             //题型select
                    {
                        xtype: 'selectfield',
                        cls:'selectFileldCls',
                        id:'libStyleId',
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
                            var questionListV = Ext.getCmp('libListId');
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
                id:'libListId',
                flex:1,
                cls:'listCls',
                pressedCls: '',
                disableSelection:'false',
                itemTpl:[
                    '<div style="float:left; padding:10px; width:40%;">{content}</div>',
                    '<div style="float:left; padding:10px; width:10%;">{coursename}</div>',
                    '<div style="float:left; padding:10px; width:10%;">{gradename}</div>',
                    '<div style="float:left; padding:10px; width:10%;">{stylename}</div>',
                    '<div style="float:left;padding:10px; width:20%; font-size:13px;">{createtime}</div>',
                    '<button name="deleteLib" class="listBtn" style="float:left;margin-top:10px;">删除</button>'
                ],
                store:{
                    type:'QuestionListStore'
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
   //设置年级数组
                        var gradeSelect = Ext.getCmp('libGradeId');
                        gradeSelect.setOptions(gradeArr);
   //设置题型
                        var typeSelect = Ext.getCmp('libStyleId');
                        typeSelect.setOptions(styleArr);
//                        if(!isLivShowed){
//                            isLivShowed = true;
//                            ExamTeacher.app.setStoreProperty(this, 'QuestionSelectStoreId', rootUrl, {act:'getQuestionFilter'});
//                            ExamTeacher.app.setStoreProperty(this, 'QuestionSelectStoreId', phaseUrl, {act:'getGrade'});
                            ExamTeacher.app.setStoreProperty(this, 'QuestionListStoreId', rootUrl, {act:'getQuestions'});
                            selectCourseId = 'libCourseId';
                            selectGradeId = 'libGradeId';
                            selectStyleId = 'libStyleId';
//                        }
                    }
                }
            }
        ]
    }
});