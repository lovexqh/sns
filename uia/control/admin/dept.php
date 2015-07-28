<?php
session_start();
/*
 [RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
This is NOT a freeware, use is subject to license terms

$Id: user.php 1096 2011-05-13 11:26:36Z svn_project_zhangjie $
*/


!defined('IN_UC') && exit('Access Denied');

define('UC_DEPT_CHECK_DEPTNAME_FAILED', -1);
define('UC_DEPT_CHECK_DEPTNAME_EXISTS', -2);
define('UC_USER_CHECK_UpDeptID_FAILED', -3);
define('UC_LOGIN_SUCCEED', 0);

class control extends adminbase {


	function __construct() {
		$this->control();
	}

	function control() {
		parent::__construct();
		if(getgpc('a') != 'login' && getgpc('a') != 'logout') {
			if(!$this->user['isfounder'] && !$this->user['allowadmindept']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('dept');
	}

	function ondeptList(){
		//$this->clearSession();	//清除公共区数据
		$data["schoolid"] = $_GET['schoolid'];
		$data["depid"] = $_GET['deptId'];
		//var_dump($data);
		$this->view->assign('data', $data);
		$this->view->display('admin_dept_list');
	}




	function ongetDeptList(){
		//$this->clearSession();	//清除公共区数据
		if(!isset($_GET['schoolid'])){
			$schoolid = $this->getLoginUserSchoolId();
			if((int)$schoolid != -1){
				$wherear['schoolid']=$schoolid;
			}
		}else{
			$wherear['schoolid']=$_GET['schoolid'];
		}
		$wherear['depid']=$_GET['deptId'];
		$keyvalue = $_POST['key'];
		//var_dump($wherear);
		$data = $_ENV['dept']->get_dept_list_grid($wherear,$keyvalue);
		$data['UpDeptName'] = $_ENV['dept']->get_dept_name($data['UpDeptID']);
		$this->view->assign('data', $data);
		$this->view->display('admin_dept_list');
	}


	/**
	 * 编辑部门信息时的提交
	 */
	function onEditSubmit(){
		$test = $_POST['submitData'];
		$jsonstr = stripslashes($test);
		$rows = json_decode($jsonstr,true);
		//var_dump($rows);
		$success = $_ENV['dept']->update_department($rows);
		$errMsg=$_ENV['dept']->errMsg;
		if(!$success){//如果更新不成功
			$error=array("code"=>0,'msg'=>$errMsg);
			echo json_encode($error);
		}else{
			echo json_encode(array("code"=>1,'msg'=>$errMsg));

		}

	}
	
	function onaddDept(){
		$updeptid = $_GET['updeptid'];
		$schoolid = isset($_GET['schoolid']) ? $_GET['schoolid'] : 0;
		if(!$updeptid){
			$updeptid = 0;
		}
		$this->view->assign('schoolid',$schoolid);
		$this->view->assign('updeptid',$updeptid);
		$this->view->display('admin_dept_add');
	}
	
	function oncheckName(){
		$deptname=$_POST['deptname'];
		$schoolid = isset($_GET['schoolid']) ? $_GET['schoolid'] : 0;
		$result = $_ENV['dept']->checkDepartName($deptname, $schoolid);
		echo $result;
	}
	
	function ondoAddDept(){
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);	//去掉"\"
		$rows = json_decode($jsonstr,true);
		$num = $_ENV['dept']->checkDepartName($rows[0]['DepartName'], $rows[0]['schoolid']);
		if($num == 1){
			$_ENV['dept']->doAddDept($rows[0]);
			print_r(json_encode(1));
		}
	}


	function onselect_tree(){
		$this->view->display('admin_TreeWindow');
	}

	function onlsDept(){

		$this->view->display('admin_deptNew');
	}

}
?>