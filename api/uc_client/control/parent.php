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

class parentcontrol extends base {

	function __construct() {
		$this->_parentcontrol();
	}

	function _parentcontrol() {
		parent::__construct();
		$this->load('parent');
	}

	function onget_userinfo_by_userno() {
		$this->init_input();
		$userno = $this->input('userno');
		$name = $this->input('name');
		if(trim($userno)){
			$data = $_ENV['parent']->get_userinfo_by_userno($userno,$name);
			if ($data){
				$_data = $_ENV['parent']->get_studentinfo_by_familyid($data['familyid']);
				foreach ($_data as $_v) {
					$sonnames[] = $_v['xm'];
					$sonxhs[] = $_V['xh'];
				}
				$data['sonname'] = $sonnames;
				$data['sonxh'] = $sonxhs;
			}
			
			return $data;
		}else
			return null;
	}
	
	//根据ID获取相关数据
	function onget_userinfo_by_id() {
		$this->init_input();
		$id = $this->input('userno');
		$name = $this->input('name');
		if (intval($id)){
			$data = $_ENV['parent']->get_parent_by_id($id,$name);
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
			$data = $_ENV['parent']->get_deptinfo_by_uid($uid);
			return $data;
		}
		else
			return null;
	}

}

?>