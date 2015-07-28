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

class identitymodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->_identitymodel($base);
	}

	function _identitymodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function getlist($order='') {
		if(!empty($order)){
			$order = $order.',';
		}
		$data = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."identity order by ".$order."IdentityID ASC");
		return $data;
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取用户角色身份等信息
	 +----------------------------------------------------------
	 * @param string $username ID or 昵称 or Email
	 * @param number $isuid 0：ID，1：昵称，2：Email
	 * @return array 查询后的结果
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-15 下午2:01:06
	 +----------------------------------------------------------
	 */
	function get_identity_info($username,$isuid){
		if (empty($username)) {
			return null;
		}
		if($isuid==1) {
			$rolesql = "SELECT identitytype FROM ".UC_DBTABLEPRE."members WHERE username='".$username."'";
		} elseif($isuid == 2) {
			$rolesql = "SELECT identitytype FROM ".UC_DBTABLEPRE."members WHERE email='".$username."'";
		} else {
			$rolesql = "SELECT identitytype FROM ".UC_DBTABLEPRE."members WHERE uid=".$username;
		}
		$result = $this->db->fetch_first($rolesql);
		return $result['identitytype'];
	}
	/**
	 +----------------------------------------------------------
	 * 获取用户email信息
	 +----------------------------------------------------------
	 * @param string $username ID or 昵称 or Email
	 * @param number $isuid 0：ID，1：昵称，2：Email
	 * @return array 查询后的结果
	 * @author 小伟
	 +----------------------------------------------------------
	 * 创建时间：2013-3-23 下午2:01:06
	 +----------------------------------------------------------
	 */
	function get_member_email($identityType,$identityId){
		
		$sql = "SELECT email FROM ".UC_DBTABLEPRE."members WHERE identityID = ".$identityId." and identityType = ".$identityType;
		$result = $this->db->fetch_first($sql);
		return $result['email'];		
	}

}
?>
