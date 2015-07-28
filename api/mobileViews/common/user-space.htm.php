<div id="content" data-uid="<?php echo $data['uid']?>">
    <div id='s'></div>
    <div class="top-background"><img src="<?php echo API_PUBLIC_PATH.'image/user-space.gif'?>" alt=""/></div>
    <div class="user-info">
        <div class="left"><img src="<?php echo str_replace('middle','big',$data['face']);?>"></div>
        <div class="right">
            <div class="name"><?php echo $data['uname'];?></div>
            <?php if($data['is_followed']){?>
            <div class="follow_status">
                <span>
                    <?php
                        switch($data['is_followed']){
                            case 'eachfollow':
                                echo '相互关注';
                                break;
                            case 'havefollow':
                                echo '已关注';
                                break;
                            case 'unfollow':
                                echo '加关注';
                                break;
                        }
                    ?>
                </span>
            </div>
            <?php };?>
        </div>
    </div>
    <div class="count">
        <div class="weibo"><span class="num"><?php echo $data['weibo_count'];?></span><br>微博</div>
        <div class="follow"><span class="num"><?php echo $data['followed_count'];?></span><br>关注</div>
        <div class="fans"><span class="num"><?php echo $data['followers_count'];?></span><br>粉丝</div>
    </div>
</div>
<script type="text/javascript">
    Zepto(function($){
        var uid = $("#content").data('uid');
        $(".weibo").tap(function(){
            window.location="objc://weibo^"+uid;
        })
        $(".follow").tap(function(){
            window.location="objc://follow^"+uid;
        })
        $(".fans").tap(function(){
            window.location="objc://fans^"+uid;
        })
        $(".follow-status").on('tap',function(){
            var a = $(this);
            if($(this).html() != '加关注' ){
                if(confirm('确定要取消关注吗？')){
                    var uid = $("#content").data('uid');
                    $.getJSON(destroy+'&user_id='+uid,function(res){
                        //* @return string eachfollow:相互关注 | havefollow:已关注 | unfollow:未关注 | 空:同一个用户
                        if(res.is_followed == 'unfollow'){
                            a.html('加关注');
                        }
                    });
                }

            }else{
                var uid = $("#content").data('uid');
                $.getJSON(create+'&user_id='+uid,function(res){
                    //* @return string eachfollow:相互关注 | havefollow:已关注 | unfollow:未关注 | 空:同一个用户
                    switch(res.is_followed){
                        case 'unfollow':
                            a.html('加关注');
                            break;
                        case 'eachfollow':
                            a.html('相互关注');
                            break;
                        case 'havefollow':
                            a.html('已关注');
                            break;
                    }
                });

            }

        });

    });

    var scroll1, scroll2;
    function loaded() {
        scroll1 = new iScroll('s');
    }

    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    document.addEventListener('DOMContentLoaded', loaded, false);
</script>