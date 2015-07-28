Zepto(function($){

    $("#comment-list").on('tap','li',function(e){
            window.location="objc://comment^"+$(this).data('uid')+'^'+$(this).data('cid');
        return false;
    });
    $("#comment-list").on('tap','.c-face,.c-uname',function(e){
        if(e.currentTarget.className == 'c-uname' || e.currentTarget.className == 'c-face'){
            window.location="objc://user^"+$(this).data('uid');
        }
        return false;
    });

    $("#pullUp").click(function(){
        loadMore();
    });
})

function loadMore(){
    var html = '';
    if($(".pullUpLabel").html() == '没有评论~' && refresh != 1){
        return false;
    }
    pageNo++;
    $.ajax({
        type:'POST',
        url:url,
        data:{pc:1,limit:limit,pageNo:pageNo},
        dataType:'json',
        beforeSend:function(){
            $('.loading-a').html('<img src='+loadingImg+'>');
        },
        success:function(data){
            if(!data[0]){
                $(".pullUpLabel").html('没有评论~');
                return false;
            }

            for(var i in data){
                html = '<li class="comment" data-uid='+data[i]['uid']+' data-cid='+data[i]['comment_id']+'>';
                html += '<span class="c-face" data-uid='+data[i]['uid']+'><img src='+data[i]['face']+'></span>';
                html += '<div class="c-right">';
                html += '<div class="c-uname" data-uid='+data[i]['uid']+'>'+data[i]['uname']+'</div>';
                html += '<div class="c-content">'+data[i]['content']+'</div>';
                html += '<div class="c-time">'+data[i]['ctime']+'</div>'
                html += '</div>';
                html += '</li>';
                $("#comment-list").append(html);
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
