<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class familymembermodel {

	var $db;
	var $base;
	var $errMsg;

	function __construct(&$base) {
		$this->familymembermodel($base);
	}

	function familymembermodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT.'lib/baseInfoPub.php';

	}
	/**
	 * 添加家庭成员信息
	 */
	function add_familymember(){

		if(!$this->checkDataAdd()){//数据合法性检查
			return false;
		}

		$columns=$this->initCol();
		$familyID=$_POST['familyid'];

		$sql="insert into ".UC_DBTABLEPRE."parentinfo ";
		$sql.=genKeyValSql($columns);



		$this->db->query($sql);
		$status = $this->db->errno() ? -1 : 1;
		return $status;
	}

	/**
	 * 更新家庭成员信息
	 */
	function update_familymember(){
		$identityid = getgpc('identityid');

		if(!$this->checkDataUpdate()){//数据合法性检查
			return false;
		}

		$columns=$this->initCol();

		$sql="UPDATE ".UC_DBTABLEPRE."parentinfo ";
		$sql.=genKeyValSql($columns);
		$sql.="WHERE identityid=$identityid";

		$this->db->query($sql);
		$status = $this->db->errno() ? -1 : 1;
		return $status;
	}

	/**
	 * 批量删除家庭成员信息
	 */
	function delete_familymember() {
		$IDsarr=$_POST["delete"];

		$IDs = $this->base->implode($IDsarr);//将数组转换为","分割的字符串

		$sql="";
		if($IDs) {
			$sql="DELETE FROM ".UC_DBTABLEPRE."parentinfo WHERE identityid IN($IDs)";
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."parentinfo WHERE identityid IN($IDs)");
			return $this->db->affected_rows();
		} else {
			$this->errMsg="删除信息异常!sql=$sql";
			return 0;
		}
	}


	/**
	 * 通过班级号查询家庭成员信息
	 */
	function searchByClass(){

	}

	/**
	 * 查询符合条件的记录条数
	 */
    function get_total_num($sql = '') {

		$data = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."parentinfo $sql");
		return $data;
	}

	/**
	 * 分页查询
	 */
    function get_familymember_aPage($crtPage,$lineApage,$totalnum,$sqladd) {

		$start = $this->base->page_get_start($crtPage, $lineApage, $totalnum);

		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."parentinfo $sqladd ORDER BY identityid DESC LIMIT $start, $lineApage");

		return $data;
	}

	/**
	 * 条件查询家庭成员信息
	 */
	function searchCondition(){
		$searchCol=array(
			"familyid",		//家庭id
			"xm",			//姓名
			"sfzjh"			//身份证号
		);

		$searchSql=genSearchSql($searchCol,true);
		return $searchSql;

	}
	/**
	 * 根据id查询家庭成员信息
	 */
	function get_a_familymember($identityid){
		$familymember = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."parentinfo WHERE identityid=$identityid");
		return $familymember;
	}

	/**
	 * 根据姓名查询家庭成员信息
	 */
	function get_familymember_by_xm($xm) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."parentinfo WHERE xm='$xm'");
		return $arr;
	}
	/**
	 *根据身份证件号查询家庭成员信息
	 */
	function get_familymember_by_sfzjh($sfzjh) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."parentinfo WHERE sfzjh='$sfzjh'");
		return $arr;
	}
	/**
	 *根据家庭id查询家庭成员信息
	 */
	function get_familymember_by_id($familyid) {
		$arr = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."parentinfo WHERE familyid='$familyid'");
		return $arr;
	}

	/*
	 * 添加时的数据检查
	 */
	private function checkDataAdd(){
		$pass=$this->checkNull();

		$xm=$_POST['xm'];
		if($xm){
			if($this->get_familymember_by_xm($xm)){
				$pass=false;
				$this->errMsg.="姓名 '$xm' 已经存在!" ;
			};

		}

		$sfzjh=$_POST['sfzjh'];
		if($sfzjh){
			if($this->get_familymember_by_sfzjh($sfzjh)){
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

		$gh=$_POST['xm'];
		if($gh){
			$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."parentinfo WHERE xm='$xm' AND identityid!=$identityid");
			if($arr){
				$pass=false;
				$this->errMsg.="姓名 '$gh' 已经存在!" ;
			}
		}


		$sfzjh=$_POST['sfzjh'];
		if($sfzjh){
			$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."parentinfo WHERE sfzjh='$sfzjh' AND identityid!=$identityid");
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

//		if(!$_POST['gh']){
//			$pass=false;
//			$this->errMsg.="[工号]$fixs";
//		}

		if(!$_POST['xm']){
			$pass=false;
			$this->errMsg.="[姓名]$fixs";
		}

		return $pass;
	}

	private function initCol(){
		$columns=array(
//				"identityid",			//身份ID
				"xm",                           //姓名
				"ywxm",                         //英文姓名
				"xmpy",                         //姓名拼音
				"xbm",                          //性别码
				"familyid",                     //家庭ID
				"jtgxm",                        //家庭关系码
				"jtcyzym",                      //家庭成员职业码
				"szdw",                         //所在单位
				"zzmm",                         //政治面貌码
				"lxdh",                         //联系电话
				"dzxx",                         //电子信箱
				"sfzjlxm",                      //身份证件类型码
				"sfzjh",                        //身份证件号
				"hyzkm",                        //婚姻状况码
				"gatqwm",                       //港澳台侨外码
				"txdz",                         //通信地址
				"yzbm",                         //邮政编码
				"jtzz",                         //家庭住址
				"xzz"                          //现住址
//				"input_userid",                 //录入人
//				"update_userid",                //修改人
//				"lastupdate",                   //最近更新时间
//				"rec_flag"                     //记录标志

		);

		return $columns;
	}

}

?>