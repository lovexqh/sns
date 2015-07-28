<?php //p($data);exit;?>
<?php
if(!$data){
    echo '<h2>还没有座位表哦~</h2>';
    exit;
}
?>
<div id="header">
    <div id="r">
    <?php
        for($i=1; $i<=$count['colCount']; $i++){
            echo '<div class="auto">'.$i.'</div>';
        }
    ?>
    </div>
</div>
<div id="wrapper">
    <div id="scroller">
        <div id="seat">

            <div id="right">

                <?php
                $row = array();
                foreach ($data as $k=>$v) {
                    $row[] = $k;
                }
                $rows = $count['rowCount'];
                $cols = $count['colCount'];
                for($i=1; $i<=$rows; $i++){
                    if(in_array($i,$row)){
                        echo '<div class="row row-have">';
                        $col = array();
                        foreach($data[$i] as $k=>$v){
                            $col[] = $k;
                        }
                        for($j=1; $j<=$cols; $j++){
                            if(in_array($j,$col)){
                                if($data[$i][$j]){
                                    echo '<div class="col col-have auto">'.$data[$i][$j].'</div>';
                                }else{
                                    echo '<div class="col col-empty auto"></div>';
                                }
                            }else{
                                echo '<div class="col col-empty auto"></div>';
                            }
                        }
                        echo '</div>';
                    }else{
                        echo '<div class="row row-empty"></div>';
                    }
                }
                ?>
            </div>
            <div id="left">
                <?php
                for($i=1; $i<=$count['rowCount']; $i++){
                    echo '<div>'.$i.'</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var myScroll;
    function loaded() {
        myScroll = new iScroll('wrapper');
    }
    var cols,s_w,c_w;
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    document.addEventListener('DOMContentLoaded', loaded, false);
    $(function(){
        winAuto()
        $(window).resize(function(){
            winAuto()
        });
        $(".col").click(function(){
            if($(this).html()){
                alert($(this).html())
            }else{
                alert('这个座位还没有人哦~')
            }
        });
    })
    function winAuto(){
        cols = <?php echo $count['colCount'];?>;
        s_w = $("#right").width();
        c_w = Math.floor(s_w / cols);
        if(cols > 7){
            $(".auto").width(c_w-cols/2);
        }else{
            $(".auto").width(c_w-cols);
        }
    }
</script>
