<?php //var_dump($data);exit;?>
<div id="wrapper">
    <div id="scroller">
        <div id="pullDown">
            <span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新</span>
        </div>

        <div id="thelist">
            <div id="list">
                <?php
                if($data){
                    foreach ($data as $v) {
                        ?>
                        <section>
                            <div class="title">
                                <?php echo $v['title'];?>
                            </div>
                            <div class="content">
                                <?php echo $v['content'];?>
                            </div>
                            <div class="info">
                                <?php
                                if($type == 'exercise'){
                                ?>
                                <span><?php echo $v['uname'];?></span>
                                <span class="course">科目:<span class="h"><?php echo $v['coursename'];?></span></span>
                                        <span class="info-r">时间:<span class="h"><?php echo $v['stime'];?></span> 至 <span class="h"><?php echo $v['etime'];?></span><span>

                                <?php
                                }else{
                                    ?>
                                    <span>
                                    <?php echo $v['uname'];?>&nbsp;&nbsp;&nbsp;
                                </span>
                                    <span>
                                    <?php echo $v['ctime'];?>
                                </span>
                                <?php }?>
                            </div>
                        </section>
                    <?php
                    }
                }
                ?>
            </div>
        </div>
        <div id="pullUp" class="more">
            <span class="pullUpLabel loading-a"><?php if($data){echo '加载更多';}else{echo '暂无数据';}?></span>
        </div>
    </div>
</div>

<script type="text/javascript">
    var url = "<?php echo $url;?>";
    var limit = 10;
    var pageNo = 1;
    var refresh;
    var type = '<?php echo $type;?>';
</script>

<script type="text/javascript" src="<?php echo API_PUBLIC_PATH;?>js/common-list.js"></script>

<script type="text/javascript">
    $(function(){
//        加载更多
        $("#wrapper").hammer({}).on("tap",".more",loadMore);
//        请求开始
        $(document).ajaxStart(function(){
            $('.loading-a').html('<img src="<?php echo API_PUBLIC_PATH;?>image/ajax-loader.gif">');
        });
    })
    function loadMore(){

        pageNo++;
        var html = '';
        if($(".pullUpLabel").html() == '没有了~' && refresh!=1){
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
                    html = '<section>';
                    html += '<div class="title">'+data[i]['title']+'</div>';
                    html += '<div class="content">'+data[i]['content']+'</div>';
                    html += '<div class="info">';
                    if(type == 'exercise'){
                        html += '<span>'+data[i]['uname']+'</span>';
                        html += '<span class="course">科目：<span class="h">'+data[i]['coursename']+'</span></span>';
                        html += '<span class="info-r">时间：<span class="h">'+data[i]['stime']+'</span>-<span class="h">'+data[i]['etime']+'</span><span>';
                    }else{
                        html += '<span>'+data[i]['uname']+'</span>';
                        html += '<span>'+data[i]['ctime']+'</span>';
                    }
                    html += '</div>';
                    html += '</section>';
                    $("#list").append(html);
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
