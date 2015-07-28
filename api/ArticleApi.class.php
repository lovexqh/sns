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
        //查看自己
        $arr = array();
        if($who ==1 ){
            $condition = "u.uid=".$uid;
        //查看所有人
        }elseif($who == 3){
            //获取子分类
            $arr['category'] = M('square_category')->where('p_id='.$cid)->field('id,p_id,category_name')->order('display_order')->findAll();
            if(!$arr['category'])$arr['category']=array();
            $status = $this->rule();
            $condition = "s.square_id=$cid and (b.private=0 or b.private=2) and $status";
         //查看某个用户
        }elseif($who == 2){
            $status = $this->rule();
            $condition = "u.uid=$author and (b.private=0 or b.private=2) and $status";
        }
//        if(!$cid)exit;
        $limit = getLimit();
        $sql = "select b.id,b.uid author_id,title,u.uname author_name,b.readCount,b.commentCount,FROM_UNIXTIME(b.cTime,'%Y-%m-%e %H:%k:%i') cTime,b.private
                from ts_blog b
                inner join ts_square_blog s on b.id=s.blog_id
                inner join ts_user u on  u.uid=b.uid
                where $condition
                order by b.cTime
                limit $limit
                ";
        $arr['articles'] = M('')->query($sql);
        $data = getUserImg($arr['articles']);
//        //获取用户头像
//        $data = array();
//        foreach($arr['articles'] as $v){
//            $img_path = SITE_PATH.'/data/uploads/avatar/'.$v['author_id'].'/small.jpg';
//            //http://esn.ruijie-grid.com/data/uploads/avatar/122/middle.jpg
//            if(file_exists($img_path))
//                $v['author_img'] = SITE_URL.'/data/uploads/avatar/'.$v['author_id'].'/small.jpg';
//            else
//                $v['author_img'] = SITE_URL.'/public/themes/edustyle/images/user_pic_middle.gif';
//            $data[] = $v;
//        }
        $arr['articles'] = $data;
        return $arr;
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
        if($author_id == $uid){
            $condition = "b.id=$id";
        //查看他人的文章
        }else{
            $status = $this->rule();
            $condition = "b.id=$id and (b.private=0 or b.private=2) and $status";
        }
        if(!$id)exit;
        $sql = "select b.id,b.uid author_id,b.title,b.content,u.uname author_name,b.readCount,b.commentCount,FROM_UNIXTIME(b.cTime,'%Y-%m-%e %H:%k:%i') cTime
                from ts_blog b
                inner join ts_user u on  u.uid=b.uid
                where $condition
                order by b.cTime
                ";
        $article = M()->query($sql);
        $article[0]['content'] = strip_tags($article[0]['content'],"<div><p><img><br><strong><font><span><td><tr><th><em>");
        $this->render(array('article'=>$article[0]),false);
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
}










