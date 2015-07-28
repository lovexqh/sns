<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class modulemodel {

	var $db;
	var $base;
	var $errMsg;

	function __construct(&$base) {
		$this->modulemodel($base);
	}

	function modulemodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT.'lib/baseInfoPub.php';

	}
	
	function getModuleList($pageIndex=0,$pagesize=50){
		$index = $pageIndex * $pagesize;
		$sum = $this->db->fetch_first( "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."uia_func");
		$data['total'] = $sum['num'];
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."uia_func ORDER BY id DESC LIMIT $index,$pagesize";
		$data['data'] =  $this->db->fetch_all($sql);
		for($i=0; $i<$data['total']; $i++){
			$pid = $data['data'][$i][pid];
			if($pid){
				$pmodule = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."uia_func WHERE id=$pid");
				$data['data'][$i][pName] = $pmodule[appAlias];
			}
		}
		return $data;
	}
	
	function addModuleByArray($rows){
		$upstr = "INSERT ".UC_DBTABLEPRE."uia_func SET ";
		foreach ($rows[0] as $key=>$value){
			$upstr .= "$key = '$value' ,";
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$this->db->query($upstr);
	}
	
	function upModuleByArray($rows, $id){
		$upstr = "UPDATE ".UC_DBTABLEPRE."uia_func SET ";
		foreach ($rows[0] as $key=>$value){
			$upstr .= "$key = '$value' ,";
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$upstr .= " WHERE id = '$id'";
		$this->db->query($upstr);
	}
	
	function checkModuleByAppname($appName, $id=0){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."uia_func WHERE appName = '$appName' AND id!='$id' ";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	
	/*---------------------------------------------------*/
	
	function checkModuleByAppalias($appAlias, $id=0){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."uia_func WHERE appAlias = '$appAlias' AND id!='$id' ";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	
	function checkModuleByAppentry($appEntry, $id=0){
		if($appEntry){
			$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."uia_func WHERE appEntry = '$appEntry' AND id!='$id'";
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
	
	function delModuleById($id){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."uia_func WHERE id='$id'";
		$this->db->query($delstr);
	}
	
	
	function getDeptTree(){
		$sql = "SELECT id, appAlias as text, pid FROM ".UC_DBTABLEPRE."uia_func ORDER BY id";
		$node = $this->db->fetch_all($sql);
		return json_encode($node);
	}
	
	function getModuleById($id){
		return $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."uia_func WHERE id='$id'");
	}
	
	function chkModule($id, $appName, $appAlias, $appEntry){
		$chkAppName = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."uia_func WHERE appName='$appName' AND id != '$id'");
		$chkAppAlias = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."uia_func WHERE appAlias='$appAlias' AND id != '$id'");
		$chkAppEntry = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."uia_func WHERE appEntry='$appEntry' AND id != '$id'");
		if( $chkAppName ){
			$num = 1;
		}else{
			if( $chkAppAlias ){
				$num = 2;
			}else{
				if( $chkAppEntry ){
					$num = 3;
				}else{
					$num = 0;
				}
			}
		}
		return $num;
	}
	
	function upModuleById($module){
		$id = $module['id'];
		unset($module['id']);
		$upstr = "UPDATE ".UC_DBTABLEPRE."uia_func SET ";
		foreach ($module as $key=>$value){
			$upstr .= "$key = '$value' ,";
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$upstr .= " WHERE id='$id'";
		$num = $this->db->query($upstr);
		return $num;
	}
	
	function addNewModule($module){
		$upstr = "INSERT ".UC_DBTABLEPRE."uia_func SET ";
		foreach ($module[0] as $key=>$value){
			$upstr .= "$key = '$value' ,";
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$this->db->query($upstr);
	}
	
}

?>