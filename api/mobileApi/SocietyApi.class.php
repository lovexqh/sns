<?php
/**
 * －－－－－－－－－－－－－－－－－－－－－－－－－－
 * 社交圈API
 * @author ssq  14-02-10
 * －－－－－－－－－－－－－－－－－－－－－－－－－－
 */
class SocietyApi extends Api {
	function _initialize() {
	}
	private function get_photo_url($savepath) {
		$path = '/data/uploads/' . $savepath;
		if (! file_exists ( '.' . $path ))
			$path = '/apps/society/Tpl/desktop/Public/images/society_pic.gif';
		return $path;
	}
	
	/**
	 * 获取我加入社交圈汇总
	 * -------------------------------------------
	 * @param 用户UID 新鲜事条数
	 * @return status, statusCode, data
	 * @author ssq 14-02-10
	 * -------------------------------------------
	 */
	function getNewList() {
		$uid   = $_REQUEST ['uid'];
		$count = $_REQUEST ['value'];
		$arrkey = array (
				'sid' => 'id',
				'shead' => 'icon',
				'sname' => 'societyName',
				'ssign' => 'sign',
				'stype' => 'type',
				'stag' => 'tags',
				'smanager' => 'manager',//管理员
				'snumber' => 'messageCount',//帖子总数
				'sarray' => 'message' //新鲜事
		);
		$sarray = array (
				'uid' => 'uid',
				'uname' => 'userName',
				'uhead' => 'userIcon',
				'tid' => 'id',
				'thead' => 'thead',
				'ttitle' => 'title',
				'ttime' => 'cTime',
				'treply' => 'replyCount'
		);
		$userInfo = get_user_by_uid ( getUcUid ( $uid ) ); // 获取用户社区信息
		$userInfo = $userInfo [0];
		$ucInfo = get_baseinfo_by_uid ( getUcUid ( $uid ) ); // 获取用户UIA信息
		$schoolSociety = $this->openSchoolSociety ( $uid, $userInfo ['identitytype'], $ucInfo ); // 查看所属校方圈子，没有的话新建,返回所有校方圈子
		foreach ($schoolSociety as $key => $value) {
			$result[] = $value;
		}
		$result = $this->array_sort($result, 'type' ,$type='asc');
		$results = D ( 'SocietyMember', 'society' )->getListByUID ( $uid );
		foreach ($results as $key => $value) {
			$results[$key]['type'] = 6; 
		}
		foreach ($results as $key => $value) {
			$result[] = $value;
		}
		foreach ($result as $key => $value) {
			$result[$key]['manager'] = $this->getSocietyManager($value['id']);
			$string = array();
			$strings = array();
			$string = explode(",",$result[$key]['manager']);
			foreach ($string as $k => $v) {
				$strings[] = getUserName($v);
			}
			$result[$key]['manager'] = '';
			foreach ($strings as $k => $v) {
				$result[$key]['manager'] .= $v.',';
			}
			if(strlen($result[$key]['manager'])>=1){
				$result[$key]['manager'] = substr($result[$key]['manager'],0,strlen($result[$key]['manager'])-1);
			}
			$result[$key]['icon'] = SITE_URL.$this->get_photo_url($result[$key]['icon']);
			$messageList = $this->getSocietyMessage($value['id'],$count);
			foreach ($messageList as $k => $v) {
				$messageList[$k]['userName'] = getUserName($v['uid']) ? getUserName($v['uid']) : getUserEmail($v['uid']);
				$messageList[$k]['userIcon'] = getUserFace($v['uid']);
				$messageList[$k]['cTime'] = date("Y-m-d H:i",$v['cTime']);
				$messageList[$k]['replyCount'] = D('SocietyComment','society')->getSocietyCommentCount(null,null,$v['id']);
				$messageList[$k]['thead'] = $this->doTopicPic($v);
				$messageList[$k] = getApiArray($messageList[$k], $sarray);
			}
			if (!isset($messageList)) {
				$messageList = array();
			}
			$result[$key]['messageCount'] = D('SocietyMessage','society')->getSocietyMessageCount($value['id']);
			$result[$key]['message'] = $messageList;
		}
		foreach ($result as $key => $value) {
			$apiarr[] = getApiArray($value, $arrkey);
		}
		$return['status']     = 0;
		$return['statusCode'] = '连接成功';
		$return['data']       = $apiarr;
		return $return;
	}
	
	/**
	 * 指定条件获取我的社交圈
	 * @param  int 用户id
	 * @param  string 关键字
	 * @param  int  圈子类型 1班级级，2专业级，3年级级，4院系级，5部门级，6自定义级
	 * @return  array 
	 * @author  ssq 14-02-11
	 */
	function getlist(){
		$uid         = $_REQUEST['uid'];
		$societyName = $_REQUEST['keyword'];
		$type        = $_REQUEST['stype'];
		$type        = $type == 6 ? 0 : $type;
		$arrkey      = array (
				'sid'      => 'societyId',
				'shead'    => 'icon',
				'sname'    => 'societyName',
				'ssign'    => 'sign',
				'stype'    => 'type',
				'smanager' => 'manager',
				'snumber'  => 'messageCount',
				'slook'    => 'visitable',
				'scheck'   => 'joinable',
				'isjoin'   => 'isjoin',
				'ischeck'  => 'ischeck'
		);
		if (isset($societyName)) {
			$param['_string'] = 'sm.societyName like "%'.$societyName.'%"  OR sm.tags like "%'.$societyName.'%"';
		}
		if (isset($type)) {
			$param['sm.type'] = $type;
		}
		if (!isset($type) && !isset($societyName)) {
			$return['status']     = 2;
			$return['statusCode'] = '传入参数错误';
			$return['data']       = array();
			return $return;
			exit();
		}
		$param['sm.isDel'] = 0;
		$param['s.isDel']  = 0;
		$param['s.status'] = 2;
		$result = D('Society','society')->getSocietyBypara($param);
		foreach ($result as $key => $value) {
			$result[$key]['manager'] = $this->getSocietyManager($value['societyId']);
			$string = array();
			$strings = array();
			$string = explode(",",$result[$key]['manager']);
			foreach ($string as $k => $v) {
				$strings[] = getUserName($v);
			}
			$result[$key]['manager'] = '';
			foreach ($strings as $k => $v) {
				$result[$key]['manager'] .= $v.',';
			}
			if(strlen($result[$key]['manager'])>=1){
				$result[$key]['manager'] = substr($result[$key]['manager'],0,strlen($result[$key]['manager'])-1);
			}
			$result[$key]['messageCount'] = D('SocietyMessage','society')->getSocietyMessageCount($value['societyId']);
			$result[$key]['icon'] = SITE_URL.$this->get_photo_url($result[$key]['icon']);
			if ($value['type']==0) {
				$result[$key]['isjoin'] = $this->getResultOfApply($value['societyId'], $uid);
			}else{
				$result[$key]['isjoin'] = 2;
			}
			if($result[$key]['isjoin']==0){
				unset($param);
				$param['societyId'] = $value['societyId'];
				$param['fromUid']   = $uid;
				$param['newsType']  = 1;
				$param['result']    = 0;
				$re = D('SocietyNews','society')->getNewsByParam($param);
				if ($re) {
					$result[$key]['ischeck'] = 1;
				}else{
					$result[$key]['ischeck'] = 2;
				}
			}else{
				$result[$key]['ischeck'] = 0;
			}
			if($result[$key]['type']==0){
				$result[$key]['type'] = 6;
			}
			$apiarr[$key] = getApiArray($result[$key], $arrkey);
		}
		$return['status']     = 0;
		$return['statusCode'] = '连接成功';
		$return['data']       = $apiarr;
		return $return;
	}
	
	/**
	 * 加入或者退出圈子操作
	 * --------------------------
	 * @param  int 用户id
	 * @param  int 圈子id
	 * @param  int 1代表加入 2代表退出
	 * @return  array 
	 * @author  ssq 14-02-11
	 * --------------------------
	 */
	function doaction(){
		$uid  = $_REQUEST['uid']; //用户UID
		$sid  = $_REQUEST['sid']; //圈子ID
		$join = $_REQUEST['join']; //操作 1代表加入 2代表退出
		if(!isset($sid) || !isset($join)){
			$return['status']     = 2;
			$return['statusCode'] = '传入参数不能够为空';
			return $return;
			exit();
		}
		$society = D('Society','society')->getSocietyInfoBypara(array('id'=>$sid,'isDel'=>0));
		if (!isset($society)) {
			$return['status']     = 2;
			$return['statusCode'] = '没有该圈子信息';
			return $return;
			exit();
		}
		switch ($join) {
			case 1: //加入
				if($society['joinable']==0){
					$param['societyId'] = $sid;
					$param['uid'] = $uid;
					$param['status'] = 0;
					$param['cTime'] = time();
					$param['isDel'] = 0;
					$id = D('SocietyMember','society')->addMemberBySocietyid_Uid($param);
					if (isset($id)) {
						$params['societyId'] = $sid;
						$params['fromUid'] = $uid;
						$params['toUid'] = 0;
						$params['newsType'] =2 ;
						$params['result'] = 3;
						$params['cTime'] = time();
						$params['isDel'] = 0;
						D('SocietyNews','society')->addNews($params);
						$return['status']     = 0;
						$return['statusCode'] = '加入成功';
					}else{
						$return['status']     = 2;
						$return['statusCode'] = '加入失败';
					}					
					return $return;
					exit();
				}else{
					$param['societyId'] = $sid;
					$param['fromUid'] = $uid;
					$param['toUid'] = 0;
					$param['newsType'] =1 ;
					$param['result'] = 0;
					$param['cTime'] = time();
					$param['isDel'] = 0;
					$re = D('SocietyNews','society')->addNews($param);
					if($re>=1){
						$return['status']     = 0;
						$return['statusCode'] = '加入请求已发送，正在审核中！';
						return $return;
					}else{
						$return['status']     = 2;
						$return['statusCode'] = '加入失败';
						return $return;
					}
				}
				
				
			break;
			case 2: //退出
				$member = D('SocietyMember','society')->getSocietyInfoByParam(array('societyId'=>$society['id'],'uid'=>$uid,'isDel'=>0));
				if($member[0]['status']==2){
					$return['status']     = 2;
					$return['statusCode'] = '退出失败,请在WEB端退出该圈子';
					return $return;
					exit();
				}
				$param['societyId'] = $sid;
				$param['uid'] = $uid;
				$id = D('SocietyMember','society')->delMember($param);
				if ($id==1) {
					$params['societyId'] = $sid;
					$params['fromUid'] = $uid;
					$params['toUid'] = 0;
					$params['newsType'] =3 ;
					$params['result'] = 0;
					$params['cTime'] = time();
					$params['isDel'] = 0;
					D('SocietyNews','society')->addNews($params);
					$return['status']     = 0;
					$return['statusCode'] = '退出成功';
				}else{
					$return['status']     = 2;
					$return['statusCode'] = '退出失败';
				}
				return $return;
				exit();
			break;
		}
	}
	
