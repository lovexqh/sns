<?php
/**
 +------------------------------------------------------------------------------
 * Ucenter Service 应用类
 +------------------------------------------------------------------------------
 * @category	API Services
 * @author		小波 <xiaobosun@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-5-29 下午5:18:13
 +------------------------------------------------------------------------------
 */

/**
 +----------------------------------------------------------
 * 获取serivce的返回结果
 +----------------------------------------------------------
 * @param $method 服务名称
 * @return $param 服务的参数列表
 * @author 小波 2013-5-31
 +----------------------------------------------------------
 */
function service_call($method, $param){
	
	//读取缓存数据 孙晓波于2013-8-2添加
	$cache_key = $method . "_" . array_to_string($param);
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
			show_service_error("WebService 远程服务器已经挂起！请稍后再试。");
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
	$data =array('service'=>$method, 'param'=> json_encode($param));
	try {
		$result = $soap->__soapCall("getData",
				array('getData' => $data),
				NULL,
				NULL);
	} catch (Exception $e) {
		show_service_error(" 访问 WebService 方法时失败！");
	}
	
	//Object to array 功能
	foreach($result as $key =>$value){
		if(gettype($value) == 'array' || gettype($value) == 'object'){
			$data[$key] = $this->objToArr($value);
		}
		else{
			$data[$key] = $value;
		}
	}
	$result = arrayKeyToLower(json_decode($data['return'],true));
	if (count($result['code'])>1 && $result['code']['error_code']!=0) { //出错提示
		show_service_error($method.' '.$result['code']['error_message']);
	}else{
		//生成缓存
		S($cache_key, $result['data'], 600);
		return $result['data'];
	}
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
function array_to_string($array){
	if (count($array)>0) {
		$param = "";
		//将数组转换为字符串
		foreach ($array as $k=>$v){
			if (gettype($v)=='array') {
				$param .= $k . array_to_string($v);
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
function show_service_error($error){
	$html = '<div id="serviceStatus" style="position:absolute; z-index:999; top:0; width:100%; text-align:center; height:30px; line-height:30px; background-color:#FF0">
						<a href="javascript:;" onclick="document.getElementById(\'serviceStatus\').style.display=\'none\'" class="close" style="float:right; margin-right:15px; display:inline; font-family:Verdana, Geneva, sans-serif; font-size:150%">X</a>
						'.$error.'
					  </div>';
	//echo $html;
	redirect(U('home/Public/error500', array('msg'=>urlencode($error))));
	return;
}

/**
 +----------------------------------------------------------
 * 通过sql语句获取结果数据 (测试方法)
 +----------------------------------------------------------
 * @author 小波 2013-6-26
 +----------------------------------------------------------
 */
function db(){
	require_once UC_ROOT.'lib/db.class.php';
	$db = new ucclient_db();
	$db->connect(UC_DBHOST, UC_DBUSER, UC_DBPW, '', UC_DBCHARSET, UC_DBCONNECT, UC_DBTABLEPRE);
	return $db;
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
function get_teacher_baseinfo_by_sfzjh($sfzjh){
	if (empty($sfzjh)) return null;
	$param['sfzjh']=$sfzjh;
	$return = service_call('Utch026', $param);
	if ($return) $return = $return[0];
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}


/**
 +----------------------------------------------------------
 * 根据学生身份证号获取学生基础信息
 +----------------------------------------------------------
 * @param  string $sfzjh 身份证件号
 * @return array
 * @author 小波 2013-6-26
 +----------------------------------------------------------
 */
function get_student_baseinfo_by_sfzjh($sfzjh){
	if (empty($sfzjh)) return null;
	$param['sfzjh']=$sfzjh;
	//webservice 还没有写
// 	$return = service_call('Utch026', $param);
	if ($return) $return = $return[0];
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_teacher_baseinfo_by_xm($xm){
	if (empty($xm)) return null;
	$param['xm']=$xm;
	$return = service_call('Utch026', $param);
	if ($return) $return = $return[0];
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_student_baseinfo_by_xm($xm){
	if(empty($xm)) return null;
	$param['xm']=$xm;
	$return = service_call('Ustu010', $param);
	if ($return) $return = $return[0];
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_teacher_baseinfo_by_identityid($identityid){
	if (empty($identityid)) return null;
	$param['identityid']=$identityid;
	$return = service_call('Utch026', $param);
	if ($return) $return = $return[0];
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_teacher_baseinfo_by_param($map){
	$return = service_call('Utch026', $map);
	if ($return) $return = $return[0];
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * 根据老师的UID得到老师的部门信息
 */
function get_teacher_dept_by_uid($uid){
	if (empty($uid)) return null;
	$param['uid']=$uid;
	$return = service_call('Utch014', $param);
	if ($return) $return = $return[0];
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_student_baseinfo_by_identityid($identityid){
	if (intval($identityid) == 0) return array();
	$param['identityId']=$identityid;
	$return = service_call('Ustu010', $param);
	if ($return) $return = $return[0];
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_student_baseinfo_by_xh($xh){
	if (empty($xh)) return null;
	$param['xh']=$xh;
	$return = service_call('Ustu010', $param);
	if ($return) $return = $return[0];
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_student_baseinfo_by_param($param, $parentxm=''){
	if (empty($param)) return null;
	if (empty($parentxm)) { //如果仅查询学生信息时
		$return = service_call('Ustu010', $param);
	}else {	//如果根据学生信息及家长姓名查询时
		$param['parentxm'] = $parentxm;
		$return = service_call('Ustu007', $param);
	}
	if ($return) $return = $return[0];
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_parent_baseinfo_by_xm($xm){
	if (empty($xm)) return null;
	$param['xm']=$xm;
	$return = service_call('Uuse013', $param);
	if ($return) $return = $return[0];
	
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_parent_baseinfo_by_identityid($identityid){
	if (empty($identityid)) return null;
	$param['identityid']=$identityid;
	$return = service_call('Uuse013', $param);
	if ($return) $return = $return[0];

	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * 不定参数获取家长基础信息
 +----------------------------------------------------------
 * @author 小波 2013-6-28
 +----------------------------------------------------------
 */
function get_parent_baseinfo_by_param($param){
	if (empty($param)) return null;
	$return = service_call('Uuse013', $param);
	if ($return) $return = $return[0];
	
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 根据用户统一身份的Uid获取基础信息
 +----------------------------------------------------------
 * @author 小波 2013-6-28
 +----------------------------------------------------------
 */
function get_baseinfo_by_uid($uc_uid){
	if (intval($uc_uid)==0) return null;
	$param['uid']=$uc_uid;
	$return = service_call('Uuse010', $param);
	if ($return) $return = $return[0][0];
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_members_by_param($map){
	if (empty($map)) return null;
	$return = service_call('Uuse014', $map);
	if ($return) $return = $return[0];
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_members_teacher_by_schoolid($schoolid, $rowCount=0, $page = 0){
	if (intval($schoolid)==0) return null;
	$param['schoolId']=$schoolid;
	$param['page']=$page;
	if ($rowCount!=0) $param['rowCount'] = $rowCount;
	$return = service_call('Utch013', $param);
	
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_members_student_by_schoolid($schoolid, $rowCount=0, $page = 0){
	if (intval($schoolid)==0) return null;
	$param['schoolId']=$schoolid;
	$param['page']=$page;
	if ($rowCount!=0) $param['rowCount'] = $rowCount;
	$return = service_call('Ustu011', $param);
	
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_members_student_by_classid($classid, $rowCount=0, $page = 0){
	if (intval($classid)==0) return null;
	$param['classId']=$classid;
	$param['page']=$page;
	if ($rowCount!=0) $param['rowCount'] = $rowCount;
	$return = service_call('Ustu012', $param);
	
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_members_parent_by_schoolid($schoolid, $rowCount=0, $page = 0){
	if (intval($schoolid)==0) return null;
	$param['schoolId']=$schoolid;
	$param['page']=$page;
	if ($rowCount!=0) $param['rowCount'] = $rowCount;
	$return = service_call('Uuse011', $param);
	
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_members_parent_by_classid($classid, $rowCount=0, $page = 0){
	if (intval($classid)==0) return null;
	$param['classId']=$classid;
	$param['page']=$page;
	if ($rowCount!=0) $param['rowCount'] = $rowCount;
	$return = service_call('Uuse012', $param);
	
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_organization_by_schoolid($schoolid){
	$param['schoolid']=$schoolid;
	$return = service_call('Usch016', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_organization2_by_schoolid($schoolid){
	$param['schoolid']=$schoolid;
	$return = service_call('Usch002', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_xd_by_school($schoolid){
	$param['schoolid']=$schoolid;
	$return = service_call('Usch005', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_nj_by_school_xd($schoolid,$phaseid){
	$param['schoolid']=$schoolid;
	$param['xd']=$phaseid;	
	$return = service_call('Usch006', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_subject_by_school_xd_nj($schoolid,$phaseid,$gradeid){
	$param['schoolid']=$schoolid;
	$param['xd']=$phaseid;
	$param['nj']=$gradeid;	
	$return = service_call('Usch007', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_user_by_uid($uid, $page = '', $rowCount = ''){
	$param['uid']=$uid;
	$param['page']=$page;
	$param['rowCount']=$rowCount;
	$return = service_call('Uuse001', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 +----------------------------------------------------------
 * Enter description here ... （方法功能的注释）
 +----------------------------------------------------------
 * @param $xd 学段
 * @param $schoolid 学校id
 * @param $xkid 学科id array
 * @return array|null
 * @author 小波 2013-7-1
 +----------------------------------------------------------
 */
function get_user_xz_group($schoolid, $xd,  $xkid=array()){
	if (empty($xd)) return null;
	$param['xd']=$xd;
	if (!empty($schoolid)) $param['schoolId']=$schoolid;
	if (!empty($xkid)) $param['xkid']=$xkid;
	$return = service_call('Usch024',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

function get_userAppLimit_by_uid($uid, $page = '', $rowCount = ''){
	if($uid && $uc_uid = M( 'ucenter_user_link' )->where('uid='.$uid)->getField('uc_uid')) {
		$param['uid']=$uc_uid;
		$return = service_call('Uuse002', $param);
	} else {
		$return = array();
	}
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_TeacherUid_By_Identityid($identityid){
	$param['identityid']=$identityid;
	$return = service_call('Utch008', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

function get_teacher_by_grade($schoolid='',$xd='',$course='',$grade=''){
	$param['schoolid']=$schoolid;
	$param['xd']=$xd;
	if(!empty($grade))$param['nj']=$grade;
	$param['kmbm']=$course;
	if($schoolid=='' && $xd=='' && $course=='' && $grade==''){
		$param = array();
	}
	$return = service_call('Utch007', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

function get_school_by_id($id){
	$param['id']=$id;
	$return = service_call('Usch010', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

function get_students_by_deptid($deptId, $schoolId){
	$param['schoolid']=$schoolId;
	$param['classid']=$deptId;
	$return = service_call('Ustu009', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function get_webService($service,$param=array()){
    $return = service_call($service,$param);
    return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function jbInfo($classid){
    $jb = get_webService('Usch021',array('classid'=>(string)$classid));
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
function get_ZZgroup_by_identityid($identityid){
	$param['identityid']=$identityid;
	$return = service_call('Utch012', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 根据id获取备课组信息
 +----------------------------------------------------------
 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return return_type <返回类型(void的方法就不用该选项)>
 * @author 小伟 2013-6-28
 +----------------------------------------------------------
 */
function get_BKgroup_by_identityid($identityid){
	$param['identityid']=$identityid;
	$return = service_call('Utch011', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
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
function getXdSubjectGrade(){
	$param['id']='1';
	$return = service_call('Usch022',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);	
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
function getBKgroupBySchoolidXd($schoolid,$xd){
	$param['schoolId']=$schoolid;
	$param['xd']=$xd;
	$return = service_call('Usch024',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);	
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
function getXZgroup($schoolid,$xd){
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
function getAllCourseXd(){
	$param['schoolType']='';
	$param['subjectType']='';
	$return = service_call('Usch026',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);		
}

function get_leaders($schoolid,$zwlx='',$xd='',$kmbm='',$jbid=''){
	$param['schoolid']	=	$schoolid;
	if(!empty($zwlx))$param['zwlx']	=	$zwlx;
	if(!empty($xd))$param['xd']		=	$xd;
	if(!empty($kmbm))$param['kmbm']	=	$kmbm;
	if(!empty($jbid))$param['jbid']	=	$jbid;
	$return = service_call('Utch006',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);	
	
}

function get_subject_by_id($id){
	$param['kmbm']	=	$id;
	$return = service_call('Usch018',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);	
}

/**
* @Title: get_class_by_teacher_uid
* @Description: 根据ucenter里面的老师uid得到老师教的相关班级
* @param @param Ucenter->members->uid  $uid
* @author RickerYu rickeryu@gridinfo.com.cn
 */
function get_class_by_teacher_uid($uid,$page='',$rowCount=''){
	$param['uid']=$uid;
	!empty($page) ? $param['page'] = $page : null;
	!empty($page) ? $param['rowCount'] = $rowCount : null;
	$return = service_call('Utch009',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 * @Title: get_class_by_student_uid
 * @Description: 根据ucenter里面的学生uid得到学生相关班级
 * @param @param Ucenter->members->uid  $uid
 * @author RickerYu rickeryu@gridinfo.com.cn
 */
function get_class_by_student_uid($uid,$page='',$rowCount=''){
	$param['uid']=$uid;
	!empty($page) ? $param['page'] = $page : null;
	!empty($page) ? $param['rowCount'] = $rowCount : null;
	$return = service_call('Ustu013',$param);
	foreach ($return as $key=>$val){
		$return[$key]['classid']  = $val['id'];
		$return[$key]['classname']  = $val['bj'];
	}
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 * @Title: get_class_by_parent_uid
 * @Description: 根据ucenter里面的家长 uid得到家长的孩子的相关班级
 * @param @param Ucenter->members->uid  $uid
 * @author RickerYu rickeryu@gridinfo.com.cn
 */
function get_class_by_parent_uid($uid,$page='',$rowCount=''){
	if (intval($uid)==0) return null;
	$param['uid']=$uid;
	!empty($page) ? $param['page'] = $page : null;
	!empty($page) ? $param['rowCount'] = $rowCount : null;
	$return = service_call('Ustu014',$param);
	foreach ($return as $key=>$val){
		$return[$key]['classid']  = $val['id'];
		$return[$key]['classname']  = $val['bj'];
	}
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}


/**
 * @Title: get_school_by_uid
 * @Description:  uid(社区users表的uid)得到学校的基本信息
 * @param @param Ucenter->members->uid  $uid
 * @author RickerYu rickeryu@gridinfo.com.cn
 */
function get_school_by_uid($uid){
	$uc_uid = D('ucenter_user_link')->field('uc_uid')->where('uid='.$uid)->find();
	$param['uid']=$uc_uid['uc_uid'];
	$return = service_call('Usch028',$param);
	$return = $return[0];	
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 +----------------------------------------------------------
 * 获取所有学科
 +----------------------------------------------------------
 * @return return_type array
 * @author 小伟 2013-7-9
 +----------------------------------------------------------
 */
function getAllCourse($id){
	$param['kmbm']	=	$id;
	$return = service_call('Usch029',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * @Title: get_teacher_or_student_by_sfzh
 * @Description: 根据身份证号得到老师或学生的基本信息，如果members表里面没有 记录，只返回 identityid和xm，也用于判断当前身份证号可不可以注册
 * @param @param unknown $sfzh    设定文件
 * @return return_type
 * @author RickerYu rickeryu@gridinfo.com.cn
 */
function get_all_by_sfzh($sfzh){
	$param['uno']=$sfzh;
	$return = service_call('Uuse022',$param);
	$return = $return[0];
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 * @description 根据老师的uid返回老师的教课信息
 * @param int $uid - 老师在uia里面的uid
 * @return 科目信息数组
 */
function get_course_by_uid($uc_uid){
	$param['uid']=$uc_uid;
	$return = service_call('Utch027',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 * @description 根据学生的uid返回学生的选课信息
 * @param int $uid - 学生在uia里面的uid
 * @return 选择科目信息数组
 */
function get_student_course_by_uid($uc_uid){
	$param['uid']=$uc_uid;
	$return = service_call('Ustu018',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * @description 根据学生的identityid返回学生信息及院系专业名称
 * @param int $identityid
 * @return 学生学号姓名以及所在院系和专业
 */
function get_studentinfo_by_identityid($identityid){
	$param['identityid']=$identityid;
	$return = service_call('Ustu019',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * RickerYu add 2013-10-17 10:15
 * 我的同班同学
 */
function get_student_same_class_student($uid,$pagenum = 1,$pagesize=20,$isreg = ''){
	$param['uid']=$uid;
	$param['page']=$pagenum;
	$param['rowCount']=$pagesize;
	if($isreg != ''){
		$param['isReg']=$isreg;
	}

	$return = service_call('Ustu020',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}


/**
 * RickerYu add 2013-10-17 10:15
 * 我的同系同学
 */
function get_student_same_subject($uid,$pagenum = 1,$pagesize=20,$isreg = ''){
	$param['uid']=$uid;
	$param['page']=$pagenum;
	$param['rowCount']=$pagesize;
	if($isreg != ''){
		$param['isReg']=$isreg;
	}

	$return = service_call('Ustu021',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * RickerYu add 2013-10-17 10:15
 * 我的教课老师
 */
function get_student_same_class_teacher($uid,$pagenum = 1,$pagesize=20,$isreg = ''){
	$param['uid']=$uid;
	$param['page']=$pagenum;
	$param['rowCount']=$pagesize;
	if($isreg != ''){
		$param['isReg']=$isreg;
	}

	$return = service_call('Ustu022',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}


/**
 * RickerYu add 2013-10-17 10:15
 * 一起上课同学
 */
function get_student_same_class($uid,$pagenum = 1,$pagesize=20,$isreg = ''){
	$param['uid']=$uid;
	$param['page']=$pagenum;
	$param['rowCount']=$pagesize;
	if($isreg != ''){
		$param['isReg']=$isreg;
	}

	$return = service_call('Ustu023',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}


/**
 * RickerYu add 2013-10-17 10:15
 * 根据关键字查询学生
 */
function get_student_same_keyword($keyword,$pagenum = 1,$pagesize=20,$isreg = ''){
	$param['keyword']=$keyword;
	$param['page']=$pagenum;
	$param['rowCount']=$pagesize;
	if($isreg != ''){
		$param['isReg']=$isreg;
	}

	$return = service_call('Ustu024',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}


/**
 * RickerYu add 2013-10-17 10:15
 * 同部门的同事
 */
function get_teacher_same_dept($uid,$pagenum = 1,$pagesize=20,$isreg = ''){
	$param['uid']=$uid;
	$param['page']=$pagenum;
	$param['rowCount']=$pagesize;
	if($isreg != ''){
		$param['isReg']=$isreg;
	}

	$return = service_call('Utch028',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * RickerYu add 2013-10-17 10:15
 * 我的教课学生
 */
function get_teacher_same_class_student($uid,$pagenum = 1,$pagesize=20,$isreg = ''){
	$param['uid']=$uid;
	$param['page']=$pagenum;
	$param['rowCount']=$pagesize;
	if($isreg != ''){
		$param['isReg']=$isreg;
	}

	$return = service_call('Utch029',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * RickerYu add 2013-10-17 10:15
 * 教同科目老师
 */
function get_teacher_same_subject($uid,$pagenum = 1,$pagesize=20,$isreg = ''){
	$param['uid']=$uid;
	$param['page']=$pagenum;
	$param['rowCount']=$pagesize;
	if($isreg != ''){
		$param['isReg']=$isreg;
	}
	
	$return = service_call('Utch030',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * RickerYu add 2013-10-17 10:15
 * 搜索老师
 */
function get_teacher_same_keyword($keyword,$pagenum = 1,$pagesize=20,$isreg = ''){
	$param['keyword']=$keyword;
	$param['page']=$pagenum;
	$param['rowCount']=$pagesize;
	if($isreg != ''){
		$param['isReg']=$isreg;
	}

	$return = service_call('Utch031',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * 
 +----------------------------------------------------------
 * Enter description here ... （得到学生的课程表信息）
 +----------------------------------------------------------
 * @return return_type <数组>
 * @author RickerYu 2013-11-11
 +----------------------------------------------------------
 */
function get_student_course($uc_uid,$xnd,$kkxq){
	$param['uid'] = $uc_uid;
	$param['xnd'] = $xnd;
	$param['kkxq'] = $kkxq;
	$return = service_call('Ustu025',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 * 
 +----------------------------------------------------------
 * Enter description here ... （从uia里面得到当前教学楼可以用的自习室）
 +----------------------------------------------------------
 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return return_type <返回类型(void的方法就不用该选项)>
 * @author RickerYu 2013-11-12
 +----------------------------------------------------------
 */
function get_classroom_from_uia($xs,$skxq,$jxlh,$skjc){
	
	
	$param['xs'] = $xs;
	$param['skxq'] = $skxq;
	if($skjc != ''){
		$param['skjc'] = $skjc;
	}
	if($jxlh != ''){
		$param['jxlh'] = $jxlh;
	}
	$return = service_call('Uuse025',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 * 
 +----------------------------------------------------------
 * Enter description here ... （根据学生的uc_uid返回学生的选课信息）
 +----------------------------------------------------------
 * @return Ambigous <unknown, Ambigous, string, multitype:> <返回类型(void的方法就不用该选项)>
 * @author RickerYu 2013-11-14
 +----------------------------------------------------------
 */
function get_studentcourse_ucid($uc_uid, $xnd='', $kkxq=''){
	
	//Ustu026
	$param['uid'] = $uc_uid;
	if($xnd != ''){
		$param['xnd'] = $xnd;
	}
	if($kkxq != ''){
		$param['kkxq'] = $kkxq;
	}
	
	$return = service_call('Ustu026',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
	
}


/**
 *
 +----------------------------------------------------------
 * Enter description here ... （根据学生的uc_uid返回老师的选课信息）
 +----------------------------------------------------------
 * @return Ambigous <unknown, Ambigous, string, multitype:> <返回类型(void的方法就不用该选项)>
 * @author RickerYu 2013-11-14
 +----------------------------------------------------------
 */
function get_teachercourse_ucid($uc_uid, $xnd='', $kkxq=''){

	//Ustu026
	$param['uid'] = $uc_uid;
	if($xnd != ''){
		$param['xnd'] = $xnd;
	}
	if($kkxq != ''){
		$param['kkxq'] = $kkxq;
	}

	$return = service_call('Utch034',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);

}

/**
 * 
 +----------------------------------------------------------
 * Enter description here ... （得到所有的教室楼名）
 +----------------------------------------------------------
 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return Ambigous <Ambigous, string, multitype:, unknown, $param, boolean, void> <返回类型(void的方法就不用该选项)>
 * @author RickerYu 2013-11-14
 +----------------------------------------------------------
 */
function get_all_classroom(){
	$param = array();
	$return = service_call('Uuse026',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
	
}
/**
 * 
 +----------------------------------------------------------
 * Enter description here ... （根据关键字和类型查找老师信息或选课信息）
 +----------------------------------------------------------
 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return Ambigous <Ambigous, string, multitype:, unknown, $param, boolean, void> <返回类型(void的方法就不用该选项)>
 * @author RickerYu 2013-11-16
 +----------------------------------------------------------
 */
function search_course_keyword($keyword,$keywordtype){
	$param['keyword'] = $keyword;
	$param['keywordtype'] = $keywordtype;
	$return = service_call('Utch035',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}
/**
 *
 +----------------------------------------------------------
 * Enter description here ... （根据关键字和类型查找老师信息或选课信息）
 +----------------------------------------------------------
 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return Ambigous <Ambigous, string, multitype:, unknown, $param, boolean, void> <返回类型(void的方法就不用该选项)>
 * @author RickerYu 2013-11-16
 +----------------------------------------------------------
 */
function search_course_list($bh,$keywordtype,$page = 1){
	$param['bh'] = $bh;
	$param['keywordtype'] = $keywordtype;
	$param['page'] = (int)$page -1;
	$return = service_call('Utch036',$param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * 根据学校ID获取学校下的所有部门
 */
function get_detptree_by_schoolid($schoolid){
	$param['schoolid']=intval($schoolid);
	$return = service_call('Udep005', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * 根据用户UC schoolid获取用户所在学校的组织结构
 */
function get_deptinfo_by_schoolid($schoolid){
	$param['schoolid']=intval($schoolid);
	$return = service_call('Utch033', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * 根据用户uid获取该用户所在院系信息
 */
function get_Academy_by_uid($schoolid, $uid){
	$param['schoolid']=intval($schoolid);
	$param['uid']=intval($uid);
	$return = service_call('Usch031', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * 根据用户所在yxid获取该用户所在院系的所有专业信息
 */
function get_Specialty_by_uid($schoolid, $yxid){
	$param['schoolid']=intval($schoolid);
	$param['yxid']=intval($yxid);
	$return = service_call('Usch032', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * 根据用户所在院系获取院系下的所有班级信息
 */
function get_Class_by_yxid($schoolid, $yxid){
	$param['schoolid']=intval($schoolid);
	$param['yxid']=intval($yxid);
	$return = service_call('Usch034', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * 根据用户所在的院系ID获取该院系下的所有学生信息
 */
function get_Student_baseinfo_by_yxid($schoolid, $yxid, $zyid, $classid, $nj){
	$param['schoolid']=intval($schoolid);
	$param['yxid']=intval($yxid);
	$param['zyid']=intval($zyid);
	$param['classid']=intval($classid);
	$param['nj']=intval($nj);
	$return = service_call('Usch033', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

/**
 * 根据组织机构的各ID获取其下的所有学生信息(注册的和非注册的可分别获取)
 */
function get_Student_baseinfo_by_org($schoolid, $yxid, $zyid, $classid, $nj, $isReg, $rowCount, $page = 1){
	$param['schoolid']=intval($schoolid);
	$param['yxid']=intval($yxid);
	$param['zyid']=intval($zyid);
	$param['classid']=intval($classid);
	$param['nj']=intval($nj);
	$param['isReg']=intval($isReg);// 1：已注册 2：未注册
	$param['rowCount']=intval($rowCount);
	$param['page']=intval($page) - 1;
	$return = service_call('Ustu027', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

//根据uid获取学生信息
function getStudentinfoByUid($uid){
	$param['uid']=$uid;
	$return = service_call('Ustu001', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

//用户名模糊查询
function searchUserByUsername($param){
	$return = service_call('Uuse028', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

//特定集合模糊查找用户
function getUserByUserNameANDParam($param){
	$return = service_call('Uuse029', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

//根据uid,日期,节次查询是否有课
function getHaveClassUids($uid, $xs, $skxq, $skjc, $columns){
	$param['uid'] = $uid;
	$param['xs'] = $xs;
	$param['skxq'] = $skxq;
	$param['skjc'] = $skjc;
	$param['columns'] = $columns;
	$return = service_call('Ustu028', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

//根据uid,日期,节次查询是否有课(老师)
function getHaveClassTeacherUids($uid, $xs, $skxq, $skjc, $columns){
	$param['uid'] = $uid;
	$param['xs'] = $xs;
	$param['skxq'] = $skxq;
	$param['skjc'] = $skjc;
	$param['columns'] = $columns;
	$return = service_call('Utch041', $param);
	return UC_CONNECT == 'mysql' ? $return : uc_unserialize($return);
}

?>