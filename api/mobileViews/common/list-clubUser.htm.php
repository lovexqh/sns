<?php //var_dump($data);exit;?>
<div id="wrapper">
    <div id="scroller">
        <div id="pullDown">
            <span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新</span>
        </div>

        <ul id="thelist">
            <?php
            if($data){

                foreach ($data as $v) {
                    ?>
                    <li class="user-list" data-uid="<?php echo $v['user']['uid'];?>">
                        <div class="left">
                            <img src="<?php echo $v['user']['face'];?>">
                        </div>
                        <div class="middle">
                            <div class="right">
                                <?php
                                switch($v['user']['is_followed']){
                                    case 'eachfollow':
                                        echo '<span data-follow="no" class="follow-status">相互关注</span>';
                                        break;
                                    case 'havefollow':
                                        echo '<span data-follow="no" class="follow-status">已关注</span>';
                                        break;
                                    case 'unfollow':
                                        echo '<span data-follow="add" class="follow-status">加关注</span>';
                                        break;
                                }
                                ?>
                            </div>
                            <div class="uname"><?php echo $v['user']['uname'];?></div>
                            <div class="info">
                            </div>

                        </div>
                    </li>
                <?php
                }
            }

            ?>
        </ul>
        <div id="pullUp" class="more">
            <span class="pullUpLabel loading-a"><?php if($data){echo '加载更多';}else{echo '暂无数据';}?></span>
        </div>
    </div>
</div>
<script type="text/javascript">
    var url = "<?php echo $url;?>";
    var destroy = "<?php echo $destroy;?>";
    var create = "<?php echo $create;?>";
    var limit = 10;
    var pageNo = 1;
    var refresh;
</script>
<script type="text/javascript" src="<?php echo API_PUBLIC_PATH;?>js/common-list.js"></script>

<script type="text/javascript">
    Zepto(function($){

        $("#thelist").on('click','.uname',function(e){
            window.location="objc://user^"+$("li").data('uid');
        });

        $("#thelist").on('click','.info-w',function(e){
            window.location="objc://weibo^"+$("li").data('uid');
        });

        $("#thelist").on('click','.info-follow',function(e){
            window.location="objc://follow^"+$("li").data('uid');
        });

        $("#thelist").on('click','.info-fans',function(e){
            window.location="objc://fans^"+$("li").data('uid');
        });

        $("#thelist").on('click','.follow-status',function(e){
            var a = $(this);
            if($(this).data('follow') == 'no' ){
                if(confirm('确定要取消关注吗？')){
                    var uid = $("li").data('uid');
                    $.getJSON(destroy+'&user_id='+uid,function(res){
                        //* @return string eachfollow:相互关注 | havefollow:已关注 | unfollow:未关注 | 空:同一个用户
                        if(res.is_followed == 'unfollow'){
                            a.data('follow','add')
                            a.html('加关注');
                        }
                    });
                }

            }else{
                var uid = $("li").data('uid');
                $.getJSON(create+'&user_id='+uid,function(res){
                    //* @return string eachfollow:相互关注 | havefollow:已关注 | unfollow:未关注 | 空:同一个用户
                    switch(res.is_followed){
                        case 'unfollow':
                            a.data('follow','add')
                            a.html('加关注');
                            break;
                        case 'eachfollow':
                            a.data('follow','no')
                            a.html('相互关注');
                            break;
                        case 'havefollow':
                            a.data('follow','no')
                            a.html('已关注');
                            break;
                    }
                });

            }
            return false;
        });

        $("#pullUp").click(function(){
            loadMore();
        });
    })

    function loadMore(){

        var html = '';
        if($(".pullUpLabel").html() == '没有了~' && refresh != 1){
            return false;
        }
        pageNo++;
        $.ajax({
            type:'POST',
            url:url,
            data:{pc:1,limit:limit,pageNo:pageNo},
            dataType:'json',
            beforeSend:function(){
                $('.loading-a').html('<img src="<?php echo API_PUBLIC_PATH;?>image/ajax-loader.gif">');
            },
            success:function(data){
                if(!data[0]){
                    $(".pullUpLabel").html('没有了~');
                    return false;
                }

                for(var i in data){
                    html = '<li class="user-list" data-uid='+data[i]['user']['uid']+'>';
                    html += '<div class="left">';
                    html += '<img src='+data[i]['user']['face']+'>';
                    html += '</div>';
                    html += '<div class="middle">';
                    html += '<div class="right">';
                    html += '<span class="follow-status">';
                    switch(data[i]['user']['is_followed']){
                        case 'eachfollow':
                            html +='相互关注';
                            break;
                        case 'havefollow':
                            html += '已关注';
                            break;
                        case 'unfollow':
                            html += '未关注';
                            break;
                    }
                    html += '</span>';
                    html += '</div>';
                    html += '<div class="uname">'+data[i]['user']['uname']+'</div>';
                    html += '<div class="info">';
                    html += '<span class="info-w">微博<span class="count">'+data[i]['user']['weibo_count']+'</span></span>';
                    html += '<span class="info-follow">关注<span class="count">'+data[i]['user']['followed_count']+'</span></span>';
                    html += '<span class="info-fans">粉丝<span class="count">'+data[i]['user']['followers_count']+'</span></span>';
                    html += '</div>';
                    html += '</div>';
                    html += '</li>';
                    $("#thelist").append(html);
                }
                //刷新DOM
                $('.pullUpLabel').html('加载更多');
                myScroll.refresh();
                return false;
            },
            error:function(){
                $(".pullUpLabel").html("加载失败，检查网络");
                return false;
            }
        })
    }
</script>