	/**
	 * 创建圈子
	 * ------------------------------
	 * @param $param 圈子的基本信息
	 * @return array
	 * @author ssq 14-02-11
	 * ------------------------------
	 */
	function create() {
		$param['uid']          = $_REQUEST['uid'];
		$param['societyName']  = $_REQUEST['sname'];
		$param['tag']          = $_REQUEST['stag'];
		$param['visitable']    = $_REQUEST['slook'];
		$param['downloadable'] = $_REQUEST['sshare'];
		$param['joinable']     = $_REQUEST['scheck'];
		$param['type']         = 0;
		$param['isDel']        = 0;
		$param['cTime']        = time();
		foreach ($param as $key => $value) {
			if (!isset($value)) {
				$return['status']     = 2;
				$return['statusCode'] = '必填参数不能为空';
				return $return;
				exit();
			}
		}		
		$param['sign']         = $_REQUEST['ssign'];
		$id = D('Society','soceity')->addSocietyInfoBypara($param);
		if ($id==1) {
			$return['status']     = 0;
			$return['statusCode'] = '创建成功';
			return $return;
			exit();
		}else {
			$return['status']     = 2;
			$return['statusCode'] = '创建失败';
			return $return;
			exit();
		}
	}
	

	/**
	 * 修改圈子
	 * ------------------------------
	 * @param $param 圈子的基本信息
	 * @return array
	 * @author ssq 14-02-11
	 * ------------------------------
	 */
	function modify() {
		$id                    = $_REQUEST['sid'];
		$uid                   = $_REQUEST['uid'];
		$param['societyName']  = $_REQUEST['sname'];
		$param['tag']          = $_REQUEST['stag'];
		$param['sign']         = $_REQUEST['ssign'];
		$param['notice']       = $_REQUEST['snotice'];
		$param['visitable']    = $_REQUEST['slook'];
		$param['downloadable'] = $_REQUEST['sshare'];
		$param['joinable']     = $_REQUEST['scheck'];
		$society = D('Society','society')->getSocietyInfoBypara(array('id'=>$id,'isDel'=>0));
		if (!isset($society)) {
			$return['status']     = 2;
			$return['statusCode'] = '没有该圈子信息';
			return $return;
			exit();
		}
		foreach ($param as $key => $value) {
			if (!isset($value)) {
				unset($param[$key]);
			}
		}
		$result = D('Society','society')->settingSocietyBypara($param,$id);
		if ($result==1) {
			$return['status']     = 0;
			$return['statusCode'] = '更新成功';
			return $return;
			exit();
		}else{
			$return['status']     = 2;
			$return['statusCode'] = '更新失败';
			return $return;
			exit();
		}
	}
	
	/**
	 * 上传头像(更新操作)
	 * @description
	 * @param int 用户UID
	 * @param int 社交圈ID
	 * @return array
	 * @author ssq 14-02-12
	 */
	function uploadhead(){
		$uid       = $_REQUEST['uid'];
		$societyId = $_REQUEST['sid'];
		 
		$options ['userId'] = $uid;
		$options ['max_size'] = 2 * 1024 * 1024; // 2MB
		$options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
		$info = X ( 'Xattach' )->upload ( 'group_logo', $options );
		if ($info ['status']) {
			$data ['icon'] = $info ['info'] [0] ['savepath'] . $info ['info'] [0] ['savename'];
		}
		
		$result = D('Society')->settingSocietyBypara($data,$this->societyId);
		if($result >= 0){
			$return['status']     = 2;
			$return['statusCode'] = '修改成功!';
		}else{
			$return['status']     = 2;
			$return['statusCode'] = '修改失败!';
		}
		return $return;
	}

	/**
	 * 获取指定类型的备选邀请列表，根据传入的id，从而显示班级好友，专业好友，年级好友，院系好友，部门好友
	 * @param int uid
	 * @param int 好友类型
	 * @param int 页数
	 * @param int 每页数量
	 * @author ssq 14-02-20
	 */
	function toInvite(){
		$uid   = $_REQUEST['uid'];
		$stype = $_REQUEST['stype'];
		$page  = $_REQUEST['page'];
		$num   = $_REQUEST['num'];
		if (!isset($uid) || !isset($stype) || !isset($page) || !isset($num)) {
			$return['status']     = 2;
			$return['statusCode'] = '传入参数不完整';
			$return['data']       = array();
			return $return;
			exit();
		}
		$userInfo = get_user_by_uid ( getUcUid ( $uid ) ); // 获取用户社区信息
		$userInfo = $userInfo [0];
		$ucInfo   = get_baseinfo_by_uid ( getUcUid ( $uid ) ); // 获取用户UIA信息
		switch ($userInfo['identitytype']) {
			case 2: // 老师
				if($stype==5){
					//求出总条数
					$lists = get_teacher_same_dept_includeMe($ucInfo['depid'],0, 1 ,1);
					$total = $lists[0]['total']; //总条数
					if (($page-1)*$num>=$total) {
						$return['status']     = 2;
						$return['statusCode'] = 'end';
						$return['data']       = array();
						return $return;
						exit();
					}
					$list = get_teacher_same_dept_includeMe($ucInfo['depid'],$page-1, $num ,1);
					$userList = $list[0]['data'];
					if (!$userList) {
						$list = get_teacher_same_dept_includeMe($this->ucInfo['depid'],0,21,1);
						$userList = $list[0]['data'];
					}
					foreach ($userList as $v){
						$reg_member[]= $v['uid'];
					}
					$map['uc_uid']  = array('in',$reg_member);
					$userList = array();
					$userList = M('ucenter_user_link')->where($map)->field('uid')->select();
					foreach ($userList as $key=>$value) {
						$userList[$key]['uid'] = $value['uid'];
						$userList[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) : getUserEmail($value['uid']);
						$userList[$key]['uhead'] = getUserFace($value['uid'], 'm');
					}
					$return['status']     = 1;
					$return['statusCode'] = '连接成功';
					$return['data']       = $userList;
					return $return;
					exit();
				}else{
					$return['status']     = 2;
					$return['statusCode'] = '当前用户无指定类型好友';
					$return['data']       = array();
					return $return;
					exit();
				}
			break;
			case 3: // 学生
				$schoolid = $ucInfo['schoolid'] ? $ucInfo['schoolid'] : 1;
				$yxid     = $ucInfo['yxid'];
				$zyid     = $ucInfo['zyid'];
				$bjid     = $ucInfo['bjid'];
				$nj       = $ucInfo['nj'];
				switch ($stype) {
					case 1:
						return $this->getUserByStudentInfo($schoolid, $yxid, $zyid, $bjid, $nj, 1, $num, $page);
					break;
					case 2:
						return $this->getUserByStudentInfo($schoolid, $yxid, $zyid, null, $nj, 1, $num, $page);
					break;
					case 3:
						return $this->getUserByStudentInfo($schoolid, $yxid, null, null, $nj, 1, $num, $page);
					break;
					case 4:
						return $this->getUserByStudentInfo($schoolid, $yxid, null, null, null, 1, $num, $page);
					break;
					default:
						$return['status']     = 2;
						$return['statusCode'] = '当前用户无指定类型好友';
						$return['data']       = array();
						return $return;
						exit();
					break;
				}
			break;
			default:
				$return['status']     = 2;
				$return['statusCode'] = '当前用户无指定类型好友';
				$return['data']       = array();
				return $return;
				exit();
			break;
		}
	}
	
	/**
	 * 邀请好友
	 * ---------------------------------------
	 * @param int 用户UID
	 * @param string 用户uid字符窜 逗号隔开
	 * @param int 社交圈ID
	 * @return string
	 */
	function invite(){
		$uid       = $_REQUEST['uid'];
		$member    = $_REQUEST['iid'];
		$societyId = $_REQUEST['sid'];
		if (!isset($uid) || !isset($member) || !isset($societyId)) {
			$return['status']     = 2;
			$return['statusCode'] = '传入参数不能为空';
			return $return;
			exit();
		}
		$uidList = explode(',',$member);
		foreach ($uidList as $val){
			$list = array();
			$list['societyId'] = $societyId;
			$list['fromUid'] = $uid;
			$list['toUid'] = $val;
			$list['newsType'] = 2;
			$list['cTime'] = time();
			D('SocietyNews')->addNews($list);
		}
		$param['to'] = $member;
		$param['content'] = '您好，邀请您加入圈子 "'.$this->societyInfo['societyName'].'" 请点击 <a href="'.U('society/Index/index').'" target="_blank">社交圈</a> 板块查看您的消息！';
		$res = model('Message')->postMessage($param, $uid);
		$return['status']     = 0;
		$return['statusCode'] = '邀请成功';
		return $return;
		exit();
	}
	
