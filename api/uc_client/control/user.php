<?php

/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1082 2011-04-07 06:42:14Z svn_project_zhangjie $
*/

!defined('IN_UC') && exit('Access Denied');

define('UC_USER_CHECK_USERNAME_FAILED', -1);
define('UC_USER_USERNAME_BADWORD', -2);
define('UC_USER_USERNAME_EXISTS', -3);
define('UC_USER_EMAIL_FORMAT_ILLEGAL', -4);
define('UC_USER_EMAIL_ACCESS_ILLEGAL', -5);
define('UC_USER_EMAIL_EXISTS', -6);
define('UC_USER_USERNO_EXISTS', -7);
define('UC_USER_USERNO_ROSTER', -8);

class usercontrol extends base {

	function __construct() {
		$this->_usercontrol();
	}

	function _usercontrol() {
		parent::__construct();
		$this->load('user');
		$this->load('identity');
		$this->load('dept');
		$this->app = $this->cache['apps'][UC_APPID];
	}

	function onsynlogin() {
		$this->init_input();
		$uid = $this->input('uid');
		if($this->app['synlogin']) {
			if($this->user = $_ENV['user']->get_user_by_uid($uid)) {
				$synstr = '';
				foreach($this->cache['apps'] as $appid => $app) {
					if($app['synlogin'] && $app['appid'] != $this->app['appid']) {
						$synstr .= '<script type="text/javascript" src="'.$app['url'].'/api/uc.php?time='.$this->time.'&code='.urlencode($this->authcode('action=synlogin&username='.$this->user['username'].'&uid='.$this->user['uid'].'&password='.$this->user['password']."&time=".$this->time, 'ENCODE', $app['authkey'])).'"></script>';
					}
				}
				return $synstr;
			}
		}
		return '';
	}

	function onsynlogout() {
		$this->init_input();
		if($this->app['synlogin']) {
			$synstr = '';
			foreach($this->cache['apps'] as $appid => $app) {
				if($app['synlogin'] && $app['appid'] != $this->app['appid']) {
					$synstr .= '<script type="text/javascript" src="'.$app['url'].'/api/uc.php?time='.$this->time.'&code='.urlencode($this->authcode('action=synlogout&time='.$this->time, 'ENCODE', $app['authkey'])).'"></script>';
				}
			}
			return $synstr;
		}
		return '';
	}

	function onregister() {
		$this->init_input();
		
		$_member['username']	= $this->input('username');
		$_member['password']	= $this->input('password');
		$_member['email']		= $this->input('email');
		$_member['realname']	= $this->input('realname');
		$identityid	= $this->input('identityid');
		$_member['identitytype'] = $this->input('identitytype');
		//$_member['questionid']	= $this->input('questionid');
		//$_member['answer']		= $this->input('answer');
		$_member['regip']		= $this->input('regip');
		if($identityid){
			$_member['identityid'] = $identityid;
		}

		$require 	= $this->input('require');
		if ($require){
			if(($status = $this->_check_username($_member['username'])) < 0) {
				return $status;
			}
		}
		
		if(($status = $this->_check_email($_member['email'])) < 0) {
			return $status;
		}
		$uid = $_ENV['user']->add_user($_member);
		return $uid;
	}

	function onedit() {
		$this->init_input();
        $uid = $this->input('uid');
		$email = $this->input('email');
		$oldpw = $this->input('oldpw');
		$newpw = $this->input('newpw');
		$newemail = $this->input('newemail');
		$ignoreoldpw = $this->input('ignoreoldpw');
		$questionid = $this->input('questionid');
		$answer = $this->input('answer');

		if(!$ignoreoldpw && $newemail && ($status = $this->_check_email($newemail)) < 0) {
			return $status;
		}
		$status = $_ENV['user']->edit_user($uid, $oldpw, $newpw, $newemail, $ignoreoldpw, $questionid, $answer);
		if($newpw && $status > 0) {
			$this->load('note');
			$_ENV['note']->add('updatepw', 'uid='.$uid.'&password=');
			$_ENV['note']->send();
		}
		return $status;
	}

