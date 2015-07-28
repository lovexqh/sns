<?php
class FollowModel extends Model{
	var $tableName = 'weibo_follow';

	public function getNowFollowingSql($uid)
	{
		return "SELECT `fid` FROM {$this->tablePrefix}weibo_follow WHERE `uid` = '{$uid}' AND `type` = 0";
	}

	public function getUserFollowCount($uid){
		$userInfo = S('S_userInfo_'.$uid);
		$followCount = $userInfo['following'];
		if(empty($followCount) && $followCount !== '0') {
	    	$followCount = $this->where('`uid` = '.$uid.' AND `type` = 0')->count();
		}

		return $followCount;
	}

	/**
	 * 添加关注 (关注用户 / 关注话题)
	 *
	 * @param int $mid  发起操作的用户ID
	 * @param int $fid  被关注的用户ID 或 被关注的话题ID
	 * @param int $type 0:关注用户(默认) 1:关注话题
	 * @return null:参数错误 00:禁止关注(即位于黑名单) 10:不能关注自己 11:已关注 12:关注成功(且为单向关注) 13:关注成功(且为互粉)
	 */
	public function dofollow($mid, $fid, $type = 0)
	{
		if ($mid <= 0 || $fid <= 0 || !in_array($type, array('0', '1')))
			return ; // 参数错误

		if ($type == 0 && $mid == $fid)
			return '10'; // 不能关注自己

		if ($type == 0 && !D('User', 'home')->getUserByIdentifier($fid, 'uid'))
			return ; // 参数错误: 被关注的用户不存在

		$privacy = D('UserPrivacy','home')->getPrivacy($mid, $fid);
		if (!$privacy['follow'])
			return '00'; // 禁止关注(位于黑名单中)

		$map['uid']  = $mid;
		$map['fid']  = $fid;
		$map['type'] = $type;
		if (0 == $this->where($map)->count()) { // 未关注
			$this->add($map);
			unset($map);

			if ($type == 0) { // 关注用户
				// 关注记录 - 漫游使用
				$map['uid']	     = $mid;
				$map['fuid']	 = $fid;
				$map['action']	 = 'add';
				$map['dateline'] = time();
				M('myop_friendlog')->add($map);

			    //修改登录用户缓存信息--关注数
			    $userLoginInfoMid = S('S_userInfo_'.$mid);
			    if(!empty($userLoginInfoMid)) {
			    	$userLoginInfoMid['following'] = strval($userLoginInfoMid['following'] + 1);
			    	S('S_userInfo_'.$mid, $userLoginInfoMid);
			    }

				//修改登录用户缓存信息--被关注数
			    $userLoginInfoFid = S('S_userInfo_'.$fid);
			    if(!empty($userLoginInfoFid)) {
			    	$userLoginInfoFid['follower'] = strval($userLoginInfoFid['follower'] + 1);
			    	S('S_userInfo_'.$fid, $userLoginInfoFid);
			    }

				// 通知和动态
				X('Notify')->send($fid, 'weibo_follow', '', $mid);
				X('Feed')->put('weibo_follow', array('fid'=>$fid), $mid);
			} else if ($type == 1) { // 关注话题
				// 重置缓存
				$this->unsetUserTopicList($mid);
			}

			if (0 == $this->where("uid={$fid} AND fid={$mid} AND type={$type}")->count()) {
				return '12'; // 关注成功(单向关注)
			} else {
				return '13'; // 关注成功(互粉)
			}
		} else {
			return '11'; // 已关注过
		}
	}

