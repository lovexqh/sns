<?php
require_once(SITE_PATH.'/apps/weibo/Lib/Model/WeiboModel.class.php');
class OperateModel extends WeiboModel{

    var $tableName = 'weibo';

   function getSavePath(){
        $savePath = SITE_PATH.'/data/uploads/miniblog';
        if( !file_exists( $savePath ) ) mk_dir( $savePath  );
        return $savePath;
    }

    //删除一条微广播
    function deleteMini($id,$uid){
		if($GLOBALS['ts']['isSystemAdmin']){
			$where = "weibo_id=$id";
		}else{
			$where = "weibo_id=$id AND uid=$uid";
		}
    	if( $info = $this->where($where)->find()){
    	    $opt = $this->setField('isdel',1,'weibo_id='.$info['weibo_id'].' AND isdel=0');
    		if($info['isdel'] == 0 && $opt){
	    		//关联操作
	    		if( $info['transpond_id'] ){
	    			$this->setDec('transpond','weibo_id='.$info['transpond_id']);
	    		}
	    		//同时删除@用户的微广播数据
	    		D('Atme','weibo')->where('weibo_id='.$info['weibo_id'])->delete();

	    		//同时删除收藏
	    		D('Favorite','weibo')->where('weibo_id='.$info['weibo_id'])->delete();

	    		//同时删除评论
	    		D('Comment','weibo')->setField('isdel',1,'weibo_id='.$info['weibo_id']);

	    		//删除附件
                D('WeiboAttach','weibo')->del($uid,$id);

                $this->_removeWeiboCache($id);

				//同时更新话题微广播数
//	    		preg_match_all('/#(.*)#/isU',$info['content'],$topic_arr);
				D('Topic', 'weibo')->deleteWeiboJoinTopic($info['content'], $info['weibo_id']);

			    //修改登录用户缓存信息--修改微广播数目
			    $userLoginInfo = S('S_userInfo_'.$uid);
			    if(!empty($userLoginInfo)) {
			    	$userLoginInfo['miniNum'] = strval($userLoginInfo['miniNum'] - 1);
			    	S('S_userInfo_'.$uid, $userLoginInfo);
			    }
    		}elseif($info['isdel'] == 1 && $this->where('weibo_id='.$info['weibo_id'].' AND isdel=1')->delete()){
	    		//同时彻底删除评论
	    		D('Comment','weibo')->where('weibo_id='.$info['weibo_id'])->delete();
    		}else{
    			return false;
    		}
    		return true;
    	}else{
    		return false;
    	}
    }

