<?php //var_dump($data);exit;?>
<script type="text/javascript" src="<?php echo API_PUBLIC_PATH;?>js/common-show.js"></script>
<div id="wrapper">
    <div id="scroller">
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
                                    if($v['applyed'] == 0 && $v['applyed'] != null){
                                        ?>
                                        <div class="joined verifying">审核中</div>
                                    <?php
                                    }elseif($v['applyed'] == 1){
                                        ?>
                                        <div class="joined pass">已通过</div>
                                    <?php
                                    }elseif($v['applyed'] == 2){
                                        ?>
                                        <div class="past nopass">已拒绝</div>
                                    <?php
                                    }else{
                                        ?>
                                        <div class="unjoin" data-id="<?php echo $v['id'];?>">未报名</div>
                                    <?php
                                    }
                                }else{
                                    ?>
                                    <div class="joined no">无限制</div>
                                <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="info">
                            <h3 data-id="<?php echo $v['id'];?>"><?php echo $v['title'];?></h3>
                            <p>
                                类型：<span class="light"><?php echo $v['name']?></span>
                            </p>
                            <p>
                                开始时间：<span class="light">
                                    <?php
                                    if($v['meettime']){
                                        echo $v['meettime'].'('.$v['after'].')';
                                    }else{
                                        echo '-';
                                    }
                                    ?>
                                </span>
                            </p>
                            <p>
                                结束时间：<span class="light"><?php echo $v['deadline']?$v['deadline']:' -';?></span>
                            </p>
                            <p>招聘人数：<span class="light"><?php echo $v['num']?$v['num']:'不定';?></span></p>
                            <p>举行地点：<span class="light"><?php echo $v['meetplace']?$v['meetplace']:'暂无';?></span></p>
                            <p>联系方式：<span class="light"><?php echo $v['contact'].'  '.$v['tel'];?></span></p>
                            <p>发布部门：<span class="light"><?php echo $v['publishdept'];?></span></p>
                            <p>详细说明：</p>
                            <div class="intro light">
                                <?php echo $v['content']?$v['content']:'还没有简介哦~';?>
                            </div>
                        </div>
                        <div class="bottom"></div>
                    </li>
                <?php
                }
            }
            ?>
        </ul>
    </div>
</div>
<script type="text/javascript">
    var applyUrl = "<?php echo $applyUrl;?>";
</script>
<script type="text/javascript">
    Zepto(function($){
        $(".verifying").click(function(){
            alert('已报名，等待发布人审核中……');
        });
        $(".nopass").click(function(){
            alert('不好意思你的报名申请被拒绝了。');
        });
        $(".pass").click(function(){
            alert('恭喜你的报名申请已通过！');
        });
        $(".no").click(function(){
            alert('无需报名，即可参加。');
        });
        //报名
        $("#thelist").on('click','.unjoin',function(e){
            var obj = $(this);
            $.ajax({
                type : 'GET',
                url : applyUrl,
                success : function(res){
                    if(res == 1){
                        obj.html('审核中');
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
            return false;
        });
    })
</script>