	/**
	 * 取消关注 (关注用户 / 关注话题)
	 *
	 * @param int $mid  发起操作的用户ID
	 * @param int $fid  被取消关注的用户ID 或 被取消关注的话题ID
	 * @param int $type 0:取消关注用户(默认) 1:取消关注话题
	 * @return 00:取消失败 01:取消成功
	 */
	public function unfollow($mid, $fid, $type = 0)
	{
		$map['uid']  = $mid;
		$map['fid']  = $fid;
		$map['type'] = $type;

		if ($this->where($map)->delete()) { // 取消成功
			if ($map['type'] == 0) { // 取消关注用户时
				// 删除关注分组的记录
				$follow_id = M('weibo_follow')->getField('follow_id',$map);
				M('weibo_follow_group_link')->where("follow_id={$follow_id} AND uid={$map['uid']}")->delete();

			    //修改登录用户缓存信息--取消关注
			    $userLoginInfoMid = S('S_userInfo_'.$mid);
			    if(!empty($userLoginInfoMid)) {
			    	$userLoginInfoMid['following'] = strval($userLoginInfoMid['following'] - 1);
			    	S('S_userInfo_'.$mid, $userLoginInfoMid);
			    }

			    //修改登录用户缓存信息--被关注数
			    $userLoginInfoFid = S('S_userInfo_'.$fid);
			    if(!empty($userLoginInfoFid)) {
			    	$userLoginInfoFid['follower'] = strval($userLoginInfoFid['follower'] - 1);
			    	S('S_userInfo_'.$fid, $userLoginInfoFid);
			    }

				// 关注记录 - 漫游使用
				unset($map);
				$map['uid']		 = $mid;
				$map['fuid']	 = $fid;
				$map['action']	 = 'delete';
				$map['dateline'] = time();
				M('myop_friendlog')->add($map);

			} else if ($map['type'] == 1) { // 取消关注话题时
				// 重置缓存
				$this->unsetUserTopicList($mid);
			}

			return '01'; //取消成功
		} else {
			return '00'; //取消失败
		}
	}

	/**
	 * 获取用户对话题的关注状态
	 *
	 * @param int    $mid  用户ID
	 * @param string $name 话题名称
	 * @return boolean true:关注 false:未关注
	 */
	public function getTopicState($mid, $name)
	{
		$followed_topic = $this->getTopicList($mid);
		$followed_topic = getSubByKey($followed_topic, 'name');
		return in_array($name, $followed_topic);
	}

	/**
	 * 获取用户关注的话题列表
	 *
	 * 按照Session缓存 -> 数据库的顺序查询
	 *
	 * @param int $mid 用户ID
	 */
	public function getTopicList($mid)
	{
		$cache_id = 'user_topic_list_' . $mid;
		if (!isset($_SESSION[$cache_id])) {
			$sql = "SElECT a.* FROM {$this->tablePrefix}weibo_topic a " .
					"LEFT JOIN {$this->tablePrefix}weibo_follow b ON b.fid=a.topic_id " .
					"WHERE b.uid=$mid AND b.type=1";
			$_SESSION[$cache_id] = $this->query($sql);
		}
		return $_SESSION[$cache_id];
	}
	
