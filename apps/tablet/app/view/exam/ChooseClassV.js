/**
 * Created with JetBrains WebStorm.
 * User: Administrator
 * Date: 13-6-18
 * Time: 上午10:27
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.exam.ChooseClassV', {
    extend:'Ext.form.Panel',
    xtype:'chooseclassv',
    requires:[
        'Ext.TitleBar',
        'Ext.dataview.List',
        'Ext.Button'
    ],
    config:{
        id:'CheckPanel',
        scrollable: {
            direction: 'vertical',
            directionLock: true
        },
        items:[
//顶部导航栏
            {
                xtype:'titlebar',
                docked:'top',
                title:'不限班级',
                items:[
                    {
                        xtype:'button',
                        id:'ChooseBackCreateBtn',
                        ui:'back',
                        text:'返回'
                    },
                    {
                        xtype:'button',
                        align:'right',
                        id:'saveChooseBtn',
                        text:'确定'
                    }
                ]
            }
        ],
        listeners:{
            painted:function(){
//                console.log('chooseClassV');
//                console.log(classArr[0].text);
//                for(var i = 0;i < classArr.length; i++){
//                    var check = Ext.create('Ext.field.Checkbox',{
//                        xtype: 'checkboxfield',
//                        name : 'tomato',
//                        label: classArr[i].text,
//                        value: classArr[i].value
//                    });
//                    this.add(check);
//                }
                for(var i = 1;i < classArr.length; i++){
                    var check = Ext.create('Ext.field.Checkbox',{
                        xtype: 'checkboxfield',
                        name : 'classes',
                        label: classArr[i].text,
                        value: '\''+ classArr[i].value+ '\':\''+ classArr[i].text+ '\''
                    });
                    console.log(classArr[i]);
                    this.add(check);
                }
//                for(var i = 1;i < 10; i++){
//                    var check = Ext.create('Ext.field.Checkbox',{
//                        xtype: 'checkboxfield',
//                        name : 'tomato',
//                        label: i,
//                        value: '\''+ i+ '\':\''+ i+ '\''
//                    });
//                    console.log(classArr[i]);
//                    this.add(check);
//                }
            }
        }
    }
});