/**
 * 处理页面滚动事件
 * by 徐程亮
 * 13-7-29 上午10:12
 */
var myScroll,
    pullDownEl, pullDownOffset,
    pullUpEl, pullUpOffset, a,
    generatedCount = 0;
var pageNo = 1;
var limit = 10;
var view = '';
//初始化方法
function iscroll(v){
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
    view = v;
}

//下拉执行事件
function pullDownAction () {
    setTimeout(function () {	// <-- Simulate network congestion, remove setTimeout from production!
        pageNo = -1;
        $("#thelist").empty()
        scroll.getData();
        setTimeout(function(){
            initLoad()
        },801);
    }, 2000);
}

//加载数据方法
var x = 0;
var scroll ={
    pic:function(data,lazy){
        var html = '';
        if(Object.keys(data).length > 4){
            for (var i in data){
                if(typeof(data[i]) == 'object'){
                    html += "<img class='pic lazy checkpic' src="+lazy+" data-url-p="+data[i]['picurl']+" data-url="+data['thumburl']+data[i]['thumburl']+">";
                }
            }
        }else{
            html += "<img class='pic lazy checkpic' src="+lazy+" data-url-p="+data['picurl']+" data-url="+data['thumburl']+">";
        }
        return html;
    },
    //班级空间list
    getData:function(){
        pageNo++;
        if(x == 1){
            myScroll.refresh();
            return false;
        }
        $.post(url,{"pc":1,"limit":limit,"pageNo":pageNo},function(data){
            if(data == 0){
                x = 1;
                return false;
            }
            var json = eval("("+data+")");
            var html = '';
            var l = json.length;
            for(var i=0 ; i < l; i++ ){
                if(view == 'weibo'){
                    var tid;
                    if(json[i]['transpond_id'] > 0){
                        tid = json[i]['transpond_id'];
                    }else{
                        tid = 0;
                    }
                    html = '<li class="weibo-list" data-id='+json[i]['weibo_id']+' data-tid='+tid+' data-uid='+json[i]['uid']+'>';
                    html += '<img class="img-r lazy user" data-url='+json[i]['face']+' src='+lazy_user+' data-uid='+json[i]['uid']+'>';
                    html += '<span class="time">'+json[i]['agoTime']+'</span>';
                    html += '<span class="name uname user" data-uid='+json[i]['uid']+'>'+json[i]['uname']+'</span>';
                    html += '<span class="uid yin">'+json[i]['uid']+'</span>';
                    html += '<p class="weibo-content weibo-detail">'+json[i]['content'];
                    //是否有转发
                    if(json[i]['transpond_id'] > 0){
                        html += '<section class="zhuanfa" data-id='+json[i]['transpond_data']['weibo_id']+'>';
                        html += '<img class="img-r lazy user" data-uid='+json[i]['transpond_data']['uid']+' data-url='+json[i]['transpond_data']['face']+' src='+lazy_user+'>';
                        html += '<span class="yanse uname user" data-uid='+json[i]['transpond_data']['uid']+'>'+json[i]['transpond_data']['uname']+'</span>';
                        html += '<p>'+json[i]['transpond_data']['content'];
                        //是否为图片类型
                        //图片为一张
                        if(json[i]['transpond_data']['type'] == 1){
                            html += '<span class="show-pic">';
                            html += scroll.pic(json[i]['transpond_data']['type_data'],lazy_img);
                            html += '</span>';
                        }else if(json[i]['transpond_data']['type'] == 3){
                            html += "<br><img src="+play_img+" data-url="+json[i]['transpond_data']['type_data']['flashvar']+" class='media'>";
                        }else if(json[i]['transpond_data']['type'] == 999){
                            html += "<br><img src="+play_img+" data-url="+json[i]['transpond_data']['type_data']['mp3']+" class='media'>";
                        }
                        html += '</p>';
                        html +='</section>';
                    }
                    //图片类型微博

                    if((json[i]['type'] == 1) && (json[i]['transpond_id'] == 0)){
                        html += '<br />';
                        html += '<span class="show-pic">';
                        html += scroll.pic(json[i]['type_data'],lazy_img);
                        html += '</span>';
                    }else if((json[i]['type'] == 3) && (json[i]['transpond_id'] == 0)){
                        html += "<br><img src="+play_img+" data-url="+json[i]['type_data']['flashvar']+" class='media'>";

                    }else if((json[i]['type'] == 999) && (json[i]['transpond_id'] == 0)){
                        html += "<br><img src="+play_img+" data-url="+json[i]['type_data']['mp3']+" class='media'>";
                    }
                    html +='</p>';
                    //底部
                    html +='<p class="down">';
                    html += '<span class="down-r">';
                    html += '<span class="comment">评论<span class="yanse">'+json[i]['comment']+'</span></span>';
                    html += '<span class="tran">转发<span class="yanse">'+json[i]['transpond']+'</span></span>';
                    html += '</span>';
                    html += '<span class="from">';
                    if(json[i]['from'] == 0){
                        html += '来自 网站';
                    }else if(json[i]['from'] == 2){
                        html += '来自 Android';
                    }else if(json[i]['from'] == 3){
                        html += '来自 iPhone';
                    }
                    html += '</span>';
                    html +='</p>';
                    html +='</li>';
                }else{
                    html = '<li class="listView-title">'+json[i]['title']+'</li><li class="listView-content">'+json[i]['content']+'</li>';
                }
                $("#thelist").append(html);
            }
            myScroll.refresh();
            if(view == 'weibo'){

            }else{

                $(".listView-title").unbind("tap");
                $(".listView-title").hammer({}).on("tap",listViewTap);
            }
        });
    }
}
//
function pullUpAction () {
    setTimeout(function () {	// <-- Simulate network congestion, remove setTimeout from production!
        scroll.getData();
    }, 2000);	// <-- Simulate network congestion, remove setTimeout from production!
}

function loaded() {
    pullDownEl = document.getElementById('pullDown');
    pullDownOffset = pullDownEl.offsetHeight;
    pullUpEl = document.getElementById('pullUp');
    pullUpOffset = pullUpEl.offsetHeight;

    myScroll = new iScroll('wrapper', {
        useTransition: true,
        topOffset: pullDownOffset,
        onRefresh: function () {
            if (pullDownEl.className.match('loading')) {
                pullDownEl.className = '';
            } else if (pullUpEl.className.match('loading')) {
                pullUpEl.className = '';
            }
        },
        onScrollMove: function () {
            if (this.y > 5 && !pullDownEl.className.match('flip')) {
                pullDownEl.className = 'flip';
                this.minScrollY = 0;
            } else if (this.y < 5 && pullDownEl.className.match('flip')) {
                pullDownEl.className = '';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新';
                this.minScrollY = -pullDownOffset;
            } else if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip')) {
                pullUpEl.className = 'flip';
                this.maxScrollY = this.maxScrollY;
            } else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
                pullUpEl.className = '';
                this.maxScrollY = pullUpOffset;
            }
        },
        onScrollEnd: function () {
            if (pullDownEl.className.match('flip')) {
                pullDownEl.className = 'loading';
                pullDownAction();	// Execute custom function (ajax call?)
            } else if (pullUpEl.className.match('flip')) {
                pullUpEl.className = 'loading';
                pullUpAction();	// Execute custom function (ajax call?)
            }
        }
    });

    setTimeout(function () { document.getElementById('wrapper').style.left = '0'; }, 800);
}
