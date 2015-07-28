<?php //var_dump($data);exit;?>
<div id="wrapper">
    <div id="scroller">
        <div id="list">
            <ul>
                <?php
                if($data){
                    //按老师名
                   if($type == 1){
                       foreach ($data as $v) {
                           echo '<li data-id='.$v['jsh1'].'>';
                           echo '<h3>';
                           echo $v['jsm1'];
                           echo '</h3>';
                           echo '</li>';
                       }
                   }else{
                       //按科目名
                       foreach ($data as $v) {
                           echo '<li data-id='.$v['kch'].'>';
                           echo '<h3>';
                           echo $v['kcm'];
                           echo '</h3>';
                           echo '</li>';
                       }
                   }
                }else{
                    echo '<li>';
                    echo '<h3>';
                    echo '暂无数据~';
                    echo '</h3>';
                    echo '</li>';
                }
                ?>
            </ul>
        </div>
        <div class="space"></div>
    </div>
</div>
<script type="text/javascript">
    var myScroll,
        pullDownEl, pullDownOffset,
        pullUpEl, pullUpOffset,
        generatedCount = 0;

    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);

    function loaded() {
        myScroll = new iScroll('wrapper', {
            useTransition: true,
            topOffset: pullDownOffset
        });
        setTimeout(function () { document.getElementById('wrapper').style.left = '0'; }, 800);
    }

</script>
<script type="text/javascript">
    $(function(){
        $("li").click(function(){
            if($(this).data('id')){
                window.location="objc://id^"+$(this).data('id');
            }
        });
    });
</script>
