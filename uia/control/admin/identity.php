<?php

/*
 * [RuiJie-Grid] (C)2011-2014 Ruijie-Grid Inc. This is NOT a freeware, use is
 * subject to license terms $Id: user.php 1096 2011-05-13 11:26:36Z
 * svn_project_zhangjie $
 */

! defined ( 'IN_UC' ) && exit ( 'Access Denied' );

define ( 'UC_IDENTITY_CHECK_identityName_FAILED', - 1 );
define ( 'UC_IDENTITY_CHECK_identityName_EXISTS', - 2 );

define ( 'UC_LOGIN_SUCCEED', 0 );
class control extends adminbase {
	function __construct() {
		$this->control ();
	}
	function control() {
		parent::__construct ();
		if (getgpc ( 'a' ) != 'login' && getgpc ( 'a' ) != 'logout') {
			if (! $this->user ['isfounder'] && ! $this->user ['allowadminidentity']) {
				$this->message ( 'no_permission_for_this_module' );
			}
		}
		$this->load ( 'identity' );
	}
	function onls() {
		$schoolid =$this->getLoginUserSchoolId();
		$this->view->assign ('schoolid', $schoolid);
		$this->view->display ( "admin_identity" );
	}
	
	/**
	 *
	 *
	 *
	 * @Title: onidentityList
	 * ription:跳转admin_identity_list页面
	 *
	 * @author 肖萌
	 */
	function onidentityList() {
		$this->clearSession ();
		$this->view->display ( "admin_identity_list" );
	}
	
	/**
	 *
	 *
	 *
	 * @Title: ongetidentityList
	 * ription:在页面上显示identity的信息
	 *
	 * @author 肖萌
	 */
	function ongetidentityList() {
		$this->clearSession ();
		$keyvalue = $_POST ['key'];
		$pageSize = $_POST ['pageSize'];
		$pageIndex = $_POST ['pageIndex'];
		$identitylist = $_ENV ['identity']->getIdentityList ( $keyvalue, $pageIndex, $pageSize );
		$num = count ( $identitylist ['data'] );
		for($i = 0; $i < $num; $i ++) {
			$img = $identitylist ['data'] [$i] ['IdentityIcon'];
			$identitylist ['data'] [$i] ['IdentityIcon'] = "<img src='" . $img . "'/>";
		}
		// print_r($identitylist);
		// exit;
		$jsonstr = json_encode ( $identitylist );
		print_r ( $jsonstr );
	}
	
	/**
	 *
	 *
	 *
	 * @Title: onidentityAdd
	 * ription:显示身份添加界面
	 *
	 * @author 肖萌
	 */
	function onidentityAdd() {
		$identityId = $_GET ['identityId'];
		if ($identityId) {
			$this->view->assign ( 'identityId', $identityId );
		}
		$this->view->display ( 'admin_identity_add' );
	}
	
	/**
	 *
	 *
	 *
	 * @Title: onupIdentityById
	 * ription: 获取admin_identity_add页面的post值,并执行添加或更新操作
	 *
	 * @author 肖萌
	 */
	function onupIdentityById() {
		$identityId = $_GET ['identityId'];
		$identityjson = $_POST ['data'];
		$identityjson = stripslashes ( $identityjson );
		$identity = json_decode ( $identityjson, true );
		$identityName = $identity [0] [IdentityName];
		$num = $_ENV ['identity']->checkIdentityidByName ( $identityName, $identityId );
		if ($num == 1) {
			if ($identityId) {
				$_ENV ['identity']->upIdentityById ( $identity, $identityId );
			} else {
				$_ENV ['identity']->addIdentityById ( $identity );
			}
		}
	}
	function ongetIdentityByName() {
		$identityId = $_GET ['identityId'];
		$identity = $_ENV ['identity']->getIdentityById ( $identityId );
		print_r ( json_encode ( $identity ) );
	}
	function oncheckIdentityidByName() {
		$identityName = $_POST ['IdentityName'];
		echo $_ENV ['identity']->checkIdentityidByName ( $identityName );
	}
	function ondelIdentity() {
		$identityid = $_POST ['identityid'];
		$_ENV ['identity']->delIdentityById ( $identityid );
	}
	