	//搜索话题
    function doSearch($key, $type='')
    {
    	global $ts;
    	//$key = addslashes(t($key));
    	if (!$key) {
    		$list['count'] = 0;
    		return $list;
    	}
    	switch ($type){
    		case '':
    			$list = $this->field('weibo_id')->where("content LIKE '%{$key}%' AND isdel=0" . $map)->order('weibo_id DESC')->findpage(20);
    			break;

    		case 'location':
    			$user = M('user')->where('uid='.$ts['user']['uid'])->field('province')->find();
    			$list = $this->field('weibo_id')->where("uid IN (SELECT uid FROM {$this->tablePrefix}user WHERE province=".$user['province'].") AND content LIKE '%{$key}%' AND isdel=0" . $map)->order('weibo_id DESC')->findpage(20);
    			break;

    		case 'follow':
    			$list = $this->table("{$this->tablePrefix}weibo AS w,(SELECT fid FROM {$this->tablePrefix}weibo_follow WHERE uid={$ts['user']['uid']} AND type=0) AS f")
    						 ->where("w.uid=f.fid AND w.content LIKE '%{$key}%' AND w.isdel=0" . $denounce_ids? " AND w.weibo_id NOT IN ({$denounce_ids}) " : '')
    						 ->order('weibo_id DESC')
    						 ->field('w.weibo_id as weibo_id')
    						 ->findpage(20);
    			break;

    		case 'original':
    			$list = $this->field('weibo_id')->where("transpond_id=0 AND content LIKE '%{$key}%' AND isdel=0" . $map)->order('weibo_id DESC')->findpage(20);
    			break;

    		case 'image':
    			$list = $this->field('weibo_id')->where("type=1 AND content LIKE '%{$key}%' AND isdel=0" . $map)->order('weibo_id DESC')->findpage(20);
    			break;

    		case 'music':
    			$list = $this->field('weibo_id')->where("type=4 AND content LIKE '%{$key}%' AND isdel=0" . $map)->order('weibo_id DESC')->findpage(20);
    			break;

    		case 'video':
    			$list = $this->field('weibo_id')->where("type=3 AND content LIKE '%{$key}%' AND isdel=0" . $map)->order('weibo_id DESC')->findpage(20);
    			break;

    		case 'file':
    			$list = $this->field('weibo_id')->where("type=5 AND content LIKE '%{$key}%' AND isdel=0" . $map)->order('weibo_id DESC')->findpage(20);
    			break;
    		case 'keyword'://key查询条件直接传进来做查询条件的一部分
    		    $list = $this->field('weibo_id')->where($key." and isdel=0")->order('weibo_id DESC')->findpage(20);
    		    break;
    		default:
    			$list = $this->field('weibo_id')->where("type='{$type}' AND content LIKE '%{$key}%' AND isdel=0" . $map)->order('weibo_id DESC')->findpage(20);
    	}
    	$weibo_id_list = getSubByKey($list['data'],'weibo_id');
    	$list['data'] = $this->getWeiboDetail($weibo_id_list);

    	/*
    	 * 缓存用户信息, 被转发微广播的详情
    	 */
    	$ids = getSubBeKeyArray($list['data'], 'transpond_id,uid');
    	$transpond_list = $this->getWeiboDetail($ids['transpond_id']);
    	// 本页的用户IDs = 作者IDs + 被转发微广播的作者IDs
    	$ids['uid'] = array_merge($ids['uid'], getSubByKey($transpond_list, 'uid'));
    	D('User', 'home')->setUserObjectCache($ids['uid']);

    	foreach($list['data'] as $key=>$value){
    		$list['data'][$key] = $this->getOne('',$value);
    	}
    	return $list;
    }

    //搜索话题--TODO
    public function doSearchWithTopic($key, $type) {
    	global $ts;
    	//$key = addslashes(t($key));
    	if (!$key) {
    		$list['count'] = 0;
    		return $list;
    	}
    	//获取话题ID
    	$topicId = D('Topic', 'weibo')->getTopicId($key);

    	$weiboTopicLinkModel = M('weibo_topic_link');
    	switch ($type){
    		case '':
				$list = $weiboTopicLinkModel->where("topic_id = {$topicId}".$map)->order('weibo_id DESC')->findPage(20);
    			break;

    		case 'original':
				$list = $weiboTopicLinkModel->where("topic_id = {$topicId} AND transpond_id = 0".$map)->order('weibo_id DESC')->findPage(20);
    			break;

    		case 'image':
				$list = $weiboTopicLinkModel->where("topic_id = {$topicId} AND type = '1'".$map)->order('weibo_id DESC')->findPage(20);
    			break;

    		case 'music':
				$list = $weiboTopicLinkModel->where("topic_id = {$topicId} AND type = '4'".$map)->order('weibo_id DESC')->findPage(20);
    			break;

    		case 'video':
				$list = $weiboTopicLinkModel->where("topic_id = {$topicId} AND type = '3'".$map)->order('weibo_id DESC')->findPage(20);
    			break;

    		case 'file':
				$list = $weiboTopicLinkModel->where("topic_id = {$topicId} AND type = '5'".$map)->order('weibo_id DESC')->findPage(20);
    			break;

    		default:
				$list = $weiboTopicLinkModel->where("topic_id = {$topicId} AND type = '{$type}'".$map)->order('weibo_id DESC')->findPage(20);
    	}

		$weiboIdArr = getSubByKey($list['data'], 'weibo_id');
		$listMap['weibo_id'] = array('IN', $weiboIdArr);
		$data = $this->field('weibo_id')->where($listMap)->order('weibo_id DESC')->findAll();

		$weibo_id_list = getSubByKey($data,'weibo_id');
		$list['data'] = $this->getWeiboDetail($weibo_id_list);

    	/*
    	 * 缓存用户信息, 被转发微广播的详情
    	 */
    	$ids = getSubBeKeyArray($list['data'], 'transpond_id,uid');
    	$transpond_list = $this->getWeiboDetail($ids['transpond_id']);
    	// 本页的用户IDs = 作者IDs + 被转发微广播的作者IDs
    	$ids['uid'] = array_merge($ids['uid'], getSubByKey($transpond_list, 'uid'));
    	D('User', 'home')->setUserObjectCache($ids['uid']);

    	foreach($list['data'] as $key=>$value){
    		$list['data'][$key] = $this->getOne('',$value);
    	}
    	return $list;
    }

