<?php
class AppsAction extends AdministratorAction {

	protected $_host_type;

	public function _initialize() {
		parent::_initialize();
		$this->_host_type = array('0'=>'本地应用', '1'=>'远程应用');
	}

	private function __getAppInfo($path_name, $using_lowercase = true) {
		$filename = SITE_PATH . '/apps/' . $path_name . '/Appinfo/info.php';
		if ( is_file($filename) ) {
			$info = include_once $filename;
			$info['HOST_TYPE_ALIAS']	= $this->_host_type[$info['HOST_TYPE']];
			$info['APP_ALIAS']			= $info['NAME'];
			$info['PATH_NAME'] 			= $path_name;
			$info['APP_NAME']			= $path_name;
			$info['CONTRIBUTOR_NAME']	= $info['CONTRIBUTOR_NAMES'];
			return $using_lowercase ? array_change_key_case($info) : array_change_key_case($info,CASE_UPPER);
		}else {
			return NULL;
		}
	}

	// 安装应用 + 编辑应用
	private function __updateApp($method, $info) {
		if ( !in_array($method, array('add','save')) ) {
			return false;
		}

		$_LOG['uid'] = $this->mid;

		if ($method == 'add') {
			$data['host_type']					= intval($info['host_type']);
			$data['homepage_url']				= $info['homepage_url'];
			$data['sidebar_support_submenu']	= intval($info['sidebar_support_submenu']);
			$data['author_name']				= $info['author_name'];
			$data['author_email']				= $info['author_email'];
			$data['author_homepage_url']		= $info['author_homepage_url'];
			$data['contributor_name']			= $info['contributor_names'];
			$data['release_date']				= $info['release_date'];
			$data['last_update_date']			= $info['last_update_date'];

			$_LOG['type'] = '1';
			$log_data[] = '应用 - 安装应用 ';

		}else {

			$_LOG['type'] = '3';
			$log_data[] = '应用 - 编辑应用 ';

			$data['app_id']						= intval($_POST['app_id']);
		}

		$data['app_name']					= $method=='add' ? t($_POST['path_name']) : t($_POST['app_name']);
		$data['group_id']                   = trim(implode(',',$_POST['group_id']));
		$data['app_alias']					= t($_POST['app_alias']);
		$data['description']				= htmlspecialchars($_POST['description']);
		$data['status']						= intval($_POST['status']);
		$data['category']					= t($_POST['category']);
		$data['app_entry']					= t($_POST['app_entry']);
		$data['icon_url']					= t($_POST['icon_url']);
		$data['large_icon_url']				= t($_POST['large_icon_url']);
		$data['admin_entry']				= t($_POST['admin_entry']);
		$data['statistics_entry']			= t($_POST['statistics_entry']);
		$data['sidebar_title']				= t($_POST['sidebar_title']);
		$data['sidebar_entry']				= t($_POST['sidebar_entry']);
		$data['sidebar_icon_url']			= t($_POST['sidebar_icon_url']);
		$data['sidebar_is_submenu_active']	= intval($_POST['sidebar_is_submenu_active']);
		$data['ctime']						= time();
		$data['app_type']						= t($_POST['app_type']);

		if( $method != 'add' ){
			$appinfo = $this->__getAppInfo($data['app_name']);
			$str = 'name,host_type,homepage_url,release_date,last_update_date,sidebar_support_submenu,author_name,author_email,author_homepage_url,contributor_names,host_type_alias,path_name';
			$arr = explode(',', $str);
			foreach($arr as $v){
				unset($appinfo[$v]);
			}
			$log_data[] = $appinfo;
		}
		$editlater = $data;
		unset($editlater['ctime']);
		$log_data[] = $editlater;
		$_LOG['data'] = serialize($log_data);
		$_LOG['ctime'] = time();
		M('AdminLog')->add($_LOG);

		$res = model('App')->$method($data);
		if ($res && $method = 'add') {
			//为排序方便，将order = id
			model('App')->where('`app_id`='.$res)->setField('display_order', $res);
		}
		return $res;
	}

	public function applist() {
		$installed = model('App')->getAllAppByPage();
		$this->assign($installed);
		$this->display();
	}

	public function install() {
		$uninstalled = array();
		$installed 	 = model('App')->getAllApp('app_name');
		$installed   = getSubByKey($installed, 'app_name');

		//默认应用,不能安装卸载.
		$installed = array_merge($installed, C('DEFAULT_APPS'));

		require_once SITE_PATH . '/addons/libs/Io/Dir.class.php';
		$dirs	= new Dir(SITE_PATH.'/apps/');
		$dirs	= $dirs->toArray();
		foreach($dirs as $v){
			if ( $v['isDir'] && !in_array($v['filename'], $installed) ) {
				if ( $info = $this->__getAppInfo($v['filename']) ) {
					$uninstalled[]	= $info;
				}
			}
		}
		$this->assign('uninstalled', $uninstalled);
		$this->display();
	}

