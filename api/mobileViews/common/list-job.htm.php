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
                    <li class="club">
                        <div class="logo">
                            <?php
                            if($v['past'] == 1){
                                ?>
                                <div class="past">已过期</div>
                            <?php
                            }else{
                                if($v['apply'] == 1){
                                    if($v['applyed']){
                                        ?>
                                        <div class="joined">已报名</div>
                                    <?php
                                    }else{
                                        ?>
                                        <div class="unjoin" data-id="<?php echo $v['id'];?>">未报名</div>
                                    <?php
                                    }
                                }else{
                                    ?>
                                    <div class="joined">无限制</div>
                                <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="info" data-id="<?php echo $v['id'];?>">
                            <h3><?php echo $v['title'];?></h3>
                            <p>
                                类型：<span class="light"><?php echo $v['name']?></span>
                            </p>
                            <p>
                                <span class="light">
                                    <?php
                                    if($v['meettime'] && $v['deadline']){
                                        echo '时间：'.$v['meettime'].' 至 '.$v['deadline'];
                                    }elseif($v['meettime']){
                                        echo '开始时间：'.$v['meettime'];
                                    }elseif($v['deadline']){
                                        echo '结束时间：'.$v['deadline'];
                                    }else{
                                        echo '时间：-';
                                    }
                                    ?>
                                </span>
                            </p>
                            <p>地点：<span class="light"><?php echo $v['meetplace'];?></span></p>
                        </div>
                        <div class="bottom"></div>
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
    var applyUrl = "<?php echo $applyUrl;?>";
    var limit = 10;
    var pageNo = 1;
    var refresh;
</script>
<script type="text/javascript" src="<?php echo API_PUBLIC_PATH;?>js/common-list.js"></script>

<script type="text/javascript">
    Zepto(function($){

        $("#thelist").on('tap','.unjoin',function(e){
            if(e.currentTarget.className == 'unjoin'){
                var id = $(this).data('id');
                var obj = $(this);
                $.ajax({
                    type : 'GET',
                    url : applyUrl+'&id='+id,
                    success : function(res){
                        if(res == 1){
                            obj.html('已报名');
                            obj.removeClass('unjoin');
                            obj.addClass('joined');
                            alert('报名成功，等候审核');
                        }else{
                            alert('报名失败，检查网络');
                        }
                        return false;
                    },
                    error : function(){
                        alert('报名失败，检查网络');
                        return false;
                    }
                });
            }
            return false;
        });

        $("#thelist").on('tap','.info',function(e){
            window.location="objc://detail^"+$(this).data('id');
        })

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
                    myScroll.refresh();
                    return false;
                }


                for(var i in data){

                    html = '<li class="club">';
                    html += '<div class="logo">';
                    if(data[i]['past'] == 1){
                        html += '<div class="past">已过期</div>';
                    }else{
                        if(data[i]['apply'] == 1){
                            if(data[i]['applyed']){
                                html += '<div class="joined">已报名</div>';
                            }else{
                                html += '<div class="unjoin" data-id='+data[i]['id']+'>未报名</div>';
                            }
                        }else{
                            html += '<div class="joined">无限制</div>';
                        }
                    }
                    html += '</div>';
                    html += '<div class="info" data-id='+data[i]['id']+'>';
                    html += '<h3 data-id='+data[i]['id']+'>'+data[i]['title']+'</h3>';
                    html += '<p>';
                    html += '类型：<span class="light">'+data[i]['name']+'</span>';
                    html += '</p>';
                    html += '<p>';
                    html += '<span class="light">';
                    if(data[i]['meettime'] && data[i]['deadline']){
                        html += '时间：'+data[i]['meettime']+' 至 '+data[i]['deadline'];
                    }else if(data[i]['meettime']){
                        html += '开始时间：'+data[i]['meettime'];
                    }else if(data[i]['deadline']){
                        html += '结束时间：'+data[i]['deadline'];
                    }else{
                        html += '时间：-';
                    }
                    html += '</span>';
                    html += '</p>';
                    html += '<p>地点：<span class="light">'+data[i]['meetplace']+'</span></p>';
                    html += '</div>';
                    html += '<div class="bottom"></div>';
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
