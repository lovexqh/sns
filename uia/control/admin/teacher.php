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
			if(!$this->user['isfounder'] && !$this->user['allowadminteacher']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('teacher');
	}

	function onls(){
		$this->view->display('admin_teacher');
	}
	
	function onListTree(){
		$schoolid =$this->getLoginUserSchoolId();//如果是超级管理员,则加载所有的学校列表
		echo $_ENV['teacher']->get_teacher_dept_tree($schoolid);
	}
	
	/*
	 * 显示老师列表信息返回json数组
	 * author ： Ricker
	 */
	
	function onteacherList(){
		$this->clearSession();	//清除公共区数据
		$data["schoolid"] = $_GET['schoolid'];	//获取成功
		$data["depid"] = $_GET['deptId'];		//获取成功
		$data["updepid"] = $_GET['updepid'];
		$data["deptIdNull"] = $_GET['deptIdNull']; //是否取部门为空	
		$_SESSION['schoolid'] = $data["schoolid"];
		$this->view->assign('data', $data);
		$this->view->assign('updepid', $data["updepid"]);
		$this->view->assign('depid', $data["depid"]);
		$this->view->display('admin_teacher_list');
	}
	
	function ongetTeacherList(){
		$this->clearSession();	//清除公共区数据
		
		
		if(!isset($_GET['schoolid'])){
			$schoolid = $this->getLoginUserSchoolId();
			if((int)$schoolid != -1){
				$wherear['schoolid']=$schoolid;
			}
		}else{
			$wherear['schoolid']=$_GET['schoolid'];
		}			
		$wherear['depid']=$_GET['deptId'];
		
		$updepid=$_GET['updepid'];
		$keyvalue = $_POST['key'];
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$isdeptIdNull = $_GET['deptIdNull']; //是否取部门为空
		if ($isdeptIdNull=="yes") {
			$pageSize = 50;
			$otherssql=' depid is null ';
			unset($wherear['depid']);
			$jsonstr = json_encode( $_ENV['teacher']->get_teacher_list_bydepidNull($updepid, $wherear,$keyvalue,$pageIndex,$pageSize));
		}else {
		   $jsonstr = json_encode( $_ENV['teacher']->get_teacher_list_grid($updepid, $wherear,$keyvalue,$pageIndex,$pageSize));
		}
		print_r($jsonstr);
	}	
	
	/**
	 * 
	* @Title: onTeacherAdd
	* @Description:显示老师添加界面
	* @author Ricker lhyfe@sohu.com
	 */
	function onTeacherAdd(){
		$identityid = isset($_GET['identityid']) ? $_GET['identityid'] : 0;
		$_SESSION['identityid'] = $identityid;
		$dept = $_ENV['teacher']->getDeptByTeaid($identityid);
		//若没有获取到$_SESSION['schoolid']则用Get获取     @pansun
		if(empty($_SESSION['schoolid'])){
			$_SESSION['schoolid']=$_GET['schoolid'];
		}
		$schoolid = $_SESSION['schoolid'];
		if(!$identityid){
			$depid = $_GET['depid'];
			$dept = $_ENV['teacher']->getDeptById($depid);
			$schoolid = $dept[schoolid];
			$this->view->assign("depid", $depid);
		}
		$_SESSION['schoolId'] = $dept[schoolid];
		$this->view->assign("depName", $dept[departName]);
		$this->view->assign("schoolid", $schoolid);
		$this->view->assign("identityid", $identityid);
		$this->view->display("admin_teacher_add");
	}
	
	function onselect_tree(){
		$schoolid = $_GET['schoolid'];//学校id
		$this->view->assign('schoolid', $schoolid);
		$this->view->display("admin_dept_selecttree");
	}
	function ongetSelectTree(){
		$schoolid = $_GET['schoolid'];//学校id
		echo $_ENV['teacher']->getSelectTree($schoolid);
	}
	/**
	 * 
	* @Title: ongetTeacher_by_identityid
	* @Description: 根据老师的编号Ajax返回老师的相关信息
	* @param int identityId 老师的编号 
	* @return 老师的基本信息
	* @author Ricker lhyfe@sohu.com
	 */
	function ongetTeacherByIdentityid(){
		$identityid = isset($_GET['identityid']) ? $_GET['identityid'] : 0;
		$teacherfirst =  $_ENV['teacher']->getTeacher_by_identityid($identityid);
		//print_r($teacherfirst);
		print_r(json_encode($teacherfirst));
	}
	/**
	 * 
	* @Title: ongetTeacherXL
	* @Description: 得到最高学历的集合
	* @return 最高学历的集合
	* @author Ricker lhyfe@sohu.com
	 */
	function ongetTeacherXL(){
		$this->load('common');
		$xlList = $_ENV['common']->get_dict_item_by_en('XL');
		
		print_r(json_encode($xlList));
	}
	
	/**
	 * 
	* @Title: upTeacherByIdentityid
	* @Description: 更新老师的基本信息的操作
	* @author Ricker lhyfe@sohu.com
	 */
	function onupTeacherByIdentityid(){
		$identityid = isset($_GET['identityid']) ? $_GET['identityid'] : 0;
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		$identityid = $_SESSION['identityid'];
		$schoolId = $_SESSION['schoolId'];
		$zgh = $rows[0][zgh];
		$sfzjh = $rows[0][sfzjh];
		//根据部门id找到部门名称
		if($rows[0]['depid']){
			$this->load('dept');
			$result=$_ENV['dept']->get_dept_byId($rows[0]['depid']);
			$rows[0]['bmmc']=$result[0]['departName'];
			$rows[0]['bmbm']=$result[0]['deptCode'];
		}
		$num1 = $_ENV['teacher']->checkTeacherByZgh($zgh, $schoolId, $identityid);
		if($num1==1){
			$_ENV['teacher']->upTeacherByArray($rows);
		}
	}
	
	/**
	 * 
	* @Title: ondelTeacher
	* @Description: 删除老师的基本信息
	* @param 
	* @return 
	* @author Ricker lhyfe@sohu.com
	 */
	function ondelTeacher(){
		$identityid = isset($_POST['identityid']) ? $_POST['identityid'] : 0;
		$_ENV['teacher']->delTeacherById($identityid);
	}
	
	/**
	 * 
	* @Title: onaddTeacher
	* @Description: 添加老师的操作
	* @author Ricker lhyfe@sohu.com
	 */
	function onaddTeacher(){
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);	//去掉"\"
		$rows = json_decode($jsonstr,true);
		$identityid = $_SESSION['identityid'];
		$schoolId = $_SESSION['schoolId'];
		$zgh = $rows[0][zgh];
		$sfzjh = $rows[0][sfzjh];
		$num1 = $_ENV['teacher']->checkTeacherByZgh($zgh, $schoolId, $identityid);
		if($num1==1){
			$_ENV['teacher']->addTeacherByArray($rows);
		}
	}
	/**
	 * 
	* @Title: oncheckTeacherBySfzjh
	* @Description: todo
	* @param 
	* @return 
	* @author Ricker lhyfe@sohu.com
	 */
	function oncheckTeacherBySfzjh(){
		$identityid = $_SESSION['identityid'];
		$sfzjh = $_POST['sfzjh'];
		echo $_ENV['teacher']->checkTeacherBySfzjh($sfzjh,$identityid);
	}
	
	function ongetMz(){
		$mz = $_ENV['teacher']->get_MZ();
		print_r(json_encode($mz));
	}
	
	/**
	 * 
	* @Title: oncheckTeacherByZgh
	* @Description: todo
	* @param 
	* @return 
	* @author Ricker lhyfe@sohu.com
	 */
	function oncheckTeacherByZgh(){
		$identityid = $_SESSION['identityid'];
		$zgh = $_POST['zgh'];
		$schoolId = $_SESSION['schoolId'];
		echo  $_ENV['teacher']->checkTeacherByZgh($zgh, $schoolId, $identityid);
	}
	
	/**
	 * 弹出未分配部门的老师列表
	 */
	function onteacherDeptNull(){
		$data['schoolid'] = $this->getLoginUserSchoolId();
		$data['deptIdNull'] = 'yes';
		$this->view->assign('data', $data);
		$this->view->display("admin_teacherDeptNull");
	}
	/*--------------------------------新界面功能函数----华丽的分隔线---------------------------*/

	function onInit(){

		$schoolId=$this->getLoginUserSchoolId();		//获得当前登录用户的学校id

		$condition="";
		if($schoolId!=-1){								//非超级管理员登录,则加上学校id作为筛选条件
			$condition=" WHERE schoolid='$schoolId'";
		}

		$this->clearSession();							//清除公共区数据

		$this->showData($condition);					//显示初始数据
	}

	/*
	 * 点击添加
	*/
	function onAdd(){
		//		$schoolId=$_SESSION['departmentSchoolid'];	//获取当前学校id,该id在点击部门树节点时存入session
		//		$deptId=$_SESSION['departmentId'];			//获取当前部门id,该id在点击部门树节点时存入session

		$schoolId=$this->getCrtSchoolID();;		//获取当前学校id,该id在点击部门树节点时存入session
		$deptId=$this->getCrtDeptID();;			//获取当前部门id,该id在点击部门树节点时存入session


		$this->view->assign('schoolId', $schoolId);
		$this->view->assign('deptId', $deptId);
		$this->view->assign('onAdd', true);
		$this->view->display('admin_teacher_add');
	}

	/*
	 * 添加老师信息 提交
	*/
	function onAddSubmit(){
		$success = $_ENV['teacher']->add_teacher();

		if(!$success){
			$errMsg=$_ENV['teacher']->errMsg;
			$error=array("code"=>0,'msg'=>$errMsg);
			echo json_encode($error);
		}else{
			echo json_encode(array("code"=>1));
		}
	}
	/**
	 * 点击部门树时的老师信息查询
	 */
	function onTreeNav(){

		$deptId=$_GET['deptId'];
		$schoolid=$_GET['schoolid'];//部门所在学校id
		$updepid = $_GET['updepid'];//部门分类父分类


		$_SESSION['departmentId']=$deptId;//将部门id存入session,做为其他查询时的默认条件需要使用
		$_SESSION['departmentName']=$deptName;//将部门名称存入session,做为其他查询时结果的显示使用
		$_SESSION['updepid']=$updepid;//将部门名称存入session,做为其他查询时结果的显示使用
		$_SESSION['departmentSchoolid']=$schoolid;//将部门所在学校id存入session,做为添加老师数据时的基础数据

		$this->onSearch();
	}

	/*
	 * 老师信息查询
	*/
	function onSearch(){
		$condition =$_ENV['teacher']->searchCondition();	//查询符合条件的记录总数
		$this->showData($condition);

	}

	function onNextPage(){
		$condition=$_SESSION['condition'];
		$this->showData($condition);

	}

	private function showData($condition=''){
		$totalnum =$_ENV['teacher']->get_total_num($condition);											//查询符合条件的记录总数
		$teacherList = $_ENV['teacher']->get_teacher_aPage($_GET['page'], UC_PPP, $totalnum,$condition);	//获得一页数据,其中,$_GET['page']参数由分页控制器提供

		$url='admin.php?m=teacher&a=NextPage';
		$multipage = $this->page($totalnum, UC_PPP, $_GET['page'], $url);//生成分页控制器,其中会自动包含$_GET['page']参数

		$deptId = $_SESSION['departmentId']; //从session中获取部门id,做为查询条件
		//ricker addd
		$updepid = $_SESSION['updepid']; //将部门名称存入session,做为其他查询时结果的显示使用
		$schoolid = $_SESSION['departmentSchoolid']; //将部门所在学校id存入session,做为添加老师数据时的基础数据
		//得到学校及部门的标题
		if($deptId){
			$this->load('dept');
			$depinfo = $_ENV['dept']->get_dept_byId($deptId);
		}

		if($schoolid){
			$this->load('school');
			$scoinfo = $_ENV['school']->get_school_by_id($schoolid);
		}

		if($depinfo[0]['UpDeptID'] != 0)
		{
			$this->view->assign('deptName', $depinfo[0]['DepartName']);
		}

		$this->view->assign('schoolid', $_SESSION['departmentSchoolid']);
		$this->view->assign('depid', $_SESSION['departmentId']);
		$this->view->assign('schoolName', $scoinfo['xxmc'].' - ');
		$this->view->assign('multipage', $multipage);
		$this->view->assign('teacherList', $teacherList);
		$_SESSION['condition']=$condition;//将查询条件存入session,点击"下一页"时需要使用
		$this->view->display('admin_teacher');
	}