	public function preinstall() {
		$info = $this->__getAppInfo($_GET['path_name']);
		$info['groupid']=model('AppGoup')->order('display_order ASC')->findAll();
		
		$this->assign($info);
		$this->display('edit');
	}

	public function doInstall() {
		$_POST['path_name'] = t($_POST['path_name']);
		$info = $this->__getAppInfo($_POST['path_name']);

		if (!$info)
			$this->error('参数错误');

		if (model('App')->isAppNameExist($_POST['path_name']))
			$this->error('应用已存在');

		$install_script = SITE_PATH . "/apps/{$info['path_name']}/Appinfo/install.php";
		if (is_file($install_script))
			include_once $install_script;

		if (!$this->__updateApp('add', $info))
			$this->error('安装失败');

		model('App')->unsetSiteDefaultApp();
		model('App')->unsetUserInstalledApp($this->mid);

		// 设置版本号
		model('Xdata')->put("{$info['app_name']}:version_number", $info['version_number'], true);

		$this->assign('jumpUrl', U('admin/Apps/install'));
		$this->success('安装成功');
	}

	public function edit() {
		$info = model('App')->getAppDetailById(intval($_GET['app_id']));
		$info['path_name']			= $info['app_name'];
		$info['host_type_alias']	= $this->_host_type[$info['host_type']];
		
		$info['groupid']=model('AppGoup')->order('display_order ASC')->findAll();
		$this->assign($info);
		$this->display();
	}

	public function doEdit() {
		if (! is_file(SITE_PATH . '/apps/' . $_POST['app_name'] . '/Appinfo/info.php') ) {
			$this->error('应用名称“'.$_POST['app_name'].'”错误，请确认apps目录下存在该应用');
		}
		if ( model('App')->isAppNameExist($_POST['app_name'], intval($_POST['app_id'])) ) {
			$this->error('应用名称“'.$_POST['app_name'].'”已存在');
		}
		
		if ( ! $this->__updateApp('save') ) {
			$this->error('保存失败');
		}else {
			model('App')->unsetSiteDefaultApp();
			model('App')->unsetUserInstalledApp($this->mid);

			$this->assign('jumpUrl', U('admin/Apps/applist'));
			$this->success('保存成功');
		}
	}

	public function uninstall() {
		$_POST['app_id'] = intval($_GET['app_id']);
		
		
		//2013/06/17朱长奇修改原始应用卸载，注释为源代码，下册修改为新添加
		$app_name = model('App')->where('`app_id`='.$_POST['app_id'])->getField('app_name');
		if ( ! $app_name ) {
			$this->error('应用不存在');
		}

		//查询大桌面基础APP表appid
		$appid=M('dsk_apps')->where("oid=".$_POST['app_id'])->getField('appid');
		
		//删除大桌面相关app信息
		M('dsk_apps')->where("oid=".$_POST['app_id']." and idtype='app'")->delete();
		
		//删除该app用户安装信息
		M('dsk_icos')->where("oid=".$appid)->delete();
		
		$uninstall_script = SITE_PATH . "/apps/{$app_name}/Appinfo/uninstall.php";
		if ( is_file($uninstall_script) ) {
			include_once $uninstall_script;
		}

		$_LOG['uid'] = $this->mid;
		$_LOG['type'] = '2';
		$data[] = '应用 - 卸载应用';
		$data[] =  $this->__getAppInfo($app_name);
		$_LOG['data'] = serialize($data);
		$_LOG['ctime'] = time();
		M('AdminLog')->add($_LOG);

		if ( ! model('App')->deleteApp($_POST['app_id']) ) {
			$this->error('卸载失败');
		}

		model('App')->unsetSiteDefaultApp();
		model('App')->unsetUserInstalledApp($this->mid);

		$this->assign('jumpUrl', U('admin/Apps/applist'));
		$this->success('卸载成功');
	}

