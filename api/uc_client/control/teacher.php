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

class teachercontrol extends base {

	function __construct() {
		$this->_teachercontrol();
	}

	function _teachercontrol() {
		parent::__construct();
		$this->load('teacher');
	}

	function onget_userinfo_by_userno() {
		$this->init_input();
		$userno = $this->input('userno');
		if(trim($userno))
			return $_ENV['teacher']->get_userinfo_by_userno($userno);
		else
			return null;
	}

	//根据ID获取相关数据
	function onget_userinfo_by_id() {
		$this->init_input();
		$id = $this->input('id');
		if (intval($id)){
			$data = $_ENV['teacher']->get_teacher_by_id($id);
			return $data;
		}
		else
			return null;
	}

	
	
	//根据Uid获取相关部门信息
	function onget_deptinfo_by_uid(){
		$this->init_input();
		$uid = $this->input('uid');
		if (intval($uid)){
			$data = $_ENV['teacher']->get_deptinfo_by_uid($uid);
			return $data;
		}
		else
			return null;
	}
	/**
	 +----------------------------------------------------------
	 * 根据老师uid获取老师所在学校的所有部门信息
	 +----------------------------------------------------------
	 * @return array
	 * @author 小伟 2013-3-19
	 +----------------------------------------------------------
	 */
	function onget_dept_list(){
		$this->init_input();
		$uid = $this->input('uid');
		if (intval($uid)){
			$data = $_ENV['teacher']->get_depts_by_uid($uid);
			return $data;
		}
		else
			return null;
	}
	/**
	 +----------------------------------------------------------
	 * 根据部门ID获取该部门所有的人员信息
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-3-19
	 +----------------------------------------------------------
	 */
	function onget_teachers_list(){
		$this->init_input();
		$deptId = $this->input('deptId');
		$schoolId = $this->input('schoolId');
		if (intval($deptId)&&intval($schoolId)){
			$data = $_ENV['teacher']->get_teachers_by_deptid($deptId,$schoolId);
			return $data;
		}
		else
			return null;		
	}

}

?>