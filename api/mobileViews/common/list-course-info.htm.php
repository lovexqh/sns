<?php //var_dump($data);exit;?>
<div id="wrapper">
    <div id="scroller">
        <div id="pullDown">
            <span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新</span>
        </div>

        <ul id="thelist">
            <?php
            if($data['data']){

                foreach ($data['data'] as $v) {
                    ?>
                    <li>
                        <div class="left">
                            <h3>

                            <?php
                                echo $v['kcm'];
                            ?>
                            </h3>
                        </div>
                        <div class="middle">
                            <div class="info">
                                <img src="<?php echo API_PUBLIC_PATH.'image/people.png'?>"><span>老师：</span><div><?php echo $v['jsm1'];?></div>
                            </div>
                            <div class="info jieci">
                                <img src="<?php echo API_PUBLIC_PATH.'image/book.png'?>"><span>节次：</span><div><?php echo $v['skjc'];?></div>
                            </div>
                            <div class="info week">
                                <img src="<?php echo API_PUBLIC_PATH.'image/week.png'?>"><span>周数：</span><div><?php echo $v['weeknum'].'(每周'.$v['skxq'].')';?></div>
                            </div>
                            <div class="info address">
                                <img src="<?php echo API_PUBLIC_PATH.'image/address.png'?>"><span>地点：</span><div><?php echo $v['jxlm'].$v['jsm'];?></div>
                            </div>
                        </div>
                        <div class="right">
                            <div><?php echo $v['xf'];?>学分<br/><?php echo $v['kcsx'];?></div>
                        </div>
                    </li>
                <?php
                }
            }

            ?>
        </ul>
        <div id="pullUp" class="more" data-have="<?php echo $data?1:0?>">
            <span class="pullUpLabel loading-a"><?php if($data){echo '加载更多';}else{echo '暂无数据';}?></span>
        </div>
    </div>
</div>
<script type="text/javascript">
    var url = "<?php echo $url;?>";
    var limit = 10;
    var pageNo = 1;
    var refresh;
    var imagePath = "<?php echo API_PUBLIC_PATH;?>";
</script>
<script type="text/javascript" src="<?php echo API_PUBLIC_PATH;?>js/common-list.js"></script>
<script type="text/javascript">
    Zepto(function($){
        $("#pullUp").click(function(){
            loadMore();
        });
    })

    function loadMore(){

        var html = '';
        if($("#pullUp").data('have')==0){
            return false;
        }
        pageNo++;
        $.ajax({
            type:'POST',
            url:url+'&l=1',
            data:{pc:1,limit:limit,page:pageNo},
            dataType:'json',
            beforeSend:function(){
                $('.loading-a').html('<img src="<?php echo API_PUBLIC_PATH;?>image/ajax-loader.gif">');
            },
            success:function(data){
                if(!data['data']){
                    $(".pullUpLabel").data('have',0);
                    $(".pullUpLabel").html('没有了~');
                    return false;
                }
                var data = data['data'];
                for(var i in data){
                    html = '<li>';
                    html += '<div class="left">';
                    html += '<h3>'+data[i]['kcm']+'</h3>';
                    html += '</div>';
                    html += '<div class="middle">';
                    html += '<div class="info">';
                    html += '<img src='+imagePath+'image/people.png><span>老师：</span><div>'+data[i]['jsm1']+'</div>';
                    html += '</div>';
                    html += '<div class="info jieci">';
                    html += '<img src='+imagePath+'image/book.png><span>节次：</span><div>'+data[i]['skjc']+'</div>';
                    html += '</div>';
                    html += '<div class="info week">';
                    html += '<img src='+imagePath+'image/week.png><span>周数：</span><div>'+data[i]['weeknum']+'(每周'+data[i]['skxq']+')</div>';
                    html += '</div>';
                    html += '<div class="info address">';
                    html += '<img src='+imagePath+'image/address.png><span>地点：</span><div>'+data[i]['jxlm']+data[i]['jsm']+'</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="right">';
                    html += '<div>'+data[i]['xf']+'学分<br/>'+data[i]['kcsx']+'</div>';
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
