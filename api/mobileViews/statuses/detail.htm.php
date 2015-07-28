<?php
//p($data);
?>
<section>
    <header>
        <div class="up">
            <!--头像-->
            <img class="face" src="<?php echo $data['face']?>">
            <!--名字-->
            <span class="name"><?php echo $data['uname'];?></span>
        </div>
        <div class="down">
        </div>
    </header>
    <div id="weibo">
        <div class="content">
            <p>
            <?php echo replaceFace($data['content'])?>
            </p>
        <?php
        //如果是转发
        if($data['transpond_id'] > 0){
            echo '<div class="zhuanfa">';
                echo '<img class="face" src='.$data['transpond_data']['face'].'>';
                echo '<span class="name" >'.$data['transpond_data']['uname'].'</span>';
                echo '<div class="content">';
                echo $data['transpond_data']['content'];
                echo '</div>';
                if($data['transpond_data']['type'] ==1 ){
                    echo '<div class="pic">';
                        pic($data['transpond_data']['type_data']);
                    echo '</div>';
                }elseif($data['transpond_data']['type'] == 3){

                    ?>
                    <br>
                    <img src="<?php echo $playImg;?>" data-url="<?php echo $data['transpond_data']['type_data']['flashvar'];?>" class="media">
                <?php
                }elseif($data['transpond_data']['type'] == 999){
                    ?>
                    <br>
                    <img src="<?php echo $playImg;?>" data-url="<?php echo $data['transpond_data']['type_data']['mp3'];?>" class="media">
                <?php
                }
            echo '</div>';
        }
        ?>
        <?php
        if($data['type']==1 && !$data['transpond_id']){
            ?>
            <div class="pic ppic">
                <?php
                pic($data['type_data'],$lazyImg);
                ?>
            </div>
        <?php
        }elseif($data['type'] == 3 && !$data['transpond_id']){

            ?>
            <br>
            <img src="<?php echo $playImg;?>" data-url="<?php echo $data['type_data']['flashvar'];?>" class="media">
        <?php
        }elseif($data['type'] == 999 && !$data['transpond_id']){
            ?>
            <br>
            <img src="<?php echo $playImg;?>" data-url="<?php echo $data['type_data']['mp3'];?>" class="media">
        <?php
        }
        ?>
    </div>
    </div>
    <div id="action">
        评论<span><?php echo $data['comment'] ?></span>  转发<span><?php echo $data['transpond']?></span>
    </div>
    <hr />
    <div id="comment-list">
        <ul>

        </ul>
    </div>
    <div id="pullUp" class="more s-font pullUpLabel loading-a"><div>
</section>
<script type="text/javascript">
    var url = '<?php echo $comments_url;?>';
    var pageNo = 0;
    var limit = 10;
    var loading = $(".more").html();
    var loadingImg = "<?php echo API_PUBLIC_PATH;?>image/ajax-loader.gif";
    var myScroll = {};
    myScroll.refresh = function(){
        return false;
    }
</script>
<script type="text/javascript" src="<?php echo API_PUBLIC_PATH;?>js/comment-list.js"></script>
<script type="text/javascript">
    loadMore();
    $(function(){
        $(".checkpic").click(function(){
            window.location = "objc://img^"+$(this).data('url-p');
        });
        $(".media").click(function(){
            window.location = "objc://media^"+$(this).data('url');
        })
    })
</script>
