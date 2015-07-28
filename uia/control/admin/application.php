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
			if(!$this->user['isfounder'] && !$this->user['allowadminapplication']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('application');
	}
	function onls(){		
		$this->view->display('admin_application');
	}
	
	//显示专业信息返回json数组
	function onappList(){
		$this->clearSession();	//清除公共区数据
		$this->view->display('admin_application_list');
	}
	
	function ongetAppList(){
		$this->clearSession();	//清除公共区数据
		
		$keyvalue = $_POST['key'];
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$jsonstr = json_encode( $_ENV['application']->get_app_list_grid($keyvalue,$pageIndex,$pageSize));
		print_r($jsonstr);
	}
	
	function onAppAdd(){
		$id = isset($_GET['app_id']) ? $_GET['app_id'] : 0;
		$_SESSION['id'] = $id;
		$date = date("Y-m-d");
		$this->view->assign("date", $date);
		$this->view->assign("id", $id);
		$this->view->display("admin_application_add");
	}
	
	function ongetAppById(){
		$id = isset($_GET['app_id']) ? $_GET['app_id'] : 0;
		$appfirst =  $_ENV['application']->getApp_by_id($id);
		print_r(json_encode($appfirst));
	}
	
	function onupAppById(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		$id = $_SESSION['id'];
		$name = $rows[0][app_name];
		$alias = $rows[0][app_alias];
		$num1 = $_ENV['application']->checkAppByName($name, $id);
		$num2 = $_ENV['application']->checkAppByAlias($alias, $id);
		if($num1==1 && $num2==1){
			$_ENV['application']->upAppByArray($rows);
		}
	}
	
	function ondelApp(){
		$id = isset($_POST['app_id']) ? $_POST['app_id'] : 0;
		$_ENV['application']->delAppById($id);
	}

	function onaddApp(){
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		$id = $_SESSION['id'];
		$name = $rows[0][app_name];
		$alias = $rows[0][app_alias];
		$num1 = $_ENV['application']->checkAppByName($name, $id);
		$num2 = $_ENV['application']->checkAppByAlias($alias, $id);
		if($num1==1 && $num2==1){
			$_ENV['application']->addAppByArray($rows);
		}
	}

	function oncheckAppByName(){
		$id = $_SESSION['id'];
		$name = $_POST['app_name'];
		echo $_ENV['application']->checkAppByName($name, $id);
	}
	function oncheckAppByAlias(){
		$id = $_SESSION['id'];
		$alias = $_POST['app_alias'];
		echo $_ENV['application']->checkAppByAlias($alias, $id);
	}
	
	/*
	 * 清除公共交换区的内容
	 */
	private function clearSession(){
		$_SESSION['LoginUserSchoolId']=null;
		$_SESSION['condition']=null;
		$_SESSION['schoolid']=null;
		$_SESSION['id']=null;
	}

}



?>