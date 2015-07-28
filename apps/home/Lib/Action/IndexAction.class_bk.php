<?php
class IndexAction extends Action{
	
	public function index() {
		/*if (service('Passport')->isLogged())
			redirect(U('home/User/index'));
		else*/
			$this->showIndex();
	}
	
	public function main(){
		$this->showIndex();
	}

	private function showIndex(){
		unset($_SESSION['sina'], $_SESSION['key'], $_SESSION['douban'], $_SESSION['qq'],$_SESSION['open_platform_type']);

		//验证码
		$opt_verify = $this->_isVerifyOn('login');
		if ($opt_verify) {
			$this->assign('login_verify_on', $opt_verify);
		}

		$data['email'] = t($_REQUEST['email']);
		$data['uid']   = t($_REQUEST['uid']);
		$uids = array();
		
		// 注册用户统计
		$data['usercount'] = D('User')->count();
		
		// 视频推荐
		$data['hot_video']  = D('Video', 'video')->getTopVideo();
		$data['hot_video'] = array_slice($data['hot_video'], 0, 4);
		
		// 学生动态信息
		$data['dynainfo'] = uc_get_users_by_identity();
		foreach ($data['dynainfo'] as &$r){
			$map['uc_uid'] = array('in',$r['uc_ids']);
			$uids = M('ucenter_user_link')->field('uid')->where($map)->findAll();
			$return_uids = array();
			arrayFormat($uids,$return_uids);
			$ids = implode(',', $return_uids);
			if(!empty($ids)){
				$r['photos'] = M()->query("SELECT * FROM (SELECT * FROM `".C('DB_PREFIX')."photo` WHERE (`userId` IN (".$ids.")) ORDER BY cTime DESC) temp GROUP BY userId ORDER BY cTime DESC LIMIT 4");
				$r['weibos'] = M()->query("SELECT * FROM (SELECT * FROM `".C('DB_PREFIX')."weibo` WHERE (`uid` IN (".$ids.")) ORDER BY ctime DESC) temp GROUP BY uid ORDER BY ctime DESC LIMIT 5");
			}
			foreach ($r['weibos'] as &$obj){
				$uc_uid = M('ucenter_user_link')->field('uc_uid')->where('uid='.$obj['uid'])->find();
				//$deptinfo = uc_dept_get_uid($uc_uid['uc_uid']);
				//$obj['deptname'] = $deptinfo['DepartName'];
			}
		}

		// 正在热议 1小时缓存
		$data['hot_topic'] = D('Topic', 'weibo')->getHot();

		// 人气推荐
		$data['hot_user']  = D('Follow', 'weibo')->getTopFollowerUser();
		$data['hot_user'] = array_slice($data['hot_user'], 0, 8);
		$uids = array_merge($uids, getSubByKey($data['hot_user'], 'uid'));

		// 正在发生 (原创的文字微广播)
		$data['lastest_weibo'] = D('Operate', 'weibo')->getLastWeibo();
		$data['lastest_weibo'] = array_slice($data['lastest_weibo'], 0, 6);
		$uids = array_merge($uids, getSubByKey($data['lastest_weibo'], 'uid'));
		$this->assign('since_id', empty($data['lastest_weibo']) ? 0 : $data['lastest_weibo'][0]['weibo_id'] );

		// 原创的图片微广播
		$data['pic_weibo'] = S('S_login_pic_weibo');
		if(empty($data['pic_weibo'])) {
			$map['transpond_id'] = 0;
			$map['type']		 = 1;
			$map['isdel'] 		 = 0;
			$data['pic_weibo'] = D('Operate', 'weibo')->where($map)->order('weibo_id DESC')->limit(3)->findAll();
			S('S_login_pic_weibo', $data['pic_weibo'], 3600);
		}

		$uids = array_merge($uids, getSubByKey($data['pic_weibo'], 'uid'));
		foreach ($data['pic_weibo'] as $k => $v){
			$imageData = unserialize($v['type_data']);
			if(isset($imageData[0])) {
				$data['pic_weibo'][$k]['type_data'] = $imageData[0];
			} else {
				$data['pic_weibo'][$k]['type_data'] = $imageData;
			}
		}

		D('User', 'home')->setUserObjectCache(array_flip(array_flip($uids)));
		
		// 获取用户的信息
		$data['userInfo'] = $_SESSION['userInfo'];
		$data['ucInfo'] = $_SESSION['ucInfo'];
		
		//孙晓波于2013/6/24添加新版首页背景图片列表 start
		$data['backgroup'] = array(
				array(
					'title'=>'',
					'img'=>__THEME__.'/desktop/images/index/1.jpg'
				),
				array(
						'title'=>'',
						'img'=>__THEME__.'/desktop/images/index/2.jpg'
				),
				array(
						'title'=>'',
						'img'=>__THEME__.'/desktop/images/index/3.jpg'
				),
				array(
						'title'=>'',
						'img'=>__THEME__.'/desktop/images/index/4.jpg'
				),
				array(
						'title'=>'',
						'img'=>__THEME__.'/desktop/images/index/5.jpg'
				)
		);
		//孙晓波于2013/6/24添加新版首页背景图片列表 start
		
		
		//得到吉林建筑大学rss
		//得到远程基层动态
		$data['jcdt'] = get_rss('http://www.jlju.edu.cn/index.php?m=content&c=rss&rssid=19',3,20);
		//得到远程基层动态
		$data['jdxw'] = get_rss('http://www.jlju.edu.cn/index.php?m=content&c=rss&rssid=11',1,20);
		//得到远程公示通知
		$data['gstz'] = get_rss('http://www.jlju.edu.cn/index.php?m=content&c=rss&rssid=10', 4, 20);
		
		//登录成功时要跳转的链接
		$_SESSION['refer_url'] = U('home/User/index');
		$this->assign($data);
		$this->assign('regInfo',model('Xdata')->lget('register'));
		$this->display();
	}