// 	/*
// 	 * 导入excel操作
// 	*/
// 	function onLoadExcel(){
// 		$schoolid = $_GET['schoolid'];
// 		$deptid = $_GET['depid'];
// 		$this->load('school');
// 		$scoinfo = $_ENV['school']->get_school_by_id($schoolid);
// 		$this->load('dept');
// 		$depinfo = $_ENV['dept']->get_dept_byId($deptid);
// 		$this->view->assign('xxmc', $scoinfo['xxmc']);
// 		$this->view->assign('DepartName', $depinfo[0]['DepartName']);
// 		$this->view->assign('UpDeptID', $depinfo[0]['UpDeptID']);
// 		$this->view->assign('schoolid', $schoolid);
// 		$this->view->assign('deptid', $deptid);
// 		$this->view->display('admin_teacher_load');
// 	}

// 	/*
// 	 * 上传excel到服务器进行操作
// 	* ricker
// 	*/
// 	function onUploadExcel(){
// 		include_once UC_ROOT.'lib/upfile.class.php';
// 		$schoolid = $_GET['schoolid'];
// 		$deptid = $_GET['deptid'];
// 		if (!empty($_POST['submit'])) {
// 			$upload = new UploadFile($_FILES['files'], './tmp');
// 			$uploadre = $upload->upload();
// 			if (count($uploadre) != 0) {
// 				$this->load('school');
// 				$scoinfo = $_ENV['school']->get_school_by_id($schoolid);
// 				$this->load('dept');
// 				$depinfo = $_ENV['dept']->get_dept_byId($deptid);
// 				if(count($scoinfo) > 0 && count($depinfo) > 0){
// 					$this->excelInsert($uploadre[0]['path'],$uploadre[0]['type'],$schoolid,$deptid);
// 				}else{
// 					echo '请选择合法的学校或部分信息！';
// 				}

