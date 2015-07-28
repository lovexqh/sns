<?php
class PublicAction extends Action{

	public function code(){
		if (md5(strtoupper($_POST['verify'])) == $_SESSION['verify']) {
			echo 1;
		}else{
			echo 0;
		}
	}

	public function adminlogin() {
		if ( service('Passport')->isLoggedAdmin() ) {
			redirect(U('admin/Index/index'));
		}

		$this->display();
	}

	public function doAdminLogin() {
		// 检查验证码
		if ( md5(strtoupper($_POST['verify'])) != $_SESSION['verify'] ) {
			$this->error(L('error_security_code'));
		}

		// 数据检查
		if ( empty($_POST['password']) ) {
			$this->error(L('password_notnull'));
		}

		// 检查帐号/密码
		$is_logged = false;
		if (isset($_POST['email'])) {
			$is_logged = service('Passport')->loginAdmin($_POST['email'], $_POST['password']);
		}else if ( $this->mid > 0 ) {
			$is_logged = service('Passport')->loginAdmin($this->mid, $_POST['password']);
		}else {
			$this->error(L('parameter_error'));
		}

		// 提示消息不显示头部
		$this->assign('isAdmin','1');

		if ($is_logged) {
			$this->assign('jumpUrl', U('admin/Index/index'));
			$this->success(L('login_success'));
		}else {
			$this->assign('jumpUrl', U('home/Public/adminlogin'));
			$this->error(service('Passport')->getLastError());
		}
	}
	/**
	 +----------------------------------------------------------
	 * 登录页面
	 +----------------------------------------------------------
	 * @author 小波 2013-6-29
	 +----------------------------------------------------------
	 */
	public function login(){
		//if (service('Passport')->isLogged())
			//redirect(U('desktop'));

		unset($_SESSION['sina'], $_SESSION['key'], $_SESSION['douban'], $_SESSION['qq'],$_SESSION['open_platform_type']);

		//验证码
		$opt_verify = $this->_isVerifyOn('login');
		if ($opt_verify) {
			$this->assign('login_verify_on', $opt_verify);
		}
		//来路地址
		if (empty($_SESSION['refer_url'])) {
			$default = C('NOT_DESKTOP_THEME');
			$refer = false;
			foreach ($default as $val){
				if(stripos($_SERVER['HTTP_REFERER'], $val) !== false)
					$refer = true;
			}
			$_SESSION['refer_url'] = empty($_SERVER['HTTP_REFERER']) ? U('desktop') : ( $refer ? $_SERVER['HTTP_REFERER'] : U('desktop') );
		}else{ //如果不为空的时候
			$_SESSION['refer_url'] = U('desktop');
		}

		$data['email'] = t($_REQUEST['email']);
		$data['uname'] = t($_REQUEST['uname']);
		$data['uid']   = t($_REQUEST['uid']);
		Addons::hook('public_before_login',array(&$data));
		$this->setTitle(L('login'));
		$this->display();
	}

	function displayAddons(){
        $result = array();
        $param['res'] = &$result;
        $param['type'] = $_REQUEST['type'];
        Addons::addonsHook($_GET['addon'],$_GET['hook'],$param);
        isset($result['url']) && $this->assign("jumpUrl",$result['url']);
        isset($result['title']) && $this->setTitle($result['title']);
        isset($result['jumpUrl']) && $this->assign('jumpUrl',$result['jumpUrl']);
        if(isset($result['status']) && !$result['status']){
            $this->error($result['info']);
        }
        if(isset($result['status']) && $result['status']){
            $this->success($result['info']);
        }
	}

	//第三方登录页面显示
	function tryOtherLogin(){
		if ( !in_array($_GET['type'], array('sina', 'douban', 'qq')) ) {
			$this->error(L('parameter_error'));
		}
		include_once(SITE_PATH . "/addons/plugins/Login/lib/{$_GET['type']}.class.php");
        $platform = new $_GET['type']();
        redirect($platform->getUrl());
	}

	// 腾讯回调地址
	public function qqcallback() {
		include_once( SITE_PATH . '/addons/plugins/Login/lib/qq.class.php' );
        $qq = new qq();
        $qq->checkUser();
        redirect(U('home/Public/otherlogin'));
	}

	//外站帐号登录
	public function otherlogin(){
		if ( !in_array($_SESSION['open_platform_type'], array('sina', 'douban', 'qq')) ) {
			$this->error(L('not_authorised'));
		}

		$type = $_SESSION['open_platform_type'];
		include_once( SITE_PATH."/addons/plugins/Login/lib/{$type}.class.php" );
		$platform = new $type();
		$userinfo = $platform->userInfo();
		// 检查是否成功获取用户信息
		if ( empty($userinfo['id']) || empty($userinfo['uname']) ) {
			$this->assign('jumpUrl', SITE_URL);
			$this->error(L('user_information_filed'));
		}
		if ( $info = M('login')->where("`type_uid`='".$userinfo['id']."' AND type='{$type}'")->find() ) {
			$user = M('user')->where("uid=".$info['uid'])->find();
			if (empty($user)) {
				// 未在本站找到用户信息, 删除用户站外信息,让用户重新登录
				M('login')->where("type_uid=".$userinfo['id']." AND type='{$type}'")->delete();
			}else {
				if ( $info['oauth_token'] == '' ) {
					$syncdata['login_id']        	= $info['login_id'];
					$syncdata['oauth_token']        = $_SESSION[$type]['access_token']['oauth_token'];
					$syncdata['oauth_token_secret'] = $_SESSION[$type]['access_token']['oauth_token_secret'];
					M('login')->save($syncdata);
				}

				service('Passport')->registerLogin($user);

				redirect(U('home/User/index'));
			}
		}
		$this->assign('user',$userinfo);
		$this->assign('type',$type);
		$this->setTitle(L('third_party_account_login'));
		$this->display();
	}

	// 激活外站登录
	public function initotherlogin(){
		if ( ! in_array($_POST['type'], array('douban','sina', 'qq')) ) {
			$this->error(L('parameter_error'));
		}


		if( !isLegalUsername( t($_POST['uname']) ) ){
			$this->error(L('nickname_format_error'));
		}

		$haveName = M('User')->where( "`uname`='".t($_POST['uname'])."'")->find();
		if( is_array( $haveName ) && sizeof($haveName)>0 ){
			$this->error(L('nickname_used'));
		}

		$type = $_POST['type'];
		include_once( SITE_PATH."/addons/plugins/Login/lib/{$type}.class.php" );
		$platform = new $type();
		$userinfo = $platform->userInfo();

		// 检查是否成功获取用户信息
		if ( empty($userinfo['id']) || empty($userinfo['uname']) ) {
			$this->assign('jumpUrl', SITE_URL);
			$this->error(L('create_user_information_failed'));
		}

		// 检查是否已加入本站
		$map['type_uid'] = $userinfo['id'];
		$map['type']     = $type;
		if ( ($local_uid = M('login')->where($map)->getField('uid')) && (M('user')->where('uid='.$local_uid)->find()) ) {
			$this->assign('jumpUrl', SITE_URL);
			$this->success(L('you_joined'));
		}
		// 初使化用户信息, 激活帐号
		$data['uname']        = t($_POST['uname'])?t($_POST['uname']):$userinfo['uname'];
		$data['province']     = intval($userinfo['province']);
		$data['city']         = intval($userinfo['city']);
		$data['location']     = $userinfo['location'];
		$data['sex']          = intval($userinfo['sex']);
		$data['is_active']    = 1;
		$data['is_init']      = 1;
		$data['ctime']      = time();
		$data['is_synchronizing']  = ($type == 'sina') ? '1' : '0'; // 是否同步新浪微广播. 目前仅能同步新浪微广播

		if ( $id = M('user')->add($data) ) {
			// 记录至同步登录表
			$syncdata['uid']                = $id;
			$syncdata['type_uid']           = $userinfo['id'];
			$syncdata['type']               = $type;
			$syncdata['oauth_token']        = $_SESSION[$type]['access_token']['oauth_token'];
			$syncdata['oauth_token_secret'] = $_SESSION[$type]['access_token']['oauth_token_secret'];
			M('login')->add($syncdata);

			// 转换头像
			if ($_POST['type'] != 'qq') { // 暂且不转换QQ头像: QQ头像的转换很慢, 且会拖慢apache
				D('Avatar')->saveAvatar($id,$userinfo['userface']);
			}

			// 将用户添加到myop_userlog，以使漫游应用能获取到用户信息
			$userlog = array(
				'uid'		=> $id,
				'action'	=> 'add',
				'type'		=> '0',
				'dateline'	=> time(),
			);
			M('myop_userlog')->add($userlog);

			service('Passport')->loginLocal($id);

			$this->registerRelation($id);

			redirect( U('home/Public/followuser') );
		}else{
			$this->error('account_sync_error');
		}
	}

	public function bindaccount() {
		if ( ! in_array($_POST['type'], array('douban','sina','qq')) ) {
			$this->error(L('parameter_error'));
		}

		$psd  = ($_POST['passwd']) ? $_POST['passwd'] : true;
		$type = $_POST['type'];

		if ( $user = service('Passport')->getLocalUser($_POST['email'], $psd) ) {
			include_once( SITE_PATH."/addons/plugins/Login/lib/{$type}.class.php" );
			$platform = new $type();
			$userinfo = $platform->userInfo();

			// 检查是否成功获取用户信息
			if ( empty($userinfo['id']) || empty($userinfo['uname']) ) {
				$this->assign('jumpUrl', SITE_URL);
				$this->error(L('user_information_filed'));
			}

			// 检查是否已加入本站
			$map['type_uid'] = $userinfo['id'];
			$map['type']     = $type;
			if ( ($local_uid = M('login')->where($map)->getField('uid')) && (M('user')->where('uid='.$local_uid)->find()) ) {
				$this->assign('jumpUrl', SITE_URL);
				$this->success(L('you_joined'));
			}

			$syncdata['uid']      = $user['uid'];
			$syncdata['type_uid'] = $userinfo['id'];
			$syncdata['type']     = $type;
			if ( M('login')->add($syncdata) ) {
				service('Passport')->registerLogin($user);

				$this->assign('jumpUrl', U('home/User/index'));
				$this->success(L('bind_success'));

			}else {
				$this->assign('jumpUrl', SITE_URL);
				$this->error(L('bind_error'));
			}
		}else {
			$this->error(L('wrong_account'));
		}
	}

	//
	public function callback(){
		include_once( SITE_PATH.'/addons/plugins/Login/lib/sina.class.php' );
		$sina = new sina();
		$sina->checkUser();
		redirect(U('home/public/otherlogin'));
	}

