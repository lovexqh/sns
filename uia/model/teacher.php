<?php


/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit ('Access Denied');

class teachermodel {

	var $db;
	var $base;
	var $errMsg;

	function __construct(& $base) {
		$this->teachermodel($base);
	}

	function teachermodel(& $base) {
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT . 'lib/baseInfoPub.php';

	}
	function getSchoolById($id){
		return $this->db->fetch_first("SELECT * FROM uc_schoolinfo WHERE id='$id'");
	}
	function getDeptByDeptName($deptName, $xxid){
		return $this->db->fetch_first("SELECT * FROM uc_dept WHERE DepartName='$deptName' AND schoolid='$xxid'");
	}
	function getDeptById($id){
		return $this->db->fetch_first("SELECT * FROM uc_dept WHERE deptID=$id");	
	}
	function getDeptByTeaid($id){
		return $this->db->fetch_first("SELECT * FROM uc_dept WHERE deptID=(SELECT depid FROM uc_teacherinfo WHERE identityid=$id)");
	}
	function get_MZ(){
		return $this->db->fetch_all("select * from ".UC_DBTABLEPRE."dict_item where dataid=(select dataid from ".UC_DBTABLEPRE."dict_type where dataen='MZ')");
	}

	
	/*
	 *根据schoolid去得到相应的记录以json形式返回来创建一棵新的树
	*author： Ricker
	*
	*/
	function get_teacher_dept_tree($schoolid){
		if($schoolid != -1){
			$sql = " AND schoolid = '".$schoolid."'";
		}
		$sql = "SELECT DeptID as id,DepartName as text,UpDeptID as pid,schoolid FROM ".UC_DBTABLEPRE."dept WHERE  DeptID > 0 ".$sql." order by UpDeptID ASC,DeptID ASC";
	
		$treeNode = $this->db->fetch_all($sql);
	
		return json_encode($treeNode);
	}
	
