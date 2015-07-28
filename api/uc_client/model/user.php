<?php

/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class usermodels {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->_usermodel($base);
	}

	function _usermodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function get_user_by_uid($uid) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."members WHERE uid='$uid'");
		return $arr;
	}

	function get_user_by_username($username) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."members WHERE username='$username'");
		return $arr;
	}

	function get_user_by_email($email) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."members WHERE email='$email'");
		return $arr;
	}

	function get_user_info_by_email($email) {
		$arr = $this->db->fetch_first("SELECT user.*,role.RoleName,role.RoleIcon,dept.DepartName,dept.DeptLevel,dept.UpDeptID FROM ".UC_DBTABLEPRE."members as user,".UC_DBTABLEPRE."role as role,".UC_DBTABLEPRE."dept as dept WHERE user.RoleID=role.RoleID and dept.DeptID=user.DeptID and email='$email'");
		return $arr;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据用户identityid获取用户的学校信息
	 +----------------------------------------------------------
	 * @param int $identityid 用户基础数据ID
	 * @param int $identitytype 用户类型ID
	 * @return array 一条或多条结果集
	 * @author 小波
	 +----------------------------------------------------------
 	 * 创建时间：2013-3-13
 	 +----------------------------------------------------------
	 */
	function get_user_schoolinfo($identityid,$identitytype,$fields = '*'){
		$identityid = intval($identityid);
		if (2 == $identitytype) {		//获取老师信息
			$sql = "SELECT $fields FROM ".UC_DBTABLEPRE."schoolinfo WHERE id LIKE (SELECT schoolid FROM ".UC_DBTABLEPRE."teacherinfo WHERE identityid = $identityid)";
		}
		if (3 == $identitytype) {		//获取学生信息
			$sql = "SELECT $fields FROM ".UC_DBTABLEPRE."schoolinfo WHERE id LIKE (SELECT schoolid FROM ".UC_DBTABLEPRE."studentinfo WHERE identityid = $identityid)";
		}
		if (4 == $identitytype) {		//获取家长信息
			$sql = "SELECT $fields FROM ".UC_DBTABLEPRE."schoolinfo WHERE id LIKE (SELECT schoolid FROM ".UC_DBTABLEPRE."studentinfo WHERE familyid = (SELECT familyid FROM ".UC_DBTABLEPRE."parentinfo WHERE identityid = $identityid))";
		}
		if(!empty($sql)){
			$arr = $this->db->fetch_all($sql);
// 			if(1==count($arr)){
// 				return $arr[0];
// 			}
		}
		return $arr;
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取班级信息
	 +----------------------------------------------------------
	 * @param int $identityid 用户基础数据ID
	 * @return array 一条结果集
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-13 下午4:09:38
	 +----------------------------------------------------------
	 */
	function get_user_classinfo($identityid,$fields = '*'){
		$identityid = intval($identityid);
		$sql = "SELECT $fields,id as classid FROM ".UC_DBTABLEPRE."classinfo c LEFT JOIN ".UC_DBTABLEPRE."studentinfo s ON c.id = s.bjid WHERE s.identityid = $identityid";
		if(!empty($sql)){
			$arr = $this->db->fetch_first($sql);
		}
		return $arr;
	}

	//获取用户的身份与角色数据 2012/11/05
	function get_user_role_by_id($id){
		$arr = $this->db->fetch_all("select roleid,rolename,roleicon,identityid,identityname,identityicon from ".UC_DBTABLEPRE."role left join ".UC_DBTABLEPRE."identity on ridentityid=identityid where roleid=$id");
		
		return $arr;
	}

	function get_class_list($deptid,$email,$page,$pagesize) {
		$totalnum = $this->get_class_num($deptid)-1;
		$start = $this->base->page_get_start($page, $pagesize, $totalnum);
		$limit = '';
		if($pagesize)
			$limit = "LIMIT $start,$pagesize";
		$data = $this->db->fetch_all("SELECT user.*,role.RoleName,role.RoleIcon FROM ".UC_DBTABLEPRE."members as user left join ".UC_DBTABLEPRE."role as role on user.RoleID=role.RoleID WHERE user.DeptID=$deptid and email!='$email' $limit");
		return $data;
	}

	function get_class_num($deptid) {
		$result = $this->db->result_first("SELECT count(*) FROM ".UC_DBTABLEPRE."members WHERE `DeptID`=$deptid");
		return $result;
	}
	function get_deptid_num($deptid) {
		$result = $this->db->result_first("SELECT count(*) FROM ".UC_DBTABLEPRE."members WHERE `DeptID`=$deptid");
		return $result;
	}

	function get_dept_list($deptid,$isall) {
		//获取某部门下以及下级部门下所有人员
		if($isall){
			$data = $this->db->fetch_all("SELECT user.*,role.RoleName,role.RoleIcon FROM ".UC_DBTABLEPRE."members as user left join ".UC_DBTABLEPRE."role as role on user.RoleID=role.RoleID WHERE user.DeptID=$deptid and email!='$email' $limit");
		}else
			$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."members WHERE DeptID=$deptid");
		return $data;
	}

	function check_username($username) {
		$guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		$len = $this->dstrlen($username);
		if($len > 15 || $len < 3 || preg_match("/\s+|^c:\\con\\con|[%,\*\"\s\<\>\&]|$guestexp/is", $username)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function dstrlen($str) {
		if(strtolower(UC_CHARSET) != 'utf-8') {
			return strlen($str);
		}
		$count = 0;
		for($i = 0; $i < strlen($str); $i++){
			$value = ord($str[$i]);
			if($value > 127) {
				$count++;
				if($value >= 192 && $value <= 223) $i++;
				elseif($value >= 224 && $value <= 239) $i = $i + 2;
				elseif($value >= 240 && $value <= 247) $i = $i + 3;
		    	}
	    		$count++;
		}
		return $count;
	}

	function check_mergeuser($username) {
		$data = $this->db->result_first("SELECT count(*) FROM ".UC_DBTABLEPRE."mergemembers WHERE appid='".$this->base->app['appid']."' AND username='$username'");
		return $data;
	}

	function check_usernamecensor($username) {
		$_CACHE['badwords'] = $this->base->cache('badwords');
		$censorusername = $this->base->get_setting('censorusername');
		$censorusername = $censorusername['censorusername'];
		$censorexp = '/^('.str_replace(array('\\*', "\r\n", ' '), array('.*', '|', ''), preg_quote(($censorusername = trim($censorusername)), '/')).')$/i';
		$usernamereplaced = isset($_CACHE['badwords']['findpattern']) && !empty($_CACHE['badwords']['findpattern']) ? @preg_replace($_CACHE['badwords']['findpattern'], $_CACHE['badwords']['replace'], $username) : $username;
		if(($usernamereplaced != $username) || ($censorusername && preg_match($censorexp, $username))) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function check_usernameexists($username) {
		$data = $this->db->result_first("SELECT username FROM ".UC_DBTABLEPRE."members WHERE username='$username'");
		return $data;
	}

	function check_emailformat($email) {
		return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
	}

	function check_emailaccess($email) {
		$setting = $this->base->get_setting(array('accessemail', 'censoremail'));
		$accessemail = $setting['accessemail'];
		$censoremail = $setting['censoremail'];
		$accessexp = '/('.str_replace("\r\n", '|', preg_quote(trim($accessemail), '/')).')$/i';
		$censorexp = '/('.str_replace("\r\n", '|', preg_quote(trim($censoremail), '/')).')$/i';
		if($accessemail || $censoremail) {
			if(($accessemail && !preg_match($accessexp, $email)) || ($censoremail && preg_match($censorexp, $email))) {
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			return TRUE;
		}
	}

	/***
	 * 检查用户提交的学号是否存在已注册用户数据中
	 */
	function check_usernoexists($userno, $username = '') {
		$sqladd = $username !== '' ? "AND username<>'$username'" : '';
		$userno = $this->db->result_first("SELECT UserNO FROM  ".UC_DBTABLEPRE."members WHERE UserNO='$userno' $sqladd");
		return $userno;
	}
	/***
	 * 检查用户提交的学号是否存在用户花名册中
	 */
	function check_usernoroster($userno, $username = '') {
		$sqladd = $username !== '' ? "AND username<>'$username'" : '';
		$userno = $this->db->result_first("SELECT userno FROM  ".UC_DBTABLEPRE."roster_student WHERE userno='$userno' $sqladd");
		return $userno;
	}

	function check_emailexists($email, $username = '') {
		$sqladd = $username !== '' ? "AND username<>'$username'" : '';
		$email = $this->db->result_first("SELECT email FROM  ".UC_DBTABLEPRE."members WHERE email='$email' $sqladd");
		return $email;
	}

	function check_login($username, $password, &$user) {
		$user = $this->get_user_by_username($username);
		if(empty($user['username'])) {
			return -1;
		} elseif($user['password'] != md5(md5($password).$user['salt'])) {
			return -2;
		}
		return $user['uid'];
	}
	
	//根据基础数据的角色与基础数据的ID来判断用户是否已注册
	function check_identity($identityid,$identitytype){
		$result = $this->db->result_first("SELECT email FROM  ".UC_DBTABLEPRE."members WHERE identityid='$identityid' AND FIND_IN_SET($identitytype,identitytype)");
		return $result;
	}

	function add_user($_member) {
		$uid = 0;
		$_member['regip'] = empty($_member['regip']) ? $this->base->onlineip : $regip;
		$salt = substr(uniqid(rand()), -6);
		$_member['password'] = md5(md5($_member['password']).$salt);
		$sqladd = $uid ? "uid='".intval($uid)."'," : '';
		$sqladd .= $_member['questionid'] > 0 ? " secques='".$this->quescrypt($_member['questionid'], $_member['answer'])."'," : " secques='',";
		
		$sqlparam = "";
		foreach ($_member as $k=>$v){
			$sqlparam .= "$k='$v',";
		}
		$sqlparam .="regdate='".$this->base->time."', salt='$salt'";
		
		$this->db->query("INSERT INTO ".UC_DBTABLEPRE."members SET $sqladd $sqlparam");
		$uid = $this->db->insert_id();
		$this->db->query("INSERT INTO ".UC_DBTABLEPRE."memberfields SET uid='$uid'");
		return $uid;
	}

	function edit_user($uid, $oldpw, $newpw, $newemail, $ignoreoldpw = 0, $questionid = '', $answer = '') {
		$data = $this->db->fetch_first("SELECT username, uid, password, salt FROM ".UC_DBTABLEPRE."members WHERE uid='$uid'");

		if($ignoreoldpw) {
			$isprotected = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."protectedmembers WHERE uid = '$data[uid]'");
			if($isprotected) {
				return -8;
			}
		}
        /*
		if(!$ignoreoldpw && $data['password'] != md5(md5($oldpw).$data['salt'])) {
			return -1;
		}
        */
		$sqladd = $newpw ? "password='".md5(md5($newpw).$data['salt'])."'" : '';
		$sqladd .= $newemail ? ($sqladd ? ',' : '')." email='$newemail'" : '';
		if($questionid !== '') {
			if($questionid > 0) {
				$sqladd .= ($sqladd ? ',' : '')." secques='".$this->quescrypt($questionid, $answer)."'";
			} else {
				$sqladd .= ($sqladd ? ',' : '')." secques=''";
			}
		}
		if($sqladd) {
			$this->db->query("UPDATE ".UC_DBTABLEPRE."members SET $sqladd WHERE uid='$uid'");
			return $this->db->affected_rows();
		} else {
			return -7;
		}
	}

	function delete_user($uidsarr) {
		$uidsarr = (array)$uidsarr;
		if(!$uidsarr) {
			return 0;
		}
		$uids = $this->base->implode($uidsarr);
		$arr = $this->db->fetch_all("SELECT uid FROM ".UC_DBTABLEPRE."protectedmembers WHERE uid IN ($uids)");
		$puids = array();
		foreach((array)$arr as $member) {
			$puids[] = $member['uid'];
		}
		$uids = $this->base->implode(array_diff($uidsarr, $puids));
		if($uids) {
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."members WHERE uid IN($uids)");
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."memberfields WHERE uid IN($uids)");
			uc_user_deleteavatar($uidsarr);
			$this->base->load('note');
			$_ENV['note']->add('deleteuser', "ids=$uids");
			return $this->db->affected_rows();
		} else {
			return 0;
		}
	}

	function get_total_num($sqladd = '') {
		$data = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."members $sqladd");
		return $data;
	}

	function get_list($page, $ppp, $totalnum, $sqladd) {
		$start = $this->base->page_get_start($page, $ppp, $totalnum);
		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."members $sqladd LIMIT $start, $ppp");
		return $data;
	}

	function name2id($usernamesarr) {
		$usernamesarr = uc_addslashes($usernamesarr, 1, TRUE);
		$usernames = $this->base->implode($usernamesarr);
		$query = $this->db->query("SELECT uid FROM ".UC_DBTABLEPRE."members WHERE username IN($usernames)");
		$arr = array();
		while($user = $this->db->fetch_array($query)) {
			$arr[] = $user['uid'];
		}
		return $arr;
	}

	function id2name($uidarr) {
		$arr = array();
		$query = $this->db->query("SELECT uid, username FROM ".UC_DBTABLEPRE."members WHERE uid IN (".$this->base->implode($uidarr).")");
		while($user = $this->db->fetch_array($query)) {
			$arr[$user['uid']] = $user['username'];
		}
		return $arr;
	}

	function quescrypt($questionid, $answer) {
		return $questionid > 0 && $answer != '' ? substr(md5($answer.md5($questionid)), 16, 8) : '';
	}
	function get_school_num() {
		$result['school'] = $this->db->fetch_first("SELECT count(*) FROM ".UC_DBTABLEPRE."schoolinfo");
		$result['class'] = $this->db->fetch_first("SELECT count(*) FROM ".UC_DBTABLEPRE."classinfo");
		$result['school']=$result['school']['count(*)'];
		$result['class']=$result['class']['count(*)'];
		return $result;
	}
	function get_classbyid($id) {
		$ids= $this->db->fetch_all("SELECT classid FROM ".UC_DBTABLEPRE."R_teacher_class WHERE identityid = ".$id." group by classid");
		foreach ($ids as $v)
		{
			$result[$v['classid']] = $this->db->fetch_first("SELECT id,bh,bj FROM ".UC_DBTABLEPRE."classinfo where id=".$v['classid']);
		}
		return $result;
	}
	/**
	 +----------------------------------------------------------
	 * 根据用户ID获取班级同学列表
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function get_class_user_by_uid($uid){
		$sql = "SELECT uid FROM ".UC_DBTABLEPRE."members WHERE identityid IN (SELECT identityid FROM ".UC_DBTABLEPRE."studentinfo WHERE classid = (SELECT classid FROM ".UC_DBTABLEPRE."studentinfo WHERE identityid in (SELECT identityid FROM ".UC_DBTABLEPRE."members WHERE uid =$uid))) AND identitytype = 3 AND uid<>$uid";
		$data = $this->db->fetch_all($sql);
		return $data;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据学校ID获取用户列表
	 +----------------------------------------------------------
	 * @param intval $sid 学校ID
	 * @return array 结果集
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-15 下午5:07:48
	 +----------------------------------------------------------
	 */
	function get_userlist_by_sid($sid){
		$sql = "SELECT uid,username,email FROM ".UC_DBTABLEPRE."members WHERE (identitytype = 3 AND identityid IN 
				(SELECT identityid FROM ".UC_DBTABLEPRE."studentinfo WHERE schoolid = $sid)) OR
				 (identitytype = 2 AND identityid IN (SELECT identityid FROM ".UC_DBTABLEPRE."teacherinfo WHERE schoolid = $sid))";
		$data = $this->db->fetch_all($sql);
		return $data;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据班级ID获取用户列表
	 +----------------------------------------------------------
	 * @param intval $cid 班级ID
	 * @return array 结果集
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-15 下午5:07:48
	 +----------------------------------------------------------
	 */
	function get_userlist_by_cid($cid){
		$sql = "SELECT uid,username,email FROM ".UC_DBTABLEPRE."members WHERE (identitytype = 3 AND identityid IN
				(SELECT identityid FROM ".UC_DBTABLEPRE."studentinfo WHERE classid = $cid))";
		$data = $this->db->fetch_all($sql);
		return $data;
	}

    /**
    +----------------------------------------------------------
     * 根据班级ID获取用户列表
    +----------------------------------------------------------
     * @param intval $sid 班级ID
     * @return array 结果集
     * @author 徐程亮
    +----------------------------------------------------------
     * 创建时间：2013-5-31
    +----------------------------------------------------------
     */
    function get_class_members($cid){
        $sql = "select s.identityid uc_id,s.xm name,s.xbm,s.classid,IFNULL(s.zp,'/public/themes/edustyle/images/user_pic_middle.gif') p,m.uid
                from ".UC_DBTABLEPRE."studentinfo s
                left join ".UC_DBTABLEPRE."members m on m.identityID=s.identityid
                where s.classid=$cid
                ";
        return $this->db->fetch_all($sql);
    }

    /**
    +----------------------------------------------------------
     * 根据父母ID获取表获取学生
    +----------------------------------------------------------
     * @param intval $pid 父母ID
     * @return array 结果集
     * @author 徐程亮
    +----------------------------------------------------------
     * 创建时间：2013-5-31
    +----------------------------------------------------------
     */
    function get_student_info($pid){
        $sql = "select s.identityid uc_id,s.classid,s.xm name,s.xbm,IFNULL(s.zp,'/public/themes/edustyle/images/user_pic_middle.gif') p
                from ".UC_DBTABLEPRE."studentinfo s
                left join ".UC_DBTABLEPRE."parentinfo p on s.familyid=p.familyid
                where p.identityid=$pid
                ";
        return $this->db->fetch_all($sql);
    }

    /**
    +----------------------------------------------------------
     * 根据学生ID获取学生详细信息
    +----------------------------------------------------------
     * @param intval $sid 学生ID
     * @return array 结果集
     * @author 徐程亮
    +----------------------------------------------------------
     * 创建时间：2013-5-31
    +----------------------------------------------------------
     */
    function get_studentDetail_info($sid){
        $sql = "select m.uid,s.xh,s.xm,s.xbm,s.csrq,s.classid,s.nj,s.bh,s.lxdh
                from ".UC_DBTABLEPRE."studentinfo s
                left join ".UC_DBTABLEPRE."members m on m.identityid=s.identityid
                where s.identityid=$sid
                ";
        return $this->db->fetch_all($sql);
    }

}

?>