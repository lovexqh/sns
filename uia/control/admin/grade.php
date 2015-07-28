<?php
session_start();
/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1096 2011-05-13 11:26:36Z svn_project_zhangjie $
*/

!defined('IN_UC') && exit('Access Denied');

define('UC_GRADE_CHECK_GRADENAME_ILLEGAL', -1);
define('UC_GRADE_CHECK_GRADENAME_EXISTS', -2);
define('UC_GRADE_CHECK_XXID_NULL',-3);
define('UC_GRADE_CHECK_GRADE_NULL', -4);
define('UC_LOGIN_SUCCEED', 0);
/**
 * 级部信息管理
 *
 */
class control extends adminbase
{
	function __construct()
	{
		$this->control();
	}
	function control()
	{
		parent::__construct();
		if(getgpc('a') != 'login' && getgpc('a') != 'logout')
		{
			if(!$this->user['isfounder'] && !$this->user['allowadmingrade'])
			{
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('grade');
	}
	/**
	 * 点击级部信息管理主菜单
	 * 显示当前登录用户所在学校的级部列表
	 * 超级管理员显示全部级部列表
	 */
	function onls(){
		$this->clearSession();							//清除公共区数据

		$schoolId=$this->getLoginUserSchoolId();		//获得当前登录用户的学校id
		if($schoolId!=-1){								//非超级管理员登录,则将学校id存入session,做为公共变量
			$_SESSION['LoginUserSchoolId']=$schoolId;
			$_SESSION['LoginUserisManager']=false;
		}else{
			$_SESSION['LoginUserisManager']=true;
		}
		$this->onSearch();
	}
	/**
	 * 点击查询时的处理
	 */
	function onSearch(){
		$condition =$_ENV['grade']->searchCondition();									//根据输入的查询条件,生成查询子句
		$this->showData($condition);													//查询并显示
	}

	/**
	 * 点击下一页标签时
	 */
	function onNextPage(){
		$condition=$_SESSION['condition'];
		$this->showData($condition);
	}

	/*
	 * 根据条件查询数据并显示
	 */
	private function showData($condition=''){
		$totalnum =$_ENV['grade']->get_total_num($condition);											//查询符合条件的记录总数
		$gradelist = $_ENV['grade']->get_list($_GET['page'], UC_PPP, $totalnum, $condition);	//获得一页数据,其中,$_GET['page']参数由分页控制器提供

		$url='admin.php?m=grade&a=NextPage';
		$multipage = $this->page($totalnum, UC_PPP, $_GET['page'], $url);//生成分页控制器,其中会自动包含$_GET['page']参数

		$_SESSION['condition']=$condition;//将查询条件存入session,点击"下一页"时需要使用

		$isManager=$_SESSION['LoginUserisManager'];
		$this->view->assign('isManager',$isManager);

		$this->view->assign('gradelist', $gradelist);
		$this->view->assign('multipage', $multipage);
		$this->view->display('admin_grade');
	}


	/**
	 * 点击添加 跳转到添加页面
	 */
	function onAdd(){

		$xxid=$this->getLoginUserSchoolId();

		$schoolName="";
		if($xxid==-1){
			$schoolName="none";
		}else{
			$schoolName=$this->getSchoolName($xxid);
		}

		$schoolList=$this->getLoginUserSchool();

		$this->view->assign('schoolList', $schoolList);
		$this->view->assign('onAdd', true);
		$this->view->display('admin_grade_add');
	}

	/**
	 * 添加数据提交时的处理
	 */
	function onAddSubmit(){
		$success = $_ENV['grade']->add_grade();
		if(!$success){
			$errMsg=$_ENV['grade']->errMsg;
			$error=array("code"=>0,'msg'=>$errMsg);
			echo json_encode($error);
		}else{
			echo json_encode(array("code"=>1));
		}
	}

	/**
	 * 点击编辑,跳转到编辑页面
	 */
	function onEdit(){
		$id = getgpc('ID');
		$grade=$_ENV['grade']->get_grade_by_id($id);
		$xxid=$grade['xxid'];
		$schoolName=$this->getSchoolName($xxid);

		$this->view->assign('grade', $grade);
		$this->view->assign('schoolName', $schoolName);
		$this->view->display('admin_grade_add');
	}

	/**
	 * 编辑页面 提交时的处理
	 */
	function onEditSubmit(){

		$success = $_ENV['grade']->update_grade();
		if(!$success){
			$errMsg=$_ENV['grade']->errMsg;
			$error=array("code"=>0,'msg'=>$errMsg);
			echo json_encode($error);
		}else{
			echo json_encode(array("code"=>1));
		}
	}

	/**
	 * 删除时的处理
	 * 可批量删除
	 */
	function onDelete(){
		$success = $_ENV['grade']->delete_grade();

		if(!$success){
			$errMsg=$_ENV['grade']->errMsg;
			$this->message($errMsg,"admin.php?m=grade&a=ls");
			return;
		}
		$this->onNextPage();

	}

	/*
	 * 清除公共交换区的内容
	 */
	private function clearSession(){
		$_SESSION['LoginUserSchoolId']=null;
		$_SESSION['condition']=null;
	}
}
?>