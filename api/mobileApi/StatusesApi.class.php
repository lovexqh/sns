<?php
//微广播Api接口
class StatusesApi extends Api{

    public $lazyImg;
    public $lazyUser;
    public $playImg;

    function _initialize(){
        $this->lazyImg = SITE_URL.'/api/mobileViews/public/image/lazy.jpg';
        $this->lazyUser = SITE_URL.'/api/mobileViews/public/image/lazy-user.gif';
        $this->playImg = SITE_URL.'/api/mobileViews/public/image/play.png';
        if(!$this->mid){
            $this->mid = (int)$_REQUEST['uid']?(int)$_REQUEST['uid']:exit;
        }
    }

    public function userSpace(){
        $uid = $this->mid;
        $user_id = (int)$_REQUEST['user_id']?(int)$_REQUEST['user_id']:exit;
        $data = getUserInfo($user_id,'',$uid);
        if($uid == $user_id){
            unset($data['is_followed']);
        }
        $arr = array(
            'data' => $data,
        );
        $this->render($arr,true,'zepto','common/user-space',array('user-space'));
    }

    //获取最新更新的公共微广播消息
    function public_timeline(){
        $limit = getLimit(true);
        $data = D('WeiboApi','weibo')->public_timeline( $this->since_id , $this->max_id ,$limit ) ;
        $url = siteUrl();
        $data = self::handleData($data);
        if($_POST['pc'] == 1){
            if($data){
                return $data;
            }else{
                return 0;
            }
        }

        $this->render(array('data'=>$data,'url'=>$url,'lazy'=>$this->lazyImg,'lazyUser'=>$this->lazyUser,'playImg'=>$this->playImg),true,'weibo','list');
    }

    /**
     *+---------------------------------------------------
     *class_timeline——班级
     *+---------------------------------------------------
     * @return array|int
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function class_timeline(){
        $cid = (int)$_REQUEST['classid']?(int)$_REQUEST['classid']:exit;
        $limit = getLimit(true);
        $data = D('WeiboApi','weibo')->class_timeline( $cid, $this->since_id , $this->max_id ,$limit) ;
        $url = siteUrl(array('classid'=>$cid));
        $data = self::handleData($data);
        if($_POST['pc'] == 1){
            if($data){
                return $data;
            }else{
                return 0;
            }
        }

        $this->render(array('data'=>$data,'url'=>$url,'lazy'=>$this->lazyImg,'lazyUser'=>$this->lazyUser,'playImg'=>$this->playImg),true,'weibo','list');
    }

    /**
    +---------------------------------------------------
     *handleData—处理微博数据
    +---------------------------------------------------
     * @param $data
     * @return array
     * @author  徐程亮
    +---------------------------------------------------
     *创建时间：
    +----------------------------------------------------
     */
    static function handleData($data){
        if($data){
            foreach($data as $k=>$v){
                $data[$k]['agoTime'] = putTime(strtotime($data[$k]['ctime']));
                $data[$k]['content'] = replaceFace($data[$k]['content']);
                if($v['type'] == 999 || $v['type']==3){
                    $data[$k]['type_data'] = unserialize($v['type_data']);
                }
                if($v['transpond_id'] && ($v['transpond_data']['type']==999 || $v['transpond_data']['type']==3)){
                    $data[$k]['transpond_data']['type_data'] = unserialize($v['transpond_data']['type_data']);
                }
                if($v['transpond_id']){
                    $data[$k]['transpond_data']['content'] = replaceFace($data[$k]['transpond_data']['content']);
                }
            }
            return $data;
        }else{
            return $data = array();
        }

    }

    function friends(){
        $limit = getLimit();
        $data = D('WeiboApi','weibo')->friends_timeline( $this->mid , $this->since_id , $this->max_id ,$limit) ;
        return $data;
    }

    //获取当前用户所关注用户的最新微广播信息
    function friends_timeline(){
        $limit = getLimit(true);
        $data = D('WeiboApi','weibo')->friends_timeline( $this->mid , $this->since_id , $this->max_id ,$limit) ;
//        $url = siteUrl(array('classid'=>$cid));
//        return $data;
        //ajax取数据的url-徐
        $url = siteUrl();
        $data = self::handleData($data);
        if($_POST['pc'] == 1){
            if($data){
                return $data;
            }else{
                return 0;
            }
        }

        $this->render(array('data'=>$data,'url'=>$url,'lazy'=>$this->lazyImg,'lazyUser'=>$this->lazyUser,'playImg'=>$this->playImg),true,'weibo','list');
    }

    //获取用户发布的微广播信息列表
    function user_timeline() {
        $uid = (int)$_REQUEST['uid']?(int)$_REQUEST['uid']:exit;
        $limit = getLimit(true);
        $data = D('WeiboApi','weibo')->user_timeline( $uid, $this->user_name , $this->since_id , $this->max_id ,$limit);
        $url = siteUrl(array('uid'=>$uid));
        $data = self::handleData($data);
        if($_POST['pc'] == 1){
            if($data){
                return $data;
            }else{
                return 0;
            }
        }
        $this->render(array('data'=>$data,'url'=>$url,'lazy'=>$this->lazyImg,'lazyUser'=>$this->lazyUser,'playImg'=>$this->playImg),true,'weibo','list');
    }

