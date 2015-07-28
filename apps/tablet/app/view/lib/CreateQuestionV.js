/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-6-6
 * Time: 下午5:16
 * To change this template use File | Settings | File Templates.
 */
//选择创建题型
var createType;
Ext.define('ExamTeacher.view.lib.CreateQuestionV', {
    extend:'Ext.Container',
    xtype:'createquestionv',
    requires:[
        'Ext.TitleBar',
        'Ext.form.FieldSet',
        'Ext.field.Select'
    ],
    config:{
//        layout:'vbox',
        id:'createQuestionId',
        scrollable: {
            direction: 'vertical',
            directionLock: true
        },
        items:[
//顶部导航栏
            {
                xtype:'titlebar',
                docked:'top',
                title:'创建试题',
                items:[
                    {
                        xtype:'button',
                        id:'createQuestionBackSMainVBtn',
                        text:'返回',
                        ui:'back'
                    },
                    {
                        xtype:'button',
                        id:'saveQuestionBtn',
                        align:'right',
                        text:'保存'
                    }
                ]
            },
//选择创建试题类型
            {
                xtype:'container',
                docked:'top',
                layout:'hbox',
//                style:'background:#abcdef',
                border:1,
                height:55,
                defaults:{
                    centered:'true'
                },
                items:[
                    {
                        xtype:'button',
                        id:'',
                        margin:'5 0 0 -500',
                        text:'判断题',
                        handler:function(){
                            createType = '1';
                            Ext.getCmp('createQuestionId').refreshField();
                        }
                    },
                    {
                        xtype:'button',
                        id:'',
                        margin:'5 0 0 -180',
                        text:'填空题',
                        handler:function(){
                            createType = '2';
                            Ext.getCmp('createQuestionId').refreshField();
                        }
                    },
                    {
                        xtype:'button',
                        id:'',
                        margin:'5 -180 0 0',
                        text:'选择题',
                        handler:function(){
                            createType = '3';
                            Ext.getCmp('createQuestionId').refreshField();
                        }
                    },
//                    {
//                        xtype:'button',
//                        id:'',
//                        margin:'5 -250 0 0',
//                        text:'计算题',
//                        handler:function(){
//                            createType = 'calculate';
//                            Ext.getCmp('createQuestionId').refreshField();
//                        }
//                    },
                    {
                        xtype:'button',
                        id:'',
                        margin:'5 -500 0 0',
                        text:'简答题',
                        handler:function(){
                            createType = '4';
                            Ext.getCmp('createQuestionId').refreshField();
                        }
                    }
                ]
            },
//试题区域
            {
                xtype:'fieldset',
                id:'fieldsetId',
                title: '请先选择题型',
                instructions: '创建试题',
                items:[
                    {
                        xtype: 'textareafield',
                        id : 'createCon',
                        maxRows: 3,
                        label: '题干'
                    },
//                    {
//                        xtype: 'textfield',
//                        id : 'createA',
//                        label: 'A:'
//                    },
//                    {
//                        xtype: 'textfield',
//                        id : 'createB',
//                        label: 'B:'
//                    },
//                    {
//                        xtype: 'textfield',
//                        id : 'createC',
//                        label: 'C:'
//                    },
//                    {
//                        xtype: 'textfield',
//                        id : 'createD',
//                        label: 'D:'
//                    },
                    {
                        xtype: 'textareafield',
                        id : 'options',
                        maxRows: 3,
                        label: '选项'
                    },
                    {
                        xtype: 'textareafield',
                        id : 'createAnswer',
                        maxRows: 3,
                        label: '答案'
                    },
//                    {
//                        xtype: 'textfield',
//                        id : 'schoolId',
//                        label: '学校'
//                    },
                    {
                        xtype: 'selectfield',
                        id : 'createGrade',
                        label: '年级',
                        listeners:{
                            change:function( selectThis, newValue, oldValue, eOpts ){
//                                gradeId = newValue;
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
//                                            courseArr.unshift({text:"选择科目", value:0});
                                            console.log(courseArr);
                                            var courseSelect = Ext.getCmp('courseId');
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
                    {
                        xtype: 'selectfield',
                        id : 'courseId',
                        label: '科目'
                    }
                ]
            }
        ],
        listeners:{
            painted:function(){
                //设置年级数组
                var gradeSelect = Ext.getCmp('createGrade');
                var thisGradeArr = gradeArr;
                thisGradeArr.splice(0, 1);
                gradeSelect.setOptions(thisGradeArr);
            }
        }
    },
    refreshField:function(){
//        alert(createType);
        var createCon = Ext.getCmp('createCon');                //题干
//        var createA = Ext.getCmp('createA');                    //A
//        var createB = Ext.getCmp('createB');                    //B
//        var createC = Ext.getCmp('createC');                    //C
//        var createD = Ext.getCmp('createD');                    //D
        var options = Ext.getCmp('options');                    //选项
        var createAnswer = Ext.getCmp('createAnswer');         //答案
//        var schoolId = Ext.getCmp('schoolId');                  //学校id
        var createGrade = Ext.getCmp('createGrade');           //年级
//全部清空
        createCon.setValue('');
//        createA.setValue('');
//        createB.setValue('');
//        createC.setValue('');
//        createD.setValue('');
        options.setValue('');
        createAnswer.setValue('');
//        schoolId.setValue('');
//设置标志
        if(createType == '3'){         //选择题
//            createA.setHidden(false);
//            createB.setHidden(false);
//            createC.setHidden(false);
//            createD.setHidden(false);
            options.setHidden(false);
            Ext.getCmp('fieldsetId').setTitle("选择题");
            Ext.getCmp('fieldsetId').setInstructions("选项之间以^隔开");
        } else {
//            createA.setHidden(true);
//            createB.setHidden(true);
//            createC.setHidden(true);
//            createD.setHidden(true);
            options.setHidden(true);
            if(createType == '2'){
                Ext.getCmp('fieldsetId').setInstructions("??代表空格");
                Ext.getCmp('fieldsetId').setTitle("填空题");
            } else if(createType == '1'){
                Ext.getCmp('fieldsetId').setInstructions("0代表错误，1代表正确 ； 答案之间用@隔开");
                Ext.getCmp('fieldsetId').setTitle("判断题");
            } else {
                Ext.getCmp('fieldsetId').setInstructions("创建试题");
                if(createType == '3'){
                    Ext.getCmp('fieldsetId').setTitle("选择题");
//                }else if(createType == 'calculate') {
//                    Ext.getCmp('fieldsetId').setTitle("计算题");
                }else if(createType == '4'){
                    Ext.getCmp('fieldsetId').setTitle("简答题");
                }
            }
        }
    }
});