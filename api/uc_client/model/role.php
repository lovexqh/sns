<?php
/*=========================================================*
 * GRIDSNS
 *
 * @auth				xiaobo.sun@ruijie-grid.com
 * @copyright			(C) 2012-2022 GRIDSNS
 * @license				http://www.ruijie-grid.com
 * @Created				2012-8-24
 *=========================================================*/

!defined('IN_UC') && exit('Access Denied');

class rolemodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->_rolemodel($base);
	}

	function _rolemodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function getrolelist($isreg=0,$sqladd='') {
		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."role where isreg = $isreg $sqladd");
		return $data;
	}
	
}
?>
