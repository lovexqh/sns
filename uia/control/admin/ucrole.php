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
			if(!$this->user['isfounder'] && !$this->user['allowadminucrole']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('ucrole');
	}
	
	function onls(){
		$this->view->display('admin_ucrole');
	}
	
	function ongetRoleList(){
		$this->clearSession();	//清除公共区数据
		
		$keyvalue = $_POST['key'];
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$jsonstr = json_encode( $_ENV['ucrole']->get_role_list_grid($keyvalue,$pageIndex,$pageSize));
		print_r($jsonstr);
	}
	
	function onRoleAdd(){
		$roleid = isset($_GET['roleid']) ? $_GET['roleid'] : 0;
		$this->view->assign('roleid', $roleid);
		$this->view->display("admin_ucrole_add");
	}
	
	function onaddRole(){
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		$roleid = $_GET['roleid'];
		$roleName = $rows[0][roleName];
		$num = $_ENV['ucrole']->checkRoleByRoleName($roleid, $roleName);
		if($num==1){
			$_ENV['ucrole']->addRoleByArray($roleid, $roleName);
		}
	}
	
	function ondelRole(){
		$roleID = isset($_POST['roleID']) ? $_POST['roleID'] : 0;
		$_ENV['ucrole']->delRoleById($roleID);
	}
	
	function oneditRole(){
		$this->clearSession();	//清除公共区数据
		$roleId = $_GET['roleID'];	//传参成功
		$_SESSION['roleId'] = $roleId;
		$role = $_ENV['ucrole']->getRoleById($roleId);
		$this->view->assign('roleId', $roleId);
		$this->view->assign('roleName', $role[roleName]);
		$this->view->display('admin_ucrole_edit');
	}
	
	function oncheckRoleByRoleName(){
		$roleId = $_GET['roleId'];
		$roleName = $_POST['roleName'];
		echo  $_ENV['ucrole']->checkRoleByRoleName($roleId, $roleName);
	}
	
	function ongetFunList(){
		$roleId = $_GET['roleID'];	//成功
		$roleFuncList = $_ENV ['ucrole']->get_role_funcList ( $roleId );
		print_r(json_encode($roleFuncList));
	}
	
	function ongetOtherFunList(){
		$roleId = $_GET['roleID'];
		$roleOtherFuncList = $_ENV ['ucrole']->get_role_otherFuncList ( $roleId );
		print_r(json_encode($roleOtherFuncList));
	}
	
	function onajaxAddFunc(){
		$roleId = $_POST['roleID'];
		$func = $_POST ['func'];
		$_ENV['ucrole']->addFuncList($roleId, $func);
	}
	
	function onajaxDelFunc(){
		$roleId = $_POST['roleID'];
		$func = $_POST ['func'];
		$_ENV['ucrole']->delFuncList($roleId, $func);
	}
	
	function onajaxSavaData(){
		$json = $_POST['data'];
 		$jsonstr = stripslashes($json);
		$rows = json_decode($jsonstr,true);
		$_ENV['ucrole']->saveDataByArray($rows);
	}
	
	function ongetRoleById(){
		$roleid = $_GET['roleid'];
		$role = $_ENV['ucrole']->getRoleById($roleid);
		print_r(json_encode($role));
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