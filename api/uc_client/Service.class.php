<?php
/**
 +------------------------------------------------------------------------------
 * UIA服务静态类
 +------------------------------------------------------------------------------
 * @category	UIA
 * @package		Service
 * @author		小波 <xiaobosun@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-11-22 下午5:22:53
 +------------------------------------------------------------------------------
 */
class UIA extends Think{
	
	/**
	 +----------------------------------------------------------
	 * 添加ThinkSNS与UCenter的用户映射
	 +----------------------------------------------------------
	 * @author 小波 2013-11-25
	 +----------------------------------------------------------
	 */
	static function add_ucenter_user_ref($uid,$uc_uid,$uc_username='',$identitytype=0){
		$uc_ref_data = array(
				'uid' => $uid,
				'uc_uid' => $uc_uid,
				'uc_identitytype'  => $identitytype,
				'uc_username'  => $uc_username,
		);
		if(M('ucenter_user_link')->add($uc_ref_data)){
			return $uc_ref_data;
		}else{
			return null;
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 更新社区与UIA的用户映射
	 +----------------------------------------------------------
	 * @author 小波 2013-11-25
	 +----------------------------------------------------------
	 */
	static function update_ucenter_user_ref($uid,$uc_uid,$uc_username='',$identitytype=0){
		$uid 		 &&	$map['uid']					= intval($uid);
		$uc_uid 	 && $map['uc_uid'] 				= intval($uc_uid);
		$identitytype&& $uc_ref_data['uc_identitytype'] = $identitytype;
		$uc_username && $uc_ref_data['uc_username'] = $uc_username;
		if(empty($uc_ref_data['uc_username']))return;
		M('ucenter_user_link')->where($map)->save($uc_ref_data);
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取社区与UIA的用户映射
	 +----------------------------------------------------------
	 * @author 小波 2013-11-25
	 +----------------------------------------------------------
	 */
	static function get_ucenter_user_ref($uid='',$uc_uid='',$uc_username='',$identitytype=''){
		$uid && $map['uid'] 				= intval($uid);
		$uc_uid && $map['uc_uid'] 			= intval($uc_uid);
		$uc_username && $map['uc_username'] = $uc_username;
		$identitytype && $map['uc_identitytype'] = $identitytype;
		if(!$map) return;
		return M('ucenter_user_link')->where($map)->find();
	}
	
	/**
	 +----------------------------------------------------------
	 * 模糊删除key中包含某些字符串的缓存数据
	 +----------------------------------------------------------
	 * @param $key 要查找删除的key
	 * @author 小波 2013-11-26
	 +----------------------------------------------------------
	 */
	static public function removeCache($key){
	
		if(is_null($key) || $key == '' ) return;
	
		static $_cache;
	
		if (!class_exists('Cache'))
			require_once CORE_PATH . '/sociax/Cache.class.php';
	
		$cache = Cache :: getInstance();
	
		$map['name'] = array('like','%'.$key.'%');
		$result = M('cache_log')->where($map)->findAll();
		foreach ($result as $vo){
			$result = $cache->rm($vo['name']);
			if ($result)
				unset ($_cache[$type . '_' . $vo['name']]);
		}
		M('cache_log')->where($map)->delete();
	}
	
	/**
	 +----------------------------------------------------------
	 * 将结果以xml序列化方式输出
	 +----------------------------------------------------------
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-4-27 上午9:49:26
	 +----------------------------------------------------------
	 */
	function uia_serialize($arr, $htmlon = 0) {
		require_cache(SITE_PATH."/addons/libs/Xml.class.php");
		return xml_serialize($arr, $htmlon);
	}
	
	/**
	 +----------------------------------------------------------
	 * 将结果以xml反序列化方式输出
	 +----------------------------------------------------------
	 * @author 小波 2013-11-24
	 +----------------------------------------------------------
	 */
	function uia_unserialize($s) {
		require_cache(SITE_PATH."/addons/libs/Xml.class.php");
		return xml_unserialize($s);
	}

	/**
	 +----------------------------------------------------------
	 * 判断参数中最后一位是否为nocache
	 +----------------------------------------------------------
	 * @author 小波 2013-11-23
	 +----------------------------------------------------------
	 */
	private function is_cache($args){
		if(strtolower($args[count($args)-1]) == 'nocache')
			return false;
		else
			return true;
	}
	/**
	 +----------------------------------------------------------
	 * 获取serivce的返回结果
	 +----------------------------------------------------------
	 * @param $method 服务名称
	 * @return $param 服务的参数列表
	 * @return $ismemcache 是否缓存数据
	 * @author 小波 2013-5-31
	 +----------------------------------------------------------
	 */
	private function service_call($method, $param, $ismemcache = true){

		//读取缓存数据 孙晓波于2013-8-2添加
		$cache_key = $method . "_" . self::array_to_string($param);
		//不缓存数据
		if($ismemcache === false) S($cache_key,null);
		
		//读取UIA的缓存
		$cache  =  S($cache_key);
		if($cache) {
			return $cache;
		}

		$wsdl = SERVICE_URL;

		//判断WebService是否可用
		$services = pathinfo($wsdl);
		if(function_exists("get_headers")){
			$_headers = get_headers($services['dirname'],1);
			if(!$_headers || !preg_match('/200/',$_headers[0])){
				self::show_service_error("WebService 远程服务器已经挂起！请稍后再试。");
			}
		}
		//如果参数数组$param为空
		if(empty($param) && gettype($param)=='array'){
			$param=array(""=>"");
		}else{
			//强转参数类型
			foreach ($param as $k=>$p){
				if (gettype($p) != 'array'){
					$param[$k] = (string)$p;
				}
			}
		}

		//查询服务功能
		$encoding = strtoupper(CODE_CHARSET);
		$soap = new SoapClient($wsdl, array('encoding'=>$encoding));
		$param['serviceid'] = $method;	//兼容新服务 刘春强 130724添加
		$data =array('service'=>$method, 'param'=> json_encode($param));
		try {
			$result = $soap->__soapCall("getData",
					array('getData' => $data),
					NULL,
					NULL);
		} catch (Exception $e) {
			self::show_service_error(" 访问 WebService 方法时失败！");
		}

		//Object to array 功能
		foreach($result as $key =>$value){
			if(gettype($value) == 'array' || gettype($value) == 'object'){
				$data[$key] = object_to_array($value);
			}
			else{
				$data[$key] = $value;
			}
		}
		$result = arrayKeyToLower(json_decode($data['return'],true));
		if (count($result['code'])>1 && $result['code']['error_code']!=0) { //出错提示
			self::show_service_error($method.' '.$result['code']['error_message']);
		}else{
			$time = 3600*24;
			//如果最后一个参数为数字
			if(is_numeric($ismemcache)) $time = $ismemcache;
			//生成缓存
			S($cache_key, $result['data'], $time, 'uia');
			return $result['data'];
		}
	}
	/**
	 +----------------------------------------------------------
	 * 外部测试等服务调用方法
	 +----------------------------------------------------------
	 * @author 小波 2013-11-23
	 +----------------------------------------------------------
	 */
	static public function webservice_call($method, $param, $ismemcache = false){
		return self::service_call($method, $param, $ismemcache);
	}
	/**
	 +----------------------------------------------------------
	 * 执行serivce的返回结果
	 +----------------------------------------------------------
	 * @param $method 服务名称
	 * @return $param 服务的参数列表
	 * @author 小波 2013-5-31
	 +----------------------------------------------------------
	 */
	static public function service_exec($wsdl, $method, $param){
	
		//判断WebService是否可用
		$services = pathinfo($wsdl);
		if(function_exists("get_headers")){
			$_headers = get_headers($wsdl,1);
			if(!$_headers || !preg_match('/200/',$_headers[0])){
				self::show_service_error("EIM WebService 远程服务器已经挂起！请稍后再试。");
			}
		}
		//如果参数数组$param为空
		if(empty($param) && gettype($param)=='array'){
			$param=array(""=>"");
		}else{
			//强转参数类型
			foreach ($param as $k=>$p){
				if (gettype($p) != 'array'){
					$param[$k] = (string)$p;
				}
			}
		}
	
		//查询服务功能
		$encoding = strtoupper(CODE_CHARSET);
		$soap = new SoapClient($wsdl, array('encoding'=>$encoding));
		foreach ($param as $val)
			$data[]=$val;
		try {
			$result = $soap->__soapCall($method, array($param));
		} catch (Exception $e) {
			self::show_service_error(" 访问 EIM WebService 方法时失败！");
		}
	
		//Object to array 功能
		foreach($result as $key =>$value){
			if(gettype($value) == 'array' || gettype($value) == 'object'){
				$data[$key] = object_to_array($value);
			}
			else{
				$data[$key] = $value;
			}
		}
		return $result->out;
	}
	/**
	 +----------------------------------------------------------
	 + 将数组转换为字符串
	 +----------------------------------------------------------
	 + @param	@param $array 要转换的多维数组
	 + @param	@return string
	 + @author	小波 (ZzStudio)
	 +----------------------------------------------------------
	 + 创建时间：	2013-8-2 下午3:19:52
	 +----------------------------------------------------------
	 */
	private function array_to_string($array){
		if (count($array)>0) {
			$param = "";
			//将数组转换为字符串
			foreach ($array as $k=>$v){
				if (gettype($v)=='array') {
					$param .= $k . self::array_to_string($v);
				}else{
					$param .= $k . $v;
				}
			}
			return $param;
		}else{
			return "empty";
		}
	}
	/**
	 +----------------------------------------------------------
	 * 显示错误信息
	 +----------------------------------------------------------
	 * @param $error 错误信息
	 * @author 小波 2013-6-28
	 +----------------------------------------------------------
	 */
	private function show_service_error($error){
		$html = '<div id="serviceStatus" style="position:absolute; z-index:999; top:0; width:100%; text-align:center; height:30px; line-height:30px; background-color:#FF0">
						<a href="javascript:;" onclick="document.getElementById(\'serviceStatus\').style.display=\'none\'" class="close" style="float:right; margin-right:15px; display:inline; font-family:Verdana, Geneva, sans-serif; font-size:150%">X</a>
						'.$error.'
					  </div>';
		//echo $html;
		redirect(U('home/Public/error500', array('msg'=>urlencode($error))));
		exit();
	}
	
	/**
	 +----------------------------------------------------------
	 + 注册数据时邮箱的黑白名单问题
	 +----------------------------------------------------------
	 + @param	@param unknown $email
	 + @param	@return boolean
	 + @return	boolean
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-1 上午11:31:00
	 +----------------------------------------------------------
	 */
	static public function uia_check_emailaccess($email) {
		$accessemail = $setting['accessemail'];
		$censoremail = $setting['censoremail'];
		$accessexp = '/('.str_replace("\r\n", '|', preg_quote(trim($accessemail), '/')).')$/i';
		$censorexp = '/('.str_replace("\r\n", '|', preg_quote(trim($censoremail), '/')).')$/i';
		if($accessemail || $censoremail) {
			if(($accessemail && !preg_match($accessexp, $email)) || ($censoremail && preg_match($censorexp, $email))) {
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
	}
	/**
	 +----------------------------------------------------------
	 + 检查用户Email是否合法
	 +----------------------------------------------------------
	 + @param	@param unknown $email
	 + @return	return_type
	 define('UC_USER_EMAIL_FORMAT_ILLEGAL', -4);
	 define('UC_USER_EMAIL_ACCESS_ILLEGAL', -5);
	 define('UC_USER_EMAIL_EXISTS', -6);
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-1 上午11:02:47
	 +----------------------------------------------------------
	 */
	static public function uia_user_checkemail($email){
		if(!preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email)){
			return -4;
		}elseif(!self::uia_check_emailaccess($email)) {
			return -5;
		}elseif(self::get_members_by_param(array('email'=>$email))) {
			return -6;
		} else {
			return 1;
		}
	}
	/**
	 +----------------------------------------------------------
	 + 验证用户昵称是否存在
	 +----------------------------------------------------------
	 + @param	@param unknown $username
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-5 上午10:16:02
	 +----------------------------------------------------------
	 */
	static public function uia_user_checkname($username, $uid = 0){
		if(empty($username) || !isset($username)) return null;
		$map['username'] = $username;
		$return = self::service_call('Uuse014', $map);
		if ($return) $return = $return[0];
		//如果返回的结果不为空，且返回的uid与当前用户的uid不一至时 表示昵称存在
		if (count($return)>0 && $return['uid']!=$uid) {
			$return = -1;
		}else{ //可以注册时返回0
			$return = 0;
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 根据学生的身份证号判断是否注册过了
	 +----------------------------------------------------------
	 + @param	@param unknown $map
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-5 上午10:16:02
	 +----------------------------------------------------------
	 */
	static public function uia_student_check($map){
		if (!count($map)) {
			$return = -1;
		}else{
			$return = self::service_call('Uuse033', $map);
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + UIA同步登录操作
	 +----------------------------------------------------------
	 + @param
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-1 下午6:35:18
	 +----------------------------------------------------------
	 */
	static public function uia_user_synlogin(){
		//为以后的单点登录功能做登录操作
		$return = '';
		return $return;
	}
	/**
	 +----------------------------------------------------------
	 * 注销登录后同步注销到UC
	 +----------------------------------------------------------
	 * @return string
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-4-27 上午9:48:21
	 +----------------------------------------------------------
	 */
	static public function uia_user_synlogout() {
		//为以后的单点登录功能做注销同步
		$return = '';
		return $return;
	}
	/**
	 +----------------------------------------------------------
	 * 添加用户注册信息
	 +----------------------------------------------------------
	 * @param $uid 必选 用户ID
	 * @return return 所影响的行数
	 * @author 小波 2013-7-2
	 +----------------------------------------------------------
	 */
	static public function add_member_by_data($data){
		if (count($data)<5) return null;
		$return = self::service_call('Uuse017', $data);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 设置用户的信息
	 +----------------------------------------------------------
	 * @param $uid 必选 用户ID
	 * @param $data 要更新信息的二维数组 key为数据库键名
	 * @param $oldpassword 旧密码
	 * @param $overwrite 是否判断旧密码的正确性
	 * @return return true|false
	 * @author 小波 2013-7-2
	 +----------------------------------------------------------
	 */
	static public function update_member_by_uid($uid, $data, $oldpassword = '', $overwrite = 0){
		if (intval($uid)==0) return null;
		$data['uid']=$uid;
	
		$return = self::service_call('Uuse016', $data);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 学生在线注册
	 +----------------------------------------------------------
	 * @param $data 学生的基础信息
	 * @author 小波 2013-11-24
	 +----------------------------------------------------------
	 */
	static public function add_student_register($data){
		if (!count($data)) {
			$return = -1;
		}else{
			$return = self::service_call('Uuse032', $data);
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 老师在线注册
	 +----------------------------------------------------------
	 * @param $data 老师的基础信息
	 * @author 小波 2013-11-24
	 +----------------------------------------------------------
	 */
	static public function add_teacher_register($data){
		if (!count($data)) {
			$return = -1;
		}else{
			$return = self::service_call('Uuse038', $data);
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 家长在线注册
	 +----------------------------------------------------------
	 * @param $data 家长的基础信息
	 * @author 小波 2013-11-24
	 +----------------------------------------------------------
	 */
	static public function add_parent_register($data){
		if (!count($data)) {
			$return = -1;
		}else{
			$return = self::service_call('Uuse034', $data);
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 区县级用户在线注册
	 +----------------------------------------------------------
	 * @param $data 区县级用户的基础信息
	 * @author 小波 2013-11-24
	 +----------------------------------------------------------
	 */
	static public function add_district_register($data){
		if (!count($data)) {
			$return = -1;
		}else{
			$return = self::service_call('Uuse035', $data);
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 地市级用户在线注册
	 +----------------------------------------------------------
	 * @param $data 地市级用户的基础信息
	 * @author 小波 2013-11-24
	 +----------------------------------------------------------
	 */
	static public function add_city_register($data){
		if (!count($data)) {
			$return = -1;
		}else{
			$return = self::service_call('Uuse036', $data);
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据老师身份证号获取老师基础信息
	 +----------------------------------------------------------
	 * @param  string $sfzjh 身份证件号
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_teacher_baseinfo_by_sfzjh($sfzjh){
		if (empty($sfzjh)) return null;
		$param['sfzjh']=$sfzjh;
		$return = self::service_call('Utch026', $param);
		if ($return) $return = $return[0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据老师姓名获取老师基础信息
	 +----------------------------------------------------------
	 * @param  string $xm 姓名
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_teacher_baseinfo_by_xm($xm){
		if (empty($xm)) return null;
		$param['xm']=$xm;
		$return = self::service_call('Utch026', $param);
		if ($return) $return = $return[0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据老师identityid号获取老师基础信息
	 +----------------------------------------------------------
	 * @param  string $identityid 身份证件号
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_teacher_baseinfo_by_identityid($identityid){
		if (empty($identityid)) return null;
		$param['identityid']=$identityid;
		$return = self::service_call('Utch026', $param);
		if ($return) $return = $return[0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据老师identityid号获取老师基础信息
	 +----------------------------------------------------------
	 * @param  array
	 identityId 身份ID 可选
	 schoolId 学校ID 可选
	 depID 部门ID 可选
	 xm 姓名 可选
	 sfzjh 身份证号 可选
	 page 当前页码 可选
	 rowCount 每页数据条数 可选
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_teacher_baseinfo_by_param($map){
		$return = self::service_call('Utch026', $map);
		if ($return) $return = $return[0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据学生identityid获取学生基础信息
	 +----------------------------------------------------------
	 * @param  string $sfzjh 身份证件号
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_student_baseinfo_by_identityid($identityid){
		if (intval($identityid) == 0) return array();
		$param['identityId']=$identityid;
		$return = self::service_call('Ustu010', $param);
		if ($return) $return = $return[0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据学生xm获取学生基础信息
	 +----------------------------------------------------------
	 * @param  string $xm 姓名
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_student_baseinfo_by_xm($xm){
		if(empty($xm)) return null;
		$param['xm']=$xm;
		$return = self::service_call('Ustu010', $param);
		if ($return) $return = $return[0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据学生学号获取学生基础信息
	 +----------------------------------------------------------
	 * @param  string $xh 学号
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_student_baseinfo_by_xh($xh){
		if (empty($xh)) return null;
		$param['xh']=$xh;
		$return = self::service_call('Ustu010', $param);
		if ($return) $return = $return[0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据学生多参数获取学生基础信息
	 +----------------------------------------------------------
	 * @param  array
	 identityId 身份ID 可选
	 schoolId 学校ID 可选
	 classId 班级ID 可选
	 familyId 家庭Id 可选
	 xh 学号 可选
	 xm 姓名 可选
	 sfzjh 身份证号码 可选
	 page 当前页码 可选
	 rowCount 每页数据条数 可选
	 * @param string $parentxm 家长姓名
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_student_baseinfo_by_param($param, $parentxm=''){
		if (empty($param)) return null;
		if (empty($parentxm)) { //如果仅查询学生信息时
			$return = self::service_call('Ustu010', $param);
		}else {	//如果根据学生信息及家长姓名查询时
			$param['parentxm'] = $parentxm;
			$return = self::service_call('Ustu007', $param);
		}
		if ($return) $return = $return[0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据家长的姓名获取家长信息
	 +----------------------------------------------------------
	 * @param  string $xm 家长姓名
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_parent_baseinfo_by_xm($xm){
		if (empty($xm)) return null;
		$param['xm']=$xm;
		$return = self::service_call('Uuse013', $param);
		if ($return) $return = $return[0];
	
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据家长的identityid获取家长信息
	 +----------------------------------------------------------
	 * @param  string $identityid 家长id
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_parent_baseinfo_by_identityid($identityid){
		if (empty($identityid)) return null;
		$param['identityid']=$identityid;
		$return = self::service_call('Uuse013', $param);
		if ($return) $return = $return[0];
	
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 不定参数获取家长基础信息
	 +----------------------------------------------------------
	 * @author 小波 2013-6-28
	 +----------------------------------------------------------
	 */
	static public function get_parent_baseinfo_by_param($param){
		if (empty($param)) return null;
		$return = self::service_call('Uuse013', $param);
		if ($return) $return = $return[0];
	
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 不定参数获取区县级用户基础信息
	 +----------------------------------------------------------
	 + @param	@param unknown $param
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-28 下午3:55:43
	 +----------------------------------------------------------
	 */
	static public function get_district_baseinfo_by_param($param){
		if (empty($param)) return null;
		$return = self::service_call('Udep015', $param);
		if ($return) $return = $return[0];
	
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 不定参数获取地市级用户基础信息
	 +----------------------------------------------------------
	 + @param	@param unknown $param
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-28 下午3:55:43
	 +----------------------------------------------------------
	 */
	static public function get_city_baseinfo_by_param($param){
		if (empty($param)) return null;
		$return = self::service_call('Udep014', $param);
		if ($return) $return = $return[0];
	
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据用户统一身份的Uid获取基础信息
	 +----------------------------------------------------------
	 * @author 小波 2013-6-28
	 +----------------------------------------------------------
	 */
	static public function get_baseinfo_by_uid($uc_uid){
		if (intval($uc_uid)==0) return null;
		$param['uid']=$uc_uid;
		$return = self::service_call('Uuse010', $param);
		if ($return && !empty($return[0]['uid'])) $return = $return[0];
		else $return = $return[0][0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 不定参数获取已注册用户信息<该方法内容需要WebService来支持>
	 +----------------------------------------------------------
	 * @param  array
	
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_members_by_param($map){
		if (empty($map)) return null;
		//参数中是否有禁止缓存的参数
		$cache = self::is_cache(func_get_args());
		//调用服务
		$return = self::service_call('Uuse014', $map, $cache);
		if ($return) $return = $return[0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 不定参数获取已注册用户信息<该方法内容需要WebService来支持>
	 +----------------------------------------------------------
	 * @param  array
	
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_baseinfo_by_param($map){
		if (empty($map)) return null;
		$return = self::service_call('Uuse010', $map);
		if ($return && !empty($return[0]['uid'])) $return = $return[0];
		else $return = $return[0][0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 不定参数获取未注册用户信息
	 +----------------------------------------------------------
	 * @param  array
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_off_baseinfo_by_param($map){
		if (empty($map)) return null;
		$return = self::service_call('Uuse037', $map);
		if ($return) $return = $return[0];
		//如果只有一条数据时只返回一条数据
		if (count($return)==1) {
			$return = $return[0];
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据学校ID获取该学校下已注册的老师信息
	 +----------------------------------------------------------
	 * @param  string $schoolid 身份证件号
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_members_teacher_by_schoolid($schoolid, $rowCount=0, $page = 0){
		if (intval($schoolid)==0) return null;
		$param['schoolId']=$schoolid;
		$param['page']=$page;
		if ($rowCount!=0) $param['rowCount'] = $rowCount;
		$return = self::service_call('Utch013', $param);
	
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据班级ID获取该班级所有学生
	 +----------------------------------------------------------
	 * @param  string $classid 班级id
	 * @return array
	 * @author 美美 2013-9-2
	 +----------------------------------------------------------
	 */
	static public function get_student_by_classid($classid, $rowCount=0, $page = 0){
		if (intval($classid)==0) return null;
		$param['classid']=$classid;
		$param['page']=$page;
		if ($rowCount!=0) $param['rowCount'] = $rowCount;
		$return = self::service_call('Ustu022', $param);
	
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据班级ID获取该班级所有学生
	 +----------------------------------------------------------
	 * @param  string $classid 班级id
	 * @return array
	 * @author 美美 2013-9-2
	 +----------------------------------------------------------
	 */
	static public function get_teacher_by_classid($classid, $rowCount=0, $page = 0){
		if (intval($classid)==0) return null;
		$param['classid']=$classid;
		$param['page']=$page;
		if ($rowCount!=0) $param['rowCount'] = $rowCount;
		$return = self::service_call('Utch018', $param);
	
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据学校ID获取该学校下已注册的学生信息 <该方法内容需要WebService来支持>
	 +----------------------------------------------------------
	 * @param  string $schoolid 班级ID
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_members_student_by_schoolid($schoolid, $rowCount=0, $page = 0){
		if (intval($schoolid)==0) return null;
		$param['schoolId']=$schoolid;
		$param['page']=$page;
		if ($rowCount!=0) $param['rowCount'] = $rowCount;
		$return = self::service_call('Ustu011', $param);
	
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据班级ID获取该学校班级下已注册的学生信息
	 +----------------------------------------------------------
	 * @param  string $classid 班级ID
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_members_student_by_classid($classid, $rowCount=0, $page = 0){
		if (intval($classid)==0) return null;
		$param['classId']=$classid;
		$param['page']=$page;
		if ($rowCount!=0) $param['rowCount'] = $rowCount;
		$return = self::service_call('Ustu012', $param);
	
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据学校ID获取该学校下已注册的家长信息
	 +----------------------------------------------------------
	 * @param  string $classid 班级ID
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_members_parent_by_schoolid($schoolid, $rowCount=0, $page = 0){
		if (intval($schoolid)==0) return null;
		$param['schoolId']=$schoolid;
		$param['page']=$page;
		if ($rowCount!=0) $param['rowCount'] = $rowCount;
		$return = self::service_call('Uuse011', $param);
	
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据班级ID获取该学校班级下已注册的家长信息
	 +----------------------------------------------------------
	 * @param  string $classid 班级ID
	 * @return array
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_members_parent_by_classid($classid, $rowCount=0, $page = 0){
		if (intval($classid)==0) return null;
		$param['classId']=$classid;
		$param['page']=$page;
		if ($rowCount!=0) $param['rowCount'] = $rowCount;
		$return = self::service_call('Uuse012', $param);
	
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据学校id获取学段-学校-年级-学科树型结构信息
	 +----------------------------------------------------------
	 * @param $schoolid 学校id
	 * @return $param 学段学科年级
	 * @author 小波 2013-5-31
	 +----------------------------------------------------------
	 */
	static public function get_organization_by_schoolid($schoolid){
		//参数中是否有禁止缓存的参数
		$cache = self::is_cache(func_get_args());
		//其它参数
		$param['schoolid']=$schoolid;
		$return = self::service_call('Usch016', $param, $cache);
		//返回的结果
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 教研广场用的树
	 +----------------------------------------------------------
	 * @param $schoolid 学校id
	 * @return $param 学段学科年级
	 * @author 小波 2013-5-31
	 +----------------------------------------------------------
	 */
	static public function get_organizationtree($schoolid){
		$param['schoolid']=$schoolid;
		$return = self::service_call('Usch031', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据学校id获取学校-学段-年级-班级树型结构信息
	 +----------------------------------------------------------
	 * @param $schoolid 学校id
	 * @return $param 学校学段年级班级
	 * @author 杨志明 2013-6-26
	 +----------------------------------------------------------
	 */
	static public function get_organization2_by_schoolid($schoolid){
		$param['schoolid']=$schoolid;
		$return = self::service_call('Usch002', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据学校id获取学段
	 +----------------------------------------------------------
	 * @param  string $schoolid
	 * @return array
	 * @author 小伟 2013-6-4
	 +----------------------------------------------------------
	 */
	static public function get_xd_by_school($schoolid=0){
		$return = array();
		if($schoolid!=0 && !empty($schoolid)){
			$param['schoolid']=$schoolid;
			$return = self::service_call('Usch005', $param);
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据学校id和学段获取年级
	 +----------------------------------------------------------
	 * @param string $schoolid $phase
	 * @return array
	 * @author 小伟 2013-6-4
	 +----------------------------------------------------------
	 */
	static public function get_nj_by_school_xd($schoolid,$phaseid){
		$param['schoolid']=$schoolid;
		$param['xd']=$phaseid;
		$return = self::service_call('Usch006', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据学校id,学段,年级,获取科目信息
	 +----------------------------------------------------------
	 * @param string
	 * @return array
	 * @author 小伟 2013-6-4
	 +----------------------------------------------------------
	 */
	static public function get_subject_by_school_xd_nj($schoolid,$phaseid,$gradeid){
		$param['schoolid']=$schoolid;
		$param['xd']=$phaseid;
		$param['nj']=$gradeid;
		$return = self::service_call('Usch007', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据用户id获取用户信息
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-6-4
	 +----------------------------------------------------------
	 */
	static public function get_user_by_uid($uid, $page = '', $rowCount = ''){
		$param['uid']=$uid;
		$param['page']=$page;
		$param['rowCount']=$rowCount;
		$return = self::service_call('Uuse001', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取备课组
	 +----------------------------------------------------------
	 * @param $xd 学段
	 * @param $schoolid 学校id
	 * @param $xkid 学科id array
	 * @return array|null
	 * @author 小波 2013-7-1
	 +----------------------------------------------------------
	 */
	static public function get_user_xz_group($schoolid, $xd,  $xkid=array()){
		if (empty($xd)) return null;
		$param['xd']=$xd;
		if (!empty($schoolid)) $param['schoolId']=$schoolid;
		$return = array();
		if (!empty($xkid)) {
			foreach ($xkid as $id){
				$param['xkid']=$id;
				$result = self::service_call('Usch024',$param);
				$return = array_merge($return, $result);
			}
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取与某用户相关的教研组信息
	 +----------------------------------------------------------
	 * @param $xd 学段
	 * @param $schoolid 学校id
	 * @param $xkid 学科id array
	 * @return array|null
	 * @author 小波 2013-7-1
	 +----------------------------------------------------------
	 */
	static public function get_user_jy_group($uid){
		if (empty($uid)) return null;
		$param['uid']=$uid;
		$return = self::service_call('Usch030', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	static public function get_user_bk_group($identityid){
		if (empty($identityid)) return null;
		$param['identityid']=$identityid;
		$return = self::service_call('Utch028', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取用户的权限等信息
	 +----------------------------------------------------------
	 + @param	@param unknown $uid
	 + @param	@param string $page
	 + @param	@param string $rowCount
	 + @param	@return Ambigous <Ambigous, string, multitype:, unknown, $param, boolean, void>
	 + @return	Ambigous <Ambigous, string, multitype:, unknown, $param, boolean, void>
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-12 上午10:12:32
	 +----------------------------------------------------------
	 */
	static public function get_userAppLimit_by_uid($uid, $page = '', $rowCount = ''){
		if($uid && $uc_uid = M( 'ucenter_user_link' )->where('uid='.$uid)->getField('uc_uid')) {
			$param['uid']=$uc_uid;
			$return = self::service_call('Uuse002', $param);
		} else {
			$return = array();
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 根据学校、科目、年级获取已注册
	 +----------------------------------------------------------
	 * @param $xx 学校
	 * @param $km 科目
	 * @param $nj 年级
	 * @return array uid
	 * @author 小伟 2013-6-4
	 +----------------------------------------------------------
	 */
	static public function get_TeacherUid_By_Identityid($identityid){
		$param['identityid']=$identityid;
		$return = self::service_call('Utch008', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	static public function get_teacher_by_grade($schoolid='',$xd='',$course='',$grade=''){
		$param['schoolid']=$schoolid;
		$param['xd']=$xd;
		if(!empty($grade))$param['nj']=$grade;
		$param['kmbm']=$course;
		if($schoolid=='' && $xd=='' && $course=='' && $grade==''){
			$param = array();
		}
		$return = self::service_call('Utch007', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	static public function get_school_by_id($id){
		$param['id']=$id;
		$return = self::service_call('Usch010', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	static public function get_students_by_deptid($deptId, $schoolId){
		$param['schoolid']=$schoolId;
		$param['classid']=$deptId;
		$return = self::service_call('Ustu009', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 *调用服务
	 +----------------------------------------------------------
	 * @param $service 服务号
	 * @param array $param 传递参数
	 * @return Ambigous
	 * @author 徐程亮 2013-6-8
	 +----------------------------------------------------------
	 */
	static public function get_webService($service,$param=array()){
		$return = self::service_call($service,$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 通过班级id获取级部信息
	 +----------------------------------------------------------
	 * @param $classid
	 * @return mixed
	 * @author 徐程亮
	 +----------------------------------------------------------
	 * 创建时间：2013-6-19
	 +----------------------------------------------------------
	 */
	static public function jbInfo($classid){
		$jb = self::webservice_call('Usch021',array('classid'=>(string)$classid));
		return $jb[0];
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 根据id获取行政教研组信息
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-6-14
	 +----------------------------------------------------------
	 */
	static public function get_ZZgroup_by_identityid($identityid){
		$param['identityid']=$identityid;
		$return = self::service_call('Utch012', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据id获取教研组信息
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-6-28
	 +----------------------------------------------------------
	 */
	static public function get_JYgroup_by_identityid($identityid){
		$param['identityid']=$identityid;
		$return = self::service_call('Utch017', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 获取学段科目年级
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-6-28
	 +----------------------------------------------------------
	 */
	static public function getXdSubjectGrade(){
		$param['id']='1';
		$return = self::service_call('Usch022',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 获取行政备课组
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-6-28
	 +----------------------------------------------------------
	 */
	static public function getBKgroupBySchoolidXd($schoolid,$xd){
		$param['schoolId']=$schoolid;
		$param['xd']=$xd;
		$return = self::service_call('Usch024',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 获取行政教研组
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-6-28
	 +----------------------------------------------------------
	 */
	static public function getXZgroup($schoolid,$xd){
		$arr	=	getBKgroupBySchoolidXd($schoolid,$xd);
		foreach($arr as $key => $value){
			switch ($value[xd]){
				case '21':
					$arr[$key]['xdmc']	=	'小学';
					break;
				case '31':
					$arr[$key]['xdmc']	=	'初中';
					break;
				case '34':
					$arr[$key]['xdmc']	=	'高中';
					break;
			}
		}
		function unique_arr($array2D,$stkeep=false,$ndformat=true)
		{
			// 判断是否保留一级数组键 (一级数组键可以为非数字)
			//if($stkeep) $stArr = array_keys($array2D);
			// 判断是否保留二级数组键 (所有二级数组键必须相同)
			if($ndformat) $ndArr = array_keys(end($array2D));
			//降维,也可以用implode,将一维数组转换为用逗号连接的字符串
			foreach ($array2D as $v){
				$v = join(',',$v);
				$temp[] = $v;
			}
			//去掉重复的字符串,也就是重复的一维数组
			$temp = array_unique($temp);
			//再将拆开的数组重新组装
			foreach ($temp as $k => $v)
			{
				//if($stkeep) $k = $stArr[$k];
				if($ndformat)
				{
					$tempArr = explode(",",$v);
					foreach($tempArr as $ndkey => $ndval) $output[$k][$ndArr[$ndkey]] = $ndval;
				}else
					$output[$k] = explode(",",$v);
			}
			return $output;
		}
		return unique_arr($arr);
	}
	/**
	 +----------------------------------------------------------
	 * 获取全部的科目和学段.(广场搜索用.)
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 小伟 2013-6-28
	 +----------------------------------------------------------
	 */
	static public function getAllCourseXd(){
		$param['schoolType']='';
		$param['subjectType']='';
		$return = self::service_call('Usch026',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	static public function get_leaders($schoolid,$zwlx='',$xd='',$kmbm='',$jbid=''){
		$param['schoolid']	=	$schoolid;
		if(!empty($zwlx))$param['zwlx']	=	$zwlx;
		if(!empty($xd))$param['xd']		=	$xd;
		if(!empty($kmbm))$param['kmbm']	=	$kmbm;
		if(!empty($jbid))$param['jbid']	=	$jbid;
		$return = self::service_call('Utch006',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	
	}
	
	static public function get_subject_by_id($id){
		$param['kmbm']	=	$id;
		$return = self::service_call('Usch018',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 * @Title: get_class_by_teacher_uid
	 * @Description: 根据ucenter里面的老师uid得到老师教的相关班级
	 * @param @param Ucenter->members->uid  $uid
	 * @author RickerYu rickeryu@gridinfo.com.cn
	 */
	static public function get_class_by_teacher_uid($uid,$page='',$rowCount=''){
		$param['uid']=$uid;
		!empty($page) ? $param['page'] = $page : null;
		!empty($page) ? $param['rowCount'] = $rowCount : null;
		$return = self::service_call('Utch009',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 * @Title: get_class_by_student_uid
	 * @Description: 根据ucenter里面的学生uid得到学生相关班级
	 * @param @param Ucenter->members->uid  $uid
	 * @author RickerYu rickeryu@gridinfo.com.cn
	 */
	static public function get_class_by_student_uid($uid,$page='',$rowCount=''){
		$param['uid']=$uid;
		!empty($page) ? $param['page'] = $page : null;
		!empty($page) ? $param['rowCount'] = $rowCount : null;
		$return = self::service_call('Ustu013',$param);
		foreach ($return as $key=>$val){
			$return[$key]['classid']	= $val['id'];
			$return[$key]['classname']  = $val['bj'];
			$return[$key]['xd']			= $val['xd'];
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 * @Title: get_class_by_parent_uid
	 * @Description: 根据ucenter里面的家长 uid得到家长的孩子的相关班级
	 * @param @param Ucenter->members->uid  $uid
	 * @author RickerYu rickeryu@gridinfo.com.cn
	 */
	static public function get_class_by_parent_uid($uid,$page='',$rowCount=''){
		if (intval($uid)==0) return null;
		$param['uid']=$uid;
		!empty($page) ? $param['page'] = $page : null;
		!empty($page) ? $param['rowCount'] = $rowCount : null;
		$return = self::service_call('Ustu014',$param);
		foreach ($return as $key=>$val){
			$return[$key]['classid']  = $val['id'];
			$return[$key]['classname']  = $val['bj'];
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	
	/**
	 * @Title: get_school_by_uid
	 * @Description:  uc_uid 得到学校的基本信息
	 * @param @param Ucenter->members->uid  $uc_uid
	 * @author RickerYu rickeryu@gridinfo.com.cn
	 */
	static public function get_school_by_uid($uc_uid){
		if(intval($uc_uid)==0) return null;
		$param['uid']=$uc_uid;
		$return = self::service_call('Usch028',$param);
		$return = $return[0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 获取所有学科
	 +----------------------------------------------------------
	 * @return return_type array
	 * @author 小伟 2013-7-9
	 +----------------------------------------------------------
	 */
	static public function getAllCourse($id){
		$param['kmbm']	=	$id;
		$return = self::service_call('Usch029',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据家长的identityid获取孩子的uid
	 +----------------------------------------------------------
	 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 朱长奇 2013-8-18
	 +----------------------------------------------------------
	 */
	static public function getChildUid_by_identityid($identityid){
		$param['identityid']=$identityid;
		$return = self::service_call('Ustu003',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 根据老师所在年级获取该年级主任的uc_uid
	 +----------------------------------------------------------
	 * @param $identityid
	 * @return $uc_uid
	 * @author 杨小伟
	 +----------------------------------------------------------
	 */
	static public function getMasterUid_by_identityid($identityid){
		$param['identityid']=$identityid;
		$return = self::service_call('Utch029',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 获取所有学校
	 +----------------------------------------------------------
	 * @return $uc_uid
	 * @author 杨小伟
	 +----------------------------------------------------------
	 */
	static public function getAllSchool(){
		$param['identityid']='';
		$return = self::service_call('Usch025',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 * 获取所有学校
	 +----------------------------------------------------------
	 * @param $schoolid
	 * @return $uc_uid
	 * @author 杨小伟
	 +----------------------------------------------------------
	 */
	static public function getAllClassBySchoolid($schoolid){
		$param['schoolid']=$schoolid;
		$return = self::service_call('Usch042',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 *
	 +----------------------------------------------------------
	 + 根据班级和月份获取寿星
	 +----------------------------------------------------------
	 + @param	@param unknown $classid
	 + @param	@param unknown $month
	 + @author	李洋 (admininstrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-8-30 下午3:48:48
	 +----------------------------------------------------------
	 */
	static public function getBirthdayByClassId($classid,$month){
		$param['classid'] = $classid;
		$param['month'] = $month;
		$return = self::service_call('Ustu017', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 + 获取教研相关用户
	 +----------------------------------------------------------
	 + @param	@param unknown $uid
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-9-3 下午3:02:57
	 +----------------------------------------------------------
	 */
	static public function get_user_teaching_byuid($uid){
		$param['uid']=$uid;
		$return = self::service_call('Utch031',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 + 获取我的学生
	 +----------------------------------------------------------
	 + @param	@param unknown $uid
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-9-3 下午3:02:57
	 +----------------------------------------------------------
	 */
	static public function get_user_student_bytuid($uid){
		$param['uid']=$uid;
		$return = self::service_call('Utch030',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 + 获取学校领导
	 +----------------------------------------------------------
	 + @param	@param unknown $uid
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-9-3 下午3:02:57
	 +----------------------------------------------------------
	 */
	static public function get_user_lead_byschoolid($sid){
		$param['schoolid']=$sid;
		$return = self::service_call('Utch032',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 + 获取同班同学
	 +----------------------------------------------------------
	 + @param	@param unknown $uid
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-9-3 下午3:02:57
	 +----------------------------------------------------------
	 */
	static public function get_user_student_byidentityid($identityid){
		$param['identityid']=$identityid;
		$return = self::service_call('Ustu023',$param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取班主任信息
	 +----------------------------------------------------------
	 * @param unknown $id
	 * @return Ambigous <mixed, string, multitype:, unknown>
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-4-27 上午9:39:34
	 +----------------------------------------------------------
	 */
	static public function get_classadviser_by_classid($classid){
		$param['classid'] = $classid;
		$return = self::service_call('Utch027', $param);
		$return = $return[0]['uid'];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 通过老师ID获取相关的任课信息(去重复列)
	 +----------------------------------------------------------
	 * @param intval $identityid 老师identityid
	 * @return array
	 * @author 小朱
	 +----------------------------------------------------------
	 * 创建时间：2013-3-26 上午9:59:45
	 +----------------------------------------------------------
	 */
	static public function get_course_by_identityid($identityid){
		$param['identityid']=$identityid;
		$return = self::service_call('Utch021', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据老师identityid和课程courseid获取老师代课班级列表
	 +----------------------------------------------------------
	 * @param	int $courseid 课程id
	 * @param	int $identityid 老师identityid
	 * @return	array
	 * @author	小朱 2013-3-30
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-30 下午03:55:16
	 +----------------------------------------------------------
	 */
	static public function get_classids_by_courseid($courseid,$identityid){
		$param['identityid']=$identityid;
		$param['xkid']=$courseid;
		$return = self::service_call('Utch022', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据班级id获取班级内学生信息
	 +----------------------------------------------------------
	 * @param	int $classid 班级id
	 * @author	小朱 2013-4-2
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-2 下午04:59:13
	 +----------------------------------------------------------
	 */
	static public function uc_student_get_id($classid){
		$param['classid'] = intval($classid);
		$return = self::service_call('Ustu004', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 + 根据学校ID获取学校下的所有部门
	 +----------------------------------------------------------
	 + @param	@param unknown $schoolid
	 + @return	Ambigous <Ambigous, string, multitype:, unknown, $param, boolean, void>
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-25 下午4:35:36
	 +----------------------------------------------------------
	 */
	static public function get_detptree_by_schoolid($schoolid){
		$param['schoolid']=intval($schoolid);
		$return = self::service_call('Udep005', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 + 根据用户UC schoolid获取用户所在学校的组织结构
	 +----------------------------------------------------------
	 + @param	@param unknown $schoolid
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-25 下午2:33:06
	 +----------------------------------------------------------
	 */
	static public function get_deptinfo_by_schoolid($schoolid){
		$param['schoolid']=intval($schoolid);
		$return = self::service_call('Utch033', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取本社区的地市信息
	 +----------------------------------------------------------
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-28 下午5:08:32
	 +----------------------------------------------------------
	 */
	static public function get_select_district(){
		$param = array();
		$return = self::service_call('Udep006', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取本社区的区县信息
	 +----------------------------------------------------------
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-28 下午5:08:32
	 +----------------------------------------------------------
	 */
	static public function get_select_city_by_param($sjid){
		if (intval($sjid)==0) {
			return null;
		}
		$param['sjid'] = intval($sjid);
		$return = self::service_call('Udep009', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取本社区的区县信息
	 +----------------------------------------------------------
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-28 下午5:08:32
	 +----------------------------------------------------------
	 */
	static public function get_select_school_by_param($qxid){
		if (intval($qxid)==0) {
			return null;
		}
		$param['xjid'] = intval($qxid);
		$return = self::service_call('Udep012', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取本社区的某学校学段信息
	 +----------------------------------------------------------
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-28 下午5:08:32
	 +----------------------------------------------------------
	 */
	static public function get_select_xd_by_param($schoolid){
		if (intval($schoolid)==0) {
			return null;
		}
		$param['schoolid'] = intval($schoolid);
		$return = self::service_call('Usch043', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取本社区的某学校班级信息
	 +----------------------------------------------------------
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-28 下午5:08:32
	 +----------------------------------------------------------
	 */
	static public function get_select_classes_by_param($schoolid=0,$xd=0,$nj=0){
		if (intval($schoolid)==0 || intval($xd)==0 || intval($nj)==0) {
			return null;
		}
		$param['sid'] = intval($schoolid);
		$param['xd'] = intval($xd);
		$param['nj'] = intval($nj);
		$return = self::service_call('Usch035', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取学校ID获取学校部门信息
	 +----------------------------------------------------------
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-28 下午5:08:32
	 +----------------------------------------------------------
	 */
	static public function get_select_dept_by_param($schoolid=0){
		if (intval($schoolid)==0) {
			return null;
		}
		$param['xxid'] = intval($schoolid);
		$return = self::service_call('Udep016', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取相关科目信息
	 +----------------------------------------------------------
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-28 下午5:08:32
	 +----------------------------------------------------------
	 */
	static public function get_select_courses_by_param($classid=0){
		if (intval($classid)==0) {
			return null;
		}
		$param['classid'] = intval($classid);
		$return = self::service_call('Subj001', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取市级下面部门信息
	 +----------------------------------------------------------
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-28 下午5:08:32
	 +----------------------------------------------------------
	 */
	static public function get_select_sjdept_by_param($sjid=0){
		if (intval($sjid)==0) {
			return null;
		}
		$param['sjid'] = intval($sjid);
		$return = self::service_call('Udep018', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取市级下面部门信息
	 +----------------------------------------------------------
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-28 下午5:08:32
	 +----------------------------------------------------------
	 */
	static public function get_select_xjdept_by_param($xjid=0){
		if (intval($xjid)==0) {
			return null;
		}
		$param['xjid'] = intval($xjid);
		$return = self::service_call('Udep017', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 根据用户UC teacherid获取老师下的所有学生
	 +----------------------------------------------------------
	 + @param	@param unknown $uid
	 + @return	return_type
	 + @author	曹飞 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-28 下午13：38：30
	 +----------------------------------------------------------
	 */
	static public function get_student_by_teacheruid($uid)
	{
		$param['uid']=intval($uid);
		$return = self::service_call('Utch034', $param, 300);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 根据用户UC teacherid获取老师所在教研组同事
	 +----------------------------------------------------------
	 + @param	@param unknown $uid
	 + @return	return_type
	 + @author	曹飞 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-30 下午15：38：30
	 +----------------------------------------------------------
	 */
	static public function get_worker_by_teacheruid($uid)
	{
		$param['uid']=intval($uid);
		$return = self::service_call('Utch036', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 根据用户UC 家长uid获取同班老师信息
	 +----------------------------------------------------------
	 + @param	@param unknown $uid
	 + @return	return_type
	 + @author	曹飞 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-30 下午15：45：55
	 +----------------------------------------------------------
	 */
	static public function get_teacher_by_parentuid($uid)
	{
		$param['uid']=intval($uid);
		$return = self::service_call('Ustu031', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 根据用户UC 家长uid获取同班其他家长信息
	 +----------------------------------------------------------
	 + @param	@param unknown $uid
	 + @return	return_type
	 + @author	曹飞 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-30 下午15：47：25
	 +----------------------------------------------------------
	 */
	static public function get_othersparent_by_parentuid($uid)
	{
		$param['uid']=intval($uid);
		$return = self::service_call('Ustu030', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 根据用户UC 学生uid获取同班老师信息
	 +----------------------------------------------------------
	 + @param	@param unknown $uid
	 + @return	return_type
	 + @author	曹飞 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-30 下午15：48：58
	 +----------------------------------------------------------
	 */
	static public function get_teacher_by_studentuid($uid)
	{
		$param['uid']=intval($uid);
		$return = self::service_call('Ustu029', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 根据用户UC 学生uid获取同班同学信息
	 +----------------------------------------------------------
	 + @param	@param unknown $uid
	 + @return	return_type
	 + @author	曹飞 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-30 下午15：50：10
	 +----------------------------------------------------------
	 */
	static public function get_classmates_by_studentuid($uid)
	{
		$param['uid']=intval($uid);
		$return = self::service_call('Ustu028', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取学生的名片相关信息
	 +----------------------------------------------------------
	 + @param
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-5 下午1:27:24
	 +----------------------------------------------------------
	 */
	static public function get_student_spacecard_by_uid($uid){
		$param['uid']=intval($uid);
		$return = self::service_call('Ustu001', $param);
		if ($return) $return = $return[0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取家长的名片相关信息
	 +----------------------------------------------------------
	 + @param
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-5 下午1:27:24
	 +----------------------------------------------------------
	 */
	static public function get_parent_spacecard_by_uid($uid){
		$param['uid']=intval($uid);
		$return = self::service_call('Ustu027', $param);
		if ($return) $return = $return[0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取老师的名片相关信息
	 +----------------------------------------------------------
	 + @param
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-5 下午1:27:24
	 +----------------------------------------------------------
	 */
	static public function get_teacher_spacecard_by_uid($uid){
		$param['uid']=intval($uid);
		$return = self::service_call('Utch035', $param);
		if ($return) $return = $return[0];
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 * +----------------------------------------------------------
	 * + 根据老师identityid和学校ID获取老师课程表
	 * +----------------------------------------------------------
	 * + @param
	 * + @return	return_type
	 * + @author	曹飞
	 * +----------------------------------------------------------
	 * + 创建时间：	上午9:32:39
	 * +----------------------------------------------------------
	 */
	static public function get_teacherCourse_by_identityid($schoolid ,$identityid ,$identitytype)
	{
		$return = array();
		if (intval($schoolid)) {
			$param['schoolid'] = intval($schoolid);
		}
		if (intval($identityid)) {
			$param['identityid'] = intval($identityid);
		}
		if (intval($identitytype)) {
			$param['identitytype'] = intval($identitytype);
		}
		if (count($param)) {
			$return= self::service_call("Subj002",$param);
		}
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 +----------------------------------------------------------
	 + 获取该老师所担任班主任的班级列表
	 +----------------------------------------------------------
	 + @param	@param unknown $identityid
	 + @return	NULL|Ambigous <Ambigous, string, multitype:, unknown, $param, boolean, void>
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-8 上午10:20:55
	 +----------------------------------------------------------
	 */
	static public function get_dutyclasslist_by_identityid($identityid){
		if (intval($identityid)==0) return null;
		$param['identityid']=$identityid;
		$return = self::service_call('Usch044', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	/**
	 * +----------------------------------------------------------
	 * + 获取所以班级信息
	 * +----------------------------------------------------------
	 * + @param
	 * + @return	return_type
	 * + @author	曹飞
	 * +----------------------------------------------------------
	 * + 创建时间：	下午3:04:50
	 * +----------------------------------------------------------
	 */
	static public function getAllClass(){
		$return = self::service_call('Usch045');
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 通过学校ID获取用户列表
	 +----------------------------------------------------------
	 * @param integer $sid 学校ID
	 * @return array
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-15 下午5:03:41
	 +----------------------------------------------------------
	 */
	static public function get_userlist_by_sid($sid){
		$param['schoolid']=$sid;
		$return = self::service_call('Usch040', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
	
	/**
	 +----------------------------------------------------------
	 * 通过班级ID获取用户列表
	 +----------------------------------------------------------
	 * @param integer $sid 学校ID
	 * @return array
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-16 下午5:03:41
	 +----------------------------------------------------------
	 */
	static public function get_userlist_by_cid($cid){
		$param['classid']=$cid;
		$return = self::service_call('Ustu020', $param);
		return UC_CONNECT == 'mysql' ? $return : self::uia_unserialize($return);
	}
}
?>