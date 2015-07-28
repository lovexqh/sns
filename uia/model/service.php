<?php

/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class servicemodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->servicemodel($base);
	}

	function servicemodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function getServiceList($keyvalue, $page=0,$pagesize=50){
		if ($keyvalue != ''){
			$sql .= " AND (serviceID LIKE '%$keyvalue%' OR serviceDes LIKE '%$keyvalue%' OR method LIKE '%$keyvalue%' )";
		}
		$index = $page * $pagesize;
		$sql1 = "SELECT COUNT(*) AS num FROM ".UC_DBTABLEPRE."service WHERE id>0 ".$sql;
		$sum = $this->db->fetch_first($sql1);
		$data['total'] = $sum['num'];
		$sql2 = "SELECT * FROM ".UC_DBTABLEPRE."service WHERE id>0 ".$sql." ORDER BY id DESC LIMIT $index,$pagesize";
		$data['data'] = $this->db->fetch_all($sql2);
		return $data;
	}
	
	function addServiceByArray($rows){
		$serviceID = $rows[0]['serviceID'];
		$class = $rows[0]['class'];
		$method = $rows[0]['method'];
		$serviceDes = $rows[0]['serviceDes'];
		$upstr = "INSERT INTO ".UC_DBTABLEPRE."service (serviceID,class,method,serviceDes) VALUES ('$serviceID','$class','$method','$serviceDes')";
		$this->db->query($upstr);
	}
	
	function upServiceByArray($rows, $id){
		$upstr = "UPDATE ".UC_DBTABLEPRE."service SET ";
		foreach ($rows[0] as $key=>$value){
			$upstr .= "`$key` = '$value' ,";
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$upstr .= " WHERE id = '$id'";
		$this->db->query($upstr);
	}
	
	function checkServiceByServiceid($id, $serviceid){
		$chekstr = "SELECT COUNT(*) FROM ".UC_DBTABLEPRE."service WHERE serviceID='$serviceid' AND id!='$id'";
		$result = $this->db->result_first($chekstr);
		if($result == 0){
			return 1;
		}
		else{
			return 0;
		}
	}
	
	function delServiceById($id){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."service WHERE id in ($id)";
		$this->db->query($delstr);
	}
	
	function getServiceById($id){
		$service = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."service WHERE id='$id'");
		return $service;
	}
	
	function getInputParamById($id, $pageIndex, $pagesize){
		$index = $pageIndex * $pagesize;
		$sql1 = "SELECT COUNT(*) AS num FROM ".UC_DBTABLEPRE."service_param WHERE fwid='$id' AND cslx='1'";
		$sum = $this->db->fetch_first($sql1);
		$data['total'] = $sum['num'];
		$sql2 = "SELECT * FROM ".UC_DBTABLEPRE."service_param WHERE fwid='$id' AND cslx='1' ORDER BY sfbt LIMIT $index,$pagesize";
		$data['data'] = $this->db->fetch_all($sql2);
		for($i=0; $i<$data['total']; $i++){
			$sfbt = $data['data'][$i][sfbt];
			if($sfbt == 1){
				$data['data'][$i][bt] = "必填";
			}else if($sfbt == 2){
				$data['data'][$i][bt] = "可选";
			}
		}
		return $data;
	}
	
	function getOutputParamById($id, $pageIndex, $pagesize){
		$index = $pageIndex * $pagesize;
		$sql1 = "SELECT COUNT(*) AS num FROM ".UC_DBTABLEPRE."service_param WHERE fwid='$id' AND cslx='2'";
		$sum = $this->db->fetch_first($sql1);
		$data['total'] = $sum['num'];
		$sql2 = "SELECT * FROM ".UC_DBTABLEPRE."service_param WHERE fwid='$id' AND cslx='2' ORDER BY sfbt LIMIT $index,$pagesize";
		$data['data'] = $this->db->fetch_all($sql2);
		return $data;
	}
	
	function addParamByArray($rows){
		$upstr = "INSERT ".UC_DBTABLEPRE."service_param SET ";
		foreach ($rows[0] as $key=>$value){
			$upstr .= "$key = '$value' ,";
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$this->db->query($upstr);
	}
	
	function delParamById($id){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."service_param WHERE id in ($id)";
		$this->db->query($delstr);
	}
	
	function checkInputParamByCsmc($id, $csmc){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."service_param WHERE fwid='$id' AND csmc = '$csmc' AND cslx='1'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	
	function checkOutputParamByCsmc($id, $csmc){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."service_param WHERE fwid='$id' AND csmc = '$csmc' AND cslx='2'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	
}

?>