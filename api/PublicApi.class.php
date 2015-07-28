<?php

class PublicApi extends Api {

    /**
     *+---------------------------------------------------
     *deleteComment—删除评论
     *+---------------------------------------------------
     * @return int
     * @author  徐程亮
     *+---------------------------------------------------
     */

    function deleteComment(){
        $commentId = (int)$_REQUEST['commentId']?(int)$_REQUEST['commentId']:exit;
        $objectId = (int)$_REQUEST['objectId']?(int)$_REQUEST['objectId']:exit;
        $type = $_REQUEST['type'];
        $m = M('comment');
        $r = '';
        switch($type){
            case 'blog':
                $r = $m->where("(appuid=$this->mid or uid=$this->mid) and id=$commentId")->delete();
                $r && M('blog')->setDec('commentCount','id='.$objectId);
                break;
        }
        if($r){
            return 1;
        }else{
            return 0;
        }
    }
    /**
     *+---------------------------------------------------
     *getUserName—获取用户名
     *+---------------------------------------------------
     * @return mixed
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function getUserName(){
        $uid = (int)$_REQUEST['uid']?(int)$_REQUEST['uid']:exit;
        return M('user')->field('uname name')->where('uid='.$uid)->find();
    }
    /**
     *+---------------------------------------------------
     *myCategory—获取我的分类
     *+---------------------------------------------------
     * @return array
     * @author  徐程亮
     *+---------------------------------------------------
     */
    function myCategory(){
        $uid = $this->mid;
        switch($_REQUEST('type')){
            case 'blog':
                $data = M('blog_category')->field('id,name')->where('uid='.$uid.' or uid=0')->findAll();
                break;
            case 'video':
                $data = M('video_category')->field('id,name')->where('userId='.$uid)->findAll();
                break;
        }
        if($data){
            return $data;
        }else{
            return array();
        }
    }

    /**
     *+---------------------------------------------------
     *commentsList——获取评论列表
     *+---------------------------------------------------
     * @return mixed
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    public function commentsList(){
        $id = (int)$_REQUEST['id']?(int)$_REQUEST['id']:exit;
        $type = checkType($_REQUEST['type'],'string')?$_REQUEST['type']:exit;
        $limit = getLimit();
        $sql = "select c.id comment_id,c.comment content,c.cTime ctime,u.uname,u.uid
                from ts_comment c
                left join ts_user u on u.uid=c.uid
                where c.appid=$id and type='{$type}'
                order by c.cTime desc
                limit $limit;
                ";
        $data = M('')->query($sql);
        foreach($data as $k=>$v){
            $data[$k]['face'] = getUserFace($v['uid'],'s');
            $data[$k]['ctime'] = putTime($v['ctime']);
        }
        return $data;
    }


	// 获取MD5加密
	public function getMd5Data() {
		$data = md5($this->data['md5_data']);
		return $data;
	}

	// 通过email获取uid
	public function emailGetUid(){
		$map['email']=$this->data['email'];
		$uid = M('user')->where($map)->getField('uid');
		return $uid;	
	}

    //用户注册
    public function register(){
        $identity_code = array(
            'student' => '3',
            'teacher' => '2',
            'parent' => '4'
        );

    }

    /**
     *+---------------------------------------------------
     *baseCategory—广场的基础分类
     *+---------------------------------------------------
     */
    function baseCategory(){
        //获取分类
        $type=$_REQUEST['type'];
        $section=$_REQUEST['section'];
        $option['Grade']=$_REQUEST['Grade'];
        $option['Subject']=$_REQUEST['Subject'];
        $option['Publisher']=$_REQUEST['Publisher'];
        $option['Volume']=$_REQUEST['Volume'];
        $option['Cell']=$_REQUEST['Cell'];
        $category = model('Knowledge')->getTreeNode($type,$section,$option);

        return $category;
    }

    function category(){
        $pid = (int)$_REQUEST['pid']?(int)$_REQUEST['pid']:exit;
        return M('square_category')->field('id,category_name')->where('p_id='.$pid)->findAll();
    }

    /**
     *+---------------------------------------------------
     *subjectCategory—专题分类//ReSpecialAttribute资源 type资源ReSpecialType
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function subjectCategory(){
        $dataType = $_REQUEST['dataType']?$_REQUEST['dataType']:exit;
        $c = M('category_dictionary')->field('DataName,DataCode')->where("DataType='{$dataType}'")->order('DataOrder')->findAll();
        return $c;
    }


//    function typeCategory(){
//        //获取专题分类
//        $data['category'] = M('category_dictionary')->where("DataType=ReSpecialAttribute")->field("DataCode,DataName")->order("DataOrder")->findAll();
//        //获取专题类型
//        $data['type'] = M('category_dictionary')->where("DataType=ReSpecialType")->field("DataCode,DataName")->order("DataOrder")->findAll();
//    }


    /*
     * 根据学校获取学段信息
	 * @param schoolid学校id
     */
    public function getPhase(){
        $sid = (int)$_POST['schoolid'];
//        $sid = 1;
        $param = array();
        $param['schoolid'] = (string)$sid;
        //通过老师id获取该老师所教班级id和班级名称列表
        $a = get_webService('Usch011',$param);
        foreach($a as $v){
            if($v['jd'] == 21){
                $v['name'] = '小学';
            }elseif($v['jd'] == 31){
                $v['name'] = '初中';
            }elseif($v['jd'] == 34){
                $v['name'] = '高中';
            }
            $b[] = $v;
        }
        return $b;
    }