	/**
	 * 获取与我相关的N个用户
	 *
	 * @param boolean      $uid 		     用户ID
	 * @param int 	  	   $count            结果数 (默认:10)
	 * @param null|boolean $hide_auto_friend 是否剔除默认关注的用户 (null时使用系统默认配置)
	 * @param null|boolean $hide_no_avatar	 是否剔除无头像的用户 (null时使用系统默认配置)
	 * @return array
	 */
	public function getRelativeFollowerUser($uid = 0, $count = 10, $hide_auto_friend = null, $hide_no_avatar = null){
		$ucinfo = arrayKeyToLower($_SESSION['ucInfo']);
		if (!$ucinfo) { //如果session为空时
			return $this->getTopFollowerUser($uid, $count, $hide_auto_friend, $hide_no_avatar);
		}
		switch ($ucinfo['identitytype']){
			case '2': //当前用户为老师时获取和当前老师在同一所学校的已注册用户
				$schoolid = $ucinfo['schoolid'];
				if (empty($schoolid)) {	//如果无法从session中获取 schoolid 则进行数据库查询
					if (UC_SYNC) {
						$baseinfo = get_teacher_baseinfo_by_identityid($ucinfo['identityid']);
						$schoolid = $baseinfo['schoolid'];
					};
				}
				if (intval($schoolid)>0) {
					//获取某学校下所有注册的用户信息
					$uc_memberlist = get_members_teacher_by_schoolid($schoolid);
				}else{
					return $this->getTopFollowerUser($uid, $count, $hide_auto_friend, $hide_no_avatar);
				}
				break;
			case '4': //当前用户为家长时获取和自己孩子在同一班级的已注册家长信息
				$schoolid = $ucinfo['schoolid'];
				$classid = $ucinfo['classid'];
				if (empty($schoolid)) {	//如果无法从session中获取 schoolid 则进行数据库查询
					if (UC_SYNC) {
						$baseinfo = get_parent_baseinfo_by_identityid($ucinfo['identityid']);
						$schoolid = $baseinfo['schoolid'];
						$classid = $baseinfo['classid'];
					};
				}
				if (intval($classid)>0) {
					//获取某学校下某班级下所有注册的用户信息
					$uc_memberlist = get_members_parent_by_classid($classid);
				}else if (intval($schoolid)>0) {
					//获取某学校下所有注册的用户信息
					$uc_memberlist = get_members_parent_by_schoolid($schoolid);
				}else{
					return $this->getTopFollowerUser($uid, $count, $hide_auto_friend, $hide_no_avatar);
				}
				break;
			case '3': //当前用户为学生时，获取同一班级的已注册学生
				$schoolid = $ucinfo['schoolid'];
				$classid = $ucinfo['classid'];
				if ( empty($schoolid) || empty($classid) ) {	//如果无法从session中获取 schoolid 则进行数据库查询
					if (UC_SYNC) {
						$baseinfo = get_student_baseinfo_by_identityid($ucinfo['identityid']);
						$schoolid = $baseinfo['schoolid'];
						$classid = $baseinfo['classid'];
					};
				}
				if (intval($classid)>0) {
					//获取某学校下某班级下所有注册的用户信息
					$uc_memberlist = get_members_student_by_classid($classid,$count);
				}
				if (empty($uc_memberlist) && intval($schoolid)>0) {
					//获取某学校下所有注册的用户信息
					$uc_memberlist = get_members_student_by_schoolid($schoolid,$count);
				}else{
					return $this->getTopFollowerUser($uid, $count, $hide_auto_friend, $hide_no_avatar);
				}
				break;
			default:
				return $this->getTopFollowerUser($uid, $count, $hide_auto_friend, $hide_no_avatar);
		}
		//将结果进行前$count条过滤
		$uc_memberlist = array_slice($uc_memberlist, 0, $count);
		//获取单独uid的结果集
		$uc_uid_list = getSubByKey($uc_memberlist,'uid');
		//优化大批量查询用户，每次查询100条
		$uc_uid_list	=	array_chunk($uc_uid_list, 100);
		//初始化一个array来存储结果集
		$user_result = array();
		foreach ($uc_uid_list as $uid_list){
			$map['C.uid'] = array('in', $uid_list);
			$user_list = M()->table($this->tablePrefix . "user U")
			->field("(SELECT count(`uid`) FROM ".$this->tablePrefix ."weibo_follow WHERE fid = U.uid) as count,U.*")
			->join("RIGHT JOIN ".$this->tablePrefix ."ucenter_user_link C ON U.uid = C.uid")
			->where($map)
			->findAll();
			$user_result = array_merge($user_result,$user_list);
		}
		//如果最终的结果为空获取被关注数最多的前N个用户
		return empty($user_result) ? $this->getTopFollowerUser($uid, $count, $hide_auto_friend, $hide_no_avatar)  : $user_result;
	}

