<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class deptmodel {

	var $db;
	var $base;
	var $errMsg;

	function __construct(&$base) {
		$this->deptmodel($base);
	}

	 /*根据wherear数组得到对应的部门记录集以json形式返回来创建一棵新的树
	*author： 潘信伍
	*/
	function get_dept_list_grid($wherear, $keyvalue="")
	{
		$this->base->load("common");
		$wherestr = $_ENV['common']->whereEquale($wherear);
		if($keyvalue != ''){
			$wherestr .= " AND (zgh LIKE '%$keyvalue%' OR xm LIKE '%$keyvalue%' OR jsh LIKE '%$keyvalue%' )";
		}
		$DeptID = $wherear['depid'];
		$SchoolID = $wherear['schoolid'];
		//var_dump($wherear['depid']);
		$deptList=$this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."dept WHERE schoolid='$SchoolID' AND deptID = '$DeptID'");
		//var_dump($data);
		return $deptList;
	}

	function update_department(&$rows){
		$this->base->load('common');
		$DepartID = $rows['deptID'];
		//var_dump($DepartID);
		if(!$this->checkDataUpdate($rows)){//数据合法性检查
			return false;
		}
		if(!empty($DepartID)){
			$sql="UPDATE ".UC_DBTABLEPRE."dept SET ";
			$sql.=$_ENV['common']->arraytoSql($rows);
			$sql.=" WHERE DeptID='$DepartID'";
			$this->db->query($sql);
			$status = $this->db->errno() ? -1 : 1;
			if($status==-1){
				$this->errMsg.="更新数据失败: '$sql' " ;
			}else{
				$this->errMsg.="更新成功!" ;
			
			}
		}else{
			$sql="INSERT INTO ".UC_DBTABLEPRE."dept SET ";
			$sql.=$_ENV['common']->arraytoSql($rows);
			$this->db->query($sql);
			$status = $this->db->errno() ? -1 : 1;
			if($status==-1){
				$this->errMsg.="更新数据失败: '$sql' " ;
			}else{
				$this->errMsg.="插入部门成功!" ;
					
			}
		}
		
		return $status;
	}

