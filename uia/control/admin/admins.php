<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: admin.php 1059 2011-03-01 07:25:09Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class control extends adminbase {

	function __construct() {
		$this->control();
	}

	function control() {
		parent::__construct();
		$this->load('user');
		$this->check_priv();
		if(!$this->user['isfounder'] && !$this->user['allowadminadmins']) {
			$this->message('no_permission_for_this_module');
		}

		$this->load('admins');
	}
	
	function onls(){
		$this->view->display('admin_admin');
	}
	
	//显示专业信息返回json数组
	function onadminList(){
		$this->view->display('admin_admin_list');
	}
	
	function ongetNewAdminList(){
		$this->clearSession();	//清除公共区数据
		$keyvalue = $_POST['key'];
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$jsonstr = json_encode( $_ENV['admins']->get_newadmin_list_grid($keyvalue,$pageIndex,$pageSize));
		print_r($jsonstr);
	}
	
	function onteacherList(){
		$data['logschoolid'] = $_GET['logschoolid'];
		$data['schoolid'] = $_GET['schoolid'];
		$data['depid'] = $_GET['deptId'];
		$data['updepid'] = $_GET['updepid'];
		$this->view->assign('data', $data);
		$this->view->display('admin_esnadmin_select');
	}
	
	function ongetMemberList(){
		$deptid = $_GET['deptid'];
		$updepid = $_GET['updeptid'];
		$schoolid = $_GET['schoolid'];
		$logschoolid = $_GET['logschoolid'];
		$keyvalue = $_POST['key'];
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$memberList =  $_ENV['admins']->getMemberList($logschoolid, $schoolid, $updepid, $deptid,$keyvalue,$pageIndex,$pageSize);
		print_r(json_encode($memberList));
	}
	
	function onaddadmin(){
		$uid = $_POST['uid'];
		$uid = explode(',', $uid);
		$_ENV['admins']->addadmin($uid);
	}
	
	function onNewAdminAdd(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$this->view->assign('id', $id);
		$this->view->display("admin_newadmin_add");
	}
	
	function onadminAdd(){
		$logschoolid = $this->getLoginUserSchoolId();	//获取登录用户schoolid，超级管理员为-1
		$this->view->assign('logschoolid', $logschoolid);
		$this->view->display('admin_esnadmin_add');
	}
	
	function onTeacherTree(){
		$logschoolid = $_GET['logschoolid'];
		echo $_ENV['admins']->getTeacherDeptTree($logschoolid);
	}
	
	function onaddNewAdmin(){				//////////////判断两次输入的密码是否一致
		$this->clearSession();
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		$md5pwd = md5($rows[0]['password']);
		$rows[0]['password'] = $md5pwd;
		$userName = $rows[0]['username'];
		$id = $rows[0]['id'];
		$num1 = $_ENV['admins']->checkAdminByUsername($id, $userName);
		if($num1==1){
			$_ENV['admins']->addNewAdminByArray($rows);
		}
			
	}
	
	function oncheckAdminByUsername(){
		$id = $_GET['id'];
		$username = $_POST['username'];
		echo  $_ENV['admins']->checkRoleByRoleName($id, $username);
	}
	
	function ongetSchool(){
		$school = $_ENV['admins']->getSchool();
		print_r(json_encode($school));
	}
	
	function ondelNewAdmin(){
		$id = isset($_POST['id']) ? $_POST['id'] : 0;
		$_ENV['admins']->delNewAdminById($id);
	}
	
	function ongetAdminById(){
		$id = $_GET['id'];
		$admin = $_ENV['admins']->getAdminById($id);
		print_r(json_encode($admin));
	}
	
	function ongetAdminRole(){
		$id = isset($_GET['id']) ?  $_GET['id'] : 0;
		$this->view->assign('id', $id);
		$this->view->display('admin_admin_role');
	}
	
	function ongetRoleById(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$adminRole = $_ENV['admins']->getRoleById($id);
		print_r(json_encode($adminRole));
	}
	
	function ongetOtherRoleById(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$otherRole = $_ENV['admins']->getOtherRoleById($id);
		print_r(json_encode($otherRole));
	}
	
	function ongetAppLimit(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$apps = $_ENV['admins']->getAppLimit($id);		
		$num = count ( $apps );
		for($i = 0; $i < $num; $i ++) {
			$limit = explode ( ",", $apps [$i] ['roleExtend'] );
			$limitResult = array_unique ( $limit );
			$str = "";
			foreach ( $limitResult as $value ) {
				if ($value == "I") {
					$str .= "增加" . ",";
				}
				if ($value == "D") {
					$str .= "删除" . ",";
				}
				if ($value == "U") {
					$str .= "修改" . ",";
				}
				if ($value == "S") {
					$str .= "查看" . ",";
				}
				if ($value == "C") {
					$str .= "跨校" . ",";
				}
			}
			if($str != ""){
				$len = strlen($str);
				$str1 = substr($str, 0, $len-1);
			}else{
				$str1 = "";
			}
			$apps [$i] ['roleExtend'] = $str1;
		}
		print_r ( json_encode ( $apps ) );
	}
	
	function onaddRole(){
		$adminid = isset($_GET['adminid']) ? $_GET['adminid'] : 0;
		$roleid = isset($_POST['roleid']) ? $_POST['roleid'] : 0;
		if($adminid != 0){
			$_ENV['admins']->addRole($adminid, $roleid);
		}
	}
	
	function ondelRole(){
		$adminid = isset($_GET['adminid']) ? $_GET['adminid'] : 0;
		$roleid = isset($_POST['roleid']) ? $_POST['roleid'] : 0;
		if ($adminid != 0) {
			foreach ( $roleid as $value ) {
				$_ENV ['admins']->delRole ( $adminid, $value );
			}
		}
	}
	
	// 清除公共交换区的内容
	private function clearSession(){
		$_SESSION['LoginUserSchoolId']=null;
		$_SESSION['condition']=null;
		$_SESSION['schoolid']=null;
		$_SESSION['id']=null;
	}

}

?>