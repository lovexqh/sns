

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

    wid : document.body.clientWidth,
    /**
     *设置自动宽度不超过屏幕宽度
     */
    auto:function(tagName){
        var obj = document.getElementsByTagName(tagName);
        var len = obj.length;
        for(var i=0; i<len; i++){
            obj[i].style.maxWidth=this.wid+"px";
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
                tag[j].style="";
                if(tag[j].size){tag[j].size=""}
                if(tag[j].color){tag[j].color=""}
                if(tag[j].width){tag[j].maxWidth=this.wid+"px"}
                if(tag[j].height){tag[j].height=""}
                if(tag[j].align){tag[j].align="center"}
            }
        }
    },
    /**
     *
     */
    tags:["div","span","p","td","tr","th","table","font","a","img"]
}
