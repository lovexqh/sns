<?php

/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1082 2011-04-07 06:42:14Z svn_project_zhangjie $
*/

!defined('IN_UC') && exit('Access Denied');

class gradecontrol extends base {

	function __construct() {
		$this->_gradecontrol();
	}

	function _gradecontrol() {
		parent::__construct();
		$this->load('grade');
	}

	/**
	 +----------------------------------------------------------
	 * 获取基础数据中的年级列表
	 +----------------------------------------------------------
	 * @return array
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-14 上午11:33:50
	 +----------------------------------------------------------
	 */
	function onget_grade_list() {
		$this->init_input();
		$sid = $this->input('sid');
		$xd = $this->input('xd');
		return $_ENV['grade']->get_grade_list($sid,$xd);
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据学段ID获取年级信息
	 +----------------------------------------------------------
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-4-1 下午4:48:11
	 +----------------------------------------------------------
	 */
	function onget_grades_by_xd(){
		$this->init_input();
		$xdid = $this->input('xdid');
		return $_ENV['grade']->get_grades_by_xd($xdid);
	}

}

?>