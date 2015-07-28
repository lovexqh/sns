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

class teacher_classcontrol extends base {

	function __construct() {
		$this->_teacherclasscontrol();
	}

	function _teacherclasscontrol() {
		parent::__construct();
		$this->load('teacher_class');
	}
	
	/*
		根据班级编号查出班主任信息
	*/
	function onget_class_teacher_ById()
	{
		$id = $this->input('id');
		if (intval($id)){
			$data = $_ENV['teacher_class']->get_class_teacher_ById($id);
			return $data;
		}
		else
			return null;
	}
	/*
		根据班级编号查出班主任注册uid
	*/
	function onget_class_adviser_ById()
	{
		$id = $this->input('id');
		if (intval($id)){
			$data = $_ENV['teacher_class']->get_class_adviser_ById($id);
			return $data;
		}
		else
			return null;
	}
	
	/*
		根据班级编号查出班级所有教师信息
	*/
	function onget_teacher_get_id()
	{
		$id = $this->input('id');
		if (intval($id)){
			$data = $_ENV['teacher_class']->get_teacher_get_id($id);
			return $data;
		}
		else
			return null;
	}
	/**
	 +----------------------------------------------------------
	 * 根据identityid获取任班主任的班级列表
	 +----------------------------------------------------------
	 * @param	int identityid  身份id
	 * @return	array
	 * @author	小朱 2013-4-9
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-9 下午02:08:56
	 +----------------------------------------------------------
	 */
	function onget_adviserclassids_by_identityid(){
		$identityid=$this->input('identityid');
		if (intval($identityid)){
			$data = $_ENV['teacher_class']->get_adviserclassids_by_identityid($identityid);
			return $data;
		}
		else
			return null;
	}
	
}

?>