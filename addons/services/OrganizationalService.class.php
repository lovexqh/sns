<?php
class OrganizationalService extends Service {
	
	/**
	 * +----------------------------------------------------------
	 * + 获取用户所在院系的所有学生信息
	 * +----------------------------------------------------------
	 * + @param	@param unknown $uid
	 * + @return	return_type
	 * + @author	lihao
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-11-20 下午2:41:30
	 * +----------------------------------------------------------
	 */
	public function getStudentTreeByYxid($schoolid, $yxid) {
		if(! $schoolid && ! $yxid){
			return null;
		}
		//获取学生信息
		$StudentList = get_Student_baseinfo_by_yxid($schoolid, $yxid);
		return $StudentList;
	}
	
	/**
	 * +----------------------------------------------------------
	 * + 获取用户所在学校的所有班级信息
	 * +----------------------------------------------------------
	 * + @param	@param unknown $uid
	 * + @return	return_type
	 * + @author	lihao
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-11-20 下午2:41:30
	 * +----------------------------------------------------------
	 */
	public function getClassTreeByYxid($schoolid, $yxid) {
		if(! $schoolid && ! $yxid){
			return null;
		}
		$classTree = get_Class_by_yxid($schoolid, $yxid);
		return $classTree;
	}
	
	/**
	 * +----------------------------------------------------------
	 * + 获取用户所在院系的所有专业信息
	 * +----------------------------------------------------------
	 * + @param	@param unknown $uid
	 * + @return	return_type
	 * + @author	lihao
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-11-20 下午2:41:30
	 * +----------------------------------------------------------
	 */
	public function getSpecialtyByUid($schoolid, $yxid){
		if(!$schoolid && !$yxid){
			return null;
		}
		//获取该用户所在学院的专业信息
		$specialtyTree = get_Specialty_by_uid($schoolid, $yxid);
		return $specialtyTree;
	}
	
	/**
	 * +----------------------------------------------------------
	 * + 获取用户所在的院系信息
	 * +----------------------------------------------------------
	 * + @param	@param unknown $uid
	 * + @return	return_type
	 * + @author	lihao
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-11-20 下午2:41:30
	 * +----------------------------------------------------------
	 */
	public function getAcademyByUid($schoolid, $uid) {
		if (! $schoolid && !$uid) {
			return null;
		}
		$academy = get_Academy_by_uid($schoolid, $uid);
		return $academy;
	}
	
