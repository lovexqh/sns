<?php
/**
 * VisitorAddons
 * 来访的人插件
 * @uses VisitorAddons
 * @package
 * @version 1.0
 * @copyright 2001-2013 小川
 * @author 小川 <chenweichuan@zhishisoft.com>
 * @license PHP Version 5.2
 */
class IMRemoteCallAddons extends SimpleAddons
{
	protected $version = '1.0';
	protected $author = '网格插件';
	protected $site = 'www.educomm.cn';
	protected $info = 'IM远程调用相关功能插件';
	protected $pluginName = 'IM远程调用';
	protected $tsVersion = "2.5"; // ts核心版本号

	public function getHooksInfo(){
	}
	
	/**
	 * +----------------------------------------------------------
	 * 索引页面
	 * +----------------------------------------------------------
	 * @author 小波 2013-6-17
	 * +----------------------------------------------------------
	 */
	public function index() {
		$this->display('index');
	}
	
	/**
	 +----------------------------------------------------------
	 + 验证用户名密码是否正确
	 +----------------------------------------------------------
	 + @param	@return boolean
	 + @return	boolean
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-25 下午1:26:34
	 +----------------------------------------------------------
	 */
	private function checkLogin($isPost=true){
	    $username = trim ( $isPost?$_POST['u']:$_GET['u'] ); // get过来的账号
	    $password = trim ( $isPost?$_POST['p']:$_GET['p'] ); // get过来的密码
	    $code = trim ( $_REQUEST ['code'] ); // get过来的验证码
	    //判断认证码是否正确
	    if ($code != md5 ( $username.'GridSoft' )) {
	        exit(JSON(array('errorno'=>'-1','errormsg'=>'请勿非法引用链接！')));
	    }
	    $passport = service ( 'Passport' );
	    // 判断用户账号是否存在
	    $result = $passport->getLocalUser ( $username );
	    if ($result) {
	        //判断密码是否正确
	        if ($result['password'] == $password) {
	            // 记录session等信息
	            $passport->registerLogin ( $result, false );
	            return $result;
	        } else {
	            exit(JSON(array('errorno'=>'-2','errormsg'=>'登录密码错误！')));
	        }
        } else { // 登录失败
            exit(JSON(array('errorno'=>'-3','errormsg'=>$passport->_error.'！')));
            
        }
	    return false;
	}
	