	/**
	 * 同意邀请加入某圈子		
	 * @param int 用户uid
	 * @param int 圈子ID
	 * @param int 要加入的用户UID
	 * @return array
	 * @author ssq 14-02-14
	 */
	function agree(){
		$uid                = $_REQUEST['uid'];
		$param['societyId'] = $_REQUEST['sid'];
		$param['uid']       = $_REQUEST['iid'];
		if (!isset($param['societyId']) || !isset($param['uid'])) {
			$return['status']     = 2;
			$return['statusCode'] = '传入参数不能为空';
			return $return;
			exit();
		}
		$param['status']    = 0;
		$result = D('SocietyMember','society')->addMemberBySocietyid_Uid($param);
		if ($result>=1) {
			$return['status']     = 0;
			$return['statusCode'] = '加入成功';
			return $return;
			exit();
		}else {
			$return['status']     = 2;
			$return['statusCode'] = '加入失败';
			return $return;
			exit();
		}
	}
	
	/**
	 * 获取指定圈子的详情，返回圈子详情		
	 * @return int 用户UID
	 * @return int 圈子ID
	 * @return array
	 * @author ssq 14-02-14
	 */
	function detail(){
		$uid       = $_REQUEST['uid'];
		$societyId = $_REQUEST['sid'];
		$arrkey    = array (
				'shead'    => 'icon',  // 头像
				'sname'    => 'societyName', // 圈子名称
				'ssign'    => 'sign', //签名
				'stag'     => 'tags', //标签
				'stype'    => 'type',//管理员
				'smanager' => 'manager',//管理员
				'snumber'  => 'messageCount',//帖子总数
				'smember'  => 'memberCount',//成员数
				'snotice'  => 'notice',//公告
				'srole'    => 'status'//权限
		);
		$society = D('Society','society')->getSocietyInfoBypara(array('id'=>$societyId));
		if (!isset($society)) {
			$return['status'] = 2;
			$return['statusCode'] = '没有该圈子';
			$return['data'] = array();
			return $return;
			exit();
		}
		$society['manager'] = $this->getSocietyManager($societyId);
		$string = explode(",",$society['manager']);
		foreach ($string as $key => $value) {
			$strings[] = getUserName($value);
		}
		$society['manager'] = '';
		foreach ($strings as $key => $value) {
			$society['manager'] .= $value.',';
		}
		if(strlen($society['manager'])>=1){
			$society['manager'] = substr($society['manager'],0,strlen($society['manager'])-1);
		}
		$society['messageCount'] = D('SocietyMessage','society')->getSocietyMessageCount($society['id']);
		$society['icon'] = SITE_URL.$this->get_photo_url($society['icon']);
		$school = uc_get_school_by_uid($uid);
		if ( $school=='' || $school==null ) {
			$school['id'] = 1;
		}
		$schoolid   = $school['id'];
		$type       = $society['type'];
		$yxid       = $society['typeid'];
		$zyid       = $society['typeid'];
		$bjid       = $society['typeid'];
		$nj         = $society['typeid'];
		$depid         = $society['typeid'];
        $ucInfo = get_baseinfo_by_uid ( getUcUid ( $uid ) ); // 获取用户UIA信息
		switch ($society['type']) {
			case 0:
				$society['memberCount']  = D('SocietyMember','society')->countMember(array('societyId'=>$societyId));
				$member = D('SocietyMember','society')->getSocietyInfoByParam(array('societyId'=>$society['id'],'uid'=>$uid,'isDel'=>0));
				if (!isset($member)) {
					$society['status'] = -1;
				}else{
					$society['status'] = $member[0]['status'];
				}
			break;
			case 1:
				$memberLists = get_Student_baseinfo_by_org($schoolid,null,null,$bjid,null,1,12);
				$society['memberCount'] = $memberLists[0]['totalcount'];
				$member = D('SocietyMember','society')->getSocietyInfoByParam(array('societyId'=>$society['id'],'uid'=>$uid,'isDel'=>0));
				if (!isset($member)) {
                    if($ucInfo['bjid'] == $bjid){
                        $society['status'] = 0;
                    }else{
                        $society['status'] = -1;
                    }
				}else{
					$society['status'] = $member[0]['status'];
				}
			break;
			case 2:
				$memberLists = get_Student_baseinfo_by_org($schoolid,null,$zyid,null,null,1,12);
				$society['memberCount'] = $memberLists[0]['totalcount'];
				$member = D('SocietyMember','society')->getSocietyInfoByParam(array('societyId'=>$society['id'],'uid'=>$uid,'isDel'=>0));
				if (!isset($member)) {
                    if($ucInfo['zyid'] == $zyid){
                        $society['status'] = 0;
                    }else{
                        $society['status'] = -1;
                    }
				}else{
					$society['status'] = $member[0]['status'];
				}
			break;
			case 3:
				$memberLists = get_Student_baseinfo_by_org($schoolid,null,null,null,$nj,1,12);
				$society['memberCount'] = $memberLists[0]['totalcount'];
				$member = D('SocietyMember','society')->getSocietyInfoByParam(array('societyId'=>$society['id'],'uid'=>$uid,'isDel'=>0));
				if (!isset($member)) {
                    if($ucInfo['nj'] == $nj){
                        $society['status'] = 0;
                    }else{
                        $society['status'] = -1;
                    }
				}else{
					$society['status'] = $member[0]['status'];
				}
			break;
			case 4:
				$memberLists = get_Student_baseinfo_by_org($schoolid,$yxid,null,null,null,1,12);
				$society['memberCount'] = $memberLists[0]['totalcount'];
				$member = D('SocietyMember','society')->getSocietyInfoByParam(array('societyId'=>$society['id'],'uid'=>$uid,'isDel'=>0));
				if (!isset($member)) {
                    if($ucInfo['yxid'] == $yxid){
                        $society['status'] = 0;
                    }else{
                        $society['status'] = -1;
                    }
				}else{
					$society['status'] = $member[0]['status'];
				}
			break;
			case 5: 
				$memberLists = get_teacher_same_dept_includeMe($society['typeid'],1,12,1);
                $society['memberCount'] = $memberLists[0]['total'];
				$member = D('SocietyMember','society')->getSocietyInfoByParam(array('societyId'=>$society['id'],'uid'=>$uid,'isDel'=>0));
				if (!isset($member)) {
                    if($ucInfo['depid'] == $depid){
                        $society['status'] = 0;
                    }else{
                        $society['status'] = -1;
                    }
				}else{
					$society['status'] = $member[0]['status'];
				}
			break;
		}
		if($society['type'] == 1){
			$society['type'] = '班级';
		}else if($society['type'] == 2){
			$society['type'] = '专业';
		}else if($society['type'] == 3){
			$society['type'] = '年级';
		}else if($society['type'] == 4){
			$society['type'] = '院系';
		}else if($society['type'] == 5){
			$society['type'] = '部门';
		}else if($society['type'] == 0){
			$society['type'] = '自定义';
		}
		$apiarr = getApiArray($society, $arrkey);
		$return['status'] = 0;
		$return['statusCode'] = '连接成功';
		$return['data'] = $apiarr;
		return $return;
		exit();
	}
	
	/**
	 * 获取指定圈子的新鲜事列表
	 * @param int 用户uid
	 * @param int 圈子id
	 * @param int page
	 * @return array
	 * @author ssq 14-02-14
	 */
	function tlist(){
		$uid        = $_REQUEST['uid'];
		$societyId  = $_REQUEST['sid'];
		$page       = $_REQUEST['page'];
		$num        = $_REQUEST['num']; //每一页个数
		$arrayKey = array(
				'uid'=>uid,
				'uname'=>'userName',
				'uhead'=>'icon',
				'tid'=>'id',
				'ttitle'=>'title',
				'tcontent'=>'content',
				'ttime'=>'cTime',
				'reply'=>'commentNum',
				'replytime'=>'commentTime',
				'replyuname'=>'commentUid'
		);
		$society = D('Society','society')->getSocietyInfoBypara(array('id'=>$societyId));
		if (!isset($society)) {
			$return['status'] = 2;
			$return['statusCode'] = '没有该圈子';
			$return['data'] = array();
			return $return;
			exit();
		}
		$messageList=D('SocietyMessage','society')->getSocietyMessageBySocietyidForApi($societyId,$page,$num);
	    foreach ($messageList as $key=>$item){
	    	$messageList[$key]['content'] = text($messageList[$key]['content']);
	    	$messageList[$key]['commentNum']=D('SocietyComment','society')->getSocietyCommentCount($item['societyId'],NULL,$item['id']);
            $result = D('SocietyComment')->getSocietyCommentLast($item['societyId'],$item['id']);
            $messageList[$key]['userName'] = getUserName($item['uid']) ? getUserName($item['uid']) : getUserEmail($item['uid']);
            $messageList[$key]['icon'] = getUserFace($item['uid']);
            $messageList[$key]['cTime'] = date("Y-m-d H:i",$item['cTime']);
            if ($result) {
	            $messageList[$key]['commentUid'] = getUserName($result['uid']);
	            $messageList[$key]['commentTime'] = date("Y-m-d H:i",$result['cTime']);
            }else{
            	$messageList[$key]['commentUid'] = '';
            	$messageList[$key]['commentTime'] = '';
            }
	    }
		foreach ($messageList as $key => $value) {
			$apiarr[$key] = getApiArray($value,$arrayKey);
		}
		if ($apiarr) {
			$return['status'] = 0;
			$return['statusCode'] = '连接成功';
			$return['data'] = $apiarr;
			return $return;
			exit();
		}else{
			$return['status'] = 2;
			$return['statusCode'] = 'end';
			$return['data'] = array();
			return $return;
			exit();
		}
	}
	
	/**
	 * 删除新鲜事
	 * @param int 用户id
	 * @param int 圈子id
	 * @return array
	 * @author ssq 14-02-14 
	 */
	function deletet(){
		$uid = $_REQUEST['uid'];
		$id  = $_REQUEST['tid'];
		$result = D('SocietyMessage','society')->deleteMess($id);
		if ($result>=1) {
			$result = D("SocietyComment")->deleteComment($id);
			$return['status'] = 0;
			$return['statusCode'] = '删除成功';
			return $return;
			exit();
		}else{
			$return['status'] = 2;
			$return['statusCode'] = '删除失败';
			return $return;
			exit();
		}
	}
	