	private function _isVerifyOn($type='login'){
		// 检查验证码
		if($type!='login' && $type!='register') return false;
		$opt_verify = $GLOBALS['ts']['site']['site_verify'];
		return in_array($type, $opt_verify);
	}

	/**  前台 应用管理  **/
	public function addapp() {
		$dao = model('App');
		//获取用户应用分组查看权限
		$_groups = $this->getUserGroupIds();
		$_gid = 1;
		if (is_array($_groups))
			$_gid = $_groups[0];
		$groups=$_GET['groups']?$_GET['groups']:$_gid;
		if($groups)
		$all_apps  = $dao->where("`status`<>0 AND FIND_IN_SET('$groups',`group_id`)")->order('display_order ASC')->findPage($limit);
		else $all_apps  = $dao->getAllAppByPage();
		
		$group = "";
		if (is_array($_groups)){
	        foreach($_groups as $val){
	        	if(empty($group)){
	        		$group = "FIND_IN_SET('$val',group_id)";	
	        	}else{
	        		$group .= " or FIND_IN_SET('$val',group_id)";
	        	}
			}
		}else{
			$group = "FIND_IN_SET('$group',group_id)";
		}
		
		$group=model('Appgoup')->field('group_id,group_name')->where($group)->order('display_order ASC')->findAll();
		
		$installed = isset($_SESSION['installed_app_user_'.$this->mid]) ? $_SESSION['installed_app_user_'.$this->mid] :M('user_app')->where('`uid`='.$this->mid)->field('app_id')->findAll();
		$installed = getSubByKey($installed, 'app_id');
		$this->assign($all_apps);
		$this->assign('groups',$groups);
		$this->assign('group',$group);
		$this->assign('installed', $installed);
		$this->setTitle(L('add_apps'));
		$this->display();
	}
	
