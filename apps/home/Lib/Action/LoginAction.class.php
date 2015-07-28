<?php
class LoginAction extends Action{
	public function index(){
	    $data=$this->_getRequestData();
	    $this->assign('data',$data);
	    $this->display();
	}
	/**
	 +----------------------------------------------------------
	 * ajax登录方法
	 +----------------------------------------------------------
	 * @author 小波 2013-6-23
	 +----------------------------------------------------------
	 */
	public function doAjaxLogin(){

	
		$username = trim($_POST['email']);
		$password =	trim($_POST['password']);
	
		Addons::hook('public_before_doajaxlogin',$_POST);
	
		if(!$password){
			$return['message']	=	L('password_notnull');
			$return['status']	=	0;
			exit(json_encode($return));
		}
	
		$result = service('Passport')->loginLocal($username,$password, intval($_POST['remember']) === 1);
	
	
		if($result){
			$return['message']	=	L('login_success');
			$return['status']	=	1;
			if(UC_SYNC && $uc_user[0])
				$return['callback']	=	uc_user_synlogin($uc_user[0]);
			$this->doSuccess();
			
		}else{
			$error_message = service('Passport')->getLastError();
			$return['message']	=	$error_message;
			$return['status']	=	0;
			$this->doError();
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
	
	/**
	 *
	 +----------------------------------------------------------
	 * 登录成功时执行的操作
	 +----------------------------------------------------------
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author Snail 2013-11-27
	 +----------------------------------------------------------
	 */
	private function doSuccess(){
	    
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 登录失败时执行的操作
	 +----------------------------------------------------------
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author Snail 2013-11-27
	 +----------------------------------------------------------
	 */
	private function doError(){
		 
	}
	
	/**
	 * 
	 +----------------------------------------------------------
	 * 获取页面传递的相关参数
	 +----------------------------------------------------------
	 * @return array $data
	 * @author Snail 2013-11-27
	 +----------------------------------------------------------
	 */
	private function _getRequestData(){
		$data['wlanuserip']=$_REQUEST['wlanuserip'];
		$data['wlanacip']=$_REQUEST['wlanacip'];
		$data['ssid']=$_REQUEST['sid'];
		$data['wlanapmac']=$_REQUEST['wlanapmac'];
		return $data;
	}
	
	

	
}




















