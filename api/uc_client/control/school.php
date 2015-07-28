<?php

/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1082 2011-04-07 06:42:14Z svn_project_zhangjie $
*/

!defined('IN_UC') && exit('Access Denied');

class schoolcontrol extends base {

	function __construct() {
		$this->_schoolcontrol();
	}

	function _schoolcontrol() {
		parent::__construct();
		$this->load('school');
	}

	/**
	 +----------------------------------------------------------
	 * 获取基础数据中的学校列表
	 +----------------------------------------------------------
	 * @return array
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-14 上午11:33:50
	 +----------------------------------------------------------
	 */
	function onget_school_list() {
		$this->init_input();
		$type = $this->input('type');
		$fields = $this->input('fields');
		$fields = implode( ',' , $fields );
		return $_ENV['school']->get_school_list($type,$fields);
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取基础数据中的学校名称
	 +----------------------------------------------------------
	 * @return array
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-14 上午11:33:50
	 +----------------------------------------------------------
	 */
	function onget_school_info() {
		$this->init_input();
		$uid = $this->input('uid');
		return $_ENV['school']->get_school_info($uid);
	}

}

?>