	/**
	 * 发布指定帖子		
	 * @param int 用户uid
	 * @param int 社交圈
	 * @param
	 * @param
	 * @return array
	 */
	function postt(){
		$uid       = $_REQUEST['uid'];
		$societyId = $_REQUEST['sid'];
		$title     = trim($_REQUEST['ttitle']);
		$content   = trim($_REQUEST['tcontent']);
		if (!($uid && $societyId && $title && $content)) {
			$result['status'] = 2;
			$result['statusCode'] = '参数不完整';
			return $result;
			exit();
		}
		$society = D('Society','society')->getSocietyInfoBypara(array('id'=>$societyId,'isDel'=>0));
		if (!isset($society)) {
			$result['status'] = 2;
			$result['statusCode'] = '没有该圈子'; 
			return $result;
			exit();
		}
		$param['societyId'] = $societyId;
		$param['uid']       = $uid;
		$param['title']     = $title;
		$param['content']   = $content;
		$param['cTime']     = time();
		$param['mTime']     = time();
		$param['isDel']     = 0;
		$re = D('SocietyMessage','society')->addMessage($param);
		if ($re>=1) {
			$result['status'] = 0;
			$result['statusCode'] = '发布成功'; 
			return $result;
			exit();
		}else{
			$result['status'] = 2;
			$result['statusCode'] = '发布失败';
			return $result;
			exit();
		}
	}
	/**
	 * 帖子详情
	 * @param int 帖子id
	 * @return array
	 * @author ssq 14-02-21
	 */
	function tdetail(){
		$id     = $_REQUEST['tid'];
		$arrKey = array(
				'uid'     =>'uid',
				'uname'   =>'uname',
				'uhead'   =>'uhead',
				'tid'     =>'id',
				'ttitle'  =>'title',
				'tcontent'=>'content',
				'ttime'   =>'cTime',
				'reply'   =>'replyCount'
		);
		$messResult = D('SocietyMessage','society')->getMess($id);
		if (!$messResult) {
			$result['status'] = 2;
			$result['statusCode'] = '没有该帖子';
			$result['data'] = array();
			return $result;
			exit();
		}
		$uid = $messResult['uid'];
		$messResult['cTime'] = date("Y-m-d H:i",$messResult['cTime']);
		$messResult['uname'] = getUserName($uid) ? getUserName($uid) : getUserEmail($uid);
		$messResult['uhead'] = getUserFace($uid, 'm');
		$messResult['content'] = stripslashes($messResult['content']);
		$messResult['replyCount'] = D('SocietyComment','society')->getSocietyCommentCount(NULL,NULL,$id);
		$re = getApiArray($messResult, $arrKey);
		$result['status'] = 0;
		$result['statusCode'] = '连接成功';
		$result['data'] = $re;
		return $result;
		exit();
	}
	/**
	 * 获取指定帖子的评论列表
	 * 
	 */
	function replylist(){
		$tid  = $_REQUEST['tid'];
		$page = $_REQUEST['page'];
		$num  = $_REQUEST['num'];
		if (!($tid && $page && $num)) {
			$result['status'] = 2;
			$result['statusCode'] = '传入参数不完整';
			$result['data'] = array();
			return $result;
			exit();
		}
		$arrayKey = array(
				'uid'      => 'uid',
				'uname'    => 'uname',
				'uhead'    => 'uhead',
				'rid'      => 'id',
				'rcontext' => 'content',
				'rdate'    => 'cTime',
				'reply'    => 'replyCount',
				'rrlist'   => 'arr'
		);
		$arrayKey_Key = array(
				'rrid'      => 'id',
				'rrcontext' => 'content',
				'rrdate'    => 'cTime',
				'uid'       => 'uid',
				'uname'     => 'uname',
				'uhead'     => 'uhead'
		);
		$result = D('SocietyComment','society')->getCommentLimitForApi(null,$tid,0,$page,$num); //$toId,$start,$limit
		$comment = $result;
		if ($comment=='' || $comment == null) {
			$re['status'] = 2;
			$re['statusCode'] = 'end';
			$re['data'] = array();
			return $re;
			exit();
		}
		foreach ($comment as $key => $value) {
			$comment[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) : getUserEmail($value['uid']);
			$comment[$key]['uhead'] = getUserFace($value['uid'],'m');
			$comment[$key]['cTime'] = date("Y-m-d H:i",$comment[$key]['cTime']);
			$comment[$key]['content'] = stripslashes($comment[$key]['content']);
			
			$rep_result = D('SocietyComment','society')->getCommentLimit($this->societyId,$tid,$comment[$key]['id'],100);
			$comment[$key]['replyCount'] = $rep_result['count'];
			unset($rep_return);
			foreach ($rep_result['data'] as $k => $v) {
				$v['cTime'] = date("Y-m-d H:i",$v['cTime']);
				$v['uname'] = getUserName($v['uid']) ? getUserName($v['uid']) : getUserEmail($v['uid']);
				$v['uhead'] = getUserFace($v['uid'],'m');
				$v['content'] = stripslashes($v['content']);
				$rep_return[] = getApiArray($v, $arrayKey_Key);
			}
			$comment[$key]['arr'] = $rep_return;
			$return[$key] = getApiArray($comment[$key], $arrayKey);
		}
		$re['status'] = 0;
		$re['statusCode'] = '连接成功';
		$re['data'] = $return;
		return $re;
		exit();
	}
	
	/**
	 * 回复帖子
	 * @param 回复内容
	 * @return array
	 * @author ssq 14-02-22
	 */
	function reply(){
		$param['societyId'] = $_REQUEST['sid'];
		$param['messageId'] = $_REQUEST['tid'];
		$param['uid']       = $_REQUEST['uid'];
		$param['toId']      = $_REQUEST['rid'] ? $_REQUEST['rid'] : 0;
		$param['toUid']     = $_REQUEST['ruid'] ? $_REQUEST['ruid'] :0;
		$param['content']   = $_REQUEST['rcontent'];
		$param['cTime']     = time();
		$param['isDel']     = 0;
		foreach ($param as $key => $value) {
			if (!isset($value)) {
				$re['status'] = 2;
				$re['statusCode'] = '传入参数不完整';
				return $re;
			}
		}
		if ( $param['toId'] && $param['toUid'] ) {
			$result = D("SocietyComment")->addMainComment($param);
			if ($result>=1) {
				$re['status'] = 1;
				$re['statusCode'] = '回复成功';
				return $re;
			}else{
				$re['status'] = 2;
				$re['statusCode'] = '回复失败';
				return $re;
			}
		}else{
			$result = D("SocietyComment","society")->addMainComment($param);
			if ($result>=1) {
				$re['status'] = 1;
				$re['statusCode'] = '回复成功';
				return $re;
			}else{
				$re['status'] = 2;
				$re['statusCode'] = '回复失败';
				return $re;
			}
		}
	}
	
	/**
	 * 删除回复
	 * @return string
	 * @author ssq 14-02-22
	 */
	function deletereply(){
		$uid = $_REQUEST['uid'];
 		$id  = $_REQUEST['rid'];
 		if (isset($uid) && isset($id)) {
 			$res = D('SocietyComment','society')->deleteMyComment($id,$uid,1);
 			if ($res>=1){
 				$re['status'] = 0;
 				$re['statusCode'] = '删除成功';
 				return $re;
 			}else{
 				$re['status'] = 2;
 				$re['statusCode'] = '删除失败';
 				return $re;
 			}
 		}else{
 			$re['status'] = 2;
			$re['statusCode'] = '参数不完整';
			return $re;
 		}
	}
	
	/**
	 * 博客
	 * @param
	 * @return array
	 * @author ssq 14-02-22
	 */
	function webloglist(){
		$societyId = $_REQUEST['sid'];
		$page      = $_REQUEST['page'];
		$num       = $_REQUEST['num'];
		$arrayKey  = array(
			'uid'=>'uid',
			'uname'=>'uname',
			'uhead'=>'uhead',
			'wid'=>'id',
			'whead'=>'title',
			'wcontent'=>'content',
			'wtime'=>'cTime',
			'wreadnum'=>'readCount',
			'wreplynum'=>'commentCount'
		);
		
		if (isset($societyId) && isset($page) && isset($num)) {
			$params['id'] = $societyId;
			$params['isDel'] = 0;
			$societyInfo = D('Society','society')->getSocietyInfoBypara($params);
			switch($societyInfo['type']){
				case '0':
					$memberList=D('SocietyMember','society')->getMemberBySocietyid($societyId);
					$uidstr = '-1';
					foreach($memberList as $k=>$v){
						$uidstr .=','.$v['uid'];
					}
					$param['uidstr'] = ltrim($uidstr,',');
					$blogList=D('Blog','blog')->getBlogForSocietyForApi($param,$page,$num);
					break;
				case '1':
					$param['bjid'] = $societyInfo['typeid'];
					$blogList=D('Blog','blog')->getBlogForSocietyForApi($param,$page,$num);
					break;
				case '2':
					$param['zyid'] = $societyInfo['typeid'];
					$blogList=D('Blog','blog')->getBlogForSocietyForApi($param,$page,$num);
					break;
				case '3':
					$param['nj'] = $societyInfo['typeid'];
					$blogList=D('Blog','blog')->getBlogForSocietyForApi($param,$page,$num);
					break;
				case '4':
					$param['yxid'] = $societyInfo['typeid'];
					$blogList=D('Blog','blog')->getBlogForSocietyForApi($param,$page,$num);
					break;
				case '5':
					$param['depid'] = $societyInfo['typeid'];
					$blogList=D('Blog','blog')->getBlogForSocietyForApi($param,$page,$num);
					break;
			}
			$blogLists = $blogList;
			unset($blogList);
			$blogList = $blogLists;
			if ($blogList==null || $blogList=='') {
				$re['status'] = 2;
				$re['statusCode'] = 'end';
				$re['data'] = array();
				return $re;
			}else{
				foreach ($blogList as $key => $value) {
					$blogList[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) :getUserEmail($value['uid']);
					$blogList[$key]['uhead'] = getUserFace($value['uid'],'m');
					$blogList[$key]['cTime'] = date("Y-m-d H:i",$value['cTime']);
					$result[$key] = getApiArray($blogList[$key], $arrayKey);
				}
				if ($result) {
					$re['status'] = 0;
					$re['statusCode'] = '连接成功';
					$re['data'] = $result;
					return $re;
				}else{
					$re['status'] = 2;
					$re['statusCode'] = '连接失败';
					$re['data'] = $result;
					return $re;
				}
			}
		}else{
			$re['status'] = 2;
			$re['statusCode'] = '参数不完整';
			$re['data'] = array();
			return $re;
		}
	}
	