    /**
     * 首页获取正在发生的微广播 2分钟缓存
     * @return unknown
     */
    function getLastWeibo(){
    	if(($cache = S('Cache_LastWeibo')) === false){
    		S('Cache_LastWeibo_t',time()); //缓存未设置 先设置缓存设定时间
    	}else{
    		if(($cacheSetTime =  S('Cache_LastWeibo_t')) === false || $cacheSetTime+120 <= time()){
    			S('Cache_LastWeibo_t',time()); //缓存未设置 先设置缓存设定时间
    		}else{
    			return $cache;
    		}
    	}
    	$data= $this->doSearchTopic('`transpond_id` = 0 AND `type` = 0', 'weibo_id DESC', 0);
		S('Cache_LastWeibo',$data['data']);
		return $data['data'];
    }

	//Topic搜索
	function doSearchTopic($map,$order,$uid,$limit = true) {
		if (!is_string($map))
			return false;

		$map .= trim($map)?' AND isdel = 0':'isdel = 0';
    	$maskHotTopic = model('Xdata')->get('weibo:maskHotTopic');
		if( $maskHotTopic ){
			$arr_MaskHotTopic = explode('|', trim($maskHotTopic,'|'));
			foreach($arr_MaskHotTopic as $v){
				$map .= " AND content NOT LIKE '%#{$v}#%' ";
			}
		}
		if($limit){
		    $list = $this->field('weibo_id')->where($map)->order($order)->findPage(20);
		    $weibo_id_list = getSubByKey($list['data'],'weibo_id');
		    $list['data'] = $this->getWeiboDetail($weibo_id_list);
		}else{
		    $result = $this->field('weibo_id')->where($map)->order($order)->find();
		    $list['data'] = $this->getWeiboDetail($result);
		    if(empty($result)) return false;
		}

		/*
    	 * 缓存被转发微广播的详情, 作者信息, 被转发微广播的作者信息
    	 */
    	$ids = getSubBeKeyArray($list['data'], 'weibo_id,transpond_id,uid');
    	$transpond_list = $this->getWeiboDetail($ids['transpond_id']);
    	// 本页的用户IDs = 作者IDs + 被转发微广播的作者IDs
    	$ids['uid'] = array_merge($ids['uid'], getSubByKey($transpond_list, 'uid'));
    	D('User', 'home')->setUserObjectCache($ids['uid']);

		$weibo_ids = getSubByKey($list['data'], 'weibo_id');
		foreach($list['data'] as $key=>$value){
			$value['is_favorited'] = D('Favorite','weibo')->isFavorited($value['weibo_id'], $uid, $weibo_ids);
			$list['data'][$key] = $this->getOne('', $value);
		}
		return $list;
	}

	//获取未取出来的新微广播条数
	function loadMore($uid,$lastId,$type=null,$follow_gid=null,$simple=0){
		$this->simple = $simple;
	    return $this->__optToNew( $uid,$lastId,$type,$follow_gid,20,true);
	}
    //获取班级主页未取出来的新微广播条数
	function ClassloadMore($type=null,$simple=4,$classid,$lastId,$follow_gid=null){
		$this->simple = $simple;
	    return $this->__ClassoptToNew( $type,$simple,$classid,$lastId,$follow_gid,10,true);
	}
	
	//获取未取出来的新微广播条数
	function countNew($uid,$lastId,$type=null,$follow_gid=null){
	    $lastId = intval($lastId);
	    if(empty($lastId)) return 0;
        return $this->__optToNew( $uid,$lastId,$type,$follow_gid );
	}

	function loadNew($uid,$lastId,$limit,$type=null,$follow_gid=null){
	    return $this->__optToNew( $uid,$lastId,$type,$follow_gid,$limit);
	}