	public function doubanCallback() {
		if ( !isset($_GET['oauth_token']) ) {
			$this->error('Error: No oauth_token detected.');
			exit;
		}
		require_once SITE_PATH . '/addons/plugins/Login/lib/douban.class.php';
		$douban = new douban();
		if ( $douban->checkUser($_GET['oauth_token']) ) {
			redirect(U('home/Public/otherlogin'));
		}else {
			$this->assign('jumpUrl', SITE_URL);
			$this->error(L('checking_failed'));
		}
	}

	/**
	 +----------------------------------------------------------
	 * 执行登录方法
	 +----------------------------------------------------------
	 * @author 小波 2013-6-29
	 +----------------------------------------------------------
	 */
	public function doLogin() {
		// 检查验证码
		$opt_verify = $this->_isVerifyOn('login');
		if ($opt_verify && (md5(strtoupper($_POST['verify']))!=$_SESSION['verify'])) {
			$this->error(L('error_security_code'));
		}

		Addons::hook('public_before_dologin',$_POST);

		$username =	$_POST['email'];
		$password =	$_POST['password'];


		if(!$password){
			$this->error(L('please_input_password'));
		}
		$result = service('Passport')->loginLocal($username,$password,intval($_POST['remember']));
		$lastError = service('Passport')->getLastError();

		//检查是否激活
	    if (!$result && $lastError =='用户未激活') {
	        $this->assign('jumpUrl',U('home/public/login'));
	        $this->error('该用户尚未激活，请更换帐号或激活帐号！');
	        exit;
	    }

		Addons::hook('public_after_dologin',$result);
		if($result) {
			if(is_array($result) && $result['user'] == false){
				unset($_SESSION['refer_url']);
				$this->error($lastError);
			}
			elseif(UC_SYNC && $result['reg_from_ucenter']){
				//从UCenter导入ThinkSNS，跳转至帐号修改页
				$refer_url = U('home/Public/checkRegister');
			}elseif ( $_SESSION['refer_url'] != '' ) {
				//跳转至登录前输入的url
				$refer_url	=	$_SESSION['refer_url'];
				unset($_SESSION['refer_url']);
			}else {
				$refer_url = U('desktop');
			}
			$this->assign('jumpUrl',$refer_url);
			$this->success($username.L('login_success').$result['login']);
		}else {
			$this->error($lastError);
		}
	}

	/**
	 +----------------------------------------------------------
	 * ajax登录方法
	 +----------------------------------------------------------
	 * @author 小波 2013-6-23
	 +----------------------------------------------------------
	 */
	public function doAjaxLogin(){
		//孙晓波于2013/6/22添加当页面登录错误三次时执行验证码 start
		$logincount = $_COOKIE['logincount'];
		if ($logincount>=3){
			// 检查验证码
			$opt_verify = $this->_isVerifyOn('login');
			if ($opt_verify){
				if(empty($_POST['verify'])){
					$return['message']	=	"请输入验证码";
					$return['status']	=	0;
				}else if(md5(strtoupper($_POST['verify']))!=$_SESSION['verify']) {
					$return['message']	=	L('error_security_code');
					$return['status']	=	0;
				}
				if (!empty($return)) {
					exit(json_encode($return));
				}
			}
		}
		//孙晓波于2013/6/22添加当页面登录错误三次时执行验证码 end

		$username = trim($_POST['email']);
		$password =	trim($_POST['password']);

		Addons::hook('public_before_doajaxlogin',$_POST);

		if(!$password){
			$return['message']	=	L('password_notnull');
			$return['status']	=	0;
			exit(json_encode($return));
		}

		$result = service('Passport')->loginLocal($username,$password, intval($_POST['remember']) === 1);
		
		//孙晓波于2013/6/22添加当ajax登录时判断若是统一身份登录则进行二次判断 start
		if (UC_SYNC && $result!==true) {
			$result = $result['user'];
		}
		//孙晓波于2013/6/22添加当ajax登录时判断若是统一身份登录则进行二次判断 end
		
		if($result){
			$return['message']	=	L('login_success');
			$return['status']	=	1;
			if(UC_SYNC && $uc_user[0]){
				
			}
				//$return['callback']	=	uc_user_synlogin($uc_user[0]);
		}else{
			$error_message = service('Passport')->getLastError();
			$return['message']	=	$error_message;
			$return['status']	=	0;
		}

		Addons::hook('public_after_doajaxlogin',$result);
		
		exit(json_encode($return));
	}
	
	/**
	 +----------------------------------------------------------
	 * 验证系统是否已登录
	 +----------------------------------------------------------
	 * @author 小波 2013-7-3
	 +----------------------------------------------------------
	 */
	public function isLogin(){
		if (empty($_SESSION['userInfo'])) {
			echo 'false';
		}else{
			echo 'true';
		}
	}

	public function logout() {
		service('Passport')->logoutLocal();

		Addons::hook('public_after_logout');
		
		if(UC_SYNC){
			$uc_result = uc_user_synlogout();
		}
		$this->assign('jumpUrl',U('home/Index/index'));
		$this->success(L('exit_success'));
	}