    //获取@当前用户的微广播列表
    function mentions(){
        $limit = getLimit(true);
        $data = D('WeiboApi','weibo')->mentions($this->mid , $this->since_id , $this->max_id , $limit);
        $url = siteUrl();
        $data = self::handleData($data);
        if($_POST['pc'] == 1){
            if($data){
                return $data;
            }else{
                return 0;
            }
        }
        $this->render(array('data'=>$data,'url'=>$url,'lazy'=>$this->lazyImg,'lazyUser'=>$this->lazyUser,'playImg'=>$this->playImg),true,'weibo','list');
    }

    //获取当前用户发送及收到的评论列表
    function comments_timeline(){
        return D('WeiboApi','weibo')->getCommentlist($this->mid,'all', $this->since_id , $this->max_id , $this->count , $this->page);
    }

    function show(){
        //修改 by 徐程亮
        $data = D('Weibo','weibo')->getOneApi($this->id, null, $this->mid);
        $comments_url = siteUrl(array('id'=>$data['weibo_id']),'comments');
        if($data['type'] == 999 || $data['type']==3){
            $data['type_data'] = unserialize($data['type_data']);
        }
        if($data['transpond_id'] && ($data['transpond_data']['type']==999 || $data['transpond_data']['type']==3)){
            $data['transpond_data']['type_data'] = unserialize($data['transpond_data']['type_data']);
        }
        $this->render(array('data'=>$data,'playImg'=>$this->playImg,'comments_url'=>$comments_url),true,'zepto','detail',array('statuses-show','comments-list'));
//        $this->render($arr,true,'zepto','common/blog-show',array('blog-show','comments-list'));
    }

    //获取当前用户发出的评论
    function comments_by_me() {
        return D('WeiboApi','weibo')->getCommentlist($this->mid,'send',$this->since_id , $this->max_id , $this->count , $this->page);
    }

    //获取当前用户收到的评论
    function comments_receive_me() {
        return D('WeiboApi','weibo')->getCommentlist($this->mid,'receive',$this->since_id , $this->max_id , $this->count , $this->page);
    }

    //获取指定微广播的评论列表
    function comments(){
        $limit = getLimit();
        $data = D('WeiboApi','weibo')->comments($this->id,$this->since_id , $this->max_id , $limit);
        $_POST['pc'] = 1;
        if($_POST['pc'] == 1){
            if($data){
                foreach ($data as $k=>$v) {
                    $data[$k]['ctime'] = putTime($data[$k]['ctime']);
                }

                return $data;
            }else{
                return 0;
            }
        }
    }

    //发布一条微广播
    function update(){
        $data['content'] = $this->data['content'];
        $id = D('Weibo','weibo')->publish( $this->mid,$data,$this->data['from'],0,'',array('sina'));
        return (int) $id;
    }

    //上传一张图片并返回图片地址
    function uploadpic(){
        if( $_FILES['pic'] ){
            //执行上传操作
            $savePath =  $this->_getSaveTempPath();
            $filename = md5( time().'teste' ).'.'.substr($_FILES['pic']['name'],strpos($_FILES['pic']['name'],'.')+1);
            if(@copy($_FILES['pic']['tmp_name'], $savePath.'/'.$filename) || @move_uploaded_file($_FILES['pic']['tmp_name'], $savePath.'/'.$filename))
            {
                $result['boolen']    = 1;
                $result['type_data'] = 'temp/'.$filename;
                $result['picurl']    = SITE_URL.'/data/uploads/temp/'.$filename;
            } else {
                $result['boolen']    = 0;
                $result['message']   = '上传失败';
            }
        }else{
            $result['boolen']    = 0;
            $result['message']   = '上传失败';
        }
        return $result;
    }

    //上传临时文件
    private function _getSaveTempPath(){
        $savePath = SITE_PATH.'/data/uploads/temp';
        if( !file_exists( $savePath ) ) mk_dir( $savePath  );
        return $savePath;
    }

    //发布一个图片微广播
    function upload(){
        $uppic = $this->uploadpic();
        $pic = $uppic['boolen']?$uppic['type_data']:h($this->data['pic']);
        $data['content'] = h( $this->data['content'] );
        $id = D('Weibo','weibo')->publish( $this->mid,$data,$this->data['from'],1,$pic,array('sina'));
        return (int) $id;
    }

    //删除一条微广播
    function destroy(){
        $result = D('Operate','weibo')->deleteMini($this->id,$this->mid);
        return (int) $result;
    }

    //删除一条评论
    function commentDestroy()
    {
        $result = D('Comment','weibo')->deleteComments($this->id, $this->mid);
        return (int) $result['boolen'];
    }

