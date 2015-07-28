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
                    if($v['jiaru']){
                        $a = 1;
                    }else{
                        $a = 0;
                    }
                    ?>
                    <li class="club" data-id="<?php echo $v['id'];?>" data-join="<?php echo $a;?>">
                        <div class="logo">
                            <img src="<?php echo $v['logo'];?>">
                            <?php
                                if($v['jiaru']){
                                    ?>
                                    <div class="joined">已加入</div>
                                    <?php
                                }else{
                                    ?>
                                    <div class="unjoin">未加入</div>
                                    <?php
                                }
                            ?>
                        </div>
                        <div class="info">
                            <h3><?php echo $v['name'];?></h3>
                            <p>
                                人数：<span class="light"><?php echo $v['membercount']?></span>&nbsp;&nbsp;
                                发帖：<span class="light"><?php echo $v['threadcount'];?></span>
                            </p>
                            <p>类型：<span class="light"><?php echo $v['t1'].'—'.$v['t2']?></span></p>
                            <p>
                                创始人：<span class="light"><?php echo $v['uname'];?></span>&nbsp;&nbsp;
                                创建时间：<span class="light"><?php echo $v['ctime'];?></span>
                            </p>
                            <artilce>
                                <p>社团简介：</p>
                                <p class="intro">
                                    <span class="light"><?php echo $v['intro']?$v['intro']:'还没有简介哦~';?></span>
                                </p>
                            </artilce>
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
    var destroy = "<?php echo $destroy;?>";
    var create = "<?php echo $create;?>";
    var limit = 10;
    var pageNo = 1;
    var refresh;
</script>
<script type="text/javascript" src="<?php echo API_PUBLIC_PATH;?>js/common-list.js"></script>

<script type="text/javascript">
    Zepto(function($){

        $("#thelist").on('click','tap',function(){
            window.location="objc://club^"+$(this).data('id')+'^'+$(this).data('join');
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
                    if(data[i]['jiaru']){
                        var a = 1;
                    }else{
                        var a = 0;
                    }
                    html = '<li class="club" data-id='+data[i]['id']+' data-join='+a+'>';
                    html += '<div class="logo">';
                    html += '<img src='+data[i]['logo']+'>';
                    if(data[i]['jiaru']){
                        html += '<div class="joined">已加入</div>';
                    }else{
                        html += '<div class="unjoin">申请加入</div>';
                    }
                    html += '</div>';
                    html += '<div class="info">';
                    html += '<h3>'+data[i]['name']+'</h3>';
                    html += '<p>';
                    html += '人数：<span class="light">'+data[i]['membercount']+'</span>&nbsp;&nbsp;';
                    html += '发帖：<span class="light">'+data[i]['threadcount']+'</span>';
                    html += '</p>';
                    html += '<p>类型：<span class="light">'+data[i]['t1']+'—'+data[i]['t2']+'</span></p>'
                    html += '<p>';
                    html += '创始人：<span class="light">'+data[i]['uname']+'</span>&nbsp;&nbsp;';
                    html += '创建时间：<span class="light">'+data[i]['ctime']+'</span>';
                    html += '</p>';
                    html += '<artilce>'
                    html += '<p>社团简介：</p>';
                    html += '<p class="intro">';
                    html += '<span class="light">';
                    html += data[i]['intro']?data[i]['intro']:'还没有简介哦~';
                    html += '</span>';
                    html += '</p>';
                    html += '</artilce>';
                    html += '</div>'
                    html += '<div class="bottom"></div>'
                    html += '</li>'
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
