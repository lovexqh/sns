/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-31
 * Time: 上午9:16
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.exam.EditExamV', {
    extend:'Ext.form.Panel',
    xtype:'editexamv',
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
        id:'EditExamVId',
        scrollable: {
            direction: 'vertical',
            directionLock: true
        },
        items:[
//顶部导航栏
            {
                xtype:'titlebar',
                docked:'top',
                title:'编辑考试',
                items:[
                    {
                        xtype:'button',
                        id:'editExamBackMainBtn',
                        ui:'back',
                        text:'返回'
                    },
                    {
                        xtype:'button',
                        align:'right',
                        id:'saveEditBtn',
                        text:'修改'
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
                        name: 'examName',
                        id:'examNameId'
                    },
                    {
                        xtype: 'textfield',
                        label: '试卷名',
                        id: 'paperNameId'
                    },
                    {
                        xtype: 'selectfield',
                        label: '年级',
                        id: 'editGradeNameId'
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
                        id: 'courseNameId',
                        options: [
                            {text: '数学',  value: '1'},
                            {text: '语文', value: '2'},
                            {text: '英语',  value: '3'}
                        ]
                    },
                    {
                        xtype: 'selectfield',
                        label: '班级',
                        id: 'classNameId',
                        options: [
                            {text: '1班',  value: '1'},
                            {text: '2班', value: '2'},
                            {text: '3班',  value: '3'}
                        ]
                    },
//                    {
//                        xtype: 'textfield',
//                        label: '类型',
//                        id: 'lastName'
//                    },
//                    {
//                        xtype: 'datetimepickerfield',
//                        id : 'startTimeId',
//                        label: '开始时间',
////                        value: new Date(),
//                        dateTimeFormat : 'Y-m-d H:i:s',
//                        picker: {
//                            doneButton: true,
//                            cancelButton: true,
//                            yearFrom: 2010,
//                            minuteInterval : 1,
//                            ampm : false,
//                            slotOrder: ['month', 'day', 'year','hour','minute']
//                        }
//                    },
                    {
                        xtype:'textfield',
                        id : 'startTimeId',
                        label:'开始时间'
                    },
                    {
                        xtype: 'textfield',
                        label: '时长(分钟)',
                        id: 'durationId'
                    }
                ]
            }
        ],
        listeners:{
            painted:function(){
//                console.log(examRecord);
//设置考试名
                var examName = Ext.getCmp('examNameId');
                examName.setValue(examRecord.get('name'));
//设置试卷名
                var paperName = Ext.getCmp('paperNameId');
                paperName.setValue(examRecord.get('papername'));
                console.log(examRecord.get('papername'));
//设置年级数组
                var gradeSelect = Ext.getCmp('editGradeNameId');
                gradeSelect.setOptions(gradeArr);
                gradeSelect.setValue(examRecord.get('gradename'));
                console.log(examRecord.get('gradename'));
//设置科目名
                var courseName = Ext.getCmp('courseNameId');
                courseName.setValue(examRecord.get('coursename'));
//设置开始时间
                var startTime = Ext.getCmp('startTimeId');
//                startTime.setValue({
//                    y: '11',
//                    m  : '12',
//                    d : '13'
//                });
                startTime.setValue(examRecord.get('starttime'));
//设置时长
                var duration = Ext.getCmp('durationId');
                duration.setValue(examRecord.get('duration'));
            }
        }
    }
});