<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class studentmodel {

	var $db;
	var $base;
	var $errMsg;

	function __construct(&$base) {
		$this->studentmodel($base);
	}

	function studentmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT.'lib/baseInfoPub.php';

	}
	
	function getClassById($id){
		return $this->db->fetch_first("SELECT * FROM uc_classinfo WHERE id=$id");
	}
	function getAcademyById($id){
		return $this->db->fetch_first("SELECT * FROM uc_academyinfo WHERE id=$id");
	}
	function getSpecialtyById($id){
		return $this->db->fetch_first("SELECT * FROM uc_specialtyinfo WHERE id=$id");
	}
	function getClassByStuid($id){
		return $this->db->fetch_first("SELECT * FROM uc_classinfo WHERE id=(SELECT bjid FROM uc_studentinfo WHERE identityid='$id')");
	}
	function getSpecialtyByStuid($id){
		return $this->db->fetch_first("SELECT * FROM uc_specialtyinfo WHERE id=(SELECT zyid FROM uc_studentinfo WHERE identityid='$id')");
	}
	function getAcademyByStuid($id){
		return $this->db->fetch_first("SELECT * FROM uc_academyinfo WHERE id=(SELECT yxid FROM uc_studentinfo WHERE identityid='$id')");
	}
	function getSchoolById($id){
		return $this->db->fetch_first("SELECT * FROM uc_schoolinfo WHERE id=$id");
	}
	
	/*
	 *根据schoolid去得到相应的记录以json形式返回来创建一棵新的树
	*author： Ricker
	*
	*/
	function get_student_tree($schoolid){
		$reList = array ();
		$sql = '';
		if ($schoolid != -1) {
			$sql_a = " AND id = '" . $schoolid . "'";
		}
		$sql1 = "SELECT id,xxmc FROM " . UC_DBTABLEPRE . "schoolinfo WHERE 1=1" . $sql_a;
		$school = $this->db->fetch_all ( $sql1 ); // 所有学校
		$num_s = count ( $school );
		$node = array ();
		$j = 1;
		$m = 0;
		for($i1 = 0; $i1 < $num_s; $i1 ++) {
			$tree1 = array (
				"id" => $j ++,
				"pid" => 0,
				"text" => $school [$i1] ['xxmc'],
				"eid" => $school [$i1] ['id'],
				"ss" => 1 
			);
			array_push ( $node, $tree1 );
			$sql2 = "SELECT id,yxmc FROM " . UC_DBTABLEPRE . "academyinfo WHERE xxid=" . $tree1 ['eid'];
			$yx = $this->db->fetch_all ( $sql2 );
			$num_y = count ( $yx );
			for($i2 = 1; $i2 <= $num_y; $i2 ++) {
				$tree2 = array (
					"id" => $j ++,
					"pid" => $tree1 ['id'],
					"text" => $yx [$i2 - 1] ['yxmc'],
					"eid" => $yx [$i2 - 1] ['id'],
					"ss" => 2 
				);
				array_push ( $node, $tree2 );
				$sql3 = "SELECT id,zymc FROM " . UC_DBTABLEPRE . "specialtyinfo WHERE yxid=" . $tree2 ['eid'];
				$zy = $this->db->fetch_all ( $sql3 );
				$num_z = count ( $zy );
				if ($num_z > 0) {
					for($i3 = 1; $i3 <= $num_z; $i3 ++) {
						$tree3 = array (
							"id" => $j ++,
							"pid" => $tree2 ['id'],
							"text" => $zy [$i3 - 1] ['zymc'],
							"eid" => $zy [$i3 - 1] ['id'],
							"ss" => 3 
						);
						array_push ( $node, $tree3 );
						$sql4 = "SELECT id,bm FROM " . UC_DBTABLEPRE . "classinfo WHERE zyid=" . $tree3 ['eid'];
						$bj = $this->db->fetch_all ( $sql4 );
						$num_b = count ( $bj );
						if($num_b > 0){
							for($i4 = 1; $i4 <= $num_b; $i4 ++){
								$tree4 = array (
										"id" => $j ++,
										"pid" => $tree3 ['id'],
										"text" => $bj [$i4 - 1] ['bm'],
										"eid" => $bj [$i4 - 1] ['id'],
										"ss" => 4
								);
								array_push ( $node, $tree4 );
							}
						}
					}
				}
			}
		}
		//print_r($node);exit;
		return $node;
	}
	
	
	/*
	 *根据wherear数组得到对应的学生记录集以json形式返回来创建一棵新的树
	*/
	function get_student_list_grid($wherear, $keyvalue="", $page=0, $pagesize=50)
	{
		$schoolid = $wherear['schoolid'];
		$eid = $wherear['eid'];
		$ss = $wherear['ss'];
		$sql = "";
		if (!$ss){
			if ($schoolid){
				$sql .= " AND s.schoolid=$schoolid";
			}
		}else if ($ss == 1){
			$sql .= " AND s.schoolid=$eid";
		}else if ($ss == 2){
			$sql .= " AND s.yxid=$eid";
		}else if ($ss == 3){
			$sql .= " AND s.zyid=$eid";
		}else if ($ss == 4){
			$sql .= " AND s.bjid=$eid";
		}
		if ($keyvalue != ''){
			$sql .= " AND (s.xh LIKE '%$keyvalue%' OR s.xm LIKE '%$keyvalue%' OR s.sfzjh LIKE '%$keyvalue%' )";
		}
		$index = $page * $pagesize;
		$sum = $this->db->fetch_first( "SELECT count(*) AS num FROM uc_studentinfo s LEFT JOIN uc_academyinfo a ON s.yxid=a.id LEFT JOIN uc_specialtyinfo z ON s.zyid=z.id LEFT JOIN uc_classinfo c ON s.bjid=c.id WHERE  identityid > 0 ".$sql);
		$data['total'] = $sum['num'];
		$sql = "SELECT s.identityid,s.xh,s.xm,s.xbm,a.yxmc,z.zymc,s.nj,c.bm,s.pyccjd FROM ".UC_DBTABLEPRE."studentinfo s LEFT JOIN ".UC_DBTABLEPRE."academyinfo a ON s.yxid=a.id LEFT JOIN ".UC_DBTABLEPRE."specialtyinfo z ON s.zyid=z.id LEFT JOIN ".UC_DBTABLEPRE."classinfo c ON s.bjid=c.id WHERE  identityid > 0 ".$sql." order by identityId DESC LIMIT $index,$pagesize";
		$data['data'] =  $this->db->fetch_all($sql);
		$num = $data['total'];
		for ($i=0; $i<$num; $i++){
			$xbm = $data['data'][$i]['xbm'];
			if($xbm == 1){
				$data['data'][$i]['xb'] = '男';
			}else if ($xbm == 2){
				$data['data'][$i]['xb'] = '女';				
			}
		}
		return $data;

	}
	
	function getStudent_by_identityid($identityid){
		$student = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."studentinfo WHERE  identityid > 0 AND identityID = '$identityid' ");
		if($student['csrq'] != '' && !strpos($student['csrq'],'-')){
			$student['csrq'] = substr($student['csrq'],0,4).'-'.substr($student['csrq'],4,2).'-'.substr($student['csrq'],6,2);
		}
		return $student;
	}
	
	function addStudentByArray($rows){
		$upstr = "INSERT ".UC_DBTABLEPRE."studentinfo SET ";
		foreach ($rows[0] as $key=>$value){
			if($key == 'csrq'){
				$upstr .= "$key = '".str_replace('-','',$value)."' ,";
			}else{
				$upstr .= "$key = '$value' ,";
			}
		}

		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$this->db->query($upstr);
	}
	
	function upStudentByArray($rows){
		$upstr = "UPDATE ".UC_DBTABLEPRE."studentinfo SET ";
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
	
	function delStudentById($identityid){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."studentinfo WHERE identityid in ($identityid)";
		//echo $delstr;
		$this->db->query($delstr);
	}
	
	function checkStudentByXh($xh, $identityid){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."studentinfo WHERE xh = '$xh' AND identityid !='$identityid'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}

	function checkStudentBySfzjh($sfzjh, $identityid){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."studentinfo WHERE sfzjh = '$sfzjh' AND identityid !='$identityid'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	
	function get_ZZMM(){
		return $this->db->fetch_all("select * from ".UC_DBTABLEPRE."dict_item where dataid=(select dataid from ".UC_DBTABLEPRE."dict_type where dataen='zzmm')");
	}
	
	/**
	 * 添加学生信息
	 */
	function add_student(){

		if(!$this->checkDataAdd()){//数据合法性检查
			return false;
		}

		$columns=$this->initCol();

		$sql="insert into ".UC_DBTABLEPRE."studentinfo ";
		$sql.=genKeyValSql($columns);
		$sql.=",".genInsertCommonSql($this->base->user);

		$this->db->query($sql);
		$status = $this->db->errno() ? -1 : 1;
		return $status;
	}

	/**
	 * 更新学生信息
	 */
	function update_student(){
		$identityid = getgpc('identityid');

		if(!$this->checkDataUpdate()){//数据合法性检查
			return false;
		}

		$columns=$this->initCol();

		$sql="UPDATE ".UC_DBTABLEPRE."studentinfo ";
		$sql.=genKeyValSql($columns);

		$sql.=",".genUpdateCommonSql($this->base->user);//拼接登录用户等信息
		$sql.=" WHERE identityid=$identityid";

		$this->db->query($sql);
		$status = $this->db->errno() ? -1 : 1;
		return $status;
	}

	/**
	 * 批量删除学生信息
	 */
	function delete_student() {
		$IDsarr=$_POST["delete"];

		$IDs = $this->base->implode($IDsarr);//将数组转换为","分割的字符串

		$sql="";
		if($IDs) {
			$sql="DELETE FROM ".UC_DBTABLEPRE."studentinfo WHERE identityid IN($IDs)";
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."studentinfo WHERE identityid IN($IDs)");
			return $this->db->affected_rows();
		} else {
			$this->errMsg="删除信息异常!sql=$sql";
			return 0;
		}
	}


	/**
	 * 通过班级号查询学生信息
	 */
	function searchByClass(){

	}

	/**
	 * 查询符合条件的记录条数
	 */
    function get_total_num($sql = '') {

		$data = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."studentinfo $sql");
		return $data;
	}

	/**
	 * 分页查询
	 */
    function get_student_aPage($crtPage,$lineApage,$totalnum,$sqladd) {

		$start = $this->base->page_get_start($crtPage, $lineApage, $totalnum);

		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."studentinfo $sqladd ORDER BY identityid DESC LIMIT $start, $lineApage");

		return $data;
	}

	/**
	 * 条件查询学生信息
	 */
	function searchCondition(){

		$searchCol=array(
			"xh",			//学号
			"xm",			//姓名
			"sfzjh"			//身份证号
		);

		$searchSql=genSearchSql($searchCol,true);

		$schoolid=$_SESSION['schoolid'];
		if($schoolid){
			$searchSql.=" And schoolid='$schoolid'";
		}else{
			$classId=$_SESSION['classId'];//从session中获取班级id,做为查询条件
			if($classId){
				$searchSql.=" And classid='$classId'";
			}

			$schoolid=$_SESSION['LoginUserSchoolId'];//从session中获取学校id,做为查询条件
			if($schoolid){
				$searchSql.=" And schoolid='$schoolid'";
			}
		}
		return $searchSql;

	}
	/**
	 * 根据id查询学生信息
	 */
	function get_a_student($identityid){
		$student = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."studentinfo WHERE identityid=$identityid");
		return $student;
	}

	/**
	 * 根据学号查询学生信息
	 */
	function get_student_by_xh($xh) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."studentinfo WHERE xh='$xh'");
		return $arr;
	}
	/**
	 *根据身份证件号查询学生信息
	 */
	function get_student_by_sfzjh($sfzjh) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."studentinfo WHERE sfzjh='$sfzjh'");
		return $arr;
	}
	/**
	 * 生成导航树
	 */
	function genTreeNav(){
		$level=$_REQUEST['lv'];

		$treeNode=array();

		if(!$level){//初始化,学校节点
			$treeNode = $this->base->getLoginUserSchool();
			foreach ($treeNode as &$obj){
				$obj['isParent'] = true;
				$obj['icon'] = 'images/department.png';
				$obj['deepL'] ='school';
			}
			return $treeNode;

		}

		if($level=="school"){//点击学校节点,生成级部信息节点
			$schoolId=$_REQUEST['scid'];
			//$gradeInfo = $this->db->fetch_all("SELECT id,xxid,jd,nj,njmc as title FROM ".UC_DBTABLEPRE."dept  WHERE schoolid='$schoolId' ORDER BY jd,nj ");
			$gradeInfo = $this->db->fetch_all("SELECT departId,departname as title,updeptid FROM ".UC_DBTABLEPRE."dept  WHERE schoolid='$schoolId' ORDER BY jd,nj ");
			foreach ($gradeInfo as &$obj){
				$obj['isParent'] = true;
				$obj['icon'] = 'images/department.png';
				$obj['deepL'] ='grade';
			}
			return $gradeInfo;
		}

		if($level=="grade"){//如果点击的是级部节点,则返回级部下的班级信息
			$xd=$_REQUEST['xd'];//学段
			$nji=$_REQUEST['nji'];//年级
			$schoolid=$_REQUEST['gScid'];//学校id

			$class=$this->getClass($xd,$nji,$schoolid);
			return $class;
		}

		if($level=="none"){//如果点击的是"暂无数据"节点,则返回空
			return null;
		}

	}
	
	
	
	/**
	 * 获取某个学段下，某个年级下的所有班级
	 * $xd ：学段 21：小学； 31：初中；	34:高中
	 * $nji：年级 顺序号，1：1年级；2:2年级
	 * $schoolid：学校ID
	 */
	public function getClasses($xd,$nji,$schoolid){
		$condition=$this->genClzCondition($xd,$nji,$schoolid);
		$sql="SELECT * FROM ".UC_DBTABLEPRE."classinfo".$condition." ORDER BY bh";
		$classes = $this->db->fetch_all($sql);
		return $classes;
	}

	private function genClzCondition($xd='',$nji='',$schoolid=''){
		$tmonth=date('n');	//当前月份
		$tyear=date('Y');	//当前年份

		$upGrade=0;
		if($tmonth<9){//当前月份小于9月,则年级还没有升级,各个年级的级别需要年度额外-1,如2013年9月以前,高一为2013-1级,高二为(2013-1)-1,高三为(2013-2)-1
			$upGrade=-1;
		}

		$grade=$tyear+$upGrade-($nji-1);//2012年入学,高一为2012级,高二为2012-(2-1)级,高三为2012-(3-1)级

		$condition=" WHERE xd = '$xd'";
		$condition.=" AND jbny like '$grade%'";
		$condition.=" AND xxid = '$schoolid'";
		return $condition;
	}
	/**
	 * 按学段，年级查询班级，并生成班级树
	 */
	private function getClass($xd,$nji,$schoolid){

		$condition=$this->genClzCondition($xd,$nji,$schoolid);

		$sql="SELECT id,bj as title FROM ".UC_DBTABLEPRE."classinfo".$condition." ORDER BY bh";
		$class = $this->db->fetch_all($sql);

		$treeNode=array();
		if($class==null){//没有查询到数据
			$treeNode['id']="-1";
			$treeNode['title']="暂无数据";
			$treeNode['isParent']=false;
			$treeNode['deepL'] = 'none';
			return $treeNode;
		}

		foreach ($class as &$obj){
			$obj['isParent'] = false;
		}

		return $class;
	}

	/*
	 * 添加时的数据检查
	 */
	private function checkDataAdd(){
		$pass=$this->checkNull();

		$xh=$_POST['xh'];
		if($xh){
			if($this->get_student_by_xh($xh)){
				$pass=false;
				$this->errMsg.="学号 '$xh' 已经存在!" ;
			};

		}

		$sfzjh=$_POST['sfzjh'];
		if($sfzjh){
			if($this->get_student_by_sfzjh($sfzjh)){
				$pass=false;
				$this->errMsg.="身份证号 '$sfzjh' 已经存在!" ;
			};
		}
		return $pass;
	}

	/*
	 * 修改时的数据检查
	 */
	private function checkDataUpdate(){
		$pass=$this->checkNull();

		$identityid = getgpc('identityid');

		$xh=$_POST['xh'];
		if($xh){
			$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."studentinfo WHERE xh='$xh' AND identityid!=$identityid");
			if($arr){
				$pass=false;
				$this->errMsg.="学号 '$xh' 已经存在!" ;
			}
		}


		$sfzjh=$_POST['sfzjh'];
		if($sfzjh){
			$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."studentinfo WHERE sfzjh='$sfzjh' AND identityid!=$identityid");
			if($arr){
				$pass=false;
				$this->errMsg.="身份证号 '$sfzjh' 已经存在!" ;
			}
		}
		return $pass;

	}

	private function checkNull(){
		$pass=true;
		$fixs="必须输入!";

		if(!$_POST['xh']){
			$pass=false;
			$this->errMsg.="[学号]$fixs";
		}

		if(!$_POST['xm']){
			$pass=false;
			$this->errMsg.="[姓名]$fixs";
		}

		return $pass;
	}

	private function initCol(){
		$columns=array(
//			"identityid",	 	//身份ID
			"schoolid",	 		//学校ID
			"classid",	 		//班级ID
			"familyid",	 		//家庭ID
			"xh",	 			//学号
			"xm",	 			//姓名
			"ywxm",	 			//英文姓名
			"xmpy",	 			//姓名拼音
			"cym",	 			//曾用名
			"xbm",	 			//性别码
			"csrq",	 			//出生日期
			"csdm",	 			//出生地码
			"jg",	 			//籍贯
			"mzm",	 			//民族码
			"gjdqm",	 		//国籍/地区码
			"sfzjlxm",	 		//身份证件类型码
			"sfzjh",	 		//身份证件号
			"hyzkm",	 		//婚姻状况码
			"gatqwm",	 		//港澳台侨外码
			"zzmmm",	 		//政治面貌码
			"jkzkm",	 		//健康状况码
			"xyzjm",	 		//信仰宗教码
			"xxm",	 			//血型码
			"zp",	 			//照片
			"sfzjyxq",	 		//身份证件有效期
			"dszybz",	 		//独生子女标志
			"rxny",	 			//入学年月
			"nj",	 			//年级
			"bh",	 			//班号
			"xslbm",	 		//学生类别码
			"xzz",	 			//现住址
			"hkszd",	 		//户口所在地
			"hkxzm",	 		//户口性质码
			"sfldrk",	 		//是否流动人口
			"tc",	 			//特长
			"lxdh",	 			//联系电话
			"txdz",	 			//通信地址
			"yzbm",	 			//邮政编码
			"jstxh",	 		//即时通讯号
			"dzxx",	 			//电子信箱
			"zydz",	 			//主页地址
			"xjh"	 			//学籍号
//			"input_userid",	 	//录入人
//			"update_userid",	//修改人
//			"lastupdate",	 	//最近更新时间
//			"rec_flag"	 		//记录标志
		);

		return $columns;
	}
	
	
	/*--------------------------------------------新界面功能函数在此下---------------------------*/
	

	
}

?>