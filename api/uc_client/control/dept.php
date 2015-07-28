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

class deptcontrol extends base {

	function __construct() {
		$this->_deptcontrol();
	}

	function _deptcontrol() {
		parent::__construct();
		$this->load('dept');
	}

	function onls() {
 		$this->init_input();
 		$parentid = $this->input('parentid');
 		$childid = $this->input('childid');
		$result = $_ENV['dept']->get_dept_list($childid,$parentid);
 		return $result;
	}

	function ongetById(){
		$this->init_input();
 		$id = $this->input('id');
		$result = $_ENV['dept']->get_dept_by_id($id);
 		return $result;
	}
	
	/**
	 * 根据用户ID获取部门信息
	 */
	function onget_dept_by_uid(){
		$this->init_input();
 		$uid = $this->input('uid');
 		$this->load('user');
 		$user = $_ENV['user']->get_user_by_uid($uid);
 		if($user['identitytype']=='2'){ //老 师
 			$result['ParentDeptName'] = '烟台第二中学';
 		}
		if($user['identitytype']=='3'){ //学生
 			$result['ParentDeptName'] = '烟台第二中学';
 		}
		if($user['identitytype']=='4'){ //家长
 			$result['ParentDeptName'] = '烟台第二中学';
 		}
		$result = $_ENV['dept']->get_dept_by_id($user['DeptID']);
		$result['ParentDeptName'] = '烟台第二中学';
 		return $result;
	}
	/**
	 * 根据用户ID获取是否是班级负责人
	 */
	function onget_dept_by_manage(){
		$this->init_input();
		$deptid = $this->input('deptid');
		$this->load('dept');
		$result = $_ENV['dept']->get_dept_by_manager($deptid);
 		return $result;
	}
	/**
	 * 根据用户ID获取父级部门信息
	 */
	function onget_parent_dept_by_uid(){
		$this->init_input();
 		$uid = $this->input('uid');
 		$this->load('user');
 		$user = $_ENV['user']->get_user_by_uid($uid);
		$result = $_ENV['dept']->get_parent_dept_by_id($user['DeptID']);
 		return $result;
	}
	/**
	 +----------------------------------------------------------
	 * 根据条件获取部门信息
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	function onget_dept_by_where(){
		$this->init_input();
 		$typeid = $this->input('typeid');
 		$parentid = $this->input('parentid');
 		$this->load('dept');
		$result = $_ENV['dept']->get_parent_dept_by_id($user['DeptID']);
 		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据用户ID获取老师用户所在的部门信息
	 +----------------------------------------------------------
	 * @return multitype:array |NULL
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-15 下午1:47:18
	 +----------------------------------------------------------
	 */
	function onget_departinfo_by_uid(){
		$this->init_input();
		$uid = $this->input('uid');
		if (intval($uid)){
			$fields = implode( ',' , array('DeptID','DepartName') );
			$data = $_ENV['dept']->get_departinfo_by_uid($uid,$fields);
			$result = array(
					'id'	=>	$data['DeptID'],
					'departname'	=>	$data['DepartName']
			);
			return $result;
		}
		else
			return null;
	}
}
?>