	/**
	 * 获取被关注数最多的前N个用户
	 *
	 * <p>当指定uid时, 该uid已关注的用户将被剔除. 注意: 数据量大时本操作较耗资源, 且结果集不会被缓存.</p>
	 * <p>不指定uid时, 返回全站的被关注排行榜, 该排行榜会缓存1小时.</p>
	 * <p>当$hide_no_avatar为true时, 为了保证结果集数量, 实际查询的数量是3倍的$count, 然后再顺次剔除无头像的用户,
	 * 所以最终结果集的数量可能会小于$count.</p>
	 *
	 * @param boolean      $uid 		     用户ID
	 * @param int 	  	   $count            结果数 (默认:10)
	 * @param null|boolean $hide_auto_friend 是否剔除默认关注的用户 (null时使用系统默认配置)
	 * @param null|boolean $hide_no_avatar	 是否剔除无头像的用户 (null时使用系统默认配置)
	 * @return array
	 */
	public function getTopFollowerUser($uid = 0, $count = 10, $hide_auto_friend = null, $hide_no_avatar = null)
	{
		// 未指定参数时, 加载系统配置
		$config = model('Xdata')->lget('top_follower');
		!isset($hide_auto_friend) && $hide_auto_friend = intval($config['hide_auto_friend']);
		!isset($hide_no_avatar)   && $hide_no_avatar   = intval($config['hide_no_avatar']);

		$uid       = intval($uid);
		$count 	   = intval($count);
		$limit     = 0;       // 查询的结果数
		$following = array(); // 已关注的用户
		$top_user  = array(); // 最终结果


		if ($uid > 0) {
			$following = $this->query($this->getNowFollowingSql($uid));
			$following = getSubByKey($following, 'fid');
			$following = array_merge($following, array($uid)); // 自己不出现在最终结果中
			$limit    += count($following);
		}
		$cache_id = '_weibo_top_followed_' . $count .'_'. $uid .'_'. intval($hide_auto_friend) . intval($hide_no_avatar);

		// 缓存有效时间: 1 Hour
		$expire   = 1 * 3600;$expire=0;

		if (($top_user = S($cache_id)) === false) {

			// 隐藏无头像用户时, 为了保证最后结果满足$limit, 查询时使用3倍的$limit
			$limit += $hide_no_avatar ? $count * 3 : $count;

			$where = "WHERE `type` = 0 and `fid` in (select uid from " . C ( 'DB_PREFIX' ) . "user) ";

			// 判断如果是学生，那么就筛选本班学生列表
			$_SESSION['ucInfo'] = arrayKeyTolower($_SESSION['ucInfo'],1);
			if(UC_SYNC&&$_SESSION['ucInfo']['identitytype']==3){
				$classid = $_SESSION['ucInfo']['classid'];
				if (intval($classid) == 0) { //重新获取用户的班级id
					$baseinfo = get_student_baseinfo_by_identityid($_SESSION['ucInfo']['identityid']);
					$classid = $baseinfo['classid'];
				}
				$classlist = get_members_student_by_classid($classid);
				$uids = array();
				foreach ($classlist as $user) {
					$uids[] = $user['uid'];
				}
				$_uids = M('ucenter_user_link')->field("uid")->where("FIND_IN_SET(uc_uid,'".implode(',', $uids)."')")->findAll();
				unset($uids);
				foreach ($_uids as $user) {
					$uids[] = $user['uid'];
				}
				
				if(count($uids)>0){
					$where .= "AND (FIND_IN_SET(fid,'".implode(',', $uids)."')) ";
				}
			}
			//如果没有角色
			if(empty($uids)){
				$_uids = M('ucenter_user_link')->field("uid")->findAll();
				unset($uids);
				foreach ($_uids as $user) {
					$uids[] = $user['uid'];
				}
				if(count($uids)>0){
					$where .= "AND (FIND_IN_SET(fid,'".implode(',', $uids)."')) ";
				}				 
			}

			// 隐藏默认关注的用户时，limit+默认关注用户人数，再去掉默认关注用户，这样效率更好
			if ($hide_auto_friend) { 
				$auto_friend = model('Xdata')->get('register:register_auto_friend');
			
				$auto_friend = explode(',', $auto_friend);
				//if (count($auto_friend) > 1)
				//	$where .= 'AND `fid` NOT IN ( ' . implode(',', $auto_friend) . ' )';
				$auto_friend_count = count($auto_friend);
				$limit += $auto_friend_count;
			}
			
			//$sql = "SELECT `fid` AS `uid`, count(`uid`) AS `count` FROM {$this->tablePrefix}weibo_follow " .
			//	   $where . " GROUP BY `fid` " .
			//	   "ORDER BY `count` DESC LIMIT {$limit}";
			
			$sql = "SELECT `fid` AS `uid`, count(`uid`) AS `count` FROM {$this->tablePrefix}weibo_follow " .
				   $where . " GROUP BY `fid` " .
				   "ORDER BY `count` DESC LIMIT {$limit}";
			$res = $this->query($sql);
			$res = $res ? $res : array();
			if (!empty($res)) { // 过滤
				$noPic = array();
				foreach ($res as $k => $v) {
					//剔除默认关注用户
					if ($hide_auto_friend && in_array($v['uid'], $auto_friend)) { 
						unset($res[$k]);
					//剔除已关注用户
					}elseif ($uid > 0 && in_array($v['uid'], $following)) { 
						unset($res[$k]);
					}else{
						$top_user[] = $v;
					}
				}
				unset($res);
				//剔除无头像的用户
				foreach ($top_user as $k=>$v){
					if ($hide_no_avatar && !hasUserFace($v['uid'])) { 
						$noPic[] = $v;
						unset($top_user[$k]);
					}
				}
				//如果全都是无头像的.那就补充上去
				if(empty($top_user) && !empty($noPic))
					$top_user = $noPic;
			}
			if (isset($uids)&&count($uids)>0){
				$_top_user = array();
				foreach ($top_user as $user) {
					if (in_array($user['uid'], $uids)){
						$_top_user[] = $user;
					}
				}
			}
			unset($top_user);
			$top_user = $_top_user;
			
			//截取需要的前$count个
			if(is_array($top_user))
				$top_user = array_slice($top_user, 0, $count);

			S($cache_id, $top_user, $expire);
		}

		return $top_user;
	}