// 			}else {
// 				echo "上传失败<br>";
// 			}
// 		}
// 	}
	
// 	/*
// 	 * 对于上传的excel文件进行解析及操作
// 	* 包括插入数据库的操作
// 	*/
// 	function excelInsert($filepath,$filetype,$schoolid,$deptid){
// 		header("Content-Type: text/html;charset=utf-8");
// 		if($filepath != ''){
// 			//判断文件在不在
// 			if (!file_exists($filepath)) {
// 				throw new Exception("Could not open " . $pFilename . " for reading! File does not exist.");
// 			}else{
// 				set_include_path(UC_ROOT.'lib/Classes/');
// 				/** PHPExcel_IOFactory */
// 				include 'PHPExcel/IOFactory.php';
// 				$objReader = new PHPExcel_Reader_Excel5();
// 				if($filetype == 'xlsx'){
// 					$objReader = new PHPExcel_Reader_Excel2007();
// 				}
// 				$objPHPExcel = $objReader->load($filepath);

// 				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
// 				$this->load('common');
// 				/*
// 				 * 对应的列名对应的值
// 				* 1 = 必写
// 				* 0 = 可选
// 				*/
// 				$check = array(
// 						'A'=>'0',
// 						'B'=>'0',
// 						'C'=>'0',
// 						'D'=>'0',
// 						'E'=>'0',
// 						'F'=>'0'
// 				);
// 				//检测可写与必写的规则，按上面 走