	public function doSetStatus() {
		$post['app_id'] = intval($_POST['app_id']);
		$post['status'] = intval($_POST['status']);

		$_LOG['uid']  = $this->mid;
		$_LOG['type'] = '3';
		$data[] = '应用 - 设置状态';
		$data[] = $_POST;
		$_LOG['data'] = serialize($data);
		$_LOG['ctime'] = time();
		M('AdminLog')->add($_LOG);

		if (M('app')->save($post)) {
			model('App')->unsetSiteDefaultApp();
			model('App')->unsetUserInstalledApp($this->mid);
			model('App')->resetAppCache($_POST['app_id']);
			echo '1';
		}else {
			echo '0';
		}
	}
	//应用排序
	public function doAppOrder() {
		$_POST = $_REQUEST;
		
		$_POST['app_id'] = intval($_POST['app_id']);
		$_POST['baseid'] = intval($_POST['baseid']);
		if ( $_POST['app_id'] <= 0 || $_POST['baseid'] <= 0 ) {
			echo 0;
			exit;
		}

		$_LOG['uid'] = $this->mid;
		$_LOG['type'] = '3';
		$data[] = '应用 - 设置排序';
		$data[] = $_POST;
		$_LOG['data'] = serialize($data);
		$_LOG['ctime'] = time();
		M('AdminLog')->add($_LOG);

		$dao = model('App');
		$res = $dao->getAppDetailById( array($_POST['app_id'], $_POST['baseid']), 'app_id,display_order,app_name' );
		if ( count($res) < 2 ) {
			echo 0;
			exit; 
		}

		//转为结果集为array('id'=>'order')的格式
    	foreach($res as $v) {
    		$order[$v['app_id']] = intval($v['display_order']);
    	}
    	unset($res);

    	//交换order值
    	$res = 		   $dao->where('`app_id`=' . $_POST['app_id'])->setField( 'display_order', $order[$_POST['baseid']] );
    	if($res){
    	 	$res = $dao->where('`app_id`=' . $_POST['baseid'])->setField( 'display_order', $order[$_POST['app_id']] );
    	}

    	model('App')->unsetSiteDefaultApp();
		model('App')->unsetUserInstalledApp($this->mid);

    	if($res) echo 1;
    	else	 echo 0;
	}

	/***
	 * 应用分组展示
	 */
	public function appGroup() {
		$limit = 20;
		$data = model('AppGoup')->order('display_order ASC')->findPage($limit);
		$this->assign('data',$data);
		$this->display('appgroup');
	}

	/***
	 * 添加应用分组
	 */
	public function modifyGroup() {
		$_GET['id'] 	&& $map['group_id'] 	 = intval($_GET['id']);
		if($map['group_id']){
			$this->assign('do','modify');
			$data = model('AppGoup')->where($map)->find();
			if($data['operation']==1)$this->error('系统应用不能更改！');
			$this->assign('data',$data);
		}else{
			$this->assign('do','add');
		}
		//获取所有应用列表
		//$apps=model('App')->field('app_id,group_id,app_name,app_alias,icon_url,large_icon_url,admin_entry')->where('group_id <> 1')->findAll();
		$apps=model('App')->field('app_id,group_id,app_name,app_alias,icon_url,large_icon_url,admin_entry')->findAll();
		$this->assign('apps',$apps);
		
		$this->assign('isadd',1);
		$this->display('modifygroup');
	}
	/***
	 * 执行添加修改的功能
	 */
	public function doModifyGroup(){

		$action = $_POST['do'];

		$_POST['group_name']	&&	$item['group_name']  	=$_POST['group_name'];
		$_POST['display_order']	&&	$item['display_order']  =intval($_POST['display_order']);
		$_POST['description'] 	&&	$item['description'] 	=$_POST['description'];
		$file = X('Xattach')->upload($attach_type='large_icon_url');
		$item['mtime']			= time();
		foreach ($file['info'] as $k=>$v) {
			$item['large_icon_url'] = $v['savepath'].$v['savename'];
		}	
		if($action=='add'){
			$item['datadescribe']	=	"应用分组";
			$item['datatype']		=	"AppGroup";
			$item['ctime']			= time();
			$row = model('AppGoup')->add($item);
		}else{
			$_POST['id'] 	&& $map['group_id'] 	 = intval($_POST['id']);
			$row = model('AppGoup')->where($map)->save($item);
		}
		if($row){
			//获取分组的id	
			$groupid = empty($_POST['id'])?model('AppGoup')->getLastInsID():$_POST['id'];
			//开始更新APP的分组状态
			unset($map);
			$map['app_id'] = array('in',$_POST['apps']);
			//$apps = model('App')->field('app_id,group_id')->where($map)->findAll();

			$_group['group_id'] = $groupid;
			/**
			 *1、先将所有的当前分类更新到默认分类
			 *2、然后更新选中的分类为当前分类
			 */

			if(count($_group)){
				/**
				 * 找到默认应用
				 */
				$defaultapp = model('AppGoup')->where('operation = 1')->field('group_id')->find();
				model('App')->where($_group)->save($defaultapp);
				/**
				 * 把当前获取的应用集里面的信息更新到当前分类
				 */
				model('App')->where($map)->save($_group);
			}

			/*
			foreach ($apps as $app){
				/*
				$_group = array();
				if(!in_array($groupid,explode(',',$app['group_id']))){
					if(empty($app['group_id'])){
						$_group['group_id'] = $groupid;
					}else{
						$_group['group_id'] = $app['group_id'] . ',' . $groupid;
					}
	            }
				*/
				/*
				$_group['group_id'] = $groupid;

	            //如果应用分组有更新，则
	            if(count($_group)){
	            	unset($map);
	            	$map['app_id'] = $app['app_id'];
	            	model('App')->where($map)->save($_group);
	            }
			}
			*/
			$jumpUrl = $_POST['jumpUrl'] ? $_POST['jumpUrl'] : U('admin/Apps/appGroup');
			$this->assign('jumpUrl', $jumpUrl);
			$this->success('');
		}else{
			$this->error('');
		}

		/*$row = model('AppGoup')->insert($item);

		if($row){
			$jumpUrl = $_POST['jumpUrl'] ? $_POST['jumpUrl'] : U('admin/Apps/appGroup');
			$this->assign('jumpUrl', $jumpUrl);
			$this->success();
		}else{
			$this->error();
		}*/
	}
	//应用分组排序
	public function doGroupAppOrder() {
		$groupid = intval($_POST['group_id']);
		$baseid = intval($_POST['baseid']);
		if ( $groupid <= 0 || $baseid <= 0 ) {
			echo 0;
			exit;
		}
		$dao = model('AppGoup');
		$res = $dao->where('group_id in ('.$groupid.','.$baseid.')')->findAll();
		if ( count($res) < 2 ) {
			echo 0;
			exit;
		}
		
		//转为结果集为array('id'=>'order')的格式
    	foreach($res as $v) {
    		$order[$v['group_id']] = intval($v['display_order']);
    		$operation[$v['group_id']] = intval($v['operation']);
    	}
    	unset($res);
		if($operation[$groupid]!=1&&$operation[$baseid]!=1)
    	//交换order值
		{$res = $dao->where('`group_id`=' . $groupid)->setField( 'display_order', $order[$baseid] );
    	if($res){
    	 	$res = $dao->where('`group_id`=' . $baseid)->setField( 'display_order', $order[$groupid] );
    	}
		
    	if($res) echo 1;
    	else echo 0;
		}else echo 0;
    	

	}
	
