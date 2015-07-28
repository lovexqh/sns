/**
 * Created by 志远 on 14-1-10.
 */
function changeCategory(obj){
    if($(obj).val() == 0){
        ui.box.load(U('blog/Index/createcategory'),{title:'创建分类'});
        $("#category option:first").prop("selected", 'selected');
    }
}
function addBlog(form){
    var title = $('#title').val();
    if(title == ''){
        alert('博客标题不能为空！');
        $('#title').focus();
        return false;
    }
    var content = getEditorContent('content');
    if(content == ''){
        alert('博客内容不能为空！');
        check_focus('iframe[title="kissy-editor"]');
        $("#content").focus();
        return false;
    }else{
        $("#content").val(content);
    }
    var category = $('#category').val();
    if(category == 0 || category == -1){
        alert('请选择博客专辑！');
        return false;
    }
    var private = $('#private').val();
    var square = $('#square').val();
    if(private == 0 && (square == '' || square == 0)){
        alert('当前访问模式下，一定要选择发表于哪！');
        return false;
    }

    $.post(
        $(form).attr('action'),
        $(form).serialize(),
        function(data){
            var data = eval('(' + data + ')');  // change the JSON string to javascript object
            if(data.status>0){
                document.location.href = data.url;
            }else{
                alert(data.msg);
            }
        }
    );
    return false;
}


function checkprivate (value){
    if(value != 0){
        $('.contentbox').hide();
        $('#fby').hide();
    }else{
        $('.contentbox').show();
        $('#fby').show();
    }
}


