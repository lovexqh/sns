<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class collegemodel {

	var $db;
	var $base;
	var $errMsg;

	function __construct(&$base) {
		$this->collegemodel($base);
	}

	function collegemodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT.'lib/baseInfoPub.php';
	}
	
	function getSchoolById($id){
		return $this->db->fetch_first("SELECT * FROM uc_schoolinfo WHERE id=$id");
	}
	
	function get_college_tree($schoolid){
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
		$tree1 = array(
				"id"=>$j++,
				"pid"=>0,
				"text" => "学校",
				"eid"=>0,
				"ss"=>1
				);
		array_push($node,$tree1);
		for($i1 = 0; $i1 < $num_s; $i1 ++) {
			$tree2 = array (
					"id" => $j ++,
					"pid" => 1,
					"text" => $school [$i1] ['xxmc'],
					"eid" => $school [$i1] ['id'],
					"ss" => 2
			);
			array_push ( $node, $tree2 );
		}
		return json_encode ( $node );
	}
	
	function get_college_list_grid($wherear, $keyvalue="", $page=0, $pagesize=50){
		$schoolid = $wherear['schoolid'];
		$eid = $wherear['eid'];
		$ss = $wherear['ss'];
		$sql = "";
		if($ss == "" || $ss == 1){
			if($schoolid){
				$sql .= " AND xxid=$schoolid";
			}
		}
		else if($ss == 2){
			$sql .= " AND xxid=$eid";
		}
		if($keyvalue != ''){
			$sql .= " AND yxmc LIKE '%$keyvalue%'";
		}
		$index = $page * $pagesize;
		$sql2 = "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."academyinfo WHERE id > 0 ".$sql;
		$sum = $this->db->fetch_first($sql2);
		$data['total'] = $sum['num'];
		$sql1 = "SELECT * FROM ".UC_DBTABLEPRE."academyinfo WHERE  id > 0 ".$sql." order by id DESC LIMIT $index,$pagesize";
		$data['data'] =  $this->db->fetch_all($sql1);
		return $data;
	}
	
	function getCollegeById($yxid){
		$sql = "SELECT a.*,b.xxmc FROM ".UC_DBTABLEPRE."academyinfo a,".UC_DBTABLEPRE."schoolinfo b WHERE a.id>0 AND a.xxid=b.id AND a.id=$yxid";
		$arr = $this->db->fetch_first($sql);
		return $arr;
	}
	
	function upCollegeByArray($collegelist){
		$yxid = $collegelist['yxid'];
		unset($collegelist['yxid']);
		$yxmcs = $_SESSION['yxmcs'];
		$yxbms = $_SESSION['yxbms'];
		if($yxid == 0 || $yxid == ""){
			$instr = "INSERT INTO ".UC_DBTABLEPRE."academyinfo (";
			foreach ($collegelist as $key=>$value){
				if($key == "remark"){
					$keysql .= "$key";
					$valuesql .= "'$value'";
				}
				else{
					$keysql .= "$key,";
					$valuesql .= "'$value',";
				}
				
			}
			$instr .= "$keysql".") VALUES (".$valuesql.")";
			if($yxbms == 1 && $yxmcs == 1){
				$this->db->query($instr);
			}
		}
		else if($yxbms == 1 && $yxmcs == 1){
			$upstr = "UPDATE ".UC_DBTABLEPRE."academyinfo SET ";
			foreach ($collegelist as $key=>$value){
				if($key == "remark"){
					$sql .= "$key='$value'";
				}
				else{
					$sql .= "$key='$value',";
				}
			}
			$upstr .= $sql." WHERE id=$yxid";
			$this->db->query($upstr);
		}
	}
	
	function delCollegeById($yxid){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."academyinfo WHERE id IN ($yxid)";
		$this->db->query($delstr);
	}
	
	function checkCollegeByYxmc($yxmc,$schoolid,$yxid){
		if($schoolid){
			$chekstr = "SELECT COUNT(*) FROM ".UC_DBTABLEPRE."academyinfo WHERE yxmc='$yxmc' AND xxid=$schoolid AND id NOT IN($yxid)";
		}
		else{
			$sql = "SELECT xxid FROM ".UC_DBTABLEPRE."academyinfo WHERE id=$yxid";
			$sid = $this->db->result_first($sql);
			$chekstr = "SELECT COUNT(*) FROM ".UC_DBTABLEPRE."academyinfo WHERE yxmc='$yxmc' AND xxid=$sid AND id NOT IN($yxid)";
		}
		$result = $this->db->result_first($chekstr);
		if($result == 0){
			return 1;
		}
		else{
			return 0;
		}
	}
	
	function checkCollegeByYxbm($yxbm,$schoolid,$yxid){
		if($schoolid){
			$chekstr = "SELECT COUNT(*) FROM ".UC_DBTABLEPRE."academyinfo WHERE yxbm='$yxbm' AND xxid=$schoolid AND id NOT IN($yxid)";
		}
		else{
			$sql = "SELECT xxid FROM ".UC_DBTABLEPRE."academyinfo WHERE id=$yxid";
			$sid = $this->db->result_first($sql);
			$chekstr = "SELECT COUNT(*) FROM ".UC_DBTABLEPRE."academyinfo WHERE yxbm='$yxbm' AND xxid=$sid AND id NOT IN($yxid)";
		}
		$result = $this->db->result_first($chekstr);
		if($result == 0){
			return 1;
		}
		else{
			return 0;
		}
	}
	
	
	//----------------------------------------------分割线------------------------------------------
	function get_college_by_schoolid($schoolid){
		if((int)$schoolid != -1){
			$sql = " AND a.xxid = '$schoolid' ";
		}
		$sql = "SELECT a.*,b.xxmc FROM ".UC_DBTABLEPRE."academyInfo a,".UC_DBTABLEPRE."schoolinfo b WHERE a.xxid = b.id ".$sql." ORDER BY a.yxbm ASC";
		return $this->db->fetch_all($sql);
	}
	/**
	 * 
	* @Title: get_college_by_id
	* @Description: 得到基本院糸的信息
	* @param @param int $collegeid
	* @return 院系基本信息数组
	* @author Ricker lhyfe@sohu.com
	 */
	function get_college_by_id($collegeid){
		$sql = "SELECT a.*,b.xxmc,b.id as bid FROM ".UC_DBTABLEPRE."academyInfo a, ".UC_DBTABLEPRE."schoolinfo b WHERE  a.xxid = b.id AND a.id = '$collegeid'";
		return $this->db->fetch_first($sql);
	}
	/**
	 * 
	* @Title: add_college
	* @Description: 进行院系的添加操作，返回 1 Or 0
	* @param @param Array $college
	* @return 1 Or 0
	* @author Ricker lhyfe@sohu.com
	 */
	
	function add_college($college){
		$status = 0;
		if(is_array($college)){
			$str = "INSERT INTO  ".UC_DBTABLEPRE."academyInfo ";
			$str .= $this->common_columns($college);
			$this->db->query($str);
			$status = $this->db->errno() ? 0 : 1;
		}
		return $status;
	}
	
	/**
	 * 
	* @Title: update_college
	* @Description: 更新院系的操作，返回的是1 Or 0
	* @param @param Array $college
	* @return 1 or 0
	* @author Ricker lhyfe@sohu.com
	 */
	function update_college($college,$academyinfoid){
		$status = 0;
		if(is_array($college)){
			$str = "UPDATE  ".UC_DBTABLEPRE."academyInfo ";
			$str .= $this->common_columns($college);
			$str .=" WHERE id = '$academyinfoid'";
			$this->db->query($str);
			$status = $this->db->errno() ? 0 : 1;
		}
		return $status;
	}
	
	/**
	 * 
	* @Title: delete_college
	* @Description: $collegeid可以是数字或数组处理，删除对应的院系信息
	* @param @param Array OR int $collegeid
	* @return 0 Or 1
	* @author Ricker lhyfe@sohu.com
	 */
	function delete_college($collegeid){
		if(is_array($collegeid)){
			$collegeid = implode(',',$collegeid);
		}
		$str = "DELETE FROM ".UC_DBTABLEPRE."academyInfo WHERE id IN ($collegeid)";
		$this->db->query($str);
		$status = $this->db->errno() ? 0 : 1;
		return $status;
	}
	
	/**
	 * 
	* @Title: common_columns
	* @Description: 传入数组类型的列名与列表，自动格式化成对应的SET格式
	* @param @param Array $columns
	* @param @return String 返回拼好的字符串
	* @author Ricker lhyfe@sohu.com
	 */
	function common_columns($columns){
		$columnstr  = ' SET ';
		foreach ($columns as $key=>$val){
			$columnstr .= " $key = '$val',";
		}
		$columnstr= substr($columnstr,0,strlen($columnstr)-1);
		return $columnstr;
	}
    /**
     * 
    * @Title: get_college_by_search
    * @Description: 根据搜索来得到基本院糸的信息
    * @param @param Array $search
    * @return Array
    * @author Ricker lhyfe@sohu.com
     */
	function get_college_by_search($search){
		$sql = "SELECT a.*,b.xxmc,b.id as bid FROM ".UC_DBTABLEPRE."academyInfo a, ".UC_DBTABLEPRE."schoolinfo b WHERE  a.xxid = b.id AND (a.yxmc LIKE '%$search%' OR a.yxbm LIKE '%$search%' OR a.yxlxr LIKE '%$search%')";
		return $this->db->fetch_all($sql);
	}

}

?>