/*
	 * 修改时的数据检查
	 */
	private function checkDataUpdate(&$rows){
		$pass=$this->checkNull($rows);
		//var_dump($rows);
		$DepartID = $rows['deptID'];
		$schoolid = $rows['schoolid'];
		$DepartName=$rows['DepartName'];
		$upDepartID=$rows['UpDeptID'];
		if($DepartName){
			$sql="SELECT * FROM ".UC_DBTABLEPRE."dept WHERE UpDeptID='$upDepartID' AND schoolid='$schoolid' AND DepartName='$DepartName' AND DeptID!='$DepartID'";
			$dept = $this->db->fetch_first($sql);
			//var_dump($sql);
			if($dept){
				$pass=false;
				$this->errMsg.="部门名称 '$DepartName' 已经存在! 请修改为其他名称!" ;
			}
		}
		return $pass;

	}
	
	function getSchoolById($schoolid){
		return $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."schoolinfo WHERE id='$schoolid'");
	}
	
	function get_dept_name($DeptID){
		$dept = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."dept WHERE DeptID='$DeptID'");
		return $dept['departName'];
	}
	
	function checkDepartName($deptname, $schoolid){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."dept WHERE departName='$deptname' AND schoolid='$schoolid'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	
	function doAddDept($rows){
		$upstr = "INSERT ".UC_DBTABLEPRE."dept SET ";
		foreach ($rows as $key=>$value){
			$upstr .= "$key = '$value' ,";
		}
		
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$this->db->query($upstr);
	}


	/*--------------------------------新界面功能函数----华丽的分隔线---------------------------*/

	function deptmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT.'lib/baseInfoPub.php';
	}
    function get_total_num($sqladd = '') {
		$data = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."dept d $sqladd");
		return $data;
	}

	function get_total_nums($sqladd) {
		$data = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."members m right JOIN ".UC_DBTABLEPRE."dept d ON d.DeptManager=m.uid");
		return $data;
	}
	function get_dept_list($sqladd='') {
		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."members m right JOIN ".UC_DBTABLEPRE."dept d ON d.DeptManager=m.email  $sqladd order by d.DeptID");
		return $data;
	}

	/**
	 * 获得部门所在的部门树
	 */
	function getSelDepTree($schoolid){
//		$dept = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."dept WHERE DeptID='$DeptID'");
//		//获得部门所在的组id,一般为学校id
//		$DepGroup=$dept['DepGroup'];

		//获得一个学校下的所有部门,即schoolid属性相同的记录
		$deptList=$this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."dept WHERE schoolid='$schoolid'");

		return $deptList;
	}

	

	function deptExist($DepartName,$upDepartID){
		$dept = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."dept WHERE UpDeptID='$upDepartID' AND DepartName='$DepartName'");
		if($dept){
			return true;
		}else{
			return false;
		}
	}

	//查询除本身以外的数据
	function get_dept_list1($DeptID) {
		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."dept where DeptID not in($DeptID) order by DeptID");
		return $data;
	}

	//查询除本身数据
	function get_dept_byId($DeptID) {
		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."dept where DeptID in ($DeptID) order by DeptID");
		return $data;
	}


    function get_list($page, $ppp, $totalnum, $sqladd) {
		$start = $this->base->page_get_start($page, $ppp, $totalnum);
		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."members m right JOIN ".UC_DBTABLEPRE."dept d ON d.DeptManager=m.email  $sqladd order by d.DeptID  LIMIT $start, $ppp");
		return $data;
	}

	function check_deptname($deptname) {
		$guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		$len = $this->dstrlen($deptname);
		if($len > 30 || $len < 2 || preg_match("/\s+|^c:\\con\\con|[%,\*\"\s\<\>\&]|$guestexp/is", $deptname)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function dstrlen($str) {
		if(strtolower(UC_CHARSET) != 'utf-8') {
			return strlen($str);
		}
		$count = 0;
		for($i = 0; $i < strlen($str); $i++){
			$value = ord($str[$i]);
			if($value > 127) {
				$count++;
				if($value >= 192 && $value <= 223) $i++;
				elseif($value >= 224 && $value <= 239) $i = $i + 2;
				elseif($value >= 240 && $value <= 247) $i = $i + 3;
		    	}
	    		$count++;
		}
		return $count;
	}

	function check_deptnameexists($deptname,$pid=0) {
		$data = $this->db->result_first("SELECT DepartName FROM ".UC_DBTABLEPRE."dept WHERE DepartName='$deptname' AND UpDeptID=$pid");
		return $data;
	}

	function add_dept($data) {
		$valsql = "";
		foreach ($data as $k=>$v){
			if(empty($valsql))
				$sqlsplit = "";
			else
				$sqlsplit = ",";
			$valsql .= $sqlsplit."$k='$v'";
		}
		$result=$this->db->query("INSERT INTO ".UC_DBTABLEPRE."dept SET  $valsql");
		//$this->db->query("UPDATE ".UC_DBTABLEPRE."dept SET UnderFlag=1  WHERE DeptID='$UpDeptID'");
		return $result;
	}

	function get_dept_by_username($DepartName) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."dept WHERE DepartName='$DepartName'");
		return $arr;
	}
	function delete_dept($DeptIDsarr) {
		$DeptIDsarr = (array)$DeptIDsarr;
		if(!$DeptIDsarr) {
			return 0;
		}
		$DeptIDs = $this->base->implode($DeptIDsarr);

		if($DeptIDs) {
		    $this->db->query("DELETE FROM ".UC_DBTABLEPRE."dept WHERE DeptID IN($DeptIDs)");
			$this->base->load('note');
			$_ENV['note']->add('deletedept', "DeptID=$DeptIDs");
			return $this->db->affected_rows();
		} else {
			return 0;
		}
	}

	/***
	 * 添加一条部门类型数据
	 */
	function add_dept_type($typename,$typeorder) {
		$date=$this->db->query("INSERT INTO ".UC_DBTABLEPRE."dept_type SET  title='$typename',`order`=$typeorder");
		return $date;
	}
	/***
	 * 更新一条部门类型数据
	 */
	function update_dept_type($id,$typename,$typeorder) {
		$date=$this->db->query("update ".UC_DBTABLEPRE."dept_type SET  title='$typename',`order`=$typeorder where id=".$id);
		return $date;
	}
	/***
	 * 删除一个或多个部门类型
	 */
	function delete_dept_type($typeidsarr) {
		$typeidsarr = (array)$typeidsarr;
		if(!$typeidsarr) {
			return 0;
		}
		$ids = $this->base->implode($typeidsarr);

		if($ids) {
		    $this->db->query("DELETE FROM ".UC_DBTABLEPRE."dept_type WHERE id IN($ids)");
			$this->base->load('note');
			$_ENV['note']->add('delete_dept_type', "id in $ids");
			return $this->db->affected_rows();
		} else {
			return 0;
		}
	}

	function add_department(){

		if(!$this->checkDataAdd()){//数据合法性检查
			return false;
		}

		$columns=$this->initCol();

		$sql="insert into ".UC_DBTABLEPRE."dept ";
		$sql.=genKeyValSql($columns);
		$this->db->query($sql);
		$status = $this->db->errno() ? -1 : $this->db->insert_id();

		if($status==-1){
			$this->errMsg.="添加数据失败: '$sql' " ;
		}else{
			$this->errMsg.="添加成功!" ;

		}
		return $status;
	}



	private function initCol(){
		$columns=array(
			"DepartName",	 	//部门名称
			"UpDeptID",	 		//上级部门ID
			"DeptComment",	 	//部门简介
			"DeptManager",	 	//部门联系人
			"DeptPhone",	 	//部门联系电话
			"schoolid",	 		//所属学校id.
			"deptCode"          //部门编码
		);
		return $columns;
	}


	/*
	 * 添加时的数据检查
	 */
	private function checkDataAdd(){
		$pass=$this->checkNull();

		$DepartName=$_POST['DepartName'];
		$upDepartID=$_POST['UpDeptID'];
		if($DepartName){
			if($this->deptExist($DepartName,$upDepartID)){
				$pass=false;
				$this->errMsg.="部门名称 '$DepartName' 已经存在!" ;
			};

		}

		return $pass;
	}



	private function checkNull(&$rows){
		$pass=true;
		$fixs="必须输入!";

		if(!$rows['DepartName']){
			$pass=false;
			$this->errMsg.="[部门名称]$fixs";
		}
		return $pass;
	}

	/**
	 * 一次性加载部门树
	 */
	public function genDepTreeAll(){

		$schoolId=$this->base->getLoginUserSchoolId();		//获得当前登录用户的学校id

		$condition="";
		if($schoolId!=-1){								//非超级管理员登录,则加上学校id作为筛选条件
			$condition=" WHERE schoolid='$schoolId'";
		}
		$sql = "SELECT deptID,DepartName as title,UpDeptID,schoolid FROM ".UC_DBTABLEPRE."dept".$condition ." order by `sortOrder` ASC";
		$treeNode = $this->db->fetch_all($sql);

		return $treeNode;
	}

	/**
	 * 分级返回部门树
	 */
	private function genDepTree(){
		$level=$_REQUEST['lv'];
		$treeNode=array();
		if(!$level){//初始化,加载根节点.根节点根据当前登录用户信息从学校信息表中加载学校列表,
			$treeNode =$this->base->getLoginUserSchool();//如果是超级管理员,则加载所有的学校列表

			foreach ($treeNode as &$obj){
				$obj['isParent'] = true;
				$obj['icon'] = 'images/department.png';
				$obj['deepL'] ='school';
			}
			return $treeNode;
		}

		if($level=="school"){//点击学校节点,查询学校下的部门信息
			$schoolid=$_REQUEST['scid'];
			$sql = "SELECT DeptID,DepartName as title,UpDeptID,schoolid FROM ".UC_DBTABLEPRE."dept WHERE schoolid = $schoolid AND UpDeptID='0' order by `order` ASC";
			$treeNode = $this->db->fetch_all($sql);
			foreach ($treeNode as &$obj){
				$obj['isParent'] = true;
				$obj['icon'] = 'images/department.png';
				$obj['deepL'] ='dep';
			}
			return $treeNode;
		}

		if($level=="dep"){//点击部门节点,查询部门下的部门信息
			$depid=$_REQUEST['depid'];
			$sql = "SELECT DeptID,DepartName as title,UpDeptID,schoolid FROM ".UC_DBTABLEPRE."dept WHERE UpDeptID = $depid order by `order` ASC";
			$treeNode = $this->db->fetch_all($sql);
			foreach ($treeNode as &$obj){
				$obj['isParent'] = true;
				$obj['icon'] = 'images/department.png';
				$obj['deepL'] ='dep';
			}
			return $treeNode;
		}

	}

	/**
	 * 分页查询
	 */
	public function getDepApage($crtPage,$lineApage,$totalnum,$condition=''){

		$start = $this->base->page_get_start($crtPage, $lineApage, $totalnum);

		if($condition==''){
			$condition = " WHERE 1=1 ";
		}

		$condition.=" AND d.schoolid=s.id";

		$sql = "SELECT d.*,s.xxmc as schoolName FROM ".UC_DBTABLEPRE."dept d ,".UC_DBTABLEPRE."schoolinfo s".$condition." LIMIT $start, $lineApage";
		$DepList = $this->db->fetch_all($sql);
		return $DepList;

	}


}

?>