	private function __optToNew($uid,$lastId,$type='index',$follow_gid=null,$limit = 0,$since = false){
		if (isset($this->simple)){
			$simple = $this->simple;
			unset($this->simple);
		}
		if(!empty($type) && $type >0){
	        $type_str = " and type=".intval($type);
		}

		if($type==='original'){
			$type_str = " and transpond_id=0";
	    }

		if($since){
	        if($lastId >0){
	            $weiboId = "weibo_id<{$lastId} and";
	        }
	    }else{
	        if($lastId >0){
	            $weiboId = "weibo_id>{$lastId} and";
	        }
	    }

	    $map="{$weiboId} isdel=0 {$type_str}";

		if($uid>0){
			$followCount = D('Follow','weibo')->getUserFollowCount($uid);
			if($followCount){
				if(!empty($follow_gid)){
					$fids = D('FollowGroup','weibo')->getUsersByGroup($uid,$follow_gid);
					$map.=' AND uid IN ('.implode(',',$fids).')';
				}else{
					$map.=" AND ( uid IN (SELECT fid FROM {$this->tablePrefix}weibo_follow WHERE uid=$uid AND type=0) OR uid={$uid} )";
				}
			}else{//无关注时.数据为空.
				$map.=' AND uid = '.$uid;
			}
		}

	    if($limit){
	        $list = $this->field('weibo_id')->where($map)->order('weibo_id DESC')->limit($limit)->findAll();
			unset($map);
	        //取出微广播的实际数据
	        $weibo_id_list = getSubByKey($list,'weibo_id');
	        $data= $this->getWeiboDetail($weibo_id_list,$simple);
	        $result = array();
	        foreach( $data as $key=>$value){
	            $result[] = $this->getOne('',$value);
	        }
	        $list['data'] = $result;
	    }else{
			$list = $this->where($map)->count();
	    }
	    return $list;
	}
   private function __ClassoptToNew($type='MoveState',$simple=4,$classid,$lastId,$follow_gid=null,$limit = 0,$since = false){
		if (isset($this->simple)){
			$simple = $this->simple;
			unset($this->simple);
		}
		/*if(!empty($type) && $type >0){
	        $type_str = " and type=".intval($type);
		}

		if($type==='original'){
			$type_str = " and transpond_id=0";
	    }*/

		//if($since){
	        if($lastId >0){
	            $weiboId = "weibo_id<{$lastId} and";
	      }
	  /*}else{
	        if($lastId >0){
	            $weiboId = "weibo_id>{$lastId} and";
	        }
	    }*/
        //$map="{$weiboId} isdel=0 {$type_str}";
	    $map="{$weiboId} isdel=0";
        if($classid){
			if(UC_SYNC){
				$userids=$this->__paramUserClassGroup($classid);
				$useridss=array();
				foreach($userids as $key=>$obj)
				{
					$useridss[$key]=$obj['fuid'];
				}
				$uids=implode(",",$useridss);
				//获取我的同学全部数据
				$map.=' AND uid IN ('.$uids.')';
			}
		}
		//$list = $this->field('weibo_id')->where($map)->order('weibo_id DESC')->limit($row)->findAll();
	    if($limit){
	        $list = $this->field('weibo_id')->where($map)->order('weibo_id DESC')->limit($limit)->findAll();
			unset($map);
	        //取出微广播的实际数据
	        $weibo_id_list = getSubByKey($list,'weibo_id');
	        $data= $this->getWeiboDetail($weibo_id_list,$simple);
	        $result = array();
	        foreach( $data as $key=>$value){
	            $result[] = $this->getOne('',$value);
	        }
	        $list['data'] = $result;
	    }else{
			$list = $this->where($map)->count();
	    }
		//var_dump($list);
	    return $list;
	}
    //获取首页微广播列表
    function getHomeList($uid, $type='index', $since, $row=10, $gid='',$simple=0) {
    	$row = $row?$row:10;
		if($type=='original'){  //原创
			$map = 'transpond_id=0 AND isdel=0';
    		if($since){
    			$map.=" AND weibo_id<$since";
    		}
    	}else if($type=='index' || $type==''){   // 默认全显
    	    if ($since) {
    			$map="weibo_id < $since AND isdel=0";
    		} else {
    			$map = '1=1 AND isdel=0';
    		}
    	}else {
    		if ($since) {
    			$map="weibo_id < $since AND isdel=0";
    		}else {
    			$map = '1=1 AND isdel=0';
    		}
			$map.=" AND type=".$type;
    	}

		if($uid>0){
			$followCount = D('Follow','weibo')->getUserFollowCount($uid);
			if ($followCount) { // 有关注时, 展示关注的用户的微广播
				if (is_numeric($gid)) {
					$fids = D('FollowGroup','weibo')->getUsersByGroup($uid,$gid);
					$map.=' AND uid IN ('.implode(',',$fids).')';
				}else if ("class"==$gid){
					if(UC_SYNC){
						if(!isset($_SESSION['userInfo']['email'])){
				    		return false;
				    	}
				    	$students = uc_get_classuser($_SESSION['userInfo']['email']);
				        //获取我的同学全部数据
				        $mails = array();
						foreach($students as $stu){
							array_push($mails,$stu['email']);
						}
						$where['email'] = array('in',$mails);
						$result = D("User")->field('uid')->where($where)->findAll();
						$fids = array();
						foreach($result as $v){
					        $fids[] = $v['uid'];
					    }
					    $fids[]=$_SESSION['mid'];

						$map.=' AND uid IN ('.implode(',',$fids).')';

					}
				}else{
					$map.=" AND ( uid IN (SELECT fid FROM {$this->tablePrefix}weibo_follow WHERE uid=$uid AND type=0) OR uid={$uid})";
				}
			}else{//无关注时.数据为空.
				$map.=' AND uid = '.$uid;
			}
		}

    	$list = $this->field('weibo_id')->where($map)->order('weibo_id DESC')->limit($row)->findAll();
		unset($map);
        $return['data'] = $this->_paramResultData($list,$uid,false,$simple);

        unset( $result, $list);
        return $return;
    }
	
