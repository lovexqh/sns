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
			if(!$this->user['isfounder'] && !$this->user['allowadminspecialty']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('specialty');
	}
	function onls(){		
		$this->view->display('admin_specialty');
	}
	
	function onListTree(){
		$schoolid =$this->getLoginUserSchoolId();//如果是超级管理员,则加载所有的学校列表
		echo $_ENV['specialty']->get_specialty_tree($schoolid);
	}
	
	//显示专业信息返回json数组
	function onspecialtyList(){
		$this->clearSession();	//清除公共区数据
		$data["eid"] = $_GET['eid'];
		$data["ss"] = $_GET['ss'];
		$this->view->assign('data', $data);
		$this->view->display('admin_specialty_list');
	}
	
	function ongetSpecialtyList(){
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
		$jsonstr = json_encode( $_ENV['specialty']->get_specialty_list_grid($wherear,$keyvalue,$pageIndex,$pageSize));
		print_r($jsonstr);
	}
	
	function onSpecialtyAdd(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$_SESSION['id'] = $id;
		$academy = $_ENV['specialty']->getAcademyBySpeid($id);
		if(!$id){
			$acaid = $_GET['yxid'];
			$academy = $_ENV['specialty']->getAcademyById($acaid);
			$this->view->assign("xxid", $academy[xxid]);
			$this->view->assign("yxmc", $academy[yxmc]);
		}
		
		$this->view->assign("yxmc", $academy[yxmc]);
		$this->view->assign("yxid", $acaid);
		
		$this->view->assign("id", $id);
		$this->view->display("admin_specialty_add");
	}
	
	function ongetSpecialtyById(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$specialtyfirst =  $_ENV['specialty']->getSpecialty_by_id($id);
		print_r(json_encode($specialtyfirst));
	}
	
	function onupSpecialtyById(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		$id = $_SESSION['id'];
		$zyh = $rows[0][zyh];
		$zymc = $rows[0][zymc];
		$num1 = $_ENV['specialty']->checkSpecialtyByZyh($zyh, $id);
		$num2 = $_ENV['specialty']->checkSpecialtyByZymc($zymc, $id);
		if($num1==1 && $num2==1){
			$_ENV['specialty']->upSpecialtyByArray($rows);
		}
	}
	
	function ondelSpecialty(){
		$id = isset($_POST['id']) ? $_POST['id'] : 0;
		$_ENV['specialty']->delSpecialtyById($id);
	}

	function onaddSpecialty(){
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		$id = $_SESSION['id'];
		$zyh = $rows[0][zyh];
		$zymc = $rows[0][zymc];
		$num1 = $_ENV['specialty']->checkSpecialtyByZyh($zyh, $identityid);
		$num2 = $_ENV['specialty']->checkSpecialtyByZymc($zymc, $identityid);
		if($num1==1 && $num2==1){
			$_ENV['specialty']->addSpecialtyByArray($rows);
		}
	}

	function oncheckSpecialtyByZyh(){
		$id = $_SESSION['id'];
		$zyh = $_POST['zyh'];
		echo  $_ENV['specialty']->checkSpecialtyByZyh($zyh, $id);
	}
	function oncheckSpecialtyByZymc(){
		$id = $_SESSION['id'];
		$zymc = $_POST['zymc'];
		echo  $_ENV['specialty']->checkSpecialtyByZymc($zymc, $id);
	}
	