	function resourcelist(){
		$societyId = $_REQUEST['sid'];
		$page      = $_REQUEST['page'];
		$num       = $_REQUEST['num'];
		$arrKey = array(
			'sid' => 'id',
			'spath'=>'path',
			'stitle'=>'shareName',
			'ssize'=>'size',
			'stime'=>'download',
			'sdate'=>'cTime',
			'uid'=>'uid',
			'uname'=>'uname',
			'uhead'=>'uhead'
		);
		if (isset($societyId) && isset($page) && isset($num)) {
			$shareList = D('SocietyShare','society')->getSocietyShareBySocietyid($societyId,$page,$num);
			if ($shareList==null || $shareList=='') {
				$re['status'] = 2;
				$re['statusCode'] = 'end';
				$re['data'] = array();
				return $re;
			}else{
				foreach ($shareList as $key => $value) {
					$shareList[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) :getUserEmail($value['uid']);
					$shareList[$key]['uhead'] = getUserFace($value['uid'],'m');
					$shareList[$key]['cTime'] = date("Y-m-d H:i",$value['cTime']);
					$result[$key] = getApiArray($shareList[$key], $arrKey);
				}
				if ($result) {
					$re['status'] = 0;
					$re['statusCode'] = '连接成功';
					$re['data'] = $result;
					return $re;
				}else{
					$re['status'] = 2;
					$re['statusCode'] = '连接失败';
					$re['data'] = $result;
					return $re;
				}
			
			}
		}else{
			$re['status'] = 2;
			$re['statusCode'] = '参数不完整';
			$re['data'] = array();
			return $re;
		}
	}
	/**
	 * 发布印象
	 * @param 
	 * @return  
	 * @author  ssq 14-02-22
	 */
	function pubimpression(){
		$uid       = $_REQUEST['uid'];
		$societyId = $_REQUEST['sid'];
		$content    = $_REQUEST['ncontent'];
		if (isset($uid) && isset($societyId) && isset($content)) {
			$data['content'] = $content;
			$data['societyId'] = $societyId;
			$data['uid'] = $uid;
			$data['cTime'] = time();
			$data['isDel'] = 0;
			$result=D('SocietyWall','society')->addWall($data);
			if ($result>=1) {
				$re['status'] = 0;
				$re['statusCode'] = '连接成功';
				return $re;
			}else{
				$re['status'] = 2;
				$re['statusCode'] = '发布失败';
				return $re;
			}
		}else{
			$re['status'] = 2;
			$re['statusCode'] = '参数不完整';
			return $re;
		}
		
	}
	
	/**
	 * 发布公告
	 * @param
	 * @return
	 * @author ssq 14-02-22
	 */
	function pubnotice(){
		$uid       = $_REQUEST['uid'];
		$societyId = $_REQUEST['sid'];
		$notice    = $_REQUEST['ncontent'];
		if (isset($uid) && isset($societyId) && isset($notice)) {
			$data['notice'] = $notice;
			$result = D('Society','society')->settingSocietyBypara($data,$societyId);
			if ($result>=1) {
				$re['status'] = 0;
				$re['statusCode'] = '连接成功';
				return $re;
			}else{
				$re['status'] = 2;
				$re['statusCode'] = '发布失败';
				return $re;
			}
		}else{
			$re['status'] = 2;
			$re['statusCode'] = '参数不完整';
			return $re;
		}
	}
	
	/**
	 * 投票
	 * @return string
	 * @author ssq 14-02-22
	 */
	function vote(){
		$uid       = $_REQUEST['uid'];
		$societyId = $_REQUEST['sid'];
		$toUid     = $_REQUEST['vid'];
		if (isset($uid) && isset($societyId) && isset($toUid)) {
			$now = date('Y-m-d',time());
			$now = strtotime($now);
			$map['toUid'] = $toUid;
			$map['societyId'] = $societyId;
			$map['fromUid'] = $uid;
			$map['cTime'] = array('gt',$now);
			$return = D('SocietyVote','society')->getVoteCountByParam($map);
			if ($return>0) {
				$re['status'] = 2;
				$re['statusCode'] = '今天已投票';
				return $re;
			}else{
				$map['cTime'] = time();
				$result = D('SocietyVote','soceity')->vote($map);
				if ($result>=1) {
					$this->doReManager($societyId);
					$re['status'] = 0;
					$re['statusCode'] = '投票成功';
					return $re;
				}else{
					$re['status'] = 2;
					$re['statusCode'] = '投票失败';
					return $re;
				}
			}
		}else{
			$re['status'] = 2;
			$re['statusCode'] = '参数不完整';
			return $re;
		}
	}
	/**
	 * 竞选者
	 * @return string
	 * @author ssq 14-02-22
	 */
	function voter(){
		$uid       = $_REQUEST['uid'];
		$societyId = $_REQUEST['sid'];
		$keyword   = $_REQUEST['keyword'];
		$page      = $_REQUEST['page'];
		$num       = $_REQUEST['num'];
		if (isset($uid) && isset($societyId) && isset($page) && isset($num)) {
			$userInfo = get_user_by_uid ( getUcUid ( $uid ) ); // 获取用户社区信息
			$userInfo = $userInfo [0];
			$ucInfo   = get_baseinfo_by_uid ( getUcUid ( $uid ) ); // 获取用户UIA信息
			$params['id'] = $societyId;
			$params['isDel'] = 0;
			$societyInfo = D('Society','society')->getSocietyInfoBypara($params);
			$schoolid   = $ucInfo['schoolid'] ? $ucInfo['schoolid'] : 1;
			$type       = $societyInfo['type'];
			$yxid       = $societyInfo['typeid'];
			$zyid       = $societyInfo['typeid'];
			$bjid       = $societyInfo['typeid'];
			$nj         = $societyInfo['typeid'];
			if (!($keyword=='' && $keyword==null)) {
				$param['schoolid'] = $ucInfo['schoolid'] ? $ucInfo['schoolid'] : 1;
				$param['yxid']  = null;
		        $param['nj']    = null;
		        $param['zyid']  = null;
		       	$param['bjid']  = null;
		       	$param['depid'] = null;
		       	if($societyInfo['type']==1){
		       		$param['bjid'] = $societyInfo['typeid'];
		       	}
		       	if($societyInfo['type']==2){
		       		$param['zyid'] = $societyInfo['typeid'];
		       	}
		       	if($societyInfo['type']==3){
		       		$param['nj']   = $societyInfo['typeid'];
		       	}
		       	if($societyInfo['type']==4){
		       		$param['yxid'] = $societyInfo['typeid'];
		       	}
		       	if($societyInfo['type']==5){
		       		$param['depid'] = $societyInfo['typeid'];
		       	}
				$memberList = D('UserOf','society')->getSocietyByparaNoPage($keyword);
				foreach ($memberList as $v){
					$reg_member[]= $v['uid'];
				}
				$map['uid']  = array('in',$reg_member);
				$regMember = M('ucenter_user_link')->where($map)->field('uc_uid')->select();
				$uidString = '-1';
				foreach ($regMember as $value) {
					$uidString = $uidString.','.$value['uc_uid'];
				}
				$param['uid'] = $uidString;
				$memberList = getUserByUserNameANDParam($param);
				unset($reg_member);
				foreach ($memberList as $v){
					$reg_member[]= $v['uid'];
				}
				$map['uc_uid']  = array('in',$reg_member);
				unset($regMember);
				$count = M('ucenter_user_link')->where($map)->count();
				$limit = ($page-1)*$num;
				$limit = $limit.",".$num;
				$regMember = M('ucenter_user_link')->where($map)->field('uid')->limit($limit)->select();
			}else{
				$memberList = array();
				switch($type){
					case '1': //班级
						$memberLists = get_Student_baseinfo_by_org($schoolid,null,null,$bjid,null,1,$num,$page);
						$memberList = $memberLists[0];
						foreach ($memberList['data'] as $v){
							$reg_member[]= $v['uid'];
						}
						$map['uc_uid']  = array('in',$reg_member);
						$regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
						break;
					case '2': //专业
						$memberLists = get_Student_baseinfo_by_org($schoolid,null,$zyid,null,null,1,$num,$page);
						$memberList = $memberLists[0];
						foreach ($memberList['data'] as $v){
							$reg_member[]= $v['uid'];
						}
						$map['uc_uid']  = array('in',$reg_member);
						$regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
						break;
					case '3'://年级
						$memberLists = get_Student_baseinfo_by_org($schoolid,null,null,null,$nj,1,$num,$page);
						$memberList = $memberLists[0];
						foreach ($memberList['data'] as $v){
							$reg_member[]= $v['uid'];
						}
						$map['uc_uid']  = array('in',$reg_member);
						$regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
						break;
					case '4'://院系
						$memberLists = get_Student_baseinfo_by_org($schoolid,$yxid,null,null,null,1,$num,$page);
						$memberList = $memberLists[0];
						foreach ($memberList['data'] as $v){
							$reg_member[]= $v['uid'];
						}
						$map['uc_uid']  = array('in',$reg_member);
						$regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
						break;
					case '5'://部门
						$memberLists = get_teacher_same_dept_includeMe($societyInfo['typeid'],($page-1),$num,1);
						$memberList = $memberLists[0];
						foreach ($memberList['data'] as $v){
							$reg_member[]= $v['uid'];
						}
						$map['uc_uid']  = array('in',$reg_member);
						$regMember = M('ucenter_user_link')->where($map)->field('uid')->select();
						break;
				}
			}
			$result = D('SocietyVote','society')->getVoteCountBySocietyId($societyId);
			$max = '';
			for ($i = 0; $i < count($result); $i++) {
				if ($result[$i]['count(toUid)']>$max) {
					$max = $result[$i]['count(toUid)'];
				}
			}
			$list = array();
			for ($i = 0; $i < count($result); $i++) {
				if ($result[$i]['count(toUid)']==$max) {
					$list[] = $result[$i]['toUid'];
				}
			}
			foreach ($regMember as $key => $value) {
				$regMember[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) :getUserEmail($value['uid']);
				$regMember[$key]['uhead'] = getUserFace($value['uid'],'m');
				$regMember[$key]['voted'] = $this->voteYesOrNo($value['uid'], $societyId,$uid);
				$regMember[$key]['votesum'] = $this->getVote($value['uid'], $societyId);
				if (in_array($value['uid'] , $list)) {
					$regMember[$key]['votewin'] = 1;
				}else{
					$regMember[$key]['votewin'] = 0;
				}
			}
			if ($regMember) {
				$re['status'] = 0;
				$re['statusCode'] = '连接成功';
				$re['data'] = $regMember;
				return $re;
			}else{
				$re['status'] = 2;
				$re['statusCode'] = 'end';
				$re['data'] = array();
				return $re;
			}
		}else{
			$re['status'] = 2;
			$re['statusCode'] = '参数不完整';
			$re['data'] = array();
			return $re;
		}
	}
	/**
	 * 获取圈子访问人员
	 * @return multitype:|Ambigous <multitype:, >|Ambigous <multitype:, unknown, >
	 * @author ssq 14-02-22
	 */
	function visitor(){
		$societyId = $_REQUEST['sid'];
		$status    = $_REQUEST['vtype'];
		$page      = $_REQUEST['page'];
		$num       = $_REQUEST['num'];
		if (isset($societyId) && isset($status) && isset($page) && isset($num)) {
			$visitorModel  = D('SocietyVisitor','society');
			$startTime = strtotime(date('Y-m-d',time()));
			if($status==1){
				$memberVisitor = $visitorModel->getMemVisitorByPageForApi($societyId,1,$page,$num);
				$member    = $visitorModel->getVisitorNum($societyId,1,$startTime);
				if (($member['allTimes']==''||$member['allTimes']==null)) {
					$member['allTimes'] = '0';
				}
			}else{
				$memberVisitor  = $visitorModel->getMemVisitorByPageForApi($societyId,0,$page,$num);
				$member     = $visitorModel->getVisitorNum($societyId,0,$startTime);
				if (($member['allTimes']==''||$member['allTimes']==null)) {
					$other['allTimes'] = '0';
				}
			}
			if ($memberVisitor) {
				foreach ($memberVisitor as $key => $value) {
					$memberVisitors[$key]['uid'] = $value['uid']; 
					$memberVisitors[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) :getUserEmail($value['uid']);
					$memberVisitors[$key]['uhead'] = getUserFace($value['uid'],'m');
					$memberVisitors[$key]['vtime'] = date("Y-m-d H:i",$value['cTime']);
				}
				$re['status'] = 0;
				$re['statusCode'] = '连接成功';
				$re['vsum'] = $member['allTimes'];
				$re['vtodaysum'] = $member['todayTimes'];
				$re['data'] = $memberVisitors;
				return $re;
			}else{
				$re['status'] = 2;
				$re['statusCode'] = 'end';
				$re['vsum'] = $member['allTimes'];
				$re['vtodaysum'] = $member['todayTimes'];
				$re['data'] = array();
				return $re;
			}
		}else{
			$re['status'] = 2;
			$re['statusCode'] = '参数不完整';
			$re['vsum'] = 0;
			$re['vtodaysum'] = 0;
			$re['data'] = array();
			return $re;
		}
	}
	/**
	 * 获取圈子印象
	 * @return multitype:|Ambigous <multitype:, >|Ambigous <multitype:, unknown, >
	 * @author ssq 14-02-22
	 */
	function impression(){
		$societyId = $_REQUEST['sid'];
		$page      = $_REQUEST['page'];
		$num       = $_REQUEST['num'];
		if (isset($societyId) && isset($page) && isset($num)) {
			$wallList=D('SocietyWall','society')->getSocietyWallBySocietyidForApi($societyId,$page,$num);
			if($wallList){
				foreach ($wallList as $key => $value) {
					$walls[$key]['imstr'] = $value['content'];
					$walls[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) :getUserEmail($value['uid']);
					$walls[$key]['uhead'] = getUserFace($value['uid'],'m');
					$walls[$key]['itime'] = date("Y-m-d H:i",$value['cTime']);
					unset($walls[$key]['cTime']);
					unset($walls[$key]['content']);
					unset($walls[$key]['societyId']);
					unset($walls[$key]['isDel']);
				}
				$re['status'] = 0;
				$re['statusCode'] = '连接成功';
				$re['data'] = $walls;
				return $re;
			}else{
				$re['status'] = 2;
				$re['statusCode'] = 'end';
				$re['data'] = array();
				return $re;
			}
		}else{
			$re['status'] = 2;
			$re['statusCode'] = '参数不完整';
			$re['data'] = array();
			return $re;
		}
	}
	