	//删除分组
	public function doDeleteGroup()
	{
		$map['group_id'] = array('in', t($_POST['ids']));
		if($map['group_id'])
		{
			$row = model('AppGoup')->where($map)->find();
			if($row['operation']!=1)
			{
				$rows = model('AppGoup')->where($map)->delete();

				/**
				 * author:rickeryu
				 * 将删除的应用组里面的app更新成默认的app
				 */
				$defaultapp = model('AppGoup')->where('operation = 1')->field('group_id')->find();

				unset($map);
				$map['group_id'] = array('in',$_POST['ids']);
				model('App')->where($map)->save($defaultapp);

				/*
				if(is_array(t($_POST['ids']))){
					//就要删除的app的应用放置到默认分组里面
					foreach (t($_POST['ids']) as $gid){
						model('App')->execute("UPDATE " . C('DB_PREFIX') . "app SET group_id = REPLACE(REPLACE(group_id,'" . $gid . ",',''),'," . $gid . "','') WHERE group_id<>1");
					}
				}else{
					$gid = t($_POST['ids']);
					model('App')->execute("UPDATE " . C('DB_PREFIX') . "app SET group_id = REPLACE(REPLACE(group_id,'" . $gid . ",',''),'," . $gid . "','') WHERE group_id<>1");
				}
				*/


				if($rows) echo '1';
				else  echo '0';
				
			}else echo '0';
		}else 
		{
				echo '0';
		}
		
		
	}
	
	/***
	 * 应用分组浏览权限设置
	 */
	public function appSetPrivate() {
		$data['id'] = $_REQUEST['id'];
		if ($_POST['id']){
			$group = implode(',', $_POST['group']);
			$map['group_id'] = $_POST['id'];
			$row = model('AppGoup')->where($map)->setField('role_private',$group);
			if ($row)
				exit('1');
			else 
				exit('0');
		}
		$data['result']= model('AppGoup')->where("group_id = '".$data['id']."'")->find();
		$data['groups'] = model('UserGroup')->getUserGroupByMap();
		$this->assign($data);
		$this->display('appsetprivate');
	}
	
}
?>