	/**
	 * 重置用户关注的话题列表的缓存
	 *
	 * @param int $mid 用户ID
	 */
	public function unsetUserTopicList($mid)
	{
		$cache_id = 'user_topic_list_' . $mid;
		unset($_SESSION[$cache_id]);
	}

	//获取关注状态
	function getState( $uid , $fid , $type=0 ){
		return getFollowState( $uid,$fid);
	}

	//获取关注列表
	function getList( $uid , $operate ,$type=0 ,$gid=NULL, $limit = 10){
		$limit = intval($limit) >0 ? $limit : 10;
		global $ts;
		if( $operate == 'following' ){ //关注
			if(is_numeric($gid) && $type==0){
				if($gid == 0){
//					$list = $this->where("uid={$uid} AND type={$type} ")->order('follow_id DESC')->findpage($limit);
					$list = $this->Table("{$this->tablePrefix}{$this->tableName} AS follow LEFT JOIN {$this->tablePrefix}weibo_follow_group_link AS link ON link.follow_id = follow.follow_id")
								 ->field('follow.*')
								 ->where("follow.uid={$uid} AND follow.type={$type} AND link.follow_id IS NULL")
								 ->order('follow.uid DESC')
								 ->findPage($limit);
				}else{
					$list = $this->field('follow.*')
							 ->table("{$this->tablePrefix}weibo_follow_group_link AS link LEFT JOIN {$this->tablePrefix}{$this->tableName} AS follow ON link.follow_id=follow.follow_id AND link.uid=follow.uid")
							 ->where("follow.type={$type} AND follow.uid={$uid} AND link.follow_group_id={$gid}")
							 ->order('follow.uid DESC')
							 ->findPage($limit);
				}
			}else{
				$list = $this->where("uid=$uid AND type=$type")->order('follow_id DESC')->findpage($limit);
			}
		}else{ //关注我
			$list = $this->where("fid=$uid AND type=$type")->order('follow_id DESC')->findpage($limit);
			foreach ($list['data'] as $key=>$value){
				$uid = $value['uid'];
				$fid = $value['fid'];
				$list['data'][$key]['uid'] = $fid;
				$list['data'][$key]['fid'] = $uid;
			}
		}

		$fids = $fidHash = array();
		foreach ($list['data'] as $k=>$v){

			//先使用此方法
			$list['data'][$k]['mini'] = M('weibo')->where('uid='.$v['fid'].' AND type='.$type)->order('weibo_id DESC')->find();

			$fids[] = $v['fid'];
			$fidHash[$v['fid']]['follower'] = $fidHash[$v['fid']]['following']
											= $fidHash[$v['fid']]['user']
											= $fidHash[$v['fid']]['mini']
											= $fidHash[$v['fid']]['followState']
											='';

			$list['data'][$k]['follower']  = & $fidHash[$v['fid']]['follower'];
			$list['data'][$k]['following']  = & $fidHash[$v['fid']]['following'];
			$list['data'][$k]['user']  = & $fidHash[$v['fid']]['user'];
			$list['data'][$k]['mini']  = & $fidHash[$v['fid']]['mini'];
			$list['data'][$k]['followState']  = & $fidHash[$v['fid']]['followState'];
		}


		//最新微广播
		// 先注释,效果不佳
		/*
		$maxWeibo = M('Weibo')->field('Max(weibo_id) as weibo_id,uid')->where("uid in (". implode(",",$fids).") AND type = {$type}" )->group('uid')->findAll();
		$maxId = array();
		foreach($maxWeibo as $v){
			$maxId[] = $v['weibo_id'];
		}
		$miniList = M('Weibo')->where("weibo_id in (". implode(",",$maxId).")")->findAll();
		foreach($miniList as $v){
			$fidHash[$v['uid']]['mini'] = $v;
		}
		*/
		//用户地址
		$location = M('User')->where("uid in (". implode(",",$fids).")")->field('location,uid')->findAll();

		//关注和被关注
		$following = $this->field('count(1) as count,uid')->where("uid in (". implode(",",$fids).") AND type={$type}")->group('uid')->findAll();
		$follower = $this->field('count(1) as count,fid')->where("fid in (". implode(",",$fids).") AND type={$type}")->group('fid')->findAll();

		//关注状态
		$followState = $this->getStateByArr($ts['user']['uid'],$fids);

		foreach($followState as $k => $v){
			$fidHash[$k]['followState'] = $v != 3 ? $v != 1 ? 'unfollow' : 'havefollow' : 'eachfollow';
		}

		foreach ($location as $v){
			$fidHash[$v['uid']]['user']['location'] = $v['location'];
		}
		foreach ($following as $v){
			$fidHash[$v['uid']]['following'] =$v['count'];
		}
		foreach ($follower as $v){
			$fidHash[$v['fid']]['follower'] =$v['count'];
		}

		return $list;
	}

