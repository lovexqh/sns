<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 徐程亮
 * Date: 13-5-22
 * Time: 上午10:36
 * To change this template use File | Settings | File Templates.
 */
class BlogApi extends Api{

    /**
     +---------------------------------------------------
     *show——返回某个文章列表
     +---------------------------------------------------
     * @return array
     * @author  徐程亮
     +---------------------------------------------------
     *创建时间：13-6-22 下午4:37
     +----------------------------------------------------
     */
    public function show(){
//        if(!$_POST){exit;}
        $mid = (int)$_POST['mid'];//用户id
        $uid = (int)$_POST['user_id']; //文章作者id
        $category = t($_POST['type']);//文章分类
        $count = (int)$_POST['count'] ? (int)$_POST['count'] : 20;
        $page = (int)$_POST['page'] ? (int)$_POST['page'] : 1;
        $start = ($page-1)*$count;
        $conditions = '';
        if($uid && ($uid!=$mid))
            if(getFollowState($mid,$uid)=='havefollow'){
                $conditions .= "b.uid = $uid and private=0 or private=2 and ";
            }elseif(getFollowState($mid,$uid)=='unfollow'){
                $conditions .= "b.uid = $uid and private=0 and ";
            }

        if(!$uid){
            $conditions .= 'private=0 and ';
        }

        if(($mid==$uid) && $mid && $uid){
            $conditions .= "b.uid = $uid and ";
        }
        if($category)
            $conditions .= "b.category = $category and ";
        $limit = $start.','.$count;
        $list = self::getBlogs($conditions,$limit);
        //TODO 路径更新
        $path = '/EDNS-SNS';
        $arr = array();
        foreach($list as $v){
            $img_path = SITE_PATH.'/data/uploads/avatar/'.$v['author_id'].'/small.jpg';
            //http://esn.ruijie-grid.com/data/uploads/avatar/122/middle.jpg
            if(file_exists($img_path))
                $v['author_img'] = 'http://esn.ruijie-grid.com/data/uploads/avatar/'.$v['author_id'].'/small.jpg';
            else
                $v['author_img'] = 'http://esn.ruijie-grid.com/public/themes/edustyle/images/user_pic_middle.gif';
            $v['time'] = date("Y-m-d H:i:s",$v['time']);
            $arr[] = $v;
        }
        return $arr;
    }

    /**
     *+---------------------------------------------------
     *detail——返回文章详细信息
     *+---------------------------------------------------
     * @return mixed
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-6-22 下午4:32
     *+----------------------------------------------------
     */
    function detail(){
        $art_id = (int)$_POST['art_id'];
        $format = t($_REQUEST['format']);
        if($format == 'text'){
            $sql = "select
                    b.content,b.private
                from ts_blog b
                where id=$art_id";

            $m = new Model();
            $detail = $m->query($sql);
            echo $detail[0]['content'];
        }else{
            $sql = "select
                    b.id art_id,title,u.uname author,b.content,private,FROM_UNIXTIME(b.cTime,'%Y-%m-%e %H:%k:%i') time,b.category_title type,b.commentCount comment_num,b.readCount read_num
                from ts_blog b
                left join ts_user u on u.uid=b.uid
                where id=$art_id";

            $m = new Model();
            $detail = $m->query($sql);
            return $detail;
        }
    }

