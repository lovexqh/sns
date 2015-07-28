<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1096 2011-05-13 11:26:36Z svn_project_zhangjie $
*/

!defined('IN_UC') && exit('Access Denied');

define('UC_ROLE_CHECK_ROLENAME_FAILED', -1);
define('UC_ROLE_CHECK_ROLENAME_EXISTS', -2);



define('UC_LOGIN_SUCCEED', 0);




class control extends adminbase {


	function __construct() {
		$this->control();
	}
	function control() {
		parent::__construct();
		if(getgpc('a') != 'login' && getgpc('a') != 'logout') {
			if(!$this->user['isfounder'] && !$this->user['allowadminrole']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('role');
	}
	
	function onls(){
		$this->view->display('admin_role');
	}
	
	function ongetRoleList(){
		$this->clearSession();	//清除公共区数据
		
		$keyvalue = $_POST['key'];
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$jsonstr = json_encode( $_ENV['role']->get_role_list_grid($keyvalue,$pageIndex,$pageSize));
		print_r($jsonstr);
	}
	
	function onRoleAdd(){
		$this->view->display("admin_role_add");
	}
	
	function onaddRole(){
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		$roleName = $rows[0][RoleName];
		$num = $_ENV['role']->checkRoleByRoleName($roleName);
		if($num==1){
			$_ENV['role']->addRoleByArray($rows);
		}
	}
	
	function ondelRole(){
		$RoleID = isset($_POST['RoleID']) ? $_POST['RoleID'] : 0;
		$_ENV['role']->delRoleById($RoleID);
	}
	
	function oneditRole(){
		$this->clearSession();	//清除公共区数据
		$roleId = $_GET['RoleID'];	//传参成功
		$_SESSION['roleId'] = $roleId;
		$role = $_ENV['role']->getRoleById($roleId);
		$this->view->assign('roleId', $roleId);
		$this->view->assign('roleName', $role[RoleName]);
		$this->view->display('admin_role_edit');
	}
	
	function oncheckRoleByRoleName(){
		$RoleName = $_POST['RoleName'];
		echo  $_ENV['role']->checkRoleByRoleName($RoleName);
	}
	
	function ongetFunList(){
		$roleId = $_GET['RoleID'];	//成功
		$roleFuncList = $_ENV ['role']->get_role_funcList ( $roleId );
		print_r(json_encode($roleFuncList));
	}
	
	function ongetOtherFunList(){
		$roleId = $_GET['RoleID'];
		$roleOtherFuncList = $_ENV ['role']->get_role_otherFuncList ( $roleId );
		print_r(json_encode($roleOtherFuncList));
	}
	
	function onajaxAddFunc(){
		$roleId = $_POST['RoleID'];
		$func = $_POST ['func'];
		$_ENV['role']->addFuncList($roleId, $func);
	}
	
	function onajaxDelFunc(){
		$roleId = $_POST['RoleID'];
		$func = $_POST ['func'];
		$_ENV['role']->delFuncList($roleId, $func);
	}
	
	function onajaxSavaData(){
		$json = $_POST['data'];
 		$jsonstr = stripslashes($json);
		$rows = json_decode($jsonstr,true);
		$_ENV['role']->saveDataByArray($rows);
	}
	
	/*
	 * 清除公共交换区的内容
	*/
	private function clearSession(){
		$_SESSION['LoginUserSchoolId']=null;
		$_SESSION['roleId']=null;
	}
	
}
?>