/*---------------------------------------------------------------------------------------------------------------------------*/
	function onInit(){
		$this->clearSession();	//清除公共区数据

		$schoolId=$this->getLoginUserSchoolId();

		if($schoolId!=-1){		//非超级管理员登录,则将学校id存入session,做为公共变量
			$_SESSION['LoginUserSchoolId']=$schoolId;
		}

		$this->onSearch();
	}

	/**
	 * 生成专业导航树
	 */
	function onClassTreeNav(){

		$treeNode=$_ENV['specialty']->genTreeNav();
		echo json_encode($treeNode);
	}

	/**
	 * 点击院系树时查询专业信息
	 */
	function onTreeNav(){
		$this->clearSession();		//清除公共区数据

		$schoolid=$_GET['schoolid'];
		if($schoolid){
			$_SESSION['schoolid']=$schoolid;//将学校id存入session
			$schoolName=$_GET['schoolName'];
			$_SESSION['schoolName']=$schoolName;
		}else{
			$academyId=$_GET['academyId'];
			$academy = $_ENV['specialty']->getAcademyById($academyId);
			$_SESSION['yxbm']=$academy[yxbm];
			$xxid=$_GET['xxid'];
			$_SESSION['academyId']=$academyId;//将院系id存入session
			$_SESSION['xxid']=$xxid;//将学校id存入session
			$academyName=$_GET['academyName'];
			$_SESSION['academyid']=$academyId;
			$_SESSION['schoolid']=$schoolid;
			$_SESSION['academyName']=$academyName;//将院系名称存入session
		}
		$this->onSearch();
	}

	/**
	 * 点击添加 跳转到添加页面
	 */
	function onAdd(){
		$academyId = $_GET['academyid'];	//获取要添加专业的院系的id
		$schoolid = $_GET['schoolid'];
		
		$schoolId=$this->getLoginUserSchoolId();
		$academyId=$_SESSION['academyId'];
		$academyName = $_SESSION['academyName'];
		$yxbm = $_SESSION['yxbm'];

		$this->view->assign('yxbm', $yxbm);
		$this->view->assign('schoolid', $schoolid);
		$this->view->assign('schoolId', $schoolId);
		$this->view->assign('academyId', $academyId);
		$this->view->assign('academyName', $academyName);
		$this->view->assign('onAdd', true);
		$this->view->display('admin_specialty_add');
	}

	/**
	 * 添加专业信息 提交
	 *
	 */
	function onAddSubmit(){

		$success = $_ENV['specialty']->add_specialty();
		if(!$success){
			$errMsg=$_ENV['specialty']->errMsg;
			$error=array("code"=>0,'msg'=>$errMsg);
			echo json_encode($error);
		}else{
			echo json_encode(array("code"=>1));
		}
	}
	/**
	 * 专业信息查询
	 */
	function onSearch(){
		$condition =$_ENV['specialty']->searchCondition();		//查询符合条件的记录总数
		$this->showData($condition);
	}

	function onNextPage(){
		$condition=$_SESSION['condition'];
		$this->showData($condition);
	}

	private function showData($condition=''){
		$totalnum =$_ENV['specialty']->get_total_num($condition);	//查询符合条件的记录总数
		$specialtyList = $_ENV['specialty']->get_specialty_aPage($_GET['page'], UC_PPP, $totalnum,$condition);	//获得一页数据,其中,$_GET['page']参数由分页控制器提供

		$url='admin.php?m=specialty&a=NextPage';
		$multipage = $this->page($totalnum, UC_PPP, $_GET['page'], $url);//生成分页控制器,其中会自动包含$_GET['page']参数

		$academyName=$_SESSION['academyName'];//院系名称
		$academyid=$_SESSION['academyid'];//院系id
		$schoolid=$_SESSION['xxid'];//学校id

		$this->view->assign('schoolid', $schoolid);
		$this->view->assign('academyid', $academyid);
		$this->view->assign('academyName', $academyName);
		$this->view->assign('multipage', $multipage);
		$this->view->assign('specialtyList', $specialtyList);
		$_SESSION['condition']=$condition;//将查询条件存入session,点击"下一页"时需要使用
		$this->view->display('admin_specialty');
	}



	function onEdit() {
		$id = getgpc('ID');
		
		$_SESSION['zyid']=$id;
		$specialty=$_ENV['specialty']->get_a_specialty($id);

		$status = 0;
		$academyid = $specialty[yxid];
		
		$this->load ( specialty );
		// 获取所在院系
		$academy = $_ENV ['specialty']->getAcademyById($academyid);
		$academyName = $academy[yxmc];
		
		$yxbm = $_SESSION['yxbm'];
		
		$this->view->assign ( 'yxbm', $yxbm );
		$this->view->assign ( 'academyName', $academyName );
		$this->view->assign('specialty', $specialty);
		$this->view->assign('status', $status);
		$this->view->display('admin_specialty_add');
	}

	function onEditSubmit(){
		$zyid = $_SESSION['zyid'];	
		$success = $_ENV['specialty']->update_specialty($zyid);
		if(!$success){
			$errMsg=$_ENV['specialty']->errMsg;
			$error=array("code"=>0,'msg'=>$errMsg);
			echo json_encode($error);
		}else{
			echo json_encode(array("code"=>1));
		}
	}

	function onDelete(){
		$success = $_ENV['specialty']->delete_specialty();
		if(!$success){
			$errMsg=$_ENV['specialty']->errMsg;
			$this->message($errMsg,"admin.php?m=specialty&a=Init");
			return;
		}
		$this->onSearch();
	}

	/*
	 * 清除公共交换区的内容
	 */
	private function clearSession(){
		$_SESSION['LoginUserSchoolId']=null;
		$_SESSION['condition']=null;
		$_SESSION['schoolid']=null;
		$_SESSION['academyId']=null;
		$_SESSION['academyName']=null;
	}

}



?>