	/**
	 * +----------------------------------------------------------
	 * + 获取用户的头像
	 * +----------------------------------------------------------
	 * + @param
	 * + @return	return_type
	 * + @author	小波 (ZzStudio)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-7-25 下午4:04:45
	 * +----------------------------------------------------------
	 * + 示例：index.php?app=home&mod=public&act=displayAddons&addon=IMRemoteCall&hook=getUserFace&email=xxx@xxx.com&size=m
	 * +----------------------------------------------------------
	 */
	public function getUserFace() {
		$email = $_GET ['email'];
		$size = empty ( $_GET ['size'] ) ? 'm' : trim ( $_GET ['size'] );
		$uid = M ( 'user' )->where ( "email='{$email}'" )->getField ( "uid" );
		// 头像的大小方式为 [m,s,b]
		echo getUserFace ( $uid, $size );
		exit ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * + 获取用户的应用列表
	 * +----------------------------------------------------------
	 * + @author	小波 (ZzStudio)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-7-25 下午4:16:47
	 * +----------------------------------------------------------
	 * + index.php?app=home&mod=public&act=displayAddons&addon=IMRemoteCall&hook=appList&u=guoli@admin.com&p=96e79218965eb72c92a549dd5a330112&code=3dadcd529e7db5d21e5c20746f18405e
	 * +----------------------------------------------------------
	 */
	public function appList() {
		$username = trim ( $_GET ['u'] ); // get过来的账号
		$password = trim ( $_GET ['p'] ); // get过来的密码
		$code = trim ( $_GET ['code'] ); // get过来的验证码
		//判断认证码是否正确
		if ($code != md5 ( 'GridSoft' )) {
			$this->error ( "请勿非法引用链接！" );
		}
		$passport = service ( 'Passport' );
		// 判断用户账号是否存在
		$result = $passport->getLocalUser ( $username );
		if ($result) {
			//判断密码是否正确
			if ($result ['password'] == $password) {
				// 记录session等信息
				$passport->registerLogin ( $result, false );
				
				//根据email获取uid
				$this->mid = M ( 'user' )->where ( "email='{$username}'" )->getField ( "uid" );

				//获取统一身份系统中权限分配后的应用列表
				$userAppLimit = getUserAppLimit($this->mid);

				//获取社区中的应用列表
				// 用户的应用 = 默认应用 + 用户安装的可选应用
				$default_app = $this->getAppList(-1);
				$installed   = $this->getAppList($this->mid);
				//合并结果
				$install_apps = array_merge($default_app, $installed);

				//对两个结果进行过滤，取交集
				foreach ($install_apps as $key=>$app){
					if (empty($userAppLimit[$app['app_name']])) {
						unset($install_apps[$key]);
					}
				}
				
				//将结果集写入模板引擎
				$this->assign($_GET);
				$this->assign("data", $install_apps);
			} else {
				echo ( "登录密码错误！" );
				exit();
			}
		} else { // 登录失败
			echo ( $passport->_error );
			exit();
		}
		
		$this->display('index');
	}
	
	/**
	 +----------------------------------------------------------
	 + 获取社区中的应用列表
	 +----------------------------------------------------------
	 + @param	@param int $uid 查询的用户id
	 + @return	array
	 + @author	小波 (ZzStudio)
	 +----------------------------------------------------------
	 + 创建时间：	2013-7-25 下午5:38:04
	 +----------------------------------------------------------
	 */
	private function getAppList($uid=-1){
		$db_prefix = C('DB_PREFIX');
		$apps = M()->table($db_prefix."dsk_icos icos")
					->field("app.app_id,
								app.app_name,
								app.`status`,
								app_entry,
								icos.*")
					->join("LEFT JOIN ts_dsk_apps apps ON icos.oid = apps.appid
								LEFT JOIN ts_app app ON apps.oid = app.app_id")
					->where("icos.uid ={$uid}
								AND app.app_name <> ''")
					->findAll();
		return $apps==null?array():$apps;
	}
	
	/**
	 * +----------------------------------------------------------
	 * + IM登录方式
	 * +----------------------------------------------------------
	 * + @param
	 * + @return	return_type
	 * + @author	小波 (ZzStudio)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-7-25 下午4:03:14
	 * +----------------------------------------------------------
	 */
	public function imLogin() {
	    $data = $this->checkLogin(false);
	    
		$appName = trim ( $_GET ['o'] ); // get过来需要打开的应用
		
		// 判断要打开的应用
		if (! empty( $appName )) {
			$param = array (
				'openid' => getIcoid( $appName ) 
			);
		}
		if ($appName == 'desktop') {
			$param = array ();
		}
		
		redirect ( U ( 'desktop', $param ) );
	}
	
	/**
	 * +----------------------------------------------------------
	 * + 获取用户的应用列表（JSON）
	 * +----------------------------------------------------------
	 * + @author	小波 (ZzStudio)
	 * +----------------------------------------------------------
	 * + 创建时间：	2013-9-18 下午4:16:47
	 * +----------------------------------------------------------
	 * + code加密方式为：用户邮箱地址+GridSoft MD5后的编码
	 * + index.php?app=home&mod=public&act=displayAddons&addon=IMRemoteCall&hook=appList&u=guoli@admin.com&p=96e79218965eb72c92a549dd5a330112&code=3dadcd529e7db5d21e5c20746f18405e
	 * +----------------------------------------------------------
	 */
	public function appListToJSON() {
	    //验证账号密码
	    $data = $this->checkLogin();
	
        //根据email获取uid
        $this->mid = $data['uid'];
        $username = $data['email'];
        $password = $data['password'];
        $code = md5($username.'GridSoft');
        
        //获取统一身份系统中权限分配后的应用列表
        $userAppLimit = getUserAppLimit($this->mid);

        //获取社区中的应用列表
        // 用户的应用 = 默认应用 + 用户安装的可选应用
        $default_app = $this->getAppList(-1);
        $installed   = $this->getAppList($this->mid);
        //合并结果
        $install_apps = array_merge($default_app, $installed);

        //对两个结果进行过滤，取交集
        foreach ($install_apps as $key=>$app){
            if (empty($userAppLimit[$app['app_name']])) {
                unset($install_apps[$key]);
            }
        }
	
	    unset($data);
	    foreach ($install_apps as $app){
	        $data[$app['app_name']]['appid'] = $app['app_id'];
	        $data[$app['app_name']]['appname'] = $app['name'];
	        $data[$app['app_name']]['appimg'] = $app['img'];
	        $data[$app['app_name']]['appurl'] = U('home/Public/displayAddons', array('addon'=>'IMRemoteCall', 'hook'=>'imLogin', 'u'=>$username, 'p'=>$password, 'code'=>$code, 'o'=>$app['app_name']));
	    };
	    
	    exit(JSON($data));
	}
	
	/**
	 +----------------------------------------------------------
	 + 获取组织架构列表
	 +----------------------------------------------------------
	 + @param	
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-25 下午1:20:05
	 +----------------------------------------------------------
	 */
	public function OrganizStruct(){
	    //验证账号密码
	    $data = $this->checkLogin();
	    $uid = $data['uid'];
	    $this->ucInfo = get_baseinfo_by_uid(getUcUid($uid));
	    $schoolid = $this->ucInfo['schoolid'];
	    $email = $data['email'];
	    $uc_uid = $this->ucInfo['uid'];
	    
	    //获取登录用户类型(老师、学生)
	    $identities = get_baseinfo_by_uid($uc_uid);
	    $identity = $identities['identitytype'];

	    if($identity == '3'){//学生
	    	//获取uid所在的院系
	    	$academy = service('Organizational')->getAcademyByUid($schoolid, $uc_uid);
	    	$academy = $academy['0'];
	    	if(!empty($academy)){
	    		$yxid = $academy['yxid'];
	    		//获取院系下的所有专业
	    		$specialtyTree = service('Organizational')->getSpecialtyByUid($schoolid, $yxid);
	    		//获取院系下的所有班级
	    		$classTree = service('Organizational')->getClassTreeByYxid($schoolid, $yxid);
	    		//获取院系下的所有学生
	    		$studentTree = service('Organizational')->getStudentTreeByYxid($schoolid, $yxid);
	    	
	    		echo '<?xml version="1.0" encoding="utf-8"?>
                <root><iq>';
	    		//生成院系
	    		echo "<node name='{$academy['yxmc']}' uid='{$academy['yxid']}' parentid='0' />";
	    		//生成专业
	    		foreach ($specialtyTree as $specialty){
	    			echo "<node name='{$specialty['zymc']}' uid='{$specialty['id']}' parentid='{$specialty['yxid']}' />";
	    		}
	    		//生成班级
	    		foreach ($classTree as $class){
	    			echo "<node name='{$class['bm']}' uid='{$class['id']}' parentid='{$class['zyid']}' />";
	    		}
				//生成学生列表信息
	    		foreach($studentTree as $student){
	    			echo "<node name='{$student['xm']}' uid='{$student['identitytype']}-{$student['identityid']}' parentid='{$student['bjid']}' flag='s' sex='{$student['xbm']}' presence='unpresence' jid='{$student['email']}' />";
	    		}
	    		echo '</iq></root>';
	    		exit;
	    	}
	    }else{
	    	//获取组织架构的树
	    	$detplist = service('Organizational')->getTreeBySchoolId($schoolid);
	    	//获取用户列表的信息
	    	$userlist = service('Organizational')->getListBySchoolId($schoolid,false);
	    	echo '<?xml version="1.0" encoding="utf-8"?>
                <root><iq>';
	    	//生成组织架构的树
	    	foreach ($detplist as $dept){
	    		echo "<node name='{$dept['departname']}' uid='{$dept['deptid']}' parentid='{$dept['updeptid']}'/>";
	    	}
	    	//生成用户列表的信息
	    	foreach ($userlist as $user){
	    		echo "<node name='{$user['xm']}' uid='{$user['identitytype']}-{$user['identityid']}' parentid='{$user['deptid']}' flag='t' sex='{$user['xbm']}' presence='unpresence' jid='{$user['email']}'/>";
	    	}
	    	echo '</iq></root>';
	    	exit;
	    }
	}
	
	/**
	 +----------------------------------------------------------
	 + 用户密码校验
	 +----------------------------------------------------------
	 + @param	
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-10-28 下午2:51:56
	 +----------------------------------------------------------
	 */
	public function AuthVerify(){
	    $map['email'] = trim($_POST['email']);
	    $password = trim($_POST['password']);
	    if(empty($map['email']) || empty($password)){
	        exit('null');
	    }
	    $ucmember = get_members_by_param($map);
	    if ($ucmember) {
	    	if ($ucmember['password'] == md5($password.$ucmember['salt'])) {
	    		echo 'true';    //密码正确
	    	}else{
	    	    echo 'false';    //密码错误 
	    	}
	    }else{
	        echo 'failed';    //获取失败
	    }
	}
	
	/**
     +----------------------------------------------------------
     * 获取更新包最新版本
     +----------------------------------------------------------
     * @author lcq 2013-7-5
     +----------------------------------------------------------
     */
	 public function get_update_version(){
		
		$table_name = C('DB_PREFIX').'enc';
		$version_info =  M()->Table($table_name)->where('isshow=1 AND type=2')->group('version desc,uploadtime desc')->find();
		$version_array = array();
		if(!empty($version_info)){
			$version_array['version']  = $version_info['version'];
			$version_array['filepath'] = 'http://'.$_SERVER['SERVER_NAME'].'/'.$version_info['filepath'];
		}
		echo json_encode($version_array);
	}
	
	
	public function install()
	{
	    return true;
	}

	public function uninstall()
	{
		return true;
	}


}