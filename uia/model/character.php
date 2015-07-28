<?php


/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit ('Access Denied');

class charactermodel {

	var $db;
	var $base;

	function __construct(& $base) {
		$this->charactermodel($base);
	}

	function charactermodel(& $base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	function get_character_list($sqladd = '') {
		$data = $this->db->fetch_all("SELECT * FROM " . UC_DBTABLEPRE . "dict_type a order by a.dataid");
		//var_dump($data);
		return $data;
	}

	function get_character_list_grid($wherear, $keyvalue = "", $page = 0, $pagesize = 50) {
		$sql = "";
		if ($keyvalue != '') {
			$wherestr .= " AND (datacn LIKE '%$keyvalue%' OR dataen LIKE '%$keyvalue%' OR dataid LIKE '%$keyvalue%' )";
		}
		$index = $page * $pagesize;
		$sum = $this->db->fetch_first("SELECT COUNT(*) as num FROM " . UC_DBTABLEPRE . "dict_type WHERE  dataid !='' " . $wherestr);
		$data['total'] = $sum['num'];
		$sql = "SELECT * FROM " . UC_DBTABLEPRE . "dict_type a WHERE  dataid !='' " . $wherestr . " order by a.dataid DESC LIMIT $index,$pagesize";

		$data['data'] = $this->db->fetch_all($sql);
		return $data;
	}

	function get_character_list_xiang($wherear, $keyvalue = "", $page = 0, $pagesize = 50) {
		$sql = "";

		if ($keyvalue != '') {
			//var_dump($keyvalue);
			$wherestr = " AND (a.itemcn LIKE '%".$keyvalue."%' OR a.itemcn LIKE '%".$keyvalue."%' )";
		}
		$index = $page * $pagesize;
		$sql = "SELECT * FROM " . UC_DBTABLEPRE . "dict_item a," . UC_DBTABLEPRE . "dict_type b WHERE  a.dataid = b.dataid " . $wherestr . " order by a.autoid DESC LIMIT $index,$pagesize";
		//var_dump($sql);
		$data['data'] = $this->db->fetch_all($sql);
		//echo $sql;
		//$sql1 = "SELECT * FROM ".UC_DBTABLEPRE."dict_type a WHERE  a.dataid in $data['data'] LIMIT $index,$pagesize";
		//var_dump($data['data']);
		return $data;
	}

	function get_characteritem_list_grid($dataid, $wherear, $keyvalue = "", $page = 0, $pagesize = 50) {
		$sql = "";
		if ($keyvalue != '') {
			$sql .= " WHERE (m.username like ('%$keyvalue%') or m.realname like('%$keyvalue%') or m.email like('%$keyvalue%'))";
		}

		$index = $page * $pagesize;
		$sum = $this->db->fetch_first("SELECT COUNT(*) as num FROM " . UC_DBTABLEPRE . "dict_item WHERE  dataid='$dataid' " . $wherestr);
		$data['total'] = $sum['num'];
		$sql = "SELECT * FROM " . UC_DBTABLEPRE . "dict_item a  WHERE a.dataid='$dataid'" . $wherestr . " order by a.autoid DESC LIMIT $index,$pagesize";

		$data['data'] = $this->db->fetch_all($sql);
		//var_dump($data);
		return $data;
	}
	function get_character_by_dataid($dataid) {
		//var_dump($dataid);
		$arr = $this->db->fetch_all("SELECT * FROM " . UC_DBTABLEPRE . "dict_item WHERE dataid='$dataid'");
		//var_dump($arr);
		return $arr;
	}
	function get_type_by_dataid($dataid) {
		//var_dump($dataid);
		$arr = $this->db->fetch_all("SELECT * FROM " . UC_DBTABLEPRE . "dict_type WHERE dataid='$dataid'");
		//var_dump($arr);
		return $arr;
	}
	function get_item_by_autoid($autoid) {
		//var_dump($autoid);
		$arr = $this->db->fetch_all("SELECT * FROM " . UC_DBTABLEPRE . "dict_item WHERE autoid='$autoid'");
		//var_dump($arr);
		return $arr;
	}
	function uptypeByArray($rows) {
		//var_dump($rows);
		$upstr = "UPDATE " . UC_DBTABLEPRE . "dict_type SET ";
		foreach ($rows[0] as $key => $value) {
			$upstr .= "$key = '$value' ,";
		}
		//var_dump($upstr);
		$upstr = substr($upstr, 0, (strlen($upstr) - 1));
		//var_dump($upstr);
		$upstr .= " WHERE dataid = '" . $rows[0]["dataid"] . "'";
		//var_dump($upstr);
		$this->db->query($upstr);
	}
	function upitemByArray($rows) {
		//var_dump($rows);
		$upstr = "UPDATE " . UC_DBTABLEPRE . "dict_item SET ";
		foreach ($rows[0] as $key => $value) {
			$upstr .= "$key = '$value' ,";
		}
		//var_dump($upstr);
		$upstr = substr($upstr, 0, (strlen($upstr) - 1));
		//var_dump($upstr);
		$upstr .= " WHERE autoid = '" . $rows[0]["autoid"] . "'";
		//var_dump($upstr);
		$this->db->query($upstr);
	}
	function inserttypeByArray($rows) {
		//var_dump($rows);
		$upstr = "INSERT INTO " . UC_DBTABLEPRE . "dict_type (";
		foreach ($rows[0] as $key => $value) {
			$upstr .= "$key ,";
		}
		//var_dump($upstr);
		$upstr = substr($upstr, 0, (strlen($upstr) - 1));
		$upstr .= ") VALUES (";
		foreach ($rows[0] as $key => $value) {
			$upstr .= "'$value' ,";
		}
		$upstr = substr($upstr, 0, (strlen($upstr) - 1));
		$upstr .= ")";
		var_dump($upstr);
		//$upstr .= " WHERE dataid = '".$rows[0]["dataid"]."'";
		//var_dump($upstr);
		$this->db->query($upstr);
	}

	function deltypeById($dataid) {
		//var_dump($dataid);
		$delstr = "DELETE FROM " . UC_DBTABLEPRE . "dict_type WHERE dataid in ($dataid)";
		$this->db->query($delstr);
	}
	function delitemById($autoid) {
		//var_dump($dataid);
		$delstr = "DELETE FROM " . UC_DBTABLEPRE . "dict_item WHERE autoid in ($autoid)";
		$this->db->query($delstr);
	}
	function insertitemByArray($rows) {
		//var_dump($rows);
		$upstr = "INSERT INTO " . UC_DBTABLEPRE . "dict_item (";
		foreach ($rows[0] as $key => $value) {
			$upstr .= "$key ,";
		}
		//var_dump($upstr);
		$upstr = substr($upstr, 0, (strlen($upstr) - 1));
		$upstr .= ") VALUES (";
		foreach ($rows[0] as $key => $value) {
			$upstr .= "'$value' ,";
		}
		$upstr = substr($upstr, 0, (strlen($upstr) - 1));
		$upstr .= ")";
		var_dump($upstr);
		$this->db->query($upstr);
	}

	function check_dataid($dataid) {
		//var_dump($dataid);
		$delstr = "select dataid FROM " . UC_DBTABLEPRE . "dict_type WHERE dataid = $dataid";
		$arr = $this->db->fetch_all("SELECT * FROM " . UC_DBTABLEPRE . "dict_type WHERE dataid='$dataid'");
		if (is_null($arr)) {
			return '1';
		} else {
			return '0';
		}
	}

}
?>