	/*
	 *根据wherear数组得到对应的老师记录集以json形式返回来创建一棵新的树
	*author： Ricker
	*
	*/
	function get_teacher_list_grid($updepid, $wherear, $keyvalue="", $page=0, $pagesize=50){
		$this->base->load("common");
		if(!$updepid){
			$wherestr = $_ENV['common']->whereEquale($wherear);
		}else{
			if($updepid==-1 || $updepid==0){
				$wherestr .= " AND schoolid=".$wherear['schoolid'];
			}else{
				$wherestr = $_ENV['common']->whereEquale($wherear);
			}		
		}

		if($keyvalue != ''){
			$wherestr .= " AND (zgh LIKE '%$keyvalue%' OR xm LIKE '%$keyvalue%' OR jsh LIKE '%$keyvalue%' OR sfzjh LIKE '%$keyvalue%' )";
		}		
		$index = $page * $pagesize;		

		$sum = $this->db->fetch_first( "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."teacherinfo WHERE  identityid > 0 ".$wherestr);
		$data['total'] = $sum['num'];
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."teacherinfo WHERE  identityid > 0 ".$wherestr." order by identityId DESC LIMIT $index,$pagesize";
		$data['data'] =  $this->db->fetch_all($sql);
		for($i=0; $i<$data['total']; $i++){
			if($data['data'][$i][xbm]==1){
				$data['data'][$i][xb] = '男';
			}else if($data['data'][$i][xbm]==2){
				$data['data'][$i][xb] = '女';
			}
			$depid = $data['data'][$i][depid];
			if($depid || $depid != ''){
				$depsql = "SELECT * FROM ".UC_DBTABLEPRE."dept WHERE deptID=$depid";
				$dept = $this->db->fetch_first($depsql);
				$data['data'][$i][bmmc] = $dept[departName];			
			}
		}
		return $data;
	}
	
	//获取未分配部门的老师
	function get_teacher_list_bydepidNull($updepid, $wherear, $keyvalue="", $page=0, $pagesize=50){
		$this->base->load("common");
		if(!$updepid){
			$wherestr = $_ENV['common']->whereEquale($wherear);
		}else{
			if($updepid==-1 || $updepid==0){
				$wherestr .= " AND schoolid=".$wherear['schoolid'];
			}else{
				$wherestr = $_ENV['common']->whereEquale($wherear);
			}
		}
	
		if($keyvalue != ''){
			$wherestr .= " AND (zgh LIKE '%$keyvalue%' OR xm LIKE '%$keyvalue%' OR jsh LIKE '%$keyvalue%' OR sfzjh LIKE '%$keyvalue%' )";
		}
		$index = $page * $pagesize;
		$sum = $this->db->fetch_first( "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."teacherinfo WHERE schoolid=1 AND depid is Null OR depid=0 ".$wherestr);
		$data['total'] = $sum['num'];
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."teacherinfo WHERE schoolid=1 AND depid is Null OR depid=0 ".$wherestr." order by identityId DESC LIMIT $index,$pagesize";
		$data['data'] =  $this->db->fetch_all($sql);
		for($i=0; $i<$data['total']; $i++){
			if($data['data'][$i][xbm]==1){
				$data['data'][$i][xb] = '男';
			}else if($data['data'][$i][xbm]==2){
				$data['data'][$i][xb] = '女';
			}
// 			$depid = $data['data'][$i][depid];
// 			if($depid || $depid != ''){
// 				$depsql = "SELECT * FROM ".UC_DBTABLEPRE."dept WHERE deptID=$depid";
// 				$dept = $this->db->fetch_first($depsql);
// 				$data['data'][$i][bmmc] = $dept[departName];
// 			}
		}
		return $data;
	}
	
	/**
	 * 
	* @Title: getTeacher_by_identityid
	* @Description: Model 返回id是$identityid的老师的信息
	* @param @param int $identityid
	* @return 老师的基本信息
	* @author Ricker lhyfe@sohu.com
	 */
	
	function getTeacher_by_identityid($identityid){
		$teacher = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."teacherinfo WHERE  identityid > 0 AND identityID = '$identityid' ");
		if($teacher['csrq'] != '' && !strpos($teacher['csrq'],'-')){
			$teacher['csrq'] = substr($teacher['csrq'],0,4).'-'.substr($teacher['csrq'],4,2).'-'.substr($teacher['csrq'],6,2);
		}
// 		else if($teacher['csrq'] != '' && strpos('-', $teacher['csrq']) > 0){
// 			$teacher['csrq'] = substr($teacher['csrq'],0,4).substr($teacher['csrq'],6,2).substr($teacher['csrq'],8,2);
// 		}
		//print_r();
		return $teacher;
	}
	
	/**
	 * 
	* @Title: upTeacherByArray
	* @Description: 传递一个数组，更新数组里面的基本信息
	* @param @param Array $rows
	* @author Ricker lhyfe@sohu.com
	 */
	function upTeacherByArray($rows){
		$upstr = "UPDATE ".UC_DBTABLEPRE."teacherinfo SET ";
		foreach ($rows[0] as $key=>$value){
			if($key == 'csrq'){
				$upstr .= "$key = '".str_replace('-','',$value)."' ,";
			}else{
				$upstr .= "$key = '$value' ,";
			}
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$upstr .= " WHERE identityid = '".$rows[0]["identityid"]."'";
		//echo $upstr;
		$this->db->query($upstr);
		
	}

	/**
	 *
	 * @Title: upTeacherByUpload
	 * @Description: 数据导入时，传递一个数组，更新数组里面的基本信息
	 * @param @param Array $rows
	 * @author pansun
	 */
	function upTeacherByUpload($rows){
		$upstr = "UPDATE ".UC_DBTABLEPRE."teacherinfo SET ";
		foreach ($rows[0] as $key=>$value){
			if($key == 'csrq'){
				$upstr .= "$key = '".str_replace('-','',$value)."' ,";
			}elseif($value=='NULL'){
				$upstr .= "$key = $value ,";
			}
			else{
				$upstr .= "$key = '$value' ,";
			}
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$upstr .= " WHERE identityid = '".$rows[0]["identityid"]."'";
		//echo $upstr;
		$this->db->query($upstr);
	
	}
	/**
	 * 
	* @Title: delTeacherById
	* @Description: 删除老师的操作程序
	* @param @param 要删除的老师的identityid $identityid
	* @author Ricker lhyfe@sohu.com
	 */
	function delTeacherById($identityid){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."teacherinfo WHERE identityid in ($identityid)";
		//echo $delstr;
		$this->db->query($delstr);
	}
	/**
	 * 
	* @Title: addTeacherByArray
	* @Description: 添加老师的操作
	* @author Ricker lhyfe@sohu.com
	 */
    function addTeacherByArray($rows){
		$upstr = "INSERT ".UC_DBTABLEPRE."teacherinfo SET ";
		foreach ($rows[0] as $key=>$value){
			if($key == 'csrq'){
				$upstr .= "$key = '".str_replace('-','',$value)."' ,";
			}else if($key == 'gzny'){
				$upstr .= "$key = '".str_replace('-','',$value)."' ,";
			}else if($key == 'lxny'){
				$upstr .= "$key = '".str_replace('-','',$value)."' ,";
			}else{
				$upstr .= "$key = '$value' ,";
			}
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$this->db->query($upstr);
	}
	
	/**
	 * 
	* @Title: checkTeacherBySfzjh
	* @Description: 根据身份证号来检测老师是不是存在
	* @param @param  $sfzjh
	* @param @param  $zgh
	* @return 存在返回true 不存在返回false
	* @author Ricker lhyfe@sohu.com
	 */
	function checkTeacherBySfzjh($sfzjh,$identityid){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."teacherinfo WHERE sfzjh = '$sfzjh' AND identityid !='$identityid'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	
	/**
	 *
	 * @Title: checkTeacherByZgh
	 * @Description: 根据职工号来检测老师是不是存在
	 * @param @param  $sfzjh
	 * @param @param  $zgh
	 * @return 存在返回true 不存在返回false
	 * @author Ricker lhyfe@sohu.com
	 */
	function checkTeacherByZgh($zgh, $schoolId, $identityid){
		if($zgh){
			$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."teacherinfo WHERE zgh = '$zgh' AND schoolid='$schoolId' AND identityid !='$identityid'";
			$result = $this->db->fetch_first($chekstr);
			if(count($result)-1 > 0){
				return 0;
			}else{
				return 1;
			}
		}else{
			return 1;
		}
		
	}
	
	/*--------------------------------新界面功能函数----华丽的分隔线---------------------------*/
	
	
	/**
	 * 添加老师信息
	 */
	function add_teacher() {

		if (!$this->checkDataAdd()) { //数据合法性检查
			return false;
		}

		$columns = $this->initCol();

		$sql = "insert into " . UC_DBTABLEPRE . "teacherinfo ";
		$sql .= genKeyValSql($columns);
		$sql .= "," . genInsertCommonSql($this->base->user);

		$this->db->query($sql);
		$status = $this->db->errno() ? -1 : 1;
		return $status;
	}

	/**
	 * 更新老师信息
	 */
	function update_teacher() {
		$identityid = getgpc('identityid');

		if (!$this->checkDataUpdate()) { //数据合法性检查
			return false;
		}

		$columns = $this->initCol();

		$sql = " UPDATE " . UC_DBTABLEPRE . "teacherinfo ";
		$sql .= genKeyValSql($columns);

		$sql .= "," . genUpdateCommonSql($this->base->user);
		$sql .= " WHERE identityid=$identityid";
		$this->db->query($sql);
		$status = $this->db->errno() ? -1 : 1;
		return $status;
	}

	/**
	 * 批量删除老师信息
	 */
	function delete_teacher() {
		$IDsarr = $_POST["delete"];

		$IDs = $this->base->implode($IDsarr); //将数组转换为","分割的字符串

		$sql = "";
		if ($IDs) {
			$sql = "DELETE FROM " . UC_DBTABLEPRE . "teacherinfo WHERE identityid IN($IDs)";
			$this->db->query("DELETE FROM " . UC_DBTABLEPRE . "teacherinfo WHERE identityid IN($IDs)");
			return $this->db->affected_rows();
		} else {
			$this->errMsg = "删除信息异常!sql=$sql";
			return 0;
		}
	}

	/**
	 * 通过班级号查询老师信息
	 */
	function searchByClass() {

	}

	/**
	 * 查询符合条件的记录条数
	 */
	function get_total_num($sql = '') {

		$data = $this->db->result_first("SELECT COUNT(*) FROM " . UC_DBTABLEPRE . "teacherinfo $sql");
		//print_r("SELECT COUNT(*) FROM " . UC_DBTABLEPRE . "teacherinfo $sql");
		return $data;
	}

	/**
	 * 分页查询
	 */
	function get_teacher_aPage($crtPage, $lineApage, $totalnum, $sqladd) {

		$start = $this->base->page_get_start($crtPage, $lineApage, $totalnum);

		$data = $this->db->fetch_all("SELECT * FROM " . UC_DBTABLEPRE . "teacherinfo $sqladd ORDER BY identityid DESC LIMIT $start, $lineApage");

		return $data;
	}

	/**
	 * 条件查询老师信息
	 */
	function searchCondition() {
		$searchCol = array (
				"gh", //工号
		"xm", //姓名
		"sfzjh" //身份证号

		);

		$searchSql = genSearchSql($searchCol, true);

		$deptId = $_SESSION['departmentId']; //从session中获取部门id,做为查询条件
		//ricker addd
		$updepid = $_SESSION['updepid']; //将部门名称存入session,做为其他查询时结果的显示使用
		$schoolid = $_SESSION['departmentSchoolid']; //将部门所在学校id存入session,做为添加老师数据时的基础数据
		if($updepid){
			if ($deptId) {
				$searchSql .= " And depid IN ($deptId)";
			}
		}else{
			$searchSql .= " And schoolid IN ($schoolid)";
		}
		//ricker end

		return $searchSql;

	}
	/**
	 * 根据id查询老师信息
	 */
	function get_a_teacher($identityid) {
		$teacher = $this->db->fetch_first("SELECT * FROM " . UC_DBTABLEPRE . "teacherinfo WHERE identityid=$identityid");
		return $teacher;
	}

	/**
	 * 根据学工号查询老师信息
	 */
	function get_teacher_by_gh($gh) {
		$arr = $this->db->fetch_first("SELECT * FROM " . UC_DBTABLEPRE . "teacherinfo WHERE gh='$gh'");
		return $arr;
	}
	/**
	 *根据身份证件号查询老师信息
	 */
	function get_teacher_by_sfzjh($sfzjh) {
		$arr = $this->db->fetch_first("SELECT * FROM " . UC_DBTABLEPRE . "teacherinfo WHERE sfzjh='$sfzjh'");
		return $arr;
	}

	//根据学校id获取部门列表
	function getSelectTree($schoolid){
		$sql = "SELECT DeptID as id,DepartName as text,UpDeptID as pid,schoolid FROM ".UC_DBTABLEPRE."dept WHERE schoolid='$schoolid' order by UpDeptID ASC,DeptID ASC";
		$treeNode = $this->db->fetch_all($sql);
		return json_encode($treeNode);
	}
	
	/*
	 * 添加时的数据检查
	 */
	private function checkDataAdd() {
		$pass = $this->checkNull();

		$gh = $_POST['gh'];
		if ($gh) {
			if ($this->get_teacher_by_gh($gh)) {
				$pass = false;
				$this->errMsg .= "工号 '$gh' 已经存在!";
			};

		}

		$sfzjh = $_POST['sfzjh'];
		if ($sfzjh) {
			if ($this->get_teacher_by_sfzjh($sfzjh)) {
				$pass = false;
				$this->errMsg .= "身份证号 '$sfzjh' 已经存在!";
			};
		}
		return $pass;
	}

	/*
	 * 修改时的数据检查
	 */
	private function checkDataUpdate() {
		$pass = $this->checkNull();

		$identityid = getgpc('identityid');

		$gh = $_POST['gh'];
		if ($gh) {
			$arr = $this->db->fetch_first("SELECT * FROM " . UC_DBTABLEPRE . "teacherinfo WHERE gh='$gh' AND identityid!=$identityid");
			if ($arr) {
				$pass = false;
				$this->errMsg .= "工号 '$gh' 已经存在!";
			}
		}

		$sfzjh = $_POST['sfzjh'];
		if ($sfzjh) {
			$arr = $this->db->fetch_first("SELECT * FROM " . UC_DBTABLEPRE . "teacherinfo WHERE sfzjh='$sfzjh' AND identityid!=$identityid");
			if ($arr) {
				$pass = false;
				$this->errMsg .= "身份证号 '$sfzjh' 已经存在!";
			}
		}
		return $pass;

	}

	private function checkNull() {
		$pass = true;
		$fixs = "必须输入!";

		if (!$_POST['sfzjh']) {
			$pass = false;
			$this->errMsg .= "[身份证号]$fixs";
		}

		if (!$_POST['xm']) {
			$pass = false;
			$this->errMsg .= "[姓名]$fixs";
		}

		return $pass;
	}

	private function initCol() {
		$columns = array (
				//				"identityid",					//身份ID
		"schoolid", //学校ID
		"depid", //部门ID
		"gh", //工号
		"xm", //姓名
		"ywxm", //英文姓名
		"xmpy", //姓名拼音
		"cym", //曾用名
		"xbm", //性别码
		"csrq", //出生日期
		"csdm", //出生地码
		"jg", //籍贯
		"mzm", //民族码
		"gjdqm", //国籍/地区码
		"sfzjlxm", //身份证件类型码
		"sfzjh", //身份证件号
		"hyzkm", //婚姻状况码
		"gatqwm", //港澳台侨外码
		"zzmmm", //政治面貌码
		"jkzkm", //健康状况码
		"xyzjm", //信仰宗教码
		"xxm", //血型码
		"zp", //照片
		"sfzjyxq", //身份证件有效期
		"jgh", //机构号
		"jtzz", //家庭住址
		"xzz", //现住址
		"hkszd", //户口所在地
		"hkxzm", //户口性质码
		"xlm", //学历码
		"gzny", //参加工作年月
		"lxny", //来校年月
		"cjny", //从教年月
		"bzlbm", //编制类别码
		"dabh", //档案编号
		"dawb", //档案文本
		"txdz", //通信地址
		"lxdh", //联系电话
		"yzbm", //邮政编码
		"dzxx", //电子信箱
		"zydz", //主页地址
		"tc", //特长
		"gwzym", //岗位职业码
		"zyrkxd" //主要任课学段
		//				"input_userid",                 //录入人
		//				"update_userid",                //修改人
		//				"lastupdate",                   //最近更新时间
		//				"rec_flag"                     //记录标志


		);

		return $columns;
	}

}
?>