	public function editapp() {
		// 重置用户的漫游应用的缓存
		global $ts;
		if ($ts['site']['my_status'])
			model('Myop')->unsetAllInstalledByUser($this->mid);
			
		//获取用户应用分组查看权限
		$_groups = $this->getUserGroupIds();
		$_gid = 1;
		if (is_array($_groups))
			$_gid = $_groups[0];
			
		$groups=$_GET['groups']?$_GET['groups']:$_gid;
		
		//获取与用户相关的应用分组
		$group = "";
		if (is_array($_groups)){
	        foreach($_groups as $val){
	        	if(empty($group)){
	        		$group = "FIND_IN_SET('$val',group_id)";	
	        	}else{
	        		$group .= " or FIND_IN_SET('$val',group_id)";
	        	}
			}
		}else{
			$group = "FIND_IN_SET('$group',group_id)";
		}
		$grouplist=model('Appgoup')->field('group_id,group_name')->where($group)->order('display_order ASC')->findAll();	
		
		$this->assign('groups',$groups);
		$this->assign('group',$grouplist);
		$this->assign('has_order', array('local_app', 'myop_app'));
		$this->setTitle(L('manage_apps'));
		$this->display();
	}
	
	public function install() {
		$app = isset($_GET['app_name']) ? 
			   model('App')->getAppDetailByName(t($_GET['app_name'])) :
			   model('App')->getAppDetailById(intval($_GET['app_id']));
		if (!$app || $app['status'] == 0)
			$this->error(L('app_notexist'));
			
		$this->assign($app);
		$this->setTitle(''.L('install').'"' . $app['app_alias'] . '"'.L('app'));
		$this->display();
	}
	
	public function doInstall() {
		$_GET['app_id'] = intval($_GET['app_id']);
		$app = model('App')->getAppDetailById($_GET['app_id']);
		if (!app || $app['status'] == 0)
			$this->error(L(app_notexist));
			
		if (model('App')->addAppForUser($this->mid, $_GET['app_id'])) {
			model('App')->unsetUserInstalledApp($this->mid);
			$this->assign('jumpUrl', U($app['app_name'].'/'.$app['app_entry']));
			$this->success(L('install_success'));
		} else {
			$this->error(L('install_error'));
		}
	}
	
	public function uninstall() {
		$_GET['app_id'] = intval($_GET['app_id']);
		if (model('App')->where('`app_id`='.$_GET['app_id'])->getField('status') == '1')
			$this->error(L('default_app'));
		
		if (model('App')->removeAppForUser($this->mid, $_GET['app_id'])) {
			model('App')->unsetUserInstalledApp($this->mid);
			$this->assign('jumpUrl', U('home/Index/editapp'));
			$this->success(L('uninstall_success'));
		} else {
			$this->error(L('uninstall_error'));
		}
	}
	
	public function doOrder() {
		global $ts;
		$has_order  = array('local_app', 'myop_app');
		$table_name = array('local_app'=>'user_app', 'myop_app'=>'myop_userapp');
		$order_field_name = array('local_app'=>'display_order', 'myop_app'=>'displayorder');
		$app_id_name	  = array('local_app'=>'app_id', 'myop_app'=>'appid');
		
		// 现在的顺序 array('app_id'=>'order')
		$now_order = array();
		foreach ($has_order as $v)
			foreach ($ts['user_app'][$v] as $app)
				$now_order[$v][$app['app_id']] = $app['display_order'];
		
		$has_changed = false;
		foreach ($_POST as $field => $v) {
			if ( !in_array($field, $has_order) )
				continue ;
			foreach ($v as $order => $app_id) {
				$order  = intval($order);
				$app_id = intval($app_id);

				// 只更新有变化的顺序号
				if ($order == $now_order[$field][$app_id])
					continue ;
				// 提交修改
				if ( M($table_name[$field])->where("`{$app_id_name[$field]}`='$app_id' AND `uid`='{$this->mid}'")->setField($order_field_name[$field], $order) )
					$has_changed = true;
				else
					$this->error(L('save_error'));
			}
		}
		
		// 重置缓存
		model('App')->unsetUserInstalledApp($this->mid);
		global $ts;
		if ($ts['site']['my_status'])
			model('Myop')->unsetAllInstalledByUser($this->mid);
		
 		if ($has_changed)
			$this->success(L('save_success'));
		else
			$this->error(L('order_nochange'));
	}
}