// 				/*
// 				 * table = 这个数据库的表名
// 				* field = 要检测的列名
// 				* 这样会对比field的值是不是相同，相同的移出结果数组
// 				*/
// 				$dbcheck = array(
// 						'table'=>'teacherinfo',
// 						'field'=>'xm as A,sfzjh as B',
// 						'where'=>"schoolid = '".$schoolid."' and depid = '".$deptid."'",
// 						'excel'=>'A,B'
// 				);
// 				$result = array();
// 				foreach ($sheetData as $key =>$val){
// 					if($key >1){
// 						//进行列的必选和可选的判断
// 						$re = $_ENV['common']->check_array($check,$val);
// 						if($re['result']){
// 							$teresult = $_ENV['common']->get_array($dbcheck);
// 							//dffsprint_r($teresult);
// 							//echo "INSERT INTO uc_dict_item(dataid,itemcode,itemcn,itemen,itempid,sorder,remark) VALUES('".$val['A']."','".$val['B']."','".$val['C']."','".$val['D']."','".$val['E']."','".$val['F']."','".$val['G']."');";
// 							//echo '<br><br>';
// 							//mysql_query("INSERT INTO uc_dict_item(dataid,itemcode,itemcn,itemen,itempid,sorder,remark) VALUES('zxx0000001','".$val['A']."','".$val['B']."','".$val['D']."','".$val['E']."','".$val['F']."','".$val['G']."')",$conn);
// 							foreach ($sheetData as $key=>$val){
// 								foreach ($teresult as $rkey=>$rval){
// 									if(!$_ENV['common']->judgeEqual($rval,$val)){
// 										//unset($data[$key]);
// 										$result['fail'][] = $val;
// 									}else{
// 										$result['success'][] = $val;
// 										//这里做数据库插入操作
										
