/**
 * Created with JetBrains WebStorm.
 * User: try
 * Date: 13-5-13
 * Time: 下午12:45
 * To change this template use File | Settings | File Templates.
 */

//画图类
var draw;

Ext.define('ExamTeacher.view.sexam.AnswerV', {
    extend:'Ext.Container',
    xtype:'answerv',
    requires:[
        'Ext.TitleBar',
        'Ext.Button',
        'Ext.dataview.List',
        'ExamTeacher.store.sexam.AnswerS'
    ],
    config:{
        layout:'vbox',
//        scrollable:true,
        items:[
//顶部titlebar
            {
                xtype:'titlebar',
                title:'答题',
                docked:'top',
                items:[
                    {
                        xtype:'button',
                        id:'answerBackQuestionList',
                        ui:'back',
                        text:'返回'
                    }
//                    {
//                        xtype:'button',
//                        align:'right',
//                        text:'橡皮擦',
//                        handler:function(){
//                            console.log('\n点击橡皮擦！！！！！');
//                            if(this.getUi() == 'normal'){
//                                draw.context.strokeStyle = 'rgba(255,255,255,1)';
//                                draw.context.lineWidth = 10;
//                                this.setUi('confirm');
//                            } else {
//                                draw.context.strokeStyle = 'rgba(0,0,0,1)';
//                                draw.context.lineWidth = 1;
//                                this.setUi('normal');
//                            }
//                        }
//                    },
//                    {
//                        xtype:'button',
//                        id:'lastQuestionBtn',
//                        align:'right',
//                        text:'上一题'
//                    },
//                    {
//                        xtype:'button',
//                        id:'nextQuestionBtn',
//                        align:'right',
//                        text:'下一题'
//                    }
                ]
            },
//list试题
            {
                xtype:'list',
                id:'answerListId',
                flex:1,
                style:'border-bottom:0!important',
                scrollable:null,
                cls:'listCls',
                pressedCls: '',
                disableSelection:'false',
                itemTpl:[
                    '<div style="padding:10px; width:100%;">{index}. {content}</div>',
                    '<tpl if="stylename == \'选择题\'">',
                        '<div style="padding:10px; width:100%;"><br>',
                            '<tpl if="studentAnswer==\'a\'">',
                                '<input type="radio" name="resultInput" value ="a" checked="checked" > A：{options1} <br>',
                            '<tpl else>',
                                '<input type="radio" name="resultInput" value ="a" > A：{options1} <br>',
                            '</tpl>',
                            '<tpl if="studentAnswer==\'b\'">',
                                '<input type="radio" name="resultInput" value ="b" checked="checked" > B：{options2} <br>',
                            '<tpl else>',
                                '<input type="radio" name="resultInput" value ="b" > B：{options2} <br>',
                            '</tpl>',
                            '<tpl if="studentAnswer==\'c\'">',
                                '<input type="radio" name="resultInput" value ="c" checked="checked" > C：{options3} <br>',
                            '<tpl else>',
                                '<input type="radio" name="resultInput" value ="c" > C：{options3} <br>',
                            '</tpl>',
                            '<tpl if="studentAnswer==\'d\'">',
                                '<input type="radio" name="resultInput" value ="d" checked="checked" > D：{options4} <br>',
                            '<tpl else>',
                                '<input type="radio" name="resultInput" value ="d" > D：{options4} <br>',
                            '</tpl>',
//                            '<input type="radio" name="resultInput" value ="b" "<tpl if==\"studentAnswer==b\">checked=\"checked\"</tpl>"> B ',
//                            '<input type="radio" name="resultInput" value ="c" "<tpl if==\"studentAnswer==c\">checked=\"checked\"</tpl>"> C ',
//                            '<input type="radio" name="resultInput" value ="d" "<tpl if==\"studentAnswer==d\">checked=\"checked\"</tpl>"> D ',
                        '</div>',
                    '</tpl>',
                    '<tpl if="stylename == \'判断题\'">',
                        '<div style="padding:10px; width:100%;">',
                            '<tpl if="studentAnswer==0">',
                                '<input type="radio" name="resultInput" value ="0" checked="checked" > 错<br>',
                            '<tpl else>',
                                '<input type="radio" name="resultInput" value ="0" > 错<br>',
                            '</tpl>',

                            '<tpl if="studentAnswer==1">',
                                '<input type="radio" name="resultInput" value ="1" checked="checked" > 对<br>',
                            '<tpl else>',
                                '<input type="radio" name="resultInput" value ="1" > 对<br>',
                            '</tpl>',
//                            '<input type="radio" name="resultInput" value ="0"> 错 ',
//                            '<input type="radio" name="resultInput" value ="1"> 对 ',
                        '</div>',
                    '</tpl>',
                    '<tpl if="stylename == \'填空题\'">',
                        '<div style="font-size: 12px">答案之间用@隔开</div><br>',
                        '<tpl if="!studentAnswer">',
                            '<textarea name="resultInput" rows="5" style="width: 100%"></textarea> ',
                        '<tpl else>',
                            '<textarea name="resultInput" rows="5" style="width: 100%">{studentAnswer}</textarea> ',
                        '</tpl>',
//                        '<textarea name="resultInput" rows="5" style="width: 100%"></textarea> ',
                    '</tpl>',
                    '<tpl if="stylename == \'简答题\'">',
                        '<tpl if="!studentAnswer">',
                            '<textarea name="resultInput" rows="5" style="width: 100%"></textarea> ',
                        '<tpl else>',
                            '<textarea name="resultInput" rows="5" style="width: 100%">{studentAnswer}</textarea> ',
                        '</tpl>',
//                        '<textarea name="resultInput" rows="5" style="width: 100%"></textarea> ',
                    '</tpl>',
                    '<div style="padding:10px; width:100%;"><button name="next" style="float:right">提交</button><button name="previous" style="float:right">上一题</button></div>'
                ],
//                data:[
//                    {
//                        name:'题干',
//                        coursename:'选项',
//                        flag:3
//                    }
//                ],
                store:{
                    type:'AnswerS'
                },
                listeners:{
                    painted:function(){
                        ExamTeacher.app.setStoreProperty(this, 'AnswerSId', rootUrl, {act:'correct', sid:userID, pid:paperID, pageNo:pageNO, limit:1, action:'play'});
                    }
                }
            }
////canvas
//            {
//                html:'<canvas style="float:left;background:#ffffff;"></canvas>',
//                listeners:{
//                    painted:function(){
////显示/隐藏“上一题”按钮
//                        var lastQuestionBtn = Ext.getCmp('lastQuestionBtn');
//                        if(nowAnswerVNum == 0){
//                            lastQuestionBtn.hide();
//                        } else {
//                            lastQuestionBtn.show();
//                        }
////画板高度、宽度
//                        var elDom = document.getElementsByTagName('canvas')[nowAnswerVNum];
//                        if(elDom.height != 300){
//                            elDom.width = window.innerWidth;
//                            elDom.height = 300;
//                        }
////绘图
//                        draw = this.drawFn;
//                        draw.load('canvas');
//                    }
//                },
////绘图方法
//                drawFn:{
//                    load:function(arg){
//                        if (arg.nodeType) {
//                            this.canvas = arg;
//                        } else if (typeof arg == 'string') {
//                            this.canvas = document.getElementsByTagName(arg)[nowAnswerVNum];
//                        } else {
//                            return;
//                        }
//                        this.init();
//                    },
//                    init: function() {
//                        var that = this;
//                        if (!this.canvas.getContext) {
//                            return;
//                        }
//                        this.context = this.canvas.getContext('2d');
//                        this.canvas.onselectstart = function () {
//                            return false;  //修复chrome下光标样式的问题
//                        };
//                        this.canvas.onmousedown = function(event) {
//                            that.drawBegin(event);
//                        };
//                    },
//                    drawBegin: function(e) {                //鼠标按下调用
//                        var that = this,
//                            stage_info = this.canvas.getBoundingClientRect();
//                        window.getSelection ? window.getSelection().removeAllRanges() :
//                            document.selection.empty();  //清除文本的选中
//                        this.context.beginPath();         //重要
//                        this.context.moveTo(
//                            e.clientX - stage_info.left,
//                            e.clientY - stage_info.top
//                        );
//                        document.onmousemove = function(event) {
//                            that.drawing(event);
//                        };
//                        document.onmouseup = this.drawEnd;
//                    },
//                    drawing: function(e) {               //鼠标移动调用
//                        var stage_info = this.canvas.getBoundingClientRect();
//                        this.context.lineTo(
//                            e.clientX - stage_info.left,
//                            e.clientY - stage_info.top
//                        );
////                                this.context.closePath();
//                        this.context.stroke();
//                    },
//                    drawEnd: function() {
//                        document.onmousemove = document.onmouseup = null;
//                    }
//                }
//            }
        ]
    }
});