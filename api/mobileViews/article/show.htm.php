<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!--对可视区进行矫正，通知浏览器使用移动设备的宽度作为可视宽度-->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?php echo API_PUBLIC_PATH;?>css/article-show.css"/>
</head>
<body>
<article id="ar">
    <h2><?php echo $article['title']?></h2>
    <p class="article-left">
        <?php echo date('y-m-d H:i',$article['cTime']),'&nbsp',$article['author_name'];?>
    </p>
    <div id="article-content">
        <?php echo h(htmlspecialchars_decode($article['content']));?>
    </div>
</article>
</body>
</html>
<script type="text/javascript">
    window.onload=function(){
        liang.click("a",function(){return false;})
        liang.removeCss();
        liang.auto();
    }

    var liang = {
        /**
         * 处理标签点击
         * @param tagName 标签名
         * @param fun  处理方法
         */
        click:function(tagName,fun){
            var obj = document.getElementsByTagName(tagName);
            var len = obj.length;
            for(var i=0; i<len; i++){
                obj[i].onclick=fun;
            }
        },
        /**
         *设置自动宽度不超过屏幕宽度
         */
        wid : document.body.clientWidth,
        auto:function(){
            var wid = document.body.clientWidth;
            var obj = document.getElementsByTagName("img");
            var len = obj.length;
            for(var i=0; i<len; i++){
                obj[i].style.maxWidth=wid+"px";
            }
        },
        /**
         * 删除指定标签内联css
         * @param tagName
         */
        removeCss:function(){
            //所有要删除样式的标签
            var tags = this.tags;
            var l = tags.length;
            for(var i=0; i<l; i++){
                var tag = document.getElementsByTagName(tags[i]);
                var len = tag.length;
                for(var j=0; j<len; j++){
                    tag[j].setAttribute("style","");

//                    if(tag[j].style.size){tag[j].style.size=""}
//                    if(tag[j].style.width){tag[j].style.width=""}
//                    if(tag[j].style.height){tag[j].style.height=""}
//                    if(tag[j].style.align){tag[j].align="center"}
                }
            }
        },
        /**
         *
         */
        tags:["div","span","p","font"]
    }
</script>