// 									}
// 								}
// 							}
								
// 						}else{
// 							echo $re['msg'];
// 						}

// 					}
// 				}
// 				unlink($filepath);
// 				print_r($result);
				
// 			}
// 		}
// 	} 

	
	
	function onEdit() {
		$identityid = getgpc('identityid');
		$teacher=$_ENV['teacher']->get_a_teacher($identityid);
		$status = 0;
		$this->view->assign('teacher', $teacher);
		$this->view->assign('status', $status);
		$this->view->display('admin_teacher_add');
	}

	function onEditSubmit(){

		$success = $_ENV['teacher']->update_teacher();

		if(!$success){
			$errMsg=$_ENV['teacher']->errMsg;
			$error=array("code"=>0,'msg'=>$errMsg);
			echo json_encode($error);
		}else{
			echo json_encode(array("code"=>1));
		}

	}

	/**
	 *
	 */
	function onDelete(){
		$success = $_ENV['teacher']->delete_teacher();

		if(!$success){
			$errMsg=$_ENV['teacher']->errMsg;
			$this->message($errMsg,"admin.php?m=teacher&a=Init");
			return;
		}
		$this->onSearch();
	}

	/*
	 * 将当前学校id存入公共交换区
	*/
	private function setCrtSchoolID($schoolid){
		$_SESSION['departmentSchoolid']=$schoolid;

	}
	/*
	 * 从公共交换区取得当前学校id
	*/
	private function getCrtSchoolID(){
		$schoolid=$_SESSION['departmentSchoolid'];
		return $schoolid;
	}

	/*
	 * 将当前部门id存入公共交换区
	*/
	private function setCrtDeptID($deptId){
		$_SESSION['departmentId']=$deptId;
	}
	/*
	 * 获取当前部门id
	*/
	private function getCrtDeptID(){
		$deptId=$_SESSION['departmentId'];
		return $deptId;

	}

	/*
	 * 将当前部门名称存入公共交换区
	*/
	private function setCrtDeptName($deptName){
		$_SESSION['departmentName']=$deptName;//将部门名称存入公共交换区
	}
	/*
	 * 获取当前部门名称
	*/
	private function getCrtDeptName(){
		$deptName=$_SESSION['departmentName'];
		return $deptName;

	}

	function onloadExcel(){
		$schoolid = $_GET['schoolid'];
		$school = $_ENV['teacher']->getSchoolById($schoolid);
		$this->view->assign('xxid', $school['id']);
		$this->view->assign('xxmc', $school['xxmc']);
		$this->view->display('admin_teacher_load');
	}
	
	function onuploadExcel(){
		include_once UC_ROOT.'lib/upfile.class.php';
		$schoolid = $_POST['schoolid'];
		if(!empty($_POST['submit'])){
			$upload = new UploadFile($_FILES['files'], './tmp');
			$uploadre = $upload->upload();
			if(count($uploadre) != 0){
				$this->load('school');
				$schoolinfo = $_ENV['school']->getSchoolById($schoolid);
				if(count($schoolinfo) > 0){
					$result = $this->excelInsert($uploadre[0]['path'],$uploadre[0]['type'],$schoolid);
					if($result==-1){
						echo "数据表不存在！";
					}else{
						if($result['fail']){
							$_SESSION['upfailedlist'] = $result['fail'];
							echo "上传完成！&nbsp;&nbsp;<a href='admin.php?m=teacher&a=showUpfailed'>查看上传失败记录</a>";
						}else{
							echo "上传完成！";
						}
					}
						
				}
				else{
					echo "请选择合法的学校或班级信息！";
				}
			}
			else{
				echo "上传失败！<br>";
			}
		}
	}
	
	function onshowUpfailed(){
		//直接下载文件
		$filename = 'UpfailedLog' . date('YmdHis') .'.csv';
		header("Content-type:text/csv");
		header("Content-Disposition:attachment;filename=".$filename);
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		$upfailedlist = $_SESSION['upfailedlist'];
		$str="部门,姓名,部门代码,部门编码,性别,身份证号,职工号,教职工类别,出生日期,民族,政治面貌,参加工作时间,进入本单位时间,学历,学位,专业技术资格名称,专业技术职务级别,职务名称,享受待遇级别,工人技术职务名称,错误提示". "\n" ;
		foreach ($upfailedlist as $k=>$v){
			//将身份证整数型转换成excel字符串型
			$v['F'] = "'" . $v['F']; 
			$v['G'] = "'" . $v['G'];
			$str.= implode(',',$v) . "\n" ;
		}
		$str= iconv('utf-8','gb2312',$str);//页面编码为utf-8时使用，否则导出的中文为乱码
		echo $str;
		//$this->view->display('admin_teacher_upfailed');
	}
	
	function ongetUpfailedList(){
		$upfailedlist = $_SESSION['upfailedlist'];
		print_r(json_encode($upfailedlist));
	}
	
	function excelInsert($filepath,$filetype,$schoolid){
		header("Content-Type: text/html;charset=utf-8");
		if($filepath != ''){
			//判断文件在不在
			if (!file_exists($filepath)) {
				throw new Exception("Could not open " . $pFilename . " for reading! File does not exist.");
			}else{
				set_include_path(UC_ROOT.'lib/Classes/');
				/** PHPExcel_IOFactory */
				include 'PHPExcel/IOFactory.php';
				$objReader = new PHPExcel_Reader_Excel5();
				if($filetype == 'xlsx'){
					$objReader = new PHPExcel_Reader_Excel2007();
				}
				$objPHPExcel = $objReader->load($filepath);
				$getSheet = $objPHPExcel->getSheetByName("hr");
				if($getSheet){
					$sheetData = $getSheet->toArray(null,true,true,true);
					$this->load('common');
					/*
					 * 对应的列名对应的值
					* 1 = 必写
					* 0 = 可选
					*/
					$check = array(
							'B'=>'1',
							'C'=>'0',
							'D'=>'0',
							'E'=>'0',
							'F'=>'1',
							'G'=>'0',
							'H'=>'0',
							'I'=>'0',
							'J'=>'0',
							'K'=>'0',
							'L'=>'0'
					);
						
					$dbcheck = array(
							'table'=>'teacherinfo',
							'field'=>'sfzjh as F,identityid,xm',
							'where'=>"schoolid = '".$schoolid."'",
							'excel'=>'F'
					);
					//检测是否能对应depid（部门号）
					$deptcheck = array(
							'table'=>'dept',
							'field'=>'deptID as A',
							'where'=>"schoolid = '".$schoolid."'",
							'excel'=>'A'
					);
					$result = array();
					$m = 0;
					foreach ($sheetData as $key =>$value){
						if($key >2 && $value['B'] && $value['F']){
							//$val = array_splice($value, 1);		//去掉key值为A的值
							$val = $value;
							$re = $_ENV['common']->check_array($check,$val);		//为空验证
							if($re['result']){
								$sql = " and sfzjh='".$sheetData[$key]['F']."'";
								$dbcheck['where'] .= $sql;
								$teresult = $_ENV['common']->get_array($dbcheck);
								$dbcheck['where'] = "schoolid = '".$schoolid."'";
								//检测是否能对应depid（部门号）
								$deptsql = " and DepartName='".$sheetData[$key]['A']."'";
								$deptcheck['where'] .= $deptsql;
								$deptresult = $_ENV['common']->get_array($deptcheck);
								$fail = false;
								if (count($deptresult) < 1 ){//根据部门名称找部门id，若找不到，提示错误，但已保存至”未分配“部门
									$val['error']='部门名称填写有误（已保持至未分配）';
									$result['fail'][] = $val;
								}
								//插入，若信息已存在，做更新
								if(count($teresult) > 0 ){
									$val['identityid']=$teresult[0]['identityid'];
									$result['success'][] = $val;
								}else{
									$result['success'][] = $val;
								}
							}else{
								$val['error']='为空验证不通过（姓名和身份证号必填）';
								$result['fail'][] = $val;
								
							}
						}
					}
					unlink($filepath);
					//print_r($result['success']);
					$num = count($result['success']);
					for($i=0;$i<$num;$i++){
						foreach ($result['success'][$i] as $key1=>$value1){
							$sheetHeader = array(
									'A'=>'bmmc',
									'B'=>'xm',
									'C'=>'depid',
									'D'=>'bmbm',
									'E'=>'xb',
									'F'=>'sfzjh',
									'G'=>'zgh',
									'H'=>'jzglb',
									'I'=>'csrq',
									'J'=>'mz',
									'K'=>'zzmm',
									'L'=>'cjgzsj',
									'M'=>'lxrq', //进入本单位时间=〉来校日期
									'N'=>'zgxl',
									'O'=>'zgxw',
									'P'=>'zyjszg',
									'Q'=>'zyjszwjb',
									'R'=>'zw',
									'S'=>'zj',
									'T'=>'grjszwmc'
									
							);
							foreach($sheetHeader as $key2=>$value2){
								if($key1 == $key2){
									$inresult[0][$value2] = $value1;
									$inresult[0]['schoolid'] = $schoolid;
								}
							}
						}
						if($inresult[0]['xb'] == '男'){
							$inresult[0]['xbm'] = 1;
						}else if($inresult[0]['xb'] == '女'){
							$inresult[0]['xbm'] = 2;
						}
						$deptName = $inresult[0]['bmmc'];
						$xxid = $inresult[0]['schoolid'];
						$dept = $_ENV['teacher']->getDeptByDeptName($deptName, $xxid);
						if($dept){
							$depid = $dept['deptID'];
							$inresult[0]['depid'] = $depid;
						}else {
							unset($inresult[0]['depid']); //清空部门id
						}
// 						foreach($inresult[0] as $key=>$value){	//删除key为dept的项
// 							if($key == "bmmc"){
// 								unset($inresult[0][$key]);
// 							}
// 						}
						if(empty($result['success'][$i]['identityid'])){
							$_ENV['teacher']->addTeacherByArray($inresult);
						}else {
							$inresult[0]['identityid']=$result['success'][$i]['identityid'];
							if (!isset($inresult[0]['depid'])) $inresult[0]['depid']='NULL';
							$_ENV['teacher']->upTeacherByUpload($inresult);
						}
					}
				}else{
					$result = -1;
				}
	
			}
		}
		return $result;
	}
	

	/*
	 * 清除公共交换区的内容
	*/
	private function clearSession(){
		$_SESSION['identityid']=null;
		$_SESSION['schoolid']=null;
		$_SESSION['schoolId']=null;
		$_SESSION['departmentId']=null;
		$_SESSION['departmentName']=null;
		$_SESSION['departmentSchoolid']=null;
	}
	
	
}



?>