    /**
     +---------------------------------------------------
     *comment——评论文章
     +---------------------------------------------------
     * @return int
     * @author  徐程亮
     +---------------------------------------------------
     *创建时间：13-6-22 下午4:33
     +----------------------------------------------------
     */
    public function comment() {
        $_POST['with_new_weibo']		= intval($_POST['with_new_weibo']);
        $_POST['appid']					= intval($_POST['art_id']);
        $_POST['comment']				= $_POST['content'];
        $_POST['to_id']					= intval($_POST['postid']);
        $_POST['type']					= t($_POST['type']);
        $_POST['author_uid']			= intval($_POST['uid']);
        $_POST['title']					= t(html_entity_decode($_POST['title'],ENT_QUOTES));
        //TODO URL待更新
        $_POST['url']					= 'esn-test/app/blog/detail/'.$_POST['art_id'].'?mid='.(int)$_POST['mid'];
        $_POST['table']					= t($_POST['table']);
        $_POST['id_field']				= 'id';
        $_POST['comment_count_field']	= 'commentCount';
        //$_POST['comment_ip'] = get_client_ip();
        $app_alias	= getAppAlias($_POST['type']);

        // 被回复内容
        $former_comment = array();
        if ( $_POST['to_id'] > 0 )
            $former_comment = M('comment')->where("`id`='{$_POST['to_id']}'")->find();

        // 插入新数据
        $map['type']	= $_POST['type']; // 应用名
        $map['appid']	= $_POST['appid'];
        $map['appuid']	= $_POST['author_uid'];
        $map['uid']		= (int)$_POST['mid'];
        $map['comment']	= t(getShort($_POST['comment'], $GLOBALS['ts']['site']['length']));
        $map['cTime']	= time();
        $map['toId']	= $_POST['to_id'];
        $map['status']	= 0; // 0: 未读 1:已读
        $map['quietly']	= 0;
        $map['to_uid']	= $former_comment['uid'] ? $former_comment['uid'] : $_POST['author_uid'];
        $map['comment_ip'] = get_client_ip();
        $map['data']	= serialize(array(
            'title' 				=> $_POST['title'],
            //TODO 地址待更新
            'url'					=> 'esn-test/app/blog/detail/'.$_POST['art_id'].'?mid='.$this->mid,
            'table'					=> $_POST['table'],
            'id_field'				=> $_POST['id_field'],
            'comment_count_field'	=> $_POST['comment_count_field'],
        ));
        $res = M('comment')->add($map);

        // 避免命名冲突
        unset($map['data']);

        if ($res) {
            // 应用回调: 增加应用的评论计数
            $this->__doAddCallBack( $_POST['appid'], $_POST['table'], $_POST['id_field'], $_POST['comment_count_field'] );
            //积分处理
            $setCredit = X('Credit');
            if($map['toId'] > 0 && $this->mid != $map['to_uid']){
                $setCredit->setUserCredit($this->mid,'reply_comment')
                    ->setUserCredit($map['to_uid'],'replied_comment');
            }else if($this->mid != $map['to_uid']){
                $setCredit->setUserCredit($this->mid,'add_comment')
                    ->setUserCredit($map['to_uid'],'is_commented');
            }
            // 发表微广播
            if ($_POST['with_new_weibo']) {
                $from_data = array('app_type'=>'local_app', 'app_name'=>$_POST['type'], 'title'=>$_POST['title'], 'url'=>$_POST['url']);
                $from_data = serialize($from_data);
                D('Weibo','weibo')->publish($this->mid,
                    array(
                        'content' => html_entity_decode(
                            $_POST['comment'] . ($_POST['to_id'] > 0?(' //@' . getUserName($former_comment['uid']) . ' :' . $former_comment['comment']):''),
                            ENT_QUOTES
                        ),
                    ), 0, 0, '', '', $from_data);
            }
            /*
                            // 给被回复人发送通知
                            if ($former_comment['uid']) {
                                $data = array(
                                    'app_alias'	=> $app_alias,
                                    'url'		=> $_POST['url'],
                                    'title'		=> $_POST['title'],
                                    'content'	=> $_POST['comment'],
                                    'my_content'=> $former_comment['comment'],
                                );
                                service('Notify')->send($former_comment['uid'], 'home_replyComment', $data, $this->mid);
                                unset($data);
                            }
                            // 给作者发送通知 ( 当被回复人和作者为同一人时, 只发一个通知. 优先被回复. )
                            if ($_POST['author_uid'] > 0 && $_POST['author_uid'] != $former_comment['uid']) {
                                $data = array(
                                    'app_alias'	=> $app_alias,
                                    'url'		=> $_POST['url'],
                                    'title'		=> $_POST['title'],
                                    'content'	=> $_POST['comment'],
                                );
                                service('Notify')->send($_POST['author_uid'], 'home_addComment', $data, $this->mid);
                                unset($data);
                            }
            */

            // 组装结果集
//            $result = $map;
//            $result['data']['uavatar']  		= getUserSpace($this->mid,'null','_blank','{uavatar}');
//            $result['data']['uspace']   		= getUserSpace($this->mid,'null','_blank','{uname}');
//            //$result['data']['comment']  		= $_POST['comment'];
//            $result['data']['ctime']    		= L('just_now');
//            $result['data']['uname']    		= getUserName($this->mid);
//            $result['data']['comment']			= formatComment(t($_POST['comment']));
//            $result['data']['id']				= $res;
//            $result['data']['userGroupIcon']	= getUserGroupIcon($this->mid);
//            $result['data']['del_state']   		= 1;
            return 1;
        }else{
            return 0;
        }
    }

