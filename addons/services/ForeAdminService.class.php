<?php
class ForeAdminService extends Service {
	private $popedom;
	private $adminRule;
	private $mid;
	private $popupAdminRule=array(

	);

    public function __construct($data) {
		$this->mid = intval($_SESSION['mid']);
		$this->init($data);
		$this->run();
    }
    
    public function statusNameArray($config) {
    	return array(
    			$config['noaudit']=>'<font class="noaudit">未审核</font>'
    			,$config['pass']=>'<font class="pass">已通过</font>'
    			,$config['unpass']=>'<font class="unpass">已驳回</font>'
    			,$config['close']=>'<font class="close">已关闭</font>'
    			,''=>''
    	);
    }
    
    public function getConfig($action = 0, $appname = '') {
    	$appname = $appname ? $appname : APP_NAME;
    	$appname = strtolower($appname);
    	 
    	//取应用表配置信息
    	$action = isset($action) ? $action : ACTION_NAME;
    	$action = strtolower($action);
    
    	$config = D('ForeAdmin',$appname)->getConfig();
    	if(is_numeric($action)) {
    		$r = 0;
    		foreach($config as $c) {
    			if($r++==$action) {
    				$config = $c;
    				break;
    			}
    		}
    	} else {
    		$config = $config[$action];
    	}
    
    	return $config;
    }

    //获取某人某应用的权限
    public function getPopedom($uid = '', $appname = ''){
    	global $ts;
    	
    	$uid = $uid ? $uid : $this->mid;
    	$appname = $appname ? $appname : APP_NAME;
    	$appname = strtolower($appname);
    	
    	//$userAppLimit = get_userAppLimit_by_uid($uid);
    	$userAppLimit = $ts['userAppLimit'];
    	$popedom = '';
		foreach($userAppLimit as $L) {
			if($L['app_name']==$appname){
				$popedom .= $L['roleextend'] . '|';
			}
		}
		
		//$popedom = "I|D|U|S|R|A";
		
    	return trim($popedom);
    }

    //检查某人是否有某应用某操作的权限
    public function checkPopedom($actions, $uid = '', $appname = ''){
    	$uid = $uid ? $uid : $this->mid;
    	$appname = $appname ? $appname : APP_NAME;
    	$actions = strtolower($actions);
    	$pop = array('insert'=>'I','del'=>'D','edit'=>'U','view'=>'S','add'=>'R','pass'=>'A','unpass'=>'A');
    	
    	$popedom = $this->getPopedom($uid, $appname);
    	
    	return strpos($popedom,$pop[$actions])!==false;
    }

    //检查某应用是否需要审核
    public function isAuditApp($appname = '') {
    	global $ts;
    	
    	$appname = $appname ? $appname : APP_NAME;
    	$appname = strtolower($appname);
    	
    	if(isset($ts['isAudit'][$appname])) {
    		if($ts['isAudit'][$appname]==1) return true;
    	} else {
	    	$info = model('App')->where("app_name='".$appname."'")->find();
	    	if($info) {
	    		$ts['isAudit'][$appname] = $info['isAudit'];
	    		if($info['isAudit']==1) return true;
	    	}
    	}
    	
    	return false;
    }

    public function setAuditStatus($data, $action = 0, $appname = '') {
    	$appname = $appname ? $appname : APP_NAME;
    	$appname = strtolower($appname);
    	
    	$config = D('ForeAdmin',$appname)->getConfig();
    	if(empty($action)) {
    		foreach($config as $c) {
    			$config = $c;
    			break;
    		}
    	} else {
    		$config = $config[$action];
    	}
    	if($config) {
    		//$data[$config['statusFieldName']] = $this->isAuditApp($appname) ? $config['noaudit'] : $config['pass'];
    		$data[$config['statusFieldName']] = $config['noaudit'];
    	}
    	
    	return $data;
    }

    public function getAuditStatus($data, $action = 0, $appname = '', $tablename = '') {
    	$appname = $appname ? $appname : APP_NAME;
    	$appname = strtolower($appname);
    	
    	$config = D('ForeAdmin',$appname)->getConfig();
    	if(empty($action)) {
    		foreach($config as $c) {
    			$config = $c;
    			break;
    		}
    	} else {
    		$config = $config[$action];
    	}
    	
    	if($config) {
	    	if(!empty($tablename)) $tablename .= ".";
	    	$uidFieldName = $config['uidFieldName'] ? $config['uidFieldName'] : 'uid';

    		if(is_array($data)) {
	    		$map = array();
    			if($this->isAuditApp($appname)) {
		    		$map[$tablename . $config['statusFieldName']] = array('EQ',$config['pass']);
	    		} else {
	    			$map[$tablename . $config['statusFieldName']] = array('in',array($config['noaudit'],$config['pass']));
	    		}
    			$map[$tablename . $uidFieldName] = $this->mid;
    			$map['_logic'] = 'or';
    			$data['_complex'] = $map;
    		} else {
    			if($this->isAuditApp($appname)) {
    				$data .= (empty($data) ? "":" and ") . "(" . $tablename . "`" . $config['statusFieldName'] . "`=" . $config['pass'] . " or " . $tablename . "`" . $uidFieldName . "`=" . $this->mid.")";
    			} else {
    				$data .= (empty($data) ? "":" and ") . "(" . $tablename . "`" . $config['statusFieldName'] . "` in (" . $config['noaudit'] . "," . $config['pass'] . ") or " . $tablename . "`" . $uidFieldName . "`=" . $this->mid . ")";
    			}
    		}
    	}
    	
    	return $data;
    }

