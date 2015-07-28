/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-11
 * Time: 下午3:51
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.question.CreatePaperV', {
    extend:'Ext.Container',
    xtype:'createpaperv',
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
        id:'createPaperVID',
        scrollable: {
            direction: 'vertical',
            directionLock: true
        },
        items:[
//顶部titleBar
            {
                xtype:'titlebar',
                docked:'top',
                title:'创建试卷',
                items:[
                    {
                        xtype:'button',
                        id:'createBackMainBtn',
                        ui:'back',
                        text:'返回'
                    },
                    {
                        xtype:'button',
                        align:'right',
                        id:'createPaperBtn',
                        text:'创建并选择试题'
                    }
                ]
            },
//创建区域
            {
                xtype: 'fieldset',
                margin:'20 0 0 0',
                items: [
                    {
                        xtype: 'textfield',
                        label: '考试名称',
                        id: 'createNameId'
                    },
                    {
                        xtype:'textfield',
                        label:'试卷总分',
                        id:'createScore'
                    },
                    {
                        xtype: 'selectfield',
                        label: '年级',
                        id: 'createGradeId',
                        listeners:{
                            change:function( selectThis, newValue, oldValue, eOpts ){
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
                                //设置
                                            var courseSelect = Ext.getCmp('createCourseId');
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
                        label: '科目',
                        id: 'createCourseId',
                        options: [
                            {text: '选择试卷',  value: '0'},
                            {text: '语文', value: '1'},
                            {text: '数学',  value: '2'}
                        ]
                    }
                ]
            }
        ],
        listeners:{
            painted:function(){
        //设置年级数组
                var gradeSelect = Ext.getCmp('createGradeId');
                gradeSelect.setOptions(gradeArr);
            }
        }
    }
});