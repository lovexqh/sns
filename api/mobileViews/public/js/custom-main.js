/**
 * 列表仿手风琴效果 by 徐程亮
 * 班级空间下使用list.htm.php
 */
function listViewTap(){
    if($(this).next().is(":hidden")){
        $(".superman-list-h").hide();
        $(this).next().show();

        //清除样式
        $("#thelist > li:even").addClass('listView-title');
        $("#thelist li").removeClass('listView-show-title');
        $(this).removeClass('listView-title');
        $(this).next().removeClass('listView-content');

        //添加样式
        $(this).addClass("listView-show-title");
        $(this).next().addClass("listView-show-content");

        $(this).next().addClass('superman-list-h');
    }else{
        $(this).next().hide();

        $(this).next().removeClass("listView-show-title");
        $(this).next().removeClass("listView-show-content");

        $(this).addClass('listView-title');
        $(this).next().addClass('listView-content');

    }
}