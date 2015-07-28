var appsConfig = {};
appsConfig.modList = {
    poster : 'poster', //招贴
    club : 'club', //社团
    society : 'society'//社交圈
}
appsConfig.map = {
    societyDetail:{//社交圈帖子详情
        tpl:'society-detail',
        dataSource:{mod:appsConfig.modList.society,act:'tdetail'}
    },
    societyCommentsList:{//社交圈帖子评论
        dataSource:{mod:appsConfig.modList.society,act:'replylist'}
    },
    societyComment:{//社交圈帖子评论
        dataSource:{mod:appsConfig.modList.society,act:'reply'}
    },
    posterDetail:{//招贴详情
        tpl:'poster-detail',
        dataSource:{mod:appsConfig.modList.poster,act:'getDetail'}
    },
    posterCommentsList:{//招贴评论列表
        dataSource:{mod:appsConfig.modList.poster,act:'getComments'}
    },
    posterCommentDelete:{//招贴评论列表
        dataSource:{mod:appsConfig.modList.poster,act:'delcomment'}
    },
    posterComment:{//发表招贴评论
        dataSource:{mod:appsConfig.modList.poster,act:'comment'}
    },
    postDetail:{//帖子详情
        tpl:'post-detail',
        dataSource:{mod:appsConfig.modList.club,act:'tdetail'}
    },
    replyList:{//帖子回复
        dataSource:{mod:appsConfig.modList.club,act:'replylist'}
    },
    postReply:{//发表评论
        dataSource:{mod:appsConfig.modList.club,act:'reply'}
    },
    mienDetail:{//风采详情
        tpl:'mien-detail',
        dataSource:{mod:appsConfig.modList.club,act:'mdetail'}
    },
    mienContent:{//风采详情
        tpl:'mien-content',
        dataSource:{mod:appsConfig.modList.club,act:'mdetail'}
    },
    mienReplyList:{//获取风采评论
        dataSource:{mod:appsConfig.modList.club,act:'mienreplylist'}
    },
    a:{//
        dataSource:{mod:'',act:''}
    }
};
appsConfig.tplPath =  location.href.replace(/show\.html\?.*/,'')+'tpl/',
(function(){
    window['X'] = {
        loadFile   : function (url,type){
            var file;
            if(type == 'css'){
                file = document.createElement("link");
//            link.type = "text/css";
                file.rel = "stylesheet";
                file.href = url+'.css';
            }else if(type == 'js'){
                file = document.createElement("script");
                file.src = url+'.js';
            }
            document.getElementsByTagName("head")[0].appendChild( file );
        },

        loadTpl :  function(fileName,callback){
            $.ajax({
                type    :   'GET',
                url     :   appsConfig.tplPath+fileName +'.htm',
                success :   function(tpl){
                    callback(tpl);
                },
                error   :   function(){
                    callback('error');
                }
            });
        },
        loadData : function(callback,obj,async){
            obj = obj ? obj : {app:'',arguments:''};
            async = async ? false : true;
            $.ajax({
                type        :   'POST',
                url         :   this._handleUrl(obj.app),
                data        :   obj.app?obj.arguments:appsConfig.getData.arguments,
                dataType    :   'json',
                async       :   async,
                success     :   function(res){
                    callback(res)
                },
                error       :   function(){
                    callback('error')
                }
            });
        },
        _handleUrl  :   function(app){
            var app = app ? app : 'a';
            var url = '';
                url += location.href.replace(/api.*/,'')+'index.php?app=api&l=1&';
                url += app!='a'?'mod='+appsConfig.map[app].dataSource.mod:'mod='+appsConfig.show['dataSource']['mod'];
                url += app!='a'?'&act='+appsConfig.map[app].dataSource.act:'&act='+appsConfig.show['dataSource']['act'];
                url += '&oauth_token='+appsConfig.getData.token.split(',')[0]+'&';
                url += 'oauth_token_secret'+appsConfig.getData.token.split(',')[1];
//            console.log(url)
            return url;
        },
        handleTime  :   function(now){
            return new Date(parseInt(now) * 1000).toLocaleString().replace('年', "-").replace('月',"-").replace('日', "");
        },
        popup   :   function(message){
            $("#message-box").remove()
            var box = '<div id="message-box" class="message-box text-center" style="z-index:999;min-height: 10%;padding-top: 8px;position: absolute;top: 45%;width: 50%;left: 25%;background:#fdfdfd;border:1px solid #CCCCCC;opacity: 1;border-radius: 5px;color: #3C763D"><strong>'+message+'</strong></div>';
            $("body").append(box);
            setTimeout(function(){$("#message-box").remove()},2000)
        },
        popupAction  :   function(actions){
            $("#message-box").remove()
            var box = '<div id="message-box" class="message-box text-center" style="z-index:999;min-height: 10%;padding-top: 8px;position: absolute;top: 20%;width: 80%;left: 10%;opacity: 1;color: #3C763D">';
                box +='<div class="list-group">';
                box +='<a href="#" class="list-group-item" id="popup-hide">取消</a>';
                box += '</div>';
                $("body").append(box);
            $.each(actions,function(index,item){
                $('#message-box .list-group').before('<a href="#" class="list-group-item" id='+item.id+'>'+item.content+'</a>');
                $('#'+item.id).tap(function(){item.call();$("#message-box").remove()});
            });
            $('#popup-hide').tap(function(){$("#message-box").remove()});
        }
    };
})();
