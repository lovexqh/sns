<!--<div id="header"><a href="http://cubiq.org/iscroll">iScroll</a></div>-->
<?php //p($data);exit;?>
<div id="wrapper">
    <div id="scroller" class="weibo">
        <div id="pullDown">
            <span class="pullDownIcon"></span><span class="pullDownLabel"></span>
        </div>

        <ul id="thelist">
            <?php
            if(!$data){
                echo '<li>暂无微博</li>';
            }else{
                foreach ($data as $v) {
                    ?>
                    <li class="weibo-list" data-id="<?php echo $v['weibo_id'];?>" data-tid="<?php echo $v['transpond_id']?$v['transpond_id']:0;?>" data-uid="<?php echo $v['uid']?>">
                        <img  class="img-r lazy user" src="<?php echo $lazyUser;?>" data-url="<?php echo $v['face'];?>" data-uid="<?php echo $v['uid'];?>">
                        <span class="time"><?php echo $v['agoTime'];?></span>
                        <span class="name uname user" data-uid="<?php echo $v['uid'];?>"><?php echo $v['uname'];?></span>
                        <!--          内容区          -->
                        <p class="weibo-content weibo-detail">
                            <?php echo $v['content'];

                            //转发微博
                            if($v['transpond_id']){
                                ?>
                                <section class="zhuanfa" data-id="<?php echo $v['transpond_data']['weibo_id'];?>" data-uid="<?php echo $v['uid']?>">
                                    <img  class="img-r lazy user" src="<?php echo $lazyUser;?>" data-url="<?php echo $v['transpond_data']['face'];?>" data-uid="<?php  echo $v['transpond_data']['uid'];?>">
                                    <span class="yanse uname user" data-uid="<?php echo $v['transpond_data']['uid'];?>"><?php echo $v['transpond_data']['uname'];?></span>
                                    <p>
                                        <?php
                                        echo $v['transpond_data']['content'];
                                        if($v['transpond_data']['type'] == 1){
                                            echo '<br><span class="show-pic">';
                                            pic($v['transpond_data']['type_data'],$lazy);
                                            echo '</span>';
                                        }elseif($v['transpond_data']['type'] == 3){

                                            ?>
                                            <br>
                                            <img src="<?php echo $playImg;?>" data-url="<?php echo $v['transpond_data']['type_data']['flashvar'];?>" class="media">
                                        <?php
                                        }elseif($v['transpond_data']['type'] == 999){
                                            ?>
                                            <br>
                                            <img src="<?php echo $playImg;?>" data-url="<?php echo $v['transpond_data']['type_data']['mp3'];?>" class="media">
                                        <?php
                                        }
                                        ?>
                                    </p>
                                </section>
                            <?php
                            }
                            //如果是图片
                            if($v['type'] == 1 && !$v['transpond_id']){
                                ?>
                                <br>
                                <span class="show-pic">
                                <?php pic($v['type_data'],$lazy);?>
                            </span>
                                <?php
                                /**
                                 * 视频
                                 */
                            }elseif($v['type'] == 3 && !$v['transpond_id']){
                                ?>
                                <br>
                                <img src="<?php echo API_PUBLIC_PATH;?>/image/play.png" data-url="<?php echo $v['type_data']['flashvar'];?>" class="media">
                                <!--                            <video class="v" id="v" src="--><?php //echo $v['type_data'];?><!--"><a href="#">点击查看视频</a></video>-->
                                <?php
                                /**
                                 * 音频
                                 */
                            }elseif($v['type'] == 999 && !$v['transpond_id']){
                                ?>
                                <br>
                                <img src="<?php echo API_PUBLIC_PATH;?>/image/play.png" data-url="<?php echo $v['type_data']['mp3'];?>" class="media">
                            <?php
                            }
                            ?>
                        </p>
                        <!--底部-->
                        <p class="down">

                    <span class="down-r">
                            <span class="comment">
                                评论 <span class="yanse"><?php echo $v['comment'];?></span>
                            </span>

                            <span class="tran">
                                转发 <span class="yanse"><?php echo $v['transpond'];?></span>
                            </span>
                    </span>

                    <span class="from">
                        <?php
                        if($v['from']==0){
                            echo '来自 网站';
                        }elseif($v['from'] == 2){
                            echo '来自 Android';
                        }elseif($v['from'] == 3){
                            echo '来自 iPhone';
                        }
                        ?>
                    </span>

                        </p>
                    </li>
                <?php
                }
            }
            ?>
        </ul>
        <div id="pullUp">
            <span class="pullUpIcon"></span><span class="pullUpLabel"></span>
        </div>
    </div>
</div>
<script>
    var url = "<?php echo $url;?>";
    var lazy_user = "<?php echo $lazyUser;?>";
    var lazy_img = "<?php echo $lazy;?>";
    var play_img = "<?php echo $playImg;?>";
    var weibo,uid,zhuanfa;
</script>
<script type="text/javascript" src="<?php echo API_PUBLIC_PATH;?>js/iscroll.js"></script>
<!--<div id="footer"></div>-->
<script type="text/javascript">
    iscroll('weibo')
    $(function(){

        /*
         *   处理视频音频
         */
        $("#thelist").hammer({}).on("tap",'.media',function(){
            var url = $(this).data('url');
            window.location = "objc://media^"+url;
            return false;
        })

        //微博详情处理
        $("#thelist").hammer({}).on("tap",'.weibo-list,.zhuanfa',function(){
            var id = $(this).data('id');
            var uid = $(this).data('uid');
            window.location = "objc://weiboDetail^"+id+"^"+uid;
            return false;

        });
        //查看某人的微博
        $("#thelist").hammer({}).on("tap",'.user',function(){
            var uid = $(this).data('uid')
            window.location = "objc://uid^"+uid;
            return false;
        });

        //查看图片
        $("#thelist").hammer({}).on("tap",'.checkpic',function(){
            var url = $(this).data('url-p');
            window.location = "objc://img^"+url;
            return false;
        })

        $("#thelist").hammer({}).on("hold","li",function(){
            var id = $(this).data('id');
            var uid = $(this).data('uid');
            var tid = $(this).data('tid');
            window.location = "objc://weibo^"+id+"^"+uid+'^'+tid;
            return false;
        })

        //转发的处理
//        $(".zhuanfa").hammer({}).on("tap",function(){
//            var zhuan_id = $(this).data("id");
//            window.location = "objc://weiboDetail^"+zhuan_id;
//        })

        setTimeout(function(){
            initLoad()
        },801);

        $("#thelist").hammer({ drag_lock_to_axis: true }).on("dragup",delayLoad)
        $("#thelist").hammer({ drag_lock_to_axis: true }).on("dragdown",delayLoad)




    })

    //图片延时加载

    function initLoad(){
        $(".lazy").each(function(){
            var win_h = $(document).height();
            var top = Math.round($(this).offset().top);
            if( (top > 0) && (top < win_h)){
                var img = $(this).data('url');
                $(this).attr("src",img);
            }
        });
        myScroll.refresh();
    }

    function delayLoad(ev){
        ev.gesture.stopDetect();
        setTimeout(initLoad,1000);
    }
</script>
