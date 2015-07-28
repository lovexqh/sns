<?php //var_dump($data);exit;?>
<div id="wrapper">
    <div id="scroller">
        <div id="thelist">
            <div class="info">
                <div class="face" data-uid="<?php echo $data['author_id'];?>"><img src="<?php echo $data['face'];?>"></div>
                <div class="title"><h2><?php echo $data['title'];?></h2></div>
            </div>
            <div class="down">
                <span class="uname" data-uid="<?php echo $data['author_id'];?>">作者：<span class="light"><?php echo $data['author_name'];?></span></span>
                <span class="read">阅读：<span class="light"><?php echo $data['readCount'];?></span></span>
                <span class="comment">评论：<span class="light"><?php echo $data['commentCount'];?></span></span>
            </div>
            <div class="time">写于：<span class="light"><?php echo date('Y-m-d H:i',$data['cTime']);?></span></div>
            <div class="content"><?php echo replaceFace(htmlspecialchars_decode($data['content']));?></div>
            <ul id="comment-list">
            </ul>
        </div>
        <div id="pullUp" class="more">
            <span class="pullUpLabel loading-a"><?php if($data){echo '加载更多';}else{echo '暂无数据';}?></span>
        </div>
    </div>
</div>
<script type="text/javascript">
    var url = "<?php echo $url;?>";
    var destroy = "<?php echo $destroy;?>";
    var create = "<?php echo $create;?>";
    var loadingImg = "<?php echo API_PUBLIC_PATH;?>image/ajax-loader.gif";
    var limit = 10;
    var pageNo = 0;
    var refresh;
</script>
<script type="text/javascript">
    var myScroll,
        pullDownEl, pullDownOffset,
        pullUpEl, pullUpOffset,
        generatedCount = 0;

    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
    $(function(){
        $('.face').click(function(){
            window.location="objc://userArticles^"+$(this).data('uid');
        });
        $('.uname').click(function(){
            window.location="objc://userArticles^"+$(this).data('uid');
        });
    })
    function loaded() {
        myScroll = new iScroll('wrapper', {
            useTransition: true,
            topOffset: pullDownOffset
        });
        setTimeout(function () { document.getElementById('wrapper').style.left = '0'; }, 800);
    }

</script>
<script type="text/javascript" src="<?php echo API_PUBLIC_PATH;?>js/comment-list.js"></script>
<script type="text/javascript">
    setTimeout(loadMore,201);
</script>
