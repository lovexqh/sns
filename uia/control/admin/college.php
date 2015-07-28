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
			if(!$this->user['isfounder'] && !$this->user['allowadmincollege']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('college');
	}
	
	function onls(){
		$this->view->display('admin_academyInfo');
		
	}
	
	/**
	 *
	 * @Title: onListTree
	 * @Description: 显示树
	 * @return
	 * @author 肖萌
	 */
	function  onListTree(){
		$schoolid = $this->getLoginUserSchoolId();
		echo $_ENV['college']->get_college_tree($schoolid);
	}
	
	/**
	 *
	 * @Title: onListTree
	 * @Description: 跳转到admin_academyInfo_list页面，并获取所需变量
	 * @return：
	 * @author 肖萌
	 */
	function oncollegeList(){
		$this->clearSession();
		$data["eid"] = $_GET['eid'];
		$data["ss"] = $_GET['ss'];
		$this->view->assign('data',$data);
		$this->view->display('admin_academyInfo_list');
	}
	
	/**
	 *
	 * @Title: ongetCollegeList
	 * @Description: 获取院系集合
	 * @return：院系集合
	 * @author 肖萌
	 */
	function  ongetCollegeList(){
		$this->clearSession();
		$eid = $_GET['eid'];
		$ss = $_GET['ss'];
		
		if($ss == null || $ss == 1){
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
		$jsonstr = json_encode( $_ENV['college']->get_college_list_grid($wherear,$keyvalue,$pageIndex,$pageSize));
		print_r($jsonstr);
	}
	
	/**
	 *
	 * @Title: oncollegeAdd
	 * @Description: 显示添加页面
	 * @return
	 * @author 肖萌
	 */
	function oncollegeAdd(){
		$yxid = isset($_GET['id']) ? $_GET['id'] : 0;
		$xxid = $_GET['xxid'];
		$this->load('school');
		if($xxid != 0 && $xxid != ""){
			$school = $_ENV['school']->getSchoolById($xxid);
			$this->view->assign('school',$school);
		}
		$this->view->assign('yxid',$yxid);
		$this->view->display('admin_academyInfo_add');
	}
	
	function onupCollegeById(){
		$yxid = $_GET['yxid'];
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$college = json_decode($jsonstr,true);
		$college[0]['yxid'] = $yxid;
		$jlnys = $college[0]['jlny'];
		$jl = explode("-",$jlnys);
		$year = $jl[0];
		$month = $jl[1];
		$jlny = $year.$month;
		$college[0]['jlny'] = $jlny;
		$collegelist = $college[0];
		unset($collegelist['xxmc']);
		$_ENV['college']->upCollegeByArray($collegelist);
	}
	
	function ongetCollegeById(){
		$yxid = isset($_GET['id']) ? $_GET['id'] : 0;
		$academyfirst = $_ENV['college']->getCollegeById($yxid);
		print_r(json_encode($academyfirst));
	}
	
	function ondelCollegeById(){
		$yxid = isset($_POST['yxid']) ? $_POST['yxid'] : 0;
		$_ENV['college']->delCollegeById($yxid);
	}
	
	function oncheckCollegeByYxmc(){
		$schoolid = $_GET['schoolid'];
		$yxmc = $_POST['yxmc'];
		$yxid = $_POST['yxid'];
		$yxmcs = $_ENV['college']->checkCollegeByYxmc($yxmc,$schoolid,$yxid);
		$_SESSION['yxmcs'] = $yxmcs;
		echo $yxmcs;
	}
	
	function oncheckCollegeByYxbm(){
		$schoolid = $_GET['schoolid'];
		$yxbm = $_POST['yxbm'];
		$yxid = $_POST['yxid'];
		$yxbms = $_ENV['college']->checkCollegeByYxbm($yxbm,$schoolid,$yxid);
		$_SESSION['yxbms'] = $yxbms;
		echo $yxbms;
	}
	
	
	function clearSession(){
		$_SESSION['eid'] = null;
		$_SESSION['ss'] = null;
	}
	//------------------------------------------------分割线--------------------------------------
	function onadd($showinfo = ''){
		$data = array();
		$data['academyinfoid'] = isset( $_GET['academyinfoid'])? $_GET['academyinfoid']:'';
		$schoolid =$this->getLoginUserSchoolId();//如果是超级管理员,则加载所有的学校列表
		//得到所有学校的学校信息
		$this->load("school");
		$data['schoolList'] = $_ENV['school']->get_school_by_id($schoolid);
		
		//下面的是修改操作
		if($data['academyinfoid'] ){
			$data['academyinfo'] = $_ENV['college']->get_college_by_id($data['academyinfoid'] );
		}
		$data['showinfo'] = $showinfo;
		$this->view->assign('data',$data);
		$this->view->display('admin_academyInfo_add');
	}
	
	function oncollegeSubmit(){
		$college =isset( $_POST['college'])? $_POST['college']:'';
		$academyinfoid =isset( $_POST['academyinfoid'])? $_POST['academyinfoid']:'';
		
		if(empty($academyinfoid)){
			/**
			 * 进行插入操作
			 */
			if($_ENV['college']->add_college($college)){
				//添加记录成功显示
				$this->onadd('添加院系成功！');
			}else{
				//添加记录失败显示
				$this->onadd('添加院系失败，请检查输入内容！');
			}
		}else{
			/**
			 * 进行更新操作
			 */
			if($_ENV['college']->update_college($college,$academyinfoid)){
				//添加记录成功显示
				$this->onadd('更新院系成功！');
			}else{
				//添加记录失败显示
				$this->onadd('更新院系失败，请检查输入内容！');
			}
		}
	}
	
	/**
	 * 
	* @Title: ondelCollege
	* @Description: 进行院系的删除操作
	* @return 链接重定向走
	* @author Ricker lhyfe@sohu.com
	 */	
	function ondelCollege(){
		$delete = $_POST['delete'];
		if($delete){
			if($_ENV['college']->delete_college($delete)){
				header('Location: admin.php?m=college&a=ls');
			}
		}
	}
	
	/**
	 * 
	* @Title: onsearch
	* @Description: 进行院系的搜索操作，主要可以对，院系名称，院系编码，院系联系人进行搜索
	* @param 返回对应数组
	* @return 
	* @author Ricker lhyfe@sohu.com
	 */
	function onsearch(){
		$search = isset($_POST['search']) ? $_POST['search'] : '';
		$schoolid =$this->getLoginUserSchoolId();//如果是超级管理员,则加载所有的学校列表
		$academyInfoList = $_ENV['college']->get_college_by_search($search);
		$schoolname = $academyInfoList[0]['xxmc'];
		$this->view->assign('schoolname',$schoolname);
		$this->view->assign('academyInfoList',$academyInfoList);
		$this->view->assign('search',$search);
		$this->view->display('admin_academyInfo');
	}

}
?>