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

class studentcontrol extends base {

	function __construct() {
		$this->_studentcontrol();
	}

	function _studentcontrol() {
		parent::__construct();
		$this->load('student');
		$this->load('parent');
	}

	function onget_userinfo_by_userno() {
		$this->init_input();
		$userno = $this->input('userno');
		if(trim($userno)){
			$data = $_ENV['student']->get_userinfo_by_userno($userno);
			if ($data){
				//获取父母亲的姓名
				$familydata = $_ENV['parent']->get_parentinfo_by_familyid($data['familyid']);
				foreach ($familydata as $f) {
					if ($f['jtgxm']=='51'){
						$data['father'] = $f['xm'];
					}
					if ($f['jtgxm']=='52'){
						$data['mother'] = $f['xm'];
					}
				}
				
			}
			return $data;
		}else
			return null;
	}
	
	//根据ID获取相关数据
	function onget_userinfo_by_id() {
		$this->init_input();
		$id = $this->input('id');
		if (intval($id)){
			$data = $_ENV['student']->get_student_by_id($id);
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
			$data = $_ENV['student']->get_deptinfo_by_uid($uid);
			return $data;
		}
		else
			return null;
	}
	
	//根据姓名获取相关信息
	function onget_student_by_name(){
		$this->init_input();
		$stuName = $this->input('stuName');
		$parentName = $this->input('parentName');
		if (!empty($stuName)){
			$data = $_ENV['student']->get_student_by_name($stuName,$parentName);
			return $data;
		}
		else
			return null;
	}
	
	//获取学生信息并统计男女生人数和全班总人数
	function onget_studentcount_ById()
	{
		$this->init_input();
		$id = $this->input('id');
		if (intval($id)){
			$data = $_ENV['student']->get_studentcount_ById($id);
			return $data;
		}
		else
			return null;
	}
	
	//获取学生信息
	function onget_student_ById()
	{
		$this->init_input();
		$classid = $this->input('classid');
		if (intval($classid)){
			$data = $_ENV['student']->get_student_ById($classid);
			return $data;
		}
		else
			return null;
	}
	
	//根据班级同学姓名获取学生uid
	function onget_student_By_IdandName()
	{
		$this->init_input();
		$id = $this->input('id');
		$name = $this->input('name');
		if (intval($id)){
			$data = $_ENV['student']->get_student_By_IdandName($id,$name);
			return $data;
		}
		else
			return null;
	}
	
	
	//获取未安排座位学生信息人数
	function onget_noseat_student_ById()
	{
		$this->init_input();
		$id = $this->input('id');
		$identityids = $this->input('identityids');
		if (intval($id)){
			$data = $_ENV['student']->get_noseat_student_ById($id,$identityids);
			return $data;
		}
		else
			return null;
	}
	//统计本月过生日的人
	function onget_birthday_student_ById()
	{
		$this->init_input();
		$id = $this->input('id');
		if (intval($id)){
			$data = $_ENV['student']->get_birthday_student_ById($id);
			return $data;
		}
		else
			return null;
	}
	/**
	 * 
	 +----------------------------------------------------------
	 * 获取学生的班级结构列表
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-3-22
	 +----------------------------------------------------------
	 */
	function onget_grade_list(){
		$this->init_input();
		$uid = $this->input('uid');
		if (intval($uid)){
			$data = $_ENV['student']->get_grades_by_uid($uid);
			return $data;
		}
		else
			return null;		
	}
	/**
	 +----------------------------------------------------------
	 * 根据学校id和班级ID获取学生名字
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-3-23
	 +----------------------------------------------------------
	 */
	function onget_student_list(){
		$this->init_input();
		$classId = $this->input('classId');
		$schoolId = $this->input('schoolId');
		if (intval($classId)&&intval($schoolId)){
			$data = $_ENV['student']->get_students_by_classid($classId,$schoolId);
			return $data;
		}
		else
			return null;		
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据学生和家长姓名获取学生基础信息
	 +----------------------------------------------------------
	 * @return array
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-4-2 上午9:51:50
	 +----------------------------------------------------------
	 */
	function onget_userinfo_by_name(){
		$this->init_input();
		$stuname = $this->input('stuname');
		$parname = $this->input('parname');
		$data = $_ENV['student']->get_userinfo_by_name($stuname,$parname);
		return $data;
	}
}

?>