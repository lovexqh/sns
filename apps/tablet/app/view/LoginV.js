/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-9
 * Time: 下午1:28
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.view.LoginV', {
    extend:'Ext.Container',
    xtype:'loginv',
    requires:[
        'Ext.field.Text',
        'Ext.field.Password',
        'Ext.Button',
        'Ext.Img',
        'Ext.Label'
    ],
    config:{
        style:'background:#00a8ec',
        defaults:{
            centered:true
        },
        items:[
            {
                xtype:'label',
                margin:'-190 0 0 0',
                style:'color:#ffffff;font:bold 25pt Arial;text-shadow:2px 2px 5px #000;',
                html:'用户登录'
            },
//用户名textfield
            {
                xtype:'textfield',
                margin:'-80 0 0 0',
                padding:'0 0 0 35',
                style:'background:rgba(255, 255, 255, 0.8)',
                id:'userName',
                width:350,
                value:'meimeili@gridinfo.com.cn',
//                value:'changqizhu@gridinfo.com.cn',
                placeHolder:'用户名'
            },
//用户名图标
            {
                xtype:'img',
                width:20,
                height:22,
                margin:'-80px 0 0 -310px',
                src:'resources/images/login/userName.png'
            },
//密码passwordfield
            {
                xtype:'passwordfield',
                margin:'40 0 0 0',
                padding:'0 0 0 35',
                style:'background:rgba(255, 255, 255, 0.8)',
                id:'password',
                width:350,
                value:'111111',
                placeHolder:'密码'
            },
//用户名图标
            {
                xtype:'img',
                width:20,
                height:22,
                margin:'40 0 0 -310',
                src:'resources/images/login/password.png'
            },
//登陆button
            {
                xtype:'button',
                id:'loginBtn',
                cls:'loginBtn',
                pressedCls:'loginPressBtn',
                margin:'140 0 0 0',
                width:350,
                text:'登陆'
            },
//底部背景树
            {
                centered:false,
                style:'position:absolute;bottom:0px;right:0px;background-position:none !important;',
                html:'<img src="resources/images/loading/tree.png" style="position:absolute;bottom:0px;right:0px;width:400px;height:250px"/>'
            }
        ]
    }
});