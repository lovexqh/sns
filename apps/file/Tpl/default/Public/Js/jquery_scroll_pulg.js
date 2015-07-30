// JavaScript Document
$(function(){
    //交友信息
    ScrollStart('.scroll_wrap',6,'arrow',4000)
    //热门转换
    ScrollStart('.goodswrap',1,'point',2000)
//---------图片滚动方法-----------------------------//
       function ScrollStart(obj,n,scss,speed){
        var liNum=$(obj).find('li').length;//li个数
        var dis=-$(obj).find('li').eq(0).outerWidth()*n;//移动距离
        var cont_dis=0
        var page=Math.ceil(liNum/n);//分页数量
        var lies=liNum%n;
        var appli='<li></li>';
        var timer=null;
        var pagecur=0;
        if(lies!=0){
        for(lies;lies<n;lies++){
        $(obj).find('ul li:last-child').after(appli);   
        }}
        var ulCode=$(obj).find('ul').html();
        $(obj).find('ul').html(ulCode+ulCode);//复制ul代码
        var ulWidth=Math.abs(dis*page*2);//ul的宽度
        $(obj).find('ul').css('width',ulWidth);//设置Ul宽度
        var tarUl=$(obj).find('ul');
        timer=setInterval(MoveStart,speed)
        $(obj).hover(function(){clearInterval(timer);},function(){timer=setInterval(MoveStart,speed);})
        //-----判断滚动样式(arrow：左右箭头样式，point：圆点控制滚动)----------------------//
        switch(scss){
            case"arrow":
                $(obj).siblings('span.arr_left').click(function(){
                    dis=-$(obj).find('li').eq(0).outerWidth()*n;
                    moveFX();
                    cont_dis+=dis;
                    tarUl.stop().animate({left:+cont_dis},500);
                })
                $(obj).siblings('span.arr_right').click(function(){
                    dis=$(obj).find('li').eq(0).outerWidth()*n;
                    moveFX();
                    cont_dis+=dis;
                    tarUl.stop().animate({left:+cont_dis},500);
                })
            return;
            case"point":
                var emCode='<em></em>';
                for(var i=0;i<page;i++){
                    $(obj).siblings('.points').append(emCode);//加em标签;
                    if(i==0){
                    $(obj).siblings('.points').find('em:eq(0)').addClass('active_em') ;//默认第一个点为active_em状态;
                    }
                }
                var points_w=$(obj).siblings('.points').outerWidth();
                var ems_w=$(obj).siblings('.points').find('em:eq(0)').outerWidth()*page;
                var paddings=Math.floor((points_w-ems_w)/2);
                $(obj).siblings('.points').css('padding-left',paddings);//em标签居中;
                //point点击判断;
                $(obj).siblings('.points').find('em').mousedown(function(){
                    clearInterval(timer);
                    $(this).addClass('active_em').siblings().removeClass();
                    var dis_click
                    dis_click=$(this).index()*dis;
                    $(obj).find('ul').animate({left:dis_click})
                })
                $(obj).siblings('.points').find('em').mouseup(function(){timer=setInterval(MoveStart,speed);})
        }
        //------------------滚动坐标分析------------------------------------//
        function moveFX(){
            if(dis<0){
                if(cont_dis==dis*page){
                    tarUl.css('left','0');
                    cont_dis=0;
                }
            }
            if(dis>0){
                if(cont_dis==0){
                    tarUl.css('left',-dis*page)
                    cont_dis=-dis*page;
                }
            }
        }
        function MoveStart(){
            moveFX();
            cont_dis+=dis;
            tarUl.animate({left:cont_dis},500);
            pagecur=Math.abs(cont_dis/dis);
            if(pagecur==3){
            $(obj).siblings('.points').find("em").eq(0).addClass('active_em').siblings().removeClass();
            }else{
            $(obj).siblings('.points').find("em").eq(pagecur).addClass('active_em').siblings().removeClass();   
            }
        }
    }

})