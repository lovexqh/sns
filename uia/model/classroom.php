<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class classroommodel {

	var $db;
	var $base;
	var $errMsg;

	function __construct(&$base) {
		$this->classroommodel($base);
	}

	function classroommodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT.'lib/baseInfoPub.php';

	}
	
	function getBuildingById($id){
		return $this->db->fetch_first("SELECT * FROM uc_teachbuliding WHERE id=$id");
	}
	function getBuildingByClrid($id){
		return $this->db->fetch_first("SELECT * FROM uc_teachbuliding WHERE id=(SELECT jxlid FROM uc_classroom WHERE id=$id)");
	}
	
	/*
	 *根据wherear数组得到对应的专业记录集以json形式返回来创建一棵新的树
	*/
	function get_classroom_list_grid($wherear, $keyvalue="", $page=0, $pagesize=50)
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
			$sql .= " AND jxlid=$eid";
		}
		if($keyvalue != ''){
			$sql .= " AND (jsh LIKE '%$keyvalue%' OR jsm LIKE '%$keyvalue%' )";
		}
		$index = $page * $pagesize;
		$sum = $this->db->fetch_first( "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."classroom WHERE  id > 0 ".$sql);
		$data['total'] = $sum['num'];
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."classroom WHERE  id > 0 ".$sql." order by id DESC LIMIT $index,$pagesize";
		$data['data'] =  $this->db->fetch_all($sql);
		return $data;
	}
	
	function getClassroom_by_id($id){
		$classroom = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."classroom WHERE  id > 0 AND id = '$id' ");
		return $classroom;
	}
	
	function addClassroomByArray($rows){
		$upstr = "INSERT ".UC_DBTABLEPRE."classroom SET ";
		foreach ($rows[0] as $key=>$value){
			$upstr .= "$key = '$value' ,";
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$this->db->query($upstr);
	}
	
	function upClassroomByArray($rows){
		$upstr = "UPDATE ".UC_DBTABLEPRE."classroom SET ";
		foreach ($rows[0] as $key=>$value){
			$upstr .= "$key = '$value' ,";
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$upstr .= " WHERE id = '".$rows[0]["id"]."'";
		//echo $upstr;
		$this->db->query($upstr);
	
	}
	
	function delClassroomById($id){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."classroom WHERE id in ($id)";
		//echo $delstr;
		$this->db->query($delstr);
	}
	
	function checkClassroomByJsh($jsh, $id){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."classroom WHERE jsh = '$jsh' AND id !='$id'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	function checkClassroomByJsm($jsm, $id, $buildingid){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."classroom WHERE jsm = '$jsm' AND jxlid = '$buildingid' AND id !='$id'";
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
	function get_classroom_tree($schoolid)
	{
		$reList = array ();
		$sql = '';
		if ($schoolid != - 1) {
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
			$sql2 = "SELECT id,jxlmc FROM " . UC_DBTABLEPRE . "teachbuliding WHERE xxid=" . $tree1 ['eid'];
			$jxl = $this->db->fetch_all ( $sql2 );
			$num_y = count ( $jxl );
			for($i2 = 1; $i2 <= $num_y; $i2 ++) {
				$tree2 = array (
					"id" => $j ++,
					"pid" => $tree1 ['id'],
					"text" => $jxl [$i2 - 1] ['jxlmc'],
					"eid" => $jxl [$i2 - 1] ['id'],
					"ss" => 2 
				);
				array_push ( $node, $tree2 );
			}
		}
		return json_encode ( $node );	
	}

}

?>