	/**
	 * 获取圈子成员图片
	 * @return multitype:|Ambigous <multitype:, >|Ambigous <multitype:, unknown, >
	 * @author ssq 14-02-22
	 */
	function photolist(){
		$societyId = $_REQUEST['sid'];
		$page      = $_REQUEST['page'];
		$num       = $_REQUEST['num'];
		$arrayKey  = array(
				'uid'=>'userId',
				'uname'=>'uname',
				'uhead'=>'uhead',
				'pid'=>'id',
				'ppath'=>'savepath',
				'pname'=>'name',
				'ptime'=>'cTime',
				'preplycount'=>'commentCount'
		);
		if (isset($societyId) && isset($page) && isset($num)) {
			$params['id'] = $societyId;
			$params['isDel'] = 0;
			$societyInfo = D('Society','society')->getSocietyInfoBypara($params);
			switch($societyInfo['type']){
				case '0':
					$memberList=D('SocietyMember','society')->getMemberBySocietyid($societyId);
					$uidstr = '-1';
					foreach($memberList as $k=>$v){
						$uidstr .=','.$v['uid'];
					}
					$param['uidstr'] = ltrim($uidstr,',');
					$photoList=D('Photo','photo')->getPhotoForSocietyForApi($param,$page,$num);
					break;
				case '1':
					$param['bjid'] = $societyInfo['typeid'];
					$photoList=D('Photo','photo')->getPhotoForSocietyForApi($param,$page,$num);
					break;
				case '2':
					$param['zyid'] = $societyInfo['typeid'];
					$photoList=D('Photo','photo')->getPhotoForSocietyForApi($param,$page,$num);
					break;
				case '3':
					$param['nj'] = $societyInfo['typeid'];
					$photoList=D('Photo','photo')->getPhotoForSocietyForApi($param,$page,$num);
					break;
				case '4':
					$param['yxid'] = $societyInfo['typeid'];
					$photoList=D('Photo','photo')->getPhotoForSocietyForApi($param,$page,$num);
					break;
				case '5':
					$param['depid'] = $societyInfo['typeid'];
					$photoList=D('Photo','photo')->getPhotoForSocietyForApi($param,$page,$num);
					break;
			}
			$blogLists = $photoList;
			unset($photoList);
			$photoList = $blogLists;
			if ($photoList==null || $photoList=='') {
				$re['status'] = 2;
				$re['statusCode'] = 'end';
				$re['data'] = array();
				return $re;
			}else{
				foreach ($photoList as $key => $value) {
					$photoList[$key]['uname'] = getUserName($value['userId']) ? getUserName($value['userId']) :getUserEmail($value['userId']);
					$photoList[$key]['uhead'] = getUserFace($value['userId'],'m');
					$photoList[$key]['cTime'] = date("Y-m-d H:i",$value['cTime']);
					$photoList[$key]['savepath'] = SITE_URL.'/data/uploads/'.$photoList[$key]['savepath'];
					$result[$key] = getApiArray($photoList[$key], $arrayKey);
				}
				if ($result) {
					$re['status'] = 0;
					$re['statusCode'] = '连接成功';
					$re['data'] = $result;
					return $re;
				}else{
					$re['status'] = 2;
					$re['statusCode'] = '连接失败';
					$re['data'] = $result;
					return $re;
				}
			}
		}else{
			$re['status'] = 2;
			$re['statusCode'] = '参数不完整';
			$re['data'] = array();
			return $re;
		}
	}
	
