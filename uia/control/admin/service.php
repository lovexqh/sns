<?php


/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1096 2011-05-13 11:26:36Z svn_project_zhangjie $
*/

class control extends adminbase {
	function __construct() {
		$this->control();
	}
	function control() {
		parent :: __construct();
		if (getgpc('a') != 'login' && getgpc('a') != 'logout') {
			if (!$this->user['isfounder'] && !$this->user['allowadminservice']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('service');
	}
	
	function onls(){
		$this->view->display('admin_service');
	}
	
	/**
	 *
	 * @Title: ongetServiceList
	 * @Description:显示服务的信息
	 * @author 夏伟
	 */
	function ongetServiceList(){
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$keyvalue = $_POST['key'];
		$servicelist = $_ENV['service']->getServiceList($keyvalue, $pageIndex,$pageSize);
		$jsonstr = json_encode($servicelist);
		print_r($jsonstr);
	}
	
	/**
	 *
	 * @Title: onserviceAdd
	 * @Description:显示服务添加界面
	 * @author 夏伟
	 */
	function onserviceAdd(){
		$id = $_GET['id'];
		if($id){
			$this->view->assign('id', $id);
		}
		$this->view->display("admin_service_add");
	}
	
	function onupServiceById(){
		$id = $_GET['id'];
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		if($id){		//更新操作
			$_ENV['service']->upServiceByArray($rows, $id);
		}else{		//增加操作
			$_ENV['service']->addServiceByArray($rows);
		}
	}
	
	function ongetSchoolBySchoolid(){
		$schoolid = isset($_GET['schoolid']) ? $_GET['schoolid'] : 0;
		$schoollist = $_ENV['school']->getSchoolById($schoolid);
		print_r(json_encode($schoollist));
	}
	
	function ondelSchool(){
		$schoolid = isset($_POST['schoolid']) ? $_POST['schoolid'] : 0;
		$_ENV['school']->delSchoolById($schoolid);
	}
		
	function oncheckServiceByServiceid(){
		$id = $_GET['id'];
		$serviceID = $_POST['serviceID'];
		echo $_ENV['service']->checkServiceByServiceid($id, $serviceID);
	}
	
	function ondelService(){
		$id = isset($_POST['id']) ? $_POST['id'] : 0;
		$_ENV['service']->delServiceById($id);
	}
	
	function ongetServiceById(){
		$id = $_GET['id'];
		$servicefirst = $_ENV['service']->getServiceById($id);
		print_r(json_encode($servicefirst));
	}
	
	function onshowParam(){
		$id = $_GET['id'];
		$service = $_ENV['service']->getServiceById($id);
		$this->view->assign('id',$id);
		$this->view->assign('service',$service);
		$this->view->display('admin_service_param');
	}
	
	//获取输入参数
	function ongetInputParamList(){
		$id = $_GET['id'];
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$inputParam = $_ENV['service']->getInputParamById($id, $pageIndex, $pageSize);
		print_r(json_encode($inputParam));
	}
	
	//获取输出参数
	function ongetOutputParamList(){
		$id = $_GET['id'];
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$outputParam = $_ENV['service']->getOutputParamById($id, $pageIndex, $pageSize);
		print_r(json_encode($outputParam));
	}
	
	function onInputParamAdd(){
		$id = $_GET['id'];
		$this->view->assign('id', $id);
		$this->view->display("admin_inputparam_add");
	}
	
	function onOutputParamAdd(){
		$id = $_GET['id'];
		$this->view->assign('id', $id);
		$this->view->display("admin_outputparam_add");
	}
	
	function onaddInputParam(){
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		$id = $rows[0][fwid];
		$csmc = $rows[0][csmc];
		$num = $_ENV['service']->checkInputParamByCsmc($id, $csmc);
		if($num == 1){
			$_ENV['service']->addParamByArray($rows);
		}
	}
	
	function onaddOutputParam(){
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		$id = $rows[0][fwid];
		$csmc = $rows[0][csmc];
		$num = $_ENV['service']->checkOutputParamByCsmc($id, $csmc);
		if($num == 1){
			$_ENV['service']->addParamByArray($rows);
		}
	}
	
	function ondelParam(){
		$id = isset($_POST['id']) ? $_POST['id'] : 0;
		$_ENV['service']->delParamById($id);
	}
	
	function oncheckInputParamByCsmc(){
		$id = $_GET['id'];
		$csmc = $_POST['csmc'];
		echo $_ENV['service']->checkInputParamByCsmc($id, $csmc);
	}
	
	function oncheckOutputParamByCsmc(){
		$id = $_GET['id'];
		$csmc = $_POST['csmc'];
		echo $_ENV['service']->checkOutputParamByCsmc($id, $csmc);
	}
	
}
?>