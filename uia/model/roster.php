<?php

/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class rostermodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->rostermodel($base);
	}

	function rostermodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
    function get_total_num($sqladd = '') {
		$data = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."roster_student $sqladd");
		return $data;
	}
    function get_list($page, $ppp, $totalnum, $sqladd) {
		$start = $this->base->page_get_start($page, $ppp, $totalnum);
		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."roster_student $sqladd LIMIT $start, $ppp");
		return $data;
	}
	function get_roster_by_userno($newrosteruserno) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."roster_student WHERE userno='$newrosteruserno'");
		return $arr;
	}
	function get_roster_by_useridentity($newrosteridentity) {
		$arr = $this->db->fetch_first("SELECT * FROM ".UC_DBTABLEPRE."roster_student WHERE identity='$newrosteridentity'");
		return $arr;
	}
	
	function check_rolename($rolename) {
		$guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		$len = $this->dstrlen($rolename);
		if($len > 15 || $len < 2 || preg_match("/\s+|^c:\\con\\con|[%,\*\"\s\<\>\&]|$guestexp/is", $rolename)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function dstrlen($str) {
		if(strtolower(UC_CHARSET) != 'utf-8') {
			return strlen($str);
		}
		$count = 0;
		for($i = 0; $i < strlen($str); $i++){
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
	
	function check_rolenameexists($rolename) {
		$data = $this->db->result_first("SELECT RoleName FROM ".UC_DBTABLEPRE."roster_student WHERE RoleName='$rolename'");
		return $data;
	}
	function delete_roster($IDsarr) {
		$IDsarr = (array)$IDsarr;
		if(!$IDsarr) {
			return 0;
		}
		$IDs = $this->base->implode($IDsarr);
		
		if($IDs) {
			$this->db->query("DELETE FROM ".UC_DBTABLEPRE."roster_student WHERE ID IN($IDs)");
			$this->base->load('note');
			$_ENV['note']->add('deleteroster', "ID=$IDs");
			return $this->db->affected_rows();
		} else {
			return 0;
		}
	}
	
	
}

?>