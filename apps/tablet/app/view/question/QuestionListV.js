/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-10
 * Time: 上午6:53
 * To change this template use File | Settings | File Templates.
 */
//课程id
var courseId = 1;
//年级id
var gradeId = 1;
//类型id
var styleId = 1;
//选择试题页面是否已经加载完
var isQuestionShowed = '';
Ext.define('ExamTeacher.view.question.QuestionListV', {
    extend:'Ext.dataview.List',
    xtype:'questionlistv',
    requires:[
        'Ext.data.Store',
        'Ext.TitleBar',
        'Ext.Button',
        'Ext.field.Select',
        'ExamTeacher.store.question.QuestionListStore',
        'Ext.plugin.ListPaging',
        'ExamTeacher.store.question.QuestionSelectStore'
    ],
    config:{
        id:'questionListVId',
        items:[
//顶部titlebar
            {
                xtype:'titlebar',
                docked:'top',
                title:'选择试题',
                items:[
                    {
                        xtype:'button',
                        id:'questionBackCreateBtn',
                        ui:'back',
                        text:'返回'
                    },
                    {
                        xtype:'button',
                        id:'previewPaperBtn',
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
                        id:'questionGradeId',
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
                                            var courseSelect = Ext.getCmp('questionCourseId');
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
                        id:'questionCourseId',
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
                        id:'questionStyleId',
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
                            var questionListV = Ext.getCmp('questionListVId');
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
            }
        ],
//        id:'questionListId',
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
                noMoreRecordsText:'再无更多'
            }
        ],
        listeners:{
            painted:function(){
                if(!isQuestionShowed ){
                    isQuestionShowed = true;
//                    ExamTeacher.app.setStoreProperty(this, 'QuestionSelectStoreId', rootUrl, {act:'getQuestionFilter'});
    //设置年级数组
                    var gradeSelect = Ext.getCmp('questionGradeId');
                    gradeSelect.setOptions(gradeArr);
    //设置题型
                    var typeSelect = Ext.getCmp('questionStyleId');
                    typeSelect.setOptions(styleArr);
                    ExamTeacher.app.setStoreProperty(this, 'QuestionListStoreId', rootUrl, {act:'getQuestions'});
                    selectCourseId = 'questionCourseId';
                    selectGradeId = 'questionGradeId';
                    selectStyleId = 'questionStyleId';
                }
            }
        }
    }
});