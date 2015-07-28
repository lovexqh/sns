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

class classinfocontrol extends base {

	function __construct() {
		$this->_classinfocontrol();
	}

	function _classinfocontrol() {
		parent::__construct();
		$this->load('classinfo');
	}
	
	//根据班级编号获取班级基本信息
	function onget_classinfo_ById()
	{
		$this->init_input();
		$id = $this->input('id');
		if (intval($id)){
			$data = $_ENV['classinfo']->get_classinfo_byId($id);
			return $data;
		}
		else
			return null;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据学校的ID获取所有班级列表
	 +----------------------------------------------------------
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-14 下午1:33:22
	 +----------------------------------------------------------
	 */
	function onget_classlist_by_sid(){
		$this->init_input();
		$sid = $this->input('sid');
		$nj = $this->input('nj');
		$xd = $this->input('xd');
		if (intval($sid)){
			$fields = array('id','bj');
			$fields = implode( ',' , $fields );
			$data = $_ENV['classinfo']->get_classlist_by_sid($sid,$nj,$xd,$fields);
			return $data;
		}
		else
			return null;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据用户ID获取学生所在班级的信息
	 +----------------------------------------------------------
	 * @return array 结果集
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-15 上午11:34:33
	 +----------------------------------------------------------
	 */
	function onget_classinfo_by_uid(){
		$this->init_input();
		$uid = $this->input('uid');
		if (intval($uid)){
			$fields = array('id','bm');
			$fields = implode( ',' , $fields );
			$data = $_ENV['classinfo']->get_classinfo_by_uid($uid,$fields);
			$result = array(
				'id'	=>	$data['id'],
				'departname'	=>	$data['bj']		
			);
			return $result;
		}
		else
			return null;
	}
}

?>