	function onlogin() {
		$this->init_input();
		$isuid = $this->input('isuid');
		$username = $this->input('username');
		$password = $this->input('password');
		$checkques = $this->input('checkques');
		$questionid = $this->input('questionid');
		$answer = $this->input('answer');
		if($isuid == 1) {
			$user = $_ENV['user']->get_user_by_uid($username);
		} elseif($isuid == 2) {
			$user = $_ENV['user']->get_user_by_email($username);
		} else {
			$user = $_ENV['user']->get_user_by_username($username);
		}

		$passwordmd5 = preg_match('/^\w{32}$/', $password) ? $password : md5($password);
		if(empty($user)) {
			$status = -1;
		} elseif($user['password'] != md5($passwordmd5.$user['salt'])) {
			$status = -2;
		} elseif($checkques && $user['secques'] != '' && $user['secques'] != $_ENV['user']->quescrypt($questionid, $answer)) {
			$status = -3;
		} else {
			$status = $user['uid'];
		}
		$merge = $status != -1 && !$isuid && $_ENV['user']->check_mergeuser($username) ? 1 : 0;
		return array($status, $user['username'], $password, $user['email'], $merge);
	}

	function oncheck_email() {
		$this->init_input();
		$email = $this->input('email');
		return $this->_check_email($email);
	}

	function oncheck_username() {
		$this->init_input();
		$username = $this->input('username');
		if(($status = $this->_check_username($username)) < 0) {
			return $status;
		} else {
			return 1;
		}
	}
	/*检查基础用户是否已注册*/
	function oncheck_identity() {
		$this->init_input();
		$identityid = $this->input('identityid');
		$identitytype = $this->input('identitytype');
		return $status = $_ENV['user']->check_identity($identityid,$identitytype);
	}
	
	function onget_user() {
		$this->init_input();
		$username = $this->input('username');
		if(!$this->input('isuid')) {
			$status = $_ENV['user']->get_user_by_username($username);
		} elseif($this->input('isuid') == 2) {
			$status = $_ENV['user']->get_user_by_email($username);
		} else {
			$status = $_ENV['user']->get_user_by_uid($username);
		}
		if($this->input('isuid')==1) {
			return $status;
		} else if($status&&$this->input('isuid')!=2) {
			return array($status['uid'],$status['username'],$status['email']);
		} else if($status&&$this->input('isuid')==2) {
			return $status;
		} else {
			return 0;
		}
	}

	function onget_user_info() {
		$this->init_input();
		$username = $this->input('username');
        $isuid = $this->input('isuid');
		if(!$isuid) {
			$status = $_ENV['user']->get_user_by_username($username);
		} elseif($this->input('isuid') == 2) {
			$status = $_ENV['user']->get_user_by_email($username);
		} else {
			$status = $_ENV['user']->get_user_by_uid($username);
		}
		if($status) {
			//获取用户部门信息
			if (!empty($status['identitytype'])&&!empty($status['identityid'])) {
				$schoolarr = $_ENV['user']->get_user_schoolinfo($status['identityid'],$status['identitytype'],'xxmc');
				if (!empty($status['identitytype'])&&$status['identitytype']==3) {
					$classarr = $_ENV['user']->get_user_classinfo($status['identityid']);
				}
			}
			if(!empty($schoolarr) || 1==count($schoolarr)){
				$status['schoolname'] = $schoolarr['xxmc'];
			}
			if(!empty($classarr)){
				$status['classname'] = $classarr['bj'];
			}
			return $status;
		} else {
			return 0;
		}
	}
	