    /**
     * 根据学校id和学段获取级部信息
     */
    function getGrade(){
        $sid = (int)$_POST['sid'];
        $xd = (int)$_POST['xd'];
//        $sid = 1;
//        $xd = 34;
        $param['schoolid'] = (string)$sid;
        $param['xd'] = (string)$xd;
        return get_webService('Usch006',$param);
    }

    /*
     *根据级部id获取班级列表
     */

    public function getClasses(){
        $jbid = (int)$_POST['jbid'];
//        $jbid =5;
        $param['jbid'] = (string)$jbid;
        return get_webService('Usch013',$param);
    }

    /**
     * 根据级部id获取科目列表
     */
    public function getCourse(){
        $jbid = (int)$_POST['jbid'];
//        $jbid =6;
        $param['jbid'] = (string)$jbid;
        return get_webService('Usch014',$param);
    }


    /**
     * 通过老师id获取
     */
    function getOwnClasses(){
        $uid = (int)$_POST['uc_id'];
        $param = array();
        $param['uid'] = (string)$uid;
        //通过老师id获取该老师所教班级id和班级名称列表
        return get_webService('Utch009',$param);
    }

    /**
     *+---------------------------------------------------
     *getStudents——通过班级id获取学生信息
     *+---------------------------------------------------
     * @return Ambigous
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function getStudents(){
        $cid = (int)$_REQUEST['cid'];
//        $cid = 1;
        if(!$cid)exit;
        return get_webService('Ustu004',array('classid'=>(string)$cid));
    }


    /*
     * 老师端接口
     * 参数:uc_id 老师在Ucenter中的id POST方式
     * 返回:老师所教班级以及班内学生列表
     * 徐程亮
     */
    public function teacher(){
     return uc_get_space_teacher($_POST['uc_id']);
//        $arr = array();
//        foreach($a as $v){
//            $arr[$v['bjmc']][] = uc_get_students_by_classid($v['bjid']) ? uc_get_students_by_classid($v['bjid']):array() ;
//        }
//        return $arr;
    }

    /*
     * 家长端接口
     * 参数:uc_id 家长Ucenter中的id POST方式
     * 返回:对应学生的信息
     * 徐程亮
     */
    public function parentGetStudents(){
        $uid = (int)$_POST['identityid'];
//        $uid = 16;
        $param = array();
        $param['parentid'] = (string)$uid;
        //通过家长id获取对应学生信息
        return get_webService('Ustu003',$param);
    }

    /*
     * 获取学生详细信息接口
     * 参数:uc_id 家长Ucenter中的id POST方式
     * 返回:学生的详细信息
     * 徐程亮
     */
    public function student(){
        $data = uc_get_studentDetail_by_sid($_POST['uc_id']);
        $arr = array();
        foreach($data as $v){
            foreach ($v as $k=>$vv) {
                if($vv==null){
                    $vv='';
                }
                $arr[$k] = $vv;
            }
        }
        return $arr;
    }

/*
 * 根据评论id返回评论
 */
    function getComments(){
        $page = (int)$_POST['page'];
        $limit = (int)$_POST['limit'];
        $page = $page ? $page : 1;
        $limit = $limit ? $limit :20;
        $start = ($page-1)*$limit;

        $commentId = (int)$_REQUEST['commentId'];
        $sql = "select c.id comment_id,c.comment content,FROM_UNIXTIME(c.cTime,'%Y-%m-%e %H:%k:%i') ctime,u.uname,u.uid
                from ts_comment c
                left join ts_user u on u.uid=c.uid
                where c.id=$commentId
                order by c.cTime desc
                limit $start,$limit
                ";
        $m = new Model();
        $comments = $m->query($sql);
        $arr = array();
        foreach($comments as $v){
            $v['content'] = clearText($v['content']);
            $arr[] = $v;
        }
        return $arr;
    }

    /**
     * 修改评论
     */

    public function updateComment(){
        $commentId = (int)$_POST['commentId'];
        $data['comment'] = t($_POST['comment']);
        if(M('comment')->where('id='.$commentId)->save($data)){
            return 1;
        }else{
            return 0;
        }
    }

    /*
      * 评论文章
       */
    public function comment() {
        $_POST['with_new_weibo']		= intval($_POST['with_new_weibo']);
        $_POST['appid']					= intval($_POST['appid']);
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
        $_POST['comment_ip'] = get_client_ip();
//        $app_alias	= getAppAlias($_POST['type']);

        // 被回复内容
        $former_comment = array();
        if ( $_POST['to_id'] > 0 )
            $former_comment = M('comment')->where("`id`='{$_POST['to_id']}'")->find();

        // 插入新数据
        $m = M('ucenter_user_link')->where('uc_uid='.$_POST['author_uid'])->field('uid')->find();
        $map['type']	= $_POST['type']; // 应用名
        $map['appid']	= $_POST['appid'];
        $map['appuid']	= $m['uid'];
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
//        return $map;
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
            return 1;
        }else{
            return 0;
        }
    }
    // 评论成功后, 回调处理, 增加评论计数
    private function __doAddCallBack($appid, $table,$id_field = 'id', $comment_count_field = 'commentCount') {
        return $table ? M($table)->setInc($comment_count_field, "`$id_field`='$appid'") : false;
    }

}

?>