	function member(){
		$uid     = $_REQUEST['uid'];
		$sid     = $_REQUEST['sid'];
		$manager = $_REQUEST['stype']; //1管理员   2普通成员
		$reg     = $_REQUEST['sreg'];  //0 未注册  1 注册     不是必传参数
		$page    = $_REQUEST['page'];
		$num     = $_REQUEST['num'];
		if (!isset($uid) || !isset($sid) || !isset($page) || !isset($num)) {
			$return['status']     = 2;
			$return['statusCode'] = '传入参数不完整';
			$return['data']       = array();
			return $return;
			exit();
		}
		$society = D('Society','society')->getSocietyInfoBypara(array('id'=>$sid,'isDel'=>0));
		if (!isset($society)) {
			$return['status']     = 2;
			$return['statusCode'] = '没有该圈子信息';
			$return['data']       = array();
			return $return;
			exit();
		}
		$ucInfo   = get_baseinfo_by_uid ( getUcUid ( $uid ) ); // 获取用户UIA信息
		$schoolid = $ucInfo['schoolid'] ? $ucInfo['schoolid'] : 1;
		$param['societyId'] = $sid;
		$param['isDel'] = 0;
		$param['status'] =  array('eq',1);
		switch ($society['type']) {
			case 1:
				if ($reg==1) { //注册
					$results = $this->getUserByStudentInfo($schoolid, null, null, $society['typeid'], null, 1, $num, $page);
					$result = $results['data'];
					foreach ($result as $key => $value) {
						$result[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) : getUserEmail($value['uid']);
						$result[$key]['uhead'] = getUserFace($value['uid'],'m') ;
					}
				}else if($reg==0){  //未注册
					$result = $this->getNotRegByStudentInfo($schoolid, null, null, $society['typeid'], null, 2, $num, $page);
				}
				break;
			case 2:
				if ($reg==1) { //注册
					$results = $this->getUserByStudentInfo($schoolid, null, $society['typeid'], null, null, 1, $num, $page);
					$result = $results['data'];
					foreach ($result as $key => $value) {
						$result[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) : getUserEmail($value['uid']);
						$result[$key]['uhead'] = getUserFace($value['uid'],'m') ;
					}
				}else if($reg==0){  //未注册
					$result = $this->getNotRegByStudentInfo($schoolid, null, $society['typeid'], null, null, 2, $num, $page);
				}
				break;
			case 3:
				if ($reg==1) { //注册
					$results = $this->getUserByStudentInfo($schoolid, null, null, null, $society['typeid'], 1, $num, $page);
					$result = $results['data'];
					foreach ($result as $key => $value) {
						$result[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) : getUserEmail($value['uid']);
						$result[$key]['uhead'] = getUserFace($value['uid'],'m') ;
					}
				}else if($reg==0){  //未注册
					$result = $this->getNotRegByStudentInfo($schoolid, null, null, null, $society['typeid'], 2, $num, $page);
				}
				break;
			case 4:
				if ($reg==1) { //注册
					$results = $this->getUserByStudentInfo($schoolid, $society['typeid'], null, null, null, 1, $num, $page);
					$result = $results['data'];
					foreach ($result as $key => $value) {
						$result[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) : getUserEmail($value['uid']);
						$result[$key]['uhead'] = getUserFace($value['uid'],'m') ;
					}
				}else if($reg==0){  //未注册
					$result = $this->getNotRegByStudentInfo($schoolid, $society['typeid'], null, null, null, 2, $num, $page);
				}
				break;
				
			case 5:
				if ($reg==1) { //注册
					$memberLists = get_teacher_same_dept_includeMe($society['typeid'],($page-1),$num ,1);
					$memberList = $memberLists[0];
					foreach ($memberList['data'] as $v){
						$reg_member[]= $v['uid'];
					}
					$map['uc_uid']  = array('in',$reg_member);
					$result = M('ucenter_user_link')->where($map)->field('uid')->select();
					foreach ($result as $key => $value) {
						$result[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) : getUserEmail($value['uid']);
						$result[$key]['uhead'] = getUserFace($value['uid'],'m') ;
					}
				}else if($reg==0){  //未注册
					$memberLists = get_teacher_same_dept_includeMe($society['typeid'],($page-1),$num ,2);
	                $memberList = $memberLists[0]['data'];
	                $array = array(
	                		'uid'   =>'identityid',
	                		'uname' =>'xm',
	                		'uhead' =>'uhead'
	                );
	                foreach ($memberList as $key => $value) {
	                	$value['uhead'] = '';
	                	$result[$key] = getApiArray($value, $array);
	                }
				}
				break;
			case 0:
				unset($param);
				$param['societyId'] = $sid;
				$param['isDel'] = 0;
				$result = D('SocietyMember','society')->getMemberBySocietyidPage($param ,$page, $num);
				foreach ($result as $key => $value) {
					$result[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) : getUserEmail($value['uid']);
					$result[$key]['uhead'] = getUserFace($value['uid'],'m') ;
				}
				break;
		}
		if ($result) {
			$return['status']     = 0;
			$return['statusCode'] = '连接成功';
			$return['data']       = $result;
			return $return;
			exit();
		}else{
			$return['status']     = 2;
			$return['statusCode'] = 'end';
			$return['data']       = array();
			return $return;
			exit();
		}
	}
	
	/**
	 * 获取圈子公告
	 * @return string|multitype:unknown
	 */
	function getnotice(){
		$sid = $_REQUEST['sid'];
		$society = D('Society','society')->getSocietyInfoBypara(array('id'=>$sid,'isDel'=>0));
		if (!isset($society)) {
			$return['status']     = 2;
			$return['statusCode'] = '没有该圈子信息';
			return $return;
			exit();
		}else{
			$return['status']     = 0;
			$return['statusCode'] = '连接成功';
			$return['data'] = array('sid'=>$society['id'],'snotice'=>$society['notice']);
			return $return;
			exit();
		}
	}
	
	/**
	 * 发表消息 
	 * @return string
	 * @author ssq 2014-02-29
	 */
	function postnews(){
		$sid      = $_REQUEST['sid'];
		$fid      = $_REQUEST['fid'];
		$tid      = $_REQUEST['tid'];
		$newstype = $_REQUEST['newstype'];
		$result   = $_REQUEST['result'];
		
		$param['societyId'] = $sid;
		$param['fromUid']   = $fid;
		if($tid!='' && $tid!=null){
			$param['toUid'] = $tid;
		}
		$param['newsType']  = $newstype;
		$param['result']    = $result;
		$param['cTime']     = time();
		$param['isDel']     = 0;
		$re = D('SocietyNews','society')->addNews($param);
		if($re>=1){
			$return['status']     = 0;
			$return['statusCode'] = '发送成功';
			return $return;
		}else{
			$return['status']     = 2;
			$return['statusCode'] = '发送失败';
			return $return;
		}
	}
	
	/**
	 * 删除消息
	 * @return string
	 * @author ssq 2014-02-29
	 */
	function delnews(){
		$nid = $_REQUEST['nid'];
		$result = D('SocietyNews','society')->deleteNews($nid);
		if($result>=1){
			$return['status']     = 0;
			$return['statusCode'] = '删除成功';
			return $return;
		}else{
			$return['status']     = 2;
			$return['statusCode'] = '删除失败';
			return $return;
		}
	}
	

	/**
	 * 更改消息
	 * @return string
	 * @author ssq 2014-02-29
	 */
	function updatenews(){
		$nid      = $_REQUEST['nid'];
		$fid      = $_REQUEST['fid'];
		$tid      = $_REQUEST['tid'];
		$newstype = $_REQUEST['newstype'];
		$result   = $_REQUEST['result'];
		
		if($fid!='' && $fid!=null){
			$param['fromUid'] = $fid;
		}
		if($tid!='' && $tid!=null){
			$param['toUid'] = $tid;
		}
		if($newstype!='' && $newstype!=null){
			$param['newsType'] = $newstype;
		}
		if($result!='' && $result!=null){
			$param['result'] = $result;
		}
		$param['cTime']     = time();
		$re = D('SocietyNews','society')->where('newsId='.$nid)->save($param);;
		if($re>=1){
			$return['status']     = 0;
			$return['statusCode'] = '修改成功';
			return $return;
		}else{
			$return['status']     = 2;
			$return['statusCode'] = '修改失败';
			return $return;
		}
	}
	
	/**
	 * 获取消息
	 * @return string
	 * @author ssq 2014-02-29 
	 */
	function getnews(){
		$nid      = $_REQUEST['nid'];
		$sid      = $_REQUEST['sid'];
		$fid      = $_REQUEST['fid'];
		$tid      = $_REQUEST['tid'];
		$newstype = $_REQUEST['newstype'];
		$result   = $_REQUEST['result'];
		$page     = $_REQUEST['page'];
		$num      = $_REQUEST['num'];
		if($page=='' || $page==null || $num=='' || $num==null){
			$return['status']     = 2;
			$return['statusCode'] = '参数不完整';
			$return['data'] = array();
			return $return;
		}
		if($nid!='' && $nid!=null){
			$param['n.newsId'] = $nid;
		}
		if($sid!='' && $sid!=null){
			$param['n.societyId'] = $sid;
		}
		if($fid!='' && $fid!=null){
			$param['n.fromUid'] = $fid;
		}
		if($tid!='' && $tid!=null){
			$param['n.toUid'] = $tid;
		}
		if($newstype!='' && $newstype!=null){
			$param['n.newsType'] = $newstype;
		}
		if($result!='' && $result!=null){
			$param['n.result'] = $result;
		}
		$param['n.isDel'] = 0;
		$limit = ($page-1)*$num;
		$limit = $limit.",".$num;
		$result = M('SocietyNews n')
				->join('ts_society s on n.societyId=s.id')
				->where($param)
				->limit($limit)
				->order('n.cTime')
				->field('*,n.cTime as cTime')
				->select();
		$arrayKey  = array(
				'nid'       => 'newsId',
				'sid'       => 'societyId',
				'sname'     => 'societyName',
				'sicon'     => 'icon',
				'visitable' => 'visitable',
				'fid'       => 'fromUid',
				'fname'     => 'fname',
				'fhead'     => 'fhead',
				'tid'       => 'toUid',
				'tname'     => 'tname',
				'thead'     => 'thead',
				'newstype'  => 'newsType',
				'result'    => 'result',
				'ntime'     => 'cTime'
		);
		if ($result) {
			$return['status']     = 0;
			$return['statusCode'] = '连接成功';
			foreach ($result as $key => $value) {
				$result[$key]['icon'] = SITE_URL.$this->get_photo_url($result[$key]['icon']);
				$result[$key]['fname'] = getUserName($value['fromUid']) ? getUserName($value['fromUid']) : getUserEmail($value['fromUid']);
				$result[$key]['fhead'] = getUserFace($value['fromUid']);
				$result[$key]['tname'] = getUserName($value['toUid']) ? getUserName($value['toUid']) : getUserEmail($value['toUid']);
				$result[$key]['thead'] = getUserFace($value['toUid']);
				$result[$key]['cTime'] = date("Y-m-d H:i",$value['cTime']);
				$return['data'][] = getApiArray($result[$key], $arrayKey);
			}
			return $return;
		}else{
			$return['status']     = 2;
			$return['statusCode'] = 'end';
			$return['data'] = array();
			return $return;
		}
	}
		
	
	
	//获取票额总数
	private function getVote($uid,$societyId) {
		$param['toUid']     = $uid;
		$param['societyId'] = $societyId;
		$return = D('SocietyVote','society')->getVoteCountByParam($param);
		return $return;
	}
	
