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
                <li data-url="<?php echo $v['video_path'];?>" data-id="<?php echo $v['id'];?>">
                    <section>
                        <div class="left" data-uid="<?php echo $v['author_id'];?>" data-name="<?php echo $v['author_name']?>">
                            <img src="<?php echo $v['author_img'];?>" data-path="<?php echo $v['author_img'];?>" class="video-p" data-uid="<?php echo $v['author_id'];?>" data-name="<?php echo $v['author_name']?>">
                        </div>

                        <div class="right">
                            <h3><?php echo $v['author_name'],'：',$v['title'];?></h3>
                            <div class="content">简介：<?php echo $v['info']?$v['info']:'还没有简介哦~';?></div>
                            <div class="down"><span class="l"><?php echo $v['cTime']?></span><span class="r"><?php echo $v['readCount'];?>看过  <?php echo $v['commentCount'];?>评论</span></div>
                        </div>
                        <div class="go">
                            <img src="<?php echo API_PUBLIC_PATH;?>image/go.png" />
                        </div>

                    </section>
                </li >
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
    var readCountUrl = "<?php echo $readCountUrl;?>";
    var limit = 10;
    var pageNo = 1;
    var refresh;
</script>
<script type="text/javascript" src="<?php echo API_PUBLIC_PATH;?>js/common-list.js"></script>

<script type="text/javascript">
    $(function(){
        //加载更多
        $("#wrapper").hammer({}).on("tap",".more",loadMore);
        //用户
//        $("section").hammer({}).on("tap",".head,.left",function(){
//            window.location="objc://userArticles^"+$(this).data('uid');
//            return false;
//        });
        //详情
        $("#wrapper").hammer({}).on("tap","li",function(){
            var url = $(this).data('url');
            $.get(readCountUrl+'&id='+$(this).data('id'),function(){
                $('.loading-a').html('加载更多');
                window.location="objc://videoShow^"+url;
            });
        });
        //请求开始

    })

    function loadMore(){
        $(document).ajaxStart(function(){
            $('.loading-a').html('<img src="<?php echo API_PUBLIC_PATH;?>image/ajax-loader.gif">');
        });

        pageNo++;
        var html = '';
        if($(".pullUpLabel").html() == '没有了~' && refresh != 1){
            return false;
        }
        $.ajax({
            type:'POST',
            url:url,
            data:{pc:1,limit:limit,pageNo:pageNo},
            dataType:'json',
            success:function(data){
                if(!data[0]){
                    $(".pullUpLabel").html('没有了~');
                    myScroll.refresh();
                    return false;
                }
                for(var i in data){
                    var info = '';
                    if(data[i]['info']){
                        info = data[i]['info'];
                    }else{
                        info = '还没有简介哦~';
                    }
                    html = '<li data-url='+data[i]['video_path']+'>';
                    html += '<section>';
                    html += '<div class="left" data-uid='+data[i]['author_id']+'>';
                    html += '<img class="video-p" data-uid='+data[i]['author_id']+' src='+data[i]['author_img']+'>';
                    html += '</div>';
                    html += '<div class="right">';
                    html += '<h3>'+data[i]['author_name']+'：'+data[i]['title']+'</h3>';
                    html += '<div class="content">'+info+'</div>';
                    html += '<div class="down"><span class="l">'+data[i]['cTime']+'</span><span class="r">'+data[i]['readCount']+'看过  '+data[i]['commentCount']+'评论</span></div>';
                    html += '</div>';
                    html += '<div class="go">';
                    html += '<img src="<?php echo API_PUBLIC_PATH;?>image/go.png" />';
                    html += '</div>';
                    html += '</section>';
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
