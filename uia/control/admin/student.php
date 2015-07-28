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
		$this->load('student');
	}
	
	
	function onls(){		
		$this->view->display('admin_student');
	}
	
	function onListTree(){
		$schoolid =$this->getLoginUserSchoolId();//如果是超级管理员,则加载所有的学校列表
		$tree = $_ENV['student']->get_student_tree($schoolid);
		echo json_encode ( $tree );
	}
	
	function ongetClassSelecttree(){
		$schoolid = $_GET['schoolid'];
		$tree = $_ENV['student']->get_student_tree($schoolid);
		echo json_encode ( $tree );
	}
	
	//显示学生信息返回json数组
	function onstudentList(){
		$this->clearSession();	//清除公共区数据
		$data["eid"] = $_GET['eid'];
		$data["ss"] = $_GET['ss'];
		$this->view->assign('data', $data);
		$this->view->display('admin_student_list');
	}
	
	function ongetStudentList(){
		$this->clearSession();	//清除公共区数据
		$eid = $_GET['eid'];
		$ss = $_GET['ss'];

		if(!$ss){
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
		$jsonstr = json_encode( $_ENV['student']->get_student_list_grid($wherear,$keyvalue,$pageIndex,$pageSize));
		print_r($jsonstr);
	}
	
	function onStudentAdd(){
		$identityid = isset($_GET['identityid']) ? $_GET['identityid'] : 0;
		$_SESSION['identityid'] = $identityid;
		$class = $_ENV['student']->getClassByStuid($identityid);
		$specialty = $_ENV['student']->getSpecialtyByStuid($identityid);
		$academy = $_ENV['student']->getAcademyByStuid($identityid);
		if(!$identityid){
			$classid = $_GET['bjid'];
			$class = $_ENV['student']->getClassById($classid);
			$yxid = $class[yxid];
			$zyid = $class[zyid];
			$academy = $_ENV['student']->getAcademyById($yxid);
			$specialty = $_ENV['student']->getSpecialtyById($zyid);
			$this->view->assign("schoolid", $class[xxid]);
			$this->view->assign("yxid", $yxid);
			$this->view->assign("zyid", $zyid);
			$this->view->assign("classid", $classid);
		}

		$this->view->assign("schoolid", $academy['xxid']);
		$this->view->assign("yxmc", $academy[yxmc]);
		$this->view->assign("zymc", $specialty[zymc]);
		$this->view->assign("bjid", $class['id']);
		$this->view->assign("bm", $class[bm]);
		$this->view->assign("identityid", $identityid);
		$this->view->display("admin_student_add");
	}
	
	function onshowFromDB(){
		$zzmm = $_ENV['student']->get_ZZMM();
		print_r(json_encode($zzmm));
	}
	
	function ongetStudentByIdentityid(){
		$identityid = isset($_GET['identityid']) ? $_GET['identityid'] : 0;
		$studentfirst =  $_ENV['student']->getStudent_by_identityid($identityid);
		print_r(json_encode($studentfirst));
	}
	
	function onselect_tree(){
		$schoolid = $_GET['schoolid'];//学校id
		$this->view->assign('schoolid', $schoolid);
		$this->view->display("admin_class_selecttree");
	}
	
	function onupStudentByIdentityid(){
		$identityid = isset($_GET['identityid']) ? $_GET['identityid'] : 0;
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		if($rows[0]['chgclass']){
			$schoolid = $rows[0]['schoolid'];
			$chgclass = $rows[0]['chgclass'];
			$tree = $_ENV['student']->get_student_tree($schoolid);
			foreach ($tree as $v) {
				if( $chgclass == $v['id'] ){
					$rows[0]['bjid'] = $v['eid'];
				}
			}
			$bjid = $rows[0]['bjid'];
			$class = $_ENV['student']->getClassById($bjid);
			$rows[0]['yxid'] = $class['yxid'];
			$rows[0]['zyid'] = $class['zyid'];
		}
		unset($rows[0]['chgclass']);
		$identityid = $_SESSION['identityid'];
		$xh = $rows[0][xh];
		$sfzjh = $rows[0][sfzjh];
		$num1 = $_ENV['student']->checkStudentByXh($xh, $identityid);
		$num2 = $_ENV['student']->checkStudentBySfzjh($sfzjh, $identityid);
		if($num1==1 && $num2==1){
			$_ENV['student']->upStudentByArray($rows);
		}
	}
	
	function ondelStudent(){
		$identityid = isset($_POST['identityid']) ? $_POST['identityid'] : 0;
		$_ENV['student']->delStudentById($identityid);
	}

	function onaddStudent(){
		$this->clearSession();
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		unset($rows[0]['chgclass']);
		$identityid = $_SESSION['identityid'];
		$xh = $rows[0][xh];
		$sfzjh = $rows[0][sfzjh];
		$num1 = $_ENV['student']->checkStudentByXh($xh, $identityid);
		$num2 = $_ENV['student']->checkStudentBySfzjh($sfzjh, $identityid);
		if($num1==1 && $num2==1){
			$_ENV['student']->addStudentByArray($rows);
		}
	}

	function oncheckStudentByXh(){
		$identityid = $_SESSION['identityid'];
		$xh = $_POST['xh'];
		echo  $_ENV['student']->checkStudentByXh($xh, $identityid);
	}
	
	function oncheckStudentBySfzjh(){
		$identityid = $_SESSION['identityid'];
		$sfzjh = $_POST['sfzjh'];
		echo $_ENV['student']->checkStudentBySfzjh($sfzjh, $identityid);
	}

/*-----------------------------------------------------------------------------------------------------------*/
	
	function onInit(){
		$this->clearSession();							//清除公共区数据

		$schoolId=$this->getLoginUserSchoolId();

		if($schoolId!=-1){								//非超级管理员登录,则将学校id存入session,做为公共变量
			$_SESSION['LoginUserSchoolId']=$schoolId;
		}

		$this->onSearch();
	}

	
	/**
	 * 点击班级树时的学生信息查询
	 */
	function onstuentList(){
		$this->clearSession();							//清除公共区数据
		$schoolid=$_GET['schoolid'];
		if($schoolid){
			$_SESSION['schoolid']=$schoolid;//将学校id存入session,做为其他查询时的默认条件需要使用
		}else{
			$classId=$_GET['classId'];
			$_SESSION['classId']=$classId;//将班级id存入session,做为其他查询时的默认条件需要使用
			$className=$_GET['className'];//班级名称
			$_SESSION['className']=$className;//将班级名称存入session,做为其他查询时结果的显示使用
		}

		$this->onSearch();
	}


	/**
	 * 点击添加 跳转到添加页面
	 */
	function onAdd(){

		$schoolId=$this->getLoginUserSchoolId();
		$classId=$_SESSION['classId'];

		$this->view->assign('schoolId', $schoolId);
		$this->view->assign('classId', $classId);
		$this->view->assign('onAdd', true);
		$this->view->display('admin_student_add');
	}

	/**
	 * 添加学生信息 提交
	 *
	 */
	function onAddSubmit(){

		$success = $_ENV['student']->add_student();
		if(!$success){
			$errMsg=$_ENV['student']->errMsg;
			$error=array("code"=>0,'msg'=>$errMsg);
			echo json_encode($error);
		}else{
			echo json_encode(array("code"=>1));
		}
	}
	/**
	 * 学生信息查询
	 */
	function onSearch(){

		$condition =$_ENV['student']->searchCondition();											//查询符合条件的记录总数
		$this->showData($condition);

	}

	function onNextPage(){
		$condition=$_SESSION['condition'];
		$this->showData($condition);

	}

	private function showData($condition=''){
		$totalnum =$_ENV['student']->get_total_num($condition);											//查询符合条件的记录总数
		$studentList = $_ENV['student']->get_student_aPage($_GET['page'], UC_PPP, $totalnum,$condition);	//获得一页数据,其中,$_GET['page']参数由分页控制器提供

		$url='admin.php?m=student&a=NextPage';
		$multipage = $this->page($totalnum, UC_PPP, $_GET['page'], $url);//生成分页控制器,其中会自动包含$_GET['page']参数

		$className=$_SESSION['className'];//班级名称

		$this->view->assign('className', $className);
		$this->view->assign('multipage', $multipage);
		$this->view->assign('studentList', $studentList);
		$_SESSION['condition']=$condition;//将查询条件存入session,点击"下一页"时需要使用
		$this->view->display('admin_student');
	}



	function onEdit() {
		$identityid = getgpc('identityid');
		$student=$_ENV['student']->get_a_student($identityid);
		$this->load('common');
		$comboList = array('XSLB','XB','MZ','SFZJLX','HYZK','GATQW','ZZMM','JKZK','ZJXY','XX','HKLB','PYCC');
		$comboItem = $_ENV['common']->get_dict_item($comboList);
		$status = 0;
		
		$this->view->assign('comboItem', $comboItem);
		$this->view->assign('student', $student);
		$this->view->assign('status', $status);
		$this->view->display('admin_student_add');
	}

	function onEditSubmit(){

		$success = $_ENV['student']->update_student();
		if(!$success){
			$errMsg=$_ENV['student']->errMsg;
			$error=array("code"=>0,'msg'=>$errMsg);
			echo json_encode($error);
		}else{
			echo json_encode(array("code"=>1));
		}

	}

	function onDelete(){
		$success = $_ENV['student']->delete_student();

		if(!$success){
			$errMsg=$_ENV['student']->errMsg;
			$this->message($errMsg,"admin.php?m=student&a=Init");
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
		$_SESSION['classId']=null;
		$_SESSION['className']=null;
		$_SESSION['identityid']=null;
	}
	
	
}



?>