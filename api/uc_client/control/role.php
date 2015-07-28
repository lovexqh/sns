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

define('PRIVATEPMTHREADLIMIT_ERROR', -1);
define('PMFLOODCTRL_ERROR', -2);
define('PMMSGTONOTFRIEND', -3);
define('PMSENDREGDAYS', -4);
define('CHATPMTHREADLIMIT_ERROR', -5);
define('CHATPMMEMBERLIMIT_ERROR', -7);

class rolecontrol extends base {

	function __construct() {
		$this->_rolecontrol();
	}

	function _rolecontrol() {
		parent::__construct();
		$this->load('user');
		$this->load('role');
	}

	function onls() {
 		$this->init_input();
 		$isreg = $this->input('isreg');

		$role = $_ENV['role']->getrolelist($isreg);

		$result = $role;

 		return $result;
	}
}
?>
