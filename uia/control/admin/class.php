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
define('UC_LOGIN_SUCCEED', 0);


class control extends adminbase {


	function __construct() {
		$this->control();
	}
	function control() {
		parent::__construct();
		if(getgpc('a') != 'login' && getgpc('a') != 'logout') {
			if(!$this->user['isfounder'] && !$this->user['allowadminclass']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('class');
	}
	function onls(){
		$this->view->display('admin_class');
	}

	/**
	 * 
	* @Title: onListTree
	* @Description:生成树
	* @author Ricker lhyfe@sohu.com
	 */
	function onListTree(){
		$schoolid = $this->getLoginUserSchoolId();
		$treeNode=$_ENV['class']->get_class_dept_tree($schoolid);
		echo $treeNode;
	}
	
	/**
	 *
	 * @Title: onclassList
	 * @Description:跳转admin_class_list页面,班级信息页面
	 * @author Ricker lhyfe@sohu.com
	 */
	function onclassList(){
		$this->clearSession();
		$data["eid"] = $_GET['eid'];
		$data["ss"] = $_GET['ss'];
		$_SESSION['ss'] = $data["ss"];
		$_SESSION['eid'] = $data["eid"];
		$this->view->assign('data',$data);
		$this->view->display('admin_class_list');
	}
	
	/**
	 *
	 * @Title: ongetClassList
	 * @Description:在页面上显示class的信息
	 * @author Ricker lhyfe@sohu.com
	 */
	function ongetClassList(){
		$this->clearSession();
		$eid = $_GET['eid'];
		$ss = $_GET['ss'];
		if(!$ss){
			$schoolid = $this->getLoginUserSchoolId();
			if((int)$schoolid != -1){
				$wherear['schoolid'] = $schoolid;
			}
		}
		$wherear['ss'] = $ss;
		$wherear['eid'] = $eid;
		$keyvalue = $_POST['key'];
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$classlist = $_ENV['class']->get_class_list_grid($wherear,$keyvalue,$pageIndex,$pageSize);
// 		$zyid = $classlist['zyid'];
// 		$this->view->assign('zyid',$zyid);
		$jsonstr = json_encode($classlist);
		print_r($jsonstr);
	}
	
	/**
	 *
	 * @Title: onClassAdd
	 * @Description:显示班级添加界面
	 * @author Ricker lhyfe@sohu.com
	 */
	function onClassAdd(){
		$zyid = isset($_GET['zyid']) ? $_GET['zyid'] : 0;
		$id = isset($_GET['id']) ? $_GET['id']:0;
		$this->view->assign('classid',$id);
		$xyz = $_ENV['class']->get_mc_by_zyid($zyid);
		$this->view->assign('xyz',$xyz);
		$this->view->display("admin_class_add");
	}
	
	function oncheckClassByBm(){
		$bm = $_POST['bm'];
		$classid = isset($_GET['classid']) ? $_GET['classid'] : 0; 
		echo $_ENV['class']->checkClassByBm($bm,$classid);
	}
	
	/**
	 *
	 * @Title: onupClassById
	 * @Description: 获取class_add页面的post值,并执行添加或更新操作
	 * @author Ricker lhyfe@sohu.com
	 */
	function onupClassById(){
		$classid = isset($_GET['id']) ? $_GET['id'] : 0;
		$classjson = $_POST['data'];
		$classjson = stripslashes($classjson);
		$class = json_decode($classjson,ture);
		$class[0]['classid'] = $classid;
		$jbnys = $class[0]['jbny'];
		$jbny = substr($jbnys,0,7);
		$ssnj = substr($jbnys,0,4);
		$class[0]['jbny'] = $jbny;
		$class[0]['ssnj'] = $ssnj;
		$bjlxm = $class[0]['bjlxm'];
		if($bjlxm == "01"){
			$bjlx = "本科";
		}
		else if($bjlxm == "02"){
			$bjlx = "研究生";
		}
		else if($bjlxm == "03"){
			$bjlx = "专科";
		}
		$class[0]['bjlx'] = $bjlx;
		$bzxh = $class[0]['bzxh'];
		$bzid = "";
		if($bzxh){
			$bzid = $_ENV['class']->get_xsid_by_xh($bzxh);
		}
		$class[0]['bzid'] = $bzid;
		unset($class[0]['bzxh']);
		$bzrgh = $class[0]['bzrgh'];
		$bzrid = "";
		if($bzrgh){
			$bzrid = $_ENV['class']->get_lsid_by_gh($bzrgh);
		}
		$class[0]['bzrid'] = $bzrid;
		unset($class[0]['bzrgh']);
		$xxmc = $class[0]['xxmc'];
		unset($class[0]['xxmc']);
		unset($class[0]['yxmc']);
		unset($class[0]['zymc']);
		$classlist = $class[0];
		$bm = $classlist['bm'];
		$bms = $_ENV['class']->checkClassByBm($bm,$classid);
		$classlist['bms'] = $bms;
		$_ENV['class']->upClassByArray($classlist);
	}
	
	/**
	 *
	 * @Title: ondelClass
	 * @Description: 班级删除操作
	 * @author Ricker lhyfe@sohu.com
	 */
	function ondelClass(){
		$classid = isset($_POST['classid']) ? $_POST['classid'] : 0;
		$_ENV['class']->delClassById($classid);
	}
	
	/**
	 *
	 * @Title: ongetClassById
	 * @Description: 获取选中的班级信息
	 * @return ：classfirst
	 * @author Ricker lhyfe@sohu.com
	 */
	function ongetClassById(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$classfirst = $_ENV['class']->getClassByClassid($id);
		print_r(json_encode($classfirst));
	}

	/*
	 * 清除公共交换区的内容
	 */
	private function clearSession(){
		$_SESSION['yxid']=null;
		$_SESSION['condition']=null;
		$_SESSION['schoolid']=null;
		$_SESSION['zyid']=null;
		$_SESSION['schoolName']=null;
		$_SESSION['yxName']=null;
		$_SESSION['zyName'] = null;
	}

}



?>