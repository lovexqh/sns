<?php
/*
 * Created on 2013-4-22
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
!defined('IN_UC') && exit ('Access Denied');

class teachbulidingmodel {

	var $db;
	var $base;
	var $errMsg;

	function __construct(& $base) {
		$this->teachbulidingmodel($base);
	}

	function teachbulidingmodel(& $base) {
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT . 'lib/baseInfoPub.php';

	}
	
	
	//一次性加载
	function get_teachbuliding_dept_tree($schoolid){
		$sql = '';
		if($schoolid != -1){
			$sql_a = " AND id = '".$schoolid."'";
		}
		$sql1 = "SELECT id,xxmc FROM ".UC_DBTABLEPRE."schoolinfo WHERE 1=1".$sql_a;
		$school = $this->db->fetch_all($sql1);		//所有学校
		$num_s = count($school);
		$treeNode = array();
		$j = 1;
		$m = 0;
		$tree1 = array(
				"id"=>$j++,
				"pid"=>0,
				"name"=>"学校",
				"eid"=>0,
				"ss"=>1
				);
		array_push($treeNode,$tree1);
		for($i = 0 ; $i < $num_s ; $i ++){
			$tree2 = array(
					"id"=>$j++,
					"pid"=>1,
					"name"=>$school[$i]['xxmc'],
					"eid"=>$school[$i]['id'],
					"ss"=>2
					);
			array_push($treeNode,$tree2);
		}
// 		print_r($treeNode);
// 		exit;
		return json_encode($treeNode);
	}
		
	
	function get_teachbuliding_list_grid($wherear,$keyvalue="",$page=0,$pagesize=50){
		$schoolid = $wherear['schoolid'];
		$eid = $wherear['eid'];
		$ss = $wherear['ss'];
		$sql = "";
		if(!$ss){
			if($schoolid){
				$sql .= " AND a.xxid=$schoolid";
			}
		}
		else if($ss == 1){
			$sql .= "";
		}
		else if($ss == 2){
			$sql .= " AND b.id=$eid";
		}
		if($keyvalue != ''){
			$sql .= " AND jxlmc like '%$keyvalue%'";
		}
		$index = $page * $pagesize;
		$sql1 = "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."teachbuliding a,".UC_DBTABLEPRE."schoolinfo b WHERE a.id > 0 AND a.xxid=b.id".$sql;
		$sum = $this->db->fetch_first($sql1);
		$data['total'] = $sum['num'];
		$sql2 = "SELECT a.*,b.xxmc FROM ".UC_DBTABLEPRE."teachbuliding a,".UC_DBTABLEPRE."schoolinfo b WHERE a.id>0 AND a.xxid=b.id ".$sql." order by id DESC LIMIT $index,$pagesize";
		$data['data'] = $this->db->fetch_all($sql2);
		return $data;
	}
	
	function get_xxmc_by_id($schoolid){
		$sql = "SELECT xxmc,id as xxid FROM ".UC_DBTABLEPRE."schoolinfo WHERE id=$schoolid";
		$arr = $this->db->fetch_first($sql);
		return $arr;
	}
	
	function checkTeachbulidingByMc($jxlmc,$teaid,$schoolid){
		$chekstr = "SELECT count(*) FROM ".UC_DBTABLEPRE."teachbuliding WHERE jxlmc='$jxlmc' AND xxid='$schoolid' AND id NOT IN ($teaid)";
		$result = $this->db->result_first($chekstr);
		if($result == 0){
			return 1;
		}
		else{
			return 0;
		}
	}
	
	function upTeachbulidingByArray($tealist){
		$id = $tealist['teaid'];
		unset($tealist['teaid']);
		$jxlmcs = $tealist['jxlmcs'];
		unset($tealist['jxlmcs']);
		if($id == 0 && $jxlmcs == 1 ){
			$instr = "INSERT ".UC_DBTABLEPRE."teachbuliding(";
			foreach($tealist as $key=>$value){
				if($key == "remark"){
					$keysql .= "$key";
					$valuesql .= "'$value'";
				}
				else{
					$keysql .= "$key,";
					$valuesql .= "'$value',";
				}
			}
			$instr .= $keysql.") VALUES(".$valuesql.")";
			$this->db->query($instr);
		}
		else if($jxlmcs == 1){
			$upstr = "UPDATE ".UC_DBTABLEPRE."teachbuliding SET ";
			foreach ($tealist as $key=>$value){
				if($key == "remark"){
					$sql .= "$key='$value'";
				}
				else{
					$sql .= "$key='$value',";
				}
			}
			$upstr .= $sql." WHERE id=$id";
			$this->db->query($upstr);
		}
	}
	
	function delTeachbulidingById($teaid){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."teachbuliding WHERE id in ($teaid)";
		$this->db->query($delstr);
	}
	
	function getTeachbulidingById($teaid){
		$sql = "SELECT a.*,b.xxmc,b.id as xxid FROM ".UC_DBTABLEPRE."teachbuliding a,".UC_DBTABLEPRE."schoolinfo b WHERE a.xxid=b.id AND a.id=$teaid";
		$result = $this->db->fetch_first($sql);
		return $result;
	}
	
}
?>