	/**
	 *
	 *
	 *
	 * @Title: editIdentityRole
	 * ription: 当点击身份时，将身份id传入admin_identity_role页面
	 *
	 * @author 夏伟
	 */
	function oneditIdentityRole() {
		$identityId = isset ( $_GET ['identityId'] ) ? $_GET ['identityId'] : 0;
		$this->view->assign ( 'identityId', $identityId );
		$this->view->display ( 'admin_identity_role' );
	}
	
	/**
	 *
	 *
	 *
	 * @Title: getRoleByIdentity
	 * ription: 根据身份id查询该身份所对应的角色
	 *
	 * @author 夏伟
	 */
	function ongetRoleByIdentity() {
		$identityId = $_GET ['identityId'];
		$pageSize = $_POST ['pageSize'];
		$pageIndex = $_POST ['pageIndex'];
		$role = $_ENV ['identity']->getRoleByIdentity ( $identityId, $pageIndex, $pageSize );
		print_r ( json_encode ( $role ) );
	}
	
	/**
	 *
	 *
	 *
	 * @Title: getOtherRoleByIdentity
	 * ription: 根据身份id查询该身份可选择的角色
	 *
	 * @author 夏伟
	 */
	function ongetOtherRoleByIdentity() {
		$identityId = $_GET ['identityId'];
		$pageSize = $_POST ['pageSize'];
		$pageIndex = $_POST ['pageIndex'];
		$otherRole = $_ENV ['identity']->getOtherRoleByIdentity ( $identityId, $pageIndex, $pageSize );
		print_r ( json_encode ( $otherRole ) );
	}
	
	/**
	 *
	 *
	 *
	 * @Title: getAppByIdentity
	 * ription: 根据身份id查询该身份所拥有的功能，以及该功能所拥有的权限
	 *
	 * @author 夏伟
	 */
	function ongetAppByIdentity() {
		$identityId = isset($_GET['identityId']) ? $_GET['identityId'] : 0;
		$app = $_ENV ['identity']->getAppByIdentity ( $identityId );
		$apps = $app ['data'];
		$num = count ( $apps );
		$app ['total'] = $num;
		for($i = 0; $i < $num; $i ++) {
			$limit = explode ( ",", $apps [$i] ['RoleExtend'] );
			$limitResult = array_unique ( $limit );
			// $limitResult = array_values($limitResult);
			$str = "";
			foreach ( $limitResult as $value ) {
				if ($value == "I") {
					$str .= "增加" . ",";
				}
				if ($value == "D") {
					$str .= "删除" . ",";
				}
				if ($value == "U") {
					$str .= "修改" . ",";
				}
				if ($value == "S") {
					$str .= "查看" . ",";
				}
				if ($value == "R") {
					$str .= "发布" . ",";
				}
				if ($value == "A") {
					$str .= "审核" . ",";
				}
			}
			if($str != ""){
				$len = strlen($str);
				$str1 = substr($str, 0, $len-1);
			}else{
				$str1 = "";
			}
			$apps [$i] ['RoleExtend'] = $str1;
		}
		$app ['data'] = $apps;
		print_r ( json_encode ( $app ) );
	}
	
	/**
	 *
	 *
	 *
	 * @Title: addRole
	 * ription: 执行给身份分配角色的操作
	 *
	 * @author 夏伟
	 */
	function onaddRole() {
		$roleid = $_POST ['roleid'];
		$identityId = $_POST ['identityId'];
		if ($identityId != 0) {
			foreach ( $roleid as $value ) {
				$_ENV ['identity']->addIdentityRole ( $identityId, $value );
			}
		}
	}
	
	/**
	 *
	 *
	 *
	 * @Title: delRole
	 * ription: 执行删除身份的角色的操作
	 *
	 * @author 夏伟
	 */
	function ondelRole() {
		$roleid = $_POST ['roleid'];
		$identityId = $_POST ['identityId'];
		if ($identityId != 0) {
			foreach ( $roleid as $value ) {
				$_ENV ['identity']->delIdentityRole ( $identityId, $value );
			}
		}
	}
	function clearSession() {
		$_SESSION ['identitynames'] = null;
	}
}

?>