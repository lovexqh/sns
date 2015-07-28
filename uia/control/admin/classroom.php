<?php
session_start();
/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1096 2011-05-13 11:26:36Z svn_project_zhangjie $
*/

!defined('IN_UC') && exit('Access Denied');

define('UC_ROSTER_CHECK_ROLENAME_FAILED', -1);
define('UC_ROSTER_CHECK_ROLENAME_EXISTS', -2);

define('UC_ROSTER_CHECK_ROLENAME_EXISTS', -2);
define('UC_LOGIN_SUCCEED', 0);


class control extends adminbase {


	function __construct() {
		$this->control();
	}
	function control() {
		parent::__construct();
		if(getgpc('a') != 'login' && getgpc('a') != 'logout') {
			if(!$this->user['isfounder'] && !$this->user['allowadminclassroom']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('classroom');
	}
	function onls(){		
		$this->view->display('admin_classroom');
	}
	
	function onListTree(){
		$schoolid =$this->getLoginUserSchoolId();//如果是超级管理员,则加载所有的学校列表
		echo $_ENV['classroom']->get_classroom_tree($schoolid);
	}
	
	//显示专业信息返回json数组
	function onclassroomList(){
		$this->clearSession();	//清除公共区数据
		$data["eid"] = $_GET['eid'];
		$data["ss"] = $_GET['ss'];
		$this->view->assign('data', $data);
		$this->view->display('admin_classroom_list');
	}
	
	function ongetClassroomList(){
		$this->clearSession();	//清除公共区数据
		$eid = $_GET['eid'];
		$ss = $_GET['ss'];
	
		if(!ss){
			$schoolid = $this->getLoginUserSchoolId();
			if((int)$schoolid != -1){
				$wherear['schoolid']=$schoolid;
			}
		}
		
		$wherear['eid']=$eid;
		$wherear['ss']=$ss;
		$keyvalue = $_POST['key'];
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$jsonstr = json_encode( $_ENV['classroom']->get_classroom_list_grid($wherear,$keyvalue,$pageIndex,$pageSize));
		print_r($jsonstr);
	}
	
	function onClassroomAdd(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$_SESSION['id'] = $id;
		$building = $_ENV['classroom']->getBuildingByClrid($id);
		$_SESSION['buildingid'] = $building[id];
		if(!$id){
			$buildingid = $_GET['jxlid'];
			$_SESSION['buildingid'] = $buildingid;
			$building = $_ENV['classroom']->getBuildingById($buildingid);
			$this->view->assign("xxid", $building[xxid]);
			$this->view->assign("jxlmc", $building[jxlmc]);
		}
		
		$this->view->assign("jxlmc", $building[jxlmc]);
		$this->view->assign("jxlid", $buildingid);
		
		$this->view->assign("id", $id);
		$this->view->display("admin_classroom_add");
	}
	
	function ongetClassroomById(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$classroomfirst =  $_ENV['classroom']->getClassroom_by_id($id);
		print_r(json_encode($classroomfirst));
	}
	
	function onupClassroomById(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		$id = $_SESSION['id'];
		$jsh = $rows[0][jsh];
		$jsm = $rows[0][jsm];
		$num1 = $_ENV['classroom']->checkClassroomByJsh($jsh, $identityid);
		$num2 = $_ENV['classroom']->checkClassroomByJsm($jsm, $identityid);
		if($num1==1 && $num2==1){
			$_ENV['classroom']->upClassroomByArray($rows);
		}
	}
	
	function ondelClassroom(){
		$id = isset($_POST['id']) ? $_POST['id'] : 0;
		$_ENV['classroom']->delClassroomById($id);
	}

	function onaddClassroom(){
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		$id = $_SESSION['id'];
		$jsh = $rows[0][jsh];
		$jsm = $rows[0][jsm];
		$num1 = $_ENV['classroom']->checkClassroomByJsh($jsh, $identityid);
		$num2 = $_ENV['classroom']->checkClassroomByJsm($jsm, $identityid);
		if($num1==1 && $num2==1){
			$_ENV['classroom']->addClassroomByArray($rows);
		}
	}

	function oncheckClassroomByJsh(){
		$id = $_SESSION['id'];
		$jsh = $_POST['jsh'];
		echo  $_ENV['classroom']->checkClassroomByJsh($jsh, $id);
	}
	function oncheckClassroomByJsm(){
		$id = $_SESSION['id'];
		$buildingid = $_SESSION['buildingid'];
		$jsm = $_POST['jsm'];
		echo  $_ENV['classroom']->checkClassroomByJsm($jsm, $id, $buildingid);
	}

	/*
	 * 清除公共交换区的内容
	*/
	private function clearSession(){
		$_SESSION['LoginUserSchoolId']=null;
		$_SESSION['id']=null;
		$_SESSION['buildingid'];
	}
	
}



?>