	//今天是否投票
	private function voteYesOrNo($toUid,$societyId,$uid) {
		$param['toUid']     = $toUid;
		$param['fromUid']   = $uid;
		$param['societyId'] = $societyId;
		$now = date('Y-m-d',time());
		$now = strtotime($now);
		$param['cTime'] = array('gt',$now);
		$return = D('SocietyVote','society')->getVoteCountByParam($param);
		if ($return>0) {
			return 1;
		}else{
			return 0;
		}
	}
	
	/**
	 * 重新命名管理员
	 * @param unknown $societyId
	 */
	private function doReManager($societyId){
		$result = D('SocietyVote','society')->getVoteCountBySocietyId($societyId);
		$max = '';
		for ($i = 0; $i < count($result); $i++) {
			if ($result[$i]['count(toUid)']>$max) {
				$max = $result[$i]['count(toUid)'];
			}
		}
		$list = array();
		for ($i = 0; $i < count($result); $i++) {
			if ($result[$i]['count(toUid)']==$max) {
				$list[] = $result[$i];
			}
		}
		D('SocietyMember','society')->delMember(array('societyId'=>$societyId,'uid'=>array('NEQ','0')));
		$i = 1;
		foreach ($list as $value) {
			$param['societyId'] = $societyId;
			$param['uid'] = $value['toUid'];
			$member = D('SocietyMember','society')->getSocietyInfoByParam($param);
			if ($member) {
				D('SocietyMember','society')->memberManager($param,1);
			}else{
				$param['status'] = 1;
				D('SocietyMember','society')->addMemberBySocietyid_Uid($param);
			}
		}
	}
	
	/**
	 * 获取帖子第一张图片 
	 * @param unknown $topicList
	 * @return unknown
	 */
	private function doTopicPic($topic){
		unset($imgArr);
		preg_match_all("/\\<img.*?src\\=\"(.*?)\"[^>]*>/i", stripslashes($topic['content']), $imgArr);
		return isset($imgArr[1][0]) ? $imgArr[1][0] : '';
	}
	
	/**
	 * 获取圈子新鲜事
	 * ------------------------------------
	 * @param int societyId 圈子ID
	 * @return array 圈子新鲜事
	 * @author  ssq  14-02-11
	 * ------------------------------------
	 */
	private function getSocietyMessage($societyId,$count){
		$result = D('SocietyMessage','society')->getSocietyMessagesBySocietyid($societyId,$count);
		return $result;
	}
	
	/**
	 * 获取圈子管理员
	 * ------------------------------------
	 * @param int societyId 圈子ID
	 * @return string 管理员字符窜 逗号隔开
	 * @author  ssq  14-02-11
	 * ------------------------------------
	 */
	private function getSocietyManager($societyId){
		$result= D('SocietyMember','society')->getManagBySocietyid($societyId);
		$managers = array();
		foreach ($result as $key => $value) {
			if ($value['uid']!=0) {
				$managers[] = $value;
			}
		}
		$managerList = '';
		foreach ($managers as $key => $value) {
			$managerList = $managerList.$value['uid'].',';
		}
		if(strlen($managerList)>=1){
			$managerList = substr($managerList,0,strlen($managerList)-1);
		}
		return $managerList;
	}
	
	/**
	 * 数组重定义排序
	 * @param array  需要排序的数组
	 * @param string  排序关键字
	 * @param string asc顺序  desc倒序
	 * @return new array
	 * @author ssq 14-02-10
	 */
	private function array_sort($arr,$keys,$type='asc'){
		$keysvalue = $new_array = array();
		foreach ($arr as $k=>$v){
			$keysvalue[$k] = $v[$keys];
		}
		if($type == 'asc'){
			asort($keysvalue);
		}else{
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k=>$v){
			$new_array[$k] = $arr[$k];
		}
		return $new_array;
	}
	/**
	 * 创建校方圈子
	 * --------------------------------
	 * @param array 创建圈子的信息数组
	 * @return id 当前插入记录的id
	 * @author ssq 14-02-10
	 * --------------------------------
	 */
	private function addSchoolSociety($param) {
		$param['icon']         = 'default.gif';
		$param['tags']         = '青春,活力,奉献';
		$param['sign']         = '不管遇到什么障碍，我都要朝着我的目标前进。';
		$param['visitable']    = 1;
		$param['joinable']     = 1;
		$param['downloadable'] = 1;
		$param['cTime']        = time();
		$param['idDel']        = 0;
		$societyId = D('Society','society')->addSocietyInfoBypara($param);
		return $societyId;
	}
	
	/**
	 * 预处理圈子 查看所属校方圈子，没有的话新建
	 * ---------------------------------
	 * @param 用户UID
	 * @return array 校方圈子集合
	 * @author ssq 14-02-10
	 * ---------------------------------
	 */
	private function openSchoolSociety($uid, $identitytype, $ucInfo) {
		$schoolSocety = array ();
		switch ($identitytype) {
			case 1 : // 管理员
				break;
			case 2 : // 老师
				$param ['typeid'] = $ucInfo ['depid'];
				$param ['type']   = 5;
				$param ['isDel']  = 0;
				$schoolSocety = D ( 'Society', 'society' )->getSocietyInfoBypara ( $param );
				if (!isset($schoolSocety)) {
					$param['societyName']  = $ucInfo ['departname'];
					$societyId = $this->addSchoolSociety($param);
					if(!isset($societyId)){
						$schoolSocety = array ();
					}else{
						$schoolSocety = D ( 'Society', 'society' )->getSocietyInfoBypara ( $param );
						//插入圈子成员创建者
						$data['societyId'] = $societyId;
						$data['uid']       = 0;
						$data['cTime']     = time();
						$data['status']    = '2';
						$data['isDel']     = '0';
						D('SocietyMember','society')->addMemberBySocietyid_Uid($data);
					}
				}
				$schoolSocetyTemp = $schoolSocety;
				unset($schoolSocety);
				$schoolSocety[0] = $schoolSocetyTemp;
				break;
			case 3 : // 学生
				for ($i = 1; $i < 5; $i++) {
					$param = array();
					switch ($i) {
						case 1:
							$societyName = $ucInfo ['bm'];
							$param ['typeid'] = $ucInfo ['bjid'];
						break;
						case 2:
							$societyName = $ucInfo ['zymc'];
							$param ['typeid'] = $ucInfo ['zyid'];
						break;
						case 3:
							$societyName = $ucInfo ['nj'];
							$param ['typeid'] = $ucInfo ['nj'];
						break;
						case 4:
							$societyName = $ucInfo ['yxmc'];
							$param ['typeid'] = $ucInfo ['yxid'];
						break;
					}
					$param ['type']   = $i;
					$param ['isDel']  = 0;
					$schoolSocetyPer = D ( 'Society', 'society' )->getSocietyInfoBypara ( $param );
					if (!isset($schoolSocetyPer)) {
						$param['societyName']  = $societyName;
						if ($societyName && $param ['typeid']) {
							$societyId = $this->addSchoolSociety($param);
							if(!isset($societyId)){
								$schoolSocetyPer = array ();
							}else{
								$schoolSocetyPer = D ( 'Society', 'society' )->getSocietyInfoBypara ( $param );
								//插入圈子成员创建者
								$data['societyId'] = $societyId;
								$data['uid']       = 0;
								$data['cTime']     = time();
								$data['status']    = '2';
								$data['isDel']     = '0';
								D('SocietyMember','society')->addMemberBySocietyid_Uid($data);
							}
						}else{
							$schoolSocetyPer = null;
						}
					}
					if (isset($schoolSocetyPer)) {
						$schoolSocety[] = $schoolSocetyPer;
					}
				}
				break;
			case 4 : // 家长
				break;
			case 5 : // 校友
				break;
			default :
				break;
		}
		return $schoolSocety;
	}

	//是否申请加入该圈子结果
	private function getResultOfApply($societyId,$uid){
		$yesOrNo = D('SocietyMember','society')->checkMemberBySociety($societyId,$uid);
		if($yesOrNo==1){
			return 1;
		}else{
			return 0;
		}
	}
	
	/**
	 * 不同类型 获取已注册用户
	 * @param
	 * @return
	 * @author ssq 14-02-21
	 */
	private function getUserByStudentInfo($schoolid, $yxid, $zyid, $bjid, $nj, $isReg, $num, $page){
		$lists = get_Student_baseinfo_by_org($schoolid, $yxid, $zyid, $bjid, $nj, $isReg, $num, $page);
		$total = $lists[0]['totalcount']; //总条数
		if (($page-1)*$num>=$total) {
			$return['status']     = 2;
			$return['statusCode'] = 'end';
			$return['data']       = array();
			return $return;
		}
		$list = get_Student_baseinfo_by_org($schoolid, $yxid, $zyid, $bjid, $nj, $isReg, $num, $page);
		$userList = $list[0]['data'];
		foreach ($userList as $v){
			$reg_member[]= $v['uid'];
		}
		$map['uc_uid']  = array('in',$reg_member);
		$userList = array();
		$userList = M('ucenter_user_link')->where($map)->field('uid')->select();
		foreach ($userList as $key=>$value) {
			$userList[$key]['uid'] = $value['uid'];
			$userList[$key]['uname'] = getUserName($value['uid']) ? getUserName($value['uid']) : getUserEmail($value['uid']);
			$userList[$key]['uhead'] = getUserFace($value['uid'], 'm');
		}
		$return['status']     = 0;
		$return['statusCode'] = '连接成功';
		$return['data']       = $userList;
		return $return;
	}
	
	/**
	 * 不同类型 获取未注册用户
	 * @param
	 * @return
	 * @author ssq 14-02-21
	 */
	private function getNotRegByStudentInfo($schoolid, $yxid, $zyid, $bjid, $nj, $isReg, $num, $page){
		$array = array(
				'uid'   =>'identityid',
				'uname' =>'xm',
				'uhead' =>'uhead'
		);
		$list = get_Student_baseinfo_by_org($schoolid, $yxid, $zyid, $bjid, $nj, $isReg, $num, $page);
		$userList = $list[0]['data'];
		foreach ($userList as $key => $value) {
			$value['uhead'] = '';
			$userLists[$key] = getApiArray($value, $array);
		}
		return $userLists;
	}
}
?>