	//获取班级空间微广播列表
    function getClasspaceList($classid, $type='MoveState', $since, $row=10, $gid='',$simple=0) {
    	$row = $row?$row:10;
		if($type=='index' || $type==''){   // 默认全显
    	    if ($since) {
    			$map="weibo_id < $since AND isdel=0";
    		} else {
    			$map = '1=1 AND isdel=0';
    		}
    	}
		if($classid){
			if(UC_SYNC){
				$userids=$this->__paramUserClassGroup($classid);
				$useridss=array();
				foreach($userids as $key=>$obj)
				{
					$useridss[$key]=$obj['fuid'];
				}
				$uids=implode(",",$useridss);
				//获取我的同学全部数据
				$map.=' AND uid IN ('.$uids.')';
			}
		}
    	$list = $this->field('weibo_id')->where($map)->order('weibo_id DESC')->limit($row)->findAll();
		unset($map);
        $return['data'] = $this->_paramResultData($list,$uid,false,$simple);
        unset( $result, $list);
        return $return;
    }
	//--获取班级信息
	private function __paramUserClassGroup($classid){
        $userlist=uc_student_get_id($classid);
		$userids=array();
		$n=0;
		foreach($userlist as $key=>$obj)
		 {
			  if($obj['uid'])
			  {
				  $userids[$n]=$obj['uid'];
				  $n++;
			  }
		 }
		$uids=implode(",",$userids);
		$db_prefix  =  C('DB_PREFIX');
		$follow=M('')->field('snsuser.uid AS fuid')
				   ->table("{$db_prefix}user AS snsuser LEFT JOIN {$db_prefix}ucenter_user_link AS uc_user ON snsuser.uid=uc_user.uid")
				   ->where("uc_user.uc_uid in ($uids)")
				   ->order('snsuser.uid ASC')
				   ->findall();
        return $follow;
    }
	
	public function getSpaceList($uid, $type) {
    	if ($type == 'original') { // 原创
    		$map = 'transpond_id=0 AND uid='.$uid.' AND isdel=0';
    	} else if ($type == '') { // 默认全显
    		$map = "uid=$uid AND isdel=0";
    	} else {    //其它类型
    		$map = "uid=$uid AND type=".$type.' AND isdel=0';
    	}
        $list = $this->field('weibo_id')->where($map)->order('weibo_id DESC')->findPage(20);
        return $this->_paramResultData($list,$uid,true);
    }

