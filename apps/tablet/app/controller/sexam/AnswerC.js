/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-13
 * Time: 下午1:06
 * To change this template use File | Settings | File Templates.
 */
Ext.define('ExamTeacher.controller.sexam.AnswerC', {
    extend:'Ext.app.Controller',
    requires:[
        'ExamTeacher.view.sexam.AnswerV'
    ],
    config:{
        refs: {
            answerBackQuestionList:'#answerBackQuestionList',        //返回按钮
            lastQuestionBtn:'#lastQuestionBtn',                        //上一题
            nextQuestionBtn:'#nextQuestionBtn',                         //下一题
            answerList:'#answerListId'                                  //答题列表
        },
        control: {
            answerBackQuestionList: {                                   //返回事件
                tap: 'onAnswerBackQuestionListTap'
            },
            lastQuestionBtn:{                                           //上一题
                tap:'onLastQuestionBtnTap'
            },
            nextQuestionBtn:{                                           //下一题
                tap:'onNextQuestionBtnTap'
            },
            answerList:{                                                //点击答题列表
                itemtap:'onAnswerListTap'
            }
        },
        routes: {                   //MainV路由
            'AnswerBackQuestionList': 'AnswerBackQuestionList'    //参加考试
        }
    },
//事件
//返回
    onAnswerBackQuestionListTap:function(){
//        if(role!=2 ){
//            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
//        }
        this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: 'AnswerBackQuestionList'}));
    },

//提交：上一题、下一题
    onAnswerListTap:function(tapList, index, target, record, e, eOpts){
        var qid = record.get('id');     //题id
        var auto = record.get('auto');  //是否自动批改
        var aid = record.get('aid');    //判断改题还是提交题
        if(e.target.name == 'next'){
    //稍后
            tapList.setMasked({xtype: 'loadmask', message: '请稍候...' });
//            var answer = Ext.getCmp('answerListId').query('[name="nihao"]');
//            var answer = document.getElementsByName('nihao')[0].innerHTML;
            console.log('下一题');
    //答案
            var flag = record.get('flag');
            if(flag == 0|| flag==1 || flag== 2){
                var resultInput = document.getElementsByName('resultInput');
                console.log(resultInput);
                for(var i=0; i<resultInput.length;i++){
                    if(resultInput[i].checked)
                    {
                        var result = i;
                    }
                }
            }else{
                var result = document.getElementsByName('resultInput')[0].value;
            }
            console.log(result);
    //Ajax提交答题
            Ext.Ajax.request({
                url:rootUrl+'&act=studentAnswer',
                method:'POST',
                timeout:10000,       //超时时间
                params:{
                    uid:userID,
                    eid:paperID,
                    content:result,
                    qid:qid,
                    aid:aid,
                    auto:auto
                },
                success:function(response){
                    tapList.setMasked(false);
                    var respText = response.responseText;
                    console.log(respText);
                    if(respText){
                        Ext.Viewport.setMasked(false);
                        console.log('下一题');
            //跳转到下一题
                        if(pageNO == pageTotle){
                            Ext.Msg.alert('通知', '已是最后一题', Ext.emptyFn);
                        }else{
                            pageNO++;
                            ExamTeacher.app.setStoreProperty(Ext.getCmp('answerListId'), 'AnswerSId', rootUrl, {act:'correct', sid:userID, pid:paperID, pageNo:pageNO, limit:1, action:'play'});
                        }
                    } else {
                        Ext.Msg.alert('通知', '提交重新提交', Ext.emptyFn);
                    }
                },
                failure:function(result, response){
                    Ext.Viewport.setMasked(false);
                    Ext.Msg.alert('通知', '提交失败，请重新提交', Ext.emptyFn);
                }
            });
        }else if(e.target.name == 'previous'){
//            console.log('上一题');
            if(pageNO != 1){
                pageNO--;
                ExamTeacher.app.setStoreProperty(Ext.getCmp('answerListId'), 'AnswerSId', rootUrl, {act:'correct', sid:userID, pid:paperID, pageNo:pageNO, limit:1, action:'play'});
            }else{
                Ext.Msg.alert('通知', '已是第一题', Ext.emptyFn);
            }
        }
    },
//路由
//返回
    AnswerBackQuestionList:function(){
        if(role!=3 ){
            this.getApplication().getHistory().add(Ext.create('Ext.app.Action', {url: ''}));
        }else{
            Ext.Viewport.animateActiveItem('squestionlistv', {
                type:'slide',
                direction:'right',
                duration:200
            });
        }
    }
});