	/**
	 * +----------------------------------------------------------
	 * + 获取用户组织信息
	 * +----------------------------------------------------------
	 * + @param	@param unknown $uid
	 * + @return	return_type
	 * + @author	小波 (Administrator)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-10-25 下午2:41:30
	 * +----------------------------------------------------------
	 */
	public function getTreeBySchoolId($schoolid) {
		if (! $schoolid) {
			return null;
		}
		// 获取某学校下所有部门
		$depttree = get_detptree_by_schoolid ( $schoolid );
		return $depttree;
	}
	/**
	 * +----------------------------------------------------------
	 * + 获取用户列表信息
	 * +----------------------------------------------------------
	 * + @param	@param unknown $schoolid
	 * + @return	分组后数据
	 * + @author	曹飞 (Administrator)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-10-29 上午10：20：16
	 * +----------------------------------------------------------
	 */
	public function getListBySchoolId($schoolid, $isdept=true) {
		if (! $schoolid) {
			return null;
		}
		// 获取某学校下所有部门以及人员信息
		$deptlist = get_deptinfo_by_schoolid ( $schoolid );
		
		if($isdept){
    		// 添加头像数据，进行分组
    		foreach ( $deptlist as $key => $value ) {
    			if (! empty ( $value ['departname'] )) {
    				// 判断是否注册，查询头像路径
    				if (! empty ( $value ['uid'] )) {
    					$data = $this->get_user_face ( $value ['uid'] );
    					$value ['imgsrc'] = $data ['img'];
    					$value ['uc_uid'] = $value ['uid'];
    					$value ['uid'] = $data ['uid'];
    				} else {
    					$value ['uc_uid'] = $value ['uid'];
    					$value ['uid'] = '';
    					$value ['imgsrc'] = 'http://127.0.0.1/dsk/public/themes/edustyle/images/user_pic_middle.gif';
    				}
    				$result [$value ['deptid']] [$key] = $value;
    				$result [$value ['deptid']] ['title'] = $value ['departname'];
    			}
    		}
		}else{
		    $result = $deptlist;
		}
		return $result;
	}
	/**
	 * +----------------------------------------------------------
	 * + 根据uc_uid获取用户头像信息
	 * +----------------------------------------------------------
	 * + @param	@param unknown $uc_uid
	 * + @return	string imgsrc
	 * + @author	曹飞 (Administrator)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-10-30 下午13:26:10
	 * +----------------------------------------------------------
	 */
	private function get_user_face($uc_uid) {
		// 通过uc_uid查询uid
		$uid = M ( 'ucenter_user_link' )->field ( 'uid' )->where ( 'uc_uid=' . $uc_uid )->find ();
		if (! empty ( $uid )) {
			// 根据uid取头像地址
			$data ['img'] = getUserFace ( $uid );
		}
		$data ['uid'] = $uid;
		return $data;
	}
	/**
	 +----------------------------------------------------------
	 + 私有方法，为UIA获取到的数据完善uid及头像等信息
	 +----------------------------------------------------------
	 + @param	@param unknown $data
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-4 下午4:38:27
	 +----------------------------------------------------------
	 */
	private function parseData($data, $key, $idtype){
	    //判断缓存是否存在
	    $skey = $key.'_'.count($data);
	    $result = S($skey);
	    if(!empty($result)) return $result;
	     
	    //优化大批量查询，讲数据切割处理，每次查询100条
	    $data_split	=	array_chunk($data, 100)  ;
	    foreach ($data_split as $data_chunck){
	        foreach ($data_chunck as $k=>$v){
	            if(!isset($v['uid'])) continue;
	            $uid_in[] = $v['uid'];
	        }
	        $map['uc_uid'] = array('in',$uid_in);
	        $uulink_out[] = M( 'ucenter_user_link' )->where($map)->findAll();
	        unset($data_chunck);
	    }
	    $uclink = array();
	    
	    //格式化获取的映射信息
	    foreach ($uulink_out as $obj){
	        foreach ($obj as $ulink){
	            $uclink[$uclink['uc_uid']] = $ulink;
	        }
	    }
	    
	    foreach ($data as $key => $value){
	        if (!empty( $value['uid'] )) {
	            $data[$key]['uc_uid'] = intval($value['uid']);
	            $data[$key]['uid'] = intval($uclink[$value['uid']]['uid']);
	            $data[$key]['imgsrc'] = getUserFace ( $value['uid'] );
	        } else {
	            $data[$key]['uc_uid'] = intval($value ['uid']);
	            $data[$key]['uid'] = 0;
	            //用户未在UIA注册时
	            if($data[$key]['uc_uid']==0 && $data[$key]['uid']==0){
	                $data[$key]['baseid'] = $value['identityid'];
	                $data[$key]['basetype'] = $idtype;
	            }
	            $data[$key]['imgsrc'] = SITE_URL.'/public/themes/edustyle/images/user_pic_middle.gif';
	        }
	    }

	    //写入缓存
	    if (!empty($data)) {
	    	S($skey,$data,3600*24);
	    }
	    return $data;
	}
	/**
	 +----------------------------------------------------------
	 + 获取我的好友
	 +----------------------------------------------------------
	 + @param	@param unknown $uid
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-4 下午6:59:53
	 +----------------------------------------------------------
	 */
	public function getMyFriendsByUid($uid){
	    if (!$uid) {
	        return null;
	    }
	    $db_prefix = C('DB_PREFIX');
	    //互粉的好友
	    $data['all'] = M('')->field('follow.fid AS uid,user.uname AS xm,user.sex AS sex')
    	    ->table("{$db_prefix}weibo_follow AS follow LEFT JOIN {$db_prefix}user AS user ON follow.fid=user.uid")
    	    ->where("follow.uid={$uid} AND follow.fid IN (SELECT uid FROM {$db_prefix}weibo_follow WHERE fid={$uid})")
    	    ->order('follow.follow_id DESC')
    	    ->findAll();
	    //添加头像信息
	    foreach ($data['all'] as $ukey=>$user){
	        $data['all'][$ukey]['imgsrc'] = getUserFace($user['uid']);
	    }
	    $data['all']['title'] = '互粉好友';
	    //我关注的
	    $data['followother'] = M('')->field('follow.fid AS uid,user.uname AS xm')
    	    ->table("{$db_prefix}weibo_follow AS follow LEFT JOIN {$db_prefix}user AS user ON follow.fid=user.uid")
    	    ->where("follow.uid={$uid}")
    	    ->order('follow.follow_id DESC')
    	    ->findAll();
	    //添加头像信息
	    foreach ($data['followother'] as $ukey=>$user){
	        $data['followother'][$ukey]['imgsrc'] = getUserFace($user['uid']);
	    }
	    $data['followother']['title'] = '我关注的';
	    //关注我的
	    $data['followme'] = M('')->field('follow.fid AS uid,user.uname AS xm')
    	    ->table("{$db_prefix}weibo_follow AS follow LEFT JOIN {$db_prefix}user AS user ON follow.fid=user.uid")
    	    ->where("follow.uid={$uid}")
    	    ->order('follow.follow_id DESC')
    	    ->findAll();
	    //添加头像信息
	    foreach ($data['followme'] as $ukey=>$user){
	        $data['followme'][$ukey]['imgsrc'] = getUserFace($user['uid']);
	    }
	    $data['followme']['title'] = '关注我的';
	    
	    return $data;
	}
	/**
	 * +----------------------------------------------------------
	 * + 根据老师uid获取老师相关教研组同事
	 * +----------------------------------------------------------
	 * + @param	@param unknown $uid
	 * + @return	array
	 * + @author	曹飞 (Administrator)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-10-30 下午15:40:10
	 * +----------------------------------------------------------
	 */
	public function getUserWorkerByUid($uid) {
		if (! $uid) {
			return null;
		}
		// 获取教研组同事
		$workertlist = get_worker_by_teacheruid ( $uid );
		// 获取映射的更多信息
		$workertlist = $this->parseData($workertlist,'getUserWorkerByUid'.$uid,2);
		// 添加头像数据，进行分组
		foreach ( $workertlist as $key => $value ) {
			if (! empty ( $value ['xkmc'] )) {
				$result [$value ['xkid']] [$key] = $value;
				$result [$value ['xkid']] ['title'] = $value ['xkmc'];
			}
		}
		return $result;
	}
	/**
	 * +----------------------------------------------------------
	 * + 获取老师uid该老师下的所有学生
	 * +----------------------------------------------------------
	 * + @param	@param unknown $uid
	 * + @return
	 * + @author	曹飞 (Administrator)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-10-30 下午15:43:10
	 * +----------------------------------------------------------
	 */
	public function getStudentByTeacherUid($uid) {
		if (!$uid) {
			return null;
		}
		// 获取某老师下所以学生
		$studentlist = get_student_by_teacheruid ( $uid );
		// 获取映射的更多信息
		$studentlist = $this->parseData($studentlist,'getStudentByTeacherUid'.$uid,3);
		// 添加头像数据，进行分组
		foreach ( $studentlist as $key => $value ) {
			if (! empty ( $value ['bjmc'] )) {
				// 判断是否注册，查询头像路径
				$result [$value ['bjid']] [$key] = $value;
				$result [$value ['bjid']] ['title'] = $value ['bjmc'];
			}
		}

		return $result;
	}
	/**
	 * +----------------------------------------------------------
	 * + 根据家长uid获取同班老师信息
	 * +----------------------------------------------------------
	 * + @param	@param unknown $uid
	 * + @return
	 * + @author	曹飞 (Administrator)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-10-30 下午16:20:10
	 * +----------------------------------------------------------
	 */
	public function getTeacherByParentUid($uid) {
		if (! $uid) {
			return null;
		}
		// 根据家长uid获取同班老师信息
		$teacherlist = get_teacher_by_parentuid ( $uid );
		// 获取映射的更多信息
		$teacherlist = $this->parseData($teacherlist,'getTeacherByParentUid'.$uid,2);
		// 添加头像数据，进行分组
		foreach ( $teacherlist as $key => $value ) {
			if (! empty ( $value ['bjid'] )) {
				$xm = $value ['xkmc'] . ' ' . $value ['xm'];
				$value ['xm'] = $xm;
				$result [$value ['bjid']] [$key] = $value;
				$result [$value ['bjid']] ['title'] = $value ['bjmc'];
			}
		}
		return $result;
	}
	/**
	 * +----------------------------------------------------------
	 * + 根据家长uid获取同班其他家长信息
	 * +----------------------------------------------------------
	 * + @param	@param unknown $uid
	 * + @return
	 * + @author	曹飞 (Administrator)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-10-30 下午16:22:10
	 * +----------------------------------------------------------
	 */
	public function getOthersParentByParentUid($uid) {
		if (! $uid) {
			return null;
		}
		// 根据家长uid获取同班其他家长信息
		$parentlist = get_othersparent_by_parentuid ( $uid );
		// 获取映射的更多信息
		$parentlist = $this->parseData($parentlist,'getOthersParentByParentUid'.$uid,4);
		// 添加头像数据，进行分组
		foreach ( $parentlist as $key => $value ) {
			if (! empty ( $value ['classid'] )) {
				$value ['xm'] = $value ['username'];
				$result [$value ['classid']] [$key] = $value;
				$result [$value ['classid']] ['title'] = $value ['classname'];
			}
		}
		return $result;
	}
	/**
	 * +----------------------------------------------------------
	 * + 根据学生uid获取同班老师信息
	 * +----------------------------------------------------------
	 * + @param	@param unknown $uid
	 * + @return
	 * + @author	曹飞 (Administrator)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-10-30 下午16:25:10
	 * +----------------------------------------------------------
	 */
	public function getTeacherByStudentUid($uid) {
		if (! $uid) {
			return null;
		}
		// 根据学生uid获取同班老师信息
		$teachertlist = get_teacher_by_studentuid ( $uid );
		// 获取映射的更多信息
		$teachertlist = $this->parseData($teachertlist,'getTeacherByStudentUid'.$uid,2);
		// 添加头像数据，进行分组
		foreach ( $teachertlist as $key => $value ) {
			$xm = $value ['xkmc'] . ' ' . $value ['xm'];
			$value ['xm'] = $xm;
			$result [$key] = $value;
			$result [$key] ['title'] = 'none';
		}
		return $result;
	}
	/**
	 * +----------------------------------------------------------
	 * + 根据学生uid获取同班同学信息
	 * +----------------------------------------------------------
	 * + @param	@param unknown $uid
	 * + @return
	 * + @author	曹飞 (Administrator)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-10-30 下午16:25:10
	 * +----------------------------------------------------------
	 */
	public function getClassmatesByStudentUid($uid) {
		if (! $uid) {
			return null;
		}
		// 根据学生uid获取同班同学信息
		$studenttlist = get_classmates_by_studentuid ( $uid );
		// 获取映射的更多信息
		$studenttlist = $this->parseData($studenttlist,'getClassmatesByStudentUid'.$uid,3);
		// 添加头像数据，进行分组
		foreach ( $studenttlist as $key => $value ) {
			$result [$key] = $value;
			$result [$key] ['title'] = 'none';
		}
		return $result;
	}
	/**
	 * +----------------------------------------------------------
	 * + 获取某学校下所有一级部门下所有用户
	 * +----------------------------------------------------------
	 * + @param	@param unknown $schoolid
	 * + @param	@return NULL
	 * + @return	NULL
	 * + @author	小波 (Administrator)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-10-28 上午9:44:42
	 * +----------------------------------------------------------
	 */
	public function getGroupUserBySchoolId($schoolid) {
		if (! $schoolid) {
			return null;
		}
		
		// 缓存应用
		$cache_key = "getGroupUserBySchoolId_" . $schoolid;
		$cache = S ( $cache_key );
		if ($cache) {
			return $cache;
		}
		
		// 获取数据
		$data = get_deptinfo_by_schoolid ( $schoolid );
		
		// 如果有数据则写入缓存
		if ($data) {
			// 生成缓存
			S ( $cache_key, $data, 3600 * 24 );
		}
		return $data;
	}
	
	// 运行服务，系统服务自动运行
	public function run() {
	}
	
	// 启动服务，未编码
	public function _start() {
		return true;
	}
	
	// 停止服务，未编码
	public function _stop() {
		return true;
	}
	
	// 安装服务，未编码
	public function _install() {
		return true;
	}
	
	// 卸载服务，未编码
	public function _uninstall() {
		return true;
	}
}
?>