	/**
	 * 批量获取用户与一群人的彼此关注状态
	 *
	 * @param unknown_type $uid
	 * @param unknown_type $followArr
	 * return 1:我关注他,2:他关注我,3互相关注
	 */
	public function getStateByArr($uid,$followArr){
		$list = M('weibo_follow')->where(" ( uid = '{$uid}' and fid in(".implode(',',$followArr).") ) OR ( uid in(".implode(',',$followArr).") and fid = '{$uid}')")->findAll();
		$data = array();
		foreach($list as $v){
			if($v['uid'] == $uid){	//我关注他
				 $data[$v['fid']] = $data[$v['fid']] >0 ? 3 : 1;
			}else{	//他关注我
				$data[$v['uid']] = $data[$v['uid']] > 0 ? 3 : 2;
			}
		}
		return $data;
	}

	/**
	 * 批量获取某用户是否关注了一批用户
	 * Enter description here ...
	 */
	
	public function ifFollowByArr($uid,$arr){
		$list = M('weibo_follow')->where(" type='0' AND ( uid = '{$uid}' and fid in(".implode(',',$arr).") ) ")->findAll();
		$data = array();
		foreach($list as $v){
			$data[$v['fid']] = 1;
		}
		return $data;		
	}
	
    /**
     +----------------------------------------------------------
     * 搜索用户
     +----------------------------------------------------------
     * @param string|array $data 传入的参数（重构后可多个参数以数据型式传入）
     * @return array 搜索的结果数据
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-3-15 下午2:16:47
     +----------------------------------------------------------
     */
    function doSearchUser($data){
    	global $ts;
    	$addwhere = "";
    	//判断参数是否为数组
    	if (is_array($data)) {
    		if ($data['sex']!='') {	//判断是否选择性别查询
    			$addwhere .= " AND sex = '".$data['sex']."'";
    		}
    		if ($data['identity']!='') {		//判断是否选择角色
    			$addwhere .= " AND uid in (SELECT uid FROM ".C('DB_PREFIX')."user_group_link WHERE user_group_id = '".$data['identity']."')";
    		}
    		if ($data['school']!='' && '' == $data['class']) { 		//判断是否选择学校
    			$userlist = uc_get_userlist_by_sid($data['school']);
    			$uid = $this->__formatUcid($userlist);
    			if (!empty($uid)) {
    				$addwhere .= " AND uid IN (".implode(',', $uid).")";
    			}else{
    				$addwhere .= " AND uid=0";
    			}
    		}
    		
    		if ($data['class']!='') { 		//判断是否选择班级
    			$userlist = uc_get_userlist_by_cid($data['class']);
    			$uid = $this->__formatUcid($userlist);
    			if (!empty($uid)) {
    				$addwhere .= " AND uid IN (".implode(',', $uid).")";
    			}else{
    				$addwhere .= " AND uid=0";
    			}
    		}
    		
    		$key = $data['key'];
    	}else{
    		$key = $data;
    	}
    	if ($key) {}

    	//判断参数关键字是否为空
    	$list = $this->table(C('DB_PREFIX').'user')->where("uname LIKE '%{$key}%'".$addwhere)->findPage(15);
    	
    	/*
    	  * 缓存用户的基本信息, 被关注数, 关注数
    	  */
    	$uids = getSubByKey($list['data'], 'uid');
    	$user_count_model = model('UserCount');
    	$user_count_model->setUserFollowingCount($uids);
    	$user_count_model->setUserFollowerCount($uids);
    	D('User', 'home')->setUserObjectCache($list['data']);

    	foreach ($list['data'] as $k=>$v){
    	   	// 因为是每位用户的最新微广播, 所以不好预先查询.
			$list['data'][$k]['mini']        = M('weibo')->where('uid='.$v['uid'].' AND type=0 AND isdel = 0')->order('weibo_id DESC')->find();
			$list['data'][$k]['following']   = $user_count_model->getUserFollowingCount($v['uid']);
			$list['data'][$k]['follower']    = $user_count_model->getUserFollowerCount($v['uid']);
			$list['data'][$k]['followState'] = $this->getState( $ts['user']['uid'] , $v['uid'] );
			$list['data'][$k]['area']        = $v['location'];
		}

    	return $list;
    }
    
