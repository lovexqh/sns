<?php


/*
	[RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1096 2011-05-13 11:26:36Z svn_project_zhangjie $
*/

!defined('IN_UC') && exit ('Access Denied');

define('UC_USER_CHECK_USERNAME_FAILED', -1);
define('UC_USER_USERNAME_BADWORD', -2);
define('UC_USER_USERNAME_EXISTS', -3);
define('UC_USER_EMAIL_FORMAT_ILLEGAL', -4);
define('UC_USER_EMAIL_ACCESS_ILLEGAL', -5);
define('UC_USER_EMAIL_EXISTS', -6);

define('UC_LOGIN_SUCCEED', 0);
define('UC_LOGIN_ERROR_FOUNDER_PW', -1);
define('UC_LOGIN_ERROR_ADMIN_PW', -2);
define('UC_LOGIN_ERROR_ADMIN_NOT_EXISTS', -3);
define('UC_LOGIN_ERROR_SECCODE', -4);
define('UC_LOGIN_ERROR_FAILEDLOGIN', -5);

define('UC_USER_CHECK_REALNAME_FAILED', -7);
define('UC_USER_CHECK_ROLEID_FAILED', -8);
define('UC_USER_CHECK_DEPTID_FAILED', -9);
define('UC_USER_CHECK_USERNO_FAILED', -10);
define('UC_USER_CHECK_USERNO_EXISTS', -11);

class control extends adminbase {

	function __construct() {
		$this->control();
	}

	function control() {
		parent::__construct();
		if(getgpc('a') != 'login' && getgpc('a') != 'logout') {
			if(!$this->user['isfounder'] && !$this->user['allowadmincharacter']) {
				$this->message('no_permission_for_this_module');
			}
		}
		$this->load('character');
	}
	function onlscharacter() {
		$this->view->display('admin_character');
	}
	function ongetCharacterList(){
		//$this->clearSession();
		$opt = $_POST['opt'];
		$keyvalue = $_POST['key'];
		//print_r($keyvalue);
		$pageSize = $_POST['pageSize'];
		//echo($pageSize);
		$pageIndex = $_POST['pageIndex'];
		if ($opt == '0' || $opt == "")
		{
			//var_dump("数据类型0");
			$characterlist = $_ENV['character']->get_character_list_grid($wherear,$keyvalue,$pageIndex,$pageSize);
		}
		else
		{
			//var_dump("1");
			$characterlist = $_ENV['character']->get_character_list_xiang($wherear,$keyvalue,$pageIndex,$pageSize);
		}
		//print_r($characterlist);
		$jsonstr = json_encode($characterlist);
		print_r($jsonstr);
	}

	function ongetcharacterByDataid(){
		$dataid = isset($_GET['dataid']) ? $_GET['dataid'] : 0;
		//$ex = explode("'\",$dataid);
		//$dataid_php = decode()
		//var_dump($dataid);
		$this->view->assign('dataid',$dataid);
		$this->view->display('admin_character_role');
	}


	function ongetitemByDataid(){
		$dataid = isset($_GET['dataid']) ? $_GET['dataid'] : 0;
		//var_dump($dataid);
		$phpstr = explode("\'",$dataid);
		$dataid = $phpstr[1];
		//var_dump($dataid);
		$keyvalue = $_POST['key'];
		$pageSize = $_POST['pageSize'];
		$pageIndex = $_POST['pageIndex'];
		$itemlist = $_ENV['character']->get_characteritem_list_grid($dataid,$wherear,$keyvalue,$pageIndex,$pageSize);
		//var_dump($itemlist);
		$jsonstr = json_encode($itemlist);
		print_r($jsonstr);
	}

function ontypeAdd(){
		$this->view->display("admin_type_add");
	}
function onitemAdd(){
		$dataid = isset($_GET['dataid']) ? $_GET['dataid'] : 0;
		//var_dump($dataid);
		$this->view->assign('dataid',$dataid);
		$this->view->display("admin_item_add");
	}
function onchacteredit(){
	$dataid = isset($_GET['dataid']) ? $_GET['dataid'] : 0;
	$this->view->assign('dataid',$dataid);
	$this->view->display("admin_character_edit");
}
function onitemedit(){
	$autoid = isset($_GET['autoid']) ? $_GET['autoid'] : 0;
	//var_dump($autoid);
	$this->view->assign('autoid',$autoid);
	$this->view->display("admin_item_edit");
}
function ongettypeBydataid(){
	$dataid = isset($_GET['dataid']) ? $_GET['dataid'] : 0;
	$typeedit = $_ENV['character']->get_type_by_dataid($dataid);
	//var_dump($dataid);
	$jsonstr = json_encode($typeedit);
	print_r($jsonstr);
	//return $typeedit;
}
function ongetitemByautoid(){
	$autoid = isset($_GET['autoid']) ? $_GET['autoid'] : 0;
	//var_dump($autoid);
	$itemedit = $_ENV['character']->get_item_by_autoid($autoid);
	//var_dump($dataid);
	$jsonstr = json_encode($itemedit);
	print_r($jsonstr);
	//return $typeedit;
}
function onuptypeBydataid(){
		$dataid = isset($_GET['dataid']) ? $_GET['dataid'] : 0;
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
			$_ENV['character']->uptypeByArray($rows);
}
function onupitemByautoid(){
		$autoid = isset($_GET['autoid']) ? $_GET['autoid'] : 0;
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
			$_ENV['character']->upitemByArray($rows);
}
	function oninserttypeBydataid(){
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
			$_ENV['character']->inserttypeByArray($rows);
}
	function ondeltype(){
		$dataid = isset($_POST['data']) ? $_POST['data'] : 0;
		//var_dump($dataid);
		$_ENV['character']->deltypeById($dataid);
	}
	function ondelitem(){
		$autoid = isset($_POST['data']) ? $_POST['data'] : 0;
		//var_dump($autoid);
		$_ENV['character']->delitemById($autoid);
	}
	function oninsertitemBydataid(){
		$dataid = isset($_POST['dataid']) ? $_POST['dataid'] : 0;
		//var_dump($dataid);
		$jsonstr = $_POST['data'];
		$jsonstr = stripslashes($jsonstr);
		$rows = json_decode($jsonstr,true);
		//var_dump($rows);
		$_ENV['character']->insertitemByArray($rows);
}
function oncheckdataid(){
		$dataid = $_POST['dataid'];
		//var_dump($dataid);
		$result = $_ENV['character']->check_dataid($dataid);
		//var_dump($result);
		echo $result;
	}





}
?>