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
			if(!$this->user['isfounder'] && !$this->user['allowadminteachbuliding']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('teachbuliding');
	}
	function onls(){
		$this->view->display('admin_teachbuliding');
	}

	function onInit(){
		$this->clearSession();							//清除公共区数据

		$schoolId=$this->getLoginUserSchoolId();
		$sql="";
		if($schoolId!=-1){								//非超级管理员登录,则将学校id存入session,做为公共变量
			$_SESSION['LoginUserSchoolId']=$schoolId;
			$sql = "AND xxid=".$schoolId;
		}
		$this->clearSession();

		$this->showData($sql);
	}

	/**
	 * 
	* @Title: onListTree
	* @Description:生成树
	* @author Ricker lhyfe@sohu.com
	 */
	function onListTree(){
		$schoolid = $this->getLoginUserSchoolId();
		$treeNode=$_ENV['teachbuliding']->get_teachbuliding_dept_tree($schoolid);
		echo $treeNode;
	}
	
	/**
	 *
	 * @Title: onclassList
	 * @Description:跳转admin_class_list页面,班级信息页面
	 * @author Ricker lhyfe@sohu.com
	 */
	function onteachbulidingList(){
		$this->clearSession();
		$data["eid"] = $_GET['eid'];
		$data["ss"] = $_GET['ss'];
		$_SESSION['ss'] = $data["ss"];
		$_SESSION['eid'] = $data["eid"];
		$this->view->assign('data',$data);
		$this->view->display('admin_teachbuliding_list');
	}
	
	/**
	 *
	 * @Title: ongetClassList
	 * @Description:在页面上显示class的信息
	 * @author Ricker lhyfe@sohu.com
	 */
	function ongetTeachbulidingList(){
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
		$teachbulidinglist = $_ENV['teachbuliding']->get_teachbuliding_list_grid($wherear,$keyvalue,$pageIndex,$pageSize);
// 		$zyid = $classlist['zyid'];
// 		$this->view->assign('zyid',$zyid);
		$jsonstr = json_encode($teachbulidinglist);
		print_r($jsonstr);
	}
	
	/**
	 *
	 * @Title: onteachbulidingAdd
	 * @Description:显示班级添加界面
	 * @author Ricker lhyfe@sohu.com
	 */
	function onteachbulidingAdd(){
		$schoolid = isset($_GET['schoolid']) ? $_GET['schoolid']:0;
		$teaid = isset($_GET['teaid']) ? $_GET['teaid'] : 0;
		$this->view->assign('schoolid',$id);
		$this->view->assign('teaid',$teaid);
		$xx = $_ENV['teachbuliding']->get_xxmc_by_id($schoolid);
		$this->view->assign('xx',$xx);
		$_SESSION['schoolid'] = $xx[xxid];
		$this->view->display("admin_teachbuliding_add");
	}
	
	function oncheckTeachbulidingByMc(){
		$jxlmc = $_POST['jxlmc'];
		$teaid = $_GET['teaid'];
		$schoolid = $_SESSION['schoolid'];
		$jxlmcs =  $_ENV['teachbuliding']->checkTeachbulidingByMc($jxlmc,$teaid,$schoolid);
		$_SESSION['jxlmcs'] = $jxlmcs;
		echo $jxlmcs;
	}
	
	/**
	 *
	 * @Title: onupTeachbulidingById
	 * @Description: 获取teachbuliding_add页面的post值,并执行添加或更新操作
	 * @author Ricker lhyfe@sohu.com
	 */
	function onupTeachbulidingById(){
		$teaid = isset($_GET['teaid']) ? $_GET['teaid'] : 0;
		$teajson = $_POST['data'];
		$teajson = stripslashes($teajson);
		$tea = json_decode($teajson,true);
		$tea[0]['teaid'] = $teaid;
		unset($tea[0]['xxmc']);
		$tealist = $tea[0];
		$jxlmcs = $_SESSION['jxlmcs'];
		$tealist['jxlmcs'] = $jxlmcs;
		$_ENV['teachbuliding']->upTeachbulidingByArray($tealist);
	}
	
	/**
	 *
	 * @Title: ondelClass
	 * @Description: 班级删除操作
	 * @author Ricker lhyfe@sohu.com
	 */
	function ondelTeachbuliding(){
		$teaid = isset($_POST['teaid']) ? $_POST['teaid'] : 0;
		$_ENV['teachbuliding']->delTeachbulidingById($teaid);
	}
	
	/**
	 *
	 * @Title: ongetTeachbulidingById
	 * @Description: 获取选中的教学楼信息
	 * @return ：classfirst
	 * @author Ricker lhyfe@sohu.com
	 */
	function ongetTeachbulidingById(){
		$teaid = isset($_GET['teaid']) ? $_GET['teaid'] : 0;
		$teafirst = $_ENV['teachbuliding']->getTeachbulidingById($teaid);
		$_SESSION['schoolid'] = $teafirst['xxid'];
		print_r(json_encode($teafirst));
	}
	
	function clearSession(){
		$_SESSION['LoginUserSchoolId']=null;
		$_SESSION['schoolid'] = null;
		$_SESSION['jxlmcs'] = null;
	}

	
}



?>