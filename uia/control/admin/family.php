<?php
session_start();
/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1096 2011-05-13 11:26:36Z svn_project_zhangjie $
*/

class control extends adminbase {


	function __construct() {
		$this->control();
	}
	function control() {
		parent::__construct();
		if(getgpc('a') != 'login' && getgpc('a') != 'logout') {
			if(!$this->user['isfounder'] && !$this->user['allowadminstudent']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('family');
		$this->load('familymember');
	}

	function onInit(){
		$this->showData();
	}

	/*
	 * 点击添加
	 */
	function onAdd(){
		$this->view->assign('onAdd', true);
		$this->view->display('admin_family_add');
	}

	/*
	 * 添加家庭信息 提交
	 */
	function onAddSubmit(){

		$success = $_ENV['family']->add_family();

		if(!$success){
			$errMsg=$_ENV['family']->errMsg;
			$error=array("code"=>0,'msg'=>$errMsg);
			echo json_encode($error);
		}else{
			echo json_encode(array("code"=>1));
		}
	}
	/*
	 * 家庭信息查询
	 */
	function onSearch(){
		$condition =$_ENV['family']->searchCondition();											//查询符合条件的记录总数
		$this->showData($condition);

	}

	function onNextPage(){
		$condition=$_SESSION['condition'];
		$this->showData($condition);

	}

	private function showData($condition=''){
		$totalnum =$_ENV['family']->get_total_num($condition);											//查询符合条件的记录总数
		$familyList = $_ENV['family']->get_family_aPage($_GET['page'], UC_PPP, $totalnum,$condition);	//获得一页数据,其中,$_GET['page']参数由分页控制器提供

		$url='admin.php?m=family&a=NextPage';
		$multipage = $this->page($totalnum, UC_PPP, $_GET['page'], $url);//生成分页控制器,其中会自动包含$_GET['page']参数

		$this->view->assign('multipage', $multipage);
		$this->view->assign('familyList', $familyList);
		$_SESSION['condition']=$condition;//将查询条件存入session,点击"下一页"时需要使用
		$this->view->display('admin_family');
	}



	function onEdit($familyid) {
		if(!$familyid){
			$familyid = getgpc('identityid');//学生的身份id即为家庭id
		}

		$family=$_ENV['family']->get_a_family($familyid);
		//如果没有查询到家庭信息,则根据学生身份id创建一个家庭信息并返回
		if(!$family){
			$family=$_ENV['family']->creat_a_family($familyid);
		}

		//查询所有的家庭成员
		$familymemberList=$_ENV['familymember']->get_familymember_by_id($familyid);
		$this->view->assign('familymemberList', $familymemberList);


		$this->view->assign('familyid', $familyid);
		$this->view->assign('family', $family);
		$this->view->display('admin_family_edit');

	}

	function onShowFamily(){
		$familyid = getgpc('familyid');
		$family=$_ENV['family']->get_a_family($familyid);
		//查询所有的家庭成员
		$familymemberList=$_ENV['familymember']->get_familymember_by_id($familyid);
		$this->view->assign('familymemberList', $familymemberList);


		$this->view->assign('familyid', $familyid);
		$this->view->assign('family', $family);
		$this->view->display('admin_family_edit');
	}

	function onEditSubmit(){

		$success = $_ENV['family']->update_family();

		if(!$success){
			$errMsg=$_ENV['family']->errMsg;
			$error=array("code"=>0,'msg'=>$errMsg);
			echo json_encode($error);
		}else{
			echo json_encode(array("code"=>1,'msg'=>"信息保存成功!"));
		}

	}

	/**
	 *删除家庭成员
	 */
	function onDelete(){
		$success = $_ENV['familymember']->delete_familymember();

		$familyid = getgpc('familyid');
		if(!$success){
			$errMsg=$_ENV['familymember']->errMsg;
			$this->message($errMsg,"admin.php?m=family&a=Edit&identityid=$familyid");
			return;
		}

		$this->onEdit($familyid);

	}


}



?>