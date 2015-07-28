<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class specialtymodel {

	var $db;
	var $base;
	var $errMsg;

	function __construct(&$base) {
		$this->specialtymodel($base);
	}

	function specialtymodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT.'lib/baseInfoPub.php';

	}
	
	function getAcademyById($id){
		return $this->db->fetch_first("SELECT * FROM uc_academyinfo WHERE id=$id");
	}
	function getAcademyBySpeid($id){
		return $this->db->fetch_first("SELECT * FROM uc_academyinfo WHERE id=(SELECT yxid FROM uc_specialtyinfo WHERE id=$id)");
	}
	function getSchoolById($id){
		return $this->db->fetch_first("SELECT * FROM uc_schoolinfo WHERE id=$id");
	}
	
	/*
	 *根据wherear数组得到对应的专业记录集以json形式返回来创建一棵新的树
	*/
	function get_specialty_list_grid($wherear, $keyvalue="", $page=0, $pagesize=50)
	{
		$schoolid = $wherear['schoolid'];
		$eid = $wherear['eid'];
		$ss = $wherear['ss'];
		$sql = "";
		if(!$ss){
			if($schoolid){
				$sql .= " AND xxid=$schoolid";
			}
		}else if ($ss == 1){
			$sql .= " AND xxid=$eid";
		}else if($ss == 2){
			$sql .= " AND yxid=$eid";
		}
		if($keyvalue != ''){
			$sql .= " AND (zyh LIKE '%$keyvalue%' OR zymc LIKE '%$keyvalue%' )";
		}
		$index = $page * $pagesize;
		$sum = $this->db->fetch_first( "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."specialtyinfo WHERE  id > 0 ".$sql);
		$data['total'] = $sum['num'];
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."specialtyinfo WHERE  id > 0 ".$sql." order by id DESC LIMIT $index,$pagesize";
		$data['data'] =  $this->db->fetch_all($sql);
		return $data;
	}
	
	function getSpecialty_by_id($id){
		$specialty = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."specialtyinfo WHERE  id > 0 AND id = '$id' ");
		if($specialty['jlny'] != '' && !strpos($specialty['jlny'],'-')){
			$specialty['jlny'] = substr($specialty['jlny'],0,4).'-'.substr($specialty['jlny'],4,2).'-'.substr($specialty['jlny'],6,2);
		}
		return $specialty;
	}
	
	function addSpecialtyByArray($rows){
		$upstr = "INSERT ".UC_DBTABLEPRE."specialtyinfo SET ";
		foreach ($rows[0] as $key=>$value){
			if($key == 'jlny'){
				$upstr .= "$key = '".str_replace('-','',$value)."' ,";
			}else{
				$upstr .= "$key = '$value' ,";
			}
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$this->db->query($upstr);
	}
	
	function upSpecialtyByArray($rows){
		$upstr = "UPDATE ".UC_DBTABLEPRE."specialtyinfo SET ";
		foreach ($rows[0] as $key=>$value){
			$upstr .= "$key = '$value' ,";
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$upstr .= " WHERE id = '".$rows[0]["id"]."'";
		//echo $upstr;
		$this->db->query($upstr);
	
	}
	
	function delSpecialtyById($id){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."specialtyinfo WHERE id in ($id)";
		//echo $delstr;
		$this->db->query($delstr);
	}
	
	function checkSpecialtyByZyh($zyh, $id){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."specialtyinfo WHERE zyh = '$zyh' AND id !='$id'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	function checkSpecialtyByZymc($zymc, $id){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."specialtyinfo WHERE zymc = '$zymc' AND id !='$id'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	
	/*
	 *根据schoolid去得到相应的记录以json形式返回来创建一棵新的树
	*author： Ricker
	*
	*/
	function get_specialty_tree($schoolid)
	{
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
			}
		}
		return json_encode ( $node );	
	}
/*---------------------------------------------------------------------------------------------------------------*/

	//获得院系列表
	function get_ACADEMY(){
		return $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."academyinfo");
	}
	
	/**
	 * 添加专业信息
	 */
	function add_specialty(){
		if(!$this->checkDataAdd()){//数据合法性检查
			return false;
		}

		$columns=$this->initCol();

		$sql="insert into ".UC_DBTABLEPRE."specialtyinfo ";
		$sql.=genKeyValSql($columns);
		$sql.=",".genInsertCommonSql($this->base->user);

		$this->db->query($sql);
		$status = $this->db->errno() ? -1 : 1;
		return $status;
	}

	/**
	 * 更新专业信息
	 */
	function update_specialty($id){
		if(!$this->checkDataUpdate($id)){//数据合法性检查
			return false;
		}

		$columns=$this->initCol();

		$sql="UPDATE ".UC_DBTABLEPRE."specialtyinfo ";
		$sql.=genKeyValSql($columns);
		$sql.=",".genUpdateCommonSql($this->base->user);//拼接登录用户等信息
		$sql.=" WHERE id=$id";
		$this->db->query($sql);
		$status = $this->db->errno() ? -1 : 1;
		return $status;
	}

	/**
	 * 批量删除专业信息
	 */
	function delete_specialty() {
		$IDsarr=$_POST["delete"];

		$IDs = $this->base->implode($IDsarr);//将数组转换为","分割的字符串

		$sql="";
		if($IDs) {
			$sql="DELETE FROM ".UC_DBTABLEPRE."specialtyinfo WHERE id IN($IDs)";
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."specialtyinfo WHERE id IN($IDs)");
			return $this->db->affected_rows();
		} else {
			$this->errMsg="删除信息异常!sql=$sql";
			return 0;
		}
	}

	/**
	 * 查询符合条件的记录条数
	 */
    function get_total_num($sql = '') {

		$data = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."specialtyinfo $sql");
		return $data;
	}

	/**
	 * 分页查询
	 */
    function get_specialty_aPage($crtPage,$lineApage,$totalnum,$sqladd) {

		$start = $this->base->page_get_start($crtPage, $lineApage, $totalnum);

		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."specialtyinfo $sqladd ORDER BY id DESC LIMIT $start, $lineApage");

		return $data;
	}

	/**
	 * 条件查询专业信息
	 */
	function searchCondition(){
		$searchCol=array(
			"zyh",   //专业号
			"zymc",  //专业名称
			"xjzyh", //校级专业号
			"xjzym", //校级专业名
		);

		$searchSql=genSearchSql($searchCol,true);

		$schoolid=$_SESSION['schoolid'];
		if($schoolid){
			$searchSql.=" And xxid='$schoolid'";
		}else{
			$academyId=$_SESSION['academyId'];//从session中获取院系id
			if($academyId){
				$searchSql.=" And yxid='$academyId'";
			}

			$schoolid=$_SESSION['LoginUserSchoolId'];//从session中获取学校id
			if($schoolid){
				$searchSql.=" And xxid='$schoolid'";
			}
		}

		return $searchSql;

	}
	/**
	 * 根据id查询专业信息
	 */
	function get_a_specialty($id){
		$specialty = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."specialtyinfo WHERE id=$id");
		return $specialty;
	}

	/**
	 * 根据专业号查询专业信息
	 */
	function get_specialty_by_zyh($zyh) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."specialtyinfo WHERE zyh='$zyh'");
		return $arr;
	}
	/**
	 *根据专业名称查询专业信息
	 */
	function get_specialty_by_zymc($zymc) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."specialtyinfo WHERE zymc='$zymc'");
		return $arr;
	}
	/**
	 *根据校级专业号查询专业信息
	 */
	function get_specialty_by_xjzyh($xjzyh) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."specialtyinfo WHERE xjzyh LIKE '%$xjzyh%'");
		return $arr;
	}
	/**
	 *根据校级专业名查询专业信息
	 */
	function get_specialty_by_xjzym($xjzym) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."specialtyinfo WHERE xjzym = '%$xjzym%'");
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

		if($level=="school"){//点击学校节点,生成院系节点
			$schoolId=$_REQUEST['scid'];
			$academyInfo = $this->db->fetch_all("SELECT id,xxid,yxmc as title FROM ".UC_DBTABLEPRE."academyinfo  WHERE xxid='$schoolId'");
			foreach ($academyInfo as &$obj){
				$obj['isParent'] = false;
				$obj['icon'] = 'images/department.png';
				$obj['deepL'] ='academy';
			}
			return $academyInfo;
		}
		
		if($level=="none"){//如果点击的是"暂无数据"节点,则返回空
			return null;
		}

	}

	/*
	 * 添加时的数据检查
	 */
	private function checkDataAdd(){
		$pass=$this->checkNull();

		$zyh=$_POST['zyh'];
		if($zyh){
			if($this->get_specialty_by_zyh($zyh)){
				$pass=false;
				$this->errMsg.="专业号 '$zyh' 已经存在!" ;
			};

		}

		$zymc=$_POST['zymc'];
		if($zymc){
			if($this->get_specialty_by_zymc($zymc)){
				$pass=false;
				$this->errMsg.="专业名称 '$zymc' 已经存在!" ;
			};
		}
		return $pass;
	}

	/*
	 * 修改时的数据检查
	 */
	private function checkDataUpdate($id){
		$pass=$this->checkNull();

		$zyh=$_POST['zyh'];
		if($zyh){
			$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."specialtyinfo WHERE zyh='$zyh' AND id!=$id");
			if($arr){
				$pass=false;
				$this->errMsg.="专业号 '$zyh' 已经存在!" ;
			}
		}

		$zymc=$_POST['zymc'];
		if($zymc){
			$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."specialtyinfo WHERE zymc='$zymc' AND id!=$id");
			if($arr){
				$pass=false;
				$this->errMsg.="专业名称 '$zymc' 已经存在!" ;
			}
		}
		return $pass;
	}

	private function checkNull(){
		$pass=true;
		$fixs="必须输入!";

		if(!$_POST['zyh']){
			$pass=false;
			$this->errMsg.="[专业号]$fixs";
		}

		if(!$_POST['zymc']){
			$pass=false;
			$this->errMsg.="[专业名称]$fixs";
		}

		return $pass;
	}

	private function initCol(){
		$columns=array(
			"xxid", 	//学校id
			"yxid", 	//院系id
			"yxbm", 	//院系编码
			"zyh", 		//专业号
			"zymc",		//专业名称
			"zyjc",		//专业简称
			"xjzyh",	//校级专业号
			"xjzym",	//校级专业名
			"zyywmc",	//专业英文名称
			"xz",		//学制
			"xkmlm",	//学科门类码
			"zyccm",	//专业层次码
			"jlny",		//建立年月
			"zyjj",		//专业简介
			"remark",	//注释说明
		);
		return $columns;
	}
}

?>