    //首页滚动新微广播
    function getIndex($num=10){
    	$list = $this->where("transpond_id=0 AND type=0 AND isdel=0")->limit($num)->order('ctime DESC')->findall();
    	return $list;
    }

    //提到我的
    function getAtme($uid,$api) {
    	// 手动查询总数, 以提高效率
    	$count_sql = "SELECT count(*) AS count FROM {$this->tablePrefix}weibo AS w INNER JOIN {$this->tablePrefix}weibo_atme AS a ON w.weibo_id = a.weibo_id
    				  WHERE a.uid = {$uid}
    				  AND a.uid NOT IN ( SELECT b.fid FROM {$this->tablePrefix}user_blacklist AS b WHERE b.uid = {$uid} )";
    	$count = $this->query($count_sql);
    	$count = $count[0]['count'];

		//查询进行优化--如果IN或NOT IN中的数据为NULL，查询速度将很慢
//    	$list = $this->where("isdel=0 AND weibo_id IN (SELECT weibo_id FROM {$this->tablePrefix}weibo_atme WHERE uid=$uid) AND uid NOT IN (SELECT fid FROM {$this->tablePrefix}user_blacklist WHERE uid=$uid) and uid != $uid")
//    				 ->order('ctime DESC')
//    				 ->findPage(10, $count);

		//查询进行优化
		$list = M()->Table("`{$this->tablePrefix}weibo` AS w LEFT JOIN `{$this->tablePrefix}weibo_atme` AS a ON w.weibo_id = a.weibo_id LEFT JOIN `{$this->tablePrefix}user_blacklist` AS b ON w.uid = b.fid AND b.uid = {$uid}")
				   ->field('w.*')
				   ->where("w.isdel = 0 AND w.uid != {$uid} AND a.uid = {$uid} AND b.fid IS NULL")
				   ->order('w.ctime DESC')
				   ->findPage(10, $count);

    	/*
    	 * 缓存被转发微广播的详情, 作者信息, 被转发微广播的作者信息
    	 */
    	$ids = getSubBeKeyArray($list['data'], 'weibo_id,transpond_id,uid');
    	$transpond_list = $this->getWeiboDetail($ids['transpond_id'],3);
    	// 本页的用户IDs = 作者IDs + 被转发微广播的作者IDs
    	$ids['uid'] = array_merge($ids['uid'], getSubByKey($transpond_list, 'uid'));
    	D('User', 'home')->setUserObjectCache($ids['uid']);

    	foreach ($list['data'] as $key => $value) {
        	$value['is_favorited'] = D('Favorite','weibo')->isFavorited($value['weibo_id'], $uid, $ids['weibo_id']);
 			$list['data'][$key] = $this->getOneLocation('',$value);
        }
        return $list;
    }

    //我收藏的
    function getCollection($uid,$api){
    	/*
    	$list = $this->where("isdel=0 AND weibo_id IN (SELECT weibo_id FROM {$this->tablePrefix}weibo_favorite WHERE uid=$uid)")->order('weibo_id DESC')->findPage(10);
    	*/

    	$list = M('weibo_favorite')->where("`uid`='{$uid}'")->order('`weibo_id` DESC')->findPage(10);
    	$favorite_ids = getSubByKey($list['data'], 'weibo_id');
    	$map['weibo_id'] = array('in', $favorite_ids);
    	$map['isdel']	 = '0';
    	$list['data'] = $this->where($map)->order('`weibo_id` DESC')->limit(count($favorite_ids))->findAll();

    	/*
    	 * 缓存被转发微广播的详情, 作者信息, 被转发微广播的作者信息
    	 */
    	$ids = getSubBeKeyArray($list['data'], 'weibo_id,transpond_id,uid');
    	$transpond_list = $this->getWeiboDetail($ids['transpond_id'],3);
    	// 本页的用户IDs = 作者IDs + 被转发微广播的作者IDs
    	$ids['uid'] = array_merge($ids['uid'], getSubByKey($transpond_list, 'uid'));
    	D('User', 'home')->setUserObjectCache($ids['uid']);

    	foreach( $list['data'] as $key=>$value){
        	$value['is_favorited'] = '1';
        	$value['simple'] = 3;
 			$list['data'][$key] = $this->getOneLocation('',$value);
        }
        return $list;
    }

