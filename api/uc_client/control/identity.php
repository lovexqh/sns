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

class identitycontrol extends base {

	function __construct() {
		$this->_identitycontrol();
	}

	function _identitycontrol() {
		parent::__construct();
		$this->load('user');
		$this->load('identity');
	}

	function onls() {
 		$this->init_input();
 		$order = $this->input('order');
 		$result = $_ENV['identity']->getlist($order);
 		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据用户ID信息获取角色身份信息
	 +----------------------------------------------------------
	 * @return array 查询后的结果
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-15 下午2:01:06
	 +----------------------------------------------------------
	 */
	function onget_identity_info(){
		$this->init_input();
		$username = $this->input('username');
		$isuid = $this->input('isuid');
		return $_ENV['identity']->get_identity_info($username,$isuid);
	}
	/**
	 +----------------------------------------------------------
	 * 根据用户ID信息获取角色身份信息
	 +----------------------------------------------------------
	 * @return array 查询后的结果
	 * @author 小伟
	 +----------------------------------------------------------
	 * 创建时间：2013-3-23 下午2:01:06
	 +----------------------------------------------------------
	 */	
	function onget_member_email(){
		$this->init_input();
		$identityId = $this->input('identityId');
		$identityType = $this->input('identityType');
		return $_ENV['identity']->get_member_email($identityType,$identityId);		
	}
}
?>
