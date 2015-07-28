/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-9
 * Time: 下午1:38
 * To change this template use File | Settings | File Templates.
 */

var gradeArr = [];       //年级数组
var styleArr = [];       //题型数组
Ext.define('ExamTeacher.controller.LoginC', {
    extend:'Ext.app.Controller',
    requires:[
        'Ext.data.JsonP',
        'ExamTeacher.view.PreviewV',
//学生
        'ExamTeacher.view.SMainV'
    ],
    config:{
        refs: {
            loginv:'loginv',            //预览页
            loginBtn:'#loginBtn',       //登录按钮
            userName:'#userName',
            password:'#password'
        },
        control: {
            loginBtn: {                 //登录事件
                tap: 'onLoginBtnTap'
            }
        },
        routes: {
            'previewv': 'showPreviewv',     //previewV路由
            'SMainV':'showSMainV'           //学生SMainV路由
        }
    },
//点击登录按钮
    onLoginBtnTap:function(){
    //稍后
        Ext.Viewport.setMasked({xtype: 'loadmask', message: '请久候...' });
        console.log(Ext.getClassName(this.getLoginv()));
        var loginV = this.getLoginv();
        var userName = this.getUserName().getValue();       //用户名
        var password = this.getPassword().getValue();       //密码
        console.log(userName+password);
        var nowClass = this;
    //Ajax提交
        Ext.Ajax.request({
            url:'../../../DSK-SNS/index.php?app=api&mod=Oauth&act=authorize&a=1',
            method:'POST',
            timeout:10000,       //超时时间
            params:{
                isIphone:1,
                uid:userName,
                passwd:password
            },
            success:function(response){
//                Ext.Viewport.setMasked(false);
                console.log('登陆成功：');
                var respText = Ext.decode(response.responseText);
                console.log(respText);
                if(respText.oauth_token_secret){
        //获取老师id
                    userID = respText.uid;
                    role = respText.role;
                    schoolId = respText.schoolid;
                    classId = respText.classid;
        //生成基础url
                    rootUrl = '../../../DSK-SNS/index.php?app=api&mod=Examine&oauth_token='+respText.oauth_token+'&oauth_token_secret='+respText.oauth_token_secret+'&uid='+userID;
                    phaseUrl = '../../../DSK-SNS/index.php?app=api&mod=Public&oauth_token='+respText.oauth_token+'&oauth_token_secret='+respText.oauth_token_secret;
        //获取学段
                    Ext.Ajax.request({
                        url:phaseUrl+'&act=getPhase',
                        method:'POST',
                        timeout:10000,       //超时时间
                        params:{
                            schoolid:schoolId
                        },
                        success:function(response){
                            var respText1 = Ext.decode(response.responseText);
                            console.log(response.responseText);
                            phaseId = respText1[0].jd;
                            console.log(phaseId);
                            if(respText1){
            //获取年级信息
                                Ext.Ajax.request({
                                    url:phaseUrl+'&act=getGrade',
                                    method:'POST',
                                    timeout:10000,       //超时时间
                                    params:{
                                        sid:schoolId,
                                        xd:phaseId
                                    },
                                    success:function(response){
                                        var respText = Ext.decode(response.responseText);
                                        if(respText){
//                                            var courseOptionsArr = [];
                                            for(var i=0; i<respText.length; i++){
                                                gradeArr[i] = {text: respText[i].njmc, value: respText[i].id};
                                            }
                                            gradeArr.unshift({text:"不限年级", value:0});
                                            console.log(gradeArr);
                                        } else {

                                        }
                                    },
                                    failure:function(result, response){
                                    }
                                });
            //获取题型信息
                                Ext.Ajax.request({
                                    url:rootUrl+'&act=questionStyle',
                                    method:'POST',
                                    timeout:10000,       //超时时间
                                    params:{
                                    },
                                    success:function(response){
                                        var respText = Ext.decode(response.responseText);
                                        console.log(respText);
                                        if(respText){
                                            for(var i=0; i<respText.length; i++){
                                                styleArr[i] = {text: respText[i].stylename, value: respText[i].id};
                                            }
                                            styleArr.unshift({text:"不限题型", value:0});
                                            console.log('题型'+ styleArr);
                                            //跳转
                                            Ext.Viewport.setMasked(false);
                                            if(role==3){
                                                //跳转到学生主页
                                                nowClass.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'SMainV'}));
                                            }else if(role==2){
                                                //跳转到老师主页
                                                nowClass.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'previewv'}));
                                            }
                                        } else {
                                            Ext.Viewport.setMasked(false);
                                            Ext.Msg.alert('警告', '登陆失败！请重现登陆', Ext.emptyFn);
                                        }
                                    },
                                    failure:function(result, response){
                                    }
                                });
                            } else {

                            }
                        },
                        failure:function(result, response){
                        }
                    });

                } else {
                    Ext.Msg.alert('oh, my god', '用户名或密码错误！！！！', Ext.emptyFn);
                }
            },
            failure:function(result, response){
                Ext.Viewport.setMasked(false);
                Ext.Msg.alert('oh, my god', '登陆失败！请重现登陆', Ext.emptyFn);
                var responseText = response.responseText;
                console.log('登陆失败'+responseText+' === '+result);
            }
        });
    },
//路由
//跳转到老师主页
    showPreviewv:function(){
        if(role!=2 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            this.getLoginv().destroy();
            Ext.Viewport.animateActiveItem('previewv', {
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    },
//跳转到学生SMainV
    showSMainV:function(){
        if(role!=3 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            this.getLoginv().destroy();
            Ext.Viewport.animateActiveItem('smainv', {
                type:'slide',
                direction:'left',
                duration:200
            });
        }
    }
});
