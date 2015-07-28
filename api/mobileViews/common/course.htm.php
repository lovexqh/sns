<?php //$_SERVER['name']='aaa';?>
<?php
if(!$data){
echo '<h2>还没有课程表哦~</h2>';
exit;
}
?>
<div id="header">
    <ul>
        <li>周一</li>
        <li>周二</li>
        <li>周三</li>
        <li>周四</li>
        <li>周五</li>
    </ul>
</div>
<div id="wrapper">
    <div id="scroller">

        <div id="course">
            <?php
            $time = array();
            $day = array();
            $i = 0;
            if($data['morn'])$time[] = '早自习';
            if($data['am'])$time[] = '上午';
            if($data['pm'])$time[] = '下午';
            if($data['night'])$time[] = '晚自习';
            foreach ($data as $v) {
                echo '<div class="row">';
                echo '<div class="left">'.$time[$i].'</div>';
                echo '<div class="content">';
                foreach ($v as $vv) {
                    echo '<ul>';
                    foreach ($vv as $vvv) {
                       echo '<li onclick="a(this)">'.$vvv[1].'</li>';
                    }
                    echo '</ul>';
                }
                echo '</div>';
                echo '</div>';
                $i++;
            }
            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var myScroll;
    function loaded() {
        myScroll = new iScroll('wrapper');
    }
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    document.addEventListener('DOMContentLoaded', loaded, false);
    function a(obj){
        alert(obj.innerHTML);
    }
</script>