	/*获取相应的用户角色用户信息列表*/
	function onget_users_by_identity(){
		$this->init_input();
		$identityid = $this->input('identityid');
		$isreg = $this->input('isreg');
		if($isreg==0)
			$sql = "SELECT * FROM ".UC_DBTABLEPRE."identity WHERE isreg>0 ORDER BY `order` ASC";
		else
			$sql = "SELECT * FROM ".UC_DBTABLEPRE."identity WHERE isreg>-1 ORDER BY `order` ASC";
		if($identityid)
			$identitys[]['identityid'] = $identityid;
		else
			$identitys = $_ENV['identity']->db->fetch_all($sql);
		
		foreach ($identitys as &$obj){
			
			$obj = arrayKeyTolower($obj,1);
			
			if (!empty($obj['identityid'])){
				$users = $_ENV['user']->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."members WHERE FIND_IN_SET(".$obj['identityid'].",identitytype)");
				$emails = array();
				foreach ($users as $info){
					$obj['uc_mails'][] = $info['email'];
					$obj['uc_ids'][] = $info['uid'];
				}
			}
		}
		return $identitys;
	}
    
	/*获取对应的部门deptid的用户信息列表*/
	function onget_users_by_deptid(){
		$this->init_input();
		$deptid = $this->input('deptid');
		$userslist = $_ENV['user']->db->fetch_all("SELECT uid,username,email FROM ".UC_DBTABLEPRE."members WHERE DeptID=".$deptid);
		return $userslist;
	}
	/*获取对应的部门deptid的用户数量count*/
	function onget_usercount_by_deptid(){
		$this->init_input();
		$deptid = $this->input('deptid');
		$count = $_ENV['user']->get_deptid_num($deptid);
		return $count;
	}
	function onget_class_user(){
		$this->init_input();
		$deptid = $this->input('deptid');
		$email = $this->input('email');
		$page = $this->input('page');
		$pagesize = $this->input('pagesize');
		if(!empty($deptid)&&!empty($email)){
			$result = $_ENV['user']->get_class_list($deptid,$email,$page,$pagesize);
		}
		return $result;
	}

	function onget_class_num(){
		$this->init_input();
		$deptid = $this->input('deptid');
		$result = $_ENV['user']->get_class_num($deptid);
		return $result;
	}
	function onget_school_num(){
		$result = $_ENV['user']->get_school_num();
		return $result;
	}
	function onget_classbyid(){
		$this->init_input();
		$id = $this->input('id');
		$result = $_ENV['user']->get_classbyid($id);
		return $result;
	}
	function onget_dept_user(){
		$this->init_input();
		$deptid = $this->input('deptid');
		$isall = $this->input('isall');

		$result = $_ENV['user']->get_dept_list($deptid,$isall);
		return $result;
	}

	function ongetprotected() {
		$protectedmembers = $this->db->fetch_all("SELECT uid,username FROM ".UC_DBTABLEPRE."protectedmembers GROUP BY username");
		return $protectedmembers;
	}

	function ondelete() {
		$this->init_input();
		$uid = $this->input('uid');
		return $_ENV['user']->delete_user($uid);
	}

	function onaddprotected() {
		$this->init_input();
		$username = $this->input('username');
		$admin = $this->input('admin');
		$appid = $this->app['appid'];
		$usernames = (array)$username;
		foreach($usernames as $username) {
			$user = $_ENV['user']->get_user_by_username($username);
			$uid = $user['uid'];
			$this->db->query("REPLACE INTO ".UC_DBTABLEPRE."protectedmembers SET uid='$uid', username='$username', appid='$appid', dateline='{$this->time}', admin='$admin'", 'SILENT');
		}
		return $this->db->errno() ? -1 : 1;
	}

	function ondeleteprotected() {
		$this->init_input();
		$username = $this->input('username');
		$appid = $this->app['appid'];
		$usernames = (array)$username;
		foreach($usernames as $username) {
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."protectedmembers WHERE username='$username' AND appid='$appid'");
		}
		return $this->db->errno() ? -1 : 1;
	}

	function onmerge() {
		$this->init_input();
		$oldusername = $this->input('oldusername');
		$newusername = $this->input('newusername');
		$uid = $this->input('uid');
		$password = $this->input('password');
		$email = $this->input('email');
		if(($status = $this->_check_username($newusername)) < 0) {
			return $status;
		}
		$uid = $_ENV['user']->add_user($newusername, $password, $email, $uid);
		$this->db->query("DELETE FROM ".UC_DBTABLEPRE."mergemembers WHERE appid='".$this->app['appid']."' AND username='$oldusername'");
		return $uid;
	}

	function onmerge_remove() {
		$this->init_input();
		$username = $this->input('username');
		$this->db->query("DELETE FROM ".UC_DBTABLEPRE."mergemembers WHERE appid='".$this->app['appid']."' AND username='$username'");
		return NULL;
	}

	function oncheck_userno() {
		$this->init_input();
		$userno = $this->input('userno');
		return $this->_check_userno($userno);
	}
	/***
	 * 检查用户学号工号方法（新添加）
	 */
	function _check_userno($userno) {
		if($_ENV['user']->check_usernoexists($userno)) {
			return UC_USER_USERNO_EXISTS;
		} else if(!$_ENV['user']->check_usernoroster($userno)){
			return UC_USER_USERNO_ROSTER;
		} else {
			return 1;
		}
	}

	function _check_username($username) {
		$username = addslashes(trim(stripslashes($username)));
		if(!$_ENV['user']->check_username($username)) {
			return UC_USER_CHECK_USERNAME_FAILED;
		} elseif(!$_ENV['user']->check_usernamecensor($username)) {
			return UC_USER_USERNAME_BADWORD;
		} elseif($_ENV['user']->check_usernameexists($username)) {
			return UC_USER_USERNAME_EXISTS;
		}
		return 1;
	}

	function _check_email($email, $username = '') {
		if(empty($this->settings)) {
			$this->settings = $this->cache('settings');
		}
		if(!$_ENV['user']->check_emailformat($email)) {
			return UC_USER_EMAIL_FORMAT_ILLEGAL;
		} elseif(!$_ENV['user']->check_emailaccess($email)) {
			return UC_USER_EMAIL_ACCESS_ILLEGAL;
		} elseif(!$this->settings['doublee'] && $_ENV['user']->check_emailexists($email, $username)) {
			return UC_USER_EMAIL_EXISTS;
		} else {
			return 1;
		}
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
	function onget_class_user_by_uid(){
		$this->init_input();
		$uid = $this->input('uid');
		return $_ENV['user']->get_class_user_by_uid($uid);
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据学校ID获取用户列表
	 +----------------------------------------------------------
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-15 下午5:06:18
	 +----------------------------------------------------------
	 */
	function onget_userlist_by_sid(){
		$this->init_input();
		$sid = $this->input('sid');
		return $_ENV['user']->get_userlist_by_sid($sid);
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据班级ID获取用户列表
	 +----------------------------------------------------------
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-15 下午5:06:18
	 +----------------------------------------------------------
	 */
	function onget_userlist_by_cid(){
		$this->init_input();
		$cid = $this->input('cid');
		return $_ENV['user']->get_userlist_by_cid($cid);
	}

	function onuploadavatar() {
	}

	function onrectavatar() {
	}
	function flashdata_decode($s) {
	}


 /*
 +----------------------------------------------------------
 *根据班级id返回班内学生的 id,名字，照片
 +----------------------------------------------------------
 * @author 徐程亮
 +----------------------------------------------------------
 * 2013-5-31
 */
    function onget_class_members(){
        $this->init_input();
        $cid = $this->input('cid');
        return $_ENV['user']->get_class_members($cid);
    }

    function onget_student_info(){
        $this->init_input();
        $pid = $this->input('pid');
        return $_ENV['user']->get_student_info($pid);
    }

    function onget_studentDetail_info(){
        $this->init_input();
        $sid = $this->input('sid');
        return $_ENV['user']->get_studentDetail_info($sid);
    }
}

?>