    //显示有权限的管理按钮
	public function showAdminBtns($list, $data)
	{
		// I增加 D删除 U修改 S查看 R发布 A审核
		//$popedom = "I|D|U|S|R|A";
		$popedom = $this->getPopedom($this->mid, APP_NAME);

		$toolbar = '';
		if(strpos($popedom,'A')!==false) $toolbar .= '<a href="javascript:;" class="easyui-linkbutton small-normal" onclick="Admin(\'pass\')">通过</a> ';
		if(strpos($popedom,'A')!==false) $toolbar .= '<a href="javascript:;" class="easyui-linkbutton small-normal" onclick="Admin(\'unpass\')">驳回</a> ';
		//if(strpos($popedom,'U')!==false) $toolbar .= '<a href="javascript:;" class="easyui-linkbutton small-normal" onclick="Admin(\'close\')">关闭</a> ';
		if(strpos($popedom,'A')!==false) $toolbar .= '<a href="javascript:;" class="easyui-linkbutton small-normal" onclick="Admin(\'del\')">删除</a> ';
		$list['toolbar']  = $toolbar;

		//取应用表配置信息
		$action = strtolower(ACTION_NAME);
		$action = substr($action, -6) == '_admin' ? substr($action, 0, -6) : $action;
		$config = D('ForeAdmin',APP_NAME)->getConfig();
		$config = $config[$action];

		$statusName  = array(
				$config['noaudit']=>'<font color="#FF0000">未审核</font>'
				,$config['pass']=>'<font color="#0000FF">已通过</font>'
				,$config['unpass']=>'<font color="#FF0000">已驳回</font>'
				,$config['close']=>'<font color="#FF0000">已关闭</font>'
		);

		foreach($list['data'] as $key => $value){
			if($list['data'][$key]!=""){
				$id = $list['data'][$key][$config['idFieldName']];
				$status = $list['data'][$key][$config['statusFieldName']];
				
				$list['data'][$key]['statusName']  = $statusName[$status] ? $statusName[$status] : $status;
				
				$adminBtns = '';
				if(strpos($popedom,'A')!==false) $adminBtns .= '<a href="javascript:;" class="btn_a" onclick="Admin(\'edit\',\''.$id.'\',this)">修改</a> ';
				if(strpos($popedom,'A')!==false) $adminBtns .= '<a href="javascript:;" class="btn_a" onclick="Admin(\'del\',\''.$id.'\',this)">删除</a> ';
				if(strpos($popedom,'A')!==false && $status == $config['close']) $adminBtns .= '<a href="javascript:;" class="btn_a" onclick="Admin(\'close\',\''.$id.'\',this)">关闭</a> ';
				$list['data'][$key]['adminBtns']  = $adminBtns;
				
				$auditBtns = '';
				if(strpos($popedom,'A')!==false && ($status == $config['noaudit'] || $status == $config['unpass'])) $auditBtns .= '<a href="javascript:;" class="btn_a" onclick="Admin(\'pass\',\''.$id.'\',this)">通过</a> ';
				if(strpos($popedom,'A')!==false && ($status == $config['noaudit'] || $status == $config['pass'])) $auditBtns .= '<a href="javascript:;" class="btn_a" onclick="Admin(\'unpass\',\''.$id.'\',this)">驳回</a> ';
				$list['data'][$key]['auditBtns']  = $auditBtns;
			}
		}
		
		return $list;
	}

	//服务初始化
	public function init($data=''){
		$this->popedom = $data[0];
		$this->adminRule = array(
				
		);
		
	}

	//运行服务，系统服务自动运行
	public function run(){
	}

	/* 后台管理相关方法 */

	//启动服务，未编码
	public function _start(){
		return true;
	}
	
	//停止服务，未编码
	public function _stop(){
		return true;
	}

	//卸载服务，未编码
	public function _install(){
		return true;
	}

	//卸载服务，未编码
	public function _uninstall(){
		return true;
	}
}
?>