    //对一个微广播发一条评论
    function comment(){
        $post['reply_comment_id'] = intval( $this->data['reply_comment_id'] );  //回复 评论的ID
        $post['weibo_id']         = intval( $this->data['weibo_id'] );          //回复 微广播的ID
        $post['content']          = $this->data['comment_content'];         	//回复内容
        $post['transpond']        = intval($this->data['transpond']);           //是否同是发布一条微广播
        $post['from']             = intval($this->data['from']);            	//来自哪里
        $id = D('Comment','weibo')->doaddcomment( $this->mid ,$post,true );
        return (int) $id;
    }

    /**
     *+---------------------------------------------------
     *weiboContent—获取微博内容（用于转发）
     *+---------------------------------------------------
     * @return array
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function weiboContent(){
        $id = (int)$_REQUEST['id']?(int)$_REQUEST['id']:exit;
        $content = M('weibo')->where('weibo_id='.$id)->field('content')->find();
        if($content){
            $content['content'] = replaceFace($content['content']);
            return $content;
        }else{
            return array();
        }
    }

    //转发一条微广播
    function repost(){
        $post['content']		=  $this->data['content'] ;                  //转发内容
        $post['transpond_id']   = intval( $this->data['transpond_id'] );        //转发的微广播ID
        $post['reply_weibo_id'] = explode(',',$this->data['reply_data']);       //给xx同时评论的数组对象(此处传过来的是微广播的ID)
        $post['from'] 			= intval($this->data['from']);
        $id = D('Weibo','weibo')->transpond($this->mid,$post);
        return (int) $id;
    }

    //用户关注列表
    function following(){
        $uid = (int)$_REQUEST['uid']?(int)$_REQUEST['uid']:exit;
        $limit = getLimit(false,10);
        $url = siteUrl(array('uid'=>$uid));
        $destroy = siteUrl(array('uid'=>$uid),'destroy','Friendships');
        $create = siteUrl(array('uid'=>$uid),'create','Friendships');
        $data = D('WeiboApi','weibo')->following($uid , $this->user_name , $this->since_id , $this->max_id , $limit);
        if($_POST['pc'] == 1){
            if($data){
                return $data;
            }else{
                return 0;
            }
        }
        $arr = array(
            'data'      =>  $data,
            'url'       =>  $url,
            'destroy'   =>  $destroy,
            'create'    =>  $create,
        );
        $this->render($arr,true,'zepto','common/list-user',array('user-list'));
    }

    //用户被关注列表
    function followers(){
        $uid = (int)$_REQUEST['uid']?(int)$_REQUEST['uid']:exit;
        $limit = getLimit(false,10);
        $url = siteUrl(array('uid'=>$uid));
        $destroy = siteUrl(array('uid'=>$uid),'destroy','Friendships');
        $create = siteUrl(array('uid'=>$uid),'create','Friendships');
        $data = D('WeiboApi','weibo')->followers($uid , $this->user_name , $this->since_id , $this->max_id ,$limit);
        if($_POST['pc'] == 1){
            if($data){
                return $data;
            }else{
                return 0;
            }
        }
        $arr = array(
            'data'      =>  $data,
            'url'       =>  $url,
            'destroy'   =>  $destroy,
            'create'    =>  $create,
        );
        $this->render($arr,true,'zepto','common/list-user',array('user-list'));
    }

    // 搜索微广播
    public function search()
    {
        $result = D('WeiboApi','weibo')->search($this->data['key'], $this->since_id, $this->max_id, $this->count, $this->page);
        if (empty($result))
            $result = array();

        return $result;
    }


    // 搜索用户
    public function searchUser()
    {
        $key = t($_REQUEST['key'],true,ENT_QUOTES)?t($_REQUEST['key'],true,ENT_QUOTES):exit;
        $uid = $this->mid;
        $limit = getLimit(false,10);
        $url = siteUrl(array('key'=>$key));
        $destroy = siteUrl(array('uid'=>$uid),'destroy','Friendships');
        $create = siteUrl(array('uid'=>$uid),'create','Friendships');
        $result = D('WeiboApi','weibo')->searchUser($key, $this->mid, $this->since_id, $this->max_id, $limit);
        if (empty($result))
            $result = array();

//		$allowed_key = array('ctime', 'domain', 'face', 'followed_count', 'followers_count', 'is_active', 'is_init', 'is_followed', 'location', 'mini', 'sex', 'uid', 'uname');
//        var_dump($result);
        $data = array();
        foreach ($result as $k => $v) {
            $data[$k]['user']  = $v;
            // 剔除敏感信息
//			foreach ($v as $k2 => $v2)
//				if (!in_array($k2, $allowed_key))
//					unset($result[$k][$k2]);

//			$result[$k]['timestamp'] = $v['ctime'];
//			$result[$k]['ctime']	 = date('Y-m-d H:i:s', $v['ctime']);
//			$result[$k]['location']  = (string)$v['location'];
            $data[$k]['user']['sex'] = getSex($v['sex']);
        }
        if($_POST['pc'] == 1){
            if($data){
                return $data;
            }else{
                return 0;
            }
        }
        $arr = array(
            'data'      =>  $data,
            'url'       =>  $url,
            'destroy'   =>  $destroy,
            'create'    =>  $create,
        );
        $this->render($arr,true,'zepto','common/list-user',array('user-list'));

    }



    //获取话题
}
?>