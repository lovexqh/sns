/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-6-7
 * Time: 下午5:15
 * To change this template use File | Settings | File Templates.
 */

Ext.define('ExamTeacher.view.exam.CreateExamV', {
    extend:'Ext.form.Panel',
    xtype:'createexamv',
    requires:[
        'Ext.TitleBar',
        'Ext.Toolbar',
        'Ext.Button',
        'Ext.field.Text',
        'Ext.form.FieldSet',
        'Ext.field.Select',
        'Ext.ux.field.DateTimePicker'
    ],
    config:{
        scrollable: {
            direction: 'vertical',
            directionLock: true
        },
        items:[
//顶部导航栏
            {
                xtype:'titlebar',
                docked:'top',
                title:'创建考试',
                items:[
                    {
                        xtype:'button',
                        id:'createExamBackMainBtn',
                        ui:'back',
                        text:'返回'
                    },
                    {
                        xtype:'button',
                        align:'right',
                        id:'saveCreateBtn',
                        text:'创建并选择班级'
                    }
                ]
            },
//内容
            {
                xtype: 'fieldset',
                margin:'20 0 0 0',
                items: [
                    {
                        xtype: 'textfield',
                        label: '考试名',
                        id:'CreateexamNameId'
                    },
                    {
                        xtype: 'selectfield',
                        label: '试卷',
                        id: 'CreatepaperNameId',
                        placeHolder:'选择试卷'
                    },
                    {
                        xtype: 'selectfield',
                        label: '年级',
                        id: 'CreategradeNameId',
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
                                            var courseSelect = Ext.getCmp('CreatecourseNameId');
                                            courseSelect.setOptions([]);
                                            courseSelect.setOptions(courseArr);
                                        } else {

                                        }
                                    },
                                    failure:function(result, response){
                                    }
                                });
                        //获取班级信息
                                Ext.Ajax.request({
                                    url:phaseUrl+'&act=getClasses',
                                    method:'POST',
                                    timeout:10000,       //超时时间
                                    params:{
                                        jbid:34
                                    },
                                    success:function(response){
                                        var respText = Ext.decode(response.responseText);
                                        console.log(respText);
                                        if(respText){
                                            for(var i=0; i<respText.length; i++){
                                                classArr[i] = {text: respText[i].bj, value: respText[i].id};
                                            }
                                            classArr.unshift({text:"不限科目", value:0});
                                            console.log('班级：'+ classArr);
//                                            var courseSelect = Ext.getCmp('CreatecourseNameId');
//                                            courseSelect.setOptions([]);
//                                            courseSelect.setOptions(classArr);
                                        } else {

                                        }
                                    },
                                    failure:function(result, response){
                                    }
                                });
                            }
                        }
//                        options: [
//                            {text: '一年级',  value: '1'},
//                            {text: '二年级', value: '2'},
//                            {text: '三年级',  value: '3'},
//                            {text: '四年级',  value: '4'},
//                            {text: '五年级', value: '5'},
//                            {text: '初一',  value: '6'},
//                            {text: '初二',  value: '7'},
//                            {text: '初三', value: '8'},
//                            {text: '初四',  value: '9'},
//                            {text: '高一',  value: '10'},
//                            {text: '高二', value: '11'},
//                            {text: '高三',  value: '12'}
//                        ]
                    },
                    {
                        xtype: 'selectfield',
                        label: '科目',
                        id: 'CreatecourseNameId',
                        placeHolder:'不限科目'
//                        options: [
//                            {text: '数学',  value: '1'},
//                            {text: '语文', value: '2'},
//                            {text: '英语',  value: '3'}
//                        ]
                    },
//                    {
//                        xtype: 'selectfield',
//                        label: '班级',
//                        id: 'CreateclassNameId',
//                        options: [
//                            {text: '1班',  value: '1'},
//                            {text: '2班', value: '2'},
//                            {text: '3班',  value: '3'}
//                        ]
//                    },
                    {
                        xtype: 'datetimepickerfield',
                        id : 'CreatestartTimeId',
                        label: '开始时间',
                        value: new Date(),
                        dateTimeFormat : 'Y-m-d H:i:s',
                        picker: {
                            doneButton: true,
                            cancelButton: true,
                            yearFrom: 2010,
                            minuteInterval : 1,
                            ampm : false,
                            slotOrder: ['month', 'day', 'year','hour','minute']
                        }
                    },
                    {
                        xtype: 'textfield',
                        label: '时长(分钟)',
                        id: 'CreatedurationId'
                    }
                ]
            }
////选择班级
//            {
//                xtype:'button',
//                text:'选择班级',
//                id:'chooseClassBtn'
//            }
        ],
        listeners:{
            painted:function(){
            //获取试卷
                Ext.Ajax.request({
                    url:rootUrl+'&act=getPapers',
                    method:'POST',
                    timeout:10000,       //超时时间
                    params:{
                        schoolid:schoolId
                    },
                    success:function(response){
                        var respText = Ext.decode(response.responseText);
                        console.log(respText);
                        if(respText){
                            var paperArr = [];
                            for(var i=0; i<respText.length; i++){
                                paperArr[i] = {text: respText[i].name, value: respText[i].id};
                            }
                            paperArr.unshift({text:"选择试卷", value:0});
                            console.log(paperArr);
                            var paperSelect = Ext.getCmp('CreatepaperNameId');
                            paperSelect.setOptions([]);
                            paperSelect.setOptions(paperArr);
                        } else {

                        }
                    },
                    failure:function(result, response){
                    }
                });
            //设置年级数组
                var gradeSelect = Ext.getCmp('CreategradeNameId');
                gradeSelect.setOptions(gradeArr);
            }
        }
    }
});