	public function logoutAdmin() {
		// 成功消息不显示头部
		$this->assign('isAdmin','1');
		service('Passport')->logoutLocal();
		$this->assign('jumpUrl',U('home/Public/adminlogin'));
		$this->success(L('exit_success'));
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取数据库中的email后缀
	 +----------------------------------------------------------
	 * @author 小波 2013-6-24
	 +----------------------------------------------------------
	 */
	public function mailsuffix(){
		$suffix = '';
		$email = $_POST['email'];
		$map['email'] = array('neq','');
		if (!empty($email) && strpos($email,'@')) {
			$suffix = substr($email, strpos($email,'@'));
			$map['email'] = array('like','%'.$suffix.'%');
		}
		$result = M('user')
					  ->field("SUBSTRING(email,INSTR(email,'@')) as mailsuffix")
					  ->where($map)
					  ->group('mailsuffix')
					  ->findAll();
		
		$data['suffix'] = $suffix;
		$data['data'] = getSubByKey($result, 'mailsuffix');;
		echo json_encode($data);
	}

	private function __getInviteInfo($invite_code)
	{
		$res = null;
		$invite_option = model('Invite')->getSet();
		switch (strtolower($invite_option['invite_set'])) {
			case 'close':
				$res = null;
				break;
			case 'common':
				$res = D('User', 'home')->getUserByIdentifier($invite_code, 'uid');
				break;
			case 'invitecode':
				$res = model('Invite')->checkInviteCode($invite_code);
				if ($res['is_used'])
					$res = null;
				break;
		}

		return $res;
	}

	public function isRegisterOpen()
	{
	    return strtolower(model('Xdata')->get('register:register_type')) == 'open';
	}

	public function isRegisterAvailable()
	{
		echo $this->isRegisterOpen() ? '1' : '0';
	}

	/**
	 +----------------------------------------------------------
	 * 注册页面
	 +----------------------------------------------------------
	 * @author 小波 2013-6-25
	 +----------------------------------------------------------
	 */
	public function register(){
		if (service('Passport')->isLogged())
			redirect(U('home/User/index'));
		
		$identity = trim($_GET['identity']);
		//第一次清求展示选择角色 孙晓波2013/06/24日增加
		if(empty($identity)){
			$this->display('identity');
			exit();	
		}
		
		$identity_code = array(
			'student' => '3',
			'teacher' => '2',
			'parent' => '4'
		);

		//验证码
		$opt_verify = $this->_isVerifyOn('register');
		if ( $opt_verify ) {
			$this->assign('register_verify_on', 1);
		}

		Addons::hook('public_before_register');

		// 邀请码
		$invite_code = h($_REQUEST['invite']);
		$invite_info = null;

		// 是否开放注册
		$register_option = model('Xdata')->get('register:register_type');
		if ($register_option == 'closed') { // 关闭注册
			$this->error(L('reg_close'));

		} else if ($register_option == 'invite') { // 邀请注册
			// 邀请方式
			$invite_option = model('Invite')->getSet();
			if ($invite_option['invite_set'] == 'close') { // 关闭邀请
				$this->error(L('reg_invite_close'));
			} else { // 普通邀请 OR 使用邀请码
				if (!$invite_code)
					$this->error(L('reg_invite_warming'));
				else if (!($invite_info = $this->__getInviteInfo($invite_code)))
					$this->error(L('reg_invite_code_error'));
			}
		} else { // 公开注册
			if (!($invite_info = $this->__getInviteInfo($invite_code)))
				unset($invite_code, $invite_info);
		}

		$this->assign('identity',$identity);
		
		$this->assign('invite_info', $invite_info);
		$this->assign('invite_code', $invite_code);
		$this->setTitle(L('reg'));
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 注册执行方法
	 +----------------------------------------------------------
	 * @author 小波 2013-6-25
	 +----------------------------------------------------------
	 */
	public function doRegister(){
		// 验证码
		$verify_option = $this->_isVerifyOn('register');
		if ($verify_option && (md5(strtoupper($_POST['verify'])) != $_SESSION['verify'])){
			$this->error(L('error_security_code'));
			exit;
		}

		Addons::hook('public_before_doregister', $_POST);

		// 邀请码
		$invite_code = h($_REQUEST['invite_code']);
		$invite_info = null;

		// 是否允许注册
		$register_option = model('Xdata')->get('register:register_type');
		if ($register_option === 'closed') { // 关闭注册
			$this->error(L('reg_close'));

		} else if ($register_option === 'invite') { //邀请注册

			// 邀请方式
			$invite_option = model('Invite')->getSet();

			if ($invite_option['invite_set'] == 'close') { // 关闭邀请
				$this->error(L('reg_invite_close'));
			} else { // 普通邀请 OR 使用邀请码
				if (!$invite_code)
					$this->error(L('reg_invite_warming'));
				else if (!($invite_info = $this->__getInviteInfo($invite_code)))
					$this->error(L('reg_invite_code_error'));
			}
		} else { // 公开注册
			if (!($invite_info = $this->__getInviteInfo($invite_code)))
				unset($invite_code, $invite_info);
		}

		// 参数合法性检查
		$required_field = array(
			'email'		=> 'Email',
			'password'	=> L('password'),
			'repassword'=> L('retype_password'),
		);
		foreach ($required_field as $k => $v)
			if (empty($_POST[$k]))
				$this->error($v . L('not_null'));

		if (!$this->isValidEmail($_POST['email']))
			$this->error(L('email_format_error_retype'));
		//if (!UC_SYNC){
		if($_POST['usertype'] == 'visitor'){			
			if (empty($_POST['nickname']))
				$this->error(L('username') . L('not_null'));
			if(!$this->isValidNickName($_POST['nickname']))
				$this->error(L('nickname_format_error_or_used'));
		}
		//}
		if (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 16 || $_POST['password'] != $_POST['repassword'])
			$this->error(L('password_rule'));
		if (!$this->isEmailAvailable($_POST['email']))
			$this->error(L('email_used_retype'));

		// 是否需要Email激活
		$need_email_activate = intval(model('Xdata')->get('register:register_email_activate'));
		
		//获取用户基础数据
		//if(UC_SYNC){
			switch ($_POST['usertype']) {
				case 'teacher'://老师
					$map['sfzjh'] = trim($_POST['teacherno']);
					$map['xm'] = trim($_POST['teachername']);
					$baseinfo = get_teacher_baseinfo_by_param($map);
					$baseinfo['identitytype'] = $_POST['usertype'] = 2;
					$_POST['nickname'] = $baseinfo['xm'];
					break;
				case 'student'://学生
					$map['xm'] = trim($_POST['studentname']);
					$map['sfzjh'] = trim($_POST['studentno']);
					$baseinfo = get_student_baseinfo_by_param($map);
					$baseinfo['identitytype'] = $_POST['usertype'] = 3;
					$_POST['nickname'] = $baseinfo['xm'];
					break;
			}
		//}
		if($_POST['usertype']=='teacher' || $_POST['usertype']=='student'){
			if (empty($baseinfo)){
				$this->error('基础数据获取失败！');
			}
		}
		
		//判断用户Email是否已在Ucenter注册
		$_member = get_members_by_param(array('email'=>trim($_POST['email'])));
		if($_member){
			$this->error('该用户Email已在统一身份系统注册！');
		}
		//判断用户identityid是否已在Ucenter注册
		if($baseinfo){
			$_member = get_members_by_param(array('identityType'=>$baseinfo['identitytype'], 'identityId'=>$baseinfo['identityid']));
			if($_member){
				$this->error('该用户基础信息已在统一身份系统注册！');
			}
		}
		
		//判断用户是否在社区注册
		$_user = M('user')->field('uname,realname')->where(array('email'=>trim($_POST['email'])))->find();
		if ($_user) {
			$this->error('该E-mail已在社区注册！');
		}
		
		//判断该用户的昵称是否存在
		if($baseinfo){
			$uname = $baseinfo['xm'];
		}else{
			$uname = $_POST['nickname'];
		}
		
		$isuname = M('user')->where("uname = '{$uname}'")->find();
		if($isuname){ //如果昵称存在，则在昵称后面跟3位随机数
			$uname = randUName($uname);
		}

		unset($data);//释放data变量
		if (!empty($baseinfo)){
			//从基础数据中获取性别
			$data['sex']   	  = intval($baseinfo['xbm'])==2?0:(intval($baseinfo['xbm'])==0?-1:intval($baseinfo['xbm']));	
		}else{
			$data['sex'] = -1;
		}

		// 注册
		$data['email']     = $_POST['email'];
		$data['password']  = md5($_POST['password']);
		$data['uname']	   = $uname;	
		$data['realname']	   = t($_POST['nickname']);
		$data['ctime']	   = time();
		$data['is_active'] = $need_email_activate ? 0 : 1;
		$data['register_ip']= get_client_ip();
		$data['regip']	 = get_client_ip();

		// 添加用户信息
		if (!($uid = D('User', 'home')->add($data)))
			$this->error(L('reg_filed_retry'));
		
		// 同步至UCenter
		//if (UC_SYNC) {
		//$uc_member['username'] = $_POST['nickname'];
		$uc_member['username'] = $uname;
		$uc_member['password'] = $_POST['password'];
		$uc_member['email'] = $_POST['email'];
		$uc_member['realname'] = $_POST['nickname'];
		$uc_member['questionid'] = '';
		$uc_member['answer'] = '';
		
		if($baseinfo){
			$uc_member['identityid'] = $baseinfo['identityid'];
			$uc_member['identitytype'] = $baseinfo['identitytype'];
		}else{
			$uc_member['identitytype'] = 5;
		}
		$uc_uid = uc_user_register($uc_member);
		//echo uc_user_synlogin($uc_uid);
		if ($uc_uid > 0)
			ts_add_ucenter_user_ref($uid,$uc_uid,$data['uname']); //需加入添加昵称的方法
		//}
		
		// 添加用户分组信息
		if (intval($_POST['usertype'])){
			$group_link['user_group_id'] = $_POST['usertype'];
			$group_link['user_group_title'] = M('user_group')->where('user_group_id='.$_POST['usertype'])->getField('title');
			$group_link['uid'] = $uid;

			if (!($gid = M('user_group_link')->add($group_link)))
				$this->error('用户角色设置异常！');
		}

		Addons::hook('public_after_doregister',$uid);

		// 将用户添加到myop_userlog，以使漫游应用能获取到用户信息
		$user_log = array(
			'uid'		=> $uid,
			'action'	=> 'add',
			'type'		=> '0',
			'dateline'	=> time(),
		);
		M('myop_userlog')->add($user_log);

		// 将邀请码设置已用
		model('Invite')->setInviteCodeUsed($invite_code);
		model('InviteRecord')->addRecord($invite_info['uid'],$uid);

		if ($need_email_activate == 1) { // 邮件激活
			$this->activate($uid, $_POST['email'], $invite_code);
		} else {
			// 置为已登录, 供完善个人资料时使用
			service('Passport')->loginLocal($uid);
			//if (!is_numeric(stripos($_POST['HTTP_REFERER'], dirname('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']))) && $register_option != 'invite') {
                //注册完毕，跳回注册页之前
              // redirect($_POST['HTTP_REFERER']);
			//} else {
				//注册完毕，跳转至帐号修改页
				redirect(U('home/Public/checkRegister'));
			//}
		}
	}

	/**
	 +----------------------------------------------------------
	 * 完善个人资料
	 +----------------------------------------------------------
	 * @author 小波 2013-6-25
	 +----------------------------------------------------------
	 */
	public function checkRegister()
	{
		if (!$this->mid)
			redirect(U('home/Public/login'));

		// 已初始化的用户, 不允许在此修改资料
		global $ts;
		if ($this->mid && $ts['user']['is_init']){
			redirect(U('desktop'));
		}

		if ($_POST) {

			$user_info = D('User', 'home')->getUserByIdentifier($this->mid, 'uid');

			//获取用户基础数据
			if(UC_SYNC){
				$ucInfo = arrayKeyToLower($_SESSION['ucInfo']);
				if (empty($ucInfo)){
					$uc_uid = M('ucenter_user_link')->where('uid = '.$this->mid)->getField('uc_uid');
				}else{
					$uc_uid = $ucInfo['uid'];
				}
				$baseinfo = arrayKeyToLower(get_baseinfo_by_uid($uc_uid));
				//print_r($baseinfo);
				if (empty($baseinfo['realname'])) $baseinfo['realname'] = $baseinfo['username'];
				
				//赋值用户的详细信息
				$data['realname'] = $baseinfo['realname'];
			}
			$data['is_init']  = 1;

			M('user')->where("uid={$this->mid}")->data($data)->save();

			// 关联操作
			$this->registerRelation($this->mid);

			S('S_userInfo_'.$this->mid,null);
			
			Addons::hook('public_after_douserinfo',$this->mid);

			redirect(U('home/Public/followuser'));
		} else {
			$user_info = D('User', 'home')->getUserByIdentifier($this->mid, 'uid');
			$this->assign('nickname', $user_info['uname']);
			$this->setTitle(L('complete_information'));
			
			//获取用户角色信息
			$group_link = M('user_group_link')->where('uid='.$this->mid)->find();
			$this->assign('groupinfo',$group_link);

			//获取用户基础数据
			//if(UC_SYNC){
			$ucInfo = arrayKeyToLower($_SESSION['ucInfo']);
			if (empty($ucInfo)){
				$uc_uid = M('ucenter_user_link')->where('uid = '.$this->mid)->getField('uc_uid');
			}else{
				$uc_uid = $ucInfo['uid'];
			}
			$baseinfo = arrayKeyToLower(get_baseinfo_by_uid($uc_uid));
			$uc_user_info = get_user_by_uid($uc_uid);
			//dump($uc_user_info);exit;
			//print_r($baseinfo);
			//exit();
			switch ($ucInfo['identitytype']){
				case '2'://老师
					break;
				default://学生时获取家长信息
					
			}
			
			if (empty($baseinfo['realname'])) {
				$baseinfo['realname'] = $baseinfo['username'];
			}
			
			$this->assign($ucInfo);
			$this->assign('baseinfo', $baseinfo);
			//}
			$this->assign('userinfo', $user_info);
			$this->assign('ucUserInfo', $uc_user_info[0]);

			$this->display();
		}
	}

	//关注推荐用户
	public function followuser()
	{
		if ($_POST) {
			if ($_POST['followuid']) {
				foreach ($_POST['followuid'] as $value) {
					D('Follow','weibo')->dofollow($this->mid,$value,0);
				}
			}
			if ($_POST['doajax']) {
				echo '1';
			} else {
				redirect(U('home/Public/regSuccess'));
			}
		} else {
			$users      = D('Follow', 'weibo')->getRelativeFollowerUser($this->mid, 50);
			//随机打散取10个
			shuffle($users);
			$users = array_slice($users,0,10);
			//获取用户详细信息
			$user_model = D('User', 'home');
			$user_model->setUserObjectCache(getSubByKey($users, 'uid'));
			foreach ($users as $k => $v) {
				$user = $user_model->getUserByIdentifier($v['uid'], 'uid');
				$users[$k]['uname']    = $user['uname'];
				$users[$k]['location'] = $user['location'];
			}
			$this->assign('users', $users);
			$this->setTitle(L('recommend_user'));
			$this->display();
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 注册完成
	 +----------------------------------------------------------
	 * @author 小波 2013-6-26
	 +----------------------------------------------------------
	 */
	public function regSuccess(){
		//add 孙攀 20131207 用于外部调用注册的跳转
		if (!empty($_SESSION ['AAM']['Login_URL'])) {
			redirect($_SESSION ['AAM']['Login_URL']);
			exit();
		}
		$this->display();
	}

	//使用邀请码注册
	public function inviteRegister() {
		if ( ! $invite = service('Validation')->getValidation() ) {
			$this->error(L('reg_invite_code_error'));
		}

		if ( "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"] != $invite['target_url'] ) {
			$this->error(L('url_error'));
		}
		$this->assign('invite', $invite);

		$invite['data']			= unserialize($invite['data']);
		$map['tpl_record_id']	= $invite['data']['tpl_record_id'];
		$tpl_record 			= model('Template')->getTemplateRecordByMap($map, '', 1);
		$tpl_record 			= $tpl_record['data'][0]['data'];
		$this->assign('template', $tpl_record);

		//邀请人的好友
		$friend = model('Friend')->getFriendList($invite['from_uid'], null, 9);
		$this->assign($friend);

		$this->display('invite');
	}

	public function resendEmail() {
		$invite = service('Validation')->getValidation();
		$this->activate(intval($_GET['uid']), $_GET['email'], $invite, 1);
	}

	//发送激活邮件
	public function activate($uid, $email, $invite = '', $is_resend = 0) {
		//设置激活路径
		$activate_url  = service('Validation')->addValidation($uid, '', U('home/Public/doActivate'), 'register_activate', serialize($invite));
		if ($invite) {
			$this->assign('invite', $invite);
		}
		$this->assign('url',$activate_url);

		//设置邮件模板
		$body = <<<EOD
感谢您的注册!<br>

请马上点击以下注册确认链接，激活您的帐号！<br>

<a href="$activate_url" target='_blank'>$activate_url</a><br/>

如果通过点击以上链接无法访问，请将该网址复制并粘贴至新的浏览器窗口中。<br/>

如果你错误地收到了此电子邮件，你无需执行任何操作来取消帐号！此帐号将不会启动。
EOD;
		// 发送邮件
		global $ts;
		$email_sent = service('Mail')->send_email($email, "激活{$ts['site']['site_name']}帐号",$body);

		// 渲染输出
		if ($email_sent) {
			$email_info = explode("@", $email);
			switch ($email_info[1]) {
				case "qq.com"    : $email_url = "mail.qq.com";break;
				case "163.com"   : $email_url = "mail.163.com";break;
				case "126.com"   : $email_url = "mail.126.com";break;
				case "gmail.com" : $email_url = "mail.google.com";break;
				default          : $email_url = "mail.".$email_info[1];
			}

			$this->assign("uid",$uid);
			$this->assign('email', $email);
			$this->assign('is_resend', $is_resend);
			$this->assign("email_url",$email_url);
			$this->display('activate');
		}else {
			$this->assign('jumpUrl', U('home/Index/index'));
			$this->error(L('email_send_error_retry'));
		}
	}

	public function doActivate() {
		$invite = service('Validation')->getValidation();
		if (!$invite) {
			$this->assign('jumpUrl', U('home/Public/register'));
        	$this->error(L('activation_code_error_retry'));
		}
		$uid = $invite['from_uid'];

		$invite['data'] = unserialize($invite['data']);
		//邀请信息
		if($invite['data']){

		}
        $user = M('user')->where("`uid`=$uid")->find();
        if ($user['is_active'] == 1) {
        	$this->assign('jumpUrl', U('home/Public/login'));
        	$this->success(L('account_activity'));
        	exit;
        } else if ($user['is_active'] == 0) {
        	//激活帐户
        	$res = M('user')->where("`uid`=$uid")->setField('is_active', 1);
        	if (!$res) $this->error(L('activation_failed'));

			service('Passport')->registerLogin($user);

			//关联操作
			$this->registerRelation($user['uid']);

			service('Validation')->unsetValidation();

			$this->assign('jumpUrl', U('home/Account/index'));
			$this->success(L("activation_success"));
        } else {
        	$this->assign('jumpUrl', U('home/Public/register'));
        	$this->error(L('activation_code_error_retry'));
        }
	}

	public function sendPassword() {
		$this->display();
	}

	public function doSendPassword() {
		$_POST["email"]	= t($_POST["email"]);
		$filter=array('name','emial');
		foreach ($data as $k=>$v) {
			if (is_array($k,$filter)){
				$sql .= "";
			}
		}
		if ( !$this->isValidEmail($_POST['email']) )
			$this->error(L('email_format_error'));

		$user =	M("user")->where('`email`="' . $_POST['email'] . '"')->find();

        if(!$user) {
        	$this->error(L("email_not_reg"));
        }else {
            $code = base64_encode( $user["uid"] . "." . md5($user["uid"] . '+' . $user["password"]) );
            $url  = U('home/Public/resetPassword', array('code'=>$code));
            $body = <<<EOD
<strong>{$user["uname"]}，你好: </strong><br/>

您只需通过点击下面的链接重置您的密码: <br/>

<a href="$url">$url</a><br/>

如果通过点击以上链接无法访问，请将该网址复制并粘贴至新的浏览器窗口中。<br/>

如果你错误地收到了此电子邮件，你无需执行任何操作来取消帐号！此帐号将不会启动。
EOD;

			global $ts;
			$email_sent = service('Mail')->send_email($user['email'], L('reset')."{$ts['site']['site_name']}".L('password'), $body);

            if ($email_sent) {
	            $this->assign('jumpUrl', SITE_URL);
	            $this->success(L('send_you_mailbox').$email.L('notice_accept'));
            }else {
            	$this->error(L('email_send_error_retry'));
            }
		}
	}

	public function resetPassword() {
		$code = explode('.', base64_decode($_GET['code']));
        $user = M('user')->where('`uid`=' . $code[0])->find();

        if ( $code[1] == md5($code[0].'+'.$user["password"]) ) {
	        $this->assign('email',$user["email"]);
	        $this->assign('code', $_GET['code']);
	        $this->display();
        }else {
        	$this->error(L("link_error"));
        }
	}

	public function doResetPassword() {
		if($_POST["password"] != $_POST["repassword"]) {
        	$this->error(L("password_same_rule"));
        }

		$code = explode('.', base64_decode($_POST['code']));
        $user = M('user')->where('`uid`=' . $code[0])->find();

        if ( $code[1] == md5($code[0] . '+' . $user["password"]) ) {
	        $user['password'] = md5($_POST['password']);
	        $res = M('user')->save($user);
			//同步设置UC密码
			include_once(SITE_PATH.'/api/uc_client/uc_sync.php');
			if(UC_SYNC){
				$ucenter_user_ref = ts_get_ucenter_user_ref($code[0]);
				$uc_res = uc_user_edit($ucenter_user_ref['uc_uid'],'',$_POST['password'],'',1);
				if($uc_res == -8){
					$this->error(L('userprotected_no_right'));
				}
			}
			//去掉用户缓存信息
			S('S_userInfo_'. $code[0],null);
	        if ($res) {
	        	$this->assign('jumpUrl', U('home/Public/login'));
	        	$this->success(L('save_success'));
	        }else {
	        	$this->error(L('save_error_retry'));
	        }
        }else {
        	$this->error(L("safety_code_error"));
        }
	}

	public function doModifyEmail() {
    	if ( !$validation = service('Validation')->getValidation() ) {
    		$this->error(L('error_security_code'));
    	}
    	if ( "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"] != $validation['target_url'] ) {
    		$this->error(L('url_error'));
		}

    	$validation['data'] = unserialize($validation['data']);
    	$map['uid']			= $validation['from_uid'];
    	$map['email']		= $validation['data']['oldemail'];
		if ( M('user')->where($map)->setField('email', $validation['data']['email']) ) {
			service('Validation')->unsetValidation();
			service('Passport')->logoutLocal();
			$this->assign('jumpUrl', SITE_URL);
			$this->success(L('activate_new_email_success'));
		}else {
			$this->error(L('activate_new_email_failed'));
		}
    }

	//检查Email地址是否合法
	public function isValidEmail($email) {
		if(UC_SYNC){
			$res = uc_user_checkemail($email);
			if($res == -4){
				return false;
			}else{
				return true;
			}
		}else{
			return preg_match("/[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/i", $email) !== 0;
		}
	}

	//检查Email是否可用
	public function isEmailAvailable($email = null) {
		$return_type = empty($email) ? 'ajax' 		   : 'return';
		$email		 = empty($email) ? $_POST['email'] : $email;

		$res = M('user')->where('`email`="'.$email.'"')->find();

		if(UC_SYNC){
			$uc_res = uc_user_checkemail($email);
			if($uc_res == -5 || $uc_res == -6){
				$res = true;
			}
		}

		if ( !$res ) {
			if ($return_type === 'ajax') echo 'true';
			else return true;
		}else {
			if ($return_type === 'ajax') echo 'false';
			else return false;
		}
	}
	
	//检查昵称是否可用
	public function isNicknameAvailable($nickname = null) {
		$return_type = empty($nickname) ? 'ajax' 		   : 'return';
		$nickname		 = empty($nickname) ? $_POST['nickname'] : $nickname;
	
		$res = M('user')->where('`uname`="'.$nickname.'"')->find();
	
		if ( !$res ) {
			if ($return_type === 'ajax') echo 'true';
			else return true;
		}else {
			if ($return_type === 'ajax') echo 'false';
			else return false;
		}
	}

	// 检查验证码是否可用
	public function isVerifyAvailable($verify = null) {
		$return_type = empty($verify) ? 'ajax' : 'return';
		$verify = empty($verify) ? $_POST['verify'] : $verify;
		$verify_option = $this->_isVerifyOn('register');
		if($verify_option && md5(strtoupper($verify)) == $_SESSION['verify']) {
			if($return_type === 'ajax') {
				echo 'true';
			} else {
				return true;
			}
		} else {
			if($return_type === 'ajax') {
				echo 'false';
			} else {
				return false;
			}
		}

	}

	// 登陆检查验证码是否可用
	public function isVerifyAvailableLogin($verify = null) {
		$return_type = empty($verify) ? 'ajax' : 'return';
		$verify = empty($verify) ? $_POST['verify'] : $verify;
		if( md5(strtoupper($verify)) == $_SESSION['verify']) {
			if($return_type === 'ajax') {
				echo 'success';
			} else {
				return true;
			}
		} else {
			if($return_type === 'ajax') {
				echo '验证码输入错误';
			} else {
				return false;
			}
		}

	}

	//检查昵称是否符合规则，且是否为唯一
	public function isValidNickName($name) {

		$return_type  = empty($name)  ? 'ajax' 		   			: 'return';
		$name		  = empty($name)  ? t($_POST['nickname']) 	: $name;

		//昵称不能是纯数字昵称
		if(is_numeric($name)){
			echo '昵称不能是纯数字昵称';
			return;
		}

		//检查禁止注册的用户昵称
		$audit = model('Xdata')->lget('audit');
		if($audit['banuid']==1){
			$bannedunames = $audit['bannedunames'];
			if(!empty($bannedunames)){
				$bannedunames = explode('|',$bannedunames);
				if(in_array($name,$bannedunames)){
					if ($return_type === 'ajax') {
						echo '这个昵称禁止注册';
						return;
					} else {
						$this->error('这个昵称禁止注册');
					}
				}
			}
		}

		if (UC_SYNC) {
			$uc_res = uc_user_checkname($name);
			if($uc_res == -1 || !isLegalUsername($name)){
				if ($return_type === 'ajax') { echo L('username_rule');return; }
				else return false;
			}
		} else if (!isLegalUsername($name)) {
			if ($return_type === 'ajax') { echo L('username_rule');return; }
			else return false;
		} else if (checkKeyWord($name)) {
			if ($return_type === 'ajax') {
				echo '昵称含有敏感词';
				return;
			} else {
				$this->error('昵称含有敏感词');
			}
		}

		if ($this->mid) {
			$res = M('user')->where("uname='{$name}' AND uid<>{$this->mid}")->count();
			$nickname = M('user')->getField('uname',"uid={$this->mid}");
			if (UC_SYNC && ($uc_res == -2 || $uc_res == -3) && $nickname != $name) {
				$res = 1;
			}
		} else {
			$res = M('user')->where("uname='{$name}'")->count();
			
			if(UC_SYNC && ($uc_res == -2 || $uc_res == -3)){
				$res = 1;
			}
		}

		if ( !$res ) {
			if ($return_type === 'ajax') echo 'success';
			else return true;
		}else {
			if ($return_type === 'ajax') echo L('nickname_used');
			else return false;
		}
	}

	//检查是否真实姓名。支持ajax和return
	public function isValidRealName($name = null, $opt_register = null) {
		$return_type  = empty($name) 		 ? 'ajax' 							: 'return';
		$name		  = empty($name) 		 ? t($_POST['uname']) 				: $name;
		$opt_register = empty($opt_register) ? model('Xdata')->lget('register') : $opt_register;

		if ($opt_register['register_realname_check'] == 1) {
			$lastname = explode(',', $opt_register['register_lastname']);
			$res = in_array( substr($name, 0, 3), $lastname ) || in_array( substr($name, 0, 6), $lastname );
		}else {
			$res = true;
		}

		if ($res) {
			if ($return_type === 'ajax') echo 'success';
			else return true;
		}else {
			if ($return_type === 'ajax') echo 'fail';
			else return false;
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据条件字段获取用户基础数据
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param 
	 +----------------------------------------------------------
	 * @return 
	 +----------------------------------------------------------
	 */
	private function _getBaseInfo($userNo=0,$type=0,$name=''){
		$type = intval($type);
		if ($userNo){
			if(!isset($baseinfo)){	//检查session中是否有历史查询的记录
				$baseinfo = uc_baseinfo_by_userno($userNo,$type);
			}else{
				switch ($type){
					case '0':
						if ($baseinfo['xh']!=$userNo){	//检查session中的学号是否与提交来的学号一致
							$baseinfo = uc_baseinfo_by_userno($userNo,0);
						}
						break;
					case '1':
						if ($baseinfo['sfzjh']!=$userNo){	//检查session中的老师身份证号是否与提交来的身份证号一致
							$baseinfo = uc_baseinfo_by_userno($userNo,1);
						}
						break;
					case '2':
						if ($baseinfo['xh']!=$userNo&&$baseinfo['xm']!=$name){	//检查session中的老师身份证号是否与提交来的身份证号一致
							$baseinfo = uc_baseinfo_by_userno($userNo,2,$name);
						}
						break;
					default:
						break;
				}
			}
			if ($type!=2)
				$_SESSION['baseinfo'] = $baseinfo;
		}else{
			$baseinfo = $_SESSION['baseinfo'];
		}
		return $baseinfo;
	}

	//检查是否存在学号。支持ajax和return
	public function isValidUserNO($userNo){
		$return_type = empty($userNo) ? 'ajax' 		   : 'return';
		$userNo		 = empty($userNo) ? trimStr($_REQUEST['userno']) : $userNo;
		$flag = false;
		if(UC_SYNC){
			$uc_res = $this->_getBaseInfo($userNo,0);
			if($uc_res['xh']!=$userNo){
				$uc_res = $this->_getBaseInfo($userNo,0);
			}
			if ($uc_res){
				$_uc_member = uc_user_by_identity($uc_res['identityid'],3);
				if ($return_type === 'ajax'&&$_uc_member){
					$flag = true;
				}
			}
		}
		if ($uc_res&&!$flag) {
			if ($return_type === 'ajax') echo 'success';
			else return true;
		}else if($flag){
			if ($return_type === 'ajax') echo '<font color=red>该学号已被注册，如有问题，请联系管理员</font>';
			else return false;
		}else {
			if ($return_type === 'ajax') echo '你的学号输入错误，请重新输入！';
			else return false;
		}

	}
	
	//检查是否存在老师身份证号。支持ajax和return
	public function isValidTeacherNO($teacherNo){
		$return_type	= empty($teacherNo) ? 'ajax' 		   : 'return';
		$teacherNo		= empty($teacherNo) ? trimStr($_REQUEST['teacherno']) : $teacherNo;
		$flag = false;
		if(UC_SYNC){
			$uc_res = get_teacher_baseinfo_by_sfzjh($teacherNo);
			if (!$uc_res){
				$flag = true;
			}
		}
		if ($uc_res&&!$flag) {
			if ($return_type === 'ajax') echo 'true';
			else return true;
		}else if($flag){
			if ($return_type === 'ajax') echo 'false';		//该身份证已被注册，如有问题请联系管理员
			else return false;
		}else {
			if ($return_type === 'ajax') echo 'false';	//你的身份证号输错误，请重新输入！
			else return false;
		}

	}
	
	//检查是否存在老师身份证号。支持ajax和return
	public function isValidStudentNO($studentNo, $studentName =''){
		$return_type	= empty($studentNo) ? 'ajax' 		   : 'return';
		$studentNo		= empty($studentNo) ? trimStr($_REQUEST['studentno']) : $studentNo;
		$flag = false;
		if(UC_SYNC){
			//$uc_res = get_all_by_sfzh($studentNo);
			$uc_res = get_student_baseinfo_by_param(array('sfzjh'=>$studentNo));
			if (!$uc_res){
				$flag = false;
			}else if(!isset($uc_res['uid'])){
				$flag = false;
			}else{
				$flag = true;
			}
		}
		if ($uc_res&&!$flag) {
			if ($return_type === 'ajax') echo 'true';
			else return true;
		}else if($flag){
			if ($return_type === 'ajax') echo 'false';		//该身份证已被注册，如有问题请联系管理员
			else return false;
		}else {
			if ($return_type === 'ajax') echo 'false';	//你的身份证号输错误，请重新输入！
			else return false;
		}
	
	}
	
	//检查是否存在孩子 学号。支持ajax和return
	public function isValidSonNO($userNo){
		$return_type = empty($userNo) ? 'ajax' : 'return';
		$userNo		 = empty($userNo) ? trim($_REQUEST['sonno']) : $userNo;
		
		if(UC_SYNC){
			$uc_res = get_student_baseinfo_by_xh($userNo);
		}
		
		if ($uc_res) {
			if ($return_type === 'ajax') echo 'true';
			else return true;
		}else {
			if ($return_type === 'ajax') echo 'false'; //你的学号输入错误，请重新输入！
			else return false;
		}

	}
	
	//检查学生真实姓名是否在存。支持ajax和return
	public function isValidStudentName($studentNo=0,$userName,$userNo=0){
		$return_type = $userNo===0 ? 'ajax' : 'return';
		$studentNo = $studentNo===0 ? trim($_REQUEST['studentno']) : $studentNo;
		$userName	 = empty($userName) ? trim($_REQUEST['studentname']) : $userName;
		$result = false;
		if(UC_SYNC){
			if($studentNo){
				//$uc_res = get_all_by_sfzh($studentNo);
				$uc_res = get_student_baseinfo_by_param(array('sfzjh'=>$studentNo));
				if($uc_res['xm']==trim($userName)){
					$result = true;
				}
			}
			
		}
		if ($result&&!empty($userName)) {
			if ($return_type === 'ajax') echo 'true';
			else return true;
		}else if(empty($userName)){
			if ($return_type === 'ajax') echo 'false'; //请输入你真实的姓名！
			else return false;
		}else{
			if ($return_type === 'ajax') echo 'false'; //你输入的真实姓名错误，请重新输入！
			else return false;
		}

	}
	
	//检查老师真实姓名是否在存。支持ajax和return
	public function isValidTeacherName($teacherNo=0,$teacherName){
		$return_type	= $teacherNo===0 ? 'ajax' : 'return';
		$teacherNo		= $teacherNo===0 ? trim($_REQUEST['teacherno']) : $teacherNo;
		$teacherName	= empty($teacherName) ? trim($_REQUEST['teachername']) : $teacherName;
		$result = false;
		if(UC_SYNC){
			if (!$teacherNo) //如果没有学号则仅查询名称
				$uc_res = get_teacher_baseinfo_by_xm($teacherName);
			else{ //身份证号与名称一起查询
				$map['sfzjh'] = $teacherNo;
				$map['xm'] = $teacherName;
				$uc_res = get_teacher_baseinfo_by_param($map);
			}
				
			if($uc_res['xm']==trimStr($teacherName)){
				$result = true;
			}
		}
		if ($result&&!empty($teacherName)) {
			if ($return_type === 'ajax') echo 'true';
			else return true;
		}else if(empty($teacherName)){
			if ($return_type === 'ajax') echo 'false';		//请输入你真实的姓名！
			else return false;
		}else{
			if ($return_type === 'ajax') echo 'false';		//你输入的真实姓名错误，请重新输入！
			else return false;
		}

	}
	
	//检查孩子真实姓名是否在存。支持ajax和return
	public function isValidSonName($userNo=0,$userName){
		$return_type = $userNo===0 ? 'ajax' : 'return';
		$userNo		 = $userNo===0 ? trim($_REQUEST['sonno']) : $userNo;
		$userName	 = empty($userName) ? trim($_REQUEST['sonnickname']) : $userName;
		$result = false;
		if(UC_SYNC){
			if ($userName && $userNo===0)
				$uc_res = get_student_baseinfo_by_xm($userName);
			else if($userName && $userNo){
				$map['xh'] = $userNo;
				$map['xm'] = $userName;
				$uc_res = get_student_baseinfo_by_xm($userName);
			}

			if($userName==$uc_res['xm']||in_array($userName, $uc_res['sonname'])){
				$result = true;
			}
		}
		if ($result&&!empty($userName)) {
			if ($return_type === 'ajax') echo 'true';
			else return true;
		}else if(empty($teacherName)){
			if ($return_type === 'ajax') echo 'false'; //请输入真实的姓名！
			else return false;
		}else{
			if ($return_type === 'ajax') echo 'false'; //你输入的真实姓名错误，请重新输入！
			else return false;
		}

	}
	
	/**
	 +----------------------------------------------------------
	 * 检查学生的监护人的姓名
	 +----------------------------------------------------------
	 * @author 小波 2013-6-28
	 +----------------------------------------------------------
	 */
	public function isValidGuarderName($userNo=0, $studentname, $guarderName){
		$return_type = $userNo===0 ? 'ajax': 'return';
		$userNo		 = $userNo===0 ? trim($_REQUEST['userno']) : $userNo;
		$studentname	 = empty($studentname) ? trim($_REQUEST['studentname']) : $studentname;
		$guarderName	 = empty($guarderName) ? trim($_REQUEST['guardername']) : $guarderName;

		$result = false;
		if(UC_SYNC){
			if (!empty($userNo)  && !empty($studentname) && !empty($guarderName)){
				$uc_res = get_student_baseinfo_by_param(array('xh'=>$userNo, 'xm'=>$studentname));
			}else if(!empty($studentname) && !empty($guarderName)){
				$uc_res = get_student_baseinfo_by_param(array('xm'=>$studentname), $guarderName);
			}else if(!empty($guarderName))	{
				$uc_res = get_parent_baseinfo_by_xm($guarderName);
			}
			if(!empty($uc_res)){
				$result = true;
			}
		}
		if ($result&&!empty($guarderName)) {
			if ($return_type === 'ajax') echo 'true';
			else return true;
		}else if(empty($guarderName)){
			if ($return_type === 'ajax') echo 'false'; //请输入父亲（或母亲）真实的姓名！
			else return false;
		}else{
			if ($return_type === 'ajax') echo 'false'; //父亲（或母亲）的姓名输入错误！
			else return false;
		}
	}
	
	//检查家长的姓名
	public function isValidGuarderNickName($userNo=0,$sonnickname='', $guarderName=''){
		$return_type = $userNo===0 ? 'ajax': 'return';
		$userNo		 = $userNo===0 ? trim($_REQUEST['sonno']) : $userNo;
		$sonnickname	= empty($sonnickname) ? trim($_REQUEST['sonnickname']) : $sonnickname;
		$guarderName	 = empty($guarderName) ? trim($_REQUEST['guardernickname']) : $guarderName;
	
		$result = false;
		if(UC_SYNC){
			if ($userNo && empty($guarderName))
				$uc_res = get_student_baseinfo_by_param(array('xh'=>$userNo));
			else 
				$uc_res = get_student_baseinfo_by_param(array('xh'=>$userNo, 'xm'=>$sonnickname), $guarderName);
				
			if($uc_res){
				$result = true;
			}
		}
		if ($result && !empty($guarderName)) {
			if ($return_type === 'ajax') echo 'true';
			else return true;
		}else if(empty($guarderName)){
			if ($return_type === 'ajax') echo 'false'; //请输入您的真实的姓名！
			else return false;
		}else{
			if ($return_type === 'ajax') echo 'false'; //您的真实姓名输入错误！
			else return false;
		}
	}
	
	/**
	 * 根据自己姓名和家长姓名获取学号及其它信息
	 */
	public function selectStuInfo(){
		$this->display();
	}
	
	/**
	 * 根据学生姓名与家长姓名获取学号等信息
	 */
	public function getStudentInfo(){
		$stuName = trim($_REQUEST['studentname']);
		$parentName = trim($_REQUEST['guardername']);
		$result = uc_get_student_by_name($stuName,$parentName);
		dump($result);
	}

	// 注册的关联操作
    public function registerRelation($uid,$invite_uid)
    {
    	if (($uid = intval($uid)) <= 0)
    		return;

    	$dao = D('Follow','weibo');

    	// 使用邀请码时, 建立与邀请人的关系
    	$inviter = model('InviteRecord')->getInviter($uid);
    	if ($inviter) {
    		// 互相关注
    		$dao->dofollow($uid, $inviter['uid']);
			$dao->dofollow($inviter['uid'], $uid);

			// 设置邀请信息
			model('InviteRecord')->setRecordValid($uid);

			// 添加邀请记录
			//model('InviteRecord')->addRecord($inviter['uid'], $uid);

			//邀请人积分操作
			X('Credit')->setUserCredit($inviter['uid'], 'invite_friend');
    	}

        // 默认关注的好友
		$auto_freind = model('Xdata')->lget('register');
		$auto_freind['register_auto_friend'] = explode(',', $auto_freind['register_auto_friend']);
		foreach($auto_freind['register_auto_friend'] as $v) {
			if (($v = intval($v)) <= 0)
				continue ;
			$dao->dofollow($v, $uid);
			$dao->dofollow($uid, $v);
		}

		// 开通个人空间
		$data['uid'] = $uid;
		model('Space')->add($data);

		//注册成功 初始积分
		X('Credit')->setUserCredit($uid,'init_default');
	}

	public function verify() {
        require_once(SITE_PATH.'/addons/libs/Image.class.php');
        require_once(SITE_PATH.'/addons/libs/String.class.php');
    	Image::buildImageVerify();
	}

    //上传图片
    public function uploadpic(){
    	if( $_FILES['pic'] ){
    		//执行上传操作
    		$savePath =  $this->getSaveTempPath();
    		$filename = md5( time().'teste' ).'.'.substr($_FILES['pic']['name'],strpos($_FILES['pic']['name'],'.')+1);
	    	if(@copy($_FILES['pic']['tmp_name'], $savePath.'/'.$filename) || @move_uploaded_file($_FILES['pic']['tmp_name'], $savePath.'/'.$filename))
	        {
	        	$result['boolen']    = 1;
	        	$result['type_data'] = 'temp/'.$filename;
	        	$result['picurl']    = __UPLOAD__.'/temp/'.$filename;
	        } else {
	        	$result['boolen']    = 0;
	        	$result['message']   = L('upload_filed');
	        }
    	}else{
        	$result['boolen']    = 0;
        	$result['message']   = L('upload_filed');
    	}

    	exit( json_encode( $result ) );
    }

    //上传临时文件
	public function getSaveTempPath(){
        $savePath = SITE_PATH.'/data/uploads/temp';
        if( !file_exists( $savePath ) ) mk_dir( $savePath  );
        return $savePath;
    }

    // 地区管理
    public function getArea() {
    	echo json_encode(model('Area')->getAreaTree());
    }

	/**  文章  **/
	public function document() {
		$list	= array();
		$detail = array();
		$res    = M('document')->where('`is_active`=1')->order('`display_order` ASC,`document_id` ASC')->findAll();

		// 获取content为url且在页脚显示的文章
		global $ts;
		$ids_has_url = array();
		foreach($ts['footer_document'] as $v)
			if( !empty($v['url']) )
				$ids_has_url[] = $v['document_id'];

		$_GET['id'] = intval($_GET['id']);

		foreach($res as $v) {
			// 不显示content为url且在页脚显示的文章
			if ( in_array($v['document_id'], $ids_has_url) )
				continue ;

			$list[] = array('document_id'=>$v['document_id'], 'title'=>$v['title']);

			// 当指定ID，且该ID存在，且该文章的内容不是url时，显示指定的文章。否则显示第一篇
			if ( $v['document_id'] == $_GET['id'] || empty($detail) ) {
				$v['content'] = htmlspecialchars_decode($v['content']);
				$detail = $v;
			}
		}
		unset($res);

		$this->assign('detail', $detail);
		$this->assign('list', $list);
		$this->display();
	}

	public function toWap() {
		$_SESSION['wap_to_normal'] = '0';
		cookie('wap_to_normal', '0', 3600*24*365);
		U('wap', '', true);
	}

	public function fetchNew()
	{
		$map['weibo_id']	 = array('gt', intval($_POST['since_id']));
		$map['transpond_id'] = 0;
		$map['type']		 = 0;
		$data = D('Operate', 'weibo')->doSearchTopic('`weibo_id` > '.intval($_POST['since_id']).' AND transpond_id =0 AND `type` = 0', 'weibo_id DESC', 0,false);
		$res = $data['data'][0];
		if ($res) {
			$res['uname'] = getUserSpace($res['uid'], '', '_blank', '{uname}');
			$res['user_pic']	  = getUserSpace($res['uid'], '', '_blank', '{uavatar=m}');
			$res['friendly_date'] = friendlyDate($res['ctime']);
			$res['content'] = format($res['content'],true);
			echo json_encode($res);
		} else {
			echo 0;
		}
	}

	/**
	 +----------------------------------------------------------
	 * 404错误
	 +----------------------------------------------------------
	 * @author 小波 2013-7-8
	 +----------------------------------------------------------
	 */
	public function error404() {
		$this->display('404');
	}
	
	/**
	 +----------------------------------------------------------
	 * 500错误（主要应用于WebService错误）
	 +----------------------------------------------------------
	 * @author 小波 2013-7-8
	 +----------------------------------------------------------
	 */
	public function error500() {
		$this->display('500');
	}
	
	/**
	 +----------------------------------------------------------
	 * License错误
	 +----------------------------------------------------------
	 * @author 小波 2013-7-8
	 +----------------------------------------------------------
	 */
	public function errorLicense(){
		$this->display('license');
	}

	private function _isVerifyOn($type='login'){
		// 检查验证码
		if($type!='login' && $type!='register') return false;
		$opt_verify = $GLOBALS['ts']['site']['site_verify'];
		return in_array($type, $opt_verify);
	}

	//获取开发平台应用列表接口
	public function getDevelopList() {
		$pageId = intval($_REQUEST['p']);
		$list = D('Develop', 'develop')->getListDevelop();

    	foreach($list['data'] as $key => $value) {
    		switch($value['type']) {
    			case 1:
    				$list['data'][$key]['type'] = '风格模板';
    				break;
    			case 2:
    				$list['data'][$key]['type'] = '插件';
    				break;
    			case 3:
    				$list['data'][$key]['type'] = '应用';
    				break;
    		}
    	}

		$html = '<div class="opentitlenav">';
		$html .= '<p class="appmz">共有<b>'.$list['count'].'</b>个应用</p>';
		$html .= '<p class="applx">类型</p>';
		$html .= '<p class="appcs">下载次数</p>';
		$html .= '<p class="appkf">开发者</p>';
		$html .= '</div>';

		$html .= '<ul>';

		foreach($list['data'] as $value) {
			$html .= '<li>';
			$html .= '<p class="pic"><a href="#"><img src="'.$value['logo'].'" style="width:64px; height:64px;" /></a></p>';
			$html .= '<p class="name"><b><a href="content.php?id='.$value['develop_id'].'">'.$value['title'].'</a></b><em>'.getShort(strip_tags($value['explain']), 16).'</em></p>';
			$html .= '<p class="sort">'.$value['type'].'</p>';
			$html .= '<p class="down">'.$value['download_count'].'</p>';
			$html .= '<p class="oper"><a href="'.U('home/Space/index', array('uid'=>$value['uid'])).'">'.getUserName($value['uid']).'</a></p>';
			$attachUrl = U('home/Public/downloadWithDevelop', array('id'=>$value['develop_id']));
			$html .= '<p class="caoz"><em><a href="'.$attachUrl.'"></a></em></p>';
			$html .= '</li>';
		}

		$html .= '</ul>';

		//设置列表分页
		if($list['totalPages'] > 1) {
			$pageHtml = '';
			if($pageId != 1) {
				$pageHtml .= '<a href="javascript:void(0)" onclick="pageShow('.($pageId - 1).')">上一页</a>';
			}
			if($list['totalPages']<=5){
				for($i = 1; $i <= $list['totalPages']; $i++) {
					if($i == $list['nowPage']) {
						$pageHtml .= '<span class="current">'.$i.'</span>';
					} else {
						$pageHtml .= '<a href="javascript:void(0)" onclick="pageShow('.$i.')">'.$i.'</a>';
					}
				}

			}elseif($list['nowPage'] <= 3) {
				for($i = 1; $i <= 5; $i++) {
					if($i == $list['nowPage']) {
						$pageHtml .= '<span class="current">'.$i.'</span>';
					} else {
						$pageHtml .= '<a href="javascript:void(0)" onclick="pageShow('.$i.')">'.$i.'</a>';
					}
				}
				$pageHtml .= '<a href="javascript:void(0)" onclick="pageShow('.$list['totalPages'].')">...'.$list['totalPages'].'</a>';
			} else {
				$pageHtml .= '<a href="javascript:void(0)" onclick="pageShow(1)">1...</a>';

				if(($list['totalPages'] - $list['nowPage']) <= 3) {
					for($i = $list['totalPages'] - 4; $i <= $list['totalPages']; $i++) {
						if($i == $list['nowPage']) {
							$pageHtml .= '<span class="current">'.$i.'</span>';
						} else {
							$pageHtml .= '<a href="javascript:void(0)" onclick="pageShow('.$i.')">'.$i.'</a>';
						}
					}
				} else {
					$pageHtml .= '<a href="javascript:void(0)" onclick="pageShow('.($list['nowPage'] - 2).')">'.($list['nowPage'] - 2).'</a>';
					$pageHtml .= '<a href="javascript:void(0)" onclick="pageShow('.($list['nowPage'] - 1).')">'.($list['nowPage'] - 1).'</a>';
					$pageHtml .= '<span class="current">'.$list['nowPage'].'</span>';
					$pageHtml .= '<a href="javascript:void(0)" onclick="pageShow('.($list['nowPage'] + 1).')">'.($list['nowPage'] + 1).'</a>';
					$pageHtml .= '<a href="javascript:void(0)" onclick="pageShow('.($list['nowPage'] + 2).')">'.($list['nowPage'] + 2).'</a>';

					$pageHtml .= '<a href="javascript:void(0)" onclick="pageShow('.$list['totalPages'].')">...'.$list['totalPages'].'</a>';
				}
			}

			if($pageId != $list['totalPages']) {
				$pageHtml .= '<a href="javascript:void(0)" onclick="pageShow('.($pageId + 1).')">下一页</a>';
			}

			$html .= '<div class="page">'.$pageHtml.'</div>';
		}

		$data = json_encode(array('html'=>$html));
		echo $_GET['callback']."(".$data.")";
		exit();
	}

	//获取开发平台应用详细
	public function getDevelopDetail() {
		$developId = intval($_REQUEST['id']);
		$data = D('Develop', 'develop')->getDetailDevelop($developId);

    	foreach($data as $key => $value) {
    		switch($data['type']) {
    			case 1:
    				$data['type'] = '风格模板';
    				break;
    			case 2:
    				$data['type'] = '插件';
    				break;
    			case 3:
    				$data['type'] = '应用';
    				break;
    		}
    	}

		$html = '<dl>';
		$html .= '<dt><img src="'.$data['logo'].'" style="width:64px; height:64px;" /></dt>';
		$html .= '<dd class="la">';
		$html .= '<p class="mingz">'.$data['title'].'</p>';
		$html .= '<p class="fenl">类型：'.$data['type'].'</p>';
		$html .= '<p class="zuoz">开发者：<a href="'.U('home/Space/index', array('uid'=>$data['uid'])).'">'.getUserName($data['uid']).'</a></p>';
		$html .= '</dd>';
		$html .= '<dd class="lb">';
		$attachUrl = U('home/Public/downloadWithDevelop', array('id'=>$data['develop_id']));
		$html .= '<p class="caoz"><em><a href="'.$attachUrl.'"></a></em></p>';
		$html .= '</dd>';
		$html .= '</dl>';
		$html .= '<div class="contentapptxt">';
		$html .= '<h5>简介</h5>';
		$html .= '<p>'.$data['explain'].'</p>';
		$html .= '</div>';

		$data = json_encode(array('html'=>$html));
		echo $_GET['callback']."(".$data.")";
		exit();
	}

	//官网应用平台下载链接
    public function downloadWithDevelop(){
        try{
            $model = D('Develop', 'develop');
            $id = intval($_GET['id']);
            $data = D('Develop', 'develop')->getDetailDevelop($id);
            $attach = $data['file'];
            if(!$attach){
                $this->error(L('attach_noexist'));
            }
            if($data['status'] != DevelopModel::STATUS_PASS) $this->error('该扩展尚未通过审核，不允许下载');

            //下载函数
            require_cache('./addons/libs/Http.class.php');
            $file_path = $attach['url'];
            if(file_exists($file_path)) {
                //计算数
                $model->download($id);
                $filename = iconv("utf-8",'gb2312',$attach['name']);
                Http::download($file_path, $filename);

            }else{
                $this->error(L('attach_noexist'));
            }
        }catch(ThinkException $e){
            $this->error($e->getMessage());
        }
    }
	    //敏感词过滤keyWordFilter($content)
    public function destroyWords(){
    	$keywords = model('Xdata')->lget('audit');
    	$words = explode('|', $keywords['keywords']);
    	$badwords = C('badwords');
    	$dbprefix = C('DB_PREFIX');
    	foreach ($badwords as $k => $v ){
    		foreach($v as $value) {
	    	//	$where = "{$value} like '%".implode("%' or $value like '%",$words)."%'";
	    		$select = M($k)->field($value)->findall();
				echo M()->getLastSql();
				echo "<br/>";
				echo "<br/>";
	    		if ($select !== FALSE && $select !== null){
					//dump($select);
	    			foreach ($select as $skey => $svalue ){
	    				$content = $svalue["$value"];
	    				$upcontent = keyWordFilter($content);
	    				$map = $save = array();
	    				$map[$value] = $content;
	    				$save[$value] = $upcontent;
	    				M($k)->where($map)->save($save);
	    				echo M()->getLasloginql();
	    			}
	    		}
    		}
    	}
    }

    // 定期扫库功能 - 过滤敏感词
    public function scanTables() {
        set_time_limit(0);
        $badWords = C('badwords');
        $dbPrefix = C('DB_PREFIX');

        // 自定义过滤表
        foreach($badWords as $key => $val) {
            if($key != '*') {
                $data['tableName'] = $dbPrefix.$key;
                $data['field'] = $val;
                $badTable[] = $data;
            }
        }

        // 自定义表过滤操作
        foreach($badTable as $value) {
            $tableInfo = M()->query('DESCRIBE '.$value['tableName']);
            foreach($tableInfo as $pValue) {
                if($pValue['Key'] == 'PRI') {
                    $priKey = $pValue['Field'];
                    break;
                }
            }
            $field = $value['field'];
            array_unshift($field, $priKey);
            foreach($field as &$formatField) {
                $formatField = '`'.$formatField.'`';
            }
            $field = implode(',', $field);
            $data = M()->Table($value['tableName'])->field($field)->findAll();

            foreach($data as $cValue) {
                $map[$priKey] = $cValue[$priKey];
                foreach($value['field'] as $fValue) {
                    $contentVal = $cValue[$fValue];
                    $filterVal = keyWordFilter($contentVal);
                    if($contentVal != $filterVal) {
                        $save[$fValue] = $filterVal;
                    }
                }
                if(!empty($save)) {
                    M()->Table($value['tableName'])->where($map)->save($save);
                }
                unset($map);
                unset($save);
            }
        }
    }
    
    /**
     +----------------------------------------------------------
     * IM登录方式
     +----------------------------------------------------------
     * @author 小波 2013-6-29
     +----------------------------------------------------------
     */
    public function imlogin(){
    	$username = trim($_REQUEST['u']);	//get过来的账号
    	$password = trim($_REQUEST['p']);	//get过来的密码
    	$code = trim($_REQUEST['code']);	//get过来的验证码
    	$appName = trim($_REQUEST['o']); //get过来需要打开的应用
    	//首先判断用户验证码是否正确
    	if ($code != md5('GridSoft')) {
    		$this->error("请勿非法引用链接！");
    	}
    	$passport = service('Passport');
    	//判断用户账号密码是否正确   	
    	$result = $passport->getLocalUser($username);
    	//记录session等信息
    	if ($result) {
    		$passport->registerLogin($result, false);
    	}
    	//判断要打开的应用
		if (!empty($appName)) {
			$param = array(
				'openid' =>getIcoid($appName)
			);
		}
		if($appName == 'desktop'){
			$param = array();
		}
    	
   		if ($result){
   			if($result['password']==$password) { //登录成功
   				redirect(U('desktop', $param));
   			}else{
   				$this->error("登录密码错误！");
   			}
    	}else{ //登录失败
    		$this->error($passport->_error);
    	}
    }
    
    /**
     +----------------------------------------------------------
     * 服务功能测试页面
     +----------------------------------------------------------
     * @author 小波 2013-6-27
     +----------------------------------------------------------
     */
    public function serviceDemo(){
    	if ($_POST) {
    		$serviceNo = trim($_POST['serviceNo']);
    		$keys = $_POST['key'];
    		$vals = $_POST['val'];
    		$param = array();
    		foreach ($keys as $k=>$key){
    			$param[$key] = $vals[$k];
    		}
			$return = service_call($serviceNo, $param);
    		dump($return);
    	}else{
    		$this->display();
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
	/**
     +----------------------------------------------------------
     * 版本授权验证
     +----------------------------------------------------------
     * @author lcq 2013-7-6
     +----------------------------------------------------------
     */
	 public function license(){
	 
		$result = check_licenseinfo('home');

		if(!$result['status']){
			$this->assign('jumpUrl',U('desktop/index/index'));
			$this->success($result['info']);
		}else{
			$this->assign('jumpUrl',U('home/public/license_upload'));
			$this->error($result['info']);
		}
	}
	/**
     +----------------------------------------------------------
     * 上传并认证license认证文件
     +----------------------------------------------------------
     * @author lcq 2013-7-6
     +----------------------------------------------------------
     */
	 public function license_upload(){

		if($_POST){
			if(file_exists (SITE_PATH.'\license.zl')){
				unlink(SITE_PATH.'\license.zl');
			}
			$options['max_size']   = '2000000';
			$options['allow_exts'] = 'zl';
			$options['save_path']  = SITE_PATH.'/';
			$options['save_to_db'] = false;
			$options['save_name'] = 'license.zl';
			//执行上传操作
			$info = X('Xattach')->upload('enc',$options);
			if(!$info['status']){
				$this->error($info['info']);
			}else{
				$info = check_licenseinfo();
				if(!$info['status']){
					$this->assign('jumpUrl',U('desktop/index/index'));
					$this->success($info['info']);
				}else{ 
					$this->assign('jumpUrl',U('home/public/license_upload'));
					$this->error($info['info']);
				}
			}
		}else{
		
			$physical_address = Getphysical_Addr(PHP_OS);
			$md5_mac = md5($physical_address.'GridSoft');
			$this->assign('physical_address', $md5_mac);
			$this->display('license');
		}
		
	}

    //创建license用的临时函数
	public function create_license(){
		$physical_address = Getphysical_Addr(PHP_OS);
		$license_info = array(
			'companyname'  => '锐杰网格',
			'physicaladdr' => md5($physical_address.'GridSoft'),
			'home'		   => array(
								'starttime'	=> date('Y-m-d H:i:s',time()),
								'endtime'	=> date('Y-m-d H:i:s',time()+7*60*60*24),//一周的测试授权使用时间
								'onlinemembers' => 100
								),
			'video'		   => array(
								'starttime'	=> date('Y-m-d H:i:s',time()),
								'endtime'	=> date('Y-m-d H:i:s',time()+60*60*24*7),
								'onlinemembers' => 100
								),
			'admin'		   => array(
								'starttime'	=> date('Y-m-d H:i:s',time()),
								'endtime'	=> date('Y-m-d H:i:s',time()+60*60*24*7),
								'onlinemembers' => 100
								),
			'desktop'		   => array(
								'starttime'	=> date('Y-m-d H:i:s',time()),
								'endtime'	=> date('Y-m-d H:i:s',time()+60*60*24*7),
								'onlinemembers' => 100
								),
			'enc'		   => array(
								'starttime'	=> date('Y-m-d H:i:s',time()),
								'endtime'	=> date('Y-m-d H:i:s',time()+60*60*24*7),
								'onlinemembers' => 100
								),
		);
		$str = json_encode($license_info);
		echo $des_physical_address = desencrypt($str,'GridSoft');
	}	
	
	public function imSyncDemo(){
		if($_POST){
			header('Content-Type:text/html;charset=utf-8');
	
			$url = 'index.php?app=home&mod=Public&act=displayAddons&addon=IMRemoteCall';
			$local = $_POST['local'];
			$hook = $_POST['hook'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$post_data = array();
			switch ($hook){
				case 'AuthVerify':
					$post_data['email'] = $username;
					$post_data['password'] = $password;
					$post_data['submit'] = "submit";
					break;
				case 'appListToJSON':
				case 'OrganizStruct':
					$post_data['u'] = $username;
					$post_data['p'] = $password;
					$post_data['code'] = md5 ( $username.'GridSoft' );
					$post_data['submit'] = "submit";
					break;
// 				case 'AcademyStruct':
// 					$post_data['u'] = $username;
// 					$post_data['p'] = $password;
// 					$post_data['code'] = md5 ( $username.'GridSoft' );
// 					$post_data['submit'] = "submit";
// 					break;
			}
	
			$url= $local.$url.'&hook='.$hook;
			$o="";
			foreach ($post_data as $k=>$v)
			{
				$o.= "$k=".urlencode($v)."&";
			}
			$post_data=substr($o,0,-1);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_URL,$url);
			//为了支持cookie
			curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			$result = curl_exec($ch);
	
		}else{
			$this->display();
		}
	}
	
	/**
	 * AutelanDemo
	 * @author：lihao
	 */
	public function AutelanServiceDemo(){
		$Autelanconfig = getPositionConfig();
		$this->assign('Autelanconfig', $Autelanconfig);
		$this->display();
	}
	/**
	 * AutelanDemo-->更新配置
	 * @author：lihao
	 */
	public function updateAutelanConfig(){
		$positionTimeInterval = $_POST['positionTimeInterval'];
		$open = $_POST['isopen'];
		$delBeforeTime = $_POST['delBeforeTime'];
		$type = $_POST['type'];
		$result = updatePositionConfig($positionTimeInterval, $open, $delBeforeTime, $type);
		redirect(U('home/Public/AutelanServiceDemo'));
	}
	/**
	 * AutelanDemo-->启动同步定位
	 * @author：lihao
	 */
	public function startPosition(){
		$result = getPositionInfoToDB();
	}
	
	/**
     * +----------------------------------------------------------
     * 根据uia的uid获取用户社区头像
     * +----------------------------------------------------------
     *
     * @author xiaobo.sun 2013-7-19
     *         +----------------------------------------------------------
     */
	public function getUserFace() {
		
	  $uid= !empty($_GET['uid'])?$_GET['uid']:0;
	  $size= !empty($_GET['size'])?$_GET['size']:'m';
      $userinfo = M('ucenter_user_link')->where("uc_uid='{$uid}'")->find();
      echo getUserFace($userinfo['uid'],$size,$size);
    }

    /**
     *
     * @param
     *        	获取频道列表及当前播出的节目
     * @author liman 2013-11-8
     *
     */
    function getPlayItemsdetail($tvUrl) {
        $w = $this->getWeek ();
        $tvUrl = $tvUrl . '&w=' . $w; // 获取当天的节目
        return $tvUrl;
    }
    /*
     * 判断今天是星期几 @author liman 2013-11-8 return	1代表星期一
     */
    function getWeek($unixTime = '') {
        $unixTime = is_numeric ( $unixTime ) ? $unixTime : time ();
        $weekarray = array (
            '7',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6'
        );
        return $weekarray [date ( 'w', $unixTime )];
    }

    /**
     * 计算字符串分割符左右二边的字符
     *
     * @param string $string
     * @param string $needle
     * @return array
     */
    function getLeftRigthStr($string, $needle) {
        $array = array ();
        $num = strpos ( $string, $needle ); // 计算分隔符左边字符的长度
        $array ['left'] = substr ( $string, 0, $num );
        $array ['right'] = substr ( $string, $num + 1 ); // 截取到最后
        return $array;
    }

    /**
     * [getPlayItems 获得电视节目预告]
     *
     * @param [type] $tvUrl
     *        	[description]
     * @return [type] [description]
     */
    public function getPlayItems($tvUrl) {
        $playItems = array ();
        $itemHtml = $this->curlGetHtml ( $tvUrl );
        preg_match_all ( "#r/>([^<]+)?<b#i", $itemHtml, $matches );
        array_shift ( $matches [1] ); // 去掉数组的第一个
        $playItems = $matches [1];
        return $playItems;
    }

    /**
     * [curl 带重试次数]
     *
     * @param [type] $url
     *        	[访问的url]
     * @param [type] $post
     *        	[$POST参数]
     * @param integer $retries
     *        	[curl重试次数]
     * @return [type] [description]
     */
    function curlGetHtml($url, $post = null, $retries = 3) {
        $ch = curl_init ();
        if (is_resource ( $ch ) === true) {
            curl_setopt ( $ch, CURLOPT_URL, $url );
            curl_setopt ( $ch, CURLOPT_FAILONERROR, true );
            curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt ( $ch, CURLOPT_REFERER, "http://wap.tvmao.com/" );
            curl_setopt ( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36" );
            if (isset ( $post ) === true) {
                curl_setopt ( $ch, CURLOPT_POST, true );
                curl_setopt ( $ch, CURLOPT_POSTFIELDS, (is_array ( $post ) === true) ? http_build_query ( $post, "", "&" ) : $post );
            }
            $result = false;
            while ( ($result === false) && (-- $retries > 0) ) {
                $result = curl_exec ( $ch );
            }
            curl_close ( $ch );
        }
        return $result;
    }

    /**
     * 在电视猫上获取节目列表
     * snail 20131220
     * @param str $url_c 节目标识
     * @param str $live_name 节目名称
     * @param array $map
     */
    private function playList_tvtvmao($url_c,$live_name,$map){
        $url = "http://wap.tvmao.com/program.jsp?c=" . $url_c;
        $tvUrl = $this->getPlayItemsdetail ( $url ); // 将频道地址重新组合，以便获取节目表单
        $playItems = $this->getPlayItems ( $tvUrl ); // 节目列表数组形式
        $w = $this->getWeek ();
        //删除两天以前的数据记录
        //$this->delete_playlist();

        foreach ( $playItems as $key => $value ) {
            $map ['list'] = $value;
            $list = $value;
            $listS = $this->getLeftRigthStr ( $list, ' ' );
            $map ['p_time'] = $listS ['left'];
            $map ['p_name'] = $listS ['right'];
            $map ['week'] = $w;
            $map ['live_name'] = $live_name;
            $res = M ( 'livePlaylist' )->add ( $map );
        }

    }
    /**
     * 在cctv上获取节目列表
     * snail 20131220
     * @param str $url_c 节目标识
     * @param str $live_name 节目名称
     * @param array $map
     */
    private function playList_cctv($url_c,$live_name,$map){
        $url='http://tv.cntv.cn/api/epg/info?d='.date("Ymd").'&c='.$url_c;
        $playItems= file_get_contents($url);
        $playItems=json_decode($playItems, true);
        $w = $this->getWeek ();
        //删除两天以前的数据记录
        //$this->delete_playlist();

        foreach ( $playItems['epg']['program'] as $key => $value ) {
            $map ['p_time'] = date('H:i',$value['st']);
            $map ['p_name'] = $value['t'];
            $map ['list'] = $map ['p_time'] . " " .$map ['p_name'];
            $map ['week'] = $w;
            $map ['live_name'] = $live_name;
            $res = M ( 'livePlaylist' )->add ( $map );
        }

    }

    /**
     *
    +----------------------------------------------------------
     * 从互联网上获取节目表单
    +----------------------------------------------------------
     * @param int $live_id 节目id
     * @return int 成功获取的频道个数
     * @author Snail 2013-12-30
    +----------------------------------------------------------
     */
    public function getAllitems(){
        $successNum=0;
        $this->delete_playlist();
        $where = " DATE_FORMAT(FROM_UNIXTIME(gTime),'%Y-%m-%d')!= DATE_FORMAT(NOW(),'%Y-%m-%d') or gTime is null";
        $result=D('Live')->field('id,name,playlist,cctv_playlist,playlist_type')->where($where)->select();
        echo D('Live')->getLastSql();
        dump($result);
        foreach ($result as $key=>$val){
            unset($updata);
            $live_name=$val['name'];
            $playlist_type=$val['playlist_type'];
            $map ['time'] = date ( 'Y-m-d' );
            $map ['live_id'] = $val['id'];
            $updata['gTime']=time();
            if ($playlist_type=='tvmao'){
                $url_c=$val['playlist'];
                if(empty($url_c)){
                    D('Live')->where('id='.$val['id'])->save($updata);
                    //array_push($liveid,$val['id']);
                    continue ;
                }
                $this->playList_tvtvmao($url_c,$live_name,$map);
            }elseif ($playlist_type=='cctv'){
                $url_c=$val['cctv_playlist'];
                if(empty($url_c)){
                    D('Live')->where('id='.$val['id'])->save($updata);
                    //array_push($liveid,$val['id']);
                    continue ;
                }
                $this->playList_cctv($url_c,$live_name,$map);
            }
            $successNum++;
            D('Live')->where('id='.$val['id'])->save($updata);

        }
        return $successNum;
    }


    function delete_playlist(){
        $foredata = date("Y-m-d",strtotime("-1 day"));
        M ( 'livePlaylist' )->where("time < '".$foredata."'")->delete();
    }

}



















