<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class familymodel {

	var $db;
	var $base;
	var $errMsg;

	function __construct(&$base) {
		$this->familymodel($base);
	}

	function familymodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT.'lib/baseInfoPub.php';

	}
	/**
	 * 添加家庭信息
	 */
	function add_family(){

		if(!$this->checkDataAdd()){//数据合法性检查
			return false;
		}

		$columns=$this->initCol();

		$sql="insert into ".UC_DBTABLEPRE."familyinfo ";
		$sql.=genKeyValSql($columns);

		$this->db->query($sql);
		$status = $this->db->errno() ? -1 : 1;
		return $status;
	}

	/**
	 * 更新家庭信息
	 */
	function update_family(){
		$familyid = getgpc('familyid');

		if(!$this->checkDataUpdate()){//数据合法性检查
			return false;
		}

		$columns=$this->initCol();

		$sql="UPDATE ".UC_DBTABLEPRE."familyinfo ";
		$sql.=genKeyValSql($columns);
		$sql.="WHERE familyid=$familyid";

		$this->db->query($sql);
		$status = $this->db->errno() ? -1 : 1;
		return $status;
	}

	/**
	 * 批量删除家庭信息
	 */
	function delete_family() {
		$IDsarr=$_POST["delete"];

		$IDs = $this->base->implode($IDsarr);//将数组转换为","分割的字符串

		$sql="";
		if($IDs) {
			$sql="DELETE FROM ".UC_DBTABLEPRE."familyinfo WHERE identityid IN($IDs)";
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."familyinfo WHERE identityid IN($IDs)");
			return $this->db->affected_rows();
		} else {
			$this->errMsg="删除信息异常!sql=$sql";
			return 0;
		}
	}


	/**
	 * 通过班级号查询家庭信息
	 */
	function searchByClass(){

	}

	/**
	 * 查询符合条件的记录条数
	 */
    function get_total_num($sql = '') {

		$data = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."familyinfo $sql");
		return $data;
	}

	/**
	 * 分页查询
	 */
    function get_family_aPage($crtPage,$lineApage,$totalnum,$sqladd) {

		$start = $this->base->page_get_start($crtPage, $lineApage, $totalnum);

		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."familyinfo $sqladd ORDER BY identityid DESC LIMIT $start, $lineApage");

		return $data;
	}

	/**
	 * 条件查询家庭信息
	 */
	function searchCondition(){
		$searchCol=array(
			"gh",			//工号
			"xm",			//姓名
			"sfzjh"			//身份证号
		);

		$searchSql=genSearchSql($searchCol,true);
		return $searchSql;

	}
	/**
	 * 根据id查询家庭信息
	 */
	function get_a_family($familyid){
//		$sql="SELECT * FROM ".UC_DBTABLEPRE."familyinfo WHERE familyid='$familyid'";
		$sql="SELECT * FROM ".UC_DBTABLEPRE."familyinfo WHERE familyid=".$familyid;
		$family = $this->db->fetch_first($sql);

		return $family;
	}

	/**
	 * 创建一个家庭信息
	 */
	function creat_a_family($familyid){
		$sql="INSERT INTO ".UC_DBTABLEPRE."familyinfo SET familyid='$familyid'";
		$this->db->query($sql);

		$family=$this->get_a_family($familyid);
		return $family;
	}

	/*
	 * 修改时的数据检查
	 */
	private function checkDataUpdate(){
		$pass=$this->checkNull();

		return $pass;

	}
	private function checkNull(){
		$pass=true;
		$fixs="必须输入!";

		if(!$_POST['jtzz']){
			$pass=false;
			$this->errMsg.="[家庭住址]$fixs";
		}

		return $pass;
	}

	private function initCol(){
		$columns=array(
//				"familyid",			//家庭ID
				"jtzz",                         //家庭住址
				"jtyzbm",                       //家庭邮政编码
				"jtdh",                         //家庭电话
				"jtlxr",                        //家庭联系人
				"jtdzxx",                       //家庭电子信箱
				"jtrk",                         //家庭人口
				"jtzysrly",                     //家庭主要收入来源
				"jtysrje",                      //家庭月收入金额
				"jtnsrje",                      //家庭年收入金额
				"ljzjhcz"	                     //离家最近火车站
//				"input_userid",                 //录入人
//				"update_userid",                //修改人
//				"lastupdate",                   //最近更新时间
//				"rec_flag",                     //记录标志

		);

		return $columns;
	}

}

?>