    /**
     +----------------------------------------------------------
     * 将UC获取用的用户列表格式化输出社区uid一维数组
     +----------------------------------------------------------
     * @param array $userlist UC获取的用户列表
     * @return array 社区的用户ID获表
     * * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-3-16 下午2:17:44
     +----------------------------------------------------------
     */
    private function __formatUcid($userlist=array()){
    	foreach ($userlist as $user){
    		$uc_uid[] = $user['uid'];
    	}
    	if (!empty($uc_uid)) {
    		$uids = M('ucenter_user_link')->field('uid')->where('uc_uid IN ('.implode(',', $uc_uid).')')->findAll();
    		foreach ($uids as $user){
    			$uid[] = $user['uid'];
    		}
    	}
    	return $uid;
    }
    
    /**
     +--------------------------------------------------------
     * 通过UID查找所有关注好友
     +--------------------------------------------------------
     * @param  integer  UID
     * @return array  关注列表
     * @author ssq 2013-12-12
     */
    function getListByUid($uid,$limit){
    	$limit  = ($limit-1)*21;
    	$limit  = $limit.",21";
    	$result =M('User u')
    			->join('ts_weibo_follow f on u.uid=f.fid')
    			->where('f.uid='.$uid)
    			->order('f.fid')
    			->limit($limit)
    			->select();
    	return  $result;
    }
}
?>