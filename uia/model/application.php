<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class applicationmodel {

	var $db;
	var $base;
	var $errMsg;

	function __construct(&$base) {
		$this->applicationmodel($base);
	}

	function applicationmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		include_once UC_ROOT.'lib/baseInfoPub.php';

	}
	
	/*
	 *根据wherear数组得到对应的专业记录集以json形式返回来创建一棵新的树
	*/
	function get_app_list_grid($keyvalue="", $page=0, $pagesize=50)
	{
		$sql = "";
		if($keyvalue != ''){
			$sql .= " AND (app_alias LIKE '%$keyvalue%')";
		}
		$index = $page * $pagesize;
		$sum = $this->db->fetch_first( "SELECT COUNT(*) as num FROM ".UC_DBTABLEPRE."app WHERE  app_id > 0 ".$sql);
		$data['total'] = $sum['num'];
		$sql = "SELECT * FROM ".UC_DBTABLEPRE."app WHERE  app_id > 0 ".$sql." order by app_id DESC LIMIT $index,$pagesize";
		$data['data'] =  $this->db->fetch_all($sql);
		return $data;
	}
	
	function getApp_by_id($id){
		$app = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."app WHERE  app_id > 0 AND app_id = '$id' ");
		return $app;
	}
	
	function addAppByArray($rows){
		$upstr = "INSERT ".UC_DBTABLEPRE."app SET ";
		foreach ($rows[0] as $key=>$value){
			$upstr .= "$key = '$value' ,";
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$this->db->query($upstr);
	}
	
	function upAppByArray($rows){
		$upstr = "UPDATE ".UC_DBTABLEPRE."app SET ";
		foreach ($rows[0] as $key=>$value){
			$upstr .= "$key = '$value' ,";
		}
		$upstr = substr($upstr,0,(strlen($upstr)-1));
		$upstr .= " WHERE app_id = '".$rows[0]["app_id"]."'";
		$this->db->query($upstr);
	}
	
	function delAppById($id){
		$delstr = "DELETE FROM ".UC_DBTABLEPRE."app WHERE app_id in ($id)";
		//echo $delstr;
		$this->db->query($delstr);
	}
	
	function checkAppByName($name, $id){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."app WHERE app_name = '$name' AND app_id !='$id'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	function checkAppByAlias($alias, $id){
		$chekstr = "SELECT * FROM ".UC_DBTABLEPRE."app WHERE app_alias = '$alias' AND app_id !='$id'";
		$result = $this->db->fetch_first($chekstr);
		if(count($result)-1 > 0){
			return 0;
		}else{
			return 1;
		}
	}
	
	
}

?>