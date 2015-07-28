<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: grade.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class grademodel
{

	var $db;
	var $base;
	var $errMsg;

	function __construct(&$base)
	{
		$this->grademodel($base);
	}

	function grademodel(&$base)
	{
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT.'lib/baseInfoPub.php';
	}
    function get_total_num($sqladd = '')
    {

    	if($sqladd==''){
			$sqladd = " WHERE 1=1 ";
		}
		$sqladd.=" AND g.xxid=s.id";

		$data = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."gradeinfo g , ".UC_DBTABLEPRE."schoolinfo s $sqladd");
		return $data;
	}
	
    function get_list($page, $ppp, $totalnum, $sqladd)
    {
		$start = $this->base->page_get_start($page, $ppp, $totalnum);

		if($sqladd==''){
			$sqladd = " WHERE 1=1 ";
		}

		$sqladd.=" AND g.xxid=s.id";

		$sql="SELECT g.*,s.xxmc  FROM ".UC_DBTABLEPRE."gradeinfo g , ".UC_DBTABLEPRE."schoolinfo s  $sqladd ORDER BY xxid,jd,nj  LIMIT $start, $ppp";
		$data = $this->db->fetch_all($sql);
		return $data;
	}
	function get_grade_by_xxid($xxid)
	{
		$arr=$this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."gradeinfo WHERE xxid='$xxid'");
		return $arr;
	}
	
	function get_grade_all_xxid($xxid)
	{
		$arr=$this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."gradeinfo WHERE xxid='$xxid'");
		return $arr;
	}

	function get_all_grade()
	{
		$arr=$this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."gradeinfo ORDER BY jd,nj ");
		return $arr;
	}


	function get_grade_by_nj($newnj)
	{
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."gradeinfo WHERE nj='$newnj'");
		return $arr;
	}
	function get_grade_by_id($id)
	{
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."gradeinfo WHERE id='$id'");
		return $arr;
	}
	function get_grade_by_njmc($newnjmc)
	{
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."gradeinfo WHERE njmc='$newnjmc'");
		return $arr;
	}

	function check_gradename($njmc)
	{
		$guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		$len = $this->dstrlen($njmc);
		if($len > 20 || $len < 2 || preg_match("/\s+|^c:\\con\\con|[%,\*\"\s\<\>\&]|$guestexp/is", $njmc)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function dstrlen($str)
	{
		if(strtolower(UC_CHARSET) != 'utf-8')
		{
			return strlen($str);
		}
		$count = 0;
		for($i = 0; $i < strlen($str); $i++)
		{
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

	function check_gradenameexists($njmc)
	{
		$data = $this->db->result_first("SELECT njmc FROM ".UC_DBTABLEPRE."gradeinfo WHERE njmc='$njmc'");
		return $data;
	}
	function delete_grade() {
		$IDsarr=$_POST["delete"];

		$IDs = $this->base->implode($IDsarr);//将数组转换为","分割的字符串

		$sql="";
		if($IDs) {
			$sql="DELETE FROM ".UC_DBTABLEPRE."gradeinfo WHERE ID IN($IDs)";
			$this->db->query($sql);
			return $this->db->affected_rows();
		} else {
			$this->errMsg="删除信息异常!sql=$sql";
			return 0;
		}
	}

	function add_grade(){
		if(!$this->checkDataAdd()){//数据合法性检查
			return false;
		}

		$columns=$this->initCol();

		$sql="insert into ".UC_DBTABLEPRE."gradeinfo ";
		$sql.=genKeyValSql($columns);

		$this->db->query($sql);
		$status = $this->db->errno() ? -1 : 1;

		if($status==-1){
			$this->errMsg.="添加数据失败: '$sql' " ;
		}else{
			$this->errMsg.="添加成功!" ;

		}
		return $status;
	}

	function update_grade(){
		$id = getgpc('id');

		if(!$this->checkDataUpdate()){//数据合法性检查
			return false;
		}

		$columns=$this->initCol();

		$sql="UPDATE ".UC_DBTABLEPRE."gradeinfo ";
		$sql.=genKeyValSql($columns);

		$sql.=" WHERE id='$id'";
		$this->errMsg.$sql;

		$this->db->query($sql);
		$status = $this->db->errno() ? -1 : 1;
		if($status==-1){
			$this->errMsg.="更新数据失败: '$sql' " ;
		}else{
			$this->errMsg.="更新成功!" ;

		}

		return $status;
	}

	private function initCol(){
		$columns=array(
			"xxid",	 			//学校id
			"jd",	 			//学段
			"nj",	 			//年级
			"njmc"	 			//年级名称

		);
		return $columns;
	}


	/*
	 * 添加时的数据检查
	 */
	private function checkDataAdd(){
		$pass=$this->checkNull();

		$njmc=$_POST['njmc'];
		if($njmc){
			if($this->gradeExist($njmc)){
				$pass=false;
				$this->errMsg.="年级名称 '$njmc' 已经存在!" ;
			};
		}

		return $pass;
	}

	/*
	 * 判断年级名称是否已经存在
	 */
	private function gradeExist($njmc){
		$xxid=$_POST['xxid'];
		$njmc=$_POST['njmc'];

		$grade = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."gradeinfo WHERE njmc='$njmc' AND xxid='$xxid'");
		if($grade){
			return true;
		}else{
			return false;
		}
	}

	/*
	 * 修改时的数据检查
	 */
	private function checkDataUpdate(){
		$pass=$this->checkNull();
		$id=$_POST['id'];
		$xxid=$_POST['xxid'];
		$njmc=$_POST['njmc'];

		if($njmc){
			$sql="SELECT * FROM ".UC_DBTABLEPRE."gradeinfo WHERE njmc='$njmc' AND xxid='$xxid' AND id!='$id'";
			$this->errMsg.$sql;
			$grade = $this->db->fetch_first($sql);
			if($grade){
				$pass=false;
				$this->errMsg.="年级名称 '$njmc' 已经存在! 请修改为其他名称!" ;
			}
		}
		return $pass;
	}

	private function checkNull(){
		$pass=true;
		$fixs="必须输入!";

		if(!$_POST['njmc']){
			$pass=false;
			$this->errMsg.="[年级名称]$fixs";
		}

		if(!$_POST['jd']){
			$pass=false;
			$this->errMsg.="[学段]$fixs";
		}

		if(!$_POST['nj']){
			$pass=false;
			$this->errMsg.="[年级]$fixs";
		}
		return $pass;
	}

	function searchCondition(){

		$searchCol=array(
			"xxmc",			//学校名称
			"nj",			//年级
			"njmc"			//年级名称
		);

		$searchSql=genSearchSql($searchCol,true);

		$schoolid=$_SESSION['LoginUserSchoolId'];//从session中获取学校id,做为查询条件
		if($schoolid){
			$searchSql.=" And xxid='$schoolid'";
		}
		return $searchSql;

	}
}
?>