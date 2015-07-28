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
		if(!$this->user['isfounder'] && !$this->user['allowadminmodule']) {
			$this->message('no_permission_for_this_module');
		}
		$this->load('module');
	}
	
	function onls(){
		$this->view->display('admin_module');
	}
	
	function ongetModuleList(){
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$jsonstr = json_encode( $_ENV['module']->getModuleList($pageIndex,$pageSize));
		print_r($jsonstr);
	}
	
	function onModuleAdd(){
		$id = $_GET['id'];
		if($id){
			$this->view->assign('id', $id);
		}
		$this->view->display("admin_module_add");
	}
	
// 	function onaddModule(){
// 		$jsonstr = $_POST['data'];
// 		$jsonstr = stripslashes($jsonstr);
// 		$rows = json_decode($jsonstr,true);
// 		$id = isset($_GET['id']) ? $_GET['id'] : 0;
// 		$appAlias = $rows[0][appAlias];
// 		$appEntry = $rows[0][appEntry];
// 		$num1 = $_ENV['module']->checkModuleByAppalias($appAlias, $id);
// 		$num2 = $_ENV['module']->checkModuleByAppentry($appEntry, $id);
// 		if($num1==1 && $num2==1){
// 			if($id){
// 				$_ENV['module']->upModuleByArray($rows, $id);
// 			}else{
// 				$_ENV['module']->addModuleByArray($rows);
// 			}
// 		}
// 	}
	
				
	function oncheckAppname(){
		$appName = $_POST['appName'];
		echo  $_ENV['module']->checkModuleByAppname($appName);
	}
	
	function oncheckAppalias(){
		$appAlias = $_POST['appAlias'];
		echo  $_ENV['module']->checkModuleByAppalias($appAlias);
	}
	
	function oncheckAppentry(){
		$appEntry = $_POST['appEntry'];
		echo  $_ENV['module']->checkModuleByAppentry($appEntry);
	}
	
// 	function ondelModule(){
// 		$id = isset($_POST['id']) ? $_POST['id'] : 0;
// 		$_ENV['module']->delModuleById($id);
// 	}
	
	function ongetModuleById(){
		$id = $_GET['id'];
		$modulefirst =  $_ENV['module']->getModuleById($id);
		print_r(json_encode($modulefirst));
	}
	
	/*----------------------------------------*/
	
	function onlistTree(){
		echo $_ENV['module']->getDeptTree();
	}
	
	function onmoduleEdit(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$module = $_ENV['module']->getModuleById($id);
		$this->view->assign('id', $id);
		$this->view->assign('module', $module);
		$this->view->display("admin_module_edit");
	}
	
	function onupModuleById(){
		$modulejson = $_POST['data'];
		$modulejson = stripslashes($modulejson);
		$module = json_decode($modulejson,true);
		$id = $module[0]['id'];
		$appName = $module[0]['appName'];
		$appAlias = $module[0]['appAlias'];
		$appEntry = $module[0]['appEntry'];
		$num = $_ENV['module']->chkModule($id, $appName, $appAlias, $appEntry);
		if($num == 0){
			$n = $_ENV['module']->upModuleById($module[0]);
			if($n == 1){
				print_r(json_encode(0));
			}
		}else{
			print_r(json_encode($num));
		}
	}
	
	function onaddModule(){
		$pid = $_GET['pid'];
		$this->view->assign('pid', $pid);
		$this->view->display("admin_module_add");
	}
	
	//添加同级模块
	function onaddNewModule(){
		$modulejson = $_POST['data'];
		$modulejson = stripslashes($modulejson);
		$module = json_decode($modulejson,true);
		$_ENV['module']->addNewModule($module);
	}
	
	function ondelModule(){
		$id = $_POST['id'];
		$_ENV['module']->delModuleById($id);
	}

}

?>