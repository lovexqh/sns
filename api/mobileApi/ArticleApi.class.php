<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 徐程亮
 * date: 13-7-10 上午9:32
 * To change this template use File | Settings | File Templates.
 */
class ArticleApi extends Api{

    /**
     *+---------------------------------------------------
     *category——返回子分类和该分类下的文章列表
     *+---------------------------------------------------
     * @return array
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-7-10 下午2:43
     *+----------------------------------------------------
     */
    function articleList(){
        //分类id
        $cid = (int)$_REQUEST['cid'];
        $who = (int)$_REQUEST['who'];
        $author = (int)$_REQUEST['author_id'];
        $uid = $this->mid;
        $limit = getLimit(false,10);
        //查看自己
        if($who ==1 ){
            $condition = "b.status<>2 and u.uid=".$uid;
            if($cid)$condition.=' and category='.$cid;
            $url = siteUrl(array('who'=>1,'cid'=>$cid));
            //查看所有人
        }elseif($who == 3){
            $url = siteUrl(array('who'=>3,'cid'=>$cid));
            $blogs = procExecute("call vcBlogCategory($cid,'{$limit}','b.cTime','1');");
        }elseif($who == 2){
            $url = siteUrl(array('who'=>2));
            $condition = "u.uid=$author and (b.private=0 or b.private=2) and b.status<>2";
        }elseif($who==5){
            $keyword=t($_REQUEST['keyword'],true,ENT_QUOTES);
            if(!$keyword)exit;
            $url = siteUrl(array('who'=>5,'keyword'=>$keyword));
            $condition = "((u.uname like '%$keyword%') or (b.title like '%$keyword%')) and b.status<>2";
        }
        if($who == 2 || $who == 1 || $who == 5){
            $sql = "select b.id,b.uid author_id,b.title,b.content,u.uname author_name,b.readCount,b.commentCount,b.cTime,b.private
                from ts_blog b
                inner join ts_user u on  u.uid=b.uid
                where $condition
                order by b.cTime desc
                limit $limit
                ";
            $blogs = M('')->query($sql);
        }

        //我收藏的文章
        if($who == 4){
            $url = siteUrl(array('who'=>4));
            $sql = "select b.id,b.uid author_id,b.title,b.content,u.uname author_name,b.readCount,b.commentCount,b.cTime,b.private
                from ts_blog b
                left join ts_square_blog s on b.id=s.blog_id
                inner join ts_user u on  u.uid=b.uid
                inner join ts_favorite f on b.id=f.fid and f.appname='blog' and f.status=1
                where a.favuid=$this->mid
                order by b.cTime desc
                limit $limit
                ";
            $blogs = M('')->query($sql);
        }

        /**
         *  关键字搜索
         */
//        if($who==5 && ($keyword=t($_REQUEST['keyword'],true,ENT_QUOTES))){
//            $limit = getLimit();
//            $url = siteUrl(array('who'=>5,'keyword'=>$keyword));
//
//            $sql = "select b.id,b.uid author_id,b.title,b.content,u.uname author_name,b.readCount,b.commentCount,b.cTime,b.private
//                    from ts_blog b
//                    left join ts_square_blog s on b.id=s.blog_id
//                    inner join ts_user u on  u.uid=b.uid
//                    where $condition
//                    order by b.cTime desc
//                    limit $limit";
//            $blogs = M('')->query($sql);
//        }

        //关注的人的文章
        if($who == 6){
            $now_following_sql = D('Follow', 'weibo')->getNowFollowingSql($uid);
            $r = M('')->query($now_following_sql);
            $uids = '';
            foreach ($r as $v) {
                $uids .= $v['fid'].',';
            }
            $uids = substr($uids,0,-1);

            //没有关注任何人时,不进行查询
            $followCount = D('Follow','weibo')->getUserFollowCount($uid);
            if($followCount){
                $url = siteUrl(array('who'=>5));
                $sql = "select b.id,b.uid author_id,b.title,b.content,u.uname author_name,b.readCount,b.commentCount,b.cTime,b.private
                from ts_blog b
                left join ts_square_blog s on b.id=s.blog_id
                inner join ts_user u on  u.uid=b.uid
                where b.status<>2 and u.uid in ($uids)
                order by b.cTime desc
                limit $limit
                ";
                $blogs = M('')->query($sql);
            }
        }

        $blogs = $blogs?$blogs:array();
        //切割字符串
        foreach ($blogs as $k=>$v) {
            $v['content'] = msubstr(clearText($v['content']),0,50,'utf-8',false);
            $v['cTime'] = putTime($v['cTime']);
            $v['author_img'] = getUserFace($v['author_id']);
            $blogs[$k] = $v;
        }

        if($_REQUEST['pc']==1){
            return $blogs;
        }
        //渲染模版
        $this->render(array('data'=>$blogs,'url'=>$url,'who'=>$who),true,'common','common/list',array('common-list'));
    }

    private function rule(){
        if(service('ForeAdmin')->isAuditApp('blog')){
            $status = "b.status=1";
        }else{
            $status = "(b.status=0 or b.status=1)";
        }
        return $status;
    }
    /**
     *+---------------------------------------------------
     *show——查看文章
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-7-10 下午2:47
     *+----------------------------------------------------
     */
    function show(){
        $id = (int)$_REQUEST['id'];
        $author_id = (int)$_REQUEST['author_id'];
        $uid = $this->mid;
        //查看自己的文章
        if($author_id == $uid || ((int)$_REQUEST['self']==1)){
            $condition = "b.id=$id";
            //查看他人的文章
        }else{
            $status = $this->rule();
            $condition = "b.id=$id and (b.private=0 or b.private=2) and $status";
        }
        if(!$id)exit;
        $sql = "select b.id,b.uid author_id,b.title,b.content,u.uname author_name,b.readCount,b.commentCount,b.cTime
                from ts_blog b
                inner join ts_user u on  u.uid=b.uid
                where $condition
                order by b.cTime
                ";
        $article = M()->query($sql);
        M('blog')->setInc('readCount','id='.$id);
        $article[0]['content'] = strip_tags($article[0]['content'],"<div><p><img><br><strong><font><span><td><tr><th><em>");
        $article[0]['face'] = getUserFace($article[0]['author_id']);
        $arr = array(
            'data' => $article[0],
            'url' => siteUrl(array('id'=>$id,'type'=>'blog'),'commentsList','Public'),
        );
        $this->render($arr,true,'zepto','common/blog-show',array('blog-show','comments-list'));
//        $this->render(array('article'=>$article[0]),false);
    }

    /**
     *+---------------------------------------------------
     *checkRule——检查是否有权限查看文章内容
     *+---------------------------------------------------
     * @return int
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-7-12 上午8:41
     *+----------------------------------------------------
     */
    function checkRule(){
        $author_id = (int)$_REQUEST['author_id'];
        if(!$author_id)exit;
        $uid = $this->mid;
        if(getFollowState($uid,$author_id) == 'eachfollow'){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     *+---------------------------------------------------
     *articleCategory—获取自定义分类
     *+---------------------------------------------------
     * @return array
     * @author  徐程亮
     *+---------------------------------------------------
     */
    function articleCategory(){
        $uid = $this->mid;
        if($data = M('blog_category')->field('id,name')->where('uid='.$uid.' or uid=0')->findAll()){
            return $data;
        }else{
            return array();
        }
    }
}