    //获取手机
    function getMobile($pre,$next,$count=20,$page=1){
    		if($pre){
    			$list = $this->query("SELECT a.* FROM {$this->tablePrefix}weibo a LEFT JOIN {$this->tablePrefix}weibo b ON a.transpond_id = b.weibo_id WHERE ( b.type=0 OR b.type=1 ) AND b.is_feed=0 AND a.weibo_id>$pre UNION SELECT * FROM {$this->tablePrefix}weibo WHERE transpond_id=0 AND is_feed=0 AND weibo_id>$pre AND ( type =0 OR type=1) ORDER BY weibo_id ASC  LIMIT $count ");
    			$list = array_reverse($list);
    		}elseif($next){
    			$list = $this->query("SELECT a.* FROM {$this->tablePrefix}weibo a LEFT JOIN {$this->tablePrefix}weibo b ON a.transpond_id = b.weibo_id WHERE ( b.type=0 OR b.type=1 ) AND b.is_feed=0 AND a.weibo_id<$next UNION SELECT * FROM {$this->tablePrefix}weibo WHERE transpond_id=0 AND is_feed=0 AND weibo_id<$next AND ( type =0 OR type=1) ORDER BY weibo_id DESC  LIMIT $count ");
    		}else{
    			$list = $this->query("SELECT a.* FROM {$this->tablePrefix}weibo a LEFT JOIN {$this->tablePrefix}weibo b ON a.transpond_id = b.weibo_id WHERE ( b.type=0 OR b.type=1 ) AND b.is_feed=0 UNION SELECT * FROM {$this->tablePrefix}weibo WHERE transpond_id=0 AND is_feed=0 AND ( type =0 OR type=1)  ORDER BY weibo_id DESC  LIMIT $count ");
    		}

    	    foreach($list as $k=>$v){
				$result[$k] = $this->getOneApi('', $v);
	    	}

    	return $result;
    }

    private function _paramResultData($list,$uid,$page=false,$simple=0){
        if($page){
            //取出微广播的实际数据
            $weibo_id_list = getSubByKey($list['data'],'weibo_id');
            $data = $this->getWeiboDetail($weibo_id_list,$simple);
        }else{
            //取出微广播的实际数据
            $weibo_id_list = getSubByKey($list,'weibo_id');
            $data = $this->getWeiboDetail($weibo_id_list,$simple);
        }
        
        /*
         * 缓存被转发微广播的详情, 被转发微广播的作者信息
        */
        $ids = getSubBeKeyArray($data, 'weibo_id,transpond_id');
        $transpond_list = $this->getWeiboDetail($ids['transpond_id']);
        $ids['uid'] = getSubByKey($transpond_list, 'uid');
        D('User', 'home')->setUserObjectCache($ids['uid']);

        $weibo_ids = getSubByKey($data, 'weibo_id');
        
        foreach( $data as $key=>$value){
            $value['is_favorited'] = D('Favorite','weibo')->isFavorited($value['weibo_id'], $uid, $weibo_ids);
            $value['simple'] = $simple;// new add 2012-12-27
            $data[$key] = $this->getOne('',$value);
        }

        if($page){
            $list['data'] = $data;
        }else{
            $list = $data;
        }
        return $list;
    }

    /**
     * 获取相关动态信息
     */
    public function getWeiboDyna($uid,$gid=0,$limit = '10',$order = 'weibo_id DESC'){
		$map = "`transpond_id` = 0 AND `type` = 0 AND isdel = 0";
		$followCount = D('Follow','weibo')->getUserFollowCount($uid);
		if($followCount){
			if (is_numeric($gid)) {
				$fids = D('FollowGroup','weibo')->getUsersByGroup($uid,$gid);
				$fids[] = $uid;
				$map.=' AND uid IN ('.implode(',',$fids).')';
			}
		}

    	$list = $this->field('weibo_id')->where($map)->order($order)->limit($limit)->select();
	    $weibo_id_list = getSubByKey($list,'weibo_id');
	    $result = $this->getWeiboDetail($weibo_id_list);

		return $result;
    }
}