    /**
     +---------------------------------------------------
     *comments——返回指定文章评论信息
     +---------------------------------------------------
     * @return array
     * @author  徐程亮
     +---------------------------------------------------
     *创建时间：13-6-22 下午4:34
     +----------------------------------------------------
     */
    function comments(){
        $comments = self::getComments($_POST['art_id'],$_POST['page'],$_POST['limit'],$_POST['mid']);
        $arr = array();
        foreach($comments as $v){
            $v['content'] = clearText($v['content']);
            $arr[] = $v;
        }
        return $arr;
    }

    /**
     +---------------------------------------------------
     *create——添加一篇文章
     +---------------------------------------------------
     * @return int
     * @author  徐程亮
     +---------------------------------------------------
     *创建时间：13-6-22 下午4:34
     +----------------------------------------------------
     */
    function create(){
        $data = array();
        $data['uid'] = intval($_POST['uid']);
        $data['title'] = t($_POST['title']);
        $data['content'] = t($_POST['content']);
        $data['category'] = intval($_POST['typeId']);
        $data['category_title'] = t($_POST['typeName']);
//        $data['name'] = D('blog','blog')->getOneName($this->mid);
        $data['cTime'] = time();
        $data['mTime'] = time();
        $add=M('blog')->add($data);
        if($add)
            return 1;
        else
            return 0;
    }

    /**
     *+---------------------------------------------------
     *destroy——删除文章
     *+---------------------------------------------------
     * @return int
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function destroy(){
        $mid = (int)$_POST['mid'];
        $art_id = (int)$_POST['art_id'];
        if(M('blog')->where(array('id'=>$art_id),array('uid'=>$mid))->delete()){
            if(M('comment')->where(array('appid'=>$art_id),array('type'=>'blog'),array('appuid'=>$mid))->delete()){
                return 1;
            }else{
                return 1;
            }
        }else{
            return 0;
        }
    }

    // 评论成功后, 回调处理, 增加评论计数
    private function __doAddCallBack($appid, $table,$id_field = 'id', $comment_count_field = 'commentCount') {
        return $table ? M($table)->setInc($comment_count_field, "`$id_field`='$appid'") : false;
    }

    /*
     * 调用所有文章——blogAPI使用
     * @param $conditions where条件
     * @param $limit Limit条件
     * @author 徐程亮 2012-5-23
     */

    static public function getBlogs($conditions,$limit){
        $sql = "select
                    b.id art_id,b.title,u.uname author,b.uid author_id,b.cTime time,b.category_title type,b.readCount read_num,b.commentCount comment_num
                from ts_blog b
                left join ts_user u on u.uid=b.uid
                where $conditions 1=1
                order by mTime desc
                limit $limit";
        $m = new Model();
        $res = $m->query($sql);
        return $res;
    }

    /*
     * 获取指定文章的评论列表
     */
    static public function  getComments($id,$page,$limit,$uid){
        $page = $page ? $page : 1;
        $limit = $limit ? $limit :20;
        $start = ($page-1)*$limit;
        $sql = "select c.id comment_id,c.comment content,FROM_UNIXTIME(c.cTime,'%Y-%m-%e %H:%k:%i') ctime,u.uname,u.uid
                from ts_comment c
                left join ts_user u on u.uid=c.uid
                where c.appid=$id and type='blog'
                order by c.cTime desc
                limit $start,$limit
                ";
        $m = new Model();
        return $m->query($sql);
    }

    function test(){
        $this->render();
    }


}
